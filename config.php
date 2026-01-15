<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

try {
    $db = new PDO('sqlite:ctf.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create tables automatically
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    
    $db->exec("CREATE TABLE IF NOT EXISTS submissions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL,
        flag TEXT NOT NULL,
        points INTEGER DEFAULT 100,
        submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE(username, flag),
        FOREIGN KEY (username) REFERENCES users(username)
    )");
    
} catch(PDOException $e) {
    die("❌ Database Error: " . $e->getMessage());
}
?>
