<?php

class ProjectVersionStatus
extends AppModel {
	public $hasMany = array('ProjectVersion' => array('foreignKey' => 'status_id'));
	public $validate = array('name' => array('required' => true, 'rule' => 'isUnique', 'message' =>'This version status name is in use'));
};