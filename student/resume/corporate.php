<?php
// Include necessary files for authentication and database functions
require_once '../../auth-check.php';
checkAccess('student');
require_once '../../db-functions.php';

// Get the current user's ID from session
$user_id = $_SESSION['user_id'];

// Fetch student data
$student = getStudentByUserId($user_id);
$student_id = $student['id'] ?? null;

// Fetch user data for email (needed for Gravatar)
$user = getUserById($user_id);

// Fetch additional profile data
$academic_info = $student_id ? getStudentAcademicInfo($student_id) : null;
$contact_info = $student_id ? getStudentContactInfo($student_id) : null;
$skills = $student_id ? getStudentSkills($student_id) : null;
$projects = $student_id ? getStudentProjects($student_id) : null;
$experiences = $student_id ? getStudentExperiences($student_id) : null;

// Generate Gravatar URL
function getGravatarUrl($email, $size = 150) {
    $hash = md5(strtolower(trim($email)));
    return "https://www.gravatar.com/avatar/{$hash}?s={$size}&d=identicon&r=pg";
}

$gravatar_url = $user ? getGravatarUrl($user['email']) : '';

// Helper function to convert comma-separated strings to arrays
function stringToArray($string) {
    if (empty($string)) return [];
    return array_map('trim', explode(',', $string));
}

// Prepare data for template
$resume_data = [
    'name' => $student['name'] ?? 'Not provided',
    'role' => $academic_info ? ($academic_info['branch'] ?? 'Student') : 'Student',
    'phone_number' => $student['phone_number'] ?? 'Not provided',
    'email' => $user['email'] ?? 'Not provided',
    'github_url' => $contact_info['github_url'] ?? '#',
    'linkedin_url' => $contact_info['linkedin_url'] ?? '#',
    'portfolio_url' => $contact_info['portfolio_url'] ?? '#',
    'institution_name' => $academic_info['institution_name'] ?? 'Not provided',
    'course_name' => $academic_info['course_name'] ?? 'Not provided',
    'branch' => $academic_info['branch'] ?? 'Not provided',
    'graduation_year' => $academic_info['graduation_year'] ?? 'Not provided',
    'cgpa' => $academic_info['cgpa'] ?? 'Not provided',
    'technical_skills' => stringToArray($skills['technical_skills'] ?? ''),
    'soft_skills' => stringToArray($skills['soft_skills'] ?? ''),
    'languages' => stringToArray($skills['languages'] ?? ''),
    'projects' => $projects ?? [],
    'experiences' => $experiences ?? [],
    'gravatar_url' => $gravatar_url
];

