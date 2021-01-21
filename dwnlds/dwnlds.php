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


$filename = decrypt((isset($_GET['q']) ? $_GET['q'] : "unknown"), $smplTokenWord1);
$error = false;
// required for IE, otherwise Content-disposition is ignored
if (ini_get('zlib.output_compression')) {
    ini_set('zlib.output_compression', 'Off');
}

// addition by Jorg Weske
$file_extension = strtolower(substr(strrchr($filename, "."), 1));

if ($filename == "") {
    $error = true;
    //exit($error);
} elseif (!file_exists($filename)) {
    $error = true;
    //exit($error);
}

if ($file_extension != 'html') {
    $nwDwnldFileNm = "dwnlds/tmp/" . encrypt1(basename($filename), $smplTokenWord1) . "." . $file_extension;
} else {
    $rpt_src1 = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/amcharts_2100/images/";
    $rpt_dest1 = $fldrPrfx . "dwnlds/amcharts_2100/images/";
    if ($rpt_dest1 != "") {
        recurse_copy($rpt_src1, $rpt_dest1);
    }
    $nwDwnldFileNm = "dwnlds/amcharts_2100/samples/" . encrypt1(basename($filename), $smplTokenWord1) . "." . $file_extension;
}

if (file_exists($filename) && !is_dir($filename)) {
    copy($filename, $fldrPrfx . $nwDwnldFileNm);
    $filename = $fldrPrfx . $nwDwnldFileNm;
    $curFiles = $_SESSION['CUR_RPT_FILES'];
    $_SESSION['CUR_RPT_FILES'] = $curFiles . "$filename" . "|";
}
if ($error == false) {
    $nwAppUrl = $app_url; // str_replace("self/", "", $app_url);
    echo "<script type=\"text/javascript\"> window.location='$nwAppUrl" . "$nwDwnldFileNm'; </script>"
        . "<a href=\"" . $nwAppUrl . $nwDwnldFileNm . "\">" . $nwAppUrl . "" . $nwDwnldFileNm . "</a>";
    /* switch ($file_extension) {
      case "pdf": $ctype = "application/pdf";
      break;
      case "exe": $ctype = "application/octet-stream";
      break;
      case "zip": $ctype = "application/zip";
      break;
      case "doc":
      case "docx": $ctype = "application/msword";
      break;
      case "xls":
      case "xlsx": $ctype = "application/vnd.ms-excel";
      break;
      case "ppt":
      case "pptx": $ctype = "application/vnd.ms-powerpoint";
      break;
      case "gif": $ctype = "image/gif";
      break;
      case "png": $ctype = "image/png";
      break;
      case "ico": $ctype = "image/ico";
      break;
      case "jpeg":
      case "jpg": $ctype = "image/jpg";
      break;
      default: $ctype = "application/octet-stream";
      // header('Content-Type: application/force-download');
      //binary
      }
      header("Pragma: public"); // required
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Cache-Control: private", false); // required for certain browsers
      header('Content-Description: File Transfer');
      header("Content-Type: $ctype");
      //change, added quotes to allow spaces in filenames, by Rajkumar Singh
      header("Content-Disposition: attachment; filename=\"" . basename($filename) . "\";");
      header("Content-Transfer-Encoding: binary");
      header("Content-Length: " . filesize($filename));
      readfile("$filename");
      exit(); */
} else {
    echo "File not Available!";
}
