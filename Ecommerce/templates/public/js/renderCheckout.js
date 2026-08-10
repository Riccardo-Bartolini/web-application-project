/**
 * Inserisce <li> tag nel DOM con le info del prodotto, usate nella checkout page.
 * @param {*} p prodotto da reinderizzare
 */
export function addToCartList(p) {
    // Seleziona l'elemento della lista nel DOM
    const cartList = document.getElementById("cart-list-checkout");

    // Crea un elemento <li> per il prodotto
    const listItem = document.createElement("li");
    listItem.classList.add("list-group-item", "d-flex", "justify-content-between", "lh-sm");

    // Crea l'elemento <h6> per il nome del prodotto
    const productName = document.createElement("h6");
    productName.classList.add("my-0");
    productName.textContent = p.name;

    // Crea l'elemento <small> per la quantità del prodotto
    const productQuantity = document.createElement("small");
    productQuantity.classList.add("text-body-secondary");
    productQuantity.textContent = `pz: ${p.quantity}`;

    // Crea l'elemento <span> per il totale del prodotto
    const productTotal = document.createElement("span");
    productTotal.classList.add("text-body-secondary");
    productTotal.textContent = `${(p.quantity * p.price).toFixed(2)}€`;

    // Aggiungi gli elementi creati a <li>
    listItem.appendChild(productName);
    listItem.appendChild(productQuantity);
    listItem.appendChild(productTotal);

    // Aggiungi <li> alla lista del carrello
    cartList.appendChild(listItem);
}

/**
 * Aggiunge il totale al DOM nella checkout page
 * @param {*} total è il totale del carrello
 */
export function addTotalToCart(total) {

    // Seleziona l'elemento della lista nel DOM
    const cartList = document.getElementById("cart-list-total");

    // Crea un elemento <li> per il totale
    const totalItem = document.createElement("li");
    totalItem.classList.add("list-group-item", "d-flex", "justify-content-between");

    // Crea un elemento <span> per il testo "Total"
    const totalText = document.createElement("span");
    totalText.textContent = `Total EUR`;  // Mostra il totale calcolato

    // Crea un elemento <strong> per il valore del totale
    const totalAmount = document.createElement("strong");
    totalAmount.textContent = `${total.toFixed(2)}€`;  // Mostra il totale in Euro

    // Aggiungi gli elementi creati a <li>
    totalItem.appendChild(totalText);
    totalItem.appendChild(totalAmount);

    // Aggiungi <li> al finale della lista del carrello
    cartList.appendChild(totalItem);
}

/**
 * Reinderizza il numero di prodotti nel carrello
 * @param {*} totalCart conto dei prodotti nel carrello
 */
export function addTotalItemCart(totalCart){
    const totalAmountCartDiv = document.getElementById("total-count-cart");

    // Crea un elemento <span> per visualizzare il totale
    const totalAmountSpan = document.createElement("span");
    totalAmountSpan.classList.add("badge", "bg-primary", "rounded-pill");

    // Imposta il testo all'interno del <span>
    totalAmountSpan.textContent = `${totalCart}`;  // Mostra il totale in Euro

    // Aggiungi il <span> al div con id "total-amount-cart"
    totalAmountCartDiv.appendChild(totalAmountSpan);
}