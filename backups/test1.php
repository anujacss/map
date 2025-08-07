<?php
$redirectUri = 'https://map.sanctionsassociation.org/backups/test1.php';
$appKey = 'ussrz07irxkrj73';  // Replace with your app key
$appSecret = 'cnxemk3i4b4f71w';  // Replace with your app secret

// Step 1: Redirect user to Dropbox Authorization URL
if (!isset($_GET['code'])) {
    $authUrl = 'https://www.dropbox.com/oauth2/authorize?client_id=' . $appKey . '&redirect_uri=' . urlencode($redirectUri) . '&response_type=code&token_access_type=offline';
    header('Location: ' . $authUrl);
    exit();
}

// Step 2: Capture the authorization code
if (isset($_GET['code'])) {
    $authorizationCode = $_GET['code'];  // Capture the authorization code from the URL

    // Step 3: Exchange the authorization code for an access token and refresh token
    $url = 'https://api.dropboxapi.com/oauth2/token';
    $data = [
        'code' => $authorizationCode,
        'grant_type' => 'authorization_code',
        'redirect_uri' => $redirectUri,
        'client_id' => $appKey,
        'client_secret' => $appSecret
    ];

    // Initialize cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

    // Execute the request
    $response = curl_exec($ch);
    curl_close($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    } else {
        // Decode the response
        $responseData = json_decode($response, true);

        // Check if access token and refresh token are returned
        if (isset($responseData['access_token']) && isset($responseData['refresh_token'])) {
            echo "Access Token: " . $responseData['access_token'];
            echo "<br>Refresh Token: " . $responseData['refresh_token'];

            // Store the access token and refresh token securely
        } else {
            echo "Error: " . print_r($responseData, true);
        }
    }
}
?>



