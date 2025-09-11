<?php
// db/db.php

// Singleton-style procedural DB connection
function getConnection()
{
    static $conn = null;

    if ($conn === null) {
        $host = "localhost";
        $user = "root";
        $pass = "";
        $dbname = "campushire";

        $conn = mysqli_connect($host, $user, $pass, $dbname);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    return $conn;
}

// --- User Authentication Functions ---
function registerUser($username, $email, $password, $role)
{
    $conn = getConnection();
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($conn, "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $hashed_password, $role);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function getUserByEmailAndRole($email, $role)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ? AND role = ?");
    mysqli_stmt_bind_param($stmt, "ss", $email, $role);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $user;
}

function login($email, $password)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($user && password_verify($password, $user['password'])) {
        if ($user['role'] === 'admin') {
            return $user; // Admin can log in directly
        } elseif ($user['role'] === 'student') {
            $stmt = mysqli_prepare($conn, "SELECT status FROM students WHERE user_id = ?");
            mysqli_stmt_bind_param($stmt, "i", $user['id']);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $student = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);

            if ($student) {
                if ($student['status'] === 'approved') {
                    $user['login_status'] = 'approved';
                } elseif ($student['status'] === 'pending') {
                    $user['login_status'] = 'pending';
                } elseif ($student['status'] === 'rejected') {
                    $user['login_status'] = 'rejected';
                }
                return $user;
            }
        }
    }

    return null; // Login failed
}


// --- Student Profile Functions ---
function getStudentByUserId($user_id)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "SELECT * FROM students WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $student = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $student;
}

function createStudentProfile($user_id, $prn, $name, $phone, $dob, $id_card, $resume, $gpas)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "INSERT INTO students (user_id, prn, name, phone_number, dob, id_card, resume_path,
        gpa_sem1, gpa_sem2, gpa_sem3, gpa_sem4, gpa_sem5, gpa_sem6)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param(
        $stmt,
        "issssssdddddd",
        $user_id,
        $prn,
        $name,
        $phone,
        $dob,
        $id_card,
        $resume,
        $gpas[0],
        $gpas[1],
        $gpas[2],
        $gpas[3],
        $gpas[4],
        $gpas[5]
    );
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}


function updateStudentProfile($user_id, $prn, $name, $phone, $dob, $id_card, $resume, $gpas)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "UPDATE students 
        SET prn = ?, name = ?, phone_number = ?, dob = ?, id_card = ?, resume_path = ?, 
            gpa_sem1 = ?, gpa_sem2 = ?, gpa_sem3 = ?, gpa_sem4 = ?, gpa_sem5 = ?, gpa_sem6 = ?
        WHERE user_id = ?");

    mysqli_stmt_bind_param(
        $stmt,
        "sssssssdddddi",
        $prn,
        $name,
        $phone,
        $dob,
        $id_card,
        $resume,
        $gpas[0],
        $gpas[1],
        $gpas[2],
        $gpas[3],
        $gpas[4],
        $gpas[5],
        $user_id
    );

    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}


function getAllStudents()
{
    $conn = getConnection();
    $query = "SELECT s.id, u.username, u.email, s.prn, s.dob, s.phone_number AS phone, s.resume_path AS resume, s.id_card, s.status
              FROM students s
              JOIN users u ON s.user_id = u.id
              ORDER BY s.id DESC";
    $result = mysqli_query($conn, $query);
    $students = [];
    while ($row = mysqli_fetch_assoc($result))
        $students[] = $row;
    return $students;
}

function getApprovedStudents()
{
    $conn = getConnection();
    $query = "SELECT s.id, u.username, u.email, s.prn, s.dob, s.phone_number AS phone, s.resume_path AS resume, s.id_card, s.status
              FROM students s
              JOIN users u ON s.user_id = u.id
              WHERE s.status='approved'
              ORDER BY s.id DESC";
    $result = mysqli_query($conn, $query);
    $students = [];
    while ($row = mysqli_fetch_assoc($result))
        $students[] = $row;
    return $students;
}

function getRejectedStudents()
{
    $conn = getConnection();
    $query = "SELECT s.id, u.username, u.email, s.prn, s.dob, s.phone_number AS phone, s.resume_path AS resume, s.id_card, s.status
              FROM students s
              JOIN users u ON s.user_id = u.id
              WHERE s.status='rejected'
              ORDER BY s.id DESC";
    $result = mysqli_query($conn, $query);
    $students = [];
    while ($row = mysqli_fetch_assoc($result))
        $students[] = $row;
    return $students;
}


// --- Company Functions ---

function createCompany($name, $description, $website, $logo_path = null)
{
    $conn = getConnection();

    $stmt = mysqli_prepare($conn, "INSERT INTO companies (name, description, website, logo_path) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssss", $name, $description, $website, $logo_path);

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    return $success;
}

function getAllCompanies()
{
    $conn = getConnection();
    $result = mysqli_query($conn, "SELECT * FROM companies ORDER BY name");
    $companies = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $companies[] = $row;
    }
    return $companies;
}

