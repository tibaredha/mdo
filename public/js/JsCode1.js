
    var serveur0='localhost';
    var serveur1='tibaredha.ddns.net';
    var serveur3 = location.host;
	
	
	
	var canvas  = document.getElementById('myCanvas');
	var context = canvas.getContext('2d');
    var image0 = new Image();
    image0.src = "http://"+serveur3+"/cheval/public/images/ch.jpg"; //adresse de l'image
    image0.onload = function () {context.drawImage(this,0, 0);} 
	
	
	function rec(lastid,url1) {
	var dataURL = canvas.toDataURL();
    $.ajax({
    type: "POST",
    url: "http://"+url1+"/cheval/test.php",
    data: { 
         imgBase64: dataURL,
		 contenu: lastid
       }
    }).done(function(response) {
       console.log('saved: ' + response);
	   
     });	
	window.location = "http://"+serveur3+"/cheval/dashboard/liste/";
	// print("Hello! I am an alert box!!");
	// alert("Hello! I am an alert box!!");	
}

