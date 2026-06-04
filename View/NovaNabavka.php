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
<b><font face="Trebuchet MS" color="black" size="3px">НОВА НАБАВКА КЊИГА</font></b><br/><br/>
</td>
<td style="width:3%;"></td>
</tr>

<tr>
<td style="width:3%;"></td>
<td align="center">

<form action="controller/nabavkaSnimi.php" method="POST" onsubmit="return proveriNabavku();">

<table style="width:90%;" bgcolor="#B7F0F7" align="center" cellspacing="0" cellpadding="5" border="1">
<tr>
<td colspan="2" align="left">
<b>ПОДАЦИ О НАБАВЦИ</b>
</td>
</tr>

<tr>
<td align="right"><b>Датум набавке&nbsp;&nbsp;</b></td>
<td align="left"><input type="date" name="datumNabavke" id="datumNabavke" required></td>
</tr>

<tr>
<td align="right"><b>Добављач&nbsp;&nbsp;</b></td>
<td align="left">
<select name="dobavljac" id="dobavljac" required>
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
<td align="right"><b>Напомена&nbsp;&nbsp;</b></td>
<td align="left">
<input type="text" name="napomena" size="50" maxlength="255" value="Redovna nabavka knjiga">
</td>
</tr>
</table>

<br/>

<table style="width:90%; margin-left:auto; margin-right:auto;" bgcolor="#B7F0F7" align="center" cellspacing="0" cellpadding="5" border="1">

<tr>
<td colspan="2" align="left">
<b>СТАВКА НАБАВКЕ</b>
</td>
</tr>

<tr>
<td align="right" style="width:25%;">
<b>Књига&nbsp;&nbsp;</b>
</td>
<td align="left">
<select name="isbn" id="knjigaSelect" required style="width:380px;">
<option value="">изаберите књигу...</option>
<?php
mysqli_data_seek($rezultatKnjige, 0);

while ($knjiga = mysqli_fetch_assoc($rezultatKnjige)) {
    echo "<option value='".$knjiga['ISBN']."' data-cena='".$knjiga['Cena']."'>
            ".$knjiga['Naziv']." - ".$knjiga['ISBN']."
          </option>";
}
?>
</select>
</td>
</tr>

<tr>
<td align="right">
<b>Количина&nbsp;&nbsp;</b>
</td>
<td align="left">
<input type="number" name="kolicina" id="kolicinaInput" min="1" required style="width:120px;">
</td>
</tr>

<tr>
<td align="right">
<b>Цена&nbsp;&nbsp;</b>
</td>
<td align="left">
<input type="number" id="cenaInput" name="cena" min="1" step="0.01" required style="width:120px;">
</td>
</tr>

<tr>
<td align="right">
<b>Укупно&nbsp;&nbsp;</b>
</td>
<td align="left">
<input type="text" id="ukupnoInput" readonly style="width:120px;">
</td>
</tr>

</table>

<br/>

<table style="width:90%;" align="center">
<tr>
<td align="center">
<br/>
<input type="submit" value="САЧУВАЈ НАБАВКУ">
</td>
</tr>
</table>

</form>

</td>
<td style="width:3%;"></td>
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
    izracunajUkupno();
});

document.getElementById("kolicinaInput").addEventListener("input", izracunajUkupno);
document.getElementById("cenaInput").addEventListener("input", izracunajUkupno);

function izracunajUkupno() {
    let kolicina = parseFloat(document.getElementById("kolicinaInput").value);
    let cena = parseFloat(document.getElementById("cenaInput").value);

    if (!isNaN(kolicina) && !isNaN(cena)) {
        document.getElementById("ukupnoInput").value = (kolicina * cena).toFixed(2);
    } else {
        document.getElementById("ukupnoInput").value = "";
    }
}

function proveriNabavku() {
    let datum = document.getElementById("datumNabavke").value;
    let dobavljac = document.getElementById("dobavljac").value;
    let knjiga = document.getElementById("knjigaSelect").value;
    let kolicina = document.getElementById("kolicinaInput").value;
    let cena = document.getElementById("cenaInput").value;

    if (datum == "" || dobavljac == "" || knjiga == "" || kolicina <= 0 || cena <= 0) {
        alert("Морате исправно попунити све податке о набавци и ставци набавке.");
        return false;
    }

    return true;
}
</script>

</body>
</html>

<?php
$KonekcijaObject->disconnect();
?>