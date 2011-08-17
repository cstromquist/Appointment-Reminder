<?php
class ServicesController extends AppController {
	
	public $components = array('Acl', 'Access');
	public $pageTitle = 'Service Settings';
	
	function beforeFilter() {
        parent::BeforeFilter();
		
		$this->Access->checkCompanyStatus();
		
        if(!$this->Access->authorize(array(4))) {
        	$this->Session->setFlash('You cannot access this resource.', 'default', array('class' => 'flash-error'));
			$this->redirect($this->Auth->loginRedirect);
        }
    }
	
	function admin_index() {
        $Services = $this->Service->find('all', array('order' => 'Service.name ASC'));
    	$this->set(compact('Services'));
	}
	
	function admin_add() {
		
	}
	
	function admin_edit($id = null) {
		if($id) {
			$Service = $this->Service->findById($id);
			$this->data = $Service;
		}
	}
	
	 /**
     * Create new service
     *
     */
    function admin_create() {
    	//$this->Service->create($this->data);
    	if ($this->Service->save($this->data)) {
            return $this->redirect(array('action' => 'index'));
        }

        $settings = $this->Service->find('all');
        $this->set(compact('settings'));
        $this->render('admin_index');
    }
	
}
