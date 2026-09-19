<x-app-layout>

    <x-slot name="header">

        <div class="d-flex justify-content-between align-items-center">

            <div>
                <h4 class="fw-bold mb-1">
                    <i class="bi bi-speedometer2 me-2 text-success"></i>
                    Solar Maintenance Dashboard
                </h4>

                <p class="text-muted mb-0">
                    Preventive maintenance and replacement decision support
                </p>
            </div>

            <div>
                <span class="badge bg-success px-3 py-2">
                    <i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i>
                    System Operational
                </span>
            </div>

        </div>

    </x-slot>


    <div class="container-fluid py-3">

        {{-- Success Message --}}
        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show shadow-sm"
                 role="alert">

                <i class="bi bi-check-circle me-2"></i>

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- SUMMARY CARDS --}}
        {{-- ========================================================= --}}

        <div class="row g-3 mb-4">


            {{-- Total Installations --}}
            <div class="col-lg-3 col-md-6 col-sm-12">

                <div class="small-box text-bg-primary shadow-sm">

                    <div class="inner">

                        <h3>{{ $totalInstallations }}</h3>

                        <p>Total Installations</p>

                    </div>

                    <div class="icon">
                        <i class="bi bi-building"></i>
                    </div>

                    <a href="{{ route('installations.index') }}"
                       class="small-box-footer">

                        View Installations
                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </div>


            {{-- Total Components --}}
            <div class="col-lg-3 col-md-6 col-sm-12">

                <div class="small-box text-bg-success shadow-sm">

                    <div class="inner">

                        <h3>{{ $totalComponents }}</h3>

                        <p>Total Components</p>

                    </div>

                    <div class="icon">
                        <i class="bi bi-cpu"></i>
                    </div>

                    <a href="{{ route('components.index') }}"
                       class="small-box-footer">

                        View Components
                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </div>


            {{-- Pending Inspections --}}
            <div class="col-lg-3 col-md-6 col-sm-12">

                <div class="small-box text-bg-warning shadow-sm">

                    <div class="inner">

                        <h3>{{ $pendingInspections }}</h3>

                        <p>Pending Inspections</p>

                    </div>

                    <div class="icon">
                        <i class="bi bi-clipboard-check"></i>
                    </div>

                    <a href="{{ route('inspections.index') }}"
                       class="small-box-footer">

                        View Inspections
                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </div>


            {{-- Maintenance Due --}}
            <div class="col-lg-3 col-md-6 col-sm-12">

                <div class="small-box text-bg-danger shadow-sm">

                    <div class="inner">

                        <h3>{{ $maintenanceDue }}</h3>

                        <p>Maintenance Due</p>

                    </div>

                    <div class="icon">
                        <i class="bi bi-tools"></i>
                    </div>

                    <a href="{{ route('maintenance-schedules.index') }}"
                       class="small-box-footer">

                        View Maintenance
                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- MAIN DASHBOARD ROW --}}
        {{-- ========================================================= --}}

        <div class="row g-4">


            {{-- ===================================================== --}}
            {{-- COMPONENT CONDITION --}}
            {{-- ===================================================== --}}

            <div class="col-lg-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-header bg-white border-bottom py-3">

                        <h5 class="fw-bold mb-0">

                            <i class="bi bi-bar-chart-line me-2 text-success"></i>

                            Component Condition

                        </h5>

                    </div>


                    <div class="card-body">

                        <div style="height: 330px;">

                            <canvas id="conditionChart"></canvas>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- MAINTENANCE STATUS --}}
            {{-- ===================================================== --}}

            <div class="col-lg-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-header bg-white border-bottom py-3">

                        <h5 class="fw-bold mb-0">

                            <i class="bi bi-calendar-check me-2 text-success"></i>

                            Maintenance Status

                        </h5>

                    </div>


                    <div class="card-body">

                        <div style="height: 330px;">

                            <canvas id="maintenanceChart"></canvas>

                        </div>

                    </div>

                </div>

            </div>


        </div>


        {{-- ========================================================= --}}
        {{-- SECOND DASHBOARD ROW --}}
        {{-- ========================================================= --}}

        <div class="row g-4 mt-1">


            {{-- ===================================================== --}}
            {{-- REPLACEMENT RISK --}}
            {{-- ===================================================== --}}

            <div class="col-lg-5">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-header bg-white border-bottom py-3">

                        <h5 class="fw-bold mb-0">

                            <i class="bi bi-shield-exclamation me-2 text-warning"></i>

                            Replacement Risk

                        </h5>

                    </div>


                    <div class="card-body">

                        <div style="height: 280px;">

                            <canvas id="riskChart"></canvas>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- MAINTENANCE OVERVIEW --}}
            {{-- ===================================================== --}}

            <div class="col-lg-7">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-header bg-white border-bottom py-3">

                        <h5 class="fw-bold mb-0">

                            <i class="bi bi-calendar3 me-2 text-success"></i>

                            Maintenance Overview

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">


                            {{-- Total --}}
                            <div class="col-md-6">

                                <div class="border rounded p-3 h-100">

                                    <div class="d-flex justify-content-between">

                                        <div>

                                            <small class="text-muted">
                                                Total Schedules
                                            </small>

                                            <h3 class="fw-bold mb-0">
                                                {{ $totalMaintenance }}
                                            </h3>

                                        </div>

                                        <i class="bi bi-calendar-event fs-3 text-success"></i>

                                    </div>

                                </div>

                            </div>


                            {{-- Scheduled --}}
                            <div class="col-md-6">

                                <div class="border rounded p-3 h-100">

                                    <div class="d-flex justify-content-between">

                                        <div>

                                            <small class="text-muted">
                                                Scheduled
                                            </small>

                                            <h3 class="fw-bold mb-0 text-primary">
                                                {{ $scheduledMaintenance }}
                                            </h3>

                                        </div>

                                        <i class="bi bi-calendar-check fs-3 text-primary"></i>

                                    </div>

                                </div>

                            </div>


                            {{-- Due --}}
                            <div class="col-md-6">

                                <div class="border rounded p-3 h-100">

                                    <div class="d-flex justify-content-between">

                                        <div>

                                            <small class="text-muted">
                                                Due / Overdue
                                            </small>

                                            <h3 class="fw-bold mb-0 text-danger">
                                                {{ $dueMaintenance }}
                                            </h3>

                                        </div>

                                        <i class="bi bi-exclamation-triangle fs-3 text-danger"></i>

                                    </div>

                                </div>

                            </div>


                            {{-- Upcoming --}}
                            <div class="col-md-6">

                                <div class="border rounded p-3 h-100">

                                    <div class="d-flex justify-content-between">

                                        <div>

                                            <small class="text-muted">
                                                Upcoming
                                            </small>

                                            <h3 class="fw-bold mb-0 text-success">
                                                {{ $scheduledMaintenance }}
                                            </h3>

                                        </div>

                                        <i class="bi bi-calendar2-week fs-3 text-success"></i>

                                    </div>

                                </div>

                            </div>


                        </div>


                        <div class="mt-4">

                            <a href="{{ route('maintenance-schedules.index') }}"
                               class="btn btn-success">

                                <i class="bi bi-calendar-check me-1"></i>

                                Manage Maintenance Schedules

                            </a>

                        </div>

                    </div>

                </div>

            </div>


        </div>


        {{-- ========================================================= --}}
        {{-- RECENT INSPECTIONS --}}
        {{-- ========================================================= --}}

        <div class="row mt-4">

            <div class="col-12">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white border-bottom py-3">

                        <div class="d-flex justify-content-between align-items-center">

                            <h5 class="fw-bold mb-0">

                                <i class="bi bi-clipboard-data me-2 text-success"></i>

                                Recent Inspections

                            </h5>

                            <a href="{{ route('inspections.index') }}"
                               class="btn btn-sm btn-outline-success">

                                View All

                            </a>

                        </div>

                    </div>


                    <div class="card-body p-0">

                        @if($recentInspections->count())

                            <div class="table-responsive">

                                <table class="table table-hover align-middle mb-0">

                                    <thead class="table-light">

                                        <tr>

                                            <th>#</th>

                                            <th>Installation</th>

                                            <th>Inspector</th>

                                            <th>Inspection Date</th>

                                            <th>Condition</th>

                                            <th>Next Inspection</th>

                                        </tr>

                                    </thead>


                                    <tbody>

                                        @foreach($recentInspections as $inspection)

                                            <tr>

                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>


                                                <td>

                                                    <span class="fw-semibold">

                                                        {{ $inspection->installation->name ?? 'N/A' }}

                                                    </span>

                                                </td>


                                                <td>

                                                    {{ $inspection->inspector->name ?? 'N/A' }}

                                                </td>


                                                <td>

                                                    @if($inspection->inspection_date)

                                                        {{ $inspection->inspection_date->format('d M Y') }}

                                                    @else

                                                        N/A

                                                    @endif

                                                </td>


                                                <td>

                                                    @switch($inspection->overall_condition)

                                                        @case('Excellent')

                                                            <span class="badge bg-success">
                                                                Excellent
                                                            </span>

                                                            @break

                                                        @case('Good')

                                                            <span class="badge bg-primary">
                                                                Good
                                                            </span>

                                                            @break

                                                        @case('Fair')

                                                            <span class="badge bg-warning text-dark">
                                                                Fair
                                                            </span>

                                                            @break

                                                        @case('Poor')

                                                            <span class="badge bg-danger">
                                                                Poor
                                                            </span>

                                                            @break

                                                        @case('Critical')

                                                            <span class="badge bg-dark">
                                                                Critical
                                                            </span>

                                                            @break

                                                        @default

                                                            <span class="badge bg-secondary">
                                                                N/A
                                                            </span>

                                                    @endswitch

                                                </td>


                                                <td>

                                                    @if($inspection->next_inspection_date)

                                                        {{ $inspection->next_inspection_date->format('d M Y') }}

                                                    @else

                                                        N/A

                                                    @endif

                                                </td>

                                            </tr>

                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                        @else

                            <div class="text-center py-5">

                                <i class="bi bi-clipboard-x text-muted"
                                   style="font-size: 50px;"></i>

                                <h6 class="fw-bold mt-3">
                                    No inspections recorded
                                </h6>

                                <p class="text-muted mb-3">
                                    Inspection records will appear here once they are created.
                                </p>

                                <a href="{{ route('inspections.create') }}"
                                   class="btn btn-success">

                                    <i class="bi bi-plus-circle me-1"></i>

                                    Create Inspection

                                </a>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- QUICK ACTIONS --}}
        {{-- ========================================================= --}}

        <div class="row g-3 mt-4 mb-3">

            <div class="col-md-3">

                <a href="{{ route('installations.create') }}"
                   class="text-decoration-none">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body text-center py-4">

                            <i class="bi bi-building-add text-primary fs-2"></i>

                            <h6 class="fw-bold mt-2 mb-1">
                                Add Installation
                            </h6>

                            <small class="text-muted">
                                Register a solar installation
                            </small>

                        </div>

                    </div>

                </a>

            </div>


            <div class="col-md-3">

                <a href="{{ route('components.create') }}"
                   class="text-decoration-none">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body text-center py-4">

                            <i class="bi bi-cpu text-success fs-2"></i>

                            <h6 class="fw-bold mt-2 mb-1">
                                Register Component
                            </h6>

                            <small class="text-muted">
                                Add a system component
                            </small>

                        </div>

                    </div>

                </a>

            </div>


            <div class="col-md-3">

                <a href="{{ route('inspections.create') }}"
                   class="text-decoration-none">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body text-center py-4">

                            <i class="bi bi-clipboard-plus text-warning fs-2"></i>

                            <h6 class="fw-bold mt-2 mb-1">
                                New Inspection
                            </h6>

                            <small class="text-muted">
                                Record a system inspection
                            </small>

                        </div>

                    </div>

                </a>

            </div>


            <div class="col-md-3">

                <a href="{{ route('maintenance-schedules.create') }}"
                   class="text-decoration-none">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body text-center py-4">

                            <i class="bi bi-calendar-plus text-danger fs-2"></i>

                            <h6 class="fw-bold mt-2 mb-1">
                                Schedule Maintenance
                            </h6>

                            <small class="text-muted">
                                Plan preventive maintenance
                            </small>

                        </div>

                    </div>

                </a>

            </div>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- CHART.JS --}}
    {{-- ============================================================= --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>

        document.addEventListener('DOMContentLoaded', function () {


            /* ========================================================
               COMPONENT CONDITION CHART
            ======================================================== */

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
                                {{ $conditionData['Excellent'] ?? 0 }},
                                {{ $conditionData['Good'] ?? 0 }},
                                {{ $conditionData['Fair'] ?? 0 }},
                                {{ $conditionData['Poor'] ?? 0 }},
                                {{ $conditionData['Critical'] ?? 0 }}
                            ],

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


            /* ========================================================
               MAINTENANCE STATUS CHART
            ======================================================== */

            const maintenanceCanvas =
                document.getElementById('maintenanceChart');

            if (maintenanceCanvas) {

                new Chart(maintenanceCanvas, {

                    type: 'bar',

                    data: {

                        labels: [
                            'Scheduled',
                            'Due',
                            'Completed',
                            'Overdue'
                        ],

                        datasets: [{

                            label: 'Maintenance Activities',

                            data: [

                                {{ $scheduledMaintenance }},

                                {{ $dueMaintenance }},

                                {{ $totalMaintenance - $scheduledMaintenance - $dueMaintenance >= 0
                                    ? $totalMaintenance - $scheduledMaintenance - $dueMaintenance
                                    : 0 }},

                                {{ $dueMaintenance }}

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

                                    precision: 0

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


            /* ========================================================
               REPLACEMENT RISK CHART
            ======================================================== */

            const riskCanvas =
                document.getElementById('riskChart');

            if (riskCanvas) {

                new Chart(riskCanvas, {

                    type: 'doughnut',

                    data: {

                        labels: [
                            'Low Risk',
                            'Medium Risk',
                            'High Risk'
                        ],

                        datasets: [{

                            data: [

                                {{ $riskData['Low'] ?? 0 }},

                                {{ $riskData['Medium'] ?? 0 }},

                                {{ $riskData['High'] ?? 0 }}

                            ],

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

        });

    </script>

</x-app-layout>