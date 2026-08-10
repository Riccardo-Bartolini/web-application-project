<?php
require_once(__ROOT__ . '/views/UserView.php');
require_once(__ROOT__ . '/models/UserModel.php');
require_once(__ROOT__ . '/models/OrdineModel.php');
require_once(__ROOT__ . '/models/OrdineProductModel.php');
require_once(__ROOT__ . '/models/NotificaModel.php');

class UserController {

    public static function login() {
        $error_message = false;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $email = $_POST["email"];
            $password = $_POST["password"];
            $user = UserModel::validateUser($email, $password);

            if (isset($user)) {
                $_SESSION['email'] = $user["email"];
                $_SESSION['nome'] = $user["nome"];
                $_SESSION['cognome'] = $user["cognome"];
                $_SESSION['venditore'] = $user["venditore"] === 1;
                $_SESSION['indirizzo'] = $user["indirizzo"];
                $_SESSION['citta'] = $user["citta"];
                session_write_close(); 
                header('Location: /');
            } else {
                $error_message = true;
            }
            
        }
        
        $view = new UserView();
        $view->renderLogin($error_message);
    }

    private static function checkSignupErrors() {
        $error_message = array(
            "password_diverse" => false,
            "email_in_uso" => false,
            "password_corta" => false
        );
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST["password"] !== $_POST["password2"]) {
                $error_message["password_diverse"] = true;
            }
            if (UserModel::get($_POST["email"]) != null) {
                $error_message["email_in_uso"] = true;
            }
            if (strlen($_POST["password"]) <= 6) {
                $error_message["password_corta"] = true;
            }
        }
        return $error_message;
    }
    public static function signup() {
        $error_message = UserController::checkSignupErrors();
        
        if (!in_array(true, $error_message, true) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = new UserModel(array(
                "id" => null,
                "email" => $_POST["email"],
                "password" => password_hash($_POST["password"], PASSWORD_DEFAULT),
                "nome" => $_POST["name"],
                "cognome" => $_POST["cognome"],
                "venditore" => (int)$_POST["venditore"],
                "indirizzo" => $_POST["indirizzo"],
                "citta" => $_POST["citta"]
            ));
            try {
                $id = $user->save();
                $notification = new NotificheModel(array(
                    "id" => 0,
                    "testo" => "Benvenuto in Eterna Eleganza!",
                    "data" => date("Y-m-d"),
                    "user_id" => $id,
                    "letta" => 0
                ));
                $notification->save();

                header("Location: /login");
            } catch (Exception $err) {
                echo $err;
            }
        }
        
        $view = new UserView();
        $view->renderSignup($error_message);
    }

    public static function privateArea() {
        $ordini = null;
        if (isset($_SESSION["email"])){
            $user = UserModel::get($_SESSION["email"]);
            $ordini = OrdineModel::where(array(
                "user_id" => $user->id
            ));
            foreach($ordini as $ordine) {
                $ordine->setProducts();
            }
        } else {
            header("Location: /");
        }
        
        $view = new UserView();
        $view->renderPrivatePage($ordini);
    }

    public static function renderNotifiche() {
        $notifiche = array();
        if (isset($_SESSION["email"])){
            $user = UserModel::get($_SESSION["email"]);
            if ($user != null) {
                $notifiche = NotificheModel::where(array(
                    "user_id" => $user->id
                ));
            }
        }
        
        $view = new UserView();
        $view->renderNotifiche($notifiche);
    }

    public static function signReadNotifica() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ottieni i dati dalla richiesta
            $input = json_decode(file_get_contents('php://input'), true);
        
            if (isset($input['id']) && isset($input['letta'])) {
                $id = $input['id'];
                $letta = $input['letta'];
        
                $notifica = NotificheModel::get($id);
                $notifica->letta = $letta;
                try {
                    $id = $notifica->save();
                } catch (Exception $err) {
                    echo $err;
                }
                // Rispondi con successo
                echo json_encode(['success' => true, 'id' => $id]);
                exit;
            }
        
            // Rispondi con errore
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Dati non validi']);
            exit;
        }
    }
}