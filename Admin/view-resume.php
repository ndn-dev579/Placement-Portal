<?php
require_once 'admin_header.php'; // Includes auth checks and db functions

// 1. Get the Student ID from the URL
if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>Error: No Student ID provided.</div>";
    require_once 'admin_footer.php';
    exit;
}
$student_id = intval($_GET['id']);

// 2. Fetch the student's full profile
$student = getStudentByUserId($student_id); // Assuming this returns the whole student row

if (!$student) {
    echo "<div class='alert alert-danger'>Error: Student not found.</div>";
    require_once 'admin_footer.php';
    exit;
}

// 3. Decode the stored resume data
$resume_data = json_decode($student['resume_data'] ?? '{}', true);
?>

<!-- Internal CSS for the resume template -->
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .resume-container, .resume-container * {
            visibility: visible;
        }
        .resume-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none;
        }
    }

    .resume-paper {
        background: white;
        max-width: 8.5in; /* Standard paper width */
        min-height: 11in; /* Standard paper height */
        margin: 2rem auto;
        padding: 0.8in;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        color: #333;
    }

    .resume-header .name {
        font-size: 2.5rem;
        font-weight: bold;
        color: #2c3e50;
        border-bottom: 2px solid #3498db;
        padding-bottom: 10px;
        margin-bottom: 10px;
    }

    .resume-header .contact-info {
        font-size: 0.9rem;
        color: #555;
    }
    .resume-header .contact-info a {
        color: #3498db;
        text-decoration: none;
    }

    .resume-section {
        margin-top: 1.5rem;
    }

    .resume-section h2 {
        font-size: 1.2rem;
        font-weight: bold;
        color: #3498db;
        border-bottom: 1px solid #eee;
        padding-bottom: 5px;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .resume-section .item h3 {
        font-size: 1.1rem;
        font-weight: bold;
        margin-bottom: 0;
    }
    .resume-section .item .sub-header {
        font-style: italic;
        color: #555;
        margin-bottom: 0.5rem;
    }
    .resume-section .skills-list {
        list-style-type: none;
        padding: 0;
    }
    .resume-section .skills-list li {
        display: inline-block;
        background-color: #ecf0f1;
        border-radius: 5px;
        padding: 5px 10px;
        margin: 2px;
        font-size: 0.9rem;
    }
</style>

<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Viewing Resume for <?= htmlspecialchars($student['username']) ?></h4>
            <div>
                <!-- Print button -->
                <button onclick="window.print()" class="btn btn-primary no-print">
                    <i data-lucide="printer" class="me-1"></i> Print / Save as PDF
                </button>
                <a href="manage_students.php" class="btn btn-outline-secondary no-print">← Back to Students</a>
            </div>
        </div>
        <div class="card-body resume-container bg-light">
            <?php if (empty($resume_data)): ?>
                <div class="alert alert-warning text-center">This student has not built their resume yet.</div>
            <?php else: ?>
                <!-- The A4-style resume paper -->
                <div class="resume-paper">
                    <header class="resume-header">
                        <div class="name"><?= htmlspecialchars($resume_data['full_name'] ?? '') ?></div>
                        <div class="contact-info">
                            <?= htmlspecialchars($resume_data['email'] ?? '') ?> | 
                            <?= htmlspecialchars($resume_data['phone'] ?? '') ?>
                            <?php if (!empty($resume_data['linkedin'])): ?>
                                | <a href="<?= htmlspecialchars($resume_data['linkedin']) ?>" target="_blank">LinkedIn</a>
                            <?php endif; ?>
                        </div>
                    </header>

                    <section class="resume-section">
                        <h2>Professional Summary</h2>
                        <p><?= nl2br(htmlspecialchars($resume_data['summary'] ?? '')) ?></p>
                    </section>
                    
                    <section class="resume-section">
                        <h2>Work Experience</h2>
                        <div class="item">
                            <h3><?= htmlspecialchars($resume_data['experience_title'] ?? '') ?></h3>
                            <div class="sub-header"><?= htmlspecialchars($resume_data['experience_company'] ?? '') ?></div>
                            <p><?= nl2br(htmlspecialchars($resume_data['experience_desc'] ?? '')) ?></p>
                        </div>
                    </section>

                    <section class="resume-section">
                        <h2>Education</h2>
                        <div class="item">
                            <h3><?= htmlspecialchars($resume_data['education_degree'] ?? '') ?></h3>
                            <div class="sub-header"><?= htmlspecialchars($resume_data['education_school'] ?? '') ?> - <?= htmlspecialchars($resume_data['education_year'] ?? '') ?></div>
                        </div>
                    </section>

                    <section class="resume-section">
                        <h2>Skills</h2>
                        <ul class="skills-list">
                            <?php 
                            $skills = explode(',', $resume_data['skills'] ?? '');
                            foreach ($skills as $skill): ?>
                                <li><?= htmlspecialchars(trim($skill)) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </section>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'admin_footer.php'; ?>
```

### 2. How to Link to the Resume Viewer

Now, you need to add a link in your `admin/manage_students.php` file (or wherever your main student list is) so the admin can open this new viewer.

Find the part of the code that loops through your students and displays them in a table. In the "Actions" column for each student, add a new button/link.

```html
<!-- Example of what the "Actions" cell might look like in manage_students.php -->
<td>
    <!-- Your existing buttons like Approve/Reject -->
    <a href="approve-student.php?id=<?= $student['id'] ?>" class="btn btn-sm btn-success">Approve</a>

    <!-- ADD THIS NEW LINK/BUTTON -->
    <a href="view-resume.php?id=<?= $student['id'] ?>" class="btn btn-sm btn-info">View Resume</a>
</td>
