<?php
class CompaniesController extends AppController {
    var $name = 'Companies';
	var $components = array('Image','RequestHandler','Access','Email','Auth','JqImgcrop');
	var $helpers = array('Thickbox','Cropimage');
	
	function beforeFilter() {
        parent::BeforeFilter();
		
		// only allow the register action for all.
        if(!$this->Access->authorize(array(4,5)) && !$this->action == 'register') {
        	$this->Session->setFlash('You cannot access this resource.', 'default', array('class' => 'flash-error'));
			$this->redirect($this->Auth->loginRedirect);
        }
    }
	
    function admin_index() {
    	if(!$this->Access->authorize(array(4))) {
    		$this->Session->setFlash('You cannot access this resource.', 'default', array('class' => 'flash-error'));
			$this->redirect($this->Auth->loginRedirect);
			return;
		}
    	$companies = $this->Company->find('all', array('order' => 'Company.name ASC'));
    	$this->set(compact('companies'));
	}
    
	/**
     * Admin Company search
     *
     * @param string $query Search term, encoded by Javascript's encodeURI()
     */
    function admin_search($query = '') {
        $query = urldecode($query);
        $CompanyResults = ClassRegistry::init('Company')->search($query);
        $results = am($CompanyResults);
        $this->set('results', $results);
        if ($this->RequestHandler->isAjax()) {
            $this->render('../companies/wf_search');
        }
    }
	
	/**
     * Edit a company
     * 
     * @param int $id Company ID
     */
    function admin_edit($id = null) {
    	if($id == null) {
    		$id = $this->Auth->user('company_id');
			$company = $this->Company->findById($id);
        	$this->data = $company;
    	} else {
        	$company = $this->Company->findById($id);
        	$this->data = $company;
		}
		// set the payment method options
		$Setting = new Setting;
		$settings = $Setting->find('name="payment_methods"');
	    $pm_array = explode(',', $settings['Setting']['value']);
		foreach($pm_array as $key=>$val) {
			$options[$val] = $val;
		}
		// set the selected options for the company
		$array = explode(',', $this->data['Company']['payment_methods']);
		foreach($array as $key=>$val) {
			$selected[$val] = $val;
		}
		if($this->Access->isAdmin()) {
			$isAdmin = 1;
		} else {
			$isAdmin = 0;
		}
		$this->set(compact('options', 'selected', 'isAdmin'));
		
    }
	
	function admin_update() {
		if(count($this->data[$this->modelClass]['payment_methods']) > 1) {
			$payment_methods = "";
			foreach($this->data[$this->modelClass]['payment_methods'] as $key => $val) {
				$payment_methods .= $val . ",";
			}
		} else {
			$this->Session->setFlash('Please choose a payment method.', 'default', array('class' => 'flash-error'));
			return $this->redirect(array('action' => 'edit'), $this->data[$this->modelClass]['id']);
		}
		$payment_methods = substr($payment_methods, 0, strlen($payment_methods) - 1);
		$this->data[$this->modelClass]['payment_methods'] = $payment_methods;
        $this->Company->create($this->data);
        if (!$this->Company->exists()) return $this->cakeError('object_not_found');
        
        if(!$this->Company->save($this->data)) {
        	$this->Session->setFlash('Company save error.', 'default', array('class' => 'flash-error'));
			return $this->redirect(array('action' => 'edit'), $this->data['Company']['id']);
        }
        //if (empty($company)) return $this->cakeError('save_error');
		
		$this->Session->setFlash('Company saved.', 'default', array('class' => 'flash-success'));
		
		if($this->Access->isAdmin()) {
        	$this->redirect(array('action' => 'index'));
        } else {
        	$this->redirect(array('action' => 'edit'), $this->data['Company']['id']);
        }
    }
	
	/**
     * Create a new company.
     *
     */
    function admin_create() {
    	if(!empty($this->data)) {
			$this->Company->create($this->data);
			if ($this->Company->save($this->data)) {
				return $this->redirect(array('action' => 'edit', $this->Company->id));
			}
		}
    }
	
