<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aufgabe4</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <h1>Kalorienrechner made by Schloffer</h1>

    <!--Eingabefelder & Dropdown-->
    <form method="post">
        <label for="geschlecht">Geschlecht:</label><br>
        <select name="geschlecht" id="geschlecht" required>
            <option value="">Bitte wählen</option>
            <option value="1">Männlich</option>
            <option value="0">Weiblich</option>
        </select><br>

        <label for="alter">Alter:</label><br>
        <input type="number" id="alter" name="alter" value="<?= $alter ?>" required><br>

        <label for="größe">Größe:</label><br>
        <input type="number" id="größe" name="größe" step="0.01" value="<?= $größe ?>" required><br>
        <!--step = "0.1" erlaubt Kommazahlen-->

        <label for="gewicht">Gewicht:</label><br>
        <input type="number" id="gewicht" name="gewicht" step="0.01" value="<?= $gewicht ?>"><br><br>


        <h2>Aktivitäten: </h2>
        <label for="ausschliesslichsitzend">Ausschließlich sitzend/liegend:</label><br>
        <input type="number" id="ausschliesslichsitzend" name="ausschliesslichsitzend" step="0.1"
            value="<?= $ausschliesslichsitzend ?>"><br>

        <label for="vorwiegendsitzend">Vorwiegend sitzende Tätigkeit:</label><br>
        <input type="number" id="vorwiegendsitzend" name="vorwiegendsitzend" step="0.1"
            value="<?= $vorwiegendsitzend ?>"><br>

        <label for="überwiegendsitzend">Überwiegend sitzende Tätigkeit:</label><br>
        <input type="number" id="überwiegendsitzend" name="überwiegendsitzend" step="0.1"
            value="<?= $überwiegendsitzend ?>"><br>

        <label for="hauptsächlichstehend">Hauptsächlich stehende Tätigkeit:</label><br>
        <input type="number" id="hauptsächlichstehend" name="hauptsächlichstehend" step="0.1"
            value="<?= $hauptsächlichstehend ?>"><br>

        <label for="körperlichanstrengend">Körperliche anstrengende Arbeiten:</label><br>
        <input type="number" id="körperlichanstrengend" name="körperlichanstrengend" step="0.1"
            value="<?= $körperlichanstrengend ?>"><br>


        <input type="submit" name="submit" value="Berechnen"><br><br>
    </form>

    <?php
    // Werte der Variablen setzen
    $geschlecht = 0;
    $alter = 0;
    $größe = 0;
    $gewicht = 0;
    $ausschliesslichsitzend = 0;
    $vorwiegendsitzend = 0;
    $überwiegendsitzend = 0;
    $hauptsächlichstehend = 0;
    $körperlichanstrengend = 0;

    if (isset($_POST["submit"])) {

        //Eingabe aus dem Formular in Zahlen umwandeln
        $geschlecht = (float) ($_POST["geschlecht"]);
        $alter = (float) ($_POST["alter"]);
        $größe = (float) ($_POST["größe"]);
        $gewicht = (float) ($_POST["gewicht"]);
        $ausschliesslichsitzend = (float) ($_POST["ausschliesslichsitzend"]);
        $vorwiegendsitzend = (float) ($_POST["vorwiegendsitzend"]);
        $überwiegendsitzend = (float) ($_POST["überwiegendsitzend"]);
        $hauptsächlichstehend = (float) ($_POST["hauptsächlichstehend"]);
        $körperlichanstrengend = (float) ($_POST["körperlichanstrengend"]);
    }


    //Berechnen des Kalorienbedarfs
    if ($geschlecht == 0) {
        //Frau
        $kalorienbedarf = 655.1 + (9.6 * $gewicht) + (1.8 * $größe) + (4.7 * $alter);
    } else {
        //Mann
        $kalorienbedarf = 66.47 + (13.7 * $gewicht) + (5 * $größe) + (6.8 * $alter);
    }

    //Berechnung der gesamt Aktivitäten & der Zeit des Schalfens
    $gesamtAktivitäten = $ausschliesslichsitzend + $vorwiegendsitzend + $überwiegendsitzend + $hauptsächlichstehend + $körperlichanstrengend;
    $schlafen = max(0, 24 - $gesamtAktivitäten);

    $pal_schlafen = 0.95;
    $pal_ausschliesslichsitzend = 1.2;
    $pal_vorwiegendsitzend = 1.4;
    $pal_überwiegendsitzend = 1.6;
    $pal_hauptsächlichstehend = 1.8;
    $pal_körperlichanstrengend = 2.0;

    //Berechnung für den gesamten PAL Faktors
    $pal_durchschnitt =
        ($schlafen * $pal_schlafen +
            $ausschliesslichsitzend * $pal_ausschliesslichsitzend +
            $vorwiegendsitzend * $pal_vorwiegendsitzend +
            $überwiegendsitzend * $pal_überwiegendsitzend +
            $hauptsächlichstehend * $pal_hauptsächlichstehend +
            $körperlichanstrengend * $pal_körperlichanstrengend) / 24;

    //Berechnung der gesamten Kalorien
    $gesamtKalorien = $kalorienbedarf * $pal_durchschnitt;

    ?>


    <?php
    //Ausgabe der Eingaben & dem Ergebnis des Kalorienrechners
    echo "<h2>Ihre Eingaben sind: </h2>";
    echo "Geschlecht: " . ($geschlecht == 1 ? "Männlich" : "Weiblich") . "<br>";
    echo "Alter: $alter Jahre <br>";
    echo "Gewicht: $gewicht kg <br>";
    echo "Größe $größe cm <br>";

    echo "<h3>Täglicher Kalorienbedarf</h3>";
    echo "Um Ihr aktuelles Gewicht zu halten, benötigen Sie ca. <strong>" . round($gesamtKalorien, 2) . " kcal</strong> pro Tag.<br><br>";

    //Berechnung für Abnehmen bzw. Zunehmen
    $abnehmen = $gesamtKalorien - 400;
    $zunehmen = $gesamtKalorien + 400;

    echo "<h3>Empfehlungen</h3>";
    echo "Für Abnehmen: ca. <strong>" . round($abnehmen, 2) . " kcal</strong> täglich (≈ 400 kcal weniger).<br>";
    echo "Für Zunehmen: ca. <strong>" . round($zunehmen, 2) . " kcal</strong> täglich (≈ 400 kcal mehr).<br><br>";

    echo "<h3>Aktivitätsübersicht</h3>";
    echo "Ausschließlich sitzend/liegend: $ausschliesslichsitzend h<br>";
    echo "Vorwiegend sitzend: $vorwiegendsitzend h<br>";
    echo "Überwiegend sitzend: $überwiegendsitzend h<br>";
    echo "Hauptsächlich stehend: $hauptsächlichstehend h<br>";
    echo "Körperlich anstrengend: $körperlichanstrengend h<br>";
    echo "Schlafzeit automatisch berechnet: $schlafen h<br><br>";


    ?>
</body>

</html>