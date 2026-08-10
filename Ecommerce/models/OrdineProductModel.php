<?php
require_once('BaseModel.php');

class OrdineProductModel extends BaseModel
{
    public static string $nome_tabella = 'ContenutoOrdine';
    
    protected array $_fields = [
        "idOrdine",
        "idProduct",
        "quantita"
    ];
   
}
