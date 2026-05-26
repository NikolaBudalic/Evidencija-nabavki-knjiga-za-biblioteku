<?php
session_start();

$korisnik = isset($_SESSION["korisnik"]) ? $_SESSION["korisnik"] : null;

if (!isset($korisnik)) {
    header('Location:index.php');
    exit();
}

$StariISBNZaIzmenu = $_POST['BrojIndeksa'];

require "klase/BaznaKonekcija.php";
require "klase/BaznaTabela.php";
require "klase/DBZanr.php";
require "klase/DBKnjiga.php";

$KonekcijaObject = new Konekcija("klase/BaznaParametriKonekcije.xml");
$KonekcijaObject->connect();

$SmerObject = new DBSmer($KonekcijaObject, "zanr");
$SmerObject->UcitajKolekcijuSvihSmerova();
$KolekcijaZapisa = $SmerObject->Kolekcija;
$UkupanBrojZapisa = $SmerObject->BrojZapisa;

$StudentObject = new DBStudent($KonekcijaObject, 'knjiga');
$StudentObject->UcitajStudentaPoBrojuIndeksa($StariISBNZaIzmenu);

$KolekcijaZapisaStudenata = $StudentObject->Kolekcija;
$UkupanBrojZapisaStudenata = $StudentObject->BrojZapisa;

if ($UkupanBrojZapisaStudenata > 0) {
    $row = 0;
    $StariBrojIndeksa = $StudentObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisaStudenata, $row, 0);
    $StaroPrezime = $StudentObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisaStudenata, $row, 1);
    $StaroIme = $StudentObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisaStudenata, $row, 2);
    $StaraOznakaSmera = $StudentObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisaStudenata, $row, 3);
    $StariNazivFajlaFotografije = $StudentObject->DajVrednostPoRednomBrojuZapisaPoRBPolja($KolekcijaZapisaStudenata, $row, 4);
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="sr-RS" xml:lang="sr-RS">
<head>
<meta charset="UTF-8">
<title>Измена књиге</title>
<?php include 'css/stil.php';?>
</head>

<body>

<table class="no-spacing" style="width:100%; padding:0; border-spacing:0;" align="center" cellspacing="0" cellpadding="0" border="0">

<?php include 'delovi/zaglavljewelcome.php';?>

<tr style="padding:0px;">
<td style="width:10%;"></td>

<td align="center" valign="middle" style="width:80%; padding:0"> 

<table style="width:100%; padding:0" align="center" cellspacing="0" cellpadding="0" border="0" bgcolor="#003366">
<tr>
<td style="width:1%;"></td>

<td style="width:15%;padding:0" valign="top">
<?php include 'delovi/menilevoadmin.php';?>
</td>

<td style="width:1%;"></td>

<td style="width:80%;padding:0" valign="top">
<?php include 'delovi/desnoKnjigaIzmeniForm.php';?>
</td>

<td style="width:1%;"></td>
</tr>
</table>

</td>

<td style="width:10%;"></td>
</tr>

<tr style="padding:0px;">
<td style="width:10%;"></td>
<td align="center" valign="middle"></td>
<td style="width:10%;"></td>
</tr>

<?php include 'delovi/footer.php';?>

</table>

</body>
</html>