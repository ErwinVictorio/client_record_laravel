<div class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="{{ route('warehouse.dashboard') }}">Warehouse</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
        @if ((int) auth()->user()?->role === 0)
        <a class="btn btn-outline-light btn-sm ms-2" href="{{ route('superAdminDashboard.view') }}">
            <i class="fas fa-arrow-left me-1"></i> SuperAdmin Dashboard
        </a>
        @endif
        <ul class="navbar-nav ms-auto me-3">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <livewire:auth.logout />
                </ul>
            </li>
        </ul>
    </nav>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <button type="button" wire:click="setTab('clients')" class="nav-link border-0 text-start {{ $activeTab === 'clients' ? 'active' : '' }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Clients
                        </button>
                        <!-- <button type="button" wire:click="setTab('auto-repair')" class="nav-link border-0 text-start {{ $activeTab === 'auto-repair' ? 'active' : '' }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-wrench"></i></div>
                            Auto Parts
                        </button> -->
                        <!-- <button type="button" wire:click="setTab('maintenance')" class="nav-link border-0 text-start {{ $activeTab === 'maintenance' ? 'active' : '' }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tools"></i></div>
                            Repair & Maintenance
                        </button> -->
                        <button type="button" wire:click="setTab('pms-records')" class="nav-link border-0 text-start {{ $activeTab === 'pms-records' ? 'active' : '' }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-clipboard-check"></i></div>
                            PMS Record
                        </button>
                        <button type="button" wire:click="setTab('other-records')" class="nav-link border-0 text-start {{ $activeTab === 'other-records' ? 'active' : '' }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-list-check"></i></div>
                            Other Record
                        </button>
                        <button type="button" class="nav-link border-0 text-start {{ $activeTab === 'other-records' ? 'active' : '' }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-list-check"></i></div>
                             Stock Status
                        </button>
                        @if ((int) auth()->user()?->role === 0)
                        <a class="nav-link" href="{{ route('superAdminDashboard.view') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-arrow-left"></i></div>
                            Back to SuperAdmin
                        </a>
                        @endif
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    {{ (int) auth()->user()?->role === 0 ? 'SuperAdmin' : 'Warehouse' }}
                </div>
            </nav>
        </div>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Warehouse Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Records Overview</li>
                    </ol>

                    @if ($noticeMessage)
                    <div class="alert alert-{{ $noticeType }}">{{ $noticeMessage }}</div>
                    @endif

                    @if ($activeTab === 'clients')
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="fw-bold"><i class="fas fa-users me-2"></i>Client Records</div>
                            <input type="text" class="form-control w-auto" wire:model.live.debounce.300ms="clientSearch" placeholder="Search clients">
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>SalesList No.</th>
                                        <th>Company</th>
                                        <th>Sales Executive</th>
                                        <th>Contact</th>
                                        <th>Status</th>
                                        <th>Vehicle/Unit</th>
                                        <th>Model</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($clients as $client)
                                    <tr>
                                        <td>{{ $client->salesList_no ?? 'N/A' }}</td>
                                        <td>{{ $client->company_name }}</td>
                                        <td>{{ $client->salesman?->first_name }} {{ $client->salesman?->last_name }}</td>
                                        <td>{{ $client->contact_number }}</td>
                                        <td><span class="badge bg-primary">{{ $client->status }}</span></td>
                                        <td>{{ $client->item_name ?? 'N/A' }}</td>
                                        <td>{{ $client->model_number ?? 'N/A' }}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#warehouseClientDetails_{{ $client->id }}">
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">No client records found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $clients->links() }}
                        </div>
                    </div>
                    @foreach ($clients as $client)
                    @include('livewire.warehouse.partials.client-details-modal', ['client' => $client])
                    @endforeach
                    @endif

                    @if ($activeTab === 'auto-repair')
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="fw-bold"><i class="fas fa-wrench me-2"></i>Auto Parts Records</div>
                            <input type="text" class="form-control w-auto" wire:model.live.debounce.300ms="autoRepairSearch" placeholder="Search auto parts">
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Stock Out No.</th>
                                        <th>Company</th>
                                        <th>Contact</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($autoRepairRecords as $record)
                                    <tr>
                                        <td>{{ $record->stock_out_number }}</td>
                                        <td>{{ $record->company_name }}</td>
                                        <td>{{ $record->contact_number }}</td>
                                        <td>{{ $record->email }}</td>
                                        <td>{{ $record->address }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No auto parts records found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $autoRepairRecords->links() }}
                        </div>
                    </div>
                    @endif

                    @if ($activeTab === 'maintenance')
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="fw-bold"><i class="fas fa-tools me-2"></i>Repair & Maintenance Records</div>
                            <input type="text" class="form-control w-auto" wire:model.live.debounce.300ms="maintenanceSearch" placeholder="Search maintenance">
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>JO Number</th>
                                        <th>Company</th>
                                        <th>Contact</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($maintenanceRecords as $record)
                                    <tr>
                                        <td>{{ $record->job_order_number }}</td>
                                        <td>{{ $record->company_name }}</td>
                                        <td>{{ $record->contact_number }}</td>
                                        <td>{{ $record->email }}</td>
                                        <td>{{ $record->address }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No repair and maintenance records found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $maintenanceRecords->links() }}
                        </div>
                    </div>
                    @endif

                    @if ($activeTab === 'pms-records')
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="fw-bold"><i class="fas fa-clipboard-check me-2"></i>PMS Records</div>
                            <input type="text" class="form-control w-auto" wire:model.live.debounce.300ms="pmsRecordSearch" placeholder="Search PMS records">
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Warranty</th>
                                        <th>JO Number</th>
                                        <th>Date</th>
                                        <th>Client</th>
                                        <th>Vehicle/Unit</th>
                                        <th>Description</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pmsRecords as $record)
                                    <tr wire:key="warehouse-pms-record-{{ $record->id }}">
                                        <td><span class="badge bg-primary">Units sold from ASAP</span></td>
                                        <td>{{ $record->warranty_type ?? 'N/A' }}</td>
                                        <td>{{ $record->job_order_number }}</td>
                                        <td>{{ $record->job_order_date?->format('F d, Y') ?? 'N/A' }}</td>
                                        <td>{{ $record->client?->company_name ?? 'N/A' }}</td>
                                        <td>{{ $record->client?->item_name ?? 'N/A' }}</td>
                                        <td style="min-width: 240px;">{{ $record->description ?? 'N/A' }}</td>
                                        <td style="min-width: 260px;">
                                            {{ $record->remarks ?? 'N/A' }}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary" wire:click="editRemarks({{ $record->id }})">
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">No PMS records found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $pmsRecords->links() }}
                        </div>
                    </div>
                    @endif

                    @if ($activeTab === 'other-records')
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="fw-bold"><i class="fas fa-list-check me-2"></i>Other Records</div>
                            <input type="text" class="form-control w-auto" wire:model.live.debounce.300ms="otherRecordSearch" placeholder="Search other records">
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>JO Number</th>
                                        <th>Date</th>
                                        <th>Client</th>
                                        <th>Description</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($otherRecords as $record)
                                    @php
                                    $maintenanceRecord = $otherMaintenanceRecordsByJobOrder->get($record->job_order_number);
                                    @endphp
                                    <tr wire:key="warehouse-other-record-{{ $record->id }}">
                                        <td><span class="badge bg-secondary">Other</span></td>
                                        <td>{{ $record->job_order_number }}</td>
                                        <td>{{ $record->job_order_date?->format('F d, Y') ?? 'N/A' }}</td>
                                        <td>{{ $maintenanceRecord?->company_name ?? 'N/A' }}</td>
                                        <td style="min-width: 260px;">{{ $record->description ?? 'N/A' }}</td>
                                        <td style="min-width: 260px;">
                                            {{ $record->remarks ?? 'N/A' }}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary" wire:click="editRemarks({{ $record->id }})">
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">No other records found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $otherRecords->links() }}
                        </div>
                    </div>
                    @endif

                    <div class="modal fade" id="warehouseRemarksModal" tabindex="-1" aria-labelledby="warehouseRemarksModalLabel" aria-hidden="true" wire:ignore.self>
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="warehouseRemarksModalLabel">Edit Remarks</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <label class="form-label">Remarks</label>
                                    <textarea
                                        id="warehouseRemarksTextarea"
                                        rows="6"
                                        class="form-control @error('editingRemarks') is-invalid @enderror"
                                        wire:model="editingRemarks"
                                        wire:key="warehouse-remarks-textarea-{{ $editingRemarksRecordId ?? 'empty' }}"></textarea>
                                    @error('editingRemarks') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-primary" wire:click="saveRemarks">Save Remarks</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    @script
                    <script>
                        $wire.on('show-warehouse-remarks-modal', (event) => {
                            const modalElement = document.getElementById('warehouseRemarksModal');
                            const remarksTextarea = document.getElementById('warehouseRemarksTextarea');

                            if (remarksTextarea && event?.remarks !== undefined) {
                                remarksTextarea.value = event.remarks ?? '';
                                remarksTextarea.dispatchEvent(new Event('input', {
                                    bubbles: true
                                }));
                            }

                            window.bootstrap.Modal.getOrCreateInstance(modalElement).show();
                        });

                        $wire.on('hide-warehouse-remarks-modal', () => {
                            const modalElement = document.getElementById('warehouseRemarksModal');
                            window.bootstrap.Modal.getOrCreateInstance(modalElement).hide();
                        });
                    </script>
                    @endscript
                </div>
            </main>
        </div>
    </div>
</div>