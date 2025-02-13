<?php

require 'connect.php';

//ia id-ul articolului
if (isset($_GET['id'])) {
    $article_id = intval($_GET['id']);

    $query = "SELECT articles.*, users.username AS author 
              FROM articles 
              JOIN users ON articles.author_id = users.id 
              WHERE articles.id = $article_id";
    $result = mysqli_query($connect, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $article = mysqli_fetch_assoc($result);
    } else {
        echo "Article not found.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment']) && isset($_POST['summary'])) {
    $user_id = $_SESSION['user_id'] ?? null;
    $comment = mysqli_real_escape_string($connect, $_POST['comment']);
    $summary = mysqli_real_escape_string($connect, $_POST['summary']);

    if ($user_id && $comment && $summary) {
        $comment_query = "INSERT INTO comments (article_id, user_id, comment, summary, create_date) 
                          VALUES ('$article_id', '$user_id', '$comment', '$summary', NOW())";
        mysqli_query($connect, $comment_query);
    }
}

// ia toate comentariile pentru articolul curent
$comments_query = "SELECT comments.*, users.username AS author 
                   FROM comments 
                   JOIN users ON comments.user_id = users.id 
                   WHERE comments.article_id = $article_id 
                   ORDER BY comments.create_date DESC";
$comments_result = mysqli_query($connect, $comments_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/user_view_article.css">


<style>
    .article-header {
        height: 300px;
        background: url('<?= htmlspecialchars($article['imageUrl']) ?>') no-repeat center center;
        background-size: cover;
    }
</style>

</head>

<body>
    <?php include('include/navbar.php'); ?>

   
    <?php if ($article['imageUrl']) : ?>
        <div class="article-header"></div>
    <?php endif; ?>

    <div class="container mt-4 content">
        <h1><?= htmlspecialchars($article['title']) ?></h1>
        <p><strong>Author:</strong> <?= htmlspecialchars($article['author']) ?></p>
        <p><strong>Published on:</strong> <?= date('d M Y, H:i', strtotime($article['create_date'])) ?></p>
        <p><strong>Content:</strong> <?= nl2br(htmlspecialchars($article['content'])) ?></p>
        <p><strong>Category:</strong> <?= htmlspecialchars($article['category']) ?></p>

        
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#commentsModal">Read Comments</button>

        <button class="btn btn-primary" onclick="history.back()">Back</button>

        
        <form method="POST" class="mt-4">
            <input type="text" name="summary" class="form-control mb-2" placeholder="Summary" required>
            <textarea name="comment" class="form-control" rows="3" placeholder="Write a comment..." required></textarea>
            <button type="submit" class="btn btn-success mt-2 ">Post Comment</button>
        </form>
    </div>

    <!-- Comentarii -->
    <div class="modal fade" id="commentsModal" tabindex="-1" aria-labelledby="commentsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentsModalLabel">Comments</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if (mysqli_num_rows($comments_result) > 0): ?>
                        <?php while ($comment = mysqli_fetch_assoc($comments_result)) : ?>
                            <div class="comment-box">
                                    <strong><?= htmlspecialchars($comment['author']) ?></strong>
                                    <small class="text-muted"><?= date('d M Y, H:i', strtotime($comment['create_date'])) ?></small>
                                    <p><strong>Summary:</strong> <?= htmlspecialchars($comment['summary']) ?></p>
                                    <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                            </div>
                        <?php endwhile; ?>

                    <?php else: ?>
                        <p>No comments yet. Be the first to comment!</p>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Back to Article</button>
                </div>
            </div>
        </div>
    </div>

    <?php include('include/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
