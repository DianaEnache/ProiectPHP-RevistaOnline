<?php
require '../../connect.php';

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    $result = mysqli_query($connect, "SELECT * FROM users WHERE id = $user_id");

    //verifica daca userul exista
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    } else {
        echo "User not found.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5 mb-5">

    <h1>User Details</h1>
    <div class="card p-3">
        <p><strong>ID:</strong> <?= $user['id'] ?></p>
        <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Role:</strong> <?= $user['role'] ?></p>
    </div>
    
    <a href="../../admin/dashboard.php?page=users" class="btn btn-secondary mt-3">Back to Users</a>

</body>
</html>
