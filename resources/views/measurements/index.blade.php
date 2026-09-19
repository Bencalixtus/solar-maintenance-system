<x-app-layout>

    <div class="container-fluid py-4">

        {{-- Page Header --}}
        <div class="d-flex flex-column flex-md-row
                    justify-content-between align-items-md-center mb-4">

            <div>
                <h2 class="fw-bold mb-1">
                    <i class="bi bi-speedometer2 me-2 text-success"></i>
                    Measurements
                </h2>

                <p class="text-muted mb-0">
                    Monitor and record measured operating parameters
                    of solar-battery system components.
                </p>
            </div>

            <div class="mt-3 mt-md-0">
                <a href="{{ route('measurements.create') }}"
                   class="btn btn-success">
                    <i class="bi bi-plus-circle me-1"></i>
                    Record Measurement
                </a>
            </div>

        </div>


        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show"
                 role="alert">

                <i class="bi bi-check-circle me-2"></i>

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>
        @endif


        {{-- Summary Cards --}}
        <div class="row g-4 mb-4">

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">

                        <div class="d-flex
                                    justify-content-between
                                    align-items-center">

                            <div>
                                <p class="text-muted mb-1">
                                    Total Measurements
                                </p>

                                <h3 class="fw-bold mb-0">
                                    {{ $totalMeasurements }}
                                </h3>
                            </div>

                            <div class="rounded-circle
                                        bg-success-subtle
                                        p-3">

                                <i class="bi bi-speedometer2
                                          fs-4 text-success"></i>

                            </div>

                        </div>

                    </div>
                </div>
            </div>


            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">

                        <div class="d-flex
                                    justify-content-between
                                    align-items-center">

                            <div>
                                <p class="text-muted mb-1">
                                    Voltage
                                </p>

                                <h3 class="fw-bold mb-0">
                                    {{ $voltageMeasurements }}
                                </h3>
                            </div>

                            <div class="rounded-circle
                                        bg-warning-subtle
                                        p-3">

                                <i class="bi bi-lightning-charge
                                          fs-4 text-warning"></i>

                            </div>

                        </div>

                    </div>
                </div>
            </div>


            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">

                        <div class="d-flex
                                    justify-content-between
                                    align-items-center">

                            <div>
                                <p class="text-muted mb-1">
                                    Current
                                </p>

                                <h3 class="fw-bold mb-0">
                                    {{ $currentMeasurements }}
                                </h3>
                            </div>

                            <div class="rounded-circle
                                        bg-primary-subtle
                                        p-3">

                                <i class="bi bi-activity
                                          fs-4 text-primary"></i>

                            </div>

                        </div>

                    </div>
                </div>
            </div>


            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">

                        <div class="d-flex
                                    justify-content-between
                                    align-items-center">

                            <div>
                                <p class="text-muted mb-1">
                                    Temperature
                                </p>

                                <h3 class="fw-bold mb-0">
                                    {{ $temperatureMeasurements }}
                                </h3>
                            </div>

                            <div class="rounded-circle
                                        bg-danger-subtle
                                        p-3">

                                <i class="bi bi-thermometer-half
                                          fs-4 text-danger"></i>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>


        {{-- Measurements Table --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">

                <div class="d-flex
                            justify-content-between
                            align-items-center">

                    <h5 class="mb-0 fw-bold">
                        Recorded Measurements
                    </h5>

                    <span class="badge bg-success">
                        {{ $totalMeasurements }} Records
                    </span>

                </div>

            </div>


            <div class="card-body p-0">

                @if($measurements->count())

                    <div class="table-responsive">

                        <table class="table table-hover
                                      align-middle mb-0">

                            <thead class="table-light">

                                <tr>
                                    <th class="ps-4">Component</th>
                                    <th>Parameter</th>
                                    <th>Value</th>
                                    <th>Reference</th>
                                    <th>Date</th>
                                    <th>Recorded By</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">
                                        Actions
                                    </th>
                                </tr>

                            </thead>

                            <tbody>

                                @foreach($measurements as $measurement)

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

                                    <tr>

                                        <td class="ps-4">

                                            <div class="fw-semibold">
                                                {{ $measurement->component->name ?? 'N/A' }}
                                            </div>

                                            <small class="text-muted">
                                                {{ $measurement->component->componentType->name ?? 'Component' }}
                                            </small>

                                        </td>


                                        <td>
                                            {{ $measurement->parameter }}
                                        </td>


                                        <td>
                                            <strong>
                                                {{ number_format($value, 2) }}
                                            </strong>

                                            <span class="text-muted">
                                                {{ $measurement->unit }}
                                            </span>
                                        </td>


                                        <td>

                                            @if($reference !== null)

                                                {{ number_format($reference, 2) }}
                                                {{ $measurement->unit }}

                                            @else

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        <td>
                                            {{ $measurement->measurement_date?->format('d M Y') }}
                                        </td>


                                        <td>
                                            {{ $measurement->recorder->name ?? 'N/A' }}
                                        </td>


                                        <td>

                                            @if($percentage === null)

                                                <span class="badge bg-secondary">
                                                    No Reference
                                                </span>

                                            @elseif(abs($percentage) <= 5)

                                                <span class="badge bg-success">
                                                    Normal
                                                </span>

                                            @elseif(abs($percentage) <= 15)

                                                <span class="badge bg-warning text-dark">
                                                    Monitor
                                                </span>

                                            @else

                                                <span class="badge bg-danger">
                                                    Attention
                                                </span>

                                            @endif

                                        </td>


                                        <td class="text-end pe-4">

                                            <div class="btn-group">

                                                <a href="{{ route('measurements.show', $measurement) }}"
                                                   class="btn btn-sm btn-outline-success"
                                                   title="View">

                                                    <i class="bi bi-eye"></i>

                                                </a>


                                                <a href="{{ route('measurements.edit', $measurement) }}"
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="Edit">

                                                    <i class="bi bi-pencil"></i>

                                                </a>


                                                <form action="{{ route('measurements.destroy', $measurement) }}"
                                                      method="POST"
                                                      class="d-inline">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Delete"
                                                            onclick="return confirm('Are you sure you want to delete this measurement?')">

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

                        <i class="bi bi-speedometer2
                                  display-4 text-muted"></i>

                        <h5 class="mt-3">
                            No Measurements Recorded
                        </h5>

                        <p class="text-muted">
                            Start recording measurements for
                            your solar-battery components.
                        </p>

                        <a href="{{ route('measurements.create') }}"
                           class="btn btn-success">

                            <i class="bi bi-plus-circle me-1"></i>
                            Record First Measurement

                        </a>

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>