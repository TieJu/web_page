<?php

class ForumsController
extends AppController {
    public function beforeFilter() {
      parent::beforeFilter();
      $this->Auth->allow('articles');
    }

    public function _check_index() {
      return $this->_checkAuthForumId('read');
     }

    public function index($id = 0) {
      $perms = $this->_getUserForumPermission( $id, $this->Auth->user( 'id' ) );
      $forum = $this->Forum->find('first', array('conditions' => array('Forum.id' => $id), 'recursive' => 3));

      if ( empty($forum) ) {
        // empty forum is ok, its the root for the forum system
        $forum['Parent'] = array('id' => null, 'name' => null);
        $forum['Forum'] = array('id' => null, 'name' => __('Forums'));
        $forum['ForumUserGroupPermission'] = array();
        $forum['ForumThread'] = array();
        $forum['SubForum'] = $this->Forum->find('all', array('conditions' => array('Forum.parent_id' => 0)));
        foreach ( $forum['SubForum'] as &$sub ) {
          $sub = array_merge($sub, $sub['Forum']);
          unset($sub['Forum']);
          unset($sub);
        }
      }

      $this->set('forum', $forum);
      $this->set('forumPermissions', $perms);
    }

    public function articles($id = null) {
      $this->loadModel('ForumThread');
      $this->Paginator->settings = array('limit' => 5, 'conditions' => array('ForumThread.forum_id' => $id, 'ForumThread.article' => true));
      $this->set('forum', $this->Paginator->paginate('ForumThread'));
    }

    protected function _check_admin_list() {
      $this->loadModel('User');
      return $this->User->checkPermission($this->Auth->user('id'),PERMISION_ADMIN_FORUM);
    }

    public function admin_list() {
      $this->pullAllAs('Forum', 'data');
    }

    protected function _check_edit() {
    	return $this->_check_admin_list();
    }

    public function edit($id = null) {
      $this->loadModel('UserGroup');
      $this->loadModel('ForumUserGroupPermission');
      if ( $this->request->is('post') || $this->request->is('put') ) {
        if ( is_null($id) ) {
          $this->Forum->create();
        } else {
          $this->request->data['Forum']['id'] = $id;
        }
        $this->request->data['Forum']['name'] = strip_tags($this->request->data['Forum']['name']);
        if ( !$this->Forum->save($this->request->data) ) {
          $this->sessionMessageError(__('Unable to save forum state'));
        } else {
          $this->sessionMessageOk(__('Forum saved'));
        }
        if ( is_null($id) ) {
          $id = $this->Forum->id;
        }

        foreach ( $this->request->data['ForumUserGroupPermission'] as $gid => $gset ) {
          foreach ( $gset as $gn => $gs ) {
            if ( $gs ) {
              if ( !$this->ForumUserGroupPermission->find('count', array('conditions' => array('ForumUserGroupPermission.permission' => $gn, 'ForumUserGroupPermission.user_group_id' => $gid, 'ForumUserGroupPermission.forum_id' => $id))) ) {
                $this->ForumUserGroupPermission->create();
                $this->ForumUserGroupPermission->save(array('ForumUserGroupPermission' => array('permission' => $gn, 'user_group_id' => $gid, 'forum_id' => $id)));
              }
            } else {
              $this->ForumUserGroupPermission->deleteAll(array('ForumUserGroupPermission.permission' => $gn, 'ForumUserGroupPermission.user_group_id' => $gid, 'ForumUserGroupPermission.forum_id' => $id));
            }
          }
        }
        $this->redirect(array('action' => 'admin_list'));
      } else {
        if ( !is_null($id) ) {
          $this->request->data = $this->Forum->findById($id);
          $permissions = $this->ForumUserGroupPermission->find('all', array('conditions' => array('ForumUserGroupPermission.forum_id' => $id)));
          $filter = array('conditions' => array('Forum.id !=' => $id), 'fields' => array('Forum.id', 'Forum.name'));
        } else {
          $permissions = array();
          $filter = array('fields' => array('Forum.id', 'Forum.name'));
        }
        $forumSet = array(__('None')) +  $this->Forum->find('list',$filter);
        $groups = $this->UserGroup->find('all');

        $this->request->data['ForumUserGroupPermission'] = array();
        foreach ( $permissions as $permission ) {
          $ugid = $permission['ForumUserGroupPermission']['user_group_id'];
          $p = $permission['ForumUserGroupPermission']['permission'];
          $this->request->data['ForumUserGroupPermission'][$ugid][$p] = 1;
        }

        $this->set('forumSet', $forumSet);
        $this->set('groupSet', $groups);
       }
    }

    protected function _check_delete() {
      return $this->_check_admin_list();
    }

    public function delete($id = null) {
    	if ( !is_null($id)) {
        	$this->loadModel('ForumUserGroupPermission');

        	$this->ForumUserGroupPermission->deleteAll(array('ForumUserGroupPermission.forum_id' => $id));
        	if ( $this->Forum->delete($id) ) {
        	  $this->sessionMessageOk(__('Forum has been deleted'));
        	} else {
        	  $this->sessionMessageError(__('Unable to delete forum'));
        	}
    	}

    	$this->redirect(array('action' => 'admin_list'));
    }
};