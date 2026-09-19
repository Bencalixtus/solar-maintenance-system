<?php

namespace App\Http\Controllers;

use App\Models\Installation;
use App\Models\Component;
use App\Models\Inspection;
use App\Models\MaintenanceSchedule;
use App\Models\ReplacementForecast;
use App\Models\Measurement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Main dashboard
     */
    public function index()
    {
        $installationCount = Installation::count();
        $componentCount = Component::count();
        $inspectionCount = Inspection::count();

        $upcomingMaintenance = MaintenanceSchedule::whereDate(
            'scheduled_date',
            '>=',
            Carbon::today()
        )
            ->orderBy('scheduled_date')
            ->take(5)
            ->get();

        $recentInspections = Inspection::with([
            'installation',
            'inspector'
        ])
            ->latest('inspection_date')
            ->take(5)
            ->get();

        $activeComponents = Component::where('status', 'Active')->count();

        $replacementForecasts = ReplacementForecast::count();

        $highRiskForecasts = ReplacementForecast::where(
            'risk_level',
            'High'
        )->count();

        return view('dashboard', [
            'installationCount' => $installationCount,
            'componentCount' => $componentCount,
            'inspectionCount' => $inspectionCount,
            'activeComponents' => $activeComponents,
            'upcomingMaintenance' => $upcomingMaintenance,
            'recentInspections' => $recentInspections,
            'replacementForecasts' => $replacementForecasts,
            'highRiskForecasts' => $highRiskForecasts,
        ]);
    }


    /**
     * Degradation analysis dashboard
     */
    public function degradation(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Performance Parameters
        |--------------------------------------------------------------------------
        |
        | These parameters are suitable for calculating performance loss when
        | a meaningful reference value is available.
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
            'measurements' => function ($query) {
                $query->orderBy('measurement_date');
            },
        ])
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Summary Counters
        |--------------------------------------------------------------------------
        */

        $normalCount = 0;
        $monitorCount = 0;
        $attentionCount = 0;
        $criticalCount = 0;


        /*
        |--------------------------------------------------------------------------
        | Component Summary
        |--------------------------------------------------------------------------
        */

        $componentSummaries = [];

        /*
        |--------------------------------------------------------------------------
        | Component Comparison Chart
        |--------------------------------------------------------------------------
        */

        $comparisonData = [];


        /*
        |--------------------------------------------------------------------------
        | Analyse Every Component
        |--------------------------------------------------------------------------
        */

        foreach ($components as $component) {

            $performanceLosses = [];

            /*
             * Group measurements by parameter.
             */
            $measurementsByParameter = $component->measurements
                ->groupBy('parameter');


            foreach ($measurementsByParameter as $parameter => $measurements) {

                /*
                 * Only performance parameters are used for degradation
                 * calculations.
                 */
                if (!in_array($parameter, $performanceParameters)) {
                    continue;
                }

                $measurements = $measurements
                    ->sortBy('measurement_date')
                    ->values();

                $latest = $measurements->last();

                if (!$latest) {
                    continue;
                }

                /*
                 * Use the latest meaningful reference value.
                 */
                $reference = null;

                foreach ($measurements->reverse() as $measurement) {

                    if (
                        $measurement->reference_value !== null &&
                        is_numeric($measurement->reference_value) &&
                        (float) $measurement->reference_value > 0
                    ) {
                        $reference = (float) $measurement->reference_value;
                        break;
                    }
                }

                /*
                 * If there is no reference value, degradation cannot
                 * be calculated reliably.
                 */
                if (
                    $reference === null ||
                    $latest->value === null ||
                    !is_numeric($latest->value)
                ) {
                    continue;
                }

                $currentValue = (float) $latest->value;

                $degradation = (
                    ($reference - $currentValue)
                    / $reference
                ) * 100;

                /*
                 * Prevent negative degradation from being displayed as
                 * performance loss.
                 */
                $degradation = max(0, $degradation);

                $performanceLosses[] = $degradation;
            }


            /*
            |--------------------------------------------------------------------------
            | Component Average Degradation
            |--------------------------------------------------------------------------
            */

            $averageDegradation = null;

            if (count($performanceLosses) > 0) {
                $averageDegradation = array_sum($performanceLosses)
                    / count($performanceLosses);
            }


            /*
            |--------------------------------------------------------------------------
            | Component Status
            |--------------------------------------------------------------------------
            */

            if ($averageDegradation === null) {

                $status = 'No Data';

            } elseif ($averageDegradation <= 5) {

                $status = 'Normal';

            } elseif ($averageDegradation <= 15) {

                $status = 'Monitor';

            } elseif ($averageDegradation <= 30) {

                $status = 'Attention';

            } else {

                $status = 'Critical';
            }


            /*
            |--------------------------------------------------------------------------
            | Count Statuses
            |--------------------------------------------------------------------------
            */

            switch ($status) {

                case 'Normal':
                    $normalCount++;
                    break;

                case 'Monitor':
                    $monitorCount++;
                    break;

                case 'Attention':
                    $attentionCount++;
                    break;

                case 'Critical':
                    $criticalCount++;
                    break;
            }


            /*
            |--------------------------------------------------------------------------
            | Component Summary
            |--------------------------------------------------------------------------
            */

            $componentSummaries[] = [
                'component' => $component,
                'degradation' => $averageDegradation,
                'status' => $status,
            ];


            /*
            |--------------------------------------------------------------------------
            | Comparison Chart
            |--------------------------------------------------------------------------
            */

            if ($averageDegradation !== null) {

                $comparisonData[] = [
                    'id' => $component->id,
                    'name' => $component->name,
                    'degradation' => round($averageDegradation, 2),
                    'status' => $status,
                ];
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Selected Component
        |--------------------------------------------------------------------------
        */

        $selectedComponent = null;
        $parameterAnalysis = [];


        if ($request->filled('component_id')) {

            $selectedComponent = $components->firstWhere(
                'id',
                (int) $request->component_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Detailed Selected Component Analysis
        |--------------------------------------------------------------------------
        */

        if ($selectedComponent) {

            $measurementsByParameter = $selectedComponent->measurements
                ->groupBy('parameter');


            /*
             * Include every parameter found in the component's
             * measurements.
             */
            foreach ($measurementsByParameter as $parameter => $measurements) {

                $measurements = $measurements
                    ->sortBy('measurement_date')
                    ->values();

                $latest = $measurements->last();


                /*
                |--------------------------------------------------------------------------
                | Is Performance Parameter?
                |--------------------------------------------------------------------------
                */

                $isPerformanceParameter = in_array(
                    $parameter,
                    $performanceParameters
                );


                /*
                |--------------------------------------------------------------------------
                | Find Reference Value
                |--------------------------------------------------------------------------
                */

                $reference = null;

                foreach ($measurements->reverse() as $measurement) {

                    if (
                        $measurement->reference_value !== null &&
                        is_numeric($measurement->reference_value) &&
                        (float) $measurement->reference_value > 0
                    ) {
                        $reference = (float) $measurement->reference_value;
                        break;
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | Default Analysis Values
                |--------------------------------------------------------------------------
                */

                $degradation = null;
                $performance = null;
                $trend = 'Insufficient Data';
                $status = 'Monitoring Only';

                $chartData = [];


                /*
                |--------------------------------------------------------------------------
                | Performance Parameter Analysis
                |--------------------------------------------------------------------------
                */

                if ($isPerformanceParameter) {

                    if (
                        $latest &&
                        $reference !== null &&
                        $latest->value !== null &&
                        is_numeric($latest->value)
                    ) {

                        $currentValue = (float) $latest->value;

                        $degradation = (
                            ($reference - $currentValue)
                            / $reference
                        ) * 100;

                        $degradation = max(0, $degradation);

                        $performance = max(
                            0,
                            min(100, 100 - $degradation)
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | Status
                        |--------------------------------------------------------------------------
                        */

                        if ($degradation <= 5) {

                            $status = 'Normal';

                        } elseif ($degradation <= 15) {

                            $status = 'Monitor';

                        } elseif ($degradation <= 30) {

                            $status = 'Attention';

                        } else {

                            $status = 'Critical';
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Trend
                        |--------------------------------------------------------------------------
                        |
                        | Compare the first and latest degradation values.
                        |
                        */

                        $degradationHistory = [];

                        foreach ($measurements as $measurement) {

                            if (
                                $measurement->value === null ||
                                !is_numeric($measurement->value)
                            ) {
                                continue;
                            }

                            $measurementReference =
                                $measurement->reference_value !== null &&
                                is_numeric($measurement->reference_value) &&
                                (float) $measurement->reference_value > 0
                                    ? (float) $measurement->reference_value
                                    : $reference;

                            if ($measurementReference <= 0) {
                                continue;
                            }

                            $measurementDegradation = (
                                ($measurementReference - (float) $measurement->value)
                                / $measurementReference
                            ) * 100;

                            $measurementDegradation = max(
                                0,
                                $measurementDegradation
                            );

                            $degradationHistory[] = [
                                'date' => $measurement->measurement_date
                                    ? $measurement->measurement_date->format('d M Y')
                                    : 'N/A',
                                'degradation' => $measurementDegradation,
                            ];
                        }


                        if (count($degradationHistory) >= 2) {

                            $firstDegradation =
                                $degradationHistory[0]['degradation'];

                            $lastDegradation =
                                $degradationHistory[
                                    count($degradationHistory) - 1
                                ]['degradation'];

                            $difference =
                                $lastDegradation - $firstDegradation;


                            if ($difference > 1) {

                                $trend = 'Declining';

                            } elseif ($difference < -1) {

                                $trend = 'Improving';

                            } else {

                                $trend = 'Stable';
                            }
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Chart Data
                        |--------------------------------------------------------------------------
                        */

                        foreach ($measurements as $measurement) {

                            if (
                                $measurement->value === null ||
                                !is_numeric($measurement->value)
                            ) {
                                continue;
                            }

                            $measurementReference =
                                $measurement->reference_value !== null &&
                                is_numeric($measurement->reference_value) &&
                                (float) $measurement->reference_value > 0
                                    ? (float) $measurement->reference_value
                                    : $reference;

                            $chartData[] = [
                                'date' => $measurement->measurement_date
                                    ? $measurement->measurement_date->format('d M Y')
                                    : 'N/A',
                                'value' => (float) $measurement->value,
                                'reference' => $measurementReference,
                            ];
                        }
                    } else {

                        $status = 'No Reference';
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | Interpretation
                |--------------------------------------------------------------------------
                */

                if (!$isPerformanceParameter) {

                    $interpretation =
                        'This parameter is monitored for operating condition. '
                        . 'Its value is not used directly to calculate degradation '
                        . 'because it can vary with operating and environmental conditions.';

                } elseif ($status === 'No Reference') {

                    $interpretation =
                        'A meaningful reference value is not available, '
                        . 'so performance loss cannot be calculated reliably.';

                } elseif ($status === 'Normal') {

                    $interpretation =
                        'Performance loss is within the normal range based on '
                        . 'the project decision-support thresholds.';

                } elseif ($status === 'Monitor') {

                    $interpretation =
                        'The component shows a moderate performance loss and '
                        . 'should be monitored during subsequent inspections.';

                } elseif ($status === 'Attention') {

                    $interpretation =
                        'The measured performance loss is significant and '
                        . 'should receive closer inspection and maintenance attention.';

                } elseif ($status === 'Critical') {

                    $interpretation =
                        'The measured performance loss is high and should be '
                        . 'investigated promptly as part of maintenance or replacement planning.';

                } else {

                    $interpretation =
                        'Additional measurements are required for a reliable assessment.';
                }


                /*
                |--------------------------------------------------------------------------
                | Store Parameter Analysis
                |--------------------------------------------------------------------------
                */

                $parameterAnalysis[] = [
                    'parameter' => $parameter,
                    'latest' => $latest,
                    'reference' => $reference,
                    'degradation' => $degradation,
                    'performance' => $performance,
                    'trend' => $trend,
                    'status' => $status,
                    'interpretation' => $interpretation,
                    'is_performance_parameter' => $isPerformanceParameter,
                    'chart_data' => $chartData,
                    'history' => $measurements,
                ];
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view('degradation.index', [

            'components' => $components,

            'componentSummaries' => $componentSummaries,

            'comparisonData' => $comparisonData,

            'selectedComponent' => $selectedComponent,

            'parameterAnalysis' => $parameterAnalysis,

            'performanceParameters' => $performanceParameters,

            'normalCount' => $normalCount,

            'monitorCount' => $monitorCount,

            'attentionCount' => $attentionCount,

            'criticalCount' => $criticalCount,
        ]);
    }
}