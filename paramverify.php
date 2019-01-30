<?php
/**
 * Paramverify
 *
 * Array verify tool
 *
 * version 1.0.1
 *
 * Author : KK (kkandmore@163.com)
 *
 */
class Paramverify
{
  /**
   *
   * @param $param
   * @param $verify
   * return Array
   *
   */
  public static function verify(array $param, array $verify)
  {
    foreach($verify as $k => $v)
    {
      $index = explode('.', $k);
      $param_ele = $param;
      
      foreach($index as $index_v)
      {
        if($v['required'] && !isset($param_ele[$index_v]))
        {
          $return  = ['status' => false, 'msg' => '['.$k.'] error code [1]'];
          return $return;
        }
        else
        {
          $param_ele = $param_ele[$index_v];
        }
      }
      
      if(isset($param_ele))
      {
        $fun_call_name = 'is_'.$v['type'];
        if(!$fun_call_name($param_ele))
        {
          $return  = ['status' => false, 'msg' => '['.$k.'] error code [2]'];
          return $return;
        }
        
        if(!preg_match($v['re'], $param_ele))
        {
          if(isset($v['msg']))
            $return  = ['status' => false, 'msg' => $v['msg']];
          else
            $return  = ['status' => false,'msg' => '['.$k.'] error code [3]'];
          
          return $return;
        }
      }
    }
    return ['status' => true, 'msg' => ''];
  }
}
