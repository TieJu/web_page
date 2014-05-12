<?php

class UserGroupsController
extends AppController {
	protected function _check_index() {
    	$this->loadModel('User');
    	return $this->User->checkPermission($this->Auth->user('id'), PERMISION_ADMIN_GROUP);
	}

	public function index() {
		$this->pullAllAs('UserGroup', 'data');
	}

	protected function _check_edit() {
		return $this->_check_index();
	}

	public function edit($id = null) {
        $this->loadModel('UserGroupPermission');
        if ( $this->request->is('post') || $this->request->is('put') ) {
            $this->request->data['UserGroup']['name'] = strip_tags($this->request->data['UserGroup']['name']);
            if ( !empty($this->request->data['UserGroup']['UserGroupPermission']) ) {
                $perms_want = array_flip($this->request->data['UserGroup']['UserGroupPermission']);
            } else {
                $perms_want = array();
            }
            unset($this->request->data['UserGroup']['UserGroupPermission']);
            if ( is_null($id) ) {
                $this->UserGroup->create();
            } else {
                $this->request->data['UserGroup']['id'] = $id;
            }
            $this->UserGroup->save($this->request->data);
            if ( is_null($id) ) {
                $id = $this->UserGroup->id;
            }
            $perms_have = array();

            $perms_have_e = $this->UserGroup->findById($id);
            foreach ( $perms_have_e['UserGroupPermission'] as $pe ) {
                $perms_have[$pe['permission']] = true;
            }
            $grant = array();
            foreach ( array_diff_key($perms_want,$perms_have) as $gt => $u ) {
                $grant[] = $gt;
            }

            $revoke = array();
            foreach ( array_diff_key($perms_have,$perms_want) as $rv => $u ) {
                $revoke[] = $rv;
            }
            $this->UserGroupPermission->deleteAll(array('UserGroupPermission.permission' => $revoke));
            $ds = array('UserGroupPermission' => array());
            foreach ( $grant as $g ) {
                $ds['UserGroupPermission'] = array('user_group_id' => $id, 'permission' => $g );
                $this->UserGroupPermission->create();
                $this->UserGroupPermission->save($ds);
            }
            $this->redirect(array('action' => 'index'));
        } else {
            if ( !is_null($id) ) {
                $this->request->data = $this->UserGroup->findById($id);
            }
            global $permissionSet;
            $lset = array();
            foreach ( $permissionSet as $pm ) {
                $lset[$pm] = __($pm);
            }

            $selected = array();
            if ( isset($this->request['data']['UserGroupPermission']) ) {
                foreach ( $this->request['data']['UserGroupPermission'] as $permission ) {
                    $selected[] = $permission['permission'];
                }
            }
            $this->set('permissionSet', $lset);
            $this->set('permissionSelect', $selected);
        }
	}

	protected function _check_delete() {
		return $this->_check_index();
	}

	public function delete($id = null) {
        if ( !is_null($id) ) {
        	$this->loadModel('UserGroup');
        	$this->loadModel('UserGroupPermission');
        	$this->UserGroupPermission->deleteAll(array('UserGroupPermission.user_group_id' => $id));
        	$this->UserGroup->delete($id);
        }
        	$this->redirect(array('action' => 'index'));
	}
};