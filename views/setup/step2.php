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
<li>EULA</li>
<li><b>Server requirements</b></li>
<li>File permissions</li>
<li>Database connection</li>
<li>Import SQL</li>
<li>Done</li>
</ol>
</div>
<h3>Server requirements</h3>

<?php
$goToNextStep = true;
if (!$goToNextStep) { 
?>
	<div class="error">Contact your webserver support (hosting service) to get the necessary PHP settings fixed and refresh this site!</div>
<?php } 

$currentPhpVersion = phpversion();
$phpVersionOk = version_compare($currentPhpVersion, 5) >= 0;
$loadedExtensions = get_loaded_extensions();
foreach ($loadedExtensions as $key => $ext) $loadedExtensions[$key] = strtolower($ext); 
$showExtensions = array();
$extensions = array("mysql", "pcre","zip");

foreach ($extensions as $ext)
	{
		$isLoaded = in_array($ext, $loadedExtensions);
		$showExtensions[$ext] =  $isLoaded;
		if (!$isLoaded) $goToNextStep = false;
	}

?>

<h4>PHP Version</h4>

<table  width='60%' >
	<thead>
		<tr>
			<th align="left"   >Name</th>
			<th>Version</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody>
		<tr bgcolor="white">
			<td align="left"></td>
			<td></td>
			<td></td>
		</tr>
		<tr bgcolor="white">
			<td align="left" >You have</td>
			<td align="center"   ><?php echo $currentPhpVersion; ?></td>
			<td align="center"  >
			<?php 
			if ($phpVersionOk) { 
			?> 
			<img src="<?php echo URL;?>public/images/accept.png"> OK 
			<?php 
			} else { 
			?> 
			<img src="<?php echo URL;?>public/images/cancel.png"> Below requirement! Please update your PHP installation.
			<?php
			}
			?>
			</td>
		</tr>
	</tbody>
</table>
<hr>

<h4>PHP Extensions</h4>

<table width='60%'  >
	<thead>
		<tr>
			<th align="left"  >Name</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($showExtensions as $extension => $status): ?>
		<tr bgcolor="white">
			<td align="left" ><?php echo $extension; ?></td>
			<td align="center"  ><?php if ($status) { ?> <img src="<?php echo URL;?>public/images/accept.png"> OK <?php } else { ?> <img src="<?php echo URL;?>public/images/cancel.png"> Not installed!<?php } ?> </td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<hr>
<?php if ($goToNextStep) { ?>
	<form action="<?php echo URL;?>setup/step3" method="post">
		
		<input type="hidden" name="nextStep" value="filePermissions">
		
	
	<button id="Cleara" onclick="javascript:window.location.reload();return false;">Clear Area</button>
<input  id="Clearb" type="submit" />
<button id="Clearl" onclick="javascript:list('<?php echo $_SERVER['SERVER_NAME'];?>');return false;">Lister</button>
<?php 
echo '</form>';
?>
</br></br></br></br></br></br></br></br></br></br></br>
<hr>
	
	
	
	
	</form>
<?php } else { ?>
	<form action="<?php echo URL;?>setup/step2" method="post">
		
		<input type="hidden" name="nextStep" value="requirements">
		
<button id="Cleara" onclick="javascript:window.location.reload();return false;">Clear Area</button>
<input  id="Clearb" type="submit" />
<button id="Clearl" onclick="javascript:list('<?php echo $_SERVER['SERVER_NAME'];?>');return false;">Lister</button>
<?php 
echo '</form>';
?>
</br></br></br></br></br></br></br></br></br></br></br>
<hr>
<?php } ?>

