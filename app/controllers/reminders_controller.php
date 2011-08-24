<?php
class RemindersController extends AppController {
	
	public $helpers = array('List', 'Time', 'Text', 'Session', 'Form', 'Ajax');
	public $pageTitle = 'Reminders';
	public $uses = array('Company', 'Reminder', 'Technician', 'Service');
	public $components = array('Email');
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->Access->checkCompanyStatus();
	}
	
	function admin_index() {
        $reminders = $this->Reminder->findAll(array('Reminder.company_id' => $this->Auth->user('company_id')));
		$this->set(compact('reminders'));
	}
	
	/**
     * Admin reminder search
     *
     * @param string $query Search term, encoded by Javascript's encodeURI()
     */
    function admin_search($query = '') {
        $query = urldecode($query);
        $reminderResults = ClassRegistry::init('Reminder')->search($query);
        $results = am($reminderResults);
        $this->set('results', $results);
        if ($this->RequestHandler->isAjax()) {
            $this->render('../reminders/wf_search');
        }
    }
	
	function admin_email_details($id = null) {
		if (!empty($this->data) && $this->data['Reminder']['form_email_details']) { 
            // We don't do any real saving, we just validate the model 
            if ($this->Reminder->create($this->data) && $this->Reminder->validates()) { 
                $this->set('valid', true);
            } else {
            	$this->set('valid', false);
				$this->Session->setFlash('Please correct the errors below.', 'default', array('class' => 'flash-error'));
            }
        } elseif(empty($this->data) && $id == null) {
        	//something is definitely wrong. we don't have a data post and no id. send 'em back!
        	$this->Session->setFlash('Something went wrong.  Please try again.', 'default', array('class' => 'flash-error'));
			$this->redirect(array('action' => 'index'));
        } else {
			$technician_id = $this->data['Reminder']['technician_id'];
			$company = $this->Company->findById($this->Auth->user('company_id'));
			if($id == null) {
				$this->data['Reminder']['from_time'] = "07:00:00";
				$this->data['Reminder']['to_time'] = "17:00:00";
				$this->data['Reminder']['service_date'] = date('Y-m-d', time());
				
			} else {
				$this->data = $this->Reminder->findById($id);
				// the reason we need to set the technician id here is because of the case where the user chooses to change a technician on an existing
				// reminder.  Since we have the id as a hidden field in the form which is changed via javascript, we don't want to reloading $this->data
				// will overwrite are current data post
				if(null != $technician_id)
					$this->data['Reminder']['technician_id'] = $technician_id;
				$this->data['Reminder']['service_date'] = date('Y-m-d', strtotime($this->data['Reminder']['service_date_from']));
				$this->data['Reminder']['from_time'] = date('h:i:s', strtotime($this->data['Reminder']['service_date_from']));
				$this->data['Reminder']['to_time'] = date('h:i:s', strtotime($this->data['Reminder']['service_date_to']));
			}
		}
		$theme = $this->data['Reminder']['theme'];
		$technician = $this->Technician->findById($this->data['Reminder']['technician_id']);
		$CompanyService =& ClassRegistry::init('CompanyService'); 
		$companyserviceslist = $CompanyService->find('list', array(
															'conditions' => array('CompanyService.company_id' => $this->Auth->user('company_id')),
															'fields' => array('CompanyService.id', 'CompanyService.name'), 
															'order' => array('CompanyService.name' => 'asc')
															)
													);
		$companyservices = $CompanyService->find('all',array(
															'conditions' => array('CompanyService.company_id' => $this->Auth->user('company_id')),
															'order' => array('CompanyService.name' => 'asc') 
															)
												);
		$company = $this->Company->findById($this->Auth->user('company_id'));
		$this->set(compact('theme', 'technician', 'companyserviceslist', 'companyservices', 'company'));
	}
	
	function admin_select_theme($theme = null, $id = null) {
		$this->data['Reminder']['id'] = $id;
		$this->set(compact('theme', 'id'));
	}
	
	/**
	 * Allow user to select a technician
	 * 
	 * @param string $theme Theme name
	 * @param int $technician_id Technician id
	 * @param int $id Reminder id
	 */
	
	function admin_select_technician($theme = null, $technician_id = null, $id = null) {
		if($this->data['Reminder']['theme'] == '' && $theme == null) {
			$this->Session->setFlash('Please be sure to choose a theme.');
			$this->redirect(array('action' => 'select_theme'));
		} elseif($this->data['Reminder']['theme']) {
			$theme = $this->data['Reminder']['theme'];
		}
		$this->data['Reminder']['id'] = $id;
		$this->data['Reminder']['technician_id'] = $technician_id;
		if($technician_id) {
			$technician = $this->Technician->findById($this->data['Reminder']['technician_id']);
		}
		$technicians = $this->Technician->find('all', array('conditions' => array('Technician.company_id' => $this->Auth->user('company_id'))));
		$company = $this->Company->findById($this->Auth->user('company_id'));
		$this->set(compact('technicians', 'theme', 'company'));
	}
	
	/**
     * Send the email, then send them to the index screen 
     *
	 *
     */
	function admin_email_send() {
		$this->data['Reminder']['company_id'] = $this->Auth->user('company_id');
		
		$time = $this->get_service_dates($this->data);
		
		$this->data['Reminder']['service_date_from'] = $time['from'];
		$this->data['Reminder']['service_date_to'] = $time['to'];;
		
		// create the reminder
		if(!$this->data['Reminder']['id']) {
			$this->Reminder->create($this->data);
		}
		
		if($this->data['Reminder']['technician_id'] == '') {
			$this->data['Reminder']['technician_id'] = 0;
		}
		
		// generate the image (file based)
		$image_name = $this->generate_image($this->data, 'FILE');
		
		// set the image before sending email
		$this->data['Reminder']['image_path'] = $image_name;

		// now do the email!
		$this->send_email($this->data);
		
		$this->data['Reminder']['sent'] = 1;
		$this->Reminder->save($this->data);
		
		$this->Session->setFlash('Your reminder has been sent!', 'default', array('class' => 'flash-success'));
		$this->redirect(array('action' => 'index'));
	}
	
	/*
	 * Send the email reminder to the customer! 
	 * 
	 */
	function send_email($reminder) {
		$reminder = $this->Reminder->findById($reminder['Reminder']['id']);
		$company = $this->Company->findById($this->Auth->user('company_id'));
		$companyurl = $company['Company']['website_url'];
		
		$settings = Configure::read('AppSettings');
		
		$this->Email->from    = sprintf('%s <%s>', $company['Company']['name'], $settings['from_email']);
		$this->Email->to      = sprintf('%s %s <%s>', $reminder['Reminder']['fname'], $reminder['Reminder']['lname'], $reminder['Reminder']['email']);
		$this->Email->bcc	  = array($settings['contact_email']);
		$this->Email->replyTo = sprintf('%s <%s>', $company['Company']['name'], $company['Company']['email']);
		$this->Email->subject = 'Your '. $reminder['CompanyService']['name'] . ' Service Appointment Reminder';
		$this->Email->template = 'reminder';
		$this->Email->sendAs = 'both';
		
		$time = $this->get_service_dates($reminder);
		$from_date = strtotime($time['from']);
		$to_date = strtotime($time['to']);
		$date = date("M d, Y", $from_date);
		$from_time = date('h:i A', $from_date);
		$to_time = date('h:i A', $to_date);
		
		$technician = $this->Technician->findById($reminder['Reminder']['technician_id']);
		
		$reminder['Reminder']['date'] = $date;
		$reminder['Reminder']['from_time'] = $from_time;
		$reminder['Reminder']['to_time'] = $to_time;
		$reminder['Reminder']['tech'] = $technician['Technician']['name'];
		$reminder['Reminder']['tech_bio'] = $technician['Technician']['bio'];
		
		$this->set(compact('reminder', 'company', 'companyurl'));
		
		$this->Email->send();
	}
	
	function admin_email_preview() {
		// check to make sure data is good
		if(!$this->data['Reminder']['service_message']) {
			$this->Session->setFlash('Please enter your service message below.', 'default', array('class' => 'flash-error'));
			$this->redirect(array('action' => 'email_details', $this->data['Reminder']['id']));
			
		}
	}
	
	function admin_image_generate() {
		$this->generate_image($this->data, 'INLINE');
	}

	/*
	 * Gengerate the image
	 * 
	 */
	function generate_image($reminder, $return_mode = "") {
		App::import('Vendor', 'igenclass', array('file' =>'igen'.DS.'igen.class.php'));
		
		$company = $this->Company->findById($this->Auth->user('company_id'));
		$technician = $this->Technician->findById($reminder['Reminder']['technician_id']);
		
		$array = explode("\n", $reminder['Reminder']['features_benefits']);
		foreach($array as $key => $val) {
			$benefits[] = $val;
		}		

		$array = explode("\n", $reminder['Reminder']['other_services']);
		foreach($array as $key => $val) {
			$services[] = $val;
		}
		
		$array = explode("\n", $reminder['Reminder']['services']);
		foreach($array as $key => $val) {
			$upsell[] = $val;
		}
		
		// set vars
		$time = $this->get_service_dates($reminder);
		$from_date = strtotime($time['from']);
		$to_date = strtotime($time['to']);
		$first_name = $this->data['Reminder']['fname'];
		$company_name = $company['Company']['name'];
		$date = date("M d, Y", $from_date);
		$from_time = date('h:i A', $from_date);
		$to_time = date('h:i A', $to_date);
		
		$ig = new imageGenerator();
		
		if($technician) {
			$ig->setFormat('tech');
			$ig->setUpsellTitle('As Long As I\'m Here');
		} else {
			$ig->setFormat('group');
			$ig->setUpsellTitle('As Long As We\'re Here');
		}
		$ig->setTheme($this->data['Reminder']['theme']);
		
		if($technician) {
			// check for technician photo
			if(!file_exists($_SERVER['DOCUMENT_ROOT'] . $this->base . '/app/webroot/img/uploads/technicians/' . $technician['Technician']['image_path'])) {
				die('Tech photo does not exist. Location:' . $_SERVER['DOCUMENT_ROOT'] . $this->base . '/app/webroot/img/uploads/technicians/' . $technician['Technician']['image_path']);
			}
			$ig->setPhotoPath($_SERVER['DOCUMENT_ROOT'] . $this->base . '/app/webroot/img/uploads/technicians/' . $technician['Technician']['image_path']);
		} else {
			// check for company group photo
			if(!file_exists($_SERVER['DOCUMENT_ROOT'] . $this->base . '/app/webroot/img/uploads/companies/photos/big/' . $company['Company']['photo_path'])) {
				die('Company group photo does not exist');
			}
			$ig->setPhotoPath($_SERVER['DOCUMENT_ROOT'] . $this->base . '/app/webroot/img/uploads/companies/photos/big/' . $company['Company']['photo_path']);
		}
		// check for company logo
		if(!file_exists($_SERVER['DOCUMENT_ROOT'] . $this->base . '/app/webroot/img/uploads/companies/logos/big/' . $company['Company']['logo_path'])) {
			die('Company logo photo does not exist');
		}
		$ig->setLogoPath($_SERVER['DOCUMENT_ROOT'] . $this->base . '/app/webroot/img/uploads/companies/logos/big/' . $company['Company']['logo_path']); 

		$ig->setCustomerGreeting(sprintf('Dear %s,', $first_name));
		$ig->setCustomerMessage(sprintf('Thank you for using %s. %s', $company_name, $this->data['Reminder']['service_message']));
		
		$ig->setServiceDate($date);
		$ig->setServiceTime(sprintf('%s - %s', $from_time, $to_time));
		$ig->setTechName($technician['Technician']['name']);
		$ig->setTechBio($technician['Technician']['bio']);
		
		$ig->setBenefitsTitle('You\'ve Made A Great Decision!');
		$ig->setBenefitsList($benefits);
		$ig->setUpsellSubTitle('Consider taking advantage of our other services:');
		$ig->setUpsellList($upsell);
		$ig->setOtherServicesTitle('Are There Any Other Services You Would Like?');
		$ig->setOtherServicesList($services);
		$ig->setCancelMessage('If you are unable to keep this appointment, please call customer service at ' . $company['Company']['phone'] . ' to reschedule.');
		$ig->setCopyright('©Copyright 2011. Nexstar®, Inc. All rights reserved.');
		$ig->setMoreInfoMessage('For more information, please visit our website at');
		$ig->setWebsite($company['Company']['website_url']);
		$ig->setPhone($company['Company']['phone']);
		$ig->setUpsellListCols(2);
		
		$cc_array = explode(',', $company['Company']['payment_methods']);
		foreach($cc_array as $key=>$val) {
			if(preg_match('/visa/', strtolower($val))) {
				$ccs[] = 'visa';
			}
			if(preg_match('/disc/', strtolower($val))) {
				$ccs[] = 'disc';
			}
			if(preg_match('/mast/', strtolower($val))) {
				$ccs[] = 'mast';
			}
			if(preg_match('/amer|amex/', strtolower($val))) {
				$ccs[] = 'amex';
			}
		}
		
		$ig->setCreditCards($ccs);
		
		if($return_mode == 'FILE') {
			$ig->setReturnMode('FILE');
			//$ig->setFileName($reminder['Reminder']['id'] . ".jpg");
			$array = $ig->generateImage();
			
			// return the image name
			return $array[1];
		} else {
			$ig->setReturnMode('INLINE');
			$ig->generateImage();
		}
		
	}
	

	function get_service_dates($data) {
		// format times
		$from_min = $data['Reminder']['from_time']['min'] < 10 ? '0' . $data['Reminder']['from_time']['min'] : $data['Reminder']['from_time']['min'];
		$to_min = $data['Reminder']['to_time']['min'] < 10 ? '0' . $data['Reminder']['to_time']['min'] : $data['Reminder']['to_time']['min'];
		$from_hour = $data['Reminder']['from_time']['meridian'] == 'pm' ? $data['Reminder']['from_time']['hour'] + 12 : $data['Reminder']['from_time']['hour'];
		$from_hour = $from_hour == 24 ? '12' : $from_hour;
		$to_hour = $data['Reminder']['to_time']['meridian'] == 'pm' ? $data['Reminder']['to_time']['hour'] + 12 : $data['Reminder']['to_time']['hour'];
		$to_hour = $to_hour == 24 ? '12' : $to_hour;
		
		$return['from'] = sprintf('%s %s:%s:00', $data['Reminder']['service_date'], $from_hour, $from_min);
		$return['to'] = sprintf('%s %s:%s:00', $data['Reminder']['service_date'], $to_hour, $to_min);
		return $return;
	}
	
}
