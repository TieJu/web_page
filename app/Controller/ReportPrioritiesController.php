<?php

class ReportPrioritiesController
extends AppController {
    protected function _check_index() {
      return $this->isProjectAdmin();
    }

	public function index() {
		$this->pullAllAs('ReportPriority', 'data');
	}

	protected function _check_edit() {
		return $this->isProjectAdmin();
	}

  public function edit($id = null) {
    if ( $this->request->is('post') || $this->request->is('put') ) {
      $this->request->data['ReportPriority']['name'] = strip_tags($this->request->data['ReportPriority']['name']);
      if ( is_null($id) ) {
          $this->Priority->create();
      } else {
          $this->request->data['ReportPriority']['id'] = $id;
      }
      if ( $this->ReportPriority->save($this->request->data) ) {
        if ( is_null($id) ) {
          $this->sessionMessageOk(__('New report priority has been created'));
        } else {
          $this->sessionMessageOk(__('Report priority has been updated'));
        }
      } else {
        $this->sessionMessageError(__('Unable to save report priority'));
      }
      $this->redirect(array('action' => 'index'));
    } else {
      $this->request->data = $this->ReportPriority->findById($id);
    }
  }

	protected function _check_delete() {
		return $this->isProjectAdmin();
	}

	public function delete($id = null) {
		$self = $this->ReportPriority->findById($id);
		if ( !empty($self['Report']) ) {
		  $this->sessionMessageInfo(__('You can not delete the report priority %s, because at least one report uses it!', $self['ReportPriority']['name']));
		} else {
			if ( $this->ReportPriority->delete($id) ) {
			  $this->sessionMessageOk(__('Report priority has been deleted'));
			} else {
			  $this->sessionMessageError(__('Unable to delete report priority'));
			}
		}
		$this->redirect(array('action' => 'index'));
	}
};