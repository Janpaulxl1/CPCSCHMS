<?php
session_start();
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
require_once 'db.php';

$result_emerg = false;

if ($conn) {
    // Get total counts for summary
    $sql_total = "SELECT COUNT(*) as total FROM notifications WHERE message LIKE 'Emergency%'";
    $total_result = $conn->query($sql_total);
    $total_emergencies = $total_result ? $total_result->fetch_assoc()['total'] : 0;

    $sql_resolved = "SELECT COUNT(*) as resolved FROM notifications WHERE message LIKE 'Emergency%' AND action_taken IS NOT NULL AND action_taken != '' AND action_taken != 'Acknowledged'";
    $resolved_result = $conn->query($sql_resolved);
    $resolved = $resolved_result ? $resolved_result->fetch_assoc()['resolved'] : 0;

$pending = $total_emergencies - $resolved;

    // Get recent 30 for table
    $sql_emerg = "SELECT id, message, created_at, action_taken FROM notifications WHERE message LIKE 'Emergency%' ORDER BY created_at DESC LIMIT 30";
    $result_emerg = $conn->query($sql_emerg);
}
?>

<script>
const totalEmergencies = <?php echo json_encode($total_emergencies); ?>;
const resolvedEmergencies = <?php echo json_encode($resolved); ?>;
const pendingEmergencies = <?php echo json_encode($pending); ?>;
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CPC Clinic</title>
    <link rel="icon" type="image" href="images/favicon.jpg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Professional styling: Modern, clean design with subtle shadows and improved spacing */
        body { background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); min-height: 100vh; font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; color: #374151; }
        header { background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); box-shadow: 0 4px 20px rgba(220, 38, 38, 0.15); width: 100vw; position: relative; left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw; padding: 15px 30px; color: white; }
        .header-content { display: flex; justify-content: space-between; align-items: center; max-width: 1400px; margin: 0 auto; }
        .header-left { display: flex; align-items: center; gap: 20px; flex: 1; justify-content: center; }
        .header-title { font-size: 28px; font-weight: 700; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header-right { display: flex; align-items: center; gap: 20px; justify-content: flex-end; margin-right: 20px; }
        .profile-avatar { width: 50px; height: 50px; border-radius: 50%; border: 3px solid rgba(255,255,255,0.8); object-fit: cover; box-shadow: 0 4px 8px rgba(0,0,0,0.1); transition: transform 0.3s ease; }
        .profile-avatar:hover { transform: scale(1.05); }
        .profile-name { font-size: 18px; font-weight: 600; margin: 0; }

        #mainShell { max-width: 1400px; margin: 30px auto; padding: 0 30px; height: calc(100vh - 120px); display: flex; gap: 30px; flex-wrap: wrap; }
        .main-left { flex: 1; min-width: 1000px; max-width: calc(100vw - 400px); height: 100%; overflow-y: auto; padding: 0; background: transparent; border-radius: 0; box-shadow: none; }
        .main-right { flex: 0 0 380px; min-width: 380px; height: 100%; }
        #emergencyCard { background: white; border: none; border-radius: 16px; box-shadow: 0 8px 32px rgba(0,0,0,0.08); transition: all 0.4s ease; width: 100%; overflow: hidden; }
        #emergencyCard:hover { box-shadow: 0 12px 48px rgba(0,0,0,0.12); transform: translateY(-2px); }
        #emergencyCard h3 { color: white; font-size: 22px; font-weight: 700; margin: 0; padding: 20px 30px; background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); border-bottom: none; border-radius: 16px 16px 0 0; }
        /* Professional Table Design */
        #emergencyTable { width: 100%; border-collapse: collapse; font-size: 14px; border: none; border-radius: 0; overflow: hidden; box-shadow: none; table-layout: fixed; }
        #emergencyTable th { background: #dc2626; color: white; font-weight: 700; padding: 12px 8px; text-align: left; border-bottom: 1px solid #dc2626; border-right: 1px solid #dc2626; }
        #emergencyTable th:last-child { border-right: none; }
        #emergencyTable td:nth-child(1) { width: 15%; } /* Date & Time */
        #emergencyTable td:nth-child(2) { width: 15%; } /* Reported By */
        #emergencyTable td:nth-child(3) { width: 10%; } /* Incident */
        #emergencyTable td:nth-child(4) { width: 25%; white-space: normal; word-wrap: break-word; } /* Details */
        #emergencyTable td:nth-child(5) { width: 20%; white-space: normal; word-wrap: break-word; } /* Action Taken */
        #emergencyTable td:nth-child(6) { width: 15%; } /* Status */
        #emergencyTable td { padding: 12px 8px; border-bottom: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb; vertical-align: top; overflow: hidden; text-overflow: ellipsis; word-wrap: break-word; }
        #emergencyTable td:last-child { border-right: none; }
        #emergencyTable tr:nth-child(even) { background: #f3f4f6; }
        #emergencyTable tr:hover { background: #e5e7eb; cursor: pointer; }
        .status-badge { padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 500; }
        .status-resolved { background: #d1fae5; color: #059669; }
        .status-pending { background: #fef3c7; color: #d97706; }
        th i, h3 i { margin-right: 0.5rem; }
        /* Modal styles */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); }
        .modal-content { background-color: #fefefe; margin: 5% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 600px; border-radius: 8px; }
        .close { color: #aaa; float: right; font-size: 28px; font-weight: bold; }
        .close:hover, .close:focus { color: black; text-decoration: none; cursor: pointer; }
        #actionModal .modal-content { max-width: 500px; background: white; border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,0.08); border: none; padding: 0 30px 30px 30px; position: relative; }
        #actionModal h3 { background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; padding: 20px 30px; margin: -20px -30px 20px -30px; border-radius: 12px 12px 0 0; font-weight: 700; font-size: 22px; text-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        #actionModal .close { color: white; font-size: 28px; position: absolute; right: 15px; top: 15px; cursor: pointer; z-index: 1; }
        #actionModal .close:hover { color: rgba(255,255,255,0.8); }
        #actionModal .action-input { margin-bottom: 20px; font-size: 14px; }
        #actionModal #saveActionBtn { background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; font-size: 16px; cursor: pointer; transition: all 0.3s ease; width: 100%; }
        #actionModal #saveActionBtn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3); }
        /* Sidebar hamburger menu */
        #sidebar { position: fixed; top: 0; left: -280px; width: 280px; height: 100vh; background: #ffffff; box-shadow: 2px 0 10px rgba(0,0,0,0.1); transition: left 0.3s ease; z-index: 1000; padding: 1rem; overflow-y: auto; border-right: 1px solid #e5e7eb; }
        #sidebar.open { left: 0; }
        #sidebar h2 { color: #b71c1c; font-weight: 700; margin-bottom: 1.5rem; font-size: 1.2rem; padding-bottom: 0.5rem; border-bottom: 1px solid #e5e7eb; }
        #sidebar a { display: block; padding: 0.75rem 1rem; margin-bottom: 0.5rem; color: #374151; text-decoration: none; border-radius: 6px; transition: all 0.3s ease; font-size: 0.95rem; }
        #sidebar a:hover { background: #dc2626; color: white; transform: translateX(5px); font-weight: bold; }
        #sidebar summary { color: #374151; font-weight: 600; cursor: pointer; }
        #sidebar details { margin-bottom: 1rem; }
        #sidebar ul { list-style: none; padding-left: 1rem; margin-top: 0.5rem; }
        #sidebar li { margin-bottom: 0.25rem; }
        #sidebar a i { margin-right: 0.5rem; width: 20px; }
        /* Overlay for sidebar */
        #sidebarOverlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.5); opacity: 0; visibility: hidden; transition: all 0.3s ease; z-index: 999; }
        #sidebarOverlay.open { opacity: 1; visibility: visible; }
        /* Responsive: stack on mobile */
        @media (max-width: 768px) { #mainShell { flex-direction: column; } .main-left, .main-right { min-width: 100%; max-width: 100%; } }
    </style>
</head>
<body>

<!-- Header - Professional Design -->
<header>
<div class="header-content">
<button id="menuBtn" class="text-2xl font-bold cursor-pointer hover:bg-white hover:bg-opacity-10 rounded p-2 transition absolute left-5 top-1/2 -translate-y-1/2 z-10">☰</button>
<div class="header-left">
<h1 class="header-title"> School Clinic Management System</h1>
</div>
<div class="header-right">
<img src="images/nurse.jpg" alt="Nurse Profile" class="profile-avatar">
<div>
<span class="profile-name">Mrs. Lorefe Verallo</span>
<p style="margin: 0; font-size: 12px; opacity: 0.8;">Nurse Administrator</p>
</div>
</div>
</div>

<!-- Sidebar -->
<nav id="sidebar">
<a href="nurse.php"><i class="fas fa-arrow-left"></i> Back</a>
<h2>File Clinic Explorer</h2>
<details>
    <summary>📁 Documents</summary>
    <ul>
    <li><a href="physical_assessment.php"><i class="fas fa-file-medical"></i> Student Physical Assessment Form</a></li>
    <li><a href="health_service_report.php"><i class="fas fa-chart-bar"></i> Health Service Utilization Report</a></li>
    </ul>
</details>
<a href="medication_dashboard.php"><i class="fas fa-pills"></i> Medical Supplies</a>
<a href="studentfile_dashboard.php"><i class="fas fa-folder-open"></i> Student File Dashboard</a>
<a href="Student_visitlogs.php"><i class="fas fa-history"></i> Student Visit Logs</a>
<a href="appointment_history.php"><i class="fas fa-calendar-alt"></i> Appointment History</a>
<a href="emergency_reports.php"><i class="fas fa-bell"></i> Emergency Reports</a>
<a href="convert_responder.php"><i class="fas fa-user-shield"></i> Emergency Responder</a>
<a href="responder_status.php"><i class="fas fa-user-shield"></i> Responder Status</a>
<a href="nurse_reset_password.php"><i class="fas fa-key"></i> Reset User Password</a>
<a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</nav>
</header>

<!-- Sidebar Overlay -->
<div id="sidebarOverlay"></div>

<div id="mainShell">
<div class="main-left">
<div id="emergencyCard">
<h3 class="text-xl font-bold text-white mb-4 flex items-center justify-between"><i class="fas fa-exclamation-triangle"></i>Emergency Report Log <button id="summaryBtn" class="text-white hover:text-gray-200 transition-colors"><i class="fas fa-question-circle text-lg"></i></button></h3>
<div id="emergencies" class="overflow-x-auto bg-white">
<table id="emergencyTable">
<thead>
<tr>
<th class="text-left"><i class="fas fa-calendar-alt"></i> Date & Time</th>
<th class="text-left"><i class="fas fa-user"></i> Reported By</th>
<th class="text-left"><i class="fas fa-exclamation-circle"></i> Incident</th>
<th class="text-left"><i class="fas fa-info-circle"></i> Details</th>
<th class="text-left"><i class="fas fa-tools"></i> Action Taken</th>
<th class="text-left"><i class="fas fa-chart-bar"></i> Status</th>
</tr>
</thead>
<tbody id="emergencyTableBody">
<?php if ($result_emerg && $result_emerg->num_rows > 0): ?>
<?php while($row = $result_emerg->fetch_assoc()): ?>
<?php
$datetime = date('M d, Y h:i A', strtotime($row['created_at']));
$reporter = "Unknown";
if (preg_match('/by\s+([A-Za-z\s]+)/', $row['message'], $matches)) {
    $reporter = htmlspecialchars(trim($matches[1]));
}
$incident = "Emergency";
$details = htmlspecialchars($row['message']);
$action_taken = htmlspecialchars($row['action_taken'] ?? '');
$display_action = ($action_taken === 'Acknowledged') ? '' : $action_taken;
$statusClass = (!is_null($row['action_taken']) && $row['action_taken'] !== '' && $row['action_taken'] !== 'Acknowledged') ? 'status-resolved' : 'status-pending';
$statusText = (!is_null($row['action_taken']) && $row['action_taken'] !== '' && $row['action_taken'] !== 'Acknowledged') ? 'Resolved' : 'Pending';
?>
<tr id="row-<?php echo $row['id']; ?>" class="hover:bg-gray-50">
<td><?php echo $datetime; ?></td>
<td><?php echo $reporter; ?></td>
<td><?php echo $incident; ?></td>
<td><?php echo $details; ?></td>
<td>
<div id="action-<?php echo $row['id']; ?>" class="mb-1" data-original-action="<?php echo htmlspecialchars($row['action_taken'] ?? ''); ?>"><?php echo $display_action; ?></div>
<?php if (empty($row['action_taken']) || $row['action_taken'] == 'Acknowledged'): ?>
<i class="fas fa-edit edit-action-icon text-blue-500 cursor-pointer text-lg" onclick="openActionModal(<?php echo $row['id']; ?>)"></i>
<?php endif; ?>
</td>
<td><span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span></td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr><td colspan="6" class="px-6 py-8 text-center text-gray-500 text-lg">No emergency reports found.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
</div>
</div>
</div>

<!-- Notification Drawer (simplified, as this page may not need full notifications) -->
<div id="notificationDrawer" class="fixed top-0 right-0 w-full md:w-96 h-full bg-white shadow-lg z-50 transform translate-x-full transition-transform duration-300 rounded-l-3xl border-l-4 border-red-500 overflow-auto">
<div class="bg-red-500 text-white flex items-center p-4">
<button onclick="closeDrawer()" class="mr-2 text-xl font-bold">&larr;</button>
<h2 class="text-lg font-semibold">Notifications</h2>
</div>
<div id="drawerContent" class="p-4 space-y-4">
<p class="text-gray-500">No new notifications.</p>
</div>
</div>

<!-- Modal for Action Taken -->
<div id="actionModal" class="modal">
<div class="modal-content">
<span class="close" onclick="closeActionModal()">&times;</span>
<h3>Add/Edit Action Taken</h3>
<textarea id="actionTextarea" class="action-input w-full border border-gray-300 rounded px-3 py-2" rows="4" placeholder="Describe the action taken..."></textarea>
<button id="saveActionBtn">Save Action</button>
</div>
</div>

<!-- Summary Modal -->
<div id="summaryModal" class="modal">
<div class="modal-content" style="max-width: 400px; background: white; border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,0.08); border: none; padding: 0; position: relative;">
<span class="close" onclick="closeSummaryModal()" style="color: white; font-size: 28px; position: absolute; right: 15px; top: 15px; cursor: pointer; z-index: 1;">&times;</span>
<h3 style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; padding: 20px 50px 20px 30px; margin: 0; border-radius: 12px 12px 0 0; font-weight: 700; font-size: 22px; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Emergency Summary</h3>
<div id="summaryContent" style="padding: 30px 30px 20px 30px; text-align: center;">
    <p style="color: #6b7280; margin-bottom: 15px; font-size: 16px;"><i class="fas fa-exclamation-triangle text-red-500 mr-2"></i><strong>Total Emergencies:</strong> <span id="totalCount"></span></p>
    <p style="color: #6b7280; margin-bottom: 15px; font-size: 16px;"><i class="fas fa-check-circle text-green-500 mr-2"></i><strong>Resolved:</strong> <span id="resolvedCount"></span></p>
    <p style="color: #6b7280; font-size: 16px;"><i class="fas fa-clock text-yellow-500 mr-2"></i><strong>Pending:</strong> <span id="pendingCount"></span></p>
</div>
</div>
</div>

<script>
let currentActionId = null;

document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle
    document.getElementById('menuBtn').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('open');
        overlay.classList.toggle('open');
    });

    document.getElementById('sidebarOverlay').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
    });

    // Summary modal
    document.getElementById('summaryBtn').addEventListener('click', function() {
        document.getElementById('totalCount').textContent = totalEmergencies;
        document.getElementById('resolvedCount').textContent = resolvedEmergencies;
        document.getElementById('pendingCount').textContent = pendingEmergencies;
        document.getElementById('summaryModal').style.display = 'block';
    });
});



