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
include_once '../config/core.php';
include_once '../shared/utilities.php';
include_once '../config/database.php';
include_once '../objects/transaction.php';

$utilities = new Utilities();

$database = new Database();
$db = $database->getConnection();

$transaction = new Transaction($db);

$stmt = $transaction->readPaging($from_record_num, $records_per_page);
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
    $total_rows = $transaction->count();
    $page_url = "{$home_url}api/transaction/read.php?";
    $paging = $utilities->getPaging($page, $total_rows, $records_per_page, $page_url);

    echo response(200, $transactions_arr);
}
else {
    echo response(404, ['description' => 'error']);
}
?>
