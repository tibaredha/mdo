<div class="item itemsml"><?php echo  sujet ;?></div>
<div class="item itemsmr">MDO</div>
<div class="item itemct">
<?php
if (isset($_SESSION['errorlogin'])) {
$sError = '<span id="errorlogin">' . $_SESSION['errorlogin'] . '</span>';		
echo $sError;			
}
else
{
$sError="Update";
echo $sError;
}
?>
</div>
<div class="item itemcl">
<?php
$db_host="localhost";
$db_name="mdo"; 
$db_user="root";
$db_pass="";
$tbl="epsp_hbb";
$cnx = mysql_connect($db_host,$db_user,$db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
$db  = mysql_select_db($db_name,$cnx) ;
mysql_query("SET NAMES 'UTF8' ");
$sql=mysql_query("SELECT * FROM $tbl ");
echo "<table  width='100%' border='1' cellpadding='3' cellspacing='1' align='center'>" ;
while($value=mysql_fetch_array($sql))
{
echo '<tr>';
// echo '<td>';echo $value['id'];echo '</td>';
// echo '<td>';echo $value['STRUCTURE'];echo '</td>';
// echo '<td>';echo $value['DATEMDO'];echo '</td>';
// echo '<td>';echo $value['NOMPRENOM'];echo '</td>';


$NOMPRENOM = $value['NOMPRENOM'];
$valeurs = explode(' ',$NOMPRENOM);$NOM = $valeurs[0];$PRENOM = $valeurs[1];
$NOMPRENOM = $NOM.'_'.$PRENOM;

echo '<td>';echo $NOMPRENOM;echo '</td>';
// echo '<td>';echo $PRENOM;echo '</td>';
// echo '<td>';echo $value['AGE'];echo '</td>';
// echo '<td>';echo $value['SEXE'];echo '</td>';
// echo '<td>';echo $value['WILAYAR'];echo '</td>';
// echo '<td>';echo $value['COMMUNER'];echo '</td>';
// echo '<td>';echo $value['ADRESSE'];echo '</td>';
// echo '<td>';echo $value['MDO'];echo '</td>';
// echo '<td>';echo $value['OBSERVATION'];echo '</td>';
echo '</tr>';
// $sql1 = "INSERT INTO mdo1 (STRUCTURE,DATEMDO,NOM,PRENOM,AGE,SEXE,WILAYAR,COMMUNER,ADRESSE,MDO,OBSERVATION) 
// VALUES (
// '".$value['STRUCTURE']."',
// '".$value['DATEMDO']."',
// '".$value['NOM']."',
// '".$value['PRENOM']."',
// '".$value['AGE']."',
// '".$value['SEXE']."',
// '".$value['WILAYAR']."',
// '".$value['COMMUNER']."',
// '".$value['ADRESSE']."',
// '".$value['MDO']."',
// '".$value['OBSERVATION']."') ";
//$query1 = mysql_query($sql1);					
}
echo "</table>";	
?>
</div>
<div class="item itemcr"><?php HTML::Image(URL."public/images/Login.jpg", $width = 400, $height = 440, $border = -1, $title = -1, $map = -1, $name = -1, $class = 'image',$id='image');?></div>
<div class="item itemcfa">*</div>
<div class="item itemcfb">*</div>
<div class="item itemcfc">*</div>
<div class="item itemsb"><?php HTML::Image(URL."public/images/".logo, $width = 400, $height = 440, $border = -1, $title = -1, $map = -1, $name = -1, $class = 'image',$id='image');?></div>
<div class="item itemfsb">DSP DJELFA</div> 
