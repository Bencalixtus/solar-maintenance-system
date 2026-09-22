@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="bi bi-people me-2 text-success"></i>
                User Management
            </h1>

            <p class="text-muted mb-0">
                Manage system users, roles and account status.
            </p>
        </div>

        <a href="{{ route('users.create') }}" class="btn btn-success">
            <i class="bi bi-person-plus me-1"></i>
            Add User
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics -->

    <div class="row g-3 mb-4">

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-muted small">Total Users</div>
                            <div class="fs-3 fw-bold">
                                {{ $totalUsers }}
                            </div>
                        </div>

                        <div class="text-success fs-2">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-muted small">Active Users</div>
                            <div class="fs-3 fw-bold text-success">
                                {{ $activeUsers }}
                            </div>
                        </div>

                        <div class="text-success fs-2">
                            <i class="bi bi-person-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-muted small">Inactive Users</div>
                            <div class="fs-3 fw-bold text-secondary">
                                {{ $inactiveUsers }}
                            </div>
                        </div>

                        <div class="text-secondary fs-2">
                            <i class="bi bi-person-x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-muted small">Administrators</div>
                            <div class="fs-3 fw-bold text-warning">
                                {{ $adminUsers }}
                            </div>
                        </div>

                        <div class="text-warning fs-2">
                            <i class="bi bi-shield-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Users Table -->

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                <i class="bi bi-person-lines-fill me-2 text-success"></i>
                System Users
            </h5>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Registered</th>
                            <th class="text-end">Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($users as $user)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    <div class="d-flex align-items-center">

                                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-2"
                                             style="width:40px;height:40px;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>

                                        <div>
                                            <div class="fw-semibold">
                                                {{ $user->name }}
                                            </div>

                                            @if($user->id === auth()->id())
                                                <span class="badge bg-light text-success">
                                                    You
                                                </span>
                                            @endif
                                        </div>

                                    </div>
                                </td>

                                <td>
                                    {{ $user->email }}
                                </td>

                                <td>
                                    {{ $user->phone ?: '—' }}
                                </td>

                                <td>

                                    @if($user->role === 'Admin')
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-shield-check me-1"></i>
                                            Admin
                                        </span>

                                    @elseif($user->role === 'Supervisor')
                                        <span class="badge bg-primary">
                                            <i class="bi bi-person-badge me-1"></i>
                                            Supervisor
                                        </span>

                                    @else
                                        <span class="badge bg-info">
                                            <i class="bi bi-tools me-1"></i>
                                            Technician
                                        </span>
                                    @endif

                                </td>

                                <td>

                                    @if($user->status === 'Active')
                                        <span class="badge bg-success">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            Inactive
                                        </span>
                                    @endif

                                </td>

                                <td>
                                    {{ $user->created_at?->format('d M Y') }}
                                </td>

                                <td class="text-end">

                                    <div class="btn-group">

                                        <a href="{{ route('users.show', $user) }}"
                                           class="btn btn-sm btn-outline-secondary"
                                           title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('users.edit', $user) }}"
                                           class="btn btn-sm btn-outline-success"
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        @if($user->id !== auth()->id())

                                            <form method="POST"
                                                  action="{{ route('users.destroy', $user) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete this user?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>

                                            </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="text-center py-5">

                                    <i class="bi bi-people fs-1 text-muted"></i>

                                    <h5 class="mt-3">
                                        No users found
                                    </h5>

                                    <p class="text-muted">
                                        Create the first system user.
                                    </p>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection