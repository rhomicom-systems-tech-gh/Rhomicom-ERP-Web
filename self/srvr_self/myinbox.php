<?php
$mdlNm = "Self Service";
$ModuleName = $mdlNm;

$dfltPrvldgs = array("View Self-Service", "Administer Other's Inbox");

$vwtyp = "0";
$qstr = "";
$dsply = "";
$actyp = "";
$qActvOnly = "true";
$srchFor = "%";
$srchIn = "Person From";
$RoutingID = -1;
$qNonLgn = "true";
$qNonAknwldg = "false";
if (isset($_POST['qNonLgn'])) {
    $qNonLgn = cleanInputData($_POST['qNonLgn']);
}
if (isset($_POST['qActvOnly'])) {
    $qActvOnly = cleanInputData($_POST['qActvOnly']);
}
if (isset($_POST['qNonAknwldg'])) {
    $qNonAknwldg = cleanInputData($_POST['qNonAknwldg']);
}
if (isset($_POST['RoutingID'])) {
    $RoutingID = cleanInputData($_POST['RoutingID']);
}
$qStrtDte = "";
$qEndDte = "";
if (isset($_POST['qStrtDte'])) {
    $qStrtDte = cleanInputData($_POST['qStrtDte']);
    if (strlen($qStrtDte) == 11) {
        $qStrtDte = substr($qStrtDte, 0, 11) . " 00:00:00";
    } else {
        $qStrtDte = "";
    }
}
if (isset($_POST['qEndDte'])) {
    $qEndDte = cleanInputData($_POST['qEndDte']);
    if (strlen($qEndDte) == 11) {
        $qEndDte = substr($qEndDte, 0, 11) . " 23:59:59";
    } else {
        $qEndDte = "";
    }
}
if (isset($_POST['searchfor'])) {
    $srchFor = cleanInputData($_POST['searchfor']);
}
if (isset($_POST['searchin'])) {
    $srchIn = cleanInputData($_POST['searchin']);
}
if (strpos($srchFor, "%") === FALSE) {
    $srchFor = " " . $srchFor . " ";
    $srchFor = str_replace(" ", "%", $srchFor);
}
if (isset($_POST['q'])) {
    $qstr = cleanInputData($_POST['q']);
}

if (isset($_POST['vtyp'])) {
    $vwtyp = cleanInputData($_POST['vtyp']);
}

if (isset($_POST['actyp'])) {
    $actyp = cleanInputData($_POST['actyp']);
}

$isMaster = 0;
if (isset($_POST['qMaster'])) {
    $isMaster = cleanInputData($_POST['qMaster']);
}

if ($isMaster == 1) {
    if (test_prmssns($dfltPrvldgs[1], $mdlNm) == FALSE) {
        $isMaster = 0;
        restricted();
        exit();
    }
}
$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";

