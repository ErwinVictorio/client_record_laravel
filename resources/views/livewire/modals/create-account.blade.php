<div wire:ignore.self class="modal fade" tabindex="-1" id="CreateAccountModal" style="background: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background-color: #004998">
                <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Create New Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- Alert Messages inside Modal --}}
            @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            <form wire:submit.prevent="save">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">First Name</label>
                            <input type="text" wire:model="first_name"
                                class="form-control @error('first_name') is-invalid @enderror" placeholder="John">
                            @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Middle Name</label>
                            <input type="text" wire:model="middle_name" class="form-control" placeholder="N/A">
                            @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Last Name</label>
                            <input type="text" wire:model="last_name"
                                class="form-control @error('last_name') is-invalid @enderror" placeholder="Doe">
                            @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nickname</label>
                            <input type="text" wire:model="NickName" class="form-control" placeholder="Johnny">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Department</label>
                            <select wire:model="department"
                                class="form-select @error('department') is-invalid @enderror">
                                <option value="">Select Department</option>
                                @foreach ($departmentList as $dep)
                                <option value="{{ $dep->department_name }}">{{ $dep->department_name }}</option>
                                @endforeach

                            </select>
                            @error('department') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Username</label>
                            <input type="text" wire:model="username"
                                class="form-control @error('username') is-invalid @enderror">
                            @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Role</label>
                            <select wire:model="role" class="form-select @error('role') is-invalid @enderror">
                                <option value="">Select Role</option>
                                <option value="0">Super Admin</option>
                                <option value="1">Admin</option>
                                <option value="2">Cashier</option>
                                <option value="3">Sales Executive</option>
                                <option value="4">After Sales</option>
                                <option value="5">Warehouse</option>
                            </select>
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold">Password</label>
                            <input type="password" wire:model="password"
                                class="form-control @error('password') is-invalid @enderror">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-primary px-4">
                        Create Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