	/* 
	 * Change the company logo
     * 
     * @param int $id Company ID
     */
	function admin_change_logo($id) {
		if(!empty($this->data)) {
			if(!$this->data['Company']['Image/logo']['name']) {
				$this->Session->setFlash(__('Please choose an image to upload.', true), 'default',  array('class' => 'flash-error'));
				$this->redirect(array('action' => 'edit/', $id));
			}
			// first delete the old image
			$company = $this->Company->findById($id);
			if($company['Company']['logo_path'] != '') {
				$this->Image->delete_image($company['Company']['logo_path'], "uploads/companies/logos");
			}
				
			$logo_path = $this->Image->upload_image_and_thumbnail($this->data['Company']['Image/logo'], 180, 100, 80, 80, "uploads/companies/logos");
	    	if(isset($logo_path)) {
	    		$this->Session->setFlash(__('Logo successfully uploaded!', true), 'default',  array('class' => 'flash-success'));
	     		$this->Company->saveField('logo_path',$logo_path);
	    	}
	    	else {
	     		$this->Session->setFlash(__('Your company logo could not be saved. Please, try again.', true), 'default', array('class' => 'flash-error'));
	    	}
	
			$this->redirect(array('action' => 'edit/', $id));
		}
	}

	function admin_delete($id) {
		if(!empty($this->data)) {
			$Technician =& ClassRegistry::init('Technician'); 
			$Technician->deleteAll(array('Technician.company_id' => $id));
			$Reminder =& ClassRegistry::init('Reminder');
			$Reminder->deleteAll(array('Reminder.company_id' => $id));
			$User =& ClassRegistry::init('User'); 
			$User->deleteAll(array('User.company_id' => $id));
			$CompanyService =& ClassRegistry::init('CompanyService');
			$CompanyService->deleteAll(array('CompanyService.company_id' => $id));
			$this->Company->delete($id);
			$this->Session->setFlash('Company successfully deleted!', 'default', array('class' => 'flash-success'));
			$this->redirect('index');
		} else {
			$this->data['Company']['id'] = $id;
		}
	}
	
	/*
	 * Allow anonymous user to register for application.
	 * 
	 */
	function register() {
		$Service =& ClassRegistry::init('Service'); 
		$services = $Service->find('list', array('fields' => array('Service.id', 'Service.name')));
		$this->set(compact('services'));
		if (!empty($this->data)) {
			if(!is_array($this->data['Company']['company_services'])) {
				$this->Session->setFlash('Please select at least one service.', 'default', array('class' => 'flash-error'));
				return;
			}
			$this->data['Company']['status'] = 0;
			$this->data['Company']['activation_id'] = $this->Company->create_unique_id();
			$this->data['User'][0]['group_id'] = Group::$ManagerId;
			unset($this->Company->User->validate['company_id']);
			$password = $this->data['User'][0]['password']; 
			$this->data['User'][0]['password'] = $this->Auth->password($this->data['User'][0]['password']);
			if($this->Company->saveAll($this->data, array('validate'=>'first'))) {
				$this->send_activation_email($this->data['Company'], $this->data['User'][0]);
				
				$CompanyService =& ClassRegistry::init('CompanyService');
				// at this point loop through the services and add them to the CompanyServices
				foreach($this->data['Company']['company_services'] as $key => $val) {
					$service = $Service->findById($val);
					$company_service['service_id'] = $val;
					$company_service['company_id'] = $this->Company->id;
					$company_service['name'] = $service['Service']['name'];
					$company_service['service_message'] = $service['Service']['service_message'];
					$company_service['features_benefits'] = $service['Service']['features_benefits'];
					$company_service['services'] = $service['Service']['services'];
					$company_service['other_services'] = $service['Service']['other_services'];
					$CompanyService->create();
					$CompanyService->set($company_service);
					$CompanyService->save();
				}
				
				$this->redirect('register_success');
			} else {
				$this->data['User'][0]['password'] = $password;
				$this->Session->setFlash('Please correct the errors below.', 'default', array('class' => 'flash-error'));
			}
        } else {

        }
	}

