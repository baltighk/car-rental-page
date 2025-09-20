<?php 
session_start();

$users = json_decode(file_get_contents("users.json"), true);
$error_message = '';

/*
foreach ($users as $user) {
    var_dump($user);
}
*/

// Bejelentkezés
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $logged_in = false;
    // Felhasználó keresése
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            // Jelszó ellenőrzése
            if (password_verify($password, $user['password'])) {
                $logged_in = true;
                $_SESSION['logged_in'] = $logged_in;
                // Sikeres bejelentkezés
                $_SESSION['user'] = [
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'admin' => $user['admin'] ?? false
                ];

                // Admin vagy felhasználói átirányítás
                if ($_SESSION['user']['admin']) {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                // Hibás jelszó
                $error_message = "Hibás jelszó!";
            }
        }
    }

    // Hibás bejelntkezés üzenet
    if (!$logged_in) {
        $error_message = "Hibás e-mail cím vagy jelszó!";
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Belépés</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="login-body">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">iKarRental</a>
            <div>
                <a href="login.php" class="btn btn-outline-light me-2">Bejelentkezés</a>
                <a href="register.php" class="btn btn-warning">Regisztráció</a>
            </div>
        </div>
    </nav>

    <!-- Belépésform-->
    <div class="form-container">
        <h1>Belépés</h1>

        <!-- Hibaüzenet -->
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>
        
        <!-- Belépés űrlap -->
        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="email">E-mail cím</label>
                <input type="email" id="email" name="email" placeholder="jakab.gipsz@ikarrental.net" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password">Jelszó</label>
                <input type="password" id="password" name="password" placeholder="********" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-warning w-100">Belépés</button>
        </form>
    </div>
</body>
</html>