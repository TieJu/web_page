<?php
class ReportsController
extends AppController {
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index', 'view');
    }
    public function index() {
        $filters = $this->_getNamedParam('filters', '');
        $this->set('filters', $filters);
        $options = array('recursive' => 2);
        if ( $filters != '' ) {
          $set = explode(',', $filters);

          $conditions = array();
          while ( !empty($set) ) {
            $conditions[array_shift($set)] = array_shift($set);
          }

          if ( !empty($conditions)) {
            $options['conditions'] = $conditions;
          }
        }

        // need this deep recursion to make filter building simple
        $list = $this->Report->find('all', $options);
        $this->set('reports', $list);
        $this->set('proj_perms', $this->_getPermissionForAllProjects());
    }

    public function view($id = null) {
        if ( is_null($id) ) {
            $this->redirect(array('action' => 'index'));
            return;
        }

        if ( $this->request->is('post') || $this->request->is('put') ) {
          $this->request->data['ReportComment']['comment'] = $this->Purifier->purify($this->request->data['ReportComment']['comment']);
          $this->request->data['ReportComment']['report_id'] = $id;
          $this->request->data['ReportComment']['author_id'] = $this->Auth->user('id');
          $this->loadModel('ReportComment');
          $this->ReportComment->create();
          $this->ReportComment->save($this->request->data);
          unset($this->request->data['ReportComment']);
        }

        $rep = $this->Report->find('first', array('conditions' => array('Report.id' => $id), 'recursive' => 2));
        if ( empty($rep)) {
          throw new NotFoundException("can't find requested report");
        }
        $this->set('report', $rep);
        $this->set('can_edit', $this->hasProjectPermission(PERMISSION_PROJECT_EDIT_REPORT, $rep['Version']['project_id']));
        $this->set('can_comment', $this->hasProjectPermission(PERMISSION_PROJECT_COMMENT_REPORT, $rep['Version']['project_id']));
    }

    protected function _getProjectIdFromReportId($report_id_ ) {
      $rep = $this->Report->findById($report_id_);
      return $rep['Version']['project_id'];
    }

    protected function _check_edit() {
      return $this->hasProjectPermission(PERMISSION_PROJECT_EDIT_REPORT, $this->_getProjectIdFromReportId($this->getParam(0)));
    }

    public function edit($id = null) {
      $back = $this->_getNamedParam('back', array('action' => 'view', $id));
      if ( $this->request->is('post') || $this->request->is('put') ) {
        $this->request->data['Report']['name'] = strip_tags($this->request->data['Report']['name']);
        $this->request->data['Report']['description'] = $this->Purifier->purify($this->request->data['Report']['description']);
        $this->request->data['Report']['id'] = $id;
        $this->Report->save($this->request->data);
        $this->redirect($back);
      } else {
        $this->loadModel('ProjectVersion');
        $this->loadModel('Priority');
        $this->loadModel('Status');
        $this->loadModel('User');
        $this->request->data = $this->Report->findById($id);
        $vlist = $this->ProjectVersion->find('list', array('conditions' => array('ProjectVersion.project_id' => $this->request->data['Version']['project_id'])));
        $plist = $this->Priority->find('list');
        $slist = $this->Status->find('list');
        $ulist = $this->User->find('list', array('fields' => array('User.id', 'User.username')));
        $this->set('id', $id);
        $this->set('versions', $vlist);
        $this->set('targets', (array(0 => '') + $vlist));
        $this->set('priorities', $plist);
        $this->set('statuses', $slist);
        $this->set('back', $back);
        $this->set('users', array(__('None')) + $ulist);
      }
    }

}