<?php
// Include necessary files for authentication and database functions
require_once '../../auth-check.php';
checkAccess('student');
require_once '../../db-functions.php';

// Get the current user's data
$user_id = $_SESSION['user_id'];
$student = getStudentByUserId($user_id);
$student_id = $student['id'] ?? null;

// Fetch user data for Gravatar
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Resume - <?= htmlspecialchars($student['name'] ?? 'Student') ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
        }

        <?php if (isset($_GET['preview'])): ?>
        body {
            background-color: transparent;
        }
        .resume-container {
            margin: 0;
            box-shadow: none;
            border-radius: 0;
        }
        <?php endif; ?>

        .resume-container {
            max-width: 900px;
            margin: 20px auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
            position: relative;
        }

        .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid white;
            margin-bottom: 20px;
            object-fit: cover;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            font-weight: 300;
        }

        .header .subtitle {
            font-size: 1.2em;
            opacity: 0.9;
            margin-bottom: 20px;
        }

        .contact-info {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95em;
        }

        .contact-item a {
            color: white;
            text-decoration: none;
        }

        .contact-item a:hover {
            text-decoration: underline;
        }

        .main-content {
            padding: 40px;
        }

        .section {
            margin-bottom: 35px;
        }

        .section-title {
            font-size: 1.4em;
            color: #667eea;
            border-bottom: 2px solid #667eea;
            padding-bottom: 8px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .academic-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }

        .academic-info h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1.2em;
        }

        .academic-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }

        .academic-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .academic-item:last-child {
            border-bottom: none;
        }

        .academic-label {
            font-weight: 600;
            color: #555;
        }

        .academic-value {
            color: #333;
            text-align: right;
        }

        .skills-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .skill-category {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #28a745;
        }

        .skill-category h4 {
            color: #333;
            margin-bottom: 12px;
            font-size: 1.1em;
        }

        .skill-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .skill-tag {
            background: #e3f2fd;
            color: #1976d2;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 500;
        }

        .experience-item, .project-item {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #ff6b6b;
        }

        .experience-header, .project-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        .experience-title, .project-title {
            font-size: 1.2em;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .company-name, .project-tech {
            color: #667eea;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .date-range, .project-link {
            background: #667eea;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.85em;
            white-space: nowrap;
        }

        .project-link a {
            color: white;
            text-decoration: none;
        }

        .location {
            color: #666;
            font-size: 0.9em;
            font-style: italic;
        }

        .description {
            margin-top: 12px;
            line-height: 1.6;
            color: #555;
        }

        .current-badge {
            background: #28a745;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.85em;
        }

        @media (max-width: 768px) {
            .resume-container {
                margin: 10px;
                border-radius: 0;
            }

            .header {
                padding: 30px 20px;
            }

            .header h1 {
                font-size: 2em;
            }

            .contact-info {
                gap: 15px;
            }

            .main-content {
                padding: 30px 20px;
            }

            .experience-header, .project-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .date-range, .project-link {
                margin-top: 10px;
            }

            .academic-details {
                grid-template-columns: 1fr;
            }

            .skills-container {
                grid-template-columns: 1fr;
            }
        }

        .icon {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }
    </style>
</head>
<body>
    <div class="resume-container">
        <!-- Header Section -->
        <header class="header">
            <?php if ($gravatar_url): ?>
                <img src="<?= htmlspecialchars($gravatar_url) ?>" alt="Profile Photo" class="profile-image">
            <?php else: ?>
                <img src="https://via.placeholder.com/120x120/667eea/ffffff?text=Photo" alt="Profile Photo" class="profile-image">
            <?php endif; ?>
            <h1><?= htmlspecialchars($student['name'] ?? 'Student Name') ?></h1>
            <div class="subtitle">
                <?php if ($academic_info): ?>
                    <?= htmlspecialchars(($academic_info['course_name'] ?? '') . ' - ' . ($academic_info['branch'] ?? '')) ?>
                <?php else: ?>
                    Computer Science Engineering
                <?php endif; ?>
            </div>
            <div class="contact-info">
                <div class="contact-item">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                    </svg>
                    <a href="mailto:<?= htmlspecialchars($user['email'] ?? '') ?>"><?= htmlspecialchars($user['email'] ?? 'email@example.com') ?></a>
                </div>
                <div class="contact-item">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                    </svg>
                    <span><?= htmlspecialchars($student['phone_number'] ?? 'Phone Number') ?></span>
                </div>
                <?php if ($contact_info && $contact_info['linkedin_url']): ?>
                <div class="contact-item">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/>
                    </svg>
                    <a href="<?= htmlspecialchars($contact_info['linkedin_url']) ?>" target="_blank">LinkedIn</a>
                </div>
                <?php endif; ?>
                <?php if ($contact_info && $contact_info['github_url']): ?>
                <div class="contact-item">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M12 2A10 10 0 0 0 2 12c0 4.42 2.87 8.17 6.84 9.5.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34-.46-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.87 1.52 2.34 1.07 2.91.83.09-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.92 0-1.11.38-2 1.03-2.71-.1-.25-.45-1.29.1-2.64 0 0 .84-.27 2.75 1.02.79-.22 1.65-.33 2.5-.33.85 0 1.71.11 2.5.33 1.91-1.29 2.75-1.02 2.75-1.02.55 1.35.2 2.39.1 2.64.65.71 1.03 1.6 1.03 2.71 0 3.82-2.34 4.66-4.57 4.91.36.31.69.92.69 1.85V21c0 .27.16.59.67.5C19.14 20.16 22 16.42 22 12A10 10 0 0 0 12 2z"/>
                    </svg>
                    <a href="<?= htmlspecialchars($contact_info['github_url']) ?>" target="_blank">GitHub</a>
                </div>
                <?php endif; ?>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Academic Information -->
            <section class="section">
                <h2 class="section-title">Education</h2>
                <div class="academic-info">
                    <h3><?= htmlspecialchars($academic_info['institution_name'] ?? 'University Name') ?></h3>
                    <div class="academic-details">
                        <div class="academic-item">
                            <span class="academic-label">Course:</span>
                            <span class="academic-value"><?= htmlspecialchars($academic_info['course_name'] ?? 'Bachelor of Technology') ?></span>
                        </div>
                        <div class="academic-item">
                            <span class="academic-label">Branch:</span>
                            <span class="academic-value"><?= htmlspecialchars($academic_info['branch'] ?? 'Computer Science') ?></span>
                        </div>
                        <div class="academic-item">
                            <span class="academic-label">Graduation Year:</span>
                            <span class="academic-value"><?= htmlspecialchars($academic_info['graduation_year'] ?? '2024') ?></span>
                        </div>
                        <div class="academic-item">
                            <span class="academic-label">CGPA:</span>
                            <span class="academic-value"><?= htmlspecialchars($academic_info['cgpa'] ?? '8.5') ?>/10</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Skills Section -->
            <section class="section">
                <h2 class="section-title">Skills</h2>
                <div class="skills-container">
                    <?php if ($skills && $skills['technical_skills']): ?>
                    <div class="skill-category">
                        <h4>Technical Skills</h4>
                        <div class="skill-tags">
                            <?php 
                            $technical_skills_array = array_map('trim', explode(',', $skills['technical_skills']));
                            foreach ($technical_skills_array as $skill): 
                                if (!empty($skill)):
                            ?>
                                <span class="skill-tag"><?= htmlspecialchars($skill) ?></span>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($skills && $skills['soft_skills']): ?>
                    <div class="skill-category">
                        <h4>Soft Skills</h4>
                        <div class="skill-tags">
                            <?php 
                            $soft_skills_array = array_map('trim', explode(',', $skills['soft_skills']));
                            foreach ($soft_skills_array as $skill): 
                                if (!empty($skill)):
                            ?>
                                <span class="skill-tag"><?= htmlspecialchars($skill) ?></span>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($skills && $skills['languages']): ?>
                    <div class="skill-category">
                        <h4>Languages</h4>
                        <div class="skill-tags">
                            <?php 
                            $languages_array = array_map('trim', explode(',', $skills['languages']));
                            foreach ($languages_array as $language): 
                                if (!empty($language)):
                            ?>
                                <span class="skill-tag"><?= htmlspecialchars($language) ?></span>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Experience Section -->
            <section class="section">
                <h2 class="section-title">Experience</h2>
                <div id="experienceContainer">
                    <?php if ($experiences && count($experiences) > 0): ?>
                        <?php foreach ($experiences as $experience): ?>
                            <div class="experience-item">
                                <div class="experience-header">
                                    <div>
                                        <div class="experience-title"><?= htmlspecialchars($experience['position'] ?? 'Position') ?></div>
                                        <div class="company-name"><?= htmlspecialchars($experience['company_name'] ?? 'Company') ?></div>
                                        <?php if ($experience['location'] ?? ''): ?>
                                            <div class="location"><?= htmlspecialchars($experience['location']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="<?= $experience['is_current'] ? 'current-badge' : 'date-range' ?>">
                                        <?= date('M Y', strtotime($experience['start_date'] ?? 'now')) ?>
                                        <?php if ($experience['is_current']): ?>
                                            - Present
                                        <?php elseif ($experience['end_date']): ?>
                                            - <?= date('M Y', strtotime($experience['end_date'])) ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php if ($experience['description']): ?>
                                    <div class="description">
                                        <?= htmlspecialchars($experience['description']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="experience-item">
                            <div class="experience-header">
                                <div>
                                    <div class="experience-title">Software Developer Intern</div>
                                    <div class="company-name">Tech Company Inc.</div>
                                    <div class="location">San Francisco, CA</div>
                                </div>
                                <div class="date-range">Jun 2023 - Aug 2023</div>
                            </div>
                            <div class="description">
                                Developed and maintained web applications using React and Node.js. Collaborated with cross-functional teams to deliver high-quality software solutions.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Projects Section -->
            <section class="section">
                <h2 class="section-title">Projects</h2>
                <div id="projectsContainer">
                    <?php if ($projects && count($projects) > 0): ?>
                        <?php foreach ($projects as $project): ?>
                            <div class="project-item">
                                <div class="project-header">
                                    <div>
                                        <div class="project-title"><?= htmlspecialchars($project['project_name'] ?? 'Project Name') ?></div>
                                        <?php if ($project['technologies']): ?>
                                            <div class="project-tech"><?= htmlspecialchars($project['technologies']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($project['project_url']): ?>
                                        <div class="project-link">
                                            <a href="<?= htmlspecialchars($project['project_url']) ?>" target="_blank">View Project</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php if ($project['description']): ?>
                                    <div class="description">
                                        <?= htmlspecialchars($project['description']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="project-item">
                            <div class="project-header">
                                <div>
                                    <div class="project-title">E-Commerce Platform</div>
                                    <div class="project-tech">React, Node.js, MongoDB</div>
                                </div>
                                <div class="project-link">
                                    <a href="#" target="_blank">View Project</a>
                                </div>
                            </div>
                            <div class="description">
                                Built a full-stack e-commerce platform with user authentication, product catalog, shopping cart, and payment integration. Implemented responsive design and optimized for performance.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>

</body>
</html>