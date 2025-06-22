<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=unos.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Unos vijesti - El Confidencial</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>El Confidencial</h1>
        <h2 style="color: grey; font-size: small; text-align: center; text-transform: upperercase">EL DIARIO DE LOS LECTORES INFLUYENTES</h2>
        <div style="position:absolute;top:24px;right:38px;font-size:1.1rem;color:#1e293b;font-weight:500;">
            <?php if (isset($_SESSION['username'])): ?>
                <span style="color:#2563eb;"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php" style="margin-left:16px;color:#e11d48;text-decoration:none;font-weight:600;">Odjava</a>
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
    <main style="max-width: 1400px; background: #fff; border-radius: 12px; padding: 32px 28px 24px 28px; min-height: 600px; margin-left: 150px;">
        <section class="main-content">
            <h2>Unos nove vijesti</h2>
            <form name="unos_vijesti" action="skripta.php" method="POST" enctype="multipart/form-data" autocomplete="on">
                <input type="hidden" name="author" value="<?php echo htmlspecialchars($_SESSION['username'] ?? 'Gost'); ?>">
                <div class="form-item">
                    <label for="title">Naslov vijesti</label>
                    <input type="text" name="title" id="title" class="form-field-textual" required autofocus style="font-size:1.2rem;">
                </div>
                <div class="form-item">
                    <label for="about">Kratki sadržaj vijesti (do 50 znakova)</label>
                    <textarea name="about" id="about" cols="30" rows="3" maxlength="50" class="form-field-textual" required style="font-size:1.1rem;"></textarea>
                </div>
                <div class="form-item">
                    <label for="content">Sadržaj vijesti</label>
                    <textarea name="content" id="content" cols="30" rows="10" class="form-field-textual" required style="font-size:1.1rem; min-height: 220px;"></textarea>
                </div>
                <div class="form-item">
                    <label for="pphoto">Slika:</label>
                    <input type="file" accept="image/*" class="input-text" name="pphoto" id="pphoto" required>
                </div>
                <div class="form-item">
                    <label for="category">Kategorija vijesti</label>
                    <select name="category" id="category" class="form-field-textual" required style="font-size:1.1rem;">
                        <option value="sport">Sport</option>
                        <option value="europa">Europa</option>
                    </select>
                </div>
                <div class="form-item">
                    <button type="reset">Poništi</button>
                    <button type="submit">Prihvati</button>
                </div>
            </form>
        </section>
        <section class="main-content" style="margin-top:40px;">
            <h2>Obriši postojeće vijesti</h2>
            <table style="width:100%;border-collapse:collapse;text-align:center;">
                <tr><th style="text-align:center;">ID</th><th style="text-align:center;">Naslov</th><th style="text-align:center;">Kategorija</th><th style="text-align:center;">Akcija</th></tr>
                <?php
                require_once 'connect.php';
                $result = $conn->query("SELECT id, title, category FROM articles ORDER BY created_at DESC");
                while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td style="text-align:center;vertical-align:middle;"><?php echo $row['id']; ?></td>
                    <td style="text-align:center;vertical-align:middle;"><?php echo htmlspecialchars($row['title']); ?></td>
                    <td style="text-align:center;vertical-align:middle;"><?php echo htmlspecialchars($row['category']); ?></td>
                    <td style="text-align:center;vertical-align:middle;">
                        <form method="POST" action="" onsubmit="return confirm('Jeste li sigurni da želite obrisati ovu vijest?');" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="delete_news" style="background:#e11d48;color:#fff;padding:6px 14px;border:none;border-radius:4px;cursor:pointer;">Obriši</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
            <?php
            if (isset($_POST['delete_news']) && isset($_POST['delete_id'])) {
                $delete_id = (int)$_POST['delete_id'];
                $conn->query("DELETE FROM articles WHERE id=$delete_id");
                echo "<meta http-equiv='refresh' content='0'>";
            }
            ?>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 El Confidencial</p>
    </footer>
</body>
</html>
