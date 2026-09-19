@extends('layouts.app')

@section('title', 'New Inspection')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <i class="bi bi-clipboard-plus me-2"></i>
                New Inspection
            </h3>
            <p class="text-muted mb-0">
                Record a physical inspection of the solar-battery installation.
            </p>
        </div>

        <a href="{{ route('inspections.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Inspections
        </a>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="fw-bold mb-2">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Please correct the following:
            </div>

            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('inspections.store') }}" id="inspectionForm">
        @csrf

        {{-- Inspection Information --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Inspection Information
                </h5>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- Installation --}}
                    <div class="col-md-6">
                        <label for="installation_id" class="form-label fw-semibold">
                            Installation <span class="text-danger">*</span>
                        </label>

                        <select
                            name="installation_id"
                            id="installation_id"
                            class="form-select @error('installation_id') is-invalid @enderror"
                            required
                        >
                            <option value="">-- Select Installation --</option>

                            @foreach ($installations as $installation)
                                <option
                                    value="{{ $installation->id }}"
                                    {{ old('installation_id') == $installation->id ? 'selected' : '' }}
                                >
                                    {{ $installation->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('installation_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Inspection Date --}}
                    <div class="col-md-3">
                        <label for="inspection_date" class="form-label fw-semibold">
                            Inspection Date <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="inspection_date"
                            id="inspection_date"
                            class="form-control @error('inspection_date') is-invalid @enderror"
                            value="{{ old('inspection_date', now()->format('Y-m-d')) }}"
                            required
                        >

                        @error('inspection_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Next Inspection --}}
                    <div class="col-md-3">
                        <label for="next_inspection_date" class="form-label fw-semibold">
                            Next Inspection <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="next_inspection_date"
                            id="next_inspection_date"
                            class="form-control @error('next_inspection_date') is-invalid @enderror"
                            value="{{ old('next_inspection_date', now()->addMonths(3)->format('Y-m-d')) }}"
                            required
                        >

                        @error('next_inspection_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Overall Condition --}}
                    <div class="col-md-6">
                        <label for="overall_condition" class="form-label fw-semibold">
                            Overall Condition <span class="text-danger">*</span>
                        </label>

                        <select
                            name="overall_condition"
                            id="overall_condition"
                            class="form-select @error('overall_condition') is-invalid @enderror"
                            required
                        >
                            <option value="">-- Select Condition --</option>
                            <option value="Excellent" {{ old('overall_condition') == 'Excellent' ? 'selected' : '' }}>
                                Excellent
                            </option>
                            <option value="Good" {{ old('overall_condition') == 'Good' ? 'selected' : '' }}>
                                Good
                            </option>
                            <option value="Fair" {{ old('overall_condition') == 'Fair' ? 'selected' : '' }}>
                                Fair
                            </option>
                            <option value="Poor" {{ old('overall_condition') == 'Poor' ? 'selected' : '' }}>
                                Poor
                            </option>
                            <option value="Critical" {{ old('overall_condition') == 'Critical' ? 'selected' : '' }}>
                                Critical
                            </option>
                        </select>

                        @error('overall_condition')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- General Observation --}}
                    <div class="col-md-6">
                        <label for="general_observation" class="form-label fw-semibold">
                            General Observation
                        </label>

                        <textarea
                            name="general_observation"
                            id="general_observation"
                            rows="3"
                            class="form-control @error('general_observation') is-invalid @enderror"
                            placeholder="Enter general observations from the inspection..."
                        >{{ old('general_observation') }}</textarea>

                        @error('general_observation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

            </div>
        </div>


        {{-- Component Checklist --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-check me-2"></i>
                    Component Inspection Checklist
                </h5>

                <span class="badge bg-warning text-dark">
                    Complete all applicable items
                </span>
            </div>

            <div class="card-body">

                <div id="componentChecklist">

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Select an installation above to load its components.
                    </div>

                </div>

            </div>
        </div>


        {{-- Recommendations --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-lightbulb me-2"></i>
                    Recommendations
                </h5>
            </div>

            <div class="card-body">

                <div class="mb-3">
                    <label for="recommendation" class="form-label fw-semibold">
                        Recommendation
                    </label>

                    <textarea
                        name="recommendation"
                        id="recommendation"
                        rows="4"
                        class="form-control"
                        placeholder="Enter recommended maintenance, repair or follow-up actions..."
                    >{{ old('recommendation') }}</textarea>
                </div>

                <div>
                    <label for="remarks" class="form-label fw-semibold">
                        Additional Remarks
                    </label>

                    <textarea
                        name="remarks"
                        id="remarks"
                        rows="3"
                        class="form-control"
                        placeholder="Enter any additional remarks..."
                    >{{ old('remarks') }}</textarea>
                </div>

            </div>
        </div>


        {{-- Buttons --}}
        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route('inspections.index') }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-x-circle me-1"></i>
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-success"
                id="saveInspectionBtn"
            >
                <i class="bi bi-check-circle me-1"></i>
                Save Inspection
            </button>

        </div>

    </form>

</div>


{{-- Component Loading JavaScript --}}
@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const installationSelect = document.getElementById('installation_id');
    const checklistContainer = document.getElementById('componentChecklist');

    installationSelect.addEventListener('change', function () {

        const installationId = this.value;

        checklistContainer.innerHTML = '';

        if (!installationId) {

            checklistContainer.innerHTML = `
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Select an installation to load its components.
                </div>
            `;

            return;
        }

        const installations = @json($installations);

        const installation = installations.find(
            item => item.id == installationId
        );

        if (!installation || !installation.components || installation.components.length === 0) {

            checklistContainer.innerHTML = `
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    No components have been registered for this installation.
                    Please add components before creating an inspection.
                </div>
            `;

            return;
        }


        installation.components.forEach(function (component, index) {

            const componentType = component.component_type
                ? component.component_type.name
                : 'Component';


            const componentCard = document.createElement('div');

            componentCard.className = 'card border mb-4';


            componentCard.innerHTML = `

                <div class="card-header bg-light">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <strong>
                                <i class="bi bi-cpu me-2"></i>
                                ${escapeHtml(component.name)}
                            </strong>

                            <span class="badge bg-secondary ms-2">
                                ${escapeHtml(componentType)}
                            </span>
                        </div>

                        <span class="text-muted small">
                            Component ${index + 1}
                        </span>

                    </div>

                </div>


                <div class="card-body">

                    <input
                        type="hidden"
                        name="items[${index}][component_id]"
                        value="${component.id}"
                    >


                    <div class="row g-3">

                        {{-- Check Item --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Check Item <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="items[${index}][check_item]"
                                class="form-control"
                                value="General physical condition"
                                required
                            >

                        </div>


                        {{-- Result --}}
                        <div class="col-md-3">

                            <label class="form-label fw-semibold">
                                Result <span class="text-danger">*</span>
                            </label>

                            <select
                                name="items[${index}][result]"
                                class="form-select"
                                required
                            >
                                <option value="">-- Select --</option>
                                <option value="Pass">Pass</option>
                                <option value="Fail">Fail</option>
                                <option value="Needs Attention">Needs Attention</option>
                                <option value="Not Applicable">Not Applicable</option>
                            </select>

                        </div>


                        {{-- Condition --}}
                        <div class="col-md-3">

                            <label class="form-label fw-semibold">
                                Condition <span class="text-danger">*</span>
                            </label>

                            <select
                                name="items[${index}][condition]"
                                class="form-select"
                                required
                            >
                                <option value="">-- Select --</option>
                                <option value="Excellent">Excellent</option>
                                <option value="Good">Good</option>
                                <option value="Fair">Fair</option>
                                <option value="Poor">Poor</option>
                                <option value="Critical">Critical</option>
                            </select>

                        </div>


                        {{-- Measurement --}}
                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Measurement
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                name="items[${index}][measurement]"
                                class="form-control"
                                placeholder="e.g. 550"
                            >

                        </div>


                        {{-- Unit --}}
                        <div class="col-md-2">

                            <label class="form-label fw-semibold">
                                Unit
                            </label>

                            <input
                                type="text"
                                name="items[${index}][unit]"
                                class="form-control"
                                placeholder="W, V, Ah"
                            >

                        </div>


                        {{-- Remarks --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Remarks
                            </label>

                            <input
                                type="text"
                                name="items[${index}][remarks]"
                                class="form-control"
                                placeholder="Enter component observations..."
                            >

                        </div>

                    </div>

                </div>

            `;

            checklistContainer.appendChild(componentCard);

        });

    });


    function escapeHtml(value) {

        if (value === null || value === undefined) {
            return '';
        }

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }


    // Prevent accidental double submission

    document.getElementById('inspectionForm').addEventListener('submit', function () {

        const button = document.getElementById('saveInspectionBtn');

        button.disabled = true;

        button.innerHTML = `
            <span class="spinner-border spinner-border-sm me-1"></span>
            Saving Inspection...
        `;

    });

});
</script>

@endpush

@endsection