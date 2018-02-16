<?php
/**
* PHP class made to write HTML code Made on the 04.september.2004 by Tristan Carron Version 1.0 Principal functions for HTML encoding every function existe with is own name. This file has been developed under GPL licence 
*
* @package html.class.php
* @author Tristan Carron
* @version 1.4
* @abstract
* @copyright GPL licence
*/

/**
 * Define the content of the class html.class.php
 * @package html.class.php
 *
 */

class HTML  {
	
	function __construct() {
	   parent::__construct();
    
	}
	
	public  $db_host="localhost"; 
	public  $db_name="mdo"; 
	public  $db_user="root";
	public  $db_pass="";
	
	function footgraphe($id,$ctrl)
    {
	echo '<fieldset id="fieldset1">';
	echo '<legend>Mois</legend>';	
	echo "<button id=\"btgraphe\"  onclick=\"document.location='".URL."dashboard/".$ctrl."/1';  \"  title=\"Par mois\">Deces</button> " ;	  	 
	echo '</fieldset>';
	
	echo '<fieldset id="fieldset1">';
	echo '<legend>Anee</legend>';
	echo "<button id=\"btgraphe\"  onclick=\"document.location='".URL."dashboard/".$ctrl."/0';  \"        title=\"Par Année\">Deces</button> " ;		  	 
	echo '</fieldset>';
	
	
	echo '<fieldset id="fieldset1">';
	echo '<legend>Structure : '.$this->nbrtostring('structure','id',$id,'structure').'</legend>';	 
	echo "<button id=\"btgraphe\"  onclick=\"document.location='".URL."dashboard/".$ctrl."/2';  \"        title=\"Par Année\">Deces</button> " ;	
	echo "<button id=\"btgraphe\"  onclick=\"document.location='".URL."dashboard/SIGA';  \"        title=\"Par Année\">SIGA</button> " ;	
	
	echo '</fieldset>';
	
	}
	function cimnbr($STR,$CODECIM0) 
	{
	$cnx = mysql_connect($this->db_host,$this->db_user,$this->db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
	$db  = mysql_select_db($this->db_name,$cnx) ;
	$sql = " select * from deceshosp where STRUCTURED=$STR and CODECIM0=$CODECIM0   ";//CODECIM0
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$OP=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $OP;
	}
	function cimnbr1($STR,$CODECIM) 
	{
	$cnx = mysql_connect($this->db_host,$this->db_user,$this->db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
	$db  = mysql_select_db($this->db_name,$cnx) ;
	$sql = " select * from deceshosp where STRUCTURED=$STR and CODECIM=$CODECIM ";
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$OP=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $OP;
	}
	function combov1($name,$Jour)  
	{	 
	echo "<select name=\"".$name."\" >";
	foreach ($Jour as $cle => $value) 
	{
	echo"<OPTION VALUE=\"".$value."\">".$cle."</OPTION>";
	}
	echo "</select> ";	
	} 
	
	function XLS($serveur,$STRUCTURED)
    {
		
	$fichier ='D:\deces\libs\deces.php';	
    error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	date_default_timezone_set('Europe/London');

	define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
	require_once dirname(__FILE__) . './PHPExcel/PHPExcel.php';
	echo date('H:i:s') , "Create new PHPExcel object" , EOL;
		$objPHPExcel = new PHPExcel();
	echo date('H:i:s') , " Set document properties" , EOL;

		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'id')
					->setCellValue('B1', 'NOM')
					->setCellValue('C1', 'PRENOM')
					->setCellValue('D1', 'FILSDE')
					->setCellValue('E1', 'ETDE')
					->setCellValue('F1', 'SEX')
					->setCellValue('G1', 'DATENAISSANCE')
					->setCellValue('H1', 'COMMUNE')
					->setCellValue('I1', 'WILAYA')
					->setCellValue('J1', 'COMMUNER')
					->setCellValue('K1', 'WILAYAR')
					->setCellValue('L1', 'DINS')
					->setCellValue('M1', 'DATEHOSPI')
					->setCellValue('N1', 'SERVICEHOSPIT')
					->setCellValue('O1', 'MEDECINHOSPIT')
					->setCellValue('P1', 'CODECIM')
					;
		
		// Rename worksheet
	    echo date('H:i:s') , " call database" , EOL;
		$sqlPes = "SELECT * FROM deceshosp where STRUCTURED =$STRUCTURED  order by DINS";  
		$exePes = mysql_query($sqlPes);
		$resPes = mysql_fetch_assoc($exePes);
		$numPes = mysql_num_rows($exePes);
		
		for ($i = 2; $i <= $numPes + 1; $i++) {
		  
			$objPHPExcel->setActiveSheetIndex(0)
				 ->setCellValue('A'.$i, $i-1)
				 ->setCellValue('B'.$i, $resPes['NOM'])
				 ->setCellValue('C'.$i, $resPes['PRENOM'])
				 ->setCellValue('D'.$i, $resPes['FILSDE'])
				 ->setCellValue('E'.$i, $resPes['ETDE'])
				 ->setCellValue('F'.$i, $resPes['SEX'])
				 ->setCellValue('G'.$i, $resPes['DATENAISSANCE'])
				 ->setCellValue('H'.$i, $resPes['COMMUNE'])
				 ->setCellValue('I'.$i, $resPes['WILAYA'])
				 ->setCellValue('J'.$i, $resPes['COMMUNER'])
				 ->setCellValue('K'.$i, $resPes['WILAYAR'])
				 ->setCellValue('L'.$i, $resPes['DINS'])
				 ->setCellValue('M'.$i, $resPes['DATEHOSPI'])
				 ->setCellValue('N'.$i, $resPes['SERVICEHOSPIT'])
				 ->setCellValue('O'.$i, $resPes['MEDECINHOSPIT'])
				 ->setCellValue('P'.$i, $resPes['CODECIM']);
	                         
			$resPes = mysql_fetch_assoc($exePes);
		   
		}
	// Rename worksheet
	
	
	
	
	echo date('H:i:s') , " Rename worksheet" , EOL;
	$objPHPExcel->getActiveSheet()->setTitle('deces');
    $objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(5);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);	
	
	$objPHPExcel->getActiveSheet()->getStyle('A1:P1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF69B4');
	
	// Save Excel 2007 file
	echo date('H:i:s') , " Write to Excel2007 format" , EOL;
	$callStartTime = microtime(true);
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	echo $fichier  ;echo'<br />';
	$objWriter->save(str_replace('.php', '.xlsx', $fichier));
	$callEndTime = microtime(true);
	$callTime = $callEndTime - $callStartTime;
	
	echo $fichier  ;echo'<br />';
	echo date('H:i:s') , " File written to " , str_replace('.php', '.xlsx', pathinfo($fichier, PATHINFO_BASENAME)) , EOL;
	echo 'Call time to write Workbook was ' , sprintf('%.4f',$callTime) , " seconds" , EOL;
	// Echo memory usage
	echo date('H:i:s') , ' Current memory usage: ' , (memory_get_usage(true) / 1024 / 1024) , " MB" , EOL;
	// Save Excel 95 file
	echo date('H:i:s') , " Write to Excel5 format" , EOL;
	$callStartTime = microtime(true);
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save(str_replace('.php', '.xls', $fichier));
	$callEndTime = microtime(true);
	$callTime = $callEndTime - $callStartTime;

	echo date('H:i:s') , " File written to " , str_replace('.php', '.xls', pathinfo($fichier, PATHINFO_BASENAME)) , EOL;
	echo 'Call time to write Workbook was ' , sprintf('%.4f',$callTime) , " seconds" , EOL;
	// Echo memory usage
	echo date('H:i:s') , ' Current memory usage: ' , (memory_get_usage(true) / 1024 / 1024) , " MB" , EOL;


	// Echo memory peak usage
	echo date('H:i:s') , " Peak memory usage: " , (memory_get_peak_usage(true) / 1024 / 1024) , " MB" , EOL;

	// Echo done
	echo date('H:i:s') , " Done writing files" , EOL;
	echo 'Files have been created in ' , getcwd() , EOL;
	echo '<a href="'.URL.'libs/deces.xls">Enregistrer Sous xls</a></br>';
	echo '<a href="'.URL.'libs/deces.xlsx">Enregistrer Sous xlsx</a></br>';	
	}

