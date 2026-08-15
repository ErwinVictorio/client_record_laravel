<div>
    <div wire:ignore.self class="modal fade" id="deleteClientModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteClientModalLabel" aria-describedby="deleteClientModalDescription" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h1 class="modal-title fs-5 fw-semibold" id="deleteClientModalLabel">Delete Client</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form wire:submit.prevent="destroyClient">
                    <div class="modal-body px-4 py-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="d-flex align-items-center justify-content-center flex-shrink-0 rounded-circle bg-danger-subtle text-danger" style="width: 48px; height: 48px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                                </svg>
                            </div>
                            <div class="flex-grow-1">
                                <h2 class="h6 fw-bold mb-2">Permanently delete this client?</h2>
                                <p class="text-muted mb-3" id="deleteClientModalDescription">
                                    You are about to delete
                                    <strong class="text-body">{{ $company_name ?: 'this client' }}</strong>.
                                    This action cannot be undone.
                                </p>
                                <div class="alert alert-warning small mb-0" role="alert">
                                    The client will be removed from the client list and can no longer be viewed or edited.
                                </div>
                            </div>
                        </div>

                        @if (session()->has('error'))
                            <div class="alert alert-danger mt-3 mb-0" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer border-0 bg-light px-4 py-3">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" wire:loading.attr="disabled" wire:target="destroyClient">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-danger" wire:loading.attr="disabled" wire:target="destroyClient" @disabled(! $clientId)>
                            <span wire:loading.remove wire:target="destroyClient">Delete Client</span>
                            <span wire:loading wire:target="destroyClient">
                                <span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                                Deleting...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @script
    <script>
        $wire.on('hide-client-delete-modal', () => {
            const modalElement = document.getElementById('deleteClientModal');

            if (!modalElement) {
                $wire.dispatch('clients-updated');
                return;
            }

            const modal = window.bootstrap.Modal.getOrCreateInstance(modalElement);

            modalElement.addEventListener('hidden.bs.modal', () => {
                window.bootstrap.Modal.getInstance(modalElement)?.dispose();

                if (!document.querySelector('.modal.show')) {
                    document.querySelectorAll('.modal-backdrop').forEach((backdrop) => backdrop.remove());
                    document.body.classList.remove('modal-open');
                    document.body.style.removeProperty('overflow');
                    document.body.style.removeProperty('padding-right');
                }

                $wire.dispatch('clients-updated');
            }, { once: true });

            modal.hide();
        });
    </script>
    @endscript
</div>
