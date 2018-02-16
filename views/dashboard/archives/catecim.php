<h1>CIM10 Categorie</h1><hr><br/>
<?php	
ob_start();
?>
<table  width='95%' border='1' cellpadding='5' cellspacing='1' align='center'>
<tr>
<th style="width:10px;">Idchapitre</th>
<th style="width:300px;">Categorie</th>
<th style="width:10px;">Diag_cod</th>
<th style="width:30px;">Nombre</th>
<th style="width:30px;">Pdf</th>
<th style="width:20px;">S/categorie</th>
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
		echo "<td style=\"width:50px;\" align=\"center\" ><a title=\"chapitre\"    href=\"".URL.'dashboard/CIM/'."\" >".$value['c_chapi']."<img  src=\"".URL.'public/images/b_props.png'."\"  width='16' height='16' border='0' alt='' ></a></td>" ;
		echo '<td align="LEFT"   >';echo $value['diag_nom'];echo '</td>';
		echo '<td align="center"   >';echo $value['diag_cod'];echo '</td>';
		echo '<td align="center"   >';echo  HTML::cimnbr1(Session::get('structure'),$value['row_id']);echo '</td>';
		echo '<td align="center" style="width:10px;" bgcolor="#32CD32" ><a target="_blank" title="pdf chapitre"  href="'.URL.'pdf/cimcateg.php?uc='.$value['row_id'].'" ><img src="'.URL.'public/images/b_props.png"   width="16" height="16" border="0" alt=""   /></a></td>';
		echo "<td style=\"width:50px;\" align=\"center\" ><a                  title=\"categorie\" href=\"".URL.'dashboard/Scatecim/'.$value['row_id']."\" ><img  src=\"".URL.'public/images/b_props.png'."\"  width='16' height='16' border='0' alt='' ></a></td>" ;
		echo "<td style=\"width:50px;\" align=\"center\" ><a                  title=\"editer\"    href=\"".URL.'dashboard/editcatecim/'.$value['row_id'].'/'.$value['c_chapi']."\" ><img  src=\"".URL.'public/images/edit.PNG'."\"  width='16' height='16' border='0' alt='' ></a></td>" ;
		echo "<td style=\"width:50px;\" align=\"center\" ><a class=\"delete\" title=\"supprimer\" href=\"".URL.'dashboard/deletecatecim/'.$value['row_id']."\" ><img  src=\"".URL.'public/images/delete.PNG'."\"  width='16' height='16' border='0' alt='' ></a></td>" ;
		echo "</tr>";
		}
		$total_count=count($this->userListview);
		echo '<tr bgcolor="#00CED1"  ><td align="left"   colspan="15" ><span>' .$total_count.' record(s) found.</span></td></tr>';			
}				
echo "</table>";
ob_end_flush();
?>
	