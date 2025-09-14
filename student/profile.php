<?php
// We must start the session and include the necessary PHP logic files first.
// These files do not output any HTML.
require_once '../auth-check.php';
checkAccess('student');
require_once '../db-functions.php';

// The user_id is set in the session from a successful login.
$user_id = $_SESSION['user_id'];
$error = '';
$success_message = '';
$edit_mode = isset($_GET['edit']) && $_GET['edit'] == '1';

// --- FORM PROCESSING LOGIC (MOVED TO THE TOP) ---
// This entire block now runs BEFORE any HTML is sent to the browser.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $prn = $_POST['prn'];
  $name = $_POST['name'];
  $phone = $_POST['phone'];
  $dob = $_POST['dob'];
  
  // Academic Information
  $institution_name = $_POST['institution_name'] ?? '';
  $course_name = $_POST['course_name'] ?? '';
  $branch = $_POST['branch'] ?? '';
  $graduation_year = $_POST['graduation_year'] ?? '';
  $cgpa = $_POST['cgpa'] ?? '';
  
  // Contact Information
  $linkedin_url = $_POST['linkedin_url'] ?? '';
  $github_url = $_POST['github_url'] ?? '';
  $portfolio_url = $_POST['portfolio_url'] ?? '';
  
  // Skills
  $technical_skills = $_POST['technical_skills'] ?? '';
  $soft_skills = $_POST['soft_skills'] ?? '';
  $languages = $_POST['languages'] ?? '';
  
  // Projects and Experiences
  $projects = $_POST['projects'] ?? [];
  $experiences = $_POST['experiences'] ?? [];

  $current_student = getStudentByUserId($user_id);
  $id_card = $current_student['id_card'] ?? null;
  $resume = $current_student['resume_path'] ?? null;

  if (!empty($_FILES['id_card']['name'])) {
    $ext = strtolower(pathinfo($_FILES['id_card']['name'], PATHINFO_EXTENSION));
    $id_card = "uploads/IDcard/" . "id_" . $user_id . "." . $ext;
    $id_card_target_path = "../" . $id_card;
    move_uploaded_file($_FILES['id_card']['tmp_name'], $id_card_target_path);
  }

  if (!empty($_FILES['resume']['name'])) {
    $ext = strtolower(pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION));
    $resume = "uploads/resumes/" . "resume_" . $user_id . "." . $ext;
    $resume_target_path = "../" . $resume;
    move_uploaded_file($_FILES['resume']['tmp_name'], $resume_target_path);
  }

  // Update basic student profile
  if ($current_student) {
    $success = updateStudentProfile($user_id, $prn, $name, $phone, $dob, $id_card, $resume);
  } else {
    $success = createStudentProfile($user_id, $prn, $name, $phone, $dob, $id_card, $resume);
  }

  if ($success) {
    $student_id = $current_student['id'] ?? getStudentByUserId($user_id)['id'];
    
    // Update academic information
    $academic_info = getStudentAcademicInfo($student_id);
    if ($academic_info) {
      updateStudentAcademicInfo($student_id, $institution_name, $course_name, $branch, $graduation_year, $cgpa);
    } else {
      createStudentAcademicInfo($student_id, $institution_name, $course_name, $branch, $graduation_year, $cgpa);
    }
    
    // Update contact information
    $contact_info = getStudentContactInfo($student_id);
    if ($contact_info) {
      updateStudentContactInfo($student_id, $linkedin_url, $github_url, $portfolio_url);
    } else {
      createStudentContactInfo($student_id, $linkedin_url, $github_url, $portfolio_url);
    }
    
    // Update skills
    $skills = getStudentSkills($student_id);
    if ($skills) {
      updateStudentSkills($student_id, $technical_skills, $soft_skills, $languages);
    } else {
      createStudentSkills($student_id, $technical_skills, $soft_skills, $languages);
    }
    
    // Handle projects
    foreach ($projects as $project_data) {
      if (!empty($project_data['name'])) {
        if (!empty($project_data['id'])) {
          // Update existing project
          updateStudentProject($project_data['id'], $project_data['name'], $project_data['description'] ?? '', $project_data['technologies'] ?? '', $project_data['url'] ?? '');
        } else {
          // Create new project
          createStudentProject($student_id, $project_data['name'], $project_data['description'] ?? '', $project_data['technologies'] ?? '', $project_data['url'] ?? '');
        }
      }
    }
    
    // Handle experiences
    foreach ($experiences as $experience_data) {
      if (!empty($experience_data['company']) && !empty($experience_data['position'])) {
        $is_current = isset($experience_data['is_current']) ? 1 : 0;
        if (!empty($experience_data['id'])) {
          // Update existing experience
          updateStudentExperience($experience_data['id'], $experience_data['type'], $experience_data['company'], $experience_data['position'], $experience_data['description'] ?? '', $experience_data['start_date'], $experience_data['end_date'] ?? null, $is_current);
        } else {
          // Create new experience
          createStudentExperience($student_id, $experience_data['type'], $experience_data['company'], $experience_data['position'], $experience_data['description'] ?? '', $experience_data['start_date'], $experience_data['end_date'] ?? null, $is_current);
        }
      }
    }
    
    // Redirect to read mode after successful update
    header("Location: profile.php?success=1");
    exit();
  } else {
    $error = "Failed to save profile. Please try again.";
  }
}


