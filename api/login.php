<?php
header("Access-Control-Allow-Origin: http://localhost/api/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
function response ($code, $data) {
    http_response_code($code);
    return json_encode([
        'code' => $code,
        'data' => $data
    ]);
};
$method =  ($_SERVER['REQUEST_METHOD']);
if ($method != 'POST') {
    echo response(503, ['description' => 'Method invalid']);
    exit();
}
include_once 'C:\xampp\htdocs\api\api\config\database.php';
include_once 'C:\xampp\htdocs\api\api\objects\user.php';
 
$database = new Database();
$db = $database->getConnection();
 
$user = new User($db);
 
$data = json_decode(file_get_contents("php://input"));
 
$user->Account = $data->Account;
$acc_exists = $user->accExists();
 
include_once '..\api\api\config\core.php';
include_once '..\api\api\libs\php-jwt-master\src\BeforeValidException.php';
include_once '..\api\api\libs\php-jwt-master\src\ExpiredException.php';
include_once '..\api\api\libs\php-jwt-master\src\SignatureInvalidException.php';
include_once '..\api\api\libs\php-jwt-master\src\JWT.php';
use \Firebase\JWT\JWT;
 
if($acc_exists && password_verify($data->password, $user->password)){
 
    $token = array(
       "iss" => $iss,
       "aud" => $aud,
       "iat" => $iat,
       "nbf" => $nbf,
       "data" => array(
           "id" => $user->id,
           "name" => $user->name,
           "email" => $user->email,
           "address" => $user->address,
           "phone" => $user->phone,
           "Account" => $user->Account
       )
    );
 
    http_response_code(200);
    $jwt = JWT::encode($token, $key);
    echo json_encode(
            array(
        "message"=> "success",
        "id" => $user->id,
        "name" => $user->name,
        "jwt" => $jwt
        ));
}
 
else{
    echo response(401, ["message" => "Login failed."]);
}
?>
