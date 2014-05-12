<?php
class ReportStatus
extends AppModel {
	public $useTable = 'statuses';
	public $hasMany = array('Report' => array('foreignKey' => 'status_id'));
	public $validate = array('name' => array('required' => true, 'rule' => 'isUnique', 'message' => 'This status name is in use'));
};