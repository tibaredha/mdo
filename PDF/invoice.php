<?php
require('fpdf.php');
define('EURO', chr(128) );
define('EURO_VAL', 6.55957 );

class PDF_Invoice extends FPDF
{
// variables priv�es
var $colonnes;
var $format;
var $angle=0;
//*********************
//variable venant d'une autre classe qui afiche un tableau sur plusieurs pages 
var $ProcessingTable=false;
var $aCols=array();
var $TableX;
var $HeaderColor;
var $RowColors;
var $ColorIndex;
function Code39($xpos, $ypos, $code, $baseline=0.5, $height=5){

	$wide = $baseline;
	$narrow = $baseline / 3 ; 
	$gap = $narrow;

	$barChar['0'] = 'nnnwwnwnn';
	$barChar['1'] = 'wnnwnnnnw';
	$barChar['2'] = 'nnwwnnnnw';
	$barChar['3'] = 'wnwwnnnnn';
	$barChar['4'] = 'nnnwwnnnw';
	$barChar['5'] = 'wnnwwnnnn';
	$barChar['6'] = 'nnwwwnnnn';
	$barChar['7'] = 'nnnwnnwnw';
	$barChar['8'] = 'wnnwnnwnn';
	$barChar['9'] = 'nnwwnnwnn';
	$barChar['A'] = 'wnnnnwnnw';
	$barChar['B'] = 'nnwnnwnnw';
	$barChar['C'] = 'wnwnnwnnn';
	$barChar['D'] = 'nnnnwwnnw';
	$barChar['E'] = 'wnnnwwnnn';
	$barChar['F'] = 'nnwnwwnnn';
	$barChar['G'] = 'nnnnnwwnw';
	$barChar['H'] = 'wnnnnwwnn';
	$barChar['I'] = 'nnwnnwwnn';
	$barChar['J'] = 'nnnnwwwnn';
	$barChar['K'] = 'wnnnnnnww';
	$barChar['L'] = 'nnwnnnnww';
	$barChar['M'] = 'wnwnnnnwn';
	$barChar['N'] = 'nnnnwnnww';
	$barChar['O'] = 'wnnnwnnwn'; 
	$barChar['P'] = 'nnwnwnnwn';
	$barChar['Q'] = 'nnnnnnwww';
	$barChar['R'] = 'wnnnnnwwn';
	$barChar['S'] = 'nnwnnnwwn';
	$barChar['T'] = 'nnnnwnwwn';
	$barChar['U'] = 'wwnnnnnnw';
	$barChar['V'] = 'nwwnnnnnw';
	$barChar['W'] = 'wwwnnnnnn';
	$barChar['X'] = 'nwnnwnnnw';
	$barChar['Y'] = 'wwnnwnnnn';
	$barChar['Z'] = 'nwwnwnnnn';
	$barChar['-'] = 'nwnnnnwnw';
	$barChar['.'] = 'wwnnnnwnn';
	$barChar[' '] = 'nwwnnnwnn';
	$barChar['*'] = 'nwnnwnwnn';
	$barChar['$'] = 'nwnwnwnnn';
	$barChar['/'] = 'nwnwnnnwn';
	$barChar['+'] = 'nwnnnwnwn';
	$barChar['%'] = 'nnnwnwnwn';

	$this->SetFont('Arial','',10);
	$this->Text($xpos, $ypos + $height + 4, $code);
	$this->SetFillColor(0);

	$code = '*'.strtoupper($code).'*';
	for($i=0; $i<strlen($code); $i++){
		$char = $code[$i];
		if(!isset($barChar[$char])){
			$this->Error('Invalid character in barcode: '.$char);
		}
		$seq = $barChar[$char];
		for($bar=0; $bar<9; $bar++){
			if($seq[$bar] == 'n'){
				$lineWidth = $narrow;
			}else{
				$lineWidth = $wide;
			}
			if($bar % 2 == 0){
				$this->Rect($xpos, $ypos, $lineWidth, $height, 'F');
			}
			$xpos += $lineWidth;
		}
		$xpos += $gap;
	}
}




// fonctions priv�es
function RoundedRect($x, $y, $w, $h, $r, $style = '')
{
	$k = $this->k;
	$hp = $this->h;
	if($style=='F')
		$op='f';
	elseif($style=='FD' || $style=='DF')
		$op='B';
	else
		$op='S';
	$MyArc = 4/3 * (sqrt(2) - 1);
	$this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
	$xc = $x+$w-$r ;
	$yc = $y+$r;
	$this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));

	$this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
	$xc = $x+$w-$r ;
	$yc = $y+$h-$r;
	$this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
	$this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
	$xc = $x+$r ;
	$yc = $y+$h-$r;
	$this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
	$this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
	$xc = $x+$r ;
	$yc = $y+$r;
	$this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
	$this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
	$this->_out($op);
}

function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
{
	$h = $this->h;
	$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
						$x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
}
function Rotate($angle, $x=-1, $y=-1)
{
	if($x==-1)
		$x=$this->x;
	if($y==-1)
		$y=$this->y;
	if($this->angle!=0)
		$this->_out('Q');
	$this->angle=$angle;
	if($angle!=0)
	{
		$angle*=M_PI/180;
		$c=cos($angle);
		$s=sin($angle);
		$cx=$x*$this->k;
		$cy=($this->h-$y)*$this->k;
		$this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
	}
}
function _endpage()
{
	if($this->angle!=0)
	{
		$this->angle=0;
		$this->_out('Q');
	}
	parent::_endpage();
}

// fonctions publiques
function sizeOfText( $texte, $largeur )
{
	$index    = 0;
	$nb_lines = 0;
	$loop     = TRUE;
	while ( $loop )
	{
		$pos = strpos($texte, "\n");
		if (!$pos)
		{
			$loop  = FALSE;
			$ligne = $texte;
		}
		else
		{
			$ligne  = substr( $texte, $index, $pos);
			$texte = substr( $texte, $pos+1 );
		}
		$length = floor( $this->GetStringWidth( $ligne ) );
		$res = 1 + floor( $length / $largeur) ;
		$nb_lines += $res;
	}
	return $nb_lines;
}

// Cette fonction affiche en haut, a gauche,
// le nom de la societe dans la police Arial-12-Bold
// les coordonnees de la societe dans la police Arial-10
function addSociete( $nom, $adresse )
{
	$x1 = 10;
	$y1 = 8;
	//Positionnement en bas
	$this->SetXY( $x1, $y1 );
	$this->SetFont('Arial','B',12);
	$length = $this->GetStringWidth( $nom );
	$this->Cell( $length, 2, $nom);
	$this->SetXY( $x1, $y1 + 4 );
	$this->SetFont('Arial','',10);
	$length = $this->GetStringWidth( $adresse );
	//Coordonn�es de la soci�t�
	$lignes = $this->sizeOfText( $adresse, $length) ;
	$this->MultiCell($length, 4, $adresse);
}

// Affiche en haut, a droite le libelle
// (FACTURE, DEVIS, Bon de commande, etc...)
// et son numero
// La taille de la fonte est auto-adaptee au cadre
function fact_dev( $libelle, $num )
{
    $r1  = $this->w - 80;
    $r2  = $r1 + 68;
    $y1  = 6;
    $y2  = $y1 + 2;
    $mid = ($r1 + $r2 ) / 2;
    
    $texte  = $libelle . " EN " . EURO . " N� : " . $num;    
    $szfont = 12;
    $loop   = 0;
    
    while ( $loop == 0 )
    {
       $this->SetFont( "Arial", "B", $szfont );
       $sz = $this->GetStringWidth( $texte );
       if ( ($r1+$sz) > $r2 )
          $szfont --;
       else
          $loop ++;
    }

    $this->SetLineWidth(0.1);
    $this->SetFillColor(192);
    $this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 2.5, 'DF');
    $this->SetXY( $r1+1, $y1+2);
    $this->Cell($r2-$r1 -1,5, $texte, 0, 0, "C" );
}

// Genere automatiquement un numero de devis
function addDevis( $numdev )
{
	$string = sprintf("DEV%04d",$numdev);
	$this->fact_dev( "Devis", $string );
}

// Genere automatiquement un numero de facture
function addFacture( $numfact )
{
	$string = sprintf("FA%04d",$numfact);
	$this->fact_dev( "Facture", $string );
}

// Affiche un cadre avec la date de la facture / devis
// (en haut, a droite)
function addDate( $date )
{
	$r1  = $this->w - 61;
	$r2  = $r1 + 30;
	$y1  = 17;
	$y2  = $y1 ;
	$mid = $y1 + ($y2 / 2);
	$this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 3.5, 'D');
	$this->Line( $r1, $mid, $r2, $mid);
	$this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1+3 );
	$this->SetFont( "Arial", "B", 10);
	$this->Cell(10,5, "DATE", 0, 0, "C");
	$this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1+9 );
	$this->SetFont( "Arial", "", 10);
	$this->Cell(10,5,$date, 0,0, "C");
}

// Affiche un cadre avec les references du client
// (en haut, a droite)
function addClient( $ref )
{
	$r1  = $this->w - 31;
	$r2  = $r1 + 19;
	$y1  = 17;
	$y2  = $y1;
	$mid = $y1 + ($y2 / 2);
	$this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 3.5, 'D');
	$this->Line( $r1, $mid, $r2, $mid);
	$this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1+3 );
	$this->SetFont( "Arial", "B", 10);
	$this->Cell(10,5, "CLIENT", 0, 0, "C");
	$this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1 + 9 );
	$this->SetFont( "Arial", "", 10);
	$this->Cell(10,5,$ref, 0,0, "C");
}

// Affiche un cadre avec un numero de page
// (en haut, a droite)
function addPageNumber( $page )
{
	$r1  = $this->w - 80;
	$r2  = $r1 + 19;
	$y1  = 17;
	$y2  = $y1;
	$mid = $y1 + ($y2 / 2);
	$this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 3.5, 'D');
	$this->Line( $r1, $mid, $r2, $mid);
	$this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1+3 );
	$this->SetFont( "Arial", "B", 10);
	$this->Cell(10,5, "PAGE", 0, 0, "C");
	$this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1 + 9 );
	$this->SetFont( "Arial", "", 10);
	$this->Cell(10,5,$page, 0,0, "C");
}

// Affiche l'adresse du client
// (en haut, a droite)
function addClientAdresse( $adresse )
{
	$r1     = $this->w - 80;
	$r2     = $r1 + 68;
	$y1     = 40;
	$this->SetXY( $r1, $y1);
	$this->MultiCell( 60, 4, $adresse);
}

