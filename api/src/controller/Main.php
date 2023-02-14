<?php

namespace lhapi\controller;

class Main{

function test($test=''){
  return ['code' => 20,'msg' => 'test','data' => $test];
}

}