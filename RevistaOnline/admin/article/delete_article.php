<?php
require '../../connect.php';
session_start();

//csrf protection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Invalid CSRF token.');
    }

    //validare id
    if (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $article_id = intval($_POST['id']);

        //sterge comentariile
        $delete_comments_query = "DELETE FROM comments WHERE article_id = $article_id";
        if (!mysqli_query($connect, $delete_comments_query)) {
            echo "Error deleting comments: " . mysqli_error($connect);
            exit();
        }

        //sterge articolul
        $delete_article_query = "DELETE FROM articles WHERE id = $article_id";
        if (mysqli_query($connect, $delete_article_query)) {
            header('Location: ../../admin/dashboard.php?page=articles&delete=success');
            exit();
        } else {
            echo "Error deleting article: " . mysqli_error($connect);
        }
    } else {
        echo "Invalid request. Article ID is missing or invalid.";
    }
} else {
    echo "Invalid request method.";
    exit();
}
?>
