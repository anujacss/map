<?php

$appKey = 'ussrz07irxkrj73';
$appSecret = 'cnxemk3i4b4f71w';
$redirectUri = 'https://map.sanctionsassociation.org/backups/test.php';

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    $ch = curl_init('https://api.dropboxapi.com/oauth2/token');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_USERPWD, $appKey . ':' . $appSecret);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
       'code' => $code,
        'grant_type' => 'authorization_code',
        'redirect_uri' => $redirectUri
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
	echo'<pre>';
	print_r($result);
	echo'</pre>';
    if (isset($result['access_token'])) {
        echo "✅ Access Token: " . $result['access_token'];
        // You can save this token in DB or session
    } else {
        echo "❌ Error: ";
        print_r($result);
    }
} else {
    echo "No code received.";
}


?>