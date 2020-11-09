<?php
$inTmpltTyp = isset($_POST['inTmpltTyp']) ? cleanInputData($_POST['inTmpltTyp']) : "EXPNS";
$dfltPrvldgs2 = array(
    /* 111 */
    "View Expense Vouchers", "Add Expense Vouchers", "Edit Expense Vouchers", "Delete Expense Vouchers",
    /* 115 */ "View Income Vouchers", "Add Income Vouchers", "Edit Income Vouchers", "Delete Income Vouchers",
    /* 119 */ "View Simplified Transaction", "Add Simplified Transaction", "Edit Simplified Transaction", "Delete Simplified Transaction"
);
$canAdd = test_prmssns($dfltPrvldgs2[1], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs2[2], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs2[3], $mdlNm);
$inTmpltTypLbl = "Expense Voucher";
$inTmpltTypLbl2 = "Payment/Expense Vouchers";
if ($inTmpltTyp == "INCM") {
    $canAdd = test_prmssns($dfltPrvldgs2[5], $mdlNm);
    $canEdt = test_prmssns($dfltPrvldgs2[6], $mdlNm);
    $canDel = test_prmssns($dfltPrvldgs2[7], $mdlNm);
    $inTmpltTypLbl = "Income Voucher";
    $inTmpltTypLbl2 = "Receipts/Income Vouchers";
}
$canApprove = test_prmssns($dfltPrvldgs[21], $mdlNm);
$canVoid = $canApprove;

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 100;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
$defaultBrkdwnLOV = "Transaction Amount Breakdown Parameters";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Simplified Transaction Header */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if (($canDel === true)) {
                    echo deleteSmplVchr($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Simplified Transaction Line */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if (($canDel === true)) {
                    echo deleteSmplVchrLine($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Simplified Transaction Transaction
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $sbmtdSmplVchrID = isset($_POST['sbmtdSmplVchrID']) ? (float) cleanInputData($_POST['sbmtdSmplVchrID']) : -1;
                $smplVchrTrnsType = isset($_POST['smplVchrTrnsType']) ? cleanInputData($_POST['smplVchrTrnsType']) : "PURCHASE";
                $smplVchrTrnsNum = isset($_POST['smplVchrTrnsNum']) ? cleanInputData($_POST['smplVchrTrnsNum']) : '';
                $smplVchrSpplrID = isset($_POST['smplVchrSpplrID']) ? (float) cleanInputData($_POST['smplVchrSpplrID']) : -1;
                $smplVchrSpplrSiteID = isset($_POST['smplVchrSpplrSiteID']) ? (float) cleanInputData($_POST['smplVchrSpplrSiteID']) : -1;
                $smplVchrMainTmpltID = isset($_POST['smplVchrMainTmpltID']) ? (float) cleanInputData($_POST['smplVchrMainTmpltID']) : -1;
                $smplVchrMltplLines = ((isset($_POST['smplVchrMltplLines']) ? cleanInputData($_POST['smplVchrMltplLines']) : "NO") == "YES") ? true : false;
                $smplVchrInvcCur = isset($_POST['smplVchrInvcCur']) ? cleanInputData($_POST['smplVchrInvcCur']) : $fnccurnm;
                $curLovID = getLovID("Currencies");
                $smplVchrInvcCurID = getPssblValID($smplVchrInvcCur, $curLovID);
                $smplVchrTransAmnt = isset($_POST['smplVchrTransAmnt']) ? (float) cleanInputData($_POST['smplVchrTransAmnt']) : 0;
                $smplVchrExchngRate = isset($_POST['smplVchrExchngRate']) ? (float) cleanInputData($_POST['smplVchrExchngRate']) : 0;
                $smplVchrTransDte = isset($_POST['smplVchrTransDte']) ? cleanInputData($_POST['smplVchrTransDte']) : $gnrlTrnsDteDMY;
                $smplVchrRefNum = isset($_POST['smplVchrRefNum']) ? cleanInputData($_POST['smplVchrRefNum']) : -1;
                $smplVchrDesc = isset($_POST['smplVchrDesc']) ? cleanInputData($_POST['smplVchrDesc']) : '';
                $shdSbmt = isset($_POST['shdSbmt']) ? (int) cleanInputData($_POST['shdSbmt']) : 0;
                $slctdTransLines = isset($_POST['slctdTransLines']) ? cleanInputData($_POST['slctdTransLines']) : '';
                $smplVchrGLBatchID = (float) getGnrlRecNm("accb.accb_smpl_vchr_hdr", "smpl_vchr_hdr_id", "gl_batch_id", $sbmtdSmplVchrID);
                $smplVchrDfltTrnsDte = $smplVchrTransDte;
                if (strlen($smplVchrDesc) > 499) {
                    $smplVchrDesc = substr($smplVchrDesc, 0, 499);
                }
                $exitErrMsg = "";
                if ($smplVchrTrnsType == "" || $smplVchrTrnsNum == "") {
                    $exitErrMsg .= "Please enter Transaction Type and Number!<br/>";
                }
                if ($smplVchrDesc == "") {
                    $exitErrMsg .= "Please enter Description!<br/>";
                }
                if ($smplVchrTransDte == "") {
                    $exitErrMsg .= "Transaction Date cannot be empty!<br/>";
                }
                if ($smplVchrMltplLines === false && $smplVchrMainTmpltID <= 0) {
                    $exitErrMsg .= 'Main Template cannot be empty when Voucher does not support Lines!<br/>';
                }
                if ($smplVchrTransAmnt <= 0 && $shdSbmt == 2) {
                    $exitErrMsg .= "Cannot Finalize when Transaction Amount is zero or less!<br/>";
                }
                if ((!$canEdt && !$canAdd)) {
                    $exitErrMsg .= "Sorry you don't have permission to perform this action";
                }

                $apprvlStatus = "Not Finalized";
                $nxtApprvlActn = "Finalize";
                $pymntTrms = "";
                $srcDocHdrID = -1;
                $srcDocType = "";
                $pymntMthdID = -1;
                $amntPaid = 0;
                $glBtchID = $smplVchrGLBatchID;
                $spplrInvcNum = "";
                $docTmpltClsftn = "";
                $amntAppld = 0;
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['sbmtdSmplVchrID'] = $sbmtdSmplVchrID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                if ($sbmtdSmplVchrID <= 0) {
                    $sbmtdSmplVchrID = getNewSmplVchrHdrID();
                    $afftctd += createSmplVchrHdr(
                        $sbmtdSmplVchrID,
                        $orgID,
                        $smplVchrSpplrID,
                        $smplVchrSpplrSiteID,
                        $smplVchrTrnsNum,
                        $smplVchrRefNum,
                        $smplVchrDesc,
                        $smplVchrTrnsType,
                        $smplVchrInvcCurID,
                        $smplVchrTransAmnt,
                        $smplVchrExchngRate,
                        $smplVchrTransDte,
                        $smplVchrMainTmpltID,
                        $smplVchrMltplLines
                    );
                } else if ($sbmtdSmplVchrID > 0) {
                    $afftctd += updtSmplVchrHdr(
                        $sbmtdSmplVchrID,
                        $orgID,
                        $smplVchrSpplrID,
                        $smplVchrSpplrSiteID,
                        $smplVchrTrnsNum,
                        $smplVchrRefNum,
                        $smplVchrDesc,
                        $smplVchrTrnsType,
                        $smplVchrInvcCurID,
                        $smplVchrTransAmnt,
                        $smplVchrExchngRate,
                        $smplVchrTransDte,
                        $smplVchrMainTmpltID,
                        $smplVchrMltplLines
                    );
                }
                if (trim($slctdTransLines, "|~") != "" && $smplVchrMltplLines == true && $sbmtdSmplVchrID > 0) {
                    //Save Transaction Lines
                    $variousRows = explode("|", trim($slctdTransLines, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 5) {
                            $ln_LineID = (float) (cleanInputData1($crntRow[0]));
                            $ln_TmpltID = (float) (cleanInputData1($crntRow[1]));
                            $ln_Desc = cleanInputData1($crntRow[2]);
                            $ln_Amount = (float) cleanInputData1($crntRow[3]);
                            $ln_Date = cleanInputData1($crntRow[4]);
                            if ($ln_Date == "") {
                                $ln_Date = $smplVchrTransDte;
                            }
                            if ($ln_Desc == "") {
                                $ln_Desc = $smplVchrDesc;
                            }
                            if ($ln_TmpltID > 0 && $ln_Amount > 0) {
                                if ($ln_LineID <= 0) {
                                    //Insert
                                    $ln_LineID = getNewSmplVchrLineID();
                                    $afftctd1 += createSmplVchrLine($ln_LineID, $sbmtdSmplVchrID, $ln_TmpltID, $ln_Desc, $ln_Amount, $ln_Date);
                                } else if ($clsfctnID > 0) {
                                    $afftctd1 +=  updtSmplVchrLine($ln_LineID, $ln_TmpltID, $ln_Desc, $ln_Amount, $ln_Date);
                                }
                            }
                        }
                    }
                }
                if ($smplVchrMltplLines == false && $sbmtdSmplVchrID > 0) {
                    execUpdtInsSQL("DELETE FROM accb.accb_smpl_vchr_det WHERE smpl_vchr_hdr_id=" . $sbmtdSmplVchrID);
                }
                if ($afftctd1 <= 0 && $smplVchrMainTmpltID > 0 && $smplVchrTransAmnt > 0 && $sbmtdSmplVchrID > 0) {
                    //Insert
                    $ln_LineID = getNewSmplVchrLineID();
                    $afftctd1 += createSmplVchrLine($ln_LineID, $sbmtdSmplVchrID, $smplVchrMainTmpltID, $smplVchrDesc, $smplVchrTransAmnt, $smplVchrTransDte);
                }
                updtSmplVchrTtlAmnt($sbmtdSmplVchrID);
                if ($smplVchrGLBatchID <= 0 && $shdSbmt == 2 && $canApprove === true) {
                    createSmplVchrAccntng(
                        $sbmtdSmplVchrID,
                        $smplVchrTrnsType,
                        $smplVchrDesc,
                        $smplVchrRefNum,
                        $smplVchrInvcCur,
                        $smplVchrTransDte,
                        $smplVchrExchngRate,
                        $afftctd1,
                        $errMsg
                    );
                }
                if ($exitErrMsg != "") {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>"
                        . "" . $afftctd . " " . $inTmpltTypLbl2 . "(s) Saved Successfully!"
                        . "<br/>" . $afftctd1 . " Additional Transaction(s) Saved Successfully!"
                        . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                } else {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>"
                        . "" . $afftctd . " " . $inTmpltTypLbl2 . "(s) Saved Successfully!"
                        . "<br/>" . $afftctd1 . " Additional Transaction(s) Saved Successfully!";
                }
                $arr_content['percent'] = 100;
                $arr_content['sbmtdSmplVchrID'] = $sbmtdSmplVchrID;
                $arr_content['message'] = $exitErrMsg;
                echo json_encode($arr_content);
                exit();
            } else if ($actyp == 2) {
            } else if ($actyp == 5) {
            }
        } else if ($qstr == "VOID") {
            if ($actyp == 1) {
                //Reverse Simplified Transaction Voucher
                $errMsg = "";
                $smplVchrDesc = isset($_POST['smplVchrDesc']) ? cleanInputData($_POST['smplVchrDesc']) : '';
                $shdSbmt = isset($_POST['shdSbmt']) ? cleanInputData($_POST['shdSbmt']) : 0;
                $sbmtdSmplVchrID = isset($_POST['sbmtdSmplVchrID']) ? cleanInputData($_POST['sbmtdSmplVchrID']) : -1;
                if (!$canVoid) {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    exit();
                }
                $sbmtdJrnlBatchID = (float) getGnrlRecNm("accb.accb_smpl_vchr_hdr", "smpl_vchr_hdr_id", "gl_batch_id", $sbmtdSmplVchrID);
                $orgnlBatchID = $sbmtdJrnlBatchID;
                $trnsIDStatus2 = getGnrlRecNm("accb.accb_trnsctn_batches", "batch_id", "batch_vldty_status", $orgnlBatchID);
                if (strtoupper($trnsIDStatus2) == "VOID") {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Journal Batch has been voided already!</span>";
                    exit();
                }
                $trnsIDStatus1 = getGnrlRecNm("accb.accb_smpl_vchr_hdr", "smpl_vchr_hdr_id", "approval_status", $sbmtdSmplVchrID);
                if ($trnsIDStatus1 == "Cancelled") {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Transaction Already Cancelled!</span>";
                    exit();
                }
                $gnrtdTrnsNo = "";
                $smplVchrDfltTrnsDte = $gnrlTrnsDteDMYHMS;
                $smplVchrGLBatch = "";
                $rqStatus = "Not Finalized"; //approval_status
                $rqStatusNext = "Finalize";
                if ($sbmtdSmplVchrID > 0) {
                    $result = get_One_SmplVchrHdr($sbmtdSmplVchrID);
                    if ($row = loc_db_fetch_array($result)) {
                        $smplVchrDfltTrnsDte = $row[11] . " 12:00:00";
                        $smplVchrGLBatch = $row[14];
                        //$gnrtdTrnsNo = $row[4];
                        $rqStatus = $row[12];
                        $rqStatusNext = "";
                    }
                }
                $smplVchrGLBatch1 = "RVRSL-" . $smplVchrGLBatch;
                $gnrtdTrnsNoExistsID2 = (float) getGnrlRecNm2("accb.accb_trnsctn_batches", "batch_name", "batch_id", $smplVchrGLBatch1);
                if ($gnrtdTrnsNoExistsID2 > 0) {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>A Reversal Batch for the Linked Journal Batch Exists Already!</span>";
                    exit();
                } else {
                    if ($rqStatus == "Not Finalized" && $sbmtdSmplVchrID > 0) {
                        echo deleteSmplVchr($sbmtdSmplVchrID, $smplVchrGLBatch);
                        exit();
                    }
                }
                $affctd1 = 0;
                $affctd2 = 0;
                $affctd3 = 0;
                $affctd4 = 0;
                //CREATE
                $rvrsldesc = "";
                $prdHdrID = getPrdHdrID($orgID);
                $tstDate = cnvrtDMYTmToYMDTm($smplVchrDfltTrnsDte);
                $prdLnID = getTrnsDteOpenPrdLnID($prdHdrID, $tstDate);
                if ($prdLnID <= 0) {
                    $smplVchrDfltTrnsDte = getLtstOpenPrdAfterDate($tstDate);
                }
                if (!isTransPrmttd(get_DfltCashAcnt($orgID), $smplVchrDfltTrnsDte, 200, $errMsg)) {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>ERROR:" . $errMsg . "</span>";
                    exit();
                }
                $affctd1 += voidJrnlBatch($orgnlBatchID, $smplVchrDesc, $smplVchrDfltTrnsDte);
                $voidedJrnlBatchID = getBatchID("RVRSL-" . $smplVchrGLBatch, $orgID);
                if ($voidedJrnlBatchID > 0) {
                    $affctd2 += voidJrnlBatchTrans($orgnlBatchID, $voidedJrnlBatchID, $smplVchrDfltTrnsDte);
                    $rsltCnt = updateSmplVchrVoid($sbmtdSmplVchrID, $smplVchrDesc, "Cancelled", "None", $voidedJrnlBatchID);
                    $errMsg = $rsltCnt . " Transaction Reversal Finalized!";
                    $response = array(
                        'sbmtdSmplVchrID' => $sbmtdSmplVchrID,
                        'sbmtMsg' => $errMsg
                    );
                    echo json_encode($response);
                } else {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Failed to reverse Journal Batch!</span>";
                    exit();
                }
            }
        } else {
            if ($vwtyp == 0) {
                //Simplified Transaction Transactions
                $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=7&vtyp=999');\">
                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                <span style=\"text-decoration:none;\">Simplified Transaction Vouchers</span>
                            </li>";
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                <span style=\"text-decoration:none;\">" . $inTmpltTypLbl2 . "</span>
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
                $sbmtdSmplVchrID = isset($_POST['sbmtdSmplVchrID']) ? (float) cleanInputData($_POST['sbmtdSmplVchrID']) : -1;
                $smplVchrTrnsType = isset($_POST['smplVchrTrnsType']) ? cleanInputData($_POST['smplVchrTrnsType']) : $inTmpltTypLbl;
                $qShwUnpstdOnly = false;
                if (isset($_POST['qShwUnpstdOnly'])) {
                    $qShwUnpstdOnly = cleanInputData($_POST['qShwUnpstdOnly']) === "true" ? true : false;
                }
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                if ($vwtyp == 0) {
                    $total = get_Total_SmplVchr($srchFor, $srchIn, $orgID, $qShwUnpstdOnly, $smplVchrTrnsType);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_SmplVchrHdr($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qShwUnpstdOnly, $smplVchrTrnsType);
                    $cntr = 0;
                    $colClassType1 = "col-md-2";
                    $colClassType2 = "col-md-6";
?>
                    <form id='smplVchrForm' action='' method='post' accept-charset='UTF-8'>
                        <!--ROW ID-->
                        <input class="form-control" id="tblRowID" type="hidden" placeholder="ROW ID" />
                        <fieldset class="">
                            <legend class="basic_person_lg1" style="color: #003245"><?php echo strtoupper($inTmpltTypLbl2); ?></legend>
                            <div class="row" style="margin-bottom:0px;">
                                <?php if ($canAdd === true) { ?>
                                    <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneSmplVchrForm(-1, 1, 'ShowDialog', '<?php echo $inTmpltTyp; ?>');">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New <?php echo $inTmpltTypLbl; ?>
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
                                        <input class="form-control" id="smplVchrSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                        echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                        ?>" onkeyup="enterKeyFuncSmplVchr(event, '', '#allmodules', 'grp=6&typ=1&pg=<?php echo $pgNo; ?>&vtyp=0&inTmpltTyp=<?php echo $inTmpltTyp; ?>');">
                                        <input id="smplVchrPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getSmplVchr('clear', '#allmodules', 'grp=6&typ=1&pg=<?php echo $pgNo; ?>&vtyp=0&inTmpltTyp=<?php echo $inTmpltTyp; ?>');">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getSmplVchr('', '#allmodules', 'grp=6&typ=1&pg=<?php echo $pgNo; ?>&vtyp=0&inTmpltTyp=<?php echo $inTmpltTyp; ?>');">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="smplVchrSrchIn">
                                            <?php
                                            $valslctdArry = array("", "", "");
                                            $srchInsArrys = array("All", "Ref. Number", "Description");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                if ($srchIn == $srchInsArrys[$z]) {
                                                    $valslctdArry[$z] = "selected";
                                                }
                                            ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="smplVchrDsplySze" style="min-width:70px !important;">
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
                                                <a href="javascript:getSmplVchr('previous', '#allmodules', 'grp=6&typ=1&pg=<?php echo $pgNo; ?>&vtyp=0&inTmpltTyp=<?php echo $inTmpltTyp; ?>');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:getSmplVchr('next', '#allmodules', 'grp=6&typ=1&pg=<?php echo $pgNo; ?>&vtyp=0&inTmpltTyp=<?php echo $inTmpltTyp; ?>');" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                                <div class="<?php echo $colClassType1; ?>">
                                    <div class="form-check" style="font-size: 12px !important;">
                                        <label class="form-check-label">
                                            <?php
                                            $shwUnpstdOnlyChkd = "";
                                            if ($qShwUnpstdOnly == true) {
                                                $shwUnpstdOnlyChkd = "checked=\"true\"";
                                            }
                                            ?>
                                            <input type="checkbox" class="form-check-input" onclick="getSmplVchr('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&inTmpltTyp=<?php echo $inTmpltTyp; ?>');" id="smplVchrShwUnpstdOnly" name="smplVchrShwUnpstdOnly" <?php echo $shwUnpstdOnlyChkd; ?>>
                                            Show Only Unposted
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-responsive" id="smplVchrHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="max-width:30px;width:30px;">No.</th>
                                                <th style="max-width:30px;width:30px;">...</th>
                                                <th style="max-width:180px;width:180px;">Transaction Type/Number</th>
                                                <th>Narration/Payee</th>
                                                <th style="max-width:120px;width:120px;">Transaction Date</th>
                                                <th style="text-align:center;max-width:30px;width:30px;">CUR.</th>
                                                <th style="text-align:right;max-width:110px;width:110px;">Total Amount</th>
                                                <th style="max-width:105px;width:105px;">Reference Number/Status</th>
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
                                                <tr id="smplVchrHdrsRow_<?php echo $cntr; ?>">
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Voucher" onclick="getOneSmplVchrForm(<?php echo $row[0]; ?>, 1, 'ShowDialog', '<?php echo $inTmpltTyp; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                            <?php
                                                            if ($canEdt === true) {
                                                            ?>
                                                                <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } else { ?>
                                                                <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } ?>
                                                        </button>
                                                    </td>
                                                    <td class="lovtd"><?php echo $row[2] . " - " . $row[1]; ?></td>
                                                    <td class="lovtd" style="word-wrap: break-word;"><?php echo str_replace(" ()", "",  $row[3] . " (" . $row[6] . ")"); ?></td>
                                                    <td class="lovtd"><?php echo $row[11]; ?></td>
                                                    <td class="lovtd" style="text-align:center;font-weight: bold;color:black;"><?php echo $row[8]; ?></td>
                                                    <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php echo number_format((float) $row[9], 2); ?></td>
                                                    <?php
                                                    $style2 = "color:red;";
                                                    $style3 = "color:red;";
                                                    if ($row[12] == "Finalized") {
                                                        $style2 = "color:green;";
                                                    } else if ($row[12] == "Cancelled") {
                                                        $style2 = "color:#0d0d0d;";
                                                    }
                                                    if ($row[15] == "1") {
                                                        $style3 = "color:green;";
                                                    }
                                                    ?>
                                                    <td class="lovtd" style="font-weight:bold;"><?php
                                                                                                echo "<span style=\"" . $style2 . "\">" . $row[12] . "</span><br/><span style=\"" . $style3 . "\">" . ($row[15] == "1" ? "Posted" : "Not Posted") . "</span>";
                                                                                                ?>
                                                    </td>
                                                    <?php if ($canDel === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction" onclick="delSmplVchrHdr('smplVchrHdrsRow_<?php echo $cntr; ?>')" style="padding:2px !important;" style="padding:2px !important;">
                                                                <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                            <input type="hidden" id="smplVchrHdrsRow<?php echo $cntr; ?>_HdrID" name="smplVchrHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                        </td>
                                                    <?php } ?>
                                                    <?php
                                                    if ($canVwRcHstry === true) {
                                                    ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                    echo urlencode(encrypt1(($row[0] . '|accb.accb_smpl_vchr_hdr|smpl_vchr_hdr_id'), $smplTokenWord1));
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
                //New Simplified Transaction Form
                $sbmtdSmplVchrID = isset($_POST['sbmtdSmplVchrID']) ? (float) cleanInputData($_POST['sbmtdSmplVchrID']) : -1;
                $smplVchrTrnsType = isset($_POST['smplVchrTrnsType']) ? cleanInputData($_POST['smplVchrTrnsType']) : $inTmpltTypLbl;
                $gnrtdTrnsNo = "";
                $smplVchrSpplr = "";
                $smplVchrSpplrID = -1;
                $smplVchrSpplrSite = "";
                $smplVchrSpplrSiteID = -1;
                $smplVchrMainTmpltID = -1;
                $smplVchrMltplLines = "0";
                $smplVchrSpplrClsfctn = "Supplier";
                if ($inTmpltTyp == "INCM") {
                    $smplVchrSpplrClsfctn = "Customer";
                }
                $smplVchrTransDte = $gnrlTrnsDteDMY;
                $smplVchrDesc = "";
                $smplVchrRefNum = "";
                $rqStatus = "Not Finalized"; //approval_status
                $rqStatusNext = "Finalize"; //next_aproval_action
                $rqstatusColor = "red";

                $smplVchrTransAmnt = 0;
                $smplVchrExchngRate = 1;
                $smplVchrGLBatch = "";
                $smplVchrGLBatchID = -1;
                $smplVchrInvcCurID = $fnccurid;
                $smplVchrInvcCur = $fnccurnm;
                $smplVchrIsPstd = "0";
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                if ($sbmtdSmplVchrID > 0) {
                    $result = get_One_SmplVchrHdr($sbmtdSmplVchrID);
                    if ($row = loc_db_fetch_array($result)) {
                        $smplVchrTrnsType = $row[2];

                        $gnrtdTrnsNo = $row[1];
                        $smplVchrSpplr = $row[6];
                        $smplVchrSpplrID = (float) $row[1];
                        $smplVchrSpplrSite = $row[17];
                        $smplVchrSpplrSiteID = (float)$row[16];
                        $smplVchrMainTmpltID = (float)$row[19];
                        $smplVchrMltplLines = $row[18];
                        $smplVchrTransDte = $row[11];
                        $smplVchrDesc = $row[3];
                        $smplVchrRefNum = $row[4];
                        $smplVchrTransAmnt = (float) $row[9];
                        $smplVchrExchngRate = (float) $row[10];
                        $smplVchrGLBatch = $row[14];
                        $smplVchrGLBatchID = (float) $row[13];
                        $smplVchrInvcCurID = (float) $row[7];
                        $smplVchrInvcCur = $row[8];
                        $smplVchrIsPstd = $row[15];
                        $rqStatus = $row[12];

                        if ($rqStatus == "Finalized") {
                            $rqstatusColor = "green";
                        } else {
                            $rqstatusColor = "red";
                        }
                        if ($rqStatus == "Not Finalized") {
                            $mkReadOnly = "";
                            $mkRmrkReadOnly = "";
                        } else {
                            $canEdt = FALSE;
                            $mkReadOnly = "readonly=\"true\"";
                            if ($rqStatus != "Finalized") {
                                $mkRmrkReadOnly = "readonly=\"true\"";
                            }
                        }
                    }
                } else {
                    //$sbmtdAccbPttyCashID = getNewAccbPttyCashID();
                    $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
                    if ($usrTrnsCode == "") {
                        $usrTrnsCode = "XX";
                    }
                    $dte = date('ymd');
                    $docTypPrfx = ($smplVchrTrnsType === "Expense Voucher") ? "EXP" : "INC";
                    $gnrtdTrnsNo1 = $docTypPrfx . "-" . $usrTrnsCode . "-" . $dte . "-";
                    $gnrtdTrnsNo = $gnrtdTrnsNo1 . str_pad(((getRecCount_LstNum("accb.accb_smpl_vchr_hdr", "smpl_vchr_number", "smpl_vchr_hdr_id", $gnrtdTrnsNo1 . "%") + 1) . ""), 3, '0', STR_PAD_LEFT);
                    $sbmtdSmplVchrID = getNewSmplVchrHdrID();
                    createSmplVchrHdr(
                        $sbmtdSmplVchrID,
                        $orgID,
                        -1,
                        -1,
                        $gnrtdTrnsNo,
                        "",
                        "",
                        $smplVchrTrnsType,
                        $smplVchrInvcCurID,
                        0,
                        1,
                        $smplVchrTransDte,
                        -1,
                        false
                    );
                }

                $payTransTypHide1 = "";
                $payTransTypHide2 = "hideNotice";
                if ($smplVchrMltplLines != "0") {
                    $payTransTypHide1 = "hideNotice";
                    $payTransTypHide2 = "";
                }

                $vPsblValID1 = getEnbldPssblValID("Application Instance SHORT CODE", getLovID("All Other General Setups"));
                $vPsblVal1 = getPssblValDesc($vPsblValID1);

                $reportTitle = $inTmpltTypLbl2;
                $reportName = "Simplified Transaction Voucher";
                $rptID = getRptID($reportName);
                $prmID1 = getParamIDUseSQLRep("{:invoice_id}", $rptID);
                $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                $trnsID = $sbmtdSmplVchrID;
                $paramRepsNVals = $prmID1 . "~" . $trnsID . "|" . $prmID2 . "~" . $reportTitle . "|-130~" . $reportTitle . "|-190~PDF";
                $paramStr = urlencode($paramRepsNVals);

                $reportTitle1 = "Account Credit Letter";
                $reportName1 = "Account Credit Letter";
                $rptID1 = getRptID($reportName1);
                $prmID11 = getParamIDUseSQLRep("{:pay_rqst_id}", $rptID1);
                $prmID21 = getParamIDUseSQLRep("{:documentTitle}", $rptID1);
                $trnsID1 = $sbmtdSmplVchrID;
                $paramRepsNVals1 = $prmID11 . "~" . $trnsID1 . "|" . $prmID21 . "~" . $reportTitle1 . "|-130~" . $reportTitle1 . "|-190~PDF";
                $paramStr1 = urlencode($paramRepsNVals1);
                ?>
                <form class="form-horizontal" id="oneSmplVchrEDTForm">
                    <div class="row" style="margin-top:5px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Voucher Number/Type:</label>
                                </div>
                                <div class="col-md-2" style="padding:0px 1px 0px 15px;">
                                    <input type="text" class="form-control" aria-label="..." id="sbmtdSmplVchrID" name="sbmtdSmplVchrID" value="<?php echo $sbmtdSmplVchrID; ?>" readonly="true">
                                </div>
                                <div class="col-md-2" style="padding:0px 1px 0px 1px;">
                                    <input type="text" class="form-control" aria-label="..." id="smplVchrTrnsType" name="smplVchrTrnsType" value="<?php echo $smplVchrTrnsType; ?>" readonly="true">
                                </div>
                                <div class="col-md-4" style="padding:0px 15px 0px 1px;">
                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="smplVchrTrnsNum" name="smplVchrTrnsNum" value="<?php echo $gnrtdTrnsNo; ?>" <?php echo $mkReadOnly; ?>>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Main Transaction Template:</label>
                                </div>
                                <div class="col-md-8">
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="smplVchrMainTmpltID" style="width:100% !important;">
                                        <option value="-1">None</option>
                                        <?php
                                        $brghtStr = "";
                                        $isDynmyc = TRUE;
                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Account Transaction Templates"), $isDynmyc, $orgID, "" . $usrID, "", " and (tbl1.g='" . $smplVchrTrnsType . "')");
                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                            $selectedTxt = "";
                                            if (((int) $titleRow[0]) == $smplVchrMainTmpltID) {
                                                $selectedTxt = "selected";
                                            }
                                        ?>
                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[1]; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Transaction Date:</label>
                                </div>
                                <div class="col-md-5" style="padding:0px 1px 0px 15px;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 1px 0px 1px !important;">
                                        <input class="form-control rqrdFld" size="16" type="text" id="smplVchrTransDte" name="smplVchrTransDte" value="<?php echo $smplVchrTransDte; ?>" placeholder="DD-MMM-YYYY">
                                        <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding:0px 15px 0px 1px;">
                                    <div class="form-check" style="font-size: 12px !important;">
                                        <label class="form-check-label">
                                            <?php
                                            $smplVchrMltplLinesChkd = "";
                                            if ($smplVchrMltplLines == "1") {
                                                $smplVchrMltplLinesChkd = "checked=\"true\"";
                                            }
                                            ?>
                                            <input type="checkbox" class="form-check-input" id="smplVchrMltplLines" name="smplVchrMltplLines" <?php echo $smplVchrMltplLinesChkd; ?> onchange="shwHideIncExpVhcrDivs();">
                                            Is Multi-Line Transaction?
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <?php
                                $payeeLabel = "Payee/Reference Num";
                                if ($vPsblVal1 == "TAKBG_SWLFR_APP_1") {
                                    $payeeLabel = "Payee/Account Number";
                                }
                                ?>
                                <label for="smplVchrSpplr" class="control-label col-md-4"><?php echo $payeeLabel; ?>:</label>
                                <div class="col-md-5" style="padding:0px 1px 0px 15px;">
                                    <div class="input-group">
                                        <input type="text" class="form-control" aria-label="..." id="smplVchrSpplr" name="smplVchrSpplr" value="<?php echo $smplVchrSpplr; ?>" readonly="true">
                                        <input type="hidden" id="smplVchrSpplrID" value="<?php echo $smplVchrSpplrID; ?>">
                                        <input type="hidden" id="smplVchrSpplrClsfctn" value="<?php echo $smplVchrSpplrClsfctn; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getCstmrSpplrForm(-1, 'Create/Edit Payee', 'ShowDialog', function () {}, 'smplVchrSpplrID');" data-toggle="tooltip" title="Create/Edit Payee">
                                            <span class="glyphicon glyphicon-plus"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'allOtherInputOrgID', '', 'smplVchrSpplrClsfctn', 'radio', true, '', 'smplVchrSpplrID', 'smplVchrSpplr', 'clear', 1, '',function(){afterVchrPayeeSlct();});" data-toggle="tooltip" title="Existing Client/Vendor">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding:0px 15px 0px 1px;">
                                    <input type="text" class="form-control" aria-label="..." id="smplVchrRefNum" name="smplVchrRefNum" value="<?php echo $smplVchrRefNum; ?>" <?php echo $mkReadOnly; ?>>
                                </div>
                            </div>
                            <div class="form-group" style="display:none;">
                                <label for="smplVchrSpplrSite" class="control-label col-md-3">Site:</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <input type="text" class="form-control" aria-label="..." id="smplVchrSpplrSite" name="smplVchrSpplrSite" value="<?php echo $smplVchrSpplrSite; ?>" readonly="true">
                                        <input class="form-control" type="hidden" id="smplVchrSpplrSiteID" value="<?php echo $smplVchrSpplrSiteID; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Customer/Supplier Sites', 'smplVchrSpplrID', '', '', 'radio', true, '', 'smplVchrSpplrSiteID', 'smplVchrSpplrSite', 'clear', 1, '');" data-toggle="tooltip" title="">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="margin-bottom:0px !important;">Total Amount/Exch. Rate:</label>
                                </div>
                                <div class="col-md-5" style="padding:0px 1px 0px 15px;">
                                    <div class="input-group">
                                        <label class="btn btn-info btn-file input-group-addon active" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $smplVchrInvcCur; ?>', 'smplVchrInvcCur', '', 'clear', 0, '', function () {
                                                    $('#smplVchrInvcCur1').html($('#smplVchrInvcCur').val());
                                                    $('#smplVchrInvcCur2').html($('#smplVchrInvcCur').val());
                                                    $('#smplVchrInvcCur3').html($('#smplVchrInvcCur').val());
                                                });">
                                            <span class="" style="font-size: 20px !important;" id="smplVchrInvcCur1"><?php echo $smplVchrInvcCur; ?></span>
                                        </label>
                                        <input type="hidden" id="smplVchrInvcCur" value="<?php echo $smplVchrInvcCur; ?>">
                                        <input class="form-control rqrdFldLght fundNumber" type="text" id="smplVchrTransAmnt" value="<?php
                                                                                                                                        echo number_format($smplVchrTransAmnt, 2);
                                                                                                                                        ?>" style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('smplVchrTransAmnt');" <?php echo $mkReadOnly; ?> />
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding:0px 15px 0px 1px;">
                                    <div class="input-group">
                                        <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                            <span class="" style="font-size: 20px !important;" id="smplVchrInvcCur3"><?php echo $smplVchrInvcCur; ?></span>
                                            <span class="" style="font-size: 20px !important;"><?php echo "&nbsp;to " . $fnccurnm; ?></span>
                                        </label>
                                        <input class="form-control fundNumber" type="text" id="smplVchrExchngRate" value="<?php
                                                                                                                            echo number_format($smplVchrExchngRate, 4);
                                                                                                                            ?>" style="font-weight:bold;width:100%;font-size:18px !important;" <?php echo $mkReadOnly; ?> />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label style="">Remark / Narration:</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group" style="width:100%;">
                                        <textarea class="form-control rqrdFld" rows="3" cols="20" id="smplVchrDesc" name="smplVchrDesc" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $smplVchrDesc; ?></textarea>
                                        <input class="form-control" type="hidden" id="smplVchrDesc1" value="<?php echo $smplVchrDesc; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('smplVchrDesc');" style="max-width:30px;width:30px;">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="smplVchrGLBatch" class="control-label col-md-4">GL Batch Name:</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input class="form-control" id="smplVchrGLBatch" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" placeholder="" value="<?php echo $smplVchrGLBatch; ?>" readonly="true" />
                                        <input type="hidden" id="smplVchrGLBatchID" value="<?php echo $smplVchrGLBatchID; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getOneJrnlBatchForm(<?php echo $smplVchrGLBatchID; ?>, 1, 'ReloadDialog',<?php echo $sbmtdSmplVchrID; ?>, '<?php echo $inTmpltTypLbl2; ?>');">
                                            <img src="cmn_images/openfileicon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Open
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" style="padding:1px 15px 1px 15px !important;">
                            <hr style="margin:2px 0px 2px 0px;">
                        </div>
                        <div class="col-md-12 <?php echo $payTransTypHide2; ?>" id="payTransItemsDiv" style="margin-top:2px;padding:0px 0px !important;">
                            <?php
                            $nwRowHtml1 = "<tr id=\"payTrnTypClsfctnsRow__WWW123WWW\" class=\"hand_cursor\">
                                                    <td class=\"lovtd\">New
                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"payTrnTypClsfctnsRow_WWW123WWW_LineID\" value=\"-1\">
                                                    </td>
                                                    <td class=\"lovtd\">
                                                        <select data-placeholder=\"Select...\" class=\"form-control chosen-select rqrdFld\" id=\"payTrnTypClsfctnsRow_WWW123WWW_TmpltID\" style=\"width:100% !important;\">";
                            $brghtStr = "";
                            $isDynmyc = TRUE;
                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Account Transaction Templates"), $isDynmyc, $orgID, "" . $usrID, "2");
                            while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                $nwRowHtml1 .= "<option value=\"" . $titleRow[0] . "\">" . $titleRow[1] . "</option>";
                            }
                            $nwRowHtml1 .= "</select>
                                                    </td>
                                                    <td class=\"lovtd\">
                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"payTrnTypClsfctnsRow_WWW123WWW_Desc\" value=\"\" style=\"width:100% !important;margin-left:0px !important;\">
                                                    </td>
                                                    <td class=\"lovtd\" style=\"text-align: center;\">
                                                        <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"payTrnTypClsfctnsRow_WWW123WWW_Amount\" value=\"0.00\" style=\"width:100% !important;margin-left:0px !important;\" onchange=\"fmtAsNumber('payTrnTypClsfctnsRow_WWW123WWW_Amount');\">
                                                    </td>
                                                    <td class=\"lovtd\">
                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"payTrnTypClsfctnsRow_WWW123WWW_Date\" value=\"\" style=\"width:100% !important;margin-left:0px !important;\">
                                                    </td>
                                                      <td class=\"lovtd\">
                                                          <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delSmplVchrHdrLn('payTrnTypClsfctnsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Line\">
                                                              <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                          </button>
                                                      </td>
                                                      <td class=\"lovtd\">&nbsp;</td>
                                                </tr>";
                            $nwRowHtml = urlencode($nwRowHtml1);
                            ?>
                            <div class="col-md-12">
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('payTrnTypClsfctnsTable', 0, '<?php echo $nwRowHtml; ?>');" style="width:100% !important;">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Addtional Transaction Line
                                </button>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="payTrnTypClsfctnsTable" cellspacing="0" width="100%" style="width:100%;border:1px solid #ddd;border-radius:5px !important;">
                                    <thead>
                                        <tr>
                                            <th style="max-width:30px;width:30px;">No.</th>
                                            <th style="max-width:150px;width:150px;">Template Name</th>
                                            <th>Transaction Description</th>
                                            <th style="max-width:120px;width:120px;">Amount</th>
                                            <th style="max-width: 125px !important;width: 125px !important;text-align: center;">Date</th>
                                            <th style="max-width:20px;width:20px;">...</th>
                                            <th style="max-width:20px;width:20px;">...</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $resultClsfctn = get_One_SmplVchrLines($sbmtdSmplVchrID);
                                        $cntr = 0;
                                        $curIdx = 0;
                                        $lmtSze = 100;
                                        while ($row1 = loc_db_fetch_array($resultClsfctn)) {
                                            $cntr += 1;
                                        ?>
                                            <tr id="payTrnTypClsfctnsRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="payTrnTypClsfctnsRow<?php echo $cntr; ?>_LineID" value="<?php echo $row1[0]; ?>">
                                                </td>
                                                <td class="lovtd">
                                                    <input type="hidden" class="form-control" aria-label="..." id="payTrnTypClsfctnsRow<?php echo $cntr; ?>_TmpltID" value="<?php echo $row1[1]; ?>">
                                                    <?php if ($canEdt === true) { ?>
                                                        <input type="text" class="form-control" aria-label="..." id="payTrnTypClsfctnsRow<?php echo $cntr; ?>_Name" value="<?php echo $row1[5]; ?>" style="width:100% !important;margin-left:0px !important;">
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[5]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdt === true) { ?>
                                                        <input type="text" class="form-control" aria-label="..." id="payTrnTypClsfctnsRow<?php echo $cntr; ?>_Desc" value="<?php echo $row1[2]; ?>" style="width:100% !important;margin-left:0px !important;">
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row1[2]; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="payTrnTypClsfctnsRow<?php echo $cntr; ?>_Amount" value="<?php echo number_format((float) $row1[3], 2); ?>" onchange="fmtAsNumber('payTrnTypClsfctnsRow<?php echo $cntr; ?>_Amount');" style="width:100% !important;margin-left:0px !important;">
                                                </td>
                                                <td class="lovtd">
                                                    <input type="text" class="form-control" aria-label="..." id="payTrnTypClsfctnsRow<?php echo $cntr; ?>_Date" value="<?php echo $row1[4]; ?>" style="width:100% !important;margin-left:0px !important;">
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canDel === true) { ?>
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delSmplVchrHdrLn('payTrnTypClsfctnsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Line">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    <?php } ?>
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row1[0] . '|accb.accb_smpl_vchr_det|smpl_vchr_det_id'), $smplTokenWord1)); ?>');" style="padding:2px !important;">
                                                            <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12" style="padding:1px 15px 1px 15px !important;">
                            <hr style="margin:2px 0px 2px 0px;">
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-5" style="float:left;">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <?php if ($sbmtdSmplVchrID > 0) { ?>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOnePayInvstTransDocsForm(<?php echo $sbmtdSmplVchrID; ?>, 20);" data-toggle="tooltip" data-placement="bottom" title="Attached Documents">
                                            <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                        </button>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="width:100% !important;">
                                            <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            Voucher
                                        </button>
                                        <?php if ($vPsblVal1 == "TAKBG_SWLFR_APP_1") { ?>
                                            <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getSilentRptsRnSts(<?php echo $rptID1; ?>, -1, '<?php echo $paramStr1; ?>');" style="width:100% !important;">
                                                <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Letter
                                            </button>
                                    <?php }
                                    } ?>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneSmplVchrForm(<?php echo $sbmtdSmplVchrID; ?>, 1, 'ReloadDialog', '<?php echo $inTmpltTyp; ?>');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                </div>
                                <div class="col-md-7">
                                    <div class="" style="padding:0px 0px 0px 0px;float:right !important;">
                                        <?php
                                        $style2 = "color:red;";
                                        $style3 = "color:red;";
                                        if ($rqStatus == "Finalized") {
                                            $style2 = "color:green;";
                                        } else if ($rqStatus == "Cancelled") {
                                            $style2 = "color:#0d0d0d;";
                                        }
                                        if ($smplVchrIsPstd == "1") {
                                            $style3 = "color:green;";
                                        }
                                        ?>
                                        <button type="button" class="btn btn-default" id="mySmplVchrStatusBtn">
                                            <span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;height:30px;"><?php
                                                                                                                                echo "<span style=\"" . $style2 . "\">" . $rqStatus . "</span><span style=\"" . $style3 . "\">" . ($smplVchrIsPstd == "1" ? " [Posted]" : " [Not Posted]") . "</span>";
                                                                                                                                ?>
                                            </span>
                                        </button>
                                        <?php
                                        if ($rqStatus == "Not Finalized") {
                                        ?>
                                            <?php if (($canEdt === true)) {
                                            ?>
                                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveSmplVchrForm('<?php echo $fnccurnm; ?>', 0, '<?php echo $inTmpltTyp; ?>');"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>
                                            <?php
                                            }
                                            if (($canApprove === true)) {
                                            ?>
                                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveSmplVchrForm('<?php echo $fnccurnm; ?>', 2, '<?php echo $inTmpltTyp; ?>');" data-toggle="tooltip" data-placement="bottom" title="Finalize Document">
                                                    <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Finalize
                                                </button>
                                            <?php } ?>
                                            <?php
                                        } else if ($rqStatus == "Finalized") {
                                            if ($canVoid && ($canDel === true)) {
                                            ?>
                                                <button id="fnlzeRvrslSmplVchrBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveSmplVchrRvrslForm('<?php echo $fnccurnm; ?>', 1, '<?php echo $inTmpltTyp; ?>');"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Void Transaction&nbsp;</button>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                </form>
<?php
            }
        }
    }
}
?>