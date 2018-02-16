<?php

class Dashboard extends Controller {
     
	public $controleur="dashboard";
	function __construct() {
		parent::__construct();
		Session::init();
		$logged = Session::get('loggedIn');
		if ($logged == false) {
			Session::destroy();
			header('location: ../login');
			exit;
		}
		$this->view->js = array('dashboard/js/default.js');	
	}
	function index() 
	{
	    $this->view->title = 'dashboard';
		$this->view->render($this->controleur.'/index');
	}
	function logout()
	{
		Session::destroy();
		header('location: ' . URL .  'login');
		exit;
	}
	
	
	/*CRUD*/
	function nouveau() 
	{
	    $this->view->title = 'dashboard';
		$this->view->render($this->controleur.'/nouveau');
	}
	
	public function create() 
	{
		$data = array();
		$data['DATEMDO']      = $_POST['DATEMDO'];
		$data['NOM']          = $_POST['NOM'];
		$data['PRENOM']       = $_POST['PRENOM'];
		$data['SEXE']         = $_POST['SEXE'];
		$data['AGE']          = $_POST['AGE'];
		$data['WILAYAR']      = $_POST['WILAYAR'];
		$data['COMMUNER']     = $_POST['COMMUNER'];
		$data['ADRESSE']      = $_POST['ADRESSE'];
		$data['MDO']          = $_POST['MDO'];
		$data['STRUCTURE']    = $_POST['STRUCTURE'];
		$data['OBSERVATION']  = $_POST['OBSERVATION'];
		echo '<pre>';print_r ($data);echo '<pre>';  
		$last_id=$this->model->createmdo($data);
		header('location: '.URL.$this->controleur.'/search/0/10?o=id&q='.$last_id);
	}
	
	function search()
	{
	    $url1 = explode('/',$_GET['url']);	
		$this->view->title = 'Search mdo';
	    $this->view->userListviewo = $_GET['o']; //criter de choix
	    $this->view->userListviewq = $_GET['q']; //key word  
		$this->view->userListviewp =$url1[2];    //parametre 2 page                     limit 2,3
		$this->view->userListviewl =10     ;     //parametre 3 nombre de ligne par page  limit 2,3 
		$this->view->userListviewb =15       ;   //parametre nombre de chiffre dan la barre  navigation
		$this->view->userListview = $this->model->userSearch($this->view->userListviewo,$this->view->userListviewq,$this->view->userListviewp,$this->view->userListviewl);
		$this->view->userListview1= $this->model->userSearch1($this->view->userListviewo,$this->view->userListviewq); // compte total pour bare de navigation
		$this->view->render($this->controleur.'/index');
	}
	
	public function edit($id) 
	{
        $this->view->title = 'editmdo';
		$this->view->user = $this->model->userSingleList($id);
		$this->view->render($this->controleur.'/edit');
	}
	
	public function editSave($id) 
	{
		$data = array();
		$data['DATEMDO']      = $_POST['DATEMDO'];
		$data['NOM']          = $_POST['NOM'];
		$data['PRENOM']       = $_POST['PRENOM'];
		$data['SEXE']         = $_POST['SEXE'];
		$data['AGE']          = $_POST['AGE'];
		$data['WILAYAR']      = $_POST['WILAYAR'];
		$data['COMMUNER']     = $_POST['COMMUNER'];
		$data['ADRESSE']      = $_POST['ADRESSE'];
		$data['MDO']          = $_POST['MDO'];
		$data['STRUCTURE']    = $_POST['STRUCTURE'];
		$data['OBSERVATION']  = $_POST['OBSERVATION'];
		$data['id']           = $id;
		//echo '<pre>';print_r ($data);echo '<pre>';  
		$last_id=$this->model->editSave($data);
		header('location: '.URL.$this->controleur.'/search/0/10?o=id&q='.$last_id);
	}
	
	
	
	public function delete($id)
	{
	$this->model->deletemdo($id);    
	header('location: ' . URL .$this->controleur. '/search/0/10?o=NOM&q=');
	}
	
	//*FIN CRUD*//
	
