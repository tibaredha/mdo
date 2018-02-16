<?php  

/*============================================================================= 
CLASSE 
    LICENCE EUPL 
_______________________________________________________________________________  
*** Cette application permet d'effectuer le test du khi2 (khi2 + ddl) 
*** Auteur : Middleton Cédric 
*** Réalisé le : 12/03/2011 
*** Modifié le : 14/03/2011 
*** Version : 2.0 
*** URL : http://testchideux.awardspace.info 

*** @Licence :  
    http://testchideux.awardspace.info/index.php?v=licence 
    http://www.osor.eu/eupl/eupl-v1.1/fr/EUPL%20v.1.1%20-%20Licence.pdf 
==============================================================================*/ 

class Chi 
{ 

    /* @var int $nombreLigne nombre de ligne du tableau */ 
    public $line; 

    /* @var int $nombreColonne nombre de colonne du tableau */ 
    public $column;  

    /* @var int $nombreDecimale nombre de décimale */  
    public $decimal = 0; 

    /* @var array $valeursTableau valeurs du tableau 'i=ligne' et 'j=colonne' */  
    public $values;   

    /* @var boolean $debug activation du débugage */ 
    public $debug = TRUE; 
     
    /* @var string $errors erreurs détectés */ 
    private $errors; 
     
    /* @var int $totalContingent total des totaux des contingents */  
    private $totalContingent; 
     
    /* @var $totalTheoreticalResult total des totaux des résultats théoriques*/ 
    private $totalTheoreticalResult; 
     
    /* @var int $totalChiResult khi2 */  
    private $totalChiResult; 
     
    /* @var int $ddl degré de liberté */  
    private $ddl;  
     
    /* @var array $theoreticalResult valeurs théoriques */  
    private $theoreticalResult    = array(); 
     
    /* @var array $chiResult valeurs khis */  
    private $chiResult = array(); 
     
    /* @var array $totalContingentResultByLine total par ligne des valeurs contingents/d'observations */ 
    private $totalContingentResultByLine = array(); 

    /* @var array $totalContingentResultByColumn total par colonne des valeurs contingents/d'observations */ 
    private $totalContingentResultByColumn = array(); 

    /* @var array $totalTheoreticalResultByLine total des valeurs théoriques par ligne */  
    private $totalTheoreticalResultByLine = array(); 

    /* @var array $totalTheoreticalResultByColunn total des valeurs théoriques par colonne */  
    private $totalTheoreticalResultByColunn = array(); 
     
    /* @var array $totalChiResultByLine total des khis par ligne */  
    private $totalChiResultByLine = array(); 

    /* @var array $totalChiResultByColumn total des khis par colonne */  
    private $totalChiResultByColumn    = array(); 

     

     
    /** 
     * Degré de liberté     
     */ 
    public function getDDL() 
    { 
        if(!isset($this->ddl)) { 
            $this->writeError('Pour obtenir le degré de liberté, lancer d\'abord le calcul avec la méthode ouput()'); 
            return false; 
        }     
        return $this->ddl; 
    } 
     
    /** 
     * Chi² 
     */ 
    public function getCHI() 
    { 
        if(!isset($this->totalChiResult)) { 
            $this->writeError('Pour obtenir le khi2, lancer d\'abord le calcul avec la méthode ouput()');
            return false; 
        }     
        return $this->totalChiResult; 
    } 
     
    public function __toString() 
    { 
        return (string)$this->getCHI(); 
    } 
     
    /** 
     * Valeurs contingents 
     */ 
    public function getContingents() 
    { 
        if(!isset($this->values)) { 
            $this->writeError('Pour obtenir la liste des valeurs d\'observations, lancer d\'abord le calcul avec la méthode ouput()');
            return false; 
        }     
        return $this->values; 
    } 
     
    public function getTotalContingentLine() 
    { 
        if(empty($this->totalContingentResultByLine)) { 
            $this->writeError('Pour obtenir la liste des totaux valeurs d\'observations par ligne, lancer d\'abord le calcul avec la méthode ouput()');
            return false; 
        }     
        return $this->totalContingentResultByLine; 
    } 
     
