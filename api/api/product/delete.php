<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
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
if ($method != 'DELETE') {
    echo response(503, ['desription'=>'method invalid']);
    exit();
}
// include database and object file
include_once '../config/database.php';
include_once '../objects/product.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare product object
$product = new Product($db);

// get product id
$data = json_decode(file_get_contents("php://input"));
// set product id to be deleted
$product->id = $data->id;
// delete the product
if($product->delete()){
    // set response code - 200 ok
    // tell the user
    echo response(200, ['description' => 'success']);
}
// if unable to delete the product
else{
    // set response code - 503 service unavailable
    // tell the user
    echo response(503, ['description' => 'error']);
}
?>
