<?php
header("Access-Control-Allow-Origin: http://localhost/api/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
function response($code, $data)
{
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
include_once '../config/database.php';
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

$user->name = $data->name;
$user->email = $data->email;
$user->address = $data->address;
$user->phone = $data->phone;
$user->Account = $data->Account;
$user->password = $data->password;
$user->created_at = date('Y-m-d H:i:s');
$user->updated_at = date('Y-m-d H:i:s');

if (
    !empty($user->name) &&
    !empty($user->email) &&
    !empty($user->address) &&
    !empty($user->phone) &&
    !empty($user->Account) &&
    !empty($user->password) &&
    $user->create()
) {
    echo response(201, ['description' => 'success']);
}
else {
    echo response(400, ['description' => 'Error']);
}
?>
