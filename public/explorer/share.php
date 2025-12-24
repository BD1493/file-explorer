<?php
require_once '../../src/auth.php';
require_once '../../src/json.php';
requireLogin();
$user = currentUser();
$fileId = $_GET['id'] ?? '';
$files = loadJson('../../data/files.json');
$file = null;
foreach($files as $f){if($f['id']===$fileId && $f['owner']===$user){$file=$f;break;}}
if(!$file) die("File not found");

$shares = loadJson('../../data/shares.json');
$success=''; $error='';

function generatePassword($length=4){return substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'),0,$length);}
if($_SERVER['REQUEST_METHOD']==='POST'){
    $sharedWith=trim($_POST['username']);
    $permission=$_POST['permission']==='edit'?'edit':'view';
    $password=trim($_POST['password']); if($password==='') $password=generatePassword();
    if($sharedWith==='') $error="Username required";
    else{
        $shares[]=[
            "id"=>'s_'.bin2hex(random_bytes(4)),
            "file_id"=>$fileId,
            "owner"=>$user,
            "shared_with"=>$sharedWith,
            "password"=>$password,
            "permission"=>$permission,
            "created_at"=>date('c')
        ];
        saveJson('../../data/shares.json',$shares);
        $success="File shared with $sharedWith. Password: $password";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Share File</title></head>
<body>
<h2>Share File: <?= htmlspecialchars($file['filename']) ?></h2>
<?php if($success) echo "<p style='color:green;'>$success</p>"; ?>
<?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
<label>Username:</label><input name="username" required>
<label>Permission:</label><select name="permission"><option value="view">View</option><option value="edit">Edit</option></select>
<label>Password (optional):</label><input name="password">
<button>Share</button>
</form>
<a href="dashboard.php">Back</a>
</body>
</html>
