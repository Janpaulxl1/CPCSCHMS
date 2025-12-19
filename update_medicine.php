<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $brand = $_POST['brand'] ?? '';
    $name = $_POST['name'] ?? '';
    $dosage = $_POST['dosage'] ?? '';
    $instructions = $_POST['instructions'] ?? '';
    $quantity = intval($_POST['quantity'] ?? 0);
    $delivery_date = $_POST['delivery_date'] ?? '';
    $expiration_date = $_POST['expiration_date'] ?? '';
    $notes = $_POST['notes'] ?? '';

    if ($id <= 0) {
        header('Location: medication_dashboard.php?error=invalid_id');
        exit;
    }

    if ($conn) {
        $sql = "UPDATE medications SET brand = ?, name = ?, dosage = ?, instructions = ?, quantity = ?, delivery_date = ?, expiration_date = ?, notes = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssissss", $brand, $name, $dosage, $instructions, $quantity, $delivery_date, $expiration_date, $notes, $id);

        if ($stmt->execute()) {
            header('Location: medication_dashboard.php?success=updated');
        } else {
            header('Location: medication_dashboard.php?error=update_failed');
        }
        $stmt->close();
    } else {
        header('Location: medication_dashboard.php?error=db_connection');
    }
    exit;
}
?>
