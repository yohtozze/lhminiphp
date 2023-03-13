<?php

namespace lhapi\middleware;

class Main{
  function userToken(){
    if(!isset($_COOKIE['token']))exit(json_encode(['code'=>400,'msg'=>'Please login']));
  }
}