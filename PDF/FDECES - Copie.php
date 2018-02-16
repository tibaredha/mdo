<?php
$t=$_GET['DATEDON']='2016-04-05';
$id=$_GET['id']=26;
require('DEC.php');
$pdf = new DEC( 'L', 'mm', 'A4' );
$pdf->AliasNbPages();//importatant pour metre en fonction  le totale nombre de page avec "/{nb}" 
$date=date("d-m-y");
$pdf->SetTitle('compagne de don ');
$pdf->SetFillColor(230);//fond gris il faut ajouter au cell un autre parametre pour qui accepte la coloration
$pdf->SetTextColor(0,0,0);//text noire
$pdf->SetFont('Times', 'B', 11);
$pdf->AliasNbPages();//prise encharge du nbr de page 
// $pdf->SetMargins(0,0,0);

$textColour = array( 0, 0, 0 );
$headerColour = array( 100, 100, 100 );
$tableHeaderTopTextColour = array( 255, 255, 255 );
$tableHeaderTopFillColour = array( 125, 152, 179 );
$tableHeaderTopProductTextColour = array( 0, 0, 0 );
$tableHeaderTopProductFillColour = array( 143, 173, 204 );
$tableHeaderLeftTextColour = array( 99, 42, 57 );
$tableHeaderLeftFillColour = array( 184, 207, 229 );
$tableBorderColour = array( 50, 50, 50 );
$tableRowFillColour = array( 213, 170, 170 );
$reportName0 = 'République Algérienne Démocratique et Populaire ';
$reportName1 = 'Ministère de la santé, de la population et de la réforme hospitalière';
$reportName2 = 'Direction De la Sante De La Population De La Wilaya De Djelfa ';
$reportName3 = 'Etablissement public hospitalier ain oussera ';
$reportName4 = "Rapport Mortalité Hospitalière";
$reportName5 = "Wilaya de Djelfa";
$reportName6 = "Année ".$t;


$reportNameYPos = 50;
$logoFile = "../public/IMAGES/photos/logoao.gif";
$logoXPos = 50;
$logoYPos = 108;
$logoWidth = 110;
$columnLabels = array( "Q1", "Q2", "Q3", "Q4" );
$rowLabels = array( "SupaWidget", "WonderWidget", "MegaWidget", "HyperWidget" );
$chartXPos = 20;
$chartYPos = 250;
$chartWidth = 160;
$chartHeight = 80;
$chartXLabel = "Product";
$chartYLabel = "2009 Sales";
$chartYStep = 20000;

$chartColours = array(
                  array( 255, 100, 100 ),
                  array( 100, 255, 100 ),
                  array( 100, 100, 255 ),
                  array( 255, 255, 100 ),
                );

$data = array(
          array( 9940, 10100, 9490, 11730 ),
          array( 19310, 21140, 20560, 22590 ),
          array( 25110, 26260, 25210, 28370 ),
          array( 27650, 24550, 30040, 31980 ),
        );

$pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );
$pdf->AddPage();
// $pdf->Image( "../public/IMAGES/photos/logoao.gif", 82,60,50 );
$pdf->SetFont( 'Arial', 'B', 12 );
$pdf->Ln(5);
$pdf->Cell( 0, 15, html_entity_decode(utf8_decode($reportName0)), 0, 0, 'C' );
$pdf->Ln(5);
$pdf->Cell( 0, 15, html_entity_decode(utf8_decode($reportName1)), 0, 0, 'C' );
$pdf->Ln(5);
$pdf->Cell( 0, 15, html_entity_decode(utf8_decode($reportName2)), 0, 0, 'C' );
$pdf->Ln(5);
$pdf->Cell( 0, 15, html_entity_decode(utf8_decode($reportName3)), 0, 0, 'C' );
$pdf->SetTextColor( 254,0 ,0  );
$pdf->SetFont( 'Arial', 'B', 24 );
$pdf->Ln(110);
$pdf->Cell( 0, 15, html_entity_decode(utf8_decode($reportName4)), 0, 0, 'C' );
$pdf->Ln(10);
$pdf->Cell( 0, 15, html_entity_decode(utf8_decode($reportName5)), 0, 0, 'C' );
$pdf->SetFont( 'Arial', '', 12 );
$pdf->SetTextColor( $headerColour[0], $headerColour[1], $headerColour[2] );
$pdf->Ln(20);
$pdf->Cell( 0, 15, html_entity_decode(utf8_decode($reportName6)), 0, 0, 'R' );
$date1=date('Y');
$date2=date('Y-m-d');
// $donparwilayar0=$pdf->donparwilayar('17000',$date1.'-01-01',$date2,'CIDT');
// $donparwilayar=$pdf->donparwilayar('17000',$date1.'-01-01',$date2,'IND');
// $donparwilayar1=$pdf->donparwilayar('17000',($date1-1).'-01-01',($date1-1).'-12-31','IND');
// $diff=$donparwilayar-$donparwilayar1;

