<?php
session_start();
require '../connect.php';

//doar adminii pot reseta datele de analiza
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

//csrf protection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Invalid CSRF token.');
    }

//sterge datele de analiza
    $delete_query = "DELETE FROM analytics";
    if (mysqli_query($connect, $delete_query)) {
        //redirectioneaza catre pagina de administrare
        header('Location: dashboard.php?page=dashboard');
        exit();
    } else {
        echo "Error resetting analytics: " . mysqli_error($connect);
    }
} else {
    echo "Invalid request.";
    exit();
}
?>
