 <fieldset id="fieldset0">
    <legend>***</legend>
    <?php HTML::Image(URL."public/images/".logo, $width = 400, $height = 440, $border = -1, $title = -1, $map = -1, $name = -1, $class = 'image',$id='image');?>

	</fieldset>
<?php
 
function equincommune($WILAYAR,$COMMUNER) 
{
$db_host="localhost"; 
$db_name="deces"; 
$db_user="root";
$db_pass="";$structure= Session::get("structure");
$cnx = mysql_connect($db_host,$db_user,$db_pass)or die ('I cannot connect to the database because: ' . mysql_error());
$db  = mysql_select_db($db_name,$cnx) ;
mysql_query("SET NAMES 'UTF8' ");// le nom et prenom doit etre lier 
$sql = " select * from deceshosp where WILAYAR=$WILAYAR  and  COMMUNER=$COMMUNER and  STRUCTURED=$structure  ";
$requete = @mysql_query($sql) or die($sql."<br>".mysql_error());
$OP=mysql_num_rows($requete);
mysql_free_result($requete);
return $OP;
}
function color($x) 
{	
	if($x>=0 and $x<=1){$valeur= "#b9b9b9";}
	if($x>1 and $x<=10){$valeur= "#ffa6a9";}
	if($x>10 and $x<=100){$valeur= "#cc6674";}
	if($x>100 and $x<=1000){$valeur= "#992038";}
	if($x>1000 and $x<=1000000){$valeur= "#60000e";}
	return $valeur;
}
$data = array(
"916"  => color(equincommune('17000',916)),//Djelfa
"917"  => color(equincommune('17000',917)),//El Idrissia
"918"  => color(equincommune('17000',918)),//Oum Cheggag
"919"  => color(equincommune('17000',919)),//El Guedid
"920"  => color(equincommune('17000',920)),//Charef
"921"  => color(equincommune('17000',921)),//El Hammam
"922"  => color(equincommune('17000',922)),//Touazi
"923"  => color(equincommune('17000',923)),//Beni Yacoub
"924"  => color(equincommune('17000',924)),//ainoussera
"925"  => color(equincommune('17000',925)),//guernini
"926"  => color(equincommune('17000',926)),//sidilaadjel
"927"  => color(equincommune('17000',927)),//hassifdoul
"928"  => color(equincommune('17000',928)),//elkhamis
"929"  => color(equincommune('17000',929)),//birine
"930"  => color(equincommune('17000',930)),//Dra Souary
"931"  => color(equincommune('17000',931)),//benahar
"932"  => color(equincommune('17000',932)),//hadshari
"933"  => color(equincommune('17000',933)),//bouiratlahdab
"934"  => color(equincommune('17000',934)),//ainfka
"935"  => color(equincommune('17000',935)),//hassi bahbah
"936"  => color(equincommune('17000',936)),//Mouilah
"937"  => color(equincommune('17000',937)),//El Mesrane
"938"  => color(equincommune('17000',938)),//Hassi el Mora
"939"  => color(equincommune('17000',939)),//zaafrane
"940"  => color(equincommune('17000',940)),//hassi el euche
"941"  => color(equincommune('17000',941)),//ain maabed
"942"  => color(equincommune('17000',942)),//darchioukh
"943"  => color(equincommune('17000',943)),//Guendouza
"944"  => color(equincommune('17000',944)),//El Oguila
"945"  => color(equincommune('17000',945)),//El Merdja
"946"  => color(equincommune('17000',946)),//mliliha
"947"  => color(equincommune('17000',947)),//sidibayzid
"948"  => color(equincommune('17000',948)),//Messad
"949"  => color(equincommune('17000',949)),//Abdelmadjid
"950"  => color(equincommune('17000',950)),//Haniet Ouled Salem
"951"  => color(equincommune('17000',951)),//Guettara
"952"  => color(equincommune('17000',952)),//Deldoul
"953"  => color(equincommune('17000',953)),//Sed Rahal
"954"  => color(equincommune('17000',954)),//Selmana
"955"  => color(equincommune('17000',955)),//El Gahra
"956"  => color(equincommune('17000',956)),//Oum Laadham
"957"  => color(equincommune('17000',957)),//Mouadjebar
"958"  => color(equincommune('17000',958)),//Ain el Ibel
"959"  => color(equincommune('17000',959)),//Amera
"960"  => color(equincommune('17000',960)),//N'thila
"961"  => color(equincommune('17000',961)),//Oued Seddeur
"962"  => color(equincommune('17000',962)),//Zaccar
"963"  => color(equincommune('17000',963)),//Douis
"964"  => color(equincommune('17000',964)),//Ain Chouhada
"965"  => color(equincommune('17000',965)),//Tadmit
"966"  => color(equincommune('17000',966)),//El Hiouhi
"967"  => color(equincommune('17000',967)),//Faidh el Botma
"968"  => color(equincommune('17000',968)) //Amourah
);

