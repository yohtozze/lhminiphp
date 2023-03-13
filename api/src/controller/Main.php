<?php

namespace lhapi\controller;

class Main{

function test($test=''){
  return ['code' => 200,'msg' => 'test','data' => $test];
}

}