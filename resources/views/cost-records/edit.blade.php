@extends('layouts.app')

@section('title', 'Edit Cost Record')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h3 fw-bold mb-1">
                <i class="bi bi-pencil-square text-success me-2"></i>
                Edit Cost Record
            </h1>

            <p class="text-muted mb-0">
                Update the expenditure information below.
            </p>
        </div>

        <a href="{{ route('cost-records.show', $costRecord) }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back

        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-bold mb-2">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Please correct the following:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <div class="row justify-content-center">

        <div class="col-xl-9">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0 pt-4 px-4">

                    <h5 class="fw-bold mb-1">
                        Cost Information
                    </h5>

                    <p class="text-muted small mb-0">
                        Modify the selected cost record.
                    </p>

                </div>


                <div class="card-body p-4">

                    <form action="{{ route('cost-records.update', $costRecord) }}"
                          method="POST">

                        @csrf
                        @method('PUT')


                        {{-- Component --}}
                        <div class="mb-4">

                            <label for="component_id"
                                   class="form-label fw-semibold">

                                Component
                                <span class="text-danger">*</span>

                            </label>

                            <select name="component_id"
                                    id="component_id"
                                    class="form-select @error('component_id') is-invalid @enderror"
                                    required>

                                <option value="">
                                    -- Select Component --
                                </option>

                                @foreach($components as $component)

                                    <option value="{{ $component->id }}"
                                        {{ old('component_id', $costRecord->component_id) == $component->id ? 'selected' : '' }}>

                                        {{ $component->name }}

                                        @if($component->componentType)
                                            — {{ $component->componentType->name }}
                                        @endif

                                        @if($component->installation)
                                            — {{ $component->installation->name }}
                                        @endif

                                    </option>

                                @endforeach

                            </select>

                            @error('component_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        <div class="row">

                            {{-- Cost Type --}}
                            <div class="col-md-6 mb-4">

                                <label for="cost_type"
                                       class="form-label fw-semibold">

                                    Cost Type
                                    <span class="text-danger">*</span>

                                </label>

                                <select name="cost_type"
                                        id="cost_type"
                                        class="form-select @error('cost_type') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        -- Select Cost Type --
                                    </option>

                                    @foreach([
                                        'Maintenance',
                                        'Repair',
                                        'Replacement',
                                        'Parts/Materials',
                                        'Labour/Service',
                                        'Other'
                                    ] as $type)

                                        <option value="{{ $type }}"
                                            {{ old('cost_type', $costRecord->cost_type) === $type ? 'selected' : '' }}>

                                            {{ $type }}

                                        </option>

                                    @endforeach

                                </select>

                                @error('cost_type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Date --}}
                            <div class="col-md-6 mb-4">

                                <label for="cost_date"
                                       class="form-label fw-semibold">

                                    Cost Date
                                    <span class="text-danger">*</span>

                                </label>

                                <input type="date"
                                       name="cost_date"
                                       id="cost_date"
                                       value="{{ old(
                                           'cost_date',
                                           $costRecord->cost_date?->format('Y-m-d')
                                       ) }}"
                                       class="form-control @error('cost_date') is-invalid @enderror"
                                       required>

                                @error('cost_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>


                        {{-- Maintenance --}}
                        <div class="mb-4">

                            <label for="maintenance_id"
                                   class="form-label fw-semibold">

                                Related Maintenance Record
                                <span class="text-muted fw-normal">
                                    (Optional)
                                </span>

                            </label>

                            <select name="maintenance_id"
                                    id="maintenance_id"
                                    class="form-select @error('maintenance_id') is-invalid @enderror">

                                <option value="">
                                    -- No linked maintenance record --
                                </option>

                                @foreach($maintenanceRecords as $maintenance)

                                    <option value="{{ $maintenance->id }}"
                                        {{ old(
                                            'maintenance_id',
                                            $costRecord->maintenance_id
                                        ) == $maintenance->id ? 'selected' : '' }}>

                                        {{ $maintenance->maintenance_date?->format('d M Y') }}

                                        —
                                        {{ $maintenance->component->name ?? 'Unknown Component' }}

                                        —
                                        {{ $maintenance->maintenance_type }}

                                    </option>

                                @endforeach

                            </select>

                            @error('maintenance_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Description --}}
                        <div class="mb-4">

                            <label for="description"
                                   class="form-label fw-semibold">

                                Description
                                <span class="text-danger">*</span>

                            </label>

                            <textarea name="description"
                                      id="description"
                                      rows="4"
                                      class="form-control @error('description') is-invalid @enderror"
                                      required>{{ old('description', $costRecord->description) }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Amount --}}
                        <div class="mb-4">

                            <label for="amount"
                                   class="form-label fw-semibold">

                                Amount (₦)
                                <span class="text-danger">*</span>

                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    ₦
                                </span>

                                <input type="number"
                                       name="amount"
                                       id="amount"
                                       value="{{ old('amount', $costRecord->amount) }}"
                                       step="0.01"
                                       min="0"
                                       class="form-control @error('amount') is-invalid @enderror"
                                       required>

                            </div>

                            @error('amount')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Remarks --}}
                        <div class="mb-4">

                            <label for="remarks"
                                   class="form-label fw-semibold">

                                Remarks
                                <span class="text-muted fw-normal">
                                    (Optional)
                                </span>

                            </label>

                            <textarea name="remarks"
                                      id="remarks"
                                      rows="3"
                                      class="form-control @error('remarks') is-invalid @enderror">{{ old('remarks', $costRecord->remarks) }}</textarea>

                            @error('remarks')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Buttons --}}
                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('cost-records.show', $costRecord) }}"
                               class="btn btn-light border">

                                Cancel

                            </a>

                            <button type="submit"
                                    class="btn btn-success">

                                <i class="bi bi-check-circle me-1"></i>
                                Update Cost Record

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>
@endsection