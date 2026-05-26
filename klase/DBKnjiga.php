<?php
class DBStudent extends Tabela 
{
    public $BrojIndeksa;
    public $Prezime;
    public $Ime;
    public $OznakaSmera;
    public $NazivFajlaFotografije;

    public function DajKolekcijuSvihStudenata()
    {
        $SQL = "select * from `knjiga` ORDER BY Naziv ASC";
        $this->UcitajSvePoUpitu($SQL);
        return $this->Kolekcija;
    }

    public function UcitajStudentaPoBrojuIndeksa($BrojIndeksaParametar)
    {
        $SQL = "select * from `knjiga` where `ISBN`='".$BrojIndeksaParametar."'";
        $this->UcitajSvePoUpitu($SQL);
    }

    public function DajOznakuSmeraStudenta($BrojIndeksaParametar)
    {
        $SQL = "select `OznakaZanra` from `knjiga` where `ISBN`='".$BrojIndeksaParametar."'";
        $this->UcitajSvePoUpitu($SQL);
        return $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, 0, 0);
    }

    public function DodajNovogStudenta()
    {
        $SQL = "INSERT INTO `knjiga`
        (ISBN, Naziv, Autor, OznakaZanra, NazivFajlaSlike)
        VALUES
        ('$this->BrojIndeksa', '$this->Prezime', '$this->Ime', '$this->OznakaSmera', '$this->NazivFajlaFotografije')";

        $greska = $this->IzvrsiAktivanSQLUpit($SQL);
        return $greska;
    }

    public function ObrisiStudenta($IdZaBrisanje)
    {
        $SQL = "DELETE FROM `knjiga` WHERE ISBN='".$IdZaBrisanje."'";
        $greska = $this->IzvrsiAktivanSQLUpit($SQL);
        return $greska;
    }

    public function IzmeniStudenta($StariBrojIndeksa, $brojIndeksa, $prezime, $ime, $oznakaSmera, $nazivFajlaFotografije)
    {
        $SQL = "UPDATE `knjiga`
        SET ISBN='".$brojIndeksa."',
            Naziv='".$prezime."',
            Autor='".$ime."',
            OznakaZanra='".$oznakaSmera."',
            NazivFajlaSlike='".$nazivFajlaFotografije."'
        WHERE ISBN='".$StariBrojIndeksa."'";

        $greska = $this->IzvrsiAktivanSQLUpit($SQL);
        return $greska;
    }
}
?>