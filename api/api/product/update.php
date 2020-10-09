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
include_once '../objects/product.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

$data = json_decode(file_get_contents("php://input"));

$product->id = $data->id;

$product->name = $data->name;
$product->soluong = $data->soluong;
$product->gia = $data->gia;
$product->content = $data->content;

if ($product->update()) {
    echo response(200, ['description' => 'success']);
}
else {
    echo response(503, ['description' => 'error']);
}
?>