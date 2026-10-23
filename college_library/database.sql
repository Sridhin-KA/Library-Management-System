
CREATE DATABASE IF NOT EXISTS college_library;
USE college_library;

-- 1. ADMIN TABLE
CREATE TABLE admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

-- 2. STUDENTS TABLE
CREATE TABLE students (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(15),
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    department VARCHAR(100)
) ENGINE=InnoDB;

-- 3. AUTHORS TABLE
CREATE TABLE authors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

-- 4. PUBLISHERS TABLE
CREATE TABLE publishers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

-- 5. BOOKS TABLE
CREATE TABLE books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(150) NOT NULL,
    author_id INT NOT NULL,
    publisher_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    available_quantity INT NOT NULL DEFAULT 0,

    CONSTRAINT fk_books_author
        FOREIGN KEY (author_id)
        REFERENCES authors(id),

    CONSTRAINT fk_books_publisher
        FOREIGN KEY (publisher_id)
        REFERENCES publishers(id),

    CONSTRAINT chk_book_quantity
        CHECK (
            quantity >= 0
            AND available_quantity >= 0
            AND available_quantity <= quantity
        )
) ENGINE=InnoDB;

-- 6. BOOK REQUESTS TABLE
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
) ENGINE=InnoDB;

-- 7. ISSUE BOOKS TABLE
CREATE TABLE issue_books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    book_id INT NOT NULL,
    issue_date DATE NOT NULL,
    due_date DATE NOT NULL,

    CONSTRAINT fk_issue_student
        FOREIGN KEY (student_id)
        REFERENCES students(id),

    CONSTRAINT fk_issue_book
        FOREIGN KEY (book_id)
        REFERENCES books(id)
) ENGINE=InnoDB;

-- 8. RETURN BOOKS TABLE
CREATE TABLE return_books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    issue_id INT NOT NULL UNIQUE,
    return_date DATE NOT NULL,

    CONSTRAINT fk_return_issue
        FOREIGN KEY (issue_id)
        REFERENCES issue_books(id)
) ENGINE=InnoDB;

-- 9. FINES TABLE
CREATE TABLE fines (
    id INT PRIMARY KEY AUTO_INCREMENT,
    return_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'Pending',

    CONSTRAINT fk_fine_return
        FOREIGN KEY (return_id)
        REFERENCES return_books(id),

    CONSTRAINT chk_fine_amount
        CHECK (amount >= 0)
) ENGINE=InnoDB;

-- SAMPLE AUTHORS
INSERT INTO authors (name) VALUES
('Robert C. Martin'),
('Herbert Schildt'),
('Ramez Elmasri');

-- SAMPLE PUBLISHERS
INSERT INTO publishers (name) VALUES
('Pearson'),
('McGraw Hill'),
('O Reilly');

-- SAMPLE STUDENTS
-- Replace these password placeholders with valid PHP password_hash() results.
INSERT INTO students
(name, email, phone, username, password, department)
VALUES
('Rahul', 'rahul@gmail.com', '9876543210',
 'rahul', 'REPLACE_WITH_VALID_HASH_1', 'Computer Science'),
('Anu', 'anu@gmail.com', '9876543211',
 'anu', 'REPLACE_WITH_VALID_HASH_2', 'Computer Science'),
('Arjun', 'arjun@gmail.com', '9876543212',
 'ARJU', 'REPLACE_WITH_VALID_HASH_3', 'BCA');

-- SAMPLE BOOKS
INSERT INTO books
(title, author_id, publisher_id, quantity, available_quantity)
VALUES
('Clean Code', 1, 3, 5, 5),
('Java: The Complete Reference', 2, 2, 3, 3),
('Database System Concepts', 3, 1, 4, 4);

-- ADMIN ACCOUNT
-- Generate the hash with PHP password_hash('admin123', PASSWORD_DEFAULT).
-- Then replace the placeholder below with that hash.
INSERT INTO admin (username, password)
VALUES ('admin', 'REPLACE_WITH_VALID_ADMIN_HASH');

SELECT 'College Library database created successfully' AS message;
