<?php

namespace App\Http\Controllers;

use App\Models\Component;
use Illuminate\Http\Request;

class DegradationController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Performance Parameters
        |--------------------------------------------------------------------------
        | These parameters can be used to calculate performance degradation.
        | Voltage, current and temperature are treated as monitoring values.
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
        | Get Components
        |--------------------------------------------------------------------------
        */

        $components = Component::with([
            'installation',
            'componentType',
        ])
            ->where('status', '!=', 'Replaced')
            ->orderBy('name')
            ->get();

        $selectedComponent = null;
        $parameterAnalysis = [];
        $componentSummaries = [];

        /*
        |--------------------------------------------------------------------------
        | COMPONENT SUMMARY
        |--------------------------------------------------------------------------
        |
        | Current status is based on the LATEST performance measurement.
        |
        | Historical measurements are not averaged for the current status.
        | They are used later for trend analysis and charts.
        |
        |--------------------------------------------------------------------------
        */

        foreach ($components as $component) {

            $performanceMeasurements = $component
                ->measurements()
                ->whereIn('parameter', $performanceParameters)
                ->whereNotNull('value')
                ->orderBy('measurement_date')
                ->get();

            $latestLosses = [];

            /*
             * Group measurements by performance parameter.
             *
             * Example:
             * Power
             * Capacity
             * Energy
             */
            $groupedPerformanceMeasurements = $performanceMeasurements
                ->groupBy('parameter');

            foreach ($groupedPerformanceMeasurements as $parameter => $measurements) {

                $measurements = $measurements
                    ->sortBy('measurement_date')
                    ->values();

                $latest = $measurements->last();

                /*
                 * Find the latest available reference value.
                 */
                $referenceMeasurement = $measurements
                    ->whereNotNull('reference_value')
                    ->last();

                $referenceValue = $referenceMeasurement?->reference_value;

                /*
                 * Calculate degradation using the latest measurement.
                 */
                if (
                    $latest &&
                    $referenceValue !== null &&
                    $referenceValue > 0 &&
                    $latest->value !== null
                ) {

                    $degradation = (
                        ($referenceValue - $latest->value)
                        / $referenceValue
                    ) * 100;

                    /*
                     * Prevent negative degradation values.
                     *
                     * If current performance is higher than the reference,
                     * we treat the degradation as 0 rather than negative.
                     */
                    $degradation = max(0, $degradation);

                    $latestLosses[] = $degradation;
                }
            }

            /*
             * If the component has multiple performance parameters,
             * calculate the average of their LATEST losses.
             *
             * Example:
             *
             * Power latest loss = 20%
             * Capacity latest loss = 10%
             *
             * Component degradation = 15%
             */
            $averageDegradation = count($latestLosses) > 0
                ? round(
                    array_sum($latestLosses) / count($latestLosses),
                    2
                )
                : null;

            /*
             * Determine current component status.
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

            $componentSummaries[] = [
                'component' => $component,
                'degradation' => $averageDegradation,
                'status' => $status,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS COUNTS
        |--------------------------------------------------------------------------
        */

        $normalCount = collect($componentSummaries)
            ->where('status', 'Normal')
            ->count();

        $monitorCount = collect($componentSummaries)
            ->where('status', 'Monitor')
            ->count();

        $attentionCount = collect($componentSummaries)
            ->where('status', 'Attention')
            ->count();

        $criticalCount = collect($componentSummaries)
            ->where('status', 'Critical')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | SELECTED COMPONENT
        |--------------------------------------------------------------------------
        */

        if ($request->filled('component_id')) {

            $selectedComponent = Component::with([
                'installation',
                'componentType',
                'measurements' => function ($query) {
                    $query->orderBy('measurement_date');
                },
            ])->find($request->component_id);

            if ($selectedComponent) {

                /*
                 * Group all measurements by parameter.
                 */
                $groupedMeasurements = $selectedComponent
                    ->measurements
                    ->groupBy('parameter');

                foreach ($groupedMeasurements as $parameter => $measurements) {

                    $measurements = $measurements
                        ->sortBy('measurement_date')
                        ->values();

                    $latest = $measurements->last();

                    /*
                     * Check whether this is a performance parameter.
                     */
                    $isPerformanceParameter = in_array(
                        $parameter,
                        $performanceParameters,
                        true
                    );

                    /*
                     * Get the latest available reference value.
                     */
                    $referenceMeasurement = $measurements
                        ->whereNotNull('reference_value')
                        ->last();

                    $referenceValue =
                        $referenceMeasurement?->reference_value;

                    $currentValue =
                        $latest?->value;

                    $degradation = null;
                    $performance = null;

                    /*
                     * Default status for monitoring-only parameters.
                     */
                    $status = 'Monitoring Only';

                    $interpretation =
                        'Operating parameter. Monitor changes over time.';

                    /*
                     |--------------------------------------------------------------------------
                     | PERFORMANCE DEGRADATION
                     |--------------------------------------------------------------------------
                     */

                    if (
                        $isPerformanceParameter &&
                        $referenceValue !== null &&
                        $referenceValue > 0 &&
                        $currentValue !== null
                    ) {

                        $degradation = (
                            ($referenceValue - $currentValue)
                            / $referenceValue
                        ) * 100;

                        /*
                         * Prevent negative degradation.
                         */
                        $degradation = max(0, $degradation);

                        /*
                         * Performance percentage.
                         */
                        $performance = max(
                            0,
                            min(100, 100 - $degradation)
                        );

                        /*
                         * Determine degradation status.
                         */
                        if ($degradation <= 5) {

                            $status = 'Normal';

                            $interpretation =
                                'Performance is within the normal range.';

                        } elseif ($degradation <= 15) {

                            $status = 'Monitor';

                            $interpretation =
                                'Some performance reduction is present. Continue monitoring.';

                        } elseif ($degradation <= 30) {

                            $status = 'Attention';

                            $interpretation =
                                'Significant performance reduction detected. Inspection is recommended.';

                        } else {

                            $status = 'Critical';

                            $interpretation =
                                'High performance loss detected. Maintenance or replacement assessment is recommended.';
                        }

                    } elseif (
                        $isPerformanceParameter &&
                        $referenceValue === null
                    ) {

                        $status = 'No Reference';

                        $interpretation =
                            'A reference value is required before performance degradation can be calculated.';
                    }

                    /*
                     |--------------------------------------------------------------------------
                     | TREND ANALYSIS
                     |--------------------------------------------------------------------------
                     |
                     | Trend compares the FIRST measurement with the LATEST
                     | measurement.
                     |--------------------------------------------------------------------------
                     */

                    $trend = 'Insufficient Data';
                    $trendPercentage = null;

                    if (
                        $isPerformanceParameter &&
                        $measurements->count() >= 2
                    ) {

                        $first = $measurements->first();

                        if (
                            $first->value !== null &&
                            $first->value > 0 &&
                            $currentValue !== null
                        ) {

                            $trendPercentage = (
                                ($first->value - $currentValue)
                                / $first->value
                            ) * 100;

                            if ($trendPercentage > 2) {

                                $trend = 'Declining';

                            } elseif ($trendPercentage < -2) {

                                $trend = 'Improving';

                            } else {

                                $trend = 'Stable';
                            }
                        }
                    }

                    /*
                     |--------------------------------------------------------------------------
                     | CHART DATA
                     |--------------------------------------------------------------------------
                     */

                    $chartData = [];

                    if ($isPerformanceParameter) {

                        foreach ($measurements as $measurement) {

                            $chartData[] = [
                                'date' =>
                                    $measurement->measurement_date?->format(
                                        'd M Y'
                                    ),

                                'value' =>
                                    $measurement->value !== null
                                        ? (float) $measurement->value
                                        : null,

                                'reference' =>
                                    $measurement->reference_value !== null
                                        ? (float) $measurement->reference_value
                                        : null,

                                'unit' =>
                                    $measurement->unit,
                            ];
                        }
                    }

                    /*
                     |--------------------------------------------------------------------------
                     | PARAMETER ANALYSIS DATA
                     |--------------------------------------------------------------------------
                     */

                    $parameterAnalysis[] = [

                        'parameter' =>
                            $parameter,

                        'latest' =>
                            $latest,

                        'reference' =>
                            $referenceValue,

                        'degradation' =>
                            $degradation,

                        'performance' =>
                            $performance,

                        'status' =>
                            $status,

                        'interpretation' =>
                            $interpretation,

                        'trend' =>
                            $trend,

                        'trend_percentage' =>
                            $trendPercentage,

                        'is_performance_parameter' =>
                            $isPerformanceParameter,

                        'history' =>
                            $measurements,

                        'chart_data' =>
                            $chartData,
                    ];
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | COMPONENT COMPARISON DATA
        |--------------------------------------------------------------------------
        */

        $comparisonData = collect($componentSummaries)
            ->filter(function ($summary) {

                return $summary['degradation'] !== null;
            })
            ->map(function ($summary) {

                return [

                    'name' =>
                        $summary['component']->name,

                    'degradation' =>
                        $summary['degradation'],

                    'status' =>
                        $summary['status'],
                ];
            })
            ->values()
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view('degradation.index', compact(

            'components',

            'selectedComponent',

            'parameterAnalysis',

            'componentSummaries',

            'normalCount',

            'monitorCount',

            'attentionCount',

            'criticalCount',

            'comparisonData'

        ));
    }
}
