<?php

class ForumPost
extends AppModel {
    public $belongsTo = array
    ('Author' => array('className' => 'User')
    ,'Thread' => array('className' => 'ForumThread')
    );
    public $order = array('ForumPost.created' => 'ASC');
	public $validate = array(
	    'name' => array('required' => true, 'rule' => 'notEmpty', 'message' => 'A post needs a name'),
	    'post' => array('required' => true, 'rule' => 'notEmpty', 'message' => 'A post text can not be empty'),
	);
  public $hasAndBelongsToMany = array( 'Tag' => array( 'joinTable' => 'forum_posts_tags', 'foreignKey' => 'forum_post_id', 'associationForeignKey' => 'tag_id', 'unique' => true) );
};