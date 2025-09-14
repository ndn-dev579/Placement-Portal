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
    'role' => $academic_info ? ($academic_info['course_name'] ?? 'Student') : 'Student',
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
    'gravatar_url' => $gravatar_url,
    'technical_skills' => stringToArray($skills['technical_skills'] ?? ''),
    'soft_skills' => stringToArray($skills['soft_skills'] ?? ''),
    'languages' => stringToArray($skills['languages'] ?? ''),
    'projects' => $projects ?: [],
    'experiences' => $experiences ?: []
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($resume_data['name']) ?> - Resume</title>
    <style>
        /* Custom styles for resume layout and print formatting */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        :root {
            --primary-color: #6366f1;
            --secondary-color: #4f46e5;
            --text-color: #374151;
            --heading-color: #1f2937;
            --light-gray: #f3f4f6;
            --medium-gray: #e5e7eb;
            --dark-gray: #9ca3af;
            --card-bg: #f9fafb;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-gray);
            color: var(--text-color);
            line-height: 1.6;
            padding: 1rem;
        }

        .resume-container {
            max-width: 8.5in;
            margin: 2rem auto;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 0.75rem;
            padding: 2rem;
        }

        .section-title {
            position: relative;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--heading-color);
        }

        .section-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 3rem;
            height: 3px;
            background-color: var(--primary-color);
            border-radius: 9999px;
        }

        .item-list-dot li {
            position: relative;
            padding-left: 1.25em;
        }

        .item-list-dot li::before {
            content: '•';
            color: var(--primary-color);
            font-weight: bold;
            position: absolute;
            left: 0;
        }

        /* Header Styles */
        .header {
            text-align: center;
            padding-bottom: 2rem;
            border-bottom: 2px solid var(--medium-gray);
            margin-bottom: 1.5rem;
        }

        .header h1 {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--heading-color);
        }

        .header p {
            font-size: 1.125rem;
            color: #4b5563;
            margin-top: 0.5rem;
        }

        .contact-links {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            margin-top: 1rem;
            gap: 0.5rem 1rem;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .contact-links a {
            text-decoration: none;
            color: inherit;
            transition: color 0.2s;
        }
        
        .contact-links a:hover {
            color: var(--secondary-color);
        }

        .contact-links span {
            color: var(--dark-gray);
        }

        /* Academic & Gravatar Section */
        .academic-section {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            align-items: center;
        }

        .academic-info h3 {
            font-weight: bold;
            color: var(--heading-color);
        }

        .academic-info p {
            font-size: 0.875rem;
        }

        .profile-image {
            width: 8rem;
            height: 8rem;
            border-radius: 50%;
            border: 4px solid #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            align-self: center;
        }

        @media (min-width: 768px) {
            .academic-section {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
            .academic-info {
                flex-grow: 1;
            }
            .profile-image {
                align-self: flex-end;
            }
        }

        /* Skills Section */
        .skills-grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 1rem;
            font-size: 0.875rem;
        }

        .skills-grid ul {
            margin-top: 0.5rem;
            color: #4b5563;
        }

        .skills-grid h3 {
            font-weight: bold;
            color: var(--heading-color);
        }

        @media (min-width: 768px) {
            .skills-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        /* Projects & Experience */
        .card-list {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .card {
            padding: 1rem;
            background-color: var(--card-bg);
            border: 1px solid var(--medium-gray);
            border-radius: 0.5rem;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.5rem;
        }

        .card-header h3 {
            font-weight: bold;
            font-size: 1.125rem;
            color: var(--heading-color);
        }

        .card a {
            color: var(--secondary-color);
            font-size: 0.875rem;
            text-decoration: none;
            transition: text-decoration 0.2s;
        }

        .card a:hover {
            text-decoration: underline;
        }
        
        .card p {
            font-size: 0.875rem;
            color: #4b5563;
        }

        .card .technologies {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .technology-tag {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            background-color: #e0e7ff;
            color: #4f46e5;
        }

        .experience-card .date {
            font-size: 0.875rem;
            color: #6b7280;
        }
        
        .experience-card .location {
            font-size: 0.875rem;
            color: #4b5563;
            margin-bottom: 0.5rem;
            font-style: italic;
        }

        /* Print Styles */
        @media print {
            body {
                background-color: #ffffff;
                padding: 0;
            }
            .resume-container {
                box-shadow: none;
                margin: 0;
                padding: 0;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Main Resume Container -->
    <div class="resume-container">

        <!-- Header Section -->
        <header class="header">
            <h1><?= htmlspecialchars($resume_data['name']) ?></h1>
            <p><?= htmlspecialchars($resume_data['role']) ?></p>
            <div class="contact-links">
                <span><?= htmlspecialchars($resume_data['phone_number']) ?></span>
                <span>|</span>
                <a href="mailto:<?= htmlspecialchars($resume_data['email']) ?>"><?= htmlspecialchars($resume_data['email']) ?></a>
                <span>|</span>
                <a href="<?= htmlspecialchars($resume_data['github_url']) ?>" target="_blank">GitHub</a>
                <span>|</span>
                <a href="<?= htmlspecialchars($resume_data['linkedin_url']) ?>" target="_blank">LinkedIn</a>
                <span>|</span>
                <a href="<?= htmlspecialchars($resume_data['portfolio_url']) ?>" target="_blank">Portfolio</a>
            </div>
        </header>

        <!-- Academic Info & Gravatar Section -->
        <section class="academic-section">
            <div class="academic-info">
                <h2 class="section-title">Education</h2>
                <div>
                    <h3 class="font-bold"><?= htmlspecialchars($resume_data['institution_name']) ?></h3>
                    <p><?= htmlspecialchars($resume_data['course_name']) ?>, <?= htmlspecialchars($resume_data['branch']) ?></p>
                    <p>Graduation Year: <?= htmlspecialchars($resume_data['graduation_year']) ?></p>
                    <p>CGPA: <?= htmlspecialchars($resume_data['cgpa']) ?></p>
                </div>
            </div>
            <img src="<?= htmlspecialchars($resume_data['gravatar_url']) ?>" alt="Profile Image" class="profile-image">
        </section>

        <!-- Skills Section -->
        <section style="margin-bottom: 1.5rem;">
            <h2 class="section-title">Skills</h2>
            <div class="skills-grid">
                <div>
                    <h3 class="font-bold">Technical Skills</h3>
                    <ul class="item-list-dot">
                        <?php foreach ($resume_data['technical_skills'] as $skill): ?>
                        <li><?= htmlspecialchars($skill) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold">Soft Skills</h3>
                    <ul class="item-list-dot">
                        <?php foreach ($resume_data['soft_skills'] as $skill): ?>
                        <li><?= htmlspecialchars($skill) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold">Languages</h3>
                    <ul class="item-list-dot">
                        <?php foreach ($resume_data['languages'] as $language): ?>
                        <li><?= htmlspecialchars($language) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Projects Section -->
        <section style="margin-bottom: 1.5rem;">
            <h2 class="section-title">Projects</h2>
            <div class="card-list">
                <?php foreach ($resume_data['projects'] as $project): ?>
                <div class="card">
                    <div class="card-header">
                        <h3><?= htmlspecialchars($project['project_name'] ?? 'Untitled Project') ?></h3>
                        <?php if (!empty($project['project_url'])): ?>
                        <a href="<?= htmlspecialchars($project['project_url']) ?>" target="_blank">View Project</a>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($project['description'])): ?>
                    <p><?= htmlspecialchars($project['description']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($project['technologies'])): ?>
                    <div class="technologies">
                        <?php 
                        $technologies = stringToArray($project['technologies']);
                        foreach ($technologies as $tech): 
                        ?>
                        <span class="technology-tag"><?= htmlspecialchars($tech) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Experience Section -->
        <section style="margin-bottom: 1.5rem;">
            <h2 class="section-title">Experience</h2>
            <div class="card-list">
                <?php foreach ($resume_data['experiences'] as $experience): ?>
                <div class="card experience-card">
                    <div class="card-header">
                        <h3><?= htmlspecialchars($experience['position'] ?? 'Position') ?> at <?= htmlspecialchars($experience['company_name'] ?? 'Company') ?></h3>
                        <p class="date">
                            <?= htmlspecialchars($experience['start_date'] ?? '') ?> - 
                            <?php if ($experience['is_current']): ?>
                                Present
                            <?php else: ?>
                                <?= htmlspecialchars($experience['end_date'] ?? '') ?>
                            <?php endif; ?>
                        </p>
                    </div>
                    <?php if (!empty($experience['location'])): ?>
                    <p class="location"><?= htmlspecialchars($experience['location']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($experience['description'])): ?>
                    <p><?= htmlspecialchars($experience['description']) ?></p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

    </div>

</body>
</html>
