<?php
class ProjectsController
extends AppController {
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index', 'view');
    }

    public function index() {
    	$this->pullAllAs('Project', 'data');
    	$this->set('is_admin', $this->isProjectAdmin());
    }

    public function view($id = null) {
        $this->loadModel('Report');
        if ( is_null($id) ) {
            $this->redirect(array('action' => 'index'));
            return;
        }

        $this->set('is_admin', $this->isProjectAdmin($id));
        $this->set('project', $this->Project->find('first', array('conditions' => array('Project.id' => $id), 'recursive' => 2)));
        $this->set('can_file_report', $this->hasProjectPermission(PERMISSION_PROJECT_FILE_REPORT, $this->getParam(0)));
        $this->set('can_add_version', $this->hasProjectPermission(PERMISSION_PROJECT_EDIT_PROJECT, $this->getParam(0)));
    }

    protected function _buildPermissionSet($set, $pid) {
      debug($set);
      $r = array();
      $names = array(PERMISSION_PROJECT_FILE_REPORT, PERMISSION_PROJECT_EDIT_REPORT, PERMISSION_PROJECT_COMMENT_REPORT, PERMISSION_PROJECT_EDIT_PROJECT);
      foreach ( $names as $name ) {
        $d = $set['Project'][$name];
        foreach ( $d as $gs ) {
          $r[] = array('ProjectUserGroupPermission' => array( 'project_id' => $pid, 'user_group_id' => $gs, 'permission' => $name ));
        }
      }

      return $r;
    }

    protected function _transformProjectData(&$set) {
      $names = array(PERMISSION_PROJECT_FILE_REPORT, PERMISSION_PROJECT_EDIT_REPORT, PERMISSION_PROJECT_COMMENT_REPORT, PERMISSION_PROJECT_EDIT_PROJECT);
      foreach ( $names as $name ) {
        $set['Project'][$name] = array();
        foreach ( $set['ProjectUserGroupPermission'] as $perm ) {
          if ( $perm['permission'] == $name ) {
            $set['Project'][$name][] = $perm['user_group_id'];
          }
        }
      }
    }

    protected function _check_edit() {
      return $this->hasProjectPermission(PERMISSION_PROJECT_EDIT_PROJECT, $this->getParam(0));
    }

    public function edit($id = null) {
    	if ( $this->request->is('post') || $this->request->is('put') ) {
    		if ( is_null($id) ) {
    			$this->Project->create();
    		} else {
    			$this->request->data['Project']['id'] = $id;
    		}
    		$this->request->data['Project']['name'] = strip_tags($this->request->data['Project']['name']);
    		$this->request->data['Project']['description'] = $this->Purifier->purify($this->request->data['Project']['description']);
    		if ( $this->Project->save($this->request->data) ) {
    		  if ( is_null($id) ) {
      		  $this->sessionMessageOk(__('The project %s has been created', $this->request->data['Project']['name']));
    		  } else {
    		    $this->sessionMessageOk(__('The project %s has been updated', $this->request->data['Project']['name']));
    		  }
      		$id = $this->Project->id;
      		// group stuff needed to be stored manually
      		$this->loadModel('ProjectUserGroupPermission');
      		$this->ProjectUserGroupPermission->deleteAll(array('ProjectUserGroupPermission.project_id' => $id));
      		$gset = $this->_buildPermissionSet($this->request->data, $id);
      		if ( !$this->ProjectUserGroupPermission->saveMany($gset) ) {
      		  $this->sessionMessageWarning(__('The project %s has been created, but there was an error while saving group permission for the project', $this->request->data['Project']['name']));
      		}
    		} else {
    		  $this->sessionMessageError(__('Can not create project %s', $this->request->data['Project']['name']));
    		}
    		$this->redirect(array('action' => 'index'));
    	} else {
    	  if ( !is_null($id) ) {
    	    $data = $this->Project->findById($id);
    	    $this->_transformProjectData($data);
    	    $this->request->data = $data;
    	  }
  	    $this->loadModel('UserGroup');
  	    $this->set('groups', $this->UserGroup->find('list'));
	   }
    }

    protected function _check_delete() {
      return $this->hasProjectPermission(PERMISSION_PROJECT_EDIT_PROJECT, $this->getParam(0));
    }

    public function delete($id = null) {
        if ( !is_null($id) ) {
            $this->loadModel('Project');
            $this->Project->delete($id);
        }
        $this->redirect(array('action' => 'index'));
    }

    protected function _check_file_report() {
      return $this->hasProjectPermission(PERMISSION_PROJECT_FILE_REPORT, $this->getParam(0));
    }

    public function file_report($id = null) {
        if ( is_null($id) ) {
            $this->redirect($this->_getNamedParam('back', array('action' => 'index')));
            return;
        }
        $back = $this->_getNamedParam('back', array('action' => 'index', $id));

        $this->loadModel('Report');
        $this->loadModel('Priority');
        $this->loadModel('Status');
        $this->loadModel('ProjectVersion');
        if ( $this->request->is('post') || $this->request->is('put') ) {
            $this->request->data['Report']['name'] = strip_tags($this->request->data['Report']['name']);
            $this->request->data['Report']['description'] = $this->Purifier->purify($this->request->data['Report']['description']);
            // auto fill in author
            $this->request->data['Report']['author_id'] = $this->Auth->user('id');
            // fix some field naming
            $this->request->data['Report']['project_version_id'] = $this->request->data['Report']['version_id'];
            unset($this->request->data['Report']['version_id']);
            $this->request->data['Report']['target_version_id'] = $this->request->data['Report']['target_id'];
            unset($this->request->data['Report']['target_id']);

            $this->Report->create();
            if ( $this->Report->save($this->request->data) ) {
              $this->sessionMessageOk(__('New report has been filed'));
            } else {
              $this->sessionMessageError(__('Unable to store report'));
            }
            $this->redirect($back);
        } else {
            $pro = $this->Project->findById($id);
            if ( empty($pro) ) {
              throw new NotFoundException("can't find requested project");
            }
            $vlist = $this->ProjectVersion->find('list', array('conditions' => array('ProjectVersion.project_id' => $id)));
            $plist = $this->Priority->find('list');
            $slist = $this->Status->find('list');
            //$mlist = array(0 => '');

            $this->set('project', $pro);
            $this->set('versions', $vlist);
            $this->set('targets', array(0 => '') + $vlist);
            $this->set('priorities', $plist);
            $this->set('statuses', $slist);
            $this->set('back', $back);
            //$this->set('assignments', $mlist);
        }
    }
}