<?php

namespace lhmini;

class Core{
  //Request parameters
  private static $data;

  //Namespace of controller and middleware
  private static $namespace=['controller'=>'','middle'=>''];
  
  /*Special events
   *Missing required parameter: missParam
   *Global middleware: middle
   *Global end piece: end
   */
  private static $events;

  //Modify the namespace of controller and middleware
  public static function init($controller,$middle){
    self::$namespace=['controller'=>$controller,'middle'=>$middle];
  }

  //Set special events
  public static function setEvent($name, $callback) {
    self::$events[$name] = $callback;
  }

  //Get request parameters
  public static function getData(){
    return self::$data;
  }

  //Start processing request
  public static function start($routes){
    //Load request parameters
    if($_SERVER['REQUEST_METHOD']=='GET'){
      self::$data=$_GET;
      unset(self::$data['r']);
    }else{
      if(isset($_SERVER['CONTENT_TYPE'])&&$_SERVER['CONTENT_TYPE']=='application/json'){
        self::$data=json_decode(file_get_contents("php://input"), true);
      }else{
        self::$data=$_POST;
      }
    }

    //Get route parameters
    $route=$routes[$_GET['r']];

    //Define the parameter array to be passed to the controller method
    $paramsForController=[];

    //Process necessary parameters
    if(isset($route['data'])){
      foreach ($route['data'] as $v){
        if(!isset(self::$data[$v])){//If necessary parameters are missing
          if(isset(self::$events['missParam']))self::$events['missParam']($v);
          exit();
        }
        $paramsForController[]=self::$data[$v];
      }
    }

    //Process optional parameters
    if(isset($route['odata'])){
      foreach ($route['odata'] as $v){
        $paramsForController[]=self::$data[$v]??null;
      }
    }

    //Call global middleware
    if(isset(self::$events['middle']))self::$events['middle']();

    //Call middleware according to routing parameters
    if(isset($route['middle'])){
      for ($i=0; $i < count($route['middle']); $i++){
        $split=explode('.', $route['middle'][$i]);
        [$class,$func]=[self::$namespace['middle'].'\\'.$split[0],$split[1]];
        $control=new $class;
        $control->$func();
      }
    }

    //Get the class name and method name according to the route
    $split=explode('/',$_GET['r']);
    $class=self::$namespace['controller'].'\\'.ucwords(array_splice($split,0,2)[1]);
    $func=array_splice($split,0,1)[0];
    for($i=0;$i<count($split);$i++){
      $func.=ucwords($split[$i]);
    }

    //Call controller method
    $control=new $class;
    $result=$control->$func(...$paramsForController);

    //Call global end method
    if(isset(self::$events['end']))self::$events['end']($result);

    //Return json results
    exit(json_encode($result));
  }
}