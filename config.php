<?php
// Make $db globally available
global $db;

try {
    $db_path = __DIR__ . '/data/ctf.db';
    
    // Create data directory with proper permissions
    $data_dir = dirname($db_path);
    if (!is_dir($data_dir)) {
        mkdir($data_dir, 0755, true);
    }
    
    // Connect to SQLite
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // CREATE ALL CTF TABLES
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    
    $db->exec("CREATE TABLE IF NOT EXISTS teams (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT UNIQUE NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    
    $db->exec("CREATE TABLE IF NOT EXISTS submissions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL,
        team TEXT,
        flag TEXT NOT NULL,
        points INTEGER DEFAULT 100,
        category TEXT,
        submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE(username, flag)
    )");
    
    // TEST ACCOUNTS (plaintext passwords for SQLi)
    $db->exec("INSERT OR IGNORE INTO users (username, password) VALUES 
        ('admin', 'admin123'), 
        ('test', 'test123'),
        ('guest', 'guest')");
    
    $db->exec("INSERT OR IGNORE INTO teams (name) VALUES ('Red Team'), ('Blue Team')");

} catch(PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>
