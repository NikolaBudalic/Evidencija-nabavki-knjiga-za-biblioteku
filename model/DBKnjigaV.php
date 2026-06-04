<?php
class DBKnjiga extends Tabela 
{
    public function DajSvePodatkeOKnjigama($filterParametar)
    {
        if (isset($filterParametar) && $filterParametar != "")
        {
            $upit = "select * from `".$this->NazivBazePodataka."`.`svipodacioknjigamasaslikom`
                     where `Naziv` LIKE '%".$filterParametar."%'";
        }
        else
        {
            $upit = "select * from `".$this->NazivBazePodataka."`.`svipodacioknjigamasaslikom`";
        }

        $this->UcitajSvePoUpitu($upit);
    }
}
?>