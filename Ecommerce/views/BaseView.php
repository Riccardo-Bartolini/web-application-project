<?php

require_once(__ROOT__ . '/vendor/autoload.php');
require_once(__ROOT__ . '/utils/SessionUtils.php');

class BaseView
{
    public $twig;

    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $this->twig = new \Twig\Environment($loader);

        $this->twig->addGlobal('isLogged', isLogged());
        if (isset($_SESSION) && isLogged()) $this->twig->addGlobal('session', $_SESSION);

        $this->twig->addGlobal('GET', $_GET);
        $this->twig->addGlobal('pageTitlte', $_SESSION["pageTitlte"]);

        // Registra la funzione "asset"
        $this->twig->addFunction(new \Twig\TwigFunction('asset', function ($path) {
            return '/templates/public/' . ltrim($path, '/');
        }));

    }

}
