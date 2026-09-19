<x-app-layout>

<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h3 mb-1">
                <i class="bi bi-graph-down-arrow me-2"></i>
                Degradation Analysis
            </h1>

            <p class="text-muted mb-0">
                Monitor component performance, degradation trends and condition.
            </p>
        </div>

    </div>


    {{-- SUMMARY CARDS --}}
    <div class="row g-3 mb-4">

        {{-- NORMAL --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 status-card normal-card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small">
                                Normal
                            </div>

                            <h3 class="mb-0 text-success">
                                {{ $normalCount }}
                            </h3>

                            <small class="text-muted">
                                Components
                            </small>
                        </div>

                        <i class="bi bi-check-circle-fill fs-2 text-success"></i>

                    </div>

                </div>
            </div>
        </div>


        {{-- MONITOR --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 status-card monitor-card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small">
                                Monitor
                            </div>

                            <h3 class="mb-0 text-warning">
                                {{ $monitorCount }}
                            </h3>

                            <small class="text-muted">
                                Components
                            </small>
                        </div>

                        <i class="bi bi-eye-fill fs-2 text-warning"></i>

                    </div>

                </div>
            </div>
        </div>


        {{-- ATTENTION --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 status-card attention-card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small">
                                Attention
                            </div>

                            <h3 class="mb-0 text-orange">
                                {{ $attentionCount }}
                            </h3>

                            <small class="text-muted">
                                Components
                            </small>
                        </div>

                        <i class="bi bi-exclamation-triangle-fill fs-2 text-warning"></i>

                    </div>

                </div>
            </div>
        </div>


        {{-- CRITICAL --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 status-card critical-card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small">
                                Critical
                            </div>

                            <h3 class="mb-0 text-danger">
                                {{ $criticalCount }}
                            </h3>

                            <small class="text-muted">
                                Components
                            </small>
                        </div>

                        <i class="bi bi-x-octagon-fill fs-2 text-danger"></i>

                    </div>

                </div>
            </div>
        </div>

    </div>


    {{-- COMPONENT SELECTOR --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                <i class="bi bi-search me-2"></i>
                Select Component
            </h5>

        </div>

        <div class="card-body">

            <form method="GET" action="{{ route('degradation.index') }}">

                <div class="row g-3 align-items-end">

                    <div class="col-md-9">

                        <label class="form-label">
                            Component
                        </label>

                        <select
                            name="component_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                -- Select Component --
                            </option>

                            @foreach($components as $component)

                                <option
                                    value="{{ $component->id }}"
                                    @selected(
                                        $selectedComponent &&
                                        $selectedComponent->id == $component->id
                                    )
                                >

                                    {{ $component->name }}

                                    @if($component->componentType)
                                        — {{ $component->componentType->name }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-3">

                        <button class="btn btn-success w-100">

                            <i class="bi bi-bar-chart-line me-1"></i>
                            Analyse Component

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- COMPONENT COMPARISON CHART --}}
    @if(count($comparisonData) > 0)

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    <i class="bi bi-bar-chart-fill me-2"></i>
                    Component Performance Loss Comparison
                </h5>

            </div>

            <div class="card-body">

                <div style="height: 320px;">
                    <canvas id="componentComparisonChart"></canvas>
                </div>

            </div>

        </div>

    @endif


    {{-- SELECTED COMPONENT --}}
    @if($selectedComponent)

        {{-- COMPONENT INFORMATION --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-success text-white">

                <h5 class="mb-0">

                    <i class="bi bi-cpu me-2"></i>

                    {{ $selectedComponent->name }}

                </h5>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-3">

                        <small class="text-muted">
                            Component Type
                        </small>

                        <div class="fw-semibold">

                            {{ $selectedComponent->componentType->name ?? 'N/A' }}

                        </div>

                    </div>


                    <div class="col-md-3">

                        <small class="text-muted">
                            Installation
                        </small>

                        <div class="fw-semibold">

                            {{ $selectedComponent->installation->name ?? 'N/A' }}

                        </div>

                    </div>


                    <div class="col-md-3">

                        <small class="text-muted">
                            Manufacturer
                        </small>

                        <div class="fw-semibold">

                            {{ $selectedComponent->manufacturer ?? 'N/A' }}

                        </div>

                    </div>


                    <div class="col-md-3">

                        <small class="text-muted">
                            Current Condition
                        </small>

                        <div>

                            @php

                                $conditionClass = match(
                                    $selectedComponent->current_condition
                                ) {

                                    'Excellent' => 'success',
                                    'Good' => 'success',
                                    'Fair' => 'warning',
                                    'Poor' => 'danger',
                                    'Critical' => 'danger',

                                    default => 'secondary'
                                };

                            @endphp

                            <span class="badge bg-{{ $conditionClass }}">

                                {{ $selectedComponent->current_condition }}

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- PERFORMANCE ANALYSIS --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">

                    <i class="bi bi-speedometer2 me-2"></i>

                    Performance Analysis

                </h5>

            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>Parameter</th>
                                <th>Latest Value</th>
                                <th>Reference</th>
                                <th>Performance Loss</th>
                                <th>Performance</th>
                                <th>Trend</th>
                                <th>Status</th>

                            </tr>

                        </thead>

                        <tbody>

                        @forelse($parameterAnalysis as $analysis)

                            <tr>

                                <td>

                                    <strong>
                                        {{ $analysis['parameter'] }}
                                    </strong>

                                    @if(!$analysis['is_performance_parameter'])

                                        <br>

                                        <small class="text-muted">
                                            Monitoring parameter
                                        </small>

                                    @endif

                                </td>


                                <td>

                                    {{ $analysis['latest']->value ?? 'N/A' }}

                                    {{ $analysis['latest']->unit ?? '' }}

                                </td>


                                <td>

                                    @if($analysis['reference'] !== null)

                                        {{ number_format(
                                            $analysis['reference'],
                                            2
                                        ) }}

                                        {{ $analysis['latest']->unit ?? '' }}

                                    @else

                                        <span class="text-muted">
                                            Not available
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @if($analysis['degradation'] !== null)

                                        <strong>
                                            {{ number_format(
                                                $analysis['degradation'],
                                                2
                                            ) }}%
                                        </strong>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @if($analysis['performance'] !== null)

                                        {{ number_format(
                                            $analysis['performance'],
                                            2
                                        ) }}%

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @if($analysis['trend'] === 'Declining')

                                        <span class="badge bg-danger">

                                            <i class="bi bi-arrow-down"></i>
                                            Declining

                                        </span>

                                    @elseif($analysis['trend'] === 'Improving')

                                        <span class="badge bg-success">

                                            <i class="bi bi-arrow-up"></i>
                                            Improving

                                        </span>

                                    @elseif($analysis['trend'] === 'Stable')

                                        <span class="badge bg-success">

                                            <i class="bi bi-dash"></i>
                                            Stable

                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            Insufficient Data
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @php

                                        $statusClass = match(
                                            $analysis['status']
                                        ) {

                                            'Normal' => 'success',
                                            'Monitor' => 'warning',
                                            'Attention' => 'warning',
                                            'Critical' => 'danger',
                                            'No Reference' => 'secondary',
                                            'Monitoring Only' => 'info',

                                            default => 'secondary'
                                        };

                                    @endphp

                                    <span class="badge bg-{{ $statusClass }}">

                                        {{ $analysis['status'] }}

                                    </span>

                                </td>

                            </tr>


                            <tr class="table-light">

                                <td colspan="7">

                                    <small class="text-muted">

                                        <i class="bi bi-info-circle me-1"></i>

                                        {{ $analysis['interpretation'] }}

                                    </small>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-5"
                                >

                                    <i class="bi bi-bar-chart fs-1 text-muted"></i>

                                    <p class="text-muted mt-2 mb-0">

                                        No measurement data available for this component.

                                    </p>

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- TREND CHARTS --}}
        @php

            $performanceAnalyses = collect($parameterAnalysis)
                ->where('is_performance_parameter', true)
                ->filter(function ($analysis) {
                    return count($analysis['chart_data']) >= 2;
                });

        @endphp


        @if($performanceAnalyses->count() > 0)

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        <i class="bi bi-graph-up-arrow me-2"></i>

                        Performance Trend Analysis

                    </h5>

                </div>

                <div class="card-body">

                    <p class="text-muted small">

                        The chart shows how the selected performance parameter
                        has changed across recorded measurements.

                    </p>


                    <div class="row g-4">

                        @foreach($performanceAnalyses as $index => $analysis)

                            <div class="col-12">

                                <div class="border rounded p-3">

                                    <div class="d-flex justify-content-between align-items-center mb-3">

                                        <div>

                                            <h6 class="fw-bold mb-1">

                                                {{ $analysis['parameter'] }}

                                            </h6>

                                            <small class="text-muted">

                                                Unit:
                                                {{ $analysis['latest']->unit ?? 'N/A' }}

                                            </small>

                                        </div>


                                        @if($analysis['trend'] === 'Declining')

                                            <span class="badge bg-danger">
                                                <i class="bi bi-arrow-down me-1"></i>
                                                Declining
                                            </span>

                                        @elseif($analysis['trend'] === 'Improving')

                                            <span class="badge bg-success">
                                                <i class="bi bi-arrow-up me-1"></i>
                                                Improving
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                {{ $analysis['trend'] }}
                                            </span>

                                        @endif

                                    </div>


                                    <div style="height: 300px;">

                                        <canvas
                                            id="trendChart{{ $index }}"
                                        ></canvas>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            </div>

        @endif


        {{-- MEASUREMENT HISTORY --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">

                    <i class="bi bi-clock-history me-2"></i>

                    Measurement History

                </h5>

            </div>

            <div class="card-body">

                @forelse($parameterAnalysis as $analysis)

                    <div class="mb-4">

                        <h6 class="fw-bold">

                            {{ $analysis['parameter'] }}

                        </h6>

                        <div class="table-responsive">

                            <table class="table table-sm table-bordered">

                                <thead class="table-light">

                                    <tr>

                                        <th>Date</th>
                                        <th>Value</th>
                                        <th>Unit</th>
                                        <th>Reference</th>
                                        <th>Remarks</th>

                                    </tr>

                                </thead>

                                <tbody>

                                @foreach($analysis['history'] as $measurement)

                                    <tr>

                                        <td>

                                            {{ $measurement->measurement_date?->format('d M Y') }}

                                        </td>

                                        <td>

                                            {{ $measurement->value }}

                                        </td>

                                        <td>

                                            {{ $measurement->unit }}

                                        </td>

                                        <td>

                                            {{ $measurement->reference_value ?? '—' }}

                                        </td>

                                        <td>

                                            {{ $measurement->remarks ?? '—' }}

                                        </td>

                                    </tr>

                                @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                @empty

                    <div class="text-center text-muted py-4">

                        No historical measurements available.

                    </div>

                @endforelse

            </div>

        </div>

    @endif


    {{-- COMPONENT SUMMARY --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">

                <i class="bi bi-grid-3x3-gap me-2"></i>

                Component Degradation Summary

            </h5>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>Component</th>
                            <th>Type</th>
                            <th>Average Performance Loss</th>
                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($componentSummaries as $summary)

                        <tr>

                            <td>

                                <strong>
                                    {{ $summary['component']->name }}
                                </strong>

                            </td>

                            <td>

                                {{ $summary['component']->componentType->name ?? 'N/A' }}

                            </td>

                            <td>

                                @if($summary['degradation'] !== null)

                                    {{ number_format(
                                        $summary['degradation'],
                                        2
                                    ) }}%

                                @else

                                    <span class="text-muted">
                                        No performance data
                                    </span>

                                @endif

                            </td>

                            <td>

                                @php

                                    $summaryClass = match(
                                        $summary['status']
                                    ) {

                                        'Normal' => 'success',
                                        'Monitor' => 'warning',
                                        'Attention' => 'warning',
                                        'Critical' => 'danger',

                                        default => 'secondary'

                                    };

                                @endphp

                                <span class="badge bg-{{ $summaryClass }}">

                                    {{ $summary['status'] }}

                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="4"
                                class="text-center text-muted py-4"
                            >

                                No components available.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- METHODOLOGY --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">

                <i class="bi bi-info-circle me-2"></i>

                Analysis Methodology

            </h5>

        </div>

        <div class="card-body">

            <p>

                The system estimates performance loss using the difference
                between the measured value and an appropriate reference value.

            </p>

            <div class="bg-light rounded p-3 mb-3">

                <strong>
                    Performance Loss (%)
                </strong>

                <div class="mt-2">

                    <code>

                        ((Reference Value − Current Value)
                        ÷ Reference Value) × 100

                    </code>

                </div>

            </div>


            <div class="row g-3">

                <div class="col-md-6">

                    <h6 class="fw-bold">
                        Performance Parameters
                    </h6>

                    <p class="text-muted small mb-0">

                        Capacity, power and energy-related measurements
                        may be evaluated for performance loss when a
                        meaningful reference value is available.

                    </p>

                </div>


                <div class="col-md-6">

                    <h6 class="fw-bold">
                        Monitoring Parameters
                    </h6>

                    <p class="text-muted small mb-0">

                        Voltage, current and temperature are monitored
                        because their values can vary according to
                        operating and environmental conditions.

                    </p>

                </div>

            </div>


            <hr>


            <div class="small text-muted">

                <strong>
                    Interpretation thresholds:
                </strong>

                <ul class="mb-0 mt-2">

                    <li>
                        <strong>0–5%:</strong>
                        Normal
                    </li>

                    <li>
                        <strong>&gt;5–15%:</strong>
                        Monitor
                    </li>

                    <li>
                        <strong>&gt;15–30%:</strong>
                        Attention
                    </li>

                    <li>
                        <strong>&gt;30%:</strong>
                        Critical
                    </li>

                </ul>


                <p class="mt-3 mb-0">

                    These values are decision-support thresholds for this
                    project and should not be interpreted as a guaranteed
                    physical failure point. Environmental conditions,
                    operating conditions, measurement quality and component
                    specifications should also be considered.

                </p>

            </div>

        </div>

    </div>

</div>


{{-- CHART.JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Component Comparison Chart
    |--------------------------------------------------------------------------
    */

    const comparisonCanvas =
        document.getElementById('componentComparisonChart');

    if (comparisonCanvas) {

        const comparisonData =
            @json($comparisonData);

        const labels =
            comparisonData.map(item => item.name);

        const values =
            comparisonData.map(item => item.degradation);

        const backgroundColors =
            comparisonData.map(item => {

                if (item.status === 'Normal') {
                    return '#198754';
                }

                if (item.status === 'Monitor') {
                    return '#ffc107';
                }

                if (item.status === 'Attention') {
                    return '#fd7e14';
                }

                if (item.status === 'Critical') {
                    return '#dc3545';
                }

                return '#6c757d';
            });

        new Chart(comparisonCanvas, {

            type: 'bar',

            data: {

                labels: labels,

                datasets: [{

                    label: 'Average Performance Loss (%)',

                    data: values,

                    backgroundColor: backgroundColors,

                    borderWidth: 1,

                    borderRadius: 6

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                scales: {

                    y: {

                        beginAtZero: true,

                        suggestedMax: 100,

                        title: {

                            display: true,

                            text: 'Performance Loss (%)'

                        }

                    },

                    x: {

                        title: {

                            display: true,

                            text: 'Component'

                        }

                    }

                },

                plugins: {

                    legend: {

                        display: false

                    },

                    tooltip: {

                        callbacks: {

                            label: function(context) {

                                return context.parsed.y.toFixed(2) + '%';

                            }

                        }

                    }

                }

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Selected Component Trend Charts
    |--------------------------------------------------------------------------
    */

    const trendAnalyses =
        @json(
            collect($parameterAnalysis)
                ->where('is_performance_parameter', true)
                ->filter(function ($analysis) {
                    return count($analysis['chart_data']) >= 2;
                })
                ->values()
        );


    trendAnalyses.forEach(function (analysis, index) {

        const canvas =
            document.getElementById('trendChart' + index);

        if (!canvas) {
            return;
        }

        const chartData =
            analysis.chart_data;

        const labels =
            chartData.map(item => item.date);

        const values =
            chartData.map(item => item.value);

        const referenceValues =
            chartData.map(item => item.reference);


        new Chart(canvas, {

            type: 'line',

            data: {

                labels: labels,

                datasets: [

                    {

                        label: analysis.parameter,

                        data: values,

                        tension: 0.3,

                        fill: false,

                        pointRadius: 5,

                        pointHoverRadius: 7

                    },

                    {

                        label: 'Reference Value',

                        data: referenceValues,

                        borderDash: [6, 6],

                        tension: 0,

                        fill: false,

                        pointRadius: 0

                    }

                ]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                interaction: {

                    intersect: false,

                    mode: 'index'

                },

                scales: {

                    y: {

                        beginAtZero: false,

                        title: {

                            display: true,

                            text: analysis.latest?.unit
                                ? 'Value (' + analysis.latest.unit + ')'
                                : 'Value'

                        }

                    },

                    x: {

                        title: {

                            display: true,

                            text: 'Measurement Date'

                        }

                    }

                },

                plugins: {

                    legend: {

                        display: true

                    },

                    tooltip: {

                        callbacks: {

                            label: function(context) {

                                const value =
                                    context.parsed.y;

                                return context.dataset.label
                                    + ': '
                                    + value;

                            }

                        }

                    }

                }

            }

        });

    });

});

</script>


<style>

.text-orange {
    color: #fd7e14 !important;
}

.status-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.status-card:hover {
    transform: translateY(-3px);
}

.normal-card {
    border-left: 4px solid #198754 !important;
}

.monitor-card {
    border-left: 4px solid #ffc107 !important;
}

.attention-card {
    border-left: 4px solid #fd7e14 !important;
}

.critical-card {
    border-left: 4px solid #dc3545 !important;
}

</style>

</x-app-layout>