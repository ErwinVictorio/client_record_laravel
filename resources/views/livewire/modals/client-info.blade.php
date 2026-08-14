<div>
    <div wire:ignore.self wire:key="client-modal_{{ $clientId }}" class="modal fade" id="clientModal_{{ $clientId }}" tabindex="-1" aria-labelledby="clientModalLabel_{{ $clientId }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
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
