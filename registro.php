<?php
include "config.php";
include "utils.php";

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-Type: application/json');
header("400 Bad Request");

$dbConn =  connect($db);


//metodo GET para obtener dato de agricultor
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
  if (isset($_GET['dpi_agricultor'])) {
      $sql = $dbConn->prepare("SELECT * FROM agricultores where dpi_agricultor=:dpi_agricultor");
      $sql->bindValue(':dpi_agricultor', $_GET['dpi_agricultor']);
      $sql->execute();
    if ($sql->rowCount() == 0) {
      header("404 Not Found");
      $request =[
        'mensaje' => "El Agricultor no existe en la base de datos"
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
      //Mostrar lista de agricultor
      $sql = $dbConn->prepare("SELECT * FROM agricultores");
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



//metodo POST para registar un agricultor
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $input = $_POST;
    $sql = "INSERT INTO agricultores
          (dpi_agricultor, nombre_agricultor, correo_agricultor, pass)
          VALUES
          (:dpi_agricultor, :nombre_agricultor, :correo_agricultor, :pass)";
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();
    $postId = $dbConn->lastInsertId();
    echo json_encode("200 Ok: Agricultor Registrado");
    if($postId)
    {
      $input['id_agricultor'] = $postId;
      header("200 OK");
      $request =[
        'mensaje' => "200 Ok"
      ];
      echo json_encode($request);
      exit();
	 }
}

/*
//metodo PUT para Actualizar datos de agricultor
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $input = $_GET;
    $postId = $input['dpi_agricultor'];
    $fields = getParams($input);

    $sql = "
          UPDATE agricultores
          SET $fields
          WHERE dpi_agricultor='$postId'
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


//metodo DELETE 
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
	$id = $_GET['dpi_agricultor'];
  $statement = $dbConn->prepare("DELETE FROM agricultores where dpi_agricultor=:dpi_agricultor");
  $statement->bindValue(':dpi_agricultor', $id);
  $statement->execute();
	header("200 OK");
  $request =[
    'mensaje' => "Datos del Agricultor Borrado"
  ];
  echo json_encode($request);
	exit();
}
*/

?>
