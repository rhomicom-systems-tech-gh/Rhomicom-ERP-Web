<?php
header('Expires: Mon, 26 Jul 1990 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

ini_set("display_errors", TRUE);
ini_set("html_errors", TRUE);
require '../app_code/cmncde/connect_pg.php';

header("content-type:application/json");
$arr_content['get_variables'] = implode("|", $_GET);
$arr_content['post_variables'] = implode("|", $_POST);
$arr_content['intro_message'] = "Rhomicom Portal Information Exchange Platform";
echo json_encode($arr_content);
exit();
