<?php
//delete transaction => delete all order by id transaction
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
    echo response(503, ['description' => 'Method invalid']);
    exit();
}
include_once '../config/database.php';
include_once '../objects/transaction.php';
  
$database = new Database();
$db = $database->getConnection();
  
$transaction = new Transaction($db);

$data = json_decode(file_get_contents("php://input"));
  
$transaction->id = $data->id;
  
if($transaction->delete()){
    echo response(200, ['description' => 'success']);
}
else{
    echo response(503, ['description' => 'error']);
}
?>
