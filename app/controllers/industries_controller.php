<?php
class IndustriesController extends AppController {
	
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
        $industries = $this->Industry->find('all', array('order' => 'Industry.name ASC'));
    	$this->set(compact('industries'));
	}
	
	function admin_add() {
		
	}
	
	function admin_edit($id = null) {
		if($id) {
			$industry = $this->Industry->findById($id);
			$this->data = $industry;
		}
	}
	
	 /**
     * Create new service
     *
     */
    function admin_create() {
    	//$this->Industry->create($this->data);
    	if ($this->Industry->save($this->data)) {
            return $this->redirect(array('action' => 'index'));
        }

        $settings = $this->Industry->find('all');
        $this->set(compact('settings'));
        $this->render('admin_index');
    }
	
}
