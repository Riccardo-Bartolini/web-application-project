import { CookieUtils } from './cookies.js';
import { createProductElement } from './renderCart.js';

// Ricarica il carrello dai cookie
const loadedCart = CookieUtils.loadCartFromCookie();
loadedCart.showCart();
const products = loadedCart.getProducts();


products.forEach(p => {
    const productsList = document.getElementById("products-list");
    // Aggiungi il prodotto dinamicamente alla lista
    const productElement = createProductElement(loadedCart, p);
    productsList.appendChild(productElement);
});
