<?php
    if(isset($_GET['consulta'])) {
        $diccionario = array("and", "or", "patron(");
        $cadena = strtolower ($_GET['consulta']);
        $arrayDividido = explode(" ", $cadena);
        $arrayDividido = array_values(removeEmptyElements($arrayDividido));
        $tamaño = count($arrayDividido);
        $sentenciaSQL = "";
        $primero = array();
        $tipoAnterior = "elemento";
        $query = array();
    
        for ($i=0; $i < $tamaño; $i++) {
            $variable = substr($arrayDividido[$i], 0, 7);
            switch ($variable) {
                case $diccionario[0]:
                    $tipoAnterior = "and";
                    break;
                case $diccionario[1]:
                    $tipoAnterior = "or";
                    break;
                case $diccionario[2]:
                    $busqueda = dividirCadena($arrayDividido, $i, "patron(", $tamaño);
                    $sentenciaSQL = agregarPalabra($arrayDividido[$i], $tipoAnterior, $busqueda);
                    $tipoAnterior = "elemento";
                    break;
                default:
                    $sentenciaSQL = agregarPalabra($arrayDividido[$i], $tipoAnterior, $sentenciaSQL);
                    array_push($query, $arrayDividido[$i]);
                    $tipoAnterior = "elemento";
                    break;
            }
        }
    } else{
        echo 'error';
    }

    $resultados = DBConection($sentenciaSQL);
    echo json_encode($resultados);

    function DBConection($sentenciaSQL){
        $servername = "localhost";
        $username = "root";
        $password = "admin";
        $dbname = "busquedas";
    
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT link FROM documents WHERE MATCH (descripcion) AGAINST ('$sentenciaSQL' IN BOOLEAN MODE)";
        $result = $conn->query($sql);

        $arrayResultados = array();
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                array_push($arrayResultados, $row);
            }
        }
        $conn->close();
        return $arrayResultados;
    }

    function agregarPalabra($palabra, $tipoAnterior, $sentenciaSQL){
        if (($tipoAnterior == "elemento") || ($tipoAnterior == "or")) {
            $sentenciaSQL = "(" . $sentenciaSQL . "*" . $palabra . "*) ";
        } else {
            $sentenciaSQL = "(" . $sentenciaSQL . "+ *" . $palabra . "*) ";
        }
        return $sentenciaSQL;
    }

    function dividirCadena($arrayDividido, $i, $divisor, $tamaño){
        $palabra = "";
        for ($k=$i; $k < $tamaño; $k++) { 
            $palabra = $palabra . " " . $arrayDividido[$k];
            if(endsWith($arrayDividido[$k], ")")) {
                break;
            }
        }
        $parte1=explode($divisor,$palabra);
        $parte2=explode(')', $parte1[1]);
        $busqueda= $parte2[0];
        return $busqueda;
    }

    function removeEmptyElements(&$element){
        if (is_array($element)) {
            if ($key = key($element)) {
                $element[$key] = array_filter($element);
            }

            if (count($element) != count($element, COUNT_RECURSIVE)) {
                $element = array_filter(current($element), __FUNCTION__);
            }

            $element = array_filter($element);

            return $element;
        } else {
            return empty($element) ? false : $element;
        }
    }

    function endsWith($haystack, $needle) {
        return substr_compare($haystack, $needle, -strlen($needle)) === 0;
    }
?>