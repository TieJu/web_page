<?php

class UserGroup
extends AppModel {
    public $hasMany = array('UserGroupPermission' => array('foreignKey' => 'user_group_id'), 'ForumUserGroupPermission', 'ProjectUserGroupPermission');
    public $hasAndBelongsToMany = array(
        'User' => array('joinTable' => 'user_groups_users' , 'unique' => true, 'foreignKey' => 'user_id', 'associationForeignKey' => 'user_group_id' )
    );

    public $validate = array('name' => array('required' => true, 'rule' => 'notEmpty', 'message' => 'A usergroup needs a name'));
};