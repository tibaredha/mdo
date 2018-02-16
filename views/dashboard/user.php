<div class="item itemsml"><?php echo  sujet ;?></div>
<div class="item itemsmr">MDO</div>
<div class="item itemct">Compte</div>

<div class="item itemcl">
 <form method="post" action="<?php echo URL;?>dashboard/userSave/<?php echo Session::get('id');?>">
	<?php
	HTML::WILAYA('wilaya','wilaya',Session::get('wilaya'),HTML::nbrtostring('wil','IDWIL',Session::get('wilaya'),'WILAYAS'));
	HTML::STRUCTURE('structure','structure',Session::get('structure'),HTML::nbrtostring('structure','id',Session::get('structure'),'structure'));
	?>	
	</br>	
	<input type="text" name="login" class="login"  value="<?php echo Session::get('login');?>" />
	<input type="text" name="password" class="login" />
	</br><input id="region" type="submit" />
	</form>   
</div>
<div class="item itemcr"><?php HTML::Image(URL."public/images/compte.jpg", $width = 400, $height = 440, $border = -1, $title = -1, $map = -1, $name = -1, $class = 'image',$id='image');?></div>
<div class="item itemcfa">*</div>
<div class="item itemcfb">*</div>
<div class="item itemcfc">*</div>
<div class="item itemsb"><?php HTML::Image(URL."public/images/".logo, $width = 400, $height = 440, $border = -1, $title = -1, $map = -1, $name = -1, $class = 'image',$id='image');?></div>
<div class="item itemfsb">DSP DJELFA</div>

















