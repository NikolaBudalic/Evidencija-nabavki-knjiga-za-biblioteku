<?php
class Upis extends Tabela 
{
// ATRIBUTI
private $bazapodataka;
private $UspehKonekcijeNaDBMS;
//
// METODE

// konstruktor nasledjuje od bazne klase Tabela

public function DaLiImaMestaZaUpis($OznakaSmeraParametar)
{
// incijalizacija promenljive za izlaz
$odgovor="NE";

// izdvajanje ogranicenja iz XML
$xml=simplexml_load_file("klase/".$OznakaSmeraParametar.".xml") or die("Nije uspesno ucitavanje fajla sa ogranicenjem!");
$maxBrojStudenata=$xml->MaxBrStudenata;

// izdvajanje koliko trenutno imamo upisanih za taj smer u bazi podataka
$NazivTrazenogPolja="count(`BrojIndeksa`)";
$KriterijumFiltriranja="`OznakaSmera`='".$OznakaSmeraParametar."'";
$KriterijumSortiranja="`BrojIndeksa`"; // nema potrebe da se sortira, ali ne menjamo baznu klasu
$trenutanBrojStudenata=$this->DajVrednostJednogPoljaPrvogZapisa($NazivTrazenogPolja, $KriterijumFiltriranja, $KriterijumSortiranja); 

// uporedjivanje max i trenutno i odlucivanje
if ($trenutanBrojStudenata<$maxBrojStudenata)
{
$odgovor="DA";
}
else
{
$odgovor="NE";
}

//vracanje odgovora
return $odgovor;
}


}
?>