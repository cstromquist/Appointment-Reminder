<?php
class CompanyServicesController extends AppController
{
	public $uses = array('CompanyService');
	
	public function admin_index() {
		$this->set('company_services', $this->CompanyService->find('all', array('conditions' => array('CompanyService.company_id' => $this->Auth->user('company_id')))));
	}
	
	function admin_edit($id = null) {
		if(!empty($this->data)) {
    		if ($this->CompanyService->save($this->data)) {
            	return $this->redirect(array('action' => 'index'));
        	}
		} elseif($id) {
			$CompanyService = $this->CompanyService->findById($id);
			$this->data = $CompanyService;
		} else {
			$this->Session->setFlash('You cannot access this resource.', 'default', array('class' => 'flash-error'));
			return $this->redirect(array('action' => 'index'));
		}
	}
}