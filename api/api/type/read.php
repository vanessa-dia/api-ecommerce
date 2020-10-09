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

// utilities
$utilities = new Utilities();

// instantiate database and type object
$database = new Database();
$db = $database->getConnection();

// initialize object
$type = new Type($db);
// query types
$stmt = $type->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();

// check if more than 0 record found
if ($num > 0) {
    // types array
    $types_arr = array();
    $types_arr["records"] = array();
    //$types_arr["paging"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        extract($row);

        $type_item = array(
            "id" => $id,
            "name" => $name,
            "cate_name" => $cate_name,
            "category" => $category,
        );

        array_push($types_arr["records"], $type_item);
    }

    $total_rows = $type->count();
    $page_url = "{$home_url}api/user/read.php?";
    $paging = $utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    //$types_arr["paging"] = $paging;

    // set response code - 200 OK
    echo response(200, $types_arr);
} else {
    echo response(404, ["description" => 'error']);
}
?>
