<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

function response ($code, $data) {
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
include_once '../config/database.php';
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);
$user->id = isset($_GET['id']) ? $_GET['id'] : die();

$stmt = $user->readOne($user->id);
$num = $stmt->rowCount();

if ($num > 0) {

    $users_arr = array();
    $users_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $user_item = array(
            "id" => $id,
            "name" => $name,
            "email" => $email,
            "address" => $address,
            "phone" => $phone,
            "Account" => $Account,
            "password" => $password,
            "avatar" => "http://localhost/api/api/assets/avt/".$user->avatar,
            "created_at" => $created_at,
            "updated_at" => $updated_at
        );
        $users_arr["records"][] = $user_item;
    }
    echo response(200, $users_arr);
} else {
    echo response(404, ["description" => "Not found"]);
}
?>