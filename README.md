# LH Mini PHP
LH Mini PHP API Framework
极简单文件PHP API框架

## 简单使用
```php
  require __DIR__.'/Core.php';
  $routes=[
    '/user/login'=>['data'=>['username','password'],],
  ];
  \lhmini\Core::start($routes);
```

## 特点
根据路由调用类和方法，`'/main/test'`代表着
```php
  [$class,$func]=['Main','test'];
  $controller=new $class;
  $controller->$func();
```

## 测试
运行start_web_server.bat，浏览器打开网址——http://127.0.0.1:16808

## License
MIT