<?php

require __DIR__.'/Core.php';

//Determine routing parameters
if(!isset($_GET['r']))exit('parameter error');

//Load route
$routes=[];
$routeFile=explode('/',$_GET['r'])[1]??'wrongRoute';
if(!file_exists(__DIR__.'/src/router/'.$routeFile.'.php'))exit('parameter error');
require __DIR__.'/src/router/'.$routeFile.'.php';

//If there is no matching route
if(!isset($routes[$_GET['r']]))exit('No matching route');

//Modify the namespace of controller and middleware
\lhmini\Core::init('lhapi\\controller','lhapi\\middleware');

//Autoload
spl_autoload_register(function($class_name){
  $namespace='lhapi';
  if(strpos($class_name,$namespace)===0){
    $class_name=substr($class_name, strlen($namespace));
    $class_name=str_replace('\\','/',$class_name);
    require_once __DIR__.'/src'.$class_name.'.php';
  }
});

//Define special events
//Missing required parameters
\lhmini\Core::setEvent('missParam',function($missParam){
  exit('Missing parameter:'.$missParam);
});
//Global middleware
\lhmini\Core::setEvent('middle',function(){

});
//Global end method
\lhmini\Core::setEvent('end',function($result){
  //Handle special return results in non-json format
});

//Global function
//Get request parameters
function getData(){
  return \lhmini\Core::getData();
}

//Start processing API request
\lhmini\Core::start($routes);