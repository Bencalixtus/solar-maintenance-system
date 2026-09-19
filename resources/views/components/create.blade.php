<x-app-layout>
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <div>
                        <h3 class="mb-1 fw-bold">
                            <i class="bi bi-cpu text-success me-2"></i>
                            Register Component
                        </h3>

                        <p class="text-muted mb-0">
                            Register a physical component of a solar-battery installation.
                        </p>
                    </div>

                    <a href="{{ route('components.index') }}"
                       class="btn btn-outline-secondary">

                        <i class="bi bi-arrow-left me-1"></i>
                        Back to Components

                    </a>

                </div>

            </div>
        </div>


        <!-- Validation Errors -->
        @if ($errors->any())

            <div class="alert alert-danger alert-dismissible fade show"
                 role="alert">

                <div class="d-flex align-items-start">

                    <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>

                    <div>

                        <strong>
                            Please correct the following errors:
                        </strong>

                        <ul class="mb-0 mt-2">

                            @foreach ($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                </div>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        <!-- No Installation Warning -->
        @if($installations->isEmpty())

            <div class="alert alert-warning">

                <div class="d-flex align-items-center">

                    <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>

                    <div>

                        <strong>No solar installation is available.</strong>

                        <div class="mt-1">
                            Please register a solar installation before
                            registering components.
                        </div>

                    </div>

                </div>

                <a href="{{ route('installations.create') }}"
                   class="btn btn-warning mt-3">

                    <i class="bi bi-plus-circle me-1"></i>
                    Add Installation

                </a>

            </div>

        @endif


        <!-- Registration Form -->
        <div class="card shadow-sm border-0">

            <div class="card-header bg-success text-white py-3">

                <h5 class="mb-0">
                    <i class="bi bi-cpu me-2"></i>
                    Component Information
                </h5>

            </div>


            <form action="{{ route('components.store') }}"
                  method="POST">

                @csrf

                <div class="card-body p-4">

                    <div class="row g-4">

                        <!-- Installation -->
                        <div class="col-md-6">

                            <label for="installation_id"
                                   class="form-label fw-semibold">

                                Solar Installation
                                <span class="text-danger">*</span>

                            </label>

                            <select name="installation_id"
                                    id="installation_id"
                                    class="form-select @error('installation_id') is-invalid @enderror"
                                    required>

                                <option value="">
                                    -- Select Installation --
                                </option>

                                @foreach($installations as $installation)

                                    <option value="{{ $installation->id }}"
                                        {{ old('installation_id') == $installation->id ? 'selected' : '' }}>

                                        {{ $installation->name }}

                                    </option>

                                @endforeach

                            </select>

                            @error('installation_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <small class="text-muted">
                                Select the solar installation where this
                                component is located.
                            </small>

                        </div>


                        <!-- Component Type -->
                        <div class="col-md-6">

                            <label for="component_type_id"
                                   class="form-label fw-semibold">

                                Component Type
                                <span class="text-danger">*</span>

                            </label>

                            <select name="component_type_id"
                                    id="component_type_id"
                                    class="form-select @error('component_type_id') is-invalid @enderror"
                                    required>

                                <option value="">
                                    -- Select Component Type --
                                </option>

                                @foreach($componentTypes as $type)

                                    <option value="{{ $type->id }}"
                                        {{ old('component_type_id') == $type->id ? 'selected' : '' }}>

                                        {{ $type->name }}

                                    </option>

                                @endforeach

                            </select>

                            @error('component_type_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <small class="text-muted">
                                Select the category of this component.
                            </small>

                        </div>


                        <!-- Component Name -->
                        <div class="col-md-6">

                            <label for="name"
                                   class="form-label fw-semibold">

                                Component Name
                                <span class="text-danger">*</span>

                            </label>

                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name') }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="e.g. Solar Panel 01"
                                   required>

                            @error('name')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- Manufacturer -->
                        <div class="col-md-6">

                            <label for="manufacturer"
                                   class="form-label fw-semibold">

                                Manufacturer

                            </label>

                            <input type="text"
                                   name="manufacturer"
                                   id="manufacturer"
                                   value="{{ old('manufacturer') }}"
                                   class="form-control @error('manufacturer') is-invalid @enderror"
                                   placeholder="e.g. Jinko Solar">

                            @error('manufacturer')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- Model -->
                        <div class="col-md-6">

                            <label for="model"
                                   class="form-label fw-semibold">

                                Model

                            </label>

                            <input type="text"
                                   name="model"
                                   id="model"
                                   value="{{ old('model') }}"
                                   class="form-control @error('model') is-invalid @enderror"
                                   placeholder="e.g. JKM550M-72HL4">

                            @error('model')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- Serial Number -->
                        <div class="col-md-6">

                            <label for="serial_number"
                                   class="form-label fw-semibold">

                                Serial Number

                            </label>

                            <input type="text"
                                   name="serial_number"
                                   id="serial_number"
                                   value="{{ old('serial_number') }}"
                                   class="form-control @error('serial_number') is-invalid @enderror"
                                   placeholder="Enter serial number">

                            @error('serial_number')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- Installation Date -->
                        <div class="col-md-6">

                            <label for="installation_date"
                                   class="form-label fw-semibold">

                                Component Installation Date
                                <span class="text-danger">*</span>

                            </label>

                            <input type="date"
                                   name="installation_date"
                                   id="installation_date"
                                   value="{{ old('installation_date') }}"
                                   class="form-control @error('installation_date') is-invalid @enderror"
                                   required>

                            @error('installation_date')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- Rated Capacity -->
                        <div class="col-md-6">

                            <label for="rated_capacity"
                                   class="form-label fw-semibold">

                                Rated Capacity

                            </label>

                            <div class="input-group">

                                <input type="number"
                                       name="rated_capacity"
                                       id="rated_capacity"
                                       value="{{ old('rated_capacity') }}"
                                       class="form-control @error('rated_capacity') is-invalid @enderror"
                                       placeholder="e.g. 550"
                                       min="0"
                                       step="0.01">

                                <span class="input-group-text">
                                    W / Ah / kW
                                </span>

                            </div>

                            @error('rated_capacity')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                            <small class="text-muted">
                                Enter the rated capacity according to the
                                component specification.
                            </small>

                        </div>


                        <!-- Rated Voltage -->
                        <div class="col-md-6">

                            <label for="rated_voltage"
                                   class="form-label fw-semibold">

                                Rated Voltage

                            </label>

                            <div class="input-group">

                                <input type="number"
                                       name="rated_voltage"
                                       id="rated_voltage"
                                       value="{{ old('rated_voltage') }}"
                                       class="form-control @error('rated_voltage') is-invalid @enderror"
                                       placeholder="e.g. 48"
                                       min="0"
                                       step="0.01">

                                <span class="input-group-text">
                                    V
                                </span>

                            </div>

                            @error('rated_voltage')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- Expected Lifespan -->
                        <div class="col-md-6">

                            <label for="expected_lifespan"
                                   class="form-label fw-semibold">

                                Expected Lifespan

                            </label>

                            <div class="input-group">

                                <input type="number"
                                       name="expected_lifespan"
                                       id="expected_lifespan"
                                       value="{{ old('expected_lifespan') }}"
                                       class="form-control @error('expected_lifespan') is-invalid @enderror"
                                       placeholder="e.g. 10"
                                       min="1"
                                       max="100">

                                <span class="input-group-text">
                                    Years
                                </span>

                            </div>

                            @error('expected_lifespan')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- Current Condition -->
                        <div class="col-md-6">

                            <label for="current_condition"
                                   class="form-label fw-semibold">

                                Current Condition
                                <span class="text-danger">*</span>

                            </label>

                            <select name="current_condition"
                                    id="current_condition"
                                    class="form-select @error('current_condition') is-invalid @enderror"
                                    required>

                                <option value="">
                                    -- Select Condition --
                                </option>

                                <option value="Excellent"
                                    {{ old('current_condition') === 'Excellent' ? 'selected' : '' }}>
                                    Excellent
                                </option>

                                <option value="Good"
                                    {{ old('current_condition') === 'Good' ? 'selected' : '' }}>
                                    Good
                                </option>

                                <option value="Fair"
                                    {{ old('current_condition') === 'Fair' ? 'selected' : '' }}>
                                    Fair
                                </option>

                                <option value="Poor"
                                    {{ old('current_condition') === 'Poor' ? 'selected' : '' }}>
                                    Poor
                                </option>

                                <option value="Critical"
                                    {{ old('current_condition') === 'Critical' ? 'selected' : '' }}>
                                    Critical
                                </option>

                            </select>

                            @error('current_condition')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- Status -->
                        <div class="col-md-6">

                            <label for="status"
                                   class="form-label fw-semibold">

                                Component Status
                                <span class="text-danger">*</span>

                            </label>

                            <select name="status"
                                    id="status"
                                    class="form-select @error('status') is-invalid @enderror"
                                    required>

                                <option value="">
                                    -- Select Status --
                                </option>

                                <option value="Active"
                                    {{ old('status') === 'Active' ? 'selected' : '' }}>
                                    Active
                                </option>

                                <option value="Under Maintenance"
                                    {{ old('status') === 'Under Maintenance' ? 'selected' : '' }}>
                                    Under Maintenance
                                </option>

                                <option value="Inactive"
                                    {{ old('status') === 'Inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>

                                <option value="Replaced"
                                    {{ old('status') === 'Replaced' ? 'selected' : '' }}>
                                    Replaced
                                </option>

                            </select>

                            @error('status')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- Description -->
                        <div class="col-12">

                            <label for="description"
                                   class="form-label fw-semibold">

                                Description

                            </label>

                            <textarea name="description"
                                      id="description"
                                      rows="5"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Enter additional information about this component...">{{ old('description') }}</textarea>

                            @error('description')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>


                <!-- Footer -->
                <div class="card-footer bg-light p-3">

                    <div class="d-flex justify-content-end gap-2">

                        <a href="{{ route('components.index') }}"
                           class="btn btn-outline-secondary">

                            <i class="bi bi-x-circle me-1"></i>
                            Cancel

                        </a>

                        <button type="submit"
                                class="btn btn-success">

                            <i class="bi bi-check-circle me-1"></i>
                            Register Component

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>
</x-app-layout>