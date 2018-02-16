<h1>Modification : <?php echo $this->user[0]['NOM'];?> <?php echo $this->user[0]['PRENOM'];?><?php //echo $this->user[0]['id'];?></h1><hr><br/>
	
	<form id="Canvas" action="<?php echo URL."dashboard/editSave/".$this->user[0]['id'];?>"  method="POST"> 
			<div class="tabbed_area">  
				<ul class="tabs">  
					<li><a href="javascript:tabSwitch('tab_1', 'content_1');" id="tab_1" class="active">1er partie</a></li>  
					<li><a href="javascript:tabSwitch('tab_2', 'content_2');" id="tab_2">2em partie</a></li> 
					<li><a href="javascript:tabSwitch('tab_3', 'content_3');" id="tab_3">3em partie</a></li> 	
				    <li><a href="javascript:tabSwitch('tab_4', 'content_4');" id="tab_4">إعلان بوفاة </a></li> 	
				
				</ul>    
				<div id="content_1" class="content">  
				<label for="WILAYAD"id="lWILAYAD"  >Wilaya:</label>	<?php HTML::WILAYA('WILAYAD','countryd',$this->user[0]['WILAYAD'],HTML::nbrtostring('wil','IDWIL',$this->user[0]['WILAYAD'],'WILAYAS')) ;?>
                <label for="COMMUNED"id="lCOMMUNED"  >Commune:</label><?php HTML::COMMUNE('COMMUNED','COMMUNED',$this->user[0]['COMMUNED'],HTML::nbrtostring('com','IDCOM',$this->user[0]['COMMUNED'],'COMMUNE')) ;?> 
				<label for="DINS"id="lDINS"  >Date deces:</label> <input id="DINS" type="txt"  name="DINS"    value="<?php echo HTML::dateUS2FR($this->user[0]['DINS']);?>"/>
				                                                  <input id="HINS" type="txt"  name="HINS"    value="<?php echo $this->user[0]['HINS'];?>"/>
				<label for="NOM"   id="lNOM"   >  Nom:   </label> <input id="NOM"    type="txt" name="NOM"    value="<?php echo $this->user[0]['NOM'];?>" placeholder="xxxxxxx"   />
				<label for="PRENOM"id="lPRENOM"  >Prenom:</label> <input id="PRENOM" type="txt" name="PRENOM" value="<?php echo $this->user[0]['PRENOM'];?>" placeholder="xxxxxxx" />
				<label for="FILSDE"id="lFILSDE"  >Père:</label>   <input id="FILSDE" type="txt" name="FILSDE" value="<?php echo $this->user[0]['FILSDE'];?>" placeholder="xxxxxxx" />
				<label for="ETDE"id="lETDE"  >Mère:</label>       <input id="ETDE"   type="txt" name="ETDE"   value="<?php echo $this->user[0]['ETDE'];?>" placeholder="xxxxxxx"/>
				<label for="SEXE"id="lSEXE"  >Sexe:</label>
				<select id="SEXE"  name="SEXE"  >  
					<option value="<?php echo $this->user[0]['SEX'];?>"><?php echo $this->user[0]['SEX'];?></option>
					<option value="M">Masculin</option>
					<option value="F">Feminin</option>  
				</select>
				<label for="DATENS"id="lDATENS">Nee le:</label><input id="DATENS" type="txt"  name="DATENS" value="<?php echo HTML::dateUS2FR($this->user[0]['DATENAISSANCE'])  ;?>"  />
				<label for="WILAYAN"id="lWILAYAN">Wilaya:</label><?php HTML::WILAYA('WILAYAN','country',$this->user[0]['WILAYA'],HTML::nbrtostring('wil','IDWIL',$this->user[0]['WILAYA'],'WILAYAS')) ;?>
				<label for="COMMUNEN"id="lCOMMUNEN">Commune:</label><?php HTML::COMMUNE('COMMUNEN','COMMUNEN',$this->user[0]['COMMUNE'],HTML::nbrtostring('com','IDCOM',$this->user[0]['COMMUNE'],'COMMUNE')) ;?> 
				<label for="WILAYAR"id="lWILAYAR"  >Wilaya:</label>	<?php HTML::WILAYA('WILAYAR','countryr',$this->user[0]['WILAYAR'],HTML::nbrtostring('wil','IDWIL',$this->user[0]['WILAYAR'],'WILAYAS')) ;?>
                <label for="COMMUNER"id="lCOMMUNER"  >Commune:</label><?php HTML::COMMUNE('COMMUNER','COMMUNER',$this->user[0]['COMMUNER'],HTML::nbrtostring('com','IDCOM',$this->user[0]['COMMUNER'],'COMMUNE')) ;?> 
				<label for="ADRESSE"id="lADRESSE"  >Adresse:</label><input id="ADRESSE" type="text" name="ADRESSE"  value="<?php echo $this->user[0]['ADRESSE'];?>"      />
				<label id="lLD0">lieux du deces:</label>
				<?php
				switch($this->user[0]['LD'])  
				{
				   case 'DOM' :
						{ 
						?>
						<label id="lLD1">Domicile:</label>            <input id="LD1" type="radio"  name="LD" value="DOM" checked />
						<label id="lLD2">Voie publique:</label>       <input id="LD2" type="radio"  name="LD" value="VP" />
						<label id="lLD3">Autres :</label>             <input id="LD3" type="radio"  name="LD" value="AAP" /><input id="LD6" type="txt"    name="AUTRES" value="<?php echo $this->user[0]['AUTRES'];?>"   />  
						<label id="lLD4">Structure public:</label>    <input id="LD4" type="radio"  name="LD" value="SSP"  />            
						<label id="lLD5">Structure privé:</label>     <input id="LD5" type="radio"  name="LD" value="SSPV" />  
						<?php
						break;}
				   case 'VP' :
						{   
						 ?>
						<label id="lLD1">Domicile:</label>            <input id="LD1" type="radio"  name="LD" value="DOM" />
						<label id="lLD2">Voie publique:</label>       <input id="LD2" type="radio"  name="LD" value="VP" checked />
						<label id="lLD3">Autres :</label>             <input id="LD3" type="radio"  name="LD" value="AAP" /><input id="LD6" type="txt"    name="AUTRES" value="<?php echo $this->user[0]['AUTRES'];?>"   />  
						<label id="lLD4">Structure public:</label>    <input id="LD4" type="radio"  name="LD" value="SSP" />            
						<label id="lLD5">Structure privé:</label>     <input id="LD5" type="radio"  name="LD" value="SSPV" />  
						<?php
						break;}
				   case 'AAP' :
						{   
					    ?>
						<label id="lLD1">Domicile:</label>            <input id="LD1" type="radio"  name="LD" value="DOM" />
						<label id="lLD2">Voie publique:</label>       <input id="LD2" type="radio"  name="LD" value="VP" />
						<label id="lLD3">Autres :</label>             <input id="LD3" type="radio"  name="LD" value="AAP" checked /><input id="LD6" type="txt"    name="AUTRES" value="<?php echo $this->user[0]['AUTRES'];?>"   />  
						<label id="lLD4">Structure public:</label>    <input id="LD4" type="radio"  name="LD" value="SSP"  />            
						<label id="lLD5">Structure privé:</label>     <input id="LD5" type="radio"  name="LD" value="SSPV" />  
						<?php
						break;}
					 case 'SSP' :
						{   
						?>
						<label id="lLD1">Domicile:</label>            <input id="LD1" type="radio"  name="LD" value="DOM" />
						<label id="lLD2">Voie publique:</label>       <input id="LD2" type="radio"  name="LD" value="VP" />
						<label id="lLD3">Autres :</label>             <input id="LD3" type="radio"  name="LD" value="AAP" /><input id="LD6" type="txt"    name="AUTRES" value="<?php echo $this->user[0]['AUTRES'];?>"   />  
						<label id="lLD4">Structure public:</label>    <input id="LD4" type="radio"  name="LD" value="SSP" checked />            
						<label id="lLD5">Structure privé:</label>     <input id="LD5" type="radio"  name="LD" value="SSPV" />  
						<?php
						break;}
					 case 'SSPV' :
						{   
						?>
						<label id="lLD1">Domicile:</label>            <input id="LD1" type="radio"  name="LD" value="DOM" />
						<label id="lLD2">Voie publique:</label>       <input id="LD2" type="radio"  name="LD" value="VP" />
						<label id="lLD3">Autres :</label>             <input id="LD3" type="radio"  name="LD" value="AAP" /><input id="LD6" type="txt"    name="AUTRES" value="<?php echo $this->user[0]['AUTRES'];?>"   />  
						<label id="lLD4">Structure public:</label>    <input id="LD4" type="radio"  name="LD" value="SSP"  />            
						<label id="lLD5">Structure privé:</label>     <input id="LD5" type="radio"  name="LD" value="SSPV"checked />  
						<?php
						break;}			
				}		
				?>
				<label for="DATEHOSPI"id="lDATEHOSPI"  >Date hospitalisation:</label>      <input id="DATEHOSPI"   type="text"  name="DATEHOSPI"    value="<?php echo HTML::dateUS2FR($this->user[0]['DATEHOSPI']);?>"/>
				                                                                           <input id="HEURESHOSPI" type="text"  name="HEURESHOSPI"  value="<?php echo $this->user[0]['HEURESHOSPI'];?>"/>
				<label for="SERVICEHOSPIT"id="lSERVICEHOSPIT"  >Service :</label><?php HTML::SER(44,44,'SERVICEHOSPIT','deces','servicedeces',$this->user[0]['SERVICEHOSPIT'],HTML::nbrtostring('servicedeces','id',$this->user[0]['SERVICEHOSPIT'],'service')) ;?>	
                <label for="MEDECINHOSPIT"id="lMEDECINHOSPIT"  ><a title="Nouveau Medecin"  href="<?php echo URL."dashboard/createmedecin/";?>"> Medecin: <?php echo'<img src="'.URL.'public/images/addvar.PNG"   width="16" height="16" border="0" alt=""   />';?> </a></label><?php HTML::MED(44,44,'MEDECINHOSPIT','deces','medecindeces',Session::get('structure'),$this->user[0]['MEDECINHOSPIT'],$this->user[0]['MEDECINHOSPIT']) ;?>  <!--  <input id="MEDECINHOSPIT" type="txt" name="MEDECINHOSPIT"   />-->  
				
				<label id="lLD7">Signalement médico-légal:</label>
				<?php
				if ($this->user[0]['OMLI']=='1') 
				{
				?>
				<label id="lLD8">Obstacle médico-légal a l'inhumation  :</label><input id="LD8" type="checkbox"  name="OMLI" value="OMLI" checked /> 
				<?php
				} 
				else 
				{
				?>
				<label id="lLD8">Obstacle médico-légal a l'inhumation  :</label><input id="LD8" type="checkbox"  name="OMLI" value="OMLI"  /> 
				<?php
				}

				if ($this->user[0]['MIEC']=='1') 
				{
				?>
				<label id="lLD9">Mise immédiate en cercueil hermétique en raison du risque de contagion :</label><input id="LD9" type="checkbox"  name="MIEC" value="MIEC" checked /> 
				<?php
				} 
				else 
				{
				?>
				<label id="lLD9">Mise immédiate en cercueil hermétique en raison du risque de contagion :</label><input id="LD9" type="checkbox"  name="MIEC" value="MIEC"  /> 
				<?php
				}

				if ($this->user[0]['EPFP']=='1') 
				{
				?>
	           <label id="lLD10">Existence d'une prothèse fonctionnant au moyen d'une pile :</label><input id="LD10" type="checkbox"  name="EPFP" value="EPFP" checked  />    
				<?php
				} 
				else 
				{
				?>
				<label id="lLD10">Existence d'une prothèse fonctionnant au moyen d'une pile :</label><input id="LD10" type="checkbox"  name="EPFP" value="EPFP"   />    
				<?php
				}
				?>
				
				<label id="lProfession">Profession :</label>	
                <?php HTML::Profession(44,44,'Profession','deces','Profession',Session::get('structure'),$this->user[0]['Profession'],HTML::nbrtostring('Profession','id',$this->user[0]['Profession'],'Profession')) ;?>
				
				</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>
				</div>

				<div id="content_2" class="content">     		  
				<label id="lCIM0">Partie I : Maladie(s) ou affection(s) morbide (s) ayant directement provoqué le décés:</label>
				<label id="lCIM1">Cause directe(intiale) :&nbsp; a</label><input id="CIM1" type="txt" name="CIM1" value="<?php echo $this->user[0]['CIM1'];?>"/>
				<label id="lCIM2">due ou consécutive a : b</label><input id="CIM2" type="txt" name="CIM2" value="<?php echo $this->user[0]['CIM2'];?>"/>
				<label id="lCIM3">due ou consécutive a : c</label><input id="CIM3" type="txt" name="CIM3" value="<?php echo $this->user[0]['CIM3'];?>"/>
				<label id="lCIM4">due ou consécutive a : d</label><input id="CIM4" type="txt" name="CIM4" value="<?php echo $this->user[0]['CIM4'];?>"/>
				<label id="lCIM00">Partie II : Autres états morbides ayant pu contribuer au décés, non mentionnés en partie 1:</label>
				<label id="lCIM5"> autres etats :</label>            <input id="CIM5" type="txt" name="CIM5" value="<?php echo $this->user[0]['CIM5'];?>"/>
				<label id="lCIM6"> Code CIM-10 :</label>
				<label id="lCIM01">Cause de décés:</label>
				<?php
				switch($this->user[0]['CD'])  
				{
				   case 'CN' :
						{ 
						?>
						<label id="lCIM02">Cause naturelle:</label><input  id="CIM02" type="radio"  name="CD" value="CN"checked />
						<label id="lCIM03">Cause viollente:</label><input  id="CIM03" type="radio"  name="CD" value="CV" />
						<label id="lCIM04">Cause idetermine:</label><input id="CIM04" type="radio"  name="CD" value="CI" />
						<?php
						break;}
				   case 'CV' :
						{   
						 ?>
						<label id="lCIM02">Cause naturelle:</label><input  id="CIM02" type="radio"  name="CD" value="CN" />
						<label id="lCIM03">Cause viollente:</label><input  id="CIM03" type="radio"  name="CD" value="CV" checked />
						<label id="lCIM04">Cause idetermine:</label><input id="CIM04" type="radio"  name="CD" value="CI" />
						<?php
						break;}
				   case 'CI' :
						{   
						 ?>
						<label id="lCIM02">Cause naturelle:</label><input  id="CIM02" type="radio"  name="CD" value="CN" />
						<label id="lCIM03">Cause viollente:</label><input  id="CIM03" type="radio"  name="CD" value="CV" />
						<label id="lCIM04">Cause idetermine:</label><input id="CIM04" type="radio"  name="CD" value="CI" checked />
						<?php 
						break;}		
				}		
				?>
				<?php HTML::cim1('CODECIM0','deces','chapitre',$this->user[0]['CODECIM0'],HTML::nbrtostring('chapitre','IDCHAP',$this->user[0]['CODECIM0'],'CHAP'));?> 
				<?php HTML::cim2('CODECIM',$this->user[0]['CODECIM'],HTML::nbrtostring('cim','row_id',$this->user[0]['CODECIM'],'diag_nom')) ;?>
				<label id="lNDM1">Nature de la mort:</label>

				<?php
				switch($this->user[0]['NDLM'])  
				{
				    case 'NAT' :
						{   
						?>
						<label id="lNDM2">Naturelle:</label>          <input id="NDM2" type="radio"  name="NDLM" value="NAT" checked />
						<label id="lNDM3">Accident:</label>           <input id="NDM3" type="radio"  name="NDLM" value="ACC" />
						<label id="lNDM4">auto induite:</label>       <input id="NDM4" type="radio"  name="NDLM" value="AID" />
						<label id="lNDM5">agression:</label>          <input id="NDM5" type="radio"  name="NDLM" value="AGR" />
						<label id="lNDM6">indéterminée:</label>       <input id="NDM6" type="radio"  name="NDLM" value="IND" />
						<label id="lNDM7">Autre (a préciser):</label> <input id="NDM7" type="radio"  name="NDLM" value="AAP" /><input id="NDLMAAP" type="txt" name="NDLMAAP" value="<?php echo $this->user[0]['NDLMAAP'];?>"/>
						
						<?php
						break;}
					 case 'ACC' :
						{   
						?>
						<label id="lNDM2">Naturelle:</label>          <input id="NDM2" type="radio"  name="NDLM" value="NAT"  />
						<label id="lNDM3">Accident:</label>           <input id="NDM3" type="radio"  name="NDLM" value="ACC" checked />
						<label id="lNDM4">auto induite:</label>       <input id="NDM4" type="radio"  name="NDLM" value="AID" />
						<label id="lNDM5">agression:</label>          <input id="NDM5" type="radio"  name="NDLM" value="AGR" />
						<label id="lNDM6">indéterminée:</label>       <input id="NDM6" type="radio"  name="NDLM" value="IND" />
						<label id="lNDM7">Autre (a préciser):</label> <input id="NDM7" type="radio"  name="NDLM" value="AAP" /><input id="NDLMAAP" type="txt" name="NDLMAAP" value="<?php echo $this->user[0]['NDLMAAP'];?>"/>
						
						<?php
						break;}
					 case 'AID' :
						{ 
						?>
						<label id="lNDM2">Naturelle:</label>          <input id="NDM2" type="radio"  name="NDLM" value="NAT"  />
						<label id="lNDM3">Accident:</label>           <input id="NDM3" type="radio"  name="NDLM" value="ACC" />
						<label id="lNDM4">auto induite:</label>       <input id="NDM4" type="radio"  name="NDLM" value="AID" checked />
						<label id="lNDM5">agression:</label>          <input id="NDM5" type="radio"  name="NDLM" value="AGR" />
						<label id="lNDM6">indéterminée:</label>       <input id="NDM6" type="radio"  name="NDLM" value="IND" />
						<label id="lNDM7">Autre (a préciser):</label> <input id="NDM7" type="radio"  name="NDLM" value="AAP" /><input id="NDLMAAP" type="txt" name="NDLMAAP" value="<?php echo $this->user[0]['NDLMAAP'];?>"/>
								
						<?php
						break;}
					 case 'AGR' :
						{   
						?>
						<label id="lNDM2">Naturelle:</label>          <input id="NDM2" type="radio"  name="NDLM" value="NAT"  />
						<label id="lNDM3">Accident:</label>           <input id="NDM3" type="radio"  name="NDLM" value="ACC" />
						<label id="lNDM4">auto induite:</label>       <input id="NDM4" type="radio"  name="NDLM" value="AID" />
						<label id="lNDM5">agression:</label>          <input id="NDM5" type="radio"  name="NDLM" value="AGR" checked />
						<label id="lNDM6">indéterminée:</label>       <input id="NDM6" type="radio"  name="NDLM" value="IND" />
						<label id="lNDM7">Autre (a préciser):</label> <input id="NDM7" type="radio"  name="NDLM" value="AAP" /><input id="NDLMAAP" type="txt" name="NDLMAAP" value="<?php echo $this->user[0]['NDLMAAP'];?>"/>
						
						<?php
						break;}
					 case 'IND' :
						{   
						?>
						<label id="lNDM2">Naturelle:</label>          <input id="NDM2" type="radio"  name="NDLM" value="NAT"  />
						<label id="lNDM3">Accident:</label>           <input id="NDM3" type="radio"  name="NDLM" value="ACC" />
						<label id="lNDM4">auto induite:</label>       <input id="NDM4" type="radio"  name="NDLM" value="AID" />
						<label id="lNDM5">agression:</label>          <input id="NDM5" type="radio"  name="NDLM" value="AGR" />
						<label id="lNDM6">indéterminée:</label>       <input id="NDM6" type="radio"  name="NDLM" value="IND" checked />
						<label id="lNDM7">Autre (a préciser):</label> <input id="NDM7" type="radio"  name="NDLM" value="AAP" /><input id="NDLMAAP" type="txt" name="NDLMAAP" value="<?php echo $this->user[0]['NDLMAAP'];?>"/>
						
						<?php
						break;}
					 case 'AAP' :
						{   
						?>
						<label id="lNDM2">Naturelle:</label>          <input id="NDM2" type="radio"  name="NDLM" value="NAT"  />
						<label id="lNDM3">Accident:</label>           <input id="NDM3" type="radio"  name="NDLM" value="ACC" />
						<label id="lNDM4">auto induite:</label>       <input id="NDM4" type="radio"  name="NDLM" value="AID" />
						<label id="lNDM5">agression:</label>          <input id="NDM5" type="radio"  name="NDLM" value="AGR" />
						<label id="lNDM6">indéterminée:</label>       <input id="NDM6" type="radio"  name="NDLM" value="IND" />
						<label id="lNDM7">Autre (a préciser):</label> <input id="NDM7" type="radio"  name="NDLM" value="AAP" checked /><input id="NDLMAAP" type="txt" name="NDLMAAP" value="<?php echo $this->user[0]['NDLMAAP'];?>"/>
						<?php
						break;}		
				}			
				?> 
                </br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>  
				</div>
				
				<div id="content_3" class="content"> 
				<label for="DECEMAT0"id="lDECEMAT0">Décés maternel:</label>
				
				<?php
				if ($this->user[0]['DECEMAT']=='1') 
				{
				?> 
				<label for="DECEMAT"id="lDECEMAT">Décés maternel:</label><input id="DECEMAT"    type="checkbox"  name="DECEMAT" checked /> 
				<?php
				} 
				else 
				{
				?> 
				<label for="DECEMAT"id="lDECEMAT">Décés maternel:</label><input id="DECEMAT"    type="checkbox"  name="DECEMAT"  /> 
				<?php
				}
				switch($this->user[0]['GRS'])  
				{
				   case 'DGRO' :
						{    
						?> 
						<label for="DGRO"id="lDGRO">durant la grossesse:</label>             <input id="DGRO"       type="radio"     name="GRS"     value="DGRO" checked />
						<label for="DACC"id="lDACC">durant l'accouchement:</label>           <input id="DACC"       type="radio"     name="GRS"     value="DACC" />
						<label for="DAVO"id="lDAVO">durant l'avortement:</label>             <input id="DAVO"       type="radio"     name="GRS"     value="DAVO" />
						<label for="AGESTATION"id="lAGESTATION">aprés la gestation:</label>  <input id="AGESTATION" type="radio"     name="GRS"     value="AGESTATION" />
						<label for="IDETER"id="lIDETER">Indéterminé:</label>                 <input id="IDETER"     type="radio"     name="GRS"     value="IDETER"  />
				        <?php
						break;}
				  case 'DACC' :
						{    
						?> 
						<label for="DGRO"id="lDGRO">durant la grossesse:</label>             <input id="DGRO"       type="radio"     name="GRS"     value="DGRO" />
						<label for="DACC"id="lDACC">durant l'accouchement:</label>           <input id="DACC"       type="radio"     name="GRS"     value="DACC" checked />
						<label for="DAVO"id="lDAVO">durant l'avortement:</label>             <input id="DAVO"       type="radio"     name="GRS"     value="DAVO" />
						<label for="AGESTATION"id="lAGESTATION">aprés la gestation:</label>  <input id="AGESTATION" type="radio"     name="GRS"     value="AGESTATION" />
						<label for="IDETER"id="lIDETER">Indéterminé:</label>                 <input id="IDETER"     type="radio"     name="GRS"     value="IDETER"  />
				       <?php
						break;}
				  case 'DAVO' :
						{    
						?> 
						<label for="DGRO"id="lDGRO">durant la grossesse:</label>             <input id="DGRO"       type="radio"     name="GRS"     value="DGRO" />
						<label for="DACC"id="lDACC">durant l'accouchement:</label>           <input id="DACC"       type="radio"     name="GRS"     value="DACC" />
						<label for="DAVO"id="lDAVO">durant l'avortement:</label>             <input id="DAVO"       type="radio"     name="GRS"     value="DAVO" checked />
						<label for="AGESTATION"id="lAGESTATION">aprés la gestation:</label>  <input id="AGESTATION" type="radio"     name="GRS"     value="AGESTATION" />
						<label for="IDETER"id="lIDETER">Indéterminé:</label>                 <input id="IDETER"     type="radio"     name="GRS"     value="IDETER"  />
				       <?php 
						break;}
				  case 'AGESTATION' :
						{    
						?> 
						<label for="DGRO"id="lDGRO">durant la grossesse:</label>             <input id="DGRO"       type="radio"     name="GRS"     value="DGRO" />
						<label for="DACC"id="lDACC">durant l'accouchement:</label>           <input id="DACC"       type="radio"     name="GRS"     value="DACC" />
						<label for="DAVO"id="lDAVO">durant l'avortement:</label>             <input id="DAVO"       type="radio"     name="GRS"     value="DAVO" />
						<label for="AGESTATION"id="lAGESTATION">aprés la gestation:</label>  <input id="AGESTATION" type="radio"     name="GRS"     value="AGESTATION" checked />
						<label for="IDETER"id="lIDETER">Indéterminé:</label>                 <input id="IDETER"     type="radio"     name="GRS"     value="IDETER"  />
				        <?php 
						break;}
				  case 'IDETER' :
						{    
						?> 
						<label for="DGRO"id="lDGRO">durant la grossesse:</label>             <input id="DGRO"       type="radio"     name="GRS"     value="DGRO" />
						<label for="DACC"id="lDACC">durant l'accouchement:</label>           <input id="DACC"       type="radio"     name="GRS"     value="DACC" />
						<label for="DAVO"id="lDAVO">durant l'avortement:</label>             <input id="DAVO"       type="radio"     name="GRS"     value="DAVO" />
						<label for="AGESTATION"id="lAGESTATION">aprés la gestation:</label>  <input id="AGESTATION" type="radio"     name="GRS"     value="AGESTATION" />
						<label for="IDETER"id="lIDETER">Indéterminé:</label>                 <input id="IDETER"     type="radio"     name="GRS"     value="IDETER" checked />
				        <?php 
						break;}		
				}
				?>
                <label id="MNP0">Mortinatalité, périnatalité:</label> 
				<?php
				if ($this->user[0]['GM']=='1') 
				{
				?>
				<label id="MNP1">Grossesse multiple:</label><input id="GM" type="checkbox"  name="GM" checked /> 
				<?php
				} 
				else 
				{
				?>
				<label id="MNP1">Grossesse multiple:</label><input id="GM" type="checkbox"  name="GM"  /> 
				<?php
				}
				?>
				
				<?php
				if ($this->user[0]['MN']=='1') 
				{
				?>
				<label id="MNP2">Mort-né:</label><input id="MN" type="checkbox"  name="MN" checked /> 
				<?php
				} 
				else 
				{
				?>
				<label id="MNP2">Mort-né:</label><input id="MN" type="checkbox"  name="MN"  /> 
				<?php
				}
				?>
				<label id="MNP3">Age gestationnel:</label>            <input id="AGEGEST"    type="txt" name="AGEGEST"   value="<?php echo $this->user[0]['AGEGEST'];?>" />
				<label id="MNP4">Poids a la naissance:</label>        <input id="POIDNSC"    type="txt" name="POIDNSC"   value="<?php echo $this->user[0]['POIDNSC'];?>" />
				<label id="MNP5">Age de la mére:</label>              <input id="AGEMERE"    type="txt" name="AGEMERE"   value="<?php echo $this->user[0]['AGEMERE'];?>" />
				<?php
				if ($this->user[0]['DPNAT']=='1') 
				{
				?>
				<label id="MNP6">Si décés périnatal préciser:</label> <input id="DPNAT" type="checkbox"  name="DPNAT" checked /> 
				<input id="EMDPNAT" type="txt"  name="EMDPNAT" value="<?php echo $this->user[0]['EMDPNAT'];?>" />
				<?php
				} 
				else 
				{
				?>
				<label id="MNP6">Si décés périnatal préciser:</label> <input id="DPNAT" type="checkbox"  name="DPNAT"  /> 
				<input id="EMDPNAT" type="txt"  name="EMDPNAT" value="<?php echo $this->user[0]['EMDPNAT'];?>" />
				<?php
				}
				?>
				<label id="POSTOPP0">Intervention chirugicale:</label>
				<?php
				if ($this->user[0]['POSTOPP']=='1') 
				{
				?>
				<label id="POSTOPP1">4 semaines avant le décés:</label>          <input id="POSTOPP2"         type="checkbox"  name="POSTOPP" checked /> 
				<?php
				} 
				else 
				{
				?>
				<label id="POSTOPP1">4 semaines avant le décés:</label>          <input id="POSTOPP2"         type="checkbox"  name="POSTOPP"  /> 
				<?php
				}
				?>			
				</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>
				</div>
				<div id="content_4" class="content"> 
				<label for="NOM"   id="lNOMAR"   >: اللفـب</label>  <input id="NOMAR"    type="txt" name="NOMAR"    value="<?php echo $this->user[0]['NOMAR'];?>" />
				<label for="PRENOM"id="lPRENOMAR"  >: الإسم</label>  <input id="PRENOMAR" type="txt" name="PRENOMAR" value="<?php echo $this->user[0]['PRENOMAR'];?>" />
				<label for="FILSDE"id="lFILSDEAR"  >: الأب</label>   <input id="FILSDEAR" type="txt" name="FILSDEAR" value="<?php echo $this->user[0]['FILSDEAR'];?>" />
				<label for="ETDE"id="lETDEAR"  >: الأم</label>       <input id="ETDEAR"   type="txt" name="ETDEAR"   value="<?php echo $this->user[0]['ETDEAR'];?>" />
				<label for="ETDE"id="lNOMPRENOMAR"  >: الإسم اللفـب</label> <input id="NOMPRENOMAR"   type="txt" name="NOMPRENOMAR"    value="<?php echo $this->user[0]['NOMPRENOMAR'];?>"/>
				<label for="ETDE"id="lPROAR"  >: المهنة </label>    <input id="PROAR"    type="txt" name="PROAR"    value="<?php echo $this->user[0]['PROAR'];?>" />
				<label for="ETDE"id="lADAR"  >: عنوان الإقامة</label><input id="ADAR"     type="txt" name="ADAR"     value="<?php echo $this->user[0]['ADAR'];?>" />
				</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>
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