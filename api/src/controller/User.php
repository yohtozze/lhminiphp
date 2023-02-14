<?php

namespace lhapi\controller;

class User{

function login(){
  return ['code'=>400,'msg'=>'用户名或密码错误'];
}

function dataGet(){
  return ['code'=>200,'data'=>'lh'];
}

}