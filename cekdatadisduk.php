<?php

  require_once('config.php');
require 'vendor/autoload.php';
use GuzzleHttp\Client;
$client = new Client();
$client = new GuzzleHttp\Client(['base_uri' => 'http://10.21.71.7:8000']);
function cekLocalData($nik)
{
    $sql = "SELECT NIK FROM disdukcapil WHERE NIK = '$nik' ";
    $result = query($sql);
    if(num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}


function getLocalData($nik)
{
    $sql = "SELECT * FROM disdukcapil WHERE NIK = '$nik' ";
    $result = query($sql);
    $row = fetch_array($result);
    return $row;
}

function getdisdukdata($nik)
{
  $response = $client->request('POST', '/dukcapil/get_json/RSUDEF/BYNIK' , [
                    'headers' =>
                    [
                     'Accept' => 'application/json',
                     'Content-Type' => 'application/json' 
                    ],
                    'json' =>
                    [
                      "NIK" => $nik,
                      "user_id" => "dev02",
                      "ip_users" => "192.168.2.1",
                      "password" => "123456"
                    ]
                    ] );

      $headers = $response->getHeaders();
      $body =$response->getBody();
      $print=json_decode($body,true);
      //Output headers and body for debugging purposes
      $data = $print['content'][0];
      
      if(sizeof($data) < 25)
      {
          return false;
      } 
      else
      {
        $abc = '';
        $count = 0;
        foreach($data as $hasil)
        {
          $abc .= "'".$hasil."',";
        }
        $abc = substr($abc, 0, -1);
         $query = 'insert into disdukcapil values('.$abc.')';
         query($query);
         return true;
     
      }
    }
  ?>