<?php
require_once("fpdf.php");
class PDF_USA_Calendar extends FPDF
{
var $date;
var $squareHeight;
var $squareWidth;
var $longestMonth;
var $tinySquareSize;

function PDF_USA_Calendar($orientation='L', $format="Letter")
{
	$this->FPDF($orientation, 'mm', $format);
	// compute longest month name
	$this->longestMonth = "";
	for ($month = 1; $month <= 12; ++$month)
		{
		$monthYear = gmdate("F Y", jdtounix($this->MDYtoJD($month,1,2009)));
		if (strlen($monthYear) > strlen($this->longestMonth))
			{
			$this->longestMonth = $monthYear;
			}
		}
	// compute font size
	$this->tinySquareSize = 4;
	$this->headerFontSize = 70;
	$this->SetFont("Times", "B", $this->headerFontSize);
	$width = $this->w - $this->lMargin - $this->rMargin;
	while ($this->GetStringWidth($this->longestMonth) > $width - $this->tinySquareSize * 22)
		{
		--$this->headerFontSize;
		$this->SetFont("Times", "B", $this->headerFontSize);
		}
}

// useful date manipulation routines

function JDtoYMD($date, &$year, &$month, &$day)
{
	$string = JDToGregorian($date);
	$month = strtok($string, " -/");
	$day = strtok(" -/");
	$year = strtok(" -/");
}

function MDYtoJD($month, $day, $year)
{
	if (! $month || ! $day || ! $year)
		return 0;
	$a = floor((14-$month)/12);
	$y = floor($year+4800-$a);
	$m = floor($month+12*$a-3);
	$jd = $day+floor((153*$m+2)/5)+$y*365;
	$jd += floor($y/4)-floor($y/100)+floor($y/400)-32045;
	return $jd;
}

function lastMonth($date)
{
	$this->JDtoYMD($date, $year, $month, $day);
	if (--$month == 0)
		{
		$month = 12;
		$year--;
		}
	return GregorianToJD($month, $day, $year);
}

function nextMonth($date)
{
	$this->JDtoYMD($date, $year, $month, $day);
	if (++$month > 12)
		{
		$month = 1;
		++$year;
		}
	return GregorianToJD($month, $day, $year);
}

function isHoliday($date)
{
	$this->JDtoYMD($date, $year, $month, $day);
	// if ($month == 7 && $day == 4)
		// return "Independence Day";
	if ($month == 1 && $day == 1)
		return "JOUR DE L'AN";
	if ($month == 5 && $day == 1)
		return "FETE DU TRAVAIL";
	if ($month == 7 && $day == 5)
		return "l'INDEPENDANCE";
	if ($month == 11 && $day == 1)
		return "REVOLUTION";
	if ($month == 3 && $day == 8)
		return "JRN FEMME";
	
	
	if ($month == 10 && $day == 25)
		return "JRN NATIONALE";
	if ($month == 3 && $day == 30)
		return "JRN MAGREBINE";
	if ($month == 6 && $day == 14)
		return "JRN MONDIALE";
	
	
	
	// if ($month == 12 && $day == 25)
		// return "Christmas";
	// if ($month == 11)
		// {
		// $dow = gmdate("w", jdtounix($date));
		// if ($day == 11 && $dow > 0 && $dow < 6) // does the eleventh fall on a weekday?
			// return "Veteran's Day";
		// if ($dow == 1 && ($day == 12 || $day == 13))
			// return "Veteran's Day";
		// }
	// if ($this->isWeekHoliday($date, 4, 4, 11)) // thursday of the fourth week of November
		// return "Thanksgiving";
	// if ($this->isWeekHoliday($date, 1, 3, 1))
		// return "MLK, Jr. Day";
	// if ($this->isWeekHoliday($date, 1, 3, 2))
		// return "President's Day";
	// if ($this->isWeekHoliday($date, 2, 1, 11))
		// return "Election Day";
	// if ($this->isWeekHoliday($date, 1, 1, 9))
		// return "Labor Day";
	// if ($this->isWeekHoliday($date, 1, 2, 10))
		// return "Columbus Day";
	// if ($this->isWeekHoliday($date, 1, 99, 5))
		// return "Memorial Day";
	// if ($this->isWeekHoliday($date, 0, 2, 5))
		// return "Mother's Day";
	// if ($this->isWeekHoliday($date, 0, 3, 6))
		// return "Father's Day";
	return "";
}

function isWeekHoliday($date, $dayOfWeek, $weekOfMonth, $monthOfDate)
{
	$this->JDtoYMD($date, $year, $month, $day);
	if ($monthOfDate != $month)
		return 0;
	$jd = jdtounix($date);
	$dow = gmdate("w", $jd);
	if ($dow != $dayOfWeek)
	return 0;
	$daysInMonth = gmdate("t", $jd);
	if ($weekOfMonth > 5 && $day + 6 > $daysInMonth)
		return 1;
 	if ($day > ($weekOfMonth - 1) * 7 && $day <= ($weekOfMonth * 7))
		return 1;
	return 0;
}

function tinyCalendar($date, $square)
{
	$this->JDtoYMD($date, $year, $month, $day);
	// print numbers in boxes
	$wd=gmdate("w",jdtounix($date));
	$cur = $date - $wd;
	$this->SetFont("Helvetica", "B", 10);
	$monthStr = gmdate ("F", jdtounix($date))." $year";
	$this->JDtoYMD($date, $year, $month, $day);
	// save local copy of coordinates for future reference
	$x = $this->x;
	$y = $this->y;
	$this->Cell(7*$square, $square, $monthStr, 0, 0, "C");
	$y+=$square;
	$this->SetXY($x, $y);
	$this->SetFontSize(8);
	for ($i = 1; $i <= 7; ++$i)
		{
		$day = strtoupper(gmdate("l", jdtounix($this->MDYtoJD(2,$i,2009))));
		$this->Cell($square, $square, $day[0], 0,0,"C");
		}
	$y+=$square;
	$this->SetXY($x, $y);
	for ($k=0;$k<6;$k++)
		{
		for ($i=0;$i<7;$i++ )
			{
			$this->JDtoYMD($cur++, $curYear, $curMonth, $curDay);
			if ($curMonth != $month)
				$curDay = " ";
			$this->Cell($square, $square, $curDay, 0, 0, "R");
			}
		$y+=$square;
		$this->SetXY($x, $y);
		}
}

function printDay($date)
{
// nothing to do, can be overriden
}

function printHoliday($date)
{
	$x = $this->x;
	$y = $this->y;
	$height = 5.5;
	if ($this->squareHeight < 50)
		$height = 4;
	$widthPercent = .92;
	$fontSize = 11;
	$holiday = $this->isHoliday($date);
	if (strlen($holiday))
		{
		$wd = gmdate("w",jdtounix($date));
		if ($wd != 0 && $wd != 6)
			$this->Cell($this->squareWidth, $this->squareHeight, "", 0, 0, "", true);
		$this->SetXY($x + $this->squareWidth * (1-$widthPercent)/2,$y + $this->squareHeight * 0.83);
		$this->SetFont("Helvetica", "B", $fontSize);
		$this->Cell($this->squareWidth * $widthPercent,$height,$holiday, 0, 0, "C");
		}
}

function printMonth($date)
{
	$this->date = $date;
	$this->JDtoYMD($date, $year, $month, $day);
	$this->AddPage();
	// compute size base on current settings
	$width = $this->w - $this->lMargin - $this->rMargin;
	$height = $this->h - $this->tMargin - $this->bMargin;
	// print prev and next calendars
	$this->SetTextColor(0,0,255);//text bleu
	$this->setXY($this->lMargin,$this->tMargin);                                   $this->tinyCalendar($this->lastMonth($date), $this->tinySquareSize);
	$this->setXY($this->lMargin+$width - $this->tinySquareSize * 7,$this->tMargin);$this->tinyCalendar($this->nextMonth($date), $this->tinySquareSize);
	$this->SetTextColor(0,0,0);//text noire
	//header month// print header
	$firstLine = $this->tinySquareSize * 8 + $this->tMargin;
	$monthStr = strtoupper(gmdate ("F Y", jdtounix($date)));
	$this->SetXY($this->lMargin,$this->tMargin);
	$this->SetFont("Times", "B", $this->headerFontSize);
	$this->SetTextColor(255,0,0);//text rouge
	$this->Cell($width, $firstLine, $monthStr, 0,0, "C");
	$this->SetTextColor(0,0,0);//text noire
	// compute number of weeks in month.
	$wd=gmdate("w",jdtounix($date));
	$start = $date - $wd;
	$numDays = $this->nextMonth($date) - $start;
	$numWeeks = 0;
	while ($numDays > 0)
		{
		$numDays -= 7;
		++$numWeeks;
		}
		
	// compute horizontal lines
	$this->squareHeight = ($height - 6 - $firstLine) / $numWeeks;
	$horizontalLines = array($firstLine,6);
	for ($i = 0; $i < $numWeeks; ++$i)
		{
		$horizontalLines[$i + 2] = $this->squareHeight;// compute vertical lines
		}
	$this->squareWidth = $width / 7;
	$verticalLines = array($this->lMargin, $this->squareWidth, $this->squareWidth, $this->squareWidth, $this->squareWidth, $this->squareWidth, $this->squareWidth, $this->squareWidth);
	
	// print days of week
	$x = 0;
	$this->SetFont("Times", "B", 12);
	for ($i = 1; $i <= 7; ++$i)
		{
		$x += $verticalLines[$i-1];
		$day = gmdate("l", jdtounix($this->MDYtoJD(2,$i,2009)));
		$this->SetTextColor(255,0,0);//text noire
		$this->SetFillColor(0,255,0);
		$this->SetXY($x,$firstLine);$this->Cell($verticalLines[$i],6,$day,0,0,1,"l");
		$this->SetFillColor(230);
		$this->SetTextColor(0,0,0);//text noire
		}
	
	// print numbers in boxes
	$wd=gmdate("w",jdtounix($date));
	$cur = $date - $wd;
	$this->SetFont("Times", "B", 20);
	$x = 0;
	$y = $horizontalLines[0];
	for ($k=0;$k<$numWeeks;$k++)
		{
		$y += $horizontalLines[$k+1];
		for ($i=0;$i<7;$i++ )
			{
			$this->JDtoYMD($cur, $curYear, $curMonth, $curDay);
			$x += $verticalLines[$i];
			$this->squareWidth = $verticalLines[$i+1];
			if ($curMonth == $month)
				{
				$this->SetFillColor(0,220,0);
				$this->setXY($x, $y);$this->printHoliday($cur);
				$this->SetFillColor(0,0,0);
				$this->setXY($x, $y);$this->printDay($cur);
				$this->SetFont("Times", "B", 20);
				$this->SetXY($x,$y+1);$this->Cell(5,5,$curDay,0);
				}
			++$cur;
			}
		$x = 0;
		}
	// print  lines
	// print horizontal lines
	$ly = 0;$ry = 0;
	foreach ($horizontalLines as $key => $value)
		{
		$ly += $value;$ry += $value;
		$this->Line($this->lMargin,$ly,$this->lMargin+$width,$ry);
		}
	// print vertical lines
	$lx = 0;$rx = 0;
	foreach ($verticalLines as $key => $value)
		{
		$lx += $value;$rx += $value;
		$this->Line($lx,$firstLine,$rx,$firstLine + 6 + $this->squareHeight * $numWeeks);
		}
		
	
}

} 

