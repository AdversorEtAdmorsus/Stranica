<?php
session_start();
require_once 'connect.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username && $password) {
        // Check if username already exists
        $stmt = $conn->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = 'Korisničko ime je zauzeto.';
        } else {
            // Insert new user
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
            $stmt->bind_param('ss', $username, $hash);
            if ($stmt->execute()) {
                $success = 'Registracija uspješna! Možete se prijaviti.';
            } else {
                $error = 'Greška pri registraciji.';
            }
        }
        $stmt->close();
    } else {
        $error = 'Unesite korisničko ime i lozinku.';
    }
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Registracija - El Confidencial</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background:#f4f6fa;">
    <main style="display:flex;justify-content:center;align-items:center;min-height:100vh;">
        <section class="main-content" style="background:#fff;padding:38px 32px 32px 32px;border-radius:14px;box-shadow:0 4px 24px rgba(30,41,59,0.10);width:100%;max-width:400px;">
            <h2 style="text-align:center;margin-bottom:28px;">Registracija</h2>
            <?php if ($error): ?>
                <p style="color:red;text-align:center;"> <?= htmlspecialchars($error) ?> </p>
            <?php elseif ($success): ?>
                <p style="color:green;text-align:center;"> <?= htmlspecialchars($success) ?> </p>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-item">
                    <label for="username">Korisničko ime</label>
                    <input type="text" name="username" id="username" class="form-field-textual" required autofocus>
                </div>
                <div class="form-item">
                    <label for="password">Lozinka</label>
                    <input type="password" name="password" id="password" class="form-field-textual" required>
                </div>
                <div class="form-item" style="text-align:center;">
                    <button type="submit">Registriraj se</button>
                </div>
            </form>
            <div style="text-align:center;margin-top:18px;">
                <a href="login.php" style="display:inline-block;padding:10px 24px;background:#2563eb;color:#fff;border-radius:6px;text-decoration:none;font-weight:600;font-size:1rem;box-shadow:0 2px 8px rgba(30,41,59,0.08);transition:background 0.2s;">Natrag na prijavu</a>
            </div>
        </section>
    </main>
</body>
</html>
