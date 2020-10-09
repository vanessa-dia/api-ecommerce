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
include_once '../objects/category.php';

// utilities
$utilities = new Utilities();

// instantiate database and category object
$database = new Database();
$db = $database->getConnection();

// initialize object
$category = new Category($db);
// query categorys
$stmt = $category->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();

// check if more than 0 record found
if ($num > 0) {
    // categorys array
    $categorys_arr = array();
    $categorys_arr["records"] = array();
    //$categorys_arr["paging"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        extract($row);

        $category_item = array(
            "id" => $id,
            "name" => $name,
            "created_at" => $created_at,
            "updated_at" => $updated_at
        );

        array_push($categorys_arr["records"], $category_item);
    }

    $total_rows = $category->count();
    $page_url = "{$home_url}api/category/read.php?";
    $paging = $utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    //$categorys_arr["paging"] = $paging;

    // set response code - 200 OK
    echo response(200, $categorys_arr);
} else {
    echo response(404, ["description" => 'error']);
}
?>
