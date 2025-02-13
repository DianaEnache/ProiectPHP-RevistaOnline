<?php

require '../../connect.php';


//redirectioneaza catre pagina de login daca userul nu este logat
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Genereaza un token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica token CSRF
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Invalid CSRF token.');
    }



$title = htmlspecialchars(trim($_POST['title']));
$content = htmlspecialchars(trim($_POST['content']));
$author_id = $_SESSION['user_id']; 
$category = htmlspecialchars(trim($_POST['category']));

// Verifica daca campurile sunt completate
if (empty($title) || empty($content) || empty($category)) {
    die('All fields are required.');
}


//Upload Image
$uploadDir = 'uploads/articles/'; 
$imagePath = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmp = $_FILES['image']['tmp_name'];
        $imageName = uniqid() . '_' . basename($_FILES['image']['name']);

        // Verifica tipul de imagine jpg, png, gif
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($imageTmp);
        if (!in_array($fileType, $allowedTypes)) {
            die('Invalid image type. Only JPG, PNG, and GIF are allowed.');
        }

        // Verifica daca exista directorul de upload
        if (!is_dir('../../' . $uploadDir)) {
            mkdir('../../' . $uploadDir, 0777, true);
        }

        // Salveaza imaginea in directorul de upload
        $imagePath = $uploadDir . $imageName;
        if (!move_uploaded_file($imageTmp, '../../' . $imagePath)) {
            die('Error uploading the image.');
        }
    }

// Insereaza datele in baza de date
$stmt = $connect->prepare("INSERT INTO articles (title, content, author_id, imageUrl, category) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssiss", $title, $content, $author_id, $imagePath, $category);

// Verifica daca interogarea a fost executata cu succes
    if ($stmt->execute()) {
        header('Location: ../../admin/dashboard.php?page=articles');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Inchide interogarea si conexiunea
    $stmt->close();
    $connect->close();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">
    <h1>Create New Article</h1>
    <form method="POST" enctype="multipart/form-data"> 
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

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
            <input type="file" name="image" class="form-control" accept="image/*" required>
        </div>

        <button type="submit" class="btn btn-success">Create</button>
        <button type="button" class="btn btn-primary" onclick="history.back()">Back</button>
    </form>

    <p></p>
    
</body>
</html>
