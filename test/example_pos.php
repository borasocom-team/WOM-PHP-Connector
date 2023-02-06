<?php
require __DIR__ . '/vendor/autoload.php';

DEFINE("POS_PRIVATE_KEY", "/keys/pos1.pem");
DEFINE("POS_PRIVATE_KEY_PASSWORD", "");
DEFINE("POS_ID", "5e74205c5f21bb265a2d26d8");

// Set development domain
\WOM\Config\Domain::SetDomain('dev.wom.social');
\WOM\Logger::Initialize(new \Monolog\Handler\StreamHandler('php://stdout', \Monolog\Logger::DEBUG));

date_default_timezone_set("UTC");

$POS = new \WOM\POS(POS_ID, POS_PRIVATE_KEY, POS_PRIVATE_KEY_PASSWORD);

// Accept health WOM vouchers from Central Europe, not older than 14 days
$filter = \WOM\Filter::Create('H', array(52, -4), array(27, 35), 14);

// Accept all WOM vouchers, with an empty filter
//$filter = \WOM\Filter::Create();

try {
    echo "Creating payment request" . PHP_EOL;

    $values = $POS->RequestPayment(
        1, // Number of WOM vouchers required to perform payment
        'https://example.org', // Pocket confirmation URL, will be opened by Pocket on payment completion, can be null if Pocket should only display payment confirmation on screen
        $filter, // Filter that determines which vouchers are accepted
        null, // Optional Registry confirmation URL, which receives a webhook request from the Registry on payment completion
        null // Optional boolean indicating whether this is a persistent payment (can be performed multiple times) or not
    );

    echo "Payment request created (OTC: {$values['otc']} Pwd: {$values['password']})" . PHP_EOL;

    $status = $POS->GetPaymentStatus($values['otc']);

    echo "Payment has been performed: " . ($status['hasBeenPerformed'] ? 'yes' : 'no') . PHP_EOL;
}
catch(Exception $exception) {
    var_dump($exception->getTraceAsString());
    echo "Failed to request payment :(" . PHP_EOL;
}
