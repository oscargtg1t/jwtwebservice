<?php

include "config.php";
include "utils.php";
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-Type: application/json');
require_once 'vendor/autoload.php';
header("400 Bad Request");

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
define('key', '2D4A614E635266556A586E3272357538782F413F4428472B4B6250655367566B');
$expirationHours = 1;
$dbConn =  connect($db);

//create a function to validate the token received
function validateToken($token) {
    try {

        $decoded = JWT::decode($token,new Key(key, 'HS256'));
        return $decoded;
    } catch (Exception $e) {
        return false;
    }
}

//metodo GET para obtener dato de piloto
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $token = null;
    $headers = apache_request_headers();

    if (isset($headers['Authorization'])) {
        $authorizationHeader = $headers['Authorization'];
    
        if (preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
            $token = $matches[1];
        }

    // Valida el token
    if ($token&&($decodedToken = validateToken($token))) {
        // Resto del código para manejar la solicitud GET
        // Accede a los datos del token decodificado
        $id = "1";
        $nombre = "Anibal Jocop";

        // Realiza acciones adicionales con los datos

        // Devuelve una respuesta
        header('Content-Type: application/json');
        echo json_encode(array('id' => $id, 'nombre' => $nombre));
        exit();
    } else {
        // El token es inválido o está ausente, denegar el acceso
        http_response_code(401);
        echo 'Acceso no autorizado. El token está ausente o es inválido.';
        exit();
    }
}
}

function generateToken($email, $password) {
    // Validate email and password against your userbeneficio table
    $userId = verificarCredenciales($email, $password);
    if (!$userId) {
        return false; // Invalid credentials
    }
    $expirationHours =1;
    $issuedAt = time();
    $expirationTime = $expirationTime = $issuedAt + 600; // Convert hours to seconds
    $payload = array(
        'user_id' => $userId,
        'exp' => $expirationTime
    );
    return JWT::encode($payload,key,'HS256');
}


function verificarCredenciales($email, $password) {
    // Aquí puedes implementar la lógica para verificar las credenciales
    // por ejemplo, consultando una base de datos o sistema de autenticación
    
    // Devuelve `true` si las credenciales son válidas, o `false` si no lo son
    return ($email === 'usuario@example.com' && $password === '123') ? 1 : false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/jwtwebservice/piloto.php/login') {
    // Obtén los datos enviados por el usuario (supongamos que los envía a través de POST)
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
?>