// --- START BUILDING THE PAGE ---
// Now that all backend work is done, we can include the header and start outputting HTML.
require_once 'student_header.php';

// Check for a success flag in the URL (after a redirect)
if (isset($_GET['success']) && $_GET['success'] == 1) {
  $success_message = "Your profile has been saved successfully!";
}

// Fetch the latest student data to display
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

<!-- Page Content -->
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center">
        <?php if ($gravatar_url): ?>
          <img src="<?= htmlspecialchars($gravatar_url) ?>" alt="Profile Photo" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
        <?php endif; ?>
        <div>
      <h4 class="card-title mb-0">👤 My Profile</h4>
          <?php if ($user): ?>
            <small class="text-muted"><?= htmlspecialchars($user['email']) ?></small>
          <?php endif; ?>
        </div>
      </div>
      <?php if (!$edit_mode): ?>
        <a href="?edit=1" class="btn btn-outline-primary">
          <i data-lucide="edit" style="width:16px; height:16px;" class="me-1"></i>Edit Profile
        </a>
      <?php else: ?>
        <a href="?" class="btn btn-outline-secondary">
          <i data-lucide="eye" style="width:16px; height:16px;" class="me-1"></i>View Profile
        </a>
      <?php endif; ?>
    </div>
    <div class="card-body">
      <p class="card-text text-muted">Keep your information up-to-date for recruiters.</p>

      <?php if ($success_message): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
      <?php endif; ?>
      <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
      <?php endif; ?>

      <?php if ($edit_mode): ?>
        <!-- EDIT MODE FORM -->
      <form method="post" enctype="multipart/form-data" class="mt-4">
          <!-- Personal Details -->
          <h5 class="mb-3 fw-semibold">Personal Information</h5>
          <div class="row">
          <div class="col-md-6 mb-3">
            <label for="name" class="form-label fw-semibold">Full Name</label>
            <input type="text" id="name" name="name" class="form-control"
              value="<?= htmlspecialchars($student['name'] ?? '') ?>" required>
          </div>
          <div class="col-md-6 mb-3">
            <label for="prn" class="form-label fw-semibold">PRN (Permanent Registration Number)</label>
            <input type="text" id="prn" name="prn" class="form-control"
              value="<?= htmlspecialchars($student['prn'] ?? '') ?>" required>
          </div>
          <div class="col-md-6 mb-3">
            <label for="phone" class="form-label fw-semibold">Phone Number</label>
            <input type="tel" id="phone" name="phone" class="form-control"
              value="<?= htmlspecialchars($student['phone_number'] ?? '') ?>" required>
          </div>
          <div class="col-md-6 mb-3">
            <label for="dob" class="form-label fw-semibold">Date of Birth</label>
            <input type="date" id="dob" name="dob" class="form-control"
              value="<?= htmlspecialchars($student['dob'] ?? '') ?>" required>
          </div>
          </div>

          <hr class="my-4">

          <!-- Academic Information -->
          <h5 class="mb-3 fw-semibold">Academic Information</h5>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="institution_name" class="form-label fw-semibold">Institution Name</label>
              <input type="text" id="institution_name" name="institution_name" class="form-control"
                value="<?= htmlspecialchars($academic_info['institution_name'] ?? '') ?>">
            </div>
            <div class="col-md-6 mb-3">
              <label for="course_name" class="form-label fw-semibold">Course Name</label>
              <input type="text" id="course_name" name="course_name" class="form-control"
                value="<?= htmlspecialchars($academic_info['course_name'] ?? '') ?>">
            </div>
            <div class="col-md-6 mb-3">
              <label for="branch" class="form-label fw-semibold">Branch</label>
              <input type="text" id="branch" name="branch" class="form-control"
                value="<?= htmlspecialchars($academic_info['branch'] ?? '') ?>">
            </div>
            <div class="col-md-6 mb-3">
              <label for="graduation_year" class="form-label fw-semibold">Graduation Year</label>
              <input type="number" id="graduation_year" name="graduation_year" class="form-control"
                value="<?= htmlspecialchars($academic_info['graduation_year'] ?? '') ?>">
            </div>
            <div class="col-md-6 mb-3">
              <label for="cgpa" class="form-label fw-semibold">CGPA</label>
              <input type="number" step="0.01" id="cgpa" name="cgpa" class="form-control"
                value="<?= htmlspecialchars($academic_info['cgpa'] ?? '') ?>">
            </div>
          </div>

          <hr class="my-4">

          <!-- Contact Information -->
          <h5 class="mb-3 fw-semibold">Professional Links</h5>
          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="linkedin_url" class="form-label fw-semibold">LinkedIn URL</label>
              <input type="url" id="linkedin_url" name="linkedin_url" class="form-control"
                value="<?= htmlspecialchars($contact_info['linkedin_url'] ?? '') ?>">
            </div>
            <div class="col-md-4 mb-3">
              <label for="github_url" class="form-label fw-semibold">GitHub URL</label>
              <input type="url" id="github_url" name="github_url" class="form-control"
                value="<?= htmlspecialchars($contact_info['github_url'] ?? '') ?>">
            </div>
            <div class="col-md-4 mb-3">
              <label for="portfolio_url" class="form-label fw-semibold">Portfolio URL</label>
              <input type="url" id="portfolio_url" name="portfolio_url" class="form-control"
                value="<?= htmlspecialchars($contact_info['portfolio_url'] ?? '') ?>">
            </div>
          </div>

          <hr class="my-4">

          <!-- Skills -->
          <h5 class="mb-3 fw-semibold">Skills</h5>
          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="technical_skills" class="form-label fw-semibold">Technical Skills</label>
              <textarea id="technical_skills" name="technical_skills" class="form-control" rows="3"
                placeholder="Enter skills separated by commas, e.g., Python, JavaScript, React, MySQL"><?= htmlspecialchars($skills['technical_skills'] ?? '') ?></textarea>
              <small class="text-muted">Separate multiple skills with commas</small>
            </div>
            <div class="col-md-4 mb-3">
              <label for="soft_skills" class="form-label fw-semibold">Soft Skills</label>
              <textarea id="soft_skills" name="soft_skills" class="form-control" rows="3"
                placeholder="Enter skills separated by commas, e.g., Leadership, Communication, Problem Solving"><?= htmlspecialchars($skills['soft_skills'] ?? '') ?></textarea>
              <small class="text-muted">Separate multiple skills with commas</small>
            </div>
            <div class="col-md-4 mb-3">
              <label for="languages" class="form-label fw-semibold">Languages</label>
              <textarea id="languages" name="languages" class="form-control" rows="3"
                placeholder="Enter languages separated by commas, e.g., English, Hindi, Spanish"><?= htmlspecialchars($skills['languages'] ?? '') ?></textarea>
              <small class="text-muted">Separate multiple languages with commas</small>
            </div>
          </div>

          <hr class="my-4">

          <!-- Projects Section -->
          <h5 class="mb-3 fw-semibold">Projects</h5>
          <div id="projects-container">
            <?php if ($projects && count($projects) > 0): ?>
              <?php foreach ($projects as $index => $project): ?>
                <div class="project-item border rounded p-3 mb-3">
                  <div class="row">
                    <div class="col-md-6 mb-2">
                      <label class="form-label fw-semibold">Project Name</label>
                      <input type="text" name="projects[<?= $index ?>][name]" class="form-control"
                        value="<?= htmlspecialchars($project['project_name'] ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-2">
                      <label class="form-label fw-semibold">Technologies</label>
                      <input type="text" name="projects[<?= $index ?>][technologies]" class="form-control"
                        value="<?= htmlspecialchars($project['technologies'] ?? '') ?>"
                        placeholder="Enter technologies separated by commas, e.g., React, Node.js, MongoDB">
                      <small class="text-muted">Separate multiple technologies with commas</small>
                    </div>
                    <div class="col-12 mb-2">
                      <label class="form-label fw-semibold">Description</label>
                      <textarea name="projects[<?= $index ?>][description]" class="form-control" rows="2"><?= htmlspecialchars($project['description'] ?? '') ?></textarea>
                    </div>
                    <div class="col-md-6 mb-2">
                      <label class="form-label fw-semibold">Project URL</label>
                      <input type="url" name="projects[<?= $index ?>][url]" class="form-control"
                        value="<?= htmlspecialchars($project['project_url'] ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-2 d-flex align-items-end">
                      <button type="button" class="btn btn-outline-danger btn-sm remove-project">
                        <i data-lucide="trash-2" style="width:16px; height:16px;" class="me-1"></i>Remove
                      </button>
                    </div>
                  </div>
                  <input type="hidden" name="projects[<?= $index ?>][id]" value="<?= $project['id'] ?>">
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
          <button type="button" id="add-project" class="btn btn-outline-primary btn-sm">
            <i data-lucide="plus" style="width:16px; height:16px;" class="me-1"></i>Add Project
          </button>

          <hr class="my-4">

          <!-- Experience Section -->
          <h5 class="mb-3 fw-semibold">Experience</h5>
          <div id="experience-container">
            <?php if ($experiences && count($experiences) > 0): ?>
              <?php foreach ($experiences as $index => $experience): ?>
                <div class="experience-item border rounded p-3 mb-3">
                  <div class="row">
                    <div class="col-md-6 mb-2">
                      <label class="form-label fw-semibold">Experience Type</label>
                      <select name="experiences[<?= $index ?>][type]" class="form-control">
                        <option value="internship" <?= $experience['experience_type'] == 'internship' ? 'selected' : '' ?>>Internship</option>
                        <option value="job" <?= $experience['experience_type'] == 'job' ? 'selected' : '' ?>>Job</option>
                        <option value="freelance" <?= $experience['experience_type'] == 'freelance' ? 'selected' : '' ?>>Freelance</option>
                        <option value="volunteer" <?= $experience['experience_type'] == 'volunteer' ? 'selected' : '' ?>>Volunteer</option>
                        <option value="research" <?= $experience['experience_type'] == 'research' ? 'selected' : '' ?>>Research</option>
                        <option value="training" <?= $experience['experience_type'] == 'training' ? 'selected' : '' ?>>Training</option>
                      </select>
                    </div>
                    <div class="col-md-6 mb-2">
                      <label class="form-label fw-semibold">Company/Organization</label>
                      <input type="text" name="experiences[<?= $index ?>][company]" class="form-control"
                        value="<?= htmlspecialchars($experience['company_name'] ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-2">
                      <label class="form-label fw-semibold">Position</label>
                      <input type="text" name="experiences[<?= $index ?>][position]" class="form-control"
                        value="<?= htmlspecialchars($experience['position'] ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-2">
                      <label class="form-label fw-semibold">Location</label>
                      <input type="text" name="experiences[<?= $index ?>][location]" class="form-control"
                        value="<?= htmlspecialchars($experience['location'] ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-2">
                      <label class="form-label fw-semibold">Start Date</label>
                      <input type="date" name="experiences[<?= $index ?>][start_date]" class="form-control"
                        value="<?= htmlspecialchars($experience['start_date'] ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-2">
                      <label class="form-label fw-semibold">End Date</label>
                      <input type="date" name="experiences[<?= $index ?>][end_date]" class="form-control"
                        value="<?= htmlspecialchars($experience['end_date'] ?? '') ?>">
                    </div>
                    <div class="col-12 mb-2">
                      <div class="form-check">
                        <input type="checkbox" name="experiences[<?= $index ?>][is_current]" class="form-check-input"
                          <?= $experience['is_current'] ? 'checked' : '' ?>>
                        <label class="form-check-label">Currently working here</label>
                      </div>
                    </div>
                    <div class="col-12 mb-2">
                      <label class="form-label fw-semibold">Description</label>
                      <textarea name="experiences[<?= $index ?>][description]" class="form-control" rows="2"><?= htmlspecialchars($experience['description'] ?? '') ?></textarea>
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                      <button type="button" class="btn btn-outline-danger btn-sm remove-experience">
                        <i data-lucide="trash-2" style="width:16px; height:16px;" class="me-1"></i>Remove
                      </button>
                    </div>
                  </div>
                  <input type="hidden" name="experiences[<?= $index ?>][id]" value="<?= $experience['id'] ?>">
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
          <button type="button" id="add-experience" class="btn btn-outline-primary btn-sm">
            <i data-lucide="plus" style="width:16px; height:16px;" class="me-1"></i>Add Experience
          </button>

          <hr class="my-4">

          <!-- File Uploads -->
          <h5 class="mb-3 fw-semibold">Documents</h5>
          <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">ID Card</label>
            <?php if (!empty($student['id_card'])): ?>
              <div class="alert alert-light p-2 d-flex justify-content-between align-items-center">
                <span>
                  <i data-lucide="file-image" class="me-2" style="width:16px; height:16px;"></i>
                  <strong><?= htmlspecialchars(basename($student['id_card'])) ?></strong>
                </span>
                <a href="../<?= htmlspecialchars($student['id_card']) ?>" target="_blank"
                  class="btn btn-outline-primary btn-sm">View</a>
              </div>
                <label for="id_card" class="form-label text-muted small">Upload a new file to replace the current one:</label>
            <?php endif; ?>
            <input type="file" id="id_card" name="id_card" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Resume/CV</label>
            <?php if (!empty($student['resume_path'])): ?>
              <div class="alert alert-light p-2 d-flex justify-content-between align-items-center">
                <span>
                  <i data-lucide="file-text" class="me-2" style="width:16px; height:16px;"></i>
                  <strong><?= htmlspecialchars(basename($student['resume_path'])) ?></strong>
                </span>
                <a href="../<?= htmlspecialchars($student['resume_path']) ?>" target="_blank"
                  class="btn btn-outline-primary btn-sm">View / Download</a>
              </div>
                <label for="resume" class="form-label text-muted small">Upload a new file to replace the current one:</label>
            <?php endif; ?>
            <input type="file" id="resume" name="resume" class="form-control" accept=".pdf,.doc,.docx">
          </div>
        </div>

          <div class="d-grid mt-4">
            <button type="submit" class="btn btn-primary btn-lg">Save Profile</button>
          </div>
        </form>
      <?php else: ?>
        <!-- READ MODE DISPLAY -->
        <div class="mt-4">
          <!-- Profile Photo and Basic Info -->
          <div class="row mb-4">
            <div class="col-12">
              <div class="d-flex align-items-center mb-4">
                <?php if ($gravatar_url): ?>
                  <img src="<?= htmlspecialchars($gravatar_url) ?>" alt="Profile Photo" class="rounded-circle me-4" style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #e9ecef;">
                <?php endif; ?>
                <div>
                  <h3 class="mb-1"><?= htmlspecialchars($student['name'] ?? 'Not provided') ?></h3>
                  <p class="text-muted mb-1"><?= htmlspecialchars($student['prn'] ?? 'Not provided') ?></p>
                  <?php if ($academic_info): ?>
                    <p class="text-muted mb-0"><?= htmlspecialchars(($academic_info['course_name'] ?? '') . ' - ' . ($academic_info['branch'] ?? '')) ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>

          <!-- Personal Information -->
          <div class="row mb-4">
            <div class="col-12">
              <h5 class="mb-3 fw-semibold">Personal Information</h5>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <strong>Full Name:</strong><br>
                  <span class="text-muted"><?= htmlspecialchars($student['name'] ?? 'Not provided') ?></span>
                </div>
                <div class="col-md-6 mb-3">
                  <strong>PRN:</strong><br>
                  <span class="text-muted"><?= htmlspecialchars($student['prn'] ?? 'Not provided') ?></span>
                </div>
                <div class="col-md-6 mb-3">
                  <strong>Phone Number:</strong><br>
                  <span class="text-muted"><?= htmlspecialchars($student['phone_number'] ?? 'Not provided') ?></span>
                </div>
                <div class="col-md-6 mb-3">
                  <strong>Date of Birth:</strong><br>
                  <span class="text-muted"><?= htmlspecialchars($student['dob'] ?? 'Not provided') ?></span>
                </div>
              </div>
            </div>
        </div>

          <!-- Academic Information -->
          <?php if ($academic_info): ?>
          <div class="row mb-4">
            <div class="col-12">
              <h5 class="mb-3 fw-semibold">Academic Information</h5>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <strong>Institution:</strong><br>
                  <span class="text-muted"><?= htmlspecialchars($academic_info['institution_name'] ?? 'Not provided') ?></span>
                </div>
                <div class="col-md-6 mb-3">
                  <strong>Course:</strong><br>
                  <span class="text-muted"><?= htmlspecialchars($academic_info['course_name'] ?? 'Not provided') ?></span>
                </div>
                <div class="col-md-6 mb-3">
                  <strong>Branch:</strong><br>
                  <span class="text-muted"><?= htmlspecialchars($academic_info['branch'] ?? 'Not provided') ?></span>
                </div>
                <div class="col-md-6 mb-3">
                  <strong>Graduation Year:</strong><br>
                  <span class="text-muted"><?= htmlspecialchars($academic_info['graduation_year'] ?? 'Not provided') ?></span>
                </div>
                <div class="col-md-6 mb-3">
                  <strong>CGPA:</strong><br>
                  <span class="text-muted"><?= htmlspecialchars($academic_info['cgpa'] ?? 'Not provided') ?></span>
                </div>
              </div>
            </div>
          </div>
          <?php endif; ?>

          <!-- Contact Information -->
          <?php if ($contact_info && ($contact_info['linkedin_url'] || $contact_info['github_url'] || $contact_info['portfolio_url'])): ?>
          <div class="row mb-4">
            <div class="col-12">
              <h5 class="mb-3 fw-semibold">Professional Links</h5>
              <div class="row">
                <?php if ($contact_info['linkedin_url']): ?>
                <div class="col-md-4 mb-3">
                  <strong>LinkedIn:</strong><br>
                  <a href="<?= htmlspecialchars($contact_info['linkedin_url'] ?? '') ?>" target="_blank" class="text-primary">
                    <?= htmlspecialchars($contact_info['linkedin_url'] ?? '') ?>
                  </a>
                </div>
                <?php endif; ?>
                <?php if ($contact_info['github_url']): ?>
                <div class="col-md-4 mb-3">
                  <strong>GitHub:</strong><br>
                  <a href="<?= htmlspecialchars($contact_info['github_url'] ?? '') ?>" target="_blank" class="text-primary">
                    <?= htmlspecialchars($contact_info['github_url'] ?? '') ?>
                  </a>
                </div>
                <?php endif; ?>
                <?php if ($contact_info['portfolio_url']): ?>
                <div class="col-md-4 mb-3">
                  <strong>Portfolio:</strong><br>
                  <a href="<?= htmlspecialchars($contact_info['portfolio_url'] ?? '') ?>" target="_blank" class="text-primary">
                    <?= htmlspecialchars($contact_info['portfolio_url'] ?? '') ?>
                  </a>
                </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <?php endif; ?>

          <!-- Skills -->
          <?php if ($skills && ($skills['technical_skills'] || $skills['soft_skills'] || $skills['languages'])): ?>
          <div class="row mb-4">
            <div class="col-12">
              <h5 class="mb-3 fw-semibold">Skills</h5>
              <div class="row">
                <?php if ($skills['technical_skills']): ?>
                <div class="col-md-4 mb-3">
                  <strong class="d-block mb-2">Technical Skills:</strong>
                  <div class="d-flex flex-wrap gap-1">
                    <?php 
                    $technical_skills_array = array_map('trim', explode(',', $skills['technical_skills'] ?? ''));
                    foreach ($technical_skills_array as $skill): 
                      if (!empty($skill)):
                    ?>
                      <span class="badge bg-primary"><?= htmlspecialchars($skill) ?></span>
                    <?php 
                      endif;
                    endforeach; 
                    ?>
                  </div>
                </div>
                <?php endif; ?>
                <?php if ($skills['soft_skills']): ?>
                <div class="col-md-4 mb-3">
                  <strong class="d-block mb-2">Soft Skills:</strong>
                  <div class="d-flex flex-wrap gap-1">
                    <?php 
                    $soft_skills_array = array_map('trim', explode(',', $skills['soft_skills'] ?? ''));
                    foreach ($soft_skills_array as $skill): 
                      if (!empty($skill)):
                    ?>
                      <span class="badge bg-success"><?= htmlspecialchars($skill) ?></span>
                    <?php 
                      endif;
                    endforeach; 
                    ?>
                  </div>
                </div>
                <?php endif; ?>
                <?php if ($skills['languages']): ?>
                <div class="col-md-4 mb-3">
                  <strong class="d-block mb-2">Languages:</strong>
                  <div class="d-flex flex-wrap gap-1">
                    <?php 
                    $languages_array = array_map('trim', explode(',', $skills['languages'] ?? ''));
                    foreach ($languages_array as $language): 
                      if (!empty($language)):
                    ?>
                      <span class="badge bg-info"><?= htmlspecialchars($language) ?></span>
                    <?php 
                      endif;
                    endforeach; 
                    ?>
                  </div>
                </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <?php endif; ?>

          <!-- Projects -->
          <?php if ($projects && count($projects) > 0): ?>
          <div class="row mb-4">
            <div class="col-12">
              <h5 class="mb-3 fw-semibold">Projects</h5>
              <?php foreach ($projects as $project): ?>
                <div class="card mb-3">
                  <div class="card-body">
                    <h6 class="card-title"><?= htmlspecialchars($project['project_name'] ?? 'Untitled Project') ?></h6>
                    <?php if ($project['description']): ?>
                      <p class="card-text"><?= htmlspecialchars($project['description'] ?? '') ?></p>
                    <?php endif; ?>
                    <div class="row">
                      <?php if ($project['technologies']): ?>
                      <div class="col-md-6">
                        <strong class="d-block mb-2">Technologies:</strong>
                        <div class="d-flex flex-wrap gap-1">
                          <?php 
                          $technologies_array = array_map('trim', explode(',', $project['technologies'] ?? ''));
                          foreach ($technologies_array as $tech): 
                            if (!empty($tech)):
                          ?>
                            <span class="badge bg-secondary"><?= htmlspecialchars($tech) ?></span>
                          <?php 
                            endif;
                          endforeach; 
                          ?>
                        </div>
                      </div>
                      <?php endif; ?>
                      <?php if ($project['project_url']): ?>
                      <div class="col-md-6">
                        <strong>Project URL:</strong><br>
                        <a href="<?= htmlspecialchars($project['project_url'] ?? '') ?>" target="_blank" class="text-primary">
                          <?= htmlspecialchars($project['project_url'] ?? '') ?>
                        </a>
                      </div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>

          <!-- Experience -->
          <?php if ($experiences && count($experiences) > 0): ?>
          <div class="row mb-4">
            <div class="col-12">
              <h5 class="mb-3 fw-semibold">Experience</h5>
              <?php foreach ($experiences as $experience): ?>
                <div class="card mb-3">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                      <div>
                        <h6 class="card-title"><?= htmlspecialchars($experience['position'] ?? 'Position') ?></h6>
                        <p class="card-subtitle text-muted mb-1"><?= htmlspecialchars($experience['company_name'] ?? 'Company') ?></p>
                        <small class="text-muted">
                          <?= ucfirst($experience['experience_type'] ?? 'experience') ?>
                          <?php if ($experience['location'] ?? ''): ?>
                            • <?= htmlspecialchars($experience['location'] ?? '') ?>
                          <?php endif; ?>
                        </small>
                      </div>
                      <div class="text-end">
                        <small class="text-muted">
                          <?= date('M Y', strtotime($experience['start_date'] ?? 'now')) ?>
                          <?php if ($experience['is_current']): ?>
                            - Present
                          <?php elseif ($experience['end_date']): ?>
                            - <?= date('M Y', strtotime($experience['end_date'] ?? 'now')) ?>
                          <?php endif; ?>
                        </small>
                      </div>
                    </div>
                    <?php if ($experience['description']): ?>
                      <p class="card-text mt-2"><?= htmlspecialchars($experience['description'] ?? '') ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>

          <!-- Documents -->
          <div class="row mb-4">
            <div class="col-12">
              <h5 class="mb-3 fw-semibold">Documents</h5>
              <div class="row">
                <?php if (!empty($student['id_card'])): ?>
                <div class="col-md-6 mb-3">
                  <strong>ID Card:</strong><br>
                  <a href="../<?= htmlspecialchars($student['id_card']) ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i data-lucide="file-image" style="width:16px; height:16px;" class="me-1"></i>View ID Card
                  </a>
                </div>
                <?php endif; ?>
                <?php if (!empty($student['resume_path'])): ?>
                <div class="col-md-6 mb-3">
                  <strong>Resume/CV:</strong><br>
                  <a href="../<?= htmlspecialchars($student['resume_path']) ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i data-lucide="file-text" style="width:16px; height:16px;" class="me-1"></i>View Resume
                  </a>
                </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
