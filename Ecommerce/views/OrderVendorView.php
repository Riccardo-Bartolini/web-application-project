<?php
require_once('BaseView.php');

class OrderVendorView extends BaseView
{
    public function render($orders)
    {
        echo $this->twig->render('vendor/orders.html.twig', [
            'orders' => $orders,
        ]);
    }

}
