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
include_once '../objects/type.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare type object
$type = new Type($db);

// set ID property of record to read
$type->id = isset($_GET['id']) ? $_GET['id'] : die();

$stmt = $type->readOne($type->id);
$num = $stmt->rowCount();
// check if more than 0 record found
if($num>0){

    $types_arr=array();
    $types_arr["records"]=array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);  
        $type_item=array(
            "id" => $id,
            "name" => $name,
            "cate_name" => $cate_name,
            "category" => $category);
        array_push($types_arr["records"], $type_item);
    }
    echo response(200, $types_arr);
}
else{
    echo response(404, ['description' => 'error']);
}
?>