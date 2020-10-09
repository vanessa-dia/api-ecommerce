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
// include database and object files
include_once '../config/core.php';
include_once '../shared/utilities.php';
include_once '../config/database.php';
include_once '../objects/admin.php';

// utilities
$utilities = new Utilities();

// instantiate database and admin object
$database = new Database();
$db = $database->getConnection();

// initialize object
$admin = new Admin($db);
// query admins
$stmt = $admin->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();

// check if more than 0 record found
if ($num > 0) {
    // admins array
    $admins_arr = array();
    $admins_arr["records"] = array();
    //$admins_arr["paging"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        extract($row);

        $admin_item = array(
            "id" => $id,
            "name" => $name,
            "address" => $address,
            "email" => $email,
            "phone" => $phone,
            "account" => $account,
            "password" => $password,
            "created_at" => $created_at,
            "updated_at" => $updated_at
        );

        array_push($admins_arr["records"], $admin_item);
    }

    $total_rows = $admin->count();
    $page_url = "{$home_url}api/admin/read.php?";
    $paging = $utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    //$admins_arr["paging"] = $paging;

    // set response code - 200 OK
    echo response(200, $admins_arr);
} else {
    echo response(404, ["description" => 'error']);
}
?>
