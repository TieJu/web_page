<?php

App::uses('AppHelper', 'View/Helper');
App::import('Vendor','Parsedown' ,array('file'=>'parsedown' . DS . 'Parsedown.php'));

class ParsedownHelper extends AppHelper {
  function __construct(){
    $this->parsedown = new Parsedown();
  }

  function text($code_) {
    return $this->parsedown->text($code_);
  }
};