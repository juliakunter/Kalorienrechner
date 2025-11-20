<!DOCTYPE html>
<html>
<head>
    <title>Kalorienrechner</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Eigenes CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Switch-Button -->
    <div class="text-center my-4">
        <label class="switch">
            <input type="checkbox" id="modeToggle">
            <span class="slider round"></span>
        </label>
        <p id="modeLabel" class="mt-2">Dark Mode</p>
    </div>

    <!-- Hauptcontainer -->
    <div class="container">
        <h2 class="mb-4">Kalorienbedarf berechnen</h2>
        <form method="POST" action="" class="row g-3">
            <div class="col-md-6">
                <label for="geschlecht" class="form-label">Geschlecht</label>
                <select name="geschlecht" id="geschlecht" class="form-select" required>
                    <option value="maennlich">Männlich</option>
                    <option value="weiblich">Weiblich</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="alter" class="form-label">Alter (in Jahren)</label>
                <input type="number" name="alter" id="alter" class="form-control" required min="1">
            </div>
            <div class="col-md-6">
                <label for="gewicht" class="form-label">Gewicht (in kg)</label>
                <input type="number" name="gewicht" id="gewicht" class="form-control" required min="1">
            </div>
            <div class="col-md-6">
                <label for="groesse" class="form-label">Größe (in cm)</label>
                <input type="number" name="groesse" id="groesse" class="form-control" required min="1">
            </div>

            <h3 class="mt-4">PAL-Faktoren (Gesamtzeit darf 24 Stunden nicht überschreiten)</h3>
            <div class="col-md-4">
                <label for="schlafen" class="form-label">Schlafen (Std)</label>
                <input type="number" name="schlafen" id="schlafen" class="form-control" required min="0">
            </div>
            <div class="col-md-4">
                <label for="sitzen" class="form-label">Sitzen (Std)</label>
                <input type="number" name="sitzen" id="sitzen" class="form-control" required min="0">
            </div>
            <div class="col-md-4">
                <label for="stehen" class="form-label">Stehen (Std)</label>
                <input type="number" name="stehen" id="stehen" class="form-control" required min="0">
            </div>
            <div class="col-md-6">
                <label for="sport" class="form-label">Sport (Std)</label>
                <input type="number" name="sport" id="sport" class="form-control" required min="0">
            </div>
            <div class="col-md-6">
                <label for="sonstige" class="form-label">Sonstige (Std)</label>
                <input type="number" name="sonstige" id="sonstige" class="form-control" required min="0">
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary w-100">Berechnen</button>
            </div>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $geschlecht = $_POST['geschlecht'];
            $alter = $_POST['alter'];
            $gewicht = $_POST['gewicht'];
            $groesse = $_POST['groesse'];
            $schlafen = $_POST['schlafen'];
            $sitzen = $_POST['sitzen'];
            $stehen = $_POST['stehen'];
            $sport = $_POST['sport'];
            $sonstige = $_POST['sonstige'];

            // Backend-Validierung
            $errors = [];
            if ($alter < 1) $errors[] = "Das Alter muss größer als 0 sein.";
            if ($gewicht < 1) $errors[] = "Das Gewicht muss größer als 0 sein.";
            if ($groesse < 1) $errors[] = "Die Größe muss größer als 0 sein.";

            $gesamtstunden = $schlafen + $sitzen + $stehen + $sport + $sonstige;
            if ($gesamtstunden > 24) {
                $errors[] = "Die Summe der PAL-Faktoren darf nicht mehr als 24 Stunden betragen.";
            }

            if (!empty($errors)) {
                echo "<div class='alert alert-danger'><ul>";
                foreach ($errors as $error) {
                    echo "<li>$error</li>";
                }
                echo "</ul></div>";
            } else {
                // Berechnung des Kalorienbedarfs
                if ($geschlecht === 'weiblich') {
                    $bmr = 655.1 + (9.6 * $gewicht) + (1.8 * $groesse) - (4.7 * $alter);
                } else {
                    $bmr = 66.47 + (13.7 * $gewicht) + (5 * $groesse) - (6.8 * $alter);
                }

                $durchschnittlicherPAL = ($schlafen * 0.95 + $sitzen * 1.2 + $stehen * 1.85 + $sport * 2.2 + $sonstige * 1.5) / $gesamtstunden;

                $kalorien = $bmr * $durchschnittlicherPAL;

                echo "<div class='mt-4 alert alert-info'>";
                echo "<h4>Ergebnisse</h4>";
                echo "<p>Täglicher Kalorienbedarf: <strong>" . round($kalorien) . " kcal</strong></p>";
                echo "<p>Zum Abnehmen: <strong>" . round($kalorien - 400) . " kcal</strong></p>";
                echo "<p>Zum Zunehmen: <strong>" . round($kalorien + 400) . " kcal</strong></p>";
                echo "</div>";
            }
        }
        ?>
    </div>

    <script>
        const modeToggle = document.getElementById("modeToggle");
        const modeLabel = document.getElementById("modeLabel");
        const body = document.body;

        // Zustand des Dark Modes aus localStorage wiederherstellen
        if (localStorage.getItem("darkMode") === "true") {
            body.classList.add("dark-mode");
            modeToggle.checked = true;
            modeLabel.textContent = "Light Mode";
        }

        // Wechsel des Dark Modes und Speicherung in localStorage
        modeToggle.addEventListener("change", () => {
            if (modeToggle.checked) {
                body.classList.add("dark-mode");
                localStorage.setItem("darkMode", "true");
                modeLabel.textContent = "Light Mode";
            } else {
                body.classList.remove("dark-mode");
                localStorage.setItem("darkMode", "false");
                modeLabel.textContent = "Dark Mode";
            }
        });
    </script>
</body>
</html>
