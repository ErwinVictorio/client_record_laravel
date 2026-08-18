# Revised Changes Documentation

**Project:** Client Record Laravel  
**Revision date:** August 15, 2026

This document summarizes the functional, database, interface, validation, and test changes completed during the current revision.

## Client Approval Review Summary

The Client Approval modal now presents the important client and vehicle information before an approver chooses **Reject** or **Sold**.

- Client name or company, type, contact details, address, current status, and assigned salesman are shown.
- Company records also show their contact person and contact number.
- Vehicle/product summary shows the item, model number, year model, quantity, and general specification.
- Detailed JSON vehicle specifications use the standard vehicle labels and omit empty optional entries.
- Older records without detailed vehicle specifications remain supported through the existing product-column summary and `N/A` fallbacks.
- The modal is now large, responsive, and scrollable while keeping the existing confirmation, rejection validation, and stale-status protection intact.
- A focused feature test verifies that client, salesman, and vehicle information render before approval.

### Main affected files

- `app/Livewire/Modals/ClientInfo.php`
- `resources/views/livewire/modals/client-info.blade.php`
- `tests/Feature/ClientApprovalTest.php`

## Super Admin Delete Confirmation

- Redesigned the client delete confirmation with a focused warning, clear client name, and responsive action area.
- Added disabled/loading states to prevent accidental duplicate deletion requests.
- The modal now explicitly closes through Bootstrap after a successful Livewire deletion.
- After the closing transition, the modal instance is disposed and any orphaned backdrop/body state is safely removed when no other modal is open.
- Missing or already-deleted records now show an inline error instead of throwing a page-level exception.

### Main affected files

- `app/Livewire/Modals/ClientDeletePart2.php`
- `resources/views/livewire/modals/client-delete-part2.blade.php`

The same backdrop-safe close sequence and improved confirmation interface were also applied to the Admin per-client delete modal:

- `app/Livewire/Modals/DeleteClient.php`
- `resources/views/livewire/modals/delete-client.blade.php`

All remaining delete confirmation flows now use the same reusable UI and backdrop-safe close lifecycle, covering salesman, department, auto repair, and maintenance records. Each list refresh waits until its modal is fully hidden.

## Edit Modal Interface Refresh

All Livewire edit modals now use a consistent responsive interface:

- Scrollable dialogs with elevated, borderless modal containers
- Branded blue headers with accessible white close buttons
- Light modal backgrounds with grouped white information cards
- Clear section headings for client, product, account, stock-out, and service information
- Consistent Cancel and primary Save/Update actions
- Loading spinners and disabled submit buttons while updates are processing
- Improved spacing and responsive layouts for desktop and mobile

The refresh covers client information, salesman, department, auto repair, repair and maintenance, and After Sales MSD edit modals.

## 1. Vehicle Specifications

Two new optional fields were added to the vehicle specification arrays:

- `engine`
- `engine_series`

The fields are supported by:

- Client Status Update vehicle form
- Repair and Maintenance Create Client form
- Vehicle array initialization
- Livewire validation
- Client specification summary
- Warehouse client details
- Cashier client details
- Maintenance record details
- After Sales Unit Specifications display

Older vehicle records remain compatible. Missing or empty Engine and Engine Series values display as `N/A`.

### Main affected files

- `app/Livewire/Modals/ClientStatusUpdate.php`
- `app/Livewire/Modals/CreateRepairAndMaintenaceRecord.php`
- `resources/views/livewire/modals/client-status-update.blade.php`
- `resources/views/livewire/modals/create-repair-and-maintenace-record.blade.php`
- `resources/views/livewire/modals/maintenance-record-info.blade.php`
- `resources/views/livewire/warehouse/partials/client-details-modal.blade.php`
- `resources/views/livewire/admin/pages/partials/cashier-client-details-modal.blade.php`
- `resources/views/livewire/after-sales/dashboard.blade.php`

No new vehicle database columns were required because vehicle specifications are stored in the existing JSON fields.

## 2. Cashier Client Approval: Sold and Reject

The old product detail inputs were removed from the Client Approval modal:

- Item Name
- Product Model
- Quantity
- Specification

They were replaced with two actions:

- `Sold`
- `Reject`

Existing database columns and previously saved product values were not removed or modified.

### Sold flow

1. The cashier clicks **Sold**.
2. A confirmation warning appears.
3. The status changes to `Sold` only after clicking **Yes, Mark as Sold**.
4. Any old rejection reason is cleared.
5. A stale-status check prevents processing a client that is no longer `For Approval`.

### Reject flow

1. The cashier clicks **Reject**.
2. A required **Reason for rejection** field appears.
3. After confirmation, the client status changes from `For Approval` to `Pending`.
4. The entered reason is saved in `clients.rejection_reason`.
5. Cashier lists and counters refresh through the `clients-updated` event.

