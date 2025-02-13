<?php
require '../../connect.php';
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Invalid CSRF token.');
    }

   
    if (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $comment_id = intval($_POST['id']);

       //sterge comentariile
        $query = "DELETE FROM comments WHERE id = $comment_id";
        if (mysqli_query($connect, $query)) {
            header('Location: ../../admin/dashboard.php?page=comments&delete=success');
            exit();
        } else {
            echo "Error deleting comment: " . mysqli_error($connect);
        }
    } else {
        echo "Invalid request. Comment ID is missing or invalid.";
    }
} else {
    echo "Invalid request method.";
    exit();
}
?>