	function Update() 
	{
	    $this->view->title = 'Demographie';
		$this->view->render($this->controleur.'/Update');
	}
	
	
	
	
	
	
	
	function Demographie() 
	{
	    $this->view->title = 'Demographie';
		$this->view->render($this->controleur.'/Demographie');
	}
	
	
	function dump() 
	{
	    $this->view->title = 'dump';
		$this->view->render($this->controleur.'/dump');
	}
	function XLS() 
	{
	    $this->view->title = 'XLS';
		$this->view->render($this->controleur.'/XLS');
	}
	function user() 
	{
	    $this->view->title = 'user';
		$this->view->render($this->controleur.'/user');
	}
	function userSave($id) 
	{
	
	    $data = array();
		$data['id']         = $id;
		$data['login']      = $_POST['login'];
		$data['password']   = md5($_POST['password']);
		$data['wilaya']   = $_POST['wilaya'];
		$data['structure']   = $_POST['structure'];
		// echo '<pre>';print_r ($data);echo '<pre>';
		$this->model->userSave($data);
		header('location: ' . URL .$this->controleur."/logout");
	}
	
	
	
	
	function Evaluation($id) 
	{	
	    $this->view->title = 'Evaluation';
		if($id!=0) {
		$this->view->render($this->controleur.'/Evaluation'.$id);
		} else {
		$this->view->render($this->controleur.'/Evaluation');
		}	
		
	}
	function graphe($id) 
	{
	    $this->view->title = 'dashboard';
		if($id!=0) {
		$this->view->render($this->controleur.'/index'.$id);
		} else {
		$this->view->render($this->controleur.'/index');
		}	
	}
	
	
	
	//**************************************************************************************************//
	
	
	
	
	
	
	
	
	function createmedecin($id) 
	{
	    $this->view->title = 'dashboard';
		$this->view->userListview = $this->model->listemedecin($id) ;
		$this->view->render($this->controleur.'/createmedecin');
	}
	
	
	public function medecinSave()
	{
		$data = array();
		$data['Nom']        = $_POST['Nom'];
		$data['Prenom']     = $_POST['Prenom'];
		$data['wilaya']     = $_POST['wilaya'];
	    $data['structure']  = $_POST['structure'];
	   echo '<pre>';print_r ($data);echo '<pre>';
	   $this->model->medecinSave($data);
		header('location: ' . URL .$this->controleur. '/nouveau');
	}	
	public function deletemedecin($id)
	{
	$this->model->deletemedecin($id);    
	header('location: ' . URL .$this->controleur. '/createmedecin/1');
	}	
	function decesmaternel($id) 
	{
	    $this->view->title = 'decesmaternel';
		$this->view->user = $this->model->userSingleList($id);
		$this->view->render($this->controleur.'/decesmaternel');
	}	
	function decesperinatal($id) 
	{
	    $this->view->title = 'decesperinatal';
		$this->view->user = $this->model->userSingleList($id);
		$this->view->render($this->controleur.'/decesperinatal');
	}	
	//**************************************************************************************************//
	
	function liste() 
	{
    $this->view->title = 'liste ';
	$this->view->userListview = $this->model->liste() ;
	$this->view->render('dashboard/liste');
	}
	public function view($id) 
	{
        $this->view->title = 'view';
		$this->view->user =$this->model->userSingleList($id);
		$this->view->render($this->controleur.'/view');
	}
	
	
	// public function delete($id)
	// {
	// $this->model->delete($id);       
	// unlink('C:/wamp/www/cheval/public/images/'.$id.'.jpg');
	// header('location: ' . URL .$this->controleur.'/search/0/10?o=NomP&q=');
	// }
	
	function SIG($id) 
	{	
	    $this->view->title = 'Systeme Information Geographique';
		switch ($id) 
		{ 
			case 17: 
				$this->view->render($this->controleur.'/djelfacom');
			break;
			
            case 14: 
				$this->view->render($this->controleur.'/tiaret');
			break;
			

			
			default:
				$this->view->render($this->controleur.'/djelfacom');
		}	
	}
	function SIGA() 
	{	
	    $this->view->title = 'Systeme Information Geographique';
		// $id='17000';
	    // $this->view->userListview = $this->model->dnrcommune($id) ;
		//$this->view->render('dnr/ALGERIE');
		$this->view->render($this->controleur.'/algerie');
	}
	
	
	
