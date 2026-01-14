<?php require 'config.php'; session_start(); ?>
<!DOCTYPE html>
<html>
<head><title>🏆 CTF Leaderboard</title>
<meta http-equiv="refresh" content="10">
<style>
body{font-family:Arial;background:#111;color:#0f0;padding:20px;margin:0;}
h1{text-align:center;font-size:36px;margin:10px;}
table{width:100%;border-collapse:collapse;margin:20px 0;} 
th,td{padding:15px;border:1px solid #333;text-align:center;}
th{background:#222;font-size:18px;}
.winner{background:#0f0 !important;color:#000;font-size:24px;font-weight:bold;}
.timer{font-size:32px;color:#ff0;text-align:center;padding:20px;background:#222;margin:20px 0;border-radius:10px;}
.login{position:absolute;top:20px;right:20px;background:#222;padding:20px;border-radius:10px;max-width:300px;}
.login input{padding:12px;width:100%;margin:8px 0;box-sizing:border-box;font-size:16px;}
.btn{padding:12px 24px;background:#0a0;color:#fff;border:none;font-size:16px;cursor:pointer;border-radius:5px;}
.team-info{background:#222;padding:20px;border-radius:10px;margin:10px 0;text-align:center;}
</style></head>
<body>
<h1>🏆 LIVE CTF LEADERBOARD</h1>

<div class="timer">
<?php 
$now=time(); 
if($now>$CTF_END) echo '🏁 EVENT FINISHED - CHECK WINNERS!';
else echo '⏰ Time left: '.gmdate('H:i:s', $CTF_END-$now);
?>
</div>

<?php if(!isset($_SESSION['team'])): ?>
<div class="login">
<h2 style="margin-top:0;">👥 Join CTF</h2>
<form method="POST" action="login.php">
<input type="text" name="team" placeholder="Team Name" required maxlength="20">
<button class="btn" style="width:100%;">🚀 Join Now</button>
</form>
<p style="font-size:12px;color:#888;margin-top:10px;">First to 3 flags wins 🏆</p>
</div>
<?php else: ?>
<div class="team-info">
👋 <strong style="color:#ff0;"><?= $_SESSION['team'] ?></strong> | 
<a href="submit.php" style="color:#ff0;">📝 Submit Flag</a> | 
<a href="logout.php" style="color:#ff0;">🚪 Logout</a>
</div>
<?php endif; ?>

<h2 style="text-align:center;">🏅 TOP TEAMS</h2>
<table>
<tr><th>Rank</th><th>Team</th><th>Flags / 3</th><th>Score</th></tr>
<?php
try {
$stmt=$pdo->query("SELECT t.name,COUNT(f.id) as flags,COALESCE(SUM(f.points),0) as score FROM teams t LEFT JOIN flags f ON t.id=f.team_id GROUP BY t.id ORDER BY flags DESC,score DESC,t.created_at ASC LIMIT 20");
$rank=1; 
foreach($stmt as $r):
$is_winner=($r['flags']>=$WIN_FLAGS);
?>
<tr class="<?= $is_winner?'winner':'' ?>">
<td style="font-size:24px;"><?= $rank++ ?></td>
<td style="font-weight:bold;"><?= htmlspecialchars($r['name']) ?></td>
<td style="font-size:20px;"><?= $r['flags'] ?>/3</td>
<td><?= number_format($r['score']) ?> pts</td>
</tr>
<?php endforeach; 
} catch(Exception $e) { 
echo "<tr><td colspan=4 style='color:#f66;'>🔄 Database connecting... please wait (first load)</td></tr>"; 
}
?>
</table>

<div style="text-align:center;color:#888;margin-top:30px;font-size:14px;">
🔄 Auto-refresh every 10 seconds • <?= date('H:i:s T') ?> • Public CTF Leaderboard
</div>
</body></html>
