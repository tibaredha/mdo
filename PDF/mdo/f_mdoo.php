<?php
function dateFR2US($date)//01/01/2013
	{
	$J      = substr($date,0,2);
    $M      = substr($date,3,2);
    $A      = substr($date,6,4);
	$dateFR2US =  $A."-".$M."-".$J ;
    return $dateFR2US;//2013-01-01
	}
function dateUS2FR($date)//2013/01/01
	{
	$J      = substr($date,8,2);
    $M      = substr($date,5,2);
    $A      = substr($date,0,4);
	$dateUS2FR =  $J."-".$M."-".$A ;
    return $dateUS2FR;//01-01-2013
	}	
	
	
function mysqlconnect()
{
$nomprenom ="tibaredha";
$db_host="localhost";
$db_name="mdo"; 
$db_user="root";
$db_pass="";
$utf8 = "" ;
$cnx = mysql_connect($db_host,$db_user,$db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
$db  = mysql_select_db($db_name,$cnx) ;
mysql_query("SET NAMES 'UTF8' ");
return $db;
}
function nbrtostring($tb_name,$colonename,$colonevalue,$resultatstring) 
{
if (is_numeric($colonevalue) and $colonevalue!=='0') 
{ 
mysqlconnect();
$result = mysql_query("SELECT * FROM $tb_name where $colonename=$colonevalue" );
$row=mysql_fetch_object($result);
$resultat=$row->$resultatstring;
return $resultat;
} 
return $resultat2='??????';
}
require('../fpdf.php');
require('../fpdi.php');
$pdf = new FPDI();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->setSourceFile('F_MDO.pdf');
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx, 5, 5, 200);
mysqlconnect();
$query = "SELECT * FROM mdo1 where id='".$_GET["id"]."'  ";
$resultat=mysql_query($query);
while($row=mysql_fetch_object($resultat))
{
$pdf->SetFont('Arial','B',10);
$pdf->SetXY(68,5); $pdf->Write(0,'Republique Algerienne Democratique et Populaire');  
$pdf->SetXY(68,10);$pdf->Write(0,'Ministere De La Sante De La Population Et De La Reforme Hospitaliere');  
$pdf->SetXY(68,15);$pdf->Write(0,'Direction De La Sante De La Population De La Wilaya De Djelfa');  
$pdf->SetXY(68,20);$pdf->Write(0,'ETABLISSEMENT DE SANTE : '.nbrtostring('structure','id',$row->STRUCTURE,'structure'));  
$pdf->SetTextColor(255,0,0);
$pdf->SetFont('Arial','B',9);
$pdf->SetXY(28,83.5);$pdf->Write(0,TRIM($row->NOM));
$pdf->SetXY(43,89);$pdf->Write(0,substr(TRIM($row->NOM), 0,1));
switch($row->SEXE)  
		{
			case 'M':
				{
				$pdf->SetXY(72,89);$pdf->Write(0,'X');
				break;
				}
			case 'F':
				{
				$pdf->SetXY(94.5,89);$pdf->Write(0,'X');
				break;
				}
			
		}
$pdf->SetXY(146,89);$pdf->Write(0,$row->AGE);

$pdf->SetXY(32,95);$pdf->Write(0,'xxxxxxxxxxxxxx');

// $pdf->SetXY(95,75+13+35+8);$pdf->Write(0,$row->ADRESS.'_'.nbrtostring('com','IDCOM',$row->COMMUNE,'COMMUNE'));
// $pdf->SetXY(95,75+13+35+15+9);$pdf->Write(0,nbrtostring('WIL','IDWIL',$row->WILAYAN,'WILAYAS'));
}


 



// switch($row->LD)  
		// {
			// case 'DOM':
				// {
				// $pdf->SetXY(95,75+13+35+15+17+18+5);$pdf->Write(0,'X');
				// break;
				// }
			// case 'VP':
				// {
				// $pdf->SetXY(95,75+13+35+15+17+18+19);$pdf->Write(0,'X');
				// break;
				// }
			// case 'AAP':
				// {
				// $pdf->SetXY(95,75+13+35+15+17+18+19);$pdf->Write(0,'X');
				// break;
				// }
			// case 'SSP':
				// {
				// $pdf->SetXY(95,75+13+35+15+17+18+12);$pdf->Write(0,'X');
				// break;
				// }
			// case 'SSPV':
				// {
				// $pdf->SetXY(76,98.2);$pdf->Write(0,'X');
				// break;
				// }		
		// }

	
