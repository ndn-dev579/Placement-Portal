<?php
// db/db.php

// Singleton-style procedural DB connection
function getConnection()
{
    static $conn = null;

    if ($conn === null) {
        // Use environment variables if available, otherwise fall back to defaults
        $host = getenv('DB_HOST') ?: "localhost";
        $user = getenv('DB_USERNAME') ?: "root";
        $pass = getenv('DB_PASSWORD') ?: "";
        $dbname = getenv('DB_NAME') ?: "campushire";
        $port = getenv('DB_PORT') ?: 3306;

        $conn = mysqli_connect($host, $user, $pass, $dbname, $port);

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

function getUserById($user_id)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
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

function createStudentProfile($user_id, $prn, $name, $phone, $dob, $id_card, $resume)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "INSERT INTO students (user_id, prn, name, phone_number, dob, id_card, resume_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "issssss", $user_id, $prn, $name, $phone, $dob, $id_card, $resume);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}


function updateStudentProfile($user_id, $prn, $name, $phone, $dob, $id_card, $resume)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "UPDATE students SET prn = ?, name = ?, phone_number = ?, dob = ?, id_card = ?, resume_path = ? WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "ssssssi", $prn, $name, $phone, $dob, $id_card, $resume, $user_id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

// --- New Student Academic Info Functions ---
function createStudentAcademicInfo($student_id, $institution_name, $course_name, $branch, $graduation_year, $cgpa)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "INSERT INTO student_academic_info (student_id, institution_name, course_name, branch, graduation_year, cgpa) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "isssid", $student_id, $institution_name, $course_name, $branch, $graduation_year, $cgpa);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function updateStudentAcademicInfo($student_id, $institution_name, $course_name, $branch, $graduation_year, $cgpa)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "UPDATE student_academic_info SET institution_name = ?, course_name = ?, branch = ?, graduation_year = ?, cgpa = ? WHERE student_id = ?");
    mysqli_stmt_bind_param($stmt, "sssidi", $institution_name, $course_name, $branch, $graduation_year, $cgpa, $student_id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function getStudentAcademicInfo($student_id)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "SELECT * FROM student_academic_info WHERE student_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $student_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $academic_info = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $academic_info;
}

// --- New Student Contact Info Functions ---
function createStudentContactInfo($student_id, $linkedin_url, $github_url, $portfolio_url)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "INSERT INTO student_contact_info (student_id, linkedin_url, github_url, portfolio_url) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "isss", $student_id, $linkedin_url, $github_url, $portfolio_url);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function updateStudentContactInfo($student_id, $linkedin_url, $github_url, $portfolio_url)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "UPDATE student_contact_info SET linkedin_url = ?, github_url = ?, portfolio_url = ? WHERE student_id = ?");
    mysqli_stmt_bind_param($stmt, "sssi", $linkedin_url, $github_url, $portfolio_url, $student_id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function getStudentContactInfo($student_id)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "SELECT * FROM student_contact_info WHERE student_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $student_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $contact_info = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $contact_info;
}

// --- New Student Skills Functions ---
function createStudentSkills($student_id, $technical_skills, $soft_skills, $languages)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "INSERT INTO student_skills (student_id, technical_skills, soft_skills, languages) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "isss", $student_id, $technical_skills, $soft_skills, $languages);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function updateStudentSkills($student_id, $technical_skills, $soft_skills, $languages)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "UPDATE student_skills SET technical_skills = ?, soft_skills = ?, languages = ? WHERE student_id = ?");
    mysqli_stmt_bind_param($stmt, "sssi", $technical_skills, $soft_skills, $languages, $student_id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function getStudentSkills($student_id)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "SELECT * FROM student_skills WHERE student_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $student_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $skills = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $skills;
}

// --- New Student Projects Functions ---
function createStudentProject($student_id, $project_name, $description, $technologies, $project_url)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "INSERT INTO student_projects (student_id, project_name, description, technologies, project_url) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "issss", $student_id, $project_name, $description, $technologies, $project_url);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function updateStudentProject($project_id, $project_name, $description, $technologies, $project_url)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "UPDATE student_projects SET project_name = ?, description = ?, technologies = ?, project_url = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssssi", $project_name, $description, $technologies, $project_url, $project_id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function getStudentProjects($student_id)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "SELECT * FROM student_projects WHERE student_id = ? ORDER BY created_at DESC");
    mysqli_stmt_bind_param($stmt, "i", $student_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $projects = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $projects[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $projects;
}

function deleteStudentProject($project_id)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "DELETE FROM student_projects WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $project_id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

// --- New Student Experience Functions ---
function createStudentExperience($student_id, $experience_type, $company_name, $position, $description, $start_date, $end_date, $is_current)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "INSERT INTO student_experience (student_id, experience_type, company_name, position, description, start_date, end_date, is_current) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "issssssi", $student_id, $experience_type, $company_name, $position, $description, $start_date, $end_date, $is_current);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function updateStudentExperience($experience_id, $experience_type, $company_name, $position, $description, $start_date, $end_date, $is_current)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "UPDATE student_experience SET experience_type = ?, company_name = ?, position = ?, description = ?, start_date = ?, end_date = ?, is_current = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssssssii", $experience_type, $company_name, $position, $description, $start_date, $end_date, $is_current, $experience_id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function getStudentExperiences($student_id)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "SELECT * FROM student_experience WHERE student_id = ? ORDER BY start_date DESC");
    mysqli_stmt_bind_param($stmt, "i", $student_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $experiences = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $experiences[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $experiences;
}

function deleteStudentExperience($experience_id)
{
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "DELETE FROM student_experience WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $experience_id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

// --- Comprehensive Student Profile Functions ---
function getCompleteStudentProfile($student_id)
{
    // Get basic student info
    $student = getStudentByUserId($student_id);
    if (!$student)
        return null;

    // Get academic info
    $academic_info = getStudentAcademicInfo($student['id']);

    // Get contact info
    $contact_info = getStudentContactInfo($student['id']);

    // Get skills
    $skills = getStudentSkills($student['id']);

    // Get projects
    $projects = getStudentProjects($student['id']);

    // Get experiences
    $experiences = getStudentExperiences($student['id']);

    return [
        'basic' => $student,
        'academic' => $academic_info,
        'contact' => $contact_info,
        'skills' => $skills,
        'projects' => $projects,
        'experiences' => $experiences
    ];
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


function getFilteredJobs($searchTerm = '', $location = '', $stream = '')
{
    $conn = getConnection();
    // Base query that joins jobs with companies
    $query = "SELECT j.*, c.name as company_name 
              FROM jobs j 
              JOIN companies c ON j.company_id = c.id 
              WHERE 1=1"; // Start with a condition that's always true

    $params = [];
    $types = '';

    // Add conditions if search terms are provided
    if (!empty($searchTerm)) {
        $query .= " AND (j.title LIKE ? OR c.name LIKE ?)";
        $searchTermWildcard = "%" . $searchTerm . "%";
        $params[] = $searchTermWildcard;
        $params[] = $searchTermWildcard;
        $types .= 'ss';
    }

    if (!empty($location)) {
        $query .= " AND j.location LIKE ?";
        $locationWildcard = "%" . $location . "%";
        $params[] = $locationWildcard;
        $types .= 's';
    }

    if (!empty($stream)) {
        $query .= " AND j.allowed_streams LIKE ?";
        $streamWildcard = "%" . $stream . "%";
        $params[] = $streamWildcard;
        $types .= 's';
    }

    $query .= " ORDER BY j.last_date_to_apply DESC";

    $stmt = mysqli_prepare($conn, $query);

    // Bind parameters if any exist
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $jobs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $jobs[] = $row;
    }

    mysqli_stmt_close($stmt);
    return $jobs;
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

function getFilteredJobApplications($searchTerm = '', $status = '')
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
            WHERE 1=1"; // Start with a condition that's always true

    $params = [];
    $types = '';

    // Add search term condition (student name, job title, company)
    if (!empty($searchTerm)) {
        $query .= " AND (u.username LIKE ? OR j.title LIKE ? OR c.name LIKE ?)";
        $searchTermWildcard = "%" . $searchTerm . "%";
        $params[] = $searchTermWildcard;
        $params[] = $searchTermWildcard;
        $params[] = $searchTermWildcard;
        $types .= 'sss';
    }

    // Add status filter condition
    if (!empty($status)) {
        $query .= " AND ja.status = ?";
        $params[] = $status;
        $types .= 's';
    }

    $query .= " ORDER BY ja.application_date DESC";

    $stmt = mysqli_prepare($conn, $query);

    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $applications = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $applications[] = $row;
    }
    mysqli_stmt_close($stmt);
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