<?php 
require 'config.php'; 
session_start(); 

if(!isset($_SESSION['team'])) {
    header('Location: leaderboard.php');
    exit;
}

$message = '';
if($_POST['challenge'] && $_POST['flag']) {
    $challenge = trim($_POST['challenge']);
    $flag = trim($_POST['flag']);
    $team_name = $_SESSION['team'];
    
    try {
        // Get team ID
        $stmt = $pdo->prepare("SELECT id FROM teams WHERE name = ?");
        $stmt->execute([$team_name]);
        $team_id = $stmt->fetchColumn();
        
        if(!$team_id) {
            $message = "❌ Team not found. Please rejoin.";
        } elseif(isset($FLAGS[$challenge][$flag])) {
            $points = $FLAGS[$challenge][$flag];
            
            // Insert flag if not already solved
            $stmt = $pdo->prepare("
                INSERT INTO flags(team_id, challenge, flag, points) 
                VALUES(?, ?, ?, ?) 
                ON CONFLICT (team_id, challenge) DO NOTHING
            ");
            $result = $stmt->execute([$team_id, $challenge, $flag, $points]);
            
            if($result) {
                $message = "✅ FLAG ACCEPTED! +{$points} points 🎉";
            } else {
                $message = "✅ Flag already submitted! Nice work!";
            }
        } else {
            $message = "❌ Wrong flag! Keep trying...";
        }
    } catch(Exception $e) {
        $message = "❌ Database error. Try again.";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>📝 Submit Flag - <?= $_SESSION['team'] ?></title>
<style>
body{font-family:Arial;background:#111;color:#0f0;padding:40px;margin:0;}
h1{text-align:center;font-size:28px;}
form{max-width:500px;margin:40px auto;background:#222;padding:30px;border-radius:15px;}
input{display:block;width:100%;padding:15px;margin:15px 0;box-sizing:border-box;font-size:16px;border:1px solid #444;background:#111;color:#0f0;}
input:focus{outline:none;border-color:#0f0;}
label{font-size:16px;font-weight:bold;margin-top:20px;display:block;}
.btn{padding:15px 30px;background:#0a0;color:#fff;border:none;font-size:18px;cursor:pointer;border-radius:8px;width:100%;margin-top:20px;}
.success{color:#0f0;font-size:22px;text-align:center;padding:20px;background:#0a5a0a;border-radius:10px;margin:20px 0;}
.error{color:#f66;font-size:20px;text-align:center;padding:20px;background:#5a0a0a;border-radius:10px;margin:20px 0;}
a{color:#ff0;text-decoration:none;}
.back-link{text-align:center;display:block;margin-top:30px;}
</style>
</head>
<body>
<h1>📝 <?= $_SESSION['team'] ?> - Submit Flag</h1>

<?php if($message): ?>
<div class="<?= strpos($message, '✅') !== false ? 'success' : 'error' ?>">
    <?= $message ?>
</div>
<?php endif; ?>

<form method="POST">
<label>Challenge ID:</label>
<input type="text" name="challenge" placeholder="challenge1" required maxlength="20" value="<?= $_POST['challenge'] ?? '' ?>">

<label>Flag:</label>
<input type="text" name="flag" placeholder="flag{xxxxxxxx}" required maxlength="100" value="<?= $_POST['flag'] ?? '' ?>">

<button class="btn">🚀 Submit Flag</button>
</form>

<div class="back-link">
<a href="leaderboard.php">← Back to Live Leaderboard</a>
</div>

<div style="text-align:center;color:#888;margin-top:40px;font-size:14px;">
💡 Test flags: challenge1=flag{test_challenge1}, challenge2=flag{test_challenge2}, challenge3=flag{test_challenge3}
</div>
</body>
</html>
