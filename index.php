<?php

header("200");

// Permitir solicitudes desde cualquier origen
header('Access-Control-Allow-Origin: *');

// Habilitar los métodos HTTP permitidos
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

// Establecer el tipo de contenido de la respuesta a JSON
header('Content-Type: application/json');

//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("400 Bad Request");

$response = [
    'status' => '200 ok',
    'message' => 'webservice is running'
  ];
  echo json_encode($response);

?>