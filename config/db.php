<?php
$origin = [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
];
$local = require(__DIR__ . '/db_local.php');
return array_merge($origin, $local);
