<x-app-layout>

    <x-slot name="header">

        <div class="d-flex justify-content-between align-items-center">

            <div>
                <h4 class="fw-bold mb-1">
                    <i class="bi bi-pencil-square me-2 text-success"></i>
                    Edit Maintenance Schedule
                </h4>

                <p class="text-muted mb-0">
                    Update preventive maintenance schedule information.
                </p>
            </div>

            <a href="{{ route('maintenance-schedules.show', $maintenanceSchedule) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Back to Details

            </a>

        </div>

    </x-slot>


    <div class="container-fluid py-3">

        {{-- Validation Errors --}}
        @if ($errors->any())

            <div class="alert alert-danger shadow-sm">

                <div class="fw-bold mb-2">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Please correct the following errors:
                </div>

                <ul class="mb-0">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif


        <div class="row">

            {{-- Main Form --}}
            <div class="col-xl-8 col-lg-9">

                <form action="{{ route('maintenance-schedules.update', $maintenanceSchedule) }}"
                      method="POST">

                    @csrf
                    @method('PUT')


                    {{-- Component Information --}}
                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white border-bottom py-3">

                            <h5 class="fw-bold mb-0">

                                <i class="bi bi-cpu me-2 text-success"></i>

                                Component Information

                            </h5>

                        </div>


                        <div class="card-body">

                            <div class="mb-3">

                                <label for="component_id"
                                       class="form-label fw-semibold">

                                    Component
                                    <span class="text-danger">*</span>

                                </label>


                                <select name="component_id"
                                        id="component_id"
                                        class="form-select @error('component_id') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        -- Select Component --
                                    </option>

                                    @foreach($components as $component)

                                        <option value="{{ $component->id }}"
                                            {{ old(
                                                'component_id',
                                                $maintenanceSchedule->component_id
                                            ) == $component->id ? 'selected' : '' }}>

                                            {{ $component->name }}

                                            @if($component->componentType)
                                                — {{ $component->componentType->name }}
                                            @endif

                                            @if($component->installation)
                                                | {{ $component->installation->name }}
                                            @endif

                                        </option>

                                    @endforeach

                                </select>


                                @error('component_id')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            <div class="mb-0">

                                <label for="maintenance_task"
                                       class="form-label fw-semibold">

                                    Maintenance Task
                                    <span class="text-danger">*</span>

                                </label>


                                <input type="text"
                                       name="maintenance_task"
                                       id="maintenance_task"
                                       value="{{ old(
                                           'maintenance_task',
                                           $maintenanceSchedule->maintenance_task
                                       ) }}"
                                       class="form-control @error('maintenance_task') is-invalid @enderror"
                                       placeholder="e.g. Clean solar panel surface and inspect cable connections"
                                       required>


                                @error('maintenance_task')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                                <small class="text-muted">
                                    Describe the preventive maintenance activity.
                                </small>

                            </div>

                        </div>

                    </div>


                    {{-- Schedule Information --}}
                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white border-bottom py-3">

                            <h5 class="fw-bold mb-0">

                                <i class="bi bi-calendar3 me-2 text-success"></i>

                                Schedule Information

                            </h5>

                        </div>


                        <div class="card-body">

                            <div class="row g-3">


                                {{-- Frequency --}}
                                <div class="col-md-6">

                                    <label for="frequency"
                                           class="form-label fw-semibold">

                                        Maintenance Frequency
                                        <span class="text-danger">*</span>

                                    </label>


                                    <select name="frequency"
                                            id="frequency"
                                            class="form-select @error('frequency') is-invalid @enderror"
                                            required>

                                        <option value="">
                                            -- Select Frequency --
                                        </option>


                                        @foreach([
                                            'Weekly',
                                            'Monthly',
                                            'Quarterly',
                                            'Semi-Annually',
                                            'Annually',
                                            'As Needed'
                                        ] as $frequency)

                                            <option value="{{ $frequency }}"
                                                {{ old(
                                                    'frequency',
                                                    $maintenanceSchedule->frequency
                                                ) === $frequency ? 'selected' : '' }}>

                                                {{ $frequency }}

                                            </option>

                                        @endforeach

                                    </select>


                                    @error('frequency')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                {{-- Priority --}}
                                <div class="col-md-6">

                                    <label for="priority"
                                           class="form-label fw-semibold">

                                        Priority
                                        <span class="text-danger">*</span>

                                    </label>


                                    <select name="priority"
                                            id="priority"
                                            class="form-select @error('priority') is-invalid @enderror"
                                            required>

                                        <option value="">
                                            -- Select Priority --
                                        </option>

                                        @foreach([
                                            'Low',
                                            'Medium',
                                            'High',
                                            'Critical'
                                        ] as $priority)

                                            <option value="{{ $priority }}"
                                                {{ old(
                                                    'priority',
                                                    $maintenanceSchedule->priority
                                                ) === $priority ? 'selected' : '' }}>

                                                {{ $priority }}

                                            </option>

                                        @endforeach

                                    </select>


                                    @error('priority')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                {{-- Last Maintenance Date --}}
                                <div class="col-md-6">

                                    <label for="last_maintenance_date"
                                           class="form-label fw-semibold">

                                        Last Maintenance Date

                                    </label>


                                    <input type="date"
                                           name="last_maintenance_date"
                                           id="last_maintenance_date"
                                           value="{{ old(
                                               'last_maintenance_date',
                                               optional(
                                                   $maintenanceSchedule->last_maintenance_date
                                               )->format('Y-m-d')
                                           ) }}"
                                           class="form-control @error('last_maintenance_date') is-invalid @enderror">


                                    @error('last_maintenance_date')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                    <small class="text-muted">
                                        Date of the most recent completed maintenance.
                                    </small>

                                </div>


                                {{-- Next Due Date --}}
                                <div class="col-md-6">

                                    <label for="next_due_date"
                                           class="form-label fw-semibold">

                                        Next Due Date
                                        <span class="text-danger">*</span>

                                    </label>


                                    <input type="date"
                                           name="next_due_date"
                                           id="next_due_date"
                                           value="{{ old(
                                               'next_due_date',
                                               optional(
                                                   $maintenanceSchedule->next_due_date
                                               )->format('Y-m-d')
                                           ) }}"
                                           class="form-control @error('next_due_date') is-invalid @enderror"
                                           required>


                                    @error('next_due_date')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                    <small class="text-muted">
                                        Date when this maintenance activity should next occur.
                                    </small>

                                </div>


                                {{-- Status --}}
                                <div class="col-md-6">

                                    <label for="status"
                                           class="form-label fw-semibold">

                                        Schedule Status
                                        <span class="text-danger">*</span>

                                    </label>


                                    <select name="status"
                                            id="status"
                                            class="form-select @error('status') is-invalid @enderror"
                                            required>

                                        @foreach([
                                            'Scheduled',
                                            'Due',
                                            'Overdue',
                                            'Completed'
                                        ] as $status)

                                            <option value="{{ $status }}"
                                                {{ old(
                                                    'status',
                                                    $maintenanceSchedule->status
                                                ) === $status ? 'selected' : '' }}>

                                                {{ $status }}

                                            </option>

                                        @endforeach

                                    </select>


                                    @error('status')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Current Record Information --}}
                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white border-bottom py-3">

                            <h5 class="fw-bold mb-0">

                                <i class="bi bi-clock-history me-2 text-success"></i>

                                Record Information

                            </h5>

                        </div>


                        <div class="card-body">

                            <div class="row g-3">

                                <div class="col-md-6">

                                    <small class="text-muted d-block">
                                        Schedule ID
                                    </small>

                                    <strong>
                                        #{{ str_pad(
                                            $maintenanceSchedule->id,
                                            5,
                                            '0',
                                            STR_PAD_LEFT
                                        ) }}
                                    </strong>

                                </div>


                                <div class="col-md-6">

                                    <small class="text-muted d-block">
                                        Created
                                    </small>

                                    <strong>

                                        {{ $maintenanceSchedule->created_at
                                            ? $maintenanceSchedule->created_at->format('d M Y, h:i A')
                                            : 'N/A'
                                        }}

                                    </strong>

                                </div>


                                <div class="col-md-6">

                                    <small class="text-muted d-block">
                                        Last Updated
                                    </small>

                                    <strong>

                                        {{ $maintenanceSchedule->updated_at
                                            ? $maintenanceSchedule->updated_at->format('d M Y, h:i A')
                                            : 'N/A'
                                        }}

                                    </strong>

                                </div>


                                <div class="col-md-6">

                                    <small class="text-muted d-block">
                                        Current Component
                                    </small>

                                    <strong>

                                        {{ $maintenanceSchedule->component->name ?? 'N/A' }}

                                    </strong>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Form Buttons --}}
                    <div class="d-flex justify-content-end gap-2 mb-4">

                        <a href="{{ route(
                            'maintenance-schedules.show',
                            $maintenanceSchedule
                        ) }}"
                           class="btn btn-outline-secondary">

                            <i class="bi bi-x-circle me-1"></i>

                            Cancel

                        </a>


                        <button type="submit"
                                class="btn btn-success">

                            <i class="bi bi-check-circle me-1"></i>

                            Update Maintenance Schedule

                        </button>

                    </div>

                </form>

            </div>


            {{-- Right Sidebar --}}
            <div class="col-xl-4 col-lg-3">


                {{-- Current Status --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-success text-white py-3">

                        <h6 class="mb-0 fw-bold">

                            <i class="bi bi-calendar-check me-2"></i>

                            Current Schedule

                        </h6>

                    </div>


                    <div class="card-body">

                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Component
                            </small>

                            <strong>
                                {{ $maintenanceSchedule->component->name ?? 'N/A' }}
                            </strong>

                        </div>


                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Current Status
                            </small>


                            @switch($maintenanceSchedule->status)

                                @case('Completed')

                                    <span class="badge bg-success mt-1">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Completed
                                    </span>

                                    @break

                                @case('Overdue')

                                    <span class="badge bg-danger mt-1">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        Overdue
                                    </span>

                                    @break

                                @case('Due')

                                    <span class="badge bg-warning text-dark mt-1">
                                        <i class="bi bi-clock me-1"></i>
                                        Due
                                    </span>

                                    @break

                                @default

                                    <span class="badge bg-primary mt-1">
                                        <i class="bi bi-calendar-check me-1"></i>
                                        Scheduled
                                    </span>

                            @endswitch

                        </div>


                        <div>

                            <small class="text-muted d-block">
                                Next Due Date
                            </small>

                            <strong>

                                @if($maintenanceSchedule->next_due_date)

                                    {{ $maintenanceSchedule->next_due_date->format('d M Y') }}

                                @else

                                    Not specified

                                @endif

                            </strong>

                        </div>

                    </div>

                </div>


                {{-- Editing Guidance --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-bottom py-3">

                        <h6 class="fw-bold mb-0">

                            <i class="bi bi-lightbulb text-warning me-2"></i>

                            Editing Guidance

                        </h6>

                    </div>


                    <div class="card-body">

                        <div class="mb-3">

                            <div class="fw-semibold">

                                <i class="bi bi-calendar-event text-success me-2"></i>

                                Frequency

                            </div>

                            <small class="text-muted">

                                Update the frequency when the recommended
                                maintenance interval changes.

                            </small>

                        </div>


                        <div class="mb-3">

                            <div class="fw-semibold">

                                <i class="bi bi-flag text-warning me-2"></i>

                                Priority

                            </div>

                            <small class="text-muted">

                                Increase the priority when the component
                                condition or operational importance requires
                                faster attention.

                            </small>

                        </div>


                        <div class="mb-3">

                            <div class="fw-semibold">

                                <i class="bi bi-calendar-check text-primary me-2"></i>

                                Next Due Date

                            </div>

                            <small class="text-muted">

                                Keep the next due date aligned with the
                                preventive maintenance plan.

                            </small>

                        </div>


                        <div>

                            <div class="fw-semibold">

                                <i class="bi bi-check2-circle text-success me-2"></i>

                                Completed

                            </div>

                            <small class="text-muted">

                                Mark a schedule as completed after the
                                maintenance activity has actually been carried out.

                            </small>

                        </div>

                    </div>

                </div>


                {{-- Danger Zone --}}
                <div class="card border-danger shadow-sm">

                    <div class="card-header bg-danger text-white">

                        <h6 class="mb-0 fw-bold">

                            <i class="bi bi-exclamation-triangle me-2"></i>

                            Danger Zone

                        </h6>

                    </div>


                    <div class="card-body">

                        <p class="small text-muted">

                            Deleting this schedule permanently removes
                            the maintenance planning record.

                        </p>


                        <form action="{{ route(
                            'maintenance-schedules.destroy',
                            $maintenanceSchedule
                        ) }}"
                              method="POST"
                              onsubmit="return confirm(
                                  'Are you sure you want to permanently delete this maintenance schedule?'
                              );">

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

            </div>

        </div>

    </div>

</x-app-layout>