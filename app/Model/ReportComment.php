<?php
class ReportComment
extends AppModel {
  public $belongsTo = array('Author' => array('className' => 'User'));
	public $validate = array('comment' => array('required' => true, 'rule' => 'notEmpty', 'message' => 'A comment can not be empty'));
};