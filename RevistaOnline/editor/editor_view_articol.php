<?php
require '../connect.php';

//verifica daca utilizatorul este logat si este editor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'editor') {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// verrica id-ul articolului
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $article_id = intval($_GET['id']);

    try {
        // Verifica daca userul este autorul articolului
        $stmt = $connect->prepare("SELECT * FROM articles WHERE id = ? AND author_id = ?");
        $stmt->bind_param("ii", $article_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $article = $result->fetch_assoc();

            //preia comentariile legate de articol
            $comment_stmt = $connect->prepare("SELECT comments.*, users.username 
                                              FROM comments 
                                              JOIN users ON comments.user_id = users.id 
                                              WHERE comments.article_id = ? 
                                              ORDER BY comments.create_date DESC");
            $comment_stmt->bind_param("i", $article_id);
            $comment_stmt->execute();
            $comments_result = $comment_stmt->get_result();

        } else {
            throw new Exception("Article not found or you don't have permission to view it.");
        }
    } catch (Exception $e) {
        echo "Error: " . htmlspecialchars($e->getMessage());
        exit();
    }
} else {
    echo "Invalid request. Article ID not provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/editor.css">
</head>
<body class="container mt-5">

    <h1><?= htmlspecialchars($article['title']) ?></h1>
    <p><strong>Category:</strong> <?= htmlspecialchars($article['category']) ?></p>

    <!-- Afiseaza imaginea articolului -->
    <?php if (!empty($article['imageUrl'])) : ?>
        <img src="../<?= htmlspecialchars($article['imageUrl']) ?>" class="img-fluid my-3" style="max-width: 500px;" alt="Article Image">
    <?php endif; ?>

    <p><?= nl2br(htmlspecialchars($article['content'])) ?></p>

    <hr>
    <h3>Comments Received</h3>
    <?php if ($comments_result->num_rows > 0) : ?>
        <?php while ($comment = $comments_result->fetch_assoc()) : ?>
            <div class="comment-box">
                <strong><?= htmlspecialchars($comment['username']) ?></strong>
                <small class="text-muted">(<?= date('d M Y, H:i', strtotime($comment['create_date'])) ?>)</small>
                <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
            </div>
        <?php endwhile; ?>
    <?php else : ?>
        <p>No comments yet.</p>
    <?php endif; ?>

    <a href="editor_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
    
</body>
</html>
