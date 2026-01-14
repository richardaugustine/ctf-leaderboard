<?php
session_start();
session_destroy();
header('Location: leaderboard.php');
exit;
?>
