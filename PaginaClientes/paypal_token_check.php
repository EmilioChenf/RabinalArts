<?php
include '../../Animated Login/config.php';

$ch = curl_init(PAYPAL_BASE_URL . '/v1/oauth2/token');
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_USERPWD => PAYPAL_CLIENT_ID . ':' . PAYPAL_SECRET,
  CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
  CURLOPT_HTTPHEADER => ['Accept: application/json', 'Accept-Language: en_US'],
]);
$resp = curl_exec($ch);
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err  = curl_error($ch);
curl_close($ch);

header('Content-Type: application/json');
echo json_encode(['http'=>$http,'error'=>$err,'raw'=>json_decode($resp,true)]);
