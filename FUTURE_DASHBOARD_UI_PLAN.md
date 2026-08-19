# Future Dashboard UI Enhancement Plan

Enhance **this Dashboard page only** using the existing Laravel + Livewire + Bootstrap 5 setup.

## Important

- UI/design changes only.
- Do **not** modify the database, migrations, models, relationships, controllers, routes, or business logic.
- Preserve all existing Livewire functionality and data.
- Reuse the existing sidebar/header structure.

## Design Goals

Match the provided visual reference as closely as possible.

Improve:

- Sidebar spacing, icons, active state, section labels, and logged-in user area.
- Header/topbar with cleaner spacing and user menu.
- Dashboard title and breadcrumb.
- Summary cards for:
  - Total Pending
  - Total Sold
  - Total Approval
- Use clean cards, rounded corners, subtle borders/shadows, and better spacing.
- Client List section should look like a modern admin table.
- Improve search input, filter button, and Add Client button.
- Improve table header, row spacing, status badges, created date styling, and action buttons.
- Use compact icon buttons for View/Edit/Delete.
- Add clean pagination styling.
- Keep Chinese labels that already exist.
- Make the page responsive on desktop, tablet, and mobile.

## Style

Use a modern SaaS/admin-dashboard look:

- Dark navy sidebar
- White/light content area
- Blue primary accent
- Soft green/red/blue status badges
- Consistent 12–16px typography
- Subtle shadows
- Rounded cards
- Clean spacing

## Workflow

1. Inspect the current Dashboard Blade/Livewire files.
2. Identify which frontend files need changes.
3. Modify only the presentation/UI.
4. Do not rewrite working backend logic.
5. Verify all existing buttons, search, pagination, and Livewire actions still work.

## Non-Negotiable Constraint

**Frontend design only. Do not touch the database or backend logic.**
