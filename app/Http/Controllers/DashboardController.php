<?php

namespace App\Http\Controllers;

use App\Models\Installation;
use App\Models\Component;
use App\Models\Inspection;
use App\Models\MaintenanceSchedule;
use App\Models\ReplacementForecast;
use App\Models\Measurement;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard.
     */
    public function index()
    {
        $today = Carbon::today();

        /*
        |--------------------------------------------------------------------------
        | Basic Statistics
        |--------------------------------------------------------------------------
        */

        $totalInstallations = Installation::count();

        $totalComponents = Component::count();


        /*
        |--------------------------------------------------------------------------
        | Pending / Overdue Inspections
        |--------------------------------------------------------------------------
        */

        $pendingInspections = Inspection::whereDate(
            'next_inspection_date',
            '<=',
            $today
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Maintenance Due
        |--------------------------------------------------------------------------
        */

        $maintenanceDue = MaintenanceSchedule::whereDate(
            'next_due_date',
            '<=',
            $today
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Component Condition
        |--------------------------------------------------------------------------
        */

        $conditionData = [
            'Excellent' => Component::where(
                'current_condition',
                'Excellent'
            )->count(),

            'Good' => Component::where(
                'current_condition',
                'Good'
            )->count(),

            'Fair' => Component::where(
                'current_condition',
                'Fair'
            )->count(),

            'Poor' => Component::where(
                'current_condition',
                'Poor'
            )->count(),

            'Critical' => Component::where(
                'current_condition',
                'Critical'
            )->count(),
        ];


        /*
        |--------------------------------------------------------------------------
        | Maintenance Statistics
        |--------------------------------------------------------------------------
        */

        $totalMaintenance = MaintenanceSchedule::count();

        $scheduledMaintenance = MaintenanceSchedule::whereDate(
            'next_due_date',
            '>',
            $today
        )->count();

        $dueMaintenance = MaintenanceSchedule::whereDate(
            'next_due_date',
            '<=',
            $today
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Recent Inspections
        |--------------------------------------------------------------------------
        */

        $recentInspections = Inspection::with([
            'installation',
            'inspector'
        ])
            ->latest('inspection_date')
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Replacement Risk
        |--------------------------------------------------------------------------
        */

        $riskData = [
            'Low' => ReplacementForecast::where(
                'risk_level',
                'Low'
            )->count(),

            'Medium' => ReplacementForecast::where(
                'risk_level',
                'Medium'
            )->count(),

            'High' => ReplacementForecast::where(
                'risk_level',
                'High'
            )->count(),
        ];


        /*
        |--------------------------------------------------------------------------
        | Send Data To Dashboard
        |--------------------------------------------------------------------------
        */

        return view('dashboard', compact(
            'totalInstallations',
            'totalComponents',
            'pendingInspections',
            'maintenanceDue',
            'conditionData',
            'totalMaintenance',
            'scheduledMaintenance',
            'dueMaintenance',
            'recentInspections',
            'riskData'
        ));
    }


    /**
     * Display degradation analysis.
     *
     * This method provides the degradation analysis page using the
     * component measurements stored in the database.
     */
    public function degradation()
    {
        /*
        |--------------------------------------------------------------------------
        | Performance Parameters
        |--------------------------------------------------------------------------
        |
        | These are the measurement parameters that can be used to
        | calculate component degradation.
        |
        */

        $performanceParameters = [
            'Capacity',
            'Power',
            'Energy',
            'Usable Capacity',
            'Battery Capacity',
            'Output Power',
            'Panel Power',
        ];


        /*
        |--------------------------------------------------------------------------
        | Load Components
        |--------------------------------------------------------------------------
        */

        $components = Component::with([
            'componentType',
            'installation',
            'measurements'
        ])->get();


        /*
        |--------------------------------------------------------------------------
        | Build Component Degradation Summary
        |--------------------------------------------------------------------------
        */

        $componentSummaries = collect();


        foreach ($components as $component) {

            $performanceMeasurements = $component->measurements
                ->filter(function ($measurement) use ($performanceParameters) {
                    return in_array(
                        trim($measurement->parameter),
                        $performanceParameters,
                        true
                    )
                    && $measurement->reference_value !== null
                    && (float) $measurement->reference_value > 0;
                });


            /*
            |----------------------------------------------------------------------
            | Group Measurements By Parameter
            |----------------------------------------------------------------------
            */

            $latestByParameter = $performanceMeasurements
                ->groupBy(function ($measurement) {
                    return trim($measurement->parameter);
                })
                ->map(function ($measurements) {
                    return $measurements
                        ->sortByDesc('measurement_date')
                        ->first();
                });


            $degradationValues = [];


            foreach ($latestByParameter as $measurement) {

                $reference = (float) $measurement->reference_value;
                $current = (float) $measurement->value;


                if ($reference <= 0) {
                    continue;
                }


                $degradation = (
                    ($reference - $current)
                    / $reference
                ) * 100;


                /*
                |------------------------------------------------------------------
                | Prevent negative degradation
                |------------------------------------------------------------------
                */

                $degradation = max(0, $degradation);


                $degradationValues[] = $degradation;
            }


            /*
            |--------------------------------------------------------------------------
            | Average Degradation
            |--------------------------------------------------------------------------
            */

            $averageDegradation = count($degradationValues) > 0
                ? round(
                    array_sum($degradationValues)
                    / count($degradationValues),
                    2
                )
                : null;


            /*
            |--------------------------------------------------------------------------
            | Determine Status
            |--------------------------------------------------------------------------
            */

            if ($averageDegradation === null) {

                $status = 'No Data';

            } elseif ($averageDegradation < 5) {

                $status = 'Normal';

            } elseif ($averageDegradation < 10) {

                $status = 'Monitor';

            } elseif ($averageDegradation < 20) {

                $status = 'Attention';

            } else {

                $status = 'Critical';
            }


            /*
            |--------------------------------------------------------------------------
            | Determine Trend
            |--------------------------------------------------------------------------
            |
            | Compare older and newer performance measurements where
            | possible.
            |
            */

            $trend = 'Stable';


            $historicalDegradation = [];


            foreach (
                $component->measurements
                    ->filter(function ($measurement) use ($performanceParameters) {
                        return in_array(
                            trim($measurement->parameter),
                            $performanceParameters,
                            true
                        )
                        && $measurement->reference_value !== null
                        && (float) $measurement->reference_value > 0;
                    })
                    ->groupBy(function ($measurement) {
                        return trim($measurement->parameter);
                    })
                as $measurements
            ) {

                foreach ($measurements->sortBy('measurement_date') as $measurement) {

                    $reference = (float) $measurement->reference_value;
                    $current = (float) $measurement->value;

                    if ($reference <= 0) {
                        continue;
                    }

                    $degradation = max(
                        0,
                        (($reference - $current) / $reference) * 100
                    );

                    $historicalDegradation[] = [
                        'date' => $measurement->measurement_date,
                        'degradation' => $degradation,
                    ];
                }
            }


            if (count($historicalDegradation) >= 2) {

                usort(
                    $historicalDegradation,
                    function ($a, $b) {
                        return $a['date'] <=> $b['date'];
                    }
                );


                $first = $historicalDegradation[0]['degradation'];
                $last = end($historicalDegradation)['degradation'];


                if ($last > $first + 1) {

                    $trend = 'Declining';

                } elseif ($last < $first - 1) {

                    $trend = 'Improving';

                } else {

                    $trend = 'Stable';
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Store Summary
            |--------------------------------------------------------------------------
            */

            $componentSummaries->push([
                'component' => $component,
                'average_degradation' => $averageDegradation,
                'status' => $status,
                'trend' => $trend,
                'parameters_count' => count($degradationValues),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Selected Component
        |--------------------------------------------------------------------------
        |
        | Allow the page to display a specific component using:
        |
        | /degradation?component=1
        |
        */

        $selectedComponent = null;

        $selectedComponentId = request()->query('component');


        if ($selectedComponentId) {

            $selectedComponent = $components->firstWhere(
                'id',
                (int) $selectedComponentId
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Default Selected Component
        |--------------------------------------------------------------------------
        */

        if (!$selectedComponent) {

            $selectedComponent = $components->first();
        }


        /*
        |--------------------------------------------------------------------------
        | Selected Component Analysis
        |--------------------------------------------------------------------------
        */

        $selectedAnalysis = collect();


        if ($selectedComponent) {

            foreach ($performanceParameters as $parameter) {

                $measurements = $selectedComponent->measurements
                    ->filter(function ($measurement) use ($parameter) {

                        return trim($measurement->parameter) === $parameter;
                    })
                    ->sortByDesc('measurement_date')
                    ->values();


                $latest = $measurements->first();


                if (!$latest) {

                    $selectedAnalysis->push([
                        'parameter' => $parameter,
                        'latest' => null,
                        'degradation' => null,
                        'performance' => null,
                        'status' => 'No Data',
                        'trend' => 'No Data',
                        'chart' => [],
                    ]);

                    continue;
                }


                /*
                |------------------------------------------------------------------
                | No reference value
                |------------------------------------------------------------------
                */

                if (
                    $latest->reference_value === null
                    || (float) $latest->reference_value <= 0
                ) {

                    $selectedAnalysis->push([
                        'parameter' => $parameter,
                        'latest' => $latest,
                        'degradation' => null,
                        'performance' => null,
                        'status' => 'No Reference',
                        'trend' => 'Monitoring Only',
                        'chart' => [],
                    ]);

                    continue;
                }


                $reference = (float) $latest->reference_value;
                $current = (float) $latest->value;


                $degradation = max(
                    0,
                    (($reference - $current) / $reference) * 100
                );


                $performance = (
                    $current / $reference
                ) * 100;


                /*
                |------------------------------------------------------------------
                | Status
                |------------------------------------------------------------------
                */

                if ($degradation < 5) {

                    $parameterStatus = 'Normal';

                } elseif ($degradation < 10) {

                    $parameterStatus = 'Monitor';

                } elseif ($degradation < 20) {

                    $parameterStatus = 'Attention';

                } else {

                    $parameterStatus = 'Critical';
                }


                /*
                |------------------------------------------------------------------
                | Historical Chart Data
                |------------------------------------------------------------------
                */

                $chart = $measurements
                    ->filter(function ($measurement) {
                        return $measurement->reference_value !== null
                            && (float) $measurement->reference_value > 0;
                    })
                    ->sortBy('measurement_date')
                    ->map(function ($measurement) {

                        $reference = (float) $measurement->reference_value;
                        $current = (float) $measurement->value;

                        $degradation = max(
                            0,
                            (($reference - $current) / $reference) * 100
                        );

                        return [
                            'date' => $measurement->measurement_date
                                ? $measurement->measurement_date->format('Y-m-d')
                                : null,

                            'value' => $current,

                            'reference' => $reference,

                            'degradation' => round(
                                $degradation,
                                2
                            ),
                        ];
                    })
                    ->values()
                    ->toArray();


                /*
                |------------------------------------------------------------------
                | Parameter Trend
                |------------------------------------------------------------------
                */

                $parameterTrend = 'Stable';


                if (count($chart) >= 2) {

                    $first = $chart[0]['degradation'];
                    $last = end($chart)['degradation'];


                    if ($last > $first + 1) {

                        $parameterTrend = 'Declining';

                    } elseif ($last < $first - 1) {

                        $parameterTrend = 'Improving';
                    }
                }


                $selectedAnalysis->push([
                    'parameter' => $parameter,
                    'latest' => $latest,
                    'degradation' => round($degradation, 2),
                    'performance' => round($performance, 2),
                    'status' => $parameterStatus,
                    'trend' => $parameterTrend,
                    'chart' => $chart,
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Return Degradation View
        |--------------------------------------------------------------------------
        */

        return view('degradation.index', [
            'components' => $components,
            'componentSummaries' => $componentSummaries,
            'selectedComponent' => $selectedComponent,
            'selectedAnalysis' => $selectedAnalysis,
            'performanceParameters' => $performanceParameters,
        ]);
    }
}