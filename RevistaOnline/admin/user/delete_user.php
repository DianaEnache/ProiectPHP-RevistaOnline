<?php
require '../../connect.php';
session_start();

//csrf protection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Invalid CSRF token.');
    }

    //valideaza id
    if (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $user_id = intval($_POST['id']);

        //verifica rolul utilizatorului
        $check_role_query = "SELECT role FROM users WHERE id = $user_id";
        $result = mysqli_query($connect, $check_role_query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            //previne stergerea utilizatorilor admin
            if ($user['role'] === 'admin') {
                echo "Error: Admin users cannot be deleted.";
                exit();
            }
        } else {
            echo "Error: User not found.";
            exit();
        }

        // sterge comentariile utilizatorului
        $delete_comments_query = "DELETE FROM comments WHERE user_id = $user_id";
        if (!mysqli_query($connect, $delete_comments_query)) {
            echo "Error deleting comments: " . mysqli_error($connect);
            exit();
        }

        // sterge articolele utilizatorului
        $delete_articles_query = "DELETE FROM articles WHERE author_id = $user_id";
        if (!mysqli_query($connect, $delete_articles_query)) {
            echo "Error deleting articles: " . mysqli_error($connect);
            exit();
        }

        // sterge utilizatorul
        $delete_user_query = "DELETE FROM users WHERE id = $user_id";
        if (mysqli_query($connect, $delete_user_query)) {
            header('Location: ../../admin/dashboard.php?page=users&delete=success');
            exit();
        } else {
            echo "Error deleting user: " . mysqli_error($connect);
        }
    } else {
        echo "Invalid request. User ID is missing or invalid.";
    }
} else {
    echo "Invalid request method.";
    exit();
}
?>