$ANNEE='2016';
$data = array(
"A"    => '00-00',
"B"    => '01-20',
"C"    => '20-40',
"D"    => '40-60',
"E"    => '60-100',
"1"    => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",916,''),//daira  Djelfa
"2"    => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",924,'')+valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",925,''),//daira  ainoussera
"3"    => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",929,'')+valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",931,''),//daira  birine
"4"    => $pdf->donparcommune('17000',926)+$pdf->donparcommune('17000',927)+$pdf->donparcommune('17000',928),//daira  sidilaadjel
"5"    => $pdf->donparcommune('17000',932)+$pdf->donparcommune('17000',933)+$pdf->donparcommune('17000',934),//daira  hadsahari
"6"    => $pdf->donparcommune('17000',935)+$pdf->donparcommune('17000',939)+$pdf->donparcommune('17000',941)+$pdf->donparcommune('17000',940),//daira  hassibahbah
"7"    => $pdf->donparcommune('17000',942)+$pdf->donparcommune('17000',946)+$pdf->donparcommune('17000',947),//daira  darchioukhe
"8"    => $pdf->donparcommune('17000',920)+$pdf->donparcommune('17000',919)+$pdf->donparcommune('17000',923),//daira  charef
"9"    => $pdf->donparcommune('17000',917)+$pdf->donparcommune('17000',964)+$pdf->donparcommune('17000',963),//daira  idrissia
"10"   => $pdf->donparcommune('17000',965)+$pdf->donparcommune('17000',958)+$pdf->donparcommune('17000',962)+$pdf->donparcommune('17000',957),//daira  ain elbel
"11"   => $pdf->donparcommune('17000',948)+$pdf->donparcommune('17000',952)+$pdf->donparcommune('17000',954)+$pdf->donparcommune('17000',953)+$pdf->donparcommune('17000',951),//daira  messaad
"12"   => $pdf->donparcommune('17000',967)+$pdf->donparcommune('17000',968)+$pdf->donparcommune('17000',956),//daira  faid elbotma
"916"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",916,''),//daira  Djelfa
"917"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",917,''),// daira El Idrissia
"918"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",918,''),//Oum Cheggag
"919"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",919,''),//El Guedid
"920"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",920,''),//daira Charef
"921"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",921,''),//El Hammam
"922"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",922,''),//Touazi
"923"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",923,''),//Beni Yacoub
"924"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",924,''),//daira ainoussera
"925"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",925,''),//guernini
"926"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",926,''),//daira sidilaadjel
"927"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",927,''),//hassifdoul
"928"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",928,''),//elkhamis
"929"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",929,''),//daira birine
"930"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",930,''),//Dra Souary
"931"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",931,''),//benahar
"932"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",932,''),//daira hadshari
"933"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",933,''),//bouiratlahdab
"934"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",934,''),//ainfka
"935"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",935,''),//daira hassi bahbah
"936"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",936,''),//Mouilah
"937"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",937,''),//El Mesrane
"938"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",938,''),//Hassi el Mora
"939"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",939,''),//zaafrane
"940"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",940,''),//hassi el euche
"941"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",941,''),//ain maabed
"942"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",942,''),//daira darchioukh
"943"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",943,''),//Guendouza
"944"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",944,''),//El Oguila
"945"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",945,''),//El Merdja
"946"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",946,''),//mliliha
"947"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",947,''),//sidibayzid
"948"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",948,''),//daira Messad
"949"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",949,''),//Abdelmadjid
"950"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",950,''),//Haniet Ouled Salem
"951"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",951,''),//Guettara
"952"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",952,''),//Deldoul
"953"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",953,''),//Sed Rahal
"954"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",954,''),//Selmana
"955"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",955,''),//El Gahra
"956"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",956,''),//Oum Laadham
"957"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",957,''),//Mouadjebar
"958"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",958,''),//daira Ain el Ibel
"959"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",959,''),//Amera
"960"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",960,''),//N'thila
"961"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",961,''),//Oued Seddeur
"962"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",962,''),//Zaccar
"963"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",963,''),//Douis
"964"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",964,''),//Ain Chouhada
"965"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",965,''),//Tadmit
"966"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",966,''),//El Hiouhi
"967"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",967,''),//daira Faidh el Botma
"968"  => $pdf->valeurmoisdeces('','deceshosp','DINS','COMMUNER',$ANNEE."-01-01",$ANNEE."-12-31",968,'') //Amourah
);




