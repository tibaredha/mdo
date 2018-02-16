<?php

class Login extends Controller {

	function __construct() {
		parent::__construct();	
	}
	
	function index() 
	{
	    $this->view->title = 'maladies a declaration obligatoire';
		$this->view->render('login/index');
	}
	
	function run()
	{
	$this->model->run();
	}
	

}