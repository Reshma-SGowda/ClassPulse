ClassPulse – Online Feedback Management
System
ClassPulse is a web-based application developed using PHP and MySQL that streamlines the
process of collecting, managing, and analyzing student feedback. It provides a structured platform
for students to evaluate teachers and courses while enabling administrators to improve academic
quality.
Key Features
Student Module
1 User registration and secure login
2 Submit structured feedback
3 Simple and user-friendly interface
Admin Module
1 Admin dashboard
2 Manage teacher records
3 View and analyze feedback
Tech Stack
1 Frontend: HTML, CSS, JavaScript
2 Backend: PHP
3 Database: MySQL
4 Server: XAMPP / WAMP
Project Structure
classpulse/
■■■ index.php
■■■ register.php
■■■ student/
■ ■■■ dashboard.php
■ ■■■ feedback_form.php
■ ■■■ submit_feedback.php
■■■ admin/
■ ■■■ dashboard.php
■ ■■■ manage_teacher.php
■ ■■■ view_feedback.php
■■■ includes/
■ ■■■ db_connect.php
■ ■■■ header.php
■ ■■■ footer.php
■■■ assets/
 ■■■ css/
 ■■■ js/
 ■■■ images/
Setup Instructions
1 Clone the repository
2 Move project to htdocs/www
3 Create MySQL database and import SQL file
4 Configure db_connect.php
5 Run using localhost
Security Note
Do not upload sensitive credentials. Use environment variables and validate inputs to prevent
vulnerabilities.
Author
Reshma S Gowda – Engineering Student | Cybersecurity Enthusiast
