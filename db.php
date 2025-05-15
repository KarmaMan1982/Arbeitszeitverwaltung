<?php
// db.php
$db = new PDO('sqlite:arbeitszeit.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Tabelle für Benutzer (users)
$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL
)");

// Tabelle für Arbeitszeiten
$db->exec("CREATE TABLE IF NOT EXISTS arbeitszeiten (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    datum TEXT NOT NULL,
    start_time TEXT,
    end_time TEXT,
    pause INTEGER DEFAULT 0,
    status TEXT DEFAULT 'normal',
    FOREIGN KEY(user_id) REFERENCES users(id)
)");
?>
