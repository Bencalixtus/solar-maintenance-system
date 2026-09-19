<x-app-layout>
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <div>
                        <h3 class="mb-1 fw-bold">
                            <i class="bi bi-plus-circle text-success me-2"></i>
                            Add Component Type
                        </h3>

                        <p class="text-muted mb-0">
                            Create a category for components used in solar installations.
                        </p>
                    </div>

                    <a href="{{ route('component-types.index') }}"
                       class="btn btn-outline-secondary">

                        <i class="bi bi-arrow-left me-1"></i>
                        Back to Component Types

                    </a>

                </div>

            </div>
        </div>


        <!-- Validation Errors -->
        @if ($errors->any())

            <div class="alert alert-danger alert-dismissible fade show"
                 role="alert">

                <div class="d-flex align-items-start">

                    <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>

                    <div>

                        <strong>
                            Please correct the following errors:
                        </strong>

                        <ul class="mb-0 mt-2">

                            @foreach ($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                </div>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        <!-- Form -->
        <div class="row justify-content-center">

            <div class="col-xl-8 col-lg-9">

                <div class="card shadow-sm border-0">

                    <!-- Card Header -->
                    <div class="card-header bg-success text-white py-3">

                        <h5 class="mb-0">

                            <i class="bi bi-tags me-2"></i>

                            Component Type Information

                        </h5>

                    </div>


                    <!-- Form -->
                    <form action="{{ route('component-types.store') }}"
                          method="POST">

                        @csrf

                        <div class="card-body p-4">

                            <!-- Component Type Name -->
                            <div class="mb-4">

                                <label for="name"
                                       class="form-label fw-semibold">

                                    Component Type Name

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>

                                <input type="text"
                                       name="name"
                                       id="name"
                                       value="{{ old('name') }}"
                                       class="form-control form-control-lg @error('name') is-invalid @enderror"
                                       placeholder="e.g. Solar Panel"
                                       required>

                                @error('name')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                                <small class="text-muted">
                                    Enter the category name used to classify
                                    components in the solar installation.
                                </small>

                            </div>


                            <!-- Description -->
                            <div class="mb-4">

                                <label for="description"
                                       class="form-label fw-semibold">

                                    Description

                                </label>

                                <textarea name="description"
                                          id="description"
                                          rows="5"
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="Describe this type of component...">{{ old('description') }}</textarea>

                                @error('description')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                                <small class="text-muted">
                                    Optional. Explain what this component
                                    type represents and its role in the
                                    solar-battery system.
                                </small>

                            </div>


                            <!-- Examples -->
                            <div class="alert alert-light border">

                                <div class="d-flex">

                                    <i class="bi bi-lightbulb text-warning fs-4 me-3"></i>

                                    <div>

                                        <h6 class="fw-bold mb-2">
                                            Suggested Component Types
                                        </h6>

                                        <div class="d-flex flex-wrap gap-2">

                                            <span class="badge bg-success">
                                                Solar Panel
                                            </span>

                                            <span class="badge bg-success">
                                                Battery
                                            </span>

                                            <span class="badge bg-success">
                                                Inverter
                                            </span>

                                            <span class="badge bg-success">
                                                Charge Controller
                                            </span>

                                            <span class="badge bg-secondary">
                                                Other
                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- Footer -->
                        <div class="card-footer bg-light p-3">

                            <div class="d-flex justify-content-end gap-2">

                                <a href="{{ route('component-types.index') }}"
                                   class="btn btn-outline-secondary">

                                    <i class="bi bi-x-circle me-1"></i>

                                    Cancel

                                </a>


                                <button type="submit"
                                        class="btn btn-success">

                                    <i class="bi bi-check-circle me-1"></i>

                                    Save Component Type

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>
</x-app-layout>