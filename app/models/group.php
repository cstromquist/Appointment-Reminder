<?php
class Group extends AppModel {

	var $name = 'Group';
	var $validate = array(
		'name' => array('notempty')
	);
	public static $AdminId = 4;
	public static $ManagerId = 5;
	public static $CrpId = 6;
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasMany = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'group_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
		
	var $actsAs = array('Acl' => array('type' => 'requester'));
 
	function parentNode() {
    	return null;
	}	

}
?>