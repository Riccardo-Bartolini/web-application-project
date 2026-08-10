<?php

require_once('BaseView.php');

class UserView extends BaseView
{

    public function renderLogin($error_message)
    {
        echo $this->twig->render('login.html.twig', [
            "error_message" => $error_message
        ]);
    }

    public function renderSignup($error_message)
    {
        echo $this->twig->render('signup.html.twig', [
            "error_message" => $error_message
        ]);
    }

    public function renderPrivatePage($ordini)
    {
        echo $this->twig->render('private_page.html.twig', [
            "ordini" => $ordini
        ]);
    }

    public function renderNotifiche($notifiche) {
        echo $this->twig->render('notification.html.twig', [
            "notifiche" => $notifiche
        ]);
    }


}
