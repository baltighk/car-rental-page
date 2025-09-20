<?php
session_start();

// Ellenőrzés - admijn
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || $_SESSION['user']['admin'] == false) {
    header("Location: user_dashboard.php");
    exit();
}

$cars = json_decode(file_get_contents("cars.json"), true) ?? [];
$error_message = [];
$success_message = '';

// Ellenőrzés ID-re
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$car_id = (int)$_GET['id'];
$car = null;

// Autó keresése az ID alapján
foreach ($cars as $current_car) {
    if ($current_car['id'] === $car_id) {
        $car = $current_car;
        break;
    }
}

// autó nem található
if (!$car) {
    header("Location: admin_dashboard.php");
    exit();
}

// Adatok módosítása
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = $_POST['brand'] ?? '';
    $model = $_POST['model'] ?? '';
    $year = $_POST['year'] ?? '';
    $transmission = $_POST['transmission'] ?? '';
    $fuel_type = $_POST['fuel_type'] ?? '';
    $passengers = $_POST['passengers'] ?? '';
    $daily_price_huf = $_POST['daily_price_huf'] ?? '';
    $image_url = $_POST['image_url'] ?? '';

    // Hibakezelés
    if (empty($brand) || empty($model) || empty($year) || empty($transmission) || empty($fuel_type) || empty($passengers) || empty($daily_price_huf) || empty($image_url)) {
        $error_message[] = "Minden mező kitöltése kötelező!";
    }

    if (!filter_var($image_url, FILTER_VALIDATE_URL)) {
        $error_message[] = "Érvénytelen kép URL!";
    }

    if (!is_numeric($year) || $year < 1900 || $year > date('Y')) {
        $error_message[] = "Érvénytelen év!";
    }

    if (!is_numeric($daily_price_huf) || $daily_price_huf <= 0) {
        $error_message[] = "Az ár pozitív szám kell legyen!";
    }

    if (!is_numeric($passengers) || $passengers <= 0) {
        $error_message[] = "A férőhelyek száma pozitív szám kell legyen!";
    }

    // Mentés, ha nincs hiba
    if (empty($error_message)) {
        foreach ($cars as &$current_car) {
            if ($current_car['id'] === $car_id) {
                $current_car['brand'] = $brand;
                $current_car['model'] = $model;
                $current_car['year'] = (int)$year;
                $current_car['transmission'] = $transmission;
                $current_car['fuel_type'] = $fuel_type;
                $current_car['passengers'] = (int)$passengers;
                $current_car['daily_price_huf'] = (int)$daily_price_huf;
                $current_car['image'] = $image_url;
                break;
            }
        }

        if (!(file_put_contents("cars.json", json_encode($cars, JSON_PRETTY_PRINT)) !== false)) {
            $error_message[] = "Hiba történt az adatok mentése során.";
        }

        /*
        if (file_put_contents("cars.json", json_encode($cars, JSON_PRETTY_PRINT)) !== false) {
            $success_message = "Az autó adatai sikeresen frissítve!";
        } else {
            $error_message[] = "Hiba történt az adatok mentése során.";
        }
        */
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Autó módosítása</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center">Autó adatainak módosítása</h1>

        <!-- Hibák megjelenítése -->
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($error_message as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Autó módosítási űrlap -->
        <form action="edit_car.php?id=<?= htmlspecialchars($car_id) ?>" method="POST">
            <div class="mb-3">
                <label for="brand" class="form-label">Márka</label>
                <input type="text" id="brand" name="brand" class="form-control" value="<?= htmlspecialchars($car['brand']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="model" class="form-label">Modell</label>
                <input type="text" id="model" name="model" class="form-control" value="<?= htmlspecialchars($car['model']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="year" class="form-label">Év</label>
                <input type="number" id="year" name="year" class="form-control" value="<?= htmlspecialchars($car['year']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="transmission" class="form-label">Váltó</label>
                <select id="transmission" name="transmission" class="form-select" required>
                    <option value="Manual" <?= $car['transmission'] === 'Manual' ? 'selected' : '' ?>>Manuális</option>
                    <option value="Automatic" <?= $car['transmission'] === 'Automatic' ? 'selected' : '' ?>>Automata</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="fuel_type" class="form-label">Üzemanyag</label>
                <select id="fuel_type" name="fuel_type" class="form-select" required>
                    <option value="Petrol" <?= $car['fuel_type'] === 'Petrol' ? 'selected' : '' ?>>Benzin</option>
                    <option value="Diesel" <?= $car['fuel_type'] === 'Diesel' ? 'selected' : '' ?>>Dízel</option>
                    <option value="Electric" <?= $car['fuel_type'] === 'Electric' ? 'selected' : '' ?>>Elektromos</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="passengers" class="form-label">Férőhelyek száma</label>
                <input type="number" id="passengers" name="passengers" class="form-control" value="<?= htmlspecialchars($car['passengers']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="daily_price_huf" class="form-label">Napi ár (Ft)</label>
                <input type="number" id="daily_price_huf" name="daily_price_huf" class="form-control" value="<?= htmlspecialchars($car['daily_price_huf']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="image_url" class="form-label">Kép URL</label>
                <input type="url" id="image_url" name="image_url" class="form-control" value="<?= htmlspecialchars($car['image']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Mentés</button>
        </form>
    </div>
</body>
</html>