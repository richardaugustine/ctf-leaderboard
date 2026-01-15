<?php
require 'config.php';
session_start();

$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>🏆 CTF Leaderboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php if(isset($_SESSION['team'])): ?>
            <nav>
                <a href="submit.php" class="nav-btn">📝 Submit Flag</a>
                <a href="logout.php" class="nav-btn">🚪 Logout</a>
            </nav>
            <h1>👋 <?= $_SESSION['team'] ?> Team</h1>
        <?php else: ?>
            <h1>🏆 Live CTF Leaderboard</h1>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="success"><?= $success ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <?php if(!isset($_SESSION['team'])): ?>
            <form method="POST" action="login.php" class="form-group">
                <h2>Join CTF</h2>
                <input type="text" name="team" placeholder="Enter Team Name" required maxlength="50">
                <button class="btn">🚀 Join CTF</button>
            </form>
        <?php endif; ?>

        <table class="leaderboard-table">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Team</th>
                    <th>Flags</th>
                    <th>Points</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $db->query("
                    SELECT username, 
                           COUNT(flag) as flags_count,
                           SUM(points) as total_points,
                           ROW_NUMBER() OVER (ORDER BY SUM(points) DESC, COUNT(flag) DESC) as rank
                    FROM submissions 
                    GROUP BY username 
                    ORDER BY total_points DESC, flags_count DESC
                    LIMIT 50
                ");
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                ?>
                <tr>
                    <td>#<?= $row['rank'] ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= $row['flags_count'] ?>/3</td>
                    <td><?= $row['total_points'] ?> pts</td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Mouse trail particles
        document.addEventListener('mousemove', (e) => {
            const particle = document.createElement('div');
            particle.className = 'trail-particle';
            particle.style.left = e.clientX + 'px';
            particle.style.top = e.clientY + 'px';
            particle.style.setProperty('--drift-x', (Math.random() - 0.5) * 20 + 'px');
            particle.style.setProperty('--drift-y', (Math.random() - 0.5) * 20 + 'px');
            document.body.appendChild(particle);
            setTimeout(() => particle.remove(), 500);
        });
    </script>
</body>
</html>