	function register_success() {
		
	}
	
	/*
	 * Send activation email 
	 * 
	 */
	function send_activation_email($company, $user) {
		
		$settings = Configure::read('AppSettings');
		
		$this->Email->from    = sprintf('%s <%s>', $settings['site_name'], $settings['from_email']);
		$this->Email->to      = sprintf('%s <%s>', $user['name'], $user['email']);
		$this->Email->bcc	  = array($settings['contact_email']);
		$this->Email->subject = 'Please Activate Your Account';
		$this->Email->template = 'activate';
		$this->Email->sendAs = 'both';
		
		$link = 'http://' . $_SERVER['HTTP_HOST'] . $this->base. '/companies/activate/' . $company['activation_id'];
		$name = $user['name'];
		$this->set(compact('link', 'name'));
		
		$this->Email->send();
	}
	
	function activate($id = null) {
		if(!$id) {
			$message = "Invalid activation id.";
			$flag = false;
		} else {
			$company = $this->Company->find('first', array('conditions' => array('activation_id' => $id)));
			if($company) {
				$company['Company']['status'] = 1;
				$this->Company->save($company);
				$message = "Your company has been activated!";
				$flag = true;
			} else {
				$flag = false;
				$message = "Company was not found.";
			}
		}
		$this->set(compact('message', 'flag'));
	}
	
	/*
	 * Crop Photo functions
	 */
	function admin_upload_photo($id = null) {
		$this->layout = 'admin_modal';
		$this->data['Company']['id'] = $id;
	}
	
	function admin_crop_image($id = null){
		$this->layout = 'admin_modal';
		if (!empty($this->data)) {
			// check to make sure they actually uploaded something
			if(!$this->data['Company']['image']['name']) {
				$this->Session->setFlash('Please upload an image.', 'default', array('class' => 'flash-error'));
				$this->redirect(array('controller' => 'companies', 'action' => 'upload_photo', $this->data['Company']['id']));
			} else {
				// check to make sure image is in jpeg format
				$filetype = $this->JqImgcrop->getFileExtension($this->data['Company']['image']['name']);
				$filetype = strtolower($filetype);
				if (($filetype != "jpeg")  && ($filetype != "jpg")) {
					$this->Session->setFlash('Please ensure image is jpeg or jpg format.', 'default', array('class' => 'flash-error'));
					$this->redirect(array('controller' => 'companies', 'action' => 'upload_photo', $this->data['Company']['id']));
				} else {
					$uploaded = $this->JqImgcrop->uploadImage($this->data['Company']['image'], 'uploads/companies/photos', 'company_');
					$this->set('uploaded',$uploaded);
				}
			} 
		}
	}

	function admin_save_image($id =  null) {
		$this->layout = 'admin_modal';
		$arrayDir = explode('/', $this->data['Company']['imagePath']);
		$image = array_pop($arrayDir);
		$this->JqImgcrop->cropImage(
			250, 
			$this->data['Company']['x1'], 
			$this->data['Company']['y1'],
			$this->data['Company']['x2'],
			$this->data['Company']['y2'],
			$this->data['Company']['w'],
			$this->data['Company']['h'],
			$this->data['Company']['imagePath'],
			$this->data['Company']['imagePath']);
		$company = $this->Company->findById($this->data['Company']['id']);
		// first delete the old image
		if($company['Company']['photo_path'] != '') {
			$this->JqImgcrop->deleteImage($company['Company']['photo_path'], "uploads/companies/photos");
		}
		$company['Company']['photo_path'] = $image;
		$this->Company->set($company['Company']);
		$this->Company->save();
		$this->set(compact('company'));
	}
}