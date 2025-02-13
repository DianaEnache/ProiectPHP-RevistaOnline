<?php
require '../connect.php';

//doar adminii pot accesa aceasta pagina
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// CSRF Protection
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

//determina pagina curenta
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

//pentru search
$search = isset($_GET['search']) ? htmlspecialchars(trim($_GET['search'])) : '';

// Securizeaza query-urile SQL
$stmt = $connect->prepare("SELECT * FROM articles WHERE title LIKE ?");
$search_param = "%$search%";
$stmt->bind_param("s", $search_param);
$stmt->execute();
$articles = $stmt->get_result();

$stmt = $connect->prepare("
    SELECT comments.*, users.username 
    FROM comments 
    JOIN users ON comments.user_id = users.id 
    WHERE comments.summary LIKE ?");

$stmt->bind_param("s", $search_param);
$stmt->execute();
$comments = $stmt->get_result();

$stmt = $connect->prepare("SELECT * FROM users WHERE username LIKE ?");
$stmt->bind_param("s", $search_param);
$stmt->execute();
$users = $stmt->get_result();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/dashboard.css">
</head>

<body>
<div class="container-fluid">
    <div class="row">

        <!-- sidebar -->
        <nav class="col-md-2 sidebar">
            <h4><strong>Admin Dashboard</strong></h4>
            <a href="dashboard.php"> Dashboard</a>
            <a href="dashboard.php?page=articles"> Articles</a>
            <a href="dashboard.php?page=comments"> Comments</a>
            <a href="dashboard.php?page=users"> Users</a>
            <a href="dashboard.php?page=settings"> Settings</a>
            <a href="logout.php" class="btn btn-danger w-100 mt-3"> Logout</a>
        </nav>

        <!--content-->
        <main class="col-md-10 p-4">
            <?php if ($page === 'dashboard') : ?>

                <h1>~ Welcome to the Admin Dashboard ~</h1>
                <?php include 'analytics.php'; ?> <!--analytics -->

            <?php elseif ($page === 'articles') : ?>

                <h1>Articles Management</h1>
                <form class="d-flex mb-3" method="GET">

                    <input type="hidden" name="page" value="articles">
                    <input class="form-control me-2" type="search" name="search" placeholder="Search articles..." value="<?= $search ?>">
                    <button class="btn btn-outline-success" type="submit">Search</button>

                </form>

                <a href="article/create_article.php" class="btn btn-primary mb-3">Create Article</a>

                <table class="table table-bordered">
                    <thead>
                        <tr><th>ID</th><th>Title</th><th>Category</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($article = $articles->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $article['id'] ?></td>
                                <td><?= htmlspecialchars($article['title']) ?></td>
                                <td><?= htmlspecialchars($article['category']) ?></td>
                                <td>

                                    <a href="article/view_article.php?id=<?= $article['id'] ?>" class="btn btn-warning btn-sm">View</a>
                                    <a href="article/update_article.php?id=<?= $article['id'] ?>" class="btn btn-info btn-sm">Edit</a>
                                    <form method="POST" action="article/delete_article.php" style="display:inline;">

                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <input type="hidden" name="id" value="<?= $article['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                    
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                    </tbody>
                </table>

            <?php elseif ($page === 'comments') : ?>

                <h1>Comments Management</h1>

                <form class="d-flex mb-3" method="GET">
                    <!-- Search form -->
                    <input type="hidden" name="page" value="comments">
                    <input class="form-control me-2" type="search" name="search" placeholder="Search comments..." value="<?= $search ?>">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>

                <table class="table table-bordered">
                    <thead>
                        <tr><th>ID</th><th>Content</th><th>User</th><th>Summary</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($comment = $comments->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $comment['id'] ?></td>
                                <td><?= htmlspecialchars($comment['comment']) ?></td>
                                <td><?= htmlspecialchars($comment['username']) ?></td>
                                <td><?= htmlspecialchars($comment['summary']) ?></td>
                                <td>
                                    <a href="comment/view_comment.php?id=<?= $comment['id'] ?>" class="btn btn-info btn-sm">View</a>
                                    <form method="POST" action="comment/delete_comment.php" style="display:inline;">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <input type="hidden" name="id" value="<?= $comment['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

            <?php elseif ($page === 'users') : ?>

                <h1>Users Management</h1>
                <!-- Search form -->
                <form class="d-flex mb-3" method="GET">
                    <input type="hidden" name="page" value="users">
                    <input class="form-control me-2" type="search" name="search" placeholder="Search users..." value="<?= $search ?>">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>

                <table class="table table-bordered">
                    <thead>
                        <tr><th>ID</th><th>Username</th><th>Email</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $users->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td>
                                    <a href="user/view_user.php?id=<?= $user['id'] ?>" class="btn btn-info btn-sm">View</a>
                                    <form method="POST" action="user/delete_user.php" style="display:inline;">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

            <?php elseif ($page === 'settings') : ?>
                <h1>Settings</h1>
                <p>Coming soon...</p>

            <?php else : ?>
                <h1>Page Not Found</h1>
            <?php endif; ?>
        </main>

    </div>
    
</div>
</body>
</html>
