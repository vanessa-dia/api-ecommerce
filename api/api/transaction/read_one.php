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
    echo response(503, ['description' => 'Method invalid']);
    exit();
}
include_once '../config/database.php';
include_once '../objects/transaction.php';

$database = new Database();
$db = $database->getConnection();

$transaction = new Transaction($db);

$transaction->id = isset($_GET['id']) ? $_GET['id'] : die();

$stmt = $transaction->readOne($transaction->id);
$num = $stmt->rowCount();

if ($num > 0) {
    $transactions_arr = array();
    $transactions_arr["records"] = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $transaction_item = array(
            "id" => $id,
            "amount" => $amount,
            "user_id" => $user_id,
            "note" => html_entity_decode($note),
            "created_at" => $created_at,
            "updated_at" => $updated_at
        );
        array_push($transactions_arr["records"], $transaction_item);
    }
    echo response(200, $transactions_arr);
}
else {
    echo response(404, ['description' => 'error']);
}
?>