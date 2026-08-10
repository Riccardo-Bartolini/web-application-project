<?php
require_once(__ROOT__ . '/models/ProductModel.php');
require_once(__ROOT__ . '/views/ProductView.php');
require_once(__ROOT__ . '/models/UserModel.php');
require_once(__ROOT__ . '/utils/ImageUtils.php');

class ProductVendorController
{
    // Mostra i prodotti del venditore
    public static function listProducts()
    {
        if(!isset($_SESSION['venditore']) || $_SESSION['venditore'] === false) {
            header('Location: /');
            exit;
        }

        $products = ProductModel::where(['vendor_id' => UserModel::get($_SESSION['email'])->id]);
        $view = new ProductView();
        $view->renderVendorProducts($products);
    }

    // Crea un nuovo prodotto
    public static function createProduct()
    {
        if(!isset($_SESSION['venditore']) || $_SESSION['venditore'] === false) {
            header('Location: /');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $uploadedImages = saveProductImages($_FILES);
                
                $product = new ProductModel(array(
                    "id" => 0,
                    "unitCost" => $_POST["unitCost"],
                    "name" => $_POST["name"],
                    "marca" => $_POST["marca"],
                    "descrizione" => $_POST["descrizione"],
                    "condizioni" => $_POST["condizioni"],
                    "imgPath1" => $uploadedImages[0],
                    "imgPath2" => $uploadedImages[1],
                    "imgPath3" => $uploadedImages[2],
                    'vendor_id' => UserModel::get($_SESSION['email'])->id
                ));
                try {
                    $id = $product->save();
                    //header('Location: /productForm');
                } catch (Exception $err) {
                    echo $err;
                }

            } catch (Exception $e) {
                echo "Errore: " . $e->getMessage();
            }
        }
        $products = ProductModel::where(['vendor_id' => UserModel::get($_SESSION['email'])->id]);
        $view = new ProductView();
        $view->renderVendorForm($products);

    }


    // Elimina un prodotto
    public static function deleteProduct()
    {
        if(!isset($_SESSION['venditore']) || $_SESSION['venditore'] === false) {
            header('Location: /');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            $productId = $data['id'];

            $product = ProductModel::get($productId);
            $result = deleteImagesFromProjectRoot($product->imgPath1, $product->imgPath2, $product->imgPath3);
            ProductModel::delete($productId);
            http_response_code(200); // Risposta OK
            header('Location: /ListinoProdotti');
        } else {
            http_response_code(405); // Metodo non consentito
        }
    }

}
