<x-app-layout>
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <div>
                        <h3 class="mb-1 fw-bold">
                            <i class="bi bi-tags text-success me-2"></i>
                            Component Types
                        </h3>

                        <p class="text-muted mb-0">
                            Manage the categories of components used in solar installations.
                        </p>
                    </div>

                    <a href="{{ route('component-types.create') }}"
                       class="btn btn-success">

                        <i class="bi bi-plus-circle me-1"></i>
                        Add Component Type

                    </a>

                </div>

            </div>
        </div>


        <!-- Success Message -->
        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show"
                 role="alert">

                <i class="bi bi-check-circle-fill me-2"></i>

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        <!-- Error Message -->
        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show"
                 role="alert">

                <i class="bi bi-exclamation-triangle-fill me-2"></i>

                {{ session('error') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        <!-- Statistics -->
        <div class="row g-3 mb-4">

            <div class="col-md-4">

                <div class="card shadow-sm border-0">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                Total Component Types
                            </small>

                            <h3 class="fw-bold mb-0">
                                {{ $componentTypes->total() }}
                            </h3>

                        </div>

                        <div class="text-success fs-1">
                            <i class="bi bi-tags"></i>
                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="card shadow-sm border-0">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                Solar Components
                            </small>

                            <h3 class="fw-bold mb-0">
                                {{ $componentTypes->sum('components_count') }}
                            </h3>

                        </div>

                        <div class="text-warning fs-1">
                            <i class="bi bi-cpu"></i>
                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="card shadow-sm border-0">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                Current Page
                            </small>

                            <h3 class="fw-bold mb-0">
                                {{ $componentTypes->currentPage() }}
                            </h3>

                        </div>

                        <div class="text-success fs-1">
                            <i class="bi bi-list-ul"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Component Type List -->
        <div class="card shadow-sm border-0">

            <div class="card-header py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-list-check text-success me-2"></i>
                        Registered Component Types
                    </h5>

                    <span class="badge bg-success">
                        {{ $componentTypes->total() }}
                    </span>

                </div>

            </div>


            <div class="card-body p-0">

                @if($componentTypes->count() > 0)

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th class="px-4">
                                        #
                                    </th>

                                    <th>
                                        Component Type
                                    </th>

                                    <th>
                                        Description
                                    </th>

                                    <th>
                                        Components
                                    </th>

                                    <th class="text-end px-4">
                                        Actions
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($componentTypes as $componentType)

                                    <tr>

                                        <td class="px-4">

                                            {{ $componentTypes->firstItem() + $loop->index }}

                                        </td>


                                        <td>

                                            <div class="d-flex align-items-center">

                                                <div class="bg-success bg-opacity-10
                                                            rounded-circle p-2 me-3">

                                                    <i class="bi bi-cpu text-success"></i>

                                                </div>

                                                <div>

                                                    <div class="fw-semibold">
                                                        {{ $componentType->name }}
                                                    </div>

                                                </div>

                                            </div>

                                        </td>


                                        <td>

                                            @if($componentType->description)

                                                {{ Str::limit($componentType->description, 70) }}

                                            @else

                                                <span class="text-muted fst-italic">
                                                    No description
                                                </span>

                                            @endif

                                        </td>


                                        <td>

                                            <span class="badge bg-light text-dark border">

                                                {{ $componentType->components_count }}

                                                {{ $componentType->components_count == 1
                                                    ? 'Component'
                                                    : 'Components' }}

                                            </span>

                                        </td>


                                        <td class="text-end px-4">

                                            <div class="btn-group">

                                                <a href="{{ route('component-types.show', $componentType) }}"
                                                   class="btn btn-sm btn-outline-success"
                                                   title="View">

                                                    <i class="bi bi-eye"></i>

                                                </a>


                                                <a href="{{ route('component-types.edit', $componentType) }}"
                                                   class="btn btn-sm btn-outline-warning"
                                                   title="Edit">

                                                    <i class="bi bi-pencil"></i>

                                                </a>


                                                <form action="{{ route('component-types.destroy', $componentType) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this component type?');">

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


                    <!-- Pagination -->
                    @if($componentTypes->hasPages())

                        <div class="p-3 border-top">

                            {{ $componentTypes->links() }}

                        </div>

                    @endif

                @else

                    <div class="text-center py-5">

                        <div class="mb-3">

                            <i class="bi bi-tags"
                               style="font-size: 3.5rem; color: #198754;">
                            </i>

                        </div>

                        <h5 class="fw-semibold">
                            No Component Types Found
                        </h5>

                        <p class="text-muted mb-3">
                            Create your first component type to begin registering
                            solar system components.
                        </p>

                        <a href="{{ route('component-types.create') }}"
                           class="btn btn-success">

                            <i class="bi bi-plus-circle me-1"></i>
                            Add First Component Type

                        </a>

                    </div>

                @endif

            </div>

        </div>

    </div>
</x-app-layout>