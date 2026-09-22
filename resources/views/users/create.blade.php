@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <a href="{{ route('users.index') }}"
           class="text-decoration-none text-muted">
            <i class="bi bi-arrow-left me-1"></i>
            Back to User Management
        </a>

        <h1 class="h3 mt-2 mb-1">
            <i class="bi bi-person-plus me-2 text-success"></i>
            Add New User
        </h1>

        <p class="text-muted mb-0">
            Create a system account and assign an appropriate role.
        </p>
    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                User Information
            </h5>
        </div>

        <div class="card-body">

            <form method="POST" action="{{ route('users.store') }}">

                @csrf

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Full Name <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               required>

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Email Address <span class="text-danger">*</span>
                        </label>

                        <input type="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               required>

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Phone Number
                        </label>

                        <input type="text"
                               name="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone') }}">

                        @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-md-3">

                        <label class="form-label">
                            Role <span class="text-danger">*</span>
                        </label>

                        <select name="role"
                                class="form-select @error('role') is-invalid @enderror"
                                required>

                            <option value="">Select Role</option>

                            <option value="Admin"
                                @selected(old('role') === 'Admin')>
                                Admin
                            </option>

                            <option value="Technician"
                                @selected(old('role') === 'Technician')>
                                Technician
                            </option>

                            <option value="Supervisor"
                                @selected(old('role') === 'Supervisor')>
                                Supervisor
                            </option>

                        </select>

                        @error('role')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-md-3">

                        <label class="form-label">
                            Status <span class="text-danger">*</span>
                        </label>

                        <select name="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>

                            <option value="Active"
                                @selected(old('status', 'Active') === 'Active')>
                                Active
                            </option>

                            <option value="Inactive"
                                @selected(old('status') === 'Inactive')>
                                Inactive
                            </option>

                        </select>

                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Password <span class="text-danger">*</span>
                        </label>

                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               required>

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Confirm Password <span class="text-danger">*</span>
                        </label>

                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               required>

                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('users.index') }}"
                       class="btn btn-light border">
                        Cancel
                    </a>

                    <button type="submit"
                            class="btn btn-success">
                        <i class="bi bi-person-plus me-1"></i>
                        Create User
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection