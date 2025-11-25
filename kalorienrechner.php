<!DOCTYPE html> <!-- Definiert den Dokumenttyp als HTML5 -->
<html lang="de"> <!-- HTML-Dokument beginnt, Sprache Deutsch -->
<head> <!-- Kopfbereich mit Metadaten -->
<meta charset="UTF-8"> <!-- Zeichencodierung auf UTF-8 setzen -->
<meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsives Verhalten für mobile Geräte -->
<title>Kalorienrechner</title> <!-- Titel der Webseite -->

<style> <!-- CSS-Styles beginnen -->
    body { /* Hauptseite Styling */
        font-family: Arial, sans-serif; /* Schriftart */
        background: #f4f4f4; /* Hintergrundfarbe */
        margin: 0; /* Kein äußerer Abstand */
        padding: 30px; /* Innenabstand */
    }

    h1 { /* Styling der Hauptüberschrift */
        text-align: center; /* Zentrieren */
        color: #2c3e50; /* Textfarbe */
        margin-bottom: 25px; /* Abstand nach unten */
    }

    .container { /* Formularcontainer */
        width: 500px; /* Breite */
        margin: 0 auto; /* Zentriert */
        background: white; /* Hintergrund weiß */
        padding: 25px; /* Innenabstand */
        border-radius: 15px; /* Abgerundete Ecken */
        box-shadow: 0 0 25px rgba(0,0,0,0.15); /* Schatten */
    }

    label { /* Beschriftungen */
        font-weight: bold; /* Fett */
        display: block; /* Neue Zeile */
        margin-top: 15px; /* Abstand oberhalb */
    }

    input, select { /* Eingabefelder */
        width: 100%; /* Volle Breite */
        padding: 10px; /* Innenabstand */
        border-radius: 8px; /* Runde Ecken */
        border: 1px solid #bbb; /* Rahmenfarbe */
        margin-top: 5px; /* Abstand oberhalb */
    }

    button { /* Button Styling */
        margin-top: 20px; /* Abstand oben */
        width: 100%; /* Volle Breite */
        padding: 12px; /* Innenabstand */
        background: #4caf50; /* Hintergrundfarbe */
        color: white; /* Textfarbe */
        border: none; /* Kein Rahmen */
        font-size: 18px; /* Textgröße */
        border-radius: 8px; /* Runde Ecken */
        cursor: pointer; /* Mauszeiger als Hand */
    }

    button:hover { /* Hover-Effekt */
        background: #45a049; /* Dunkleres Grün */
    }

    .ergebnis { /* Ergebnisbox */
        width: 500px; /* Breite */
        margin: 30px auto; /* Abstand + Zentrierung */
        background: white; /* Hintergrund */
        padding: 25px; /* Innenabstand */
        border-radius: 15px; /* Runde Ecken */
        box-shadow: 0 0 25px rgba(0,0,0,0.15); /* Schatten */
    }

    .ergebnis h2 { /* Überschrift Ergebnis */
        margin-top: 0; /* Kein oberer Abstand */
        color: #222; /* Textfarbe */
    }

    .ergebnis p strong { /* Zahlen hervorheben */
        color: #c0392b; /* Rote Farbe */
    }
</style> <!-- Ende CSS -->
</head>

<body> <!-- sichtbarer Seiteninhalt beginnt -->

<h1>Kalorienrechner</h1> <!-- Titel der Seite -->

