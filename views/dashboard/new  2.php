<h1><a title="Créer une nouvelle  <?php echo  sujet ;?>"  target="_blank"  href="<?php echo URL; ?>tcpdf/docpdf/dz/decesfrx.pdf">Créer une nouvelle  <?php echo  sujet ;?></a></h1><hr><br/>

	<form id="Canvas" action="<?php echo URL."dashboard/create/";  ?>"  method="POST"> 

		<div class="tabbed_area">  
				<ul class="tabs">  
					<li><a href="javascript:tabSwitch('tab_1', 'content_1');" id="tab_1" class="active">1er partie</a></li>  
					<li><a href="javascript:tabSwitch('tab_2', 'content_2');" id="tab_2">2em partie</a></li> 
					<li><a href="javascript:tabSwitch('tab_3', 'content_3');" id="tab_3">3em partie</a></li> 	
				    <li><a href="javascript:tabSwitch('tab_4', 'content_4');" id="tab_4"> إعلان بوفاة   </a></li> 	
				
				</ul>    
				<div id="content_1" class="content">  
				<?php 
				$commune1=HTML::nbrtostring('structure','id',Session::get('structure'),'numcom');
				$commune2=HTML::nbrtostring('structure','id',Session::get('structure'),'com');
				?>
				<label for="WILAYAD"id="lWILAYAD"  >Wilaya:</label>	<?php HTML::WILAYA('WILAYAD','countryd','17000','Djelfa') ;?>
                <label for="COMMUNED"id="lCOMMUNED"  >Commune:</label><?php HTML::COMMUNE('COMMUNED','COMMUNED',$commune1,$commune2) ;?> 
				<label for="DINS"id="lDINS"  >Date deces:</label> <input id="DINS" type="txt"  name="DINS"  placeholder=" 00-00-0000"/>
				                                                  <input id="HINS" type="txt"  name="HINS"  placeholder=" 00:00"/>
				<label for="NOM"   id="lNOM"   >  Nom:   </label> <input id="NOM"    type="txt" name="NOM"    value="" placeholder="xxxxxxx"   />
				<label for="PRENOM"id="lPRENOM"  >Prenom:</label> <input id="PRENOM" type="txt" name="PRENOM" value="" placeholder="xxxxxxx" />
				<label for="FILSDE"id="lFILSDE"  >Père:</label>   <input id="FILSDE" type="txt" name="FILSDE" value="" placeholder="xxxxxxx" />
				<label for="ETDE"id="lETDE"  >Mère:</label>       <input id="ETDE"   type="txt" name="ETDE"   value="" placeholder="xxxxxxx"/>
				<label for="SEXE"id="lSEXE"  >Sexe:</label>
				<select id="SEXE"  name="SEXE"  >  
					<option value="M">Masculin</option>
					<option value="F">Feminin</option>  
				</select>
				<label for="DATENS"id="lDATENS">Nee le:</label><input id="DATENS" type="txt"  name="DATENS" value="" placeholder="00-00-0000" />
				<label for="WILAYAN"id="lWILAYAN">Wilaya:</label><?php HTML::WILAYA('WILAYAN','country','17000','Djelfa') ;?>
				<label for="COMMUNEN"id="lCOMMUNEN">Commune:</label><?php HTML::COMMUNE('COMMUNEN','COMMUNEN',$commune1,$commune2) ;?> 
				<label for="WILAYAR"id="lWILAYAR"  >Wilaya:</label>	<?php HTML::WILAYA('WILAYAR','countryr','17000','Djelfa') ;?>
                <label for="COMMUNER"id="lCOMMUNER"  >Commune:</label><?php HTML::COMMUNE('COMMUNER','COMMUNER',$commune1,$commune2) ;?> 
				<label for="ADRESSE"id="lADRESSE"  >Adresse:</label><input id="ADRESSE" type="text" name="ADRESSE" placeholder="adresse de residence"/>
				<label id="lLD0">lieux du deces:</label>
			    <label id="lLD1">Domicile:</label>            <input id="LD1" type="radio"  name="LD" value="DOM" />
			    <label id="lLD2">Voie publique:</label>       <input id="LD2" type="radio"  name="LD" value="VP" />
			    <label id="lLD3">Autres :</label>             <input id="LD3" type="radio"  name="LD" value="AAP" /><input id="LD6" type="txt"    name="AUTRES" value="" placeholder="xxxxxxx"  />  
			    <label id="lLD4">Structure public:</label>    <input id="LD4" type="radio"  name="LD" value="SSP" checked />            
			    <label id="lLD5">Structure privé:</label>     <input id="LD5" type="radio"  name="LD" value="SSPV" />  
				<label for="DATEHOSPI"id="lDATEHOSPI"  >Date hospitalisation:</label>      <input id="DATEHOSPI" type="txt"     name="DATEHOSPI"  placeholder=" 00-00-0000"/>
				                                              <input id="HEURESHOSPI" type="txt"  name="HEURESHOSPI"  placeholder=" 00:00"/>
				<label for="SERVICEHOSPIT"id="lSERVICEHOSPIT"  >Service :</label><?php HTML::SER(44,44,'SERVICEHOSPIT','deces','servicedeces','21','Service') ;?>	
                <label for="MEDECINHOSPIT"id="lMEDECINHOSPIT"  ><a title="Nouveau Medecin"  href="<?php echo URL."dashboard/createmedecin/".Session::get('structure');?>"> Medecin:</a>  <?php echo'<img src="'.URL.'public/images/addvar.PNG" width="12" height="12" border="0" alt=""   />';?></label><?php HTML::MED(44,44,'MEDECINHOSPIT','deces','medecindeces',Session::get('structure'),'0','Medecin') ;?>  <!--  <input id="MEDECINHOSPIT" type="txt" name="MEDECINHOSPIT"   />-->  
				<label id="lLD7">Signalement médico-légal:</label>
				<label id="lLD8">Obstacle médico-légal a l'inhumation  :</label>                                 <input id="LD8" type="checkbox"  name="OMLI" value="OMLI" /> 
				<label id="lLD9">Mise immédiate en cercueil hermétique en raison du risque de contagion :</label><input id="LD9" type="checkbox"  name="MIEC" value="MIEC" /> 
				<label id="lLD10">Existence d'une prothèse fonctionnant au moyen d'une pile :</label>            <input id="LD10" type="checkbox"  name="EPFP" value="EPFP" />    
			   
				<label id="lProfession">Profession :</label>	
                <?php HTML::Profession(44,44,'Profession','deces','Profession',Session::get('structure'),'0','Profession') ;?>

			   </br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>
				</div>
				
				<div id="content_2" class="content">     		  
				<label id="lCIM0">Partie I : Maladie(s) ou affection(s) morbide (s) ayant directement provoqué le décés:</label>
				<label id="lCIM1">Cause directe :&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a</label><input title="La définition n'inclut pas les symptômes ni les modes de décès"id="CIM1" type="txt" name="CIM1" value="" placeholder="x" />
				<label id="lCIM2">due ou consécutive à : b</label><input title="La définition n'inclut pas les symptômes ni les modes de décès"   id="CIM2" type="txt" name="CIM2" value="" placeholder="x" />
				<label id="lCIM3">due ou consécutive à : c</label><input title="La définition n'inclut pas les symptômes ni les modes de décès"id="CIM3" type="txt" name="CIM3" value="" placeholder="x" />
				<label id="lCIM4">due ou consécutive à : d</label><input title="La définition n'inclut pas les symptômes ni les modes de décès"id="CIM4" type="txt" name="CIM4" value="" placeholder="x" />
				<label id="lCIM00">Partie II : Autres états morbides ayant pu contribuer au décés, non mentionnés en partie 1:</label>
				<label id="lCIM5"> autres etats :</label>            <input id="CIM5" type="txt" name="CIM5" value="" placeholder="x"  />
				
				
				
				
				<label id="lCIM6"> CIM-10 : cause initiale  </label>
				<?php HTML::cim1('CODECIM0','deces','chapitre','0','Chapitre');?> 
				<?php HTML::cim2('CODECIM','0','Categorie') ;?>
				<label id="lCIM7"> * la cause initiale est notée à la dernière ligne</label> 
				
				
				<label id="lCIM01">Cause de décés:</label>
				<label id="lCIM02">Cause naturelle:</label><input  title="Cause endogene(maladie,senescence)"id="CIM02" type="radio"  name="CD" value="CN"checked />
				<label id="lCIM03">Cause viollente:</label><input  title="Cause exogene(accident,scuicide,homicide)"id="CIM03" type="radio"  name="CD" value="CV" />
				<label id="lCIM04">Cause idetermine:</label><input title="Indeterminée(homicide,scuicide,accident)"id="CIM04" type="radio"  name="CD" value="CI" />
				
				<label id="lNDM1">Nature de la mort:</label>
				<label id="lNDM2">Naturelle:</label>          <input id="NDM2" type="radio"  name="NDLM" value="NAT" checked />
				<label id="lNDM3">Accident:</label>           <input id="NDM3" type="radio"  name="NDLM" value="ACC" />
				<label id="lNDM4">auto induite:</label>       <input id="NDM4" type="radio"  name="NDLM" value="AID" />
				<label id="lNDM5">agression:</label>          <input id="NDM5" type="radio"  name="NDLM" value="AGR" />
				<label id="lNDM6">indéterminée:</label>       <input id="NDM6" type="radio"  name="NDLM" value="IND" />
				<label id="lNDM7">Autre (a préciser):</label> <input id="NDM7" type="radio"  name="NDLM" value="AAP" /><input id="NDLMAAP" type="txt" name="NDLMAAP" value="x"/>
				</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br> </br> </br>  
				</div>
				<div id="content_3" class="content"> 
				
				<label for="DECEMAT0"id="lDECEMAT0"  >Décés maternel:</label>
				<label for="DECEMAT"id="lDECEMAT"  >Décés maternel:</label>          <input id="DECEMAT"    type="checkbox"  name="DECEMAT"  /> 
				<label for="DGRO"id="lDGRO">durant la grossesse:</label>             <input id="DGRO"       type="radio"     name="GRS"     value="DGRO" />
				<label for="DACC"id="lDACC">durant l'accouchement:</label>           <input id="DACC"       type="radio"     name="GRS"     value="DACC" />
				<label for="DAVO"id="lDAVO">durant l'avortement:</label>             <input id="DAVO"       type="radio"     name="GRS"     value="DAVO" />
				<label for="AGESTATION"id="lAGESTATION">aprés la gestation:</label>  <input id="AGESTATION" type="radio"     name="GRS"     value="AGESTATION" />
				<label for="IDETER"id="lIDETER">Indéterminé:</label>                 <input id="IDETER"     type="radio"     name="GRS"     value="IDETER" checked />
				<label id="MNP0">Mortinatalité, périnatalité:</label> 
				<label id="MNP1">Grossesse multiple:</label>          <input id="GM"         type="checkbox"  name="GM"  /> 
				<label id="MNP2">Mort-né:</label>                     <input id="MN"         type="checkbox"  name="MN"  /> 
				<label id="MNP3">Age gestationnel:</label>            <input id="AGEGEST"    type="txt" name="AGEGEST"   value="0" />
				<label id="MNP4">Poids a la naissance:</label>        <input id="POIDNSC"    type="txt" name="POIDNSC"   value="0" />
				<label id="MNP5">Age de la mére:</label>              <input id="AGEMERE"    type="txt" name="AGEMERE"   value="0" />
				<label id="MNP6">Si décés périnatal préciser:</label> <input id="DPNAT"      type="checkbox"  name="DPNAT"  /> 
				<input id="EMDPNAT" type="txt"  name="EMDPNAT" value="x" />
				<label id="POSTOPP0">Intervention chirugicale:</label> 
				<label id="POSTOPP1">4 semaines avant le décés:</label>          <input id="POSTOPP2"         type="checkbox"  name="POSTOPP"  /> 
				</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>
				</div>
				
				<div id="content_4" class="content"> 
				<label for="NOM"   id="lNOMAR"   >: اللفـب</label>  <input id="NOMAR"    type="txt" name="NOMAR"    value="" placeholder="xxxxxxx"/>
				<label for="PRENOM"id="lPRENOMAR"  >: الإسم</label>  <input id="PRENOMAR" type="txt" name="PRENOMAR" value="" placeholder="xxxxxxx"/>
				<label for="FILSDE"id="lFILSDEAR"  >: الأب</label>   <input id="FILSDEAR" type="txt" name="FILSDEAR" value="" placeholder="xxxxxxx"/>
				<label for="ETDE"id="lETDEAR"  >: الأم</label>       <input id="ETDEAR"   type="txt" name="ETDEAR"   value="" placeholder="xxxxxxx"/>
				<label for="ETDE"id="lNOMPRENOMAR"  >: إسم و لقب الزوج</label> <input id="NOMPRENOMAR"   type="txt" name="NOMPRENOMAR"   value="" placeholder="xxxxxxx"/>
				
				<label for="ETDE"id="lPROAR"  >: المهنة </label>    <input id="PROAR"    type="txt" name="PROAR"    value="" placeholder="xxxxxxx"/>
				<label for="ETDE"id="lADAR"  >: عنوان الإقامة</label><input id="ADAR"     type="txt" name="ADAR"     value="" placeholder="xxxxxxx"/>
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