class MyCalendar extends PDF_USA_Calendar
{

function printDay($date)
{
	// add logic here to customize a day
	$this->JDtoYMD($date,$year,$month,$day);
	if ($month == 5 && $day == 3)
		{
		$this->SetFont("Arial", "B", 10);$this->SetTextColor(255,0,0);//text ROUGE
		$this->SetXY($this->x, $this->y + $this->squareHeight / 2);$this->Cell($this->squareWidth,5,"Happy Birthday!", 0,0, "C");$this->SetTextColor(0,0,0);//text noire
		}
}

function isHoliday($date)
{
	// insert your favorite holidays here
	// $this->JDtoYMD($date, $year, $month, $day);
	// if ($date == easter_days($year) + $this->MDYtoJD(3,21,$year))
		// {
		// $noSchool = false;
		// return "Easter";
		// }
	// if ($date == easter_days($year) + $this->MDYtoJD(3,21,$year) - 2)
		// {
		// $noSchool = false;
		// return "Good Friday";
		// }
	// $jewishDate = explode("/", jdtojewish(gregoriantojd($month,$day,$year)));
	// $month = $jewishDate[0];
	// $day = $jewishDate[1];
	// if ($month == 1 && $day == 1)
		// return "Rosh Hashanah";
	// if ($month == 1 && $day == 2)
		// return "Rosh Hashanah";
	// if ($month == 1 && $day == 10)
		// return "Yom Kippur";
	// if ($month == 3 && $day == 25)
		// return "Chanukkah";
	// if ($month == 8 && $day == 15)
		// return "Passover";
	
	return parent::isHoliday($date);// call the base class for USA holidays
}

var $extgstates = array();

