# Project Change Notes

This file tracks the system changes, fixes, and implementation notes we make during development.

## 2026-06-30

### Salesman Repair & Maintenance JO Removal

- Removed the JO Number search input and Search button from the Salesman Repair & Maintenance page.
- Removed the Job Order Number column and values from the Salesman records table.
- Hid Job Order Number from the Salesman create, edit, and record information modals.
- Added a shared modal context flag so the current Admin and Super Admin pages keep their existing JO fields and required validation.
- Locked the context flag after component initialization to prevent it from being changed through a Livewire request.
- New Salesman Repair & Maintenance records are now saved with `job_order_number = NULL`.
- Existing JO Numbers are preserved when a Salesman edits an older record.
- Removed the unused `jobOrderSearch` property, search action, and JO query filter from the Salesman Livewire component.
- Added migration `2026_06_30_000001_make_job_order_number_nullable_on_maintenance_records_table.php`.

### Dynamic Repair & Maintenance Vehicle Specifications

- Added a dynamic Vehicle Specifications section to the Repair & Maintenance create-record modal.
- Salesmen can add or remove vehicle entries while the form always keeps at least one vehicle.
- Each vehicle stores:
  - Brand;
  - Model;
  - Serial Number / Plate Number;
  - Loading Capacity;
  - Lifting Height;
  - Mast Type;
  - Power Type;
  - Tire;
  - Fork Length;
  - Attachment.
- Brand, Model, and Serial Number / Plate Number are required for every vehicle.
- Removed the standalone Serial Number input from the create-record form.
- Added nullable JSON column `vehicle_specifications` through migration `2026_06_30_000002_add_vehicle_specifications_to_maintenance_records_table.php`.
- Added an array cast for `vehicle_specifications` on the maintenance record model.
- The first vehicle's Serial Number / Plate Number is synchronized to the legacy `serial_number` column for backward-compatible tables and existing integrations.
- Updated the Salesman table heading to `Serial / Plate Number` and added a first-vehicle identifier summary with legacy fallback.
- Updated the record information modal to show every saved vehicle and its complete specifications.
- The Repair & Maintenance Edit modal remains unchanged in this phase.

### After Sales Pending Company Search and JO Assignment

- Replaced the Other-form `Search JO` workflow with a pending Repair & Maintenance company-name search.
- Company search supports partial names and only returns records whose `job_order_number` is null or blank.
- Added selectable pending-record results containing company/contact details and all saved vehicles with Brand, Model, and Serial/Plate Number.
- After Sales now enters the JO Number only after selecting a pending Repair & Maintenance record.
- One selected Repair & Maintenance record can contain multiple vehicles under the same JO Number.
- Added nullable `maintenance_record_id` to `after_sales_records` through migration `2026_06_30_000003_link_after_sales_records_to_maintenance_records.php`.
- The migration backfills existing Other records by matching their current JO Number when a corresponding maintenance record exists.
- Added direct Eloquent relationships between After Sales and Repair & Maintenance records.
- New Other saves use a database transaction to:
  - verify and lock the selected pending record;
  - assign its JO Number;
  - create the linked After Sales record.
- A stale or concurrently assigned maintenance record is rejected instead of overwriting its JO.
- Editing an Other JO updates both the After Sales record and its linked Repair & Maintenance record in one transaction.
- Updated the JO Information table to display every vehicle included under an Other JO.
- PMS behavior and the JO Information client/JO list search remain unchanged.

### Test Coverage and Test Isolation

- Added `SalesmanRepairAndMaintenanceTest` with coverage for:
  - hidden Salesman JO controls;
  - record creation without a JO Number;
  - preservation of an existing JO Number during Salesman edits;
  - required JO validation in shared modal contexts that still manage JO Numbers;
  - dynamic vehicle add/remove behavior;
  - required vehicle identifiers;
  - multi-vehicle JSON persistence;
  - first-vehicle legacy serial synchronization.
- Extended `AfterSalesDashboardTest` with coverage for:
  - partial company-name pending search;
  - exclusion of records that already have a JO;
  - one JO assignment for a record with multiple vehicles;
  - direct maintenance-record linking;
  - stale assignment rejection;
  - synchronized Other JO edits.
