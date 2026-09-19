<x-app-layout>

    <x-slot name="header">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h4 class="fw-bold mb-1">

                    <i class="bi bi-clipboard-plus me-2 text-success"></i>

                    Record Maintenance Activity

                </h4>

                <p class="text-muted mb-0">
                    Record maintenance work performed on a solar-battery component.
                </p>

            </div>

            <a href="{{ route('maintenance-records.index') }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>

                Back

            </a>

        </div>

    </x-slot>


    <div class="container-fluid py-3">

        @if($errors->any())

            <div class="alert alert-danger shadow-sm">

                <strong>
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Please correct the following:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif


        <div class="row">

            <div class="col-xl-8 col-lg-9">

                <form action="{{ route('maintenance-records.store') }}"
                      method="POST">

                    @csrf


                    {{-- Component & Technician --}}
                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white py-3">

                            <h5 class="fw-bold mb-0">

                                <i class="bi bi-cpu me-2 text-success"></i>

                                Maintenance Assignment

                            </h5>

                        </div>


                        <div class="card-body">

                            <div class="row g-3">


                                <div class="col-md-6">

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

                                </div>


                                <div class="col-md-6">

                                    <label for="technician_id"
                                           class="form-label fw-semibold">

                                        Technician
                                        <span class="text-danger">*</span>

                                    </label>


                                    <select name="technician_id"
                                            id="technician_id"
                                            class="form-select @error('technician_id') is-invalid @enderror"
                                            required>

                                        <option value="">
                                            -- Select Technician --
                                        </option>

                                        @foreach($technicians as $technician)

                                            <option value="{{ $technician->id }}"
                                                {{ old('technician_id') == $technician->id ? 'selected' : '' }}>

                                                {{ $technician->name }}

                                            </option>

                                        @endforeach

                                    </select>


                                    @error('technician_id')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Maintenance Details --}}
                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white py-3">

                            <h5 class="fw-bold mb-0">

                                <i class="bi bi-tools me-2 text-success"></i>

                                Maintenance Details

                            </h5>

                        </div>


                        <div class="card-body">

                            <div class="row g-3">


                                <div class="col-md-6">

                                    <label for="maintenance_type"
                                           class="form-label fw-semibold">

                                        Maintenance Type
                                        <span class="text-danger">*</span>

                                    </label>


                                    <select name="maintenance_type"
                                            id="maintenance_type"
                                            class="form-select @error('maintenance_type') is-invalid @enderror"
                                            required>

                                        <option value="">
                                            -- Select Type --
                                        </option>

                                        @foreach([
                                            'Preventive',
                                            'Corrective',
                                            'Inspection',
                                            'Emergency',
                                            'Replacement'
                                        ] as $type)

                                            <option value="{{ $type }}"
                                                {{ old('maintenance_type') === $type ? 'selected' : '' }}>

                                                {{ $type }}

                                            </option>

                                        @endforeach

                                    </select>


                                    @error('maintenance_type')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                <div class="col-md-6">

                                    <label for="maintenance_date"
                                           class="form-label fw-semibold">

                                        Maintenance Date
                                        <span class="text-danger">*</span>

                                    </label>


                                    <input type="date"
                                           name="maintenance_date"
                                           id="maintenance_date"
                                           value="{{ old(
                                               'maintenance_date',
                                               now()->format('Y-m-d')
                                           ) }}"
                                           class="form-control @error('maintenance_date') is-invalid @enderror"
                                           required>


                                    @error('maintenance_date')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                <div class="col-12">

                                    <label for="description"
                                           class="form-label fw-semibold">

                                        Maintenance Description
                                        <span class="text-danger">*</span>

                                    </label>


                                    <textarea name="description"
                                              id="description"
                                              rows="4"
                                              class="form-control @error('description') is-invalid @enderror"
                                              placeholder="Describe the maintenance activity or reason for maintenance..."
                                              required>{{ old('description') }}</textarea>


                                    @error('description')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Condition Assessment --}}
                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white py-3">

                            <h5 class="fw-bold mb-0">

                                <i class="bi bi-activity me-2 text-success"></i>

                                Component Condition Assessment

                            </h5>

                        </div>


                        <div class="card-body">

                            <div class="row g-3">


                                <div class="col-md-6">

                                    <label for="condition_before"
                                           class="form-label fw-semibold">

                                        Condition Before Maintenance
                                        <span class="text-danger">*</span>

                                    </label>


                                    <select name="condition_before"
                                            id="condition_before"
                                            class="form-select @error('condition_before') is-invalid @enderror"
                                            required>

                                        <option value="">
                                            -- Select Condition --
                                        </option>

                                        @foreach([
                                            'Excellent',
                                            'Good',
                                            'Fair',
                                            'Poor',
                                            'Critical'
                                        ] as $condition)

                                            <option value="{{ $condition }}"
                                                {{ old('condition_before') === $condition ? 'selected' : '' }}>

                                                {{ $condition }}

                                            </option>

                                        @endforeach

                                    </select>


                                    @error('condition_before')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                <div class="col-md-6">

                                    <label for="condition_after"
                                           class="form-label fw-semibold">

                                        Condition After Maintenance
                                        <span class="text-danger">*</span>

                                    </label>


                                    <select name="condition_after"
                                            id="condition_after"
                                            class="form-select @error('condition_after') is-invalid @enderror"
                                            required>

                                        <option value="">
                                            -- Select Condition --
                                        </option>

                                        @foreach([
                                            'Excellent',
                                            'Good',
                                            'Fair',
                                            'Poor',
                                            'Critical'
                                        ] as $condition)

                                            <option value="{{ $condition }}"
                                                {{ old('condition_after') === $condition ? 'selected' : '' }}>

                                                {{ $condition }}

                                            </option>

                                        @endforeach

                                    </select>


                                    @error('condition_after')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                <div class="col-12">

                                    <label for="action_taken"
                                           class="form-label fw-semibold">

                                        Action Taken
                                        <span class="text-danger">*</span>

                                    </label>


                                    <textarea name="action_taken"
                                              id="action_taken"
                                              rows="4"
                                              class="form-control @error('action_taken') is-invalid @enderror"
                                              placeholder="Describe the repairs, cleaning, adjustment, replacement or other action performed..."
                                              required>{{ old('action_taken') }}</textarea>


                                    @error('action_taken')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Follow-up --}}
                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white py-3">

                            <h5 class="fw-bold mb-0">

                                <i class="bi bi-calendar-check me-2 text-success"></i>

                                Follow-up & Status

                            </h5>

                        </div>


                        <div class="card-body">

                            <div class="row g-3">


                                <div class="col-md-6">

                                    <label for="next_due_date"
                                           class="form-label fw-semibold">

                                        Next Maintenance Due Date

                                    </label>


                                    <input type="date"
                                           name="next_due_date"
                                           id="next_due_date"
                                           value="{{ old('next_due_date') }}"
                                           class="form-control @error('next_due_date') is-invalid @enderror">


                                    @error('next_due_date')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                <div class="col-md-6">

                                    <label for="status"
                                           class="form-label fw-semibold">

                                        Status
                                        <span class="text-danger">*</span>

                                    </label>


                                    <select name="status"
                                            id="status"
                                            class="form-select @error('status') is-invalid @enderror"
                                            required>

                                        @foreach([
                                            'Pending',
                                            'In Progress',
                                            'Completed',
                                            'Cancelled'
                                        ] as $status)

                                            <option value="{{ $status }}"
                                                {{ old('status', 'Completed') === $status ? 'selected' : '' }}>

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


                                <div class="col-12">

                                    <label for="remarks"
                                           class="form-label fw-semibold">

                                        Additional Remarks

                                    </label>


                                    <textarea name="remarks"
                                              id="remarks"
                                              rows="3"
                                              class="form-control @error('remarks') is-invalid @enderror"
                                              placeholder="Additional observations, recommendations or notes...">{{ old('remarks') }}</textarea>


                                    @error('remarks')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="d-flex justify-content-end gap-2 mb-4">

                        <a href="{{ route('maintenance-records.index') }}"
                           class="btn btn-outline-secondary">

                            <i class="bi bi-x-circle me-1"></i>

                            Cancel

                        </a>


                        <button type="submit"
                                class="btn btn-success">

                            <i class="bi bi-check-circle me-1"></i>

                            Save Maintenance Record

                        </button>

                    </div>

                </form>

            </div>


            {{-- Information Sidebar --}}
            <div class="col-xl-4 col-lg-3">

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-success text-white">

                        <h6 class="fw-bold mb-0">

                            <i class="bi bi-info-circle me-2"></i>

                            Maintenance Record

                        </h6>

                    </div>


                    <div class="card-body">

                        <p class="text-muted small">

                            This record documents maintenance actually
                            performed on a component.

                        </p>


                        <hr>


                        <div class="mb-3">

                            <strong>
                                <i class="bi bi-1-circle text-success me-2"></i>
                                Record the condition
                            </strong>

                            <small class="text-muted d-block mt-1">
                                Assess the component before and after the
                                maintenance activity.
                            </small>

                        </div>


                        <div class="mb-3">

                            <strong>
                                <i class="bi bi-2-circle text-success me-2"></i>
                                Describe the action
                            </strong>

                            <small class="text-muted d-block mt-1">
                                Clearly document what was done to the component.
                            </small>

                        </div>


                        <div>

                            <strong>
                                <i class="bi bi-3-circle text-success me-2"></i>
                                Set the next date
                            </strong>

                            <small class="text-muted d-block mt-1">
                                Enter the next recommended maintenance date
                                where applicable.
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>