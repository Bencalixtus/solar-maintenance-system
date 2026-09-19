<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\ReplacementForecast;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReplacementForecastController extends Controller
{
    /**
     * Display replacement forecast dashboard.
     */
    public function index()
    {
        // Load existing forecasts with related component information.
        $forecasts = ReplacementForecast::with([
            'component.componentType',
            'component.installation',
        ])
        ->orderByDesc('forecast_date')
        ->orderByDesc('id')
        ->get();


        // Load all components and their related information.
        $components = Component::with([
            'componentType',
            'measurements',
            'maintenanceRecords',
            'costRecords',
            'replacementForecasts',
        ])
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Risk Summary
        |--------------------------------------------------------------------------
        */

        $lowRisk = $forecasts->where('risk_level', 'Low')->count();

        $mediumRisk = $forecasts->where('risk_level', 'Medium')->count();

        $highRisk = $forecasts->where('risk_level', 'High')->count();


        /*
        |--------------------------------------------------------------------------
        | Additional Summary Information
        |--------------------------------------------------------------------------
        */

        $totalForecasts = $forecasts->count();

        $componentsWithForecast = $forecasts
            ->pluck('component_id')
            ->unique()
            ->count();

        $componentsWithoutForecast = $components
            ->whereNotIn(
                'id',
                $forecasts->pluck('component_id')->unique()
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view('replacement-forecasts.index', compact(
            'forecasts',
            'components',
            'lowRisk',
            'mediumRisk',
            'highRisk',
            'totalForecasts',
            'componentsWithForecast',
            'componentsWithoutForecast'
        ));
    }


    /**
     * Generate a new replacement forecast for a component.
     */
    public function generate(Component $component)
    {
        $component->load([
            'componentType',
            'installation',
            'measurements',
            'maintenanceRecords',
            'costRecords',
            'replacementForecasts',
        ]);


        $forecastData = $this->calculateForecast($component);


        $forecast = ReplacementForecast::create($forecastData);


        return redirect()
            ->route('replacement-forecasts.show', $forecast)
            ->with(
                'success',
                'Replacement forecast generated successfully.'
            );
    }


    /**
     * Display a replacement forecast.
     */
    public function show(ReplacementForecast $replacementForecast)
    {
        $replacementForecast->load([
            'component.componentType',
            'component.installation',
            'component.measurements',
            'component.maintenanceRecords',
            'component.costRecords',
        ]);


        return view(
            'replacement-forecasts.show',
            compact('replacementForecast')
        );
    }


    /**
     * Refresh the latest replacement forecast for a component.
     */
    public function refresh(Component $component)
    {
        $component->load([
            'componentType',
            'installation',
            'measurements',
            'maintenanceRecords',
            'costRecords',
            'replacementForecasts',
        ]);


        $forecastData = $this->calculateForecast($component);


        $latestForecast = $component->replacementForecasts()
            ->latest('forecast_date')
            ->latest('id')
            ->first();


        if ($latestForecast) {

            $latestForecast->update($forecastData);

            $message = 'Replacement forecast refreshed successfully.';

        } else {

            ReplacementForecast::create($forecastData);

            $message = 'Replacement forecast generated successfully.';
        }


        return redirect()
            ->route('replacement-forecasts.index')
            ->with('success', $message);
    }


    /**
     * Delete a replacement forecast.
     */
    public function destroy(ReplacementForecast $replacementForecast)
    {
        $replacementForecast->delete();


        return redirect()
            ->route('replacement-forecasts.index')
            ->with(
                'success',
                'Replacement forecast deleted successfully.'
            );
    }


    /**
     * Calculate replacement forecast data.
     */
    private function calculateForecast(Component $component): array
    {
        /*
        |--------------------------------------------------------------------------
        | Current Age
        |--------------------------------------------------------------------------
        */

        $currentAge = null;

        if ($component->installation_date) {

            $currentAge = round(
                Carbon::parse($component->installation_date)
                    ->diffInDays(Carbon::today()) / 365.25,
                2
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Expected Lifespan
        |--------------------------------------------------------------------------
        */

        $expectedLifespan = $component->expected_lifespan
            ? (float) $component->expected_lifespan
            : null;


        /*
        |--------------------------------------------------------------------------
        | Age Score
        |--------------------------------------------------------------------------
        */

        $ageScore = 50;

        if (
            $currentAge !== null &&
            $expectedLifespan !== null &&
            $expectedLifespan > 0
        ) {

            $agePercentage =
                ($currentAge / $expectedLifespan) * 100;


            if ($agePercentage >= 100) {
                $ageScore = 100;

            } elseif ($agePercentage >= 80) {
                $ageScore = 80;

            } elseif ($agePercentage >= 60) {
                $ageScore = 60;

            } elseif ($agePercentage >= 40) {
                $ageScore = 40;

            } else {
                $ageScore = 20;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Condition Score
        |--------------------------------------------------------------------------
        */

        $condition = $component->current_condition;

        $conditionScore = match ($condition) {

            'Excellent' => 10,

            'Good' => 30,

            'Fair' => 55,

            'Poor' => 80,

            'Critical' => 100,

            default => 50,

        };


        /*
        |--------------------------------------------------------------------------
        | Degradation
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


        $degradationValues = [];


        foreach ($performanceParameters as $parameter) {

            $measurement = $component->measurements
                ->where('parameter', $parameter)
                ->filter(function ($measurement) {

                    return $measurement->reference_value !== null
                        && (float) $measurement->reference_value > 0;

                })
                ->sortByDesc(function ($measurement) {

                    return $measurement->measurement_date
                        ? $measurement->measurement_date->timestamp
                        : 0;

                })
                ->first();


            if ($measurement) {

                $reference = (float) $measurement->reference_value;

                $current = (float) $measurement->value;


                $degradation = (($reference - $current) / $reference) * 100;


                // Do not allow negative degradation.
                $degradation = max(0, $degradation);


                $degradationValues[] = $degradation;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Average Degradation
        |--------------------------------------------------------------------------
        */

        $averageDegradation = null;

        if (count($degradationValues) > 0) {

            $averageDegradation = round(
                array_sum($degradationValues) / count($degradationValues),
                2
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Degradation Risk Score
        |--------------------------------------------------------------------------
        */

        $degradationScore = 50;

        if ($averageDegradation !== null) {

            if ($averageDegradation >= 30) {
                $degradationScore = 100;

            } elseif ($averageDegradation >= 20) {
                $degradationScore = 80;

            } elseif ($averageDegradation >= 10) {
                $degradationScore = 60;

            } elseif ($averageDegradation >= 5) {
                $degradationScore = 40;

            } else {
                $degradationScore = 20;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Maintenance History Score
        |--------------------------------------------------------------------------
        */

        $maintenanceCount = $component->maintenanceRecords->count();


        if ($maintenanceCount === 0) {

            $maintenanceScore = 70;

        } elseif ($maintenanceCount <= 2) {

            $maintenanceScore = 50;

        } elseif ($maintenanceCount <= 5) {

            $maintenanceScore = 30;

        } else {

            $maintenanceScore = 20;
        }


        /*
        |--------------------------------------------------------------------------
        | Remaining Life
        |--------------------------------------------------------------------------
        */

        $remainingLife = null;

        $lifespanScore = 50;


        if (
            $currentAge !== null &&
            $expectedLifespan !== null
        ) {

            $remainingLife = max(
                0,
                round(
                    $expectedLifespan - $currentAge,
                    2
                )
            );


            if ($remainingLife <= 0) {

                $lifespanScore = 100;

            } elseif ($remainingLife <= 1) {

                $lifespanScore = 80;

            } elseif ($remainingLife <= 2) {

                $lifespanScore = 60;

            } elseif ($remainingLife <= 4) {

                $lifespanScore = 40;

            } else {

                $lifespanScore = 20;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Final Risk Score
        |--------------------------------------------------------------------------
        |
        | Weighting:
        |
        | Age             = 20%
        | Condition       = 30%
        | Degradation     = 30%
        | Maintenance     = 10%
        | Remaining Life  = 10%
        |
        */

        $riskScore =

            ($ageScore * 0.20) +

            ($conditionScore * 0.30) +

            ($degradationScore * 0.30) +

            ($maintenanceScore * 0.10) +

            ($lifespanScore * 0.10);


        $riskScore = round($riskScore, 2);


        /*
        |--------------------------------------------------------------------------
        | Risk Level
        |--------------------------------------------------------------------------
        */

        if ($riskScore < 30) {

            $riskLevel = 'Low';

        } elseif ($riskScore < 60) {

            $riskLevel = 'Medium';

        } else {

            $riskLevel = 'High';
        }


        /*
        |--------------------------------------------------------------------------
        | Recommended Action
        |--------------------------------------------------------------------------
        */

        if ($riskLevel === 'High') {

            $recommendedAction =
                'Plan for replacement and carry out detailed inspection.';

        } elseif ($riskLevel === 'Medium') {

            $recommendedAction =
                'Schedule preventive maintenance and continue close monitoring.';

        } else {

            $recommendedAction =
                'Continue routine preventive maintenance and monitoring.';
        }


        /*
        |--------------------------------------------------------------------------
        | Estimated Replacement Cost
        |--------------------------------------------------------------------------
        */

        $replacementCost = null;


        $replacementRecord = $component->costRecords
            ->filter(function ($cost) {

                return strtolower(trim($cost->cost_type)) === 'replacement';

            })
            ->sortByDesc(function ($cost) {

                return $cost->cost_date
                    ? $cost->cost_date->timestamp
                    : 0;

            })
            ->first();


        if ($replacementRecord) {

            $replacementCost = $replacementRecord->amount;
        }


        /*
        |--------------------------------------------------------------------------
        | Forecast Notes
        |--------------------------------------------------------------------------
        */

        $notes = [];

        $notes[] = "Calculated risk score: {$riskScore}/100.";

        if ($currentAge !== null) {

            if ($expectedLifespan !== null) {

                $notes[] =
                    "Current age: {$currentAge} years against an expected lifespan of {$expectedLifespan} years.";

            } else {

                $notes[] =
                    "Current age: {$currentAge} years. Expected lifespan was not available.";
            }

        } else {

            $notes[] =
                "Current age could not be calculated because installation date was not available.";
        }


        if ($averageDegradation !== null) {

            $notes[] =
                "Average measured degradation: {$averageDegradation}%.";

        } else {

            $notes[] =
                "No suitable performance measurement with a reference value was available for degradation analysis.";
        }


        if ($condition) {

            $notes[] =
                "Current component condition: {$condition}.";

        } else {

            $notes[] =
                "Current component condition was not available.";
        }


        $notes[] =
            "Maintenance records considered: {$maintenanceCount}.";


        $notes[] =
            "This forecast is a decision-support estimate and does not represent an exact component failure date.";


        /*
        |--------------------------------------------------------------------------
        | Return Forecast Data
        |--------------------------------------------------------------------------
        */

        return [

            'component_id' =>
                $component->id,

            'forecast_date' =>
                Carbon::today(),

            'current_age' =>
                $currentAge,

            'current_condition' =>
                $condition,

            'degradation_rate' =>
                $averageDegradation,

            'estimated_remaining_life' =>
                $remainingLife,

            'estimated_replacement_cost' =>
                $replacementCost,

            'risk_level' =>
                $riskLevel,

            'recommended_action' =>
                $recommendedAction,

            'forecast_notes' =>
                implode("\n", $notes),

        ];
    }
}