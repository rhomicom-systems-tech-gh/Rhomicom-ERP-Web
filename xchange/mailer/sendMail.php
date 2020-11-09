<?php
require '../../app_code/cmncde/connect_pg.php';
require '../../app_code/cmncde/globals.php';
$_inRhData = json_decode(file_get_contents('php://input'), true);
//echo "Dumping _POST:Cnt=>".count($data);
//print_r($data);
//var_dump($_inRhData);
set_time_limit(3000);
$sendIndvdl = isset($_inRhData['sendIndvdl']) ? cleanInputData($_inRhData['sendIndvdl']) : TRUE;
$msgTyp = isset($_inRhData['msgTypeCombo']) ? cleanInputData($_inRhData['msgTypeCombo']) : 'Email';
$inToken = isset($_inRhData['inToken']) ? cleanInputData($_inRhData['inToken']) : '';
$inRunID = isset($_inRhData['inRunID']) ? (float)cleanInputData($_inRhData['inRunID']) : -1;
$toAddresses = isset($_inRhData['toAddresses']) ? cleanInputData($_inRhData['toAddresses']) : "";
$ccAddresses = isset($_inRhData['ccAddresses']) ? cleanInputData($_inRhData['ccAddresses']) : "";
$bccAddresses = isset($_inRhData['bccAddresses']) ? cleanInputData($_inRhData['bccAddresses']) : "";
$subjectDetails = isset($_inRhData['subjectDetails']) ? cleanInputData($_inRhData['subjectDetails']) : "";
$attachments = isset($_inRhData['attachments']) ? cleanInputData($_inRhData['attachments']) : "";
$messageBody = isset($_inRhData['msgHtml']) ? cleanInputData($_inRhData['msgHtml']) : "";
$messageBody = urldecode($messageBody);
//print_r($messageBody);
$toAddresses = trim(str_replace("\r\n", "", str_replace(",", ";", $toAddresses)), ";");
$ccAddresses = trim(str_replace("\r\n", "", str_replace(",", ";", $ccAddresses)), ";");
$bccAddresses = trim(str_replace("\r\n", "", str_replace(",", ";", $bccAddresses)), ";");
$attachments = trim(str_replace("\r\n", "", str_replace(",", ";", $attachments)), ";");
$toEmails = explode(";", $toAddresses);

$errMsg = "";
$cntrnLmt = 0;
$mailLst = "";
$mailLstCnt = 0;
$emlRes = false;
$failedMails = "";
//string errMsg = "";
$dbToken = getGnrlRecNm("rpt.rpt_report_runs", "rpt_run_id", "run_date||run_by", $inRunID);
if ($inToken === $dbToken) {
    for ($i = 0; $i < count($toEmails); $i++) {
        if ($cntrnLmt == 0) {
            $mailLst = "";
        }
        $mailLst .= $toEmails[$i] . ",";

        $cntrnLmt++;
        if (($cntrnLmt == 50 || $i == count($toEmails) - 1 || $sendIndvdl == true) && trim($mailLst, ",") != "") {
            //echo "Call SendMail Func with:".trim($mailLst, ",");
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
            if ($emlRes == false) {
                $failedMails .= trim($mailLst, ",") . ";";
            } else {
                $mailLstCnt++;
            }
            $cntrnLmt = 0;
        }
    }
    if ($failedMails == "" && $mailLstCnt > 0) {
        //cmnCde.showMsg("Message Successfully Sent to all Recipients!", 3);
        print_r(json_encode(array(
            'success' => true,
            'message' => 'Sent Successfully',
            'data' => array('src' => 'Message Successfully Sent to all Recipients!'),
            'total' => $mailLstCnt,
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
} else {
    //cmnCde.showSQLNoPermsn("Messages to some Recipients Failed!\r\n" + errMsg);
    print_r(json_encode(array(
        'success' => false,
        'message' => 'Error',
        'data' => '',
        'total' => '0',
        'errors' => 'INVALID REQUEST TOKEN!<br/>' . $errMsg
    )));
}
