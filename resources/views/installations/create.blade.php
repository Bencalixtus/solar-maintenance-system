<x-app-layout>
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <div>
                        <h3 class="mb-1 fw-bold">
                            <i class="bi bi-plus-circle text-success me-2"></i>
                            Add Solar Installation
                        </h3>

                        <p class="text-muted mb-0">
                            Register a new solar-battery installation in the system.
                        </p>
                    </div>

                    <div>
                        <a href="{{ route('installations.index') }}"
                           class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            Back to Installations
                        </a>
                    </div>

                </div>

            </div>
        </div>


        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">

                <div class="d-flex align-items-start">

                    <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>

                    <div>
                        <strong>Please correct the following errors:</strong>

                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
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


        <!-- Installation Form -->
        <div class="card shadow-sm border-0">

            <div class="card-header bg-success text-white py-3">

                <h5 class="mb-0">
                    <i class="bi bi-building me-2"></i>
                    Installation Information
                </h5>

            </div>


            <form action="{{ route('installations.store') }}"
                  method="POST">

                @csrf

                <div class="card-body">

                    <div class="row g-4">

                        <!-- Installation Name -->
                        <div class="col-md-6">

                            <label for="name" class="form-label fw-semibold">
                                Installation Name
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="e.g. Department Solar-Battery System"
                                   required>

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <small class="text-muted">
                                Enter a unique name that identifies the installation.
                            </small>

                        </div>


                        <!-- Location -->
                        <div class="col-md-6">

                            <label for="location" class="form-label fw-semibold">
                                Location
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="location"
                                   id="location"
                                   class="form-control @error('location') is-invalid @enderror"
                                   value="{{ old('location') }}"
                                   placeholder="e.g. Computer Science Department"
                                   required>

                            @error('location')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <small class="text-muted">
                                Specify where the solar installation is located.
                            </small>

                        </div>


                        <!-- Installation Date -->
                        <div class="col-md-6">

                            <label for="installation_date"
                                   class="form-label fw-semibold">

                                Installation Date
                                <span class="text-danger">*</span>

                            </label>

                            <input type="date"
                                   name="installation_date"
                                   id="installation_date"
                                   class="form-control @error('installation_date') is-invalid @enderror"
                                   value="{{ old('installation_date') }}"
                                   required>

                            @error('installation_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <small class="text-muted">
                                Date when the solar-battery system was installed.
                            </small>

                        </div>


                        <!-- System Capacity -->
                        <div class="col-md-6">

                            <label for="system_capacity"
                                   class="form-label fw-semibold">

                                System Capacity
                                <span class="text-muted fw-normal">(kW)</span>

                            </label>

                            <div class="input-group">

                                <input type="number"
                                       name="system_capacity"
                                       id="system_capacity"
                                       class="form-control @error('system_capacity') is-invalid @enderror"
                                       value="{{ old('system_capacity') }}"
                                       placeholder="e.g. 10"
                                       min="0"
                                       step="0.01">

                                <span class="input-group-text">
                                    kW
                                </span>

                            </div>

                            @error('system_capacity')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                            <small class="text-muted">
                                Total rated capacity of the solar installation.
                            </small>

                        </div>


                        <!-- Status -->
                        <div class="col-md-6">

                            <label for="status"
                                   class="form-label fw-semibold">

                                Installation Status
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

                            </select>

                            @error('status')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <small class="text-muted">
                                Current operational status of the installation.
                            </small>

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
                                      placeholder="Enter additional information about the solar-battery installation...">{{ old('description') }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <small class="text-muted">
                                Optional information such as the purpose of the system,
                                installation details, or other relevant notes.
                            </small>

                        </div>

                    </div>

                </div>


                <!-- Form Footer -->
                <div class="card-footer bg-light d-flex justify-content-end gap-2 py-3">

                    <a href="{{ route('installations.index') }}"
                       class="btn btn-outline-secondary">

                        <i class="bi bi-x-circle me-1"></i>
                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-success">

                        <i class="bi bi-check-circle me-1"></i>
                        Save Installation

                    </button>

                </div>

            </form>

        </div>

    </div>
</x-app-layout>