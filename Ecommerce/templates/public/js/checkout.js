import { CookieUtils } from './cookies.js';
import { addToCartList, addTotalToCart, addTotalItemCart } from './renderCheckout.js';

// Ricarica il carrello dai cookie
const loadedCart = CookieUtils.loadCartFromCookie();
loadedCart.showCart();
const products = loadedCart.getProducts();


products.forEach(p => {
    addToCartList(p);
});
addTotalToCart(loadedCart.getTotal());
addTotalItemCart(loadedCart.getProductsCount());

//imposto la value predefinita dell'input per inviarla al backend
document.querySelector('input[name="prezzoTotale"]').value = loadedCart.getTotal();
