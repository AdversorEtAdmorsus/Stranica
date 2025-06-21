<?php
// skripta.php - prikaz podataka iz forme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $about = isset($_POST['about']) ? $_POST['about'] : '';
    $content = isset($_POST['content']) ? $_POST['content'] : '';
    $category = isset($_POST['category']) ? $_POST['category'] : '';
    $archive = isset($_POST['archive']) ? 'DA' : 'NE';
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
} else {
    header('Location: unos.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Prikaz vijesti</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Portal Vijesti</h1>
        <nav>
            <ul>
                <li><a href="index.php">Početna</a></li>
                <li><a href="kategoija.php">Kategorije</a></li>
                <li><a href="unos.html">Unos vijesti</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section role="main">
            <div class="row">
                <p class="category"><?php echo htmlspecialchars($category); ?></p>
                <h1 class="title"><?php echo htmlspecialchars($title); ?></h1>
                <p>AUTOR:</p>
                <p>OBJAVLJENO:</p>
            </div>
            <section class="slika">
                <?php if ($image) echo "<img src='" . htmlspecialchars($image) . "' alt='Slika vijesti'>"; ?>
            </section>
            <section class="about">
                <p><?php echo nl2br(htmlspecialchars($about)); ?></p>
            </section>
            <section class="sadrzaj">
                <p><?php echo nl2br(htmlspecialchars($content)); ?></p>
            </section>
            <section class="arhiva">
                <p>Prikazati na stranici: <?php echo $archive; ?></p>
            </section>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 Portal Vijesti</p>
    </footer>
</body>
</html>