// Affiche un cadre avec le r�glement (ch�que, etc...)
// (en haut, a gauche)
function addReglement( $mode )
{
	$r1  = 10;
	$r2  = $r1 + 60;
	$y1  = 80;
	$y2  = $y1+10;
	$mid = $y1 + (($y2-$y1) / 2);
	$this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
	$this->Line( $r1, $mid, $r2, $mid);
	$this->SetXY( $r1 + ($r2-$r1)/2 -5 , $y1+1 );
	$this->SetFont( "Arial", "B", 10);
	$this->Cell(10,4, "MODE DE REGLEMENT", 0, 0, "C");
	$this->SetXY( $r1 + ($r2-$r1)/2 -5 , $y1 + 5 );
	$this->SetFont( "Arial", "", 10);
	$this->Cell(10,5,$mode, 0,0, "C");
}

// Affiche un cadre avec la date d'echeance
// (en haut, au centre)
function addEcheance( $date )
{
	$r1  = 80;
	$r2  = $r1 + 40;
	$y1  = 80;
	$y2  = $y1+10;
	$mid = $y1 + (($y2-$y1) / 2);
	$this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
	$this->Line( $r1, $mid, $r2, $mid);
	$this->SetXY( $r1 + ($r2 - $r1)/2 - 5 , $y1+1 );
	$this->SetFont( "Arial", "B", 10);
	$this->Cell(10,4, "DATE D'ECHEANCE", 0, 0, "C");
	$this->SetXY( $r1 + ($r2-$r1)/2 - 5 , $y1 + 5 );
	$this->SetFont( "Arial", "", 10);
	$this->Cell(10,5,$date, 0,0, "C");
}

// Affiche un cadre avec le numero de la TVA
// (en haut, au droite)
function addNumTVA($tva)
{
	$this->SetFont( "Arial", "B", 10);
	$r1  = $this->w - 80;
	$r2  = $r1 + 70;
	$y1  = 80;
	$y2  = $y1+10;
	$mid = $y1 + (($y2-$y1) / 2);
	$this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
	$this->Line( $r1, $mid, $r2, $mid);
	$this->SetXY( $r1 + 16 , $y1+1 );
	$this->Cell(40, 4, "TVA Intracommunautaire", '', '', "C");
	$this->SetFont( "Arial", "", 10);
	$this->SetXY( $r1 + 16 , $y1+5 );
	$this->Cell(40, 5, $tva, '', '', "C");
}

// Affiche une ligne avec des reference
// (en haut, a gauche)
function addReference($ref)
{
	$this->SetFont( "Arial", "", 10);
	$length = $this->GetStringWidth( "R�f�rences : " . $ref );
	$r1  = 10;
	$r2  = $r1 + $length;
	$y1  = 92;
	$y2  = $y1+5;
	$this->SetXY( $r1 , $y1 );
	$this->Cell($length,4, "R�f�rences : " . $ref);
}

// trace le cadre des colonnes du devis/facture
function addCols( $tab )
{
	global $colonnes;
	
	$r1  = 10;
	$r2  = $this->w - ($r1 * 2) ;
	$y1  = 100;
	$y2  = $this->h - 50 - $y1;
	$this->SetXY( $r1, $y1 );
	$this->Rect( $r1, $y1, $r2, $y2, "D");
	$this->Line( $r1, $y1+6, $r1+$r2, $y1+6);
	$colX = $r1;
	$colonnes = $tab;
	while ( list( $lib, $pos ) = each ($tab) )
	{
		$this->SetXY( $colX, $y1+2 );
		$this->Cell( $pos, 1, $lib, 0, 0, "C");
		$colX += $pos;
		$this->Line( $colX, $y1, $colX, $y1+$y2);
	}
}

// m�morise le format (gauche, centre, droite) d'une colonne
function addLineFormat( $tab )
{
	global $format, $colonnes;
	
	while ( list( $lib, $pos ) = each ($colonnes) )
	{
		if ( isset( $tab["$lib"] ) )
			$format[ $lib ] = $tab["$lib"];
	}
}

function lineVert( $tab )
{
	global $colonnes;

	reset( $colonnes );
	$maxSize=0;
	while ( list( $lib, $pos ) = each ($colonnes) )
	{
		$texte = $tab[ $lib ];
		$longCell  = $pos -2;
		$size = $this->sizeOfText( $texte, $longCell );
		if ($size > $maxSize)
			$maxSize = $size;
	}
	return $maxSize;
}

// Affiche chaque "ligne" d'un devis / facture
/*    $ligne = array( "REFERENCE"    => $prod["ref"],
                      "DESIGNATION"  => $libelle,
                      "QUANTITE"     => sprintf( "%.2F", $prod["qte"]) ,
                      "P.U. HT"      => sprintf( "%.2F", $prod["px_unit"]),
                      "MONTANT H.T." => sprintf ( "%.2F", $prod["qte"] * $prod["px_unit"]) ,
                      "TVA"          => $prod["tva"] );
*/
function addLine( $ligne, $tab )
{
	global $colonnes, $format;

	$ordonnee     = 10;
	$maxSize      = $ligne;

	reset( $colonnes );
	while ( list( $lib, $pos ) = each ($colonnes) )
	{
		$longCell  = $pos -2;
		$texte     = $tab[ $lib ];
		$length    = $this->GetStringWidth( $texte );
		$tailleTexte = $this->sizeOfText( $texte, $length );
		$formText  = $format[ $lib ];
		$this->SetXY( $ordonnee, $ligne-1);
		$this->MultiCell( $longCell, 4 , $texte, 0, $formText);
		if ( $maxSize < ($this->GetY()  ) )
			$maxSize = $this->GetY() ;
		$ordonnee += $pos;
	}
	return ( $maxSize - $ligne );
}

// Ajoute une remarque (en bas, a gauche)
function addRemarque($remarque)
{
	$this->SetFont( "Arial", "", 10);
	$length = $this->GetStringWidth( "Remarque : " . $remarque );
	$r1  = 10;
	$r2  = $r1 + $length;
	$y1  = $this->h - 45.5;
	$y2  = $y1+5;
	$this->SetXY( $r1 , $y1 );
	$this->Cell($length,4, "Remarque : " . $remarque);
}

// trace le cadre des TVA
function addCadreTVAs()
{
	$this->SetFont( "Arial", "B", 8);
	$r1  = 10;
	$r2  = $r1 + 120;
	$y1  = $this->h - 40;
	$y2  = $y1+20;
	$this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
	$this->Line( $r1, $y1+4, $r2, $y1+4);
	$this->Line( $r1+5,  $y1+4, $r1+5, $y2); // avant BASES HT
	$this->Line( $r1+27, $y1, $r1+27, $y2);  // avant REMISE
	$this->Line( $r1+43, $y1, $r1+43, $y2);  // avant MT TVA
	$this->Line( $r1+63, $y1, $r1+63, $y2);  // avant % TVA
	$this->Line( $r1+75, $y1, $r1+75, $y2);  // avant PORT
	$this->Line( $r1+91, $y1, $r1+91, $y2);  // avant TOTAUX
	$this->SetXY( $r1+9, $y1);
	$this->Cell(10,4, "BASES HT");
	$this->SetX( $r1+29 );
	$this->Cell(10,4, "REMISE");
	$this->SetX( $r1+48 );
	$this->Cell(10,4, "MT TVA");
	$this->SetX( $r1+63 );
	$this->Cell(10,4, "% TVA");
	$this->SetX( $r1+78 );
	$this->Cell(10,4, "PORT");
	$this->SetX( $r1+100 );
	$this->Cell(10,4, "TOTAUX");
	$this->SetFont( "Arial", "B", 6);
	$this->SetXY( $r1+93, $y2 - 8 );
	$this->Cell(6,0, "H.T.   :");
	$this->SetXY( $r1+93, $y2 - 3 );
	$this->Cell(6,0, "T.V.A. :");
}

// trace le cadre des totaux
function addCadreEurosFrancs()
{
	$r1  = $this->w - 70;
	$r2  = $r1 + 60;
	$y1  = $this->h - 40;
	$y2  = $y1+20;
	$this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
	$this->Line( $r1+20,  $y1, $r1+20, $y2); // avant EUROS
	$this->Line( $r1+20, $y1+4, $r2, $y1+4); // Sous Euros & Francs
	$this->Line( $r1+38,  $y1, $r1+38, $y2); // Entre Euros & Francs
	$this->SetFont( "Arial", "B", 8);
	$this->SetXY( $r1+22, $y1 );
	$this->Cell(15,4, "EUROS", 0, 0, "C");
	$this->SetFont( "Arial", "", 8);
	$this->SetXY( $r1+42, $y1 );
	$this->Cell(15,4, "FRANCS", 0, 0, "C");
	$this->SetFont( "Arial", "B", 6);
	$this->SetXY( $r1, $y1+5 );
	$this->Cell(20,4, "TOTAL TTC", 0, 0, "C");
	$this->SetXY( $r1, $y1+10 );
	$this->Cell(20,4, "ACOMPTE", 0, 0, "C");
	$this->SetXY( $r1, $y1+15 );
	$this->Cell(20,4, "NET A PAYER", 0, 0, "C");
}

