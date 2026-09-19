<?php

namespace App\Http\Controllers;

use App\Models\Installation;
use Illuminate\Http\Request;

class InstallationController extends Controller
{
    /**
     * Display a listing of installations.
     */
    public function index()
    {
        $installations = Installation::latest()->paginate(10);

        return view('installations.index', compact('installations'));
    }


    /**
     * Show the form for creating a new installation.
     */
    public function create()
    {
        return view('installations.create');
    }


    /**
     * Store a newly created installation.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'location' => [
                'required',
                'string',
                'max:255',
            ],

            'installation_date' => [
                'required',
                'date',
            ],

            'system_capacity' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:Active,Under Maintenance,Inactive',
            ],
        ]);


        Installation::create($validated);


        return redirect()
            ->route('installations.index')
            ->with('success', 'Solar installation added successfully.');
    }


    /**
     * Display the specified installation.
     */
    public function show(Installation $installation)
    {
        $installation->load('components');

        return view('installations.show', compact('installation'));
    }


    /**
     * Show the form for editing the specified installation.
     */
    public function edit(Installation $installation)
    {
        return view('installations.edit', compact('installation'));
    }


    /**
     * Update the specified installation.
     */
    public function update(Request $request, Installation $installation)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'location' => [
                'required',
                'string',
                'max:255',
            ],

            'installation_date' => [
                'required',
                'date',
            ],

            'system_capacity' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:Active,Under Maintenance,Inactive',
            ],
        ]);


        $installation->update($validated);


        return redirect()
            ->route('installations.index')
            ->with('success', 'Solar installation updated successfully.');
    }


    /**
     * Remove the specified installation.
     */
    public function destroy(Installation $installation)
    {
        $installation->delete();


        return redirect()
            ->route('installations.index')
            ->with('success', 'Solar installation deleted successfully.');
    }
}