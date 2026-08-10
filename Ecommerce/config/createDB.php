<?php
require_once "config.php";

// Configurazione per la connessione al database MySQL
$database = "ECOMMERCE"; // Nome del database
$sql_file = "table_creation.SQL"; // Percorso del file SQL

$conn = new mysqli(Config::$db_host, Config::$db_username, Config::$db_password);

// Query per creare il database ECOMMERCE
$sql_create_db = "CREATE DATABASE IF NOT EXISTS ECOMMERCE";

if ($conn->query($sql_create_db) === TRUE) {
    echo "Database 'ECOMMERCE' creato con successo";
} else {
    echo "Errore nella creazione del database: " . $conn->error;
}


// Ottieni il percorso completo del file SQL
$sql_file_path = __DIR__ . DIRECTORY_SEPARATOR . $sql_file;

// Connessione al database
$conn = new mysqli(Config::$db_host, Config::$db_username, Config::$db_password, $database);

// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Verifica se il file SQL esiste
if (!file_exists($sql_file_path)) {
    die("File SQL non trovato: " . $sql_file_path);
}

// Leggi il contenuto del file SQL
$sql_content = file_get_contents($sql_file_path);


// Suddividi il contenuto del file SQL in singole query
$queries = explode(';', $sql_content);

// Esegui ogni singola query
foreach ($queries as $query) {
    // Ignora le query vuote (ad esempio alla fine del file)
    if (trim($query) != '') {
        if ($conn->query($query) === FALSE) {
            echo "Errore nell'esecuzione della query: " . $conn->error;
            break;
        }
    }
}

// Chiusura della connessione al database
$conn->close();

?>
