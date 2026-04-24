<?php
// student/feedback_form.php
session_start();
include('../includes/db_connect.php'); // Correct path for DB

// Redirect if not logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: ../index.php");
    exit();
}

// Fetch all teachers
$teachers = $conn->query("SELECT * FROM teachers ORDER BY name ASC");

// Show message from submission
$message = "";
if (isset($_SESSION['feedback_msg'])) {
    $message = $_SESSION['feedback_msg'];
    unset($_SESSION['feedback_msg']);
}
?>

<?php include('../includes/header.php'); // Correct header include ?>

<div class="container mt-5">
  <div class="card shadow-lg border-0 rounded-4">
    <div class="card-body p-4">
      <h3 class="text-center text-success fw-bold mb-4">Submit Feedback</h3>

      <?= $message ?>

      <!-- FORM SUBMITS TO submit_feedback.php -->
      <form method="POST" action="submit_feedback.php">

        <div class="mb-3">
          <label class="form-label">Select Teacher</label>
          <select name="teacher_id" class="form-select" required>
            <option value="" selected disabled>Choose a teacher</option>
            <?php while ($row = $teachers->fetch_assoc()): ?>
              <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?> — <?= htmlspecialchars($row['subject']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Rating (1 - 5)</label>
          <select name="rating" class="form-select" required>
            <option value="" selected disabled>Select rating</option>
            <option value="1">1 - Poor</option>
            <option value="2">2 - Fair</option>
            <option value="3">3 - Good</option>
            <option value="4">4 - Very Good</option>
            <option value="5">5 - Excellent</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Comments</label>
          <textarea name="comments" class="form-control" rows="4" placeholder="Write your feedback..." required></textarea>
        </div>

        <button type="submit" name="submit_feedback" class="btn btn-success w-100 rounded-3">Submit Feedback</button>
      </form>
    </div>
  </div>
</div>

<?php include('../includes/footer.php'); // Correct footer include ?>
