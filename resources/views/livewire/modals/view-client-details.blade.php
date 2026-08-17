@php
    $vehicles = $client?->vehicle_specifications ?? [];
    if (is_string($vehicles)) {
        $vehicles = json_decode($vehicles, true);
    }
    $vehicles = is_array($vehicles) ? $vehicles : [];

    $documents = $client?->supporting_document_paths ?? [];
    if (is_string($documents)) {
        $documents = json_decode($documents, true);
    }
    $documents = is_array($documents) ? $documents : [];
    if ($client?->supporting_document_path && ! in_array($client->supporting_document_path, $documents, true)) {
        array_unshift($documents, $client->supporting_document_path);
    }
    $documents = array_values(array_filter($documents));

    $hasPersonalName = $client && collect([$client->first_name, $client->middle_name, $client->last_name])
        ->contains(fn ($name) => filled($name));
    $salesmanName = $client
        ? trim(($client->salesman?->first_name ?? '').' '.($client->salesman?->last_name ?? ''))
        : '';
    $vehicleLabels = [
        'brand' => 'Brand / Vehicle Unit',
        'model' => 'Model',
        'engine' => 'Engine',
        'engine_series' => 'Engine Series',
        'loading_capacity' => 'Loading Capacity',
        'lifting_height' => 'Lifting Height',
        'mast_type' => 'Mast Type',
        'power_type' => 'Power Type',
        'tire' => 'Tire',
        'fork_length' => 'Fork Length',
        'attachment' => 'Attachment',
    ];
@endphp

