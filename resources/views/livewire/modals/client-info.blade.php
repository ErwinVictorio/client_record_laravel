@php
    $vehicles = $client?->vehicle_specifications ?? [];

    if (is_string($vehicles)) {
        $vehicles = json_decode($vehicles, true);
    }

    $vehicles = is_array($vehicles) ? $vehicles : [];
    $hasPersonalName = $client && collect([$client->first_name, $client->middle_name, $client->last_name])
        ->contains(fn ($name) => filled($name));
    $salesAgentName = $client
        ? trim(($client->salesman?->first_name ?? '') . ' ' . ($client->salesman?->last_name ?? ''))
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
    <div wire:ignore.self wire:key="client-modal_{{ $clientId }}" class="modal fade" id="clientModal_{{ $clientId }}" tabindex="-1" aria-labelledby="clientModalLabel_{{ $clientId }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form wire:submit.prevent="{{ $showRejectReason ? 'rejectClient' : ($showSoldConfirmation ? 'markAsSold' : 'openSoldConfirmation') }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="clientModalLabel_{{ $clientId }}">Client Approval</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        @if (session()->has('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if (session()->has('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        @if ($client)
                            <div class="row g-3 mb-3">
                                <div class="col-lg-7">
                                    <div class="border rounded p-3 h-100">
                                        <h6 class="fw-bold mb-3">Client Information</h6>
                                        <dl class="row mb-0">
                                            <dt class="col-sm-4 text-muted">{{ $hasPersonalName ? 'Client Name' : 'Company' }}</dt>
                                            <dd class="col-sm-8 fw-semibold">{{ $client->display_name }}</dd>

                                            <dt class="col-sm-4 text-muted">Client Type</dt>
                                            <dd class="col-sm-8">{{ $hasPersonalName ? 'Personal' : 'Company' }}</dd>

                                            <dt class="col-sm-4 text-muted">Contact Number</dt>
                                            <dd class="col-sm-8">{{ $client->contact_number ?: 'N/A' }}</dd>

                                            <dt class="col-sm-4 text-muted">Email</dt>
                                            <dd class="col-sm-8 text-break">{{ $client->email ?: 'N/A' }}</dd>

                                            <dt class="col-sm-4 text-muted">Address</dt>
                                            <dd class="col-sm-8 text-break">{{ $client->address ?: 'N/A' }}</dd>
                                        </dl>
                                    </div>
                                </div>

                                <div class="col-lg-5">
                                    <div class="border rounded p-3 h-100">
                                        <h6 class="fw-bold mb-3">Approval Details</h6>
                                        <dl class="row mb-0">
                                            <dt class="col-sm-5 text-muted">Status</dt>
                                            <dd class="col-sm-7"><span class="badge bg-warning text-dark">{{ $client->status ?: 'N/A' }}</span></dd>

                                            <dt class="col-sm-5 text-muted">Salesman</dt>
                                            <dd class="col-sm-7">{{ $salesAgentName ?: 'N/A' }}</dd>

                                            @unless ($hasPersonalName)
                                                <dt class="col-sm-5 text-muted">Contact Person</dt>
                                                <dd class="col-sm-7">{{ $client->contact_person ?: 'N/A' }}</dd>

                                                <dt class="col-sm-5 text-muted">Person's No.</dt>
                                                <dd class="col-sm-7">{{ $client->contact_number_person ?: 'N/A' }}</dd>
                                            @endunless
                                        </dl>
                                    </div>
                                </div>
                            </div>

                            <div class="border rounded p-3 mb-3">
                                <h6 class="fw-bold mb-3">Vehicle / Product Information</h6>
                                <div class="row g-3 mb-3">
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="small text-muted">Vehicle / Item</div>
                                        <div class="fw-semibold">{{ $client->item_name ?: 'N/A' }}</div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="small text-muted">Model Number</div>
                                        <div class="fw-semibold">{{ $client->model_number ?: 'N/A' }}</div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="small text-muted">Year Model</div>
                                        <div class="fw-semibold">{{ $client->year_model ?: 'N/A' }}</div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="small text-muted">Quantity</div>
                                        <div class="fw-semibold">{{ $client->quantity ?: 'N/A' }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted">General Specification</div>
                                        <div class="text-break">{{ $client->specification ?: 'N/A' }}</div>
                                    </div>
                                </div>

                                @forelse ($vehicles as $index => $vehicle)
                                    <div class="bg-light border rounded p-3 {{ $loop->last ? '' : 'mb-3' }}">
                                        <div class="fw-semibold mb-2">Vehicle #{{ $index + 1 }}</div>
                                        <div class="row g-2">
                                            @foreach ($vehicleLabels as $field => $label)
                                                @if (filled($vehicle[$field] ?? null))
                                                    <div class="col-sm-6 col-lg-4">
                                                        <div class="small text-muted">{{ $label }}</div>
                                                        <div class="fw-semibold text-break">{{ $vehicle[$field] }}</div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="small text-muted">No detailed vehicle specifications encoded yet.</div>
                                @endforelse
                            </div>
                        @else
                            <div class="alert alert-danger">Client information is no longer available.</div>
                        @endif

                        @if ($showRejectReason)
                            <div class="form-floating">
                                <textarea wire:model="rejectionReason" class="form-control" placeholder="Reason for rejection" id="rejectionReason_{{ $clientId }}" style="height: 120px"></textarea>
                                <label for="rejectionReason_{{ $clientId }}">Reason for rejection</label>
                            </div>
                            @error('rejectionReason')
                                <span class="text-danger small d-block mt-1">{{ $message }}</span>
                            @enderror
                        @elseif ($showSoldConfirmation)
                            <div class="alert alert-warning mb-0">
                                <div class="fw-bold mb-1">Confirm Sold Transaction</div>
                                Are you sure you want to mark this client as Sold? This action will update the client's current status.
                            </div>
                        @else
                            <p class="mb-0">Choose whether to reject this client or mark the sale as sold.</p>
                        @endif
                    </div>

                    <div class="modal-footer">
                        @if ($showRejectReason)
                            <button type="button" class="btn btn-secondary" wire:click="cancelReject">Cancel</button>
                            <button type="submit" class="btn btn-danger" wire:loading.attr="disabled" wire:target="rejectClient">
                                <span wire:loading wire:target="rejectClient" class="spinner-border spinner-border-sm me-1"></span>
                              Confirm Reject
                            </button>
                        @elseif ($showSoldConfirmation)
                            <button type="button" class="btn btn-secondary" wire:click="cancelSoldConfirmation">Cancel</button>
                            <button type="submit" class="btn btn-success" wire:loading.attr="disabled" wire:target="markAsSold">
                                <span wire:loading wire:target="markAsSold" class="spinner-border spinner-border-sm me-1"></span>
                                Yes, Mark as Sold
                            </button>
                        @else
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-outline-danger" wire:click="showRejectForm">Reject</button>
                            <button style="background-color: #0d629b" type="button" class="btn text-light" wire:click="openSoldConfirmation">
                                Sold
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
