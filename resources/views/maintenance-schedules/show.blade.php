<x-app-layout>

    <x-slot name="header">

        <div class="d-flex justify-content-between align-items-center">

            <div>
                <h4 class="fw-bold mb-1">
                    <i class="bi bi-calendar-check me-2 text-success"></i>
                    Maintenance Schedule Details
                </h4>

                <p class="text-muted mb-0">
                    View preventive maintenance schedule information.
                </p>
            </div>

            <div class="d-flex gap-2">

                <a href="{{ route('maintenance-schedules.index') }}"
                   class="btn btn-outline-secondary">

                    <i class="bi bi-arrow-left me-1"></i>
                    Back

                </a>

                <a href="{{ route('maintenance-schedules.edit', $maintenanceSchedule) }}"
                   class="btn btn-success">

                    <i class="bi bi-pencil-square me-1"></i>
                    Edit

                </a>

            </div>

        </div>

    </x-slot>


    <div class="container-fluid py-3">

        @php
            $today = \Illuminate\Support\Carbon::today();

            $nextDue = $maintenanceSchedule->next_due_date
                ? \Illuminate\Support\Carbon::parse($maintenanceSchedule->next_due_date)
                : null;

            $isCompleted = $maintenanceSchedule->status === 'Completed';

            $isOverdue = $nextDue
                && $nextDue->lt($today)
                && !$isCompleted;

            $isDueToday = $nextDue
                && $nextDue->equalTo($today)
                && !$isCompleted;

            $isUpcoming = $nextDue
                && $nextDue->gt($today)
                && $nextDue->lte($today->copy()->addDays(7))
                && !$isCompleted;
        @endphp


        {{-- Status Alert --}}
        @if($isOverdue)

            <div class="alert alert-danger shadow-sm border-0">

                <div class="d-flex align-items-center">

                    <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>

                    <div>
                        <strong>Maintenance Overdue</strong>

                        <div>
                            This maintenance activity was due on
                            <strong>{{ $nextDue->format('d M Y') }}</strong>.
                        </div>
                    </div>

                </div>

            </div>

        @elseif($isDueToday)

            <div class="alert alert-warning shadow-sm border-0">

                <div class="d-flex align-items-center">

                    <i class="bi bi-clock-fill fs-4 me-3"></i>

                    <div>
                        <strong>Maintenance Due Today</strong>

                        <div>
                            This maintenance activity is scheduled for today.
                        </div>
                    </div>

                </div>

            </div>

        @elseif($isUpcoming)

            <div class="alert alert-primary shadow-sm border-0">

                <div class="d-flex align-items-center">

                    <i class="bi bi-calendar-event-fill fs-4 me-3"></i>

                    <div>
                        <strong>Maintenance Due Soon</strong>

                        <div>
                            This maintenance activity is scheduled within the next 7 days.
                        </div>
                    </div>

                </div>

            </div>

        @elseif($isCompleted)

            <div class="alert alert-success shadow-sm border-0">

                <div class="d-flex align-items-center">

                    <i class="bi bi-check-circle-fill fs-4 me-3"></i>

                    <div>
                        <strong>Maintenance Completed</strong>

                        <div>
                            This maintenance schedule has been marked as completed.
                        </div>
                    </div>

                </div>

            </div>

        @endif


        <div class="row g-4">

            {{-- Main Information --}}
            <div class="col-lg-8">

                {{-- Schedule Overview --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-bottom py-3">

                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-info-circle me-2 text-success"></i>
                            Schedule Overview
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row g-4">

                            {{-- Maintenance Task --}}
                            <div class="col-md-12">

                                <label class="text-muted small text-uppercase fw-semibold">
                                    Maintenance Task
                                </label>

                                <div class="fs-5 fw-semibold mt-1">
                                    {{ $maintenanceSchedule->maintenance_task }}
                                </div>

                            </div>


                            {{-- Frequency --}}
                            <div class="col-md-6">

                                <label class="text-muted small text-uppercase fw-semibold">
                                    Frequency
                                </label>

                                <div class="mt-2">

                                    <span class="badge bg-light text-dark border px-3 py-2">

                                        <i class="bi bi-arrow-repeat me-1"></i>

                                        {{ $maintenanceSchedule->frequency }}

                                    </span>

                                </div>

                            </div>


                            {{-- Priority --}}
                            <div class="col-md-6">

                                <label class="text-muted small text-uppercase fw-semibold">
                                    Priority
                                </label>

                                <div class="mt-2">

                                    @switch($maintenanceSchedule->priority)

                                        @case('Critical')

                                            <span class="badge bg-danger px-3 py-2">
                                                <i class="bi bi-exclamation-octagon me-1"></i>
                                                Critical
                                            </span>

                                            @break

                                        @case('High')

                                            <span class="badge bg-warning text-dark px-3 py-2">
                                                <i class="bi bi-exclamation-triangle me-1"></i>
                                                High
                                            </span>

                                            @break

                                        @case('Medium')

                                            <span class="badge bg-info text-dark px-3 py-2">
                                                <i class="bi bi-dash-circle me-1"></i>
                                                Medium
                                            </span>

                                            @break

                                        @default

                                            <span class="badge bg-secondary px-3 py-2">
                                                <i class="bi bi-circle me-1"></i>
                                                Low
                                            </span>

                                    @endswitch

                                </div>

                            </div>


                            {{-- Last Maintenance --}}
                            <div class="col-md-6">

                                <label class="text-muted small text-uppercase fw-semibold">
                                    Last Maintenance Date
                                </label>

                                <div class="mt-1 fw-semibold">

                                    @if($maintenanceSchedule->last_maintenance_date)

                                        <i class="bi bi-calendar-check text-success me-1"></i>

                                        {{ $maintenanceSchedule->last_maintenance_date->format('d M Y') }}

                                    @else

                                        <span class="text-muted">
                                            No previous maintenance recorded
                                        </span>

                                    @endif

                                </div>

                            </div>


                            {{-- Next Due --}}
                            <div class="col-md-6">

                                <label class="text-muted small text-uppercase fw-semibold">
                                    Next Due Date
                                </label>

                                <div class="mt-1 fw-semibold">

                                    @if($nextDue)

                                        <i class="bi bi-calendar-event
                                            {{ $isOverdue ? 'text-danger' : '' }}
                                            {{ $isDueToday ? 'text-warning' : '' }}
                                            {{ $isUpcoming ? 'text-primary' : '' }}
                                            {{ !$isOverdue && !$isDueToday && !$isUpcoming ? 'text-success' : '' }}
                                            me-1"></i>

                                        {{ $nextDue->format('d M Y') }}

                                    @else

                                        <span class="text-muted">
                                            Not specified
                                        </span>

                                    @endif

                                </div>

                            </div>


                            {{-- Status --}}
                            <div class="col-md-6">

                                <label class="text-muted small text-uppercase fw-semibold">
                                    Schedule Status
                                </label>

                                <div class="mt-2">

                                    @switch($maintenanceSchedule->status)

                                        @case('Completed')

                                            <span class="badge bg-success px-3 py-2">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Completed
                                            </span>

                                            @break

                                        @case('Overdue')

                                            <span class="badge bg-danger px-3 py-2">
                                                <i class="bi bi-exclamation-circle me-1"></i>
                                                Overdue
                                            </span>

                                            @break

                                        @case('Due')

                                            <span class="badge bg-warning text-dark px-3 py-2">
                                                <i class="bi bi-clock me-1"></i>
                                                Due
                                            </span>

                                            @break

                                        @default

                                            <span class="badge bg-primary px-3 py-2">
                                                <i class="bi bi-calendar-check me-1"></i>
                                                Scheduled
                                            </span>

                                    @endswitch

                                </div>

                            </div>


                            {{-- Created --}}
                            <div class="col-md-6">

                                <label class="text-muted small text-uppercase fw-semibold">
                                    Created
                                </label>

                                <div class="mt-1 text-muted">

                                    {{ $maintenanceSchedule->created_at->format('d M Y, h:i A') }}

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Component Information --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-bottom py-3">

                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-cpu me-2 text-success"></i>
                            Component Information
                        </h5>

                    </div>

                    <div class="card-body">

                        @if($maintenanceSchedule->component)

                            <div class="row g-4">

                                <div class="col-md-6">

                                    <label class="text-muted small text-uppercase fw-semibold">
                                        Component Name
                                    </label>

                                    <div class="mt-1 fw-semibold">

                                        {{ $maintenanceSchedule->component->name }}

                                    </div>

                                </div>


                                <div class="col-md-6">

                                    <label class="text-muted small text-uppercase fw-semibold">
                                        Component Type
                                    </label>

                                    <div class="mt-1">

                                        {{ $maintenanceSchedule->component->componentType->name ?? 'N/A' }}

                                    </div>

                                </div>


                                <div class="col-md-6">

                                    <label class="text-muted small text-uppercase fw-semibold">
                                        Manufacturer
                                    </label>

                                    <div class="mt-1">

                                        {{ $maintenanceSchedule->component->manufacturer ?: 'N/A' }}

                                    </div>

                                </div>


                                <div class="col-md-6">

                                    <label class="text-muted small text-uppercase fw-semibold">
                                        Model
                                    </label>

                                    <div class="mt-1">

                                        {{ $maintenanceSchedule->component->model ?: 'N/A' }}

                                    </div>

                                </div>


                                <div class="col-md-6">

                                    <label class="text-muted small text-uppercase fw-semibold">
                                        Serial Number
                                    </label>

                                    <div class="mt-1">

                                        {{ $maintenanceSchedule->component->serial_number ?: 'N/A' }}

                                    </div>

                                </div>


                                <div class="col-md-6">

                                    <label class="text-muted small text-uppercase fw-semibold">
                                        Current Condition
                                    </label>

                                    <div class="mt-2">

                                        @php
                                            $condition = $maintenanceSchedule->component->current_condition;
                                        @endphp

                                        @switch($condition)

                                            @case('Excellent')
                                                <span class="badge bg-success px-3 py-2">
                                                    Excellent
                                                </span>
                                                @break

                                            @case('Good')
                                                <span class="badge bg-primary px-3 py-2">
                                                    Good
                                                </span>
                                                @break

                                            @case('Fair')
                                                <span class="badge bg-warning text-dark px-3 py-2">
                                                    Fair
                                                </span>
                                                @break

                                            @case('Poor')
                                                <span class="badge bg-danger px-3 py-2">
                                                    Poor
                                                </span>
                                                @break

                                            @case('Critical')
                                                <span class="badge bg-dark px-3 py-2">
                                                    Critical
                                                </span>
                                                @break

                                            @default
                                                <span class="badge bg-secondary px-3 py-2">
                                                    N/A
                                                </span>

                                        @endswitch

                                    </div>

                                </div>


                                <div class="col-md-12">

                                    <label class="text-muted small text-uppercase fw-semibold">
                                        Installation
                                    </label>

                                    <div class="mt-1">

                                        @if($maintenanceSchedule->component->installation)

                                            <a href="{{ route(
                                                'installations.show',
                                                $maintenanceSchedule->component->installation
                                            ) }}"
                                               class="text-decoration-none fw-semibold">

                                                <i class="bi bi-building me-1"></i>

                                                {{ $maintenanceSchedule->component->installation->name }}

                                            </a>

                                            @if($maintenanceSchedule->component->installation->location)

                                                <div class="small text-muted mt-1">

                                                    <i class="bi bi-geo-alt me-1"></i>

                                                    {{ $maintenanceSchedule->component->installation->location }}

                                                </div>

                                            @endif

                                        @else

                                            <span class="text-muted">
                                                N/A
                                            </span>

                                        @endif

                                    </div>

                                </div>

                            </div>

                        @else

                            <div class="text-muted">
                                Component information is unavailable.
                            </div>

                        @endif

                    </div>

                </div>

            </div>


            {{-- Right Sidebar --}}
            <div class="col-lg-4">

                {{-- Schedule Status Card --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-success text-white py-3">

                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-shield-check me-2"></i>
                            Maintenance Status
                        </h6>

                    </div>

                    <div class="card-body text-center py-4">

                        @if($isCompleted)

                            <i class="bi bi-check-circle-fill text-success"
                               style="font-size: 60px;"></i>

                            <h5 class="fw-bold mt-3">
                                Completed
                            </h5>

                            <p class="text-muted mb-0">
                                This maintenance activity has been completed.
                            </p>

                        @elseif($isOverdue)

                            <i class="bi bi-exclamation-triangle-fill text-danger"
                               style="font-size: 60px;"></i>

                            <h5 class="fw-bold mt-3 text-danger">
                                Overdue
                            </h5>

                            <p class="text-muted mb-0">
                                Immediate attention is recommended.
                            </p>

                        @elseif($isDueToday)

                            <i class="bi bi-clock-fill text-warning"
                               style="font-size: 60px;"></i>

                            <h5 class="fw-bold mt-3 text-warning">
                                Due Today
                            </h5>

                            <p class="text-muted mb-0">
                                This maintenance activity should be performed today.
                            </p>

                        @elseif($isUpcoming)

                            <i class="bi bi-calendar-event-fill text-primary"
                               style="font-size: 60px;"></i>

                            <h5 class="fw-bold mt-3 text-primary">
                                Due Soon
                            </h5>

                            <p class="text-muted mb-0">
                                Maintenance is scheduled within the next 7 days.
                            </p>

                        @else

                            <i class="bi bi-calendar-check-fill text-success"
                               style="font-size: 60px;"></i>

                            <h5 class="fw-bold mt-3">
                                Scheduled
                            </h5>

                            <p class="text-muted mb-0">
                                This maintenance activity is currently scheduled.
                            </p>

                        @endif

                    </div>

                </div>


                {{-- Quick Actions --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-bottom py-3">

                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-lightning-charge me-2 text-warning"></i>
                            Quick Actions
                        </h6>

                    </div>

                    <div class="card-body">

                        <a href="{{ route('maintenance-schedules.edit', $maintenanceSchedule) }}"
                           class="btn btn-outline-primary w-100 mb-2">

                            <i class="bi bi-pencil-square me-1"></i>
                            Edit Schedule

                        </a>


                        <form action="{{ route('maintenance-schedules.destroy', $maintenanceSchedule) }}"
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this maintenance schedule?');">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-outline-danger w-100">

                                <i class="bi bi-trash me-1"></i>
                                Delete Schedule

                            </button>

                        </form>

                    </div>

                </div>


                {{-- Preventive Maintenance Note --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <h6 class="fw-bold mb-3">

                            <i class="bi bi-lightbulb text-warning me-2"></i>

                            Preventive Maintenance

                        </h6>

                        <p class="small text-muted mb-0">

                            Regular preventive maintenance helps identify component
                            deterioration early, reduce unexpected failures and
                            support reliable operation of the solar-battery system.

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>