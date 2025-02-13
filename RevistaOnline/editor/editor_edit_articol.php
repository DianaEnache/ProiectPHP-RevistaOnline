<?php
require '../connect.php';

//doar editorii pot edita articole
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'editor') {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

//preluam articolul
try {
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $article_id = intval($_GET['id']);
        // Verificam daca userul este autorul articolului
        $stmt = $connect->prepare("SELECT * FROM articles WHERE id = ? AND author_id = ?");
        $stmt->bind_param("ii", $article_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $article = $result->fetch_assoc();
        } else {
            throw new Exception("Article not found or you don't have permission to edit it.");
        }
    } else {
        throw new Exception("Invalid request. Article ID not provided.");
    }

    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = htmlspecialchars($_POST['title']);
        $content = htmlspecialchars($_POST['content']);
        $category = htmlspecialchars($_POST['category']);
        $imagePath = $article['imageUrl'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageName = uniqid() . '_' . basename($_FILES['image']['name']);
            $imageTmp = $_FILES['image']['tmp_name'];
            $uploadDir = 'uploads/articles/'; 
        
            if (!is_dir('../' . $uploadDir)) {
                mkdir('../' . $uploadDir, 0777, true);
            }
        
            $newImagePath = $uploadDir . $imageName;
        
            if (move_uploaded_file($imageTmp, '../' . $newImagePath)) {  
                if ($imagePath && file_exists('../' . $imagePath)) {
                    unlink('../' . $imagePath);  
                }
                $imagePath = $newImagePath;  
            } else {
                throw new Exception("Error uploading the image.");
            }
        }
        
        //updateaza articolul in baza de date
        $updateStmt = $connect->prepare("UPDATE articles SET title = ?, content = ?, category = ?, imageUrl = ? WHERE id = ? AND author_id = ?");
        $updateStmt->bind_param("ssssii", $title, $content, $category, $imagePath, $article_id, $user_id);

        if ($updateStmt->execute()) {
            header('Location: editor_dashboard.php');
            exit();
        } else {
            throw new Exception("Error updating article: " . $updateStmt->error);
        }
    }
} catch (Exception $e) {
    //afiseaza mesaj de eroare
    echo $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">

    <h1>Edit Article</h1>
    <form method="POST" enctype="multipart/form-data">

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($article['title']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <input type="text" name="category" id="category" class="form-control" value="<?= htmlspecialchars($article['category']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea name="content" id="content" class="form-control" rows="6" required><?= htmlspecialchars($article['content']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Current Image:</label><br>
                <?php if ($article['imageUrl'] && file_exists('../' . $article['imageUrl'])) : ?>
                    <img src="../<?= htmlspecialchars($article['imageUrl']) ?>" class="img-thumbnail" style="max-width: 300px;" alt="Current Image">
                <?php else : ?>
                    <p>No image uploaded.</p>
                <?php endif; ?>
            </div>


        <div class="mb-3">
            <label for="image" class="form-label">Upload New Image (Optional)</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-success">Update Article</button>

        <a href="editor_dashboard.php" class="btn btn-secondary">Cancel</a>
        
    </form>
</body>
</html>