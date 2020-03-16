<?php
/**
 * Paramverify
 *
 * Parameter verify tool
 *
 * version 2.0.0
 *
 * Author : KK (kkandmore@163.com)
 *
 */
namespace Paramverify;

class Paramverify
{
  /**
   * The array of configuration
   */
  protected $config = [
    'required' => true,
    'type' => 'string',
    're' => '',
    'msg' => null,
    'filters' => [],
    'is_defined' => true,
  ];

  /**
   * The function to set configuration
   *
   * @param $config
   */
  public function config(array $config)
  {
    $verify = [
      'required' => [
        'required' => false,
        'type' => 'bool'
      ],
      'type' => [
        'required' => false,
        'type' => 'string',
        're' => '/^(int|float|string|numeric|bool)$/',
        'msg' => 'The value of configuration parameter [type] must be int|float|string|numeric|bool'
      ],
      're' => [
        'required' => false,
        'type' => 'string'
      ],
      'msg' => [
        'required' => false,
        'type' => 'string'
      ],
    ];
    $return = $this->verify($config, $verify);
    $return['status'] ? '' : exit($return['msg']);
    foreach ($this->config as $k => $v) {
      $this->config[$k] = $config[$k] ?? $this->config[$k];
    }
    return $this;
  }

  /**
   * The function to verify parameter
   * 
   * @param $param
   * @param $verify
   * return Array
   */
  public function verify(array $param, array $verify)
  {
    foreach ($verify as $k => $v) {
      if($return = $this->required($k, $v['required'] ?? $this->config['required'], $param)) {
        return $return;
      }
      if(array_key_exists($k, $param)) {
        foreach ($v['filters'] ?? $this->config['filters'] as $func_name){
          $param[$k] = $func_name($param[$k]);
        }
        if($return = $this->type($k, $v['type'] ?? $this->config['type'], $param[$k])) {
          return $return;
        }
        if($return = $this->re($k, $v['type'] ?? $this->config['type'], $v['re'] ?? $this->config['re'], $v['msg'] ?? $this->config['msg'], $param[$k])) {
          return $return;
        }
      }
    }
    $param = $this->is_defined($param, $verify);

    return [
      'status' => true,
      'msg' => '',
      'data' => $param,
    ];
  }

  protected function required($key, $is_required, $param)
  {
    if($is_required && !isset($param[$key])) {
      return [
        'status' => false,
        'msg' => 'Error Code [1],['.$key.'] is required.'
      ];
    }
    return;
  }

  protected function type($key, $type, $val)
  {
    $func_name = 'is_'.$type;
    if(!$func_name($val)) {
      return [
        'status' => false,
        'msg' => 'Error Code [2],['.$key.'] must be of the type '.$type.','.gettype($val).' given.',
      ];
    }
    return;
  }

  protected function re($key, $type, $re, $msg, $val)
  {
    if($type === 'bool') {
      if(($re === 'true' && $val !== true) || ($re === 'false' && $val !== false)) {
        return [
          'status' => false,
          'msg' => $msg ?? 'Error Code [3],['.$key.'] re mismatch.',
        ];
      }
    } else {
      if(!preg_match($re, $val))
      {
        return [
          'status' => false,
          'msg' => $msg ?? 'Error Code [3],['.$key.'] re mismatch.',
        ];
      }
    }
  }

  protected function is_defined($param, $verify)
  {
    if($this->config['is_defined']) {
      foreach ($param as $k => $v) {
        if(!isset($verify[$k])) {
          unset($param[$k]);
        }
      }
    }
    return $param;
  }
}