<?php
require '../app_code/cmncde/connect_pg.php';

//var_dump($_POST);
set_time_limit(3000);
$sendIndvdl = isset($_POST['sendIndvdl']) ? cleanInputData($_POST['sendIndvdl']) : TRUE;
$msgTyp = isset($_POST['msgTypeCombo']) ? cleanInputData($_POST['msgTypeCombo']) : 'Email';
$toAddresses = isset($_POST['toAddresses']) ? cleanInputData($_POST['toAddresses']) : "";
$ccAddresses = isset($_POST['ccAddresses']) ? cleanInputData($_POST['ccAddresses']) : "";
$bccAddresses = isset($_POST['bccAddresses']) ? cleanInputData($_POST['bccAddresses']) : "";
$subjectDetails = isset($_POST['subjectDetails']) ? cleanInputData($_POST['subjectDetails']) : "";
$attachments = isset($_POST['attachments']) ? cleanInputData($_POST['attachments']) : "";
$messageBody = isset($_POST['msgHtml']) ? cleanInputData($_POST['msgHtml']) : "";
$messageBody = urldecode($messageBody);
//print_r($messageBody);
$toAddresses = trim(str_replace("\r\n", "", str_replace(",", ";", $toAddresses)), ";");
$ccAddresses = trim(str_replace("\r\n", "", str_replace(",", ";", $ccAddresses)), ";");
$bccAddresses = trim(str_replace("\r\n", "", str_replace(",", ";", $bccAddresses)), ";");
$attachments = trim(str_replace("\r\n", "", str_replace(",", ";", $attachments)), ";");
$toAddresses = $toAddresses . ";" . $ccAddresses . ";" . $bccAddresses;
$toEmails = explode(";", $toAddresses);

$errMsg = "";
$cntrnLmt = 0;
$mailLst = "";
$emlRes = false;
$failedMails = "";
//string errMsg = "";
for ($i = 0; $i < count($toEmails); $i++) {
    if ($cntrnLmt == 0) {
        $mailLst = "";
    }
    $mailLst .= $toEmails[$i] . ",";

    $cntrnLmt++;
    if ($cntrnLmt == 50 || $i == count($toEmails) - 1 || $sendIndvdl == true || $msgTyp != "Email") {
        if ($msgTyp == "Email") {
            $emlRes = sendEmail(
                trim($mailLst, ","),
                trim($mailLst, ","),
                $subjectDetails,
                $messageBody,
                $errMsg,
                $ccAddresses,
                $bccAddresses,
                $attachments
            );
        } else if ($msgTyp == "SMS") {
            $emlRes = sendSMS(cleanOutputData($messageBody), trim($mailLst, ","), $errMsg);
        } else {
        }
        if ($emlRes == false) {
            $failedMails .= trim($mailLst, ",") . ";";
        }
        $cntrnLmt = 0;
    }
}
if ($failedMails == "") {
    //cmnCde.showMsg("Message Successfully Sent to all Recipients!", 3);
    print_r(json_encode(array(
        'success' => true,
        'message' => 'Sent Successfully',
        'data' => array('src' => 'Message Successfully Sent to all Recipients!'),
        'total' => '1',
        'errors' => ''
    )));
} else {
    //cmnCde.showSQLNoPermsn("Messages to some Recipients Failed!\r\n" + errMsg);
    print_r(json_encode(array(
        'success' => false,
        'message' => 'Error',
        'data' => '',
        'total' => '0',
        'errors' => 'Messages to some Recipients Failed!<br/>' . $errMsg
    )));
}
