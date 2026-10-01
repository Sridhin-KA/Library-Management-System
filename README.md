# 📚 College Library Management System

A simple and user-friendly **College Library Management System** built using **PHP, MySQL, HTML, CSS, and JavaScript**.

The system provides separate portals for **Admin** and **Students** to manage books, authors, publishers, book requests, issue/return operations, and fines.

---

## 🚀 Features

### 👨‍💼 Admin Panel

- Admin Login
- Dashboard with library statistics
- Student Management
  - Add Student
  - View Students
  - Edit Student
  - Delete Student
- Author Management
  - Add Author
  - View Authors
  - Edit Author
  - Delete Author
- Publisher Management
  - Add Publisher
  - View Publishers
  - Edit Publisher
  - Delete Publisher
- Book Management
  - Add Book
  - View Books
  - Edit Book
  - Delete Book
- Issue Books
- View Issued Books
- Return Books
- Automatic fine calculation
- Manage fines
- Approve/Reject student book requests

### 👨‍🎓 Student Panel

- Student Registration
- Student Login
- Student Dashboard
- View Available Books
- Request Books
- View My Requests
- View My Books
- View Fines
- Logout

---

## 💰 Fine System

The system automatically calculates fines when a book is returned after its due date.

**Fine:** ₹10 per late day

Example:
text
Due Date       : 10/09/2026
Return Date    : 13/09/2026
Late Days      : 3
Fine           : ₹30

🛠️ Technologies Used
Technology	Purpose
PHP	Backend
MySQL	Database
HTML5	Structure
CSS3	Styling
JavaScript	Client-side functionality
Bootstrap	Not Required


📂 Project Structure
college_library/
│
├── admin/
│   ├── dashboard.php
│   ├── issue.php
│   ├── return.php
│   ├── fines.php
│   ├── issued_books.php
│   ├── book_requests.php
│   │
│   ├── students/
│   │   ├── add.php
│   │   ├── view.php
│   │   ├── edit.php
│   │   └── delete.php
│   │
│   ├── authors/
│   │   ├── add.php
│   │   ├── view.php
│   │   ├── edit.php
│   │   └── delete.php
│   │
│   ├── publishers/
│   │   ├── add.php
│   │   ├── view.php
│   │   ├── edit.php
│   │   └── delete.php
│   │
│   └── books/
│       ├── add.php
│       ├── view.php
│       ├── edit.php
│       └── delete.php
│
├── student/
│   ├── register.php
│   ├── login.php
│   ├── logout.php
│   ├── dashboard.php
│   ├── books.php
│   ├── my_requests.php
│   ├── my_books.php
│   └── fines.php
│
├── config/
│   └── db.php
│
├── css/
│   └── style.css
│
├── js/
│   └── script.js
│
├── index.php
├── login.php
├── logout.php
└── database.sql


🗄️ Database
The project uses MySQL with the following tables:
admin
students
authors
publishers
books
issue_books
return_books
fines
book_requests

Database Relationships
Authors ────────┐
                ↓
              Books
                ↓
        ┌───────┴────────┐
        ↓                ↓
   Issue Books       Book Requests
        ↓                ↑
        ↓                │
  Return Books      Students
        ↓
      Fines

⚙️ Installation
1. Clone the Repository
git clone https://github.com/YOUR-USERNAME/college-library.git

Go inside the project:
cd college-library

🪟 Windows Setup
You can use XAMPP on Windows.
Step 1: Install XAMPP
Install XAMPP and start:
Apache
MySQL

Step 2: Copy the Project
Copy the project folder into:
C:\xampp\htdocs\

Your path should be:
C:\xampp\htdocs\college_library

Step 3: Create Database
Open:
http://localhost/phpmyadmin

Create a database named:
college_library

Then import:
database.sql

Step 4: Configure Database
Open:
config/db.php

For a default XAMPP installation:
<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "college_library";

$conn = mysqli_connect(
    $host,
    $username,
    $password,
    $database
);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

?>

Step 5: Open the Project
Go to:
http://localhost/college_library/

🐧 Linux Setup
If you are using Linux with native PHP and MySQL:
Start MySQL
sudo systemctl start mysql

Go to the project
cd college_library

Start PHP Server
php -S localhost:8000

Open:
http://localhost:8000/

Database Setup
Import the database:
sudo mysql < database.sql

If you are using a separate MySQL user, update:
config/db.php

with your MySQL credentials.
🔐 Default Admin Login
Username: admin
Password: admin123

Change the default credentials before using this project in a real production environment.

👨‍🎓 Student Login
Students can create an account using:
Student Registration

Required fields:
Name
Username
Password

After registration, students can log in and access the Student Dashboard.
📖 Library Workflow
Student
Register
   ↓
Login
   ↓
View Available Books
   ↓
Request Book
   ↓
Wait for Admin Approval
   ↓
Book Issued
   ↓
Return Book
   ↓
View Fine

Admin
Login
   ↓
Dashboard
   ↓
Manage Students / Authors / Publishers / Books
   ↓
View Book Requests
   ↓
Approve Request
   ↓
Book Issued
   ↓
Return Book
   ↓
Fine Generated
   ↓
Mark Fine as Paid

🔒 Important Note
This project is intended primarily for learning and academic purposes.
For production use, additional security improvements should be implemented, including:
- Prepared SQL statements
- Password hashing
- Input validation
- CSRF protection
- Secure session handling
- Role-based authorization
- Database transactions
- Environment variables for database credentials
🎯 Future Improvements
Possible future features:
- 📧 Email notifications
- 📱 Mobile responsive improvements
- 🔍 Advanced book search
- 📊 Admin reports
- 📈 Library statistics and charts
- 📚 Book categories
- 🖼️ Book cover images
- 🔔 Due-date reminders
- 📄 PDF reports
- 🔐 Improved authentication
- 👥 Multiple admin accounts
👨‍💻 Author
Sridhin K A
GitHub:
https://github.com/Sridhin-KA
⭐ Support
If you find this project useful for learning, consider giving the repository a ⭐ on GitHub.

### GitHub repo structure

I'd recommend adding these two things before pushing:


college_library/
├── README.md
├── .gitignore
├── database.sql
└── ...

And your .gitignore can be:
# IDE
.vscode/
.idea/

# OS
.DS_Store
Thumbs.db

# Logs
*.log

# Environment files
.env

# PHP temporary files
*.tmp
