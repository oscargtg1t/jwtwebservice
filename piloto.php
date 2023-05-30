<?php
include "config.php";
include "utils.php";
var_dump($_POST);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-Type: application/json');
require 'vendor/autoload.php';
header("400 Bad Request");
use Firebase\JWT\JWT;
$secretKey = 'web-token';
$expirationHours = 1;
$dbConn =  connect($db);

function validateToken($token) {
  try {
      $decoded = JWT::decode($token, $secretKey, ['HS256']);
      return $decoded; // Token is valid
  } catch (Exception $e) {
      return false; // Token is invalid
  }
}

//metodo GET para obtener dato de piloto
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
  $token = $_COOKIE['jwt'] ?? null;
  if ($token && ($decodedToken = validateToken($token))) {
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
} else {
    // Token is invalid or missing, deny access
    http_response_code(401);
    echo 'Unauthorized access I am using JWT.';
}

}


  function generateToken($email, $password) {
    // Validate email and password against your userbeneficio table
    $userId = validateUser($email, $password);
    if (!$userId) {
        return false; // Invalid credentials
    }

    $issuedAt = time();
    $expirationTime = $issuedAt + ($expirationHours * 3600); // Convert hours to seconds
    $payload = array(
        'user_id' => $userId,
        'exp' => $expirationTime
    );
    return JWT::encode($payload, $secretKey);
}

function validateUser($email, $password) {
  // Implement your validation logic against the userbeneficio table
  // Return the user ID if the email and password are valid; otherwise, return false
  // Example:
  if ($email === 'admin' && $password === '123') {
      return 1; // Example user ID
  } else {
      return false;
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Check if the required keys are present in the $_POST array
  if (isset($_POST['email'], $_POST['password'])) {
      $email = $_POST['email'];
      $password = $_POST['password'];

      $token = generateToken($email, $password);
      if ($token) {
          // Token is generated successfully, send it in the response or set it as a secure cookie
          echo 'Login successful.';
          echo 'Token: ' . $token;
      } else {
          // Invalid credentials, return an error response
          http_response_code(401);
          echo 'Invalid credentials.';
      }
  } else {
      // Missing required keys in the $_POST array
      http_response_code(400);
      echo 'Missing parameters.';
  }
}
?>