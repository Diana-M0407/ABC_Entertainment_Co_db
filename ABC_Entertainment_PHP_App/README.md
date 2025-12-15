# ABC Entertainment — Cinema Management System (PHP + MySQL)

This folder is a ready-to-run sample web app that matches your schema and project requirements.

## 1) Import the database
1. Open phpMyAdmin (or MySQL Workbench).
2. Run your SQL script: `ABC_Entertainment_Co_db.sql`
   - It creates the DB `ABC_Entertainment_Company_db`, tables, inserts sample data, and creates the required views.

## 2) Configure DB credentials
Edit `config.php` if your MySQL username/password differs from XAMPP defaults.

## 3) Run in XAMPP
1. Copy this folder into: `xampp/htdocs/ABC_Entertainment_App/`
2. Visit: `http://localhost/ABC_Entertainment_App/index.php`

## 4) Create a login and test
1. Go to `Create account` (create_user.php).
2. Pick an existing employee (seeded by the SQL).
   e.g. use the aly.paul@company.com example for sign-in (Aly is assigned as manager).
3. Create a username + password that matches the policy.
4. Login using either:
   - The username you created, OR
   - The employee's email (from the employee table)

## Project mapping to requirements
- Home + login/logout: index.php / login.php / logout.php
- Business rule #3 search: showtime_search.php
- Employee profiles: employees.php (+ add/edit/delete)
- Password policy enforced at registration: create_user.php (util.php)
- Creation timestamps are displayed on list pages.
- Required views displayed: views.php

## Notes
- The UI uses a modern dark theme without external dependencies.
- All database queries use PDO prepared statements.
- Basic CSRF protection for state-changing forms.
