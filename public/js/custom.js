$(function() {    
    $('.delete').click(function(e) {
        var c = confirm(" Vous êtes sure de supprimer l'enregistrement ? \n  Si oui, confirmer la suppression ");
        if (c == false) return false;   
    });   
});

$(document).ready(function() {	
});
//jvs pour chapitre categorie de la cim10
$(document).ready(function()
{
		$(".cim1").change(function()
		{
			var id=$(this).val();
			var dataString = 'id='+ id;

			$.ajax
			({
				type: "POST",                        // Le type de ma requete
				url: "/deces/public/js/AJAXCIM.PHP",                // L'url vers laquelle la requete sera envoyee
				data: dataString,                    // Les donnees que l'on souhaite envoyer au serveur au format varaible ,JSON
				cache: false,
				success: function(html)              // La reponse du serveur est contenu dans data  text xml json JSON (JavaScript Object Notation) 
						{
						$(".cim2").html(html);   // On peut faire ce qu'on veut avec ici
						} 
					
			});

		});
});


//WILAYA STRUCTURE
$(document).ready(function()
{
var val1= ".wilaya";
var val2= ".structure";
var urlajax = "/mdo/public/js/AJAXWS.PHP";
		
		$(val1).change(function()
		{
			var id=$(this).val();
			var dataString = 'id='+ id;
			$.ajax
			({
				type: "POST",                        // Le type de ma requete
				url: urlajax,                        // L'url vers laquelle la requete sera envoyee
				data: dataString,                    // Les donnees que l'on souhaite envoyer au serveur au format varaible ,JSON
				cache: false,
				success: function(html)              // La reponse du serveur est contenu dans data  text xml json JSON (JavaScript Object Notation) 
						{
						$(val2).html(html);          // On peut faire ce qu'on veut avec ici
						} 		
			});

		});
});
//wilaya commune naissance
$(document).ready(function()
{
var val1= ".country";
var val2= ".COMMUNEN";
var urlajax = "/mdo/public/js/AJAXWC.PHP";
		$(val1).change(function()
		{
			var id=$(this).val();
			var dataString = 'id='+ id;
			$.ajax
			({
				type: "POST",                        // Le type de ma requete
				url: urlajax,                        // L'url vers laquelle la requete sera envoyee
				data: dataString,                    // Les donnees que l'on souhaite envoyer au serveur au format varaible ,JSON
				cache: false,
				success: function(html)              // La reponse du serveur est contenu dans data  text xml json JSON (JavaScript Object Notation) 
						{
						$(val2).html(html);          // On peut faire ce qu'on veut avec ici
						} 		
			});

		});
});
//wilaya commune residence
$(document).ready(function()
{
var val1= ".countryr";
var val2= ".COMMUNER";
var urlajax = "/mdo/public/js/AJAXWC.PHP";
		$(val1).change(function()
		{
			var id=$(this).val();
			var dataString = 'id='+ id;

			$.ajax
			({
				type: "POST",
				url: urlajax,
				data: dataString,
				cache: false,
				success: function(html)
						{
						$(val2).html(html);
						} 
			});

		});
});

//wilaya commune deces
$(document).ready(function()
{
var val1= ".countryd";
var val2= ".COMMUNED";
var urlajax = "/mdo/public/js/AJAXWC.PHP";
		$(val1).change(function()
		{
			var id=$(this).val();
			var dataString = 'id='+ id;

			$.ajax
			({
				type: "POST",                        // Le type de ma requete
				url: urlajax,                        // L'url vers laquelle la requete sera envoyee
				data: dataString,                    // Les donnees que l'on souhaite envoyer au serveur au format varaible ,JSON
				cache: false,
				success: function(html)              // La reponse du serveur est contenu dans data  text xml json JSON (JavaScript Object Notation) 
						{
						$(val2).html(html);          // On peut faire ce qu'on veut avec ici
						} 		
			});

		});
});



function list(url) {
	window.location = "http://"+url+"/mdo/dashboard/search/0/10?o=NOM&q=";	
}


// function view(id,url) {
	// window.location = "http://"+url+"/cheval/dashboard/view/"+id;	
// }
// function nve(url) {
	// window.location = "http://"+url+"/cheval/dashboard/";	
// }








$(document).ready(function()
{
		$(".cheval0").change(function()
		{
			var id=$(this).val();
			var dataString = 'id='+ id;

			$.ajax
			({
				type: "POST",                        // Le type de ma requete
				url: "/cheval/public/js/AJAXWR.PHP",     // L'url vers laquelle la requete sera envoyee
				data: dataString,                    // Les donnees que l'on souhaite envoyer au serveur au format varaible ,JSON
				cache: false,
				success: function(html)              // La reponse du serveur est contenu dans data  text xml json JSON (JavaScript Object Notation) 
						{
						$(".cheval1").html(html);   // On peut faire ce qu'on veut avec ici
						} 		
			});

		});
});


$(document).ready(function()
{
		$(".cheval1").change(function()
		{
			var id=$(this).val();
			var dataString = 'id='+ id;

			$.ajax
			({
				type: "POST",                        // Le type de ma requete
				url: "/cheval/public/js/AJAXS.PHP",     // L'url vers laquelle la requete sera envoyee
				data: dataString,                    // Les donnees que l'on souhaite envoyer au serveur au format varaible ,JSON
				cache: false,
				success: function(html)              // La reponse du serveur est contenu dans data  text xml json JSON (JavaScript Object Notation) 
						{
						$(".cheval2").html(html);   // On peut faire ce qu'on veut avec ici
						} 		
			});

		});
});

/*Activates the Tabs*/
function tabSwitch(new_tab, new_content) {    
    document.getElementById('content_1').style.display = 'none';  
    document.getElementById('content_2').style.display = 'none';  
    document.getElementById('content_3').style.display = 'none';  
	document.getElementById('content_4').style.display = 'none';  
	
	/*document.getElementById('content_3').style.display = 'none';*/ 
	document.getElementById(new_content).style.display = 'block';     
    document.getElementById('tab_1').className = '';  
    document.getElementById('tab_2').className = '';  
    document.getElementById('tab_3').className = '';  
	document.getElementById('tab_4').className = '';  
	
	/*document.getElementById('tab_3').className = ''; */        
    document.getElementById(new_tab).className = 'active';        
}
