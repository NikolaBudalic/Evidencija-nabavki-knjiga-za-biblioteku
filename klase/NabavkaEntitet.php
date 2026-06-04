<?php

class KnjigaEntitet
{
    public $ISBN;
    public $Naziv;
    public $Autor;
    public $OznakaZanra;
    public $NazivZanra;
    public $Cena;

    public function __construct($ISBN, $Naziv = "", $Autor = "", $OznakaZanra = "", $NazivZanra = "", $Cena = 0)
    {
        $this->ISBN = $ISBN;
        $this->Naziv = $Naziv;
        $this->Autor = $Autor;
        $this->OznakaZanra = $OznakaZanra;
        $this->NazivZanra = $NazivZanra;
        $this->Cena = $Cena;
    }
}

?>