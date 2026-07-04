# Digital Internship Portal (DIP)

A centralized, verified internship management platform for UICT students.

## Problem & Solution

**Problem:** UICT students currently have no centralized, verified system to find internships. They rely on WhatsApp groups, word-of-mouth, and unverified postings — leading to wasted time, exploitation, and missed opportunities.

**Solution:** The Digital Internship Portal provides a verified platform where students can browse internships, track applications, and get Readiness certified. Companies post verified internships and manage applicants. Admin oversees all listings, payments, and placements.

## Key Features

- Student registration with @stu.uict.ac.ug email domain
- Readiness certification bootcamp (Foundation UGX 30k / Professional UGX 50k)
- Internship browsing, applying, and application tracking
- Company posting and applicant management (shortlist/reject/place)
- Admin approval workflow (listings, payments, placements)
- Placement tracking and reporting

## Tech Stack

- Backend: PHP 8.2
- Database: MySQL 10.4
- Frontend: HTML5, CSS3, JavaScript
- Server: XAMPP (local) / InfinityFree (production)

## Setup Instructions

### Local Development (XAMPP)

1. Install XAMPP from https://www.apachefriends.org/
2. Clone this repository:
   ```bash
   git clone https://github.com/BrianOtuura/digital_intern_portal.git
3. Copy the internhub folder to C:\xampp\htdocs\

4. Start Apache and MySQL in XAMPP

5. Import the database:

        Open phpMyAdmin: http://localhost/phpmyadmin

        Create a database named internhub_db

        Import the SQL file from database/dump.sql

6. Update database credentials in includes/db.php

7. Visit http://localhost/internhub/


## Production (InfinityFree)

    Create a free account at https://www.infinityfree.com/

    Upload all files to the htdocs folder via FTP or file manager

    Create a MySQL database in the control panel

    Import the SQL file via phpMyAdmin

    Update includes/db.php with production credentials

    Visit your domain

## Default Admin Credentials

    Email: admin@internhub.com

    Password: admin123

## Demo Mode

For Readiness certification demo, enter DEMO123 as the payment reference.

## Team

    Otuura Brian Oneka – 2401901918

    Naika Cornellious – 2401900958g

    Muhire Moses – 2401901919

    Karl Peter Kireeba – 2401900229

    Musika Owen – 2401902020g

    Mikenge Adonia – 2401900306

## Supervisor

Mr. Kimbugwe Anthony

## License

This project is for academic purposes at UICT
