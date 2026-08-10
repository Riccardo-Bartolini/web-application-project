/**
 * Rappresent a generic product
 */
export class Product{
    /**
     * 
     * @param {*} id id in database, must be unique
     * @param {*} name name of product
     * @param {*} price price of product
     * @param {*} quantity quantity in cart of product
     * @param {*} imgSRC relative path of img product
     */
    constructor(id,name,price,quantity, imgSRC){
        this.id = id;
        this.name = name;
        this.price = price;
        //is the quantity inside cart
        this.quantity = quantity;
        this.imgSRC = imgSRC
    }
}