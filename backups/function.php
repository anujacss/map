<?php
// refresh_access_token
function refresh_access_token($refresh_token) {
    $client_id = 'ussrz07irxkrj73';  // Your app key
    $client_secret = 'cnxemk3i4b4f71w';  // Your app secret

    // Dropbox API token refresh URL
    $url = 'https://api.dropboxapi.com/oauth2/token';

    // Prepare the POST data
    $data = [
        'grant_type' => 'refresh_token',
        'refresh_token' => $refresh_token,
        'client_id' => $client_id,
        'client_secret' => $client_secret
    ];

    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

    // Execute the cURL request
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the response
    $response_data = json_decode($response, true);

    if (isset($response_data['access_token'])) {
        // Return the new access token
        return $response_data['access_token'];
    } else {
        // Handle error (e.g., invalid refresh token)
        throw new Exception('Error refreshing access token: ' . json_encode($response_data));
    }
}

// Dropbox Upload
function dropbox_upload($filename, $refresh_token) {
    // Step 1: Refresh the access token if necessary
    try {
        $token = refresh_access_token($refresh_token);  // Get a new access token using the refresh token
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return;
    }

    // Step 2: Define file paths
    $localPath = '/home/acssmap/public_html/backups/database/' . $filename;
    $dropboxPath = '/Map-database/' . $filename; // Path in Dropbox

    // Open the local file for reading
    $fp = fopen($localPath, 'rb');
    $size = filesize($localPath);

    // Set up the headers for the cURL request
    $cheaders = array(
        'Authorization: Bearer ' . $token,
        'Content-Type: application/octet-stream',
        'Dropbox-API-Arg: {"path": "' . $dropboxPath . '", "mode": "add", "autorename": true}'
    );

    // Initialize cURL session for file upload
    $ch = curl_init('https://content.dropboxapi.com/2/files/upload');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $cheaders);
    curl_setopt($ch, CURLOPT_PUT, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_INFILE, $fp);
    curl_setopt($ch, CURLOPT_INFILESIZE, $size);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the cURL request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
 return $httpCode;

    // Close the cURL session and file pointer
    curl_close($ch);
    fclose($fp);
}

?>