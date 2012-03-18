<?php
class DashboardsController extends AppController {
	
	public $helpers = array('List', 'Time', 'Text');
	public $pageTitle = 'Dashboard';
	public $components = array('Auth', 'Access');
	//public $uses = array('Company', 'Technician', 'Reminder');
	
	function beforeFilter() {
		parent::beforeFilter();
	}
	
	function admin_welcome() {
		$objReminder = ClassRegistry::init('Reminder');
		// check to see if Reminders were already added
		$reminderscheck = false;
		$total = $objReminder->find('count', array('conditions' => array('Reminder.company_id' => $this->Auth->user('company_id'))));
		if($total) {
			$this->redirect(array('controller' => 'dashboards', 'action' => 'index'));
		}
		// check to make sure company settings have been completed
		$objCompany = ClassRegistry::init('Company');
		$company = $objCompany->findById($this->Auth->user('company_id'));
		$haslogo = false;
		$hasphoto = false;
		$haspaymentmethods = false;
		$hasemail = false;
		$haswebsite = false;
		$companycheck = false;
		if($company['Company']['logo_path']) {
			$haslogo = true;
		}
		if($company['Company']['photo_path']) {
			$hasphoto = true;
		}
		if($company['Company']['payment_methods']) {
			$haspaymentmethods = true;
		}
		if($company['Company']['email']) {
			$hasemail = true;
		}
		if($company['Company']['website_url']) {
			$haswebsite = true;
		}
		if($haslogo && $hasphoto && $haspaymentmethods && $hasemail && $haswebsite) {
			$companycheck = true;
		}
		
		// check if a tech has been added
		$techniciancheck = false;
		$objTechnician = ClassRegistry::init('Technician');
		$total = $objTechnician->find('count', array('conditions' => array('Technician.company_id' => $this->Auth->user('company_id'))));
		if($total) {
			$techniciancheck = true;
		}
		
		// check to make sure company has services
		$companyservicescheck = false;
		$objCompanyService = ClassRegistry::init('CompanyService');
		$total = $objCompanyService->find('count', array('conditions' => array('CompanyService.company_id' => $this->Auth->user('company_id'))));
		if($total) {
			$companyservicescheck = true;
		}
		$this->set(compact('haslogo', 'hasphoto', 'haspaymentmethods', 'hasemail', 'haswebsite', 'companycheck', 'techniciancheck', 'companyservicescheck', 'reminderscheck'));
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
    function admin_search2($query = '') {
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
    function admin_search($query = '') {
		$query = urldecode($query);
		$Technician =& ClassRegistry::init('Technician');
        $techResults = $Technician->search($query);
		$Company =& ClassRegistry::init('Company');
        $companyResults = $Company->search($query);
		$Reminder =& ClassRegistry::init('Reminder');
        $reminderResults = $Reminder->search($query);
        if (!is_array($techResults)) {
        	$techResults = array();
        }
        if (!is_array($companyResults)) {
        	$companyResults = array();
        }
		if (!is_array($reminderResults)) {
        	$reminderResults = array();
        }
        $results = array_merge($techResults, $companyResults, $reminderResults);
        $this->set('results', $results);

        if ($this->RequestHandler->isAjax()) {
            $this->render('../dashboards/wf_search');
        }
    }
	
}
