<?php
session_start();

$korisnik = isset($_SESSION["korisnik"]) ? $_SESSION["korisnik"] : null;

if (!isset($korisnik)) {
    header('Location:../index.php');
    exit();
}

// PREUZIMANJE PODATAKA SA FORME
$datumNabavke = trim($_POST['datumNabavke']);
$dobavljac = trim($_POST['dobavljac']);
$napomena = trim($_POST['napomena']);
$isbn = trim($_POST['isbn']);
$kolicina = trim($_POST['kolicina']);
$cena = trim($_POST['cena']);

// SERVER SIDE VALIDACIJE
$dozvoljeniDobavljaci = array(
    "Laguna",
    "Delfi knjižare",
    "Vulkan izdavaštvo",
    "Klett",
    "Zavod za udžbenike",
    "Službeni glasnik"
);

if ($datumNabavke == "") {
    die("Грешка: Датум набавке је обавезан.<br><br><a href='../NovaNabavka.php'>ПОВРАТАК</a>");
}

$datumProvera = DateTime::createFromFormat('Y-m-d', $datumNabavke);
if (!$datumProvera || $datumProvera->format('Y-m-d') !== $datumNabavke) {
    die("Грешка: Датум набавке није исправан.<br><br><a href='../NovaNabavka.php'>ПОВРАТАК</a>");
}

if ($dobavljac == "") {
    die("Грешка: Морате изабрати добављача.<br><br><a href='../NovaNabavka.php'>ПОВРАТАК</a>");
}

if (!in_array($dobavljac, $dozvoljeniDobavljaci)) {
    die("Грешка: Изабрани добављач није у дозвољеном домену вредности.<br><br><a href='../NovaNabavka.php'>ПОВРАТАК</a>");
}

if (strlen($napomena) > 255) {
    die("Грешка: Напомена не сме бити дужа од 255 карактера.<br><br><a href='../NovaNabavka.php'>ПОВРАТАК</a>");
}

if ($isbn == "") {
    die("Грешка: Морате изабрати књигу.<br><br><a href='../NovaNabavka.php'>ПОВРАТАК</a>");
}

if (!preg_match('/^[0-9]{13}$/', $isbn)) {
    die("Грешка: ISBN мора имати тачно 13 цифара.<br><br><a href='../NovaNabavka.php'>ПОВРАТАК</a>");
}

if (!is_numeric($kolicina) || $kolicina <= 0 || $kolicina > 100) {
    die("Грешка: Количина мора бити број у опсегу од 1 до 100.<br><br><a href='../NovaNabavka.php'>ПОВРАТАК</a>");
}

if (!is_numeric($cena) || $cena <= 0 || $cena > 100000) {
    die("Грешка: Цена мора бити број у опсегу од 1 до 100000.<br><br><a href='../NovaNabavka.php'>ПОВРАТАК</a>");
}

// KLASE
require "../klase/BaznaKonekcija.php";
require "../klase/BaznaTabela.php";
require "../klase/BaznaTransakcija.php";

require_once "../klase/KnjigaEntitet.php";
require_once "../klase/StavkaNabavkeEntitet.php";
require_once "../klase/NabavkaEntitet.php";

require "../klase/DBNabavka.php";
require "../klase/DBStavkaNabavke.php";

// KREIRANJE ENTITETA - ASOCIJACIJA I KOMPOZICIJA
$KnjigaEntitet = new KnjigaEntitet($isbn);
$StavkaEntitet = new StavkaNabavkeEntitet($KnjigaEntitet, $kolicina, $cena);

$NabavkaEntitet = new NabavkaEntitet($datumNabavke, $dobavljac, $napomena);
$NabavkaEntitet->DodajStavku($StavkaEntitet);

// KONEKCIJA
$KonekcijaObject = new Konekcija("../klase/BaznaParametriKonekcije.xml");
$KonekcijaObject->connect();

if ($KonekcijaObject->konekcijaDB) {

    $konekcija = $KonekcijaObject->konekcijaDB;

    // zaštita od SQL injection
    $datumNabavke = mysqli_real_escape_string($konekcija, $datumNabavke);
    $dobavljac = mysqli_real_escape_string($konekcija, $dobavljac);
    $napomena = mysqli_real_escape_string($konekcija, $napomena);
    $isbn = mysqli_real_escape_string($konekcija, $isbn);
    $kolicina = mysqli_real_escape_string($konekcija, $kolicina);
    $cena = mysqli_real_escape_string($konekcija, $cena);

    $TransakcijaObject = new Transakcija($KonekcijaObject);
    $TransakcijaObject->ZapocniTransakciju();

    $NabavkaObject = new DBNabavka($KonekcijaObject, "nabavka");
    $StavkaObject = new DBStavkaNabavke($KonekcijaObject, "stavka_nabavke");

    // proverava da li već postoji nabavka sa istim datumom i dobavljačem
    $idNabavke = $NabavkaObject->PronadjiNabavku($datumNabavke, $dobavljac);

    $greska1 = "";
    $greska2 = "";

    if ($idNabavke == null) {
        $greska1 = $NabavkaObject->DodajNabavku($datumNabavke, $dobavljac, $napomena);
        $idNabavke = $NabavkaObject->DajPoslednjiID();
    }

    $greska2 = $StavkaObject->DodajStavkuNabavke($idNabavke, $isbn, $kolicina, $cena);

    $utvrdjenaGreska = $greska1 . $greska2;

    $TransakcijaObject->ZavrsiTransakciju($utvrdjenaGreska);

    if ($utvrdjenaGreska != "") {
        echo "Грешка приликом снимања набавке.";
        echo "<br>";
        echo $utvrdjenaGreska;
        echo "<br><br><a href='../NovaNabavka.php'>ПОВРАТАК</a>";
    } else {
        header("Location:../NabavkeLista.php");
        exit();
    }
} else {
    echo "Није успостављена конекција ка бази података.";
}

$KonekcijaObject->disconnect();
?>