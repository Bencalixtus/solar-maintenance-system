@extends('layouts.app')

@section('title', 'Replacement Forecast Details')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                <i class="bi bi-graph-up-arrow text-success me-2"></i>
                Replacement Forecast Details
            </h3>

            <p class="text-muted mb-0">
                Detailed condition and replacement planning analysis
            </p>

        </div>


        <div class="d-flex gap-2 mt-2 mt-md-0">

            <a href="{{ route('replacement-forecasts.index') }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Back to Forecasts

            </a>


            <form action="{{ route('replacement-forecasts.refresh', $replacementForecast->component) }}"
                  method="POST">

                @csrf

                <button type="submit"
                        class="btn btn-success"
                        onclick="return confirm('Refresh this replacement forecast?')">

                    <i class="bi bi-arrow-clockwise me-1"></i>
                    Refresh Forecast

                </button>

            </form>

        </div>

    </div>


    {{-- Risk Banner --}}
    @php

        $riskClass = match($replacementForecast->risk_level) {
            'Low' => 'success',
            'Medium' => 'warning',
            'High' => 'danger',
            default => 'secondary',
        };

        $riskIcon = match($replacementForecast->risk_level) {
            'Low' => 'bi-shield-check',
            'Medium' => 'bi-exclamation-triangle',
            'High' => 'bi-shield-exclamation',
            default => 'bi-question-circle',
        };

    @endphp


    <div class="alert alert-{{ $riskClass }} border-0 shadow-sm mb-4">

        <div class="d-flex align-items-center">

            <i class="bi {{ $riskIcon }} fs-2 me-3"></i>

            <div>

                <h5 class="fw-bold mb-1">
                    {{ $replacementForecast->risk_level }} Replacement Risk
                </h5>

                <div>
                    {{ $replacementForecast->recommended_action }}
                </div>

            </div>

        </div>

    </div>


    {{-- Component Information --}}
    <div class="row g-4 mb-4">

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-bottom">

                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-cpu text-success me-2"></i>
                        Component Information
                    </h5>

                </div>


                <div class="card-body">

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Component Name
                        </small>

                        <strong class="fs-5">
                            {{ $replacementForecast->component->name }}
                        </strong>

                    </div>


                    <div class="row g-3">

                        <div class="col-6">

                            <small class="text-muted d-block">
                                Component Type
                            </small>

                            <strong>
                                {{ $replacementForecast->component->componentType?->name ?? 'N/A' }}
                            </strong>

                        </div>


                        <div class="col-6">

                            <small class="text-muted d-block">
                                Status
                            </small>

                            <strong>
                                {{ $replacementForecast->component->status ?? 'N/A' }}
                            </strong>

                        </div>


                        <div class="col-6">

                            <small class="text-muted d-block">
                                Manufacturer
                            </small>

                            <strong>
                                {{ $replacementForecast->component->manufacturer ?? 'N/A' }}
                            </strong>

                        </div>


                        <div class="col-6">

                            <small class="text-muted d-block">
                                Model
                            </small>

                            <strong>
                                {{ $replacementForecast->component->model ?? 'N/A' }}
                            </strong>

                        </div>


                        <div class="col-12">

                            <small class="text-muted d-block">
                                Serial Number
                            </small>

                            <strong>
                                {{ $replacementForecast->component->serial_number ?? 'N/A' }}
                            </strong>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Forecast Metrics --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-bottom">

                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-speedometer2 text-success me-2"></i>
                        Forecast Metrics
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-3">

                        {{-- Age --}}
                        <div class="col-md-4">

                            <div class="p-3 border rounded h-100">

                                <small class="text-muted d-block">
                                    Current Age
                                </small>

                                <h4 class="fw-bold mt-2 mb-0">

                                    @if($replacementForecast->current_age !== null)
                                        {{ number_format((float) $replacementForecast->current_age, 2) }}
                                        <small class="fs-6 text-muted">years</small>
                                    @else
                                        N/A
                                    @endif

                                </h4>

                            </div>

                        </div>


                        {{-- Condition --}}
                        <div class="col-md-4">

                            <div class="p-3 border rounded h-100">

                                <small class="text-muted d-block">
                                    Current Condition
                                </small>

                                <h4 class="fw-bold mt-2 mb-0">

                                    {{ $replacementForecast->current_condition ?? 'N/A' }}

                                </h4>

                            </div>

                        </div>


                        {{-- Degradation --}}
                        <div class="col-md-4">

                            <div class="p-3 border rounded h-100">

                                <small class="text-muted d-block">
                                    Degradation Rate
                                </small>

                                <h4 class="fw-bold mt-2 mb-0">

                                    @if($replacementForecast->degradation_rate !== null)

                                        {{ number_format((float) $replacementForecast->degradation_rate, 2) }}%

                                    @else

                                        N/A

                                    @endif

                                </h4>

                            </div>

                        </div>


                        {{-- Remaining Life --}}
                        <div class="col-md-4">

                            <div class="p-3 border rounded h-100">

                                <small class="text-muted d-block">
                                    Estimated Remaining Life
                                </small>

                                <h4 class="fw-bold mt-2 mb-0">

                                    @if($replacementForecast->estimated_remaining_life !== null)

                                        {{ number_format((float) $replacementForecast->estimated_remaining_life, 2) }}

                                        <small class="fs-6 text-muted">
                                            years
                                        </small>

                                    @else

                                        N/A

                                    @endif

                                </h4>

                            </div>

                        </div>


                        {{-- Replacement Cost --}}
                        <div class="col-md-4">

                            <div class="p-3 border rounded h-100">

                                <small class="text-muted d-block">
                                    Estimated Replacement Cost
                                </small>

                                <h4 class="fw-bold mt-2 mb-0">

                                    @if($replacementForecast->estimated_replacement_cost !== null)

                                        ₦{{ number_format((float) $replacementForecast->estimated_replacement_cost, 2) }}

                                    @else

                                        N/A

                                    @endif

                                </h4>

                            </div>

                        </div>


                        {{-- Forecast Date --}}
                        <div class="col-md-4">

                            <div class="p-3 border rounded h-100">

                                <small class="text-muted d-block">
                                    Forecast Date
                                </small>

                                <h4 class="fw-bold mt-2 mb-0">

                                    {{ optional($replacementForecast->forecast_date)->format('d M Y') }}

                                </h4>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Recommendation --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-bottom">

            <h5 class="fw-bold mb-0">
                <i class="bi bi-lightbulb text-warning me-2"></i>
                Recommended Action
            </h5>

        </div>


        <div class="card-body">

            <div class="p-3 rounded bg-light border">

                <div class="fw-bold mb-2">
                    {{ $replacementForecast->recommended_action }}
                </div>

                <div class="text-muted">
                    The recommendation is generated from the component's
                    condition, age, degradation behaviour, maintenance history
                    and expected lifespan.
                </div>

            </div>

        </div>

    </div>


    {{-- Forecast Notes --}}
    @if($replacementForecast->forecast_notes)

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-bottom">

                <h5 class="fw-bold mb-0">
                    <i class="bi bi-journal-text text-success me-2"></i>
                    Forecast Analysis
                </h5>

            </div>


            <div class="card-body">

                <div class="bg-light border rounded p-3"
                     style="white-space: pre-line;">

                    {{ $replacementForecast->forecast_notes }}

                </div>

            </div>

        </div>

    @endif


    {{-- Recent Measurements --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-bottom">

            <h5 class="fw-bold mb-0">
                <i class="bi bi-speedometer text-success me-2"></i>
                Recent Measurements
            </h5>

        </div>


        <div class="card-body p-0">

            @if($replacementForecast->component->measurements->count() > 0)

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th class="ps-4">Date</th>
                                <th>Parameter</th>
                                <th>Value</th>
                                <th>Unit</th>
                                <th>Reference</th>
                                <th>Remarks</th>
                            </tr>

                        </thead>


                        <tbody>

                            @foreach($replacementForecast->component->measurements->sortByDesc('measurement_date')->take(10) as $measurement)

                                <tr>

                                    <td class="ps-4">
                                        {{ optional($measurement->measurement_date)->format('d M Y') }}
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
                                        {{ $measurement->unit ?? '-' }}
                                    </td>

                                    <td>
                                        @if($measurement->reference_value !== null)
                                            {{ number_format((float) $measurement->reference_value, 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td>
                                        {{ $measurement->remarks ?? '-' }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <i class="bi bi-speedometer2 fs-1 text-muted"></i>

                    <p class="text-muted mt-3 mb-0">
                        No measurement records are available for this component.
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- Maintenance and Cost --}}
    <div class="row g-4 mb-4">

        {{-- Maintenance --}}
        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-bottom">

                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-tools text-success me-2"></i>
                        Maintenance History
                    </h5>

                </div>


                <div class="card-body">

                    @if($replacementForecast->component->maintenanceRecords->count() > 0)

                        @foreach($replacementForecast->component->maintenanceRecords->sortByDesc('maintenance_date')->take(5) as $maintenance)

                            <div class="border-bottom pb-3 mb-3">

                                <div class="fw-semibold">
                                    {{ $maintenance->maintenance_type ?? 'Maintenance' }}
                                </div>

                                <small class="text-muted">
                                    {{ optional($maintenance->maintenance_date)->format('d M Y') }}
                                </small>

                                @if($maintenance->description)

                                    <div class="small text-muted mt-1">
                                        {{ $maintenance->description }}
                                    </div>

                                @endif

                            </div>

                        @endforeach

                    @else

                        <div class="text-center py-4">

                            <i class="bi bi-tools fs-2 text-muted"></i>

                            <p class="text-muted mt-2 mb-0">
                                No maintenance records available.
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- Cost --}}
        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-bottom">

                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-cash-stack text-success me-2"></i>
                        Cost Information
                    </h5>

                </div>


                <div class="card-body">

                    @if($replacementForecast->component->costRecords->count() > 0)

                        @foreach($replacementForecast->component->costRecords->sortByDesc('cost_date')->take(5) as $cost)

                            <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">

                                <div>

                                    <div class="fw-semibold">
                                        {{ $cost->cost_type }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $cost->description }}
                                    </small>

                                    <div class="small text-muted">
                                        {{ optional($cost->cost_date)->format('d M Y') }}
                                    </div>

                                </div>


                                <strong>
                                    ₦{{ number_format((float) $cost->amount, 2) }}
                                </strong>

                            </div>

                        @endforeach

                    @else

                        <div class="text-center py-4">

                            <i class="bi bi-cash-stack fs-2 text-muted"></i>

                            <p class="text-muted mt-2 mb-0">
                                No cost records available.
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- Disclaimer --}}
    <div class="alert alert-warning border-0 shadow-sm">

        <div class="d-flex">

            <i class="bi bi-info-circle fs-5 me-2"></i>

            <div>

                <strong>Decision-support notice:</strong>

                This forecast provides an estimated replacement planning
                recommendation based on available component data. It should
                support, not replace, physical inspection and professional
                maintenance decisions.

            </div>

        </div>

    </div>

</div>

@endsection