    public function getTotalContingentColumn() 
    { 
        if(empty($this->totalContingentResultByColumn)) { 
            $this->writeError('Pour obtenir la liste des totaux valeurs d\'observations par colonne, lancer d\'abord le calcul avec la méthode ouput()');
            return false; 
        }     
        return $this->totalContingentResultByColumn; 
    } 
     
    public function getTotalContingent() 
    { 
        if(empty($this->totalContingent)) { 
            $this->writeError('Pour obtenir le total des totaux valeurs d\'observations, lancer d\'abord le calcul avec la méthode ouput()');
            return false; 
        }     
        return $this->totalContingent; 
    } 
     
    /** 
     * Valeurs théoriques 
     */     
    public function getTheoreticals() 
    {  
        if(empty($this->theoreticalResult)) { 
            $this->writeError('Pour obtenir la liste des valeurs théoriques, lancer d\'abord le calcul avec la méthode ouput()');
            return false; 
        }     
        return $this->theoreticalResult; 
    } 
     
    public function getTotalTheoreticalLine() 
    { 
        if(empty($this->totalTheoreticalResultByLine)) { 
            $this->writeError('Pour obtenir la liste des totaux valeurs théoriques par ligne, lancer d\'abord le calcul avec la méthode ouput()');
            return false; 
        }     
        return $this->totalTheoreticalResultByLine; 
    } 
     
    public function getTotalTheoreticalColumn() 
    { 
        if(empty($this->totalTheoreticalResultByColumn)) { 
            $this->writeError('Pour obtenir la liste des totaux valeurs théoriques par colonne, lancer d\'abord le calcul avec la méthode ouput()');
            return false; 
        }     
        return $this->totalTheoreticalResultByColumn; 
    } 
     
    public function getTotalTheoretical() 
    { 
        if(empty($this->totalTheoreticalResult)) { 
            $this->writeError('Pour obtenir le total des totaux valeurs théoriques, lancer d\'abord le calcul avec la méthode ouput()');
            return false; 
        }     
        return $this->totalTheoreticalResult; 
    } 
     
    /** 
     * Valeurs khis 
     */ 
    public function getChis() 
    { 
        if(!isset($this->chiResult)) { 
            $this->writeError('Pour obtenir la liste des valeurs khis, lancer d\'abord le calcul avec la méthode ouput()');
            return false; 
        }     
        return $this->chiResult; 
    } 
     
    public function getTotalChiLine() 
    { 
        if(empty($this->totalChiResultByLine)) { 
            $this->writeError('Pour obtenir la liste des totaux valeurs khis par ligne, lancer d\'abord le calcul avec la méthode ouput()');
            return false; 
        }     
        return $this->totalChiResultByLine; 
    } 
     
    public function getTotalChiColumn() 
    { 
        if(empty($this->totalChiResultByColumn)) { 
            $this->writeError('Pour obtenir la liste des totaux valeurs khis par colonne, lancer d\'abord le calcul avec la méthode ouput()');
            return false; 
        }     
        return $this->totalChiResultByColumn; 
    } 
     
    public function getTotalChi() 
    { 
        if(empty($this->totalChiResultByColumn)) { 
            $this->writeError('Pour obtenir le total des totaux valeurs khis, lancer d\'abord le calcul avec la méthode ouput()');
            return false; 
        }     
        return $this->totalChiResult; 
    } 
     
    /** 
     * Lancement des calculs 
     * @return void 
     */ 
    public function output() 
    { 
        if(empty($this->line) OR preg_match('/[^1-9]/', $this->line)) 
            $this->writeError('Veuillez indiquer le nombre de ligne que contient le tableau'); 
        if(empty($this->column) OR preg_match('/[^1-9]/', $this->column)) 
            $this->writeError('Veuillez indiquer le nombre de colonne que contient le tableau'); 
        if(preg_match('/[^0-9]/', $this->decimal)) 
            $this->writeError('Veuillez indiquer le nombre de décimale que vous souhaitez pour les calculs'); 
        if(empty($this->values) OR !is_array($this->values) OR !array_sum($this->values)) 
            $this->writeError('Veuillez indiquer les valeurs du tableau'); 
        if($this->line<2 OR $this->column<2) 
            $this->writeError('La dimension du tableau doit être de 2x2 minimum'); 
             
        $this->setDDL();         
        $this->setContingent(); 
        $this->setTheoretical(); 
        $this->setChi(); 
    } 
     
