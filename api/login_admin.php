<?php
header("Access-Control-Allow-Origin: http://localhost/api/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
function response ($code, $data) {
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
include_once '..\api\api\config\database.php';
include_once '..\api\api\objects\admin.php';

$database = new Database();
$db = $database->getConnection();

$admin = new Admin($db);

$data = json_decode(file_get_contents("php://input"));
$admin->account = $data->account;
$admin->password = $data->password;
$VERIFY = $admin->VERIFY();

if ($VERIFY) {
    http_response_code(200);
    echo json_encode(
            array(
        "message"=> "success",
        "id" => $admin->id,
        "name" => $admin->name
        ));
}
else {
    echo response(401, ['description'=>'Login failed.']);
}
?>
