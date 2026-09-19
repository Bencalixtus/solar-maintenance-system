<x-app-layout>

    <x-slot name="header">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h4 class="mb-1 fw-bold">

                    <i class="bi bi-calendar-check me-2 text-success"></i>

                    Maintenance Schedule

                </h4>

                <p class="text-muted mb-0">

                    Manage preventive maintenance activities
                    for solar-battery components.

                </p>

            </div>


            <a href="{{ route('maintenance-schedules.create') }}"
               class="btn btn-success">

                <i class="bi bi-plus-circle me-1"></i>

                New Schedule

            </a>

        </div>

    </x-slot>


    <div class="container-fluid">


        {{-- Success Message --}}

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


        {{-- Statistics --}}

        <div class="row g-3 mb-4">


            {{-- Total --}}

            <div class="col-md-6 col-xl-3">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="text-muted small">
                                    Total Schedules
                                </div>

                                <h3 class="fw-bold mb-0">
                                    {{ $totalSchedules }}
                                </h3>

                            </div>

                            <div class="text-success fs-2">

                                <i class="bi bi-calendar3"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Overdue --}}

            <div class="col-md-6 col-xl-3">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="text-muted small">
                                    Overdue
                                </div>

                                <h3 class="fw-bold text-danger mb-0">
                                    {{ $overdueSchedules }}
                                </h3>

                            </div>

                            <div class="text-danger fs-2">

                                <i class="bi bi-exclamation-triangle"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Due Today --}}

            <div class="col-md-6 col-xl-3">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="text-muted small">
                                    Due Today
                                </div>

                                <h3 class="fw-bold text-warning mb-0">
                                    {{ $dueToday }}
                                </h3>

                            </div>

                            <div class="text-warning fs-2">

                                <i class="bi bi-clock"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Completed --}}

            <div class="col-md-6 col-xl-3">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <div class="text-muted small">
                                    Completed
                                </div>

                                <h3 class="fw-bold text-success mb-0">
                                    {{ $completedSchedules }}
                                </h3>

                            </div>

                            <div class="text-success fs-2">

                                <i class="bi bi-check2-circle"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Maintenance Table --}}

        <div class="card border-0 shadow-sm">


            <div class="card-header bg-white border-bottom">

                <h5 class="mb-0 fw-bold">

                    <i class="bi bi-list-check me-2 text-success"></i>

                    Scheduled Maintenance

                </h5>

            </div>


            <div class="card-body">


                @if($schedules->count())


                    <div class="table-responsive">

                        <table class="table table-hover align-middle">


                            <thead class="table-light">

                                <tr>

                                    <th>#</th>

                                    <th>Component</th>

                                    <th>Maintenance Task</th>

                                    <th>Frequency</th>

                                    <th>Next Due</th>

                                    <th>Priority</th>

                                    <th>Status</th>

                                    <th class="text-end">
                                        Actions
                                    </th>

                                </tr>

                            </thead>


                            <tbody>


                                @foreach($schedules as $schedule)


                                    @php

                                        $today = now()->startOfDay();

                                        $isOverdue =
                                            $schedule->next_due_date &&
                                            $schedule->next_due_date->lt($today) &&
                                            $schedule->status !== 'Completed';

                                        $isDueToday =
                                            $schedule->next_due_date &&
                                            $schedule->next_due_date->isSameDay($today) &&
                                            $schedule->status !== 'Completed';

                                        $isUpcoming =
                                            $schedule->next_due_date &&
                                            $schedule->next_due_date->gt($today) &&
                                            $schedule->next_due_date->lte(
                                                $today->copy()->addDays(7)
                                            ) &&
                                            $schedule->status !== 'Completed';

                                    @endphp


                                    <tr>


                                        {{-- Number --}}

                                        <td>

                                            {{ $loop->iteration }}

                                        </td>


                                        {{-- Component --}}

                                        <td>

                                            <div class="fw-semibold">

                                                {{ $schedule->component->name ?? 'N/A' }}

                                            </div>

                                            <small class="text-muted">

                                                {{ $schedule->component->componentType->name ?? 'Component' }}

                                            </small>

                                        </td>


                                        {{-- Task --}}

                                        <td>

                                            {{ $schedule->maintenance_task }}

                                        </td>


                                        {{-- Frequency --}}

                                        <td>

                                            <span class="badge bg-light text-dark border">

                                                {{ $schedule->frequency }}

                                            </span>

                                        </td>


                                        {{-- Next Due --}}

                                        <td>


                                            @if($schedule->next_due_date)

                                                {{ $schedule->next_due_date->format('d M Y') }}


                                                @if($isOverdue)

                                                    <br>

                                                    <small class="text-danger fw-semibold">

                                                        <i class="bi bi-exclamation-circle"></i>

                                                        Overdue

                                                    </small>

                                                @elseif($isDueToday)

                                                    <br>

                                                    <small class="text-warning fw-semibold">

                                                        <i class="bi bi-clock"></i>

                                                        Due today

                                                    </small>

                                                @elseif($isUpcoming)

                                                    <br>

                                                    <small class="text-success fw-semibold">

                                                        <i class="bi bi-calendar-event"></i>

                                                        Due soon

                                                    </small>

                                                @endif


                                            @else

                                                <span class="text-muted">
                                                    Not set
                                                </span>

                                            @endif


                                        </td>


                                        {{-- Priority --}}

                                        <td>


                                            @switch($schedule->priority)


                                                @case('Critical')

                                                    <span class="badge bg-danger">
                                                        Critical
                                                    </span>

                                                    @break


                                                @case('High')

                                                    <span class="badge bg-warning text-dark">
                                                        High
                                                    </span>

                                                    @break


                                                @case('Medium')

                                                    <span class="badge bg-info text-dark">
                                                        Medium
                                                    </span>

                                                    @break


                                                @default

                                                    <span class="badge bg-secondary">
                                                        Low
                                                    </span>


                                            @endswitch


                                        </td>


                                        {{-- Status --}}

                                        <td>


                                            @if($schedule->status === 'Completed')

                                                <span class="badge bg-success">

                                                    <i class="bi bi-check-circle me-1"></i>

                                                    Completed

                                                </span>


                                            @elseif($isOverdue)

                                                <span class="badge bg-danger">

                                                    <i class="bi bi-exclamation-circle me-1"></i>

                                                    Overdue

                                                </span>


                                            @elseif($isDueToday)

                                                <span class="badge bg-warning text-dark">

                                                    <i class="bi bi-clock me-1"></i>

                                                    Due

                                                </span>


                                            @else

                                                <span class="badge bg-primary">

                                                    Scheduled

                                                </span>

                                            @endif


                                        </td>


                                        {{-- Actions --}}

                                        <td class="text-end">


                                            <div class="btn-group">


                                                {{-- View --}}

                                                <a href="{{ route('maintenance-schedules.show', $schedule) }}"
                                                   class="btn btn-sm btn-outline-success"
                                                   title="View">

                                                    <i class="bi bi-eye"></i>

                                                </a>


                                                {{-- Edit --}}

                                                <a href="{{ route('maintenance-schedules.edit', $schedule) }}"
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="Edit">

                                                    <i class="bi bi-pencil-square"></i>

                                                </a>


                                                {{-- Delete --}}

                                                <form action="{{ route('maintenance-schedules.destroy', $schedule) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this maintenance schedule?');">

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


                    {{-- Empty State --}}

                    <div class="text-center py-5">


                        <div class="mb-3">

                            <i class="bi bi-calendar-x text-muted"
                               style="font-size: 3rem;">
                            </i>

                        </div>


                        <h5 class="fw-bold">

                            No Maintenance Schedules

                        </h5>


                        <p class="text-muted">

                            Create your first preventive maintenance schedule.

                        </p>


                        <a href="{{ route('maintenance-schedules.create') }}"
                           class="btn btn-success">

                            <i class="bi bi-plus-circle me-1"></i>

                            Create Schedule

                        </a>


                    </div>


                @endif


            </div>

        </div>


    </div>

</x-app-layout>