<h1>Installation steps</h1><hr><br/>
<fieldset id="fieldset0">
        <legend>***</legend>
		<?php
		HTML::Image(URL."public/images/".logo, $width = 400, $height = 440, $border = -1, $title = -1, $map = -1, $name = -1, $class = 'image',$id='image');
		?>
		</fieldset>
<div>  
<ol>
<li>Introduction</li>
<li><b>EULA</b></li>
<li>Server requirements</li>
<li>File permissions</li>
<li>Database connection</li>
<li>Import SQL</li>
<li>Done</li>
</ol>
</div>
<h3>EULA</h3>
<div class="info">You must accept the EULA to continue!</div>
<textarea style="height: 230px; width: 60%;">
<?php
$eula = file_get_contents(URL."views\setup\eula.txt");
echo $eula; 
?>
</textarea>
<hr>
<form action="<?php echo URL;?>setup/step2"  method="post">
<input type="hidden" name="nextStep" value="requirements">
<button id="Cleara" onclick="javascript:window.location.reload();return false;">Clear Area</button>
<input  id="Clearb" type="submit" />
<button id="Clearl" onclick="javascript:list('<?php echo $_SERVER['SERVER_NAME'];?>');return false;">Lister</button>
<?php 
echo '</form>';
?>
</br></br></br></br></br></br></br></br></br>
<hr>
