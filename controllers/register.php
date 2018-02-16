<?php

class Register extends Controller {

	function __construct() {
		parent::__construct();	
	}
	
	function index() 
	{
	    $this->view->title = 'maladies a declaration obligatoire';
		$this->view->render('register/index');
	}
	
    function Registerrun()
	{
		$data = array();
		$data['wilaya']   = $_POST['wilaya'];
		$data['structure']   = $_POST['structure'];
		$data['login']     = $_POST['login'];
		$data['password']  = $_POST['password'];
		$this->model->runRegister($data); 	
	}
}