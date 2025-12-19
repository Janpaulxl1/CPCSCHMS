<?php
session_start();
require_once "db.php";

// ✅ Ensure students table exists with all required columns
$conn->query("CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(50) UNIQUE,
    first_name VARCHAR(100),
    middle_name VARCHAR(100),
    last_name VARCHAR(100),
    birthday DATE,
    gender VARCHAR(10),
    email VARCHAR(100),
    phone VARCHAR(20),
    home_address TEXT,
    guardian_name VARCHAR(100),
    guardian_address TEXT,
    emergency_contact VARCHAR(20),
    relationship VARCHAR(50),
    course VARCHAR(50),
    year_level VARCHAR(10),
    section VARCHAR(100),
    password VARCHAR(255),
    profile_picture VARCHAR(255) DEFAULT NULL,
    section_id INT,
    is_responder TINYINT(1) DEFAULT 0,
    responder_status ENUM('Active','On Duty','Off Duty') DEFAULT 'Off Duty',
    requirements_completed TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

function addColumnIfNotExists($conn, $table, $column, $definition) {
    $result = $conn->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
    if ($result->num_rows == 0) {
        $conn->query("ALTER TABLE `$table` ADD `$column` $definition");
    }
}

// Add missing columns to students
addColumnIfNotExists($conn, 'students', 'student_id', 'VARCHAR(50) UNIQUE');
addColumnIfNotExists($conn, 'students', 'first_name', 'VARCHAR(100)');
addColumnIfNotExists($conn, 'students', 'middle_name', 'VARCHAR(100)');
addColumnIfNotExists($conn, 'students', 'last_name', 'VARCHAR(100)');
addColumnIfNotExists($conn, 'students', 'birthday', 'DATE');
addColumnIfNotExists($conn, 'students', 'gender', 'VARCHAR(10)');
addColumnIfNotExists($conn, 'students', 'email', 'VARCHAR(100)');
addColumnIfNotExists($conn, 'students', 'phone', 'VARCHAR(20)');
addColumnIfNotExists($conn, 'students', 'home_address', 'TEXT');
addColumnIfNotExists($conn, 'students', 'guardian_name', 'VARCHAR(100)');
addColumnIfNotExists($conn, 'students', 'guardian_address', 'TEXT');
addColumnIfNotExists($conn, 'students', 'emergency_contact', 'VARCHAR(20)');
addColumnIfNotExists($conn, 'students', 'relationship', 'VARCHAR(50)');
addColumnIfNotExists($conn, 'students', 'course', 'VARCHAR(50)');
addColumnIfNotExists($conn, 'students', 'year_level', 'VARCHAR(10)');
addColumnIfNotExists($conn, 'students', 'section', 'VARCHAR(100)');
addColumnIfNotExists($conn, 'students', 'password', 'VARCHAR(255)');
addColumnIfNotExists($conn, 'students', 'profile_picture', 'VARCHAR(255) DEFAULT NULL');
addColumnIfNotExists($conn, 'students', 'section_id', 'INT');
addColumnIfNotExists($conn, 'students', 'is_responder', 'TINYINT(1) DEFAULT 0');
addColumnIfNotExists($conn, 'students', 'responder_status', "ENUM('Active','On Duty','Off Duty') DEFAULT 'Off Duty'");
addColumnIfNotExists($conn, 'students', 'requirements_completed', 'TINYINT(1) DEFAULT 0');
addColumnIfNotExists($conn, 'students', 'created_at', 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP');

// ✅ Ensure physical_assessments table exists with all columns
$conn->query("CREATE TABLE IF NOT EXISTS physical_assessments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    name VARCHAR(255),
    age VARCHAR(10),
    sex VARCHAR(10),
    civil_status VARCHAR(50),
    address TEXT,
    date_of_birth DATE,
    place_of_birth VARCHAR(255),
    nationality VARCHAR(100),
    religion VARCHAR(100),
    course VARCHAR(50),
    email VARCHAR(100),
    facebook VARCHAR(255),
    dose1 DATE,
    dose2 DATE,
    booster1 DATE,
    booster2 DATE,
    spouse VARCHAR(255),
    father VARCHAR(255),
    father_contact VARCHAR(20),
    mother VARCHAR(255),
    mother_contact VARCHAR(20),
    complete_address TEXT,
    emergency_person VARCHAR(255),
    relationship VARCHAR(50),
    emergency_no VARCHAR(20),
    has_child TINYINT(1) DEFAULT 0,
    child_details TEXT,
    is_solo TINYINT(1) DEFAULT 0,
    solo_details TEXT,
    is_pregnant TINYINT(1) DEFAULT 0,
    pregnant_details TEXT,
    is_pwd TINYINT(1) DEFAULT 0,
    pwd_details TEXT,
    is_inborn TINYINT(1) DEFAULT 0,
    inborn_details TEXT,
    is_indig TINYINT(1) DEFAULT 0,
    indig_details TEXT,
    other_persons TINYINT(1) DEFAULT 0,
    other_persons_text TEXT,
    has_allergies TINYINT(1) DEFAULT 0,
    allergies TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
)");

// Add missing columns to physical_assessments
addColumnIfNotExists($conn, 'physical_assessments', 'name', 'VARCHAR(255)');
addColumnIfNotExists($conn, 'physical_assessments', 'age', 'VARCHAR(10)');
addColumnIfNotExists($conn, 'physical_assessments', 'sex', 'VARCHAR(10)');
addColumnIfNotExists($conn, 'physical_assessments', 'civil_status', 'VARCHAR(50)');
addColumnIfNotExists($conn, 'physical_assessments', 'address', 'TEXT');
addColumnIfNotExists($conn, 'physical_assessments', 'date_of_birth', 'DATE');
addColumnIfNotExists($conn, 'physical_assessments', 'place_of_birth', 'VARCHAR(255)');
addColumnIfNotExists($conn, 'physical_assessments', 'nationality', 'VARCHAR(100)');
addColumnIfNotExists($conn, 'physical_assessments', 'religion', 'VARCHAR(100)');
addColumnIfNotExists($conn, 'physical_assessments', 'course', 'VARCHAR(50)');
addColumnIfNotExists($conn, 'physical_assessments', 'email', 'VARCHAR(100)');
addColumnIfNotExists($conn, 'physical_assessments', 'facebook', 'VARCHAR(255)');
addColumnIfNotExists($conn, 'physical_assessments', 'dose1', 'DATE');
addColumnIfNotExists($conn, 'physical_assessments', 'dose2', 'DATE');
addColumnIfNotExists($conn, 'physical_assessments', 'booster1', 'DATE');
addColumnIfNotExists($conn, 'physical_assessments', 'booster2', 'DATE');
addColumnIfNotExists($conn, 'physical_assessments', 'spouse', 'VARCHAR(255)');
addColumnIfNotExists($conn, 'physical_assessments', 'father', 'VARCHAR(255)');
addColumnIfNotExists($conn, 'physical_assessments', 'father_contact', 'VARCHAR(20)');
addColumnIfNotExists($conn, 'physical_assessments', 'mother', 'VARCHAR(255)');
addColumnIfNotExists($conn, 'physical_assessments', 'mother_contact', 'VARCHAR(20)');
addColumnIfNotExists($conn, 'physical_assessments', 'complete_address', 'TEXT');
addColumnIfNotExists($conn, 'physical_assessments', 'emergency_person', 'VARCHAR(255)');
addColumnIfNotExists($conn, 'physical_assessments', 'relationship', 'VARCHAR(50)');
addColumnIfNotExists($conn, 'physical_assessments', 'emergency_no', 'VARCHAR(20)');
addColumnIfNotExists($conn, 'physical_assessments', 'has_child', 'TINYINT(1) DEFAULT 0');
addColumnIfNotExists($conn, 'physical_assessments', 'child_details', 'TEXT');
addColumnIfNotExists($conn, 'physical_assessments', 'is_solo', 'TINYINT(1) DEFAULT 0');
addColumnIfNotExists($conn, 'physical_assessments', 'solo_details', 'TEXT');
addColumnIfNotExists($conn, 'physical_assessments', 'is_pregnant', 'TINYINT(1) DEFAULT 0');
addColumnIfNotExists($conn, 'physical_assessments', 'pregnant_details', 'TEXT');
addColumnIfNotExists($conn, 'physical_assessments', 'is_pwd', 'TINYINT(1) DEFAULT 0');
addColumnIfNotExists($conn, 'physical_assessments', 'pwd_details', 'TEXT');
addColumnIfNotExists($conn, 'physical_assessments', 'is_inborn', 'TINYINT(1) DEFAULT 0');
addColumnIfNotExists($conn, 'physical_assessments', 'inborn_details', 'TEXT');
addColumnIfNotExists($conn, 'physical_assessments', 'is_indig', 'TINYINT(1) DEFAULT 0');
addColumnIfNotExists($conn, 'physical_assessments', 'indig_details', 'TEXT');
addColumnIfNotExists($conn, 'physical_assessments', 'other_persons', 'TINYINT(1) DEFAULT 0');
addColumnIfNotExists($conn, 'physical_assessments', 'other_persons_text', 'TEXT');
addColumnIfNotExists($conn, 'physical_assessments', 'has_allergies', 'TINYINT(1) DEFAULT 0');
addColumnIfNotExists($conn, 'physical_assessments', 'allergies', 'TEXT');
addColumnIfNotExists($conn, 'physical_assessments', 'created_at', 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP');

// Fetch Sections
$sections = [];
$res = $conn->query("SELECT id, name, semester FROM sections ORDER BY name ASC");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $sections[] = $row;
    }
}

// Pre-fill data from session
$prefill = $_SESSION['registration_data'] ?? [];

if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST['register'])) {
    // Store form data from student_registration.php in session
    $_SESSION['registration_data'] = $_POST;
    // Handle profile picture upload and store path in session
    if (!empty($_FILES['profilePicture']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $filename = uniqid("profile_") . "_" . basename($_FILES["profilePicture"]["name"]);
        $profile_picture = $targetDir . $filename;
        move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $profile_picture);
        $_SESSION['registration_data']['profilePicture'] = $profile_picture;
    }
    // Redirect to physical_assessment.php to display the form
    header("Location: physical_assessment.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['register'])) {
    // Check if registration data exists in session
    if (!isset($_SESSION['registration_data'])) {
        echo "No registration data found. Please start from the registration page.";
        exit;
    }

    $data = $_SESSION['registration_data'];

    $student_id       = $data['studentID'];
    $first_name       = $data['firstName'];
    $middle_name      = $data['middleName'];
    $last_name        = $data['lastName'];
    $birthday         = $data['birthday'];
    $gender           = $data['gender'];
    $email            = $data['email'];
    $phone            = $data['phone'];
    $home_address     = $data['homeAddress'];
    $guardian_name    = $data['guardiansName'];
    $guardian_address = $data['guardiansAddress'];
    $emergency_contact= $data['contactNo'];
    $relationship     = $data['relationship'];
    $course           = $data['course'];
    $year_level       = $data['yearLevel'];
    $section_id       = $data['section'];
    $password         = password_hash($data['createPassword'], PASSWORD_BCRYPT);

    // All students are regular students
    $is_responder = 0;
    $responder_status = "Off Duty"; // default
    $requirements_completed = 1;

    // Profile picture
    $profile_picture = isset($data['profilePicture']) ? $data['profilePicture'] : null;

    // Get section name
    $secStmt = $conn->prepare("SELECT name FROM sections WHERE id = ? LIMIT 1");
    $secStmt->bind_param("i", $section_id);
    $secStmt->execute();
    $secRes = $secStmt->get_result();
    $section_name = "";
    if ($secRes && $row = $secRes->fetch_assoc()) {
        $section_name = $row['name'];
    }
    $secStmt->close();

    // Insert into students
    $stmt = $conn->prepare("INSERT INTO students (student_id, first_name, middle_name, last_name, birthday, gender, email, phone, home_address, guardian_name, guardian_address, emergency_contact, relationship, course, year_level, section, password, profile_picture, section_id, is_responder, responder_status, requirements_completed) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,??)");

    $stmt->bind_param(
        "ssssssssssssssssssiisi",
        $student_id,
        $first_name,
        $middle_name,
        $last_name,
        $birthday,
        $gender,
        $email,
        $phone,
        $home_address,
        $guardian_name,
        $guardian_address,
        $emergency_contact,
        $relationship,
        $course,
        $year_level,
        $section_name,
        $password,
        $profile_picture,
        $section_id,
        $is_responder,
        $responder_status,
        $requirements_completed
    );

    if ($stmt->execute()) {
        $student_db_id = $conn->insert_id;

        // Insert physical assessment data
        $assess_stmt = $conn->prepare("INSERT INTO physical_assessments (student_id, name, age, sex, civil_status, address, date_of_birth, place_of_birth, nationality, religion, course, email, facebook, dose1, dose2, booster1, booster2, spouse, father, father_contact, mother, mother_contact, complete_address, emergency_person, relationship, emergency_no, has_child, child_details, is_solo, solo_details, is_pregnant, pregnant_details, is_pwd, pwd_details, is_inborn, inborn_details, is_indig, indig_details, other_persons, other_persons_text, has_allergies, allergies) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $has_child = isset($_POST['has_child']) ? 1 : 0;
        $child_details = $_POST['child_details'] ?? '';
        $is_solo = isset($_POST['is_solo']) ? 1 : 0;
        $solo_details = $_POST['solo_details'] ?? '';
        $is_pregnant = isset($_POST['is_pregnant']) ? 1 : 0;
        $pregnant_details = $_POST['pregnant_details'] ?? '';
        $is_pwd = isset($_POST['is_pwd']) ? 1 : 0;
        $pwd_details = $_POST['pwd_details'] ?? '';
        $is_inborn = isset($_POST['is_inborn']) ? 1 : 0;
        $inborn_details = $_POST['inborn_details'] ?? '';
        $is_indig = isset($_POST['is_indig']) ? 1 : 0;
        $indig_details = $_POST['indig_details'] ?? '';
        $other_persons = isset($_POST['other_persons']) ? 1 : 0;
        $other_persons_text = $_POST['other_persons_text'] ?? '';
        $has_allergies = isset($_POST['has_allergies']) ? 1 : 0;
        $allergies = $_POST['allergies'] ?? '';

        $assess_stmt->bind_param(
            "isssssssssssssssssssssssssisisisisisisisisisis",
            $student_db_id,
            $_POST['name'] ?? '',
            $_POST['age'] ?? '',
            $_POST['sex'] ?? '',
            $_POST['civil_status'] ?? '',
            $_POST['address'] ?? '',
            $_POST['date_of_birth'] ?? null,
            $_POST['place_of_birth'] ?? '',
            $_POST['nationality'] ?? '',
            $_POST['religion'] ?? '',
            $_POST['course'] ?? '',
            $_POST['email'] ?? '',
            $_POST['facebook'] ?? '',
            $_POST['dose1'] ?? null,
            $_POST['dose2'] ?? null,
            $_POST['booster1'] ?? null,
            $_POST['booster2'] ?? null,
            $_POST['spouse'] ?? '',
            $_POST['father'] ?? '',
            $_POST['father_contact'] ?? '',
            $_POST['mother'] ?? '',
            $_POST['mother_contact'] ?? '',
            $_POST['complete_address'] ?? '',
            $_POST['emergency_person'] ?? '',
            $_POST['relationship'] ?? '',
            $_POST['emergency_no'] ?? '',
            $has_child,
            $child_details,
            $is_solo,
            $solo_details,
            $is_pregnant,
            $pregnant_details,
            $is_pwd,
            $pwd_details,
            $is_inborn,
            $inborn_details,
            $is_indig,
            $indig_details,
            $other_persons,
            $other_persons_text,
            $has_allergies,
            $allergies
        );

        if ($assess_stmt->execute()) {
            // Clear session data
            unset($_SESSION['registration_data']);
            header("Location: studentfile_dashboard.php?success=1");
            exit;
        } else {
            echo "Error saving assessment: " . $assess_stmt->error;
        }
        $assess_stmt->close();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>CPC Clinic - Physical Assessment</title>
    <link rel="icon" type="image" href="images/favicon.jpg">
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <style>
    /* Page background so PDF white area stands out */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background: #e8e8e8;
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
    }

    /* Printable container sized to A4 minus outer margin */
    #printArea {
      width: 100%;
      max-width: 210mm;
      min-height: auto;
      margin: 10mm auto;
      padding: 12mm;
      background: #fff;
      box-sizing: border-box;
      border: 1px solid #111;
      color: #000;
      transform: none;
    }

    .avoid-break { page-break-inside: avoid; break-inside: avoid; }

    .header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
    }
    .header img { width: 78px; height: auto; display: block; }
    .header-text {
      flex: 1 1 auto;
      text-align: center;
      font-size: 13px;
      line-height: 1.2;
    }

    h3 {
      text-align: center;
      margin: 10px 0 12px;
      font-size: 16px;
      text-decoration: underline;
    }

    .section { margin: 10px 0; font-size: 13px; }
    label { display: block; font-weight: 600; margin-bottom: 6px; }

    input[type="text"], input[type="date"], input[type="email"], select {
      width: 100%;
      padding: 6px 8px;
      margin-bottom: 8px;
      border: 1px solid #ccc;
      border-radius: 3px;
      box-sizing: border-box;
      font-size: 13px;
    }

    .inline-group { display: flex; gap: 10px; flex-wrap: wrap; }
    .inline-group .field { flex: 1 1 150px; min-width: 120px; }

    .checkbox-row {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 8px;
      flex-wrap: wrap;
    }
    .checkbox-row input[type="text"] { flex: 1 1 180px; min-width: 140px; }

    /* Action buttons */
    .action-buttons {
      display:flex;
      gap:10px;
      justify-content:flex-end;
      margin: 12px auto;
      max-width: 190mm;
      padding: 8px 12mm;
      box-sizing: border-box;
    }
    .action-buttons button {
      padding: 8px 14px;
      border: none;
      background: #0b74d1;
      color: #fff;
      border-radius: 4px;
      cursor: pointer;
      font-size: 13px;
    }
    .action-buttons button:hover { background:#095ea6; }

    .register-button {
      background-color: #fff;
      color: #b71c1c;
      padding: 8px 14px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 13px;
      font-weight: bold;
    }

    .register-button:hover { background:#f2f2f2; }

    /* Hide action buttons & page outline when printing */
    @media print {
      .action-buttons { display: none; }
      body { background: #fff; }
    }

    .page-break-after { page-break-after: always; }
  </style>
</head>
<body>
  <form action="physical_assessment.php" method="POST" enctype="multipart/form-data">
    <div id="printArea" role="main" aria-label="Student Physical Assessment Form">

    <!-- Header -->
    <div class="header avoid-break">
      <img src="images/cpc.jpg" alt="Left logo" crossorigin="anonymous">
      <div class="header-text">
        Republic of the Philippines<br>
        Province of Cebu<br>
        Municipality of Cordova<br>
        <strong>CORDOVA PUBLIC COLLEGE</strong><br>
        Gabi, Cordova, Cebu<br>
        PATIENT INFORMATION SHEET
      </div>
      <img src="images/municipal.jpg" alt="Right logo" crossorigin="anonymous">
    </div>

    <h3 class="avoid-break">STUDENT PHYSICAL ASSESSMENT FORM<br><small style="font-weight:600">(TO BE FILLED-UP BY STUDENTS)</small></h3>

    <!-- Personal Info -->
    <div class="section avoid-break">
      <div class="inline-group">
        <div class="field"><label for="name">Name</label><input id="name" name="name" type="text" placeholder="Enter name" value="<?php echo htmlspecialchars(($prefill['firstName'] ?? '') . ' ' . ($prefill['middleName'] ?? '') . ' ' . ($prefill['lastName'] ?? '')); ?>"></div>
        <div class="field"><label for="age">Age</label><input id="age" name="age" type="text" placeholder="Age"></div>
        <div class="field"><label for="sex">Sex</label>
          <select id="sex" name="sex">
            <option value="">-- Select --</option>
            <option value="Male" <?php echo (isset($prefill['gender']) && strtolower($prefill['gender']) === 'male') ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo (isset($prefill['gender']) && strtolower($prefill['gender']) === 'female') ? 'selected' : ''; ?>>Female</option>
          </select>
        </div>
        <div class="field"><label for="civil">Civil Status</label><input id="civil" name="civil_status" type="text" placeholder="Civil status"></div>
      </div>

      <label for="address">Address</label>
      <input id="address" name="address" type="text" placeholder="Complete address" value="<?php echo htmlspecialchars($prefill['homeAddress'] ?? ''); ?>">

      <div class="inline-group">
        <div class="field"><label for="dob">Date of Birth</label><input id="dob" name="date_of_birth" type="date" value="<?php echo htmlspecialchars($prefill['birthday'] ?? ''); ?>"></div>
        <div class="field"><label for="pob">Place of Birth</label><input id="pob" name="place_of_birth" type="text" placeholder="Place of birth"></div>
      </div>

      <div class="inline-group">
        <div class="field"><label for="nat">Nationality</label><input id="nat" name="nationality" type="text" placeholder="Nationality"></div>
        <div class="field"><label for="rel">Religion</label><input id="rel" name="religion" type="text" placeholder="Religion"></div>
        <div class="field"><label for="course">Course</label>
          <select id="course" name="course">
            <option value="">-- Select Course --</option>
            <option value="BSIT" <?php echo (isset($prefill['course']) && $prefill['course'] === 'BSIT') ? 'selected' : ''; ?>>BSIT</option>
            <option value="BSHM" <?php echo (isset($prefill['course']) && $prefill['course'] === 'BSHM') ? 'selected' : ''; ?>>BSHM</option>
            <option value="BSED" <?php echo (isset($prefill['course']) && $prefill['course'] === 'BSED') ? 'selected' : ''; ?>>BSED</option>
            <option value="BEED" <?php echo (isset($prefill['course']) && $prefill['course'] === 'BEED') ? 'selected' : ''; ?>>BEED</option>
          </select>
        </div>
      </div>

      <label for="email">Email Address</label>
      <input id="email" name="email" type="email" placeholder="Email address" value="<?php echo htmlspecialchars($prefill['email'] ?? ''); ?>">

      <label for="fb">Facebook Account</label>
      <input id="fb" name="facebook" type="text" placeholder="Facebook account">
    </div>

    <!-- Covid Info -->
    <div class="section avoid-break">
      <h4 style="margin:6px 0 8px;">Covid Vaccine Info</h4>
      <div class="inline-group">
        <div class="field"><label for="dose1">1st Dose Date</label><input id="dose1" name="dose1" type="date"></div>
        <div class="field"><label for="dose2">2nd Dose Date</label><input id="dose2" name="dose2" type="date"></div>
        <div class="field"><label for="booster1">1st Booster Date</label><input id="booster1" name="booster1" type="date"></div>
        <div class="field"><label for="booster2">2nd Booster Date</label><input id="booster2" name="booster2" type="date"></div>
      </div>
    </div>

    <!-- Family Info -->
    <div class="section avoid-break">
      <div class="inline-group">
        <div class="field"><label for="spouse">Spouse Name</label><input id="spouse" name="spouse" type="text"></div>
        <div class="field"><label for="father">Father's Name</label><input id="father" name="father" type="text"></div>
      </div>
      <div class="inline-group">
        <div class="field"><label for="father_contact">Father's Contact No.</label><input id="father_contact" name="father_contact" type="text"></div>
        <div class="field"><label for="mother">Mother's Name</label><input id="mother" name="mother" type="text"></div>
      </div>
      <div class="inline-group">
        <div class="field"><label for="mother_contact">Mother's Contact No.</label><input id="mother_contact" name="mother_contact" type="text"></div>
        <div class="field"><label for="complete_address">Complete Address</label><input id="complete_address" name="complete_address" type="text"></div>
      </div>
      <div class="inline-group">
        <div class="field"><label for="emergency_person">Emergency Contact Person</label><input id="emergency_person" name="emergency_person" type="text"></div>
        <div class="field"><label for="relationship">Relationship</label><input id="relationship" name="relationship" type="text"></div>
      </div>
      <div class="inline-group">
        <div class="field"><label for="emergency_no">Emergency Contact No.</label><input id="emergency_no" name="emergency_no" type="text"></div>
      </div>
    </div>

    <!-- Yes/No Section -->
    <div class="section avoid-break">
  <h4 style="margin:6px 0 8px;">Personal Info Checkboxes</h4>
  <div class="checkbox-row"><label><input type="checkbox" id="child" name="has_child"> Do you have a child?</label><input name="child_details" type="text" placeholder="How many?"></div>
  <div class="checkbox-row"><label><input type="checkbox" id="solo" name="is_solo"> Are you a solo parent?</label><input name="solo_details" type="text" placeholder="Since when?"></div>
  <div class="checkbox-row"><label><input type="checkbox" id="preg" name="is_pregnant"> Are you pregnant?</label><input name="pregnant_details" type="text" placeholder="EDC/EDD, GPTALM"></div>
  <div class="checkbox-row"><label><input type="checkbox" id="pwd" name="is_pwd"> Are you PWD?</label><input name="pwd_details" type="text" placeholder="Which part of the body? If no, when accident?"></div>
  <div class="checkbox-row"><label><input type="checkbox" id="inborn" name="is_inborn"> Inborn?</label><input name="inborn_details" type="text" placeholder="Details"></div>
  <div class="checkbox-row"><label><input type="checkbox" id="indig" name="is_indig"> Are you part of indigenous people?</label><input name="indig_details" type="text" placeholder="What group/Culture?"></div>
  <div class="checkbox-row"><label><input type="checkbox" id="otherp" name="other_persons"> Other persons/Contact:</label><input name="other_persons_text" type="text" placeholder="Enter here"></div>

  <!-- Added allergies field -->
  <div class="checkbox-row">
    <label><input type="checkbox" id="allergiesChk" name="has_allergies"> Do you have any allergies?</label>
    <input name="allergies" type="text" id="allergiesText" placeholder="Enter allergies if any">
  </div>
</div>

  </div><!-- end printArea -->
  </form>

  <!-- Action buttons -->
  <div class="action-buttons" role="toolbar" aria-label="Actions">
    <button onclick="window.location.href='student_registration.php'">⬅️ Back</button>
    <button type="button" class="register-button" onclick="registerStudent()">Register</button>
  </div>

  <div class="login-link" style="text-align: center; margin-top: 1rem; color: white;">
    Already have an account?
    <a href="index.html" style="color: #f8f8f8; text-decoration: underline; font-size: 1rem;">Login</a>
  </div>

  <!-- html2pdf -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <script>
    function registerStudent() {
      if (confirm('Are you sure you want to register?')) {
        // Collect form data
        const formData = new FormData(document.querySelector('form'));
        formData.append('register', 'true');

        // Send AJAX request to register
        fetch('physical_assessment.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.text())
        .then(data => {
          if (data.includes('Location: studentfile_dashboard.php')) {
            alert('Registration successful!');
            window.location.href = 'studentfile_dashboard.php?success=1';
          } else {
            alert('Registration failed: ' + data);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('An error occurred during registration.');
        });
      }
    }

    function waitForImages(parent) {
      const imgs = parent.querySelectorAll('img');
      const promises = [];
      imgs.forEach(img => {
        if (img.complete && img.naturalWidth !== 0) return;
        promises.push(new Promise(resolve => {
          img.addEventListener('load', resolve, { once: true });
          img.addEventListener('error', resolve, { once: true });
        }));
      });
      return Promise.all(promises);
    }

    document.getElementById('printBtn').addEventListener('click', () => window.print());

    document.getElementById('download').addEventListener('click', async function () {
      const element = document.getElementById('printArea');
      await waitForImages(element);
      const opt = {
        margin: [8, 8, 8, 8],
        filename: 'Student_Physical_Assessment.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2, useCORS: true, logging: false, scrollY: 0 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
        pagebreak: { mode: ['css', 'legacy'] }
      };
      const outline = element.style.border;
      element.style.border = 'none';
      try {
        await html2pdf().set(opt).from(element).save();
      } catch (err) {
        console.error('PDF export error:', err);
        alert('PDF export failed — see console for details.');
      } finally {
        element.style.border = outline || '1px solid #111';
      }
    });
  </script>
</body>
</html>
