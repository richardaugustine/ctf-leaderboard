<?php
try {
    $db_path = __DIR__ . '/data/ctf.db';
    
    // Create data directory
    $data_dir = dirname($db_path);
    if (!is_dir($data_dir)) {
        mkdir($data_dir, 0755, true);
    }
    
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // CREATE TABLES DIRECTLY (no init.sql needed)
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL
    )");
    
    $db->exec("CREATE TABLE IF NOT EXISTS submissions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL,
        flag TEXT NOT NULL,
        points INTEGER DEFAULT 100,
        submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE(username, flag)
    )");
    
    // Add test users
    $db->exec("INSERT OR IGNORE INTO users (username, password) VALUES 
        ('admin', 'admin123'), 
        ('test', 'test123')");
    
} catch(PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>
