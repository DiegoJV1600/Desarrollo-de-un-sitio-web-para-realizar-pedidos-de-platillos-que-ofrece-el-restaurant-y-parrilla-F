<?php
    define("CLIENT_ID", "AXmBoGh832SBJwcZIhJZGStHetmxkWHPBCKPgU2zJ0nFTzN1igxtzNH-WgDMwVNWze_RhUA2MAaYnfgR");
    define("CURRENCY", "MXN");
    define("KEY_TOKEN", "APR.wqc-354*");
    define("MONEDA", "$");

    session_start();

    $num_cart = 0;

    if(isset($_SESSION['carrito']['productos']))
    {
        $num_cart = count($_SESSION['carrito']['productos']);
    }
?>