// $pdf->SetXY(95,75+26+35+23+24+18+26);$pdf->Write(0,nbrtostring('structure','id',$row->STRUCTURED,'structure'));

// $pdf->AddPage();
// $pdf->setSourceFile('deces/DM2013.pdf');
// $tplIdx = $pdf->importPage(11);
// $pdf->useTemplate($tplIdx, 5, 5, 200);
// $pdf->SetFont('Arial','B',10);
// $pdf->SetXY(22,10.5);$pdf->Write(0,'ETABLISSEMENT DE SANTE : '.nbrtostring('structure','id',$row->STRUCTURED,'structure'));
// $pdf->SetTextColor(255,0,0);
// $pdf->AddPage();
// $pdf->setSourceFile('deces/DM2013.pdf');
// $tplIdx = $pdf->importPage(12);
// $pdf->useTemplate($tplIdx, 5, 5, 200);
// $pdf->SetFont('Arial','B',10);
// $pdf->SetXY(22,10.5);$pdf->Write(0,'ETABLISSEMENT DE SANTE : '.nbrtostring('structure','id',$row->STRUCTURED,'structure'));
// $pdf->SetTextColor(255,0,0);
// $j = substr($row->DATENAISSANCE, 8, 2); 
// $m = substr($row->DATENAISSANCE, 5, 2); 
// $a = substr($row->DATENAISSANCE, 0, 4); 
// $pdf->SetXY(97+14,60);$pdf->Write(0,$j);
// $pdf->SetXY(95+15+14,60);$pdf->Write(0,$m);
// $pdf->SetXY(95+35+14,60);$pdf->Write(0,$a);
// $pdf->SetXY(106,67);$pdf->Write(0,$row->Years);
// $j = substr($row->DINS, 8, 2); 
// $m = substr($row->DINS, 5, 2); 
// $a = substr($row->DINS, 0, 4); 
// $pdf->SetXY(97+14,75);$pdf->Write(0,$j);
// $pdf->SetXY(95+15+14,75);$pdf->Write(0,$m);
// $pdf->SetXY(95+35+14,75);$pdf->Write(0,$a);
// $pdf->SetXY(106,82);$pdf->Write(0,$row->HINS);
// $pdf->SetXY(106,90);$pdf->Write(0,nbrtostring('WIL','IDWIL',$row->WILAYAR,'WILAYAS'));
// switch($_POST["NIP"])  
		// {
			// case 'A':
				// {
				// $pdf->SetXY(108,110);$pdf->Write(0,'X');
				// break;
				// }
			// case 'B':
				// {
				// $pdf->SetXY(108,116);$pdf->Write(0,'X');
				// break;
				// }
			// case 'C':
				// {
				// $pdf->SetXY(108,122);$pdf->Write(0,'X');
				// break;
				// }
			// case 'D':
				// {
				// $pdf->SetXY(108,128);$pdf->Write(0,'X');
				// break;
				// }
			// case 'E':
				// {
				// $pdf->SetXY(108,134);$pdf->Write(0,'X');
				// break;
				// }
            // case 'F':
				// {
				// $pdf->SetXY(108,140);$pdf->Write(0,'X');
				// break;
				// }	
			// case 'G':
				// {
				// $pdf->SetXY(108,146);$pdf->Write(0,'X');
				// break;
				// }		
		// }

// switch($_POST["NIC"])  
		// {
			// case 'A':
				// {
				// $pdf->SetXY(108,166);$pdf->Write(0,'X');
				// break;
				// }
			// case 'B':
				// {
				// $pdf->SetXY(108,172);$pdf->Write(0,'X');
				// break;
				// }
			// case 'C':
				// {
				// $pdf->SetXY(108,178);$pdf->Write(0,'X');
				// break;
				// }
			// case 'D':
				// {
				// $pdf->SetXY(108,184);$pdf->Write(0,'X');
				// break;
				// }
			// case 'E':
				// {
				// $pdf->SetXY(108,190);$pdf->Write(0,'X');
				// break;
				// }
            // case 'F':
				// {
				// $pdf->SetXY(108,196);$pdf->Write(0,'X');
				// break;
				// }	
			// case 'G':
				// {
				// $pdf->SetXY(108,202);$pdf->Write(0,'X');
				// break;
				// }		
		// }		
