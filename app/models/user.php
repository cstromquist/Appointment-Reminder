<?php
/**
 * User model
 * 
 * Users are Wildflower`s administrator accounts.
 *
 * @todo Allow login to have chars like _.
 * @package wildflower
 */
class User extends AppModel {
	
	var $name = 'User';
    var $belongsTo = array('Company', 'Group');
    var $actsAs = array('Acl' => array('requester'));
	
	public $hasMany = array(
	    'Page',
	    'Post',
	);

    public $validate = array(
        'name' => array(
            'rule' => array('between', 1, 255),
            'allowEmpty' => false,
            'required' => true
        ),
		'login' => array(
			'alphaNumeric' => array(
				'rule' => array('between', 5, 50),
				'required' => true,
				'message' => 'Login must be between 5 to 50 alphanumeric characters long'
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'Username taken.  Please choose another username.'
			)
		),
		'password' => array(
            'between' => array(
                'rule' => array('between', 5, 50),
                'message' => 'Password must be between 5 to 50 characters long'
            ),
            'confirmPassword' => array(
                'rule' => array('confirmPassword'),
                'message' => 'Please enter the same value for both password fields'
            )
        ),
		'email' => array(
			'rule' => 'email',
			'required' => true,
			'message' => 'Please enter a valid email address'
		)
    );
		
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
     * Does password and password confirm match?
     *
     * @return bool true
     */
    function confirmPassword() {
    	App::import('Auth');
		$Auth = new AuthComponent();
        $this->data[$this->name]['confirm_password'] = $Auth->password($this->data[$this->name]['confirm_password']);
		//$this->data[$this->name]['password'] = $Auth->password($this->data[$this->name]['password']);	
        if ($this->data[$this->name]['confirm_password'] !== $this->data[$this->name]['password']) {
            return false;
        }
        return true;
    }
	
	function checkDuplicate() {
		$user = $this->User->find('first', array('conditions' => array('login' => $this->data[$this->name]['login'])));
		if($user)
			return false;
		return true;
	}

}
