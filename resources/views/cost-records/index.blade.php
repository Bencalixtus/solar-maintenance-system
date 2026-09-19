@extends('layouts.app')

@section('title', 'Cost Management')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold">
                <i class="bi bi-cash-stack text-success me-2"></i>
                Cost Management
            </h1>
            <p class="text-muted mb-0">
                Track maintenance, repair, replacement and other system-related costs.
            </p>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="{{ route('cost-records.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i>
                Add Cost Record
            </a>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small text-uppercase fw-semibold">
                                Total Cost
                            </div>

                            <h3 class="fw-bold mt-2 mb-0">
                                ₦{{ number_format($totalCost, 2) }}
                            </h3>
                        </div>

                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-cash-stack text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Maintenance --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small text-uppercase fw-semibold">
                                Maintenance
                            </div>

                            <h3 class="fw-bold mt-2 mb-0">
                                ₦{{ number_format($maintenanceCost, 2) }}
                            </h3>
                        </div>

                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-tools text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Repair --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small text-uppercase fw-semibold">
                                Repairs
                            </div>

                            <h3 class="fw-bold mt-2 mb-0">
                                ₦{{ number_format($repairCost, 2) }}
                            </h3>
                        </div>

                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-wrench-adjustable text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Replacement --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small text-uppercase fw-semibold">
                                Replacement
                            </div>

                            <h3 class="fw-bold mt-2 mb-0">
                                ₦{{ number_format($replacementCost, 2) }}
                            </h3>
                        </div>

                        <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-arrow-repeat text-danger fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Secondary Cost Cards --}}
    <div class="row g-3 mb-4">

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">
                            Parts / Materials
                        </span>

                        <strong>
                            ₦{{ number_format($partsCost, 2) }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">
                            Labour / Service
                        </span>

                        <strong>
                            ₦{{ number_format($labourCost, 2) }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">
                            Other Costs
                        </span>

                        <strong>
                            ₦{{ number_format($otherCost, 2) }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Charts --}}
    <div class="row g-4 mb-4">

        {{-- Cost Type Chart --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-pie-chart text-success me-2"></i>
                        Cost Distribution
                    </h5>

                    <p class="text-muted small mb-0">
                        Breakdown of recorded costs by category.
                    </p>
                </div>

                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="costTypeChart"></canvas>
                    </div>
                </div>

            </div>
        </div>

        {{-- Component Cost Chart --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-bar-chart text-success me-2"></i>
                        Cost by Component
                    </h5>

                    <p class="text-muted small mb-0">
                        Total recorded cost associated with each component.
                    </p>
                </div>

                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="componentCostChart"></canvas>
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- Cost Records Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 pt-4 px-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">

                <div>
                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-receipt text-success me-2"></i>
                        Cost Records
                    </h5>

                    <p class="text-muted small mb-0">
                        Complete history of system-related expenditure.
                    </p>
                </div>

                <div class="mt-3 mt-md-0">
                    <span class="badge bg-light text-dark border">
                        {{ $costRecords->count() }} Records
                    </span>
                </div>

            </div>
        </div>

        <div class="card-body px-4">

            @if($costRecords->count() > 0)

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-light">

                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Component</th>
                                <th>Cost Type</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th class="text-end">Action</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($costRecords as $record)

                                <tr>

                                    <td class="text-muted">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        {{ $record->cost_date?->format('d M Y') ?? 'N/A' }}
                                    </td>

                                    <td>

                                        @if($record->component)

                                            <div class="fw-semibold">
                                                {{ $record->component->name }}
                                            </div>

                                            <small class="text-muted">
                                                {{ $record->component->componentType->name ?? 'Component' }}
                                            </small>

                                        @else

                                            <span class="text-muted">
                                                Not assigned
                                            </span>

                                        @endif

                                    </td>

                                    <td>

                                        @php
                                            $badgeClass = match($record->cost_type) {
                                                'Maintenance' => 'bg-primary',
                                                'Repair' => 'bg-warning text-dark',
                                                'Replacement' => 'bg-danger',
                                                'Parts/Materials' => 'bg-info text-dark',
                                                'Labour/Service' => 'bg-success',
                                                default => 'bg-secondary',
                                            };
                                        @endphp

                                        <span class="badge {{ $badgeClass }}">
                                            {{ $record->cost_type }}
                                        </span>

                                    </td>

                                    <td>
                                        <div style="max-width: 260px;">
                                            {{ \Illuminate\Support\Str::limit(
                                                $record->description,
                                                60
                                            ) }}
                                        </div>
                                    </td>

                                    <td class="fw-bold">
                                        ₦{{ number_format($record->amount, 2) }}
                                    </td>

                                    <td class="text-end">

                                        <div class="btn-group">

                                            <a href="{{ route('cost-records.show', $record) }}"
                                               class="btn btn-sm btn-outline-success"
                                               title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <a href="{{ route('cost-records.edit', $record) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <form action="{{ route('cost-records.destroy', $record) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this cost record?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                        <tfoot class="table-light">

                            <tr>

                                <th colspan="5" class="text-end">
                                    Total:
                                </th>

                                <th>
                                    ₦{{ number_format($totalCost, 2) }}
                                </th>

                                <th></th>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <i class="bi bi-receipt display-4 text-muted"></i>

                    <h5 class="mt-3">
                        No Cost Records Yet
                    </h5>

                    <p class="text-muted">
                        Start recording maintenance, repair and replacement costs.
                    </p>

                    <a href="{{ route('cost-records.create') }}"
                       class="btn btn-success">

                        <i class="bi bi-plus-circle me-1"></i>
                        Add First Cost Record

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>
@endsection


@push('scripts')

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    const costLabels = @json(array_keys($costByType));
    const costValues = @json(array_values($costByType));

    const componentLabels = @json(
        $componentCosts->map(
            fn ($item) => $item->component->name ?? 'Unknown Component'
        )->values()
    );

    const componentValues = @json(
        $componentCosts->map(
            fn ($item) => (float) $item->total_cost
        )->values()
    );


    // Cost Distribution Chart
    const costTypeCanvas = document.getElementById('costTypeChart');

    if (costTypeCanvas) {

        new Chart(costTypeCanvas, {

            type: 'doughnut',

            data: {

                labels: costLabels,

                datasets: [{

                    data: costValues,

                    borderWidth: 1

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {
                        position: 'bottom'
                    }

                }

            }

        });

    }


    // Component Cost Chart
    const componentCanvas =
        document.getElementById('componentCostChart');

    if (componentCanvas) {

        new Chart(componentCanvas, {

            type: 'bar',

            data: {

                labels: componentLabels,

                datasets: [{

                    label: 'Total Cost (₦)',

                    data: componentValues,

                    borderWidth: 1

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                scales: {

                    y: {

                        beginAtZero: true,

                        ticks: {

                            callback: function(value) {

                                return '₦' +
                                    Number(value).toLocaleString();

                            }

                        }

                    }

                },

                plugins: {

                    legend: {
                        display: false
                    }

                }

            }

        });

    }

</script>

@endpush