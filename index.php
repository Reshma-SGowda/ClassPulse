<?php
// index.php - Unified Login Page for Admin and Student
session_start();
include('includes/db_connect.php');

$error = '';

if (isset($_POST['login'])) {
    $email_or_username = $_POST['email_or_username'];
    $password = md5($_POST['password']);

    // Admin login check
    $adminQuery = "SELECT * FROM admin WHERE username='$email_or_username' AND password='$password'";
    $adminResult = $conn->query($adminQuery);

    if ($adminResult->num_rows == 1) {
        $_SESSION['admin'] = $email_or_username;
        header("Location: admin/dashboard.php");
        exit();
    }

    // Student login check
    $studentQuery = "SELECT * FROM students WHERE email='$email_or_username' OR roll_no='$email_or_username'";
    $studentResult = $conn->query($studentQuery);

    if ($studentResult->num_rows == 1) {
        $row = $studentResult->fetch_assoc();
        if ($row['password'] == $password) {
            $_SESSION['student_id'] = $row['id'];
            $_SESSION['student_name'] = $row['name'];
            header("Location: student/dashboard.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No user found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClassPulse | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

<div class="card shadow-lg border-0 rounded-4" style="width: 380px;">
    <div class="card-body p-4">
        <h3 class="text-center mb-4 text-primary fw-bold">ClassPulse Login</h3>

        <?php if ($error): ?>
            <div class="alert alert-danger py-2"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Email / Username / Roll No</label>
                <input type="text" name="email_or_username" class="form-control" placeholder="Enter your login ID" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100 rounded-3">Login</button>
        </form>

        <p class="text-center mt-3 mb-0">
            <a href="register.php" class="text-decoration-none">New student? Register here</a>
        </p>
    </div>
</div>

</body>
</html>
