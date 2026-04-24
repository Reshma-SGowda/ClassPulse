<?php
// student/dashboard.php
session_start();
include('../includes/db_connect.php');

// Redirect if not logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: ../index.php");
    exit();
}

$student_name = $_SESSION['student_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClassPulse | Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">ClassPulse</a>
    <div class="ms-auto">
      <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- Dashboard Content -->
<div class="container mt-5">
  <div class="card shadow-lg border-0 rounded-4">
    <div class="card-body text-center p-4">
      <h3 class="fw-bold text-success">Welcome, <?= htmlspecialchars($student_name) ?> 🎓</h3>
      <p class="text-muted">You can now give feedback to your teachers.</p>
      
      <div class="d-flex justify-content-center gap-3 mt-4">
        <a href="feedback_form.php" class="btn btn-success px-4">Give Feedback</a>
        <a href="submitted_feedback.php" class="btn btn-outline-primary px-4">View Submitted</a>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="text-center mt-5 text-muted small">
  © <?= date("Y") ?> ClassPulse | Designed for College Feedback System
</footer>

</body>
</html>
