<SCRIPT LANGUAGE="JavaScript">
function PopupImage(img) {
	w=open("",'image','weigth=toolbar=no,scrollbars=no,resizable=yes, width=220, height=268');	
	w.document.write("<HTML><BODY onblur=\"window.close();\"><IMG src='"+img+"'>");
	w.document.write("</BODY></HTML>");
	w.document.close();
}
</script>
<div class="span-5 colborder">
	        <h3>Installation steps</h3>
	        <fieldset id="fieldset0">
        <legend>***</legend>
		<?php
		HTML::Image(URL."public/images/".logo, $width = 400, $height = 440, $border = -1, $title = -1, $map = -1, $name = -1, $class = 'image',$id='image');
		?>
		</fieldset>
			
			
			
			<ol>
	        	<li <?php //if ($step == "introduction")    echo "class='current'"; ?>>Introduction</li>
				<li <?php //if ($step == "eula")            echo "class='current'"; ?>>EULA</li>
				<li <?php //if ($step == "requirements")    echo "class='current'"; ?>>Server requirements</li>
				<li <?php //if ($step == "filePermissions") echo "class='current'"; ?>>File permissions</li>
				<li <?php //if ($step == "database")        echo "class='current'"; ?>>Database connection</li>
				<li <?php //if ($step == "importSQL")       echo "class='current'"; ?>>Import SQL</li>
				<li <?php //if ($step == "done")            echo "class='current'"; ?>><b>Done</b></li>
	        </ol>
		</div>
<?php 
//View::photosurl(1100,100,URL.'public/images/photos/LOGOAO.GIF');
//ob_start();
?>
<h3>Done</h3>
<p>Installation finished! <b>Please delete the "setup" folder now.</b></p>
<hr>
<p>Now you can login with the default administrator account and password</p>

<ul>
	<li>Username: <b>admin</b></li>
	<li>Password: <b>admin</b></li>
</ul>

<p>Please change the default password after you logged in!</p>
<?php 


//echo $furtherInstructions; ?>

<?php
// view::sautligne(6);
//ob_end_flush();
?>
</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>