<?php
require '../connect.php';

// ddoar editorii pot sterge articole
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'editor') {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $article_id = intval($_GET['id']);

    // Verificam daca userul este autorul articolului
    $check_stmt = $connect->prepare("SELECT * FROM articles WHERE id = ? AND author_id = ?");
    $check_stmt->bind_param("ii", $article_id, $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Se sterg comentariile legate de articol
        $delete_comments_stmt = $connect->prepare("DELETE FROM comments WHERE article_id = ?");
        $delete_comments_stmt->bind_param("i", $article_id);
        $delete_comments_stmt->execute();
        $delete_comments_stmt->close();

        //se sterge articolul
        $delete_stmt = $connect->prepare("DELETE FROM articles WHERE id = ?");
        $delete_stmt->bind_param("i", $article_id);

        //verifica daca s-a sters articolul
        if ($delete_stmt->execute()) {
            header('Location: editor_dashboard.php');
            exit();
        } else {
            echo "Error deleting article: " . $connect->error;
        }

        $delete_stmt->close();
    } else {
        echo "You do not have permission to delete this article.";
    }

    $check_stmt->close();
} else {
    echo "Invalid request. Article ID not provided.";
}
?>
