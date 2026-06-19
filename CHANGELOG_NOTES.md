# Project Change Notes

This file tracks the system changes, fixes, and implementation notes we make during development.

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
