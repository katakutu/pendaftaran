<?php
require_once('config.php');
require 'vendor/autoload.php';
use GuzzleHttp\Client;

$client = new Client();
$client = new GuzzleHttp\Client(['base_uri' => 'http://10.21.71.7:8000']);

//cek data lokal
if($_SERVER['REQUEST_METHOD'] == "POST")
{
	if(empty($_POST['no_ktp'])) {
	$errors[] = 'Kolom No KTP tidak boleh kosong';
    }
    if(nik_exits($_POST['no_ktp'])) {
	$errors[] = 'No KTP telah terdaftar';
    }
    if(empty($_POST['nm_pasien'])) {
	$errors[] = 'Kolom nama tidak boleh kosong';
    }

    if(empty($_POST['email'])) {
    $errors[] = 'Kolom email tidak boleh kosong';
    } else {
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
          $errors[] = 'Format email salah'; 
        }

    }
    if(!empty($errors))
    {
    	return $errors;
    }
    else
    {
		if(!$cekLocalData)
		{
			$response = $client->request('POST', '/dukcapil/get_json/RSUDEF/BYNIK' , [
										'headers' =>
										[
										 'Accept' => 'application/json',
										 'Content-Type' => 'application/json' 
										],
										'json' =>
										[
											  "NIK" => "2171030202819019",
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
			$abc = '';
			$count = 0;
			foreach($data as $hasil)
			{
				$abc .= "'".$hasil."',";
			}
			$abc = substr($abc, 0, -1);
			$query = 'insert into disdukcapil values('.$abc.')';
			query($query);
    	}
    	else
    	{
    		//ambil data lokal
    	}
    }

}
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
?>