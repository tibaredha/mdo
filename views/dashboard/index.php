<div class="item itemsml"><?php echo  sujet ;?></div>
<div class="item itemsmr">
<?php $ctrl="nouveau"; echo "<button id=\"button\"  onclick=\"document.location='".URL."dashboard/".$ctrl."/1';  \"  title=\"Nouveau\">Nouveau</button> " ; ?>
<?php $ctrl="tttt"; echo "<button id=\"button\"     onclick=\"document.location='".URL."dashboard/".$ctrl."/1';  \"  title=\"Par mois\">MDO</button> " ; ?>
<?php  echo "<button id=\"button\"         onclick=\"document.location='".URL."dashboard/search/0/10?o=NOM&q=';  \"  title=\"Par mois\">chercher</button> " ; ?>
</div>
<div class="item itemct">
<?php 
$data = array(
"c"   => 'dashboard',
"m"   => 'search',
"combo"   => array( 
                    "id"     => 'id',
					"Nom_Prenom"     => 'NOMPRENOM',
					"Sexe"           => 'SEXE'
				  ),
"submitvalue" => 'Search',
"cb1" => 'dashboard',"mb1" => 'nouveau',        "tb1" => 'New',   "vb1" => 'New',   "icon1" => 'add.PNG',
"cb2" => 'dashboard',"mb2" => 'imp',            "tb2" => 'Print', "vb2" => 'Print', "icon2" => 'print.PNG',
"cb3" => 'dashboard',"mb3" => 'CGR',            "tb3" => 'graphe',"vb3" => 'graph',"icon3" => 'graph.PNG',
"cb4" => 'dashboard',"mb4" => '',               "tb4" => 'Search',"vb4" => 'Search',"icon4" => 'search.PNG'
);
echo "<form   onsubmit=\"return validateForm11(this);\" name=\"form1\"  action=\"".URL.$data['c']."/".$data['m']."/0/10\" method=\"GET\">" ;
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
?>
</div>
<div class="item itemctl"></div>
<?php 
$colspan=12;				
if (isset($this->userListview)) 
{
echo "<div class=\"item itemclist\">"; 
echo'<table width="100%" border="1" cellpadding="5" cellspacing="1" align="center">';
	echo'<tr bgcolor="#00CED1"   >';
	echo'<th colspan="'.$colspan.'" >';
	echo 'Relevé des maladies a declaration obligatoire'; echo '&nbsp;';		
	echo'</th>';
	echo'</tr>';
	echo'<tr bgcolor="#00CED1">';
	echo'<th >Date Declaration</th>';
	echo'<th >Nom_Prénom</th>';//class="thL"
	echo'<th >Sexe</th>';
	echo'<th >Age</th>';
	echo'<th >Wilaya</th>';
	echo'<th >Commune</th>';
	echo'<th >Adresse</th>';
	echo'<th >Maladie</th>';
	echo'<th >Structure</th>';
	echo'<th >F_MDO</th>';
	echo'<th >Update</th>';
	echo'<th >Delete</th>';
	echo'</tr>';
		
		foreach($this->userListview as $key => $value)
		{ 
            $bgcolor_donate ='#EDF7FF';
			echo "<tr bgcolor=\"".$bgcolor_donate."\"  onmouseover=\"this.style.backgroundColor='#9FF781';\"   onmouseout=\"this.style.backgroundColor='".$bgcolor_donate."';\"  >" ;
			echo '<td align="center">'.HTML::dateUS2FR($value['DATEMDO']).'</td>';
			echo '<td align="left" ><b>'.$value['NOM'].'_'.$value['PRENOM'].'<b></td>';
	        echo '<td align="center"  >'.$value['SEXE'].'</td>';
			echo '<td align="center" >'.$value['AGE'].'</td>';
			echo '<td align="center" >'.HTML::nbrtostring('wil','IDWIL',$value['WILAYAR'],'WILAYAS').'</td>';
			echo '<td align="center" >'.HTML::nbrtostring('com','IDCOM',$value['COMMUNER'],'COMMUNE').'</td>';
			echo '<td align="center" >'.$value['ADRESSE'].'</td>';
			echo '<td align="center" >'.HTML::nbrtostring('mdo','id',$value['MDO'],'mdo').'</td>';
			echo '<td align="center" >'.$value['STRUCTURE'].'</td>';
			echo '<td align="center"  ><a target="_blank" title="editer"    href="'.URL.'PDF/mdo/f_mdoo.php?id='.$value['id'].'" ><img src="'.URL.'public/images/print.png"   width="16" height="16" border="0" alt=""   /></a></td>';
			echo '<td align="center"  ><a target="_blank" title="editer"    href="'.URL.'dashboard/edit/'.$value['id'].'" ><img src="'.URL.'public/images/edit.png"   width="16" height="16" border="0" alt=""   /></a></td>';
			echo '<td align="center"  ><a class="delete" title="supprimer"  href="'.URL.'dashboard/delete/'.$value['id'].'" ><img src="'.URL.'public/images/delete.png"   width="16" height="16" border="0" alt=""   /></a></td>';
			echo'</tr>';	
		}
		$total_count=count($this->userListview1);
		$total_count1=count($this->userListview);
		if ($total_count <= 0 )
		{
			echo '<tr><td align="center" colspan="'.$colspan.'" ><span> No record found for deces </span></td> </tr>';
			header('location: ' . URL . 'dashboard/nouveau/'.$this->userListviewq);
			echo '<tr bgcolor="#00CED1"  ><td align="left"   colspan="'.$colspan.'" ><span>' .$total_count1.'/'.$total_count.' Record(s) found.</span></td></tr>';					
		}
        else
		{		
			echo '<tr bgcolor="#00CED1"><td align="center" colspan="'.$colspan.'" >'. HTML::barre_navigation ($total_count,$this->userListviewl,$this->userListviewo,$this->userListviewq,$this->userListviewp,$this->userListviewb,'dashboard','search').'</td></tr>';	
			
			$limit=$this->userListviewl;		
			$page=$this->userListviewp;
			if ($page <= 0){$prev_page =$this->userListviewp;}else{$prev_page = $page-$limit;}
			$total_page = ceil($total_count/$limit); echo "<br>" ;  
			$prev_url = URL.'dashboard/search/'.$prev_page.'/'.$limit.'?q='.$this->userListviewq.'&o='.$this->userListviewo.'';   
			$next_url = URL.'dashboard/search/'.($page+$limit).'/'.$limit.'?q='.$this->userListviewq.'&o='.$this->userListviewo.'';    
			echo '<tr bgcolor="#00CED1"  ><td align="center" colspan="'.$colspan.'" >';	
			?> 
			<?php echo '<button id="button"'; echo ($page<=0)?'disabled':'';?>                  onclick="document.location='<?php echo $prev_url; ?>'"> <?php echo ""; echo 'Previews</button>&nbsp;<span>[' .$total_count1.'/'.$total_count.' Record(s) found.]</span>'; ?>                              
			<?php echo '<button id="button"'; echo ($page>=$total_page*$limit)?'disabled':'';?> onclick="document.location='<?php echo $next_url; ?>'"> <?php echo "Next</button>";?> 
			<?php 
	    }
echo "</table>";
echo "</div>";		
}
else 
{		
?>
<div class="item itemcl">
<?php 
HTML::multigraphe(30,340,'Décés Par annee et sexe  Arret Au : ','mdo1','DATEMDO','SEXE','M','F','='.Session::get('structure')) ;
?>
</div>
<div class="item itemcr"><?php HTML::Image(URL."public/images/ok.jpg", $width = 400, $height = 440, $border = -1, $title = -1, $map = -1, $name = -1, $class = 'image',$id='image');?></div>
<div class="item itemcfa"><?php $ctrl="tttt"; echo "<button id=\"button\"  onclick=\"document.location='".URL."dashboard/".$ctrl."/1';  \"  title=\"Par mois\">MDO</button> " ; ?></div>
<div class="item itemcfb"><?php $ctrl="tttt"; echo "<button id=\"button\"  onclick=\"document.location='".URL."dashboard/".$ctrl."/1';  \"  title=\"Par mois\">MDO</button> " ; ?></div>
<div class="item itemcfc"><?php $ctrl="tttt"; echo "<button id=\"button\"  onclick=\"document.location='".URL."dashboard/".$ctrl."/1';  \"  title=\"Par mois\">MDO</button> " ; ?></div>
<div class="item itemsb"><?php HTML::Image(URL."public/images/".logo, $width = 400, $height = 440, $border = -1, $title = -1, $map = -1, $name = -1, $class = 'image',$id='image');?></div>
<div class="item itemfsb">DSP DJELFA</div>
<?php 
}
?>















	