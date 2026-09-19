<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="bi bi-clipboard-check me-2"></i>
                    Inspections
                </h2>
                <p class="text-muted mb-0">
                    Preventive maintenance inspection records
                </p>
            </div>

            <a href="{{ route('inspections.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i>
                New Inspection
            </a>
        </div>
    </x-slot>

    <div class="container-fluid py-4">

        {{-- Summary Card --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 rounded p-3 me-3">
                                <i class="bi bi-clipboard-check fs-3 text-success"></i>
                            </div>

                            <div>
                                <h6 class="text-muted mb-1">
                                    Total Inspections
                                </h6>

                                <h3 class="fw-bold mb-0">
                                    {{ $inspections->total() }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"></button>
            </div>
        @endif

        {{-- Error Message --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"></button>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Please correct the following errors:
                </strong>

                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Inspections Table --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-list-check me-2"></i>
                        Inspection Records
                    </h5>

                    <span class="badge bg-success">
                        {{ $inspections->total() }} Records
                    </span>
                </div>
            </div>

            <div class="card-body p-0">

                @if ($inspections->count() > 0)

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Installation</th>
                                    <th>Inspector</th>
                                    <th>Inspection Date</th>
                                    <th>Overall Condition</th>
                                    <th>Next Inspection</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($inspections as $inspection)

                                    @php
                                        $condition = $inspection->overall_condition ?? 'N/A';

                                        $conditionClass = match ($condition) {
                                            'Excellent' => 'success',
                                            'Good' => 'primary',
                                            'Fair' => 'warning',
                                            'Poor' => 'danger',
                                            'Critical' => 'dark',
                                            default => 'secondary',
                                        };

                                        $nextInspection = $inspection->next_inspection_date;

                                        $isDue = false;
                                        $isDueSoon = false;

                                        if ($nextInspection) {
                                            $isDue = $nextInspection->isPast() ||
                                                     $nextInspection->isToday();

                                            $isDueSoon = !$isDue &&
                                                         now()->diffInDays($nextInspection) <= 7;
                                        }
                                    @endphp

                                    <tr>

                                        {{-- Number --}}
                                        <td class="fw-semibold">
                                            {{ $inspections->firstItem() + $loop->index }}
                                        </td>

                                        {{-- Installation --}}
                                        <td>
                                            <div class="fw-semibold">
                                                {{ $inspection->installation?->name ?? 'N/A' }}
                                            </div>

                                            @if ($inspection->installation?->location)
                                                <small class="text-muted">
                                                    <i class="bi bi-geo-alt me-1"></i>
                                                    {{ $inspection->installation->location }}
                                                </small>
                                            @endif
                                        </td>

                                        {{-- Inspector --}}
                                        <td>
                                            @if ($inspection->inspector)
                                                <div class="fw-semibold">
                                                    {{ $inspection->inspector->name }}
                                                </div>

                                                @if ($inspection->inspector->email)
                                                    <small class="text-muted">
                                                        {{ $inspection->inspector->email }}
                                                    </small>
                                                @endif
                                            @else
                                                <span class="text-muted">
                                                    N/A
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Inspection Date --}}
                                        <td>
                                            @if ($inspection->inspection_date)
                                                <span>
                                                    <i class="bi bi-calendar3 me-1 text-muted"></i>
                                                    {{ $inspection->inspection_date->format('d M Y') }}
                                                </span>
                                            @else
                                                <span class="text-muted">
                                                    N/A
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Overall Condition --}}
                                        <td>
                                            <span class="badge bg-{{ $conditionClass }}">
                                                {{ $condition }}
                                            </span>
                                        </td>

                                        {{-- Next Inspection --}}
                                        <td>

                                            @if ($nextInspection)

                                                <div>
                                                    {{ $nextInspection->format('d M Y') }}
                                                </div>

                                                @if ($isDue)
                                                    <span class="badge bg-danger mt-1">
                                                        <i class="bi bi-exclamation-circle me-1"></i>
                                                        Due
                                                    </span>
                                                @elseif ($isDueSoon)
                                                    <span class="badge bg-warning text-dark mt-1">
                                                        <i class="bi bi-clock me-1"></i>
                                                        Due Soon
                                                    </span>
                                                @else
                                                    <span class="badge bg-success mt-1">
                                                        <i class="bi bi-check-circle me-1"></i>
                                                        Scheduled
                                                    </span>
                                                @endif

                                            @else
                                                <span class="text-muted">
                                                    Not Set
                                                </span>
                                            @endif

                                        </td>

                                        {{-- Actions --}}
                                        <td class="text-center">

                                            <div class="btn-group"
                                                 role="group"
                                                 aria-label="Inspection actions">

                                                {{-- View --}}
                                                <a href="{{ route('inspections.show', $inspection) }}"
                                                   class="btn btn-sm btn-outline-info"
                                                   title="View Inspection">
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                                {{-- Edit --}}
                                                <a href="{{ route('inspections.edit', $inspection) }}"
                                                   class="btn btn-sm btn-outline-warning"
                                                   title="Edit Inspection">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>

                                                {{-- Print --}}
                                                <a href="{{ route('inspections.print', $inspection) }}"
                                                   class="btn btn-sm btn-outline-dark"
                                                   title="Print Inspection Report"
                                                   target="_blank"
                                                   rel="noopener">
                                                    <i class="bi bi-printer"></i>
                                                </a>

                                                {{-- Delete --}}
                                                <form action="{{ route('inspections.destroy', $inspection) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this inspection?');">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Delete Inspection">
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

                    {{-- Empty State --}}
                    <div class="text-center py-5">

                        <div class="mb-3">
                            <i class="bi bi-clipboard-x display-4 text-muted"></i>
                        </div>

                        <h5 class="fw-bold">
                            No Inspection Records
                        </h5>

                        <p class="text-muted">
                            No inspections have been recorded yet.
                        </p>

                        <a href="{{ route('inspections.create') }}"
                           class="btn btn-success">

                            <i class="bi bi-plus-circle me-1"></i>
                            Create First Inspection

                        </a>

                    </div>

                @endif

            </div>

            {{-- Pagination --}}
            @if ($inspections->hasPages())
                <div class="card-footer bg-white border-top">
                    <div class="d-flex justify-content-center">
                        {{ $inspections->links() }}
                    </div>
                </div>
            @endif

        </div>

    </div>
</x-app-layout>