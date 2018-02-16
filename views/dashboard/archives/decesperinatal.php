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
	
	/*document.getElementById('content_3').style.display = 'none';*/ 
	document.getElementById(new_content).style.display = 'block';     
    document.getElementById('tabe_1').className = '';  
    document.getElementById('tabe_2').className = '';  
    document.getElementById('tabe_3').className = '';  
	document.getElementById('tabe_4').className = '';  
	
	/*document.getElementById('tab_3').className = ''; */        
    document.getElementById(new_tab).className = 'active';        
}
</script>
<h1>Décès Périnatal et Néonatal tardif
<a title="DEN/05-438 "  target="_blank"  href="<?php echo URL; ?>tcpdf/docpdf/dz/F2005075">1</a> 
<a title="périnatalité 06-09 "  target="_blank"  href="<?php echo URL; ?>tcpdf/docpdf/dz/périnatalité 06-09">2</a> 
<a title="périnatalité 16-20 "  target="_blank"  href="<?php echo URL; ?>tcpdf/docpdf/dz/périnatalité 16-20">3</a> 

: <?php echo  HTML::nbrtostring('deceshosp','id',$this->user[0]['id'],'NOM').'_'.HTML::nbrtostring('deceshosp','id',$this->user[0]['id'],'PRENOM'); ?></h1><hr><br/>


<form id="Canvas" action="<?php echo URL."pdf/decesperinat.php?uc=".$this->user[0]['id'];  ?>"  method="POST"> 

