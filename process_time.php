<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $datum = $_POST['datum'];
    $status = $_POST['status'];
    
    // Für regulären Arbeitstag (Status "normal") werden Start-/Endzeit und Pause erfasst
    if ($status === 'normal') {
        $start_time = $_POST['start_time'] ?? null;
        $end_time = $_POST['end_time'] ?? null;
        $pause = isset($_POST['pause']) ? intval($_POST['pause']) : 0;
    } else {
        $start_time = null;
        $end_time = null;
        $pause = 0;
    }
    
    $stmt = $db->prepare("INSERT INTO arbeitszeiten (user_id, datum, start_time, end_time, pause, status) VALUES (:user_id, :datum, :start_time, :end_time, :pause, :status)");
    $stmt->execute([
        ':user_id'    => $user_id,
        ':datum'      => $datum,
        ':start_time' => $start_time,
        ':end_time'   => $end_time,
        ':pause'      => $pause,
        ':status'     => $status
    ]);
    $message = "Zeit erfolgreich eingetragen.";
    header("Location: time_entry.php?message=" . urlencode($message));
    exit;
}
?>
