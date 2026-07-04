-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql110.infinityfree.com
-- Generation Time: Jul 02, 2026 at 10:04 PM
-- Server version: 11.4.12-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_42128139_internhub_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$r8UVPNsxEL.bc.JPJR80DO5r8r8001We2tDojMyvAH0IkRJ2.tqwK', '2026-04-07 19:46:08');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `listing_id` int(11) NOT NULL,
  `cover_letter` text DEFAULT NULL,
  `status` enum('pending','reviewed','shortlisted','rejected','placed') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `company_notes` text DEFAULT NULL,
  `cv_path` varchar(255) DEFAULT NULL,
  `portfolio_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `student_id`, `listing_id`, `cover_letter`, `status`, `created_at`, `company_notes`, `cv_path`, `portfolio_link`) VALUES
(2, 1, 1, 'Dear Hiring Manager,\r\nI am writing to express my interest in joining the MTN Uganda technology division. With hands-on experience in building robust web applications and a strong drive for digital innovation, I am eager to contribute to your team.In my previous roles,\r\n I have developed responsive, customer-facing interfaces and intuitive internal tools that streamline business operations. Working alongside senior developers, I have honed my skills in [List 2-3 of your key technical skills, e.g., React, JavaScript, or backend APIs] and learned how to build scalable products that enhance user experience.I deeply admire MTN’s mission to drive digital and financial inclusion across Uganda. I would welcome the opportunity to bring my technical background and collaborative problem-solving skills to your team to help build the next generation of MTN’s digital products.\r\nThank you for your time and consideration. I look forward to the possibility of discussing how my skills align with the goals of MTN Uganda.Sincerely,\r\n[Your Name]\r\n[Your Phone Number]\r\n[Your Email]\r\n[Link to your Portfolio or GitHub]', 'pending', '2026-05-22 21:49:18', NULL, NULL, NULL),
(3, 1003, 8, 'UHGQeugyuwegy8ewgyugfu', 'pending', '2026-05-23 09:14:36', NULL, NULL, NULL),
(4, 1004, 8, '', 'pending', '2026-05-23 09:24:21', NULL, NULL, NULL),
(5, 1, 8, 'Dear hiring manager \r\n\r\nI am applying to this because of this and that', 'pending', '2026-06-08 07:11:11', NULL, 'uploads/cvs/1_1780927871.docx', ''),
(6, 1, 17, 'Dear Hiring Team,\r\nI am writing to express my strong interest in the Cybersecurity Internship at NITA-U. As an active [Your Degree Name] student at [Your University], I am passionate about digital transformation and safeguarding government digital infrastructures.\r\nMy academic background has equipped me with a solid foundation in network security, vulnerability management, and ethical hacking. During my coursework, I gained practical experience using tools like [e.g., Wireshark, Kali Linux, or Python for scripting] and learned how to identify, analyze, and mitigate security threats.\r\nI deeply admire NITA-U’s role in securing Uganda\'s digital future, particularly the work done by the CERT-UG team and the Personal Data Protection Office. I am eager to bring my enthusiasm, technical skills, and commitment to continuous learning to your team, where I hope to gain hands-on experience in real-world incident response and compliance.\r\nI have attached my academic transcripts, curriculum vitae, and the completed application form for your review. I would welcome the opportunity to discuss how my academic background aligns with NITA-U\'s mission. Thank you for your time and consideration.\r\nSincerely,', 'placed', '2026-06-10 21:39:31', NULL, 'uploads/cvs/1_1781152771.docx', ''),
(7, 1007, 17, 'Hi, application for intern.[Test Mode]', 'placed', '2026-06-11 01:21:12', NULL, NULL, ''),
(8, 1003, 17, 'dear sir requesting for a placement', 'placed', '2026-06-11 01:42:00', NULL, 'uploads/cvs/1003_1781167319.docx', ''),
(9, 1008, 17, '', 'placed', '2026-06-11 02:35:04', NULL, 'uploads/cvs/1008_1781170503.docx', '');

-- --------------------------------------------------------

--
-- Table structure for table `internships`
--

CREATE TABLE `internships` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `company` varchar(100) NOT NULL,
  `logo` varchar(5) NOT NULL,
  `logo_color` varchar(10) NOT NULL,
  `location` varchar(100) NOT NULL,
  `field` varchar(50) NOT NULL,
  `duration` int(11) NOT NULL,
  `paid` varchar(20) NOT NULL,
  `stipend` varchar(100) NOT NULL,
  `deadline` date NOT NULL,
  `slots` int(11) NOT NULL DEFAULT 1,
  `description` text NOT NULL,
  `responsibilities` text NOT NULL,
  `requirements` text NOT NULL,
  `contact` varchar(100) NOT NULL,
  `tags` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `posted_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `internships`
