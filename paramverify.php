<?php
/**
 * Paramverify
 *
 * Parameter verify tool
 *
 * version 1.1.1
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
  ];

  /**
   * The function to set configuration
   *
   * @param $config
   */
  public static function config(array $config)
  {
    $verify = [
      'required' => ['required' => false, 'type' => 'bool'],
      'type' => ['required' => false, 'type' => 'string', 're' => '/^(int|float|string|numeric|bool)$/', 'msg' => 'The value of configuration parameter [type] must be int|float|string|numeric|bool'],
    ];

    $return = self::verify($config, $verify);
    
    if(!$return['status'])
    {
      exit($return['msg']);
    }
    else
    {
      foreach (self::$config as $k => $v)
      {
        if(isset($config[$k]))
        {
          self::$config[$k] = $config[$k];
        }
      }
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
      $index = explode('.', $k);
      $param_ele = $param;
      
      foreach($index as $index_v)
      {
        $required = isset($v['required']) ? $v['required'] : self::$config['required'];

        if($required && !isset($param_ele[$index_v]))
        {
          $return  = ['status' => false, 'msg' => '['.$k.'] error code [1]'];
          return $return;
        }
        else
        {
          @$param_ele = $param_ele[$index_v];
        }
      }
      
      if(isset($param_ele))
      {
        @$fun_call_name = 'is_'.($v['type'] ? $v['type'] : self::$config['type']);
        if(!$fun_call_name($param_ele))
        {
          $return  = ['status' => false, 'msg' => '['.$k.'] error code [2]'];
          return $return;
        }
        
        if(isset($v['re']))
        {
          if((isset($v['type']) ? $v['type'] : self::$config['type']) === 'bool')
          {
            if(($v['re'] === 'true' && $param_ele !== true)||($v['re'] === 'false' && $param_ele !== false))
            {
              $return = ['status' => false,'msg' => isset($v['msg'])? $v['msg'] : '['.$k.'] error code [3]'];
              return $return;
            }
          }
          else
          {
            if(!preg_match($v['re'], $param_ele))
            {
              $return = ['status' => false,'msg' => isset($v['msg'])? $v['msg'] : '['.$k.'] error code [3]'];
              return $return;
            }
          }
        }
      }
    }
    return ['status' => true, 'msg' => ''];
  }
}