### Migration

`database/migrations/2026_08_14_000001_add_rejection_reason_to_clients_table.php`

Adds only:

```text
clients.rejection_reason TEXT NULL
```

No existing client columns are deleted.

### Main affected files

- `app/Livewire/Modals/ClientInfo.php`
- `resources/views/livewire/modals/client-info.blade.php`
- `app/Models/clients.php`
- `app/Livewire/Admin/Pages/CahierDashboard.php`
- `app/Livewire/SuperAdmin/Page/Cashier.php`

## 3. Repeated Client Registration by the Same Salesman

The Create Client duplicate ownership rule was revised.

### Current behavior

- A salesman can repeatedly register the same client under their own account.
- The same company name, contact number, and email are allowed when existing matching records belong to the logged-in salesman.
- Registration remains blocked when any matching client belongs to another salesman.
- The blocking message is:

```text
The client is already taken by another salesman!
```

The project already contains an older migration that removes the unique email index from the clients table, so repeated client email addresses are supported.

### Duplicate validation details

The create-client modal validates duplicate ownership in `app/Livewire/Modals/ClientCreate.php`.

For corporate clients, the system normalizes the company name before comparison:

- Converts the company name to uppercase.
- Removes spaces, punctuation, and other non-alphanumeric characters.
- Ignores the selected company suffix when checking the base company name.

Because the suffix is ignored, these are treated as the same base client name:

```text
Test Company
Test Company INC.
Test-Company, Inc.
```

For personal clients, the system uses the normalized full name from:

- First name
- Middle name
- Last name

The duplicate check only looks at records owned by another salesman:

```text
clients.salesman_id != current logged-in salesman id
```

If a record from another salesman matches any of the following, the create action is blocked:

- Same normalized company name or personal full name
- Same contact number
- Same email address

This means a user may see the warning even when the exact company name does not appear in their own dashboard. The match can come from a client owned by another salesman, or from a different company record that reuses the same contact number or email address.

Example:

```text
Input company: Test Company INC.
Input contact: 0999729154

Existing other-salesman record:
Company: Weimann PLC
Contact: 0999729154

Result:
Blocked, because the contact number is already assigned to another salesman.
```

When investigating the warning locally, check the client table for the entered company name, email, and contact number across all salesmen, not only the current dashboard list.

### Main affected file

- `app/Livewire/Modals/ClientCreate.php`

## 4. After Sales Vehicle Information

The After Sales Unit Specifications card now displays all available vehicle information:

- Brand
- Model
- Engine
- Engine Series
- Loading Capacity
- Lifting Height
- Mast Type
- Power Type
- Tire
- Fork Length
- Attachment

Blank values display as `N/A`, including Attachment.

### Main affected file

- `resources/views/livewire/after-sales/dashboard.blade.php`

## 5. MSD Finish and Cancel Status

The Edit MSD Record flow supports:

- `Finish`
- `Cancel`

When `Cancel` is selected, **Reason for Cancellation** becomes required. Selecting another status clears the cancellation reason.

The JO Information table now contains a Status column:

- Finish uses a green badge.
- Cancel uses a red badge.
- The cancellation reason is displayed below a cancelled status.
- Records without a status display `N/A`.

### Existing database fields used

- `after_sales_records.status_update`
- `after_sales_records.cancellation_reason`

### Main affected files

- `app/Livewire/Modals/AfterSalesEditRecord.php`
- `app/Models/AfterSalesRecord.php`
- `resources/views/livewire/modals/after-sales-edit-record.blade.php`
- `resources/views/livewire/after-sales/dashboard.blade.php`

## 6. Duplicate JO Number Protection

JO Numbers are now protected from duplicate use.

### Create MSD behavior

- JO availability is checked live while typing.
- Leading and trailing spaces are ignored.
- Matching is case-insensitive at the application level.
- Both tables are checked:
  - `after_sales_records`
  - `client_record_for_maintenance_and_repairs`
- A duplicate JO displays an immediate warning and input validation error.
- **Save Record** is disabled while the JO is known to be duplicated.
- Availability is checked again immediately before saving.
- Unique-constraint race conditions are converted to a user-facing warning.

Example warning:

```text
JO Number 260801 is already in use. Please enter another JO Number.
```

### Edit MSD behavior

- A record can keep its own current JO Number.
- A record cannot change its JO Number to one owned by another After Sales or Maintenance record.
- Linked Repair and Maintenance records are excluded from false duplicate detection when editing their corresponding MSD record.

### Database protection

`database/migrations/2026_08_14_000002_add_unique_index_to_after_sales_job_order_number.php`

Adds the unique index:

```text
after_sales_records_job_order_number_unique
```

Before adding the index, the migration audits normalized existing JO Numbers. It stops with a clear error when legacy duplicates exist and does not delete or rewrite any records.

