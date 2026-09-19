<x-app-layout>
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <div>
                        <h3 class="mb-1 fw-bold">
                            <i class="bi bi-building text-success me-2"></i>
                            Installation Details
                        </h3>

                        <p class="text-muted mb-0">
                            View complete information about this solar-battery installation.
                        </p>
                    </div>

                    <div class="d-flex gap-2">

                        <a href="{{ route('installations.index') }}"
                           class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            Back
                        </a>

                        <a href="{{ route('installations.edit', $installation) }}"
                           class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i>
                            Edit Installation
                        </a>

                    </div>

                </div>

            </div>
        </div>


        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">

                <i class="bi bi-check-circle-fill me-2"></i>

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>
        @endif


        <!-- Installation Overview -->
        <div class="row g-4 mb-4">

            <!-- Installation Name -->
            <div class="col-lg-8">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header bg-success text-white py-3">

                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            Installation Information
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row g-4">

                            <!-- Name -->
                            <div class="col-md-6">

                                <small class="text-muted d-block mb-1">
                                    Installation Name
                                </small>

                                <h5 class="fw-bold mb-0">
                                    {{ $installation->name }}
                                </h5>

                            </div>


                            <!-- Location -->
                            <div class="col-md-6">

                                <small class="text-muted d-block mb-1">
                                    Location
                                </small>

                                <h6 class="fw-semibold mb-0">
                                    <i class="bi bi-geo-alt text-success me-1"></i>
                                    {{ $installation->location }}
                                </h6>

                            </div>


                            <!-- Installation Date -->
                            <div class="col-md-6">

                                <small class="text-muted d-block mb-1">
                                    Installation Date
                                </small>

                                <h6 class="fw-semibold mb-0">

                                    <i class="bi bi-calendar-event text-success me-1"></i>

                                    {{ \Carbon\Carbon::parse($installation->installation_date)->format('d M Y') }}

                                </h6>

                            </div>


                            <!-- System Capacity -->
                            <div class="col-md-6">

                                <small class="text-muted d-block mb-1">
                                    System Capacity
                                </small>

                                <h6 class="fw-semibold mb-0">

                                    @if($installation->system_capacity)
                                        {{ number_format($installation->system_capacity, 2) }} kW
                                    @else
                                        <span class="text-muted">
                                            Not specified
                                        </span>
                                    @endif

                                </h6>

                            </div>


                            <!-- Status -->
                            <div class="col-md-6">

                                <small class="text-muted d-block mb-1">
                                    Current Status
                                </small>

                                @if($installation->status === 'Active')

                                    <span class="badge bg-success fs-6">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Active
                                    </span>

                                @elseif($installation->status === 'Under Maintenance')

                                    <span class="badge bg-warning text-dark fs-6">
                                        <i class="bi bi-tools me-1"></i>
                                        Under Maintenance
                                    </span>

                                @else

                                    <span class="badge bg-secondary fs-6">
                                        <i class="bi bi-pause-circle me-1"></i>
                                        Inactive
                                    </span>

                                @endif

                            </div>


                            <!-- Components Count -->
                            <div class="col-md-6">

                                <small class="text-muted d-block mb-1">
                                    Registered Components
                                </small>

                                <h6 class="fw-bold mb-0">

                                    <i class="bi bi-cpu text-success me-1"></i>

                                    {{ $installation->components->count() }}

                                    <span class="text-muted fw-normal">
                                        component(s)
                                    </span>

                                </h6>

                            </div>


                            <!-- Description -->
                            <div class="col-12">

                                <small class="text-muted d-block mb-1">
                                    Description
                                </small>

                                @if($installation->description)

                                    <div class="bg-light rounded p-3">
                                        {{ $installation->description }}
                                    </div>

                                @else

                                    <div class="text-muted fst-italic">
                                        No description provided.
                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Quick Statistics -->
            <div class="col-lg-4">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header bg-warning py-3">

                        <h5 class="mb-0">
                            <i class="bi bi-bar-chart me-2"></i>
                            Quick Statistics
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div>
                                <small class="text-muted d-block">
                                    Components
                                </small>

                                <h4 class="fw-bold mb-0">
                                    {{ $installation->components->count() }}
                                </h4>
                            </div>

                            <div class="text-success fs-2">
                                <i class="bi bi-cpu"></i>
                            </div>

                        </div>


                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div>
                                <small class="text-muted d-block">
                                    Installation Age
                                </small>

                                <h4 class="fw-bold mb-0">

                                    {{ \Carbon\Carbon::parse($installation->installation_date)->diffInYears(now()) }}

                                    <small class="fs-6 text-muted">
                                        year(s)
                                    </small>

                                </h4>
                            </div>

                            <div class="text-warning fs-2">
                                <i class="bi bi-calendar3"></i>
                            </div>

                        </div>


                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                <small class="text-muted d-block">
                                    System Capacity
                                </small>

                                <h4 class="fw-bold mb-0">

                                    {{ $installation->system_capacity
                                        ? number_format($installation->system_capacity, 2)
                                        : '0' }}

                                    <small class="fs-6 text-muted">
                                        kW
                                    </small>

                                </h4>
                            </div>

                            <div class="text-success fs-2">
                                <i class="bi bi-lightning-charge"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Components -->
        <div class="card shadow-sm border-0">

            <div class="card-header py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0 fw-bold">

                        <i class="bi bi-cpu text-success me-2"></i>

                        Installation Components

                    </h5>

                    <span class="badge bg-success">

                        {{ $installation->components->count() }}

                        {{ $installation->components->count() === 1 ? 'Component' : 'Components' }}

                    </span>

                </div>

            </div>


            <div class="card-body p-0">

                @if($installation->components->count() > 0)

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th class="px-4">
                                        Component
                                    </th>

                                    <th>
                                        Type
                                    </th>

                                    <th>
                                        Manufacturer
                                    </th>

                                    <th>
                                        Condition
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach($installation->components as $component)

                                    <tr>

                                        <td class="px-4">

                                            <div class="fw-semibold">
                                                {{ $component->name }}
                                            </div>

                                            @if($component->model)
                                                <small class="text-muted">
                                                    Model: {{ $component->model }}
                                                </small>
                                            @endif

                                        </td>


                                        <td>

                                            {{ $component->componentType->name ?? 'Not specified' }}

                                        </td>


                                        <td>

                                            {{ $component->manufacturer ?? 'Not specified' }}

                                        </td>


                                        <td>

                                            @php
                                                $conditionClass = match($component->current_condition) {
                                                    'Excellent' => 'success',
                                                    'Good' => 'success',
                                                    'Fair' => 'warning',
                                                    'Poor' => 'danger',
                                                    'Critical' => 'danger',
                                                    default => 'secondary',
                                                };
                                            @endphp

                                            <span class="badge bg-{{ $conditionClass }}">

                                                {{ $component->current_condition ?? 'Not specified' }}

                                            </span>

                                        </td>


                                        <td>

                                            @if($component->status === 'Active')

                                                <span class="badge bg-success">
                                                    Active
                                                </span>

                                            @elseif($component->status === 'Under Maintenance')

                                                <span class="badge bg-warning text-dark">
                                                    Under Maintenance
                                                </span>

                                            @else

                                                <span class="badge bg-secondary">
                                                    {{ $component->status ?? 'Inactive' }}
                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="text-center py-5">

                        <div class="mb-3">

                            <i class="bi bi-cpu"
                               style="font-size: 3.5rem; color: #198754;">
                            </i>

                        </div>

                        <h5 class="fw-semibold">
                            No Components Registered
                        </h5>

                        <p class="text-muted mb-3">
                            Components such as solar panels, batteries and inverters
                            will appear here after they are registered.
                        </p>

                        <span class="badge bg-light text-dark border">
                            Component Management Coming Next
                        </span>

                    </div>

                @endif

            </div>

        </div>


        <!-- Danger Zone -->
        <div class="card shadow-sm border-0 mt-4">

            <div class="card-header bg-light py-3">

                <h5 class="mb-0 text-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Installation Actions
                </h5>

            </div>

            <div class="card-body">

                <p class="text-muted">
                    Use the edit option to update this installation.
                    Deleting an installation should only be done when it is no longer
                    required in the system.
                </p>

                <form action="{{ route('installations.destroy', $installation) }}"
                      method="POST"
                      onsubmit="return confirm('Are you sure you want to delete this installation? This action cannot be undone.');">

                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="btn btn-outline-danger">

                        <i class="bi bi-trash me-1"></i>
                        Delete Installation

                    </button>

                </form>

            </div>

        </div>

    </div>
</x-app-layout>