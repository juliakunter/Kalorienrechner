<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8"> <!-- Zeichencodierung auf UTF-8 setzen -->
<meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Für mobile Geräte optimieren -->
<title>Kalorienrechner</title> <!-- Seitentitel -->

<style>
    /* Body Styling: Schriftart, Hintergrundfarbe, Abstand */
    body {
        font-family: Arial, sans-serif; /* Schriftart setzen */
        background: #f4f4f4; /* Hellgrauer Hintergrund */
        margin: 0; /* Kein Standard-Margin */
        padding: 30px; /* Innenabstand rundum */
    }

    /* Überschrift Styling */
    h1 {
        text-align: center; /* Überschrift zentrieren */
        color: #2c3e50; /* Dunkelblaue Farbe */
        margin-bottom: 25px; /* Abstand nach unten */
    }

    /* Container für Formular: Breite, Zentrierung, Hintergrund, Padding, Schatten */
    .container {
        width: 500px; /* Breite des Containers */
        margin: 0 auto; /* Zentriert horizontal */
        background: white; /* Hintergrundfarbe weiß */
        padding: 25px; /* Innenabstand */
        border-radius: 15px; /* Abgerundete Ecken */
        box-shadow: 0 0 25px rgba(0,0,0,0.15); /* Schatten für 3D Effekt */
    }

    /* Label Styling: Fett, Abstand nach oben */
    label {
        font-weight: bold; /* Fett */
        display: block; /* Blockelement */
        margin-top: 15px; /* Abstand nach oben */
    }

    /* Input und Select Styling: volle Breite, Padding, Rahmen, Abstand nach oben */
    input, select {
        width: 100%; /* volle Breite */
        padding: 10px; /* Innenabstand */
        border-radius: 8px; /* Abgerundete Ecken */
        border: 1px solid #bbb; /* Grauer Rahmen */
        margin-top: 5px; /* Abstand nach oben */
    }

    /* Button Styling: volle Breite, Hintergrundfarbe, Textfarbe, Padding, abgerundete Ecken, Hover-Effekt */
    button {
        margin-top: 20px; /* Abstand nach oben */
        width: 100%; /* volle Breite */
        padding: 12px; /* Innenabstand */
        background: #4caf50; /* Grün */
        color: white; /* Weißer Text */
        border: none; /* Kein Rahmen */
        font-size: 18px; /* Schriftgröße */
        border-radius: 8px; /* Abgerundete Ecken */
        cursor: pointer; /* Mauszeiger zeigt Hand */
    }

    button:hover {
        background: #45a049; /* Dunkleres Grün beim Hover */
    }

    /* Ergebnisbox Styling: ähnlich wie Container */
    .ergebnis {
        width: 500px; /* Breite */
        margin: 30px auto; /* Abstand oben und zentriert */
        background: white; /* Hintergrundfarbe weiß */
        padding: 25px; /* Innenabstand */
        border-radius: 15px; /* Abgerundete Ecken */
        box-shadow: 0 0 25px rgba(0,0,0,0.15); /* Schatten */
    }

    /* Überschrift innerhalb der Ergebnisbox */
    .ergebnis h2 {
        margin-top: 0; /* Kein Abstand nach oben */
        color: #222; /* Dunkelgrau */
    }

    /* Fettgedruckte Zahlen in Rot hervorheben */
    .ergebnis p strong {
        color: #c0392b; /* Rot */
    }
</style>
</head>

<body>

<h1>Kalorienrechner</h1> <!-- Hauptüberschrift -->

<!-- Container für das Eingabeformular -->
<div class="container">

    <!-- Geschlecht Auswahl -->
    <label>Geschlecht:</label>
    <select id="geschlecht">
        <option value="m">Männlich</option> <!-- Wert m = männlich -->
        <option value="w">Weiblich</option> <!-- Wert w = weiblich -->
    </select>

    <!-- Alter Input -->
    <label>Alter (Jahre):</label>
    <input type="number" id="alter" value="25"> <!-- Standardwert 25 Jahre -->

    <!-- Gewicht Input -->
    <label>Gewicht (kg):</label>
    <input type="number" id="gewicht" value="70"> <!-- Standardwert 70kg -->

    <!-- Größe Input -->
    <label>Größe (cm):</label>
    <input type="number" id="groesse" value="175"> <!-- Standardwert 175cm -->

    <h3>Aktivitäten (Stunden)</h3> <!-- Überschrift Aktivitäten -->

    <!-- Verschiedene Aktivitätsstunden -->
    <label>Sitzend / liegend (PAL 1,2):</label>
    <input type="number" step="0.1" id="sitzen" value="4">

    <label>Büro / sitzende Tätigkeit (PAL 1,45):</label>
    <input type="number" step="0.1" id="buero" value="6">

    <label>Stehen / gehen (PAL 1,85):</label>
    <input type="number" step="0.1" id="gehend" value="2">

    <label>Körperlich anstrengend (PAL 2,2):</label>
    <input type="number" step="0.1" id="anstrengend" value="0">

    <!-- Schlaf Input: optional -->
    <label>Schlaf (Stunden, automatisch berechnet wenn leer):</label>
    <input type="number" step="0.1" id="schlaf" placeholder="wird automatisch berechnet">

    <!-- Berechnen Button -->
    <button onclick="berechnen()">Berechnen</button>
</div>

<!-- Ergebnisbox, initial unsichtbar -->
<div class="ergebnis" id="output" style="display:none;"></div>

<script>
// Funktion zum Berechnen der Kalorien
function berechnen() {
    // Werte aus den Inputs holen
    let geschlecht = document.getElementById("geschlecht").value;
    let alter      = Number(document.getElementById("alter").value);
    let gewicht    = Number(document.getElementById("gewicht").value);
    let groesse    = Number(document.getElementById("groesse").value);

    let sitzen     = Number(document.getElementById("sitzen").value) || 0;
    let buero      = Number(document.getElementById("buero").value) || 0;
    let gehen      = Number(document.getElementById("gehend").value) || 0;
    let anstrengend= Number(document.getElementById("anstrengend").value) || 0;

    // Summe aller Aktivitäten
    let gesamtAktiv = sitzen + buero + gehen + anstrengend;

    // Schlaf berechnen, falls nicht angegeben
    let schlafInput = document.getElementById("schlaf").value;
    let schlaf = schlafInput ? Number(schlafInput) : Math.max(0, 24 - gesamtAktiv);

    // PAL-Faktoren
    let pal_schlaf = 0.95;
    let pal_sitzen = 1.2;
    let pal_buero  = 1.45;
    let pal_gehen  = 1.85;
    let pal_anstr  = 2.2;

    // Durchschnittlicher PAL-Wert
    let palAvg = (schlaf*pal_schlaf + sitzen*pal_sitzen + buero*pal_buero + gehen*pal_gehen + anstrengend*pal_anstr)/24;

    // Grundumsatz berechnen
    let grund = (geschlecht==="m") 
        ? 66.47 + (13.7*gewicht) + (5*groesse) - (6.8*alter) 
        : 655.1 + (9.6*gewicht) + (1.8*groesse) - (4.7*alter);

    // Gesamtbedarf berechnen
    let gesamt = grund * palAvg;

    // Empfehlungen zum Abnehmen/Zunehmen
    let abnehmen = gesamt - 400;
    let zunehmen = gesamt + 400;

    // Ergebnisbox anzeigen und füllen
    let output = document.getElementById("output");
    output.style.display = "block"; // sichtbar machen
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
