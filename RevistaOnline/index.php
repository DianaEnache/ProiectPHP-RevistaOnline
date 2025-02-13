<?php
session_start();

// redirectioneaza utilizatorul catre pagina home.php daca acesta este deja autentificat
if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit();
}

//Csrf protection
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Revista Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/index_1.css">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
    <div class="card">
        <h1 class="mb-4"><strong>Welcome to RevistaOnline</strong></h1>
        <p class="mb-4">Join our community to read and share amazing articles.</p>
        <div class="d-grid gap-3">
            <a href="login.php" class="btn btn-custom">Login</a>
            <a href="register.php" class="btn btn-outline-primary">Register</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-..." crossorigin="anonymous"></script>
</body>
</html>
