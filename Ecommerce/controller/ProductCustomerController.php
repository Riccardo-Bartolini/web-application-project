<?php
require_once(__ROOT__ . '/models/ProductModel.php');
require_once(__ROOT__ . '/views/ProductView.php');
require_once(__ROOT__ . '/models/OrdineModel.php');
require_once(__ROOT__ . '/models/OrdineProductModel.php');
require_once(__ROOT__ . '/views/CheckoutView.php');
require_once(__ROOT__ . '/utils/CookieUtils.php');

class ProductCustomerController {

    /**
     * Reinderizza tutti i prodotti filtrati per nome
     */
    public static function allProducts() {
        $not_found_message = FALSE;
        if (!isset($_GET['search'])){
            $list = ProductModel::all();
        } else {
            $list = ProductModel::where(array('name' => $_GET['search']));
            if (count($list) === 0) {
                $not_found_message = TRUE;
            }
        }

        $view = new ProductView();
        $view->render($not_found_message, $list);
    }

    /**
     * Reinderizza un solo prodotto passato per id
     */
    public static function showProduct($id) {
        $product = ProductModel::get($id);

        $view = new ProductView();
        $view->show($product);
    }

    /**
     * Effettua il salvataggio di un Ordine e dei prodotti ad esso associati
     */
    public static function checkout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ordine = new OrdineModel(array(
                "id" => null,
                "data" => date('Y-m-d'),
                "prezzoTotale" => $_POST["prezzoTotale"],
                "nome" =>  $_POST["nome"],
                "cognome" => $_POST["cognome"],
                "email" => $_POST["email"],
                "indirizzo" => $_POST["indirizzo"],
                "citta" => $_POST["citta"],
                "user_id" =>  isset($_SESSION["email"]) ? UserModel::get($_SESSION["email"])->id : null,
                "codiceOrdine" => OrdineModel::generateOrderCode()
            ));

            try {
                $idOrdine = $ordine->save();
                //Invio notifica al cliente dell'acquisto
                if(isset($_SESSION["email"])) {
                    $notification = new NotificheModel(array(
                        "id" => 0,
                        "testo" => "Grazie di averci scelto, il tuo ordine è stato preso in carico!",
                        "data" => date("Y-m-d"),
                        "user_id" => UserModel::get($_SESSION["email"])->id,
                        "letta" => 0
                    ));
                    $notification->save();
                }
                $productDetails = getCartFromCookie();

                foreach ($productDetails as $detail) {
                    $pivot = new OrdineProductModel(array(
                        "idOrdine" => $idOrdine,
                        "idProduct" => $detail["id"],
                        "quantita" => $detail["quantity"]
                    ));
                    //Invio notifica al venditore
                    $productOrdered = ProductModel::get($detail["id"]);
                    $notification = new NotificheModel(array(
                        "id" => 0,
                        "testo" => "Hai un nuovo ordine per ". $productOrdered->name,
                        "data" => date("Y-m-d"),
                        "user_id" => $productOrdered->vendor_id,
                        "letta" => 0
                    ));
                    $notification->save();

                    try {
                        $pivot->save();
                    } catch (Exception $err) {
                        echo $_SESSION["error"] = "Si è verificato un errore durante il salvataggio del pivot";
                        echo $err;
                    }
                }
                clearCartCookie();
                header('Location: /thankyou_page');
                
            } catch (Exception $err) {
                echo $_SESSION["error"] = "Si è verificato un errore durante il salvataggio dell'ordine";
                echo $err;
            }

        }else{
            UserController::renderNotifiche();
        }

        $view = new CheckoutView();
        $view->render();
    }

    /**
     * Reinderizza i prodotti in base ai filtri
     */
    public static function renderFilteredProducts() {
        $not_found_message = FALSE;
        if (empty($_GET)){
            $list = ProductModel::all();
        } else {
            $product = new ProductModel();
            $expectedParams = $product->getFields();
            $expectedParams[] = "unitCostMin";
            $expectedParams[] = "unitCostMax";

            // Array per salvare i filtri
            $filters = array();

            // Itera sui parametri attesi
            foreach ($expectedParams as $param) {
                if (isset($_GET[$param]) && !empty($_GET[$param])) {
                    // Aggiungi il parametro all'array dei filtri
                    $filters[$param] = $_GET[$param];
                }
            }
            $list = ProductModel::filterProducts($filters);
            if (count($list) === 0) {
                $not_found_message = TRUE;
            }
        }

        $view = new ProductView();
        $view->renderFilteredProducts($not_found_message, $list);
    }
}