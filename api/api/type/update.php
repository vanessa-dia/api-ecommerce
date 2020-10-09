<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
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
if ($method != 'PUT') {
    echo response(503, ['description' => 'Method invalid']);
    exit();
}
include_once '../config/database.php';
include_once '../objects/type.php';

$database = new Database();
$db = $database->getConnection();

$type = new Type($db);

$data = json_decode(file_get_contents("php://input"));

$type->id = $data->id;

$type->name = $data->name;
//$type->category = $data->category;

if ($type->update()) {
    echo response(200, ['description' => 'success']);
}
else {
    echo response(503, ['description' => 'error']);
}
?>