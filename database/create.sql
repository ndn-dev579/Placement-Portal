-- Drop tables if they exist (in reverse dependency order)
DROP TABLE IF EXISTS job_applications;
DROP TABLE IF EXISTS jobs;
DROP TABLE IF EXISTS companies;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS users;

-- Create tables
CREATE TABLE IF NOT EXISTS users (
    id int NOT NULL AUTO_INCREMENT,
    username varchar(100) NOT NULL,
    email varchar(100) NOT NULL,
    password varchar(255) NOT NULL,
    role enum('student', 'admin') NOT NULL,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY email (email)
);
CREATE TABLE IF NOT EXISTS students (
    id int NOT NULL AUTO_INCREMENT,
    user_id int NOT NULL,
    prn varchar(20) NOT NULL,
    name varchar(100) NOT NULL,
    phone_number varchar(15) DEFAULT NULL,
    dob date DEFAULT NULL,
    id_card varchar(255) DEFAULT NULL,
    resume_path varchar(255) DEFAULT NULL,
    gpa_sem1 decimal(4, 2) DEFAULT NULL,
    gpa_sem2 decimal(4, 2) DEFAULT NULL,
    gpa_sem3 decimal(4, 2) DEFAULT NULL,
    gpa_sem4 decimal(4, 2) DEFAULT NULL,
    gpa_sem5 decimal(4, 2) DEFAULT NULL,
    gpa_sem6 decimal(4, 2) DEFAULT NULL,
    status enum('pending', 'approved', 'rejected') DEFAULT 'pending',
    PRIMARY KEY (id),
    UNIQUE KEY user_id (user_id),
    UNIQUE KEY prn (prn)
);
CREATE TABLE IF NOT EXISTS companies (
    id int NOT NULL AUTO_INCREMENT,
    name varchar(100) NOT NULL,
    description text,
    website varchar(255) DEFAULT NULL,
    logo_path varchar(255) DEFAULT NULL,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);
CREATE TABLE IF NOT EXISTS jobs (
    id int NOT NULL AUTO_INCREMENT,
    company_id int NOT NULL,
    title varchar(100) NOT NULL,
    description text,
    allowed_streams text,
    salary varchar(50) DEFAULT NULL,
    location varchar(100) DEFAULT NULL,
    last_date_to_apply date DEFAULT NULL,
    PRIMARY KEY (id),
    KEY company_id (company_id)
);
-- CREATE TABLE IF NOT EXISTS job_applications (
--     id int NOT NULL AUTO_INCREMENT,
--     job_id int NOT NULL,
--     student_id int NOT NULL,
--     application_date datetime DEFAULT CURRENT_TIMESTAMP,
--     status enum('pending', 'accepted', 'rejected') DEFAULT 'pending',
--     PRIMARY KEY (id),
--     UNIQUE KEY job_id (job_id, student_id),
--     KEY student_id (student_id)
-- );

CREATE TABLE IF NOT EXISTS job_applications (
    id int NOT NULL AUTO_INCREMENT,
    job_id int NOT NULL,
    student_id int NOT NULL,
    application_date datetime DEFAULT CURRENT_TIMESTAMP,
    status enum('Applied', 'Shortlisted', 'Rejected') NOT NULL DEFAULT 'Applied',
    PRIMARY KEY (id),
    UNIQUE KEY job_id (job_id, student_id),
    KEY student_id (student_id)
);