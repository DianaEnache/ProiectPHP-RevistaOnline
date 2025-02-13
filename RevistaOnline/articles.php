<?php
require 'connect.php';

//tracking analytics
$page = basename($_SERVER['PHP_SELF']);  
$ip_address = $_SERVER['REMOTE_ADDR'];   

// verific daca exista inregistrare pentru aceasta pagina si ip in ziua curenta
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

//search
$search = $_GET['search'] ?? '';
$search = trim($search); //elimina spatiile inutile

//query pentru a selecta articolele si autorii lor
$query = "SELECT articles.*, users.username AS author 
          FROM articles 
          JOIN users ON articles.author_id = users.id";

//daca s-a dat search
if ($search) {
    $query .= " WHERE articles.title LIKE ? OR articles.category LIKE ?";
    $stmt = $connect->prepare($query);
    $searchTerm = '%' . $search . '%';
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $query .= " ORDER BY articles.create_date DESC";
    $result = $connect->query($query);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Articles - Revista Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/articles.css">
    
</head>

<body class="d-flex flex-column min-vh-100">

    <?php include('include/navbar.php'); ?><!-- Navbar -->

    <div class="container content">
        <h1 class="text-center mb-4">Articles</h1>

        <!-- Search bar/form -->
        <form method="GET" class="input-group mb-4">
            <input type="text" name="search" class="form-control" placeholder="Search by title or category" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <!--lista articole-->
        <div>

            <?php if ($result && $result->num_rows > 0) : ?>
                <?php while ($article = $result->fetch_assoc()) : ?>
                    <div class="article-item">
                        <div class="article-content">
                            <h5><?= htmlspecialchars($article['title']) ?></h5>
                            <p><?= htmlspecialchars(substr($article['content'], 0, 150)) . '...' ?></p>
                            <div class="article-footer">
                                <strong>Author:</strong> <?= htmlspecialchars($article['author']) ?> |
                                <strong>Category:</strong> <?= htmlspecialchars($article['category']) ?> |
                                <strong>Published:</strong> <?= date('d M Y', strtotime($article['create_date'])) ?>
                            </div>
                        </div>
                        <a href="user_view_article.php?id=<?= urlencode($article['id']) ?>" class="btn btn-sm btn-primary read-more-btn">Read More</a>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="alert alert-warning">No articles found.</div>
            <?php endif; ?>

        </div>
    </div>

    <?php include('include/footer.php'); ?><!-- Footer -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
