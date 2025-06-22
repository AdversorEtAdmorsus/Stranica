<?php
include 'connect.php';
$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';
$sql = "SELECT * FROM articles WHERE archive=1";
if ($category) {
    $sql .= " AND category='$category'";
}
$sql .= " ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>El Confidencial</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>El Confidencial</h1>
        <h2 style="color: grey; font-size: small; text-align: center; text-transform: upperercase">EL DIARIO DE LOS LECTORES INFLUYENTES</h2>
        <div style="position:absolute;top:24px;right:38px;font-size:1.1rem;color:#1e293b;font-weight:500;">
            <?php session_start(); if (isset($_SESSION['username'])): ?>
                <span style="color:#2563eb;\"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php" style="margin-left:16px;color:#e11d48;text-decoration:none;font-weight:600;">Odjava</a>
            <?php else: ?>
                <span style="color:#64748b;">Gost</span>
                <a href="login.php" style="color:#2563eb;text-decoration:none;font-weight:600;margin-left:10px;">Prijava</a>
            <?php endif; ?>
        </div>
        <nav class="main-categories" style="background:#fff;box-shadow:none;border-bottom:2px solid #1e293b;">
            <ul style="gap:24px;">
                <li><a href="index.php" style="color:#1e293b;font-size:0.9rem;padding:10px 10px;">HOME</a></li>
                <li><a href="unos.php" style="color:#1e293b;font-size:0.9rem;padding:10px 10px;">ADMINISTRACIJA</a></li>
                <li><a href="kategorija.php?category=sport" style="color:#1e293b;font-size:0.9rem;padding:10px 10px;">SPORT</a></li>
                <li><a href="kategorija.php?category=europa" style="color:#1e293b;font-size:0.9rem;padding:10px 10px;">EUROPA</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2 class="page-title">Vijesti iz kategorije: <?php echo htmlspecialchars($category); ?></h2>
        <section class="news-gallery">
            <div class="gallery-grid">
                <?php while($row = $result->fetch_assoc()): ?>
                <div class="news-item">
                    <a href="clanak.php?id=<?php echo $row['id']; ?>">
                        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Slika vijesti">
                        <h3 style="color:#64748b;font-size:1.2rem;margin:8px 0 0 0;display:block;font-weight:400;text-decoration:none;"><?php echo htmlspecialchars($row['title']); ?></h3>
                    </a>
                </div>
                <?php endwhile; ?>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 El Confidencial</p>
    </footer>
    <style>
    html, body {
        height: 100%;
    }
    body {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    main {
        flex: 1 0 auto;
    }
    footer {
        flex-shrink: 0;
        position: relative;
        bottom: 0;
        width: 100%;
        margin-top: 0;
    }
    </style>
</body>
</html>
