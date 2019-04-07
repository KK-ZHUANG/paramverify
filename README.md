# Getting Started
``` php
require_once('paramverify.php');
$param = [
  'username' => 'kkzhuang',
  'password' => 'kkzhuang._-+',
];

$verify = [
  'username' =>['re' => '/^([a-z0-9]{6,64})$/'],
  'password' =>['re' => '/^([a-z0-9\.\-_+]{6,64})$/'],
  'gender' =>['required' => false, 'type' => 'string', 're' => '/^$|^(M|F)$/', 'msg' => 'Element "gender" must be empty string or M|F!'],
];

$return = Paramverify::verify($param, $verify);
echo $return['status'] ? 'Yes' : $return['msg'];  //Yes
```

# Configuration
The default configuration array is :
``` php
$config = [
  'required' => true,
  'type' => 'string'
];
```
If you want to change the default configuration, call to Paramverify::config($config).
``` php
require_once('paramverify.php');
$config = [
  'required' => false,
  'type' => 'numeric',
];
Paramverify::config($config);

$param = [
  'username' => 'kkzhuang',
  'password' => 'kkzhuang._-+',
];

$verify = [
  'username' =>['re' => '/^([a-z0-9]{6,64})$/'],
  'password' =>['re' => '/^([a-z0-9\.\-_+]{6,64})$/'],
  'gender' =>['required' => false, 'type' => 'string', 're' => '/^$|^(M|F)$/', 'msg' => 'Element "gender" must be empty string or M|F!'],
];

$return = Paramverify::verify($param, $verify);
echo $return['status'] ? 'Yes' : $return['msg'];  //[username] error code [2]
```

# Error Description
- error code [1]

``` php
require_once('paramverify.php');
$param = [
  'username' => 'kkzhuang',
];

$verify = [
  'username' =>['re' => '/^([a-z0-9]{6,64})$/'],
  'password' =>['re' => '/^([a-z0-9\.\-_+]{6,64})$/', 'msg' => 'Element "password" format error!'],
];

$return = Paramverify::verify($param, $verify);
echo $return['status'] ? 'Yes' : $return['msg'];  //[password] error code [1]
```

- error code [2]

``` php
require_once('paramverify.php');
$param = [
  'username' => 'kkzhuang',
  'password' => 123456,
];

$verify = [
  'username' =>['re' => '/^([a-z0-9]{6,64})$/'],
  'password' =>['re' => '/^([a-z0-9\.\-_+]{6,64})$/', 'msg' => 'Element "password" format error!'],
];

$return = Paramverify::verify($param, $verify);
echo $return['status'] ? 'Yes' : $return['msg'];  //[password] error code [2]
```

- error code [3]

``` php
require_once('paramverify.php');
$param = [
  'username' => 'kkzhuang._-+',
  'password' => 'kkzhuang._-+',
];

$verify = [
  'username' =>['re' => '/^([a-z0-9]{6,64})$/'],
  'password' =>['re' => '/^([a-z0-9\.\-_+]{6,64})$/', 'msg' => 'Element "password" format error!'],
];

$return = Paramverify::verify($param, $verify);
echo $return['status'] ? 'Yes' : $return['msg'];  //[username] error code [3]
```
In verify array,if you define 'msg' for verify element,the msg will replace error code [3].
``` php
require_once('paramverify.php');
$param = [
  'username' => 'kkzhuang._-+',
  'password' => 'kkzhuang._-+',
];

$verify = [
  'username' =>['re' => '/^([a-z0-9]{6,64})$/', 'msg' => 'Element "username" format error!'],
  'password' =>['re' => '/^([a-z0-9\.\-_+]{6,64})$/', 'msg' => 'Element "password" format error!'],
];

$return = Paramverify::verify($param, $verify);
echo $return['status'] ? 'Yes' : $return['msg'];  //Element "username" format error!
```
