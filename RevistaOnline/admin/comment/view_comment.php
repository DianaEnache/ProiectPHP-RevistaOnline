<?php
require '../../connect.php';


if (isset($_GET['id'])) {
    $comment_id = intval($_GET['id']);

    //ia comentariul si numele autorului
    $query = "SELECT comments.*, users.username AS author FROM comments JOIN users ON comments.user_id = users.id WHERE comments.id = $comment_id";
    $result = mysqli_query($connect, $query);

    //verifica daca s-a gasit comentariul
    if ($result && mysqli_num_rows($result) > 0) {
        $comment = mysqli_fetch_assoc($result);
    } else {
        echo "Comment not found.";
        exit();
    }
} else {
    echo "Invalid request. Comment ID not provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Comment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-4">

        <h1>View Comment</h1>
        <p><strong>Summary:</strong> <?= htmlspecialchars($comment['summary']) ?></p>
        <p><strong>Author:</strong> <?= htmlspecialchars($comment['author']) ?></p>
        <p><strong>Content:</strong> <?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
        <p><strong>Posted on:</strong> <?= htmlspecialchars($comment['create_date']) ?></p>

        <a href="../../admin/dashboard.php?page=comments" class="btn btn-secondary">Back to Comments</a>
        
    </div>
</body>
</html>
