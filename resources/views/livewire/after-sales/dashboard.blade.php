<div class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="{{ route('afterSales.dashboard') }}">MSD Admin</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
        @if ((int) auth()->user()?->role === 0)
        <a class="btn btn-outline-light btn-sm ms-2" href="{{ route('superAdminDashboard.view') }}">
            <i class="fas fa-arrow-left me-1"></i> SuperAdmin Dashboard
        </a>
        @endif
        <div class="ms-auto me-3">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user fa-fw"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <livewire:auth.logout />
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link active" href="{{ route('afterSales.dashboard') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-screwdriver-wrench"></i></div>
                            MSD Admin Dashboard
                        </a>
                        <button type="button" class="nav-link border-0 text-start w-100 {{ $section === 'asap' ? 'active' : '' }}" wire:click="setSection('asap')">
                            <div class="sb-nav-link-icon"><i class="fas fa-truck-fast"></i></div>
                            Units sold from ASAP
                        </button>
                        <button type="button" class="nav-link border-0 text-start w-100 {{ $section === 'other' ? 'active' : '' }}" wire:click="setSection('other')">
                            <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                            Other
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
                    {{ (int) auth()->user()?->role === 0 ? 'SuperAdmin' : 'MSD Admin' }}
                </div>
            </nav>
        </div>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">MSD Admin Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">
                            {{ $section === 'asap' ? 'Units sold from ASAP' : 'Other JO Records' }}
                        </li>
                    </ol>

                    @if ($noticeMessage)
                    <div class="alert alert-{{ $noticeType }}">{{ $noticeMessage }}</div>
                    @endif

                    @if (session()->has('message'))
                    <div class="alert alert-success">{{ session('message') }}</div>
                    @endif
                    @if (session()->has('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <div class="fw-bold mb-1">Please check the form:</div>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="row g-4">
                        @if ($section === 'asap')
                        <div class="col-lg-5">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white fw-bold">
                                    <i class="fas fa-magnifying-glass me-2"></i>Units sold from ASAP
                                </div>
                                <div class="card-body">
                                    <label class="form-label">Sale Control No.</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control @error('saleControlNo') is-invalid @enderror" wire:model="saleControlNo" placeholder="Enter Sale Control No.">
                                        <button class="btn btn-primary" type="button" wire:click="searchSaleControl">Search</button>
                                    </div>
                                    @error('saleControlNo') <div class="text-danger small mb-2">{{ $message }}</div> @enderror

                                    @if ($selectedClient)
                                    <div class="border rounded p-3 bg-light">
                                        <div class="fw-bold mb-2">{{ $selectedClient->company_name }}</div>
                                        <div class="small text-muted">Sales No: {{ $selectedClient->salesList_no ?? 'N/A' }}</div>
                                        <div class="small text-muted">Contact: {{ $selectedClient->contact_number }}</div>
                                        <div class="small text-muted">Vehicle/Unit: {{ $selectedClient->item_name ?? 'N/A' }}</div>
                                        <div class="small text-muted">Model: {{ $selectedClient->model_number ?? 'N/A' }}</div>
                                        <div class="small text-muted">Year Model: {{ $selectedClient->year_model ?? 'N/A' }}</div>
                                    </div>
                                    @else
                                    <div class="text-muted small">Search a sold unit before creating an MSD record.</div>
                                    @endif
                                    @error('selectedClientId') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="{{ $section === 'asap' ? 'col-lg-7' : 'col-lg-12' }}">
                            <div
                                class="card shadow-sm {{ $editingRecordId ? 'border-warning' : 'border-0' }}"
                                x-on:msd-record-editing.window="$el.scrollIntoView({ behavior: 'smooth', block: 'start' })"
                            >
                                <div class="card-header bg-white fw-bold">
                                    <i class="fas {{ $editingRecordId ? 'fa-pen-to-square' : 'fa-file-circle-plus' }} me-2"></i>
                                    {{ $editingRecordId ? 'Edit MSD Record' : 'Add MSD Record' }}
                                </div>
                                <div class="card-body">
                                    <form wire:submit.prevent="save">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Type</label>
                                                <select class="form-select @error('change_type') is-invalid @enderror" wire:model.live="change_type">
                                                    <option value="">Select Type</option>
                                                    <option value="WITH CHANGE">With Change</option>
                                                    <option value="WITHOUT CHANGE">Without Change</option>
                                                </select>
                                                @error('change_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                            @if ($section === 'asap')
                                            <div class="col-md-4">
                                                <label class="form-label">Warranty Type</label>
                                                <select
                                                    class="form-select @error('warranty_type') is-invalid @enderror"
                                                    wire:model="warranty_type"
                                                    @disabled($change_type === 'WITH CHANGE')
                                                >
                                                    <option value="">Select Warranty</option>
                                                    <option value="UNDER WARRANTY">UNDER WARRANTY</option>
                                                    <option value="OUT OF WARRANTY">OUT OF WARRANTY</option>
                                                </select>
                                                @error('warranty_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Number of PMS</label>
                                                <input type="text" class="form-control @error('pms_number') is-invalid @enderror" wire:model="pms_number">
                                                @error('pms_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                            @endif
                                            @if ($section === 'other' && !$editingRecordId)
                                            <div class="col-12">
                                                <label class="form-label">Search Pending Repair & Maintenance by Company Name</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control @error('maintenanceCompanySearch') is-invalid @enderror" wire:model="maintenanceCompanySearch" placeholder="Enter company name">
                                                    <button class="btn btn-outline-primary" type="button" wire:click="searchMaintenanceCompany">Search Company</button>
                                                </div>
                                                @error('maintenanceCompanySearch') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                                <div class="form-text">Only Repair & Maintenance records without a JO Number are shown.</div>
                                            </div>

                                            @if ($maintenanceSearchPerformed)
                                            <div class="col-12">
                                                <div class="fw-semibold mb-2">Pending Records</div>
                                                @forelse ($maintenanceSearchResults as $maintenanceSearchRecord)
                                                @php
                                                    $searchResultVehicles = $maintenanceSearchRecord->vehicle_specifications ?? [];
                                                @endphp
                                                <div class="border rounded p-3 mb-2 {{ (int) $selectedMaintenanceRecordId === $maintenanceSearchRecord->id ? 'border-primary bg-light' : '' }}">
                                                    <div class="d-flex justify-content-between align-items-start gap-3">
                                                        <div>
                                                            <div class="fw-bold">{{ $maintenanceSearchRecord->company_name }}</div>
                                                            <div class="small text-muted">
                                                                Contact: {{ $maintenanceSearchRecord->contact_number }} ·
                                                                Contact Person: {{ $maintenanceSearchRecord->contact_person }} ·
                                                                Created: {{ $maintenanceSearchRecord->created_at?->format('F d, Y') ?? 'N/A' }}
                                                            </div>
                                                        </div>
                                                        <button type="button" class="btn btn-sm {{ (int) $selectedMaintenanceRecordId === $maintenanceSearchRecord->id ? 'btn-primary' : 'btn-outline-primary' }}" wire:click="selectMaintenanceRecord({{ $maintenanceSearchRecord->id }})">
                                                            {{ (int) $selectedMaintenanceRecordId === $maintenanceSearchRecord->id ? 'Selected' : 'Select Record' }}
                                                        </button>
                                                    </div>
                                                    <div class="mt-2 small">
                                                        <span class="fw-semibold">Vehicles:</span>
                                                        @if (count($searchResultVehicles) > 0)
                                                            @foreach ($searchResultVehicles as $vehicle)
                                                                <span class="badge text-bg-secondary me-1 mb-1">
                                                                    {{ $vehicle['brand'] ?? 'N/A' }} {{ $vehicle['model'] ?? '' }} · {{ $vehicle['serial_or_plate_number'] ?? 'N/A' }}
                                                                </span>
                                                            @endforeach
                                                        @else
                                                            <span class="text-muted">{{ $maintenanceSearchRecord->serial_number ?? 'N/A' }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @empty
                                                <div class="alert alert-light border text-muted mb-0">No pending Repair & Maintenance records found for this company.</div>
                                                @endforelse
                                            </div>
                                            @endif
                                            @endif
                                            <div class="col-md-4">
                                                <label class="form-label">JO Number</label>
                                                <input type="text" class="form-control @error('job_order_number') is-invalid @enderror" wire:model="job_order_number">
                                                @error('job_order_number') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Date JO</label>
                                                <input type="date" class="form-control @error('job_order_date') is-invalid @enderror" wire:model="job_order_date">
                                                @error('job_order_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                            @if ($section === 'other')
                                            <div class="col-12">
                                                @if ($selectedMaintenanceRecord)
                                                <div class="border rounded p-3 bg-light">
                                                    <div class="fw-bold mb-2">{{ $selectedMaintenanceRecord->company_name }}</div>
                                                    <div class="row g-2 small text-muted">
                                                        <div class="col-md-3">JO Status: {{ $editingRecordId ? $selectedMaintenanceRecord->job_order_number : 'Pending assignment' }}</div>
                                                        <div class="col-md-3">Contact: {{ $selectedMaintenanceRecord->contact_number }}</div>
                                                        <div class="col-md-3">Contact Person: {{ $selectedMaintenanceRecord->contact_person }}</div>
                                                        <div class="col-md-3">Email: {{ $selectedMaintenanceRecord->email }}</div>
                                                    </div>
                                                    @php
                                                        $selectedMaintenanceVehicles = $selectedMaintenanceRecord->vehicle_specifications ?? [];
                                                    @endphp
                                                    <div class="mt-2 small">
                                                        <span class="fw-semibold">Vehicles under this JO:</span>
                                                        @if (count($selectedMaintenanceVehicles) > 0)
                                                            @foreach ($selectedMaintenanceVehicles as $vehicle)
                                                                <span class="badge text-bg-secondary me-1 mb-1">
                                                                    {{ $vehicle['brand'] ?? 'N/A' }} {{ $vehicle['model'] ?? '' }} · {{ $vehicle['serial_or_plate_number'] ?? 'N/A' }}
                                                                </span>
                                                            @endforeach
                                                        @else
                                                            <span class="text-muted">{{ $selectedMaintenanceRecord->serial_number ?? 'N/A' }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @else
                                                <div class="text-muted small">Search by company and select a pending Repair & Maintenance record before saving.</div>
                                                @endif
                                                @error('selectedMaintenanceRecordId') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                                            </div>
                                            @endif
                                            <div class="col-12">
                                                <label class="form-label">Description</label>
                                                <textarea rows="3" class="form-control @error('description') is-invalid @enderror" wire:model="description"></textarea>
                                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Remarks</label>
                                                <textarea rows="2" class="form-control @error('remarks') is-invalid @enderror" wire:model="remarks"></textarea>
                                                @error('remarks') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                        <div class="text-end mt-3 d-flex gap-2 justify-content-end">
                                            @if ($editingRecordId)
                                            <button type="button" class="btn btn-outline-secondary" wire:click="cancelEdit">Cancel</button>
                                            @endif
                                            <button type="submit" class="btn btn-primary">
                                                {{ $editingRecordId ? 'Update Record' : 'Save Record' }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mt-4 mb-4">
                        <div class="card-header bg-white d-flex flex-wrap gap-3 justify-content-between align-items-center">
                            <div class="fw-bold"><i class="fas fa-list-check me-2"></i>JO Information</div>
                            <input type="text" class="form-control w-auto" wire:model.live.debounce.300ms="jobOrderSearch" placeholder="Search Client Name or JO Number" aria-label="Search Client Name or JO Number">
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
                                    @forelse ($records as $record)
                                    @php
                                        $maintenanceRecord = $record->maintenanceRecord ?? $maintenanceRecordsByJobOrder->get($record->job_order_number);
                                        $recordVehicles = $maintenanceRecord?->vehicle_specifications ?? [];
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $record->change_type ? ucwords(strtolower($record->change_type)) : 'N/A' }}
                                            </span>
                                        </td>
                                        <td>{{ $record->warranty_type ?? 'N/A' }}</td>
                                        <td>{{ $record->job_order_number }}</td>
                                        <td>{{ $record->job_order_date?->format('F d, Y') ?? 'N/A' }}</td>
                                        <td>{{ $record->client->company_name ?? $maintenanceRecord?->company_name ?? 'N/A' }}</td>
                                        <td style="min-width: 220px;">
                                            @if ($record->client?->item_name)
                                                {{ $record->client->item_name }}
                                            @elseif (count($recordVehicles) > 0)
                                                @foreach ($recordVehicles as $vehicle)
                                                    <div class="small mb-1">
                                                        <span class="fw-semibold">{{ $vehicle['brand'] ?? 'N/A' }} {{ $vehicle['model'] ?? '' }}</span>
                                                        <span class="text-muted">· {{ $vehicle['serial_or_plate_number'] ?? 'N/A' }}</span>
                                                    </div>
                                                @endforeach
                                            @else
                                                {{ $maintenanceRecord?->serial_number ?? 'N/A' }}
                                            @endif
                                        </td>
                                        <td style="min-width: 260px;">
                                            <div>{{ $record->description ?? 'N/A' }}</div>
                                        </td>
                                        <td style="min-width: 220px;">
                                            <div>{{ $record->remarks ?? 'N/A' }}</div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary" wire:click="editRecord({{ $record->id }})">
                                                <i class="fas fa-pen-to-square me-1"></i>Edit
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">No MSD records found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $records->links() }}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
