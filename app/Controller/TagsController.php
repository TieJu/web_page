<?php

class TagsController
extends AppController {
  public function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('articles', 'index');
  }

  public function _tag_sort($l, $r) {
    $cl = count($l['ForumPost']);
    $cr = count($r['ForumPost']);
    return $cl == $cr ? 0 : ( ( $cl > $cr ) ? -1 : 1 );
  }

  public function index() {
    $set = $this->Tag->find('all', array('recursive' => 2));
    foreach ($set as $tag ) {
      foreach ( $tag['ForumPost'] as $post ) {
        if ( $post['Thread']['article'] ) {
          $filtered_tags[] = $tag;
          break;
        }
      }
    }
    uasort($filtered_tags, array($this, '_tag_sort'));
    $this->set('data', $filtered_tags);
  }

  public function threads($id = null) {
    $this->set('data', $this->Tag->findById($id));
  }

  public function articles($id = null) {
    $set = $this->Tag->find('first', array('conditions' => array('Tag.id' => $id), 'recursive' => 2));
    foreach ($set['ForumPost'] as $post ) {
      if ( $post['Thread']['article'] ) {
        $filtered_posts[] = $post;
      }
    }
    $set['ForumPost'] = $filtered_posts;
    $this->set('data', $set);
  }
};