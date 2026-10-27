<?php

$upiuid      = "54847";
$token       = "b3eb29-51c8f0-dacce6-d39ac0-faf954";
$orderId     = "ORDS" . time();
$txnAmount   = "100";
$txnNote     = "Test";
$callbackUrl = "http://localhost/a4Digital/public/franchise/payment-callback";

$checksumString =
    $upiuid .
    $token .
    $orderId .
    $txnAmount .
    $txnNote .
    $callbackUrl;

$checksum = md5($checksumString);

$data = [
    "upiuid"      => $upiuid,
    "token"       => $token,
    "orderId"     => $orderId,
    "txnAmount"   => $txnAmount,
    "txnNote"     => $txnNote,
    "callback_url" => $callbackUrl,
    "checksum"    => $checksum
];

$ch = curl_init("https://apnacsc.online/order/process");
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => http_build_query($data),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_TIMEOUT        => 30,
]);
$response = curl_exec($ch);
curl_close($ch);

echo "RESPONSE: " . $response . "\n";
