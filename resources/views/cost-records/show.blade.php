@extends('layouts.app')

@section('title', 'Cost Record Details')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h3 fw-bold mb-1">
                <i class="bi bi-receipt text-success me-2"></i>
                Cost Record Details
            </h1>

            <p class="text-muted mb-0">
                View expenditure information and related maintenance activity.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('cost-records.edit', $costRecord) }}"
               class="btn btn-primary">

                <i class="bi bi-pencil me-1"></i>
                Edit

            </a>

            <a href="{{ route('cost-records.index') }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Back

            </a>

        </div>

    </div>


    <div class="row g-4">

        {{-- Main Details --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0 pt-4 px-4">

                    <h5 class="fw-bold mb-0">
                        Cost Information
                    </h5>

                </div>

                <div class="card-body p-4">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small">
                                COMPONENT
                            </div>

                            <div class="fw-semibold fs-5 mt-1">

                                {{ $costRecord->component->name ?? 'Not assigned' }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                COST TYPE
                            </div>

                            <div class="mt-1">

                                @php
                                    $badgeClass = match($costRecord->cost_type) {
                                        'Maintenance' => 'bg-primary',
                                        'Repair' => 'bg-warning text-dark',
                                        'Replacement' => 'bg-danger',
                                        'Parts/Materials' => 'bg-info text-dark',
                                        'Labour/Service' => 'bg-success',
                                        default => 'bg-secondary',
                                    };
                                @endphp

                                <span class="badge {{ $badgeClass }} fs-6">
                                    {{ $costRecord->cost_type }}
                                </span>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                COST DATE
                            </div>

                            <div class="fw-semibold mt-1">
                                {{ $costRecord->cost_date?->format('d F Y') ?? 'N/A' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                AMOUNT
                            </div>

                            <div class="fw-bold text-success fs-4 mt-1">
                                ₦{{ number_format($costRecord->amount, 2) }}
                            </div>

                        </div>


                        <div class="col-12">

                            <hr>

                            <div class="text-muted small">
                                DESCRIPTION
                            </div>

                            <p class="mt-2 mb-0">
                                {{ $costRecord->description }}
                            </p>

                        </div>


                        @if($costRecord->remarks)

                            <div class="col-12">

                                <hr>

                                <div class="text-muted small">
                                    REMARKS
                                </div>

                                <p class="mt-2 mb-0">
                                    {{ $costRecord->remarks }}
                                </p>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- Related Information --}}
        <div class="col-lg-4">

            {{-- Component --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 pt-4 px-4">

                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-cpu text-success me-2"></i>
                        Component
                    </h6>

                </div>

                <div class="card-body px-4">

                    @if($costRecord->component)

                        <div class="fw-semibold">
                            {{ $costRecord->component->name }}
                        </div>

                        <div class="text-muted small mt-1">

                            {{ $costRecord->component->componentType->name ?? 'Component' }}

                        </div>

                        @if($costRecord->component->installation)

                            <div class="mt-3">

                                <span class="text-muted small">
                                    Installation
                                </span>

                                <div class="fw-semibold">
                                    {{ $costRecord->component->installation->name }}
                                </div>

                            </div>

                        @endif

                    @else

                        <span class="text-muted">
                            No component linked.
                        </span>

                    @endif

                </div>

            </div>


            {{-- Maintenance --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0 pt-4 px-4">

                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-tools text-success me-2"></i>
                        Related Maintenance
                    </h6>

                </div>

                <div class="card-body px-4">

                    @if($costRecord->maintenance)

                        <div class="fw-semibold">
                            {{ $costRecord->maintenance->maintenance_type }}
                        </div>

                        <div class="text-muted small mt-1">

                            {{ $costRecord->maintenance->maintenance_date?->format('d M Y') }}

                        </div>

                        @if($costRecord->maintenance->description)

                            <p class="small mt-3 mb-0">
                                {{ $costRecord->maintenance->description }}
                            </p>

                        @endif

                    @else

                        <span class="text-muted">
                            No maintenance record linked.
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>
@endsection