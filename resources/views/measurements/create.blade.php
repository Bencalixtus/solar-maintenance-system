<x-app-layout>

    <div class="container-fluid py-4">

        <div class="d-flex
                    justify-content-between
                    align-items-center mb-4">

            <div>
                <h2 class="fw-bold mb-1">
                    <i class="bi bi-plus-circle me-2 text-success"></i>
                    Record Measurement
                </h2>

                <p class="text-muted mb-0">
                    Enter a measured operating parameter for a component.
                </p>
            </div>

            <a href="{{ route('measurements.index') }}"
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


        <form action="{{ route('measurements.store') }}"
              method="POST">

            @csrf

            <div class="row g-4">

                {{-- Main Measurement --}}
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
                                                {{ old('component_id') == $component->id ? 'selected' : '' }}>

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

                                        <option value="">
                                            Select User
                                        </option>

                                        @foreach($users as $user)

                                            <option value="{{ $user->id }}"
                                                {{ old('recorded_by', auth()->id()) == $user->id ? 'selected' : '' }}>

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
                                           value="{{ old('measurement_date', now()->format('Y-m-d')) }}"
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

                                        <option value="">
                                            Select Parameter
                                        </option>

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
                                                {{ old('parameter') === $parameter ? 'selected' : '' }}>

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
                                           value="{{ old('value') }}"
                                           placeholder="e.g. 41.80"
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
                                           value="{{ old('unit') }}"
                                           placeholder="V, A, °C"
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
                                           value="{{ old('reference_value') }}"
                                           placeholder="Rated / expected value">

                                    <small class="text-muted">
                                        Optional. Used for comparison and
                                        degradation analysis.
                                    </small>

                                </div>


                                {{-- Remarks --}}
                                <div class="col-md-12">

                                    <label class="form-label fw-semibold">
                                        Remarks
                                    </label>

                                    <textarea name="remarks"
                                              class="form-control"
                                              rows="4"
                                              placeholder="Enter any observation or additional information...">{{ old('remarks') }}</textarea>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Information --}}
                <div class="col-lg-4">

                    <div class="card border-0 shadow-sm">

                        <div class="card-header bg-success text-white">

                            <h5 class="mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                Measurement Guide
                            </h5>

                        </div>

                        <div class="card-body">

                            <p class="text-muted">
                                Measurements should be recorded during
                                routine inspection and maintenance activities.
                            </p>

                            <hr>

                            <h6 class="fw-bold">
                                Examples
                            </h6>

                            <ul class="text-muted">

                                <li>
                                    Solar panel voltage — V
                                </li>

                                <li>
                                    Solar panel current — A
                                </li>

                                <li>
                                    Battery voltage — V
                                </li>

                                <li>
                                    Battery temperature — °C
                                </li>

                                <li>
                                    Battery capacity — Ah
                                </li>

                                <li>
                                    Inverter output voltage — V
                                </li>

                            </ul>

                            <div class="alert alert-warning mb-0">

                                <i class="bi bi-exclamation-triangle me-2"></i>

                                Only record measurements that can be
                                obtained safely and reliably from the
                                available equipment.

                            </div>

                        </div>

                    </div>


                    <div class="card border-0 shadow-sm mt-4">

                        <div class="card-body">

                            <h6 class="fw-bold">
                                Why record measurements?
                            </h6>

                            <p class="text-muted mb-0">

                                Historical measurements allow the system
                                to identify changes in component performance
                                and support future degradation and
                                replacement analysis.

                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Submit --}}
            <div class="mt-4 d-flex
                        justify-content-end gap-2">

                <a href="{{ route('measurements.index') }}"
                   class="btn btn-light border">

                    Cancel

                </a>

                <button type="submit"
                        class="btn btn-success">

                    <i class="bi bi-check-circle me-1"></i>
                    Save Measurement

                </button>

            </div>

        </form>

    </div>

</x-app-layout>