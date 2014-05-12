<?php

class Tag
extends AppModel {
  public $hasAndBelongsToMany = array( 'ForumPost' => array( 'joinTable' => 'forum_posts_tags', 'foreignKey' => 'tag_id', 'associationForeignKey' => 'forum_post_id', 'unique' => true) );
};