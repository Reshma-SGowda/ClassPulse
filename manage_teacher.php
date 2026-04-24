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

// Handle Add Teacher form submission
if (isset($_POST['add_teacher'])) {
    $name = trim($_POST['name']);
    $subject = trim($_POST['subject']);

    if (!empty($name) && !empty($subject)) {
        $stmt = $conn->prepare("INSERT INTO teachers (name, subject) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $subject);
        if ($stmt->execute()) {
            $message = "<div class='alert alert-success text-center'>Teacher added successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger text-center'>Error: " . htmlspecialchars($conn->error) . "</div>";
        }
        $stmt->close();
    } else {
        $message = "<div class='alert alert-warning text-center'>Please fill in all fields!</div>";
    }
}

// Handle Delete Teacher
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM teachers WHERE id=?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_teacher.php");
    exit();
}

// Fetch all teachers
$teachers = $conn->query("SELECT * FROM teachers ORDER BY name ASC");
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">

    <h2 class="text-primary fw-bold mb-4 text-center">Manage Teachers</h2>

    <!-- Show message -->
    <?= $message ?>

    <!-- Add Teacher Form -->
    <div class="card shadow-sm border-0 rounded-4 mb-5">
        <div class="card-body">
            <h5 class="card-title text-success mb-3">Add New Teacher</h5>
            <form method="POST" class="row g-3">
                <div class="col-md-5">
                    <input type="text" name="name" class="form-control" placeholder="Teacher Name" required>
                </div>
                <div class="col-md-5">
                    <input type="text" name="subject" class="form-control" placeholder="Subject" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="add_teacher" class="btn btn-success w-100">Add</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Teacher List Table -->
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body">
            <h5 class="card-title text-primary mb-3">Existing Teachers</h5>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Subject</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        if ($teachers->num_rows > 0) {
                            while ($row = $teachers->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$i}</td>
                                        <td>".htmlspecialchars($row['name'])."</td>
                                        <td>".htmlspecialchars($row['subject'])."</td>
                                        <td>
                                            
                                            <a href='manage_teacher.php?delete_id={$row['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete this teacher?');\">Delete</a>
                                        </td>
                                      </tr>";
                                $i++;
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center'>No teachers found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?php include('../includes/footer.php'); ?>
