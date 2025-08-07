<?php
require_once('../connection.php');
session_start();

// Redirect if not logged in or no POST data
if (empty($_SESSION['user_id'])) {
    header('Location: index.php');
    die();
}
if (empty($_POST)) {
    header('Location: worldmap_form.php');
    die();
}

$id = intval($_POST['id'] ?? 0);
$country = $_POST['country'] ?? '';
$sanctionsArray = $_POST['sanctions'] ?? [];

// CKEditor fields
$usa_sanctions = $_POST['usa_sanctions'] ?? '';
$eu_sanctions = $_POST['eu_sanctions'] ?? '';
$uk_sanctions = $_POST['uk_sanctions'] ?? '';
$un_sanctions = $_POST['un_sanctions'] ?? '';
$canada_sanctions = $_POST['canada_sanctions'] ?? '';
$switzerland_sanctions = $_POST['switzerland_sanctions'] ?? '';

// Handle multi-source fields (convert arrays to newline strings)
function format_sources($field) {
    return isset($_POST[$field]) && is_array($_POST[$field]) ? implode("\n", array_filter($_POST[$field])) : ($_POST[$field] ?? '');
}

$usa_sanctions_source = format_sources('usa_sanctions_source');
$eu_sanctions_source = format_sources('eu_sanctions_source');
$uk_sanctions_source = format_sources('uk_sanctions_source');
$un_sanctions_source = format_sources('un_sanctions_source');
$canada_sanctions_source = format_sources('canada_sanctions_source');
$switzerland_sanctions_source = format_sources('switzerland_sanctions_source');

// Existing PDF name
$pdf_already = $_POST['pdf_already'] ?? '';
$pdf = $pdf_already;

// File upload
if (!empty($_FILES['pdf']['name'])) {
    $uploadDir = '../uploads/worldmap/';
    /* if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    } */

    $pdfName = basename($_FILES['pdf']['name']);
    $targetPath = $uploadDir . $pdfName;

    if (move_uploaded_file($_FILES['pdf']['tmp_name'], $targetPath)) {
        $pdf = $pdfName;
    } else {
        echo "File upload failed.";
        exit;
    }
}

// Sanctions handling
$sanction_status = (count($sanctionsArray) > 1) ? 'multiple' : 'single';
$sanctions = implode(',', $sanctionsArray);

// Update query
$sql = "UPDATE wp_world_map SET 
    country = ?, 
    sanctions = ?, 
    usa_sanctions = ?, 
    eu_sanctions = ?, 
    uk_sanctions = ?, 
    un_sanctions = ?, 
    switzerland_sanctions = ?, 
    canada_sanctions = ?, 
    usa_sanctions_source = ?, 
    eu_sanctions_source = ?, 
    uk_sanctions_source = ?, 
    un_sanctions_source = ?, 
    canada_sanctions_source = ?, 
    switzerland_sanctions_source = ?, 
    pdf = ?, 
    sanction_status = ?
    WHERE id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo "Prepare failed: " . $conn->error;
    exit;
}

$stmt->bind_param(
    "ssssssssssssssssi",
    $country,
    $sanctions,
    $usa_sanctions,
    $eu_sanctions,
    $uk_sanctions,
    $un_sanctions,
    $switzerland_sanctions,
    $canada_sanctions,
    $usa_sanctions_source,
    $eu_sanctions_source,
    $uk_sanctions_source,
    $un_sanctions_source,
    $canada_sanctions_source,
    $switzerland_sanctions_source,
    $pdf,
    $sanction_status,
    $id
);

if ($stmt->execute()) {
    echo 1;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
