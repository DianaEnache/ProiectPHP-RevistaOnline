<?php
require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(mysqli_real_escape_string($connect, $_POST['username']));
    $password = trim($_POST['password']);

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($connect, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];

            //redirectioneaza utilizatorul in functie de rol
            if ($user['role'] === 'admin') {
                header("Location: admin/dashboard.php");
                exit();
            } else if ($user['role'] === 'user') {
                header("Location: home.php");
                exit();
            }else if ($user['role'] === 'editor') {
                header("Location: editor/editor_dashboard.php");
                exit();
            }
        } else {
            $errorMsg = "Password verification failed.";
        }
    } else {
        $errorMsg = "Invalid username.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/similar.css">
</head>

<body class="bg-light d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow p-4" style="width: 350px;">
        <h2 class="text-center mb-4"><strong>Login</strong></h2>

        <?php if (isset($errorMsg)): ?>
            <div class="alert alert-danger"><?= $errorMsg ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>

        </form>

        <div class="text-center mt-3">
            <a href="register.php">Don't have an account? Register here</a>
        </div>
        
    </div>
</body>
</html>
