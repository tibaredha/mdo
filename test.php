<?php
	define('UPLOAD_DIR', 'public/images/');
	if( isset($_POST['imgBase64']) && isset($_POST['contenu']) ){
	$img = $_POST['imgBase64'];
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	//$file = UPLOAD_DIR .$_POST['contenu'].uniqid() . '.jpg';
	$file = UPLOAD_DIR .trim($_POST['contenu']). '.jpg';
	$success = file_put_contents($file, $data); 
	}
	//send request to ocr 

	// print $success ? $file : 'Unable to save the file.';
?>