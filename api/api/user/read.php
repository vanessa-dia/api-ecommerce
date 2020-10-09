<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
function response($code, $data)
{
    http_response_code($code);
    return json_encode([
        'code' => $code,
        'data' => $data
    ]);
};
include_once '../config/database.php';
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$stmt = $user->readAll();
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
            "avatar" => $avatar,
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