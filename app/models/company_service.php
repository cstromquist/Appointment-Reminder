<?php
class CompanyService extends AppModel
{
	public $belongsTo = array(
		'Service', 'Company'
	);

	public $validate = array(
		'service_message' => array(
        	'rule' => 'notEmpty',
        	'allowEmpty' => false,
        	'required' => true
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
}	
