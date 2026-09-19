<x-app-layout>

    <div class="container-fluid py-4">

        <div class="d-flex
                    justify-content-between
                    align-items-center mb-4">

            <div>

                <h2 class="fw-bold mb-1">
                    <i class="bi bi-speedometer2 me-2 text-success"></i>
                    Measurement Details
                </h2>

                <p class="text-muted mb-0">
                    Detailed measurement record and comparison.
                </p>

            </div>

            <div class="d-flex gap-2">

                <a href="{{ route('measurements.edit', $measurement) }}"
                   class="btn btn-primary">

                    <i class="bi bi-pencil me-1"></i>
                    Edit

                </a>

                <a href="{{ route('measurements.index') }}"
                   class="btn btn-outline-secondary">

                    <i class="bi bi-arrow-left me-1"></i>
                    Back

                </a>

            </div>

        </div>


        @if(session('success'))

            <div class="alert alert-success">

                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}

            </div>

        @endif


        @php

            $value = (float) $measurement->value;

            $reference = $measurement->reference_value !== null
                ? (float) $measurement->reference_value
                : null;

            $difference = null;
            $percentage = null;

            if ($reference !== null && $reference != 0) {
                $difference = $value - $reference;
                $percentage = ($difference / $reference) * 100;
            }

        @endphp


        <div class="row g-4">

            {{-- Measurement --}}
            <div class="col-lg-8">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-success text-white py-3">

                        <h5 class="mb-0 fw-bold">
                            Measurement Result
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="text-center py-4">

                            <div class="display-3 fw-bold text-success">

                                {{ number_format($value, 2) }}

                                <span class="fs-3 text-muted">
                                    {{ $measurement->unit }}
                                </span>

                            </div>

                            <p class="text-muted mb-0">
                                {{ $measurement->parameter }}
                            </p>

                        </div>


                        <hr>


                        <div class="row g-4">

                            <div class="col-md-4">

                                <small class="text-muted">
                                    Component
                                </small>

                                <div class="fw-semibold">
                                    {{ $measurement->component->name ?? 'N/A' }}
                                </div>

                            </div>


                            <div class="col-md-4">

                                <small class="text-muted">
                                    Component Type
                                </small>

                                <div class="fw-semibold">
                                    {{ $measurement->component->componentType->name ?? 'N/A' }}
                                </div>

                            </div>


                            <div class="col-md-4">

                                <small class="text-muted">
                                    Installation
                                </small>

                                <div class="fw-semibold">
                                    {{ $measurement->component->installation->name ?? 'N/A' }}
                                </div>

                            </div>


                            <div class="col-md-4">

                                <small class="text-muted">
                                    Measurement Date
                                </small>

                                <div class="fw-semibold">
                                    {{ $measurement->measurement_date?->format('d M Y') }}
                                </div>

                            </div>


                            <div class="col-md-4">

                                <small class="text-muted">
                                    Recorded By
                                </small>

                                <div class="fw-semibold">
                                    {{ $measurement->recorder->name ?? 'N/A' }}
                                </div>

                            </div>


                            <div class="col-md-4">

                                <small class="text-muted">
                                    Recorded On
                                </small>

                                <div class="fw-semibold">
                                    {{ $measurement->created_at?->format('d M Y H:i') }}
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Remarks --}}
                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header bg-white">

                        <h5 class="fw-bold mb-0">
                            Remarks
                        </h5>

                    </div>

                    <div class="card-body">

                        @if($measurement->remarks)

                            <p class="mb-0">
                                {{ $measurement->remarks }}
                            </p>

                        @else

                            <span class="text-muted">
                                No remarks recorded.
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            {{-- Comparison --}}
            <div class="col-lg-4">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white py-3">

                        <h5 class="fw-bold mb-0">
                            Reference Comparison
                        </h5>

                    </div>

                    <div class="card-body">

                        @if($reference !== null)

                            <div class="mb-4">

                                <small class="text-muted">
                                    Reference Value
                                </small>

                                <h4 class="fw-bold">
                                    {{ number_format($reference, 2) }}
                                    {{ $measurement->unit }}
                                </h4>

                            </div>


                            <div class="mb-4">

                                <small class="text-muted">
                                    Difference
                                </small>

                                <h4 class="fw-bold">

                                    {{ $difference >= 0 ? '+' : '' }}
                                    {{ number_format($difference, 2) }}
                                    {{ $measurement->unit }}

                                </h4>

                            </div>


                            <div>

                                <small class="text-muted">
                                    Percentage Difference
                                </small>

                                <h4 class="fw-bold">

                                    {{ $percentage >= 0 ? '+' : '' }}
                                    {{ number_format($percentage, 2) }}%

                                </h4>

                            </div>


                            <hr>


                            @if(abs($percentage) <= 5)

                                <div class="alert alert-success mb-0">

                                    <i class="bi bi-check-circle me-2"></i>

                                    <strong>Normal</strong>

                                    <br>

                                    The measured value is close to
                                    the reference value.

                                </div>

                            @elseif(abs($percentage) <= 15)

                                <div class="alert alert-warning mb-0">

                                    <i class="bi bi-exclamation-triangle me-2"></i>

                                    <strong>Monitor</strong>

                                    <br>

                                    The measured value differs
                                    moderately from the reference.

                                </div>

                            @else

                                <div class="alert alert-danger mb-0">

                                    <i class="bi bi-exclamation-octagon me-2"></i>

                                    <strong>Attention Required</strong>

                                    <br>

                                    The measured value differs
                                    significantly from the reference.

                                </div>

                            @endif

                        @else

                            <div class="text-center py-4">

                                <i class="bi bi-bar-chart
                                          display-5 text-muted"></i>

                                <p class="text-muted mt-3 mb-0">

                                    No reference value was supplied,
                                    so comparison cannot be calculated.

                                </p>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>