<?php // thêm category
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: POST");
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
include_once '../objects/category.php';

$database = new Database();
$db = $database->getConnection();

$category = new Category($db);
$data = json_decode(file_get_contents("php://input"));
if (!empty($data->name)) {
    $category->name = $data->name;
    $category->created_at = date('Y-m-d H:i:s');
    $category->updated_at = date('Y-m-d H:i:s');
    if ($category->create()) {
        echo response(201, ['description' => 'success']);
    } else {
        echo response(503, ['description' => 'Unable']);
    }
} else {
    echo response(400, ['description' => 'Data is incomplete']);
}
?>