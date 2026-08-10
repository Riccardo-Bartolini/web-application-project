<?php

define('__ROOT__', dirname(__FILE__));

require_once (__ROOT__ . '/controller/ProductCustomerController.php');
require_once (__ROOT__ . '/controller/UserController.php');
require_once (__ROOT__ . '/views/ThankyouView.php');
require_once (__ROOT__ . '/utils/CookieUtils.php');
require_once (__ROOT__ . '/controller/OrderVendorController.php');
require_once (__ROOT__ . '/controller/ProductVendorController.php');


session_start();
$_SESSION["pageTitlte"] = "Eterna Eleganza";
class Dispatcher
{
    private $method;
    private $path;

    public function __construct()
    {
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    }

    public function dispatch()
    {
        switch ($this->path) {
            
            case "/":
                UserController::renderNotifiche();
                ProductCustomerController::allProducts();
                $_SESSION["pageTitlte"] = "Eterna Eleganza";
                break;
            
            case "/segna-notifica-letta":
                UserController::signReadNotifica();
                break;
            
            case "/catalogo_prodotti":
                UserController::renderNotifiche();
                ProductCustomerController::renderFilteredProducts();
                $_SESSION["pageTitlte"] = "Catalogo Prodotti";
                break;

            case "/prodotto":
                UserController::renderNotifiche();
                ProductCustomerController::showProduct($_GET["id"]);
                $_SESSION["pageTitlte"] = "Info Prodotto";
                break;

            case "/checkout":
                ProductCustomerController::checkout();
                $_SESSION["pageTitlte"] = "Pagina Pagamento";
                break;

            case "/thankyou_page":
                UserController::renderNotifiche();
                $view = new ThankyouView();
                $view->show();
                $_SESSION["pageTitlte"] = "Grazie di averci scelto";
                break;

            case "/login":
                UserController::login();
                $_SESSION["pageTitlte"] = "Eterna Eleganza Login";
                break;
            
            case "/signup":
                UserController::signup();
                $_SESSION["pageTitlte"] = "Eterna Eleganza Registrati";
                break;

            case "/area_privata":
                UserController::renderNotifiche();
                UserController::privateArea();
                $_SESSION["pageTitlte"] = "Eterna Eleganza Area Privata";
                break;
            
            case "/logout":
                session_destroy();
                clearCartCookie();
                header('Location: /');
                break;
            
            case "/vendorOrdini":
                UserController::renderNotifiche();
                OrderVendorController::listOrders();
                $_SESSION["pageTitlte"] = "Ordini Venditore";
                break;

            case "/productForm":
                UserController::renderNotifiche();
                ProductVendorController::createProduct();
                $_SESSION["pageTitlte"] = "Prodotti Venditore";
                break;

            case "/ListinoProdotti":
                UserController::renderNotifiche();
                ProductVendorController::listProducts();
                $_SESSION["pageTitlte"] = "Prodotti Venditore";
                break;
            
            case "/elimina_prodotto":
                ProductVendorController::deleteProduct();
                break;

            default:
                echo "404 HTML<br>";
                echo $this->path;
                break;
        }
    }
}

$dispatcher = new Dispatcher();
$dispatcher->dispatch();

