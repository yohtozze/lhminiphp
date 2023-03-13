<?php

namespace lhapi\controller;

class User{

function login(){
  return ['code'=>400,'msg'=>'Incorrect username or password'];
}

function dataGet(){
  return ['code'=>200,'data'=>'lh'];
}

}