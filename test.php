<?php
require_once('paramverify.php');

$param = [
  'username' => 'kkzhuang',
  'password' => [1 => '123456'],
];

$verify = [
  'username' =>['required' => true, 'type' => 'string', 're' => '/^$|^([a-z0-9]{6,64})$/'],
  'password.1' =>['required' => true, 'type' => 'string', 're' => '/^[0-9a-zA-Z_]{6,64}$/', 'msg' => 'unexpected RE format!'],
];

$return = Paramverify::verify($param, $verify);

var_dump($return);