<div>
    <div wire:key="view-client-modal-{{ $clientId }}" class="modal fade" wire:ignore.self id="viewClientDetails_{{ $clientId }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="viewClientDetailsLabel_{{ $clientId }}" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content shadow">
                <div class="modal-header text-white" style="background-color: #004998">
                    <h1 class="modal-title fs-5" id="viewClientDetailsLabel_{{ $clientId }}">
                        <i class="fas fa-circle-info me-2"></i>Client Information
                    </h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body bg-light p-3 p-md-4">
                    @if ($client)
                        <div class="row g-3 mb-3">
                            <div class="col-lg-7">
                                <div class="bg-white border rounded p-3 h-100">
                                    <h6 class="fw-bold mb-3">Client Details</h6>
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4 text-muted">Sales List No.</dt>
                                        <dd class="col-sm-8 fw-semibold">{{ $client->salesList_no ?: 'N/A' }}</dd>
                                        <dt class="col-sm-4 text-muted">{{ $hasPersonalName ? 'Client Name' : 'Company' }}</dt>
                                        <dd class="col-sm-8 fw-semibold">{{ $client->display_name }}</dd>
                                        <dt class="col-sm-4 text-muted">Client Type</dt>
                                        <dd class="col-sm-8">{{ $hasPersonalName ? 'Personal / Individual' : 'Corporate' }}</dd>
                                        <dt class="col-sm-4 text-muted">Address</dt>
                                        <dd class="col-sm-8 text-break">{{ $client->address ?: 'N/A' }}</dd>
                                        <dt class="col-sm-4 text-muted">Email</dt>
                                        <dd class="col-sm-8 text-break">{{ $client->email ?: 'N/A' }}</dd>
                                        <dt class="col-sm-4 text-muted">Contact Number</dt>
                                        <dd class="col-sm-8">{{ $client->contact_number ?: 'N/A' }}</dd>
                                    </dl>
                                </div>
                            </div>

                            <div class="col-lg-5">
                                <div class="bg-white border rounded p-3 h-100">
                                    <h6 class="fw-bold mb-3">Contact and Sales Information</h6>
                                    <dl class="row mb-0">
                                        <dt class="col-sm-5 text-muted">Status</dt>
                                        <dd class="col-sm-7"><span class="badge bg-primary">{{ $client->status ?: 'N/A' }}</span></dd>
                                        <dt class="col-sm-5 text-muted">Salesman</dt>
                                        <dd class="col-sm-7">{{ $salesmanName ?: 'N/A' }}</dd>
                                        <dt class="col-sm-5 text-muted">Contact Person</dt>
                                        <dd class="col-sm-7">{{ $client->contact_person ?: 'N/A' }}</dd>
                                        <dt class="col-sm-5 text-muted">Person's Number</dt>
                                        <dd class="col-sm-7">{{ $client->contact_number_person ?: 'N/A' }}</dd>
                                        <dt class="col-sm-5 text-muted">Bank Account</dt>
                                        <dd class="col-sm-7 text-break">{{ $client->bank_account_number ?: 'N/A' }}</dd>
                                        <dt class="col-sm-5 text-muted">Created</dt>
                                        <dd class="col-sm-7">{{ $client->created_at?->format('F d, Y h:i A') ?? 'N/A' }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white border rounded p-3 mb-3">
                            <h6 class="fw-bold mb-3">Vehicle / Product Information</h6>
                            <div class="row g-3 mb-3">
                                <div class="col-sm-6 col-lg-3"><div class="small text-muted">Vehicle / Item</div><div class="fw-semibold">{{ $client->item_name ?: 'N/A' }}</div></div>
                                <div class="col-sm-6 col-lg-3"><div class="small text-muted">Model Number</div><div class="fw-semibold">{{ $client->model_number ?: 'N/A' }}</div></div>
                                <div class="col-sm-6 col-lg-3"><div class="small text-muted">Year Model</div><div class="fw-semibold">{{ $client->year_model ?: 'N/A' }}</div></div>
                                <div class="col-sm-6 col-lg-3"><div class="small text-muted">Quantity</div><div class="fw-semibold">{{ $client->quantity ?: 'N/A' }}</div></div>
                                <div class="col-12"><div class="small text-muted">General Specification</div><div class="text-break">{{ $client->specification ?: 'N/A' }}</div></div>
                            </div>

                            @forelse ($vehicles as $index => $vehicle)
                                <div class="border rounded p-3 {{ $loop->last ? '' : 'mb-3' }}">
                                    <div class="fw-semibold text-primary mb-3">Vehicle #{{ $index + 1 }}</div>
                                    <div class="row g-3">
                                        @foreach ($vehicleLabels as $field => $label)
                                            <div class="col-sm-6 col-lg-4">
                                                <div class="small text-muted">{{ $label }}</div>
                                                <div class="fw-semibold text-break">{{ filled($vehicle[$field] ?? null) ? $vehicle[$field] : 'N/A' }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted">No detailed vehicle specifications encoded yet.</div>
                            @endforelse
                        </div>

                        @if (filled($client->rejection_reason))
                            <div class="alert alert-danger">
                                <div class="fw-bold">Reason for Rejection</div>
                                <div class="text-break">{{ $client->rejection_reason }}</div>
                            </div>
                        @endif

                        <div class="bg-white border rounded p-3">
                            <h6 class="fw-bold mb-3">Supporting Documents</h6>
                            @forelse ($documents as $index => $document)
                                @php
                                    $extension = strtolower(pathinfo($document, PATHINFO_EXTENSION));
                                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'avif', 'heic', 'heif', 'tif', 'tiff', 'svg'], true);
                                @endphp
                                <a href="{{ asset('storage/'.$document) }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm me-2 mb-2">
                                    <i class="fas {{ $isImage ? 'fa-image' : 'fa-file-pdf' }} me-1"></i>
                                    View {{ $isImage ? 'Image' : 'Document' }} {{ $index + 1 }}
                                </a>
                            @empty
                                <div class="text-muted">No supporting documents uploaded.</div>
                            @endforelse
                        </div>
                    @else
                        <div class="alert alert-danger mb-0">Client information is unavailable or you do not have permission to view it.</div>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
