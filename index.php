<?php
// =========================================================================
// FIXED: PURE SQLITE - NO POSTGRESQL, NO ENV VARS
// =========================================================================

// SQLite connection (WORKS ON RENDER FREE TIER)
try {
    $db_path = __DIR__ . '/data/ctf.db';
    if (!is_dir(dirname($db_path))) {
        mkdir(dirname($db_path), 0755, true);
    }
    
    $db = new PDO('sqlite:' . $db_path);  // FIXED: $db not $pdo
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // CREATE ALL TABLES
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        points INTEGER DEFAULT 0,
        last_submission DATETIME
    )");
    
    $db->exec("CREATE TABLE IF NOT EXISTS submissions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER,
        flag TEXT NOT NULL,
        points INTEGER DEFAULT 100,
        submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )");
    
    // TEST USERS
    $db->exec("INSERT OR IGNORE INTO users (username, password, points) VALUES 
        ('admin', 'admin123', 0), ('test', 'test123', 0)");
        
} catch(PDOException $e) {
    die("SQLite Error: " . $e->getMessage());
}

session_start();

// Your render_header() and render_footer() functions (keep exactly same)
function render_header($page_title = 'CTF Platform') {
    // ... your existing function - NO CHANGES ...
}

function render_footer() {
    // ... your existing function - NO CHANGES ...
}

// PAGE ROUTER (FIXED $pdo → $db)
$page = $_GET['page'] ?? 'login';

switch ($page) {
    case 'login':
        // YOUR LOGIN CODE - CHANGE $pdo to $db ONLY
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            $stmt = $db->prepare("SELECT id, username, password FROM users WHERE username = ?");  // FIXED
            $stmt->execute([$username]);
            // ... rest same
        }
        render_header('Login');
        // ... your HTML same
        render_footer();
        break;
        
    // Apply same $pdo → $db fix to ALL other cases
    // ... rest of your switch cases with $db instead of $pdo
}
?>
