<?php

$routes=[
  '/user/login'=>['data'=>['username','password']],
  '/user/data/get'=>['middle'=>['Main.userToken']],
];