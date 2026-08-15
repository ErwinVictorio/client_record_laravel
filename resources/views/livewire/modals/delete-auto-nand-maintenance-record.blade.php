<div>
    <x-delete-confirmation-modal modal-id="deleteAutoRecordMintenance" label-id="deleteAutoMaintenanceRecordLabel" :subject="$company_name" action="destroyClient" hide-event="hide-auto-maintenance-delete-modal" refresh-event="maintenance-records-updated" item-label="maintenance record" />
</div>
