<?php
//Important https://lucene.apache.org/solr/guide/8_7/updating-parts-of-documents.html //Tiene la guia de como actualizar y eliminar

echo file_get_contents("http://localhost:8983/solr/repository/select?q=Name%3A*Daniel*");

function addDocument($document){ //este va a añadirse
    $ch = curl_init("http://localhost:8983/solr/repository/update?commit=true");

    $data = array(
                'Name' => 'Roberto',
                'Email' => 'A13000832@correo.uady.mx',
                'Fecha' => '0009-12-21T00:00:00Z',
                'City' => 'Mérida'
    );

    $data_string = json_encode(array($data));          

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

    echo curl_exec($ch);
}
?>