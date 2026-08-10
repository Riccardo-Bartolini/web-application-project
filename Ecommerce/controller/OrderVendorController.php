<?php
require_once(__ROOT__ . '/models/OrdineModel.php');
require_once(__ROOT__ . '/models/ProductModel.php');
require_once(__ROOT__ . '/views/OrderVendorView.php');

class OrderVendorController
{
    // Lista ordini per venditore
    public static function listOrders()
    {
        if(!isset($_SESSION['venditore']) || $_SESSION['venditore'] === false) {
            header('Location: /');
            exit;
        }

        $orders = ProductModel::getVendorOrders(UserModel::get($_SESSION['email'])->id);
        $view = new OrderVendorView();
        $view->render($orders);
    }
}
