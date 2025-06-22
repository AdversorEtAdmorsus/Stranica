<?php
include 'connect.php';
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}
$id = (int)$_GET['id'];
$sql = "SELECT * FROM articles WHERE id=$id";
$result = $conn->query($sql);
if (!$result || $result->num_rows == 0) {
    echo "<h2>Vijest nije pronađena.</h2>";
    exit();
}
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>El Confidencial</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div style="position:absolute;top:24px;right:38px;">
        <a href="index.php" style="color:#2563eb;font-size:1.1rem;font-weight:600;text-decoration:none;padding:10px 18px;border-radius:6px;background:#f4f6fa;box-shadow:0 2px 8px rgba(30,41,59,0.08);">Home</a>
    </div>
    <main style="background:#fff;border-radius:12px;box-shadow:0 4px 24px rgba(30,41,59,0.10);padding:32px 28px 24px 28px;">
        <div style="color:#64748b;font-size:1.05rem;text-align:center;margin-bottom:6px;">
            Objavljeno: <span style="color:#2563eb;"><?php echo htmlspecialchars($row['created_at']); ?></span>
        </div>
        <div style="color:#64748b;font-size:1.05rem;text-align:center;margin-bottom:18px;">
            Autor: <span style="color:#2563eb;"><?php echo htmlspecialchars($row['author']); ?></span>
        </div>
        <h2 style="font-family:'Times New Roman', Times, serif;font-weight:bold;font-size:3.2rem;margin-bottom:18px;text-align:center;letter-spacing:1px;">
            <?php echo htmlspecialchars($row['title']); ?>
        </h2>
        <div style="font-size:1.25rem;color:#64748b;margin-bottom:24px;font-weight:500;text-align:center;">
            <?php echo nl2br(htmlspecialchars($row['about'])); ?>
        </div>
        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Slika vijesti" style="max-width:200px;width:100%;height:auto;display:block;margin:0 auto 32px auto;border-radius:12px;box-shadow:0 4px 16px rgba(30,41,59,0.13);">
        <div style="font-size:1.18rem;line-height:1.8;color:#222;text-align:center;max-width:800px;margin:0 auto 40px auto;">
            <?php echo nl2br(htmlspecialchars($row['content'])); ?>
        </div>
    </main>
    <footer>
        <p>&copy; 2025 El Confidencial</p>
    </footer>
</body>
</html>
