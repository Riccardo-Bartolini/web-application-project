<?php
require_once('BaseModel.php');

class ProductModel extends BaseModel
{
    public static string $nome_tabella = 'Product';
    
    protected array $_fields = [
        "id",
        "unitCost",
        "name",
        "marca",
        "descrizione",
        "condizioni",
        "imgPath1",
        "imgPath2",
        "imgPath3",
        "vendor_id"
    ];
    
    public static function getVendorOrders($vendor_id)
    {
        $query = "SELECT O.nome, O.cognome, O.indirizzo, O.citta, O.codiceOrdine,
         P.name AS nomeProdotto, PO.quantita, (P.unitCost * PO.quantita) AS totaleCosto
          FROM " . static::$nome_tabella . " P, " . OrdineModel::$nome_tabella . " O, " . OrdineProductModel::$nome_tabella . " PO 
          WHERE P.vendor_id = :vendor_id
          AND O.id = PO.idOrdine AND P.id = PO.idProduct";
        $stmt = DB::get()->prepare($query);
        $stmt->bindParam(':vendor_id', $vendor_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
   
    public static function filterProducts($filters) {
        // Inizializza la query base
        $query = "SELECT * FROM " . static::$nome_tabella . " WHERE 1=1";
        $params = [];

        // Itera sui filtri per costruire la query dinamica
        foreach ($filters as $key => $value) {
            if (in_array($key, ['name', 'marca', 'condizioni'])) {
                // Filtri per campi testuali (LIKE)
                $query .= " AND $key LIKE :$key";
                $params[$key] = "%$value%";
            } elseif ($key === 'unitCostMin') {
                // Filtro per valore minimo
                $query .= " AND unitCost >= :unitCostMin";
                $params['unitCostMin'] = $value;
            } elseif ($key === 'unitCostMax') {
                // Filtro per valore massimo
                $query .= " AND unitCost <= :unitCostMax";
                $params['unitCostMax'] = $value;
            }
        }

        // Prepara e esegui la query
        $stmt = DB::get()->prepare($query);
        $stmt->execute($params);

        // Restituisci i risultati
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
