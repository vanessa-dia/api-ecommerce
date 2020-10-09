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
    echo response(503, ['description' => 'method invalid']);
    exit();
}
include_once '../config/core.php';
include_once '../shared/utilities.php';
include_once '../config/database.php';
include_once '../objects/order.php';

$utilities = new Utilities();
  
$database = new Database();
$db = $database->getConnection();
  
$order = new Order($db);

$order->transaction_id = isset($_GET['trans']) ? $_GET['trans'] : die();

$stmt = $order->read_detail($order->transaction_id, $from_record_num, $records_per_page);
$num = $stmt->rowCount();

if($num>0){
    $orders_arr=array();
    $orders_arr["records"]=array();
  
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $order_item=array(
            "id" => $id,
            "transaction_id" => $transaction_id,
            "product_id" => $product_id,
            "product" => $product,
            "soluong" => $soluong,
            "gia" => $gia,
            "created_at" => $created_at
        );  
        array_push($orders_arr["records"], $order_item);
    }
    $total_rows = $order->count();
    $page_url = "{$home_url}api/transaction/read_detail.php?";
    $paging = $utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
        echo response(200, $orders_arr);
}
else{
    echo response(404, ['description' => 'Not found']);
}
?>