<?php
session_start();

if (isset($_POST['submit'])) {
    require_once('../connection.php');

    // Validate and sanitize input
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Prepare statement to prevent SQL Injection
    $stmt = $conn->prepare("SELECT id FROM user WHERE email = ? AND password = ?");
    $hashed_password = md5($password);
    $stmt->bind_param("ss", $email, $hashed_password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        header('Location: worldmap_list.php');
        exit;
    } else {
        echo "Incorrect username or password.";
    }
}
?>
<link href="https://map.sanctionsassociation.org/asset/css/backend_style.css" rel="stylesheet" />
<div class="form_grid">
<div class="login_form">
<h2>Login</h2>
    <form method="post">
        <div class="form-row">
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="form-row">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="form-row">
            <input class="btn_form" type="submit" name="submit" value="Login">
        </div>
    </form>
</div>
	</div>
