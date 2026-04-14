# Task Management System

> A lightweight task management system built with Laravel 11 and Bootstrap 5, designed to help teams organize and track their daily work.

---

## Table of Contents

- [Technologies](#technologies)
- [Getting Started](#getting-started)
- [Environment Configuration](#environment-configuration)
- [Database](#database)
- [Features](#features)
- [Project Structure](#project-structure)
- [Assumptions & Decisions](#assumptions--decisions)
- [Testing](#testing)

---

## Technologies

- **Backend:** PHP 8.x, Laravel 11
- **Frontend:** Blade Templates, Bootstrap 5.3, Bootstrap Icons 1.11
- **Database:** MySQL with Eloquent ORM (SoftDeletes)

---

## Getting Started

```bash
# 1. Clone the repository
git clone <repository-url>
cd task_management_system_Qtec

# 2. Install PHP dependencies
composer install

# 3. Copy environment file and generate app key
cp .env.example .env
php artisan key:generate

# 4. Run database migrations
php artisan migrate

# 5. Start the development server
php artisan serve
```

App will be available at **http://127.0.0.1:8000/tasks**

---

## Environment Configuration

Update the following in your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management_system_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

---

## Database

### `tasks` table

| Column      | Type         | Description                         |
|-------------|--------------|-------------------------------------|
| id          | bigint       | Primary key                         |
| title       | varchar(100) | Required                            |
| description | text         | Optional                            |
| status      | enum         | pending, in_progress, completed     |
| priority    | enum         | low, medium, high                   |
| due_date    | date         | Optional, must be today or future   |
| created_at  | timestamp    | Auto-managed                        |
| updated_at  | timestamp    | Auto-managed                        |
| deleted_at  | timestamp    | Soft delete timestamp               |

**Indexes:** `status`, `priority`, `due_date`, `(status, priority)`

---

## Features

- **Create** tasks with title, description, status, priority, and due date
- **Edit** tasks via a centered Bootstrap modal with AJAX data fetch
- **Soft Delete** tasks — records are retained in the database via `deleted_at`
- **Filter** tasks by title, status, priority, and due date range (from / to)
- **Pagination** — 10 tasks per page with active filter state preserved
- **Validation** — server-side via `StoreTaskRequest` and `UpdateTaskRequest`
- **Toast Notifications** — on create, update, and delete actions

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── TaskController.php
│   └── Requests/
│       ├── StoreTaskRequest.php
│       └── UpdateTaskRequest.php
├── Models/
│   └── Task.php
database/
└── migrations/
    ├── 2026_04_14_000001_create_tasks_table.php
    └── 2026_04_14_103207_add_soft_deletes_to_tasks_table.php
resources/
└── views/
    ├── layouts/
    │   └── app.blade.php
    └── tasks/
        ├── index.blade.php
        └── create.blade.php
routes/
└── web.php
```

---

## Assumptions & Decisions

| Decision | Reason |
|----------|--------|
| No authentication | The brief did not require user login; designed as a single-team tool |
| Soft deletes | Preserves data integrity; allows future restore functionality |
| Edit via modal | Avoids full page reload; improves UX with lightweight AJAX fetch |
| Due date validation | Blocked at browser level (`min` attribute) and server-side (`after_or_equal:today`) |
| Form Requests | Keeps controller clean with separated validation logic |
| Bootstrap via CDN | Eliminates Node.js build step for simpler setup |

---

## Testing

Automated tests are not included in this version. Manual testing was performed covering:

- Task creation with valid and invalid inputs
- Inline validation error messages
- Edit modal populating correct task data via AJAX
- Soft delete removing task from list without database deletion
- Filters working individually and in combination
- Pagination preserving active filter state

**Recommended automated test approach:**

```bash
# Run tests
php artisan test
```

- **Feature tests** — use `RefreshDatabase` to test HTTP responses for each route
- **Unit tests** — cover any custom business logic in the `Task` model
