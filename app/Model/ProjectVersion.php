<?php

class ProjectVersion
extends AppModel {
	public $hasMany = array( 'Report' => array( 'foreignKey' => 'target_version_id') );
    public $belongsTo = array( 'Project','ProjectVersionStatus' => array( 'foreignKey' => 'status_id' ) );
	public $validate = array('name' => array('required' => true, 'rule' => 'notEmpty', 'message' => 'A version needs a name'));
};