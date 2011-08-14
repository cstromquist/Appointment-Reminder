<?php
class TechniciansController extends AppController {
	
	var $name = "Technicians";
	var $components = array("Image","RequestHandler");
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->Access->checkCompanyStatus();
	}
	
    function admin_index() {
    	$finder = array('Technician.company_id=' . $this->Auth->user('company_id'));
    	$technicians = $this->Technician->find('all', array("conditions"=>$finder));
    	$this->set(compact('technicians'));
	}
    
	/**
     * Admin reminder search
     *
     * @param string $query Search term, encoded by Javascript's encodeURI()
     */
    function admin_search($query = '') {
        $query = urldecode($query);
        $reminderResults = ClassRegistry::init('Technician')->search($query);
        $results = am($reminderResults);
        $this->set('results', $results);
        if ($this->RequestHandler->isAjax()) {
            $this->render('../technicians/wf_search');
        }
    }
	
	/**
     * Edit a Technician
     * 
     * @param int $id Technician ID
     */
    function admin_edit($id = null) {
        if(!empty($this->data)) {
        	if($this->Technician->save($this->data)) {
				if (!$this->Technician->exists()) return $this->cakeError('object_not_found');
		        $this->Session->setFlash(__('Technician saved!', true), 'default', array('class' => 'flash-success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The technician could not be saved. Please, try again.', true), 'default', array('class' => 'flash-error'));
			}
        } else {
        	$technician = $this->Technician->findById($id);
        	$this->data = $technician;
			$this->set(compact('technician'));
		} 
    }
	
	function admin_delete($id = null) {
		$tech = $this->Technician->findById($id);
		// be sure this user has permissions to delete this technician
		if(!$this->Access->isAdmin()) {
			if($this->Auth->user('company_id') != $tech['Technician']['company_id']) {
				$this->Session->setFlash('You do not have permission to delete this company', 'default', array('class' => 'flash-error'));
				return;
			}
		}
		$this->Technician->delete($id);
		$this->Session->setFlash('Technician successfully deleted!', 'default', array('class' => 'flash-success'));
		if($this->Access->isAdmin()) {
			$this->redirect(array('controller' => 'companies', 'action' => 'index'));
		} else {
			$this->redirect('index');
		}
		
	}
	
	 /* Change the technician photo
     * 
     * @param int $id Technician ID
     */
	function admin_change_photo($id) {
		if(!$this->data[$this->modelClass]['Image/photo']['name']) {
			$this->Session->setFlash(__('Please choose an image to upload.', true), 'default',  array('class' => 'flash-error'));
			$this->redirect(array('action' => 'edit/', $id));
		}
		// first delete the old image
		$technician = $this->Technician->findById($id);
		if($technician['Technician']['image_path'] != '') {
			$this->Image->delete_image($technician['Technician']['image_path'], "uploads/technicians");
		}
			
		$image_path = $this->Image->upload_image_and_thumbnail($this->data[$this->modelClass]['Image/photo'], 573, 380, 80, 80, "uploads/technicians");
    	if(isset($image_path)) {
     		$this->Session->setFlash(__('Photo successfully uploaded!', true), 'default',  array('class' => 'flash-success'));
     		$this->Technician->saveField('image_path',$image_path);
    	}
    	else {
     		$this->Session->setFlash(__('The image for this technician could not be saved. Please, try again.', true), 'default',  array('class' => 'flash-error'));
    	}

		$this->redirect(array('action' => 'edit/', $id));
	}
	
	function admin_create() {
		print_r($this->data);
		if(!empty($this->data)) {
			$this->data['Technician']['company_id'] = $this->Auth->user('company_id');
			$this->Technician->create($this->data);
			if ($this->Technician->save($this->data)) {
				return $this->redirect(array('action' => 'edit', $this->Technician->id));
	        }
		}
	}
	
}