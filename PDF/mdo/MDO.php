<?php
require('MDOO.php');



// if ($datejour1>=$datejour2)
// {
	// define('URL', 'http://'.$_SERVER['SERVER_NAME'].'/mdo/');
	// header("Location: ../eva/") ;
	// header("Location: ".URL."dashboard/Evaluation/0") ;
// }
// else
// {
	if ($_POST['Rapport']==1)//ANNEXE II 
	{
	    $pdf = new MDO( 'l', 'mm', 'A4' );
		$datejour1 = $pdf->dateFR2US($_POST['Datedebut']);
		$datejour2 = $pdf->dateFR2US($_POST['Datefin']);
		$structure = $_POST['structure'];
		$pdf->MDO2($structure,$datejour1,$datejour2);
	}

	if ($_POST['Rapport']==2)//ANNEXE III /C  
	{
	    $pdf = new MDO( 'l', 'mm', 'A4' );
		$datejour1 = $pdf->dateFR2US($_POST['Datedebut']);
		$datejour2 = $pdf->dateFR2US($_POST['Datefin']);
		$structure = $_POST['structure'];
		$pdf->MDO3('='.$structure,$datejour1,$datejour2);
	}

	if ($_POST['Rapport']==3)//ANNEXE III /S
	{
	    $pdf = new MDO( 'l', 'mm', 'A4' );
		$datejour1 = $pdf->dateFR2US($_POST['Datedebut']);
		$datejour2 = $pdf->dateFR2US($_POST['Datefin']);
		$structure = $_POST['structure'];
		$pdf->MDO3S('='.$structure,$datejour1,$datejour2);
	}

	if ($_POST['Rapport']==4)//Rapport 
	{
	    $pdf = new MDO( 'P', 'mm', 'A4' );
		$datejour1 = $pdf->dateFR2US($_POST['Datedebut']);
		$datejour2 = $pdf->dateFR2US($_POST['Datefin']);
		$structure = $_POST['structure'];
		$MDO=$_POST['MDO'];
		$pdf->MDOR('='.$structure,$MDO,$datejour1,$datejour2);
	}

// }


?>