<?php
require_once('../connection.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Extract all post variables safely
$country = $_POST['country'] ?? '';
$sanctions = $_POST['sanctions'] ?? [];

$usa_sanctions = $_POST['usa_sanctions'] ?? '';
$eu_sanctions = $_POST['eu_sanctions'] ?? '';
$uk_sanctions = $_POST['uk_sanctions'] ?? '';
$un_sanctions = $_POST['un_sanctions'] ?? '';
$switzerland_sanctions = $_POST['switzerland_sanctions'] ?? '';
$canada_sanctions = $_POST['canada_sanctions'] ?? '';

// Handle multiple source URLs as newline-separated strings
function formatSources($sources) {
    return isset($sources) && is_array($sources) ? implode("\n", array_filter($sources)) : '';
}

$usa_sanctions_source = formatSources($_POST['usa_sanctions_source'] ?? []);
$eu_sanctions_source = formatSources($_POST['eu_sanctions_source'] ?? []);
$uk_sanctions_source = formatSources($_POST['uk_sanctions_source'] ?? []);
$un_sanctions_source = formatSources($_POST['un_sanctions_source'] ?? []);
$canada_sanctions_source = formatSources($_POST['canada_sanctions_source'] ?? []);
$switzerland_sanctions_source = formatSources($_POST['switzerland_sanctions_source'] ?? []);

// Set custom upload path
$uploadBaseDir = __DIR__ . '/uploads/worldmap/';
$uploadBaseUrl = '../uploads/worldmap/';

// Create the directory if it doesn't exist
/* if (!file_exists($uploadBaseDir)) {
    mkdir($uploadBaseDir, 0777, true);
} */

// File upload handling
$pdf = '';
if (!empty($_FILES['pdf']['name'])) {
    $pdfName = basename($_FILES['pdf']['name']);
    $fileTmpPath = $_FILES['pdf']['tmp_name'];
    echo $destPath = $uploadBaseUrl . $pdfName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        $pdf = $pdfName;
    } else {
        echo "Failed to upload PDF.";
        exit;
    }
}

// Sanction type status
$sanction_status = count($sanctions) > 1 ? 'multiple' : 'single';
$sanctionsList = implode(',', $sanctions);

// Prepare SQL query
$query = "INSERT INTO wp_world_map (
    country, sanctions, usa_sanctions, eu_sanctions, uk_sanctions, un_sanctions, switzerland_sanctions, canada_sanctions,
    usa_sanctions_source, eu_sanctions_source, uk_sanctions_source, canada_sanctions_source, un_sanctions_source, switzerland_sanctions_source,
    pdf, sanction_status
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($query);

if (!$stmt) {
    echo "Prepare failed: " . $conn->error;
    exit;
}

$stmt->bind_param("ssssssssssssssss",
    $country,
    $sanctionsList,
    $usa_sanctions,
    $eu_sanctions,
    $uk_sanctions,
    $un_sanctions,
    $switzerland_sanctions,
    $canada_sanctions,
    $usa_sanctions_source,
    $eu_sanctions_source,
    $uk_sanctions_source,
    $canada_sanctions_source,
    $un_sanctions_source,
    $switzerland_sanctions_source,
    $pdf,
    $sanction_status
);

// Execute and respond
if ($stmt->execute()) {
    echo "1"; // success
} else {
    echo "Insert failed: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>