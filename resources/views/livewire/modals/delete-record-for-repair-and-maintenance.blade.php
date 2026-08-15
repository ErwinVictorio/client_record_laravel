<div>
    <x-delete-confirmation-modal :modal-id="'DeleteMaintenanceRecord_'.$recordId" :label-id="'deleteMaintenanceRecordLabel_'.$recordId" :subject="$company_name" action="delete_Record_maintenance" :hide-event="'hide-maintenance-record-modal-'.$recordId" refresh-event="maintenance-records-updated" item-label="maintenance record" />
</div>
