

-- USERS table: Shared login table for students and admins
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Hashed password
    role ENUM('student', 'admin') NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- STUDENTS table: Additional profile details, linked to users
CREATE TABLE IF NOT EXISTS student (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE, -- FK to users.id
    prn VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    phone_number VARCHAR(15) NOT NULL,
    dob DATE,
    id_card VARCHAR(255),
    resume_path VARCHAR(255),
    gpa_sem1 DECIMAL(4,2),
    gpa_sem2 DECIMAL(4,2),
    gpa_sem3 DECIMAL(4,2),
    gpa_sem4 DECIMAL(4,2),
    gpa_sem5 DECIMAL(4,2),
    gpa_sem6 DECIMAL(4,2),
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- COMPANIES table
CREATE TABLE IF NOT EXISTS companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    website VARCHAR(255),
    logo_path VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- JOBS table
CREATE TABLE IF NOT EXISTS jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    allowed_streams TEXT, 
    salary VARCHAR(50), -- e.g., "50000-70000 per annum"
    location VARCHAR(100), 
    last_date_to_apply DATE,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
);

-- JOB APPLICATIONS table
CREATE TABLE IF NOT EXISTS job_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    student_id INT NOT NULL,
    application_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    UNIQUE (job_id, student_id) -- One student can apply only once per job
);
