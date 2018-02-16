<div class="item itemsml"><?php echo  sujet ;?></div>
<div class="item itemsmr">
<?php $ctrl="nouveau"; echo "<button id=\"button\"  onclick=\"document.location='".URL."dashboard/".$ctrl."/1';  \"  title=\"Nouveau\">Nouveau</button> " ; ?>
<?php $ctrl="tttt"; echo "<button id=\"button\"     onclick=\"document.location='".URL."dashboard/".$ctrl."/1';  \"  title=\"Par mois\">MDO</button> " ; ?>
<?php  echo "<button id=\"button\"         onclick=\"document.location='".URL."dashboard/search/0/10?o=NOM&q=';  \"  title=\"Par mois\">chercher</button> " ; ?>

</div>
<div class="item itemct">Modifier  malade</div>
<div class="item itemcl">
<form action="<?php echo URL."dashboard/editSave/".$this->user[0]['id'];  ?>" method="post">		
	<input id="DINS"   type="txt"  name="DATEMDO" value="<?php echo view::dateUS2FR($this->user[0]['DATEMDO']);?>"/>
	<?php HTML::MDO('MDO','MDO',$this->user[0]['MDO'],$this->user[0]['MDO']) ;?>
	<?php //HTML::MED(44,44,'MEDECINHOSPIT','mdo','medecindeces',Session::get('structure'),'0','Medecin') ;?></br>
	<input id="NOM"    type="txt" name="NOM"    value="<?php echo $this->user[0]['NOM'];?>" />
    <input id="PRENOM" type="txt" name="PRENOM" value="<?php echo $this->user[0]['PRENOM'];?>" /></br>
	<select id="SEXE"  name="SEXE"  >  
		<option value="<?php echo $this->user[0]['SEXE'];?>"><?php echo $this->user[0]['SEXE'];?></option>
		<option value="M">Masculin</option>
		<option value="F">Feminin</option>  
	</select>
	<input id="DINS" type="txt"  name="AGE" value="<?php echo $this->user[0]['AGE'];?>"  /></br>
	<?php HTML::WILAYA('WILAYAR','countryr',$this->user[0]['WILAYAR'],'Djelfa') ;?>
	<?php HTML::COMMUNE('COMMUNER','COMMUNER',$this->user[0]['COMMUNER'],HTML::nbrtostring('com','IDCOM',$this->user[0]['COMMUNER'],'COMMUNE')) ;?> 
	<input type="text"   name="ADRESSE" id="ADRESSE"      placeholder="Adresse De Residence"/>
	 <input id="PRENOM" type="txt" name="OBSERVATION" value="<?php echo $this->user[0]['OBSERVATION'];?>" />
	<input type="hidden" name="WILAYA"     value="<?php echo Session::get('wilaya')  ;?>"/>
	<input type="hidden" name="STRUCTURE"  value="<?php echo Session::get('structure')  ;?>"/>
	<input type="hidden" name="login"      value="<?php echo Session::get('login')  ;?>"/>
	</br><input type="submit" id="button"  />
</div>
<div class="item itemcr"><?php HTML::Image(URL."public/images/edite.png", $width = 400, $height = 440, $border = -1, $title = -1, $map = -1, $name = -1, $class = 'image',$id='image');?></div>
<div class="item itemcfa">*</div>
<div class="item itemcfb">*</div>
<div class="item itemcfc">*</div>
</form>
<div class="item itemsb"><?php HTML::Image(URL."public/images/".logo, $width = 400, $height = 440, $border = -1, $title = -1, $map = -1, $name = -1, $class = 'image',$id='image');?></div>
<div class="item itemfsb">DSP DJELFA</div>