import { CookieUtils } from './cookies.js';

/**
 * 
 * @param {*} cart is the cart rendered from cookie using navbar
 * @param {*} product is the product to render in the DOM contained in cart
 * @returns the div with product DOM info
 */
export function createProductElement(cart, product) {
    // Crea il contenitore principale <div>
    const productDiv = document.createElement("div");
    productDiv.classList.add("product", "border", "border-1", "border-primary", "mt-2", "rounded-2", "p-1", "d-flex");

    // Crea l'immagine <img>
    const img = document.createElement("img");
    img.setAttribute("src", product.imgSRC); // Path dell'immagine
    img.setAttribute("width", "50");
    img.setAttribute("height", "50");

    // Crea il paragrafo per il nome del prodotto
    const nameParagraph = document.createElement("p");
    nameParagraph.classList.add("text", "text-start", "fs-5", "w-50", "mt-2", "ps-2");
    nameParagraph.textContent = product.name; // Nome del prodotto

    // Crea un paragrafo <p> e aggiungi le classi
    const qtyParagraph = document.createElement("p");
    qtyParagraph.classList.add("text", "text-end", "fs-5", "w-50", "mt-2");

    // Crea un elemento <span> per la quantità
    const quantitySpan = document.createElement("span");
    quantitySpan.id = `quantityShowed${product.id}`; // Assegna l'ID al <span>

    // Crea il nodo di testo per la quantità
    const quantityText = document.createTextNode(`Pz: ${product.quantity} `);

    // Aggiungi il testo al <span>
    quantitySpan.appendChild(quantityText);

    // Aggiungi il <span> al <p>
    qtyParagraph.appendChild(quantitySpan);


    // Crea lo span e aggiungilo al paragrafo (se necessario)
    const spanElement = document.createElement("span");
    qtyParagraph.appendChild(spanElement);

    // Crea il link per aggiungere il prodotto al carrello
    const addLink = document.createElement("a");
    addLink.classList.add("text-light", "text-decoration-none", "add-product-cart", "fs-4", "p-2");
    addLink.textContent = "+"; // Testo per aggiungere al carrello

    // Crea il link per rimuovere il prodotto dal carrello
    const removeLink = document.createElement("a");
    removeLink.classList.add("text-light", "text-decoration-none", "toggle-product-cart", "fs-4", "p-2");
    removeLink.textContent = "-"; // Testo per rimuovere dal carrello

    // Aggiungi gli event listeners per eseguire le funzioni al click
    addLink.addEventListener("click", () => {
        cart.addProduct(product);  // Chiamata alla funzione per aggiungere al carrello
        console.log("Elemento aggiunto");
        const valueShowed = document.getElementById(`quantityShowed${product.id}`);
        valueShowed.textContent = `Pz: ${product.quantity} `;
        CookieUtils.saveCartToCookie(cart);
    });

    removeLink.addEventListener("click", () => {
        cart.removeProduct(product)  // Chiamata alla funzione per rimuovere dal carrello
        console.log("Elemento rimosso");
        const valueShowed = document.getElementById(`quantityShowed${product.id}`);
        valueShowed.textContent = `Pz: ${product.quantity} `;
        if (!cart.isProductInCart(product.id)) valueShowed.textContent = `Pz: 0 `;
        CookieUtils.saveCartToCookie(cart);
    });

    // Aggiungi i link per aggiungere e rimuovere al carrello
    spanElement.appendChild(addLink);
    spanElement.appendChild(removeLink);

    // Aggiungi gli elementi creati al div principale
    productDiv.appendChild(img);
    productDiv.appendChild(nameParagraph);
    productDiv.appendChild(qtyParagraph);

    // Restituisci l'elemento prodotto creato
    return productDiv;
}