    // alpha: real value from 0 (transparent) to 1 (opaque)
    // bm:    blend mode, one of the following:
    //          Normal, Multiply, Screen, Overlay, Darken, Lighten, ColorDodge, ColorBurn,
    //          HardLight, SoftLight, Difference, Exclusion, Hue, Saturation, Color, Luminosity
    function SetAlpha($alpha, $bm='Normal')
    {
        // set alpha for stroking (CA) and non-stroking (ca) operations
        $gs = $this->AddExtGState(array('ca'=>$alpha, 'CA'=>$alpha, 'BM'=>'/'.$bm));
        $this->SetExtGState($gs);
    }

    function AddExtGState($parms)
    {
        $n = count($this->extgstates)+1;
        $this->extgstates[$n]['parms'] = $parms;
        return $n;
    }

    function SetExtGState($gs)
    {
        $this->_out(sprintf('/GS%d gs', $gs));
    }

    function _enddoc()
    {
        if(!empty($this->extgstates) && $this->PDFVersion<'1.4')
            $this->PDFVersion='1.4';
        parent::_enddoc();
    }

    function _putextgstates()
    {
        for ($i = 1; $i <= count($this->extgstates); $i++)
        {
            $this->_newobj();
            $this->extgstates[$i]['n'] = $this->n;
            $this->_out('<</Type /ExtGState');
            $parms = $this->extgstates[$i]['parms'];
            $this->_out(sprintf('/ca %.3F', $parms['ca']));
            $this->_out(sprintf('/CA %.3F', $parms['CA']));
            $this->_out('/BM '.$parms['BM']);
            $this->_out('>>');
            $this->_out('endobj');
        }
    }

