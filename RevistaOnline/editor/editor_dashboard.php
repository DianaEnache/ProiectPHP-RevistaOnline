<?php
require '../connect.php';

//doar editorii pot accesa aceasta pagina
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'editor') {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

//search functionlitate
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Securizeaza query-urile SQL pentru a preveni SQL Injection
$stmt = $connect->prepare("SELECT * FROM articles WHERE author_id = ? AND title LIKE ?");
$searchParam = '%' . $search . '%';
$stmt->bind_param("is", $user_id, $searchParam);

$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Editor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/dashboard.css">
</head>

<body>
<div class="container-fluid">

    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 sidebar">
            <h4>Editor Dashboard</h4>
            <a href="editor_dashboard.php">Profile</a>
            <a href="editor_dashboard.php?page=articles">My Articles</a>
            <a href="../logout.php" class="btn btn-danger mt-3 w-100">Logout</a>
        </nav>

        <!--content -->
        <main class="col-md-10 p-4">
            <h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
            <h2>My Articles</h2>

            <!--search bar -->
            <form class="d-flex mb-3" method="GET" action="editor_dashboard.php">
                <input class="form-control me-2" type="search" name="search" placeholder="Search articles..." value="<?= htmlspecialchars($search) ?>">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>

            <a href="editor_create_articol.php" class="btn btn-primary mb-3">Create New Article</a>

            <!--articolele-->
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!--afiseaza articolele -->
                    <?php if ($result->num_rows > 0) : ?>
                        <?php while ($article = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $article['id'] ?></td>
                                <td><?= htmlspecialchars($article['title']) ?></td>
                                <td><?= htmlspecialchars($article['category']) ?></td>
                                <td>
                                    <a href="editor_view_articol.php?id=<?= $article['id'] ?>" class="btn btn-warning btn-sm">View</a>
                                    <a href="editor_edit_articol.php?id=<?= $article['id'] ?>" class="btn btn-info btn-sm">Edit</a>
                                    <a href="editor_delete_articol.php?id=<?= $article['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this article?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                    <?php else : ?>
                        <tr>
                            <td colspan="4" class="text-center">No articles found.</td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>
        </main>
    </div>

</div>

</body>
</html>

<?php
$stmt->close();
$connect->close();
?>
