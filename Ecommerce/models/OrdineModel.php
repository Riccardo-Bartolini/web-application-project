<?php

require_once('BaseModel.php');

class OrdineModel extends BaseModel
{
    public static string $nome_tabella = 'Ordine';
    
    protected array $_fields = [
        "id",
        "data",
        "prezzoTotale",
        "nome",
        "cognome",
        "email",
        "indirizzo",
        "citta",
        "user_id",
        "codiceOrdine"
    ];

    /** @var ProductModel[] */
    public array $productsAssociated;
   
    public function setProducts() {
        $sql = "SELECT P.* FROM " . static::$nome_tabella . " O, ". ProductModel::$nome_tabella ." P, " . OrdineProductModel::$nome_tabella . " OP WHERE O.id = OP.idOrdine AND P.id = OP.idProduct AND O.id=:id";
        $sth = DB::get()->prepare($sql);
        $sth->execute([
            "id" => $this->id
        ]);
        $rows = $sth->fetchAll();
        $this->productsAssociated = $rows;
    }

    /**
     * Genera un codice univoco per l'ordine finchè il generato non è presente su DB
     */
    public static function generateOrderCode() {
        $codiceOrdine = substr(str_shuffle(str_repeat("0123456789ABCDEFGHIJKLMNOPQRSTUWXYZ", 5)), 0, 5);
        while(true) {
            $query = "SELECT * FROM " . static::$nome_tabella . " WHERE codiceOrdine = :codiceOrdine";
            $stmt = DB::get()->prepare($query);
            $stmt->bindParam(':codiceOrdine', $codiceOrdine);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) === 0) {
                return $codiceOrdine;
            }
        }
    }

}
