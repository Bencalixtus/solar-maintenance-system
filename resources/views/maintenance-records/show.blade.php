<x-app-layout>

    <x-slot name="header">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h4 class="fw-bold mb-1">

                    <i class="bi bi-clipboard-check me-2 text-success"></i>

                    Maintenance Record Details

                </h4>

                <p class="text-muted mb-0">

                    Maintenance record
                    #{{ str_pad($maintenanceRecord->id, 5, '0', STR_PAD_LEFT) }}

                </p>

            </div>


            <div class="d-flex gap-2">

                <a href="{{ route('maintenance-records.index') }}"
                   class="btn btn-outline-secondary">

                    <i class="bi bi-arrow-left me-1"></i>

                    Back

                </a>


                <a href="{{ route(
                    'maintenance-records.edit',
                    $maintenanceRecord
                ) }}"
                   class="btn btn-success">

                    <i class="bi bi-pencil-square me-1"></i>

                    Edit

                </a>

            </div>

        </div>

    </x-slot>


    <div class="container-fluid py-3">


        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                <i class="bi bi-check-circle me-2"></i>

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        <div class="row">


            {{-- Main Details --}}
            <div class="col-xl-8">


                {{-- Status --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <small class="text-muted">
                                    Maintenance Status
                                </small>

                                <div class="mt-1">

                                    @switch($maintenanceRecord->status)

                                        @case('Completed')

                                            <span class="badge bg-success fs-6">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Completed
                                            </span>

                                            @break

                                        @case('In Progress')

                                            <span class="badge bg-warning text-dark fs-6">
                                                <i class="bi bi-hourglass-split me-1"></i>
                                                In Progress
                                            </span>

                                            @break

                                        @case('Cancelled')

                                            <span class="badge bg-danger fs-6">
                                                <i class="bi bi-x-circle me-1"></i>
                                                Cancelled
                                            </span>

                                            @break

                                        @default

                                            <span class="badge bg-primary fs-6">
                                                <i class="bi bi-clock me-1"></i>
                                                Pending
                                            </span>

                                    @endswitch

                                </div>

                            </div>


                            <div class="text-end">

                                <small class="text-muted d-block">
                                    Maintenance Type
                                </small>

                                <strong>

                                    {{ $maintenanceRecord->maintenance_type }}

                                </strong>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Component --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="fw-bold mb-0">

                            <i class="bi bi-cpu me-2 text-success"></i>

                            Component Information

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <small class="text-muted d-block">
                                    Component
                                </small>

                                <strong>
                                    {{ $maintenanceRecord->component->name ?? 'N/A' }}
                                </strong>

                            </div>


                            <div class="col-md-6">

                                <small class="text-muted d-block">
                                    Component Type
                                </small>

                                <strong>
                                    {{ $maintenanceRecord->component->componentType->name ?? 'N/A' }}
                                </strong>

                            </div>


                            <div class="col-md-6">

                                <small class="text-muted d-block">
                                    Installation
                                </small>

                                <strong>
                                    {{ $maintenanceRecord->component->installation->name ?? 'N/A' }}
                                </strong>

                            </div>


                            <div class="col-md-6">

                                <small class="text-muted d-block">
                                    Location
                                </small>

                                <strong>
                                    {{ $maintenanceRecord->component->installation->location ?? 'N/A' }}
                                </strong>

                            </div>


                            <div class="col-md-6">

                                <small class="text-muted d-block">
                                    Manufacturer
                                </small>

                                <strong>
                                    {{ $maintenanceRecord->component->manufacturer ?? 'N/A' }}
                                </strong>

                            </div>


                            <div class="col-md-6">

                                <small class="text-muted d-block">
                                    Model
                                </small>

                                <strong>
                                    {{ $maintenanceRecord->component->model ?? 'N/A' }}
                                </strong>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Maintenance Details --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="fw-bold mb-0">

                            <i class="bi bi-tools me-2 text-success"></i>

                            Maintenance Details

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3 mb-4">

                            <div class="col-md-6">

                                <small class="text-muted d-block">
                                    Maintenance Date
                                </small>

                                <strong>

                                    {{ $maintenanceRecord->maintenance_date
                                        ? $maintenanceRecord->maintenance_date->format('d M Y')
                                        : 'N/A'
                                    }}

                                </strong>

                            </div>


                            <div class="col-md-6">

                                <small class="text-muted d-block">
                                    Technician
                                </small>

                                <strong>

                                    {{ $maintenanceRecord->technician->name ?? 'N/A' }}

                                </strong>

                            </div>

                        </div>


                        <div class="mb-4">

                            <small class="text-muted d-block mb-1">
                                Description
                            </small>

                            <div class="p-3 bg-light rounded">

                                {!! nl2br(e(
                                    $maintenanceRecord->description
                                )) !!}

                            </div>

                        </div>


                        <div>

                            <small class="text-muted d-block mb-1">
                                Action Taken
                            </small>

                            <div class="p-3 bg-light rounded">

                                {!! nl2br(e(
                                    $maintenanceRecord->action_taken
                                )) !!}

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Condition --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="fw-bold mb-0">

                            <i class="bi bi-activity me-2 text-success"></i>

                            Condition Assessment

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-4">

                            <div class="col-md-6">

                                <div class="text-center p-4 bg-light rounded">

                                    <small class="text-muted d-block mb-2">
                                        Condition Before
                                    </small>

                                    <span class="badge fs-6
                                        @if($maintenanceRecord->condition_before === 'Excellent')
                                            bg-success
                                        @elseif($maintenanceRecord->condition_before === 'Good')
                                            bg-primary
                                        @elseif($maintenanceRecord->condition_before === 'Fair')
                                            bg-warning text-dark
                                        @elseif($maintenanceRecord->condition_before === 'Poor')
                                            bg-danger
                                        @else
                                            bg-dark
                                        @endif
                                    ">

                                        {{ $maintenanceRecord->condition_before }}

                                    </span>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="text-center p-4 bg-light rounded">

                                    <small class="text-muted d-block mb-2">
                                        Condition After
                                    </small>

                                    <span class="badge fs-6
                                        @if($maintenanceRecord->condition_after === 'Excellent')
                                            bg-success
                                        @elseif($maintenanceRecord->condition_after === 'Good')
                                            bg-primary
                                        @elseif($maintenanceRecord->condition_after === 'Fair')
                                            bg-warning text-dark
                                        @elseif($maintenanceRecord->condition_after === 'Poor')
                                            bg-danger
                                        @else
                                            bg-dark
                                        @endif
                                    ">

                                        {{ $maintenanceRecord->condition_after }}

                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Remarks --}}
                @if($maintenanceRecord->remarks)

                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white py-3">

                            <h5 class="fw-bold mb-0">

                                <i class="bi bi-chat-left-text me-2 text-success"></i>

                                Additional Remarks

                            </h5>

                        </div>


                        <div class="card-body">

                            {!! nl2br(e(
                                $maintenanceRecord->remarks
                            )) !!}

                        </div>

                    </div>

                @endif

            </div>


            {{-- Sidebar --}}
            <div class="col-xl-4">


                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-success text-white py-3">

                        <h6 class="fw-bold mb-0">

                            <i class="bi bi-calendar-check me-2"></i>

                            Follow-up

                        </h6>

                    </div>


                    <div class="card-body">

                        <small class="text-muted d-block">
                            Next Maintenance Due
                        </small>

                        <h5 class="fw-bold mt-1">

                            @if($maintenanceRecord->next_due_date)

                                {{ $maintenanceRecord->next_due_date->format('d M Y') }}

                            @else

                                Not specified

                            @endif

                        </h5>

                    </div>

                </div>


                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h6 class="fw-bold mb-0">

                            <i class="bi bi-person-check me-2 text-success"></i>

                            Technician

                        </h6>

                    </div>


                    <div class="card-body">

                        <h6 class="fw-bold mb-1">

                            {{ $maintenanceRecord->technician->name ?? 'N/A' }}

                        </h6>

                        <small class="text-muted">

                            Technician responsible for this maintenance record.

                        </small>

                    </div>

                </div>


                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white py-3">

                        <h6 class="fw-bold mb-0">

                            <i class="bi bi-clock-history me-2 text-success"></i>

                            Record Information

                        </h6>

                    </div>


                    <div class="card-body">

                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Record ID
                            </small>

                            <strong>

                                #{{ str_pad(
                                    $maintenanceRecord->id,
                                    5,
                                    '0',
                                    STR_PAD_LEFT
                                ) }}

                            </strong>

                        </div>


                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Created
                            </small>

                            <strong>

                                {{ $maintenanceRecord->created_at
                                    ? $maintenanceRecord->created_at->format('d M Y, h:i A')
                                    : 'N/A'
                                }}

                            </strong>

                        </div>


                        <div>

                            <small class="text-muted d-block">
                                Last Updated
                            </small>

                            <strong>

                                {{ $maintenanceRecord->updated_at
                                    ? $maintenanceRecord->updated_at->format('d M Y, h:i A')
                                    : 'N/A'
                                }}

                            </strong>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>