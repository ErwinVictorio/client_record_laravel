<div wire:ignore.self class="modal fade" id="MaintenanceRecordInfo_{{ $record->id }}" tabindex="-1" aria-labelledby="MaintenanceRecordInfoLabel_{{ $record->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="MaintenanceRecordInfoLabel_{{ $record->id }}">Client Information</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="small text-muted">Company Name</div>
                        <div class="fw-semibold">{{ $record->company_name }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted">Job Order Number</div>
                        <div class="fw-semibold">{{ $record->job_order_number ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted">Serial Number</div>
                        <div class="fw-semibold">{{ $record->serial_number ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted">Date Sold</div>
                        <div class="fw-semibold">{{ $record->date_sold?->format('F d, Y') ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted">Email</div>
                        <div class="fw-semibold">{{ $record->email }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted">Contact Number</div>
                        <div class="fw-semibold">{{ $record->contact_number }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted">Contact Person</div>
                        <div class="fw-semibold">{{ $record->contact_person }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted">Contact Person Number</div>
                        <div class="fw-semibold">{{ $record->contact_number_person }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted">Bank Account Number</div>
                        <div class="fw-semibold">{{ $record->bank_account_number ?? 'N/A' }}</div>
                    </div>
                    <div class="col-12">
                        <div class="small text-muted">Address</div>
                        <div class="fw-semibold">{{ $record->address }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted">Created</div>
                        <div class="fw-semibold">{{ $record->created_at?->format('F d, Y h:i A') ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted">Last Updated</div>
                        <div class="fw-semibold">{{ $record->updated_at?->format('F d, Y h:i A') ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
