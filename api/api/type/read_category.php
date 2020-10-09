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
include_once '../config/core.php';
include_once '../shared/utilities.php';
include_once '../config/database.php';
include_once '../objects/type.php';
$utilities = new Utilities();

// instantiate database and type object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$type = new Type($db);

$type->category = isset($_GET['category']) ? $_GET['category'] : die();

// read types will be here
// query types
$stmt = $type->read_category($type->category, $from_record_num, $records_per_page);
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){
    // types array
    $types_arr=array();
    $types_arr["records"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        extract($row);
  
        $type_item=array(
            "id" => $id,
            "name" => $name,
            "category" => $category,
            "cate_name" => $cate_name
        );
  
        array_push($types_arr["records"], $type_item);
    }
    $total_rows = $type->count();
    $page_url = "{$home_url}api/type/read_category.php?";
    $paging = $utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    echo response(200, $types_arr);
}
else{
    echo response(404, ['description' => 'Not found']);
}

?>