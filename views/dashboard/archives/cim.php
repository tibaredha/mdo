<h1><a title="CIM10"  target="_blank"  href="<?php echo URL; ?>PDF/deces/cim10.pdf">CIM10 Chapitre</a></h1><hr><br/>
<?php	
ob_start();
?>
<table  width='95%' border='1' cellpadding='5' cellspacing='1' align='center'>
<tr>
<th style="width:5px;">Idchapitre</th>
<th style="width:300px;">Chapitre</th>
<th style="width:30px;">Nombre</th>
<th style="width:30px;">Pdf</th>
<th style="width:20px;">Categorie</th>
<th style="width:20px;">Update</th>
<th style="width:20px;">Delete</th>
</tr>
<?php		
if (isset($this->userListview)) 
{	
		foreach($this->userListview as $key => $value)
		{ 
		$bgcolor_donate ='#EDF7FF';
        ?>
		<tr bgcolor='<?php echo $bgcolor_donate ;?>' onmouseover="this.style.backgroundColor='#9FF781';" onmouseout="this.style.backgroundColor='<?php echo $bgcolor_donate ;?>';" >
        <?php
		echo '<td align="center"   >';echo $value['IDCHAP'];echo '</td>';
		echo '<td align="LEFT"   >';echo $value['CHAP'];echo '</td>';
		echo '<td align="center"   >';echo  HTML::cimnbr(Session::get('structure'),$value['IDCHAP']);echo '</td>';
		echo '<td align="center" style="width:10px;" bgcolor="#32CD32" ><a target="_blank" title="pdf chapitre"  href="'.URL.'pdf/cim.php?uc='.$value['IDCHAP'].'" ><img src="'.URL.'public/images/b_props.png"   width="16" height="16" border="0" alt=""   /></a></td>';
		
		echo "<td style=\"width:50px;\" align=\"center\" ><a                  title=\"categorie\" href=\"".URL.'dashboard/catecim/'.$value['IDCHAP']."\" ><img  src=\"".URL.'public/images/b_props.png'."\"  width='16' height='16' border='0' alt='' ></a></td>" ;
		echo "<td style=\"width:50px;\" align=\"center\" ><a                  title=\"editer\"    href=\"".URL.'dashboard/editcim/'.$value['IDCHAP']."\" ><img  src=\"".URL.'public/images/edit.PNG'."\"  width='16' height='16' border='0' alt='' ></a></td>" ;
		echo "<td style=\"width:50px;\" align=\"center\" ><a class=\"delete\" title=\"supprimer\" href=\"".URL.'dashboard/deletecim/'.$value['IDCHAP']."\" ><img  src=\"".URL.'public/images/delete.PNG'."\"  width='16' height='16' border='0' alt='' ></a></td>" ;
		echo "</tr>";
		}
		$total_count=count($this->userListview);
		echo '<tr bgcolor="#00CED1"  ><td align="left"   colspan="15" ><span>' .$total_count.' record(s) found.</span></td></tr>';			
}				
echo "</table>";
// view::sautligne(9);
ob_end_flush();
?>
	