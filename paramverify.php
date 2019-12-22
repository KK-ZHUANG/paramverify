<?php
/**
 * Paramverify
 *
 * Parameter verify tool
 *
 * version 1.2.0
 *
 * Author : KK (kkandmore@163.com)
 *
 */
class Paramverify
{
  /**
   * The array of configuration
   */
  private static $config = [

    'required' => true,

    'type' => 'string',

    're' => null,

    'msg' => null,

    'map' => [],

  ];

  /**
   * The function to set configuration
   *
   * @param $config
   */
  public static function config(array $config)
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

    $return = self::verify($config, $verify);
    
    $return['status'] ? '' : exit($return['msg']);

    foreach (self::$config as $k => $v)
    {
      self::$config[$k] = $config[$k] ?? self::$config[$k];
    }
  }

  /**
   * The function to verify parameter
   * 
   * @param $param
   * @param $verify
   * return Array
   */
  public static function verify(array $param, array $verify)
  {
    foreach($verify as $k => $v)
    {
      foreach (self::$config as $config_index => $config_val)
      {
        $exec_config[$config_index] = $v[$config_index] ?? self::$config[$config_index];
      }

      $index = explode('.', $k);
      $param_ele = &$param;

      foreach($index as $index_v)
      {
        if($exec_config['required'] && !isset($param_ele[$index_v]))
        {
          return [
            'status' => false,
            'msg' => 'Error Code [1],['.$k.'] is required.'
          ];
        }
        else
        {
          if(isset($param_ele[$index_v]))
          {
            $param_ele = &$param_ele[$index_v];
          }
          else
          {
            unset($param_ele);
            $param_ele = null;
          }
        }
      }

      if($param_ele !== null)
      {
        if($exec_config['map'])
        {
          foreach($exec_config['map'] as $fun_name)
          {
            $param_ele = $fun_name($param_ele);
          }
        }

        $return  = self::type($k, $exec_config, $param_ele) ?? self::re($k, $exec_config, $param_ele);
        if($return)
        {
          return $return;
        }
      }
    }
    return [
      'status' => true,
      'msg' => $param,
    ];
  }

  private static function type($index, $rule, $val)
  {
    $fun_call_name = 'is_'.$rule['type'];
    if(!$fun_call_name($val))
    {
      return [
        'status' => false,
        'msg' => 'Error Code [2],['.$index.'] must be of the type '.$rule['type'].','.gettype($val).' given.',
      ];
    }
  }

  private static function re($index, $rule, $val)
  {
    if($rule['type'] === 'bool')
    {
      if(($rule['re'] === 'true' && $val !== true) || ($rule['re'] === 'false' && $val !== false))
      {
        return [
          'status' => false,
          'msg' => $rule['msg'] ?? 'Error Code [3],['.$index.'] re mismatch.',
        ];
      }
    }
    else
    {
      if(!preg_match($rule['re'], $val))
      {
        return [
          'status' => false,
          'msg' => $rule['msg'] ?? 'Error Code [3],['.$index.'] re mismatch.',
        ];
      }
    }
  }
}
