<?php
session_start();
$loggedIn = $_SESSION['loggedIn'] ?? false;
$userName = $_SESSION['user']['name'] ?? '';
?>

<?php
    error_reporting(0);
    $cars = json_decode(file_get_contents("cars.json"), true);
    $autoSzama = 0;

    $loggedIn = $_GET['loggedIn'] ?? null;
    $admin = $_GET['admin'] ?? null;

    //echo $admin, $loggedIn;

    // Szűrés

    $filteredCars = $cars;
    if (!empty($_GET)) {
        $filteredCars = array_filter($cars, function ($car) {
            $isValid = true;

            // Férőhely szűrés
            if (!empty($_GET['seats']) && $car['passengers'] < $_GET['seats']) {
                $isValid = false;
            }
            // Váltó típus szűrés
            if (!empty($_GET['gear']) && $_GET['gear'] !== $car['transmission']) {
                $isValid = false;
                //echo " " .$_GET['gear'];
                
            }

            // Ár szűrés
            if (!empty($_GET['minPrice']) && $car['daily_price_huf'] < $_GET['minPrice']) {
                $isValid = false;
            }
            if (!empty($_GET['maxPrice']) && $car['daily_price_huf'] > $_GET['maxPrice']) {
                $isValid = false;
            }

            // Dátum szűrés
            if (!empty($_GET['fromDate']) && !empty($_GET['toDate'])) {
                $fromDate = strtotime($_GET['fromDate']);
                $toDate = strtotime($_GET['toDate']);
                $elerhetoleFrom = strtotime($car['available_from']);
                $elerhetoTo = strtotime($car['available_to']);

                // Ellenőrzés, hogy az autó elérhető-e a megadott időszakban
                if ($fromDate < $elerhetoleFrom || $toDate > $elerhetoTo) {
                    $isValid = false;
                }
            }

            return $isValid;
        });
    }

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iKarRental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navbar -->
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

    <!-- Üdvözlő -->
    <div class="container text-center my-5">
        <h1 class="display-4 fw-bold">Kölcsönözz autókat könnyedén!</h1>
        <a href="register.php" class="btn btn-warning btn-lg mt-3">Regisztráció</a>
    </div>

    <!-- Szűrők -->
    <div class="container my-4 d-flex justify-content-center text-center">
        <form method="GET" class="w-100">
            <div class="row g-3">
                <div class="col-md-2">
                    <label for="seats" class="form-label">Férőhely</label>
                    <input type="number" id="seats" name="seats" class="form-control" placeholder="0" value="<?= htmlspecialchars($_GET['seats'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label for="date" class="form-label">Időszak</label>
                    <div class="d-flex">
                        <input type="date" id="fromDate" name="fromDate" class="form-control me-2" value="<?= htmlspecialchars($_GET['fromDate'] ?? '') ?>">
                        <input type="date" id="toDate" name="toDate" class="form-control" value="<?= htmlspecialchars($_GET['toDate'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-2" id="margin">
                    <label for="gear" class="form-label">Váltó típusa</label>
                    <select id="gear" name="gear" class="form-select">
                        <option value="" <?= empty($_GET['gear']) ? 'selected' : '' ?>>Mindkettő</option>
                        <option value="Manual" <?= ($_GET['gear'] ?? '') === 'Manual' ? 'selected' : '' ?>>Manuális</option>
                        <option value="Automatic" <?= ($_GET['gear'] ?? '') === 'Automatic' ? 'selected' : '' ?>>Automata</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="price" class="form-label">Ár</label>
                    <div class="d-flex">
                        <input type="number" id="minPrice" name="minPrice" class="form-control me-2" placeholder="Min" value="<?= htmlspecialchars($_GET['minPrice'] ?? '') ?>">
                        <input type="number" id="maxPrice" name="maxPrice" class="form-control" placeholder="Max" value="<?= htmlspecialchars($_GET['maxPrice'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Szűrés</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Autók listázása konténerbe kártya -->
    <div class="container my-4">
        <div class="row row-cols-1 row-cols-md-4 g-4">
            <?php if(empty($filteredCars)): ?>
                <h4 class="alert alert-warning text-center">Nincs találat a keresési feltételeknek megfelelően</h4>
            <?php else:?>
                <?php foreach($filteredCars as $car): ?>
                    <div class="col">
                        <div class="card car-card text-center">
                            <a href="car.php?id=<?=htmlspecialchars($car['id'])?>" class="text-decoration-none text-white">
                                <img src="<?= htmlspecialchars($car['image']) ?>" class="card-img-top" alt="Autó">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($car['passengers']) ?> férőhely - <?= htmlspecialchars($car['transmission']) ?></p>
                                    <p class="fw-bold"><?= htmlspecialchars(number_format($car['daily_price_huf'], 0, ',', ' ')) ?> Ft</p>
                                    
                                </div>
                            </a>
                            <a class="btn btn-warning w-100" href="<?= ($_GET['loggedIn'] ?? '') === true ? "successfulBooking.php" : "login.php" ?>">Foglalás</a>   
                        </div>
                    </div>
                <?php endforeach;?>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>