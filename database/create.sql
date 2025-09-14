-- Drop tables if they exist (in reverse dependency order)
DROP TABLE IF EXISTS job_applications;
DROP TABLE IF EXISTS jobs;
DROP TABLE IF EXISTS companies;
DROP TABLE IF EXISTS student_experience;
DROP TABLE IF EXISTS student_projects;
DROP TABLE IF EXISTS student_skills;
DROP TABLE IF EXISTS student_contact_info;
DROP TABLE IF EXISTS student_academic_info;
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
    status enum('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY user_id (user_id),
    UNIQUE KEY prn (prn),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Student Academic Information Table
CREATE TABLE IF NOT EXISTS student_academic_info (
    id int NOT NULL AUTO_INCREMENT,
    student_id int NOT NULL,
    institution_name varchar(200) NOT NULL,
    course_name varchar(100) NOT NULL,
    branch varchar(100) NOT NULL,
    graduation_year int NOT NULL,
    cgpa decimal(4, 2) DEFAULT NULL,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (id),
    UNIQUE KEY student_id (student_id),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Student Contact Information Table
CREATE TABLE IF NOT EXISTS student_contact_info (
    id int NOT NULL AUTO_INCREMENT,
    student_id int NOT NULL,
    linkedin_url varchar(255) DEFAULT NULL,
    github_url varchar(255) DEFAULT NULL,
    portfolio_url varchar(255) DEFAULT NULL,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (id),
    UNIQUE KEY student_id (student_id),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Student Skills Table
CREATE TABLE IF NOT EXISTS student_skills (
    id int NOT NULL AUTO_INCREMENT,
    student_id int NOT NULL,
    technical_skills text DEFAULT NULL,
    soft_skills text DEFAULT NULL,
    languages text DEFAULT NULL,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (id),
    UNIQUE KEY student_id (student_id),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Student Projects Table
CREATE TABLE IF NOT EXISTS student_projects (
    id int NOT NULL AUTO_INCREMENT,
    student_id int NOT NULL,
    project_name varchar(200) NOT NULL,
    description text,
    technologies text DEFAULT NULL,
    project_url varchar(255) DEFAULT NULL,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (id),
    KEY student_id (student_id),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Student Experience Table
CREATE TABLE IF NOT EXISTS student_experience (
    id int NOT NULL AUTO_INCREMENT,
    student_id int NOT NULL,
    experience_type enum('internship', 'job', 'freelance', 'volunteer', 'research', 'training') NOT NULL,
    company_name varchar(200) NOT NULL,
    position varchar(200) NOT NULL,
    description text,
    start_date date NOT NULL,
    end_date date DEFAULT NULL,
    is_current boolean DEFAULT false,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (id),
    KEY student_id (student_id),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
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
    KEY company_id (company_id),
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS job_applications (
    id int NOT NULL AUTO_INCREMENT,
    job_id int NOT NULL,
    student_id int NOT NULL,
    application_date datetime DEFAULT CURRENT_TIMESTAMP,
    status enum('applied', 'shortlisted', 'rejected', 'accepted') NOT NULL DEFAULT 'applied',
    PRIMARY KEY (id),
    UNIQUE KEY job_id (job_id, student_id),
    KEY student_id (student_id),
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);