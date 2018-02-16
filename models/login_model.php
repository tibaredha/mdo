<?php

class Login_Model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function run()
	{
		$sth = $this->db->prepare("SELECT * FROM users WHERE 
				login = :login AND password = :password");
		$sth->execute(array(
			':login' => $_POST['login'],
			':password' => md5($_POST['password'])
		));
		//Hash::create('sha256', $_POST['password'], HASH_PASSWORD_KEY)
		$data = $sth->fetch();
		$count =  $sth->rowCount();
		if ($count > 0) {
			Session::init();
			
			Session::set('wilaya',$data['wilaya']);
			Session::set('structure',$data['structure']);
			Session::set('login',$data['login']);
			Session::set('role', $data['role']);
			Session::set('id', $data['id']);
			Session::set('loggedIn', true);
			header('location: ../dashboard');
		} else {
			header('location: ../login');
		}
		
	}
	
}