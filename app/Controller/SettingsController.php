<?php
class SettingsController
extends AppController {
	protected function _check_index() {
    	$this->loadModel('User');
    	return $this->User->checkPermission($this->Auth->user('id'), PERMISION_ADMIN_SERVER);
	}

	public function index() {
        if ( $this->request->is('post') || $this->request->is('put') ) {
            foreach ( $this->request->data['ServerConfig'] as $cfg => $val ) {
                $this->Setting->setValue($cfg, strip_tags($val));
            }
        } else {
            $this->request->data['ServerConfig'] = array();
            $cfgs = array(INDEX_LINK => '', SERVER_EMAIL_RESET_EMAIL => 'tiemo.jung@mni.thm.de', SERVER_PASSWORD_GEN_LENGTH => '8');
            foreach ( $cfgs as $cfg => $val ) {
                $this->request->data['ServerConfig'][$cfg] = $this->Setting->getValue($cfg, $val);
            }
        }
	}
};