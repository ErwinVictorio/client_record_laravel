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
                                        <div class="fw-bold mb-2 text-primary">{{ $selectedClient->company_name }}</div>
                                        <div class="small text-muted">Sales No: {{ $selectedClient->salesList_no ?? 'N/A' }}</div>
                                        <div class="small text-muted">Contact: {{ $selectedClient->contact_number }}</div>
                                        <div class="small text-muted">Vehicle/Unit: {{ $selectedClient->item_name ?? 'N/A' }}</div>
                                        <div class="small text-muted">Model: {{ $selectedClient->model_number ?? 'N/A' }}</div>
                                        <div class="small text-muted">Year Model: {{ $selectedClient->year_model ?? 'N/A' }}</div>

                                        <!-- Vehicle/Unit Specifications Breakdown -->
                                        @php
                                        $vehicles = $selectedClient->vehicle_specifications ?? [];
                                        @endphp

                                        <div class="mt-3 pt-3 border-top">
                                            <span class="fw-bold text-dark d-block mb-3" style="font-size: 0.8rem;">
                                                <i class="fas fa-truck-ramp-box me-1 text-muted"></i> Unit Specifications
                                            </span>

                                            @if (is_array($vehicles) && count($vehicles) > 0)
                                            @foreach ($vehicles as $vehicle)
                                            <div class="mb-3 p-3 bg-white rounded border shadow-sm">
                                                <!-- Header: Brand & Model -->
                                                <div class="d-flex align-items-center gap-2 mb-2 pb-2 border-bottom border-light">
                                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                                        <strong>Brand:</strong> {{ $vehicle['brand'] ?? 'N/A' }}
                                                    </span>
                                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">
                                                        <strong>Model:</strong> {{ $vehicle['model'] ?? 'N/A' }}
                                                    </span>
                                                </div>

                                                <!-- Technical Details Grid -->
                                                <div class="row g-2 mt-1 text-muted" style="font-size: 0.75rem;">
                                                    <div class="col-6 d-flex justify-content-between pe-2">
                                                        <span class="text-secondary">Capacity:</span>
                                                        <strong class="text-dark">{{ $vehicle['loading_capacity'] ?? 'N/A' }}</strong>
                                                    </div>
                                                    <div class="col-6 d-flex justify-content-between ps-2">
                                                        <span class="text-secondary">Height:</span>
                                                        <strong class="text-dark">{{ $vehicle['lifting_height'] ?? 'N/A' }}</strong>
                                                    </div>
                                                    <div class="col-6 d-flex justify-content-between pe-2">
                                                        <span class="text-secondary">Mast:</span>
                                                        <strong class="text-dark">{{ $vehicle['mast_type'] ?? 'N/A' }}</strong>
                                                    </div>
                                                    <div class="col-6 d-flex justify-content-between ps-2">
                                                        <span class="text-secondary">Power:</span>
                                                        <strong class="text-dark">{{ $vehicle['power_type'] ?? 'N/A' }}</strong>
                                                    </div>
                                                    <div class="col-6 d-flex justify-content-between pe-2">
                                                        <span class="text-secondary">Tire:</span>
                                                        <strong class="text-dark">{{ $vehicle['tire'] ?? 'N/A' }}</strong>
                                                    </div>
                                                    <div class="col-6 d-flex justify-content-between ps-2">
                                                        <span class="text-secondary">Fork:</span>
                                                        <strong class="text-dark">{{ $vehicle['fork_length'] ?? 'N/A' }}</strong>
                                                    </div>

                                                    <!-- Full-width Attachment section -->
                                                    @if(!empty($vehicle['attachment']) && $vehicle['attachment'] !== 'N/A')
                                                    <div class="col-12 mt-2 pt-2 border-top border-light-subtle d-flex align-items-start gap-2">
                                                        <span class="text-secondary text-nowrap">Attachment:</span>
                                                        <strong class="text-dark">{{ $vehicle['attachment'] }}</strong>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @endforeach
                                            @else
                                            <div class="text-muted bg-light p-3 border d-block rounded text-center small shadow-inner">
                                                <i class="fas fa-info-circle me-1"></i> No specifications available.
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @else
                                    <div class="text-muted small">Search a sold unit before creating an MSD record.</div>
                                    @endif
                                    @error('selectedClientId') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        @endif

                        @if ($section === 'other')
                        <div class="col-lg-5">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white fw-bold">
                                    <i class="fas fa-magnifying-glass me-2"></i>Search Pending Repair & Maintenance
                                </div>
                                <div class="card-body">
                                    <label class="form-label fw-semibold">Search by Company Name</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control @error('maintenanceCompanySearch') is-invalid @enderror" wire:model="maintenanceCompanySearch" placeholder="Enter company name">
                                        <button class="btn btn-primary" type="button" wire:click="searchMaintenanceCompany">Search</button>
                                    </div>
                                    @error('maintenanceCompanySearch') <div class="text-danger small mb-2">{{ $message }}</div> @enderror
                                    <div class="form-text mb-3 text-muted small">Only Repair & Maintenance records without a JO Number are shown.</div>

                                    <!-- Search Results Area -->
                                    @if ($maintenanceSearchPerformed)
                                    <div class="fw-semibold mb-2 small">Pending Records</div>
                                    @forelse ($maintenanceSearchResults as $maintenanceSearchRecord)
                                    @php
                                    $searchResultVehicles = $maintenanceSearchRecord->vehicle_specifications ?? [];
                                    @endphp
                                    <div class="border rounded p-3 mb-2 {{ (int) $selectedMaintenanceRecordId === $maintenanceSearchRecord->id ? 'border-primary bg-light' : '' }}">
                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                            <div style="flex: 1;">
                                                <div class="fw-bold small">{{ $maintenanceSearchRecord->company_name }}</div>
                                                <div style="font-size: 0.75rem;" class="text-muted mt-1">
                                                    Contact: {{ $maintenanceSearchRecord->contact_number }}<br>
                                                    Person: {{ $maintenanceSearchRecord->contact_person }}<br>
                                                    Created: {{ $maintenanceSearchRecord->created_at?->format('M d, Y') ?? 'N/A' }}
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-sm {{ (int) $selectedMaintenanceRecordId === $maintenanceSearchRecord->id ? 'btn-primary' : 'btn-outline-primary' }}" wire:click="selectMaintenanceRecord({{ $maintenanceSearchRecord->id }})">
                                                {{ (int) $selectedMaintenanceRecordId === $maintenanceSearchRecord->id ? 'Selected' : 'Select' }}
                                            </button>
                                        </div>
                                        <div class="mt-2" style="font-size: 0.75rem;">
                                            <span class="fw-semibold">Vehicles:</span>
                                            @if (count($searchResultVehicles) > 0)
                                            @foreach ($searchResultVehicles as $vehicle)
                                            <span class="badge text-bg-secondary me-1 mb-1">
                                                {{ $vehicle['brand'] ?? 'N/A' }} {{ $vehicle['model'] ?? '' }}
                                            </span>
                                            @endforeach
                                            @else
                                            <span class="text-muted">{{ $maintenanceSearchRecord->serial_number ?? 'N/A' }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    @empty
                                    <div class="alert alert-light border text-muted small mb-0 py-2">No records found.</div>
                                    @endforelse
                                    @endif

                                    <!-- Selected Target Display -->
                                    <div class="mt-3 pt-3 border-top">
                                        @if ($selectedMaintenanceRecord)
                                        <div class="border rounded p-3 bg-light">
                                            <div class="fw-bold mb-1 text-primary small">{{ $selectedMaintenanceRecord->company_name }}</div>
                                            <div style="font-size: 0.75rem;" class="text-muted">
                                                <div><strong>Status:</strong> Pending JO assignment</div>
                                                <div><strong>Contact:</strong> {{ $selectedMaintenanceRecord->contact_number }}</div>
                                                <div><strong>Person:</strong> {{ $selectedMaintenanceRecord->contact_person }}</div>
                                            </div>
                                            @php
                                            $selectedMaintenanceVehicles = $selectedMaintenanceRecord->vehicle_specifications ?? [];
                                            @endphp
                                            <div class="mt-2 text-muted" style="font-size: 0.75rem;">
                                                <span class="fw-semibold">Vehicles under this JO:</span>
                                                <div class="mt-1">
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
                                        </div>
                                        @else
                                        <div class="text-muted small p-2 text-center border rounded bg-light" style="font-size: 0.8rem;">
                                            Select a pending record above to link.
                                        </div>
                                        @endif
                                        @error('selectedMaintenanceRecordId') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                                    </div>

                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- RIGHT SIDE: FORM CARD -->
                        <div class="{{ in_array($section, ['asap', 'other'], true) ? 'col-lg-7' : 'col-lg-12' }}">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-white fw-bold">
                                    <i class="fas fa-file-circle-plus me-2"></i>
                                    Add MSD Record
                                </div>
                                <div class="card-body">
                                    <form wire:submit.prevent="save">
                                        <div class="row g-3">

                                            <div class="col-md-4">
                                                <label class="form-label">Type</label>
                                                <select class="form-select @error('change_type') is-invalid @enderror" wire:model.live="change_type">
                                                    <option value="">Select Type</option>
                                                    <option value="WITH CHANGE">With Charge</option>
                                                    <option value="WITHOUT CHANGE">Without Charge</option>
                                                </select>
                                                @error('change_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>

                                            @if ($section === 'asap')
                                            <div class="col-md-4">
                                                <label class="form-label">Warranty Type</label>
                                                <select class="form-select @error('warranty_type') is-invalid @enderror" wire:model="warranty_type" @disabled($change_type==='WITH CHANGE' )>
                                                    <option value="">Select Warranty</option>
                                                    <option value="UNDER WARRANTY">UNDER WARRANTY</option>
                                                    <option value="OUT OF WARRANTY">OUT OF WARRANTY</option>
                                                </select>
                                                @error('warranty_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                            @endif

                                            <div class="col-md-4">
                                                <label class="form-label">Service Type</label>
                                                <select class="form-select @error('service_type') is-invalid @enderror" wire:model.live="service_type">
                                                    <option value="">Select Service Type</option>
                                                    <option value="PMS">PMS</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                                @error('service_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>

                                            {{-- Ginawang eksaktong 'PMS' na lang para hindi na magpakita kapag 'Other' ang pinindot --}}
                                            @if ($service_type === 'PMS')
                                            <div class="col-md-4">
                                                <label class="form-label">Number of PMS</label>
                                                <input type="text" class="form-control @error('pms_number') is-invalid @enderror" wire:model="pms_number">
                                                @error('pms_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
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

                                        <div class="text-end mt-4">
                                            <button type="submit" class="btn btn-primary px-4">
                                                Save Record
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mt-4 mb-4">
                        <div class="card-header bg-white d-flex flex-wrap gap-3 justify-content-between align-items-center py-3">
                            <div class="fw-bold text-dark fs-6">
                                <i class="fas fa-list-check text-primary me-2"></i>JO Information
                            </div>

                            <!-- Pinahabang Search Input na may Magnifying Glass Icon -->
                            <div class="input-group shadow-sm flex-grow-1" style="max-width: 500px;">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <i class="fas fa-magnifying-glass"></i>
                                </span>
                                <input
                                    type="text"
                                    class="form-control bg-light border-start-0 ps-0"
                                    wire:model.live.debounce.300ms="jobOrderSearch"
                                    placeholder="Search Client Name or JO Number..."
                                    aria-label="Search Client Name or JO Number">
                                @if(!empty($jobOrderSearch))
                                <button class="btn btn-light border border-start-0 text-muted" type="button" wire:click="$set('jobOrderSearch', '')">
                                    <i class="fas fa-xmark"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>JO Number</th>
                                        <th>Service</th>
                                        <th>Type</th>
                                        <th>Warranty</th>
                                        <th>Date</th>
                                        <th>Sales List No</th>
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
                                        <td>{{ $record->job_order_number }}</td>
                                        <td>{{ $record->service_type }}</td>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $record->change_type ? ucwords(strtolower(str_replace('CHANGE', 'CHARGE', $record->change_type))) : 'N/A' }}
                                            </span>
                                        </td>
                                        <td>{{ $record->warranty_type ?? 'N/A' }}</td>

                                        <td>{{ $record->job_order_date?->format('F d, Y') ?? 'N/A' }}</td>
                                        <td>{{ $record->client->salesList_no ?? $maintenanceRecord?->salesList_no ?? 'N/A' }}</td>
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
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#msdEditModal_{{ $record->id }}">
                                                <i class="fas fa-pen-to-square me-1"></i>Edit
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-4">No MSD records found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $records->links() }}
                        </div>
                    </div>

                    @if (false)
                    <div
                        class="modal fade"
                        id="msdEditModal"
                        tabindex="-1"
                        aria-labelledby="msdEditModalLabel"
                        aria-hidden="true"
                        wire:ignore.self
                        wire:key="msd-edit-modal-{{ $editingRecordId ?? 'empty' }}">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <form wire:submit.prevent="updateRecord">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="msdEditModalLabel">
                                            <i class="fas fa-pen-to-square me-2"></i>Edit MSD Record
                                        </h5>
                                        <button type="button" class="btn-close" wire:click="cancelEdit" aria-label="Close"></button>
                                    </div>


                                    <div class="modal-body">
                                        @if ($editClientId && $selectedEditClient)
                                        <div class="alert alert-light border mb-3">
                                            <div class="fw-bold">{{ $selectedEditClient->company_name }}</div>
                                            <div class="small text-muted">
                                                Sale Control No.: {{ $selectedEditClient->salesList_no ?? 'N/A' }} ·
                                                Vehicle/Unit: {{ $selectedEditClient->item_name ?? 'N/A' }}
                                            </div>
                                        </div>
                                        @elseif ($editMaintenanceRecordId && $selectedEditMaintenanceRecord)
                                        <div class="alert alert-light border mb-3">
                                            <div class="fw-bold">{{ $selectedEditMaintenanceRecord->company_name }}</div>
                                            <div class="small text-muted">
                                                Contact: {{ $selectedEditMaintenanceRecord->contact_number ?? 'N/A' }} ·
                                                Contact Person: {{ $selectedEditMaintenanceRecord->contact_person ?? 'N/A' }}
                                            </div>
                                        </div>
                                        @endif

                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Type</label>
                                                <select class="form-select @error('editChangeType') is-invalid @enderror" wire:model.live="editChangeType">
                                                    <option value="">Select Type</option>
                                                    <option value="WITH CHANGE">With Charge</option>
                                                    <option value="WITHOUT CHANGE">Without Charge</option>
                                                </select>
                                                @error('editChangeType') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>

                                            @if ($editClientId)
                                            <div class="col-md-4">
                                                <label class="form-label">Warranty Type</label>
                                                <select class="form-select @error('editWarrantyType') is-invalid @enderror" wire:model="editWarrantyType" @disabled($editChangeType==='WITH CHANGE' )>
                                                    <option value="">Select Warranty</option>
                                                    <option value="UNDER WARRANTY">UNDER WARRANTY</option>
                                                    <option value="OUT OF WARRANTY">OUT OF WARRANTY</option>
                                                </select>
                                                @error('editWarrantyType') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                            @endif

                                            <div class="col-md-4">
                                                <label class="form-label">Service Type</label>
                                                <select class="form-select @error('editServiceType') is-invalid @enderror" wire:model.live="editServiceType">
                                                    <option value="">Select Service Type</option>
                                                    <option value="PMS">PMS</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                                @error('editServiceType') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>

                                            @if (in_array($editServiceType, ['PMS', 'Other'], true))
                                            <div class="col-md-4">
                                                <label class="form-label">Number of PMS</label>
                                                <input type="text" class="form-control @error('editPmsNumber') is-invalid @enderror" wire:model="editPmsNumber">
                                                @error('editPmsNumber') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                            @endif

                                            <div class="col-md-4">
                                                <label class="form-label">JO Number</label>
                                                <input type="text" class="form-control @error('editJobOrderNumber') is-invalid @enderror" wire:model="editJobOrderNumber">
                                                @error('editJobOrderNumber') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Date JO</label>
                                                <input type="date" class="form-control @error('editJobOrderDate') is-invalid @enderror" wire:model="editJobOrderDate">
                                                @error('editJobOrderDate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Description</label>
                                                <textarea rows="3" class="form-control @error('editDescription') is-invalid @enderror" wire:model="editDescription"></textarea>
                                                @error('editDescription') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Remarks</label>
                                                <textarea rows="2" class="form-control @error('editRemarks') is-invalid @enderror" wire:model="editRemarks"></textarea>
                                                @error('editRemarks') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" wire:click="cancelEdit">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Update Record</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @script
                    <script>
                        $wire.on('show-msd-edit-modal', () => {
                            const msdEditModalElement = document.getElementById('msdEditModal');
                            window.bootstrap.Modal.getOrCreateInstance(msdEditModalElement).show();
                        });

                        $wire.on('hide-msd-edit-modal', () => {
                            const msdEditModalElement = document.getElementById('msdEditModal');
                            window.bootstrap.Modal.getOrCreateInstance(msdEditModalElement).hide();
                        });

                        document.addEventListener('hidden.bs.modal', (event) => {
                            if (event.target.id === 'msdEditModal') {
                                $wire.editModalClosed();
                            }
                        });
                    </script>
                    @endscript
                    @endif

                    @foreach ($records as $record)
                    <livewire:modals.after-sales-edit-record
                        :record-id="$record->id"
                        :key="'msd-edit-'.$section.'-'.$record->id" />
                    @endforeach
                </div>
            </main>
        </div>
    </div>
</div>