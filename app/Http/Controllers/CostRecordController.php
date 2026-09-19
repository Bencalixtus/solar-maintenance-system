<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\CostRecord;
use App\Models\MaintenanceRecord;
use Illuminate\Http\Request;

class CostRecordController extends Controller
{
    /**
     * Display a listing of cost records.
     */
    public function index()
    {
        $costRecords = CostRecord::with([
            'component.installation',
            'maintenance',
        ])
            ->latest('cost_date')
            ->latest('id')
            ->get();

        $totalCost = CostRecord::sum('amount');

        $maintenanceCost = CostRecord::where(
            'cost_type',
            'Maintenance'
        )->sum('amount');

        $repairCost = CostRecord::where(
            'cost_type',
            'Repair'
        )->sum('amount');

        $replacementCost = CostRecord::where(
            'cost_type',
            'Replacement'
        )->sum('amount');

        $partsCost = CostRecord::where(
            'cost_type',
            'Parts/Materials'
        )->sum('amount');

        $labourCost = CostRecord::where(
            'cost_type',
            'Labour/Service'
        )->sum('amount');

        $otherCost = CostRecord::where(
            'cost_type',
            'Other'
        )->sum('amount');

        $costByType = [
            'Maintenance' => $maintenanceCost,
            'Repair' => $repairCost,
            'Replacement' => $replacementCost,
            'Parts/Materials' => $partsCost,
            'Labour/Service' => $labourCost,
            'Other' => $otherCost,
        ];

        $componentCosts = CostRecord::with('component')
            ->selectRaw('component_id, SUM(amount) as total_cost')
            ->groupBy('component_id')
            ->orderByDesc('total_cost')
            ->get();

        return view('cost-records.index', compact(
            'costRecords',
            'totalCost',
            'maintenanceCost',
            'repairCost',
            'replacementCost',
            'partsCost',
            'labourCost',
            'otherCost',
            'costByType',
            'componentCosts'
        ));
    }

    /**
     * Show the form for creating a new cost record.
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

        $maintenanceRecords = MaintenanceRecord::with('component')
            ->latest('maintenance_date')
            ->get();

        return view('cost-records.create', compact(
            'components',
            'maintenanceRecords'
        ));
    }

    /**
     * Store a newly created cost record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'component_id' => [
                'required',
                'exists:components,id',
            ],

            'maintenance_id' => [
                'nullable',
                'exists:maintenance_records,id',
            ],

            'cost_type' => [
                'required',
                'in:Maintenance,Repair,Replacement,Parts/Materials,Labour/Service,Other',
            ],

            'description' => [
                'required',
                'string',
                'max:1000',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'cost_date' => [
                'required',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        CostRecord::create($validated);

        return redirect()
            ->route('cost-records.index')
            ->with(
                'success',
                'Cost record added successfully.'
            );
    }

    /**
     * Display the specified cost record.
     */
    public function show(CostRecord $costRecord)
    {
        $costRecord->load([
            'component.installation',
            'component.componentType',
            'maintenance.component',
        ]);

        return view('cost-records.show', compact(
            'costRecord'
        ));
    }

    /**
     * Show the form for editing the specified cost record.
     */
    public function edit(CostRecord $costRecord)
    {
        $components = Component::with([
            'installation',
            'componentType',
        ])
            ->orderBy('name')
            ->get();

        $maintenanceRecords = MaintenanceRecord::with('component')
            ->latest('maintenance_date')
            ->get();

        return view('cost-records.edit', compact(
            'costRecord',
            'components',
            'maintenanceRecords'
        ));
    }

    /**
     * Update the specified cost record.
     */
    public function update(
        Request $request,
        CostRecord $costRecord
    ) {
        $validated = $request->validate([
            'component_id' => [
                'required',
                'exists:components,id',
            ],

            'maintenance_id' => [
                'nullable',
                'exists:maintenance_records,id',
            ],

            'cost_type' => [
                'required',
                'in:Maintenance,Repair,Replacement,Parts/Materials,Labour/Service,Other',
            ],

            'description' => [
                'required',
                'string',
                'max:1000',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'cost_date' => [
                'required',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $costRecord->update($validated);

        return redirect()
            ->route('cost-records.index')
            ->with(
                'success',
                'Cost record updated successfully.'
            );
    }

    /**
     * Remove the specified cost record.
     */
    public function destroy(CostRecord $costRecord)
    {
        $costRecord->delete();

        return redirect()
            ->route('cost-records.index')
            ->with(
                'success',
                'Cost record deleted successfully.'
            );
    }
}