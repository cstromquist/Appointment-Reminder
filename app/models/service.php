<?php
class Service extends AppModel {

    public $validate = array(
		'name' => array(
	       'rule' => array('maxLength', 255), 
	       'allowEmpty' => false, 
	       'required' => true,
	    )
	);
		
	public $hasMany = array(
	   'CompanyService'
	);
		
	var $name = 'Service';
    var $actsAs = array('Acl' => array('requester'));
	function parentNode() {
        return null;
    }
}
