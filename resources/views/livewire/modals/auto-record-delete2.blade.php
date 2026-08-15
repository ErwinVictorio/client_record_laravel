<div>
    <x-delete-confirmation-modal modal-id="deleteAutoRecord" label-id="deleteAutoRecordLabel" :subject="$company_name" action="destroyClient" hide-event="hide-auto-record-delete-modal" refresh-event="auto-repair-records-updated" item-label="auto repair record" />
</div>
