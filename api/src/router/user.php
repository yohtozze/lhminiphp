<?php

$routes_user=[
  //用户登录
  '/user/login'=>['data'=>['username','password']],
  //获取用户数据
  '/user/data/get'=>['middle'=>['Main.userToken']],
];

$routes=array_merge($routes,$routes_user);