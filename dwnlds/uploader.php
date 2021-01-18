<?php

session_start();
//$dwnldfile = $_GET['q'];
header('Expires: Mon, 26 Jul 1990 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

ini_set("display_errors", TRUE);
ini_set("html_errors", TRUE);

require '../app_code/cmncde/connect_pg.php';
if (isset($_SESSION['LAST_ACTIVITY'])) {
    if ((time() - $_SESSION['LAST_ACTIVITY'] > 1800) && $_SESSION['LGN_NUM'] > 0) {
        // last request was more than 50 minates ago
        destroySession();
        if (count($_POST) <= 0) {
            header("Location: index.php");
        } else {
            sessionInvalid();
            exit();
        }
    } else {
        $_SESSION['LAST_ACTIVITY'] = time();
    }
} else {
    $_SESSION['LAST_ACTIVITY'] = time();
}

require '../app_code/cmncde/globals.php';
require '../app_code/cmncde/admin_funcs.php';

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0) {
        $allowed = array('png', 'jpg', 'gif', 'jpeg', 'bmp');
        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            if (!in_array(strtolower($extension), $allowed)) {
                echo '[Error Occurred:Unpermitted File Type!]';
                exit;
            }
            $filename = str_replace("." . $extension, "", str_replace(" ", "_", basename($_FILES["file"]["name"])) . "_" . encrypt1(getRandomTxt(10), $smplTokenWord1)) . "." . $extension;
            $destination_path = getcwd() . DIRECTORY_SEPARATOR . "pem" . DIRECTORY_SEPARATOR;
            $target_path = $destination_path . $filename;
            /*
      if (!file_exists(dirname($target_path))) {
      mkdir('path/to/directory', 0777, true);
      }
     */
            $res = move_uploaded_file($_FILES['file']['tmp_name'], $target_path);
            if ($res) {
                echo $app_url . 'dwnlds/pem/' . $filename;
                exit();
            }
        }
        echo '[Error:Unknown]';
        exit();
    }
}
