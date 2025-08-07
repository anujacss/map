<?php
require_once('../connection.php');
session_start();

$id = $_POST['id'] ?? null;

if ($id !== null && is_numeric($id)) {
    $stmt = $conn->prepare("DELETE FROM wp_world_map WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid or missing ID.";
}
?>
