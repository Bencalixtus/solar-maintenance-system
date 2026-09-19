<x-app-layout>

    <x-slot name="header">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h2 class="h4 mb-1">
                    <i class="fas fa-clipboard-check text-success me-2"></i>
                    Inspection Report
                </h2>

                <p class="text-muted mb-0 small">
                    Inspection details and component assessment.
                </p>

            </div>


            <div class="d-flex gap-2">

                <a
                    href="{{ route('inspections.index') }}"
                    class="btn btn-outline-secondary"
                >
                    <i class="fas fa-arrow-left me-1"></i>
                    Back
                </a>


                <a
                    href="{{ route('inspections.edit', $inspection) }}"
                    class="btn btn-primary"
                >
                    <i class="fas fa-edit me-1"></i>
                    Edit
                </a>


                <a
                    href="{{ route('inspections.print', $inspection) }}"
                    class="btn btn-success"
                    target="_blank"
                >
                    <i class="fas fa-print me-1"></i>
                    Print Report
                </a>

            </div>

        </div>

    </x-slot>


    <div class="container-fluid py-4">


        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                <i class="fas fa-check-circle me-2"></i>

                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        {{-- SUMMARY --}}
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">

                    <i class="fas fa-info-circle text-success me-2"></i>

                    Inspection Information

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-4">

                        <small class="text-muted">
                            Installation
                        </small>

                        <div class="fw-bold">
                            {{ $inspection->installation->name ?? 'N/A' }}
                        </div>

                    </div>


                    <div class="col-md-4">

                        <small class="text-muted">
                            Location
                        </small>

                        <div class="fw-bold">
                            {{ $inspection->installation->location ?? 'N/A' }}
                        </div>

                    </div>


                    <div class="col-md-4">

                        <small class="text-muted">
                            Inspector
                        </small>

                        <div class="fw-bold">
                            {{ $inspection->inspector->name ?? 'N/A' }}
                        </div>

                    </div>


                    <div class="col-md-4">

                        <small class="text-muted">
                            Inspection Date
                        </small>

                        <div class="fw-bold">
                            {{ $inspection->inspection_date?->format('d M Y') }}
                        </div>

                    </div>


                    <div class="col-md-4">

                        <small class="text-muted">
                            Next Inspection
                        </small>

                        <div class="fw-bold">

                            {{ $inspection->next_inspection_date?->format('d M Y') ?? 'Not set' }}

                            @if(
                                $inspection->next_inspection_date &&
                                $inspection->next_inspection_date->isPast()
                            )

                                <span class="badge bg-danger ms-1">
                                    Due
                                </span>

                            @endif

                        </div>

                    </div>


                    <div class="col-md-4">

                        <small class="text-muted">
                            Overall Condition
                        </small>

                        <div>

                            @php

                                $conditionClass = match(
                                    $inspection->overall_condition
                                ) {

                                    'Excellent' => 'success',

                                    'Good' => 'primary',

                                    'Fair' => 'warning',

                                    'Poor' => 'secondary',

                                    'Critical' => 'danger',

                                    default => 'secondary',

                                };

                            @endphp


                            <span class="badge bg-{{ $conditionClass }} fs-6">

                                {{ $inspection->overall_condition }}

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- CHECKLIST --}}
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">

                    <i class="fas fa-tasks text-success me-2"></i>

                    Component Checklist

                </h5>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover mb-0 align-middle">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>
                                <th>Component</th>
                                <th>Check Item</th>
                                <th>Result</th>
                                <th>Measurement</th>
                                <th>Condition</th>
                                <th>Remarks</th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse(
                                $inspection->inspectionItems
                                as $index => $item
                            )

                                <tr>

                                    <td>
                                        {{ $index + 1 }}
                                    </td>


                                    <td>

                                        <strong>
                                            {{ $item->component->name ?? 'N/A' }}
                                        </strong>

                                        <br>

                                        <small class="text-muted">
                                            {{ $item->component->componentType->name ?? 'Other' }}
                                        </small>

                                    </td>


                                    <td>
                                        {{ $item->check_item }}
                                    </td>


                                    <td>

                                        @php

                                            $resultClass = match(
                                                $item->result
                                            ) {

                                                'Pass' => 'success',

                                                'Fail' => 'danger',

                                                'Needs Attention' => 'warning',

                                                'Not Applicable' => 'secondary',

                                                default => 'secondary',

                                            };

                                        @endphp


                                        <span class="badge bg-{{ $resultClass }}">

                                            {{ $item->result }}

                                        </span>

                                    </td>


                                    <td>

                                        @if($item->measurement !== null)

                                            {{ $item->measurement }}

                                            {{ $item->unit }}

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        <span class="badge bg-light text-dark border">

                                            {{ $item->condition }}

                                        </span>

                                    </td>


                                    <td>

                                        {{ $item->remarks ?: '—' }}

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="7"
                                        class="text-center text-muted py-4"
                                    >
                                        No checklist records found.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- OBSERVATION AND RECOMMENDATION --}}
        <div class="row g-4 mb-4">


            <div class="col-md-6">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">

                            <i class="fas fa-eye text-success me-2"></i>

                            General Observation

                        </h5>

                    </div>


                    <div class="card-body">

                        @if($inspection->general_observation)

                            <p class="mb-0">
                                {!! nl2br(e($inspection->general_observation)) !!}
                            </p>

                        @else

                            <span class="text-muted">
                                No general observation recorded.
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            <div class="col-md-6">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">

                            <i class="fas fa-tools text-success me-2"></i>

                            Recommendation

                        </h5>

                    </div>


                    <div class="card-body">

                        @if($inspection->recommendation)

                            <p class="mb-0">
                                {!! nl2br(e($inspection->recommendation)) !!}
                            </p>

                        @else

                            <span class="text-muted">
                                No recommendation recorded.
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- ADDITIONAL REMARKS --}}
        @if($inspection->remarks)

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        <i class="fas fa-comment-alt text-success me-2"></i>

                        Additional Remarks

                    </h5>

                </div>


                <div class="card-body">

                    {!! nl2br(e($inspection->remarks)) !!}

                </div>

            </div>

        @endif


    </div>

</x-app-layout>