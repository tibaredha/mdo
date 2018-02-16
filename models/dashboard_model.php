<?php
class Dashboard_Model extends Model {

	public function __construct() {
		parent::__construct();
	}
	
	
	 public function userSave($data) {
        $postData = array(
            'wilaya' => $data['wilaya'],
            'structure' => $data['structure'],
            'login' => $data['login'],
			'password' => $data['password']
        );
		echo '<pre>';print_r ($postData);echo '<pre>'; 
        $this->db->update('users', $postData, "id =" . $data['id'] . "");
    }
	
	
	
	 
	 
	 
	
	 
	 public function listemedecin($id) {
        return $this->db->select('SELECT * FROM medecindeces WHERE structure = :id order by Nom   ', array(':id' => $id));
		// return $this->db->select('SELECT * FROM medecindeces ');
     } 
	 
	 
	 /*CRUD*/
	 
	 public function createmdo($data) {
		$this->db->insert('mdo1', array(
		'DATEMDO'    => $this->dateFR2US($data['DATEMDO']),
		'NOM'        => $data['NOM'],	
		'PRENOM'     => $data['PRENOM'],	
		'AGE'        => $data['AGE'],	
		'SEXE'       => $data['SEXE'],	
		'WILAYAR'    => $data['WILAYAR'],
        'COMMUNER'   => $data['COMMUNER'],	
		'ADRESSE'    => $data['ADRESSE'],	
		'MDO'        => $data['MDO'],	
        'STRUCTURE'  => $data['STRUCTURE'],
		'OBSERVATION'=> $data['OBSERVATION']		
        ));
        //echo '<pre>';print_r ($data);echo '<pre>';
		return $last_id = $this->db->lastInsertId();
    }
	
	public function userSearch($o, $q, $p, $l) {
	$structure = Session::get("structure");
    return $this->db->select("SELECT * FROM mdo1 where STRUCTURE=$structure and $o like '$q%' order by NOM limit $p,$l  ");//DATEMDO,
    }
    public function userSearch1($o, $q) {
        $structure = Session::get("structure");
		return $this->db->select("SELECT * FROM mdo1 where STRUCTURE=$structure and $o like '$q%' order by NOM ");//DATEMDO,
    }
	
    public function userSingleList($id) {
        return $this->db->select('SELECT * FROM mdo1 WHERE id = :id', array(':id' => $id));
     }


