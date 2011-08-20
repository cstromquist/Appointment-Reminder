<?php
class Dashboard extends AppModel {
	
    public $useTable = false;
    static public $classNames = array(
        'Company' => array('id', 'name', 'updated'),
        'Technician' => array('id', 'name', 'updated'),
        'Reminder' => array('id', 'fname', 'lname', 'updated')
    );
    
    function findRecentHappening($group_id, $company_id = null) {
        // Get changed or added companies, technicians, emails sent, recent company sign-ups
        $limit = 15;
        $recursive = -1;
		$conditions = null;
        $items = array();		
		if($group_id == 6 || $group_id == 5) {
			$conditions = array('company_id' => $company_id);
			array_shift(Dashboard::$classNames);
		}
		
		$models = Dashboard::$classNames;
		
        foreach ($models as $model => $fields) {
            $class = ClassRegistry::init($model);
            $items = array_merge($items, $class->find('all', compact('limit', 'recursive', 'fields', 'conditions')));
        }

        // Sort by update time
        function cmp($a, $b) {
            $a = Dashboard::accessByClassName($a);
            $b = Dashboard::accessByClassName($b);
            $aTime = strtotime($a['item']['updated']);
            $bTime = strtotime($b['item']['updated']);
            return $bTime - $aTime;
        }
        usort($items, 'cmp');
        
        // Create an array without diff keys
        foreach ($items as &$item) {
            $item = Dashboard::accessByClassName($item);
        }
        
        return $items;
    }
	
    static function accessByClassName($array) {
        $names = array_keys(Dashboard::$classNames);
        foreach ($names as $name) {
            if (isset($array[$name])) {
                // Unify everything to title
                if (isset($array[$name]['name'])) {
                    $array[$name]['title'] = $array[$name]['name'];
                }
				if (isset($array[$name]['fname'])) {
                    $array[$name]['title'] = $array[$name]['fname'] . " " . $array[$name]['lname'];
                }
                return array(
                    'item' => $array[$name],
                    'class' => $name
                );
            }
        }
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
    
}