	function XLS1($serveur,$STRUCTURED)
    {
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	date_default_timezone_set('Europe/London');

	define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
	require_once dirname(__FILE__) . './PHPExcel/PHPExcel.php';
	echo date('H:i:s') , " Create new PHPExcel object" , EOL;// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
	echo date('H:i:s') , " Set document properties" , EOL;// Set document properties
	$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
								 ->setLastModifiedBy("Maarten Balliauw")
								 ->setTitle("PHPExcel Test Document")
								 ->setSubject("PHPExcel Test Document")
								 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
								 ->setKeywords("office PHPExcel php")
								 ->setCategory("Test result file");
	
	 $cnx = mysql_connect($serveur,$this->db_user,$this->db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
	 $db  = mysql_select_db($this->db_name,$cnx) ;
	 mysql_query("SET NAMES 'UTF8' ");
	 $sql=mysql_query("SELECT * FROM deceshosp where STRUCTURED =$STRUCTURED  order by DINS");//where STRUCTURED=4where STRUCTURED=1 
	
      // $objPHPExcel->setActiveSheetIndex(0)
													 // ->setCellValue('A1','id')
													 // ->setCellValue('B1', 'NOM')
													 // ->setCellValue('C1', 'PRENOM')
													 // ->setCellValue('D1', 'FILSDE')
													 // ->setCellValue('E1', 'ETDE')
													 // ->setCellValue('F1', 'SEX')
													 // ->setCellValue('G1', 'DATENAISSANCE');


	while($value=mysql_fetch_array($sql))
		{
			if (isset($value['id'])) 
				{
				$objPHPExcel->setActiveSheetIndex(0)
													 ->setCellValue('A'.$value['id'], $value['id'])
													 ->setCellValue('B'.$value['id'], $value['NOM'])
													 ->setCellValue('C'.$value['id'], $value['PRENOM'])
													 ->setCellValue('D'.$value['id'], $value['FILSDE'])
													 ->setCellValue('E'.$value['id'], $value['ETDE'])
													 ->setCellValue('F'.$value['id'], $value['SEX'])
													 ->setCellValue('G'.$value['id'], $value['DATENAISSANCE'])
													 ->setCellValue('H'.$value['id'], $value['COMMUNE'])
													 ->setCellValue('I'.$value['id'], $value['WILAYA'])
													 ->setCellValue('J'.$value['id'], $value['COMMUNER'])
													 ->setCellValue('K'.$value['id'], $value['WILAYAR'])
													 ->setCellValue('L'.$value['id'], $value['DINS'])
													 ->setCellValue('M'.$value['id'], $value['DATEHOSPI'])
													 ->setCellValue('N'.$value['id'], $value['SERVICEHOSPIT'])
													 ->setCellValue('O'.$value['id'], $value['MEDECINHOSPIT'])
													 ->setCellValue('P'.$value['id'], $value['CODECIM']);
				
				
				
				
				}        
		}			
	
	
	// Rename worksheet
	echo date('H:i:s') , " Rename worksheet" , EOL;
	$objPHPExcel->getActiveSheet()->setTitle('deces');
    $objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(5);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
  
	
	// Save Excel 2007 file
	echo date('H:i:s') , " Write to Excel2007 format" , EOL;
	$callStartTime = microtime(true);
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
	$callEndTime = microtime(true);
	$callTime = $callEndTime - $callStartTime;
	echo date('H:i:s') , " File written to " , str_replace('.php', '.xlsx', pathinfo(__FILE__, PATHINFO_BASENAME)) , EOL;
	echo 'Call time to write Workbook was ' , sprintf('%.4f',$callTime) , " seconds" , EOL;
	// Echo memory usage
	echo date('H:i:s') , ' Current memory usage: ' , (memory_get_usage(true) / 1024 / 1024) , " MB" , EOL;


	// Save Excel 95 file
	echo date('H:i:s') , " Write to Excel5 format" , EOL;
	$callStartTime = microtime(true);
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save(str_replace('.php', '.xls', __FILE__));
	$callEndTime = microtime(true);
	$callTime = $callEndTime - $callStartTime;

	echo date('H:i:s') , " File written to " , str_replace('.php', '.xls', pathinfo(__FILE__, PATHINFO_BASENAME)) , EOL;
	echo 'Call time to write Workbook was ' , sprintf('%.4f',$callTime) , " seconds" , EOL;
	// Echo memory usage
	echo date('H:i:s') , ' Current memory usage: ' , (memory_get_usage(true) / 1024 / 1024) , " MB" , EOL;


	// Echo memory peak usage
	echo date('H:i:s') , " Peak memory usage: " , (memory_get_peak_usage(true) / 1024 / 1024) , " MB" , EOL;

	// Echo done
	echo date('H:i:s') , " Done writing files" , EOL;
	echo 'Files have been created in ' , getcwd() , EOL;
	echo '<a href="'.URL.'libs/html.xls">Enregistrer Sous xls</a></br>';
	echo '<a href="'.URL.'libs/html.xlsx">Enregistrer Sous xlsx</a></br>';
	
	// echo 'Sauvegarde terminée au niveau D:/Deces_26-05-2017.xls';
	}
		
	function dump_MySQL($serveur, $login, $password, $base, $mode)
    {
    $connexion = mysql_connect($serveur, $login, $password);
    mysql_select_db($base, $connexion);
    
    $entete  = "-- ----------------------\n";
    $entete .= "-- dump de la base ".$base." au ".date("d-M-Y")."\n";
    $entete .= "-- ----------------------\n\n\n";
    $creations = "";
    $insertions = "\n\n";
    
    $listeTables = mysql_query("show tables", $connexion);
    while($table = mysql_fetch_array($listeTables))
    {
        // structure ou la totalite la BDD
        if($mode == 1 || $mode == 2)
        {
            $creations .= "-- -----------------------------\n";
            $creations .= "-- Structure de la table ".$table[0]."\n";
            $creations .= "-- -----------------------------\n";
            $listeCreationsTables = mysql_query("show create table ".$table[0],$connexion); 

            while($creationTable = mysql_fetch_array($listeCreationsTables))
            {
              $creations .= $creationTable[1].";\n\n";
            }
        }
        // donn꦳ ou la totalit        
		if($mode > 1)
        {
		    mysql_query("SET NAMES 'UTF8' ");
            $donnees = mysql_query("SELECT * FROM ".$table[0]);
            $insertions .= "-- -----------------------------\n";
            $insertions .= "-- Contenu de la table ".$table[0]."\n";
            $insertions .= "-- -----------------------------\n";
            while($nuplet = mysql_fetch_array($donnees))
            {
			mysql_query("SET NAMES 'UTF8' ");
                $insertions .= "INSERT INTO ".$table[0]." VALUES(";
                for($i=0; $i < mysql_num_fields($donnees); $i++)
                {
                  if($i != 0)
                     $insertions .=  ", ";
                  if(mysql_field_type($donnees, $i) == "string" || mysql_field_type($donnees, $i) == "blob")
                     $insertions .=  "'";
                  $insertions .= addslashes($nuplet[$i]);
                  if(mysql_field_type($donnees, $i) == "string" || mysql_field_type($donnees, $i) == "blob")
                    $insertions .=  "'";
                }
                $insertions .=  ");\n";
            }
            $insertions .= "\n";
        }
    }
 
    mysql_close($connexion);
   
	$time=date('d-m-Y'); 
	$fichierDump = fopen("D:/Deces_".$time.".sql", "wb");
   // $fichierDump = fopen(dump_mysql.$time.".sql", "wb");
    fwrite($fichierDump, $entete);
    fwrite($fichierDump, $creations);
    fwrite($fichierDump, $insertions);
    fclose($fichierDump);

    echo "Sauvegarde terminée au niveau D:/Deces_".$time.".sql";
	// header("Location:index.php?uc=accueil") ;
}
	function smunuf($data) 
	{
			echo "<form   onsubmit=\"return validateForm11(this);\" name=\"form1\"  action=\"".URL.$data['c']."/".$data['m']."/0/10\" method=\"GET\">" ;
				// echo "<tr bgcolor='#EDF7FF' >" ;
					// echo "<td align=\"left\"  >" ;
						echo "<select  id=\"Race\"    name=\"o\" style=\"width: 100px;\">" ;				
						foreach ($data['combo'] as $cle => $value) 
						{
						echo"<OPTION VALUE=\"".$value."\">".$cle."</OPTION>";
						}	
						echo "</select>&nbsp;" ;
						echo "<input type=\"search\"  placeholder=\"Search...\"    name=\"q\"  value=\"\"  autofocus /> " ;//<!-- onfocus = "tooltip.pop(this,'Donors: <br />Search Keyword.');"   -->
						echo "<img src=\"".URL."public/images/search.PNG\" width='20' height='20' border='0' alt=''/>" ;
						echo "<input id=\"search\" type=\"submit\" name=\"\" value=\"".$data['submitvalue']."\"/> " ;
			echo "</form>" ;
						echo "<button id=\"Cleari\" onclick=\"document.location='".URL.$data['cb1']."/".$data['mb1']."/';  \"   title=\"".$data['tb1']."\">&nbsp;<img src=\"".URL."public/images/".$data['icon1']."\" width='15' height='15' border='0' alt=''/>&nbsp;".$data['vb1']."&nbsp;</button> " ;
					echo "</td>" ;
					echo "<td align=\"right\"> " ;
						echo "<button id=\"Clearm\"  onclick=\"document.location='".URL.$data['cb2']."/".$data['mb2']."/';  \"   title=\"".$data['tb2']."\">&nbsp;<img src=\"".URL."public/images/".$data['icon2']."\" width='15' height='15' border='0' alt=''/>&nbsp;".$data['vb2']."&nbsp;</button> " ;
						echo "<button id=\"Cleark\"  onclick=\"document.location='".URL.$data['cb3']."/".$data['mb3']."/';  \"   title=\"".$data['tb3']."\">&nbsp;<img src=\"".URL."public/images/".$data['icon3']."\" width='15' height='15' border='0' alt=''/>&nbsp;".$data['vb3']."&nbsp;</button> " ;
						echo "<button id=\"Clearl\"  onclick=\"document.location='".URL.$data['cb4']."/".$data['mb4']."/';  \"   title=\"".$data['tb4']."\">&nbsp;<img src=\"".URL."public/images/".$data['icon4']."\" width='15' height='15' border='0' alt=''/>&nbsp;".$data['vb4']."&nbsp;</button> " ;
					echo "</td>" ;
				echo "</tr>" ;	
	}
	
	function munu($menu) 
	{
	// echo '<fieldset id="fieldset1">';
	// echo '<legend>***</legend>';
	// echo "<hr/>";
	//echo "<table  width='100%' border='1' cellpadding='5' cellspacing='1' align='center'>" ;
		if ($menu=='cheval')
		{
			$data = array(
			"c"   => 'dashboard',
			"m"   => 'search',
			"combo"   => array( 
								"NOM"           => 'NOM',
							    "PRENOM"        => 'PRENOM',
								"SEX"           => 'SEX'
							  ),
			"submitvalue" => 'Search',
			"cb1" => 'dashboard',"mb1" => 'nouveau',        "tb1" => 'New',   "vb1" => 'New',   "icon1" => 'add.PNG',
			"cb2" => 'dashboard',"mb2" => 'imp',            "tb2" => 'Print', "vb2" => 'Print', "icon2" => 'print.PNG',
			"cb3" => 'dashboard',"mb3" => 'CGR',            "tb3" => 'graphe',"vb3" => 'graph',"icon3" => 'graph.PNG',
			"cb4" => 'dashboard',"mb4" => '',               "tb4" => 'Search',"vb4" => 'Search',"icon4" => 'search.PNG'
			);
			
			$this->smunuf($data);	
		}	
	//echo "</table>" ;
	echo "<br/>";echo "<br/>";
	// echo '</fieldset>';
	}
	function barre_navigation ($nb_total,$nb_affichage_par_page,$o,$q,$p,$nb_liens_dans_la_barre,$c,$m)//$c= controleure ,$m=methode
	{
	$barre = '';
	// 1 on recherche l'URL courante munie de ses paraméµ²e auxquels on ajoute le paraméµ²e'debut' qui jouera le role du premier ê­©ment de notre LIMIT
	$query = URL.$c.'/'.$m.'/'.$p.'/'.$nb_affichage_par_page.'?q='.$q.'&o='.$o.'';	 
	// on calcul le numê³¯ de la page active
	$page_active = floor(($p/$nb_affichage_par_page)+1);
	// on calcul le nombre de pages total que va prendre notre affichage
	$nb_pages_total = ceil($nb_total/$nb_affichage_par_page);
	// la fonction ceil arrondie au nbr sup
	// on calcul le premier numero de la barre qui va s'afficher, ainsi que le dernier($cpt_deb et $cpt_fin) 
	// exemple : 2 3 4 5 6 7 8 9 10 11 << $cpt_deb = 2 et $cpt_fin = 11
		if ($nb_liens_dans_la_barre%2==0) 
		{
			$cpt_deb1 = $page_active - ($nb_liens_dans_la_barre/2)+1;
			$cpt_fin1 = $page_active + ($nb_liens_dans_la_barre/2);
		}
		else 
		{
			$cpt_deb1 = $page_active - floor(($nb_liens_dans_la_barre/2));
			$cpt_fin1 = $page_active + floor(($nb_liens_dans_la_barre/2));
		}
		
		if ($cpt_deb1 <= 1) 
		{
			$cpt_deb = 1;
			$cpt_fin = $nb_liens_dans_la_barre;
		}
		elseif ($cpt_deb1>1 && $cpt_fin1<$nb_pages_total) 
		{
			$cpt_deb = $cpt_deb1;
			$cpt_fin = $cpt_fin1;
		}
		else 
		{
			$cpt_deb = ($nb_pages_total-$nb_liens_dans_la_barre)+1;
			$cpt_fin = $nb_pages_total;
		}
		
		if ($nb_pages_total <= $nb_liens_dans_la_barre) {
		$cpt_deb=1;
		$cpt_fin=$nb_pages_total;
		}
		// si le premier numê³¯ qui s'affiche est diffê³¥nt de 1, on affiche << qui sera unlien vers la premiere page
		if ($cpt_deb != 1) 
		{
			$cible = URL.$c.'/'.$m.'/'.(0).'/'.$nb_affichage_par_page.'?q='.$q.'&o='.$o.''; 
			$lien = '<A HREF="'.$cible.'">&lt;&lt;</A>&nbsp;&nbsp;';
		}
		else 
		{
		$lien='';
		}
		
		$barre .= $lien;

		// on affiche tous les liens de notre barre, tout en vê³©fiant de ne pas mettre delien pour la page active

		for ($cpt = $cpt_deb; $cpt <= $cpt_fin; $cpt++) 
		{
			if ($cpt == $page_active) 
			{
				if ($cpt == $nb_pages_total) {
				$barre .= $cpt;
				}
				else {
				$barre .= $cpt.'&nbsp;-&nbsp;';
				}
			}
			else 
			{
				if ($cpt == $cpt_fin) {
				$barre .= "<A HREF='".URL.$c.'/'.$m.'/'.(($cpt-1)*$nb_affichage_par_page).'/'.$nb_affichage_par_page.'?q='.$q.'&o='.$o.'';  
				$barre .= "'>".'['.$cpt.']'."</A>";
				}
				else {
				$barre .= "<A HREF='".URL.$c.'/'.$m.'/'.(($cpt-1)*$nb_affichage_par_page).'/'.$nb_affichage_par_page.'?q='.$q.'&o='.$o.'';  
				$barre .= "'>".'['.$cpt.']'."</A>&nbsp;-&nbsp;";
				}
			}
		}

		$fin = ($nb_total - ($nb_total % $nb_affichage_par_page));
		if (($nb_total % $nb_affichage_par_page) == 0) 
		{
		$fin = $fin - $nb_affichage_par_page;
		}
	   // si $cpt_fin ne vaut pas la dernié³¥ page de la barre de navigation, on afficheun >> qui sera un lien vers la dernié³¥ page de navigation

		if ($cpt_fin != $nb_pages_total) 
		{
		$cible = URL.$c.'/'.$m.'/'.$fin.'/'.$nb_affichage_par_page.'?q='.$q.'&o='.$o.''; 
		$lien = '&nbsp;&nbsp;<A HREF="'.$cible.'">&gt;&gt;</A>';
		}
		else {
		$lien='';
		}

		$barre .= $lien;

		return $barre;
	}   

	function valeurmois($SRS,$TBL,$COLONE1,$COLONE2,$DATEJOUR1,$DATEJOUR2,$VALEUR2,$STR) 
	{
	$cnx = mysql_connect($this->db_host,$this->db_user,$this->db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
	$db  = mysql_select_db($this->db_name,$cnx) ;
	$sql = " select * from $TBL  where $COLONE1 BETWEEN '$DATEJOUR1' AND '$DATEJOUR2' and  STRUCTURED $STR ";
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$OP=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $OP;
	}
    function graphemois($x,$y,$TITRE,$SRS,$TBL,$COLONE1,$COLONE2,$ANNEE,$IND,$STR) 
	{
	include "./chart/libchart/classes/libchart.php";
	$chart = new VerticalBarChart();
	$fichier='./chart/demo/generated/demo1.png';
	$dataSet = new XYDataSet();
	$dataSet->addPoint(new Point("JAN", $this->valeurmois($SRS,$TBL,$COLONE1,$COLONE2,$ANNEE."-01-01",$ANNEE."-01-31",$IND,$STR) ));
	$dataSet->addPoint(new Point("FEV", $this->valeurmois($SRS,$TBL,$COLONE1,$COLONE2,$ANNEE."-02-01",$ANNEE."-02-29",$IND,$STR)));
	$dataSet->addPoint(new Point("MAR", $this->valeurmois($SRS,$TBL,$COLONE1,$COLONE2,$ANNEE."-03-01",$ANNEE."-03-31",$IND,$STR)));
	$dataSet->addPoint(new Point("AVR", $this->valeurmois($SRS,$TBL,$COLONE1,$COLONE2,$ANNEE."-04-01",$ANNEE."-04-30",$IND,$STR)));
	$dataSet->addPoint(new Point("MAI", $this->valeurmois($SRS,$TBL,$COLONE1,$COLONE2,$ANNEE."-05-01",$ANNEE."-05-31",$IND,$STR)));
	$dataSet->addPoint(new Point("JUIN",$this->valeurmois($SRS,$TBL,$COLONE1,$COLONE2,$ANNEE."-06-01",$ANNEE."-06-30",$IND,$STR)));
	$dataSet->addPoint(new Point("JUIL",$this->valeurmois($SRS,$TBL,$COLONE1,$COLONE2,$ANNEE."-07-01",$ANNEE."-07-31",$IND,$STR)));
	$dataSet->addPoint(new Point("AOUT",$this->valeurmois($SRS,$TBL,$COLONE1,$COLONE2,$ANNEE."-08-01",$ANNEE."-08-31",$IND,$STR)));
	$dataSet->addPoint(new Point("SEP", $this->valeurmois($SRS,$TBL,$COLONE1,$COLONE2,$ANNEE."-09-01",$ANNEE."-09-30",$IND,$STR)));
	$dataSet->addPoint(new Point("OCT", $this->valeurmois($SRS,$TBL,$COLONE1,$COLONE2,$ANNEE."-10-01",$ANNEE."-10-31",$IND,$STR)));
	$dataSet->addPoint(new Point("NOV", $this->valeurmois($SRS,$TBL,$COLONE1,$COLONE2,$ANNEE."-11-01",$ANNEE."-11-30",$IND,$STR)));
	$dataSet->addPoint(new Point("DEC", $this->valeurmois($SRS,$TBL,$COLONE1,$COLONE2,$ANNEE."-12-01",$ANNEE."-12-31",$IND,$STR)));
	$chart->setDataSet($dataSet);
	$DATE=date("d-m-Y");
	$chart->setTitle($TITRE.$DATE);
	$chart->render($fichier);	
	echo "<div class=\"data\" style=\" position:absolute;left:".$x."px;top:".$y."px;\">";	 
	echo '<img alt="Pie chart"  src="'.URL.$fichier.'" style="border: 2px solid red;"/>';
	echo "</div>";
	}
    
	
	function valeureta($SRS,$TBL,$COLONE1,$COLONE2,$DATEJOUR1,$DATEJOUR2,$VALEUR2,$STR) 
	{
	$cnx = mysql_connect($this->db_host,$this->db_user,$this->db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
	$db  = mysql_select_db($this->db_name,$cnx) ;
	$sql = " select * from $TBL  where $COLONE1 BETWEEN '$DATEJOUR1' AND '$DATEJOUR2' and  STRUCTURED $STR ";
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$OP=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $OP;
	}
    function grapheeta($x,$y,$TITRE,$SRS,$TBL,$COLONE1,$COLONE2,$ANNEE,$IND) 
	{
	include "./chart/libchart/classes/libchart.php";
	$chart = new VerticalBarChart();
	$fichier='./chart/demo/generated/demo1.png';
	$dataSet = new XYDataSet();
	$dataSet->addPoint(new Point("EPH-MA", $this->valeureta($SRS,$TBL,$COLONE1,$COLONE2,$ANNEE."-01-01",$ANNEE."-12-31",$IND,'=4') ));
	$dataSet->addPoint(new Point("EPH-DJ", $this->valeureta($SRS,$TBL,$COLONE1,$COLONE2,$ANNEE."-01-01",$ANNEE."-12-31",$IND,'=1')));
	$dataSet->addPoint(new Point("EHS-DJ", $this->valeureta($SRS,$TBL,$COLONE1,$COLONE2,$ANNEE."-01-01",$ANNEE."-12-31",$IND,'=5')));
	$dataSet->addPoint(new Point("EPH-HB", $this->valeureta($SRS,$TBL,$COLONE1,$COLONE2,$ANNEE."-01-01",$ANNEE."-12-31",$IND,'=3')));
	$dataSet->addPoint(new Point("EPH-ID", $this->valeureta($SRS,$TBL,$COLONE1,$COLONE2,$ANNEE."-01-01",$ANNEE."-12-31",$IND,'=6')));
	$dataSet->addPoint(new Point("EPH-AO",$this->valeureta($SRS,$TBL,$COLONE1,$COLONE2,$ANNEE."-01-01",$ANNEE."-12-31",$IND,'=2')));
	$chart->setDataSet($dataSet);
	$DATE=date("d-m-Y");
	$chart->setTitle($TITRE.$DATE);
	$chart->render($fichier);	
	echo "<div class=\"data\" style=\" position:absolute;left:".$x."px;top:".$y."px;\">";	 
	echo '<img alt="Pie chart"  src="'.URL.$fichier.'" style="border: 2px solid red;"/>';
	echo "</div>";
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//multigraphe qui prend en charge la structure
	function valeurmultigraphe($TBL,$COLONE1,$DATEJOUR1,$DATEJOUR2,$COLONE2,$VALEUR2,$structure) 
	{
	$cnx = mysql_connect($this->db_host,$this->db_user,$this->db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
	$db  = mysql_select_db($this->db_name,$cnx) ;
	$sql = " select $COLONE1,$COLONE2 from $TBL where STRUCTURE $structure	and	$COLONE1 BETWEEN '$DATEJOUR1' AND '$DATEJOUR2'  AND $COLONE2='$VALEUR2' ";
	$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
	$OP=mysql_num_rows($requete);
	mysql_free_result($requete);
	return $OP;
	}
	function multigraphe($x,$y,$TITRE,$TBL,$COL,$COLONE,$VALEUR1,$VALEUR2,$structure) //,$data$data[$DATE-4]
	{
	include "./CHART/libchart/classes/libchart.php";
	$chart = new VerticalBarChart();
	$dataSet = new XYSeriesDataSet();
	$fichier='./CHART/demo/generated/demo7.png';
	$DATE=date("Y");
	$serie1 = new XYDataSet();
	
	$serie1->addPoint(new Point($DATE-9,$this->valeurmultigraphe($TBL,$COL,($DATE-9)."-01-01",($DATE-9)."-12-31",$COLONE,$VALEUR1,$structure)));
	$serie1->addPoint(new Point($DATE-8,$this->valeurmultigraphe($TBL,$COL,($DATE-8)."-01-01",($DATE-8)."-12-31",$COLONE,$VALEUR1,$structure)));
	$serie1->addPoint(new Point($DATE-7,$this->valeurmultigraphe($TBL,$COL,($DATE-7)."-01-01",($DATE-7)."-12-31",$COLONE,$VALEUR1,$structure)));
	$serie1->addPoint(new Point($DATE-6,$this->valeurmultigraphe($TBL,$COL,($DATE-6)."-01-01",($DATE-6)."-12-31",$COLONE,$VALEUR1,$structure)));
	$serie1->addPoint(new Point($DATE-5,$this->valeurmultigraphe($TBL,$COL,($DATE-5)."-01-01",($DATE-5)."-12-31",$COLONE,$VALEUR1,$structure)));
	$serie1->addPoint(new Point($DATE-4,$this->valeurmultigraphe($TBL,$COL,($DATE-4)."-01-01",($DATE-4)."-12-31",$COLONE,$VALEUR1,$structure)));
	$serie1->addPoint(new Point($DATE-3,$this->valeurmultigraphe($TBL,$COL,($DATE-3)."-01-01",($DATE-3)."-12-31",$COLONE,$VALEUR1,$structure)));
	$serie1->addPoint(new Point($DATE-2,$this->valeurmultigraphe($TBL,$COL,($DATE-2)."-01-01",($DATE-2)."-12-31",$COLONE,$VALEUR1,$structure)));
	$serie1->addPoint(new Point($DATE-1,$this->valeurmultigraphe($TBL,$COL,($DATE-1)."-01-01",($DATE-1)."-12-31",$COLONE,$VALEUR1,$structure)));
	$serie1->addPoint(new Point($DATE-0,$this->valeurmultigraphe($TBL,$COL,($DATE-0)."-01-01",($DATE-0)."-12-31",$COLONE,$VALEUR1,$structure)));
	$dataSet->addSerie($VALEUR1, $serie1);
	
	$serie2 = new XYDataSet();
	$serie2->addPoint(new Point($DATE-9, $this->valeurmultigraphe($TBL,$COL,($DATE-9)."-01-01",($DATE-9)."-12-31",$COLONE,$VALEUR2,$structure)));
	$serie2->addPoint(new Point($DATE-8, $this->valeurmultigraphe($TBL,$COL,($DATE-8)."-01-01",($DATE-8)."-12-31",$COLONE,$VALEUR2,$structure)));
	$serie2->addPoint(new Point($DATE-7, $this->valeurmultigraphe($TBL,$COL,($DATE-7)."-01-01",($DATE-7)."-12-31",$COLONE,$VALEUR2,$structure)));
	$serie2->addPoint(new Point($DATE-6, $this->valeurmultigraphe($TBL,$COL,($DATE-6)."-01-01",($DATE-6)."-12-31",$COLONE,$VALEUR2,$structure)));
	$serie2->addPoint(new Point($DATE-5, $this->valeurmultigraphe($TBL,$COL,($DATE-5)."-01-01",($DATE-5)."-12-31",$COLONE,$VALEUR2,$structure)));
	$serie2->addPoint(new Point($DATE-4, $this->valeurmultigraphe($TBL,$COL,($DATE-4)."-01-01",($DATE-4)."-12-31",$COLONE,$VALEUR2,$structure)));
	$serie2->addPoint(new Point($DATE-3, $this->valeurmultigraphe($TBL,$COL,($DATE-3)."-01-01",($DATE-3)."-12-31",$COLONE,$VALEUR2,$structure)));
	$serie2->addPoint(new Point($DATE-2, $this->valeurmultigraphe($TBL,$COL,($DATE-2)."-01-01",($DATE-2)."-12-31",$COLONE,$VALEUR2,$structure)));
	$serie2->addPoint(new Point($DATE-1, $this->valeurmultigraphe($TBL,$COL,($DATE-1)."-01-01",($DATE-1)."-12-31",$COLONE,$VALEUR2,$structure)));
	$serie2->addPoint(new Point($DATE-0, $this->valeurmultigraphe($TBL,$COL,($DATE-0)."-01-01",($DATE-0)."-12-31",$COLONE,$VALEUR2,$structure)));
	$dataSet->addSerie($VALEUR2, $serie2);
	
	$chart->setDataSet($dataSet);
	$chart->getPlot()->setGraphCaptionRatio(0.65);

	$chart->setTitle($TITRE.date("d-m-Y"));
	$chart->render($fichier);	
	// echo "<div class=\"data\" style=\" position:absolute;left:".$x."px;top:".$y."px;\">";	
	echo '<img id ="graphe"  alt="Pie chart"  src="'.URL.$fichier.'" style="border: 2px solid red;"/>';
	// echo "</div>";
	}
	
	
	
	
	
	
	
	
	
	public function WILAYA($name,$class,$value,$selected) 
	{
	$cnx = mysql_connect($this->db_host,$this->db_user,$this->db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
	$db  = mysql_select_db($this->db_name,$cnx) ;		 
	echo "<select size=1 id=\"wil\" class=\"".$class."\" name=\"".$name."\">"."\n";
	echo"<option value=\"".$value."\"  selected=\"selected\">".$selected."</option>"."\n";
	mysql_query("SET NAMES 'UTF8' ");
	$result = mysql_query("SELECT * FROM wil order by WILAYAS" );
	while($data =  mysql_fetch_array($result))
	{
	echo '<option value="'.$data[0].'">'.$data[1].'</option>';
	}
	echo '</select>'."\n"; 
	}
	function COMMUNE($name,$class,$value,$selected) 
	{	 
	echo "<select size=1 id=\"com\"  class=\"".$class."\" name=\"".$name."\">"."\n";
	echo"<option value=\"".$value."\" selected=\"selected\">".$selected."</option>"."\n";
	echo '</select>'."\n";
	}
	
	function STRUCTURE($name,$class,$value,$selected) 
	{	 
	echo "<select size=1 id=\"com\"  class=\"".$class."\" name=\"".$name."\">"."\n";
	echo"<option value=\"".$value."\" selected=\"selected\">".$selected."</option>"."\n";
	echo '</select>'."\n";
	}
	function MED($x,$y,$name,$db_name,$tb_name,$structure,$value,$selected) 
	{
	//echo "<div class=\"data\" style=\" position:absolute;left:".$x."px;top:".$y."px;\">";	
	$cnx = mysql_connect($this->db_host,$this->db_user,$this->db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
	$db  = mysql_select_db($this->db_name,$cnx) ; 
	echo "<select size=1 id=\"MEDECINHOSPIT\" name=\"".$name."\">"."\n";
	echo"<option value=\"".$value."\"  selected=\"selected\">".$selected."</option>"."\n";
	$result = mysql_query("SELECT * FROM $tb_name  where structure=$structure  order by Nom" );
	while($data =  mysql_fetch_array($result))
	{
	//echo '<option value="'.$data['id'].'">'.$data['Nom'].'_'.$data['Prenom'].'</option>';
	echo '<option value="'.$data['Nom'].'_'.$data['Prenom'].'">'.$data['Nom'].'_'.$data['Prenom'].'</option>';
	}
	echo '</select>'."\n"; 
	//echo "</div>";
	}
    function MDO($name,$tb_name,$value,$selected) 
	{
	$cnx = mysql_connect($this->db_host,$this->db_user,$this->db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
	$db  = mysql_select_db($this->db_name,$cnx) ; 
	echo "<select size=1 id=\"MEDECINHOSPIT\" name=\"".$name."\">"."\n";
	echo"<option value=\"".$value."\"  selected=\"selected\">".$selected."</option>"."\n";
	$result = mysql_query("SELECT id,mdo FROM $tb_name order by mdo" );
	while($data =  mysql_fetch_array($result))
	{
	$foo = ucwords(strtolower($data['mdo'])); 
	echo '<option value="'.$data['id'].'">'.$foo.'</option>';
	}
	echo '</select>'."\n"; 
	}
	
	
	
	
	
	function Profession($x,$y,$name,$db_name,$tb_name,$structure,$value,$selected) 
	{
	//echo "<div class=\"data\" style=\" position:absolute;left:".$x."px;top:".$y."px;\">";	
	$cnx = mysql_connect($this->db_host,$this->db_user,$this->db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
	$db  = mysql_select_db($this->db_name,$cnx) ; 
	echo "<select size=1 id=\"Profession\" name=\"".$name."\">"."\n";
	echo"<option value=\"".$value."\"  selected=\"selected\">".$selected."</option>"."\n";
	$result = mysql_query("SELECT * FROM $tb_name order by Profession" );
	while($data =  mysql_fetch_array($result))
	{
	echo '<option value="'.$data['id'].'">'.$data['Profession'].'</option>';
	}
	echo '</select>'."\n"; 
	//echo "</div>";
	}
	
	
	
	function SER($x,$y,$name,$db_name,$tb_name,$value,$selected) 
	{
	//echo "<div class=\"data\" style=\" position:absolute;left:".$x."px;top:".$y."px;\">";	
	$cnx = mysql_connect($this->db_host,$this->db_user,$this->db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
	$db  = mysql_select_db($this->db_name,$cnx) ; 
	echo "<select size=1 class=\"SERVICEHOSPIT\" name=\"".$name."\">"."\n";
	echo"<option value=\"".$value."\"  selected=\"selected\">".$selected."</option>"."\n";
	$result = mysql_query("SELECT * FROM $tb_name  " );
	while($data =  mysql_fetch_array($result))
	{
	echo '<option value="'.$data['id'].'">'.$data['service'].'</option>';
	}
	echo '</select>'."\n"; 
	//echo "</div>";
	}
	function cim1($name,$db_name,$tb_name,$value,$selected) 
	{
	$cnx = mysql_connect($this->db_host,$this->db_user,$this->db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
	$db  = mysql_select_db($this->db_name,$cnx) ; 
	 
	echo "<select size=1 class=\"cim1\" name=\"".$name."\">"."\n";
	echo"<option value=\"".$value."\"  selected=\"selected\">".$selected."</option>"."\n";
	mysql_query("SET NAMES 'UTF8' ");
    $result = mysql_query("SELECT * FROM chapitre " );
    while($data =  mysql_fetch_array($result))
    {
    echo '<option value="'.$data[0].'">'.'['.$data[0].'] '.$data[1].'</option>';
    }
	echo '</select>'."\n"; 
	
	}
	function cim2($name,$value,$selected) 
	{
	 
	echo "<select size=1 class=\"cim2\" name=\"".$name."\">"."\n";
	echo"<option value=\"".$value."\" selected=\"selected\">".$selected."</option>"."\n";
    echo '</select>'."\n"; 
	
	}
	
	
	function stringtostring($tb_name,$colonename,$colonevalue,$resultatstring) 
	{
	if ($colonevalue!=='') 
		{
		$cnx = mysql_connect($this->db_host,$this->db_user,$this->db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
	    $db  = mysql_select_db($this->db_name,$cnx) ;
		$result = mysql_query("SELECT * FROM $tb_name where $colonename='$colonevalue'" );
		$row=mysql_fetch_object($result);
		$resultat=$row->$resultatstring;
		return $resultat;
		}
		else
		{
		return $resultat2='??????';
		}
	}
	function nbrtostring($tb_name,$colonename,$colonevalue,$resultatstring) 
	{
		if (is_numeric($colonevalue) and $colonevalue!=='-1') 
		{ 
		$cnx = mysql_connect($this->db_host,$this->db_user,$this->db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
	    $db  = mysql_select_db($this->db_name,$cnx) ;
		$result = mysql_query("SELECT * FROM $tb_name where $colonename=$colonevalue" );
		$row=mysql_fetch_object($result);
		$resultat=$row->$resultatstring;
		return $resultat;
		}
        else
        {
		return $resultat2='??????';
		}		
	}
	
	function datePlus($dateDo,$nbrJours)
	{
	$timeStamp = strtotime($dateDo); 
	$timeStamp += 24 * 60 * 60 * $nbrJours;
	$newDate = date("Y-m-d", $timeStamp);
	return  $newDate;
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
	function RACE($name,$class,$value,$selected) 
	{
	$cnx = mysql_connect($this->db_host,$this->db_user,$this->db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
	$db  = mysql_select_db($this->db_name,$cnx) ;
	echo "<select id=\"Race\"   size=1 id=\"wil\" class=\"".$class."\" name=\"".$name."\">"."\n";
	echo"<option value=\"".$value."\"  selected=\"selected\">".$selected."</option>"."\n";
	mysql_query("SET NAMES 'UTF8' ");
	$result = mysql_query("SELECT * FROM race where id != 1  order by race" );
	while($data =  mysql_fetch_array($result))
	{
	echo '<option value="'.$data[0].'">'.$data[1].'</option>';
	}
	echo '</select>'."\n"; 
	}
	
	function ROBE($name,$class,$value,$selected) 
	{
	$cnx = mysql_connect($this->db_host,$this->db_user,$this->db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
	$db  = mysql_select_db($this->db_name,$cnx) ;
	echo "<select id=\"Couleur\"   size=1 id=\"wil\" class=\"".$class."\" name=\"".$name."\">"."\n";
	echo"<option value=\"".$value."\"  selected=\"selected\">".$selected."</option>"."\n";
	mysql_query("SET NAMES 'UTF8' ");
	$result = mysql_query("SELECT * FROM robe  where id != 1 order by robe" );
	while($data =  mysql_fetch_array($result))
	{
	echo '<option value="'.$data[0].'">'.$data[1].'</option>';
	}
	echo '</select>'."\n"; 
	}
	function REGION($name,$class,$value,$selected) 
	{
	$cnx = mysql_connect($this->db_host,$this->db_user,$this->db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
	$db  = mysql_select_db($this->db_name,$cnx) ;
	echo "<select id=\"region\" class=\"".$class."\" name=\"".$name."\">"."\n";
	echo"<option value=\"".$value."\"  selected=\"selected\">".$selected."</option>"."\n";
	mysql_query("SET NAMES 'UTF8' ");
	$result = mysql_query("SELECT * FROM region  where id != 1 order by region" );
	while($data =  mysql_fetch_array($result))
	{
	echo '<option value="'.$data[0].'">'.$data[1].'</option>';
	}
	echo '</select>'."\n"; 
	}
	function WREGION($name,$class,$value,$selected) 
	{
	echo "<select id=\"region\"  class=\"".$class."\" name=\"".$name."\">"."\n";
	echo"<option value=\"".$value."\" selected=\"selected\">".$selected."</option>"."\n";
	echo '</select>'."\n";
	}
	
	function STATION($name,$class,$value,$selected) 
	{
	echo "<select id=\"region\" class=\"".$class."\" name=\"".$name."\">"."\n";
	echo"<option value=\"".$value."\" selected=\"selected\">".$selected."</option>"."\n";
	echo '</select>'."\n";
	}
	
	function STATIONT($name,$class,$value,$selected) 
	{
	$cnx = mysql_connect($this->db_host,$this->db_user,$this->db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
	$db  = mysql_select_db($this->db_name,$cnx) ;		 
	echo "<select size=1 id=\"wil\" class=\"".$class."\" name=\"".$name."\">"."\n";
	echo"<option value=\"".$value."\"  selected=\"selected\">".$selected."</option>"."\n";
	mysql_query("SET NAMES 'UTF8' ");
	$result = mysql_query("SELECT * FROM station order by station" );
	while($data =  mysql_fetch_array($result))
	{
	echo '<option value="'.$data[0].'">'.$data[1].'</option>';
	}
	echo '</select>'."\n"; 
	}
	
	
	function EQUIN($name,$class,$value,$selected,$sexe) 
	{
	$cnx = mysql_connect($this->db_host,$this->db_user,$this->db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
	$db  = mysql_select_db($this->db_name,$cnx) ;	 
	echo "<select size=1 id=\"wil\" class=\"".$class."\" name=\"".$name."\">"."\n";
	echo"<option value=\"".$value."\"  selected=\"selected\">".$selected."</option>"."\n";
	mysql_query("SET NAMES 'UTF8' ");
	$result = mysql_query("SELECT * FROM cheval where Sexe='$sexe' order by NomA" );
	while($data =  mysql_fetch_array($result))
	{
	echo '<option value="'.$data[0].'">'.$data[7].'</option>';
	}
	echo '</select>'."\n"; 
	}
	function EQUINT($name,$class,$value,$selected) 
	{
	$cnx = mysql_connect($this->db_host,$this->db_user,$this->db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
	$db  = mysql_select_db($this->db_name,$cnx) ;	 
	echo "<select size=1 id=\"wil\" class=\"".$class."\" name=\"".$name."\">"."\n";
	echo"<option value=\"".$value."\"  selected=\"selected\">".$selected."</option>"."\n";
	mysql_query("SET NAMES 'UTF8' ");
	$result = mysql_query("SELECT * FROM cheval  order by NomA" );
	while($data =  mysql_fetch_array($result))
	{
	echo '<option value="'.$data[0].'">'.$data[7].'('.$data[9].')'.'</option>';
	}
	echo '</select>'."\n"; 
	}
	function BILAN($name,$class,$value,$selected) 
	{
	$cnx = mysql_connect($this->db_host,$this->db_user,$this->db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
	$db  = mysql_select_db($this->db_name,$cnx) ;
	echo "<select id=\"wil\"   size=1 id=\"wil\" class=\"".$class."\" name=\"".$name."\">"."\n";
	echo"<option value=\"".$value."\"  selected=\"selected\">".$selected."</option>"."\n";
	mysql_query("SET NAMES 'UTF8' ");
	$result = mysql_query("SELECT * FROM bilansbiologiques   order by bilansbiologique" );
	while($data =  mysql_fetch_array($result))
	{
	echo '<option value="'.$data[0].'">'.$data[1].'</option>';
	}
	echo '</select>'."\n"; 
	}
	
	
	
	
	//**************************************************************************************************************************//
    function TblStart ($bordure = "1", $width = -1, $height = -1, $espCell = "2",$rempCell = "4", $bordercolor = -1, $class = -1)
    {
        $optionClasse = "" ;
        $optionWidth = "" ;
        $optionHeight = "" ;
        if ($bordercolor != -1)
            $optionClasse = " BORDERCOLOR =\"$bordercolor\"" ;
        if ($class != -1)
            $optionClasse .= " CLASS =\"$class\"" ;
        if ($width != -1)
            $optionWidth = " WIDTH =\"$width\"" ;
        if ($height != -1)
            $optionHeight = " HEIGHT =\"$height\"" ;

        echo "<TABLE BORDER =\"$bordure\"" . " CELLSPACING =\"$espCell\" CELLPADDING =\"$rempCell\" " . $optionWidth . $optionHeight . $optionClasse . ">\n" ;
    } 
    
    /**
     * function to close the table tag
     *
     */
    
    function TblEnd()
    {
        echo "</TABLE>\n" ;
    } 
    
    
    /**
     * Function to close the tag <table>
     *
     * @param string $align
     * @param string $bg
     * @param string $action
     * @param string $class
     */
    
    function TblStartLine($align = -1, $bg = -1, $action = -1, $class = -1)
    {
        $optionClasse = "" ;
        switch ($align) {
            case "bottom" :
                $optionClasse .= " VALIGN =\"$align\"" ;
                break ;
            case "top" :
                $optionClasse .= " VALIGN =\"$align\"" ;
                break ;
            case "left" :
                $optionClasse .= " ALIGN =\"$align\"" ;
                break ;
            case "right" :
                $optionClasse .= " ALIGN =\"$align\"" ;
                break ;
            case "center" :
                $optionClasse .= " ALIGN =\"$align\"" ;
                break ;
        }
        // choose between the background image or color
        if ($bg != -1)
        {
            if ($bg[0] != "#")
                $optionClasse .= " BACKGROUND =\"$bg\"" ;
            else
                $optionClasse .= " BGCOLOR =\"$bg\"" ;
        }
        if ($class != -1)
            $optionClasse .= " CLASS =\"$class\"" ;

        if($action != -1)
                echo "<TR" . $optionClasse . " " . $action . ">\n";
        else
                echo "<TR" . $optionClasse . ">\n";
    }
    
    /**
     * function to close the tag of the new line
     *
     */
    
    function TblEndLine()
    {
        echo "</TR>\n" ;
    } 
    
    
    /**
     * the tag to add an entete in the table
     *
     * @param string $content
     * @param string $bg
     * @param integer $nbLig
     * @param integer $nbCol
     */
    
    function TblEntete($content, $bg = -1, $nbLig = 1, $nbCol = 1)
    {
        if ($bg != -1)
            $optionClasse = " BACKGROUND =\"$bg\"" ;
        echo "<TH" . $optionClasse . " ROWSPAN =\"$nbLig\" COLSPAN =\"$nbCol\">$content</TH>\n" ;
    }
    
    /**
     * the function to start a new cell only
     *
     * @param integer $width
     * @param integer $height
     * @param string $bg
     * @param string $align
     * @param integer $nbLig
     * @param integer $nbCol
     * @param string $class
     */
    
    function TblStartCell($width = -1, $height = -1, $bg = -1, $align = -1, $nbLig = -1, $nbCol = -1, $class = -1)
    {
        $optionClasse = "" ;
        if ($width != -1)
            $optionClasse = " WIDTH =\"$width\"" ;
        if ($height != -1)
            $optionClasse .= " HEIGHT =\"$height\"" ;
        // choose between the background image or color
        if ($bg != -1) {
            if ($bg[0] != "#")
                $optionClasse .= " BACKGROUND =\"$bg\"" ;
            else
                $optionClasse .= " BGCOLOR =\"$bg\"" ;
        } 

        if ($align != -1) {
            switch ($align) {
                case "center" :
                    $optionClasse .= " ALIGN =\"$align\"" ;
                    break ;
                case "bottom" :
                    $optionClasse .= " VALIGN =\"$align\"" ;
                    break ;
                case "top" :
                    $optionClasse .= " VALIGN =\"$align\"" ;
                    break ;
                case "left" :
                    $optionClasse .= " ALIGN =\"$align\"" ;
                    break ;
                case "right" :
                    $optionClasse .= " ALIGN =\"$align\"" ;
            } 
        } 
        if ($nbLig != -1)
            $optionClasse .= " ROWSPAN =\"$nbLig\"" ;
        if ($nbCol != -1)
            $optionClasse .= " COLSPAN =\"$nbCol\"" ;
        if ($class != -1)
            $optionClasse .= " CLASS =\"$class\"" ;
        echo "<TD" . $optionClasse . ">" ;
    } 
    
    /**
     * the tag to close the new cell
     *
     */
    
    function TblEndCell()
    {
        echo "</TD>\n" ;
    } 
   
    /**
     * tag just to add a new cell
     *
     * @param string $contenu
     * @param integer $width
     * @param integer $height
     * @param string $bg
     * @param integer $align
     * @param integer $nbLig
     * @param integer $nbCol
     * @param string $class
     */
    
    function TblCell($contenu, $width = -1, $height = -1, $bg = -1, $align = -1, $nbLig = -1, $nbCol = -1, $class = -1)
    {
        $options = "" ;
        $optionClasse = "" ;
        // choose between the background image or color
        if ($bg != -1) {
            if ($bg[0] != "#")
                $optionClasse .= " BACKGROUND =\"$bg\"" ;
            else
                $optionClasse .= " BGCOLOR =\"$bg\"" ;
        } 

        switch ($align) {
            case "bottom" :
                $options .= " VALIGN =\"$align\"" ;
                break ;
            case "top" :
                $options .= " VALIGN =\"$align\"" ;
                break ;
            case "left" :
                $options .= " ALIGN =\"$align\"" ;
                break ;
            case "right" :
                $options .= " ALIGN =\"$align\"" ;
                break ;
            case "center" :
                $options .= " ALIGN =\"$align\"" ;
                break ;
        }
        if ($width != -1)
            $options .= " WIDTH =\"$width\"" ;
        if ($height != -1)
            $options .= " HEIGHT =\"$height\"" ;
        if ($class != -1)
            $options .= " CLASS =\"$class\"" ;
        if ($nbLig != -1)
            $options .= " ROWSPAN =\"$nbLig\"" ;
        if ($nbCol != -1)
            $options .= " COLSPAN =\"$nbCol\"" ;

        echo "   <TD$options>$contenu</TD>\n" ;
    }
    
    /**
     * All the HTML tags from the HTML 4. The anchor tag defines either a link or an anchor in a document. The anchor tag must contain either a NAME attribute or an HREF attribute, or both.
     *
     * @param string $url
     * @param string $text
     * @param string $class
     */
    
    function Anchor($url, $text, $class = -1)
    {
        $optionClasse = "" ;
        if ($class != -1)$optionClasse = " CLASS =\"$class\"" ;
        echo "<A HREF =\"$url\"" . "$optionClasse>$text</A>" ;        
    } 
    
    /**
     * The inline image tag displays an image referred to by a URL. It must contain at least an SRC attribute.
     *
     * @param string $url
     * @param integer $width
     * @param integer $height
     * @param integer $border
     * @param string $title
     * @param integer $map
     * @param string $name
     * @param string $class
     */
    
    function Image($url, $width = -1, $height = -1, $border = -1, $title = -1, $map = -1, $name = -1, $class = -1, $id = -1)
    {
        $options = "" ;
        if ($width != -1)
            $options = " WIDTH =\"$width\"" ;
        if ($height != -1)
            $options .= " HEIGHT =\"$height\"" ;
        if ($border != -1)
            $options .= " BORDER =\"$border\"" ;
        if ($title != -1)
            $options .= " TITLE =\"$title\"" ;
        if ($map != -1)
            $options .= " USEMAP =\"#$map\"" ;
        if ($name != -1)
            $options .= " NAME =\"$name\"" ;
        if ($class != -1)
            $options .= " CLASS =\"$class\"" ;
        if ($id != -1)
            $options .= " id =\"$id\"" ; 
        echo "<IMG  SRC =\"$url\"" . $options . ">" ;
    } 
    
    
    /**
     * The comment tag includes the actual comment text
     *
     * @param string $text
     */
    
    function Comment($text)
    {
        echo "<!-- $text -->\n" ;
    } 
    
    
    /**
     * The <A HREF> tag is used to link pages together It must contain both parameteres
     *
     * @param string $url
     * @param string $txtLinks
     * @param string $action
     * @param string $class
     */
    
    function A($url, $txtLinks, $action = -1, $class = -1)
    {
        $optionclasse = "" ;
        if($class != -1)
            $optionclasse = " CLASS=\"$class\"" ;
        if($action != -1)
                echo "<A HREF=\"$url\" " . $action . $optionclasse . ">$txtLinks</a>" ;
        else
                echo "<A HREF=\"$url\" " . $optionclasse . ">$txtLinks</a>" ;
    }
    
    
    /**
     * The <A HREF> tag is used to link pages together It must contain both parameteres
     *
     * @param string $url
     * @param string $action
     * @param string $class
     */
    
    function AStart($url, $action = -1, $class = -1)
    {
        $optionclasse = "" ;
        if($class != -1)
            $optionclasse = " CLASS=\"$class\"" ;
        if($action != -1)
                echo "<A HREF=\"$url\" " . $action . $optionclasse .">" ;
        else
                echo "<A HREF=\"$url\" " . $optionclasse . ">" ;
    }

    
    /**
     * The tag is made to close the <A HREF> tag
     *
     */
    
    function AEnd()
    {
        echo "</A>" ;
    }
    
    
    /**
     * put the text in bold
     *
     * @param string $text
     * @param string $class
     */
    
    function Bold($text, $class = -1)
    {
        $optionclasse = "" ;
        if($class != -1)
            $optionclasse = " CLASS=\"$class\"" ;

        echo "<B " . $optionclasse . ">$text</B>" ;
    } 
    
    
    /**
     * to do a line return
     *
     * @param integer $nbr
     */
    
    function Br($nbr = -1)
    {
        if($nbr != -1)
            {
                for($i = 0 ; $i < $nbr; $i++)
                echo "<BR>" ;
            }
        else
            echo "<BR>" ;
    } 
    
    
    /**
     * The form tag introduces a form, which is made up of INPUT elements, described in the sections that follow. A form may be inside structural HTML tags and may also contain structural tags. Using tables and other elements a form can take on various shapes and looks
     *
     * @param string $action
     * @param string $method
     * @param string $class
     */
    
    function Form($action, $method = -1, $class = -1)
    {
        $optionclasse = "" ;
        $attrMethod = "" ;
        if($class != -1)
            $optionclasse = " CLASS=\"$class\"" ;
        if ($method != -1)
            $attrMethod = " METHOD =\"$method\"" ;

        echo "<FORM ACTION =\"$action\"" . $attrMethod . $optionclasse . ">\n" ;
    } 
    
    
    /**
     * function to close the tag form
     *
     */
    
    function FormEnd()
    {
        echo "</FORM>\n" ;
    } 
    
    
    /**
     * The select tag specifies a multiple line selection box field within the form that contains it. The user can select one or more lines if the attribute MULTIPLE is specified. The NAME attribute is a required field and is used to identify the data for the field. The SIZE attribute specifies the number of lines of selections that are to be displayed
     *
     * @param string $text
     * @param string $name
     * @param integer $selected
     * @param integer $size
     * @param string $id
     * @param string $class
     */
    
    function Select($text = array(), $name = -1, $selected = -1, $size = -1, $id = -1, $class = -1)
    {
        $options = "" ;
        $attrSelected = "" ;
        if ($name != -1)
            $options = " NAME =\"$name\"" ;
        if ($size != -1)
            $options .= " SIZE =\"$size\"" ;
        if ($id != -1)
            $options .= " ID =\"$id\"" ;
        if ($class != -1)
            $options .= " CLASS =\"$class\"" ;

        echo "<SELECT" . $options . ">\n" ;

        for($i = 0 ; $i < sizeof($text) ; $i++) {
            if ($i == $selected-1)
                $attrSelected = " SELECTED" ;

            echo "  <OPTION" . $attrSelected . ">$text[$i]</OPTION>\n" ;
        } 

        echo "</SELECT>\n" ;
    }
    
    /**
     * the input tag must be between to form tags and it must contain the type, text, password, checkbox, radio submit, reset, file, hidden, image or button
     *
     * @param string $type
     * @param string $name
     * @param string $value
     * @param integer $size
     * @param integer $maxLength
     * @param integer $checked
     * @param string $title
     * @param string $id
     * @param string $class
     */
    
    function Input($type = -1, $name = -1, $value = -1, $size = -1, $maxLength = -1, $checked = -1, $title = -1, $id = -1, $class = -1)
    {
        $options = "" ;
        if ($type != -1)
            $options = " TYPE =\"$type\"" ;
        if ($name != -1)
            $options .= " NAME =\"$name\"" ;
        if ($value != -1)
            $options .= " VALUE =\"$value\"" ;
        if ($size != -1)
            $options .= " SIZE =\"$size\"" ;
        if ($maxLength != -1)
            $options .= " MAXLENGTH =\"$maxLength\"" ;
        if ($checked != -1)
            $options .= " CHECKED" ;
        if ($title != -1)
            $options .= " TITLE =\"$title\"" ;
        if ($id != -1)
            $options .= " ID =\"$id\"" ;
        if ($class != -1)
            $options .= " CLASS =\"$class\"" ;

        echo "<INPUT" . $options . ">" ;
    } 
    
    /**
     * The text area tag specifies a multiple line text area field within the form that contains it. The NAME attribute is a required field and is used to identify the data for the field. The COLS attribute specifies the width in characters of the text area. The ROWS attribute specifies the number of lines the text area contains. The content is used as an initial value for the field. The field can be scrolled beyond the COLS and ROWS size to allow for larger amounts of text to be entered. The wrap attribute can have values of OFF, SOFT and HARD
     *
     * @param string $text
     * @param string $name
     * @param integer $cols
     * @param integer $rows
     * @param string $wrap
     * @param string $class
     */
    
    function TextArea($text, $name = -1, $cols = -1, $rows = -1, $wrap = -1, $class = -1)
    {
        $options = "" ;
        if ($name != -1)
            $options = " NAME =\"$name\"" ;
        if ($cols != -1)
            $options .= " COLS =\"$cols\"" ;
        if ($rows != -1)
            $options .= " ROWS =\"$rows\"" ;
        if ($wrap != -1)
            $options .= " WRAP =\"$wrap\"" ;
        if ($class != -1)
            $options .= " CLASS =\"$class\"" ;

        echo "<TEXTAREA " . $options . ">$text</TEXTAREA>\n" ;
    } 
    
    
    // 
    /**
     * The heading tag defines a level heading. It is typically shown in a very large bold font with several blank lines around it
     *
     * @param string $text
     * @param integer $size
     */
    
    function H($text, $size = -1)
    {
        $attrSize = "" ;
        if ($size != -1)
            $attrSize = $size ;
        echo "<H$attrSize>$text</H$attrSize>" ;
    }
    
    /**
     * The horizontal rule tag causes a horizontal line to be drawn across the screen. There is no </HR> tag
     *
     * @param string $align
     * @param integer $width
     * @param integer $size
     * @param integer $shade
     * @param string $color
     * @param string $class
     */
    
    function Hr($align = -1, $width = -1, $size = -1, $shade = -1, $color = -1, $class = -1)
    {
        $options = "" ;
        if ($align != -1)
            $options = "ALIGN =\"$align\"" ;
        if ($size != -1)
            $options .= " SIZE =\"$size\"" ;
        if ($width != -1)
            $options .= " WIDTH =\"$width\"" ;
        if ($shade == 1)
            $options .= " NOSHADE" ;
        if ($color != -1)
            $options .= " COLOR =\"$color\"" ;
        if ($class != -1)
            $options .= " CLASS =\"$class\"" ;

        echo "<HR " . $options . ">\n" ;
    }
    
    /**
     * The italic tag defines text that should be shown in italics
     *
     * @param string $text
     */
    
    function I($text)
    {
        echo "<I>$text</I>" ;
    } 
    
    /**
     * The unordered list tag introduces an unordered (bulleted) list, which is made up of List Item (LI) tags
     *
     * @param string $type
     * @param string $text
     * @param string $value
     * @param string $class
     */
    
    function Ul($type = -1, $text = array(), $value = -1, $class = -1)
    {
        $options = "" ;
        if ($type != -1)
            $options = " TYPE =\"$type\"" ;
        if ($value != -1)
            $options .= " VALUE =\"$value\"" ;
        if ($class != -1)
            $options .= " CLASS =\"$class\"" ;

        echo "<UL" . $options . ">\n" ;

        for($i = 0 ; $i < sizeof($text) ; $i++)
        echo "  <LI>$text[$i]\n" ;

        echo "</UL>\n" ;
    }
    
    /**
     * The ordered list tag introduces an ordered (numbered) list, which is made up of List Item (LI) tags the type of bullets are 1, a, i, A, I
     *
     * @param string $type
     * @param string $text
     * @param string $start
     * @param string $value
     * @param string $class
     */
    
    function Ol($type = -1, $text = array(), $start = -1, $value = -1, $class = -1)
    {
        $options = "" ;
        if ($type != -1)
            $options = " TYPE =\"$type\"" ;
        if ($start != -1)
            $options .= " START =\"$start\"" ;
        if ($value != -1)
            $options .= " VALUE =\"$value\"" ;
        if ($class != -1)
            $options .= " CLASS =\"$class\"" ;

        echo "<OL" . $options . ">\n" ;

        for($i = 0 ; $i < sizeof($text) ; $i++)
        echo "  <LI>$text[$i]\n" ;

        echo "</OL>\n" ;
    } 
    
    /**
     * The small text tag defines text that should be displayed in a smaller font than usual
     *
     * @param string $text
     * @param string $class
     */
    
    function Small($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<SMALL" . $options . ">$text</SMALL>" ;
    }
    
    /**
     * The strikethrough tag defines text that should be shown with a horizontal line through it
     *
     * @param string $text
     * @param string $class
     */
    
    function Strike($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<STRIKE" . $options . ">$text</STRIKE>" ;
    }
       
    /**
     * The strong tag defines text that should be strongly emphasized -- most browsers will display it in boldface
     *
     * @param string $text
     * @param string $class
     */
    
    function Strong($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<STRONG" . $options . ">$text</STRONG>" ;
    } 
    
    /**
     * The subscript tag defines text that should be displayed in a smaller font than usual, lower on the line than usual
     *
     * @param string $text
     * @param string $class
     */
    
    function Sub($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<SUB" . $options . ">$text</SUB>" ;
    } 
     
    /**
     * The superscript tag defines text that should be displayed in a smaller font than usual higher on the line than usual
     *
     * @param string $text
     * @param string $class
     */
    
    function Sup($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<SUP" . $options . ">$text</SUP>" ;
    } 
    
    /**
     * The underlined tag defines text that should be shown with a line underneath it
     *
     * @param string $text
     * @param string $class
     */
    
    function U($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<U" . $options . ">$text</U>" ;
    } 
    
    /**
     * The teletype tag defines text that should be shown in a fixed width font
     *
     * @param string $text
     * @param string $class
     */
    
    function Tt($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<TT" . $options . ">$text</TT>" ;
    } 
    

    /**
     * The meta tag, which is only valid in a HEAD section, declares HTTP meta name/value pairs that are associated with the HTML document. These are used to extend the HTTP header information returned by the HTTP server. The support of the meta information is HTTP server specific. If a name attribute is not specified the HTTP-EQUIV attribute is used as the name. This tag can also be used to trigger client side behaviour
     *
     * @param string $author
     * @param string $keywords
     * @param string $descr
     * @param string $lang
     * @param string $robots
     * @param string $reply
     * @param string $httpequiv
     */
    
    function Meta($author = -1, $keywords = array(), $descr = -1, $lang = -1, $robots = -1, $reply = -1, $httpequiv = array())
    {
        $text = "" ;
        if ($author != -1)
            echo "<META NAME =\"author\" CONTENT =\"$author\">\n" ;
        if ($descr != -1)
            echo "<META NAME =\"description\" CONTENT =\"$descr\">\n" ;
        if ($lang != -1)
            echo "<META NAME =\"language\" CONTENT =\"$lang\">\n" ;
        // if ($robots != -1)
            // echo "<META NAME =\"robots\" CONTENT =\"$robots\">\n" ;
        // if ($reply != -1)
            // echo "<META NAME =\"reply-to\" CONTENT =\"$reply\">\n" ;
        // if ($httpequiv[0] != -1)
            // echo "<META HTTP-EQUIV =\"refresh\" CONTENT =\"$httpequiv[0] ;  URL = $httpequiv[1]\">\n" ;
        if (sizeof($keywords) != 0) {
            for($i = 0 ; $i < sizeof($keywords) ; $i++)
            $text .= $keywords[$i] . " " ;
            echo "<META NAME =\"keywords\" CONTENT =\"$text\">\n" ;
        } 
    } 
    /**
     * The tag to include a style sheet
     *
     * @param string $url
     */
    
	function Icon($url)
    {
		echo "<link rel=\"icon\" type=\"image/png\" href=\"$url\" />";
    }
    function Style($url)
    {
        echo "<LINK HREF=\"$url\" REL=\"stylesheet\" type=\"text/css\">\n" ;
    }  
    /**
     * The HTML tag defines an HTML document. The <HTML> tag should be the first in the entire document, and the </HTML> tag should be the last
     *
     */
    
    function HtmlStart()
    {
        echo "<HTML>\n" ;
    } 
    
    /**
     * the function to close the <HTML> tag
     *
     */
    
    function HtmlEnd()
    {
        echo "</HTML>\n" ;
    }
    
    /**
     * The body tag introduces the body of the document. It should appear after the head section and occupy the remainder of the document
     *
     * @param string $action
     */
    
    function Body($action = -1)
    {
     if($action != -1)
        echo "<BODY " . $action . ">\n" ;
     else
        echo "<BODY>\n" ;
    }
    
    /**
     * the function to close the <BODY> tag
     *
     */
    
    function BodyEnd()
    {
        echo "</BODY>\n" ;
    }
    
    /**
     * These escape sequences are used to enter characters such as <, >, &, and " into HTML documents
     *
     * @param integer $nbr
     */
    
    function Sp($nbr = -1)
    {
        if($nbr != -1)
            {
                for($i = 0 ; $i < $nbr; $i++)
                echo "&nbsp;" ;
            }
        else
            echo "&nbsp;" ;
    } 
    
    /**
     * The abbreviation tag defines an abbreviation. It is typically displayed just like normal text, but is used by automatic indexers
     *
     * @param string $text
     * @param string $class
     */
    
    function Abbr($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<ABBR" . $options . ">$text</ABBR>" ;
    } 
    
    /**
     * The acronym tag defines an acronym. It is typically displayed just like normal text, but is used by automatic indexers
     *
     * @param string $text
     * @param string $class
     */
    
    function Acronym($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<ACRONYM" . $options . ">$text</ACRONYM>" ;
    }
    
    /**
     * The address tag defines text that gives an address or other contact information. It is typically displayed in italics, slightly indented, and is used by automatic indexers
     *
     * @param string $text
     * @param string $class
     */
    
    function Address($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<ADDRESS" . $options . ">$text</ADDRESS>" ;
    } 
    
    /**
     * The Java applet tag runs a Java applet referred to by a URL. applet-content consists of optional PARAM tags, ordinary text and markup to be displayed by browsers that cannot run Java applets, and a TEXTFLOW tag if no ordinary text and markup is included WARNING does not work with all the browser...
     *
     * @param string $code
     * @param string $name
     * @param string $codeBase
     * @param integer $width
     * @param integer $height
     * @param string $title
     * @param string $param
     * @param string $paramData
     * @param string $class
     */
    
    function Applet($code = -1, $name = -1, $codeBase = -1, $width = -1, $height = -1, $title = -1, $param = array(), $paramData = array(), $class = -1)
    {
        $options = "" ;
        if ($code != -1)
            $options = " CODE =\"$code\"" ;
        if ($name != -1)
            $options .= " NAME =\"$name\"" ;
        if ($codeBase != -1)
            $options .= " CODEBASE =\"$codeBase\"" ;
        if ($width != -1)
            $options .= " WIDTH =\"$width\"" ;
        if ($height != -1)
            $options .= " HEIGHT =\"$height\"" ;
        if ($title != -1)
            $options .= " TITLE =\"$title\"" ;
        if ($class != -1)
            $options .= " CLASS =\"$class\"" ;

        echo "<APPLET" . $options . ">\n";

        for($i = 0 ; $i < sizeof($param) ; $i++)
        echo "  <PARAM NAME =" . $param[$i] . " VALUE =" . $paramData[$i] . ">\n" ;

        echo "</APPLET>\n" ;
    } 
   
    /**
     * The background sound tag identifies a .wav, .au, or .mid resource that will be played when the page is opened. The optional LOOP attribute will cause the resource to be played n times. LOOP="INFINITE" will cause the resource to be played continuously as long as the page is open WARNING works only with Internet Explorer
     *
     * @param string $url
     * @param string $loop
     */
    
    function BgSound($url = -1, $loop = -1)
    {
        $options = "" ;
        if ($url != -1)
            $options = " SRC =\"$url\"" ;
        if ($loop != -1)
            $options .= " LOOP =\"$loop\"" ;

        echo "<BGSOUND" . $options . ">" ;
    } 
    
    /**
     * The big text tag defines text that should be displayed in a larger font than usual
     *
     * @param string $text
     * @param string $class
     */
    
    function Big($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<BIG" . $options . ">$text</BIG>" ;
    } 
    
    /**
     * The blink tag highlights the text by having it blink on and off
     *
     * @param string $text
     * @param string $class
     */
    
    function Blink($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<BLINK" . $options . ">$text</BLINK>" ;
    }
    
    /**
     * The font tag defines text with a smaller or larger font than usual. The normal font size corresponds to 3; smaller values of number will produce a smaller font, and larger values of number will produce a larger font the diferent fonts are : Times, Arial, Courier, Verdana, Geneva and Georgia
     *
     * @param string $text
     * @param string $face
     * @param string $color
     * @param integer $size
     */
    
    function Font($text, $face = -1, $color = -1, $size = -1)
    {
        $options = "" ;
        if ($face != -1) {
            switch ($face) {
                case "Times" :
                    $options = " FACE =\"Times New Roman, Times, serif\"" ;
                    break ;
                case "Arial" :
                    $options = " FACE =\"Arial, Helvetica, sans-serif\"" ;
                    break ;
                case "Courier" :
                    $options = " FACE =\"Courier New, Courier, mono\"" ;
                    break ;
                case "Georgia" :
                    $options = " FACE =\"Georgia, Times New Roman, Times, serif\"" ;
                    break ;
                case "Verdana" :
                    $options = " FACE =\"Verdana, Arial, Helvetica, sans-serif\"" ;
                    break ;
                case "Geneva" :
                    $options = " FACE =\"Geneva, Arial, Helvetica, sans-serif\"" ;
                    break ;
            } 
        }
        if ($color != -1)
            $options .= " COLOR =\"$color\"" ;
        if ($size != -1)
            $options .= " SIZE =\"$size\"" ;

        echo "<FONT" . $options . ">$text</FONT>" ;
    }
        
    /**
     * The FontStart tag defines text with a smaller or larger font than usual. The normal font size corresponds to 3; smaller values of number will produce a smaller font, and larger values of number will produce a larger font the diferent fonts are : Times, Arial, Courier, Verdana, Geneva and Georgia you can pass the text after the FrontStart tag.
     *
     * @param string $face
     * @param string $color
     * @param integer $size
     */
    
    function FontStart($face = -1, $color = -1, $size = -1)
    {
        $options = "" ;
        if ($face != -1) {
            switch ($face) {
                case "Times" :
                    $options = " FACE =\"Times New Roman, Times, serif\"" ;
                    break ;
                case "Arial" :
                    $options = " FACE =\"Arial, Helvetica, sans-serif\"" ;
                    break ;
                case "Courier" :
                    $options = " FACE =\"Courier New, Courier, mono\"" ;
                    break ;
                case "Georgia" :
                    $options = " FACE =\"Georgia, Times New Roman, Times, serif\"" ;
                    break ;
                case "Verdana" :
                    $options = " FACE =\"Verdana, Arial, Helvetica, sans-serif\"" ;
                    break ;
                case "Geneva" :
                    $options = " FACE =\"Geneva, Arial, Helvetica, sans-serif\"" ;
                    break ;
            }
        }
        if ($color != -1)
            $options .= " COLOR =\"$color\"" ;
        if ($size != -1)
            $options .= " SIZE =\"$size\"" ;

        echo "<FONT" . $options . ">" ;
    }
       
    /**
     * The FontEnd tag close the FontStart tag
     *
     */
    function FontEnd()
    {
        echo "</FONT>\n" ;
    }
    
    /**
     * The head tag introduces text that describes an HTML document. Most documents have only a TITLE tag in the head section you can add a script too example : JavaScript
     *
     * @param string $title
     * @param string $typeScript
     * @param string $script
     */
    
    function Head($title = -1, $typeScript = -1, $script = -1)
    {
        $attrTitle = "" ;
        $attrScript = "" ;
        $attrType = "" ;
        if ($title != -1)
            $attrTitle = "<TITLE>$title</TITLE>\n" ;
        if ($typeScript != -1)
            $attrType = " LANGUAGE =\"$typeScript\"" ;
        if ($script != -1)
            $attrScript = "<SCRIPT" . $attrType . ">\n$script\n</SCRIPT>\n" ;

        echo "<HEAD>\n" . $attrTitle . $attrScript;
    } 
    
    /**
     * the function to close the <HEAD> tag
     *
     */
    
    function HeadEnd()
    {
        echo "</HEAD>\n" ;
    }
    
    /**
     * The au tag defines text that names the author of a document. It is typically displayed just like normal text, but is used by automatic indexers
     *
     * @param string $text
     * @param string $class
     */
    
    function Au($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<AU" . $options . ">$text</AU>" ;
    } 
    
    /**
     * The author tag defines text that names the author of a document. It is typically displayed just like normal text, but is used by automatic indexers
     *
     * @param string $text
     * @param string $class
     */
    
    function Author($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<AUTHOR" . $options . ">$text</AUTHOR>" ;
    }
    
    /**
     * The deleted text tag marks text that has been deleted, for example in a group authoring situation or a legal document
     *
     * @param string $text
     * @param string $class
     */
    
    function Del($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<DEL" . $options . ">$text</DEL>" ;
    } 
        
    /**
     * The center tag defines text that should be centered
     *
     * @param string $text
     * @param string $class
     */
    
    function Center($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<CENTER" . $options . ">$text</CENTER>" ;
    }
    
    /**
     * // The citation tag defines text that cites a book or other work -- most browsers will display it in italics
     *
     * @param string $text
     * @param string $class
     */
    
    function Cite($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<CITE" . $options . ">$text</CITE>" ;
    } 
    
    /**
     * The map tag defines a client side image map It gives a name to a collection of AREA tags that are superimposed over an inline image to connect user clicks with URLs the different shape for the area are : rect, circle, poly and the target are : _self, _blank, _parent, _top
     *
     * @param string $name
     * @param string $shape
     * @param integer $coords
     * @param string $url
     * @param string $alt
     * @param string $title
     * @param string $target
     * @param string $class
     */
    
    function Map($name, $shape = -1, $coords = -1, $url = -1, $alt = -1, $title = -1, $target = -1, $class = -1)
    {
        $options = "" ;
        if ($shape != -1)
            $options = " SHAPE =\"$shape\"" ;
        if ($coords != -1)
            $options .= " COORDS =\"$coords\"" ;
        if ($url != -1)
            $options .= " HREF =\"$url\"" ;
        if ($alt != -1)
            $options .= " ALT =\"$alt\"" ;
        if ($title != -1)
            $options .= " TITLE =\"$title\"" ;
        if ($target != -1)
            $options .= " TARGET =\"$target\"" ;
        if ($class != -1)
            $options .= " CLASS =\"$class\"" ;

        echo "<MAP NAME =\"$name\">\n" ;

        echo "  <AREA" . $options . ">" ;

        echo "</MAP>\n" ;
    }
    
    /**
     * The base tag, which is valid only in the HEAD section, defines the base address of an HTML document, which is used to determine the full address of relative URL"s that appear in the document. The typical use for this is to move an HTML document to another site without moving all the images and related documents with it: the base URL can be set to the directory where those images and documents remain. The "default target" will become the target for all links unless specified explicitly the different target are : _parent, _top, _self, _blank
     *
     * @param string $url
     * @param string $target
     */
    
    function Base($url, $target = -1)
    {
        $options = "" ;
        if ($target != -1)
            $options = " TARGET =\"$target\"" ;

        echo "<BASE HREF =\"$url\"" . $options . ">\n" ;
    }
    
    /**
     * The base font tag defines the base that relative FONT changes are based on. (Default is 3.) WARNING this tag as to be put in the head section
     *
     * @param string $text
     * @param string $face
     * @param string $color
     * @param integer $size
     */
    
    function BaseFont($text, $face = -1, $color = -1, $size = -1)
    {
        $options = "" ;
        if ($face != -1) {
            switch ($face) {
                case "Times" :
                    $options = " FACE =\"Times New Roman, Times, serif\"" ;
                    break ;
                case "Arial" :
                    $options = " FACE =\"Arial, Helvetica, sans-serif\"" ;
                    break ;
                case "Courier" :
                    $options = " FACE =\"Courier New, Courier, mono\"" ;
                    break ;
                case "Georgia" :
                    $options = " FACE =\"Georgia, Times New Roman, Times, serif\"" ;
                    break ;
                case "Verdana" :
                    $options = " FACE =\"Verdana, Arial, Helvetica, sans-serif\"" ;
                    break ;
                case "Geneva" :
                    $options = " FACE =\"Geneva, Arial, Helvetica, sans-serif\"" ;
                    break ;
            }
        }
        if ($color != -1)
            $options .= " COLOR =\"$color\"" ;
        if ($size != -1)
            $options .= " SIZE =\"$size\"" ;

        echo "<BASEFONT" . $options . ">$text\n" ;
    } 
    
    /**
     * The code tag defines text that should be shown in a fixed width font. It can be nested with other idiomatic or typographic tags but some browsers will respect only the innermost tag. Many browsers use the same font for the KBD, SAMP, TT and CODE tags. For many lines of fixed width text, with the line breaks and other whitespace specified by the page author, use the PRE tag
     *
     * @param string $text
     * @param string $class
     */
    
    function Code($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<CODE" . $options . ">$text</CODE>" ;
    } 
    
    /**
     * The definition tag defines text that defines a term -- many browsers will display it in italics, though others will ignore it. It can be nested with other idiomatic or typographic tags but some browsers will respect only the innermost tag
     *
     * @param string $text
     * @param string $class
     */
    
    function Dfn($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<DFN" . $options . ">$text</DFN>" ;
    } 
    
    /**
     * The embed element is used to embed a plugin into a document. The OBJECT tag can also be used to embed objects the loop parametre will be false if anything is set the play parametre will be false if anything is set the different quality are : Low, Auto Low, Auto High, High the scale parametre are : Default(show all), No border, Exact fit
     *
     * @param string $url
     * @param integer $width
     * @param integer $height
     * @param string $loop
     * @param string $play
     * @param string $quality
     * @param string $scale
     */
    
    function Embed($url, $width = -1, $height = -1, $loop = -1, $play = -1, $quality = -1, $scale = -1)
    {
        $options = "" ;
        if ($width != -1)
            $options = " WIDTH =\"$width\"" ;
        if ($height != -1)
            $options .= " HEIGHT =\"$height\"" ;
        if ($loop != -1)
            $options .= " LOOP =\"$loop\"" ;
        if ($play != -1)
            $options .= " PLAY =\"$play\"" ;
        if ($quality != -1)
            $options .= " QUALITY =\"$quality\"" ;
        if ($scale != -1)
            $options .= " SCALE =\"$scale\"" ;

        echo "<EMBED SRC =\"$url\"" . $options . ">\n" ;
    }
    
    /**
     * The inserted text tag marks text that has been inserted, for example in a group authoring situation or a legal document
     *
     * @param string $text
     * @param string $class
     */
    
    function Ins($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<INS" . $options . ">$text</INS>" ;
    } 
    
    /**
     * The keyboard tag defines text that should be shown in a fixed width font. It can be nested with other idiomatic or typographic tags but some browsers will respect only the innermost tag
     *
     * @param string $text
     * @param string $class
     */
    
    function Kbd($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<KBD" . $options . ">$text</KBD>" ;
    } 
    
    /**
     * The no break tag defines a block of text which will have no line breaks except those explicitly requested with BR or suggested with WOBR
     *
     * @param string $text
     * @param string $class
     */
    
    function NoBr($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options .= " CLASS =\"$class\"" ;

        echo "<NOBR" . $options . ">$text</NOBR>" ;
    } 
    
    /**
     * The NOFRAMES tag specifies HTML that can be used by browsers that do not support frames. Everything between the start and end tag is ignored by browsers that understand frames should be put before the <body> tag
     *
     */
    function NoFrame()
    {
        echo "<NOFRAME>\n" ;
    }
    
    /**
     * function to close the <NOFRAME> tag should be put after the closed </body> tag
     *
     */
    function NoFrameEnd()
    {
        echo "</NOFRAME>\n" ;
    }
    
    /**
     * function to open a div tag to align or put classes
     *
     * @param string $align
     */
    
    function Div1($align = -1)
    {
        $options = "" ;
        if ($align != -1)
            $options = " ALIGN =\"$align\"" ;

        echo "<DIV" . $options . ">\n" ;
    } 
    function Div($id = -1)
    {
        $options = "" ;
        if ($id != -1)
            $options = " id =\"$id\"" ;

        echo "<DIV" . $options . ">\n" ;
    } 
    /**
     * function to close the div tag
     *
     */
    
     function Divclass($class)
    {
        echo "<DIV class=\"".$class."\">" ;
    } 
	
	
	function DivEnd()
    {
        echo "</DIV>\n" ;
    } 
    
    /**
     * function to invert a texte with the prams. rtl : right to left ltr : left to right
     *
     * @param string $text
     * @param string $direction
     * @param string $lang
     * @param string $class
     */
    
    function Bdo($text, $direction = -1, $lang = -1, $class = -1)
    {
        $options = "" ;
        if ($direction != -1)
            $options = " DIR =\"$direction\"" ;
        if ($lang != -1)
            $options .= " LANG =\"$lang\"" ;
        if ($class != -1)
            $options .= " CLASS =\"$class\"" ;

        echo "<BDO" . $options . ">$text</BDO>\n" ;
    }
    
    /**
     * The <blockquote> tag is supposed to contain only block-level elements within it, and not just plain text.
     *
     * @param string $text
     * @param string $class
     */
    
    function BlockQuote($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<BLOCKQUOTE" . $options . ">\n<P>$text</P>\n</BLOCKQUOTE>\n" ;
    }
    
    /**
     * Defines a push button. Inside a button element you can put content, like text or images. This is the difference between this element and buttons created with the input element. the type can be : submit, reset, button
     *
     * @param string $text
     * @param string $type
     * @param string $name
     * @param string $value
     * @param string $action
     * @param string $nameAction
     * @param string $disabled
     * @param string $class
     */
    
    function Button($text, $type = -1, $name = -1, $value = -1, $action = -1, $nameAction = -1, $disabled = -1, $class = -1)
    {
        $options = "" ;
        if ($type != -1)
            $options = " TYPE =\"$type\"" ;
        else
            $options = " TYPE =\"button\"" ;

        if ($name != -1)
            $options .= " NAME =\"$name\"" ;
        if ($value != -1)
            $options .= " VALUE =\"$value\"" ;

        switch ($action) {
            case 'onBlur' :
                $options .= " onBlur =\"$nameAction\"" ;
                break ;
            case 'onClick' :
                $options .= " onClick =\"$nameAction\"" ;
                break ;
            case 'onDblClick' :
                $options .= " onDblClick =\"$nameAction\"" ;
                break ;
            case 'onFocus' :
                $options .= " onFocus =\"$nameAction\"" ;
                break ;
            case 'onMouseDown' :
                $options .= " onMouseDown =\"$nameAction\"" ;
                break ;
            case 'onMouseUp' :
                $options .= " onMouseUp =\"$nameAction\"" ;
                break ;
            case 'onMouseOver' :
                $options .= " onMouseOver =\"$nameAction\"" ;
                break ;
            case 'onMouseMove' :
                $options .= " onMouseMove =\"$nameAction\"" ;
                break ;
            case 'onMouseOut' :
                $options .= " onMouseOut =\"$nameAction\"" ;
                break ;
            case 'onKeyPress' :
                $options .= " onKeyPress =\"$nameAction\"" ;
                break ;
            case 'onKeyDown' :
                $options .= " onKeyDown =\"$nameAction\"" ;
                break ;
            case 'onKeyUp' :
                $options .= " onKeyUp =\"$nameAction\"" ;
                break ;
        }
        if ($disabled != -1)
            $options .= " DISABLED" ;
        if ($class != -1)
            $options .= " CLASS =\"$class\"" ;

        echo "<BUTTON" . $options . ">$text</BUTTON>\n" ;
    }
    
    /**
     * This element defines a table caption. The <caption> tag must be inserted immediately after the <table> tag. You can specify only one caption pertable. Usually the caption will be centered above the table.
     *
     * @param string $text
     * @param string $class
     */
    
    function Caption($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<CAPTION" . $options . ">$text</CAPTION>\n" ;
    } 
    
    /**
     * Defines the attribute values for one or more columns in a table. You can only use this element inside a colgroup. Use this element when you want to specify different attribute values to a column inside a colgroup. Without a col element a column will inherit all its attribute values from the column group.
     *
     * @param string $span
     * @param integer $width
     */
    
    function ColGroup($span = -1, $width = array())
    {
        $options = "" ;
        if ($span != -1)
            $options = " SPAN =\"$span\"" ;

        echo "<COLGROUP" . $options . ">\n" ;

        for($i = 0 ; $i < sizeof($width) ; $i++)
        echo "    <COL WIDTH =\"$width[$i]\"></COL>\n" ;

        echo "</COLGROUP>\n" ;
    } 
    
    /**
     * The <dd> tag defines the description of a term in a definition list.
     *
     * @param string $text
     */
    
    function Dd($text)
    {
        echo "        <DD>$text</DD>\n" ;
    }
    
    /**
     * The <dt> tag defines the start of a term in a definition list.
     *
     * @param string $text
     */
    
    function Dt($text)
    {
        echo "    <DT>$text</DT>\n" ;
    } 
    
    /**
     * The <dl> tag defines a definition list.
     *
     * @param string $compact
     */
    
    function DlStart($compact = -1)
    {
        $options = "" ;
        if ($compact != -1)
            $options = " COMPACT" ;

        echo "<DL" . $options . ">\n" ;
    } 
    
    /**
     * The </dl> tag defines the end of a definition list.
     *
     */
    
    function DlEnd()
    {
        echo "</DL>\n" ;
    } 
    
    /**
     * The fieldset element draws a box around its containing elements.
     *
     * @param string $class
     */
    
    function FieldSetStart($class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<FIELDSET" . $options . ">\n" ;
    }
    
    /**
     * The fieldSetEnd() close the fieldset tag
     *
     */
    
    function FieldSetEnd()
    {
        echo "</FIELDSET>\n" ;
    } 
    
    /**
     * Defines a label to a control. If you click the text within the label element, it is supposed to toggle the control.
     *
     * @param string $text
     * @param string $for
     * @param string $class
     */
    
    function Label($text, $for, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<LABEL FOR=\"$for\" " . $options . ">$text</LABEL>\n" ;
    }
    
    /**
     * The legend element defines a caption for a fieldset. param align = center, left, right
     *
     * @param string $text
     * @param string $align
     * @param string $class
     */
    
    function Legend($text, $align = -1, $class = -1)
    {
        $options = "" ;
        if ($align != -1)
            $options = " ALIGN =\"$align\"" ;
        if ($class != -1)
            $options .= " CLASS =\"$class\"" ;

        echo "<LEGEND" . $options . ">$text</LEGEND>\n" ;
    }
    
    /**
     * The noscript element is used to define an alternate content (text) if a script is NOT executed. This tag is used for browsers that recognizes the <script> tag, but does not support the script in it.
     *
     * @param string $text
     */
    
    function NoScript($text)
    {
        echo "<NOSCRIPT>$text</NOSCRIPT>\n" ;
    }
    
    /**
     * The <p> tag defines a paragraph.
     *
     * @param string $text
     * @param string $class
     */
    
    function P($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<P" . $options . ">$text</P>\n" ;
    }
    
    /**
     * The pre element defines preformatted text. The text enclosed in the pre element usually preserves spaces and line breaks. The text renders in a fixed-pitch font.
     *
     * @param string $text
     * @param integer $width
     * @param string $class
     */
    
    function Pre($text, $width = -1, $class = -1)
    {
        $options = "" ;
        if ($width != -1)
            $options = " WIDTH =\"$width\"" ;
        if ($class != -1)
            $options .= " CLASS =\"$class\"" ;

        echo "<PRE" . $options . ">$text</PRE>\n" ;
    }
    
    /**
     * The <q> tag defines the start of a short quotation.
     *
     * @param string $text
     * @param string $class
     */
    
    function Q($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<Q" . $options . ">$text</Q>\n" ;
    }
    
    /**
     * The strikethrough tag defines text that should be shown with a horizontal line through it
     *
     * @param string $text
     * @param string $class
     */
    
    function S($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<S" . $options . ">$text</S>\n" ;
    }
    
    /**
     * Defines sample computer code
     *
     * @param string $text
     * @param string $class
     */
    
    function Samp($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<SAMP" . $options . ">$text</SAMP>\n" ;
    } 
    
    /**
     * Defines a script, such as a JavaScript.
     *
     * @param string $type
     * @param string $language
     */
    
	function javascript($url)
    {
         echo "<script type=\"text/javascript\" src=\"$url\"></script>";
    }
	
    function ScriptStart($type, $language)
    {
        echo "<SCRIPT LANGUAGE=\"$language\" TYPE=\"$type\">\n" ;
    }
 
    /**
     * Defines a script, such as a JavaScript.
     *
     */
    function ScriptEnd()
    {
        echo "</SCRIPT>\n" ;
    }
    
    /**
     * The <span> tag is used to group inline-elements in a document. one way to put color in document : style="color:#FF0000"
     *
     * @param string $text
     * @param string $style
     * @param string $class
     */
    
    function Span($text, $style = -1, $class = -1)
    {
        $options = "" ;
        if ($style != -1)
            $options = " STYLE =\"$style\"" ;
        if ($class != -1)
            $options .= " CLASS =\"$class\"" ;

        echo "<SPAN" . $options . ">$text</SPAN>\n" ;
    } 
    
    /**
     * Defines a variable
     *
     * @param string $text
     * @param string $class
     */
    
    function V($text, $class = -1)
    {
        $options = "" ;
        if ($class != -1)
            $options = " CLASS =\"$class\"" ;

        echo "<VAR" . $options . ">$text</VAR>\n" ;
    }
    
    /**
     * Define the Frame and frameset tag to create multiple pages the scrolling  param must 'YES', 'NO', 'AUTO'
     *
     * @param string $url
     * @param string $align
     * @param integer $frameborder
     * @param integer $width
     * @param integer $height
     * @param integer $longdesc
     * @param integer $marginheight
     * @param integer $marginwidth
     * @param string $name
     * @param string $scroll
     */
    function Iframe($url, $align = -1, $frameborder = -1, $width = -1, $height = -1, $longdesc = -1, $marginheight = -1,
                        $marginwidth = -1, $name = -1, $scroll = -1)
    {
     $options = "" ;
     if($align != -1)
        $options = " ALIGN =\"$align\"" ;
     if($frameborder != -1)
        $options .= " FRAMEBORDER =\"$frameborder\"" ;
     if($width != -1)
        $options .= " WIDTH =\"$width\"" ;
     if($height != -1)
        $options .= " HEIGHT =\"$height\"" ;
     if($longdesc != -1)
        $options .= " LONGDESC =\"$longdesc\"" ;
     if($marginheight != -1)
        $options .= " MARGINHEIGHT =\"$marginheight\"" ;
     if($marginwidth != -1)
        $options .= " MARGINWIDTH =\"$marginwidth\"" ;
     if($name != -1)
        $options .= " NAME =\"$name\"" ;
     if($scroll != -1)
        $options .= " SCROLLING =\"$scroll\"" ;

     echo "<IFRAME SRC=\"$url\"" . $options . "></IFRAME>\n" ;
    }
    
    /**
     * The framesetCols tag define a new set of frame sort by collone
     *
     * @param integer $cols
     */
    
    function FrameSetCols($cols = array())
    {
     
     if($cols != -1)
        {
        echo "<FRAMESET COLS =\"" ;
        for($i=0 ; $i<sizeof($cols) ; $i++)
                {
                 if($i == sizeof($cols)-1)
                        echo $cols[$i] ;
                 else
                        echo $cols[$i] . ", " ;
                }
         echo "\">\n" ;
        }
    }
    
    /**
     * The framesetrows tag define a new set of frame sort by rows
     *
     * @param integer $rows
     */
    
    function FrameSetRows($rows = array())
    {

     if($rows != -1)
        {
        echo "<FRAMESET ROWS =\"" ;
        for($i=0 ; $i<sizeof($rows) ; $i++)
                {
                 if($i == sizeof($rows)-1)
                        echo $rows[$i] ;
                 else
                        echo $rows[$i] . ", " ;
                }
         echo "\">\n" ;
        }
    }
    
    /**
     * The frame tag to define the page to load
     *
     * @param string $url
     * @param string $name
     * @param string $noresize
     * @param string $scrolling
     * @param integer $frameborder
     * @param integer $longdesc
     * @param integer $marginheight
     * @param integer $marginwidth
     */
    
    function Frame($url, $name = -1, $noresize = -1, $scrolling = -1, $frameborder = -1, $longdesc = -1,
    $marginheight = -1, $marginwidth = -1)
    {
     $options = "" ;
     if($name != -1)
        $options = " NAME =\"$name\"" ;
     if($noresize != -1)
        $options .= " NORESIZE" ;
     if($scrolling != -1)
        $options .= " SCROLLING =\"$scrolling\"" ;
     if($frameborder != -1)
        $options .= " FRAMEBORDER =\"$frameborder\"" ;
     if($longdesc != -1)
        $options .= " LONGDESC =\"$longdesc\"" ;
     if($marginheight != -1)
        $options .= " MARGINHEIGHT =\"$marginheight\"" ;
     if($marginwidth != -1)
        $options .= " MARGINWIDTH =\"$marginwidth\"" ;

    echo "<FRAME SRC =\"$url\"" . $options . ">\n" ;
    }
    
    /**
     * The tag to close the frameset
     *
     */
    
    function FrameSetEnd()
    {
     echo "</FRAMESET>\n" ;
    }
}
// $tiba= new HTML;


?>
