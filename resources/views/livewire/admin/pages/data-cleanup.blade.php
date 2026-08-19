<div class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="{{ route('admin.dashboard') }}">Dashboard</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" type="button"><i class="fas fa-bars"></i></button>
        <ul class="navbar-nav ms-auto me-3">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end"><li><livewire:auth.logout /></li></ul>
            </li>
        </ul>
    </nav>

    @include('partials.admin_nav')

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4 pb-5">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mt-4 mb-3">
                    <div>
                        <h1 class="h3 mb-1">Permanent Data Cleanup</h1>
                        <p class="text-muted mb-0">Filter client records by creation date and status before permanent deletion.</p>
                    </div>
                    <span class="badge bg-danger fs-6"><i class="fas fa-exclamation-triangle me-1"></i> Permanent Delete</span>
                </div>

                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Data Cleanup</li>
                </ol>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="alert alert-danger border-danger">
                    <strong>This action cannot be undone.</strong> Deleted clients and their supporting documents cannot be restored. Linked After Sales records will remain, but their client link will be cleared.
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white fw-semibold py-3"><i class="fas fa-filter me-2 text-primary"></i>Cleanup Filters</div>
                    <div class="card-body">
                        <form wire:submit.prevent="applyFilters" class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label for="cleanupStartDate" class="form-label">Start Date</label>
                                <input id="cleanupStartDate" type="date" wire:model="startDate" class="form-control @error('startDate') is-invalid @enderror">
                                @error('startDate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="cleanupEndDate" class="form-label">End Date</label>
                                <input id="cleanupEndDate" type="date" wire:model="endDate" class="form-control @error('endDate') is-invalid @enderror">
                                @error('endDate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="cleanupStatus" class="form-label">Status</label>
                                <select id="cleanupStatus" wire:model="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="">Select status</option>
                                    <option value="Pending">Pending</option>
                                    <option value="For Approval">For Approval</option>
                                    <option value="Sold">Sold</option>
                                </select>
                                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="cleanupSearch" class="form-label">Optional Search</label>
                                <input id="cleanupSearch" type="search" wire:model="search" class="form-control @error('search') is-invalid @enderror" placeholder="Client, Sales No., Salesman">
                                @error('search') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12 d-flex gap-2">
                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="applyFilters">
                                    <span wire:loading wire:target="applyFilters" class="spinner-border spinner-border-sm me-1"></span>Apply Filters
                                </button>
                                <button type="button" wire:click="resetFilters" class="btn btn-outline-secondary">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>

                @if ($filtersApplied && $clients)
                    <div class="card shadow-sm">
                        <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2 py-3">
                            <div>
                                <span class="fw-semibold">Filtered Client Records</span>
                                <span class="badge bg-secondary ms-1">{{ $clients->total() }}</span>
                                <span class="badge bg-primary ms-1">{{ count($selectedIds) }} selected</span>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" wire:click="selectCurrentPage" class="btn btn-sm btn-outline-primary">Select Current Page</button>
                                <button type="button" wire:click="clearSelection" class="btn btn-sm btn-outline-secondary">Clear Selection</button>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#permanentCleanupModal" @disabled(empty($selectedIds))>
                                    <i class="fas fa-trash-alt me-1"></i>Delete Selected
                                </button>
                            </div>
                        </div>

                        @error('selectedIds') <div class="alert alert-danger rounded-0 mb-0">{{ $message }}</div> @enderror

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Select</th>
                                        <th>Client</th>
                                        <th>Sales List No.</th>
                                        <th>Sales Executive</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Age</th>
                                        <th>After Sales Links</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($clients as $client)
                                        <tr wire:key="cleanup-client-{{ $client->id }}">
                                            <td class="ps-3"><input type="checkbox" class="form-check-input" wire:model.live="selectedIds" value="{{ $client->id }}" aria-label="Select {{ $client->display_name }}"></td>
                                            <td><div class="fw-semibold">{{ $client->display_name }}</div><small class="text-muted">{{ $client->email }}</small></td>
                                            <td>{{ $client->salesList_no ?: 'N/A' }}</td>
                                            <td>{{ $client->salesman?->first_name }} {{ $client->salesman?->last_name }}</td>
                                            <td><span class="badge {{ $client->status === 'Sold' ? 'bg-primary' : ($client->status === 'Pending' ? 'bg-warning text-dark' : 'bg-success') }}">{{ $client->status }}</span></td>
                                            <td>{{ $client->created_at?->format('M d, Y h:i A') }}</td>
                                            <td>{{ $client->created_at?->diffForHumans() }}</td>
                                            <td>
                                                @if ($client->after_sales_records_count > 0)
                                                    <span class="badge bg-danger">{{ $client->after_sales_records_count }} linked</span>
                                                @else
                                                    <span class="badge bg-secondary">None</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="8" class="text-center text-muted py-5">No client records match the selected filters.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if ($clients->hasPages()) <div class="card-footer bg-white">{{ $clients->links() }}</div> @endif
                    </div>
                @else
                    <div class="text-center text-muted border rounded bg-light py-5">
                        <i class="fas fa-filter fa-2x mb-3"></i>
                        <p class="mb-0">Choose a date range and status to preview records.</p>
                    </div>
                @endif
            </div>
        </main>
    </div>

    <div wire:ignore.self class="modal fade" id="permanentCleanupModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="permanentCleanupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="permanentCleanupModalLabel">Confirm Permanent Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="permanentlyDeleteSelected">
                    <div class="modal-body p-4">
                        <p>You are permanently deleting <strong>{{ count($selectedIds) }}</strong> selected client record(s).</p>
                        <p class="text-danger fw-semibold">This cannot be undone.</p>
                        <label for="cleanupConfirmation" class="form-label">Type <strong>DELETE</strong> to continue</label>
                        <input id="cleanupConfirmation" wire:model="confirmationText" type="text" autocomplete="off" class="form-control @error('confirmationText') is-invalid @enderror" placeholder="DELETE">
                        @error('confirmationText') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger" wire:loading.attr="disabled" wire:target="permanentlyDeleteSelected">
                            <span wire:loading wire:target="permanentlyDeleteSelected" class="spinner-border spinner-border-sm me-1"></span>Permanently Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @script
    <script>
        $wire.on('hide-permanent-cleanup-modal', () => {
            const modalElement = document.getElementById('permanentCleanupModal');
            if (modalElement) {
                window.bootstrap.Modal.getOrCreateInstance(modalElement).hide();
            }
        });
    </script>
    @endscript
</div>
