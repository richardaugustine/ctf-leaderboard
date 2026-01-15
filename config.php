<?php
try {
    $db = new PDO('sqlite:ctf.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY AUTOINCREMENT, username TEXT UNIQUE NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP)");
    $db->exec("CREATE TABLE IF NOT EXISTS submissions (id INTEGER PRIMARY KEY AUTOINCREMENT, username TEXT NOT NULL, flag TEXT NOT NULL, points INTEGER DEFAULT 100, submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP, UNIQUE(username, flag))");
} catch(PDOException $e) {
    http_response_code(500);
    die("Service temporarily unavailable.");
}
?>
