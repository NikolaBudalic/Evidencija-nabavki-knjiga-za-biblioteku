<?php
class DBStudent extends Tabela 
{
    public function DajSvePodatkeOStudentima($filterParametar)
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