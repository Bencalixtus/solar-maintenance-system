<x-app-layout>

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">
                    <i class="bi bi-calendar-check me-2 text-success"></i>
                    Maintenance Schedule
                </h4>
                <p class="text-muted mb-0">
                    Preventive maintenance planning and monitoring
                </p>
            </div>

            <a href="{{ route('maintenance-schedules.create') }}"
               class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i>
                Add Maintenance Schedule
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
                        data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Summary Cards --}}
        <div class="row g-3 mb-4">

            <div class="col-md-6 col-xl">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Total Schedules</p>
                                <h3 class="fw-bold mb-0">
                                    {{ $totalSchedules }}
                                </h3>
                            </div>

                            <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                <i class="bi bi-calendar3 text-success fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Overdue</p>
                                <h3 class="fw-bold mb-0 text-danger">
                                    {{ $overdueSchedules }}
                                </h3>
                            </div>

                            <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                                <i class="bi bi-exclamation-triangle text-danger fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Due Today</p>
                                <h3 class="fw-bold mb-0 text-warning">
                                    {{ $dueToday }}
                                </h3>
                            </div>

                            <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                                <i class="bi bi-clock-history text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Next 7 Days</p>
                                <h3 class="fw-bold mb-0 text-primary">
                                    {{ $upcomingSchedules }}
                                </h3>
                            </div>

                            <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                <i class="bi bi-calendar-week text-primary fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Completed</p>
                                <h3 class="fw-bold mb-0 text-success">
                                    {{ $completedSchedules }}
                                </h3>
                            </div>

                            <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                <i class="bi bi-check2-circle text-success fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        {{-- Maintenance Schedule Table --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h5 class="fw-bold mb-1">
                            <i class="bi bi-list-check me-2 text-success"></i>
                            Maintenance Activities
                        </h5>

                        <small class="text-muted">
                            Scheduled preventive maintenance activities
                        </small>
                    </div>

                </div>
            </div>

            <div class="card-body p-0">

                @if($schedules->count())

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Component</th>
                                    <th>Installation</th>
                                    <th>Maintenance Task</th>
                                    <th>Frequency</th>
                                    <th>Next Due</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($schedules as $schedule)

                                    @php
                                        $today = \Illuminate\Support\Carbon::today();

                                        $nextDue = $schedule->next_due_date
                                            ? \Illuminate\Support\Carbon::parse($schedule->next_due_date)
                                            : null;

                                        $isOverdue = $nextDue
                                            && $nextDue->lt($today)
                                            && $schedule->status !== 'Completed';

                                        $isDueToday = $nextDue
                                            && $nextDue->equalTo($today)
                                            && $schedule->status !== 'Completed';

                                        $isUpcoming = $nextDue
                                            && $nextDue->gt($today)
                                            && $nextDue->lte($today->copy()->addDays(7))
                                            && $schedule->status !== 'Completed';
                                    @endphp

                                    <tr>

                                        <td>
                                            {{ $loop->iteration }}
                                        </td>

                                        <td>
                                            <div class="fw-semibold">
                                                {{ $schedule->component->name ?? 'N/A' }}
                                            </div>

                                            @if($schedule->component?->componentType)
                                                <small class="text-muted">
                                                    {{ $schedule->component->componentType->name }}
                                                </small>
                                            @endif
                                        </td>

                                        <td>
                                            {{ $schedule->component->installation->name ?? 'N/A' }}
                                        </td>

                                        <td>
                                            {{ $schedule->maintenance_task }}
                                        </td>

                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                {{ $schedule->frequency }}
                                            </span>
                                        </td>

                                        <td>

                                            @if($nextDue)

                                                <div class="fw-semibold
                                                    {{ $isOverdue ? 'text-danger' : '' }}
                                                    {{ $isDueToday ? 'text-warning' : '' }}
                                                    {{ $isUpcoming ? 'text-primary' : '' }}">

                                                    {{ $nextDue->format('d M Y') }}

                                                </div>

                                                @if($isOverdue)
                                                    <small class="text-danger">
                                                        <i class="bi bi-exclamation-circle me-1"></i>
                                                        Overdue
                                                    </small>
                                                @elseif($isDueToday)
                                                    <small class="text-warning">
                                                        <i class="bi bi-clock me-1"></i>
                                                        Due today
                                                    </small>
                                                @elseif($isUpcoming)
                                                    <small class="text-primary">
                                                        <i class="bi bi-calendar-event me-1"></i>
                                                        Due soon
                                                    </small>
                                                @endif

                                            @else
                                                <span class="text-muted">
                                                    Not set
                                                </span>
                                            @endif

                                        </td>

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

                                        <td>

                                            @switch($schedule->status)

                                                @case('Completed')
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle me-1"></i>
                                                        Completed
                                                    </span>
                                                    @break

                                                @case('Overdue')
                                                    <span class="badge bg-danger">
                                                        <i class="bi bi-exclamation-circle me-1"></i>
                                                        Overdue
                                                    </span>
                                                    @break

                                                @case('Due')
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="bi bi-clock me-1"></i>
                                                        Due
                                                    </span>
                                                    @break

                                                @default
                                                    <span class="badge bg-primary">
                                                        <i class="bi bi-calendar-check me-1"></i>
                                                        Scheduled
                                                    </span>

                                            @endswitch

                                        </td>

                                        <td class="text-center">

                                            <div class="btn-group" role="group">

                                                <a href="{{ route('maintenance-schedules.show', $schedule) }}"
                                                   class="btn btn-sm btn-outline-success"
                                                   title="View">

                                                    <i class="bi bi-eye"></i>

                                                </a>

                                                <a href="{{ route('maintenance-schedules.edit', $schedule) }}"
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="Edit">

                                                    <i class="bi bi-pencil-square"></i>

                                                </a>

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

                    <div class="text-center py-5">

                        <i class="bi bi-calendar-x text-muted"
                           style="font-size: 60px;"></i>

                        <h5 class="mt-3 fw-bold">
                            No Maintenance Schedules
                        </h5>

                        <p class="text-muted">
                            No preventive maintenance activities have been scheduled yet.
                        </p>

                        <a href="{{ route('maintenance-schedules.create') }}"
                           class="btn btn-success">

                            <i class="bi bi-plus-circle me-1"></i>
                            Create First Schedule

                        </a>

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>