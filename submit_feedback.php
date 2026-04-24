<?php
// student/submit_feedback.php
session_start();
include('../includes/db_connect.php'); // Correct path for DB

// Redirect if student not logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: ../index.php");
    exit();
}

$student_id = $_SESSION['student_id'];

if (isset($_POST['submit_feedback'])) {
    // Sanitize input
    $teacher_id = intval($_POST['teacher_id']);
    $rating = intval($_POST['rating']);
    $comments = trim($_POST['comments']);

    // Prevent duplicate feedback using prepared statement
    $stmt = $conn->prepare("SELECT id FROM feedback WHERE student_id=? AND teacher_id=?");
    $stmt->bind_param("ii", $student_id, $teacher_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['feedback_msg'] = "<div class='alert alert-warning text-center'>You have already submitted feedback for this teacher!</div>";
    } else {
        // Insert feedback securely
        $insertStmt = $conn->prepare("INSERT INTO feedback (student_id, teacher_id, rating, comments) VALUES (?, ?, ?, ?)");
        $insertStmt->bind_param("iiis", $student_id, $teacher_id, $rating, $comments);

        if ($insertStmt->execute()) {
            $_SESSION['feedback_msg'] = "<div class='alert alert-success text-center'>Feedback submitted successfully!</div>";
        } else {
            $_SESSION['feedback_msg'] = "<div class='alert alert-danger text-center'>Error: " . htmlspecialchars($conn->error) . "</div>";
        }
        $insertStmt->close();
    }

    $stmt->close();

    // Redirect back to feedback form
    header("Location: feedback_form.php");
    exit();

} else {
    // Accessed directly without submitting form
    header("Location: dashboard.php");
    exit();
}
?>
