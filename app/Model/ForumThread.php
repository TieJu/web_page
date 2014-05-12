<?php

class ForumThread
extends AppModel {
    public $order = array('ForumThread.sticky' => 'DESC', 'ForumThread.modified' => 'DESC');
    public $belongsTo = array('Author' => array('className' => 'User'), 'Forum');
    public $hasMany = array('ForumPost' => array('foreignKey' => 'thread_id', 'order' => array('ForumPost.created')));
    public $validate = array('name' => array('required' => true, 'rule' => 'notEmpty', 'message' => 'A thread needs a name'));
};