import { Product } from './Product.js';

/**
 * Rappresent the cart used by user for buy products, it's has to be saved
 * in browser cache
 */
export class Cart {

    /**
     * Array of (Product) products
     */
    constructor(){
        this.products = [];
    }

    clearCart(){
        this.products = [];
    }

    /**
     * 
     * @param {*} product is the product to add in cart
     */
    addProduct(product){
        if (!(product instanceof Product)) {
            throw new Error("Only Product instances can be added to the cart");
        }

        const existingProduct = this.products.find(p => p.id === product.id);

        if (existingProduct) {
            existingProduct.quantity += 1;
        } else {
            this.products.push(product);
        }
    }

    /**
     * 
     * @param {*} product is the product to remove from cart
     */
    removeProduct(product) {
        if (!(product instanceof Product)) {
            throw new Error("Only Product instances can be removed from the cart");
        }
    
        // Trova il prodotto nel carrello
        const existingProduct = this.products.find(p => p.id === product.id);
    
        if (existingProduct) {
            if (existingProduct.quantity > 1) {
                // Riduce la quantità di 1
                existingProduct.quantity -= 1;
            } else {
                // Rimuove il prodotto se la quantità è 0 o minore
                this.products = this.products.filter(p => p.id !== product.id);
            }
        } else {
            console.log("Il prodotto non esiste nel carrello.");
        }
    }

    /**
     * 
     * @returns the total amount of money to spend for products in the cart
     */
    getTotal() {
        return this.products.reduce((total, product) => total + (product.price * product.quantity), 0);
    }

    /**
     * Show in console info about cart
     */
    showCart() {
        if (this.products.length === 0) {
            console.log("Il carrello è vuoto!");
        } else {
            console.log("Prodotti nel carrello:");
            this.products.forEach(product => {
                console.log(`- ${product.name} (x${product.quantity}): €${(product.price * product.quantity).toFixed(2)}`);
            });
        }
    }

    /**
     * 
     * @returns the number of products in the cart
     */
    getProductsCount(){
        return parseInt(this.products.length);
    }

    /**
     * 
     * @returns the products in cart as JSON
     */
    toJSON() {
        return JSON.stringify(this.products);
    }

    /**
     * 
     * @param {*} json is the json saved in web cache or somewhere with cart info
     * @returns the Cart object contained the products explained in the JSON
     */
    static fromJSON(json) {
        const cart = new Cart();
        const products = JSON.parse(json);
        products.forEach(p => {
            cart.addProduct(new Product(p.id, p.name, p.price, p.quantity, p.imgSRC));
        });
        return cart;
    }

    /**
     * 
     * @returns the products array in cart
     */
    getProducts(){
        return this.products;
    }

    /**
     * Controlla se un prodotto con l'ID specificato è presente nel carrello
     * @param {number|string} productId - L'ID del prodotto da cercare
     * @returns {boolean} - Ritorna true se il prodotto è nel carrello, altrimenti false
     */
    isProductInCart(productId) {
        return this.products.some(product => product.id === productId);
    }
    
}