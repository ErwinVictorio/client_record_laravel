<div>
    <div class="modal fade" id="msdEditModal_{{ $recordId }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="msdEditModalLabel_{{ $recordId }}" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <form wire:submit.prevent="updateRecord">
                    <div class="modal-header text-white" style="background-color: #004998">
                        <h5 class="modal-title" id="msdEditModalLabel_{{ $recordId }}">
                            <i class="fas fa-pen-to-square me-2"></i>Edit MSD Record
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body bg-light p-4">
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

                        <div class="row g-3 bg-white border rounded-3 p-3 p-md-4 shadow-sm">
                            <h6 class="fw-bold mb-0">Service Update</h6>
                            <div class="col-md-4">
                                <label class="form-label">Type</label>
                                <select disabled class="form-select @error('changeType') is-invalid @enderror" wire:model.live="changeType">
                                    <option value="">Select Type</option>
                                    <option value="WITH CHARGE">With Charge</option>
                                    <option value="WITHOUT CHARGE">Without Charge</option>
                                </select>
                                @error('changeType') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            @if ($clientId)
                            <div class="col-md-4">
                                <label class="form-label">Warranty Type</label>
                                <select disabled class="form-select @error('warrantyType') is-invalid @enderror" wire:model="warrantyType" @disabled($changeType==='WITH CHARGE' )>
                                    <option value="">Select Warranty</option>
                                    <option value="UNDER WARRANTY">UNDER WARRANTY</option>
                                    <option value="OUT OF WARRANTY">OUT OF WARRANTY</option>
                                </select>
                                @error('warrantyType') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            @endif

                            <div class="col-md-4">
                                <label class="form-label">Service Type</label>
                                <select disabled class="form-select @error('serviceType') is-invalid @enderror" wire:model.live="serviceType">
                                    <option value="">Select Service Type</option>
                                    <option value="PMS">PMS</option>
                                    <option value="Other">Other</option>
                                </select>
                                @error('serviceType') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            @if (in_array($serviceType, ['PMS', 'Other'], true))
                            <div class="col-md-4">
                                <label class="form-label">Number of PMS</label>
                                <input disabled type="text" class="form-control @error('pmsNumber') is-invalid @enderror" wire:model="pmsNumber">
                                @error('pmsNumber') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            @endif

                            <div class="col-md-4">
                                <label class="form-label">JO Number</label>
                                <input disabled type="text" class="form-control @error('jobOrderNumber') is-invalid @enderror" wire:model="jobOrderNumber">
                                @error('jobOrderNumber') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Date JO</label>
                                <input disabled type="date" class="form-control @error('jobOrderDate') is-invalid @enderror" wire:model="jobOrderDate">
                                @error('jobOrderDate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea disabled rows="3" class="form-control @error('description') is-invalid @enderror" wire:model="description"></textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Assigned To</label>
                                <input type="text" class="form-control @error('assign_mechanic') is-invalid @enderror" wire:model="assign_mechanic" placeholder="Mechanic Name">
                                @error('assign_mechanic') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Status Update</label>
                                <select class="form-select @error('status_update') is-invalid @enderror" wire:model.live="status_update">
                                    <option value="">Select Status</option>
                                    <option value="Finish">Finish</option>
                                    <option value="Cancel">Cancel</option>
                                </select>
                                @error('status_update') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- if status is "Cancel" -->
                            @if ($status_update === 'Cancel')
                                <div class="col-12">
                                    <label class="form-label">Reason for Cancellation</label>
                                    <textarea placeholder="Enter reason for cancellation" rows="2" class="form-control @error('cancellation_reason') is-invalid @enderror" wire:model="cancellation_reason"></textarea>
                                    @error('cancellation_reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif

                            <div class="col-12">
                                <label class="form-label">Remarks</label>
                                <textarea placeholder="Enter remarks" rows="2" class="form-control @error('remarks') is-invalid @enderror" wire:model="remarks"></textarea>
                                @error('remarks') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-white">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4" wire:loading.attr="disabled" wire:target="updateRecord"><span wire:loading wire:target="updateRecord" class="spinner-border spinner-border-sm me-1"></span>Update Record</button>
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
