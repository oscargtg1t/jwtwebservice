<?php
include "config.php";
include "utils.php";

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-Type: application/json');
header("400 Bad Request");

$dbConn =  connect($db);

//metodo GET para obtener dato de cargamento
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
  if (isset($_GET['id_cargamento'])) {
    $sql = $dbConn->prepare("SELECT * FROM cargamento where id_cargamento=:id_cargamento");
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
      //Mostrar lista de cargamentos
      $sql = $dbConn->prepare("SELECT * FROM cargamento");
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
/*
//metodo POST para Crear un nuevo cargamento
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $input = $_POST;
    $sql = "INSERT INTO cargamento
          (id_cargamento, agricultor_dpi, nombre, tipo_cafe, cant_parcialidad, peso_total, licencia_piloto,
          piloto_nombre, vehiculo_placa, tipo_vehiculo)
          VALUES
          (:id_cargamento, :agricultor_dpi, :nombre, :tipo_cafe, :cant_parcialidad, :peso_total, :licencia_piloto,
          :piloto_nombre, :vehiculo_placa, :tipo_vehiculo)";
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();
    $postId = $dbConn->lastInsertId();
    echo json_encode("200 Ok: cargamento creado");
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
*/

//metodo PUT para Actualizar datos de cargamento
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $input = $_GET;
    $postId = $input['id_cargamento'];
    $fields = getParams($input);

    $sql = "
          UPDATE cargamento
          SET $fields
          WHERE id_cargamento='$postId'
           ";

    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);

    $statement->execute();
    header("201 OK");
    $request =[
      'mensaje' => "201: Datos de cargamneto Actualizado"
    ];
    echo json_encode($request);
    exit();
}
/*
//metodo DELETE para Borrar datos de cargamento
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
	$id = $_GET['id_cargamento'];
  $statement = $dbConn->prepare("DELETE FROM cargamento where id_cargamento=:id_cargamento");
  $statement->bindValue(':id_cargamento', $id);
  $statement->execute();
	header("200 OK");
  $request =[
    'mensaje' => "Datos de cargamento Borrado"
  ];
  echo json_encode($request);
	exit();
}
*/

?>
