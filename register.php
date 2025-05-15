<?php
session_start();
require_once 'db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (!empty($username) && !empty($password)) {
        // Passwort-Hash erstellen
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $stmt->execute([':username' => $username, ':password' => $hashed]);
            $message = 'Registrierung erfolgreich. Du kannst dich jetzt anmelden.';
        } catch (PDOException $e) {
            $message = 'Fehler: Benutzername existiert bereits.';
        }
    } else {
        $message = 'Bitte alle Felder ausfüllen.';
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Registrierung</title>
    <style>
        body { font-family: Arial, sans-serif; }
        form { max-width: 300px; margin: auto; }
        input { width: 100%; margin: 0.5em 0; }
        .message { color: red; text-align: center; }
    </style>
</head>
<body>
    <h2>Registrierung</h2>
    <?php if ($message): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post" action="register.php">
        <input type="text" name="username" placeholder="Benutzername" required>
        <input type="password" name="password" placeholder="Passwort" required>
        <input type="submit" value="Registrieren">
    </form>
    <p>Hast du bereits ein Konto? <a href="index.php">Anmelden</a></p>
</body>
</html>
