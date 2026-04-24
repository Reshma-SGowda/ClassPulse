<?php
session_start();
include('../includes/db_connect.php'); // Correct DB include

// Redirect if student not logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: ../index.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch feedback submitted by this student
$feedbacks = $conn->query("
    SELECT f.id, t.name AS teacher_name, t.subject, f.rating, f.comments
    FROM feedback f
    JOIN teachers t ON f.teacher_id = t.id
    WHERE f.student_id = ?
    ORDER BY f.id DESC
");

// Prepare statement for security
$stmt = $conn->prepare("
    SELECT f.id, t.name AS teacher_name, t.subject, f.rating, f.comments
    FROM feedback f
    JOIN teachers t ON f.teacher_id = t.id
    WHERE f.student_id = ?
    ORDER BY f.id DESC
");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$feedback_result = $stmt->get_result();
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h3 class="text-center text-primary fw-bold mb-4">Your Submitted Feedback</h3>

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Teacher Name</th>
                            <th>Subject</th>
                            <th>Rating</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($feedback_result->num_rows > 0) {
                            $i = 1;
                            while ($row = $feedback_result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$i}</td>
                                        <td>".htmlspecialchars($row['teacher_name'])."</td>
                                        <td>".htmlspecialchars($row['subject'])."</td>
                                        <td>{$row['rating']}/5</td>
                                        <td>".htmlspecialchars($row['comments'])."</td>
                                      </tr>";
                                $i++;
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center'>You have not submitted any feedback yet.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-3">
                <a href="dashboard.php" class="btn btn-outline-secondary">Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
