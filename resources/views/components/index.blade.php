<x-app-layout>
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <div>
                        <h3 class="mb-1 fw-bold">
                            <i class="bi bi-cpu text-success me-2"></i>
                            Solar Components
                        </h3>

                        <p class="text-muted mb-0">
                            Manage and monitor components installed in solar-battery systems.
                        </p>
                    </div>

                    <a href="{{ route('components.create') }}"
                       class="btn btn-success">

                        <i class="bi bi-plus-circle me-1"></i>
                        Register Component

                    </a>

                </div>

            </div>
        </div>


        <!-- Success Message -->
        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show"
                 role="alert">

                <i class="bi bi-check-circle-fill me-2"></i>

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        <!-- Error Message -->
        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show"
                 role="alert">

                <i class="bi bi-exclamation-triangle-fill me-2"></i>

                {{ session('error') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        <!-- Statistics -->
        <div class="row g-3 mb-4">

            <!-- Total Components -->
            <div class="col-md-3">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                Total Components
                            </small>

                            <h3 class="fw-bold mb-0">
                                {{ $components->total() }}
                            </h3>

                        </div>

                        <div class="text-success fs-1">
                            <i class="bi bi-cpu"></i>
                        </div>

                    </div>

                </div>

            </div>


            <!-- Active -->
            <div class="col-md-3">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                Active
                            </small>

                            <h3 class="fw-bold mb-0">

                                {{ $components->getCollection()->where('status', 'Active')->count() }}

                            </h3>

                        </div>

                        <div class="text-success fs-1">
                            <i class="bi bi-check-circle"></i>
                        </div>

                    </div>

                </div>

            </div>


            <!-- Maintenance -->
            <div class="col-md-3">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                Under Maintenance
                            </small>

                            <h3 class="fw-bold mb-0">

                                {{ $components->getCollection()->where('status', 'Under Maintenance')->count() }}

                            </h3>

                        </div>

                        <div class="text-warning fs-1">
                            <i class="bi bi-tools"></i>
                        </div>

                    </div>

                </div>

            </div>


            <!-- Critical -->
            <div class="col-md-3">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                Critical Condition
                            </small>

                            <h3 class="fw-bold mb-0">

                                {{ $components->getCollection()->where('current_condition', 'Critical')->count() }}

                            </h3>

                        </div>

                        <div class="text-danger fs-1">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Components Table -->
        <div class="card shadow-sm border-0">

            <div class="card-header py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0 fw-bold">

                        <i class="bi bi-list-check text-success me-2"></i>

                        Registered Components

                    </h5>

                    <span class="badge bg-success">

                        {{ $components->total() }}

                    </span>

                </div>

            </div>


            <div class="card-body p-0">

                @if($components->count() > 0)

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th class="px-4">
                                        #
                                    </th>

                                    <th>
                                        Component
                                    </th>

                                    <th>
                                        Type
                                    </th>

                                    <th>
                                        Installation
                                    </th>

                                    <th>
                                        Condition
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th class="text-end px-4">
                                        Actions
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($components as $component)

                                    <tr>

                                        <!-- Number -->
                                        <td class="px-4">

                                            {{ $components->firstItem() + $loop->index }}

                                        </td>


                                        <!-- Component -->
                                        <td>

                                            <div class="d-flex align-items-center">

                                                <div class="bg-success bg-opacity-10
                                                            rounded-circle p-2 me-3">

                                                    <i class="bi bi-cpu text-success"></i>

                                                </div>

                                                <div>

                                                    <div class="fw-semibold">
                                                        {{ $component->name }}
                                                    </div>

                                                    @if($component->manufacturer)

                                                        <small class="text-muted">

                                                            {{ $component->manufacturer }}

                                                            @if($component->model)
                                                                · {{ $component->model }}
                                                            @endif

                                                        </small>

                                                    @endif

                                                </div>

                                            </div>

                                        </td>


                                        <!-- Type -->
                                        <td>

                                            @if($component->componentType)

                                                <span class="badge bg-light text-dark border">

                                                    {{ $component->componentType->name }}

                                                </span>

                                            @else

                                                <span class="text-muted">
                                                    Not specified
                                                </span>

                                            @endif

                                        </td>


                                        <!-- Installation -->
                                        <td>

                                            @if($component->installation)

                                                <div class="fw-semibold">
                                                    {{ $component->installation->name }}
                                                </div>

                                                <small class="text-muted">
                                                    {{ $component->installation->location }}
                                                </small>

                                            @else

                                                <span class="text-muted">
                                                    Not specified
                                                </span>

                                            @endif

                                        </td>


                                        <!-- Condition -->
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


                                        <!-- Status -->
                                        <td>

                                            @if($component->status === 'Active')

                                                <span class="badge bg-success">
                                                    Active
                                                </span>

                                            @elseif($component->status === 'Under Maintenance')

                                                <span class="badge bg-warning text-dark">
                                                    Under Maintenance
                                                </span>

                                            @elseif($component->status === 'Replaced')

                                                <span class="badge bg-info">
                                                    Replaced
                                                </span>

                                            @else

                                                <span class="badge bg-secondary">
                                                    {{ $component->status ?? 'Inactive' }}
                                                </span>

                                            @endif

                                        </td>


                                        <!-- Actions -->
                                        <td class="text-end px-4">

                                            <div class="btn-group">

                                                <a href="{{ route('components.show', $component) }}"
                                                   class="btn btn-sm btn-outline-success"
                                                   title="View Component">

                                                    <i class="bi bi-eye"></i>

                                                </a>


                                                <a href="{{ route('components.edit', $component) }}"
                                                   class="btn btn-sm btn-outline-warning"
                                                   title="Edit Component">

                                                    <i class="bi bi-pencil"></i>

                                                </a>


                                                <form action="{{ route('components.destroy', $component) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this component?');">

                                                    @csrf

                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Delete Component">

                                                        <i class="bi bi-trash"></i>

                                                    </button>

                                                </form>

                                            </div>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    <!-- Pagination -->
                    @if($components->hasPages())

                        <div class="p-3 border-top">

                            {{ $components->links() }}

                        </div>

                    @endif

                @else

                    <!-- Empty State -->
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
                            Start by registering the solar panels, batteries,
                            inverter and other components in your installation.
                        </p>

                        <a href="{{ route('components.create') }}"
                           class="btn btn-success">

                            <i class="bi bi-plus-circle me-1"></i>
                            Register First Component

                        </a>

                    </div>

                @endif

            </div>

        </div>


        <!-- Information Notice -->
        <div class="alert alert-light border mt-4">

            <div class="d-flex align-items-start">

                <i class="bi bi-info-circle text-success fs-4 me-3"></i>

                <div>

                    <h6 class="fw-bold mb-1">
                        Component Tracking
                    </h6>

                    <p class="mb-0 text-muted">
                        Each registered component will later be linked to
                        inspections, measurements, maintenance records,
                        maintenance costs and replacement forecasts.
                    </p>

                </div>

            </div>

        </div>

    </div>
</x-app-layout>