<?php
class ForumThreadsController
 extends AppController {
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('article');
    }

    public function article($id = null) {
        $thread = $this->ForumThread->find('first', array('conditions' => array('ForumThread.id' => $id, 'ForumThread.article' => true), 'recursive' => 2));
        $this->set('thread', $thread);
        if ( empty($thread) ) {
          // go back to index page
          // TODO: throw error ?
          $this->redirect('/');
          return;
        }

        if ( $this->request->is('post') || $this->request->is('put') ) {
          $this->loadModel('ForumPost');
          $this->request->data['ForumPost']['thread_id'] = $id;
          $this->request->data['ForumPost']['author_id'] = $this->Auth->user('id');
          $this->request->data['ForumPost']['name'] = __('Comment');
          $this->request->data['ForumPost']['post'] = $this->Purifier->purify($this->request->data['ForumPost']['post']);
          $this->ForumPost->create();
          if ( $this->ForumPost->save($this->request->data) ) {
            $this->sessionMessageOk(__('Your post has been saved'));
          } else {
            $this->sessionMessageError(__('Unable to save your post'));
          }
          unset($this->request->data['ForumPost']);
          // update thread mod date to move it up at the thread overview
          // TODO: the update stuff needs to be in sync with the target db...
          $this->ForumThread->id = $id;
          $this->ForumThread->saveField('modified', date('Y-m-d H:i:s'));
        }
    }

    public function _check_view() {
        return $this->_checkAuthThreadId('read');
    }

    public function view($id = null) {
        if ( $this->request->is('post') || $this->request->is('put') ) {
          $this->loadModel('ForumPost');
          $this->request->data['ForumPost']['thread_id'] = $id;
          $this->request->data['ForumPost']['author_id'] = $this->Auth->user('id');
          $this->request->data['ForumPost']['post'] = $this->Purifier->purify($this->request->data['ForumPost']['post']);
          $this->request->data['Tag'] = $this->_transformTagsToStore($this->request->data['ForumPost']['tags']);
          unset($this->request->data['ForumPost']['tags']);
          $this->ForumPost->create();
          if ( $this->ForumPost->save($this->request->data) ) {
            $this->sessionMessageOk(__('Your post has been saved'));
          } else {
            $this->sessionMessageError(__('Unable to save your post'));
          }
          unset($this->request->data['ForumPost']);
          $self = $this->ForumThread->find('first', array('conditions' => array('ForumThread.id' => $id), 'recursive' => -1));
          // update thread mod date to move it up at the thread overview
          // TODO: the update stuff needs to be in sync with the target db...
          $this->ForumThread->id = $id;
          $this->ForumThread->saveField('modified', date('Y-m-d H:i:s'));
        }

        $thread = $this->ForumThread->find('first', array('conditions' => array('ForumThread.id' => $id), 'recursive' => 2));
        if ( empty($thread) ) {
          throw new NotFoundException("can't find requested forum thread");
        }
        $perms = $this->_getUserForumPermission( $thread['ForumThread']['forum_id'], $this->Auth->user( 'id' ) );
        $this->request->data['ForumPost']['name'] = __('RE: %s', $thread['ForumThread']['name']);
        $this->set('thread', $thread);
        $this->set('forumPermissions', $perms);
    }

    protected function _check_create() {
    	return $this->_checkAuthForumId('write');
    }

    protected function _transformTagsToStore($data) {
      $this->loadModel('Tag');
      // turn a comma separated list into an array of tag entries
      $tags = explode(',', strip_tags($data));
      $tag_set = array();
      foreach ( $tags as $tag ) {
        $tag = trim($tag);
        if ( $tag == '' ) {
          continue;
        }
        $db_tag = $this->Tag->findByName($tag);
        if ( empty($db_tag) || is_null($db_tag['Tag']['name']) ) {
          $this->Tag->create();
          $this->Tag->save(array('Tag' => array('name' => $tag)));
          $tag_set[] = $this->Tag->id;
        } else {
          $tag_set[] = $db_tag['Tag']['id'];
        }
      }
      return array('Tag' => $tag_set);
    }

    protected function _transformTagsFromStore($data) {
      $set = array();
      foreach ( $data as $tag ) {
        $set[] = $tag['name'];
      }
      return implode(', ', $set);
    }

    public function create($forum_id = null) {
        $perms = $this->_getUserForumPermission( $forum_id, $this->Auth->user( 'id' ) );
        $this->set('forumPermissions', $perms);
        $this->set('forumId', $forum_id);
        if ( $this->request->is('post') || $this->request->is('put') ) {
            $this->loadModel('ForumPost');

            $this->request->data['ForumThread']['author_id'] = $this->Auth->user( 'id' );
            $this->request->data['ForumThread']['name'] = strip_tags($this->request->data['ForumThread']['name']);

            $post = array();
            $post['ForumPost']['post'] = $this->Purifier->purify($this->request->data['ForumThread']['text']);
            unset($this->request->data['ForumThread']['text']);
            $this->ForumThread->create();
            $post['ForumPost']['name'] = $this->request->data['ForumThread']['name'];
            if ( $this->ForumThread->save($this->request->data) ) {
              $post['ForumPost']['thread_id'] = $this->ForumThread->id;
              $post['ForumPost']['author_id'] = $this->Auth->user( 'id' );
              $post['Tag'] = $this->_transformTagsToStore($this->request->data['ForumThread']['tags']);
              $this->ForumPost->create();
              if ( $this->ForumPost->save($post) ) {
                $this->sessionMessageOk(__('New thread has been created'));
              } else {
                $this->sessionMessageError(__('Error while saving new thread'));
                // remove thread or it will cause a lot of error messages
                $this->ForumThread->delete($post['ForumPost']['thread_id']);
              }
            } else {
              $this->sessionMessageError(__('Error while saving new thread'));
            }
            $this->redirect(array('action' => 'view', $post['ForumPost']['thread_id']));
        } else {
            $this->request->data['ForumThread'] = array('forum_id' => $forum_id);
        }
    }

    public function _check_delete() {
        return $this->_checkAuthThreadId('mod');
    }

    public function delete($id = null) {
        $this->loadModel('ForumPost');

        $thread = $this->ForumThread->findById($id);
        $this->ForumPost->deleteAll(array('ForumPost.thread_id' => $id));
        $this->ForumThread->delete($id);

        $this->redirect(array('controller' => 'forums', 'action' => 'index',$thread['ForumThread']['forum_id']));
    }

    public function toggle_lock($id = null) {
        $thread = $this->ForumThread->findById($id);
        $perms = $this->_getUserForumPermission( $thread['ForumThread']['forum_id'], $this->Auth->user( 'id' ) );

        $thread['ForumThread']['locked'] = 1 - $thread['ForumThread']['locked'];
        $this->ForumThread->save($thread);

        $this->redirect(array('controller' => 'forums', 'action' => 'index', $thread['ForumThread']['forum_id']));
    }

    public function edit($post_id = null) {
      $this->loadModel('ForumPost');
      $self = $this->ForumPost->findById($post_id);
      if ( empty($self) ) {
        throw new NotFoundException("can't find requested forum thread");
      }
      $first = $this->ForumPost->find('first', array('conditions' => array('ForumPost.thread_id' => $self['ForumPost']['thread_id'])));
      $is_first = $first['ForumPost']['id'] == $post_id;
      $is_mod = $this->_checkAuthThread( $self['ForumPost']['thread_id'], 'mod' );
      if ( $this->request->is('post') || $this->request->is('put') ) {
        $this->request->data['ForumPost']['name'] = strip_tags($this->request->data['ForumPost']['name']);
        $this->request->data['ForumPost']['name'] = $this->Purifier->purify($this->request->data['ForumPost']['name']);
        if ( $is_first ) {
          $thread['ForumThread']['id'] = $self['ForumPost']['thread_id'];
          if ( $is_mod ) {
            // mod can change sticky bit
            $thread['ForumThread']['sticky'] = $this->request->data['ForumPost']['sticky'];
          }
          // can update name of thread
          // can update article bit
          $thread['ForumThread']['name'] = $this->request->data['ForumPost']['name'];
          $thread['ForumThread']['article'] = $this->request->data['ForumPost']['article'];
          $thread['ForumThread']['modified'] = false; // don't change modified field, it is used to order threads
          $this->ForumThread->save($thread);
        }
        $this->ForumPost->id = $post_id;
        $this->request->data['Tag'] = $this->_transformTagsToStore($this->request->data['ForumPost']['tags']);
        $this->ForumPost->save($this->request->data);
        $this->redirect(array('action' => 'view', $self['ForumPost']['thread_id']));
      } else {
        $this->set('is_mod', $is_mod);
        $self['ForumPost']['tags'] = $this->_transformTagsFromStore($self['Tag']);
        $self['ForumPost']['sticky'] = $self['Thread']['sticky'];
        $self['ForumPost']['article'] = $self['Thread']['article'];
        $this->set('is_first_post',$is_first);
        $this->request->data = $self;
        $this->set('tid', $self['ForumPost']['thread_id']);
      }
    }
}