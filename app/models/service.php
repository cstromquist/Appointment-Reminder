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
	   	'CompanyService' => array(
	   		'className' => 'CompanyService',
			'order' => 'CompanyService.created ASC'
	   	)
	);
		
	var $name = 'Service';
    var $actsAs = array('Acl' => array('requester'));
	function parentNode() {
        return null;
    }
}
