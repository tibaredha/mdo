<div class="item itemsml"><?php echo  sujet ;?></div>
<div class="item itemsmr">MDO</div>
<div class="item itemct">Evaluation</div>

<div class="item itemcl">
<form action="<?php echo URL; ?>PDF/mdo/MDO.php" method="post">	
<input type="hidden" name="wilaya"      value="<?php echo Session::get('wilaya')  ;?>"/>
<input type="hidden" name="structure"   value="<?php echo Session::get('structure')  ;?>"/>
<input type="txt"    name="Datedebut" class="cheval" id="EVA1"   placeholder="00-00-0000"/>
<input type="txt"    name="Datefin"   class="cheval" id="EVA11"  placeholder="00-00-0000" />

<select id="EVA2"  class="cheval" name="Rapport"  >  
			<option value="1">ANNEXE II</option>  
			<option value="2">ANNEXE III /C</option> 
			<option value="3">ANNEXE III /S</option>  
			<option value="4">Rapport</option>  
			
</select>
<?php HTML::MDO('MDO','MDO','0','Maladie') ;?>
<input id="region" type="submit" />
</form> 
</div>
<div class="item itemcr"><?php HTML::Image(URL."public/images/evaluation.jpg", $width = 400, $height = 440, $border = -1, $title = -1, $map = -1, $name = -1, $class = 'image',$id='image');?></div>
<div class="item itemcfa">*</div>
<div class="item itemcfb">*</div>
<div class="item itemcfc">*</div>
<div class="item itemsb"><?php HTML::Image(URL."public/images/".logo, $width = 400, $height = 440, $border = -1, $title = -1, $map = -1, $name = -1, $class = 'image',$id='image');?></div>
<div class="item itemfsb">DSP DJELFA</div>

 <?php 
// HTML::multigraphe(30,340,'Décés Par annee et sexe  Arret Au : ','deceshosp','DINS','SEX','M','F','='.Session::get('structure')) ;
// HTML::footgraphe(Session::get("structure"),'Evaluation');
 ?>





 