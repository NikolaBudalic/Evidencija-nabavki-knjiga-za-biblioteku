<?php

class DBStavkaNabavke extends Tabela
{
    public function DodajStavkuNabavke($IDNabavke, $ISBN, $Kolicina, $Cena)
    {
        $SQL = "INSERT INTO `stavka_nabavke`
                (IDNabavke, ISBN, Kolicina, Cena)
                VALUES
                ('".$IDNabavke."', '".$ISBN."', '".$Kolicina."', '".$Cena."')";

        return $this->IzvrsiAktivanSQLUpit($SQL);
    }
}

?>