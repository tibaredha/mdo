<?php
require('invoice.php');
class DEC extends PDF_Invoice
{
     
	 public $nomprenom ="tibaredha";
	 public $db_host="localhost";
	 public $db_name="deces"; //probleme avec base de donnes  il faut change  gpts avec mvc   
     public $db_user="root";
     public $db_pass="";
	 public $utf8 = "" ;
	
	
	function dspnbr($datejour1,$datejour2,$STRUCTURED)
	{
	$this->mysqlconnect();
	$sql = " select * from deceshosp where (DINS BETWEEN '$datejour1' AND '$datejour2') and ( STRUCTURED  $STRUCTURED )          ";
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$collecte=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $collecte;
	}
	
	
	function LineGraph($x,$y,$w, $h, $data, $options='', $colors=null, $maxVal=0, $nbDiv=4){
		/*******************************************
		Explain the variables:
		
		$x=x
		$y=y
		$w = the width of the diagram
		$h = the height of the diagram
		$data = the data for the diagram in the form of a multidimensional array
		$options = the possible formatting options which include:
			'V' = Print Vertical Divider lines
			'H' = Print Horizontal Divider Lines
			'kB' = Print bounding box around the Key (legend)
			'vB' = Print bounding box around the values under the graph
			'gB' = Print bounding box around the graph
			'dB' = Print bounding box around the entire diagram
		$colors = A multidimensional array containing RGB values
		$maxVal = The Maximum Value for the graph vertically
		$nbDiv = The number of vertical Divisions
		*******************************************/
		$this->SetDrawColor(0,0,0);
		$this->SetLineWidth(0.2);
		$keys = array_keys($data);
		$ordinateWidth = 10;
		$w -= $ordinateWidth;
		$valX = $this->getX()+$x+$ordinateWidth;
		$valY = $this->getY()+$y;
		$margin = 1;
		$titleH = 8;
		$titleW = $w;
		$lineh = 5;
		$keyH = count($data)*$lineh;
		$keyW = $w/5;
		$graphValH = 5;
		$graphValW = $w-$keyW-3*$margin;
		$graphH = $h-(3*$margin)-$graphValH;
		$graphW = $w-(2*$margin)-($keyW+$margin);
		$graphX = $valX+$margin;
		$graphY = $valY+$margin;
		$graphValX = $valX+$margin;
		$graphValY = $valY+2*$margin+$graphH;
		$keyX = $valX+(2*$margin)+$graphW;
		$keyY = $valY+$margin+.5*($h-(2*$margin))-.5*($keyH);
		//draw graph frame border
		if(strstr($options,'gB')){
			$this->Rect($valX,$valY,$w,$h);
		}
		//draw graph diagram border
		if(strstr($options,'dB')){
			$this->Rect($valX+$margin,$valY+$margin,$graphW,$graphH);
		}
		//draw key legend border
		if(strstr($options,'kB')){
			$this->Rect($keyX,$keyY,$keyW,$keyH);
		}
		//draw graph value box
		if(strstr($options,'vB')){
			$this->Rect($graphValX,$graphValY,$graphValW,$graphValH);
		}
		//define colors
		if($colors===null){
			$safeColors = array(0,51,102,153,204,225);
			for($i=0;$i<count($data);$i++){
				$colors[$keys[$i]] = array($safeColors[array_rand($safeColors)],$safeColors[array_rand($safeColors)],$safeColors[array_rand($safeColors)]);
			}
		}
		//form an array with all data values from the multi-demensional $data array
		$ValArray = array();
		foreach($data as $key => $value){
			foreach($data[$key] as $val){
				$ValArray[]=$val;					
			}
		}
		//define max value
		if($maxVal<ceil(max($ValArray))){
			$maxVal = ceil(max($ValArray));
		}
		//draw horizontal lines
		$vertDivH = $graphH/$nbDiv;
		if(strstr($options,'H')){
			for($i=0;$i<=$nbDiv;$i++){
				if($i<$nbDiv){
					$this->Line($graphX,$graphY+$i*$vertDivH,$graphX+$graphW,$graphY+$i*$vertDivH);
				} else{
					$this->Line($graphX,$graphY+$graphH,$graphX+$graphW,$graphY+$graphH);
				}
			}
		}
		//draw vertical lines
		$horiDivW = floor($graphW/(count($data[$keys[0]])-1));
		if(strstr($options,'V')){
			for($i=0;$i<=(count($data[$keys[0]])-1);$i++){
				if($i<(count($data[$keys[0]])-1)){
					$this->Line($graphX+$i*$horiDivW,$graphY,$graphX+$i*$horiDivW,$graphY+$graphH);
				} else {
					$this->Line($graphX+$graphW,$graphY,$graphX+$graphW,$graphY+$graphH);
				}
			}
		}
		//draw graph lines
		foreach($data as $key => $value){
			$this->setDrawColor($colors[$key][0],$colors[$key][1],$colors[$key][2]);
			$this->SetLineWidth(0.8);
			$valueKeys = array_keys($value);
			for($i=0;$i<count($value);$i++){
				if($i==count($value)-2){
					$this->Line(
						$graphX+($i*$horiDivW),
						$graphY+$graphH-($value[$valueKeys[$i]]/$maxVal*$graphH),
						$graphX+$graphW,
						$graphY+$graphH-($value[$valueKeys[$i+1]]/$maxVal*$graphH)
					);
				} else if($i<(count($value)-1)) {
					$this->Line(
						$graphX+($i*$horiDivW),
						$graphY+$graphH-($value[$valueKeys[$i]]/$maxVal*$graphH),
						$graphX+($i+1)*$horiDivW,
						$graphY+$graphH-($value[$valueKeys[$i+1]]/$maxVal*$graphH)
					);
				}
			}
			//Set the Key (legend)
			$this->SetFont('Courier','',10);
			if(!isset($n))$n=0;
			$this->Line($keyX+1,$keyY+$lineh/2+$n*$lineh,$keyX+8,$keyY+$lineh/2+$n*$lineh);
			$this->SetXY($keyX+8,$keyY+$n*$lineh);
			$this->Cell($keyW,$lineh,$key,0,1,'L');
			$n++;
		}
		//print the abscissa values
		foreach($valueKeys as $key => $value){
			if($key==0){
				$this->SetXY($graphValX,$graphValY);
				$this->Cell(30,$lineh,$value,0,0,'L');
			} else if($key==count($valueKeys)-1){
				$this->SetXY($graphValX+$graphValW-30,$graphValY);
				$this->Cell(30,$lineh,$value,0,0,'R');
			} else {
				$this->SetXY($graphValX+$key*$horiDivW-15,$graphValY);
				$this->Cell(30,$lineh,$value,0,0,'C');
			}
		}
		//print the ordinate values
		for($i=0;$i<=$nbDiv;$i++){
			$this->SetXY($graphValX-10,$graphY+($nbDiv-$i)*$vertDivH-3);
			$this->Cell(8,6,sprintf('%.1f',$maxVal/$nbDiv*$i),0,0,'R');
		}
		$this->SetDrawColor(0,0,0);
		$this->SetLineWidth(0.2);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function dateUS2FR($date)//2013-01-01
    {
	$J= substr($date,8,2);$M= substr($date,5,2);$A= substr($date,0,4);$dateUS2FR =  $J."-".$M."-".$A ;
    return $dateUS2FR;//01-01-2013
    }	
	function dateFR2US($date)//01/01/2013
	{
	$J= substr($date,0,2);$M= substr($date,3,2);$A= substr($date,6,4);$dateFR2US =  $A."-".$M."-".$J ;
    return $dateFR2US;//2013-01-01
	}
	
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
	function nbrtostring($db_name,$tb_name,$colonename,$colonevalue,$resultatstring) 
	{
	if (is_numeric($colonevalue) and $colonevalue!=='') 
	{ 
	$db_host="localhost"; 
    $db_user="root";
    $db_pass="";
    $cnx = mysql_connect($db_host,$db_user,$db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
    $db  = mysql_select_db($db_name,$cnx) ;
    // mysql_query("SET NAMES 'UTF8' ");
    $result = mysql_query("SELECT * FROM $tb_name where $colonename=$colonevalue" );
    $row=mysql_fetch_object($result);
	$resultat=$row->$resultatstring;
	return $resultat;
	} 
	return $resultat2='-------';
	}
	function stringtostring($tb_name,$colonename,$colonevalue,$resultatstring) 
	{
	if (isset($colonevalue) and $colonevalue!=='' ) 
	{ 
	$this->mysqlconnect();
    $result = mysql_query("SELECT * FROM $tb_name where $colonename='$colonevalue'" );
    $row1=mysql_fetch_object($result);
	// $resultat=$row1->$resultatstring;
	// return $resultat;
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
	function valeurmoisdeceshc($TBL,$COLONE1,$DATEJOUR1,$DATEJOUR2,$STRUCTURED) 
	{
	$this->mysqlconnect();
	$sql = " select * from $TBL where  ($COLONE1 BETWEEN '$DATEJOUR1' AND '$DATEJOUR2')  and  WILAYAR!=17000  and  (STRUCTURED $STRUCTURED)";
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
//non coriger  probleme des hors commune 
		$this->SetXY(8,$this->GetY());
		$this->cell(15,5,"HC",1,0,'C',1,0);
		$this->cell(140,5,"Hors Communes",1,0,'C',1,0);
		$this->cell(20,5,$this->valeurmoisdeceshc('deceshosp','DINS',$datejour1,$datejour2,$STRUCTURED),1,0,'C',1,0);
		$this->cell(20,5,"",1,0,'C',1,0);
		
		$this->SetXY(8,$this->GetY()+5);$this->cell(15,5,"Total",1,0,'C',1,0);	  
		$this->cell(90,5,$totalmbr1."  Communes",1,0,'C',1,0);	  
		$this->cell(20,5,round($rs['total'],2),1,0,'C',1,0);	  
	    $this->cell(30,5,round($rs1['total1'],2),1,0,'C',1,0);	  
		$this->cell(20,5,$this->valeurmoisdecest('','deceshosp','DINS','COMMUNER',$datejour1,$datejour2,'','',$STRUCTURED),1,0,'C',1,0);	  
		$this->cell(20,5,round(($this->valeurmoisdecest('','deceshosp','DINS','COMMUNER',$datejour1,$datejour2,'','',$STRUCTURED)*1000)/round($rs1['total1'],3),3),1,0,'C',1,0);	  
	
	
	}
	function decescimcomm($DATEJOUR1,$DATEJOUR2,$COMMUNER,$STRUCTURED,$CODECIM0) 
	{
	$this->mysqlconnect();
	$sql = " select * from deceshosp where DINS BETWEEN '$DATEJOUR1' AND '$DATEJOUR2' and COMMUNER=$COMMUNER and STRUCTURED $STRUCTURED and CODECIM0=$CODECIM0 ";
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$OP=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $OP;
	}
	function datacimchapitre1($datejour1,$datejour2,$STRUCTURED,$CODECIM0) 
	{
	$data = array(
	"titre"=> 'Nombre De Deces',
	"A"    => '00-00',
	"B"    => '01-10',
	"C"    => '09-100',
	"D"    => '99-1000',
	"E"    => '999-10000',
	"1"    => $this->decescimcomm($datejour1,$datejour2,916,$STRUCTURED,$CODECIM0),//daira  Djelfa
	"2"    => $this->decescimcomm($datejour1,$datejour2,924,$STRUCTURED,$CODECIM0)+$this->decescimcomm($datejour1,$datejour2,925,$STRUCTURED,$CODECIM0),//daira  ainoussera
	"3"    => $this->decescimcomm($datejour1,$datejour2,929,$STRUCTURED,$CODECIM0)+$this->decescimcomm($datejour1,$datejour2,931,$STRUCTURED,$CODECIM0),//daira  birine
	"4"    => $this->decescimcomm($datejour1,$datejour2,929,$STRUCTURED,$CODECIM0)+$this->decescimcomm($datejour1,$datejour2,927,$STRUCTURED,$CODECIM0)+$this->decescimcomm($datejour1,$datejour2,928,$STRUCTURED,$CODECIM0),//daira  sidilaadjel
	"5"    => $this->decescimcomm($datejour1,$datejour2,932,$STRUCTURED,$CODECIM0)+$this->decescimcomm($datejour1,$datejour2,933,$STRUCTURED,$CODECIM0)+$this->decescimcomm($datejour1,$datejour2,934,$STRUCTURED,$CODECIM0),//daira  hadsahari
	"6"    => $this->decescimcomm($datejour1,$datejour2,935,$STRUCTURED,$CODECIM0)+$this->decescimcomm($datejour1,$datejour2,939,$STRUCTURED,$CODECIM0)+$this->decescimcomm($datejour1,$datejour2,941,$STRUCTURED,$CODECIM0)+$this->decescimcomm($datejour1,$datejour2,940,$STRUCTURED,$CODECIM0),//daira  hassibahbah
	"7"    => $this->decescimcomm($datejour1,$datejour2,942,$STRUCTURED,$CODECIM0)+$this->decescimcomm($datejour1,$datejour2,946,$STRUCTURED,$CODECIM0)+$this->decescimcomm($datejour1,$datejour2,947,$STRUCTURED,$CODECIM0),//daira  darchioukhe
	"8"    => $this->decescimcomm($datejour1,$datejour2,920,$STRUCTURED,$CODECIM0)+$this->decescimcomm($datejour1,$datejour2,919,$STRUCTURED,$CODECIM0)+$this->decescimcomm($datejour1,$datejour2,923,$STRUCTURED,$CODECIM0),//daira  charef
	"9"    => $this->decescimcomm($datejour1,$datejour2,917,$STRUCTURED,$CODECIM0)+$this->decescimcomm($datejour1,$datejour2,964,$STRUCTURED,$CODECIM0)+$this->decescimcomm($datejour1,$datejour2,963,$STRUCTURED,$CODECIM0),//daira  idrissia
	"10"   => $this->decescimcomm($datejour1,$datejour2,965,$STRUCTURED,$CODECIM0)+$this->decescimcomm($datejour1,$datejour2,958,$STRUCTURED,$CODECIM0)+$this->decescimcomm($datejour1,$datejour2,962,$STRUCTURED,$CODECIM0)+$this->decescimcomm($datejour1,$datejour2,957,$STRUCTURED,$CODECIM0),//daira  ain elbel
	"11"   => $this->decescimcomm($datejour1,$datejour2,948,$STRUCTURED,$CODECIM0)+$this->decescimcomm($datejour1,$datejour2,952,$STRUCTURED,$CODECIM0)+$this->decescimcomm($datejour1,$datejour2,954,$STRUCTURED,$CODECIM0)+$this->decescimcomm($datejour1,$datejour2,953,$STRUCTURED,$CODECIM0)+$this->decescimcomm($datejour1,$datejour2,951,$STRUCTURED,$CODECIM0),//daira  messaad
	"12"   => $this->decescimcomm($datejour1,$datejour2,967,$STRUCTURED,$CODECIM0)+$this->decescimcomm($datejour1,$datejour2,968,$STRUCTURED,$CODECIM0)+$this->decescimcomm($datejour1,$datejour2,956,$STRUCTURED,$CODECIM0),//daira  faid elbotma
	"916"  => $this->decescimcomm($datejour1,$datejour2,916,$STRUCTURED,$CODECIM0),//daira  Djelfa
	"917"  => $this->decescimcomm($datejour1,$datejour2,917,$STRUCTURED,$CODECIM0),//daira El Idrissia
	"918"  => $this->decescimcomm($datejour1,$datejour2,918,$STRUCTURED,$CODECIM0),//Oum Cheggag
	"919"  => $this->decescimcomm($datejour1,$datejour2,919,$STRUCTURED,$CODECIM0),//El Guedid
	"920"  => $this->decescimcomm($datejour1,$datejour2,920,$STRUCTURED,$CODECIM0),//daira Charef
	"921"  => $this->decescimcomm($datejour1,$datejour2,921,$STRUCTURED,$CODECIM0),//El Hammam
	"922"  => $this->decescimcomm($datejour1,$datejour2,922,$STRUCTURED,$CODECIM0),//Touazi
	"923"  => $this->decescimcomm($datejour1,$datejour2,923,$STRUCTURED,$CODECIM0),//Beni Yacoub
	"924"  => $this->decescimcomm($datejour1,$datejour2,924,$STRUCTURED,$CODECIM0),//daira ainoussera
	"925"  => $this->decescimcomm($datejour1,$datejour2,925,$STRUCTURED,$CODECIM0),//guernini
	"926"  => $this->decescimcomm($datejour1,$datejour2,926,$STRUCTURED,$CODECIM0),//daira sidilaadjel
	"927"  => $this->decescimcomm($datejour1,$datejour2,927,$STRUCTURED,$CODECIM0),//hassifdoul
	"928"  => $this->decescimcomm($datejour1,$datejour2,928,$STRUCTURED,$CODECIM0),//elkhamis
	"929"  => $this->decescimcomm($datejour1,$datejour2,929,$STRUCTURED,$CODECIM0),//daira birine
	"930"  => $this->decescimcomm($datejour1,$datejour2,930,$STRUCTURED,$CODECIM0),//Dra Souary
	"931"  => $this->decescimcomm($datejour1,$datejour2,931,$STRUCTURED,$CODECIM0),//benahar
	"932"  => $this->decescimcomm($datejour1,$datejour2,932,$STRUCTURED,$CODECIM0),//daira hadshari
	"933"  => $this->decescimcomm($datejour1,$datejour2,933,$STRUCTURED,$CODECIM0),//bouiratlahdab
	"934"  => $this->decescimcomm($datejour1,$datejour2,934,$STRUCTURED,$CODECIM0),//ainfka
	"935"  => $this->decescimcomm($datejour1,$datejour2,935,$STRUCTURED,$CODECIM0),//daira hassi bahbah
	"936"  => $this->decescimcomm($datejour1,$datejour2,936,$STRUCTURED,$CODECIM0),//Mouilah
	"937"  => $this->decescimcomm($datejour1,$datejour2,937,$STRUCTURED,$CODECIM0),//El Mesrane
	"938"  => $this->decescimcomm($datejour1,$datejour2,938,$STRUCTURED,$CODECIM0),//Hassi el Mora
	"939"  => $this->decescimcomm($datejour1,$datejour2,939,$STRUCTURED,$CODECIM0),//zaafrane
	"940"  => $this->decescimcomm($datejour1,$datejour2,940,$STRUCTURED,$CODECIM0),//hassi el euche
	"941"  => $this->decescimcomm($datejour1,$datejour2,941,$STRUCTURED,$CODECIM0),//ain maabed
	"942"  => $this->decescimcomm($datejour1,$datejour2,942,$STRUCTURED,$CODECIM0),//daira darchioukh
	"943"  => $this->decescimcomm($datejour1,$datejour2,943,$STRUCTURED,$CODECIM0),//Guendouza
	"944"  => $this->decescimcomm($datejour1,$datejour2,944,$STRUCTURED,$CODECIM0),//El Oguila
	"945"  => $this->decescimcomm($datejour1,$datejour2,945,$STRUCTURED,$CODECIM0),//El Merdja
	"946"  => $this->decescimcomm($datejour1,$datejour2,946,$STRUCTURED,$CODECIM0),//mliliha
	"947"  => $this->decescimcomm($datejour1,$datejour2,947,$STRUCTURED,$CODECIM0),//sidibayzid
	"948"  => $this->decescimcomm($datejour1,$datejour2,948,$STRUCTURED,$CODECIM0),//daira Messad
	"949"  => $this->decescimcomm($datejour1,$datejour2,949,$STRUCTURED,$CODECIM0),//Abdelmadjid
	"950"  => $this->decescimcomm($datejour1,$datejour2,950,$STRUCTURED,$CODECIM0),//Haniet Ouled Salem
	"951"  => $this->decescimcomm($datejour1,$datejour2,951,$STRUCTURED,$CODECIM0),//Guettara
	"952"  => $this->decescimcomm($datejour1,$datejour2,952,$STRUCTURED,$CODECIM0),//Deldoul
	"953"  => $this->decescimcomm($datejour1,$datejour2,953,$STRUCTURED,$CODECIM0),//Sed Rahal
	"954"  => $this->decescimcomm($datejour1,$datejour2,954,$STRUCTURED,$CODECIM0),//Selmana
	"955"  => $this->decescimcomm($datejour1,$datejour2,955,$STRUCTURED,$CODECIM0),//El Gahra
	"956"  => $this->decescimcomm($datejour1,$datejour2,956,$STRUCTURED,$CODECIM0),//Oum Laadham
	"957"  => $this->decescimcomm($datejour1,$datejour2,957,$STRUCTURED,$CODECIM0),//Mouadjebar
	"958"  => $this->decescimcomm($datejour1,$datejour2,958,$STRUCTURED,$CODECIM0),//daira Ain el Ibel
	"959"  => $this->decescimcomm($datejour1,$datejour2,959,$STRUCTURED,$CODECIM0),//Amera
	"960"  => $this->decescimcomm($datejour1,$datejour2,960,$STRUCTURED,$CODECIM0),//N'thila
	"961"  => $this->decescimcomm($datejour1,$datejour2,961,$STRUCTURED,$CODECIM0),//Oued Seddeur
	"962"  => $this->decescimcomm($datejour1,$datejour2,962,$STRUCTURED,$CODECIM0),//Zaccar
	"963"  => $this->decescimcomm($datejour1,$datejour2,963,$STRUCTURED,$CODECIM0),//Douis
	"964"  => $this->decescimcomm($datejour1,$datejour2,964,$STRUCTURED,$CODECIM0),//Ain Chouhada
	"965"  => $this->decescimcomm($datejour1,$datejour2,965,$STRUCTURED,$CODECIM0),//Tadmit
	"966"  => $this->decescimcomm($datejour1,$datejour2,966,$STRUCTURED,$CODECIM0),//El Hiouhi
	"967"  => $this->decescimcomm($datejour1,$datejour2,967,$STRUCTURED,$CODECIM0),//daira Faidh el Botma
	"968"  => $this->decescimcomm($datejour1,$datejour2,968,$STRUCTURED,$CODECIM0) //Amourah
	);		
	return $data;
	}
	function decescimcommcat($DATEJOUR1,$DATEJOUR2,$COMMUNER,$STRUCTURED,$CODECIM0) 
	{
	$this->mysqlconnect();
	$sql = " select * from deceshosp where DINS BETWEEN '$DATEJOUR1' AND '$DATEJOUR2' and COMMUNER=$COMMUNER and STRUCTURED $STRUCTURED and CODECIM=$CODECIM0 ";
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$OP=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $OP;
	}
	function datacimcat1($datejour1,$datejour2,$STRUCTURED,$CODECIM0) 
	{
	$data = array(
	"titre"=> 'Nombre De Deces',
	"A"    => '00-00',
	"B"    => '01-10',
	"C"    => '09-100',
	"D"    => '99-1000',
	"E"    => '999-10000',
	"1"    => $this->decescimcommcat($datejour1,$datejour2,916,$STRUCTURED,$CODECIM0),//daira  Djelfa
	"2"    => $this->decescimcommcat($datejour1,$datejour2,924,$STRUCTURED,$CODECIM0)+$this->decescimcommcat($datejour1,$datejour2,925,$STRUCTURED,$CODECIM0),//daira  ainoussera
	"3"    => $this->decescimcommcat($datejour1,$datejour2,929,$STRUCTURED,$CODECIM0)+$this->decescimcommcat($datejour1,$datejour2,931,$STRUCTURED,$CODECIM0),//daira  birine
	"4"    => $this->decescimcommcat($datejour1,$datejour2,929,$STRUCTURED,$CODECIM0)+$this->decescimcommcat($datejour1,$datejour2,927,$STRUCTURED,$CODECIM0)+$this->decescimcommcat($datejour1,$datejour2,928,$STRUCTURED,$CODECIM0),//daira  sidilaadjel
	"5"    => $this->decescimcommcat($datejour1,$datejour2,932,$STRUCTURED,$CODECIM0)+$this->decescimcommcat($datejour1,$datejour2,933,$STRUCTURED,$CODECIM0)+$this->decescimcommcat($datejour1,$datejour2,934,$STRUCTURED,$CODECIM0),//daira  hadsahari
	"6"    => $this->decescimcommcat($datejour1,$datejour2,935,$STRUCTURED,$CODECIM0)+$this->decescimcommcat($datejour1,$datejour2,939,$STRUCTURED,$CODECIM0)+$this->decescimcommcat($datejour1,$datejour2,941,$STRUCTURED,$CODECIM0)+$this->decescimcommcat($datejour1,$datejour2,940,$STRUCTURED,$CODECIM0),//daira  hassibahbah
	"7"    => $this->decescimcommcat($datejour1,$datejour2,942,$STRUCTURED,$CODECIM0)+$this->decescimcommcat($datejour1,$datejour2,946,$STRUCTURED,$CODECIM0)+$this->decescimcommcat($datejour1,$datejour2,947,$STRUCTURED,$CODECIM0),//daira  darchioukhe
	"8"    => $this->decescimcommcat($datejour1,$datejour2,920,$STRUCTURED,$CODECIM0)+$this->decescimcommcat($datejour1,$datejour2,919,$STRUCTURED,$CODECIM0)+$this->decescimcommcat($datejour1,$datejour2,923,$STRUCTURED,$CODECIM0),//daira  charef
	"9"    => $this->decescimcommcat($datejour1,$datejour2,917,$STRUCTURED,$CODECIM0)+$this->decescimcommcat($datejour1,$datejour2,964,$STRUCTURED,$CODECIM0)+$this->decescimcommcat($datejour1,$datejour2,963,$STRUCTURED,$CODECIM0),//daira  idrissia
	"10"   => $this->decescimcommcat($datejour1,$datejour2,965,$STRUCTURED,$CODECIM0)+$this->decescimcommcat($datejour1,$datejour2,958,$STRUCTURED,$CODECIM0)+$this->decescimcommcat($datejour1,$datejour2,962,$STRUCTURED,$CODECIM0)+$this->decescimcommcat($datejour1,$datejour2,957,$STRUCTURED,$CODECIM0),//daira  ain elbel
	"11"   => $this->decescimcommcat($datejour1,$datejour2,948,$STRUCTURED,$CODECIM0)+$this->decescimcommcat($datejour1,$datejour2,952,$STRUCTURED,$CODECIM0)+$this->decescimcommcat($datejour1,$datejour2,954,$STRUCTURED,$CODECIM0)+$this->decescimcommcat($datejour1,$datejour2,953,$STRUCTURED,$CODECIM0)+$this->decescimcommcat($datejour1,$datejour2,951,$STRUCTURED,$CODECIM0),//daira  messaad
	"12"   => $this->decescimcommcat($datejour1,$datejour2,967,$STRUCTURED,$CODECIM0)+$this->decescimcommcat($datejour1,$datejour2,968,$STRUCTURED,$CODECIM0)+$this->decescimcommcat($datejour1,$datejour2,956,$STRUCTURED,$CODECIM0),//daira  faid elbotma
	"916"  => $this->decescimcommcat($datejour1,$datejour2,916,$STRUCTURED,$CODECIM0),//daira  Djelfa
	"917"  => $this->decescimcommcat($datejour1,$datejour2,917,$STRUCTURED,$CODECIM0),//daira El Idrissia
	"918"  => $this->decescimcommcat($datejour1,$datejour2,918,$STRUCTURED,$CODECIM0),//Oum Cheggag
	"919"  => $this->decescimcommcat($datejour1,$datejour2,919,$STRUCTURED,$CODECIM0),//El Guedid
	"920"  => $this->decescimcommcat($datejour1,$datejour2,920,$STRUCTURED,$CODECIM0),//daira Charef
	"921"  => $this->decescimcommcat($datejour1,$datejour2,921,$STRUCTURED,$CODECIM0),//El Hammam
	"922"  => $this->decescimcommcat($datejour1,$datejour2,922,$STRUCTURED,$CODECIM0),//Touazi
	"923"  => $this->decescimcommcat($datejour1,$datejour2,923,$STRUCTURED,$CODECIM0),//Beni Yacoub
	"924"  => $this->decescimcommcat($datejour1,$datejour2,924,$STRUCTURED,$CODECIM0),//daira ainoussera
	"925"  => $this->decescimcommcat($datejour1,$datejour2,925,$STRUCTURED,$CODECIM0),//guernini
	"926"  => $this->decescimcommcat($datejour1,$datejour2,926,$STRUCTURED,$CODECIM0),//daira sidilaadjel
	"927"  => $this->decescimcommcat($datejour1,$datejour2,927,$STRUCTURED,$CODECIM0),//hassifdoul
	"928"  => $this->decescimcommcat($datejour1,$datejour2,928,$STRUCTURED,$CODECIM0),//elkhamis
	"929"  => $this->decescimcommcat($datejour1,$datejour2,929,$STRUCTURED,$CODECIM0),//daira birine
	"930"  => $this->decescimcommcat($datejour1,$datejour2,930,$STRUCTURED,$CODECIM0),//Dra Souary
	"931"  => $this->decescimcommcat($datejour1,$datejour2,931,$STRUCTURED,$CODECIM0),//benahar
	"932"  => $this->decescimcommcat($datejour1,$datejour2,932,$STRUCTURED,$CODECIM0),//daira hadshari
	"933"  => $this->decescimcommcat($datejour1,$datejour2,933,$STRUCTURED,$CODECIM0),//bouiratlahdab
	"934"  => $this->decescimcommcat($datejour1,$datejour2,934,$STRUCTURED,$CODECIM0),//ainfka
	"935"  => $this->decescimcommcat($datejour1,$datejour2,935,$STRUCTURED,$CODECIM0),//daira hassi bahbah
	"936"  => $this->decescimcommcat($datejour1,$datejour2,936,$STRUCTURED,$CODECIM0),//Mouilah
	"937"  => $this->decescimcommcat($datejour1,$datejour2,937,$STRUCTURED,$CODECIM0),//El Mesrane
	"938"  => $this->decescimcommcat($datejour1,$datejour2,938,$STRUCTURED,$CODECIM0),//Hassi el Mora
	"939"  => $this->decescimcommcat($datejour1,$datejour2,939,$STRUCTURED,$CODECIM0),//zaafrane
	"940"  => $this->decescimcommcat($datejour1,$datejour2,940,$STRUCTURED,$CODECIM0),//hassi el euche
	"941"  => $this->decescimcommcat($datejour1,$datejour2,941,$STRUCTURED,$CODECIM0),//ain maabed
	"942"  => $this->decescimcommcat($datejour1,$datejour2,942,$STRUCTURED,$CODECIM0),//daira darchioukh
	"943"  => $this->decescimcommcat($datejour1,$datejour2,943,$STRUCTURED,$CODECIM0),//Guendouza
	"944"  => $this->decescimcommcat($datejour1,$datejour2,944,$STRUCTURED,$CODECIM0),//El Oguila
	"945"  => $this->decescimcommcat($datejour1,$datejour2,945,$STRUCTURED,$CODECIM0),//El Merdja
	"946"  => $this->decescimcommcat($datejour1,$datejour2,946,$STRUCTURED,$CODECIM0),//mliliha
	"947"  => $this->decescimcommcat($datejour1,$datejour2,947,$STRUCTURED,$CODECIM0),//sidibayzid
	"948"  => $this->decescimcommcat($datejour1,$datejour2,948,$STRUCTURED,$CODECIM0),//daira Messad
	"949"  => $this->decescimcommcat($datejour1,$datejour2,949,$STRUCTURED,$CODECIM0),//Abdelmadjid
	"950"  => $this->decescimcommcat($datejour1,$datejour2,950,$STRUCTURED,$CODECIM0),//Haniet Ouled Salem
	"951"  => $this->decescimcommcat($datejour1,$datejour2,951,$STRUCTURED,$CODECIM0),//Guettara
	"952"  => $this->decescimcommcat($datejour1,$datejour2,952,$STRUCTURED,$CODECIM0),//Deldoul
	"953"  => $this->decescimcommcat($datejour1,$datejour2,953,$STRUCTURED,$CODECIM0),//Sed Rahal
	"954"  => $this->decescimcommcat($datejour1,$datejour2,954,$STRUCTURED,$CODECIM0),//Selmana
	"955"  => $this->decescimcommcat($datejour1,$datejour2,955,$STRUCTURED,$CODECIM0),//El Gahra
	"956"  => $this->decescimcommcat($datejour1,$datejour2,956,$STRUCTURED,$CODECIM0),//Oum Laadham
	"957"  => $this->decescimcommcat($datejour1,$datejour2,957,$STRUCTURED,$CODECIM0),//Mouadjebar
	"958"  => $this->decescimcommcat($datejour1,$datejour2,958,$STRUCTURED,$CODECIM0),//daira Ain el Ibel
	"959"  => $this->decescimcommcat($datejour1,$datejour2,959,$STRUCTURED,$CODECIM0),//Amera
	"960"  => $this->decescimcommcat($datejour1,$datejour2,960,$STRUCTURED,$CODECIM0),//N'thila
	"961"  => $this->decescimcommcat($datejour1,$datejour2,961,$STRUCTURED,$CODECIM0),//Oued Seddeur
	"962"  => $this->decescimcommcat($datejour1,$datejour2,962,$STRUCTURED,$CODECIM0),//Zaccar
	"963"  => $this->decescimcommcat($datejour1,$datejour2,963,$STRUCTURED,$CODECIM0),//Douis
	"964"  => $this->decescimcommcat($datejour1,$datejour2,964,$STRUCTURED,$CODECIM0),//Ain Chouhada
	"965"  => $this->decescimcommcat($datejour1,$datejour2,965,$STRUCTURED,$CODECIM0),//Tadmit
	"966"  => $this->decescimcommcat($datejour1,$datejour2,966,$STRUCTURED,$CODECIM0),//El Hiouhi
	"967"  => $this->decescimcommcat($datejour1,$datejour2,967,$STRUCTURED,$CODECIM0),//daira Faidh el Botma
	"968"  => $this->decescimcommcat($datejour1,$datejour2,968,$STRUCTURED,$CODECIM0) //Amourah
	);		
	return $data;
	}
	function decescomm($DATEJOUR1,$DATEJOUR2,$COMMUNER,$STRUCTURED) 
	{
	$this->mysqlconnect();
	$sql = " select * from deceshosp where DINS BETWEEN '$DATEJOUR1' AND '$DATEJOUR2' and COMMUNER=$COMMUNER and STRUCTURED $STRUCTURED  ";
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$OP=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $OP;
	}
	function datasig($datejour1,$datejour2,$STRUCTURED) 
	{
	$data = array(
	"titre"=> 'Nombre De Deces',
	"A"    => '00-00',
	"B"    => '01-10',
	"C"    => '09-100',
	"D"    => '99-1000',
	"E"    => '999-10000',
	"1"    => $this->decescomm($datejour1,$datejour2,916,$STRUCTURED),//daira  Djelfa
	"2"    => $this->decescomm($datejour1,$datejour2,924,$STRUCTURED)+$this->decescomm($datejour1,$datejour2,925,$STRUCTURED),//daira  ainoussera
	"3"    => $this->decescomm($datejour1,$datejour2,929,$STRUCTURED)+$this->decescomm($datejour1,$datejour2,931,$STRUCTURED),//daira  birine
	"4"    => $this->decescomm($datejour1,$datejour2,929,$STRUCTURED)+$this->decescomm($datejour1,$datejour2,927,$STRUCTURED)+$this->decescomm($datejour1,$datejour2,928,$STRUCTURED),//daira  sidilaadjel
	"5"    => $this->decescomm($datejour1,$datejour2,932,$STRUCTURED)+$this->decescomm($datejour1,$datejour2,933,$STRUCTURED)+$this->decescomm($datejour1,$datejour2,934,$STRUCTURED),//daira  hadsahari
	"6"    => $this->decescomm($datejour1,$datejour2,935,$STRUCTURED)+$this->decescomm($datejour1,$datejour2,939,$STRUCTURED)+$this->decescomm($datejour1,$datejour2,941,$STRUCTURED)+$this->decescomm($datejour1,$datejour2,940,$STRUCTURED),//daira  hassibahbah
	"7"    => $this->decescomm($datejour1,$datejour2,942,$STRUCTURED)+$this->decescomm($datejour1,$datejour2,946,$STRUCTURED)+$this->decescomm($datejour1,$datejour2,947,$STRUCTURED),//daira  darchioukhe
	"8"    => $this->decescomm($datejour1,$datejour2,920,$STRUCTURED)+$this->decescomm($datejour1,$datejour2,919,$STRUCTURED)+$this->decescomm($datejour1,$datejour2,923,$STRUCTURED),//daira  charef
	"9"    => $this->decescomm($datejour1,$datejour2,917,$STRUCTURED)+$this->decescomm($datejour1,$datejour2,964,$STRUCTURED)+$this->decescomm($datejour1,$datejour2,963,$STRUCTURED),//daira  idrissia
	"10"   => $this->decescomm($datejour1,$datejour2,965,$STRUCTURED)+$this->decescomm($datejour1,$datejour2,958,$STRUCTURED)+$this->decescomm($datejour1,$datejour2,962,$STRUCTURED)+$this->decescomm($datejour1,$datejour2,957,$STRUCTURED),//daira  ain elbel
	"11"   => $this->decescomm($datejour1,$datejour2,948,$STRUCTURED)+$this->decescomm($datejour1,$datejour2,952,$STRUCTURED)+$this->decescomm($datejour1,$datejour2,954,$STRUCTURED)+$this->decescomm($datejour1,$datejour2,953,$STRUCTURED)+$this->decescomm($datejour1,$datejour2,951,$STRUCTURED),//daira  messaad
	"12"   => $this->decescomm($datejour1,$datejour2,967,$STRUCTURED)+$this->decescomm($datejour1,$datejour2,968,$STRUCTURED)+$this->decescomm($datejour1,$datejour2,956,$STRUCTURED),//daira  faid elbotma
	"916"  => $this->decescomm($datejour1,$datejour2,916,$STRUCTURED),//daira  Djelfa
	"917"  => $this->decescomm($datejour1,$datejour2,917,$STRUCTURED),//daira El Idrissia
	"918"  => $this->decescomm($datejour1,$datejour2,918,$STRUCTURED),//Oum Cheggag
	"919"  => $this->decescomm($datejour1,$datejour2,919,$STRUCTURED),//El Guedid
	"920"  => $this->decescomm($datejour1,$datejour2,920,$STRUCTURED),//daira Charef
	"921"  => $this->decescomm($datejour1,$datejour2,921,$STRUCTURED),//El Hammam
	"922"  => $this->decescomm($datejour1,$datejour2,922,$STRUCTURED),//Touazi
	"923"  => $this->decescomm($datejour1,$datejour2,923,$STRUCTURED),//Beni Yacoub
	"924"  => $this->decescomm($datejour1,$datejour2,924,$STRUCTURED),//daira ainoussera
	"925"  => $this->decescomm($datejour1,$datejour2,925,$STRUCTURED),//guernini
	"926"  => $this->decescomm($datejour1,$datejour2,926,$STRUCTURED),//daira sidilaadjel
	"927"  => $this->decescomm($datejour1,$datejour2,927,$STRUCTURED),//hassifdoul
	"928"  => $this->decescomm($datejour1,$datejour2,928,$STRUCTURED),//elkhamis
	"929"  => $this->decescomm($datejour1,$datejour2,929,$STRUCTURED),//daira birine
	"930"  => $this->decescomm($datejour1,$datejour2,930,$STRUCTURED),//Dra Souary
	"931"  => $this->decescomm($datejour1,$datejour2,931,$STRUCTURED),//benahar
	"932"  => $this->decescomm($datejour1,$datejour2,932,$STRUCTURED),//daira hadshari
	"933"  => $this->decescomm($datejour1,$datejour2,933,$STRUCTURED),//bouiratlahdab
	"934"  => $this->decescomm($datejour1,$datejour2,934,$STRUCTURED),//ainfka
	"935"  => $this->decescomm($datejour1,$datejour2,935,$STRUCTURED),//daira hassi bahbah
	"936"  => $this->decescomm($datejour1,$datejour2,936,$STRUCTURED),//Mouilah
	"937"  => $this->decescomm($datejour1,$datejour2,937,$STRUCTURED),//El Mesrane
	"938"  => $this->decescomm($datejour1,$datejour2,938,$STRUCTURED),//Hassi el Mora
	"939"  => $this->decescomm($datejour1,$datejour2,939,$STRUCTURED),//zaafrane
	"940"  => $this->decescomm($datejour1,$datejour2,940,$STRUCTURED),//hassi el euche
	"941"  => $this->decescomm($datejour1,$datejour2,941,$STRUCTURED),//ain maabed
	"942"  => $this->decescomm($datejour1,$datejour2,942,$STRUCTURED),//daira darchioukh
	"943"  => $this->decescomm($datejour1,$datejour2,943,$STRUCTURED),//Guendouza
	"944"  => $this->decescomm($datejour1,$datejour2,944,$STRUCTURED),//El Oguila
	"945"  => $this->decescomm($datejour1,$datejour2,945,$STRUCTURED),//El Merdja
	"946"  => $this->decescomm($datejour1,$datejour2,946,$STRUCTURED),//mliliha
	"947"  => $this->decescomm($datejour1,$datejour2,947,$STRUCTURED),//sidibayzid
	"948"  => $this->decescomm($datejour1,$datejour2,948,$STRUCTURED),//daira Messad
	"949"  => $this->decescomm($datejour1,$datejour2,949,$STRUCTURED),//Abdelmadjid
	"950"  => $this->decescomm($datejour1,$datejour2,950,$STRUCTURED),//Haniet Ouled Salem
	"951"  => $this->decescomm($datejour1,$datejour2,951,$STRUCTURED),//Guettara
	"952"  => $this->decescomm($datejour1,$datejour2,952,$STRUCTURED),//Deldoul
	"953"  => $this->decescomm($datejour1,$datejour2,953,$STRUCTURED),//Sed Rahal
	"954"  => $this->decescomm($datejour1,$datejour2,954,$STRUCTURED),//Selmana
	"955"  => $this->decescomm($datejour1,$datejour2,955,$STRUCTURED),//El Gahra
	"956"  => $this->decescomm($datejour1,$datejour2,956,$STRUCTURED),//Oum Laadham
	"957"  => $this->decescomm($datejour1,$datejour2,957,$STRUCTURED),//Mouadjebar
	"958"  => $this->decescomm($datejour1,$datejour2,958,$STRUCTURED),//daira Ain el Ibel
	"959"  => $this->decescomm($datejour1,$datejour2,959,$STRUCTURED),//Amera
	"960"  => $this->decescomm($datejour1,$datejour2,960,$STRUCTURED),//N'thila
	"961"  => $this->decescomm($datejour1,$datejour2,961,$STRUCTURED),//Oued Seddeur
	"962"  => $this->decescomm($datejour1,$datejour2,962,$STRUCTURED),//Zaccar
	"963"  => $this->decescomm($datejour1,$datejour2,963,$STRUCTURED),//Douis
	"964"  => $this->decescomm($datejour1,$datejour2,964,$STRUCTURED),//Ain Chouhada
	"965"  => $this->decescomm($datejour1,$datejour2,965,$STRUCTURED),//Tadmit
	"966"  => $this->decescomm($datejour1,$datejour2,966,$STRUCTURED),//El Hiouhi
	"967"  => $this->decescomm($datejour1,$datejour2,967,$STRUCTURED),//daira Faidh el Botma
	"968"  => $this->decescomm($datejour1,$datejour2,968,$STRUCTURED) //Amourah
	);		
	return $data;
	}
	
	function DECMAT($colone1,$colone2,$colone3,$datejour1,$datejour2,$SEXEDNR,$STRUCTURED)
	{
	$this->mysqlconnect();
	$sql = " select * from deceshosp where  ($colone1 >=$colone2  and $colone1 <=$colone3)  and (DINS BETWEEN '$datejour1' AND '$datejour2') and (SEX='$SEXEDNR' and STRUCTURED $STRUCTURED )          ";
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$collecte=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $collecte;
	}
	
	function AGESEXE($colone1,$colone2,$colone3,$datejour1,$datejour2,$SEXEDNR,$STRUCTURED)
	{
	$this->mysqlconnect();
	$sql = " select * from deceshosp where  ($colone1 >=$colone2  and $colone1 <=$colone3)  and (DINS BETWEEN '$datejour1' AND '$datejour2') and (SEX='$SEXEDNR' and STRUCTURED $STRUCTURED )          ";
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$collecte=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $collecte;
	}
	function dataagesexe($x,$y,$colone1,$TABLE,$DINS,$COMMUNER,$datejour1,$datejour2,$STRUCTURED) 
	{
	$T2F20=array(
	"xt" => $x,
	"yt" => $y,
	"wc" => "",
	"hc" => "",
	"tt" => "Repartition des deces par tranche d'age et sexe (global)",
	"tc" => "Sexe",
	"tc1" =>"M",
	"tc3" =>"F",
	"tc5" =>"Total",
	"1M"  => $this->AGESEXE($colone1,0,4,$datejour1,$datejour2,'M',$STRUCTURED),    "1F"  => $this->AGESEXE($colone1,0,4,$datejour1,$datejour2,'F',$STRUCTURED),
	"2M"  => $this->AGESEXE($colone1,5,9,$datejour1,$datejour2,'M',$STRUCTURED),    "2F"  => $this->AGESEXE($colone1,5,9,$datejour1,$datejour2,'F',$STRUCTURED),
	"3M"  => $this->AGESEXE($colone1,10,14,$datejour1,$datejour2,'M',$STRUCTURED),  "3F"  => $this->AGESEXE($colone1,10,14,$datejour1,$datejour2,'F',$STRUCTURED),
	"4M"  => $this->AGESEXE($colone1,15,19,$datejour1,$datejour2,'M',$STRUCTURED),  "4F"  => $this->AGESEXE($colone1,15,19,$datejour1,$datejour2,'F',$STRUCTURED),
	"5M"  => $this->AGESEXE($colone1,20,24,$datejour1,$datejour2,'M',$STRUCTURED),  "5F"  => $this->AGESEXE($colone1,20,24,$datejour1,$datejour2,'F',$STRUCTURED),
	"6M"  => $this->AGESEXE($colone1,25,29,$datejour1,$datejour2,'M',$STRUCTURED),  "6F"  => $this->AGESEXE($colone1,25,29,$datejour1,$datejour2,'F',$STRUCTURED),
	"7M"  => $this->AGESEXE($colone1,30,34,$datejour1,$datejour2,'M',$STRUCTURED),  "7F"  => $this->AGESEXE($colone1,30,34,$datejour1,$datejour2,'F',$STRUCTURED),
	"8M"  => $this->AGESEXE($colone1,35,39,$datejour1,$datejour2,'M',$STRUCTURED),  "8F"  => $this->AGESEXE($colone1,35,39,$datejour1,$datejour2,'F',$STRUCTURED),
	"9M"  => $this->AGESEXE($colone1,40,44,$datejour1,$datejour2,'M',$STRUCTURED),  "9F"  => $this->AGESEXE($colone1,40,44,$datejour1,$datejour2,'F',$STRUCTURED),
	"10M" => $this->AGESEXE($colone1,45,49,$datejour1,$datejour2,'M',$STRUCTURED),  "10F" => $this->AGESEXE($colone1,45,49,$datejour1,$datejour2,'F',$STRUCTURED),
	"11M" => $this->AGESEXE($colone1,50,54,$datejour1,$datejour2,'M',$STRUCTURED),  "11F" => $this->AGESEXE($colone1,50,54,$datejour1,$datejour2,'F',$STRUCTURED),
	"12M" => $this->AGESEXE($colone1,55,59,$datejour1,$datejour2,'M',$STRUCTURED),  "12F" => $this->AGESEXE($colone1,55,59,$datejour1,$datejour2,'F',$STRUCTURED),
	"13M" => $this->AGESEXE($colone1,60,64,$datejour1,$datejour2,'M',$STRUCTURED),  "13F" => $this->AGESEXE($colone1,60,64,$datejour1,$datejour2,'F',$STRUCTURED),
	"14M" => $this->AGESEXE($colone1,65,69,$datejour1,$datejour2,'M',$STRUCTURED),  "14F" => $this->AGESEXE($colone1,65,69,$datejour1,$datejour2,'F',$STRUCTURED),
	"15M" => $this->AGESEXE($colone1,70,74,$datejour1,$datejour2,'M',$STRUCTURED),  "15F" => $this->AGESEXE($colone1,70,74,$datejour1,$datejour2,'F',$STRUCTURED),
	"16M" => $this->AGESEXE($colone1,75,79,$datejour1,$datejour2,'M',$STRUCTURED),  "16F" => $this->AGESEXE($colone1,75,79,$datejour1,$datejour2,'F',$STRUCTURED),
	"17M" => $this->AGESEXE($colone1,80,84,$datejour1,$datejour2,'M',$STRUCTURED),  "17F" => $this->AGESEXE($colone1,80,84,$datejour1,$datejour2,'F',$STRUCTURED),
	"18M" => $this->AGESEXE($colone1,85,89,$datejour1,$datejour2,'M',$STRUCTURED),  "18F" => $this->AGESEXE($colone1,85,89,$datejour1,$datejour2,'F',$STRUCTURED),
	"19M" => $this->AGESEXE($colone1,90,94,$datejour1,$datejour2,'M',$STRUCTURED),  "19F" => $this->AGESEXE($colone1,90,94,$datejour1,$datejour2,'F',$STRUCTURED),
	"20M" => $this->AGESEXE($colone1,95,150,$datejour1,$datejour2,'M',$STRUCTURED), "20F" => $this->AGESEXE($colone1,95,150,$datejour1,$datejour2,'F',$STRUCTURED),			
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
	
	function AGESEXECIM($colone1,$colone2,$colone3,$datejour1,$datejour2,$SEXEDNR,$STRUCTURED,$CIM,$CODECIM0)
	{
	$this->mysqlconnect();
	$sql = " select * from deceshosp where  ($colone1 >=$colone2  and $colone1 <=$colone3)  and (DINS BETWEEN '$datejour1' AND '$datejour2') and (SEX='$SEXEDNR' and STRUCTURED $STRUCTURED )  and $CIM=$CODECIM0        ";
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$collecte=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $collecte;
	}
	function dataagesexecim($x,$y,$colone1,$TABLE,$DINS,$COMMUNER,$datejour1,$datejour2,$STRUCTURED,$CIM,$CODECIM0) 
	{
	$T2F20=array(
	"xt" => $x,
	"yt" => $y,
	"wc" => "",
	"hc" => "",
	"tt" => "Repartition des deces par tranche d'age et sexe (global)",
	"tc" => "Sexe",
	"tc1" =>"M",
	"tc3" =>"F",
	"tc5" =>"Total",
	"1M"  => $this->AGESEXECIM($colone1,0,4,$datejour1,$datejour2,'M',$STRUCTURED,$CIM,$CODECIM0),    "1F"  => $this->AGESEXECIM($colone1,0,4,$datejour1,$datejour2,'F',$STRUCTURED,$CIM,$CODECIM0),
	"2M"  => $this->AGESEXECIM($colone1,5,9,$datejour1,$datejour2,'M',$STRUCTURED,$CIM,$CODECIM0),    "2F"  => $this->AGESEXECIM($colone1,5,9,$datejour1,$datejour2,'F',$STRUCTURED,$CIM,$CODECIM0),
	"3M"  => $this->AGESEXECIM($colone1,10,14,$datejour1,$datejour2,'M',$STRUCTURED,$CIM,$CODECIM0),  "3F"  => $this->AGESEXECIM($colone1,10,14,$datejour1,$datejour2,'F',$STRUCTURED,$CIM,$CODECIM0),
	"4M"  => $this->AGESEXECIM($colone1,15,19,$datejour1,$datejour2,'M',$STRUCTURED,$CIM,$CODECIM0),  "4F"  => $this->AGESEXECIM($colone1,15,19,$datejour1,$datejour2,'F',$STRUCTURED,$CIM,$CODECIM0),
	"5M"  => $this->AGESEXECIM($colone1,20,24,$datejour1,$datejour2,'M',$STRUCTURED,$CIM,$CODECIM0),  "5F"  => $this->AGESEXECIM($colone1,20,24,$datejour1,$datejour2,'F',$STRUCTURED,$CIM,$CODECIM0),
	"6M"  => $this->AGESEXECIM($colone1,25,29,$datejour1,$datejour2,'M',$STRUCTURED,$CIM,$CODECIM0),  "6F"  => $this->AGESEXECIM($colone1,25,29,$datejour1,$datejour2,'F',$STRUCTURED,$CIM,$CODECIM0),
	"7M"  => $this->AGESEXECIM($colone1,30,34,$datejour1,$datejour2,'M',$STRUCTURED,$CIM,$CODECIM0),  "7F"  => $this->AGESEXECIM($colone1,30,34,$datejour1,$datejour2,'F',$STRUCTURED,$CIM,$CODECIM0),
	"8M"  => $this->AGESEXECIM($colone1,35,39,$datejour1,$datejour2,'M',$STRUCTURED,$CIM,$CODECIM0),  "8F"  => $this->AGESEXECIM($colone1,35,39,$datejour1,$datejour2,'F',$STRUCTURED,$CIM,$CODECIM0),
	"9M"  => $this->AGESEXECIM($colone1,40,44,$datejour1,$datejour2,'M',$STRUCTURED,$CIM,$CODECIM0),  "9F"  => $this->AGESEXECIM($colone1,40,44,$datejour1,$datejour2,'F',$STRUCTURED,$CIM,$CODECIM0),
	"10M" => $this->AGESEXECIM($colone1,45,49,$datejour1,$datejour2,'M',$STRUCTURED,$CIM,$CODECIM0),  "10F" => $this->AGESEXECIM($colone1,45,49,$datejour1,$datejour2,'F',$STRUCTURED,$CIM,$CODECIM0),
	"11M" => $this->AGESEXECIM($colone1,50,54,$datejour1,$datejour2,'M',$STRUCTURED,$CIM,$CODECIM0),  "11F" => $this->AGESEXECIM($colone1,50,54,$datejour1,$datejour2,'F',$STRUCTURED,$CIM,$CODECIM0),
	"12M" => $this->AGESEXECIM($colone1,55,59,$datejour1,$datejour2,'M',$STRUCTURED,$CIM,$CODECIM0),  "12F" => $this->AGESEXECIM($colone1,55,59,$datejour1,$datejour2,'F',$STRUCTURED,$CIM,$CODECIM0),
	"13M" => $this->AGESEXECIM($colone1,60,64,$datejour1,$datejour2,'M',$STRUCTURED,$CIM,$CODECIM0),  "13F" => $this->AGESEXECIM($colone1,60,64,$datejour1,$datejour2,'F',$STRUCTURED,$CIM,$CODECIM0),
	"14M" => $this->AGESEXECIM($colone1,65,69,$datejour1,$datejour2,'M',$STRUCTURED,$CIM,$CODECIM0),  "14F" => $this->AGESEXECIM($colone1,65,69,$datejour1,$datejour2,'F',$STRUCTURED,$CIM,$CODECIM0),
	"15M" => $this->AGESEXECIM($colone1,70,74,$datejour1,$datejour2,'M',$STRUCTURED,$CIM,$CODECIM0),  "15F" => $this->AGESEXECIM($colone1,70,74,$datejour1,$datejour2,'F',$STRUCTURED,$CIM,$CODECIM0),
	"16M" => $this->AGESEXECIM($colone1,75,79,$datejour1,$datejour2,'M',$STRUCTURED,$CIM,$CODECIM0),  "16F" => $this->AGESEXECIM($colone1,75,79,$datejour1,$datejour2,'F',$STRUCTURED,$CIM,$CODECIM0),
	"17M" => $this->AGESEXECIM($colone1,80,84,$datejour1,$datejour2,'M',$STRUCTURED,$CIM,$CODECIM0),  "17F" => $this->AGESEXECIM($colone1,80,84,$datejour1,$datejour2,'F',$STRUCTURED,$CIM,$CODECIM0),
	"18M" => $this->AGESEXECIM($colone1,85,89,$datejour1,$datejour2,'M',$STRUCTURED,$CIM,$CODECIM0),  "18F" => $this->AGESEXECIM($colone1,85,89,$datejour1,$datejour2,'F',$STRUCTURED,$CIM,$CODECIM0),
	"19M" => $this->AGESEXECIM($colone1,90,94,$datejour1,$datejour2,'M',$STRUCTURED,$CIM,$CODECIM0),  "19F" => $this->AGESEXECIM($colone1,90,94,$datejour1,$datejour2,'F',$STRUCTURED,$CIM,$CODECIM0),
	"20M" => $this->AGESEXECIM($colone1,95,150,$datejour1,$datejour2,'M',$STRUCTURED,$CIM,$CODECIM0), "20F" => $this->AGESEXECIM($colone1,95,150,$datejour1,$datejour2,'F',$STRUCTURED,$CIM,$CODECIM0),			
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
	
	$this->SetXY(5,25+10);$this->cell(285,5,html_entity_decode(utf8_decode("Cette étude a porté sur ".$T." décès survenus durant la periode du ".$this->dateUS2FR($datejour1)." au ".$this->dateUS2FR($datejour2)." au niveau de 36 communes ")),0,0,'L',0);
	$this->SetXY(5,175);  $this->cell(285,5,html_entity_decode(utf8_decode("1-Répartition des décès par sexe : ")),0,0,'L',0);
	$this->SetXY(5,175+5);$this->cell(285,5,html_entity_decode(utf8_decode("La répartition des ".$T." décès enregistrés montre que :")),0,0,'L',0);
	$this->SetXY(5,175+10);$this->cell(285,5,html_entity_decode(utf8_decode(round(($TM/$T)*100,2)."% des décès touche les hommes. ")),0,0,'L',0);
	$this->SetXY(5,175+15);$this->cell(285,5,html_entity_decode(utf8_decode(round(($TF/$T)*100,2)."% des décès touche les femmes. ")),0,0,'L',0);
	if ($TF > 0){$TF0=$TF;}else{$TF0=1;}
	$this->SetXY(5,175+20);$this->cell(285,5,html_entity_decode(utf8_decode("avec un sexe ratio de ".round(($TM/$TF0),2))),0,0,'L',0);
	$this->SetXY(5,175+30);$this->cell(285,5,html_entity_decode(utf8_decode("2-Répartition des décès par tranche d'âge : ")),0,0,'L',0);
	rsort($datamf);
	$this->SetXY(5,175+35);$this->cell(285,5,html_entity_decode(utf8_decode("la proportion des décès la plus élevée est : ".round($datamf[0]*100/$T,2)."%")),0,0,'L',0);
	sort($datamf);
	$this->SetXY(5,175+40);$this->cell(285,5,html_entity_decode(utf8_decode("la proportion des décès la moins élevée est : ".round($datamf[0]*100/$T,2)."%")),0,0,'L',0);
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
	$TA8=$data['8M']+$data['8F'];
	$TA9=$data['9M']+$data['9F'];
	$TA10=$data['10M']+$data['10F'];
	$TA11=$data['11M']+$data['11F'];
	$TA12=$data['12M']+$data['12F'];
	$TA13=$data['13M']+$data['13F'];
	$TA14=$data['14M']+$data['14F'];
	$TA15=$data['15M']+$data['15F'];
	$TA16=$data['16M']+$data['16F'];
	$TA17=$data['17M']+$data['17F'];
	$TA18=$data['18M']+$data['18F'];
	$TA19=$data['19M']+$data['19F'];
	$TA20=$data['20M']+$data['20F'];
	$this->bar20(135,150,$TA1,$TA2,$TA3,$TA4,$TA5,$TA6,$TA7,$TA8,$TA9,$TA10,$TA11,$TA12,$TA13,$TA14,$TA15,$TA16,$TA17,$TA18,$TA19,$TA20,utf8_decode('Distribution des décès par tranche d\'age en année'));
	}
	function bar20($x,$y,$a,$b,$c,$d,$e,$f,$g,$h,$i,$j,$k,$l,$m,$n,$o,$p,$q,$r,$s,$t,$titre)
    {
	if ($a+$b+$c+$d+$e+$f+$g+$h+$i+$j+$k+$l+$m+$n+$o+$p+$q+$r+$s+$t > 0){$total=$a+$b+$c+$d+$e+$f+$g+$h+$i+$j+$k+$l+$m+$n+$o+$p+$q+$r+$s+$t;}else {$total=1;}
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
	$mp=round($m*100/$total,2);
	$np=round($n*100/$total,2);
	$op=round($o*100/$total,2);
	$pp=round($p*100/$total,2);
	$qp=round($q*100/$total,2);
	$rp=round($r*100/$total,2);
	$sp=round($s*100/$total,2);
	$tp=round($t*100/$total,2);
	$this->SetFont('Times', 'BIU', 11);
	$this->SetXY($x-20,$y-108);$this->Cell(0, 5,$titre ,0, 2, 'L');
	$this->RoundedRect($x-20,$y-108, 90, 130, 2, $style = '');
	$this->SetFont('Times', 'B',08);$this->SetFillColor(120,255,120);
	$w=4.5;
	$h=1;
	$this->SetFont('Times', 'B', 9);
	$this->SetXY(111,160-2.5);$this->cell(5,5,'00-',0,0,'C',0);
	$this->SetXY(111,150-2.5);$this->cell(5,5,'10-',0,0,'C',0);
	$this->SetXY(111,140-2.5);$this->cell(5,5,'20-',0,0,'C',0);
	$this->SetXY(111,130-2.5);$this->cell(5,5,'30-',0,0,'C',0);
	$this->SetXY(111,120-2.5);$this->cell(5,5,'40-',0,0,'C',0);
	$this->SetXY(111,110-2.5);$this->cell(5,5,'50-',0,0,'C',0);
	$this->SetXY(111,100-2.5);$this->cell(5,5,'60-',0,0,'C',0);
	$this->SetXY(111,90-2.5);$this->cell(5,5,'70-',0,0,'C',0);
	$this->SetXY(111,80-2.5);$this->cell(5,5,'80-',0,0,'C',0);
	$this->SetXY(111,70-2.5);$this->cell(5,5,'90-',0,0,'C',0);
	$this->SetXY(111,60-2.5);$this->cell(5,5,'100-',0,0,'C',0);
	$this->SetXY($x-20,$y+10);
	$this->cell($w,-$ap*$h,'',1,0,'C',1,0);     
	$this->cell($w,-$bp*$h,'',1,0,'C',1,0);
	$this->cell($w,-$cp*$h,'',1,0,'C',1,0);
	$this->cell($w,-$dp*$h,'',1,0,'C',1,0);
	$this->cell($w,-$ep*$h,'',1,0,'C',1,0);
	$this->cell($w,-$fp*$h,'',1,0,'C',1,0);
	$this->cell($w,-$gp*$h,'',1,0,'C',1,0);
	$this->cell($w,-$hp*$h,'',1,0,'C',1,0);
	$this->cell($w,-$ip*$h,'',1,0,'C',1,0);
	$this->cell($w,-$jp*$h,'',1,0,'C',1,0);
	$this->cell($w,-$kp*$h,'',1,0,'C',1,0);        
	$this->cell($w,-$lp*$h,'',1,0,'C',1,0);
	$this->cell($w,-$mp*$h,'',1,0,'C',1,0);
	$this->cell($w,-$np*$h,'',1,0,'C',1,0);
	$this->cell($w,-$op*$h,'',1,0,'C',1,0);
	$this->cell($w,-$pp*$h,'',1,0,'C',1,0);
	$this->cell($w,-$qp*$h,'',1,0,'C',1,0);
	$this->cell($w,-$rp*$h,'',1,0,'C',1,0);
	$this->cell($w,-$sp*$h,'',1,0,'C',1,0);
	$this->cell($w,-$tp*$h,'',1,0,'C',1,0);
	$this->SetTextColor(255,0,0);
	$this->RotatedText($x-17.5,$y+10-$ap,'-'.$ap.'%',80);
	$this->RotatedText($x-17.5+5,$y+10-$bp,'-'.$bp.'%',80);
	$this->RotatedText($x-17.5+9,$y+10-$cp,'-'.$cp.'%',80);
	$this->RotatedText($x-17.5+14,$y+10-$dp,'-'.$dp.'%',80);
	$this->RotatedText($x-17.5+18.5,$y+10-$ep,'-'.$ep.'%',80);
	$this->RotatedText($x-17.5+23,$y+10-$fp,'-'.$fp.'%',80);
	$this->RotatedText($x-17.5+27,$y+10-$gp,'-'.$gp.'%',80);
	$this->RotatedText($x-17.5+32,$y+10-$hp,'-'.$hp.'%',80);
	$this->RotatedText($x-17.5+36.5,$y+10-$ip,'-'.$ip.'%',80);
	$this->RotatedText($x-17.5+41,$y+10-$jp,'-'.$jp.'%',80);
	$this->RotatedText($x-17.5+45.5,$y+10-$kp,'-'.$kp.'%',80);
	$this->RotatedText($x-17.5+49.5,$y+10-$lp,'-'.$lp.'%',80);
	$this->RotatedText($x-17.5+54,$y+10-$mp,'-'.$mp.'%',80);
	$this->RotatedText($x-17.5+59,$y+10-$np,'-'.$np.'%',80);
	$this->RotatedText($x-17.5+63,$y+10-$op,'-'.$op.'%',80);
	$this->RotatedText($x-17.5+68,$y+10-$pp,'-'.$pp.'%',80);
	$this->RotatedText($x-17.5+72.5,$y+10-$qp,'-'.$qp.'%',80);
	$this->RotatedText($x-17.5+77,$y+10-$rp,'-'.$rp.'%',80);
	$this->RotatedText($x-17.5+81.5,$y+10-$sp,'-'.$sp.'%',80);
	$this->RotatedText($x-17.5+85.5,$y+10-$tp,'-'.$tp.'%',80);
	$this->SetTextColor(0,0,0);
	$this->SetXY($x-20,$y+12);    
	$this->SetFont('Times', 'B', 9);
	$this->cell($w,5,'05',1,0,'C',0,0);
	$this->cell($w,5,'10',1,0,'C',0,0);
	$this->cell($w,5,'15',1,0,'C',0,0);
	$this->cell($w,5,'20',1,0,'C',0,0);
	$this->cell($w,5,'25',1,0,'C',0,0);
	$this->cell($w,5,'30',1,0,'C',0,0);
	$this->cell($w,5,'35',1,0,'C',0,0);
	$this->cell($w,5,'40',1,0,'C',0,0);
	$this->cell($w,5,'45',1,0,'C',0,0);
	$this->cell($w,5,'50',1,0,'C',0,0);
	$this->cell($w,5,'55',1,0,'C',0,0);
	$this->cell($w,5,'60',1,0,'C',0,0);
	$this->cell($w,5,'65',1,0,'C',0,0);
	$this->cell($w,5,'70',1,0,'C',0,0);
	$this->cell($w,5,'75',1,0,'C',0,0);
	$this->cell($w,5,'80',1,0,'C',0,0);
	$this->cell($w,5,'85',1,0,'C',0,0);
	$this->cell($w,5,'90',1,0,'C',0,0);
	$this->cell($w,5,'95',1,0,'C',0,0);
	$this->cell($w,5,'99',1,0,'C',0,0);
	$this->SetFillColor(230);//fond gris il faut ajouter au cell un autre parametre pour qui accepte la coloration
	$this->SetTextColor(0,0,0);//text noire
	$this->SetFont('Times', 'B', 11);
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
	//$this->Image('../public/IMAGES/photos/pc.gif',250,50,30,30,0);
	$this->SetXY(220,40);$this->cell(65,5,'WILAYA DE DJELFA',1,0,'C',1,0);
	$this->RoundedRect($x-15,35,160,200, 2, $style = '');
	$this->RoundedRect($x-15,35,200,200, 2, $style = '');
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
	$this->color(0);    $this->SetXY($x-10,150);$this->cell(30,5,$data['titre'],0,0,'C',0,0);
	$this->color(0);    $this->SetXY($x-5,$this->GetY()+10);$this->cell(5,5,'',1,0,'C',1,0);$this->cell(15,5,$data['A'],0,0,'L',0,0);
	$this->color(1);    $this->SetXY($x-5,$this->GetY()+10);$this->cell(5,5,'',1,0,'C',1,0);$this->cell(15,5,$data['B'],0,0,'L',0,0);
	$this->color(11);   $this->SetXY($x-5,$this->GetY()+10);$this->cell(5,5,'',1,0,'C',1,0);$this->cell(15,5,$data['C'],0,0,'L',0,0);
	$this->color(101);  $this->SetXY($x-5,$this->GetY()+10);$this->cell(5,5,'',1,0,'C',1,0);$this->cell(15,5,$data['D'],0,0,'L',0,0);
	$this->color(1001); $this->SetXY($x-5,$this->GetY()+10);$this->cell(5,5,'',1,0,'C',1,0);$this->cell(15,5,$data['E'],0,0,'L',0,0);
	$this->color(0);    $this->SetXY($x-10,$this->GetY()+15);$this->cell(40,5,'00km_____45km_____90km',0,0,'L',0,0);
	$this->color(0);    $this->SetXY($x-10,$this->GetY()+5);$this->cell(40,5,'Source : Dr TIBA Redha  EPH Ain-oussera ',0,0,'L',0,0);
	$this->color(0);
	$this->SetFont('Times', 'BIU', 8);
	$this->SetDrawColor(255,0,0);
	$this->SetXY(150,42);$this->cell(65,5,'La Wilaya De Djelfa',0,0,'C',0,0);
	$this->SetFont('Times', 'B', 8);
	$yy=170;
	$this->SetXY($yy,$this->GetY()+5);$this->cell(55,5,'1-Ain Chouhada',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'2-Ain el Ibel',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'3-Ain Fekka',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'4-Ain Maabed',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'5-Ain Oussera',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'6-Amourah',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'7-Benhar',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'8-Beni Yacoub',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'9-Birine',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'10-Bouira Lahdab',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'11-Charef',0,0,'L',0,0);//$this->SetXY(35,$this->GetY()+5);$this->cell(65,5,'11',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'12-Dar Chioukh',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'13-Deldoul',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'14-Djelfa',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'15-Douis',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'16-El Guedid',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'17-El Idrissia',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'18-El Khemis',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'19-Faidh el Botma',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'20-Guernini',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'21-Guettara',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'22-Had-Sahary',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'23-Hassi Bahbah',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'24-Hassi el Euch',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'25-Hassi Fedoul',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'26-M Liliha',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'27-Messad',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'28-Mouadjebar',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'29-Oum Laadham',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'30-Sed Rahal',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'31-Selmana',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'32-Sidi Baizid',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'33-Sidi Ladjel',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'34-Tadmit',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'35-Zaafrane',0,0,'L',0,0);
	$this->SetXY($yy,$this->GetY()+5);$this->cell(65,5,'36-Zaccar',0,0,'L',0,0);												
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
	$this->SetXY(36,100);$this->cell(65,5,'*11',0,0,'L',0,0);
	$this->SetXY(66,86);$this->cell(65,5,'*12',0,0,'L',0,0);
	$this->SetXY(65,132);$this->cell(65,5,'*13',0,0,'L',0,0);
	$this->SetXY(56,97);$this->cell(65,5,'*14',0,0,'L',0,0);
	$this->SetXY(35,119);$this->cell(65,5,'*15',0,0,'L',0,0);
	$this->SetXY(28,95);$this->cell(65,5,'*16',0,0,'L',0,0);
	$this->SetXY(27,110);$this->cell(65,5,'*17',0,0,'L',0,0);
	$this->SetXY(33,58);$this->cell(65,5,'*18',0,0,'L',0,0);
	$this->SetXY(80,105);$this->cell(65,5,'*19',0,0,'L',0,0);
	$this->SetXY(38,70);$this->cell(65,5,'*20',0,0,'L',0,0);
	$this->SetXY(110,175);$this->cell(65,5,'*21',0,0,'L',0,0);
	$this->SetXY(62,61);$this->cell(65,5,'*22',0,0,'L',0,0);
	$this->SetXY(45,77);$this->cell(65,5,'*23',0,0,'L',0,0);
	$this->SetXY(58,73,$this->GetY()+5);$this->cell(65,5,'*24',0,0,'L',0,0);
	$this->SetXY(14,55);$this->cell(65,5,'*25',0,0,'L',0,0);
	$this->SetXY(73,94);$this->cell(65,5,'*26',0,0,'L',0,0);
	$this->SetXY(68,122);$this->cell(65,5,'*27',0,0,'L',0,0);
	$this->SetXY(69,110);$this->cell(65,5,'*28',0,0,'L',0,0);
	$this->SetXY(100,148);$this->cell(65,5,'*29',0,0,'L',0,0);
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
	
	function decescim($DATEJOUR1,$DATEJOUR2,$WILAYAR,$STRUCTURED,$CODECIM) 
	{
	$this->mysqlconnect();
	$sql = " select * from deceshosp where DINS BETWEEN '$DATEJOUR1' AND '$DATEJOUR2' and WILAYAR=$WILAYAR and STRUCTURED $STRUCTURED and CODECIM0=$CODECIM  ";
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$OP=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $OP;
	}
	function datacimchapitre($datejour1,$datejour2,$STRUCTURED,$CODECIM) 
	{
	$data = array(
	"titre"=> 'Nombre De Deces',
	"A"    => '00-00',
	"B"    => '01-10',
	"C"    => '09-100',
	"D"    => '99-1000',
	"E"    => '999-10000',
	"1"    => $this->decescim($datejour1,$datejour2,1000,$STRUCTURED,$CODECIM),
	"2"    => $this->decescim($datejour1,$datejour2,2000,$STRUCTURED,$CODECIM),
	"3"    => $this->decescim($datejour1,$datejour2,3000,$STRUCTURED,$CODECIM),
	"4"    => $this->decescim($datejour1,$datejour2,4000,$STRUCTURED,$CODECIM),
	"5"    => $this->decescim($datejour1,$datejour2,5000,$STRUCTURED,$CODECIM),
	"6"    => $this->decescim($datejour1,$datejour2,6000,$STRUCTURED,$CODECIM),
	"7"    => $this->decescim($datejour1,$datejour2,7000,$STRUCTURED,$CODECIM),
	"8"    => $this->decescim($datejour1,$datejour2,8000,$STRUCTURED,$CODECIM),
	"9"    => $this->decescim($datejour1,$datejour2,9000,$STRUCTURED,$CODECIM),
	"10"    => $this->decescim($datejour1,$datejour2,10000,$STRUCTURED,$CODECIM),
	"11"    => $this->decescim($datejour1,$datejour2,11000,$STRUCTURED,$CODECIM),
	"12"    => $this->decescim($datejour1,$datejour2,12000,$STRUCTURED,$CODECIM),
	"13"    => $this->decescim($datejour1,$datejour2,13000,$STRUCTURED,$CODECIM),
	"14"    => $this->decescim($datejour1,$datejour2,14000,$STRUCTURED,$CODECIM),
	"15"    => $this->decescim($datejour1,$datejour2,15000,$STRUCTURED,$CODECIM),
	"16"    => $this->decescim($datejour1,$datejour2,16000,$STRUCTURED,$CODECIM),
	"17"    => $this->decescim($datejour1,$datejour2,17000,$STRUCTURED,$CODECIM),
	"18"    => $this->decescim($datejour1,$datejour2,18000,$STRUCTURED,$CODECIM),
	"19"    => $this->decescim($datejour1,$datejour2,19000,$STRUCTURED,$CODECIM),
	"20"    => $this->decescim($datejour1,$datejour2,20000,$STRUCTURED,$CODECIM),
	"21"    => $this->decescim($datejour1,$datejour2,21000,$STRUCTURED,$CODECIM),
	"22"    => $this->decescim($datejour1,$datejour2,22000,$STRUCTURED,$CODECIM),
	"23"    => $this->decescim($datejour1,$datejour2,23000,$STRUCTURED,$CODECIM),
	"24"    => $this->decescim($datejour1,$datejour2,24000,$STRUCTURED,$CODECIM),
	"25"    => $this->decescim($datejour1,$datejour2,25000,$STRUCTURED,$CODECIM),
	"26"    => $this->decescim($datejour1,$datejour2,26000,$STRUCTURED,$CODECIM),
	"27"    => $this->decescim($datejour1,$datejour2,27000,$STRUCTURED,$CODECIM),
	"28"    => $this->decescim($datejour1,$datejour2,28000,$STRUCTURED,$CODECIM),
	"29"    => $this->decescim($datejour1,$datejour2,29000,$STRUCTURED,$CODECIM),
	"30"    => $this->decescim($datejour1,$datejour2,30000,$STRUCTURED,$CODECIM),
	"31"    => $this->decescim($datejour1,$datejour2,31000,$STRUCTURED,$CODECIM),
	"32"    => $this->decescim($datejour1,$datejour2,32000,$STRUCTURED,$CODECIM),
	"33"    => $this->decescim($datejour1,$datejour2,33000,$STRUCTURED,$CODECIM),
	"34"    => $this->decescim($datejour1,$datejour2,34000,$STRUCTURED,$CODECIM),
	"35"    => $this->decescim($datejour1,$datejour2,35000,$STRUCTURED,$CODECIM),
	"36"    => $this->decescim($datejour1,$datejour2,36000,$STRUCTURED,$CODECIM),
	"37"    => $this->decescim($datejour1,$datejour2,37000,$STRUCTURED,$CODECIM),
	"38"    => $this->decescim($datejour1,$datejour2,38000,$STRUCTURED,$CODECIM),
	"39"    => $this->decescim($datejour1,$datejour2,39000,$STRUCTURED,$CODECIM),
	"40"    => $this->decescim($datejour1,$datejour2,40000,$STRUCTURED,$CODECIM),
	"41"    => $this->decescim($datejour1,$datejour2,41000,$STRUCTURED,$CODECIM),
	"42"    => $this->decescim($datejour1,$datejour2,42000,$STRUCTURED,$CODECIM),
	"43"    => $this->decescim($datejour1,$datejour2,43000,$STRUCTURED,$CODECIM),
	"44"    => $this->decescim($datejour1,$datejour2,44000,$STRUCTURED,$CODECIM),
	"45"    => $this->decescim($datejour1,$datejour2,45000,$STRUCTURED,$CODECIM),
	"46"    => $this->decescim($datejour1,$datejour2,46000,$STRUCTURED,$CODECIM),
	"47"    => $this->decescim($datejour1,$datejour2,47000,$STRUCTURED,$CODECIM),
	"48"    => $this->decescim($datejour1,$datejour2,48000,$STRUCTURED,$CODECIM)
	);		
	return $data;
	}
	function decescimcat($DATEJOUR1,$DATEJOUR2,$WILAYAR,$STRUCTURED,$CODECIM) 
	{
	$this->mysqlconnect();
	$sql = " select * from deceshosp where DINS BETWEEN '$DATEJOUR1' AND '$DATEJOUR2' and WILAYAR=$WILAYAR and STRUCTURED $STRUCTURED and CODECIM=$CODECIM  ";
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$OP=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $OP;
	}
	function datacimcat($datejour1,$datejour2,$STRUCTURED,$CODECIM) 
	{
	$data = array(
	"titre"=> 'Nombre De Deces',
	"A"    => '00-00',
	"B"    => '01-10',
	"C"    => '09-100',
	"D"    => '99-1000',
	"E"    => '999-10000',
	"1"    => $this->decescimcat($datejour1,$datejour2,1000,$STRUCTURED,$CODECIM),
	"2"    => $this->decescimcat($datejour1,$datejour2,2000,$STRUCTURED,$CODECIM),
	"3"    => $this->decescimcat($datejour1,$datejour2,3000,$STRUCTURED,$CODECIM),
	"4"    => $this->decescimcat($datejour1,$datejour2,4000,$STRUCTURED,$CODECIM),
	"5"    => $this->decescimcat($datejour1,$datejour2,5000,$STRUCTURED,$CODECIM),
	"6"    => $this->decescimcat($datejour1,$datejour2,6000,$STRUCTURED,$CODECIM),
	"7"    => $this->decescimcat($datejour1,$datejour2,7000,$STRUCTURED,$CODECIM),
	"8"    => $this->decescimcat($datejour1,$datejour2,8000,$STRUCTURED,$CODECIM),
	"9"    => $this->decescimcat($datejour1,$datejour2,9000,$STRUCTURED,$CODECIM),
	"10"    => $this->decescimcat($datejour1,$datejour2,10000,$STRUCTURED,$CODECIM),
	"11"    => $this->decescimcat($datejour1,$datejour2,11000,$STRUCTURED,$CODECIM),
	"12"    => $this->decescimcat($datejour1,$datejour2,12000,$STRUCTURED,$CODECIM),
	"13"    => $this->decescimcat($datejour1,$datejour2,13000,$STRUCTURED,$CODECIM),
	"14"    => $this->decescimcat($datejour1,$datejour2,14000,$STRUCTURED,$CODECIM),
	"15"    => $this->decescimcat($datejour1,$datejour2,15000,$STRUCTURED,$CODECIM),
	"16"    => $this->decescimcat($datejour1,$datejour2,16000,$STRUCTURED,$CODECIM),
	"17"    => $this->decescimcat($datejour1,$datejour2,17000,$STRUCTURED,$CODECIM),
	"18"    => $this->decescimcat($datejour1,$datejour2,18000,$STRUCTURED,$CODECIM),
	"19"    => $this->decescimcat($datejour1,$datejour2,19000,$STRUCTURED,$CODECIM),
	"20"    => $this->decescimcat($datejour1,$datejour2,20000,$STRUCTURED,$CODECIM),
	"21"    => $this->decescimcat($datejour1,$datejour2,21000,$STRUCTURED,$CODECIM),
	"22"    => $this->decescimcat($datejour1,$datejour2,22000,$STRUCTURED,$CODECIM),
	"23"    => $this->decescimcat($datejour1,$datejour2,23000,$STRUCTURED,$CODECIM),
	"24"    => $this->decescimcat($datejour1,$datejour2,24000,$STRUCTURED,$CODECIM),
	"25"    => $this->decescimcat($datejour1,$datejour2,25000,$STRUCTURED,$CODECIM),
	"26"    => $this->decescimcat($datejour1,$datejour2,26000,$STRUCTURED,$CODECIM),
	"27"    => $this->decescimcat($datejour1,$datejour2,27000,$STRUCTURED,$CODECIM),
	"28"    => $this->decescimcat($datejour1,$datejour2,28000,$STRUCTURED,$CODECIM),
	"29"    => $this->decescimcat($datejour1,$datejour2,29000,$STRUCTURED,$CODECIM),
	"30"    => $this->decescimcat($datejour1,$datejour2,30000,$STRUCTURED,$CODECIM),
	"31"    => $this->decescimcat($datejour1,$datejour2,31000,$STRUCTURED,$CODECIM),
	"32"    => $this->decescimcat($datejour1,$datejour2,32000,$STRUCTURED,$CODECIM),
	"33"    => $this->decescimcat($datejour1,$datejour2,33000,$STRUCTURED,$CODECIM),
	"34"    => $this->decescimcat($datejour1,$datejour2,34000,$STRUCTURED,$CODECIM),
	"35"    => $this->decescimcat($datejour1,$datejour2,35000,$STRUCTURED,$CODECIM),
	"36"    => $this->decescimcat($datejour1,$datejour2,36000,$STRUCTURED,$CODECIM),
	"37"    => $this->decescimcat($datejour1,$datejour2,37000,$STRUCTURED,$CODECIM),
	"38"    => $this->decescimcat($datejour1,$datejour2,38000,$STRUCTURED,$CODECIM),
	"39"    => $this->decescimcat($datejour1,$datejour2,39000,$STRUCTURED,$CODECIM),
	"40"    => $this->decescimcat($datejour1,$datejour2,40000,$STRUCTURED,$CODECIM),
	"41"    => $this->decescimcat($datejour1,$datejour2,41000,$STRUCTURED,$CODECIM),
	"42"    => $this->decescimcat($datejour1,$datejour2,42000,$STRUCTURED,$CODECIM),
	"43"    => $this->decescimcat($datejour1,$datejour2,43000,$STRUCTURED,$CODECIM),
	"44"    => $this->decescimcat($datejour1,$datejour2,44000,$STRUCTURED,$CODECIM),
	"45"    => $this->decescimcat($datejour1,$datejour2,45000,$STRUCTURED,$CODECIM),
	"46"    => $this->decescimcat($datejour1,$datejour2,46000,$STRUCTURED,$CODECIM),
	"47"    => $this->decescimcat($datejour1,$datejour2,47000,$STRUCTURED,$CODECIM),
	"48"    => $this->decescimcat($datejour1,$datejour2,48000,$STRUCTURED,$CODECIM)
	);		
	return $data;
	}
	function deceswil($DATEJOUR1,$DATEJOUR2,$WILAYAR,$STRUCTURED) 
	{
	$this->mysqlconnect();
	$sql = " select * from deceshosp where DINS BETWEEN '$DATEJOUR1' AND '$DATEJOUR2' and WILAYAR=$WILAYAR and STRUCTURED $STRUCTURED  ";
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$OP=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $OP;
	}
	function datasigwil($datejour1,$datejour2,$STRUCTURED) 
	{
	$data = array(
	"titre"=> 'Nombre De Deces',
	"A"    => '00-00',
	"B"    => '01-10',
	"C"    => '09-100',
	"D"    => '99-1000',
	"E"    => '999-10000',
	"1"    => $this->deceswil($datejour1,$datejour2,1000,$STRUCTURED),
	"2"    => $this->deceswil($datejour1,$datejour2,2000,$STRUCTURED),
	"3"    => $this->deceswil($datejour1,$datejour2,3000,$STRUCTURED),
	"4"    => $this->deceswil($datejour1,$datejour2,4000,$STRUCTURED),
	"5"    => $this->deceswil($datejour1,$datejour2,5000,$STRUCTURED),
	"6"    => $this->deceswil($datejour1,$datejour2,6000,$STRUCTURED),
	"7"    => $this->deceswil($datejour1,$datejour2,7000,$STRUCTURED),
	"8"    => $this->deceswil($datejour1,$datejour2,8000,$STRUCTURED),
	"9"    => $this->deceswil($datejour1,$datejour2,9000,$STRUCTURED),
	"10"    => $this->deceswil($datejour1,$datejour2,10000,$STRUCTURED),
	"11"    => $this->deceswil($datejour1,$datejour2,11000,$STRUCTURED),
	"12"    => $this->deceswil($datejour1,$datejour2,12000,$STRUCTURED),
	"13"    => $this->deceswil($datejour1,$datejour2,13000,$STRUCTURED),
	"14"    => $this->deceswil($datejour1,$datejour2,14000,$STRUCTURED),
	"15"    => $this->deceswil($datejour1,$datejour2,15000,$STRUCTURED),
	"16"    => $this->deceswil($datejour1,$datejour2,16000,$STRUCTURED),
	"17"    => $this->deceswil($datejour1,$datejour2,17000,$STRUCTURED),
	"18"    => $this->deceswil($datejour1,$datejour2,18000,$STRUCTURED),
	"19"    => $this->deceswil($datejour1,$datejour2,19000,$STRUCTURED),
	"20"    => $this->deceswil($datejour1,$datejour2,20000,$STRUCTURED),
	"21"    => $this->deceswil($datejour1,$datejour2,21000,$STRUCTURED),
	"22"    => $this->deceswil($datejour1,$datejour2,22000,$STRUCTURED),
	"23"    => $this->deceswil($datejour1,$datejour2,23000,$STRUCTURED),
	"24"    => $this->deceswil($datejour1,$datejour2,24000,$STRUCTURED),
	"25"    => $this->deceswil($datejour1,$datejour2,25000,$STRUCTURED),
	"26"    => $this->deceswil($datejour1,$datejour2,26000,$STRUCTURED),
	"27"    => $this->deceswil($datejour1,$datejour2,27000,$STRUCTURED),
	"28"    => $this->deceswil($datejour1,$datejour2,28000,$STRUCTURED),
	"29"    => $this->deceswil($datejour1,$datejour2,29000,$STRUCTURED),
	"30"    => $this->deceswil($datejour1,$datejour2,30000,$STRUCTURED),
	"31"    => $this->deceswil($datejour1,$datejour2,31000,$STRUCTURED),
	"32"    => $this->deceswil($datejour1,$datejour2,32000,$STRUCTURED),
	"33"    => $this->deceswil($datejour1,$datejour2,33000,$STRUCTURED),
	"34"    => $this->deceswil($datejour1,$datejour2,34000,$STRUCTURED),
	"35"    => $this->deceswil($datejour1,$datejour2,35000,$STRUCTURED),
	"36"    => $this->deceswil($datejour1,$datejour2,36000,$STRUCTURED),
	"37"    => $this->deceswil($datejour1,$datejour2,37000,$STRUCTURED),
	"38"    => $this->deceswil($datejour1,$datejour2,38000,$STRUCTURED),
	"39"    => $this->deceswil($datejour1,$datejour2,39000,$STRUCTURED),
	"40"    => $this->deceswil($datejour1,$datejour2,40000,$STRUCTURED),
	"41"    => $this->deceswil($datejour1,$datejour2,41000,$STRUCTURED),
	"42"    => $this->deceswil($datejour1,$datejour2,42000,$STRUCTURED),
	"43"    => $this->deceswil($datejour1,$datejour2,43000,$STRUCTURED),
	"44"    => $this->deceswil($datejour1,$datejour2,44000,$STRUCTURED),
	"45"    => $this->deceswil($datejour1,$datejour2,45000,$STRUCTURED),
	"46"    => $this->deceswil($datejour1,$datejour2,46000,$STRUCTURED),
	"47"    => $this->deceswil($datejour1,$datejour2,47000,$STRUCTURED),
	"48"    => $this->deceswil($datejour1,$datejour2,48000,$STRUCTURED)
	);		
	return $data;
	}
	
	
	
	function Algerie($data,$x,$y,$z,$cd) 
    {
	//$this->Image('../public/IMAGES/photos/pc.gif',250,50,30,30,0);
	$this->SetXY(220,40);$this->cell(65,5,'WILAYA DE DJELFA',1,0,'C',1,0);
	$this->RoundedRect($x-15,35,160,200, 2, $style = '');
	$this->RoundedRect($x-15,35,200,200, 2, $style = '');
	
	if ($cd=='wilaya')
	{
	       //tindouf
			$this->color($data['37']);$this->Polygon(array((98+$x)/$z,(244+$y)/$z,(100+$x)/$z,(248+$y)/$z,(106+$x)/$z,(250+$y)/$z,(113+$x)/$z,(250+$y)/$z,(120+$x)/$z,(254+$y)/$z,(125+$x)/$z,(271+$y)/$z,(133+$x)/$z,(290+$y)/$z,(136+$x)/$z,(295+$y)/$z,(136+$x)/$z,(304+$y)/$z,(141+$x)/$z,(310+$y)/$z,(147+$x)/$z,(314+$y)/$z,(159+$x)/$z,(318+$y)/$z,(159+$x)/$z,(327+$y)/$z,(146+$x)/$z,(341+$y)/$z,(138+$x)/$z,(335+$y)/$z,(133+$x)/$z,(343+$y)/$z,(127+$x)/$z,(354+$y)/$z,(110+$x)/$z,(354+$y)/$z,(101+$x)/$z,(361+$y)/$z,(87+$x)/$z,(367+$y)/$z,(8+$x)/$z,(308+$y)/$z,(10+$x)/$z,(264+$y)/$z,(26+$x)/$z,(256+$y)/$z,(41+$x)/$z,(248+$y)/$z,(47+$x)/$z,(248+$y)/$z,(52+$x)/$z,(243+$y)/$z,(64+$x)/$z,(246+$y)/$z,(72+$x)/$z,(243+$y)/$z,(89+$x)/$z,(243+$y)/$z,(95+$x)/$z,(248+$y)/$z,(98+$x)/$z,(244+$y)/$z),'FD');
			//adrar
			$this->color($data['1']);$this->Polygon(array((159+$x)/$z,(318+$y)/$z,(163+$x)/$z,(302+$y)/$z,(168+$x)/$z,(299+$y)/$z,(174+$x)/$z,(289+$y)/$z,(183+$x)/$z,(282+$y)/$z,(190+$x)/$z,(272+$y)/$z,(200+$x)/$z,(269+$y)/$z,(219+$x)/$z,(267+$y)/$z,(222+$x)/$z,(263+$y)/$z,(226+$x)/$z,(261+$y)/$z,(226+$x)/$z,(244+$y)/$z,(233+$x)/$z,(240+$y)/$z,(238+$x)/$z,(235+$y)/$z,(242+$x)/$z,(234+$y)/$z,(242+$x)/$z,(229+$y)/$z,(247+$x)/$z,(224+$y)/$z,(255+$x)/$z,(214+$y)/$z,(261+$x)/$z,(210+$y)/$z,(265+$x)/$z,(209+$y)/$z,(269+$x)/$z,(204+$y)/$z,(274+$x)/$z,(203+$y)/$z,(277+$x)/$z,(198+$y)/$z,(282+$x)/$z,(197+$y)/$z,(286+$x)/$z,(194+$y)/$z,(292+$x)/$z,(191+$y)/$z,(295+$x)/$z,(187+$y)/$z,(298+$x)/$z,(187+$y)/$z,(297+$x)/$z,(224+$y)/$z,(294+$x)/$z,(236+$y)/$z,(297+$x)/$z,(246+$y)/$z,(300+$x)/$z,(268+$y)/$z,(297+$x)/$z,(285+$y)/$z,(292+$x)/$z,(293+$y)/$z,(292+$x)/$z,(306+$y)/$z,(288+$x)/$z,(311+$y)/$z,(288+$x)/$z,(317+$y)/$z,(291+$x)/$z,(321+$y)/$z,(288+$x)/$z,(328+$y)/$z,(287+$x)/$z,(338+$y)/$z,(278+$x)/$z,(339+$y)/$z,(274+$x)/$z,(345+$y)/$z,(269+$x)/$z,(350+$y)/$z,(269+$x)/$z,(357+$y)/$z,(279+$x)/$z,(371+$y)/$z,(282+$x)/$z,(485+$y)/$z,(291+$x)/$z,(486+$y)/$z,(293+$x)/$z,(495+$y)/$z,(297+$x)/$z,(499+$y)/$z,(304+$x)/$z,(512+$y)/$z,(306+$x)/$z,(520+$y)/$z,(310+$x)/$z,(532+$y)/$z,(313+$x)/$z,(539+$y)/$z,(302+$x)/$z,(534+$y)/$z,(294+$x)/$z,(534+$y)/$z,(287+$x)/$z,(525+$y)/$z,(275+$x)/$z,(518+$y)/$z,(273+$x)/$z,(506+$y)/$z,(87+$x)/$z,(367+$y)/$z,(101+$x)/$z,(361+$y)/$z,(110+$x)/$z,(354+$y)/$z,(127+$x)/$z,(354+$y)/$z,(133+$x)/$z,(343+$y)/$z,(138+$x)/$z,(335+$y)/$z,(146+$x)/$z,(341+$y)/$z,(159+$x)/$z,(327+$y)/$z,(159+$x)/$z,(318+$y)/$z),'FD');
			//tamanraset
			$this->color($data['11']);$this->Polygon(array((300+$x)/$z,(268+$y)/$z,(324+$x)/$z,(265+$y)/$z,(330+$x)/$z,(269+$y)/$z,(333+$x)/$z,(275+$y)/$z,(339+$x)/$z,(276+$y)/$z,(344+$x)/$z,(282+$y)/$z,(349+$x)/$z,(277+$y)/$z,(355+$x)/$z,(275+$y)/$z,(357+$x)/$z,(271+$y)/$z,(363+$x)/$z,(269+$y)/$z,(366+$x)/$z,(265+$y)/$z,(368+$x)/$z,(262+$y)/$z,(375+$x)/$z,(258+$y)/$z,(379+$x)/$z,(257+$y)/$z,(382+$x)/$z,(253+$y)/$z,(386+$x)/$z,(251+$y)/$z,(390+$x)/$z,(248+$y)/$z,(391+$x)/$z,(244+$y)/$z,(396+$x)/$z,(243+$y)/$z,(397+$x)/$z,(284+$y)/$z,(396+$x)/$z,(294+$y)/$z,(399+$x)/$z,(310+$y)/$z,(399+$x)/$z,(325+$y)/$z,(404+$x)/$z,(326+$y)/$z,(406+$x)/$z,(331+$y)/$z,(411+$x)/$z,(333+$y)/$z,(415+$x)/$z,(337+$y)/$z,(411+$x)/$z,(345+$y)/$z,(417+$x)/$z,(353+$y)/$z,(423+$x)/$z,(367+$y)/$z,(423+$x)/$z,(372+$y)/$z,(428+$x)/$z,(374+$y)/$z,(433+$x)/$z,(385+$y)/$z,(440+$x)/$z,(393+$y)/$z,(444+$x)/$z,(393+$y)/$z,(449+$x)/$z,(399+$y)/$z,(452+$x)/$z,(401+$y)/$z,(453+$x)/$z,(420+$y)/$z,(456+$x)/$z,(429+$y)/$z,(459+$x)/$z,(433+$y)/$z,(457+$x)/$z,(438+$y)/$z,(465+$x)/$z,(443+$y)/$z,(486+$x)/$z,(439+$y)/$z,(490+$x)/$z,(434+$y)/$z,(497+$x)/$z,(429+$y)/$z,(501+$x)/$z,(426+$y)/$z,(517+$x)/$z,(426+$y)/$z,(532+$x)/$z,(432+$y)/$z,(531+$x)/$z,(455+$y)/$z,(410+$x)/$z,(557+$y)/$z,(338+$x)/$z,(573+$y)/$z,(331+$x)/$z,(568+$y)/$z,(334+$x)/$z,(562+$y)/$z,(334+$x)/$z,(547+$y)/$z,(313+$x)/$z,(539+$y)/$z,(310+$x)/$z,(532+$y)/$z,(306+$x)/$z,(520+$y)/$z,(304+$x)/$z,(512+$y)/$z,(297+$x)/$z,(499+$y)/$z,(293+$x)/$z,(495+$y)/$z,(291+$x)/$z,(486+$y)/$z,(282+$x)/$z,(485+$y)/$z,(279+$x)/$z,(371+$y)/$z,(269+$x)/$z,(357+$y)/$z,(269+$x)/$z,(350+$y)/$z,(274+$x)/$z,(345+$y)/$z,(278+$x)/$z,(339+$y)/$z,(287+$x)/$z,(338+$y)/$z,(288+$x)/$z,(328+$y)/$z,(291+$x)/$z,(321+$y)/$z,(288+$x)/$z,(317+$y)/$z,(288+$x)/$z,(311+$y)/$z,(292+$x)/$z,(306+$y)/$z,(292+$x)/$z,(293+$y)/$z,(297+$x)/$z,(285+$y)/$z,(300+$x)/$z,(268+$y)/$z),'FD');
			//illizi
			$this->color($data['33']);$this->Polygon(array((396+$x)/$z,(243+$y)/$z,(400+$x)/$z,(239+$y)/$z,(408+$x)/$z,(235+$y)/$z,(415+$x)/$z,(232+$y)/$z,(419+$x)/$z,(233+$y)/$z,(423+$x)/$z,(231+$y)/$z,(452+$x)/$z,(223+$y)/$z,(483+$x)/$z,(215+$y)/$z,(494+$x)/$z,(212+$y)/$z,(495+$x)/$z,(218+$y)/$z,(496+$x)/$z,(224+$y)/$z,(493+$x)/$z,(227+$y)/$z,(497+$x)/$z,(234+$y)/$z,(507+$x)/$z,(254+$y)/$z,(509+$x)/$z,(282+$y)/$z,(512+$x)/$z,(293+$y)/$z,(511+$x)/$z,(300+$y)/$z,(510+$x)/$z,(307+$y)/$z,(510+$x)/$z,(314+$y)/$z,(511+$x)/$z,(323+$y)/$z,(514+$x)/$z,(326+$y)/$z,(514+$x)/$z,(331+$y)/$z,(508+$x)/$z,(338+$y)/$z,(503+$x)/$z,(340+$y)/$z,(502+$x)/$z,(346+$y)/$z,(507+$x)/$z,(353+$y)/$z,(512+$x)/$z,(360+$y)/$z,(513+$x)/$z,(363+$y)/$z,(519+$x)/$z,(368+$y)/$z,(521+$x)/$z,(382+$y)/$z,(528+$x)/$z,(394+$y)/$z,(536+$x)/$z,(395+$y)/$z,(542+$x)/$z,(395+$y)/$z,(548+$x)/$z,(394+$y)/$z,(554+$x)/$z,(399+$y)/$z,(561+$x)/$z,(402+$y)/$z,(566+$x)/$z,(401+$y)/$z,(571+$x)/$z,(409+$y)/$z,(577+$x)/$z,(421+$y)/$z,(571+$x)/$z,(427+$y)/$z,(565+$x)/$z,(433+$y)/$z,(531+$x)/$z,(455+$y)/$z,(532+$x)/$z,(432+$y)/$z,(517+$x)/$z,(426+$y)/$z,(501+$x)/$z,(426+$y)/$z,(497+$x)/$z,(429+$y)/$z,(490+$x)/$z,(434+$y)/$z,(486+$x)/$z,(439+$y)/$z,(465+$x)/$z,(443+$y)/$z,(457+$x)/$z,(438+$y)/$z,(459+$x)/$z,(433+$y)/$z,(456+$x)/$z,(429+$y)/$z,(453+$x)/$z,(420+$y)/$z,(452+$x)/$z,(401+$y)/$z,(449+$x)/$z,(399+$y)/$z,(444+$x)/$z,(393+$y)/$z,(440+$x)/$z,(393+$y)/$z,(433+$x)/$z,(385+$y)/$z,(428+$x)/$z,(374+$y)/$z,(423+$x)/$z,(372+$y)/$z,(423+$x)/$z,(367+$y)/$z,(417+$x)/$z,(353+$y)/$z,(411+$x)/$z,(345+$y)/$z,(415+$x)/$z,(337+$y)/$z,(411+$x)/$z,(333+$y)/$z,(406+$x)/$z,(331+$y)/$z,(404+$x)/$z,(326+$y)/$z,(399+$x)/$z,(325+$y)/$z,(399+$x)/$z,(310+$y)/$z,(396+$x)/$z,(294+$y)/$z,(397+$x)/$z,(284+$y)/$z,(396+$x)/$z,(243+$y)/$z),'FD');
			//ghardaya
			$this->color($data['47']);$this->Polygon(array((298+$x)/$z,(187+$y)/$z,(304+$x)/$z,(179+$y)/$z,(303+$x)/$z,(170+$y)/$z,(306+$x)/$z,(169+$y)/$z,(306+$x)/$z,(164+$y)/$z,(303+$x)/$z,(162+$y)/$z,(303+$x)/$z,(151+$y)/$z,(315+$x)/$z,(150+$y)/$z,(323+$x)/$z,(149+$y)/$z,(331+$x)/$z,(150+$y)/$z,(332+$x)/$z,(147+$y)/$z,(328+$x)/$z,(145+$y)/$z,(338+$x)/$z,(144+$y)/$z,(341+$x)/$z,(142+$y)/$z,(343+$x)/$z,(144+$y)/$z,(347+$x)/$z,(144+$y)/$z,(360+$x)/$z,(143+$y)/$z,(374+$x)/$z,(146+$y)/$z,(374+$x)/$z,(153+$y)/$z,(369+$x)/$z,(160+$y)/$z,(360+$x)/$z,(170+$y)/$z,(360+$x)/$z,(188+$y)/$z,(352+$x)/$z,(213+$y)/$z,(344+$x)/$z,(240+$y)/$z,(336+$x)/$z,(255+$y)/$z,(324+$x)/$z,(265+$y)/$z,(300+$x)/$z,(268+$y)/$z,(297+$x)/$z,(246+$y)/$z,(294+$x)/$z,(236+$y)/$z,(297+$x)/$z,(224+$y)/$z,(298+$x)/$z,(187+$y)/$z),'FD');
			//ouargla
			$this->color($data['30']);$this->Polygon(array((374+$x)/$z,(146+$y)/$z,(372+$x)/$z,(137+$y)/$z,(373+$x)/$z,(132+$y)/$z,(374+$x)/$z,(137+$y)/$z,(380+$x)/$z,(132+$y)/$z,(401+$x)/$z,(131+$y)/$z,(400+$x)/$z,(125+$y)/$z,(402+$x)/$z,(122+$y)/$z,(399+$x)/$z,(119+$y)/$z,(400+$x)/$z,(116+$y)/$z,(402+$x)/$z,(115+$y)/$z,(405+$x)/$z,(113+$y)/$z,(407+$x)/$z,(122+$y)/$z,(409+$x)/$z,(129+$y)/$z,(417+$x)/$z,(149+$y)/$z,(420+$x)/$z,(154+$y)/$z,(422+$x)/$z,(160+$y)/$z,(426+$x)/$z,(162+$y)/$z,(431+$x)/$z,(171+$y)/$z,(480+$x)/$z,(167+$y)/$z,(494+$x)/$z,(212+$y)/$z,(483+$x)/$z,(215+$y)/$z,(452+$x)/$z,(223+$y)/$z,(423+$x)/$z,(231+$y)/$z,(419+$x)/$z,(233+$y)/$z,(415+$x)/$z,(232+$y)/$z,(408+$x)/$z,(235+$y)/$z,(400+$x)/$z,(239+$y)/$z,(396+$x)/$z,(243+$y)/$z,(391+$x)/$z,(244+$y)/$z,(390+$x)/$z,(248+$y)/$z,(386+$x)/$z,(251+$y)/$z,(382+$x)/$z,(253+$y)/$z,(379+$x)/$z,(257+$y)/$z,(375+$x)/$z,(258+$y)/$z,(368+$x)/$z,(262+$y)/$z,(366+$x)/$z,(265+$y)/$z,(363+$x)/$z,(269+$y)/$z,(357+$x)/$z,(271+$y)/$z,(355+$x)/$z,(275+$y)/$z,(349+$x)/$z,(277+$y)/$z,(344+$x)/$z,(282+$y)/$z,(339+$x)/$z,(276+$y)/$z,(333+$x)/$z,(275+$y)/$z,(330+$x)/$z,(269+$y)/$z,(324+$x)/$z,(265+$y)/$z,(336+$x)/$z,(255+$y)/$z,(344+$x)/$z,(240+$y)/$z,(352+$x)/$z,(213+$y)/$z,(360+$x)/$z,(188+$y)/$z,(360+$x)/$z,(170+$y)/$z,(369+$x)/$z,(160+$y)/$z,(374+$x)/$z,(153+$y)/$z,(374+$x)/$z,(146+$y)/$z),'FD');
			//bechar
			$this->color($data['8']);$this->Polygon(array((98+$x)/$z,(244+$y)/$z,(101+$x)/$z,(240+$y)/$z,(108+$x)/$z,(231+$y)/$z,(114+$x)/$z,(227+$y)/$z,(119+$x)/$z,(226+$y)/$z,(122+$x)/$z,(222+$y)/$z,(128+$x)/$z,(219+$y)/$z,(133+$x)/$z,(215+$y)/$z,(142+$x)/$z,(215+$y)/$z,(147+$x)/$z,(213+$y)/$z,(148+$x)/$z,(207+$y)/$z,(151+$x)/$z,(204+$y)/$z,(147+$x)/$z,(199+$y)/$z,(145+$x)/$z,(195+$y)/$z,(148+$x)/$z,(191+$y)/$z,(149+$x)/$z,(185+$y)/$z,(154+$x)/$z,(183+$y)/$z,(160+$x)/$z,(182+$y)/$z,(169+$x)/$z,(180+$y)/$z,(169+$x)/$z,(171+$y)/$z,(178+$x)/$z,(170+$y)/$z,(192+$x)/$z,(169+$y)/$z,(204+$x)/$z,(171+$y)/$z,(212+$x)/$z,(171+$y)/$z,(212+$x)/$z,(163+$y)/$z,(217+$x)/$z,(163+$y)/$z,(218+$x)/$z,(169+$y)/$z,(226+$x)/$z,(167+$y)/$z,(237+$x)/$z,(169+$y)/$z,(235+$x)/$z,(194+$y)/$z,(239+$x)/$z,(197+$y)/$z,(238+$x)/$z,(202+$y)/$z,(244+$x)/$z,(206+$y)/$z,(245+$x)/$z,(210+$y)/$z,(250+$x)/$z,(210+$y)/$z,(255+$x)/$z,(214+$y)/$z,(247+$x)/$z,(224+$y)/$z,(242+$x)/$z,(229+$y)/$z,(242+$x)/$z,(234+$y)/$z,(238+$x)/$z,(235+$y)/$z,(233+$x)/$z,(240+$y)/$z,(226+$x)/$z,(244+$y)/$z,(226+$x)/$z,(261+$y)/$z,(222+$x)/$z,(263+$y)/$z,(219+$x)/$z,(267+$y)/$z,(200+$x)/$z,(269+$y)/$z,(190+$x)/$z,(272+$y)/$z,(183+$x)/$z,(282+$y)/$z,(174+$x)/$z,(289+$y)/$z,(168+$x)/$z,(299+$y)/$z,(163+$x)/$z,(302+$y)/$z,(159+$x)/$z,(318+$y)/$z,(147+$x)/$z,(314+$y)/$z,(141+$x)/$z,(310+$y)/$z,(136+$x)/$z,(304+$y)/$z,(136+$x)/$z,(295+$y)/$z,(133+$x)/$z,(290+$y)/$z,(125+$x)/$z,(271+$y)/$z,(120+$x)/$z,(254+$y)/$z,(113+$x)/$z,(250+$y)/$z,(106+$x)/$z,(250+$y)/$z,(100+$x)/$z,(248+$y)/$z,(98+$x)/$z,(244+$y)/$z),'FD');
			//elbayed
			$this->color($data['32']);$this->Polygon(array((236+$x)/$z,(170+$y)/$z,(240+$x)/$z,(169+$y)/$z,(242+$x)/$z,(165+$y)/$z,(243+$x)/$z,(160+$y)/$z,(244+$x)/$z,(153+$y)/$z,(243+$x)/$z,(148+$y)/$z,(245+$x)/$z,(145+$y)/$z,(245+$x)/$z,(139+$y)/$z,(246+$x)/$z,(131+$y)/$z,(249+$x)/$z,(127+$y)/$z,(249+$x)/$z,(124+$y)/$z,(247+$x)/$z,(122+$y)/$z,(250+$x)/$z,(122+$y)/$z,(247+$x)/$z,(118+$y)/$z,(247+$x)/$z,(115+$y)/$z,(245+$x)/$z,(113+$y)/$z,(244+$x)/$z,(108+$y)/$z,(246+$x)/$z,(106+$y)/$z,(246+$x)/$z,(101+$y)/$z,(251+$x)/$z,(104+$y)/$z,(256+$x)/$z,(102+$y)/$z,(260+$x)/$z,(104+$y)/$z,(262+$x)/$z,(101+$y)/$z,(268+$x)/$z,(101+$y)/$z,(272+$x)/$z,(103+$y)/$z,(272+$x)/$z,(107+$y)/$z,(277+$x)/$z,(107+$y)/$z,(280+$x)/$z,(112+$y)/$z,(285+$x)/$z,(118+$y)/$z,(289+$x)/$z,(118+$y)/$z,(292+$x)/$z,(123+$y)/$z,(294+$x)/$z,(128+$y)/$z,(294+$x)/$z,(132+$y)/$z,(298+$x)/$z,(132+$y)/$z,(299+$x)/$z,(136+$y)/$z,(304+$x)/$z,(136+$y)/$z,(304+$x)/$z,(139+$y)/$z,(299+$x)/$z,(140+$y)/$z,(303+$x)/$z,(151+$y)/$z,(303+$x)/$z,(162+$y)/$z,(306+$x)/$z,(164+$y)/$z,(306+$x)/$z,(169+$y)/$z,(303+$x)/$z,(170+$y)/$z,(304+$x)/$z,(179+$y)/$z,(298+$x)/$z,(187+$y)/$z,(295+$x)/$z,(187+$y)/$z,(292+$x)/$z,(190+$y)/$z,(286+$x)/$z,(194+$y)/$z,(282+$x)/$z,(197+$y)/$z,(277+$x)/$z,(198+$y)/$z,(274+$x)/$z,(203+$y)/$z,(269+$x)/$z,(204+$y)/$z,(265+$x)/$z,(209+$y)/$z,(261+$x)/$z,(210+$y)/$z,(255+$x)/$z,(214+$y)/$z,(249+$x)/$z,(210+$y)/$z,(245+$x)/$z,(210+$y)/$z,(244+$x)/$z,(206+$y)/$z,(238+$x)/$z,(202+$y)/$z,(238+$x)/$z,(197+$y)/$z,(235+$x)/$z,(193+$y)/$z,(236+$x)/$z,(170+$y)/$z),'FD');
			//naama
			$this->color($data['45']);$this->Polygon(array((217+$x)/$z,(163+$y)/$z,(218+$x)/$z,(160+$y)/$z,(218+$x)/$z,(157+$y)/$z,(215+$x)/$z,(155+$y)/$z,(211+$x)/$z,(153+$y)/$z,(209+$x)/$z,(149+$y)/$z,(206+$x)/$z,(146+$y)/$z,(208+$x)/$z,(143+$y)/$z,(207+$x)/$z,(140+$y)/$z,(203+$x)/$z,(137+$y)/$z,(203+$x)/$z,(131+$y)/$z,(204+$x)/$z,(128+$y)/$z,(204+$x)/$z,(124+$y)/$z,(202+$x)/$z,(122+$y)/$z,(202+$x)/$z,(113+$y)/$z,(204+$x)/$z,(109+$y)/$z,(209+$x)/$z,(106+$y)/$z,(218+$x)/$z,(105+$y)/$z,(223+$x)/$z,(106+$y)/$z,(227+$x)/$z,(106+$y)/$z,(230+$x)/$z,(104+$y)/$z,(234+$x)/$z,(106+$y)/$z,(236+$x)/$z,(115+$y)/$z,(244+$x)/$z,(108+$y)/$z,(244+$x)/$z,(113+$y)/$z,(247+$x)/$z,(115+$y)/$z,(247+$x)/$z,(118+$y)/$z,(250+$x)/$z,(120+$y)/$z,(247+$x)/$z,(122+$y)/$z,(249+$x)/$z,(124+$y)/$z,(249+$x)/$z,(127+$y)/$z,(246+$x)/$z,(131+$y)/$z,(245+$x)/$z,(139+$y)/$z,(245+$x)/$z,(145+$y)/$z,(243+$x)/$z,(148+$y)/$z,(244+$x)/$z,(153+$y)/$z,(243+$x)/$z,(160+$y)/$z,(241+$x)/$z,(165+$y)/$z,(240+$x)/$z,(169+$y)/$z,(236+$x)/$z,(169+$y)/$z,(226+$x)/$z,(167+$y)/$z,(218+$x)/$z,(169+$y)/$z,(217+$x)/$z,(163+$y)/$z),'FD');
			//laghouat
			$this->color($data['3']);$this->Polygon(array((280+$x)/$z,(112+$y)/$z,(282+$x)/$z,(111+$y)/$z,(283+$x)/$z,(107+$y)/$z,(288+$x)/$z,(107+$y)/$z,(290+$x)/$z,(104+$y)/$z,(291+$x)/$z,(100+$y)/$z,(296+$x)/$z,(99+$y)/$z,(301+$x)/$z,(95+$y)/$z,(304+$x)/$z,(93+$y)/$z,(307+$x)/$z,(103+$y)/$z,(310+$x)/$z,(106+$y)/$z,(312+$x)/$z,(110+$y)/$z,(316+$x)/$z,(111+$y)/$z,(318+$x)/$z,(108+$y)/$z,(320+$x)/$z,(105+$y)/$z,(323+$x)/$z,(107+$y)/$z,(326+$x)/$z,(119+$y)/$z,(331+$x)/$z,(120+$y)/$z,(335+$x)/$z,(123+$y)/$z,(338+$x)/$z,(124+$y)/$z,(341+$x)/$z,(128+$y)/$z,(345+$x)/$z,(130+$y)/$z,(349+$x)/$z,(131+$y)/$z,(353+$x)/$z,(133+$y)/$z,(358+$x)/$z,(138+$y)/$z,(361+$x)/$z,(143+$y)/$z,(347+$x)/$z,(144+$y)/$z,(343+$x)/$z,(144+$y)/$z,(340+$x)/$z,(142+$y)/$z,(338+$x)/$z,(144+$y)/$z,(327+$x)/$z,(145+$y)/$z,(332+$x)/$z,(147+$y)/$z,(331+$x)/$z,(150+$y)/$z,(323+$x)/$z,(148+$y)/$z,(315+$x)/$z,(150+$y)/$z,(303+$x)/$z,(151+$y)/$z,(299+$x)/$z,(140+$y)/$z,(304+$x)/$z,(139+$y)/$z,(304+$x)/$z,(136+$y)/$z,(299+$x)/$z,(136+$y)/$z,(298+$x)/$z,(132+$y)/$z,(294+$x)/$z,(132+$y)/$z,(294+$x)/$z,(128+$y)/$z,(292+$x)/$z,(123+$y)/$z,(289+$x)/$z,(118+$y)/$z,(285+$x)/$z,(118+$y)/$z,(280+$x)/$z,(112+$y)/$z,(280+$x)/$z,(112+$y)/$z),'FD');
			//tiaret
			$this->color($data['14']);$this->Polygon(array((304+$x)/$z,(93+$y)/$z,(306+$x)/$z,(90+$y)/$z,(308+$x)/$z,(86+$y)/$z,(310+$x)/$z,(82+$y)/$z,(313+$x)/$z,(80+$y)/$z,(314+$x)/$z,(76+$y)/$z,(311+$x)/$z,(74+$y)/$z,(307+$x)/$z,(74+$y)/$z,(303+$x)/$z,(73+$y)/$z,(299+$x)/$z,(71+$y)/$z,(298+$x)/$z,(68+$y)/$z,(295+$x)/$z,(66+$y)/$z,(290+$x)/$z,(66+$y)/$z,(285+$x)/$z,(65+$y)/$z,(281+$x)/$z,(63+$y)/$z,(278+$x)/$z,(63+$y)/$z,(273+$x)/$z,(65+$y)/$z,(270+$x)/$z,(66+$y)/$z,(268+$x)/$z,(71+$y)/$z,(267+$x)/$z,(73+$y)/$z,(262+$x)/$z,(74+$y)/$z,(260+$x)/$z,(79+$y)/$z,(263+$x)/$z,(81+$y)/$z,(266+$x)/$z,(83+$y)/$z,(265+$x)/$z,(87+$y)/$z,(263+$x)/$z,(90+$y)/$z,(265+$x)/$z,(94+$y)/$z,(268+$x)/$z,(98+$y)/$z,(268+$x)/$z,(101+$y)/$z,(272+$x)/$z,(103+$y)/$z,(272+$x)/$z,(107+$y)/$z,(277+$x)/$z,(107+$y)/$z,(280+$x)/$z,(112+$y)/$z,(282+$x)/$z,(111+$y)/$z,(283+$x)/$z,(107+$y)/$z,(288+$x)/$z,(107+$y)/$z,(290+$x)/$z,(104+$y)/$z,(291+$x)/$z,(100+$y)/$z,(296+$x)/$z,(99+$y)/$z,(301+$x)/$z,(95+$y)/$z,(304+$x)/$z,(93+$y)/$z),'FD');
			//eloued
			$this->color($data['39']);$this->Polygon(array((380+$x)/$z,(132+$y)/$z,(380+$x)/$z,(126+$y)/$z,(380+$x)/$z,(121+$y)/$z,(379+$x)/$z,(117+$y)/$z,(380+$x)/$z,(113+$y)/$z,(382+$x)/$z,(109+$y)/$z,(380+$x)/$z,(105+$y)/$z,(378+$x)/$z,(102+$y)/$z,(379+$x)/$z,(99+$y)/$z,(383+$x)/$z,(98+$y)/$z,(396+$x)/$z,(100+$y)/$z,(402+$x)/$z,(99+$y)/$z,(409+$x)/$z,(100+$y)/$z,(413+$x)/$z,(103+$y)/$z,(418+$x)/$z,(104+$y)/$z,(424+$x)/$z,(106+$y)/$z,(425+$x)/$z,(102+$y)/$z,(430+$x)/$z,(100+$y)/$z,(434+$x)/$z,(101+$y)/$z,(440+$x)/$z,(103+$y)/$z,(444+$x)/$z,(104+$y)/$z,(439+$x)/$z,(106+$y)/$z,(438+$x)/$z,(112+$y)/$z,(439+$x)/$z,(120+$y)/$z,(442+$x)/$z,(122+$y)/$z,(444+$x)/$z,(128+$y)/$z,(446+$x)/$z,(133+$y)/$z,(447+$x)/$z,(136+$y)/$z,(451+$x)/$z,(136+$y)/$z,(455+$x)/$z,(139+$y)/$z,(456+$x)/$z,(143+$y)/$z,(459+$x)/$z,(144+$y)/$z,(461+$x)/$z,(154+$y)/$z,(466+$x)/$z,(158+$y)/$z,(471+$x)/$z,(161+$y)/$z,(476+$x)/$z,(163+$y)/$z,(479+$x)/$z,(167+$y)/$z,(431+$x)/$z,(171+$y)/$z,(426+$x)/$z,(162+$y)/$z,(422+$x)/$z,(160+$y)/$z,(420+$x)/$z,(154+$y)/$z,(417+$x)/$z,(149+$y)/$z,(409+$x)/$z,(129+$y)/$z,(407+$x)/$z,(122+$y)/$z,(405+$x)/$z,(113+$y)/$z,(402+$x)/$z,(115+$y)/$z,(400+$x)/$z,(116+$y)/$z,(399+$x)/$z,(119+$y)/$z,(402+$x)/$z,(122+$y)/$z,(400+$x)/$z,(125+$y)/$z,(401+$x)/$z,(131+$y)/$z,(380+$x)/$z,(132+$y)/$z),'FD');
			//djelfa
			$this->color($data['17']);$this->Polygon(array((298+$x)/$z,(68+$y)/$z,(300+$x)/$z,(66+$y)/$z,(302+$x)/$z,(67+$y)/$z,(304+$x)/$z,(69+$y)/$z,(307+$x)/$z,(69+$y)/$z,(309+$x)/$z,(67+$y)/$z,(311+$x)/$z,(70+$y)/$z,(313+$x)/$z,(70+$y)/$z,(315+$x)/$z,(67+$y)/$z,(318+$x)/$z,(65+$y)/$z,(318+$x)/$z,(58+$y)/$z,(322+$x)/$z,(58+$y)/$z,(323+$x)/$z,(63+$y)/$z,(326+$x)/$z,(63+$y)/$z,(329+$x)/$z,(59+$y)/$z,(332+$x)/$z,(61+$y)/$z,(334+$x)/$z,(64+$y)/$z,(337+$x)/$z,(65+$y)/$z,(339+$x)/$z,(68+$y)/$z,(339+$x)/$z,(71+$y)/$z,(337+$x)/$z,(74+$y)/$z,(334+$x)/$z,(76+$y)/$z,(333+$x)/$z,(79+$y)/$z,(336+$x)/$z,(81+$y)/$z,(336+$x)/$z,(87+$y)/$z,(340+$x)/$z,(89+$y)/$z,(343+$x)/$z,(90+$y)/$z,(344+$x)/$z,(94+$y)/$z,(346+$x)/$z,(98+$y)/$z,(347+$x)/$z,(102+$y)/$z,(350+$x)/$z,(105+$y)/$z,(353+$x)/$z,(108+$y)/$z,(356+$x)/$z,(111+$y)/$z,(355+$x)/$z,(115+$y)/$z,(358+$x)/$z,(118+$y)/$z,(362+$x)/$z,(120+$y)/$z,(365+$x)/$z,(121+$y)/$z,(369+$x)/$z,(122+$y)/$z,(372+$x)/$z,(123+$y)/$z,(374+$x)/$z,(125+$y)/$z,(374+$x)/$z,(130+$y)/$z,(373+$x)/$z,(132+$y)/$z,(372+$x)/$z,(137+$y)/$z,(374+$x)/$z,(146+$y)/$z,(360+$x)/$z,(143+$y)/$z,(357+$x)/$z,(138+$y)/$z,(353+$x)/$z,(133+$y)/$z,(349+$x)/$z,(131+$y)/$z,(345+$x)/$z,(130+$y)/$z,(340+$x)/$z,(128+$y)/$z,(338+$x)/$z,(124+$y)/$z,(335+$x)/$z,(123+$y)/$z,(331+$x)/$z,(121+$y)/$z,(326+$x)/$z,(119+$y)/$z,(323+$x)/$z,(107+$y)/$z,(320+$x)/$z,(105+$y)/$z,(318+$x)/$z,(108+$y)/$z,(316+$x)/$z,(111+$y)/$z,(312+$x)/$z,(110+$y)/$z,(310+$x)/$z,(106+$y)/$z,(307+$x)/$z,(103+$y)/$z,(304+$x)/$z,(93+$y)/$z,(306+$x)/$z,(90+$y)/$z,(308+$x)/$z,(86+$y)/$z,(310+$x)/$z,(82+$y)/$z,(313+$x)/$z,(80+$y)/$z,(314+$x)/$z,(76+$y)/$z,(311+$x)/$z,(74+$y)/$z,(307+$x)/$z,(74+$y)/$z,(303+$x)/$z,(73+$y)/$z,(298+$x)/$z,(68+$y)/$z),'FD');
			//biskra
			$this->color($data['7']);$this->Polygon(array((353+$x)/$z,(108+$y)/$z,(356+$x)/$z,(105+$y)/$z,(353+$x)/$z,(102+$y)/$z,(352+$x)/$z,(98+$y)/$z,(354+$x)/$z,(95+$y)/$z,(357+$x)/$z,(93+$y)/$z,(360+$x)/$z,(93+$y)/$z,(363+$x)/$z,(91+$y)/$z,(365+$x)/$z,(88+$y)/$z,(368+$x)/$z,(87+$y)/$z,(371+$x)/$z,(87+$y)/$z,(374+$x)/$z,(80+$y)/$z,(380+$x)/$z,(81+$y)/$z,(386+$x)/$z,(81+$y)/$z,(386+$x)/$z,(77+$y)/$z,(390+$x)/$z,(73+$y)/$z,(396+$x)/$z,(74+$y)/$z,(396+$x)/$z,(81+$y)/$z,(403+$x)/$z,(81+$y)/$z,(410+$x)/$z,(81+$y)/$z,(409+$x)/$z,(87+$y)/$z,(415+$x)/$z,(86+$y)/$z,(418+$x)/$z,(94+$y)/$z,(415+$x)/$z,(99+$y)/$z,(413+$x)/$z,(103+$y)/$z,(409+$x)/$z,(100+$y)/$z,(402+$x)/$z,(99+$y)/$z,(396+$x)/$z,(100+$y)/$z,(383+$x)/$z,(98+$y)/$z,(379+$x)/$z,(99+$y)/$z,(378+$x)/$z,(102+$y)/$z,(380+$x)/$z,(105+$y)/$z,(382+$x)/$z,(109+$y)/$z,(380+$x)/$z,(113+$y)/$z,(379+$x)/$z,(117+$y)/$z,(380+$x)/$z,(121+$y)/$z,(380+$x)/$z,(126+$y)/$z,(380+$x)/$z,(132+$y)/$z,(372+$x)/$z,(137+$y)/$z,(373+$x)/$z,(132+$y)/$z,(374+$x)/$z,(130+$y)/$z,(374+$x)/$z,(125+$y)/$z,(372+$x)/$z,(123+$y)/$z,(369+$x)/$z,(122+$y)/$z,(365+$x)/$z,(121+$y)/$z,(362+$x)/$z,(120+$y)/$z,(358+$x)/$z,(118+$y)/$z,(355+$x)/$z,(115+$y)/$z,(356+$x)/$z,(111+$y)/$z,(353+$x)/$z,(108+$y)/$z),'FD');
			//msila
			$this->color($data['28']);$this->Polygon(array((338+$x)/$z,(56+$y)/$z,(341+$x)/$z,(56+$y)/$z,(344+$x)/$z,(56+$y)/$z,(347+$x)/$z,(55+$y)/$z,(349+$x)/$z,(52+$y)/$z,(352+$x)/$z,(52+$y)/$z,(355+$x)/$z,(52+$y)/$z,(358+$x)/$z,(51+$y)/$z,(358+$x)/$z,(53+$y)/$z,(357+$x)/$z,(56+$y)/$z,(359+$x)/$z,(56+$y)/$z,(361+$x)/$z,(56+$y)/$z,(364+$x)/$z,(56+$y)/$z,(366+$x)/$z,(56+$y)/$z,(370+$x)/$z,(57+$y)/$z,(372+$x)/$z,(58+$y)/$z,(374+$x)/$z,(60+$y)/$z,(376+$x)/$z,(63+$y)/$z,(379+$x)/$z,(64+$y)/$z,(375+$x)/$z,(66+$y)/$z,(369+$x)/$z,(66+$y)/$z,(369+$x)/$z,(72+$y)/$z,(367+$x)/$z,(75+$y)/$z,(367+$x)/$z,(79+$y)/$z,(373+$x)/$z,(80+$y)/$z,(371+$x)/$z,(87+$y)/$z,(368+$x)/$z,(87+$y)/$z,(365+$x)/$z,(88+$y)/$z,(363+$x)/$z,(91+$y)/$z,(360+$x)/$z,(93+$y)/$z,(357+$x)/$z,(93+$y)/$z,(354+$x)/$z,(95+$y)/$z,(352+$x)/$z,(98+$y)/$z,(353+$x)/$z,(102+$y)/$z,(355+$x)/$z,(105+$y)/$z,(353+$x)/$z,(108+$y)/$z,(350+$x)/$z,(105+$y)/$z,(347+$x)/$z,(102+$y)/$z,(346+$x)/$z,(98+$y)/$z,(344+$x)/$z,(94+$y)/$z,(343+$x)/$z,(90+$y)/$z,(340+$x)/$z,(89+$y)/$z,(336+$x)/$z,(87+$y)/$z,(336+$x)/$z,(81+$y)/$z,(333+$x)/$z,(79+$y)/$z,(334+$x)/$z,(76+$y)/$z,(337+$x)/$z,(74+$y)/$z,(339+$x)/$z,(71+$y)/$z,(339+$x)/$z,(68+$y)/$z,(337+$x)/$z,(65+$y)/$z,(334+$x)/$z,(64+$y)/$z,(332+$x)/$z,(61+$y)/$z,(338+$x)/$z,(56+$y)/$z),'FD');
			//batna
			$this->color($data['5']);$this->Polygon(array((379+$x)/$z,(64+$y)/$z,(382+$x)/$z,(63+$y)/$z,(382+$x)/$z,(59+$y)/$z,(388+$x)/$z,(58+$y)/$z,(390+$x)/$z,(54+$y)/$z,(395+$x)/$z,(56+$y)/$z,(400+$x)/$z,(55+$y)/$z,(404+$x)/$z,(56+$y)/$z,(407+$x)/$z,(56+$y)/$z,(410+$x)/$z,(57+$y)/$z,(413+$x)/$z,(57+$y)/$z,(416+$x)/$z,(60+$y)/$z,(416+$x)/$z,(63+$y)/$z,(413+$x)/$z,(65+$y)/$z,(412+$x)/$z,(71+$y)/$z,(412+$x)/$z,(75+$y)/$z,(411+$x)/$z,(78+$y)/$z,(409+$x)/$z,(81+$y)/$z,(403+$x)/$z,(81+$y)/$z,(396+$x)/$z,(81+$y)/$z,(396+$x)/$z,(74+$y)/$z,(389+$x)/$z,(73+$y)/$z,(386+$x)/$z,(77+$y)/$z,(386+$x)/$z,(81+$y)/$z,(380+$x)/$z,(81+$y)/$z,(373+$x)/$z,(80+$y)/$z,(367+$x)/$z,(79+$y)/$z,(367+$x)/$z,(75+$y)/$z,(369+$x)/$z,(72+$y)/$z,(369+$x)/$z,(66+$y)/$z,(375+$x)/$z,(66+$y)/$z,(379+$x)/$z,(64+$y)/$z),'FD');
			//khanchela
			$this->color($data['40']);$this->Polygon(array((416+$x)/$z,(63+$y)/$z,(419+$x)/$z,(62+$y)/$z,(423+$x)/$z,(63+$y)/$z,(426+$x)/$z,(62+$y)/$z,(429+$x)/$z,(62+$y)/$z,(432+$x)/$z,(63+$y)/$z,(435+$x)/$z,(65+$y)/$z,(433+$x)/$z,(68+$y)/$z,(433+$x)/$z,(76+$y)/$z,(430+$x)/$z,(79+$y)/$z,(431+$x)/$z,(83+$y)/$z,(434+$x)/$z,(87+$y)/$z,(431+$x)/$z,(92+$y)/$z,(430+$x)/$z,(100+$y)/$z,(425+$x)/$z,(102+$y)/$z,(423+$x)/$z,(106+$y)/$z,(418+$x)/$z,(104+$y)/$z,(413+$x)/$z,(103+$y)/$z,(415+$x)/$z,(99+$y)/$z,(417+$x)/$z,(94+$y)/$z,(415+$x)/$z,(86+$y)/$z,(409+$x)/$z,(87+$y)/$z,(409+$x)/$z,(81+$y)/$z,(411+$x)/$z,(78+$y)/$z,(413+$x)/$z,(75+$y)/$z,(412+$x)/$z,(71+$y)/$z,(413+$x)/$z,(65+$y)/$z,(416+$x)/$z,(63+$y)/$z),'FD');
			//tebessa
			$this->color($data['12']);$this->Polygon(array((435+$x)/$z,(65+$y)/$z,(438+$x)/$z,(64+$y)/$z,(442+$x)/$z,(62+$y)/$z,(443+$x)/$z,(56+$y)/$z,(442+$x)/$z,(52+$y)/$z,(446+$x)/$z,(50+$y)/$z,(450+$x)/$z,(48+$y)/$z,(453+$x)/$z,(48+$y)/$z,(454+$x)/$z,(56+$y)/$z,(456+$x)/$z,(59+$y)/$z,(456+$x)/$z,(63+$y)/$z,(454+$x)/$z,(66+$y)/$z,(455+$x)/$z,(70+$y)/$z,(459+$x)/$z,(72+$y)/$z,(456+$x)/$z,(75+$y)/$z,(456+$x)/$z,(79+$y)/$z,(454+$x)/$z,(82+$y)/$z,(455+$x)/$z,(86+$y)/$z,(454+$x)/$z,(90+$y)/$z,(452+$x)/$z,(95+$y)/$z,(446+$x)/$z,(97+$y)/$z,(443+$x)/$z,(104+$y)/$z,(440+$x)/$z,(103+$y)/$z,(434+$x)/$z,(101+$y)/$z,(430+$x)/$z,(100+$y)/$z,(431+$x)/$z,(92+$y)/$z,(433+$x)/$z,(87+$y)/$z,(432+$x)/$z,(83+$y)/$z,(430+$x)/$z,(79+$y)/$z,(433+$x)/$z,(76+$y)/$z,(433+$x)/$z,(68+$y)/$z,(435+$x)/$z,(65+$y)/$z),'FD');
			//saida
			$this->color($data['20']);$this->Polygon(array((246+$x)/$z,(101+$y)/$z,(243+$x)/$z,(97+$y)/$z,(240+$x)/$z,(96+$y)/$z,(243+$x)/$z,(94+$y)/$z,(242+$x)/$z,(90+$y)/$z,(238+$x)/$z,(89+$y)/$z,(238+$x)/$z,(84+$y)/$z,(241+$x)/$z,(81+$y)/$z,(245+$x)/$z,(80+$y)/$z,(249+$x)/$z,(80+$y)/$z,(251+$x)/$z,(82+$y)/$z,(254+$x)/$z,(82+$y)/$z,(257+$x)/$z,(81+$y)/$z,(260+$x)/$z,(79+$y)/$z,(263+$x)/$z,(81+$y)/$z,(266+$x)/$z,(83+$y)/$z,(265+$x)/$z,(87+$y)/$z,(263+$x)/$z,(90+$y)/$z,(265+$x)/$z,(94+$y)/$z,(268+$x)/$z,(98+$y)/$z,(268+$x)/$z,(101+$y)/$z,(262+$x)/$z,(101+$y)/$z,(259+$x)/$z,(104+$y)/$z,(256+$x)/$z,(102+$y)/$z,(252+$x)/$z,(104+$y)/$z,(246+$x)/$z,(101+$y)/$z),'FD');
			//sidi belabas
			$this->color($data['22']);$this->Polygon(array((218+$x)/$z,(105+$y)/$z,(220+$x)/$z,(102+$y)/$z,(223+$x)/$z,(97+$y)/$z,(225+$x)/$z,(95+$y)/$z,(226+$x)/$z,(90+$y)/$z,(223+$x)/$z,(88+$y)/$z,(221+$x)/$z,(84+$y)/$z,(221+$x)/$z,(80+$y)/$z,(223+$x)/$z,(76+$y)/$z,(226+$x)/$z,(73+$y)/$z,(232+$x)/$z,(71+$y)/$z,(235+$x)/$z,(72+$y)/$z,(241+$x)/$z,(72+$y)/$z,(241+$x)/$z,(81+$y)/$z,(238+$x)/$z,(84+$y)/$z,(238+$x)/$z,(89+$y)/$z,(242+$x)/$z,(90+$y)/$z,(243+$x)/$z,(94+$y)/$z,(240+$x)/$z,(96+$y)/$z,(243+$x)/$z,(97+$y)/$z,(246+$x)/$z,(101+$y)/$z,(246+$x)/$z,(106+$y)/$z,(244+$x)/$z,(108+$y)/$z,(244+$x)/$z,(113+$y)/$z,(236+$x)/$z,(115+$y)/$z,(234+$x)/$z,(106+$y)/$z,(230+$x)/$z,(104+$y)/$z,(227+$x)/$z,(106+$y)/$z,(223+$x)/$z,(106+$y)/$z,(218+$x)/$z,(105+$y)/$z),'FD');
			//tlemcen
			$this->color($data['13']);$this->Polygon(array((204+$x)/$z,(109+$y)/$z,(202+$x)/$z,(104+$y)/$z,(200+$x)/$z,(101+$y)/$z,(202+$x)/$z,(98+$y)/$z,(199+$x)/$z,(94+$y)/$z,(201+$x)/$z,(90+$y)/$z,(197+$x)/$z,(88+$y)/$z,(195+$x)/$z,(85+$y)/$z,(191+$x)/$z,(81+$y)/$z,(199+$x)/$z,(80+$y)/$z,(202+$x)/$z,(79+$y)/$z,(203+$x)/$z,(75+$y)/$z,(207+$x)/$z,(75+$y)/$z,(211+$x)/$z,(77+$y)/$z,(216+$x)/$z,(77+$y)/$z,(221+$x)/$z,(80+$y)/$z,(221+$x)/$z,(84+$y)/$z,(223+$x)/$z,(88+$y)/$z,(226+$x)/$z,(90+$y)/$z,(225+$x)/$z,(95+$y)/$z,(223+$x)/$z,(97+$y)/$z,(220+$x)/$z,(102+$y)/$z,(218+$x)/$z,(105+$y)/$z,(209+$x)/$z,(106+$y)/$z,(204+$x)/$z,(109+$y)/$z),'FD');
			//aintimouchent
			$this->color($data['46']);$this->Polygon(array((232+$x)/$z,(71+$y)/$z,(232+$x)/$z,(68+$y)/$z,(229+$x)/$z,(66+$y)/$z,(225+$x)/$z,(65+$y)/$z,(222+$x)/$z,(67+$y)/$z,(220+$x)/$z,(64+$y)/$z,(217+$x)/$z,(65+$y)/$z,(214+$x)/$z,(68+$y)/$z,(214+$x)/$z,(71+$y)/$z,(211+$x)/$z,(74+$y)/$z,(207+$x)/$z,(73+$y)/$z,(203+$x)/$z,(75+$y)/$z,(207+$x)/$z,(75+$y)/$z,(211+$x)/$z,(77+$y)/$z,(216+$x)/$z,(77+$y)/$z,(221+$x)/$z,(80+$y)/$z,(223+$x)/$z,(76+$y)/$z,(226+$x)/$z,(73+$y)/$z,(232+$x)/$z,(71+$y)/$z),'FD');
			//maascare
			$this->color($data['29']);$this->Polygon(array((232+$x)/$z,(68+$y)/$z,(235+$x)/$z,(66+$y)/$z,(237+$x)/$z,(64+$y)/$z,(240+$x)/$z,(62+$y)/$z,(241+$x)/$z,(61+$y)/$z,(246+$x)/$z,(60+$y)/$z,(249+$x)/$z,(62+$y)/$z,(252+$x)/$z,(63+$y)/$z,(254+$x)/$z,(65+$y)/$z,(257+$x)/$z,(66+$y)/$z,(260+$x)/$z,(67+$y)/$z,(263+$x)/$z,(68+$y)/$z,(266+$x)/$z,(69+$y)/$z,(268+$x)/$z,(70+$y)/$z,(267+$x)/$z,(73+$y)/$z,(262+$x)/$z,(74+$y)/$z,(260+$x)/$z,(79+$y)/$z,(257+$x)/$z,(81+$y)/$z,(254+$x)/$z,(82+$y)/$z,(251+$x)/$z,(82+$y)/$z,(249+$x)/$z,(80+$y)/$z,(245+$x)/$z,(80+$y)/$z,(241+$x)/$z,(81+$y)/$z,(241+$x)/$z,(72+$y)/$z,(235+$x)/$z,(72+$y)/$z,(232+$x)/$z,(71+$y)/$z,(232+$x)/$z,(68+$y)/$z),'FD');
			//oran
			$this->color($data['31']);$this->Polygon(array((220+$x)/$z,(64+$y)/$z,(223+$x)/$z,(62+$y)/$z,(226+$x)/$z,(60+$y)/$z,(229+$x)/$z,(61+$y)/$z,(232+$x)/$z,(60+$y)/$z,(234+$x)/$z,(58+$y)/$z,(236+$x)/$z,(56+$y)/$z,(239+$x)/$z,(57+$y)/$z,(242+$x)/$z,(58+$y)/$z,(246+$x)/$z,(60+$y)/$z,(243+$x)/$z,(61+$y)/$z,(240+$x)/$z,(60+$y)/$z,(237+$x)/$z,(64+$y)/$z,(235+$x)/$z,(66+$y)/$z,(232+$x)/$z,(68+$y)/$z,(229+$x)/$z,(66+$y)/$z,(225+$x)/$z,(65+$y)/$z,(222+$x)/$z,(67+$y)/$z,(220+$x)/$z,(64+$y)/$z),'FD');
			//ghelizane
			$this->color($data['48']);$this->Polygon(array((252+$x)/$z,(63+$y)/$z,(252+$x)/$z,(60+$y)/$z,(254+$x)/$z,(58+$y)/$z,(257+$x)/$z,(57+$y)/$z,(258+$x)/$z,(54+$y)/$z,(261+$x)/$z,(52+$y)/$z,(261+$x)/$z,(49+$y)/$z,(263+$x)/$z,(47+$y)/$z,(266+$x)/$z,(46+$y)/$z,(269+$x)/$z,(48+$y)/$z,(271+$x)/$z,(52+$y)/$z,(275+$x)/$z,(54+$y)/$z,(278+$x)/$z,(54+$y)/$z,(281+$x)/$z,(55+$y)/$z,(280+$x)/$z,(59+$y)/$z,(281+$x)/$z,(63+$y)/$z,(278+$x)/$z,(63+$y)/$z,(273+$x)/$z,(65+$y)/$z,(270+$x)/$z,(66+$y)/$z,(268+$x)/$z,(70+$y)/$z,(266+$x)/$z,(69+$y)/$z,(263+$x)/$z,(68+$y)/$z,(260+$x)/$z,(67+$y)/$z,(257+$x)/$z,(66+$y)/$z,(254+$x)/$z,(65+$y)/$z,(252+$x)/$z,(63+$y)/$z),'FD');
			//mostaghanem
			$this->color($data['27']);$this->Polygon(array((242+$x)/$z,(58+$y)/$z,(245+$x)/$z,(57+$y)/$z,(248+$x)/$z,(55+$y)/$z,(249+$x)/$z,(51+$y)/$z,(252+$x)/$z,(48+$y)/$z,(256+$x)/$z,(47+$y)/$z,(259+$x)/$z,(45+$y)/$z,(261+$x)/$z,(42+$y)/$z,(265+$x)/$z,(42+$y)/$z,(266+$x)/$z,(46+$y)/$z,(263+$x)/$z,(47+$y)/$z,(261+$x)/$z,(49+$y)/$z,(261+$x)/$z,(52+$y)/$z,(258+$x)/$z,(54+$y)/$z,(257+$x)/$z,(57+$y)/$z,(254+$x)/$z,(58+$y)/$z,(252+$x)/$z,(60+$y)/$z,(252+$x)/$z,(63+$y)/$z,(249+$x)/$z,(62+$y)/$z,(246+$x)/$z,(60+$y)/$z,(242+$x)/$z,(58+$y)/$z),'FD');
			//chelef
			$this->color($data['2']);$this->Polygon(array((265+$x)/$z,(42+$y)/$z,(268+$x)/$z,(40+$y)/$z,(271+$x)/$z,(38+$y)/$z,(274+$x)/$z,(37+$y)/$z,(278+$x)/$z,(37+$y)/$z,(281+$x)/$z,(36+$y)/$z,(285+$x)/$z,(37+$y)/$z,(287+$x)/$z,(39+$y)/$z,(284+$x)/$z,(41+$y)/$z,(285+$x)/$z,(45+$y)/$z,(287+$x)/$z,(50+$y)/$z,(285+$x)/$z,(53+$y)/$z,(281+$x)/$z,(55+$y)/$z,(278+$x)/$z,(54+$y)/$z,(275+$x)/$z,(54+$y)/$z,(271+$x)/$z,(52+$y)/$z,(269+$x)/$z,(48+$y)/$z,(266+$x)/$z,(46+$y)/$z,(265+$x)/$z,(42+$y)/$z),'FD');
			//tissemsilet 
			$this->color($data['38']);$this->Polygon(array((287+$x)/$z,(50+$y)/$z,(288+$x)/$z,(53+$y)/$z,(290+$x)/$z,(50+$y)/$z,(293+$x)/$z,(56+$y)/$z,(295+$x)/$z,(54+$y)/$z,(298+$x)/$z,(54+$y)/$z,(301+$x)/$z,(55+$y)/$z,(303+$x)/$z,(57+$y)/$z,(302+$x)/$z,(60+$y)/$z,(301+$x)/$z,(63+$y)/$z,(300+$x)/$z,(66+$y)/$z,(298+$x)/$z,(68+$y)/$z,(295+$x)/$z,(66+$y)/$z,(290+$x)/$z,(66+$y)/$z,(285+$x)/$z,(65+$y)/$z,(281+$x)/$z,(63+$y)/$z,(280+$x)/$z,(59+$y)/$z,(281+$x)/$z,(55+$y)/$z,(285+$x)/$z,(53+$y)/$z,(287+$x)/$z,(50+$y)/$z),'FD');
			//aindefla
			$this->color($data['44']);$this->Polygon(array((287+$x)/$z,(39+$y)/$z,(290+$x)/$z,(40+$y)/$z,(293+$x)/$z,(40+$y)/$z,(296+$x)/$z,(40+$y)/$z,(299+$x)/$z,(39+$y)/$z,(302+$x)/$z,(39+$y)/$z,(309+$x)/$z,(39+$y)/$z,(309+$x)/$z,(42+$y)/$z,(309+$x)/$z,(46+$y)/$z,(311+$x)/$z,(49+$y)/$z,(307+$x)/$z,(51+$y)/$z,(307+$x)/$z,(54+$y)/$z,(303+$x)/$z,(57+$y)/$z,(301+$x)/$z,(55+$y)/$z,(298+$x)/$z,(54+$y)/$z,(295+$x)/$z,(54+$y)/$z,(293+$x)/$z,(56+$y)/$z,(290+$x)/$z,(55+$y)/$z,(288+$x)/$z,(53+$y)/$z,(287+$x)/$z,(50+$y)/$z,(285+$x)/$z,(45+$y)/$z,(284+$x)/$z,(41+$y)/$z,(287+$x)/$z,(39+$y)/$z),'FD');
			//tipaza
			$this->color($data['42']);$this->Polygon(array((285+$x)/$z,(37+$y)/$z,(288+$x)/$z,(36+$y)/$z,(290+$x)/$z,(34+$y)/$z,(293+$x)/$z,(34+$y)/$z,(296+$x)/$z,(34+$y)/$z,(299+$x)/$z,(34+$y)/$z,(302+$x)/$z,(33+$y)/$z,(305+$x)/$z,(32+$y)/$z,(309+$x)/$z,(34+$y)/$z,(313+$x)/$z,(33+$y)/$z,(316+$x)/$z,(32+$y)/$z,(314+$x)/$z,(36+$y)/$z,(311+$x)/$z,(38+$y)/$z,(309+$x)/$z,(39+$y)/$z,(302+$x)/$z,(39+$y)/$z,(299+$x)/$z,(39+$y)/$z,(296+$x)/$z,(40+$y)/$z,(293+$x)/$z,(40+$y)/$z,(290+$x)/$z,(40+$y)/$z,(287+$x)/$z,(39+$y)/$z,(285+$x)/$z,(37+$y)/$z),'FD');
			//medea
			$this->color($data['26']);$this->Polygon(array((309+$x)/$z,(42+$y)/$z,(311+$x)/$z,(42+$y)/$z,(314+$x)/$z,(41+$y)/$z,(317+$x)/$z,(41+$y)/$z,(320+$x)/$z,(41+$y)/$z,(323+$x)/$z,(40+$y)/$z,(326+$x)/$z,(38+$y)/$z,(329+$x)/$z,(37+$y)/$z,(331+$x)/$z,(39+$y)/$z,(334+$x)/$z,(40+$y)/$z,(334+$x)/$z,(46+$y)/$z,(332+$x)/$z,(50+$y)/$z,(334+$x)/$z,(52+$y)/$z,(337+$x)/$z,(56+$y)/$z,(332+$x)/$z,(61+$y)/$z,(329+$x)/$z,(59+$y)/$z,(326+$x)/$z,(63+$y)/$z,(323+$x)/$z,(63+$y)/$z,(322+$x)/$z,(58+$y)/$z,(318+$x)/$z,(58+$y)/$z,(318+$x)/$z,(65+$y)/$z,(315+$x)/$z,(67+$y)/$z,(313+$x)/$z,(70+$y)/$z,(311+$x)/$z,(70+$y)/$z,(309+$x)/$z,(67+$y)/$z,(307+$x)/$z,(69+$y)/$z,(304+$x)/$z,(69+$y)/$z,(302+$x)/$z,(67+$y)/$z,(300+$x)/$z,(66+$y)/$z,(301+$x)/$z,(63+$y)/$z,(302+$x)/$z,(60+$y)/$z,(303+$x)/$z,(57+$y)/$z,(307+$x)/$z,(54+$y)/$z,(307+$x)/$z,(51+$y)/$z,(311+$x)/$z,(49+$y)/$z,(309+$x)/$z,(46+$y)/$z,(309+$x)/$z,(42+$y)/$z),'FD');
			//alger
			$this->color($data['16']);$this->Polygon(array((316+$x)/$z,(32+$y)/$z,(319+$x)/$z,(33+$y)/$z,(322+$x)/$z,(34+$y)/$z,(325+$x)/$z,(33+$y)/$z,(328+$x)/$z,(31+$y)/$z,(328+$x)/$z,(28+$y)/$z,(324+$x)/$z,(30+$y)/$z,(322+$x)/$z,(27+$y)/$z,(318+$x)/$z,(27+$y)/$z,(316+$x)/$z,(32+$y)/$z),'FD');
			//blida
			$this->color($data['9']);$this->Polygon(array((325+$x)/$z,(33+$y)/$z,(327+$x)/$z,(35+$y)/$z,(326+$x)/$z,(38+$y)/$z,(323+$x)/$z,(40+$y)/$z,(320+$x)/$z,(41+$y)/$z,(317+$x)/$z,(41+$y)/$z,(314+$x)/$z,(41+$y)/$z,(311+$x)/$z,(42+$y)/$z,(309+$x)/$z,(42+$y)/$z,(309+$x)/$z,(39+$y)/$z,(311+$x)/$z,(38+$y)/$z,(314+$x)/$z,(36+$y)/$z,(313+$x)/$z,(33+$y)/$z,(316+$x)/$z,(32+$y)/$z,(319+$x)/$z,(33+$y)/$z,(322+$x)/$z,(34+$y)/$z,(325+$x)/$z,(33+$y)/$z),'FD');
			//bouira
			$this->color($data['10']);$this->Polygon(array((329+$x)/$z,(37+$y)/$z,(331+$x)/$z,(35+$y)/$z,(334+$x)/$z,(33+$y)/$z,(337+$x)/$z,(33+$y)/$z,(339+$x)/$z,(33+$y)/$z,(341+$x)/$z,(37+$y)/$z,(344+$x)/$z,(39+$y)/$z,(348+$x)/$z,(39+$y)/$z,(355+$x)/$z,(38+$y)/$z,(356+$x)/$z,(44+$y)/$z,(352+$x)/$z,(46+$y)/$z,(349+$x)/$z,(48+$y)/$z,(349+$x)/$z,(52+$y)/$z,(347+$x)/$z,(55+$y)/$z,(344+$x)/$z,(56+$y)/$z,(340+$x)/$z,(56+$y)/$z,(337+$x)/$z,(56+$y)/$z,(334+$x)/$z,(52+$y)/$z,(332+$x)/$z,(50+$y)/$z,(334+$x)/$z,(46+$y)/$z,(334+$x)/$z,(40+$y)/$z,(331+$x)/$z,(39+$y)/$z,(329+$x)/$z,(37+$y)/$z),'FD');
			//boumerdes
			$this->color($data['35']);$this->Polygon(array((328+$x)/$z,(28+$y)/$z,(331+$x)/$z,(29+$y)/$z,(334+$x)/$z,(27+$y)/$z,(337+$x)/$z,(26+$y)/$z,(340+$x)/$z,(27+$y)/$z,(342+$x)/$z,(24+$y)/$z,(346+$x)/$z,(24+$y)/$z,(346+$x)/$z,(27+$y)/$z,(344+$x)/$z,(29+$y)/$z,(342+$x)/$z,(31+$y)/$z,(339+$x)/$z,(33+$y)/$z,(337+$x)/$z,(33+$y)/$z,(334+$x)/$z,(33+$y)/$z,(331+$x)/$z,(35+$y)/$z,(329+$x)/$z,(37+$y)/$z,(326+$x)/$z,(38+$y)/$z,(325+$x)/$z,(33+$y)/$z,(328+$x)/$z,(32+$y)/$z,(328+$x)/$z,(28+$y)/$z),'FD');
			//tiziouzou
			$this->color($data['15']);$this->Polygon(array((346+$x)/$z,(24+$y)/$z,(350+$x)/$z,(24+$y)/$z,(355+$x)/$z,(24+$y)/$z,(362+$x)/$z,(24+$y)/$z,(362+$x)/$z,(28+$y)/$z,(360+$x)/$z,(32+$y)/$z,(357+$x)/$z,(35+$y)/$z,(355+$x)/$z,(38+$y)/$z,(348+$x)/$z,(39+$y)/$z,(344+$x)/$z,(39+$y)/$z,(341+$x)/$z,(37+$y)/$z,(339+$x)/$z,(33+$y)/$z,(342+$x)/$z,(31+$y)/$z,(344+$x)/$z,(29+$y)/$z,(346+$x)/$z,(27+$y)/$z,(346+$x)/$z,(24+$y)/$z),'FD');
			//bejaia
			$this->color($data['6']);$this->Polygon(array((362+$x)/$z,(24+$y)/$z,(365+$x)/$z,(24+$y)/$z,(368+$x)/$z,(25+$y)/$z,(371+$x)/$z,(26+$y)/$z,(373+$x)/$z,(30+$y)/$z,(381+$x)/$z,(31+$y)/$z,(381+$x)/$z,(33+$y)/$z,(380+$x)/$z,(37+$y)/$z,(379+$x)/$z,(40+$y)/$z,(376+$x)/$z,(40+$y)/$z,(375+$x)/$z,(34+$y)/$z,(372+$x)/$z,(34+$y)/$z,(369+$x)/$z,(35+$y)/$z,(366+$x)/$z,(37+$y)/$z,(364+$x)/$z,(40+$y)/$z,(362+$x)/$z,(42+$y)/$z,(359+$x)/$z,(44+$y)/$z,(356+$x)/$z,(44+$y)/$z,(355+$x)/$z,(38+$y)/$z,(357+$x)/$z,(35+$y)/$z,(360+$x)/$z,(32+$y)/$z,(362+$x)/$z,(28+$y)/$z,(362+$x)/$z,(24+$y)/$z),'FD');
			//bordj
			$this->color($data['34']);$this->Polygon(array((364+$x)/$z,(40+$y)/$z,(367+$x)/$z,(41+$y)/$z,(370+$x)/$z,(42+$y)/$z,(373+$x)/$z,(43+$y)/$z,(374+$x)/$z,(46+$y)/$z,(376+$x)/$z,(49+$y)/$z,(374+$x)/$z,(51+$y)/$z,(374+$x)/$z,(54+$y)/$z,(372+$x)/$z,(58+$y)/$z,(370+$x)/$z,(57+$y)/$z,(366+$x)/$z,(56+$y)/$z,(364+$x)/$z,(56+$y)/$z,(361+$x)/$z,(56+$y)/$z,(359+$x)/$z,(56+$y)/$z,(357+$x)/$z,(56+$y)/$z,(358+$x)/$z,(53+$y)/$z,(358+$x)/$z,(51+$y)/$z,(355+$x)/$z,(52+$y)/$z,(352+$x)/$z,(52+$y)/$z,(349+$x)/$z,(52+$y)/$z,(349+$x)/$z,(48+$y)/$z,(352+$x)/$z,(46+$y)/$z,(356+$x)/$z,(44+$y)/$z,(359+$x)/$z,(44+$y)/$z,(362+$x)/$z,(42+$y)/$z,(364+$x)/$z,(40+$y)/$z),'FD');
			//setif
			$this->color($data['9']);$this->Polygon(array((381+$x)/$z,(33+$y)/$z,(384+$x)/$z,(34+$y)/$z,(389+$x)/$z,(34+$y)/$z,(390+$x)/$z,(38+$y)/$z,(393+$x)/$z,(41+$y)/$z,(395+$x)/$z,(44+$y)/$z,(393+$x)/$z,(47+$y)/$z,(396+$x)/$z,(48+$y)/$z,(396+$x)/$z,(53+$y)/$z,(395+$x)/$z,(56+$y)/$z,(390+$x)/$z,(54+$y)/$z,(388+$x)/$z,(58+$y)/$z,(382+$x)/$z,(59+$y)/$z,(382+$x)/$z,(63+$y)/$z,(379+$x)/$z,(64+$y)/$z,(376+$x)/$z,(63+$y)/$z,(374+$x)/$z,(60+$y)/$z,(372+$x)/$z,(58+$y)/$z,(374+$x)/$z,(58+$y)/$z,(374+$x)/$z,(51+$y)/$z,(376+$x)/$z,(49+$y)/$z,(374+$x)/$z,(46+$y)/$z,(373+$x)/$z,(43+$y)/$z,(370+$x)/$z,(42+$y)/$z,(367+$x)/$z,(41+$y)/$z,(364+$x)/$z,(40+$y)/$z,(366+$x)/$z,(37+$y)/$z,(369+$x)/$z,(35+$y)/$z,(372+$x)/$z,(34+$y)/$z,(375+$x)/$z,(34+$y)/$z,(376+$x)/$z,(40+$y)/$z,(379+$x)/$z,(40+$y)/$z,(380+$x)/$z,(37+$y)/$z,(381+$x)/$z,(33+$y)/$z),'FD');
			//mila
			$this->color($data['43']);$this->Polygon(array((389+$x)/$z,(34+$y)/$z,(392+$x)/$z,(33+$y)/$z,(395+$x)/$z,(32+$y)/$z,(400+$x)/$z,(32+$y)/$z,(404+$x)/$z,(32+$y)/$z,(407+$x)/$z,(33+$y)/$z,(405+$x)/$z,(35+$y)/$z,(405+$x)/$z,(39+$y)/$z,(408+$x)/$z,(42+$y)/$z,(409+$x)/$z,(45+$y)/$z,(406+$x)/$z,(47+$y)/$z,(405+$x)/$z,(50+$y)/$z,(402+$x)/$z,(52+$y)/$z,(400+$x)/$z,(55+$y)/$z,(395+$x)/$z,(56+$y)/$z,(396+$x)/$z,(53+$y)/$z,(396+$x)/$z,(48+$y)/$z,(393+$x)/$z,(47+$y)/$z,(395+$x)/$z,(44+$y)/$z,(393+$x)/$z,(41+$y)/$z,(390+$x)/$z,(38+$y)/$z,(389+$x)/$z,(34+$y)/$z),'FD');
			//jijel
			$this->color($data['18']);$this->Polygon(array((381+$x)/$z,(31+$y)/$z,(384+$x)/$z,(29+$y)/$z,(386+$x)/$z,(27+$y)/$z,(388+$x)/$z,(25+$y)/$z,(392+$x)/$z,(25+$y)/$z,(395+$x)/$z,(25+$y)/$z,(398+$x)/$z,(23+$y)/$z,(401+$x)/$z,(22+$y)/$z,(404+$x)/$z,(24+$y)/$z,(405+$x)/$z,(25+$y)/$z,(406+$x)/$z,(30+$y)/$z,(404+$x)/$z,(32+$y)/$z,(400+$x)/$z,(32+$y)/$z,(395+$x)/$z,(32+$y)/$z,(392+$x)/$z,(33+$y)/$z,(389+$x)/$z,(34+$y)/$z,(384+$x)/$z,(34+$y)/$z,(381+$x)/$z,(33+$y)/$z,(381+$x)/$z,(31+$y)/$z),'FD');
			//oumelbaouaki
			$this->color($data['4']);$this->Polygon(array((409+$x)/$z,(45+$y)/$z,(411+$x)/$z,(47+$y)/$z,(414+$x)/$z,(46+$y)/$z,(417+$x)/$z,(46+$y)/$z,(421+$x)/$z,(46+$y)/$z,(423+$x)/$z,(49+$y)/$z,(427+$x)/$z,(48+$y)/$z,(428+$x)/$z,(47+$y)/$z,(431+$x)/$z,(50+$y)/$z,(434+$x)/$z,(52+$y)/$z,(437+$x)/$z,(55+$y)/$z,(443+$x)/$z,(56+$y)/$z,(442+$x)/$z,(62+$y)/$z,(438+$x)/$z,(64+$y)/$z,(435+$x)/$z,(65+$y)/$z,(432+$x)/$z,(63+$y)/$z,(429+$x)/$z,(62+$y)/$z,(426+$x)/$z,(62+$y)/$z,(423+$x)/$z,(63+$y)/$z,(419+$x)/$z,(62+$y)/$z,(416+$x)/$z,(63+$y)/$z,(416+$x)/$z,(60+$y)/$z,(413+$x)/$z,(57+$y)/$z,(410+$x)/$z,(57+$y)/$z,(407+$x)/$z,(56+$y)/$z,(404+$x)/$z,(56+$y)/$z,(400+$x)/$z,(55+$y)/$z,(402+$x)/$z,(52+$y)/$z,(405+$x)/$z,(50+$y)/$z,(406+$x)/$z,(47+$y)/$z,(409+$x)/$z,(45+$y)/$z),'FD');
			//constantine
			$this->color($data['25']);$this->Polygon(array((407+$x)/$z,(33+$y)/$z,(410+$x)/$z,(32+$y)/$z,(413+$x)/$z,(31+$y)/$z,(416+$x)/$z,(32+$y)/$z,(417+$x)/$z,(35+$y)/$z,(419+$x)/$z,(37+$y)/$z,(421+$x)/$z,(39+$y)/$z,(422+$x)/$z,(42+$y)/$z,(421+$x)/$z,(46+$y)/$z,(417+$x)/$z,(46+$y)/$z,(414+$x)/$z,(46+$y)/$z,(411+$x)/$z,(47+$y)/$z,(409+$x)/$z,(45+$y)/$z,(408+$x)/$z,(42+$y)/$z,(405+$x)/$z,(39+$y)/$z,(405+$x)/$z,(35+$y)/$z,(407+$x)/$z,(33+$y)/$z),'FD');
			//skikda
			$this->color($data['21']);$this->Polygon(array((401+$x)/$z,(22+$y)/$z,(402+$x)/$z,(19+$y)/$z,(405+$x)/$z,(17+$y)/$z,(408+$x)/$z,(17+$y)/$z,(410+$x)/$z,(19+$y)/$z,(413+$x)/$z,(20+$y)/$z,(415+$x)/$z,(22+$y)/$z,(418+$x)/$z,(22+$y)/$z,(421+$x)/$z,(22+$y)/$z,(425+$x)/$z,(21+$y)/$z,(428+$x)/$z,(19+$y)/$z,(429+$x)/$z,(22+$y)/$z,(430+$x)/$z,(26+$y)/$z,(430+$x)/$z,(29+$y)/$z,(427+$x)/$z,(31+$y)/$z,(424+$x)/$z,(31+$y)/$z,(422+$x)/$z,(34+$y)/$z,(419+$x)/$z,(37+$y)/$z,(417+$x)/$z,(35+$y)/$z,(416+$x)/$z,(32+$y)/$z,(413+$x)/$z,(31+$y)/$z,(410+$x)/$z,(32+$y)/$z,(407+$x)/$z,(33+$y)/$z,(404+$x)/$z,(32+$y)/$z,(406+$x)/$z,(30+$y)/$z,(405+$x)/$z,(27+$y)/$z,(404+$x)/$z,(24+$y)/$z,(401+$x)/$z,(22+$y)/$z),'FD');
			//annaba
			$this->color($data['23']);$this->Polygon(array((428+$x)/$z,(19+$y)/$z,(430+$x)/$z,(17+$y)/$z,(433+$x)/$z,(17+$y)/$z,(437+$x)/$z,(18+$y)/$z,(438+$x)/$z,(22+$y)/$z,(439+$x)/$z,(24+$y)/$z,(437+$x)/$z,(27+$y)/$z,(437+$x)/$z,(30+$y)/$z,(434+$x)/$z,(30+$y)/$z,(430+$x)/$z,(29+$y)/$z,(430+$x)/$z,(26+$y)/$z,(429+$x)/$z,(22+$y)/$z,(428+$x)/$z,(19+$y)/$z),'FD');
			//guelma
			$this->color($data['24']);$this->Polygon(array((437+$x)/$z,(30+$y)/$z,(439+$x)/$z,(31+$y)/$z,(443+$x)/$z,(31+$y)/$z,(444+$x)/$z,(33+$y)/$z,(441+$x)/$z,(37+$y)/$z,(438+$x)/$z,(40+$y)/$z,(435+$x)/$z,(42+$y)/$z,(431+$x)/$z,(42+$y)/$z,(428+$x)/$z,(43+$y)/$z,(428+$x)/$z,(47+$y)/$z,(427+$x)/$z,(48+$y)/$z,(423+$x)/$z,(49+$y)/$z,(421+$x)/$z,(46+$y)/$z,(422+$x)/$z,(42+$y)/$z,(421+$x)/$z,(39+$y)/$z,(419+$x)/$z,(37+$y)/$z,(422+$x)/$z,(34+$y)/$z,(424+$x)/$z,(31+$y)/$z,(427+$x)/$z,(31+$y)/$z,(430+$x)/$z,(29+$y)/$z,(434+$x)/$z,(30+$y)/$z, (437+$x)/$z,(30+$y)/$z),'FD');
			//taref
			$this->color($data['36']);$this->Polygon(array((439+$x)/$z,(24+$y)/$z,(442+$x)/$z,(23+$y)/$z,(445+$x)/$z,(22+$y)/$z,(448+$x)/$z,(20+$y)/$z,(451+$x)/$z,(19+$y)/$z,(454+$x)/$z,(20+$y)/$z,(457+$x)/$z,(20+$y)/$z,(460+$x)/$z,(19+$y)/$z,(460+$x)/$z,(22+$y)/$z,(460+$x)/$z,(24+$y)/$z,(456+$x)/$z,(24+$y)/$z,(456+$x)/$z,(29+$y)/$z,(453+$x)/$z,(31+$y)/$z,(450+$x)/$z,(33+$y)/$z,(447+$x)/$z,(35+$y)/$z,(444+$x)/$z,(33+$y)/$z,(443+$x)/$z,(31+$y)/$z,(439+$x)/$z,(31+$y)/$z,(437+$x)/$z,(30+$y)/$z,(437+$x)/$z,(27+$y)/$z,(439+$x)/$z,(24+$y)/$z),'FD');
			//soukahras
			$this->color($data['41']);$this->Polygon(array((450+$x)/$z,(33+$y)/$z,(453+$x)/$z,(34+$y)/$z,(455+$x)/$z,(36+$y)/$z,(455+$x)/$z,(39+$y)/$z,(454+$x)/$z,(44+$y)/$z,(453+$x)/$z,(48+$y)/$z,(450+$x)/$z,(48+$y)/$z,(446+$x)/$z,(50+$y)/$z,(442+$x)/$z,(52+$y)/$z,(443+$x)/$z,(56+$y)/$z,(437+$x)/$z,(55+$y)/$z,(434+$x)/$z,(52+$y)/$z,(431+$x)/$z,(50+$y)/$z,(428+$x)/$z,(47+$y)/$z,(428+$x)/$z,(43+$y)/$z,(431+$x)/$z,(42+$y)/$z,(435+$x)/$z,(42+$y)/$z,(438+$x)/$z,(40+$y)/$z,(441+$x)/$z,(37+$y)/$z,(444+$x)/$z,(33+$y)/$z, (447+$x)/$z,(35+$y)/$z,(450+$x)/$z,(33+$y)/$z),'FD');		
	}			
	$this->RoundedRect($x-10,155,30,55, 2, $style = '');
	$this->color(0);    $this->SetXY($x-10,150);$this->cell(30,5,$data['titre'],0,0,'C',0,0);
	$this->color(0);    $this->SetXY($x-5,$this->GetY()+10);$this->cell(5,5,'',1,0,'C',1,0);$this->cell(15,5,$data['A'],0,0,'L',0,0);
	$this->color(1);    $this->SetXY($x-5,$this->GetY()+10);$this->cell(5,5,'',1,0,'C',1,0);$this->cell(15,5,$data['B'],0,0,'L',0,0);
	$this->color(11);   $this->SetXY($x-5,$this->GetY()+10);$this->cell(5,5,'',1,0,'C',1,0);$this->cell(15,5,$data['C'],0,0,'L',0,0);
	$this->color(101);  $this->SetXY($x-5,$this->GetY()+10);$this->cell(5,5,'',1,0,'C',1,0);$this->cell(15,5,$data['D'],0,0,'L',0,0);
	$this->color(1001); $this->SetXY($x-5,$this->GetY()+10);$this->cell(5,5,'',1,0,'C',1,0);$this->cell(15,5,$data['E'],0,0,'L',0,0);
	$this->color(0);    $this->SetXY($x-10,$this->GetY()+15);$this->cell(40,5,'00km_____45km_____90km',0,0,'L',0,0);
	$this->color(0);    $this->SetXY($x-10,$this->GetY()+5);$this->cell(40,5,'Source : Dr TIBA Redha  EPH Ain-oussera ',0,0,'L',0,0);
	$this->color(0);
	$this->SetFont('Times', 'BIU', 8);
	$this->SetDrawColor(255,0,0);
	$this->SetXY(150,35);$this->cell(65,4,'Algerie',0,0,'C',0,0);
	$this->SetFont('Times', 'B', 7.5);
	$yy=170;
	$this->SetXY($yy,$this->GetY()+4);$this->cell(55,4,'1-Adrar',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'2-Chlef',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'3-Laghouat',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'4-Oum el bouaghi',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'5-Batna',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'6-Bejaia',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'7-Biskra',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'8-Bechar',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'9-Blida',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'10-Bouira',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'11-Tamanrasset',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'12-Tebessa',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'13-Tlemcen',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'14-Tiaret',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'15-Tizi ouzou',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'16-Alger',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'17-Djelfa',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'18-Jijel',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'19-Setif',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'20-Saida',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'21-Skikda',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'22-Sidi bel abbes',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'23-Annaba',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'24-Guelma',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'25-Constantine',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'26-Medea',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'27-Mostaganem',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'28-M sila',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'29-Mascara',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'30-Ouargla',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'31-Oran',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'32-El bayadh',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'33-Illizi',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'34-Bordj bou arreridj',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'35-Boumerdes',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'36-El taref',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'37-Tindouf',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'38-Tissemsilt',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'39-El oued',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'40-Khenchela',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'41-Souk ahras',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'42-Tipaza',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'43-Mila',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'44-Ain defla',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'45-Naama',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'46-Ain temouchent',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'47-Ghardaia',0,0,'L',0,0);$this->SetXY($yy,$this->GetY()+4);$this->cell(65,4,'48-Relizane',0,0,'L',0,0);											
	$this->SetDrawColor(0,0,0);
	$this->SetFont('Times', 'B', 5.5);
	$this->SetXY(65,119);$this->cell(55,5,'1',0,0,'L',0,0);$this->SetXY(78,44);$this->cell(55,5,'2',0,0,'L',0,0);$this->SetXY(90.5,68);$this->cell(55,5,'3',0,0,'L',0,0);$this->SetXY(117,47);$this->cell(55,5,'4',0,0,'L',0,0);$this->SetXY(110,50);$this->cell(55,5,'5',0,0,'L',0,0);$this->SetXY(103,40);$this->cell(55,5,'6',0,0,'L',0,0);$this->SetXY(108,56);$this->cell(55,5,'7',0,0,'L',0,0);$this->SetXY(55,90);$this->cell(55,5,'8',0,0,'L',0,0);	$this->SetXY(90,42.5);$this->cell(55,5,'9',0,0,'L',0,0);$this->SetXY(96,45);$this->cell(55,5,'10',0,0,'L',0,0);	$this->SetXY(95,129);$this->cell(55,5,'11',0,0,'L',0,0);$this->SetXY(123,52);$this->cell(55,5,'12',0,0,'L',0,0);$this->SetXY(60,57);$this->cell(55,5,'13',0,0,'L',0,0);$this->SetXY(80.5,55);$this->cell(55,5,'14',0,0,'L',0,0);	$this->SetXY(99,40);$this->cell(55,5,'15',0,0,'L',0,0);$this->SetXY(90,40);$this->cell(55,5,'16',0,0,'L',0,0);$this->SetXY(90.5,55);$this->cell(55,5,'17',0,0,'L',0,0);	$this->SetXY(109,40);$this->cell(55,5,'18',0,0,'L',0,0);$this->SetXY(107,45);$this->cell(55,5,'19',0,0,'L',0,0);$this->SetXY(71,57);$this->cell(55,5,'20',0,0,'L',0,0);$this->SetXY(116,39.5);$this->cell(55,5,'21',0,0,'L',0,0);$this->SetXY(66,57);$this->cell(55,5,'22',0,0,'L',0,0);$this->SetXY(120.5,38.5);$this->cell(55,5,'23',0,0,'L',0,0);$this->SetXY(119.5,42);$this->cell(55,5,'24',0,0,'L',0,0);$this->SetXY(115,43);$this->cell(55,5,'25',0,0,'L',0,0);$this->SetXY(90,45);$this->cell(55,5,'26',0,0,'L',0,0);$this->SetXY(72,47);$this->cell(55,5,'27',0,0,'L',0,0);$this->SetXY(98,52);$this->cell(55,5,'28',0,0,'L',0,0);	$this->SetXY(71,52);$this->cell(55,5,'29',0,0,'L',0,0);$this->SetXY(110,90);$this->cell(55,5,'30',0,0,'L',0,0);$this->SetXY(66,49);$this->cell(55,5,'31',0,0,'L',0,0);$this->SetXY(75.5,68);$this->cell(55,5,'32',0,0,'L',0,0);$this->SetXY(125,119);$this->cell(55,5,'33',0,0,'L',0,0);$this->SetXY(102,45);$this->cell(55,5,'34',0,0,'L',0,0);	$this->SetXY(94,40);$this->cell(55,5,'35',0,0,'L',0,0);$this->SetXY(124,39.5);$this->cell(55,5,'36',0,0,'L',0,0);$this->SetXY(25,112);$this->cell(55,5,'37',0,0,'L',0,0);$this->SetXY(81.5,48.5);$this->cell(55,5,'38',0,0,'L',0,0);	$this->SetXY(118.5,68);$this->cell(55,5,'39',0,0,'L',0,0);$this->SetXY(117,52);$this->cell(55,5,'40',0,0,'L',0,0);$this->SetXY(122,44.5);$this->cell(55,5,'41',0,0,'L',0,0);$this->SetXY(84,42);$this->cell(55,5,'42',0,0,'L',0,0);$this->SetXY(111,43);$this->cell(55,5,'43',0,0,'L',0,0);$this->SetXY(84,45);$this->cell(55,5,'44',0,0,'L',0,0);$this->SetXY(65,68);$this->cell(55,5,'45',0,0,'L',0,0);$this->SetXY(63,51.5);$this->cell(55,5,'46',0,0,'L',0,0);$this->SetXY(90,90);$this->cell(55,5,'47',0,0,'L',0,0);		$this->SetXY(75,48);$this->cell(55,5,'48',0,0,'L',0,0);	
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
	
	
	
	function tblparcim1($titre,$datejour1,$datejour2,$EPH1) 
	{    
		$this->SetFont('Times', 'B', 10);
		$this->SetXY(5,25);$this->cell(200,5,$titre,1,0,'C',1,0);
		$this->SetXY(5,35);
		$this->cell(10,5,"Code",1,0,'C',1,0);
		$this->cell(165,5,"Chapitre",1,0,'C',1,0);
	    $this->cell(10,5,"Nbr",1,0,'C',1,0);
		$this->cell(15,5,"TXM",1,0,'C',1,0);
		$this->SetXY(5,40);
		$IDWIL=17000;
		$ANNEE='2016';
		$this->mysqlconnect();
		$req="SELECT * FROM deceshosp where STRUCTURED $EPH1 and  DINS BETWEEN '$datejour1' AND '$datejour2' ";
		$query1 = mysql_query($req);   
		$totalmbr11=mysql_num_rows($query1);
		
		$query="SELECT CODECIM0,count(CODECIM0)as nbr FROM deceshosp where STRUCTURED $EPH1 and  DINS BETWEEN '$datejour1' AND '$datejour2' GROUP BY CODECIM0  order by nbr desc "; //    % %will search form 0-9,a-z            
		$resultat=mysql_query($query);
		$totalmbr1=mysql_num_rows($resultat);
		while($row=mysql_fetch_object($resultat))
		{
			$this->SetFont('Times', '', 10);
			$this->cell(10,4,trim($this->nbrtostring('deces','chapitre','IDCHAP',$row->CODECIM0,'IDCHAP')),1,0,'C',0);
			$this->cell(165,4,html_entity_decode(utf8_decode($this->nbrtostring('deces','chapitre','IDCHAP',$row->CODECIM0,'CHAP'))) ,1,0,'L',0);
			$this->cell(10,4,trim($row->nbr),1,0,'C',0);
			$this->cell(15,4,round(($row->nbr*100)/$totalmbr11,2).' %',1,0,'C',0);
			$this->SetXY(5,$this->GetY()+4); 
		}
		$this->SetXY(5,$this->GetY());$this->cell(10,5,"Total",1,0,'C',1,0);	  
		$this->cell(165,5,$totalmbr1." : Chapitres",1,0,'C',1,0);	  
		$this->cell(10,5,$totalmbr11,1,0,'C',1,0);	  
		$this->cell(15,5,'100%',1,0,'C',1,0);  
	}
	function tblparcim2($titre,$datejour1,$datejour2,$EPH1) 
	{    
		$this->SetFont('Times', 'B', 10);
		$this->SetXY(5,25);$this->cell(200,5,$titre,1,0,'C',1,0);
		$this->SetXY(5,35);
		$this->cell(10,5,"Code",1,0,'C',1,0);
		$this->cell(165,5,"Categorie",1,0,'C',1,0);
	    $this->cell(10,5,"Nbr",1,0,'C',1,0);
		$this->cell(15,5,"TXM",1,0,'C',1,0);
		$this->SetXY(5,40);
		$IDWIL=17000;
		$ANNEE='2016';
		$this->mysqlconnect();
		$req="SELECT * FROM deceshosp where STRUCTURED $EPH1 and  DINS BETWEEN '$datejour1' AND '$datejour2' ";
		$query1 = mysql_query($req);   
		$totalmbr11=mysql_num_rows($query1);
		$query="SELECT CODECIM,count(CODECIM)as nbr FROM deceshosp where STRUCTURED $EPH1 and  DINS BETWEEN '$datejour1' AND '$datejour2' GROUP BY CODECIM  order by nbr desc "; //    % %will search form 0-9,a-z            
		$resultat=mysql_query($query);
		$totalmbr1=mysql_num_rows($resultat);
		while($row=mysql_fetch_object($resultat))
		{
			$this->SetFont('Times', '', 10);
			$this->cell(10,4,trim($this->nbrtostringcim('deces','cim','row_id',$row->CODECIM,'diag_cod')),1,0,'C',0);
			$this->cell(165,4,html_entity_decode(utf8_decode($this->nbrtostringcim('deces','cim','row_id',$row->CODECIM,'diag_nom'))) ,1,0,'L',0);
			$this->cell(10,4,trim($row->nbr),1,0,'C',0);
			$this->cell(15,4,round(($row->nbr*100)/$totalmbr11,2).' %',1,0,'C',0);
			$this->SetXY(5,$this->GetY()+4); 
		}
		$this->SetXY(5,$this->GetY());$this->cell(10,5,"Total",1,0,'C',1,0);	  
		$this->cell(165,5,$totalmbr1." : Categorie",1,0,'C',1,0);	  
		$this->cell(10,5,$totalmbr11,1,0,'C',1,0);	  
		$this->cell(15,5,'100%',1,0,'C',1,0);  
	}
	
	function nbrtostringcim($db_name,$tb_name,$colonename,$colonevalue,$resultatstring) 
	{
	if (is_numeric($colonevalue) and $colonevalue!=='0') 
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
	return $resultat2='-------';
	}
	
	function nbrservicedinf($nbrservice,$datejour1,$datejour2,$eph)
	{
	$this->mysqlconnect();
	$sql = " select * from deceshosp where DUREEHOSPIT <= $nbrservice  and STRUCTURED $eph and (DINS BETWEEN '$datejour1' AND '$datejour2')";
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$collecte=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $collecte;
	}
	function nbrserviced($nbrservice,$datejour1,$datejour2,$eph)
	{
	$this->mysqlconnect();
	$sql = " select * from deceshosp where DUREEHOSPIT=$nbrservice  and STRUCTURED $eph and (DINS BETWEEN '$datejour1' AND '$datejour2')";
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$collecte=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $collecte;
	}
	function nbrservicedsup($nbrservice,$datejour1,$datejour2,$eph)
	{
	$this->mysqlconnect();
	$sql = " select * from deceshosp where DUREEHOSPIT >= $nbrservice  and STRUCTURED $eph and (DINS BETWEEN '$datejour1' AND '$datejour2')";
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$collecte=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $collecte;
	}
	
	function nbrservicedsexe($sexe,$datejour1,$datejour2,$eph)
	{
	$this->mysqlconnect();
	$sql = " select * from deceshosp where SEX = '$sexe'  and STRUCTURED $eph and (DINS BETWEEN '$datejour1' AND '$datejour2')";
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$collecte=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $collecte;
	}
	
	
	function dureehospitalisation1($titre,$datejour1,$datejour2,$valeurs,$eph) 
	   { 
		$TA1=$this->nbrservicedinf(0,$datejour1,$datejour2,$eph);	
		$TA2=$this->nbrserviced(1,$datejour1,$datejour2,$eph);	
		$TA3=$this->nbrserviced(2,$datejour1,$datejour2,$eph);	
		$TA4=$this->nbrserviced(3,$datejour1,$datejour2,$eph);	
		$TA5=$this->nbrserviced(4,$datejour1,$datejour2,$eph);	
		$TA6=$this->nbrserviced(5,$datejour1,$datejour2,$eph);	
		$TA7=$this->nbrserviced(6,$datejour1,$datejour2,$eph);	
		$TA8=$this->nbrserviced(7,$datejour1,$datejour2,$eph);	
		$TA9=$this->nbrserviced(8,$datejour1,$datejour2,$eph);	
		$TA10=$this->nbrserviced(9,$datejour1,$datejour2,$eph);	
		$TA11=$this->nbrserviced(10,$datejour1,$datejour2,$eph);	
		$TA12=$this->nbrserviced(11,$datejour1,$datejour2,$eph);	
		$TA13=$this->nbrserviced(12,$datejour1,$datejour2,$eph);	
		$TA14=$this->nbrserviced(13,$datejour1,$datejour2,$eph);	
		$TA15=$this->nbrserviced(14,$datejour1,$datejour2,$eph);	
		$TA16=$this->nbrserviced(15,$datejour1,$datejour2,$eph);	
		$TA17=$this->nbrserviced(16,$datejour1,$datejour2,$eph);	
		$TA18=$this->nbrserviced(17,$datejour1,$datejour2,$eph);	
		$TA19=$this->nbrserviced(18,$datejour1,$datejour2,$eph);	
		$TA20=$this->nbrservicedsup(19,$datejour1,$datejour2,$eph);	
		$TA21x=$TA1+$TA2+$TA3+$TA4+$TA5+$TA6+$TA7+$TA8+$TA9+$TA10+$TA11+$TA12+$TA13+$TA14+$TA15+$TA16+$TA17+$TA18+$TA19+$TA20;
		if ($TA21x>0) {
		$TA21=$TA1+$TA2+$TA3+$TA4+$TA5+$TA6+$TA7+$TA8+$TA9+$TA10+$TA11+$TA12+$TA13+$TA14+$TA15+$TA16+$TA17+$TA18+$TA19+$TA20;
		}else{
		$TA21=1;
		} 
		$datamf = array($TA1,$TA2,$TA3,$TA4,$TA5,$TA6,$TA7,$TA8,$TA9,$TA10,$TA11,$TA12,$TA13,$TA14,$TA15,$TA16,$TA17,$TA18,$TA19,$TA20);
		$this->SetXY(5,25+10);$this->cell(285,5,html_entity_decode(utf8_decode("Cette étude a porté sur ".$TA21." décès survenus durant la periode du ".$this->dateUS2FR($datejour1)." au ".$this->dateUS2FR($datejour2)." au niveau de 36 communes ")),0,0,'L',0);
		$this->SetFont('Times', 'B', 10);
		$this->SetXY(5,25);$this->cell(200,5,$titre,1,0,'C',1,0);
		$this->SetXY(5,35+7);$this->cell(105,5,html_entity_decode(utf8_decode("Repartition des deces par Durée D'hospitalisation ")),1,0,'L',1,0);
		$this->SetXY(5,35+7+5);$this->cell(60,5,"Nombre de jours",1,0,'C',1,0);                    $this->cell(20,5,"Nbr Deces",1,0,'C',1,0);$this->cell(25,5,"Tx %",1,0,'C',1,0);
		$this->SetXY(5,35+7+10);$this->cell(60,5,"0",1,0,'C',0);        $this->cell(20,5,$TA1,1,0,'C',0);         $this->cell(25,5,round(($TA1*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+15);$this->cell(60,5,"1",1,0,'C',0);        $this->cell(20,5,$TA2,1,0,'C',0);         $this->cell(25,5,round(($TA2*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+20);$this->cell(60,5,"2",1,0,'C',0);        $this->cell(20,5,$TA3,1,0,'C',0);         $this->cell(25,5,round(($TA3*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+25);$this->cell(60,5,"3",1,0,'C',0);        $this->cell(20,5,$TA4,1,0,'C',0);         $this->cell(25,5,round(($TA4*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+30);$this->cell(60,5,"4",1,0,'C',0);        $this->cell(20,5,$TA5,1,0,'C',0);         $this->cell(25,5,round(($TA5*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+35);$this->cell(60,5,"5",1,0,'C',0);        $this->cell(20,5,$TA6,1,0,'C',0);         $this->cell(25,5,round(($TA6*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+40);$this->cell(60,5,"6",1,0,'C',0);        $this->cell(20,5,$TA7,1,0,'C',0);         $this->cell(25,5,round(($TA7*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+45);$this->cell(60,5,"7",1,0,'C',0);        $this->cell(20,5,$TA8,1,0,'C',0);         $this->cell(25,5,round(($TA8*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+50);$this->cell(60,5,"8",1,0,'C',0);        $this->cell(20,5,$TA9,1,0,'C',0);         $this->cell(25,5,round(($TA9*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+55);$this->cell(60,5,"9",1,0,'C',0);        $this->cell(20,5,$TA10,1,0,'C',0);         $this->cell(25,5,round(($TA10*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+60);$this->cell(60,5,"10",1,0,'C',0);       $this->cell(20,5,$TA11,1,0,'C',0);         $this->cell(25,5,round(($TA11*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+65);$this->cell(60,5,"11",1,0,'C',0);       $this->cell(20,5,$TA12,1,0,'C',0);         $this->cell(25,5,round(($TA12*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+70);$this->cell(60,5,"12",1,0,'C',0);       $this->cell(20,5,$TA13,1,0,'C',0);         $this->cell(25,5,round(($TA13*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+75);$this->cell(60,5,"13",1,0,'C',0);       $this->cell(20,5,$TA14,1,0,'C',0);         $this->cell(25,5,round(($TA14*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+80);$this->cell(60,5,"14",1,0,'C',0);       $this->cell(20,5,$TA15,1,0,'C',0);         $this->cell(25,5,round(($TA15*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+85);$this->cell(60,5,"15",1,0,'C',0);       $this->cell(20,5,$TA16,1,0,'C',0);         $this->cell(25,5,round(($TA16*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+90);$this->cell(60,5,"16",1,0,'C',0);       $this->cell(20,5,$TA17,1,0,'C',0);         $this->cell(25,5,round(($TA17*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+95);$this->cell(60,5,"17",1,0,'C',0);       $this->cell(20,5,$TA18,1,0,'C',0);         $this->cell(25,5,round(($TA18*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+100);$this->cell(60,5,"18",1,0,'C',0);      $this->cell(20,5,$TA19,1,0,'C',0);         $this->cell(25,5,round(($TA19*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+105);$this->cell(60,5,">=19",1,0,'C',0);     $this->cell(20,5,$TA20,1,0,'C',0);         $this->cell(25,5,round(($TA20*100)/$TA21,2),1,0,'C',0);
        $this->SetXY(5,35+7+110); $this->cell(60,5,"Total",1,0,'L',1,0);$this->cell(20,5,$TA21,1,0,'C',1,0);        $this->cell(25,5,round(($TA21*100)/$TA21,2),1,0,'C',1,0);						
		$mas=$this->nbrservicedsexe('M',$datejour1,$datejour2,$eph);
		$fem=$this->nbrservicedsexe('F',$datejour1,$datejour2,$eph);
		$pie2 = array(
		"x" => 135, 
		"y" => 200, 
		"r" => 17,
		"v1" =>$mas ,
		"v2" =>$fem ,
		"t0" => html_entity_decode(utf8_decode("Distribution des décès par sexe ")),
		"t1" => "M",
		"t2" => "F");
		$this->pie2($pie2);
		$this->SetXY(5,175+5);  $this->cell(285,5,html_entity_decode(utf8_decode("2-Répartition des décès par sexe : ")),0,0,'L',0);
	    $this->SetXY(5,175+10);$this->cell(285,5,html_entity_decode(utf8_decode("La répartition des ".$TA21." décès enregistrés montre que :")),0,0,'L',0);
	    $this->SetXY(5,175+15);$this->cell(285,5,html_entity_decode(utf8_decode(round(($mas/$TA21)*100,2)."% des décès touche les hommes. ")),0,0,'L',0);
	    $this->SetXY(5,175+20);$this->cell(285,5,html_entity_decode(utf8_decode(round(($fem/$TA21)*100,2)."% des décès touche les femmes. ")),0,0,'L',0);
	    if ($fem > 0){$fem=$fem;}else{$fem=1;}
	    $this->SetXY(5,175+25);$this->cell(285,5,html_entity_decode(utf8_decode("avec un sexe ratio de ".round(($mas/$fem),2))),0,0,'L',0);
		$this->SetXY(5,160);$this->cell(285,5,html_entity_decode(utf8_decode("1-Repartition des deces par Durée D'hospitalisation : ")),0,0,'L',0);
	    rsort($datamf);
	    $this->SetXY(5,165);$this->cell(285,5,html_entity_decode(utf8_decode("la proportion des décès la plus élevée est : ".round($datamf[0]*100/$TA21,2)."%")),0,0,'L',0);
	    sort($datamf);
	    $this->SetXY(5,170);$this->cell(285,5,html_entity_decode(utf8_decode("la proportion des décès la moins élevée est : ".round($datamf[0]*100/$TA21,2)."%")),0,0,'L',0);
		$this->barservice(135,150,$TA1,$TA2,$TA3,$TA4,$TA5,$TA6,$TA7,$TA8,$TA9,$TA10,$TA11,$TA12,$TA13,$TA14,$TA15,$TA16,$TA17,$TA18,$TA19,$TA20,$titre); 	
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
	$sql = " select * from deceshosp where SERVICEHOSPIT=$nbrservice  and STRUCTURED $eph and (DINS BETWEEN '$datejour1' AND '$datejour2')";
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$collecte=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $collecte;
	}
	function servicehospitalisation($titre,$datejour1,$datejour2,$valeurs,$eph) 
	{ 
		$TA1=$this->nbrservice(1,$datejour1,$datejour2,$eph);	
		$TA2=$this->nbrservice(2,$datejour1,$datejour2,$eph);	
		$TA3=$this->nbrservice(3,$datejour1,$datejour2,$eph);	
		$TA4=$this->nbrservice(4,$datejour1,$datejour2,$eph);	
		$TA5=$this->nbrservice(5,$datejour1,$datejour2,$eph);	
		$TA6=$this->nbrservice(6,$datejour1,$datejour2,$eph);	
		$TA7=$this->nbrservice(7,$datejour1,$datejour2,$eph);	
		$TA8=$this->nbrservice(8,$datejour1,$datejour2,$eph);	
		$TA9=$this->nbrservice(9,$datejour1,$datejour2,$eph);	
		$TA10=$this->nbrservice(10,$datejour1,$datejour2,$eph);	
		$TA11=$this->nbrservice(11,$datejour1,$datejour2,$eph);	
		$TA12=$this->nbrservice(12,$datejour1,$datejour2,$eph);	
		$TA13=$this->nbrservice(13,$datejour1,$datejour2,$eph);	
		$TA14=$this->nbrservice(14,$datejour1,$datejour2,$eph);	
		$TA15=$this->nbrservice(15,$datejour1,$datejour2,$eph);	
		$TA16=$this->nbrservice(16,$datejour1,$datejour2,$eph);	
		$TA17=$this->nbrservice(17,$datejour1,$datejour2,$eph);	
		$TA18=$this->nbrservice(18,$datejour1,$datejour2,$eph);	
		$TA19=$this->nbrservice(19,$datejour1,$datejour2,$eph);	
		$TA20=$this->nbrservice(20,$datejour1,$datejour2,$eph);	
		$TA21x=$TA1+$TA2+$TA3+$TA4+$TA5+$TA6+$TA7+$TA8+$TA9+$TA10+$TA11+$TA12+$TA13+$TA14+$TA15+$TA16+$TA17+$TA18+$TA19+$TA20;
		if ($TA21x>0) {
		$TA21=$TA1+$TA2+$TA3+$TA4+$TA5+$TA6+$TA7+$TA8+$TA9+$TA10+$TA11+$TA12+$TA13+$TA14+$TA15+$TA16+$TA17+$TA18+$TA19+$TA20;
		}else{
		$TA21=1;
		} 
		$datamf = array($TA1,$TA2,$TA3,$TA4,$TA5,$TA6,$TA7,$TA8,$TA9,$TA10,$TA11,$TA12,$TA13,$TA14,$TA15,$TA16,$TA17,$TA18,$TA19,$TA20);
		$this->SetXY(5,25+10);$this->cell(285,5,html_entity_decode(utf8_decode("Cette étude a porté sur ".$TA21." décès survenus durant la periode du ".$this->dateUS2FR($datejour1)." au ".$this->dateUS2FR($datejour2)." au niveau de 36 communes ")),0,0,'L',0);
		$this->SetFont('Times', 'B', 10);
		$this->SetXY(5,25);$this->cell(200,5,$titre,1,0,'C',1,0);
		$this->SetXY(5,35+7);$this->cell(105,5,"Repartition des deces par service ",1,0,'L',1,0);
		$this->SetXY(5,35+7+5);$this->cell(10,5,"Num",1,0,'C',1,0);    $this->cell(50,5,"Service",1,0,'L',1,0);                 $this->cell(20,5,"Nbr Deces",1,0,'C',1,0);$this->cell(25,5,"Tx %",1,0,'C',1,0);
		$this->SetXY(5,35+7+10);$this->cell(10,5,"1",1,0,'L',0);       $this->cell(50,5,"Cardiologie",1,0,'L',0);               $this->cell(20,5,$TA1,1,0,'C',0);         $this->cell(25,5,round(($TA1*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+15);$this->cell(10,5,"2",1,0,'L',0);        $this->cell(50,5,"Chirurgie generale",1,0,'L',0);        $this->cell(20,5,$TA2,1,0,'C',0);         $this->cell(25,5,round(($TA2*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+20);$this->cell(10,5,"3",1,0,'L',0);        $this->cell(50,5,"Chirurgie pediatrique",1,0,'L',0);     $this->cell(20,5,$TA3,1,0,'C',0);         $this->cell(25,5,round(($TA3*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+25);$this->cell(10,5,"4",1,0,'L',0);        $this->cell(50,5,"Gastrologie enterologie",1,0,'L',0);   $this->cell(20,5,$TA4,1,0,'C',0);         $this->cell(25,5,round(($TA4*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+30);$this->cell(10,5,"5",1,0,'L',0);        $this->cell(50,5,"Gyneco-obstetrique",1,0,'L',0);        $this->cell(20,5,$TA5,1,0,'C',0);         $this->cell(25,5,round(($TA5*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+35);$this->cell(10,5,"6",1,0,'L',0);        $this->cell(50,5,"Maladies infectieuses",1,0,'L',0);     $this->cell(20,5,$TA6,1,0,'C',0);         $this->cell(25,5,round(($TA6*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+40);$this->cell(10,5,"7",1,0,'L',0);        $this->cell(50,5,"Medecine interne",1,0,'L',0);          $this->cell(20,5,$TA7,1,0,'C',0);         $this->cell(25,5,round(($TA7*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+45);$this->cell(10,5,"8",1,0,'L',0);        $this->cell(50,5,"Nephrologie hemodialyse",1,0,'L',0);   $this->cell(20,5,$TA8,1,0,'C',0);         $this->cell(25,5,round(($TA8*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+50);$this->cell(10,5,"9",1,0,'L',0);        $this->cell(50,5,"Neurochirurgie",1,0,'L',0);            $this->cell(20,5,$TA9,1,0,'C',0);         $this->cell(25,5,round(($TA9*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+55);$this->cell(10,5,"10",1,0,'L',0);       $this->cell(50,5,"Neonatologie",1,0,'L',0);              $this->cell(20,5,$TA10,1,0,'C',0);         $this->cell(25,5,round(($TA10*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+60);$this->cell(10,5,"11",1,0,'L',0);       $this->cell(50,5,"Orthopedie traumatologie",1,0,'L',0);  $this->cell(20,5,$TA11,1,0,'C',0);         $this->cell(25,5,round(($TA11*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+65);$this->cell(10,5,"12",1,0,'L',0);       $this->cell(50,5,"Ophtalmologie",1,0,'L',0);             $this->cell(20,5,$TA12,1,0,'C',0);         $this->cell(25,5,round(($TA12*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+70);$this->cell(10,5,"13",1,0,'L',0);       $this->cell(50,5,"Oto-rhino-laryngologie",1,0,'L',0);    $this->cell(20,5,$TA13,1,0,'C',0);         $this->cell(25,5,round(($TA13*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+75);$this->cell(10,5,"14",1,0,'L',0);       $this->cell(50,5,"Oncologie medicale",1,0,'L',0);        $this->cell(20,5,$TA14,1,0,'C',0);         $this->cell(25,5,round(($TA14*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+80);$this->cell(10,5,"15",1,0,'L',0);       $this->cell(50,5,"Pediaitrie",1,0,'L',0);                $this->cell(20,5,$TA15,1,0,'C',0);         $this->cell(25,5,round(($TA15*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+85);$this->cell(10,5,"16",1,0,'L',0);       $this->cell(50,5,"Pneumo-phtisiologie",1,0,'L',0);       $this->cell(20,5,$TA16,1,0,'C',0);         $this->cell(25,5,round(($TA16*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+90);$this->cell(10,5,"17",1,0,'L',0);       $this->cell(50,5,"Psychiatrie",1,0,'L',0);               $this->cell(20,5,$TA17,1,0,'C',0);         $this->cell(25,5,round(($TA17*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+95);$this->cell(10,5,"18",1,0,'L',0);       $this->cell(50,5,"Reanimation",1,0,'L',0);               $this->cell(20,5,$TA18,1,0,'C',0);         $this->cell(25,5,round(($TA18*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+100);$this->cell(10,5,"19",1,0,'L',0);       $this->cell(50,5,"Urologie",1,0,'L',0);                  $this->cell(20,5,$TA19,1,0,'C',0);         $this->cell(25,5,round(($TA19*100)/$TA21,2),1,0,'C',0);
		$this->SetXY(5,35+7+105);$this->cell(10,5,"20",1,0,'L',0);      $this->cell(50,5,"Umc",1,0,'L',0);                       $this->cell(20,5,$TA20,1,0,'C',0);         $this->cell(25,5,round(($TA20*100)/$TA21,2),1,0,'C',0);
        $this->SetXY(5,35+7+110); $this->cell(60,5,"Total",1,0,'L',1,0);$this->cell(20,5,$TA21,1,0,'C',1,0);                     $this->cell(25,5,round(($TA21*100)/$TA21,2),1,0,'C',1,0);						
		$mas=$this->nbrservicedsexe('M',$datejour1,$datejour2,$eph);
		$fem=$this->nbrservicedsexe('F',$datejour1,$datejour2,$eph);
		$pie2 = array(
		"x" => 135, 
		"y" => 200, 
		"r" => 17,
		"v1" =>$mas ,
		"v2" =>$fem ,
		"t0" => html_entity_decode(utf8_decode("Distribution des décès par sexe ")),
		"t1" => "M",
		"t2" => "F");
		$this->pie2($pie2);
		$this->SetXY(5,160);$this->cell(285,5,html_entity_decode(utf8_decode("1-Repartition des deces par service : ")),0,0,'L',0);
	    rsort($datamf);
	    $this->SetXY(5,165);$this->cell(285,5,html_entity_decode(utf8_decode("la proportion des décès la plus élevée est : ".round($datamf[0]*100/$TA21,2)."%")),0,0,'L',0);
	    sort($datamf);
	    $this->SetXY(5,170);$this->cell(285,5,html_entity_decode(utf8_decode("la proportion des décès la moins élevée est : ".round($datamf[0]*100/$TA21,2)."%")),0,0,'L',0);
		$this->SetXY(5,175+5);  $this->cell(285,5,html_entity_decode(utf8_decode("2-Répartition des décès par sexe : ")),0,0,'L',0);
	    $this->SetXY(5,175+10);$this->cell(285,5,html_entity_decode(utf8_decode("La répartition des ".$TA21." décès enregistrés montre que :")),0,0,'L',0);
	    $this->SetXY(5,175+15);$this->cell(285,5,html_entity_decode(utf8_decode(round(($mas/$TA21)*100,2)."% des décès touche les hommes. ")),0,0,'L',0);
	    $this->SetXY(5,175+20);$this->cell(285,5,html_entity_decode(utf8_decode(round(($fem/$TA21)*100,2)."% des décès touche les femmes. ")),0,0,'L',0);
	    if ($fem > 0){$fem=$fem;}else{$fem=1;}
	    $this->SetXY(5,175+25);$this->cell(285,5,html_entity_decode(utf8_decode("avec un sexe ratio de ".round(($mas/$fem),2))),0,0,'L',0);
		$this->barservice(135,150,$TA1,$TA2,$TA3,$TA4,$TA5,$TA6,$TA7,$TA8,$TA9,$TA10,$TA11,$TA12,$TA13,$TA14,$TA15,$TA16,$TA17,$TA18,$TA19,$TA20,$titre); 	
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
	function barservice($x,$y,$a,$b,$c,$d,$e,$f,$g,$h,$i,$j,$k,$l,$m,$n,$o,$p,$q,$r,$s,$t,$titre)
    {
	$total1=$a+$b+$c+$d+$e+$f+$g+$h+$i+$j+$k+$l+$m+$n+$o+$p+$q+$r+$s+$t;
	
	if ($total1==0)  {$total=1;} else {$total=$total1;} 
	
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
	$mp=round($m*100/$total,2);
	$np=round($n*100/$total,2);
	$op=round($o*100/$total,2);
	$pp=round($p*100/$total,2);
	$qp=round($q*100/$total,2);
	$rp=round($r*100/$total,2);
	$sp=round($s*100/$total,2);
	$tp=round($t*100/$total,2);
	$this->SetFont('Times', 'BIU', 11);
	$this->SetXY($x-20,$y-108);$this->Cell(0, 5,$titre ,0, 2, 'L');
	$this->RoundedRect($x-20,$y-108, 90, 130, 2, $style = '');
	$this->SetFont('Times', 'B',08);$this->SetFillColor(120,255,120);
	$w=4.5;
	$this->SetXY($x-20,$y+10);   
	$this->cell($w,-$ap,'',1,0,'C',1,0);        
	$this->cell($w,-$bp,'',1,0,'C',1,0);
	$this->cell($w,-$cp,'',1,0,'C',1,0);
	$this->cell($w,-$dp,'',1,0,'C',1,0);
	$this->cell($w,-$ep,'',1,0,'C',1,0);
	$this->cell($w,-$fp,'',1,0,'C',1,0);
	$this->cell($w,-$gp,'',1,0,'C',1,0);
	$this->cell($w,-$hp,'',1,0,'C',1,0);
	$this->cell($w,-$ip,'',1,0,'C',1,0);
	$this->cell($w,-$jp,'',1,0,'C',1,0);
	$this->cell($w,-$kp,'',1,0,'C',1,0);        
	$this->cell($w,-$lp,'',1,0,'C',1,0);
	$this->cell($w,-$mp,'',1,0,'C',1,0);
	$this->cell($w,-$np,'',1,0,'C',1,0);
	$this->cell($w,-$op,'',1,0,'C',1,0);
	$this->cell($w,-$pp,'',1,0,'C',1,0);
	$this->cell($w,-$qp,'',1,0,'C',1,0);
	$this->cell($w,-$rp,'',1,0,'C',1,0);
	$this->cell($w,-$sp,'',1,0,'C',1,0);
	$this->cell($w,-$tp,'',1,0,'C',1,0);
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
	$this->cell($w,5,'10',1,0,'C',0,0);
	$this->cell($w,5,'11',1,0,'C',0,0);
	$this->cell($w,5,'12',1,0,'C',0,0);
	$this->cell($w,5,'13',1,0,'C',0,0);
	$this->cell($w,5,'14',1,0,'C',0,0);
	$this->cell($w,5,'15',1,0,'C',0,0);
	$this->cell($w,5,'16',1,0,'C',0,0);
	$this->cell($w,5,'17',1,0,'C',0,0);
	$this->cell($w,5,'18',1,0,'C',0,0);
	$this->cell($w,5,'19',1,0,'C',0,0);
	$this->cell($w,5,'20',1,0,'C',0,0);
	$this->SetFont('Times', 'B', 9);
	$this->SetXY(111,160-2.5);$this->cell(5,5,'00-',0,0,'C',0);
	$this->SetXY(111,150-2.5);$this->cell(5,5,'10-',0,0,'C',0);
	$this->SetXY(111,140-2.5);$this->cell(5,5,'20-',0,0,'C',0);
	$this->SetXY(111,130-2.5);$this->cell(5,5,'30-',0,0,'C',0);
	$this->SetXY(111,120-2.5);$this->cell(5,5,'40-',0,0,'C',0);
	$this->SetXY(111,110-2.5);$this->cell(5,5,'50-',0,0,'C',0);
	$this->SetXY(111,100-2.5);$this->cell(5,5,'60-',0,0,'C',0);
	$this->SetXY(111,90-2.5);$this->cell(5,5,'70-',0,0,'C',0);
	$this->SetXY(111,80-2.5);$this->cell(5,5,'80-',0,0,'C',0);
	$this->SetXY(111,70-2.5);$this->cell(5,5,'90-',0,0,'C',0);
	$this->SetXY(111,60-2.5);$this->cell(5,5,'100-',0,0,'C',0);
	$this->SetTextColor(255,0,0);
	$this->RotatedText($x-17.5,$y+10-$ap,'-'.$ap.'%',80);
	$this->RotatedText($x-17.5+5,$y+10-$bp,'-'.$bp.'%',80);
	$this->RotatedText($x-17.5+9,$y+10-$cp,'-'.$cp.'%',80);
	$this->RotatedText($x-17.5+14,$y+10-$dp,'-'.$dp.'%',80);
	$this->RotatedText($x-17.5+18.5,$y+10-$ep,'-'.$ep.'%',80);
	$this->RotatedText($x-17.5+23,$y+10-$fp,'-'.$fp.'%',80);
	$this->RotatedText($x-17.5+27,$y+10-$gp,'-'.$gp.'%',80);
	$this->RotatedText($x-17.5+32,$y+10-$hp,'-'.$hp.'%',80);
	$this->RotatedText($x-17.5+36.5,$y+10-$ip,'-'.$ip.'%',80);
	$this->RotatedText($x-17.5+41,$y+10-$jp,'-'.$jp.'%',80);
	$this->RotatedText($x-17.5+45.5,$y+10-$kp,'-'.$kp.'%',80);
	$this->RotatedText($x-17.5+49.5,$y+10-$lp,'-'.$lp.'%',80);
	$this->RotatedText($x-17.5+54,$y+10-$mp,'-'.$mp.'%',80);
	$this->RotatedText($x-17.5+59,$y+10-$np,'-'.$np.'%',80);
	$this->RotatedText($x-17.5+63,$y+10-$op,'-'.$op.'%',80);
	$this->RotatedText($x-17.5+68,$y+10-$pp,'-'.$pp.'%',80);
	$this->RotatedText($x-17.5+72.5,$y+10-$qp,'-'.$qp.'%',80);
	$this->RotatedText($x-17.5+77,$y+10-$rp,'-'.$rp.'%',80);
	$this->RotatedText($x-17.5+81.5,$y+10-$sp,'-'.$sp.'%',80);
	$this->RotatedText($x-17.5+85.5,$y+10-$tp,'-'.$tp.'%',80);
	$this->SetTextColor(0,0,0);
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