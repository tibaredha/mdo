<div class="item itemsml"><?php echo  sujet ;?></div>
<div class="item itemsmr">MDO</div>
<div class="item itemct">
<?php
if (isset($_SESSION['errorregister'])) {
$sError = '<span id="error">' . $_SESSION['errorregister'] . '</span></h1><hr><br/>';		
echo $sError;			
}
else
{
$sError="Créer un compte";
echo $sError;
}
?></div>
<div class="item itemcl">
    <form action="register/Registerrun" method="post">	
	<?php  HTML::WILAYA('wilaya','wilaya','17000','wilaya');?>
	<?php  HTML::STRUCTURE('structure','structure','01','structure'); ?>
	</br>
	<input class="login" type="text" name="login" />
	<input class="login" type="password" name="password" />
	</br>
	<input id="button" type="submit" />
	</form>
</div>
<div class="item itemcr"><?php HTML::Image(URL."public/images/register.jpg", $width = 400, $height = 440, $border = -1, $title = -1, $map = -1, $name = -1, $class = 'image',$id='image');?></div>
<div class="item itemcfa">*</div>
<div class="item itemcfb">*</div>
<div class="item itemcfc">*</div>
<div class="item itemsb"><?php HTML::Image(URL."public/images/".logo, $width = 400, $height = 440, $border = -1, $title = -1, $map = -1, $name = -1, $class = 'image',$id='image');?></div>
<div class="item itemfsb">DSP DJELFA</div>   