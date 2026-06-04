<?php

require_once "KnjigaEntitet.php";

class StavkaNabavkeEntitet
{
    public $IDStavke;
    public $IDNabavke;
    public $Knjiga;
    public $Kolicina;
    public $Cena;

    public function __construct($Knjiga, $Kolicina, $Cena, $IDStavke = null, $IDNabavke = null)
    {
        $this->IDStavke = $IDStavke;
        $this->IDNabavke = $IDNabavke;
        $this->Knjiga = $Knjiga;
        $this->Kolicina = $Kolicina;
        $this->Cena = $Cena;
    }

    public function DajUkupno()
    {
        return $this->Kolicina * $this->Cena;
    }
}

?>