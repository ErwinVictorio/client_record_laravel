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
                    <livewire:auth.logout/>
                </ul>
            </li>
        </ul>
    </nav>

    {{-- sidebar --}}
    @include('partials.superAdmin_nav')

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4 text-dark fw-bold">Edit Account</h1>
                <ol class="breadcrumb mb-4 shadow-sm p-3 bg-white rounded">
                    <li class="breadcrumb-item"><a href="{{route('superAdminDashboard.view')}}"
                            class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active">Edit Account</li>
                </ol>

                <div class="row">
                    <div class="col-lg-10 mx-auto">
                        {{-- Main Form Card --}}
                        <div class="card border-0 shadow-sm rounded-4 mb-5">
                            <div class="card-header bg-white border-bottom py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                                        <i class="fas fa-user-edit text-primary"></i>
                                    </div>
                                    <h5 class="card-title m-0 fw-bold text-dark">User Information</h5>
                                </div>
                            </div>

                            <form wire:submit.prevent="update">
                                <div class="card-body p-4">
                                    {{-- Profile Section Header --}}
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h6 class="text-muted text-uppercase small fw-bold mb-3 border-bottom pb-2">
                                                Personal Details</h6>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label small fw-bold text-secondary">First Name</label>
                                            <input type="text" wire:model="first_name"
                                                class="form-control rounded-3 shadow-sm @error('first_name') is-invalid @enderror"
                                                placeholder="Enter first name">
                                            @error('first_name') <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label small fw-bold text-secondary">Middle Name</label>
                                            <input type="text" wire:model="middle_name"
                                                class="form-control rounded-3 shadow-sm" placeholder="N/A">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label small fw-bold text-secondary">Last Name</label>
                                            <input type="text" wire:model="last_name"
                                                class="form-control rounded-3 shadow-sm @error('last_name') is-invalid @enderror"
                                                placeholder="Enter last name">
                                            @error('last_name') <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small fw-bold text-secondary">Nickname</label>
                                            <input type="text" wire:model="NickName"
                                                class="form-control rounded-3 shadow-sm" placeholder="Display name">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small fw-bold text-secondary">Department</label>
                                            <select wire:model="department" class="form-select rounded-3 shadow-sm">
                                                <option value="">Select Department</option>
                                                @foreach ($departmentList as $dep)
                                                <option value="{{ $dep->department_name }}">{{ $dep->department_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Account Security Header --}}
                                    <div class="row mb-4">
                                        <div class="col-12 mt-2">
                                            <h6 class="text-muted text-uppercase small fw-bold mb-3 border-bottom pb-2">
                                                Account & Security</h6>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small fw-bold text-secondary">Username</label>
                                            <div class="input-group shadow-sm rounded-3">
                                                <span class="input-group-text bg-light border-end-0 text-muted"><i
                                                        class="fas fa-at"></i></span>
                                                <input type="text" wire:model="username"
                                                    class="form-control border-start-0 @error('username') is-invalid @enderror"
                                                    placeholder="username">
                                            </div>
                                            @error('username') <div class="invalid-feedback d-block">{{ $message }}
                                            </div> @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small fw-bold text-secondary">User Role</label>
                                            <select wire:model="role"
                                                class="form-select rounded-3 shadow-sm @error('role') is-invalid @enderror">
                                                <option value="">Select Role</option>
                                                <option value="0">Super Admin</option>
                                                <option value="1">Admin</option>
                                                <option value="2">Cashier</option>
                                            </select>
                                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label small fw-bold text-secondary">New Password (Leave
                                                blank to keep current)</label>
                                            <div class="input-group shadow-sm rounded-3">
                                                <span class="input-group-text bg-light border-end-0 text-muted"><i
                                                        class="fas fa-lock"></i></span>
                                                <input type="password" wire:model="password"
                                                    class="form-control border-start-0" placeholder="••••••••">
                                            </div>
                                            <small class="text-muted">Minimum 8 characters for security.</small>
                                        </div>
                                    </div>
                                </div>

                                {{-- Card Footer Actions --}}
                                <div
                                    class="card-footer bg-light p-4 border-top-0 d-flex justify-content-between rounded-bottom-4">
                                    <a href="#"
                                        class="btn btn-light px-4 rounded-pill fw-bold border text-secondary">
                                        <i class="fas fa-arrow-left me-2"></i> Back to List
                                    </a>
                                    <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold shadow">
                                        <i class="fas fa-save me-2"></i> Update Account
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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