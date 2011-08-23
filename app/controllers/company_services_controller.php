<?php
class CompanyServicesController extends AppController
{
	public $uses = array('CompanyService');
	
	public function admin_index() {
		$companyservices = $this->CompanyService->find('all', array('conditions' => array('CompanyService.company_id' => $this->Auth->user('company_id'))));
		$this->set(compact('companyservices'));
	}
	
	function admin_edit($id = null) {
		if(!empty($this->data)) {
    		if ($this->CompanyService->save($this->data)) {
            	return $this->redirect(array('action' => 'index'));
        	} else {
        		$this->Session->setFlash('Please fix the errors below.', 'default', array('class' => 'flash-error'));
        	}
		} elseif($id) {
			$CompanyService = $this->CompanyService->findById($id);
			$this->data = $CompanyService;
		} else {
			$this->Session->setFlash('You cannot access this resource.', 'default', array('class' => 'flash-error'));
			return $this->redirect(array('action' => 'index'));
		}
	}
	
	function admin_add() {
		$Service =& ClassRegistry::init('Service'); 
		$services = $Service->find('list', array('fields' => array('Service.id', 'Service.name')));
		$selectedservices = $this->CompanyService->find('list', array('fields' => array('CompanyService.service_id', 'CompanyService.name'), 'conditions' => array('CompanyService.company_id' => $this->Auth->user('company_id'))));
		$this->set(compact('services', 'selectedservices'));
		if(!empty($this->data)) {
			if(!is_array($this->data['CompanyService']['company_services'])) {
				$this->Session->setFlash('Please select at least one service.', 'default', array('class' => 'flash-error'));
				return;
			} else {
				foreach($this->data['CompanyService']['company_services'] as $key => $val) {
					// check to see if it already exists and if so, skip
					if($this->CompanyService->find('first', array('conditions' => array('CompanyService.company_id' => $this->Auth->user('company_id'), 'CompanyService.service_id' => $val))))
						continue;
					$service = $Service->findById($val);
					$company_service['service_id'] = $val;
					$company_service['company_id'] = $this->Auth->user('company_id');
					$company_service['name'] = $service['Service']['name'];
					$company_service['service_message'] = $service['Service']['service_message'];
					$company_service['features_benefits'] = $service['Service']['features_benefits'];
					$company_service['services'] = $service['Service']['services'];
					$company_service['other_services'] = $service['Service']['other_services'];
					$this->CompanyService->create();
					$this->CompanyService->set($company_service);
					$this->CompanyService->save();
				}
				$this->Session->setFlash('Services succesfully saved!', 'default', array('class' => 'flash-success'));
				return $this->redirect(array('action' => 'index'));
			}
		}
	}

}