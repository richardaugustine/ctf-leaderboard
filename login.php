<?php
require 'config.php';
session_start();

if($_POST['team']) {
    $team_name = trim($_POST['team']);
    
    try {
        $stmt = $db->prepare("INSERT INTO users (username) VALUES (?)");
        $stmt->execute([$team_name]);
        $_SESSION['team'] = $team_name;
        $_SESSION['success'] = "Welcome to CTF, {$team_name}! 🎉";
        header('Location: leaderboard.php');
        exit;
    } catch(PDOException $e) {
        $_SESSION['error'] = "❌ Team '$team_name' already exists! Choose another name.";
    }
}

header('Location: leaderboard.php');
exit;
?>
