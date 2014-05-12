<?php

App::uses('UserGroupUser', 'Model');
App::uses('UserGroupPermission', 'Model');
App::uses('ProjectUserGroupPermission', 'Model');
App::uses('ForumUserGroupPermission', 'Model');
class User
extends AppModel {
    public $validate = array(
        'username' => array('required' => true, 'rule' => 'isUnique', 'message' => 'This username has already been taken'),
        'password' => array('rule' => array('minLength', '8'), 'message' => 'A password must be at least 8 characters long'),
        'email' => 'email'
    );

    public $hasMany = array('UserPermission');

    public $hasAndBelongsToMany = array(
        'UserGroup' => array('joinTable' => 'user_groups_users' , 'unique' => true, 'foreignKey' => 'user_group_id', 'associationForeignKey' => 'user_id' )
    );

    public function beforeSave($option = array()) {
      // we need to hash the password before we save it
        if ( isset($this->data[$this->alias]['password']) ) {
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        }
    }

    /**
     * Retrieves a list of all groups where the requested user is a member of.
     * @param integer $id_ ID of the requested user.
     */
    public function getGroupList($id_) {
      $UserGroupUser = new UserGroupUser();
      return array_keys($UserGroupUser->find('list', array('conditions' => array('UserGroupUser.user_id' => $id_ ), 'fields' => array('user_group_id', 'id'))));
    }

    public function getPermissions($id_) {
      $UserGroupPermission = new UserGroupPermission();
      $groups = $this->getGroupList($id_);
      $perm = array();
      $ugps = $UserGroupPermission->find('list', array('conditions' => array('UserGroupPermission.user_group_id' => $groups ), 'recursive' => -1, 'fields' => 'permission'));
      foreach ( $ugps as $ugp ) {
        $perm[$ugp] = true;
      }

      $self = $this->findById($id_);
      if ( !empty($self) ) {
        foreach ( $self['UserPermission'] as $upm ) {
          $perm[$upm['permission']] = true;
        }
      }
      return $perm;
    }

    public function checkPermission($id_, $permission_ ) {
      $set = $this->getPermissions($id_);
      return isset($set[$permission_]);
    }

    public function getProjectPermission($id_, $pid_) {
      $ProjectUserGroupPermission = new ProjectUserGroupPermission();
      $groups = $this->getGroupList($id_);
      $perm = $ProjectUserGroupPermission->find( 'list'
                                               , array('conditions' => array('ProjectUserGroupPermission.user_group_id' => $groups, 'ProjectUserGroupPermission.project_id' => $pid_ )
                                               , 'recursive' => -1
                                               , 'fields' => array('permission')));
      return array_flip($perm);
    }

    public function checkProjectPermission($id_, $pid_, $permission_) {
      $set = $this->getProjectPermission($id_, $pid_);
      return isset($set[$permission_]);
    }

    public function getForumPermission($id_, $fid_) {
      $ForumUserGroupPermission = new ForumUserGroupPermission();
      $groups = $this->getGroupList($id_);
      $perm = $ForumUserGroupPermission->find( 'list'
                                             , array('conditions' => array('ForumUserGroupPermission.user_group_id' => $groups, 'ForumUserGroupPermission.forum_id' => $fid_ )
                                             , 'recursive' => -1
                                             , 'fields' => array('permission')));
      return array_flip($perm);
    }

    public function checkForumPermission($id_, $fid_, $permission_) {
      $set = $this->getForumPermission($id_, $fid_);
      return isset($set[$permission_]);
    }
};