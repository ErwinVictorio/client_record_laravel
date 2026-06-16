<div>
    <div wire:ignore.self class="modal fade" id="ClientSupportingDocuments_{{ $clientId }}" tabindex="-1" aria-labelledby="ClientSupportingDocumentsLabel_{{ $clientId }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-6 fw-bold" id="ClientSupportingDocumentsLabel_{{ $clientId }}">
                        Supporting Documents
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="fw-bold mb-2">{{ $companyName }}</div>

                    @if (session()->has('documents_message'))
                        <div class="alert alert-success">{{ session('documents_message') }}</div>
                    @endif
                    @if (session()->has('documents_error'))
                        <div class="alert alert-danger">{{ session('documents_error') }}</div>
                    @endif

                    @if (count($documents) > 0)
                        <div class="list-group">
                            @foreach ($documents as $index => $document)
                                <div class="list-group-item d-flex justify-content-between align-items-center gap-3" wire:key="client-document-{{ $clientId }}-{{ $index }}">
                                    <div class="text-truncate">
                                        <i class="fas fa-file-pdf text-danger me-2"></i>
                                        {{ basename($document) }}
                                    </div>
                                    <div class="btn-group">
                                        <a class="btn btn-sm btn-outline-primary" href="{{ asset('storage/' . $document) }}" target="_blank">
                                            View
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeDocument({{ $index }})">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-muted text-center py-4">
                            No supporting documents uploaded yet.
                        </div>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
