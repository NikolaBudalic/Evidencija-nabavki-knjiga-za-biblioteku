<?php
session_start();

$korisnik = isset($_SESSION["korisnik"]) ? $_SESSION["korisnik"] : null;

if (!isset($korisnik)) {
    header('Location:index.php');
    exit();
}

$brojIndeksa = trim($_POST['brojIndeksa']);
$StariBrojIndeksa = trim($_POST['StariBrojIndeksa']);
$prezime = trim($_POST['prezime']);
$ime = trim($_POST['ime']);
$oznakaSmera = trim($_POST['oznakaSmera']);

if (!preg_match('/^[0-9]{13}$/', $brojIndeksa)) {
    die("Грешка: ISBN мора имати тачно 13 цифара.<br><br><a href=\"KnjigeLista.php\">ПОВРАТАК</a>");
}

if ($prezime == "" || strlen($prezime) > 100) {
    die("Грешка: Назив књиге је обавезан и не сме бити дужи од 100 карактера.<br><br><a href=\"KnjigeLista.php\">ПОВРАТАК</a>");
}

if ($ime == "" || strlen($ime) > 100) {
    die("Грешка: Аутор је обавезан и не сме бити дужи од 100 карактера.<br><br><a href=\"KnjigeLista.php\">ПОВРАТАК</a>");
}

if ($oznakaSmera == "") {
    die("Грешка: Морате изабрати жанр.<br><br><a href=\"KnjigeLista.php\">ПОВРАТАК</a>");
}

$nazivFajlaFotografije = "";

if (isset($_FILES["nazivFajlaFotografije"]) && $_FILES["nazivFajlaFotografije"]["error"] == 0) {
    $name = basename($_FILES["nazivFajlaFotografije"]["name"]);
    $tmp_name = $_FILES["nazivFajlaFotografije"]["tmp_name"];

    if (!empty($name)) {
        $location = 'SlikeStudenata/';
        move_uploaded_file($tmp_name, $location.$name);
        $nazivFajlaFotografije = $name;
    }
}

$StariNazivFajlaFotografije = $_POST['StariNazivFajlaFotografije'];

if ($nazivFajlaFotografije == "") {
    $nazivFajlaFotografije = $StariNazivFajlaFotografije;
}

require "klase/BaznaKonekcija.php";
require "klase/BaznaTabela.php";
require "klase/DBKnjiga.php";

$KonekcijaObject = new Konekcija('klase/BaznaParametriKonekcije.xml');
$KonekcijaObject->connect();

if ($KonekcijaObject->konekcijaDB) {

    $konekcija = $KonekcijaObject->konekcijaDB;
    $baza = $KonekcijaObject->KompletanNazivBazePodataka;

    $brojIndeksa = mysqli_real_escape_string($konekcija, $brojIndeksa);
    $StariBrojIndeksa = mysqli_real_escape_string($konekcija, $StariBrojIndeksa);

    if ($brojIndeksa != $StariBrojIndeksa) {
        $provera = mysqli_query($konekcija, "SELECT ISBN FROM `$baza`.`knjiga` WHERE ISBN='$brojIndeksa'");

        if (mysqli_num_rows($provera) > 0) {
            die("Грешка: Књига са тим ISBN бројем већ постоји.<br><br><a href=\"KnjigeLista.php\">ПОВРАТАК</a>");
        }
    }

    $StudentObject = new DBStudent($KonekcijaObject, 'knjiga');
    $greska = $StudentObject->IzmeniStudenta(
        $StariBrojIndeksa,
        $brojIndeksa,
        $prezime,
        $ime,
        $oznakaSmera,
        $nazivFajlaFotografije
    );

} else {
    echo "Није успостављена конекција ка бази података!";
}

$KonekcijaObject->disconnect();

if (isset($greska) && !empty($greska)) {
    echo "ГРЕШКА:";
    echo "<br><br>"; 
    echo $greska;
    echo "<br><br>";
    echo "<a href=\"KnjigeLista.php\">ПОВРАТАК</a>";    
} else {
    header('Location:KnjigeLista.php');
    exit();
}
?>