	//***************************************************************************************************************************//
	function ordonnacednr($id) 
	{	
	    $this->view->title = 'ordonnacednr';
		$this->view->user =$this->model->userSingleList($id);
		$this->view->render($this->controleur.'/ordonnacednr');
	}
	
	function creationPanier(){
	   if (!isset($_SESSION['ordonnace'])){
		  $_SESSION['ordonnace']=array();
		  $_SESSION['ordonnace']['libelleProduit']    = array();
		  $_SESSION['ordonnace']['doseparprise']      = array();
		  $_SESSION['ordonnace']['nbrdrfoisparjours'] = array();
		  $_SESSION['ordonnace']['nbrdejours']        = array();
		  $_SESSION['ordonnace']['totaltrt']          = array();
		  $_SESSION['ordonnace']['qteProduit']        = array();
		  $_SESSION['ordonnace']['prixProduit']       = array();
		  $_SESSION['ordonnace']['verrou']            = false;
	   }
	   return true;
	}
	function isVerrouille(){
	   if (isset($_SESSION['ordonnace']) && $_SESSION['ordonnace']['verrou'])
	   return true;
	   else
	   return false;
	}
	function ajouterArticle()
	{   
	    $libelleProduit=$_POST['libelleProduit'];
		$doseparprise=$_POST['doseparprise'];
		$nbrdrfoisparjours=$_POST['nbrdrfoisparjours'];
		$nbrdejours=$_POST['nbrdejours'];
		$qteProduit=$_POST['qteProduit'];
		$prixProduit=$_POST['prixProduit'];
		$totaltrt=$doseparprise*$nbrdrfoisparjours*$nbrdejours; 	
		session_start();
		   if ($this->creationPanier() && !$this->isVerrouille())
		   {
		   $positionProduit = array_search($libelleProduit,$_SESSION['ordonnace']['libelleProduit']);
			  if ($positionProduit !== false)
			  {
				 header('location:'.URL.$this->controleur.'/ordonnacednr/'.$_POST['id']);
			  }
			  else
			  {
				 array_push( $_SESSION['ordonnace']['libelleProduit'],$libelleProduit);
				 array_push( $_SESSION['ordonnace']['doseparprise'],$doseparprise);
				 array_push( $_SESSION['ordonnace']['nbrdrfoisparjours'],$nbrdrfoisparjours);
				 array_push( $_SESSION['ordonnace']['nbrdejours'],$nbrdejours);
				 array_push( $_SESSION['ordonnace']['qteProduit'],$qteProduit);
				 array_push( $_SESSION['ordonnace']['prixProduit'],$prixProduit);
				 array_push( $_SESSION['ordonnace']['totaltrt'],$totaltrt);
			  }			      
		   }
	header('location:'.URL.$this->controleur.'/ordonnacednr/'.$_POST['id']);	  
	}
	function modifierQTeArticle($libelleProduit,$qteProduit)
	{
		session_start();
		if ($this->creationPanier() && !$this->isVerrouille())
		{
			if ($qteProduit > 0)
			{
				$positionProduit = array_search($libelleProduit,  $_SESSION['panier']['libelleProduit']);
				if ($positionProduit !== false)
				{
				$_SESSION['panier']['qteProduit'][$positionProduit] = $qteProduit ;
				}
				header('location: ' . URL .'pan/pan');  
			}
			else
			$this->supprimerArticle($libelleProduit);
		}	
	}
	function supprimerArticle($libelleProduit)
	{
	$url1 = explode('/',$_GET['url']);	
		session_start();
		if ($this->creationPanier() && !$this->isVerrouille())
		{
			$tmp=array();
			$tmp['libelleProduit']    = array();
			$tmp['doseparprise']      = array();
			$tmp['nbrdrfoisparjours'] = array();
			$tmp['nbrdejours']        = array();
			$tmp['totaltrt']          = array();
			$tmp['qteProduit']        = array();
			$tmp['prixProduit']       = array();
			$tmp['verrou'] = $_SESSION['ordonnace']['verrou'];
			for($i = 0; $i < count($_SESSION['ordonnace']['libelleProduit']); $i++)
			{
				if ($_SESSION['ordonnace']['libelleProduit'][$i] !== $libelleProduit)
				{
				array_push( $tmp['libelleProduit'],$_SESSION['ordonnace']['libelleProduit'][$i]);
				array_push( $tmp['doseparprise'],$_SESSION['ordonnace']['doseparprise'][$i]);
				array_push( $tmp['nbrdrfoisparjours'],$_SESSION['ordonnace']['nbrdrfoisparjours'][$i]);
				array_push( $tmp['nbrdejours'],$_SESSION['ordonnace']['nbrdejours'][$i]);
				array_push( $tmp['totaltrt'],$_SESSION['ordonnace']['totaltrt'][$i]);
				array_push( $tmp['qteProduit'],$_SESSION['ordonnace']['qteProduit'][$i]);
				array_push( $tmp['prixProduit'],$_SESSION['ordonnace']['prixProduit'][$i]);
				}
			}
			$_SESSION['ordonnace'] =  $tmp;
			unset($tmp);
			header('location: ' . URL .$this->controleur.'/ordonnacednr/'.$url1[3]); 
		}
	}	
	function supprimePanier(){
	 $url1 = explode('/',$_GET['url']);	
	 session_start();unset($_SESSION['ordonnace']);
     header('location: '.URL.$this->controleur.'/ordonnacednr/'.$url1[2]); 
	}
	function compterArticles()
	{
		if (isset($_SESSION['ordonnace']))
		return count($_SESSION['ordonnace']['libelleProduit']);
		else
		return 0;
	}
	function MontantGlobal(){
		$total=0;
		for($i = 0; $i < count($_SESSION['ordonnace']['libelleProduit']); $i++)
		{
			$total += $_SESSION['ordonnace']['qteProduit'][$i] * $_SESSION['ordonnace']['prixProduit'][$i];
		}
		return $total;
	}
	function miseajour(){
		session_start();
		for ($i = 0 ; $i < count($_POST['q']) ; $i++)
		{
			$this->modifierQTeArticle($_SESSION['panier']['libelleProduit'][$i],$_POST['q'][$i]);
		}    
	}	
	//***fin ordonnace dnr ***//
	
