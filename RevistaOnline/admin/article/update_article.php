<?php
require '../../connect.php';

//verifica daca utilizatorul este logat
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    //selecteaza articolul cu id-ul specificat
    $query = "SELECT * FROM articles WHERE id = $id";
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

//verifica daca s-a trimis un formular de tip POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($connect, $_POST['title']);
    $content = mysqli_real_escape_string($connect, $_POST['content']);
    $imagePath = $article['imageUrl']; //imaginea curenta

    //verifica daca s-a incarcat o imagine
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageName = uniqid() . '_' . basename($_FILES['image']['name']);
        $imageTmp = $_FILES['image']['tmp_name'];
        $uploadDir = '../../uploads/articles/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Salveaza imaginea in directorul de upload
        $newImagePath = 'uploads/articles/' . $imageName; 
        //verifica daca s-a incarcat imaginea cu succes
        if (move_uploaded_file($imageTmp, $uploadDir . $imageName)) {
            //sterge vechea imagine...
            if ($imagePath && file_exists('../../' . $imagePath)) {
                unlink('../../' . $imagePath);
            }
            $imagePath = $newImagePath; //updateaza path-ul imaginii
        } else {
            echo "Error uploading the image.";
        }
    }

    //updateaza articolul in baza de date
    $updateQuery = "UPDATE articles SET title = '$title', content = '$content', imageUrl = '$imagePath' WHERE id = $id";
    if (mysqli_query($connect, $updateQuery)) {
        header("Location: ../../admin/dashboard.php?page=articles");
        exit();
    } else {
        echo "Error updating article: " . mysqli_error($connect);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5 mb-5">
    <h1>Update Article</h1>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($article['title']) ?>" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content" class="form-control" rows="5" required><?= htmlspecialchars($article['content']) ?></textarea>
        </div>

        <div class="mb-3">

            <label class="form-label">Current Image:</label><br>
            <?php if ($article['imageUrl']) : ?>
                <img src="../../<?= htmlspecialchars($article['imageUrl']) ?>" class="img-thumbnail" style="max-width: 300px;" alt="Current Image">
            <?php else : ?>
                <p>No image uploaded.</p>
            <?php endif; ?>

        </div>

        <div class="mb-3">
            <label class="form-label">Upload New Image (Optional)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <button type="button" class="btn btn-secondary" onclick="history.back()">Back</button>
    </form>
    <p></p>
    


</body>
</html>
