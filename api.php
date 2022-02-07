<?php

use Controller\Db;

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$config = require 'src/bootstrap.php';

$db =  new Db($config);

$config = $db->getConfigItems();
$config['setting'] = $db->getSetting();

echo json_encode($config);

exit();