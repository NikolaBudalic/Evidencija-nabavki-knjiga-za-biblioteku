<?php
session_start();

$korisnik = isset($_SESSION["korisnik"]) ? $_SESSION["korisnik"] : null;

if (!isset($korisnik)) {
    header('Location:index.php');
    exit();
}

$ISBN = trim($_POST['isbn']);
$StariISBN = trim($_POST['StariISBN']);
$Naziv = trim($_POST['naziv']);
$Autor = trim($_POST['autor']);
$OznakaZanra = trim($_POST['oznakaZanra']);

if (!preg_match('/^[0-9]{13}$/', $ISBN)) {
    die("Грешка: ISBN мора имати тачно 13 цифара.<br><br><a href=\"KnjigeLista.php\">ПОВРАТАК</a>");
}

if ($Naziv == "" || strlen($Naziv) > 100) {
    die("Грешка: Назив књиге је обавезан и не сме бити дужи од 100 карактера.<br><br><a href=\"KnjigeLista.php\">ПОВРАТАК</a>");
}

if ($Autor == "" || strlen($Autor) > 100) {
    die("Грешка: Аутор је обавезан и не сме бити дужи од 100 карактера.<br><br><a href=\"KnjigeLista.php\">ПОВРАТАК</a>");
}

if ($OznakaZanra == "") {
    die("Грешка: Морате изабрати жанр.<br><br><a href=\"KnjigeLista.php\">ПОВРАТАК</a>");
}

$NazivFajlaSlike = "";

if (isset($_FILES["nazivFajlaSlike"]) && $_FILES["nazivFajlaSlike"]["error"] == 0) {
    $name = basename($_FILES["nazivFajlaSlike"]["name"]);
    $tmp_name = $_FILES["nazivFajlaSlike"]["tmp_name"];

    if (!empty($name)) {
        $location = 'SlikeKnjiga/';
        move_uploaded_file($tmp_name, $location.$name);
        $NazivFajlaSlike = $name;
    }
}

$StariNazivFajlaSlike = $_POST['StariNazivFajlaSlike'];

if ($NazivFajlaSlike == "") {
    $NazivFajlaSlike = $StariNazivFajlaSlike;
}

require "klase/BaznaKonekcija.php";
require "klase/BaznaTabela.php";
require "klase/DBKnjiga.php";

$KonekcijaObject = new Konekcija('klase/BaznaParametriKonekcije.xml');
$KonekcijaObject->connect();

if ($KonekcijaObject->konekcijaDB) {

    $konekcija = $KonekcijaObject->konekcijaDB;
    $baza = $KonekcijaObject->KompletanNazivBazePodataka;

    $ISBN = mysqli_real_escape_string($konekcija, $ISBN);
    $StariISBN = mysqli_real_escape_string($konekcija, $StariISBN);

    if ($ISBN != $StariISBN) {
        $provera = mysqli_query($konekcija, "SELECT ISBN FROM `$baza`.`knjiga` WHERE ISBN='$ISBN'");

        if (mysqli_num_rows($provera) > 0) {
            die("Грешка: Књига са тим ISBN бројем већ постоји.<br><br><a href=\"KnjigeLista.php\">ПОВРАТАК</a>");
        }
    }

    $KnjigaObject = new DBKnjiga($KonekcijaObject, 'knjiga');
    $greska = $KnjigaObject->IzmeniKnjigu(
        $StariISBN,
        $ISBN,
        $Naziv,
        $Autor,
        $OznakaZanra,
        $NazivFajlaSlike
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