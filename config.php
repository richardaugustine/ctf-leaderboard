<?php
// Supabase PostgreSQL connection (Render will set these)
$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: '5432';
$dbname = getenv('DB_NAME') ?: 'postgres';
$user = getenv('DB_USER') ?: 'postgres';
$pass = getenv('DB_PASS') ?: '';

$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
$pdo = new PDO($dsn, $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// YOUR CTF SETTINGS - EDIT THESE LATER
$CTF_END = strtotime('2026-01-14 22:00:00 IST');  // 10 PM IST
$WIN_FLAGS = 3;  // First to 3 flags wins
$FLAGS = [
    'challenge1' => ['flag{test_challenge1}' => 100],
    'challenge2' => ['flag{test_challenge2}' => 200],
    'challenge3' => ['flag{test_challenge3}' => 300]
    // Add your real flags here later
];
?>