--

INSERT INTO `internships` (`id`, `title`, `company`, `logo`, `logo_color`, `location`, `field`, `duration`, `paid`, `stipend`, `deadline`, `slots`, `description`, `responsibilities`, `requirements`, `contact`, `tags`, `status`, `posted_by`, `created_at`) VALUES
(1, 'Software Development Intern', 'MTN Uganda', 'MT', '#F5A623', 'Kampala CBD', 'Web Dev', 3, 'paid', 'UGX 250,000 / month', '2026-05-30', 4, 'Join MTN Uganda\'s technology division and work alongside senior developers building internal web tools and customer-facing digital products.', 'Develop and maintain web applications|Participate in daily stand-up meetings|Write clean, documented code|Collaborate with the QA team|Present progress updates to team lead', 'Diploma or Degree in CS or IT|Knowledge of HTML, CSS, JavaScript|Basic understanding of SQL|Good communication skills|Available full-time', 'careers@mtn.co.ug', 'Web Dev,JavaScript,Paid,3 months', 'approved', NULL, '2026-04-06 19:16:11'),
(2, 'Data Analysis Intern', 'DFCU Bank', 'DF', '#64B5F6', 'Kampala', 'Data', 6, 'paid', 'UGX 300,000 / month', '2025-04-15', 2, 'DFCU Bank is looking for an analytically minded intern to support the Business Intelligence team with data collection, cleaning and analysis.', 'Assist in data collection and cleaning|Generate weekly dashboards using Excel and Power BI|Support analytics team in reports|Conduct basic statistical analysis|Document data processes', 'Student in CS, Statistics or Information Systems|Proficiency in Microsoft Excel|Basic SQL knowledge is an advantage|Attention to detail|Ability to handle confidential data', 'humanresources@dfcubank.com', 'Data,Excel,Power BI,Paid,6 months', 'approved', NULL, '2026-04-06 19:16:11'),
(3, 'IT Support Intern', 'Airtel Uganda', 'AI', '#FF8A65', 'Kololo, Kampala', 'IT Support', 2, 'stipend', 'Transport + Lunch allowance', '2025-04-01', 3, 'Support Airtel Uganda\'s internal IT helpdesk team in providing technical assistance to staff across multiple offices.', 'Resolve IT helpdesk tickets within SLA|Install and configure computers and peripherals|Support network troubleshooting|Maintain hardware and software inventory|Escalate complex issues', 'Diploma in IT, CS or Telecommunications|Basic hardware and software troubleshooting|Familiarity with Windows OS|Customer-service oriented|Ability to work in fast-paced environment', 'ug.itrecruitment@airtel.com', 'IT Support,Hardware,Networking,Stipend', 'approved', NULL, '2026-04-06 19:16:11'),
(4, 'Cybersecurity Intern', 'Stanbic Bank Uganda', 'SB', '#4DB6AC', 'Crested Towers, Kampala', 'Cybersecurity', 3, 'paid', 'UGX 350,000 / month', '2025-06-10', 2, 'Join Stanbic Bank\'s Information Security team and gain real-world exposure to cybersecurity operations within Uganda\'s leading financial institution.', 'Monitor security dashboards|Assist in vulnerability assessments|Support security awareness training|Review access control logs|Research emerging cyber threats', 'Student in IT, CS or Cybersecurity|Passion for information security|Understanding of networking fundamentals|Familiarity with Linux is an advantage|High integrity and confidentiality', 'securityinternship@stanbic.co.ug', 'Cybersecurity,Networking,Linux,Paid', 'approved', NULL, '2026-04-06 19:16:11'),
(5, 'Mobile App Development Intern', 'SafeBoda Technology', 'SF', '#81C784', 'Nakawa, Kampala', 'Mobile', 6, 'paid', 'UGX 400,000 / month', '2025-07-01', 2, 'SafeBoda is one of Uganda\'s leading tech startups. Join our mobile development team and contribute to features used daily by thousands across Kampala.', 'Develop features for rider and customer apps|Write unit tests|Participate in design reviews|Fix reported bugs|Collaborate with backend engineers on API integration', 'CS or Software Engineering student|Experience with React Native or Flutter|Understanding of RESTful APIs|GitHub account with at least one project|Passion for African tech products', 'talent@safeboda.com', 'Mobile,React Native,Flutter,Paid,6 months', 'approved', NULL, '2026-04-06 19:16:11'),
(6, 'Software Dev INtern', 'MTN Uganda', 'MU', '#AED581', 'Kampala', 'Web Dev', 1, 'paid', 'Paid — amount on request', '2026-05-05', 2, 'Designing and developing suitable websites', 'Punctuality|Time Keeping|Software Development', 'National ID|School ID', 'botuura@gmail.com', 'Web Dev,Telecommunications,Paid,1 months', 'approved', NULL, '2026-04-06 20:40:22'),
(8, 'Frontend Developer Intern', 'Yo! Uganda Limited', 'YO', '#E91E63', 'Ntinda, Kampala', 'Web Dev', 3, 'paid', 'UGX 280,000 / month', '2026-08-15', 2, 'Yo! Uganda is a pioneer in mobile money and digital payments. Join our product team to build and improve the web interfaces used by thousands of customers across Uganda.', 'Build and maintain responsive web pages using HTML, CSS and JavaScript|Implement UI components from design mockups|Collaborate with the backend team on API integration|Write unit tests for frontend components|Participate in weekly product review sessions', 'Diploma or degree student in Computer Science or IT|Proficiency in HTML5, CSS3, and JavaScript|Familiarity with responsive design principles|A portfolio or GitHub profile showing personal projects is an advantage|Strong attention to detail', 'careers@yo.co.ug', 'Web Dev,HTML,CSS,JavaScript,Paid', 'approved', NULL, '2026-04-12 18:35:01'),
(9, 'Network Administration Intern', 'Liquid Telecom Uganda', 'LT', '#00BCD4', 'Bugolobi, Kampala', 'Networking', 6, 'paid', 'UGX 320,000 / month', '2025-07-30', 3, 'Liquid Telecom is one of East Africa\'s leading fibre network providers. Join our Network Operations Centre and gain hands-on experience managing enterprise-grade network infrastructure.', 'Monitor network performance dashboards and flag anomalies|Assist in configuring routers, switches and firewalls|Support the rollout of new fibre infrastructure across Kampala|Maintain network documentation and topology diagrams|Participate in on-call rotations with the senior NOC team', 'Student in Telecommunications, IT or Computer Science|Understanding of TCP/IP, DNS, DHCP and routing protocols|Cisco CCNA certification or coursework is a strong advantage|Ability to work in a structured 24/7 operations environment|Valid ID and willingness to travel within Kampala', 'noc-recruitment@liquid.tech', 'Networking,Cisco,Telecom,Paid,6 months', 'approved', NULL, '2026-04-12 18:35:01'),
(10, 'Data Science Intern', 'Makerere University AI Lab', 'MU', '#9C27B0', 'Makerere, Kampala', 'Data', 3, 'stipend', 'Research stipend provided', '2025-05-25', 2, 'The Makerere University AI Lab is one of East Africa\'s leading artificial intelligence research centres. Join our data team to work on real-world datasets that address African development challenges.', 'Clean and preprocess large datasets from agricultural and health sectors|Build and evaluate machine learning models using Python and scikit-learn|Assist researchers in preparing data visualisations for publications|Document data pipelines and model evaluation results|Attend weekly lab seminars and present progress updates', 'Student in Computer Science, Data Science or Statistics|Proficiency in Python (pandas, numpy, matplotlib)|Basic understanding of machine learning concepts|Strong analytical and problem-solving skills|Academic transcript showing strong performance in quantitative subjects', 'ailab@mak.ac.ug', 'Data,Python,Machine Learning,Stipend', 'approved', NULL, '2026-04-12 18:35:01'),
(11, 'Mobile Developer Intern', 'Fenix International Uganda', 'FI', '#FF6F00', 'Kampala CBD', 'Mobile', 6, 'paid', 'UGX 380,000 / month', '2025-08-01', 2, 'Fenix International brings clean energy and financial services to off-grid communities across Uganda. Join our mobile engineering team to build products that directly improve millions of lives.', 'Develop and maintain features in the Fenix mobile application|Write clean, well-tested code using React Native|Fix reported bugs and improve application performance and load times|Collaborate with designers on implementing new UI screens|Participate in sprint planning and retrospective meetings', 'Computer Science or Software Engineering student|Experience with React Native, Flutter or similar mobile framework|Familiarity with RESTful APIs and JSON data|A GitHub account with at least one personal or academic project|Passion for technology with social impact', 'talent@fenixintl.com', 'Mobile,React Native,Paid,6 months', 'approved', NULL, '2026-04-12 18:35:01'),
(12, 'Cybersecurity Analyst Intern', 'NITA-Uganda', 'NI', '#37474F', 'Nakasero, Kampala', 'Cybersecurity', 3, 'unpaid', 'Certificate of completion + reference letter', '2025-05-10', 5, 'The National Information Technology Authority of Uganda (NITA-U) is the government body responsible for ICT infrastructure and cybersecurity across Uganda. This internship offers unparalleled exposure to national-level digital security operations.', 'Support the national CERT (Computer Emergency Response Team) in monitoring cyber threats|Assist in conducting vulnerability assessments on government systems|Help develop and deliver cybersecurity awareness training materials|Review and update incident response documentation|Research emerging cybersecurity threats relevant to Uganda\'s digital infrastructure', 'Ugandan citizen enrolled in IT, Computer Science or Cybersecurity|Understanding of information security frameworks (ISO 27001, NIST)|Basic knowledge of penetration testing concepts|High integrity and ability to handle sensitive government information|Letter of introduction from UICTI required', 'internships@nita.go.ug', 'Cybersecurity,Government,CERT,Unpaid', 'approved', NULL, '2026-04-12 18:35:01'),
(13, 'UI/UX Research Intern', 'Jumia Uganda', 'JU', '#F97316', 'Nakawa, Kampala', 'Design', 3, 'paid', 'UGX 250,000 / month', '2025-06-20', 1, 'Jumia is Africa\'s leading e-commerce platform. Our Uganda team is looking for a curious, user-focused design intern to help improve the shopping experience for millions of Ugandan customers.', 'Conduct user research sessions including interviews and usability tests|Analyse user behaviour data from the Jumia platform|Create wireframes and low-fidelity prototypes in Figma|Present research findings and design recommendations to the product team|Contribute to the Jumia Uganda design component library', 'Student in IT, Computer Science, Multimedia Design or related field|Basic proficiency in Figma or Adobe XD|Strong interest in user research and human-centred design|Good presentation and communication skills|Portfolio showing at least 2 design projects (academic work accepted)', 'ug.design@jumia.com', 'Design,Figma,UX Research,Paid', 'approved', NULL, '2026-04-12 18:35:01'),
(15, 'Software QA Intern', 'PostBank Uganda', 'PB', '#1B5E20', 'Kampala CBD', 'IT Support', 3, 'stipend', 'UGX 200,000 / month', '2025-06-01', 3, 'PostBank Uganda is one of the country\'s most trusted financial institutions. Join our digital banking team as a Quality Assurance intern and help ensure our mobile and web banking products work flawlessly for customers.', 'Write and execute manual test cases for mobile banking features|Report and track defects using Jira bug tracking system|Perform regression testing after each development sprint|Assist the QA lead in preparing test plans and test reports|Participate in UAT sessions with business stakeholders', 'Diploma or degree student in Computer Science, IT or Software Engineering|Understanding of software testing principles and methodologies|Attention to detail and strong documentation skills|Familiarity with mobile applications and web browsers|Ability to work methodically under deadline pressure', 'digital.recruitment@postbank.co.ug', 'QA Testing,Jira,Banking,Stipend', 'approved', NULL, '2026-04-12 18:35:01'),
(16, 'IT Support Intern', 'Warid Uganda', 'WU', '#CE93D8', 'Kampala', 'IT Support', 2, 'unpaid', 'Unpaid / Voluntary', '2026-05-26', 5, 'Warid Uganda is one of the country\'s most trusted telecom institutions. Join our digital telecom team as a Quality Assurance intern and help ensure our mobile and web telecommunications products work flawlessly for customers.', 'Write and execute manual test cases for mobile banking features|Report and track defects using Jira bug tracking system|Perform regression testing after each development sprint|Assist the QA lead in preparing test plans and test reports|Participate in UAT sessions with business stakeholders', 'Diploma or degree student in Computer Science, IT or Software Engineering|Understanding of software testing principles and methodologies|Attention to detail and strong documentation skills|Familiarity with mobile applications and web browsers|Ability to work methodically under deadline pressure', 'jhr@gmail.com', 'IT Support,Telecommunications,Unpaid,2 months', 'pending', 2, '2026-04-12 19:30:22'),
(17, 'Cyber security intern', 'Nita-Ug', 'NI', '#F5A623', 'Kampala Central', 'Cybersecurity', 3, 'unpaid', 'Unpaid / Voluntary', '2026-08-10', 1, 'The National Information Technology Authority of Uganda (NITA-U) is the government body responsible for ICT infrastructure and cybersecurity across Uganda. This internship offers unparalleled exposure to national-level digital security operations.', '•	Support the national CERT (Computer Emergency Response Team) in monitoring cyber threats|•	Assist in conducting vulnerability assessments on government systems|•	Help develop and deliver cybersecurity awareness training materials|•	Review and update incident response documentation|•	Research emerging cybersecurity threats relevant to Uganda\'s digital infrastructure', '•	Ugandan citizen enrolled in IT, Computer Science or Cybersecurity|•	Understanding of information security frameworks (ISO 27001, NIST)|•	Basic knowledge of penetration testing concepts|•	High integrity and ability to handle sensitive government information|•	Letter of introduction from UICT required', 'hrmatovu@gmail.com', 'Cybersecurity,Unpaid,3 months', 'approved', 1006, '2026-06-11 04:34:15');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `reference` varchar(100) DEFAULT NULL,
  `amount` decimal(12,2) DEFAULT NULL,
  `type` enum('listing','readiness') DEFAULT NULL,
  `status` enum('pending','confirmed','failed') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `placements`
