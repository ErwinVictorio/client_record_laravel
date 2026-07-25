<div>
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="/admin/dashboard">Dashboard</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">

        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li>
                        <livewire:auth.logout />
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    @include('partials.admin_nav')

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Manage Suffix (管理后缀)</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Manage Suffix</li>
                </ol>
                {{-- Main Content Here --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="card shadow-sm">
                            <div class="card-header">Add Company Suffix</div>
                            <div class="card-body">
                                <form wire:submit="createSuffix">
                                    <div class="mb-3">
                                        <label for="newSuffix" class="form-label">Suffix</label>
                                        <input wire:model="newSuffix" type="text" class="form-control @error('newSuffix') is-invalid @enderror" id="newSuffix" placeholder="e.g. Inc., LLC, Ltd.">
                                        @error('newSuffix')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button style="background-color: #004998" type="submit" class="btn text-light" wire:loading.attr="disabled">
                                        <i class="fas fa-plus"></i> Add Suffix
                                    </button>
                                </form>
                            </div>
                        </div>

                        @if ($editingSuffixId !== null)
                            <div class="card shadow-sm mt-4">
                                <div class="card-header">Edit Company Suffix</div>
                                <div class="card-body">
                                    <form wire:submit="updateSuffix">
                                        <div class="mb-3">
                                            <label for="editingSuffix" class="form-label">Suffix</label>
                                            <input wire:model="editingSuffix" type="text" class="form-control @error('editingSuffix') is-invalid @enderror" id="editingSuffix">
                                            @error('editingSuffix')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <button style="background-color: #004998" type="submit" class="btn text-light" wire:loading.attr="disabled">
                                            <i class="fas fa-save"></i> Save Changes
                                        </button>
                                        <button wire:click="cancelEditing" type="button" class="btn btn-secondary">Cancel</button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="col-lg-8">
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header">
                                <i class="fas fa-list me-1"></i>
                                Company Suffix List
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th class="ps-3">#</th>
                                                <th>Suffix</th>
                                                <th class="text-end pe-3">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($suffixes as $suffix)
                                                <tr wire:key="suffix-{{ $suffix->id }}">
                                                    <td class="ps-3">{{ $loop->iteration }}</td>
                                                    <td>{{ $suffix->suffix }}</td>
                                                    <td class="text-end pe-3">
                                                        <button wire:click="startEditing({{ $suffix->id }})" type="button" class="btn btn-sm text-light" style="background-color: #004998">
                                                            <i class="fas fa-pen"></i> Edit
                                                        </button>
                                                        <button wire:click="deleteSuffix({{ $suffix->id }})" wire:confirm="Delete the suffix '{{ $suffix->suffix }}'?" type="button" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted py-4">No company suffixes have been added yet.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container-fluid p-2 d-none">
                    <button style="background-color: #004998" data-bs-toggle="modal" data-bs-target="#createModalDepartment" class="btn rounded-0 text-light">
                        Add New Suffix (新后缀)
                    </button>
                </div>
            </div>
        </main>
    </div>
</div>