function getCompanyById($id)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "SELECT * FROM companies WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $company = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $company;
}

function updateCompany($id, $name, $description, $website, $logo_path)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "UPDATE companies SET name = ?, description = ?, website = ?, logo_path = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssssi", $name, $description, $website, $logo_path, $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function deleteCompany($id)
{
    $conn = getConnection();
    $sql = "DELETE FROM companies WHERE id=?;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;

}



// --- Job Application Functions ---
function createJob($company_id, $title, $description, $allowed_streams, $salary, $location, $deadline)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "INSERT INTO jobs (company_id, title, description, allowed_streams, salary, location, last_date_to_apply) VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "issssss", $company_id, $title, $description, $allowed_streams, $salary, $location, $deadline);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;

}

function getAllJobs()
{
    $conn = getConnection();
    $query = "SELECT j.*, c.name as company_name FROM jobs j JOIN companies c ON j.company_id = c.id ORDER BY j.last_date_to_apply DESC";
    $result = mysqli_query($conn, $query);
    $jobs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $jobs[] = $row;
    }
    return $jobs;
}

function getJobById($id)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "SELECT j.*, c.name as company_name FROM jobs j JOIN companies c ON j.company_id = c.id WHERE j.id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $job = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $job;
}
function updateJob($id, $company_id, $title, $description, $allowed_streams, $salary, $location, $deadline)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "UPDATE jobs SET company_id = ?, title = ?, description = ?, allowed_streams = ?, salary = ?, location = ?, last_date_to_apply = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "issssssi", $company_id, $title, $description, $allowed_streams, $salary, $location, $deadline, $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function deleteJob($id)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "DELETE FROM jobs WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function getJobsByCompanyId($company_id)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "SELECT * FROM jobs WHERE company_id = ? ORDER BY last_date_to_apply DESC");
    mysqli_stmt_bind_param($stmt, "i", $company_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $jobs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $jobs[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $jobs;
}


function applyToJob($job_id, $student_id)
{
    $conn = getConnection();
    // Modified to include the 'status' column
    $stmt = mysqli_prepare($conn, "INSERT INTO job_applications (job_id, student_id, status) VALUES (?, ?, 'Applied')");
    mysqli_stmt_bind_param($stmt, "ii", $job_id, $student_id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function hasStudentAppliedForJob($job_id, $student_id)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) as count FROM job_applications WHERE job_id = ? AND student_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $job_id, $student_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $row['count'] > 0;
}

function getApplicationsByStudent($student_id)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "
        SELECT ja.*, j.title, c.name as company_name
        FROM job_applications ja
        JOIN jobs j ON ja.job_id = j.id
        JOIN companies c ON j.company_id = c.id
        WHERE ja.student_id = ?
        ORDER BY ja.application_date DESC");
    mysqli_stmt_bind_param($stmt, "i", $student_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $applications = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $applications[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $applications;
}


function getAllJobApplications()
{
    $conn = getConnection();
    $query = "SELECT 
                ja.id AS application_id, 
                u.username AS student_name, 
                s.prn, 
                j.title AS job_title, 
                c.name AS company_name, 
                ja.application_date, 
                ja.status
            FROM 
                job_applications ja
            JOIN 
                students s ON ja.student_id = s.id
            JOIN 
                users u ON s.user_id = u.id
            JOIN 
                jobs j ON ja.job_id = j.id
            JOIN 
                companies c ON j.company_id = c.id
            ORDER BY 
                ja.application_date DESC";
    
    $result = mysqli_query($conn, $query);
    if (!$result) {
        // It's good practice to check for query errors
        die("Database query failed: " . mysqli_error($conn));
    }

    $applications = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $applications[] = $row;
    }
    return $applications;
}



function updateApplicationStatus($application_id, $new_status)
{
    // Use the new, user-friendly status values
    $allowed_statuses = ['Applied', 'Shortlisted', 'Rejected'];
    if (!in_array($new_status, $allowed_statuses)) {
        return false; // Invalid status
    }

    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "UPDATE job_applications SET status = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "si", $new_status, $application_id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function deleteStudentById($student_id)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "DELETE FROM students WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $student_id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

// Approve Student Function
function approveStudentById($student_id)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "UPDATE students SET status = 'approved' WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $student_id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

// Reject Student Function
function rejectStudentById($student_id)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "UPDATE students SET status = 'rejected' WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $student_id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function getPendingStudents()
{
    $conn = getConnection();
    $query = "SELECT s.id, u.username, u.email, s.prn, s.dob, s.id_card, s.status
              FROM students s
              JOIN users u ON s.user_id = u.id
              WHERE s.status = 'pending'";
    $result = mysqli_query($conn, $query);
    $students = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $students[] = $row;
    }

    return $students;
}


function updateRejectedStudentBasic($student_id, $prn, $dob, $id_card_path)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "UPDATE students SET prn = ?, dob = ?, id_card = ?, status = 'pending' WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "sssi", $prn, $dob, $id_card_path, $student_id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}





?>