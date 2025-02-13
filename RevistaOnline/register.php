<?php
require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($connect, $_POST['username']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    $role = 'user';

    $passwordPattern = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/";

    //Validare parola
    if ($password !== $confirmPassword) {
        $errorMsg = "Passwords do not match.";
    } elseif (!preg_match($passwordPattern, $password)) {
        $errorMsg = "Password must contain at least 8 characters, one uppercase letter, one number, and one special character.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO users (username, password, email, role) VALUES ('$username', '$hashedPassword', '$email', '$role')";

        if (mysqli_query($connect, $query)) {
            $successMsg = "Registration successful! <a href='login.php'>Login here</a>";
        } else {
            $errorMsg = "Error: " . mysqli_error($connect);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/similar.css">
    <script>
        //Validare formular
        function validateForm() {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirm_password").value;

            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow p-4" style="width: 350px;">
        <h2 class="text-center mb-4">Register</h2>
        
        <?php if (isset($successMsg)): ?>
            <div class="alert alert-success"><?= $successMsg ?></div>
        <?php elseif (isset($errorMsg)): ?>
            <div class="alert alert-danger"><?= $errorMsg ?></div>
        <?php endif; ?>

        <form method="POST" onsubmit="return validateForm()">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password"
                    class="form-control" 
                    required 
                    minlength="8" 
                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}" 
                    title="Password must contain at least 8 characters, one uppercase letter, one number, and one special character."
                >
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input 
                    type="password" 
                    name="confirm_password" 
                    id="confirm_password"
                    class="form-control" 
                    required 
                    minlength="8"
                >
            </div>
            <button type="submit" class="btn btn-success w-100">Register</button>
        </form>

        <div class="text-center mt-3">
            <a href="login.php">Already have an account? Login here</a>
        </div>
    </div>
</body>
</html>
