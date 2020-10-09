<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$home_url = "http://localhost/api/";
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$records_per_page = 10;
$from_record_num = ($records_per_page * $page) - $records_per_page;
date_default_timezone_set('Asia/Manila');
$key = "example_key";
$iss = "http://example.org";
$aud = "http://example.com";
$iat = 1356999524;
$nbf = 1357000000;
?>