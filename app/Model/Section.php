<?php

class Section
extends AppModel {
  public $order = array('Section.ordering');
	public $belongsTo = array('Parent' => array('className' => 'Section', 'foreignKey' => 'parent_id') );
	public $hasMany = array('Child' => array('className' => 'Section', 'foreignKey' => 'parent_id', 'order' => 'Child.ordering' ));
	public $validate = array('name' => array('required' => true, 'rule' => 'isUnique', 'message' =>'This section name is in use'));
};