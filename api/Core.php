<?php

namespace lhmini;

class Core{
  //请求参数
  private static $data;

  //控制器和中间件的命名空间
  private static $namespace=['controller'=>'','middle'=>''];
  
  /*特殊事件
   *缺少必要参数:missParam
   *全局中间件:middle
   *全局结束件:end
   */
  private static $events;

  //修改控制器和中间件的命名空间
  public static function init($controller,$middle){
    self::$namespace=['controller'=>$controller,'middle'=>$middle];
  }

  //设置特殊事件
  public static function setEvent($name, $callback) {
    self::$events[$name] = $callback;
  }

  //获取请求参数
  public static function getData(){
    return self::$data;
  }

  //开始处理请求
  public static function start($routes){
    //获取json或者POST表单数据
    $tmp=json_decode(file_get_contents("php://input"), true);
    $data=($tmp && (is_object($tmp)) || (is_array($tmp) && !empty(current($tmp))))?$tmp:$_POST;

    //加载请求参数
    if(!$data)$data=$_GET;
    self::$data=$data;

    //获取路由参数
    $route=$routes[$_GET['r']];

    //定义要传递给控制器方法的参数数组
    $paramsForController=[];

    //处理必要参数
    if(isset($route['data'])){
      foreach ($route['data'] as $v){
        if(!isset($data[$v])){//如果缺少必要参数
          if(isset(self::$events['missParam']))self::$events['missParam']($v);
          exit();
        }
        array_push($paramsForController,self::$data[$v]);
      }
    }

    //处理可选参数
    if(isset($route['odata'])){
      foreach ($route['odata'] as $v){
        array_push($paramsForController,self::$data[$v]??null);
      }
    }

    //调用全局中间件
    if(isset(self::$events['middle']))self::$events['middle']();

    //根据路由参数调用中间件
    if(isset($route['middle'])){
      $split=explode('.', $route['middle']);
      [$class,$func]=[self::$namespace['middle'].'\\'.$split[0],$split[1]];
      $control=new $class;
      $control->$func();
    }

    //根据路由获取类名和方法名
    $split=explode('/',$_GET['r']);
    $class=self::$namespace['controller'].'\\'.ucwords(array_splice($split,0,2)[1]);
    $func=array_splice($split,0,1)[0];
    for($i=0;$i<count($split);$i++){
      $func.=ucwords($split[$i]);
    }

    //调用控制器方法
    $control=new $class;
    $result=$control->$func(...$paramsForController);

    //调用全局结束件
    if(isset(self::$events['end']))self::$events['end']($result);

    //返回json结果
    exit(json_encode($result));
  }
}