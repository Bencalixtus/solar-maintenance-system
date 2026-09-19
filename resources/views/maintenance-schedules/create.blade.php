<x-app-layout>

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">
                    <i class="bi bi-calendar-plus me-2 text-success"></i>
                    Add Maintenance Schedule
                </h4>

                <p class="text-muted mb-0">
                    Create a preventive maintenance schedule for a solar-battery component.
                </p>
            </div>

            <a href="{{ route('maintenance-schedules.index') }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Back to Schedule
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

            <div class="col-xl-8 col-lg-9">

                <form action="{{ route('maintenance-schedules.store') }}"
                      method="POST">

                    @csrf

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
                                            {{ old('component_id') == $component->id ? 'selected' : '' }}>

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

                                @if($components->isEmpty())
                                    <div class="alert alert-warning mt-2 mb-0">
                                        <i class="bi bi-exclamation-circle me-2"></i>

                                        No active components are currently registered.

                                        <a href="{{ route('components.create') }}"
                                           class="fw-semibold">

                                            Register a component

                                        </a>
                                        before creating a maintenance schedule.
                                    </div>
                                @endif

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
                                       value="{{ old('maintenance_task') }}"
                                       class="form-control @error('maintenance_task') is-invalid @enderror"
                                       placeholder="e.g. Clean solar panel surface and inspect cable connections"
                                       required>

                                @error('maintenance_task')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <small class="text-muted">
                                    Describe the preventive maintenance activity to be performed.
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
                                                {{ old('frequency') === $frequency ? 'selected' : '' }}>

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

                                        <option value="Low"
                                            {{ old('priority') === 'Low' ? 'selected' : '' }}>
                                            Low
                                        </option>

                                        <option value="Medium"
                                            {{ old('priority') === 'Medium' ? 'selected' : '' }}>
                                            Medium
                                        </option>

                                        <option value="High"
                                            {{ old('priority') === 'High' ? 'selected' : '' }}>
                                            High
                                        </option>

                                        <option value="Critical"
                                            {{ old('priority') === 'Critical' ? 'selected' : '' }}>
                                            Critical
                                        </option>

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
                                           value="{{ old('last_maintenance_date') }}"
                                           class="form-control @error('last_maintenance_date') is-invalid @enderror">

                                    @error('last_maintenance_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <small class="text-muted">
                                        Leave blank if this is the first scheduled maintenance.
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
                                           value="{{ old('next_due_date') }}"
                                           class="form-control @error('next_due_date') is-invalid @enderror"
                                           required>

                                    @error('next_due_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <small class="text-muted">
                                        Date when the maintenance activity should next be performed.
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

                                        <option value="Scheduled"
                                            {{ old('status', 'Scheduled') === 'Scheduled' ? 'selected' : '' }}>
                                            Scheduled
                                        </option>

                                        <option value="Due"
                                            {{ old('status') === 'Due' ? 'selected' : '' }}>
                                            Due
                                        </option>

                                        <option value="Overdue"
                                            {{ old('status') === 'Overdue' ? 'selected' : '' }}>
                                            Overdue
                                        </option>

                                        <option value="Completed"
                                            {{ old('status') === 'Completed' ? 'selected' : '' }}>
                                            Completed
                                        </option>

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


                    {{-- Information Card --}}
                    <div class="alert alert-light border shadow-sm">

                        <div class="d-flex">

                            <i class="bi bi-info-circle text-success fs-4 me-3"></i>

                            <div>

                                <h6 class="fw-bold mb-1">
                                    Preventive Maintenance
                                </h6>

                                <p class="mb-0 text-muted">
                                    Maintenance schedules help ensure that solar panels,
                                    batteries, inverters, charge controllers and other
                                    system components are inspected and serviced before
                                    their condition deteriorates significantly.
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Form Buttons --}}
                    <div class="d-flex justify-content-end gap-2 mb-4">

                        <a href="{{ route('maintenance-schedules.index') }}"
                           class="btn btn-outline-secondary">

                            <i class="bi bi-x-circle me-1"></i>
                            Cancel

                        </a>

                        <button type="submit"
                                class="btn btn-success">

                            <i class="bi bi-check-circle me-1"></i>
                            Save Maintenance Schedule

                        </button>

                    </div>

                </form>

            </div>


            {{-- Right Side Help --}}
            <div class="col-xl-4 col-lg-3">

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-success text-white">

                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-lightbulb me-2"></i>
                            Maintenance Planning
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <div class="fw-semibold">
                                <i class="bi bi-calendar-event text-success me-2"></i>
                                Frequency
                            </div>

                            <small class="text-muted">
                                Select how frequently the maintenance activity
                                should normally be performed.
                            </small>

                        </div>


                        <div class="mb-3">

                            <div class="fw-semibold">
                                <i class="bi bi-flag text-warning me-2"></i>
                                Priority
                            </div>

                            <small class="text-muted">
                                Use higher priorities for components or tasks
                                that may significantly affect system reliability.
                            </small>

                        </div>


                        <div class="mb-3">

                            <div class="fw-semibold">
                                <i class="bi bi-calendar-check text-primary me-2"></i>
                                Next Due Date
                            </div>

                            <small class="text-muted">
                                This date is used by the dashboard to identify
                                upcoming and overdue maintenance activities.
                            </small>

                        </div>


                        <div>

                            <div class="fw-semibold">
                                <i class="bi bi-shield-check text-success me-2"></i>
                                Preventive Approach
                            </div>

                            <small class="text-muted">
                                Schedule maintenance before component condition
                                reaches a critical level.
                            </small>

                        </div>

                    </div>

                </div>


                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <h6 class="fw-bold">
                            <i class="bi bi-check2-square text-success me-2"></i>
                            Recommended Tasks
                        </h6>

                        <ul class="small text-muted mb-0 ps-3">

                            <li class="mb-2">
                                Solar panel cleaning and visual inspection
                            </li>

                            <li class="mb-2">
                                Battery terminal and condition inspection
                            </li>

                            <li class="mb-2">
                                Inverter ventilation inspection
                            </li>

                            <li class="mb-2">
                                Cable and connector inspection
                            </li>

                            <li>
                                System performance checks
                            </li>

                        </ul>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>