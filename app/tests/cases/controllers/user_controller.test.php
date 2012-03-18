<?php 
/* SVN FILE: $Id$ */
/* UserController Test cases generated on: 2011-08-06 21:28:29 : 1312684109*/
App::import('Controller', 'User');

class TestUser extends UserController {
	var $autoRender = false;
}

class UserControllerTest extends CakeTestCase {
	var $User = null;

	function startTest() {
		$this->User = new TestUser();
		$this->User->constructClasses();
	}

	function testUserControllerInstance() {
		$this->assertTrue(is_a($this->User, 'UserController'));
	}

	function endTest() {
		unset($this->User);
	}
}
?>