// remplit les cadres TVA / Totaux et la remarque
// params  = array( "RemiseGlobale" => [0|1],
//                      "remise_tva"     => [1|2...],  // {la remise s'applique sur ce code TVA}
//                      "remise"         => value,     // {montant de la remise}
//                      "remise_percent" => percent,   // {pourcentage de remise sur ce montant de TVA}
//                  "FraisPort"     => [0|1],
//                      "portTTC"        => value,     // montant des frais de ports TTC
//                                                     // par defaut la TVA = 19.6 %
//                      "portHT"         => value,     // montant des frais de ports HT
//                      "portTVA"        => tva_value, // valeur de la TVA a appliquer sur le montant HT
//                  "AccompteExige" => [0|1],
//                      "accompte"         => value    // montant de l'acompte (TTC)
//                      "accompte_percent" => percent  // pourcentage d'acompte (TTC)
//                  "Remarque" => "texte"              // texte
// tab_tva = array( "1"       => 19.6,
//                  "2"       => 5.5, ... );
// invoice = array( "px_unit" => value,
//                  "qte"     => qte,
//                  "tva"     => code_tva );
function addTVAs( $params, $tab_tva, $invoice )
{
	$this->SetFont('Arial','',8);
	
	reset ($invoice);
	$px = array();
	while ( list( $k, $prod) = each( $invoice ) )
	{
		$tva = $prod["tva"];
		@ $px[$tva] += $prod["qte"] * $prod["px_unit"];
	}

	$prix     = array();
	$totalHT  = 0;
	$totalTTC = 0;
	$totalTVA = 0;
	$y = 261;
	reset ($px);
	natsort( $px );
	while ( list($code_tva, $articleHT) = each( $px ) )
	{
		$tva = $tab_tva[$code_tva];
		$this->SetXY(17, $y);
		$this->Cell( 19,4, sprintf("%0.2F", $articleHT),'', '','R' );
		if ( $params["RemiseGlobale"]==1 )
		{
			if ( $params["remise_tva"] == $code_tva )
			{
				$this->SetXY( 37.5, $y );
				if ($params["remise"] > 0 )
				{
					if ( is_int( $params["remise"] ) )
						$l_remise = $param["remise"];
					else
						$l_remise = sprintf ("%0.2F", $params["remise"]);
					$this->Cell( 14.5,4, $l_remise, '', '', 'R' );
					$articleHT -= $params["remise"];
				}
				else if ( $params["remise_percent"] > 0 )
				{
					$rp = $params["remise_percent"];
					if ( $rp > 1 )
						$rp /= 100;
					$rabais = $articleHT * $rp;
					$articleHT -= $rabais;
					if ( is_int($rabais) )
						$l_remise = $rabais;
					else
						$l_remise = sprintf ("%0.2F", $rabais);
					$this->Cell( 14.5,4, $l_remise, '', '', 'R' );
				}
				else
					$this->Cell( 14.5,4, "ErrorRem", '', '', 'R' );
			}
		}
		$totalHT += $articleHT;
		$totalTTC += $articleHT * ( 1 + $tva/100 );
		$tmp_tva = $articleHT * $tva/100;
		$a_tva[ $code_tva ] = $tmp_tva;
		$totalTVA += $tmp_tva;
		$this->SetXY(11, $y);
		$this->Cell( 5,4, $code_tva);
		$this->SetXY(53, $y);
		$this->Cell( 19,4, sprintf("%0.2F",$tmp_tva),'', '' ,'R');
		$this->SetXY(74, $y);
		$this->Cell( 10,4, sprintf("%0.2F",$tva) ,'', '', 'R');
		$y+=4;
	}

	if ( $params["FraisPort"] == 1 )
	{
		if ( $params["portTTC"] > 0 )
		{
			$pTTC = sprintf("%0.2F", $params["portTTC"]);
			$pHT  = sprintf("%0.2F", $pTTC / 1.196);
			$pTVA = sprintf("%0.2F", $pHT * 0.196);
			$this->SetFont('Arial','',6);
			$this->SetXY(85, 261 );
			$this->Cell( 6 ,4, "HT : ", '', '', '');
			$this->SetXY(92, 261 );
			$this->Cell( 9 ,4, $pHT, '', '', 'R');
			$this->SetXY(85, 265 );
			$this->Cell( 6 ,4, "TVA : ", '', '', '');
			$this->SetXY(92, 265 );
			$this->Cell( 9 ,4, $pTVA, '', '', 'R');
			$this->SetXY(85, 269 );
			$this->Cell( 6 ,4, "TTC : ", '', '', '');
			$this->SetXY(92, 269 );
			$this->Cell( 9 ,4, $pTTC, '', '', 'R');
			$this->SetFont('Arial','',8);
			$totalHT += $pHT;
			$totalTVA += $pTVA;
			$totalTTC += $pTTC;
		}
		else if ( $params["portHT"] > 0 )
		{
			$pHT  = sprintf("%0.2F", $params["portHT"]);
			$pTVA = sprintf("%0.2F", $params["portTVA"] * $pHT / 100 );
			$pTTC = sprintf("%0.2F", $pHT + $pTVA);
			$this->SetFont('Arial','',6);
			$this->SetXY(85, 261 );
			$this->Cell( 6 ,4, "HT : ", '', '', '');
			$this->SetXY(92, 261 );
			$this->Cell( 9 ,4, $pHT, '', '', 'R');
			$this->SetXY(85, 265 );
			$this->Cell( 6 ,4, "TVA : ", '', '', '');
			$this->SetXY(92, 265 );
			$this->Cell( 9 ,4, $pTVA, '', '', 'R');
			$this->SetXY(85, 269 );
			$this->Cell( 6 ,4, "TTC : ", '', '', '');
			$this->SetXY(92, 269 );
			$this->Cell( 9 ,4, $pTTC, '', '', 'R');
			$this->SetFont('Arial','',8);
			$totalHT += $pHT;
			$totalTVA += $pTVA;
			$totalTTC += $pTTC;
		}
	}

	$this->SetXY(114,266.4);
	$this->Cell(15,4, sprintf("%0.2F", $totalHT), '', '', 'R' );
	$this->SetXY(114,271.4);
	$this->Cell(15,4, sprintf("%0.2F", $totalTVA), '', '', 'R' );

	$params["totalHT"] = $totalHT;
	$params["TVA"] = $totalTVA;
	$accompteTTC=0;
	if ( $params["AccompteExige"] == 1 )
	{
		if ( $params["accompte"] > 0 )
		{
			$accompteTTC=sprintf ("%.2F", $params["accompte"]);
			if ( strlen ($params["Remarque"]) == 0 )
				$this->addRemarque( "Accompte de $accompteTTC Euros exig� � la commande.");
			else
				$this->addRemarque( $params["Remarque"] );
		}
		else if ( $params["accompte_percent"] > 0 )
		{
			$percent = $params["accompte_percent"];
			if ( $percent > 1 )
				$percent /= 100;
			$accompteTTC=sprintf("%.2F", $totalTTC * $percent);
			$percent100 = $percent * 100;
			if ( strlen ($params["Remarque"]) == 0 )
				$this->addRemarque( "Accompte de $percent100 % (soit $accompteTTC Euros) exig� � la commande." );
			else
				$this->addRemarque( $params["Remarque"] );
		}
		else
			$this->addRemarque( "Dr�le d'acompte !!! " . $params["Remarque"]);
	}
	else
	{
		if ( strlen ($params["Remarque"]) > 0 )
			$this->addRemarque( $params["Remarque"] );
	}
	$re  = $this->w - 50;
	$rf  = $this->w - 29;
	$y1  = $this->h - 40;
	$this->SetFont( "Arial", "", 8);
	$this->SetXY( $re, $y1+5 );
	$this->Cell( 17,4, sprintf("%0.2F", $totalTTC), '', '', 'R');
	$this->SetXY( $re, $y1+10 );
	$this->Cell( 17,4, sprintf("%0.2F", $accompteTTC), '', '', 'R');
	$this->SetXY( $re, $y1+14.8 );
	$this->Cell( 17,4, sprintf("%0.2F", $totalTTC - $accompteTTC), '', '', 'R');
	$this->SetXY( $rf, $y1+5 );
	$this->Cell( 17,4, sprintf("%0.2F", $totalTTC * EURO_VAL), '', '', 'R');
	$this->SetXY( $rf, $y1+10 );
	$this->Cell( 17,4, sprintf("%0.2F", $accompteTTC * EURO_VAL), '', '', 'R');
	$this->SetXY( $rf, $y1+14.8 );
	$this->Cell( 17,4, sprintf("%0.2F", ($totalTTC - $accompteTTC) * EURO_VAL), '', '', 'R');
}

// Permet de rajouter un commentaire (Devis temporaire, REGLE, DUPLICATA, ...)
// en sous-impression
// ATTENTION: APPELER CETTE FONCTION EN PREMIER
function temporaire( $texte )
{
	$this->SetFont('Arial','B',50);
	$this->SetTextColor(203,203,203);
	$this->Rotate(45,55,190);
	$this->Text(55,190,$texte);
	$this->Rotate(0);
	$this->SetTextColor(0,0,0);
}
//*******************************************
//*********************
//fonction venant d'une autre classe qui affiche un tableau sur plusieurs pages 

function Header()
{
	//Imprime l'en-t�te du tableau si n�cessaire
	if($this->ProcessingTable)
		$this->TableHeader();
}

function TableHeader()
{
	$this->SetFont('Arial','B',12);
	$this->SetX($this->TableX);
	$fill=!empty($this->HeaderColor);
	if($fill)
		$this->SetFillColor($this->HeaderColor[0],$this->HeaderColor[1],$this->HeaderColor[2]);
	foreach($this->aCols as $col)
		$this->Cell($col['w'],6,$col['c'],1,0,'C',$fill);
	$this->Ln();
}

function Row($data)
{
	$this->SetX($this->TableX);
	$ci=$this->ColorIndex;
	$fill=!empty($this->RowColors[$ci]);
	if($fill)
		$this->SetFillColor($this->RowColors[$ci][0],$this->RowColors[$ci][1],$this->RowColors[$ci][2]);
	foreach($this->aCols as $col)
		$this->Cell($col['w'],5,$data[$col['f']],1,0,$col['a'],$fill);
	$this->Ln();
	$this->ColorIndex=1-$ci;
}

function CalcWidths($width,$align)
{
	//Calcule les largeurs des colonnes
	$TableWidth=0;
	foreach($this->aCols as $i=>$col)
	{
		$w=$col['w'];
		if($w==-1)
			$w=$width/count($this->aCols);
		elseif(substr($w,-1)=='%')
			$w=$w/100*$width;
		$this->aCols[$i]['w']=$w;
		$TableWidth+=$w;
	}
	//Calcule l'abscisse du tableau
	if($align=='C')
		$this->TableX=max(($this->w-$TableWidth)/2,0);
	elseif($align=='R')
		$this->TableX=max($this->w-$this->rMargin-$TableWidth,0);
	else
		$this->TableX=$this->lMargin;
}

function AddCol($field=-1,$width=-1,$caption='',$align='L')
{
	//Ajoute une colonne au tableau
	if($field==-1)
		$field=count($this->aCols);
	$this->aCols[]=array('f'=>$field,'c'=>$caption,'w'=>$width,'a'=>$align);
}

