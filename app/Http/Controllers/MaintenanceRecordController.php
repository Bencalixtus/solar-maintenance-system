<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\MaintenanceRecord;
use App\Models\User;
use Illuminate\Http\Request;

class MaintenanceRecordController extends Controller
{
    public function index()
    {
        $records = MaintenanceRecord::with([
            'component.installation',
            'component.componentType',
            'technician',
        ])
            ->latest('maintenance_date')
            ->get();

        $totalRecords = $records->count();

        $completedRecords = $records
            ->where('status', 'Completed')
            ->count();

        $pendingRecords = $records
            ->where('status', 'Pending')
            ->count();

        $inProgressRecords = $records
            ->where('status', 'In Progress')
            ->count();

        $cancelledRecords = $records
            ->where('status', 'Cancelled')
            ->count();

        return view(
            'maintenance-records.index',
            compact(
                'records',
                'totalRecords',
                'completedRecords',
                'pendingRecords',
                'inProgressRecords',
                'cancelledRecords'
            )
        );
    }


    public function create()
    {
        $components = Component::with([
            'installation',
            'componentType',
        ])
            ->where('status', '!=', 'Replaced')
            ->orderBy('name')
            ->get();

        $technicians = User::orderBy('name')->get();

        return view(
            'maintenance-records.create',
            compact(
                'components',
                'technicians'
            )
        );
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'component_id' => [
                'required',
                'exists:components,id',
            ],

            'technician_id' => [
                'required',
                'exists:users,id',
            ],

            'maintenance_type' => [
                'required',
                'in:Preventive,Corrective,Inspection,Emergency,Replacement',
            ],

            'maintenance_date' => [
                'required',
                'date',
            ],

            'description' => [
                'required',
                'string',
            ],

            'condition_before' => [
                'required',
                'in:Excellent,Good,Fair,Poor,Critical',
            ],

            'action_taken' => [
                'required',
                'string',
            ],

            'condition_after' => [
                'required',
                'in:Excellent,Good,Fair,Poor,Critical',
            ],

            'next_due_date' => [
                'nullable',
                'date',
                'after_or_equal:maintenance_date',
            ],

            'status' => [
                'required',
                'in:Pending,In Progress,Completed,Cancelled',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        MaintenanceRecord::create($validated);

        return redirect()
            ->route('maintenance-records.index')
            ->with(
                'success',
                'Maintenance record created successfully.'
            );
    }


    public function show(MaintenanceRecord $maintenanceRecord)
    {
        $maintenanceRecord->load([
            'component.installation',
            'component.componentType',
            'technician',
        ]);

        return view(
            'maintenance-records.show',
            compact('maintenanceRecord')
        );
    }


    public function edit(MaintenanceRecord $maintenanceRecord)
    {
        $components = Component::with([
            'installation',
            'componentType',
        ])
            ->where('status', '!=', 'Replaced')
            ->orderBy('name')
            ->get();

        $technicians = User::orderBy('name')->get();

        return view(
            'maintenance-records.edit',
            compact(
                'maintenanceRecord',
                'components',
                'technicians'
            )
        );
    }


    public function update(
        Request $request,
        MaintenanceRecord $maintenanceRecord
    ) {
        $validated = $request->validate([
            'component_id' => [
                'required',
                'exists:components,id',
            ],

            'technician_id' => [
                'required',
                'exists:users,id',
            ],

            'maintenance_type' => [
                'required',
                'in:Preventive,Corrective,Inspection,Emergency,Replacement',
            ],

            'maintenance_date' => [
                'required',
                'date',
            ],

            'description' => [
                'required',
                'string',
            ],

            'condition_before' => [
                'required',
                'in:Excellent,Good,Fair,Poor,Critical',
            ],

            'action_taken' => [
                'required',
                'string',
            ],

            'condition_after' => [
                'required',
                'in:Excellent,Good,Fair,Poor,Critical',
            ],

            'next_due_date' => [
                'nullable',
                'date',
                'after_or_equal:maintenance_date',
            ],

            'status' => [
                'required',
                'in:Pending,In Progress,Completed,Cancelled',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        $maintenanceRecord->update($validated);

        return redirect()
            ->route(
                'maintenance-records.show',
                $maintenanceRecord
            )
            ->with(
                'success',
                'Maintenance record updated successfully.'
            );
    }


    public function destroy(MaintenanceRecord $maintenanceRecord)
    {
        $maintenanceRecord->delete();

        return redirect()
            ->route('maintenance-records.index')
            ->with(
                'success',
                'Maintenance record deleted successfully.'
            );
    }
}