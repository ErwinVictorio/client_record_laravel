# Admin Dashboard UI Enhancement Plan

## Context

`FUTURE_DASHBOARD_UI_PLAN.md` asks for a modern SaaS/admin-dashboard visual refresh of the
dashboard, using the existing **Laravel 12 + Livewire 3 + Bootstrap 5 (SB Admin v7.0.7)** stack,
with a hard constraint: **presentation/UI only — no database, migration, model, route, controller,
or Livewire PHP changes**, and **all existing Livewire functionality, data bindings, and Chinese
labels must be preserved**.

Confirmed scope:
- **Target page: Admin Dashboard** (`/admin/dashboard`). Its structure matches the brief exactly
  (SB Admin sidebar + topbar, "Dashboard" title + breadcrumb, the three cards Total Pending /
  Total Sold / Total Approval, client list table with View/Edit/Delete, search, pagination).
- **Scope includes the shared sidebar + topbar polish.** Restyling the shared sidebar partial
  visually affects other admin pages too (intended: one consistent look).
- The brief's "Add Client button" and "filter dropdown" do **not** apply here — admins don't create
  clients in this app, and adding them would require new business logic (forbidden). They are
  **out of scope**; only the existing search + row actions are restyled.

Outcome: a cleaner, responsive, modern admin dashboard with a dark-navy sidebar, light content area,
blue accent, soft status badges, rounded cards, and subtle shadows — with zero behavior changes.

## Constraints / What must NOT change

- No edits to any PHP: models, migrations, `routes/web.php`, middleware, and **no changes to
  `app/Livewire/Admin/Dashboard.php`** (all needed data already exists there).
- Preserve every `wire:model`/`wire:model.live`, `wire:click`, `wire:key`, `data-bs-toggle`,
  `data-bs-target`, and the per-row `<livewire:...>` modal includes in the dashboard blade.
- Preserve the `#sidebarToggle` id and the `sb-nav-fixed` / `#layoutSidenav` / `.sb-sidenav`
  structure — the toggle is driven by `resources/js/scripts.js` (SB Admin).
- Keep all existing Chinese labels (move them into subtitles/tooltips where needed, never delete).
- Do **not** hand-edit the 251 KB vendor `resources/css/styles.css` (SB Admin + Bootstrap bundle).

## Files to modify

1. **`resources/css/dashboard-theme.css`** — NEW. All custom design tokens + component styles
   (stat cards, data table, soft badges, sidebar/topbar overrides, pagination).
2. **`resources/css/app.css`** — add `@import './dashboard-theme.css';` AFTER the existing imports
   so overrides win. (Current: imports `bootstrap` then `./styles.css`.)
3. **`resources/views/livewire/admin/dashboard.blade.php`** — main markup refresh (topbar, header,
   3 stat cards, client-list toolbar, table, badges, action buttons, pagination). Behavior-neutral.
4. **`resources/views/partials/admin_nav.blade.php`** — sidebar spacing/icons, active-state
   highlighting, section labels, logged-in user area (avatar + name/role).
5. *(optional, minor)* **`resources/views/livewire/auth/logout.blade.php`** — light class tweak so
   the button reads as a dropdown item. Keep `wire:click='logout'` intact.

## Approach

Deliver the look through **one new scoped stylesheet + Blade markup restructuring** — not by editing
the vendor CSS. Custom classes are namespaced (`app-*` / `stat-*` / `data-table`) to avoid clobbering
Bootstrap elsewhere; sidebar/topbar rules intentionally target the SB Admin classes so the polish is
consistent across admin pages.

### Design tokens (`dashboard-theme.css` `:root`)
Navy sidebar (`#0f172a`→`#1e293b` gradient), brand blue accent (`#004998`, already used app-wide),
neutral ink/muted/line, light content bg (`#f5f7fb`), white cards, radius (`14px`), soft shadows,
and soft status pairs — success `#16a34a/#dcfce7`, info `#2563eb/#dbeafe`, danger `#dc2626/#fee2e2`.
Base font 14px.

### Section-by-section

