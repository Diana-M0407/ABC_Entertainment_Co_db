# Database Application Project

Movie ticket reservation system for ABC Entertainment Co. cinema chain.

The application retrieves and persists user data in a local MySQL database using PHP. SQL queries are executed through PHP classes, and results are rendered dynamically in the web-based user interface.
The database tracks cinemas, auditoriums, seats, movies, screening times, and ticket reservations, with enforced primary and foreign key constraints to preserve referential integrity.

---

## Table of Contents

1. [Overview](#-overview)  
2. [Getting Started](#-getting-started)   
3. [Screenshots](#-screenshots)  
4. [Core Features](#-Core-Features)  

---

### Overview

| Tool                     | Purpose             |
| ------------------------ | ------------------- |
| **PHP**                  | Server-side scripting language      |
| **OOP**                  | Programming paradigm used in PHP implementation    |
| **XAMPP**                | Local development environment        |
| **Apache**               | Web server  |
| **MySQL**                | Relational database management system  |
| **phpMyAdmin**           | Web-based MySQL administration interface|


---

#### Getting Started

- Clone the repository
- Launch XAMPP
- Run Apache + MySQL
- Ensure MySQL & Apache arerunning (green light) is ON
- Open phpMyAdmin via http://localhost/phpmyadmin
- Import the provided SQL schema 
- Navigate to http://localhost/ABC_Entertainment/connect_db.php
  
---

##### Screenshots


---


###### Core Features 

** 1. User Account Management **

    Load user records from the MySQL database
    Persist user-related data using PHP classes and prepared statements

** 2. Cinema and Auditorium Management **

    Retrieve cinema locations and associated auditoriums    
    Display auditorium capacity and seating layout

** 3. Movie and Screening Management **

    Store and retrieve movie metadata (title, genre, duration)
    Track screening times by cinema and auditorium

** 4. Seat Availability Tracking **

    Represent seats as database entities
    Determine seat availability based on existing reservations

** 5. Ticket Reservation System **

    Create reservations linked to users, screenings, and seats
    Enforce one-seat-per-screening constraints
    Prevent double-booking through database constraints

** 6. Relational Integrity Enforcement **

    Primary keys uniquely identify entities
    Foreign keys enforce relationships between cinemas, screenings, seats, and reservations
    Normalized schema designed to at least Third Normal Form (3NF)

** 7. Database-Driven UI **

    Dynamic content rendering based on live database queries
    Separation of database logic and presentation using OOP PHP classes
