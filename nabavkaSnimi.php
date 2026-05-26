<?php
session_start();

$korisnik = isset($_SESSION["korisnik"]) ? $_SESSION["korisnik"] : null;

if (!isset($korisnik)) {
    header('Location:index.php');
    exit();
}

$datumNabavke = $_POST['datumNabavke'];
$dobavljac = trim($_POST['dobavljac']);
$napomena = trim($_POST['napomena']);
$isbn = $_POST['isbn'];
$kolicina = $_POST['kolicina'];
$cena = $_POST['cena'];

if ($datumNabavke == "" || $dobavljac == "" || $isbn == "" || $kolicina <= 0 || $cena <= 0) {
    die("Грешка: Сва обавезна поља морају бити исправно попуњена.<br><br><a href='NovaNabavka.php'>ПОВРАТАК</a>");
}

require "klase/BaznaKonekcija.php";

$KonekcijaObject = new Konekcija("klase/BaznaParametriKonekcije.xml");
$KonekcijaObject->connect();

if ($KonekcijaObject->konekcijaDB) {

    $konekcija = $KonekcijaObject->konekcijaDB;
    $baza = $KonekcijaObject->KompletanNazivBazePodataka;

    $datumNabavke = mysqli_real_escape_string($konekcija, $datumNabavke);
    $dobavljac = mysqli_real_escape_string($konekcija, $dobavljac);
    $napomena = mysqli_real_escape_string($konekcija, $napomena);
    $isbn = mysqli_real_escape_string($konekcija, $isbn);
    $kolicina = mysqli_real_escape_string($konekcija, $kolicina);
    $cena = mysqli_real_escape_string($konekcija, $cena);

    mysqli_begin_transaction($konekcija);

    $upitProvera = "SELECT IDNabavke 
                    FROM `$baza`.`nabavka`
                    WHERE DatumNabavke = '$datumNabavke'
                    AND Dobavljac = '$dobavljac'
                    LIMIT 1";

    $rezultatProvera = mysqli_query($konekcija, $upitProvera);

    if ($rezultatProvera && mysqli_num_rows($rezultatProvera) > 0) {
        $red = mysqli_fetch_assoc($rezultatProvera);
        $idNabavke = $red['IDNabavke'];
    } else {
        $upitNabavka = "INSERT INTO `$baza`.`nabavka`
        (DatumNabavke, Dobavljac, Napomena)
        VALUES
        ('$datumNabavke', '$dobavljac', '$napomena')";

        $rezultatNabavka = mysqli_query($konekcija, $upitNabavka);

        if (!$rezultatNabavka) {
            mysqli_rollback($konekcija);
            die("Грешка приликом креирања набавке.<br>" . mysqli_error($konekcija));
        }

        $idNabavke = mysqli_insert_id($konekcija);
    }

    $upitStavka = "INSERT INTO `$baza`.`stavka_nabavke`
    (IDNabavke, ISBN, Kolicina, Cena)
    VALUES
    ('$idNabavke', '$isbn', '$kolicina', '$cena')";

    $rezultatStavka = mysqli_query($konekcija, $upitStavka);

    if ($rezultatStavka) {
        mysqli_commit($konekcija);
        header("Location:NabavkeLista.php");
        exit();
    } else {
        mysqli_rollback($konekcija);
        echo "Грешка приликом снимања ставке набавке.";
        echo "<br>";
        echo mysqli_error($konekcija);
        echo "<br><br><a href='NovaNabavka.php'>ПОВРАТАК</a>";
    }
}

$KonekcijaObject->disconnect();
?>