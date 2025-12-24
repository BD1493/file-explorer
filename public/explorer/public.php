<?php
require_once '../../src/auth.php';
require_once '../../src/json.php';
require_once '../../src/permissions.php';
requireLogin();
$user=currentUser();
$files=loadJson('../../data/files.json');
?>
<!DOCTYPE html>
<html>
<head><title>Public Files</title></head>
<body>
<h2>Public Files</h2>
<ul>
<?php foreach($files as $f): ?>
<?php if($f['visibility']==='public' && checkPermission($f['id'],$user,'view')): ?>
<li>
<?= htmlspecialchars($f['filename']) ?>
<?php if(checkPermission($f['id'],$user,'edit')): ?>
 — <a href="edit.php?id=<?= $f['id'] ?>">Edit</a>
<?php endif; ?>
</li>
<?php endif; ?>
<?php endforeach; ?>
</ul>
<a href="dashboard.php">Back</a>
</body>
</html>
