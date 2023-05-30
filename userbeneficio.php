<?php
include "config.php";
include "utils.php";

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-Type: application/json');
header("400 Bad Request");

$dbConn =  connect($db);


//metodo GET para obtener dato de usuario
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
  if (isset($_GET['dpi_usuario'])) {
      $sql = $dbConn->prepare("SELECT * FROM userbeneficio where dpi_usuario=:dpi_usuario");
      $sql->bindValue(':dpi_usuario', $_GET['dpi_usuario']);
      $sql->execute();
    if ($sql->rowCount() == 0) {
      header("404 Not Found");
      $request =[
        'mensaje' => "El usuario no existe en la base de datos"
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
      //Mostrar lista de usuarios
      $sql = $dbConn->prepare("SELECT * FROM userbeneficio");
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



//metodo POST para registar un usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $input = $_POST;
    $sql = "INSERT INTO userbeneficio
          (dpi_usuario, nombre, correo, pass, rol)
          VALUES
          (:dpi_usuario, :nombre, :correo, :pass, :rol)";
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();
    $postId = $dbConn->lastInsertId();
    echo json_encode("200 Ok: Usuario Registrado");
    if($postId)
    {
      $input['dpi_usuario'] = $postId;
      header("200 OK");
      $request =[
        'mensaje' => "200 Ok"
      ];
      echo json_encode($request);
      exit();
	 }
}

/*
//metodo PUT para Actualizar datos de usuario
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $input = $_GET;
    $postId = $input['dpi_usuario'];
    $fields = getParams($input);

    $sql = "
          UPDATE userbeneficio
          SET $fields
          WHERE dpi_usuario='$postId'
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
	$id = $_GET['dpi_usuario'];
  $statement = $dbConn->prepare("DELETE FROM userbeneficio where dpi_usuario=:dpi_usuario");
  $statement->bindValue(':dpi_usuario', $id);
  $statement->execute();
	header("200 OK");
  $request =[
    'mensaje' => "Datos del usuario Borrado"
  ];
  echo json_encode($request);
	exit();
}
*/

?>
