<?php
try {
    $db_path = __DIR__ . '/data/ctf.db';
    
    // Create data directory if missing
    if (!is_dir(dirname($db_path))) {
        mkdir(dirname($db_path), 0755, true);
    }
    
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Initialize tables on first run
    $init_sql = file_get_contents(__DIR__ . '/init.sql');
    $db->exec($init_sql);
    
} catch(PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>
