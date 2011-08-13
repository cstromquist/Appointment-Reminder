<?php
class Industry extends AppModel {

    public $validate = array(
		'inudstry_name' => array(
	       'rule' => array('maxLength', 255), 
	       'allowEmpty' => false, 
	       'required' => true, 
	       'on' => 'admin_create'
	    )
	);
		
	public $hasMany = array(
	   'Company' => array(
	       'className' => 'Company',
	       'order' => 'Company.created ASC'
	   )
	);
		
	var $name = 'Industry';
    var $actsAs = array('Acl' => array('requester'));
	function parentNode() {
        return null;
    }
}
