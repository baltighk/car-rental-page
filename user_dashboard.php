<?php
session_start();


if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php");
    exit();
}

// Felhasználói adatok betöltése
$userName = $_SESSION['user']['name'];


$cars = json_decode(file_get_contents("cars.json"), true) ?? [];
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($userName) ?> - Foglalásaim</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">iKarRental</a>
            <div>
                <span class="text-white me-3">Üdv, <?= htmlspecialchars($userName) ?>!</span>
                <a href="logout.php" class="btn btn-outline-light">Kijelentkezés</a>
            </div>
        </div>
    </nav>

    <!-- Üdvözlő szöveg -->
    <div class="container">
        <p class="welcome-text">Üdvözlünk a felhasználói irányítópulton, <span id="user_dashb_name"><?= htmlspecialchars($userName) ?></span>!</p>
    </div>

    <!-- Autók listázása -->
    
    <div class="container my-4">
        <h2>Foglalásaim:</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            
        </div>
    </div>
</body>
</html>