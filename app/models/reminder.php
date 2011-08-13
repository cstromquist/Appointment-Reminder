<?php
/**
 * Reminder model
 * 
 * Email reminders which are generated using GD
 *
 * @package appointment reminder
 */
class Reminder extends AppModel {
	
	public $validate = array(
        'fname' => array(
            'rule' => 'notEmpty',
            'allowEmpty' => false,
            'required' => true
        ),
        'lname' => array(
            'rule' => 'notEmpty',
            'allowEmpty' => false,
            'required' => true,
            'message' => 'Please specify a last name.'
        ),
		'service_message' => array(
            'rule' => 'notEmpty',
            'allowEmpty' => false,
            'required' => true
        ),
        'email' => array(
            'rule' => 'email',
            'allowEmpty' => false,
            'required' => true,
            'message' => 'Please enter a valid email.'
        ),
        'features_benefits' => array(
			'rule' => array('checkMaxList', 8),
			'allowEmpty' => false,
            'required' => true
		),
		'services' => array(
			'rule' => array('checkMaxList', 10),
			'allowEmpty' => false,
            'required' => true
		),
		'other_services' => array(
			'rule' => array('checkMaxList', 8),
			'allowEmpty' => false,
            'required' => true
		)
	);
	
	function checkMaxList($check, $limit) {
		$value = array_shift($check);
		$array_items = explode("\n", $value);
		$over_by = count($array_items) - $limit;
		if(count($array_items) > $limit) {
			return 'Please limit to '.$limit.' list items. You currently have ' . count($array_items) . ' items listed. Please remove ' . $over_by . ' items.';
		}
		return true;
	}
	
	/**
     * Search name
     *
     * @param string $query
     * @return array
     */
    function search($query) {
    	$fields = array('id', 'fname', 'lname', 'email');
		
    	$results = $this->find(
			'all',
			array(
				'conditions' => "{$this->name}.lname LIKE '%$query%' OR {$this->name}.fname LIKE '%$query%'",
				'fields' => $fields
			)
		);
    	
    	return $results;
    }
}
