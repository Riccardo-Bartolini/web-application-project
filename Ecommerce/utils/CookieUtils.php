<?php

/**
 * Recupera il contenuto del carrello dai cookie.
 * 
 * @return array Un array con le informazioni dei prodotti nel carrello.
 */
function getCartFromCookie() {
    // Controlla se il cookie 'cart' esiste
    if (isset($_COOKIE['cart'])) {
        // Recupera il valore del cookie
        $cartJSON = $_COOKIE['cart'];
        
        // Decodifica il JSON in un array associativo
        $cartArray = json_decode($cartJSON, true);

        // Verifica se la decodifica ha avuto successo
        if ($cartArray !== null) {
            return $cartArray; // Ritorna i prodotti del carrello
        }
    }

    // Se il cookie non esiste o è malformato, ritorna un array vuoto
    return [];
}

/**
 * Pulisce il carrello eliminando il cookie 'cart'.
 */
function clearCartCookie() {
    if (isset($_COOKIE['cart'])) {
        // Imposta il cookie 'cart' con una data di scadenza nel passato
        setcookie('cart', '', time() - 3600, '/');
        echo "Carrello svuotato con successo.";
    } else {
        echo "Il carrello è già vuoto.";
    }
}
