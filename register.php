<?php
// register.php — Student Registration Page
include('includes/db_connect.php');
session_start();

$message = '';

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $roll_no = $_POST['roll_no'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $course = $_POST['course'];
    $semester = $_POST['semester'];

    // Check if student already exists
    $checkQuery = "SELECT * FROM students WHERE email='$email' OR roll_no='$roll_no'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        $message = "<div class='alert alert-warning text-center'>Email or Roll No already registered!</div>";
    } else {
        $insertQuery = "INSERT INTO students (name, roll_no, email, password, course, semester)
                        VALUES ('$name', '$roll_no', '$email', '$password', '$course', '$semester')";
        if ($conn->query($insertQuery)) {
            $message = "<div class='alert alert-success text-center'>Registration successful! <a href='index.php'>Login now</a></div>";
        } else {
            $message = "<div class='alert alert-danger text-center'>Error: " . $conn->error . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClassPulse | Student Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

<div class="card shadow-lg border-0 rounded-4" style="width: 430px;">
    <div class="card-body p-4">
        <h3 class="text-center mb-3 text-success fw-bold">Student Registration</h3>

        <?= $message ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Roll Number</label>
                <input type="text" name="roll_no" class="form-control" placeholder="Enter roll number" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter email address" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Create password" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Course</label>
                    <input type="text" name="course" class="form-control" placeholder="e.g., B.E CSE" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Semester</label>
                    <select name="semester" class="form-select" required>
                        <option value="" selected disabled>Choose...</option>
                        <option value="1st">1st</option>
                        <option value="2nd">2nd</option>
                        <option value="3rd">3rd</option>
                        <option value="4th">4th</option>
                        <option value="5th">5th</option>
                        <option value="6th">6th</option>
                        <option value="7th">7th</option>
                        <option value="8th">8th</option>
                    </select>
                </div>
            </div>

            <button type="submit" name="register" class="btn btn-success w-100 rounded-3">Register</button>
        </form>

        <p class="text-center mt-3 mb-0">
            <a href="index.php" class="text-decoration-none">Already have an account? Login</a>
        </p>
    </div>
</div>

</body>
</html>
