<x-app-layout>

    <div class="container-fluid py-4">

        <div class="d-flex
                    justify-content-between
                    align-items-center mb-4">

            <div>

                <h2 class="fw-bold mb-1">
                    <i class="bi bi-pencil-square me-2 text-success"></i>
                    Edit Measurement
                </h2>

                <p class="text-muted mb-0">
                    Update the measurement record.
                </p>

            </div>

            <a href="{{ route('measurements.show', $measurement) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Back

            </a>

        </div>


        @if($errors->any())

            <div class="alert alert-danger">

                <strong>
                    Please correct the following errors:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form action="{{ route('measurements.update', $measurement) }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="row g-4">

                <div class="col-lg-8">

                    <div class="card border-0 shadow-sm">

                        <div class="card-header bg-white py-3">

                            <h5 class="fw-bold mb-0">
                                Measurement Details
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row g-3">

                                {{-- Component --}}
                                <div class="col-md-12">

                                    <label class="form-label fw-semibold">
                                        Component
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select name="component_id"
                                            class="form-select"
                                            required>

                                        <option value="">
                                            Select Component
                                        </option>

                                        @foreach($components as $component)

                                            <option value="{{ $component->id }}"
                                                {{ old('component_id', $measurement->component_id) == $component->id ? 'selected' : '' }}>

                                                {{ $component->name }}
                                                —
                                                {{ $component->componentType->name ?? 'Component' }}
                                                —
                                                {{ $component->installation->name ?? 'Installation' }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>


                                {{-- Recorded By --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">
                                        Recorded By
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select name="recorded_by"
                                            class="form-select"
                                            required>

                                        @foreach($users as $user)

                                            <option value="{{ $user->id }}"
                                                {{ old('recorded_by', $measurement->recorded_by) == $user->id ? 'selected' : '' }}>

                                                {{ $user->name }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>


                                {{-- Date --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">
                                        Measurement Date
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="date"
                                           name="measurement_date"
                                           class="form-control"
                                           value="{{ old('measurement_date', $measurement->measurement_date?->format('Y-m-d')) }}"
                                           required>

                                </div>


                                {{-- Parameter --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">
                                        Parameter
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select name="parameter"
                                            class="form-select"
                                            required>

                                        @foreach([
                                            'Voltage',
                                            'Current',
                                            'Power',
                                            'Temperature',
                                            'Battery Capacity',
                                            'State of Charge',
                                            'Output Voltage',
                                            'Input Voltage',
                                            'Efficiency',
                                            'Other'
                                        ] as $parameter)

                                            <option value="{{ $parameter }}"
                                                {{ old('parameter', $measurement->parameter) === $parameter ? 'selected' : '' }}>

                                                {{ $parameter }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>


                                {{-- Value --}}
                                <div class="col-md-3">

                                    <label class="form-label fw-semibold">
                                        Measured Value
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="number"
                                           name="value"
                                           class="form-control"
                                           step="0.01"
                                           value="{{ old('value', $measurement->value) }}"
                                           required>

                                </div>


                                {{-- Unit --}}
                                <div class="col-md-3">

                                    <label class="form-label fw-semibold">
                                        Unit
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                           name="unit"
                                           class="form-control"
                                           value="{{ old('unit', $measurement->unit) }}"
                                           required>

                                </div>


                                {{-- Reference --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">
                                        Reference Value
                                    </label>

                                    <input type="number"
                                           name="reference_value"
                                           class="form-control"
                                           step="0.01"
                                           value="{{ old('reference_value', $measurement->reference_value) }}">

                                </div>


                                {{-- Remarks --}}
                                <div class="col-md-12">

                                    <label class="form-label fw-semibold">
                                        Remarks
                                    </label>

                                    <textarea name="remarks"
                                              class="form-control"
                                              rows="4">{{ old('remarks', $measurement->remarks) }}</textarea>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="col-lg-4">

                    <div class="card border-0 shadow-sm">

                        <div class="card-header bg-success text-white">

                            <h5 class="mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                Important
                            </h5>

                        </div>

                        <div class="card-body">

                            <p class="text-muted">

                                Ensure the recorded value, unit and
                                reference value correspond to the same
                                parameter.

                            </p>

                            <div class="alert alert-warning mb-0">

                                <i class="bi bi-exclamation-triangle me-2"></i>

                                Historical measurement data is important
                                for degradation trend analysis.

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="mt-4 d-flex
                        justify-content-end gap-2">

                <a href="{{ route('measurements.show', $measurement) }}"
                   class="btn btn-light border">

                    Cancel

                </a>

                <button type="submit"
                        class="btn btn-success">

                    <i class="bi bi-check-circle me-1"></i>
                    Update Measurement

                </button>

            </div>

        </form>

    </div>

</x-app-layout>