	 //**CHANGER PHOTOS**//
	function upl() 
	{
	$this->view->title = 'upload';
	$this->view->render($this->controleur.'/upl');    
	}
	
	function upl1($id) 
	{
		$this->view->title = 'upload';
		if (isset($_POST))
		{
		
			if (isset($_FILES))
			{
				//upload_max_filesize=10M   A MODIFIER DANS PHP.INI
				// $uploadLocation = "d:\\mvc/public/webcam/pat/"; 
				
				
				$uploadLocation = "C:\\wamp/www/cheval/public/images/cheval/";
				
				$target_path = $uploadLocation.trim($id).".jpg";      
				if(move_uploaded_file($_FILES['upfile']['tmp_name'], $target_path)) 
				{	
				$this->view->msg ='le fichier :  '.basename( $_FILES['upfile']['name']).'  a été corectement envoyer merci';
				} 
				else
				{
				$this->view->msg ='il ya une erreur d\'envoie du fichier :  '.basename( $_FILES['upfile']['name']).'  veillez recomencer svp';	
				}
			}	
		}
		header('location: ' . URL .$this->controleur.'/upl/'.$id.'');
		
		   
	}		
	
	//**fin CHANGER PHOTOS**//
	
	
	
	
	//***************************************************************************//
	
	function xhrInsert()
	{
		$this->model->xhrInsert();
	}
	
	function xhrGetListings()
	{
		$this->model->xhrGetListings();
	}
	
	function xhrDeleteListing()
	{
		$this->model->xhrDeleteListing();
	}
	
}