    function _putresourcedict()
    {
        parent::_putresourcedict();
        $this->_out('/ExtGState <<');
        foreach($this->extgstates as $k=>$extgstate)
            $this->_out('/GS'.$k.' '.$extgstate['n'].' 0 R');
        $this->_out('>>');
    }

    function _putresources()
    {
        $this->_putextgstates();
        parent::_putresources();
    }




} 


$pdf = new MyCalendar("L", "Letter");
$pdf->SetMargins(7,7);
$pdf->SetAutoPageBreak(false, 0);
$greyValue = 190;
$pdf->SetFillColor($greyValue,$greyValue,$greyValue);
$year = gmdate("Y");
for ($month = 1; $month <= 12; ++$month)
	{
	$date = $pdf->MDYtoJD($month, 1, $year);
	$pdf->printMonth($date);
	$pdf->Image('../public/IMAGES/photos/logoao.gif',220,0,15,15,0);
	$pdf->Image('../public/IMAGES/photos/logoao.gif',45,0,15,15,0);
	$pdf->SetFont("Arial", "B", 10);
	$pdf->SetXY(50+40,2);$pdf->Cell(100,5,"ETABLISSEMENT PUBLIQUE HOSPITALIER AIN OUSSERA", 0,1, "C");
	$pdf->SetXY(50+55,7);$pdf->Cell(100,5,"POSTE DE TRANSFUSION SANGUINE", 0,1, "L");
	$pdf->SetXY(50+75,12);$pdf->Cell(100,5,"Dr Redha TIBA", 0,1, "L");
	
	// $pdf->SetXY(50,5);$pdf->Cell(20,5,"AGENCE REGIONALE DE :", 0,0, "C");
	// $pdf->SetXY(50,10);$pdf->Cell(20,5,"AGENCE REGIONALE DE :", 0,0, "C");
	
	

	

	// passe en mode semi-transparent
	$pdf->SetAlpha(0.1);
	$pdf->Image('../public/IMAGES/photos/logoao.gif',15,45,265,163);
	

	

	
	
	
	
	
	
	
	
	
	}
	

	
	
	
	
	
	
	
	
	
$pdf->Output();