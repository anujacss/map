<?php
require_once('function.php');
// If no code is received, initiate the authorization flow
$redirectUri = 'https://map.sanctionsassociation.org/backups/database_export.php';
$host = 'localhost';      // Your DB host
$dbname = 'acssmap_map';    // Database name
$user = 'acssmap_mp';  // Database username
$pass = 'Rko6FO+#}S!d5T4@';  // Database password

// Directory where you want to save the backup on the server
$backupDir = __DIR__ . '/database';
if (!file_exists($backupDir)) {
    mkdir($backupDir, 0755, true);
}

$filename = $dbname . '_' . date("Y-m-d_H-i-s") . '.sql';
$filepath = $backupDir . '/' . $filename;

// Prepare mysqldump command
$command = "mysqldump -h $host -u $user -p$pass $dbname > \"$filepath\"";

// Execute the command
$output = null;
$return_var = null;
exec($command, $output, $return_var);

// Check if successful
if ($return_var === 0 && file_exists($filepath)) {
    echo "✅ Database exported successfully to: $filepath";
	$dropbox_upload = dropbox_upload($filename, 'a-KuVEYAKmoAAAAAAAAAAVBAf-YTNU4Bly1-KDQ2LBGF06SxSY_WQTyWzO2MVAlE');
	if ($dropbox_upload === 200) {
		unlink($filepath);
	}
} else {
    echo "❌ Failed to export database. Check permissions and credentials.";
}
?>