if (strpos($srchFor, "%") === FALSE) {
    $srchFor = " " . $srchFor . " ";
    $srchFor = str_replace(" ", "%", $srchFor);
}
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                $msg = "Re-assigning Message...";
                $toPrsLocID = isset($_POST['toPrsLocID']) ? cleanInputData($_POST['toPrsLocID']) : -1;
                $wkfSlctdRoutingIDs = isset($_POST['wkfSlctdRoutingIDs']) ? cleanInputData($_POST['wkfSlctdRoutingIDs']) : '';
                $actReason = isset($_POST['actReason']) ? cleanInputData($_POST['actReason']) : '';
                $dlmtrs = '|';
                $routingIDs = explode($dlmtrs, $wkfSlctdRoutingIDs);
                $rtngMsgID = -1;
                $msg_id = $rtngMsgID;
                $curPrsnsLevel = 0;
                $isActionDone = '0';
                $oldMsgbodyAddOn = "";
                $orgnlToPrsnID = -1;
                $curStatus = "";
                $actnToPrfm = "";
                $nxt_action_to_prfm = "";
                $msgTitle = "";
                $msgBdy = "";
                $msgType = "";
                $srcDocID = -1;
                $srcDocType = "";
                $orgnlPrsnUsrID = -1;
                $hrchyid = -1;
                $appID = -1;
                $attchmnts = "";
                $attchmnts_desc = "";
                $userID = $usrID;
                $affctd = 0;
                $affctd1 = 0;
                $affctd2 = 0;
                $affctd3 = 0;
                for ($w = 0; $w < count($routingIDs); $w++) {
                    $routingID = (float) $routingIDs[$w];
                    if ($routingID > 0) {
                        $oldMsgbodyAddOn = "";
                        $reslt1 = getWkfMsgRtngData($routingID);
                        while ($row = loc_db_fetch_array($reslt1)) {
                            $rtngMsgID = (float) $row[0];
                            $msg_id = $rtngMsgID;
                            $curPrsnsLevel = (float) $row[18];
                            $isActionDone = $row[9];
                            $oldMsgbodyAddOn = $row[17];
                            $orgnlToPrsnID = (float) $row[2];
                            $curStatus = $row[7];
                            $actnToPrfm = $row[8];
                            $nxt_action_to_prfm = $row[13];
                        }
                        $row = NULL;

                        $reslt2 = getWkfMsgHdrData($rtngMsgID);
                        while ($row = loc_db_fetch_array($reslt2)) {
                            $msgTitle = $row[1]; //getGnrlRecNm("wkf.wkf_actual_msgs_hdr", "msg_id", "msg_hdr", $rtngMsgID);
                            $msgBdy = $row[2]; //getGnrlRecNm("wkf.wkf_actual_msgs_hdr", "msg_id", "msg_body", $rtngMsgID);
                            $msgType = $row[6];
                            $srcDocID = (float) $row[10]; //getGnrlRecNm("wkf.wkf_actual_msgs_hdr", "msg_id", "src_doc_id", $rtngMsgID);
                            $srcDocType = $row[9]; //getGnrlRecNm("wkf.wkf_actual_msgs_hdr", "msg_id", "src_doc_type", $rtngMsgID);
                            $orgnlPrsnUsrID = (float) $row[3]; //getGnrlRecNm("wkf.wkf_actual_msgs_hdr", "msg_id", "created_by", $rtngMsgID);
                            $hrchyid = (float) $row[5];
                            $appID = (float) $row[7];
                            $attchmnts = $row[13];
                            $attchmnts_desc = $row[14]; //Get Attachments
                        }
                        $srcdoctyp = $srcDocType;
                        $srcdocid = $srcDocID;
                        $nwPrsnLocID = $toPrsLocID;
                        $apprvrCmmnts = $actReason;

                        $fromPrsnID = getUserPrsnID($user_Name);
                        $usrFullNm = getPrsnFullNm($fromPrsnID);

                        $reportTitle = "Send Outstanding Bulk Messages";
                        $reportName = "Send Outstanding Bulk Messages";
                        $rptID = getRptID($reportName);
                        $prmID = getParamIDUseSQLRep("{:msg_batch_id}", $rptID);
                        $msgBatchID = -1;

                        $nwPrsnID = getPersonID($nwPrsnLocID);
                        $affctd += updtWkfMsgRtngUsngLvl($rtngMsgID, $curPrsnsLevel, $fromPrsnID, "Re-assigned", "None", $userID);
                        $datestr = getFrmtdDB_Date_time();
                        $msgbodyAddOn = "";
                        $msgbodyAddOn .= "RE-ASSIGNED ON $datestr:- A requested has been re-assigned by $usrFullNm with the ff Message:<br/>";
                        $msgbodyAddOn .= $apprvrCmmnts . "<br/><br/>";
                        $affctd1 += updtWkfMsgRtngCmnts($routingID, $msgbodyAddOn, $userID);
                        $msgbodyAddOn .= $oldMsgbodyAddOn;

                        updateWkfMsgStatus($rtngMsgID, "1", $userID);
                        updtWkfMsgAllUnclsdRtng($rtngMsgID, $fromPrsnID, "Closed", "None", $userID);

                        $msghdr = "RE-ASSIGNED - " . $msgTitle;
                        $msgbody = $msgBdy; //"ORIGINAL MESSAGE :<br/><br/>" . 
                        $msgtyp = $msgType;
                        $msgsts = "0";

                        $affctd2 += updateWkfMsg(
                            $msg_id,
                            $msghdr,
                            $msgbody,
                            $userID,
                            $appID,
                            $msgtyp,
                            $msgsts,
                            $srcdoctyp,
                            $srcdocid,
                            $hrchyid,
                            $attchmnts,
                            $attchmnts_desc
                        );
                        $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $nwPrsnID, $userID, $curStatus, $actnToPrfm, $curPrsnsLevel, $msgbodyAddOn);

                        if ($affctd > 0) {
                            $dsply .= "<br/>Status of $affctd Workflow Document(s) successfully updated to Information Requested!";
                            $dsply .= "<br/>$affctd1 Workflow Document(s) Message Body Successfully Updated!";

                            $dsply .= "<br/>$affctd3 Workflow Document(s) Successfully Re-Routed to New Person!";

                            $msg = "<p style = \"text-align:left; color:#32CD32;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>"; //#32CD32
                        } else {
                            $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Worked On";
                            $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
                        }
                    }
                }
                echo $msg;
            } else if ($actyp == 2) {
            }
        } else if ($qstr == "act") {
            $usrID = $_SESSION['USRID'];
            $arry1 = explode(";", $actyp);
            for ($r = 0; $r < count($arry1); $r++) {
                if ($arry1[$r] !== "") {
                    actOnMsgSQL($RoutingID, $usrID, $arry1[$r]);
                }
            }
        } else {
            if ($vwtyp == "0") {
                $isMaster = 0;
                $total = get_MyInbxTtls($srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_MyInbx($srchFor, $srchIn, $curIdx, $lmtSze);
?>
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>My Inbox/Worklist</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Inbox</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>
                <section class="content">
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-md-12">
                            <div class="card card-primary card-outline" style="margin:0px 10px 10px 10px !important;padding: 0px !important; border-left:1px solid #ddd; border-right:1px solid #bbb; border-bottom:1px solid #ddd;">
                                <div class="card-header" style="padding:6px 0px 4px 0px !important;">
                                    <form id='myInbxForm' action='' method='post' accept-charset='UTF-8'>
                                        <div class="row col-sm-12">
                                            <div class="col-sm-7">
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control" placeholder="Search Mail" id="myInbxSrchFor" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncMyInbx(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                                    <input id="myInbxPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                                    <div class="input-group-append handCursor" onclick="getMyInbx('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                                        <span class="input-group-text rhoclickable"><i class="fas fa-times"></i></span>
                                                    </div>
                                                    <div class="input-group-append handCursor" onclick="getMyInbx('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                                        <span class="input-group-text rhoclickable"><i class="fas fa-search"></i></span>
                                                    </div>
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-filter"></i></span>
                                                    </div>
                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="myInbxSrchIn" style="max-width:160px !important;border-bottom-left-radius: 1px !important;">
                                                        <?php
                                                        $valslctdArry = array("", "", "", "", "", "");
                                                        $srchInsArrys = array("Status", "Source App", "Subject", "Person From", "Person To", "Message Type");

                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                            if ($srchIn == $srchInsArrys[$z]) {
                                                                $valslctdArry[$z] = "selected";
                                                            }
                                                        ?>
                                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-filter"></i></span>
                                                    </div>
                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="myInbxDsplySze" style="max-width:70px !important;">
                                                        <?php
                                                        $valslctdArry = array("", "", "", "", "", "", "", "");
                                                        $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000);
                                                        for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                                            if ($lmtSze == $dsplySzeArry[$y]) {
                                                                $valslctdArry[$y] = "selected";
                                                            } else {
                                                                $valslctdArry[$y] = "";
                                                            }
                                                        ?>
                                                            <option value="<?php echo $dsplySzeArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $dsplySzeArry[$y]; ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-2" style="padding:0px 1px 0px 1px !important;">
                                                <div class="input-group date rho-DatePicker input-group-sm" id="myInbxStrtDateDP" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input" id="myInbxStrtDate" value="<?php echo substr($qStrtDte, 0, 11); ?>" data-target="#myInbxStrtDateDP" placeholder="DD-MMM-YYYY">
                                                    <div class="input-group-append handCursor" onclick="$('#myInbxStrtDate').val('');">
                                                        <span class="input-group-text rhoclickable"><i class="fa fa-times"></i></span>
                                                    </div>
                                                    <div class="input-group-append handCursor" data-target="#myInbxStrtDateDP" data-toggle="datetimepicker">
                                                        <span class="input-group-text rhoclickable"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2" style="padding:0px 1px 0px 1px !important;">
                                                <div class="input-group date rho-DatePicker input-group-sm" id="myInbxEndDateDP" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input" id="myInbxEndDate" value="<?php echo substr($qEndDte, 0, 11); ?>" data-target="#myInbxEndDateDP" placeholder="DD-MMM-YYYY">
                                                    <div class="input-group-append handCursor" onclick="$('#myInbxEndDate').val('');">
                                                        <span class="input-group-text rhoclickable"><i class="fa fa-times"></i></span>
                                                    </div>
                                                    <div class="input-group-append handCursor" data-target="#myInbxEndDateDP" data-toggle="datetimepicker">
                                                        <span class="input-group-text rhoclickable"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-1" style="padding-right:1px !important;">
                                                <div class="float-right">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default btn-sm" onclick="getMyInbx('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');"><i class="fas fa-chevron-left"></i></button>
                                                        <button type="button" class="btn btn-default btn-sm" onclick="getMyInbx('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');"><i class="fas fa-chevron-right"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr style="margin: 3px 0px 3px 0px !important;">
                                        <div class="row col-sm-12">
                                            <div class=" col-sm-6">
                                                <!-- Check all button -->
                                                <button type="button" class="btn btn-default btn-sm checkbox-toggle" onclick="checkAllBtns('myInbxTblForm');">
                                                    <i class="far fa-check-square"></i>
                                                </button>
                                                <button type="button" class="btn btn-default btn-sm checkbox-toggle" onclick="unCheckAllBtns('myInbxTblForm');">
                                                    <i class="far fa-square"></i>
                                                </button>
                                                <button type="button" class="btn btn-default btn-sm" onclick="getMyInbx('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                                <button type="button" class="btn btn-default btn-sm" onclick="onReassign('myInbxTblForm');">
                                                    <img src="../cmn_images/reassign_users.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    RE-ASSIGN SELECTED LINES
                                                </button>
                                                <button type="button" class="btn btn-default btn-sm" onclick="sendGeneralMessage2();">
                                                    <img src="../cmn_images/Mail.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    SEND MESSAGE
                                                </button>
                                            </div>
                                            <div class="col-sm-2 form-group mb-0" style="padding:5px 1px 0px 1px !important;">
                                                <?php
                                                $actvChekd = "";
                                                if ($qActvOnly == "true") {
                                                    $actvChekd = "checked=\"true\"";
                                                }
                                                $noLgnChekd = "";
                                                if ($qNonLgn == "true") {
                                                    $noLgnChekd = "checked=\"true\"";
                                                }
                                                $noAknwChekd = "";
                                                if ($qNonAknwldg == "true") {
                                                    $noAknwChekd = "checked=\"true\"";
                                                }
                                                ?>
                                                <div class="form-check" style="font-size: 12px !important;">
                                                    <input type="checkbox" class="form-check-input handCursor" onclick="getMyInbx('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="myInbxShwActvNtfs" name="myInbxShwActvNtfs" <?php echo $actvChekd; ?>>
                                                    <label class="form-check-label handCursor" style="padding-top: 6px;" onclick="toggleCheckBx('myInbxShwActvNtfs', function () {
                                                                getMyInbx('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');
                                                            });">
                                                        Active Notifications
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-2 form-group mb-0" style="padding:5px 1px 0px 1px !important;">
                                                <div class="form-check" style="font-size: 12px !important;">
                                                    <input type="checkbox" class="form-check-input handCursor" onclick="getMyInbx('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="myInbxShwNonLgnNtfs" name="myInbxShwNonLgnNtfs" <?php echo $noLgnChekd; ?>>
                                                    <label class="form-check-label handCursor" style="padding-top: 6px;" onclick="toggleCheckBx('myInbxShwNonLgnNtfs', function () {
                                                                getMyInbx('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');
                                                            });">
                                                        Non-Logon
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-2 form-group mb-0" style="padding:5px 1px 0px 1px !important;">
                                                <div class="form-check" style="font-size: 12px !important;">
                                                    <input type="checkbox" class="form-check-input handCursor" onclick="getMyInbx('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="myInbxShwNonAknwNtfs" name="myInbxShwNonAknwNtfs" <?php echo $noAknwChekd; ?>>
                                                    <label class="form-check-label handCursor" style="padding-top: 6px;" onclick="toggleCheckBx('myInbxShwNonAknwNtfs', function () {
                                                                getMyInbx('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');
                                                            });">
                                                        Non-Informational
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card-header -->
                                <div class="p-2">
                                    <form id='myInbxTblForm' action='' method='post' accept-charset='UTF-8'>
                                        <div class="table-responsive mailbox-messages">
                                            <table class="table table-hover table-striped" id="myInbxTable" style="border:1px solid #ddd;">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align:center;">...</th>
                                                        <th style="text-align:center;">No.</th>
                                                        <th>...</th>
                                                        <th>...</th>
                                                        <th>...</th>
                                                        <th>...</th>
                                                        <th>...</th>
                                                        <th>Subject</th>
                                                        <th>Source App</th>
                                                        <th>From</th>
                                                        <th>To</th>
                                                        <th>Date Sent</th>
                                                        <th>Status</th>
                                                        <th>...</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $cntr = 0;
                                                    $cntrStrtVal = 0;
                                                    $cntrEndVal = 0;
                                                    while ($row = loc_db_fetch_array($result)) {
                                                        $cntr += 1;
                                                        if ($cntr == 1) {
                                                            $cntrStrtVal = ($curIdx * $lmtSze) + ($cntr);
                                                        }
                                                        $cntrEndVal = ($curIdx * $lmtSze) + ($cntr);
                                                    ?>
                                                        <tr id="myInbxRow_<?php echo $cntr; ?>">
                                                            <td class="lovtd mailbox-star" style="text-align:center;">
                                                                <div class="icheck-primary">
                                                                    <input type="checkbox" name="myInbxRow<?php echo $cntr; ?>_CheckBox" value="<?php echo $row[0] . ";" . $row[1]; ?>">
                                                                </div>
                                                                <input type="hidden" value="<?php echo $row[0]; ?>" id="myInbxRow<?php echo $cntr; ?>_RoutingID">
                                                                <input type="hidden" value="<?php echo $row[14]; ?>" id="myInbxRow<?php echo $cntr; ?>_MsgType">
                                                                <input type="hidden" value="<?php echo $row[2]; ?>" id="myInbxRow<?php echo $cntr; ?>_MsgSubject">
                                                                <input type="hidden" value="<?php echo $row[5]; ?>" id="myInbxRow<?php echo $cntr; ?>_DateSent">
                                                                <input type="hidden" value="<?php echo $row[15]; ?>" id="myInbxRow<?php echo $cntr; ?>_FromPersonLocID">
                                                                <input type="hidden" value="<?php echo $row[3]; ?>" id="myInbxRow<?php echo $cntr; ?>_FromPerson">
                                                            </td>
                                                            <td class="lovtd mailbox-star" style="text-align:center;"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                            <td class="lovtd mailbox-star">
                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Details" onclick="getOneMyInbxForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'myInbxDetForm', 'View Message (ID: <?php echo $row[0]; ?> - <?php
                                                                                                                                                                                                                                                                                                                                                echo str_replace("'", "\'", $row[2]);
                                                                                                                                                                                                                                                                                                                                                ?>)', <?php echo $row[0]; ?>, 1, <?php echo $pgNo ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                                    <img src="../cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </td>
                                                            <td class="lovtd mailbox-star">
                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Approve/Acknowledge" onclick="directApprove('myInbxRow_<?php echo $cntr; ?>', '<?php echo $row[9]; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                    <img src="../cmn_images/thumbsUp28.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </td>
                                                            <td class="lovtd mailbox-star">
                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Reject" onclick="directReject('myInbxRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                    <img src="../cmn_images/thumbsDown28.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </td>
                                                            <td class="lovtd mailbox-star">
                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Request for Information" onclick="directInfoRqst('myInbxRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                    <img src="../cmn_images/info.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </td>
                                                            <td class="lovtd mailbox-star">
                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Reply to Message" onclick="sendGeneralMessage3('myInbxRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                    <img src="../cmn_images/reply_Mail.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </td>
                                                            <?php if ($row[10] == "1") { ?>
                                                                <td class="lovtd mailbox-subject">
                                                                    <a href="javascript:getOneMyInbxForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'myInbxDetForm', 'View Message (ID: <?php echo $row[0]; ?> - <?php
                                                                                                                                                                                                                                        echo str_replace("'", "\'", $row[2]);
                                                                                                                                                                                                                                        ?>)', <?php echo $row[0]; ?>, 1, <?php echo $pgNo ?>);" style="font-weight:normal;color:#0000FF;"><?php echo $row[2]; ?>
                                                                    </a>
                                                                </td>
                                                            <?php } else { ?>
                                                                <td class="lovtd mailbox-subject"><a href="javascript:getOneMyInbxForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'myInbxDetForm', 'View Message (ID: <?php echo $row[0]; ?> - <?php
                                                                                                                                                                                                                                                                    echo str_replace("'", "\'", $row[2]);
                                                                                                                                                                                                                                                                    ?>)', <?php echo $row[0]; ?>, 1, <?php echo $pgNo ?>);" style="font-weight:bold;color:#0000FF;"><?php echo $row[2]; ?>
                                                                    </a>
                                                                </td>
                                                            <?php } ?>
                                                            <td class="lovtd mailbox-name"><?php echo $row[11]; ?></td>
                                                            <td class="lovtd mailbox-name"><?php echo $row[3]; ?></td>
                                                            <td class="lovtd mailbox-name"><?php echo $row[13]; ?></td>
                                                            <td class="lovtd mailbox-date"><?php echo $row[5]; ?></td>
                                                            <td class="lovtd mailbox-name"><?php echo $row[8]; ?></td>
                                                            <td class="lovtd mailbox-attachment">
                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Attachments" onclick="directAttachment('myInbxRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                    <img src="../cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                            <!-- /.table -->
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer p-0">
                                    <div class="mailbox-controls">
                                        <!-- Check all button -->
                                        <button type="button" class="btn btn-default btn-sm checkbox-toggle" onclick="checkAllBtns('myInbxTblForm');">
                                            <i class="far fa-check-square"></i>
                                        </button>
                                        <button type="button" class="btn btn-default btn-sm checkbox-toggle" onclick="unCheckAllBtns('myInbxTblForm');">
                                            <i class="far fa-square"></i>
                                        </button>
                                        <button type="button" class="btn btn-default btn-sm" onclick="getMyInbx('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                        <button type="button" class="btn btn-default btn-sm" onclick="onReassign('myInbxTblForm');">
                                            <img src="../cmn_images/reassign_users.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            RE-ASSIGN SELECTED LINES
                                        </button>
                                        <div class="float-right">
                                            <?php echo $cntrStrtVal; ?>-<?php echo $cntrEndVal; ?>/<?php echo $total; ?>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-sm" onclick="getMyInbx('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');"><i class="fas fa-chevron-left"></i></button>
                                                <button type="button" class="btn btn-default btn-sm" onclick="getMyInbx('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');"><i class="fas fa-chevron-right"></i></button>
                                            </div>
                                            <!-- /.btn-group -->
                                        </div>
                                        <!-- /.float-right -->
                                    </div>
                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </section>
            <?php
            } else if ($vwtyp == "1") {
                //Get My Inbox Detail
                $result = get_MyInbxDetl($RoutingID);
            ?>
                <form id='myInbxDetForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="gridtable table" id="myInbxDetTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead style="display:none;">
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $output = "";
                                        $cmpID = "";
                                        $i = 0;
                                        $b = 0;
                                        $colsCnt = loc_db_num_fields($result);
                                        $msgID = -1;
                                        while ($row = loc_db_fetch_array($result)) {
                                            $style = "";
                                            $msgID = $row[1];
                                            for ($d = 0; $d < $colsCnt; $d++) {
                                                $style = "";
                                                $style2 = "";
                                                if (trim(loc_db_field_name($result, $d)) == "mt") {
                                                    $style = "style=\"display:none;\"";
                                                }
                                                if ($row[$d] == 'No') {
                                                    $style2 = "style=\"color:red;font-weight:bold;\"";
                                                } else if ($row[$d] == 'Yes') {
                                                    $style2 = "style=\"color:#32CD32;font-weight:bold;\"";
                                                }
                                                $indx = $i - 1;
                                                $hrf1 = "";
                                                $hrf2 = "";
                                                $labl = ucwords(str_replace("_", " ", loc_db_field_name($result, $d)));
                                                if ($d == 2 || $d == 3 || $d == 4) {
                                                    continue;
                                                } else {
                                                    $output .= "<tr $style>";
                                                    $output .= "<td width=\"20%\" class=\"likeheader\">" . $labl . ":</td>";
                                                }
                                                if ($d == 14 && $row[15] == '0') {
                                                    $cnrtLevel = (int)$row[25];
                                                    $arry1 = explode(";", $row[$d]);
                                                    $output .= "<td $style>";
                                                    for ($r = 0; $r < count($arry1); $r++) {
                                                        if ($arry1[$r] !== "" && $arry1[$r] != "None") {
                                                            $isadmnonly = getActionAdminOnly($row[0], $arry1[$r]);
                                                            $actionDesc = $arry1[$r]; //getActionDescription($row[0], $arry1[$r]);
                                                            if ($isadmnonly == '0' || $isMaster == 1) {
                                                                $webUrlDsply = getActionUrlDsplyTyp($row[0], $arry1[$r]);
                                                                $hrf1 = "<div style=\"padding:2px;float:left;\">"
                                                                    . "<button type=\"button\" class=\"btn btn-primary\""
                                                                    . " onclick=\"actionProcess('$cmpID', '$row[0]','$arry1[$r]','$webUrlDsply','$row[24]','" . str_replace(
                                                                        "'",
                                                                        "\'",
                                                                        $row[5]
                                                                    ) . "','" . str_replace("'", "\'", $row[8]) . "','$row[7]','$row[2]');\">";
                                                                $hrf2 = "</button></div>";
                                                                $output .= "$hrf1" . $actionDesc . "$hrf2";
                                                            }
                                                        }

                                                        if ($r == count($arry1) - 1) {
                                                            $webUrlDsply = "";
                                                            $hrf1 = "<div style=\"padding:2px;float:left;\">"
                                                                . "<button type=\"button\" class=\"btn btn-primary\" "
                                                                . "onclick=\"actionProcess('$cmpID', '$row[0]','View Attachments','$webUrlDsply','$row[24]','" . str_replace(
                                                                    "'",
                                                                    "\'",
                                                                    $row[5]
                                                                ) . "','" . str_replace("'", "\'", $row[8]) . "','$row[7]','$row[2]');\">";
                                                            $hrf2 = "</button></div>";
                                                            $output .= "$hrf1" . 'View Attachments' . "$hrf2";
                                                        }
                                                    }

                                                    $output .= "</td>";
                                                } else if ($d == 14 && $row[15] != '0') {
                                                    $output .= "<td width=\"70%\" $style2>$hrf1<span style=\"font-weight:bold;font-size:12px;color:green;\">NONE</span>$hrf2</td>";
                                                } else if ($d == 8) {
                                                    $output .= "<td width=\"70%\" $style2>$hrf1<span style=\"font-weight:bold;font-size:12px;color:blue;\">" . strtoupper($row[$d]) . "</span>$hrf2</td>";
                                                } else {
                                                    $output .= "<td width=\"70%\" $style2>$hrf1" . $row[$d] . "$hrf2</td>";
                                                }
                                                $output .= "</tr>";
                                            }

                                            $i++;
                                            $b++;
                                        }
                                        echo $output;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <div class="caption basic_person_lg">ACTION HISTORY</div>
                                <table class="gridtable table" id="myInbxActionsTable">
                                    <?php
                                    $result1 = get_ActionHistory($msgID);
                                    $colsCnt1 = loc_db_num_fields($result1);
                                    $output = "";
                                    $output .= "<thead><tr>";
                                    $output .= "<th style=\"font-weight:bold;\">No.</th>";
                                    $output .= "<th style=\"font-weight:bold;\">Approvers/Reviewers</th>";
                                    $output .= "<th style=\"font-weight:bold;\">Action Date</th>";
                                    $output .= "<th style=\"font-weight:bold;\">Action Performed</th>";
                                    $output .= "<th style=\"font-weight:bold;\">Performed By</th>";
                                    $output .= "</tr></thead>";
                                    $output .= "<tbody>";
                                    while ($row = loc_db_fetch_array($result1)) {
                                        $style = "";
                                        $style2 = "";
                                        $hrf1 = "";
                                        $hrf2 = "";
                                        $output .= "<tr>";
                                        for ($d = 0; $d < $colsCnt1; $d++) {
                                            if (trim(loc_db_field_name($result1, $d)) == "mt") {
                                                continue;
                                            }
                                            $output .= "<td $style>$hrf1" . $row[$d] . "$hrf2</td>";
                                        }
                                        $output .= "</tr>";
                                    }
                                    $output .= "</tbody>";
                                    echo $output;
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            <?php
            } else if ($vwtyp == 3) {
                //Reject Form 
                $RoutingID = isset($_POST['RoutingID']) ? cleanInputData($_POST['RoutingID']) : -1;
                $msgSubjct = isset($_POST['msgSubjct']) ? cleanInputData($_POST['msgSubjct']) : "";
                $msgDate = isset($_POST['msgDate']) ? cleanInputData($_POST['msgDate']) : "";
                $actionNm = isset($_POST['actionNm']) ? cleanInputData($_POST['actionNm']) : "Reject";
                $isResDiag = isset($_POST['isResDiag']) ? cleanInputData($_POST['isResDiag']) : "1";
            ?>
                <form class="form-horizontal" method="post">
                    <div class="row" style="margin-bottom: 5px;">
                        <div class="col-md-2"><label class="control-label">Notice: </label></div>
                        <div class="col-md-10">
                            <span style="color:#0000ff;font-style:italic;font-weight:bold;text-align: left;padding:0px;margin-left:0px;">
                                1 Workflow Document with Title (<?php echo $msgSubjct; ?>) and Date (<?php echo $msgDate; ?>) Selected for REJECTION!
                            </span>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 5px;">
                        <div class="col-md-2"><label for="wkfActionMsg" class="control-label">Rejection Reason:</label></div>
                        <div class="col-md-10">
                            <textarea rows="8" wrap="hard" autofocus required name="wkfActionMsg" id="wkfActionMsg" class="form-control rqrdFld"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div style="float:right;">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    <img src="../cmn_images/stop.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Cancel
                                </button>
                                <button type="button" class="btn btn-default" onclick="be4OnAct(<?php echo $RoutingID; ?>, '<?php echo $actionNm; ?>',<?php echo $isResDiag; ?>);">
                                    <img src="../cmn_images/thumbsDown28.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Reject
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            <?php
            } else if ($vwtyp == 4) {
                //Request for Information Form
                $RoutingID = isset($_POST['RoutingID']) ? cleanInputData($_POST['RoutingID']) : -1;
                $msgSubjct = isset($_POST['msgSubjct']) ? cleanInputData($_POST['msgSubjct']) : "";
                $msgDate = isset($_POST['msgDate']) ? cleanInputData($_POST['msgDate']) : "";
                $actionNm = isset($_POST['actionNm']) ? cleanInputData($_POST['actionNm']) : "Request for Information";
                $isResDiag = isset($_POST['isResDiag']) ? cleanInputData($_POST['isResDiag']) : "1";
            ?>
                <form class="form-horizontal" method="post">
                    <div class="row" style="margin-bottom: 5px;">
                        <div class="col-md-2"><label class="control-label">Person to Question(*): </label></div>
                        <div class="col-md-10">
                            <div class="input-group" style="width:100% !important;">
                                <input type="text" class="form-control rqrdFld" aria-label="..." id="wkfInfoRqstToPrsn" value="" style="width:100% !important;" readonly="true">
                                <input type="hidden" class="form-control" aria-label="..." id="wkfToPrsnLocID" value="">
                                <div class="input-group-append handCursor" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Persons', '', '', '', 'radio', true, '', 'wkfToPrsnLocID', 'wkfInfoRqstToPrsn', 'clear', 0, '');">
                                    <span class="input-group-text rhoclickable"><i class="fas fa-list-ul"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 5px;">
                        <div class="col-md-2"><label for="wkfActionMsg" class="control-label">Question?: </label></div>
                        <div class="col-md-10">
                            <textarea rows="8" wrap="hard" autofocus required name="wkfActionMsg" id="wkfActionMsg" class="form-control rqrdFld"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div style="float:right;">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    <img src="../cmn_images/stop.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Cancel
                                </button>
                                <button type="button" class="btn btn-default" onclick="be4OnAct(<?php echo $RoutingID; ?>, '<?php echo $actionNm; ?>',<?php echo $isResDiag; ?>);">
                                    <img src="../cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Send Message
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            <?php
            } else if ($vwtyp == 5) {
                //Respond Form
                $RoutingID = isset($_POST['RoutingID']) ? cleanInputData($_POST['RoutingID']) : -1;
                $msgSubjct = isset($_POST['msgSubjct']) ? cleanInputData($_POST['msgSubjct']) : "";
                $msgDate = isset($_POST['msgDate']) ? cleanInputData($_POST['msgDate']) : "";
                $toPrsnLocID = isset($_POST['toPrsnLocID']) ? cleanInputData($_POST['toPrsnLocID']) : "";
                $toPrsNm = isset($_POST['toPrsNm']) ? cleanInputData($_POST['toPrsNm']) : "";
                $actionNm = isset($_POST['actionNm']) ? cleanInputData($_POST['actionNm']) : "Respond";
                $isResDiag = isset($_POST['isResDiag']) ? cleanInputData($_POST['isResDiag']) : "1";
            ?>
                <form class="form-horizontal" method="post">
                    <div class="row" style="margin-bottom: 5px;">
                        <div class="col-md-2"><label class="control-label">Notice: </label></div>
                        <div class="col-md-10">
                            <span style="color:#0000ff;font-style:italic;font-weight:bold;text-align: left;padding:0px;margin-left:0px;">
                                1 Workflow Document with Title (<?php echo $msgSubjct; ?>) and Date (<?php echo $msgDate; ?>) Selected for a RESPONSE!
                            </span>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 5px;">
                        <div class="col-md-2"><label class="control-label">Person to Respond To(*): </label></div>
                        <div class="col-md-10">
                            <div class="input-group" style="width:100% !important;">
                                <input type="text" class="form-control rqrdFld" aria-label="..." id="wkfInfoRqstToPrsn" value="<?php echo $toPrsNm; ?>" style="width:100% !important;" readonly="true">
                                <input type="hidden" class="form-control" aria-label="..." id="wkfToPrsnLocID" value="<?php echo $toPrsnLocID; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 5px;">
                        <div class="col-md-2"><label for="wkfActionMsg" class="control-label">Question?: </label></div>
                        <div class="col-md-10">
                            <textarea rows="8" wrap="hard" autofocus required name="wkfActionMsg" id="wkfActionMsg" class="form-control rqrdFld"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div style="float:right;">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    <img src="../cmn_images/stop.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Cancel
                                </button>
                                <button type="button" class="btn btn-default" onclick="be4OnAct(<?php echo $RoutingID; ?>, '<?php echo $actionNm; ?>',<?php echo $isResDiag; ?>);">
                                    <img src="../cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Send Message
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            <?php
            } else if ($vwtyp == 6) {
                //Attachments Form
                $RoutingID = isset($_POST['RoutingID']) ? cleanInputData($_POST['RoutingID']) : -1;
                $msgSubjct = isset($_POST['msgSubjct']) ? cleanInputData($_POST['msgSubjct']) : "";
                downloadForm();
            } else if ($vwtyp == 7) {
                //Reassign Form
                $routingIDs = isset($_POST['routingIDs']) ? cleanInputData($_POST['routingIDs']) : -1;
                $inCount = isset($_POST['inCount']) ? cleanInputData($_POST['inCount']) : 0;
                $actionNm = isset($_POST['actionNm']) ? cleanInputData($_POST['actionNm']) : "Re-Assign";
                $isResDiag = isset($_POST['isResDiag']) ? cleanInputData($_POST['isResDiag']) : "1";
            ?>
                <form class="form-horizontal" method="post">
                    <div class="row" style="margin-bottom: 5px;">
                        <div class="col-md-2"><label class="control-label">Notice: </label></div>
                        <div class="col-md-10">
                            <span style="color:#0000ff;font-style:italic;font-weight:bold;text-align: left;padding:0px;margin-left:0px;">
                                <?php echo $inCount; ?> Workflow Notification(s) have been selected for RE-ASSIGNMENT!
                            </span>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 5px;">
                        <div class="col-md-2"><label class="control-label">Person to Question(*): </label></div>
                        <div class="col-md-10">
                            <div class="input-group" style="width:100% !important;">
                                <input type="text" class="form-control rqrdFld" aria-label="..." id="wkfInfoRqstToPrsn" value="" style="width:100% !important;" readonly="true">
                                <input type="hidden" class="form-control" aria-label="..." id="wkfToPrsnLocID" value="">
                                <input type="hidden" class="form-control" aria-label="..." id="wkfSlctdRoutingIDs" value="<?php echo $routingIDs; ?>">
                                <div class="input-group-append handCursor" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Persons', '', '', '', 'radio', true, '', 'wkfToPrsnLocID', 'wkfInfoRqstToPrsn', 'clear', 0, '');">
                                    <span class="input-group-text rhoclickable"><i class="fas fa-list-ul"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 5px;">
                        <div class="col-md-2"><label for="wkfActionMsg" class="control-label">Question?: </label></div>
                        <div class="col-md-10">
                            <textarea rows="8" wrap="hard" autofocus required name="wkfActionMsg" id="wkfActionMsg" class="form-control rqrdFld"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div style="float:right;">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    <img src="../cmn_images/stop.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Cancel
                                </button>
                                <button type="button" class="btn btn-default" onclick="be4OnAct(<?php echo $RoutingID; ?>, '<?php echo $actionNm; ?>',<?php echo $isResDiag; ?>);">
                                    <img src="../cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Send Message
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            <?php
            } else if ($vwtyp == 8) {
                //Get Action History
                $msgID = (float) getGnrlRecNm("wkf.wkf_actual_msgs_routng", "routing_id", "msg_id", $RoutingID);
            ?>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        $output = "<div class=\"caption basic_person_lg\">ACTION HISTORY</div>";
                        ?>
                        <table class="gridtable" id="gnrlInbxActionsTable" cellspacing="0" cellpadding="0" width="100%" style="width:100%;min-width: 300px;">
                            <?php
                            $result1 = get_ActionHistory($msgID);
                            $colsCnt1 = loc_db_num_fields($result1);
                            $output .= "<thead><tr>";
                            $output .= "<th width=\"170px\" style=\"font-weight:bold;\">No.</th>";
                            $output .= "<th width=\"250px\" style=\"font-weight:bold;\">Approvers/Reviewers</th>";
                            $output .= "<th width=\"250px\" style=\"font-weight:bold;\">Action Date</th>";
                            $output .= "<th width=\"250px\" style=\"font-weight:bold;\">Action Performed</th>";
                            $output .= "<th width=\"250px\" style=\"font-weight:bold;\">Performed By</th>";
                            $output .= "</tr></thead>";
                            $output .= "<tbody>";
                            while ($row = loc_db_fetch_array($result1)) {
                                $style = "";
                                $style2 = "";
                                $hrf1 = "";
                                $hrf2 = "";
                                $output .= "<tr>";
                                for ($d = 0; $d < $colsCnt1; $d++) {
                                    if (trim(loc_db_field_name($result1, $d)) == "mt") {
                                        continue;
                                    }
                                    $output .= "<td $style>$hrf1" . $row[$d] . "$hrf2</td>";
                                }
                                $output .= "</tr>";
                            }
                            $output .= "</tbody>";
                            echo $output;
                            ?>
                        </table>
                    </div>
                </div>
<?php
            } else if ($vwtyp == 9) {
            } else if ($vwtyp == 10) {
            } else if ($vwtyp == 11) {
            }
        }
    } else {
        sessionInvalid();
    }
}

