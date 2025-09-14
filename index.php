<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusHire - Placement Portal</title>

    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        /* General Body & Typography */
        :root {
            --primary-purple: #6366F1; /* A nice, modern purple/blue */
            --dark-purple: #4338CA;
            --highlight-yellow: #FBBF24;
            --light-blue: #EFF6FF;
            --text-light: #F0F9FF;
            --text-dark: #1F2937;
            --text-medium: #4B5563;
            --border-light: rgba(255, 255, 255, 0.2);
            --border-gray: #E5E7EB;
            --background-gray: #F9FAFB;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #FFFFFF;
            color: var(--text-dark);
            margin: 0;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* Header & Navbar */
        .header {
            background-color: white;
            border-bottom: 1px solid var(--border-gray);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 80px;
        }

        .nav-logo a {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-purple);
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        .nav-logo i {
            margin-right: 0.5rem;
        }

        .nav-links {
            display: none;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 2rem;
        }
        .nav-links a {
            color: var(--text-dark);
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }
        .nav-links a:hover {
            color: var(--primary-purple);
        }
        
        .nav-actions {
             display: none;
        }
        
        .btn {
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
            border: 2px solid transparent;
        }
        .btn-primary {
            background-color: var(--primary-purple);
            color: white;
            border-color: var(--primary-purple);
        }
        .btn-primary:hover {
            background-color: var(--dark-purple);
            border-color: var(--dark-purple);
        }
        .btn-outline {
            background-color: transparent;
            color: white;
            border-color: white;
        }
        .btn-outline:hover {
            background-color: white;
            color: var(--primary-purple);
        }

        .mobile-menu-button {
            display: block;
            background: none;
            border: none;
            cursor: pointer;
        }
        
        .mobile-menu {
            display: none;
            padding: 1rem 0;
            background-color: white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .mobile-menu a {
            display: block;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            color: var(--text-dark);
            font-weight: bold;
        }
        
        @media (min-width: 992px) {
            .nav-links, .nav-actions {
                display: flex;
            }
            .mobile-menu-button {
                display: none;
            }
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(45deg, #4338CA, #6366F1);
            padding: 6rem 0;
            color: white;
        }
        .hero-content {
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }
        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: bold;
            line-height: 1.1;
            margin: 0 0 1.5rem 0;
        }
        .hero-section .highlight {
            color: var(--highlight-yellow);
        }
        .hero-section p {
            font-size: 1.2rem;
            margin-bottom: 2.5rem;
            color: var(--text-light);
        }
        .hero-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .hero-stats {
            margin-top: 5rem;
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 2rem;
            border-top: 1px solid var(--border-light);
            padding-top: 3rem;
        }
        .stat-item {
            text-align: center;
        }
        .stat-item .number {
            font-size: 3rem;
            font-weight: bold;
        }
        .stat-item .label {
            font-size: 1rem;
            color: var(--text-light);
            text-transform: uppercase;
        }

        /* Features Section */
        .features-section {
            padding: 5rem 0;
            background-color: var(--background-gray);
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-header h2 {
            font-size: 2.25rem;
            font-weight: bold;
        }
        .section-header p {
            font-size: 1.1rem;
            color: var(--text-medium);
            margin-top: 0.5rem;
        }

        .features-grid {
            display: grid;
            gap: 1.5rem;
        }

        @media (min-width: 768px) {
            .features-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .feature-card {
            background: white;
            border: 1px solid var(--border-gray);
            border-radius: 12px;
            padding: 2.5rem 2rem;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        .feature-icon {
            background-color: var(--light-blue);
            color: var(--primary-purple);
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem auto;
        }

        .feature-card h3 {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .feature-card p {
            color: var(--text-medium);
        }


        /* Footer */
        .footer {
            background-color: var(--text-dark);
            color: #D1D5DB;
            text-align: center;
            padding: 2rem 0;
        }
        
        @media (max-width: 768px) {
            .hero-section h1 { font-size: 2.5rem; }
            .hero-stats {
                flex-direction: column;
                align-items: center;
                gap: 2.5rem;
            }
        }
    </style>
</head>

<body>

    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <div class="nav-logo">
                    <a href="#">
                        <i data-lucide="graduation-cap"></i>
                        CampusHire
                    </a>
                </div>
                <ul class="nav-links">
                    <li><a href="#">Home</a></li>
                    <li><a href="student/jobs.php">Jobs</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
                <div class="nav-actions">
                    <a href="student-registration.php" class="btn btn-primary">Sign Up</a>
                </div>
                <button class="mobile-menu-button" id="mobile-menu-button">
                    <i data-lucide="menu"></i>
                </button>
            </nav>
        </div>
        <div class="mobile-menu" id="mobile-menu">
            <a href="#">Home</a>
            <a href="student/jobs.php">Jobs</a>
            <a href="#">Contact</a>
            <a href="student-registration.php">Sign Up</a>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="hero-section">
             <div class="container">
                <div class="hero-content">
                    <h1>Land Your <span class="highlight">Dream Job</span> with Expert Guidance</h1>
                    <p>Join thousands of students who have successfully secured top placements with our comprehensive career assistance program.</p>
                    <div class="hero-actions">
                        <a href="student-registration.php" class="btn btn-primary">Get Started Today</a>
                        <a href="student/jobs.php" class="btn btn-outline">Explore Jobs</a>
                    </div>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="number">5,000+</div>
                            <div class="label">Students Placed</div>
                        </div>
                        <div class="stat-item">
                            <div class="number">95%</div>
                            <div class="label">Success Rate</div>
                        </div>
                        <div class="stat-item">
                            <div class="number">500+</div>
                            <div class="label">Partner Companies</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="features-section">
            <div class="container">
                <div class="section-header">
                    <h2>How It Works</h2>
                    <p>A simple, streamlined process to connect you with your future career.</p>
                </div>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i data-lucide="user-plus" style="width:32px; height:32px;"></i>
                        </div>
                        <h3>1. Create Profile</h3>
                        <p>Sign up and build a compelling profile to showcase your skills and achievements.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i data-lucide="search" style="width:32px; height:32px;"></i>
                        </div>
                        <h3>2. Find Opportunities</h3>
                        <p>Browse exclusive job listings from companies verified by our placement cell.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i data-lucide="file-check-2" style="width:32px; height:32px;"></i>
                        </div>
                        <h3>3. Apply & Succeed</h3>
                        <p>Apply to your chosen roles with ease and track your application progress.</p>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 CampusHire Placement Portal. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();
        
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenuButton.addEventListener('click', () => {
            if (mobileMenu.style.display === 'block') {
                mobileMenu.style.display = 'none';
            } else {
                mobileMenu.style.display = 'block';
            }
        });

    </script>
</body>
</html>

