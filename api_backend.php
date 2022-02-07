<?php

use Controller\Db;

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$config = require 'src/bootstrap.php';

$db =  new Db($config);



if ($_POST['category_save']) {

    $isUpdate = $db->updateConfig($_POST);

    $categoryConfig = $db->getConfigItem($_POST['category_id']);
    $categoryConfig['name']= $_POST['name'];
    $categoryConfig['shop']= $_POST['shop'];
    $categoryConfig['id'] = $_POST['category_id'];

    echo json_encode(array('is_update'=>$isUpdate));
}
elseif(!$_POST['setting_save']){
    $categoryConfig = $db->getConfigItem($_GET['id']);
    $categoryConfig['name']= $_GET['name'];
    $categoryConfig['shop']= $_GET['shop'];
    $categoryConfig['id'] = $_GET['id'];

    echo json_encode($categoryConfig);
}

if($_POST['setting_save']){
    $isUpdate = $db->updateSetting($_POST);
    echo json_encode(array('is_update'=>$isUpdate));
}

exit();