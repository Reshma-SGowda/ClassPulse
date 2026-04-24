<?php
session_start();
include('../includes/db_connect.php');

// Redirect if admin not logged in
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

// Fetch all feedback
$sql = "
    SELECT f.id, s.name AS student_name, t.name AS teacher_name, f.rating, f.comments
    FROM feedback f
    JOIN students s ON f.student_id = s.id
    JOIN teachers t ON f.teacher_id = t.id
    ORDER BY f.id DESC
";

$feedbacks = $conn->query($sql);

// Check if query succeeded
if (!$feedbacks) {
    die("Database query failed: " . $conn->error);
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">

    <h2 class="text-primary fw-bold mb-4 text-center">View Feedback</h2>

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Teacher Name</th>
                            <th>Rating</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($feedbacks->num_rows > 0) {
                            $i = 1;
                            while ($row = $feedbacks->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$i}</td>
                                        <td>".htmlspecialchars($row['student_name'])."</td>
                                        <td>".htmlspecialchars($row['teacher_name'])."</td>
                                        <td>{$row['rating']}/5</td>
                                        <td>".htmlspecialchars($row['comments'])."</td>
                                      </tr>";
                                $i++;
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center'>No feedback submitted yet.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-primary">⬅ Back to Dashboard</a>
    </div>

</div>

<?php include('../includes/footer.php'); ?>
