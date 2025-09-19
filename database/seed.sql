-- Insert admin user
INSERT INTO users (username, email, password, role)
VALUES (
        'admin',
        'admin@placement.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        -- password
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
INSERT INTO users (
        id,
        email,
        password,
        role,
        created_at
    )
VALUES (
        2,
        'user2@mail.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'student',
        NOW()
    ),
    (
        3,
        'user3@mail.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'student',
        NOW()
    ),
    (
        4,
        'user4@mail.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'student',
        NOW()
    ),
    (
        5,
        'user5@mail.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'student',
        NOW()
    ),
    (
        6,
        'user6@mail.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'student',
        NOW()
    ),
    (
        7,
        'user7@mail.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'student',
        NOW()
    ),
    (
        8,
        'user8@mail.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'student',
        NOW()
    ),
    (
        9,
        'user9@mail.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'student',
        NOW()
    ),
    (
        10,
        'user10@mail.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'student',
        NOW()
    ),
    (
        11,
        'user11@mail.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'student',
        NOW()
    ),
    (
        12,
        'user12@mail.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'student',
        NOW()
    ),
    (
        13,
        'user13@mail.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'student',
        NOW()
    ),
    (
        14,
        'user14@mail.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'student',
        NOW()
    ),
    (
        15,
        'user15@mail.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'student',
        NOW()
    ),
    (
        16,
        'user16@mail.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'student',
        NOW()
    ),
    (
        17,
        'user17@mail.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'student',
        NOW()
    ),
    (
        18,
        'user18@mail.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'student',
        NOW()
    ),
    (
        19,
        'user19@mail.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'student',
        NOW()
    ),
    (
        20,
        'user20@mail.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'student',
        NOW()
    ),
    (
        21,
        'user21@mail.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'student',
        NOW()
    ),
    (
        22,
        'user22@mail.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'student',
        NOW()
    ),
    (
        23,
        'user23@mail.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'student',
        NOW()
    ),
    (
        24,
        'user24@mail.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'student',
        NOW()
    ),
    (
        25,
        'user25@mail.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'student',
        NOW()
    ),
    (
        26,
        'user1@mail.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'student',
        NOW()
    );
INSERT INTO students (
        user_id,
        prn,
        name,
        phone_number,
        dob,
        id_card,
        resume_path,
        status
    )
VALUES (
        2,
        'PRN20230002',
        'Jane Smith',
        '9876543211',
        '1999-08-20',
        'id_card_2.jpg',
        'resume_2.pdf',
        'pending'
    ),
    (
        3,
        'PRN20230003',
        'Michael Johnson',
        '9876543212',
        '2001-01-10',
        'id_card_3.jpg',
        'resume_3.pdf',
        'rejected'
    ),
    (
        4,
        'PRN20230004',
        'Emily Davis',
        '9876543213',
        '2000-03-25',
        'id_card_4.jpg',
        'resume_4.pdf',
        'approved'
    ),
    (
        5,
        'PRN20230005',
        'Chris Brown',
        '9876543214',
        '1998-12-30',
        'id_card_5.jpg',
        'resume_5.pdf',
        'pending'
    ),
    (
        6,
        'PRN20230006',
        'Sarah Wilson',
        '9876543215',
        '2002-07-18',
        'id_card_6.jpg',
        'resume_6.pdf',
        'approved'
    ),
    (
        7,
        'PRN20230007',
        'David Lee',
        '9876543216',
        '1999-11-05',
        'id_card_7.jpg',
        'resume_7.pdf',
        'rejected'
    ),
    (
        8,
        'PRN20230008',
        'Sophia Martinez',
        '9876543217',
        '2001-04-12',
        'id_card_8.jpg',
        'resume_8.pdf',
        'approved'
    ),
    (
        9,
        'PRN20230009',
        'James Anderson',
        '9876543218',
        '2000-09-22',
        'id_card_9.jpg',
        'resume_9.pdf',
        'pending'
    ),
    (
        10,
        'PRN20230010',
        'Olivia Thomas',
        '9876543219',
        '1998-06-14',
        'id_card_10.jpg',
        'resume_10.pdf',
        'approved'
    ),
    (
        11,
        'PRN20230011',
        'Liam Garcia',
        '9876543220',
        '2001-02-28',
        'id_card_11.jpg',
        'resume_11.pdf',
        'rejected'
    ),
    (
        12,
        'PRN20230012',
        'Emma Rodriguez',
        '9876543221',
        '1999-10-08',
        'id_card_12.jpg',
        'resume_12.pdf',
        'approved'
    ),
    (
        13,
        'PRN20230013',
        'Noah Hernandez',
        '9876543222',
        '2000-01-19',
        'id_card_13.jpg',
        'resume_13.pdf',
        'pending'
    ),
    (
        14,
        'PRN20230014',
        'Ava Lopez',
        '9876543223',
        '2002-03-03',
        'id_card_14.jpg',
        'resume_14.pdf',
        'approved'
    ),
    (
        15,
        'PRN20230015',
        'William Gonzalez',
        '9876543224',
        '1998-07-27',
        'id_card_15.jpg',
        'resume_15.pdf',
        'rejected'
    ),
    (
        16,
        'PRN20230016',
        'Isabella Perez',
        '9876543225',
        '2001-05-09',
        'id_card_16.jpg',
        'resume_16.pdf',
        'approved'
    ),
    (
        17,
        'PRN20230017',
        'Elijah White',
        '9876543226',
        '1999-12-17',
        'id_card_17.jpg',
        'resume_17.pdf',
        'pending'
    ),
    (
        18,
        'PRN20230018',
        'Mia Harris',
        '9876543227',
        '2000-08-30',
        'id_card_18.jpg',
        'resume_18.pdf',
        'approved'
    ),
    (
        19,
        'PRN20230019',
        'Benjamin Clark',
        '9876543228',
        '2002-01-15',
        'id_card_19.jpg',
        'resume_19.pdf',
        'rejected'
    ),
    (
        20,
        'PRN20230020',
        'Charlotte Lewis',
        '9876543229',
        '1998-11-11',
        'id_card_20.jpg',
        'resume_20.pdf',
        'approved'
    ),
    (
        21,
        'PRN20230021',
        'Lucas Walker',
        '9876543230',
        '2001-06-21',
        'id_card_21.jpg',
        'resume_21.pdf',
        'pending'
    ),
    (
        22,
        'PRN20230022',
        'Amelia Hall',
        '9876543231',
        '1999-09-13',
        'id_card_22.jpg',
        'resume_22.pdf',
        'approved'
    ),
    (
        23,
        'PRN20230023',
        'Ethan Allen',
        '9876543232',
        '2000-04-07',
        'id_card_23.jpg',
        'resume_23.pdf',
        'rejected'
    ),
    (
        24,
        'PRN20230024',
        'Harper Young',
        '9876543233',
        '2002-02-20',
        'id_card_24.jpg',
        'resume_24.pdf',
        'approved'
    ),
    (
        25,
        'PRN20230025',
        'Jack King',
        '9876543234',
        '1998-10-01',
        'id_card_25.jpg',
        'resume_25.pdf',
        'pending'
    ),
    (
        26,
        'PRN20230001',
        'John Doe',
        '9876543210',
        '2000-05-15',
        'id_card_1.jpg',
        'resume_1.pdf',
        'approved'
    );