<div class="container"> <!-- Container für Eingabefelder -->

    <label>Geschlecht:</label> <!-- Beschriftung -->
    <select id="geschlecht"> <!-- Auswahlfeld Geschlecht -->
        <option value="m">Männlich</option> <!-- Option männlich -->
        <option value="w">Weiblich</option> <!-- Option weiblich -->
    </select>

    <label>Alter (Jahre):</label> <!-- Beschriftung Alter -->
    <input type="number" id="alter" value="25"> <!-- Eingabefeld Alter -->

    <label>Gewicht (kg):</label> <!-- Beschriftung Gewicht -->
    <input type="number" id="gewicht" value="70"> <!-- Eingabefeld Gewicht -->

    <label>Größe (cm):</label> <!-- Beschriftung Größe -->
    <input type="number" id="groesse" value="175"> <!-- Eingabefeld Größe -->

    <h3>Aktivitäten (Stunden)</h3> <!-- Bereich Aktivitäten -->

    <label>Sitzend / liegend (PAL 1,2):</label> <!-- Aktivität sitzend -->
    <input type="number" step="0.1" id="sitzen" value="4"> <!-- Eingabe Stunden -->

    <label>Büro / sitzende Tätigkeit (PAL 1,45):</label> <!-- Aktivität Büro -->
    <input type="number" step="0.1" id="buero" value="6"> <!-- Eingabe Stunden -->

    <label>Stehen / gehen (PAL 1,85):</label> <!-- Aktivität gehen -->
    <input type="number" step="0.1" id="gehend" value="2"> <!-- Eingabe Stunden -->

    <label>Körperlich anstrengend (PAL 2,2):</label> <!-- Aktivität anstrengend -->
    <input type="number" step="0.1" id="anstrengend" value="0"> <!-- Eingabe Stunden -->

    <label>Schlaf (Stunden, automatisch berechnet wenn leer):</label> <!-- Schlaf -->
    <input type="number" step="0.1" id="schlaf" placeholder="wird automatisch berechnet"> <!-- Eingabe Schlaf -->

    <button onclick="berechnen()">Berechnen</button> <!-- Button zur Berechnung -->
</div>

<div class="ergebnis" id="output" style="display:none;"></div> <!-- Ergebnisbox versteckt -->

<script> <!-- JavaScript beginnt -->
function berechnen() { /* Funktion startet */
    let geschlecht = document.getElementById("geschlecht").value; /* Geschlecht holen */
    let alter      = Number(document.getElementById("alter").value); /* Alter holen */
    let gewicht    = Number(document.getElementById("gewicht").value); /* Gewicht holen */
    let groesse    = Number(document.getElementById("groesse").value); /* Größe holen */

    let sitzen     = Number(document.getElementById("sitzen").value) || 0; /* Stunden sitzen */
    let buero      = Number(document.getElementById("buero").value) || 0; /* Stunden Büro */
    let gehen      = Number(document.getElementById("gehend").value) || 0; /* Stunden gehen */
    let anstrengend= Number(document.getElementById("anstrengend").value) || 0; /* Stunden anstrengend */

    let gesamtAktiv = sitzen + buero + gehen + anstrengend; /* Summe Aktivitäten */

    let schlafInput = document.getElementById("schlaf").value; /* Schlafwert */
    let schlaf = schlafInput ? Number(schlafInput) : Math.max(0, 24 - gesamtAktiv); /* Automatische Berechnung */

    let pal_schlaf = 0.95; /* PAL Schlaf */
    let pal_sitzen = 1.2;  /* PAL sitzen */
    let pal_buero  = 1.45; /* PAL Büro */
    let pal_gehen  = 1.85; /* PAL gehen */
    let pal_anstr  = 2.2;  /* PAL anstrengend */

    let palAvg = (schlaf*pal_schlaf + sitzen*pal_sitzen + buero*pal_buero + gehen*pal_gehen + anstrengend*pal_anstr)/24; /* Durchschnittlicher PAL */

    let grund = (geschlecht==="m") 
        ? 66.47 + (13.7*gewicht) + (5*groesse) - (6.8*alter)  /* Grundumsatz Männer */
        : 655.1 + (9.6*gewicht) + (1.8*groesse) - (4.7*alter); /* Grundumsatz Frauen */

    let gesamt = grund * palAvg; /* Gesamtbedarf */

    let abnehmen = gesamt - 400; /* Empfehlung Kaloriendefizit */
    let zunehmen = gesamt + 400; /* Empfehlung Kalorienüberschuss */

    let output = document.getElementById("output"); /* Ergebnisfeld referenzieren */
    output.style.display = "block"; /* Ergebnisbox einblenden */
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
    `; /* Ergebnis HTML */
}
</script> <!-- Ende JavaScript -->

</body>
</html>
