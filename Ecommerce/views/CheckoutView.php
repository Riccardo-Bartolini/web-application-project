<?php

require_once('BaseView.php');

class CheckoutView extends BaseView
{

    public function render()
    {
        echo $this->twig->render('customers/checkout.html.twig', [
            
        ]);
    }
}
