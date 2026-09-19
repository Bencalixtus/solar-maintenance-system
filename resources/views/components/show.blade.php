<x-app-layout>
    <div class="container-fluid py-4">

        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">
                    <i class="fas fa-solar-panel text-success me-2"></i>
                    Component Details
                </h3>

                <p class="text-muted mb-0">
                    Detailed information and maintenance history for this component.
                </p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('components.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Back
                </a>

                <a href="{{ route('components.edit', $solarComponent) }}"
                   class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i>
                    Edit
                </a>
            </div>
        </div>


        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>
            </div>
        @endif


        {{-- Component Overview --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Component Overview
                </h5>
            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Name --}}
                    <div class="col-md-6 mb-4">
                        <small class="text-muted">Component Name</small>

                        <h5 class="mb-0">
                            {{ $solarComponent->name }}
                        </h5>
                    </div>


                    {{-- Type --}}
                    <div class="col-md-6 mb-4">
                        <small class="text-muted">Component Type</small>

                        <h5 class="mb-0">
                            {{ $solarComponent->componentType->name ?? 'N/A' }}
                        </h5>
                    </div>


                    {{-- Installation --}}
                    <div class="col-md-6 mb-4">
                        <small class="text-muted">Solar Installation</small>

                        <div>
                            @if($solarComponent->installation)

                                <a href="{{ route('installations.show', $solarComponent->installation) }}"
                                   class="text-decoration-none fw-semibold">

                                    <i class="fas fa-solar-panel text-success me-1"></i>

                                    {{ $solarComponent->installation->name }}

                                </a>

                            @else

                                <span class="text-muted">
                                    N/A
                                </span>

                            @endif
                        </div>
                    </div>


                    {{-- Manufacturer --}}
                    <div class="col-md-6 mb-4">
                        <small class="text-muted">Manufacturer</small>

                        <p class="mb-0">
                            {{ $solarComponent->manufacturer ?: 'Not specified' }}
                        </p>
                    </div>


                    {{-- Model --}}
                    <div class="col-md-6 mb-4">
                        <small class="text-muted">Model</small>

                        <p class="mb-0">
                            {{ $solarComponent->model ?: 'Not specified' }}
                        </p>
                    </div>


                    {{-- Serial --}}
                    <div class="col-md-6 mb-4">
                        <small class="text-muted">Serial Number</small>

                        <p class="mb-0">
                            {{ $solarComponent->serial_number ?: 'Not specified' }}
                        </p>
                    </div>


                    {{-- Installation Date --}}
                    <div class="col-md-6 mb-4">
                        <small class="text-muted">Installation Date</small>

                        <p class="mb-0">
                            @if($solarComponent->installation_date)
                                {{ $solarComponent->installation_date->format('d M Y') }}
                            @else
                                Not specified
                            @endif
                        </p>
                    </div>


                    {{-- Lifespan --}}
                    <div class="col-md-6 mb-4">
                        <small class="text-muted">Expected Lifespan</small>

                        <p class="mb-0">
                            @if($solarComponent->expected_lifespan)
                                {{ $solarComponent->expected_lifespan }} years
                            @else
                                Not specified
                            @endif
                        </p>
                    </div>


                    {{-- Capacity --}}
                    <div class="col-md-6 mb-4">
                        <small class="text-muted">Rated Capacity</small>

                        <p class="mb-0">

                            @if($solarComponent->rated_capacity !== null)

                                {{ number_format((float) $solarComponent->rated_capacity, 2) }}

                                @if(
                                    $solarComponent->componentType &&
                                    $solarComponent->componentType->name === 'Battery'
                                )
                                    Ah
                                @else
                                    W
                                @endif

                            @else

                                Not specified

                            @endif

                        </p>
                    </div>


                    {{-- Voltage --}}
                    <div class="col-md-6 mb-4">
                        <small class="text-muted">Rated Voltage</small>

                        <p class="mb-0">

                            @if($solarComponent->rated_voltage !== null)

                                {{ number_format((float) $solarComponent->rated_voltage, 2) }} V

                            @else

                                Not specified

                            @endif

                        </p>
                    </div>


                    {{-- Condition --}}
                    <div class="col-md-6 mb-4">

                        <small class="text-muted">
                            Current Condition
                        </small>

                        <div>

                            @php
                                $conditionClass = match($solarComponent->current_condition) {
                                    'Excellent' => 'bg-success',
                                    'Good' => 'bg-primary',
                                    'Fair' => 'bg-warning text-dark',
                                    'Poor' => 'bg-orange',
                                    'Critical' => 'bg-danger',
                                    default => 'bg-secondary',
                                };
                            @endphp

                            <span class="badge {{ $conditionClass }} px-3 py-2">
                                {{ $solarComponent->current_condition }}
                            </span>

                        </div>
                    </div>


                    {{-- Status --}}
                    <div class="col-md-6 mb-4">

                        <small class="text-muted">
                            Status
                        </small>

                        <div>

                            @php
                                $statusClass = match($solarComponent->status) {
                                    'Active' => 'bg-success',
                                    'Under Maintenance' => 'bg-warning text-dark',
                                    'Inactive' => 'bg-secondary',
                                    'Replaced' => 'bg-danger',
                                    default => 'bg-secondary',
                                };
                            @endphp

                            <span class="badge {{ $statusClass }} px-3 py-2">
                                {{ $solarComponent->status }}
                            </span>

                        </div>
                    </div>

                </div>


                {{-- Description --}}
                @if($solarComponent->description)

                    <hr>

                    <small class="text-muted">
                        Description
                    </small>

                    <p class="mb-0">
                        {{ $solarComponent->description }}
                    </p>

                @endif

            </div>
        </div>


        {{-- Statistics --}}
        <div class="row mb-4">

            {{-- Measurements --}}
            <div class="col-md-3 mb-3">

                <div class="card shadow-sm h-100 border-start border-4 border-info">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>
                                <small class="text-muted">
                                    Measurements
                                </small>

                                <h3 class="mb-0">
                                    {{ $solarComponent->measurements->count() }}
                                </h3>
                            </div>

                            <i class="fas fa-chart-line fa-2x text-info"></i>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Maintenance Schedules --}}
            <div class="col-md-3 mb-3">

                <div class="card shadow-sm h-100 border-start border-4 border-warning">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <small class="text-muted">
                                    Maintenance Schedules
                                </small>

                                <h3 class="mb-0">
                                    {{ $solarComponent->maintenanceSchedules->count() }}
                                </h3>

                            </div>

                            <i class="fas fa-calendar-check fa-2x text-warning"></i>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Maintenance Records --}}
            <div class="col-md-3 mb-3">

                <div class="card shadow-sm h-100 border-start border-4 border-success">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <small class="text-muted">
                                    Maintenance Records
                                </small>

                                <h3 class="mb-0">
                                    {{ $solarComponent->maintenanceRecords->count() }}
                                </h3>

                            </div>

                            <i class="fas fa-tools fa-2x text-success"></i>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Total Cost --}}
            <div class="col-md-3 mb-3">

                <div class="card shadow-sm h-100 border-start border-4 border-danger">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <small class="text-muted">
                                    Total Cost
                                </small>

                                <h3 class="mb-0">
                                    ₦{{ number_format((float) $solarComponent->costRecords->sum('amount'), 2) }}
                                </h3>

                            </div>

                            <i class="fas fa-money-bill-wave fa-2x text-danger"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Measurements --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-chart-line text-info me-2"></i>

                    Measurements & Degradation Tracking

                </h5>

            </div>


            <div class="card-body">

                @if($solarComponent->measurements->count())

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead class="table-light">

                                <tr>
                                    <th>Date</th>
                                    <th>Parameter</th>
                                    <th>Value</th>
                                    <th>Reference</th>
                                    <th>Unit</th>
                                    <th>Remarks</th>
                                </tr>

                            </thead>


                            <tbody>

                                @foreach(
                                    $solarComponent->measurements->sortByDesc('measurement_date')
                                    as $measurement
                                )

                                    <tr>

                                        <td>
                                            {{ $measurement->measurement_date
                                                ? $measurement->measurement_date->format('d M Y')
                                                : 'N/A' }}
                                        </td>

                                        <td>
                                            <strong>
                                                {{ $measurement->parameter }}
                                            </strong>
                                        </td>

                                        <td>
                                            {{ number_format((float) $measurement->value, 2) }}
                                        </td>

                                        <td>
                                            {{ $measurement->reference_value !== null
                                                ? number_format((float) $measurement->reference_value, 2)
                                                : 'N/A' }}
                                        </td>

                                        <td>
                                            {{ $measurement->unit ?: '-' }}
                                        </td>

                                        <td>
                                            {{ $measurement->remarks ?: '-' }}
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="text-center py-5">

                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>

                        <h6>
                            No Measurements Available
                        </h6>

                        <p class="text-muted mb-0">
                            Measurement records will appear here once
                            inspection and performance data are recorded.
                        </p>

                    </div>

                @endif

            </div>

        </div>


        {{-- Maintenance Schedule --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-calendar-alt text-warning me-2"></i>

                    Maintenance Schedule

                </h5>

            </div>


            <div class="card-body">

                @if($solarComponent->maintenanceSchedules->count())

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead class="table-light">

                                <tr>
                                    <th>Task</th>
                                    <th>Frequency</th>
                                    <th>Last Maintenance</th>
                                    <th>Next Due</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                </tr>

                            </thead>


                            <tbody>

                                @foreach(
                                    $solarComponent->maintenanceSchedules->sortBy('next_due_date')
                                    as $schedule
                                )

                                    <tr>

                                        <td>
                                            {{ $schedule->maintenance_task }}
                                        </td>

                                        <td>
                                            {{ $schedule->frequency }}
                                        </td>

                                        <td>
                                            {{ $schedule->last_maintenance_date
                                                ? $schedule->last_maintenance_date->format('d M Y')
                                                : 'N/A' }}
                                        </td>

                                        <td>
                                            {{ $schedule->next_due_date
                                                ? $schedule->next_due_date->format('d M Y')
                                                : 'N/A' }}
                                        </td>

                                        <td>

                                            @php
                                                $priorityClass = match($schedule->priority) {
                                                    'High' => 'bg-danger',
                                                    'Medium' => 'bg-warning text-dark',
                                                    'Low' => 'bg-success',
                                                    default => 'bg-secondary',
                                                };
                                            @endphp

                                            <span class="badge {{ $priorityClass }}">
                                                {{ $schedule->priority }}
                                            </span>

                                        </td>

                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $schedule->status }}
                                            </span>
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="text-center py-5">

                        <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>

                        <h6>
                            No Maintenance Schedule
                        </h6>

                        <p class="text-muted mb-0">
                            Preventive maintenance schedules will appear here
                            after they are created.
                        </p>

                    </div>

                @endif

            </div>

        </div>


        {{-- Maintenance History --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-tools text-success me-2"></i>

                    Maintenance History

                </h5>

            </div>


            <div class="card-body">

                @if($solarComponent->maintenanceRecords->count())

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead class="table-light">

                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Condition Before</th>
                                    <th>Action Taken</th>
                                    <th>Condition After</th>
                                    <th>Status</th>
                                </tr>

                            </thead>


                            <tbody>

                                @foreach(
                                    $solarComponent->maintenanceRecords->sortByDesc('maintenance_date')
                                    as $record
                                )

                                    <tr>

                                        <td>
                                            {{ $record->maintenance_date
                                                ? $record->maintenance_date->format('d M Y')
                                                : 'N/A' }}
                                        </td>

                                        <td>
                                            {{ $record->maintenance_type }}
                                        </td>

                                        <td>
                                            {{ $record->condition_before ?: '-' }}
                                        </td>

                                        <td>
                                            {{ $record->action_taken ?: '-' }}
                                        </td>

                                        <td>
                                            {{ $record->condition_after ?: '-' }}
                                        </td>

                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $record->status }}
                                            </span>
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="text-center py-5">

                        <i class="fas fa-tools fa-3x text-muted mb-3"></i>

                        <h6>
                            No Maintenance Records
                        </h6>

                        <p class="text-muted mb-0">
                            Completed maintenance activities will appear here.
                        </p>

                    </div>

                @endif

            </div>

        </div>


        {{-- Cost Management --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-money-bill-wave text-danger me-2"></i>

                    Cost Summary

                </h5>

            </div>


            <div class="card-body">

                @if($solarComponent->costRecords->count())

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead class="table-light">

                                <tr>
                                    <th>Date</th>
                                    <th>Cost Type</th>
                                    <th>Description</th>
                                    <th class="text-end">Amount</th>
                                </tr>

                            </thead>


                            <tbody>

                                @foreach(
                                    $solarComponent->costRecords->sortByDesc('cost_date')
                                    as $cost
                                )

                                    <tr>

                                        <td>
                                            {{ $cost->cost_date
                                                ? $cost->cost_date->format('d M Y')
                                                : 'N/A' }}
                                        </td>

                                        <td>
                                            {{ $cost->cost_type }}
                                        </td>

                                        <td>
                                            {{ $cost->description ?: '-' }}
                                        </td>

                                        <td class="text-end fw-semibold">
                                            ₦{{ number_format((float) $cost->amount, 2) }}
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>


                            <tfoot class="table-light">

                                <tr>

                                    <th colspan="3" class="text-end">
                                        Total:
                                    </th>

                                    <th class="text-end text-danger">
                                        ₦{{ number_format((float) $solarComponent->costRecords->sum('amount'), 2) }}
                                    </th>

                                </tr>

                            </tfoot>

                        </table>

                    </div>

                @else

                    <div class="text-center py-5">

                        <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>

                        <h6>
                            No Cost Records
                        </h6>

                        <p class="text-muted mb-0">
                            Maintenance and replacement costs will appear here.
                        </p>

                    </div>

                @endif

            </div>

        </div>


        {{-- Replacement Forecast --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-crystal-ball text-primary me-2"></i>

                    Replacement Forecast

                </h5>

            </div>


            <div class="card-body">

                @if($solarComponent->replacementForecasts->count())

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead class="table-light">

                                <tr>
                                    <th>Forecast Date</th>
                                    <th>Current Age</th>
                                    <th>Condition</th>
                                    <th>Degradation Rate</th>
                                    <th>Remaining Life</th>
                                    <th>Risk</th>
                                    <th>Recommended Action</th>
                                </tr>

                            </thead>


                            <tbody>

                                @foreach(
                                    $solarComponent->replacementForecasts->sortByDesc('forecast_date')
                                    as $forecast
                                )

                                    <tr>

                                        <td>
                                            {{ $forecast->forecast_date
                                                ? $forecast->forecast_date->format('d M Y')
                                                : 'N/A' }}
                                        </td>

                                        <td>
                                            {{ $forecast->current_age ?? 'N/A' }}
                                        </td>

                                        <td>
                                            {{ $forecast->current_condition ?? 'N/A' }}
                                        </td>

                                        <td>
                                            {{ $forecast->degradation_rate !== null
                                                ? number_format((float) $forecast->degradation_rate, 2) . '%'
                                                : 'N/A' }}
                                        </td>

                                        <td>
                                            {{ $forecast->estimated_remaining_life ?? 'N/A' }}
                                        </td>

                                        <td>

                                            @php
                                                $riskClass = match($forecast->risk_level) {
                                                    'High' => 'bg-danger',
                                                    'Medium' => 'bg-warning text-dark',
                                                    'Low' => 'bg-success',
                                                    default => 'bg-secondary',
                                                };
                                            @endphp

                                            <span class="badge {{ $riskClass }}">
                                                {{ $forecast->risk_level }}
                                            </span>

                                        </td>

                                        <td>
                                            {{ $forecast->recommended_action ?? 'N/A' }}
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="text-center py-5">

                        <i class="fas fa-crystal-ball fa-3x text-muted mb-3"></i>

                        <h6>
                            No Replacement Forecast Available
                        </h6>

                        <p class="text-muted mb-0">
                            Replacement forecasting will be generated after
                            sufficient condition and measurement data are available.
                        </p>

                    </div>

                @endif

            </div>

        </div>


        {{-- Bottom Actions --}}
        <div class="card shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <a href="{{ route('components.index') }}"
                       class="btn btn-secondary">

                        <i class="fas fa-arrow-left me-1"></i>

                        Back to Components

                    </a>


                    <div class="d-flex gap-2">

                        <a href="{{ route('components.edit', $solarComponent) }}"
                           class="btn btn-warning">

                            <i class="fas fa-edit me-1"></i>

                            Edit Component

                        </a>


                        <form action="{{ route('components.destroy', $solarComponent) }}"
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this component? This action cannot be undone.');">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-danger">

                                <i class="fas fa-trash me-1"></i>

                                Delete

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <style>
        .bg-orange {
            background-color: #fd7e14 !important;
            color: #fff !important;
        }

        .border-start {
            border-left-width: 4px !important;
        }

        .card-header h5 {
            font-weight: 600;
        }

        .table th {
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }
    </style>

</x-app-layout>