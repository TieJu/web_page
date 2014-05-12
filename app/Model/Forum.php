<?php

class Forum
extends AppModel {
  public $belongsTo = array('Parent' => array('className' => 'Forum', 'foreignKey' => 'parent_id'));
    public $hasMany = array('ForumUserGroupPermission'
                           ,'ForumThread' => array('order' => array('ForumThread.sticky' => 'DESC', 'ForumThread.modified' => 'DESC'))
                           ,'SubForum' => array('foreignKey' => 'parent_id', 'className' => 'Forum'));
	public $validate = array('name' => array('required' => true, 'rule' => 'notEmpty', 'message' => 'A forum needs a name'));
};