<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
App::uses('CakeTime', 'Utility');
class AppController extends Controller {
    public $components = array(
        'DebugKit.Toolbar' => array(/* array of settings */),
        'Session',
        'Auth' => array(
            'loginRedirect' => array('controller' => 'Forums', 'action' => 'articles', 1),
            'logoutRedirect' => array('controller' => 'Forums', 'action' => 'articles', 1),
            'loginAction' => array('controller' => 'Users', 'action' => 'login'),
            'logoutAction' => array('controller' => 'Users', 'action' => 'logout'),
            'authorize' => array('Controller'),
            'flash' => array('element' => 'alert','key' => 'auth','params' => array('plugin' => 'BoostCake','class' => 'alert-danger')),
            'authenticate' => array(
                'Authenticate.Cookie' => array(
                    'fields' => array(
                        'username' => 'username',
                        'password' => 'password'
                    ),
                    'userModel' => 'User'
                ),
                'Authenticate.MultiColumn' => array(
                    'fields' => array(
                        'username' => 'username',
                        'password' => 'password'
                    ),
                    'columns' => array('username', 'email'),
                    'userModel' => 'User'
                )
            )
        ),
        'Email',
        'Cookie',
        'Security',
        'Purifier',
        'Parsedown',
        'Paginator'
    );
    public $helpers = array(
        //'Bbcode', not needed, i use ckeditor as front-end and makes bb code obsolete
        'Html' => array('className' => 'BoostCake.BoostCakeHtml'),
        'Form' => array('className' => 'BoostCake.BoostCakeForm'),
        'Paginator' => array('className' => 'BoostCake.BoostCakePaginator'),
        'Session',
        'MinifyHtml.MinifyHtml',
        'Parsedown',
        //'Geshi.Geshi'
    );

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Cookie->key = '`*\\!vhv)&]b5=;b.@iXnl\'Z$v&GXtF0#8q^pl#E]-t_M3U99F`_j3>8tbPor^t!';
        $this->Cookie->type('rijndael');