   public function editSave($data) {
		$postData = array(
		'DATEMDO'    => $this->dateFR2US($data['DATEMDO']),
		'NOM'        => $data['NOM'],	
		'PRENOM'     => $data['PRENOM'],	
		'AGE'        => $data['AGE'],	
		'SEXE'       => $data['SEXE'],	
		'WILAYAR'    => $data['WILAYAR'],
        'COMMUNER'   => $data['COMMUNER'],	
		'ADRESSE'    => $data['ADRESSE'],	
		'MDO'        => $data['MDO'],	
        'STRUCTURE'  => $data['STRUCTURE'],
		'OBSERVATION'=> $data['OBSERVATION']		
        );
        //echo '<pre>';print_r ($postData);echo '<pre>'; 
        $this->db->update('mdo1', $postData, "id =" . $data['id'] . "");
		return $last_id = $data['id'];
    }





	
	public function deletemdo($id) {       
        $this->db->delete('mdo1', "id = '$id'");
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	 public function medecinSave($data) {
	
	$this->db->insert('medecindeces', array(
			'Nom'       => $data['Nom'],
            'Prenom'    => $data['Prenom'],
            'wilaya'    => $data['wilaya'],
			'structure' => $data['structure']
	
	 ));
        echo '<pre>';print_r ($data);echo '<pre>';
		return $last_id = $this->db->lastInsertId();
    }
	
	public function deletemedecin($id) {       
        $this->db->delete('medecindeces', "id = '$id'");
    }
	
	//********************************************************************************//
	function dateUS2FR($date)//2013-01-01
    {
	$J      = substr($date,8,2);
    $M      = substr($date,5,2);
    $A      = substr($date,0,4);
	$dateUS2FR =  $J."-".$M."-".$A ;
    return $dateUS2FR;//01-01-2013
    }
	function dateFR2US($date)//01/01/2013
	{
	$J      = substr($date,0,2);
    $M      = substr($date,3,2);
    $A      = substr($date,6,4);
	$dateFR2US =  $A."-".$M."-".$J ;
    return $dateFR2US;//2013-01-01
	}
	function CalculateTimestampFromCurrDatetime($DateTime = -1) 
    {

	if ($DateTime == -1 || $DateTime == '' || $DateTime == '0000-00-00 00:00:00' || $DateTime == '0000-00-00') 
	{
		$DateTime = date("Y-m-d H:i:s");
	}

	$NewDate['year']   = substr($DateTime,0,4);
	$NewDate['month']  = substr($DateTime,5,2);
	$NewDate['day']    = substr($DateTime,8,2);
	$NewDate['hour']   = substr($DateTime,11,2);
	$NewDate['minute'] = substr($DateTime,14,2);
	$NewDate['second'] = substr($DateTime,17,2);

	return mktime($NewDate['hour'], $NewDate['minute'], $NewDate['second'], $NewDate['month'], $NewDate['day'], $NewDate['year']);
   }

	// calculate date difference
	function CalculateDateDifference($TimestampFirst, $TimestampSecond = -1)	
	{
		if ($TimestampSecond == -1) 
		{
			$TimestampSecond = CalculateTimestampFromCurrDatetime();
		}

		if ($TimestampSecond < $TimestampFirst) 
		{
			$TempTimestamp = $TimestampFirst;
			$TimestampFirst = $TimestampSecond;
			$TimestampSecond = $TempTimestamp;
			
			$NegativeDifference = true;
		}
		else 
		{
			$NegativeDifference = false;
		}

		list($Year1, $Month1, $Day1) = explode('-', date('Y-m-d', $TimestampFirst));
		list($Year2, $Month2, $Day2) = explode('-', date('Y-m-d', $TimestampSecond));
		$Time1 = (date('H',$TimestampFirst)*3600) + (date('i',$TimestampFirst)*60) + (date('s',$TimestampFirst));
		$Time2 = (date('H',$TimestampSecond)*3600) + (date('i',$TimestampSecond)*60) + (date('s',$TimestampSecond));

		$YearDiff = $Year2 - $Year1;
		$MonthDiff = ($Year2 * 12 + $Month2) - ($Year1 * 12 + $Month1);

		if($Month1 > $Month2)
		{
			$YearDiff -= 1;
		}
		elseif($Month1 == $Month2)
		{
			if($Day1 > $Day2) 
			{
				$YearDiff -= 1;
			}
			elseif($Day1 == $Day2) 
			{
				if($Time1 > $Time2) 
				{
					$YearDiff -= 1;
				}
			}
		}

		// handle over flow of month difference
		if($Day1 > $Day2) 
		{
			$MonthDiff -= 1;
		}
		elseif($Day1 == $Day2) 
		{
			if($Time1 > $Time2) 
			{
				$MonthDiff -= 1;
			}
		}

		$DateDifference = Array();
		$TotalSeconds = $TimestampSecond - $TimestampFirst;

		$WeekDiff = floor(($TotalSeconds / 604800));
		$DayDiff = floor(($TotalSeconds / 86400));

		if ($NegativeDifference == true) 
		{
			$DateDifference['years'] = ($YearDiff == 0) ? $YearDiff : -($YearDiff);
			$DateDifference['months'] = ($MonthDiff == 0) ? $MonthDiff : -($MonthDiff);
			$DateDifference['weeks'] = ($WeekDiff == 0) ? $WeekDiff : -($WeekDiff);
			$DateDifference['days'] = ($DayDiff == 0) ? $DayDiff : -($DayDiff);
		}
		else 
		{
			$DateDifference['years'] = $YearDiff;
			$DateDifference['months'] = $MonthDiff;
			$DateDifference['weeks'] = $WeekDiff;
			$DateDifference['days'] = $DayDiff;
		}
		
		return $DateDifference;
	}
	
	//*****************************************************************************************************//
	public function xhrInsert() 
	{
		$text = $_POST['text'];
		
		$this->db->insert('data', array('text' => $text));
		
		$data = array('text' => $text, 'id' => $this->db->lastInsertId());
		echo json_encode($data);
	}
	public function xhrGetListings()
	{
		$result = $this->db->select("SELECT * FROM data");
		echo json_encode($result);
	}
	public function xhrDeleteListing()
	{
		$id = (int) $_POST['id'];
		$this->db->delete('data', "id = '$id'");
	}
}