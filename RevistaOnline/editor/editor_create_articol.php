<?php
require '../connect.php';

//doar editorii pot crea articole(si adminii...)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'editor') {
    header('Location: ../login.php');
    exit();
}


$user_id = $_SESSION['user_id'];

//proceseaza datele din formular
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category = trim($_POST['category']);

    // verifica daca campurile sunt completate
    if (empty($title) || empty($content) || empty($category)) {
        die('All fields are required.');
    }

    //imagine upload
    $imagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageName = uniqid() . '_' . basename($_FILES['image']['name']);
        $imageTmp = $_FILES['image']['tmp_name'];
        $uploadDir = 'uploads/articles/';  //path-ul pentru baza de date

        if (!is_dir('../' . $uploadDir)) {
            mkdir('../' . $uploadDir, 0777, true);
        }

        $imagePath = $uploadDir . $imageName;
        if (!move_uploaded_file($imageTmp, '../' . $imagePath)) { 
            die('Error uploading the image.');
        }
    }

    // insereaza datele in baza de date folosind prepared statements si bind_param pentru a preveni SQL injection
    $stmt = $connect->prepare("INSERT INTO articles (title, content, author_id, imageUrl, category) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $title, $content, $user_id, $imagePath, $category);

    if ($stmt->execute()) {
        header('Location: editor_dashboard.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $connect->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">

    <h1>Create New Article</h1>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Category</label>
            <input type="text" name="category" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content" class="form-control" rows="5" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Upload Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-success">Create</button>

        <a href="editor_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>

    </form>

</body>
</html>
