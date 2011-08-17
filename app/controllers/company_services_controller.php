<?php
class ServiceCompaniesController extends AppController
{
	public $uses = array('CourseMembership');
	
	public function index() {
		$this->set('course_memberships_list', $this->CourseMembership->find('all'));
	}
	
	public function add() {
		
		if (! empty($this->data)) {
			
			if ($this->CourseMembership->saveAll(
				$this->data, array('validate' => 'first'))) {

				
				$this->redirect(array('action' => 'index'));
			}
		}
	}
}