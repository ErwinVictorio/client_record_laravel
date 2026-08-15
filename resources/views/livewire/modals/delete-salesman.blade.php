<div>
    <x-delete-confirmation-modal
        :modal-id="'deleteSalesmanModal_'.$salesmanID"
        :label-id="'deleteSalesmanModalLabel_'.$salesmanID"
        :subject="$name ? trim($name->first_name.' '.$name->last_name) : null"
        action="onDeleteSalesman"
        :hide-event="'hide-delete-salesman-modal-'.$salesmanID"
        refresh-event="salesmen-updated"
        item-label="salesman"
    />
</div>
