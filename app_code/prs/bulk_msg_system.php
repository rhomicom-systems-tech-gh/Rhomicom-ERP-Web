<?php
if (array_key_exists('lgn_num', get_defined_vars())) {
    $prsnid = $_SESSION['PRSN_ID'];
    $orgID = $_SESSION['ORG_ID'];
    $lnkdFirmID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "lnkd_firm_org_id", $prsnid);
    $pkID = $prsnid;
    if ($lgn_num > 0 && $canview === true) {
        $prsnid = $_SESSION['PRSN_ID'];
        if ($qstr == "DELETE") {
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                $msgType = trim(cleanInputData($_POST['msgType']));
                $sndMsgOneByOne = cleanInputData($_POST['sndMsgOneByOne']);
                $grpType = trim(cleanInputData($_POST['grpType']));
                $grpNm = trim(cleanInputData($_POST['grpName']));
                $groupID = trim(cleanInputData($_POST['groupID']));
                $workPlaceID = trim(cleanInputData($_POST['workPlaceID']));
                $workPlaceSiteID = trim(cleanInputData($_POST['workPlaceSiteID']));
                session_write_close();
                $dtaVld = TRUE;
                $errMsg = "";
                // && $grpType != "Single Person"
                if ($grpType != "Everyone") {
                    if ($groupID == "-1" || $groupID == "") {
                        $dtaVld = FALSE;
                        $errMsg = "Please provide a Group Name!";
                    }
                }
                if ($msgType == "") {
                    $dtaVld = FALSE;
                    $errMsg = "Please select a Message Type!";
                }
                if ($grpType == "") {
                    $dtaVld = FALSE;
                    $errMsg = "Data Received Invalid!";
                }
                if ($grpType == "Person Type" && is_numeric($groupID) === FALSE) {
                    $groupID = getPssblValID($groupID, getLovID("Person Types"));
                }
                if ($dtaVld === TRUE) {
                    $slctdText = $grpType;
                    $msgTyp = $msgType;
                    $curid = getOrgFuncCurID($orgID);
                    $toTextBox = "";
                    if ($slctdText == "Companies/Institutions") {
                        $cstmrIDs = explode(";", $groupID);
                        $total = count($cstmrIDs);
                        $arr_content = array();
                        for ($a = 0; $a < count($cstmrIDs); $a++) {
                            if ($msgTyp == "Email") {
                                $toTextBox .= str_replace(",", ";", getCstmrSpplrEmails($cstmrIDs[$a])) . ";";
                            } else if ($msgTyp == "SMS") {
                                $toTextBox .= str_replace(",", ";", getCstmrSpplrMobiles($cstmrIDs[$a])) . ";";
                            } else {
                                $toTextBox .= str_replace(",", ";", $cstmrIDs[$a]) . ";";
                            }
                            $percent = round((($a + 1) / $total) * 100, 2);
                            $arr_content['percent'] = $percent;
                            if ($percent >= 100) {
                                $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> 100% Completed!..." . ($a + 1) . " out of " . $total . " address(es) retrieved.";
                                $arr_content['addresses'] = $toTextBox;
                            } else {
                                $arr_content['message'] = "<i class=\"fa fa-spin fa-spinner\"></i> Getting Addresses...Please Wait..." . ($a + 1) . " out of " . $total . " address(es) retrieved.";
                            }
                            file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_progress.rho", json_encode($arr_content));
                        }
                    } else {
                        $prsnIDs = getPrsnsInvolved("contains", $grpType, $grpNm, $groupID, $workPlaceID, $workPlaceSiteID);
                        $toTextBox = "";
                        $total = count($prsnIDs);
                        $arr_content = array();
                        for ($a = 0; $a < count($prsnIDs); $a++) {
                            $prsnID = $prsnIDs[$a];
                            if ($msgTyp == "Email") {
                                $toTextBox .= str_replace(",", ";", getPrsnEmail($prsnID)) . ";";
                            } else if ($msgTyp == "SMS") {
                                $toTextBox .= str_replace(",", ";", getPrsnMobile($prsnID)) . ";";
                            } else {
                                $toTextBox .= $prsnIDs[$a] . ";";
                            }
                            $percent = round((($a + 1) / $total) * 100, 2);
                            $arr_content['percent'] = $percent;
                            if ($percent >= 100) {
                                $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> 100% Completed!..." . ($a + 1) . " out of " . $total . " address(es) retrieved.";
                                $arr_content['addresses'] = $toTextBox;
                            } else {
                                $arr_content['message'] = "<i class=\"fa fa-spin fa-spinner\"></i> Getting Addresses...Please Wait..." . ($a + 1) . " out of " . $total . " address(es) retrieved.";
                            }
                            file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_progress.rho", json_encode($arr_content));
                        }
                    }
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['addresses'] = "";
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_progress.rho", json_encode($arr_content));
                }
            } else if ($actyp == 2) {
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_progress.rho";
                if (file_exists($file)) {
                    $text = file_get_contents($file);
                    echo $text;

                    $obj = json_decode($text);
                    if ($obj->percent >= 100) {
                        //$rs = file_exists($file) ? unlink($file) : TRUE;
                    }
                } else {
                    echo json_encode(array("percent" => null, "message" => null));
                }
            } else if ($actyp == 3) {
                //Queue Mail Messages
                $msgType = cleanInputData($_POST['msgType']);
                $sndMsgOneByOne = cleanInputData($_POST['sndMsgOneByOne']);
                $mailTo = trim(cleanInputData($_POST['mailTo']), ";, ");
                $mailCc = trim(cleanInputData($_POST['mailCc']), ";, ");
                $mailBcc = trim(cleanInputData($_POST['mailBcc']), ";, ");
                $mailAttchmnts = trim(cleanInputData($_POST['mailAttchmnts']), ";, ");
                $mailSubject = trim(cleanInputData($_POST['mailSubject']), ";, ");
                $bulkMessageBody = cleanInputData($_POST['bulkMessageBody']);
                session_write_close();
                $dtaVld = TRUE;
                $errMsg = "";
                if ($msgType == "") {
                    $dtaVld = FALSE;
                    $errMsg = "Please select a Message Type!";
                }
                if ($mailTo == "") {
                    $dtaVld = FALSE;
                    $errMsg = "Recipient Addresses cannot be Empty!";
                }
                if ($mailSubject == "") {
                    $dtaVld = FALSE;
                    $errMsg = "Message Subject cannot be empty!";
                }
                if ($bulkMessageBody == "") {
                    $dtaVld = FALSE;
                    $errMsg = "Message Body cannot be empty!";
                }
                $mailTo = str_replace(",", ";", str_replace("\r\n", "", $mailTo));
                $mailCc = str_replace(",", ";", str_replace("\r\n", "", $mailCc));
                $mailBcc = str_replace(",", ";", str_replace("\r\n", "", $mailBcc));
                $mailAttchmnts = str_replace(",", ";", str_replace("\r\n", "", $mailAttchmnts));
                if ($dtaVld === TRUE) {
                    $toEmails = explode(";", $mailTo);
                    $cntrnLmt = 0;

                    $mailLst = "";
                    $msgBatchID = getMsgBatchID();
                    $total = count($toEmails);
                    $reportTitle = "Send Outstanding Bulk Messages";
                    $reportName = "Send Outstanding Bulk Messages";
                    $rptID = getRptID($reportName);
                    $prmID = getParamIDUseSQLRep("{:msg_batch_id}", $rptID);
                    $paramRepsNVals = $prmID . "~" . $msgBatchID . "|-190~HTML";
                    for ($i = 0; $i < $total; $i++) {
                        if ($cntrnLmt == 0) {
                            $mailLst = "";
                        }
                        if (trim($toEmails[$i], ";, ") == "") {
                            continue;
                        }
                        $mailLst .= trim($toEmails[$i], ";, ") . ";";
                        $cntrnLmt++;
                        if (($cntrnLmt == 50 || $i == $total - 1 || $sndMsgOneByOne == "YES") && $msgType == "Email") {
                            createMessageQueue(
                                $msgBatchID,
                                trim($mailLst, ";, "),
                                trim($mailCc, ";, "),
                                trim($mailBcc, ";, "),
                                $bulkMessageBody,
                                $mailSubject,
                                trim($mailAttchmnts, ";, "),
                                $msgType
                            );
                            $cntrnLmt = 0;
                        } else if (($cntrnLmt == 1000 || $i == $total - 1) && $msgType == "SMS") {
                            createMessageQueue(
                                $msgBatchID,
                                trim($mailLst, ";, "),
                                trim($mailCc, ";, "),
                                trim($mailBcc, ";, "),
                                $bulkMessageBody,
                                $mailSubject,
                                trim($mailAttchmnts, ";, "),
                                $msgType
                            );
                            $cntrnLmt = 0;
                        } else if ($msgType == "System Inbox") {
                            createSysInboxMsg(
                                (float) trim($mailLst, ";, "),
                                $mailSubject,
                                $bulkMessageBody,
                                trim($mailAttchmnts, ";, "),
                                trim($mailAttchmnts, ";, "),
                                $prsnid
                            );
                            $cntrnLmt = 0;
                        }
                        $percent = round((($i + 1) / $total) * 100, 2);
                        $arr_content['percent'] = $percent;
                        if ($percent >= 100) {
                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> 100% Completed!..." . ($i + 1) . " out of " . $total . " Messages(es) successfully queued to be sent. You can close the Mail form now! Thank you!";
                            $arr_content['msgcount'] = $total;
                        } else {
                            $arr_content['message'] = "<i class=\"fa fa-spin fa-spinner\"></i> Queuing Messages...Please Wait..." . ($i + 1) . " out of " . $total . " Messages(es) successfully queued to be sent. You can close the Mail form now! Thank you!";
                        }
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_msgque_progress.rho", json_encode($arr_content));
                        if ($i <= 0 && $msgType != "System Inbox") {
                            generateReportRun($rptID, $paramRepsNVals, -1);
                        }
                    }
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['msgcount'] = "";
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_msgque_progress.rho", json_encode($arr_content));
                }
            } else if ($actyp == 4) {
                //Checked Queueing Process Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_msgque_progress.rho";
                if (file_exists($file)) {
                    $text = file_get_contents($file);
                    echo $text;

                    $obj = json_decode($text);
                    if ($obj->percent >= 100) {
                        //$rs = file_exists($file) ? unlink($file) : TRUE;
                    }
                } else {
                    echo json_encode(array("percent" => null, "message" => null));
                }
            }
        } else if ($qstr == "SELF-SERVICE-MSG") {
            //Get Default Values
            $inGroupID = -1;
            $inGroupName = "";
            $inMsgID = -1;
            $RoutingID = isset($_POST['RoutingID']) ? cleanInputData($_POST['RoutingID']) : "";
            $msgType = isset($_POST['msgType']) ? cleanInputData($_POST['msgType']) : "";
            $mailTo = isset($_POST['mailTo']) ? trim(cleanInputData($_POST['mailTo']), ";, ") : "";
            $mailSubject = isset($_POST['mailSubject']) ? trim(cleanInputData($_POST['mailSubject']), ";, ") : "";
            $bulkMessageBody = isset($_POST['bulkMessageBody']) ? cleanInputData($_POST['bulkMessageBody']) : "";
            $mailCc = isset($_POST['mailCc']) ? trim(cleanInputData($_POST['mailCc']), ";, ") : "";
            $mailAttchmnts = isset($_POST['mailAttchmnts']) ? trim(cleanInputData($_POST['mailAttchmnts']), ";, ") : "";
            if ($RoutingID > 0) {
                $reslt1 = getWkfMsgRtngData($RoutingID);
                while ($row = loc_db_fetch_array($reslt1)) {
                    $inMsgID = (float) $row[0];
                    $inGroupID = (float) $row[2];
                    $inGroupName = $row[19];
                }
                if ($inMsgID > 0) {
                    $reslt21 = getWkfMsgHdrData($inMsgID);
                    while ($row2 = loc_db_fetch_array($reslt21)) {
                        $mailSubject = "RE:" . $row2[1];
                        $bulkMessageBody = "<br/><br/><br/><em>Prev Message:</em><br/>" . $row2[2];
                    }
                }
            }
?>
            <div class="">
                <div class="row">
                    <div class="col-md-12">
                        <!--<div class="custDiv">-->
                        <form class="form-horizontal">
                            <div class="row">
                                <div class="col-md-4 hideNotice">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <fieldset class="" style="min-height:240px !important;margin-bottom:5px;">
                                                <legend class="basic_person_lg">Message Destination</legend>
                                                <div class="row hideNotice">
                                                    <div class="col-md-7">
                                                        <div class="form-group form-group-sm">
                                                            <label for="msgType" class="control-label col-md-5" style="padding:0px 0px 0px 15px !important;">Message Type:</label>
                                                            <div class="col-md-7">
                                                                <select class="form-control" id="msgType" style="min-width:75px !important;">
                                                                    <?php
                                                                    $valslctdArry = array("");
                                                                    $srchInsArrys = array("System Inbox");
                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                        if ($msgType == $srchInsArrys[$z]) {
                                                                            $valslctdArry[$z] = "selected";
                                                                        }
                                                                    ?>
                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                <input type="hidden" id="rho_msg_source" value="SELF-SERVICE-MSG" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-check" style="font-size: 12px !important;">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" class="form-check-input" id="sndMsgOneByOne" name="sndMsgOneByOne" checked="true">
                                                                Send Individually
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row hideNotice">
                                                    <div class="col-md-12">
                                                        <div class="form-group form-group-sm">
                                                            <label for="grpType" class="control-label col-md-3" style="padding:0px 0px 0px 15px !important;">Group Type:</label>
                                                            <div class="col-md-9">
                                                                <select class="form-control" id="grpType" onchange="grpTypNoticesChange();">
                                                                    <option value="Single Person">Single Person</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row hideNotice">
                                                    <div class="col-md-12">
                                                        <div class="form-group form-group-sm">
                                                            <label for="workPlaceName" class="control-label col-md-3" style="padding:0px 0px 0px 15px !important;">Workplace Name:</label>
                                                            <div class="col-md-9">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" aria-label="..." id="workPlaceName" value="" readonly="">
                                                                    <input type="hidden" id="workPlaceID" value="-1">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Business/Trade Partners', 'gnrlOrgID', '', '', 'radio', true, '', 'workPlaceID', 'workPlaceName', 'clear', 1, '');">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row hideNotice">
                                                    <div class="col-md-12">
                                                        <div class="form-group form-group-sm">
                                                            <label for="workPlaceSiteName" class="control-label col-md-3" style="padding:0px 0px 0px 15px !important;">Site:</label>
                                                            <div class="col-md-9">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" aria-label="..." id="workPlaceSiteName" value="" readonly="">
                                                                    <input type="hidden" id="workPlaceSiteID" value="-1">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Customer/Supplier Sites', 'workPlaceID', '', '', 'radio', true, '', 'workPlaceSiteID', 'workPlaceSiteName', 'clear', 1, '');">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-primary hideNotice" style="float:right;" onclick="autoLoadAddresses();">Auto-Load E-mails</button>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="row hideNotice">
                                        <div class="col-md-12">
                                            <fieldset class="">
                                                <legend class="basic_person_lg">Message Parameters</legend>
                                                <div class="form-group form-group-sm">
                                                    <label for="mailTo" class="control-label col-md-2">To:</label>
                                                    <div class="col-md-10">
                                                        <div class="col-xs-10" style="padding:0px 1px 0px 0px !important;">
                                                            <textarea class="form-control" id="mailTo" cols="2" placeholder="To" rows="4"><?php echo urlencode($mailTo); ?></textarea>
                                                        </div>
                                                        <div class="col-xs-2" style="padding:0px 1px 0px 5px !important;">
                                                            <button type="button" class="btn btn-default btn-sm" style="float:right;"><span class="glyphicon glyphicon-th-list"></span></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm">
                                                    <label for="mailCc" class="control-label col-md-2">Cc:</label>
                                                    <div class="col-md-10">
                                                        <div class="col-xs-10" style="padding:0px 1px 0px 0px !important;">
                                                            <input class="form-control" id="mailCc" type="text" placeholder="Cc" value="<?php echo urlencode($mailCc); ?>" />
                                                        </div>
                                                        <div class="col-xs-2" style="padding:0px 1px 0px 5px !important;">
                                                            <button type="button" class="btn btn-default btn-sm" style="float:right;"><span class="glyphicon glyphicon-th-list"></span></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm">
                                                    <label for="mailBcc" class="control-label col-md-2">Bcc:</label>
                                                    <div class="col-md-10">
                                                        <div class="col-xs-10" style="padding:0px 1px 0px 0px !important;">
                                                            <input class="form-control" id="mailBcc" type="text" placeholder="Bcc" />
                                                        </div>
                                                        <div class="col-xs-2" style="padding:0px 1px 0px 5px !important;">
                                                            <button type="button" class="btn btn-default btn-sm" style="float:right;"><span class="glyphicon glyphicon-th-list"></span></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <fieldset class="">
                                        <legend class="basic_person_lg">Message Body</legend>
                                        <div class="row" style="margin: 1px 0px 0px 0px !important;padding:0px 15px 0px 15px !important;">
                                            <div class="form-group form-group-sm">
                                                <label for="groupName" class="control-label col-md-2">Person To:</label>
                                                <div class="col-md-10" style="padding:0px 1px 0px 15px !important;">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" aria-label="..." id="groupName" value="<?php echo $inGroupName; ?>" readonly="">
                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                        <input type="hidden" id="groupID" value="<?php echo $inGroupID; ?>">
                                                        <label id="groupNameLbl" class="btn btn-primary btn-file input-group-addon" onclick="getNoticeLovs('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Business/Trade Partners', 'gnrlOrgID', '', '', 'radio', true, '', 'groupID', 'groupName', 'clear', 1, '');">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin: 1px 0px 0px 0px !important;padding:0px 15px 0px 15px !important;">
                                            <div class="form-group form-group-sm">
                                                <label for="mailSubject" class="control-label col-md-2">Subject:</label>
                                                <div class="col-md-10" style="padding:0px 1px 0px 15px !important;">
                                                    <input class="form-control" id="mailSubject" type="text" placeholder="Subject" value="<?php echo urlencode($mailSubject); ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin: 1px 0px 0px 0px !important;padding:0px 15px 0px 15px !important;">
                                            <div class="form-group form-group-sm">
                                                <label for="mailAttchmnts" class="control-label col-md-2">Attached Files <span class="glyphicon glyphicon-paperclip"></span>:</label>
                                                <div class="col-md-10">
                                                    <div class="col-xs-10" style="padding:0px 1px 0px 0px !important;">
                                                        <textarea class="form-control" id="mailAttchmnts" cols="2" placeholder="Attachments" rows="3"><?php echo $mailAttchmnts; ?></textarea>
                                                    </div>
                                                    <div class="col-xs-2" style="padding:0px 1px 0px 5px !important;">
                                                        <button type="button" class="btn btn-default" style="float:right;" onclick="attchFileToMsg();"><span class="glyphicon glyphicon-th-list"></span> Browse...</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" id="prevBulkMessageBody" value="<?php echo urlencode($bulkMessageBody); ?> " />
                                        <input type="hidden" id="prevMailSubject" value="<?php echo urlencode($mailSubject); ?> " />
                                        <div class="row" style="padding:0px 15px 0px 15px !important;">
                                            <div id="bulkMessageBody"></div>
                                        </div>
                                        <div class="row" style="margin: -5px 0px 0px 0px !important;">
                                            <div class="col-md-12" style="padding:0px 0px 0px 0px">
                                                <div class="" style="padding:0px 1px 0px 1px !important;float:right !important;">
                                                    <button type="button" class="btn btn-default btn-sm" style="" onclick="clearMsgForm();"><img src="cmn_images/reload.png" style="left: 0.05%; padding-right: 2px; height:20px; width:auto; position: relative; vertical-align: middle;">RESET</button>
                                                    <button type="button" class="btn btn-default btn-sm" style="" onclick="autoQueueMsgs();"><img src="cmn_images/Emailcon.png" style="left: 0.05%; padding-right: 2px; height:20px; width:auto; position: relative; vertical-align: middle;">SEND MESSAGE</button>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                </div>
                            </div>
                        </form>
                        <!--</div>-->
                    </div>
                </div>
            </div>
        <?php
        } else {
            //Get Default Values
            $msgType = isset($_POST['msgType']) ? cleanInputData($_POST['msgType']) : "";
            $mailTo = isset($_POST['mailTo']) ? trim(cleanInputData($_POST['mailTo']), ";, ") : "";
            $mailSubject = isset($_POST['mailSubject']) ? trim(cleanInputData($_POST['mailSubject']), ";, ") : "";
            $bulkMessageBody = isset($_POST['bulkMessageBody']) ? cleanInputData($_POST['bulkMessageBody']) : "";
            $mailCc = isset($_POST['mailCc']) ? trim(cleanInputData($_POST['mailCc']), ";, ") : "";
            $mailAttchmnts = isset($_POST['mailAttchmnts']) ? trim(cleanInputData($_POST['mailAttchmnts']), ";, ") : "";
        ?>
            <div class="">
                <div class="row">
                    <div class="col-md-12">
                        <!--<div class="custDiv">-->
                        <form class="form-horizontal">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <fieldset class="" style="min-height:240px !important;margin-bottom:5px;">
                                                <legend class="basic_person_lg">Destination Group</legend>
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <div class="form-group form-group-sm">
                                                            <label for="msgType" class="control-label col-md-5" style="padding:0px 0px 0px 15px !important;">Message Type:</label>
                                                            <div class="col-md-7">
                                                                <select class="form-control" id="msgType" style="min-width:75px !important;">
                                                                    <?php
                                                                    $valslctdArry = array("", "", "");
                                                                    $srchInsArrys = array("Email", "SMS", "System Inbox");
                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                        if ($msgType == $srchInsArrys[$z]) {
                                                                            $valslctdArry[$z] = "selected";
                                                                        }
                                                                    ?>
                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                <input type="hidden" id="rho_msg_source" value="VIEW" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-check" style="font-size: 12px !important;">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" class="form-check-input" id="sndMsgOneByOne" name="sndMsgOneByOne" checked="true">
                                                                Send Individually
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group form-group-sm">
                                                            <label for="grpType" class="control-label col-md-3" style="padding:0px 0px 0px 15px !important;">Group Type:</label>
                                                            <div class="col-md-9">
                                                                <select class="form-control" id="grpType" onchange="grpTypNoticesChange();">
                                                                    <option value="Everyone">Everyone</option>
                                                                    <option value="Divisions/Groups">Divisions/Groups</option>
                                                                    <option value="Grade">Grade</option>
                                                                    <option value="Job">Job</option>
                                                                    <option value="Position">Position</option>
                                                                    <option value="Site/Location">Site/Location</option>
                                                                    <option value="Person Type">Person Type</option>
                                                                    <option value="Single Person">Single Person</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group form-group-sm">
                                                            <label for="groupName" class="control-label col-md-3" style="padding:0px 0px 0px 15px !important;">Group Name:</label>
                                                            <div class="col-md-9">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" aria-label="..." id="groupName" value="" readonly="">
                                                                    <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                    <input type="hidden" id="groupID" value="-1">
                                                                    <label disabled="true" id="groupNameLbl" class="btn btn-primary btn-file input-group-addon" onclick="getNoticeLovs('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Business/Trade Partners', 'gnrlOrgID', '', '', 'radio', true, '', 'groupID', 'groupName', 'clear', 1, '');">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group form-group-sm">
                                                            <label for="workPlaceName" class="control-label col-md-3" style="padding:0px 0px 0px 15px !important;">Workplace Name:</label>
                                                            <div class="col-md-9">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" aria-label="..." id="workPlaceName" value="" readonly="">
                                                                    <input type="hidden" id="workPlaceID" value="-1">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Business/Trade Partners', 'gnrlOrgID', '', '', 'radio', true, '', 'workPlaceID', 'workPlaceName', 'clear', 1, '');">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group form-group-sm">
                                                            <label for="workPlaceSiteName" class="control-label col-md-3" style="padding:0px 0px 0px 15px !important;">Site:</label>
                                                            <div class="col-md-9">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" aria-label="..." id="workPlaceSiteName" value="" readonly="">
                                                                    <input type="hidden" id="workPlaceSiteID" value="-1">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Customer/Supplier Sites', 'workPlaceID', '', '', 'radio', true, '', 'workPlaceSiteID', 'workPlaceSiteName', 'clear', 1, '');">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-primary" style="float:right;" onclick="autoLoadAddresses();">Auto-Load E-mails</button>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <fieldset class="">
                                                <legend class="basic_person_lg">Message Parameters</legend>
                                                <div class="form-group form-group-sm">
                                                    <label for="mailTo" class="control-label col-md-2">To:</label>
                                                    <div class="col-md-10">
                                                        <div class="col-xs-10" style="padding:0px 1px 0px 0px !important;">
                                                            <textarea class="form-control" id="mailTo" cols="2" placeholder="To" rows="4"><?php echo urlencode($mailTo); ?></textarea>
                                                        </div>
                                                        <div class="col-xs-2" style="padding:0px 1px 0px 5px !important;">
                                                            <button type="button" class="btn btn-default btn-sm" style="float:right;"><span class="glyphicon glyphicon-th-list"></span></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm">
                                                    <label for="mailCc" class="control-label col-md-2">Cc:</label>
                                                    <div class="col-md-10">
                                                        <div class="col-xs-10" style="padding:0px 1px 0px 0px !important;">
                                                            <input class="form-control" id="mailCc" type="text" placeholder="Cc" value="<?php echo urlencode($mailCc); ?>" />
                                                        </div>
                                                        <div class="col-xs-2" style="padding:0px 1px 0px 5px !important;">
                                                            <button type="button" class="btn btn-default btn-sm" style="float:right;"><span class="glyphicon glyphicon-th-list"></span></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm">
                                                    <label for="mailBcc" class="control-label col-md-2">Bcc:</label>
                                                    <div class="col-md-10">
                                                        <div class="col-xs-10" style="padding:0px 1px 0px 0px !important;">
                                                            <input class="form-control" id="mailBcc" type="text" placeholder="Bcc" />
                                                        </div>
                                                        <div class="col-xs-2" style="padding:0px 1px 0px 5px !important;">
                                                            <button type="button" class="btn btn-default btn-sm" style="float:right;"><span class="glyphicon glyphicon-th-list"></span></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm">
                                                    <label for="mailAttchmnts" class="control-label col-md-2">Attached Files <span class="glyphicon glyphicon-paperclip"></span>:</label>
                                                    <div class="col-md-10">
                                                        <div class="col-xs-10" style="padding:0px 1px 0px 0px !important;">
                                                            <textarea class="form-control" id="mailAttchmnts" cols="2" placeholder="Attachments" rows="3"><?php echo $mailAttchmnts; ?></textarea>
                                                        </div>
                                                        <div class="col-xs-2" style="padding:0px 1px 0px 5px !important;">
                                                            <button type="button" class="btn btn-default btn-sm" style="float:right;" onclick="attchFileToMsg();"><span class="glyphicon glyphicon-th-list"></span></button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <fieldset class="">
                                        <legend class="basic_person_lg">Message Body</legend>
                                        <div class="row" style="margin: 1px 0px 0px 0px !important;padding:0px 15px 0px 15px !important;">
                                            <div class="form-group form-group-sm">
                                                <label for="mailSubject" class="control-label col-md-2">Subject:</label>
                                                <div class="col-md-10" style="padding:0px 1px 0px 15px !important;">
                                                    <input class="form-control" id="mailSubject" type="text" placeholder="Subject" value="<?php echo urlencode($mailSubject); ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="padding:0px 15px 0px 15px !important;">
                                            <div id="bulkMessageBody"></div>
                                        </div>
                                        <div class="row" style="margin: -5px 0px 0px 0px !important;">
                                            <div class="col-md-12" style="padding:0px 0px 0px 0px">
                                                <div class="" style="padding:0px 1px 0px 1px !important;float:right !important;">
                                                    <button type="button" class="btn btn-default btn-sm" style="" onclick="clearMsgForm();"><img src="cmn_images/reload.png" style="left: 0.05%; padding-right: 2px; height:20px; width:auto; position: relative; vertical-align: middle;">RESET</button>
                                                    <button type="button" class="btn btn-default btn-sm" style="" onclick="autoQueueMsgs();"><img src="cmn_images/Emailcon.png" style="left: 0.05%; padding-right: 2px; height:20px; width:auto; position: relative; vertical-align: middle;">SEND MESSAGE</button>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                </div>
                            </div>
                        </form>
                        <!--</div>-->
                    </div>
                </div>
            </div>
<?php
        }
    }
}
?>