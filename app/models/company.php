<?php
class Company extends AppModel {

    public $validate = array(
		'name' => array(
	       'rule' => 'notEmpty', 
	       'allowEmpty' => false, 
	       'required' => true,
	       'message' => 'Please enter your company name.'
	    ),
	    'phone' => array(
	       'rule' => 'notEmpty', 
	       'allowEmpty' => false, 
	       'required' => true,
	       'message' => 'Please enter your company phone.'
	    ),
	    'website_url' => array(
	       'rule' => 'notEmpty', 
	       'allowEmpty' => false, 
	       'required' => true,
	       'message' => 'Please enter your company website.'
	    )
	);
		
	public $hasMany = array(
	   'Technician' => array(
	       'className' => 'Technician',
	       'order' => 'Technician.created ASC'
	   ),
	   'User' => array(
	   		'className' => 'User',
			'order' => 'User.created ASC'
	   	),
	   	'Reminder' => array(
	   		'className' => 'Reminder',
			'order' => 'Reminder.created ASC'
	   	)
	);
		
	var $name = 'Company';
    var $actsAs = array('Acl' => array('requester'));
	var $belongsTo = array('Industry');
	function parentNode() {
        return null;
    }
	
	/**
     * Search title and content fields
     *
     * @param string $query
     * @return array
     */
    function search($query) {
    	$fields = array('id', 'name');
		
    	$results = $this->find(
			'all',
			array(
				'conditions' => "{$this->name}.name LIKE '%$query%'",
				'fields' => $fields
			)
		);
    	
    	return $results;
    }
	
	function create_unique_id() {
		return uniqid(true);
	}
}
