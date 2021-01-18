<?php
$vPsblValID1 = getEnbldPssblValID("Application Instance SHORT CODE", getLovID("All Other General Setups"));
$vPsblVal1 = getPssblValDesc($vPsblValID1);
$canAdd = true;
$canEdt = true;
$canDel = true;
$canVoid = false;
$canApprove = false;
$canVwRcHstry = false;
$menuItems = array("Summary Financial Info.", "My Loan Requests", "My Payments/Contributions", "My Settlement Requests", "Linked Customer Invoices", "Linked Supplier Invoices");
$menuImages = array("invcBill.png", "person.png", "98-512.png", "invcBill.png", "invcBill.png", "invoice1.png");
$menuLinks = array("grp=80&typ=1&vtyp=0", "grp=80&typ=1&vtyp=10", "grp=80&typ=1&vtyp=11", "grp=80&typ=1&vtyp=12", "grp=80&typ=1&vtyp=2", "grp=80&typ=1&vtyp=4");
$vwtyp1 = 0;
//echo $vwtyp1;
$mdlNm = "Internal Payments";
$ModuleName = $mdlNm;
$pageHtmlID = "prsnDataPage";
$canview = test_prmssns("View Self-Service", "Self Service");

$prsnid = $_SESSION['PRSN_ID'];
$orgID = $_SESSION['ORG_ID'];
$crntOrgName = getOrgName($orgID);
$usrID = $_SESSION['USRID'];
$uName = $_SESSION['UNAME'];

$vwtyp = isset($_POST['vtyp']) ? cleanInputData($_POST['vtyp']) : "999";
$qstr = "";
$dsply = "";
$actyp = "";
$srchFor = "";
$srchIn = "Name";
$PKeyID = -1;
$fltrTypValue = "All";
$fltrTyp = "Relation Type";
$gnrlTrnsDteDMYHMS = getFrmtdDB_Date_time();
$gnrlTrnsDteYMDHMS = cnvrtDMYTmToYMDTm($gnrlTrnsDteDMYHMS);
$gnrlTrnsDteYMD = substr($gnrlTrnsDteYMDHMS, 0, 10);
$gnrlTrnsDteDMY = substr($gnrlTrnsDteDMYHMS, 0, 11);

