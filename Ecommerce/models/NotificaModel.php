<?php
require_once('BaseModel.php');

class NotificheModel extends BaseModel
{
    public static string $nome_tabella = 'Notifiche';
    
    protected array $_fields = [
        "id",
        "testo",
        "data",
        "user_id",
        "letta"
    ];
   
}
