<?php

class SectionsController
extends AppController {
    protected function _check_index() {
    	$this->loadModel('User');
    	return $this->User->checkPermission($this->Auth->user('id'), PERMISION_ADMIN_SECTION);
    }

    public function index() {
    	$this->pullAllAs('Section', 'data');
    }

    protected function _check_edit() {
    	return $this->_check_index();
    }

    public function edit($id = null) {
        if ( $this->request->is('post') || $this->request->is('put') ) {
            $this->request->data['Section']['name'] = strip_tags($this->request->data['Section']['name']);
            $this->request->data['Section']['controller'] = strip_tags($this->request->data['Section']['controller']);
            $this->request->data['Section']['action'] = strip_tags($this->request->data['Section']['action']);
            $this->request->data['Section']['params'] = strip_tags($this->request->data['Section']['params']);
            if ( is_null($id) ) {
                $this->Section->create();
            } else {
                $this->request->data['Section']['id'] = $id;
            }
            if ( $this->Section->save($this->request->data) ) {
              if ( is_null($id) ) {
                $this->sessionMessageOk(__('New page section has been created'));
              } else {
                $this->sessionMessageOk(__('Page section has been saved'));
              }
            } else {
              $this->sessionMessageError(__('Error while saving page section'));
            }
            $this->redirect(array('action' => 'index'));
        } else {
            $this->set('parents', ( array( 0 => '' ) + $this->Section->find('list', array('conditions' => array('Section.id !=' => $id)))));
            if ( is_null($id) ) {
            } else {
                $this->request->data = $this->Section->findById($id);
            }
        }
    }

    public function _check_delete() {
    	return $this->_check_index();
    }

    public function delete($id = null) {
        if ( $this->Section->delete($id) ) {
          $this->sessionMessageOk(__('Page section has been deleted'));
        } else {
          $this->sessionMessageError(__('Error while deleting page section'));
        }
        $this->redirect(array('action' => 'index'));
    }
}