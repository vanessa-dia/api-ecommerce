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
include_once '../objects/product.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare product object
$product = new Product($db);

// set ID property of record to read
$product->id = isset($_GET['id']) ? $_GET['id'] : die();

$stmt = $product->readOne($product->id);
$num = $stmt->rowCount();

if($num>0){
    $products_arr=array();
    $products_arr["records"]=array();
  
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        extract($row);
  
        $product_item=array(
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
    echo response(200, $products_arr);
}
  
else{
    echo response(404, ['description' => 'Not found']);
}
?>
