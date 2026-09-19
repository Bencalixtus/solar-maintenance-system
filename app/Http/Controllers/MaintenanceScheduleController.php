<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\MaintenanceSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MaintenanceScheduleController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $schedules = MaintenanceSchedule::with([
            'component.installation',
            'component.componentType',
        ])
            ->orderBy('next_due_date')
            ->get();

        $totalSchedules = $schedules->count();

        $overdueSchedules = $schedules->filter(function ($schedule) use ($today) {
            return $schedule->next_due_date &&
                $schedule->next_due_date->lt($today) &&
                $schedule->status !== 'Completed';
        })->count();

        $dueToday = $schedules->filter(function ($schedule) use ($today) {
            return $schedule->next_due_date &&
                $schedule->next_due_date->equalTo($today) &&
                $schedule->status !== 'Completed';
        })->count();

        $upcomingSchedules = $schedules->filter(function ($schedule) use ($today) {
            return $schedule->next_due_date &&
                $schedule->next_due_date->gt($today) &&
                $schedule->next_due_date->lte($today->copy()->addDays(7)) &&
                $schedule->status !== 'Completed';
        })->count();

        $completedSchedules = $schedules->where('status', 'Completed')->count();

        return view('maintenance-schedules.index', compact(
            'schedules',
            'totalSchedules',
            'overdueSchedules',
            'dueToday',
            'upcomingSchedules',
            'completedSchedules'
        ));
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

        return view('maintenance-schedules.create', compact('components'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'component_id' => [
                'required',
                'exists:components,id',
            ],

            'maintenance_task' => [
                'required',
                'string',
                'max:255',
            ],

            'frequency' => [
                'required',
                'in:Weekly,Monthly,Quarterly,Semi-Annually,Annually,As Needed',
            ],

            'last_maintenance_date' => [
                'nullable',
                'date',
            ],

            'next_due_date' => [
                'required',
                'date',
            ],

            'priority' => [
                'required',
                'in:Low,Medium,High,Critical',
            ],

            'status' => [
                'required',
                'in:Scheduled,Due,Overdue,Completed',
            ],
        ]);

        $component = Component::findOrFail($validated['component_id']);

        MaintenanceSchedule::create($validated);

        return redirect()
            ->route('maintenance-schedules.index')
            ->with('success', 'Maintenance schedule created successfully.');
    }

    public function show(MaintenanceSchedule $maintenanceSchedule)
    {
        $maintenanceSchedule->load([
            'component.installation',
            'component.componentType',
        ]);

        return view('maintenance-schedules.show', compact('maintenanceSchedule'));
    }

    public function edit(MaintenanceSchedule $maintenanceSchedule)
    {
        $components = Component::with([
            'installation',
            'componentType',
        ])
            ->where('status', '!=', 'Replaced')
            ->orderBy('name')
            ->get();

        return view('maintenance-schedules.edit', compact(
            'maintenanceSchedule',
            'components'
        ));
    }

    public function update(
        Request $request,
        MaintenanceSchedule $maintenanceSchedule
    ) {
        $validated = $request->validate([
            'component_id' => [
                'required',
                'exists:components,id',
            ],

            'maintenance_task' => [
                'required',
                'string',
                'max:255',
            ],

            'frequency' => [
                'required',
                'in:Weekly,Monthly,Quarterly,Semi-Annually,Annually,As Needed',
            ],

            'last_maintenance_date' => [
                'nullable',
                'date',
            ],

            'next_due_date' => [
                'required',
                'date',
            ],

            'priority' => [
                'required',
                'in:Low,Medium,High,Critical',
            ],

            'status' => [
                'required',
                'in:Scheduled,Due,Overdue,Completed',
            ],
        ]);

        $maintenanceSchedule->update($validated);

        return redirect()
            ->route('maintenance-schedules.index')
            ->with('success', 'Maintenance schedule updated successfully.');
    }

    public function destroy(MaintenanceSchedule $maintenanceSchedule)
    {
        $maintenanceSchedule->delete();

        return redirect()
            ->route('maintenance-schedules.index')
            ->with('success', 'Maintenance schedule deleted successfully.');
    }
}