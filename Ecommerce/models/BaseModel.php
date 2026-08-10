<?php

require_once(__ROOT__ . '/config/DB.php');
require_once(__ROOT__ . '/utils/ClassUtils.php');



/**
 * Classe base per la gestione di modelli ORM.
 * Fornisce metodi per operazioni CRUD e gestione dinamica delle proprietà.
 */
abstract class BaseModel
{
    /**
     * @var array Associazione di campi e valori dell'entità.
     */
    private $_data;

    /**
     * @var string Nome della tabella associata al modello.
     */
    public static string $nome_tabella;

    /**
     * @var array Elenco dei campi della tabella gestiti dal modello.
     */
    protected array $_fields;

    /**
     * @var array Valori preparati per query di inserimento o aggiornamento.
     */
    private array $_values;

    /**
     * @var string Elenco delle colonne per la query SQL.
     */
    private string $_columns;

    /**
     * @var string Elenco delle colonne con segnaposto per valori.
     */
    private string $_bind_columns;

    /**
     * @var array|null Eventuali errori di validazione o di altro tipo.
     */
    public ?array $errors;

    /**
     * Costruttore del modello.
     *
     * @param array $properties Valori iniziali per i campi del modello.
     */
    public function __construct(array $properties = array())
    {
        foreach ($this->_fields as $field) {
            $this->_data[$field] = $properties[$field] ?? null;
        }
    }

    /**
     * Metodo magico per impostare il valore di una proprietà.
     *
     * @param string $property Nome della proprietà.
     * @param mixed $value Valore da assegnare.
     * @return mixed Valore assegnato.
     */
    public function __set(string $property, $value)
    {
        if (method_exists($this, $method = 'set' . ucfirst($property))) {
            $this->$method($property, $value);
        }

        return $this->_data[$property] = $value;
    }

    /**
     * Metodo magico per ottenere il valore di una proprietà.
     *
     * @param string $property Nome della proprietà.
     * @return mixed|null Valore della proprietà o null se non esiste.
     */
    public function __get(string $property)
    {
        if (method_exists($this, $method = 'get' . ucfirst($property))) {
            return $this->$method($property);
        }

        return array_key_exists($property, $this->_data)
            ? $this->_data[$property]
            : null;
    }

    /**
     * Metodo magico per verificare se una proprietà è impostata.
     *
     * @param string $property Nome della proprietà.
     * @return bool True se la proprietà è impostata, altrimenti false.
     */
    public function __isset(string $property): bool
    {
        return array_key_exists($property, $this->_data);
    }

    /**
     * Prepara i dati per un'operazione di inserimento o aggiornamento.
     *
     * @return void
     */
    private function _prepare()
    {
        $props = non_null_properties($this, $this->_fields);

        $this->_columns = implode(", ", $props);
        $this->_bind_columns = ':' . implode(", :", $props);
        $this->_values = class_to_array($this, $props);
    }

    /**
     * Salva l'istanza nel database.
     *
     * @return int ID del record salvato.
     */
    public function save(): int
    {
        $this->_prepare();
        $updates = array();
        foreach ($this->_fields as $column) {
            $updates[] = "$column=VALUES($column)";
        }
        $updates_str = implode(',', $updates);
        $sql = "INSERT INTO " . static::$nome_tabella . " ($this->_columns) VALUES ($this->_bind_columns) ON DUPLICATE KEY UPDATE $updates_str";
        $sth = DB::get()->prepare($sql);
        $sth->execute($this->_values);

        if (isset($this->_values["id"]) && $this->_values["id"] != '') {
            return (int) $this->_values["id"];
        } else {
            return (int) DB::get()->lastInsertId();
        }
    }

    /**
     * Restituisce tutti i record della tabella.
     *
     * @return array Array di istanze del modello.
     */
    public static function all(): array
    {
        $sql = 'SELECT * FROM ' . static::$nome_tabella;
        $list = DB::get()->query($sql)->fetchAll();

        $entities = array();
        foreach ($list as $row) {
            $entities[] = new static($row);
        }

        return $entities;
    }

    /**
     * Seleziona specifiche colonne dalla tabella.
     *
     * @param array $selection Colonne da selezionare.
     * @return array Array di istanze del modello.
     */
    public static function select(array $selection): array
    {
        $columns = implode(',', $selection);
        $sql = 'SELECT $columns FROM ' . static::$nome_tabella;
        $list = DB::get()->query($sql)->fetchAll();

        $entities = array();
        foreach ($list as $row) {
            $entities[] = new static($row);
        }

        return $entities;
    }

    /**
     * Filtra i record in base a specifiche condizioni.
     *
     * @param array $conditions Condizioni come coppie chiave-valore.
     * @return array Array di istanze del modello.
     */
    public static function where(array $conditions): array
    {
        $sql = 'SELECT * FROM ' . static::$nome_tabella . ' WHERE';

        $where = '';
        foreach ($conditions as $key => $prop) {
            if ($where == '')
                $where .= " $key = :$key";
            else
                $where .= " AND $key = :$key";
        }
        $sql .= $where;

        $sth = DB::get()->prepare($sql);
        $sth->execute($conditions);
        $list = $sth->fetchAll();

        $entities = array();
        foreach ($list as $row) {
            $entities[] = new static($row);
        }

        return $entities;
    }

    /**
     * Recupera un record in base al suo ID.
     *
     * @param int $id ID del record.
     * @return static Istanza del modello.
     */
    public static function get(int $id)
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("L'ID fornito non è valido.");
        }

        $query = "SELECT * FROM " . static::$nome_tabella  . " WHERE id=:id";
        $sth = DB::get()->prepare($query);
        $sth->execute([
            'id' => $id,
        ]);
        $row = $sth->fetch();

        if (!$row) {
            throw new Exception("Record non trovato per l'ID: $id");
        }

        return new static($row);
    }

    /**
     * Elimina un record in base al suo ID.
     *
     * @param int $id ID del record.
     * @return void
     */
    public static function delete(int $id): void
    {
        $query = "DELETE FROM " . static::$nome_tabella  . " WHERE id = :id;";
        $sth = DB::get()->prepare($query);
        $sth->execute([
            'id' => $id,
        ]);
    }

    /**
     * Valida i dati del modello.
     * Questo metodo può essere sovrascritto nelle classi figlie.
     *
     * @return bool True se i dati sono validi, altrimenti false.
     */
    public function validate(): bool
    {
        return true;
    }

    /**
     * Metodo per accedere al campo _fields
     *  */ 
    public function getFields(): array
    {
        return $this->_fields;
    }
}