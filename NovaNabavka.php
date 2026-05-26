<?php
session_start();

$korisnik = isset($_SESSION["korisnik"]) ? $_SESSION["korisnik"] : null;

if (!isset($korisnik)) {
    header('Location:index.php');
    exit();
}

require "klase/BaznaKonekcija.php";

$KonekcijaObject = new Konekcija("klase/BaznaParametriKonekcije.xml");
$KonekcijaObject->connect();

$konekcija = $KonekcijaObject->konekcijaDB;
$baza = $KonekcijaObject->KompletanNazivBazePodataka;

$upitKnjige = "SELECT ISBN, Naziv, Cena FROM `$baza`.`knjiga` ORDER BY Naziv ASC";
$rezultatKnjige = mysqli_query($konekcija, $upitKnjige);
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="sr-RS" xml:lang="sr-RS">
<head>
<meta charset="UTF-8">
<title>Нова набавка</title>
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

<img src="images/sredinagore.jpg" width="100%" height="3" alt="" class="flt1 rp_topcornn" /> 

<table style="width:100%; padding:0" align="center" cellspacing="0" cellpadding="0" border="0" bgcolor="#D8E7F4">
<tr>
<td style="width:5%;"></td>

<td align="left">
<br/>

<table style="width:100%;" bgcolor="#D8E7F4" align="center" cellspacing="0" cellpadding="0" border="0">

<tr>
<td style="width:3%;"></td>
<td align="left">
<b><font face="Trebuchet MS" color="black" size="3px">НОВА НАБАВКА КЊИГА</font></b><br/>
</td>
<td style="width:3%;"></td>
</tr>

<tr>
<td style="width:3%;"></td>
<td align="center"><font color="#D8E7F4" size="1px">.</font></td>
<td style="width:3%;"></td>
</tr>

<tr>
<td style="width:3%;"></td>
<td align="center">

<table style="width:95%;" bgcolor="#D8E7F4" align="center" cellspacing="0" cellpadding="0" border="0">
<form action="nabavkaSnimi.php" method="POST">

<tr>
<td align="right" valign="bottom">
<b><font face="Trebuchet MS" color="black" size="2px">Датум набавке&nbsp;&nbsp;</font></b>
</td>
<td align="left" valign="bottom">
<input type="date" name="datumNabavke" required>
</td>
</tr>

<tr>
<td align="right" valign="bottom"><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
<td></td>
</tr>

<tr>
<td align="right" valign="bottom">
<b><font face="Trebuchet MS" color="black" size="2px">Добављач&nbsp;&nbsp;</font></b>
</td>
<td align="left" valign="bottom">
<select name="dobavljac" required>
    <option value="">изаберите добављача...</option>
    <option value="Laguna">Laguna</option>
    <option value="Delfi knjižare">Delfi knjižare</option>
    <option value="Vulkan izdavaštvo">Vulkan izdavaštvo</option>
    <option value="Klett">Klett</option>
    <option value="Zavod za udžbenike">Zavod za udžbenike</option>
    <option value="Službeni glasnik">Službeni glasnik</option>
</select>
</td>
</tr>

<tr>
<td align="right" valign="bottom"><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
<td></td>
</tr>

<tr>
<td align="right" valign="bottom">
<b><font face="Trebuchet MS" color="black" size="2px">Напомена&nbsp;&nbsp;</font></b>
</td>
<td align="left" valign="bottom">
<input type="text" name="napomena" size="50" value="Redovna nabavka knjiga">
</td>
</tr>

<tr>
<td align="right" valign="bottom"><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
<td></td>
</tr>

<tr>
<td align="right" valign="bottom">
<b><font face="Trebuchet MS" color="black" size="2px">Књига&nbsp;&nbsp;</font></b>
</td>
<td align="left" valign="bottom">
<select name="isbn" id="knjigaSelect" required>
<option value="">изаберите књигу...</option>
<?php
while ($knjiga = mysqli_fetch_assoc($rezultatKnjige)) {

    $ISBN = $knjiga['ISBN'];
    $Naziv = $knjiga['Naziv'];
    $Cena = $knjiga['Cena'];

    echo "<option 
            value='".$ISBN."' 
            data-cena='".$Cena."'>
            ".$Naziv." - ".$ISBN."
          </option>";
}
?>
</select>
</td>
</tr>

<tr>
<td align="right" valign="bottom"><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
<td></td>
</tr>

<tr>
<td align="right" valign="bottom">
<b><font face="Trebuchet MS" color="black" size="2px">Количина&nbsp;&nbsp;</font></b>
</td>
<td align="left" valign="bottom">
<input type="number" name="kolicina" min="1" required>
</td>
</tr>

<tr>
<td align="right" valign="bottom"><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
<td></td>
</tr>

<tr>
<td align="right" valign="bottom">
<b><font face="Trebuchet MS" color="black" size="2px">Цена&nbsp;&nbsp;</font></b>
</td>
<td align="left" valign="bottom">
<input type="number" id="cenaInput" name="cena" min="1" step="0.01" required>
</td>
</tr>

<tr>
<td align="right" valign="bottom"><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
<td><font face="Trebuchet MS" color="#D8E7F4" size="2px">.</font><br/></td>
</tr>

<tr>
<td></td>
<td>
<input type="submit" value="САЧУВАЈ НАБАВКУ">
</td>
</tr>

</form>
</table>

</td>
<td style="width:3%;"></td>
</tr>

<tr>
<td style="width:3%;"></td>
<td align="center"><font color="#D8E7F4" size="1px">.</font></td>
<td style="width:3%;"></td>
</tr>

</table>
</td>

<td style="width:5%;"></td>
</tr>
</table>

<img src="images/sredinadole.jpg" width="100%" height="5" alt="" class="flt1" />

</td>

<td style="width:1%;"></td>
</tr>
</table>

</td>

<td style="width:10%;"></td>
</tr>

<?php include 'delovi/footer.php';?>

</table>

<script>

document.getElementById("knjigaSelect").addEventListener("change", function() {

    let selectedOption = this.options[this.selectedIndex];

    let cena = selectedOption.getAttribute("data-cena");

    document.getElementById("cenaInput").value = cena;
});

</script>

</body>
</html>

<?php
$KonekcijaObject->disconnect();
?>