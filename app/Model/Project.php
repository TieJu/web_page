<?php

class Project
extends AppModel {
    public $hasMany = array('ProjectVersion', 'ProjectUserGroupPermission' => array('foreignKey' => 'project_id'));
	public $validate = array('name' => array('required' => true, 'rule' => 'notEmpty', 'message' => 'A project needs a name'));
};