<?php
require_once 'connect.php';
session_start();
if (!isset($_SESSION['username'])) {
    // Ako nije logiran, preusmjeri na login
    header('Location: login.php?redirect=unos.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $about = isset($_POST['about']) ? $_POST['about'] : '';
    $content = isset($_POST['content']) ? $_POST['content'] : '';
    $category = isset($_POST['category']) ? $_POST['category'] : '';
    $author = $_SESSION['username']; // Uvijek user koji je loginan
    $archive = 1; // Always publish on main index
    $image = '';
    if (isset($_FILES['pphoto']) && $_FILES['pphoto']['error'] === UPLOAD_ERR_OK) {
        $img_dir = 'img/';
        if (!is_dir($img_dir)) {
            mkdir($img_dir);
        }
        $img_name = basename($_FILES['pphoto']['name']);
        $img_path = $img_dir . $img_name;
        move_uploaded_file($_FILES['pphoto']['tmp_name'], $img_path);
        $image = $img_path;
    }
    // Insert into database
    $stmt = $conn->prepare("INSERT INTO articles (title, about, content, image, category, author, archive) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $title, $about, $content, $image, $category, $author, $archive);
    $stmt->execute();
    $inserted_id = $stmt->insert_id;
    $stmt->close();
    $conn->close();
    // Fetch created_at and author for display
    require 'connect.php';
    $res = $conn->query("SELECT created_at, author FROM articles WHERE id=$inserted_id");
    $meta = $res->fetch_assoc();
    $created_at = $meta['created_at'];
    $author = $meta['author'];
    $conn->close();
} else {
    header('Location: unos.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>El Confidencial</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: #f4f6fa;
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container-news {
            max-width: 800px;
            margin: 40px auto 30px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(30,41,59,0.10);
            padding: 38px 32px 32px 32px;
        }
        .news-title {
            color: #1e293b;
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 12px;
            text-align: center;
        }
        .news-meta {
            color: #64748b;
            font-size: 1rem;
            text-align: center;
            margin-bottom: 18px;
        }
        .news-image {
            display: flex;
            justify-content: center;
            margin-bottom: 22px;
        }
        .news-image img {
            max-width: 100%;
            max-height: 340px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(30,41,59,0.10);
        }
        .news-about {
            color: #334155;
            font-size: 1.1rem;
            margin-bottom: 18px;
            text-align: center;
        }
        .news-content {
            color: #222;
            font-size: 1.15rem;
            line-height: 1.7;
            margin-bottom: 24px;
        }
        @media (max-width: 900px) {
            .container-news {
                max-width: 98vw;
                padding: 18px 2vw 18px 2vw;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1 style="color:#1e293b;text-align:center;font-size:2.5rem;margin-bottom:0;">El Confidencial</h1>
        <h2 style="color: grey; font-size: small; text-align: center; margin-top:0;">EL DIARIO DE LOS LECTORES INFLUYENTES</h2>
        <nav class="main-categories" style="background:#fff;box-shadow:none;border-bottom:2px solid #1e293b;margin-bottom:24px;">
            <ul style="gap:24px;display:flex;justify-content:center;list-style:none;padding:0;margin:0;">
                <li><a href="index.php" style="color:#1e293b;font-size:1rem;padding:10px 18px;text-decoration:none;">Home</a></li>
                <li><a href="unos.php" style="color:#1e293b;font-size:1rem;padding:10px 18px;text-decoration:none;">Administracija</a></li>
                <li><a href="kategorija.php?category=sport" style="color:#1e293b;font-size:1rem;padding:10px 18px;text-decoration:none;">Sport</a></li>
                <li><a href="kategorija.php?category=europa" style="color:#1e293b;font-size:1rem;padding:10px 18px;text-decoration:none;">Europa</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="container-news">
            <div class="news-meta">Kategorija: <b><?php echo htmlspecialchars($category); ?></b></div>
            <div class="news-meta">Objavljeno: <span style="color:#2563eb;"><?php echo htmlspecialchars($created_at); ?></span></div>
            <div class="news-meta">Autor: <span style="color:#2563eb;"><?php echo htmlspecialchars($author); ?></span></div>
            <div class="news-title"><?php echo htmlspecialchars($title); ?></div>
            <div class="news-image">
                <?php if ($image) echo "<img src='" . htmlspecialchars($image) . "' alt='Slika vijesti'>"; ?>
            </div>
            <div class="news-about"><?php echo nl2br(htmlspecialchars($about)); ?></div>
            <div class="news-content"><?php echo nl2br(htmlspecialchars($content)); ?></div>
        </div>
    </main>
    <footer>
        <p style="text-align:center;color:#64748b;font-size:1rem;margin:32px 0 0 0;">&copy; 2025 Portal Vijesti</p>
    </footer>
</body>
</html>
