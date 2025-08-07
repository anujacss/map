<?php
require_once('function.php'); 

$sourceDir = '/home/acssmap/public_html/';
$filename = 'backup_' . date('Ymd_His') . '.zip';
$zipFile = '/home/acssmap/public_html/backups/database/'.$filename;
$redirectUri = 'https://map.sanctionsassociation.org/backups/create_zip.php';

if (zipFolder($sourceDir, $zipFile)) {
    echo "ZIP file created successfully: " . $zipFile;
	//$dropbox_upload = dropbox_upload($filename);
	$dropbox_upload = dropbox_upload($filename, 'DGLbxt2OnSsAAAAAAAAAARmzzOy8w5nweR57LX15hB85866TtK4rZxiQDVMS-jFu');
	if ($dropbox_upload === 200) {
		unlink($filepath);
	}
} else {
    echo "Failed to create ZIP file.";
}
function zipFolder($source, $zipFile) {
    $zip = new ZipArchive();
    if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
        return false;
    }

    $source = realpath($source);

    if (!is_dir($source)) {
        return false;
    }

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($files as $file) {
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($source) + 1);

        if ($file->isDir()) {
            $zip->addEmptyDir($relativePath);
        } else {
            $zip->addFile($filePath, $relativePath);
        }
    }

    return $zip->close();
}

?>