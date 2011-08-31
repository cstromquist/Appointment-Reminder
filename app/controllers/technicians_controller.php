<?php
class TechniciansController extends AppController {
	
	var $name = "Technicians";
	var $components = array('Image','RequestHandler','JqImgcrop');
	var $helpers = array('Thickbox','Cropimage');
	
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
        	$this->data['Technician']['company_id'] = $this->Auth->user('company_id');
			// prevent overwriting image.
			unset($this->data['Technician']['image_path']);
        	if($this->Technician->save($this->data)) {
				if (!$this->Technician->exists()) return $this->cakeError('object_not_found');
		        if($this->data['Technician']['redirect']) {
					return $this->redirect($this->data['Technician']['redirect']);
				} else {
		        	$this->Session->setFlash(__('Technician saved!', true), 'default', array('class' => 'flash-success'));
					$this->redirect(array('action' => 'index'));
				}
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

	function admin_create() {
		if(!empty($this->data)) {
			$this->data['Technician']['company_id'] = $this->Auth->user('company_id');
			$this->Technician->create($this->data);
			if ($this->Technician->save($this->data)) {
				return $this->redirect(array('action' => 'edit', $this->Technician->id));
			}
		}
	}
	
	function admin_upload_photo($id = null) {
		$this->layout = 'admin_modal';
		$this->data['Technician']['id'] = $id;
	}
	
	function admin_crop_image($id = null){
		$this->layout = 'admin_modal';
		if (!empty($this->data)) {
			// check to make sure they actually uploaded something
			if(!$this->data['Technician']['image']['name']) {
				$this->Session->setFlash('Please upload an image.', 'default', array('class' => 'flash-error'));
				$this->redirect(array('controller' => 'technicians', 'action' => 'upload_photo', $this->data['Technician']['id']));
			} else {
				// check to make sure image is in jpeg format
				$filetype = $this->JqImgcrop->getFileExtension($this->data['Technician']['image']['name']);
				$filetype = strtolower($filetype);
				if (($filetype != "jpeg")  && ($filetype != "jpg")) {
					$this->Session->setFlash('Please ensure image is jpeg or jpg format.', 'default', array('class' => 'flash-error'));
					$this->redirect(array('controller' => 'technicians', 'action' => 'upload_photo', $this->data['Technician']['id']));
				} else {
					$uploaded = $this->JqImgcrop->uploadImage($this->data['Technician']['image'], 'uploads/technicians', 'tech_');
					$this->set('uploaded',$uploaded);
				}
			} 
		}
	}

	function admin_save_image($id =  null) {
		$this->layout = 'admin_modal';
		$arrayDir = explode('/', $this->data['Technician']['imagePath']);
		$image = array_pop($arrayDir);
		$this->JqImgcrop->cropImage(
			200, 
			$this->data['Technician']['x1'], 
			$this->data['Technician']['y1'],
			$this->data['Technician']['x2'],
			$this->data['Technician']['y2'],
			$this->data['Technician']['w'],
			$this->data['Technician']['h'],
			$this->data['Technician']['imagePath'],
			$this->data['Technician']['imagePath']);
		$technician = $this->Technician->findById($this->data['Technician']['id']);
		// first delete the old image
		if($technician['Technician']['image_path'] != '') {
			$this->JqImgcrop->deleteImage($technician['Technician']['image_path'], "uploads/technicians");
		}
		$technician['Technician']['image_path'] = $image;
		$this->Technician->set($technician["Technician"]);
		$this->Technician->save();
		$this->set(compact('technician'));
	}
}
