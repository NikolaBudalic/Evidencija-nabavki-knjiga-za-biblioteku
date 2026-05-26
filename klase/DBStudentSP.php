<?php
class DBStudent extends Tabela 
// rad sa stored procedurom za snimanje novog studenta
{
// ATRIBUTI
private $bazapodataka;
private $UspehKonekcijeNaDBMS;
//
public $BrojIndeksa;
public $Prezime;
public $Ime;
public $OznakaSmera;
public $NazivFajlaFotografije;

// METODE

// konstruktor

public function DodajNovogStudenta()
{
	//$SQL = "INSERT INTO `student` (BrojIndeksa, Prezime, Ime, OznakaSmera, NazivFajlaFotografije) VALUES ('$this->BrojIndeksa','$this->Prezime', '$this->Ime', '$this->OznakaSmera', '$this->NazivFajlaFotografije')";
	//$greska=$this->IzvrsiAktivanSQLUpit($SQL);
	
	
		$GreskarezultatPar1 = $this->IzvrsiAktivanSQLUpit ("SET @BrojIndeksaParametar='".$this->BrojIndeksa."'");
		
		$GreskarezultatPar2 = $this->IzvrsiAktivanSQLUpit ("SET @PrezimeParametar='".$this->Prezime."'");
		
		$GreskarezultatPar3 =  $this->IzvrsiAktivanSQLUpit ("SET @ImeParametar='".$this->Ime."'");
		
		$GreskarezultatPar4 = $this->IzvrsiAktivanSQLUpit (  "SET @OznakaSmeraParametar='".$this->OznakaSmera."'");
		
		$GreskarezultatPar5 = $this->IzvrsiAktivanSQLUpit (  "SET @NazivFajlaFotografijeParametar='".$this->NazivFajlaFotografije."'");
		
		$GreskarezultatCall = $this->IzvrsiAktivanSQLUpit ( "CALL `DodajStudenta`(@BrojIndeksaParametar,@PrezimeParametar,@ImeParametar,@OznakaSmeraParametar,@NazivFajlaFotografijeParametar);");
		
	
	$greska=$GreskarezultatPar1.$GreskarezultatPar2.$GreskarezultatPar3.$GreskarezultatPar4.$GreskarezultatPar5.$GreskarezultatCall;
	return $greska;
}


}
?>