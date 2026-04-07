-- 1. Create Database
CREATE DATABASE IF NOT EXISTS lms_system;
USE lms_system;

-- 2. Students Table
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    admission_no VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Teachers Table
CREATE TABLE teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Courses Table
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    teacher_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE SET NULL
);

-- 5. Exams Table
CREATE TABLE exams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    total_marks INT NOT NULL,
    duration_minutes INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- 6. Questions Table
CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    exam_id INT NOT NULL,
    question_text TEXT NOT NULL,
    option_a VARCHAR(255) NOT NULL,
    option_b VARCHAR(255) NOT NULL,
    option_c VARCHAR(255),
    option_d VARCHAR(255),
    correct_option CHAR(1) NOT NULL, -- 'A','B','C','D'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE
);

-- 7. Results Table
CREATE TABLE results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    exam_id INT NOT NULL,
    score INT DEFAULT 0,
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    finished_at TIMESTAMP NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE
);

-- 8. Violations Table (Anti-Cheating Logs)
CREATE TABLE violations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    exam_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    violation_type VARCHAR(100), -- tab_switch, copy, fullscreen_exit
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE
);

CREATE TABLE teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    file VARCHAR(255),
    due_date DATETIME
);

CREATE TABLE assignment_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    assignment_id INT,
    student_id INT,
    title VARCHAR(255),
    file VARCHAR(255),
    marks INT DEFAULT 0,
    feedback TEXT,
    status VARCHAR(50) DEFAULT 'Pending',
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



CREATE TABLE forum_posts(
id INT AUTO_INCREMENT PRIMARY KEY,
student_id INT,
course_id INT,
title TEXT,
content TEXT,
created_at DATETIME
);

CREATE TABLE forum_replies(
id INT AUTO_INCREMENT PRIMARY KEY,
post_id INT,
student_id INT,
reply TEXT,
created_at DATETIME
);


CREATE TABLE group_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT,
    student_id INT
);

CREATE TABLE groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    group_name VARCHAR(255)
);

CREATE TABLE group_assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    title VARCHAR(255),
    description TEXT,
    file VARCHAR(255),
    due_date DATETIME
);

CREATE TABLE tutorials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    title VARCHAR(255),
    description TEXT,
    file VARCHAR(255)
);

CREATE TABLE course_resources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    title VARCHAR(255),
    file VARCHAR(255),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE course_announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    title VARCHAR(255),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE course_students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT
);


CREATE TABLE quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    total_marks INT,
    duration INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);



CREATE TABLE group_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT,
    student_id INT,
    message TEXT,
    reply_to INT DEFAULT NULL,
    file VARCHAR(255),
    voice VARCHAR(255),
    seen TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE users_online (
    student_id INT PRIMARY KEY,
    last_seen TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);




CREATE TABLE groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    course_id INT,
    leader_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE group_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT,
    student_id INT,
    status ENUM('pending','approved') DEFAULT 'pending'
);



CREATE TABLE enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT
);


CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    message TEXT,
    is_read INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE course_announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    title VARCHAR(255),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



CREATE TABLE lecture_rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    meeting_link VARCHAR(255),
    meeting_password VARCHAR(100),
    start_time DATETIME,
    end_time DATETIME,
    created_by INT, -- teacher id
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);