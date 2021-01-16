<?php
//Important https://lucene.apache.org/solr/guide/8_7/updating-parts-of-documents.html //Tiene la guia de como actualizar y eliminar
require_once 'vendor/autoload.php';
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
addDocument();
//echo file_get_contents("http://localhost:8983/solr/repository/selectCheck?q=Name%3A*Daniel*");

function addDocument(){ //este va a añadirse
    $ch = curl_init("http://localhost:8983/solr/start/update/extract?commit=true");

    $url = "https://api.drupal.org/api/drupal/vendor%21guzzlehttp%21guzzle%21src%21Exception%21ConnectException.php/function/ConnectException%3A%3AgetResponse/8.0.x";

    $data_string = petitionGuzzle($url);//file_get_contents($data);

    try {
        if ($data_string->getStatusCode() == 200) {
            echo $data_string->getBody();
            /*curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        
            echo curl_exec($ch);*/
        }
    } catch (\Throwable $th) {
        echo $data_string["error"];
    }
}

function petitionGuzzle($url){
    try {
        // Create a client and provide a base URL
        $client = new Client();
        // Create a request with basic Auth
        $response = $client->request('GET', $url);
        // Send the request and get the response
        // echo $response->getStatusCode();
        // echo $response->getBody();
        // var_dump($response->getHeaders());
        return $response;//->getBody();
    } catch (RequestException $e) {
        return $e->getResponse();
    } catch (ConnectException $e) {
        return $e->getHandlerContext();
    }
}
?>