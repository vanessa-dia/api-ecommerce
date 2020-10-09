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
include_once '../objects/admin.php';
$database = new Database();
$db = $database->getConnection();
$admin = new Admin($db);

$admin->id = isset($_GET['id']) ? $_GET['id'] : die();
$stmt = $admin->readOne($admin->id);
$num = $stmt->rowCount();

if ($num > 0) {
    $admins_arr = array();
    $admins_arr["records"] = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $admin_item = array(
            "id" => $id,
            "name" => $name,
            "address" => $address,
            "email" => $email,
            "account" => $account,
            "password" => $password,
            "phone" => $phone,
            "avatar" => "http://localhost/api/api/assets/avt/".$admin->avatar,
            "created_at" => $created_at,
            "updated_at" => $updated_at
        );
        $admins_arr["records"][] = $admin_item;
    }
    echo response(200, $admins_arr);
} else {
    echo response(404, ["description" => "Not found"]);
}
?>