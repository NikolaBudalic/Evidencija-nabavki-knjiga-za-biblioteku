<?php
session_start();

$korisnik = isset($_SESSION["korisnik"]) ? $_SESSION["korisnik"] : null;

if (!isset($korisnik)) {
    header('Location:index.php');
    exit();
}

$ISBN = trim($_POST['brojIndeksa']);
$Naziv = trim($_POST['prezime']);
$Autor = trim($_POST['ime']);
$OznakaZanra = trim($_POST['oznakaSmera']);

if (!preg_match('/^[0-9]{13}$/', $ISBN)) {
    die("Грешка: ISBN мора имати тачно 13 цифара.<br><br><a href=\"unos.php\">ПОВРАТАК</a>");
}

if ($Naziv == "" || strlen($Naziv) > 100) {
    die("Грешка: Назив књиге је обавезан и не сме бити дужи од 100 карактера.<br><br><a href=\"unos.php\">ПОВРАТАК</a>");
}

if ($Autor == "" || strlen($Autor) > 100) {
    die("Грешка: Аутор је обавезан и не сме бити дужи од 100 карактера.<br><br><a href=\"unos.php\">ПОВРАТАК</a>");
}

if ($OznakaZanra == "") {
    die("Грешка: Морате изабрати жанр.<br><br><a href=\"unos.php\">ПОВРАТАК</a>");
}

$name = "";

if (isset($_FILES["nazivFajlaFotografije"]) && $_FILES["nazivFajlaFotografije"]["error"] == 0) {
    $name = basename($_FILES["nazivFajlaFotografije"]["name"]);
    $tmp_name = $_FILES["nazivFajlaFotografije"]["tmp_name"];

    if (!empty($name)) {
        $location = 'SlikeStudenata/';
        move_uploaded_file($tmp_name, $location.$name);
    }
}

$NazivFajlaSlike = $name;

require "klase/BaznaKonekcija.php";

$KonekcijaObject = new Konekcija('klase/BaznaParametriKonekcije.xml');
$KonekcijaObject->connect();

if ($KonekcijaObject->konekcijaDB) {

    $konekcija = $KonekcijaObject->konekcijaDB;
    $baza = $KonekcijaObject->KompletanNazivBazePodataka;

    $ISBN = mysqli_real_escape_string($konekcija, $ISBN);
    $Naziv = mysqli_real_escape_string($konekcija, $Naziv);
    $Autor = mysqli_real_escape_string($konekcija, $Autor);
    $OznakaZanra = mysqli_real_escape_string($konekcija, $OznakaZanra);
    $NazivFajlaSlike = mysqli_real_escape_string($konekcija, $NazivFajlaSlike);

    $provera = mysqli_query($konekcija, "SELECT ISBN FROM `$baza`.`knjiga` WHERE ISBN='$ISBN'");

    if (mysqli_num_rows($provera) > 0) {
        die("Грешка: Књига са тим ISBN бројем већ постоји.<br><br><a href=\"unos.php\">ПОВРАТАК</a>");
    }

    mysqli_begin_transaction($konekcija);

    $upit1 = "INSERT INTO `$baza`.`knjiga`
    (`ISBN`, `Naziv`, `Autor`, `OznakaZanra`, `NazivFajlaSlike`)
    VALUES
    ('$ISBN', '$Naziv', '$Autor', '$OznakaZanra', '$NazivFajlaSlike')";

    $rezultat1 = mysqli_query($konekcija, $upit1);

    $upit2 = "UPDATE `$baza`.`zanr`
    SET `UkupanBrojKnjiga` = `UkupanBrojKnjiga` + 1
    WHERE `Oznaka` = '$OznakaZanra'";

    $rezultat2 = mysqli_query($konekcija, $upit2);

    if ($rezultat1 && $rezultat2) {
        mysqli_commit($konekcija);
        header('Location:KnjigeLista.php');
        exit();
    } else {
        mysqli_rollback($konekcija);
        echo "Грешка приликом снимања књиге!";
        echo "<br>";
        echo mysqli_error($konekcija);
        echo "<br><br>";
        echo "<a href=\"KnjigeLista.php\">ПОВРАТАК</a>";
    }
}

$KonekcijaObject->disconnect();
?>