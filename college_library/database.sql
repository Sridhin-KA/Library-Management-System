CREATE DATABASE college_library;

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
    email VARCHAR(100),
    phone VARCHAR(15),
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
    quantity INT NOT NULL,
    available_quantity INT NOT NULL,

    FOREIGN KEY (author_id) REFERENCES authors(id),
    FOREIGN KEY (publisher_id) REFERENCES publishers(id)
);


-- 6. Issue Books table
CREATE TABLE issue_books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    book_id INT NOT NULL,
    issue_date DATE NOT NULL,
    due_date DATE NOT NULL,

    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (book_id) REFERENCES books(id)
);


-- 7. Return Books table
CREATE TABLE return_books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    issue_id INT NOT NULL,
    return_date DATE NOT NULL,

    FOREIGN KEY (issue_id) REFERENCES issue_books(id)
);


-- 8. Fines table
CREATE TABLE fines (
    id INT PRIMARY KEY AUTO_INCREMENT,
    return_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(20) DEFAULT 'Pending',

    FOREIGN KEY (return_id) REFERENCES return_books(id)
);


-- Admin login
INSERT INTO admin (username, password)
VALUES ('admin', 'admin123');


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
INSERT INTO students (name, email, phone, department) VALUES
('Rahul', 'rahul@gmail.com', '9876543210', 'Computer Science'),
('Anu', 'anu@gmail.com', '9876543211', 'Computer Science'),
('Arjun', 'arjun@gmail.com', '9876543212', 'BCA');


-- Sample books
INSERT INTO books
(title, author_id, publisher_id, quantity, available_quantity)
VALUES
('Clean Code', 1, 3, 5, 5),
('Java: The Complete Reference', 2, 2, 3, 3),
('Database System Concepts', 3, 1, 4, 4);