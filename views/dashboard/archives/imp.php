<h1><a title="Releve des causes de deces"  target="_blank"  href="<?php echo URL; ?>tcpdf/docpdf/dz/decesfrx.pdf">Relevé des causes de décés</a></h1><hr><br/>

<?php 
html::munu('cheval'); 
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
<form method="post" action="<?php echo URL.'pdf/impdeces.php' ;?>">
	<label>Ordre</label><?php   HTML::combov1('ordre',array("Nom"=>"NOM","Prenom"=>"PRENOM","Date Deces"=>"DINS"));    ?><br />
	<label>Ascdesc</label><?php HTML::combov1('ascdesc',array("croissant"=>"asc","décroissant"=>"desc"));    ?><br />
	<label>MFT</label><?php     HTML::combov1('SEXE',array("Tous Masculin et Feminin"=>"IS NOT NULL","Masculin"=>"='M'","Feminin"=>"='F'"));?><br />
	<label>Structure</label><?php HTML::combov1('STRUCTURED',array("Tous Structure"=>"IS NOT NULL","EPH_DJELFA"=>"='1'","EPH_AIN_OUSSERA"=>"='2'","EPH_HASSI_BAHBAH"=>"='3'","EPH_MESSAAD"=>"='4'","EHS_DJELFA"=>"='5'"));   ?><br />
	<label>Nbrlimit</label><?php HTML::combov1('nbrlimit',array("Limiter A 10"=>"10","Limiter A 20"=>"20","Limiter A 30"=>"30","Limiter A 40"=>"40","Limiter A 50"=>"50","Limiter A 60"=>"60","Limiter A 70"=>"70","Limiter A 80"=>"80","Limiter A 90"=>"90","Limiter A 100"=>"100","Limiter A 1000"=>"1000","Limiter A 10000"=>"10000"));?><br />
	<label>&nbsp;</label><input type="submit" />
</form>
<?php
// echo "</div>";
HTML::Br(12);
ob_end_flush();
?>










