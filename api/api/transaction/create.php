<?php
//tạo transaction, tạo product -> order: (order thuộc về transaction, order gồm array([product, amount]))
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
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
    echo response(503, ['description' => 'Method invalid']);
    exit();
}
include_once '../config/database.php';

include_once '../objects/order.php';
include_once '../objects/transaction.php';
include_once '../objects/product.php';

$database = new Database();
$db = $database->getConnection();

$order = new Order($db);
$transaction = new Transaction($db);
$productDb = new Product($db);

$data = json_decode(file_get_contents("php://input"));
if (
    !empty($data->products) &&
    isset($data->money) &&
    isset($data->user_id)
) {
    $transaction->user_id = $data->user_id;
    $transaction->amount = $data->money;
    $transaction->note = $data->note;
    try {
        $transaction->create();
        $order->transaction_id = $transaction->getLastTranByUser($transaction->user_id);
        $products = $data->products;
        $productsLength = count($products);
        $count = 0;
        foreach ($products as $key => $product) {
            $order->product_id = $product->product_id;
            $order->soluong = $product->amount;
            $order->gia = $productDb->getGiaById($order->product_id);
            if ($order->create()) $count++;
        }
        if ($count == $productsLength) {
            echo response(201, ['description' => 'Success']);
        }
    } catch (\Throwable $th) {
        echo $th;
        echo response(503, ['description' => 'Unable']);
    }
} else {
    echo response(503, ['description' => 'Error']);
}
?>