<?php
class DBSmer extends Tabela 
{
    public $Oznaka;
    public $Naziv; 
    public $UkupanBrojStudenata;

    public function UcitajKolekcijuSvihSmerova()
    {
        $SQL = "select * from `".$this->NazivBazePodataka."`.`zanr` ORDER BY Naziv ASC";
        $this->UcitajSvePoUpitu($SQL);
    }

    public function InkrementirajBrojStudenata($IDSmer)
    {
        $KriterijumFiltriranja = "Oznaka='".$IDSmer."'";
        $StaraVrednost = $this->DajVrednostJednogPoljaPrvogZapisa(
            'UkupanBrojKnjiga',
            $KriterijumFiltriranja,
            'UkupanBrojKnjiga'
        );

        $NovaVrednost = $StaraVrednost + 1;

        $SQL = "UPDATE `".$this->NazivBazePodataka."`.`zanr`
                SET UkupanBrojKnjiga=".$NovaVrednost."
                WHERE Oznaka='".$IDSmer."'";

        $greska = $this->IzvrsiAktivanSQLUpit($SQL);

        return $greska;
    }

    public function DekrementirajBrojStudenata($IDSmer)
    {
        $KriterijumFiltriranja = "Oznaka='".$IDSmer."'";
        $StaraVrednost = $this->DajVrednostJednogPoljaPrvogZapisa(
            'UkupanBrojKnjiga',
            $KriterijumFiltriranja,
            'UkupanBrojKnjiga'
        );

        $NovaVrednost = $StaraVrednost - 1;

        $SQL = "UPDATE `".$this->NazivBazePodataka."`.`zanr`
                SET UkupanBrojKnjiga=".$NovaVrednost."
                WHERE Oznaka='".$IDSmer."'";

        $greska = $this->IzvrsiAktivanSQLUpit($SQL);

        return $greska;
    }
}
?>