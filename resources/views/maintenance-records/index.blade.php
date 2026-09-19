<x-app-layout>

    <x-slot name="header">

        <div class="d-flex justify-content-between align-items-center">

            <div>
                <h4 class="fw-bold mb-1">
                    <i class="bi bi-clipboard-check me-2 text-success"></i>
                    Maintenance Records
                </h4>

                <p class="text-muted mb-0">
                    Record and monitor completed and ongoing maintenance activities.
                </p>
            </div>

            <a href="{{ route('maintenance-records.create') }}"
               class="btn btn-success">

                <i class="bi bi-plus-circle me-1"></i>
                Add Maintenance Record

            </a>

        </div>

    </x-slot>


    <div class="container-fluid py-3">


        {{-- Success Message --}}
        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show shadow-sm"
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
        <div class="row g-3 mb-4">


            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <small class="text-muted">
                                    Total Records
                                </small>

                                <h3 class="fw-bold mb-0">
                                    {{ $totalRecords }}
                                </h3>

                            </div>

                            <div class="fs-1 text-success">
                                <i class="bi bi-clipboard-data"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <small class="text-muted">
                                    Completed
                                </small>

                                <h3 class="fw-bold mb-0 text-success">
                                    {{ $completedRecords }}
                                </h3>

                            </div>

                            <div class="fs-1 text-success">
                                <i class="bi bi-check-circle"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <small class="text-muted">
                                    In Progress
                                </small>

                                <h3 class="fw-bold mb-0 text-warning">
                                    {{ $inProgressRecords }}
                                </h3>

                            </div>

                            <div class="fs-1 text-warning">
                                <i class="bi bi-hourglass-split"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <small class="text-muted">
                                    Pending
                                </small>

                                <h3 class="fw-bold mb-0 text-primary">
                                    {{ $pendingRecords }}
                                </h3>

                            </div>

                            <div class="fs-1 text-primary">
                                <i class="bi bi-clock-history"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Records Table --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-bottom py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="fw-bold mb-0">

                        <i class="bi bi-list-check me-2 text-success"></i>

                        Maintenance Activity History

                    </h5>

                    <span class="badge bg-light text-dark">
                        {{ $totalRecords }} Records
                    </span>

                </div>

            </div>


            <div class="card-body p-0">

                @if($records->count())

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>#</th>

                                    <th>Component</th>

                                    <th>Type</th>

                                    <th>Technician</th>

                                    <th>Maintenance Date</th>

                                    <th>Before</th>

                                    <th>After</th>

                                    <th>Status</th>

                                    <th class="text-end">
                                        Actions
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($records as $record)

                                    <tr>

                                        <td class="fw-semibold">
                                            {{ str_pad(
                                                $record->id,
                                                4,
                                                '0',
                                                STR_PAD_LEFT
                                            ) }}
                                        </td>


                                        <td>

                                            <div class="fw-semibold">

                                                {{ $record->component->name ?? 'N/A' }}

                                            </div>

                                            <small class="text-muted">

                                                {{ $record->component->installation->name ?? 'N/A' }}

                                            </small>

                                        </td>


                                        <td>

                                            @switch($record->maintenance_type)

                                                @case('Preventive')
                                                    <span class="badge bg-success">
                                                        Preventive
                                                    </span>
                                                    @break

                                                @case('Corrective')
                                                    <span class="badge bg-warning text-dark">
                                                        Corrective
                                                    </span>
                                                    @break

                                                @case('Emergency')
                                                    <span class="badge bg-danger">
                                                        Emergency
                                                    </span>
                                                    @break

                                                @case('Replacement')
                                                    <span class="badge bg-dark">
                                                        Replacement
                                                    </span>
                                                    @break

                                                @default
                                                    <span class="badge bg-info">
                                                        {{ $record->maintenance_type }}
                                                    </span>

                                            @endswitch

                                        </td>


                                        <td>

                                            {{ $record->technician->name ?? 'N/A' }}

                                        </td>


                                        <td>

                                            @if($record->maintenance_date)

                                                {{ $record->maintenance_date->format('d M Y') }}

                                            @else

                                                N/A

                                            @endif

                                        </td>


                                        <td>

                                            <span class="badge
                                                @if($record->condition_before === 'Excellent')
                                                    bg-success
                                                @elseif($record->condition_before === 'Good')
                                                    bg-primary
                                                @elseif($record->condition_before === 'Fair')
                                                    bg-warning text-dark
                                                @elseif($record->condition_before === 'Poor')
                                                    bg-danger
                                                @else
                                                    bg-dark
                                                @endif
                                            ">

                                                {{ $record->condition_before }}

                                            </span>

                                        </td>


                                        <td>

                                            <span class="badge
                                                @if($record->condition_after === 'Excellent')
                                                    bg-success
                                                @elseif($record->condition_after === 'Good')
                                                    bg-primary
                                                @elseif($record->condition_after === 'Fair')
                                                    bg-warning text-dark
                                                @elseif($record->condition_after === 'Poor')
                                                    bg-danger
                                                @else
                                                    bg-dark
                                                @endif
                                            ">

                                                {{ $record->condition_after }}

                                            </span>

                                        </td>


                                        <td>

                                            @switch($record->status)

                                                @case('Completed')

                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle me-1"></i>
                                                        Completed
                                                    </span>

                                                    @break

                                                @case('In Progress')

                                                    <span class="badge bg-warning text-dark">
                                                        <i class="bi bi-hourglass-split me-1"></i>
                                                        In Progress
                                                    </span>

                                                    @break

                                                @case('Cancelled')

                                                    <span class="badge bg-danger">
                                                        <i class="bi bi-x-circle me-1"></i>
                                                        Cancelled
                                                    </span>

                                                    @break

                                                @default

                                                    <span class="badge bg-primary">
                                                        <i class="bi bi-clock me-1"></i>
                                                        Pending
                                                    </span>

                                            @endswitch

                                        </td>


                                        <td class="text-end">

                                            <div class="btn-group">

                                                <a href="{{ route(
                                                    'maintenance-records.show',
                                                    $record
                                                ) }}"
                                                   class="btn btn-sm btn-outline-success"
                                                   title="View">

                                                    <i class="bi bi-eye"></i>

                                                </a>


                                                <a href="{{ route(
                                                    'maintenance-records.edit',
                                                    $record
                                                ) }}"
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="Edit">

                                                    <i class="bi bi-pencil-square"></i>

                                                </a>


                                                <form action="{{ route(
                                                    'maintenance-records.destroy',
                                                    $record
                                                ) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm(
                                                          'Are you sure you want to delete this maintenance record?'
                                                      );">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Delete">

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

                        <div class="display-4 text-muted mb-3">
                            <i class="bi bi-clipboard-x"></i>
                        </div>

                        <h5 class="fw-bold">
                            No Maintenance Records
                        </h5>

                        <p class="text-muted">
                            No maintenance activities have been recorded yet.
                        </p>

                        <a href="{{ route('maintenance-records.create') }}"
                           class="btn btn-success">

                            <i class="bi bi-plus-circle me-1"></i>

                            Record Maintenance

                        </a>

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>