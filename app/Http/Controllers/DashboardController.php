<?php

namespace App\Http\Controllers;

use App\Models\Installation;
use App\Models\Component;
use App\Models\Inspection;
use App\Models\MaintenanceSchedule;
use App\Models\ReplacementForecast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class DashboardController extends Controller
{
    /**
     * Main dashboard
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Basic Statistics
        |--------------------------------------------------------------------------
        */

        $totalInstallations = Installation::count();

        $totalComponents = Component::count();


        /*
        |--------------------------------------------------------------------------
        | Pending Inspections
        |--------------------------------------------------------------------------
        |
        | An inspection is considered pending when its next inspection
        | date is today or earlier.
        |
        */

        $pendingInspections = Inspection::whereNotNull('next_inspection_date')
            ->whereDate('next_inspection_date', '<=', Carbon::today())
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Maintenance Statistics
        |--------------------------------------------------------------------------
        */

        $totalMaintenance = MaintenanceSchedule::count();

        $scheduledMaintenance = MaintenanceSchedule::where(
            'status',
            'Scheduled'
        )->count();

        $dueMaintenance = MaintenanceSchedule::whereIn(
            'status',
            ['Due Soon', 'Overdue']
        )->count();

        $maintenanceDue = $dueMaintenance;


        /*
        |--------------------------------------------------------------------------
        | Upcoming Maintenance
        |--------------------------------------------------------------------------
        |
        | The maintenance_schedules table uses next_due_date.
        |
        */

        $upcomingMaintenance = MaintenanceSchedule::whereDate(
            'next_due_date',
            '>=',
            Carbon::today()
        )
            ->orderBy('next_due_date')
            ->take(5)
            ->get();


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
        | Component Condition Data
        |--------------------------------------------------------------------------
        */

        /*
|--------------------------------------------------------------------------
| Component Condition Data
|--------------------------------------------------------------------------
|
| Component condition is recorded during inspections.
| Therefore, the dashboard uses the latest inspection condition
| recorded for each component.
|
*/

$conditionData = [
    'Excellent' => 0,
    'Good' => 0,
    'Fair' => 0,
    'Poor' => 0,
    'Critical' => 0,
];

/*
|--------------------------------------------------------------------------
| Get Latest Inspection Condition for Each Component
|--------------------------------------------------------------------------
*/

$latestComponentConditions = DB::table('inspection_items')
    ->join(
        'inspections',
        'inspection_items.inspection_id',
        '=',
        'inspections.id'
    )
    ->select(
        'inspection_items.component_id',
        'inspection_items.condition',
        'inspections.inspection_date',
        'inspection_items.id'
    )
    ->whereNotNull('inspection_items.component_id')
    ->whereNotNull('inspection_items.condition')
    ->orderByDesc('inspections.inspection_date')
    ->orderByDesc('inspection_items.id')
    ->get();

/*
|--------------------------------------------------------------------------
| Count Only the Latest Condition Per Component
|--------------------------------------------------------------------------
*/

$processedComponents = [];

foreach ($latestComponentConditions as $item) {

    $componentId = $item->component_id;

    /*
    | Skip this component if its latest condition
    | has already been processed.
    */

    if (isset($processedComponents[$componentId])) {
        continue;
    }

    $processedComponents[$componentId] = true;

    $condition = trim((string) $item->condition);

    /*
    |--------------------------------------------------------------------------
    | Normalize Condition Value
    |--------------------------------------------------------------------------
    */

    $normalizedCondition = strtolower($condition);

    switch ($normalizedCondition) {

        case 'excellent':
            $conditionData['Excellent']++;
            break;

        case 'good':
            $conditionData['Good']++;
            break;

        case 'fair':
            $conditionData['Fair']++;
            break;

        case 'poor':
            $conditionData['Poor']++;
            break;

        case 'critical':
            $conditionData['Critical']++;
            break;
    }
}

        /*
        |--------------------------------------------------------------------------
        | Replacement Risk Data
        |--------------------------------------------------------------------------
        */

        $riskData = [
            'Low' => 0,
            'Medium' => 0,
            'High' => 0,
        ];

        $replacementForecastsCollection = ReplacementForecast::get();

        foreach ($replacementForecastsCollection as $forecast) {

            $riskLevel = $forecast->risk_level;

            if (isset($riskData[$riskLevel])) {
                $riskData[$riskLevel]++;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Dashboard View
        |--------------------------------------------------------------------------
        */

        return view('dashboard', [

            'totalInstallations' => $totalInstallations,

            'totalComponents' => $totalComponents,

            'pendingInspections' => $pendingInspections,

            'maintenanceDue' => $maintenanceDue,

            'totalMaintenance' => $totalMaintenance,

            'scheduledMaintenance' => $scheduledMaintenance,

            'dueMaintenance' => $dueMaintenance,

            'upcomingMaintenance' => $upcomingMaintenance,

            'recentInspections' => $recentInspections,

            'conditionData' => $conditionData,

            'riskData' => $riskData,

        ]);
    }


    /**
     * Degradation Analysis Dashboard
     */
    public function degradation(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Performance Parameters
        |--------------------------------------------------------------------------
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

            $measurementsByParameter = $component->measurements
                ->groupBy('parameter');


            foreach ($measurementsByParameter as $parameter => $measurements) {

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

                $degradation = max(0, $degradation);

                $performanceLosses[] = $degradation;
            }


            /*
            |--------------------------------------------------------------------------
            | Average Degradation
            |--------------------------------------------------------------------------
            */

            $averageDegradation = null;

            if (count($performanceLosses) > 0) {

                $averageDegradation =
                    array_sum($performanceLosses)
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


            foreach ($measurementsByParameter as $parameter => $measurements) {

                $measurements = $measurements
                    ->sortBy('measurement_date')
                    ->values();

                $latest = $measurements->last();


                /*
                |--------------------------------------------------------------------------
                | Performance Parameter
                |--------------------------------------------------------------------------
                */

                $isPerformanceParameter = in_array(
                    $parameter,
                    $performanceParameters
                );


                /*
                |--------------------------------------------------------------------------
                | Reference Value
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
                | Default Values
                |--------------------------------------------------------------------------
                */

                $degradation = null;

                $performance = null;

                $trend = 'Insufficient Data';

                $status = 'Monitoring Only';

                $chartData = [];


                /*
                |--------------------------------------------------------------------------
                | Performance Analysis
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
                        | Degradation History
                        |--------------------------------------------------------------------------
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


                        /*
                        |--------------------------------------------------------------------------
                        | Trend
                        |--------------------------------------------------------------------------
                        */

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
        | Degradation View
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