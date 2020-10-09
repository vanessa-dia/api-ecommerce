<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
function response($code, $data)
{
    http_response_code($code);
    return json_encode([
        'code' => $code,
        'data' => $data
    ]);
};
$method =  ($_SERVER['REQUEST_METHOD']);
if ($method != 'GET') {
    echo response(503, ['description'=> 'method invalid']);
    exit();
}
// include database and object files
include_once '../config/database.php';
include_once '../objects/category.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare category object
$category = new Category($db);

// set ID property of record to read
$category->id = isset($_GET['id']) ? $_GET['id'] : die();

$stmt = $category->readOne($category->id);
$num = $stmt->rowCount();

if ($num > 0) {

    $categorys_arr = array();
    $cayegorys_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $category_item = array(
            "id" => $id,
            "name" => $name,
            "created_at" => $created_at,
            "updated_at" => $updated_at
        );
        $categorys_arr["records"][] = $category_item;
    }
    echo response(200, $categorys_arr);
} else {
    echo response(404, ['description' => 'Not Found']);
}
?>