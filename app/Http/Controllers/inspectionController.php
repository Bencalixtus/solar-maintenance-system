<?php

namespace App\Http\Controllers;

use App\Models\Installation;
use App\Models\Inspection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InspectionController extends Controller
{
    /**
     * Display all inspections.
     */
    public function index()
    {
        $inspections = Inspection::with([
            'installation',
            'inspector',
        ])
            ->latest('inspection_date')
            ->paginate(10);

        return view('inspections.index', compact('inspections'));
    }

    /**
     * Show inspection form.
     */
    public function create()
    {
        $installations = Installation::with('components.componentType')
            ->where('status', '!=', 'Inactive')
            ->orderBy('name')
            ->get();

        return view('inspections.create', compact('installations'));
    }

    /**
     * Store a new inspection.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'installation_id' => [
                'required',
                'exists:installations,id'
            ],

            'inspection_date' => [
                'required',
                'date'
            ],

            'overall_condition' => [
                'required',
                'in:Excellent,Good,Fair,Poor,Critical'
            ],

            'general_observation' => [
                'nullable',
                'string'
            ],

            'recommendation' => [
                'nullable',
                'string'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],

            'next_inspection_date' => [
                'required',
                'date',
                'after_or_equal:inspection_date'
            ],

            'items' => [
                'required',
                'array',
                'min:1'
            ],

            'items.*.component_id' => [
                'required',
                'exists:components,id'
            ],

            'items.*.check_item' => [
                'required',
                'string',
                'max:255'
            ],

            'items.*.result' => [
                'required',
                'in:Pass,Fail,Needs Attention,Not Applicable'
            ],

            'items.*.measurement' => [
                'nullable',
                'numeric'
            ],

            'items.*.unit' => [
                'nullable',
                'string',
                'max:50'
            ],

            'items.*.condition' => [
                'required',
                'in:Excellent,Good,Fair,Poor,Critical'
            ],

            'items.*.remarks' => [
                'nullable',
                'string'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Verify components belong to selected installation
        |--------------------------------------------------------------------------
        */

        $installation = Installation::with('components')
            ->findOrFail($validated['installation_id']);

        $installationComponentIds = $installation->components
            ->pluck('id')
            ->toArray();

        foreach ($validated['items'] as $item) {
            if (!in_array($item['component_id'], $installationComponentIds)) {
                throw ValidationException::withMessages([
                    'items' => 'One or more selected components do not belong to the selected installation.',
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Save inspection and checklist items
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($validated) {

            $inspection = Inspection::create([
                'installation_id' => $validated['installation_id'],
                'inspector_id' => Auth::id(),
                'inspection_date' => $validated['inspection_date'],
                'overall_condition' => $validated['overall_condition'],
                'general_observation' => $validated['general_observation'] ?? null,
                'recommendation' => $validated['recommendation'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
                'next_inspection_date' => $validated['next_inspection_date'],
            ]);

            foreach ($validated['items'] as $item) {

                $inspection->inspectionItems()->create([
                    'component_id' => $item['component_id'],
                    'check_item' => $item['check_item'],
                    'result' => $item['result'],
                    'measurement' => $item['measurement'] ?? null,
                    'unit' => $item['unit'] ?? null,
                    'condition' => $item['condition'],
                    'remarks' => $item['remarks'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('inspections.index')
            ->with(
                'success',
                'Inspection completed and recorded successfully.'
            );
    }

    /**
     * Display a single inspection.
     */
    public function show(Inspection $inspection)
    {
        $inspection->load([
            'installation',
            'inspector',
            'inspectionItems.component.componentType',
        ]);

        return view('inspections.show', compact('inspection'));
    }

    /**
     * Show edit form.
     */
    public function edit(Inspection $inspection)
    {
        $inspection->load([
            'inspectionItems.component.componentType',
        ]);

        $installations = Installation::with(
            'components.componentType'
        )
            ->orderBy('name')
            ->get();

        return view('inspections.edit', compact(
            'inspection',
            'installations'
        ));
    }

    /**
     * Update inspection.
     */
    public function update(
        Request $request,
        Inspection $inspection
    ) {
        $validated = $request->validate([
            'installation_id' => [
                'required',
                'exists:installations,id'
            ],

            'inspection_date' => [
                'required',
                'date'
            ],

            'overall_condition' => [
                'required',
                'in:Excellent,Good,Fair,Poor,Critical'
            ],

            'general_observation' => [
                'nullable',
                'string'
            ],

            'recommendation' => [
                'nullable',
                'string'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],

            'next_inspection_date' => [
                'required',
                'date',
                'after_or_equal:inspection_date'
            ],

            'items' => [
                'required',
                'array',
                'min:1'
            ],

            'items.*.id' => [
                'nullable',
                'integer'
            ],

            'items.*.component_id' => [
                'required',
                'exists:components,id'
            ],

            'items.*.check_item' => [
                'required',
                'string',
                'max:255'
            ],

            'items.*.result' => [
                'required',
                'in:Pass,Fail,Needs Attention,Not Applicable'
            ],

            'items.*.measurement' => [
                'nullable',
                'numeric'
            ],

            'items.*.unit' => [
                'nullable',
                'string',
                'max:50'
            ],

            'items.*.condition' => [
                'required',
                'in:Excellent,Good,Fair,Poor,Critical'
            ],

            'items.*.remarks' => [
                'nullable',
                'string'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Verify selected components
        |--------------------------------------------------------------------------
        */

        $installation = Installation::with('components')
            ->findOrFail($validated['installation_id']);

        $installationComponentIds = $installation->components
            ->pluck('id')
            ->toArray();

        foreach ($validated['items'] as $item) {

            if (!in_array(
                $item['component_id'],
                $installationComponentIds
            )) {

                throw ValidationException::withMessages([
                    'items' =>
                        'One or more selected components do not belong to the selected installation.',
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Update inspection
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $inspection
        ) {

            $inspection->update([
                'installation_id' => $validated['installation_id'],
                'inspection_date' => $validated['inspection_date'],
                'overall_condition' => $validated['overall_condition'],
                'general_observation' =>
                    $validated['general_observation'] ?? null,
                'recommendation' =>
                    $validated['recommendation'] ?? null,
                'remarks' =>
                    $validated['remarks'] ?? null,
                'next_inspection_date' =>
                    $validated['next_inspection_date'],
            ]);

            /*
            |--------------------------------------------------------------------------
            | Replace checklist records
            |--------------------------------------------------------------------------
            */

            $inspection->inspectionItems()->delete();

            foreach ($validated['items'] as $item) {

                $inspection->inspectionItems()->create([
                    'component_id' => $item['component_id'],
                    'check_item' => $item['check_item'],
                    'result' => $item['result'],
                    'measurement' => $item['measurement'] ?? null,
                    'unit' => $item['unit'] ?? null,
                    'condition' => $item['condition'],
                    'remarks' => $item['remarks'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('inspections.show', $inspection)
            ->with(
                'success',
                'Inspection updated successfully.'
            );
    }

    /**
     * Print inspection report.
     */
    public function print(Inspection $inspection)
    {
        $inspection->load([
            'installation',
            'inspector',
            'inspectionItems.component.componentType',
        ]);

        return view(
            'inspections.print',
            compact('inspection')
        );
    }

    /**
     * Delete inspection.
     */
    public function destroy(Inspection $inspection)
    {
        DB::transaction(function () use ($inspection) {

            $inspection->inspectionItems()->delete();

            $inspection->delete();
        });

        return redirect()
            ->route('inspections.index')
            ->with(
                'success',
                'Inspection deleted successfully.'
            );
    }
}