- Configured PHPUnit to use in-memory SQLite and a valid test `APP_URL`, isolating automated tests from the local MySQL application database.
- Verification result: 15 tests passed with 56 assertions.
- PHP syntax checks and Blade view compilation passed.

## 2026-06-16

### After Sales Account and Dashboard

- Added a new user role for After Sales using `role = 4`.
- Added After Sales login option.
- Added After Sales route: `/after-sales/dashboard`.
- Added `AfterSalesMiddleware` and registered it as `isAfterSales`.
- Added `AfterSales\Dashboard` Livewire page.
- Added PMS and Other record entry flow.
- Added `after_sales_records` table migration.
- Added `AfterSalesRecord` model.
- Added `year_model` field to `clients`.
- Added dedicated `AfterSalesAccountSeeder`.

Default After Sales account:

```text
Username: aftersales01
Password: aftersales123456
Role: 4
Department: After Sales
```

### PMS Understanding

- PMS records depend on the client/unit having a `salesList_no`.
- PMS search only finds clients where:

```text
salesList_no = entered Sale Control No.
status = Sold
```

- If a client/unit has no `salesList_no`, it will not be found for PMS.
- Units without `salesList_no` should be handled under `Other`, based on the requirements doc.
- After Sales PMS search now checks `salesList_no` first, then shows a clearer error if the client exists but is not yet `Sold`.
- After Sales dashboard now uses Livewire notice state for success/error messages and displays validation summaries, including the PMS selected sold unit requirement.

### Form Refresh Fixes

- Removed the manual Refresh button/component from modals.
- Deleted unused `RefreshPage` Livewire component and view.
- Added Livewire events so tables update after create/edit/delete without browser refresh.
- Fixed client delete flow so it no longer flashes an error after successful delete.
- Fixed dynamic modal rendering issues by moving row modals outside `<tbody>`.
- Added stable `wire:key` values to dynamic modals.
- Fixed malformed table tags in Cashier pages.
- Fixed unclosed Livewire component tags in repair/maintenance pages.
- Fixed Salesman dashboard status badge markup by replacing malformed `<spa/>` with `</span>` and adding a default status style.

### Client Status Update Changes

- Moved Vehicle Specifications from the Create Client modal to the Change Status modal.
- Create Client modal now only saves basic client information.
- Change Status modal now saves:
  - Status
  - SalesList No.
  - Bank Account Number
  - Supporting PDF document paths
  - Vehicle Specifications
- Added production-safe migration for new `clients` columns:
  - `supporting_document_path`
  - `supporting_document_paths`
  - `vehicle_specifications`
- Change Status modal now supports multiple supporting PDF uploads with add/remove file inputs.
- Salesman client table now has a `View Files` action button.
- Added supporting documents modal where uploaded PDFs can be viewed and removed.
- Vehicle specifications are stored as JSON.
- The first vehicle is also synced to existing client fields for compatibility:
  - `item_name`
  - `model_number`
  - `specification`

### Warehouse Account and Dashboard

- Added a new user role for Warehouse using `role = 5`.
- Added Warehouse login option.
- Added Warehouse route: `/warehouse/dashboard`.
- Added `WarehouseMiddleware` and registered it as `isWarehouse`.
- Added `Warehouse\Dashboard` Livewire page.
- Warehouse dashboard is read-only and can view:
  - Client records
  - Auto Parts records
  - Repair & Maintenance records
- Added summary counts:
  - Total clients
  - Sold clients
  - Auto Parts records
  - Repair & Maintenance records
- Added dedicated `WarehouseAccountSeeder`.

Default Warehouse account:

```text
Username: warehouse01
Password: warehouse123456
Role: 5
Department: Warehouse
```

### Verification

- `php artisan view:clear` passed.
- `php artisan view:cache` passed.
- PHP syntax checks passed for touched Livewire classes.

### Known Local Environment Issues

- Full test run is blocked by missing Vite manifest:

```text
public/build/manifest.json
```

- `npm` was not available in the current PowerShell PATH, so assets could not be built from this shell.
- Migration checks were blocked by MySQL credential/access issue for `root` with no password.
