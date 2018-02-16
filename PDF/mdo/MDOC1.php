<?php
require('../invoice.php');
class MDOC extends PDF_Invoice
{ 
	 public $nomprenom ="tibaredha";
	 public $db_host="localhost";
	 public $db_name="mvc"; //probleme avec base de donnes  il faut change  gpts avec mvc   
     public $db_user="root";
     public $db_pass="";
	 public $utf8 = "" ;
	
//*************poure mettre le celle en verticale 	
	var $angle=0;

	function Rotate($angle,$x=-1,$y=-1)
	{
		if($x==-1)
			$x=$this->x;
		if($y==-1)
			$y=$this->y;
		if($this->angle!=0)
			$this->_out('Q');
		$this->angle=$angle;
		if($angle!=0)
		{
			$angle*=M_PI/180;
			$c=cos($angle);
			$s=sin($angle);
			$cx=$x*$this->k;
			$cy=($this->h-$y)*$this->k;
			$this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
		}
	}

	function RotatedText($x,$y,$txt,$angle)
	{
		//Text rotated around its origin
		$this->Rotate($angle,$x,$y);
		$this->Text($x,$y,$txt);
		//$this->Cell(30,8,$txt,1,1,'C');
		$this->Rotate(0);
	}
	function Rotatedcell($x1,$y1,$x,$y,$txt,$angle)
	{
		//Text rotated around its origin
		$this->Rotate($angle,$x1,$y1);
		$this->SetXY($x1,$y1);
		$this->Cell($x,$y,$txt,1,1,1,'L');
		$this->Rotate(0);
	}




	function RotatedImage($file,$x,$y,$w,$h,$angle)
	{
		//Image rotated around its upper-left corner
		$this->Rotate($angle,$x,$y);
		$this->Image($file,$x,$y,$w,$h);
		$this->Rotate(0);
	}
	//************************************************************//	
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
	function nbrtostring($tb_name,$colonename,$colonevalue,$resultatstring) 
	{
	if (is_numeric($colonevalue) and $colonevalue!=='0') 
	{ 
	$this->mysqlconnect();
    $result = mysql_query("SELECT * FROM $tb_name where $colonename=$colonevalue" );
    $row=mysql_fetch_object($result);
	$resultat=$row->$resultatstring;
	return $resultat;
	} 
	return $resultat2='-------';
	}
	function tibavac0($datejour1,$datejour2) 
	{
	$this->mysqlconnect();
	$requete="SELECT * FROM enfant where DateDC !='NULL'  and    (DateNais BETWEEN '$datejour1' AND '$datejour2') ";//
	$requete = @mysql_query($requete) or die($requete."<br>".mysql_error());
	$OP=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $OP;
	}
	function tibavac($datejour1,$datejour2) 
	{
	$this->mysqlconnect();
	$requete="SELECT * FROM enfant where  (DateNais BETWEEN '$datejour1' AND '$datejour2') ";//
	$requete = @mysql_query($requete) or die($requete."<br>".mysql_error());
	$OP=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $OP;
	}
	
	function tibavac1($datejour1,$datejour2,$vac) 
	{
	$this->mysqlconnect();
	$requete="SELECT * FROM enfant where DateNais BETWEEN '$datejour1' AND '$datejour2' and  $vac !=''  ";//
	$requete = @mysql_query($requete) or die($requete."<br>".mysql_error());
	$OP=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $OP;
	}
	function tibavac2($mois,$datejour1,$datejour2) 
	{
	    $this->SetXY(5,$this->GetY()+5);
		$this->cell(23,5,html_entity_decode(utf8_decode($mois)),1,0,'L',1,0);
		
		$nss=$this->tibavac($datejour1,$datejour2);
		$dec=$this->tibavac0($datejour1,$datejour2);
		$popc=$nss-$dec;
		$bcg=$this->tibavac1($datejour1,$datejour2,"BCG");
		$dtcp1=$this->tibavac1($datejour1,$datejour2,"DTCP1");
		$dtcp2=$this->tibavac1($datejour1,$datejour2,"DTCP2");
		$dtcp3=$this->tibavac1($datejour1,$datejour2,"DTCP3");
		$dtcpr=$this->tibavac1($datejour1,$datejour2,"DTCPR");
		$ro=$this->tibavac1($datejour1,$datejour2,"RO");
        
		
		
		
        $this->cell(23,5,$nss ,1,0,'C',0,0);
		$this->cell(17,5,$dec,1,0,'C',0,0);
		$this->cell(17,5,$popc,1,0,'C',0,0);
		$this->cell(15,5,$bcg,1,0,'C',0,0);
		$this->cell(15,5,round($bcg*100/$popc, 2),1,0,'C',0,0);
		$this->cell(15,5,$dtcp1,1,0,'C',0,0);
		$this->cell(15,5,round($dtcp1*100/$popc, 2),1,0,'C',0,0);
		$this->cell(15,5,$dtcp2,1,0,'C',0,0);
		$this->cell(15,5,round($dtcp2*100/$popc, 2),1,0,'C',0,0);
		$this->cell(15,5,$dtcp3,1,0,'C',0,0);
		$this->cell(15,5,round($dtcp3*100/$popc, 2),1,0,'C',0,0);
		$this->cell(15,5,$dtcpr,1,0,'C',0,0);
		$this->cell(15,5,round($dtcpr*100/$popc, 2),1,0,'C',0,0);
		$this->cell(15,5,$ro,1,0,'C',0,0);
		$this->cell(15,5,round($ro*100/$popc, 2),1,0,'C',0,0);
		$this->cell(15,5,"",1,0,'C',0,0);
		$this->cell(15,5,"",1,0,'C',0,0);
	
	}
	
	
	
	
	function mdocmd($structure,$mdo,$datejour1,$datejour2) //chsl
	{
	$this->mysqlconnect();
	$requete="SELECT * FROM mdo1 where 	STRUCTURE $structure and  MDO='$mdo' and  (DATEMDO BETWEEN '$datejour1' AND '$datejour2') ";//
	$requete = @mysql_query($requete) or die($requete."<br>".mysql_error());
	$OP=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $OP;
	}
	
	function mdocmd1($mdo,$sexe,$datejour1,$datejour2,$age1,$age2) //chsl
	{
	$this->mysqlconnect();
	$requete="SELECT * FROM mdo1 where MDO='$mdo' and  SEXE='$sexe'  and  (AGE BETWEEN '$age1' AND '$age2')  and  (DATEMDO BETWEEN '$datejour1' AND '$datejour2') ";//
	$requete = @mysql_query($requete) or die($requete."<br>".mysql_error());
	$OP=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $OP;
	}
	function mdomeningite($structure,$mdo,$datejour1,$datejour2,$age1,$age2) //chsl
	{
	$this->mysqlconnect();
	$requete="SELECT * FROM mdo1 where 	STRUCTURE $structure and  MDO='$mdo' and   (AGE BETWEEN '$age1' AND '$age2' and  (DATEMDO BETWEEN '$datejour1' AND '$datejour2')) ";//     
	$requete = @mysql_query($requete) or die($requete."<br>".mysql_error());
	$OP=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $OP;
	}
	
	function mdoiii($structure,$mdo,$commune,$sexe,$datejour1,$datejour2,$age1,$age2) //chsl
	{
	$this->mysqlconnect();
	$requete="SELECT * FROM mdo1 where 	STRUCTURE $structure and  MDO='$mdo' and COMMUNE='$commune' and  SEXE='$sexe' and  (AGE BETWEEN '$age1' AND '$age2' and  (DATEMDO BETWEEN '$datejour1' AND '$datejour2')) ";//     
	$requete = @mysql_query($requete) or die($requete."<br>".mysql_error());
	$OP=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $OP;
	}
	
	function mdoiiit($structure,$mdo,$sexe,$datejour1,$datejour2,$age1,$age2) //chsl
	{
	$this->mysqlconnect();
	$requete="SELECT * FROM mdo1 where 	STRUCTURE $structure  and  MDO='$mdo' and  SEXE='$sexe' and  (AGE BETWEEN '$age1' AND '$age2' and  (DATEMDO BETWEEN '$datejour1' AND '$datejour2')) ";//     
	$requete = @mysql_query($requete) or die($requete."<br>".mysql_error());
	$OP=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $OP;
	}
	