?>
<div style=" position:absolute;left:4px;top:128px;">
<?xml version='1.0' encoding='utf-8'?> 
<svg xmlns:cc="http://web.resource.org/cc/"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
     xmlns:svg="http://www.w3.org/2000/svg"
     xmlns="http://www.w3.org/2000/svg"	 
	 width="600" height="700"
	 y="100"
     onload="init(evt)"> 
 
<metadata> 
  <rdf:rdf> 
   <dc:format> 
    image/svg+xml
   </dc:format> 
   <dc:title> 
    Blank map
   </dc:title> 
   <dc:creator> 
    Peter Collingridge
   </dc:creator> 
  </rdf:rdf> 
</metadata> 
<style type="text/css"> 
    .commune
	{
	fill: black;
	font-family: "Times New Roman", Times, serif;
	font-size: 10px;font-weight: bold;
	}
	.land
	{
    	fill: #b9b9b9;
    	stroke: white;
    	stroke-width: 1.5;
    	stroke-miterlimit: 4;
	}
	.colour0 {fill: #b9b9b9;}
	.colour1 {fill: #ffa6a9;}
	.colour2 {fill: #cc6674;}
	.colour3 {fill: #992038;}
	.colour4 {fill: #60000e;}
	.coast
	{
        stroke-width: 0.5;
	}
	.label{
        font-size: 14px;
	}
	path:hover
	{
    	opacity: 0.5;
	}
</style> 
<script type="text/ecmascript"> 
<![CDATA[

	function init(evt) {
	    if ( window.svgDocument == null ) {
	      svgDocument = evt.target.ownerDocument;
	    }
   		
  	}
  
	function displayName(name) {
    	svgDocument.getElementById('country_name').firstChild.data = name;
	}
	function notify(){
	location.href="http://localhost/cheval/dashboard/SIG"
	}
  
]]>
</script>
<!---->
<rect 
x="8" xy="20"
y="5"  ry="20"
width="500" 
height="600" 
fill = "<?php echo "#FBEFEF";?>"
/>

<text class="label" id="country_name1" x="320" y="30">Repartition Des deces</text>  	  
<text class="label" id="country_name1" x="325" y="47">Commune de djelfa </text>  	  
<path  
   d="M 120  
       74 L 149  
       74 L 158  
       48 L 167  
       74 L 196  
       74 L 172.5  
       90 L 181.5  
       116 L 158  
       100 L 134.5  
       116 L 143.5  
       90  
       Z"  
    fill="#FFD300"  
    fill-opacity="0.4"  
    stroke="#FFD300"  
    stroke-width="3.0"  
>  
</path> 
<path 
fill = "<?php echo $data['927'];?>" stroke="black" onmouseover="displayName('Hassi-fedoul')" onclick="notify()"	
d="M11,64 48,60 50,80 68,78 69,91 59,106 44,102 11,76 11,64"/><text class="commune" x="25" y="75">HF</text>  
<path 
fill = "<?php echo $data['926']; ?>" stroke="black" onmouseover="displayName('Sidi-laadjel')"  	
d="M68,78 69,91 59,106 70,120 89,103 101,81 87,70 68,78"/>  	
<path 
fill = "<?php echo $data['928'];?>" stroke="black" onmouseover="displayName('Elkhamis')"  	
d="M101,81 89,103 97,110 98,119 111,123 122,111 133,93 138,90 139,82 126,82 123,86 119,85 119,80 127,76 135,62 130,58 120,70 119,77 114,77 109,82 101,81"/>  
<path 
fill = "<?php echo $data['924']; ?>" stroke="black" onmouseover="displayName('Ain-oussera')" 	
d="M130,58 135,62 127,76 119,80 119,85 123,86 126,82 139,82 138,90 133,93 122,111 154,136 162,127 161,123 164,117 158,116 155,87 160,83 160,78 155,78 150,82 150,11 145,8 143,14 145,22 143,28 147,33 147,44 142,48 137,53 130,58"/>  
<path 
fill = "<?php echo $data['931'];?>" stroke="black" onmouseover="displayName('Benahar')" 
d="M150,11 150,82 155,78 160,78 160,83 155,87 158,116 164,117 161,123 162,127 172,123 179,119 191,105 200,98 194,78 193,64 188,64 173,50 172,38 170,25 165,23 161,6 150,11"/>  
<path 
fill = "<?php echo $data['929'];?>" stroke="black" onmouseover="displayName('Birine')" 
d="M173,50 188,64 193,64 194,78 204,75 224,68 243,53 221,30 220,22 212,22 207,14 205,9 198,14 197,25 191,36 185,36 181,38 173,50"/>          
<path 
fill = "<?php echo $data['934'];?>" stroke="black" onmouseover="displayName('Ain-fka')" 	
d="M243,53 224,68 237,100 248,105 256,118 266,108 263,92 269,89 270,74 243,53"/>     
<path 
fill = "<?php echo $data['932'];?>" stroke="black" onmouseover="displayName('Had-shari')" 	
d="M191,105 198,112 200,133 207,130 216,132 228,132 234,137 254,117 256,118 248,105 237,100 224,68 204,75 194,78 194,78 200,98 191,105"/>    
<path 
fill = "<?php  echo $data['933'];?>" stroke="black"onmouseover="displayName('Bouirat-lahdab')" 	
d="M154,136 154,144 163,145 170,149 177,150 200,133 198,112 191,105 179,119 172,123 162,127 154,136"/>    
<path 
fill = "<?php  echo $data['925'];?>"  stroke="black"onmouseover="displayName('Guernini')" 	
d="M111,123 109,131 113,135 107,136 98,153 108,163 132,155 141,148 154,144 154,136 122,111 111,123"/>    
<path 
fill = "<?php echo $data['935'];?>"  stroke="black"onmouseover="displayName('Hassi-bahbah')" 	
d="M108,163 113,171  124,171 125,180 139,181  152,185  157,195 159,200 176,192 181,188 179,183 185,181 191,177 184,173 187,170  181,163 177,156 177,150 170,149  163,145  154,144 141,148 132,155 108,163"/>    
<path 
fill = "<?php echo $data['939'];?>"  stroke="black" onmouseover="displayName('Zaafrane')" 	
d="M108,163 102,167 89,168 85,172 81,193 114,198 118,196 123,196 127,204 128,215 133,221 138,222 139,232 142,237 141,242 145,245 142,256 155,259 164,249 174,243 173,227 164,223 170,214 159,200 157,195 152,185 139,181 125,180 124,171 113,171 108,163"/>    
<path 
fill = "<?php echo $data['940'];?>"  stroke="black"onmouseover="displayName('Hassi-eleuche')" 	
d="M177,150 177,156 181,163 187,170 184,173 191,177 214,164 222,164 222,150 233,137 228,132 216,132 207,130  200,133  177,150"/>    
<path 
fill = "<?php echo $data['941'];?>"  stroke="black"onmouseover="displayName('Ain-maabed')" 	
d="M217,197 207,194 203,183 197,183 191,177 185,181 179,183 181,188 176,192 159,200 170,214 164,223 173,227 178,224 183,223 189,223 189,217 193,212 201,210 205,208 217,197"/>    
<path 
fill = "<?php echo $data['942'];?>"  stroke="black" onmouseover="displayName('Darchioukhe')" 	
d="M205,208 211,218 218,217 221,211 227,208 237,208 240,201 248,198 254,194 252,186 249,182 253,180 250,165 226,187 226,194 217,197 205,208"/>    
<path 
fill = "<?php echo $data['946'];?>"  stroke="black" onmouseover="displayName('Mliliha')" 	
d="M254,194  248,198 240,201 237,208 227,208 221,211 218,217  227,219 233,219 239,226 240,241 245,243 245,250 249,250 251,246 258,244 272,255 274,250 269,248 268,243 271,240 276,242 279,247 283,250 288,248 306,247 306,243 302,240 301,214 276,212 272,206 265,211 262,204 261,197  254,194"/>    
<path 
fill = "<?php echo $data['947'];?>"  stroke="black"onmouseover="displayName('Sidi-bayzid')" 	
d="M233,137 222,150 222,164 214,164 191,177 197,183 203,183 207,194 217,197 226,194 226,187 250,165 255,154 248,159  233,137"/>    


<path 
fill = "<?php echo $data['916'];?>"  stroke="black" onmouseover="displayName('Djelfa')" 	
d="M173,227 174,243 177,248 184,251 185,256 188,260 194,258  201,263 214,255 212,240 217,230 215,220 218,217 211,218 205,208 201,210 193,212 189,217 189,223 183,223 178,224 173,227"/> 
<path 
fill = "<?php echo $data['919'];?>"  stroke="black" onmouseover="displayName('Elgadid')" 	
d="M81,193 74,209 62,211 65,227 67,278 77,275 78,265 96,261 96,253 113,239 118,228  128,215 127,204 123,196 118,196 114,198 81,193"/>
<path 
fill = "<?php echo $data['917'];?>"  stroke="black" onmouseover="displayName('Elidrissia')" 	
d="M67,278 72,289 77,305 88,304 92,300 110,289 100,285 100,280 106,277 107,273 101,273 95,269 96,261 78,265 77,275 67,278 "/>
<path 
fill = "<?php echo $data['964'];?>"  stroke="black" onmouseover="displayName('Ain-chouhada')" 	
d="M77,305  85,320  91,325  93,333 100,334 102,339 107,343  111,343 108,332  105,329  105,318 106,307  101,306 95,304 92,300 88,304 77,305"/>    
<path 
fill = "<?php echo $data['963'];?>"  stroke="black" onmouseover="displayName('Douis')" 	
d="M111,343 118,344 126,338 134,339 132,332 143,315 137,311 133,313 131,310 127,311 127,303 132,299 129,297 128,288 123,288 115,285 110,289 92,300 95,304 101,306 106,307 105,318 105,329 108,332 111,343"/>    
<path 
fill = "<?php echo $data['965'];?>"  stroke="black" onmouseover="displayName('Taadmit')" 	
d="M143,315 151,310 157,314 161,319 170,316 172,324 177,329 176,344 186,368 197,360 199,352 196,352 193,354 191,352 187,350 186,353 183,348 182,333 183,327 187,322 177,307 177,302 181,299 181,293 177,289 176,281 180,280 180,265 180,257 175,254 170,260 162,261 157,264 152,269 145,277 138,282 133,279 129,280 128,283 128,288 129,297 132,299 127,303 127,311 131,310 133,313 137,311 143,315"/>                                                                                                                                    
<path 
fill = "<?php echo $data['920'];?>"  stroke="black" onmouseover="displayName('Charef')" 	
d="M110,289  115,285 115,279 121,272 137,263 142,256 145,245 141,242 142,237 139,232 138,222 133,221 128,215 118,228 113,239 96,253 96,261 95,269 101,273 107,273 106,277 100,280 100,285 110,289"/>
<path 
fill = "<?php echo $data['923'];?>"  stroke="black" onmouseover="displayName('Ben-yaagoube')" 	
d="M115,285 123,288 128,288 128,283 129,280 133,279 138,282 145,277 152,269 157,264 155,259 142,256 137,263 121,272 115,279 115,285 "/>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
<path 
fill = "<?php echo $data['967'];?>"  stroke="black" onmouseover="displayName('Faid-elbotma')" 	
d="M306,247 288,248 283,250 279,247 276,242 271,240 268,243 269,248 274,250 272,255 258,244 251,246 249,250 245,250 242,252 252,272 266,289 277,299 277,303 284,306 298,295 301,291 310,288 317,280 303,262 306,247"/>    
<path 
fill = "<?php echo $data['957'];?>"  stroke="black" onmouseover="displayName('Moudjbara')" 	
d="M218,217 215,220  217,230 212,240 214,255 222,248 233,257 232,271 231,279  231,308 229,322 237,322 240,320 247,325  252,313 256,308 262,302 266,289 252,272 242,252 245,250 245,243 240,241 239,226 233,219 227,219  218,217"/>     
<path 
fill = "<?php echo $data['948'];?>"  stroke="black" onmouseover="displayName('Masaad')" 	
d="M247,325 251,333 252,342 249,346 246,349 242,352  240,346 234,340 230,334 229,322 237,322 240,320 247,325"/>       
<path 
fill = "<?php echo $data['962'];?>"  stroke="black" onmouseover="displayName('Zecar')" 	
d="M214,255 212,259 211,273 201,277 200,282 206,283 209,291 207,296 210,302 215,293 222,281 227,268 231,279 232,271 233,257 222,248  214,255"/>    
<path 
fill = "<?php echo $data['958'];?>"  stroke="black" onmouseover="displayName('Ain-elbel')" 	
d="M155,259 157,264 162,261 170,260 175,254 180,257 180,265 180,280 176,281 177,289 181,293 181,299 177,302 177,307 187,322 194,314 203,309  210,302  207,296 209,291 206,283 200,282 201,277 211,273 212,259  214,255 201,263 194,258 188,260 185,256 184,251 177,248 174,243  164,249 155,259"/>                                                                                                                                                                                                                                                                                                                                           
<path 
fill = "<?php echo $data['953'];?>"  stroke="black" onmouseover="displayName('Sed-rahal')" 	
d="M186,368 192,393 197,397 197,403 213,404 228,412 241,419 254,432 267,446 275,461 290,465 301,446 260,411 237,399 223,381 216,380 211,372 207,370 203,372 197,372 186,368"/>                                                                                                                                                                                                                                                                                                                                        
<path 
fill = "<?php echo $data['952'];?>"  stroke="black" onmouseover="displayName('Deldoul')" 	
d="M301,446 314,429 264,395 262,389 250,380 242,352 240,346 234,340 230,334  229,322 231,308 231,279 227,268 222,281 215,293 210,302 203,309 194,314 187,322 183,327 182,333 183,348 186,353 187,350 191,352 193,354 196,352 199,352 197,360 186,368 197,372 203,372 207,370 211,372 216,380 223,381 237,399 260,411   301,446"/>    
<path 
fill = "<?php echo $data['954'];?>"  stroke="black" onmouseover="displayName('Salmana')" 	
d="M314,429 327,411 302,371 312,360 308,358 307,352 303,344 303,338 293,328 292,320 284,306 277,303 277,299 266,289 262,302 256,308 252,313 247,325 251,333 252,342 249,346 246,349 242,352 250,380 262,389 264,395 314,429"/>    
<path 
fill = "<?php echo $data['968'];?>"  stroke="black" onmouseover="displayName('Amoura')" 	
d="M367,342 364,338 359,338 358,335 349,338 348,332 343,329 348,323 342,322 342,317  337,317 340,312 331,308 329,302 324,302 316,298 317,280 310,288 301,291 298,295 284,306 292,320 293,328 303,338 303,344 307,352 308,358 312,360 302,371 367,342"/>                                                                                                                                                                
<path 
fill = "<?php echo $data['951'];?>"  stroke="black" onmouseover="displayName('Guetara')" 	
d="M290,465 311,474 328,481 345,492 373,520 380,535 389,544 392,555 400,567 485,590 473,522 443,525 422,510  381,472 360,480 325,430 337,427 327,411 314,429 301,446 290,465"/>                                                                                                                                                                                                           
<path 
fill = "<?php echo $data['956'];?>"  stroke="black" onmouseover="displayName('Oumlaadam')" 	
d="M473,522 473,498 489,463 486,449 493,442 473,434 462,434 458,424 443,425 439,418 435,420 432,416 419,416 416,414 411,405 407,402 398,398 384,395 378,389 364,384 356,378 356,374 369,373 379,360 388,360 386,353 372,354 366,349 367,342 302,371 327,411 337,427 325,430 360,480 381,472 422,510 443,525 473,522"/>  
<text class="label" id="country_name" x="300" y="90">Wilaya De Djelfa</text>                                                                                                                                                   
<text class="label" id="country_name" x="10" y="440">Wilaya De Djelfa</text>                                                                                                                                                                                                   
<g id="key" class="label">
<rect x="10" y="450" width="20" height="20" class="key colour0" /><text x="35" y="465">0</text>
<rect x="10" y="475" width="20" height="20" class="key colour1" /><text x="35" y="490">1 to 10</text>
<rect x="10" y="500" width="20" height="20" class="key colour2" /><text x="35" y="515">11 to 100</text>
<rect x="10" y="525" width="20" height="20" class="key colour3" /><text x="35" y="540">101 to 1000</text>
<rect x="10" y="550" width="20" height="20" class="key colour4" /><text x="35" y="565">1001+</text>
</g>
<rect
x="270" y="55"
width="110" 
height="10">
    <animate attributeName="width" attributeType="XML"
    fill="freeze"
    from="0" to="200"
    begin="0s" dur="2s"/>
</rect>
<!--  -->
</svg>
</div>
</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>
</br>
</br>
</br>
</br>
</br></br></br>




