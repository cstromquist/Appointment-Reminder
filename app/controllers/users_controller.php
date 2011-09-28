<?php
class UsersController extends AppController {

    public $helpers = array('Wildflower.List', 'Time');
    public $pageTitle = 'User Accounts';
	var $name = 'Users';
	var $belongsTo = array('Group');
	var $actsAs = array('Acl' => array('type' => 'requester'));
	public $components = array('Email','Access','PasswordHelper');

	function beforeFilter() {
        parent::BeforeFilter();
		
        if(!$this->Access->authorize(array(4,5)) && $this->action != 'login' && $this->action != 'admin_logout' && $this->action != 'forgot_password' && $this->action != 'password_sent') {
        	$this->Session->setFlash('You cannot access this resource.', 'default', array('class' => 'flash-error'));
			$this->redirect($this->Auth->loginRedirect);
        }
    }

	function parentNode() {
	    if (!$this->id && empty($this->data)) {
	        return null;
	    }
	    if (isset($this->data['User']['group_id'])) {
			$groupId = $this->data['User']['group_id'];
	    } else {
	    	$groupId = $this->field('group_id');
	    }
	    if (!$groupId) {
		return null;
	    } else {
	        return array('Group' => array('id' => $groupId));
	    }
	}
	
    /**
     * @TODO shit code, refactor
     *
     * Delete an user
     *
     * @param int $id
     */
    function admin_delete($id) {
        $id = intval($id);
        if ($this->RequestHandler->isAjax()) {
            return $this->User->del($id);
        }

        if (empty($this->data)) {
            $this->data = $this->User->findById($id);
            if (empty($this->data)) {
                $this->indexRedirect();
            }
        } else {
        	$User = $this->User->findById($this->data[$this->modelClass]['id']);
			if($User['User']['id'] == $this->Auth->user('id')) {
				$this->Session->setFlash('You can\t delete yourself!', 'default', array('class' => 'flash-error'));
				$this->redirect('index');
			}
            $this->User->del($this->data[$this->modelClass]['id']);
            $this->indexRedirect();
        }
    }

    /**
     * Login screen
     *
     */
    function login() {
        $this->layout = 'login';   
        $this->pageTitle = 'Login';
        $User = ClassRegistry::init('User');

        // Try to authorize user with POSTed data
        if ($user = $this->Auth->user()) {
            if (!empty($this->data) && $this->data['User']['remember']) {
                // Generate unique cookie token
                $cookieToken = Security::hash(String::uuid(), null, true);
                
                while ($User->findByCookieToken($cookieToken)) {
                    $cookieToken = Security::hash(String::uuid(), null, true);
                }

                // Save token to DB
                $User->create($user);
                $User->saveField('cookie_token', $cookieToken);

                // Save login cookie
                $cookie = array();
                $cookie['login'] = $this->data['User']['login'];
                $cookie['cookie_token'] = $cookieToken;
                $this->Cookie->write('Auth.User', $cookie, true, '+2 weeks');
                unset($this->data['User']['remember']);
            }
            
            // Save last login time
            $User->create($user);
            $User->saveField('last_login', date('Y-m-d h:i:s', time()));
            $User->save($user);
			
            $this->redirect(array('controller' => 'admin'));
        }

        // Try to authorize user with data from a cookie
        if (empty($this->data)) {
            $cookie = $this->Cookie->read('Auth.User');
            if (!is_null($cookie)) {
                $this->Auth->fields = array(
                    'username' => 'login', 
                    'password' => 'cookie_token'
                );
                if ($this->Auth->login($cookie)) {
                    //  Clear auth message, just in case we use it.
                    $this->Session->del('Message.auth');
                    
                    // Save last login time
                    $User->create($user);
                    $User->saveField('last_login', time());
                    
                    return $this->redirect($this->Auth->redirect());
                } else { 
                    // Delete invalid Cookie
                    $this->Cookie->del('Auth.User');
                }
            }
        }
    }

    /**
     * Logout
     * 
     * Delete User info from Session, Cookie and reset cookie token.
     */
    function admin_logout() {
        $this->User->create($this->Auth->user());
        $this->User->saveField('cookie_token', '');
        $this->Cookie->del('Auth.User');
        $this->redirect($this->Auth->logout());
    }

    function admin_view($id) {
        $this->User->recursive = -1;
        $this->set('user', $this->User->findById($id));
    }

    /**
     * Users overview
     * 
     */
    function admin_index() {
    	if($this->Auth->user('group_id') == Group::$AdminId) {
        	$users = $this->User->findAll();
			$isAdmin = true;
		} else {
			$users = $this->User->findAll(array('company_id' => $this->Auth->user('company_id')));
			$isAdmin = false;
		}
		$companies = $this->User->Company->find('list');
		$groups = $this->User->Group->find('list');
		$this->set(compact('companies', 'groups', 'users', 'isAdmin'));
    }
    
