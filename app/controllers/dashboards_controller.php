<?php
class DashboardsController extends AppController {
	
	public $helpers = array('List', 'Time', 'Text');
	public $pageTitle = 'Dashboard';
	public $components = array('Auth', 'Access');
	
	function beforeFilter() {
		parent::beforeFilter();
	}
	
	function admin_index() {
		$company_id = $this->Auth->user('company_id');
		if($this->Auth->user('group_id') == 4) {
        	$items = $this->Dashboard->findRecentHappening(4);
		} elseif($this->Auth->user('group_id') == 5) {
			$items = $this->Dashboard->findRecentHappening(5, $company_id);
		} else {
			$items = $this->Dashboard->findRecentHappening(6, $company_id);
		}
        $users = ClassRegistry::init('User')->find('all', array('order' => 'last_login ASC', 'conditions' => array('company_id' => $this->Auth->user('company_id'))));
		$this->set(compact('items', 'users'));
	}
	
	/**
     * Admin company and technician search
     *
     * @param string $query Search term, encoded by Javascript's encodeURI()
     */
    function admin_search($query = '') {
        $query = urldecode($query);
        $companyResults = ClassRegistry::init('Company')->search($query);
        $results = am($companyResults);
        $this->set('results', $results);
        if ($this->RequestHandler->isAjax()) {
            $this->render('../dashboards/wf_search');
        }
    }
    
    /**
     * Public search @TODO
     *
     */
    function search() {
		$query = Sanitize::escape($_GET['q']);
        $postResults = $this->Post->search($query);
        $pageResults = $this->Page->search($query);
        if (!is_array($postResults)) {
        	$postResults = array();
        }
        if (!is_array($pageResults)) {
        	$pageResults = array();
        }
        $results = array_merge($postResults, $pageResults);
        $this->set('results', $results);

        if ($this->RequestHandler->isAjax()) {
            $this->render('/elements/search_results');
        }
    }
	
}
