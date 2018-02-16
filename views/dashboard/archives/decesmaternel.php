<style type="text/css"> 
#contente_2, #contente_3, #contente_4, #contente_5, #contente_6 { display: none; height: auto; clear: all;} 
</style> 
<script type="text/javascript">
/*Activates the Tabs*/
function tabSwitch(new_tab, new_content) {    
    document.getElementById('contente_1').style.display = 'none';  
    document.getElementById('contente_2').style.display = 'none';  
    document.getElementById('contente_3').style.display = 'none';  
	document.getElementById('contente_4').style.display = 'none';  
	document.getElementById('contente_5').style.display = 'none';  
	document.getElementById('contente_6').style.display = 'none';  
	/*document.getElementById('content_3').style.display = 'none';*/ 
	document.getElementById(new_content).style.display = 'block';     
    document.getElementById('tabe_1').className = '';  
    document.getElementById('tabe_2').className = '';  
    document.getElementById('tabe_3').className = '';  
	document.getElementById('tabe_4').className = '';  
	document.getElementById('tabe_5').className = '';  
	document.getElementById('tabe_6').className = '';  
	/*document.getElementById('tab_3').className = ''; */        
    document.getElementById(new_tab).className = 'active';        
}
</script>
<h1><a title="Partie commune a completer en totalite"  target="_blank"  href="<?php echo URL; ?>tcpdf/docpdf/dz/decesfrx.pdf">Partie commune a completer en totalite  </a>:<?php echo  HTML::nbrtostring('deceshosp','id',$this->user[0]['id'],'NOM').'_'.HTML::nbrtostring('deceshosp','id',$this->user[0]['id'],'PRENOM'); ?></h1><hr><br/>

<form id="Canvas" action="<?php echo URL."pdf/decesmaternels.php?uc=".$this->user[0]['id'];  ?>"  method="POST"> 