    ///////////////////////////////////// 
    // SETTERS, PRIVATE METHODS 
    //////////////////////////////////// 
     
    /** 
     * Calcul le degré de liberté  
     */ 
    private function setDDL() 
    { 
        if(!empty($this->errors)) 
            return false; 
        $this->ddl = ($this->line - 1) * ($this->column - 1); 
        return $this->ddl; 
    } 
     
    /** 
     * Cacul des totaux contigents 
     */ 
    private function setContingent() 
    { 
        if(!empty($this->errors)) 
            return false; 
         
        $this->totalContingentResultByLine = $this->calculateBL($this->values, $this->line, $this->column); 
        $this->totalContingentResultByColumn = $this->calculateBC($this->values, $this->column, $this->line); 
        $this->totalContingent = round(array_sum($this->totalContingentResultByLine), $this->decimal); 
        return $this->totalContingent;     
    } 

    /** 
     * Calcul des résultats théoriques 
     */ 
    private function setTheoretical() 
    { 
        if(!empty($this->errors)) 
            return false; 
             
        foreach($this->totalContingentResultByLine AS $keyLine => $valueLine) { 
            foreach($this->totalContingentResultByColumn AS $keyCol => $valueCol) { 
                $tempResult = $valueLine * $valueCol; 
                $result = (!$tempResult) ? 0 : ($tempResult/$this->totalContingent); 
                $this->theoreticalResult[] =  round(floatval($result), $this->decimal); 
            } 
        } 
        $this->totalTheoreticalResultByLine = $this->calculateBL($this->theoreticalResult, $this->line, $this->column); 
        $this->totalTheoreticalResultByColumn = $this->calculateBC($this->theoreticalResult, $this->column, $this->line); 
        $this->totalTheoreticalResult = round(array_sum($this->totalTheoreticalResultByLine), $this->decimal);     
        return $this->totalTheoreticalResult;     
    } 
     
    /** 
     * Calcul des khis 
     */ 
    private function setChi() 
    { 
        if(!empty($this->errors)) 
            return false; 
             
        foreach($this->theoreticalResult AS $key => $value) { 
            $tempSquare = $this->values[$key] - $value; 
            $tempResult = (!$tempSquare) ? 0 : (pow($tempSquare, 2) / $value); 
            $this->chiResult[] = round(floatval($tempResult), $this->decimal); 
        } 
        $this->totalChiResultByLine = $this->calculateBL($this->chiResult, $this->line, $this->column); 
        $this->totalChiResultByColumn = $this->calculateBC($this->chiResult, $this->column, $this->line);
        $this->totalChiResult = round(array_sum($this->totalChiResultByLine), $this->decimal); 
        return $this->totalChiResult; 
    }     

    /** 
     * Sauvegarde des erreurs 
     */ 
    public function writeError($msg) 
    {  
        if(!empty($msg)) 
            return $this->errors[] = htmlentities($msg, ENT_COMPAT, 'UTF-8'); 
        return false; 
    } 
     
    /** 
     * Message d'erreur 
     */ 
    public function displayError() 
    { 
        if($this->debug AND sizeof($this->errors)) 
            throw new Exception( implode('<br/>', $this->errors) ); 
        return false; 
    } 
     
     
    /** 
     * fonctions de calculs par ligne 
     */ 
    private function calculateBL($input, $loop, $limit) 
    { 
        for($i=0; $i<$loop; $i++) { 
            $temp=0; 
            for($j=0; $j<$limit; $j++) {     
                if(!$i) 
                    $temp += $input[$j]; 
                else  
                    $temp += $input[$j+($i*$limit)]; 
            } 
            $tempSave[] = round(floatval($temp), $this->decimal); 
        } 
        return $tempSave;     
    } 
     
