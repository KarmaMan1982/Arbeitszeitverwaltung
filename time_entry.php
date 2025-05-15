<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
require_once 'db.php';
$message = '';
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Arbeitszeit erfassen</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { max-width: 500px; margin: auto; }
        input, select { width: 100%; padding: 8px; margin: 5px 0; }
        .message { color: green; text-align: center; }
    </style>
    <script>
        function validateForm() {
            var status = document.getElementById("status").value;
            if (status === "normal") {
                var startTime = document.getElementById("start_time").value;
                var endTime = document.getElementById("end_time").value;
                var pause = parseInt(document.getElementById("pause").value, 10);
                if (!startTime || !endTime) {
                    alert("Bitte Start- und Endzeit angeben.");
                    return false;
                }
                // Berechnung der Arbeitszeit in Minuten
                var start = new Date("1970-01-01T" + startTime + "Z");
                var end = new Date("1970-01-01T" + endTime + "Z");
                var diff = (end - start) / 60000; // Differenz in Minuten
                if (diff < 0) {
                    alert("Die Endzeit muss nach der Startzeit liegen.");
                    return false;
                }
                // Gemäß den gesetzlichen Vorgaben:
                // Bei > 6 Stunden (360 Minuten) mindestens 30 Min Pause,
                // bei > 9 Stunden (540 Minuten) mindestens 45 Min Pause.
                if (diff > 360 && pause < 30) {
                    alert("Bei mehr als 6 Stunden Arbeitszeit muss die Pause mindestens 30 Minuten betragen.");
                    return false;
                }
                if (diff > 540 && pause < 45) {
                    alert("Bei mehr als 9 Stunden Arbeitszeit muss die Pause mindestens 45 Minuten betragen.");
                    return false;
                }
            }
            return true;
        }
        function toggleTimeFields() {
            var status = document.getElementById("status").value;
            var timeFields = document.getElementById("timeFields");
            if (status === "normal") {
                timeFields.style.display = "block";
                document.getElementById("start_time").required = true;
                document.getElementById("end_time").required = true;
            } else {
                timeFields.style.display = "none";
                document.getElementById("start_time").required = false;
                document.getElementById("end_time").required = false;
            }
        }
        window.onload = toggleTimeFields;
    </script>
</head>
<body>
    <h2>Arbeitszeit erfassen</h2>
    <?php if ($message): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>
    <form method="post" action="process_time.php" onsubmit="return validateForm();">
        <label for="datum">Datum:</label>
        <input type="date" id="datum" name="datum" required value="<?php echo date('Y-m-d'); ?>">
        
        <label for="status">Status:</label>
        <select id="status" name="status" required onchange="toggleTimeFields();">
            <option value="normal">Arbeiten</option>
            <option value="urlaub">Urlaub</option>
            <option value="feiertag">Feiertag</option>
            <option value="frei">Arbeitsfrei</option>
        </select>
        
        <!-- Felder für Arbeitszeiten (nur bei Status "normal") -->
        <div id="timeFields">
            <label for="start_time">Startzeit:</label>
            <input type="time" id="start_time" name="start_time">
            
            <label for="end_time">Endzeit:</label>
            <input type="time" id="end_time" name="end_time">
            
            <label for="pause">Pause (Minuten):</label>
            <input type="number" id="pause" name="pause" min="0" value="0">
        </div>
        
        <input type="submit" value="Eintragen">
    </form>
    <p><a href="logout.php">Abmelden</a></p>
</body>
</html>
