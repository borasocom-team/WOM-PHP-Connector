<?php

require __DIR__ . '/vendor/autoload.php';

DEFINE("CLIENT_PUBLIC_KEY", "keys/registry.pub");
DEFINE("CLIENT_PRIVATE_KEY", "keys/pos1.pem");
DEFINE("CLIENT_PRIVATE_KEY_PASSWORD", "");
DEFINE("CLIENT_ID", "5e74205c5f21bb265a2d26d8");

date_default_timezone_set("UTC");


$POS = new \WOM\POS(CLIENT_ID, CLIENT_PUBLIC_KEY, CLIENT_PRIVATE_KEY, CLIENT_PRIVATE_KEY_PASSWORD);

$filter = \WOM\Filter::Create('H', array(46.0, -17.0), array(12.0, 160.0), 14);


// Request Vouchers
$otc = null;
$password = null;


try{
    $POS->RequestPayment(100,
        'http://google.it',
        $filter,
        'http://libero.it',
        False,
        null,
        $password,
        $otc);

    echo "Otc: {$otc} Password:{$password}";
}catch(Exception $exception) {

    echo "No payment generated :(";
}