This migration was successfully applied locally as migration batch 10.

### Main affected files

- `app/Livewire/AfterSales/Dashboard.php`
- `app/Livewire/Modals/AfterSalesEditRecord.php`
- `resources/views/livewire/after-sales/dashboard.blade.php`

## 7. Supporting Document Uploads

Supporting Document uploads now accept:

- PDF files
- Browser-recognized image MIME types (`image/*`)
- A combination of PDFs and images in the multi-file workflow

Each file retains the existing maximum size of 5 MB.

### Browser filter

```text
accept="application/pdf,image/*"
```

### Server validation

```text
nullable|file|mimetypes:application/pdf,image/*|max:5120
```

Files that are neither images nor PDFs remain blocked. Labels and validation messages were updated to say **PDF or Images** instead of PDF only.

### Main affected files

- `app/Livewire/Modals/ClientStatusUpdate.php`
- `resources/views/livewire/modals/client-status-update.blade.php`

## 8. Added Feature Tests

The following focused test files were added:

- `tests/Feature/ClientApprovalTest.php`
  - Sold confirmation and update
  - Rejection reason requirement
  - Rejected status transition to Pending
  - Existing product values remain unchanged
  - Stale approval action protection

- `tests/Feature/ClientCreateDuplicateOwnershipTest.php`
  - Repeated registration by the same salesman
  - Blocking a matching client owned by another salesman

- `tests/Feature/AfterSalesJobOrderUniquenessTest.php`
  - Live duplicate JO warning
  - Case-insensitive and trimmed comparison
  - Cross-table maintenance JO conflict
  - Successful available JO creation
  - Edit self-exclusion and duplicate edit blocking

- `tests/Feature/ClientSupportingDocumentUploadTest.php`
  - Combined image and PDF uploads
  - Invalid non-image and non-PDF rejection

All newly added focused test suites passed during implementation.

## 9. Deployment and Verification

Run all outstanding migrations in the target environment:

```powershell
php artisan migrate
```

Clear cached Laravel files after deployment:

```powershell
php artisan optimize:clear
```

Run the focused revision tests:

```powershell
php artisan test tests/Feature/ClientApprovalTest.php
php artisan test tests/Feature/ClientCreateDuplicateOwnershipTest.php
php artisan test tests/Feature/AfterSalesJobOrderUniquenessTest.php
php artisan test tests/Feature/ClientSupportingDocumentUploadTest.php
```

For public supporting-document access, confirm that the Laravel storage link exists:

```powershell
php artisan storage:link
```

## 10. Data-Safety Notes

- No existing client or vehicle columns were removed.
- Existing product columns remain intact even though their inputs were removed from Client Approval.
- Vehicle Engine and Engine Series use existing JSON storage.
- Rejection adds one nullable column only.
- JO uniqueness adds an index only and does not delete duplicate records automatically.
- Existing uploaded supporting-document paths remain compatible.

## 11. Vehicle Specification Dropdowns with Other Values

The free-text inputs for Mast Type, Power Type, and Tire were replaced with consistent dropdowns in all vehicle specification create/update workflows.

### Mast Type options

- `M`
- `ZM`
- `ZSM`
- `Other`

### Power Type options

- `Electric-Li`
- `Electric Lead Acid`
- `Diesel`
- `Other`

### Tire options

- `Solid`
- `Pneumatic`
- `Other`

Selecting `Other` displays a required manual text input. The manual value is saved as the final vehicle specification value instead of the literal word `Other`.

Existing custom values remain compatible. A value outside the configured options automatically loads with `Other` selected and the existing value placed in the manual input.

The behavior is shared by:

- Client Status Update
- Repair and Maintenance Create Client
- Repair and Maintenance Edit Client

The Maintenance Edit modal now loads and edits the complete `vehicle_specifications` JSON array. Temporary dropdown-selection and manual-input helper fields are removed before persistence, so only clean final vehicle values are stored.

No migration was required because these values continue to use the existing vehicle specification JSON columns.

## 12. Salesman and Super Admin View Client Information

A read-only `View Info` action was added to the client tables on:

- Salesman My Client
- Super Admin Finish Vehicle

The shared View Client Details modal now displays complete client/contact information, status, salesman, dates, legacy product fields, rejection reason, every encoded vehicle, and all PDF/image supporting-document links.

Every vehicle displays Brand, Model, Engine, Engine Series, Loading Capacity, Lifting Height, Mast Type, Power Type, Tire, Fork Length, and Attachment. Missing values display as `N/A`.

The modal query is ownership-restricted: the logged-in user can only load client details whose `salesman_id` matches their account. The `clientId` Livewire property is locked against browser-side mutation.

No migration was required. A database-portable full-name search condition was also added to the Salesman page so it works on both MySQL and SQLite test environments.
