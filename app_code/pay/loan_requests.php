<?php
$canAdd = test_prmssns($dfltPrvldgs[42], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[43], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[44], $mdlNm);
$canVoid = test_prmssns($dfltPrvldgs[10], $mdlNm);
$canApprove = test_prmssns($dfltPrvldgs[23], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 100;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
$defaultBrkdwnLOV = "Transaction Amount Breakdown Parameters";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Loan and Payment Requests Header */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel === true) {
                    echo deleteTrnsRqsts($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
            } else if ($actyp == 5) {
                /* Delete Attachment */
                $attchmentID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
                $docTrnsNum = isset($_POST['docTrnsNum']) ? cleanInputData($_POST['docTrnsNum']) : -1;
                if ($canEdt) {
                    echo deleteInvstTransDoc($attchmentID, $docTrnsNum);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Loan and Payment Requests Transaction
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $sbmtdPayTrnsRqstsID = isset($_POST['sbmtdPayTrnsRqstsID']) ? (float) cleanInputData($_POST['sbmtdPayTrnsRqstsID']) : -1;
                $lnkdPayTrnsRqstsID = isset($_POST['lnkdPayTrnsRqstsID']) ? (float) cleanInputData($_POST['lnkdPayTrnsRqstsID']) : -1;
                if (!(($canEdt === true && $sbmtdPayTrnsRqstsID > 0) || ($canAdd === true && $sbmtdPayTrnsRqstsID <= 0))) {
                    restricted();
                    exit();
                }
                $payTrnsRqstsType = isset($_POST['payTrnsRqstsType']) ? cleanInputData($_POST['payTrnsRqstsType']) : "LOAN";
                $payTrnsRqstsPrsnID = isset($_POST['payTrnsRqstsPrsnID']) ? (float) cleanInputData($_POST['payTrnsRqstsPrsnID']) : -1;
                $payTrnsRqstsPrsnNm = isset($_POST['payTrnsRqstsPrsnNm']) ? cleanInputData($_POST['payTrnsRqstsPrsnNm']) : '';
                $payTrnsRqstsItmTypID = isset($_POST['payTrnsRqstsItmTypID']) ? (float) cleanInputData($_POST['payTrnsRqstsItmTypID']) : -1;
                $payTrnsRqstsItmTypNm = isset($_POST['payTrnsRqstsItmTypNm']) ? cleanInputData($_POST['payTrnsRqstsItmTypNm']) : '';
                $payTrnsRqstsClsfctn = isset($_POST['payTrnsRqstsClsfctn']) ? cleanInputData($_POST['payTrnsRqstsClsfctn']) : '';
                $payTrnsRqstsDesc = isset($_POST['payTrnsRqstsDesc']) ? cleanInputData($_POST['payTrnsRqstsDesc']) : '';
                $payTrnsRqstsDate = $gnrlTrnsDteYMDHMS;
                $payTrnsRqstsInvcCur = $fnccurnm;
                $payTrnsRqstsInvcCurID = $fnccurid;
                $payTrnsRqstsAmnt = isset($_POST['payTrnsRqstsAmnt']) ? (float) cleanInputData($_POST['payTrnsRqstsAmnt']) : 0;
                $payTrnsRqstsHsAgreed = isset($_POST['payTrnsRqstsHsAgreed']) ? cleanInputData($_POST['payTrnsRqstsHsAgreed']) : "NO";
                $hsAgreed = ($payTrnsRqstsHsAgreed == "YES") ? true : false;
                $shdSbmt = isset($_POST['shdSbmt']) ? (int) cleanInputData($_POST['shdSbmt']) : 0;

                if (strlen($payTrnsRqstsDesc) > 499) {
                    $payTrnsRqstsDesc = substr($payTrnsRqstsDesc, 0, 499);
                }
                $exitErrMsg = "";
                if ($payTrnsRqstsPrsnID <= 0 || $payTrnsRqstsItmTypID <= 0) {
                    $exitErrMsg .= "Requestor and Item Type cannot be empty!<br/>";
                }
                if ($payTrnsRqstsDesc == "" || $payTrnsRqstsClsfctn == "") {
                    $exitErrMsg .= "Classification and Narration are required fields!<br/>";
                }
                if ($payTrnsRqstsAmnt <= 0) {
                    $exitErrMsg .= "Amount cannot be zero or less!<br/>";
                }
                /* if ($payTrnsRqstsType == "SETTLEMENT" && $lnkdPayTrnsRqstsID <= 0) {
                  $exitErrMsg .= "Linked Loan cannot be empty for settlements!<br/>";
                  } */
                $apprvlStatus = "Not Submitted";
                $nxtApprvlActn = "Approve";
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['sbmtdPayTrnsRqstsID'] = $sbmtdPayTrnsRqstsID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                if ($sbmtdPayTrnsRqstsID <= 0) {
                    $sbmtdPayTrnsRqstsID = getNewPayTrnsRqstsID();
                    $afftctd += createTrnsRqstsDocHdr(
                        $sbmtdPayTrnsRqstsID,
                        $payTrnsRqstsPrsnID,
                        $payTrnsRqstsType,
                        $payTrnsRqstsItmTypID,
                        $payTrnsRqstsDesc,
                        $payTrnsRqstsClsfctn,
                        $payTrnsRqstsAmnt,
                        $hsAgreed,
                        $lnkdPayTrnsRqstsID
                    );
                } else if ($sbmtdPayTrnsRqstsID > 0) {
                    $afftctd += updtTrnsRqstsDocHdr(
                        $sbmtdPayTrnsRqstsID,
                        $payTrnsRqstsPrsnID,
                        $payTrnsRqstsType,
                        $payTrnsRqstsItmTypID,
                        $payTrnsRqstsDesc,
                        $payTrnsRqstsClsfctn,
                        $payTrnsRqstsAmnt,
                        $hsAgreed,
                        $lnkdPayTrnsRqstsID
                    );
                }
                if ($shdSbmt == 2 && $payTrnsRqstsHsAgreed != "YES") {
                    $exitErrMsg .= "<span style=\"font-weight:bold;font-size:14px;font-style:italic;font-family:georgia;\">You have to agree to the terms and conditions first!</span><br/>";
                }
                $payTrnsRqstsNetAmnt = (float) getGnrlRecNm("pay.pay_loan_pymnt_rqsts", "pay_request_id", "net_loan_amount", $sbmtdPayTrnsRqstsID);
                if ($shdSbmt == 2 && $payTrnsRqstsNetAmnt <= 0) {
                    $exitErrMsg .= "<span style=\"font-weight:bold;font-size:14px;font-style:italic;font-family:georgia;\">Net Amount to Credit cannot be zero or less!</span><br/>";
                }
                $payTrnsRqstsMinAmnt = (float)getGnrlRecNm("pay.pay_loan_pymnt_rqsts", "pay_request_id", "min_loan_amount", $sbmtdPayTrnsRqstsID);
                if ($shdSbmt == 2 && $payTrnsRqstsAmnt < $payTrnsRqstsMinAmnt) {
                    $exitErrMsg .= "<span style=\"font-weight:bold;font-size:14px;font-style:italic;font-family:georgia;\">Request Amount (" . number_format($payTrnsRqstsAmnt, 2) . ") cannot be below Min Amount Allowed (" . number_format($payTrnsRqstsMinAmnt, 2) . ")!</span><br/>";
                }
                $payTrnsRqstsEnfrcMx = getGnrlRecNm("pay.pay_loan_pymnt_rqsts", "pay_request_id", "enforce_max_amnt", $sbmtdPayTrnsRqstsID);
                $payTrnsRqstsMaxAmnt = (float)getGnrlRecNm("pay.pay_loan_pymnt_rqsts", "pay_request_id", "max_loan_amount", $sbmtdPayTrnsRqstsID);
                if ($shdSbmt == 2 && $payTrnsRqstsEnfrcMx == "1" && $payTrnsRqstsAmnt > $payTrnsRqstsMaxAmnt) {
                    $exitErrMsg .= "<span style=\"font-weight:bold;font-size:14px;font-style:italic;font-family:georgia;\">Request Amount (" . number_format($payTrnsRqstsAmnt, 2) . ") cannot be above Max Amount Allowed (" . number_format($payTrnsRqstsMaxAmnt, 2) . ")!</span><br/>";
                }
                if ($exitErrMsg != "") {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd . " " . ucfirst(strtolower($payTrnsRqstsType)) . " Requests Successfully Saved!"
                        . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                } else {
                    $errMsg = "";
                    if ($shdSbmt == 2) {
                        $srcDocID = $sbmtdPayTrnsRqstsID;
                        $srcDocType = "Internal Pay " . ucfirst(strtolower($payTrnsRqstsType)) . " Requests";
                        $routingID = -1;
                        $inptSlctdRtngs = "";
                        $actionToPrfrm = "Initiate";
                        $errMsg = "<br/><br/>" . loanPayReqMsgActns($routingID, $inptSlctdRtngs, $actionToPrfrm, $srcDocID, $srcDocType);
                    }
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd . " " . ucfirst(strtolower($payTrnsRqstsType)) . " Requests Successfully Saved!" . $errMsg;
                }
                $arr_content['percent'] = 100;
                $arr_content['sbmtdPayTrnsRqstsID'] = $sbmtdPayTrnsRqstsID;
                $arr_content['message'] = $exitErrMsg;
                echo json_encode($arr_content);
                exit();
            } else if ($actyp == 2) {
                //Upload Attachement
                header("content-type:application/json");
                $attchmentID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
                $sbmtdPayTrnsRqstsID = isset($_POST['sbmtdPayTrnsRqstsID']) ? cleanInputData($_POST['sbmtdPayTrnsRqstsID']) : -1;
                $payTransType = "LOAN_N_PAY";
                if (!($canEdt || $canAdd)) {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $docCtrgrName = isset($_POST['docCtrgrName']) ? cleanInputData($_POST['docCtrgrName']) : "";
                $nwImgLoc = "";
                $errMsg = "";
                $pkID = $sbmtdPayTrnsRqstsID;
                if ($attchmentID > 0) {
                    uploadDaInvstTransDoc($attchmentID, $nwImgLoc, $errMsg);
                } else {
                    $attchmentID = getNewInvstTransDocID();
                    createInvstTransDoc($attchmentID, $pkID, $payTransType, $docCtrgrName, "");
                    uploadDaInvstTransDoc($attchmentID, $nwImgLoc, $errMsg);
                }
                $arr_content['attchID'] = $attchmentID;
                if (strpos($errMsg, "Document Stored Successfully!<br/>") === FALSE) {
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $errMsg;
                } else {
                    $doc_src = $ftp_base_db_fldr . "/PayDocs/" . $nwImgLoc;
                    $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                    if (file_exists($doc_src)) {
                        //file exists!
                    } else {
                        //file does not exist.
                        $doc_src_encrpt = "None";
                    }
                    $arr_content['crptpath'] = $doc_src_encrpt;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $errMsg;
                }
                echo json_encode($arr_content);
                exit();
            } else if ($actyp == 40) {
                //Submit Loan Requests to Workflow
                $sbmtdPayTrnsRqstsID = isset($_POST['sbmtdPayTrnsRqstsID']) ? (float) cleanInputData($_POST['sbmtdPayTrnsRqstsID']) : -1;
                $payTrnsRqstsType = getGnrlRecNm("pay.pay_loan_pymnt_rqsts", "pay_request_id", "request_type", $sbmtdPayTrnsRqstsID);
                $RoutingID = -1;
                if (isset($_POST['RoutingID'])) {
                    $RoutingID = cleanInputData($_POST['RoutingID']);
                }
                if ($RoutingID <= 0) {
                    $RoutingID = getMxRoutingID($sbmtdPayTrnsRqstsID, "Internal Pay " . ucfirst(strtolower($payTrnsRqstsType)) . " Requests");
                }
                if ($RoutingID <= 0) {
                    $srcDocID = $sbmtdPayTrnsRqstsID;
                    $srcDocType = "Internal Pay " . ucfirst(strtolower($payTrnsRqstsType)) . " Requests";
                    $routingID = -1;
                    $inptSlctdRtngs = "";
                    $actionToPrfrm = "Initiate";
                    echo loanPayReqMsgActns($routingID, $inptSlctdRtngs, $actionToPrfrm, $srcDocID, $srcDocType);
                } else {
                    $actiontyp = isset($_POST['actiontyp']) ? $_POST['actiontyp'] : "";
                    $usrID = $_SESSION['USRID'];
                    $arry1 = explode(";", $actiontyp);
                    for ($r = 0; $r < count($arry1); $r++) {
                        if ($arry1[$r] !== "") {
                            $srcDocID = -1;
                            $srcDocType = "Internal Pay " . ucfirst(strtolower($payTrnsRqstsType)) . " Requests";
                            $inptSlctdRtngs = "";
                            $routingID = $RoutingID;
                            $actionToPrfrm = $arry1[$r];
                            echo loanPayReqMsgActns($routingID, $inptSlctdRtngs, $actionToPrfrm, $srcDocID, $srcDocType);
                        }
                    }
                }
            }
        } else if ($qstr == "VOID") {
            if ($actyp == 1) {
            }
        } else {
            if ($vwtyp == 0) {
                //Loan and Payment Requests Transactions
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                <span style=\"text-decoration:none;\">Loan and Payment Requests</span>
                            </li>
                           </ul>
                          </div>";
                $error = "";
                $searchAll = true;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 100;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
                $qShwUnpstdOnly = false;
                if (isset($_POST['qShwUnpstdOnly'])) {
                    $qShwUnpstdOnly = cleanInputData($_POST['qShwUnpstdOnly']) === "true" ? true : false;
                }
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                if ($vwtyp == 0) {
                    $total = get_Total_TrnsRqstsDoc($srchFor, $srchIn, $orgID, $qShwUnpstdOnly);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_TrnsRqstsDocHdr($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qShwUnpstdOnly);
                    $cntr = 0;
                    $colClassType1 = "col-md-2";
                    $colClassType2 = "col-md-5";
?>
                    <form id='payTrnsRqstsForm' action='' method='post' accept-charset='UTF-8'>
                        <!--ROW ID-->
                        <input class="form-control" id="tblRowID" type="hidden" placeholder="ROW ID" />
                        <fieldset class="">
                            <legend class="basic_person_lg1" style="color: #003245">LOAN AND PAYMENT REQUESTS TRANSACTIONS</legend>
                            <div class="row" style="margin-bottom:0px;">
                                <?php if ($canAdd === true) { ?>
                                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 15px !important;">
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOnePayTrnsRqstsForm(-1, 1, 'ShowDialog', 'LOAN');">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New Loan
                                        </button>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOnePayTrnsRqstsForm(-1, 1, 'ShowDialog', 'PAYMENT');">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New Payment
                                        </button>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOnePayTrnsRqstsForm(-1, 1, 'ShowDialog', 'SETTLEMENT');">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New Settlement
                                        </button>
                                    </div>
                                <?php
                                } else {
                                    $colClassType1 = "col-md-2";
                                    $colClassType2 = "col-md-4";
                                }
                                ?>
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                    <div class="input-group">
                                        <input class="form-control" id="payTrnsRqstsSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                            echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                            ?>" onkeyup="enterKeyFuncPayTrnsRqsts(event, '', '#allmodules', 'grp=7&typ=1&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                        <input id="payTrnsRqstsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getPayTrnsRqsts('clear', '#allmodules', 'grp=7&typ=1&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getPayTrnsRqsts('', '#allmodules', 'grp=7&typ=1&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="payTrnsRqstsSrchIn">
                                            <?php
                                            $valslctdArry = array("", "", "");
                                            $srchInsArrys = array("All", "Requestor", "Narration");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                if ($srchIn == $srchInsArrys[$z]) {
                                                    $valslctdArry[$z] = "selected";
                                                }
                                            ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="payTrnsRqstsDsplySze" style="min-width:70px !important;">
                                            <?php
                                            $valslctdArry = array("", "", "", "", "", "", "", "");
                                            $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000, 1000000);
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
                                <div class="<?php echo $colClassType1; ?>">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination" style="margin: 0px !important;">
                                            <li>
                                                <a href="javascript:getPayTrnsRqsts('previous', '#allmodules', 'grp=7&typ=1&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:getPayTrnsRqsts('next', '#allmodules', 'grp=7&typ=1&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important">
                                <div class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                                    <div class="col-md-3" style="padding:5px 1px 0px 1px !important;">
                                        <div class="form-check" style="font-size: 12px !important;">
                                            <label class="form-check-label">
                                                <?php
                                                $shwUnpstdOnlyChkd = "";
                                                if ($qShwUnpstdOnly == true) {
                                                    $shwUnpstdOnlyChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <input type="checkbox" class="form-check-input" onclick="getPayTrnsRqsts('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="payTrnsRqstsShwUnpstdOnly" name="payTrnsRqstsShwUnpstdOnly" <?php echo $shwUnpstdOnlyChkd; ?>>
                                                Self Only
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="padding:5px 1px 0px 1px !important;">
                                        &nbsp;
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-responsive" id="payTrnsRqstsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="max-width:30px;width:30px;">No.</th>
                                                <th style="max-width:30px;width:30px;">...</th>
                                                <th style="max-width:180px;width:180px;">Requestor</th>
                                                <th style="max-width:100px;width:100px;">Request Date</th>
                                                <th style="max-width:100px;width:100px;">Request Type (Item Type)</th>
                                                <th style="max-width:100px;width:100px;">Classification</th>
                                                <th>Request Reason </th>
                                                <th style="text-align:center;max-width:35px;width:35px;padding:8px 4px !important;">CUR.</th>
                                                <th style="text-align:right;max-width:90px;width:90px;">Principal Amount</th>
                                                <th style="max-width:145px;width:145px;">Request Status</th>
                                                <th style="max-width:165px;width:165px;">Payment Status</th>
                                                <?php if ($canDel === true) { ?>
                                                    <th style="max-width:30px;width:30px;">...</th>
                                                <?php } ?>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <th style="max-width:30px;width:30px;">...</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($row = loc_db_fetch_array($result)) {
                                                $cntr += 1;
                                            ?>
                                                <tr id="payTrnsRqstsHdrsRow_<?php echo $cntr; ?>">
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Request" onclick="getOnePayTrnsRqstsForm(<?php echo $row[0]; ?>, 1, 'ShowDialog', '<?php echo $row[3]; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                            <?php
                                                            if ($canEdt === true) {
                                                            ?>
                                                                <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } else { ?>
                                                                <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } ?>
                                                        </button>
                                                    </td>
                                                    <td class="lovtd"><?php echo $row[2]; ?></td>
                                                    <td class="lovtd"><?php echo $row[8]; ?></td>
                                                    <td class="lovtd" style="word-wrap: break-word;"><?php echo $row[3] . " (" . $row[5] . ")"; ?></td>
                                                    <td class="lovtd"><?php echo $row[6]; ?></td>
                                                    <td class="lovtd" style="word-wrap: break-word;"><?php echo $row[7]; ?></td>
                                                    <td class="lovtd" style="text-align:center;font-weight: bold;color:black;"><?php echo $fnccurnm; ?></td>
                                                    <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                                                                                                echo number_format((float) $row[9], 2);
                                                                                                                                ?>
                                                    </td>
                                                    <?php
                                                    $style1 = "color:red;";
                                                    if ($row[13] == "Approved") {
                                                        $style1 = "color:green;";
                                                    } else if ($row[13] == "Cancelled") {
                                                        $style1 = "color:#0d0d0d;";
                                                    }
                                                    $style2 = "color:red;";
                                                    if ($row[15] == "1") {
                                                        $style2 = "color:blue;";
                                                    }
                                                    $style3 = "color:red;";
                                                    if ($row[17] == "PAID") {
                                                        $style3 = "color:green;";
                                                    }
                                                    ?>
                                                    <td class="lovtd" style="font-weight:bold;<?php echo $style1; ?>"><?php
                                                                                                                        echo $row[13] . " - <span style=\"" . $style2 . "\">" . ($row[15] == "1" ? "Processed" : "Not Processed") . "</span>";
                                                                                                                        ?>
                                                    </td>
                                                    <td class="lovtd" style="font-weight:bold;color:blue;"><?php
                                                                                                            echo $row[16] . " - <span style=\"" . $style3 . "\">" . ($row[17]) . "</span>";
                                                                                                            ?>
                                                    </td>
                                                    <?php if ($canDel === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction" onclick="delPayTrnsRqsts('payTrnsRqstsHdrsRow_<?php echo $cntr; ?>')" style="padding:2px !important;" style="padding:2px !important;">
                                                                <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                            <input type="hidden" id="payTrnsRqstsHdrsRow<?php echo $cntr; ?>_HdrID" name="payTrnsRqstsHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                        </td>
                                                    <?php } ?>
                                                    <?php
                                                    if ($canVwRcHstry === true) {
                                                    ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                    echo urlencode(encrypt1(($row[0] . "|pay.pay_loan_pymnt_rqsts|pay_request_id"), $smplTokenWord1));
                                                                                                                                                                                                                    ?>');" style="padding:2px !important;">
                                                                <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </td>
                                                    <?php } ?>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                <?php
                }
            } else if ($vwtyp == 1) {
                //New Loan and Payment Requests Form
                $sbmtdPayTrnsRqstsID = isset($_POST['sbmtdPayTrnsRqstsID']) ? cleanInputData($_POST['sbmtdPayTrnsRqstsID']) : -1;
                $lnkdPayTrnsRqstsID = -1;
                $payTrnsRqstsType = isset($_POST['payTrnsRqstsType']) ? cleanInputData($_POST['payTrnsRqstsType']) : "LOAN";
                if ((!$canAdd) || ($sbmtdPayTrnsRqstsID > 0 && !$canEdt)) {
                    restricted();
                    exit();
                }
                $vPsblValID1 = getEnbldPssblValID("Application Instance SHORT CODE", getLovID("All Other General Setups"));
                $vPsblVal1 = getPssblValDesc($vPsblValID1);
                $payTrnsRqstsPrsnID = -1;
                $payTrnsRqstsPrsnNm = "";
                $payTrnsRqstsItmTypID = -1;
                $payTrnsRqstsItmTypNm = "";
                $payTrnsRqstsClsfctn = "";
                $payTrnsRqstsDesc = "";
                $payTrnsRqstsDate = $gnrlTrnsDteDMYHMS;
                $rqStatus = "Not Submitted"; //approval_status
                $rqStatusNext = "Approve"; //next_aproval_action
                $rqstatusColor = "red";
                $payTrnsRqstsInvcCur = $fnccurnm;
                $payTrnsRqstsInvcCurID = $fnccurid;
                $payTrnsRqstsAmnt = 0;
                $payTrnsRqstsPrdcDdctAmt = 0;
                $payTrnsRqstsNetAmt = 0;
                $payTrnsRqstsMaxAmt = 0;
                $payTrnsRqstsMinAmnt = 0;
                $payTrnsRqstsEnfrcMx = "0";
                $payTrnsRqstsIntrstRt = 0;
                $payTrnsRqstsIntrstTyp = "% Per Annum"; //Flat
                $payTrnsRqstsRepayPrd = 0;
                $payTrnsRqstsRepayPrdTyp = "Installments";
                $payTrnsRqstsHsAgreed = "0";
                $payTrnsRqstsIsPrcsd = "0";
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                if ($sbmtdPayTrnsRqstsID > 0) {
                    $result = get_One_TrnsRqstsDocHdr($sbmtdPayTrnsRqstsID);
                    if ($row = loc_db_fetch_array($result)) {
                        $payTrnsRqstsPrsnID = (float) $row[1];
                        $payTrnsRqstsPrsnNm = $row[2];
                        $payTrnsRqstsType = $row[3];
                        $payTrnsRqstsItmTypID = (int) $row[4];
                        $payTrnsRqstsItmTypNm = $row[5];
                        $payTrnsRqstsClsfctn = $row[6];
                        $payTrnsRqstsDesc = $row[7];
                        $payTrnsRqstsDate = $row[8];

                        $payTrnsRqstsAmnt = (float) $row[9];
                        $payTrnsRqstsPrdcDdctAmt = (float) $row[10];
                        $payTrnsRqstsIntrstRt = (float) $row[11];
                        $payTrnsRqstsIntrstTyp = $row[16];
                        $payTrnsRqstsRepayPrd = (float) $row[12];
                        $payTrnsRqstsRepayPrdTyp = $row[17];
                        $payTrnsRqstsHsAgreed = $row[14];
                        $payTrnsRqstsIsPrcsd = $row[15];

                        $payTrnsRqstsNetAmt = (float) $row[18];
                        $payTrnsRqstsMaxAmt = (float) $row[19];
                        $payTrnsRqstsEnfrcMx = $row[20];
                        $lnkdPayTrnsRqstsID = (float) $row[21];
                        $payTrnsRqstsMinAmnt = (float) $row[22];

                        $rqStatus = $row[13];
                        $rqStatusNext = "";
                        $rqstatusColor = "red";

                        if ($rqStatus == "Approved") {
                            $rqstatusColor = "green";
                        } else {
                            $rqstatusColor = "red";
                        }
                        if ($rqStatus == "Not Submitted" || $rqStatus == "Rejected" || $rqStatus == "Withdrawn") {
                            $mkReadOnly = "";
                            $mkRmrkReadOnly = "";
                        } else {
                            $canEdt = FALSE;
                            $mkReadOnly = "readonly=\"true\"";
                            if ($rqStatus != "Approved") {
                                $mkRmrkReadOnly = "readonly=\"true\"";
                            }
                        }
                    }
                }
                $rqstStatus = $rqStatus;
                $routingID = getMxRoutingID($sbmtdPayTrnsRqstsID, "Internal Pay " . ucfirst(strtolower($payTrnsRqstsType)) . " Requests");
                $reportTitle = "Loan Application Letter";
                if ($payTrnsRqstsType == "SETTLEMENT") {
                    $reportTitle = "Loan Settlement Request";
                } else if ($payTrnsRqstsType == "PAYMENT") {
                    $reportTitle = "Dues Contribution Payment Request";
                }
                $reportName = "Welfare Loan Application Letter";
                $rptID = getRptID($reportName);
                $prmID1 = getParamIDUseSQLRep("{:pay_rqst_id}", $rptID);
                $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                $trnsID = $sbmtdPayTrnsRqstsID;
                $paramRepsNVals = $prmID1 . "~" . $trnsID . "|" . $prmID2 . "~" . $reportTitle . "|-130~" . $reportTitle . "|-190~PDF";
                $paramStr = urlencode($paramRepsNVals);
                ?>
                <form class="form-horizontal" id="onePayTrnsRqstsEDTForm">
                    <div class="row" style="margin-top:5px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Request ID/Date:</label>
                                </div>
                                <div class="col-md-2" style="padding:0px 1px 0px 15px;">
                                    <input type="hidden" value="<?php echo $vPsblVal1; ?>" id="sbmtdPayTrnsAppCODE" name="sbmtdPayTrnsAppCODE">
                                    <input type="hidden" value="<?php echo $payTrnsRqstsEnfrcMx; ?>" id="payTrnsRqstsEnfrcMx" name="payTrnsRqstsEnfrcMx">
                                    <input type="text" class="form-control" aria-label="..." id="sbmtdPayTrnsRqstsID" name="sbmtdPayTrnsRqstsID" value="<?php echo $sbmtdPayTrnsRqstsID; ?>" readonly="true">
                                </div>
                                <div class="col-md-2" style="padding:0px 1px 0px 1px;">
                                    <input type="text" class="form-control" aria-label="..." id="payTrnsRqstsType" name="payTrnsRqstsType" value="<?php echo $payTrnsRqstsType; ?>" readonly="true">
                                </div>
                                <div class="col-md-4" style="padding:0px 15px 0px 1px;">
                                    <input type="text" class="form-control" aria-label="..." id="payTrnsRqstsDate" name="payTrnsRqstsDate" value="<?php echo $payTrnsRqstsDate; ?>" readonly="true">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;"><?php echo ucfirst(strtolower($payTrnsRqstsType)); ?> Type / Classification:</label>
                                </div>
                                <div class="col-md-4" style="padding:0px 1px 0px 15px;">
                                    <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="payTrnsRqstsItmTypID" name="payTrnsRqstsItmTypID" style="width:100% !important;" onchange="shwHidePayTrnsFlds();">
                                        <?php
                                        $lqlovNm = "Internal Pay Loan Types";
                                        if ($payTrnsRqstsType == "PAYMENT") {
                                            $lqlovNm = "Internal Pay Payment Types";
                                        } else if ($payTrnsRqstsType == "SETTLEMENT") {
                                            $lqlovNm = "Internal Pay Settlement Types";
                                        }
                                        $cnt = 0;
                                        $brghtStr = "";
                                        $isDynmyc = true;
                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID($lqlovNm), $isDynmyc, $orgID, "", "");
                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                            $selectedTxt = "";
                                            if ($cnt == 0 && $payTrnsRqstsItmTypID <= 0) {
                                                $payTrnsRqstsItmTypID = ((int) $titleRow[0]);
                                            }
                                            $cnt++;
                                            if (((int) $titleRow[0]) == $payTrnsRqstsItmTypID) {
                                                $selectedTxt = "selected";
                                            }
                                        ?>
                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[1]; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-4" style="padding:0px 15px 0px 1px;">
                                    <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="payTrnsRqstsClsfctn" name="payTrnsRqstsClsfctn" style="width:100% !important;" onchange="shwHideTrnsTypsDivs();">
                                        <?php
                                        $lqlovNm = "Internal Pay Loan Classifications";
                                        if ($payTrnsRqstsType == "PAYMENT") {
                                            $lqlovNm = "Internal Pay Payment Classifications";
                                        } else if ($payTrnsRqstsType == "SETTLEMENT") {
                                            $lqlovNm = "Internal Pay Settlement Classifications";
                                        }
                                        //Semi-Month
                                        $brghtStr = "";
                                        $isDynmyc = true;
                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID($lqlovNm), $isDynmyc, $payTrnsRqstsItmTypID, "", "");
                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                            /* if (!($titleRow[1] == "All" || trim($titleRow[1]) == "" || $titleRow[1] == $vPsblVal1)) {
                                              continue;
                                              } */
                                            $selectedTxt = "";
                                            if ($titleRow[0] == $payTrnsRqstsClsfctn) {
                                                $selectedTxt = "selected";
                                            }
                                        ?>
                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label for="payTrnsRqstsPrsnNm" class="control-label">Person:</label>
                                </div>
                                <div class="col-md-8">
                                    <?php if ($canEdt === true || $canAdd === true) { ?>
                                        <div class="input-group" style="width:100% !important;">
                                            <input type="text" name="payTrnsRqstsPrsnNm" id="payTrnsRqstsPrsnNm" class="form-control" value="<?php echo $payTrnsRqstsPrsnNm; ?>" readonly="true" style="width:100% !important;">
                                            <input type="hidden" name="payTrnsRqstsPrsnID" id="payTrnsRqstsPrsnID" class="form-control" value="<?php echo $payTrnsRqstsPrsnID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payTrnsRqstsPrsnID', 'payTrnsRqstsPrsnNm', 'clear', 0, '', function () {shwHidePayPrevLoans();});">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    <?php } else { ?>
                                        <input type="hidden" name="payTrnsRqstsPrsnID" id="payTrnsRqstsPrsnID" class="form-control" value="<?php echo $payTrnsRqstsPrsnID; ?>">
                                        <span><?php echo $payTrnsRqstsPrsnNm; ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php if ($payTrnsRqstsType == "SETTLEMENT") { ?>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Linked Loan Request:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="lnkdPayTrnsRqstsID" name="lnkdPayTrnsRqstsID" style="width:100% !important;" onchange="shwHidePayPrevLoans();">
                                            <?php
                                            $payTrnsRqstsDpndtItmTypID = (float) getGnrlRecNm("pay.loan_pymnt_invstmnt_typs", "item_type_id", "lnkd_loan_type_id", $payTrnsRqstsItmTypID);
                                            $payTrnsRqstsDpndtBalsItmID = (float) getGnrlRecNm("pay.loan_pymnt_invstmnt_typs", "item_type_id", "lnkd_loan_mn_itm_id", $payTrnsRqstsItmTypID);
                                            $cnt = 0;
                                            $titleRslt = get_UnsttldLoanRqsts("%", "Requestor", 0, 500, $orgID, $payTrnsRqstsPrsnID, $payTrnsRqstsDpndtItmTypID, $payTrnsRqstsDpndtBalsItmID, "LOAN");
                                            while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                $selectedTxt = "";
                                                $cnt++;
                                                if (((float) $titleRow[0]) == $lnkdPayTrnsRqstsID) {
                                                    $selectedTxt = "selected";
                                                }
                                            ?>
                                                <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[5]; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Remark / Narration:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group" style="width:100%;">
                                        <textarea class="form-control rqrdFld" rows="2" cols="20" id="payTrnsRqstsDesc" name="payTrnsRqstsDesc" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $payTrnsRqstsDesc; ?></textarea>
                                        <input class="form-control" type="hidden" id="payTrnsRqstsDesc1" value="<?php echo $payTrnsRqstsDesc; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('payTrnsRqstsDesc');" style="max-width:30px;width:30px;">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Amount:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <label class="btn btn-info btn-file input-group-addon active" onclick="">
                                            <span class="" style="font-size: 20px !important;" id="payTrnsRqstsInvcCur1"><?php echo $payTrnsRqstsInvcCur; ?></span>
                                        </label>
                                        <input type="hidden" id="payTrnsRqstsInvcCur" value="<?php echo $payTrnsRqstsInvcCur; ?>">
                                        <input class="form-control rqrdFld" type="text" id="payTrnsRqstsAmnt" value="<?php
                                                                                                                        echo number_format($payTrnsRqstsAmnt, 2);
                                                                                                                        ?>" style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('payTrnsRqstsAmnt');" <?php echo $mkReadOnly; ?> />
                                    </div>
                                </div>
                            </div>
                            <?php
                            if (($payTrnsRqstsType == "LOAN" || $payTrnsRqstsType == "SETTLEMENT") && $sbmtdPayTrnsRqstsID > 0) {
                                $antcptdIntrst = (($payTrnsRqstsPrdcDdctAmt * $payTrnsRqstsRepayPrd) - $payTrnsRqstsAmnt);
                                if ($payTrnsRqstsType == "SETTLEMENT") {
                                    $antcptdIntrst = $payTrnsRqstsNetAmt - $payTrnsRqstsAmnt;
                                }
                            ?>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Anticipated Total Interest:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                                <span class="" style="font-size: 20px !important;" id="payTrnsRqstsInvcCur5"><?php echo $payTrnsRqstsInvcCur; ?></span>
                                            </label>
                                            <input type="hidden" id="payTrnsRqstsInvcCur4" value="<?php echo $payTrnsRqstsInvcCur; ?>">
                                            <input class="form-control" type="text" id="payTrnsRqstsIntrstAmt" value="<?php echo number_format($antcptdIntrst, 2); ?>" style="font-weight:bold;width:100%;font-size:18px !important;" readonly="true" />
                                        </div>
                                    </div>
                                </div>
                                <?php if ($payTrnsRqstsType != "SETTLEMENT") { ?>
                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <label style="margin-bottom:0px !important;">Installment Deductions:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                                    <span class="" style="font-size: 20px !important;" id="payTrnsRqstsInvcCur3"><?php echo $payTrnsRqstsInvcCur; ?></span>
                                                </label>
                                                <input type="hidden" id="payTrnsRqstsInvcCur2" value="<?php echo $payTrnsRqstsInvcCur; ?>">
                                                <input class="form-control" type="text" id="payTrnsRqstsPrdcDdctAmt" value="<?php echo number_format($payTrnsRqstsPrdcDdctAmt, 2);
                                                                                                                            ?>" style="font-weight:bold;width:100%;font-size:18px !important;" readonly="true" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <label style="margin-bottom:0px !important;">Interest Rate:</label>
                                        </div>
                                        <div class="col-md-4" style="padding-right:1px !important;">
                                            <input class="form-control" type="text" id="payTrnsRqstsIntrstRt" value="<?php
                                                                                                                        echo number_format($payTrnsRqstsIntrstRt, 2);
                                                                                                                        ?>" style="font-weight:bold;width:100%;font-size:18px !important;text-align: right;" readonly="true" />
                                        </div>
                                        <div class="col-md-4" style="padding-left:1px !important;">
                                            <input class="form-control" type="text" id="payTrnsRqstsIntrstTyp" value="<?php echo $payTrnsRqstsIntrstTyp; ?>" style="font-weight:bold;width:100%;font-size:18px !important;" readonly="true" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <label style="margin-bottom:0px !important;">Repayment Period:</label>
                                        </div>
                                        <div class="col-md-4" style="padding-right:1px !important;">
                                            <input class="form-control" type="text" id="payTrnsRqstsRepayPrd" value="<?php
                                                                                                                        echo number_format($payTrnsRqstsRepayPrd, 2);
                                                                                                                        ?>" style="font-weight:bold;width:100%;font-size:18px !important;text-align: right;" readonly="true" />
                                        </div>
                                        <div class="col-md-4" style="padding-left:1px !important;">
                                            <input class="form-control" type="text" id="payTrnsRqstsRepayPrdTyp" value="<?php echo $payTrnsRqstsRepayPrdTyp; ?>" style="font-weight:bold;width:100%;font-size:18px !important;" readonly="true" />
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Net Amount to be Paid:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                                <span class="" style="font-size: 20px !important;" id="payTrnsRqstsInvcCur8"><?php echo $payTrnsRqstsInvcCur; ?></span>
                                            </label>
                                            <input type="hidden" id="payTrnsRqstsInvcCur7" value="<?php echo $payTrnsRqstsInvcCur; ?>">
                                            <input class="form-control" type="text" id="payTrnsRqstsNetAmt" value="<?php echo number_format($payTrnsRqstsNetAmt, 2);
                                                                                                                    ?>" style="font-weight:bold;width:100%;font-size:18px !important;" readonly="true" />
                                        </div>
                                    </div>
                                </div>
                                <?php if ($payTrnsRqstsEnfrcMx == "1" && $payTrnsRqstsMaxAmt != 0) { ?>
                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <label style="margin-bottom:0px !important;">Max Amount Allowed:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                                    <span class="" style="font-size: 20px !important;" id="payTrnsRqstsInvcCur10"><?php echo $payTrnsRqstsInvcCur; ?></span>
                                                </label>
                                                <input type="hidden" id="payTrnsRqstsInvcCur9" value="<?php echo $payTrnsRqstsInvcCur; ?>">
                                                <input class="form-control" type="text" id="payTrnsRqstsMaxAmt" value="<?php echo number_format($payTrnsRqstsMaxAmt, 2);
                                                                                                                        ?>" style="font-weight:bold;width:100%;font-size:18px !important;" readonly="true" />
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if ($payTrnsRqstsMinAmnt != 0) { ?>
                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <label style="margin-bottom:0px !important;">Min Amount Allowed:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                                    <span class="" style="font-size: 20px !important;" id="payTrnsRqstsInvcCur15"><?php echo $payTrnsRqstsInvcCur; ?></span>
                                                </label>
                                                <input type="hidden" id="payTrnsRqstsInvcCur14" value="<?php echo $payTrnsRqstsInvcCur; ?>">
                                                <input class="form-control" type="text" id="payTrnsRqstsMinAmnt" value="<?php echo number_format($payTrnsRqstsMinAmnt, 2);
                                                                                                                        ?>" style="font-weight:bold;width:100%;font-size:18px !important;" readonly="true" />
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                            <?php if ($sbmtdPayTrnsRqstsID > 0) { ?>
                                <div class="form-group" style="padding:6px 0px 0px 0px !important;">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">&nbsp;</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-check" style="font-size: 16px !important;color:blue;font-weight: bold;font-style: italic;font-family: georgia;">
                                            <label class="form-check-label">
                                                <?php
                                                $payTrnsRqstsHsAgreedChkd = "";
                                                if ($payTrnsRqstsHsAgreed == "1") {
                                                    $payTrnsRqstsHsAgreedChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <input type="checkbox" class="form-check-input" onclick="" id="payTrnsRqstsHsAgreed" name="payTrnsRqstsHsAgreed" <?php echo $payTrnsRqstsHsAgreedChkd; ?>>
                                                I agree to all the Terms and Conditions associated with this Request!
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Status:</label>
                                </div>
                                <div class="col-md-8">
                                    <?php
                                    $style2 = "color:red;";
                                    if ($payTrnsRqstsIsPrcsd == "1") {
                                        $style2 = "color:blue;";
                                    }
                                    ?>
                                    <button type="button" class="btn btn-default" style="height:30px;width:100% !important;" id="myPayTrnsRqstsStatusBtn">
                                        <span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;height:30px;"><?php
                                                                                                                            echo $rqStatus . "<span style=\"" . $style2 . "\">" . ($payTrnsRqstsIsPrcsd == "1" ? " [Processed]" : " [Not Processed]") . "</span>";
                                                                                                                            ?>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding:1px 15px 1px 15px !important;">
                        <hr style="margin:2px 0px 2px 0px;">
                    </div>
                    <div class="row">
                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                            <div class="col-md-5" style="float:left;">
                                <?php if ($sbmtdPayTrnsRqstsID > 0) { ?>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOnePayTrnsRqstsDocsForm(<?php echo $sbmtdPayTrnsRqstsID; ?>, 20);" data-toggle="tooltip" data-placement="bottom" title="Attached Documents">
                                        <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="width:100% !important;">
                                        <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Print
                                    </button>
                                <?php } ?>
                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOnePayTrnsRqstsForm(<?php echo $sbmtdPayTrnsRqstsID; ?>, 1, 'ReloadDialog', '<?php echo $payTrnsRqstsType; ?>');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                            </div>
                            <div class="col-md-7">
                                <div class="" style="float:right !important;">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <?php if ($canEdt === true && ($rqstStatus == "Not Submitted" || $rqstStatus == "Withdrawn" || $rqstStatus == "Rejected")) { ?>
                                        <button type="button" class="btn btn-primary" onclick="savePayTrnsRqstsForm('<?php echo $fnccurnm; ?>', 0);">Save Changes</button>
                                        <?php if ($sbmtdPayTrnsRqstsID > 0) { ?>
                                            <button type="button" class="btn btn-primary" onclick="savePayTrnsRqstsForm('<?php echo $fnccurnm; ?>', 2);">Submit</button>
                                    <?php
                                        }
                                    }
                                    ?>
                                    <?php
                                    if (!($rqstStatus == "Not Submitted" || $rqstStatus == "Withdrawn" || $rqstStatus == "Rejected") && ($rqstStatus != "Approved")) {
                                    ?>
                                        <button type="button" class="btn btn-default btn-sm" style="" onclick="actOnLoanRqst('Withdraw');"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Withdraw&nbsp;</button>
                                        <?php if (($canApprove === true)) { ?>
                                            <button type="button" class="btn btn-default btn-sm" style="" onclick="actOnLoanRqst('Approve');"><img src="cmn_images/Stamp-512.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Approve&nbsp;</button>
                                        <?php } ?>
                                        <button type="button" class="btn btn-default btn-sm" style="" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, '<?php echo ucfirst(strtolower($payTrnsRqstsType)); ?> Approval Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Progress&nbsp;</button>
                                    <?php
                                    } else if ($rqstStatus == "Approved") {
                                    ?>
                                        <button type="button" class="btn btn-default btn-sm" style="" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, '<?php echo ucfirst(strtolower($payTrnsRqstsType)); ?> Approval Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;" data-toggle="tooltip" title="Approval Progress History">Progress&nbsp;</button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php
            } else if ($vwtyp == 20) {
                /* All Attached Documents */
                $sbmtdPayTrnsRqstsID = isset($_POST['sbmtdPayTrnsRqstsID']) ? cleanInputData($_POST['sbmtdPayTrnsRqstsID']) : -1;
                $payTransType = "LOAN_N_PAY";
                if (!$canAdd || ($sbmtdPayTrnsRqstsID > 0 && !$canEdt)) {
                    restricted();
                    exit();
                }
                $pkID = $sbmtdPayTrnsRqstsID;
                $total = get_Total_InvstTrans_Attachments($srchFor, $pkID, $payTransType);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $attchSQL = "";
                $result2 = get_InvstTrans_Attachments($srchFor, $curIdx, $lmtSze, $pkID, $payTransType, $attchSQL);
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-4";
            ?>
                <fieldset class="" style="padding:10px 0px 5px 0px !important;">
                    <form class="" id="attchdTrnsRqstsDocsTblForm">
                        <div class="row">
                            <?php
                            $nwRowHtml = urlencode("<tr id=\"attchdTrnsRqstsDocsRow__WWW123WWW\">"
                                . "<td class=\"lovtd\"><span>New</span></td>"
                                . "<td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                              <div class=\"input-group\" style=\"width:100% !important;\">
                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"attchdTrnsRqstsDocsRow_WWW123WWW_DocCtgryNm\" value=\"\">
                                                <input class=\"form-control\" aria-label=\"...\" id=\"attchdTrnsRqstsDocsRow_WWW123WWW_DocFile\" type=\"file\" style=\"visibility:hidden;height:5px !important;display:none;\" />     
                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Attachment Document Categories', '', '', '', 'radio', true, '', 'attchdTrnsRqstsDocsRow_WWW123WWW_DocCtgryNm', 'attchdTrnsRqstsDocsRow_WWW123WWW_DocCtgryNm', 'clear', 0, '');\">
                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                </label>
                                              </div>
                                              </div>
                                              <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attchdTrnsRqstsDocsRow_WWW123WWW_AttchdDocsID\" value=\"-1\" style=\"\">                                               
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"uploadFileToTrnsRqstsDocs('attchdTrnsRqstsDocsRow_WWW123WWW_DocFile','attchdTrnsRqstsDocsRow_WWW123WWW_AttchdDocsID','attchdTrnsRqstsDocsRow_WWW123WWW_DocCtgryNm'," . $pkID . ",'attchdTrnsRqstsDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Download Document\">
                                                    <img src=\"cmn_images/openfileicon.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\"> Upload
                                                </button>
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAttchdTrnsRqstsDoc('attchdTrnsRqstsDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Document\">
                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                </button>
                                          </td>
                                        </tr>");
                            ?>
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 1px !important;">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('attchdTrnsRqstsDocsTable', 0, '<?php echo $nwRowHtml; ?>');" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Document
                                    </button>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="attchdTrnsRqstsDocsSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                            echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                            ?>" onkeyup="enterKeyFuncAttchdTrnsRqstsDocs(event, '', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPayTrnsRqstsID=<?php echo $sbmtdPayTrnsRqstsID; ?>', 'ReloadDialog');">
                                    <input id="attchdTrnsRqstsDocsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdTrnsRqstsDocs('clear', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPayTrnsRqstsID=<?php echo $sbmtdPayTrnsRqstsID; ?>', 'ReloadDialog');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdTrnsRqstsDocs('', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPayTrnsRqstsID=<?php echo $sbmtdPayTrnsRqstsID; ?>', 'ReloadDialog');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="attchdTrnsRqstsDocsDsplySze" style="min-width:70px !important;">
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
                            <div class="<?php echo $colClassType1; ?>">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a class="rhopagination" href="javascript:getAttchdTrnsRqstsDocs('previous', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPayTrnsRqstsID=<?php echo $sbmtdPayTrnsRqstsID; ?>','ReloadDialog');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAttchdTrnsRqstsDocs('next', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPayTrnsRqstsID=<?php echo $sbmtdPayTrnsRqstsID; ?>','ReloadDialog');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="attchdTrnsRqstsDocsTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Doc. Name/Description</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cntr = 0;
                                        while ($row2 = loc_db_fetch_array($result2)) {
                                            $cntr += 1;
                                            $doc_src = $ftp_base_db_fldr . "/PayDocs/" . $row2[3];
                                            $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                                            if (file_exists($doc_src)) {
                                                //file exists!
                                            } else {
                                                //file does not exist.
                                                $doc_src_encrpt = "None";
                                            }
                                        ?>
                                            <tr id="attchdTrnsRqstsDocsRow_<?php echo $cntr; ?>">
                                                <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                <td class="lovtd">
                                                    <span><?php echo $row2[2]; ?></span>
                                                    <input type="hidden" class="form-control" aria-label="..." id="attchdTrnsRqstsDocsRow<?php echo $cntr; ?>_AttchdDocsID" value="<?php echo $row2[0]; ?>" style="width:100% !important;">
                                                </td>
                                                <td class="lovtd">
                                                    <?php
                                                    if ($doc_src_encrpt == "None") {
                                                    ?>
                                                        <span style="font-weight: bold;color:#FF0000;">
                                                            <?php
                                                            echo "File Not Found!";
                                                            ?>
                                                        </span>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="doAjax('grp=1&typ=11&q=Download&fnm=<?php echo $doc_src_encrpt; ?>', '', 'Redirect', '', '', '');" data-toggle="tooltip" data-placement="bottom" title="Download Document">
                                                            <img src="cmn_images/dwldicon.png" style="height:15px; width:auto; position: relative; vertical-align: middle;"> Download
                                                        </button>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttchdTrnsRqstsDoc('attchdTrnsRqstsDocsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </fieldset>
    <?php
            }
        }
    }
}

function getLoanRqstAttchMnts($src_pkey_id, $src_trans_type)
{
    global $ftp_base_db_fldr;
    $sqlStr = "SELECT string_agg(REPLACE(a.attchmnt_desc,';',','),';') attchmnt_desc, 
string_agg(REPLACE('" . $ftp_base_db_fldr . "/PayDocs/' || a.file_name,';',','),';') file_name 
  FROM pay.pay_trans_attchmnts a 
  WHERE src_pkey_id=" . $src_pkey_id . " and src_trans_type='" . loc_db_escape_string($src_trans_type) . "'";
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function updateLoanRqst($srcDocID, $nwvalue)
{
    global $usrID;
    $affctd = 0;
    $datestr = getDB_Date_time();
    if ($nwvalue != "") {
        $updSQL1 = "UPDATE pay.pay_loan_pymnt_rqsts
            SET REQUEST_STATUS='" . loc_db_escape_string($nwvalue) . "',
                last_update_by = " . $usrID .
            ", last_update_date = '" . loc_db_escape_string($datestr) .
            "' WHERE pay_request_id =" . $srcDocID . " and REQUEST_STATUS NOT IN ('Approved')";
        $affctd = execUpdtInsSQL($updSQL1);
    }
    return $affctd;
}

function loanPayReqMsgActns($routingID = -1, $inptSlctdRtngs = "", $actionToPrfrm = "Initiate", $srcDocID = -1, $srcDocType = "")
{
    global $app_url;
    global $admin_name;
    global $isWflMailAllwd;
    global $isWflMailAllwdID;
    $userID = $_SESSION['USRID'];
    $user_Name = $_SESSION['UNAME'];
    try {
        $rtngMsgID = -1;
        $affctd = 0;
        $affctd1 = 0;
        $affctd2 = 0;
        $affctd3 = 0;
        $affctd4 = 0;
        $curPrsnsLevel = -123456789;
        $msgTitle = "";
        $msgBdy = "";
        $nwPrsnLocID = isset($_POST['toPrsLocID']) ? cleanInputData($_POST['toPrsLocID']) : "";
        $apprvrCmmnts = isset($_POST['actReason']) ? cleanInputData($_POST['actReason']) : "";
        $fromPrsnID = getUserPrsnID($user_Name);
        $usrFullNm = getPrsnFullNm($fromPrsnID);
        $msg = "";
        $dsply = "";
        $msg_id = -1;
        $appID = -1;
        $attchmnts = "";
        $reqestDte = getFrmtdDB_Date_time();

        $srcdoctyp = $srcDocType;
        $srcdocid = $srcDocID;
        $msgPartDynmc = strpos($srcDocType, 'Loan') !== FALSE ? "LOAN APPLICATION" : "PAYMENT REQUEST";
        if (strpos($srcDocType, 'Settle') !== FALSE) {
            $msgPartDynmc = "SETTLEMENT REQUEST";
        }
        $msgPartDynmcStnc = ucfirst(strtolower($msgPartDynmc));
        $attchDocType = "LOAN_N_PAY";
        $reportTitle = "Send Outstanding Bulk Messages";
        $reportName = "Send Outstanding Bulk Messages";
        $rptID = getRptID($reportName);
        $prmID = getParamIDUseSQLRep("{:msg_batch_id}", $rptID);
        $msgBatchID = -1;
        //session_write_close();
        if ($routingID <= 0 && $inptSlctdRtngs == "") {
            if ($actionToPrfrm == "Initiate" && $srcDocID > 0) {
                $msg_id = getWkfMsgID();
                $appID = getAppID($srcDocType, 'Internal Payments');
                //Requestor
                $prsnid = $fromPrsnID;
                $fullNm = $usrFullNm;
                $prsnLocID = getPersonLocID($prsnid);
                //Message Header & Details
                $msghdr = $msgPartDynmcStnc . " from " . $fullNm . " (" . $prsnLocID . ")";
                $msgbody = $msgPartDynmc . " ON ($reqestDte):- "
                    . "A " . $msgPartDynmcStnc . " has been submitted by $fullNm ($prsnLocID) "
                    . "<br/>Please open the attached Work Document and attend to this Request.
                      <br/>Thank you.";
                $msgtyp = "Work Document";
                $msgsts = "0";
                $hrchyid = (float) getGnrlRecID2("wkf.wkf_hierarchy_hdr", "hierarchy_name", "hierarchy_id", $srcDocType . " Hierarchy"); //Get hierarchy ID

                $attchmnts = ""; //Get Attachments
                $attchmnts_desc = ""; //Get Attachments
                $rslt = getLoanRqstAttchMnts($srcdocid, $attchDocType);
                while ($rw = loc_db_fetch_array($rslt)) {
                    $attchmnts = $rw[1];
                    $attchmnts_desc = $rw[0];
                }
                createWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
                //Get Hierarchy Members
                $result = getNextApprvrsInMnlHrchy($hrchyid, $curPrsnsLevel);
                $prsnsFnd = 0;
                $lastPrsnID = "|";
                $msgBatchID = getMsgBatchID();
                $paramRepsNVals = $prmID . "~" . $msgBatchID . "|-190~HTML";
                while ($row = loc_db_fetch_array($result)) {
                    $toPrsnID = (float) $row[0];
                    $prsnsFnd++;
                    if ($toPrsnID > 0) {
                        //transform:translateY(-50%);
                        routWkfMsg($msg_id, $prsnid, $toPrsnID, $userID, 'Initiated', 'Open;Reject;Request for Information;Approve');
                        $dsply = '<div style="text-align:center;font-weight:bold;font-size:18px;color:blue;position:relative;top:50%;">Your request has been submitted successfully for Approval.</br>
                        A notification will be sent to you on approval of your request. Thank you!</div>';
                        $msg = $dsply;
                        //Begin Email Sending Process                    
                        $result1 = getEmlDetailsB4Actn($srcdoctyp, $srcdocid);
                        while ($row1 = loc_db_fetch_array($result1)) {
                            $frmID = $toPrsnID;
                            if (strpos($lastPrsnID, "|" . $frmID . "|") !== FALSE) {
                                $lastPrsnID .= $frmID . "|";
                                continue;
                            }
                            $lastPrsnID .= $frmID . "|";
                            $subject = $row1[1];
                            $actSoFar = $row1[3];
                            if ($actSoFar == "") {
                                $actSoFar = "&nbsp;&nbsp;NONE";
                            }
                            $msgPart = "<span style=\"font-weight:bold;text-decoration:underline;color:blue;\">ACTIONS TAKEN SO FAR:</span><br/>" . $actSoFar . "<br/> <span style=\"font-weight:bold;text-decoration:underline;color:blue;\">ORIGINAL MESSAGE:</span><br/>&nbsp;&nbsp;" . $row1[2];
                            $docType = $srcDocType;
                            $to = getPrsnEmail($frmID);
                            $nameto = getPrsnFullNm($frmID);
                            if ($docType != "" && $docType != "Login") {
                                $message = "Dear $nameto, <br/><br/>A notification has been sent to your account in the Portal as follows:"
                                    . "<br/><br/>"
                                    . $msgPart .
                                    "<br/><br/>Kindly <a href=\""
                                    . $app_url . "\">Login via this Link</a> to <strong>VIEW and ACT</strong> on it!<br/>Thank you for your cooperation!<br/><br/>Best Regards,<br/>" . $admin_name;
                                $errMsg = "";
                                createMessageQueue($msgBatchID, trim(str_replace(";", ",", $to), ";, "), "", "", $message, $subject, "", "Email");
                            }
                        }
                    }
                }
                if ($prsnsFnd <= 0) {
                    $dsply .= "<br/>|ERROR|-No Approval Hierarchy Found";
                    $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
                } else {
                    //Update Request Status to In Process
                    updateLoanRqst($srcdocid, "Initiated");
                }
            } else {
                $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Generated";
                $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
            }
        } else {
            if ($routingID > 0) {
                $oldMsgbodyAddOn = "";
                $reslt1 = getWkfMsgRtngData($routingID);
                while ($row = loc_db_fetch_array($reslt1)) {
                    $rtngMsgID = (float) $row[0];
                    $msg_id = $rtngMsgID;
                    $curPrsnsLevel = (float) $row[18];
                    $isActionDone = $row[9];
                    $oldMsgbodyAddOn = $row[17];
                    //$rtngMsgID = (float) getGnrlRecNm("wkf.wkf_actual_msgs_routng", "routing_id", "msg_id", $routingID);
                    //$curPrsnsLevel = (float) getGnrlRecNm("wkf.wkf_actual_msgs_routng", "routing_id", "to_prsns_hrchy_level", $routingID);
                    //$isActionDone = getGnrlRecNm("wkf.wkf_actual_msgs_routng", "routing_id", "is_action_done", $routingID);
                }
                $row = NULL;

                $reslt2 = getWkfMsgHdrData($rtngMsgID);
                while ($row = loc_db_fetch_array($reslt2)) {
                    $msgTitle = $row[1]; //getGnrlRecNm("wkf.wkf_actual_msgs_hdr", "msg_id", "msg_hdr", $rtngMsgID);
                    $msgBdy = $row[2]; //getGnrlRecNm("wkf.wkf_actual_msgs_hdr", "msg_id", "msg_body", $rtngMsgID);
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
                $orgnlPrsnID = getUserPrsnID1($orgnlPrsnUsrID);
                if ($isActionDone == '0') {
                    if ($actionToPrfrm == "Open") {
                        echo LoanRqstRODsply($srcDocID);
                    } else if ($actionToPrfrm == "Reject") {
                        $affctd += updtWkfMsgRtngUsngLvl($rtngMsgID, $curPrsnsLevel, $fromPrsnID, "Rejected", "None", $userID);
                        //$affctd1+= updateWkfMsgBdy($rtngMsgID, $msgbodyAddOn, $userID);
                        $datestr = getFrmtdDB_Date_time();
                        $msgbodyAddOn = "";
                        $msgbodyAddOn .= "REJECTION ON $datestr:- This document has been Rejected by $usrFullNm with the ff Message:<br/>";
                        $msgbodyAddOn .= $apprvrCmmnts . "<br/><br/>";
                        $affctd1 += updtWkfMsgRtngCmnts($routingID, $msgbodyAddOn, $userID);
                        $msgbodyAddOn .= $oldMsgbodyAddOn;

                        updateWkfMsgStatus($rtngMsgID, "1", $userID);
                        updtWkfMsgAllUnclsdRtng($rtngMsgID, $fromPrsnID, "Closed", "None", $userID);

                        //Message Header & Details
                        $msghdr = "REJECTED - " . $msgTitle;
                        $msgbody = $msgBdy; //$msgbodyAddOn. "ORIGINAL MESSAGE :<br/><br/>" .
                        $msgtyp = "Informational";
                        $msgsts = "0";
                        //$msg_id = getWkfMsgID();
                        $affctd2 += updateWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
                        $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $orgnlPrsnID, $userID, "Initiated", "Acknowledge;Open", 1, $msgbodyAddOn);
                        $affctd4 += updateLoanRqst($srcdocid, "Rejected");

                        //Begin Email Sending Process                    
                        $result = getEmlDetailsAftrActn($srcdoctyp, $srcdocid);
                        $lastPrsnID = "|";
                        $msgBatchID = getMsgBatchID();
                        $paramRepsNVals = $prmID . "~" . $msgBatchID . "|-190~HTML";
                        while ($row = loc_db_fetch_array($result)) {
                            $frmID = $row[0];
                            if (strpos($lastPrsnID, "|" . $frmID . "|") !== FALSE || $frmID == $fromPrsnID) {
                                $lastPrsnID .= $frmID . "|";
                                continue;
                            }
                            $lastPrsnID .= $frmID . "|";
                            $subject = $row[1];
                            $actSoFar = $row[3];
                            if ($actSoFar == "") {
                                $actSoFar = "&nbsp;&nbsp;NONE";
                            }
                            $msgPart = "<span style=\"font-weight:bold;text-decoration:underline;color:blue;\">ACTIONS TAKEN SO FAR:</span><br/>" . $actSoFar . "<br/> <span style=\"font-weight:bold;text-decoration:underline;color:blue;\">ORIGINAL MESSAGE:</span><br/>&nbsp;&nbsp;" . $row[2];
                            $docType = $srcDocType;
                            $to = getPrsnEmail($frmID);
                            $nameto = getPrsnFullNm($frmID);
                            if ($docType != "" && $docType != "Login") {
                                $message = "Dear $nameto, <br/><br/>A notification has been sent to your account in the Portal as follows:"
                                    . "<br/><br/>"
                                    . $msgPart .
                                    "<br/><br/>Kindly <a href=\""
                                    . $app_url . "\">Login via this Link</a> to <strong>VIEW and ACT</strong> on it!<br/>Thank you for your cooperation!<br/><br/>Best Regards,<br/>" . $admin_name;
                                $errMsg = "";
                                createMessageQueue($msgBatchID, trim(str_replace(";", ",", $to), ";, "), "", "", $message, $subject, "", "Email");
                                //sendEMail(trim(str_replace(";", ",", $to), ","), $nameto, $subject, $message, $errMsg, "", "", "", $admin_name);
                            }
                        }
                        if ($affctd > 0) {
                            $dsply .= "<br/>Status of $affctd Workflow Document(s) successfully updated to Rejected!";
                            $dsply .= "<br/>$affctd1 Workflow Document(s) Message Body Successfully Updated!";
                            //$dsply .= "<br/>$affctd2 New Workflow Document(s) Message Body Successfully Created!";
                            $dsply .= "<br/>$affctd3 Workflow Document(s) Successfully Re-Routed to Original Sender!";
                            $dsply .= "<br/>$affctd4 Request Status Successfully Updated!";
                            $msg = "<p style = \"text-align:left; color:#32CD32;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>"; //#32CD32
                        } else {
                            $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Rejected";
                            $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
                        }
                    } else if ($actionToPrfrm == "Withdraw") {
                        $affctd += updtWkfMsgRtngUsngLvl($rtngMsgID, $curPrsnsLevel, $fromPrsnID, "Rejected", "None", $userID);
                        //$affctd1+= updateWkfMsgBdy($rtngMsgID, $msgbodyAddOn, $userID);
                        $datestr = getFrmtdDB_Date_time();
                        $msgbodyAddOn = "";
                        $msgbodyAddOn .= "WITHDRAWAL ON $datestr:- This document has been withdrawn by $usrFullNm with the ff Message:<br/>";
                        $msgbodyAddOn .= $apprvrCmmnts . "<br/><br/>";
                        $affctd1 += updtWkfMsgRtngCmnts($routingID, $msgbodyAddOn, $userID);
                        $msgbodyAddOn .= $oldMsgbodyAddOn;

                        updateWkfMsgStatus($rtngMsgID, "1", $userID);
                        updtWkfMsgAllUnclsdRtng($rtngMsgID, $fromPrsnID, "Closed", "None", $userID);

                        //Message Header & Details
                        $msghdr = "WITHDRAWN - " . $msgTitle;
                        $msgbody = $msgBdy; //$msgbodyAddOn. "ORIGINAL MESSAGE :<br/><br/>" .
                        $msgtyp = "Informational";
                        $msgsts = "0";
                        //$msg_id = getWkfMsgID();
                        $affctd2 += updateWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
                        $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $orgnlPrsnID, $userID, "Initiated", "Acknowledge;Open", 1, $msgbodyAddOn);
                        $affctd4 += updateLoanRqst($srcdocid, "Withdrawn");

                        //Begin Email Sending Process                    
                        $result = getEmlDetailsAftrActn($srcdoctyp, $srcdocid);
                        $lastPrsnID = "|";
                        $msgBatchID = getMsgBatchID();
                        $paramRepsNVals = $prmID . "~" . $msgBatchID . "|-190~HTML";
                        while ($row = loc_db_fetch_array($result)) {
                            $frmID = $row[0];
                            if (strpos($lastPrsnID, "|" . $frmID . "|") !== FALSE || $frmID == $fromPrsnID) {
                                $lastPrsnID .= $frmID . "|";
                                continue;
                            }
                            $lastPrsnID .= $frmID . "|";
                            $subject = $row[1];
                            $actSoFar = $row[3];
                            if ($actSoFar == "") {
                                $actSoFar = "&nbsp;&nbsp;NONE";
                            }
                            $msgPart = "<span style=\"font-weight:bold;text-decoration:underline;color:blue;\">ACTIONS TAKEN SO FAR:</span><br/>" . $actSoFar . "<br/> <span style=\"font-weight:bold;text-decoration:underline;color:blue;\">ORIGINAL MESSAGE:</span><br/>&nbsp;&nbsp;" . $row[2];
                            $docType = $srcDocType;
                            $to = getPrsnEmail($frmID);
                            $nameto = getPrsnFullNm($frmID);
                            if ($docType != "" && $docType != "Login") {
                                $message = "Dear $nameto, <br/><br/>A notification has been sent to your account in the Portal as follows:"
                                    . "<br/><br/>"
                                    . $msgPart .
                                    "<br/><br/>Kindly <a href=\""
                                    . $app_url . "\">Login via this Link</a> to <strong>VIEW and ACT</strong> on it!<br/>Thank you for your cooperation!<br/><br/>Best Regards,<br/>" . $admin_name;
                                $errMsg = "";
                                createMessageQueue($msgBatchID, trim(str_replace(";", ",", $to), ";, "), "", "", $message, $subject, "", "Email");
                                //sendEMail(trim(str_replace(";", ",", $to), ","), $nameto, $subject, $message, $errMsg, "", "", "", $admin_name);
                            }
                        }
                        if ($affctd > 0) {
                            $dsply .= "<br/>Status of $affctd Workflow Document(s) successfully updated to Withdrawn!";
                            $dsply .= "<br/>$affctd1 Workflow Document(s) Message Body Successfully Updated!";
                            //$dsply .= "<br/>$affctd2 New Workflow Document(s) Message Body Successfully Created!";
                            $dsply .= "<br/>$affctd3 Workflow Document(s) Successfully Re-Routed to Original Sender!";
                            $dsply .= "<br/>$affctd4 Request Status Successfully Updated!";
                            $msg = "<p style = \"text-align:left; color:#32CD32;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>"; //#32CD32
                        } else {
                            $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Rejected";
                            $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
                        }
                    } else if ($actionToPrfrm == "Request for Information") {
                        $nwPrsnID = getPersonID($nwPrsnLocID);
                        //$nwPrsnFullNm = getPrsnFullNm($nwPrsnID);
                        $affctd += updtWkfMsgRtngUsngLvl($rtngMsgID, $curPrsnsLevel, $fromPrsnID, "Information Requested", "None", $userID);
                        //$affctd1+= updateWkfMsgBdy($rtngMsgID, $msgbodyAddOn, $userID);
                        $datestr = getFrmtdDB_Date_time();
                        $msgbodyAddOn = "";
                        $msgbodyAddOn .= "INFORMATION REQUESTED ON $datestr:- A requested for Information has been generated by $usrFullNm with the ff Message:<br/>";
                        $msgbodyAddOn .= $apprvrCmmnts . "<br/><br/>";
                        $affctd1 += updtWkfMsgRtngCmnts($routingID, $msgbodyAddOn, $userID);
                        $msgbodyAddOn .= $oldMsgbodyAddOn;

                        updateWkfMsgStatus($rtngMsgID, "1", $userID);
                        updtWkfMsgAllUnclsdRtng($rtngMsgID, $fromPrsnID, "Closed", "None", $userID);

                        //Message Header & Details
                        $msghdr = "INFORMATION REQUEST - " . $msgTitle;
                        $msgbody = $msgBdy; //"ORIGINAL MESSAGE :<br/><br/>" . 
                        $msgtyp = "Work Document";
                        $msgsts = "0";
                        //$msg_id = getWkfMsgID();
                        $affctd2 += updateWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
                        $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $nwPrsnID, $userID, "Initiated", "Respond;Open", $curPrsnsLevel, $msgbodyAddOn);
                        //$affctd4+=updateLoanRqst($srcdocid, "Rejected");
                        if ($affctd > 0) {
                            $dsply .= "<br/>Status of $affctd Workflow Document(s) successfully updated to Information Requested!";
                            $dsply .= "<br/>$affctd1 Workflow Document(s) Message Body Successfully Updated!";
                            //$dsply .= "<br/>$affctd2 New Workflow Document(s) Message Body Successfully Created!";
                            $dsply .= "<br/>$affctd3 Workflow Document(s) Successfully Re-Routed to New Person!";
                            // $dsply .= "<br/>$affctd4 Appointment Status Successfully Updated!";
                            $msg = "<p style = \"text-align:left; color:#32CD32;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>"; //#32CD32
                        } else {
                            $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Worked On";
                            $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
                        }
                    } else if ($actionToPrfrm == "Respond") {
                        $nwPrsnID = getPersonID($nwPrsnLocID);
                        //$nwPrsnFullNm = getPrsnFullNm($nwPrsnID);
                        $affctd += updtWkfMsgRtngUsngLvl($rtngMsgID, $curPrsnsLevel, $fromPrsnID, "Response Given", "None", $userID);
                        //$affctd1+= updateWkfMsgBdy($rtngMsgID, $msgbodyAddOn, $userID);
                        $datestr = getFrmtdDB_Date_time();
                        $msgbodyAddOn = "";
                        $msgbodyAddOn .= "RESPONSE TO INFORMATION REQUESTED ON $datestr:- A response to an Information Request has been given by $usrFullNm with the ff Message:<br/>";
                        $msgbodyAddOn .= $apprvrCmmnts . "<br/><br/>";
                        $affctd1 += updtWkfMsgRtngCmnts($routingID, $msgbodyAddOn, $userID);
                        $msgbodyAddOn .= $oldMsgbodyAddOn;

                        updateWkfMsgStatus($rtngMsgID, "1", $userID);
                        updtWkfMsgAllUnclsdRtng($rtngMsgID, $fromPrsnID, "Closed", "None", $userID);

                        //Message Header & Details
                        $msghdr = "RESPONSE TO INFORMATION REQUEST - " . $msgTitle;
                        $msgbody = $msgBdy; //"ORIGINAL MESSAGE :<br/><br/>" . 
                        $msgtyp = "Work Document";
                        $msgsts = "0";
                        //$msg_id = getWkfMsgID();
                        $affctd2 += updateWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
                        $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $nwPrsnID, $userID, "Initiated", 'Open;Reject;Request for Information;Approve', $curPrsnsLevel, $msgbodyAddOn);
                        //$affctd4+=updateLoanRqst($srcdocid, "Rejected");
                        if ($affctd > 0) {
                            $dsply .= "<br/>Status of $affctd Workflow Document(s) successfully updated to Response Given!";
                            $dsply .= "<br/>$affctd1 Workflow Document(s) Message Body Successfully Updated!";
                            //$dsply .= "<br/>$affctd2 New Workflow Document(s) Message Body Successfully Created!";
                            $dsply .= "<br/>$affctd3 Workflow Document(s) Successfully Re-Routed to New Person!";
                            // $dsply .= "<br/>$affctd4 Appointment Status Successfully Updated!";
                            $msg = "<p style = \"text-align:left; color:#32CD32;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>"; //#32CD32
                        } else {
                            $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Worked On";
                            $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
                        }
                    } else if ($actionToPrfrm == "Acknowledge") {
                        $nwPrsnID = getPersonID($nwPrsnLocID);
                        //$nwPrsnFullNm = getPrsnFullNm($nwPrsnID);
                        $affctd += updtWkfMsgRtngUsngLvl($rtngMsgID, $curPrsnsLevel, $fromPrsnID, "Acknowledged", "None", $userID);
                        //$affctd1+= updateWkfMsgBdy($rtngMsgID, $msgbodyAddOn, $userID);
                        $datestr = getFrmtdDB_Date_time();
                        $msgbodyAddOn = "";
                        $msgbodyAddOn .= "MESSAGE ACKNOWLEDGED ON $datestr:- An acknowledgement of the message has been given by $usrFullNm <br/><br/>";
                        //$msgbodyAddOn.=$apprvrCmmnts . "<br/><br/>";
                        $affctd1 += updtWkfMsgRtngCmnts($routingID, $msgbodyAddOn, $userID);

                        updateWkfMsgStatus($rtngMsgID, "1", $userID);
                        updtWkfMsgAllUnclsdRtng($rtngMsgID, $fromPrsnID, "Closed", "None", $userID);
                        if ($affctd > 0) {
                            $dsply .= "<br/>Status of $affctd Workflow Document(s) successfully updated to Acknowledged!";
                            $dsply .= "<br/>$affctd1 Workflow Document(s) Message Body Successfully Updated!";
                            //$dsply .= "<br/>$affctd2 New Workflow Document(s) Message Body Successfully Created!";
                            //$dsply .= "<br/>$affctd3 Workflow Document(s) Successfully Re-Routed to New Person!";
                            // $dsply .= "<br/>$affctd4 Appointment Status Successfully Updated!";
                            $msg = "<p style = \"text-align:left; color:#32CD32;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>"; //#32CD32
                        } else {
                            $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Worked On";
                            $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
                        }
                    } else if ($actionToPrfrm == "Approve") {
                        $nxtPrsnsRslt = getNextApprvrsInMnlHrchy($hrchyid, $curPrsnsLevel);
                        $prsnsFnd = 0;
                        $lastPrsnID = "|";
                        $msgbodyAddOn = "";
                        while ($row = loc_db_fetch_array($nxtPrsnsRslt)) {
                            $nxtPrsnID = (float) $row[0];
                            $newStatus = "Reviewed";
                            $nxtStatus = "Open;Reject;Request for Information;Approve";
                            $nxtApprvr = "Next Approver";
                            if ($prsnsFnd == 0) {
                                $affctd += updtWkfMsgRtngUsngLvl($rtngMsgID, $curPrsnsLevel, $fromPrsnID, $newStatus, $nxtStatus, $userID);
                                $datestr = getFrmtdDB_Date_time();
                                $msgbodyAddOn .= strtoupper($newStatus) . " ON $datestr:- This document has been $newStatus by $usrFullNm <br/><br/>";
                                $affctd1 += updtWkfMsgRtngCmnts($routingID, $msgbodyAddOn, $userID);
                                $msgbodyAddOn .= $oldMsgbodyAddOn;
                                updtWkfMsgAllUnclsdRtng($rtngMsgID, $fromPrsnID, "Closed", "None", $userID);
                                $msghdr = $msgTitle;
                                $msgbody = $msgBdy;
                                $msgtyp = "Work Document";
                                $msgsts = "0";
                                $curPrsnsLevel += 1;
                                $affctd2 += updateWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
                            }
                            $prsnsFnd++;
                            if ($nxtPrsnID > 0) {
                                $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $nxtPrsnID, $userID, $newStatus, $nxtStatus, $curPrsnsLevel, $msgbodyAddOn);
                            }
                            if ($prsnsFnd == 1) {
                                $affctd4 += updateLoanRqst($srcdocid, $newStatus);
                            }
                        }
                        if ($prsnsFnd <= 0) {
                            $newStatus = "Approved";
                            $nxtStatus = "None;Acknowledge";
                            $nxtApprvr = "Original Person";
                            $affctd += updtWkfMsgRtngUsngLvl($rtngMsgID, $curPrsnsLevel, $fromPrsnID, $newStatus, $nxtStatus, $userID);
                            $datestr = getFrmtdDB_Date_time();
                            $msgbodyAddOn = "";
                            $msgbodyAddOn .= strtoupper($newStatus) . " ON $datestr:- This document has been $newStatus by $usrFullNm <br/><br/>";
                            $affctd1 += updtWkfMsgRtngCmnts($routingID, $msgbodyAddOn, $userID);
                            $msgbodyAddOn .= $oldMsgbodyAddOn;
                            updtWkfMsgAllUnclsdRtng($rtngMsgID, $fromPrsnID, "Closed", "None", $userID);
                            updateWkfMsgStatus($rtngMsgID, "1", $userID);
                            $msghdr = "APPROVED - " . $msgTitle;
                            $msgbody = $msgBdy;
                            $msgtyp = "Informational";
                            $msgsts = "0";
                            $curPrsnsLevel += 1;
                            $affctd2 += updateWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
                            $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $orgnlPrsnID, $userID, $newStatus, $nxtStatus, $curPrsnsLevel, $msgbodyAddOn);
                            $affctd4 += updateLoanRqst($srcdocid, $newStatus);
                            //Begin Email Sending Process                    
                            $result = getEmlDetailsAftrActn($srcdoctyp, $srcdocid);
                            $lastPrsnID = "|";
                            $msgBatchID = getMsgBatchID();
                            $paramRepsNVals = $prmID . "~" . $msgBatchID . "|-190~HTML";
                            while ($row = loc_db_fetch_array($result)) {
                                $frmID = $orgnlPrsnID;
                                if (strpos($lastPrsnID, "|" . $frmID . "|") !== FALSE) {
                                    $lastPrsnID .= $frmID . "|";
                                    continue;
                                }
                                $lastPrsnID .= $frmID . "|";
                                $subject = $row[1];
                                $actSoFar = $row[3];
                                if ($actSoFar == "") {
                                    $actSoFar = "&nbsp;&nbsp;NONE";
                                }
                                $msgPart = "<span style=\"font-weight:bold;text-decoration:underline;color:blue;\">ACTIONS TAKEN SO FAR:</span><br/>" . $actSoFar . "<br/> <span style=\"font-weight:bold;text-decoration:underline;color:blue;\">ORIGINAL MESSAGE:</span><br/>&nbsp;&nbsp;" . $row[2];
                                $docType = $srcDocType;
                                $to = getPrsnEmail($frmID);
                                $nameto = getPrsnFullNm($frmID);
                                if ($docType != "" && $docType != "Login") {
                                    $message = "Dear $nameto, <br/><br/>A notification has been sent to your account in the Portal as follows:"
                                        . "<br/><br/>"
                                        . $msgPart .
                                        "<br/><br/>Kindly <a href=\""
                                        . $app_url . "\">Login via this Link</a> to <strong>VIEW</strong> it!<br/>Thank you for your cooperation!<br/><br/>Best Regards,<br/>" . $admin_name;
                                    $errMsg = "";
                                    createMessageQueue($msgBatchID, trim(str_replace(";", ",", $to), ";, "), "", "", $message, $subject, "", "Email");
                                    //sendEMail(trim(str_replace(";", ",", $to), ","), $nameto, $subject, $message, $errMsg, "", "", "", $admin_name);
                                }
                                break;
                            }
                        }
                        if ($affctd > 0) {
                            $dsply .= "<br/>Status of $affctd Workflow Document(s) successfully updated to $newStatus!";
                            $dsply .= "<br/>$affctd1 Workflow Document(s) Message Body Successfully Updated!";
                            //$dsply .= "<br/>$affctd2 New Workflow Document(s) Message Body Successfully Created!";
                            $dsply .= "<br/>$affctd3 Workflow Document(s) Successfully Re-Routed to $nxtApprvr!";
                            $dsply .= "<br/>$affctd4 Request Status Successfully Updated!";
                            $msg = "<p style = \"text-align:left; color:#32CD32;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>"; //#32CD32
                        } else {
                            $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Worked On";
                            $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
                        }
                    }
                }
            } else {
                $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Selected";
                $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
            }
        }
        if ($msgBatchID > 0) {
            generateReportRun($rptID, $paramRepsNVals, -1);
        }
        return $msg;
    } catch (Exception $e) {
        $errMsg = 'Caught exception:' . $e->getMessage();
        return $errMsg;
    }
}

function LoanRqstRODsply($sbmtdPayTrnsRqstsID)
{
    //New Loan and Payment Requests Form
    global $gnrlTrnsDteDMYHMS;
    global $fnccurnm;
    global $fnccurid;
    global $orgID;

    $payTrnsRqstsType = "LOAN";
    $canAdd = FALSE;
    $canEdt = FALSE;
    $vPsblValID1 = getEnbldPssblValID("Application Instance SHORT CODE", getLovID("All Other General Setups"));
    $vPsblVal1 = getPssblValDesc($vPsblValID1);
    $payTrnsRqstsPrsnID = -1;
    $payTrnsRqstsPrsnNm = "";
    $payTrnsRqstsItmTypID = -1;
    $payTrnsRqstsItmTypNm = "";
    $payTrnsRqstsClsfctn = "";
    $payTrnsRqstsDesc = "";
    $payTrnsRqstsDate = $gnrlTrnsDteDMYHMS;
    $rqStatus = "Not Submitted"; //approval_status
    $rqStatusNext = "Approve"; //next_aproval_action
    $rqstatusColor = "red";
    $payTrnsRqstsInvcCur = $fnccurnm;
    $payTrnsRqstsInvcCurID = $fnccurid;
    $payTrnsRqstsAmnt = 0;
    $payTrnsRqstsPrdcDdctAmt = 0;
    $payTrnsRqstsIntrstRt = 0;
    $payTrnsRqstsIntrstTyp = "% Per Annum"; //Flat
    $payTrnsRqstsRepayPrd = 0;
    $payTrnsRqstsRepayPrdTyp = "Installments";
    $payTrnsRqstsHsAgreed = "0";
    $payTrnsRqstsIsPrcsd = "0";
    $payTrnsRqstsNetAmt = 0;
    $payTrnsRqstsMaxAmt = 0;
    $payTrnsRqstsEnfrcMx = "0";
    $mkReadOnly = "";
    $mkRmrkReadOnly = "";
    $lnkdPayTrnsRqstsID = -1;
    $payTrnsRqstsMinAmnt = 0;
    if ($sbmtdPayTrnsRqstsID > 0) {
        $result = get_One_TrnsRqstsDocHdr($sbmtdPayTrnsRqstsID);
        if ($row = loc_db_fetch_array($result)) {
            $payTrnsRqstsPrsnID = (float) $row[1];
            $payTrnsRqstsPrsnNm = $row[2];
            $payTrnsRqstsType = $row[3];
            $payTrnsRqstsItmTypID = (int) $row[4];
            $payTrnsRqstsItmTypNm = $row[5];
            $payTrnsRqstsClsfctn = $row[6];
            $payTrnsRqstsDesc = $row[7];
            $payTrnsRqstsDate = $row[8];

            $payTrnsRqstsAmnt = (float) $row[9];
            $payTrnsRqstsPrdcDdctAmt = (float) $row[10];
            $payTrnsRqstsIntrstRt = (float) $row[11];
            $payTrnsRqstsIntrstTyp = $row[16];
            $payTrnsRqstsRepayPrd = (float) $row[12];
            $payTrnsRqstsRepayPrdTyp = $row[17];
            $payTrnsRqstsHsAgreed = $row[14];
            $payTrnsRqstsIsPrcsd = $row[15];
            $payTrnsRqstsNetAmt = (float) $row[18];
            $payTrnsRqstsMaxAmt = (float) $row[19];
            $payTrnsRqstsEnfrcMx = $row[20];
            $lnkdPayTrnsRqstsID = (float) $row[21];
            $payTrnsRqstsMinAmnt = (float) $row[22];

            $rqStatus = $row[13];
            $rqStatusNext = "";
            $rqstatusColor = "red";

            if ($rqStatus == "Approved") {
                $rqstatusColor = "green";
            } else {
                $rqstatusColor = "red";
            }
            $canEdt = FALSE;
            $mkReadOnly = "readonly=\"true\"";
            if ($rqStatus != "Approved") {
                $mkRmrkReadOnly = "readonly=\"true\"";
            }
        }
    } else {
        return "ERROR-Nothing to Display!!";
    }
    $rqstStatus = $rqStatus;
    $routingID = getMxRoutingID($sbmtdPayTrnsRqstsID, "Internal Pay " . ucfirst(strtolower($payTrnsRqstsType)) . " Requests");
    $reportTitle = "Loan Application Letter";
    if ($payTrnsRqstsType == "SETTLEMENT") {
        $reportTitle = "Loan Settlement Request";
    } else if ($payTrnsRqstsType == "PAYMENT") {
        $reportTitle = "Dues Contribution Payment Request";
    }
    $reportName = "Welfare Loan Application Letter";
    $rptID = getRptID($reportName);
    $prmID1 = getParamIDUseSQLRep("{:pay_rqst_id}", $rptID);
    $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
    $trnsID = $sbmtdPayTrnsRqstsID;
    $paramRepsNVals = $prmID1 . "~" . $trnsID . "|" . $prmID2 . "~" . $reportTitle . "|-130~" . $reportTitle . "|-190~PDF";
    $paramStr = urlencode($paramRepsNVals);
    ?>
    <form class="form-horizontal" id="onePayTrnsRqstsEDTForm">
        <div class="row" style="margin-top:5px;">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="col-md-4">
                        <label style="margin-bottom:0px !important;">Request ID/Date:</label>
                    </div>
                    <div class="col-md-2" style="padding:0px 1px 0px 15px;">
                        <input type="hidden" value="<?php echo $vPsblVal1; ?>" id="sbmtdPayTrnsAppCODE" name="sbmtdPayTrnsAppCODE">
                        <input type="hidden" value="<?php echo $payTrnsRqstsEnfrcMx; ?>" id="payTrnsRqstsEnfrcMx" name="payTrnsRqstsEnfrcMx">
                        <input type="text" class="form-control" aria-label="..." id="sbmtdPayTrnsRqstsID" name="sbmtdPayTrnsRqstsID" value="<?php echo $sbmtdPayTrnsRqstsID; ?>" readonly="true">
                    </div>
                    <div class="col-md-2" style="padding:0px 1px 0px 1px;">
                        <input type="text" class="form-control" aria-label="..." id="payTrnsRqstsType" name="payTrnsRqstsType" value="<?php echo $payTrnsRqstsType; ?>" readonly="true">
                    </div>
                    <div class="col-md-4" style="padding:0px 15px 0px 1px;">
                        <input type="text" class="form-control" aria-label="..." id="payTrnsRqstsDate" name="payTrnsRqstsDate" value="<?php echo $payTrnsRqstsDate; ?>" readonly="true">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-4">
                        <label style="margin-bottom:0px !important;"><?php echo ucfirst(strtolower($payTrnsRqstsType)); ?> Type / Classification:</label>
                    </div>
                    <div class="col-md-4" style="padding:0px 1px 0px 15px;">
                        <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="payTrnsRqstsItmTypID" name="payTrnsRqstsItmTypID" style="width:100% !important;" onchange="shwHidePayTrnsFlds();">
                            <?php
                            $lqlovNm = "Internal Pay Loan Types";
                            if ($payTrnsRqstsType == "PAYMENT") {
                                $lqlovNm = "Internal Pay Payment Types";
                            } else if ($payTrnsRqstsType == "SETTLEMENT") {
                                $lqlovNm = "Internal Pay Settlement Types";
                            }
                            $cnt = 0;
                            $brghtStr = "";
                            $isDynmyc = true;
                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID($lqlovNm), $isDynmyc, $orgID, "", "");
                            while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                $selectedTxt = "";
                                if ($cnt == 0 && $payTrnsRqstsItmTypID <= 0) {
                                    $payTrnsRqstsItmTypID = ((int) $titleRow[0]);
                                }
                                $cnt++;
                                if (((int) $titleRow[0]) == $payTrnsRqstsItmTypID) {
                                    $selectedTxt = "selected";
                                }
                            ?>
                                <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[1]; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4" style="padding:0px 15px 0px 1px;">
                        <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="payTrnsRqstsClsfctn" name="payTrnsRqstsClsfctn" style="width:100% !important;" onchange="shwHideTrnsTypsDivs();">
                            <?php
                            $lqlovNm = "Internal Pay Loan Classifications";
                            if ($payTrnsRqstsType == "PAYMENT") {
                                $lqlovNm = "Internal Pay Payment Classifications";
                            } else if ($payTrnsRqstsType == "SETTLEMENT") {
                                $lqlovNm = "Internal Pay Settlement Classifications";
                            }
                            //Semi-Month
                            $brghtStr = "";
                            $isDynmyc = true;
                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID($lqlovNm), $isDynmyc, $payTrnsRqstsItmTypID, "", "");
                            while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                /* if (!($titleRow[1] == "All" || trim($titleRow[1]) == "" || $titleRow[1] == $vPsblVal1)) {
                                  continue;
                                  } */
                                $selectedTxt = "";
                                if ($titleRow[0] == $payTrnsRqstsClsfctn) {
                                    $selectedTxt = "selected";
                                }
                            ?>
                                <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-4">
                        <label for="payTrnsRqstsPrsnNm" class="control-label">Person:</label>
                    </div>
                    <div class="col-md-8">
                        <?php if ($canEdt === true || $canAdd === true) { ?>
                            <div class="input-group" style="width:100% !important;">
                                <input type="text" name="payTrnsRqstsPrsnNm" id="payTrnsRqstsPrsnNm" class="form-control" value="<?php echo $payTrnsRqstsPrsnNm; ?>" readonly="true" style="width:100% !important;">
                                <input type="hidden" name="payTrnsRqstsPrsnID" id="payTrnsRqstsPrsnID" class="form-control" value="<?php echo $payTrnsRqstsPrsnID; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payTrnsRqstsPrsnID', 'payTrnsRqstsPrsnNm', 'clear', 0, '', function () {shwHidePayPrevLoans();});">
                                    <span class="glyphicon glyphicon-th-list"></span>
                                </label>
                            </div>
                        <?php } else { ?>
                            <input type="hidden" name="payTrnsRqstsPrsnID" id="payTrnsRqstsPrsnID" class="form-control" value="<?php echo $payTrnsRqstsPrsnID; ?>">
                            <span><?php echo $payTrnsRqstsPrsnNm; ?></span>
                        <?php } ?>
                    </div>
                </div>
                <?php if ($payTrnsRqstsType == "SETTLEMENT") { ?>
                    <div class="form-group">
                        <div class="col-md-4">
                            <label style="margin-bottom:0px !important;">Linked Loan Request:</label>
                        </div>
                        <div class="col-md-8">
                            <select data-placeholder="Select..." class="form-control chosen-select" id="lnkdPayTrnsRqstsID" name="lnkdPayTrnsRqstsID" style="width:100% !important;">
                                <?php
                                $payTrnsRqstsDpndtItmTypID = (float) getGnrlRecNm("pay.loan_pymnt_invstmnt_typs", "item_type_id", "lnkd_loan_type_id", $payTrnsRqstsItmTypID);
                                $payTrnsRqstsDpndtBalsItmID = (float) getGnrlRecNm("pay.loan_pymnt_invstmnt_typs", "item_type_id", "lnkd_loan_mn_itm_id", $payTrnsRqstsItmTypID);
                                $cnt = 0;
                                $titleRslt = get_UnsttldLoanRqsts("%", "Requestor", 0, 500, $orgID, $payTrnsRqstsPrsnID, $payTrnsRqstsDpndtItmTypID, $payTrnsRqstsDpndtBalsItmID, "LOAN");
                                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                    $selectedTxt = "";
                                    $cnt++;
                                    if (((float) $titleRow[0]) == $lnkdPayTrnsRqstsID) {
                                        $selectedTxt = "selected";
                                    }
                                ?>
                                    <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[5]; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                <?php } ?>
                <div class="form-group">
                    <div class="col-md-4">
                        <label style="margin-bottom:0px !important;">Remark / Narration:</label>
                    </div>
                    <div class="col-md-8">
                        <div class="input-group" style="width:100%;">
                            <textarea class="form-control rqrdFld" rows="2" cols="20" id="payTrnsRqstsDesc" name="payTrnsRqstsDesc" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $payTrnsRqstsDesc; ?></textarea>
                            <input class="form-control" type="hidden" id="payTrnsRqstsDesc1" value="<?php echo $payTrnsRqstsDesc; ?>">
                            <label class="btn btn-default btn-file input-group-addon" onclick="popUpDisplay('payTrnsRqstsDesc');" style="max-width:30px;width:30px;">
                                <i class="fas fa-th-list"></i>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-4">
                        <label style="margin-bottom:0px !important;">Amount:</label>
                    </div>
                    <div class="col-md-8">
                        <div class="input-group">
                            <label class="btn btn-info btn-file input-group-addon active" onclick="">
                                <span class="" style="" id="payTrnsRqstsInvcCur1"><?php echo $payTrnsRqstsInvcCur; ?></span>
                            </label>
                            <input type="hidden" id="payTrnsRqstsInvcCur" value="<?php echo $payTrnsRqstsInvcCur; ?>">
                            <input class="form-control rqrdFld" type="text" id="payTrnsRqstsAmnt" value="<?php
                                                                                                            echo number_format($payTrnsRqstsAmnt, 2);
                                                                                                            ?>" style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('payTrnsRqstsAmnt');" <?php echo $mkReadOnly; ?> />
                        </div>
                    </div>
                </div>
                <?php
                if (($payTrnsRqstsType == "LOAN" || $payTrnsRqstsType == "SETTLEMENT") && $sbmtdPayTrnsRqstsID > 0) {
                    $antcptdIntrst = (($payTrnsRqstsPrdcDdctAmt * $payTrnsRqstsRepayPrd) - $payTrnsRqstsAmnt);
                    if ($payTrnsRqstsType == "SETTLEMENT") {
                        $antcptdIntrst = $payTrnsRqstsNetAmt - $payTrnsRqstsAmnt;
                    }
                ?>
                    <div class="form-group">
                        <div class="col-md-4">
                            <label style="margin-bottom:0px !important;">Anticipated Total Interest:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group">
                                <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                    <span class="" style="" id="payTrnsRqstsInvcCur5"><?php echo $payTrnsRqstsInvcCur; ?></span>
                                </label>
                                <input type="hidden" id="payTrnsRqstsInvcCur4" value="<?php echo $payTrnsRqstsInvcCur; ?>">
                                <input class="form-control" type="text" id="payTrnsRqstsIntrstAmt" value="<?php echo number_format($antcptdIntrst, 2); ?>" style="font-weight:bold;width:100%;font-size:18px !important;" readonly="true" />
                            </div>
                        </div>
                    </div>
                    <?php if ($payTrnsRqstsType != "SETTLEMENT") { ?>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label style="margin-bottom:0px !important;">Installment Deductions:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                        <span class="" style="" id="payTrnsRqstsInvcCur3"><?php echo $payTrnsRqstsInvcCur; ?></span>
                                    </label>
                                    <input type="hidden" id="payTrnsRqstsInvcCur2" value="<?php echo $payTrnsRqstsInvcCur; ?>">
                                    <input class="form-control" type="text" id="payTrnsRqstsPrdcDdctAmt" value="<?php echo number_format($payTrnsRqstsPrdcDdctAmt, 2);
                                                                                                                ?>" style="font-weight:bold;width:100%;font-size:18px !important;" readonly="true" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label style="margin-bottom:0px !important;">Interest Rate:</label>
                            </div>
                            <div class="col-md-4" style="padding-right:1px !important;">
                                <input class="form-control" type="text" id="payTrnsRqstsIntrstRt" value="<?php
                                                                                                            echo number_format($payTrnsRqstsIntrstRt, 2);
                                                                                                            ?>" style="font-weight:bold;width:100%;font-size:18px !important;text-align: right;" readonly="true" />
                            </div>
                            <div class="col-md-4" style="padding-left:1px !important;">
                                <input class="form-control" type="text" id="payTrnsRqstsIntrstTyp" value="<?php echo $payTrnsRqstsIntrstTyp; ?>" style="font-weight:bold;width:100%;font-size:18px !important;" readonly="true" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label style="margin-bottom:0px !important;">Repayment Period:</label>
                            </div>
                            <div class="col-md-4" style="padding-right:1px !important;">
                                <input class="form-control" type="text" id="payTrnsRqstsRepayPrd" value="<?php
                                                                                                            echo number_format($payTrnsRqstsRepayPrd, 2);
                                                                                                            ?>" style="font-weight:bold;width:100%;font-size:18px !important;text-align: right;" readonly="true" />
                            </div>
                            <div class="col-md-4" style="padding-left:1px !important;">
                                <input class="form-control" type="text" id="payTrnsRqstsRepayPrdTyp" value="<?php echo $payTrnsRqstsRepayPrdTyp; ?>" style="font-weight:bold;width:100%;font-size:18px !important;" readonly="true" />
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <div class="col-md-4">
                            <label style="margin-bottom:0px !important;">Net Amount to be Paid:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group">
                                <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                    <span class="" style="" id="payTrnsRqstsInvcCur8"><?php echo $payTrnsRqstsInvcCur; ?></span>
                                </label>
                                <input type="hidden" id="payTrnsRqstsInvcCur7" value="<?php echo $payTrnsRqstsInvcCur; ?>">
                                <input class="form-control" type="text" id="payTrnsRqstsNetAmt" value="<?php echo number_format($payTrnsRqstsNetAmt, 2);
                                                                                                        ?>" style="font-weight:bold;width:100%;font-size:18px !important;" readonly="true" />
                            </div>
                        </div>
                    </div>
                    <?php if ($payTrnsRqstsEnfrcMx == "1" && $payTrnsRqstsMaxAmt != 0) { ?>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label style="margin-bottom:0px !important;">Max Amount Allowed:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                        <span class="" style="" id="payTrnsRqstsInvcCur10"><?php echo $payTrnsRqstsInvcCur; ?></span>
                                    </label>
                                    <input type="hidden" id="payTrnsRqstsInvcCur9" value="<?php echo $payTrnsRqstsInvcCur; ?>">
                                    <input class="form-control" type="text" id="payTrnsRqstsMaxAmt" value="<?php echo number_format($payTrnsRqstsMaxAmt, 2);
                                                                                                            ?>" style="font-weight:bold;width:100%;font-size:18px !important;" readonly="true" />
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($payTrnsRqstsMinAmnt != 0) { ?>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label style="margin-bottom:0px !important;">Min Amount Allowed:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                        <span class="" style="" id="payTrnsRqstsInvcCur15"><?php echo $payTrnsRqstsInvcCur; ?></span>
                                    </label>
                                    <input type="hidden" id="payTrnsRqstsInvcCur14" value="<?php echo $payTrnsRqstsInvcCur; ?>">
                                    <input class="form-control" type="text" id="payTrnsRqstsMinAmnt" value="<?php echo number_format($payTrnsRqstsMinAmnt, 2);
                                                                                                            ?>" style="font-weight:bold;width:100%;font-size:18px !important;" readonly="true" />
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group" style="padding:6px 0px 0px 0px !important;">
                        <div class="col-md-4">
                            <label style="margin-bottom:0px !important;">&nbsp;</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-check" style="font-size: 16px !important;color:blue;font-weight: bold;font-style: italic;font-family: georgia;">
                                <label class="form-check-label">
                                    <?php
                                    $payTrnsRqstsHsAgreedChkd = "";
                                    if ($payTrnsRqstsHsAgreed == "1") {
                                        $payTrnsRqstsHsAgreedChkd = "checked=\"true\"";
                                    }
                                    ?>
                                    <input type="checkbox" class="form-check-input" onclick="" id="payTrnsRqstsHsAgreed" name="payTrnsRqstsHsAgreed" <?php echo $payTrnsRqstsHsAgreedChkd; ?>>
                                    I agree to all the Terms and Conditions associated with this Request!
                                </label>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="form-group">
                    <div class="col-md-4">
                        <label style="margin-bottom:0px !important;">Status:</label>
                    </div>
                    <div class="col-md-8">
                        <?php
                        $style2 = "color:red;";
                        if ($payTrnsRqstsIsPrcsd == "1") {
                            $style2 = "color:blue;";
                        }
                        ?>
                        <button type="button" class="btn btn-default" style="height:30px;width:100% !important;" id="myPayTrnsRqstsStatusBtn">
                            <span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;height:30px;"><?php
                                                                                                                echo $rqStatus . "<span style=\"" . $style2 . "\">" . ($payTrnsRqstsIsPrcsd == "1" ? " [Processed]" : " [Not Processed]") . "</span>";
                                                                                                                ?>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="padding:1px 15px 1px 15px !important;">
            <hr style="margin:2px 0px 2px 0px;">
        </div>
        <div class="row">
            <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                <div class="col-md-5" style="float:left;">
                    <button type="button" class="btn btn-default" style="" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="width:100% !important;">
                        <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                        Print
                    </button>
                </div>
                <div class="col-md-7" style="float:right;">
                    <div class="" style="float:right !important;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-default" style="" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, '<?php echo ucfirst(strtolower($payTrnsRqstsType)); ?> Approval Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Progress&nbsp;</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="padding:1px 15px 1px 15px !important;">
            <hr style="margin:2px 0px 2px 0px;">
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-bordered" id="myPayRnLinesTable" cellspacing="0" width="100%" style="border:1px solid #ddd;width:100%;">
                    <caption style="border-width: 1px;padding: 8px;border-style: solid;border-color: #ccc;background-color: #efefef;margin-bottom: 1px;font-weight: bold;font-size: 16px;font-style: italic;color: #333333">CURRENT BALANCE INFORMATION</caption>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Item Name</th>
                            <th style="text-align: right;">Amount (GHS)</th>
                            <th>Effective Date</th>
                            <th>Line Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result2 = get_CumltiveBals($payTrnsRqstsPrsnID);
                        $cntr = 0;
                        $curIdx = 0;
                        $lmtSze = 10;
                        $brghtTotal = 0;
                        $prpsdTtlSpnColor = "black";
                        $itmTypCnt = getBatchItmTypCnt(-1, -1, $payTrnsRqstsPrsnID);
                        while ($row2 = loc_db_fetch_array($result2)) {
                            if ($row2[14] != 'Money') {
                                continue;
                            }
                            $cntr += 1;
                        ?>
                            <tr id="myPayRnLinesRow_<?php echo $cntr; ?>" class="hand_cursor">
                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                <td class="lovtd"><?php echo $row2[6]; ?></td>
                                <td class="lovtd" style="text-align: right;"><?php echo getBatchNetAmnt($itmTypCnt, $row2[11], $row2[6], $row2[10], $row2[7], $brghtTotal, $prpsdTtlSpnColor); ?></td>
                                <td class="lovtd"><span><?php echo $row2[8]; ?></span></td>
                                <td class="lovtd"><span><?php echo $row2[9]; ?></span></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>&nbsp;</th>
                            <th style="text-align: right;">Net Amount (GHS):</th>
                            <th style="text-align: right;"><?php echo "<span style=\"color:$prpsdTtlSpnColor;\">" . number_format($brghtTotal, 2, '.', ',') . "</span>"; ?></th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <?php if ($payTrnsRqstsType == "LOAN") { ?>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="payTrnsRqstsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                        <caption style="border-width: 1px;padding: 8px;border-style: solid;border-color: #ccc;background-color: #efefef;margin-bottom: 1px;font-weight: bold;font-size: 16px;font-style: italic;color: #333333">PREVIOUS TEN(10) LOANS HISTORY</caption>
                        <thead>
                            <tr>
                                <th style="max-width:40px;width:40px;">No.</th>
                                <th style="max-width:180px;width:180px;">Requestor</th>
                                <th style="max-width:120px;width:120px;">Request Date</th>
                                <th style="max-width:180px;width:180px;">Request Type (Item Type)</th>
                                <th style="max-width:140px;width:140px;">Classification</th>
                                <th>Request Reason </th>
                                <th style="text-align:center;max-width:40px;width:40px;padding:8px 4px !important;">CUR.</th>
                                <th style="text-align:right;max-width:100px;width:100px;">Principal Amount</th>
                                <th style="max-width:165px;width:165px;">Request Status</th>
                                <th style="max-width:165px;width:165px;">Payment Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $curIdx = 0;
                            $lmtSze = 10;
                            $result = get_IndvdlTrnsRqsts("%", "Requestor", 0, 10, $orgID, $payTrnsRqstsPrsnID, $sbmtdPayTrnsRqstsID, $row[3]);
                            //get_TrnsRqstsDocHdr($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qShwUnpstdOnly);
                            $cntr = 0;
                            while ($row = loc_db_fetch_array($result)) {
                                $cntr += 1;
                            ?>
                                <tr id="payTrnsRqstsHdrsRow_<?php echo $cntr; ?>">
                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                    <td class="lovtd"><?php echo $row[2]; ?></td>
                                    <td class="lovtd"><?php echo $row[8]; ?></td>
                                    <td class="lovtd" style="word-wrap: break-word;"><?php echo $row[3] . " (" . $row[5] . ")"; ?></td>
                                    <td class="lovtd"><?php echo $row[6]; ?></td>
                                    <td class="lovtd" style="word-wrap: break-word;"><?php echo $row[7]; ?></td>
                                    <td class="lovtd" style="text-align:center;font-weight: bold;color:black;"><?php echo $fnccurnm; ?></td>
                                    <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                                                                                echo number_format((float) $row[9], 2);
                                                                                                                ?>
                                    </td>
                                    <?php
                                    $style1 = "color:red;";
                                    if ($row[13] == "Approved") {
                                        $style1 = "color:green;";
                                    } else if ($row[13] == "Cancelled") {
                                        $style1 = "color:#0d0d0d;";
                                    }
                                    $style2 = "color:red;";
                                    if ($row[15] == "1") {
                                        $style2 = "color:blue;";
                                    }
                                    $style3 = "color:red;";
                                    if ($row[17] == "PAID") {
                                        $style3 = "color:green;";
                                    }
                                    ?>
                                    <td class="lovtd" style="font-weight:bold;<?php echo $style1; ?>"><?php
                                                                                                        echo $row[13] . " - <span style=\"" . $style2 . "\">" . ($row[15] == "1" ? "Processed" : "Not Processed") . "</span>";
                                                                                                        ?>
                                    </td>
                                    <td class="lovtd" style="font-weight:bold;color:blue;"><?php
                                                                                            echo $row[16] . " - <span style=\"" . $style3 . "\">" . ($row[17]) . "</span>";
                                                                                            ?>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>
    </form>
<?php
}
?>