// switch($_POST["CS"])  
		// {
			// case 'A':
				// {
				// $pdf->SetXY(109,215);$pdf->Write(0,'X');
				// break;
				// }
			// case 'B':
				// {
				// $pdf->SetXY(109,221);$pdf->Write(0,'X');
				// break;
				// }
			// case 'C':
				// {
				// $pdf->SetXY(109,227);$pdf->Write(0,'X');
				// break;
				// }
		// }			

// $pdf->AddPage();
// $pdf->setSourceFile('deces/DM2013.pdf');
// $tplIdx = $pdf->importPage(13);
// $pdf->useTemplate($tplIdx, 5, 5, 200);
// $pdf->SetFont('Arial','B',10);
// $pdf->SetXY(22,10.5);$pdf->Write(0,'ETABLISSEMENT DE SANTE : '.nbrtostring('structure','id',$row->STRUCTURED,'structure'));
// $pdf->SetTextColor(255,0,0);
// switch($_POST["LD"])  
		// {
			// case 'A':
				// {
				// $pdf->SetXY(158,45);$pdf->Write(0,'X');
				// break;
				// }
			// case 'B':
				// {
				// $pdf->SetXY(158,45+6);$pdf->Write(0,'X');
				// break;
				// }
			// case 'C':
				// {
				// $pdf->SetXY(158,45+12);$pdf->Write(0,'X');
				// break;
				// }
			// case 'D':
				// {
				// $pdf->SetXY(158,45+18);$pdf->Write(0,'X');
				// break;
				// }
			// case 'E':
				// {
				// $pdf->SetXY(158,45+24);$pdf->Write(0,'X');
				// break;
				// }
            // case 'F':
				// {
				// $pdf->SetXY(158,45+30);$pdf->Write(0,'X');
				// break;
				// }	
			// case 'G':
				// {
				// $pdf->SetXY(158,45+36);$pdf->Write(0,'X');
				// break;
				// }
			// case 'H':
				// {
				// $pdf->SetXY(158,45+42);$pdf->Write(0,'X');
				// break;
				// }		
		// }
// switch($_POST["MD"])  
		// {
			// case 'A':
				// {
				// $pdf->SetXY(158,107);$pdf->Write(0,'X');
				// break;
				// }
			// case 'B':
				// {
				// $pdf->SetXY(158,118);$pdf->Write(0,'X');
				// break;
				// }
			// case 'C':
				// {
				// $pdf->SetXY(158,124);$pdf->Write(0,'X');
				// break;
				// }
			// case 'D':
				// {
				// $pdf->SetXY(158,130);$pdf->Write(0,'X');
				// break;
				// }
			// case 'E':
				// {
				// $pdf->SetXY(158,136);$pdf->Write(0,'X');
				// break;
				// }
            // case 'F':
				// {
				// $pdf->SetXY(158,142);$pdf->Write(0,'X');
				// break;
				// }	
			// case 'G':
				// {
				// $pdf->SetXY(158,148);$pdf->Write(0,'X');
				// break;
				// }
			// case 'H':
				// {
				// $pdf->SetXY(158,154);$pdf->Write(0,'X');
				// break;
				// }		
		// }
		
// for ($x = 14; $x <= 87; $x++) {
// $pdf->AddPage();
// $pdf->setSourceFile('deces/DM2013.pdf');
// $tplIdx = $pdf->importPage($x);
// $pdf->useTemplate($tplIdx, 5, 5, 200);
// $pdf->SetFont('Arial','B',10);
// $pdf->SetXY(22,10.5);$pdf->Write(0,'ETABLISSEMENT DE SANTE : '.nbrtostring('structure','id',$row->STRUCTURED,'structure'));   
// } 




$pdf->Output();
?>