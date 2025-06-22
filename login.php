<?php
session_start();
require_once 'connect.php';

$error = '';
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username && $password) {
        $stmt = $conn->prepare('SELECT id, password FROM users WHERE username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows === 1) {
            $stmt->bind_result($user_id, $hash);
            $stmt->fetch();
            if (password_verify($password, $hash)) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                header('Location: ' . $redirect);
                exit();
            } else {
                $error = 'Pogrešna lozinka.';
            }
        } else {
            $error = 'Korisnik ne postoji.';
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
    <title>Prijava - El Confidencial</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background:#f4f6fa;">
    <main style="display:flex;justify-content:center;align-items:center;min-height:100vh;">
        <section class="main-content" style="background:#fff;padding:38px 32px 32px 32px;border-radius:14px;box-shadow:0 4px 24px rgba(30,41,59,0.10);width:100%;max-width:400px;">
            <h2 style="text-align:center;margin-bottom:28px;">Prijava</h2>
            <?php if ($error): ?>
                <p style="color:red;text-align:center;"><?= htmlspecialchars($error) ?></p>
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
                    <button type="submit">Prijavi se</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