        if ( !$this->request->is('put') && !$this->request->is('post') ) {
          // dont track puts and posts
          $this->_pushURLBackLog($this->request->params);
        }
    }

    protected function _sessionMessageTemplate($msg, $type) {
      $this->Session->setFlash($msg, 'alert', array('plugin' => 'BoostCake', 'class' => 'alert-' . $type));
    }

    protected function sessionMessageSuccess($msg) {
      $this->_sessionMessageTemplate($msg, 'success');
    }

    protected function sessionMessageOk($msg) { $this->sessionMessageSuccess($msg); }

    protected function sessionMessageInfo($msg) {
      $this->_sessionMessageTemplate($msg, 'info');
    }

    protected function sessionMessageWarning($msg) {
      $this->_sessionMessageTemplate($msg, 'warning');
    }

    protected function sessionMessageError($msg) {
      $this->_sessionMessageTemplate($msg, 'danger');
    }

    /**
     * Checks if a logged in user is able to access
     * the requested action of any controller.
     * If the method _check_<action>() is present, then
     * it is called to check access rights of the user
     * to access the requested action.
     * If it is not present then it is assumed
     * that anyone can access the requested action.
     * @param array $user
     * @return boolean
     */
    public function isAuthorized($user) {
        $call_name = ('_check_' . $this->action);
        if ( method_exists( $this, $call_name ) ) {
            return $this->$call_name();
        } else {
            return true;
        }
    }

    /**
     * Wraps $this->set($name, $this->$class->find('all')).
     * @param class $class
     * @param string $name
     * @param array $params
     */
    protected function pullAllAs($class, $name = 'data', $params = array()) {
    	$this->loadModel($class);
    	$this->set($name, $this->$class->find('all', $params));
    }

    protected function splitPermission($perm) {
        $result = array();
        foreach ( $perm as $p => $v ) {
            $res = explode('/', $p);
            while ( !empty($res) ) {
                $result[implode('/', $res)] = true;
                array_pop($res);
            }
        }
        return $result;
    }

    protected function getUserPermissions() {
        $this->loadModel('User');
        return $this->splitPermission($this->User->getPermissions($this->Auth->user('id')));
    }

    public function beforeRender() {
        $this->loadModel('Section');
        $this->loadModel('User');

        $this->set('sections', $this->Section->find('all'));
        $this->set('user', $this->User->find('first', array('conditions' => array('User.id' => $this->Auth->user('id')), 'recursive' => 2)));
        $this->set('permissions', $this->getUserPermissions());
        $this->set('DEFAULT_FORM_OPTIONS', array('inputDefaults' => array('div' => 'form-group', 'wrapInput' => false, 'class' => 'form-control'),'class' => 'well well-lg'));
        $this->set('back_url', $this->_getPrevURLFromBackLog());
    }

    protected function _checkAuthByForumId($forum_id,$permission_) {
        if ( 0 == $forum_id ) {
            return true;
        }

        $perms = $this->_getUserForumPermission($forum_id, $this->Auth->user('id'));
        return isset($perms[$permission_]);
    }

    protected function _checkAuthThread($thread_, $permission_) {
      $this->loadModel('ForumThread');

      if ( 0 == $thread_ ) {
        return true;
      }

      $thread = $this->ForumThread->findById($thread_);
      if ( empty($thread) ) {
        return false;
      }

      return $this->_checkAuthByForumId($thread['ForumThread']['forum_id'],$permission_);
    }

    protected function _checkAuthThreadId($permission_) {
        $thread_id = 0;
        if ( isset($this->params->pass) && count($this->params->pass) > 0 ) {
            $thread_id = $this->params->pass[0];
        }
        return $this->_checkAuthThread($thread_id, $permission_);
    }

    protected function _checkAuthForumId($permission_) {
        $forum_id = 0;
        if ( isset($this->params->pass) && count($this->params->pass) > 0 ) {
            $forum_id = $this->params->pass[0];
        }
        return $this->_checkAuthByForumId($forum_id,$permission_);
    }

    protected function _getUserForumPermission( $forum_id, $user_id ) {
        $this->loadModel('User');
        return $this->User->getForumPermission($user_id,$forum_id);
    }

    protected function isProjectAdmin($id = null) {
      return $this->hasProjectPermission(PERMISSION_PROJECT_EDIT_PROJECT, $id);
    }

    protected function hasProjectPermission($permission_, $id_ = null) {
      $this->loadModel('User');
      if ( !is_null($id_) ) {
        if ( $this->User->checkProjectPermission($this->Auth->user('id'), $id_, $permission_) ) {
          return true;
        }
      }
      return $this->User->checkPermission($this->Auth->user('id'), PERMISION_ADMIN_PROJECT);
    }

    protected function getParam($index_, $default_ = null) {
      if ( isset($this->params->pass) && count($this->params->pass) > $index_ ) {
        return $this->params->pass[$index_];
      }
      return $default_;
    }

    protected function _getNamedParam($name_, $default_ = null) {
      if ( $this->_hasNamedParam($name_) ) {
        return $this->request['named'][$name_];
      }
      return $default_;
    }

    protected function _hasNamedParam($name_) {
      return isset($this->request['named'][$name_]);
    }

    protected function _getPermissionForAllProjects() {
      $this->loadModel('User');
      $this->loadModel('ProjectUserGroupPermission');
      $this->loadModel('Project');
      $result = array();
      if ( $this->User->checkPermission($this->Auth->user('id'), PERMISION_ADMIN_PROJECT) ) {
      $plist = $this->Project->find('list');
        foreach ( $plist as $p => $n ) {
          $result[$p][PERMISSION_PROJECT_FILE_REPORT] = true;
          $result[$p][PERMISSION_PROJECT_EDIT_REPORT] = true;
          $result[$p][PERMISSION_PROJECT_COMMENT_REPORT] = true;
          $result[$p][PERMISSION_PROJECT_EDIT_PROJECT] = true;
        }
      } else {
        $groups = $this->User->getGroupList($this->Auth->user('id'));
        $perm = $this->ProjectUserGroupPermission->find( 'all'
                                                 , array('conditions' => array('ProjectUserGroupPermission.user_group_id' => $groups )
                                                 , 'recursive' => -1));
        foreach ( $perm as $p ) {
          $result[$p['ProjectUserGroupPermission']['project_id']][$p['ProjectUserGroupPermission']['permission']] = true;
        }
      }
      return $result;
    }

    protected function _getURLBackLog() {
      return $this->Session->read('backlog.list');
    }

    protected function _getCurrentURLFromBackLog() {
      $log = $this->Session->read('backlog.list');
      $len = count($log);
      if ( $len > 0 ){
        return $log[$len - 1];
      } else {
        return array();
      }
    }

    protected function _getPrevURLFromBackLog() {
      $log = $this->Session->read('backlog.list');
      $len = count($log);
      if ( $len > 1 ){
        return $log[$len - 2];
      } else {
        return array();
      }
    }

    protected function _pushURLBackLog($entry_) {
      if ( isset($entry_['named']['back_log_back'])) {
        $this->_popURLBackLog();
        return;
      }
      if ( $this->_backLogNeedsToIgnore() ) {
        return;
      }
      $log = $this->_getURLBackLog();
      $n = $entry_['named'];
      $p = $entry_['pass'];
      $entry_['named'] = array();
      $entry_['pass'] = array();
      $entry_ = array_merge($entry_, $n, $p);
      $entry_['back_log_back'] = true;
      $log[] = $entry_;
      $this->Session->write('backlog.list', $log);
    }

    protected function _popURLBackLog($count_ = 1) {
      $log = $this->_getURLBackLog();
      for ( $i = 0; $i < $count_; ++$i ) {
        array_pop($log);
      }
      $this->Session->write('backlog.list', $log);
    }

    protected function _ignoreBackLogPushes($count_ = 1) {
      $count = $this->Session->read('backlog.ignore_count');
      if ( !$count ) {
        $count = $count_;
      } else {
        $count += $count_;
      }
      $this->Session->write('backlog.ignore_count', $count);
    }

    protected function _backLogNeedsToIgnore() {
      $count = $this->Session->read('backlog.ignore_count');
      if ( $count ) {
        --$count;
        $this->Session->write('backlog.ignore_count', $count);
        return true;
      } else {
        return false;
      }
    }
}