    function admin_change_password($id = null) {
        $this->data = $this->User->findById($id);
    }

    /**
     * Create new user
     *
     */
    function admin_create() {
    	$password = $this->data['User']['confirm_password'];
        if($this->Auth->user('group_id') == Group::$AdminId) {
        	$users = $this->User->findAll();
			$isAdmin = true;
		} else {
			$users = $this->User->findAll(array('company_id' => $this->Auth->user('company_id')));
			$isAdmin = false;
			// if not an admin, then a manager is creating a CRP
        	$this->data['User']['group_id'] = Group::$CrpId;
			$this->data['User']['company_id'] = $this->Auth->user('company_id');
		}
        if ($this->User->save($this->data)) {
        	//send email
        	$this->send_email($this->data, $password);
        	$this->Session->setFlash('User successfully saved!', 'default', array('class' => 'flash-success'));
            return $this->redirect(array('action' => 'index'));
        } else {
        	$this->set(compact('users', 'isAdmin'));
        	$this->render('admin_index');
		}
    }

	/*
	 * Send user email 
	 * 
	 */
	function send_email($user, $password) {
		$objCompany = ClassRegistry::init('Company');
		$company = $objCompany->findById($this->Auth->user('company_id'));
		$this->Email->from    = sprintf('%s <%s>', $company['Company']['name'], $user['User']['email']);
		$this->Email->to      = sprintf('%s <%s>', $user['User']['name'], $user['User']['email']);
		$this->Email->bcc	  = array('chris.stromquist@gmail.com');
		$this->Email->subject = 'Your Account Has Been Created!';
		$this->Email->template = 'newuser';
		$this->Email->sendAs = 'both';
		
		$link = 'http://' . $_SERVER['HTTP_HOST'] . $this->base. '/users/login/';
		$name = $user['User']['name'];
		$username = $user['User']['login'];
		$this->set(compact('link', 'name', 'username', 'password'));
		
		$this->Email->send();
	}
	
    /**
     * Edit user account
     *
     * @param int $id
     */
    function admin_edit($id = null) {
        $this->data = $this->User->findById($id);
        if (empty($this->data)) $this->cakeError('object_not_found');
    }
	
	function admin_profile() {
        if(!empty($this->data)) {
	    	if($this->data['User']['password'] == '8a7e7c266f8271bb1ceb0927169dcd41f172f856') { 
	    		unset($this->data['User']['password']);
	    	}
    		if($this->User->save($this->data))
				$this->Session->setFlash('User successfully saved!', 'default', array('class' => 'flash-success'));
			else
				$this->Session->setFlash('Please fix the errors below.', 'default', array('class' => 'flash-error'));
    	} else {
        	$this->data = $this->User->findById($this->Auth->user('id'));
			if (empty($this->data)) $this->cakeError('object_not_found');
			unset($this->data['User']['password']);
		}
    }
    
    function admin_update() {
        unset($this->User->validate['password']);
        $this->User->create($this->data);
        if ($this->User->save()) {
            return $this->redirect(array('action' => 'edit', $this->User->id));
        }
        $this->render('admin_edit');
    }
    
    function admin_update_password() {
        unset($this->User->validate['name'], $this->User->validate['email'], $this->User->validate['login']);
        App::import('Security');
        $this->data['User']['password'] = Security::hash($this->data['User']['password'], null, true);
        $this->User->create($this->data);
        if (!$this->User->exists()) $this->cakeError('object_not_found');
        if ($this->User->save()) {
            return $this->redirect(array('action' => 'edit', $this->data[$this->modelClass]['id']));
        }
        $this->render('admin_change_password');
    }
	
	function forgot_password() {
		if(!empty($this->data)) {
			$data = $this->User->find('first', array('conditions' => array('User.email' => $this->data['User']['email'])));
			$user = array('User' => $data['User']);
			if(!$user) {
				$this->Session->setFlash('Profile not found.', 'default', array('class' => 'flash-error'));
			} else {
				$password = $this->PasswordHelper->generatePassword(8);
				$user['User']['password'] = $this->Auth->password($password);
				$this->User->save($user);
				$this->send_password($user, $password);
				$this->redirect(array('controller' => 'users', 'action' => 'password_sent'));
			}
		}
	}
	
	/*
	 * Send user email 
	 * 
	 */
	function send_password($user, $password) {
		$this->Email->from    = sprintf('%s <%s>', 'Appointment Reminder', 'nobody@nobody.com');
		$this->Email->to      = sprintf('%s <%s>', $user['User']['name'], $user['User']['email']);
		$this->Email->subject = 'Your New Password';
		$this->Email->template = 'newpassword';
		$this->Email->sendAs = 'both';
		
		$link = 'http://' . $_SERVER['HTTP_HOST'] . $this->base. '/users/login/';
		$name = $user['User']['name'];
		$this->set(compact('link', 'name', 'username', 'password'));
		
		$this->Email->send();
	}
	
	function password_sent() {
		
	}
}
