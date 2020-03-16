# Why I use it?
## before
```php
$_POST = [
  'username' => 'kkzhuang',
  'password' => 'kkzhuang',
  'gender' => 'M',
];

if(@!$_POST['username'])
{
  exit('username is required!');
}
if(!preg_match('/^[0-9A-Za-z]{6,64}$/', $_POST['username']))
{
  exit('username re mismatch!');
}
if(@!$_POST['password'])
{
  exit('password is required!');
}
if(!preg_match('/^[0-9A-Za-z\.\-_+]{6,64}$/', $_POST['password']))
{
  exit('password re mismatch!');
}
if(@!$_POST['gender'])
{
  exit('gender is required!');
}
if(!preg_match('/^(M|F)$/', $_POST['gender']))
{
  exit('gender re mismatch!');
}
...
```

## after
```php
$_POST = [
  'username' => 'kkzhuang',
  'password' => 'kkzhuang',
  'gender' => 'M',
];

$verify = [
  'username' => ['re' => '/^[0-9A-Za-z]{6,64}$/'],
  'password' => ['re' => '/^[0-9A-Za-z\.\-_+]{6,64}$/'],
  'gender' =>['re' => '/^$|^(M|F)$/'],
];

$return = (new Paramverify)->verify($_POST, $verify);
exit($return['msg']);
...
```

# Getting Started
```php
require 'Paramverify.php';
use Paramverify\Paramverify;
$param = [
  'username' => 'kkzhuang',
  'password' => 'kkzhuang._-+',
];

$verify = [
  'username' =>['re' => '/^([a-z0-9]{6,64})$/'],
  'password' =>['re' => '/^([a-z0-9\.\-_+]{6,64})$/'],
  'gender' =>['required' => false, 'type' => 'string', 're' => '/^$|^(M|F)$/', 'msg' => 'Element "gender" must be empty string or M|F!'],
];

$return = (new Paramverify)->verify($param, $verify);
echo $return['status'] ? 'Yes' : $return['msg'];  //Yes
```

# Configuration
The default configuration array is :
```php
$config = [
  'required' => true,
  'type' => 'string',
  're' => '',
  'msg' => null,
  'filters' => [],
  'is_defined' => true,
];
```
If you want to change the default configuration, call to (new Paramverify)->config($config).
```php
require 'Paramverify.php';
use Paramverify\Paramverify;

$param = [
  'username' => '  kkzhuang  ',
  'password' => 'kkzhuang._-+',
  'email' => 'kk@kk.com',
];

$config = [
  'required' => false,
  'is_defined' => false,
];

$verify = [
  'username' => ['required' => true, 'type' => 'string', 're' => '/^[0-9A-Za-z]{6,64}$/', 'filters' => ['trim', 'strtoupper']],
  'password' => ['required' => true, 're' => '/^[0-9A-Za-z\.\-_+]{6,64}$/'],
  'gender' =>[ 're' => '/^$|^(M|F)$/'],
];

$return = (new Paramverify)->config($config)->verify($param, $verify);
var_dump($return);
```

# Error Description
## error code [1]
```php
require 'Paramverify.php';
use Paramverify\Paramverify;

$param = [
  'username' => 'kkzhuang',
];

$verify = [
  'username' =>['re' => '/^([a-z0-9]{6,64})$/'],
  'password' =>['re' => '/^([a-z0-9\.\-_+]{6,64})$/', 'msg' => 'Element "password" format error!'],
];

$return = (new Paramverify)->verify($param, $verify);
echo $return['status'] ? 'Yes' : $return['msg'];  //[password] error code [1]
```

## error code [2]
```php
$param = [
  'username' => 'kkzhuang',
  'password' => 123456,
];

$verify = [
  'username' =>['re' => '/^([a-z0-9]{6,64})$/'],
  'password' =>['re' => '/^([a-z0-9\.\-_+]{6,64})$/', 'msg' => 'Element "password" format error!'],
];

$return = (new Paramverify)->verify($param, $verify);
echo $return['status'] ? 'Yes' : $return['msg'];  //[password] error code [2]
```

## error code [3]
```php
$param = [
  'username' => 'kkzhuang._-+',
  'password' => 'kkzhuang._-+',
];

$verify = [
  'username' =>['re' => '/^([a-z0-9]{6,64})$/'],
  'password' =>['re' => '/^([a-z0-9\.\-_+]{6,64})$/', 'msg' => 'Element "password" format error!'],
];

$return = (new Paramverify)->verify($param, $verify);
echo $return['status'] ? 'Yes' : $return['msg'];  //[username] error code [3]
```
In verify array,if you define 'msg' for verify element,the msg will replace error code [3].
```php
$param = [
  'username' => 'kkzhuang._-+',
  'password' => 'kkzhuang._-+',
];

$verify = [
  'username' =>['re' => '/^([a-z0-9]{6,64})$/', 'msg' => 'Element "username" format error!'],
  'password' =>['re' => '/^([a-z0-9\.\-_+]{6,64})$/', 'msg' => 'Element "password" format error!'],
];

$return = (new Paramverify)->verify($param, $verify);
echo $return['status'] ? 'Yes' : $return['msg'];  //Element "username" format error!
```
