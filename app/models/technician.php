<?php
class Technician extends AppModel {

    public $validate = array(
		'name' => array(
	       'rule' => 'notEmpty', 
	       'allowEmpty' => false, 
	       'required' => true,
	       'message' => 'Technician name is required.'
	    ),
	    'bio' => array(
	       'rule' => 'notEmpty', 
	       'allowEmpty' => false, 
	       'required' => true,
	       'message' => 'Technician bio is required.'
	    ),
		'technician_file_size' => array(
			'rule' => array('maxLength', 6),
			'message' => 'Image size is waaayyy too big. Try resizing first'
		)
	);
		
	public $hasMany = array(
	   'Reminder' => array(
	       'className' => 'Reminder',
	       'order' => 'Reminder.created ASC'
	   )
	);
		
	public $belongsTo = array('Company');
	
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

}
