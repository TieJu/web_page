<?php
class ReportPriority
extends AppModel {
	public $useTable = 'priorities';
	public $hasMany = array('Report' => array('foreignKey' => 'priority_id'));
	public $validate = array('name' => array('required' => true, 'rule' => 'isUnique', 'message' => 'This priority name is in use'));
};