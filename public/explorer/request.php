<?php
require_once '../../src/auth.php';
require_once '../../src/json.php';
requireLogin();
$user = currentUser();
$requests = loadJson('../../data/requests.json');

if($_SERVER['REQUEST_METHOD']==='POST'){
    $requests[]=[
        "owner"=>$_POST['owner'],
        "requester"=>$user,
        "permission"=>$_POST['permission'],
        "note"=>$_POST['note']??'',
        "status"=>"pending",
        "created_at"=>date('c')
    ];
    saveJson('../../data/requests.json',$requests);
    echo "Access request sent!";
    exit;
}
