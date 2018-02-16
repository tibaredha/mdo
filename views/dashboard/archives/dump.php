<h1>Dump  Sauvegarde data base :   </h1><hr><br/>
     <fieldset id="fieldset0">
    <legend>***</legend>
	<?php
	HTML::Image(URL."public/images/".logo, $width = 400, $height = 440, $border = -1, $title = -1, $map = -1, $name = -1, $class = 'image',$id='image');
	?>
	</fieldset>
	<?php 
	
	
	HTML::dump_MySQL($_SERVER['SERVER_NAME'],"root","","deces",2);
	HTML::Br(36);
	?>	    
	
		
	
	
	
	 
	 