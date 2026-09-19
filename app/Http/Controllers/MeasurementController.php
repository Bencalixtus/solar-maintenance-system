<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\Measurement;
use App\Models\User;
use Illuminate\Http\Request;

class MeasurementController extends Controller
{
    /**
     * Display all measurements.
     */
    public function index()
    {
        $measurements = Measurement::with([
            'component.installation',
            'component.componentType',
            'recorder',
        ])
            ->latest('measurement_date')
            ->latest('id')
            ->get();

        $totalMeasurements = $measurements->count();

        $voltageMeasurements = $measurements
            ->filter(function ($measurement) {
                return str_contains(
                    strtolower($measurement->parameter),
                    'voltage'
                );
            })
            ->count();

        $currentMeasurements = $measurements
            ->filter(function ($measurement) {
                return str_contains(
                    strtolower($measurement->parameter),
                    'current'
                );
            })
            ->count();

        $temperatureMeasurements = $measurements
            ->filter(function ($measurement) {
                return str_contains(
                    strtolower($measurement->parameter),
                    'temperature'
                );
            })
            ->count();

        return view(
            'measurements.index',
            compact(
                'measurements',
                'totalMeasurements',
                'voltageMeasurements',
                'currentMeasurements',
                'temperatureMeasurements'
            )
        );
    }

    /**
     * Show the measurement creation form.
     */
    public function create()
    {
        $components = Component::with([
            'installation',
            'componentType',
        ])
            ->where('status', '!=', 'Replaced')
            ->orderBy('name')
            ->get();

        $users = User::orderBy('name')->get();

        return view(
            'measurements.create',
            compact(
                'components',
                'users'
            )
        );
    }

    /**
     * Store a new measurement.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'component_id' => [
                'required',
                'exists:components,id',
            ],

            'recorded_by' => [
                'required',
                'exists:users,id',
            ],

            'measurement_date' => [
                'required',
                'date',
            ],

            'parameter' => [
                'required',
                'string',
                'max:255',
            ],

            'value' => [
                'required',
                'numeric',
            ],

            'unit' => [
                'required',
                'string',
                'max:50',
            ],

            'reference_value' => [
                'nullable',
                'numeric',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        Measurement::create($validated);

        return redirect()
            ->route('measurements.index')
            ->with(
                'success',
                'Measurement recorded successfully.'
            );
    }

    /**
     * Display a specific measurement.
     */
    public function show(Measurement $measurement)
    {
        $measurement->load([
            'component.installation',
            'component.componentType',
            'recorder',
        ]);

        return view(
            'measurements.show',
            compact('measurement')
        );
    }

    /**
     * Show the measurement edit form.
     */
    public function edit(Measurement $measurement)
    {
        $components = Component::with([
            'installation',
            'componentType',
        ])
            ->where('status', '!=', 'Replaced')
            ->orderBy('name')
            ->get();

        $users = User::orderBy('name')->get();

        return view(
            'measurements.edit',
            compact(
                'measurement',
                'components',
                'users'
            )
        );
    }

    /**
     * Update a measurement.
     */
    public function update(
        Request $request,
        Measurement $measurement
    ) {
        $validated = $request->validate([
            'component_id' => [
                'required',
                'exists:components,id',
            ],

            'recorded_by' => [
                'required',
                'exists:users,id',
            ],

            'measurement_date' => [
                'required',
                'date',
            ],

            'parameter' => [
                'required',
                'string',
                'max:255',
            ],

            'value' => [
                'required',
                'numeric',
            ],

            'unit' => [
                'required',
                'string',
                'max:50',
            ],

            'reference_value' => [
                'nullable',
                'numeric',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        $measurement->update($validated);

        return redirect()
            ->route(
                'measurements.show',
                $measurement
            )
            ->with(
                'success',
                'Measurement updated successfully.'
            );
    }

    /**
     * Delete a measurement.
     */
    public function destroy(Measurement $measurement)
    {
        $measurement->delete();

        return redirect()
            ->route('measurements.index')
            ->with(
                'success',
                'Measurement deleted successfully.'
            );
    }
}