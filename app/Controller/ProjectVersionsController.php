<?php

class ProjectVersionsController
extends AppController {
  public function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('view');
  }

	protected function _check_edit() {
    	$this->loadModel('User');
    	return $this->User->checkPermission($this->Auth->user('id'), PERMISION_ADMIN_PROJECT);
	}

	public function edit($id = null) {
	  $back = $this->_getNamedParam('back', array('controller' => 'projects', 'action' => 'index'));

	  if ( is_null($id) ) {
	    $this->redirect($back);
	    return;
	  }

	  $data = $this->ProjectVersion->findById($id);
	  if ( !$this->_hasNamedParam('back') ) {
	    $back = array('controller' => 'projects', 'action' => 'view',  $data['ProjectVersion']['project_id']);
	  }

	  if ( $this->request->is('post') || $this->request->is('put') ) {
	    $this->request->data['ProjectVersion']['name'] = strip_tags($this->request->data['ProjectVersion']['name']);
	    $this->request->data['ProjectVersion']['description'] = $this->Purifier->purify($this->request->data['ProjectVersion']['description']);
	    $this->request->data['ProjectVersion']['id'] = $id;
	    if ( $this->ProjectVersion->save($this->request->data) ) {
	      $this->sessionMessageOk(__('The project version has been updated'));
	      $this->request->data = $this->ProjectVersion->findById($id);
	    } else {
        $this->sessionMessageError(__('Unable to save project version'));
	    }
	    $this->redirect($back);
	  } else {
	    $this->loadModel('ProjectVersionStatus');
	    $this->set('statuses', $this->ProjectVersionStatus->find('list'));
	    $this->set('back', $back);
	    $this->set('id', $id);
	    $this->request->data = $data;
	  }
	}

	protected function _check_create() {
		return $this->_check_edit();
	}

	public function create($id = null) {
		if ( is_null($id) ) {
		  // TODO: throw bad request error?
			$this->redirect(array('controller' => 'projects', 'action' => 'index'));
			return;
		}

		if ( $this->request->is('post') || $this->request->is('put') ) {
		  $this->ProjectVersion->create();
	    $this->request->data['ProjectVersion']['name'] = strip_tags($this->request->data['ProjectVersion']['name']);
	    $this->request->data['ProjectVersion']['description'] = $this->Purifier->purify($this->request->data['ProjectVersion']['description']);
		  $this->request->data['ProjectVersion']['project_id'] = $id;
		  if ( $this->ProjectVersion->save($this->request->data) ) {
		    $this->sessionMessageOk(__('The project version %s has been created', $this->request->data['ProjectVersion']['name']));
		  } else {
		    $this->sessionMessageError(__('Unable to create project version'));
		  }
		  $this->redirect(array('controller' => 'projects', 'action' => 'view', $id));
		} else {
		  $this->loadModel('ProjectVersionStatus');
		  $this->set('pid', $id);
	    $this->set('statuses', $this->ProjectVersionStatus->find('list'));
		}
	}

	protected function _check_delete() {
		return $this->_check_edit();
	}

	public function delete($id = null) {
		$v = $this->ProjectVersions->findById($id);
		$this->ProjectVersions->delete($id);
		$this->redirect(array('controller' => 'project', 'action' => 'view', $v['ProjectVersion']['project_id']));
	}

  protected function _check_view() {
    return $this->_check_edit();
  }

  public function view($id = null) {
    $data = $this->ProjectVersion->find('first', array('conditions' => array('ProjectVersion.id' => $id), 'recursive' => 2));
    if ( empty($data) ) {
      throw new NotFoundException("can't find requested project version");
    }
    $this->set('can_file_report', $this->hasProjectPermission(PERMISSION_PROJECT_FILE_REPORT, $data['ProjectVersion']['project_id']));
    $this->set('data', $data);
  }
};
