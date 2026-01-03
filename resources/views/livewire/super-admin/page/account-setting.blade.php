<div>
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href={{route('superAdminDashboard.view')}}>Dashboard</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0"></form>
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <livewire:auth.logout />
                </ul>
            </li>
        </ul>
    </nav>

    {{-- sidebar --}}
    @include('partials.superAdmin_nav')

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4 text-dark fw-bold">Accounts</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="{{route('superAdminDashboard.view')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Account  Settings</li>
                </ol>

                {{-- Alert Messages --}}
                @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user-cog me-2"></i>User
                                    Management</h6>
                            </div>
                            {{-- Search Input --}}
                            <div class="col-md-5 mt-2 mt-md-0">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="fas fa-search text-muted"></i></span>
                                    <input wire:model.live.debounce.300ms="searchQuery" type="text"
                                        class="form-control bg-light border-start-0"
                                        placeholder="Search by name or username...">
                                </div>
                            </div>
                            <div class="col-md-3 text-md-end mt-2 mt-md-0">
                                <button data-bs-toggle="modal" data-bs-target="#CreateAccountModal"
                                    class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                                    <i class="fas fa-plus-circle me-1"></i> New Account
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0"> {{-- Removed padding for edge-to-edge table look --}}
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 ps-4">User</th>
                                        <th class="border-0">Department</th>
                                        <th class="border-0">Role</th>
                                        <th class="border-0 text-center pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($usersAccounts as $user)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-3 d-flex align-items-center justify-content-center text-white {{ $roleMap[$user->role]['class'] ?? 'bg-secondary' }}"
                                                    style="width: 40px; height: 40px; font-weight: 600;">
                                                    {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <span class="fw-bold d-block text-dark">
                                                        {{ $user->first_name }} {{ $user->last_name }}
                                                        @if($user->id === auth()->id())
                                                        <span class="badge bg-primary-subtle text-primary ms-1"
                                                            style="font-size: 0.65rem; letter-spacing: 0.5px;">YOU</span>
                                                        @endif
                                                    </span>
                                                    <small class="text-muted">@ {{ $user->username }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->department }}</td>
                                        <td>
                                            <span
                                                class="badge rounded-pill {{ $roleMap[$user->role]['class'] ?? 'bg-secondary' }}"
                                                style="font-weight: 500;">
                                                {{ $roleMap[$user->role]['label'] ?? 'Unknown' }}
                                            </span>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('edit-account.view', $user->id) }}"
                                                    class="btn btn-outline-light btn-sm text-primary border"
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                @if($user->id !== auth()->id())
                                                <button
                                                    onclick="confirm('Are you sure you want to delete this account?') || event.stopImmediatePropagation()"
                                                    wire:click="deleteAccount({{ $user->id }})"
                                                    class="btn btn-outline-light btn-sm text-danger border"
                                                    title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                @else
                                                <button class="btn btn-light btn-sm border disabled"
                                                    title="Cannot delete self" disabled>
                                                    <i class="fas fa-trash-alt text-muted"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <img src="https://illustrations.popsy.co/gray/searching.svg"
                                                alt="No results" style="width: 150px;" class="mb-3">
                                            <p class="text-muted">No accounts found matching "{{ $searchQuery }}"</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- Pagination Footer --}}
                    @if($usersAccounts->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="small text-muted">
                                Showing {{ $usersAccounts->firstItem() }} to {{ $usersAccounts->lastItem() }} of {{
                                $usersAccounts->total() }} entries
                            </div>
                            <div>
                                {{ $usersAccounts->links() }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- modal for Creating Accounts --}}
            <livewire:modals.create-account />

        </main>

        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid px-4">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Copyright &copy; Your Website 2026</div>
                    <div>
                        <a href="#">Privacy Policy</a>
                        &middot;
                        <a href="#">Terms &amp; Conditions</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>


</script>