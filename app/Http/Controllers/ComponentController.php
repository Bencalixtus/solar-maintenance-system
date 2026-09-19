<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\ComponentType;
use App\Models\Installation;
use Illuminate\Http\Request;

class ComponentController extends Controller
{
    /**
     * Display all components.
     */
    public function index()
    {
        $components = Component::with([
            'installation',
            'componentType'
        ])
            ->latest()
            ->paginate(10);

        return view('components.index', compact('components'));
    }

    /**
     * Show the component registration form.
     */
    public function create()
    {
        $installations = Installation::orderBy('name')->get();

        $componentTypes = ComponentType::orderBy('name')->get();

        return view('components.create', compact(
            'installations',
            'componentTypes'
        ));
    }

    /**
     * Store a newly registered component.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'installation_id' => [
                'required',
                'exists:installations,id',
            ],

            'component_type_id' => [
                'required',
                'exists:component_types,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'manufacturer' => [
                'nullable',
                'string',
                'max:255',
            ],

            'model' => [
                'nullable',
                'string',
                'max:255',
            ],

            'serial_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'installation_date' => [
                'required',
                'date',
            ],

            'rated_capacity' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'rated_voltage' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'expected_lifespan' => [
                'nullable',
                'integer',
                'min:1',
                'max:100',
            ],

            'current_condition' => [
                'required',
                'in:Excellent,Good,Fair,Poor,Critical',
            ],

            'status' => [
                'required',
                'in:Active,Under Maintenance,Inactive,Replaced',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        Component::create($validated);

        return redirect()
            ->route('components.index')
            ->with('success', 'Component registered successfully.');
    }

    /**
     * Display a component.
     */
public function show(Component $component)
{
    $component->load([
        'installation',
        'componentType',
        'measurements',
        'maintenanceSchedules',
        'maintenanceRecords',
        'costRecords',
        'replacementForecasts',
    ]);

    return view('components.show', [
        'solarComponent' => $component,
    ]);
}
    /**
     * Show the component edit form.
     */
    public function edit(Component $component)
    {
        $installations = Installation::orderBy('name')->get();

        $componentTypes = ComponentType::orderBy('name')->get();

        return view('components.edit', compact(
            'component',
            'installations',
            'componentTypes'
        ));
    }

    /**
     * Update a component.
     */
    public function update(Request $request, Component $component)
    {
        $validated = $request->validate([
            'installation_id' => [
                'required',
                'exists:installations,id',
            ],

            'component_type_id' => [
                'required',
                'exists:component_types,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'manufacturer' => [
                'nullable',
                'string',
                'max:255',
            ],

            'model' => [
                'nullable',
                'string',
                'max:255',
            ],

            'serial_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'installation_date' => [
                'required',
                'date',
            ],

            'rated_capacity' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'rated_voltage' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'expected_lifespan' => [
                'nullable',
                'integer',
                'min:1',
                'max:100',
            ],

            'current_condition' => [
                'required',
                'in:Excellent,Good,Fair,Poor,Critical',
            ],

            'status' => [
                'required',
                'in:Active,Under Maintenance,Inactive,Replaced',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        $component->update($validated);

        return redirect()
            ->route('components.show', $component)
            ->with('success', 'Component updated successfully.');
    }

    /**
     * Delete a component.
     */
    public function destroy(Component $component)
    {
        $component->delete();

        return redirect()
            ->route('components.index')
            ->with('success', 'Component deleted successfully.');
    }
}