<h1>Evaluation mortalité :</h1><hr><br/>
<fieldset id="fieldset0">
    <legend>***</legend>
    <?php HTML::Image(URL."public/images/".logo, $width = 400, $height = 440, $border = -1, $title = -1, $map = -1, $name = -1, $class = 'image',$id='image');?>
</fieldset>
<form action="<?php echo URL; ?>PDF/FDECES.php" method="post">	

<input type="hidden" name="wregion" value="<?php echo Session::get('wilaya')  ;?>"/>
<input type="hidden" name="station" value="<?php echo Session::get('structure')  ;?>"/>
<fieldset id="fieldset1">
    <legend>Date debut-fin</legend>
<input  id="EVA1"   class="cheval"type="txt"  name="Datedebut" placeholder="00-00-0000"/>
<input id="EVA11"  class="cheval"type="txt"  name="Datefin" placeholder="00-00-0000" />
<select id="EVA2"  class="cheval" name="deces"  >  
			<option value="1">Releve</option>  
			<option value="2">Releve+</option>  
			<option value="3">Rapport</option>  
			
</select>	
</fieldset>
<button id="Cleara" onclick="javascript:window.location.reload();return false;">Clear Area</button>
<input id="Clearb" type="submit" />
<button id="Clearc" onclick="javascript:list('<?php echo $_SERVER['SERVER_NAME'];?>');return false;">Lister</button>
</form>

<?php 

HTML::graphemois(30,340,'Décés Par Mois Arret Au  : ','','deceshosp','DINS','',date("Y"),'','='.Session::get('structure'));
// HTML::graphemois(30,340,'Décés Par Mois Arret Au  : ','','deceshosp','DINS','',date("Y"),'','IS NOT NULL');
HTML::Br(17);
HTML::footgraphe(Session::get("structure"),'Evaluation');
HTML::Br(3);		      
			


?>




	