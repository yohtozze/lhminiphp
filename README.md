# LH Mini PHP
LH Mini PHP API Framework
极简单文件PHP API框架

## Define an API route
```php
  require __DIR__.'/Core.php';
  $routes=[
    '/user/login'=>['data'=>['username','password'],],
  ];
  \lhmini\Core::start($routes);
```

## Feature
Load the file and call the class and method according to the route，`'/main/test'` mean
```php
  [$class,$func]=['Main','test'];
  $controller=new $class;
  $controller->$func();
```

## Test
Run start_web_server.bat，Browser open url——http://127.0.0.1:16808

## License
MIT