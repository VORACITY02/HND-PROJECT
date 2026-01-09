# Implementation Notes: Supervisor Applications, Personal Data, Styling, and Stability Fixes

Last updated: {{DATE}}

## Overview
This document summarizes the work completed to:
- Add a proper staff supervisor application flow (collecting department and personal info, updating status, notifying on decisions).
- Add a Personal Data area for users to view their stored info (separate from Profile).
- Reduce 403 errors and remove unrequested/unstable UI elements.
- Apply a sky-blue palette while maintaining layout structure.
- Improve resilience when migrations haven’t been run yet.

## Key Changes

### 1) Personal Data support
- Added `personal_data` table and model.
  - File: `database/migrations/2025_12_17_000000_create_personal_data_table.php`
  - File: `app/Models/PersonalData.php`
- Linked to user model:
  - `App\Models\User::personalData()` added.
- New route & page for viewing:
  - Route: `GET /personal-data` -> `ProfileController@personalData`
  - View: `resources/views/profile/personal-data.blade.php`
- Navbar link shows only if the table exists (prevents errors before migration):
  - Conditional in `resources/views/layouts/app.blade.php` using `hasPersonalDataTable` flag.

### 2) Staff Supervisor Application Flow
- Controller updated:
  - File: `app/Http/Controllers/staff/SupervisorApplicationController.php`
  - `create()` now passes existing application and user.
  - `store()` validates department and personal info, upserts `PersonalData`, and creates/updates `SupervisorApplication` with status `pending`.
  - Guard added: if `personal_data` table doesn’t exist, returns friendly validation error.
- Staff Apply page updated:
  - File: `resources/views/staff/supervisor/apply.blade.php`
  - Collects: department (required), title, phone, address, bio, and max students (required).
  - Shows banners for pending/approved, disables form while pending, enables re-apply if rejected.
- Staff dashboard CTA reflects application status:
  - File: `resources/views/staff/dashboard.blade.php`
  - Shows one of: Apply / Pending / Rejected (re-apply) / Approved.

### 3) Admin Supervisor Management
- Controller updated to be migration-safe and to notify staff on decisions:
  - File: `app/Http/Controllers/Admin/AdminSupervisorController.php`
  - `index()` conditionally eager loads `staff.personalData` only if `personal_data` exists.
  - `approve()` and `reject()` send internal messages to staff.
- View shows department from PersonalData:
  - File: `resources/views/admin/supervisors/index.blade.php`

### 4) Reduce 403 errors and remove unrequested UI
- Role middleware: allow admin bypass across role-restricted routes
  - File: `app/Http/Middleware/RoleMiddleware.php`
  - Change: admins are allowed through even if route is `role:user` or `role:staff`.
- Removed a staff-only link to admin user management
  - File: `resources/views/staff/dashboard.blade.php`
  - Removed the “Manage Students” link to admin users to avoid 403 for staff.
- Removed “Recent Students” block from staff dashboard (unrequested and unstable)
  - File: `resources/views/staff/dashboard.blade.php`
  - This block defined `$recentUsers` inline and linked to admin pages; it is not requested and could cause errors/403s.

### 5) Styling (Sky-blue Theme)
- Updated accents to `sky-*` shades while keeping layout and structure the same.
  - File: `resources/views/layouts/app.blade.php`
  - File: `resources/views/layouts/guest.blade.php`

### 6) Resilience Before Migrations
- View flags shared globally to avoid SQL errors if tables aren’t created yet.
  - File: `app/Providers/AppServiceProvider.php`
  - Flags: `hasPersonalDataTable`, `hasSupervisorApplicationsTable`.
- Views use flags to conditionally render personal-data-dependent UI.
  - Files updated: `layouts/app.blade.php`, `staff/supervisor/apply.blade.php`, `profile/personal-data.blade.php`.
- Migration down method implemented for supervisor applications
  - File: `database/migrations/2025_12_15_134716_supervisor_application_table.php`

## Removed or Revised Code (What and Why)

1) Staff dashboard “Recent Students” block
   - File: `resources/views/staff/dashboard.blade.php` (bottom section previously showing recent users and a link to admin users page)
   - What it did: listed latest registered users (`$recentUsers`) with a link to admin user management.
   - Why removed: Not requested for staff, could cause 403s and undefined variable errors, and is better suited for admin views.

2) Staff dashboard “Manage Students” card linking to admin users
   - File: `resources/views/staff/dashboard.blade.php`
   - What it did: linked staff to `admin.users.index` which is admin-only.
   - Why removed: Caused 403 for staff and wasn’t requested.

3) Navbar Personal Data link conditioned
   - File: `resources/views/layouts/app.blade.php`
   - What changed: Show only if `personal_data` exists, to avoid pre-migration SQL errors.

## How to Apply and Test

1) Migrate the database (creates `personal_data` and ensures all tables exist):
```
php artisan migrate
```

2) Optional: Start fresh
```
php artisan app:purge-all-users --force
php artisan migrate:fresh
```

3) Create users and test flows
- Staff:
  - Go to Staff Dashboard -> Apply to be Supervisor
  - Fill required Department and Max Students (+ optional fields)
  - Dashboard should show “Application Pending”
  - Personal Data page should reflect saved info: `/personal-data`
- Admin:
  - Top nav -> Supervisor Apps
  - Approve or Reject -> Staff receives internal message
  - Staff dashboard CTA updates accordingly

## Known Behavior
- Non-admins still cannot access other roles’ pages; this is intended. Admins can bypass role restrictions (prevents 403s for admins only).
- Staff re-apply is allowed after rejection; form remains disabled during pending.

## Future Enhancements (Optional)
- Email notifications in addition to internal messages on approve/reject.
- Add reason input for admin when rejecting and show it to staff.
- Prevent resubmission while pending at the route level as well.
- Expand Personal Data (e.g., faculty/position codes) as needed.

## File Index (for review)
- Controllers:
  - `app/Http/Controllers/staff/SupervisorApplicationController.php`
  - `app/Http/Controllers/Admin/AdminSupervisorController.php`
  - `app/Http/Controllers/ProfileController.php`
- Models:
  - `app/Models/PersonalData.php`
  - `app/Models/SupervisorApplication.php`
  - `app/Models/User.php` (relations)
- Migrations:
  - `database/migrations/2025_12_17_000000_create_personal_data_table.php`
  - `database/migrations/2025_12_15_134716_supervisor_application_table.php`
- Views:
  - `resources/views/staff/supervisor/apply.blade.php`
  - `resources/views/staff/dashboard.blade.php`
  - `resources/views/admin/supervisors/index.blade.php`
  - `resources/views/profile/personal-data.blade.php`
  - `resources/views/layouts/app.blade.php`
  - `resources/views/layouts/guest.blade.php`
- Middleware:
  - `app/Http/Middleware/RoleMiddleware.php`
- Provider:
  - `app/Providers/AppServiceProvider.php`

---
If you want, I can publish this to Confluence as a page and include screenshots of the new flows. Would you like me to:
- Create a Confluence page with this content?
- Create a pull request summarizing these changes?
- Add Jira items for the optional future enhancements?
