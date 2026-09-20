@extends('layouts.app')

@section('title', 'Reports & Analytics')

@section('content')

<style>
    .report-page {
        background: #f5f7fa;
        min-height: calc(100vh - 70px);
        padding: 24px;
    }

    .report-header {
        background: linear-gradient(135deg, #146c43, #198754);
        color: #fff;
        border-radius: 14px;
        padding: 24px 28px;
        margin-bottom: 24px;
        box-shadow: 0 4px 15px rgba(0,0,0,.08);
    }

    .report-header h2 {
        margin: 0;
        font-weight: 700;
    }

    .report-header p {
        margin: 6px 0 0;
        opacity: .9;
    }

    .report-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
        margin-bottom: 24px;
    }

    .report-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #edf0f2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }

    .report-card-header h5 {
        margin: 0;
        font-weight: 700;
        color: #1f2937;
    }

    .report-card-body {
        padding: 20px;
    }

    .stat-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        height: 100%;
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
    }

    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        background: #e8f5ee;
        color: #198754;
        margin-bottom: 12px;
    }

    .stat-value {
        font-size: 25px;
        font-weight: 700;
        color: #1f2937;
    }

    .stat-label {
        color: #6b7280;
        font-size: 14px;
        margin-top: 3px;
    }

    .filter-box {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 18px;
        margin-bottom: 24px;
    }

    .risk-high {
        background: #fdecec;
        color: #b42318;
    }

    .risk-medium {
        background: #fff7df;
        color: #946200;
    }

    .risk-low {
        background: #e8f5ee;
        color: #146c43;
    }

    .risk-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .condition-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .condition-excellent {
        background: #e8f5ee;
        color: #146c43;
    }

    .condition-good {
        background: #edf7ed;
        color: #2f7d32;
    }

    .condition-fair {
        background: #fff7df;
        color: #946200;
    }

    .condition-poor {
        background: #fff0df;
        color: #9a5a00;
    }

    .condition-critical {
        background: #fdecec;
        color: #b42318;
    }

    .report-table th {
        white-space: nowrap;
        font-size: 13px;
        color: #4b5563;
    }

    .report-table td {
        vertical-align: middle;
        font-size: 13px;
    }

    .chart-container {
        position: relative;
        height: 280px;
    }

    .methodology {
        background: #f8faf9;
        border-left: 4px solid #198754;
        padding: 16px;
        border-radius: 6px;
        color: #4b5563;
        font-size: 14px;
        line-height: 1.7;
    }

    @media print {
        .app-sidebar,
        .app-header,
        .filter-box,
        .no-print,
        .report-actions {
            display: none !important;
        }

        .report-page {
            padding: 0;
            background: #fff;
        }

        .report-card,
        .stat-card {
            box-shadow: none;
            break-inside: avoid;
        }

        .report-header {
            box-shadow: none;
        }

        body {
            background: #fff !important;
        }
    }

    @media (max-width: 768px) {
        .report-page {
            padding: 15px;
        }

        .report-header {
            padding: 20px;
        }

        .report-card-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="report-page">

    {{-- Header --}}
    <div class="report-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

            <div>
                <h2>
                    <i class="bi bi-file-earmark-bar-graph me-2"></i>
                    Maintenance & System Reports
                </h2>

                <p>
                    Preventive maintenance, inspection, degradation,
                    cost and replacement decision-support summary.
                </p>
            </div>

            <div class="report-actions no-print">
                <button onclick="window.print()" class="btn btn-warning">
                    <i class="bi bi-printer me-1"></i>
                    Print Report
                </button>
            </div>

        </div>
    </div>


    {{-- Filter --}}
    <div class="filter-box no-print">

        <form method="GET" action="{{ route('reports.index') }}">

            <div class="row align-items-end g-3">

                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Installation
                    </label>

                    <select name="installation_id" class="form-select">

                        <option value="">
                            All Installations
                        </option>

                        @foreach($installations as $installation)

                            <option
                                value="{{ $installation->id }}"
                                {{ (string) $installationId === (string) $installation->id ? 'selected' : '' }}
                            >
                                {{ $installation->name }}
                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-auto">

                    <button class="btn btn-success">
                        <i class="bi bi-funnel me-1"></i>
                        Generate Report
                    </button>

                </div>

                @if($installationId)

                    <div class="col-md-auto">

                        <a
                            href="{{ route('reports.index') }}"
                            class="btn btn-outline-secondary"
                        >
                            <i class="bi bi-x-circle me-1"></i>
                            Clear Filter
                        </a>

                    </div>

                @endif

            </div>

        </form>

    </div>


    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="stat-card">

                <div class="stat-icon">
                    <i class="bi bi-box-seam"></i>
                </div>

                <div class="stat-value">
                    {{ $statistics['total_components'] }}
                </div>

                <div class="stat-label">
                    Total Components
                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="stat-card">

                <div class="stat-icon">
                    <i class="bi bi-clipboard-check"></i>
                </div>

                <div class="stat-value">
                    {{ $statistics['total_inspections'] }}
                </div>

                <div class="stat-label">
                    Total Inspections
                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="stat-card">

                <div class="stat-icon">
                    <i class="bi bi-tools"></i>
                </div>

                <div class="stat-value">
                    {{ $statistics['total_maintenance'] }}
                </div>

                <div class="stat-label">
                    Maintenance Schedules
                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="stat-card">

                <div class="stat-icon">
                    <i class="bi bi-cash-stack"></i>
                </div>

                <div class="stat-value">
                    ₦{{ number_format($statistics['total_cost'], 2) }}
                </div>

                <div class="stat-label">
                    Total Recorded Cost
                </div>

            </div>

        </div>

    </div>


    {{-- Condition + Risk --}}
    <div class="row g-4">

        <div class="col-lg-6">

            <div class="report-card">

                <div class="report-card-header">

                    <h5>
                        <i class="bi bi-heart-pulse me-2 text-success"></i>
                        Component Condition
                    </h5>

                </div>

                <div class="report-card-body">

                    <div class="chart-container">
                        <canvas id="conditionChart"></canvas>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-lg-6">

            <div class="report-card">

                <div class="report-card-header">

                    <h5>
                        <i class="bi bi-graph-up-arrow me-2 text-success"></i>
                        Replacement Risk
                    </h5>

                </div>

                <div class="report-card-body">

                    <div class="chart-container">
                        <canvas id="riskChart"></canvas>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Maintenance Overview --}}
    <div class="report-card">

        <div class="report-card-header">

            <h5>
                <i class="bi bi-calendar-check me-2 text-success"></i>
                Maintenance Overview
            </h5>

        </div>

        <div class="report-card-body">

            <div class="row text-center g-3">

                <div class="col-md-3">

                    <div class="border rounded p-3">

                        <div class="fs-3 fw-bold text-primary">
                            {{ $scheduledMaintenance }}
                        </div>

                        <small class="text-muted">
                            Scheduled
                        </small>

                    </div>

                </div>

                <div class="col-md-3">

                    <div class="border rounded p-3">

                        <div class="fs-3 fw-bold text-warning">
                            {{ $dueMaintenance }}
                        </div>

                        <small class="text-muted">
                            Due Soon
                        </small>

                    </div>

                </div>

                <div class="col-md-3">

                    <div class="border rounded p-3">

                        <div class="fs-3 fw-bold text-danger">
                            {{ $overdueMaintenance }}
                        </div>

                        <small class="text-muted">
                            Overdue
                        </small>

                    </div>

                </div>

                <div class="col-md-3">

                    <div class="border rounded p-3">

                        <div class="fs-3 fw-bold text-success">
                            {{ $completedMaintenance }}
                        </div>

                        <small class="text-muted">
                            Completed
                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Cost Analysis --}}
    <div class="row g-4">

        <div class="col-lg-6">

            <div class="report-card">

                <div class="report-card-header">

                    <h5>
                        <i class="bi bi-wallet2 me-2 text-success"></i>
                        Cost Summary
                    </h5>

                </div>

                <div class="report-card-body">

                    <div class="chart-container">
                        <canvas id="costChart"></canvas>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-lg-6">

            <div class="report-card">

                <div class="report-card-header">

                    <h5>
                        <i class="bi bi-calculator me-2 text-success"></i>
                        Cost Breakdown
                    </h5>

                </div>

                <div class="report-card-body">

                    <div class="table-responsive">

                        <table class="table table-sm">

                            <tbody>

                                <tr>
                                    <td>Maintenance</td>
                                    <td class="text-end fw-semibold">
                                        ₦{{ number_format($maintenanceCost, 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>Repair</td>
                                    <td class="text-end fw-semibold">
                                        ₦{{ number_format($repairCost, 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>Replacement</td>
                                    <td class="text-end fw-semibold">
                                        ₦{{ number_format($replacementCost, 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>Parts / Materials</td>
                                    <td class="text-end fw-semibold">
                                        ₦{{ number_format($partsCost, 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>Labour / Service</td>
                                    <td class="text-end fw-semibold">
                                        ₦{{ number_format($labourCost, 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>Other</td>
                                    <td class="text-end fw-semibold">
                                        ₦{{ number_format($otherCost, 2) }}
                                    </td>
                                </tr>

                                <tr class="table-light">

                                    <th>
                                        Total
                                    </th>

                                    <th class="text-end text-success">
                                        ₦{{ number_format($totalCost, 2) }}
                                    </th>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Replacement Forecast --}}
    <div class="report-card">

        <div class="report-card-header">

            <h5>
                <i class="bi bi-exclamation-triangle me-2 text-warning"></i>
                Replacement Forecast Summary
            </h5>

        </div>

        <div class="report-card-body">

            <div class="table-responsive">

                <table class="table table-hover report-table">

                    <thead>

                        <tr>
                            <th>Component</th>
                            <th>Condition</th>
                            <th>Degradation</th>
                            <th>Remaining Life</th>
                            <th>Risk</th>
                            <th>Recommended Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($replacementForecasts as $forecast)

                            <tr>

                                <td class="fw-semibold">
                                    {{ $forecast->component->name ?? 'N/A' }}
                                </td>

                                <td>

                                    @php
                                        $condition = $forecast->current_condition ?? 'N/A';
                                        $conditionClass = strtolower($condition);
                                    @endphp

                                    @if($condition !== 'N/A')

                                        <span class="condition-badge condition-{{ $conditionClass }}">
                                            {{ $condition }}
                                        </span>

                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif

                                </td>

                                <td>
                                    {{ $forecast->degradation_rate !== null
                                        ? number_format($forecast->degradation_rate, 2) . '%'
                                        : 'N/A' }}
                                </td>

                                <td>
                                    {{ $forecast->estimated_remaining_life !== null
                                        ? number_format($forecast->estimated_remaining_life, 1) . ' years'
                                        : 'N/A' }}
                                </td>

                                <td>

                                    @if($forecast->risk_level === 'High')

                                        <span class="risk-badge risk-high">
                                            High
                                        </span>

                                    @elseif($forecast->risk_level === 'Medium')

                                        <span class="risk-badge risk-medium">
                                            Medium
                                        </span>

                                    @else

                                        <span class="risk-badge risk-low">
                                            Low
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    {{ $forecast->recommended_action }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="text-center text-muted py-4">

                                    No replacement forecasts have been generated yet.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Recent Inspections --}}
    <div class="report-card">

        <div class="report-card-header">

            <h5>
                <i class="bi bi-clipboard-data me-2 text-success"></i>
                Recent Inspection Records
            </h5>

        </div>

        <div class="report-card-body">

            <div class="table-responsive">

                <table class="table table-hover report-table">

                    <thead>

                        <tr>
                            <th>Date</th>
                            <th>Installation</th>
                            <th>Inspector</th>
                            <th>Condition</th>
                            <th>Next Inspection</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($recentInspections as $inspection)

                            <tr>

                                <td>
                                    {{ \Carbon\Carbon::parse($inspection->inspection_date)->format('d M Y') }}
                                </td>

                                <td>
                                    {{ $inspection->installation->name ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $inspection->inspector->name ?? 'N/A' }}
                                </td>

                                <td>

                                    @php
                                        $condition = $inspection->overall_condition ?? 'N/A';
                                        $conditionClass = strtolower($condition);
                                    @endphp

                                    @if($condition !== 'N/A')

                                        <span class="condition-badge condition-{{ $conditionClass }}">
                                            {{ $condition }}
                                        </span>

                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif

                                </td>

                                <td>

                                    @if($inspection->next_inspection_date)

                                        {{ \Carbon\Carbon::parse($inspection->next_inspection_date)->format('d M Y') }}

                                    @else

                                        <span class="text-muted">
                                            Not scheduled
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="text-center text-muted py-4">

                                    No inspection records found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Components --}}
    <div class="report-card">

        <div class="report-card-header">

            <h5>
                <i class="bi bi-cpu me-2 text-success"></i>
                Installed Components
            </h5>

            <span class="badge bg-success">
                {{ $components->count() }} Components
            </span>

        </div>

        <div class="report-card-body">

            <div class="table-responsive">

                <table class="table table-hover report-table">

                    <thead>

                        <tr>
                            <th>Component</th>
                            <th>Type</th>
                            <th>Manufacturer</th>
                            <th>Model</th>
                            <th>Status</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($components as $component)

                            <tr>

                                <td class="fw-semibold">
                                    {{ $component->name }}
                                </td>

                                <td>
                                    {{ $component->componentType->name ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $component->manufacturer ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $component->model ?? 'N/A' }}
                                </td>

                                <td>

                                    @if(($component->status ?? '') === 'Active')

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                    @elseif(($component->status ?? '') === 'Replaced')

                                        <span class="badge bg-secondary">
                                            Replaced
                                        </span>

                                    @else

                                        <span class="badge bg-warning text-dark">
                                            {{ $component->status ?? 'N/A' }}
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="text-center text-muted py-4">

                                    No components found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Methodology --}}
    <div class="report-card">

        <div class="report-card-header">

            <h5>
                <i class="bi bi-info-circle me-2 text-success"></i>
                Report Interpretation
            </h5>

        </div>

        <div class="report-card-body">

            <div class="methodology">

                <strong>Purpose of this report:</strong>

                This report provides a consolidated view of the
                preventive maintenance condition of the solar-battery
                installation.

                <br><br>

                <strong>Degradation:</strong>

                Performance degradation is calculated from available
                reference and current measurement values. A higher
                degradation percentage indicates greater loss relative
                to the reference value.

                <br><br>

                <strong>Replacement risk:</strong>

                Replacement forecasts are decision-support estimates
                based on component age, condition, degradation,
                maintenance history and expected lifespan. They are
                intended to support maintenance planning and do not
                represent an exact failure date.

                <br><br>

                <strong>Cost analysis:</strong>

                Recorded costs are grouped according to the maintenance,
                repair, replacement, parts/materials, labour/service and
                other categories stored in the system.

            </div>

        </div>

    </div>


</div>


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Component Condition Chart
    |--------------------------------------------------------------------------
    */

    const conditionCanvas =
        document.getElementById('conditionChart');

    if (conditionCanvas) {

        new Chart(conditionCanvas, {

            type: 'doughnut',

            data: {

                labels: [
                    'Excellent',
                    'Good',
                    'Fair',
                    'Poor',
                    'Critical'
                ],

                datasets: [{
                    data: [
                        {{ $conditionData['Excellent'] }},
                        {{ $conditionData['Good'] }},
                        {{ $conditionData['Fair'] }},
                        {{ $conditionData['Poor'] }},
                        {{ $conditionData['Critical'] }}
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

                }

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Replacement Risk Chart
    |--------------------------------------------------------------------------
    */

    const riskCanvas =
        document.getElementById('riskChart');

    if (riskCanvas) {

        new Chart(riskCanvas, {

            type: 'doughnut',

            data: {

                labels: [
                    'Low',
                    'Medium',
                    'High'
                ],

                datasets: [{

                    data: [
                        {{ $riskData['Low'] }},
                        {{ $riskData['Medium'] }},
                        {{ $riskData['High'] }}
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

                }

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Cost Chart
    |--------------------------------------------------------------------------
    */

    const costCanvas =
        document.getElementById('costChart');

    if (costCanvas) {

        new Chart(costCanvas, {

            type: 'bar',

            data: {

                labels: [
                    'Maintenance',
                    'Repair',
                    'Replacement',
                    'Parts',
                    'Labour',
                    'Other'
                ],

                datasets: [{

                    label: 'Cost (₦)',

                    data: [
                        {{ $maintenanceCost }},
                        {{ $repairCost }},
                        {{ $replacementCost }},
                        {{ $partsCost }},
                        {{ $labourCost }},
                        {{ $otherCost }}
                    ],

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

});

</script>

@endpush

@endsection