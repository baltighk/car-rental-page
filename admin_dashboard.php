<?php

session_start();

//var_dump($_SESSION);

//echo $_SESSION['user']['admin'];


// Ellenőrzés, hoyg admin-e a bejelentkezett a felhasználó
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || $_SESSION['user']['admin'] == false) {
    header("Location: user_dashboard.php");
    exit();
}

$cars = json_decode(file_get_contents("cars.json"), true) ?? [];
$error_message = [];
$success_message = '';

// Autó törlés gomb
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];

    // Autó keresése az getelet ID alapján
    $car_found = false;
    foreach ($cars as $index => $car) {
        if ($car['id'] === $delete_id) {
            $car_found = true;
            array_splice($cars, $index, 1); // Autó eltávolítása
            if (file_put_contents("cars.json", json_encode($cars, JSON_PRETTY_PRINT)) !== false) {
                $success_message = "Az autó sikeresen törölve lett!";
            } else {
                $error_message[] = "Hiba történt az autó törlése során.";
            }
            break;
        }
    }

    if (!$car_found) {
        $error_message[] = "Az autó nem található!";
    }
}

// Új autó hozzáadása
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = $_POST['brand'] ?? '';
    $model = $_POST['model'] ?? '';
    $year = $_POST['year'] ?? '';
    $transmission = $_POST['transmission'] ?? '';
    $fuel_type = $_POST['fuel_type'] ?? '';
    $passengers = $_POST['passengers'] ?? '';
    $daily_price_huf = $_POST['daily_price_huf'] ?? '';
    $image_url = $_POST['image_url'] ?? '';

    // Hibák
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

    // Adatok mentése ha nincs hiba
    if (empty($error_message)) {
        $new_car = [
            'id' => count($cars) + 1,
            'brand' => $brand,
            'model' => $model,
            'year' => (int)$year,
            'transmission' => $transmission,
            'fuel_type' => $fuel_type,
            'passengers' => (int)$passengers,
            'daily_price_huf' => (int)$daily_price_huf,
            'image' => $image_url
        ];

        $cars[] = $new_car;

        if (file_put_contents("cars.json", json_encode($cars, JSON_PRETTY_PRINT)) !== false) {
            $success_message = "Az új autó sikeresen hozzáadásra került!";
        } else {
            $error_message[] = "Hiba történt az autó mentése során.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Új autó hozzáadása</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">iKarRental</a>
            <div>
                <a href="logout.php" class="btn btn-outline-light">Kijelentkezés</a>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <h2 class="text-center mb-4">Autók listája</h2>
        <div class="row row-cols-1 row-cols-md-4 g-4">
            <?php if (empty($cars)): ?>
                <h4 class="alert alert-warning text-center">Nincsenek autók a rendszerben.</h4>
            <?php else: ?>
                <?php foreach ($cars as $car): ?>
                    <div class="col">
                        <div class="card car-card text-center h-100">
                            <img src="<?= htmlspecialchars($car['image']) ?>" class="card-img-top" alt="Autó">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($car['passengers']) ?> férőhely - <?= htmlspecialchars($car['transmission']) ?></p>
                                <p class="fw-bold"><?= htmlspecialchars(number_format($car['daily_price_huf'], 0, ',', ' ')) ?> Ft</p>
                            </div>
                            <div class="card-footer">
                                <a href="admin_dashboard.php?delete_id=<?= htmlspecialchars($car['id']) ?>" class="btn btn-danger btn-sm w-100">Törlés</a>
                                <a href="edit_car.php?id=<?= htmlspecialchars($car['id']) ?>" class="btn btn-secondary btn-sm w-100">Szerkesztés</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="container my-5">
        <h1 class="text-center">Új autó hozzáadása</h1>

        <!-- Hibák kioirasa -->
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($error_message as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
                        
        <!--
        <?php /* if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success_message) ?>
            </div>
        <?php endif; */?>
        !-->

        <!-- Autó hozzáadás logiak -->
        <form action="admin_dashboard.php" method="POST">
            <div class="mb-3">
                <label for="brand" class="form-label">Márka</label>
                <input type="text" id="brand" name="brand" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="model" class="form-label">Modell</label>
                <input type="text" id="model" name="model" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="year" class="form-label">Év</label>
                <input type="number" id="year" name="year" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="transmission" class="form-label">Váltó</label>
                <select id="transmission" name="transmission" class="form-select" required>
                    <option value="Manual">Manuális</option>
                    <option value="Automatic">Automata</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="fuel_type" class="form-label">Üzemanyag</label>
                <select id="fuel_type" name="fuel_type" class="form-select" required>
                    <option value="Petrol">Benzin</option>
                    <option value="Diesel">Dízel</option>
                    <option value="Electic">Elektromos</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="passengers" class="form-label">Férőhelyek száma</label>
                <input type="number" id="passengers" name="passengers" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="daily_price_huf" class="form-label">Napi ár (Ft)</label>
                <input type="number" id="daily_price_huf" name="daily_price_huf" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="image_url" class="form-label">Kép URL</label>
                <input type="url" id="image_url" name="image_url" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Autó hozzáadása</button>
        </form>
    </div>

</body>
</html>