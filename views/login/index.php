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
$sError="Se Connecter";
echo $sError;
}
?>
</div>
<div class="item itemcl">
<form action="login/run" method="post">		
	<input  class="login" type="text" name="login" />
	<input  class="login" type="password" name="password" />
	
	<input id="button" type="submit" />
	</form>
</div>
<div class="item itemcr"><?php HTML::Image(URL."public/images/Login.jpg", $width = 400, $height = 440, $border = -1, $title = -1, $map = -1, $name = -1, $class = 'image',$id='image');?></div>
<div class="item itemcfa">*</div>
<div class="item itemcfb">*</div>
<div class="item itemcfc">*</div>
<div class="item itemsb"><?php HTML::Image(URL."public/images/".logo, $width = 400, $height = 440, $border = -1, $title = -1, $map = -1, $name = -1, $class = 'image',$id='image');?></div>
<div class="item itemfsb">DSP DJELFA</div>   