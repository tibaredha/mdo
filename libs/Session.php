<?php

class Session
{
	
	public static function init()
	{
	
		// Lieu de la création de la session 
		// session_save_path('C:\tiba');
		// Nom de la session 
		// session_name('SessionPHP');
		// Création de la session 
	
	
	
		@session_start();
	}
	
	public static function set($key, $value)
	{
		$_SESSION[$key] = $value;
	}
	
	public static function get($key)
	{
		if (isset($_SESSION[$key]))
		return $_SESSION[$key];
	}
	
	public static function destroy()
	{
		//unset($_SESSION);
		session_destroy();
	}
	
}