<?php

class ProjectVersionStatusesController
extends AppController {
	protected function _check_index() {
    	$this->loadModel('User');
    	return $this->User->checkPermission($this->Auth->user('id'),PERMISION_ADMIN_PROJECT);
	}

	public function index() {
		$this->pullAllAs('ProjectVersionStatus', 'data');
	}

	protected function _check_edit() {
		return $this->_check_index();
	}

	public function edit($id = null) {
	  $back = $this->_getNamedParam('back', array('action' => 'index'));

		if ( $this->request->is('post') || $this->request->is('put') ) {
		  $this->request->data['ProjectVersionStatus']['name'] = strip_tags($this->request->data['ProjectVersionStatus']['name']);
		  $this->request->data['ProjectVersionStatus']['property_string'] = strip_tags($this->request->data['ProjectVersionStatus']['property_string']);
			if ( !is_null($id) ) {
				$this->request->data['ProjectVersionStatus']['id'] = $id;
			} else {
				$this->ProjectVersionStatus->create();
			}
			if ( $this->ProjectVersionStatus->save($this->request->data) ) {
			  if ( is_null($id) ) {
			    $this->sessionMessageOk(__('Project Version has been created'));
			  } else {
			    $this->sessionMessageOk(__('Project Version has been updated'));
			  }
			} else {
			  $this->sessionMessageError(__('Error while saving project version'));
			}
			$this->redirect($back);
		} else {
			$this->set('back', $back);
			$this->request->data = $this->ProjectVersionStatus->findById($id);
		}
	}

	protected function _check_delete() {
		return $this->_check_index();
	}

  public function delete($id = null) {
    if ( !is_null($id) ) {
      $self = $this->ProjectVersionStatus->findById($id);
      if ( !empty($self['ProjectVersion']) ) {
        $this->sessionMessageInfo(__('You can not delete the version status %s, because at least one project version uses it', $self['ProjectVersionStatus']['name']));
      } else {
        if ( $this->ProjectVersionStatus->delete($id) ) {
          $this->sessionMessageOk(__('Version status %s has been deleted', $self['ProjectVersionStatus']['name']));
        } else {
          $this->sessionMessageError(__('Unable to delete version status %s', $self['ProjectVersionStatus']['name']));
        }
      }
    }
    $this->redirect(array('action' => 'index'));
  }
};