// $pdf->AddPage();
// $pdf->SetTextColor( $headerColour[0], $headerColour[1], $headerColour[2] );
// $pdf->SetFont( 'Arial', '', 12 );
// $pdf->Cell( 0, 15, html_entity_decode(utf8_decode($reportName4)), 0, 0, 'C' );
// $pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );
// $pdf->SetFont( 'Arial', '', 20 );
// $pdf->Write( 19, "La collecte du sang" );
// $pdf->SetFont( 'Arial', '', 12 );
// $pdf->Ln( 16 );
// $pdf->Write( 6,html_entity_decode(utf8_decode("L'activité du don de sang se résume en ".$donparwilayar." dons collectés à l'échelle secteur sanitaire répartie comme suit :")));
// $pdf->Ln( 12 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("Prélèvement du sang total")) );
// $pdf->Ln( 5 );

// if ($diff>0) 
// {
// $pdf->Write( 6, html_entity_decode(utf8_decode("L'année ".$date1." a été marquée par une augmentation du nombre de dons. Il a été enregistré ".$donparwilayar." dons de sang total collectés par la structure de transfusion sanguine, avec ".$diff." dons de plus que l'année ".($date1-1)." , soit une progression de **%.")) );
// }

// if ($diff<0) 
// {
// $pdf->Write( 6, html_entity_decode(utf8_decode("L'année ".$date1." a été marquée par une diminution du nombre de dons. Il a été enregistré ".$donparwilayar." dons de sang total collectés par la structure de transfusion sanguine, avec ".$diff*(-1)." dons de moins que l'année ".($date1-1)." , soit une regression de **%.")) );
// }



// $pdf->Ln( 12 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("Prélèvement d'Aphérèse")) );
// $pdf->Ln( 5 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("Le nombre de concentrés de plaquettes d'aphérèse (CPA) prélevés est de 00 dons .")) );//avec une augmentation de 00% par rapport à l’année 2013 (00 dons)
// $pdf->Ln( 12 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("Les ajournements")) );
// $pdf->Ln( 5 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("En ".$date1.", le nombre de  candidats au don de sang total a été estimé à ".($donparwilayar+$donparwilayar0).", la part des candidats ajournés est de ".round (($donparwilayar0*100)/($donparwilayar+$donparwilayar0),2)." % soit ".$donparwilayar0." candidats.Le nombre de candidats inaptes évolue de façon proportionnelle par rapport au nombre de dons")) );
//prevoire causes ajournement  +  tableau indication contre indication 
$pdf->AddPage();
// $pdf->Write( 6, html_entity_decode(utf8_decode("Nombre de don pour 1000 habitants : La population du secteur sanitaire 10 communes en ".$date1." étant estimée à 251 038 habitants , la proportion de dons pour 1 000 habitants est de ".round((($donparwilayar*1000)/251038),2).". ")) );
// $pdf->Ln( 5 );$pdf->Write( 6, html_entity_decode(utf8_decode("L'indice de générosité en transfusion sanguine est défini comme étant le rapport entre le nombre de dons et l'effectif de la population entre 18 et 65 ans (susceptible de donner son sang). Il est exprimé pour 100 ou pour 1 000 habitants.")) );
// $pdf->Ln( 5 );
// $pdf->Ln( 0 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("Indice de générosité : En ".$date1.", l'effectif entre 18 et 65 ans étant estimé à 251 038 habitants, l'indice secteur sanitaire de générosité est de ".round((($donparwilayar*1000)/251038),2)." dons pour 1000 habitants. Cet indice varie d'une commune à une autre et souligne les efforts effectués pour le recrutement de nouveaux donneurs.")) );
$pdf->tblparcommune('Dons') ;//Dons//Donneurs
$pdf->djelfa($data,560,128,3.7,'c');//c:commune//d:dairas 
$pdf->SetFont( 'Arial', '', 12 );
$pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );

