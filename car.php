<?php
session_start();
$loggedIn = $_SESSION['loggedIn'] ?? false;
$userName = $_SESSION['user']['name'] ?? '';
?>

<?php 
$cars = json_decode(file_get_contents("cars.json"), true);


$carId = $_GET['id'] ?? null;

$admin = $_GET['admin'] ?? null;

// Kiválasztott autó keresése
$selectedCar = null;
foreach ($cars as $car) {
    if ($car['id'] == $carId) {
        $selectedCar = $car;
        break;
    }
}

// Ha az autó nem található, hibaüzenet megjelenítése
if (!$selectedCar) {
    echo "<p>Az autó nem található.</p>";
    echo '<a href="index.php" class="btn btn-primary">Vissza a főoldalra</a>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iKarRental - <?= htmlspecialchars($selectedCar['brand'] . ' ' . $selectedCar['model']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">iKarRental</a>
            <div>
                <?php if ($_SESSION['logged_in']): ?>
                    <span class="text-white me-3">Üdv, <a href="admin_dashboard.php" class="userLink"><?= htmlspecialchars($userName) ?></a>!</span>
                    <a href="logout.php" class="btn btn-outline-light me-2">Kijelentkezés</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-light me-2">Bejelentkezés</a>
                    <a href="register.php" class="btn btn-warning">Regisztráció</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="car-details-container">
            <!-- Autó képe -->
            <img src="<?= htmlspecialchars($selectedCar['image']) ?>" alt="<?= htmlspecialchars($selectedCar['brand'] . ' ' . $selectedCar['model']) ?>" class="car-image">

            <!-- Autó részletei -->
            <div class="car-details">
                <h1><?= htmlspecialchars($selectedCar['brand']) ?> <span style="color: gold;"><?= htmlspecialchars($selectedCar['model']) ?></span></h1>
                <p><strong>Üzemanyag:</strong> <?= htmlspecialchars($selectedCar['fuel_type']) ?></p>
                <p><strong>Váltó:</strong> <?= htmlspecialchars($selectedCar['transmission']) ?></p>
                <p><strong>Gyártási év:</strong> <?= htmlspecialchars($selectedCar['year']) ?></p>
                <p><strong>Férőhelyek száma:</strong> <?= htmlspecialchars($selectedCar['passengers']) ?></p>
                <p class="car-price"><?= htmlspecialchars(number_format($selectedCar['daily_price_huf'], 0, ',', ' ')) ?> Ft/nap</p>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary btn-custom">Dátum kiválasztása</button>
                    <a class="btn btn-warning btn-custom" href="<?= ($_GET['loggedIn'] ?? '') === true ? "successfulBooking.php" : "login.php" ?>">Lefoglalom</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>