function openActionModal(id) {
    currentActionId = id;
    const currentAction = document.getElementById(`action-${id}`).textContent;
    document.getElementById('actionTextarea').value = currentAction;
    document.getElementById('actionModal').style.display = 'block';
}

function closeActionModal() {
    document.getElementById('actionModal').style.display = 'none';
    currentActionId = null;
}

function closeSummaryModal() {
    document.getElementById('summaryModal').style.display = 'none';
}

async function saveAction() {
    const action = document.getElementById('actionTextarea').value.trim();
    if (!currentActionId) return;

    try {
        const response = await fetch('update_action_taken.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: currentActionId, action_taken: action })
        });

        const result = await response.json();
        if (result.success) {
            const actionDiv = document.getElementById(`action-${currentActionId}`);
            actionDiv.textContent = action;
            actionDiv.dataset.originalAction = action; // Update original for future edits if needed

            // Hide edit icon if action is now resolved (non-empty and not Acknowledged)
            const row = actionDiv.closest('tr');
            const editIcon = row.querySelector('.edit-action-icon');
            if (action.trim() !== '' && action !== 'Acknowledged') {
                if (editIcon) editIcon.style.display = 'none';
            }

            // Update status
            const statusSpan = row.querySelector('.status-badge');
            if (action.trim() !== '' && action !== 'Acknowledged') {
                statusSpan.textContent = 'Resolved';
                statusSpan.className = 'status-badge status-resolved';
            } else {
                statusSpan.textContent = 'Pending';
                statusSpan.className = 'status-badge status-pending';
            }
            closeActionModal();
        } else {
            alert('Failed to save action: ' + result.message);
        }
    } catch (error) {
        alert('Error saving action: ' + error.message);
    }
}

document.getElementById('saveActionBtn').addEventListener('click', saveAction);
</script>

</body>
</html>
