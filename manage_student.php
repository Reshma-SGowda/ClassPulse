<?php
session_start();
include('../includes/db_connect.php');

// Redirect if admin not logged in
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

// Initialize message
$message = "";

// Handle Add Student form submission
if (isset($_POST['add_student'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($name) && !empty($email) && !empty($password)) {
        // Hash the password before storing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO students (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashed_password);
        if ($stmt->execute()) {
            $message = "<div class='alert alert-success text-center'>Student added successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger text-center'>Error: " . htmlspecialchars($conn->error) . "</div>";
        }
        $stmt->close();
    } else {
        $message = "<div class='alert alert-warning text-center'>Please fill in all fields!</div>";
    }
}

// Handle Delete Student
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM students WHERE id=?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_student.php");
    exit();
}

// Fetch all students
$students = $conn->query("SELECT * FROM students ORDER BY name ASC");
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">

    <h2 class="text-primary fw-bold mb-4 text-center">Manage Students</h2>

    <!-- Show message -->
    <?= $message ?>

    <!-- Add Student Form -->
    <div class="card shadow-sm border-0 rounded-4 mb-5">
        <div class="card-body">
            <h5 class="card-title text-success mb-3">Add New Student</h5>
            <form method="POST" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="name" class="form-control" placeholder="Student Name" required>
                </div>
                <div class="col-md-4">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="col-md-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="col-md-1">
                    <button type="submit" name="add_student" class="btn btn-success w-100">Add</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Student List Table -->
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body">
            <h5 class="card-title text-primary mb-3">Existing Students</h5>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        if ($students->num_rows > 0) {
                            while ($row = $students->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$i}</td>
                                        <td>".htmlspecialchars($row['name'])."</td>
                                        <td>".htmlspecialchars($row['email'])."</td>
                                        <td>
                                            <a href='edit_student.php?id={$row['id']}' class='btn btn-sm btn-warning me-2'>Edit</a>
                                            <a href='manage_student.php?delete_id={$row['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete this student?');\">Delete</a>
                                        </td>
                                      </tr>";
                                $i++;
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center'>No students found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?php include('../includes/footer.php'); ?>
