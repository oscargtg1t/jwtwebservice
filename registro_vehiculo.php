<?php
include "config.php";
include "utils.php";

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-Type: application/json');
header("400 Bad Request");

$dbConn =  connect($db);


//metodo GET para obtener dato de piloto
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
  if (isset($_GET['placa'])) {
      $sql = $dbConn->prepare("SELECT * FROM vehiculo where placa=:placa");
      $sql->bindValue(':placa', $_GET['placa']);
      $sql->execute();
    if ($sql->rowCount() == 0) {
      header("404 Not Found");
      $request =[
        'mensaje' => "El no. de placa no existe en la base de datos"
      ];
      echo json_encode($request);
      exit();
    } else {
      header("200 OK");
      echo json_encode($sql->fetch(PDO::FETCH_ASSOC));
      exit();
    }
  }
    else {
      //Mostrar lista de pilotos
      $sql = $dbConn->prepare("SELECT * FROM vehiculo");
      $sql->execute();
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      header("200 OK");
      echo json_encode( $sql->fetchAll()  );
      header("404 not found");
      //$request =[
      //  'mensaje' => "404: No autorizado"
      //];
      //echo json_encode($request);
      exit();
	}
}

//metodo POST para registar un vehiculo
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $input = $_POST;
    $sql = "INSERT INTO vehiculo
          (placa, tipo_vehiculo)
          VALUES
          (:placa, :tipo_vehiculo)";
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();
    $postId = $dbConn->lastInsertId();
    echo json_encode("200 Ok: vehiculo Registrado");
    if($postId)
    {
      $input['placa'] = $postId;
      header("200 OK");
      $request =[
        'mensaje' => "200 Ok"
      ];
      echo json_encode($request);
      exit();
	 }
}

/*
//metodo PUT para Actualizar datos de vehiculo
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $input = $_GET;
    $postId = $input['placa'];
    $fields = getParams($input);

    $sql = "
          UPDATE vehiculo
          SET $fields
          WHERE placa='$postId'
           ";

    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);

    $statement->execute();
    header("201 OK");
    $request =[
      'mensaje' => "201: Datos Actualizados"
    ];
    echo json_encode($request);
    exit();
}


//metodo DELETE para borrar datos de vehiculo
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
	$id = $_GET['placa'];
  $statement = $dbConn->prepare("DELETE FROM vehiculo where placa=:placa");
  $statement->bindValue(':placa', $id);
  $statement->execute();
	header("200 OK");
  $request =[
    'mensaje' => "Datos de vehiculo Borrado"
  ];
  echo json_encode($request);
	exit();
}
*/

?>