    /** 
     * fonctions de calculs par colonne 
     */ 
    private function calculateBC($input, $loop, $limit) 
    { 
        for($i=0; $i<$loop; $i++) { 
            $temp=0; 
            for($j=0; $j<$limit; $j++) { 
                if(!$i) 
                    $temp += $input[$j*$loop]; 
                else 
                    $temp += $input[$i+($loop*$j)]; 
            } 
            $tempSave[] = round(floatval($temp), $this->decimal); 
        } 
        return $tempSave; 
    } 
     
} 

?>
<h3>Définissez le tableau</h3>
		<form action="?v=calcul" method="post">
			<label for="ligne">Nombre de ligne</label>
				<input type="text" name="ligne" value=""/>
			<label for="colonne">Nombre de colonne</label>
				<input type="text" name="colonne" value=""/>
			<label for="decimale">Nombre de décimale pour les résultats</label>
				<input type="text" name="decimale" value=""/>
			<input type="submit" name="genTab" value="Générer le tableau"/>		
		</form>

<?php 
/*============================================================================= 
DOCUMENTATION 
    LICENCE EUPL 
_______________________________________________________________________________  
*** Cette application permet d'effectuer le test du khi2 (khi2 + ddl) 
*** Auteur : Middleton Cédric 
*** Réalisé le : 12/03/2011 
*** Modifié le : 14/03/2011 
*** Version : 2.0 
*** URL : http://testchideux.awardspace.info 

*** @Licence :  
    http://testchideux.awardspace.info/index.php?v=licence 
    http://www.osor.eu/eupl/eupl-v1.1/fr/EUPL%20v.1.1%20-%20Licence.pdf 
==============================================================================*/ 

    try { 
         
        $chi = new Chi(); 
         
        /* nombre de ligne dans le tableau */ 
        $chi->line = $_POST['ligne'];  
         
        /* nombre de colonne dans le tableau */ 
        $chi->column = $_POST['colonne'];  
         
        /* nombre de décimale désirée après la virgule */ 
        $chi->decimal = $_POST['decimale'];  
         
        /* <imput name="valeurs[]"/> :: valeurs du tableau */ 
        $chi->values = $_POST['valeurs'];  
         
        /* Calcul et traitement des valeurs */ 
        $chi->output();  
         
        /* désactivation du débugage, par défaut est activé */ 
        //$chi->debug=false;  
         
        /* affichage des erreurs si le débugage est activé, sinon n'affiche rien */ 
        $chi->displayError();  
         
        /* affiche le chi2, équivalence==> echo $chi; OU echo $chi->getTotalChi(); */ 
        echo $chi->getCHI();  
         
        /* affiche le degré de liberté    */                 
        echo $chi->getDDL();  
         
        /* tableau des valeurs d'observations */ 
        print_r($chi->getContingents());  
         
        /* liste des totaux d'observations par ligne */ 
        print_r($chi->getTotalContingentLine());  
         
        /* liste des totaux d'observations par colonne */ 
        print_r($chi->getTotalContingentColumn());  
         
        /* total des totaux d'observations */ 
        echo $chi->getTotalContingent();  
         
        /* tableau des valeurs théoriques */ 
        print_r($chi->getTheoreticals());  
         
        /* liste des totaux théoriques par ligne */ 
        print_r($chi->getTotalTheoreticalLine());  
         
        /* liste des totaux théoriques par colonne */ 
        print_r($chi->getTotalTheoreticalColumn());  
         
        /* total des totaux théoriques */ 
        echo $chi->getTotalTheoretical();  
         
        /* tableau des khis */ 
        print_r($chi->getChis());  
         
        /* liste des totaux khis par ligne */ 
        print_r($chi->getTotalChiLine());  
         
        /* liste des totaux khis par colonne */ 
        print_r($chi->getTotalChiColumn());  
         
        /* total des totaux khis */ 
        echo $chi->getTotalChi(); 
         
    } 
    catch(Exception $catch) { 
        echo $catch->getMessage(); 
    }     
?>