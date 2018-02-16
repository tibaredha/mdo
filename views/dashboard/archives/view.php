<h1>Visualisation</h1><hr><br/>
	<fieldset id="fieldset0">
    <legend>***</legend>
	<?php HTML::Image(URL."public/images/".$this->user[0]['id'].".jpg?t=".time(), $width = 400, $height = 440, $border = -1, $title = -1, $map = -1, $name = -1, $class = 'image',$id='image');?>
	</fieldset>
	<form id="Canvas" action="<?php echo URL."dashboard/create/";  ?>"  method="POST"> 
	<fieldset id="fieldset1">
        <legend>***</legend>	
		<input  id="N"     class="cheval" type="text" name="N"   value="N : <?php echo $this->user[0]['N'];?>"/>
		<input  id="Dns"   class="cheval"type="text"  name="Datesigna"  value="<?php echo HTML::dateUS2FR($this->user[0]['Datesigna']);?>"/>	  
		<select id="Sexe"  class="cheval" name="secteur"  >  
			<option value="<?php echo $this->user[0]['secteur'];?>"><?php if ($this->user[0]['secteur']==1) {echo 'Publique';}  else {echo 'Privé';} ?></option>
			<option value="1">Publique</option>
			<option value="0">Privé</option>  
		</select>
		<select id="Sexe"  class="cheval" name="aprouve"  >  
			<option value="<?php echo $this->user[0]['aprouve'];?>"><?php if ($this->user[0]['aprouve']==1) {echo 'Aprouve';}  else {echo 'Non aprouve';}?></option>
			<option value="1">Aprouve</option>
			<option value="0">NAprouve</option>  
		</select>
		<input  id="Dns"   class="cheval"type="date"  name="DateAprouve"  placeholder=" (0000-00-00)"/>
		<?php //HTML::STATIONT('stataprouv','country','30','Station') ;?>
		</fieldset>
		<fieldset id="fieldset1">
        <legend>***</legend>
		<input  id="NomP"  class="cheval" type="text" name="NomP" value="Proprietaire : <?php echo $this->user[0]['NomP'];?>"     />
		<?php
		//HTML::nbrtostring($tb_name,$colonename,$colonevalue,$resultatstring)
		HTML::WILAYA('wil','country',$this->user[0]['N'],HTML::nbrtostring('wil','IDWIL',$this->user[0]['wil'],'WILAYAS')) ;
		HTML::COMMUNE('com','COMMUNEN',$this->user[0]['N'],HTML::nbrtostring('com','IDCOM',$this->user[0]['com'],'COMMUNE')) ;
		?>		
		<input  id="adresse"  class="cheval" type="text" name="adresse" value="adresse : <?php echo $this->user[0]['adresse'];?>"/>
		</fieldset>
		<fieldset id="fieldset1">
		<legend>***</legend>
		<input  id="NomA"  class="cheval" type="text" name="NomA" value="Produit : <?php echo $this->user[0]['NomA'];?>"/>
		<input  id="Dns"  class="cheval"type="text" name="Dns"  value="<?php echo HTML::dateUS2FR($this->user[0]['Dns']);?>"/>
		<?php
		HTML::RACE('Race','cheval',$this->user[0]['Race'],HTML::nbrtostring('race','id',$this->user[0]['Race'],'race')) ;
		HTML::ROBE('Nobo','cheval',$this->user[0]['Nobo'],HTML::nbrtostring('robe','id',$this->user[0]['Nobo'],'robe')) ;
		?>
		<input  id="N"     class="cheval" type="text" name="NPPRODUIT"    value="Puce N° : <?php echo $this->user[0]['NPPRODUIT'];?>"/>
		<select id="Sexe"  class="cheval" name="Sexe"  >  
			<option value=""><?php echo $this->user[0]['Sexe'];?></option>
			<option value="M">Masculin</option>
			<option value="F">Feminin</option>  
		</select>
		</fieldset>
		<fieldset id="fieldset2">
		<legend>***</legend>
		<input  id="Pere" class="cheval"type="text" name="Pere" value="Pere : <?php echo $this->user[0]['Pere'];?>"/>
		<input  id="Dns"  class="cheval"type="text" name="DnsPere"  value="<?php echo HTML::dateUS2FR($this->user[0]['DnsPere']);?>"/>
		<?php
		HTML::RACE('RacePere','cheval',$this->user[0]['RacePere'],HTML::nbrtostring('race','id',$this->user[0]['RacePere'],'race')) ;
		HTML::ROBE('CouleurPere','cheval',$this->user[0]['CouleurPere'],HTML::nbrtostring('robe','id',$this->user[0]['CouleurPere'],'robe')) ;
		?>
		<input  id="N"     class="cheval" type="text" name="NPPERE"    value="Puce N° : <?php echo $this->user[0]['NPPERE'];?>"/>
		</br>
		<input  id="Mere" class="cheval"type="text" name="mere" value="Mere : <?php echo $this->user[0]['mere'];?>"/>
		<input  id="Dns"  class="cheval"type="text" name="DnsMere"  value="<?php echo HTML::dateUS2FR($this->user[0]['DnsMere']);?>"/>
		<?php
		HTML::RACE('RaceMere','cheval',$this->user[0]['RaceMere'],HTML::nbrtostring('race','id',$this->user[0]['RaceMere'],'race')) ;
		HTML::ROBE('CouleurMere','cheval',$this->user[0]['CouleurMere'],HTML::nbrtostring('robe','id',$this->user[0]['CouleurMere'],'robe')) ;
		?>
	    <input  id="N"     class="cheval" type="text" name="NPMERE"    value="Puce N° : <?php echo $this->user[0]['NPMERE'];?>"/>
		</fieldset>
		<fieldset id="fieldset3">
		<legend>***</legend>
		<textarea id="Tete"     name="Tete"    placeholder="Tete"    rows="2" cols="107"><?php echo $this->user[0]['Tete'];?></textarea></br></br>
		<textarea id="Tete"     name="AG"      placeholder="AG"      rows="2" cols="51"><?php echo $this->user[0]['AG'];?></textarea>
		<textarea id="Tete"     name="AD"      placeholder="AD"      rows="2" cols="51"><?php echo $this->user[0]['AD'];?></textarea></br></br>
		<textarea id="Tete"     name="PG"      placeholder="PG"      rows="2" cols="51"><?php echo $this->user[0]['PG'];?></textarea>
		<textarea id="Tete"     name="PD"      placeholder="PD"      rows="2" cols="51"><?php echo $this->user[0]['PD'];?></textarea></br></br>
		<textarea id="Tete"     name="MARQUES" placeholder="MARQUES" rows="2" cols="107"><?php echo $this->user[0]['MARQUES'];?></textarea>
		</fieldset>	
		<button id="Cleard" onclick="javascript:Vacciner(<?php echo $this->user[0]['id'];?>,'<?php echo $_SERVER['SERVER_NAME'];?>');return false;">Vacciner</button>
		<button id="Cleare" onclick="javascript:Bilanter(<?php echo $this->user[0]['id'];?>,'<?php echo $_SERVER['SERVER_NAME'];?>');return false;">Bilanter</button>
		<button id="Clearf" onclick="javascript:Traiter(<?php echo $this->user[0]['id'];?>,'<?php echo $_SERVER['SERVER_NAME'];?>');return false;">Traiter</button>
		<button id="Clearg" onclick="javascript:Saillir(<?php echo $this->user[0]['id'];?>,'<?php echo $_SERVER['SERVER_NAME'];?>');return false;">Saillir</button>
		<button id="Clearh" onclick="javascript:list('<?php echo $_SERVER['SERVER_NAME'];?>');return false;">Lister</button>
	 </form > 