	function lignemdoiii($STRUCTURE,$MDO,$datejour1,$datejour2,$TITRE,$y) //chsl
	{
	$this->SetXY(5,$this->GetY()+$y);
    $this->cell(290,5,html_entity_decode(utf8_decode($TITRE)),1,0,'L',1,0);
    
	$this->mysqlconnect();
	$query = "SELECT * from COM where IDWIL='17000' and yes='1' order by COMMUNE "; 
	$this->SetXY(5,$this->GetY()+5); 
	$resultat=mysql_query($query);
	$totalmbr1=mysql_num_rows($resultat);
	while($row=mysql_fetch_object($resultat))
	{
	$val1=$this->mdoiii($STRUCTURE,$MDO,$row->IDCOM,'M',$datejour1,$datejour2,0,1);
	$val2=$this->mdoiii($STRUCTURE,$MDO,$row->IDCOM,'F',$datejour1,$datejour2,0,1);
	
	$val3=$this->mdoiii($STRUCTURE,$MDO,$row->IDCOM,'M',$datejour1,$datejour2,2,4);
	$val4=$this->mdoiii($STRUCTURE,$MDO,$row->IDCOM,'F',$datejour1,$datejour2,2,4);
	
	$val5=$this->mdoiii($STRUCTURE,$MDO,$row->IDCOM,'M',$datejour1,$datejour2,5,9);
	$val6=$this->mdoiii($STRUCTURE,$MDO,$row->IDCOM,'F',$datejour1,$datejour2,5,9);
	
	$val7=$this->mdoiii($STRUCTURE,$MDO,$row->IDCOM,'M',$datejour1,$datejour2,10,14);
	$val8=$this->mdoiii($STRUCTURE,$MDO,$row->IDCOM,'F',$datejour1,$datejour2,10,14);
	
	$val9=$this->mdoiii($STRUCTURE,$MDO,$row->IDCOM,'M',$datejour1,$datejour2,15,19);
	$val10=$this->mdoiii($STRUCTURE,$MDO,$row->IDCOM,'F',$datejour1,$datejour2,15,19);
	
	$val11=$this->mdoiii($STRUCTURE,$MDO,$row->IDCOM,'M',$datejour1,$datejour2,20,44);
	$val12=$this->mdoiii($STRUCTURE,$MDO,$row->IDCOM,'F',$datejour1,$datejour2,20,44);
	
	$val13=$this->mdoiii($STRUCTURE,$MDO,$row->IDCOM,'M',$datejour1,$datejour2,45,64);
	$val14=$this->mdoiii($STRUCTURE,$MDO,$row->IDCOM,'F',$datejour1,$datejour2,45,64);
	
	$val15=$this->mdoiii($STRUCTURE,$MDO,$row->IDCOM,'M',$datejour1,$datejour2,65,99);
	$val16=$this->mdoiii($STRUCTURE,$MDO,$row->IDCOM,'F',$datejour1,$datejour2,65,99);
	
	
	
	
	
	$this->cell(46,5,$row->COMMUNE,1,0,'l',1,0);
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
	$valt1=$this->mdoiiit($STRUCTURE,$MDO,'M',$datejour1,$datejour2,0,1);
	$valt2=$this->mdoiiit($STRUCTURE,$MDO,'F',$datejour1,$datejour2,0,1);
	
	$valt3=$this->mdoiiit($STRUCTURE,$MDO,'M',$datejour1,$datejour2,2,4);
	$valt4=$this->mdoiiit($STRUCTURE,$MDO,'F',$datejour1,$datejour2,2,4);
	
	$valt5=$this->mdoiiit($STRUCTURE,$MDO,'M',$datejour1,$datejour2,5,9);
	$valt6=$this->mdoiiit($STRUCTURE,$MDO,'F',$datejour1,$datejour2,5,9);
	
	$valt7=$this->mdoiiit($STRUCTURE,$MDO,'M',$datejour1,$datejour2,10,14);
	$valt8=$this->mdoiiit($STRUCTURE,$MDO,'F',$datejour1,$datejour2,10,14);
	
	$valt9=$this->mdoiiit($STRUCTURE,$MDO,'M',$datejour1,$datejour2,15,19);
	$valt10=$this->mdoiiit($STRUCTURE,$MDO,'F',$datejour1,$datejour2,15,19);
	
	$valt11=$this->mdoiiit($STRUCTURE,$MDO,'M',$datejour1,$datejour2,20,44);
	$valt12=$this->mdoiiit($STRUCTURE,$MDO,'F',$datejour1,$datejour2,20,44);
	
	$valt13=$this->mdoiiit($STRUCTURE,$MDO,'M',$datejour1,$datejour2,45,64);
	$valt14=$this->mdoiiit($STRUCTURE,$MDO,'F',$datejour1,$datejour2,45,64);
	
	$valt15=$this->mdoiiit($STRUCTURE,$MDO,'M',$datejour1,$datejour2,65,99);
	$valt16=$this->mdoiiit($STRUCTURE,$MDO,'F',$datejour1,$datejour2,65,99);
	
	
	$this->setxy(5,$this->gety());
	$this->cell(46,5,'Total Commune : ',1,0,'l',1,0);//.$totalmbr1
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
	}
	
	function lignemdoiiia($STRUCTURE,$MDO,$datejour1,$datejour2,$TITRE,$y) //chsl
	{
	
	$valt1=$this->mdoiiit($STRUCTURE,$MDO,'M',$datejour1,$datejour2,0,1);
	$valt2=$this->mdoiiit($STRUCTURE,$MDO,'F',$datejour1,$datejour2,0,1);
	
	$valt3=$this->mdoiiit($STRUCTURE,$MDO,'M',$datejour1,$datejour2,2,4);
	$valt4=$this->mdoiiit($STRUCTURE,$MDO,'F',$datejour1,$datejour2,2,4);
	
	$valt5=$this->mdoiiit($STRUCTURE,$MDO,'M',$datejour1,$datejour2,5,9);
	$valt6=$this->mdoiiit($STRUCTURE,$MDO,'F',$datejour1,$datejour2,5,9);
	
	$valt7=$this->mdoiiit($STRUCTURE,$MDO,'M',$datejour1,$datejour2,10,14);
	$valt8=$this->mdoiiit($STRUCTURE,$MDO,'F',$datejour1,$datejour2,10,14);
	
	$valt9=$this->mdoiiit($STRUCTURE,$MDO,'M',$datejour1,$datejour2,15,19);
	$valt10=$this->mdoiiit($STRUCTURE,$MDO,'F',$datejour1,$datejour2,15,19);
	
	$valt11=$this->mdoiiit($STRUCTURE,$MDO,'M',$datejour1,$datejour2,20,44);
	$valt12=$this->mdoiiit($STRUCTURE,$MDO,'F',$datejour1,$datejour2,20,44);
	
	$valt13=$this->mdoiiit($STRUCTURE,$MDO,'M',$datejour1,$datejour2,45,64);
	$valt14=$this->mdoiiit($STRUCTURE,$MDO,'F',$datejour1,$datejour2,45,64);
	
	$valt15=$this->mdoiiit($STRUCTURE,$MDO,'M',$datejour1,$datejour2,65,99);
	$valt16=$this->mdoiiit($STRUCTURE,$MDO,'F',$datejour1,$datejour2,65,99);
	
	
	$this->setxy(5,$this->gety());
	$this->SetFont('Arial', '', 7.5);
	$this->cell(46,5,$TITRE,1,0,'l',1,0);//.$totalmbr1
	$this->SetFont('Arial', 'B', 9);
	$this->cell(13,5,$valt1,1,0,'C',0,0);
	$this->cell(13,5,$valt2,1,0,'C',0,0);
	$this->cell(13,5,$valt3,1,0,'C',0,0);
	$this->cell(13,5,$valt4,1,0,'C',0,0);
	$this->cell(13,5,$valt5,1,0,'C',0,0);
	$this->cell(13,5,$valt6,1,0,'C',0,0);
	$this->cell(13,5,$valt7,1,0,'C',0,0);
	$this->cell(13,5,$valt8,1,0,'C',0,0);
	$this->cell(13,5,$valt9,1,0,'C',0,0);
	$this->cell(13,5,$valt10,1,0,'C',0,0);
	$this->cell(13,5,$valt11,1,0,'C',0,0);
	$this->cell(13,5,$valt12,1,0,'C',0,0);
	$this->cell(13,5,$valt13,1,0,'C',0,0);
	$this->cell(13,5,$valt14,1,0,'C',0,0);
	$this->cell(13,5,$valt15,1,0,'C',0,0);
	$this->cell(13,5,$valt16,1,0,'C',0,0);
	$this->cell(12,5,$valt1+$valt3+$valt5+$valt7+$valt9+$valt11+$valt13+$valt15,1,0,'C',1,0);
	$this->cell(12,5,$valt2+$valt4+$valt6+$valt8+$valt10+$valt12+$valt14+$valt16,1,0,'C',1,0);
	$this->cell(12,5,$valt1+$valt3+$valt5+$valt7+$valt9+$valt11+$valt13+$valt15+$valt2+$valt4+$valt6+$valt8+$valt10+$valt12+$valt14+$valt16,1,0,'C',1,0);
	}
	
	
	
	
	
	
	
	
	
    
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//*************************************************partie non prise en compte jusqua ce jours **************************************************************//
	
	
	
	
	
	
	
	
	
	
	
	
	function stringtostring($tb_name,$colonename,$colonevalue,$resultatstring) 
	{
	if (isset($colonevalue) and $colonevalue!=='' ) 
	{ 
	$this->mysqlconnect();
    $result = mysql_query("SELECT * FROM $tb_name where $colonename='$colonevalue'" );
    $row=mysql_fetch_object($result);
	$resultat=$row->$resultatstring;
	return $resultat;
	} 
	return $resultat2='-------';
	}
	function valeurmoisdeces($SRS,$TBL,$COLONE1,$COLONE2,$DATEJOUR1,$DATEJOUR2,$VALEUR2,$STR,$STRUCTURED) 
	{
	$this->mysqlconnect();
	$sql = " select * from $TBL where $COLONE1 BETWEEN '$DATEJOUR1' AND '$DATEJOUR2' and ($COLONE2='$VALEUR2') and (STRUCTURED $STRUCTURED)";
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$OP=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $OP;
	}
	function valeurmoisdecest($SRS,$TBL,$COLONE1,$COLONE2,$DATEJOUR1,$DATEJOUR2,$VALEUR2,$STR,$STRUCTURED) 
	{
	$this->mysqlconnect();
	$sql = " select * from $TBL where $COLONE1 BETWEEN '$DATEJOUR1' AND '$DATEJOUR2' and (STRUCTURED $STRUCTURED)";
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$OP=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $OP;
	}	
	

	function tblparcommune($dnrdon,$datejour1,$datejour2,$STRUCTURED) 
	{    
		$this->SetFont('Times', 'B', 10);
		$h=35;
		$this->SetXY(8,$h);$this->cell(15,5,"IDCOM",1,0,'C',1,0);
		$this->cell(90,5,"Commune",1,0,'C',1,0);
	    $this->cell(20,5,"Superficie",1,0,'C',1,0);
		$this->cell(30,5,"Population 2008",1,0,'C',1,0);
		$this->cell(20,5,$dnrdon,1,0,'C',1,0);
		$this->cell(20,5,"Tx mortalite",1,0,'C',1,0);
		$this->SetXY(8,$h+5);
		$IDWIL=17000;
		$ANNEE='2007';
		$this->mysqlconnect();
		$query="SELECT * FROM com where IDWIL='$IDWIL' and yes='1' order by COMMUNE "; //    % %will search form 0-9,a-z            
		$resultat=mysql_query($query);
		$totalmbr1=mysql_num_rows($resultat);
		while($row=mysql_fetch_object($resultat))
		{
			$this->SetFont('Times', '', 10);
			$this->cell(15,4,trim($row->IDCOM),1,0,'C',0);
			$this->cell(90,4,trim($row->COMMUNE),1,0,'L',0);
			$this->cell(20,4,trim($row->SUPER),1,0,'L',0);
			$this->cell(30,4,trim($row->POPULATION),1,0,'L',0);
			$this->cell(20,4,$this->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$datejour1,$datejour2,trim($row->IDCOM),'',$STRUCTURED),1,0,'L',0);
			$this->cell(20,4,round(($this->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$datejour1,$datejour2,trim($row->IDCOM),'',$STRUCTURED)*1000)/$row->POPULATION,3),1,0,'L',0);
			$this->SetXY(8,$this->GetY()+4); 
		}
		$req="SELECT SUM(SUPER) AS total FROM com WHERE IDWIL='$IDWIL' and yes='1'";
		$query1 = mysql_query($req);   
		$rs = mysql_fetch_assoc($query1);
		$req1="SELECT SUM(POPULATION) AS total1 FROM com WHERE IDWIL='$IDWIL' and yes='1'";
		$query11 = mysql_query($req1);   
		$rs1 = mysql_fetch_assoc($query11);
		$this->SetXY(8,$this->GetY());$this->cell(15,5,"Total",1,0,'C',1,0);	  
		$this->cell(90,5,$totalmbr1."  Communes",1,0,'C',1,0);	  
		$this->cell(20,5,round($rs['total'],2),1,0,'C',1,0);	  
	    $this->cell(30,5,round($rs1['total1'],2),1,0,'C',1,0);	  
		$this->cell(20,5,$this->valeurmoisdecest('','deceshosp','DINS','COMMUNER',$datejour1,$datejour2,'','',$STRUCTURED),1,0,'C',1,0);	  
		$this->cell(20,5,round(($this->valeurmoisdecest('','deceshosp','DINS','COMMUNER',$datejour1,$datejour2,'','',$STRUCTURED)*1000)/round($rs1['total1'],3),3),1,0,'C',1,0);	  
	}
	function mdocomm($DATEJOUR1,$DATEJOUR2,$COMMUNER,$STRUCTURED,$MDO) 
	{
	$this->mysqlconnect();
	$sql = " select * from mdo1 where DATEMDO BETWEEN '$DATEJOUR1' AND '$DATEJOUR2' and COMMUNE=$COMMUNER and STRUCTURE $STRUCTURED  and MDO=$MDO ";
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$OP=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $OP;
	}
	function datasig($datejour1,$datejour2,$STRUCTURED,$MDO) 
	{
	$data = array(
	"titre"=> 'Nombre De '.ucwords(strtolower($this->nbrtostring('mdo','id',$MDO,'mdo'))) ,
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
	function AGESEXE($colone1,$colone2,$colone3,$datejour1,$datejour2,$SEXEDNR,$STRUCTURED,$MDO)
	{
	$this->mysqlconnect();
	$sql = " select * from mdo1 where  ($colone1 >=$colone2  and $colone1 <=$colone3)  and (DATEMDO BETWEEN '$datejour1' AND '$datejour2') and (SEXE='$SEXEDNR' and STRUCTURE $STRUCTURED AND MDO=$MDO   )          ";
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$collecte=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $collecte;
	}
	function dataagesexe($x,$y,$colone1,$TABLE,$DINS,$COMMUNER,$datejour1,$datejour2,$STRUCTURED,$MDO) 
	{
	$T2F20=array(
	"xt" => $x,
	"yt" => $y,
	"wc" => "",
	"hc" => "",
	"tt" => ucwords(strtolower ($this->nbrtostring('mdo','id',$MDO,'mdo'))) ." par tranche d'age et sexe",
	"tc" => "Sexe",
	"tc1" =>"M",
	"tc3" =>"F",
	"tc5" =>"Total",
	"1M"  => $this->AGESEXE($colone1,0,4,$datejour1,$datejour2,'M',$STRUCTURED,$MDO),    "1F"  => $this->AGESEXE($colone1,0,4,$datejour1,$datejour2,'F',$STRUCTURED,$MDO),
	"2M"  => $this->AGESEXE($colone1,5,9,$datejour1,$datejour2,'M',$STRUCTURED,$MDO),    "2F"  => $this->AGESEXE($colone1,5,9,$datejour1,$datejour2,'F',$STRUCTURED,$MDO),
	"3M"  => $this->AGESEXE($colone1,10,14,$datejour1,$datejour2,'M',$STRUCTURED,$MDO),  "3F"  => $this->AGESEXE($colone1,10,14,$datejour1,$datejour2,'F',$STRUCTURED,$MDO),
	"4M"  => $this->AGESEXE($colone1,15,19,$datejour1,$datejour2,'M',$STRUCTURED,$MDO),  "4F"  => $this->AGESEXE($colone1,15,19,$datejour1,$datejour2,'F',$STRUCTURED,$MDO),
	"5M"  => $this->AGESEXE($colone1,20,24,$datejour1,$datejour2,'M',$STRUCTURED,$MDO),  "5F"  => $this->AGESEXE($colone1,20,24,$datejour1,$datejour2,'F',$STRUCTURED,$MDO),
	"6M"  => $this->AGESEXE($colone1,25,29,$datejour1,$datejour2,'M',$STRUCTURED,$MDO),  "6F"  => $this->AGESEXE($colone1,25,29,$datejour1,$datejour2,'F',$STRUCTURED,$MDO),
	"7M"  => $this->AGESEXE($colone1,30,34,$datejour1,$datejour2,'M',$STRUCTURED,$MDO),  "7F"  => $this->AGESEXE($colone1,30,34,$datejour1,$datejour2,'F',$STRUCTURED,$MDO),
	"8M"  => $this->AGESEXE($colone1,35,39,$datejour1,$datejour2,'M',$STRUCTURED,$MDO),  "8F"  => $this->AGESEXE($colone1,35,39,$datejour1,$datejour2,'F',$STRUCTURED,$MDO),
	"9M"  => $this->AGESEXE($colone1,40,44,$datejour1,$datejour2,'M',$STRUCTURED,$MDO),  "9F"  => $this->AGESEXE($colone1,40,44,$datejour1,$datejour2,'F',$STRUCTURED,$MDO),
	"10M" => $this->AGESEXE($colone1,45,49,$datejour1,$datejour2,'M',$STRUCTURED,$MDO),  "10F" => $this->AGESEXE($colone1,45,49,$datejour1,$datejour2,'F',$STRUCTURED,$MDO),
	"11M" => $this->AGESEXE($colone1,50,54,$datejour1,$datejour2,'M',$STRUCTURED,$MDO),  "11F" => $this->AGESEXE($colone1,50,54,$datejour1,$datejour2,'F',$STRUCTURED,$MDO),
	"12M" => $this->AGESEXE($colone1,55,59,$datejour1,$datejour2,'M',$STRUCTURED,$MDO),  "12F" => $this->AGESEXE($colone1,55,59,$datejour1,$datejour2,'F',$STRUCTURED,$MDO),
	"13M" => $this->AGESEXE($colone1,60,64,$datejour1,$datejour2,'M',$STRUCTURED,$MDO),  "13F" => $this->AGESEXE($colone1,60,64,$datejour1,$datejour2,'F',$STRUCTURED,$MDO),
	"14M" => $this->AGESEXE($colone1,65,69,$datejour1,$datejour2,'M',$STRUCTURED,$MDO),  "14F" => $this->AGESEXE($colone1,65,69,$datejour1,$datejour2,'F',$STRUCTURED,$MDO),
	"15M" => $this->AGESEXE($colone1,70,74,$datejour1,$datejour2,'M',$STRUCTURED,$MDO),  "15F" => $this->AGESEXE($colone1,70,74,$datejour1,$datejour2,'F',$STRUCTURED,$MDO),
	"16M" => $this->AGESEXE($colone1,75,79,$datejour1,$datejour2,'M',$STRUCTURED,$MDO),  "16F" => $this->AGESEXE($colone1,75,79,$datejour1,$datejour2,'F',$STRUCTURED,$MDO),
	"17M" => $this->AGESEXE($colone1,80,84,$datejour1,$datejour2,'M',$STRUCTURED,$MDO),  "17F" => $this->AGESEXE($colone1,80,84,$datejour1,$datejour2,'F',$STRUCTURED,$MDO),
	"18M" => $this->AGESEXE($colone1,85,89,$datejour1,$datejour2,'M',$STRUCTURED,$MDO),  "18F" => $this->AGESEXE($colone1,85,89,$datejour1,$datejour2,'F',$STRUCTURED,$MDO),
	"19M" => $this->AGESEXE($colone1,90,94,$datejour1,$datejour2,'M',$STRUCTURED,$MDO),  "19F" => $this->AGESEXE($colone1,90,94,$datejour1,$datejour2,'F',$STRUCTURED,$MDO),
	"20M" => $this->AGESEXE($colone1,95,150,$datejour1,$datejour2,'M',$STRUCTURED,$MDO), "20F" => $this->AGESEXE($colone1,95,150,$datejour1,$datejour2,'F',$STRUCTURED,$MDO),			
	"T" =>'1',
	"tl" =>"Age",
	"1MF"  => '00-04',  
	"2MF"  => '05-09',   
	"3MF"  => '10-14',  
	"4MF"  => '15-19',   
	"5MF"  => '20-24',  
	"6MF"  => '25-29',   
	"7MF"  => '30-34',  
	"8MF"  => '35-39',   
	"9MF"  => '40-44',   
	"10MF" => '45-49',  
	"11MF" => '50-54',  
	"12MF" => '55-59', 
	"13MF" => '60-64',  
	"14MF" => '65-69', 
	"15MF" => '70-74',  
	"16MF" => '75-79',  
	"17MF" => '80-84',  
	"18MF" => '85-89', 
	"19MF" => '90-94', 
	"20MF" => '95-99'  
	);
	return $T2F20;
	}
	function T2F20($data,$datejour1,$datejour2)  //tableau  corige le 15/08/2016
    {
	$this->SetXY($data['xt'],$data['yt']);     $this->cell(105,05,$data['tt'],1,0,'L',1,0);
	$this->SetXY($data['xt'],$this->GetY()+5); $this->cell(15,15,$data['tl'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(75+15,10,$data['tc'],1,0,'C',1,0);$this->SetXY($data['xt']+15,$this->GetY()+10);$this->cell(30,5,$data['tc1'],1,0,'C',1,0); $this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['tc3'],1,0,'C',1,0);$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['tc5'],1,0,'C',1,0);$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,'P %',1,0,'C',1,0);
	
	$TM=$data['1M']+$data['2M']+$data['3M']+$data['4M']+$data['5M']+$data['6M']+$data['7M']+$data['8M']+$data['9M']+$data['10M']+$data['11M']+$data['12M']+$data['13M']+$data['14M']+$data['15M']+$data['16M']+$data['17M']+$data['18M']+$data['19M']+$data['20M'];
	$TF=$data['1F']+$data['2F']+$data['3F']+$data['4F']+$data['5F']+$data['6F']+$data['7F']+$data['8F']+$data['9F']+$data['10F']+$data['11F']+$data['12F']+$data['13F']+$data['14F']+$data['15F']+$data['16F']+$data['17F']+$data['18F']+$data['19F']+$data['20F'];
	if ($TM+$TF > 0){$T=$TM+$TF;}else{$T=1;}
	$datamf = array($data['1M']+$data['1F'],
	                $data['2M']+$data['2F'],
					$data['3M']+$data['3F'],
					$data['4M']+$data['4F'],
					$data['5M']+$data['5F'],
					$data['6M']+$data['6F'],
					$data['7M']+$data['7F'],
					$data['8M']+$data['8F'],
					$data['9M']+$data['9F'],
					$data['10M']+$data['10F'],
					$data['11M']+$data['11F'],
					$data['12M']+$data['12F'],
					$data['13M']+$data['13F'],
					$data['14M']+$data['14F'],
					$data['15M']+$data['15F'],
					$data['16M']+$data['16F'],
					$data['17M']+$data['17F'],
					$data['18M']+$data['18F'],
					$data['19M']+$data['19F'],
					$data['20M']+$data['20F']);

	$this->SetXY($data['xt'],$this->GetY()+5);$this->cell(15,5,$data['1MF'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,$data['1M'],1,0,'C',0,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['1F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['1M']+$data['1F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['1M']+$data['1F'])/$T)*100),2).' %',1,0,'R',1,0);        
	
	$this->SetXY($data['xt'],$this->GetY()+5);$this->cell(15,5,$data['2MF'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,$data['2M'],1,0,'C',0,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['2F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['2M']+$data['2F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['2M']+$data['2F'])/$T)*100),2).' %',1,0,'R',1,0);        
 
	$this->SetXY($data['xt'],$this->GetY()+5);$this->cell(15,5,$data['3MF'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,$data['3M'],1,0,'C',0,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['3F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['3M']+$data['3F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['3M']+$data['3F'])/$T)*100),2).' %',1,0,'R',1,0);        
	 
	$this->SetXY($data['xt'],$this->GetY()+5);$this->cell(15,5,$data['4MF'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,$data['4M'],1,0,'C',0,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['4F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['4M']+$data['4F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['4M']+$data['4F'])/$T)*100),2).' %',1,0,'R',1,0);        
	
	$this->SetXY($data['xt'],$this->GetY()+5);$this->cell(15,5,$data['5MF'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,$data['5M'],1,0,'C',0,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['5F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['5M']+$data['5F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['5M']+$data['5F'])/$T)*100),2).' %',1,0,'R',1,0);        
	 
	$this->SetXY($data['xt'],$this->GetY()+5);$this->cell(15,5,$data['6MF'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,$data['6M'],1,0,'C',0,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['6F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['6M']+$data['6F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['6M']+$data['6F'])/$T)*100),2).' %',1,0,'R',1,0);        
	
	$this->SetXY($data['xt'],$this->GetY()+5);$this->cell(15,5,$data['7MF'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,$data['7M'],1,0,'C',0,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['7F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['7M']+$data['7F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['7M']+$data['7F'])/$T)*100),2).' %',1,0,'R',1,0);        
 
	$this->SetXY($data['xt'],$this->GetY()+5);$this->cell(15,5,$data['8MF'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,$data['8M'],1,0,'C',0,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['8F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['8M']+$data['8F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['8M']+$data['8F'])/$T)*100),2).' %',1,0,'R',1,0);        
	
	$this->SetXY($data['xt'],$this->GetY()+5);$this->cell(15,5,$data['9MF'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,$data['9M'],1,0,'C',0,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['9F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['9M']+$data['9F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['9M']+$data['9F'])/$T)*100),2).' %',1,0,'R',1,0);        
	
	$this->SetXY($data['xt'],$this->GetY()+5);$this->cell(15,5,$data['10MF'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,$data['10M'],1,0,'C',0,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['10F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['10M']+$data['10F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['10M']+$data['10F'])/$T)*100),2).' %',1,0,'R',1,0);        
	
	$this->SetXY($data['xt'],$this->GetY()+5);$this->cell(15,5,$data['11MF'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,$data['11M'],1,0,'C',0,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['11F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['11M']+$data['11F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['11M']+$data['11F'])/$T)*100),2).' %',1,0,'R',1,0);        
	
	$this->SetXY($data['xt'],$this->GetY()+5);$this->cell(15,5,$data['12MF'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,$data['12M'],1,0,'C',0,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['12F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['12M']+$data['12F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['12M']+$data['12F'])/$T)*100),2).' %',1,0,'R',1,0);        
	
	$this->SetXY($data['xt'],$this->GetY()+5);$this->cell(15,5,$data['13MF'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,$data['13M'],1,0,'C',0,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['13F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['13M']+$data['13F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['13M']+$data['13F'])/$T)*100),2).' %',1,0,'R',1,0);        
	
	$this->SetXY($data['xt'],$this->GetY()+5);$this->cell(15,5,$data['14MF'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,$data['14M'],1,0,'C',0,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['14F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['14M']+$data['14F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['14M']+$data['14F'])/$T)*100),2).' %',1,0,'R',1,0);        
	
	$this->SetXY($data['xt'],$this->GetY()+5);$this->cell(15,5,$data['15MF'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,$data['15M'],1,0,'C',0,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['15F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['15M']+$data['15F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['15M']+$data['15F'])/$T)*100),2).' %',1,0,'R',1,0);        
	
	$this->SetXY($data['xt'],$this->GetY()+5); $this->cell(15,5,$data['16MF'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,$data['16M'],1,0,'C',0,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['16F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['16M']+$data['16F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['16M']+$data['16F'])/$T)*100),2).' %',1,0,'R',1,0);        
	
	$this->SetXY($data['xt'],$this->GetY()+5); $this->cell(15,5,$data['17MF'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,$data['17M'],1,0,'C',0,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['17F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['17M']+$data['17F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['17M']+$data['17F'])/$T)*100),2).' %',1,0,'R',1,0);        
	
	$this->SetXY($data['xt'],$this->GetY()+5); $this->cell(15,5,$data['18MF'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,$data['18M'],1,0,'C',0,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['18F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['18M']+$data['18F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['18M']+$data['18F'])/$T)*100),2).' %',1,0,'R',1,0);        
	
	$this->SetXY($data['xt'],$this->GetY()+5); $this->cell(15,5,$data['19MF'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,$data['19M'],1,0,'C',0,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['19F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['19M']+$data['19F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['19M']+$data['19F'])/$T)*100),2).' %',1,0,'R',1,0);        
	
	$this->SetXY($data['xt'],$this->GetY()+5); $this->cell(15,5,$data['20MF'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,$data['20M'],1,0,'C',0,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['20F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['20M']+$data['20F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['20M']+$data['20F'])/$T)*100),2).' %',1,0,'R',1,0);        
	
	$this->SetXY($data['xt'],$this->GetY()+5);$this->cell(15,5,'Total',1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,$TM,1,0,'C',1,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$TF,1,0,'C',1,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$T,1,0,'C',1,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($TM+$TF)/$T)*100),2).' %',1,0,'R',1,0); 	                                                                
	
	$this->SetXY($data['xt'],$this->GetY()+5); $this->cell(15,5,'P %',1,0,'C',1,0);      
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,round(($TM/$T)*100,2),1,0,'C',1,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,round(($TF/$T)*100,2),1,0,'C',1,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,round(($T/$T)*100,2).' %',1,0,'C',1,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,'***',1,0,'C',1,0); 	                                                                
	
	$this->SetXY(5,25+10);$this->cell(285,5,html_entity_decode(utf8_decode("Cette étude a porté sur ".$T." cas survenus durant la periode du ".$this->dateUS2FR($datejour1)." au ".$this->dateUS2FR($datejour2)." au niveau des communes ")),0,0,'L',0);
	$this->SetXY(5,175);  $this->cell(285,5,html_entity_decode(utf8_decode("1-Répartition des cas par sexe : ")),0,0,'L',0);
	$this->SetXY(5,175+5);$this->cell(285,5,html_entity_decode(utf8_decode("La répartition des ".$T." cas enregistrés montre que :")),0,0,'L',0);
	$this->SetXY(5,175+10);$this->cell(285,5,html_entity_decode(utf8_decode(round(($TM/$T)*100,2)."% des cas touche les hommes. ")),0,0,'L',0);
	$this->SetXY(5,175+15);$this->cell(285,5,html_entity_decode(utf8_decode(round(($TF/$T)*100,2)."% des cas touche les femmes. ")),0,0,'L',0);
	if ($TF > 0){$TF0=$TF;}else{$TF0=1;}
	$this->SetXY(5,175+20);$this->cell(285,5,html_entity_decode(utf8_decode("avec un sexe ratio de ".round(($TM/$TF0),2))),0,0,'L',0);
	$this->SetXY(5,175+30);$this->cell(285,5,html_entity_decode(utf8_decode("2-Répartition des cas par tranche d'âge : ")),0,0,'L',0);
	rsort($datamf);
	$this->SetXY(5,175+35);$this->cell(285,5,html_entity_decode(utf8_decode("la proportion des cas la plus élevée est : ".round($datamf[0]*100/$T,2)."%")),0,0,'L',0);
	sort($datamf);
	$this->SetXY(5,175+40);$this->cell(285,5,html_entity_decode(utf8_decode("la proportion des cas la moins élevée est : ".round($datamf[0]*100/$T,2)."%")),0,0,'L',0);
	$pie2 = array(
	"x" => 135, 
	"y" => 200, 
	"r" => 17,
	"v1" => $TM,
	"v2" => $TF,
	"t0" => html_entity_decode(utf8_decode("Distribution des cas par sexe ")),
	"t1" => "M",
	"t2" => "F");
    $this->pie2($pie2);
	$TA1=$data['1M']+$data['2M']+$data['1F']+$data['2F'];
	$TA2=$data['3M']+$data['4M']+$data['3F']+$data['4F'];
	$TA3=$data['5M']+$data['6M']+$data['5F']+$data['6F'];
	$TA4=$data['7M']+$data['8M']+$data['7F']+$data['8F'];
	$TA5=$data['9M']+$data['10M']+$data['9F']+$data['10F'];
	$TA6=$data['11M']+$data['12M']+$data['11F']+$data['12F'];
	$TA7=$data['13M']+$data['14M']+$data['13F']+$data['14F'];
	$TA8=$data['15M']+$data['16M']+$data['15F']+$data['16F'];
	$TA9=$data['17M']+$data['18M']+$data['17F']+$data['18F'];
	$TA10=$data['19M']+$data['20M']+$data['19F']+$data['20F'];
	$this->bar(135,150,$TA1,$TA2,$TA3,$TA4,$TA5,$TA6,$TA7,$TA8,$TA9,$TA10,utf8_decode('Distribution des cas par tranche d\'age en année'));
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
	$this->Image('../../public/IMAGES/photos/pc.gif',250,50,30,30,0);
	$this->SetXY(220,40);$this->cell(65,5,'WILAYA DE DJELFA',1,0,'C',1,0);
	// $this->RoundedRect($x-15,35,140,160, 2, $style = '');
	$this->RoundedRect($x-15,35,200,195, 2, $style = '');
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
	$this->RoundedRect($x-10,155,30,55, 2, $style = '');
	$this->color(0);    $this->SetXY($x-10,150);$this->cell(30,5,$data['titre'],0,0,'L',0,0);
	$this->color(0);    $this->SetXY($x-5,$this->GetY()+10);$this->cell(5,5,'',1,0,'C',1,0);$this->cell(15,5,$data['A'],0,0,'L',0,0);
	$this->color(1);    $this->SetXY($x-5,$this->GetY()+10);$this->cell(5,5,'',1,0,'C',1,0);$this->cell(15,5,$data['B'],0,0,'L',0,0);
	$this->color(11);   $this->SetXY($x-5,$this->GetY()+10);$this->cell(5,5,'',1,0,'C',1,0);$this->cell(15,5,$data['C'],0,0,'L',0,0);
	$this->color(101);  $this->SetXY($x-5,$this->GetY()+10);$this->cell(5,5,'',1,0,'C',1,0);$this->cell(15,5,$data['D'],0,0,'L',0,0);
	$this->color(1001); $this->SetXY($x-5,$this->GetY()+10);$this->cell(5,5,'',1,0,'C',1,0);$this->cell(15,5,$data['E'],0,0,'L',0,0);
	$this->color(0);    $this->SetXY($x+25,$this->GetY()+10);$this->cell(40,5,'00km_____45km_____90km',0,0,'L',0,0);
	$this->color(0);    $this->SetXY($x+25,$this->GetY()+10);$this->cell(40,5,'Source:PTS EPH AO W DE Djelfa',0,0,'L',0,0);
	$this->color(0);
	$this->SetFont('Times', 'BIU', 8);
	$this->SetDrawColor(255,0,0);
	$this->SetXY(140,42);$this->cell(65,5,'La Wilaya De Djelfa',0,0,'C',0,0);
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
	$this->SetFont('Times', 'B', 10);
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
	function dataagesexeped($x,$y,$colone1,$TABLE,$DINS,$COMMUNER,$datejour1,$datejour2,$STRUCTURED) 
	{
	$T2F20=array(
	"xt" => $x,
	"yt" => $y,
	"wc" => "",
	"hc" => "",
	"tt" => "Repartition des deces par tranche d'age et sexe (pediatrique)",
	"tc" => "Sexe",
	"tc1" =>"M",
	"tc3" =>"F",
	"tc5" =>"Total",
	"1M"  => $this->AGESEXE($colone1,0,7,$datejour1,$datejour2,'M',$STRUCTURED),           "1F"  => $this->AGESEXE($colone1,0,7,$datejour1,$datejour2,'F',$STRUCTURED),
	"2M"  => $this->AGESEXE($colone1,8,28,$datejour1,$datejour2,'M',$STRUCTURED),          "2F"  => $this->AGESEXE($colone1,8,28,$datejour1,$datejour2,'F',$STRUCTURED),
	"3M"  => $this->AGESEXE($colone1,29,365,$datejour1,$datejour2,'M',$STRUCTURED),        "3F"  => $this->AGESEXE($colone1,29,365,$datejour1,$datejour2,'F',$STRUCTURED),
	"4M"  => $this->AGESEXE($colone1,366,365*4,$datejour1,$datejour2,'M',$STRUCTURED),     "4F"  => $this->AGESEXE($colone1,366,365*4,$datejour1,$datejour2,'F',$STRUCTURED),
	"5M"  => $this->AGESEXE($colone1,365*4,365*15,$datejour1,$datejour2,'M',$STRUCTURED),  "5F"  => $this->AGESEXE($colone1,365*4,365*15,$datejour1,$datejour2,'F',$STRUCTURED),			
	"T" =>'1',
	"tl" =>"Age",
	"1MF"  => '00j-07j',  
	"2MF"  => '08j-28j',   
	"3MF"  => '01m-01a',  
	"4MF"  => '01a-04a',   
	"5MF"  => '05a-15a'  
	);
	return $T2F20;
	}
	function T2F20PED($data,$datejour1,$datejour2)
    {
	//tc2
	$this->SetXY($data['xt'],$data['yt']);     $this->cell(90+15,05,$data['tt'],1,0,'L',1,0);
	$this->SetXY($data['xt'],$this->GetY()+5); $this->cell(15,15,$data['tl'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(75+15,10,$data['tc'],1,0,'C',1,0);$this->SetXY($data['xt']+15,$this->GetY()+10);$this->cell(30,5,$data['tc1'],1,0,'C',1,0); $this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['tc3'],1,0,'C',1,0);$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['tc5'],1,0,'C',1,0);$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,'P %',1,0,'C',1,0);
	$TM=$data['1M']+$data['2M']+$data['3M']+$data['4M']+$data['5M'];
	$TF=$data['1F']+$data['2F']+$data['3F']+$data['4F']+$data['5F'];
	if ($TM+$TF>0){$T=$TM+$TF;}else {$T=1;}
	
	
	
	$datamf = array($data['1M']+$data['1F'],
	                $data['2M']+$data['2F'],
					$data['3M']+$data['3F'],
					$data['4M']+$data['4F'],
					$data['5M']+$data['5F']);
	
	
	$this->SetXY($data['xt'],$this->GetY()+5); $this->cell(15,5,$data['1MF'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,$data['1M'],1,0,'C',0,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['1F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['1M']+$data['1F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['1M']+$data['1F'])/$T)*100),2).' %',1,0,'R',1,0);        
	
	$this->SetXY($data['xt'],$this->GetY()+5);$this->cell(15,5,$data['2MF'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,$data['2M'],1,0,'C',0,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['2F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['2M']+$data['2F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['2M']+$data['2F'])/$T)*100),2).' %',1,0,'R',1,0);        
	
	
	$this->SetXY($data['xt'],$this->GetY()+5);$this->cell(15,5,$data['3MF'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,$data['3M'],1,0,'C',0,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['3F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['3M']+$data['3F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['3M']+$data['3F'])/$T)*100),2).' %',1,0,'R',1,0);        
	
	$this->SetXY($data['xt'],$this->GetY()+5);$this->cell(15,5,$data['4MF'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,$data['4M'],1,0,'C',0,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['4F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['4M']+$data['4F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['4M']+$data['4F'])/$T)*100),2).' %',1,0,'R',1,0);        
	
	$this->SetXY($data['xt'],$this->GetY()+5);$this->cell(15,5,$data['5MF'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,$data['5M'],1,0,'C',0,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['5F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['5M']+$data['5F'],1,0,'C',0,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['5M']+$data['5F'])/$T)*100),2).' %',1,0,'R',1,0);        
	
	$this->SetXY($data['xt'],$this->GetY()+5);$this->cell(15,5,'Total',1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(30,5,$TM,1,0,'C',1,0);
	$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$TF,1,0,'C',1,0);
	$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$T,1,0,'C',1,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($TM+$TF)/$T)*100),2).' %',1,0,'R',1,0); 	                                                                
	
	
	$this->SetXY($data['xt'],$this->GetY()+5); $this->cell(15,5,'P %',1,0,'C',1,0);      
	$this->SetXY($data['xt']+15,$this->GetY());      $this->cell(30,5,round(($TM/$T)*100,2),1,0,'C',1,0);
	$this->SetXY($data['xt']+45,$this->GetY());      $this->cell(30,5,round(($TF/$T)*100,2),1,0,'C',1,0);
	$this->SetXY($data['xt']+75,$this->GetY());      $this->cell(15,5,round(($T/$T)*100,2).' %',1,0,'C',1,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());   $this->cell(15,5,'***',1,0,'C',1,0); 	                                                                
	$this->SetXY(5,25+10);$this->cell(285,5,html_entity_decode(utf8_decode("Cette étude a porté sur ".$T." décès survenus durant la periode du ".$this->dateUS2FR($datejour1)." au ".$this->dateUS2FR($datejour2)." au niveau de 36 communes ")),0,0,'L',0);
	$this->SetXY(5,175);$this->cell(285,5,html_entity_decode(utf8_decode("1-Répartition des décès par sexe : ")),0,0,'L',0);
	$this->SetXY(5,175+5);$this->cell(285,5,html_entity_decode(utf8_decode("La répartition des ".$T." décès enregistrés montre que :")),0,0,'L',0);
	$this->SetXY(5,175+10);$this->cell(285,5,html_entity_decode(utf8_decode(round(($TM/$T)*100,2)."% des décès touche les garcons. ")),0,0,'L',0);
	$this->SetXY(5,175+15);$this->cell(285,5,html_entity_decode(utf8_decode(round(($TF/$T)*100,2)."% des décès touche les filles. ")),0,0,'L',0);
	if($TF>0){$TF0=$TF; }else{$TF0=1;}
	$this->SetXY(5,175+20);$this->cell(285,5,html_entity_decode(utf8_decode("avec un sexe ratio de ".round(($TM/$TF0),2))),0,0,'L',0);
	$this->SetXY(5,175+30);$this->cell(285,5,html_entity_decode(utf8_decode("2-Répartition des décès par tranche d'âge : ")),0,0,'L',0);
	rsort($datamf);
	$this->SetXY(5,175+35,$this->GetY()+5);$this->cell(285,5,html_entity_decode(utf8_decode("la proportion des décès la plus élevée est : ".round($datamf[0]*100/$T,2)."%")),0,0,'L',0);
    sort($datamf);
    $this->SetXY(5,175+40,$this->GetY()+5);$this->cell(285,5,html_entity_decode(utf8_decode("la proportion des décès la moins élevée est : ".round($datamf[0]*100/$T,2)."%")),0,0,'L',0);
	$pie2 = array(
	"x" => 135, 
	"y" => 200, 
	"r" => 17,
	"v1" => $TM,
	"v2" => $TF,
	"t0" => html_entity_decode(utf8_decode("Distribution des décès par sexe ")),
	"t1" => "M",
	"t2" => "F");
    $this->pie2($pie2);
    $TA1=$data['1M']+$data['1F'];
	$TA2=$data['2M']+$data['2F'];
	$TA3=$data['3M']+$data['3F'];
	$TA4=$data['4M']+$data['4F'];
	$TA5=$data['5M']+$data['5F'];
	$this->bar5(135,150,$TA1,$TA2,$TA3,$TA4,$TA5,utf8_decode('Distribution des décès par tranche d\'age ')); 
	}
	function dataagesexepedj($x,$y,$colone1,$TABLE,$DINS,$COMMUNER,$datejour1,$datejour2,$STRUCTURED) 
	{
	$T2F20=array(
	"xt" => $x,
	"yt" => $y,
	"wc" => "",
	"hc" => "",
	"tt" => "Repartition des deces par tranche d'age et sexe (pediatrique)",
	"tc" => "Sexe",
	"tc1" =>"M",
	"tc3" =>"F",
	"tc5" =>"Total",
	"1M"  => $this->AGESEXE($colone1,0,1,$datejour1,$datejour2,'M',$STRUCTURED),  "1F"  => $this->AGESEXE($colone1,0,1,$datejour1,$datejour2,'F',$STRUCTURED),
	"2M"  => $this->AGESEXE($colone1,2,2,$datejour1,$datejour2,'M',$STRUCTURED),  "2F"  => $this->AGESEXE($colone1,2,2,$datejour1,$datejour2,'F',$STRUCTURED),
	"3M"  => $this->AGESEXE($colone1,3,3,$datejour1,$datejour2,'M',$STRUCTURED),  "3F"  => $this->AGESEXE($colone1,3,3,$datejour1,$datejour2,'F',$STRUCTURED),
	"4M"  => $this->AGESEXE($colone1,4,4,$datejour1,$datejour2,'M',$STRUCTURED),  "4F"  => $this->AGESEXE($colone1,4,4,$datejour1,$datejour2,'F',$STRUCTURED),
	"5M"  => $this->AGESEXE($colone1,5,5,$datejour1,$datejour2,'M',$STRUCTURED),  "5F"  => $this->AGESEXE($colone1,5,5,$datejour1,$datejour2,'F',$STRUCTURED),			
	"6M"  => $this->AGESEXE($colone1,6,6,$datejour1,$datejour2,'M',$STRUCTURED),  "6F"  => $this->AGESEXE($colone1,6,6,$datejour1,$datejour2,'F',$STRUCTURED),			
	"7M"  => $this->AGESEXE($colone1,7,7,$datejour1,$datejour2,'M',$STRUCTURED),  "7F"  => $this->AGESEXE($colone1,7,7,$datejour1,$datejour2,'F',$STRUCTURED),			
	"T" =>'1',
	"tl" =>"Age",
	"1MF"  => '01j',  
	"2MF"  => '02j',   
	"3MF"  => '03j',  
	"4MF"  => '04j',   
	"5MF"  => '05j',
	"6MF"  => '06j',
	"7MF"  => '07j'	
	);
	return $T2F20;
	}
	function T2F20PEDJ($data,$datejour1,$datejour2)
    {
    //tc2
	$this->SetXY($data['xt'],$data['yt']);     $this->cell(90+15,05,$data['tt'],1,0,'L',1,0);
	$this->SetXY($data['xt'],$this->GetY()+5); $this->cell(15,15,$data['tl'],1,0,'C',1,0);
	$this->SetXY($data['xt']+15,$this->GetY());$this->cell(75+15,10,$data['tc'],1,0,'C',1,0);$this->SetXY($data['xt']+15,$this->GetY()+10);$this->cell(30,5,$data['tc1'],1,0,'C',1,0); $this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['tc3'],1,0,'C',1,0);$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['tc5'],1,0,'C',1,0);$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,'P %',1,0,'C',1,0);
	$TM=$data['1M']+$data['2M']+$data['3M']+$data['4M']+$data['5M']+$data['6M']+$data['7M'];
	$TF=$data['1F']+$data['2F']+$data['3F']+$data['4F']+$data['5F']+$data['6F']+$data['7F'];
	if($TM+$TF>0){$T=$TM+$TF;}else{$T=1;}
	$datamf = array($data['1M']+$data['1F'],$data['2M']+$data['2F'],$data['3M']+$data['3F'],$data['4M']+$data['4F'],$data['5M']+$data['5F'],$data['6M']+$data['6F'],$data['7M']+$data['7F']);
	$this->SetXY($data['xt'],$this->GetY()+5); $this->cell(15,5,$data['1MF'],1,0,'C',1,0);$this->SetXY($data['xt']+15,$this->GetY());      $this->cell(30,5,$data['1M'],1,0,'C',0,0);$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['1F'],1,0,'C',0,0);$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['1M']+$data['1F'],1,0,'C',0,0);$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['1M']+$data['1F'])/$T)*100),2).' %',1,0,'R',1,0);        
	$this->SetXY($data['xt'],$this->GetY()+5); $this->cell(15,5,$data['2MF'],1,0,'C',1,0);$this->SetXY($data['xt']+15,$this->GetY());      $this->cell(30,5,$data['2M'],1,0,'C',0,0);$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['2F'],1,0,'C',0,0);$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['2M']+$data['2F'],1,0,'C',0,0);$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['2M']+$data['2F'])/$T)*100),2).' %',1,0,'R',1,0);        
	$this->SetXY($data['xt'],$this->GetY()+5); $this->cell(15,5,$data['3MF'],1,0,'C',1,0);$this->SetXY($data['xt']+15,$this->GetY());      $this->cell(30,5,$data['3M'],1,0,'C',0,0);$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['3F'],1,0,'C',0,0);$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['3M']+$data['3F'],1,0,'C',0,0);$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['3M']+$data['3F'])/$T)*100),2).' %',1,0,'R',1,0);        
	$this->SetXY($data['xt'],$this->GetY()+5); $this->cell(15,5,$data['4MF'],1,0,'C',1,0);$this->SetXY($data['xt']+15,$this->GetY());      $this->cell(30,5,$data['4M'],1,0,'C',0,0);$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['4F'],1,0,'C',0,0);$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['4M']+$data['4F'],1,0,'C',0,0);$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['4M']+$data['4F'])/$T)*100),2).' %',1,0,'R',1,0);        
	$this->SetXY($data['xt'],$this->GetY()+5); $this->cell(15,5,$data['5MF'],1,0,'C',1,0);$this->SetXY($data['xt']+15,$this->GetY());      $this->cell(30,5,$data['5M'],1,0,'C',0,0);$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['5F'],1,0,'C',0,0);$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['5M']+$data['5F'],1,0,'C',0,0);$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['5M']+$data['5F'])/$T)*100),2).' %',1,0,'R',1,0);        
	$this->SetXY($data['xt'],$this->GetY()+5); $this->cell(15,5,$data['6MF'],1,0,'C',1,0);$this->SetXY($data['xt']+15,$this->GetY());      $this->cell(30,5,$data['6M'],1,0,'C',0,0);$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['6F'],1,0,'C',0,0);$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['6M']+$data['6F'],1,0,'C',0,0);$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['6M']+$data['6F'])/$T)*100),2).' %',1,0,'R',1,0);        
	$this->SetXY($data['xt'],$this->GetY()+5); $this->cell(15,5,$data['7MF'],1,0,'C',1,0);$this->SetXY($data['xt']+15,$this->GetY());      $this->cell(30,5,$data['7M'],1,0,'C',0,0);$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$data['7F'],1,0,'C',0,0);$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$data['7M']+$data['7F'],1,0,'C',0,0);$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($data['7M']+$data['7F'])/$T)*100),2).' %',1,0,'R',1,0);        
	$this->SetXY($data['xt'],$this->GetY()+5); $this->cell(15,5,'Total',1,0,'C',1,0);$this->SetXY($data['xt']+15,$this->GetY());           $this->cell(30,5,$TM,1,0,'C',1,0);$this->SetXY($data['xt']+45,$this->GetY());$this->cell(30,5,$TF,1,0,'C',1,0);$this->SetXY($data['xt']+75,$this->GetY());$this->cell(15,5,$T,1,0,'C',1,0);$this->SetXY($data['xt']+75+15,$this->GetY());$this->cell(15,5,round(((($TM+$TF)/$T)*100),2).' %',1,0,'R',1,0); 	                                                                
	$this->SetXY($data['xt'],$this->GetY()+5); $this->cell(15,5,'P %',1,0,'C',1,0);      
	$this->SetXY($data['xt']+15,$this->GetY());      $this->cell(30,5,round(($TM/$T)*100,2),1,0,'C',1,0);
	$this->SetXY($data['xt']+45,$this->GetY());      $this->cell(30,5,round(($TF/$T)*100,2),1,0,'C',1,0);
	$this->SetXY($data['xt']+75,$this->GetY());      $this->cell(15,5,round(($T/$T)*100,2).' %',1,0,'C',1,0);
	$this->SetXY($data['xt']+75+15,$this->GetY());   $this->cell(15,5,'***',1,0,'C',1,0); 	                                                                
	$this->SetXY(5,25+10);$this->cell(285,5,html_entity_decode(utf8_decode("Cette étude a porté sur ".$T." décès survenus durant la periode du ".$this->dateUS2FR($datejour1)." au ".$this->dateUS2FR($datejour2)." au niveau de 36 communes ")),0,0,'L',0);
	$this->SetXY(5,175);$this->cell(285,5,html_entity_decode(utf8_decode("1-Répartition des décès par sexe : ")),0,0,'L',0);
	$this->SetXY(5,175+5);$this->cell(285,5,html_entity_decode(utf8_decode("La répartition des ".$T." décès enregistrés montre que :")),0,0,'L',0);
	$this->SetXY(5,175+10);$this->cell(285,5,html_entity_decode(utf8_decode(round(($TM/$T)*100,2)."% des décès touche les garcons. ")),0,0,'L',0);
	$this->SetXY(5,175+15);$this->cell(285,5,html_entity_decode(utf8_decode(round(($TF/$T)*100,2)."% des décès touche les filles. ")),0,0,'L',0);
	if($TF>0){$TF0=$TF;}else{$TF0=1;}
	$this->SetXY(5,175+20);$this->cell(285,5,html_entity_decode(utf8_decode("avec un sexe ratio de ".round(($TM/$TF0),2))),0,0,'L',0);
	$this->SetXY(5,175+30);$this->cell(285,5,html_entity_decode(utf8_decode("2-Répartition des décès par tranche d'âge : ")),0,0,'L',0);
	rsort($datamf);
	$this->SetXY(5,175+35,$this->GetY()+5);$this->cell(285,5,html_entity_decode(utf8_decode("la proportion des décès la plus élevée est : ".round($datamf[0]*100/$T,2)."%")),0,0,'L',0);
    sort($datamf);
    $this->SetXY(5,175+40,$this->GetY()+5);$this->cell(285,5,html_entity_decode(utf8_decode("la proportion des décès la moins élevée est : ".round($datamf[0]*100/$T,2)."%")),0,0,'L',0);
	$pie2 = array(
	"x" => 135, 
	"y" => 200, 
	"r" => 17,
	"v1" => $TM,
	"v2" => $TF,
	"t0" => html_entity_decode(utf8_decode("Distribution des décès par sexe ")),
	"t1" => "M",
	"t2" => "F");
    $this->pie2($pie2);
	$TA1=$data['1M']+$data['1F'];
	$TA2=$data['2M']+$data['2F'];
	$TA3=$data['3M']+$data['3F'];
	$TA4=$data['4M']+$data['4F'];
	$TA5=$data['5M']+$data['5F'];
	$TA6=$data['6M']+$data['6F'];
	$TA7=$data['7M']+$data['7F'];
	$this->bar7(135,150,$TA1,$TA2,$TA3,$TA4,$TA5,$TA6,$TA7,utf8_decode('Distribution des décès par tranche d\'age en jours')); 
	}
	
	
	
	function STAT($colone1,$datejour1,$datejour2)
	{
    $this->mysqlconnect();
	$sql = " select * from deceshosp where $colone1>=1 and  $colone1<=150  and (DINS BETWEEN '$datejour1' AND '$datejour2')  ";
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$x = array(); 
	while($value=mysql_fetch_array($requete))
		{
		 array_push( $x,$value[$colone1]);
		}
	
	return $x;
	}
	function boxplotgv($x,$y,$titre,$data)
    {
	sort($data);
	
	$contd=count ($data);
	// for ($i = 0; $i <= $contd-1; $i++) {
	// $this->SetXY(255,$this->GetY()-5);$this->cell(15,5,$data[$i],1,0,'L',1,0); 
    // }
	
	$min=$data[0];
	$q1=$data[round($contd / 4)];
	$mediane=$this->median($data);
	//$mean=round($this->mean($data),2);
	$q3=$data[round($contd * 3 / 4)];
	$max=$data[$contd-1];
	$total=$min+$q1+$q3+$max;                                       
		if($total==0){
		$total=1;
		}
	$a=round($min*100/$total,2);
	$b=round($q1*100/$total,2);
	$c=round($q3*100/$total,2);
	$d=round($max*100/$total,2);
	// $m=round($mean*100/$total,2);
	
	$this->SetFont('Times', 'BIU', 11);
	$this->SetXY($x-15,$y-108-5);$this->Cell(0, 5,$titre ,0, 2, 'L');
	$this->RoundedRect($x-15,$y-108-5, 45, 118, 2, $style = '');
	$this->SetFont('Times', 'B', 11);
	
	$this->SetXY($x-15,$y);$this->cell(10,-5,$min,0,0,'L',0,0);
	$this->SetFillColor(0,0,0);$this->SetXY($x+4.5,$y);$this->cell(5,-1,'',1,0,'C',1,0);
	$this->SetFillColor(0,0,0);$this->SetXY($x+6.5,$y);$this->cell(1,-$a,'',1,0,'C',1,0);
	$this->SetFillColor(224,255,255);$this->SetXY($x,$y-$a);$this->cell(15,-$b,'',1,0,'C',1,0);
	$this->SetFillColor(224,255,255);$this->SetXY($x,$y-$a-$b);$this->cell(15,-$c,'',1,0,'C',1,0);
	$this->SetFillColor(255,64,64);$this->SetXY($x,$y-$a-$b);$this->cell(15,-1,'',1,0,'C',1,0);
	
	$this->SetFillColor(230);$this->SetXY($x-15,$y-$a-$b+2.5);$this->cell(10,-5,$mediane,0,0,'L',0,0);
	$this->SetFillColor(0,0,0);$this->SetXY($x+6.5,$y-$a-$b-$c);$this->cell(1,-$d,'',1,0,'C',1,0);
	$this->SetFillColor(0,0,0);$this->SetXY($x+4.5,$y-$a-$b-$c-$d);$this->cell(5,-1,'',1,0,'C',1,0);
	$this->SetFillColor(230);$this->SetXY($x-15,$y-$a-$b-$c-$d);$this->cell(10,-5,$max,0,0,'L',0,0);
	
	$this->SetFillColor(230);
	$this->SetTextColor(0,0,0);//text noire
	$this->SetFont('Times', 'B', 11);
	}
	
	function T2F20STAT($colone1,$datejour1,$datejour2,$titre)
    {
	$d=$this->STAT($colone1,$datejour1,$datejour2);
	$this->SetXY(125,42);  
	$this->SetXY(125,$this->GetY()+120);$this->cell(45,5,'IC 95% Mean',1,0,'L',1,0);
	$this->SetXY(125,$this->GetY()+5);$this->cell(10,5,round(array_sum($d)/count($d),2)-round(1.96*($this->sd($d)/count($d)),2),1,0,'C',1,0);$this->cell(25,5,round(array_sum($d)/count($d),2),1,0,'C',1,0);
	$this->cell(10,5,round(array_sum($d)/count($d),2)+round(1.96*($this->sd($d)/count($d)),2),1,0,'C',1,0);
 
    $this->SetXY(180,$this->GetY()-5);$this->cell(15,5,'Mean',1,0,'L',1,0);      $this->cell(20,5,round($this->mean($d),2),1,0,'L',1,0);
    $this->SetXY(217.5,$this->GetY());$this->cell(15,5,'Mode',1,0,'L',1,0);      $this->cell(20,5,implode(" ",$this->mode($d)),1,0,'L',1,0);
    $this->SetXY(255,$this->GetY());$this->cell(15,5,'median',1,0,'L',1,0);      $this->cell(20,5,round($this->median($d),2),1,0,'L',1,0);
   
    $this->SetXY(180,$this->GetY()+5);$this->cell(15,5,'var(n-1)',1,0,'L',1,0);  $this->cell(20,5,round($this->variance($d),2),1,0,'L',1,0);
    $this->SetXY(217.5,$this->GetY());$this->cell(15,5,'std(n-1)',1,0,'L',1,0);  $this->cell(20,5,round($this->sd($d),2),1,0,'L',1,0);
    $this->SetXY(255,$this->GetY());$this->cell(15,5,'cv',1,0,'L',1,0);          $this->cell(20,5,round($this->cv($d),2),1,0,'L',1,0);
   
    $this->boxplotgv(140,155,'boxplot:'.$titre,$d);
	}
	
	function pyramide($x,$y,$titre,$pyramide)
    {
	$ta1M=$pyramide['1M'];$ta1F=$pyramide['1F'];
	$ta2M=$pyramide['2M'];$ta2F=$pyramide['2F'];
	$ta3M=$pyramide['3M'];$ta3F=$pyramide['3F'];
	$ta4M=$pyramide['4M'];$ta4F=$pyramide['4F'];
	$ta5M=$pyramide['5M'];$ta5F=$pyramide['5F'];
	$ta6M=$pyramide['6M'];$ta6F=$pyramide['6F'];
	$ta7M=$pyramide['7M'];$ta7F=$pyramide['7F'];
	$ta8M=$pyramide['8M'];$ta8F=$pyramide['8F'];
	$ta9M=$pyramide['9M'];$ta9F=$pyramide['9F'];
	$ta10M=$pyramide['10M'];$ta10F=$pyramide['10F'];
	$ta11M=$pyramide['11M'];$ta11F=$pyramide['11F'];
	$ta12M=$pyramide['12M'];$ta12F=$pyramide['12F'];
	$ta13M=$pyramide['13M'];$ta13F=$pyramide['13F'];
	$ta14M=$pyramide['14M'];$ta14F=$pyramide['14F'];
	$ta15M=$pyramide['15M'];$ta15F=$pyramide['15F'];
	$ta16M=$pyramide['16M'];$ta16F=$pyramide['16F'];
	$ta17M=$pyramide['17M'];$ta17F=$pyramide['17F'];
	$ta18M=$pyramide['18M'];$ta18F=$pyramide['18F'];
	$ta19M=$pyramide['19M'];$ta19F=$pyramide['19F'];
	$ta20M=$pyramide['20M'];$ta20F=$pyramide['20F'];
	
	$totalm=$ta1M+$ta2M+$ta3M+$ta4M+$ta5M+$ta6M+$ta7M+$ta8M+$ta9M+$ta10M+$ta11M+$ta12M+$ta13M+$ta14M+$ta15M+$ta16M+$ta17M+$ta18M+$ta19M+$ta20M;                                       
	$totalf=$ta1F+$ta2F+$ta3F+$ta4F+$ta5F+$ta6F+$ta7F+$ta8F+$ta9F+$ta10F+$ta11F+$ta12F+$ta13F+$ta14F+$ta15F+$ta16F+$ta17F+$ta18F+$ta19F+$ta20F;
	if($totalm==0){
	$totalm=1;
	}
	if($totalf==0){
	$totalf=1;
	}
	$pta1M=round($ta1M*100/$totalm,2);$pta1F=round($ta1F*100/$totalf,2);
	$pta2M=round($ta2M*100/$totalm,2);$pta2F=round($ta2F*100/$totalf,2);
	$pta3M=round($ta3M*100/$totalm,2);$pta3F=round($ta3F*100/$totalf,2);
	$pta4M=round($ta4M*100/$totalm,2);$pta4F=round($ta4F*100/$totalf,2);
	$pta5M=round($ta5M*100/$totalm,2);$pta5F=round($ta5F*100/$totalf,2);
	$pta6M=round($ta6M*100/$totalm,2);$pta6F=round($ta6F*100/$totalf,2);
	$pta7M=round($ta7M*100/$totalm,2);$pta7F=round($ta7F*100/$totalf,2);
	$pta8M=round($ta8M*100/$totalm,2);$pta8F=round($ta8F*100/$totalf,2);
	$pta9M=round($ta9M*100/$totalm,2);$pta9F=round($ta9F*100/$totalf,2);
	$pta10M=round($ta10M*100/$totalm,2);$pta10F=round($ta10F*100/$totalf,2);
	$pta11M=round($ta11M*100/$totalm,2);$pta11F=round($ta11F*100/$totalf,2);
	$pta12M=round($ta12M*100/$totalm,2);$pta12F=round($ta12F*100/$totalf,2);
	$pta13M=round($ta13M*100/$totalm,2);$pta13F=round($ta13F*100/$totalf,2);
	$pta14M=round($ta14M*100/$totalm,2);$pta14F=round($ta14F*100/$totalf,2);
	$pta15M=round($ta15M*100/$totalm,2);$pta15F=round($ta15F*100/$totalf,2);
	$pta16M=round($ta16M*100/$totalm,2);$pta16F=round($ta16F*100/$totalf,2);
	$pta17M=round($ta17M*100/$totalm,2);$pta17F=round($ta17F*100/$totalf,2);
	$pta18M=round($ta18M*100/$totalm,2);$pta18F=round($ta18F*100/$totalf,2);
	$pta19M=round($ta19M*100/$totalm,2);$pta19F=round($ta19F*100/$totalf,2);
	$pta20M=round($ta20M*100/$totalm,2);$pta20F=round($ta20F*100/$totalf,2);
	
	$this->SetFont('Times', 'BIU', 11);
	$this->SetXY($x-20,$y-108);$this->Cell(0, 5,$titre ,0, 2, 'L');
	$this->RoundedRect($x-20,$y-108, 110, 118, 2, $style = '');
	$this->SetFont('Times', 'B', 11);
	// $this->SetXY($x-20,$y);$this->cell(2.5,-100,'***',1,0,'L',1,0);
	$this->SetXY($x+4.5-20,$y-100);$this->cell(20,5,'Masculin',1,0,'C',1,0);$this->SetXY($x+65,$y-100);$this->cell(20,5,'Feminin',1,0,'C',1,0);
	
	$this->SetXY($x+24.5,$y);$this->cell(10,5,'20',1,0,'L',1,0);$this->SetXY($x+35,$y);$this->cell(10,5,'20',1,0,'R',1,0);
	$this->SetXY($x+14.5,$y);$this->cell(10,5,'40',1,0,'L',1,0);$this->SetXY($x+45,$y);$this->cell(10,5,'40',1,0,'R',1,0);
	$this->SetXY($x+4.5,$y);$this->cell(10,5,'60',1,0,'L',1,0);$this->SetXY($x+55,$y);$this->cell(10,5,'60',1,0,'R',1,0);
	$this->SetXY($x+4.5-10,$y);$this->cell(10,5,'80',1,0,'L',1,0);$this->SetXY($x+65,$y);$this->cell(10,5,'80',1,0,'R',1,0);
	$this->SetXY($x+4.5-20,$y);$this->cell(10,5,'100',1,0,'L',1,0);$this->SetXY($x+75,$y);$this->cell(10,5,'100',1,0,'R',1,0);
	
	$this->SetFillColor(120,120,255);$w0=$pta1M;$this->SetXY( ($x+268.5-$w0)/2,$y-5);$this->cell(($w0+1)/2,5,$ta1M,1,0,'R',1,0);$this->SetFillColor(255,120,120);$w1=$pta1F;$this->SetXY($x+35,$y-5);$this->cell(($w1+1)/2,5,$ta1F,1,0,'L',1,0);
	$this->SetFillColor(120,120,255);$w0=$pta2M;$this->SetXY( ($x+268.5-$w0)/2,$y-10);$this->cell(($w0+1)/2,5,$ta2M,1,0,'R',1,0);$this->SetFillColor(255,120,120);$w1=$pta2F;$this->SetXY($x+35,$y-10);$this->cell(($w1+1)/2,5,$ta2F,1,0,'L',1,0);
	$this->SetFillColor(120,120,255);$w0=$pta3M;$this->SetXY( ($x+268.5-$w0)/2,$y-15);$this->cell(($w0+1)/2,5,$ta3M,1,0,'R',1,0);$this->SetFillColor(255,120,120);$w1=$pta3F;$this->SetXY($x+35,$y-15);$this->cell(($w1+1)/2,5,$ta3F,1,0,'L',1,0);
	$this->SetFillColor(120,120,255);$w0=$pta4M;$this->SetXY( ($x+268.5-$w0)/2,$y-20);$this->cell(($w0+1)/2,5,$ta4M,1,0,'R',1,0);$this->SetFillColor(255,120,120);$w1=$pta4F;$this->SetXY($x+35,$y-20);$this->cell(($w1+1)/2,5,$ta4F,1,0,'L',1,0);
	$this->SetFillColor(120,120,255);$w0=$pta5M;$this->SetXY( ($x+268.5-$w0)/2,$y-25);$this->cell(($w0+1)/2,5,$ta5M,1,0,'R',1,0);$this->SetFillColor(255,120,120);$w1=$pta5F;$this->SetXY($x+35,$y-25);$this->cell(($w1+1)/2,5,$ta5F,1,0,'L',1,0);
	$this->SetFillColor(120,120,255);$w0=$pta6M;$this->SetXY( ($x+268.5-$w0)/2,$y-30);$this->cell(($w0+1)/2,5,$ta6M,1,0,'R',1,0);$this->SetFillColor(255,120,120);$w1=$pta6F;$this->SetXY($x+35,$y-30);$this->cell(($w1+1)/2,5,$ta6F,1,0,'L',1,0);
	$this->SetFillColor(120,120,255);$w0=$pta7M;$this->SetXY( ($x+268.5-$w0)/2,$y-35);$this->cell(($w0+1)/2,5,$ta7M,1,0,'R',1,0);$this->SetFillColor(255,120,120);$w1=$pta7F;$this->SetXY($x+35,$y-35);$this->cell(($w1+1)/2,5,$ta7F,1,0,'L',1,0);
	$this->SetFillColor(120,120,255);$w0=$pta8M;$this->SetXY( ($x+268.5-$w0)/2,$y-40);$this->cell(($w0+1)/2,5,$ta8M,1,0,'R',1,0);$this->SetFillColor(255,120,120);$w1=$pta8F;$this->SetXY($x+35,$y-40);$this->cell(($w1+1)/2,5,$ta8F,1,0,'L',1,0);
	$this->SetFillColor(120,120,255);$w0=$pta9M;$this->SetXY( ($x+268.5-$w0)/2,$y-45);$this->cell(($w0+1)/2,5,$ta9M,1,0,'R',1,0);$this->SetFillColor(255,120,120);$w1=$pta9F;$this->SetXY($x+35,$y-45);$this->cell(($w1+1)/2,5,$ta9F,1,0,'L',1,0);
	$this->SetFillColor(120,120,255);$w0=$pta10M;$this->SetXY( ($x+268.5-$w0)/2,$y-50);$this->cell(($w0+1)/2,5,$ta10M,1,0,'R',1,0);$this->SetFillColor(255,120,120);$w1=$pta10F;$this->SetXY($x+35,$y-50);$this->cell(($w1+1)/2,5,$ta10F,1,0,'L',1,0);
	$this->SetFillColor(120,120,255);$w0=$pta11M;$this->SetXY( ($x+268.5-$w0)/2,$y-55);$this->cell(($w0+1)/2,5,$ta11M,1,0,'R',1,0);$this->SetFillColor(255,120,120);$w1=$pta11F;$this->SetXY($x+35,$y-55);$this->cell(($w1+1)/2,5,$ta11F,1,0,'L',1,0);
	$this->SetFillColor(120,120,255);$w0=$pta12M;$this->SetXY( ($x+268.5-$w0)/2,$y-60);$this->cell(($w0+1)/2,5,$ta12M,1,0,'R',1,0);$this->SetFillColor(255,120,120);$w1=$pta12F;$this->SetXY($x+35,$y-60);$this->cell(($w1+1)/2,5,$ta12F,1,0,'L',1,0);
	$this->SetFillColor(120,120,255);$w0=$pta13M;$this->SetXY( ($x+268.5-$w0)/2,$y-65);$this->cell(($w0+1)/2,5,$ta13M,1,0,'R',1,0);$this->SetFillColor(255,120,120);$w1=$pta13F;$this->SetXY($x+35,$y-65);$this->cell(($w1+1)/2,5,$ta13F,1,0,'L',1,0);
	$this->SetFillColor(120,120,255);$w0=$pta14M;$this->SetXY( ($x+268.5-$w0)/2,$y-70);$this->cell(($w0+1)/2,5,$ta14M,1,0,'R',1,0);$this->SetFillColor(255,120,120);$w1=$pta14F;$this->SetXY($x+35,$y-70);$this->cell(($w1+1)/2,5,$ta14F,1,0,'L',1,0);
	$this->SetFillColor(120,120,255);$w0=$pta15M;$this->SetXY( ($x+268.5-$w0)/2,$y-75);$this->cell(($w0+1)/2,5,$ta15M,1,0,'R',1,0);$this->SetFillColor(255,120,120);$w1=$pta15F;$this->SetXY($x+35,$y-75);$this->cell(($w1+1)/2,5,$ta15F,1,0,'L',1,0);
	$this->SetFillColor(120,120,255);$w0=$pta16M;$this->SetXY( ($x+268.5-$w0)/2,$y-80);$this->cell(($w0+1)/2,5,$ta16M,1,0,'R',1,0);$this->SetFillColor(255,120,120);$w1=$pta16F;$this->SetXY($x+35,$y-80);$this->cell(($w1+1)/2,5,$ta16F,1,0,'L',1,0);
	$this->SetFillColor(120,120,255);$w0=$pta17M;$this->SetXY( ($x+268.5-$w0)/2,$y-85);$this->cell(($w0+1)/2,5,$ta17M,1,0,'R',1,0);$this->SetFillColor(255,120,120);$w1=$pta17F;$this->SetXY($x+35,$y-85);$this->cell(($w1+1)/2,5,$ta17F,1,0,'L',1,0);
	$this->SetFillColor(120,120,255);$w0=$pta18M;$this->SetXY( ($x+268.5-$w0)/2,$y-90);$this->cell(($w0+1)/2,5,$ta18M,1,0,'R',1,0);$this->SetFillColor(255,120,120);$w1=$pta18F;$this->SetXY($x+35,$y-90);$this->cell(($w1+1)/2,5,$ta18F,1,0,'L',1,0);
	$this->SetFillColor(120,120,255);$w0=$pta19M;$this->SetXY( ($x+268.5-$w0)/2,$y-95);$this->cell(($w0+1)/2,5,$ta19M,1,0,'R',1,0);$this->SetFillColor(255,120,120);$w1=$pta19F;$this->SetXY($x+35,$y-95);$this->cell(($w1+1)/2,5,$ta19F,1,0,'L',1,0);
	$this->SetFillColor(120,120,255);$w0=$pta20M;$this->SetXY( ($x+268.5-$w0)/2,$y-100);$this->cell(($w0+1)/2,5,$ta20M,1,0,'R',1,0);$this->SetFillColor(255,120,120);$w1=$pta20F;$this->SetXY($x+35,$y-100);$this->cell(($w1+1)/2,5,$ta20F,1,0,'L',1,0);
	$this->SetFillColor(230);//fond gris il faut ajouter au cell un autre parametre pour qui accepte la coloration
	$this->SetTextColor(0,0,0);//text noire
	}
	
	function tblparcim($titre,$datejour1,$datejour2,$valeurs) 
	{    
		$this->SetFont('Times', 'B', 10);
		$h=35;
		$this->SetXY(5,$h-10);$this->cell(200,5,$titre,1,0,'C',1,0);
		$this->SetXY(5,$h);
		$this->cell(20,5,"CODE",1,0,'C',1,0);
		$this->cell(115,5,"CATEGORIE",1,0,'C',1,0);
	    $this->cell(20,5,"NOMBRE",1,0,'C',1,0);
		$this->cell(45,5,"TX MORTALITE",1,0,'C',1,0);
		$this->SetXY(5,$h+5);
		$IDWIL=17000;
		$ANNEE='2016';
		$this->mysqlconnect();
		
		$req="SELECT * FROM deceshosp where DINS BETWEEN '$datejour1' AND '$datejour2' ";
		$query1 = mysql_query($req);   
		$totalmbr11=mysql_num_rows($query1);
		
		$query="SELECT $valeurs,count($valeurs)as nbr,CODECIM FROM deceshosp where DINS BETWEEN '$datejour1' AND '$datejour2' GROUP BY $valeurs  order by nbr desc "; //    % %will search form 0-9,a-z            
		$resultat=mysql_query($query);
		$totalmbr1=mysql_num_rows($resultat);
		while($row=mysql_fetch_object($resultat))
		{
		$this->SetFont('Times', '', 10);
			if ($valeurs=='CODECIM') 
			{
				$this->cell(20,4,trim($row->$valeurs),1,0,'C',0);
				$this->cell(115,4,html_entity_decode(utf8_decode($this->stringtostring('cim','diag_cod',$row->$valeurs,'diag_nom'))) ,1,0,'L',0);	
			} 
			else 
			{
				$this->cell(20,4,trim($row->CODECIM),1,0,'C',0);
				$this->cell(115,4,trim($row->$valeurs) ,1,0,'L',0);
			}	
		$this->cell(20,4,trim($row->nbr),1,0,'C',0);
		$this->cell(45,4,round(($row->nbr*100)/$totalmbr11,2).' %',1,0,'C',0);
		$this->SetXY(5,$this->GetY()+4); 
		}
		$this->SetXY(5,$this->GetY());$this->cell(20,5,"Total",1,0,'C',1,0);	  
		$this->cell(115,5,$totalmbr1." : CATEGORIES",1,0,'C',1,0);	  
		$this->cell(20,5,$totalmbr11,1,0,'C',1,0);	//round($rs['total'],2)  
		$this->cell(45,5,'100%',1,0,'C',1,0);//round($rs1['total1'],2)	  
	}
	
	function tblparcim1($titre,$datejour1,$datejour2) 
	{    
		$this->SetFont('Times', 'B', 10);
		$this->SetXY(5,25);$this->cell(200,5,$titre,1,0,'C',1,0);
		$this->SetXY(5,35);
		$this->cell(20,5,"CODE",1,0,'C',1,0);
		$this->cell(115,5,"CHAPITRE",1,0,'C',1,0);
	    $this->cell(20,5,"NOMBRE",1,0,'C',1,0);
		$this->cell(45,5,"TX MORTALITE",1,0,'C',1,0);
		$this->SetXY(5,40);
		$IDWIL=17000;
		$ANNEE='2016';
		$this->mysqlconnect();
		$req="SELECT * FROM deceshosp where  DINS BETWEEN '$datejour1' AND '$datejour2' ";
		$query1 = mysql_query($req);   
		$totalmbr11=mysql_num_rows($query1);
		
		$query="SELECT CODECIM0,count(CODECIM0)as nbr FROM deceshosp where DINS BETWEEN '$datejour1' AND '$datejour2' GROUP BY CODECIM0  order by CODECIM0 asc "; //    % %will search form 0-9,a-z            
		$resultat=mysql_query($query);
		$totalmbr1=mysql_num_rows($resultat);
		while($row=mysql_fetch_object($resultat))
		{
			$this->SetFont('Times', '', 10);
			$this->cell(20,4,trim($row->CODECIM0),1,0,'C',0);
			$this->cell(115,4,html_entity_decode(utf8_decode($this->stringtostring('chapitre','IDCHAP',$row->CODECIM0,'CHAP'))) ,1,0,'L',0);
			$this->cell(20,4,trim($row->nbr),1,0,'C',0);
			$this->cell(45,4,round(($row->nbr*100)/$totalmbr11,2).' %',1,0,'C',0);
			$this->SetXY(5,$this->GetY()+4); 
		}
		$this->SetXY(5,$this->GetY());$this->cell(20,5,"Total",1,0,'C',1,0);	  
		$this->cell(115,5,$totalmbr1." : CHAPITRES",1,0,'C',1,0);	  
		$this->cell(20,5,$totalmbr11,1,0,'C',1,0);	  
		$this->cell(45,5,'100%',1,0,'C',1,0);  
	}
	function dureehospitalisation($titre,$datejour1,$datejour2,$valeurs) 
	{    
		$this->SetFont('Times', 'B', 10);
		$this->SetXY(5,25);$this->cell(200,5,$titre,1,0,'C',1,0);
		$this->SetXY(5,35+7);
		$this->cell(40,5,"DUREE SEJOUR",1,0,'L',1,0);
	    $this->cell(20,5,"DECES",1,0,'C',1,0);
		$this->cell(45,5,"TX MORTALITE",1,0,'C',1,0);
		$this->SetXY(5,40+7);
		$IDWIL=17000;
		$ANNEE='2016';
		$this->mysqlconnect();
		$req="SELECT * FROM deceshosp where  DINS BETWEEN '$datejour1' AND '$datejour2' ";
		$query1 = mysql_query($req);   
		$totalmbr11=mysql_num_rows($query1);
		
		$query="SELECT $valeurs,count($valeurs)as nbr FROM deceshosp where DINS BETWEEN '$datejour1' AND '$datejour2' GROUP BY $valeurs  order by $valeurs asc "; //    % %will search form 0-9,a-z            
		$resultat=mysql_query($query);
		$totalmbr1=mysql_num_rows($resultat);
		while($row=mysql_fetch_object($resultat))
		{
			$this->SetFont('Times', '', 10);
		
			$this->cell(40,4,trim($row->$valeurs),1,0,'L',0);
			$this->cell(20,4,trim($row->nbr),1,0,'C',0);
			$this->cell(45,4,round(($row->nbr*100)/$totalmbr11,2).' %',1,0,'C',0);
			$this->SetXY(5,$this->GetY()+4); 
		}
		$this->SetXY(5,$this->GetY());  
		$this->cell(40,5,"Total ".$totalmbr1." : DUREE",1,0,'L',1,0);	  
		$this->cell(20,5,$totalmbr11,1,0,'C',1,0);	  
		$this->cell(45,5,'100%',1,0,'C',1,0);  
	}
	function nbrservice($nbrservice,$datejour1,$datejour2,$eph)
	{
	$this->mysqlconnect();
	$sql = " select * from deceshosp where SERVICEHOSPIT=$nbrservice and (DINS BETWEEN '$datejour1' AND '$datejour2') and STRUCTURED='$eph' ";
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$collecte=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $collecte;
	}
	function servicehospitalisation($titre,$datejour1,$datejour2,$valeurs,$eph) 
	{    
		$this->SetFont('Times', 'B', 10);
		$this->SetXY(5,25);$this->cell(200,5,$titre,1,0,'C',1,0);
		$this->SetXY(5,35+7);
		$this->cell(40,5,"SERVICE",1,0,'L',1,0);
	    $this->cell(20,5,"DECES",1,0,'C',1,0);
		$this->cell(45,5,"TX MORTALITE",1,0,'C',1,0);
		$this->SetXY(5,40+7);
		$IDWIL=17000;
		$ANNEE='2016';
		$this->mysqlconnect();
		$req="SELECT * FROM deceshosp where  (DINS BETWEEN '$datejour1' AND '$datejour2') and (STRUCTURED $eph) ";
		$query1 = mysql_query($req);   
		$totalmbr11=mysql_num_rows($query1);
		
		$query="SELECT $valeurs,count($valeurs)as nbr FROM deceshosp where (DINS BETWEEN '$datejour1' AND '$datejour2') and (STRUCTURED $eph)  GROUP BY $valeurs  order by $valeurs asc "; //    % %will search form 0-9,a-z            
		$resultat=mysql_query($query);
		$totalmbr1=mysql_num_rows($resultat);
		while($row=mysql_fetch_object($resultat))
		{
			$this->SetFont('Times', '', 10);
			$this->cell(40,4,trim($row->$valeurs),1,0,'C',0);
			//$this->cell(40,4,html_entity_decode(utf8_decode($this->stringtostring('service','ids',$row->$valeurs,'servicefr'))).' ('.$row->$valeurs.')' ,1,0,'L',0);
			$this->cell(20,4,trim($row->nbr),1,0,'C',0);
			$this->cell(45,4,round(($row->nbr*100)/$totalmbr11,2).' %',1,0,'C',0);
			$this->SetXY(5,$this->GetY()+4); 
		   //$TA1=$this->nbrservice($row->$valeurs,$datejour1,$datejour2);
		
		}
		$this->SetXY(5,$this->GetY());	  
		$this->cell(40,5,"Total ".$totalmbr1." : SERVICE",1,0,'L',1,0);	  
		$this->cell(20,5,$totalmbr11,1,0,'C',1,0);	  
		$this->cell(45,5,'100%',1,0,'C',1,0);
	// $TA1=$this->nbrservice(1,$datejour1,$datejour2,$eph);	
	// $TA2=$this->nbrservice(2,$datejour1,$datejour2,$eph);	
	// $TA3=$this->nbrservice(3,$datejour1,$datejour2,$eph);	
	// $TA4=$this->nbrservice(4,$datejour1,$datejour2,$eph);	
	// $TA5=$this->nbrservice(5,$datejour1,$datejour2,$eph);	
	// $TA6=$this->nbrservice(6,$datejour1,$datejour2,$eph);	
	// $TA7=$this->nbrservice(7,$datejour1,$datejour2,$eph);	
	// $TA8=$this->nbrservice(8,$datejour1,$datejour2,$eph);	
	// $TA9=$this->nbrservice(9,$datejour1,$datejour2,$eph);	
	// $TA15=$this->nbrservice(15,$datejour1,$datejour2,$eph);	
    // $this->barservice(135,150,$TA1,$TA2,$TA3,$TA4,$TA5,$TA6,$TA7,$TA8,$TA9,$TA15,$titre); 	
	}
	
	function neonat($x,$y,$age,$val,$val1,$titre,$datejour1,$datejour2) 
	{    
		$this->SetFont('Times', 'B', 10);
		$this->SetXY(5,25);$this->cell(285,5,$titre.$age,1,0,'C',1,0);
		$this->SetXY($x,$y);
		$this->cell(20,5,"Age:".$age,1,0,'C',1,0);
	    $this->cell(20,5,"N",1,0,'C',1,0);
		$this->cell(20,5,"P%",1,0,'C',1,0);
		$this->SetXY($x,$y+5);
		$IDWIL=17000;
		$ANNEE='2016';
		$this->mysqlconnect();
		$req="SELECT * FROM deceshosp  where ($age BETWEEN $val and $val1)  and  (DINS BETWEEN '$datejour1' AND '$datejour2')";
		$query1 = mysql_query($req);   
		$totalmbr11=mysql_num_rows($query1);
		$query="SELECT $age,count($age)as nbr FROM deceshosp where ($age BETWEEN $val and $val1)  and  (DINS BETWEEN '$datejour1' AND '$datejour2') GROUP BY $age  order by $age asc "; //    % %will search form 0-9,a-z            
		$resultat=mysql_query($query);
		$totalmbr1=mysql_num_rows($resultat);
		while($row=mysql_fetch_object($resultat))
		{
			$this->SetFont('Times', '', 10);
			$this->cell(20,4,trim($row->$age),1,0,'C',0);
			$this->cell(20,4,trim($row->nbr),1,0,'C',0);
			$this->cell(20,4,round(($row->nbr*100)/$totalmbr11,2).' %',1,0,'C',0);
			$this->SetXY($x,$this->GetY()+4); 
		}
		$this->SetXY($x,$this->GetY());$this->cell(20,5,"Total",1,0,'C',1,0);	  
		$this->cell(20,5,$totalmbr11,1,0,'C',1,0);	  
		$this->cell(20,5,'100%',1,0,'C',1,0);  
	}
	
	function neonat1($age,$val,$val1,$titre,$datejour1,$datejour2) 
	{    
		$this->SetFont('Times', 'B', 10);
		$this->SetXY(5,25);$this->cell(285,5,$titre.$age,1,0,'C',1,0);
		$this->SetXY(5,35);
		$this->cell(20,5,"Age:".$age,1,0,'C',1,0);
	    $this->cell(20,5,"N",1,0,'C',1,0);
		$this->cell(20,5,"P%",1,0,'C',1,0);
		$this->SetXY(5,40);
		$IDWIL=17000;
		$ANNEE='2016';
		$this->mysqlconnect();
		$req="SELECT * FROM deceshosp  where  (DINS BETWEEN '$datejour1' AND '$datejour2')";
		$query1 = mysql_query($req);   
		$totalmbr11=mysql_num_rows($query1);
		$query="SELECT $age,count($age)as nbr FROM deceshosp where   (DINS BETWEEN '$datejour1' AND '$datejour2') GROUP BY $age  order by $age asc "; //    % %will search form 0-9,a-z            
		$resultat=mysql_query($query);
		$totalmbr1=mysql_num_rows($resultat);
		while($row=mysql_fetch_object($resultat))
		{
			$this->SetFont('Times', '', 10);
			$this->cell(20,4,trim($row->$age),1,0,'C',0);
			$this->cell(20,4,trim($row->nbr),1,0,'C',0);
			$this->cell(20,4,round(($row->nbr*100)/$totalmbr11,2).' %',1,0,'C',0);
			$this->SetXY(5,$this->GetY()+4); 
		}
		$this->SetXY(5,$this->GetY());$this->cell(20,5,"Total",1,0,'C',1,0);	  
		$this->cell(20,5,$totalmbr11,1,0,'C',1,0);	  
		$this->cell(20,5,'100%',1,0,'C',1,0);  
	}
	
	function pie2($data)
    {
	$xc=$data['x'];
	$yc=$data['y'];
	$r=$data['r'];
	if ($data['v1']+$data['v2'] > 0){$tot=$data['v1']+$data['v2'];}else {$tot=1;}
	$p1=round($data['v1']*100/$tot,2);
	$p2=round($data['v2']*100/$tot,2);
	$a1=$p1*3.6;
	$a2=$a1+($p2*3.6);
	$this->SetFont('Times', 'BIU', 11);
	$this->SetXY($xc-20,$yc-25);$this->Cell(0, 5,$data['t0'] ,0, 2, 'L');
	$this->RoundedRect($xc-20,$yc-25, 90, 45, 2, $style = '');
	$this->SetFont('Times', 'B', 11);
	$this->SetFillColor(120,120,255);$this->Sector($xc,$yc,$r,0,$a1);
	$this->SetXY($xc+25,$yc-15);$this->cell(10,5,'',1,0,'C',1,0);$this->cell(10,5,$data['t1'],1,0,'C',0,0);$this->cell(20,5,$p1.'%',1,0,'C',0,0);
	$this->SetFillColor(120,255,120);$this->Sector($xc,$yc,$r,$a1,$a2);
	$this->SetXY($xc+25,$yc-5);$this->cell(10,5,'',1,0,'C',1,0);$this->cell(10,5,$data['t2'],1,0,'C',0,0);$this->cell(20,5,$p2.'%',1,0,'C',0,0);
	$this->SetFillColor(230);//fond gris il faut ajouter au cell un autre parametre pour qui accepte la coloration
	$this->SetTextColor(0,0,0);//text noire
	$this->SetFont('Times', 'B', 11);
	}
	
	function mdobarmois($datejour1,$datejour2,$STRUCTURED,$MDO)
	{
	$this->mysqlconnect();
	$sql = " select * from mdo1 where   (DATEMDO BETWEEN '$datejour1' AND '$datejour2') and ( STRUCTURE $STRUCTURED AND MDO=$MDO) ";
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$collecte=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $collecte;
	}
	
	
	function barmois($x,$y,$a,$b,$c,$d,$e,$f,$g,$h,$i,$j,$k,$l,$titre)
    {
	if ($a+$b+$c+$d+$e+$f+$g+$h+$i+$j+$k+$l > 0){$total=$a+$b+$c+$d+$e+$f+$g+$h+$i+$j+$k+$l;}else {$total=1;}
	$ap=round($a*100/$total,2);
	$bp=round($b*100/$total,2);
	$cp=round($c*100/$total,2);
	$dp=round($d*100/$total,2);
	$ep=round($e*100/$total,2);
	$fp=round($f*100/$total,2);
	$gp=round($g*100/$total,2);
	$hp=round($h*100/$total,2);
	$ip=round($i*100/$total,2);
	$jp=round($j*100/$total,2);
	$kp=round($k*100/$total,2);
	$lp=round($l*100/$total,2);
	$this->SetFont('Times', 'BIU', 11);
	$this->SetXY($x-20,$y-108);$this->Cell(0, 5,$titre ,0, 2, 'L');
	$this->RoundedRect($x-20,$y-108, 90, 130, 2, $style = '');
	$this->SetFont('Times', 'B',08);$this->SetFillColor(120,255,120);
	// $this->SetXY($x-5,$y);$this->cell(0.5,-100,'',1,0,'L',1,0);
	// $this->SetXY($x-19,$y-100);$this->cell(13,5,'100 % ',1,0,'L',1,0);
	// $this->SetXY($x-19,$y-50);$this->cell(13,5,'50 % ',1,0,'L',1,0);
	// $this->SetXY($x-19,$y-05);$this->cell(13,5,'00 % ',1,0,'L',1,0);
	$w=7.5;
	$this->SetXY($x-20,$y+10);   
	$this->cell($w,-$ap,$ap,1,0,'C',1,0);        
	$this->cell($w,-$bp,$bp,1,0,'C',1,0);
	$this->cell($w,-$cp,$cp,1,0,'C',1,0);
	$this->cell($w,-$dp,$dp,1,0,'C',1,0);
	$this->cell($w,-$ep,$ep,1,0,'C',1,0);
	$this->cell($w,-$fp,$fp,1,0,'C',1,0);
	$this->cell($w,-$gp,$gp,1,0,'C',1,0);
	$this->cell($w,-$hp,$hp,1,0,'C',1,0);
	$this->cell($w,-$ip,$ip,1,0,'C',1,0);
	$this->cell($w,-$jp,$jp,1,0,'C',1,0);
	$this->cell($w,-$kp,$kp,1,0,'C',1,0);
	$this->cell($w,-$lp,$lp,1,0,'C',1,0);
	$this->SetXY($x-20,$y+12);    
	$this->cell($w,5,'01',1,0,'C',0,0);
	$this->cell($w,5,'02',1,0,'C',0,0);
	$this->cell($w,5,'03',1,0,'C',0,0);
	$this->cell($w,5,'04',1,0,'C',0,0);
	$this->cell($w,5,'05',1,0,'C',0,0);
	$this->cell($w,5,'06',1,0,'C',0,0);
	$this->cell($w,5,'07',1,0,'C',0,0);
	$this->cell($w,5,'08',1,0,'C',0,0);
	$this->cell($w,5,'09',1,0,'C',0,0);
	$this->cell($w,5,'10',1,0,'C',0,0);
	$this->cell($w,5,'11',1,0,'C',0,0);
	$this->cell($w,5,'12',1,0,'C',0,0);
	$this->SetFillColor(230);//fond gris il faut ajouter au cell un autre parametre pour qui accepte la coloration
	$this->SetTextColor(0,0,0);//text noire
	$this->SetFont('Times', 'B', 11);
	}
	function bar($x,$y,$a,$b,$c,$d,$e,$f,$g,$h,$i,$j,$titre)
    {
	if ($a+$b+$c+$d+$e+$f+$g+$h+$i+$j > 0){$total=$a+$b+$c+$d+$e+$f+$g+$h+$i+$j;}else {$total=1;}
	$ap=round($a*100/$total,2);
	$bp=round($b*100/$total,2);
	$cp=round($c*100/$total,2);
	$dp=round($d*100/$total,2);
	$ep=round($e*100/$total,2);
	$fp=round($f*100/$total,2);
	$gp=round($g*100/$total,2);
	$hp=round($h*100/$total,2);
	$ip=round($i*100/$total,2);
	$jp=round($j*100/$total,2);
	$this->SetFont('Times', 'BIU', 11);
	$this->SetXY($x-20,$y-108);$this->Cell(0, 5,$titre ,0, 2, 'L');
	$this->RoundedRect($x-20,$y-108, 90, 130, 2, $style = '');
	$this->SetFont('Times', 'B',08);$this->SetFillColor(120,255,120);
	// $this->SetXY($x-5,$y);$this->cell(0.5,-100,'',1,0,'L',1,0);
	// $this->SetXY($x-19,$y-100);$this->cell(13,5,'100 % ',1,0,'L',1,0);
	// $this->SetXY($x-19,$y-50);$this->cell(13,5,'50 % ',1,0,'L',1,0);
	// $this->SetXY($x-19,$y-05);$this->cell(13,5,'00 % ',1,0,'L',1,0);
	$w=9;
	$this->SetXY($x-20,$y+10);   
	$this->cell($w,-$ap,$ap,1,0,'C',1,0);        
	$this->cell($w,-$bp,$bp,1,0,'C',1,0);
	$this->cell($w,-$cp,$cp,1,0,'C',1,0);
	$this->cell($w,-$dp,$dp,1,0,'C',1,0);
	$this->cell($w,-$ep,$ep,1,0,'C',1,0);
	$this->cell($w,-$fp,$fp,1,0,'C',1,0);
	$this->cell($w,-$gp,$gp,1,0,'C',1,0);
	$this->cell($w,-$hp,$hp,1,0,'C',1,0);
	$this->cell($w,-$ip,$ip,1,0,'C',1,0);
	$this->cell($w,-$jp,$jp,1,0,'C',1,0);
	$this->SetXY($x-20,$y+12);    
	$this->cell($w,5,'00-09',1,0,'C',0,0);
	$this->cell($w,5,'10-19',1,0,'C',0,0);
	$this->cell($w,5,'20-29',1,0,'C',0,0);
	$this->cell($w,5,'30-39',1,0,'C',0,0);
	$this->cell($w,5,'40-49',1,0,'C',0,0);
	$this->cell($w,5,'50-59',1,0,'C',0,0);
	$this->cell($w,5,'60-69',1,0,'C',0,0);
	$this->cell($w,5,'70-79',1,0,'C',0,0);
	$this->cell($w,5,'80-89',1,0,'C',0,0);
	$this->cell($w,5,'90-99',1,0,'C',0,0);
	$this->SetFillColor(230);//fond gris il faut ajouter au cell un autre parametre pour qui accepte la coloration
	$this->SetTextColor(0,0,0);//text noire
	$this->SetFont('Times', 'B', 11);
	}
	function barservice($x,$y,$a,$b,$c,$d,$e,$f,$g,$h,$i,$j,$titre)
    {
	$total=$a+$b+$c+$d+$e+$f+$g+$h+$i+$j;
	$ap=round($a*100/$total,2);
	$bp=round($b*100/$total,2);
	$cp=round($c*100/$total,2);
	$dp=round($d*100/$total,2);
	$ep=round($e*100/$total,2);
	$fp=round($f*100/$total,2);
	$gp=round($g*100/$total,2);
	$hp=round($h*100/$total,2);
	$ip=round($i*100/$total,2);
	$jp=round($j*100/$total,2);
	$this->SetFont('Times', 'BIU', 11);
	$this->SetXY($x-20,$y-108);$this->Cell(0, 5,$titre ,0, 2, 'L');
	$this->RoundedRect($x-20,$y-108, 90, 130, 2, $style = '');
	$this->SetFont('Times', 'B',08);$this->SetFillColor(120,255,120);
	// $this->SetXY($x-5,$y);$this->cell(0.5,-100,'',1,0,'L',1,0);
	// $this->SetXY($x-19,$y-100);$this->cell(13,5,'100 % ',1,0,'L',1,0);
	// $this->SetXY($x-19,$y-50);$this->cell(13,5,'50 % ',1,0,'L',1,0);
	// $this->SetXY($x-19,$y-05);$this->cell(13,5,'00 % ',1,0,'L',1,0);
	$w=9;
	$this->SetXY($x-20,$y+10);   
	$this->cell($w,-$ap,$ap,1,0,'C',1,0);        
	$this->cell($w,-$bp,$bp,1,0,'C',1,0);
	$this->cell($w,-$cp,$cp,1,0,'C',1,0);
	$this->cell($w,-$dp,$dp,1,0,'C',1,0);
	$this->cell($w,-$ep,$ep,1,0,'C',1,0);
	$this->cell($w,-$fp,$fp,1,0,'C',1,0);
	$this->cell($w,-$gp,$gp,1,0,'C',1,0);
	$this->cell($w,-$hp,$hp,1,0,'C',1,0);
	$this->cell($w,-$ip,$ip,1,0,'C',1,0);
	$this->cell($w,-$jp,$jp,1,0,'C',1,0);
	$this->SetXY($x-20,$y+12);    
	$this->cell($w,5,'1',1,0,'C',0,0);
	$this->cell($w,5,'2',1,0,'C',0,0);
	$this->cell($w,5,'3',1,0,'C',0,0);
	$this->cell($w,5,'4',1,0,'C',0,0);
	$this->cell($w,5,'5',1,0,'C',0,0);
	$this->cell($w,5,'6',1,0,'C',0,0);
	$this->cell($w,5,'7',1,0,'C',0,0);
	$this->cell($w,5,'8',1,0,'C',0,0);
	$this->cell($w,5,'9',1,0,'C',0,0);
	$this->cell($w,5,'15',1,0,'C',0,0);
	$this->SetFillColor(230);//fond gris il faut ajouter au cell un autre parametre pour qui accepte la coloration
	$this->SetTextColor(0,0,0);//text noire
	$this->SetFont('Times', 'B', 11);
	}
	function bar5($x,$y,$a,$b,$c,$d,$e,$titre)
    {
	if($a+$b+$c+$d+$e>0){$total=$a+$b+$c+$d+$e;}else{$total=1;}
	$ap=round($a*100/$total,2);
	$bp=round($b*100/$total,2);
	$cp=round($c*100/$total,2);
	$dp=round($d*100/$total,2);
	$ep=round($e*100/$total,2);
	$this->SetFont('Times', 'BIU', 11);
	$this->SetXY($x-20,$y-108);$this->Cell(0, 5,$titre ,0, 2, 'L');
	$this->RoundedRect($x-20,$y-108, 90, 130, 2, $style = '');
	$this->SetFont('Times', 'B',08);$this->SetFillColor(120,255,120);
	// $this->SetXY($x-5,$y);$this->cell(0.5,-100,'',1,0,'L',1,0);
	// $this->SetXY($x-19,$y-100);$this->cell(13,5,'100 % ',1,0,'L',1,0);
	// $this->SetXY($x-19,$y-50);$this->cell(13,5,'50 % ',1,0,'L',1,0);
	// $this->SetXY($x-19,$y-05);$this->cell(13,5,'00 % ',1,0,'L',1,0);
	$w=18;
	$this->SetXY($x-20,$y+10);   
	$this->cell($w,-$ap,$ap,1,0,'C',1,0);        
	$this->cell($w,-$bp,$bp,1,0,'C',1,0);
	$this->cell($w,-$cp,$cp,1,0,'C',1,0);
	$this->cell($w,-$dp,$dp,1,0,'C',1,0);
	$this->cell($w,-$ep,$ep,1,0,'C',1,0);
	
	$this->SetXY($x-20,$y+12);    
	$this->cell($w,5,'00-07',1,0,'C',0,0);
	$this->cell($w,5,'08-28',1,0,'C',0,0);
	$this->cell($w,5,'01-01',1,0,'C',0,0);
	$this->cell($w,5,'01-04',1,0,'C',0,0);
	$this->cell($w,5,'05-15',1,0,'C',0,0);
	
	$this->SetFillColor(230);//fond gris il faut ajouter au cell un autre parametre pour qui accepte la coloration
	$this->SetTextColor(0,0,0);//text noire
	$this->SetFont('Times', 'B', 11);
	}
	
	function bar7($x,$y,$a,$b,$c,$d,$e,$f,$g,$titre)
    {
	if($a+$b+$c+$d+$e+$f+$g>0){$total=$a+$b+$c+$d+$e+$f+$g;}else{$total=1;}
	$ap=round($a*100/$total,2);
	$bp=round($b*100/$total,2);
	$cp=round($c*100/$total,2);
	$dp=round($d*100/$total,2);
	$ep=round($e*100/$total,2);
	$fp=round($f*100/$total,2);
	$gp=round($g*100/$total,2);
	$this->SetFont('Times', 'BIU', 11);
	$this->SetXY($x-20,$y-108);$this->Cell(0, 5,$titre ,0, 2, 'L');
	$this->RoundedRect($x-20,$y-108, 90, 130, 2, $style = '');
	$this->SetFont('Times', 'B',08);$this->SetFillColor(120,255,120);
	// $this->SetXY($x-5,$y);$this->cell(0.5,-100,'',1,0,'L',1,0);
	// $this->SetXY($x-19,$y-100);$this->cell(13,5,'100 % ',1,0,'L',1,0);
	// $this->SetXY($x-19,$y-50);$this->cell(13,5,'50 % ',1,0,'L',1,0);
	// $this->SetXY($x-19,$y-05);$this->cell(13,5,'00 % ',1,0,'L',1,0);
	$w=12.80;
	$this->SetXY($x-20,$y+10);   
	$this->cell($w,-$ap,$ap,1,0,'C',1,0);        
	$this->cell($w,-$bp,$bp,1,0,'C',1,0);
	$this->cell($w,-$cp,$cp,1,0,'C',1,0);
	$this->cell($w,-$dp,$dp,1,0,'C',1,0);
	$this->cell($w,-$ep,$ep,1,0,'C',1,0);
	$this->cell($w,-$fp,$ep,1,0,'C',1,0);
	$this->cell($w,-$gp,$ep,1,0,'C',1,0);
	$this->SetXY($x-20,$y+12);    
	$this->cell($w,5,'01',1,0,'C',0,0);
	$this->cell($w,5,'02',1,0,'C',0,0);
	$this->cell($w,5,'03',1,0,'C',0,0);
	$this->cell($w,5,'04',1,0,'C',0,0);
	$this->cell($w,5,'05',1,0,'C',0,0);
	$this->cell($w,5,'06',1,0,'C',0,0);
	$this->cell($w,5,'07',1,0,'C',0,0);
	$this->SetFillColor(230);//fond gris il faut ajouter au cell un autre parametre pour qui accepte la coloration
	$this->SetTextColor(0,0,0);//text noire
	$this->SetFont('Times', 'B', 11);
	}
	
	
	
	
	
	
	
	
	
}	