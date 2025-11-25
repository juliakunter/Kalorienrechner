<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kalorienrechner</title>

<style>
    body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 30px; }
    h1 { text-align: center; color: #2c3e50; margin-bottom: 25px; }
    .container { width: 500px; margin: 0 auto; background: white; padding: 25px; border-radius: 15px; box-shadow: 0 0 25px rgba(0,0,0,0.15); }
    label { font-weight: bold; display: block; margin-top: 15px; }
    input, select { width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #bbb; margin-top: 5px; }
    button { margin-top: 20px; width: 100%; padding: 12px; background: #4caf50; color: white; border: none; font-size: 18px; border-radius: 8px; cursor: pointer; }
    button:hover { background: #45a049; }
    .ergebnis { width: 500px; margin: 30px auto; background: white; padding: 25px; border-radius: 15px; box-shadow: 0 0 25px rgba(0,0,0,0.15); }
    .ergebnis h2 { margin-top: 0; color: #222; }
    .ergebnis p strong { color: #c0392b; }
</style>
</head>
<body>

<h1>Kalorienrechner</h1>
<div class="container">
    <label>Geschlecht:</label>
    <select id="geschlecht">
        <option value="m">Männlich</option>
        <option value="w">Weiblich</option>
    </select>

    <label>Alter (Jahre):</label>
    <input type="number" id="alter" value="25">

    <label>Gewicht (kg):</label>
    <input type="number" id="gewicht" value="70">

    <label>Größe (cm):</label>
    <input type="number" id="groesse" value="175">

    <h3>Aktivitäten (Stunden)</h3>

    <label>Sitzend / liegend (PAL 1,2):</label>
    <input type="number" step="0.1" id="sitzen" value="4">

    <label>Büro / sitzende Tätigkeit (PAL 1,45):</label>
    <input type="number" step="0.1" id="buero" value="6">

    <label>Stehen / gehen (PAL 1,85):</label>
    <input type="number" step="0.1" id="gehend" value="2">

    <label>Körperlich anstrengend (PAL 2,2):</label>
    <input type="number" step="0.1" id="anstrengend" value="0">

    <label>Schlaf (Stunden):</label>
    <input type="number" step="0.1" id="schlaf" placeholder="wird automatisch berechnet">

    <button onclick="berechnen()">Berechnen</button>
</div>

<div class="ergebnis" id="output" style="display:none;"></div>

<script>
function berechnen() {
    // Liest das Geschlecht aus dem Dropdown-Menü aus.
    // Wird später benötigt, um die passende Grundumsatzformel zu wählen.
    let geschlecht = document.getElementById("geschlecht").value;

    // Liest die eingegebenen Zahlen für Alter, Gewicht und Größe aus.
    // Number() stellt sicher, dass Strings in echte Zahlen umgewandelt werden.
    let alter      = Number(document.getElementById("alter").value);
    let gewicht    = Number(document.getElementById("gewicht").value);
    let groesse    = Number(document.getElementById("groesse").value);

    // Liest die Aktivitätszeiten ein, wenn keine Eingabe vorhanden ist, wird 0 gesetzt.
    // So wird verhindert, dass NaN in Berechnungen auftaucht.
    let sitzen     = Number(document.getElementById("sitzen").value) || 0;
    let buero      = Number(document.getElementById("buero").value) || 0;
    let gehen      = Number(document.getElementById("gehend").value) || 0;
    let anstrengend= Number(document.getElementById("anstrengend").value) || 0;

    // Summiert alle angegebenen Aktivitätsstunden (außer Schlaf) zusammen.
    let gesamtAktiv = sitzen + buero + gehen + anstrengend;

    // Lese optionalen Schlafwert. Wenn leer, wird Schlaf automatisch berechnet
    // als Rest von 24 Stunden minus die übrigen Aktivitäten.
    let schlafInput = document.getElementById("schlaf").value;
    let schlaf = schlafInput ? Number(schlafInput) : Math.max(0, 24 - gesamtAktiv);

    // Definiert PAL-Werte für jede Aktivitätsart. PAL = Physical Activity Level.
    let pal_schlaf = 0.95;   // Energieverbrauch im Schlaf
    let pal_sitzen = 1.2;    // Sitzende Tätigkeiten (gering aktive Zeit)
    let pal_buero  = 1.45;   // Büroarbeit, sitzende Arbeit
    let pal_gehen  = 1.85;   // Stehen, Gehen, leichte Bewegung
    let pal_anstr  = 2.2;    // körperlich anstrengende Tätigkeit

    // Berechnet den durchschnittlichen PAL über 24 Stunden
    // Jeder Aktivitätswert wird mit dem PAL multipliziert, dann alles addiert und durch 24 geteilt.
    let palAvg = (schlaf*pal_schlaf + sitzen*pal_sitzen + buero*pal_buero + gehen*pal_gehen + anstrengend*pal_anstr) / 24;

    // Berechnet den Grundumsatz (BMR) anhand des Geschlechts.
    // Männer und Frauen haben unterschiedliche Formeln, um den Kalorienverbrauch in Ruhe zu bestimmen.
    let grund = (geschlecht === "m")
        ? 66.47 + (13.7*gewicht) + (5*groesse) - (6.8*alter)   // Männer-Formel
        : 655.1 + (9.6*gewicht) + (1.8*groesse) - (4.7*alter); // Frauen-Formel

    // Gesamtenergiebedarf = Grundumsatz * durchschnittlicher PAL
    // Das berücksichtigt den täglichen Energieverbrauch durch Bewegung.
    let gesamt = grund * palAvg;

    // Faustregel: 400 kcal unter/über Gesamtbedarf für Ab- oder Zunahme
    let abnehmen = gesamt - 400;
    let zunehmen = gesamt + 400;

    // Zeigt die Ergebnisbox an
    let output = document.getElementById("output");
    output.style.display = "block";

    // Schreibt alle berechneten Werte in die Ergebnisbox.
    // Math.round rundet die Werte auf ganze Zahlen.
    output.innerHTML = `
        <h2>Ergebnisse</h2>
        <p><strong>Grundumsatz:</strong> ${Math.round(grund)} kcal</p>
        <p><strong>Gesamtbedarf:</strong> ${Math.round(gesamt)} kcal</p>
        <p>Zum Abnehmen: <strong>${Math.round(abnehmen)} kcal</strong></p>
        <p>Zum Zunehmen: <strong>${Math.round(zunehmen)} kcal</strong></p>
        <h3>Aktivitätsübersicht</h3>
        <p>Sitzend/liegend: ${sitzen} h</p>
        <p>Büro / sitzend: ${buero} h</p>
        <p>Stehen / gehen: ${gehen} h</p>
        <p>Körperlich anstrengend: ${anstrengend} h</p>
        <p><strong>Schlaf:</strong> ${schlaf} h</p>
    `;
}
</script>

</body>
</html>