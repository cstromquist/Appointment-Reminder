<?php
class FaultsController extends AppController {

	var $name = 'Faults';
	var $useTable = false;
	
	function admin_error($error = null) {
		switch($error) {
			case 'activation':
				$main = 'Your Account Needs To Be Activated';
				$message = 'Please be sure to check your email and click on the link to activate your account before logging in.';
				break;
			case 'subscription':
				$main = 'Subsription Error';
				$message = 'Your subscription has ended.  Please contact a site administrator to reactivate your account.';
				break;
			default:
				$main = 'Error';
				$message = 'There has been an error.  Please try again.';
				break;
		}
		$this->set(compact('main', 'message'));
	}
}
?>