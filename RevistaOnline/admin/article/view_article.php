<?php
require '../../connect.php';


if (isset($_GET['id'])) {
    $article_id = intval($_GET['id']); 

//selecteaza articolul si autorul acestuia
    $query = "SELECT articles.*, users.username AS author 
              FROM articles 
              JOIN users ON articles.author_id = users.id 
              WHERE articles.id = $article_id";

    $result = mysqli_query($connect, $query);

    //verifica daca s-a gasit articolul
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">
<!--<h1>View Article</h1>-->
    <h1><?= htmlspecialchars($article['title']) ?></h1>
    <p><strong>Author:</strong> <?= htmlspecialchars($article['author']) ?></p>
    <p><strong>Published on:</strong> <?= htmlspecialchars($article['create_date']) ?></p>
    <p><strong>Content :</strong> <?= nl2br(htmlspecialchars($article['content'])) ?></p>
    <p><strong>Category:</strong> <?= htmlspecialchars($article['category']) ?></p>

    <?php if ($article['imageUrl']) : ?>
        <img src="../../<?= $article['imageUrl'] ?>" class="img-fluid my-3" style="max-width: 500px;" alt="Article Image">
    <?php endif; ?>

    <p></p>
    <button class="btn btn-primary" onclick="history.back()">Back</button>
    <p></p>
    
</body>
</html>
