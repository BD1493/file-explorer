<?php
require_once '../../src/auth.php';
require_once '../../src/json.php';
require_once '../../src/permissions.php';
requireLogin();
$user = currentUser();
$id = $_GET['id'] ?? '';

$files = loadJson('../../data/files.json');
$fileMeta = null;
foreach($files as $f){if($f['id']===$id){$fileMeta=$f;break;}}
if(!$fileMeta || !checkPermission($id,$user,'edit')) die("Access denied");

$realPath = '../../'.$fileMeta['path'];
if(isset($_GET['ajax'])){echo file_exists($realPath)?file_get_contents($realPath):''; exit;}

if($_SERVER['REQUEST_METHOD']==='POST'){
    file_put_contents($realPath,$_POST['content']??'');
}

$content = file_exists($realPath)?htmlspecialchars(file_get_contents($realPath)):'';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit <?= htmlspecialchars($fileMeta['filename']) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <script src="/assets/js/app.js"></script>
</head>
<body>
<h2>Editing: <?= htmlspecialchars($fileMeta['filename']) ?></h2>
<form method="post">
<textarea name="content" style="width:100%;height:400px;"><?= $content ?></textarea><br>
<button>Save</button>
</form>
<a href="dashboard.php">Back</a>
</body>
</html>
