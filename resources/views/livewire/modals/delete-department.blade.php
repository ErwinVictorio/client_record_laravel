<div>
    <x-delete-confirmation-modal :modal-id="'DeleteDepartment_'.$department_id" :label-id="'deleteDepartmentLabel_'.$department_id" :subject="$department_name" action="delete_department" :hide-event="'hide-delete-department-modal-'.$department_id" refresh-event="departments-updated" item-label="department" />
</div>
