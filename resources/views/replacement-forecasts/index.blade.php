@extends('layouts.app')

@section('title', 'Replacement Forecast')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-graph-up-arrow text-success me-2"></i>
                Replacement Forecast
            </h3>

            <p class="text-muted mb-0">
                Condition-based replacement planning and decision support
            </p>
        </div>

        <div class="mt-2 mt-md-0">
            <a href="{{ route('components.index') }}" class="btn btn-outline-success">
                <i class="bi bi-cpu me-1"></i>
                View Components
            </a>
        </div>
    </div>


    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">

        {{-- Total Forecasts --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small text-uppercase fw-semibold">
                                Total Forecasts
                            </div>

                            <h2 class="fw-bold mb-0 mt-2">
                                {{ $forecasts->count() }}
                            </h2>
                        </div>

                        <div class="rounded-circle bg-success-subtle p-3">
                            <i class="bi bi-clipboard-data fs-3 text-success"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        {{-- Low Risk --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small text-uppercase fw-semibold">
                                Low Risk
                            </div>

                            <h2 class="fw-bold mb-0 mt-2 text-success">
                                {{ $lowRisk }}
                            </h2>
                        </div>

                        <div class="rounded-circle bg-success-subtle p-3">
                            <i class="bi bi-shield-check fs-3 text-success"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        {{-- Medium Risk --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small text-uppercase fw-semibold">
                                Medium Risk
                            </div>

                            <h2 class="fw-bold mb-0 mt-2 text-warning">
                                {{ $mediumRisk }}
                            </h2>
                        </div>

                        <div class="rounded-circle bg-warning-subtle p-3">
                            <i class="bi bi-exclamation-triangle fs-3 text-warning"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        {{-- High Risk --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small text-uppercase fw-semibold">
                                High Risk
                            </div>

                            <h2 class="fw-bold mb-0 mt-2 text-danger">
                                {{ $highRisk }}
                            </h2>
                        </div>

                        <div class="rounded-circle bg-danger-subtle p-3">
                            <i class="bi bi-shield-exclamation fs-3 text-danger"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>


    {{-- Risk Overview --}}
    <div class="row g-4 mb-4">

        {{-- Risk Chart --}}
        <div class="col-lg-5">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-pie-chart text-success me-2"></i>
                        Risk Distribution
                    </h5>
                </div>

                <div class="card-body">

                    @if($forecasts->count() > 0)

                        <div style="height: 280px;">
                            <canvas id="riskChart"></canvas>
                        </div>

                    @else

                        <div class="text-center py-5">

                            <i class="bi bi-bar-chart fs-1 text-muted"></i>

                            <h6 class="mt-3 fw-bold">
                                No forecast data available
                            </h6>

                            <p class="text-muted small mb-0">
                                Generate forecasts for your components to view risk analysis.
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- Decision Support Information --}}
        <div class="col-lg-7">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-lightbulb text-warning me-2"></i>
                        Decision Support Guide
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-4">
                            <div class="p-3 rounded border bg-success-subtle h-100">
                                <div class="fw-bold text-success mb-2">
                                    <i class="bi bi-shield-check me-1"></i>
                                    Low Risk
                                </div>

                                <small class="text-muted">
                                    Continue routine preventive maintenance
                                    and monitoring.
                                </small>
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="p-3 rounded border bg-warning-subtle h-100">
                                <div class="fw-bold text-warning mb-2">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    Medium Risk
                                </div>

                                <small class="text-muted">
                                    Schedule preventive maintenance and
                                    continue close monitoring.
                                </small>
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="p-3 rounded border bg-danger-subtle h-100">
                                <div class="fw-bold text-danger mb-2">
                                    <i class="bi bi-shield-exclamation me-1"></i>
                                    High Risk
                                </div>

                                <small class="text-muted">
                                    Plan for replacement and carry out
                                    detailed inspection.
                                </small>
                            </div>
                        </div>

                    </div>


                    <div class="alert alert-light border mt-4 mb-0">

                        <div class="d-flex">

                            <i class="bi bi-info-circle text-success fs-5 me-2"></i>

                            <div>
                                <strong>Important:</strong>

                                <span class="text-muted">
                                    Replacement forecasts are decision-support
                                    estimates based on component age, condition,
                                    degradation, maintenance history and expected
                                    lifespan. They do not represent an exact
                                    failure date.
                                </span>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Forecast Table --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-bottom">

            <div class="d-flex flex-wrap justify-content-between align-items-center">

                <div>
                    <h5 class="mb-1 fw-bold">
                        <i class="bi bi-list-check text-success me-2"></i>
                        Component Replacement Forecasts
                    </h5>

                    <small class="text-muted">
                        Current condition and replacement planning overview
                    </small>
                </div>

            </div>

        </div>


        <div class="card-body p-0">

            @if($forecasts->count() > 0)

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th class="ps-4">Component</th>
                                <th>Type</th>
                                <th>Age</th>
                                <th>Condition</th>
                                <th>Degradation</th>
                                <th>Remaining Life</th>
                                <th>Risk</th>
                                <th>Replacement Cost</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>

                        </thead>


                        <tbody>

                            @foreach($forecasts as $forecast)

                                @php

                                    $riskClass = match($forecast->risk_level) {
                                        'Low' => 'success',
                                        'Medium' => 'warning',
                                        'High' => 'danger',
                                        default => 'secondary',
                                    };

                                    $conditionClass = match($forecast->current_condition) {
                                        'Excellent' => 'success',
                                        'Good' => 'success',
                                        'Fair' => 'warning',
                                        'Poor' => 'danger',
                                        'Critical' => 'danger',
                                        default => 'secondary',
                                    };

                                @endphp


                                <tr>

                                    {{-- Component --}}
                                    <td class="ps-4">

                                        <div class="fw-semibold">
                                            {{ $forecast->component->name ?? 'Unknown Component' }}
                                        </div>

                                        @if($forecast->component?->serial_number)
                                            <small class="text-muted">
                                                S/N: {{ $forecast->component->serial_number }}
                                            </small>
                                        @endif

                                    </td>


                                    {{-- Type --}}
                                    <td>
                                        <span class="text-muted">
                                            {{ $forecast->component?->componentType?->name ?? 'N/A' }}
                                        </span>
                                    </td>


                                    {{-- Age --}}
                                    <td>

                                        @if($forecast->current_age !== null)

                                            <span class="fw-semibold">
                                                {{ number_format((float) $forecast->current_age, 2) }}
                                            </span>

                                            <small class="text-muted">
                                                yrs
                                            </small>

                                        @else

                                            <span class="text-muted">
                                                N/A
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Condition --}}
                                    <td>

                                        @if($forecast->current_condition)

                                            <span class="badge bg-{{ $conditionClass }}">
                                                {{ $forecast->current_condition }}
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                N/A
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Degradation --}}
                                    <td>

                                        @if($forecast->degradation_rate !== null)

                                            @php
                                                $degradation = (float) $forecast->degradation_rate;

                                                if ($degradation >= 30) {
                                                    $degradationClass = 'danger';
                                                } elseif ($degradation >= 20) {
                                                    $degradationClass = 'danger';
                                                } elseif ($degradation >= 10) {
                                                    $degradationClass = 'warning';
                                                } elseif ($degradation >= 5) {
                                                    $degradationClass = 'warning';
                                                } else {
                                                    $degradationClass = 'success';
                                                }
                                            @endphp

                                            <span class="fw-semibold text-{{ $degradationClass }}">
                                                {{ number_format($degradation, 2) }}%
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                No data
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Remaining Life --}}
                                    <td>

                                        @if($forecast->estimated_remaining_life !== null)

                                            @php
                                                $remainingLife = (float) $forecast->estimated_remaining_life;
                                            @endphp

                                            <span class="fw-semibold">
                                                {{ number_format($remainingLife, 2) }}
                                            </span>

                                            <small class="text-muted">
                                                yrs
                                            </small>

                                        @else

                                            <span class="text-muted">
                                                N/A
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Risk --}}
                                    <td>

                                        <span class="badge bg-{{ $riskClass }}">
                                            {{ $forecast->risk_level }}
                                        </span>

                                    </td>


                                    {{-- Replacement Cost --}}
                                    <td>

                                        @if($forecast->estimated_replacement_cost !== null)

                                            <span class="fw-semibold">
                                                ₦{{ number_format((float) $forecast->estimated_replacement_cost, 2) }}
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                Not available
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Actions --}}
                                    <td class="text-end pe-4">

                                        <div class="btn-group">

                                            <a href="{{ route('replacement-forecasts.show', $forecast) }}"
                                               class="btn btn-sm btn-outline-success"
                                               title="View forecast">

                                                <i class="bi bi-eye"></i>

                                            </a>


                                            <form action="{{ route('replacement-forecasts.refresh', $forecast->component) }}"
                                                  method="POST"
                                                  class="d-inline">

                                                @csrf

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-warning"
                                                        title="Refresh forecast"
                                                        onclick="return confirm('Refresh the replacement forecast for this component?')">

                                                    <i class="bi bi-arrow-clockwise"></i>

                                                </button>

                                            </form>


                                            <form action="{{ route('replacement-forecasts.destroy', $forecast) }}"
                                                  method="POST"
                                                  class="d-inline">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Delete forecast"
                                                        onclick="return confirm('Are you sure you want to delete this forecast?')">

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

                <div class="text-center py-5">

                    <div class="mb-3">
                        <i class="bi bi-graph-up-arrow text-muted"
                           style="font-size: 3rem;"></i>
                    </div>

                    <h5 class="fw-bold">
                        No Replacement Forecasts Yet
                    </h5>

                    <p class="text-muted mb-4">
                        Generate a forecast for one of your active components
                        to begin replacement planning.
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- Components Without Forecast --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-bottom">

            <h5 class="mb-1 fw-bold">
                <i class="bi bi-cpu text-success me-2"></i>
                Components Available for Forecasting
            </h5>

            <small class="text-muted">
                Generate a replacement forecast for components that do not
                currently have a forecast.
            </small>

        </div>


        <div class="card-body">

            @php

                $forecastComponentIds = $forecasts
                    ->pluck('component_id')
                    ->unique();

                $componentsWithoutForecast = $components
                    ->whereNotIn('id', $forecastComponentIds);

            @endphp


            @if($componentsWithoutForecast->count() > 0)

                <div class="row g-3">

                    @foreach($componentsWithoutForecast as $component)

                        <div class="col-xl-4 col-md-6">

                            <div class="border rounded p-3 h-100">

                                <div class="d-flex justify-content-between align-items-start">

                                    <div>

                                        <h6 class="fw-bold mb-1">
                                            {{ $component->name }}
                                        </h6>

                                        <small class="text-muted">
                                            {{ $component->componentType?->name ?? 'Component' }}
                                        </small>

                                    </div>

                                    <span class="badge bg-light text-dark border">
                                        {{ $component->status }}
                                    </span>

                                </div>


                                <div class="row mt-3">

                                    <div class="col-6">

                                        <small class="text-muted d-block">
                                            Condition
                                        </small>

                                        <strong>
                                            {{ $component->current_condition ?? 'N/A' }}
                                        </strong>

                                    </div>


                                    <div class="col-6">

                                        <small class="text-muted d-block">
                                            Lifespan
                                        </small>

                                        <strong>
                                            @if($component->expected_lifespan)
                                                {{ number_format((float) $component->expected_lifespan, 1) }}
                                                yrs
                                            @else
                                                N/A
                                            @endif
                                        </strong>

                                    </div>

                                </div>


                                <div class="mt-3">

                                    <a href="{{ route('replacement-forecasts.generate', $component) }}"
                                       class="btn btn-sm btn-success w-100">

                                        <i class="bi bi-magic me-1"></i>
                                        Generate Forecast

                                    </a>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="text-center py-4">

                    <i class="bi bi-check-circle text-success fs-2"></i>

                    <p class="mb-0 mt-2 text-muted">
                        All available components currently have replacement forecasts.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>


{{-- Chart --}}
@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const canvas = document.getElementById('riskChart');

    if (!canvas) {
        return;
    }

    new Chart(canvas, {

        type: 'doughnut',

        data: {
            labels: [
                'Low Risk',
                'Medium Risk',
                'High Risk'
            ],

            datasets: [{
                data: [
                    {{ $lowRisk }},
                    {{ $mediumRisk }},
                    {{ $highRisk }}
                ],

                backgroundColor: [
                    '#198754',
                    '#ffc107',
                    '#dc3545'
                ],

                borderWidth: 2
            }]
        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {
                    position: 'bottom'
                }

            },

            cutout: '65%'

        }

    });

});

</script>

@endpush

@endsection