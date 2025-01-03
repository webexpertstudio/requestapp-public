<?php
require('mysql_table.php');

class PDF extends PDF_MySQL_Table
{
function Header()
{
	//Title
	$this->SetFont('Arial','',18);
	$this->Cell(0,6,'PDF From mysql',0,1,'C');
	$this->Ln(10);
	//Ensure table header is output
	parent::Header();
}
}

//Connect to database
$server="localhost";
$user="ldteam_kjorders";
$pass="xbocuD0DPZaf";
$db="ldteam_kjorders";

mysql_connect($server, $user, $pass) or die ("Baza nije dostupna!");
mysql_select_db($db) or die ("Baza nije dostupna!");

$pdf=new PDF();
$pdf->AddPage();
//First table: put all columns automatically
$pdf->Table('SELECT `id`, `dokumid`,`sifra` from stavke order by `id`');
$pdf->AddPage();
//Second table: specify 3 columns
$pdf->AddCol('id',40,'','C');
$pdf->AddCol('dokumid',40,'stavke','C');
$pdf->AddCol('sifra',40,'','C');
$pdf->AddCol('robaid',40,'','C');
$prop=array('HeaderColor'=>array(255,150,100),
			'color1'=>array(210,245,255),
			'color2'=>array(255,255,210),
			'padding'=>2);
$pdf->Table('select dokumid,  sifra, id, robaid from stavke order by id limit 0,10',$prop);

//$pdf->Output("C:\Users\John\Desktop/somename.pdf",'F'); 


$pdf->Output($downloadfilename.".pdf"); 
header('Location: '.$downloadfilename.".pdf");
?>
