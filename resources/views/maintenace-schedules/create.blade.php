<x-app-layout>

    <x-slot name="header">
        <div>
            <h4 class="mb-1 fw-bold">
                <i class="bi bi-calendar-plus me-2 text-success"></i>
                Create Maintenance Schedule
            </h4>

            <p class="text-muted mb-0">
                Schedule preventive maintenance for a solar-battery component.
            </p>
        </div>
    </x-slot>

    <div class="container-fluid">

        <div class="row justify-content-center">

            <div class="col-lg-9">

                <form method="POST"
                      action="{{ route('maintenance-schedules.store') }}">

                    @csrf

                    <div class="card border-0 shadow-sm">

                        <div class="card-header bg-white">
                            <h5 class="mb-0 fw-bold">
                                Maintenance Details
                            </h5>
                        </div>

                        <div class="card-body">

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Please correct the following:</strong>

                                    <ul class="mb-0 mt-2">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row g-3">

                                {{-- Component --}}
                                <div class="col-md-12">

                                    <label class="form-label fw-semibold">
                                        Component <span class="text-danger">*</span>
                                    </label>

                                    <select name="component_id"
                                            class="form-select"
                                            required>

                                        <option value="">
                                            Select component
                                        </option>

                                        @foreach($components as $component)

                                            <option value="{{ $component->id }}"
                                                {{ old('component_id') == $component->id ? 'selected' : '' }}>

                                                {{ $component->name }}

                                                -
                                                {{ $component->componentType->name ?? 'Component' }}

                                                @if($component->installation)
                                                    ({{ $component->installation->name }})
                                                @endif

                                            </option>

                                        @endforeach

                                    </select>

                                </div>


                                {{-- Maintenance Task --}}
                                <div class="col-md-12">

                                    <label class="form-label fw-semibold">
                                        Maintenance Task
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                           name="maintenance_task"
                                           value="{{ old('maintenance_task') }}"
                                           class="form-control"
                                           placeholder="e.g. Clean solar panel surfaces"
                                           required>

                                </div>


                                {{-- Frequency --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">
                                        Frequency
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select name="frequency"
                                            class="form-select"
                                            required>

                                        <option value="">
                                            Select frequency
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

                                </div>


                                {{-- Priority --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">
                                        Priority
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select name="priority"
                                            class="form-select"
                                            required>

                                        <option value="">
                                            Select priority
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

                                </div>


                                {{-- Last Maintenance --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">
                                        Last Maintenance Date
                                    </label>

                                    <input type="date"
                                           name="last_maintenance_date"
                                           value="{{ old('last_maintenance_date') }}"
                                           class="form-control">

                                </div>


                                {{-- Next Due --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">
                                        Next Due Date
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="date"
                                           name="next_due_date"
                                           value="{{ old('next_due_date') }}"
                                           class="form-control"
                                           required>

                                </div>


                                {{-- Status --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">
                                        Status
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select name="status"
                                            class="form-select"
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

                                </div>

                            </div>

                        </div>

                        <div class="card-footer bg-white d-flex justify-content-between">

                            <a href="{{ route('maintenance-schedules.index') }}"
                               class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>
                                Cancel
                            </a>

                            <button type="submit"
                                    class="btn btn-success">
                                <i class="bi bi-check-circle me-1"></i>
                                Save Schedule
                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>