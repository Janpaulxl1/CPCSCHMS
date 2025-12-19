<?php
require 'db.php';

$student_id = (int)($_GET['student_id'] ?? 0);

if (!$student_id) {
    die("Student ID not provided.");
}

if ($conn) {
    // Fetch student name
    $stmt = $conn->prepare("SELECT first_name, middle_name, last_name FROM students WHERE id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$student) {
        die("Student not found.");
    }

    $student_name = trim($student['first_name'] . ' ' . ($student['middle_name'] ?? '') . ' ' . $student['last_name']);

    // Fetch assessment data
    $assess_stmt = $conn->prepare("SELECT * FROM physical_assessments WHERE student_id = ? ORDER BY created_at DESC LIMIT 1");
    $assess_stmt->bind_param("i", $student_id);
    $assess_stmt->execute();
    $assessment = $assess_stmt->get_result()->fetch_assoc();
    $assess_stmt->close();
} else {
    die("Database connection failed.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Physical Assessment - <?= htmlspecialchars($student_name) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body { background: #f3e5e5; font-family: 'Inter', sans-serif; }
    header { background: #b91c1c; color: white; padding: 1rem; text-align: center; }
    #mainCard { background: white; margin: 1rem; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .section { margin-bottom: 1.5rem; }
    .section h4 { color: #b91c1c; font-weight: bold; margin-bottom: 0.5rem; }
    .field { margin-bottom: 0.5rem; }
    .field label { font-weight: 600; color: #374151; }
    .field value { font-weight: 500; color: #1f2937; }
    .inline-group { display: flex; gap: 1rem; flex-wrap: wrap; }
    .inline-group .field { flex: 1 1 200px; }
    .checkbox-row { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; }
    .back-btn { background: #b91c1c; color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; display: inline-block; margin-bottom: 1rem; }
    .back-btn:hover { background: #a11717; }
  </style>
</head>
<body>
  <header>
    <h1 class="text-xl font-bold">Physical Assessment Record</h1>
    <p>Student: <?= htmlspecialchars($student_name) ?></p>
  </header>
  <div id="mainCard">
    <a href="studentfile_dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>

    <?php if ($assessment): ?>
      <div class="section">
        <h4>Personal Information</h4>
        <div class="inline-group">
          <div class="field"><label>Name:</label> <span><?= htmlspecialchars($assessment['name'] ?? 'N/A') ?></span></div>
          <div class="field"><label>Age:</label> <span><?= htmlspecialchars($assessment['age'] ?? 'N/A') ?></span></div>
          <div class="field"><label>Sex:</label> <span><?= htmlspecialchars($assessment['sex'] ?? 'N/A') ?></span></div>
          <div class="field"><label>Civil Status:</label> <span><?= htmlspecialchars($assessment['civil_status'] ?? 'N/A') ?></span></div>
        </div>
        <div class="field"><label>Address:</label> <span><?= htmlspecialchars($assessment['address'] ?? 'N/A') ?></span></div>
        <div class="inline-group">
          <div class="field"><label>Date of Birth:</label> <span><?= htmlspecialchars($assessment['date_of_birth'] ?? 'N/A') ?></span></div>
          <div class="field"><label>Place of Birth:</label> <span><?= htmlspecialchars($assessment['place_of_birth'] ?? 'N/A') ?></span></div>
        </div>
        <div class="inline-group">
          <div class="field"><label>Nationality:</label> <span><?= htmlspecialchars($assessment['nationality'] ?? 'N/A') ?></span></div>
          <div class="field"><label>Religion:</label> <span><?= htmlspecialchars($assessment['religion'] ?? 'N/A') ?></span></div>
          <div class="field"><label>Course:</label> <span><?= htmlspecialchars($assessment['course'] ?? 'N/A') ?></span></div>
        </div>
        <div class="field"><label>Email:</label> <span><?= htmlspecialchars($assessment['email'] ?? 'N/A') ?></span></div>
        <div class="field"><label>Facebook:</label> <span><?= htmlspecialchars($assessment['facebook'] ?? 'N/A') ?></span></div>
      </div>

      <div class="section">
        <h4>COVID Vaccine Information</h4>
        <div class="inline-group">
          <div class="field"><label>1st Dose:</label> <span><?= htmlspecialchars($assessment['dose1'] ?? 'N/A') ?></span></div>
          <div class="field"><label>2nd Dose:</label> <span><?= htmlspecialchars($assessment['dose2'] ?? 'N/A') ?></span></div>
          <div class="field"><label>1st Booster:</label> <span><?= htmlspecialchars($assessment['booster1'] ?? 'N/A') ?></span></div>
          <div class="field"><label>2nd Booster:</label> <span><?= htmlspecialchars($assessment['booster2'] ?? 'N/A') ?></span></div>
        </div>
      </div>

      <div class="section">
        <h4>Family Information</h4>
        <div class="inline-group">
          <div class="field"><label>Spouse:</label> <span><?= htmlspecialchars($assessment['spouse'] ?? 'N/A') ?></span></div>
          <div class="field"><label>Father:</label> <span><?= htmlspecialchars($assessment['father'] ?? 'N/A') ?></span></div>
        </div>
        <div class="inline-group">
          <div class="field"><label>Father Contact:</label> <span><?= htmlspecialchars($assessment['father_contact'] ?? 'N/A') ?></span></div>
          <div class="field"><label>Mother:</label> <span><?= htmlspecialchars($assessment['mother'] ?? 'N/A') ?></span></div>
        </div>
        <div class="inline-group">
          <div class="field"><label>Mother Contact:</label> <span><?= htmlspecialchars($assessment['mother_contact'] ?? 'N/A') ?></span></div>
          <div class="field"><label>Complete Address:</label> <span><?= htmlspecialchars($assessment['complete_address'] ?? 'N/A') ?></span></div>
        </div>
        <div class="inline-group">
          <div class="field"><label>Emergency Person:</label> <span><?= htmlspecialchars($assessment['emergency_person'] ?? 'N/A') ?></span></div>
          <div class="field"><label>Relationship:</label> <span><?= htmlspecialchars($assessment['relationship'] ?? 'N/A') ?></span></div>
        </div>
        <div class="field"><label>Emergency No:</label> <span><?= htmlspecialchars($assessment['emergency_no'] ?? 'N/A') ?></span></div>
      </div>

      <div class="section">
        <h4>Personal Health Information</h4>
        <div class="checkbox-row"><label>Has Child:</label> <span><?= $assessment['has_child'] ? 'Yes - ' . htmlspecialchars($assessment['child_details'] ?? '') : 'No' ?></span></div>
        <div class="checkbox-row"><label>Solo Parent:</label> <span><?= $assessment['is_solo'] ? 'Yes - ' . htmlspecialchars($assessment['solo_details'] ?? '') : 'No' ?></span></div>
        <div class="checkbox-row"><label>Pregnant:</label> <span><?= $assessment['is_pregnant'] ? 'Yes - ' . htmlspecialchars($assessment['pregnant_details'] ?? '') : 'No' ?></span></div>
        <div class="checkbox-row"><label>PWD:</label> <span><?= $assessment['is_pwd'] ? 'Yes - ' . htmlspecialchars($assessment['pwd_details'] ?? '') : 'No' ?></span></div>
        <div class="checkbox-row"><label>Inborn Condition:</label> <span><?= $assessment['is_inborn'] ? 'Yes - ' . htmlspecialchars($assessment['inborn_details'] ?? '') : 'No' ?></span></div>
        <div class="checkbox-row"><label>Indigenous People:</label> <span><?= $assessment['is_indig'] ? 'Yes - ' . htmlspecialchars($assessment['indig_details'] ?? '') : 'No' ?></span></div>
        <div class="checkbox-row"><label>Other Persons:</label> <span><?= $assessment['other_persons'] ? 'Yes - ' . htmlspecialchars($assessment['other_persons_text'] ?? '') : 'No' ?></span></div>
        <div class="checkbox-row"><label>Allergies:</label> <span><?= $assessment['has_allergies'] ? 'Yes - ' . htmlspecialchars($assessment['allergies'] ?? '') : 'No' ?></span></div>
      </div>

      <div class="section">
        <h4>Record Date:</h4>
        <span><?= htmlspecialchars($assessment['created_at'] ?? 'N/A') ?></span>
      </div>
    <?php else: ?>
      <p class="text-center text-gray-500">No physical assessment record found for this student.</p>
    <?php endif; ?>
  </div>
</body>
</html>
