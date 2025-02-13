<?php
require 'connect.php';

//tracking analytics
$page = basename($_SERVER['PHP_SELF']);  
$ip_address = $_SERVER['REMOTE_ADDR'];   

// veriiic daca exista inregistrare pentru aceasta pagina si ip in ziua curenta
$check_query = "SELECT id FROM analytics WHERE page = ? AND ip_address = ? AND visit_date >= CURDATE()";
$stmt = $connect->prepare($check_query);
$stmt->bind_param("ss", $page, $ip_address);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) { // insereaza daca nu exista inregistrare 
    $insert_query = "INSERT INTO analytics (page, ip_address) VALUES (?, ?)";
    $stmt = $connect->prepare($insert_query);
    $stmt->bind_param("ss", $page, $ip_address);
    $stmt->execute();
}
$stmt->close();
//---------------------------------------------

//previne sql injection
$stmt = $connect->prepare("
    SELECT articles.*, users.username AS author 
    FROM articles 
    JOIN users ON articles.author_id = users.id 
    ORDER BY articles.create_date DESC 
    LIMIT 3
");

$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Revista Online - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/home.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php include('include/navbar.php'); ?> <!-- navbar -->

    <div class="container mt-4 content">

        <h1 class="text-center mb-4">Latest Articles</h1>
        <div class="row">

            <?php while ($article = $result->fetch_assoc()) : ?>
                <div class="col-md-4 d-flex">

                    <div class="card shadow-sm flex-fill">
                        <?php
                        $imagePath = htmlspecialchars($article['imageUrl']);
                        $imageFullPath = __DIR__ . '/' . $imagePath;

                        // Verifica daca exista imaginea
                        if (!empty($imagePath) && file_exists($imageFullPath)) :
                        ?>
                            <img src="<?= htmlspecialchars($imagePath) ?>" class="card-img-top" alt="Article Image">
                        <?php else : ?>
                            <img src="uploads/articles/default.jpg" class="card-img-top" alt="Placeholder Image">
                        <?php endif; ?>

                        
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="card-title"><?= htmlspecialchars($article['title']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars(substr($article['content'], 0, 100)) . '...' ?></p>
                            </div>
                            <div class="author-date">
                                <strong>Author:</strong> <?= htmlspecialchars($article['author']) ?><br>
                                <strong>Published:</strong> <?= date('d M Y', strtotime($article['create_date'])) ?>
                            </div>

                            <a href="user_view_article.php?id=<?= urlencode($article['id']) ?>" class="btn btn-primary mt-2">Read More</a>
                        </div>

                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- read more articles -->
        <div class="text-center mt-5">
            <h3>Want to Read More?</h3>
            <p>Discover more exciting articles, tips, and stories!</p>
            <a href="articles.php" class="btn btn-success btn-lg">Click Here to Explore More Articles</a>
        </div>
    </div>

    <?php include('include/footer.php'); ?> <!-- footer -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
