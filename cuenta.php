<?php
include "config.php";
include "utils.php";

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-Type: application/json');
header("400 Bad Request");

$dbConn =  connect($db);

//metodo GET para obtener estado de cuenta de cargamento 
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
  if (isset($_GET['cargamento_id'])) {
    $sql = $dbConn->prepare("SELECT * FROM cuenta where cargamento_id=:cargamento_id");
    $sql->bindValue(':cargamento_id', $_GET['cargamento_id']);
    $sql->execute();
    if ($sql->rowCount() == 0) {
      header("404 Not Found");
      $request =[
        'mensaje' => "El no. de cargamento no existe en la base de datos"
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
      //Mostrar lista de estados
      $sql = $dbConn->prepare("SELECT * FROM cuenta");
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

//metodo para crear estado de cuenta de cargamento
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $input = $_POST;
    $sql = "INSERT INTO cuenta
          (cargamento_id, mensaje)
          VALUES
          (:cargamento_id, :mensaje)";
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();
    $postId = $dbConn5->lastInsertId();
    echo json_encode("200 Ok: Estado de cuenta creado");
    if($postId)
    {
      $input['cargamento_id'] = $postId;
      header("200 OK");
      $request =[
        'mensaje' => "200 Ok"
      ];
      echo json_encode($request);
      exit();
	 }
}


if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $input = $_GET;
    $postId = $input['cargamento_id'];
    $fields = getParams($input);

    $sql = "
          UPDATE cuenta
          SET $fields
          WHERE cargamento_id='$postId'
           ";

    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);

    $statement->execute();
    header("201 OK");
    $request =[
      'mensaje' => "201: Estado de cuenta Actualizado"
    ];
    echo json_encode($request);
    exit();
}

/*
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
	$id = $_GET['cargamento_id'];
  $statement = $dbConn5->prepare("DELETE FROM cuenta where cargamento_id=:cargamento_id");
  $statement->bindValue(':cargamento_id', $id);
  $statement->execute();
	header("200 OK");
  $request =[
    'mensaje' => "Estado de cuenta Borrado"
  ];
  echo json_encode($request);
	exit();
}
*/
?>