<div class="tabbed_area">  
        <ul class="tabs">  
            <li><a href="javascript:tabSwitch('tabe_1', 'contente_1');" id="tabe_1" class="active">Section 1</a></li>  
            <li><a href="javascript:tabSwitch('tabe_2', 'contente_2');" id="tabe_2">Section 2</a></li> 
			<li><a href="javascript:tabSwitch('tabe_3', 'contente_3');" id="tabe_3">Section 3</a></li> 	
            <li><a href="javascript:tabSwitch('tabe_4', 'contente_4');" id="tabe_4">Section 4</a></li> 	
            <li><a href="javascript:tabSwitch('tabe_5', 'contente_5');" id="tabe_5">Section 5</a></li> 	
            <li><a href="javascript:tabSwitch('tabe_6', 'contente_6');" id="tabe_6">Section 6</a></li> 	
		</ul>    
		
		<div id="contente_1" class="content">  
		<h2>Caractéristiques de la femme</h2>
		
		<label id="lM1">Q1: Numéro d'identification </label>   <input id="M1"    type="txt"  name="M1"     value="" placeholder="xxxxxxx"/>
		<label id="lM2">Q2: Date de naissance</label>          <input id="M2"     type="txt" name="M2"    value="" placeholder="xxxxxxx" />
		<label id="lM3">Q3: Age</label>                        <input id="M3"     type="txt" name="M3"    value="" placeholder="xxxxxxx" />
		<label id="lM4">Q4: Date du décès</label>              <input id="M4"     type="txt" name="M4"    value="" placeholder="xxxxxxx" />
		<label id="lM5">Q5: Heure du Décè</label>              <input id="M5"     type="txt" name="M5"    value="" placeholder="xxxxxxx" />
		<label id="lM6">Q6: Wilaya de résidence</label>        <input id="M6"     type="txt" name="M6"    value="" placeholder="xxxxxxx" />
		<label id="lM7">Q7: Profession de la patiente</label>  <input id="M7"     type="txt" name="M7"    value="" placeholder="xxxxxxx" />
		<label id="lM8">Q8: Niveau d'instruction de la patiente</label>  
		<select id="M8"  name="M8"  >  
					<option value="1">Analphabète</option>
				    <option value="2">Ecole coranique</option>
				    <option value="3">Primaire</option>
					<option value="4">Moyen</option>
					<option value="5">Secondaire </option>
					<option value="6">Universitaire</option>
					<option value="7">Non précis</option>
				</select>
		<label id="lM9">Q9: Profession de la patiente</label>  <input id="M9"     type="txt" name="M9"    value="" placeholder="xxxxxxx"   />
		<label id="lM10">Q10: Niveau d'instruction du conjoint</label>  
		<select id="M10"  name="M10"  >  
					<option value="1">Analphabète</option>
				    <option value="2">Ecole coranique</option>
				    <option value="3">Primaire</option>
					<option value="4">Moyen</option>
					<option value="5">Secondaire </option>
					<option value="6">Universitaire</option>
					<option value="7">Non précis</option>
				</select>
		
		<label id="lM11">Q11: Couverture sociale</label>  
		<select id="M11"  name="M11"  >  
					<option value="1">Oui</option>
				    <option value="2">Non</option>
				    <option value="3">Non précisé</option>
				</select>
		
		<label id="lM12">Q12:Lieu du décès</label>  
		<select id="M12"  name="M12"  >  
					<option value="1">Domicile</option>
				    <option value="2">Maternité publique extrahospitaiière</option>
				    <option value="3">EHS mère/enfant</option>
					<option value="4">EPH</option>
					<option value="5">CHU</option>
					<option value="6">EHU</option>
					<option value="7">Structure de santé privée</option>
				    <option value="8">Autre</option>
				    <option value="9">Si autre, Préciser</option>
				    </select>
		<label id="lM13">Q13:Moment du décès</label>  
		<select id="M13"  name="M13"  >  
					<option value="1">Pendant la grossesse</option>
				    <option value="2">Pendant l'avortement </option>
				    <option value="3">Pendant le travail ou l'accouchement </option>
					<option value="4">Dans les 24 heures suivant l'issue de la grossesse</option>
					<option value="5">Dans les 42 jours suivant un avortement </option>
					<option value="6">Dans les 42 jours suivant un accouchement </option>
					<option value="7">Dans les 42 jours suivant l'issue d'une grossesse molaire</option>
				    <option value="7">Dans les 42 jours suivant l'issue d'une grossesse extra-utérine</option>
				    </select>
		
		<label id="lM14">Q14: NBR de jours  l'acc ou de l'avo, et le décès </label>  <input id="M14"     type="txt" name="M14"    value="" placeholder="xxxxxxx"   />
		<label id="lM15">Q15: Nom de l'assesseur </label>                            <input id="M15"     type="txt" name="M15"    value="" placeholder="xxxxxxx"   />
		<label id="lM16">Q16: Qualité de l'assesseur </label>                        <input id="M16"     type="txt" name="M16"    value="" placeholder="xxxxxxx"   />
		<label id="lM17">Q17: Lieu de travail </label>                               <input id="M17"     type="txt" name="M17"    value="" placeholder="xxxxxxx"   />
		<label id="lM18">Numéro de téléphone</label>                                 <input id="M18"     type="txt" name="M18"    value="" placeholder="xxxxxxx"   />
		<label id="lM19">Adresse email </label>                                      <input id="M19"     type="txt" name="M19"    value="" placeholder="xxxxxxx"   />
		<label id="lM20">Q19:Date de l'enquête</label>                               <input id="M20"     type="txt" name="M20"    value="" placeholder="xxxxxxx"   />
		<?php
		 HTML::Br(57);
		
		
		
			// view::sautligne(22);		
			// $this->label($data['x'],$data['y']+60," Date enquete");$this->txts($data['x']+60+130,$data['y']+50,'DATENS',0,$data['DATENS'],'date');
			// $this->label($data['x'],$data['y']+90," Numéro d'identification"); 
			
			// $this->label($data['x'],$data['y']+120,'Profession de la patiente');
			// $this->label($data['x'],$data['y']+150,"Niveau d'instruction de la patiente");
			// View::label($data['x'],$data['y']+180,'Analphabète');View::radio($data['x']+150,$data['y']+180,"NIP","A");    
			// View::label($data['x'],$data['y']+210,'Ecole coranique');View::radio($data['x']+150,$data['y']+210,"NIP","B");    
			// View::label($data['x'],$data['y']+240,'Primaire');View::radio($data['x']+150,$data['y']+240,"NIP","C");    
			// View::label($data['x'],$data['y']+270,'Moyen');View::radio($data['x']+150,$data['y']+270,"NIP","D");    
			// View::label($data['x'],$data['y']+300,'Secondaire');View::radio($data['x']+150,$data['y']+300,"NIP","E");    
			// View::label($data['x'],$data['y']+330,'Universitaire');View::radio($data['x']+150,$data['y']+330,"NIP","F");    
			// View::label($data['x'],$data['y']+360,'Non précis');View::radioed($data['x']+150,$data['y']+360,"NIP","G");    

			// $this->label($data['x']+300,$data['y']+120,'Profession du conjoint');
			// $this->label($data['x']+280,$data['y']+150,"Niveau d'instruction du conjoint");
			// View::label($data['x']+280,$data['y']+180,'Analphabète');View::radio($data['x']+450,$data['y']+180,"NIC","A");    
			// View::label($data['x']+280,$data['y']+210,'Ecole coranique');View::radio($data['x']+450,$data['y']+210,"NIC","B");    
			// View::label($data['x']+280,$data['y']+240,'Primaire');View::radio($data['x']+450,$data['y']+240,"NIC","C");    
			// View::label($data['x']+280,$data['y']+270,'Moyen');View::radio($data['x']+450,$data['y']+270,"NIC","D");    
			// View::label($data['x']+280,$data['y']+300,'Secondaire');View::radio($data['x']+450,$data['y']+300,"NIC","E");    
			// View::label($data['x']+280,$data['y']+330,'Universitaire');View::radio($data['x']+450,$data['y']+330,"NIC","F");    
			// View::label($data['x']+280,$data['y']+360,'Non précis');View::radioed($data['x']+450,$data['y']+360,"NIC","G");  
			 
			// $this->label($data['x']+520,$data['y']+150,"Couverture sociale");
			// View::label($data['x']+520,$data['y']+180,'OUI');View::radio($data['x']+610,$data['y']+180,"CS","A");    
			// View::label($data['x']+520,$data['y']+210,'NON');View::radio($data['x']+610,$data['y']+210,"CS","B");  
			// View::label($data['x']+520,$data['y']+240,'NON  précisé');View::radioed($data['x']+610,$data['y']+240,"CS","C");  
			  
			// $this->label($data['x']+680,$data['y']+150,"Lieu du décès");
			// View::label($data['x']+680,$data['y']+180,'Domicile');View::radio($data['x']+950,$data['y']+180,"LD","A");    
			// View::label($data['x']+680,$data['y']+210,'Maternité publique extrahospitaiière');View::radio($data['x']+950,$data['y']+210,"LD","B");    
			// View::label($data['x']+680,$data['y']+240,'EHS mère/enfant');View::radio($data['x']+950,$data['y']+240,"LD","C");    
			// View::label($data['x']+680,$data['y']+270,'EPH');View::radio($data['x']+950,$data['y']+270,"LD","D");    
			// View::label($data['x']+680,$data['y']+300,'CHU');View::radio($data['x']+950,$data['y']+300,"LD","E");    
			// View::label($data['x']+680,$data['y']+330,'EHU');View::radio($data['x']+950,$data['y']+330,"LD","F");    
			// View::label($data['x']+680,$data['y']+360,'Structure de santé privée');View::radioed($data['x']+950,$data['y']+360,"LD","G");  
			// View::label($data['x']+680,$data['y']+390,'Autre');View::radioed($data['x']+950,$data['y']+390,"LD","H");  
			// $this->label($data['x']+680,$data['y']+420,"Si autre, Préciser"); 
			  
			// $this->label($data['x']+1030,$data['y']+150,"Moment du décès");
			// View::label($data['x']+1030,$data['y']+180,'Pendant !a grossesse');View::radio($data['x']+1380,$data['y']+180,"MD","A");    
			// View::label($data['x']+1030,$data['y']+210,"Pendant l'avortement ");View::radio($data['x']+1380,$data['y']+210,"MD","B");    
			// View::label($data['x']+1030,$data['y']+240,"Pendant le travail ou l'accouchement");View::radio($data['x']+1380,$data['y']+240,"MD","C");    
			// View::label($data['x']+1030,$data['y']+270,"24 heures suivant l'issue de la grossesse ");View::radio($data['x']+1380,$data['y']+270,"MD","D");    
			// View::label($data['x']+1030,$data['y']+300,"42 jours suivant un avortement");View::radio($data['x']+1380,$data['y']+300,"MD","E");    
			// View::label($data['x']+1030,$data['y']+330,"42 jours suivant un accouchement ");View::radio($data['x']+1380,$data['y']+330,"MD","F");    
			// View::label($data['x']+1030,$data['y']+360,"42 jours suivant l'issue d'une grossesse molaire");View::radioed($data['x']+1380,$data['y']+360,"MD","G");  
			// View::label($data['x']+1030,$data['y']+390,"42 jours suivant l'issue d'une GEU");View::radioed($data['x']+1380,$data['y']+390,"MD","H");  
      
	   ?>
		</div>
		
		<div id="contente_2" class="content"> 
		<h2>Antécédents personnels de la femme</h2>
		<?php
         HTML::Br(17);
        ?>
		</div>
		
		<div id="contente_3" class="content">  
		<h2>Histoire de la grossesse ayant entraîné le décès</h2>    		  
		<?php
        HTML::Br(17);
        ?>
		</div>

		<div id="contente_4" class="content">  
		<h2>Issue de la grossesse</h2>    		  
		<?php
        HTML::Br(17);
        ?>
		</div>
        
		<div id="contente_5" class="content">  
		<h2>Enchaînement des événements ayant mené au décès</h2>    		  
		<?php
        HTML::Br(17);
        ?>
		</div>
        
		<div id="contente_6" class="content">  
		<h2>Caractéristiques de l'établissement où a eu lieu i'issue de la grossesse</h2>    		  
		<?php
        HTML::Br(17);
        ?>
		</div>		
</div>
        <input type="hidden" name="WILAYA" value="<?php echo Session::get('wilaya')  ;?>"/>
		<input type="hidden" name="STRUCTURE" value="<?php echo Session::get('structure')  ;?>"/>
		<input type="hidden" name="STRUCTURED" value="<?php echo Session::get('structure')  ;?>"/>
		<input type="hidden" name="login" value="<?php echo Session::get('login')  ;?>"/>
		<button id="Cleara" onclick="javascript:window.location.reload();return false;">Clear Area</button>
		<input  id="Clearb" type="submit" />
		<button id="Clearl" onclick="javascript:list('<?php echo $_SERVER['SERVER_NAME'];?>');return false;">Lister</button>
</form > 
		
		