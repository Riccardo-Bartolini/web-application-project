import { Cart } from './Cart.js';
import { Product } from './Product.js';

// Funzioni per gestire i cookie

/**
 * Salva un cookie sulla cache del browser
 * @param {*} name nome del cookie
 * @param {*} value valore del cookie
 * @param {*} days durata del cookie
 */

export class CookieUtils {

    /**
     * 
     * @param {*} name nome del cookie
     * @param {*} value valore del cookie
     * @param {*} days quanti giorni dura il cookie
     */
    static setCookie(name, value, days) {
        const date = new Date();
        // Converte i giorni forniti dall'utente in millisecondi
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = `expires=${date.toUTCString()}`;
        document.cookie = `${name}=${value};${expires};path=/`;
    }
    
    /**
     * 
     * @param {*} name il nome del cookie
     * @returns il valore del cookie associato al suo nome
     */
    static getCookie(name) {
        const cookies = document.cookie.split(';');
        for (let cookie of cookies) {
            const [key, value] = cookie.trim().split('=');
            if (key === name) {
                return value;
            }
        }
        return null;
    }
    
    /**
     * Salva il carrello nei cookie come json
     * @param {*} cart è instanza di Cart.js
     */
    static saveCartToCookie(cart) {
        const cartJSON = cart.toJSON();
        CookieUtils.setCookie('cart', cartJSON, 1); // Salva il cookie per 1 giorno
    }
    
    /**
     * 
     * @returns un'istanza di Cart.js scaricata dai cookies
     */
    static loadCartFromCookie() {
        const cartJSON = CookieUtils.getCookie('cart');
        if (cartJSON) {
            return Cart.fromJSON(cartJSON);
        }
        return new Cart(); // Se il cookie non esiste, ritorna un carrello vuoto
    }
}


/* Test
const cart = new Cart();
cart.clearCart();
const prod1 = new Product(0,"pizza",3.00,1,'images/img-card-example.png');
const prod2 = new Product(1,"mele",1.00,1,'images/img-card-example.png');
const prod3 = new Product(2,"pere",5.00,1,'images/img-card-example.png');
cart.addProduct(prod1);
cart.addProduct(prod2);
cart.addProduct(prod3);
cart.showCart();
saveCartToCookie(cart);
 */
