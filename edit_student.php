<?php
session_start();
include('../includes/db_connect.php');

// Redirect if admin not logged in
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

// Check if student ID is provided
if (!isset($_GET['id'])) {
    header("Location: manage_student.php");
    exit();
}

$student_id = intval($_GET['id']);
$message = "";

// Fetch student details
$stmt = $conn->prepare("SELECT * FROM students WHERE id=?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    header("Location: manage_student.php");
    exit();
}

$student = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if (isset($_POST['update_student'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']); // optional

    if (!empty($name) && !empty($email)) {
        if (!empty($password)) {
            // Update with new password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE students SET name=?, email=?, password=? WHERE id=?");
            $stmt->bind_param("sssi", $name, $email, $hashed_password, $student_id);
        } else {
            // Update without changing password
            $stmt = $conn->prepare("UPDATE students SET name=?, email=? WHERE id=?");
            $stmt->bind_param("ssi", $name, $email, $student_id);
        }

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success text-center'>Student updated successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger text-center'>Error: " . htmlspecialchars($conn->error) . "</div>";
        }
        $stmt->close();
    } else {
        $message = "<div class='alert alert-warning text-center'>Name and Email cannot be empty!</div>";
    }
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">

    <h2 class="text-primary fw-bold mb-4 text-center">Edit Student</h2>

    <!-- Show message -->
    <?= $message ?>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <form method="POST" class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Student Name</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($student['name']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($student['email']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password (Leave blank to keep unchanged)</label>
                    <input type="password" name="password" class="form-control" placeholder="New Password">
                </div>

                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" name="update_student" class="btn btn-success w-100">Update Student</button>
                </div>

            </form>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="manage_student.php" class="btn btn-primary">⬅ Back to Manage Students</a>
    </div>

</div>

<?php include('../includes/footer.php'); ?>
