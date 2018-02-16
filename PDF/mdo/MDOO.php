<?php
require('../invoice.php');
class MDO extends PDF_Invoice
{ 
     public $nomprenom ="tibaredha";
	 public $db_host="localhost";
	 public $db_name="mdo";  
     public $db_user="root";
     public $db_pass="";
	 public $utf8 = "" ;
	
	function mysqlconnect()
	{
	$this->db_host;
	$this->db_name;
	$this->db_user;
	$this->db_pass;
    $cnx = mysql_connect($this->db_host,$this->db_user,$this->db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
    $db  = mysql_select_db($this->db_name,$cnx) ;
	mysql_query("SET NAMES 'UTF8' ");
	return $db;
	}
    
	
	
	
	function dateUS2FR($date)//2013-01-01
    {
	$J      = substr($date,8,2);
    $M      = substr($date,5,2);
    $A      = substr($date,0,4);
	$dateUS2FR =  $J."/".$M."/".$A ;
    return $dateUS2FR;//01/01/2013
    }
	function dateFR2US($date)//01/01/2013
	{
	$J      = substr($date,0,2);
    $M      = substr($date,3,2);
    $A      = substr($date,6,4);
	$dateFR2US =  $A."-".$M."-".$J ;
    return $dateFR2US;//2013-01-01
	}
	function datePlus($dateDo,$nbrJours)
	{
	$timeStamp = strtotime($dateDo); 
	$timeStamp += 24 * 60 * 60 * $nbrJours;
	$newDate = date("Y-m-d", $timeStamp);
	return  $newDate;
	}
	function nbrtostringv($tb_name,$colonename,$colonevalue,$resultatstring) 
	{
		if (is_numeric($colonevalue) and $colonevalue!=='-1') 
		{ 
		$this->mysqlconnect();
		// $db_host="localhost"; 
		// $db_name="cheval"; 
		// $db_user="root";
		// $db_pass="";
		// $cnx = mysql_connect($db_host,$db_user,$db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
		// $db  = mysql_select_db($db_name,$cnx) ;
		$result = mysql_query("SELECT * FROM $tb_name where $colonename=$colonevalue" );
		while ($row = mysql_fetch_array($result, MYSQL_BOTH)) {
        return $row[$resultatstring];
        }
		
		return $resultat2='??????';
		}
        else
        {
		return $resultat2='??????';
		}		
	}
	
	
	
	function nbrtostring($db_name,$tb_name,$colonename,$colonevalue,$resultatstring) 
	{
		
		$db_host="localhost"; 
		$db_user="root";
		$db_pass="";
		$cnx = mysql_connect($db_host,$db_user,$db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
		$db  = mysql_select_db($db_name,$cnx) ;
		mysql_query("SET NAMES 'UTF8' ");
		$result = mysql_query("SELECT * FROM $tb_name where $colonename=$colonevalue" );
		$row=mysql_fetch_object($result);
		$resultat=$row->$resultatstring;
		return $resultat;
		
	}
	function entetemdo($STRUCTURE,$titre,$DATEJOUR1,$DATEJOUR2)
    {
	$date=date("d-m-y");
	$this->SetDisplayMode('fullpage','single');//mode d affichage 
	$this->SetFillColor(230);    //fond gris il faut ajouter au cell un autre parametre pour qui accepte la coloration
	$this->SetTextColor(0,0,0);  //text noire
	$this->SetFont('Arial','B',9.5);
	$this->AddPage();$this->AliasNbPages();
	$this->Text(90,5,"REPUBLIQUE ALGERIENNE DEMOCRATIQUE ET POPULAIRE");
	$this->Text(70,10,"MINISTERE DE LA SANTE DE LA POPULATION ET DE LA REFORME HOSPITALIERE");
	$this->Text(75.5,15,"DIRECTION DE LA SANTE ET DE LA POPULAION DE LA WILAYA DE DJELFA");
	$this->Text(80,25,$titre);
	$this->Text(95,30,"RELEVE DES MALADIES A DECLARATION OBLIGATOIRE   ");
	$this->Text(115,35,'DU  '.$this->dateUS2FR($DATEJOUR1).'  AU  '.$this->dateUS2FR($DATEJOUR2));
	$this->Text(5,40,"ETABLISSEMENT PUBLIC : ".$this->nbrtostring('mdo','structure','id',$STRUCTURE,'structure'));  $this->Text(255,40,"DATE :  ".DATE('d-m-Y')); 
	$this->Text(5,45,"SERVICE:PREVENTION  ");     
	$this->Text(5,50,"CODE :17000");
	}
	function entetemdoP($STRUCTURE,$titre,$DATEJOUR1,$DATEJOUR2)
    {
	$date=date("d-m-y");
	$this->SetDisplayMode('fullpage','single');//mode d affichage 
	$this->SetFillColor(230);    //fond gris il faut ajouter au cell un autre parametre pour qui accepte la coloration
	$this->SetTextColor(0,0,0);  //text noire
	$this->SetFont('Arial','B',9.5);
	$this->AddPage();$this->AliasNbPages();
	$this->Text(90-30,5,"REPUBLIQUE ALGERIENNE DEMOCRATIQUE ET POPULAIRE");
	$this->Text(70-30,10,"MINISTERE DE LA SANTE DE LA POPULATION ET DE LA REFORME HOSPITALIERE");
	$this->Text(75.5-30,15,"DIRECTION DE LA SANTE ET DE LA POPULAION DE LA WILAYA DE DJELFA");
	$this->Text(80-30,25,$titre);
	$this->Text(95-30,30,"RELEVE DES MALADIES A DECLARATION OBLIGATOIRE   ");
	// $this->Text(115-30,35,'DU  '.$this->dateUS2FR($DATEJOUR1).'  AU  '.$this->dateUS2FR($DATEJOUR2));
	// $this->Text(5,40,"ETABLISSEMENT PUBLIC : ".$this->nbrtostring('mdo','structure','id',$STRUCTURE,'structure'));  $this->Text(255,40,"DATE :  ".DATE('d-m-Y')); 
	// $this->Text(5,45,"SERVICE:PREVENTION  ");     
	// $this->Text(5,50,"CODE :17000");
	}
	
	function piedmdo()
    {
	$this->SetFont('Arial','B',9.5);
	$this->SetXY(30,$this->GetY()-5);$this->cell(120,15," NOM ET QUALITE DU SIGNATAIRE",0,0,'C',0); 	  
	$this->SetXY(60,$this->GetY()); $this->cell(320,15," FAIT  LE : ".DATE('d-m-Y'),0,0,'C',0);		  
	$this->SetXY(30,$this->GetY()+4);$this->cell(120,15,"DR TIBA  ",0,0,'C',0); 	  
	$this->Output();	
	}
	function piedmdoP()
    {
	// $this->SetFont('Arial','B',9.5);
	// $this->SetXY(1,$this->GetY()-5);$this->cell(120,15," NOM ET QUALITE DU SIGNATAIRE",0,0,'C',0); 	  
	// $this->SetXY(30,$this->GetY()); $this->cell(320,15," FAIT  LE : ".DATE('d-m-Y'),0,0,'C',0);		  
	// $this->SetXY(1,$this->GetY()+4);$this->cell(120,15,"DR TIBA  ",0,0,'C',0); 	  
	$this->Output();	
	}
	function maladie($tab,$DATEJOUR1,$DATEJOUR2,$germe)
    {
	$query = "SELECT * from mdo1 where( DATEMDO BETWEEN '$DATEJOUR1' AND '$DATEJOUR2')";//
	$resultat=mysql_query($query);
	$totalmbr=mysql_num_rows($resultat);
	$this->SetXY(05,$this->GetY()+5); 
	while($row=mysql_fetch_object($resultat))
	  {
	 $this->SetXY(05,$this->GetY()+5); 
	 $this->cell(20,5,$row->id,1,0,'C',0);  
     $this->cell(20,5,$this->dateUS2FR(trim($row->DATEMDO)),1,0,'C',0);
	 $this->cell(60,5,trim($row->NOM).'_'.trim($row->PRENOM),1,0,'L',0);
     $this->cell(20,5,$row->AGE,1,0,'C',0);
	   if (Trim($row->SEXE)=='M')
			  {
			   $this->cell(5,5,'X',1,0,'L',0);
			   $this->cell(5,5,'',1,0,'L',0);
			  }
			  if (Trim($row->SEXE)=='F')
			  {
			   $this->cell(5,5,'',1,0,'L',0);
			   $this->cell(5,5,'X',1,0,'L',0);
			  } 
	 $this->SetFont('Arial','',8);
	 $this->cell(40,5,$row->ADRESSE,1,0,'L',0);$this->SetFont('Arial','',9.5);
	 $this->cell(80,5,$this->nbrtostringv('mdo','id',$row->MDO,'mdo') ,1,0,'L',0);
	 $this->cell(30,5,$row->OBSERVATION,1,0,'L',0);
	 }
	 $this->SetXY(05,$this->GetY()+10); 	  	   
    }

	function MDO2($STRUCTURE,$DATEJOUR1,$DATEJOUR2)
    {
	$this->entetemdo($STRUCTURE,"ANNEXE II - CIRCULAIRE N° 1126 /MSP/DP/SDPG... DU 17 NOVEMBER 1990",$DATEJOUR1,$DATEJOUR2);                                                                            
	$this->SetFont('Arial','',9.5);
	$this->SetXY(005,$this->GetY()+45);$this->cell(20,10,"N°",1,0,'C',1,0);
	$this->cell(20,10,"DATE",1,0,'C',1,0);
	$this->cell(60,10,"NOM ET PRENOM",1,0,'C',1,0);
	$this->cell(20,10,"AGE",1,0,'C',1,0);
	$this->cell(10,5,"SEXE",1,0,'C',1,0);
	$this->SetXY(125,$this->GetY()+5);$this->cell(5,5,"M",1,0,'C',1,0);
	$this->SetXY(130,$this->GetY());$this->cell(5,5,"F",1,0,'C',1,0);
	$this->SetXY(135,$this->GetY()-5);$this->cell(40,10,"ADRESSE",1,0,'C',1,0);
	$this->cell(80,10,"MALADIE",1,0,'C',1,0);
	$this->cell(30,10,"OBSERVATION",1,0,'C',1,0);
	$this->mysqlconnect();
    $this->maladie('',$DATEJOUR1,$DATEJOUR2,'');
	$this->piedmdo();	
	}
	function mdoiii($STRUCTURE,$mdo,$commune,$sexe,$datejour1,$datejour2,$age1,$age2) 
	{
	$this->mysqlconnect();
	$requete="SELECT * FROM mdo1 where 	STRUCTURE $STRUCTURE and  MDO='$mdo' and COMMUNER='$commune' and  SEXE='$sexe' and  (AGE BETWEEN '$age1' AND '$age2' and  (DATEMDO BETWEEN '$datejour1' AND '$datejour2')) ";//     
	$requete = @mysql_query($requete) or die($requete."<br>".mysql_error());
	$OP=mysql_num_rows($requete);
	mysql_free_result($requete);
	if ($OP==0){return $OP='';}else{return $OP;} 
	}
	
	function mdoiiit($STRUCTURE,$commune,$sexe,$datejour1,$datejour2,$age1,$age2) 
	{
	$this->mysqlconnect();
	$requete="SELECT * FROM mdo1 where 	STRUCTURE $STRUCTURE and COMMUNER='$commune' and  SEXE='$sexe' and  (AGE BETWEEN '$age1' AND '$age2' and  (DATEMDO BETWEEN '$datejour1' AND '$datejour2')) ";//     
	$requete = @mysql_query($requete) or die($requete."<br>".mysql_error());
	$OP=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $OP;
	}
	function entetemdoiiit($commune) 
	{
	$this->SetAutoPageBreak(false);
	$this->AddPage();
	$this->SetFont('Arial','',9.5);
	$this->SetXY(5,$this->GetY());
	$this->cell(46,10,html_entity_decode(utf8_decode("Nature De L'affection")),1,0,'L',1,0);
	$this->cell(26*8,5,"Tranche d'age et Sexe",1,0,'C',1,0);$this->cell(36,10,"Total",1,0,'C',1,0);
	$this->SetXY(5+46,$this->GetY()+5);
	$this->cell(26,5,"00-01",1,0,'C',1,0);
	$this->cell(26,5,"02-04",1,0,'C',1,0);
	$this->cell(26,5,"05-09",1,0,'C',1,0);
	$this->cell(26,5,"10-14",1,0,'C',1,0);
	$this->cell(26,5,"15-19",1,0,'C',1,0);
	$this->cell(26,5,"20-44",1,0,'C',1,0);
	$this->cell(26,5,"44-64",1,0,'C',1,0);
	$this->cell(26,5,"65-99",1,0,'C',1,0);
	$this->SetXY(5,$this->GetY()+5);
	$this->cell(46,5,html_entity_decode(utf8_decode("Commune : ".$commune)),1,0,'L',1,0);
	$this->cell(13,5,"M",1,0,'C',1,0);
	$this->cell(13,5,"F",1,0,'C',1,0);
	$this->cell(13,5,"M",1,0,'C',1,0);
	$this->cell(13,5,"F",1,0,'C',1,0);
	$this->cell(13,5,"M",1,0,'C',1,0);
	$this->cell(13,5,"F",1,0,'C',1,0);
	$this->cell(13,5,"M",1,0,'C',1,0);
	$this->cell(13,5,"F",1,0,'C',1,0);
	$this->cell(13,5,"M",1,0,'C',1,0);
	$this->cell(13,5,"F",1,0,'C',1,0);
	$this->cell(13,5,"M",1,0,'C',1,0);
	$this->cell(13,5,"F",1,0,'C',1,0);
	$this->cell(13,5,"M",1,0,'C',1,0);
	$this->cell(13,5,"F",1,0,'C',1,0);
	$this->cell(13,5,"M",1,0,'C',1,0);
	$this->cell(13,5,"F",1,0,'C',1,0);
	$this->cell(12,5,"M",1,0,'C',1,0);
	$this->cell(12,5,"F",1,0,'C',1,0);
	$this->cell(12,5,"T",1,0,'C',1,0);
	}
	
	function MDO3($STRUCTURE,$DATEJOUR1,$DATEJOUR2)
    {
	$this->entetemdo(substr($STRUCTURE,1,20),"ANNEXE III - CIRCULAIRE N° 1126 /MSP/DP/SDPG... DU 17 NOVEMBER 1990",$DATEJOUR1,$DATEJOUR2);                                                                            
	//********************************************************************************************//
	$this->mysqlconnect();
	$query = "SELECT * from COM where IDWIL='17000' and yes='1' order by COMMUNE   "; 
	$res=mysql_query($query);
	$tot=mysql_num_rows($res);
	$this->SetXY(5,$this->GetY()+5); 
	while($row=mysql_fetch_object($res))
	{
	    $this->entetemdoiiit($row->COMMUNE);
		//****************************************************************************************//
			$query1 = "SELECT * from mdo order by id "; 
			$this->SetXY(5,$this->GetY()+5); 
			$resultat1=mysql_query($query1);
			$totalmbr1=mysql_num_rows($resultat1);
			$this->SetXY(5,$this->GetY()); 
			while($row1=mysql_fetch_object($resultat1))
			{
			$this->SetFont('Arial','',6.5);
			$this->cell(46,5,$row1->mdo,1,0,'L',1,0);
			$this->SetFont('Arial','',9.5);
			$MDO=$row1->id;
			$COMMUNE=$row->IDCOM;
			$val1=$this->mdoiii($STRUCTURE,$MDO,$COMMUNE,'M',$DATEJOUR1,$DATEJOUR2,0,1);
			$val2=$this->mdoiii($STRUCTURE,$MDO,$COMMUNE,'F',$DATEJOUR1,$DATEJOUR2,0,1);
			$val3=$this->mdoiii($STRUCTURE,$MDO,$COMMUNE,'M',$DATEJOUR1,$DATEJOUR2,2,4);
			$val4=$this->mdoiii($STRUCTURE,$MDO,$COMMUNE,'F',$DATEJOUR1,$DATEJOUR2,2,4);
			$val5=$this->mdoiii($STRUCTURE,$MDO,$COMMUNE,'M',$DATEJOUR1,$DATEJOUR2,5,9);
			$val6=$this->mdoiii($STRUCTURE,$MDO,$COMMUNE,'F',$DATEJOUR1,$DATEJOUR2,5,9);
			$val7=$this->mdoiii($STRUCTURE,$MDO,$COMMUNE,'M',$DATEJOUR1,$DATEJOUR2,10,14);
			$val8=$this->mdoiii($STRUCTURE,$MDO,$COMMUNE,'F',$DATEJOUR1,$DATEJOUR2,10,14);
			$val9=$this->mdoiii($STRUCTURE,$MDO,$COMMUNE,'M',$DATEJOUR1,$DATEJOUR2,15,19);
		   $val10=$this->mdoiii($STRUCTURE,$MDO,$COMMUNE,'F',$DATEJOUR1,$DATEJOUR2,15,19);
		   $val11=$this->mdoiii($STRUCTURE,$MDO,$COMMUNE,'M',$DATEJOUR1,$DATEJOUR2,20,44);
		   $val12=$this->mdoiii($STRUCTURE,$MDO,$COMMUNE,'F',$DATEJOUR1,$DATEJOUR2,20,44);
		   $val13=$this->mdoiii($STRUCTURE,$MDO,$COMMUNE,'M',$DATEJOUR1,$DATEJOUR2,45,64);
		   $val14=$this->mdoiii($STRUCTURE,$MDO,$COMMUNE,'F',$DATEJOUR1,$DATEJOUR2,45,64);
		   $val15=$this->mdoiii($STRUCTURE,$MDO,$COMMUNE,'M',$DATEJOUR1,$DATEJOUR2,65,99);
		   $val16=$this->mdoiii($STRUCTURE,$MDO,$COMMUNE,'F',$DATEJOUR1,$DATEJOUR2,65,99);
			
			$this->cell(13,5,$val1,1,0,'C',0,0);
			$this->cell(13,5,$val2,1,0,'C',0,0);
			$this->cell(13,5,$val3,1,0,'C',0,0);
			$this->cell(13,5,$val4,1,0,'C',0,0);
			$this->cell(13,5,$val5,1,0,'C',0,0);
			$this->cell(13,5,$val6,1,0,'C',0,0);
			$this->cell(13,5,$val7,1,0,'C',0,0);
			$this->cell(13,5,$val8,1,0,'C',0,0);
			$this->cell(13,5,$val9,1,0,'C',0,0);
			$this->cell(13,5,$val10,1,0,'C',0,0);
			$this->cell(13,5,$val11,1,0,'C',0,0);
			$this->cell(13,5,$val12,1,0,'C',0,0);
			$this->cell(13,5,$val13,1,0,'C',0,0);
			$this->cell(13,5,$val14,1,0,'C',0,0);
			$this->cell(13,5,$val15,1,0,'C',0,0);
			$this->cell(13,5,$val16,1,0,'C',0,0);
			$this->cell(12,5,$val1+$val3+$val5+$val7+$val9+$val11+$val13+$val15,1,0,'C',1,0);
			$this->cell(12,5,$val2+$val4+$val6+$val8+$val10+$val12+$val14+$val16,1,0,'C',1,0);
			$this->cell(12,5,$val1+$val3+$val5+$val7+$val9+$val11+$val13+$val15+$val2+$val4+$val6+$val8+$val10+$val12+$val14+$val16,1,0,'C',1,0);
			$this->setxy(5,$this->gety()+5); 
			}
			$this->SetXY(5,$this->GetY()); 
	        $this->cell(46,5,'Total Commune pour '.$totalmbr1.' MDO',1,0,'L',1,0);
            
			$valt1=$this->mdoiiit($STRUCTURE,$COMMUNE,'M',$DATEJOUR1,$DATEJOUR2,0,1);
			$valt2=$this->mdoiiit($STRUCTURE,$COMMUNE,'F',$DATEJOUR1,$DATEJOUR2,0,1);
			$valt3=$this->mdoiiit($STRUCTURE,$COMMUNE,'M',$DATEJOUR1,$DATEJOUR2,2,4);
			$valt4=$this->mdoiiit($STRUCTURE,$COMMUNE,'F',$DATEJOUR1,$DATEJOUR2,2,4);
			$valt5=$this->mdoiiit($STRUCTURE,$COMMUNE,'M',$DATEJOUR1,$DATEJOUR2,5,9);
			$valt6=$this->mdoiiit($STRUCTURE,$COMMUNE,'F',$DATEJOUR1,$DATEJOUR2,5,9);
			$valt7=$this->mdoiiit($STRUCTURE,$COMMUNE,'M',$DATEJOUR1,$DATEJOUR2,10,14);
			$valt8=$this->mdoiiit($STRUCTURE,$COMMUNE,'F',$DATEJOUR1,$DATEJOUR2,10,14);
			$valt9=$this->mdoiiit($STRUCTURE,$COMMUNE,'M',$DATEJOUR1,$DATEJOUR2,15,19);
			$valt10=$this->mdoiiit($STRUCTURE,$COMMUNE,'F',$DATEJOUR1,$DATEJOUR2,15,19);
			$valt11=$this->mdoiiit($STRUCTURE,$COMMUNE,'M',$DATEJOUR1,$DATEJOUR2,20,44);
			$valt12=$this->mdoiiit($STRUCTURE,$COMMUNE,'F',$DATEJOUR1,$DATEJOUR2,20,44);
			$valt13=$this->mdoiiit($STRUCTURE,$COMMUNE,'M',$DATEJOUR1,$DATEJOUR2,45,64);
			$valt14=$this->mdoiiit($STRUCTURE,$COMMUNE,'F',$DATEJOUR1,$DATEJOUR2,45,64);
			$valt15=$this->mdoiiit($STRUCTURE,$COMMUNE,'M',$DATEJOUR1,$DATEJOUR2,65,99);
			$valt16=$this->mdoiiit($STRUCTURE,$COMMUNE,'F',$DATEJOUR1,$DATEJOUR2,65,99);
			
			$this->cell(13,5,$valt1,1,0,'C',1,0);
			$this->cell(13,5,$valt2,1,0,'C',1,0);
			$this->cell(13,5,$valt3,1,0,'C',1,0);
			$this->cell(13,5,$valt4,1,0,'C',1,0);
			$this->cell(13,5,$valt5,1,0,'C',1,0);
			$this->cell(13,5,$valt6,1,0,'C',1,0);
			$this->cell(13,5,$valt7,1,0,'C',1,0);
			$this->cell(13,5,$valt8,1,0,'C',1,0);
			$this->cell(13,5,$valt9,1,0,'C',1,0);
			$this->cell(13,5,$valt10,1,0,'C',1,0);
			$this->cell(13,5,$valt11,1,0,'C',1,0);
			$this->cell(13,5,$valt12,1,0,'C',1,0);
			$this->cell(13,5,$valt13,1,0,'C',1,0);
			$this->cell(13,5,$valt14,1,0,'C',1,0);
			$this->cell(13,5,$valt15,1,0,'C',1,0);
			$this->cell(13,5,$valt16,1,0,'C',1,0);
			$this->cell(12,5,$valt1+$valt3+$valt5+$valt7+$valt9+$valt11+$valt13+$valt15,1,0,'C',1,0);
			$this->cell(12,5,$valt2+$valt4+$valt6+$valt8+$valt10+$valt12+$valt14+$valt16,1,0,'C',1,0);
			$this->cell(12,5,$valt1+$valt3+$valt5+$valt7+$valt9+$valt11+$valt13+$valt15+$valt2+$valt4+$valt6+$valt8+$valt10+$valt12+$valt14+$valt16,1,0,'C',1,0);
			
		//****************************************************************************************//
		$this->setxy(5,$this->gety()+10); 
	}
	//********************************************************************************************//
	$this->piedmdo();	
	}
	function mdoiiiX($STRUCTURE,$mdo,$sexe,$datejour1,$datejour2,$age1,$age2) 
	{
	$this->mysqlconnect();
	$requete="SELECT * FROM mdo1 where 	STRUCTURE $STRUCTURE and  MDO='$mdo' and  SEXE='$sexe' and  (AGE BETWEEN '$age1' AND '$age2' and  (DATEMDO BETWEEN '$datejour1' AND '$datejour2')) ";//     
	$requete = @mysql_query($requete) or die($requete."<br>".mysql_error());
	$OP=mysql_num_rows($requete);
	mysql_free_result($requete);
	if ($OP==0){return $OP='';}else{return $OP;} 
	}
	
	function mdoiiitX($STRUCTURE,$sexe,$datejour1,$datejour2,$age1,$age2) 
	{
	$this->mysqlconnect();
	$requete="SELECT * FROM mdo1 where 	STRUCTURE $STRUCTURE and  SEXE='$sexe' and  (AGE BETWEEN '$age1' AND '$age2' and  (DATEMDO BETWEEN '$datejour1' AND '$datejour2')) ";//     
	$requete = @mysql_query($requete) or die($requete."<br>".mysql_error());
	$OP=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $OP;
	}
	
	function MDO3S($STRUCTURE,$DATEJOUR1,$DATEJOUR2)
    {
	$this->entetemdo(substr($STRUCTURE,1,20),"ANNEXE III - CIRCULAIRE N° 1126 /MSP/DP/SDPG... DU 17 NOVEMBER 1990",$DATEJOUR1,$DATEJOUR2);                                                                            
	//********************************************************************************************//
	$this->mysqlconnect();
	// $query = "SELECT * from COM where IDWIL='17000' and yes='1' order by COMMUNE   "; 
	// $res=mysql_query($query);
	// $tot=mysql_num_rows($res);
	// $this->SetXY(5,$this->GetY()+5); 
	// while($row=mysql_fetch_object($res))
	// {
	    $this->entetemdoiiit($this->nbrtostring('mdo','structure','id',substr($STRUCTURE,1,20),'structure'));
		//****************************************************************************************//
			$query1 = "SELECT * from mdo order by id "; 
			$this->SetXY(5,$this->GetY()+5); 
			$resultat1=mysql_query($query1);
			$totalmbr1=mysql_num_rows($resultat1);
			$this->SetXY(5,$this->GetY()); 
			while($row1=mysql_fetch_object($resultat1))
			{
			$this->SetFont('Arial','',6.5);
			$this->cell(46,5,$row1->mdo,1,0,'L',1,0);
			$this->SetFont('Arial','',9.5);
			$MDO=$row1->id;
			$COMMUNE=924;
			$val1=$this->mdoiiiX($STRUCTURE,$MDO,'M',$DATEJOUR1,$DATEJOUR2,0,1);
			$val2=$this->mdoiiiX($STRUCTURE,$MDO,'F',$DATEJOUR1,$DATEJOUR2,0,1);
			$val3=$this->mdoiiiX($STRUCTURE,$MDO,'M',$DATEJOUR1,$DATEJOUR2,2,4);
			$val4=$this->mdoiiiX($STRUCTURE,$MDO,'F',$DATEJOUR1,$DATEJOUR2,2,4);
			$val5=$this->mdoiiiX($STRUCTURE,$MDO,'M',$DATEJOUR1,$DATEJOUR2,5,9);
			$val6=$this->mdoiiiX($STRUCTURE,$MDO,'F',$DATEJOUR1,$DATEJOUR2,5,9);
			$val7=$this->mdoiiiX($STRUCTURE,$MDO,'M',$DATEJOUR1,$DATEJOUR2,10,14);
			$val8=$this->mdoiiiX($STRUCTURE,$MDO,'F',$DATEJOUR1,$DATEJOUR2,10,14);
			$val9=$this->mdoiiiX($STRUCTURE,$MDO,'M',$DATEJOUR1,$DATEJOUR2,15,19);
		   $val10=$this->mdoiiiX($STRUCTURE,$MDO,'F',$DATEJOUR1,$DATEJOUR2,15,19);
		   $val11=$this->mdoiiiX($STRUCTURE,$MDO,'M',$DATEJOUR1,$DATEJOUR2,20,44);
		   $val12=$this->mdoiiiX($STRUCTURE,$MDO,'F',$DATEJOUR1,$DATEJOUR2,20,44);
		   $val13=$this->mdoiiiX($STRUCTURE,$MDO,'M',$DATEJOUR1,$DATEJOUR2,45,64);
		   $val14=$this->mdoiiiX($STRUCTURE,$MDO,'F',$DATEJOUR1,$DATEJOUR2,45,64);
		   $val15=$this->mdoiiiX($STRUCTURE,$MDO,'M',$DATEJOUR1,$DATEJOUR2,65,99);
		   $val16=$this->mdoiiiX($STRUCTURE,$MDO,'F',$DATEJOUR1,$DATEJOUR2,65,99);
			
			$this->cell(13,5,$val1,1,0,'C',0,0);
			$this->cell(13,5,$val2,1,0,'C',0,0);
			$this->cell(13,5,$val3,1,0,'C',0,0);
			$this->cell(13,5,$val4,1,0,'C',0,0);
			$this->cell(13,5,$val5,1,0,'C',0,0);
			$this->cell(13,5,$val6,1,0,'C',0,0);
			$this->cell(13,5,$val7,1,0,'C',0,0);
			$this->cell(13,5,$val8,1,0,'C',0,0);
			$this->cell(13,5,$val9,1,0,'C',0,0);
			$this->cell(13,5,$val10,1,0,'C',0,0);
			$this->cell(13,5,$val11,1,0,'C',0,0);
			$this->cell(13,5,$val12,1,0,'C',0,0);
			$this->cell(13,5,$val13,1,0,'C',0,0);
			$this->cell(13,5,$val14,1,0,'C',0,0);
			$this->cell(13,5,$val15,1,0,'C',0,0);
			$this->cell(13,5,$val16,1,0,'C',0,0);
			$this->cell(12,5,$val1+$val3+$val5+$val7+$val9+$val11+$val13+$val15,1,0,'C',1,0);
			$this->cell(12,5,$val2+$val4+$val6+$val8+$val10+$val12+$val14+$val16,1,0,'C',1,0);
			$this->cell(12,5,$val1+$val3+$val5+$val7+$val9+$val11+$val13+$val15+$val2+$val4+$val6+$val8+$val10+$val12+$val14+$val16,1,0,'C',1,0);
			$this->setxy(5,$this->gety()+5); 
			}
			$this->SetXY(5,$this->GetY()); 
	        $this->cell(46,5,'Total Commune pour '.$totalmbr1.' MDO',1,0,'L',1,0);
            
			$valt1=$this->mdoiiitX($STRUCTURE,'M',$DATEJOUR1,$DATEJOUR2,0,1);
			$valt2=$this->mdoiiitX($STRUCTURE,'F',$DATEJOUR1,$DATEJOUR2,0,1);
			$valt3=$this->mdoiiitX($STRUCTURE,'M',$DATEJOUR1,$DATEJOUR2,2,4);
			$valt4=$this->mdoiiitX($STRUCTURE,'F',$DATEJOUR1,$DATEJOUR2,2,4);
			$valt5=$this->mdoiiitX($STRUCTURE,'M',$DATEJOUR1,$DATEJOUR2,5,9);
			$valt6=$this->mdoiiitX($STRUCTURE,'F',$DATEJOUR1,$DATEJOUR2,5,9);
			$valt7=$this->mdoiiitX($STRUCTURE,'M',$DATEJOUR1,$DATEJOUR2,10,14);
			$valt8=$this->mdoiiitX($STRUCTURE,'F',$DATEJOUR1,$DATEJOUR2,10,14);
			$valt9=$this->mdoiiitX($STRUCTURE,'M',$DATEJOUR1,$DATEJOUR2,15,19);
			$valt10=$this->mdoiiitX($STRUCTURE,'F',$DATEJOUR1,$DATEJOUR2,15,19);
			$valt11=$this->mdoiiitX($STRUCTURE,'M',$DATEJOUR1,$DATEJOUR2,20,44);
			$valt12=$this->mdoiiitX($STRUCTURE,'F',$DATEJOUR1,$DATEJOUR2,20,44);
			$valt13=$this->mdoiiitX($STRUCTURE,'M',$DATEJOUR1,$DATEJOUR2,45,64);
			$valt14=$this->mdoiiitX($STRUCTURE,'F',$DATEJOUR1,$DATEJOUR2,45,64);
			$valt15=$this->mdoiiitX($STRUCTURE,'M',$DATEJOUR1,$DATEJOUR2,65,99);
			$valt16=$this->mdoiiitX($STRUCTURE,'F',$DATEJOUR1,$DATEJOUR2,65,99);
			$this->cell(13,5,$valt1,1,0,'C',1,0);
			$this->cell(13,5,$valt2,1,0,'C',1,0);
			$this->cell(13,5,$valt3,1,0,'C',1,0);
			$this->cell(13,5,$valt4,1,0,'C',1,0);
			$this->cell(13,5,$valt5,1,0,'C',1,0);
			$this->cell(13,5,$valt6,1,0,'C',1,0);
			$this->cell(13,5,$valt7,1,0,'C',1,0);
			$this->cell(13,5,$valt8,1,0,'C',1,0);
			$this->cell(13,5,$valt9,1,0,'C',1,0);
			$this->cell(13,5,$valt10,1,0,'C',1,0);
			$this->cell(13,5,$valt11,1,0,'C',1,0);
			$this->cell(13,5,$valt12,1,0,'C',1,0);
			$this->cell(13,5,$valt13,1,0,'C',1,0);
			$this->cell(13,5,$valt14,1,0,'C',1,0);
			$this->cell(13,5,$valt15,1,0,'C',1,0);
			$this->cell(13,5,$valt16,1,0,'C',1,0);
			$this->cell(12,5,$valt1+$valt3+$valt5+$valt7+$valt9+$valt11+$valt13+$valt15,1,0,'C',1,0);
			$this->cell(12,5,$valt2+$valt4+$valt6+$valt8+$valt10+$valt12+$valt14+$valt16,1,0,'C',1,0);
			$this->cell(12,5,$valt1+$valt3+$valt5+$valt7+$valt9+$valt11+$valt13+$valt15+$valt2+$valt4+$valt6+$valt8+$valt10+$valt12+$valt14+$valt16,1,0,'C',1,0);
			
		//****************************************************************************************//
		$this->setxy(5,$this->gety()+10); 
	// }
	//********************************************************************************************//
	$this->piedmdo();	
	}
	
    function MDOR($STRUCTURE,$mdo,$DATEJOUR1,$DATEJOUR2)
    {
	$this->entetemdoP(substr($STRUCTURE,1,20),"Repartition Geographique Des Cas de ".$this->nbrtostring('mdo','mdo','id',$mdo,'mdo'),$DATEJOUR1,$DATEJOUR2);                                                                            
	$this->mysqlconnect(); 
    $this->djelfa($this->datasig($DATEJOUR1,$DATEJOUR2,$STRUCTURE,$mdo),20,128,3.7,'commune');//commune//dairas  
	$this->piedmdoP();	
	}
	
	function mdocomm($DATEJOUR1,$DATEJOUR2,$COMMUNER,$STRUCTURED,$MDO) 
	{
	$this->mysqlconnect();
	$sql = " select * from mdo1 where DATEMDO BETWEEN '$DATEJOUR1' AND '$DATEJOUR2' and COMMUNER=$COMMUNER and STRUCTURE $STRUCTURED  and MDO=$MDO ";
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$OP=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $OP;
	}
	function datasig($datejour1,$datejour2,$STRUCTURED,$MDO) 
	{
	$data = array(
	
	"titre"=> 'Nombre De '.ucwords(strtolower($this->nbrtostring('mdo','mdo','id',$MDO,'mdo'))) ,
	"A"    => '00-00',
	"B"    => '01-10',
	"C"    => '09-100',
	"D"    => '99-1000',
	"E"    => '999-10000',
	"1"    => $this->mdocomm($datejour1,$datejour2,916,$STRUCTURED,$MDO),//daira  Djelfa
	"2"    => $this->mdocomm($datejour1,$datejour2,924,$STRUCTURED,$MDO)+$this->mdocomm($datejour1,$datejour2,925,$STRUCTURED,$MDO),//daira  ainoussera
	"3"    => $this->mdocomm($datejour1,$datejour2,929,$STRUCTURED,$MDO)+$this->mdocomm($datejour1,$datejour2,931,$STRUCTURED,$MDO),//daira  birine
	"4"    => $this->mdocomm($datejour1,$datejour2,929,$STRUCTURED,$MDO)+$this->mdocomm($datejour1,$datejour2,927,$STRUCTURED,$MDO)+$this->mdocomm($datejour1,$datejour2,928,$STRUCTURED,$MDO),//daira  sidilaadjel
	"5"    => $this->mdocomm($datejour1,$datejour2,932,$STRUCTURED,$MDO)+$this->mdocomm($datejour1,$datejour2,933,$STRUCTURED,$MDO)+$this->mdocomm($datejour1,$datejour2,934,$STRUCTURED,$MDO),//daira  hadsahari
	"6"    => $this->mdocomm($datejour1,$datejour2,935,$STRUCTURED,$MDO)+$this->mdocomm($datejour1,$datejour2,939,$STRUCTURED,$MDO)+$this->mdocomm($datejour1,$datejour2,941,$STRUCTURED,$MDO)+$this->mdocomm($datejour1,$datejour2,940,$STRUCTURED,$MDO),//daira  hassibahbah
	"7"    => $this->mdocomm($datejour1,$datejour2,942,$STRUCTURED,$MDO)+$this->mdocomm($datejour1,$datejour2,946,$STRUCTURED,$MDO)+$this->mdocomm($datejour1,$datejour2,947,$STRUCTURED,$MDO),//daira  darchioukhe
	"8"    => $this->mdocomm($datejour1,$datejour2,920,$STRUCTURED,$MDO)+$this->mdocomm($datejour1,$datejour2,919,$STRUCTURED,$MDO)+$this->mdocomm($datejour1,$datejour2,923,$STRUCTURED,$MDO),//daira  charef
	"9"    => $this->mdocomm($datejour1,$datejour2,917,$STRUCTURED,$MDO)+$this->mdocomm($datejour1,$datejour2,964,$STRUCTURED,$MDO)+$this->mdocomm($datejour1,$datejour2,963,$STRUCTURED,$MDO),//daira  idrissia
	"10"   => $this->mdocomm($datejour1,$datejour2,965,$STRUCTURED,$MDO)+$this->mdocomm($datejour1,$datejour2,958,$STRUCTURED,$MDO)+$this->mdocomm($datejour1,$datejour2,962,$STRUCTURED,$MDO)+$this->mdocomm($datejour1,$datejour2,957,$STRUCTURED,$MDO),//daira  ain elbel
	"11"   => $this->mdocomm($datejour1,$datejour2,948,$STRUCTURED,$MDO)+$this->mdocomm($datejour1,$datejour2,952,$STRUCTURED,$MDO)+$this->mdocomm($datejour1,$datejour2,954,$STRUCTURED,$MDO)+$this->mdocomm($datejour1,$datejour2,953,$STRUCTURED,$MDO)+$this->mdocomm($datejour1,$datejour2,951,$STRUCTURED,$MDO),//daira  messaad
	"12"   => $this->mdocomm($datejour1,$datejour2,967,$STRUCTURED,$MDO)+$this->mdocomm($datejour1,$datejour2,968,$STRUCTURED,$MDO)+$this->mdocomm($datejour1,$datejour2,956,$STRUCTURED,$MDO),//daira  faid elbotma
	"916"  => $this->mdocomm($datejour1,$datejour2,916,$STRUCTURED,$MDO),//daira  Djelfa
	"917"  => $this->mdocomm($datejour1,$datejour2,917,$STRUCTURED,$MDO),//daira El Idrissia
	"918"  => $this->mdocomm($datejour1,$datejour2,918,$STRUCTURED,$MDO),//Oum Cheggag
	"919"  => $this->mdocomm($datejour1,$datejour2,919,$STRUCTURED,$MDO),//El Guedid
	"920"  => $this->mdocomm($datejour1,$datejour2,920,$STRUCTURED,$MDO),//daira Charef
	"921"  => $this->mdocomm($datejour1,$datejour2,921,$STRUCTURED,$MDO),//El Hammam
	"922"  => $this->mdocomm($datejour1,$datejour2,922,$STRUCTURED,$MDO),//Touazi
	"923"  => $this->mdocomm($datejour1,$datejour2,923,$STRUCTURED,$MDO),//Beni Yacoub
	"924"  => $this->mdocomm($datejour1,$datejour2,924,$STRUCTURED,$MDO),//daira ainoussera
	"925"  => $this->mdocomm($datejour1,$datejour2,925,$STRUCTURED,$MDO),//guernini
	"926"  => $this->mdocomm($datejour1,$datejour2,926,$STRUCTURED,$MDO),//daira sidilaadjel
	"927"  => $this->mdocomm($datejour1,$datejour2,927,$STRUCTURED,$MDO),//hassifdoul
	"928"  => $this->mdocomm($datejour1,$datejour2,928,$STRUCTURED,$MDO),//elkhamis
	"929"  => $this->mdocomm($datejour1,$datejour2,929,$STRUCTURED,$MDO),//daira birine
	"930"  => $this->mdocomm($datejour1,$datejour2,930,$STRUCTURED,$MDO),//Dra Souary
	"931"  => $this->mdocomm($datejour1,$datejour2,931,$STRUCTURED,$MDO),//benahar
	"932"  => $this->mdocomm($datejour1,$datejour2,932,$STRUCTURED,$MDO),//daira hadshari
	"933"  => $this->mdocomm($datejour1,$datejour2,933,$STRUCTURED,$MDO),//bouiratlahdab
	"934"  => $this->mdocomm($datejour1,$datejour2,934,$STRUCTURED,$MDO),//ainfka
	"935"  => $this->mdocomm($datejour1,$datejour2,935,$STRUCTURED,$MDO),//daira hassi bahbah
	"936"  => $this->mdocomm($datejour1,$datejour2,936,$STRUCTURED,$MDO),//Mouilah
	"937"  => $this->mdocomm($datejour1,$datejour2,937,$STRUCTURED,$MDO),//El Mesrane
	"938"  => $this->mdocomm($datejour1,$datejour2,938,$STRUCTURED,$MDO),//Hassi el Mora
	"939"  => $this->mdocomm($datejour1,$datejour2,939,$STRUCTURED,$MDO),//zaafrane
	"940"  => $this->mdocomm($datejour1,$datejour2,940,$STRUCTURED,$MDO),//hassi el euche
	"941"  => $this->mdocomm($datejour1,$datejour2,941,$STRUCTURED,$MDO),//ain maabed
	"942"  => $this->mdocomm($datejour1,$datejour2,942,$STRUCTURED,$MDO),//daira darchioukh
	"943"  => $this->mdocomm($datejour1,$datejour2,943,$STRUCTURED,$MDO),//Guendouza
	"944"  => $this->mdocomm($datejour1,$datejour2,944,$STRUCTURED,$MDO),//El Oguila
	"945"  => $this->mdocomm($datejour1,$datejour2,945,$STRUCTURED,$MDO),//El Merdja
	"946"  => $this->mdocomm($datejour1,$datejour2,946,$STRUCTURED,$MDO),//mliliha
	"947"  => $this->mdocomm($datejour1,$datejour2,947,$STRUCTURED,$MDO),//sidibayzid
	"948"  => $this->mdocomm($datejour1,$datejour2,948,$STRUCTURED,$MDO),//daira Messad
	"949"  => $this->mdocomm($datejour1,$datejour2,949,$STRUCTURED,$MDO),//Abdelmadjid
	"950"  => $this->mdocomm($datejour1,$datejour2,950,$STRUCTURED,$MDO),//Haniet Ouled Salem
	"951"  => $this->mdocomm($datejour1,$datejour2,951,$STRUCTURED,$MDO),//Guettara
	"952"  => $this->mdocomm($datejour1,$datejour2,952,$STRUCTURED,$MDO),//Deldoul
	"953"  => $this->mdocomm($datejour1,$datejour2,953,$STRUCTURED,$MDO),//Sed Rahal
	"954"  => $this->mdocomm($datejour1,$datejour2,954,$STRUCTURED,$MDO),//Selmana
	"955"  => $this->mdocomm($datejour1,$datejour2,955,$STRUCTURED,$MDO),//El Gahra
	"956"  => $this->mdocomm($datejour1,$datejour2,956,$STRUCTURED,$MDO),//Oum Laadham
	"957"  => $this->mdocomm($datejour1,$datejour2,957,$STRUCTURED,$MDO),//Mouadjebar
	"958"  => $this->mdocomm($datejour1,$datejour2,958,$STRUCTURED,$MDO),//daira Ain el Ibel
	"959"  => $this->mdocomm($datejour1,$datejour2,959,$STRUCTURED,$MDO),//Amera
	"960"  => $this->mdocomm($datejour1,$datejour2,960,$STRUCTURED,$MDO),//N'thila
	"961"  => $this->mdocomm($datejour1,$datejour2,961,$STRUCTURED,$MDO),//Oued Seddeur
	"962"  => $this->mdocomm($datejour1,$datejour2,962,$STRUCTURED,$MDO),//Zaccar
	"963"  => $this->mdocomm($datejour1,$datejour2,963,$STRUCTURED,$MDO),//Douis
	"964"  => $this->mdocomm($datejour1,$datejour2,964,$STRUCTURED,$MDO),//Ain Chouhada
	"965"  => $this->mdocomm($datejour1,$datejour2,965,$STRUCTURED,$MDO),//Tadmit
	"966"  => $this->mdocomm($datejour1,$datejour2,966,$STRUCTURED,$MDO),//El Hiouhi
	"967"  => $this->mdocomm($datejour1,$datejour2,967,$STRUCTURED,$MDO),//daira Faidh el Botma
	"968"  => $this->mdocomm($datejour1,$datejour2,968,$STRUCTURED,$MDO) //Amourah
	);		
	return $data;
	}
	function color($x) 
    {	
	if($x <= 0 ){$this->SetDrawColor(255,0,0);$this->SetFillColor(193,205,205);}//gris
	if($x >0  and $x<=10){$this->SetDrawColor(255,0,0);$this->SetFillColor(0,250,255);}//jaune
	if($x >10 and $x<=100){$this->SetDrawColor(255,0,0);$this->SetFillColor(0,255,0);}//orange
	if($x >100 and $x<=1000){$this->SetDrawColor(255,0,0);$this->SetFillColor(255,0,0);}//rouge
	if($x >1000 and $x<=10000){$this->SetDrawColor(255,0,0);$this->SetFillColor(165,42,42);}//brond	
    }
	function djelfa($data,$x,$y,$z,$cd) 
    {
	//$this->Image('../../public/IMAGES/photos/pc.gif',250,50,30,30,0);
	$this->SetXY(220,$y+88);$this->cell(65,5,'WILAYA DE DJELFA',1,0,'C',1,0);
	// $this->RoundedRect($x-15,35,140,160, 2, $style = '');
	$this->RoundedRect($x-15,$y-93,200,195, 2, $style = '');
	if ($cd=='dairas')
	{
	    //dairas ain-oussera//1-ain-oussera//2-guernini
		$this->color($data['2']);$this->Polygon(array((130+$x)/$z,(58+$y)/$z,(135+$x)/$z,(62+$y)/$z,(127+$x)/$z,(76+$y)/$z,(119+$x)/$z,(80+$y)/$z,(119+$x)/$z,(85+$y)/$z,(123+$x)/$z,(86+$y)/$z,(126+$x)/$z,(82+$y)/$z,(139+$x)/$z,(82+$y)/$z,(138+$x)/$z,(90+$y)/$z,(133+$x)/$z,(93+$y)/$z,(122+$x)/$z,(111+$y)/$z,(111+$x)/$z,(123+$y)/$z,(109+$x)/$z,(131+$y)/$z,(113+$x)/$z,(135+$y)/$z,(107+$x)/$z,(136+$y)/$z,(98+$x)/$z,(153+$y)/$z,(108+$x)/$z,(163+$y)/$z,(132+$x)/$z,(155+$y)/$z,(141+$x)/$z,(148+$y)/$z,(154+$x)/$z,(144+$y)/$z,(154+$x)/$z,(136+$y)/$z,(154+$x)/$z,(136+$y)/$z,(162+$x)/$z,(127+$y)/$z,(161+$x)/$z,(123+$y)/$z,(164+$x)/$z,(117+$y)/$z,(158+$x)/$z,(116+$y)/$z,(155+$x)/$z,(87+$y)/$z,(160+$x)/$z,(83+$y)/$z,(160+$x)/$z,(78+$y)/$z,(155+$x)/$z,(78+$y)/$z,(150+$x)/$z,(82+$y)/$z,(150+$x)/$z,(11+$y)/$z,(145+$x)/$z,(8+$y)/$z,(143+$x)/$z,(14+$y)/$z,(145+$x)/$z,(22+$y)/$z,(143+$x)/$z,(28+$y)/$z,(147+$x)/$z,(33+$y)/$z,(147+$x)/$z,(44+$y)/$z,(142+$x)/$z,(48+$y)/$z,(137+$x)/$z,(53+$y)/$z,(130+$x)/$z,(58+$y)/$z),'FD');														
		//dairas birin//2-benahar//1-birine
		$this->color($data['3']);$this->Polygon(array((150+$x)/$z,(11+$y)/$z,(150+$x)/$z,(82+$y)/$z,(155+$x)/$z,(78+$y)/$z,(160+$x)/$z,(78+$y)/$z,(160+$x)/$z,(83+$y)/$z,(155+$x)/$z,(87+$y)/$z,(158+$x)/$z,(116+$y)/$z,(164+$x)/$z,(117+$y)/$z,(161+$x)/$z,(123+$y)/$z,(162+$x)/$z,(127+$y)/$z,(172+$x)/$z,(123+$y)/$z,(179+$x)/$z,(119+$y)/$z,(191+$x)/$z,(105+$y)/$z,(200+$x)/$z,(98+$y)/$z,(194+$x)/$z,(78+$y)/$z,(204+$x)/$z,(75+$y)/$z,(224+$x)/$z,(68+$y)/$z,(243+$x)/$z,(53+$y)/$z,(221+$x)/$z,(30+$y)/$z,(220+$x)/$z,(22+$y)/$z,(212+$x)/$z,(22+$y)/$z,(207+$x)/$z,(14+$y)/$z,(205+$x)/$z,(9+$y)/$z,(198+$x)/$z,(14+$y)/$z,(197+$x)/$z,(25+$y)/$z,(191+$x)/$z,(36+$y)/$z,(185+$x)/$z,(36+$y)/$z,(181+$x)/$z,(38+$y)/$z,(173+$x)/$z,(50+$y)/$z,(172+$x)/$z,(38+$y)/$z,(170+$x)/$z,(25+$y)/$z,(165+$x)/$z,(23+$y)/$z,(161+$x)/$z,(6+$y)/$z,(150+$x)/$z,(11+$y)/$z),'FD');												
		//dairas sidilaadjel//2-hassifedoul//1-sidilaadjel//3-elkhamiss
		$this->color($data['4']);$this->Polygon(array((11+$x)/$z,(64+$y)/$z,(11+$x)/$z,(76+$y)/$z,(44+$x)/$z,(102+$y)/$z,(59+$x)/$z,(106+$y)/$z,(70+$x)/$z,(120+$y)/$z,(89+$x)/$z,(103+$y)/$z,(97+$x)/$z,(110+$y)/$z,(98+$x)/$z,(119+$y)/$z,(111+$x)/$z,(123+$y)/$z,(122+$x)/$z,(111+$y)/$z,(133+$x)/$z,(93+$y)/$z,(138+$x)/$z,(90+$y)/$z,(139+$x)/$z,(82+$y)/$z,(126+$x)/$z,(82+$y)/$z,(123+$x)/$z,(86+$y)/$z,(119+$x)/$z,(85+$y)/$z,(119+$x)/$z,(80+$y)/$z,(127+$x)/$z,(76+$y)/$z,(135+$x)/$z,(62+$y)/$z,(130+$x)/$z,(58+$y)/$z,(120+$x)/$z,(70+$y)/$z,(119+$x)/$z,(77+$y)/$z,(114+$x)/$z,(77+$y)/$z,(109+$x)/$z,(82+$y)/$z,(101+$x)/$z,(81+$y)/$z,(87+$x)/$z,(70+$y)/$z,(68+$x)/$z,(78+$y)/$z,(50+$x)/$z,(80+$y)/$z,(48+$x)/$z,(60+$y)/$z,(11+$x)/$z,(64+$y)/$z),'FD');
		//dairas had-sahari//2-ainfaka//1-had-sahari//3-bouiratlahdab							
		$this->color($data['5']);$this->Polygon(array((154+$x)/$z,(136+$y)/$z,(154+$x)/$z,(144+$y)/$z,(163+$x)/$z,(145+$y)/$z,(170+$x)/$z,(149+$y)/$z,(177+$x)/$z,(150+$y)/$z,(200+$x)/$z,(133+$y)/$z,(207+$x)/$z,(130+$y)/$z,(216+$x)/$z,(132+$y)/$z,(228+$x)/$z,(132+$y)/$z,(234+$x)/$z,(137+$y)/$z,(254+$x)/$z,(117+$y)/$z,(256+$x)/$z,(118+$y)/$z,(266+$x)/$z,(108+$y)/$z,(263+$x)/$z,(92+$y)/$z,(269+$x)/$z,(89+$y)/$z,(270+$x)/$z,(74+$y)/$z,(243+$x)/$z,(53+$y)/$z,(224+$x)/$z,(68+$y)/$z,(204+$x)/$z,(75+$y)/$z,(194+$x)/$z,(78+$y)/$z,(194+$x)/$z,(78+$y)/$z,(200+$x)/$z,(98+$y)/$z,(191+$x)/$z,(105+$y)/$z,(179+$x)/$z,(119+$y)/$z,(172+$x)/$z,(123+$y)/$z,(162+$x)/$z,(127+$y)/$z,(154+$x)/$z,(136+$y)/$z),'FD');			
		//dairas hassi-bahbah//2-zaafrane//4-ainmaabed//3-eleuch//1-hassi-bahbah
		$this->color($data['6']);$this->Polygon(array((108+$x)/$z,(163+$y)/$z,(102+$x)/$z,(167+$y)/$z,(89+$x)/$z,(168+$y)/$z,(85+$x)/$z,(172+$y)/$z,(81+$x)/$z,(193+$y)/$z,(114+$x)/$z,(198+$y)/$z,(118+$x)/$z,(196+$y)/$z,(123+$x)/$z,(196+$y)/$z,(127+$x)/$z,(204+$y)/$z,(128+$x)/$z,(215+$y)/$z,(133+$x)/$z,(221+$y)/$z,(138+$x)/$z,(222+$y)/$z,(139+$x)/$z,(232+$y)/$z,(142+$x)/$z,(237+$y)/$z,(141+$x)/$z,(242+$y)/$z,(145+$x)/$z,(245+$y)/$z,(142+$x)/$z,(256+$y)/$z,(155+$x)/$z,(259+$y)/$z,(164+$x)/$z,(249+$y)/$z,(174+$x)/$z,(243+$y)/$z,(173+$x)/$z,(227+$y)/$z,(178+$x)/$z,(224+$y)/$z,(183+$x)/$z,(223+$y)/$z,(189+$x)/$z,(223+$y)/$z,(189+$x)/$z,(217+$y)/$z,(193+$x)/$z,(212+$y)/$z,(201+$x)/$z,(210+$y)/$z,(205+$x)/$z,(208+$y)/$z,(217+$x)/$z,(197+$y)/$z,(207+$x)/$z,(194+$y)/$z,(203+$x)/$z,(183+$y)/$z,(197+$x)/$z,(183+$y)/$z,(191+$x)/$z,(177+$y)/$z,(214+$x)/$z,(164+$y)/$z,(222+$x)/$z,(164+$y)/$z,(222+$x)/$z,(150+$y)/$z,(233+$x)/$z,(137+$y)/$z,(228+$x)/$z,(132+$y)/$z,(216+$x)/$z,(132+$y)/$z,(207+$x)/$z,(130+$y)/$z,(200+$x)/$z,(133+$y)/$z,(177+$x)/$z,(150+$y)/$z,(170+$x)/$z,(149+$y)/$z,(163+$x)/$z,(145+$y)/$z,(154+$x)/$z,(144+$y)/$z,(141+$x)/$z,(148+$y)/$z,(132+$x)/$z,(155+$y)/$z,(108+$x)/$z,(163+$y)/$z),'FD');
		//dairas darchioukh//3-sidibayzid//1-darchioukh//2-mliliha
		$this->color($data['7']);$this->Polygon(array((233+$x)/$z,(137+$y)/$z,(222+$x)/$z,(150+$y)/$z,(222+$x)/$z,(164+$y)/$z,(214+$x)/$z,(164+$y)/$z,(191+$x)/$z,(177+$y)/$z,(197+$x)/$z,(183+$y)/$z,(203+$x)/$z,(183+$y)/$z,(207+$x)/$z,(194+$y)/$z,(217+$x)/$z,(197+$y)/$z,(205+$x)/$z,(208+$y)/$z,(211+$x)/$z,(218+$y)/$z,(218+$x)/$z,(217+$y)/$z,(233+$x)/$z,(219+$y)/$z,(239+$x)/$z,(226+$y)/$z,(240+$x)/$z,(241+$y)/$z,(245+$x)/$z,(243+$y)/$z,(245+$x)/$z,(250+$y)/$z,(249+$x)/$z,(250+$y)/$z,(251+$x)/$z,(246+$y)/$z,(258+$x)/$z,(244+$y)/$z,(272+$x)/$z,(255+$y)/$z,(274+$x)/$z,(250+$y)/$z,(269+$x)/$z,(248+$y)/$z,(268+$x)/$z,(243+$y)/$z,(271+$x)/$z,(240+$y)/$z,(276+$x)/$z,(242+$y)/$z,(279+$x)/$z,(247+$y)/$z,(283+$x)/$z,(250+$y)/$z,(288+$x)/$z,(248+$y)/$z,(306+$x)/$z,(247+$y)/$z,(306+$x)/$z,(243+$y)/$z,(302+$x)/$z,(240+$y)/$z,(301+$x)/$z,(214+$y)/$z,(276+$x)/$z,(212+$y)/$z,(272+$x)/$z,(206+$y)/$z,(265+$x)/$z,(211+$y)/$z,(262+$x)/$z,(204+$y)/$z,(261+$x)/$z,(197+$y)/$z,(254+$x)/$z,(194+$y)/$z,(252+$x)/$z,(186+$y)/$z,(249+$x)/$z,(182+$y)/$z,(253+$x)/$z,(180+$y)/$z,(250+$x)/$z,(165+$y)/$z,(255+$x)/$z,(154+$y)/$z,(248+$x)/$z,(159+$y)/$z,(233+$x)/$z,(137+$y)/$z),'FD');
		//djelfa
		$this->color($data['1']);$this->Polygon(array((173+$x)/$z,(227+$y)/$z,(174+$x)/$z,(243+$y)/$z,(177+$x)/$z,(248+$y)/$z,(184+$x)/$z,(251+$y)/$z,(185+$x)/$z,(256+$y)/$z,(188+$x)/$z,(260+$y)/$z,(194+$x)/$z,(258+$y)/$z,(201+$x)/$z,(263+$y)/$z,(214+$x)/$z,(255+$y)/$z,(212+$x)/$z,(240+$y)/$z,(217+$x)/$z,(230+$y)/$z,(215+$x)/$z,(220+$y)/$z,(218+$x)/$z,(217+$y)/$z,(211+$x)/$z,(218+$y)/$z,(205+$x)/$z,(208+$y)/$z,(201+$x)/$z,(210+$y)/$z,(193+$x)/$z,(212+$y)/$z,(189+$x)/$z,(217+$y)/$z,(189+$x)/$z,(223+$y)/$z,(183+$x)/$z,(223+$y)/$z,(178+$x)/$z,(224+$y)/$z,(173+$x)/$z,(227+$y)/$z),'FD');
		//dairas idrissia//1-idrissia//3-ainchouhadda//2-douisse
		$this->color($data['9']);$this->Polygon(array((67+$x)/$z,(278+$y)/$z,(72+$x)/$z,(289+$y)/$z,(77+$x)/$z,(305+$y)/$z,(85+$x)/$z,(320+$y)/$z,(91+$x)/$z,(325+$y)/$z,(93+$x)/$z,(333+$y)/$z,(100+$x)/$z,(334+$y)/$z,(102+$x)/$z,(339+$y)/$z,(107+$x)/$z,(343+$y)/$z,(111+$x)/$z,(343+$y)/$z,(118+$x)/$z,(344+$y)/$z,(126+$x)/$z,(338+$y)/$z,(134+$x)/$z,(339+$y)/$z,(132+$x)/$z,(332+$y)/$z,(143+$x)/$z,(315+$y)/$z,(137+$x)/$z,(311+$y)/$z,(133+$x)/$z,(313+$y)/$z,(131+$x)/$z,(310+$y)/$z,(127+$x)/$z,(311+$y)/$z,(127+$x)/$z,(303+$y)/$z,(132+$x)/$z,(299+$y)/$z,(129+$x)/$z,(297+$y)/$z,(128+$x)/$z,(288+$y)/$z,(123+$x)/$z,(288+$y)/$z,(115+$x)/$z,(285+$y)/$z,(110+$x)/$z,(289+$y)/$z,(100+$x)/$z,(285+$y)/$z,(100+$x)/$z,(280+$y)/$z,(106+$x)/$z,(277+$y)/$z,(107+$x)/$z,(273+$y)/$z,(101+$x)/$z,(273+$y)/$z,(95+$x)/$z,(269+$y)/$z,(96+$x)/$z,(261+$y)/$z,(78+$x)/$z,(265+$y)/$z,(77+$x)/$z,(275+$y)/$z,(67+$x)/$z,(278+$y)/$z),'FD');
		//dairas charef//2-guedid//1-charef//3-benyaagoub
		$this->color($data['8']);$this->Polygon(array((81+$x)/$z,(193+$y)/$z,(74+$x)/$z,(209+$y)/$z,(62+$x)/$z,(211+$y)/$z,(65+$x)/$z,(227+$y)/$z,(67+$x)/$z,(278+$y)/$z,(77+$x)/$z,(275+$y)/$z,(78+$x)/$z,(265+$y)/$z,(96+$x)/$z,(261+$y)/$z,(95+$x)/$z,(269+$y)/$z,(101+$x)/$z,(273+$y)/$z,(107+$x)/$z,(273+$y)/$z,(106+$x)/$z,(277+$y)/$z,(100+$x)/$z,(280+$y)/$z,(100+$x)/$z,(285+$y)/$z,(110+$x)/$z,(289+$y)/$z,(115+$x)/$z,(285+$y)/$z,(123+$x)/$z,(288+$y)/$z,(128+$x)/$z,(288+$y)/$z,(128+$x)/$z,(283+$y)/$z,(129+$x)/$z,(280+$y)/$z,(133+$x)/$z,(279+$y)/$z,(138+$x)/$z,(282+$y)/$z,(145+$x)/$z,(277+$y)/$z,(152+$x)/$z,(269+$y)/$z,(157+$x)/$z,(264+$y)/$z,(155+$x)/$z,(259+$y)/$z,(142+$x)/$z,(256+$y)/$z,(145+$x)/$z,(245+$y)/$z,(141+$x)/$z,(242+$y)/$z,(142+$x)/$z,(237+$y)/$z,(139+$x)/$z,(232+$y)/$z,(138+$x)/$z,(222+$y)/$z,(133+$x)/$z,(221+$y)/$z,(128+$x)/$z,(215+$y)/$z,(128+$x)/$z,(215+$y)/$z,(127+$x)/$z,(204+$y)/$z,(123+$x)/$z,(196+$y)/$z,(118+$x)/$z,(196+$y)/$z,(114+$x)/$z,(198+$y)/$z,(81+$x)/$z,(193+$y)/$z),'FD');
        //dairas ainelbel//3-taadmit //1-ainelbel//4-zakar//2-moudjbara
		$this->color($data['10']);$this->Polygon(array((143+$x)/$z,(315+$y)/$z,(151+$x)/$z,(310+$y)/$z,(157+$x)/$z,(314+$y)/$z,(161+$x)/$z,(319+$y)/$z,(170+$x)/$z,(316+$y)/$z,(172+$x)/$z,(324+$y)/$z,(177+$x)/$z,(329+$y)/$z,(176+$x)/$z,(344+$y)/$z,(186+$x)/$z,(368+$y)/$z,(197+$x)/$z,(360+$y)/$z,(199+$x)/$z,(352+$y)/$z,(196+$x)/$z,(352+$y)/$z,(193+$x)/$z,(354+$y)/$z,(191+$x)/$z,(352+$y)/$z,(187+$x)/$z,(350+$y)/$z,(186+$x)/$z,(353+$y)/$z,(183+$x)/$z,(348+$y)/$z,(182+$x)/$z,(333+$y)/$z,(183+$x)/$z,(327+$y)/$z,(187+$x)/$z,(322+$y)/$z,(194+$x)/$z,(314+$y)/$z,(203+$x)/$z,(309+$y)/$z,(210+$x)/$z,(302+$y)/$z,(215+$x)/$z,(293+$y)/$z,(222+$x)/$z,(281+$y)/$z,(227+$x)/$z,(268+$y)/$z,(231+$x)/$z,(279+$y)/$z,(231+$x)/$z,(308+$y)/$z,(229+$x)/$z,(322+$y)/$z,(237+$x)/$z,(322+$y)/$z,(240+$x)/$z,(320+$y)/$z,(247+$x)/$z,(325+$y)/$z,(252+$x)/$z,(313+$y)/$z,(256+$x)/$z,(308+$y)/$z,(262+$x)/$z,(302+$y)/$z,(266+$x)/$z,(289+$y)/$z,(252+$x)/$z,(272+$y)/$z,(242+$x)/$z,(252+$y)/$z,(245+$x)/$z,(250+$y)/$z,(245+$x)/$z,(243+$y)/$z,(240+$x)/$z,(241+$y)/$z,(239+$x)/$z,(226+$y)/$z,(233+$x)/$z,(219+$y)/$z,(227+$x)/$z,(219+$y)/$z,(218+$x)/$z,(217+$y)/$z,(215+$x)/$z,(220+$y)/$z,(217+$x)/$z,(230+$y)/$z,(212+$x)/$z,(240+$y)/$z,(214+$x)/$z,(255+$y)/$z,(214+$x)/$z,(255+$y)/$z,(201+$x)/$z,(263+$y)/$z,(194+$x)/$z,(258+$y)/$z,(188+$x)/$z,(260+$y)/$z,(185+$x)/$z,(256+$y)/$z,(184+$x)/$z,(251+$y)/$z,(177+$x)/$z,(248+$y)/$z,(174+$x)/$z,(243+$y)/$z,(164+$x)/$z,(249+$y)/$z,(155+$x)/$z,(259+$y)/$z,(157+$x)/$z,(264+$y)/$z,(152+$x)/$z,(269+$y)/$z,(145+$x)/$z,(277+$y)/$z,(138+$x)/$z,(282+$y)/$z,(133+$x)/$z,(279+$y)/$z,(129+$x)/$z,(280+$y)/$z,(128+$x)/$z,(283+$y)/$z,(128+$x)/$z,(288+$y)/$z,(129+$x)/$z,(297+$y)/$z,(132+$x)/$z,(299+$y)/$z,(127+$x)/$z,(303+$y)/$z,(127+$x)/$z,(311+$y)/$z,(131+$x)/$z,(310+$y)/$z,(133+$x)/$z,(313+$y)/$z,(137+$x)/$z,(311+$y)/$z,(143+$x)/$z,(315+$y)/$z),'FD');
		//dairas messaad//1-mesaad//2-deldoul//3-selmana//4-sedrahal//5-getara
		$this->color($data['11']);$this->Polygon(array((290+$x)/$z,(465+$y)/$z,(311+$x)/$z,(474+$y)/$z,(328+$x)/$z,(481+$y)/$z,(345+$x)/$z,(492+$y)/$z,(373+$x)/$z,(520+$y)/$z,(380+$x)/$z,(535+$y)/$z,(389+$x)/$z,(544+$y)/$z,(392+$x)/$z,(555+$y)/$z,(400+$x)/$z,(567+$y)/$z,(485+$x)/$z,(590+$y)/$z,(473+$x)/$z,(522+$y)/$z,(443+$x)/$z,(525+$y)/$z,(422+$x)/$z,(510+$y)/$z,(381+$x)/$z,(472+$y)/$z,(360+$x)/$z,(480+$y)/$z,(325+$x)/$z,(430+$y)/$z,(337+$x)/$z,(427+$y)/$z,(327+$x)/$z,(411+$y)/$z,(302+$x)/$z,(371+$y)/$z,(312+$x)/$z,(360+$y)/$z,(308+$x)/$z,(358+$y)/$z,(307+$x)/$z,(352+$y)/$z,(303+$x)/$z,(344+$y)/$z,(303+$x)/$z,(338+$y)/$z,(293+$x)/$z,(328+$y)/$z,(292+$x)/$z,(320+$y)/$z,(284+$x)/$z,(306+$y)/$z,(277+$x)/$z,(303+$y)/$z,(277+$x)/$z,(299+$y)/$z,(266+$x)/$z,(289+$y)/$z,(262+$x)/$z,(302+$y)/$z,(256+$x)/$z,(308+$y)/$z,(252+$x)/$z,(313+$y)/$z,(247+$x)/$z,(325+$y)/$z,(240+$x)/$z,(320+$y)/$z,(237+$x)/$z,(322+$y)/$z,(229+$x)/$z,(322+$y)/$z,(231+$x)/$z,(308+$y)/$z,(231+$x)/$z,(279+$y)/$z,(227+$x)/$z,(268+$y)/$z,(222+$x)/$z,(281+$y)/$z,(215+$x)/$z,(293+$y)/$z,(210+$x)/$z,(302+$y)/$z,(203+$x)/$z,(309+$y)/$z,(194+$x)/$z,(314+$y)/$z,(187+$x)/$z,(322+$y)/$z,(183+$x)/$z,(327+$y)/$z,(182+$x)/$z,(333+$y)/$z,(183+$x)/$z,(348+$y)/$z,(186+$x)/$z,(353+$y)/$z,(187+$x)/$z,(350+$y)/$z,(191+$x)/$z,(352+$y)/$z,(193+$x)/$z,(354+$y)/$z,(196+$x)/$z,(352+$y)/$z,(199+$x)/$z,(352+$y)/$z,(197+$x)/$z,(360+$y)/$z,(186+$x)/$z,(368+$y)/$z,(192+$x)/$z,(393+$y)/$z,(197+$x)/$z,(397+$y)/$z,(197+$x)/$z,(403+$y)/$z,(213+$x)/$z,(404+$y)/$z,(228+$x)/$z,(412+$y)/$z,(241+$x)/$z,(419+$y)/$z,(254+$x)/$z,(432+$y)/$z,(267+$x)/$z,(446+$y)/$z,(275+$x)/$z,(461+$y)/$z,(290+$x)/$z,(465+$y)/$z),'FD');
		//dairas faid boutma//1-faid boutma//2-amoura//3-oumeladam
		$this->color($data['12']);$this->Polygon(array((306+$x)/$z,(247+$y)/$z,(288+$x)/$z,(248+$y)/$z,(283+$x)/$z,(250+$y)/$z,(279+$x)/$z,(247+$y)/$z,(276+$x)/$z,(242+$y)/$z,(271+$x)/$z,(240+$y)/$z,(268+$x)/$z,(243+$y)/$z,(269+$x)/$z,(248+$y)/$z,(274+$x)/$z,(250+$y)/$z,(272+$x)/$z,(255+$y)/$z,(258+$x)/$z,(244+$y)/$z,(251+$x)/$z,(246+$y)/$z,(249+$x)/$z,(250+$y)/$z,(245+$x)/$z,(250+$y)/$z,(242+$x)/$z,(252+$y)/$z,(252+$x)/$z,(272+$y)/$z,(266+$x)/$z,(289+$y)/$z,(277+$x)/$z,(299+$y)/$z,(277+$x)/$z,(303+$y)/$z,(284+$x)/$z,(306+$y)/$z,(292+$x)/$z,(320+$y)/$z,(293+$x)/$z,(328+$y)/$z,(303+$x)/$z,(338+$y)/$z,(303+$x)/$z,(344+$y)/$z,(307+$x)/$z,(352+$y)/$z,(308+$x)/$z,(358+$y)/$z,(312+$x)/$z,(360+$y)/$z,(302+$x)/$z,(371+$y)/$z,(327+$x)/$z,(411+$y)/$z,(337+$x)/$z,(427+$y)/$z,(325+$x)/$z,(430+$y)/$z,(360+$x)/$z,(480+$y)/$z,(381+$x)/$z,(472+$y)/$z,(422+$x)/$z,(510+$y)/$z,(443+$x)/$z,(525+$y)/$z,(473+$x)/$z,(522+$y)/$z,(473+$x)/$z,(498+$y)/$z,(489+$x)/$z,(463+$y)/$z,(486+$x)/$z,(449+$y)/$z,(493+$x)/$z,(442+$y)/$z,(473+$x)/$z,(434+$y)/$z,(462+$x)/$z,(434+$y)/$z,(458+$x)/$z,(424+$y)/$z,(443+$x)/$z,(425+$y)/$z,(439+$x)/$z,(418+$y)/$z,(435+$x)/$z,(420+$y)/$z,(432+$x)/$z,(416+$y)/$z,(419+$x)/$z,(416+$y)/$z,(416+$x)/$z,(414+$y)/$z,(411+$x)/$z,(405+$y)/$z,(407+$x)/$z,(402+$y)/$z,(398+$x)/$z,(398+$y)/$z,(384+$x)/$z,(395+$y)/$z,(378+$x)/$z,(389+$y)/$z,(364+$x)/$z,(384+$y)/$z,(356+$x)/$z,(378+$y)/$z,(356+$x)/$z,(374+$y)/$z,(369+$x)/$z,(373+$y)/$z,(379+$x)/$z,(360+$y)/$z,(388+$x)/$z,(360+$y)/$z,(386+$x)/$z,(353+$y)/$z,(372+$x)/$z,(354+$y)/$z,(366+$x)/$z,(349+$y)/$z,(367+$x)/$z,(342+$y)/$z,(364+$x)/$z,(338+$y)/$z,(359+$x)/$z,(338+$y)/$z,(358+$x)/$z,(335+$y)/$z,(349+$x)/$z,(338+$y)/$z,(348+$x)/$z,(332+$y)/$z,(343+$x)/$z,(329+$y)/$z,(348+$x)/$z,(323+$y)/$z,(342+$x)/$z,(322+$y)/$z,(342+$x)/$z,(317+$y)/$z,(337+$x)/$z,(317+$y)/$z,(340+$x)/$z,(312+$y)/$z,(331+$x)/$z,(308+$y)/$z,(329+$x)/$z,(302+$y)/$z,(324+$x)/$z,(302+$y)/$z,(316+$x)/$z,(298+$y)/$z,(317+$x)/$z,(280+$y)/$z,(306+$x)/$z,(247+$y)/$z),'FD');
	}
	if ($cd=='commune')
	{
	//A-ain-oussera
		//dairas ain-oussera
		    $this->color($data['1']);$this->Polygon(array((130+$x)/$z,(58+$y)/$z,(135+$x)/$z,(62+$y)/$z,(127+$x)/$z,(76+$y)/$z,(119+$x)/$z,(80+$y)/$z,(119+$x)/$z,(85+$y)/$z,(123+$x)/$z,(86+$y)/$z,(126+$x)/$z,(82+$y)/$z,(139+$x)/$z,(82+$y)/$z,(138+$x)/$z,(90+$y)/$z,(133+$x)/$z,(93+$y)/$z,(122+$x)/$z,(111+$y)/$z,(122+$x)/$z,(111+$y)/$z,(111+$x)/$z,(123+$y)/$z,(109+$x)/$z,(131+$y)/$z,(113+$x)/$z,(135+$y)/$z,(107+$x)/$z,(136+$y)/$z,(98+$x)/$z,(153+$y)/$z,(108+$x)/$z,(163+$y)/$z,(132+$x)/$z,(155+$y)/$z,(141+$x)/$z,(148+$y)/$z,(154+$x)/$z,(144+$y)/$z,(154+$x)/$z,(136+$y)/$z,(154+$x)/$z,(136+$y)/$z,(162+$x)/$z,(127+$y)/$z,(161+$x)/$z,(123+$y)/$z,(164+$x)/$z,(117+$y)/$z,(158+$x)/$z,(116+$y)/$z,(155+$x)/$z,(87+$y)/$z,(160+$x)/$z,(83+$y)/$z,(160+$x)/$z,(78+$y)/$z ,(155+$x)/$z,(78+$y)/$z,(150+$x)/$z,(82+$y)/$z,(150+$x)/$z,(11+$y)/$z,(145+$x)/$z,(8+$y)/$z,(143+$x)/$z,(14+$y)/$z,(145+$x)/$z,(22+$y)/$z,(143+$x)/$z,(28+$y)/$z,(147+$x)/$z,(33+$y)/$z,(147+$x)/$z,(44+$y)/$z,(142+$x)/$z,(48+$y)/$z,(137+$x)/$z,(53+$y)/$z,(130+$x)/$z,(58+$y)/$z),'FD');	
			//1-ain-oussera
			$this->color($data['924']);$this->Polygon(array((130+$x)/$z,(58+$y)/$z,(135+$x)/$z,(62+$y)/$z,(127+$x)/$z,(76+$y)/$z,(119+$x)/$z,(80+$y)/$z,(119+$x)/$z,(85+$y)/$z,(123+$x)/$z,(86+$y)/$z,(126+$x)/$z,(82+$y)/$z,(139+$x)/$z,(82+$y)/$z,(138+$x)/$z,(90+$y)/$z,(133+$x)/$z,(93+$y)/$z,(122+$x)/$z,(111+$y)/$z,(154+$x)/$z,(136+$y)/$z,(162+$x)/$z,(127+$y)/$z,(161+$x)/$z,(123+$y)/$z,(164+$x)/$z,(117+$y)/$z,(158+$x)/$z,(116+$y)/$z,(155+$x)/$z,(87+$y)/$z,(160+$x)/$z,(83+$y)/$z,(160+$x)/$z,(78+$y)/$z ,(155+$x)/$z,(78+$y)/$z,(150+$x)/$z,(82+$y)/$z,(150+$x)/$z,(11+$y)/$z,(145+$x)/$z,(8+$y)/$z,(143+$x)/$z,(14+$y)/$z,(145+$x)/$z,(22+$y)/$z,(143+$x)/$z,(28+$y)/$z,(147+$x)/$z,(33+$y)/$z,(147+$x)/$z,(44+$y)/$z,(142+$x)/$z,(48+$y)/$z,(137+$x)/$z,(53+$y)/$z,(130+$x)/$z,(58+$y)/$z),'FD');
			//2-guernini
			$this->color($data['925']);$this->Polygon(array((111+$x)/$z,(123+$y)/$z,(109+$x)/$z,(131+$y)/$z,(113+$x)/$z,(135+$y)/$z,(107+$x)/$z,(136+$y)/$z,(98+$x)/$z,(153+$y)/$z,(108+$x)/$z,(163+$y)/$z,(132+$x)/$z,(155+$y)/$z,(141+$x)/$z,(148+$y)/$z,(154+$x)/$z,(144+$y)/$z,(154+$x)/$z,(136+$y)/$z,(122+$x)/$z,(111+$y)/$z,(111+$x)/$z,(123+$y)/$z),'FD');
		//dairas birin
			//1-birine
			$this->color($data['929']);$this->Polygon(array((173+$x)/$z,(50+$y)/$z,(188+$x)/$z,(64+$y)/$z,(193+$x)/$z,(64+$y)/$z,(194+$x)/$z,(78+$y)/$z,(204+$x)/$z,(75+$y)/$z,(224+$x)/$z,(68+$y)/$z,(243+$x)/$z,(53+$y)/$z,(221+$x)/$z,(30+$y)/$z,(220+$x)/$z,(22+$y)/$z,(212+$x)/$z,(22+$y)/$z,(207+$x)/$z,(14+$y)/$z,(205+$x)/$z,(9+$y)/$z,(198+$x)/$z,(14+$y)/$z ,(197+$x)/$z,(25+$y)/$z ,(191+$x)/$z,(36+$y)/$z,(185+$x)/$z,(36+$y)/$z,(181+$x)/$z,(38+$y)/$z,(173+$x)/$z,(50+$y)/$z),'FD');
			//2-benahar
			$this->color($data['931']);$this->Polygon(array((150+$x)/$z,(11+$y)/$z,(150+$x)/$z,(82+$y)/$z,(155+$x)/$z,(78+$y)/$z,(160+$x)/$z,(78+$y)/$z,(160+$x)/$z,(83+$y)/$z,(155+$x)/$z,(87+$y)/$z,(158+$x)/$z,(116+$y)/$z,(164+$x)/$z,(117+$y)/$z,(161+$x)/$z,(123+$y)/$z,(162+$x)/$z,(127+$y)/$z,(172+$x)/$z,(123+$y)/$z,(179+$x)/$z,(119+$y)/$z,(191+$x)/$z,(105+$y)/$z,(200+$x)/$z,(98+$y)/$z,(194+$x)/$z,(78+$y)/$z,(193+$x)/$z,(64+$y)/$z,(188+$x)/$z,(64+$y)/$z,(173+$x)/$z,(50+$y)/$z,(172+$x)/$z,(38+$y)/$z,(170+$x)/$z,(25+$y)/$z,(165+$x)/$z,(23+$y)/$z,(161+$x)/$z,(6+$y)/$z,(150+$x)/$z,(11+$y)/$z),'FD');
		//dairas sidilaadjel
			//1-sidilaadjel
			$this->color($data['926']);$this->Polygon(array((68+$x)/$z,(78+$y)/$z,(69+$x)/$z,(91+$y)/$z,(59+$x)/$z,(106+$y)/$z,(70+$x)/$z,(120+$y)/$z,(89+$x)/$z,(103+$y)/$z,(101+$x)/$z,(81+$y)/$z,(87+$x)/$z,(70+$y)/$z,(68+$x)/$z,(78+$y)/$z),'FD');
			//2-hassifedoul
			$this->color($data['927']);$this->Polygon(array((11+$x)/$z,(64+$y)/$z,(48+$x)/$z,(60+$y)/$z,(50+$x)/$z,(80+$y)/$z,(68+$x)/$z,(78+$y)/$z,(69+$x)/$z,(91+$y)/$z,(59+$x)/$z,(106+$y)/$z,(44+$x)/$z,(102+$y)/$z,(11+$x)/$z,(76+$y)/$z,(11+$x)/$z,(64+$y)/$z),'FD');
			//3-elkhamiss
			$this->color($data['928']);$this->Polygon(array((101+$x)/$z,(81+$y)/$z,(89+$x)/$z,(103+$y)/$z,(97+$x)/$z,(110+$y)/$z,(98+$x)/$z,(119+$y)/$z,(111+$x)/$z,(123+$y)/$z,(122+$x)/$z,(111+$y)/$z,(133+$x)/$z,(93+$y)/$z,(138+$x)/$z,(90+$y)/$z,(139+$x)/$z,(82+$y)/$z,(126+$x)/$z,(82+$y)/$z,(123+$x)/$z,(86+$y)/$z,(119+$x)/$z,(85+$y)/$z,(119+$x)/$z,(80+$y)/$z,(127+$x)/$z,(76+$y)/$z,(135+$x)/$z,(62+$y)/$z,(130+$x)/$z,(58+$y)/$z,(120+$x)/$z,(70+$y)/$z,(119+$x)/$z,(77+$y)/$z,(114+$x)/$z,(77+$y)/$z,(109+$x)/$z,(82+$y)/$z,(101+$x)/$z,(81+$y)/$z),'FD');	
		//dairas had-sahari
			//1-had-sahari
			$this->color($data['932']);$this->Polygon(array((191+$x)/$z,(105+$y)/$z,(198+$x)/$z,(112+$y)/$z,(200+$x)/$z,(133+$y)/$z,(207+$x)/$z,(130+$y)/$z,(216+$x)/$z,(132+$y)/$z,(228+$x)/$z,(132+$y)/$z,(234+$x)/$z,(137+$y)/$z,(254+$x)/$z,(117+$y)/$z,(256+$x)/$z,(118+$y)/$z,(248+$x)/$z,(105+$y)/$z,(237+$x)/$z,(100+$y)/$z,(224+$x)/$z,(68+$y)/$z,(204+$x)/$z,(75+$y)/$z,(194+$x)/$z,(78+$y)/$z,(194+$x)/$z,(78+$y)/$z,(200+$x)/$z,(98+$y)/$z,(191+$x)/$z,(105+$y)/$z),'FD');
			//2-ainfaka
			$this->color($data['934']);$this->Polygon(array((243+$x)/$z,(53+$y)/$z,(224+$x)/$z,(68+$y)/$z,(237+$x)/$z,(100+$y)/$z,(248+$x)/$z,(105+$y)/$z,(256+$x)/$z,(118+$y)/$z,(266+$x)/$z,(108+$y)/$z,(263+$x)/$z,(92+$y)/$z,(269+$x)/$z,(89+$y)/$z,(270+$x)/$z,(74+$y)/$z,(243+$x)/$z,(53+$y)/$z),'FD');
			//3-bouiratlahdab
			$this->color($data['933']);$this->Polygon(array((154+$x)/$z,(136+$y)/$z,(154+$x)/$z,(144+$y)/$z,(163+$x)/$z,(145+$y)/$z,(170+$x)/$z,(149+$y)/$z,(177+$x)/$z,(150+$y)/$z,(200+$x)/$z,(133+$y)/$z,(198+$x)/$z,(112+$y)/$z,(191+$x)/$z,(105+$y)/$z,(179+$x)/$z,(119+$y)/$z,(172+$x)/$z,(123+$y)/$z,(162+$x)/$z,(127+$y)/$z,(154+$x)/$z,(136+$y)/$z),'FD');
	//B-hassi-bahbah  
		//dairas hassi-bahbah
			//1-hassi-bahbah
			$this->color($data['935']);$this->Polygon(array((108+$x)/$z,(163+$y)/$z,(113+$x)/$z,(171+$y)/$z,(124+$x)/$z,(171+$y)/$z,(125+$x)/$z,(180+$y)/$z,(139+$x)/$z,(181+$y)/$z,(152+$x)/$z,(185+$y)/$z,(157+$x)/$z,(195+$y)/$z,(159+$x)/$z,(200+$y)/$z,(176+$x)/$z,(192+$y)/$z,(181+$x)/$z,(188+$y)/$z,(179+$x)/$z,(183+$y)/$z,(185+$x)/$z,(181+$y)/$z,(191+$x)/$z,(177+$y)/$z,(184+$x)/$z,(173+$y)/$z,(187+$x)/$z,(170+$y)/$z,(181+$x)/$z,(163+$y)/$z,(177+$x)/$z,(156+$y)/$z,(177+$x)/$z,(150+$y)/$z,(170+$x)/$z,(149+$y)/$z,(163+$x)/$z,(145+$y)/$z,(154+$x)/$z,(144+$y)/$z,(141+$x)/$z,(148+$y)/$z,(132+$x)/$z,(155+$y)/$z,(108+$x)/$z,(163+$y)/$z),'FD');
			//2-zaafrane
			$this->color($data['939']);$this->Polygon(array((108+$x)/$z,(163+$y)/$z,(102+$x)/$z,(167+$y)/$z,(89+$x)/$z,(168+$y)/$z,(85+$x)/$z,(172+$y)/$z,(81+$x)/$z,(193+$y)/$z,(114+$x)/$z,(198+$y)/$z,(118+$x)/$z,(196+$y)/$z,(123+$x)/$z,(196+$y)/$z,(127+$x)/$z,(204+$y)/$z,(128+$x)/$z,(215+$y)/$z,(133+$x)/$z,(221+$y)/$z,(138+$x)/$z,(222+$y)/$z,(139+$x)/$z,(232+$y)/$z,(142+$x)/$z,(237+$y)/$z,(141+$x)/$z,(242+$y)/$z,(145+$x)/$z,(245+$y)/$z,(142+$x)/$z,(256+$y)/$z,(155+$x)/$z,(259+$y)/$z,(164+$x)/$z,(249+$y)/$z,(174+$x)/$z,(243+$y)/$z,(173+$x)/$z,(227+$y)/$z,(164+$x)/$z,(223+$y)/$z,(170+$x)/$z,(214+$y)/$z,(159+$x)/$z,(200+$y)/$z,(157+$x)/$z,(195+$y)/$z,(152+$x)/$z,(185+$y)/$z,(139+$x)/$z,(181+$y)/$z,(125+$x)/$z,(180+$y)/$z,(124+$x)/$z,(171+$y)/$z,(113+$x)/$z,(171+$y)/$z,(108+$x)/$z,(163+$y)/$z),'FD');
			//3-eleuch
			$this->color($data['940']);$this->Polygon(array((177+$x)/$z,(150+$y)/$z,(177+$x)/$z,(156+$y)/$z,(181+$x)/$z,(163+$y)/$z,(187+$x)/$z,(170+$y)/$z,(184+$x)/$z,(173+$y)/$z,(191+$x)/$z,(177+$y)/$z,(214+$x)/$z,(164+$y)/$z,(222+$x)/$z,(164+$y)/$z,(222+$x)/$z,(150+$y)/$z,(233+$x)/$z,(137+$y)/$z,(228+$x)/$z,(132+$y)/$z,(216+$x)/$z,(132+$y)/$z,(207+$x)/$z,(130+$y)/$z,(200+$x)/$z,(133+$y)/$z,(177+$x)/$z,(150+$y)/$z),'FD');
			//4-ainmaabed
			$this->color($data['941']);$this->Polygon(array((217+$x)/$z,(197+$y)/$z,(207+$x)/$z,(194+$y)/$z,(203+$x)/$z,(183+$y)/$z,(197+$x)/$z,(183+$y)/$z,(191+$x)/$z,(177+$y)/$z,(185+$x)/$z,(181+$y)/$z,(179+$x)/$z,(183+$y)/$z,(181+$x)/$z,(188+$y)/$z,(176+$x)/$z,(192+$y)/$z,(159+$x)/$z,(200+$y)/$z,(170+$x)/$z,(214+$y)/$z,(164+$x)/$z,(223+$y)/$z,(173+$x)/$z,(227+$y)/$z,(178+$x)/$z,(224+$y)/$z,(183+$x)/$z,(223+$y)/$z,(189+$x)/$z,(223+$y)/$z,(189+$x)/$z,(217+$y)/$z,(193+$x)/$z,(212+$y)/$z,(201+$x)/$z,(210+$y)/$z,(205+$x)/$z,(208+$y)/$z,(217+$x)/$z,(197+$y)/$z),'FD');
		//dairas darchioukh
			//1-darchioukh
			$this->color($data['942']);$this->Polygon(array((205+$x)/$z,(208+$y)/$z,(211+$x)/$z,(218+$y)/$z,(218+$x)/$z,(217+$y)/$z,(221+$x)/$z,(211+$y)/$z,(227+$x)/$z,(208+$y)/$z,(237+$x)/$z,(208+$y)/$z,(240+$x)/$z,(201+$y)/$z,(248+$x)/$z,(198+$y)/$z,(254+$x)/$z,(194+$y)/$z,(252+$x)/$z,(186+$y)/$z,(249+$x)/$z,(182+$y)/$z,(253+$x)/$z,(180+$y)/$z,(250+$x)/$z,(165+$y)/$z,(226+$x)/$z,(187+$y)/$z,(226+$x)/$z,(194+$y)/$z,(217+$x)/$z,(197+$y)/$z,(205+$x)/$z,(208+$y)/$z),'FD');
			//2-mliliha
			$this->color($data['946']);$this->Polygon(array((254+$x)/$z,(194+$y)/$z,(248+$x)/$z,(198+$y)/$z,(240+$x)/$z,(201+$y)/$z,(237+$x)/$z,(208+$y)/$z,(227+$x)/$z,(208+$y)/$z,(221+$x)/$z,(211+$y)/$z,(218+$x)/$z,(217+$y)/$z,(227+$x)/$z,(219+$y)/$z,(233+$x)/$z,(219+$y)/$z,(239+$x)/$z,(226+$y)/$z,(240+$x)/$z,(241+$y)/$z,(245+$x)/$z,(243+$y)/$z,(245+$x)/$z,(250+$y)/$z,(249+$x)/$z,(250+$y)/$z,(251+$x)/$z,(246+$y)/$z,(258+$x)/$z,(244+$y)/$z,(272+$x)/$z,(255+$y)/$z,(274+$x)/$z,(250+$y)/$z,(269+$x)/$z,(248+$y)/$z,(268+$x)/$z,(243+$y)/$z,(271+$x)/$z,(240+$y)/$z,(276+$x)/$z,(242+$y)/$z,(279+$x)/$z,(247+$y)/$z,(283+$x)/$z,(250+$y)/$z,(288+$x)/$z,(248+$y)/$z,(306+$x)/$z,(247+$y)/$z,(306+$x)/$z,(243+$y)/$z,(302+$x)/$z,(240+$y)/$z,(301+$x)/$z,(214+$y)/$z,(276+$x)/$z,(212+$y)/$z,(272+$x)/$z,(206+$y)/$z,(265+$x)/$z,(211+$y)/$z,(262+$x)/$z,(204+$y)/$z,(261+$x)/$z,(197+$y)/$z,(254+$x)/$z,(194+$y)/$z),'FD');
			//3-sidibayzid
			$this->color($data['947']);$this->Polygon(array((233+$x)/$z,(137+$y)/$z,(222+$x)/$z,(150+$y)/$z,(222+$x)/$z,(164+$y)/$z,(214+$x)/$z,(164+$y)/$z,(191+$x)/$z,(177+$y)/$z,(197+$x)/$z,(183+$y)/$z,(203+$x)/$z,(183+$y)/$z,(207+$x)/$z,(194+$y)/$z,(217+$x)/$z,(197+$y)/$z,(226+$x)/$z,(194+$y)/$z,(226+$x)/$z,(187+$y)/$z,(250+$x)/$z,(165+$y)/$z,(255+$x)/$z,(154+$y)/$z,(248+$x)/$z,(159+$y)/$z,(233+$x)/$z,(137+$y)/$z),'FD');
	//C-djelfa
		//djelfa
		$this->color($data['916']);$this->Polygon(array((173+$x)/$z,(227+$y)/$z,(174+$x)/$z,(243+$y)/$z,(177+$x)/$z,(248+$y)/$z,(184+$x)/$z,(251+$y)/$z,(185+$x)/$z,(256+$y)/$z,(188+$x)/$z,(260+$y)/$z,(194+$x)/$z,(258+$y)/$z,(201+$x)/$z,(263+$y)/$z,(214+$x)/$z,(255+$y)/$z,(212+$x)/$z,(240+$y)/$z,(217+$x)/$z,(230+$y)/$z,(215+$x)/$z,(220+$y)/$z,(218+$x)/$z,(217+$y)/$z,(211+$x)/$z,(218+$y)/$z,(205+$x)/$z,(208+$y)/$z,(201+$x)/$z,(210+$y)/$z,(193+$x)/$z,(212+$y)/$z,(189+$x)/$z,(217+$y)/$z,(189+$x)/$z,(223+$y)/$z,(183+$x)/$z,(223+$y)/$z,(178+$x)/$z,(224+$y)/$z,(173+$x)/$z,(227+$y)/$z),'FD');
		//dairas idrissia
			//1-idrissia
			$this->color($data['917']);$this->Polygon(array((67+$x)/$z,(278+$y)/$z,(72+$x)/$z,(289+$y)/$z,(77+$x)/$z,(305+$y)/$z,(88+$x)/$z,(304+$y)/$z,(92+$x)/$z,(300+$y)/$z,(110+$x)/$z,(289+$y)/$z,(100+$x)/$z,(285+$y)/$z,(100+$x)/$z,(280+$y)/$z,(106+$x)/$z,(277+$y)/$z,(107+$x)/$z,(273+$y)/$z,(101+$x)/$z,(273+$y)/$z,(95+$x)/$z,(269+$y)/$z,(96+$x)/$z,(261+$y)/$z,(78+$x)/$z,(265+$y)/$z,(77+$x)/$z,(275+$y)/$z,(67+$x)/$z,(278+$y)/$z),'FD');
			//2-douisse
			$this->color($data['963']);$this->Polygon(array((111+$x)/$z,(343+$y)/$z,(118+$x)/$z,(344+$y)/$z,(126+$x)/$z,(338+$y)/$z,(134+$x)/$z,(339+$y)/$z,(132+$x)/$z,(332+$y)/$z,(143+$x)/$z,(315+$y)/$z,(137+$x)/$z,(311+$y)/$z,(133+$x)/$z,(313+$y)/$z,(131+$x)/$z,(310+$y)/$z,(127+$x)/$z,(311+$y)/$z,(127+$x)/$z,(303+$y)/$z,(132+$x)/$z,(299+$y)/$z,(129+$x)/$z,(297+$y)/$z,(128+$x)/$z,(288+$y)/$z,(123+$x)/$z,(288+$y)/$z,(115+$x)/$z,(285+$y)/$z,(110+$x)/$z,(289+$y)/$z,(92+$x)/$z,(300+$y)/$z,(95+$x)/$z,(304+$y)/$z,(101+$x)/$z,(306+$y)/$z,(106+$x)/$z,(307+$y)/$z,(105+$x)/$z,(318+$y)/$z,(105+$x)/$z,(329+$y)/$z,(108+$x)/$z,(332+$y)/$z,(111+$x)/$z,(343+$y)/$z),'FD');
			//3-ainchouhadda
			$this->color($data['964']);$this->Polygon(array((77+$x)/$z,(305+$y)/$z,(85+$x)/$z,(320+$y)/$z,(91+$x)/$z,(325+$y)/$z,(93+$x)/$z,(333+$y)/$z,(100+$x)/$z,(334+$y)/$z,(102+$x)/$z,(339+$y)/$z,(107+$x)/$z,(343+$y)/$z,(111+$x)/$z,(343+$y)/$z,(108+$x)/$z,(332+$y)/$z,(105+$x)/$z,(329+$y)/$z,(105+$x)/$z,(318+$y)/$z,(106+$x)/$z,(307+$y)/$z,(101+$x)/$z,(306+$y)/$z,(95+$x)/$z,(304+$y)/$z,(92+$x)/$z,(300+$y)/$z,(88+$x)/$z,(304+$y)/$z,(77+$x)/$z,(305+$y)/$z),'FD');
		//dairas charef
			//1-charef
			$this->color($data['920']);$this->Polygon(array((110+$x)/$z,(289+$y)/$z,(115+$x)/$z,(285+$y)/$z,(115+$x)/$z,(279+$y)/$z,(121+$x)/$z,(272+$y)/$z,(137+$x)/$z,(263+$y)/$z,(142+$x)/$z,(256+$y)/$z,(145+$x)/$z,(245+$y)/$z,(141+$x)/$z,(242+$y)/$z,(142+$x)/$z,(237+$y)/$z,(139+$x)/$z,(232+$y)/$z,(138+$x)/$z,(222+$y)/$z,(133+$x)/$z,(221+$y)/$z,(128+$x)/$z,(215+$y)/$z,(118+$x)/$z,(228+$y)/$z,(113+$x)/$z,(239+$y)/$z,(96+$x)/$z,(253+$y)/$z,(96+$x)/$z,(261+$y)/$z,(95+$x)/$z,(269+$y)/$z,(101+$x)/$z,(273+$y)/$z,(107+$x)/$z,(273+$y)/$z,(106+$x)/$z,(277+$y)/$z,(100+$x)/$z,(280+$y)/$z,(100+$x)/$z,(285+$y)/$z,(110+$x)/$z,(289+$y)/$z),'FD');
			//2-guedid
			$this->color($data['919']);$this->Polygon(array((81+$x)/$z,(193+$y)/$z,(74+$x)/$z,(209+$y)/$z,(62+$x)/$z,(211+$y)/$z,(65+$x)/$z,(227+$y)/$z,(67+$x)/$z,(278+$y)/$z,(77+$x)/$z,(275+$y)/$z,(78+$x)/$z,(265+$y)/$z,(96+$x)/$z,(261+$y)/$z,(96+$x)/$z,(253+$y)/$z,(113+$x)/$z,(239+$y)/$z,(118+$x)/$z,(228+$y)/$z,(128+$x)/$z,(215+$y)/$z,(127+$x)/$z,(204+$y)/$z,(123+$x)/$z,(196+$y)/$z,(118+$x)/$z,(196+$y)/$z,(114+$x)/$z,(198+$y)/$z,(81+$x)/$z,(193+$y)/$z),'FD');
			//3-benyaagoub
			$this->color($data['923']);$this->Polygon(array((115+$x)/$z,(285+$y)/$z,(123+$x)/$z,(288+$y)/$z,(128+$x)/$z,(288+$y)/$z,(128+$x)/$z,(283+$y)/$z,(129+$x)/$z,(280+$y)/$z,(133+$x)/$z,(279+$y)/$z,(138+$x)/$z,(282+$y)/$z,(145+$x)/$z,(277+$y)/$z,(152+$x)/$z,(269+$y)/$z,(157+$x)/$z,(264+$y)/$z,(155+$x)/$z,(259+$y)/$z,(142+$x)/$z,(256+$y)/$z,(137+$x)/$z,(263+$y)/$z,(121+$x)/$z,(272+$y)/$z,(115+$x)/$z,(279+$y)/$z,(115+$x)/$z,(285+$y)/$z),'FD');
		//dairas ainelbel
			//1-ainelbel
			$this->color($data['958']);$this->Polygon(array((155+$x)/$z,(259+$y)/$z,(157+$x)/$z,(264+$y)/$z,(162+$x)/$z,(261+$y)/$z,(170+$x)/$z,(260+$y)/$z,(175+$x)/$z,(254+$y)/$z,(180+$x)/$z,(257+$y)/$z,(180+$x)/$z,(265+$y)/$z,(180+$x)/$z,(280+$y)/$z,(176+$x)/$z,(281+$y)/$z,(177+$x)/$z,(289+$y)/$z,(181+$x)/$z,(293+$y)/$z,(181+$x)/$z,(299+$y)/$z,(177+$x)/$z,(302+$y)/$z,(177+$x)/$z,(307+$y)/$z,(187+$x)/$z,(322+$y)/$z,(194+$x)/$z,(314+$y)/$z,(203+$x)/$z,(309+$y)/$z,(210+$x)/$z,(302+$y)/$z,(207+$x)/$z,(296+$y)/$z,(209+$x)/$z,(291+$y)/$z,(206+$x)/$z,(283+$y)/$z,(200+$x)/$z,(282+$y)/$z,(201+$x)/$z,(277+$y)/$z,(211+$x)/$z,(273+$y)/$z,(212+$x)/$z,(259+$y)/$z,(214+$x)/$z,(255+$y)/$z,(201+$x)/$z,(263+$y)/$z,(194+$x)/$z,(258+$y)/$z,(188+$x)/$z,(260+$y)/$z,(185+$x)/$z,(256+$y)/$z,(184+$x)/$z,(251+$y)/$z,(177+$x)/$z,(248+$y)/$z,(174+$x)/$z,(243+$y)/$z,(164+$x)/$z,(249+$y)/$z,(155+$x)/$z,(259+$y)/$z),'FD');
			//2-moudjbara
			$this->color($data['957']);$this->Polygon(array((218+$x)/$z,(217+$y)/$z,(215+$x)/$z,(220+$y)/$z,(217+$x)/$z,(230+$y)/$z,(212+$x)/$z,(240+$y)/$z,(214+$x)/$z,(255+$y)/$z,(222+$x)/$z,(248+$y)/$z,(233+$x)/$z,(257+$y)/$z,(232+$x)/$z,(271+$y)/$z,(231+$x)/$z,(279+$y)/$z,(231+$x)/$z,(308+$y)/$z,(229+$x)/$z,(322+$y)/$z,(237+$x)/$z,(322+$y)/$z,(240+$x)/$z,(320+$y)/$z,(247+$x)/$z,(325+$y)/$z,(252+$x)/$z,(313+$y)/$z,(256+$x)/$z,(308+$y)/$z,(262+$x)/$z,(302+$y)/$z,(266+$x)/$z,(289+$y)/$z,(252+$x)/$z,(272+$y)/$z,(242+$x)/$z,(252+$y)/$z,(245+$x)/$z,(250+$y)/$z,(245+$x)/$z,(243+$y)/$z,(240+$x)/$z,(241+$y)/$z,(239+$x)/$z,(226+$y)/$z,(233+$x)/$z,(219+$y)/$z,(227+$x)/$z,(219+$y)/$z,(218+$x)/$z,(217+$y)/$z),'FD');
			//3-taadmit
			$this->color($data['965']);$this->Polygon(array((143+$x)/$z,(315+$y)/$z,(151+$x)/$z,(310+$y)/$z,(157+$x)/$z,(314+$y)/$z,(161+$x)/$z,(319+$y)/$z,(170+$x)/$z,(316+$y)/$z,(172+$x)/$z,(324+$y)/$z,(177+$x)/$z,(329+$y)/$z,(176+$x)/$z,(344+$y)/$z,(186+$x)/$z,(368+$y)/$z,(197+$x)/$z,(360+$y)/$z,(199+$x)/$z,(352+$y)/$z,(196+$x)/$z,(352+$y)/$z,(193+$x)/$z,(354+$y)/$z,(191+$x)/$z,(352+$y)/$z,(187+$x)/$z,(350+$y)/$z,(186+$x)/$z,(353+$y)/$z,(183+$x)/$z,(348+$y)/$z,(182+$x)/$z,(333+$y)/$z,(183+$x)/$z,(327+$y)/$z,(187+$x)/$z,(322+$y)/$z,(177+$x)/$z,(307+$y)/$z,(177+$x)/$z,(302+$y)/$z,(181+$x)/$z,(299+$y)/$z,(181+$x)/$z,(293+$y)/$z,(177+$x)/$z,(289+$y)/$z,(176+$x)/$z,(281+$y)/$z,(180+$x)/$z,(280+$y)/$z,(180+$x)/$z,(265+$y)/$z,(180+$x)/$z,(257+$y)/$z,(175+$x)/$z,(254+$y)/$z,(170+$x)/$z,(260+$y)/$z,(162+$x)/$z,(261+$y)/$z,(157+$x)/$z,(264+$y)/$z,(152+$x)/$z,(269+$y)/$z,(145+$x)/$z,(277+$y)/$z,(138+$x)/$z,(282+$y)/$z,(133+$x)/$z,(279+$y)/$z,(129+$x)/$z,(280+$y)/$z,(128+$x)/$z,(283+$y)/$z,(128+$x)/$z,(288+$y)/$z,(129+$x)/$z,(297+$y)/$z,(132+$x)/$z,(299+$y)/$z,(127+$x)/$z,(303+$y)/$z,(127+$x)/$z,(311+$y)/$z,(131+$x)/$z,(310+$y)/$z,(133+$x)/$z,(313+$y)/$z,(137+$x)/$z,(311+$y)/$z,(143+$x)/$z,(315+$y)/$z),'FD');
			//4-zakar
			$this->color($data['962']);$this->Polygon(array((214+$x)/$z,(255+$y)/$z,(212+$x)/$z,(259+$y)/$z,(211+$x)/$z,(273+$y)/$z,(201+$x)/$z,(277+$y)/$z,(200+$x)/$z,(282+$y)/$z,(206+$x)/$z,(283+$y)/$z,(209+$x)/$z,(291+$y)/$z,(207+$x)/$z,(296+$y)/$z,(210+$x)/$z,(302+$y)/$z,(215+$x)/$z,(293+$y)/$z,(222+$x)/$z,(281+$y)/$z,(227+$x)/$z,(268+$y)/$z,(231+$x)/$z,(279+$y)/$z,(232+$x)/$z,(271+$y)/$z,(233+$x)/$z,(257+$y)/$z,(222+$x)/$z,(248+$y)/$z,(214+$x)/$z,(255+$y)/$z),'FD');
	//D-mesaad
		//dairas messaad
			//1-mesaad
			$this->color($data['948']);$this->Polygon(array((247+$x)/$z,(325+$y)/$z,(251+$x)/$z,(333+$y)/$z,(252+$x)/$z,(342+$y)/$z,(249+$x)/$z,(346+$y)/$z,(246+$x)/$z,(349+$y)/$z,(242+$x)/$z,(352+$y)/$z,(240+$x)/$z,(346+$y)/$z,(234+$x)/$z,(340+$y)/$z,(230+$x)/$z,(334+$y)/$z,(229+$x)/$z,(322+$y)/$z,(237+$x)/$z,(322+$y)/$z,(240+$x)/$z,(320+$y)/$z,(247+$x)/$z,(325+$y)/$z),'FD');
			//2-deldoul
			$this->color($data['952']);$this->Polygon(array((301+$x)/$z,(446+$y)/$z,(314+$x)/$z,(429+$y)/$z,(264+$x)/$z,(395+$y)/$z,(262+$x)/$z,(389+$y)/$z,(250+$x)/$z,(380+$y)/$z,(242+$x)/$z,(352+$y)/$z,(240+$x)/$z,(346+$y)/$z,(234+$x)/$z,(340+$y)/$z,(230+$x)/$z,(334+$y)/$z,(229+$x)/$z,(322+$y)/$z,(231+$x)/$z,(308+$y)/$z,(231+$x)/$z,(279+$y)/$z,(227+$x)/$z,(268+$y)/$z,(222+$x)/$z,(281+$y)/$z,(215+$x)/$z,(293+$y)/$z,(210+$x)/$z,(302+$y)/$z,(203+$x)/$z,(309+$y)/$z,(194+$x)/$z,(314+$y)/$z,(187+$x)/$z,(322+$y)/$z,(183+$x)/$z,(327+$y)/$z,(182+$x)/$z,(333+$y)/$z,(183+$x)/$z,(348+$y)/$z,(186+$x)/$z,(353+$y)/$z,(187+$x)/$z,(350+$y)/$z,(191+$x)/$z,(352+$y)/$z,(193+$x)/$z,(354+$y)/$z,(196+$x)/$z,(352+$y)/$z,(199+$x)/$z,(352+$y)/$z,(197+$x)/$z,(360+$y)/$z,(186+$x)/$z,(368+$y)/$z,(197+$x)/$z,(372+$y)/$z,(203+$x)/$z,(372+$y)/$z,(207+$x)/$z,(370+$y)/$z,(211+$x)/$z,(372+$y)/$z,(216+$x)/$z,(380+$y)/$z,(223+$x)/$z,(381+$y)/$z,(237+$x)/$z,(399+$y)/$z,(260+$x)/$z,(411+$y)/$z,(301+$x)/$z,(446+$y)/$z),'FD');
			//3-selmana
			$this->color($data['954']);$this->Polygon(array((314+$x)/$z,(429+$y)/$z,(327+$x)/$z,(411+$y)/$z,(302+$x)/$z,(371+$y)/$z,(312+$x)/$z,(360+$y)/$z,(308+$x)/$z,(358+$y)/$z,(307+$x)/$z,(352+$y)/$z,(303+$x)/$z,(344+$y)/$z,(303+$x)/$z,(338+$y)/$z,(293+$x)/$z,(328+$y)/$z,(292+$x)/$z,(320+$y)/$z,(284+$x)/$z,(306+$y)/$z,(277+$x)/$z,(303+$y)/$z,(277+$x)/$z,(299+$y)/$z,(266+$x)/$z,(289+$y)/$z,(262+$x)/$z,(302+$y)/$z,(256+$x)/$z,(308+$y)/$z,(252+$x)/$z,(313+$y)/$z,(247+$x)/$z,(325+$y)/$z,(251+$x)/$z,(333+$y)/$z,(252+$x)/$z,(342+$y)/$z,(249+$x)/$z,(346+$y)/$z,(246+$x)/$z,(349+$y)/$z,(242+$x)/$z,(352+$y)/$z,(250+$x)/$z,(380+$y)/$z,(262+$x)/$z,(389+$y)/$z,(264+$x)/$z,(395+$y)/$z,(314+$x)/$z,(429+$y)/$z),'FD');
			//4-sedrahal
			$this->color($data['953']);$this->Polygon(array((186+$x)/$z,(368+$y)/$z,(192+$x)/$z,(393+$y)/$z,(197+$x)/$z,(397+$y)/$z,(197+$x)/$z,(403+$y)/$z,(213+$x)/$z,(404+$y)/$z,(228+$x)/$z,(412+$y)/$z,(241+$x)/$z,(419+$y)/$z,(254+$x)/$z,(432+$y)/$z,(267+$x)/$z,(446+$y)/$z,(275+$x)/$z,(461+$y)/$z,(290+$x)/$z,(465+$y)/$z,(301+$x)/$z,(446+$y)/$z,(260+$x)/$z,(411+$y)/$z,(237+$x)/$z,(399+$y)/$z,(223+$x)/$z,(381+$y)/$z,(216+$x)/$z,(380+$y)/$z,(211+$x)/$z,(372+$y)/$z,(207+$x)/$z,(370+$y)/$z,(203+$x)/$z,(372+$y)/$z,(197+$x)/$z,(372+$y)/$z,(186+$x)/$z,(368+$y)/$z),'FD');
			//5-getara
			$this->color($data['951']);$this->Polygon(array((290+$x)/$z,(465+$y)/$z,(311+$x)/$z,(474+$y)/$z,(328+$x)/$z,(481+$y)/$z,(345+$x)/$z,(492+$y)/$z,(373+$x)/$z,(520+$y)/$z,(380+$x)/$z,(535+$y)/$z,(389+$x)/$z,(544+$y)/$z,(392+$x)/$z,(555+$y)/$z,(400+$x)/$z,(567+$y)/$z,(485+$x)/$z,(590+$y)/$z,(473+$x)/$z,(522+$y)/$z,(443+$x)/$z,(525+$y)/$z,(422+$x)/$z,(510+$y)/$z,(381+$x)/$z,(472+$y)/$z,(360+$x)/$z,(480+$y)/$z,(325+$x)/$z,(430+$y)/$z,(337+$x)/$z,(427+$y)/$z,(327+$x)/$z,(411+$y)/$z,(314+$x)/$z,(429+$y)/$z,(301+$x)/$z,(446+$y)/$z,(290+$x)/$z,(465+$y)/$z),'FD');
		//dairas faid boutma
			//1-faid boutma
			$this->color($data['967']);$this->Polygon(array((306+$x)/$z,(247+$y)/$z,(288+$x)/$z,(248+$y)/$z,(283+$x)/$z,(250+$y)/$z,(279+$x)/$z,(247+$y)/$z,(276+$x)/$z,(242+$y)/$z,(271+$x)/$z,(240+$y)/$z,(268+$x)/$z,(243+$y)/$z,(269+$x)/$z,(248+$y)/$z,(274+$x)/$z,(250+$y)/$z,(272+$x)/$z,(255+$y)/$z,(258+$x)/$z,(244+$y)/$z,(251+$x)/$z,(246+$y)/$z,(249+$x)/$z,(250+$y)/$z,(245+$x)/$z,(250+$y)/$z,(242+$x)/$z,(252+$y)/$z,(252+$x)/$z,(272+$y)/$z,(266+$x)/$z,(289+$y)/$z,(277+$x)/$z,(299+$y)/$z,(277+$x)/$z,(303+$y)/$z,(284+$x)/$z,(306+$y)/$z,(298+$x)/$z,(295+$y)/$z,(301+$x)/$z,(291+$y)/$z,(310+$x)/$z,(288+$y)/$z,(317+$x)/$z,(280+$y)/$z,(303+$x)/$z,(262+$y)/$z,(306+$x)/$z,(247+$y)/$z),'FD');
			//2-amoura
			$this->color($data['968']);$this->Polygon(array((367+$x)/$z,(342+$y)/$z,(364+$x)/$z,(338+$y)/$z,(359+$x)/$z,(338+$y)/$z,(358+$x)/$z,(335+$y)/$z,(349+$x)/$z,(338+$y)/$z,(348+$x)/$z,(332+$y)/$z,(343+$x)/$z,(329+$y)/$z,(348+$x)/$z,(323+$y)/$z,(342+$x)/$z,(322+$y)/$z,(342+$x)/$z,(317+$y)/$z,(337+$x)/$z,(317+$y)/$z,(340+$x)/$z,(312+$y)/$z,(331+$x)/$z,(308+$y)/$z,(329+$x)/$z,(302+$y)/$z,(324+$x)/$z,(302+$y)/$z,(316+$x)/$z,(298+$y)/$z,(317+$x)/$z,(280+$y)/$z,(310+$x)/$z,(288+$y)/$z,(301+$x)/$z,(291+$y)/$z,(298+$x)/$z,(295+$y)/$z,(284+$x)/$z,(306+$y)/$z,(292+$x)/$z,(320+$y)/$z,(293+$x)/$z,(328+$y)/$z,(303+$x)/$z,(338+$y)/$z,(303+$x)/$z,(344+$y)/$z,(307+$x)/$z,(352+$y)/$z,(308+$x)/$z,(358+$y)/$z,(312+$x)/$z,(360+$y)/$z,(302+$x)/$z,(371+$y)/$z,(367+$x)/$z,(342+$y)/$z),'FD');
			//3-oumeladam
			$this->color($data['956']);$this->Polygon(array((473+$x)/$z,(522+$y)/$z,(473+$x)/$z,(498+$y)/$z,(489+$x)/$z,(463+$y)/$z,(486+$x)/$z,(449+$y)/$z,(493+$x)/$z,(442+$y)/$z,(473+$x)/$z,(434+$y)/$z,(462+$x)/$z,(434+$y)/$z,(458+$x)/$z,(424+$y)/$z,(443+$x)/$z,(425+$y)/$z,(439+$x)/$z,(418+$y)/$z,(435+$x)/$z,(420+$y)/$z,(432+$x)/$z,(416+$y)/$z,(419+$x)/$z,(416+$y)/$z,(416+$x)/$z,(414+$y)/$z,(411+$x)/$z,(405+$y)/$z,(407+$x)/$z,(402+$y)/$z,(398+$x)/$z,(398+$y)/$z,(384+$x)/$z,(395+$y)/$z,(378+$x)/$z,(389+$y)/$z,(364+$x)/$z,(384+$y)/$z,(356+$x)/$z,(378+$y)/$z,(356+$x)/$z,(374+$y)/$z,(369+$x)/$z,(373+$y)/$z,(379+$x)/$z,(360+$y)/$z,(388+$x)/$z,(360+$y)/$z,(386+$x)/$z,(353+$y)/$z,(372+$x)/$z,(354+$y)/$z,(366+$x)/$z,(349+$y)/$z,(367+$x)/$z,(342+$y)/$z,(302+$x)/$z,(371+$y)/$z,(327+$x)/$z,(411+$y)/$z,(337+$x)/$z,(427+$y)/$z,(325+$x)/$z,(430+$y)/$z,(360+$x)/$z,(480+$y)/$z,(381+$x)/$z,(472+$y)/$z,(422+$x)/$z,(510+$y)/$z,(443+$x)/$z,(525+$y)/$z,(473+$x)/$z,(522+$y)/$z),'FD');																	

	}			
	$this->RoundedRect($x-10,$y+42,30,55, 2, $style = '');
	$this->color(0);    $this->SetXY($x-10,$y+35);$this->cell(30,5,$data['titre'],0,0,'L',0,0);
	$this->color(0);    $this->SetXY($x-5,$this->GetY()+10);$this->cell(5,5,'',1,0,'C',1,0);$this->cell(15,5,$data['A'],0,0,'L',0,0);
	$this->color(1);    $this->SetXY($x-5,$this->GetY()+10);$this->cell(5,5,'',1,0,'C',1,0);$this->cell(15,5,$data['B'],0,0,'L',0,0);
	$this->color(11);   $this->SetXY($x-5,$this->GetY()+10);$this->cell(5,5,'',1,0,'C',1,0);$this->cell(15,5,$data['C'],0,0,'L',0,0);
	$this->color(101);  $this->SetXY($x-5,$this->GetY()+10);$this->cell(5,5,'',1,0,'C',1,0);$this->cell(15,5,$data['D'],0,0,'L',0,0);
	$this->color(1001); $this->SetXY($x-5,$this->GetY()+10);$this->cell(5,5,'',1,0,'C',1,0);$this->cell(15,5,$data['E'],0,0,'L',0,0);
	$this->color(0);    $this->SetXY($x+25,$this->GetY());$this->cell(40,5,'00km_____45km_____90km',0,0,'L',0,0);
	$this->color(0);    $this->SetXY($x+25,$this->GetY()+5);$this->cell(40,5,'Source: Dr TIBA',0,0,'L',0,0);
	$this->color(0);
	$this->SetFont('Times', 'BIU', 8);
	$this->SetDrawColor(255,0,0);
	$this->SetXY(140,$y-86);$this->cell(65,5,'La Wilaya De Djelfa',0,0,'C',0,0);
	$this->SetFont('Times', 'B', 8);
	$this->SetXY(160,$this->GetY()+5);$this->cell(55,5,'1-Ain Chouhada',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'2-Ain el Ibel',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'3-Ain Fekka',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'4-Ain Maabed',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'5-Ain Oussera',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'6-Amourah',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'7-Benhar',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'8-Beni Yacoub',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'9-Birine',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'10-Bouira Lahdab',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'11-Charef',0,0,'L',0,0);//$this->SetXY(35,$this->GetY()+5);$this->cell(65,5,'11',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'12-Dar Chioukh',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'13-Deldoul',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'14-Djelfa',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'15-Douis',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'16-El Guedid',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'17-El Idrissia',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'18-El Khemis',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'19-Faidh el Botma',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'20-Guernini',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'21-Guettara',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'22-Had-Sahary',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'23-Hassi Bahbah',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'24-Hassi el Euch',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'25-Hassi Fedoul',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'26-M Liliha',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'27-Messad',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'28-Mouadjebar',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'29-Oum Laadham',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'30-Sed Rahal',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'31-Selmana',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'32-Sidi Baizid',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'33-Sidi Ladjel',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'34-Tadmit',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'35-Zaafrane',0,0,'L',0,0);
	$this->SetXY(160,$this->GetY()+5);$this->cell(65,5,'36-Zaccar',0,0,'L',0,0);												
	$this->SetDrawColor(0,0,0);
	$this->SetFont('Times', 'B', 6);
	$this->SetXY(30,119);$this->cell(55,5,'*1',0,0,'L',0,0);
	$this->SetXY(55,107);$this->cell(65,5,'*2',0,0,'L',0,0);
	$this->SetXY(70,54);$this->cell(65,5,'*3',0,0,'L',0,0);
	$this->SetXY(54,87);$this->cell(65,5,'*4',0,0,'L',0,0);
	$this->SetXY(42,61);$this->cell(65,5,'*5',0,0,'L',0,0);
	$this->SetXY(90,118);$this->cell(65,5,'*6',0,0,'L',0,0);
	$this->SetXY(50,53);$this->cell(65,5,'*7',0,0,'L',0,0);
	$this->SetXY(42,105);$this->cell(65,5,'*8',0,0,'L',0,0);
	$this->SetXY(59,45);$this->cell(65,5,'*9',0,0,'L',0,0);
	$this->SetXY(51,68);$this->cell(65,5,'*10',0,0,'L',0,0);
	$this->SetXY(36,100);$this->cell(65,5,'*11',0,0,'L',0,0);//$this->SetXY(35,$this->GetY()+5);$this->cell(65,5,'11',0,0,'L',0,0);
	$this->SetXY(66,86);$this->cell(65,5,'*12',0,0,'L',0,0);
	$this->SetXY(65,132);$this->cell(65,5,'*13',0,0,'L',0,0);
	$this->SetXY(56,97);$this->cell(65,5,'*14',0,0,'L',0,0);
	$this->SetXY(35,119);$this->cell(65,5,'*15',0,0,'L',0,0);
	$this->SetXY(28,95);$this->cell(65,5,'*16',0,0,'L',0,0);
	$this->SetXY(27,110);$this->cell(65,5,'*17',0,0,'L',0,0);
	$this->SetXY(33,58);$this->cell(65,5,'*18',0,0,'L',0,0);
	$this->SetXY(80,105);$this->cell(65,5,'*19',0,0,'L',0,0);
	$this->SetXY(38,70);$this->cell(65,5,'*20',0,0,'L',0,0);
	$this->SetXY(110,175);$this->cell(65,5,'21-Guettara',0,0,'L',0,0);
	$this->SetXY(62,61);$this->cell(65,5,'*22',0,0,'L',0,0);
	$this->SetXY(45,77);$this->cell(65,5,'*23',0,0,'L',0,0);
	$this->SetXY(58,73,$this->GetY()+5);$this->cell(65,5,'*24',0,0,'L',0,0);
	$this->SetXY(14,55);$this->cell(65,5,'*25',0,0,'L',0,0);
	$this->SetXY(73,94);$this->cell(65,5,'*26',0,0,'L',0,0);
	$this->SetXY(68,122);$this->cell(65,5,'*27',0,0,'L',0,0);
	$this->SetXY(69,110);$this->cell(65,5,'*28',0,0,'L',0,0);
	$this->SetXY(100,148);$this->cell(65,5,'29-Oum Laadham',0,0,'L',0,0);
	$this->SetXY(59,137);$this->cell(65,5,'*30',0,0,'L',0,0);
	$this->SetXY(77,132);$this->cell(65,5,'*31',0,0,'L',0,0);
	$this->SetXY(62,80);$this->cell(65,5,'*32',0,0,'L',0,0);
	$this->SetXY(25,57);$this->cell(65,5,'*33',0,0,'L',0,0);
	$this->SetXY(45,112);$this->cell(65,5,'*34',0,0,'L',0,0);
	$this->SetXY(42,87);$this->cell(65,5,'*35',0,0,'L',0,0);
	$this->SetXY(63,105);$this->cell(65,5,'*36',0,0,'L',0,0);												
	$this->SetDrawColor(0,0,0);
	$this->SetFont('Times', 'B', 10);
    }
	
	
	
	
	
// ***************************************************barre code******************************************
//ne pas modifier
	function EAN13($x, $y, $barcode, $h=16, $w=.35)
	{
		$this->Barcode($x,$y,$barcode,$h,$w,13);
	}

	function UPC_A($x, $y, $barcode, $h=16, $w=.35)
	{
		$this->Barcode($x,$y,$barcode,$h,$w,12);
	}

	function GetCheckDigit($barcode)
	{
		//Calcule le chiffre de contrôle
		$sum=0;
		for($i=1;$i<=11;$i+=2)
			$sum+=3*$barcode[$i];
		for($i=0;$i<=10;$i+=2)
			$sum+=$barcode[$i];
		$r=$sum%10;
		if($r>0)
			$r=10-$r;
		return $r;
	}

	function TestCheckDigit($barcode)
	{
		//Vérifie le chiffre de contrôle
		$sum=0;
		for($i=1;$i<=11;$i+=2)
			$sum+=3*$barcode[$i];
		for($i=0;$i<=10;$i+=2)
			$sum+=$barcode[$i];
		return ($sum+$barcode[12])%10==0;
	}

	function Barcode($x, $y, $barcode, $h, $w, $len)
	{
		//Ajoute des 0 si nécessaire
		$barcode=str_pad($barcode,$len-1,'0',STR_PAD_LEFT);
		if($len==12)
			$barcode='0'.$barcode;
		//Ajoute ou teste le chiffre de contrôle
		if(strlen($barcode)==12)
			$barcode.=$this->GetCheckDigit($barcode);
		elseif(!$this->TestCheckDigit($barcode))
			$this->Error('Incorrect check digit');
		//Convertit les chiffres en barres
		$codes=array(
			'A'=>array(
				'0'=>'0001101','1'=>'0011001','2'=>'0010011','3'=>'0111101','4'=>'0100011',
				'5'=>'0110001','6'=>'0101111','7'=>'0111011','8'=>'0110111','9'=>'0001011'),
			'B'=>array(
				'0'=>'0100111','1'=>'0110011','2'=>'0011011','3'=>'0100001','4'=>'0011101',
				'5'=>'0111001','6'=>'0000101','7'=>'0010001','8'=>'0001001','9'=>'0010111'),
			'C'=>array(
				'0'=>'1110010','1'=>'1100110','2'=>'1101100','3'=>'1000010','4'=>'1011100',
				'5'=>'1001110','6'=>'1010000','7'=>'1000100','8'=>'1001000','9'=>'1110100')
			);
		$parities=array(
			'0'=>array('A','A','A','A','A','A'),
			'1'=>array('A','A','B','A','B','B'),
			'2'=>array('A','A','B','B','A','B'),
			'3'=>array('A','A','B','B','B','A'),
			'4'=>array('A','B','A','A','B','B'),
			'5'=>array('A','B','B','A','A','B'),
			'6'=>array('A','B','B','B','A','A'),
			'7'=>array('A','B','A','B','A','B'),
			'8'=>array('A','B','A','B','B','A'),
			'9'=>array('A','B','B','A','B','A')
			);
		$code='101';
		$p=$parities[$barcode[0]];
		for($i=1;$i<=6;$i++)
			$code.=$codes[$p[$i-1]][$barcode[$i]];
		$code.='01010';
		for($i=7;$i<=12;$i++)
			$code.=$codes['C'][$barcode[$i]];
		$code.='101';
		//Dessine les barres
		for($i=0;$i<strlen($code);$i++)
		{
			if($code[$i]=='1')
				$this->Rect($x+$i*$w,$y,$w,$h,'F');
		}
		//Imprime le texte sous le code-barres
		$this->SetFont('Arial','', 12);$this->SetFont('Arial','',12);
		//$this->Text($x,$y+$h+0.5/$this->k,substr($barcode,-$len));
	}
// ***************************************************barre code******************************************

}	