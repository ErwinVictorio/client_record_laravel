@php
    $maintenanceVehicles = $record->vehicle_specifications ?? [];
    $primaryMaintenanceVehicle = $maintenanceVehicles[0] ?? [];
    $primarySerialOrPlateNumber = $primaryMaintenanceVehicle['serial_or_plate_number'] ?? $record->serial_number;
    $vehicleSpecificationLabels = [
        'brand' => 'Brand',
        'model' => 'Model',
        'serial_or_plate_number' => 'Serial / Plate Number',
        'loading_capacity' => 'Loading Capacity',
        'lifting_height' => 'Lifting Height',
        'mast_type' => 'Mast Type',
        'power_type' => 'Power Type',
        'tire' => 'Tire',
        'fork_length' => 'Fork Length',
        'attachment' => 'Attachment',
    ];
@endphp

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
                    @if ($showJobOrderNumber ?? true)
                        <div class="col-md-6">
                            <div class="small text-muted">Job Order Number</div>
                            <div class="fw-semibold">{{ $record->job_order_number ?? 'N/A' }}</div>
                        </div>
                    @endif
                    <div class="col-md-6">
                        <div class="small text-muted">Serial / Plate Number</div>
                        <div class="fw-semibold">{{ $primarySerialOrPlateNumber ?? 'N/A' }}</div>
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
                    @if (count($maintenanceVehicles) > 0)
                        <div class="col-12">
                            <div class="small text-muted fw-bold mb-2">Vehicle Specifications</div>
                            @foreach ($maintenanceVehicles as $vehicleIndex => $vehicle)
                                <div class="border rounded p-3 mb-2">
                                    <div class="fw-bold text-secondary mb-2">Vehicle #{{ $vehicleIndex + 1 }}</div>
                                    <div class="row g-2">
                                        @foreach ($vehicleSpecificationLabels as $key => $label)
                                            <div class="col-md-4">
                                                <div class="small text-muted">{{ $label }}</div>
                                                <div class="fw-semibold">{{ filled($vehicle[$key] ?? null) ? $vehicle[$key] : 'N/A' }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
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
