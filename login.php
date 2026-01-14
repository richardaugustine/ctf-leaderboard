<?php
require 'config.php'; 
session_start();

if($_POST['team']) {
    $team = trim($_POST['team']);
    if(strlen($team) < 2 || strlen($team) > 20) {
        $_SESSION['error'] = "Team name must be 2-20 characters";
        header('Location: leaderboard.php');
        exit;
    }
    
    try {
        $stmt = $db->prepare("INSERT INTO teams(name) VALUES(?) ON CONFLICT (name) DO NOTHING");  // ← FIXED $db
        $stmt->execute([$team]);
        $_SESSION['team'] = $team;
        $_SESSION['success'] = "Welcome to CTF, $team!";
    } catch(Exception $e) {
        $_SESSION['error'] = "Error joining. Try different team name.";
    }
}

header('Location: leaderboard.php');
exit;
?>
