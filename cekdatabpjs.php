<?php

require_once('config.php');
require 'vendor/autoload.php';
use GuzzleHttp\Client;

function getbpjsdata($nik)
{
      ini_set('precision',20);
      date_default_timezone_set('UTC');
      $consID = '22660';
      $secretKey = '5mMA57F0DE';
      $stamp = strval(time()-strtotime('1970-01-01 00:00:00'));
      $data = $consID.'&'.$stamp;
      $signature = hash_hmac('sha256', $data, $secretKey, true);
      $encodedSignature = base64_encode($signature);
      $client = new Client();
      $client = new GuzzleHttp\Client(['base_uri' => 'http://dvlp.bpjs-kesehatan.go.id:8081']);
      $response = $client->request('GET', '/Vclaim-rest/Peserta/nik/'.$nik.'/tglSEP/2018-04-09',[
                            'headers' => [
                                'X-cons-id' => $consID,
                                'X-timestamp' => $stamp,
                                'X-signature' => $encodedSignature,
                                'Accept' => 'application/json',
                                'Content-Type' => 'application/json; charset=utf-8']
                            ]);

      $headers = $response->getHeaders();
      $body = $response->getBody()->getContents();
      $print=json_decode($body,true);
      //Output headers and body for debugging purposes
      $nama = $print['response']['peserta']['nama'];
      $nik  = $print['response']['peserta']['nik'];
      $kartu = $print['response']['peserta']['noKartu'];
      $data = array('nama'=>strtoupper($nama),'nik' => $nik,'kartu' => $kartu);
      return $data;
}
echo "<pre>";
$ab = getbpjsdata(2171092104889009);
print_r($ab);
echo "</pre>";
?>