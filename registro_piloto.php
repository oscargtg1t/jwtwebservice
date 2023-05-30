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
  if (isset($_GET['licencia'])) {
    $sql = $dbConn->prepare("SELECT * FROM piloto where licencia=:licencia");
    $sql->bindValue(':licencia', $_GET['licencia']);
    $sql->execute();
    if ($sql->rowCount() == 0) {
      header("404 Not Found");
      $request =[
        'mensaje' => "El no. de licencia no existe en la base de datos"
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
      $sql = $dbConn->prepare("SELECT * FROM piloto");
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



//metodo POST para registar un piloto
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $input = $_POST;
    $sql = "INSERT INTO piloto
          (licencia, nombre_piloto)
          VALUES
          (:licencia, :nombre_piloto)";
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();
    $postId = $dbConn->lastInsertId();
    echo json_encode("200 Ok: Piloto Registrado");
    if($postId)
    {
      $input['licencia'] = $postId;
      header("200 OK");
      $request =[
        'mensaje' => "200 Ok"
      ];
      echo json_encode($request);
      exit();
	 }
}

/*
//metodo PUT para Actualizar datos de piloto
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $input = $_GET;
    $postId = $input['licencia'];
    $fields = getParams($input);

    $sql = "
          UPDATE piloto
          SET $fields
          WHERE licencia='$postId'
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


//metodo DELETE para borrar datos del piloto
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
	$id = $_GET['licencia'];
  $statement = $dbConn2->prepare("DELETE FROM piloto where licencia=:licencia");
  $statement->bindValue(':licencia', $id);
  $statement->execute();
	header("200 OK");
  $request =[
    'mensaje' => "Datos de piloto Borrado"
  ];
  echo json_encode($request);
	exit();
}
*/

?>