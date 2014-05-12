<?php

App::uses('Component', 'Controller/Component');
App::import('Vendor','HtmlPurifier' ,array('file'=>'htmlpurifier' . DS . 'library' . DS . 'HTMLPurifier.auto.php'));

class PurifierComponent extends Component {
  public function startup(Controller $controller) {
    $config = HTMLPurifier_Config::createDefault();
    $config->set('Filter.YouTube', true);
    $controller->Purifier = new HTMLPurifier();

    $controller->set('Purifier',$controller->Purifier);
  }
};