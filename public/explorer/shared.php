<?php
require_once '../../src/auth.php';
require_once '../../src/json.php';
requireLogin();
$user = currentUser();
$shares = loadJson('../../data/shares.json');
$files = loadJson('../../data/files.json');
$error=''; $fileData=null; $shareInfo=null;

if($_SERVER['REQUEST_METHOD']==='POST'){
    $owner=trim($_POST['owner']);
    $password=trim($_POST['password']);
    foreach($shares as $s){
        if($s['owner']===$owner && $s['shared_with']===$user && $s['password']===$password){
            foreach($files as $f){if($f['id']===$s['file_id']){$fileData=$f;$shareInfo=$s;break 2;}}
        }
    }
    if(!$fileData) $error="Invalid owner/password or file not shared";
}
?>
<!DOCTYPE html>
<html>
<head><title>Get Shared File</title></head>
<body>
<h2>Access Shared File</h2>
<?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
<?php if(!$fileData): ?>
<form method="post">
<input name="owner" placeholder="Owner username" required>
<input name="password" placeholder="Password" required>
<button>Get File</button>
</form>
<?php else: ?>
<p>File: <?= htmlspecialchars($fileData['filename']) ?></p>
<p>Permission: <?= htmlspecialchars($shareInfo['permission']) ?></p>
<a href="edit.php?id=<?= $fileData['id'] ?>">Open</a>
<?php endif; ?>
<a href="dashboard.php">Back</a>
</body>
</html>