$fnccurid = getOrgFuncCurID($orgID);
$fnccurnm = getPssblValNm($fnccurid);
if (isset($_POST['PKeyID'])) {
    $PKeyID = cleanInputData($_POST['PKeyID']);
}
if (isset($_POST['searchfor'])) {
    $srchFor = cleanInputData($_POST['searchfor']);
}
if (isset($_POST['searchin'])) {
    $srchIn = cleanInputData($_POST['searchin']);
}
if (isset($_POST['q'])) {
    $qstr = cleanInputData($_POST['q']);
}
if (isset($_POST['actyp'])) {
    $actyp = cleanInputData($_POST['actyp']);
}
if (isset($_POST['fltrTypValue'])) {
    $fltrTypValue = cleanInputData($_POST['fltrTypValue']);
}
if (isset($_POST['fltrTyp'])) {
    $fltrTyp = cleanInputData($_POST['fltrTyp']);
}
if (strpos($srchFor, "%") === FALSE) {
    $srchFor = " " . $srchFor . " ";
    $srchFor = str_replace(" ", "%", $srchFor);
}
$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "ID ASC";
$curIdx = 0;
if (array_key_exists('lgn_num', get_defined_vars())) {
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
                $afftctd += createTrnsRqstsDocHdr($sbmtdPayTrnsRqstsID, $payTrnsRqstsPrsnID, $payTrnsRqstsType, $payTrnsRqstsItmTypID, $payTrnsRqstsDesc, $payTrnsRqstsClsfctn, $payTrnsRqstsAmnt, $hsAgreed, $lnkdPayTrnsRqstsID);
            } else if ($sbmtdPayTrnsRqstsID > 0) {
                $afftctd += updtTrnsRqstsDocHdr($sbmtdPayTrnsRqstsID, $payTrnsRqstsPrsnID, $payTrnsRqstsType, $payTrnsRqstsItmTypID, $payTrnsRqstsDesc, $payTrnsRqstsClsfctn, $payTrnsRqstsAmnt, $hsAgreed, $lnkdPayTrnsRqstsID);
            }
            if ($shdSbmt == 2 && $payTrnsRqstsHsAgreed != "YES") {
                $exitErrMsg .= "<span style=\"font-weight:bold;font-size:14px;font-style:italic;font-family:georgia;\">You have to agree to the terms and conditions first!</span><br/>";
            }
            $payTrnsRqstsNetAmnt = (float) getGnrlRecNm("pay.pay_loan_pymnt_rqsts", "pay_request_id", "net_loan_amount", $sbmtdPayTrnsRqstsID);
            if ($shdSbmt == 2 && $payTrnsRqstsNetAmnt <= 0) {
                $exitErrMsg .= "<span style=\"font-weight:bold;font-size:14px;font-style:italic;font-family:georgia;\">Net Amount to Credit cannot be zero or less!</span><br/>";
            }
            $payTrnsRqstsMinAmnt = (float) getGnrlRecNm("pay.pay_loan_pymnt_rqsts", "pay_request_id", "min_loan_amount", $sbmtdPayTrnsRqstsID);
            if ($shdSbmt == 2 && $payTrnsRqstsAmnt < $payTrnsRqstsMinAmnt) {
                $exitErrMsg .= "<span style=\"font-weight:bold;font-size:14px;font-style:italic;font-family:georgia;\">Request Amount (" . number_format($payTrnsRqstsAmnt, 2) . ") cannot be below Min Amount Allowed (" . number_format($payTrnsRqstsMinAmnt, 2) . ")!</span><br/>";
            }
            $payTrnsRqstsEnfrcMx = getGnrlRecNm("pay.pay_loan_pymnt_rqsts", "pay_request_id", "enforce_max_amnt", $sbmtdPayTrnsRqstsID);
            $payTrnsRqstsMaxAmnt = (float) getGnrlRecNm("pay.pay_loan_pymnt_rqsts", "pay_request_id", "max_loan_amount", $sbmtdPayTrnsRqstsID);
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
    } else if ($vwtyp == "999") {
?>
        <div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">My Financials</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:openATab('#allmodules', 'grp=42&typ=1');">All Apps</a></li>
                            <li class="breadcrumb-item active">My Financials</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content" style="padding: 10px 5px 10px 5px !important;">
            <div class="container-fluid">
                <?php
                $grpcntr = 0;
                $cntent = "";
                for ($i = 0; $i < count($menuItems); $i++) {
                    $No = $i + 1;
                    if ($i == 1 && test_prmssns("View Internal Pay Loan Requests", "Self Service") == FALSE) {
                        continue;
                    } else if ($i == 2 && test_prmssns("View Internal Pay Settlement Requests", "Self Service") == FALSE) {
                        continue;
                    } else if ($i == 3 && test_prmssns("View Internal Pay Payment Requests", "Self Service") == FALSE) {
                        continue;
                    } else if ($i == 4 && test_prmssns("View Receivables Invoices", "Self Service") == FALSE) {
                        continue;
                    } else if ($i == 5 && test_prmssns("View Payables Invoices", "Self Service") == FALSE) {
                        continue;
                    }
                    if ($grpcntr == 0) {
                        $cntent .= "<div class=\"row\">";
                    }
                    $cntent .= "<div class=\"col-md-3 colmd3special2\">
        <button type=\"button\" class=\"btn btn-default btn-lg btn-block modulesButton\" onclick=\"openATab('#allmodules', '" . $menuLinks[$i] . "');\">
            <img src=\"../cmn_images/" . $menuImages[$i] . "\" style=\"margin:5px; padding-right: 1em; height:58px; width:auto; position: relative; vertical-align: middle;float:left;\">
            <span class=\"wordwrap2\">" . ($menuItems[$i]) . "</span>
        </button>
            </div>";

                    if ($grpcntr == 3) {
                        $cntent .= "</div>";
                        $grpcntr = 0;
                    } else {
                        $grpcntr = $grpcntr + 1;
                    }
                }
                echo $cntent;
                ?>
            </div>
        </section>
        <?php
    } else if ($vwtyp == 0 || $vwtyp == 1) {
        $fnlColorAmntDffrnc = 0;
        $sbmtdMspyID = isset($_POST['sbmtdMspyID']) ? (float) $_POST['sbmtdMspyID'] : -1;
        $sbmtdPyReqID = isset($_POST['sbmtdPyReqID']) ? (float) $_POST['sbmtdPyReqID'] : -1;
        $sbmtdPrsnSetMmbrID = isset($_POST['sbmtdPrsnSetMmbrID']) ? (float) $_POST['sbmtdPrsnSetMmbrID'] : -1;

        $pkID = $sbmtdMspyID;
        $total = get_MyPyRnsTtl($srchFor, $srchIn, $prsnid);
        if ($pageNo > ceil($total / $lmtSze)) {
            $pageNo = 1;
        } else if ($pageNo < 1) {
            $pageNo = ceil($total / $lmtSze);
        }

        $curIdx = $pageNo - 1;
        $result = get_MyPyRnsTblr($srchFor, $srchIn, $curIdx, $lmtSze, $prsnid);
        $cntr = 0;
        $colClassType1 = "col-lg-2";
        $colClassType2 = "col-lg-3";
        $colClassType3 = "col-lg-4";
        if ($vwtyp == 0) {
        ?>
            <style type="text/css">
                table {
                    border-collapse: separate;
                    border: solid #ddd 1px;
                    border-radius: 6px;
                    -moz-border-radius: 6px;
                }

                td {
                    border-left: solid #ddd 1px;
                    border-top: solid #ddd 1px;
                }

                th {
                    border-left: solid #ddd 1px;
                    border-top: none !important;
                }

                td:first-child,
                th:first-child {
                    border-left: none;
                }
            </style>
            <div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">My Summary Info.</h1>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="javascript:openATab('#allmodules', 'grp=42&typ=1');">All Apps</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:openATab('#allmodules', 'grp=80&typ=1');">My Financials</a></li>
                                <li class="breadcrumb-item active">My Summary Info.</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->
            <section class="content" style="padding: 10px 5px 10px 5px !important;">
                <div class="container-fluid">
                    <form id='myPayRnsForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row">
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <input class="form-control" id="myPayRnsSrchFor" type="text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncMyPayRns(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <input id="myPayRnsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                    <div class="input-group-append handCursor" onclick="getMyPayRns('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                        <span class="input-group-text rhoclickable"><i class="fas fa-times"></i></span>
                                    </div>
                                    <div class="input-group-append handCursor" onclick="getMyPayRns('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                        <span class="input-group-text rhoclickable"><i class="fas fa-search"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType3; ?>">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-filter"></i></span>
                                    </div>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="myPayRnsSrchIn">
                                        <?php
                                        $valslctdArry = array("");
                                        $srchInsArrys = array("Name/Number");

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                        ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="myPayRnsDsplySze" style="min-width:70px !important;">
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
                                            <a class="rhopagination" href="javascript:getMyPayRns('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getMyPayRns('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row" style="padding-top:5px !important">
                            <div class="col-md-3" style="padding:0px 1px 0px 7.5px !important">
                                <table class="table table-hover table-striped handCursor" id="myPayRnsTable" cellspacing="0" width="100%" style="border:1px solid #ddd;width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Batch Name / Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            if ($pkID <= 0 && $cntr <= 0) {
                                                $pkID = $row[1];
                                                $sbmtdMspyID = $pkID;
                                                $sbmtdPyReqID = $row[0];
                                            }
                                            $cntr += 1;
                                        ?>
                                            <tr id="myPayRnsRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                <td><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td>
                                                    <?php echo $row[2]; ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="myPayRnsRow<?php echo $cntr; ?>_PyReqID" value="<?php echo $row[0]; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="myPayRnsRow<?php echo $cntr; ?>_MspyID" value="<?php echo $row[1]; ?>">
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-9" style="padding:0px 1px 0px 1px !important">
                                <div class="container-fluid" id="myPayRnsDetailInfo">
                                <?php
                            }
                            $locIDNo = getPersonLocID($prsnid);
                            $reportName5 = "Customized Pay Slip (Sample 1)-Per Run";
                            $reportTitle5 = "Pay Slip";
                            $rptID5 = getRptID($reportName5);
                            $prmID501 = getParamIDUseSQLRep("{:IDNos}", $rptID5);
                            $prmID502 = getParamIDUseSQLRep("{:documentTitle}", $rptID5);
                            $prmID503 = getParamIDUseSQLRep("{:orgID}", $rptID5);
                            $prmID504 = getParamIDUseSQLRep("{:pay_run_id}", $rptID5);
                            $paramRepsNVals5 = $prmID501 . "~'" . $locIDNo . "'|" . $prmID502 . "~" . $reportTitle5 . "|" . $prmID503 . "~" . $orgID . "|" . $prmID504 . "~" . $sbmtdMspyID . "|-130~" . $reportTitle5 . "|-190~PDF";
                            $paramStr5 = urlencode($paramRepsNVals5);

                            if ($sbmtdPrsnSetMmbrID <= 0) {
                                $sbmtdPrsnSetMmbrID = $prsnid;
                            }
                            $result1 = get_MyPyHdrDet($sbmtdPyReqID, $sbmtdMspyID, $prsnid);
                            while ($row1 = loc_db_fetch_array($result1)) {
                                ?>
                                    <div class="row" style="padding-top:10px !important;border:1px solid #ddd;border-radius:5px;">
                                        <div class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                            <div class="form-group col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                <label for="myPayRnNm" class="control-label col-lg-4">Batch Name:</label>
                                                <div class="col-lg-8">
                                                    <span><?php echo $row1[2]; ?></span>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                <label for="myPayRnDesc" class="control-label col-lg-4">Batch Description:</label>
                                                <div class="col-lg-8">
                                                    <span><?php echo $row1[3]; ?></span>
                                                </div>
                                            </div>
                                            <?php if ($row1[2] != "CUMULATIVE BALANCES" && 1 > 2) { ?>
                                                <div class="form-group col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="myPayRnDesc" class="control-label col-lg-4">Print Slips:</label>
                                                    <div class="col-lg-8">
                                                        <button type="button" class="btn btn-default" style="" onclick="getSilentRptsRnSts(<?php echo $rptID5; ?>, -1, '<?php echo $paramStr5; ?>');" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title="Pay Run Results Slip">
                                                            <img src="../cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:25px; width:auto; position: relative; vertical-align: middle;">&nbsp;&nbsp;Results Slip&nbsp;&nbsp;&nbsp;
                                                        </button>
                                                    </div>
                                                </div>
                                            <?php } else if (1 > 2) { ?>
                                                <div class="form-group col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="myPayRnDesc" class="control-label col-lg-4">Details:</label>
                                                    <div class="col-lg-8">
                                                        <button type="button" class="btn btn-default" style="" onclick="openATab('#allmodules', 'grp=80&typ=1&vtyp=11&myBalsDetItmStID=-1');" style="width:100% !important;" title="View Balance Details">
                                                            <img src="../cmn_images/kghostview.png" style="left: 0.5%; padding-right: 1px; height:25px; width:auto; position: relative; vertical-align: middle;">&nbsp;&nbsp;View Balance Details&nbsp;&nbsp;&nbsp;
                                                        </button>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                            <div class="form-group col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                <label for="myPayRnDate" class="control-label col-lg-5">Date:</label>
                                                <div class="col-lg-7">
                                                    <span><?php echo $row1[6]; ?></span>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                <label for="myPayRnDesc" class="control-label col-lg-5">Batch Status:</label>
                                                <div class="col-lg-7">
                                                    <span><?php echo $row1[5]; ?></span>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                <label for="myPayRnNetAmnt" class="control-label col-lg-5" style="color:blue;">Net Amount (GHS):</label>
                                                <div class="col-lg-7">
                                                    <span id="myPyRnTotal1"><?php echo "0.00"; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                            <?php
                                            $hideTable = "";
                                            if ($vPsblVal1 == "NARHBT_COLLEGE_APP_1" && !($sbmtdMspyID <= 0 && $sbmtdPyReqID <= 0)) {
                                                $hideTable = "hideNotice";
                                            }
                                            ?>
                                            <table class="table table-striped table-bordered dataTable table-hover <?php echo  $hideTable ?>" id="myPayRnLinesTable" cellspacing="0" width="100%" style="border:1px solid #ddd;width:100%;">
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
                                                    $result2 = null;
                                                    if ($sbmtdMspyID <= 0 && $sbmtdPyReqID <= 0) {
                                                        $result2 = get_CumltiveBals($prsnid);
                                                    } else {
                                                        $result2 = get_MyPyRnsDt($sbmtdPyReqID, $sbmtdMspyID, $prsnid);
                                                    }
                                                    $cntr = 0;
                                                    $curIdx = 0;
                                                    $brghtTotal = 0;
                                                    $prpsdTtlSpnColor = "black";
                                                    $itmTypCnt = getBatchItmTypCnt($sbmtdPyReqID, $sbmtdMspyID, $prsnid);
                                                    while ($row2 = loc_db_fetch_array($result2)) {
                                                        $cntr += 1;
                                                        $dsplyAmount = getBatchNetAmnt($itmTypCnt, $row2[11], $row2[6], $row2[10], $row2[7], $brghtTotal, $prpsdTtlSpnColor);
                                                        //getBatchNetAmnt($itmTypCnt, $row2[11], $row2[6], $row2[10], $row2[7], $brghtTotal, $prpsdTtlSpnColor)                                                                     
                                                        if ($vPsblVal1 == "NARHBT_COLLEGE_APP_1" && $row1[2] != "CUMULATIVE BALANCES") {
                                                            continue;
                                                        }
                                                    ?>
                                                        <tr id="myPayRnLinesRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                            <?php if ($row1[2] != "CUMULATIVE BALANCES") { ?>
                                                                <td class="lovtd"><?php echo $row2[6]; ?></td>
                                                                <td class="lovtd" style="text-align: right;"><?php echo $dsplyAmount; ?></td>
                                                            <?php } else { ?>
                                                                <td class="lovtd"><a class="nav-link" href="javascript:openATab('#allmodules', 'grp=80&typ=1&vtyp=11&myBalsDetItmStID=<?php echo $row2[5]; ?>');"><?php echo $row2[6]; ?></a></td>
                                                                <td class="lovtd" style="text-align: right;"><a class="nav-link" href="javascript:openATab('#allmodules', 'grp=80&typ=1&vtyp=11&myBalsDetItmStID=<?php echo $row2[5]; ?>');"><?php echo $dsplyAmount; ?></a></td>
                                                            <?php } ?>
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
                                                        <th>
                                                            <?php
                                                            if ($row1[2] != "CUMULATIVE BALANCES") {
                                                                if ($vPsblVal1 == "TAKBG_SWLFR_APP_1") {
                                                                    echo "";
                                                                } else {
                                                            ?>
                                                                    <button type="button" class="btn btn-default" style="" onclick="getSilentRptsRnSts(<?php echo $rptID5; ?>, -1, '<?php echo $paramStr5; ?>');" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title="Pay Run Results Slip">
                                                                        <img src="../cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:25px; width:auto; position: relative; vertical-align: middle;">&nbsp;&nbsp;Results Slip&nbsp;&nbsp;&nbsp;
                                                                    </button>
                                                                <?php
                                                                }
                                                            } else {
                                                                ?>
                                                                <button type="button" class="btn btn-default" style="" onclick="openATab('#allmodules', 'grp=80&typ=1&vtyp=11&myBalsDetItmStID=-1');" style="width:100% !important;" title="View Balance Details">
                                                                    <img src="../cmn_images/kghostview.png" style="left: 0.5%; padding-right: 1px; height:25px; width:auto; position: relative; vertical-align: middle;">&nbsp;&nbsp;View Balance Details&nbsp;&nbsp;&nbsp;
                                                                </button>
                                                            <?php } ?>
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <div><input type="hidden" id="myPyRnTotal2" value="<?php echo urlencode("<span style=\"color:$prpsdTtlSpnColor;font-weight:bold;font-size:12px;\">" . number_format($brghtTotal, 2, '.', ',') . "</span>"); ?>"></div>
                                            <script type="text/javascript">
                                                $(document).ready(function() {
                                                    $("#myPyRnTotal1").html('<?php echo "<span style=\"color:$prpsdTtlSpnColor;font-weight:bold;font-size:12px;\">" . number_format($brghtTotal, 2, '.', ',') . "</span>"; ?>');
                                                });
                                            </script>
                                        </div>
                                    </div>
                                <?php
                            }
                            if ($vwtyp == 0) {
                                ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        <?php
                            }
                        } else if ($vwtyp == 2 || $vwtyp == 3) {
                            //My Receivable Invoices
                            $cstmrID = getGnrlRecNm("scm.scm_cstmr_suplr", "lnkd_prsn_id", "cust_sup_id", $prsnid);
                            $cstmrID1 = getGnrlRecNm("sec.sec_users", "user_id", "customer_id", $usrID);
                            if ($cstmrID == "") {
                                $cstmrID = -1234567;
                            }
                            if ($cstmrID1 == "" || $cstmrID1 == -1) {
                                $cstmrID1 = -1234567;
                            }
                            $cstmrID = $cstmrID . "," . $cstmrID1;
                            $sbmtdRcvblInvcID = isset($_POST['sbmtdRcvblInvcID']) ? $_POST['sbmtdRcvblInvcID'] : -1;
                            $sbmtdSalesInvcID = isset($_POST['sbmtdSalesInvcID']) ? $_POST['sbmtdSalesInvcID'] : -1;
                            $shwUnpstdOnly = FALSE;
                            $total = get_RcvblsDocHdrTtl2($srchFor, $srchIn, $orgID, $shwUnpstdOnly, $cstmrID);
                            if ($pageNo > ceil($total / $lmtSze)) {
                                $pageNo = 1;
                            } else if ($pageNo < 1) {
                                $pageNo = ceil($total / $lmtSze);
                            }

                            $curIdx = $pageNo - 1;
                            $result = get_RcvblsDocHdr2($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $shwUnpstdOnly, $cstmrID);
                            $cntr = 0;
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-5";
                            $colClassType3 = "col-lg-5";
                            if ($vwtyp == 2) {
        ?>
            <div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">My Receivable Invoices</h1>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="javascript:openATab('#allmodules', 'grp=42&typ=1');">All Apps</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:openATab('#allmodules', 'grp=80&typ=1');">My Financials</a></li>
                                <li class="breadcrumb-item active">My Receivable Invoices</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->
            <section class="content" style="padding: 10px 5px 10px 5px !important;">
                <div class="container-fluid">
                    <form id='myRcvblInvcsForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row">
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="myRcvblInvcsSrchFor" type="text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncMyRcvblInvcs(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <input id="myRcvblInvcsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                    <div class="input-group-append handCursor" onclick="getMyRcvblInvcs('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                        <span class="input-group-text rhoclickable"><i class="fas fa-times"></i></span>
                                    </div>
                                    <div class="input-group-append handCursor" onclick="getMyRcvblInvcs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                        <span class="input-group-text rhoclickable"><i class="fas fa-search"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType3; ?>">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-filter"></i></span>
                                    </div>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="myRcvblInvcsSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "", "", "");
                                        $srchInsArrys = array(
                                            "Document Number", "Document Description",
                                            "Document Classification", "Customer Name", "Customer's Doc. Number",
                                            "Source Doc Number", "Currency", "Approval Status"
                                        );

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                        ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="myRcvblInvcsDsplySze" style="min-width:70px !important;">
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
                                            <a class="rhopagination" href="javascript:getMyRcvblInvcs('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getMyRcvblInvcs('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row" style="padding:0px 15px 0px 15px !important">
                            <div class="col-md-2" style="padding:0px 1px 0px 1px !important">
                                <table class="table table-striped table-bordered" id="myRcvblInvcsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Document Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            if ($sbmtdRcvblInvcID <= 0 && $cntr <= 0) {
                                                $sbmtdRcvblInvcID = $row[0];
                                                if ($row[6] == "Sales Invoice") {
                                                    $sbmtdSalesInvcID = $row[5];
                                                } else {
                                                    $sbmtdSalesInvcID = 1;
                                                }
                                            }
                                            $cntr += 1;
                                            $spnColor4 = "";
                                            if ((float) $row[3] <= 0 && $row[4] == "Approved") {
                                                $spnColor4 = "lime";
                                            } else if ($row[4] == "Approved") {
                                                $spnColor4 = "#FF9191";
                                            } else if ($row[4] == "Cancelled") {
                                                $spnColor4 = "#ABABAB";
                                            } else if ($row[4] == "Not Validated") {
                                                $spnColor4 = "#e5a110";
                                            } else {
                                                $spnColor4 = "#44d6d6";
                                            }
                                        ?>
                                            <tr id="myRcvblInvcsRow_<?php echo $cntr; ?>" class="hand_cursor" style="background-color:<?php echo $spnColor4; ?>">
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd"><?php echo str_pad($row[0], 7, "0", STR_PAD_LEFT); ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="myRcvblInvcsRow<?php echo $cntr; ?>_RcvblID" value="<?php echo $row[0]; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="myRcvblInvcsRow<?php echo $cntr; ?>_SalesID" value="<?php echo ($row[6] == "Sales Invoice" ? $row[5] : -1); ?>">
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-10" style="padding:0px 1px 0px 1px !important">
                                <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                    <div class="container-fluid" id="myRcvblInvcsDetailInfo">
                                        <?php
                                    }
                                    if ($sbmtdRcvblInvcID > 0) {
                                        $result1 = get_One_RcvblsDocHdr($sbmtdRcvblInvcID);
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $curr = $row1[25];
                                            $salesDocTyp = $row1[8];
                                            $rcvblDocTyp = $row1[5];
                                        ?>
                                            <div class="row" style="padding-top:10px !important;">
                                                <div class="col-md-4" style="padding:0px 3px 0px 3px !important;">
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="myRcvblInvcDocTyp" class="control-label col-lg-4 formtd">Doc. Type:</label>
                                                        <div class="col-lg-8 formtd">
                                                            <span><?php echo $row1[5]; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="myRcvblInvcNum" class="control-label col-lg-4 formtd">Number:</label>
                                                        <div class="col-lg-8 formtd">
                                                            <span><?php echo $row1[4] . " (" . $row1[0] . ")"; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="myRcvblInvcDate" class="control-label col-lg-4 formtd">Date:</label>
                                                        <div class="col-lg-8 formtd">
                                                            <span><?php echo $row1[1]; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="myRcvblInvcCstmr" class="control-label col-lg-4 formtd">Customer:</label>
                                                        <div class="col-lg-8 formtd">
                                                            <span><?php echo $row1[10]; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="myRcvblInvcCstmrSite" class="control-label col-lg-4 formtd">Site:</label>
                                                        <div class="col-lg-8 formtd">
                                                            <span><?php echo $row1[12]; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="myRcvblInvcLnkdEvnt" class="control-label col-lg-4 formtd">Linked Event:</label>
                                                        <div class="col-lg-8 formtd">
                                                            <span><?php echo ($row1[31]); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="myRcvblInvcEvntCtgry" class="control-label col-lg-4 formtd">Category:</label>
                                                        <div class="col-lg-8 formtd">
                                                            <span><?php echo $row1[28]; ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4" style="padding:0px 3px 0px 3px !important;">
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="myRcvblInvcCstmrDoc" class="control-label col-lg-5 formtd">Client's Doc. No.:</label>
                                                        <div class="col-lg-7 formtd">
                                                            <span><?php echo $row1[22]; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="myRcvblInvcPayMthd" class="control-label col-lg-5 formtd">Pay Method:</label>
                                                        <div class="col-lg-7 formtd">
                                                            <span><?php echo $row1[18]; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="myRcvblInvcPayTrms" class="control-label col-lg-5 formtd">Pay Terms:</label>
                                                        <div class="col-lg-7 formtd">
                                                            <span><?php echo $row1[16]; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="myRcvblInvcSrcDocTyp" class="control-label col-lg-5 formtd">Source Doc. Type:</label>
                                                        <div class="col-lg-7 formtd">
                                                            <span><?php echo $row1[8]; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="myRcvblInvcSrcDocNum" class="control-label col-lg-5 formtd">Source Doc. No.:</label>
                                                        <div class="col-lg-7 formtd">
                                                            <span><?php echo $row1[26]; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="myRcvblInvcGLBatch" class="control-label col-lg-5 formtd">GL Batch:</label>
                                                        <div class="col-lg-7 formtd">
                                                            <span><?php echo $row1[21]; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="myRcvblInvcCrtdBy" class="control-label col-lg-5 formtd">Created By:</label>
                                                        <div class="col-lg-7 formtd">
                                                            <span><?php echo $row1[3]; ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4" style="padding:0px 3px 0px 3px !important;">
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="myRcvblInvcTtl" class="control-label col-lg-7 formtd">Invoice Total:</label>
                                                        <div class="col-lg-5 formtd">
                                                            <span><?php echo number_format($row1[15], 2, '.', ','); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="myRcvblInvcAmntPaid" class="control-label col-lg-7 formtd" style="color:blue;">Amount Paid:</label>
                                                        <div class="col-lg-5 formtd">
                                                            <span><?php echo number_format($row1[19], 2, '.', ','); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="myRcvblInvcNetAmnt" class="control-label col-lg-7 formtd" style="color:blue;">Outstanding Amount:</label>
                                                        <div class="col-lg-5 formtd">
                                                            <?php
                                                            $netAmnt = (float) $row1[15] - (float) $row1[19];
                                                            $spnColor = (round($netAmnt, 2) <= 0) ? "lime" : "#FF9191";
                                                            ?>
                                                            <span style="padding:5px;background-color:<?php echo $spnColor; ?>;"><?php echo number_format($netAmnt, 2, '.', ','); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="myRcvblInvcAvlblPrpyAmnt" class="control-label col-lg-7 formtd" style="color:blue;">Un-Applied Amount:</label>
                                                        <div class="col-lg-5 formtd">
                                                            <?php
                                                            $netAmnt1 = 0;
                                                            $spnColor1 = "";
                                                            if ($row1[5] == "Customer Advance Payment") {
                                                                $netAmnt1 = (float) $row1[19] - (float) $row1[30];
                                                                $spnColor1 = (round($netAmnt1, 2) <= 0) ? "lime" : "#FF9191";
                                                            }
                                                            ?>
                                                            <span style="padding:5px;background-color:<?php echo $spnColor1; ?>;"><?php echo number_format(0, 2, '.', ','); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="myRcvblInvcCurr" class="control-label col-lg-7 formtd">Currency:</label>
                                                        <div class="col-lg-5 formtd">
                                                            <span><?php echo $row1[25]; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="myRcvblInvcClsfctn" class="control-label col-lg-4 formtd">Doc. Classification:</label>
                                                        <div class="col-lg-8 formtd">
                                                            <span><?php echo $row1[23]; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="myRcvblInvcStatus" class="control-label col-lg-7 formtd">Doc. Status:</label>
                                                        <div class="col-lg-5 formtd">
                                                            <?php
                                                            $spnColor2 = "";
                                                            if ($row1[13] == "Approved") {
                                                                $spnColor2 = "lime";
                                                            } else if ($row1[13] == "Cancelled") {
                                                                $spnColor2 = "#FF9191";
                                                            } else if ($row1[13] == "Not Validated") {
                                                                $spnColor2 = "#e5a110";
                                                            } else {
                                                                $spnColor2 = "#44d6d6";
                                                            }
                                                            ?>
                                                            <span style="padding:5px;font-weight:bold;background-color:<?php echo $spnColor2; ?>;"><?php echo $row1[13]; ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12" style="padding:2px !important;">
                                                    <div class="form-group form-group-sm" style="padding:0px 5px 0px 5px !important;">
                                                        <label for="myRcvblInvcDesc" class="control-label col-lg-2 formtd">Description:</label>
                                                        <div class="col-lg-10 formtd">
                                                            <span><?php echo $row1[6]; ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-9" style="padding:0px 2px 0px 0px !important;">
                                                    <table class="table table-striped table-bordered" id="myRcvblInvcLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 500px;">
                                                        <thead>
                                                            <tr>
                                                                <th>No.</th>
                                                                <?php if ($sbmtdSalesInvcID <= 0) { ?>
                                                                    <th>Item Type</th>
                                                                <?php } ?>
                                                                <th>Item Description</th>
                                                                <?php if ($sbmtdSalesInvcID > 0) { ?>
                                                                    <th style="text-align: right;">Qty</th>
                                                                    <th>UOM</th>
                                                                    <th style="text-align: right;">Unit Price (<?php echo $curr; ?>)</th>
                                                                <?php } ?>
                                                                <th style="text-align: right;">Amount (<?php echo $curr; ?>)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $result2 = NULL;
                                                            if ($sbmtdSalesInvcID > 0) {
                                                                $result2 = get_One_SalesDcLines($sbmtdSalesInvcID);
                                                            } else if ($sbmtdRcvblInvcID > 0) {
                                                                $result2 = get_RcvblsDocLines($sbmtdRcvblInvcID);
                                                            }
                                                            $cntr = 0;
                                                            $curIdx = 0;
                                                            $brghtTotal = 0;
                                                            if ($result2 !== NULL) {
                                                                while ($row2 = loc_db_fetch_array($result2)) {
                                                                    $cntr += 1;
                                                                    $itemType = "";
                                                                    $itemDesc = "";
                                                                    $itmQty = 0;
                                                                    $itmUom = 0;
                                                                    $itmUntPrc = 0;
                                                                    $itmAmount = 0;
                                                                    $lnkdPrsnID = 0;
                                                                    $lnkdPrsnNm = "";
                                                                    if ($sbmtdSalesInvcID > 0) {
                                                                        $itemType = "";
                                                                        $itemDesc = $row2[25];
                                                                        $lnkdPrsnID = (float) $row2[23];
                                                                        if ($lnkdPrsnID > 0) {
                                                                            $lnkdPrsnNm = " for " . $row2[24];
                                                                        }
                                                                        $itmQty = number_format($row2[2], 2, '.', ',');
                                                                        $itmUom = $row2[18];
                                                                        $itmUntPrc = number_format($row2[3], 2, '.', ',');
                                                                        $itmAmount = number_format($row2[4], 2, '.', ',');
                                                                    } else if ($sbmtdRcvblInvcID > 0) {
                                                                        $itemType = $row2[1];
                                                                        $itemDesc = $row2[2];
                                                                        $itmQty = 0;
                                                                        $itmUom = 0;
                                                                        $itmUntPrc = 0;
                                                                        $itmAmount = number_format($row2[3], 2, '.', ',');
                                                                    }
                                                            ?>
                                                                    <tr id="myRcvblInvcLinesRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                                        <?php if ($sbmtdSalesInvcID <= 0) { ?>
                                                                            <td class="lovtd"><?php echo $itemType; ?></td>
                                                                        <?php } ?>
                                                                        <td class="lovtd"><span><?php echo $itemDesc . $lnkdPrsnNm; ?></span></td>
                                                                        <?php if ($sbmtdSalesInvcID > 0) { ?>
                                                                            <td class="lovtd" style="text-align: right;"><?php echo $itmQty; ?></td>
                                                                            <td class="lovtd"><span><?php echo $itmUom; ?></span></td>
                                                                            <td class="lovtd" style="text-align: right;"><?php echo $itmUntPrc; ?></td>
                                                                        <?php } ?>
                                                                        <td class="lovtd" style="text-align: right;"><span><?php echo $itmAmount; ?></span></td>
                                                                    </tr>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-3" style="padding:0px 0px 0px 2px !important;">
                                                    <table class="table table-striped table-bordered" id="myRcvblInvcSmryTable" cellspacing="0" width="100%" style="width:100%;min-width: 100px;">
                                                        <thead>
                                                            <tr>
                                                                <th>No.</th>
                                                                <th>Summary Item</th>
                                                                <th style="text-align: right;">Amount (<?php echo $curr; ?>)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $result3 = NULL;
                                                            if ($sbmtdSalesInvcID > 0) {
                                                                $result3 = get_SalesDocSmryLns($sbmtdSalesInvcID, $salesDocTyp);
                                                            } else if ($sbmtdRcvblInvcID > 0) {
                                                                $result3 = get_RcvblsDocSmryLns($sbmtdRcvblInvcID, $rcvblDocTyp);
                                                            }
                                                            $cntr = 0;
                                                            $curIdx = 0;
                                                            if ($result3 !== NULL) {
                                                                while ($row3 = loc_db_fetch_array($result3)) {
                                                                    $cntr += 1;
                                                                    $itemDesc = $row3[1];
                                                                    $itmAmount = number_format($row3[2], 2, '.', ',');
                                                                    $spnColor3 = "";
                                                                    if ($itemDesc == "Outstanding Balance" && (float) $row3[2] <= 0) {
                                                                        $spnColor3 = "background-color:lime;";
                                                                    } else if ($itemDesc == "Outstanding Balance") {
                                                                        $spnColor3 = "background-color:#FF9191;";
                                                                    }
                                                            ?>
                                                                    <tr id="myRcvblInvcLinesRow_<?php echo $cntr; ?>" class="hand_cursor" style="<?php echo $spnColor3; ?>">
                                                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                                        <td class="lovtd">
                                                                            <span><?php echo $itemDesc; ?></span>
                                                                        </td>
                                                                        <td class="lovtd" style="text-align: right;"><span><?php echo $itmAmount; ?></span></td>
                                                                    </tr>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <span>No Results Found</span>
                                    <?php }
                                    if ($vwtyp == 2) { ?>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        <?php
                                    }
                                } else if ($vwtyp == 4 || $vwtyp == 5) {
                                    //My Payable Invoices
                                    $cstmrID = getGnrlRecNm("scm.scm_cstmr_suplr", "lnkd_prsn_id", "cust_sup_id", $prsnid);
                                    $cstmrID1 = getGnrlRecNm("sec.sec_users", "user_id", "customer_id", $usrID);
                                    if ($cstmrID == "") {
                                        $cstmrID = -1234567;
                                    }
                                    if ($cstmrID1 == "" || $cstmrID1 == -1) {
                                        $cstmrID1 = -1234567;
                                    }
                                    $cstmrID = $cstmrID . "," . $cstmrID1;
                                    $sbmtdPyblInvcID = isset($_POST['sbmtdPyblInvcID']) ? $_POST['sbmtdPyblInvcID'] : -1;
                                    $shwUnpstdOnly = FALSE;
                                    $total = get_PyblsDocHdrTtl2($srchFor, $srchIn, $orgID, $shwUnpstdOnly, $cstmrID);
                                    if ($pageNo > ceil($total / $lmtSze)) {
                                        $pageNo = 1;
                                    } else if ($pageNo < 1) {
                                        $pageNo = ceil($total / $lmtSze);
                                    }

                                    $curIdx = $pageNo - 1;
                                    $result = get_PyblsDocHdr2($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $shwUnpstdOnly, $cstmrID);
                                    $cntr = 0;
                                    $colClassType1 = "col-lg-2";
                                    $colClassType2 = "col-lg-5";
                                    $colClassType3 = "col-lg-5";
                                    if ($vwtyp == 4) {
        ?>
            <div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">My Payable Invoices</h1>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="javascript:openATab('#allmodules', 'grp=42&typ=1');">All Apps</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:openATab('#allmodules', 'grp=80&typ=1');">My Financials</a></li>
                                <li class="breadcrumb-item active">My Payable Invoices</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->
            <section class="content" style="padding: 10px 5px 10px 5px !important;">
                <div class="container-fluid">
                    <form id='myPyblInvcsForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row">
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="myPyblInvcsSrchFor" type="text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncMyPyblInvcs(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <input id="myPyblInvcsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                    <div class="input-group-append handCursor" onclick="getMyPyblInvcs('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                        <span class="input-group-text rhoclickable"><i class="fas fa-times"></i></span>
                                    </div>
                                    <div class="input-group-append handCursor" onclick="getMyPyblInvcs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                        <span class="input-group-text rhoclickable"><i class="fas fa-search"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType3; ?>">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-filter"></i></span>
                                    </div>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="myPyblInvcsSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "", "", "");
                                        $srchInsArrys = array(
                                            "Document Number", "Document Description",
                                            "Document Classification", "Supplier Name", "Supplier's Invoice Number",
                                            "Source Doc Number", "Currency", "Approval Status"
                                        );

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                        ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="myPyblInvcsDsplySze" style="min-width:70px !important;">
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
                                            <a class="rhopagination" href="javascript:getMyPyblInvcs('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getMyPyblInvcs('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row" style="padding:0px 15px 0px 15px !important">
                            <div class="col-md-2" style="padding:0px 1px 0px 1px !important">
                                <table class="table table-striped table-bordered" id="myPyblInvcsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Document Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            if ($sbmtdPyblInvcID <= 0 && $cntr <= 0) {
                                                $sbmtdPyblInvcID = $row[0];
                                            }
                                            $cntr += 1;
                                            $spnColor4 = "";
                                            if ((float) $row[3] <= 0 && $row[4] == "Approved") {
                                                $spnColor4 = "lime";
                                            } else if ($row[4] == "Approved") {
                                                $spnColor4 = "#FF9191";
                                            } else if ($row[4] == "Cancelled") {
                                                $spnColor4 = "#ABABAB";
                                            } else if ($row[4] == "Not Validated") {
                                                $spnColor4 = "#e5a110";
                                            } else {
                                                $spnColor4 = "#44d6d6";
                                            }
                                        ?>
                                            <tr id="myPyblInvcsRow_<?php echo $cntr; ?>" class="hand_cursor" style="background-color:<?php echo $spnColor4; ?>">
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd"><?php echo str_pad($row[0], 7, "0", STR_PAD_LEFT); ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="myPyblInvcsRow<?php echo $cntr; ?>_PyblID" value="<?php echo $row[0]; ?>">
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-10" style="padding:0px 1px 0px 1px !important;padding-top:10px !important;">
                                <div class="container-fluid" id="myPyblInvcsDetailInfo">
                                    <?php
                                    }
                                    if ($sbmtdPyblInvcID > 0) {
                                        $result1 = get_One_PyblsDocHdr($sbmtdPyblInvcID);
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $curr = $row1[25];
                                            $pyblDocTyp = $row1[5];
                                    ?>
                                        <div class="row" style="padding-top:10px !important;">
                                            <div class="col-md-4" style="padding:0px 3px 0px 3px !important;">
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="myPyblInvcDocTyp" class="control-label col-lg-4 formtd">Doc. Type:</label>
                                                    <div class="col-lg-8 formtd">
                                                        <span><?php echo $row1[5]; ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="myPyblInvcNum" class="control-label col-lg-4 formtd">Number:</label>
                                                    <div class="col-lg-8 formtd">
                                                        <span><?php echo $row1[4] . " (" . $row1[0] . ")"; ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="myPyblInvcDate" class="control-label col-lg-4 formtd">Date:</label>
                                                    <div class="col-lg-8 formtd">
                                                        <span><?php echo $row1[1]; ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="myPyblInvcCstmr" class="control-label col-lg-4 formtd">Supplier:</label>
                                                    <div class="col-lg-8 formtd">
                                                        <span><?php echo $row1[10]; ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="myPyblInvcCstmrSite" class="control-label col-lg-4 formtd">Site:</label>
                                                    <div class="col-lg-8 formtd">
                                                        <span><?php echo $row1[12]; ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="myPyblInvcLnkdEvnt" class="control-label col-lg-4 formtd">Linked Event:</label>
                                                    <div class="col-lg-8 formtd">
                                                        <span><?php echo ($row1[31]); ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="myPyblInvcEvntCtgry" class="control-label col-lg-4 formtd">Category:</label>
                                                    <div class="col-lg-8 formtd">
                                                        <span><?php echo $row1[28]; ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" style="padding:0px 3px 0px 3px !important;">
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="myPyblInvcCstmrDoc" class="control-label col-lg-5 formtd">Supplier's Doc. No.:</label>
                                                    <div class="col-lg-7 formtd">
                                                        <span><?php echo $row1[22]; ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="myPyblInvcPayMthd" class="control-label col-lg-5 formtd">Pay Method:</label>
                                                    <div class="col-lg-7 formtd">
                                                        <span><?php echo $row1[18]; ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="myPyblInvcPayTrms" class="control-label col-lg-5 formtd">Pay Terms:</label>
                                                    <div class="col-lg-7 formtd">
                                                        <span><?php echo $row1[16]; ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="myPyblInvcSrcDocTyp" class="control-label col-lg-5 formtd">Source Doc. Type:</label>
                                                    <div class="col-lg-7 formtd">
                                                        <span><?php echo $row1[8]; ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="myPyblInvcSrcDocNum" class="control-label col-lg-5 formtd">Source Doc. No.:</label>
                                                    <div class="col-lg-7 formtd">
                                                        <span><?php echo $row1[26]; ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="myPyblInvcGLBatch" class="control-label col-lg-5 formtd">GL Batch:</label>
                                                    <div class="col-lg-7 formtd">
                                                        <span><?php echo $row1[21]; ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="myPyblInvcCrtdBy" class="control-label col-lg-5 formtd">Created By:</label>
                                                    <div class="col-lg-7 formtd">
                                                        <span><?php echo $row1[3]; ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" style="padding:0px 3px 0px 3px !important;">
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="myPyblInvcTtl" class="control-label col-lg-7 formtd">Invoice Total:</label>
                                                    <div class="col-lg-5 formtd">
                                                        <span><?php echo number_format($row1[15], 2, '.', ','); ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="myPyblInvcAmntPaid" class="control-label col-lg-7 formtd" style="color:blue;">Amount Paid:</label>
                                                    <div class="col-lg-5 formtd">
                                                        <span><?php echo number_format($row1[19], 2, '.', ','); ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="myPyblInvcNetAmnt" class="control-label col-lg-7 formtd" style="color:blue;">Outstanding Amount:</label>
                                                    <div class="col-lg-5 formtd">
                                                        <?php
                                                        $netAmnt = (float) $row1[15] - (float) $row1[19];
                                                        $spnColor = (round($netAmnt, 2) <= 0) ? "lime" : "#FF9191";
                                                        ?>
                                                        <span style="padding:5px;background-color:<?php echo $spnColor; ?>;"><?php echo number_format($netAmnt, 2, '.', ','); ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="myPyblInvcAvlblPrpyAmnt" class="control-label col-lg-7 formtd" style="color:blue;">Un-Applied Amount:</label>
                                                    <div class="col-lg-5 formtd">
                                                        <?php
                                                        $netAmnt1 = 0;
                                                        $spnColor1 = "";
                                                        if ($row1[5] == "Supplier Advance Payment") {
                                                            $netAmnt1 = (float) $row1[19] - (float) $row1[30];
                                                            $spnColor1 = (round($netAmnt1, 2) <= 0) ? "lime" : "#FF9191";
                                                        }
                                                        ?>
                                                        <span style="padding:5px;background-color:<?php echo $spnColor1; ?>;"><?php echo number_format(0, 2, '.', ','); ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="myPyblInvcCurr" class="control-label col-lg-7 formtd">Currency:</label>
                                                    <div class="col-lg-5 formtd">
                                                        <span><?php echo $row1[25]; ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="myPyblInvcClsfctn" class="control-label col-lg-4 formtd">Doc. Classification:</label>
                                                    <div class="col-lg-8 formtd">
                                                        <span><?php echo $row1[23]; ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="myPyblInvcStatus" class="control-label col-lg-7 formtd">Doc. Status:</label>
                                                    <div class="col-lg-5 formtd">
                                                        <?php
                                                        $spnColor2 = "";
                                                        if ($row1[13] == "Approved") {
                                                            $spnColor2 = "lime";
                                                        } else if ($row1[13] == "Cancelled") {
                                                            $spnColor2 = "#FF9191";
                                                        } else if ($row1[13] == "Not Validated") {
                                                            $spnColor2 = "#e5a110";
                                                        } else {
                                                            $spnColor2 = "#44d6d6";
                                                        }
                                                        ?>
                                                        <span style="padding:5px;font-weight:bold;background-color:<?php echo $spnColor2; ?>;"><?php echo $row1[13]; ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="padding:2px !important;">
                                            <div class="col-md-12">
                                                <div class="form-group form-group-sm " style="padding:0px 5px 0px 5px !important;">
                                                    <label for="myPyblInvcDesc" class="control-label col-lg-2 formtd">Description:</label>
                                                    <div class="col-lg-10 formtd">
                                                        <span><?php echo $row1[6]; ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-9" style="padding:0px 2px 0px 0px !important;">
                                                <table class="table table-striped table-bordered" id="myPyblInvcLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 500px;">
                                                    <thead>
                                                        <tr>
                                                            <th>No.</th>
                                                            <th>Item Type</th>
                                                            <th>Item Description</th>
                                                            <th style="text-align: right;">Amount (<?php echo $curr; ?>)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $result2 = get_PyblsDocLines($sbmtdPyblInvcID);
                                                        $cntr = 0;
                                                        $curIdx = 0;
                                                        $brghtTotal = 0;
                                                        if ($result2 !== NULL) {
                                                            while ($row2 = loc_db_fetch_array($result2)) {
                                                                $cntr += 1;
                                                                $itemType = $row2[1];
                                                                $itemDesc = $row2[2];
                                                                $itmQty = 0;
                                                                $itmUom = 0;
                                                                $itmUntPrc = 0;
                                                                $itmAmount = number_format($row2[3], 2, '.', ',');
                                                        ?>
                                                                <tr id="myPyblInvcLinesRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                                    <td class="lovtd"><?php echo $itemType; ?></td>
                                                                    <td class="lovtd"><span><?php echo $itemDesc; ?></span></td>
                                                                    <td class="lovtd" style="text-align: right;"><span><?php echo $itmAmount; ?></span></td>
                                                                </tr>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-3" style="padding:0px 0px 0px 2px !important;">
                                                <table class="table table-striped table-bordered" id="myPyblInvcSmryTable" cellspacing="0" width="100%" style="width:100%;min-width: 100px;">
                                                    <thead>
                                                        <tr>
                                                            <th>No.</th>
                                                            <th>Summary Item</th>
                                                            <th style="text-align: right;">Amount (<?php echo $curr; ?>)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $result3 = get_PyblsDocSmryLns($sbmtdPyblInvcID, $pyblDocTyp);
                                                        $cntr = 0;
                                                        $curIdx = 0;
                                                        if ($result3 !== NULL) {
                                                            while ($row3 = loc_db_fetch_array($result3)) {
                                                                $cntr += 1;
                                                                $itemDesc = $row3[1];
                                                                $itmAmount = number_format($row3[2], 2, '.', ',');
                                                                $spnColor3 = "";
                                                                if ($itemDesc == "Outstanding Balance" && (float) $row3[2] <= 0) {
                                                                    $spnColor3 = "background-color:lime;";
                                                                } else if ($itemDesc == "Outstanding Balance") {
                                                                    $spnColor3 = "background-color:#FF9191;";
                                                                }
                                                        ?>
                                                                <tr id="myPyblInvcLinesRow_<?php echo $cntr; ?>" class="hand_cursor" style="<?php echo $spnColor3; ?>">
                                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                                    <td class="lovtd">
                                                                        <span><?php echo $itemDesc; ?></span>
                                                                    </td>
                                                                    <td class="lovtd" style="text-align: right;"><span><?php echo $itmAmount; ?></span></td>
                                                                </tr>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php
                                        }
                                    } else {
                                    ?>
                                    <span>No Results Found</span>
                                <?php }
                                    if ($vwtyp == 4) {
                                ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        <?php
                                    }
                                } else if ($vwtyp == 10 || $vwtyp == 12) {
                                    $error = "";
                                    $searchAll = true;
                                    $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                                    $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                                    $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                                    $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 100;
                                    $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
                                    $qShwUnpstdOnly = true;
                                    $payTrnsRqstsType = "LOAN";
                                    $headerLabls = "My Loan Requests";
                                    if ($vwtyp == 11) {
                                        $headerLabls = "My Payment Requests";
                                        $payTrnsRqstsType = "PAYMENT";
                                    } else if ($vwtyp == 12) {
                                        $headerLabls = "My Settlement Requests";
                                        $payTrnsRqstsType = "SETTLEMENT";
                                    }
                                    if (strpos($srchFor, "%") === FALSE) {
                                        $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                                        $srchFor = str_replace("%%", "%", $srchFor);
                                    }
                                    $total = get_Total_TrnsRqstsDoc($srchFor, $srchIn, $orgID, $qShwUnpstdOnly, $payTrnsRqstsType);
                                    if ($pageNo > ceil($total / $lmtSze)) {
                                        $pageNo = 1;
                                    } else if ($pageNo < 1) {
                                        $pageNo = ceil($total / $lmtSze);
                                    }
                                    $curIdx = $pageNo - 1;
                                    $result = get_TrnsRqstsDocHdr($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qShwUnpstdOnly, $payTrnsRqstsType);
                                    $cntr = 0;
                                    $colClassType1 = "col-md-2";
                                    $colClassType2 = "col-md-3";
        ?>
        <div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark"><?php echo $headerLabls; ?></h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:openATab('#allmodules', 'grp=42&typ=1');">All Apps</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:openATab('#allmodules', 'grp=80&typ=1');">My Financials</a></li>
                            <li class="breadcrumb-item active"><?php echo $headerLabls; ?></li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content" style="padding: 10px 5px 10px 5px !important;">
            <div class="container-fluid">
                <form id='payTrnsRqstsForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type="hidden" placeholder="ROW ID" />
                    <div class="row" style="margin-bottom:0px;">
                        <?php if ($canAdd === true) { ?>
                            <div class="<?php echo $colClassType2; ?>">
                                <?php if ($vwtyp == 10) { ?>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOnePayTrnsRqstsForm(-1, 101, 'ShowDialog', 'LOAN');">
                                        <img src="../cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Loan
                                    </button>
                                <?php } else if ($vwtyp == 11) { ?>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOnePayTrnsRqstsForm(-1, 101, 'ShowDialog', 'PAYMENT');">
                                        <img src="../cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Payment
                                    </button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOnePayTrnsRqstsForm(-1, 101, 'ShowDialog', 'SETTLEMENT');">
                                        <img src="../cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Settlement
                                    </button>
                                <?php } ?>
                            </div>
                        <?php
                                    } else {
                                        $colClassType1 = "col-md-2";
                                        $colClassType2 = "col-md-4";
                                    }
                        ?>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <input class="form-control" id="payTrnsRqstsSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                    echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                    ?>" onkeyup="enterKeyFuncPayTrnsRqsts(event, '', '#allmodules', 'grp=80&typ=1&vtyp=<?php echo $vwtyp; ?>');">
                                <input id="payTrnsRqstsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                <div class="input-group-append handCursor" onclick="getPayTrnsRqsts('clear', '#allmodules', 'grp=80&typ=1&vtyp=<?php echo $vwtyp; ?>');">
                                    <span class="input-group-text rhoclickable"><i class="fas fa-times"></i></span>
                                </div>
                                <div class="input-group-append handCursor" onclick="getPayTrnsRqsts('', '#allmodules', 'grp=80&typ=1&vtyp=<?php echo $vwtyp; ?>');">
                                    <span class="input-group-text rhoclickable"><i class="fas fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-filter"></i></span>
                                </div>
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
                                        <a href="javascript:getPayTrnsRqsts('previous', '#allmodules', 'grp=80&typ=1&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:getPayTrnsRqsts('next', '#allmodules', 'grp=80&typ=1&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important;display:none;">
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
                                        <input type="checkbox" class="form-check-input" onclick="getPayTrnsRqsts('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=<?php echo $vwtyp; ?>');" id="payTrnsRqstsShwUnpstdOnly" name="payTrnsRqstsShwUnpstdOnly" <?php echo $shwUnpstdOnlyChkd; ?>>
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
                            <table class="table table-striped table-bordered" id="payTrnsRqstsHdrsTable" cellspacing="0" width="100%" style="width:100%;border-radius:10px !important;">
                                <thead>
                                    <tr>
                                        <th style="">No.</th>
                                        <th style="">...</th>
                                        <th style="">Requestor</th>
                                        <th style="">Request Date</th>
                                        <th style="">Request Type (Item Type)</th>
                                        <th style="">Classification</th>
                                        <th>Request Reason </th>
                                        <th style="text-align:center;">CUR.</th>
                                        <th style="text-align:right;">Principal Amount</th>
                                        <th style="">Request Status</th>
                                        <?php if ($canDel === true) { ?>
                                            <th style="">...</th>
                                        <?php } ?>
                                        <?php if ($canVwRcHstry === true) { ?>
                                            <th style="">...</th>
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
                                                <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Edit Request" onclick="getOnePayTrnsRqstsForm(<?php echo $row[0]; ?>, 101, 'ShowDialog', '<?php echo $row[3]; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                    <?php
                                                    if ($canEdt === true) {
                                                    ?>
                                                        <img src="../cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    <?php } else { ?>
                                                        <img src="../cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
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
                                            ?>
                                            <td class="lovtd" style="font-weight:bold;<?php echo $style1; ?>"><?php
                                                                                                                echo $row[13] . " - <span style=\"" . $style2 . "\">" . ($row[15] == "1" ? "Processed" : "Not Processed") . "</span>";
                                                                                                                ?>
                                            </td>
                                            <?php if ($canDel === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction" onclick="delPayTrnsRqsts('payTrnsRqstsHdrsRow_<?php echo $cntr; ?>')" style="padding:2px !important;" style="padding:2px !important;">
                                                        <img src="../cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                    <input type="hidden" id="payTrnsRqstsHdrsRow<?php echo $cntr; ?>_HdrID" name="payTrnsRqstsHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                </td>
                                            <?php } ?>
                                            <?php
                                            if ($canVwRcHstry === true) {
                                            ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                    echo urlencode(encrypt1(($row[0] . "|pay.pay_loan_pymnt_rqsts|pay_request_id"), $smplTokenWord1));
                                                                                                                                                                                                    ?>');" style="padding:2px !important;">
                                                        <img src="../cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
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
                </form>
            </div>
        </section>
        <?php
                                } else if ($vwtyp == 11 || $vwtyp == 1101 || $vwtyp == 1102) {
                                    $error = "";
                                    $searchAll = true;
                                    $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                                    $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                                    $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                                    $lmtSze = isset($_POST['limitSze']) ? (int) cleanInputData($_POST['limitSze']) : 100;
                                    $myBalsDetItmStID = isset($_POST['myBalsDetItmStID']) ? (int) cleanInputData($_POST['myBalsDetItmStID']) : -1;
                                    $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
                                    $qShwUnpstdOnly = true;
                                    $headerLabls = "My Payments and Contributions";
                                    $payTrnsRqstsType = "PAYMENT";
                                    $qStrtDte = "";
                                    $qEndDte = "";
                                    $date = new DateTime($gnrlTrnsDteDMYHMS);
                                    $date->modify('-3 month');
                                    $qStrtDte = $date->format('d-M-Y') . " 00:00:00";
                                    $date = new DateTime($gnrlTrnsDteDMYHMS);
                                    $date->modify('+0 month');
                                    $qEndDte = $date->format('d-M-Y') . " 23:59:59";
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
                                    if (strpos($srchFor, "%") === FALSE) {
                                        $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                                        $srchFor = str_replace("%%", "%", $srchFor);
                                    }
                                    if ($vwtyp == 11) {
        ?>
            <div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark"><?php echo $headerLabls; ?></h1>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="javascript:openATab('#allmodules', 'grp=42&typ=1');">All Apps</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:openATab('#allmodules', 'grp=80&typ=1');">My Financials</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:openATab('#allmodules', 'grp=80&typ=1&vtyp=<?php echo $vwtyp; ?>');"><?php echo $headerLabls; ?></a></li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->
            <section class="content" style="padding: 10px 5px 10px 5px !important;">
                <div class="container-fluid">
                    <div class="card card-outline card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs" id="prsnPrflTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="payItemBalstab" data-toggle="pill" href="#payItemBals" role="tab" aria-controls="custom-tabs-two-home" aria-selected="false"><i class="fas fa-file-invoice"></i></i> Cumulative Balance Details</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="payPymntTranstab" data-toggle="pill" href="#payPymntTrans" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false"><i class="fas fa-money-bill-wave"></i></i> All Payment Requests</a>
                                </li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="payItemBals">
                                <?php
                                    }
                                    if ($vwtyp == 1102 || $vwtyp == 11) {
                                ?>
                                    <form class="form-horizontal" id="payItemBalsForm" action='' method='post' accept-charset='UTF-8'>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" style="font-weight:bold;"> Choose Balance:</span>
                                                    </div>
                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="myBalsDetItmStID" onchange="getMyBalsDet('clear', '#payItemBals', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=1102');">
                                                        <?php
                                                        $payBalsItemID = $myBalsDetItmStID;
                                                        if ($payBalsItemID <= 0) {
                                                            $payBalsItemID = getItmID("Total Contribution", $orgID);
                                                        }
                                                        $cnt = 0;
                                                        $brghtStr = "";
                                                        $isDynmyc = true;
                                                        $titleRslt = get_CumltiveBals($prsnid);
                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                            $selectedTxt = "";
                                                            if ($cnt == 0 && $payBalsItemID <= 0) {
                                                                $payBalsItemID = ((int) $titleRow[5]);
                                                            }
                                                            $cnt++;
                                                            if (((int) $titleRow[5]) == $payBalsItemID) {
                                                                $selectedTxt = "selected";
                                                            }
                                                        ?>
                                                            <option value="<?php echo $titleRow[5]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[6]; ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <?php
                                            $total = get_Total_Trns($srchFor, $srchIn, $orgID, $qStrtDte, $qEndDte, $payBalsItemID);
                                            if ($pageNo > ceil($total / $lmtSze)) {
                                                $pageNo = 1;
                                            } else if ($pageNo < 1) {
                                                $pageNo = ceil($total / $lmtSze);
                                            }

                                            $curIdx = $pageNo - 1;
                                            $result = get_Pay_Trns($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qStrtDte, $qEndDte, $payBalsItemID);
                                            $cntr = 0;
                                            ?>
                                            <div class="col-sm-4">
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control" placeholder="Search Mail" id="myBalsDetSrchFor" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncMyBalsDet(event, '', '#payItemBals', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=1102');">
                                                    <input id="myBalsDetPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                                    <div class="input-group-append handCursor" onclick="getMyBalsDet('clear', '#payItemBals', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=1102');">
                                                        <span class="input-group-text rhoclickable"><i class="fas fa-times"></i></span>
                                                    </div>
                                                    <div class="input-group-append handCursor" onclick="getMyBalsDet('', '#payItemBals', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=1102');">
                                                        <span class="input-group-text rhoclickable"><i class="fas fa-search"></i></span>
                                                    </div>
                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="myBalsDetSrchIn" style="max-width:110px !important;border-bottom-left-radius: 1px !important;">
                                                        <?php
                                                        $valslctdArry = array("", "");
                                                        $srchInsArrys = array("Item Name", "Transaction Description");
                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                            if ($srchIn == $srchInsArrys[$z]) {
                                                                $valslctdArry[$z] = "selected";
                                                            }
                                                        ?>
                                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="myBalsDetDsplySze" style="max-width:80px !important;border-bottom-left-radius: 1px !important;">
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
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-2" style="padding:0px 1px 0px 1px !important;">
                                                <div class="input-group date rho-DatePicker input-group-sm" id="myBalsDetStrtDateDP" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input" id="myBalsDetStrtDate" value="<?php echo substr($qStrtDte, 0, 11); ?>" data-target="#myBalsDetStrtDateDP" placeholder="DD-MMM-YYYY">
                                                    <div class="input-group-append handCursor" onclick="$('#myBalsDetStrtDate').val('');">
                                                        <span class="input-group-text rhoclickable"><i class="fa fa-times"></i></span>
                                                    </div>
                                                    <div class="input-group-append handCursor" data-target="#myBalsDetStrtDateDP" data-toggle="datetimepicker">
                                                        <span class="input-group-text rhoclickable"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2" style="padding:0px 1px 0px 1px !important;">
                                                <div class="input-group date rho-DatePicker input-group-sm" id="myBalsDetEndDateDP" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input" id="myBalsDetEndDate" value="<?php echo substr($qEndDte, 0, 11); ?>" data-target="#myBalsDetEndDateDP" placeholder="DD-MMM-YYYY">
                                                    <div class="input-group-append handCursor" onclick="$('#myBalsDetEndDate').val('');">
                                                        <span class="input-group-text rhoclickable"><i class="fa fa-times"></i></span>
                                                    </div>
                                                    <div class="input-group-append handCursor" data-target="#myBalsDetEndDateDP" data-toggle="datetimepicker">
                                                        <span class="input-group-text rhoclickable"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-1" style="">
                                                <div class="float-right">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default btn-sm" onclick="getMyBalsDet('previous', '#payItemBals', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=1102');"><i class="fas fa-chevron-left"></i></button>
                                                        <button type="button" class="btn btn-default btn-sm" onclick="getMyBalsDet('next', '#payItemBals', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=1102');"><i class="fas fa-chevron-right"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr style="margin: 3px 0px 3px 0px !important;">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table class="table table-striped table-bordered" id="allPayRnTrnsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                                    <thead>
                                                        <tr>
                                                            <th>No.</th>
                                                            <th style="display:none;">ID No.</th>
                                                            <th style="min-width: 110px;display:none;">Person Name</th>
                                                            <th>Item Name</th>
                                                            <th style="display:none;">Item Type</th>
                                                            <th style="text-align:right;">UOM.</th>
                                                            <th style="text-align:right;">Transaction Amount (GHS)</th>
                                                            <th style="text-align:right;">Running Balance (GHS)</th>
                                                            <th>Transaction Date</th>
                                                            <th style="display:none;">Transaction Type</th>
                                                            <th style="display:none;">Transaction Description</th>
                                                            <th style="display:none;">Validity</th>
                                                            <?php if ($canVwRcHstry === true) { ?>
                                                                <th>...</th>
                                                            <?php } ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $opngBals = 0;
                                                        $trnsDate = "";
                                                        $trnsUOM = "";
                                                        $cntr = 0;
                                                        $trnsAmntTtl = 0;
                                                        $ttlDtRws = loc_db_num_rows($result);
                                                        while ($row = loc_db_fetch_array($result)) {
                                                            $trnsID = $row[0];
                                                            $trnsLocIDNo = $row[10];
                                                            $trnsPrsnNm = $row[11];
                                                            $trnsItmNm = $row[12];
                                                            $trnsUOM = $row[14];
                                                            $trnsAmnt = (float) $row[3];
                                                            $trnsDate = $row[4];
                                                            $trnsType = $row[6];
                                                            $trnsDesc = $row[7];
                                                            $trnsSource = $row[5];
                                                            $trnsVldty = $row[13];
                                                            $trnsItmType = $row[15];
                                                            $trnsAddSbtrct = $row[16];
                                                            $trnsMltplr = (float) $row[17];
                                                            $styleUse1 = "color:red;";
                                                            if ($trnsAddSbtrct == "Adds") {
                                                                $styleUse1 = "color:green;";
                                                                $trnsAmnt = 1 * $trnsMltplr * $trnsAmnt;
                                                            } else {
                                                                $trnsAmnt = -1 * $trnsMltplr * $trnsAmnt;
                                                            }
                                                            $trnsAmntTtl = $trnsAmntTtl + $trnsAmnt;
                                                            $cntr += 1;
                                                            if ($cntr == 1) {
                                                                $date3 = new DateTime($qStrtDte);
                                                                if (($curIdx * $lmtSze) + ($cntr) > 1) {
                                                                    $date3 = new DateTime($trnsDate);
                                                                }
                                                                $date3->modify('-1 day');
                                                                $qStrtDte3 = $date3->format('d-M-Y') . " 23:59:59";
                                                                $opngBals = get_CumltiveBalAsAt($prsnid, $payBalsItemID, $qStrtDte3, $orgID);
                                                        ?>
                                                                <tr id="allPayRnTrnsHdrsRow_<?php echo $cntr; ?>">
                                                                    <td class="lovtd"></td>
                                                                    <td class="lovtd" style="display:none;">
                                                                        <?php echo $trnsLocIDNo; ?>
                                                                        <input type="hidden" id="allPayRnTrnsHdrsRow<?php echo $cntr; ?>_HdrID" name="allPayRnTrnsHdrsRow<?php echo $cntr; ?>_HdrID" value="-1">
                                                                    </td>
                                                                    <td class="lovtd" style="display:none;"><?php echo $trnsPrsnNm; ?></td>
                                                                    <td class="lovtd" style="text-align:left;font-weight:bold;color:blue;font-size:20px;">Opening Balance</td>
                                                                    <td class="lovtd" style="display:none;"><?php echo $trnsItmType; ?></td>
                                                                    <td class="lovtd" style="text-align:right;font-weight:bold;"></td>
                                                                    <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;"></td>
                                                                    <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;font-size:20px;"><?php
                                                                                                                                                            echo number_format($opngBals, 2);
                                                                                                                                                            ?>
                                                                    </td>
                                                                    <td class="lovtd"><?php echo substr($qStrtDte3, 0, 11); ?></td>
                                                                    <td class="lovtd" style="display:none;"><?php echo $trnsType; ?></td>
                                                                    <td class="lovtd" style="display:none;"><?php echo $trnsDesc . " [" . $trnsSource . "]"; ?></td>
                                                                    <td class="lovtd" style="display:none;"><?php echo $trnsVldty; ?></td>
                                                                    <?php if ($canVwRcHstry === true) { ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                                    echo urlencode(encrypt1(($trnsID . "|pay.pay_itm_trnsctns|pay_trns_id"), $smplTokenWord1));
                                                                                                                                                                                                                                    ?>');" style="padding:2px !important;">
                                                                                <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    <?php } ?>
                                                                </tr>
                                                            <?php
                                                            }
                                                            if ($vPsblVal1 == "NARHBT_COLLEGE_APP_1" && $cntr == $ttlDtRws) {
                                                                $styleUse1 = "color:red;";
                                                                if ($trnsAmntTtl > 0) {
                                                                    $styleUse1 = "color:green;";
                                                                }
                                                            ?>
                                                                <tr id="allPayRnTrnsHdrsRow_<?php echo $cntr; ?>">
                                                                    <td class="lovtd"><?php echo "1"; ?></td>
                                                                    <td class="lovtd" style="display:none;">
                                                                        <?php echo $trnsLocIDNo; ?>
                                                                        <input type="hidden" id="allPayRnTrnsHdrsRow<?php echo $cntr; ?>_HdrID" name="allPayRnTrnsHdrsRow<?php echo $cntr; ?>_HdrID" value="-1">
                                                                    </td>
                                                                    <td class="lovtd" style="display:none;"><?php echo $trnsPrsnNm; ?></td>
                                                                    <td class="lovtd">&nbsp;&nbsp;Items Net Total for Period</td>
                                                                    <td class="lovtd" style="display:none;"><?php echo $trnsItmType; ?></td>
                                                                    <td class="lovtd" style="text-align:right;font-weight:bold;"><?php echo $trnsUOM; ?></td>
                                                                    <td class="lovtd" style="text-align:right;font-weight:bold;<?php echo $styleUse1; ?>"><?php
                                                                                                                                                            echo number_format($trnsAmntTtl, 2);
                                                                                                                                                            ?>
                                                                    </td>
                                                                    <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;"><?php
                                                                                                                                            $opngBals += $trnsAmntTtl;
                                                                                                                                            echo number_format($trnsAmntTtl, 2);
                                                                                                                                            ?>
                                                                    </td>
                                                                    <td class="lovtd">&nbsp;</td>
                                                                    <td class="lovtd" style="display:none;"><?php echo $trnsType; ?></td>
                                                                    <td class="lovtd" style="display:none;"><?php echo $trnsDesc . " [" . $trnsSource . "]"; ?></td>
                                                                    <td class="lovtd" style="display:none;"><?php echo $trnsVldty; ?></td>
                                                                    <?php if ($canVwRcHstry === true) { ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                                    echo urlencode(encrypt1(($trnsID . "|pay.pay_itm_trnsctns|pay_trns_id"), $smplTokenWord1));
                                                                                                                                                                                                                                    ?>');" style="padding:2px !important;">
                                                                                <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    <?php } ?>
                                                                </tr>
                                                            <?php } else if ($vPsblVal1 != "NARHBT_COLLEGE_APP_1") {
                                                            ?>
                                                                <tr id="allPayRnTrnsHdrsRow_<?php echo $cntr; ?>">
                                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                                    <td class="lovtd" style="display:none;">
                                                                        <?php echo $trnsLocIDNo; ?>
                                                                        <input type="hidden" id="allPayRnTrnsHdrsRow<?php echo $cntr; ?>_HdrID" name="allPayRnTrnsHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $trnsID; ?>">
                                                                    </td>
                                                                    <td class="lovtd" style="display:none;"><?php echo $trnsPrsnNm; ?></td>
                                                                    <td class="lovtd">&nbsp;&nbsp;<?php echo $trnsItmNm; ?></td>
                                                                    <td class="lovtd" style="display:none;"><?php echo $trnsItmType; ?></td>
                                                                    <td class="lovtd" style="text-align:right;font-weight:bold;"><?php echo $trnsUOM; ?></td>
                                                                    <td class="lovtd" style="text-align:right;font-weight:bold;<?php echo $styleUse1; ?>"><?php
                                                                                                                                                            echo number_format($trnsAmnt, 2);
                                                                                                                                                            ?>
                                                                    </td>
                                                                    <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;"><?php
                                                                                                                                            $opngBals += $trnsAmnt;
                                                                                                                                            echo number_format($opngBals, 2);
                                                                                                                                            ?>
                                                                    </td>
                                                                    <td class="lovtd"><?php echo $trnsDate; ?></td>
                                                                    <td class="lovtd" style="display:none;"><?php echo $trnsType; ?></td>
                                                                    <td class="lovtd" style="display:none;"><?php echo $trnsDesc . " [" . $trnsSource . "]"; ?></td>
                                                                    <td class="lovtd" style="display:none;"><?php echo $trnsVldty; ?></td>
                                                                    <?php if ($canVwRcHstry === true) { ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                                    echo urlencode(encrypt1(($trnsID . "|pay.pay_itm_trnsctns|pay_trns_id"), $smplTokenWord1));
                                                                                                                                                                                                                                    ?>');" style="padding:2px !important;">
                                                                                <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    <?php } ?>
                                                                </tr>

                                                        <?php
                                                            }
                                                        }
                                                        $date4 = new DateTime($trnsDate);
                                                        if ($total == (($curIdx * $lmtSze) + ($cntr))) {
                                                            $date4 = new DateTime($qEndDte);
                                                        }
                                                        $qEndDte4 = $date4->format('d-M-Y') . " 23:59:59";
                                                        $clsngBals = get_CumltiveBalAsAt($prsnid, $payBalsItemID, $qEndDte4, $orgID);
                                                        $cntr += 1;
                                                        ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr id="allPayRnTrnsHdrsRow_<?php echo $cntr; ?>">
                                                            <th class="lovtd"></th>
                                                            <th class="lovtd" style="display:none;"></th>
                                                            <th class="lovtd" style="display:none;"></th>
                                                            <th class="lovtd" style="text-align:left;font-weight:bold;color:blue;font-size:20px;">Closing Balance</th>
                                                            <th class="lovtd" style="display:none;"></th>
                                                            <th class="lovtd" style="text-align:right;font-weight:bold;"></th>
                                                            <th class="lovtd" style="text-align:right;font-weight:bold;color:blue;"></th>
                                                            <th class="lovtd" style="text-align:right;font-weight:bold;color:blue;font-size:20px;"><?php
                                                                                                                                                    echo number_format($clsngBals, 2);
                                                                                                                                                    ?>
                                                            </th>
                                                            <th class="lovtd" style="font-weight:normal !important;"><?php echo substr($qEndDte4, 0, 11); ?></th>
                                                            <th class="lovtd" style="display:none;"></th>
                                                            <th class="lovtd" style="display:none;"></th>
                                                            <th class="lovtd" style="display:none;"></th>
                                                            <?php if ($canVwRcHstry === true) { ?>
                                                                <th class="lovtd"></th>
                                                            <?php } ?>
                                                        </tr>
                                                        <tr id="allPayRnTrnsHdrsRow_<?php echo ($cntr + 1); ?>">
                                                            <th class="lovtd"></th>
                                                            <th class="lovtd" style="display:none;"></th>
                                                            <th class="lovtd" style="display:none;"></th>
                                                            <th class="lovtd" style="text-align:left;font-weight:bold;color:blue;font-size:20px;"></th>
                                                            <th class="lovtd" style="display:none;"></th>
                                                            <th class="lovtd" style="text-align:right;font-weight:bold;"></th>
                                                            <th class="lovtd" style="text-align:right;font-weight:bold;color:blue;"></th>
                                                            <th class="lovtd" style="text-align:right;font-weight:bold;color:blue;font-size:20px;"></th>
                                                            <th class="lovtd">
                                                                <button type="button" class="btn btn-default" style="" onclick="openATab('#allmodules', 'grp=80&typ=1&vtyp=0');" style="width:100% !important;">
                                                                    <i class="fas fa-chevron-left"></i>&nbsp;&nbsp;Back to Summary Info.
                                                                </button>
                                                            </th>
                                                            <th class="lovtd" style="display:none;"></th>
                                                            <th class="lovtd" style="display:none;"></th>
                                                            <th class="lovtd" style="display:none;"></th>
                                                            <?php if ($canVwRcHstry === true) { ?>
                                                                <th class="lovtd"></th>
                                                            <?php } ?>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </form>
                                <?php
                                    }
                                    if ($vwtyp == 11) {
                                ?>
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="payPymntTrans">
                                <?php
                                    }
                                    if ($vwtyp == 1101 || $vwtyp == 11) {
                                        $total = get_Total_TrnsRqstsDoc($srchFor, $srchIn, $orgID, $qShwUnpstdOnly, $payTrnsRqstsType);
                                        if ($pageNo > ceil($total / $lmtSze)) {
                                            $pageNo = 1;
                                        } else if ($pageNo < 1) {
                                            $pageNo = ceil($total / $lmtSze);
                                        }
                                        $curIdx = $pageNo - 1;
                                        $result = get_TrnsRqstsDocHdr($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qShwUnpstdOnly, $payTrnsRqstsType);
                                        $cntr = 0;
                                        $colClassType1 = "col-md-2";
                                        $colClassType2 = "col-md-3";
                                ?>
                                    <form id='payTrnsRqstsForm' action='' method='post' accept-charset='UTF-8'>
                                        <!--ROW ID-->
                                        <input class="form-control" id="tblRowID" type="hidden" placeholder="ROW ID" />
                                        <div class="row" style="margin-bottom:0px;">
                                            <?php if ($canAdd === true) { ?>
                                                <div class="<?php echo $colClassType2; ?>">
                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOnePayTrnsRqstsForm(-1, 101, 'ShowDialog', 'PAYMENT');">
                                                        <img src="../cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        New Payment
                                                    </button>
                                                </div>
                                            <?php
                                            } else {
                                                $colClassType1 = "col-md-2";
                                                $colClassType2 = "col-md-4";
                                            }
                                            ?>
                                            <div class="<?php echo $colClassType2; ?>">
                                                <div class="input-group">
                                                    <input class="form-control" id="payTrnsRqstsSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                                        echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                                        ?>" onkeyup="enterKeyFuncPayTrnsRqsts(event, '', '#payPymntTrans', 'grp=80&typ=1&vtyp=1101');">
                                                    <input id="payTrnsRqstsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                                    <div class="input-group-append handCursor" onclick="getPayTrnsRqsts('clear', '#payPymntTrans', 'grp=80&typ=1&vtyp=1101');">
                                                        <span class="input-group-text rhoclickable"><i class="fas fa-times"></i></span>
                                                    </div>
                                                    <div class="input-group-append handCursor" onclick="getPayTrnsRqsts('', '#payPymntTrans', 'grp=80&typ=1&vtyp=1101');">
                                                        <span class="input-group-text rhoclickable"><i class="fas fa-search"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="<?php echo $colClassType2; ?>">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-filter"></i></span>
                                                    </div>
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
                                                            <a href="javascript:getPayTrnsRqsts('previous', '#payPymntTrans', 'grp=80&typ=1&vtyp=1101');" aria-label="Previous">
                                                                <span aria-hidden="true">&laquo;</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:getPayTrnsRqsts('next', '#payPymntTrans', 'grp=80&typ=1&vtyp=1101');" aria-label="Next">
                                                                <span aria-hidden="true">&raquo;</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div>
                                        <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important;display:none;">
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
                                                            <input type="checkbox" class="form-check-input" onclick="getPayTrnsRqsts('', '#payPymntTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=1101');" id="payTrnsRqstsShwUnpstdOnly" name="payTrnsRqstsShwUnpstdOnly" <?php echo $shwUnpstdOnlyChkd; ?>>
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
                                                <table class="table table-striped table-bordered" id="payTrnsRqstsHdrsTable" cellspacing="0" width="100%" style="width:100%;border-radius:10px !important;">
                                                    <thead>
                                                        <tr>
                                                            <th style="">No.</th>
                                                            <th style="">...</th>
                                                            <th style="">Requestor</th>
                                                            <th style="">Request Date</th>
                                                            <th style="">Request Type (Item Type)</th>
                                                            <th style="">Classification</th>
                                                            <th>Request Reason </th>
                                                            <th style="text-align:center;">CUR.</th>
                                                            <th style="text-align:right;">Principal Amount</th>
                                                            <th style="">Request Status</th>
                                                            <?php if ($canDel === true) { ?>
                                                                <th style="">...</th>
                                                            <?php } ?>
                                                            <?php if ($canVwRcHstry === true) { ?>
                                                                <th style="">...</th>
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
                                                                    <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Edit Request" onclick="getOnePayTrnsRqstsForm(<?php echo $row[0]; ?>, 101, 'ShowDialog', '<?php echo $row[3]; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                        ?>
                                                                            <img src="../cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        <?php } else { ?>
                                                                            <img src="../cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
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
                                                                ?>
                                                                <td class="lovtd" style="font-weight:bold;<?php echo $style1; ?>"><?php
                                                                                                                                    echo $row[13] . " - <span style=\"" . $style2 . "\">" . ($row[15] == "1" ? "Processed" : "Not Processed") . "</span>";
                                                                                                                                    ?>
                                                                </td>
                                                                <?php if ($canDel === true) { ?>
                                                                    <td class="lovtd">
                                                                        <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction" onclick="delPayTrnsRqsts('payTrnsRqstsHdrsRow_<?php echo $cntr; ?>')" style="padding:2px !important;" style="padding:2px !important;">
                                                                            <img src="../cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                        <input type="hidden" id="payTrnsRqstsHdrsRow<?php echo $cntr; ?>_HdrID" name="payTrnsRqstsHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                                    </td>
                                                                <?php } ?>
                                                                <?php
                                                                if ($canVwRcHstry === true) {
                                                                ?>
                                                                    <td class="lovtd">
                                                                        <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                        echo urlencode(encrypt1(($row[0] . "|pay.pay_loan_pymnt_rqsts|pay_request_id"), $smplTokenWord1));
                                                                                                                                                                                                                        ?>');" style="padding:2px !important;">
                                                                            <img src="../cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
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
                                    </form>
                                <?php }
                                    if ($vwtyp == 11) { ?>
                                </div>
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                </div>
            </section>
        <?php
                                    }
                                } else if ($vwtyp == 101) {
                                    //New Loan and Payment Requests Form
                                    $sbmtdPayTrnsRqstsID = isset($_POST['sbmtdPayTrnsRqstsID']) ? (float) cleanInputData($_POST['sbmtdPayTrnsRqstsID']) : -1;
                                    $lnkdPayTrnsRqstsID = -1;
                                    $payTrnsRqstsType = isset($_POST['payTrnsRqstsType']) ? cleanInputData($_POST['payTrnsRqstsType']) : "LOAN";
                                    if ((!$canAdd) || ($sbmtdPayTrnsRqstsID > 0 && !$canEdt)) {
                                        restricted();
                                        exit();
                                    }
                                    $vPsblValID1 = getEnbldPssblValID("Application Instance SHORT CODE", getLovID("All Other General Setups"));
                                    $vPsblVal1 = getPssblValDesc($vPsblValID1);
                                    $payTrnsRqstsPrsnID = $prsnid;
                                    $payTrnsRqstsPrsnNm = getPrsnFullNm($payTrnsRqstsPrsnID);
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
                                    $payTrnsRqstsMinAmnt = 0;
                                    $payTrnsRqstsEnfrcMx = "0";
                                    $mkReadOnly = "";
                                    $mkRmrkReadOnly = "";
                                    if ($sbmtdPayTrnsRqstsID > 0) {
                                        $result = get_One_TrnsRqstsDocHdr($sbmtdPayTrnsRqstsID);
                                        if ($row = loc_db_fetch_array($result)) {
                                            $payTrnsRqstsPrsnID = (float) $row[1];
                                            if ($prsnid != $payTrnsRqstsPrsnID) {
                                                restricted();
                                                exit();
                                                return false;
                                            }
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
                        <div class="col-md-3" style="padding-right:1px;">
                            <input type="hidden" value="<?php echo $vPsblVal1; ?>" id="sbmtdPayTrnsAppCODE" name="sbmtdPayTrnsAppCODE">
                            <input type="hidden" value="<?php echo $payTrnsRqstsEnfrcMx; ?>" id="payTrnsRqstsEnfrcMx" name="payTrnsRqstsEnfrcMx">
                            <input type="text" class="form-control" aria-label="..." id="sbmtdPayTrnsRqstsID" name="sbmtdPayTrnsRqstsID" value="<?php echo $sbmtdPayTrnsRqstsID; ?>" readonly="true">
                        </div>
                        <div class="col-md-3" style="padding-left:1px;padding-right:1px;">
                            <input type="text" class="form-control" aria-label="..." id="payTrnsRqstsDate" name="payTrnsRqstsDate" value="<?php echo $payTrnsRqstsDate; ?>" readonly="true">
                        </div>
                        <div class="col-md-2" style="padding-left:1px;">
                            <input type="text" class="form-control" aria-label="..." id="payTrnsRqstsType" name="payTrnsRqstsType" value="<?php echo $payTrnsRqstsType; ?>" readonly="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4">
                            <label style="margin-bottom:0px !important;"><?php echo ucfirst(strtolower($payTrnsRqstsType)); ?> Type / Classification:</label>
                        </div>
                        <div class="col-md-4" style="padding-right:1px;">
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
                        <div class="col-md-4" style="padding-left:1px;">
                            <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="payTrnsRqstsClsfctn" name="payTrnsRqstsClsfctn" style="width:100% !important;">
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
                            <input type="hidden" name="payTrnsRqstsPrsnID" id="payTrnsRqstsPrsnID" class="form-control" value="<?php echo $payTrnsRqstsPrsnID; ?>">
                            <span><?php echo $payTrnsRqstsPrsnNm; ?></span>
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
                                <div class="input-group-append handCursor" onclick="popUpDisplay('payTrnsRqstsDesc');">
                                    <span class="input-group-text rhoclickable"><i class="fas fa-th-list"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4">
                            <label style="margin-bottom:0px !important;">Amount:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group">
                                <div class="input-group-prepend handCursor" onclick="">
                                    <span class="input-group-text rhoclickable" id="payTrnsRqstsInvcCur1"><?php echo $payTrnsRqstsInvcCur; ?></span>
                                </div>
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
                                    <div class="input-group-prepend handCursor" onclick="">
                                        <span class="input-group-text rhoclickable" id="payTrnsRqstsInvcCur5"><?php echo $payTrnsRqstsInvcCur; ?></span>
                                    </div>
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
                                        <div class="input-group-prepend handCursor" onclick="">
                                            <span class="input-group-text rhoclickable" id="payTrnsRqstsInvcCur3"><?php echo $payTrnsRqstsInvcCur; ?></span>
                                        </div>
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
                                    <div class="input-group-prepend handCursor" onclick="">
                                        <span class="input-group-text rhoclickable" id="payTrnsRqstsInvcCur8"><?php echo $payTrnsRqstsInvcCur; ?></span>
                                    </div>
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
                                        <div class="input-group-prepend handCursor" onclick="">
                                            <span class="input-group-text rhoclickable" id="payTrnsRqstsInvcCur10"><?php echo $payTrnsRqstsInvcCur; ?></span>
                                        </div>
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
                                        <div class="input-group-prepend handCursor" onclick="">
                                            <span class="input-group-text rhoclickable" id="payTrnsRqstsInvcCur15"><?php echo $payTrnsRqstsInvcCur; ?></span>
                                        </div>
                                        <input type="hidden" id="payTrnsRqstsInvcCur14" value="<?php echo $payTrnsRqstsInvcCur; ?>">
                                        <input class="form-control" type="text" id="payTrnsRqstsMinAmnt" value="<?php echo number_format($payTrnsRqstsMinAmnt, 2);
                                                                                                                ?>" style="font-weight:bold;width:100%;font-size:18px !important;" readonly="true" />
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    <?php if ($sbmtdPayTrnsRqstsID > 0) { ?>
                        <div class="form-group">
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
                            <button type="button" class="btn btn-default" style="width:100% !important;" id="myPayTrnsRqstsStatusBtn">
                                <span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php
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
                <div class="col-md-5">
                    <div class="" style="float:left !important;">
                        <?php if ($sbmtdPayTrnsRqstsID > 0) { ?>
                            <button type="button" class="btn btn-default" style="margin-bottom: 1px;" onclick="getOnePayTrnsRqstsDocsForm(<?php echo $sbmtdPayTrnsRqstsID; ?>, 20);" data-toggle="tooltip" data-placement="bottom" title="Attached Documents">
                                <img src="../cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                            </button>
                            <button type="button" class="btn btn-default" style="margin-bottom: 1px;" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="width:100% !important;">
                                <img src="../cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                Print
                            </button>
                        <?php } ?>
                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;" onclick="getOnePayTrnsRqstsForm(<?php echo $sbmtdPayTrnsRqstsID; ?>, 101, 'ReloadDialog', '<?php echo $payTrnsRqstsType; ?>');"><img src="../cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                    </div>
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
                            <button type="button" class="btn btn-default" style="" onclick="actOnLoanRqst('Withdraw');"><img src="../cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">Withdraw&nbsp;</button>
                            <?php if (($canApprove === true)) { ?>
                                <button type="button" class="btn btn-default" style="" onclick="actOnLoanRqst('Approve');"><img src="../cmn_images/Stamp-512.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">Approve&nbsp;</button>
                            <?php } ?>
                            <button type="button" class="btn btn-default" style="" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, '<?php echo ucfirst(strtolower($payTrnsRqstsType)); ?> Approval Progress History');"><img src="../cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">Progress&nbsp;</button>
                        <?php
                                    } else if ($rqstStatus == "Approved") {
                        ?>
                            <button type="button" class="btn btn-default" style="" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, '<?php echo ucfirst(strtolower($payTrnsRqstsType)); ?> Approval Progress History');"><img src="../cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;" data-toggle="tooltip" title="Approval Progress History">Progress&nbsp;</button>
                        <?php } ?>
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
                                                <div class=\"input-group-append handCursor\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Attachment Document Categories', '', '', '', 'radio', true, '', 'attchdTrnsRqstsDocsRow_WWW123WWW_DocCtgryNm', 'attchdTrnsRqstsDocsRow_WWW123WWW_DocCtgryNm', 'clear', 0, '');\">
                                                    <span class=\"input-group-text rhoclickable\"><i class=\"fas fa-th-list\"></i></span>
                                                </div>
                                              </div>
                                              </div>
                                              <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attchdTrnsRqstsDocsRow_WWW123WWW_AttchdDocsID\" value=\"-1\" style=\"\">                                               
                                          </td>
                                          <td class=\"lovtd\">
                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"uploadFileToTrnsRqstsDocs('attchdTrnsRqstsDocsRow_WWW123WWW_DocFile','attchdTrnsRqstsDocsRow_WWW123WWW_AttchdDocsID','attchdTrnsRqstsDocsRow_WWW123WWW_DocCtgryNm'," . $pkID . ",'attchdTrnsRqstsDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Download Document\">
                                                <img src=\"../cmn_images/openfileicon.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\"> Upload
                                            </button>
                                          </td>
                                          <td class=\"lovtd\">
                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAttchdTrnsRqstsDoc('attchdTrnsRqstsDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Document\">
                                                <img src=\"../cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                            </button>
                                          </td>
                                        </tr>");
                    ?>
                    <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 1px !important;">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('attchdTrnsRqstsDocsTable', 0, '<?php echo $nwRowHtml; ?>');" style="width:100% !important;">
                                <img src="../cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                New Document
                            </button>
                        </div>
                    </div>
                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                        <div class="input-group">
                            <input class="form-control" id="attchdTrnsRqstsDocsSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                    echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                    ?>" onkeyup="enterKeyFuncAttchdTrnsRqstsDocs(event, '', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPayTrnsRqstsID=<?php echo $sbmtdPayTrnsRqstsID; ?>', 'ReloadDialog');">
                            <input id="attchdTrnsRqstsDocsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                            <div class="input-group-append handCursor" onclick="getAttchdTrnsRqstsDocs('clear', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPayTrnsRqstsID=<?php echo $sbmtdPayTrnsRqstsID; ?>', 'ReloadDialog');">
                                <span class="input-group-text rhoclickable"><i class="fas fa-times"></i></span>
                            </div>
                            <div class="input-group-append handCursor" onclick="getAttchdTrnsRqstsDocs('', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPayTrnsRqstsID=<?php echo $sbmtdPayTrnsRqstsID; ?>', 'ReloadDialog');">
                                <span class="input-group-text rhoclickable"><i class="fas fa-search"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="<?php echo $colClassType2; ?>">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-filter"></i></span>
                            </div>
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
                                    <a class="rhopagination" href="javascript:getAttchdTrnsRqstsDocs('previous', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPayTrnsRqstsID=<?php echo $sbmtdPayTrnsRqstsID; ?>','ReloadDialog');" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="rhopagination" href="javascript:getAttchdTrnsRqstsDocs('next', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPayTrnsRqstsID=<?php echo $sbmtdPayTrnsRqstsID; ?>','ReloadDialog');" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered" id="attchdTrnsRqstsDocsTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
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
                                                    <img src="../cmn_images/dwldicon.png" style="height:15px; width:auto; position: relative; vertical-align: middle;"> Download
                                                </button>
                                            <?php } ?>
                                        </td>
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttchdTrnsRqstsDoc('attchdTrnsRqstsDocsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document">
                                                <img src="../cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
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
                                } else if ($vwtyp == 104) {
                                    //Get Selected Filter Details
                                    header("content-type:application/json");
                                    $payTrnsRqstsItmTypID = isset($_POST['payTrnsRqstsItmTypID']) ? (int) cleanInputData($_POST['payTrnsRqstsItmTypID']) : "";
                                    $payTrnsRqstsType = isset($_POST['payTrnsRqstsType']) ? cleanInputData($_POST['payTrnsRqstsType']) : "LOAN";
                                    $arr_content['FilterOptions'] = loadTypClsfctnOptions($payTrnsRqstsItmTypID, $payTrnsRqstsType);
                                    $errMsg = "Success";
                                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $errMsg;
                                    echo json_encode($arr_content);
                                    exit();
                                } else if ($vwtyp == 105) {
                                    //Get Selected Filter Details
                                    header("content-type:application/json");
                                    $payTrnsRqstsItmTypID = isset($_POST['payTrnsRqstsItmTypID']) ? (float) cleanInputData($_POST['payTrnsRqstsItmTypID']) : -1;
                                    $payTrnsRqstsType = isset($_POST['payTrnsRqstsType']) ? cleanInputData($_POST['payTrnsRqstsType']) : "";
                                    $lnkdPayTrnsRqstsID = isset($_POST['lnkdPayTrnsRqstsID']) ? (float) cleanInputData($_POST['lnkdPayTrnsRqstsID']) : -1;
                                    $payTrnsRqstsPrsnID = isset($_POST['payTrnsRqstsPrsnID']) ? (float) cleanInputData($_POST['payTrnsRqstsPrsnID']) : -1;
                                    $firstPayTrnsRqstsID = -1;
                                    $arr_content['FilterOptions'] = loadTypRqstsOptions($payTrnsRqstsItmTypID, $payTrnsRqstsPrsnID, $firstPayTrnsRqstsID);
                                    if ($lnkdPayTrnsRqstsID <= 0) {
                                        $lnkdPayTrnsRqstsID = $firstPayTrnsRqstsID;
                                    }
                                    $arr_content['DefaultAmnt'] = getLoanTypRqstsMxAmnt($payTrnsRqstsItmTypID, $lnkdPayTrnsRqstsID, $payTrnsRqstsPrsnID);
                                    $errMsg = "Success";
                                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $errMsg;
                                    echo json_encode($arr_content);
                                    exit();
                                } else if ($vwtyp == 11) {
    ?>
        <div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">My Payment Requests</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:openATab('#allmodules', 'grp=42&typ=1');">All Apps</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:openATab('#allmodules', 'grp=80&typ=1');">My Financials</a></li>
                            <li class="breadcrumb-item active">My Payment Requests</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content" style="padding: 10px 5px 10px 5px !important;">
            <div class="container-fluid">
            </div>
        </section>
<?php
                                }
                            }

                            function get_MyPyRnsDt($pyReqID, $mspyID, $prsnID)
                            {
                                $sqlStr = "SELECT tbl1.* FROM 
        (SELECT pymnt_req_id, payer_person_id, mass_pay_hdr_id, pymnt_req_hdr_id, 
       pymnt_trns_id, pay_item_id, org.get_payitm_nm(pay_item_id) itmNm, 
       a.amount_paid, 
       to_char(to_timestamp(a.payment_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') payment_date, 
       line_description,
       org.get_payitm_effct(pay_item_id) effct,
       org.get_payitm_mintyp(pay_item_id) mintyp
  FROM self.self_prsn_intrnl_pymnts a
  WHERE (a.pymnt_req_id = " . $pyReqID . " and payer_person_id = " . $prsnID . ") "
                                    . " UNION "
                                    . "SELECT -1, a.person_id, a.mass_pay_id, -1, 
                a.pay_trns_id, a.item_id, org.get_payitm_nm(a.item_id) itmNm, 
                a.amount_paid, 
                to_char(to_timestamp(a.paymnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') paymnt_date, 
                a.pymnt_desc,
       org.get_payitm_effct(a.item_id) effct,
       org.get_payitm_mintyp(a.item_id) mintyp
FROM pay.pay_itm_trnsctns a 
WHERE(a.mass_pay_id = " . $mspyID . " and person_id = " . $prsnID . "  and org.get_payitm_uom(a.item_id)='Money' and a.pymnt_vldty_status='VALID' and a.src_py_trns_id<=0)) tbl1 "
                                    . " ORDER BY tbl1.pymnt_trns_id ASC";
                                //echo $sqlStr;
                                $result = executeSQLNoParams($sqlStr);
                                return $result;
                            }

                            function get_CumltiveBals($prsnID)
                            {
                                $sqlStr = "SELECT DISTINCT -1, a.person_id, -1, -1, -1, a.bals_itm_id, b.item_code_name itmNm, "
                                    . "round(a.bals_amount, 2) amount_paid, "
                                    . "to_char(to_timestamp(a.bals_date|| ' 12:00:00','YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') balsdte, "
                                    . "b.item_code_name || (CASE WHEN b.item_code_name ilike '%Principal Balance%' THEN
                                    pay.get_tk_tied_to_frm_bls (a.person_id, b.item_code_name,' Principal Balance') ELSE '' END) item_code_name,"
                                    . "b.effct_on_org_debt, "
                                    . "b.item_maj_type,a.bals_date,b.pay_run_priority " .
                                    "FROM pay.pay_balsitm_bals a, org.org_pay_items b " .
                                    " WHERE((a.person_id = " . $prsnID . ") and (b.item_maj_type='Balance Item' "
                                    . "and b.balance_type='Cumulative' and a.bals_itm_id = b.item_id)"
                                    . " and (b.is_enabled = '1') and b.item_value_uom='Money' and a.bals_date = (SELECT MAX(c.bals_date) FROM pay.pay_balsitm_bals c "
                                    . "WHERE c.person_id = " . $prsnID . " and a.bals_itm_id = c.bals_itm_id) "
                                    . "and (CASE WHEN a.bals_amount!=0 THEN 1 ELSE 0 END)=1) "
                                    . "ORDER BY a.bals_date DESC, b.item_code_name, b.pay_run_priority";

                                /* 
                                 WHEN b.item_code_name ilike '%2016%' "
                                . "or b.item_code_name ilike '%2015%' "
                                . "or b.item_code_name ilike '%2014%' THEN 1 "
                                . " 
                                */

                                $result = executeSQLNoParams($sqlStr);
                                return $result;
                            }

                            function get_CumltiveBalAsAt($prsnID, $payItmID, $payBalsDte, $orgid)
                            {
                                $sqlStr = "SELECT pay.getblsitmltstdailybals(" . $payItmID . "," . $prsnID . ",'" . $payBalsDte . "'," . $orgid . ")";
                                $result = executeSQLNoParams($sqlStr);
                                while ($row = loc_db_fetch_array($result)) {
                                    return (float) $row[0];
                                }
                                return 0;
                            }

                            function getBatchItmTypCnt($pyReqID, $mspyID, $prsnID)
                            {
                                $sqlStr = "Select distinct org.get_payitm_mintyp(a.item_id) from pay.pay_itm_trnsctns a 
     WHERE(a.mass_pay_id = " . $mspyID . " and a.person_id = " . $prsnID . ")"
                                    . " UNION "
                                    . " Select distinct org.get_payitm_mintyp(a.pay_item_id) from self.self_prsn_intrnl_pymnts a 
     WHERE(a.pymnt_req_hdr_id = " . $pyReqID . " and a.payer_person_id = " . $prsnID . " and a.mass_pay_hdr_id<=0)";
                                $result = executeSQLNoParams($sqlStr);
                                return loc_db_num_rows($result);
                            }

                            function getBatchItmTypCnt1($mspyID)
                            {
                                $sqlStr = "Select distinct org.get_payitm_mintyp(a.item_id) from pay.pay_value_sets_det a 
     WHERE(a.mass_pay_id = " . $mspyID . ")";
                                $result = executeSQLNoParams($sqlStr);
                                return loc_db_num_rows($result);
                            }

                            $fnlColorAmntDffrnc = 0;

                            function getBatchNetAmnt($itmTypCnt, $itmTyp, $itmNm, $effctOnOrgDbt, $amnt, &$brghtTotal, &$prpsdTtlSpnColor)
                            {
                                /* Items Net Effect on Person's Organisational Debt
     * if(same itemtype in batch then + throughout)
     * Dues/Bills/Charges - (red) - increase
     * Dues/Bills/Charges Payments - (green) - decrease
     * 
     * Earnings - (green) - decrease
     * Payroll Deductions - (red) - increase
     * Payroll Staff Liability Balance - green - decrease
     * Employer Charges (None) (black)
     * Purely Informational (None) (black)
     * */
                                global $fnlColorAmntDffrnc;

                                $spnColor = "black";
                                $mltplr = "+";
                                /* if ($effctOnOrgDbt == "None") {
      $spnColor = "black";
      } else */
                                if ($effctOnOrgDbt == "Increase") {
                                    $spnColor = "red";
                                    $fnlColorAmntDffrnc = $fnlColorAmntDffrnc - $amnt;
                                    if ($itmTyp == "Bills/Charges") {
                                        $spnColor = "red";
                                        $brghtTotal = $brghtTotal + $amnt;
                                    } else {
                                        if ($itmTypCnt > 1 || $itmTyp == "Balance Item") {
                                            $mltplr = "-";
                                            $brghtTotal = $brghtTotal - $amnt;
                                        } else {
                                            $brghtTotal = $brghtTotal + $amnt;
                                        }
                                    }
                                } else if ($effctOnOrgDbt == "Decrease") {
                                    $spnColor = "green";
                                    $fnlColorAmntDffrnc = $fnlColorAmntDffrnc + $amnt;
                                    $brghtTotal = $brghtTotal + $amnt;
                                } else {
                                    if ($itmTyp == "Bills/Charges") {
                                        $spnColor = "red";
                                        $fnlColorAmntDffrnc = $fnlColorAmntDffrnc - $amnt;
                                        $brghtTotal = $brghtTotal + $amnt;
                                    } else if ($itmTyp == "Deductions") {
                                        if (strpos($itmNm, "(Payment)") !== FALSE || $itmNm == "Advance Payments Amount Kept") {
                                            $spnColor = "green";
                                            $fnlColorAmntDffrnc = $fnlColorAmntDffrnc + $amnt;
                                        } else {
                                            $spnColor = "red";
                                            $fnlColorAmntDffrnc = $fnlColorAmntDffrnc - $amnt;
                                        }
                                        if ($itmTypCnt > 1) {
                                            $mltplr = "-";
                                            $brghtTotal = $brghtTotal - $amnt;
                                        } else {
                                            $brghtTotal = $brghtTotal + $amnt;
                                        }
                                    } else if ($itmTyp == "Earnings") {
                                        $spnColor = "green";
                                        $fnlColorAmntDffrnc = $fnlColorAmntDffrnc + $amnt;
                                        if ($itmNm == "Advance Payments Amount Applied") {
                                            if ($itmTypCnt > 1) {
                                                $mltplr = "-";
                                                $brghtTotal = $brghtTotal - $amnt;
                                            } else {
                                                $brghtTotal = $brghtTotal + $amnt;
                                            }
                                        } else {
                                            $brghtTotal = $brghtTotal + $amnt;
                                        }
                                    } else {
                                        $spnColor = "black";
                                    }
                                }
                                if ($brghtTotal >= 0 && $fnlColorAmntDffrnc >= 0) {
                                    $prpsdTtlSpnColor = "green";
                                } else {
                                    $prpsdTtlSpnColor = "red";
                                }
                                if ($mltplr == "-") {
                                    return "<span style=\"color:$spnColor;\">" . number_format(round((float) (-1 * $amnt), 2), 2) . "</span>";
                                } else {
                                    return "<span style=\"color:$spnColor;\">" . number_format($amnt, 2) . "</span>";
                                }
                            }

                            function get_MyPyHdrDet($pyReqID, $mspyID, $prsnID)
                            {
                                $strSql = "SELECT tbl1.* FROM (
        SELECT -1 pay_red_hdr_id, -1 mass_pay_hdr_id, 
        'CUMULATIVE BALANCES' mspy_name, 
        'ALL-TIME OUTSTANDING BALANCES', 
        -1 wkfMsgID,
        'Completed Succesfully' status, 
        to_char(now(),'DD-Mon-YYYY HH24:MI:SS') pay_date, 
        '' attachments "
                                    . " UNION "
                                    . " SELECT -1 pay_red_hdr_id, a.mass_pay_id, 
        REPLACE((CASE WHEN a.mass_pay_id<=0 THEN 'Manual/Direct Payment' ELSE a.mass_pay_name END),'Quick Pay Run','Quick Run of Items') mspy_name, 
        REPLACE((CASE WHEN a.mass_pay_desc!='' THEN a.mass_pay_desc ELSE (CASE WHEN a.mass_pay_id<=0 THEN 'Manual/Direct Payment' ELSE a.mass_pay_name END) END),'Quick Pay Run','Quick Run of Items'), 
        -1 wkfMsgID,
        CASE WHEN a.run_status='1' AND a.sent_to_gl='1' THEN 'Completed Succesfully' ELSE 'Incomplete' END status, 
        to_char(to_timestamp(a.mass_pay_trns_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') pay_date, '' attachments 
      FROM pay.pay_mass_pay_run_hdr a 
      WHERE (((Select count(1) from pay.pay_itm_trnsctns z where z.person_id = " . $prsnID .
                                    " and z.mass_pay_id = a.mass_pay_id and z.pymnt_vldty_status='VALID' and z.src_py_trns_id<=0)>=1) AND (org_id = " . $_SESSION['ORG_ID'] .
                                    ")) "
                                    . " UNION "
                                    . "SELECT pymnt_req_hdr_id, mass_pay_hdr_id, ''||pymnt_req_hdr_id, pymnt_req_hdr_desc, 
       wkf_msg_id, status, to_char(to_timestamp(a.payment_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), attachments
  FROM self.self_prsn_intrnl_pymnts_hdr a 
  WHERE (((Select count(1) from self.self_prsn_intrnl_pymnts z where z.payer_person_id = " . $prsnID .
                                    " and z.pymnt_req_hdr_id = a.pymnt_req_hdr_id)>=1))) tbl1 "
                                    . "WHERE tbl1.pay_red_hdr_id=$pyReqID and tbl1.mass_pay_hdr_id =$mspyID ";
                                $result = executeSQLNoParams($strSql);
                                return $result;
                            }

                            function get_MyPyRnsTblr($searchFor, $searchIn, $offset, $limit_size, $prsnID, $recCnt = 1)
                            {
                                if ($prsnID <= 0) {
                                    $recCnt = 0;
                                }
                                $wherecls = "";
                                $wherecls1 = "";
                                if ($searchIn === "Name/Number") {
                                    $wherecls = "(a.mass_pay_name ilike '" .
                                        loc_db_escape_string($searchFor) . "' or a.mass_pay_desc ilike '" .
                                        loc_db_escape_string($searchFor) . "') and ";
                                    $wherecls1 = "('' || a.pymnt_req_hdr_id ilike '" .
                                        loc_db_escape_string($searchFor) . "' or a.pymnt_req_hdr_desc ilike '" .
                                        loc_db_escape_string($searchFor) . "') and ";
                                }
                                $strSql = "SELECT tbl1.* FROM (
        SELECT -1 pay_red_hdr_id, -1, 
        'CUMULATIVE BALANCES' mspy_name, 
        'ALL TIME ITEM BALANCES', 
        -1 wkfMsgID,
        'Completed Succesfully' status, 
        to_char(now(),'YYYY-MM-DD HH24:MI:SS') pay_date, 
        '' attachments,-1,-1,'1','','', '0' "
                                    . " UNION "
                                    . " SELECT -1 pay_red_hdr_id, 
                a.mass_pay_id, 
        CASE WHEN a.mass_pay_id<=0 THEN 'Manual/Direct Payment' ELSE a.mass_pay_name END mspy_name, 
        a.mass_pay_desc, 
        -1 wkfMsgID,
        CASE WHEN a.run_status='1' AND a.sent_to_gl='1' THEN 'Completed Succesfully' ELSE 'Incomplete' END status, 
        a.mass_pay_trns_date pay_date, 
        '' attachments,
        a.prs_st_id,
        a.itm_st_id,
        pay.get_prs_st_name(a.prs_st_id),
        pay.get_itm_st_name(a.itm_st_id),
        a.run_status,
        a.sent_to_gl
      FROM pay.pay_mass_pay_run_hdr a 
      WHERE ($wherecls((Select count(1) from pay.pay_itm_trnsctns z where z.person_id = " . $prsnID .
                                    " and z.mass_pay_id = a.mass_pay_id and z.pymnt_vldty_status='VALID' and z.src_py_trns_id<=0) >= " . $recCnt .
                                    ") AND (org_id = " . $_SESSION['ORG_ID'] . ")) "
                                    . " UNION "
                                    . "SELECT pymnt_req_hdr_id, mass_pay_hdr_id, ''||pymnt_req_hdr_id, pymnt_req_hdr_desc, 
       wkf_msg_id, status, payment_date, attachments,-1,-1,'','', CASE WHEN mass_pay_hdr_id>0 THEN '1' ELSE '0' END,COALESCE((select z.sent_to_gl from pay.pay_mass_pay_run_hdr z where z.mass_pay_id=a.mass_pay_hdr_id),'0')
  FROM self.self_prsn_intrnl_pymnts_hdr a 
  WHERE ($wherecls1((Select count(1) from self.self_prsn_intrnl_pymnts z where z.payer_person_id = " . $prsnID .
                                    " and z.pymnt_req_hdr_id = a.pymnt_req_hdr_id)>=" . $recCnt . ") and a.mass_pay_hdr_id<=0)) tbl1 "
                                    . "ORDER BY tbl1.pay_date DESC LIMIT " . $limit_size .
                                    " OFFSET " . abs($offset * $limit_size);
                                //echo $strSql;
                                $result = executeSQLNoParams($strSql);
                                return $result;
                            }

                            function get_MyPyRnsTtl($searchFor, $searchIn, $prsnID, $recCnt = 1)
                            {
                                if ($prsnID <= 0) {
                                    $recCnt = 0;
                                }
                                $wherecls = "";
                                $wherecls1 = "";
                                //"Message Header", "Message Date", "Message Status", "Source App", "Source Module"
                                if ($searchIn === "Name/Number") {
                                    $wherecls = "(a.mass_pay_name ilike '" .
                                        loc_db_escape_string($searchFor) . "' or a.mass_pay_desc ilike '" .
                                        loc_db_escape_string($searchFor) . "') and ";
                                    $wherecls1 = "('' || a.pymnt_req_hdr_id ilike '" .
                                        loc_db_escape_string($searchFor) . "' or a.pymnt_req_hdr_desc ilike '" .
                                        loc_db_escape_string($searchFor) . "') and ";
                                }

                                $strSql = "SELECT count(1) FROM (
        SELECT -1 pay_red_hdr_id, -1, 
        'CUMULATIVE BALANCES' mspy_name, 
        'ALL TIME ITEM BALANCES', 
        -1 wkfMsgID,
        'Completed Succesfully' status, 
        to_char(now(),'YYYY-MM-DD HH24:MI:SS') pay_date, 
        '' attachments,-1,-1,'1','','', '0' "
                                    . " UNION "
                                    . " SELECT -1 pay_red_hdr_id, 
                a.mass_pay_id, 
        CASE WHEN a.mass_pay_id<=0 THEN 'Manual/Direct Payment' ELSE a.mass_pay_name END mspy_name, 
        a.mass_pay_desc, 
        -1 wkfMsgID,
        CASE WHEN a.run_status='1' AND a.sent_to_gl='1' THEN 'Completed Succesfully' ELSE 'Incomplete' END status, 
        a.mass_pay_trns_date pay_date, 
        '' attachments,
        a.prs_st_id,
        a.itm_st_id,
        pay.get_prs_st_name(a.prs_st_id),
        pay.get_itm_st_name(a.itm_st_id),
        a.run_status,
        a.sent_to_gl
      FROM pay.pay_mass_pay_run_hdr a 
      WHERE ($wherecls((Select count(1) from pay.pay_itm_trnsctns z where z.person_id = " . $prsnID .
                                    " and z.mass_pay_id = a.mass_pay_id and z.pymnt_vldty_status='VALID' and z.src_py_trns_id<=0)>=" . $recCnt . ") AND (org_id = " . $_SESSION['ORG_ID'] .
                                    ")) "
                                    . " UNION "
                                    . "SELECT pymnt_req_hdr_id, mass_pay_hdr_id, ''||pymnt_req_hdr_id, pymnt_req_hdr_desc, 
       wkf_msg_id, status, payment_date, attachments,-1,-1,'','', CASE WHEN mass_pay_hdr_id>0 THEN '1' ELSE '0' END,
       COALESCE((select z.sent_to_gl from pay.pay_mass_pay_run_hdr z where z.mass_pay_id=a.mass_pay_hdr_id),'0')
  FROM self.self_prsn_intrnl_pymnts_hdr a 
  WHERE ($wherecls1((Select count(1) from self.self_prsn_intrnl_pymnts z where z.payer_person_id = " . $prsnID .
                                    " and z.pymnt_req_hdr_id = a.pymnt_req_hdr_id)>=" . $recCnt . ") and a.mass_pay_hdr_id<=0)) tbl1 ";
                                $result = executeSQLNoParams($strSql);
                                while ($row = loc_db_fetch_array($result)) {
                                    return $row[0];
                                }
                                return 0;
                            }

                            function get_Basic_QuickPy($searchWord, $searchIn, $offset, $limit_size, $prsnID)
                            {
                                $strSql = "";
                                $whereCls = "";
                                if ($searchIn == "Mass Pay Run Name") {
                                    $whereCls = "(a.mass_pay_name ilike '" . loc_db_escape_string($searchWord) .
                                        "' or a.mass_pay_id<=0)and ";
                                } else if ($searchIn == "Mass Pay Run Description") {
                                    $whereCls = "(a.mass_pay_desc ilike '" . loc_db_escape_string($searchWord) .
                                        "' or a.mass_pay_id<=0) and ";
                                }

                                $strSql = "SELECT a.mass_pay_id, CASE WHEN a.mass_pay_id<=0 THEN 'Manual/Direct Payment' ELSE a.mass_pay_name END, a.mass_pay_desc, a.run_status, 
        to_char(to_timestamp(a.mass_pay_trns_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS')
      , a.prs_st_id, a.itm_st_id, a.sent_to_gl, to_char(to_timestamp(a.gl_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') 
      FROM pay.pay_mass_pay_run_hdr a WHERE (($whereCls(Select count(1) from pay.pay_itm_trnsctns z where z.person_id = " . $prsnID .
                                    " and z.mass_pay_id = a.mass_pay_id)>=1) AND (org_id = " . $_SESSION['ORG_ID'] .
                                    ") AND (prs_st_id<=0)) ORDER BY a.mass_pay_id DESC LIMIT " . $limit_size .
                                    " OFFSET " . abs($offset * $limit_size);
                                $result = executeSQLNoParams($strSql);
                                return $result;
                            }

                            function get_One_MsPyDet($offset, $limit_size, $mspyid)
                            {
                                $strSql = "SELECT a.pay_trns_id, a.person_id, a.item_id, a.amount_paid, 
to_char(to_timestamp(a.paymnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS')
, a.paymnt_source, a.pay_trns_type, a.pymnt_desc, -1, a.crncy_id, c.local_id_no, trim(c.title || ' ' || c.sur_name || 
', ' || c.first_name || ' ' || c.other_names) fullname, b.item_code_name, a.pymnt_vldty_status, gst.get_pssbl_val(a.crncy_id) cur 
FROM (pay.pay_itm_trnsctns a LEFT OUTER JOIN org.org_pay_items b ON a.item_id = b.item_id) 
LEFT OUTER JOIN prs.prsn_names_nos c on a.person_id = c.person_id 
WHERE(a.mass_pay_id = " . $mspyid . ") ORDER BY a.pay_trns_id LIMIT " . $limit_size .
                                    " OFFSET " . abs($offset * $limit_size);

                                $result = executeSQLNoParams($strSql);
                                /* @var $result type */
                                return $result;
                            }

                            function get_One_MsPyDet2($searchWord, $searchIn, $offset, $limit_size, $mspyid)
                            {
                                $whereCls = "";
                                if ($searchIn == "Person Name/ID No.") {
                                    $whereCls = "(c.local_id_no ilike '" . loc_db_escape_string($searchWord) .
                                        "' or trim(c.title || ' ' || c.sur_name || 
', ' || c.first_name || ' ' || c.other_names) ilike '" . loc_db_escape_string($searchWord) .
                                        "') and ";
                                } else if ($searchIn == "Item Name") {
                                    $whereCls = "(b.item_code_name ilike '" . loc_db_escape_string($searchWord) .
                                        "') and ";
                                }

                                $strSql = "SELECT a.pay_trns_id, a.person_id, a.item_id, a.amount_paid, 
to_char(to_timestamp(a.paymnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS')
, a.paymnt_source, a.pay_trns_type, a.pymnt_desc, -1, a.crncy_id, c.local_id_no, trim(c.title || ' ' || c.sur_name || 
', ' || c.first_name || ' ' || c.other_names) fullname, b.item_code_name,
a.pymnt_vldty_status, (CASE WHEN coalesce(b.item_value_uom,'')='Money' THEN gst.get_pssbl_val(a.crncy_id) ELSE substr(b.item_value_uom,1,3) END) cur, b.item_min_type,
       b.effct_on_org_debt
FROM (pay.pay_itm_trnsctns a LEFT OUTER JOIN org.org_pay_items b ON a.item_id = b.item_id) 
LEFT OUTER JOIN prs.prsn_names_nos c on a.person_id = c.person_id 
WHERE " . $whereCls . "(a.mass_pay_id = " . $mspyid . ") ORDER BY c.local_id_no, b.pay_run_priority, a.pay_trns_id LIMIT " . $limit_size .
                                    " OFFSET " . abs($offset * $limit_size);
                                //echo $strSql;
                                $result = executeSQLNoParams($strSql);
                                return $result;
                            }

                            function get_One_MsPyDetTtl2($searchWord, $searchIn, $mspyid)
                            {
                                $whereCls = "";
                                if ($searchIn == "Person Name/ID No.") {
                                    $whereCls = "(c.local_id_no ilike '" . loc_db_escape_string($searchWord) .
                                        "' or trim(c.title || ' ' || c.sur_name || 
', ' || c.first_name || ' ' || c.other_names) ilike '" . loc_db_escape_string($searchWord) .
                                        "') and ";
                                } else if ($searchIn == "Item Name") {
                                    $whereCls = "(b.item_code_name ilike '" . loc_db_escape_string($searchWord) .
                                        "') and ";
                                }

                                $strSql = "SELECT count(1)  
FROM (pay.pay_itm_trnsctns a LEFT OUTER JOIN org.org_pay_items b ON a.item_id = b.item_id) 
LEFT OUTER JOIN prs.prsn_names_nos c on a.person_id = c.person_id 
WHERE " . $whereCls . "(a.mass_pay_id = " . $mspyid . ")";
                                $result = executeSQLNoParams($strSql);
                                while ($row = loc_db_fetch_array($result)) {
                                    return $row[0];
                                }
                                return 0;
                            }

                            function get_RcvblsDocHdr2($searchWord, $searchIn, $offset, $limit_size, $orgID, $shwUnpstdOnly, $cstmrID = -1)
                            {
                                $strSql = "";
                                $whrcls = "";
                                /* Document Number
      Document Description
      Document Classification
      Customer Name
      Customer's Doc. Number
      Source Doc Number
      Approval Status
      Created By
      Currency */
                                $unpstdCls = "";
                                if ($shwUnpstdOnly) {
                                    $unpstdCls = " AND (round(a.invoice_amount-a.amnt_paid,2)>0 or a.approval_status IN ('Not Validated','Validated','Reviewed'))";
                                }
                                if ($searchIn == "Document Number") {
                                    $whrcls = " and (a.rcvbls_invc_number ilike '" . loc_db_escape_string($searchWord) . "' or trim(to_char(a.rcvbls_invc_hdr_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
                                        "')";
                                } else if ($searchIn == "Document Description") {
                                    $whrcls = " and (a.comments_desc ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Document Classification") {
                                    $whrcls = " and (a.doc_tmplt_clsfctn ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Customer Name") {
                                    $whrcls = " and (a.customer_id IN (select c.cust_sup_id from 
scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
                                        "'))";
                                } else if ($searchIn == "Customer's Doc. Number") {
                                    $whrcls = " and (a.cstmrs_doc_num ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Source Doc Number") {
                                    $whrcls = " and (a.src_doc_hdr_id IN (select d.invc_hdr_id from scm.scm_sales_invc_hdr d 
where d.invc_number ilike '" . loc_db_escape_string($searchWord) .
                                        "') or a.src_doc_hdr_id IN (select f.rcvbls_invc_hdr_id from accb.accb_rcvbls_invc_hdr f
where f.rcvbls_invc_number ilike '" . loc_db_escape_string($searchWord) .
                                        "'))";
                                } else if ($searchIn == "Approval Status") {
                                    $whrcls = " and (a.approval_status ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Created By") {
                                    $whrcls = " and (sec.get_usr_name(a.created_by) ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Currency") {
                                    $whrcls = " and (gst.get_pssbl_val(a.invc_curr_id) ilike '" . loc_db_escape_string($searchWord) . "')";
                                }
                                $whrcls .= " and (a.customer_id IN ($cstmrID))";
                                $strSql = "SELECT rcvbls_invc_hdr_id, rcvbls_invc_number, 
rcvbls_invc_type, round(a.invoice_amount-a.amnt_paid,2),
 a.approval_status, a.src_doc_hdr_id, a.src_doc_type
        FROM accb.accb_rcvbls_invc_hdr a 
        WHERE((a.org_id = " . $orgID . ")" . $whrcls . $unpstdCls .
                                    ") ORDER BY rcvbls_invc_hdr_id DESC LIMIT " . $limit_size .
                                    " OFFSET " . abs($offset * $limit_size);

                                $result = executeSQLNoParams($strSql);
                                return $result;
                            }

                            function get_RcvblsDocHdrTtl2($searchWord, $searchIn, $orgID, $shwUnpstdOnly, $cstmrID = -1)
                            {
                                $strSql = "";
                                $whrcls = "";
                                /* Document Number
      Document Description
      Document Classification
      Customer Name
      Customer's Doc. Number
      Source Doc Number
      Approval Status
      Created By
      Currency */
                                $unpstdCls = "";
                                if ($shwUnpstdOnly) {
                                    $unpstdCls = " AND (round(a.invoice_amount-a.amnt_paid,2)>0 or a.approval_status IN ('Not Validated','Validated','Reviewed'))";
                                }
                                if ($searchIn == "Document Number") {
                                    $whrcls = " and (a.rcvbls_invc_number ilike '" . loc_db_escape_string($searchWord) . "' or trim(to_char(a.rcvbls_invc_hdr_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
                                        "')";
                                } else if ($searchIn == "Document Description") {
                                    $whrcls = " and (a.comments_desc ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Document Classification") {
                                    $whrcls = " and (a.doc_tmplt_clsfctn ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Customer Name") {
                                    $whrcls = " and (a.customer_id IN (select c.cust_sup_id from 
scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
                                        "'))";
                                } else if ($searchIn == "Customer's Doc. Number") {
                                    $whrcls = " and (a.cstmrs_doc_num ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Source Doc Number") {
                                    $whrcls = " and (a.src_doc_hdr_id IN (select d.invc_hdr_id from scm.scm_sales_invc_hdr d 
where d.invc_number ilike '" . loc_db_escape_string($searchWord) .
                                        "') or a.src_doc_hdr_id IN (select f.rcvbls_invc_hdr_id from accb.accb_rcvbls_invc_hdr f
where f.rcvbls_invc_number ilike '" . loc_db_escape_string($searchWord) .
                                        "'))";
                                } else if ($searchIn == "Approval Status") {
                                    $whrcls = " and (a.approval_status ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Created By") {
                                    $whrcls = " and (sec.get_usr_name(a.created_by) ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Currency") {
                                    $whrcls = " and (gst.get_pssbl_val(a.invc_curr_id) ilike '" . loc_db_escape_string($searchWord) . "')";
                                }
                                $whrcls .= " and (a.customer_id IN ($cstmrID))";
                                $strSql = "SELECT count(1) 
        FROM accb.accb_rcvbls_invc_hdr a 
        WHERE((a.org_id = " . $orgID . ")" . $whrcls . $unpstdCls .
                                    ")";

                                $result = executeSQLNoParams($strSql);
                                while ($row = loc_db_fetch_array($result)) {
                                    return $row[0];
                                }
                                return 0;
                            }

                            function get_One_RcvblsDocHdr($hdrID)
                            {
                                $strSql = "";

                                $strSql = "SELECT rcvbls_invc_hdr_id, 
        to_char(to_timestamp(rcvbls_invc_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), 
       created_by, 
       sec.get_usr_name(a.created_by), 
       rcvbls_invc_number, 
       rcvbls_invc_type, 
       comments_desc, 
       src_doc_hdr_id, 
       a.src_doc_type, 
       customer_id, 
       scm.get_cstmr_splr_name(a.customer_id),
       customer_site_id, 
       scm.get_cstmr_splr_site_name(a.customer_site_id), 
       approval_status, 
       next_aproval_action, 
       invoice_amount, 
       payment_terms, 
       pymny_method_id, 
       accb.get_pymnt_mthd_name(a.pymny_method_id), 
       amnt_paid, 
       gl_batch_id, 
       accb.get_gl_batch_name(a.gl_batch_id),
       cstmrs_doc_num, 
       doc_tmplt_clsfctn, 
       invc_curr_id, 
       gst.get_pssbl_val(a.invc_curr_id), 
       scm.get_src_doc_num(a.src_doc_hdr_id, a.src_doc_type),
        event_rgstr_id, 
        evnt_cost_category, 
        event_doc_type,
        a.invc_amnt_appld_elswhr,
        CASE WHEN a.event_doc_type='Attendance Register' and a.event_rgstr_id>0 THEN 
            (select z.recs_hdr_name from attn.attn_attendance_recs_hdr z where z.recs_hdr_id=a.event_rgstr_id)
            WHEN a.event_doc_type='Production Process Run' and a.event_rgstr_id>0 THEN 
            (select z.batch_code_num from scm.scm_process_run z where z.process_run_id=a.event_rgstr_id)
            ELSE 
            ''
        END event_num
  FROM accb.accb_rcvbls_invc_hdr a " .
                                    "WHERE((a.rcvbls_invc_hdr_id = " . $hdrID . "))";
                                $result = executeSQLNoParams($strSql);
                                return $result;
                            }

                            function get_RcvblsDocLines($docHdrID)
                            {
                                $strSql = "";
                                $whrcls = " and (a.rcvbl_smmry_type !='6Grand Total' and 
a.rcvbl_smmry_type !='7Total Payments Made' and a.rcvbl_smmry_type !='8Outstanding Balance')";
                                $strSql = "SELECT 
        rcvbl_smmry_id, 
        rcvbl_smmry_type, 
        rcvbl_smmry_desc, 
        rcvbl_smmry_amnt, 
       code_id_behind, 
       auto_calc, 
       incrs_dcrs1, 
       rvnu_acnt_id, 
       incrs_dcrs2, 
       rcvbl_acnt_id, 
       appld_prepymnt_doc_id, 
       entrd_curr_id, gst.get_pssbl_val(a.entrd_curr_id), 
       func_curr_id, gst.get_pssbl_val(a.func_curr_id), 
      accnt_curr_id, gst.get_pssbl_val(a.accnt_curr_id), 
      func_curr_rate, accnt_curr_rate, 
       func_curr_amount, accnt_curr_amnt, initial_amnt_line_id, 
       REPLACE(REPLACE(a.rcvbl_smmry_type,'2Tax','3Tax'),'3Discount','2Discount') smtyp 
  FROM accb.accb_rcvbl_amnt_smmrys a " .
                                    "WHERE((a.src_rcvbl_hdr_id = " . $docHdrID . ")" . $whrcls . ") ORDER BY 23 ASC ";
                                $result = executeSQLNoParams($strSql);
                                return $result;
                            }

                            function get_One_SalesDcLines($dochdrID)
                            {
                                $strSql = "SELECT a.invc_det_ln_id, a.itm_id, 
              a.doc_qty, a.unit_selling_price, (a.doc_qty * a.unit_selling_price) amnt, 
              a.store_id, a.crncy_id, (a.doc_qty - a.qty_trnsctd_in_dest_doc) avlbl_qty, 
              a.src_line_id, a.tax_code_id, a.dscnt_code_id, a.chrg_code_id, a.rtrn_reason, 
              a.consgmnt_ids, a.orgnl_selling_price, b.base_uom_id, b.item_code, b.item_desc, 
      c.uom_name, a.is_itm_delivered, REPLACE(a.extra_desc || ' (' || a.other_mdls_doc_type || ')',' ()','')
        , a.other_mdls_doc_id, a.other_mdls_doc_type, a.lnkd_person_id, 
      REPLACE(prs.get_prsn_surname(a.lnkd_person_id) || ' (' 
      || prs.get_prsn_loc_id(a.lnkd_person_id) || ')', ' ()', '') fullnm, 
      CASE WHEN a.alternate_item_name='' THEN b.item_desc ELSE a.alternate_item_name END, d.cat_name " .
                                    "FROM scm.scm_sales_invc_det a, inv.inv_itm_list b, inv.unit_of_measure c, inv.inv_product_categories d " .
                                    "WHERE(a.invc_hdr_id = " . $dochdrID .
                                    " and a.invc_hdr_id>0 and a.itm_id = b.item_id and b.base_uom_id = c.uom_id and d.cat_id = b.category_id) ORDER BY a.invc_det_ln_id, b.category_id";
                                $result = executeSQLNoParams($strSql);
                                return $result;
                            }

                            function get_PyblsDocSmryLns($dochdrID, $docTyp)
                            {
                                $whrcls = " and (a.pybls_smmry_type IN ('6Grand Total','7Total Payments Made','8Outstanding Balance'))";
                                $strSql = "SELECT a.pybls_smmry_id, a.pybls_smmry_desc, 
             a.pybls_smmry_amnt, a.code_id_behind, a.pybls_smmry_type, a.auto_calc 
             FROM accb.accb_pybls_amnt_smmrys a 
             WHERE((a.src_pybls_hdr_id = " . $dochdrID .
                                    ") and (a.src_pybls_type='" . $docTyp . "')$whrcls) ORDER BY a.pybls_smmry_type";
                                $result = executeSQLNoParams($strSql);
                                return $result;
                            }

                            function get_RcvblsDocSmryLns($dochdrID, $docTyp)
                            {
                                $whrcls = " and (a.rcvbl_smmry_type IN ('6Grand Total','7Total Payments Made','8Outstanding Balance'))";
                                $strSql = "SELECT a.rcvbl_smmry_id, a.rcvbl_smmry_desc, 
             a.rcvbl_smmry_amnt, a.code_id_behind, a.rcvbl_smmry_type, a.auto_calc 
             FROM accb.accb_rcvbl_amnt_smmrys a 
             WHERE((a.src_rcvbl_hdr_id = " . $dochdrID .
                                    ") and (a.src_rcvbl_type='" . $docTyp . "')$whrcls) ORDER BY a.rcvbl_smmry_type";
                                $result = executeSQLNoParams($strSql);
                                //echo $strSql;
                                return $result;
                            }

                            function get_SalesDocSmryLns($dochdrID, $docTyp)
                            {
                                $strSql = "SELECT a.smmry_id, CASE WHEN a.smmry_type='3Discount' THEN 'Discount' ELSE a.smmry_name END, 
             a.smmry_amnt, a.code_id_behind, a.smmry_type, a.auto_calc,REPLACE(REPLACE(a.smmry_type,'2Tax','3Tax'),'3Discount','2Discount') smtyp 
             FROM scm.scm_doc_amnt_smmrys a 
             WHERE((a.src_doc_hdr_id = " . $dochdrID . "
             ) and (a.src_doc_type='" . $docTyp . "')) ORDER BY 7";
                                $result = executeSQLNoParams($strSql);
                                //echo $strSql;
                                return $result;
                            }

                            function get_PyblsDocHdr2($searchWord, $searchIn, $offset, $limit_size, $orgID, $shwUnpstdOnly, $cstmrID = -1)
                            {
                                $strSql = "";
                                $whrcls = "";
                                $unpstdCls = "";
                                if ($shwUnpstdOnly) {
                                    $unpstdCls = " AND (round(a.invoice_amount-a.amnt_paid,2)>0 or a.approval_status IN ('Not Validated','Validated','Reviewed'))";
                                }
                                if ($searchIn == "Document Number") {
                                    $whrcls = " and (a.pybls_invc_number ilike '" . loc_db_escape_string($searchWord) . "' or trim(to_char(a.pybls_invc_hdr_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
                                        "')";
                                } else if ($searchIn == "Document Description") {
                                    $whrcls = " and (a.comments_desc ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Document Classification") {
                                    $whrcls = " and (a.doc_tmplt_clsfctn ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Supplier Name") {
                                    $whrcls = " and (a.customer_id IN (select c.cust_sup_id from 
scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
                                        "'))";
                                } else if ($searchIn == "Supplier's Invoice Number") {
                                    $whrcls = " and (a.spplrs_invc_num ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Source Doc Number") {
                                    $whrcls = "  and (trim(to_char(a.src_doc_hdr_id, '9999999999999999999999999')) 
IN (select trim(to_char(d.rcpt_id, '9999999999999999999999999')) from inv.inv_consgmt_rcpt_hdr d 
where trim(to_char(d.rcpt_id, '9999999999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
                                        "') or trim(to_char(a.src_doc_hdr_id, '9999999999999999999999999')) 
IN (select trim(to_char(e.rcpt_rtns_id, '9999999999999999999999999')) from inv.inv_consgmt_rcpt_rtns_hdr e 
where trim(to_char(e.rcpt_rtns_id, '9999999999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
                                        "') or a.src_doc_hdr_id IN (select f.pybls_invc_hdr_id from accb.accb_pybls_invc_hdr f
where f.pybls_invc_number ilike '" . loc_db_escape_string($searchWord) .
                                        "'))";
                                } else if ($searchIn == "Approval Status") {
                                    $whrcls = " and (a.approval_status ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Created By") {
                                    $whrcls = " and (sec.get_usr_name(a.created_by) ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Currency") {
                                    $whrcls = " and (gst.get_pssbl_val(a.invc_curr_id) ilike '" . loc_db_escape_string($searchWord) . "')";
                                }
                                $whrcls .= " and (a.supplier_id IN ($cstmrID))";

                                $strSql = "SELECT pybls_invc_hdr_id, pybls_invc_number, pybls_invc_type
, round(a.invoice_amount-a.amnt_paid,2),
 a.approval_status 
        FROM accb.accb_pybls_invc_hdr a 
        WHERE((a.org_id = " . $orgID . ")" . $whrcls . $unpstdCls .
                                    ") ORDER BY pybls_invc_hdr_id DESC LIMIT " . $limit_size .
                                    " OFFSET " . abs($offset * $limit_size);

                                $result = executeSQLNoParams($strSql);
                                return $result;
                            }

                            function get_PyblsDocHdrTtl2($searchWord, $searchIn, $orgID, $shwUnpstdOnly, $cstmrID = -1)
                            {
                                $strSql = "";
                                $whrcls = "";
                                $unpstdCls = "";
                                if ($shwUnpstdOnly) {
                                    $unpstdCls = " AND (round(a.invoice_amount-a.amnt_paid,2)>0 or a.approval_status IN ('Not Validated','Validated','Reviewed'))";
                                }
                                if ($searchIn == "Document Number") {
                                    $whrcls = " and (a.pybls_invc_number ilike '" . loc_db_escape_string($searchWord) . "' or trim(to_char(a.pybls_invc_hdr_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
                                        "')";
                                } else if ($searchIn == "Document Description") {
                                    $whrcls = " and (a.comments_desc ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Document Classification") {
                                    $whrcls = " and (a.doc_tmplt_clsfctn ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Supplier Name") {
                                    $whrcls = " and (a.customer_id IN (select c.cust_sup_id from 
scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
                                        "'))";
                                } else if ($searchIn == "Supplier's Invoice Number") {
                                    $whrcls = " and (a.spplrs_invc_num ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Source Doc Number") {
                                    $whrcls = "  and (trim(to_char(a.src_doc_hdr_id, '9999999999999999999999999')) 
IN (select trim(to_char(d.rcpt_id, '9999999999999999999999999')) from inv.inv_consgmt_rcpt_hdr d 
where trim(to_char(d.rcpt_id, '9999999999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
                                        "') or trim(to_char(a.src_doc_hdr_id, '9999999999999999999999999')) 
IN (select trim(to_char(e.rcpt_rtns_id, '9999999999999999999999999')) from inv.inv_consgmt_rcpt_rtns_hdr e 
where trim(to_char(e.rcpt_rtns_id, '9999999999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
                                        "') or a.src_doc_hdr_id IN (select f.pybls_invc_hdr_id from accb.accb_pybls_invc_hdr f
where f.pybls_invc_number ilike '" . loc_db_escape_string($searchWord) .
                                        "'))";
                                } else if ($searchIn == "Approval Status") {
                                    $whrcls = " and (a.approval_status ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Created By") {
                                    $whrcls = " and (sec.get_usr_name(a.created_by) ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Currency") {
                                    $whrcls = " and (gst.get_pssbl_val(a.invc_curr_id) ilike '" . loc_db_escape_string($searchWord) . "')";
                                }
                                $whrcls .= " and (a.supplier_id IN ($cstmrID))";
                                $strSql = "SELECT count(1) 
        FROM accb.accb_pybls_invc_hdr a 
        WHERE((a.org_id = " . $orgID . ")" . $whrcls . $unpstdCls .
                                    ")";

                                $result = executeSQLNoParams($strSql);
                                while ($row = loc_db_fetch_array($result)) {
                                    return $row[0];
                                }
                                return 0;
                            }

                            function get_One_PyblsDocHdr($hdrID)
                            {
                                $strSql = "";

                                $strSql = "SELECT pybls_invc_hdr_id, 
        to_char(to_timestamp(pybls_invc_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), 
       created_by, 
       sec.get_usr_name(a.created_by), 
       pybls_invc_number, 
       pybls_invc_type, 
       comments_desc, 
       src_doc_hdr_id, 
       a.src_doc_type, 
       supplier_id, 
       scm.get_cstmr_splr_name(a.supplier_id),
       supplier_site_id, 
       scm.get_cstmr_splr_site_name(a.supplier_site_id), 
       approval_status, 
       next_aproval_action, 
       invoice_amount, 
       payment_terms, 
       pymny_method_id, 
       accb.get_pymnt_mthd_name(a.pymny_method_id), 
       amnt_paid, 
       gl_batch_id, 
       accb.get_gl_batch_name(a.gl_batch_id),
       spplrs_invc_num, 
       doc_tmplt_clsfctn, 
       invc_curr_id, 
       gst.get_pssbl_val(a.invc_curr_id), 
       scm.get_src_doc_num(a.src_doc_hdr_id, a.src_doc_type),
        event_rgstr_id, 
        evnt_cost_category, 
        event_doc_type,
        a.invc_amnt_appld_elswhr,
        CASE WHEN a.event_doc_type='Attendance Register' and a.event_rgstr_id>0 THEN 
            (select z.recs_hdr_name from attn.attn_attendance_recs_hdr z where z.recs_hdr_id=a.event_rgstr_id)
            WHEN a.event_doc_type='Production Process Run' and a.event_rgstr_id>0 THEN 
            (select z.batch_code_num from scm.scm_process_run z where z.process_run_id=a.event_rgstr_id)
            ELSE 
            ''
        END event_num
  FROM accb.accb_pybls_invc_hdr a " .
                                    "WHERE((a.pybls_invc_hdr_id = " . $hdrID . "))";
                                $result = executeSQLNoParams($strSql);
                                return $result;
                            }

                            function get_PyblsDocLines($docHdrID)
                            {
                                $strSql = "";
                                $whrcls = " and (a.pybls_smmry_type !='6Grand Total' and 
a.pybls_smmry_type !='7Total Payments Made' and a.pybls_smmry_type !='8Outstanding Balance')";
                                $strSql = "SELECT 
        pybls_smmry_id, 
        pybls_smmry_type, 
        pybls_smmry_desc, 
        pybls_smmry_amnt, 
       code_id_behind, 
       auto_calc, 
       incrs_dcrs1, 
       asset_expns_acnt_id, 
       incrs_dcrs2, 
       liability_acnt_id, 
       appld_prepymnt_doc_id, 
       entrd_curr_id, gst.get_pssbl_val(a.entrd_curr_id), 
       func_curr_id, gst.get_pssbl_val(a.func_curr_id), 
      accnt_curr_id, gst.get_pssbl_val(a.accnt_curr_id), 
      func_curr_rate, accnt_curr_rate, 
       func_curr_amount, accnt_curr_amnt, initial_amnt_line_id, 
       REPLACE(REPLACE(a.pybls_smmry_type,'2Tax','3Tax'),'3Discount','2Discount') smtyp 
  FROM accb.accb_pybls_amnt_smmrys a " .
                                    "WHERE((a.src_pybls_hdr_id = " . $docHdrID . ")" . $whrcls . ") ORDER BY 23 ASC ";
                                $result = executeSQLNoParams($strSql);
                                return $result;
                            }

                            //Loan Requests

                            function get_InvstTrans_Attachments($searchWord, $offset, $limit_size, $batchID, $batchTyp, &$attchSQL)
                            {
                                $strSql = "SELECT a.attchmnt_id, a.src_pkey_id, a.attchmnt_desc, a.file_name, a.src_trans_type  " .
                                    "FROM pay.pay_trans_attchmnts a " .
                                    "WHERE(a.attchmnt_desc ilike '" . loc_db_escape_string($searchWord) .
                                    "' and a.src_pkey_id = " . $batchID . " and a.src_trans_type='" . loc_db_escape_string($batchTyp) .
                                    "') ORDER BY a.attchmnt_id LIMIT " . $limit_size .
                                    " OFFSET " . (abs($offset * $limit_size));
                                $result = executeSQLNoParams($strSql);
                                return $result;
                            }

                            function get_Total_InvstTrans_Attachments($searchWord, $batchID, $batchTyp)
                            {
                                $strSql = "SELECT count(1) " .
                                    "FROM pay.pay_trans_attchmnts a " .
                                    "WHERE(a.attchmnt_desc ilike '" . loc_db_escape_string($searchWord) .
                                    "' and a.src_pkey_id = " . $batchID . " and a.src_trans_type='" . loc_db_escape_string($batchTyp) .
                                    "')";
                                $result = executeSQLNoParams($strSql);
                                while ($row = loc_db_fetch_array($result)) {
                                    return $row[0];
                                }
                                return 0;
                            }

                            function getInvstTransAttchmtDocs($batchid)
                            {
                                $sqlStr = "SELECT attchmnt_id, file_name, attchmnt_desc, src_pkey_id, src_trans_type 
  FROM pay.pay_trans_attchmnts WHERE 1=1 AND file_name != '' AND doc_hdr_id = " . $batchid;

                                $result = executeSQLNoParams($sqlStr);
                                return $result;
                            }

                            function updateInvstTransDocFlNm($attchmnt_id, $file_name)
                            {
                                global $usrID;
                                $dateStr = getDB_Date_time();
                                $insSQL = "UPDATE pay.pay_trans_attchmnts SET file_name='"
                                    . loc_db_escape_string($file_name) .
                                    "', last_update_by=" . $usrID .
                                    ", last_update_date='" . $dateStr . "'
                WHERE attchmnt_id=" . $attchmnt_id;
                                return execUpdtInsSQL($insSQL);
                            }

                            function getNewInvstTransDocID()
                            {
                                $strSql = "select nextval('pay.pay_trans_attchmnts_attchmnt_id_seq')";
                                $result = executeSQLNoParams($strSql);

                                if (loc_db_num_rows($result) > 0) {
                                    $row = loc_db_fetch_array($result);
                                    return $row[0];
                                }
                                return -1;
                            }

                            function createInvstTransDoc($attchmnt_id, $hdrid, $hdrType, $attchmnt_desc, $file_name)
                            {
                                global $usrID;
                                $dateStr = getDB_Date_time();
                                $insSQL = "INSERT INTO pay.pay_trans_attchmnts(
            attchmnt_id, src_pkey_id, src_trans_type, attchmnt_desc, file_name, created_by, 
            creation_date, last_update_by, last_update_date)
             VALUES (" . $attchmnt_id . ", " . $hdrid . ",'"
                                    . loc_db_escape_string($hdrType) . "','"
                                    . loc_db_escape_string($attchmnt_desc) . "','"
                                    . loc_db_escape_string($file_name) . "',"
                                    . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";
                                return execUpdtInsSQL($insSQL);
                            }

                            function deleteInvstTransDoc($pkeyID, $docTrnsNum = "")
                            {
                                $insSQL = "DELETE FROM pay.pay_trans_attchmnts WHERE attchmnt_id = " . $pkeyID;
                                $affctd1 = execUpdtInsSQL($insSQL, "Trns. No:" . $docTrnsNum);
                                if ($affctd1 > 0) {
                                    $dsply = "Successfully Deleted the ff Records-";
                                    $dsply .= "<br/>$affctd1 Attached Document(s)!";
                                    return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
                                } else {
                                    $dsply = "No Record Deleted";
                                    return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
                                }
                            }

                            function uploadDaInvstTransDoc($attchmntID, &$nwImgLoc, &$errMsg)
                            {
                                global $tmpDest;
                                global $ftp_base_db_fldr;
                                global $usrID;
                                global $fldrPrfx;
                                global $smplTokenWord1;

                                $msg = "";
                                $allowedExts = array(
                                    'png', 'jpg', 'gif', 'jpeg', 'bmp', 'pdf', 'xls', 'xlsx',
                                    'doc', 'docx', 'ppt', 'pptx', 'txt', 'csv'
                                );

                                if (isset($_FILES["daInvstTransAttchmnt"])) {
                                    $flnm = $_FILES["daInvstTransAttchmnt"]["name"];
                                    $temp = explode(".", $flnm);
                                    $extension = end($temp);
                                    if ($_FILES["daInvstTransAttchmnt"]["error"] > 0) {
                                        $msg .= "Return Code: " . $_FILES["daInvstTransAttchmnt"]["error"] . "<br>";
                                    } else {
                                        $msg .= "Uploaded File: " . $_FILES["daInvstTransAttchmnt"]["name"] . "<br>";
                                        $msg .= "Type: " . $_FILES["daInvstTransAttchmnt"]["type"] . "<br>";
                                        $msg .= "Size: " . round(($_FILES["daInvstTransAttchmnt"]["size"]) / (1024 * 1024), 2) . " MB<br>";
                                        //$msg .= "Temp file: " . $_FILES["daInvstTransAttchmnt"]["tmp_name"] . "<br>";
                                        if ((($_FILES["daInvstTransAttchmnt"]["type"] == "image/gif") || ($_FILES["daInvstTransAttchmnt"]["type"] == "image/jpeg") || ($_FILES["daInvstTransAttchmnt"]["type"] == "image/jpg") || ($_FILES["daInvstTransAttchmnt"]["type"] == "image/pjpeg") || ($_FILES["daInvstTransAttchmnt"]["type"] == "image/x-png") ||
                                            ($_FILES["daInvstTransAttchmnt"]["type"] == "image/png") || in_array($extension, $allowedExts)) && ($_FILES["daInvstTransAttchmnt"]["size"] <
                                            10000000)) {
                                            $nwFileName = encrypt1($attchmntID . "." . $extension, $smplTokenWord1) . "." . $extension;
                                            $img_src = $fldrPrfx . $tmpDest . "$nwFileName";
                                            move_uploaded_file($_FILES["daInvstTransAttchmnt"]["tmp_name"], $img_src);
                                            $ftp_src = $ftp_base_db_fldr . "/PayDocs/$attchmntID" . "." . $extension;
                                            if (file_exists($img_src)) {
                                                copy("$img_src", "$ftp_src");
                                                $dateStr = getDB_Date_time();
                                                $updtSQL = "UPDATE pay.pay_trans_attchmnts
                            SET file_name='" . $attchmntID . "." . $extension .
                                                    "', last_update_by=" . $usrID .
                                                    ", last_update_date='" . $dateStr .
                                                    "' WHERE attchmnt_id=" . $attchmntID;
                                                execUpdtInsSQL($updtSQL);
                                            }
                                            $msg .= "Document Stored Successfully!<br/>";
                                            $nwImgLoc = "$attchmntID" . "." . $extension;
                                            $errMsg = $msg;
                                            return TRUE;
                                        } else {
                                            $msg .= "Invalid file!<br/>File Size must be below 10MB and<br/>File Type must be in the ff:<br/>" . implode(
                                                ", ",
                                                $allowedExts
                                            );
                                            $nwImgLoc = $msg;
                                            $errMsg = $msg;
                                        }
                                    }
                                }
                                $msg .= "<br/>Invalid file";
                                $nwImgLoc = $msg;
                                $errMsg = $msg;
                                return FALSE;
                            }

                            function get_TrnsRqstsDocHdr($searchWord, $searchIn, $offset, $limit_size, $orgID, $shwSelfOnly, $payTrnsRqstsType)
                            {
                                global $prsnid;
                                $strSql = "";
                                $whrcls = "";
                                $unpstdCls = " and (a.request_type='" . loc_db_escape_string($payTrnsRqstsType) . "')";
                                if ($shwSelfOnly) {
                                    $unpstdCls .= " and (a.RQSTD_FOR_PERSON_ID = " . $prsnid . ")";
                                }
                                if ($searchIn == "Requestor") {
                                    $whrcls = " and (REPLACE(prs.get_prsn_surname(a.RQSTD_FOR_PERSON_ID) || ' (' 
      || prs.get_prsn_loc_id(a.RQSTD_FOR_PERSON_ID) || ')', ' ()', '') ilike '" . loc_db_escape_string($searchWord) .
                                        "')";
                                } else if ($searchIn == "Narration") {
                                    $whrcls = " and (a.REQUEST_REASON ilike '" . loc_db_escape_string($searchWord) . "'"
                                        . "or b.item_type_name ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else {
                                    $whrcls = " and (a.REQUEST_REASON ilike '" . loc_db_escape_string($searchWord) . "'"
                                        . "or b.item_type_name ilike '" . loc_db_escape_string($searchWord) . "'"
                                        . "or REPLACE(prs.get_prsn_surname(a.RQSTD_FOR_PERSON_ID) || ' (' 
      || prs.get_prsn_loc_id(a.RQSTD_FOR_PERSON_ID) || ')', ' ()', '') ilike '" . loc_db_escape_string($searchWord) .
                                        "')";
                                }
                                $strSql = "SELECT a.pay_request_id, a.RQSTD_FOR_PERSON_ID, 
      REPLACE(prs.get_prsn_surname(a.RQSTD_FOR_PERSON_ID) || ' (' 
      || prs.get_prsn_loc_id(a.RQSTD_FOR_PERSON_ID) || ')', ' ()', '') fullnm, a.request_type, 
        a.item_type_id,b.item_type_name, a.local_clsfctn, a.REQUEST_REASON, 
        to_char(to_timestamp(a.creation_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') rqst_sbmt_date, 
        round(a.PRNCPL_AMOUNT,2) PRNCPL_AMOUNT, a.MNTHLY_DEDUC, a.INTRST_RATE, 
        a.REPAY_PERIOD, a.REQUEST_STATUS is_pstd, a.HAS_AGREED, a.IS_PROCESSED
        FROM pay.pay_loan_pymnt_rqsts a, pay.loan_pymnt_invstmnt_typs b 
        WHERE((a.item_type_id=b.item_type_id and a.org_id = " . $orgID . ")" . $whrcls . $unpstdCls .
                                    ") ORDER BY pay_request_id DESC LIMIT " . $limit_size .
                                    " OFFSET " . (abs($offset * $limit_size));
                                $result = executeSQLNoParams($strSql);
                                return $result;
                            }

                            function get_Total_TrnsRqstsDoc($searchWord, $searchIn, $orgID, $shwSelfOnly, $payTrnsRqstsType)
                            {
                                global $prsnid;
                                $strSql = "";
                                $whrcls = "";
                                $unpstdCls = " and (a.request_type='" . loc_db_escape_string($payTrnsRqstsType) . "')";
                                if ($shwSelfOnly) {
                                    $unpstdCls .= " and (a.RQSTD_FOR_PERSON_ID = " . $prsnid . ")";
                                }
                                if ($searchIn == "Requestor") {
                                    $whrcls = " and (REPLACE(prs.get_prsn_surname(a.RQSTD_FOR_PERSON_ID) || ' (' 
      || prs.get_prsn_loc_id(a.RQSTD_FOR_PERSON_ID) || ')', ' ()', '') ilike '" . loc_db_escape_string($searchWord) .
                                        "')";
                                } else if ($searchIn == "Narration") {
                                    $whrcls = " and (a.REQUEST_REASON ilike '" . loc_db_escape_string($searchWord) . "'"
                                        . "or b.item_type_name ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else {
                                    $whrcls = " and (a.REQUEST_REASON ilike '" . loc_db_escape_string($searchWord) . "'"
                                        . "or b.item_type_name ilike '" . loc_db_escape_string($searchWord) . "'"
                                        . "or REPLACE(prs.get_prsn_surname(a.RQSTD_FOR_PERSON_ID) || ' (' 
      || prs.get_prsn_loc_id(a.RQSTD_FOR_PERSON_ID) || ')', ' ()', '') ilike '" . loc_db_escape_string($searchWord) .
                                        "')";
                                }
                                $strSql = "SELECT count(1) FROM pay.pay_loan_pymnt_rqsts a, pay.loan_pymnt_invstmnt_typs b 
        WHERE((a.item_type_id=b.item_type_id and a.org_id = " . $orgID . ")" . $whrcls . $unpstdCls . ")";

                                $result = executeSQLNoParams($strSql);
                                while ($row = loc_db_fetch_array($result)) {
                                    return $row[0];
                                }
                                return 0;
                            }

                            function get_One_TrnsRqstsDocHdr($hdrID)
                            {
                                $strSql = "SELECT a.pay_request_id, a.RQSTD_FOR_PERSON_ID, 
      REPLACE(prs.get_prsn_surname(a.RQSTD_FOR_PERSON_ID) || ' (' 
      || prs.get_prsn_loc_id(a.RQSTD_FOR_PERSON_ID) || ')', ' ()', '') fullnm, a.request_type, 
        a.item_type_id,b.item_type_name, a.local_clsfctn, a.REQUEST_REASON, 
        to_char(to_timestamp(a.creation_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') rqst_sbmt_date, 
        round(a.PRNCPL_AMOUNT,2) PRNCPL_AMOUNT, a.MNTHLY_DEDUC, a.INTRST_RATE, 
        a.REPAY_PERIOD, a.REQUEST_STATUS is_pstd, a.HAS_AGREED, a.IS_PROCESSED, 
        a.intrst_period_type, a.repay_period_type, a.net_loan_amount, a.max_loan_amount, 
        a.enforce_max_amnt, a.lnkd_loan_id, a.min_loan_amount  
        FROM pay.pay_loan_pymnt_rqsts a, pay.loan_pymnt_invstmnt_typs b 
        WHERE(a.item_type_id=b.item_type_id and a.pay_request_id = " . $hdrID . ")";
                                $result = executeSQLNoParams($strSql);
                                return $result;
                            }

                            function get_UnsttldLoanRqsts($searchWord, $searchIn, $offset, $limit_size, $orgID, $rqstdPrsnID, $dpndntItmTypID, $dpndntBalsItmID, $rqstType = "LOAN")
                            {
                                $strSql = "";
                                $whrcls = "";
                                $unpstdCls = " and (a.RQSTD_FOR_PERSON_ID = " . $rqstdPrsnID .
                                    ") and (a.item_type_id IN (" . $dpndntItmTypID . ")) and a.request_type='" . loc_db_escape_string($rqstType) . "'";
                                $strSql = "SELECT a.pay_request_id, a.RQSTD_FOR_PERSON_ID, 
      REPLACE(prs.get_prsn_surname(a.RQSTD_FOR_PERSON_ID) || ' (' 
      || prs.get_prsn_loc_id(a.RQSTD_FOR_PERSON_ID) || ')', ' ()', '') fullnm, a.request_type, 
        a.item_type_id,b.item_type_name, a.local_clsfctn, a.REQUEST_REASON, 
        to_char(to_timestamp(a.creation_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') rqst_sbmt_date, 
        round(a.PRNCPL_AMOUNT,2) PRNCPL_AMOUNT, a.MNTHLY_DEDUC, a.INTRST_RATE, 
        a.REPAY_PERIOD, a.REQUEST_STATUS is_pstd, a.HAS_AGREED, a.IS_PROCESSED
        FROM pay.pay_loan_pymnt_rqsts a, pay.loan_pymnt_invstmnt_typs b 
        WHERE((a.item_type_id=b.item_type_id and a.REQUEST_STATUS IN ('Approved')
        and pay.get_ltst_blsitm_bals(" . $rqstdPrsnID .
                                    "," . $dpndntBalsItmID . ",to_char(now(),'YYYY-MM-DD'))>0
            and a.IS_PROCESSED='1' and a.org_id = " . $orgID . ")" . $whrcls . $unpstdCls .
                                    ") ORDER BY pay_request_id DESC LIMIT " . $limit_size .
                                    " OFFSET " . (abs($offset * $limit_size));
                                $result = executeSQLNoParams($strSql);
                                return $result;
                            }

                            function getNewPayTrnsRqstsID()
                            {
                                $strSql = "select nextval('pay.pay_loan_pymnt_rqsts_pay_request_id_seq')";
                                $result = executeSQLNoParams($strSql);
                                while ($row = loc_db_fetch_array($result)) {
                                    return (float) $row[0];
                                }
                                return -1;
                            }

                            function createTrnsRqstsDocHdr($nwPkeyID, $rqstPrsnID, $rqstTyp, $itmTypID, $rqstRsn, $lclClsfctn, $rqstAmnt, $hsAgreed, $lnkdPayTrnsRqstsID)
                            {
                                global $usrID;
                                global $orgID;
                                $insSQL = "INSERT INTO pay.pay_loan_pymnt_rqsts(pay_request_id, rqstd_for_person_id, request_type, item_type_id, request_reason,
                                     local_clsfctn, prncpl_amount, mnthly_deduc, intrst_rate, intrst_period_type, repay_period, 
                                     repay_period_type, request_status, has_agreed,IS_PROCESSED,
                                     org_id, created_by, creation_date, last_update_by, last_update_date,
                                     net_loan_amount,max_loan_amount,enforce_max_amnt, min_loan_amount, lnkd_loan_id) " .
                                    "VALUES (" . $nwPkeyID . ", " . $rqstPrsnID . ", '" . loc_db_escape_string($rqstTyp) .
                                    "'," . $itmTypID . ", '" . loc_db_escape_string($rqstRsn) .
                                    "', '" . loc_db_escape_string($lclClsfctn) .
                                    "', " . $rqstAmnt .
                                    ",0, pay.get_trans_type_rate(" . $itmTypID . ")," .
                                    "pay.get_trans_ratetype(" . $itmTypID . ")," .
                                    "pay.get_trans_repay_prd(" . $itmTypID . ")," .
                                    "pay.get_trans_repay_typ(" . $itmTypID . ")," .
                                    "'Not Submitted','" . cnvrtBoolToBitStr($hsAgreed) .
                                    "','0', " . $orgID . ", " . $usrID .
                                    ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
                                    ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'),0,0,pay.get_trntyp_enfrc_mx(" . $itmTypID . "),0," . $lnkdPayTrnsRqstsID . ")";


                                $insSQL1 = "UPDATE pay.pay_loan_pymnt_rqsts
            SET mnthly_deduc=round(pay.exct_itm_type_sql(pay.get_trans_typ_sql(" . $itmTypID . ")," . $itmTypID . "," . $nwPkeyID . "," . $rqstPrsnID . "," . $orgID . ",to_char(now(),'YYYY-MM-DD HH24:MI:SS')),2),
                net_loan_amount=round(pay.exct_itm_type_sql(pay.get_trntyp_net_sql(" . $itmTypID . ")," . $itmTypID . "," . $nwPkeyID . "," . $rqstPrsnID . "," . $orgID . ",to_char(now(),'YYYY-MM-DD HH24:MI:SS')),2),
                max_loan_amount=round(pay.exct_itm_type_sql(pay.get_trntyp_mx_sql(" . $itmTypID . ")," . $itmTypID . "," . $nwPkeyID . "," . $rqstPrsnID . "," . $orgID . ",to_char(now(),'YYYY-MM-DD HH24:MI:SS')),2),
                min_loan_amount=round(pay.exct_itm_type_sql(pay.get_trntyp_min_sql(" . $itmTypID . ")," . $itmTypID . "," . $nwPkeyID . "," . $rqstPrsnID . "," . $orgID . ",to_char(now(),'YYYY-MM-DD HH24:MI:SS')),2)
            WHERE pay_request_id = " . $nwPkeyID;
                                $afctd = execUpdtInsSQL($insSQL);
                                execUpdtInsSQL($insSQL1);
                                return $afctd;
                            }

                            function updtTrnsRqstsDocHdr($hdrID, $rqstPrsnID, $rqstTyp, $itmTypID, $rqstRsn, $lclClsfctn, $rqstAmnt, $hsAgreed, $lnkdPayTrnsRqstsID)
                            {
                                global $usrID;
                                global $orgID;
                                $insSQL = "
    UPDATE pay.pay_loan_pymnt_rqsts
    SET rqstd_for_person_id=" . $rqstPrsnID . ",
        request_type='" . loc_db_escape_string($rqstTyp) . "',
        item_type_id=" . $itmTypID . ",
        request_reason='" . loc_db_escape_string($rqstRsn) . "',
        local_clsfctn='" . loc_db_escape_string($lclClsfctn) . "',
        prncpl_amount=" . $rqstAmnt . ",
        intrst_rate=pay.get_trans_type_rate(" . $itmTypID . "),
        intrst_period_type=pay.get_trans_ratetype(" . $itmTypID . "),
        repay_period=pay.get_trans_repay_prd(" . $itmTypID . "),
        repay_period_type=pay.get_trans_repay_typ(" . $itmTypID . "),
        enforce_max_amnt=pay.get_trntyp_enfrc_mx(" . $itmTypID . "),
        request_status = 'Not Submitted',IS_PROCESSED='0',date_processed='',
        has_agreed = '" . cnvrtBoolToBitStr($hsAgreed) . "',
        creation_date = to_char(now(), 'YYYY-MM-DD HH24:MI:SS'),
        last_update_by=" . $usrID . ",
        lnkd_loan_id=" . $lnkdPayTrnsRqstsID . ",
        last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS')
    WHERE pay_request_id = " . $hdrID;
                                $nwPkeyID = $hdrID;
                                $insSQL1 = "UPDATE pay.pay_loan_pymnt_rqsts
                SET mnthly_deduc=round(pay.exct_itm_type_sql(pay.get_trans_typ_sql(" . $itmTypID . ")," . $itmTypID . "," . $nwPkeyID . "," . $rqstPrsnID . "," . $orgID . ",to_char(now(),'YYYY-MM-DD HH24:MI:SS')),2),
                    net_loan_amount=round(pay.exct_itm_type_sql(pay.get_trntyp_net_sql(" . $itmTypID . ")," . $itmTypID . "," . $nwPkeyID . "," . $rqstPrsnID . "," . $orgID . ",to_char(now(),'YYYY-MM-DD HH24:MI:SS')),2),
                    max_loan_amount=round(pay.exct_itm_type_sql(pay.get_trntyp_mx_sql(" . $itmTypID . ")," . $itmTypID . "," . $nwPkeyID . "," . $rqstPrsnID . "," . $orgID . ",to_char(now(),'YYYY-MM-DD HH24:MI:SS')),2),
                    min_loan_amount=round(pay.exct_itm_type_sql(pay.get_trntyp_min_sql(" . $itmTypID . ")," . $itmTypID . "," . $nwPkeyID . "," . $rqstPrsnID . "," . $orgID . ",to_char(now(),'YYYY-MM-DD HH24:MI:SS')),2)
                WHERE pay_request_id = " . $nwPkeyID;
                                $afctd = execUpdtInsSQL($insSQL);
                                execUpdtInsSQL($insSQL1);
                                return $afctd;
                            }

                            function deleteTrnsRqsts($valLnid, $docNum)
                            {
                                $strSql = "SELECT count(1) FROM pay.pay_loan_pymnt_rqsts a WHERE(a.pay_request_id = " . $valLnid .
                                    " and a.request_status IN ('Validated', 'Approved', 'Cancelled','Initiated','Reviewed'))";
                                $result1 = executeSQLNoParams($strSql);
                                $trnsCnt1 = 0;
                                while ($row = loc_db_fetch_array($result1)) {
                                    $trnsCnt1 = (float) $row[0];
                                }
                                if (($trnsCnt1) > 0) {
                                    $dsply = "No Record Deleted<br/>Cannot delete a Finalized Transaction!";
                                    return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
                                }
                                $delSQL2 = "DELETE FROM pay.pay_trans_attchmnts WHERE src_pkey_id = " . $valLnid . " and src_trans_type='LOAN_N_PAY'";
                                $affctd2 = execUpdtInsSQL($delSQL2, "Desc:" . $docNum);
                                $delSQL = "DELETE FROM pay.pay_loan_pymnt_rqsts WHERE pay_request_id = " . $valLnid;
                                $affctd1 = execUpdtInsSQL($delSQL, "Desc:" . $docNum);
                                if ($affctd1 > 0) {
                                    $dsply = "";
                                    $dsply .= "<br/>Successfully Executed the ff-";
                                    $dsply .= "<br/>Deleted $affctd1 Transaction(s)!";
                                    $dsply .= "<br/>Deleted $affctd2 Transaction Attachment(s)!";
                                    return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
                                } else {
                                    $dsply = "No Record Deleted";
                                    return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
                                }
                            }

                            function getItmID($itmname, $orgid)
                            {
                                $sqlStr = "select item_id from org.org_pay_items where lower(item_code_name) = '" .
                                    loc_db_escape_string(strtolower($itmname)) . "' and org_id = " . $orgid;
                                $result = executeSQLNoParams($sqlStr);
                                while ($row = loc_db_fetch_array($result)) {
                                    return (float) $row[0];
                                }
                                return -1;
                            }

                            function get_Pay_Trns($searchWord, $searchIn, $offset, $limit_size, $orgID, $dte1, $dte2, $payBalsItemID)
                            {
                                global $prsnid;
                                if ($dte1 !== "") {
                                    $dte1 = cnvrtDMYTmToYMDTm($dte1);
                                }
                                if ($dte2 !== "") {
                                    $dte2 = cnvrtDMYTmToYMDTm($dte2);
                                }

                                $whrcls = "";
                                $to_gl = "";
                                //if ($gonetogl)
                                //{
                                //  $to_gl = " and (gl_batch_id > 0)";
                                //}
                                if ($searchIn == "Person No.") {
                                    $whrcls = " and (c.local_id_no ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Person Name") {
                                    $whrcls = " and (trim(c.title || ' ' || c.sur_name || " .
                                        "', ' || c.first_name || ' ' || c.other_names) ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Item Name") {
                                    $whrcls = " and (b.item_code_name ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Transaction Date") {
                                    $whrcls = " and (to_char(to_timestamp(a.paymnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Transaction Description") {
                                    $whrcls = " and (a.pymnt_desc ilike '" . loc_db_escape_string($searchWord) . "')";
                                }
                                $strSql = "SELECT a.pay_trns_id, a.person_id, a.item_id, a.amount_paid, 
to_char(to_timestamp(a.paymnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS')
, a.paymnt_source, a.pay_trns_type, a.pymnt_desc, -1, a.crncy_id, c.local_id_no, trim(c.title || ' ' || c.sur_name || " .
                                    "', ' || c.first_name || ' ' || c.other_names) fullname, b.item_code_name, "
                                    . "a.pymnt_vldty_status, gst.get_pssbl_val(a.crncy_id), b.item_min_type, d.adds_subtracts, d.scale_factor " .
                                    "FROM pay.pay_itm_trnsctns a " .
                                    "LEFT OUTER JOIN org.org_pay_items b ON (a.item_id = b.item_id) " .
                                    "LEFT OUTER JOIN org.org_pay_itm_feeds d ON (a.item_id = d.fed_by_itm_id) " .
                                    "LEFT OUTER JOIN prs.prsn_names_nos c on (a.person_id = c.person_id) " .
                                    "WHERE((b.org_id = " . $orgID . ")" . $whrcls . $to_gl .
                                    " and (to_timestamp(a.paymnt_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . loc_db_escape_string($dte1) .
                                    "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . loc_db_escape_string($dte2) . "','YYYY-MM-DD HH24:MI:SS'))"
                                    . " AND d.balance_item_id=" . $payBalsItemID . " AND a.person_id = " . $prsnid . ") " .
                                    "ORDER BY a.person_id, a.paymnt_date ASC, b.pay_run_priority ASC, a.pymnt_vldty_status DESC LIMIT " . $limit_size .
                                    " OFFSET " . (abs($offset * $limit_size));
                                $result = executeSQLNoParams($strSql);
                                return $result;
                            }

                            function get_Total_Trns($searchWord, $searchIn, $orgID, $dte1, $dte2, $payBalsItemID)
                            {
                                global $prsnid;
                                if ($dte1 !== "") {
                                    $dte1 = cnvrtDMYTmToYMDTm($dte1);
                                }
                                if ($dte2 !== "") {
                                    $dte2 = cnvrtDMYTmToYMDTm($dte2);
                                }

                                $whrcls = "";
                                $to_gl = "";
                                //if ($gonetogl)
                                //{
                                //  $to_gl = " and (gl_batch_id > 0)";
                                //}
                                if ($searchIn == "Person No.") {
                                    $whrcls = " and (c.local_id_no ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Person Name") {
                                    $whrcls = " and (trim(c.title || ' ' || c.sur_name || " .
                                        "', ' || c.first_name || ' ' || c.other_names) ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Item Name") {
                                    $whrcls = " and (b.item_code_name ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Transaction Date") {
                                    $whrcls = " and (to_char(to_timestamp(a.paymnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) . "')";
                                } else if ($searchIn == "Transaction Description") {
                                    $whrcls = " and (a.pymnt_desc ilike '" . loc_db_escape_string($searchWord) . "')";
                                }
                                $strSql = "SELECT count(1) " .
                                    "FROM pay.pay_itm_trnsctns a " .
                                    "LEFT OUTER JOIN org.org_pay_items b ON (a.item_id = b.item_id) " .
                                    "LEFT OUTER JOIN org.org_pay_itm_feeds d ON (a.item_id = d.fed_by_itm_id) " .
                                    "LEFT OUTER JOIN prs.prsn_names_nos c on (a.person_id = c.person_id) " .
                                    "WHERE((b.org_id = " . $orgID . ")" . $whrcls . $to_gl .
                                    " and (to_timestamp(a.paymnt_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . loc_db_escape_string($dte1) .
                                    "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . loc_db_escape_string($dte2) . "','YYYY-MM-DD HH24:MI:SS'))"
                                    . " AND d.balance_item_id=" . $payBalsItemID . " AND a.person_id = " . $prsnid . ") ";

                                $result = executeSQLNoParams($strSql);
                                while ($row = loc_db_fetch_array($result)) {
                                    return ((float) $row[0]);
                                }
                                return 0;
                            }

                            function loadTypClsfctnOptions($payTrnsRqstsItmTypID, $payTrnsRqstsType)
                            {
                                $pssblItems = [];
                                $i = 0;
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
                                    $pssblItems[$i] = $titleRow[0];
                                    $i++;
                                }
                                return join(";", $pssblItems);
                            }

                            function loadTypRqstsOptions($payTrnsRqstsItmTypID, $payTrnsRqstsPrsnID, &$firstPayTrnsRqstsID)
                            {
                                global $orgID;
                                $payTrnsRqstsDpndtItmTypID = (float) getGnrlRecNm("pay.loan_pymnt_invstmnt_typs", "item_type_id", "lnkd_loan_type_id", $payTrnsRqstsItmTypID);
                                $payTrnsRqstsDpndtBalsItmID = (float) getGnrlRecNm("pay.loan_pymnt_invstmnt_typs", "item_type_id", "lnkd_loan_mn_itm_id", $payTrnsRqstsItmTypID);
                                $titleRslt = get_UnsttldLoanRqsts("%", "Requestor", 0, 500, $orgID, $payTrnsRqstsPrsnID, $payTrnsRqstsDpndtItmTypID, $payTrnsRqstsDpndtBalsItmID, "LOAN");
                                $pssblItems = [];
                                $i = 0;
                                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                    if ($i == 0) {
                                        $firstPayTrnsRqstsID = (float) $titleRow[0];
                                    }
                                    $pssblItems[$i] = $titleRow[0] . "|" . $titleRow[5] . "(ID:" . $titleRow[0] . " AMT:" . number_format((float)$titleRow[9], 2) . ")";
                                    $i++;
                                }
                                return join(";", $pssblItems);
                            }
                            function getLoanTypRqstsMxAmnt($itmTypID, $lnkdPayTrnsRqstsID, $rqstPrsnID)
                            {
                                global $orgID;
                                $strSql = "Select CASE WHEN " . $lnkdPayTrnsRqstsID . ">0 THEN pay.get_trns_rqst_amnt(" . $lnkdPayTrnsRqstsID .
                                    ") ELSE pay.exct_itm_type_sql(pay.get_trntyp_mx_sql(" . $itmTypID . ")," . $itmTypID . "," . $lnkdPayTrnsRqstsID .
                                    "," . $rqstPrsnID . "," . $orgID . ",to_char(now(),'YYYY-MM-DD HH24:MI:SS')) END";
                                $result = executeSQLNoParams($strSql);
                                while ($row = loc_db_fetch_array($result)) {
                                    return (float) $row[0];
                                }
                                return -1;
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
                                                    restricted();
                                                    exit();
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
?>