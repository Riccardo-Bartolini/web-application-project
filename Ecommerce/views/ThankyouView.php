<?php

require_once('BaseView.php');

class ThankyouView extends BaseView
{

    public function show()
    {
        echo $this->twig->render('customers/thankyou_page.html.twig', [
            
        ]);
    }
}
