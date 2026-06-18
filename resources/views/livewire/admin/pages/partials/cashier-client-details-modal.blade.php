@php
    $vehicles = $client->vehicle_specifications;

    if (is_string($vehicles)) {
        $vehicles = json_decode($vehicles, true);
    }

    $vehicles = is_array($vehicles) ? $vehicles : [];

    $vehicleLabels = [
        'brand' => 'Brand / Vehicle Unit',
        'model' => 'Model',
        'loading_capacity' => 'Loading Capacity',
        'lifting_height' => 'Lifting Height',
        'mast_type' => 'Mast Type',
        'power_type' => 'Power Type',
        'tire' => 'Tire',
        'fork_length' => 'Fork Length',
        'attachment' => 'Attachment',
    ];

    $salesAgentName = trim(($client->salesman?->first_name ?? '') . ' ' . ($client->salesman?->last_name ?? ''));
@endphp

<div wire:ignore.self class="modal fade" id="cashierClientDetails_{{ $client->id }}" tabindex="-1" aria-labelledby="cashierClientDetailsLabel_{{ $client->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow">
            <div style="background-color: #004998" class="modal-header text-white">
                <div>
                    <h1 class="modal-title fs-5" id="cashierClientDetailsLabel_{{ $client->id }}">Client Information</h1>
                    <div class="small opacity-75">{{ $client->company_name ?? 'N/A' }}</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-lg-6">
                        <div class="border rounded p-3 h-100">
                            <h6 class="fw-bold mb-3">Client Details</h6>
                            <dl class="row mb-0">
                                <dt class="col-sm-5 text-muted">Sales List No.</dt>
                                <dd class="col-sm-7 fw-semibold">{{ $client->salesList_no ?? 'N/A' }}</dd>

                                <dt class="col-sm-5 text-muted">Company</dt>
                                <dd class="col-sm-7 fw-semibold">{{ $client->company_name ?? 'N/A' }}</dd>

                                <dt class="col-sm-5 text-muted">Email</dt>
                                <dd class="col-sm-7 fw-semibold">{{ $client->email ?? 'N/A' }}</dd>

                                <dt class="col-sm-5 text-muted">Contact Number</dt>
                                <dd class="col-sm-7 fw-semibold">{{ $client->contact_number ?? 'N/A' }}</dd>

                                <dt class="col-sm-5 text-muted">Address</dt>
                                <dd class="col-sm-7 fw-semibold">{{ $client->address ?? 'N/A' }}</dd>
                            </dl>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="border rounded p-3 h-100">
                            <h6 class="fw-bold mb-3">Sales Agent</h6>
                            <dl class="row mb-0">
                                <dt class="col-sm-5 text-muted">Added By</dt>
                                <dd class="col-sm-7 fw-semibold">{{ $salesAgentName ?: 'N/A' }}</dd>

                                <dt class="col-sm-5 text-muted">Department</dt>
                                <dd class="col-sm-7 fw-semibold">{{ $client->salesman?->department ?? 'N/A' }}</dd>

                                <dt class="col-sm-5 text-muted">Status</dt>
                                <dd class="col-sm-7">
                                    <span class="badge bg-primary">{{ $client->status ?? 'N/A' }}</span>
                                </dd>
                            </dl>

                            <h6 class="fw-bold mt-4 mb-3">Contact Person</h6>
                            <dl class="row mb-0">
                                <dt class="col-sm-5 text-muted">Name</dt>
                                <dd class="col-sm-7 fw-semibold">{{ $client->contact_person ?? 'N/A' }}</dd>

                                <dt class="col-sm-5 text-muted">Mobile Number</dt>
                                <dd class="col-sm-7 fw-semibold">{{ $client->contact_number_person ?? 'N/A' }}</dd>

                                <dt class="col-sm-5 text-muted">Bank Account</dt>
                                <dd class="col-sm-7 fw-semibold">{{ $client->bank_account_number ?? 'N/A' }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="border rounded p-3 mb-3">
                    <h6 class="fw-bold mb-3">Vehicle Specifications</h6>
                    @forelse ($vehicles as $index => $vehicle)
                        <div class="border rounded p-3 {{ $loop->last ? '' : 'mb-3' }}">
                            <div class="fw-semibold mb-3">Vehicle #{{ $index + 1 }}</div>
                            <div class="row g-3">
                                @foreach ($vehicleLabels as $field => $label)
                                    <div class="col-md-4">
                                        <div class="text-muted small">{{ $label }}</div>
                                        <div class="fw-semibold">{{ $vehicle[$field] ?? 'N/A' }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-muted">No vehicle specifications encoded yet.</div>
                    @endforelse
                </div>

                <div class="border rounded p-3">
                    <h6 class="fw-bold mb-3">Supporting Documents</h6>
                    @forelse ($client->supporting_documents as $index => $document)
                        <a href="{{ asset('storage/' . $document) }}" target="_blank" class="btn btn-outline-primary btn-sm me-2 mb-2">
                            <i class="fas fa-file-pdf me-1"></i>
                            View Document {{ $index + 1 }}
                        </a>
                    @empty
                        <div class="text-muted">No supporting documents uploaded.</div>
                    @endforelse
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
