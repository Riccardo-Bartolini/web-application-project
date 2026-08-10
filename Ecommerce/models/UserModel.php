<?php

require_once('BaseModel.php');


class UserModel extends BaseModel
{
    public static string $nome_tabella = 'User';
    protected array $_fields = [
        "id",
        "email",
        "password",
        "nome",
        "cognome",
        "venditore",
        "indirizzo",
        "citta"
    ];

    public static function validateUser($email, $password) {
        $query = "SELECT * FROM " . static::$nome_tabella  . " WHERE email=:email";
        $sth = DB::get()->prepare($query);
        $sth->execute([
            'email' => $email,
        ]);
        $user = $sth->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }

    public static function get($email)
    {
        $query = "SELECT * FROM " . static::$nome_tabella  . " WHERE email=:email";
        $sth = DB::get()->prepare($query);
        $sth->execute([
            'email' => $email,
        ]);
        $row = $sth->fetch();

        return  $row != null ? new static($row) : null;
    }

}