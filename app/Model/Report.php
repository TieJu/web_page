<?php

class Report
extends AppModel {
    public $belongsTo = array
    ('Author' => array('className' => 'User')
    ,'Version' => array('className' => 'ProjectVersion', 'foreignKey' => 'project_version_id' )
    ,'Status'
    ,'Priority'
    ,'AssignedTo' => array('className' => 'User', 'foreignKey' => 'assigned_to_id' )
    ,'TargetVersion' => array('className' => 'ProjectVersion', 'foreignKey' => 'target_version_id' )
    );
    public $hasMany = array('ReportComment' => array('order' => 'ReportComment.created'));
	public $validate = array(
	    'name' => array('required' => true, 'rule' => 'notEmpty', 'message' => 'A report name can not be empty'),
	    'description' => array('required' => true, 'rule' => 'notEmpty', 'message' => 'A report description can not be empty'),
	);
};