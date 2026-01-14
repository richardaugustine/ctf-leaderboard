CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS submissions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL,
    flag TEXT NOT NULL,
    points INTEGER DEFAULT 100,
    submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(username, flag)
);

INSERT OR IGNORE INTO users (username, password) VALUES 
('admin', 'admin123'),
('test', 'test123');