function get_ActionHistory($msgID)
{
    $selSQL = "SELECT row_number() OVER (ORDER BY (CASE WHEN TBL2.date_action_ws_prfmd='' or tbl1.level=-999 THEN 
       TBL2.date_sent
       ELSE 
       TBL2.date_action_ws_prfmd
       END) DESC) AS \"No.  \", 
tbl1.apprvrs AS \"Approvers/Reviewers\",
 (CASE WHEN TBL2.date_action_ws_prfmd='' or tbl1.level=-999 THEN 
       TBL2.date_sent
       ELSE 
       TBL2.date_action_ws_prfmd
       END) mt,
 CASE WHEN TBL2.date_action_ws_prfmd='' or tbl1.level=-999 THEN 
       to_char(to_timestamp(TBL2.date_sent,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS')
       ELSE 
       to_char(to_timestamp(TBL2.date_action_ws_prfmd,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS')
       END Action_Date,
tbl1.action_performed,
tbl1.who_prfrmd
 FROM (SELECT a.msg_id, 
string_agg(prs.get_prsn_name(a.to_prsn_id)||' ('||prs.get_prsn_loc_id(a.to_prsn_id)||')', ', ') apprvrs, 
       MAX(a.routing_id) routing_id, 
       CASE WHEN a.status_aftr_action='' THEN
       'Pending'
       ELSE
       a.status_aftr_action
       END action_performed, 
       a.to_prsns_hrchy_level AS \"level\",
       string_agg(prs.get_prsn_name(a.who_prfmd_action)||' ('||prs.get_prsn_loc_id(a.who_prfmd_action)||')', ', ') who_prfrmd
  FROM wkf.wkf_actual_msgs_routng a, 
  wkf.wkf_actual_msgs_hdr b
  where a.msg_id=b.msg_id and b.msg_id=" . $msgID . "
  /*and (a.who_prfmd_action=a.to_prsn_id or a.who_prfmd_action<=0)*/  
  GROUP BY 1,4,5
  UNION
  SELECT a.msg_id, 
string_agg(prs.get_prsn_name(a.from_prsn_id)||' ('||prs.get_prsn_loc_id(a.from_prsn_id)||')', ', ') apprvrs, 
       MIN(a.routing_id) routing_id, 
       'Initiated' action_performed, 
       -999 AS \"level\",
       string_agg(prs.get_prsn_name(a.who_prfmd_action)||' ('||prs.get_prsn_loc_id(a.who_prfmd_action)||')', ', ') who_prfrmd
  FROM wkf.wkf_actual_msgs_routng a, 
  wkf.wkf_actual_msgs_hdr b
  where a.msg_id=b.msg_id and b.msg_id=" . $msgID . "
  and a.routing_id=(SELECT MIN(z.routing_id) FROM wkf.wkf_actual_msgs_routng z WHERE z.msg_id=" . $msgID . ")  
  GROUP BY 1,4,5) tbl1,
  wkf.wkf_actual_msgs_routng tbl2
  WHERE TBL1.routing_id=TBL2.routing_id
  ORDER BY (CASE WHEN TBL2.date_action_ws_prfmd='' or tbl1.level=-999 THEN 
       TBL2.date_sent
       ELSE 
       TBL2.date_action_ws_prfmd
       END) DESC";
    //echo $selSQL;
    $result = executeSQLNoParams($selSQL);
    return $result;
}

function get_MyInbx($searchFor, $searchIn, $offset, $limit_size)
{
    global $user_Name;
    global $qStrtDte;
    global $qEndDte;
    global $qActvOnly;
    global $qNonLgn;
    global $isMaster;
    global $qNonAknwldg;
    //echo "searchFor::" . $searchFor;
    //echo "searchIn::" . $searchIn;
    $user_Name = $_SESSION['UNAME'];

    $prsnID = getUserPrsnID($user_Name);
    $wherecls = "";

    if ($searchIn == "Status") {
        $wherecls .= " AND (CASE WHEN a.is_action_done='0' THEN a.msg_status ELSE a.status_aftr_action END ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Source App") {
        $wherecls .= " AND ((c.app_name ilike '%" . loc_db_escape_string($searchFor) . "%')"
            . " or (c.source_module ilike '%" . loc_db_escape_string($searchFor) . "%'))";
    } else if ($searchIn == "Subject") {
        $wherecls .= " AND (b.msg_hdr ilike '%" . loc_db_escape_string($searchFor) . "%')";
    } else if ($searchIn == "Person From") {
        $wherecls .= " AND (COALESCE(prs.get_prsn_name(a.from_prsn_id) || ' (' || prs.get_prsn_loc_id(a.from_prsn_id) || ')', '') ilike '%" . loc_db_escape_string($searchFor) . "%')";
    } else if ($searchIn == "Person To") {
        $wherecls .= " AND (COALESCE(prs.get_prsn_name(a.to_prsn_id) || ' (' || prs.get_prsn_loc_id(a.to_prsn_id) || ')', '') ilike '%" . loc_db_escape_string($searchFor) . "%')";
    } else if ($searchIn == "Message Type") {
        $wherecls .= " AND (b.msg_typ ilike '%" . loc_db_escape_string($searchFor) . "%')";
    }

    if ($qStrtDte != "") {
        $wherecls .= " AND (a.date_sent >= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qStrtDte)) . "')";
    }
    if ($qEndDte != "") {
        $wherecls .= " AND (a.date_sent <= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qEndDte)) . "')";
    }
    if ($qActvOnly == "true") {
        $wherecls .= " AND (a.is_action_done = '0')";
    }
    if ($qNonLgn == "true") {
        $wherecls .= " AND (lower(c.app_name) NOT like lower('Login'))";
    }
    if ($qNonAknwldg == "true") {
        $wherecls .= " AND (lower(a.action_to_perform) NOT like lower('%Acknowledge%'))";
    }
    $extrWhr = " AND (a.to_prsn_id = " . $prsnID . ")";
    if ($isMaster == 2) {
        $extrWhr = " AND (a.to_prsn_id = " . $prsnID . ")";
    } else if ($isMaster == 1) {
        $extrWhr = "";
    }
    $sqlStr = "SELECT  a.routing_id mt, a.msg_id mt, b.msg_hdr message_header, "
        . "CASE WHEN a.from_prsn_id<=0 THEN 'System Administrator' 
        ELSE prs.get_prsn_name(a.from_prsn_id) || ' (' || prs.get_prsn_loc_id(a.from_prsn_id) || ')' 
        END \"from\", a.to_prsn_id mt, to_char(to_timestamp(a.date_sent,
        'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') \"Date_sent\", a.created_by mt, a.creation_date mt, 
      CASE WHEN a.is_action_done='0' THEN a.msg_status ELSE a.status_aftr_action END message_status,  
      CASE WHEN a.is_action_done='0' THEN a.action_to_perform ELSE a.nxt_action_to_prfm END \"Action(s) To Perform\", 
      a.is_action_done mt, c.app_name mt, c.source_module mt, 
      CASE WHEN a.to_prsn_id<=0 THEN 'System Administrator' 
        ELSE prs.get_prsn_name(a.to_prsn_id) || ' (' || prs.get_prsn_loc_id(a.to_prsn_id) || ')' 
        END \"to\", b.msg_typ message_type, prs.get_prsn_loc_id(a.from_prsn_id) locid, a.from_prsn_id, a.to_prsn_id 
  FROM wkf.wkf_actual_msgs_routng a, wkf.wkf_actual_msgs_hdr b, wkf.wkf_apps c
WHERE ((c.app_id=b.app_id) AND (a.msg_id=b.msg_id)" . $extrWhr . $wherecls .
        ") ORDER BY a.date_sent DESC LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_MyInbxTtls($searchFor, $searchIn)
{
    global $user_Name;
    global $qStrtDte;
    global $qEndDte;
    global $qActvOnly;
    global $qNonLgn;
    global $isMaster;
    global $qNonAknwldg;
    $user_Name = $_SESSION['UNAME'];

    $prsnID = getUserPrsnID($user_Name);
    $wherecls = "";

    if ($searchIn == "Status") {
        $wherecls .= " AND (CASE WHEN a.is_action_done='0' THEN a.msg_status ELSE a.status_aftr_action END ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Source App") {
        $wherecls .= " AND ((c.app_name ilike '%" . loc_db_escape_string($searchFor) . "%')"
            . " or (c.source_module ilike '%" . loc_db_escape_string($searchFor) . "%'))";
    } else if ($searchIn == "Subject") {
        $wherecls .= " AND (b.msg_hdr ilike '%" . loc_db_escape_string($searchFor) . "%')";
    } else if ($searchIn == "Person From") {
        $wherecls .= " AND (COALESCE(prs.get_prsn_name(a.from_prsn_id) || ' (' || prs.get_prsn_loc_id(a.from_prsn_id) || ')', '') ilike '%" . loc_db_escape_string($searchFor) . "%')";
    } else if ($searchIn == "Person To") {
        $wherecls .= " AND (COALESCE(prs.get_prsn_name(a.to_prsn_id) || ' (' || prs.get_prsn_loc_id(a.to_prsn_id) || ')', '') ilike '%" . loc_db_escape_string($searchFor) . "%')";
    } else if ($searchIn == "Message Type") {
        $wherecls .= " AND (b.msg_typ ilike '%" . loc_db_escape_string($searchFor) . "%')";
    }

    if ($qStrtDte != "") {
        $wherecls .= " AND (a.date_sent >= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qStrtDte)) . "')";
    }
    if ($qEndDte != "") {
        $wherecls .= " AND (a.date_sent <= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qEndDte)) . "')";
    }
    if ($qActvOnly == "true") {
        $wherecls .= " AND (a.is_action_done = '0')";
    }
    if ($qNonLgn == "true") {
        $wherecls .= " AND (lower(c.app_name) NOT like lower('Login'))";
    }
    if ($qNonAknwldg == "true") {
        $wherecls .= " AND (lower(a.action_to_perform) NOT like lower('%Acknowledge%'))";
    }
    $extrWhr = " AND (a.to_prsn_id = " . $prsnID . ")";
    if ($isMaster == 2) {
        $extrWhr = " AND (a.to_prsn_id = " . $prsnID . ")";
    } else if ($isMaster == 1) {
        $extrWhr = "";
    }
    $sqlStr = "SELECT count(1) 
  FROM wkf.wkf_actual_msgs_routng a, wkf.wkf_actual_msgs_hdr b, wkf.wkf_apps c
WHERE ((c.app_id=b.app_id) AND (a.msg_id=b.msg_id)" . $extrWhr . $wherecls . ")";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_MyInbxDetl($routingID)
{
    $wherecls = "";
    $wherecls = " AND (a.routing_id = " . loc_db_escape_string($routingID) . ")";

    $sqlStr = "SELECT  a.routing_id mt, a.msg_id mt, 
        c.app_name source_app_name, c.source_module, b.msg_typ message_type,
        CASE WHEN a.from_prsn_id<0 THEN 'System Administrator' 
        ELSE prs.get_prsn_name(a.from_prsn_id) 
        END \"from\", prs.get_prsn_name(a.to_prsn_id) \"to\", to_char(to_timestamp(a.date_sent,
        'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') \"Date_sent\", b.msg_hdr message_header, 
       a.action_comments, 
        b.msg_body message_body,  a.created_by mt, a.creation_date mt, 
       a.msg_status message_status, a.action_to_perform \"Action(s) To Perform\", a.is_action_done mt, 
       CASE WHEN a.is_action_done='1' THEN 'Yes' ELSE 'No' END \"Has Message Been Acted On?\", 
       prs.get_prsn_name(a.who_prfmd_action) \"Action Performed By\", 
       CASE WHEN a.date_action_ws_prfmd='' THEN '' ELSE to_char(to_timestamp(a.date_action_ws_prfmd,
        'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') END date_action_was_performed, 
       a.status_aftr_action new_message_status_after_action, 
       a.nxt_action_to_prfm next_action_to_perform, 
       a.who_prfms_next_action \"Who Performes Next Action\", 
       a.last_update_by mt, a.last_update_date mt,
       prs.get_prsn_loc_id(a.from_prsn_id) mt,
       a.to_prsns_hrchy_level mt
  FROM wkf.wkf_actual_msgs_routng a, wkf.wkf_actual_msgs_hdr b, wkf.wkf_apps c
WHERE ((c.app_id=b.app_id) AND (a.msg_id=b.msg_id)$wherecls)";

    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function saveReassignForm()
{
    global $usrID;
    global $user_Name;
    global $formArray;

    $inptSlctdRtngs = isset($formArray['routingIDs']) ? cleanInputData($formArray['routingIDs']) : "";
    //$formName = isset($formArray['formNme']) ? cleanInputData($formArray['formNme']) : "";
    $nwPrsnLocID = isset($formArray['prsnLocID']) ? cleanInputData($formArray['prsnLocID']) : "";
    $raCmmnts = isset($formArray['noticeDetails']) ? cleanInputData($formArray['noticeDetails']) : "";

    $fromPrsnID = getUserPrsnID($user_Name);
    $usrFullNm = getPrsnFullNm($fromPrsnID);

    $nwPrsnID = getPersonID($nwPrsnLocID);
    $nwPrsnFullNm = getPrsnFullNm($nwPrsnID);
    $arry1 = preg_split('/\|/', $inptSlctdRtngs, -1, PREG_SPLIT_NO_EMPTY); //explode("|", $inptSlctdRtngs);
    $affctd = 0;
    $affctd1 = 0;
    $datestr = getFrmtdDB_Date_time();
    for ($i = 0; $i < count($arry1); $i++) {
        $slctRtngID = (float) $arry1[$i];

        $orgPrsnID = (float) getGnrlRecNm("wkf.wkf_actual_msgs_routng", "routing_id", "to_prsn_id", $slctRtngID);
        $orgPrsnNm = getPrsnFullNm($orgPrsnID);
        $rtngMsgID = (float) getGnrlRecNm("wkf.wkf_actual_msgs_routng", "routing_id", "msg_id", $slctRtngID);
        $isActionDone = getGnrlRecNm("wkf.wkf_actual_msgs_routng", "routing_id", "is_action_done", $slctRtngID);

        if ($isActionDone == '0') {
            $affctd += updtWkfMsgReasgnRtng($slctRtngID, $fromPrsnID, $nwPrsnID, $usrID);
            $msgbodyAddOn = "";
            $msgbodyAddOn .= "RE-ASSIGNMENT ON $datestr:- This document has been re-assigned from " . $orgPrsnNm . "'s Inbox to $nwPrsnFullNm" . "'s Inbox by $usrFullNm with the ff Message:<br/>";
            $msgbodyAddOn .= $raCmmnts . "<br/><br/>";
            $affctd1 += updtWkfMsgRtngCmnts($slctRtngID, $msgbodyAddOn, $usrID);
        }
    }

    $msg = "";
    $dsply = "";
    $res = "";
    if ($affctd > 0) {
        $dsply .= "<br/>$affctd Workflow Document(s) Successfully Re-routed to $nwPrsnFullNm!";
        $dsply .= "<br/>$affctd1 Workflow Document(s) Message Comments Successfully Updated!";
        /* if ($formName == "myInbxForm") {
          $dsply.="<br/>Please <a name=\"finishBtn\" id=\"finishBtn\" class=\"button\" onclick=\"getMyInbxPage('nav_inbx', 'first',0,'refresh');\">Click Here to finish!</a>";
          } else {
          $dsply.="<br/>Please <a name=\"finishBtn\" id=\"finishBtn\" class=\"button\" onclick=\"closeMydialog();\">Click Here to finish!</a>";
          } */
        $msg = "<p style = \"text-align:left; color:#32CD32;\"><b><i>$dsply</i></b></p>"; //#32CD32
    } else {
        $dsply .= "<br/>Update Failed! No Workflow Document(s) Re-routed";
        /* if ($formName == "myInbxForm") {
          $dsply.="<br/>Please <a name=\"finishBtn\" id=\"finishBtn\" class=\"button\" onclick=\"getMyInbxPage('nav_inbx', 'first',0,'refresh');\">Click Here to Close!</a>";
          } else {
          $dsply.="<br/>Please <a name=\"finishBtn\" id=\"finishBtn\" class=\"button\" onclick=\"closeMydialog();\">Click Here to Close!</a>";
          } */
        $msg = "<p style = \"text-align:left; color:#ff0000;\"><b><i>$dsply</i></b></p>";
    }
    $res .= $msg;
    return $res;
}

function actOnMsgSQL($routingID, $usr_ID, $actyp)
{
    $dsply = "";
    if ($routingID > 0) {
        /*
         * 1. Get sql_behind_action_to_be_performed 
         * E.g. "select wkf.action_sql_for_login({:routing_id},{:userID});"
         * 2. Replace {:routing_id} with $routingID and {:userID} with $usr_ID
         * 3. execute this new sql statement
         * 4. if return is |SUCCESS| display success msg and echo table or detail view
         */
        $sql = getActionSQL($routingID, $actyp);
        $sql = str_replace("{:routing_id}", "$routingID", $sql);
        $sql = str_replace("{:userID}", "$usr_ID", $sql);
        $sql = str_replace("{:actToPrfm}", "$actyp", $sql);
        //echo $sql;{:actToPrfm}
        $rtrn_msg = executeActionOnMsg($sql);
        $dsply = str_replace("|SUCCESS|", "", $rtrn_msg);
        $dsply = str_replace("|ERROR|", "", $rtrn_msg);
        if (strpos($rtrn_msg, "|SUCCESS|") === FALSE) {
            $dsply = "<p style=\"text-align:center;font-weight:bold;font-style:italic; color:red;\">$dsply</p>";
        } else {
            $dsply = "<p style=\"text-align:center;font-weight:bold;font-style:italic; color:green;\">$dsply</p>";
        }
        echo $dsply;
    }
}

function downloadForm()
{
    global $RoutingID;
    global $smplTokenWord1;

    $msgID = getGnrlRecNm("wkf.wkf_actual_msgs_routng", "routing_id", "msg_id", $RoutingID);
    $attchmnts = getGnrlRecNm("wkf.wkf_actual_msgs_hdr", "msg_id", "attchments", $msgID);
    $attchments_desc = getGnrlRecNm("wkf.wkf_actual_msgs_hdr", "msg_id", "attchments_desc", $msgID);

    $output = "<div id='rho_form222'>";
    $output .= "<table style=\"width:100%;border-collapse: collapse;border-spacing: 0;margin-top:10px;\" class=\"gridtable table\"><tbody>";
    $arry1 = explode(";", $attchmnts);
    $arry2 = explode(";", $attchments_desc);
    for ($r = 0; $r < count($arry1); $r++) {
        if ($arry1[$r] !== "") {
            $arry1[$r] = encrypt1($arry1[$r], $smplTokenWord1);
            $hrf1 = "<tr><td><div style=\"padding:2px;float:none;\">"
                . "<button type=\"button\"  class=\"btn btn-primary\" "
                . "onclick=\"dwnldAjxCall('grp=1&typ=11&q=Download&fnm=$arry1[$r]','FileNo$r');\">" . "File No." . ($r + 1) . "-" . $arry2[$r] . " ";
            $hrf2 = "</button></div></td><td><div id=\"FileNo$r\"></div></td></tr>";
            $output .= "$hrf1" . "" . "$hrf2";
        }
    }
    $output .= "</tbody></table></div>";
    echo $output;
}
?>