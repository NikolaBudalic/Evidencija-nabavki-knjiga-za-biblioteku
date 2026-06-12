<?php

require_once "../klase/BaznaKonekcija.php";
require_once "../model/NabavkaModel.php";
require_once "../model/KnjigaModel.php";

class NabavkeController
{
    private $KonekcijaObject;
    private $konekcija;
    private $baza;
    private $NabavkaModel;
    private $KnjigaModel;

    public function __construct()
    {
        $this->KonekcijaObject = new Konekcija("../klase/BaznaParametriKonekcije.xml");
        $this->KonekcijaObject->connect();

        $this->konekcija = $this->KonekcijaObject->konekcijaDB;
        $this->baza = $this->KonekcijaObject->KompletanNazivBazePodataka;

        $this->NabavkaModel = new NabavkaModel($this->konekcija, $this->baza);
        $this->KnjigaModel = new KnjigaModel($this->konekcija, $this->baza);
    }

    public function DajSveNabavke()
    {
        return $this->NabavkaModel->DajSveNabavke();
    }

    public function DajStavkeNabavke($IDNabavke)
    {
        return $this->NabavkaModel->DajStavkeNabavke($IDNabavke);
    }

    public function DajKnjigeZaNabavku()
    {
        return $this->KnjigaModel->DajSveKnjigeZaNabavku();
    }

    public function ZatvoriKonekciju()
    {
        $this->KonekcijaObject->disconnect();
    }
}

?>