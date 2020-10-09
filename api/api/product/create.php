<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Headers: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
function response($code, $data)
{
    http_response_code($code);
    return json_encode([
        'code' => $code,
        'data' => $data
    ]);
};
$method =  ($_SERVER['REQUEST_METHOD']);
if ($method != 'POST') {
    echo response(503, ['description' => 'method invalid']);
    exit();
}
// get database connection
include_once '../config/database.php';

// instantiate product object
include_once '../objects/product.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

// get posted data
$data = array();
$post = $_POST['form'];
if (!is_string($post)) {
    $data = $post ;
} else {
    parse_str($post, $data);
};
// make sure data is not empty
if (
    !empty($data['name']) &&
    !empty($data['soluong']) &&
    !empty($data['gia']) &&
    !empty($data['category']) &&
    !empty($data['type']) &&
    !empty($data['content'])
) {

    $avatar = date("hisa") . basename($_FILES["avatar"]["name"]);
    $target_dir = "../assets/" . $avatar;

    if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_dir)) {
        
        $product->name = $data['name'];
        $product->soluong = $data['soluong'];
        $product->gia = $data['gia'];
        $product->avatar =  $avatar;
        $product->category = $data['category'];
        $product->type = $data['type'];
        $product->content = $data['content'];
        $product->created_at = date('Y-m-d H:i:s');
        $product->updated_at = date('Y-m-d H:i:s');
        if ($product->create()) {
            echo response(201, ['description' => 'success']);
        }
    } else {
        echo response(503, ['description' => 'error']);
    }
    // if unable to create the product, tell the user
} else {
    echo response(400, ['description' => 'bab request']);
}
?>
