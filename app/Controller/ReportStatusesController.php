<?php

class ReportStatusesController
extends AppController {
    protected function _check_inde() {
    	return $this->isPrjectAdmin();
    }

	public function index() {
		$this->pullAllAs('ReportStatus', 'data');
	}

    protected function _check_edit() {
    	return $this->isProjectAdmin();
    }

  public function edit($id = null) {
    if ( $this->request->is('post') || $this->request->is('put') ) {
      $this->request->data['ReportStatus']['name'] = strip_tags($this->request->data['ReportStatus']['name']);
      $this->request->data['ReportStatus']['property_string'] = strip_tags($this->request->data['ReportStatus']['property_string']);
      if ( is_null($id) ) {
          $this->ReportStatus->create();
      } else {
          $this->request->data['ReportStatus']['id'] = $id;
      }

      if ( $this->ReportStatus->save($this->request->data) ) {
        if ( is_null($id) ) {
          $this->sessionMessageOk(__('New report status type has been create'));
        } else {
          $this->sessionMessageOk(__('Report status type has been updated'));
        }
      } else {
        $this->sessionMessageError(__('Unable to save report status type'));
      }
      $this->redirect(array('action' => 'index'));
    } else {
      if ( is_null($id) ) {
      } else {
          $this->request->data = $this->ReportStatus->findById($id);
      }
    }
  }

    protected function _check_delete() {
    	return $this->isProjectAdmin();
    }

	public function delete($id = null) {
		$self = $this->ReportStatus->findById($id);
		if ( empty($self['Report'])) {
			if ( $this->ReportStatus->delete($id) ) {
			  $this->sessionMessageOk(__('Report status type has been deleted'));
			} else {
			  $this->sessionMessageError(__('Failed to delete report status type'));
			}
		} else {
		  $this->sessionMessageInfo(__('You can not delete the report status %s, because it is used by at least one report', $self['ReportStatus']['name']));
		}

		$this->redirect(array('action' => 'index'));
	}
};