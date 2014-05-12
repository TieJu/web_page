<?php

App::uses('Component', 'Controller/Component');
App::import('Vendor','Parsedown' ,array('file'=>'parsedown' . DS . 'Parsedown.php'));

class ParsedownComponent extends Component {
  public function startup(Controller $controller) {
    $controller->Parsedown = new Parsedown();
    $controller->set('Parsedown',$controller->Parsedown);
  }
};