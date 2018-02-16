<h1><a title="Releve des causes de deces"  target="_blank"  href="<?php echo URL; ?>tcpdf/docpdf/dz/decesfrx.pdf">Relevé des causes de décés</a></h1><hr><br/>

<?php 
// html::munu('cheval'); 
ob_start();


// verifsession();	
// view::button('deces','');
// lang(Session::get('lang'));
// 
// view::munu('deces'); 
// $x=30;
// $y=220;
// echo "<div class=\"mydiv\" style=\" position:absolute;left:".$x."px;top:".$y."px;\">";	
?>
<form method="post" action="<?php echo URL.'pdf/demographie.php' ;?>">
<table id="demographie"  border="1" cellpadding="5" cellspacing="1" align="center"  >   
  <tr >
    <th>Effectifs</th>
    <th>Trimestre</th> 
    <th>Enregistrés a l'etat civil</th>
    <th>Enregistrés en milieu assisté</th>
    <th>Observations</th>
 </tr> 
  <tr>
    <td rowspan="5"  >Naissances vivantes </td>
    <td>1er Trimestre</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
 <tr>
    <td>2eme Trimestre</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
 <tr>
    <td>3eme Trimestre</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
  <tr>
    <td>4eme Trimestre</td> 
   <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
 <tr>
    <td>Total</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
  
  
    <tr>
    <td rowspan="5"  >Décés tout age confondus </td>
    <td>1er Trimestre</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
 <tr>
    <td>2eme Trimestre</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
 <tr>
    <td>3eme Trimestre</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
  <tr>
    <td>4eme Trimestre</td> 
   <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
 <tr>
    <td>Total</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
  
    <tr>
    <td rowspan="5"  >Décés des enfants de moins d'un an</td>
    <td>1er Trimestre</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
 <tr>
    <td>2eme Trimestre</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
 <tr>
    <td>3eme Trimestre</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
  <tr>
    <td>4eme Trimestre</td> 
   <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
 <tr>
    <td>Total</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
  
   <tr>
    <td rowspan="5"  >Décés des nouveaux nées (0-6 jours)</td>
    <td>1er Trimestre</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
 <tr>
    <td>2eme Trimestre</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
 <tr>
    <td>3eme Trimestre</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
  <tr>
    <td>4eme Trimestre</td> 
   <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
 <tr>
    <td>Total</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>

  
   <tr>
    <td rowspan="5"  >Décés des nouveaux nées (7-28 jours)</td>
    <td>1er Trimestre</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
 <tr>
    <td>2eme Trimestre</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
 <tr>
    <td>3eme Trimestre</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
  <tr>
    <td>4eme Trimestre</td> 
   <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
 <tr>
    <td>Total</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
  
  
   <tr>
    <td rowspan="5"  >Décés des nouveaux nées (0-28 jours)</td>
    <td>1er Trimestre</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
 <tr>
    <td>2eme Trimestre</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
 <tr>
    <td>3eme Trimestre</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
  <tr>
    <td>4eme Trimestre</td> 
   <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
 <tr>
    <td>Total</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
  
   <tr>
    <td rowspan="5"  >Mort nés</td>
    <td>1er Trimestre</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
 <tr>
    <td>2eme Trimestre</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
 <tr>
    <td>3eme Trimestre</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
  <tr>
    <td>4eme Trimestre</td> 
   <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
 <tr>
    <td>Total</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
  
  
   <tr>
    <td rowspan="5"  >Décés maternels</td>
    <td>1er Trimestre</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
 <tr>
    <td>2eme Trimestre</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
 <tr>
    <td>3eme Trimestre</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
  <tr>
    <td>4eme Trimestre</td> 
   <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
 <tr>
    <td>Total</td> 
    <td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
	<td><input type="text" name="lname" value="1" ></td>
  </tr>
  </table>
   </br>
	<input type="submit" />
</form>
<?php
// echo "</div>";
//HTML::Br(20);
ob_end_flush();
?>










