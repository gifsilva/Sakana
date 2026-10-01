<?php
    require_once __DIR__ . "/helpers.php";

    $SECURITY_CONFIG = [
        "session" => [
            "lifetime" => 0,
            "path" => "/",
            "secure" => (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off"),
            "httponly" => true,
            "samesite" => "Lax"
        ],
        "password" => [
            "algo" => PASSWORD_DEFAULT,
            "cost" => 10
        ]
    ];
?>