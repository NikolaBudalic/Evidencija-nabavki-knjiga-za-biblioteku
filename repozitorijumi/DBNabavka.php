<?php

class DBNabavka extends Tabela
{
    public function PronadjiNabavku($DatumNabavke, $Dobavljac)
    {
        $SQL = "SELECT IDNabavke 
                FROM `nabavka`
                WHERE DatumNabavke = '".$DatumNabavke."'
                AND Dobavljac = '".$Dobavljac."'
                LIMIT 1";

        $this->UcitajSvePoUpitu($SQL);

        if ($this->BrojZapisa > 0) {
            return $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, 0, 0);
        }

        return null;
    }

    public function DodajNabavku($DatumNabavke, $Dobavljac, $Napomena)
    {
        $SQL = "INSERT INTO `nabavka`
                (DatumNabavke, Dobavljac, Napomena)
                VALUES
                ('".$DatumNabavke."', '".$Dobavljac."', '".$Napomena."')";

        return $this->IzvrsiAktivanSQLUpit($SQL);
    }

    public function DajPoslednjiID()
    {
        $SQL = "SELECT LAST_INSERT_ID()";

        $this->UcitajSvePoUpitu($SQL);

        return $this->DajVrednostPoRednomBrojuZapisaPoRBPolja($this->Kolekcija, 0, 0);
    }
}

?>