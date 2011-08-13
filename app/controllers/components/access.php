<?php
class AccessComponent extends Object{
    var $components = array('Acl', 'Auth', 'Session');
    var $user; 
	var $uses = array('Company');
	var $company;
	
    function startup(){
        $this->user = $this->Auth->user();
		$Company =& ClassRegistry::init('Company'); 
		$this->company = $Company->findById($this->Auth->user('company_id'));
    }
	
	function check($aco, $action='*'){
	    if(!empty($this->user) && $this->Acl->check('User::'.$this->user['User']['id'], $aco, $action)){
	        return true;
	    } else {
	        return false;
	    }
	}
	
	function authorize($group_array = array()) {
		if(!in_array($this->Auth->user('group_id'), $group_array)) {
			return false;
		}
		else {
			return true;
		}
	}
	
	function checkHelper($aro, $aco, $action = "*"){
	    App::import('Component', 'Acl');
	    $acl = new AclComponent();
	    return $acl->check($aro, $aco, $action);
	} 
	
	function checkCompanyStatus() {
		if(!$this->company) {
			return false;
		} elseif($this->company['Company']['status'] == 0) {
			return false;
		} else {
			return true;
		}
	}
	
	function checkCompanySubscription() {
		if($this->company['Company']['expire_date'] && $this->company['Company']['expire_date'] != '0000-00-00 00:00:00') {
			if(strtotime($this->company['Company']['expire_date']) < time()) {
				// expired account
				return false;
			} else {
				return true;
			}
		} else {
			return true;
		}
	}
	
	function isAdmin() {
		if($this->Auth->user('group_id') != Group::$AdminId)
			return false;
		return true;
	}
	
}
?>