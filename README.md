# Task Management System

A lightweight **Task Management System** built with **Laravel 13** and **Bootstrap 5**, designed to help teams efficiently organize, track, and manage daily tasks.

---

## Table of Contents

- Tech Stack
- Features
- Installation
- Environment Configuration
- Assumptions & Decisions
- Testing

---

## Tech Stack

- **Backend:** PHP 8.3, Laravel 13  
- **Frontend:** Blade Templates, Bootstrap 5.3, Bootstrap Icons  
- **Database:** MySQL (Eloquent ORM, Soft Deletes)

---

## Features

- Create tasks with title, description, status, priority, and due date  
- Edit tasks using AJAX-powered Bootstrap modal  
- Soft delete tasks (data preserved in database)  
- Filter tasks by title, status, priority, and due date  
- Pagination (10 tasks per page with filter persistence)  
- Server-side validation using Form Requests  
- Toast notifications for user actions  

---

## Installation

```bash
git clone https://github.com/frridoy/task_management_system_Qtec.git
cd task_management_system_Qtec

composer install
cp .env.example .env
php artisan key:generate

php artisan migrate
php artisan serve

---

## Assumptions & Decisions

- Authentication was excluded as it was not required for the assessment. The application operates as a shared workspace. In a production environment,     authentication would be essential to enable features such as task assignment, user-specific task ownership, and role-based permissions.Soft deletes were implemented to ensure data integrity and future recoverability.
- Form Request validation is used to maintain clean and maintainable controllers.
- Task status and priority are implemented using enums for controlled data consistency.
- Validation is enforced on both client and server sides for reliability.
- Pagination is limited to 10 records per page with query string preservation for filters.

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
