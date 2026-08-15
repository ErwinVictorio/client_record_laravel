<div>
    <x-delete-confirmation-modal :modal-id="'DeleteAutoRecord_'.$recordId" :label-id="'deleteAutoRecordLabel_'.$recordId" :subject="$company_name" action="delete_Record_AutoRepair" :hide-event="'hide-auto-repair-record-modal-'.$recordId" refresh-event="auto-repair-records-updated" item-label="auto repair record" />
</div>
