<x-app-layout>

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="bi bi-building me-2 text-success"></i>
                    Solar Installations
                </h2>

                <p class="text-muted mb-0">
                    Manage and monitor registered solar-battery installations.
                </p>
            </div>

            <a href="{{ route('installations.create') }}"
               class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i>
                Add Installation
            </a>
        </div>
    </x-slot>


    <div class="container-fluid">

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm"
                 role="alert">

                <i class="bi bi-check-circle-fill me-2"></i>

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>
            </div>
        @endif


        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm">

                <strong>
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Please correct the following errors:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>
        @endif


        {{-- Statistics --}}
        <div class="row mb-4">

            {{-- Total Installations --}}
            <div class="col-lg-4 col-md-6 mb-3">

                <div class="small-box bg-success shadow-sm">

                    <div class="inner">

                        <h3>
                            {{ $installations->total() }}
                        </h3>

                        <p>
                            Total Installations
                        </p>

                    </div>

                    <div class="icon">

                        <i class="bi bi-building"></i>

                    </div>

                </div>

            </div>


            {{-- Active Installations --}}
            <div class="col-lg-4 col-md-6 mb-3">

                <div class="small-box bg-warning shadow-sm">

                    <div class="inner">

                        <h3>
                            {{ \App\Models\Installation::where('status', 'Active')->count() }}
                        </h3>

                        <p>
                            Active Installations
                        </p>

                    </div>

                    <div class="icon">

                        <i class="bi bi-check-circle"></i>

                    </div>

                </div>

            </div>


            {{-- Maintenance --}}
            <div class="col-lg-4 col-md-6 mb-3">

                <div class="small-box bg-danger shadow-sm">

                    <div class="inner">

                        <h3>
                            {{ \App\Models\Installation::where('status', 'Under Maintenance')->count() }}
                        </h3>

                        <p>
                            Under Maintenance
                        </p>

                    </div>

                    <div class="icon">

                        <i class="bi bi-tools"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- Installation List --}}
        <div class="card shadow-sm">

            <div class="card-header">

                <div class="d-flex justify-content-between align-items-center">

                    <h3 class="card-title mb-0">

                        <i class="bi bi-list-ul me-2 text-success"></i>

                        Installation List

                    </h3>

                    <span class="badge text-bg-success">

                        {{ $installations->total() }} Records

                    </span>

                </div>

            </div>


            <div class="card-body p-0">

                @if ($installations->count() > 0)

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th class="px-3">
                                        #
                                    </th>

                                    <th>
                                        Installation
                                    </th>

                                    <th>
                                        Location
                                    </th>

                                    <th>
                                        Installation Date
                                    </th>

                                    <th>
                                        Capacity
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th class="text-center">
                                        Actions
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach ($installations as $installation)

                                    <tr>

                                        <td class="px-3">

                                            {{ $loop->iteration + ($installations->currentPage() - 1) * $installations->perPage() }}

                                        </td>


                                        <td>

                                            <div class="fw-semibold">

                                                <i class="bi bi-sun me-1 text-warning"></i>

                                                {{ $installation->name }}

                                            </div>

                                            <small class="text-muted">

                                                ID: #{{ $installation->id }}

                                            </small>

                                        </td>


                                        <td>

                                            <i class="bi bi-geo-alt me-1 text-success"></i>

                                            {{ $installation->location }}

                                        </td>


                                        <td>

                                            {{ $installation->installation_date
                                                ? $installation->installation_date->format('d M Y')
                                                : 'N/A'
                                            }}

                                        </td>


                                        <td>

                                            @if ($installation->system_capacity)

                                                <strong>
                                                    {{ number_format($installation->system_capacity, 2) }}
                                                </strong>

                                                kW

                                            @else

                                                <span class="text-muted">
                                                    Not specified
                                                </span>

                                            @endif

                                        </td>


                                        <td>

                                            @if ($installation->status === 'Active')

                                                <span class="badge text-bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    Active
                                                </span>

                                            @elseif ($installation->status === 'Under Maintenance')

                                                <span class="badge text-bg-warning">
                                                    <i class="bi bi-tools me-1"></i>
                                                    Under Maintenance
                                                </span>

                                            @else

                                                <span class="badge text-bg-secondary">
                                                    <i class="bi bi-pause-circle me-1"></i>
                                                    Inactive
                                                </span>

                                            @endif

                                        </td>


                                        <td class="text-center">

                                            <div class="btn-group"
                                                 role="group">


                                                {{-- View --}}

                                                <a href="{{ route('installations.show', $installation) }}"
                                                   class="btn btn-sm btn-outline-success"
                                                   title="View Installation">

                                                    <i class="bi bi-eye"></i>

                                                </a>


                                                {{-- Edit --}}

                                                <a href="{{ route('installations.edit', $installation) }}"
                                                   class="btn btn-sm btn-outline-warning"
                                                   title="Edit Installation">

                                                    <i class="bi bi-pencil"></i>

                                                </a>


                                                {{-- Delete --}}

                                                <form
                                                    action="{{ route('installations.destroy', $installation) }}"
                                                    method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this installation? This action cannot be undone.');"
                                                >

                                                    @csrf

                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Delete Installation"
                                                    >

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

                @else

                    {{-- Empty State --}}

                    <div class="text-center py-5">

                        <div class="mb-3">

                            <i class="bi bi-building"
                               style="font-size: 4rem; color: #198754;">
                            </i>

                        </div>

                        <h4 class="fw-semibold">
                            No Solar Installations Yet
                        </h4>

                        <p class="text-muted mb-4">

                            Start by registering your first solar-battery
                            installation in the system.

                        </p>

                        <a href="{{ route('installations.create') }}"
                           class="btn btn-success">

                            <i class="bi bi-plus-circle me-1"></i>

                            Add First Installation

                        </a>

                    </div>

                @endif

            </div>


            {{-- Pagination --}}

            @if ($installations->hasPages())

                <div class="card-footer">

                    {{ $installations->links() }}

                </div>

            @endif

        </div>

    </div>

</x-app-layout>