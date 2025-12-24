<?php
require_once '../../src/auth.php';
require_once '../../src/json.php';
requireLogin();
$user = currentUser();
$files = loadJson('../../data/files.json');

if(!empty($_FILES['file'])){
    $file = $_FILES['file'];
    $target = "../../storage/users/$user/".$file['name'];
    move_uploaded_file($file['tmp_name'],$target);

    $files[] = [
        "id"=> 'f_'.bin2hex(random_bytes(4)),
        "owner"=> $user,
        "filename"=> $file['name'],
        "path"=> "storage/users/$user/".$file['name'],
        "type"=> "file",
        "children"=> null,
        "visibility"=> "private",
        "created_at"=> date('c')
    ];
    saveJson('../../data/files.json',$files);
}
if(!isset($_GET['ajax'])) header("Location: dashboard.php");
