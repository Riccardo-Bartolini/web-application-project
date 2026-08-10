<?php

function isLogged(): bool {
    return isset($_SESSION["email"]);
}