<div>
    <div class="modal fade" id="msdEditModal_{{ $recordId }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="msdEditModalLabel_{{ $recordId }}" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form wire:submit.prevent="updateRecord">
                    <div class="modal-header">
                        <h5 class="modal-title" id="msdEditModalLabel_{{ $recordId }}">
                            <i class="fas fa-pen-to-square me-2"></i>Edit MSD Record
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($client)
                        <div class="alert alert-light border mb-3">
                            <div class="fw-bold">{{ $client->company_name }}</div>
                            <div class="small text-muted">
                                Sale Control No.: {{ $client->salesList_no ?? 'N/A' }} ·
                                Vehicle/Unit: {{ $client->item_name ?? 'N/A' }}
                            </div>
                        </div>
                        @elseif ($maintenanceRecord)
                        <div class="alert alert-light border mb-3">
                            <div class="fw-bold">{{ $maintenanceRecord->company_name }}</div>
                            <div class="small text-muted">
                                Contact: {{ $maintenanceRecord->contact_number ?? 'N/A' }} ·
                                Contact Person: {{ $maintenanceRecord->contact_person ?? 'N/A' }}
                            </div>
                        </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Type</label>
                                <select class="form-select @error('changeType') is-invalid @enderror" wire:model.live="changeType">
                                    <option value="">Select Type</option>
                                    <option value="WITH CHANGE">With Charge</option>
                                    <option value="WITHOUT CHANGE">Without Charge</option>
                                </select>
                                @error('changeType') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            @if ($clientId)
                            <div class="col-md-4">
                                <label class="form-label">Warranty Type</label>
                                <select class="form-select @error('warrantyType') is-invalid @enderror" wire:model="warrantyType" @disabled($changeType === 'WITH CHANGE')>
                                    <option value="">Select Warranty</option>
                                    <option value="UNDER WARRANTY">UNDER WARRANTY</option>
                                    <option value="OUT OF WARRANTY">OUT OF WARRANTY</option>
                                </select>
                                @error('warrantyType') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            @endif

                            <div class="col-md-4">
                                <label class="form-label">Service Type</label>
                                <select class="form-select @error('serviceType') is-invalid @enderror" wire:model.live="serviceType">
                                    <option value="">Select Service Type</option>
                                    <option value="PMS">PMS</option>
                                    <option value="Other">Other</option>
                                </select>
                                @error('serviceType') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            @if (in_array($serviceType, ['PMS', 'Other'], true))
                            <div class="col-md-4">
                                <label class="form-label">Number of PMS</label>
                                <input type="text" class="form-control @error('pmsNumber') is-invalid @enderror" wire:model="pmsNumber">
                                @error('pmsNumber') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            @endif

                            <div class="col-md-4">
                                <label class="form-label">JO Number</label>
                                <input type="text" class="form-control @error('jobOrderNumber') is-invalid @enderror" wire:model="jobOrderNumber">
                                @error('jobOrderNumber') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Date JO</label>
                                <input type="date" class="form-control @error('jobOrderDate') is-invalid @enderror" wire:model="jobOrderDate">
                                @error('jobOrderDate') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @script
    <script>
        $wire.on('hide-msd-edit-modal', () => {
            const modal = document.getElementById('msdEditModal_{{ $recordId }}');
            window.bootstrap.Modal.getOrCreateInstance(modal).hide();
        });
    </script>
    @endscript
</div>
