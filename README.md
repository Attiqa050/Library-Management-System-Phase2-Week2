# 📚 Library Management System – Phase 2 Week 2

A simple Library Management System developed using **HTML, CSS, JavaScript, PHP, and MySQL**.

In Week 2, the frontend was connected to a custom **PHP API with MySQL**. Librarian authentication, protected API routes, and server-side book availability management were also implemented.

## Features

- Add, view, search, edit, and delete books
- Add, view, edit, and delete members
- Issue and return books
- Automatically set the due date to 14 days
- Calculate overdue fine at **Rs. 5 per late day**
- Librarian signup, login, and logout
- Password hashing using `password_hash()`
- Session-based authentication
- Protected API routes
- Server-side `availableCopies` management
- Prevent issuing a book when no copies are available

## Technologies Used

- HTML
- CSS
- JavaScript
- PHP
- MySQL
- Fetch API
- XAMPP

## Database

Database name:

```text
library_db
```

Tables:

```text
users
books
members
issues
```

## How to Run

1. Install and open **XAMPP**.

2. Start **Apache** and **MySQL**.

3. Place the project folder inside:

```text
C:\xampp\htdocs\LMS_Phase2_week02
```

4. Open **phpMyAdmin** and create a database named:

```text
library_db
```

5. Import the provided SQL file:

```text
library_db.sql
```

6. Check the database connection settings in:

```text
config/db.php
```

The project currently uses MySQL port **3307**.

7. Open the Signup page:

```text
http://localhost/LMS_Phase2_week02/signup.php
```

8. Create a librarian account, then log in using the registered email and password:

```text
http://localhost/LMS_Phase2_week02/login.php
```

9. After login, use the system to manage books, members, book issues, returns, and fines.

## Main API Files

```text
api/books.php
api/members.php
api/issues.php
api/auth.php
```

The frontend communicates with the PHP backend using the JavaScript **Fetch API**.

## Important Rules

- Due date = **Issue Date + 14 days**
- Fine = **Rs. 5 × late days**
- Issue book → `availableCopies - 1`
- Return book → `availableCopies + 1`
- If `availableCopies = 0`, the server rejects the issue request
- Logged-out API requests return **401 Unauthorized**

## Testing

Successfully tested:

- Signup, login, and logout
- Password hashing in MySQL
- Books, members, and issues APIs
- Issue and return functionality
- Fine calculation
- API authentication
- Direct API over-issue prevention

## Developer

**Attiqa**