--

CREATE TABLE `placements` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `internship_id` int(11) NOT NULL,
  `company_confirmed` tinyint(1) DEFAULT 0,
  `admin_confirmed` tinyint(1) DEFAULT 0,
  `confirmation_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `placements`
--

INSERT INTO `placements` (`id`, `student_id`, `internship_id`, `company_confirmed`, `admin_confirmed`, `confirmation_date`, `created_at`) VALUES
(2, 1, 1, 1, 1, '2026-06-11 00:08:01', '2026-06-11 07:07:40'),
(3, 2147483647, 17, 1, 0, NULL, '2026-06-11 08:33:27'),
(4, 2147483647, 17, 1, 0, NULL, '2026-06-11 09:19:20'),
(5, 2147483647, 17, 1, 0, NULL, '2026-06-11 09:42:28');

-- --------------------------------------------------------

--
-- Table structure for table `readiness_enrollments`
--

CREATE TABLE `readiness_enrollments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tier` varchar(20) DEFAULT 'foundation',
  `paid_amount` int(11) DEFAULT 0,
  `payment_ref` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `certificate_code` varchar(50) DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `readiness_enrollments`
--

INSERT INTO `readiness_enrollments` (`id`, `user_id`, `tier`, `paid_amount`, `payment_ref`, `status`, `enrolled_at`, `certificate_code`, `completed_at`, `created_at`, `updated_at`) VALUES
(2, 1, 'foundation', 0, 'DEMO123', 'completed', '2026-05-22 19:38:35', 'DIP-B9323F', '2026-05-22 21:38:35', '2026-05-22 19:38:35', NULL),
(3, 1003, 'foundation', 0, 'DEMO123', 'completed', '2026-05-23 06:13:02', 'DIP-E1F78F', '2026-05-23 08:13:02', '2026-05-23 06:13:02', NULL),
(4, 1007, 'professional', 0, 'DEMO123', 'completed', '2026-06-11 11:44:53', 'DIP-60F327', '2026-06-11 07:44:54', '2026-06-11 11:44:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','company','admin') NOT NULL DEFAULT 'student',
  `student_id` varchar(20) DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL,
  `study_year` varchar(10) DEFAULT NULL,
  `company_name` varchar(150) DEFAULT NULL,
  `industry` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `readiness_certified` tinyint(1) DEFAULT 0,
  `readiness_tier` varchar(50) DEFAULT NULL,
  `readiness_certificate_code` varchar(50) DEFAULT NULL,
  `total_placements` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `role`, `student_id`, `course`, `study_year`, `company_name`, `industry`, `phone`, `created_at`, `readiness_certified`, `readiness_tier`, `readiness_certificate_code`, `total_placements`) VALUES
(1, 'brian oneka', '2401901918@stu.ac.ug', '$2y$10$8an.DQZkOdxwHzqjL.uj2u0H4fxIPqloIft7w8GukJf6lLzXyqFO.', 'student', '2401901918', 'Computer Science', 'Year 2', NULL, NULL, NULL, '2026-04-09 09:14:44', 1, 'foundation', 'DIP-B9323F', 1),
(2, 'john mukasa', 'mhr@warid.co.ug', '$2y$10$F/EodLSUtEaT86HOQhG9Bu6fbPLHo2BypFHcUN0UiIVLIGqdqzFtG', 'company', NULL, NULL, NULL, 'Warid Telecom', 'Telecommunications', '0700255365', '2026-04-09 09:31:30', 0, NULL, NULL, 0),
(3, 'Mikenga Adonia', 'adonia@gmail.com', '$2y$10$9FKkJt.UwoGKFB.T9soar.N5ESmMx09tVovBA.sDYxyX2MZyE4HY6', 'company', NULL, NULL, NULL, 'HP Lite', 'Software / Tech', '0708781230', '2026-04-14 11:09:58', 0, NULL, NULL, 0),
(4, 'System Admin', 'admin@internhub.ug', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-22 11:51:21', 0, NULL, NULL, 0),
(999, 'System Administrator', 'admin@internhub.com', '$2y$10$Nxbabz/7SKr5t3vcGk5t8uTPUdYthfxmsyO7DrT49XXAjpqV74sPC', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-22 12:58:38', 0, NULL, NULL, 0),
(1003, 'MUSIKA OWEN', '2401902020@stu.ac.ug', '$2y$10$oZfFIkkKl9KITF6ve1gtje/WWzzkyHoaWRuvGcCjhVm.ks/SdXezi', 'student', '2401902020', 'Computer Science', 'Year 3', NULL, NULL, NULL, '2026-05-23 06:10:39', 1, 'foundation', 'DIP-E1F78F', 0),
(1004, 'Naika Cornellious', '2401900958@stu.ac.ug', '$2y$10$ZkI8k5ukNzhDHHg/zGi6Yem0pEAH4.a0vloMeXdDgTNw2ZvmU0Cjy', 'student', '2401900958', 'Computer Science', 'Year 2', NULL, NULL, NULL, '2026-05-23 06:23:08', 0, NULL, NULL, 0),
(1005, 'Mukisa Mark', 'mukisa@ugatel.com', '$2y$10$vzSm0mOiKuhrMycYZqxe8eYO9PjiYdKFHVM2PswNqo9rYhmKQ0m.O', 'company', NULL, NULL, NULL, 'Ugatel Ltd', 'Telecommunications', '0700355365', '2026-06-08 14:33:56', 0, NULL, NULL, 0),
(1006, 'Hr Matovu', 'hrmatovu@gmail.com', '$2y$10$8y/eKYC0.LtBwPOOU08gi.v6KdR.7JJW2ZysDDfKSY0qT4bCh9v4S', 'company', NULL, NULL, NULL, '', 'NGO / Non-Profit', '0708781230', '2026-06-11 04:11:05', 0, NULL, NULL, 0),
(1007, 'Karl Peter', '2401900229@stu.ac.ug', '$2y$10$KQ3.CIb411/fe9nCCrW84.sxDYNWXYP1gXGHb20fnZNO6mY/aT3xK', 'student', '2401900229', 'Computer Science', 'Year 1', NULL, NULL, NULL, '2026-06-11 08:18:22', 1, 'professional', 'DIP-60F327', 0),
(1008, 'AYO OLIVE OLWO', '2401901719@stu.ac.ug', '$2y$10$eokPHENBldLx3iV2EBOSlO4BC6nImN84JZs1nxciBEHZf8MHP8Y6.', 'student', '2401901719', 'Computer Science', 'Year 2', NULL, NULL, NULL, '2026-06-11 09:31:05', 0, NULL, NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `listing_id` (`listing_id`);

--
-- Indexes for table `internships`
--
ALTER TABLE `internships`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reference` (`reference`);

--
-- Indexes for table `placements`
--
ALTER TABLE `placements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `internship_id` (`internship_id`);

--
-- Indexes for table `readiness_enrollments`
--
ALTER TABLE `readiness_enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `internships`
--
ALTER TABLE `internships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `placements`
--
ALTER TABLE `placements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `readiness_enrollments`
--
ALTER TABLE `readiness_enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1009;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`listing_id`) REFERENCES `internships` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
