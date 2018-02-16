<!doctype html>
<?php
 ob_start();
// $page->javascript("jquery.min.js");
// $page->javascript("JsCode.js");
// $page->javascript("js.js");
// $page->
// $page->
// $page->
// $page->Div($id = "Canvas1");
HTML::HtmlStart();
// HTML::Head($title = 'tibaredha', $typeScript = -1, $script = -1);

echo "<meta http-equiv=\"pragma\" content=\"no-cache\" />";

echo "<title>";
if (isset ($this->title)){echo $this->title; }else {echo "maladies a declaration obligatoire" ;}
echo "</title>";
HTML::Meta($author = "tibaredha", $keywords = array("tibaredha,amranemimi"), $descr = "gestion", $lang = -1, $robots = -1, $reply = -1, $httpequiv = array());
echo "<meta http-equiv=\"Content-Type\" content=\"text/html;charset=utf8\" />";
echo"<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";

HTML::Icon(URL."public/images/".logo);
HTML::Style(URL."public/css/cssgrid.css?t=".time());
HTML::Style(URL."public/css/default.css?t=".time());
HTML::Style(URL."public/css/mdo.css?t=".time());
HTML::Style(URL."public/css/jquery-ui.css?t=".time());
HTML::javascript(URL."public/js/jquery.min.js?t=".time());
HTML::javascript(URL."public/js/jquery-ui.min.js?t=".time());
HTML::javascript(URL."public/js/custom.js?t=".time());
HTML::javascript(URL."public/js/JsCode.js?t=".time());
HTML::javascript(URL."public/js/jquery.maskedinput.js?t=".time());
if (isset($this->js)) 
	{
		foreach ($this->js as $js)
		{
			echo '<script type="text/javascript" src="'.URL.'views/'.$js.'"></script>';
		}
	}
	
	
?>	
<SCRIPT LANGUAGE="JavaScript"> 
<!-- Disable 
// function disableselect(e){ 
// return false 
// } 

// function reEnable(){ 
// return true 
// } 

//if IE4+ 
// document.onselectstart=new Function ("return false") 
// document.oncontextmenu=new Function ("return false") 
//if NS6 
// if (window.sidebar){ 
// document.onmousedown=disableselect 
// document.onclick=reEnable 
// } 
//--> 
</script> 	
<?php	
HTML::HeadEnd();
HTML::Body($action = 'onload="InitThis();"');
Session::init();
header("Cache-Control:no-cache");
?>
<div class="container">
	<!---->
	<div class="item itemhl"></div><!---->  
	<div class="item itemhc">Ministere de la sante de la population et de la reforme hospitaliere Version : <?php echo version; ?> </div>
	<div class="item itemhr"></div><!----> 
	<div class="item itemml">
		<?php 
		if (Session::get('loggedIn') == false):
			// echo '<a href="'.URL.'index">index</a>';echo '&nbsp;';
			// echo '<a href="'.URL.'help">help</a>';echo '&nbsp;';
			// echo '<a href="'.URL.'setup">Installation</a>';echo '&nbsp;';
		endif;
		if (Session::get('loggedIn') == true):
			echo '<a href="'.URL.'dashboard">Accueil</a>';echo '&nbsp;';
			if (Session::get('login') == 'admin'  or Session::get('login') == 'tibaredha'):
				echo '<a href="'.URL.'dashboard/Evaluation/0">Evaluation</a>';echo '&nbsp;';
				// echo '<a href="'.URL.'dashboard/SIGA">Sig</a>';echo '&nbsp;';
				// echo '<a href="'.URL.'dashboard/dump/">Dump</a>';echo '&nbsp;';
				// echo '<a href="'.URL.'dashboard/XLS/">Xls</a>';echo '&nbsp;';
				// echo '<a href="'.URL.'dashboard/CIM/">CIM</a>';echo '&nbsp;';
				echo '<a href="'.URL.'dashboard/Update">Update</a>';echo '&nbsp;';
				// echo '<a href="'.URL.'dashboard/Dsp/0">Dsp</a>';echo '&nbsp;';
				// echo '<a href="'.URL.'dashboard/Demographie/">Demographie</a>';echo '&nbsp;';
			endif;
			echo '<a href="'.URL.'dashboard/user/">Compte</a>';echo '&nbsp;';
			echo '<a href="'.URL.'dashboard/logout">Logout</a>';echo '&nbsp;';   
		else:
			echo '<a href="'.URL.'">Accueil</a>';echo '&nbsp;';
			echo '<a href="'.URL.'login">Login</a>';echo '&nbsp;';
			echo '<a href="'.URL.'register">Register</a>';echo '&nbsp;';
		endif;	
	?>
	</div>
	<div class="item itemmr">
	<?php 		
	if (Session::get('loggedIn') == true){
	echo HTML::nbrtostring('structure','id',Session::get('structure'),'structure').' : '.Session::get('login') ;
	}
	else {
	echo "DSP WILAYA DE DJELFA";
	}	
	?>	
	</div> 	