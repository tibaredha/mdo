<h1>Ajout Médecin</h1><hr><br/>
<fieldset id="fieldset0">
    <legend>***</legend>
    <?php HTML::Image(URL."public/images/".logo, $width = 400, $height = 440, $border = -1, $title = -1, $map = -1, $name = -1, $class = 'image',$id='image');?>
	</fieldset>
<form method="post" action="<?php echo URL;?>dashboard/medecinSave/">

	<fieldset id="fieldset1">
    <legend>Identification</legend>
	
	<input type="hidden" name="wilaya" value="<?php echo Session::get('wilaya')  ;?>"/>
	<input type="hidden" name="structure" value="<?php echo Session::get('structure')  ;?>"/>
	
	Nom   <input type="text" name="Nom"    value="" />
	Prénom<input type="text" name="Prenom" value="" />
	</fieldset>	
	<button id="Cleara" onclick="javascript:window.location.reload();return false;">Clear Area</button>
	<input  id="Clearb" type="submit" />
	<button id="Clearl" onclick="javascript:list('<?php echo $_SERVER['SERVER_NAME'];?>');return false;">Lister</button>
</form>
</br>
<?php
$colspan=4;
echo'<table width="63%" border="1" cellpadding="5" cellspacing="1" align="left">';
	echo'<tr bgcolor="#00CED1"   >';
	echo'<th style="width:10px;"  colspan="'.$colspan.'" >';
	echo 'liste des medecins'; echo '&nbsp;';		
	echo'</th>';
	echo'</tr>';
    echo'</tr>';
	echo'<tr bgcolor="#00CED1">';
	echo'<th style="width:10px;">Id</th>';
	echo'<th style="width:10px;">Nom</th>';
	echo'<th style="width:10px;">Prenom</th>';
	echo'<th style="width:10px;">Sup</th>';
	echo'</tr>';

foreach($this->userListview as $key => $value)
			{ 
			
			$bgcolor_donate ='#EDF7FF';
            echo "<tr bgcolor=\"".$bgcolor_donate."\"  onmouseover=\"this.style.backgroundColor='#9FF781';\"   onmouseout=\"this.style.backgroundColor='".$bgcolor_donate."';\"  >" ;
			echo'<td align="left" >'.$value['id'].'</td>';
			echo'<td align="left" >'.$value['Nom'].'</td>';
			echo'<td align="left" >'.$value['Prenom'].'</td>';
			
			echo '<td align="center" style="width:10px;" ><a class="delete" title="supprimer"  href="'.URL.'dashboard/deletemedecin/'.$value['id'].'" ><img src="'.URL.'public/images/delete.png"   width="16" height="16" border="0" alt=""   /></a></td>';
			echo '</tr>';			
			}
echo "</table>";	

?>



<?php HTML::Br(35);?><?php //echo Session::get('login');?><?php //echo Session::get('id');?>