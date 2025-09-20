<?php
    $users = json_decode(file_get_contents("users.json"), true);
    $error_message = [];

    $name = '';
    $email = '';
    $password = '';
    $password_again = '';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_again = $_POST['password_again'] ?? '';

        // Szures
        if (empty($name) || empty($email) || empty($password) || empty($password_again)) {
            $error_message['empty'] = "Kérem töltse ki az összes mezőt!";
        }

        if ($password !== $password_again) {
            $error_message['password_match'] = "A jelszavak nem egyeznek!";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message['invalid_email'] = "Érvénytelen e-mail cím!";
        }

        if (strpos($name, ' ') === false) {
            $error_message['invalid_name'] = "A név nem tartalmaz szóközt!";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">iKarRental</a>
            <div>
                <a href="login.php" class="btn btn-outline-light me-2">Bejelentkezés</a>
                <a href="register.php" class="btn btn-warning">Regisztráció</a>
            </div>
        </div>
    </nav>

    <div class="form-container">
        <h1>Regisztráció</h1>

        <?php if (!empty($error_message)): ?>
            <?php foreach ($error_message as $error): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endforeach;?>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <div class="mb-3">
                <label for="name">Teljes név</label>
                <input type="text" id="name" name="name" placeholder="Gipsz Jakab" required>
            </div>
            <div class="mb-3">
                <label for="email">E-mail cím</label>
                <input type="email" id="email" name="email" placeholder="jakab.gipsz@ikarrental.net" required>
            </div>
            <div class="mb-3">
                <label for="password">Jelszó</label>
                <input type="password" id="password" name="password" placeholder="********" required>
            </div>
            <div class="mb-3">
                <label for="password_again">Jelszó mégegyszer</label>
                <input type="password" id="password_again" name="password_again" placeholder="********" required>
            </div>
            <button type="submit" class="btn-login">Regisztráció</button>
        </form>
    </div>
</body>
</html>

<?php 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        if (empty($error_message)) {
            $uj_user = [
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'admin' => $admin ?? false
            ];
            $users[] = $uj_user;

            file_put_contents("users.json", json_encode($users, JSON_PRETTY_PRINT));

            header("Location: login.php");
            exit();
        }
    }
?>