function Table($query,$prop=array())
{
	//Ex�cute la requ�te
	$res=mysql_query($query) or die('Erreur: '.mysql_error()."<BR>Requ�te: $query");
	//Ajoute toutes les colonnes si aucune n'a �t� d�finie
	if(count($this->aCols)==0)
	{
		$nb=mysql_num_fields($res);
		for($i=0;$i<$nb;$i++)
			$this->AddCol();
	}
	//D�termine les noms des colonnes si non sp�cifi�s
	foreach($this->aCols as $i=>$col)
	{
		if($col['c']=='')
		{
			if(is_string($col['f']))
				$this->aCols[$i]['c']=ucfirst($col['f']);
			else
				$this->aCols[$i]['c']=ucfirst(mysql_field_name($res,$col['f']));
		}
	}
	//Traite les propri�t�s
	if(!isset($prop['width']))
		$prop['width']=0;
	if($prop['width']==0)
		$prop['width']=$this->w-$this->lMargin-$this->rMargin;
	if(!isset($prop['align']))
		$prop['align']='C';
	if(!isset($prop['padding']))
		$prop['padding']=$this->cMargin;
	$cMargin=$this->cMargin;
	$this->cMargin=$prop['padding'];
	if(!isset($prop['HeaderColor']))
		$prop['HeaderColor']=array();
	$this->HeaderColor=$prop['HeaderColor'];
	if(!isset($prop['color1']))
		$prop['color1']=array();
	if(!isset($prop['color2']))
		$prop['color2']=array();
	$this->RowColors=array($prop['color1'],$prop['color2']);
	//Calcule les largeurs des colonnes
	$this->CalcWidths($prop['width'],$prop['align']);
	//Imprime l'en-t�te
	$this->TableHeader();
	//Imprime les lignes
	$this->SetFont('Arial','',11);
	$this->ColorIndex=0;
	$this->ProcessingTable=true;
	while($row=mysql_fetch_array($res))
		$this->Row($row);
	$this->ProcessingTable=false;
	$this->cMargin=$cMargin;
	$this->aCols=array();
}

	protected $values    = array();
    protected $dataset   = array();
    protected $precision = 3;

	 public function setPrecision($n) 
    {
        $this->precision = (int) $n;
    }
    
    public function getPrecision() 
    {
        return $this->precision;
    }

    /**
     * Compute the arithmetic mean, it is calculated by adding a group of numbers
     * and then dividing by the count of those numbers.
     * For example, the mean of 2, 3, 3, 5, 7, and 10 is 30 divided by 6, which is 5.
     *
     * @param array  $x    List of float values for which you want to calculate the mean.
     * @param string $type Mean type [arithmetic|geometric|harmonic], default is arithmetic.
     *
     * @return float Mean
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Mean
     */
    public function mean($x, $type="arithmetic")
    {
        $type = strtolower($type);
        
        if ($type == "arithmetic") {
            $total = 0;

            foreach ($x as $value) {
                $total += $value;
            }
            
            $mean = $total/count($x);
        } elseif ($type == "geometric") {
            $total = 1;

            foreach ($x as $value) {
                $total *= $value;
            }
            
            $mean = pow($total, 1/count($x));
        } elseif ($type == "harmonic") {
            $total = 0;

            foreach ($x as $value) {
                $total += 1/$value;
            }
            
            $mean = count($x)/$total;
        }

        return $mean;
    }

    /**
     * Compute the sample median which is the middle number of a group of numbers; that is,
     * half the numbers have values that are greater than the median, and half the numbers
     * have values that are less than the median.
     * For example, the median of 2, 3, 3, 5, 7, and 10 is 4.
     *
     * @param array $x List of float values for which you want to calculate the median.
     *
     * @return float Sample median
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Median
     */
    public function median($x)
    {
        sort($x);

        $count = count($x);

        if ($count % 2 == 0) {
            $median = ($x[($count / 2) - 1] + $x[$count / 2]) / 2;
        } else {
            $median = $x[floor($count / 2)];
        }

        return $median;
    }

    /**
     * Compute the mode which is the most frequently occurring number in a group of numbers.
     * For example, the mode of 2, 3, 3, 5, 7, and 10 is 3.
     *
     * @param array $x List of float values for which you want to calculate the mode.
     *
     * @return array Returns the most frequently occurring or repetitive value
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Mode_(statistics)
     */
    public function mode($x)
    {
        $counter = array();

        foreach ($x as $value) {
            if (isset($counter[$value])) {
                $counter[$value]++;
            } else {
                $counter[$value] = 1;
            }
        }

        return array_keys($counter, max($counter));
    }

    /**
     * Estimates variance based on a sample, the variance is a measure of how far a set 
     * of numbers is spread out.
     *
     * @param array $x List of float values corresponding to a sample of a population.
     *
     * @return float Returns the sample variance
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Variance
     */
    public function variance($x)
    {
        $mean = $this->mean($x);

        $var = 0;

        foreach ($x as $value) {
            $var += ($value - $mean) * ($value - $mean);
        }

        $var = $var / (count($x) - 1);

        return $var;
    }

    /**
     * Compute the standard deviation based on a sample. The standard deviation is a measure of
     * how widely values are dispersed from the average value (the mean).
     *
     * @param array $x List of float values for which you want to calculate the standard deviation.
     *
     * @return float Returns the standard deviation
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Standard_deviation
     */
    public function sd($x)
    {
        $sd = sqrt($this->variance($x));
        
        return $sd;
    }

    /**
     * Compute the skewness of a distribution. Skewness characterizes the degree of asymmetry of
     * a distribution around its mean. Positive skewness indicates a distribution with an asymmetric
     * tail extending toward more positive values. Negative skewness indicates a distribution with
     * an asymmetric tail extending toward more negative values.
     *
     * @param array $x List of float values for which you want to calculate the skewness.
     *
     * @return float Returns the skewness of a distribution
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Skewness
     */
    public function skew($x)
    {
        $mean = $this->mean($x);
        $sd   = $this->sd($x);
        $n    = count($x);

        $skew = 0;

        foreach ($x as $value) {
            $skew += pow(($value - $mean) / $sd, 3);
        }

        $skew = ($skew * $n) / (($n - 1) * ($n - 2));

        return $skew;
    }
    
    /**
     * Test skewness against 0
     *
     * @param array $x List of float values for which you want to test the skewness for.
     *
     * @return boolean Returns if skewness is significant
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Skewness
     */
    public function isSkew($x)
    {
        $n      = count($x);
        $skew   = $this->skew($x);
        $skewSE = sqrt(6 / $n);
        
        if (abs($skew) > 2 * $skewSE) {
            $result = true;
        } else {
            $result = false;
        }
        
        return $result;
    }

    /**
     * Compute the kurtosis of a distribution. Kurtosis characterizes the relative peakedness or
     * flatness of a distribution compared with the normal distribution. Positive kurtosis indicates
     * a relatively peaked distribution. Negative kurtosis indicates a relatively flat distribution.
     *
     * @param array $x List of float values for which you want to calculate the kurtosis.
     *
     * @return float Returns the kurtosis of a distribution
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Kurtosis
     */
    public function kurt($x)
    {
        $mean = $this->mean($x);
        $sd   = $this->sd($x);
        $n    = count($x);

        $kurt = 0;

        foreach ($x as $value) {
            $kurt += pow(($value - $mean) / $sd, 4);
        }

        $kurt = ($kurt * $n * ($n + 1)) / (($n - 1) * ($n - 2) * ($n - 3));
        $kurt = $kurt - ((3 * ($n - 1) * ($n - 1)) / (($n - 2) * ($n - 3)));

        return $kurt;
    }

    /**
     * Test kurtosis against 0
     *
     * @param array $x List of float values for which you want to test the kurtosis for.
     *
     * @return boolean Returns if kurtosis is significant
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Kurtosis
     */
    public function isKurt($x)
    {
        $n      = count($x);
        $kurt   = $this->kurt($x);
        $kurtSE = sqrt(24 / $n);
        
        if (abs($kurt) > 2 * $kurtSE) {
            $result = true;
        } else {
            $result = false;
        }
        
        return $result;
    }

    /**
     * Compute the coefficients of variation, it shows the extent of variability in relation 
     * to mean of the population.
     *
     * @param array $x List of float values for which you want to calculate the coefficients of variation.
     *
     * @return float Returns the coefficients of variation
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Coefficient_of_variation
     */
    public function cv($x)
    {
        $mean = $this->mean($x);
        $sd   = $this->sd($x);

        $cv = ($sd / $mean) * 100;

        return $cv;
    }

    /**
     * Compute the covariance, the average of the products of deviations for each data point pair.
     * Use covariance to determine the relationship between two data sets. For example, you can
     * examine whether greater income accompanies greater levels of education.
     *
     * @param array $x First list of float values
     * @param array $y Second list of float values
     *
     * @return float Returns the covariance
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Covariance
     */
    public function cov($x, $y)
    {
        $meanX = $this->mean($x);
        $meanY = $this->mean($y);

        $count = count($x);
        $total = 0;

        for ($i=0; $i<$count; $i++) {
            $total += ($x[$i] - $meanX) * ($y[$i] - $meanY);
        }

        $cov = (1 / ($count - 1)) * $total;
        
        return $cov;
    }

    /**
     * Compute the correlation coefficient. Use the correlation coefficient to determine the
     * relationship between two properties. It uses different measures of association, all
     * in the range [-1, 1] with 0 indicating no association.
     *
     * @param array $x First list of float values
     * @param array $y Second list of float values
     *
     * @return float Returns the correlation coefficient
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Correlation
     */
    public function cor($x, $y)
    {
        $cov = $this->cov($x, $y);
        $sdX = $this->sd($x);
        $sdY = $this->sd($y);
        
        $cor = $cov / ($sdX * $sdY);

        return $cor;
    }

    /**
     * Test of the null hypothesis that true correlation is equal to 0
     *
     * @param float   $r Correlation value
     * @param integer $n Number of observations
     *
     * @return float Returns null hypothesis probability: true correlation is equal to 0
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     */
    public function corTest($r, $n)
    {
        $t = $r / sqrt((1 - ($r * $r)) / ($n - 2));

        $result = $this->tDist($t, $n - 2);

        return $result;
    }

    /**
     * Compute the simple linear regression fits a linear model to represent
     * the relationship between a response (or y-) variate, and an explanatory
     * (or x-) variate.
     *
     * @param array   $y      List of float values of the response (or y-) variate.
     * @param array   $x1     List of float values of the first explanatory (or x1) variate.
	 * @param array   $x2     List of float values of the second explanatory (or x2) variate (default is null).
     * @param boolean $origin If TRUE then Intercept value set to 0 (default is FALSE)
     *
     * @return array Returns [intercept], [slope], [r-square], [adj-r-square] as float 
     *               values in addition to standard error of regression model parameters 
     *               [intercept-se] and [slope-se] as well as confidence intervals 
     *               at level 95% [intercept-2.5%], [intercept-97.5%], [slope-2.5%],
     *               and [slope-97.5%]
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Regression_analysis
     */
    public function lm($y, $x1, $x2=null, $origin=false)
    {
        if (is_null($x2)) {
            $multiple = FALSE;
            $k = 1;
        } else {
            $multiple = TRUE;
            $k = 2;
        }
        
        $n = count($y);

        $mx1 = $this->mean($x1);
        $my  = $this->mean($y);

        if (!$multiple) {
            $nominator   = 0;
            $denominator = 0;
            
            $x1_2 = 0;
            $y2   = 0;
            $x1y  = 0;

            for ($i=0; $i<$n; $i++) {
                $nominator   += ($x1[$i] - $mx1) * ($y[$i] - $my);
                $denominator += ($x1[$i] - $mx1) * ($x1[$i] - $mx1);

                $x1_2 += $x1[$i] * $x1[$i];
                $y2   += $y[$i]  * $y[$i];
                $x1y  += $x1[$i] * $y[$i];
            }

            if ($origin) {
                $b  = $x1y / $x1_2;
                $a  = 0;
                $df = $n - 1;
            } else {
                $b  = $nominator / $denominator;
                $a  = $my - $b * $mx1;
                $df = $n - 2;
            }
            $reg_df = 1;
        } else {
            $mx2 = $this->mean($x2);
            
            $ysum  = array_sum($y);
            $x1sum = array_sum($x1);
            $x2sum = array_sum($x2);
            
            $x1_2  = 0;
            $x2_2  = 0;
            $x1x2  = 0;
            $x1y   = 0;
            $x2y   = 0;

            for ($i=0; $i<$n; $i++) {
                $x1_2 += $x1[$i] * $x1[$i];
                $x2_2 += $x2[$i] * $x2[$i];
                $x1x2 += $x1[$i] * $x2[$i];
                $x1y  += $x1[$i] * $y[$i];
                $x2y  += $x2[$i] * $y[$i];
            }
            
            $mx1_2 = $x1_2 - ($x1sum * $x1sum / $n);
            $mx2_2 = $x2_2 - ($x2sum * $x2sum / $n);
            $mx1x2 = $x1x2 - ($x1sum * $x2sum / $n);
            $mx1y  = $x1y - ($ysum * $x1sum / $n);
            $mx2y  = $x2y - ($ysum * $x2sum / $n);

            $b1n = ($mx2_2 * $mx1y) - ($mx1x2 * $mx2y);
            $b1d = ($mx1_2 * $mx2_2) - ($mx1x2 * $mx1x2);
            $b1  = $b1n / $b1d;
            
            $b2n = ($mx1_2 * $mx2y) - ($mx1x2 * $mx1y);
            $b2d = ($mx1_2 * $mx2_2) - ($mx1x2 * $mx1x2);
            $b2  = $b2n / $b2d;
            
            $a = $my - ($b1 * $mx1) - ($b2 * $mx2);
            
            $df     = $n - 3;
            $reg_df = 2;
        }
        
        // Total sum of squares (ss) and Residual sum of squares (rss)
        $total_ss      = 0;
        $regression_ss = 0;
        $residual_ss   = 0;

        for ($i=0; $i<$n; $i++) {
            if ($multiple) {
                $est = $a + ($b1 * $x1[$i]) + ($b2 * $x2[$i]);
            } else {
                $est = $a + ($b * $x1[$i]);
            }

            $total_ss    += pow($y[$i] - $my, 2);
            $residual_ss += ($y[$i] - $est) * ($y[$i] - $est);

            if ($origin) {
                $regression_ss += $est * $est;
            } else {
                $regression_ss += ($est - $my) * ($est - $my);
            }
        }

        // R-square value and Standard error of regression intercept and slope
        if (!$multiple) {
            if ($origin) {
                $r2  = $regression_ss / $y2;

                $ase = 0;
                $bse = sqrt($residual_ss/$df) / sqrt($x1_2);
            } else {
                $r2 = 1 - ($residual_ss/$total_ss);

                $ase = sqrt($residual_ss/$df) * sqrt($x1_2/($n*$denominator));
                $bse = sqrt($residual_ss/$df) / sqrt($denominator);
            }
        } else {
            $r2 = 1 - ($residual_ss/$total_ss);
			
            $ase  = 0;
			$b1se = 0;
			$b2se = 0;
        }
        
        // Significance of regression
        $regression_ms = $regression_ss / $reg_df;
        $residual_ms   = $residual_ss / $df;
        
        $regression_f  = $regression_ms / $residual_ms;
        $regression_p  = $this->fDist($regression_f, $reg_df, $df);
        
        // Output
        if (!$multiple) {
            $result = array('intercept'=>$a, 'slope'=>$b);
        } else {
            $result = array('intercept'=>$a, 'b1'=>$b1, 'b2'=>$b2);
        }
        
        $residual_ms = $residual_ss / ($n - $k - 1);
        $total_ms    = $total_ss / ($n - 1);
        
        $result['r-square']     = $r2;
        $result['adj-r-square'] = 1 - ($residual_ms / $total_ms);
        
        $result['intercept-se']    = $ase;
        $result['intercept-2.5%']  = $a - $this->inverseTCDF(0.05, $df) * $ase;
        $result['intercept-97.5%'] = $a + $this->inverseTCDF(0.05, $df) * $ase;

        if (!$multiple) {
            $result['slope-se']    = $bse;
            $result['slope-2.5%']  = $b - $this->inverseTCDF(0.05, $df) * $bse;
            $result['slope-97.5%'] = $b + $this->inverseTCDF(0.05, $df) * $bse;
        } else {
            $result['b1-se']    = $b1se;
            $result['b1-2.5%']  = $b1 - $this->inverseTCDF(0.05, $df) * $b1se;
            $result['b1-97.5%'] = $b1 + $this->inverseTCDF(0.05, $df) * $b1se;
            
            $result['b2-se']    = $b2se;
            $result['b2-2.5%']  = $b2 - $this->inverseTCDF(0.05, $df) * $b2se;
            $result['b2-97.5%'] = $b2 + $this->inverseTCDF(0.05, $df) * $b2se;
        }
        
        $result['F-statistic'] = $regression_f;
        $result['p-value']     = $regression_p;

        return $result;
    }

    /**
     * Compute the Student's t-Test value to determine whether two samples are likely
     * to have come from the same two underlying populations that have the same mean.
     *
     * @param array   $a      First list of float values
     * @param array   $b      Second list of float values
     * @param boolean $paired Logical indicating whether you want a paired t-test, default
     *                        value is FALSE for unpaired set of data
     *
     * @return float Returns the associated with a Student's t-Test
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/T-Test
     */
    public function tTest ($a, $b, $paired=false)
    {
        if ($paired == true) {
            $count = count($a);

            $diff = array();

            for ($i=0; $i<$count; $i++) {
                $diff[$i] = $a[$i] - $b[$i];
            }

            $mean = $this->mean($diff);
            $var  = $this->variance($diff);

            $t = $mean / sqrt($var / $count);
        } else {
            $meanA = $this->mean($a);
            $meanB = $this->mean($b);

            $varA = $this->variance($a);
            $varB = $this->variance($b);

            $countA = count($a);
            $countB = count($b);

            $t = ($meanA - $meanB) / sqrt(($varA / $countA) + ($varB / $countB));
        }

        return $t;
    }

    /**
     * Returns the standard normal cumulative distribution function. The distribution
     * has a mean of 0 (zero) and a standard deviation of one. Use this function in
     * place of a table of standard normal curve areas.
     *
     * @param float $x    Is the value for which you want the distribution.
     * @param float $mean The distribution mean (default is zero).
     * @param float $sd   The distribution standard deviation (default is one).
     *
     * @return float the standard normal cumulative distribution function.
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Normal_distribution
     */
    public function norm ($x, $mean=0, $sd=1)
    {
        $y = (1 / sqrt(2 * pi())) * exp(-0.5 * pow($x, 2));

        return $y;
    }

    private function _zip ($q, $i, $j, $b)
    {
        $zz = 1;
        $z  = $zz;
        $k  = $i;

        while ($k <= $j) {
            $zz *= $q * $k / ($k - $b);
            $z  += $zz;
            $k  += 2;
        }

        return $z;
    }

    /**
     * Returns the Percentage Points (probability) for the Student t-distribution
     * where a numeric value (t) is a calculated value of t for which the Percentage
     * Points are to be computed.
     *
     * @param float   $t    Is the numeric value at which to evaluate the distribution.
     * @param integer $n    Is an integer indicating the number of degrees of freedom.
     * @param integer $tail Specifies the number of distribution tails to return.
     *                      If tail = 1, TDIST returns the one-tailed distribution.
     *                      If tail = 2, TDIST returns the two-tailed distribution.
     *
     * @return float the Percentage Points (probability) for the Student t-distribution
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/T-distribution
     */
    public function tDist ($t, $n, $tail=1)
    {
        $pj2 = pi() / 2;

        $t = abs($t);

        $rt = $t / sqrt($n);
        $fk = atan($rt);

        if ($n == 1) {
            $result = 1 - $fk / $pj2;
        } else {
            $ek = sin($fk);
            $dk = cos($fk);

            if (($n % 2) == 1) {
                $result = 1 - ($fk + $ek * $dk * $this->_zip($dk * $dk, 2, $n-3, -1)) / $pj2;
            } else {
                $result = 1 - $ek * $this->_zip($dk * $dk, 1, $n-3, -1);
            }
        }

        return $result / $tail;
    }

    /**
     * Returns the F probability distribution. You can use this function to determine
     * whether two data sets have different degrees of diversity.
     *
     * @param float   $f   Is the value at which to evaluate the function.
     * @param integer $df1 Is the numerator degrees of freedom.
     * @param integer $df2 Is the denominator degrees of freedom.
     *
     * @return float the F probability distribution
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/F-distribution
     */
    public function fDist ($f, $df1, $df2)
    {
        $pj2 = pi() / 2;

        $x = $df2 / ($df1 * $f + $df2);

        if (($df1 % 2) == 0) {
            return $this->_zip(1 - $x, $df2, $df1 + $df2 - 4, $df2 - 2) * pow($x, $df2 / 2);
        }

        if (($df2 % 2) == 0) {
            return 1 - $this->_zip($x, $df1, $df1 + $df2 - 4, $df1 - 2) * pow(1 - $x, $df1 / 2);
        }

        $tan = atan(sqrt($df1 * $f / $df2));
        $a   = $tan / $pj2;
        $sat = sin($tan);
        $cot = cos($tan);

        if ($df2 > 1) {
            $a = $a + $sat * $cot * $this->_zip($cot * $cot, 2, $df2 - 3, -1) / $pj2;
        }

        if ($df1 == 1) {
            return 1 - $a;
        }

        $c = 4 * $this->_zip($sat * $sat, $df2 + 1, $df1 + $df2 - 4, $df2 - 2) * $sat * pow($cot, $df2) / pi();

        if ($df2 == 1) {
            return 1 - $a + $c / 2;
        }

        $k = 2;

        while ($k <= ($df2 - 1) / 2) {
            $c *= $k / ($k - 0.5);
            $k++;
        }

        return 1 - $a + $c;
    }

    /**
     * Return the probability of normal z value
     * Adapted from a polynomial approximation in:
     *     Ibbetson D, Algorithm 209
     *     Collected Algorithms of the CACM 1963 p. 616
     *
     * @param float $z Is the value at which you want to evaluate the probability.
     *
     * @return float the probability of normal z value
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     */
    public function normCDF ($z)
    {
        $max = 6;

        if ($z == 0) {
            $x = 0;
        } else {
            $y = abs($z) / 2;
            if ($y >= ($max / 2)) {
                $x = 1;
            } elseif ($y < 1) {
                $w = $y * $y;
                $x = ((((((((0.000124818987 * $w
                          - 0.001075204047) * $w + 0.005198775019) * $w
                          - 0.019198292004) * $w + 0.059054035642) * $w
                          - 0.151968751364) * $w + 0.319152932694) * $w
                          - 0.531923007300) * $w + 0.797884560593) * $y * 2;
            } else {
                $y -= 2;
                $x = (((((((((((((-0.000045255659 * $y
                                + 0.000152529290) * $y - 0.000019538132) * $y
                                - 0.000676904986) * $y + 0.001390604284) * $y
                                - 0.000794620820) * $y - 0.002034254874) * $y
                                + 0.006549791214) * $y - 0.010557625006) * $y
                                + 0.011630447319) * $y - 0.009279453341) * $y
                                + 0.005353579108) * $y - 0.002141268741) * $y
                                + 0.000535310849) * $y + 0.999936657524;
            }
        }

        if ($z > 0) {
            $result = ($x + 1) / 2;
        } else {
            $result = (1 - $x) / 2;
        }

        return $result;
    }

    /**
     * Returns the one-tailed probability of the chi-squared distribution. The chi-squared
     * distribution is associated with a chi-squared test. Use the chi-squared test to
     * compare observed and expected values.
     * Adapted from:
     *     Hill, I. D. and Pike, M. C.  Algorithm 299
     *     Collected Algorithms for the CACM 1967 p. 243
     * Updated for rounding errors based on remark in
     *     ACM TOMS June 1985, page 185
     *
     * @param float   $x  Is the value at which you want to evaluate the distribution.
     * @param integer $df Is the number of degrees of freedom.
     *
     * @return float the probability of the chi-squared distribution
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     */
    public function chiDist ($x, $df)
    {
       
$LOG_SQRT_PI=log(sqrt(pi()));
$I_SQRT_PI=1 / sqrt(pi());

	   // define(LOG_SQRT_PI, log(sqrt(pi())));
        // define(I_SQRT_PI, 1 / sqrt(pi()));

        if ($x <= 0 || $df < 1) {
            $result = 1;
        }

        $a = $x / 2;

        if ($df % 2 == 0) {
            $even = true;
        } else {
            $even = false;
        }

        if ($df > 1) {
            $y = exp(-1 * $a);
        }

        if ($even) {
            $s = $y;
        } else {
            $s = 2 * $this->normCDF(-1 * sqrt($x));
        }

        if ($df > 2) {
            $x = ($df - 1) / 2;

            if ($even) {
                $z = 1;
            } else {
                $z = 0.5;
            }

            if ($a > 20) {
                if ($even) {
                    $e = 0;
                } else {
                    $e = $LOG_SQRT_PI;
                }

                $c = log($a);

                while ($z <= $x) {
                    $e += log($z);
                    $s += exp($c * $z - $a - $e);
                    $z += 1;
                }

                $result = $s;
            } else {
                if ($even) {
                    $e = 1;
                } else {
                    $e = $I_SQRT_PI / sqrt($a);
                }

                $c = 0;

                while ($z <= $x) {
                    $e *= ($a / $z);
                    $c += $e;
                    $z += 1;
                }

                $result = $c * $y + $s;
            }
        } else {
            $result = $s;
        }

        return $result;
    }

    /**
     * Performs chi-squared contingency table tests and goodness-of-fit tests.
     *
     * Example:
     * <code>
     * $table['Automatic'] = array('4 Cylinders' => 3, '6 Cylinders' => 4, '8 Cylinders' => 12);
     * $table['Manual']    = array('4 Cylinders' => 8, '6 Cylinders' => 3, '8 Cylinders' => 2);
     *
     * $results = $stats->chiTest($table);
     * </code>
     *
     * @param array $table Specifies the two-way, n x m table containing the counts
     *
     * @return array [chi] => the value the chi-squared test statistic
     *               [df] => the degrees of freedom of the approximate chi-squared distribution of the test statistic.
     *               [expected] => the expected counts under the null hypothesis.
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     */
    public function chiTest ($table)
    {
        $total = 0;
        $chi   = 0;

        foreach ($table as $category => $row) {
            foreach ($row as $sample => $cell) {
                if (isset($rows[$category])) {
                    $rows[$category] += $cell;
                } else {
                    $rows[$category] = $cell;
                }

                if (isset($cols[$sample])) {
                    $cols[$sample] += $cell;
                } else {
                    $cols[$sample] = $cell;
                }

                $total += $cell;
            }
        }

        $r  = count($rows);
        $c  = count($cols);
        $df = ($r - 1) * ($c - 1);

        $expected = array();

        foreach ($table as $category => $row) {
            foreach ($row as $sample => $cell) {
                // fo frequency of the observed value
                // fe frequency of the expected value
                $fo  = $cell;
                $fe  = ($rows[$category] * $cols[$sample]) / $total;
                $chi += pow($fo - $fe, 2) / $fe;

                $expected[$category][$sample] = $fe;
            }
        }

        return array('chi'=>$chi, 'df'=>$df, 'expected'=>$expected);
    }

    /**
     * Returns the inverse of the standard normal cumulative distribution. 
     * The distribution has a mean of zero and a standard deviation of one.
     * This is an implementation of the algorithm published at:
     * http://home.online.no/~pjacklam/notes/invnorm/
     *
     * @param float $p Is a probability corresponding to the normal distribution between 0 and 1.
     *
     * @return float Inverse of the standard normal cumulative distribution, with a probability of $p
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     */
    public function inverseNormCDF($p) 
    {
        /* coefficients for the rational approximants for the normal probit: */
        $a1	= -3.969683028665376e+01;
        $a2	=  2.209460984245205e+02;
        $a3	= -2.759285104469687e+02;
        $a4	=  1.383577518672690e+02;
        $a5	= -3.066479806614716e+01;
        $a6	=  2.506628277459239e+00;
        $b1	= -5.447609879822406e+01;
        $b2	=  1.615858368580409e+02;
        $b3	= -1.556989798598866e+02;
        $b4	=  6.680131188771972e+01;
        $b5	= -1.328068155288572e+01;
        $c1	= -7.784894002430293e-03;
        $c2	= -3.223964580411365e-01;
        $c3	= -2.400758277161838e+00;
        $c4	= -2.549732539343734e+00;
        $c5	=  4.374664141464968e+00;
        $c6	=  2.938163982698783e+00;
        $d1	=  7.784695709041462e-03;
        $d2	=  3.224671290700398e-01;
        $d3	=  2.445134137142996e+00;
        $d4	=  3.754408661907416e+00;

        $p_low  = 0.02425;
        $p_high	= 1.0 - $p_low;

        if (0 < $p && $p < $p_low) {
            /* rational approximation for the lower region */
            $q = sqrt(-2 * log($p));
            $x = ((((($c1 * $q + $c2) * $q + $c3) * $q + $c4) * $q + $c5) * $q + $c6) / (((($d1 * $q + $d2) * $q + $d3) * $q + $d4) * $q + 1);
        } elseif ($p_low <= $p && $p <= $p_high) {
            /* rational approximation for the central region */
            $q = $p - 0.5;
            $r = $q * $q;
            $x = ((((($a1 * $r + $a2) * $r + $a3) * $r + $a4) * $r + $a5) * $r + $a6) * $q / ((((($b1 * $r + $b2) * $r + $b3) * $r + $b4) * $r + $b5) * $r + 1);
        } else {
            /* rational approximation for the upper region */
            $q = sqrt(-2 * log(1 - $p));
            $x = -((((($c1 * $q + $c2) * $q + $c3) * $q + $c4) * $q + $c5) * $q + $c6) / (((($d1 * $q + $d2) * $q + $d3) * $q + $d4) * $q + 1);
        }

        return $x;
    }

    /**
     * Returns the t-value of the Student's t-distribution as a function of 
     * the probability and the degrees of freedom. This is an implementation 
     * of the algorithm in:
     * G. W. Hill. "Algorithm 396: Student's t-Quantiles." Communications
     * of the ACM 13(10):619--620.  ACM Press, October, 1970.
     *
     * @param float   $p Is the probability associated with the two-tailed Student's t-distribution between 0 and 1.
     * @param integer $n Is the number of degrees of freedom with which to characterize the distribution.
     *
     * @return float t-value of the Student's t-distribution for the terms above (i.e. $p and $n).
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     */
    public function inverseTCDF($p, $n) 
    {
        if ($n == 1) {
            $p     *= M_PI_2;
            $result = cos($p) / sin($p);
        } else {
            $a = 1 / ($n - 0.5);
            $b = 48 / ($a * $a);
            $c = ((20700 * $a / $b - 98) * $a - 16) * $a + 96.36;
            $d = ((94.5 / ($b + $c) - 3) / $b + 1) * sqrt($a * M_PI_2) * $n;
            $y = pow(2 * $d * $p, 2 / $n);
            
            if ($y > (0.05 + $a)) {
                /* asymptotic inverse expansion about the normal */
                $x = $this->inverseNormCDF($p * 0.5);
                $y = $x * $x;

                if ($n < 5) {
                    $c += 0.3 * ($n - 4.5) * ($x + 0.6);
                }

                $c  = (((0.05 * $d * $x - 5) * $x - 7) * $x - 2) * $x + $b + $c;
                $y  = (((((0.4 * $y + 6.3) * $y + 36) * $y + 94.5) / $c - $y - 3) / $b + 1) * $x;
                $y *= $a * $y;

                if ($y > 0.002) {
                    $y = exp($y) - 1;
                } else {
                    $y += 0.5 * $y * $y;
                }
            } else {
                $y = ((1 / ((($n + 6) / ($n * $y) - 0.089 * $d - 0.822) * ($n + 2) * 3) + 0.5 / ($n + 4)) * $y - 1) * ($n + 1) / ($n + 2) + 1 / $y;
            }
            
            $result = sqrt($n * $y);
        }

        return $result;
    }
    
    /**
     * Returns the Shannon or Simpson index value
     *
     * @param array  $abundances Associated array where keys refer to the categories and values refer to the observation counts in each category.
     * @param string $index      Which index you would like to calculate ["shannon"|"simpson"] (default is "shannon")
     * @param float  $base       Base of the logarithmic transformation used in the Shannon index (default is M_E)
     *
     * @return float The index (either Shannon or Simpson as selected in the $index parameter)
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Diversity_index
     */
    public function diversity ($abundances, $index='shannon', $base=M_E)
    {
        $index = strtolower($index);
        $N = array_sum($abundances);

        // Calculate the proportion of characters belonging to each category
        foreach ($abundances as $key=>$value) {
            $P["$key"] = $value / $N;
        }

        $result = 0;

        if ($index == 'shannon') {
            foreach ($P as $key=>$value) {
                $result += $value * log($value, $base);
            }
            $result = -1 * $result;
        } elseif ($index == 'simpson') {
            if ($N < 20) {
                foreach ($abundances as $key=>$value) {
                    $result += $value * ($value - 1);
                }
                $result = $result / ($N * ($N - 1));
            } else {
                foreach ($P as $key=>$value) {
                    $result += $value * $value;
                }
            }
        }

        return $result;
    }

    /**
     * Perform standardize transformation; variables are commonly standardized to 
     * zero mean and unit variance, and this will usually be necessary if they are 
     * measured in different units.
     *
     * @param array   $x   List of float values for which you want to standardize.
     * @param boolean $var Standardize variance to be one, default is TRUE.
     *
     * @return array Returns the standardize list of float values using same keys.
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Feature_scaling#Standardization
     */
    public function standardize ($x, $var=true) 
    {
        $mean = $this->mean($x);
        $sd   = $this->sd($x);
        $min  = min($x);
        $max  = max($x);

        foreach ($x as $key => $value) {
            if ($var) {
                $x[$key] = ($value - $mean) / $sd;
            } else {
                $x[$key] = $value - 0.5 * ($min + $max);
            }
        }

        return $x;
    }

    /**
     * The boxplot summarizes a great deal of information very clearly. The 
     * horizontal line shows the median. The bottom and top of the box show 
     * the 25th and 75th percentiles, respectively. The vertical dashed lines 
     * show one of two things: either the maximum value or 1.5 times the 
     * interquartile range of the data (roughly 2 standard deviations). Points 
     * more than 1.5 times the interquartile range above and below are defined 
     * as outliers and plotted individually.
     *          
     * @param array $x List of float values corresponding to a sample of a population.
     *                    
     * @return array Returns min, q1, median, q3, max, and array of outliers 
     *               to render requested boxplot.
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Boxplot
     */
    public function boxplot ($x) // jai trouver une erreur  dans le resultat  du boxplote 
    {
        sort($x);

        $count  = count($x);
        $median = $this->median($x);
        $sd     = $this->sd($x);
        $q1     = $x[round($count / 4)];
        $q3     = $x[round($count * 3 / 4)];
        $min	= $x[0];
        $max	= $x[$count - 1];

        // Outliars ]median + 2sd, median - 2sd[
        $outliers = array();
        
        if ($min < $median - 2 * $sd) {
            $min = round($median - 2 * $sd, $this->precision);

            foreach ($x as $value) {
                if ($value < $min) {
                    $outliers[] = $value;
                }
            }
        } 

        if ($max > $median + 2 * $sd) {
            $max = round($median + 2 * $sd, $this->precision);

            foreach ($x as $value) {
                if ($value > $max) {
                    $outliers[] = $value;
                }
            }
        }

        return array('min'=>$min, 'q1'=>$q1, 'median'=>$median, 'q3'=>$q3, 'max'=>$max, 'outliers'=>$outliers);
    }

    /**
     * Histogram is a graphical representation showing a visual impression of the 
     * distribution of data. It is consists of tabular  frequencies, shown as adjacent 
     * rectangles, erected over discrete intervals (bins), with an area equal to the 
     * frequency of the observations in the interval.
     *
     * @param array   $x    List of float values corresponding to a sample of a population.
     * @param integer $bins Total number of bins (default is FALSE to calculate optimum number of bins).
     *                    
     * @return array Returns associated array where keys are bin ranges and values are count of 
     *               samples exists in this range to render requested histogram.
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Histogram
     */
    public function hist ($x, $bins=false) 
    {
        if ($bins === false) {
            $bins = ceil(sqrt(count($x)));
        }
    
        $max = max($x);
        $min = min($x);
        $bar = ($max - $min) / $bins;
        
        $bars   = array();
        $ranges = array();
        
        for ($i = 0; $i < $bins; $i++) {
            $bars[$i] = 0;
            
            $low  = round($min + $i * $bar, $this->precision);
            $high = round($min + ($i + 1) * $bar, $this->precision);
            
            $ranges[$i] = "$low-$high";
        }
        
        foreach ($x as $value) {
            if ($value == $max) {
                $bars[$bins-1]++;
            } else {
                $bars[floor(($value - $min) / $bar)]++;
            }
        }

        return(array_combine($ranges, $bars));
    }
    
    /**
     * A normal Q�Q plot comparing randomly generated, independent 
     * standard normal data on the vertical axis to a standard normal 
     * population on the horizontal axis. The linearity of the points 
     * suggests that the data are normally distributed.
     *
     * @param array $y List of float values corresponding to a sample of a population.
     *                    
     * @return array Returns x,y coordinates for each point to render requested Q-Q plot.
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Q%E2%80%93Q_plot
     */
    public function qqnorm ($y) 
    {
        asort($y);
        
        $qqx = array();
        $qqy = array();
        
        $n = count($y);
        $i = 1;

        foreach ($y as $k=>$v) {
            $z = $this->inverseNormCDF(($i - 0.5) / $n);
            $qqx[$k] = $z;
            $qqy[$k] = $v;
            $i++;
        }
        
        ksort($qqx);
        ksort($qqy);
        
        $qq = array();

        $qq['x'] = array_values($qqx);
        $qq['y'] = array_values($qqy);
        
        return $qq;
    }
    
    /**
     * A ternary plot is a barycentric plot on three variables which sum to a constant. 
     * It graphically depicts the ratios of the three variables as positions in an equilateral 
     * triangle. It is used to show the compositions of systems composed of three species.
     *
     * @param array $x List of float values corresponding to the X sample of a population.
     * @param array $y List of float values corresponding to the Y sample of a population.
     * @param array $z List of float values corresponding to the Z sample of a population.
     *                    
     * @return array Returns x,y coordinates for each data point to render requested ternary plot.
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Ternary_plot
     */
    public function ternary ($x, $y, $z)
    {
        $n = count($x);
        
        $x2d = array();
        $y2d = array();
        
        for ($i = 0; $i < $n; $i++) {
            $x2d[] = round(0.5 * (2 * $y[$i] + $z[$i]) / ($x[$i] + $y[$i] + $z[$i]), $this->precision);
            $y2d[] = round((sqrt(3) / 2) * $z[$i] / ($x[$i] + $y[$i] + $z[$i]), $this->precision);
        }
        
        $tri = array();
        
        $tri['x'] = $x2d;
        $tri['y'] = $y2d;
        
        return $tri;
    }
    
    /**
     * A moving average is commonly used with time series data to smooth out 
     * short-term fluctuations and highlight longer-term trends or cycles.
     * Financial applications use mean of the previous n points. However, in 
     * science and engineering the mean is normally taken from an equal number 
     * of data on either side of a central value. This ensures that variations 
     * in the mean are aligned with the variations in the data rather than being 
     * shifted in time.
     *
     * @param array   $x         Vector of input samples listed as float values.
     * @param integer $window    Number of datum points used to calculate moving average.
     * @param boolean $financial If TRUE use mean of the previous n datum points, else mean
     *                           is taken from an equal number of data on either side, 
     *                           default is FALSE.
     *                    
     * @return array List of float values represet calculated moving average.
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Moving_average
     */
    public function movingAvg($x, $window=3, $financial=false) 
    {
        $n = count($x);
        $y = array();
        
        $y[$window - 1] = array_sum(array_slice($x, 0, $window)) / $window;

        for ($i=$window; $i<$n; $i++) {
            $y[$i] = $y[$i-1] + ($x[$i] - $x[$i-$window]) / $window;
        }
        
        if ($financial) {
            $sma = $y;
        } else {
            $shift = floor($window / 2);
            $sma   = array();
            
            for ($i=$window-1; $i<$n; $i++) {
                $sma[$i-$shift] = $y[$i];
            }
        }

        return $sma;
    }
    
    /**
     * A ranking is a relationship between a set of items such that, for any two 
     * items, the first is either 'ranked higher than', 'ranked lower than' or 
     * 'ranked equal to' the second. This method implement standard competition 
     * ranking strategy ("1224" ranking). In competition ranking, items that compare 
     * equal receive the same ranking number, and then a gap is left in the ranking 
     * numbers. The number of ranking numbers that are left out in this gap is one 
     * less than the number of items that compared equal.
     *
     * @param array $list Vector of input samples listed as float values.
     *                    
     * @return array List of integer values represet ranks of input items list.
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Ranking
     */
    public function rank($list) 
    {
        $ranked = array();
        $sorted = $list;
        sort($sorted);
        
        foreach ($list as $x) {
            $ranked[] = array_search($x, $sorted) + 1;
        }
        
        return $ranked;
    }
    
    /**
     * Matrix addition is the operation of adding two matrices by adding the 
	 * corresponding entries together 
     *
     * @param array $a First operation parameter, 2D array (i.e. array of arrays) with integer index starts from 1
     * @param array $b Second operation parameter, 2D array (i.e. array of arrays) with integer index starts from 1
     *                    
     * @return array Addition result in 2D array format (i.e. array of arrays) with integer index starts from 1
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Matrix_addition
     */
    public function mAddition($a, $b)
    {
        $n = count($a);
        $m = count($a[1]);
        
        if ($n != count($b) || $m != count($b[1])) {
            throw new KashiException('The two matrices should be of the same dimensions');
        }
        
        for ($i=1; $i<=$n; $i++) {
            for ($j=1; $j<=$m; $j++) {
                $c[$i][$j] = $a[$i][$j] + $b[$i][$j];
            }
        }
        
        return $c;
    }
    
    /**
     * Matrix subtraction is the operation of subtracting corresponding elements of A and B, 
	 * and has the same dimensions as A and B.
     *
     * @param array $a First operation parameter, 2D array (i.e. array of arrays) with integer index starts from 1
     * @param array $b Second operation parameter, 2D array (i.e. array of arrays) with integer index starts from 1
     *                    
     * @return array Subtraction result in 2D array format (i.e. array of arrays) with integer index starts from 1
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Matrix_addition
     */
    public function mSubtraction($a, $b)
    {
        $n = count($a);
        $m = count($a[1]);
        
        if ($n != count($b) || $m != count($b[1])) {
            throw new KashiException('The two matrices should be of the same dimensions');
        }
        
        for ($i=1; $i<=$n; $i++) {
            for ($j=1; $j<=$m; $j++) {
                $c[$i][$j] = $a[$i][$j] - $b[$i][$j];
            }
        }
        
        return $c;
    }
    
    /**
     * Matrix multiplication is a binary operation that takes a pair of matrices, and produces another matrix.
	 * - Scalar multiplication of a matrix A with a scalar b gives another matrix bA of the same size as A.
	 * - Matrix product (two matrices) assume two matrices are to be multiplied. If A is an n�m matrix and B is 
	 *   an m�p matrix, the result AB of their multiplication is an n�p matrix defined only if the number of
	 *   columns m in A is equal to the number of rows m in B.
     *
     * @param array $a First operation parameter, 2D array (i.e. array of arrays) with integer index starts from 1
     * @param array $b Second operation parameter, float for scalar multiplication or 2D array for matrix product
	 *                 (i.e. array of arrays) with integer index starts from 1
     *                    
     * @return array Multiplication result in 2D array format (i.e. array of arrays) with integer index starts from 1
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Matrix_multiplication
     */
    public function mMultiplication($a, $b)
    {
        if (is_numeric($b)) {
            $n = count($a);
            $m = count($a[1]);
            
            for ($i=1; $i<=$n; $i++) {
                for ($j=1; $j<=$m; $j++) {
                    $c[$i][$j] = $a[$i][$j] * $b;
                }
            }
        } else {
            $an = count($a);
            $am = count($a[1]);
            $bn = count($b);
            $bm = count($b[1]);

            if ($am != $bn) {
                throw new KashiException('The number of columns in first matrix should be equal to the number of rows in second matrix');
            } else {
                $n = $an;
                $m = $bm;
                $k = $am;
            }
            
            for ($i=1; $i<=$n; $i++) {
                for ($j=1; $j<=$m; $j++) {
                    $c[$i][$j] = 0;
                    for ($s=1; $s<=$k; $s++) {
                        $c[$i][$j] += $a[$i][$s] * $b[$s][$j];
                    }
                }
            }
        }
        
        return $c;
    }
    
    /**
     * Transpose of a matrix A is another matrix t(A) created by write the rows of A as the columns of t(T)
     *
     * @param array $a 2D array (i.e. array of arrays) with integer index starts from 1
     *                    
     * @return array Transpose result in 2D array format (i.e. array of arrays) with integer index starts from 1
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Transpose
     */
    public function mTranspose($a)
    {
        $n = count($a);
        $m = count($a[1]);
        
        for ($i=1; $i<=$n; $i++) {
            for ($j=1; $j<=$m; $j++) {
                $c[$j][$i] = $a[$i][$j];
            }
        }
        
        return $c;        
    }
    
    /**
     * The determinant is a value associated with a square matrix. It can be computed from the entries of 
	 * the matrix by a specific arithmetic expression.
     *
     * @param array $a 2D array (i.e. array of arrays) with integer index starts from 1
     *                    
     * @return float Matrix determinant value
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Determinant
     */
    public function mDeterminant($a)
    {
        $n = count($a);
        
        if ($n != count($a[1])) {
            throw new KashiException('Determinat can be calculated only for squared matrix');
        }
        
        if ($n == 1) {
            $d = $a[1][1];
        } elseif ($n == 2) {
            $d = ($a[1][1] * $a[2][2]) - ($a[1][2] * $a[2][1]);
        } else {
            $d = 0;
            
            for ($i=1; $i<=$n; $i++) {
                if ($i % 2 == 1) {
                    $t = $a[1][$i];
                } else {
                    $t = -1 * $a[1][$i];
                }
                
                $c = array();
                for ($x=2; $x<=$n; $x++) {
                    for ($y=1; $y<=$n; $y++) {
                        if ($y < $i) {
                            $c[$x-1][$y] = $a[$x][$y];
                        } elseif ($y == $i) {
                            continue;
                        } else {
                            $c[$x-1][$y-1] = $a[$x][$y];
                        }
                    }
                }
                
                $d += $t * $this->mDeterminant($c);
            }
        }
        
        return $d;
    }
    
    /**
     * A minor of a matrix A is the determinant of some smaller square matrix, cut down from A by
	 * removing one or more of its rows or columns. Minors obtained by removing just one row and one 
	 * column from square matrices (first minors) are required for calculating matrix cofactors, which 
	 * in turn are useful for computing both the determinant and inverse of square matrices. The (i,j) 
	 * cofactor is obtained by multiplying the minor by (-1)^(i+j).
     *
     * @param array $a 2D array (i.e. array of arrays) with integer index starts from 1
     *                    
     * @return array Matrix A cofactor
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Cofactor_matrix
     */
    public function mCofactor($a)
    {
        $n = count($a);
        
        if ($n != count($a[1])) {
            throw new KashiException('CoFactor matrix can be calculated only for squared matrix');
        }

        for ($i=1; $i<=$n; $i++) {
            for ($j=1; $j<=$n; $j++) {
                if (($i+$j) % 2 == 1) {
                    $b[$i][$j] = -1;
                } else {
                    $b[$i][$j] = 1;
                }
                
                $c = array();
                for ($x=1; $x<=$n; $x++) {
                    if ($x == $i) {
                        continue;
                    }
                    for ($y=1; $y<=$n; $y++) {
                        if ($y == $j) {
                            continue;
                        }
                        
                        if ($x < $i) {
                            if ($y < $j) {
                                $c[$x][$y] = $a[$x][$y];
                            } else {
                                $c[$x][$y-1] = $a[$x][$y];
                            }
                        } else {
                            if ($y < $j) {
                                $c[$x-1][$y] = $a[$x][$y];
                            } else {
                                $c[$x-1][$y-1] = $a[$x][$y];
                            }
                        }
                    }
                }
                
                $b[$i][$j] *= $this->mDeterminant($c);
            }
        }
        
        return $b;
    }
    
    /**
     * The adjugate or classical adjoint (occasionally referred to as adjunct) of a square 
	 * matrix is the transpose of the cofactor matrix.
     *
     * @param array $a 2D array (i.e. array of arrays) with integer index starts from 1
     *                    
     * @return array Matrix A adjoint
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Adjugate_matrix
     */
    public function mAdjoint($a)
    {
        $b = $this->mCofactor($a);
        $c = $this->mTranspose($b);
        
        return $c;
    }
    
    /**
     * In linear algebra an n-by-n (square) matrix A is called invertible (some authors use 
	 * non-singular or non-degenerate) if there exists an n-by-n matrix B such that:
	 * AB = BA = In
	 * where In denotes the n-by-n identity matrix and the multiplication used is ordinary 
	 * matrix multiplication. If this is the case, then the matrix B is uniquely determined 
	 * by A and is called the inverse of A.
     *
     * @param array $a 2D array (i.e. array of arrays) with integer index starts from 1
     *                    
     * @return array Inverse of A matrix
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Invertible_matrix
     */
    public function mInverse($a)
    {
        // An nXn square matrix that that contains only 1's along its diagonal 
        // is called an identity matrix, and is denoted by I
        // Any matrix that is not invertible is said to be singular
        // $a * inv($a) = I
        $det = $this->mDeterminant($a);
        
        if ($det == 0) {
            throw new KashiException('It is singular matrix because its determinant is 0');
        }

        $adj = $this->mAdjoint($a);
        
        $b = $this->mMultiplication($adj, 1/$det);
        
        return $b;
    }
    
    /**
     * Path analysis is used to describe the directed dependencies among a set of variables. This includes models 
	 * equivalent to any form of multiple regression analysis, factor analysis, canonical correlation analysis, 
	 * as well as discriminant analysis.
     *
     * @param array $y List of float values of the dependent variable.
     * @param array $x 2D array (i.e. array of arrays) with integer index starts from 1 for
	 *                 each independent variable.
     *
     * @return array Returns the covariance
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/Path_analysis_(statistics)
     */
    public function path($y, $x)
    {
        $n = count($x) + 1;
        for ($i=1; $i<$n; $i++) {
            $r[$i][1] = $this->cor($y, $x[$i]);
        }
        
        for ($i=1; $i<$n-1; $i++) {
            $R[$i][$i] = 1;
            for ($j=$i+1; $j<$n; $j++) {
                $R[$i][$j] = $this->cor($x[$i], $x[$j]);
                $R[$j][$i] = $R[$i][$j];
            }
        }
        $R[$n-1][$n-1] = 1;

        $invR = $this->mInverse($R);
        $p    = $this->mMultiplication($invR, $r);

        $n = count($p);
        for ($i=1; $i<=$n; $i++) {
            $p[$i] = $p[$i][1];
        }

        return $p;
    }
    
    /**
     * a system of linear equations (or linear system) is a collection of linear equations involving the same set 
	 * of variables. A solution to a linear system is an assignment of numbers to the variables such that all the 
	 * equations are simultaneously satisfied.
     *
     * @param array $a m�n matrix of system coefficients for m equations and n unknowns with integer index starts from 1
     * @param array $b Vector of the constant terms with m entries (i.e. array with integer index starts from 1)
     *                    
     * @return array Returns the entire solution set expressed in matrix form with integer index starts from 1
     * @author Khaled Al-Sham'aa <khaled.alshamaa@gmail.com>
     * @url http://en.wikipedia.org/wiki/System_of_linear_equations
     */
    public function solve($a, $b)
    {
        if (count($a[1]) != count($b)) {
            throw new KashiException("Number of X's should be same as number of equations!");
        }

        $n = count($b);
        for ($i=1; $i<=$n; $i++) {
            $b[$i] = array(1=>$b[$i]);
        }

        $invA = $this->mInverse($a);
        $x    = $this->mMultiplication($invA, $b);

        $n = count($x);
        for ($i=1; $i<=$n; $i++) {
            $x[$i] = $x[$i][1];
        }

        return $x;
    }




















}


?>
