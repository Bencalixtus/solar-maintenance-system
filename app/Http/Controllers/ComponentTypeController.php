<?php

namespace App\Http\Controllers;

use App\Models\ComponentType;
use Illuminate\Http\Request;

class ComponentTypeController extends Controller
{
    /**
     * Display all component types.
     */
    public function index()
    {
        $componentTypes = ComponentType::withCount('components')
            ->latest()
            ->paginate(10);

        return view('component-types.index', compact('componentTypes'));
    }

    /**
     * Show the form for creating a component type.
     */
    public function create()
    {
        return view('component-types.create');
    }

    /**
     * Store a new component type.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:component_types,name'],
            'description' => ['nullable', 'string'],
        ]);

        ComponentType::create($validated);

        return redirect()
            ->route('component-types.index')
            ->with('success', 'Component type added successfully.');
    }

    /**
     * Display a component type.
     */
    public function show(ComponentType $componentType)
    {
        $componentType->load('components');

        return view('component-types.show', compact('componentType'));
    }

    /**
     * Show the form for editing a component type.
     */
    public function edit(ComponentType $componentType)
    {
        return view('component-types.edit', compact('componentType'));
    }

    /**
     * Update a component type.
     */
    public function update(Request $request, ComponentType $componentType)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:component_types,name,' . $componentType->id,
            ],
            'description' => ['nullable', 'string'],
        ]);

        $componentType->update($validated);

        return redirect()
            ->route('component-types.index')
            ->with('success', 'Component type updated successfully.');
    }

    /**
     * Delete a component type.
     */
    public function destroy(ComponentType $componentType)
    {
        if ($componentType->components()->exists()) {
            return redirect()
                ->route('component-types.index')
                ->with('error', 'This component type cannot be deleted because it has registered components.');
        }

        $componentType->delete();

        return redirect()
            ->route('component-types.index')
            ->with('success', 'Component type deleted successfully.');
    }
}