$pdf->AddPage();
$pdf->Ln( 0 );
$pdf->Write( 6, html_entity_decode(utf8_decode("Répartition des déces par tranche d'age ( 05 ans ) et sexe")) );


    $M1=$pdf->AGESEXE(0,4,$t,'M');  $F1=$pdf->AGESEXE(0,4,$t,'F');
	$M2=$pdf->AGESEXE(5,9,$t,'M');  $F2=$pdf->AGESEXE(5,9,$t,'F');
	$M3=$pdf->AGESEXE(10,14,$t,'M');$F3=$pdf->AGESEXE(10,14,$t,'F');
	$M4=$pdf->AGESEXE(15,19,$t,'M');$F4=$pdf->AGESEXE(15,19,$t,'F');
	$M5=$pdf->AGESEXE(20,24,$t,'M');$F5=$pdf->AGESEXE(20,24,$t,'F');
	$M6=$pdf->AGESEXE(25,29,$t,'M');$F6=$pdf->AGESEXE(25,29,$t,'F');
	$M7=$pdf->AGESEXE(30,34,$t,'M');$F7=$pdf->AGESEXE(30,34,$t,'F');
	$M8=$pdf->AGESEXE(35,39,$t,'M');$F8=$pdf->AGESEXE(35,39,$t,'F');
	$M9=$pdf->AGESEXE(40,44,$t,'M');$F9=$pdf->AGESEXE(40,44,$t,'F');
	$M10=$pdf->AGESEXE(45,49,$t,'M');$F10=$pdf->AGESEXE(45,49,$t,'F');
	$M11=$pdf->AGESEXE(50,54,$t,'M');$F11=$pdf->AGESEXE(50,54,$t,'F');
	$M12=$pdf->AGESEXE(55,59,$t,'M');$F12=$pdf->AGESEXE(55,59,$t,'F');
	$M13=$pdf->AGESEXE(60,64,$t,'M');$F13=$pdf->AGESEXE(60,64,$t,'F');
	$M14=$pdf->AGESEXE(65,69,$t,'M');$F14=$pdf->AGESEXE(65,69,$t,'F');
	$M15=$pdf->AGESEXE(70,74,$t,'M');$F15=$pdf->AGESEXE(70,74,$t,'F');
	$M16=$pdf->AGESEXE(75,79,$t,'M');$F16=$pdf->AGESEXE(75,79,$t,'F');
	$M17=$pdf->AGESEXE(80,84,$t,'M');$F17=$pdf->AGESEXE(80,84,$t,'F');
	$M18=$pdf->AGESEXE(85,89,$t,'M');$F18=$pdf->AGESEXE(85,89,$t,'F');
	$M19=$pdf->AGESEXE(90,94,$t,'M');$F19=$pdf->AGESEXE(90,94,$t,'F');
	$M20=$pdf->AGESEXE(95,99,$t,'M');$F20=$pdf->AGESEXE(95,99,$t,'F');


	// $pyramide= array(
	// "1M"  => $M1,   "1F"  => $F1,
	// "2M"  => $M2,   "2F"  => $F2,
	// "3M"  => $M3,   "3F"  => $F3,
	// "4M"  => $M4,   "4F"  => $F4,
	// "5M"  => $M5,   "5F"  => $F5,
	// "6M"  => $M6,   "6F"  => $F6,
	// "7M"  => $M7,   "7F"  => $F7,
	// "8M"  => $M8,   "8F"  => $F8,
	// "9M"  => $M9,   "9F"  => $F9,
	// "10M" => $M10,  "10F" => $F10,
	// "11M" => $M11,  "11F" => $F11,
	// "12M" => $M12,  "12F" => $F12,
	// "13M" => $M13,  "13F" => $F13,
	// "14M" => $M14,  "14F" => $F14,
	// "15M" => $M15,  "15F" => $F15,
	// "16M" => $M16,  "16F" => $F16,
	// "17M" => $M17,  "17F" => $F17,
	// "18M" => $M18,  "18F" => $F18,
	// "19M" => $M19,  "19F" => $F19,
	// "20M" => $M20,  "20F" => $F20
	// );
	// $pdf->pyramide(200,150,utf8_decode('1 - Pyramide des âges de la population des donneurs'),$pyramide);
	$T2F20=array(
			"xt" => 5,
			"yt" => 42,
			"wc" => "",
			"hc" => "",
			"tt" => "I  Repartition des déces par tranche d'age ( 05 ans ) et sexe",
			"tc" => "Sexe",
			"tc1" =>"M",
			"tc3" =>"F",
			"tc5" =>"Total",
			"1M"  => $M1,   "1F"  => $F1,
			"2M"  => $M2,   "2F"  => $F2,
			"3M"  => $M3,   "3F"  => $F3,
			"4M"  => $M4,   "4F"  => $F4,
			"5M"  => $M5,   "5F"  => $F5,
			"6M"  => $M6,   "6F"  => $F6,
			"7M"  => $M7,   "7F"  => $F7,
			"8M"  => $M8,   "8F"  => $F8,
			"9M"  => $M9,   "9F"  => $F9,
			"10M" => $M10,  "10F" => $F10,
			"11M" => $M11,  "11F" => $F11,
			"12M" => $M12,  "12F" => $F12,
			"13M" => $M13,  "13F" => $F13,
			"14M" => $M14,  "14F" => $F14,
			"15M" => $M15,  "15F" => $F15,
			"16M" => $M16,  "16F" => $F16,
			"17M" => $M17,  "17F" => $F17,
			"18M" => $M18,  "18F" => $F18,
			"19M" => $M19,  "19F" => $F19,
			"20M" => $M20,  "20F" => $F20,
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
	$pdf->SetFont( 'Arial', '', 10 );
	// $pdf->T2F20($T2F20);
	// $pdf->T2F20STAT($t,'AGEDNR','AGE');
    $pdf->SetFont( 'Arial', '', 12 );

// $pdf->AddPage();
// $pdf->Ln( 0 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("Répartition des dons par tranche de poids ( 05 kg ) et sexe,Pyramide des poids de la population des donneurs")) );

    // $M1=$pdf->POIDSSEXE(0,4,$t,'M');  $F1=$pdf->POIDSSEXE(0,4,$t,'F');
	// $M2=$pdf->POIDSSEXE(5,9,$t,'M');  $F2=$pdf->POIDSSEXE(5,9,$t,'F');
	// $M3=$pdf->POIDSSEXE(10,14,$t,'M');$F3=$pdf->POIDSSEXE(10,14,$t,'F');
	// $M4=$pdf->POIDSSEXE(15,19,$t,'M');$F4=$pdf->POIDSSEXE(15,19,$t,'F');
	// $M5=$pdf->POIDSSEXE(20,24,$t,'M');$F5=$pdf->POIDSSEXE(20,24,$t,'F');
	// $M6=$pdf->POIDSSEXE(25,29,$t,'M');$F6=$pdf->POIDSSEXE(25,29,$t,'F');
	// $M7=$pdf->POIDSSEXE(30,34,$t,'M');$F7=$pdf->POIDSSEXE(30,34,$t,'F');
	// $M8=$pdf->POIDSSEXE(35,39,$t,'M');$F8=$pdf->POIDSSEXE(35,39,$t,'F');
	// $M9=$pdf->POIDSSEXE(40,44,$t,'M');$F9=$pdf->POIDSSEXE(40,44,$t,'F');
	// $M10=$pdf->POIDSSEXE(45,49,$t,'M');$F10=$pdf->POIDSSEXE(45,49,$t,'F');
	// $M11=$pdf->POIDSSEXE(50,54,$t,'M');$F11=$pdf->POIDSSEXE(50,54,$t,'F');
	// $M12=$pdf->POIDSSEXE(55,59,$t,'M');$F12=$pdf->POIDSSEXE(55,59,$t,'F');
	// $M13=$pdf->POIDSSEXE(60,64,$t,'M');$F13=$pdf->POIDSSEXE(60,64,$t,'F');
	// $M14=$pdf->POIDSSEXE(65,69,$t,'M');$F14=$pdf->POIDSSEXE(65,69,$t,'F');
	// $M15=$pdf->POIDSSEXE(70,74,$t,'M');$F15=$pdf->POIDSSEXE(70,74,$t,'F');
	// $M16=$pdf->POIDSSEXE(75,79,$t,'M');$F16=$pdf->POIDSSEXE(75,79,$t,'F');
	// $M17=$pdf->POIDSSEXE(80,84,$t,'M');$F17=$pdf->POIDSSEXE(80,84,$t,'F');
	// $M18=$pdf->POIDSSEXE(85,89,$t,'M');$F18=$pdf->POIDSSEXE(85,89,$t,'F');
	// $M19=$pdf->POIDSSEXE(90,94,$t,'M');$F19=$pdf->POIDSSEXE(90,94,$t,'F');
	// $M20=$pdf->POIDSSEXE(95,150,$t,'M');$F20=$pdf->POIDSSEXE(95,150,$t,'F');

	// $pyramide= array(
	// "1M"  => $M1,   "1F"  => $F1,
	// "2M"  => $M2,   "2F"  => $F2,
	// "3M"  => $M3,   "3F"  => $F3,
	// "4M"  => $M4,   "4F"  => $F4,
	// "5M"  => $M5,   "5F"  => $F5,
	// "6M"  => $M6,   "6F"  => $F6,
	// "7M"  => $M7,   "7F"  => $F7,
	// "8M"  => $M8,   "8F"  => $F8,
	// "9M"  => $M9,   "9F"  => $F9,
	// "10M" => $M10,  "10F" => $F10,
	// "11M" => $M11,  "11F" => $F11,
	// "12M" => $M12,  "12F" => $F12,
	// "13M" => $M13,  "13F" => $F13,
	// "14M" => $M14,  "14F" => $F14,
	// "15M" => $M15,  "15F" => $F15,
	// "16M" => $M16,  "16F" => $F16,
	// "17M" => $M17,  "17F" => $F17,
	// "18M" => $M18,  "18F" => $F18,
	// "19M" => $M19,  "19F" => $F19,
	// "20M" => $M20,  "20F" => $F20
	// );
	// $pdf->pyramide(200,150,utf8_decode('1 - Pyramide des poids de la population des donneurs'),$pyramide);
	// $T2F20=array(
			// "xt" => 5,
			// "yt" => 42,
			// "wc" => "",
			// "hc" => "",
			// "tt" => "I  Repartition des dons par tranche de poids ( 05 kg ) et sexe",
			// "tc" => "Sexe",
			// "tc1" =>"M",
			// "tc3" =>"F",
			// "tc5" =>"Total",
			// "1M"  => $M1,   "1F"  => $F1,
			// "2M"  => $M2,   "2F"  => $F2,
			// "3M"  => $M3,   "3F"  => $F3,
			// "4M"  => $M4,   "4F"  => $F4,
			// "5M"  => $M5,   "5F"  => $F5,
			// "6M"  => $M6,   "6F"  => $F6,
			// "7M"  => $M7,   "7F"  => $F7,
			// "8M"  => $M8,   "8F"  => $F8,
			// "9M"  => $M9,   "9F"  => $F9,
			// "10M" => $M10,  "10F" => $F10,
			// "11M" => $M11,  "11F" => $F11,
			// "12M" => $M12,  "12F" => $F12,
			// "13M" => $M13,  "13F" => $F13,
			// "14M" => $M14,  "14F" => $F14,
			// "15M" => $M15,  "15F" => $F15,
			// "16M" => $M16,  "16F" => $F16,
			// "17M" => $M17,  "17F" => $F17,
			// "18M" => $M18,  "18F" => $F18,
			// "19M" => $M19,  "19F" => $F19,
			// "20M" => $M20,  "20F" => $F20,
			// "T" =>'1',
			// "tl" =>"Poids",
			// "1MF"  => '00-04',  
			// "2MF"  => '05-09',   
			// "3MF"  => '10-14',  
			// "4MF"  => '15-19',   
			// "5MF"  => '20-24',  
			// "6MF"  => '25-29',   
			// "7MF"  => '30-34',  
			// "8MF"  => '35-39',   
			// "9MF"  => '40-44',   
			// "10MF" => '45-49',  
			// "11MF" => '50-54',  
			// "12MF" => '55-59', 
			// "13MF" => '60-64',  
			// "14MF" => '65-69', 
			// "15MF" => '70-74',  
			// "16MF" => '75-79',  
			// "17MF" => '80-84',  
			// "18MF" => '85-89', 
			// "19MF" => '90-94', 
			// "20MF" => '95-99'  
			// );
	// $pdf->T2F20($T2F20);	
	// $pdf->T2F20STAT($t,'POIDS','POIDS');
    // $pdf->SetFont( 'Arial', '', 12 );

	
// $pdf->AddPage();
// $pdf->Ln( 12 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("Dons occasionnels et  réguliers :")) );
// $pdf->Ln( 5 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("En 2014, 27,39% des dons sont des dons réguliers (137 749 dons), soit une diminution significative de 10,25% (une différence de 15 740 dons par rapport à 2013 « p=10-6 »)")) );

// $pdf->Ln( 12 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("Collectes mobiles et fixes:")) );
// $pdf->Ln( 5 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("Le prélèvement de sang total, effectué lors des collectes mobiles passe d’un taux de 34,55% (169 526 dons) en 2013 à un taux de 33,49% (168 428 dons) en 2014. Ce pourcentage est en diminution significative de 0,64% (« p=10-6 »). Le pourcentage de la collecte mobile varie d’un minimum de 2,44% à Souk Ahras à un maximum de 83,01% pour Constantine.")) );


// $pdf->AddPage();
// $pdf->SetFont( 'Arial', '', 20 );
// $pdf->Write( 19, html_entity_decode(utf8_decode("La préparation des Produits Sanguins Labiles")) );
// $pdf->SetFont( 'Arial', '', 12 );
// $pdf->Ln( 12 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("La séparation:")) );
// $pdf->Ln( 5 );
//$pdf->Write( 6, html_entity_decode(utf8_decode("La séparation est un procédé qui vise à obtenir à partir du sang total, différents produits dérivés dits produits sanguins labiles (PSL) à savoir : le Concentré de Globules Rouges (CGR), le Plasma Frais Congelé (PFC) et le Concentré Plaquettaire Standard (CPS), Ce procédé peut se faire par centrifugation ou par décantation en absence de centrifugeuse. En Algérie, l'activité de séparation du sang total en ses dérivés est en voie de généralisation mais varie encore considérablement d'une structure à l'autre. En 2014, sur 206 structures de transfusion sanguine fonctionnelles, 108 structures procèdent à la séparation par centrifugation dont 09 font de la sous-traitance, 93 structurespar décantation et 05 structures ne séparent pas le sang total.Ainsi le taux national de séparation du sang total, enregistré durant l’année 2014 est de91,32%, Il a diminué significativement « p=10-6 » de 1,13% comparativement à l’annéeprécédente.Le taux de séparation enregistré est un bon indicateur de l'utilisation rationnelle desproduits sanguins labiles, selon les recommandations internationales qui ne préconisentplus l'usage du sang total mais de ses produits dérivés.Malgré ces recommandations 1,3% du sang prélevé (6 516 dons) n'est pas séparé.")) );


// $pdf->Ln( 12 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("Le don non conforme:")) );
// $pdf->Ln( 5 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("Le don non conforme représente les poches de sang éliminées au cours de la préparationsuite à une fuite, un défaut de soudure, un aspect coagulé ou un volume insuffisant /tropimportant.Les dons non conformes représentent 27 257 dons soit 5,4% du sang prélevé.")) );

// $pdf->Ln( 12 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("La préparation:")) );
// $pdf->Ln( 5 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("La préparation consiste à soumettre les poches de sang à des conditions physiques etthermiques prédéfinies afin d’obtenir des produits sanguins labiles (PSL) aux normesnationales et internationales et aux exigences de la sécurité transfusionnelle.Au cours de l’année 2014, 746 786 PSL ont été préparés à partir des dons de sang collectéssur l’ensemble du territoire national contre 725 573 PSL préparés 2013.91,2% du nombre de poches de sang total est séparé alors que 8,8% des poches de sangtotal sont utilisées telle qu’elles.")) );


// $pdf->AddPage();
// $pdf->SetFont( 'Arial', '', 20 );
// $pdf->Write( 19, html_entity_decode(utf8_decode("La qualification hemato-sérologique des dons")) );
// $pdf->SetFont( 'Arial', '', 12 );
 
// $pdf->Ln( 12 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("La qualification hematologique des dons:")) );
// $ap=$pdf->compagne('A','Positif',$t);
// $an=$pdf->compagne('A','negatif',$t);
// $bp=$pdf->compagne('B','Positif',$t);
// $bn=$pdf->compagne('B','negatif',$t);
// $abp=$pdf->compagne('AB','Positif',$t);
// $abn=$pdf->compagne('AB','negatif',$t);
// $op=$pdf->compagne('O','Positif',$t);
// $on=$pdf->compagne('O','negatif',$t);	
	// $T4F2=array(
			// "xt" => 5,
			// "yt" => 40,
			// "wc" => "",
			// "hc" => "",
			// "tt" => "III  Repartition des dons par groupage rhesus",
			// "tc" => "Groupage",
			// "tc1" =>"A",
			// "tc2" =>"B",
			// "tc3" =>"AB",
			// "tc4" =>"O",
			// "tc5" =>"Total",
			// "l1c1" =>$ap,
			// "l1c2" =>$bp,
			// "l1c3" =>$abp,
			// "l1c4" =>$op,
			// "l1c5" =>$ap+$bp+$abp+$op,
			// "l2c1" =>$an,
			// "l2c2" =>$bn,
			// "l2c3" =>$abn,
			// "l2c4" =>$on,
			// "l2c5" =>$an+$bn+$abn+$on,
			// "l3c1" =>$ap+$an,
			// "l3c2" =>$bp+$bn,
			// "l3c3" =>$abp+$abn,
			// "l3c4" =>$op+$on,
			// "l3c5" =>$ap+$an+$bp+$bn+$abp+$abn+$op+$on,
			// "tl" =>"Rhesus",
			// "tl1" =>"Rh+",
			// "tl2" =>"Rh-",
			// "tl3" =>"Total"
			// );
	// $pdf-> T4F2($T4F2);
	// $pie4 = array(
			// "x" => 200, "y" => 65, 
			// "r" => 17,
			// "v1" => $ap+$an,
			// "v2" => $bp+$bn,
			// "v3" => $abp+$abn,
			// "v4" => $op+$on,
			// "t0" => "III.A - Dons par groupage ABO ",
			// "t1" => "A",
			// "t2" => "B",
			// "t3" => "AB",
			// "t4" => "O"
			// );
	// $pdf->pie4($pie4);
	
	// $pie2 = array(
	// "x" => 200, 
	// "y" => 116, 
	// "r" => 17,
	// "v1" => $ap+$bp+$abp+$op,
	// "v2" => $an+$bn+$abn+$on,
	// "t0" => "III.B - Dons par groupage RH(-/+)  ",
	// "t1" => "RH+",
	// "t2" => "RH-");
	// $pdf->pie2($pie2);

// $pdf->AddPage();
// $pdf->SetFont( 'Arial', '', 12 );
// $pdf->Ln( 12 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("La qualification sérologique des dons:")) );	
// $pdf->Ln( 5 );
//$pdf->Write( 6, html_entity_decode(utf8_decode("hépatites C et syphilis pour tout don de sang aété rendu obligatoire par l’arrêté ministérieldu 24 Mai 1998.Pratiqué sur chaque don, le dépistage initialdoit mettre en évidence les anticorps anti HIV1 et 2, l’antigène HBs, l’anticorps del’hépatite C ainsi que celui de la syphilis. Lerésultat du test pratiqué est alors considéré comme positif, douteux ou négatif.Tout sérum dépisté positif ou douteux doit faire l’objet d’une nouvelle procédure d’analysepour confirmation (procédure fixée par les modes opératoires normalisés élaborés parl’ANS et diffusés en 2002).La confirmation des tests dépistés positifs ou douteux est obligatoire pour cesdifférents marqueurs excepté la syphilis, est effectuée actuellement à l’Institut Pasteurd’Algérie (IPA).Dans ce chapitre, nous nous sommes intéressés aux dons exclus du circuit dedistribution car dépistés positifs ou douteux aux différents marqueurs infectieux.Toutes les données communiquées dans ce chapitre sont établies sur la based’informations transmises par les différentes structures à l’Agence Nationale duSang")) );
// $pdf->Ln( 5 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("Dépistage du marqueur HIV:")) );
// $pdf->Ln( 5 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("")) );

// $pdf->Ln( 5 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("Dépistage du marqueur de l’hépatite B:")) );
// $pdf->Ln( 5 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("")) );

// $pdf->Ln( 5 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("Dépistage du marqueur de l’hépatite C:")) );
// $pdf->Ln( 5 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("")) );

// $pdf->Ln( 5 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("Dépistage de l’infection par l’agent de la syphilis :")) );
// $pdf->Ln( 5 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("")) );
// $T4F2=array(
			// "xt" => 5,
			// "yt" => 140,
			// "wc" => "",
			// "hc" => "",
			// "tt" => "III  Repartition des dons par serologie",
			// "tc" => "serologie",
			// "tc1" =>"HIV",
			// "tc2" =>"HVB",
			// "tc3" =>"HVC",
			// "tc4" =>"TPHA",
			// "tc5" =>"Total",
			// "l1c1" =>$ap,
			// "l1c2" =>$bp,
			// "l1c3" =>$abp,
			// "l1c4" =>$op,
			// "l1c5" =>$ap+$bp+$abp+$op,
			// "l2c1" =>$an,
			// "l2c2" =>$bn,
			// "l2c3" =>$abn,
			// "l2c4" =>$on,
			// "l2c5" =>$an+$bn+$abn+$on,
			// "l3c1" =>$ap+$an,
			// "l3c2" =>$bp+$bn,
			// "l3c3" =>$abp+$abn,
			// "l3c4" =>$op+$on,
			// "l3c5" =>$ap+$an+$bp+$bn+$abp+$abn+$op+$on,
			// "tl" =>"Resulta",
			// "tl1" =>"S+",
			// "tl2" =>"S-",
			// "tl3" =>"Total"
			// );
	// $pdf-> T4F2($T4F2);

// $pdf->AddPage();
// $pdf->SetFont( 'Arial', '', 20 );
// $pdf->Write( 19, html_entity_decode(utf8_decode("La distribution des produits sanguins labiles")) );
// $pdf->SetFont( 'Arial', '', 12 );

// $pdf->Ln( 12 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("La distribution des produits sanguins labiles:")) );
// $pdf->Ln( 5 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("Les 502 924 dons de sang total collectés à travers le territoire national en 2014, ontpermis la préparation de 746 786 produitssanguins labiles (PSL) validés pour ladistribution.Ainsi que 6 017 dons de plaquettes paraphérèse (CPA) ont été distribué, d’où untotal de 752 803 produits sanguins labilesprêt à la distribution.")) );


// $pdf->Ln( 12 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("Quels sont les produits sanguins labiles (PSL) distribués en Algérie ?")) );
// $pdf->Ln( 5 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("En 2014, le culot globulaire (CGR) est le produit sanguin labile le plus distribué à traversles structures hospitalières du territoire national suivi en 2ème position du concentréplaquettaire standard (CPS) puis du plasma frais congelé (PFC) en 3ème position , puis lesang total (ST) en 4ème position et enfin le concentré plaquettaire d’aphérèse en 5èmeposition.")) );

// $pdf->Ln( 12 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("Quels sont les services qui ont consommé le plus de PSL en 2014 ?")) );
// $pdf->Ln( 5 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("Parmi les services les plus consommateurs de PSL, vient en tête l’hématologie avec uneconsommation de 18% de l’ensemble des produits sanguins labiles distribués en Algérie,suivie des UMC et de la Gynécologie-Obstétrique avec 17% de consommation puis desservices de chirurgie avec un taux de consommation de 16%.")) );

// $pdf->Ln( 12 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("Utilisation des PSL par services de soins")) );
// $pdf->Ln( 5 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("Le type de PSL le plus utilisé par chaque service dépend essentiellement de son activité,ainsi :")) );


// $pdf->Ln( 12 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("Utilisation des PSL en intra et en extra muros entre le public et le privé:")) );
// $pdf->Ln( 5 );
// $pdf->Write( 6, html_entity_decode(utf8_decode("En 2014, la distribution des PSL en extramuros est très faible par rapport à la distributionen intramuros avec un taux de 19.66%, ainsi le secteur privé n’a consommé que 5% dutotal des PSL distribués en Algérie.")) );



//REPARTITION GEOGRAPHIQUE 
// $pdf->entetepage1('Reartition Geographique Des Dons Par Commune  ');


$pdf->Output();
?>