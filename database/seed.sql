-- Insert admin user
INSERT INTO users (username, email, password, role)
VALUES (
    'admin',
    'admin@placement.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    'admin'
);

-- Email: admin@placement.com
-- Password: password

-- Insert companies
INSERT INTO companies (name, description, website, logo_path)
VALUES (
        'TechNova',
        'Innovative solutions in AI and machine learning.',
        'https://technova.com',
        '/logos/technova.png'
    ),
    (
        'HealthPlus',
        'Healthcare services and digital health platforms.',
        'https://healthplus.com',
        '/logos/healthplus.png'
    ),
    (
        'EcoBuild',
        'Sustainable architecture and eco-friendly construction.',
        'https://ecobuild.com',
        '/logos/ecobuild.png'
    ),
    (
        'FinSmart',
        'Next-gen financial products and fintech solutions.',
        'https://finsmart.com',
        '/logos/finsmart.png'
    ),
    (
        'EduCore',
        'Online education platforms and content delivery.',
        'https://educore.com',
        '/logos/educore.png'
    );
-- Insert jobs for TechNova
INSERT INTO jobs (
        company_id,
        title,
        description,
        allowed_streams,
        salary,
        location,
        last_date_to_apply
    )
VALUES (
        1,
        'Software Engineer',
        'Develop scalable AI applications.',
        'CS,IT',
        '₹10 LPA',
        'Bangalore',
        '2025-08-15'
    ),
    (
        1,
        'Data Scientist',
        'Work on ML models and data pipelines.',
        'CS,IT,Math',
        '₹12 LPA',
        'Hyderabad',
        '2025-08-20'
    ),
    (
        1,
        'Product Manager',
        'Lead AI product initiatives.',
        'MBA',
        '₹15 LPA',
        'Remote',
        '2025-08-10'
    ),
    (
        1,
        'QA Engineer',
        'Ensure software quality and reliability.',
        'CS,IT',
        '₹8 LPA',
        'Pune',
        '2025-08-25'
    ),
    (
        1,
        'UI/UX Designer',
        'Design intuitive user interfaces.',
        'Design,CS',
        '₹9 LPA',
        'Chennai',
        '2025-09-01'
    );
-- Insert jobs for HealthPlus
INSERT INTO jobs (
        company_id,
        title,
        description,
        allowed_streams,
        salary,
        location,
        last_date_to_apply
    )
VALUES (
        2,
        'Healthcare Analyst',
        'Analyze patient data and trends.',
        'Bio,Stats,CS',
        '₹7 LPA',
        'Delhi',
        '2025-08-30'
    ),
    (
        2,
        'Mobile App Developer',
        'Develop health monitoring apps.',
        'CS,IT',
        '₹8 LPA',
        'Bangalore',
        '2025-08-18'
    ),
    (
        2,
        'Digital Marketing Specialist',
        'Promote healthcare platforms.',
        'MBA,Marketing',
        '₹6 LPA',
        'Mumbai',
        '2025-09-05'
    ),
    (
        2,
        'DevOps Engineer',
        'Maintain healthcare systems uptime.',
        'CS,IT',
        '₹10 LPA',
        'Kolkata',
        '2025-08-22'
    ),
    (
        2,
        'Clinical Data Manager',
        'Manage clinical trial data.',
        'Bio,Stats',
        '₹9 LPA',
        'Pune',
        '2025-09-10'
    );
-- Insert jobs for EcoBuild
INSERT INTO jobs (
        company_id,
        title,
        description,
        allowed_streams,
        salary,
        location,
        last_date_to_apply
    )
VALUES (
        3,
        'Architect',
        'Design eco-friendly buildings.',
        'Arch,Civil',
        '₹12 LPA',
        'Bangalore',
        '2025-08-28'
    ),
    (
        3,
        'Site Engineer',
        'Oversee construction sites.',
        'Civil,Mech',
        '₹10 LPA',
        'Delhi',
        '2025-08-18'
    ),
    (
        3,
        'Sustainability Consultant',
        'Advise on green practices.',
        'Env,Arch',
        '₹11 LPA',
        'Mumbai',
        '2025-08-30'
    ),
    (
        3,
        'Project Manager',
        'Lead construction projects.',
        'MBA,Civil',
        '₹14 LPA',
        'Chennai',
        '2025-09-07'
    ),
    (
        3,
        'CAD Designer',
        'Create architectural blueprints.',
        'Arch,Design',
        '₹8 LPA',
        'Hyderabad',
        '2025-09-12'
    );
-- Insert jobs for FinSmart
INSERT INTO jobs (
        company_id,
        title,
        description,
        allowed_streams,
        salary,
        location,
        last_date_to_apply
    )
VALUES (
        4,
        'Backend Developer',
        'Develop fintech APIs.',
        'CS,IT',
        '₹10 LPA',
        'Bangalore',
        '2025-08-25'
    ),
    (
        4,
        'Financial Analyst',
        'Analyze investment data.',
        'Commerce,MBA',
        '₹9 LPA',
        'Delhi',
        '2025-08-15'
    ),
    (
        4,
        'Risk Manager',
        'Manage financial risks.',
        'Finance,MBA',
        '₹12 LPA',
        'Mumbai',
        '2025-09-01'
    ),
    (
        4,
        'UI Developer',
        'Build responsive dashboards.',
        'CS,Design',
        '₹8 LPA',
        'Kolkata',
        '2025-08-20'
    ),
    (
        4,
        'Blockchain Engineer',
        'Develop smart contracts.',
        'CS,IT',
        '₹14 LPA',
        'Remote',
        '2025-09-10'
    );
-- Insert jobs for EduCore
INSERT INTO jobs (
        company_id,
        title,
        description,
        allowed_streams,
        salary,
        location,
        last_date_to_apply
    )
VALUES (
        5,
        'Content Developer',
        'Create online learning materials.',
        'Edu,English',
        '₹6 LPA',
        'Chennai',
        '2025-08-22'
    ),
    (
        5,
        'Full Stack Developer',
        'Develop e-learning platforms.',
        'CS,IT',
        '₹10 LPA',
        'Bangalore',
        '2025-08-30'
    ),
    (
        5,
        'Instructional Designer',
        'Design course curricula.',
        'Edu,Design',
        '₹7 LPA',
        'Pune',
        '2025-09-05'
    ),
    (
        5,
        'SEO Specialist',
        'Improve platform visibility.',
        'Marketing',
        '₹5 LPA',
        'Delhi',
        '2025-08-18'
    ),
    (
        5,
        'Data Analyst',
        'Analyze learner engagement.',
        'Stats,CS',
        '₹8 LPA',
        'Hyderabad',
        '2025-09-12'
    );