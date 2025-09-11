<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusHire - Your Future Awaits</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F9FAFB; /* Light Gray Background */
            color: #1F2937; /* Dark Gray Text */
        }

        .header-bg {
             background-color: rgba(255, 255, 255, 0.8);
             backdrop-filter: blur(12px);
             -webkit-backdrop-filter: blur(12px);
        }

        .hero-bg {
             background-color: #FFFFFF;
        }
        
        .cta-gradient-btn {
            background: linear-gradient(90deg, #8B5CF6, #6D28D9); /* Purple Gradient */
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.4);
        }
        .cta-gradient-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(139, 92, 246, 0.6);
        }
        
        .feature-card {
            background: #FFFFFF;
            border: 1px solid #E5E7EB; /* Gray Border */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .wave-divider {
            position: relative;
            width: 100%;
            height: 100px;
            /* Inverted wave for light theme */
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3e%3cpath fill='%23F9FAFB' fill-opacity='1' d='M0,160L48,176C96,192,192,224,288,213.3C384,203,480,149,576,133.3C672,117,768,139,864,165.3C960,192,1056,224,1152,218.7C1248,213,1344,171,1392,149.3L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3e%3c/path%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-size: cover;
            bottom: -1px; 
        }
        
        /* Scroll reveal animation */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275), transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body class="text-gray-800">

    <!-- Header -->
    <header id="header" class="header-bg sticky top-0 z-50 transition-all duration-300 border-b border-gray-200">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-indigo-600">
                    <a href="#">CampusHire</a>
                </div>
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="#" class="text-gray-600 hover:text-purple-600 transition font-medium">Home</a>
                    <a href="student/jobs.php" class="text-gray-600 hover:text-purple-600 transition font-medium">Find Jobs</a>
                    <a href="#features" class="text-gray-600 hover:text-purple-600 transition font-medium">Features</a>
                    <a href="#contact" class="text-gray-600 hover:text-purple-600 transition font-medium">Contact</a>
                </nav>
                <div class="hidden md:flex items-center space-x-4">
                     <div class="relative group">
                        <button class="cta-gradient-btn text-white px-6 py-2.5 rounded-full font-semibold flex items-center">
                            Login
                            <i data-lucide="chevron-down" class="w-4 h-4 ml-1"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg py-1 opacity-0 group-hover:opacity-100 transition-all duration-300 invisible group-hover:visible">
                            <a href="login.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600">Student Login</a>
                            <a href="student-registration.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600">Student Registration</a>
                        </div>
                    </div>
                </div>
                <div class="md:hidden">
                    <button id="mobile-menu-button">
                        <i data-lucide="menu" class="w-6 h-6 text-gray-700"></i>
                    </button>
                </div>
            </div>
        </div>
        <div id="mobile-menu" class="hidden md:hidden px-6 pb-4">
             <a href="#" class="block py-2 text-gray-700 hover:text-purple-600 font-medium">Home</a>
             <a href="student/jobs.php" class="block py-2 text-gray-700 hover:text-purple-600 font-medium">Find Jobs</a>
             <a href="#features" class="block py-2 text-gray-700 hover:text-purple-600 font-medium">Features</a>
             <a href="#contact" class="block py-2 text-gray-700 hover:text-purple-600 font-medium">Contact</a>
             <div class="border-t border-gray-200 mt-2 pt-2">
                 <a href="login.php" class="block py-2 text-gray-700 hover:bg-purple-50">Student Login</a>
                 <a href="student-registration.php" class="block py-2 text-gray-700 hover:bg-purple-50">Student Registration</a>
             </div>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="relative hero-bg pt-24 pb-32 overflow-hidden">
             <div class="container mx-auto px-6 text-center relative">
                <h1 class="text-4xl md:text-6xl font-black leading-tight mb-4 reveal">Unlock Your Dream Career.</h1>
                <p class="text-lg md:text-xl text-gray-600 mb-10 max-w-3xl mx-auto reveal" style="transition-delay: 200ms;">The ultimate launchpad for students. Connect with innovative companies, discover exclusive placements, and start building your future today.</p>
                <div class="reveal" style="transition-delay: 400ms;">
                    <a href="student/jobs.php" class="cta-gradient-btn text-white font-bold py-4 px-10 rounded-full text-lg">Find Your Opportunity</a>
                </div>
                <div class="mt-20 reveal" style="transition-delay: 600ms;">
                     <!-- Illustration -->
                     <div class="relative w-full max-w-4xl mx-auto">
                        <svg viewBox="0 0 800 300" xmlns="http://www.w3.org/2000/svg" class="w-full">
                          <defs>
                            <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="0%">
                              <stop offset="0%" style="stop-color:rgb(139,92,246);stop-opacity:1" />
                              <stop offset="100%" style="stop-color:rgb(99,102,241);stop-opacity:1" />
                            </linearGradient>
                          </defs>
                          <path d="M 50,250 C 150,100 250,100 350,250 C 450,400 550,400 650,250 C 750,100 850,100 950,250" stroke="url(#grad1)" stroke-width="5" fill="none" stroke-linecap="round"/>
                          <circle cx="50" cy="250" r="15" fill="#8B5CF6"/>
                          <circle cx="350" cy="250" r="15" fill="#6366F1"/>
                          <circle cx="650" cy="250" r="15" fill="#8B5CF6"/>
                          <circle cx="950" cy="250" r="15" fill="#6366F1"/>
                          <text x="50" y="220" font-family="Poppins" font-size="20" fill="#4B5563" text-anchor="middle">You</text>
                          <text x="350" y="220" font-family="Poppins" font-size="20" fill="#4B5563" text-anchor="middle">Dream Job</text>
                          <text x="650" y="220" font-family="Poppins" font-size="20" fill="#4B5563" text-anchor="middle">Top Company</text>
                          <text x="950" y="220" font-family="Poppins" font-size="20" fill="#4B5563" text-anchor="middle">Success</text>
                        </svg>
                     </div>
                </div>
            </div>
        </section>

        <!-- "How It Works" Section -->
        <div class="wave-divider" style="transform: rotate(180deg);"></div>
        <section class="py-20 bg-gray-50">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16 reveal">
                    <h2 class="text-3xl md:text-4xl font-bold">Your Journey to a Career</h2>
                    <p class="text-gray-600 mt-2 text-lg">Three simple steps to connect with opportunity.</p>
                </div>
                <div class="grid md:grid-cols-3 gap-10">
                    <div class="feature-card rounded-2xl p-8 text-center reveal" style="transition-delay: 100ms;">
                        <div class="bg-purple-100 text-purple-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="user-plus" class="w-10 h-10"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">1. Build Your Profile</h3>
                        <p class="text-gray-600">Craft a standout profile that highlights your skills, projects, and ambitions for recruiters to see.</p>
                    </div>
                    <div class="feature-card rounded-2xl p-8 text-center reveal" style="transition-delay: 300ms;">
                        <div class="bg-purple-100 text-purple-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="compass" class="w-10 h-10"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">2. Discover Roles</h3>
                        <p class="text-gray-600">Explore a curated list of jobs and internships from top-tier companies looking for fresh talent.</p>
                    </div>
                    <div class="feature-card rounded-2xl p-8 text-center reveal" style="transition-delay: 500ms;">
                         <div class="bg-purple-100 text-purple-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="rocket" class="w-10 h-10"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">3. Launch Your Career</h3>
                        <p class="text-gray-600">Apply with a single click, track your progress, and land the job that kickstarts your professional journey.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-24 bg-white">
            <div class="container mx-auto px-6">
                 <div class="grid md:grid-cols-2 gap-16 items-center">
                    <div class="reveal">
                        <!-- Illustration -->
                        <svg viewBox="0 0 500 400" xmlns="http://www.w3.org/2000/svg">
                          <rect x="50" y="50" width="400" height="300" rx="20" fill="#F3E8FF"/>
                          <rect x="70" y="120" width="150" height="10" rx="5" fill="#C4B5FD"/>
                          <rect x="70" y="150" width="200" height="10" rx="5" fill="#A78BFA"/>
                           <rect x="70" y="180" width="120" height="10" rx="5" fill="#C4B5FD"/>
                          <circle cx="350" cy="200" r="80" fill="#8B5CF6"/>
                          <path d="M 330 200 L 345 215 L 375 185" stroke="white" stroke-width="8" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                          <text x="150" y="90" font-family="Poppins" font-size="24" fill="#4B5563" font-weight="600">Your Dashboard</text>
                        </svg>
                    </div>
                    <div class="reveal" style="transition-delay: 200ms;">
                        <h2 class="text-3xl md:text-4xl font-bold mb-6">Everything You Need to Succeed</h2>
                        <p class="text-gray-600 mb-8">CampusHire is more than just a job board. We provide the tools and support to ensure your application stands out and you land the interview.</p>
                        <ul class="space-y-4">
                            <li class="flex items-center"><i data-lucide="shield-check" class="w-5 h-5 text-purple-600 mr-3"></i> <span>Exclusive, Verified Job Listings</span></li>
                            <li class="flex items-center"><i data-lucide="bar-chart-3" class="w-5 h-5 text-purple-600 mr-3"></i> <span>Real-time Application Status Tracking</span></li>
                            <li class="flex items-center"><i data-lucide="book-open-check" class="w-5 h-5 text-purple-600 mr-3"></i> <span>Resume Builders & Interview Prep Kits</span></li>
                        </ul>
                    </div>
                 </div>
            </div>
        </section>

        <!-- CTA Banner -->
        <section class="py-20 bg-gray-50">
            <div class="container mx-auto px-6 reveal">
                 <div class="relative rounded-2xl p-12 text-center overflow-hidden" style="background: linear-gradient(135deg, #8B5CF6 0%, #6D28D9 100%);">
                     <div class="relative">
                        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Your Next Chapter is Waiting.</h2>
                        <p class="text-purple-200 text-lg max-w-2xl mx-auto mb-8">Don't let opportunity pass you by. Create your free account today and get noticed by the world's top companies.</p>
                         <a href="student-registration.php" class="bg-white text-purple-700 font-bold py-3 px-8 rounded-full text-lg hover:bg-gray-200 transition duration-300 transform hover:scale-105 shadow-lg">Sign Up for Free</a>
                     </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer id="contact" class="bg-gray-800 pt-12">
        <div class="container mx-auto px-6 py-12">
            <div class="grid md:grid-cols-3 gap-8 text-gray-300">
                <div>
                     <h3 class="text-2xl font-bold text-white mb-4">CampusHire</h3>
                    <p class="text-gray-400">Connecting ambition with opportunity.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Home</a></li>
                        <li><a href="student/jobs.php" class="text-gray-400 hover:text-white transition">Find Jobs</a></li>
                        <li><a href="#features" class="text-gray-400 hover:text-white transition">Features</a></li>
                    </ul>
                </div>
                 <div>
                    <h3 class="text-lg font-semibold text-white mb-4">Contact</h3>
                    <p class="text-gray-400">support@campushire.com</p>
                    <div class="flex space-x-4 mt-4">
                        <a href="#" class="text-gray-400 hover:text-white"><i data-lucide="twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i data-lucide="facebook"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i data-lucide="linkedin"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-10 pt-6 text-center text-gray-500">
                <p>&copy; 2024 CampusHire. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
        
        // Header shadow on scroll
        const header = document.getElementById('header');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 10) {
                header.classList.add('shadow-md');
            } else {
                header.classList.remove('shadow-md');
            }
        });

        // Scroll reveal animation
        const revealElements = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target); // Optional: stop observing after revealed
                }
            });
        }, {
            threshold: 0.1 
        });

        revealElements.forEach(el => {
            observer.observe(el);
        });
    </script>
</body>

</html>