<div class="tabbed_area">  
        <ul class="tabs">  
            <li><a href="javascript:tabSwitch('tabe_1', 'contente_1');" id="tabe_1" class="active">Section 1</a></li>  
            <li><a href="javascript:tabSwitch('tabe_2', 'contente_2');" id="tabe_2">Section 2</a></li> 
			<li><a href="javascript:tabSwitch('tabe_3', 'contente_3');" id="tabe_3">Section 3</a></li> 	
            <li><a href="javascript:tabSwitch('tabe_4', 'contente_4');" id="tabe_4">Section 4</a></li> 	   
		</ul>    
		
		<div id="contente_1" class="content">  
		<h2>.</h2></br>
		<label id="lp1" >Q1:N° d’ordre de l’enfant</label>   <input id="p1"    type="txt" name="p1"    value="" placeholder="xxxxxxx"/>
		<label id="lp2">Q2:Wilaya</label>                    <input id="p2"    type="txt" name="p2"    value="" placeholder="xxxxxxx"/>
		<label id="lp3">Q3:Commune</label>                   <input id="p3"    type="txt" name="p3"    value="" placeholder="xxxxxxx"/>
		<label id="lp4">Q4:Type de la structure </label></br>
		<select id="p4"  name="p4"  >  
					<option value="1">Hôpital </option>
					<option value="2">Maternité extra hospitalière urbaine </option>
					<option value="3">Maternité extra hospitalière rural</option>
					<option value="4">Maternité extra hospitalière privée</option>
					<option value="5">Autres (à précise</option>
				</select>
		<label id="lp5">Q5:Etat de la naissance</label></br>
		<select id="p5"  name="p5"  >  
					<option value="1">Ne-vivant</option>
					<option value="2">Mort-né </option>
				</select>
		<label id="lp6">Q6:Genre</label></br>
		<select id="p6"  name="p6"  >  
					<option value="1">M</option>
					<option value="2">F</option>
				</select>
		<label id="lp7">Q7:Type de naissance</label></br>
		<select id="p7"  name="p7"  >  
					<option value="1">Unique</option>
					<option value="2">Gémellaire</option>
				    <option value="3">Autre</option>
				</select>
       <?php
       HTML::Br(11);
	   ?>
		</div>
		
		<div id="contente_2" class="content"> 
		<h2>.</h2>
		<label id="lp8">Q8:Rang de naissance</label></br>
		<select id="p8"  name="p8"  >  
					<option value="1">Premier jumeau </option>
					<option value="2">Deuxième jumeau </option>
				    <option value="3">Autre </option>
				</select>
		<label id="lp9">Q9:Date de naissance</label>                    <input id="p9"     type="txt" name="p9"     value="" placeholder="xxxxxxx"   />
		<label id="lp10">Q10:Date de décès pour les Nés vivants </label><input id="p10"    type="txt" name="p10"    value="" placeholder="xxxxxxx"   />
		<label id="lp11">Q11:Poids à la naissance </label>              <input id="p11"    type="txt" name="p11"    value="" placeholder="xxxxxxx"   />
		<label id="lp12">Q12:Apgar à 01 minute </label></br>
		<select id="p12"  name="p12"  >  
					<option value="0">0</option>
					<option value="1">1</option>
				    <option value="2">2</option>
				    <option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
				</select>
		<label id="lp13">Q13:Apgar à 05 minutes </label></br>
		<select id="p13"  name="p13"  >  
					<option value="0">0</option>
					<option value="1">1</option>
				    <option value="2">2</option>
				    <option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
				
				</select>
		<label id="lp14">Q14:Accouchement assisté par </label></br>
		<select id="p14"  name="p14"  >  
					<option value="1">Sage femme </option>
					<option value="2">Médecin </option>
				    <option value="3">Obstétricien</option>
				    <option value="4">Autre </option>
				</select>
		<?php
         HTML::Br(12);
        ?>
		</div>
		
		<div id="contente_3" class="content">  
		<h2>.</h2>    		  
		<label id="lp15">Q15:Causes directes des décès néonatals précoces</label></br>
		<select id="p15"  name="p15"  >  
					<option value="1">Asphyxie  </option>
					<option value="2">Infection  </option>
				    <option value="3">Détresses respiratoires </option>
				    <option value="4">Conditions associées à la prématurité </option>
				    <option value="5">Autre </option>
				</select>
		<label id="lp16">Q16:Causes de décès Pour les morts</label></br>
		<select id="p16"  name="p16"  >  
					<option value="1">Mort-né macéré avec malformation  </option>
					<option value="2">Mort-né macéré sans malformation  </option>
				    <option value="3">Mort-né non macéré avec malformation  </option>
				    <option value="4">Mort-né non macéré sans malformation  </option>
				    <option value="5">Asphyxie intra-partale  </option>
				    <option value="6">Sans précision </option>
				    <option value="7">Autre </option>
				</select>
		<label id="lp17">Q17:Age de la mère(en années)</label>            <input id="p17"    type="txt" name="p17"    value="" placeholder="xxxxxxx"   />
		<label id="lp18">Q18:Nombre de grossesses antérieures</label>     <input id="p18"    type="txt" name="p18"    value="" placeholder="xxxxxxx"   />
		<label id="lp19">Q19:Place dans la fratrie </label>               <input id="p19"    type="txt" name="p19"    value="" placeholder="xxxxxxx"   />
		<label id="lp20">Q20:Nombre de consultations prénatales</label>   <input id="p20"    type="txt" name="p20"    value="" placeholder="xxxxxxx"   />
		<label id="lp21">Q21:Pathologie pendant la grossesse</label></br>
		<select id="p21"  name="p21"  >  
					<option value="1">Diabète  </option>
					<option value="2">HTA  </option>
				    <option value="3">Iso immunisation rhésus  </option>
				    <option value="4">infection genital </option>
				    <option value="5">Infection urinaire prouvée   </option>
				    <option value="6">Autre </option>
				    <option value="7"> Aucune </option>
				</select>
		<?php
        HTML::Br(13);
        ?>
		</div>

		<div id="contente_4" class="content">  
		<h2>.</h2>    		  
		<label id="lp22">Q22:Mode d’accouchement,voie</label>
		<select id="p22"  name="p22"  >  
					<option value="1">Basse naturelle   </option>
					<option value="2">Basse instrumentalisée  </option>
				    <option value="3">Césarienne  </option>
				</select>
		<label id="lp23">Q23:Présentation</label>
		<select id="p23"  name="p23"  >  
					<option value="1">Sommet   </option>
					<option value="2">Autre   </option>
				</select>
		
		
		<label id="lp24">Q24:Age gestationnel</label>
		<select id="p24"  name="p24"  >  
					<option value="1">de 24 à 32 semaines    </option>
					<option value="2">de 32 à 36 semaines   </option>
				    <option value="3">plus de 36 semaines   </option>
				
				</select>
		
		
		<label id="lp25">Q25:Qualité</label>
		<select id="p25"  name="p25"  >  
					<option value="1">Obstétricien   </option>
					<option value="2">Médecin généraliste   </option>
				    <option value="3">Pédiatre   </option>
				     <option value="4">Sage femme  </option>
				</select>
		
		
		
		
		<?php
        HTML::Br(16);
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
		
		