- **Sidebar** (`admin_nav.blade.php` + CSS): navy gradient bg; roomier nav links with fixed-width
  icon slots and hover states; styled section headings (`.sb-sidenav-menu-heading`); **active state**
  added via `request()->routeIs('admin.dashboard')`-style checks per link (view-layer only) rendered
  as a blue-tinted item with a left accent bar; footer "Logged in as" becomes an avatar circle
  (initials from `Auth::user()`) + name + role.
- **Topbar** (dashboard blade + CSS): convert to a clean light bar with bottom border, keep the
  `#sidebarToggle` button working, brand text, and a user-menu chip (avatar + name + caret) whose
  dropdown contains the existing `<livewire:auth.logout />`.
- **Page header + breadcrumb**: larger "Dashboard" title with a small muted subtitle; restyled,
  lighter breadcrumb.
- **Summary cards (3)**: white rounded cards with a colored icon chip (amber=Pending, green=Sold,
  blue=Approval), label (Chinese kept), and large count. Responsive grid
  `col-12 col-sm-6 col-xl-4`. Bindings unchanged: `{{$totalPending}}`, `{{$totalSold}}`,
  `{{$totalApproval}}`.
- **Client List toolbar**: card header with title "Client List (客户列表)" + a single rounded search
  field (icon inside) and button; keep `wire:model.live="ClientSearch"` and
  `wire:click="applySearch"`; add a `wire:loading` spinner (visual only). Wrap table in
  `.table-responsive`.
- **Table**: borderless modern table — light uppercase muted headers, row hover, comfortable
  padding, `vertical-align: middle`. Keep all columns and data bindings.
- **Status badges**: soft pill badges (tinted bg + colored text) mapping For Approval→green,
  Sold→blue, else→red. Keep the existing in-blade PHP `switch` and Chinese labels; only swap classes.
- **Created At**: replace `badge text-success` with muted text + small calendar icon (keep the
  existing `->timezone('Asia/Manila')->format(...)` output).
- **Action buttons**: compact icon buttons in a group — View (eye), Edit (pencil), Delete (trash) —
  with `title`/`aria-label` (Chinese preserved). **Keep exactly**: `data-bs-target`
  (`#viewClientDetails_`, `#EditClientIfo_`, `#deleteClientModal_`), `data-bs-toggle="modal"`,
  `wire:key`, and the trailing `@foreach (...) <livewire:modals....>` block.
- **Pagination**: switch `{{ $clientList->links() }}` → `{{ $clientList->links('pagination::bootstrap-5') }}`
  (view-layer only — no Bootstrap paginator is configured today, so the default renders unstyled),
  then style it (rounded items, blue active) in the new CSS. Wrap in a flex row.
- **Responsiveness**: cards stack via grid; `.table-responsive` gives horizontal scroll on mobile;
  toolbar wraps and search goes full-width on small screens; sidebar keeps SB Admin collapse.

## Verification

1. Build assets: `npm run build` (or `npm run dev` — `public/hot` exists, Vite dev was running),
   then `php artisan view:clear`.
2. Log in as Admin → `/admin/dashboard` and check:
   - Three cards show correct counts and stack cleanly at desktop / tablet / mobile widths.
   - Search: typing + clicking search filters the list (`wire:model.live` + `applySearch`); pagination
     preserves the search and page 2 navigates.
   - View / Edit / Delete each open their modal and still function (Livewire create/edit/delete),
     and the list refreshes (the `clients-updated` event still fires).
   - Sidebar shows active state on Dashboard, all nav links work, user menu logout works, toggle
     collapses the sidebar.
   - Table scrolls horizontally on mobile; no console errors.
3. Spot-check other admin pages (Department, Manage Sales Executive, records) still render correctly
   with the shared sidebar/topbar CSS changes.

## Notes / risks

- Sidebar/topbar CSS is intentionally global to admin pages; rules are kept additive and safe.
  Topbar *markup* improvements only affect the dashboard (its topbar is inline there).
- The per-row Edit/Delete/View modals are separate Livewire components (already refreshed in prior
  work) — only their **trigger buttons** are restyled; the components are untouched.
- If, on inspection, `->links()` already renders acceptable Bootstrap markup in this environment, the
  `pagination::bootstrap-5` argument can be dropped — decided at implementation time.