// Calculate start year for education
$start_year = $resume_data['graduation_year'] !== 'Not provided' ? ($resume_data['graduation_year'] - 4) : date('Y') - 4;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Resume - <?= htmlspecialchars($resume_data['name']) ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            background-color: #f5f5f5;
        }

        .resume-container {
            max-width: 900px;
            margin: 20px auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            display: flex;
            min-height: 1000px;
        }

        /* Left Sidebar */
        .sidebar {
            width: 35%;
            background: #2c3e50;
            color: white;
            padding: 40px 30px;
        }

        .profile-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 8px solid white;
            margin-bottom: 20px;
            object-fit: cover;
        }

        .sidebar-section {
            margin-bottom: 40px;
        }

        .sidebar-title {
            font-size: 1.2em;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        .sidebar-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 2px;
            background: white;
        }

        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-size: 0.9em;
        }

        .contact-item svg {
            width: 16px;
            height: 16px;
            margin-right: 12px;
            fill: white;
        }

        .contact-item a {
            color: white;
            text-decoration: none;
        }

        .contact-item a:hover {
            text-decoration: underline;
        }

        .education-item {
            margin-bottom: 25px;
        }

        .education-years {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 0.95em;
        }

        .education-school {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 1em;
        }

        .education-details {
            font-size: 0.9em;
            opacity: 0.9;
        }

        .education-details li {
            margin-bottom: 3px;
            margin-left: 20px;
        }

        .skill-list {
            list-style: none;
        }

        .skill-list li {
            margin-bottom: 8px;
            font-size: 0.9em;
            position: relative;
            padding-left: 15px;
        }

        .skill-list li:before {
            content: '•';
            position: absolute;
            left: 0;
        }

        .language-list {
            list-style: none;
        }

        .language-list li {
            margin-bottom: 5px;
            font-size: 0.9em;
            position: relative;
            padding-left: 15px;
        }

        .language-list li:before {
            content: '•';
            position: absolute;
            left: 0;
        }

        /* Main Content */
        .main-content {
            width: 65%;
            padding: 40px;
            background: white;
        }

        .header {
            margin-bottom: 40px;
        }

        .name {
            font-size: 2.5em;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        .title {
            font-size: 1.2em;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        .title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: #2c3e50;
        }

        .section {
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 1.3em;
            font-weight: bold;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 10px;
        }

        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: #2c3e50;
        }

        .profile-text {
            color: #555;
            text-align: justify;
            line-height: 1.8;
            font-size: 0.95em;
        }

        .work-item {
            margin-bottom: 30px;
            position: relative;
        }

        .work-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .company-info {
            flex: 1;
        }

        .company-name {
            font-weight: bold;
            color: #2c3e50;
            font-size: 1em;
            margin-bottom: 3px;
        }

        .position {
            color: #7f8c8d;
            font-size: 0.9em;
        }

        .date-range {
            color: #7f8c8d;
            font-size: 0.85em;
            white-space: nowrap;
            font-weight: 500;
        }

        .work-description {
            margin-top: 12px;
        }

        .work-description ul {
            margin-left: 20px;
        }

        .work-description li {
            margin-bottom: 5px;
            color: #555;
            font-size: 0.9em;
            line-height: 1.6;
        }

        .references-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .reference-item h4 {
            color: #2c3e50;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .reference-company {
            color: #7f8c8d;
            font-size: 0.9em;
            margin-bottom: 8px;
        }

        .reference-contact {
            font-size: 0.85em;
            color: #555;
        }

        .reference-contact div {
            margin-bottom: 2px;
        }

        /* Timeline dots for work experience */
        .work-item:before {
            content: '';
            position: absolute;
            left: -20px;
            top: 8px;
            width: 8px;
            height: 8px;
            background: #2c3e50;
            border-radius: 50%;
        }

        @media (max-width: 768px) {
            .resume-container {
                flex-direction: column;
                margin: 10px;
            }

            .sidebar {
                width: 100%;
                padding: 30px 20px;
            }

            .main-content {
                width: 100%;
                padding: 30px 20px;
            }

            .name {
                font-size: 2em;
            }

            .work-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .date-range {
                margin-top: 5px;
            }

            .references-container {
                grid-template-columns: 1fr;
            }

            .work-item:before {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="resume-container">
        <!-- Left Sidebar -->
        <div class="sidebar">
            <div class="profile-section">
                <img src="<?= htmlspecialchars($resume_data['gravatar_url']) ?>" alt="Profile Photo" class="profile-image" id="profileImage">
            </div>

            <!-- Contact Section -->
            <div class="sidebar-section">
                <h3 class="sidebar-title">Contact</h3>
                <div class="contact-item">
                    <svg viewBox="0 0 24 24">
                        <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                    </svg>
                    <span id="phoneNumber"><?= htmlspecialchars($resume_data['phone_number']) ?></span>
                </div>
                <div class="contact-item">
                    <svg viewBox="0 0 24 24">
                        <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                    </svg>
                    <a href="mailto:<?= htmlspecialchars($resume_data['email']) ?>" id="emailLink"><?= htmlspecialchars($resume_data['email']) ?></a>
                </div>
                <?php if ($resume_data['linkedin_url'] !== '#'): ?>
                <div class="contact-item">
                    <svg viewBox="0 0 24 24">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                    </svg>
                    <a href="<?= htmlspecialchars($resume_data['linkedin_url']) ?>" target="_blank">LinkedIn</a>
                </div>
                <?php endif; ?>
                <?php if ($resume_data['github_url'] !== '#'): ?>
                <div class="contact-item">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                    </svg>
                    <a href="<?= htmlspecialchars($resume_data['github_url']) ?>" target="_blank">GitHub</a>
                </div>
                <?php endif; ?>
                <?php if ($resume_data['portfolio_url'] !== '#'): ?>
                <div class="contact-item">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                    <a href="<?= htmlspecialchars($resume_data['portfolio_url']) ?>" target="_blank">Portfolio</a>
                </div>
                <?php endif; ?>
            </div>

            <!-- Education Section -->
            <div class="sidebar-section">
                <h3 class="sidebar-title">Education</h3>
                <div id="educationContainer">
                    <div class="education-item">
                        <div class="education-years"><?= $start_year ?> - <?= htmlspecialchars($resume_data['graduation_year']) ?></div>
                        <div class="education-school"><?= htmlspecialchars(strtoupper($resume_data['institution_name'])) ?></div>
                        <ul class="education-details">
                            <li><?= htmlspecialchars($resume_data['course_name']) ?></li>
                            <li><?= htmlspecialchars($resume_data['branch']) ?></li>
                            <li>CGPA: <?= htmlspecialchars($resume_data['cgpa']) ?>/10</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Skills Section -->
            <div class="sidebar-section">
                <h3 class="sidebar-title">Skills</h3>
                <ul class="skill-list" id="skillsList">
                    <?php 
                    $all_skills = array_merge($resume_data['technical_skills'], $resume_data['soft_skills']);
                    foreach ($all_skills as $skill): 
                        if (!empty($skill)):
                    ?>
                        <li><?= htmlspecialchars($skill) ?></li>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </ul>
            </div>

            <!-- Languages Section -->
            <div class="sidebar-section">
                <h3 class="sidebar-title">Languages</h3>
                <ul class="language-list" id="languagesList">
                    <?php foreach ($resume_data['languages'] as $language): 
                        if (!empty($language)):
                    ?>
                        <li><?= htmlspecialchars($language) ?></li>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <h1 class="name" id="fullName"><?= htmlspecialchars($resume_data['name']) ?></h1>
                <div class="title" id="jobTitle"><?= htmlspecialchars($resume_data['role']) ?></div>
            </div>

            <!-- Profile Section -->
            <div class="section">
                <h2 class="section-title">Profile</h2>
                <p class="profile-text" id="profileDescription">
                    Dedicated and results-driven <?= htmlspecialchars($resume_data['role']) ?> with expertise in 
                    <?= !empty($resume_data['technical_skills']) ? implode(', ', array_slice($resume_data['technical_skills'], 0, 3)) : 'technology' ?> 
                    and project management. Passionate about creating innovative solutions and contributing to high-performing teams 
                    to achieve exceptional results. Strong background in 
                    <?= htmlspecialchars($resume_data['branch']) ?> with proven ability to deliver projects on time and within budget.
                </p>
            </div>

            <!-- Work Experience Section -->
            <div class="section">
                <h2 class="section-title">Work Experience</h2>
                <div id="workExperienceContainer">
                    <?php if (!empty($resume_data['experiences'])): ?>
                        <?php foreach ($resume_data['experiences'] as $experience): ?>
                            <div class="work-item">
                                <div class="work-header">
                                    <div class="company-info">
                                        <div class="company-name"><?= htmlspecialchars($experience['company_name']) ?></div>
                                        <div class="position"><?= htmlspecialchars($experience['position']) ?></div>
                                    </div>
                                    <div class="date-range">
                                        <?= date('Y', strtotime($experience['start_date'])) ?> - 
                                        <?= $experience['is_current'] ? 'PRESENT' : date('Y', strtotime($experience['end_date'])) ?>
                                    </div>
                                </div>
                                <div class="work-description">
                                    <ul>
                                        <li><?= htmlspecialchars($experience['description']) ?></li>
                                        <?php if (!empty($experience['location'])): ?>
                                            <li>Location: <?= htmlspecialchars($experience['location']) ?></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="work-item">
                            <div class="work-header">
                                <div class="company-info">
                                    <div class="company-name">No Experience Added</div>
                                    <div class="position">Add your work experience in your profile</div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Projects Section -->
            <?php if (!empty($resume_data['projects'])): ?>
            <div class="section" id="projectsSection">
                <h2 class="section-title">Projects</h2>
                <div id="projectsContainer">
                    <?php foreach ($resume_data['projects'] as $project): ?>
                        <div class="work-item">
                            <div class="work-header">
                                <div class="company-info">
                                    <div class="company-name"><?= htmlspecialchars($project['project_name']) ?></div>
                                    <div class="position">Technologies: <?= htmlspecialchars($project['technologies']) ?></div>
                                </div>
                                <?php if (!empty($project['project_url'])): ?>
                                    <div class="date-range">
                                        <a href="<?= htmlspecialchars($project['project_url']) ?>" target="_blank" style="color: inherit;">View Project</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="work-description">
                                <ul>
                                    <li><?= htmlspecialchars($project['description']) ?></li>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- References Section -->
            <div class="section">
                <h2 class="section-title">References</h2>
                <div class="references-container" id="referencesContainer">
                    <div class="reference-item">
                        <h4>Available upon request</h4>
                        <div class="reference-company">Professional references</div>
                        <div class="reference-contact">
                            <div><strong>Contact:</strong> <?= htmlspecialchars($resume_data['email']) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>