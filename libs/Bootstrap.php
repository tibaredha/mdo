<?php

class Bootstrap {

	function __construct() 
	{

		$url = isset($_GET['url']) ? $_GET['url'] : null;
		$url = rtrim($url, '/');
		$url = explode('/', $url);

		//print_r($url);
		// calling controller
		if (empty($url[0])) 
		{
			require 'controllers/index.php';
			$controller = new Index();
			$controller->index();
			return false;
		}
        //verifier si fichier exist
		$file = 'controllers/' . $url[0] . '.php';
		if (file_exists($file)) 
		{
			require $file;
			$controller = new $url[0];//instacier la class controlleur + appeler le model corespandant 
			$controller->loadModel($url[0]);
			if (isset($url[2]))      //calling methods
			{
				if (method_exists($controller, $url[1])) {
					$controller->{$url[1]}($url[2]); // avec parametre 
				} else {
					$this->error();
				}
			} 
			else 
			{
				if (isset($url[1])) {
					if (method_exists($controller, $url[1])) {
						$controller->{$url[1]}(); //sans parametre
					} else {
						$this->error();
					}
				} else {
					$controller->index();
				}
			}	
		} 
		else 
		{
			$this->error();
			return false;
		}	
	}
	
	function error() {
		require 'controllers/error.php';
		$controller = new Error();
		$controller->index();
		return false;
	}

}