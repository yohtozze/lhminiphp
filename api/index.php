<?php

require __DIR__.'/Core.php';

//加载路由
$routes=[];
$file=scandir(__DIR__.'/src/router/');
array_splice($file,0,2);
foreach($file as $v){require __DIR__.'/src/router/'.$v;}

//如果无匹配路由
if(!isset($_GET['r'])||!isset($routes[$_GET['r']])){
  exit('无匹配路由');
}

//修改控制器和中间件的命名空间
\lhmini\Core::init('lhapi\\controller','lhapi\\middleware');

//自动加载
spl_autoload_register(function($class_name){
  $namespace='lhapi';
  if(strpos($class_name,$namespace)==0){
    $class_name=substr($class_name, strlen($namespace));
    $class_name=str_replace('\\','/',$class_name);
    require_once __DIR__.'/src'.$class_name.'.php';
  }
});

//定义特殊事件
//缺少必要参数
\lhmini\Core::setEvent('missParam',function($missParam){
  exit('缺少参数:'.$missParam);
});
//全局中间件
\lhmini\Core::setEvent('middle',function(){

});
//全局结束件
\lhmini\Core::setEvent('end',function($result){
  //处理非json格式的特殊返回结果
});

//全局方法
//获取请求参数
function getData(){
  return \lhmini\Core::getData();
}

//开始处理API请求
\lhmini\Core::start($routes);