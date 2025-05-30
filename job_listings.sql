-- Create the 'job_listings' database
CREATE DATABASE IF NOT EXISTS job_listings;
USE job_listings;

-- Table structure for 'users' table
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `city` varchar(45) DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Insert data into 'users' table
INSERT INTO `users` VALUES
  (1,'John Doe','user1@gmail.com','$2y$10$UkdJDaWLRHPVwOu3lb9XW.FZWZmFaLM0BJbaj0/7dvPIqs7sdDlvK','Boston','MA','2023-11-18 13:55:59'),
  (2,'Jane Doe','user2@gmail.com','$2y$10$UkdJDaWLRHPVwOu3lb9XW.FZWZmFaLM0BJbaj0/7dvPIqs7sdDlvK','San Francisco','CA','2023-11-18 13:58:26'),
  (3,'Steve Smith','user3@gmail.com','$2y$10$UkdJDaWLRHPVwOu3lb9XW.FZWZmFaLM0BJbaj0/7dvPIqs7sdDlvK','Chicago','IL','2023-11-18 13:59:13'),
  (4, 'Alice Johnson',  'alice.johnson@gmail.com', '$2y$10$UkdJDaWLRHPVwOu3lb9XW.FZWZmFaLM0BJbaj0/7dvPIqs7sdDlvK', 'Seattle',      'WA', '2023-12-01 09:15:00'),
  (5, 'Bob Martinez',   'bob.martinez@example.com', '$2y$10$UkdJDaWLRHPVwOu3lb9XW.FZWZmFaLM0BJbaj0/7dvPIqs7sdDlvK', 'Austin',       'TX', '2023-12-02 11:30:45'),
  (6, 'Carol Lee',      'carol.lee@example.com',    '$2y$10$UkdJDaWLRHPVwOu3lb9XW.FZWZmFaLM0BJbaj0/7dvPIqs7sdDlvK', 'Denver',       'CO', '2023-12-03 14:22:10');


-- Table structure for 'listings' table
CREATE TABLE IF NOT EXISTS `listings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext,
  `salary` varchar(45) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `company` varchar(45) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `requirements` longtext,
  `benefits` longtext,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_listings_users_idx` (`user_id`),
  CONSTRAINT `fk_listings_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Insert data into 'listings' table
INSERT INTO `listings` VALUES
  (1,1,'Software Engineer','We are seeking a skilled software engineer to develop high-quality software solutions','90000','development, coding, java, python','Tech Solutions Inc.','123 Main St','Albany','NY','348-334-3949','info@techsolutions.com','Bachelors degree in Computer Science or related field, 3+ years of software development experience','Healthcare, 401(k) matching, flexible work hours','2023-11-18 14:04:36'),
  (2,2,'Marketing Specialist','We are looking for a Marketing Specialist to create and manage marketing campaigns','80000','marketing, advertising','Marketing Pros','123 Market St','San Francisco','CA','454-344-3344','info@marketingpros.com','Bachelors degree in Marketing or related field, experience in digital marketing','Health and dental insurance, paid time off, remote work options','2023-11-18 14:06:33'),
  (3,3,'Web Developer','Join our team as a Web Developer and create amazing web applications','85000','web development, programming','WebTech Solutions','789 Web Ave','Chicago','IL','456-876-5432','info@webtechsolutions.com','Bachelors degree in Computer Science or related field, proficiency in HTML, CSS, JavaScript','Competitive salary, professional development opportunities, friendly work environment','2023-11-18 14:08:44'),
  (4,1,'Data Analyst','We are hiring a Data Analyst to analyze and interpret data for insights','75000','data analysis, statistics','Data Insights LLC','101 Data St','Chicago','IL','444-555-5555','info@datainsights.com','Bachelors degree in Data Science or related field, strong analytical skills','Health benefits, remote work options, casual dress code','2023-11-18 14:11:55'),
  (5,2,'Graphic Designer','Join our creative team as a Graphic Designer and bring ideas to life','70000','graphic design, creative','CreativeWorks Inc','234 Design Blvd','Albany','NY','499-321-9876','info@creativeworks.com','Bachelors degree in Graphic Design or related field, proficiency in Adobe Creative Suite','Flexible work hours, creative work environment, opportunities for growth','2023-11-18 14:13:35'),
  (7,1,'Frontend Web Developer','This is a frontend position working with React','70000','frontend, development','Traversy Media','10 main st','Boston','MA','555-555-5555','info@test.com','Bachelors degree','401K and Health insurance','2023-11-21 14:07:24'),
  (8,  4, 'DevOps Engineer',     'Looking for an experienced DevOps Engineer to build CI/CD pipelines and infrastructure as code.',   '115000', 'devops, aws, docker',           'CloudWorks LLC',        '500 Cloud Ave',     'Seattle',      'WA',    '206-555-0101','jobs@cloudworks.com',        '5+ years in DevOps, AWS Certified, scripting skills in Python or Bash', 'Health insurance, stock options, remote-friendly',                      '2023-12-05 10:05:12'),
  (9,  5, 'Product Manager',     'Lead cross-functional teams to deliver new product features from concept through launch.',         '105000', 'product, agile, leadership',      'InnovateNow Inc.',      '120 Innovation Dr',  'Austin',       'TX',    '512-555-0202','pm@innovatenow.com',         '3+ years product management experience, strong communication skills',  '401(k) match, generous PTO, career mentorship',                        '2023-12-06 08:45:30'),
  (10, 6, 'UX/UI Designer',      'Design intuitive user interfaces and craft engaging user experiences for our web and mobile apps.', '95000',  'design, ux, ui, figma',            'PixelPerfect Design',    '88 Creative Blvd',   'Denver',       'CO',    '303-555-0303','careers@pixelperfect.com',   'Portfolio demonstrating UX projects, proficiency in Figma or Sketch', 'Flexible hours, creative office space, annual design retreat',          '2023-12-07 15:12:50'),
  (11, 4, 'Backend Developer',   'Build and maintain scalable RESTful APIs and microservices in a fast-paced environment.',           '98000',  'backend, node.js, mongodb',        'API Masters',           '77 Server St',       'Seattle',      'WA',    '206-555-0404','devjobs@apimasters.com',     '4+ years Node.js, experience with NoSQL databases',                'Health & dental, remote options, team offsites',                      '2023-12-08 09:55:05'),
  (12, 5, 'Data Scientist',      'Use machine learning techniques to extract insights from large datasets and drive business decisions.','120000','data science, ml, python',       'Insight Analytics',     '300 Data Rd',        'Austin',       'TX',    '512-555-0505','ds@insightanalytics.com',    'MSc or PhD in a quantitative field, Python & R proficiency',         'Competitive salary, research budget, conference travel stipend',      '2023-12-09 13:20:40');
