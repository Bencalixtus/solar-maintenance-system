<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="h4 mb-1">
                <i class="fas fa-edit text-primary me-2"></i>
                Edit Inspection
            </h2>

            <p class="text-muted mb-0 small">
                Update inspection findings, measurements and recommendations.
            </p>
        </div>
    </x-slot>

    <div class="container-fluid py-4">

        @if($errors->any())

            <div class="alert alert-danger">

                <strong>
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Please correct the following errors:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif


        <form
            action="{{ route('inspections.update', $inspection) }}"
            method="POST"
        >

            @csrf
            @method('PUT')


            {{-- INFORMATION --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        <i class="fas fa-info-circle text-success me-2"></i>
                        Inspection Information
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Installation
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="installation_id"
                                id="installation_id"
                                class="form-select"
                                required
                            >

                                @foreach($installations as $installation)

                                    <option
                                        value="{{ $installation->id }}"
                                        {{ $inspection->installation_id == $installation->id ? 'selected' : '' }}
                                    >
                                        {{ $installation->name }}
                                        -
                                        {{ $installation->location }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-md-3">

                            <label class="form-label fw-semibold">
                                Inspection Date
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="date"
                                name="inspection_date"
                                class="form-control"
                                value="{{ old(
                                    'inspection_date',
                                    $inspection->inspection_date?->format('Y-m-d')
                                ) }}"
                                required
                            >

                        </div>


                        <div class="col-md-3">

                            <label class="form-label fw-semibold">
                                Next Inspection
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="date"
                                name="next_inspection_date"
                                class="form-control"
                                value="{{ old(
                                    'next_inspection_date',
                                    $inspection->next_inspection_date?->format('Y-m-d')
                                ) }}"
                                required
                            >

                        </div>


                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Overall Condition
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="overall_condition"
                                class="form-select"
                                required
                            >

                                @foreach([
                                    'Excellent',
                                    'Good',
                                    'Fair',
                                    'Poor',
                                    'Critical'
                                ] as $condition)

                                    <option
                                        value="{{ $condition }}"
                                        {{ old(
                                            'overall_condition',
                                            $inspection->overall_condition
                                        ) == $condition ? 'selected' : '' }}
                                    >
                                        {{ $condition }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>

                </div>

            </div>


            {{-- EXISTING CHECKLIST --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        <i class="fas fa-tasks text-success me-2"></i>

                        Inspection Checklist

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
                                    <th>Unit</th>
                                    <th>Condition</th>
                                    <th>Remarks</th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($inspection->inspectionItems as $index => $item)

                                    <tr>

                                        <td>
                                            {{ $index + 1 }}

                                            <input
                                                type="hidden"
                                                name="items[{{ $index }}][component_id]"
                                                value="{{ $item->component_id }}"
                                            >

                                            <input
                                                type="hidden"
                                                name="items[{{ $index }}][check_item]"
                                                value="{{ $item->check_item }}"
                                            >

                                            <input
                                                type="hidden"
                                                name="items[{{ $index }}][unit]"
                                                value="{{ $item->unit }}"
                                            >

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

                                            <select
                                                name="items[{{ $index }}][result]"
                                                class="form-select form-select-sm"
                                                required
                                            >

                                                @foreach([
                                                    'Pass',
                                                    'Fail',
                                                    'Needs Attention',
                                                    'Not Applicable'
                                                ] as $result)

                                                    <option
                                                        value="{{ $result }}"
                                                        {{ $item->result == $result ? 'selected' : '' }}
                                                    >
                                                        {{ $result }}
                                                    </option>

                                                @endforeach

                                            </select>

                                        </td>


                                        <td>

                                            <input
                                                type="number"
                                                step="0.01"
                                                name="items[{{ $index }}][measurement]"
                                                class="form-control form-control-sm"
                                                value="{{ $item->measurement }}"
                                            >

                                        </td>


                                        <td>
                                            {{ $item->unit ?: '-' }}
                                        </td>


                                        <td>

                                            <select
                                                name="items[{{ $index }}][condition]"
                                                class="form-select form-select-sm"
                                                required
                                            >

                                                @foreach([
                                                    'Excellent',
                                                    'Good',
                                                    'Fair',
                                                    'Poor',
                                                    'Critical'
                                                ] as $condition)

                                                    <option
                                                        value="{{ $condition }}"
                                                        {{ $item->condition == $condition ? 'selected' : '' }}
                                                    >
                                                        {{ $condition }}
                                                    </option>

                                                @endforeach

                                            </select>

                                        </td>


                                        <td>

                                            <input
                                                type="text"
                                                name="items[{{ $index }}][remarks]"
                                                class="form-control form-control-sm"
                                                value="{{ $item->remarks }}"
                                            >

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- ASSESSMENT --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        <i class="fas fa-file-alt text-success me-2"></i>
                        Inspection Assessment
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                General Observation
                            </label>

                            <textarea
                                name="general_observation"
                                class="form-control"
                                rows="6"
                                placeholder="Describe the general condition observed..."
                            >{{ old(
                                'general_observation',
                                $inspection->general_observation
                            ) }}</textarea>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Recommendation
                            </label>

                            <textarea
                                name="recommendation"
                                class="form-control"
                                rows="6"
                                placeholder="Enter recommended actions..."
                            >{{ old(
                                'recommendation',
                                $inspection->recommendation
                            ) }}</textarea>

                        </div>


                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Additional Remarks
                            </label>

                            <textarea
                                name="remarks"
                                class="form-control"
                                rows="3"
                            >{{ old(
                                'remarks',
                                $inspection->remarks
                            ) }}</textarea>

                        </div>

                    </div>

                </div>

            </div>


            {{-- BUTTONS --}}
            <div class="d-flex justify-content-between">

                <a
                    href="{{ route('inspections.show', $inspection) }}"
                    class="btn btn-outline-secondary"
                >
                    <i class="fas fa-arrow-left me-1"></i>
                    Cancel
                </a>


                <button
                    type="submit"
                    class="btn btn-primary px-4"
                >
                    <i class="fas fa-save me-1"></i>
                    Update Inspection
                </button>

            </div>

        </form>

    </div>

</x-app-layout>