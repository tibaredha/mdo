<?PHP

// CREATE TABLE `session` (
// `sess_id` char(40) NOT NULL,
// `sess_datas` text NOT NULL,
// `sess_expire` bigint(20) NOT NULL,
// UNIQUE KEY `sess_id` (`sess_id`)
// ) ENGINE=MyISAM DEFAULT CHARSET=latin1;



class Session
{
	public $session_time = 7200;//2 heures
	public $session = array();
	private $db;
	
	public function __construct($sql_host, $sql_user, $sql_password, $sql_db)
	{
		$this->host     = $sql_host;
		$this->user     = $sql_user;
		$this->password = $sql_password;
		$this->dba      = $sql_db;
	}
	
	public function open ()//pour l'ouverture
	{
		$this->connect = mysql_connect($this->host, $this->user, $this>password,1);//onse connecte a la bdd
		$bdd = mysql_select_db($this->dba,$this->connect);                         //on sélectionne la basede données
		$this->gc();                                                               //on appelle la fonction gc
		return $bdd;                                                               //true ou false selon la réussite ou non de la connexion à labdd
	}
	public function read ($sid)//lecture
	{
		$sid   = mysql_real_escape_string($sid,$this->connect);
		$sql   = "SELECT sess_datas FROM sess_table WHERE sess_id = '$sid' ";
		$query = mysql_query($sql,$this->connect) or exit(mysql_error());
		$data  = mysql_fetch_array($query);
		if(empty($data)) return FALSE;
		else return $data['sess_datas'];                                           //on retourne la valeur de sess_datas
	}
	public function write ($sid, $data)//écriture
	{
		$expire = intval(time() + $this->session_time);//calcul de l'expiration de la session
		$data = mysql_real_escape_string($data,$this->connect);//si on veut stocker du code sql
		$sql = "SELECT COUNT(sess_id) AS total FROM ".SESS_TABLE." WHERE sess_id = '$sid' ";
		$query = mysql_query($sql,$this->connect) or exit(mysql_error());
		$return = mysql_fetch_array($query);
		if($return['total'] == 0)//si la session n'existe pas encore
		{
		$sql = "INSERT INTO ".SESS_TABLE."VALUES('$sid','$data','$expire')";//alors on la crée
		}
		else//sinon
		{
		$sql = "UPDATE ".SESS_TABLE."SET sess_datas = '$data',sess_expire = '$expire' WHERE sess_id = '$sid' ";//on la modifie
		}
		$query = mysql_query($sql,$this->connect) or exit(mysql_error());
		return $query;
	}
	public function close()      //fermeture
	{
	mysql_close($this->connect);//on ferme la bdd
	}
	public function destroy ($sid) //destruction
	{
	$sql = "DELETE FROM ".SESS_TABLE."WHERE sess_id = '$sid' ";//on supprime la session de la bdd
	$query = mysql_query($sql,$this->connect) or exit(mysql_error());
	return $query;
	}
	public function gc ()//nettoyage
	{
	$sql = "DELETE FROM ".SESS_TABLE."WHERE sess_expire < ".time(); //on supprime les vieilles sessions
	$query = mysql_query($sql,$this->connect) or exit(mysql_error());
	return $query;
	}
}
//fin de la classe
ini_set('session.save_handler', 'user');//on définit l'utilisation des sessions en personnel
$session = new Session($sql_host, $sql_user, $sql_password, $sql_db);//on déclare la classe
session_set_save_handler(array($session, 'open'),array($session, 'close'),array($session, 'read'),array($session, 'write'),array($session, 'destroy'),array($session, 'gc'));//on précise les méthodes àemployer pour les sessions
session_start();//on démarre la session

?>


