<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
function response ($code, $data) {
    http_response_code($code);
    return json_encode([
        'code' => $code,
        'data' => $data
    ]);
};
// include database and object files
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/product.php';
  
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$product = new Product($db);

// get keywords
$keywords=isset($_GET["s"]) ? $_GET["s"] : "";
// query products
$stmt = $product->search($keywords);
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
    // products array
    $products_arr=array();
    $products_arr["records"]=array();
  
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $product_item=array(
            "id" => $id,
            "name" => $name,
            "soluong" => $soluong,
            "gia" => $gia,
            "avatar" => $avatar,
            "category" => $category,
            "type" => $type,
            "type_name" => $type_name,
            "content" => html_entity_decode($content),
            "created_at" => $created_at,
            "updated_at" => $updated_at);
        array_push($products_arr["records"], $product_item);
    }
    // show products data
    echo response(200, $products_arr);
}
  
else{
    echo response(404, ["description" => "No products found."]);
}
?>
