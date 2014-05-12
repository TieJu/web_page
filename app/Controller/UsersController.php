<?php

class UsersController
extends AppController {
    public $components = array('Captcha');

    function securimage($random_number){
      $this->autoLayout = false; //a blank layout
      $this->set('captcha_data', $this->Captcha->show()); //dynamically creates an image
    }

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('register', 'securimage');
    }

    protected function _setCookie($id) {
        if (!$this->request->data('User.remember_me')) {
            return false;
        }
        $data = array(
            'username' => $this->request->data('User.username'),
            'password' => $this->request->data('User.password')
        );
        $this->Cookie->write('User', $data, true, '+1 week');
        return true;
    }

    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->loggedIn() || $this->Auth->login()) {
                $this->_setCookie($this->Auth->user('id'));
                return $this->redirect($this->Auth->redirect());
            }
            $this->Auth->flash(__('Invalid username or password, try again.'));
        }
    }

    public function logout() {
        $this->Cookie->delete('User');
        $this->redirect($this->Auth->logout());
    }

    public function register() {
        $this->set('captcha_image_url', '../users/securimage/0');
        if ($this->request->is('post')) {
          if ( $this->Captcha->check($this->request->data['User']['captcha_code'] ) == false ) {
            $this->sessionMessageError(__('You failed the catpcha test!'));
            unset($this->request->data['User']['password']);
            unset($this->request->data['User']['captcha_code']);
          } else {
            unset($this->request->data['User']['captcha_code']);
            $this->User->create();
            $this->request->data['User']['username'] = strip_tags($this->request->data['User']['username']);
            if ($this->User->save($this->request->data)) {
               $this->sessionMassageOk(__('The user has been created.'));
                return $this->redirect(array('controller' => 'Sections', 'action' => 'index'));
            }
            $this->sessionMessageError(__('The user could not be created. Please, try again.'));
          }
        }
    }

    public function edit() {
        $user_data = $this->User->findById($this->Auth->user('id'));

        if (empty($user_data)){
          throw new BadRequestException("invalid user id");
        }

        if ( $this->request->is('post') || $this->request->is('put') ) {
          $this->request->data['User']['username'] = strip_tags($this->request->data['User']['username']);
            $this->request->data['User']['id'] = $this->Auth->user('id');
            if ( $this->User->save($this->request->data) ) {
                $this->sessionMessageOk(__('Your profile has been updated.'));
            } else {
                $this->sessionMessageError(__('Unable to update your profile.'));
            }
        } else {
            $this->request->data = $user_data;
        }
        unset($this->request->data['User']['password']);
    }

    protected function _check_index() {
    	$perms = $this->User->getPermissions($this->Auth->user('id'));
    	return isset($perms[PERMISION_ADMIN_USER]);
    }

    public function index() {
    	$this->pullAllAs('User', 'data');
    }

    protected function _check_delete() {
    	return $this->_check_index();
    }

    public function delete($id = null) {
        if ( !is_null($id) ) {
        	$this->User->delete($id);
        	$this->sessionMessageOk(__('User has been deleted'));
        }
        $this->redirect(array('action' => 'index'));
    }

    protected function _create_password($length = 8) {
		$pass_rnd = array_merge(range('a','z'), range('A','Z'), range(0,9));
		$rnd = array_rand($pass_rnd, $length);
		$random_hash = "";
		for ( $i = 0; $i < $length; ++$i ) {
			$random_hash .= $pass_rnd[$rnd[$i]];
		}
		$random_hash = str_shuffle($random_hash);
		return $random_hash;
	}

    protected function _reset_user_password($id) {
        $this->loadModel('User');
        $this->loadModel('Setting');

        $user = $this->User->findById($id);
        $user['User']['password'] = $this->_create_password($this->Setting->getValue(SERVER_PASSWORD_GEN_LENGTH, 8));
        $this->User->save($user);
        $email = new CakeEmail();
		$email->template('administrator_reset_user_password');
		$email->emailFormat('html');
		$email->viewVars(array('user' => $user));
		$email->from($this->Setting->getValue(SERVER_EMAIL_RESET_EMAIL, 'tiemo.jung@mni.thm.de'));
		$email->to($user['User']['email'], $user['User']['username']);
		$email->subject(__('Password Reset'));
		$email->send();
    }

    protected function _check_admin_edit() {
    	return $this->_check_index();
    }

    public function admin_edit($id = null) {
        if ( $this->request->is('post') || $this->request->is('put') ) {
            $this->request->data['User']['username'] = strip_tags($this->request->data['User']['username']);
            if ( is_null($id) ) {
                $this->User->create();
                $this->User->save($this->request->data);
                $this->request->data['User']['id'] = $this->User->id;
            } else {
                $this->request->data['User']['id'] = $id;
                $this->User->save($this->request->data);
            }

            if ( $this->request->data['User']['reset_pw'] ) {
                $this->_reset_user_password($this->request->data['User']['id']);
                if ( is_null($id) ) {
                    $this->sessionMessageOk(__('The user %s has been created, password has been send to %s', $this->request->data['User']['username'], $this->request->data['User']['email']));
                } else {
                    $this->sessionMessageOk(__('The user %s\'s password has been reset, data has been updated', $this->request->data['User']['username']));
                }
            } else {
                $this->sessionMessageOk(__('The user %s has been updated', $this->request->data['User']['username']));
            }
            $this->redirect(array('action' => 'index'));
        } else {
            $this->loadModel('UserGroup');
            $this->set('groups', $this->UserGroup->find('list'));
            $this->set('id', $id);
            if ( !is_null($id) ) {
                $this->request->data = $this->User->findById($id);
            } else {
                $this->request->data['User']['reset_pw'] = 1;
            }
        }
    }
};