<div class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="{{ route('afterSales.dashboard') }}">After Sales</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
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
                            Dashboard
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    After Sales
                </div>
            </nav>
        </div>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">After Sales Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">PMS and Other JO Records</li>
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
                        <div class="col-lg-5">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white fw-bold">
                                    <i class="fas fa-magnifying-glass me-2"></i>Search Sold Unit
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
                                    <div class="text-muted small">Search a sold unit before creating a PMS record.</div>
                                    @endif
                                    @error('selectedClientId') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white fw-bold">
                                    <i class="fas fa-file-circle-plus me-2"></i>Add After Sales Record
                                </div>
                                <div class="card-body">
                                    <form wire:submit.prevent="save">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Type</label>
                                                <select class="form-select @error('service_type') is-invalid @enderror" wire:model.live="service_type">
                                                    <option value="PMS">PMS</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                                @error('service_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
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
                                                @error('job_order_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                                        </div>
                                        <div class="text-end mt-3">
                                            <button type="submit" class="btn btn-primary">Save Record</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mt-4 mb-4">
                        <div class="card-header bg-white d-flex flex-wrap gap-3 justify-content-between align-items-center">
                            <div class="fw-bold"><i class="fas fa-list-check me-2"></i>JO Information</div>
                            <input type="text" class="form-control w-auto" wire:model.live.debounce.300ms="jobOrderSearch" placeholder="Search JO Number">
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>JO Number</th>
                                        <th>Date</th>
                                        <th>Client</th>
                                        <th>Vehicle/Unit</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($records as $record)
                                    <tr>
                                        <td><span class="badge bg-primary">{{ $record->service_type }}</span></td>
                                        <td>{{ $record->job_order_number }}</td>
                                        <td>{{ $record->job_order_date?->format('F d, Y') ?? 'N/A' }}</td>
                                        <td>{{ $record->client->company_name ?? 'N/A' }}</td>
                                        <td>{{ $record->client->item_name ?? 'N/A' }}</td>
                                        <td>{{ $record->description ?? 'N/A' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">No After Sales records found.</td>
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
