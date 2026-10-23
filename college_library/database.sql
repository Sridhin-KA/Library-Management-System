```sql
CREATE DATABASE IF NOT EXISTS college_library;
USE college_library;

-- 1. Admin table
CREATE TABLE admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- 2. Students table
CREATE TABLE students (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(15),
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    department VARCHAR(100)
);

-- 3. Authors table
CREATE TABLE authors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL
);

-- 4. Publishers table
CREATE TABLE publishers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL
);

-- 5. Books table
CREATE TABLE books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(150) NOT NULL,
    author_id INT NOT NULL,
    publisher_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    available_quantity INT NOT NULL DEFAULT 0,

    CONSTRAINT fk_books_author
        FOREIGN KEY (author_id) REFERENCES authors(id),

    CONSTRAINT fk_books_publisher
        FOREIGN KEY (publisher_id) REFERENCES publishers(id),

    CONSTRAINT chk_book_quantity
        CHECK (
            quantity >= 0
            AND available_quantity >= 0
            AND available_quantity <= quantity
        )
);

-- 6. Book requests table
CREATE TABLE book_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    book_id INT NOT NULL,
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Pending', 'Approved', 'Rejected')
        NOT NULL DEFAULT 'Pending',

    CONSTRAINT fk_request_student
        FOREIGN KEY (student_id)
        REFERENCES students(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_request_book
        FOREIGN KEY (book_id)
        REFERENCES books(id)
        ON DELETE CASCADE
);

-- 7. Issue books table
CREATE TABLE issue_books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    book_id INT NOT NULL,
    issue_date DATE NOT NULL,
    due_date DATE NOT NULL,

    CONSTRAINT fk_issue_student
        FOREIGN KEY (student_id) REFERENCES students(id),

    CONSTRAINT fk_issue_book
        FOREIGN KEY (book_id) REFERENCES books(id)
);

-- 8. Return books table
CREATE TABLE return_books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    issue_id INT NOT NULL UNIQUE,
    return_date DATE NOT NULL,

    CONSTRAINT fk_return_issue
        FOREIGN KEY (issue_id) REFERENCES issue_books(id)
);

-- 9. Fines table
CREATE TABLE fines (
    id INT PRIMARY KEY AUTO_INCREMENT,
    return_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'Pending',

    CONSTRAINT fk_fine_return
        FOREIGN KEY (return_id) REFERENCES return_books(id),

    CONSTRAINT chk_fine_amount
        CHECK (amount >= 0)
);

-- Default admin
-- Generate a bcrypt hash in PHP before inserting the password.
-- Example: password_hash('admin123', PASSWORD_DEFAULT)

-- Sample authors
INSERT INTO authors (name) VALUES
('Robert C. Martin'),
('Herbert Schildt'),
('Ramez Elmasri');

-- Sample publishers
INSERT INTO publishers (name) VALUES
('Pearson'),
('McGraw Hill'),
('O Reilly');

-- Sample students
-- Replace these placeholder hashes with valid PHP password_hash() results.
INSERT INTO students
(name, email, phone, username, password, department)
VALUES
('Rahul', 'rahul@gmail.com', '9876543210',
 'rahul', 'REPLACE_WITH_VALID_PASSWORD_HASH', 'Computer Science'),
('Anu', 'anu@gmail.com', '9876543211',
 'anu', 'REPLACE_WITH_VALID_PASSWORD_HASH', 'Computer Science'),
('Arjun', 'arjun@gmail.com', '9876543212',
 'ARJU', 'REPLACE_WITH_VALID_PASSWORD_HASH', 'BCA');

-- Sample books
INSERT INTO books
(title, author_id, publisher_id, quantity, available_quantity)
VALUES
('Clean Code', 1, 3, 5, 5),
('Java: The Complete Reference', 2, 2, 3, 3),
('Database System Concepts', 3, 1, 4, 4);

SELECT 'College Library database created successfully' AS message;
```