// Dynamic form management for projects and experiences
document.addEventListener('DOMContentLoaded', function() {
    let projectIndex = <?= count($projects ?? []) ?>;
    let experienceIndex = <?= count($experiences ?? []) ?>;
    
    // Add Project functionality
    document.getElementById('add-project')?.addEventListener('click', function() {
        const container = document.getElementById('projects-container');
        const projectHtml = `
            <div class="project-item border rounded p-3 mb-3">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label fw-semibold">Project Name</label>
                        <input type="text" name="projects[${projectIndex}][name]" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-2">
                      <label class="form-label fw-semibold">Technologies</label>
                      <input type="text" name="projects[${projectIndex}][technologies]" class="form-control"
                        placeholder="Enter technologies separated by commas, e.g., React, Node.js, MongoDB">
                      <small class="text-muted">Separate multiple technologies with commas</small>
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="projects[${projectIndex}][description]" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label fw-semibold">Project URL</label>
                        <input type="url" name="projects[${projectIndex}][url]" class="form-control">
                    </div>
                    <div class="col-md-6 mb-2 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-project">
                            <i data-lucide="trash-2" style="width:16px; height:16px;" class="me-1"></i>Remove
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', projectHtml);
        projectIndex++;
    });
    
    // Add Experience functionality
    document.getElementById('add-experience')?.addEventListener('click', function() {
        const container = document.getElementById('experience-container');
        const experienceHtml = `
            <div class="experience-item border rounded p-3 mb-3">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label fw-semibold">Experience Type</label>
                        <select name="experiences[${experienceIndex}][type]" class="form-control">
                            <option value="internship">Internship</option>
                            <option value="job">Job</option>
                            <option value="freelance">Freelance</option>
                            <option value="volunteer">Volunteer</option>
                            <option value="research">Research</option>
                            <option value="training">Training</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label fw-semibold">Company/Organization</label>
                        <input type="text" name="experiences[${experienceIndex}][company]" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label fw-semibold">Position</label>
                        <input type="text" name="experiences[${experienceIndex}][position]" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label fw-semibold">Location</label>
                        <input type="text" name="experiences[${experienceIndex}][location]" class="form-control">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label fw-semibold">Start Date</label>
                        <input type="date" name="experiences[${experienceIndex}][start_date]" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label fw-semibold">End Date</label>
                        <input type="date" name="experiences[${experienceIndex}][end_date]" class="form-control">
                    </div>
                    <div class="col-12 mb-2">
                        <div class="form-check">
                            <input type="checkbox" name="experiences[${experienceIndex}][is_current]" class="form-check-input">
                            <label class="form-check-label">Currently working here</label>
                        </div>
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="experiences[${experienceIndex}][description]" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-experience">
                            <i data-lucide="trash-2" style="width:16px; height:16px;" class="me-1"></i>Remove
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', experienceHtml);
        experienceIndex++;
    });
    
    // Remove Project functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-project')) {
            e.target.closest('.project-item').remove();
        }
    });
    
    // Remove Experience functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-experience')) {
            e.target.closest('.experience-item').remove();
        }
    });
});
</script>

<?php
// Includes the closing HTML tags and necessary JS.
require_once 'student_footer.php';
?>