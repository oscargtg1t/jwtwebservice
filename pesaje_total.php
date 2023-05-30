<?php
include "config.php";
include "utils.php";

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-Type: application/json');
header("400 Bad Request");

$dbConn2 =  connect2($db2);

//metodo GET para obtener pesaje total de cargamento 
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
  if (isset($_GET['id_cargamento'])) {
    $sql = $dbConn2->prepare("SELECT * FROM pesaje_total where id_cargamento=:id_cargamento");
    $sql->bindValue(':id_cargamento', $_GET['id_cargamento']);
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
      //Mostrar lista de pilotos
      $sql = $dbConn2->prepare("SELECT * FROM pesaje_total");
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

//metodo POST para registrar un nuevo pesaje
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $input = $_POST;
    $sql = "INSERT INTO pesaje_total
          (id_cargamento, cant_parcialidades, peso_total, tolerancia)
          VALUES
          (:id_cargamento, :cant_parcialidades, :peso_total, :tolerancia)";
    $statement = $dbConn2->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();
    $postId = $dbConn2->lastInsertId();
    echo json_encode("200 Ok: pesaje creado");
    if($postId)
    {
      $input['id_cargamento'] = $postId;
      header("200 OK");
      $request =[
        'mensaje' => "200 Ok"
      ];
      echo json_encode($request);
      exit();
	 }
}



?>
