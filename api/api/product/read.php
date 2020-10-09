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
include_once '../objects/product.php';

// utilities
$utilities = new Utilities();

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$product = new Product($db);
// query products
$stmt = $product->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();

// check if more than 0 record found
if ($num > 0) {
    // products array
    $products_arr = array();
    $products_arr["records"] = array();
    //$products_arr["paging"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        extract($row);

        $product_item = array(
            "id" => $id,
            "name" => $name,
            "soluong" => $soluong,
            "gia" => $gia,
            "avatar" => $avatar,
            "category" => $category,
            "cate_name" => $cate_name,
            "type" => $type,
            "type_name" => $type_name,
            "content" => html_entity_decode($content),
            "created_at" => $created_at,
            "updated_at" => $updated_at
        );

        array_push($products_arr["records"], $product_item);
    }

    $total_rows = $product->count();
    $page_url = "{$home_url}api/product/read.php?";
    $paging = $utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    //$products_arr["paging"] = $paging;

    // set response code - 200 OK
    echo response(200, $products_arr);
} else {
    echo response(404, ["description" => 'error']);
}
?>
