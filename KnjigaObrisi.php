<?php
session_start();

$korisnik = isset($_SESSION["korisnik"]) ? $_SESSION["korisnik"] : null;

if (!isset($korisnik)) {
    header('Location:index.php');
    exit();
}

$IdZaBrisanje = $_POST['isbn'];

require "klase/BaznaKonekcija.php";
require "klase/BaznaTabela.php";
require "klase/BaznaTransakcija.php";
require "klase/DBKnjiga.php";
require "klase/DBZanr.php";

$KonekcijaObject = new Konekcija('klase/BaznaParametriKonekcije.xml');
$KonekcijaObject->connect();

$UtvrdjenaGreska = "";

if ($KonekcijaObject->konekcijaDB) {    

    $TransakcijaObject = new Transakcija($KonekcijaObject);
    $TransakcijaObject->ZapocniTransakciju();

    $KnjigaObject = new DBKnjiga($KonekcijaObject, 'knjiga');

    $OznakaZanra = $KnjigaObject->DajOznakuZanraKnjige($IdZaBrisanje);
    $greska1 = $KnjigaObject->ObrisiKnjigu($IdZaBrisanje);

    $ZanrObject = new DBZanr($KonekcijaObject, 'zanr');
    $greska2 = $ZanrObject->DekrementirajBrojKnjiga($OznakaZanra);

    $UtvrdjenaGreska = $greska1 . $greska2;

    $TransakcijaObject->ZavrsiTransakciju($UtvrdjenaGreska);
}

$KonekcijaObject->disconnect();

if ($UtvrdjenaGreska) {
    echo "Грешка: $UtvrdjenaGreska";
    echo "<br><br>";
    echo "<a href=\"KnjigeLista.php\">ПОВРАТАК</a>";        
} else {
    header('Location:KnjigeLista.php');
    exit();
}
?>