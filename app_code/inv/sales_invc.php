<?php
$scmSalesPrmSnsRstl = getScmSalesPrmssns($orgID);
$addRecsPFI = ($scmSalesPrmSnsRstl[0] >= 1) ? true : false;
$editRecsPFI = ($scmSalesPrmSnsRstl[1] >= 1) ? true : false;
$delRecsPFI = ($scmSalesPrmSnsRstl[2] >= 1) ? true : false;
$addRecsSO = ($scmSalesPrmSnsRstl[3] >= 1) ? true : false;
$editRecsSO = ($scmSalesPrmSnsRstl[4] >= 1) ? true : false;
$delRecsSO = ($scmSalesPrmSnsRstl[5] >= 1) ? true : false;
$addRecsSI = ($scmSalesPrmSnsRstl[6] >= 1) ? true : false;
$editRecsSI = ($scmSalesPrmSnsRstl[7] >= 1) ? true : false;
$delRecsSI = ($scmSalesPrmSnsRstl[8] >= 1) ? true : false;
$addRecsIIR = ($scmSalesPrmSnsRstl[9] >= 1) ? true : false;
$editRecsIIR = ($scmSalesPrmSnsRstl[10] >= 1) ? true : false;
$delRecsIIR = ($scmSalesPrmSnsRstl[11] >= 1) ? true : false;
$addRecsIIU = ($scmSalesPrmSnsRstl[12] >= 1) ? true : false;
$editRecsIIU = ($scmSalesPrmSnsRstl[13] >= 1) ? true : false;
$delRecsIIU = ($scmSalesPrmSnsRstl[14] >= 1) ? true : false;
$addRecsSR = ($scmSalesPrmSnsRstl[15] >= 1) ? true : false;
$editRecsSR = ($scmSalesPrmSnsRstl[16] >= 1) ? true : false;
$delRecsSR = ($scmSalesPrmSnsRstl[17] >= 1) ? true : false;
$vwOnlySelf = ($scmSalesPrmSnsRstl[18] >= 1) ? true : false;
$canPayDocs = ($scmSalesPrmSnsRstl[19] >= 1) ? true : false;
$cancelDocs = ($scmSalesPrmSnsRstl[20] >= 1) ? true : false;
$canEdtPrice = ($scmSalesPrmSnsRstl[21] >= 1) ? true : false;

$canAdd = $addRecsPFI || $addRecsSO || $addRecsSI || $addRecsIIR || $addRecsIIU || $addRecsSR;
$canEdt = $editRecsPFI || $editRecsSO || $editRecsSI || $editRecsIIR || $editRecsIIU || $editRecsSR;
$canDel = $delRecsPFI || $delRecsSO || $delRecsSI || $delRecsIIR || $delRecsIIU || $delRecsSR;

$canRvwApprvDocs = $canAdd || $canEdt;

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
$defaultBrkdwnLOV = "Transaction Amount Breakdown Parameters";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Doc Header */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteSalesDocHdrNDet($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Doc Header Line */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteSalesDocLine($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
                
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Sales Invoice Transaction
                header("content-type:application/json");
                $sbmtdScmSalesInvcID = isset($_POST['sbmtdScmSalesInvcID']) ? (float) cleanInputData($_POST['sbmtdScmSalesInvcID']) : -1;
                $scmSalesInvcDocNum = isset($_POST['scmSalesInvcDocNum']) ? cleanInputData($_POST['scmSalesInvcDocNum']) : "";
                $scmSalesInvcDfltTrnsDte = isset($_POST['scmSalesInvcDfltTrnsDte']) ? cleanInputData($_POST['scmSalesInvcDfltTrnsDte']) : '';
                $scmSalesInvcVchType = isset($_POST['scmSalesInvcVchType']) ? cleanInputData($_POST['scmSalesInvcVchType']) : 'Sales Invoice';
                $scmSalesInvcClssfctn = isset($_POST['scmSalesInvcClssfctn']) ? cleanInputData($_POST['scmSalesInvcClssfctn']) : 'Standard';
                if ($sbmtdScmSalesInvcID <= 0) {
                    $sbmtdScmRtrnSrcDocID = isset($_POST['sbmtdScmRtrnSrcDocID']) ? cleanInputData($_POST['sbmtdScmRtrnSrcDocID']) : -1;
                    $scmSalesAction = isset($_POST['scmSalesAction']) ? cleanInputData($_POST['scmSalesAction']) : "NOTNEW";
                    $musAllwDues = isset($_POST['musAllwDues']) ? cleanInputData($_POST['musAllwDues']) : "NO";
                    $srcCaller = isset($_POST['srcCaller']) ? cleanInputData($_POST['srcCaller']) : "SALES";
                    $qckPayPrsns_PrsnID = isset($_POST['qckPayPrsns_PrsnID']) ? (float) $_POST['qckPayPrsns_PrsnID'] : -1;
                    $qckPayItmSetID = isset($_POST['qckPayItmSetID']) ? (float) $_POST['qckPayItmSetID'] : -1;

                    IF ($scmSalesAction == "ISNEW") {
                        if (!$canAdd || ($sbmtdScmSalesInvcID > 0 && !$canEdt)) {
                            restricted();
                            exit();
                        }
                        if ($scmSalesInvcVchType == "Pro-Forma Invoice" && (!$addRecsPFI || ($sbmtdScmSalesInvcID > 0 && !$editRecsPFI))) {
                            restricted();
                            exit();
                        }
                        if ($scmSalesInvcVchType == "Sales Order" && (!$addRecsSO || ($sbmtdScmSalesInvcID > 0 && !$editRecsSO))) {
                            restricted();
                            exit();
                        }
                        if ($scmSalesInvcVchType == "Sales Invoice" && (!$addRecsSI || ($sbmtdScmSalesInvcID > 0 && !$editRecsSI))) {
                            restricted();
                            exit();
                        }
                        if ($scmSalesInvcVchType == "Internal Item Request" && (!$addRecsIIR || ($sbmtdScmSalesInvcID > 0 && !$editRecsIIR))) {
                            restricted();
                            exit();
                        }
                        if ($scmSalesInvcVchType == "Item Issue-Unbilled" && (!$addRecsIIU || ($sbmtdScmSalesInvcID > 0 && !$editRecsIIU))) {
                            restricted();
                            exit();
                        }
                        if ($scmSalesInvcVchType == "Sales Return" && (!$addRecsSR || ($sbmtdScmSalesInvcID > 0 && !$editRecsSR))) {
                            restricted();
                            exit();
                        }
                    }
                    $orgnlScmSalesInvcID = $sbmtdScmSalesInvcID;
                    $scmSalesInvcDfltTrnsDte = $gnrlTrnsDteDMYHMS;
                    $scmSalesInvcCreator = $uName;
                    $scmSalesInvcCreatorID = $usrID;
                    $gnrtdTrnsNo = "";
                    $scmSalesInvcDesc = "";

                    $srcSalesInvcDocID = -1;
                    $srcSalesInvcDocTyp = "";
                    if ($scmSalesInvcVchType == "Pro-Forma Invoice") {
                        $srcSalesInvcDocTyp = "";
                    } elseif ($scmSalesInvcVchType == "Sales Order") {
                        $srcSalesInvcDocTyp = "Pro-Forma Invoice";
                    } elseif ($scmSalesInvcVchType == "Sales Invoice") {
                        $srcSalesInvcDocTyp = "Sales Order";
                    } elseif ($scmSalesInvcVchType == "Internal Item Request") {
                        $srcSalesInvcDocTyp = "";
                    } elseif ($scmSalesInvcVchType == "Item Issue-Unbilled") {
                        $srcSalesInvcDocTyp = "Internal Item Request";
                    } elseif ($scmSalesInvcVchType == "Sales Return") {
                        $srcSalesInvcDocTyp = "Sales Invoice";
                    }
                    $scmSalesInvcClssfctn = "Standard";
                    $scmSalesInvcDocTmpltID = -1;
                    $srcSalesInvcDocNum = "";
                    $scmSalesInvcBrnchID = $brnchLocID;
                    $scmSalesInvcBrnchNm = $brnchLoc;

                    $scmSalesInvcCstmr = "";
                    $scmSalesInvcCstmrID = -1;
                    $scmSalesInvcCstmrSite = "";
                    $scmSalesInvcCstmrSiteID = -1;
                    if ($qckPayPrsns_PrsnID > 0) {
                        $scmSalesInvcCstmrID = getLnkdPrsnCstmrSpplrID($qckPayPrsns_PrsnID);
                        $scmSalesInvcCstmr = getPrsnFullNm($qckPayPrsns_PrsnID) . " (" . getPersonLocID($qckPayPrsns_PrsnID) . ")";
                        $gender = getGnrlRecNm("prs.prsn_names_nos", "person_id", "gender", $qckPayPrsns_PrsnID);
                        $dob = getGnrlRecNm("prs.prsn_names_nos", "person_id", "date_of_birth", $qckPayPrsns_PrsnID);
                        $cstmrLbltyAcntID = get_DfltPyblAcnt($orgID);
                        $cstmrRcvblsAcntID = get_DfltRcvblAcnt($orgID);
                        if ($scmSalesInvcCstmrID <= 0) {
                            createCstmrLnkdPrsn($scmSalesInvcCstmr, $scmSalesInvcCstmr, "Individual", "Customer", $orgID,
                                    $cstmrLbltyAcntID, $cstmrRcvblsAcntID, $qckPayPrsns_PrsnID, $gender, $dob, "1", "",
                                    "", "", "", "", "", "", "", 0, "", "");
                            $scmSalesInvcCstmrID = getLnkdPrsnCstmrSpplrID($qckPayPrsns_PrsnID);
                            if ($scmSalesInvcCstmrID > 0) {
                                $cstmrSiteCnt = (int) getGnrlRecNm("scm.scm_cstmr_suplr_sites", "cust_supplier_id", "count(cust_sup_site_id)", $scmSalesInvcCstmrID);
                                if ($cstmrSiteCnt <= 0) {
                                    createCstmrSiteLnkdPrsn($scmSalesInvcCstmrID, "To be Specified", "", "", "HEAD OFFICE-" . $scmSalesInvcCstmr, "HEAD OFFICE-" . $scmSalesInvcCstmr, "", "", "", -1, -1, "",
                                            "", "", "", "", "", "", "", "", "1", "", $fnccurid);
                                }
                            }
                        }
                        $scmSalesInvcCstmrSiteID = get_DfltCstmrSpplrSiteID($scmSalesInvcCstmrID);
                        $scmSalesInvcCstmrSite = getCstmrSiteNm($scmSalesInvcCstmrSiteID, $scmSalesInvcCstmrID);
                    }
                    $scmSalesInvcCstmrClsfctn = "Customer";
                    $rqStatus = "Not Validated";
                    $rqStatusNext = "Approve";
                    $rqstatusColor = "red";

                    $scmSalesInvcTtlAmnt = 0;
                    $scmSalesInvcAppldAmnt = 0;
                    $scmSalesInvcPayTerms = "";
                    $scmSalesInvcPayMthd = "";
                    $scmSalesInvcPayMthdID = -1;
                    $scmSalesInvcPaidAmnt = 0;
                    $scmSalesInvcGLBatch = "";
                    $scmSalesInvcGLBatchID = -1;
                    $scmSalesInvcCstmrInvcNum = "";
                    $scmSalesInvcDocTmplt = "";
                    $scmSalesInvcEvntRgstr = "";
                    $scmSalesInvcEvntRgstrID = -1;
                    $scmSalesInvcEvntCtgry = "";
                    $scmSalesInvcEvntDocTyp = "";
                    $scmSalesInvcDfltBalsAcnt = "";
                    $scmSalesInvcInvcCur = $fnccurnm;
                    $scmSalesInvcIsPstd = "0";
                    $scmSalesInvcAllwDues = "0";
                    if ($musAllwDues == "YES") {
                        $scmSalesInvcAllwDues = "1";
                    }
                    $duesPayCls = "hideNotice";
                    if ($scmSalesInvcAllwDues === '1') {
                        $duesPayCls = "";
                    }
                    $scmSalesInvcAutoBals = "1";
                    $scmSalesInvcExRate = 1;
                    $otherModuleDocId = -1;
                    $otherModuleDocTyp = "";
                    $otherModuleDocNum = "";
                    $accbRcvblAmtApldElswhr = 0;
                    $accbRcvblDebtGlBatchID = -1;
                    $sbmtdScmRcvblsInvcID = -1;
                    $accbRcvblDebtGlBatchNm = "";
                    $scmSalesInvcRcvblDocID = -1;
                    $scmSalesInvcRcvblDoc = "";
                    $scmSalesInvcRcvblDocType = "";
                    $mkReadOnly = "";
                    $mkRmrkReadOnly = "";
                    $scmSalesInvcPyItmSetID = -1;
                    $scmSalesInvcPyItmSetNm = "";
                    if ($qckPayItmSetID > 0) {
                        $scmSalesInvcPyItmSetID = $qckPayItmSetID;
                        $scmSalesInvcPyItmSetNm = getGnrlRecNm("pay.pay_itm_sets_hdr", "hdr_id", "itm_set_name", $qckPayItmSetID);
                    }
                    $scmSalesInvcPyAmntGvn = 0;
                    $scmSalesInvcPyChqNumber = "";
                    $scmSalesInvcPySignCode = "";
                    $scmSalesInvcAplyAdvnc = "1";
                    $scmSalesInvcKeepExcss = "1";
                    $scmSalesInvcDfltBalsAcntID = get_DfltCstmrRcvblsCashAcnt($scmSalesInvcCstmrID, $orgID);
                    $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
                    if ($usrTrnsCode == "") {
                        $usrTrnsCode = "XX";
                    }
                    $dte = date('ymd');
                    $docTypes = array("Pro-Forma Invoice", "Sales Order", "Sales Invoice",
                        "Internal Item Request", "Item Issue-Unbilled", "Sales Return");
                    $docTypPrfxs = array("PFI", "SO", "SI", "IIR", "IIU", "SR");

                    $docTypPrfx = $docTypPrfxs[findArryIdx($docTypes, $scmSalesInvcVchType)];
                    $gnrtdTrnsNo1 = $docTypPrfx . "-" . $usrTrnsCode . "-" . $dte . "-";
                    $gnrtdTrnsNo = $gnrtdTrnsNo1 . str_pad(((getRecCount_LstNum("scm.scm_sales_invc_hdr", "invc_number",
                                            "invc_hdr_id", $gnrtdTrnsNo1 . "%") + 1) . ""), 3, '0', STR_PAD_LEFT);
                    $scmSalesInvcDfltBalsAcnt = getAccntNum($scmSalesInvcDfltBalsAcntID) . "." . getAccntName($scmSalesInvcDfltBalsAcntID);
                    $scmSalesInvcInvcCurID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id",
                                    $scmSalesInvcDfltBalsAcntID);
                    if ($scmSalesInvcInvcCurID > 0) {
                        $scmSalesInvcInvcCur = getPssblValNm($scmSalesInvcInvcCurID);
                    } else {
                        $scmSalesInvcInvcCurID = $fnccurid;
                        $scmSalesInvcInvcCur = $fnccurnm;
                    }
                    if ($scmSalesInvcDesc == "" && $scmSalesInvcAllwDues === '1') {
                        //$exitErrMsg .= "Please enter Description!<br/>";
                        $scmSalesInvcDesc = "Dues/Bills Quick Pay for " . getCstmrSpplrName1($scmSalesInvcCstmrID) . " on " . $scmSalesInvcDfltTrnsDte;
                    }
                    $scmSalesInvcDocNum = $gnrtdTrnsNo;
                    createSalesDocHdr($orgID, $gnrtdTrnsNo, $scmSalesInvcDesc, $scmSalesInvcVchType,
                            $scmSalesInvcDfltTrnsDte, $scmSalesInvcPayTerms, $scmSalesInvcCstmrID, $scmSalesInvcCstmrSiteID,
                            $rqStatus, $rqStatusNext, $srcSalesInvcDocID, $scmSalesInvcDfltBalsAcntID,
                            $scmSalesInvcPayMthdID, $scmSalesInvcInvcCurID, $scmSalesInvcExRate, $otherModuleDocId,
                            $otherModuleDocTyp, $scmSalesInvcAutoBals, $scmSalesInvcEvntRgstrID, $scmSalesInvcEvntCtgry,
                            $scmSalesInvcAllwDues, $scmSalesInvcEvntDocTyp, $srcSalesInvcDocTyp, $scmSalesInvcBrnchID, $scmSalesInvcClssfctn,
                            $scmSalesInvcPyItmSetID, $scmSalesInvcPyAmntGvn, $scmSalesInvcPyChqNumber,
                            $scmSalesInvcPySignCode, $scmSalesInvcAplyAdvnc, $scmSalesInvcKeepExcss);

                    $sbmtdScmSalesInvcID = getGnrlRecID("scm.scm_sales_invc_hdr", "invc_number", "invc_hdr_id",
                            $gnrtdTrnsNo, $orgID);

                    if ($sbmtdScmSalesInvcID > 0 && $qckPayPrsns_PrsnID > 0 && $scmSalesInvcCstmrID > 0 && $scmSalesInvcAllwDues === '1') {
                        load_dues_attchd_vals($sbmtdScmSalesInvcID, $selectedStoreID);
                    }
                }
                $scmSalesInvcInvcCur = isset($_POST['scmSalesInvcInvcCur']) ? cleanInputData($_POST['scmSalesInvcInvcCur']) : $fnccurnm;
                $curLovID = getLovID("Currencies");
                $scmSalesInvcInvcCurID = getPssblValID($scmSalesInvcInvcCur, $curLovID);
                $scmSalesInvcTtlAmnt = isset($_POST['scmSalesInvcTtlAmnt']) ? (float) cleanInputData($_POST['scmSalesInvcTtlAmnt']) : 0;
                $scmSalesInvcExRate = isset($_POST['scmSalesInvcExRate']) ? (float) cleanInputData($_POST['scmSalesInvcExRate']) : 1.0000;

                $funcExchRate = round(get_LtstExchRate($scmSalesInvcInvcCurID, $fnccurid, $scmSalesInvcDfltTrnsDte), 4);
                if ($scmSalesInvcExRate == 0 || $scmSalesInvcExRate == 1) {
                    $scmSalesInvcExRate = $funcExchRate;
                }
                $scmSalesInvcEvntDocTyp = isset($_POST['scmSalesInvcEvntDocTyp']) ? cleanInputData($_POST['scmSalesInvcEvntDocTyp']) : '';
                $scmSalesInvcEvntCtgry = isset($_POST['scmSalesInvcEvntCtgry']) ? cleanInputData($_POST['scmSalesInvcEvntCtgry']) : '';
                $scmSalesInvcEvntRgstrID = isset($_POST['scmSalesInvcEvntRgstrID']) ? (float) cleanInputData($_POST['scmSalesInvcEvntRgstrID']) : -1;
                $scmSalesInvcCstmrID = isset($_POST['scmSalesInvcCstmrID']) ? (float) cleanInputData($_POST['scmSalesInvcCstmrID']) : -1;
                $scmSalesInvcCstmrSiteID = isset($_POST['scmSalesInvcCstmrSiteID']) ? (float) cleanInputData($_POST['scmSalesInvcCstmrSiteID']) : -1;
                $scmSalesInvcBrnchID = isset($_POST['scmSalesInvcBrnchID']) ? (float) cleanInputData($_POST['scmSalesInvcBrnchID']) : $brnchLocID;
                $scmSalesInvcDfltBalsAcntID = isset($_POST['scmSalesInvcDfltBalsAcntID']) ? (float) cleanInputData($_POST['scmSalesInvcDfltBalsAcntID']) : -1;
                $scmSalesInvcDesc = isset($_POST['scmSalesInvcDesc']) ? cleanInputData($_POST['scmSalesInvcDesc']) : '';
                $scmSalesInvcPayTerms = isset($_POST['scmSalesInvcPayTerms']) ? cleanInputData($_POST['scmSalesInvcPayTerms']) : '';

                $scmSalesInvcPayMthdID = isset($_POST['scmSalesInvcPayMthdID']) ? (int) cleanInputData($_POST['scmSalesInvcPayMthdID']) : -10;
                $scmSalesInvcCstmrInvcNum = isset($_POST['scmSalesInvcCstmrInvcNum']) ? cleanInputData($_POST['scmSalesInvcCstmrInvcNum']) : '';
                $srcSalesInvcDocID = isset($_POST['srcSalesInvcDocID']) ? (float) cleanInputData($_POST['srcSalesInvcDocID']) : -1;
                $slctdDetTransLines = isset($_POST['slctdDetTransLines']) ? cleanInputData($_POST['slctdDetTransLines']) : '';
                $shdSbmt = isset($_POST['shdSbmt']) ? (int) cleanInputData($_POST['shdSbmt']) : 0;

                $scmSalesInvcPyItmSetID = isset($_POST['scmSalesInvcPyItmSetID']) ? (int) cleanInputData($_POST['scmSalesInvcPyItmSetID']) : -1;
                $scmSalesInvcPyAmntGvn = isset($_POST['scmSalesInvcPyAmntGvn']) ? (float) cleanInputData($_POST['scmSalesInvcPyAmntGvn']) : 0;
                $scmSalesInvcPyChqNumber = isset($_POST['scmSalesInvcPyChqNumber']) ? cleanInputData($_POST['scmSalesInvcPyChqNumber']) : '';
                $scmSalesInvcPySignCode = isset($_POST['scmSalesInvcPySignCode']) ? cleanInputData($_POST['scmSalesInvcPySignCode']) : '';
                $scmSalesInvcAllwDues = isset($_POST['scmSalesInvcAllwDues']) ? cleanInputData($_POST['scmSalesInvcAllwDues']) : 'NO';
                $scmSalesInvcAplyAdvnc = isset($_POST['scmSalesInvcAplyAdvnc']) ? cleanInputData($_POST['scmSalesInvcAplyAdvnc']) : 'YES';
                $scmSalesInvcKeepExcss = isset($_POST['scmSalesInvcKeepExcss']) ? cleanInputData($_POST['scmSalesInvcKeepExcss']) : 'YES';

                $accbRcvblDebtGlBatchID = -1;
                $otherModuleDocId = -1;
                $otherModuleDocTyp = "";
                if (strlen($scmSalesInvcDesc) > 499) {
                    $scmSalesInvcDesc = substr($scmSalesInvcDesc, 0, 499);
                }
                if ($scmSalesInvcDesc == "" && $scmSalesInvcAllwDues === 'YES') {
                    //$exitErrMsg .= "Please enter Description!<br/>";
                    $scmSalesInvcDesc = "Dues/Bills Quick Pay for " . getCstmrSpplrName1($scmSalesInvcCstmrID) . " on " . $scmSalesInvcDfltTrnsDte;
                }
                $exitErrMsg = "";
                if ($scmSalesInvcDocNum == "") {
                    $exitErrMsg .= "Please enter Document Number!<br/>";
                }
                if ($scmSalesInvcVchType == "") {
                    $exitErrMsg .= "Document Type cannot be empty!<br/>";
                }
                if ($scmSalesInvcDfltTrnsDte == "") {
                    $exitErrMsg .= "Document Date cannot be empty!<br/>";
                }
                if ($scmSalesInvcClssfctn !== "Standard" && $scmSalesInvcCstmrID <= 0) {
                    $exitErrMsg .= "Customer Name cannot be empty for the selected Invoice Classification!<br/>";
                }
                if ($scmSalesInvcClssfctn !== "Standard" && $scmSalesInvcCstmrSiteID <= 0) {
                    $exitErrMsg .= "Customer Site cannot be empty for the selected Invoice Classification!<br/>";
                }
                /* if ($scmSalesInvcCstmrID <= 0) {
                  $exitErrMsg .= "Customer Name cannot be empty!<br/>";
                  }
                  if ($scmSalesInvcCstmrSiteID <= 0) {
                  $exitErrMsg .= "Customer Site cannot be empty!<br/>";
                  } */
                if ($scmSalesInvcEvntDocTyp != "None" && ($scmSalesInvcEvntCtgry == "" || $scmSalesInvcEvntRgstrID <= 0)) {
                    $exitErrMsg .= "Linked Event Number and Category Cannot be empty\r\n if the Event Type is not set to None!<br/>";
                }
                if ($scmSalesInvcDfltBalsAcntID <= 0) {
                    $exitErrMsg .= "Please enter a Receivable Account!<br/>";
                }
                $oldPtyCashID = getGnrlRecID("scm.scm_sales_invc_hdr", "invc_number", "invc_hdr_id", $scmSalesInvcDocNum, $orgID);
                if ($oldPtyCashID > 0 && $oldPtyCashID != $sbmtdScmSalesInvcID) {
                    $exitErrMsg .= "New Document Number/Name is already in use in this Organization!<br/>";
                }
                if ($scmSalesInvcAllwDues === 'YES') {
                    if ($scmSalesInvcPyItmSetID <= 0) {
                        $exitErrMsg .= "Item Set cannot be empty for Dues/Bills-Enabled Invoices!";
                    }
                }
                if (1 == 1) {
                    $scmSalesInvcAplyAdvnc = ($scmSalesInvcAplyAdvnc === 'YES') ? '1' : '0';
                    $scmSalesInvcKeepExcss = ($scmSalesInvcKeepExcss === 'YES') ? '1' : '0';
                }
                //var_dump($_POST);
                $apprvlStatus = "Not Validated";
                $nxtApprvlActn = "Approve";
                $pymntTrms = $scmSalesInvcPayTerms;
                $srcDocHdrID = $srcSalesInvcDocID;
                $srcDocType = getGnrlRecNm("scm.scm_sales_invc_hdr", "invc_hdr_id", "invc_type", $srcSalesInvcDocID);
                $srcSalesInvcDocTyp = $srcDocType;
                $pymntMthdID = $scmSalesInvcPayMthdID;
                $glBtchID = -1;
                $chckInID = -1;
                $chckInType = "";
                $enblAutoChrg = true;
                $allwDues = ($scmSalesInvcAllwDues === 'YES') ? true : false;
                $cstmrInvcNum = $scmSalesInvcCstmrInvcNum;
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['sbmtdScmSalesInvcID'] = $sbmtdScmSalesInvcID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                if ($sbmtdScmSalesInvcID <= 0) {
                    createSalesDocHdr($orgID, $scmSalesInvcDocNum, $scmSalesInvcDesc, $scmSalesInvcVchType, $scmSalesInvcDfltTrnsDte,
                            $scmSalesInvcPayTerms, $scmSalesInvcCstmrID, $scmSalesInvcCstmrSiteID, $apprvlStatus, $nxtApprvlActn,
                            $srcSalesInvcDocID, $scmSalesInvcDfltBalsAcntID, $scmSalesInvcPayMthdID, $scmSalesInvcInvcCurID,
                            $scmSalesInvcExRate, $chckInID, $chckInType, $enblAutoChrg, $scmSalesInvcEvntRgstrID, $scmSalesInvcEvntCtgry,
                            $allwDues, $scmSalesInvcEvntDocTyp, $srcSalesInvcDocTyp, $scmSalesInvcBrnchID, $scmSalesInvcClssfctn,
                            $scmSalesInvcPyItmSetID, $scmSalesInvcPyAmntGvn, $scmSalesInvcPyChqNumber, $scmSalesInvcPySignCode,
                            $scmSalesInvcAplyAdvnc, $scmSalesInvcKeepExcss);
                    $sbmtdScmSalesInvcID = getGnrlRecID("scm.scm_sales_invc_hdr", "invc_number", "invc_hdr_id", $scmSalesInvcDocNum, $orgID);
                } else if ($sbmtdScmSalesInvcID > 0) {
                    updtSalesDocHdr($sbmtdScmSalesInvcID, $scmSalesInvcDocNum, $scmSalesInvcDesc, $scmSalesInvcVchType,
                            $scmSalesInvcDfltTrnsDte, $scmSalesInvcPayTerms, $scmSalesInvcCstmrID, $scmSalesInvcCstmrSiteID, $apprvlStatus,
                            $nxtApprvlActn, $srcSalesInvcDocID, $scmSalesInvcDfltBalsAcntID, $scmSalesInvcPayMthdID, $scmSalesInvcInvcCurID,
                            $scmSalesInvcExRate, $chckInID, $chckInType, $enblAutoChrg, $scmSalesInvcEvntRgstrID, $scmSalesInvcEvntCtgry,
                            $allwDues, $scmSalesInvcEvntDocTyp, $srcSalesInvcDocTyp, $scmSalesInvcBrnchID, $scmSalesInvcClssfctn,
                            $scmSalesInvcPyItmSetID, $scmSalesInvcPyAmntGvn, $scmSalesInvcPyChqNumber, $scmSalesInvcPySignCode,
                            $scmSalesInvcAplyAdvnc, $scmSalesInvcKeepExcss);
                }
                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                if (trim($slctdDetTransLines, "|~") != "") {
                    //Save Petty Cash Double Entry Lines
                    $variousRows = explode("|", trim($slctdDetTransLines, "|"));
                    //echo count($variousRows);
                    for ($y = 0; $y < count($variousRows); $y++) {
                        //var_dump($crntRow);
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 17) {
                            $ln_TrnsLnID = (float) (cleanInputData1($crntRow[0]));
                            $ln_ItmID = (float) cleanInputData1($crntRow[1]);
                            $ln_StoreID = (int) cleanInputData1($crntRow[2]);
                            $ln_CnsgnIDs = cleanInputData1($crntRow[3]);
                            if (trim($ln_CnsgnIDs) === "") {
                                $ln_CnsgnIDs = ",";
                            }
                            $ln_ItmAccnts = cleanInputData1($crntRow[4]);
                            $variousAccnts = explode(";", trim($ln_ItmAccnts, ";"));
                            $ln_AstAcntID = -1;
                            $ln_CogsID = -1;
                            $ln_SalesRevID = -1;
                            $ln_SalesRetID = -1;
                            $ln_PurcRetID = -1;
                            $ln_ExpnsID = -1;
                            if (count($variousAccnts) == 6) {
                                $ln_AstAcntID = (int) cleanInputData1($variousAccnts[0]);
                                $ln_CogsID = (int) cleanInputData1($variousAccnts[1]);
                                $ln_SalesRevID = (int) cleanInputData1($variousAccnts[2]);
                                $ln_SalesRetID = (int) cleanInputData1($variousAccnts[3]);
                                $ln_PurcRetID = (int) cleanInputData1($variousAccnts[4]);
                                $ln_ExpnsID = (int) cleanInputData1($variousAccnts[5]);
                            }
                            $ln_LnkdPrsnID = (float) cleanInputData1($crntRow[5]);
                            $ln_LineDesc = cleanInputData1($crntRow[6]);
                            $ln_QTY = (float) (cleanInputData1($crntRow[7]));
                            $ln_UnitPrice = (float) cleanInputData1($crntRow[8]);
                            $ln_TaxID = (int) cleanInputData1($crntRow[9]);
                            $ln_DscntID = (int) cleanInputData1($crntRow[10]);
                            $ln_ChrgID = (int) (cleanInputData1($crntRow[11]));
                            $ln_SrcDocLnID = (int) (cleanInputData1($crntRow[12]));
                            $ln_ExtraDesc = cleanInputData1($crntRow[13]);
                            $rented_itm_qty = (float) cleanInputData1($crntRow[14]);
                            $ln_OthrMdlID = (int) (cleanInputData1($crntRow[15]));
                            $ln_OthrMdlTyp = (cleanInputData1($crntRow[16]));
                            if ($rented_itm_qty == 0) {
                                $rented_itm_qty = 1;
                            }
                            $ln_ReturnRsn = "";
                            $ln_OrgnlPrice = 0;
                            $ln_IsDlvrd = false;
                            if ($scmSalesInvcVchType == "Sales Return") {
                                $ln_ReturnRsn = $ln_ExtraDesc;
                            }
                            $errMsg = "";
                            if ($ln_LineDesc === "" || $ln_ItmID <= 0 || $ln_QTY <= 0) {
                                $errMsg = "Row " . ($y + 1) . ":- Item Description and Quantity are all required Fields!<br/>";
                            }
                            if ($errMsg === "") {
                                //Create Sales Doc Lines
                                if ($ln_LineDesc != "" && $ln_ItmID > 0 && $ln_QTY > 0) {
                                    if ($ln_TrnsLnID <= 0) {
                                        $ln_TrnsLnID = getNewSalesInvcLnID();
                                        $afftctd += createSalesDocLn($ln_TrnsLnID, $sbmtdScmSalesInvcID, $ln_ItmID, $ln_QTY, $ln_UnitPrice,
                                                $ln_StoreID, $scmSalesInvcInvcCurID, $ln_SrcDocLnID, $ln_TaxID, $ln_DscntID, $ln_ChrgID,
                                                $ln_ReturnRsn, $ln_CnsgnIDs, $ln_OrgnlPrice, $ln_IsDlvrd, $ln_LnkdPrsnID, $ln_LineDesc,
                                                $ln_AstAcntID, $ln_CogsID, $ln_SalesRevID, $ln_SalesRetID, $ln_PurcRetID, $ln_ExpnsID);
                                    } else {
                                        $afftctd += updateSalesDocLn($ln_TrnsLnID, $ln_ItmID, $ln_QTY, $ln_UnitPrice, $ln_StoreID,
                                                $scmSalesInvcInvcCurID, $ln_SrcDocLnID, $ln_TaxID, $ln_DscntID, $ln_ChrgID, $ln_ReturnRsn,
                                                $ln_CnsgnIDs, $ln_OrgnlPrice, $ln_IsDlvrd, $ln_LnkdPrsnID, $ln_LineDesc, $ln_AstAcntID,
                                                $ln_CogsID, $ln_SalesRevID, $ln_SalesRetID, $ln_PurcRetID, $ln_ExpnsID);
                                    }
                                }
                            } else {
                                $exitErrMsg .= $errMsg;
                            }
                        }
                    }
                }
                //Final Approval
                if ($shdSbmt !== 2) {
                    $errMsg1 = reCalcSalesInvcSmmrys($sbmtdScmSalesInvcID, $scmSalesInvcVchType, $scmSalesInvcCstmrID,
                            $scmSalesInvcInvcCurID, $apprvlStatus);
                    if (strpos($errMsg1, "ERROR") !== FALSE) {
                        $exitErrMsg .= "<br/>" . $errMsg1;
                    }
                }
                $errMsg = "";
                if ($exitErrMsg != "") {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Document Successfully Saved!"
                            . "<br/>" . $afftctd . " Transaction(s) Saved Successfully!"
                            . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                } else {
                    $exitErrMsg = "";
                    if ($shdSbmt == 2) {
                        $exitErrMsg = approve_sales_prchsdoc($sbmtdScmSalesInvcID, "Sales");
                        if (strpos($exitErrMsg, "SUCCESS") !== FALSE) {
                            $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                        } else {
                            $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                        }
                        /* updtRcvblsDocGLBatch($sbmtdAccbRcvblsInvcID, $accbRcvblsInvcGLBatchID);
                          updtRcvblsDocApprvl($sbmtdAccbRcvblsInvcID, "Approved", "Cancel"); */
                    } else if ($shdSbmt == 7) {
                        $exitErrMsg = load_dues_attchd_vals($sbmtdScmSalesInvcID, $selectedStoreID);
                        if (strpos($exitErrMsg, "SUCCESS") !== FALSE) {
                            $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                        } else {
                            $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                        }
                        /* updtRcvblsDocGLBatch($sbmtdAccbRcvblsInvcID, $accbRcvblsInvcGLBatchID);
                          updtRcvblsDocApprvl($sbmtdAccbRcvblsInvcID, "Approved", "Cancel"); */
                    } else {
                        $exitErrMsg .= "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Document Successfully Saved!"
                                . "<br/>" . $afftctd . " Document Transaction(s) Saved Successfully!";
                    }
                }
                $arr_content['percent'] = 100;
                $arr_content['sbmtdScmSalesInvcID'] = $sbmtdScmSalesInvcID;
                $arr_content['message'] = $exitErrMsg;
                echo json_encode($arr_content);
                exit();
            } else if ($actyp == 20) {
                //Upload Attachment
                header("content-type:application/json");
                $attchmentID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
                $sbmtdScmSalesInvcID = isset($_POST['sbmtdScmSalesInvcID']) ? cleanInputData($_POST['sbmtdScmSalesInvcID']) : -1;

                if (!($canEdt || $canAdd)) {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    echo json_encode($arr_content);
                    exit();
                }

                $docCtrgrName = isset($_POST['docCtrgrName']) ? cleanInputData($_POST['docCtrgrName']) : "";
                $nwImgLoc = "";
                $errMsg = "";
                $pkID = $sbmtdScmSalesInvcID;
                if ($attchmentID > 0) {
                    uploadDaSalesInvcDoc($attchmentID, $nwImgLoc, $errMsg);
                } else {
                    $attchmentID = getNewSalesInvcDocID();
                    createSalesInvcDoc($attchmentID, $pkID, $docCtrgrName, "");
                    uploadDaSalesInvcDoc($attchmentID, $nwImgLoc, $errMsg);
                }
                $arr_content['attchID'] = $attchmentID;
                if (strpos($errMsg, "Document Stored Successfully!<br/>") === FALSE) {
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $errMsg;
                } else {
                    $doc_src = $ftp_base_db_fldr . "/Sales/" . $nwImgLoc;
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
            }
        } else if ($qstr == "VOID") {
            if ($actyp == 1) {
                //Reverse Sales Invoice Voucher
                $errMsg = "";
                $scmSalesInvcDesc = isset($_POST['scmSalesInvcDesc']) ? cleanInputData($_POST['scmSalesInvcDesc']) : '';
                $shdSbmt = isset($_POST['shdSbmt']) ? cleanInputData($_POST['shdSbmt']) : 0;
                $sbmtdScmSalesInvcID = isset($_POST['sbmtdScmSalesInvcID']) ? cleanInputData($_POST['sbmtdScmSalesInvcID']) : -1;
                if (!$cancelDocs) {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    exit();
                }
                $rqStatus = "Not Validated"; //approval_status
                $rqStatusNext = "Approve";
                $p_dochdrtype = "";
                $scmInvcDfltTrnsDte = "";
                $scmInvcDocNum = "";
                if ($sbmtdScmSalesInvcID > 0) {
                    $result = get_One_SalesInvcDocHdr($sbmtdScmSalesInvcID);
                    if ($row = loc_db_fetch_array($result)) {
                        $scmInvcDfltTrnsDte = $row[1] . " 12:00:00";
                        $scmInvcDocNum = $row[4];
                        $p_dochdrtype = $row[5];
                        $rqStatus = $row[12];
                        $rqStatusNext = $row[13];
                    }
                }
                if ($rqStatus == "Not Validated" && $sbmtdScmSalesInvcID > 0) {
                    echo deleteSalesDocHdrNDet($sbmtdScmSalesInvcID, $scmInvcDocNum);
                    exit();
                } else {
                    $exitErrMsg = cancelSalesPrchsDoc($sbmtdScmSalesInvcID, "Sales", $orgID, $usrID);
                    $arr_content['sbmtdScmSalesInvcID'] = $sbmtdScmSalesInvcID;
                    $arr_content['percent'] = 100;
                    if (strpos($exitErrMsg, "SUCCESS") !== FALSE) {
                        execUpdtInsSQL("UPDATE scm.scm_sales_invc_hdr SET comments_desc='" . loc_db_escape_string($scmSalesInvcDesc) . "' WHERE (invc_hdr_id = " . $sbmtdScmSalesInvcID . ")");
                        $arr_content['sbmtMsg'] = "<span style=\"color:green;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    } else {
                        $arr_content['sbmtMsg'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    }
                    echo json_encode($arr_content);
                    exit();
                }
            }
        } else {
            $qStrtDte = substr($gnrlTrnsDteDMYHMS, 0, 11) . " 00:00:00";
            $qEndDte = substr($gnrlTrnsDteDMYHMS, 0, 11) . " 23:59:59";
            if (isset($_POST['qStrtDte'])) {
                $qStrtDte = cleanInputData($_POST['qStrtDte']);
                if (strlen($qStrtDte) == 11) {
                    $qStrtDte = substr($qStrtDte, 0, 11) . " 00:00:00";
                } else {
                    $qStrtDte = "01-Jan-1900 00:00:00";
                }
            }
            if (isset($_POST['qEndDte'])) {
                $qEndDte = cleanInputData($_POST['qEndDte']);
                if (strlen($qEndDte) == 11) {
                    $qEndDte = substr($qEndDte, 0, 11) . " 23:59:59";
                } else {
                    $qEndDte = "31-Dec-4000 23:59:59";
                }
            }
            if ($vwtyp == 0) {
                //echo "hello";
                $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Sales Invoice</span>
				</li>";
                $menuItems = array("Quick Sale", "All Sales Invoice");
                $menuImages = array("sale.jpg", "sale.jpg");
                $cntent .= " </ul></div>
                <div style=\"font-family: Tahoma, Arial, sans-serif;font-size: 1.3em;padding:10px 15px 15px 20px;border:1px solid #ccc;\">                    
                    <div style=\"padding:5px 30px 5px 10px;margin-bottom:2px;\">
                    <span style=\"font-family: georgia, times;font-size: 12px;font-style:italic;
                    font-weight:normal;\">From here you can perform a quick sale or create full blown invoice documents! The module has the ff areas:</span>
                    </div>
                <p>";
                $grpcntr = 0;
                for ($i = 0; $i < count($menuItems); $i++) {
                    $No = $i + 1;
                    if ($i == 0) {
                        
                    }
                    if ($grpcntr == 0) {
                        $cntent .= "<div class=\"row\">";
                    }
                    $cntent .= "<div class=\"col-md-3 colmd3special2\">
                                    <button type=\"button\" class=\"btn btn-default btn-lg btn-block modulesButton\" onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=$No');\">
                                        <img src=\"cmn_images/$menuImages[$i]\" style=\"margin:5px; padding-right: 1em; height:58px; width:auto; position: relative; vertical-align: middle;float:left;\">
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
                $cntent .= "
      </p>
    </div>";
                echo $cntent;
            } else if ($vwtyp == 1) {
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Sales Invoice</span>
				</li>" .
                "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=1');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Quick Sale</span>
				</li> </ul></div>";
                $error = "";
                $searchAll = true;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
                $qShwUnpstdOnly = false;
                if (isset($_POST['qShwUnpstdOnly'])) {
                    $qShwUnpstdOnly = cleanInputData($_POST['qShwUnpstdOnly']) === "true" ? true : false;
                }
                $qShwUnpaidOnly = false;
                if (isset($_POST['qShwUnpaidOnly'])) {
                    $qShwUnpaidOnly = cleanInputData($_POST['qShwUnpaidOnly']) === "true" ? true : false;
                }
                $qShwSelfOnly = true;
                if (isset($_POST['qShwSelfOnly'])) {
                    $qShwSelfOnly = cleanInputData($_POST['qShwSelfOnly']) === "true" ? true : false;
                }
                $qShwMyBranch = true;
                if (isset($_POST['qShwMyBranch'])) {
                    $qShwMyBranch = cleanInputData($_POST['qShwMyBranch']) === "true" ? true : false;
                }
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                $total = get_Total_SalesDoc($srchFor, $srchIn, $orgID, $qShwUnpstdOnly, $qShwUnpaidOnly, $qShwSelfOnly, $qShwMyBranch, $qStrtDte, $qEndDte);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_Basic_SalesDoc($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qShwUnpstdOnly, $qShwUnpaidOnly, $qShwSelfOnly,
                        $qShwMyBranch, $qStrtDte, $qEndDte);
                $cntr = 0;
                $colClassType1 = "col-md-2";
                $colClassType2 = "col-md-5";
                $colClassType3 = "col-md-5";
                ?> 
                <form id='scmSalesInvcForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class=""><!--<legend class="basic_person_lg1" style="color: #003245">POS TRANSACTIONS</legend>-->
                        <div class="row" style="margin-bottom:0px;">
                            <?php
                            $colClassType1 = "col-md-2";
                            $colClassType2 = "col-md-5";
                            $colClassType3 = "col-md-6";
                            ?>
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="scmSalesInvcSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncScmSalesInvc(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <input id="scmSalesInvcPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getScmSalesInvc('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getScmSalesInvc('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="scmSalesInvcSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "", "",
                                            "", "", "", "", "", "");
                                        $srchInsArrys = array("Document Number", "Classification",
                                            "Document Description",
                                            "Customer Name", "Source Doc Number",
                                            "Approval Status", "Created By",
                                            "Currency", "Branch", "All");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="scmSalesInvcDsplySze" style="min-width:70px !important;">                            
                                        <?php
                                        $valslctdArry = array("");
                                        $dsplySzeArry = array(1);
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
                            <div class="col-lg-4" style="padding:0px 1px 0px 1px !important;">
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text" id="scmSalesInvcStrtDate" name="scmSalesInvcStrtDate" value="<?php
                                        echo substr($qStrtDte, 0, 11);
                                        ?>" placeholder="Start Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div></div>
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text"  id="scmSalesInvcEndDate" name="scmSalesInvcEndDate" value="<?php
                                        echo substr($qEndDte, 0, 11);
                                        ?>" placeholder="End Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>                            
                            </div>
                            <div class="<?php echo $colClassType1; ?>">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a href="javascript:getScmSalesInvc('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getScmSalesInvc('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>   
                        <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important">   
                            <div class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                                <?php if ($canAdd === true) { ?>   
                                    <div class="col-md-5" style="padding:0px 0px 0px 0px !important;">                      
                                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getScmSalesInvc('', '#allmodules', 'grp=12&typ=1&pg=1&vtyp=1&scmSalesInvcVchType=Sales Invoice&scmSalesAction=ISNEW');" data-toggle="tooltip" data-placement="bottom" title="Add New Sales Invoice">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New Sales Invoice
                                        </button>
                                    </div>  
                                <?php }
                                ?>
                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;">
                                    <div class = "form-check" style = "font-size: 12px !important;">
                                        <label class = "form-check-label" title="Show Approved but Unpaid">
                                            <?php
                                            $shwUnpaidOnlyChkd = "";
                                            if ($qShwUnpaidOnly == true) {
                                                $shwUnpaidOnlyChkd = "checked=\"true\"";
                                            }
                                            ?>
                                            <input type="checkbox" class="form-check-input" onclick="getScmSalesInvc('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="scmSalesInvcShwUnpaidOnly" name="scmSalesInvcShwUnpaidOnly"  <?php echo $shwUnpaidOnlyChkd; ?>>
                                            Show Unpaid
                                        </label>
                                    </div> 
                                </div>
                                <div class = "col-md-2" style = "padding:5px 1px 0px 1px !important;">
                                    <div class = "form-check" style = "font-size: 12px !important;">
                                        <label class = "form-check-label">
                                            <?php
                                            $shwUnpstdOnlyChkd = "";
                                            if ($qShwUnpstdOnly == true) {
                                                $shwUnpstdOnlyChkd = "checked=\"true\"";
                                            }
                                            ?>
                                            <input type="checkbox" class="form-check-input" onclick="getScmSalesInvc('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="scmSalesInvcShwUnpstdOnly" name="scmSalesInvcShwUnpstdOnly"  <?php echo $shwUnpstdOnlyChkd; ?>>
                                            Show Unposted
                                        </label>
                                    </div>                            
                                </div>
                                <?php if ($vwOnlySelf == false) { ?>
                                    <div class = "col-md-2" style = "padding:5px 1px 0px 1px !important;">
                                        <div class = "form-check" style = "font-size: 12px !important;">
                                            <label class = "form-check-label" title="Only Self-Created">
                                                <?php
                                                $shwSelfOnlyChkd = "";
                                                if ($qShwSelfOnly == true) {
                                                    $shwSelfOnlyChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <input type="checkbox" class="form-check-input" onclick="getScmSalesInvc('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="scmSalesInvcShwSelfOnly" name="scmSalesInvcShwSelfOnly"  <?php echo $shwSelfOnlyChkd; ?>>
                                                Self-Created
                                            </label>
                                        </div>                            
                                    </div>
                                <?php } ?>
                                <?php if ($vwOnlyBranch == false) { ?>
                                    <div class = "col-md-1" style = "padding:5px 1px 0px 1px !important;">
                                        <div class = "form-check" style = "font-size: 12px !important;">
                                            <label class = "form-check-label" title="Show Only My Branch">
                                                <?php
                                                $shwMyBranchOnlyChkd = "";
                                                if ($qShwMyBranch == true) {
                                                    $shwMyBranchOnlyChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <input type="checkbox" class="form-check-input" onclick="getScmSalesInvc('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="scmSalesInvcShwMyBranchOnly" name="scmSalesInvcShwMyBranchOnly"  <?php echo $shwMyBranchOnlyChkd; ?>>
                                                My Branch
                                            </label>
                                        </div>                            
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row"> 
                            <div  class="col-md-12">
                                <?php
                                $sbmtdScmRtrnSrcDocID = isset($_POST['sbmtdScmRtrnSrcDocID']) ? cleanInputData($_POST['sbmtdScmRtrnSrcDocID']) : -1;
                                $sbmtdScmSalesInvcID = isset($_POST['sbmtdScmSalesInvcID']) ? cleanInputData($_POST['sbmtdScmSalesInvcID']) : -1;
                                $scmSalesInvcVchType = isset($_POST['scmSalesInvcVchType']) ? cleanInputData($_POST['scmSalesInvcVchType']) : "Sales Invoice";
                                $scmSalesAction = isset($_POST['scmSalesAction']) ? cleanInputData($_POST['scmSalesAction']) : "NOTNEW";
                                $musAllwDues = isset($_POST['musAllwDues']) ? cleanInputData($_POST['musAllwDues']) : "NO";
                                $srcCaller = isset($_POST['srcCaller']) ? cleanInputData($_POST['srcCaller']) : "SALES";
                                $qckPayPrsns_PrsnID = isset($_POST['qckPayPrsns_PrsnID']) ? (float) $_POST['qckPayPrsns_PrsnID'] : -1;
                                $qckPayItmSetID = isset($_POST['qckPayItmSetID']) ? (float) $_POST['qckPayItmSetID'] : -1;

                                IF ($scmSalesAction == "ISNEW") {
                                    if (!$canAdd || ($sbmtdScmSalesInvcID > 0 && !$canEdt)) {
                                        restricted();
                                        exit();
                                    }
                                    if ($scmSalesInvcVchType == "Pro-Forma Invoice" && (!$addRecsPFI || ($sbmtdScmSalesInvcID > 0 && !$editRecsPFI))) {
                                        restricted();
                                        exit();
                                    }
                                    if ($scmSalesInvcVchType == "Sales Order" && (!$addRecsSO || ($sbmtdScmSalesInvcID > 0 && !$editRecsSO))) {
                                        restricted();
                                        exit();
                                    }
                                    if ($scmSalesInvcVchType == "Sales Invoice" && (!$addRecsSI || ($sbmtdScmSalesInvcID > 0 && !$editRecsSI))) {
                                        restricted();
                                        exit();
                                    }
                                    if ($scmSalesInvcVchType == "Internal Item Request" && (!$addRecsIIR || ($sbmtdScmSalesInvcID > 0 && !$editRecsIIR))) {
                                        restricted();
                                        exit();
                                    }
                                    if ($scmSalesInvcVchType == "Item Issue-Unbilled" && (!$addRecsIIU || ($sbmtdScmSalesInvcID > 0 && !$editRecsIIU))) {
                                        restricted();
                                        exit();
                                    }
                                    if ($scmSalesInvcVchType == "Sales Return" && (!$addRecsSR || ($sbmtdScmSalesInvcID > 0 && !$editRecsSR))) {
                                        restricted();
                                        exit();
                                    }
                                }
                                $orgnlScmSalesInvcID = $sbmtdScmSalesInvcID;
                                $scmSalesInvcDfltTrnsDte = $gnrlTrnsDteDMYHMS;
                                $scmSalesInvcCreator = $uName;
                                $scmSalesInvcCreatorID = $usrID;
                                $gnrtdTrnsNo = "";
                                $scmSalesInvcDesc = "";

                                $srcSalesInvcDocID = -1;
                                $srcSalesInvcDocTyp = "";
                                if ($scmSalesInvcVchType == "Pro-Forma Invoice") {
                                    $srcSalesInvcDocTyp = "";
                                } elseif ($scmSalesInvcVchType == "Sales Order") {
                                    $srcSalesInvcDocTyp = "Pro-Forma Invoice";
                                } elseif ($scmSalesInvcVchType == "Sales Invoice") {
                                    $srcSalesInvcDocTyp = "Sales Order";
                                } elseif ($scmSalesInvcVchType == "Internal Item Request") {
                                    $srcSalesInvcDocTyp = "";
                                } elseif ($scmSalesInvcVchType == "Item Issue-Unbilled") {
                                    $srcSalesInvcDocTyp = "Internal Item Request";
                                } elseif ($scmSalesInvcVchType == "Sales Return") {
                                    $srcSalesInvcDocTyp = "Sales Invoice";
                                }
                                $scmSalesInvcClssfctn = "Standard";
                                $scmSalesInvcDocTmpltID = -1;
                                $srcSalesInvcDocNum = "";
                                $scmSalesInvcBrnchID = $brnchLocID;
                                $scmSalesInvcBrnchNm = $brnchLoc;

                                $scmSalesInvcCstmr = "";
                                $scmSalesInvcCstmrID = -1;
                                $scmSalesInvcCstmrSite = "";
                                $scmSalesInvcCstmrSiteID = -1;
                                if ($qckPayPrsns_PrsnID > 0) {
                                    $scmSalesInvcCstmrID = getLnkdPrsnCstmrSpplrID($qckPayPrsns_PrsnID);
                                    $scmSalesInvcCstmr = getPrsnFullNm($qckPayPrsns_PrsnID) . " (" . getPersonLocID($qckPayPrsns_PrsnID) . ")";
                                    $gender = getGnrlRecNm("prs.prsn_names_nos", "person_id", "gender", $qckPayPrsns_PrsnID);
                                    $dob = getGnrlRecNm("prs.prsn_names_nos", "person_id", "date_of_birth", $qckPayPrsns_PrsnID);
                                    $cstmrLbltyAcntID = get_DfltPyblAcnt($orgID);
                                    $cstmrRcvblsAcntID = get_DfltRcvblAcnt($orgID);
                                    if ($scmSalesInvcCstmrID <= 0) {
                                        createCstmrLnkdPrsn($scmSalesInvcCstmr, $scmSalesInvcCstmr, "Individual", "Customer", $orgID,
                                                $cstmrLbltyAcntID, $cstmrRcvblsAcntID, $qckPayPrsns_PrsnID, $gender, $dob, "1", "",
                                                "", "", "", "", "", "", "", 0, "", "");
                                        $scmSalesInvcCstmrID = getLnkdPrsnCstmrSpplrID($qckPayPrsns_PrsnID);
                                        if ($scmSalesInvcCstmrID > 0) {
                                            $cstmrSiteCnt = (int) getGnrlRecNm("scm.scm_cstmr_suplr_sites", "cust_supplier_id", "count(cust_sup_site_id)", $scmSalesInvcCstmrID);
                                            if ($cstmrSiteCnt <= 0) {
                                                createCstmrSiteLnkdPrsn($scmSalesInvcCstmrID, "To be Specified", "", "", "HEAD OFFICE-" . $scmSalesInvcCstmr, "HEAD OFFICE-" . $scmSalesInvcCstmr, "", "", "", -1, -1, "",
                                                        "", "", "", "", "", "", "", "", "1", "", $fnccurid);
                                            }
                                        }
                                    }
                                    $scmSalesInvcCstmrSiteID = get_DfltCstmrSpplrSiteID($scmSalesInvcCstmrID);
                                    $scmSalesInvcCstmrSite = getCstmrSiteNm($scmSalesInvcCstmrSiteID, $scmSalesInvcCstmrID);
                                }
                                $scmSalesInvcCstmrClsfctn = "Customer";
                                $rqStatus = "Not Validated";
                                $rqStatusNext = "Approve";
                                $rqstatusColor = "red";

                                $scmSalesInvcTtlAmnt = 0;
                                $scmSalesInvcAppldAmnt = 0;
                                $scmSalesInvcPayTerms = "";
                                $scmSalesInvcPayMthd = "";
                                $scmSalesInvcPayMthdID = -1;
                                $scmSalesInvcPaidAmnt = 0;
                                $scmSalesInvcGLBatch = "";
                                $scmSalesInvcGLBatchID = -1;
                                $scmSalesInvcCstmrInvcNum = "";
                                $scmSalesInvcDocTmplt = "";
                                $scmSalesInvcEvntRgstr = "";
                                $scmSalesInvcEvntRgstrID = -1;
                                $scmSalesInvcEvntCtgry = "";
                                $scmSalesInvcEvntDocTyp = "";
                                $scmSalesInvcDfltBalsAcnt = "";
                                $scmSalesInvcInvcCurID = $fnccurid;
                                $scmSalesInvcInvcCur = $fnccurnm;
                                $scmSalesInvcIsPstd = "0";
                                $scmSalesInvcAllwDues = "0";
                                if ($musAllwDues == "YES") {
                                    $scmSalesInvcAllwDues = "1";
                                }
                                $duesPayCls = "hideNotice";
                                if ($scmSalesInvcAllwDues === '1') {
                                    $duesPayCls = "";
                                }
                                $scmSalesInvcAutoBals = "1";
                                $scmSalesInvcExRate = 1;
                                $otherModuleDocId = -1;
                                $otherModuleDocTyp = "";
                                $otherModuleDocNum = "";
                                $accbRcvblAmtApldElswhr = 0;
                                $accbRcvblDebtGlBatchID = -1;
                                $sbmtdScmRcvblsInvcID = -1;
                                $accbRcvblDebtGlBatchNm = "";
                                $scmSalesInvcRcvblDocID = -1;
                                $scmSalesInvcRcvblDoc = "";
                                $scmSalesInvcRcvblDocType = "";
                                $mkReadOnly = "";
                                $mkRmrkReadOnly = "";
                                $scmSalesInvcPyItmSetID = -1;
                                $scmSalesInvcPyItmSetNm = "";
                                if ($qckPayItmSetID > 0) {
                                    $scmSalesInvcPyItmSetID = $qckPayItmSetID;
                                    $scmSalesInvcPyItmSetNm = getGnrlRecNm("pay.pay_itm_sets_hdr", "hdr_id", "itm_set_name", $qckPayItmSetID);
                                }
                                $scmSalesInvcPyAmntGvn = 0;
                                $scmSalesInvcPyChqNumber = "";
                                $scmSalesInvcPySignCode = "";
                                $scmSalesInvcAplyAdvnc = "1";
                                $scmSalesInvcKeepExcss = "1";
                                $scmSalesInvcDfltBalsAcntID = get_DfltCstmrRcvblsCashAcnt($scmSalesInvcCstmrID, $orgID);
                                if ($scmSalesAction != "ISNEW" && $row = loc_db_fetch_array($result)) {
                                    $sbmtdScmSalesInvcID = $row[0];
                                    $srcSalesInvcDocNum = $row[1];
                                    $scmSalesInvcVchType = $row[2];
                                    if ($sbmtdScmSalesInvcID > 0) {
                                        $result = get_One_SalesInvcDocHdr($sbmtdScmSalesInvcID);
                                        if ($row = loc_db_fetch_array($result)) {
                                            $scmSalesInvcDfltTrnsDte = $row[1];
                                            $scmSalesInvcCreator = $row[3];
                                            $scmSalesInvcCreatorID = $row[2];
                                            $gnrtdTrnsNo = $row[4];
                                            $scmSalesInvcVchType = $row[5];
                                            $scmSalesInvcDesc = $row[6];
                                            $srcSalesInvcDocID = $row[7];
                                            $scmSalesInvcCstmr = $row[9];
                                            $scmSalesInvcCstmrID = $row[8];
                                            $scmSalesInvcCstmrSite = $row[11];
                                            $scmSalesInvcCstmrSiteID = $row[10];
                                            $rqStatus = $row[12];
                                            $rqStatusNext = $row[13];
                                            $rqstatusColor = "red";

                                            $scmSalesInvcPayTerms = $row[15];
                                            $srcSalesInvcDocTyp = $row[16];
                                            $scmSalesInvcPayMthd = $row[18];
                                            $scmSalesInvcPayMthdID = $row[17];

                                            $scmSalesInvcTtlAmnt = (float) $row[14];
                                            $scmSalesInvcAppldAmnt = (float) $row[34];
                                            $scmSalesInvcPaidAmnt = $row[19];
                                            if (strpos($scmSalesInvcVchType, "Advance Payment") === FALSE) {
                                                $scmSalesInvcAppldAmnt = $scmSalesInvcPaidAmnt;
                                            }
                                            $scmSalesInvcGLBatch = $row[21];
                                            $scmSalesInvcGLBatchID = $row[20];
                                            $scmSalesInvcCstmrInvcNum = $row[22];
                                            $scmSalesInvcDocTmplt = $row[23];
                                            $scmSalesInvcInvcCur = $row[25];
                                            $scmSalesInvcInvcCurID = $row[24];
                                            $scmSalesInvcEvntRgstr = "";
                                            $scmSalesInvcEvntRgstrID = $row[26];
                                            $scmSalesInvcEvntCtgry = $row[27];
                                            $scmSalesInvcEvntDocTyp = $row[28];
                                            $scmSalesInvcDfltBalsAcntID = $row[29];
                                            $scmSalesInvcDfltBalsAcnt = $row[30];
                                            $scmSalesInvcIsPstd = $row[31];
                                            $scmSalesInvcAllwDues = $row[38];
                                            $duesPayCls = "hideNotice";
                                            if ($scmSalesInvcAllwDues === '1') {
                                                $duesPayCls = "";
                                            }
                                            $scmSalesInvcExRate = (float) $row[40];
                                            if ($scmSalesInvcExRate == 0) {
                                                $scmSalesInvcExRate = 1;
                                            }
                                            $otherModuleDocId = $row[32];
                                            $otherModuleDocTyp = $row[33];
                                            $otherModuleDocNum = $row[41];
                                            $sbmtdScmRcvblsInvcID = (float) $row[42];
                                            $accbRcvblAmtApldElswhr = $row[34];
                                            $accbRcvblDebtGlBatchID = $row[35];
                                            $accbRcvblDebtGlBatchNm = $row[36];
                                            $scmSalesInvcRcvblDocID = $sbmtdScmRcvblsInvcID;
                                            $scmSalesInvcRcvblDoc = $row[43];
                                            $scmSalesInvcRcvblDocType = $row[44];
                                            $scmSalesInvcBrnchID = (int) $row[45];
                                            $scmSalesInvcBrnchNm = $row[46];
                                            $scmSalesInvcClssfctn = $row[47];
                                            $scmSalesInvcPyItmSetID = (int) $row[48];
                                            $scmSalesInvcPyItmSetNm = $row[49];
                                            $scmSalesInvcPyAmntGvn = (float) $row[50];
                                            $scmSalesInvcPyChqNumber = $row[51];
                                            $scmSalesInvcPySignCode = $row[52];
                                            $scmSalesInvcAplyAdvnc = $row[53];
                                            $scmSalesInvcKeepExcss = $row[54];
                                            if ($rqStatus == "Approved") {
                                                $rqstatusColor = "green";
                                            } else {
                                                $rqstatusColor = "red";
                                            }
                                            if ($rqStatus == "Not Validated") {
                                                $mkReadOnly = "";
                                                $mkRmrkReadOnly = "";
                                            } else {
                                                $canEdt = FALSE;
                                                $mkReadOnly = "readonly=\"true\"";
                                                if ($rqStatus != "Approved") {
                                                    $mkRmrkReadOnly = "readonly=\"true\"";
                                                }
                                            }
                                            $errMsg1 = reCalcSalesInvcSmmrys($sbmtdScmSalesInvcID, $scmSalesInvcVchType,
                                                    $scmSalesInvcCstmrID, $scmSalesInvcInvcCurID, $rqStatus);
                                            $rslt = get_One_SalesInvcAmounts($sbmtdScmSalesInvcID);
                                            if ($rw = loc_db_fetch_array($rslt)) {
                                                $scmSalesInvcTtlAmnt = (float) $rw[0];
                                                $scmSalesInvcPaidAmnt = $rw[1];
                                                if (strpos($scmSalesInvcVchType, "Advance Payment") === FALSE) {
                                                    $scmSalesInvcAppldAmnt = $scmSalesInvcPaidAmnt;
                                                }
                                            }
                                        }
                                    }
                                } else if ($scmSalesAction == "ISNEW") {
                                    $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
                                    if ($usrTrnsCode == "") {
                                        $usrTrnsCode = "XX";
                                    }
                                    $dte = date('ymd');
                                    $docTypes = array("Pro-Forma Invoice", "Sales Order", "Sales Invoice",
                                        "Internal Item Request", "Item Issue-Unbilled", "Sales Return");
                                    $docTypPrfxs = array("PFI", "SO", "SI", "IIR", "IIU", "SR");

                                    $docTypPrfx = $docTypPrfxs[findArryIdx($docTypes, $scmSalesInvcVchType)];
                                    $gnrtdTrnsNo1 = $docTypPrfx . "-" . $usrTrnsCode . "-" . $dte . "-";
                                    $gnrtdTrnsNo = $gnrtdTrnsNo1 . str_pad(((getRecCount_LstNum("scm.scm_sales_invc_hdr", "invc_number",
                                                            "invc_hdr_id", $gnrtdTrnsNo1 . "%") + 1) . ""), 3, '0', STR_PAD_LEFT);
                                    $scmSalesInvcDfltBalsAcnt = getAccntNum($scmSalesInvcDfltBalsAcntID) . "." . getAccntName($scmSalesInvcDfltBalsAcntID);
                                    $scmSalesInvcInvcCurID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id",
                                                    $scmSalesInvcDfltBalsAcntID);
                                    if ($scmSalesInvcInvcCurID > 0) {
                                        $scmSalesInvcInvcCur = getPssblValNm($scmSalesInvcInvcCurID);
                                    } else {
                                        $scmSalesInvcInvcCurID = $fnccurid;
                                        $scmSalesInvcInvcCur = $fnccurnm;
                                    }
                                    if ($scmSalesInvcDesc == "" && $scmSalesInvcAllwDues === '1') {
                                        //$exitErrMsg .= "Please enter Description!<br/>";
                                        $scmSalesInvcDesc = "Dues/Bills Quick Pay for " . getCstmrSpplrName1($scmSalesInvcCstmrID) . " on " . $scmSalesInvcDfltTrnsDte;
                                    }
                                    createSalesDocHdr($orgID, $gnrtdTrnsNo, $scmSalesInvcDesc, $scmSalesInvcVchType,
                                            $scmSalesInvcDfltTrnsDte, $scmSalesInvcPayTerms, $scmSalesInvcCstmrID, $scmSalesInvcCstmrSiteID,
                                            $rqStatus, $rqStatusNext, $srcSalesInvcDocID, $scmSalesInvcDfltBalsAcntID,
                                            $scmSalesInvcPayMthdID, $scmSalesInvcInvcCurID, $scmSalesInvcExRate, $otherModuleDocId,
                                            $otherModuleDocTyp, $scmSalesInvcAutoBals, $scmSalesInvcEvntRgstrID, $scmSalesInvcEvntCtgry,
                                            $scmSalesInvcAllwDues, $scmSalesInvcEvntDocTyp, $srcSalesInvcDocTyp, $scmSalesInvcBrnchID, $scmSalesInvcClssfctn,
                                            $scmSalesInvcPyItmSetID, $scmSalesInvcPyAmntGvn, $scmSalesInvcPyChqNumber,
                                            $scmSalesInvcPySignCode, $scmSalesInvcAplyAdvnc, $scmSalesInvcKeepExcss);

                                    $sbmtdScmSalesInvcID = getGnrlRecID("scm.scm_sales_invc_hdr", "invc_number", "invc_hdr_id",
                                            $gnrtdTrnsNo, $orgID);

                                    if ($sbmtdScmSalesInvcID > 0 && $qckPayPrsns_PrsnID > 0 && $scmSalesInvcCstmrID > 0 && $scmSalesInvcAllwDues === '1') {
                                        load_dues_attchd_vals($sbmtdScmSalesInvcID, $selectedStoreID);
                                    }
                                }
                                $scmSalesInvcOustndngAmnt = $scmSalesInvcTtlAmnt - $scmSalesInvcPaidAmnt;
                                $scmSalesInvcOustndngStyle = "color:red;";
                                $scmSalesInvcPaidStyle = "color:black;";
                                if ($scmSalesInvcOustndngAmnt <= 0) {
                                    $scmSalesInvcOustndngStyle = "color:green;";
                                }
                                if ($scmSalesInvcPaidAmnt > 0 && $scmSalesInvcOustndngAmnt <= 0) {
                                    $scmSalesInvcPaidStyle = "color:green;";
                                } else if ($scmSalesInvcPaidAmnt > 0) {
                                    $scmSalesInvcPaidStyle = "color:brown;";
                                }
                                $reportName = getEnbldPssblValDesc("Sales Invoice", getLovID("Document Custom Print Process Names"));
                                $reportTitle = str_replace("Pro-Forma Invoice", "Payment Voucher", $scmSalesInvcVchType);

                                if ($scmSalesInvcVchType == "Sales Invoice" || $scmSalesInvcVchType == "Sales Return" || $scmSalesInvcVchType == "Sales Order" || $scmSalesInvcVchType == "Pro-Forma Invoice") {
                                    if ($scmSalesInvcAllwDues == "1") {
                                        $reportName = getEnbldPssblValDesc("Sales Invoice - Dues",
                                                getLovID("Document Custom Print Process Names"));
                                        $reportTitle = "Dues/Bills Payment Voucher";
                                    }
                                } else if ($scmSalesInvcVchType == "Item Issue-Unbilled") {
                                    $reportName = getEnbldPssblValDesc("Item Issues", getLovID("Document Custom Print Process Names"));
                                    $reportTitle = $scmSalesInvcVchType;
                                } else {
                                    $reportName = getEnbldPssblValDesc("Internal Item Request", getLovID("Document Custom Print Process Names"));
                                    $reportTitle = $scmSalesInvcVchType;
                                }
                                $rptID = getRptID($reportName);
                                $prmID1 = getParamIDUseSQLRep("{:invoice_id}", $rptID);
                                $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                                $trnsID = $sbmtdScmSalesInvcID;
                                $paramRepsNVals = $prmID1 . "~" . $trnsID . "|" . $prmID2 . "~" . $reportTitle . "|-130~" . $reportTitle . "|-190~PDF";
                                $paramStr = urlencode($paramRepsNVals);

                                $reportName1 = "Sales Invoice-POS";
                                $reportTitle1 = "Payment Receipt";
                                $rptID1 = getRptID($reportName1);
                                $prmID11 = getParamIDUseSQLRep("{:invoice_id}", $rptID1);
                                $prmID12 = getParamIDUseSQLRep("{:documentTitle}", $rptID1);
                                $paramRepsNVals1 = $prmID11 . "~" . $trnsID . "|" . $prmID12 . "~" . $reportTitle1 . "|-130~" . $reportTitle1 . "|-190~PDF";
                                $paramStr1 = urlencode($paramRepsNVals1);

                                $reportTitle2 = "Load Sales Dues Attached Values";
                                $reportName2 = "Load Sales Dues Attached Values";
                                $rptID2 = getRptID($reportName2);
                                $prmID212 = getParamIDUseSQLRep("{:invoice_id}", $rptID2);
                                $prmID213 = getParamIDUseSQLRep("{:p_storeid}", $rptID2);
                                $paramRepsNVals2 = $prmID212 . "~" . $sbmtdScmSalesInvcID . "|" . $prmID213 . "~" . $selectedStoreID . "|-130~" . $reportTitle2 . "|-190~HTML";
                                $paramStr2 = urlencode($paramRepsNVals2);
                                ?>
                                <fieldset class="basic_person_fs2" style="min-height:50px !important;">
                                    <div class="row">  
                                        <div  class="col-md-6" style="padding: 5px 2px 5px 15px;">
                                            <fieldset style="min-height:203px;height:203px;border:1px solid #ddd; border-radius: 5px;padding: 5px 1px 5px 1px;">
                                                <div class="form-group">
                                                    <div class="col-md-4">
                                                        <label style="margin-bottom:0px !important;">Document No./Name:</label>
                                                    </div>
                                                    <div class="col-md-3" style="padding:0px 1px 0px 15px;">
                                                        <input type="text" class="form-control" aria-label="..." id="sbmtdScmSalesInvcID" name="sbmtdScmSalesInvcID" value="<?php echo $sbmtdScmSalesInvcID; ?>" readonly="true" style="font-weight:bold;font-size:14px;">
                                                    </div>
                                                    <div class="col-md-5" style="padding:0px 15px 0px 1px;">
                                                        <input type="text" class="form-control" aria-label="..." id="scmSalesInvcDocNum" name="scmSalesInvcDocNum" value="<?php echo $gnrtdTrnsNo; ?>" readonly="true" style="font-weight:bold;font-size:14px;color:blue;">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-4">
                                                        <label style="margin-bottom:0px !important;">Document Date:</label>
                                                    </div>
                                                    <div class="col-md-8 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 15px 0px 15px !important;">
                                                        <input class="form-control" size="16" type="text" id="scmSalesInvcDfltTrnsDte" name="scmSalesInvcDfltTrnsDte" value="<?php echo $scmSalesInvcDfltTrnsDte; ?>" placeholder="Transactions Date" readonly="true">
                                                        <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-4">
                                                        <label style="margin-bottom:0px !important;">Document Classification:</label>
                                                    </div>
                                                    <div class="col-md-3" style="padding:0px 1px 0px 15px;">
                                                        <input type="text" class="form-control" aria-label="..." id="scmSalesInvcVchType" name="scmSalesInvcVchType" value="<?php echo $scmSalesInvcVchType; ?>" readonly="true">
                                                    </div>
                                                    <div  class="col-md-5" style="padding:0px 15px 0px 1px;">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="scmSalesInvcClssfctn" name="scmSalesInvcClssfctn" value="<?php echo $scmSalesInvcClssfctn; ?>" readonly="true">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sales Invoice Classifications', '', '', '', 'radio', true, '', 'scmSalesInvcClssfctn', '', 'clear', 1, '', function () {
                                                                        populateSalesDesc();
                                                                    });" data-toggle="tooltip" title="Invoice Classification">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="scmSalesInvcPayMthd" class="control-label col-md-4">Payment Method:</label>
                                                    <div  class="col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" aria-label="..." id="scmSalesInvcPayMthd" name="scmSalesInvcPayMthd" value="<?php echo $scmSalesInvcPayMthd; ?>" readonly="true">
                                                            <input type="hidden" id="scmSalesInvcPayMthdID" value="<?php echo $scmSalesInvcPayMthdID; ?>">
                                                            <input type="hidden" id="scmSalesInvcMthdType" value="Customer Payments">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Payment Methods', 'allOtherInputOrgID', 'scmSalesInvcMthdType', '', 'radio', true, '', 'scmSalesInvcPayMthdID', 'scmSalesInvcPayMthd', 'clear', 1, '');" data-toggle="tooltip" title="Existing Payment Method">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="scmSalesInvcCstmr" class="control-label col-md-4">Customer:</label>
                                                    <div  class="col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" aria-label="..." id="scmSalesInvcCstmr" name="scmSalesInvcCstmr" value="<?php echo $scmSalesInvcCstmr; ?>" <?php echo $mkReadOnly; ?>>
                                                            <input type="hidden" id="scmSalesInvcCstmrID" value="<?php echo $scmSalesInvcCstmrID; ?>">
                                                            <input type="hidden" id="scmSalesInvcCstmrClsfctn" value="<?php echo $scmSalesInvcCstmrClsfctn; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getCstmrSpplrForm(-1, 'Create/Edit Customer', 'ShowDialog', function () {}, 'scmSalesInvcCstmrID');" data-toggle="tooltip" title="Create/Edit Supplier">
                                                                <span class="glyphicon glyphicon-plus"></span>
                                                            </label>
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'allOtherInputOrgID', '', 'scmSalesInvcCstmrClsfctn', 'radio', true, '', 'scmSalesInvcCstmrID', 'scmSalesInvcCstmr', 'clear', 1, '', function () {
                                                                        getInvRcvblsAcntInfo();
                                                                    });" data-toggle="tooltip" title="Existing Client/Vendor">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>  
                                                <div class="form-group">
                                                    <label for="scmSalesInvcCstmrSite" class="control-label col-md-4">Site:</label>
                                                    <div  class="col-md-5" style="padding:0px 1px 0px 15px;">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" aria-label="..." id="scmSalesInvcCstmrSite" name="scmSalesInvcCstmrSite" value="<?php echo $scmSalesInvcCstmrSite; ?>" readonly="true">
                                                            <input class="form-control" type="hidden" id="scmSalesInvcCstmrSiteID" value="<?php echo $scmSalesInvcCstmrSiteID; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Customer/Supplier Sites', 'scmSalesInvcCstmrID', '', '', 'radio', true, '', 'scmSalesInvcCstmrSiteID', 'scmSalesInvcCstmrSite', 'clear', 1, '');" data-toggle="tooltip" title="">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div  class="col-md-3" style="padding:5px 15px 0px 1px;display:inline-table !important;vertical-align: middle !important;float: left !important;">
                                                        <div class="form-check" style="font-size: 12px !important;float: left !important;">
                                                            <label class="form-check-label" title="Check to Enable Dues/Bills Payment Template">
                                                                <?php
                                                                $scmSalesInvcAllwDuesChkd = "";
                                                                if ($scmSalesInvcAllwDues == "1") {
                                                                    $scmSalesInvcAllwDuesChkd = "checked=\"true\"";
                                                                }
                                                                ?>
                                                                &nbsp;<input type="checkbox" class="form-check-input" id="scmSalesInvcAllwDues" name="scmSalesInvcAllwDues"  <?php echo $scmSalesInvcAllwDuesChkd; ?> onclick="shwHideDuesPayDivs1('CHECK');">
                                                            </label>
                                                        </div>
                                                        <div class="" style="margin-top:2px !important; float: left !important;font-weight:bold;cursor: pointer;" onclick="shwHideDuesPayDivs1();">
                                                            <span>&nbsp;Allow Dues / Bills</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div  class="col-md-6" style="padding: 5px 15px 5px 2px;">
                                            <fieldset style="border:1px solid #ddd; border-radius: 5px;padding: 5px 1px 5px 1px;">
                                                <div class="form-group">
                                                    <div class="col-md-12" style="display:none;">
                                                        <label style="margin-bottom:0px !important;">Invoice Total:</label>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <label class="btn btn-primary btn-file input-group-addon active" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $scmSalesInvcInvcCur; ?>', 'scmSalesInvcInvcCur', '', 'clear', 0, '', function () {
                                                                        $('#scmSalesInvcInvcCur1').html($('#scmSalesInvcInvcCur').val());
                                                                        $('#scmSalesInvcInvcCur2').html($('#scmSalesInvcInvcCur').val());
                                                                        $('#scmSalesInvcInvcCur3').html($('#scmSalesInvcInvcCur').val());
                                                                        $('#scmSalesInvcInvcCur4').html($('#scmSalesInvcInvcCur').val());
                                                                        $('#scmSalesInvcInvcCur5').html($('#scmSalesInvcInvcCur').val());
                                                                        $('#scmSalesInvcInvcCur10').html($('#scmSalesInvcInvcCur').val());
                                                                    });">
                                                                <span class="" style="font-size: 70px !important;" id="scmSalesInvcInvcCur1"><?php echo $scmSalesInvcInvcCur; ?></span>
                                                            </label>
                                                            <input type="hidden" id="scmSalesInvcInvcCur" value="<?php echo $scmSalesInvcInvcCur; ?>"> 
                                                            <input type="hidden" id="scmSalesInvcInvcCurID" value="<?php echo $scmSalesInvcInvcCurID; ?>"> 
                                                            <input class="form-control" type="text" id="scmSalesInvcOustndngAmnt" value="<?php
                                                            echo number_format($scmSalesInvcOustndngAmnt, 2);
                                                            ?>" style="font-weight:bold;width:100%;font-size:65px !important;height:90px !important;<?php echo $scmSalesInvcOustndngStyle; ?>" onchange="fmtAsNumber('scmSalesInvcOustndngAmnt');"  readonly="true"/>

                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <fieldset style="min-height:100px;height:100px;border:1px solid #ddd; border-radius: 5px;padding: 5px 1px 5px 1px;">
                                                <div class="form-group">
                                                    <div class="col-md-4">
                                                        <label style="margin-bottom:0px !important;">Amount Tendered:</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <label class="btn btn-primary btn-file input-group-addon active" onclick="">
                                                                <span class="" style="font-size: 20px !important;" id="scmSalesInvcInvcCur2"><?php echo $scmSalesInvcInvcCur; ?></span>
                                                            </label>
                                                            <input class="form-control" type="text" id="scmSalesInvcTndrdAmnt" value="0.00" style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('scmSalesInvcTndrdAmnt');" onkeyup="quickSalePayAmntKeyFunc(event, <?php echo $sbmtdScmRcvblsInvcID; ?>, <?php echo $sbmtdScmSalesInvcID; ?>, '<?php echo $scmSalesInvcVchType; ?>');"/>
                                                            <?php
                                                            if ($rqStatus == "Not Validated" && $canPayDocs === true && $scmSalesInvcCstmrID > 0 && ($scmSalesInvcVchType == "Sales Invoice" || $scmSalesInvcVchType == "Sales Order" || $scmSalesInvcVchType == "Pro-Forma Invoice")) {
                                                                ?>
                                                                <input type="hidden" id="scmSalesInvcCanTakeDpsts" value="99"> 
                                                                <label data-toggle="tooltip" title="Take Customer Advance Payment" class="btn btn-success btn-file input-group-addon" onclick="getOneAccbPayInvcForm(<?php echo $sbmtdScmRcvblsInvcID; ?>, 'Customer Payments', 'ShowDialog', -1, <?php echo $sbmtdScmSalesInvcID; ?>, '<?php echo $scmSalesInvcVchType; ?>', 'scmSalesInvcTndrdAmnt', 'QUICK_SALE', 'scmSalesInvcCstmrID', 'scmSalesInvcInvcCur');">
                                                                    <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Take Deposit
                                                                </label>
                                                                <?php
                                                            } else if ($rqStatus == "Approved" && $canPayDocs === true && $scmSalesInvcOustndngAmnt > 0 && $scmSalesInvcVchType == "Sales Invoice") {
                                                                ?>
                                                                <input type="hidden" id="scmSalesInvcCanTakeDpsts" value="98"> 
                                                                <label data-toggle="tooltip" title="Process Payment" class="btn btn-success btn-file input-group-addon" onclick="getOneAccbPayInvcForm(<?php echo $sbmtdScmRcvblsInvcID; ?>, 'Customer Payments', 'ShowDialog', -1, <?php echo $sbmtdScmSalesInvcID; ?>, '<?php echo $scmSalesInvcVchType; ?>', 'scmSalesInvcTndrdAmnt', 'QUICK_SALE');">
                                                                    <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Process Payment
                                                                </label>
                                                            <?php } else { ?>
                                                                <input type="hidden" id="scmSalesInvcCanTakeDpsts" value="0"> 
                                                                <label data-toggle="tooltip" title="Process Payment" class="btn btn-success btn-file input-group-addon" onclick="">
                                                                    <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">No Action
                                                                </label>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-4">
                                                        <label style="margin-bottom:0px !important;">Total Amount Received:</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <label class="btn btn-primary btn-file input-group-addon active" onclick="">
                                                                <span class="" style="font-size: 20px !important;" id="scmSalesInvcInvcCur2"><?php echo $scmSalesInvcInvcCur; ?></span>
                                                            </label>
                                                            <input class="form-control" type="text" id="scmSalesInvcPaidAmnt" value="<?php
                                                            echo number_format($scmSalesInvcPaidAmnt, 2);
                                                            ?>" style="font-weight:bold;width:100%;font-size:18px !important;<?php echo $scmSalesInvcPaidStyle; ?>" onchange="fmtAsNumber('scmSalesInvcPaidAmnt');" readonly="true"/>
                                                            <label data-toggle="tooltip" title="History of Payments" class="btn btn-primary btn-file input-group-addon" onclick="getOneAccbPymntsHstryForm(<?php echo $sbmtdScmRcvblsInvcID; ?>, 103, 'ReloadDialog',<?php echo $sbmtdScmSalesInvcID; ?>, 'Quick Sales Invoice', 'Customer Payments');">
                                                                <img src="cmn_images/openfileicon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-4">
                                                        <label style="margin-bottom:0px !important;">Invoice Total:</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <label class="btn btn-primary btn-file input-group-addon active" onclick="">
                                                                <span class="" style="font-size: 20px !important;" id="scmSalesInvcInvcCur3"><?php echo $scmSalesInvcInvcCur; ?></span>
                                                            </label>
                                                            <input class="form-control" type="text" id="scmSalesInvcTtlAmnt" value="<?php
                                                            echo number_format($scmSalesInvcTtlAmnt, 2);
                                                            ?>" style="font-weight:bold;width:100%;font-size:18px !important;color:blue" onchange="fmtAsNumber('scmSalesInvcTtlAmnt');" readonly="true"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="basic_person_fs <?php echo $duesPayCls; ?>" style="padding-top:3px !important;" id="scmSalesInvcAllwDuesDiv">
                                    <div class="row" style="margin-top:5px;padding: 0px 10px 0px 10px !important;">
                                        <div  class="col-md-4" style="padding: 0px 10px 0px 10px !important;">
                                            <div class="form-group form-group-sm">
                                                <label for="scmSalesInvcPyItmSetNm" class="control-label col-md-3">Item Set:</label>
                                                <div  class="col-md-5" style="padding: 0px 1px 0px 13px !important;">
                                                    <div class="input-group">
                                                        <input class="form-control" id="scmSalesInvcPyItmSetNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Item Sets for Payments" type = "text" value="<?php echo $scmSalesInvcPyItmSetNm; ?>" readonly="true"/>
                                                        <input type="hidden" id="scmSalesInvcPyItmSetID" value="<?php echo $scmSalesInvcPyItmSetID; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Item Sets for Payments(Enabled)', 'allOtherInputOrgID', '', '', 'radio', true, '', 'scmSalesInvcPyItmSetID', 'scmSalesInvcPyItmSetNm', 'clear', 1, '', function () {});">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div  class="col-md-4" style="padding:5px 17px 0px 5px;display:inline-table !important;vertical-align: middle !important;float: left !important;" title="Use existing Advance Payment Balances to settle  for Persons that have Advance Balances!">
                                                    <div class="form-check" style="font-size: 12px !important;float: left !important;">
                                                        <label class="form-check-label">
                                                            <?php
                                                            $scmSalesInvcAplyAdvncChkd = "";
                                                            if ($scmSalesInvcAplyAdvnc == "1") {
                                                                $scmSalesInvcAplyAdvncChkd = "checked=\"true\"";
                                                            }
                                                            ?>
                                                            <input type="checkbox" class="form-check-input" id="scmSalesInvcAplyAdvnc" name="scmSalesInvcAplyAdvnc"  <?php echo $scmSalesInvcAplyAdvncChkd; ?> onclick="">
                                                        </label>
                                                    </div>
                                                    <div class="" style="margin-top:2px !important; float: left !important;font-weight:bold;cursor: pointer;color:red;" onclick="tickUntickChckBx('scmSalesInvcAplyAdvnc');">
                                                        <span>&nbsp;Apply Advance</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div  class="col-md-4">
                                            <div class="form-group form-group-sm">
                                                <div class="col-md-4" style="padding: 5px 15px 0px 13px !important;">
                                                    <label style="margin-bottom:0px !important;">Amount To Pay:</label>
                                                </div>
                                                <div class="col-md-5" style="padding: 0px 1px 0px 11px !important;">
                                                    <div class="input-group">
                                                        <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                                            <span class="" style="font-size: 20px !important;" id="scmSalesInvcInvcCur10"><?php echo $scmSalesInvcInvcCur; ?></span>
                                                        </label>
                                                        <input class="form-control" type="text" id="scmSalesInvcPyAmntGvn" value="<?php
                                                        echo number_format($scmSalesInvcPyAmntGvn, 2);
                                                        ?>"  style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('scmSalesInvcPyAmntGvn');"/>
                                                    </div>
                                                </div>
                                                <div  class="col-md-3" style="padding:5px 20px 0px 5px;display:inline-table !important;vertical-align: middle !important;float: left !important;" title="Keep and Apply Excess amounts as Advancve Payments">
                                                    <div class="form-check" style="font-size: 12px !important;float: left !important;">
                                                        <label class="form-check-label">
                                                            <?php
                                                            $scmSalesInvcKeepExcssChkd = "";
                                                            if ($scmSalesInvcKeepExcss == "1") {
                                                                $scmSalesInvcKeepExcssChkd = "checked=\"true\"";
                                                            }
                                                            ?>
                                                            <input type="checkbox" class="form-check-input" id="scmSalesInvcKeepExcss" name="scmSalesInvcKeepExcss"  <?php echo $scmSalesInvcKeepExcssChkd; ?> onclick="">
                                                        </label>
                                                    </div>
                                                    <div class="" style="margin-top:2px !important; float: left !important;font-weight:bold;cursor: pointer;color:red;" onclick="tickUntickChckBx('scmSalesInvcKeepExcss');">
                                                        <span>&nbsp;Keep All</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div  class="col-md-4">
                                            <div class="form-group form-group-sm">
                                                <div class="col-md-4" style="padding: 5px 15px 0px 8px !important;">
                                                    <label style="margin-bottom:0px !important;">Cheque Number:</label>
                                                </div>
                                                <div class="col-md-5" style="padding: 0px 2px 0px 8px !important;">
                                                    <input type="text" class="form-control" aria-label="..." data-toggle="tooltip" title="Cheque/Card Number" id="scmSalesInvcPyChqNumber" name="scmSalesInvcPyChqNumber" value="<?php echo $scmSalesInvcPyChqNumber; ?>" <?php echo $mkReadOnly; ?>>
                                                </div>
                                                <div class="col-md-3" style="padding: 0px 22px 0px 2px !important;">
                                                    <input class="form-control" type="text" data-toggle="tooltip" title="Sign Code (CCV)" id="scmSalesInvcPySignCode" value="<?php echo $scmSalesInvcPySignCode; ?>" style="width:100%;" <?php echo $mkReadOnly; ?>/>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                </fieldset>
                                <fieldset class="">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <ul class="nav nav-tabs" style="margin-top:1px !important;">
                                                <li class="active"><a data-toggle="tabajxsalesinvc" data-rhodata="" href="#salesInvcDetLines" id="salesInvcDetLinestab">Invoice Lines</a></li>
                                                <li class=""><a data-toggle="tabajxsalesinvc" data-rhodata="" href="#salesInvcExtraInfo" id="salesInvcExtraInfotab">Extra Information</a></li>
                                            </ul>  
                                            <div class="custDiv" style="padding:0px !important;min-height: 30px !important;"> 
                                                <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <?php
                                                            $edtPriceRdOnly = "readonly=\"true\"";
                                                            if ($canEdtPrice === true) {
                                                                $edtPriceRdOnly = "";
                                                            }
                                                            $trsctnLnTxNm = "View Tax Codes";
                                                            $trsctnLnDscntNm = "View Discounts";
                                                            $trsctnLnChrgNm = "View Extra Charges";
                                                            $nwRowHtml33 = "<tr id=\"oneScmSalesInvcSmryRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#oneScmSalesInvcSmryLinesTable tr').index(this));\">"
                                                                    . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>                          
                                                           <td class=\"lovtd\"  style=\"\">  
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_ItmID\" value=\"-1\" style=\"width:100% !important;\">  
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_StoreID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_CnsgnIDs\" value=\"\" style=\"width:100% !important;\"> 
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_ItmAccnts\" value=\"\" style=\"width:100% !important;\"> 
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_LnkdPrsnID\" value=\"-1\" style=\"width:100% !important;\">  
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_SrcDocLnID\" value=\"-1\" style=\"width:100% !important;\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_RentedQty\" value=\"1\" style=\"width:100% !important;\">     
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_OthrMdlID\" value=\"-1\" style=\"width:100% !important;\">     
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_OthrMdlTyp\" value=\"-1\" style=\"width:100% !important;\"> 
                                                                        <div class=\"input-group\" style=\"width:100% !important;\">
                                                                         <input type=\"text\" class=\"form-control rqrdFld jbDetAccRate jbDetDesc\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_LineDesc\" name=\"oneScmSalesInvcSmryRow_WWW123WWW_LineDesc\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneScmSalesInvcSmryRow_WWW123WWW_LineDesc', 'oneScmSalesInvcSmryLinesTable', 'jbDetAccRate');\" onblur=\"afterSalesInvcItmSlctn('oneScmSalesInvcSmryRow__WWW123WWW');\" onchange=\"autoCreateSalesLns=99;\">
                                                                         <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getScmSalesInvcItems('oneScmSalesInvcSmryRow__WWW123WWW', 'ShowDialog', '" . $scmSalesInvcVchType . "', 'false', function () {var a=1;});\">
                                                                             <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                         </label>
                                                                        </div>
                                                                    </td> 
                                                                    <td class=\"lovtd\" style=\"text-align: right;\">
                                                                        <input type=\"text\" class=\"form-control rqrdFld jbDetAccRate\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_QTY\" name=\"oneScmSalesInvcSmryRow_WWW123WWW_QTY\" value=\"0\" onkeypress=\"gnrlFldKeyPress(event, 'oneScmSalesInvcSmryRow_WWW123WWW_QTY', 'oneScmSalesInvcSmryLinesTable', 'jbDetAccRate');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllScmSalesInvcSmryTtl();\">                                                    
                                                                    </td>                                               
                                                                    <td class=\"lovtd\" style=\"max-width:35px;width:35px;text-align: center;\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_UomID\" value=\"-1\" style=\"width:100% !important;\">  
                                                                        <div class=\"\" style=\"width:100% !important;\">
                                                                            <label class=\"btn btn-primary btn-file\" onclick=\"getOneScmUOMBrkdwnForm(-1, 2, 'oneScmSalesInvcSmryRow__WWW123WWW');\">
                                                                                <span class=\"\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_UomNm1\">Each</span>
                                                                            </label>
                                                                        </div>                                              
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                        <input type=\"text\" class=\"form-control jbDetDbt\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_UnitPrice\" name=\"oneScmSalesInvcSmryRow_WWW123WWW_UnitPrice\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'oneScmSalesInvcSmryRow_WWW123WWW_UnitPrice', 'oneScmSalesInvcSmryLinesTable', 'jbDetDbt');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllScmSalesInvcSmryTtl();\" " . $edtPriceRdOnly . ">                                                    
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                        <input type=\"text\" class=\"form-control jbDetCrdt\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_LineAmt\" name=\"oneScmSalesInvcSmryRow_WWW123WWW_LineAmt\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'oneScmSalesInvcSmryRow_WWW123WWW_LineAmt', 'oneScmSalesInvcSmryLinesTable', 'jbDetCrdt');\" style=\"width:100% !important;text-align: right;\" readonly=\"true\" onchange=\"calcAllScmSalesInvcSmryTtl();\">                                                    
                                                                    </td>  
                                                                    <td class=\"lovtd\" style=\"text-align: center;\">
                                                                        <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View Item Consignments\" 
                                                                                onclick=\"getScmSalesInvcItems('oneScmSalesInvcSmryRow__WWW123WWW', 'ShowDialog', '" . $scmSalesInvcVchType . "', 'true', function () {var a=1;});\" style=\"padding:2px !important;\"> 
                                                                            <img src=\"cmn_images/chcklst3.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                                        </button>
                                                                    </td>    
                                                                    <td class=\"lovtd\" style=\"text-align: center;\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_TaxID\" value=\"-1\" style=\"width:100% !important;\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_TaxNm\" value=\"View Tax Codes\" style=\"width:100% !important;\">  
                                                                        <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View Tax Codes\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_TaxBtn\"
                                                                                onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Tax Codes', 'allOtherInputOrgID', '', '', 'radio', true, '-1', 'oneScmSalesInvcSmryRow_WWW123WWW_TaxID', 'oneScmSalesInvcSmryRow_WWW123WWW_TaxNm', 'clear', 0, '', function () {
                                                                                                                    changeBtnTitleFunc('oneScmSalesInvcSmryRow_WWW123WWW_TaxNm', 'oneScmSalesInvcSmryRow_WWW123WWW_TaxBtn');
                                                                                                                });\" style=\"padding:2px !important;\"> 
                                                                            <img src=\"cmn_images/tax-icon420x500.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                                        </button>
                                                                    </td>   
                                                                    <td class=\"lovtd\" style=\"text-align: center;\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_DscntID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_DscntNm\" value=\"View Discounts\" style=\"width:100% !important;\"> 
                                                                        <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View Discounts\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_DscntBtn\"  
                                                                                onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Discount Codes', 'allOtherInputOrgID', '', '', 'radio', true, '-1', 'oneScmSalesInvcSmryRow_WWW123WWW_DscntID', 'oneScmSalesInvcSmryRow_WWW123WWW_DscntNm', 'clear', 0, '', function () {
                                                                                                                    changeBtnTitleFunc('oneScmSalesInvcSmryRow_WWW123WWW_DscntNm', 'oneScmSalesInvcSmryRow_WWW123WWW_DscntBtn');
                                                                                                                });\" style=\"padding:2px !important;\"> 
                                                                            <img src=\"cmn_images/dscnt_456356.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                                        </button>
                                                                    </td>  
                                                                    <td class=\"lovtd\" style=\"text-align: center;\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_ChrgID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_ChrgNm\" value=\"View Extra Charges\" style=\"width:100% !important;\"> 
                                                                        <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View Extra Charges\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_ChrgBtn\" 
                                                                                onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Extra Charges', 'allOtherInputOrgID', '', '', 'radio', true, '-1', 'oneScmSalesInvcSmryRow_WWW123WWW_ChrgID', 'oneScmSalesInvcSmryRow_WWW123WWW_ChrgNm', 'clear', 0, '', function () {
                                                                                                                    changeBtnTitleFunc('oneScmSalesInvcSmryRow_WWW123WWW_ChrgNm', 'oneScmSalesInvcSmryRow_WWW123WWW_ChrgBtn');
                                                                                                                });\" style=\"padding:2px !important;\"> 
                                                                            <img src=\"cmn_images/truck571d7f45.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                                        </button>
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_ExtraDesc\" name=\"oneScmSalesInvcSmryRow_WWW123WWW_ExtraDesc\" value=\"\" style=\"width:100% !important;text-align: left;\" readonly=\"true\">                                                    
                                                                    </td>";
                                                            if ($scmSalesInvcAllwDues === '1') {
                                                                $nwRowHtml33 .= "<td class=\"lovtd\" style=\"text-align: center;\">
                                                                                        <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Linked Person\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_PrsnBtn\" 
                                                                                                onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', 'allOtherInputOrgID', '', '', 'radio', true, '-1', 'oneScmSalesInvcSmryRow_WWW123WWW_LnkdPrsnID', 'oneScmSalesInvcSmryRow_WWW123WWW_ExtraDesc', 'clear', 0, '', function () {});\" style=\"padding:2px !important;\"> 
                                                                                            <img src=\"cmn_images/person.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                                                        </button>
                                                                                    </td>";
                                                            }
                                                            $nwRowHtml33 .= "<td class=\"lovtd\" style=\"text-align: center;\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delScmSalesInvcDetLn('oneScmSalesInvcSmryRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Trns. Line\">
                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                            </button>
                                                        </td>
                                                    </tr>";
                                                            $nwRowHtml33 = urlencode($nwRowHtml33);
                                                            ?> 
                                                            <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                <div class="col-md-6" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                    <?php if ($canEdt) { ?>
                                                                        <input type="hidden" id="nwSalesDocLineHtm" value="<?php echo $nwRowHtml33; ?>">
                                                                        <button id="addNwScmSalesInvcSmryBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="insertNewScmSalesInvcRows('oneScmSalesInvcSmryLinesTable', 0, '<?php echo $nwRowHtml33; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Transaction Line">
                                                                            <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>                                 
                                                                    <?php } ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneScmSalesInvcDocsForm(<?php echo $sbmtdScmSalesInvcID; ?>, 20);" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                                                        <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    </button> 
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getScmSalesInvc('', '#allmodules', 'grp=12&typ=1&pg=1&vtyp=1');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;"  onclick="printEmailFullInvc(<?php echo $sbmtdScmSalesInvcID; ?>);" style="width:100% !important;"  data-toggle="tooltip" data-placement="bottom" title = "Print Invoice">
                                                                        <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        Print
                                                                    </button>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;"  onclick="printPOSRcpt(<?php echo $sbmtdScmSalesInvcID; ?>);" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title = "POS Receipt">
                                                                        <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        POS
                                                                    </button>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;"  onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title = "Print Invoice">
                                                                        <img src="cmn_images/print.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    </button>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;"  onclick="getSilentRptsRnSts(<?php echo $rptID1; ?>, -1, '<?php echo $paramStr1; ?>');" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title = "POS Receipt">
                                                                        <img src="cmn_images/printer-icon.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    </button>
                                                                    <button type="button" class="btn btn-default" style="height:30px;margin-bottom: 1px;">
                                                                        <span style="font-weight:bold;color:black;">Total: </span>
                                                                        <span style="color:red;font-weight: bold;" id="myCptrdSalesInvcValsTtlBtn"><?php echo $scmSalesInvcInvcCur; ?> 
                                                                            <?php
                                                                            echo number_format($scmSalesInvcTtlAmnt, 2);
                                                                            ?>
                                                                        </span>
                                                                        <input type="hidden" id="myCptrdSalesInvcValsTtlVal" value="<?php echo $scmSalesInvcTtlAmnt; ?>">
                                                                    </button>
                                                                    <input type="hidden" id="scmSalesInvcApprvlStatus" value="<?php echo $rqStatus; ?>">                             
                                                                    <button type="button" class="btn btn-default" style="height:30px;margin-bottom: 1px;;" id="myScmSalesInvcStatusBtn">
                                                                        <span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;height:30px;">
                                                                            <?php
                                                                            echo $rqStatus . ($scmSalesInvcIsPstd == "1" ? " [Posted]" : " [Not Posted]");
                                                                            ?>
                                                                        </span>
                                                                    </button>
                                                                </div>
                                                                <div class="col-md-3" style="padding:0px 10px 0px 10px !important;"> 
                                                                    <div class="form-group">
                                                                        <label for="scmSalesInvcBrnchNm" class="control-label col-md-3" style="padding:5px 10px 0px 13px !important;">Branch:</label>
                                                                        <div  class="col-md-9" style="padding:0px 23px 0px 11px !important;">
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control" aria-label="..." id="scmSalesInvcBrnchNm" name="scmSalesInvcBrnchNm" value="<?php echo $scmSalesInvcBrnchNm; ?>" readonly="true">
                                                                                <input class="form-control" type="hidden" id="scmSalesInvcBrnchID" value="<?php echo $scmSalesInvcBrnchID; ?>">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sites/Locations', 'allOtherInputOrgID', '', '', 'radio', true, '', 'scmSalesInvcBrnchID', 'scmSalesInvcBrnchNm', 'clear', 0, '');" data-toggle="tooltip" title="">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div> 
                                                                <div class="col-md-3" style="padding:0px 0px 0px 0px !important;">
                                                                    <div class="" style="padding:0px 0px 0px 0px;float:right !important;"> 
                                                                        <?php
                                                                        if ($rqStatus == "Not Validated") {
                                                                            ?>
                                                                            <?php if ($canEdt) { ?>
                                                                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveScmSalesInvcForm('<?php echo $fnccurnm; ?>', 0, 'QUICK_SALE', '<?php echo $musAllwDues; ?>', '<?php echo $srcCaller; ?>');" data-toggle="tooltip" data-placement="bottom" title="Save Transaction">
                                                                                    <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; height:17px; width:auto; position: relative; vertical-align: middle;">&nbsp;Save
                                                                                </button>    
                                                                            <?php } if ($scmSalesInvcAllwDues == "1") { ?>
                                                                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="runMassPyValuesForm(<?php echo $rptID2; ?>, -1, '<?php echo $paramStr2; ?>', 'QUICK_SALE', '<?php echo $srcCaller; ?>');" data-toggle="tooltip" data-placement="bottom" title="Load Attached Values">
                                                                                    <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                                                </button>
                                                                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveScmSalesInvcForm('<?php echo $fnccurnm; ?>', 7, 'QUICK_SALE', '<?php echo $musAllwDues; ?>', '<?php echo $srcCaller; ?>');" data-toggle="tooltip" data-placement="bottom" title="Save and Automatically Load Attached Values">
                                                                                    <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save/Load</button>    
                                                                            <?php } if ($canRvwApprvDocs) { ?>
                                                                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveScmSalesInvcForm('<?php echo $fnccurnm; ?>', 2, 'QUICK_SALE', '<?php echo $musAllwDues; ?>', '<?php echo $srcCaller; ?>');" data-toggle="tooltip" data-placement="bottom" title="Finalize Document">
                                                                                    <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Finalize
                                                                                </button>
                                                                                <?php
                                                                            }
                                                                        } else if ($rqStatus == "Approved") {
                                                                            if ($cancelDocs) {
                                                                                ?>
                                                                                <button id="fnlzeRvrslScmSalesInvcBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveScmSalesInvcRvrslForm('<?php echo $fnccurnm; ?>', 1, 'QUICK_SALE');"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Cancel Document&nbsp;</button>  
                                                                                <!--<button id="fnlzeBadDebtScmSalesInvcBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveScmSalesInvcRvrslForm('<?php echo $fnccurnm; ?>', 2, 'QUICK_SALE');"  data-toggle="tooltip" data-placement="bottom" title="Declare as Bad Debt">
                                                                                <img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Bad Debt&nbsp;</button>-->                                                                   
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>                    
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneScmSalesInvcLnsTblSctn"> 
                                                <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                                    <div id="salesInvcDetLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                                        <div class="row" style="padding:0px 13px 0px 13px !important;">
                                                            <div class="col-md-10" style="padding:0px 2px 0px 2px !important;">
                                                                <table class="table table-striped table-bordered table-responsive" id="oneScmSalesInvcSmryLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="max-width:30px;width:30px;">No.</th>
                                                                            <th style="min-width:250px;">Item Code/Description</th>
                                                                            <th style="max-width:50px;width:50px;text-align: right;">QTY</th>
                                                                            <th style="max-width:55px;text-align: center;">UOM.</th>
                                                                            <th style="max-width:170px;width:140px;text-align: right;">Unit Selling Price</th>
                                                                            <th style="max-width:170px;width:120px;text-align: right;">Total Amount</th>
                                                                            <th style="max-width:30px;width:30px;text-align: center;">CS</th>
                                                                            <th style="max-width:30px;width:30px;text-align: center;">TX</th>
                                                                            <th style="max-width:30px;width:30px;text-align: center;">DC</th>
                                                                            <th style="max-width:30px;width:30px;text-align: center;">EX</th>
                                                                            <th style="min-width:150px;">Extra Description</th>
                                                                            <?php if ($scmSalesInvcAllwDues === '1') { ?>
                                                                                <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                                            <?php } ?>
                                                                            <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>   
                                                                        <?php
                                                                        $cntr = 0;
                                                                        $resultRw = get_SalesInvcDocDet($sbmtdScmSalesInvcID);
                                                                        $ttlTrsctnEntrdAmnt = 0;
                                                                        $trnsBrkDwnVType = "VIEW";
                                                                        if ($mkReadOnly == "") {
                                                                            $trnsBrkDwnVType = "EDIT";
                                                                        }
                                                                        $ttlRows = loc_db_num_rows($resultRw);
                                                                        if ($ttlRows > 0) {
                                                                            while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                                $trsctnLnID = (float) $rowRw[0];
                                                                                $trsctnLnItmID = (float) $rowRw[1];
                                                                                $trsctnLnQty = (float) $rowRw[2];
                                                                                $trsctnLnUnitPrice = (float) $rowRw[3];
                                                                                $trsctnLnAmnt = (float) $rowRw[4];
                                                                                $ttlTrsctnEntrdAmnt = $ttlTrsctnEntrdAmnt + $trsctnLnAmnt;
                                                                                $trsctnLnStoreID = (float) $rowRw[5];
                                                                                $trsctnLnCurID = $rowRw[6];
                                                                                $trsctnLnAvlblQty = (float) $rowRw[7];
                                                                                $trsctnLnSrcLnID = (float) $rowRw[8];
                                                                                $trsctnLnTxID = (float) $rowRw[9];
                                                                                $trsctnLnDscntID = (float) $rowRw[10];
                                                                                $trsctnLnChrgID = (float) $rowRw[11];
                                                                                $trsctnLnRetrnRsn = $rowRw[12];
                                                                                $trsctnLnCnsgnIDs = $rowRw[13];
                                                                                $trsctnLnOrgnlPrice = (float) $rowRw[14];
                                                                                $trsctnLnUomID = (float) $rowRw[15];
                                                                                $trsctnLnUomNm = $rowRw[18];
                                                                                $trsctnLnIsDlvrd = $rowRw[19];
                                                                                $trsctnLnExtrDesc = $rowRw[20];
                                                                                $trsctnLnOthMdlDocID = (float) $rowRw[21];
                                                                                $trsctnLnOthMdlDocType = $rowRw[22];
                                                                                $trsctnLnLnkdPrsnID = (float) $rowRw[23];
                                                                                $trsctnLnLnkdPrsnNm = $rowRw[24];
                                                                                $trsctnLnDesc = $rowRw[25];
                                                                                $trsctnLnItmAccnts = $rowRw[27];
                                                                                $ln_RentedQty = (float) $rowRw[29];
                                                                                $ln_OthrMdlID = (float) $rowRw[30];
                                                                                $ln_OthrMdlTyp = $rowRw[31];
                                                                                $trsctnLnTxNm = ($trsctnLnTxID > 0) ? $rowRw[32] : "View Tax Codes";
                                                                                $trsctnLnDscntNm = ($trsctnLnDscntID > 0) ? $rowRw[33] : "View Discounts";
                                                                                $trsctnLnChrgNm = ($trsctnLnChrgID > 0) ? $rowRw[34] : "View Extra Charges";
                                                                                if ($ln_RentedQty == 0) {
                                                                                    $ln_RentedQty = 1;
                                                                                }
                                                                                if ($trsctnLnLnkdPrsnID > 0) {
                                                                                    $trsctnLnExtrDesc = $trsctnLnLnkdPrsnNm;
                                                                                }
                                                                                $cntr += 1;
                                                                                ?>
                                                                                <tr id="oneScmSalesInvcSmryRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneScmSalesInvcSmryLinesTable tr').index(this));">                                    
                                                                                    <td class="lovtd"><span><?php echo ($cntr); ?></span></td>                                              
                                                                                    <td class="lovtd"  style="">  
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLnID; ?>" style="width:100% !important;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ItmID" value="<?php echo $trsctnLnItmID; ?>" style="width:100% !important;">  
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_StoreID" value="<?php echo $trsctnLnStoreID; ?>" style="width:100% !important;"> 
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_CnsgnIDs" value="<?php echo $trsctnLnCnsgnIDs; ?>" style="width:100% !important;"> 
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ItmAccnts" value="<?php echo $trsctnLnItmAccnts; ?>" style="width:100% !important;"> 
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LnkdPrsnID" value="<?php echo $trsctnLnLnkdPrsnID; ?>" style="width:100% !important;">   
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_SrcDocLnID" value="<?php echo $trsctnLnSrcLnID; ?>" style="width:100% !important;"> 
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_RentedQty" value="<?php echo $ln_RentedQty; ?>" style="width:100% !important;">     
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_OthrMdlID" value="<?php echo $ln_OthrMdlID; ?>" style="width:100% !important;">     
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_OthrMdlTyp" value="<?php echo $ln_OthrMdlTyp; ?>" style="width:100% !important;">  
                                                                                        <?php
                                                                                        if ($canEdt === true) {
                                                                                            //onfocusout=\"afterSalesInvcItmSlctn('oneScmSalesInvcSmryRow__WWW123WWW');
                                                                                            //onfocusout="afterSalesInvcItmSlctn('oneScmSalesInvcSmryRow_');"
                                                                                            ?>
                                                                                            <div class="input-group" style="width:100% !important;">
                                                                                                <input type="text" class="form-control rqrdFld jbDetAccRate jbDetDesc" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LineDesc" name="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLnDesc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LineDesc', 'oneScmSalesInvcSmryLinesTable', 'jbDetAccRate');" onblur="afterSalesInvcItmSlctn('oneScmSalesInvcSmryRow_<?php echo $cntr; ?>');" onchange="autoCreateSalesLns = 99;">
                                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getScmSalesInvcItems('oneScmSalesInvcSmryRow_<?php echo $cntr; ?>', 'ShowDialog', '<?php echo $scmSalesInvcVchType; ?>', 'false', function () {
                                                                                                            var a = 1;
                                                                                                        });">
                                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        <?php } else {
                                                                                            ?>
                                                                                            <span><?php echo $trsctnLnDesc; ?></span>
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td> 
                                                                                    <td class="lovtd" style="text-align: right;">
                                                                                        <input type="text" class="form-control rqrdFld jbDetAccRate" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_QTY" name="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_QTY" value="<?php
                                                                                        echo $trsctnLnQty;
                                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_QTY', 'oneScmSalesInvcSmryLinesTable', 'jbDetAccRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllScmSalesInvcSmryTtl();">                                                    
                                                                                    </td>                                               
                                                                                    <td class="lovtd" style="max-width:35px;width:35px;text-align: center;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_UomID" value="<?php echo $trsctnLnUomID; ?>" style="width:100% !important;">  
                                                                                        <div class="" style="width:100% !important;">
                                                                                            <label class="btn btn-primary btn-file" onclick="getOneScmUOMBrkdwnForm(<?php echo $sbmtdScmSalesInvcID; ?>, 2, 'oneScmSalesInvcSmryRow_<?php echo $cntr; ?>');">
                                                                                                <span class="" id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_UomNm1"><?php echo $trsctnLnUomNm; ?></span>
                                                                                            </label>
                                                                                        </div>                                              
                                                                                    </td>
                                                                                    <td class="lovtd">
                                                                                        <input type="text" class="form-control jbDetDbt" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_UnitPrice" name="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_UnitPrice" value="<?php
                                                                                        echo number_format($trsctnLnUnitPrice, 5);
                                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_UnitPrice', 'oneScmSalesInvcSmryLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" <?php echo $edtPriceRdOnly; ?> onchange="calcAllScmSalesInvcSmryTtl();">                                                    
                                                                                    </td>
                                                                                    <td class="lovtd">
                                                                                        <input type="text" class="form-control jbDetCrdt" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LineAmt" name="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LineAmt" value="<?php
                                                                                        echo number_format($trsctnLnAmnt, 2);
                                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LineAmt', 'oneScmSalesInvcSmryLinesTable', 'jbDetCrdt');" style="width:100% !important;text-align: right;" readonly="true" onchange="calcAllScmSalesInvcSmryTtl();">                                                    
                                                                                    </td>  
                                                                                    <td class="lovtd" style="text-align: center;">
                                                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Item Consignments" 
                                                                                                onclick="getScmSalesInvcItems('oneScmSalesInvcSmryRow_<?php echo $cntr; ?>', 'ShowDialog', '<?php echo $scmSalesInvcVchType; ?>', 'true', function () {
                                                                                                            var a = 1;
                                                                                                        });" style="padding:2px !important;"> 
                                                                                            <img src="cmn_images/chcklst3.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                                        </button>
                                                                                    </td>    
                                                                                    <td class="lovtd" style="text-align: center;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxID" value="<?php echo $trsctnLnTxID; ?>" style="width:100% !important;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxNm" value="<?php echo $trsctnLnTxNm; ?>" style="width:100% !important;">  
                                                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?php echo $trsctnLnTxNm; ?>" id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxBtn"
                                                                                                onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Tax Codes', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnTxID; ?>', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxID', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxNm', 'clear', 0, '', function () {
                                                                                                            changeBtnTitleFunc('oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxNm', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxBtn');
                                                                                                        });" style="padding:2px !important;"> 
                                                                                            <img src="cmn_images/tax-icon420x500.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                                        </button>
                                                                                    </td>   
                                                                                    <td class="lovtd" style="text-align: center;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntID" value="<?php echo $trsctnLnDscntID; ?>" style="width:100% !important;"> 
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntNm" value="<?php echo $trsctnLnDscntNm; ?>" style="width:100% !important;"> 
                                                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?php echo $trsctnLnDscntNm; ?>" id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntBtn"  
                                                                                                onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Discount Codes', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnDscntID; ?>', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntID', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntNm', 'clear', 0, '', function () {
                                                                                                            changeBtnTitleFunc('oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntNm', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntBtn');
                                                                                                        });" style="padding:2px !important;"> 
                                                                                            <img src="cmn_images/dscnt_456356.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                                        </button>
                                                                                    </td>  
                                                                                    <td class="lovtd" style="text-align: center;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgID" value="<?php echo $trsctnLnChrgID; ?>" style="width:100% !important;"> 
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgNm" value="<?php echo $trsctnLnChrgNm; ?>" style="width:100% !important;"> 
                                                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?php echo $trsctnLnChrgNm; ?>" id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgBtn" 
                                                                                                onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Extra Charges', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnChrgID; ?>', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgID', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgNm', 'clear', 0, '', function () {
                                                                                                            changeBtnTitleFunc('oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgNm', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgBtn');
                                                                                                        });" style="padding:2px !important;"> 
                                                                                            <img src="cmn_images/truck571d7f45.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                                        </button>
                                                                                    </td>
                                                                                    <td class="lovtd">
                                                                                        <input type="text" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ExtraDesc" name="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ExtraDesc" value="<?php echo $trsctnLnExtrDesc; ?>" style="width:100% !important;text-align: left;" readonly="true">                                                    
                                                                                    </td>
                                                                                    <?php if ($scmSalesInvcAllwDues === '1') { ?>
                                                                                        <td class="lovtd" style="text-align: center;">
                                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Linked Person" id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_PrsnBtn" 
                                                                                                    onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnLnkdPrsnID; ?>', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LnkdPrsnID', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ExtraDesc', 'clear', 0, '', function () {});" style="padding:2px !important;"> 
                                                                                                <img src="cmn_images/person.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                                            </button>
                                                                                        </td>
                                                                                    <?php } ?>
                                                                                    <td class="lovtd" style="text-align: center;">
                                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delScmSalesInvcDetLn('oneScmSalesInvcSmryRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document Line">
                                                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                                <?php
                                                                            }
                                                                        } else {
                                                                            $trsctnLnID = -1;
                                                                            $trsctnLnItmID = -1;
                                                                            $trsctnLnQty = 0;
                                                                            $trsctnLnUnitPrice = 0;
                                                                            $trsctnLnAmnt = 0;
                                                                            $ttlTrsctnEntrdAmnt = $ttlTrsctnEntrdAmnt + $trsctnLnAmnt;
                                                                            $trsctnLnStoreID = -1;
                                                                            $trsctnLnCurID = $fnccurid;
                                                                            $trsctnLnAvlblQty = 0;
                                                                            $trsctnLnSrcLnID = -1;
                                                                            $trsctnLnTxID = -1;
                                                                            $trsctnLnDscntID = -1;
                                                                            $trsctnLnChrgID = -1;
                                                                            $trsctnLnRetrnRsn = "";
                                                                            $trsctnLnCnsgnIDs = ",";
                                                                            $trsctnLnOrgnlPrice = 0;
                                                                            $trsctnLnUomID = -1;
                                                                            $trsctnLnUomNm = "each";
                                                                            $trsctnLnIsDlvrd = "0";
                                                                            $trsctnLnExtrDesc = "";
                                                                            $trsctnLnOthMdlDocID = -1;
                                                                            $trsctnLnOthMdlDocType = "";
                                                                            $trsctnLnLnkdPrsnID = -1;
                                                                            $trsctnLnLnkdPrsnNm = "";
                                                                            $trsctnLnDesc = "";
                                                                            $trsctnLnItmAccnts = "-1,-1,-1,-1,-1,-1";
                                                                            if ($trsctnLnLnkdPrsnID > 0) {
                                                                                $trsctnLnExtrDesc = $trsctnLnLnkdPrsnNm;
                                                                            }
                                                                            $ln_RentedQty = 1;
                                                                            $ln_OthrMdlID = -1;
                                                                            $ln_OthrMdlTyp = "";
                                                                            $trsctnLnTxNm = "View Tax Codes";
                                                                            $trsctnLnDscntNm = "View Discounts";
                                                                            $trsctnLnChrgNm = "View Extra Charges";
                                                                            $cntr += 1;
                                                                            ?>
                                                                            <tr id="oneScmSalesInvcSmryRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneScmSalesInvcSmryLinesTable tr').index(this));">                                    
                                                                                <td class="lovtd"><span><?php echo ($cntr); ?></span></td>                                              
                                                                                <td class="lovtd"  style="">  
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLnID; ?>" style="width:100% !important;">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ItmID" value="<?php echo $trsctnLnItmID; ?>" style="width:100% !important;">  
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_StoreID" value="<?php echo $trsctnLnStoreID; ?>" style="width:100% !important;"> 
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_CnsgnIDs" value="<?php echo $trsctnLnCnsgnIDs; ?>" style="width:100% !important;"> 
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ItmAccnts" value="<?php echo $trsctnLnItmAccnts; ?>" style="width:100% !important;"> 
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LnkdPrsnID" value="<?php echo $trsctnLnLnkdPrsnID; ?>" style="width:100% !important;">   
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_SrcDocLnID" value="<?php echo $trsctnLnSrcLnID; ?>" style="width:100% !important;"> 
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_RentedQty" value="<?php echo $ln_RentedQty; ?>" style="width:100% !important;">     
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_OthrMdlID" value="<?php echo $ln_OthrMdlID; ?>" style="width:100% !important;">     
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_OthrMdlTyp" value="<?php echo $ln_OthrMdlTyp; ?>" style="width:100% !important;">  
                                                                                    <?php
                                                                                    if ($canEdt === true) {
                                                                                        //onfocusout=\"afterSalesInvcItmSlctn('oneScmSalesInvcSmryRow__WWW123WWW');
                                                                                        //onfocusout="afterSalesInvcItmSlctn('oneScmSalesInvcSmryRow_');"
                                                                                        ?>
                                                                                        <div class="input-group" style="width:100% !important;">
                                                                                            <input type="text" class="form-control rqrdFld jbDetAccRate jbDetDesc" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LineDesc" name="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLnDesc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LineDesc', 'oneScmSalesInvcSmryLinesTable', 'jbDetAccRate');" onblur="afterSalesInvcItmSlctn('oneScmSalesInvcSmryRow_<?php echo $cntr; ?>');" onchange="autoCreateSalesLns = 99;">
                                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getScmSalesInvcItems('oneScmSalesInvcSmryRow_<?php echo $cntr; ?>', 'ShowDialog', '<?php echo $scmSalesInvcVchType; ?>', 'false', function () {
                                                                                                        var a = 1;
                                                                                                    });">
                                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                                            </label>
                                                                                        </div>
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo $trsctnLnDesc; ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </td> 
                                                                                <td class="lovtd" style="text-align: right;">
                                                                                    <input type="text" class="form-control rqrdFld jbDetAccRate" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_QTY" name="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_QTY" value="<?php
                                                                                    echo $trsctnLnQty;
                                                                                    ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_QTY', 'oneScmSalesInvcSmryLinesTable', 'jbDetAccRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllScmSalesInvcSmryTtl();">                                                    
                                                                                </td>                                               
                                                                                <td class="lovtd" style="max-width:35px;width:35px;text-align: center;">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_UomID" value="<?php echo $trsctnLnUomID; ?>" style="width:100% !important;">  
                                                                                    <div class="" style="width:100% !important;">
                                                                                        <label class="btn btn-primary btn-file" onclick="getOneScmUOMBrkdwnForm(<?php echo $sbmtdScmSalesInvcID; ?>, 2, 'oneScmSalesInvcSmryRow_<?php echo $cntr; ?>');">
                                                                                            <span class="" id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_UomNm1"><?php echo $trsctnLnUomNm; ?></span>
                                                                                        </label>
                                                                                    </div>                                              
                                                                                </td>
                                                                                <td class="lovtd">
                                                                                    <input type="text" class="form-control jbDetDbt" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_UnitPrice" name="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_UnitPrice" value="<?php
                                                                                    echo number_format($trsctnLnUnitPrice, 5);
                                                                                    ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_UnitPrice', 'oneScmSalesInvcSmryLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" <?php echo $edtPriceRdOnly; ?> onchange="calcAllScmSalesInvcSmryTtl();">                                                    
                                                                                </td>
                                                                                <td class="lovtd">
                                                                                    <input type="text" class="form-control jbDetCrdt" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LineAmt" name="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LineAmt" value="<?php
                                                                                    echo number_format($trsctnLnAmnt, 2);
                                                                                    ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LineAmt', 'oneScmSalesInvcSmryLinesTable', 'jbDetCrdt');" style="width:100% !important;text-align: right;" readonly="true" onchange="calcAllScmSalesInvcSmryTtl();">                                                    
                                                                                </td>  
                                                                                <td class="lovtd" style="text-align: center;">
                                                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Item Consignments" 
                                                                                            onclick="getScmSalesInvcItems('oneScmSalesInvcSmryRow_<?php echo $cntr; ?>', 'ShowDialog', '<?php echo $scmSalesInvcVchType; ?>', 'true', function () {
                                                                                                        var a = 1;
                                                                                                    });" style="padding:2px !important;"> 
                                                                                        <img src="cmn_images/chcklst3.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                                    </button>
                                                                                </td>   
                                                                                <td class="lovtd" style="text-align: center;">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxID" value="<?php echo $trsctnLnTxID; ?>" style="width:100% !important;">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxNm" value="<?php echo $trsctnLnTxNm; ?>" style="width:100% !important;">  
                                                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?php echo $trsctnLnTxNm; ?>" id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxBtn"
                                                                                            onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Tax Codes', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnTxID; ?>', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxID', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxNm', 'clear', 0, '', function () {
                                                                                                        changeBtnTitleFunc('oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxNm', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxBtn');
                                                                                                    });" style="padding:2px !important;"> 
                                                                                        <img src="cmn_images/tax-icon420x500.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                                    </button>
                                                                                </td>   
                                                                                <td class="lovtd" style="text-align: center;">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntID" value="<?php echo $trsctnLnDscntID; ?>" style="width:100% !important;"> 
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntNm" value="<?php echo $trsctnLnDscntNm; ?>" style="width:100% !important;"> 
                                                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?php echo $trsctnLnDscntNm; ?>" id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntBtn"  
                                                                                            onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Discount Codes', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnDscntID; ?>', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntID', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntNm', 'clear', 0, '', function () {
                                                                                                        changeBtnTitleFunc('oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntNm', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntBtn');
                                                                                                    });" style="padding:2px !important;"> 
                                                                                        <img src="cmn_images/dscnt_456356.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                                    </button>
                                                                                </td>  
                                                                                <td class="lovtd" style="text-align: center;">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgID" value="<?php echo $trsctnLnChrgID; ?>" style="width:100% !important;"> 
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgNm" value="<?php echo $trsctnLnChrgNm; ?>" style="width:100% !important;"> 
                                                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?php echo $trsctnLnChrgNm; ?>" id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgBtn" 
                                                                                            onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Extra Charges', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnChrgID; ?>', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgID', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgNm', 'clear', 0, '', function () {
                                                                                                        changeBtnTitleFunc('oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgNm', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgBtn');
                                                                                                    });" style="padding:2px !important;"> 
                                                                                        <img src="cmn_images/truck571d7f45.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                                    </button>
                                                                                </td>
                                                                                <td class="lovtd">
                                                                                    <input type="text" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ExtraDesc" name="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ExtraDesc" value="<?php echo $trsctnLnExtrDesc; ?>" style="width:100% !important;text-align: left;" readonly="true">                                                    
                                                                                </td>
                                                                                <?php if ($scmSalesInvcAllwDues === '1') { ?>
                                                                                    <td class="lovtd" style="text-align: center;">
                                                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Linked Person" id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_PrsnBtn" 
                                                                                                onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnLnkdPrsnID; ?>', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LnkdPrsnID', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ExtraDesc', 'clear', 0, '', function () {});" style="padding:2px !important;"> 
                                                                                            <img src="cmn_images/person.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                                        </button>
                                                                                    </td>
                                                                                <?php } ?>
                                                                                <td class="lovtd" style="text-align: center;">
                                                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delScmSalesInvcDetLn('oneScmSalesInvcSmryRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document Line">
                                                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                    <tfoot>                                                            
                                                                        <tr>
                                                                            <th>&nbsp;</th>
                                                                            <th>&nbsp;</th>
                                                                            <th>&nbsp;</th>
                                                                            <th>TOTALS:</th>
                                                                            <th>&nbsp;</th>
                                                                            <th style="text-align: right;">
                                                                                <?php
                                                                                echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdRIJbSmryAmtTtlBtn\">" . number_format($ttlTrsctnEntrdAmnt,
                                                                                        2, '.', ',') . "</span>";
                                                                                ?>
                                                                                <input type="hidden" id="myCptrdRIJbSmryAmtTtlVal" value="<?php echo $ttlTrsctnEntrdAmnt; ?>">
                                                                            </th>
                                                                            <th style="">&nbsp;</th>                                           
                                                                            <th style="">&nbsp;</th>                                           
                                                                            <th style="">&nbsp;</th>
                                                                            <th style="">&nbsp;</th>
                                                                            <th style="">&nbsp;</th>
                                                                            <th style="">&nbsp;</th>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                            <div class="col-md-2" style="padding:0px 2px 0px 2px !important;">
                                                                <table class="table table-striped table-bordered table-responsive" id="oneScmSalesInvcSmry1Table" cellspacing="0" width="100%" style="width:100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Summary Item</th>
                                                                            <th style="text-align:right;">Amount</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>   
                                                                        <?php
                                                                        $cntr = 0;
                                                                        $resultRw = get_DocSmryLns($sbmtdScmSalesInvcID, $scmSalesInvcVchType);
                                                                        $ttlTrsctnEntrdAmnt = 0;
                                                                        $trnsBrkDwnVType = "VIEW";
                                                                        if ($mkReadOnly == "") {
                                                                            $trnsBrkDwnVType = "EDIT";
                                                                        }
                                                                        $scmRealInvcGrndTtl = 0;
                                                                        while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                            $trsctnLineID = (float) $rowRw[0];
                                                                            $trsctnLineDesc = $rowRw[1];
                                                                            $entrdAmnt = (float) $rowRw[2];
                                                                            $trsctnCodeBhndID = (int) $rowRw[3];
                                                                            $ln_smmry_type = $rowRw[4];
                                                                            $shdAutoCalc = $rowRw[5];
                                                                            $cntr += 1;
                                                                            $style4 = "";
                                                                            $style5 = "";
                                                                            if ($ln_smmry_type == "5Grand Total") {
                                                                                $style4 = "font-weight:bold;";
                                                                                $style5 = "font-weight:bold;color:black";
                                                                                $scmRealInvcGrndTtl = $entrdAmnt;
                                                                            } else if ($ln_smmry_type == "70Change/Balance") {
                                                                                $style4 = "font-weight:bold;";
                                                                                $style5 = "font-weight:bold;color:red";
                                                                            } else if ($ln_smmry_type == "6Total Payments Received" || $ln_smmry_type == "8Deposits") {
                                                                                $style4 = "font-weight:bold;color:green";
                                                                                $style5 = "font-weight:bold;color:green";
                                                                            } else if ($ln_smmry_type == "9Actual_Change/Balance" || $ln_smmry_type == "7Change/Balance") {
                                                                                if ($entrdAmnt >= 0) {
                                                                                    $style4 = "font-weight:bold;color:red";
                                                                                    $style5 = "font-weight:bold;color:red";
                                                                                } else {
                                                                                    $style4 = "font-weight:bold;";
                                                                                    $style5 = "font-weight:bold;color:green";
                                                                                }
                                                                            }
                                                                            ?>
                                                                            <tr id="oneScmSalesInvcSmry1Row_<?php echo $cntr; ?>">                                                 
                                                                                <td class="lovtd"  style="">  
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmry1Row<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmry1Row<?php echo $cntr; ?>_CodeBhndID" value="<?php echo $trsctnCodeBhndID; ?>" style="width:100% !important;">  
                                                                                    <span style="<?php echo $style4; ?>"><?php echo $trsctnLineDesc; ?></span>
                                                                                </td> 
                                                                                <td class="lovtd" style="text-align:right;">
                                                                                    <span style="<?php echo $style5; ?>"><?php
                                                                                        echo number_format($entrdAmnt, 2);
                                                                                        ?></span>
                                                                                </td>  
                                                                            </tr>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                    <tfoot>                                                            
                                                                        <tr>
                                                                            <th>&nbsp;</th>
                                                                            <th>&nbsp;</th>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                                <input id="scmRealInvcGrndTtl" type="hidden" value="<?php echo $scmRealInvcGrndTtl; ?>"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="salesInvcExtraInfo" class="tab-pane fadein" style="border:none !important;padding:0px !important;">
                                                        <div class="row"  style="padding:0px 15px 0px 15px;">
                                                            <div class="col-md-4" style="border:1px solid #ddd; border-radius: 5px;padding: 5px 10px 5px 10px;margin-right: 0px !important;">
                                                                <div class="form-group col-md-12" style="width:100% !important;">
                                                                    <div class="col-md-4">
                                                                        <label style="margin-bottom:0px !important;">Exch. Rate (Multiplier):</label>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <div class="input-group" style="width:100%;">
                                                                            <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                                                                <span class="" style="font-size: 20px !important;" id="scmSalesInvcInvcCur4"><?php echo $scmSalesInvcInvcCur; ?></span>
                                                                                <span class="" style="font-size: 20px !important;" id="scmSalesInvcFuncCur"><?php echo "&nbsp;to " . $fnccurnm; ?></span>
                                                                            </label>
                                                                            <input type="text" class="form-control" aria-label="..." id="scmSalesInvcExRate" name="scmSalesInvcExRate" value="<?php
                                                                            echo number_format(1 / $scmSalesInvcExRate, 4);
                                                                            ?>" style="font-size: 18px !important;font-weight:bold;width:100%;" <?php echo $mkReadOnly; ?>>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-12" style="width:100% !important;">
                                                                    <div class="col-md-4">
                                                                        <label style="margin-bottom:0px !important;">Payment Terms:</label>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <div class="input-group"  style="width:100%;">
                                                                            <textarea class="form-control" rows="3" cols="20" id="scmSalesInvcPayTerms" name="scmSalesInvcPayTerms" <?php echo $mkReadOnly; ?> style="text-align:left !important;"><?php echo $scmSalesInvcPayTerms; ?></textarea>
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('scmSalesInvcPayTerms');" style="max-width:30px;width:30px;">
                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-12" style="width:100% !important;">
                                                                    <div class="col-md-4">
                                                                        <label style="margin-bottom:0px !important;">Linked Document:</label>
                                                                    </div>
                                                                    <div class="col-md-8" style="padding:0px 0px 0px 0px;">
                                                                        <div class="col-md-5" style="padding:0px 1px 0px 15px;">
                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="scmSalesInvcEvntDocTyp" style="width:100% !important;" onchange="lnkdEvntScmSalesInvcChng();">
                                                                                <?php
                                                                                $valslctdArry = array("", "", "", "", "");
                                                                                $srchInsArrys = array("None", "Attendance Register", "Production Process Run",
                                                                                    "Customer File Number", "Project Management");
                                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                    if ($scmSalesInvcEvntDocTyp == $srchInsArrys[$z]) {
                                                                                        $valslctdArry[$z] = "selected";
                                                                                    }
                                                                                    ?>
                                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-7" style="padding:0px 15px 0px 1px;">
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control" aria-label="..." id="scmSalesInvcEvntCtgry" name="scmSalesInvcEvntCtgry" value="<?php echo $scmSalesInvcEvntCtgry; ?>" readonly="true">
                                                                                <label id="scmSalesInvcEvntCtgryLbl" class="btn btn-primary btn-file input-group-addon" onclick="getlnkdEvtAccbRILovCtgry('myLovModal', 'myLovModalTitle', 'myLovModalBody', '', '', '', '', 'radio', true, '', 'scmSalesInvcEvntCtgry', 'scmSalesInvcEvntCtgry', 'clear', 1, '', function () {});">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12" style="padding:2px 15px 0px 15px;">
                                                                            <div class="input-group">
                                                                                <input class="form-control" id="scmSalesInvcEvntRgstr" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Linked Document Number" type = "text" min="0" placeholder="" value="<?php echo $scmSalesInvcEvntRgstr; ?>" readonly="true"/>
                                                                                <input type="hidden" id="scmSalesInvcEvntRgstrID" value="<?php echo $scmSalesInvcEvntRgstrID; ?>">
                                                                                <label id="scmSalesInvcEvntRgstrLbl" class="btn btn-primary btn-file input-group-addon" onclick="getlnkdEvtAccbRILovEvnt('myLovModal', 'myLovModalTitle', 'myLovModalBody', '', 'allOtherInputOrgID', '', '', 'radio', true, '', 'scmSalesInvcEvntRgstrID', 'scmSalesInvcEvntRgstr', 'clear', 1, '', function () {});">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4" style="padding: 0px 1px 0px 1px;">
                                                                <fieldset class="basic_person_fs2" style="min-height:50px !important;padding: 5px 15px 5px 15px !important;margin-left:3px !important;border-radius: 5px;">
                                                                    <div class="form-group col-md-12">
                                                                        <label for="scmSalesInvcGLBatch" class="control-label col-md-4" style="padding:0px 10px 0px 10px !important;">GL Batch Name:</label>
                                                                        <div  class="col-md-8">
                                                                            <div class="input-group">
                                                                                <input class="form-control" id="scmSalesInvcGLBatch" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type = "text" placeholder="" value="<?php echo $scmSalesInvcGLBatch; ?>" readonly="true"/>
                                                                                <input type="hidden" id="scmSalesInvcGLBatchID" value="<?php echo $scmSalesInvcGLBatchID; ?>">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getOneJrnlBatchForm(<?php echo $scmSalesInvcGLBatchID; ?>, 1, 'ShowDialog',<?php echo $sbmtdScmSalesInvcID; ?>, 'Quick Sales Invoice');">
                                                                                    <img src="cmn_images/openfileicon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Open
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-12">
                                                                        <label for="scmSalesInvcRcvblDoc" class="control-label col-md-4" style="padding:0px 10px 0px 10px !important;">Receivable: Doc.</label>
                                                                        <div class="col-md-8">
                                                                            <div class="input-group">
                                                                                <input class="form-control" id="scmSalesInvcRcvblDoc" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type = "text" placeholder="" value="<?php echo $scmSalesInvcRcvblDoc; ?>" readonly="true"/>
                                                                                <input type="hidden" id="scmSalesInvcRcvblDocID" value="<?php echo $scmSalesInvcRcvblDocID; ?>">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getOneAccbRcvblsInvcForm(<?php echo $scmSalesInvcRcvblDocID; ?>, 1, 'ShowDialog', '<?php echo $scmSalesInvcRcvblDocType; ?>',<?php echo $sbmtdScmSalesInvcID; ?>, 'Quick Sales Invoice');">
                                                                                    <img src="cmn_images/openfileicon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Open
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-12">
                                                                        <label for=accbRcvblDebtGlBatchNm" class="control-label col-md-4" style="padding:0px 10px 0px 10px !important;">Bad Debt GL Batch:</label>
                                                                        <div  class="col-md-8">
                                                                            <div class="input-group">
                                                                                <input class="form-control" id="accbRcvblDebtGlBatchNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type = "text" placeholder="" value="<?php echo $accbRcvblDebtGlBatchNm; ?>" readonly="true"/>
                                                                                <input type="hidden" id="accbRcvblDebtGlBatchID" value="<?php echo $accbRcvblDebtGlBatchID; ?>">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getOneJrnlBatchForm(<?php echo $accbRcvblDebtGlBatchID; ?>, 1, 'ShowDialog',<?php echo $sbmtdScmSalesInvcID; ?>, 'Quick Sales Invoice');">
                                                                                    <img src="cmn_images/openfileicon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Open
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>                            
                                                                    <div class="form-group col-md-12">
                                                                        <label for="scmSalesInvcDfltBalsAcnt" class="control-label col-md-4" style="padding:0px 10px 0px 10px !important;">Receivable Account:</label>
                                                                        <div  class="col-md-8">
                                                                            <div class="input-group">
                                                                                <input class="form-control" id="scmSalesInvcDfltBalsAcnt" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Enter GL Account Number" type = "text" min="0" placeholder="" value="<?php echo $scmSalesInvcDfltBalsAcnt; ?>" readonly="true"/>
                                                                                <input type="hidden" id="scmSalesInvcDfltBalsAcntID" value="<?php echo $scmSalesInvcDfltBalsAcntID; ?>">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Liability Accounts', '', '', '', 'radio', true, '', 'scmSalesInvcDfltBalsAcntID', 'scmSalesInvcDfltBalsAcnt', 'clear', 1, '', function () {});">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </fieldset>
                                                            </div>
                                                            <div class="col-md-4" style="border:1px solid #ddd; border-radius: 5px;padding: 5px 10px 5px 10px;margin-right: 0px !important;">     
                                                                <div class="form-group col-md-12">
                                                                    <div class="col-md-4">
                                                                        <label style="margin-bottom:0px !important;">Remark / Narration:</label>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <div class="input-group"  style="width:100%;">
                                                                            <textarea class="form-control" rows="2" cols="20" id="scmSalesInvcDesc" name="scmSalesInvcDesc" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $scmSalesInvcDesc; ?></textarea>
                                                                            <input class="form-control" type="hidden" id="scmSalesInvcDesc1" value="<?php echo $scmSalesInvcDesc; ?>">
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('scmSalesInvcDesc');" style="max-width:30px;width:30px;">
                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                    <div class="col-md-4">
                                                                        <label style="margin-bottom:0px !important;">Source Doc. No.:</label>
                                                                    </div>
                                                                    <div  class="col-md-8">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="srcSalesInvcDocTyp" name="srcSalesInvcDocTyp" value="<?php echo $srcSalesInvcDocTyp; ?>">
                                                                        <input type="hidden" id="srcSalesInvcDocID" value="<?php echo $srcSalesInvcDocID; ?>"><?php
                                                                        $lovNm = "";
                                                                        if ($scmSalesInvcVchType == "Sales Order") {
                                                                            $lovNm = "Approved Pro-Forma Invoices";
                                                                        } else if ($scmSalesInvcVchType == "Sales Invoice") {
                                                                            $lovNm = "Approved Sales Orders";
                                                                        } else if ($scmSalesInvcVchType == "Item Issue-Unbilled") {
                                                                            $lovNm = "Approved Internal Item Requests";
                                                                        } else if ($scmSalesInvcVchType == "Sales Return") {
                                                                            $lovNm = "Approved Sales Invoices/Item Issues";
                                                                        }
                                                                        if (!($scmSalesInvcVchType == "Sales Order" || $scmSalesInvcVchType == "Pro-Forma Invoice")) {
                                                                            ?>
                                                                            <input type="text" class="form-control" aria-label="..." id="srcSalesInvcDocNum" name="srcSalesInvcDocNum" value="<?php echo $srcSalesInvcDocNum; ?>" readonly="true" style="width:100%;">
                                                                            <!--<label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $lovNm; ?>', 'allOtherInputOrgID', 'scmSalesInvcCstmrID', 'scmSalesInvcInvcCur', 'radio', true, '', 'srcSalesInvcDocID', 'srcSalesInvcDocNum', 'clear', 1, '', function () {});" data-toggle="tooltip" title="Existing Document Number">
                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>-->
                                                                        <?php } else { ?>
                                                                            <input type="text" class="form-control" aria-label="..." id="srcSalesInvcDocNum" name="srcSalesInvcDocNum" value="<?php echo $srcSalesInvcDocNum; ?>" readonly="true" style="width:100%;">
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                    <div class="col-md-4">
                                                                        <label style="margin-bottom:0px !important;">Ext. App Doc. Type:</label>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <input type="text" class="form-control" aria-label="..." id="otherModuleDocTyp" name="otherModuleDocTyp" value="<?php echo $otherModuleDocTyp; ?>" readonly="true" style="width:100%;">
                                                                    </div>
                                                                </div> 
                                                                <div class="form-group col-md-12">
                                                                    <div class="col-md-4">
                                                                        <label style="margin-bottom:0px !important;">Ext. App Doc. No.:</label>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <input type="hidden" id="otherModuleDocId" value="<?php echo $otherModuleDocId; ?>">
                                                                        <input type="text" class="form-control" aria-label="..." id="otherModuleDocNum" name="otherModuleDocNum" value="<?php echo $otherModuleDocNum; ?>" readonly="true" style="width:100%;">
                                                                    </div>
                                                                </div> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>                     
                        </div>
                    </fieldset>
                </form>
                <?php
            } else if ($vwtyp == 2) {
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Sales Invoice</span>
				</li>" .
                "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=2');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">All Sales Invoice</span>
				</li> </ul></div>";
                $error = "";
                $searchAll = true;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
                $qShwUnpstdOnly = false;
                if (isset($_POST['qShwUnpstdOnly'])) {
                    $qShwUnpstdOnly = cleanInputData($_POST['qShwUnpstdOnly']) === "true" ? true : false;
                }
                $qShwUnpaidOnly = false;
                if (isset($_POST['qShwUnpaidOnly'])) {
                    $qShwUnpaidOnly = cleanInputData($_POST['qShwUnpaidOnly']) === "true" ? true : false;
                }
                $qShwSelfOnly = true;
                if (isset($_POST['qShwSelfOnly'])) {
                    $qShwSelfOnly = cleanInputData($_POST['qShwSelfOnly']) === "true" ? true : false;
                }
                $qShwMyBranch = true;
                if (isset($_POST['qShwMyBranch'])) {
                    $qShwMyBranch = cleanInputData($_POST['qShwMyBranch']) === "true" ? true : false;
                }
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                $total = get_Total_SalesDoc($srchFor, $srchIn, $orgID, $qShwUnpstdOnly, $qShwUnpaidOnly, $qShwSelfOnly, $qShwMyBranch, $qStrtDte, $qEndDte);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_Basic_SalesDoc($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qShwUnpstdOnly, $qShwUnpaidOnly, $qShwSelfOnly,
                        $qShwMyBranch, $qStrtDte, $qEndDte);
                $cntr = 0;
                $colClassType1 = "col-md-2";
                $colClassType2 = "col-md-5";
                $colClassType3 = "col-md-5";
                ?> 
                <form id='scmSalesInvcForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">ALL SALES DOCUMENTS</legend>
                        <div class="row" style="margin-bottom:0px;">
                            <?php
                            $colClassType1 = "col-md-2";
                            $colClassType2 = "col-md-5";
                            $colClassType3 = "col-md-6";
                            ?>
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="scmSalesInvcSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncScmSalesInvc(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <input id="scmSalesInvcPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <input id="sbmtdScmRtrnSrcDocID" type = "hidden" value="-1">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getScmSalesInvc('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getScmSalesInvc('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="scmSalesInvcSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "", "",
                                            "", "", "", "", "", "");
                                        $srchInsArrys = array("All", "Document Number", "Classification",
                                            "Document Description",
                                            "Customer Name", "Source Doc Number",
                                            "Approval Status", "Created By",
                                            "Currency", "Branch");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="scmSalesInvcDsplySze" style="min-width:70px !important;">                            
                                        <?php
                                        $valslctdArry = array("", "", "", "",
                                            "", "", "", "", "", "");
                                        $dsplySzeArry = array(1, 5, 10, 15, 30,
                                            50, 100, 500, 1000, 1000000);
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
                            <div class="col-lg-4" style="padding:0px 1px 0px 1px !important;">
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text" id="scmSalesInvcStrtDate" name="scmSalesInvcStrtDate" value="<?php
                                        echo substr($qStrtDte, 0, 11);
                                        ?>" placeholder="Start Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div></div>
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text"  id="scmSalesInvcEndDate" name="scmSalesInvcEndDate" value="<?php
                                        echo substr($qEndDte, 0, 11);
                                        ?>" placeholder="End Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>                            
                            </div>
                            <div class="<?php echo $colClassType1; ?>">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a href="javascript:getScmSalesInvc('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getScmSalesInvc('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>   
                        <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important">   
                            <div class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                                <?php if ($canAdd === true) { ?>   
                                    <div class="col-md-5" style="padding:0px 0px 0px 0px !important;">                      
                                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneScmSalesInvcForm(-1, 3, 'ShowDialog', 'Pro-Forma Invoice');" data-toggle="tooltip" data-placement="bottom" title="Add New Pro-Forma Invoice">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            PFI
                                        </button>                 
                                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneScmSalesInvcForm(-1, 3, 'ShowDialog', 'Sales Order');" data-toggle="tooltip" data-placement="bottom" title="Add New Sales Order">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            SO
                                        </button>                 
                                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneScmSalesInvcForm(-1, 3, 'ShowDialog', 'Sales Invoice');" data-toggle="tooltip" data-placement="bottom" title="Add New Sales Invoice">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            SI
                                        </button>                  
                                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneScmSalesInvcForm(-1, 3, 'ShowDialog', 'Internal Item Request');" data-toggle="tooltip" data-placement="bottom" title="Add New Internal Item Request">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            IIR
                                        </button>                
                                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneScmSalesInvcForm(-1, 3, 'ShowDialog', 'Item Issue-Unbilled');" data-toggle="tooltip" data-placement="bottom" title="Add New Item Issue-Unbilled">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            IIU
                                        </button>                    
                                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Approved Sales Invoices/Item Issues', 'allOtherInputOrgID', '', '', 'radio', true, '', 'sbmtdScmRtrnSrcDocID', '', 'clear', 1, '', function () {
                                                    getOneScmSalesInvcForm(-1, 3, 'ShowDialog', 'Sales Return');
                                                });" data-toggle="tooltip" data-placement="bottom" title="Add New Sales Return">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            SR
                                        </button>
                                    </div>  
                                <?php }
                                ?>
                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;">
                                    <div class = "form-check" style = "font-size: 12px !important;">
                                        <label class = "form-check-label" title="Show Approved but Unpaid">
                                            <?php
                                            $shwUnpaidOnlyChkd = "";
                                            if ($qShwUnpaidOnly == true) {
                                                $shwUnpaidOnlyChkd = "checked=\"true\"";
                                            }
                                            ?>
                                            <input type="checkbox" class="form-check-input" onclick="getScmSalesInvc('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="scmSalesInvcShwUnpaidOnly" name="scmSalesInvcShwUnpaidOnly"  <?php echo $shwUnpaidOnlyChkd; ?>>
                                            Show Unpaid
                                        </label>
                                    </div> 
                                </div>
                                <div class = "col-md-2" style = "padding:5px 1px 0px 1px !important;">
                                    <div class = "form-check" style = "font-size: 12px !important;">
                                        <label class = "form-check-label">
                                            <?php
                                            $shwUnpstdOnlyChkd = "";
                                            if ($qShwUnpstdOnly == true) {
                                                $shwUnpstdOnlyChkd = "checked=\"true\"";
                                            }
                                            ?>
                                            <input type="checkbox" class="form-check-input" onclick="getScmSalesInvc('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="scmSalesInvcShwUnpstdOnly" name="scmSalesInvcShwUnpstdOnly"  <?php echo $shwUnpstdOnlyChkd; ?>>
                                            Show Unposted
                                        </label>
                                    </div>                            
                                </div>
                                <?php if ($vwOnlySelf == false) { ?>
                                    <div class = "col-md-2" style = "padding:5px 1px 0px 1px !important;">
                                        <div class = "form-check" style = "font-size: 12px !important;">
                                            <label class = "form-check-label" title="Only Self-Created">
                                                <?php
                                                $shwSelfOnlyChkd = "";
                                                if ($qShwSelfOnly == true) {
                                                    $shwSelfOnlyChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <input type="checkbox" class="form-check-input" onclick="getScmSalesInvc('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="scmSalesInvcShwSelfOnly" name="scmSalesInvcShwSelfOnly"  <?php echo $shwSelfOnlyChkd; ?>>
                                                Self-Created
                                            </label>
                                        </div>                            
                                    </div>
                                <?php } ?>
                                <?php if ($vwOnlyBranch == false) { ?>
                                    <div class = "col-md-1" style = "padding:5px 1px 0px 1px !important;">
                                        <div class = "form-check" style = "font-size: 12px !important;">
                                            <label class = "form-check-label" title="Show Only My Branch">
                                                <?php
                                                $shwMyBranchOnlyChkd = "";
                                                if ($qShwMyBranch == true) {
                                                    $shwMyBranchOnlyChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <input type="checkbox" class="form-check-input" onclick="getScmSalesInvc('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="scmSalesInvcShwMyBranchOnly" name="scmSalesInvcShwMyBranchOnly"  <?php echo $shwMyBranchOnlyChkd; ?>>
                                                My Branch
                                            </label>
                                        </div>                            
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="scmSalesInvcHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th style="max-width:35px;width:35px;">No.</th>
                                            <th style="max-width:30px;width:30px;">...</th>
                                            <th>Invoice Number/Type - Transaction Description</th>
                                            <th>Branch</th>
                                            <th style="text-align:center;max-width:30px;width:30px;">CUR.</th>	
                                            <th style="text-align:right;min-width:100px;width:100px;">Total Invoice Amount</th>
                                            <th style="text-align:right;min-width:100px;width:100px;">Total Amount Paid</th>
                                            <th style="text-align:right;min-width:100px;width:100px;">Amount Outstanding</th>
                                            <th style="max-width:75px;width:75px;">Invoice Status</th>
                                            <?php if ($canDel === true) { ?>
                                                <th style="max-width:30px;width:30px;">...</th>
                                            <?php } ?>
                                            <?php
                                            if ($canVwRcHstry === true) {
                                                ?>
                                                <th style="max-width:30px;width:30px;">...</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            $cntr += 1;
                                            ?>
                                            <tr id="scmSalesInvcHdrsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>    
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View/Edit Invoice" 
                                                            onclick="getOneScmSalesInvcForm(<?php echo $row[0]; ?>, 3, 'ShowDialog', '<?php echo $row[2]; ?>');" style="padding:2px !important;" style="padding:2px !important;">                                                                
                                                                <?php
                                                                if ($canAdd === true) {
                                                                    ?>                                
                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        <?php } else { ?>
                                                            <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        <?php } ?>
                                                    </button>
                                                </td>
                                                <td class="lovtd"><?php echo trim($row[1] . " (" . $row[2] . ") " . $row[9] . " " . $row[3]) . " Date: " . $row[13]; ?></td>
                                                <td class="lovtd"><?php echo $row[11]; ?></td>
                                                <td class="lovtd" style="text-align:center;font-weight: bold;color:black;"><?php echo $row[4]; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                    echo number_format((float) $row[5], 2);
                                                    ?>
                                                </td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                    echo number_format((float) $row[6], 2);
                                                    ?></td>
                                                <?php
                                                $style1 = "color:red;";
                                                if (((float) $row[7]) <= 0) {
                                                    $style1 = "color:green;";
                                                }
                                                ?>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;<?php echo $style1; ?>"><?php
                                                    echo number_format((float) $row[7], 2);
                                                    ?>
                                                </td>
                                                <?php
                                                $style1 = "color:red;";
                                                if ($row[8] == "Approved") {
                                                    $style1 = "color:green;";
                                                } else if ($row[8] == "Cancelled") {
                                                    $style1 = "color:#0d0d0d;";
                                                }
                                                ?>
                                                <td class="lovtd" style="font-weight:bold;<?php echo $style1; ?>"><?php echo $row[8]; ?></td>  
                                                <?php
                                                if ($canDel === true) {
                                                    ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction" onclick="delScmSalesInvc('scmSalesInvcHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                            <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                        <input type="hidden" id="scmSalesInvcHdrsRow<?php echo $cntr; ?>_HdrID" name="scmSalesInvcHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                    </td>
                                                <?php } ?>
                                                <?php
                                                if ($canVwRcHstry === true) {
                                                    ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                        echo urlencode(encrypt1(($row[0] . "|scm.scm_sales_invc_hdr|invc_hdr_id"),
                                                                        $smplTokenWord1));
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
            } else if ($vwtyp == 3) {
                //New Sales Invoice Form
                //var_dump($_POST);
                $sbmtdScmRtrnSrcDocID = isset($_POST['sbmtdScmRtrnSrcDocID']) ? cleanInputData($_POST['sbmtdScmRtrnSrcDocID']) : -1;
                $sbmtdScmSalesInvcID = isset($_POST['sbmtdScmSalesInvcID']) ? cleanInputData($_POST['sbmtdScmSalesInvcID']) : -1;
                $qckPayPrsns_PrsnID = isset($_POST['qckPayPrsns_PrsnID']) ? (float) $_POST['qckPayPrsns_PrsnID'] : -1;
                $qckPayItmSetID = isset($_POST['qckPayItmSetID']) ? (float) $_POST['qckPayItmSetID'] : -1;
                $scmSalesInvcVchType = isset($_POST['scmSalesInvcVchType']) ? cleanInputData($_POST['scmSalesInvcVchType']) : "Pro-Forma Invoice";
                $musAllwDues = isset($_POST['musAllwDues']) ? cleanInputData($_POST['musAllwDues']) : "NO";
                $srcCaller = isset($_POST['srcCaller']) ? cleanInputData($_POST['srcCaller']) : "SALES";

                if (!$canAdd || ($sbmtdScmSalesInvcID > 0 && !$canEdt)) {
                    restricted();
                    exit();
                }
                if ($scmSalesInvcVchType == "Pro-Forma Invoice" && (!$addRecsPFI || ($sbmtdScmSalesInvcID > 0 && !$editRecsPFI))) {
                    restricted();
                    exit();
                }
                if ($scmSalesInvcVchType == "Sales Order" && (!$addRecsSO || ($sbmtdScmSalesInvcID > 0 && !$editRecsSO))) {
                    restricted();
                    exit();
                }
                if ($scmSalesInvcVchType == "Sales Invoice" && (!$addRecsSI || ($sbmtdScmSalesInvcID > 0 && !$editRecsSI))) {
                    restricted();
                    exit();
                }
                if ($scmSalesInvcVchType == "Internal Item Request" && (!$addRecsIIR || ($sbmtdScmSalesInvcID > 0 && !$editRecsIIR))) {
                    restricted();
                    exit();
                }
                if ($scmSalesInvcVchType == "Item Issue-Unbilled" && (!$addRecsIIU || ($sbmtdScmSalesInvcID > 0 && !$editRecsIIU))) {
                    restricted();
                    exit();
                }
                if ($scmSalesInvcVchType == "Sales Return" && (!$addRecsSR || ($sbmtdScmSalesInvcID > 0 && !$editRecsSR))) {
                    restricted();
                    exit();
                }
                $orgnlScmSalesInvcID = $sbmtdScmSalesInvcID;
                $scmSalesInvcDfltTrnsDte = $gnrlTrnsDteDMYHMS;
                $scmSalesInvcCreator = $uName;
                $scmSalesInvcCreatorID = $usrID;
                $scmSalesInvcBrnchID = $brnchLocID;
                $scmSalesInvcBrnchNm = $brnchLoc;
                $gnrtdTrnsNo = "";
                $scmSalesInvcDesc = "";

                $srcSalesInvcDocID = $sbmtdScmRtrnSrcDocID;
                $srcSalesInvcDocTyp = "";
                if ($scmSalesInvcVchType == "Pro-Forma Invoice") {
                    $srcSalesInvcDocTyp = "";
                } elseif ($scmSalesInvcVchType == "Sales Order") {
                    $srcSalesInvcDocTyp = "Pro-Forma Invoice";
                } elseif ($scmSalesInvcVchType == "Sales Invoice") {
                    $srcSalesInvcDocTyp = "Sales Order";
                } elseif ($scmSalesInvcVchType == "Internal Item Request") {
                    $srcSalesInvcDocTyp = "";
                } elseif ($scmSalesInvcVchType == "Item Issue-Unbilled") {
                    $srcSalesInvcDocTyp = "Internal Item Request";
                } elseif ($scmSalesInvcVchType == "Sales Return") {
                    $srcSalesInvcDocTyp = "Sales Invoice";
                }
                $scmSalesInvcClssfctn = "Standard";
                $scmSalesInvcDocTmpltID = -1;
                $srcSalesInvcDocNum = "";

                $scmSalesInvcCstmr = "";
                $scmSalesInvcCstmrID = -1;
                $scmSalesInvcCstmrSite = "";
                $scmSalesInvcCstmrSiteID = -1;
                if ($qckPayPrsns_PrsnID > 0) {
                    $scmSalesInvcCstmrID = getLnkdPrsnCstmrSpplrID($qckPayPrsns_PrsnID);
                    $scmSalesInvcCstmr = getPrsnFullNm($qckPayPrsns_PrsnID) . " (" . getPersonLocID($qckPayPrsns_PrsnID) . ")";
                    $gender = getGnrlRecNm("prs.prsn_names_nos", "person_id", "gender", $qckPayPrsns_PrsnID);
                    $dob = getGnrlRecNm("prs.prsn_names_nos", "person_id", "date_of_birth", $qckPayPrsns_PrsnID);
                    $cstmrLbltyAcntID = get_DfltPyblAcnt($orgID);
                    $cstmrRcvblsAcntID = get_DfltRcvblAcnt($orgID);
                    if ($scmSalesInvcCstmrID <= 0) {
                        createCstmrLnkdPrsn($scmSalesInvcCstmr, $scmSalesInvcCstmr, "Individual", "Customer", $orgID,
                                $cstmrLbltyAcntID, $cstmrRcvblsAcntID, $qckPayPrsns_PrsnID, $gender, $dob, "1", "",
                                "", "", "", "", "", "", "", 0, "", "");
                        $scmSalesInvcCstmrID = getLnkdPrsnCstmrSpplrID($qckPayPrsns_PrsnID);
                        if ($scmSalesInvcCstmrID > 0) {
                            $cstmrSiteCnt = (int) getGnrlRecNm("scm.scm_cstmr_suplr_sites", "cust_supplier_id", "count(cust_sup_site_id)", $scmSalesInvcCstmrID);
                            if ($cstmrSiteCnt <= 0) {
                                createCstmrSiteLnkdPrsn($scmSalesInvcCstmrID, "To be Specified", "", "", "HEAD OFFICE-" . $scmSalesInvcCstmr, "HEAD OFFICE-" . $scmSalesInvcCstmr, "", "", "", -1, -1, "",
                                        "", "", "", "", "", "", "", "", "1", "", $fnccurid);
                            }
                        }
                    }
                    $scmSalesInvcCstmrSiteID = get_DfltCstmrSpplrSiteID($scmSalesInvcCstmrID);
                    $scmSalesInvcCstmrSite = getCstmrSiteNm($scmSalesInvcCstmrSiteID, $scmSalesInvcCstmrID);
                }
                $scmSalesInvcCstmrClsfctn = "Customer";
                $rqStatus = "Not Validated";
                $rqStatusNext = "Approve";
                $rqstatusColor = "red";

                $scmSalesInvcTtlAmnt = 0;
                $scmSalesInvcAppldAmnt = 0;
                $scmSalesInvcPayTerms = "";
                $scmSalesInvcPayMthd = "";
                $scmSalesInvcPayMthdID = -1;
                $scmSalesInvcPaidAmnt = 0;
                $scmSalesInvcGLBatch = "";
                $scmSalesInvcGLBatchID = -1;
                $scmSalesInvcCstmrInvcNum = "";
                $scmSalesInvcDocTmplt = "";
                $scmSalesInvcEvntRgstr = "";
                $scmSalesInvcEvntRgstrID = -1;
                $scmSalesInvcEvntCtgry = "";
                $scmSalesInvcEvntDocTyp = "";
                $scmSalesInvcDfltBalsAcnt = "";
                $scmSalesInvcInvcCurID = $fnccurid;
                $scmSalesInvcInvcCur = $fnccurnm;
                $scmSalesInvcIsPstd = "0";
                $scmSalesInvcAllwDues = "0";
                if ($musAllwDues == "YES") {
                    $scmSalesInvcAllwDues = "1";
                }
                $duesPayCls = "hideNotice";
                if ($scmSalesInvcAllwDues === '1') {
                    $duesPayCls = "";
                }
                $scmSalesInvcAutoBals = "1";
                $scmSalesInvcExRate = 1;
                $otherModuleDocId = -1;
                $otherModuleDocTyp = "";
                $otherModuleDocNum = "";
                $accbRcvblAmtApldElswhr = 0;
                $accbRcvblDebtGlBatchID = -1;
                $sbmtdScmRcvblsInvcID = -1;
                $accbRcvblDebtGlBatchNm = "";
                $scmSalesInvcRcvblDocID = -1;
                $scmSalesInvcRcvblDoc = "";
                $scmSalesInvcRcvblDocType = "";
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                $scmSalesInvcPyItmSetID = -1;
                $scmSalesInvcPyItmSetNm = "";
                if ($qckPayItmSetID > 0) {
                    $scmSalesInvcPyItmSetID = $qckPayItmSetID;
                    $scmSalesInvcPyItmSetNm = getGnrlRecNm("pay.pay_itm_sets_hdr", "hdr_id", "itm_set_name", $qckPayItmSetID);
                }
                $scmSalesInvcPyAmntGvn = 0;
                $scmSalesInvcPyChqNumber = "";
                $scmSalesInvcPySignCode = "";
                $scmSalesInvcAplyAdvnc = "1";
                $scmSalesInvcKeepExcss = "1";
                $scmSalesInvcDfltBalsAcntID = get_DfltCstmrRcvblsCashAcnt($scmSalesInvcCstmrID, $orgID);
                if ($sbmtdScmSalesInvcID > 0) {
                    $result = get_One_SalesInvcDocHdr($sbmtdScmSalesInvcID);
                    if ($row = loc_db_fetch_array($result)) {
                        $scmSalesInvcDfltTrnsDte = $row[1];
                        $scmSalesInvcCreator = $row[3];
                        $scmSalesInvcCreatorID = $row[2];
                        $gnrtdTrnsNo = $row[4];
                        $scmSalesInvcVchType = $row[5];
                        $scmSalesInvcDesc = $row[6];
                        $srcSalesInvcDocID = $row[7];
                        $srcSalesInvcDocTyp = $row[16];
                        $srcSalesInvcDocNum = $row[37];
                        $scmSalesInvcCstmr = $row[9];
                        $scmSalesInvcCstmrID = $row[8];
                        $scmSalesInvcCstmrSite = $row[11];
                        $scmSalesInvcCstmrSiteID = $row[10];
                        $rqStatus = $row[12];
                        $rqStatusNext = $row[13];
                        $rqstatusColor = "red";

                        $scmSalesInvcPayTerms = $row[15];
                        $scmSalesInvcPayMthd = $row[18];
                        $scmSalesInvcPayMthdID = $row[17];

                        $scmSalesInvcTtlAmnt = (float) $row[14];
                        $scmSalesInvcAppldAmnt = (float) $row[34];
                        $scmSalesInvcPaidAmnt = $row[19];
                        if (strpos($scmSalesInvcVchType, "Advance Payment") === FALSE) {
                            $scmSalesInvcAppldAmnt = $scmSalesInvcPaidAmnt;
                        }
                        $scmSalesInvcGLBatch = $row[21];
                        $scmSalesInvcGLBatchID = $row[20];
                        $scmSalesInvcCstmrInvcNum = $row[22];
                        $scmSalesInvcDocTmplt = $row[23];
                        $scmSalesInvcInvcCur = $row[25];
                        $scmSalesInvcInvcCurID = $row[24];
                        $scmSalesInvcEvntRgstr = "";
                        $scmSalesInvcEvntRgstrID = $row[26];
                        $scmSalesInvcEvntCtgry = $row[27];
                        $scmSalesInvcEvntDocTyp = $row[28];
                        $scmSalesInvcDfltBalsAcntID = $row[29];
                        $scmSalesInvcDfltBalsAcnt = $row[30];
                        $scmSalesInvcIsPstd = $row[31];
                        $scmSalesInvcAllwDues = $row[38];
                        $duesPayCls = "hideNotice";
                        if ($scmSalesInvcAllwDues === '1') {
                            $duesPayCls = "";
                        }
                        $scmSalesInvcExRate = (float) $row[40];
                        if ($scmSalesInvcExRate == 0) {
                            $scmSalesInvcExRate = 1;
                        }
                        $otherModuleDocId = $row[32];
                        $otherModuleDocTyp = $row[33];
                        $otherModuleDocNum = $row[41];
                        $sbmtdScmRcvblsInvcID = (float) $row[42];
                        $accbRcvblAmtApldElswhr = $row[34];
                        $accbRcvblDebtGlBatchID = $row[35];
                        $accbRcvblDebtGlBatchNm = $row[36];
                        $scmSalesInvcRcvblDocID = $sbmtdScmRcvblsInvcID;
                        $scmSalesInvcRcvblDoc = $row[43];
                        $scmSalesInvcRcvblDocType = $row[44];
                        $scmSalesInvcBrnchID = (int) $row[45];
                        $scmSalesInvcBrnchNm = $row[46];
                        $scmSalesInvcClssfctn = $row[47];
                        $scmSalesInvcPyItmSetID = (int) $row[48];
                        $scmSalesInvcPyItmSetNm = $row[49];
                        $scmSalesInvcPyAmntGvn = (float) $row[50];
                        $scmSalesInvcPyChqNumber = $row[51];
                        $scmSalesInvcPySignCode = $row[52];
                        $scmSalesInvcAplyAdvnc = $row[53];
                        $scmSalesInvcKeepExcss = $row[54];
                        if ($rqStatus == "Approved") {
                            $rqstatusColor = "green";
                        } else {
                            $rqstatusColor = "red";
                        }
                        if ($rqStatus == "Not Validated") {
                            $mkReadOnly = "";
                            $mkRmrkReadOnly = "";
                        } else {
                            $canEdt = FALSE;
                            $mkReadOnly = "readonly=\"true\"";
                            if ($rqStatus != "Approved") {
                                $mkRmrkReadOnly = "readonly=\"true\"";
                            }
                        }
                        $errMsg1 = reCalcSalesInvcSmmrys($sbmtdScmSalesInvcID, $scmSalesInvcVchType, $scmSalesInvcCstmrID,
                                $scmSalesInvcInvcCurID, $rqStatus);
                        $rslt = get_One_SalesInvcAmounts($sbmtdScmSalesInvcID);
                        if ($rw = loc_db_fetch_array($rslt)) {
                            $scmSalesInvcTtlAmnt = (float) $rw[0];
                            $scmSalesInvcPaidAmnt = $rw[1];
                            if (strpos($scmSalesInvcVchType, "Advance Payment") === FALSE) {
                                $scmSalesInvcAppldAmnt = $scmSalesInvcPaidAmnt;
                            }
                        }
                    }
                } else {
                    $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
                    if ($usrTrnsCode == "") {
                        $usrTrnsCode = "XX";
                    }
                    $dte = date('ymd');
                    $docTypes = array("Pro-Forma Invoice", "Sales Order", "Sales Invoice",
                        "Internal Item Request", "Item Issue-Unbilled", "Sales Return");
                    $docTypPrfxs = array("PFI", "SO", "SI", "IIR", "IIU", "SR");

                    $docTypPrfx = $docTypPrfxs[findArryIdx($docTypes, $scmSalesInvcVchType)];
                    $gnrtdTrnsNo1 = $docTypPrfx . "-" . $usrTrnsCode . "-" . $dte . "-";
                    $gnrtdTrnsNo = $gnrtdTrnsNo1 . str_pad(((getRecCount_LstNum("scm.scm_sales_invc_hdr", "invc_number", "invc_hdr_id",
                                            $gnrtdTrnsNo1 . "%") + 1) . ""), 3, '0', STR_PAD_LEFT);
                    $scmSalesInvcDfltBalsAcnt = getAccntNum($scmSalesInvcDfltBalsAcntID) . "." . getAccntName($scmSalesInvcDfltBalsAcntID);
                    $scmSalesInvcInvcCurID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id",
                                    $scmSalesInvcDfltBalsAcntID);
                    if ($scmSalesInvcInvcCurID > 0) {
                        $scmSalesInvcInvcCur = getPssblValNm($scmSalesInvcInvcCurID);
                    } else {
                        $scmSalesInvcInvcCurID = $fnccurid;
                        $scmSalesInvcInvcCur = $fnccurnm;
                    }
                    IF ($srcSalesInvcDocID > 0) {
                        $result = get_One_SalesInvcDocHdr($srcSalesInvcDocID);
                        if ($row = loc_db_fetch_array($result)) {
                            $srcSalesInvcDocNum = $row[4];
                            $srcSalesInvcDocTyp = $row[5];
                            $scmSalesInvcDesc = $row[6];
                            $scmSalesInvcCstmr = $row[9];
                            $scmSalesInvcCstmrID = $row[8];
                            $scmSalesInvcCstmrSite = $row[11];
                            $scmSalesInvcCstmrSiteID = $row[10];
                            $scmSalesInvcPayTerms = $row[15];
                            $scmSalesInvcPayMthd = $row[18];
                            $scmSalesInvcPayMthdID = $row[17];
                            $scmSalesInvcTtlAmnt = (float) $row[14];
                            $scmSalesInvcInvcCur = $row[25];
                            $scmSalesInvcInvcCurID = $row[24];
                            $scmSalesInvcEvntRgstr = "";
                            $scmSalesInvcEvntRgstrID = $row[26];
                            $scmSalesInvcEvntCtgry = $row[27];
                            $scmSalesInvcEvntDocTyp = $row[28];
                            $scmSalesInvcDfltBalsAcntID = $row[29];
                            $scmSalesInvcDfltBalsAcnt = $row[30];
                            $scmSalesInvcExRate = (float) $row[40];
                            $scmSalesInvcBrnchID = (int) $row[45];
                            $scmSalesInvcBrnchNm = $row[46];
                            $scmSalesInvcClssfctn = $row[47];
                            if ($scmSalesInvcExRate == 0) {
                                $scmSalesInvcExRate = 1;
                            }
                        }
                    }
                    if ($scmSalesInvcDesc == "" && $scmSalesInvcAllwDues === '1') {
                        //$exitErrMsg .= "Please enter Description!<br/>";
                        $scmSalesInvcDesc = "Dues/Bills Quick Pay for " . getCstmrSpplrName1($scmSalesInvcCstmrID) . " on " . $scmSalesInvcDfltTrnsDte;
                    }
                    $sbmtdScmSalesInvcID = createSalesDocHdr($orgID, $gnrtdTrnsNo, $scmSalesInvcDesc, $scmSalesInvcVchType,
                            $scmSalesInvcDfltTrnsDte, $scmSalesInvcPayTerms, $scmSalesInvcCstmrID, $scmSalesInvcCstmrSiteID, $rqStatus,
                            $rqStatusNext, $srcSalesInvcDocID, $scmSalesInvcDfltBalsAcntID, $scmSalesInvcPayMthdID, $scmSalesInvcInvcCurID,
                            $scmSalesInvcExRate, $otherModuleDocId, $otherModuleDocTyp, $scmSalesInvcAutoBals, $scmSalesInvcEvntRgstrID,
                            $scmSalesInvcEvntCtgry, $scmSalesInvcAllwDues, $scmSalesInvcEvntDocTyp, $srcSalesInvcDocTyp,
                            $scmSalesInvcBrnchID, $scmSalesInvcClssfctn, $scmSalesInvcPyItmSetID, $scmSalesInvcPyAmntGvn, $scmSalesInvcPyChqNumber,
                            $scmSalesInvcPySignCode, $scmSalesInvcAplyAdvnc, $scmSalesInvcKeepExcss);
                    if ($sbmtdScmSalesInvcID > 0 && $qckPayPrsns_PrsnID > 0 && $scmSalesInvcCstmrID > 0 && $scmSalesInvcAllwDues === '1') {
                        load_dues_attchd_vals($sbmtdScmSalesInvcID, $selectedStoreID);
                    }
                }

                //CREDIT ANALYSIS INVOICE
                $rtnCustID = -1;
                $rtnCustSupSiteID = -1;
                $rtnTtlPrice = 0.00;
                $rtnSlsClsfctn = "";
                $rtnCount = 0;

                $crdtAnalysisID = isset($_POST['crdtAnalysisID']) ? cleanInputData($_POST['crdtAnalysisID']) : -1; //BEN
                if ($crdtAnalysisID > 0) {
                    $rtnCount = createCreditAnalysisSalesInvoice($crdtAnalysisID, $sbmtdScmSalesInvcID, $scmSalesInvcInvcCurID, $scmSalesInvcVchType, $rqStatus);

                    $strSqlX = "SELECT distinct cust_sup_id, cust_sup_site_id, src_store_id, ttl_prdt_price, 'Consumer Finance' invoice_clsfctn, transaction_no 
                        FROM scm.scm_cnsmr_credit_analys x LEFT OUTER JOIN scm.scm_cstmr_suplr_sites y 
                        ON x.cust_sup_id = y.cust_supplier_id WHERE cnsmr_credit_id = $crdtAnalysisID LIMIT 1";

                    $resultX = executeSQLNoParams($strSqlX);
                    while ($rowX = loc_db_fetch_array($resultX)) {
                        $scmSalesInvcCstmrID = (int) $rowX[0];
                        $scmSalesInvcCstmr = getGnrlRecNm("scm.scm_cstmr_suplr", "cust_sup_id", "cust_sup_name", $scmSalesInvcCstmrID);
                        $rtnCustSupSiteID = (int) $rowX[1];
                        $scmSalesInvcCstmrSiteID = $rtnCustSupSiteID;
                        $scmSalesInvcCstmrSite = getGnrlRecNm("scm.scm_cstmr_suplr_sites", "cust_sup_site_id", "site_name", $rtnCustSupSiteID);
                        $scmSalesInvcBrnchID = (int) $rowX[2];
                        $scmSalesInvcBrnchNm = getGnrlRecNm("scm.scm_cstmr_suplr_sites", "cust_sup_site_id", "site_name", $scmSalesInvcBrnchID);
                        $scmSalesInvcTtlAmnt = (float) $rowX[3];
                        $scmSalesInvcOustndngAmnt = $scmSalesInvcTtlAmnt;
                        $scmSalesInvcClssfctn = $rowX[4];
                        $scmSalesInvcDesc = 'SOURCE: ' . $rowX[5];
                    }

                    //$edtPriceRdOnly = "readonly=\"true\"";
                }
                //$cnt10 = isSalesInvLnkdToCrdtAnlsys($sbmtdScmSalesInvcID);
                //END OF CREDIT ANALYSIS INVOICE

				/**CLINIC/HOSPITAL**/
                $appntmntID = isset($_POST['appntmntID']) ? cleanInputData($_POST['appntmntID']) : -1; //BEN
                if($appntmntID > 0){
                     $rtnCount = createHospAppntmntSalesInvoice($appntmntID, $sbmtdScmSalesInvcID, $scmSalesInvcInvcCurID, $scmSalesInvcVchType, $rqStatus);

                    $vstID = (int)getGnrlRecNm("hosp.appntmnt", "appntmnt_id", "vst_id", $appntmntID);
                    $ptntPrsnID = (int)getGnrlRecNm("hosp.visit", "vst_id", "prsn_id", $vstID);

                    $cstmrNm = "";
                    $scmSalesInvcCstmrID = -1;
                    $resultYY = executeSQLNoParams("SELECT cust_sup_id, cust_sup_name
                            FROM scm.scm_cstmr_suplr a WHERE lnkd_prsn_id > 0 AND lnkd_prsn_id = $ptntPrsnID AND org_id = $orgID LIMIT 1");
                    $rowYYCnt = loc_db_num_fields($resultYY);
                    if($rowYYCnt > 0){
                            while ($rowYY = loc_db_fetch_array($resultYY)) {
                                    $scmSalesInvcCstmrID = (int)$rowYY[0];
                                    $cstmrNm = $rowYY[1];
                            }
                    }
                    
                    //GET CUSTOMER SITE ID
                    $strSql2 = "SELECT cust_sup_site_id FROM scm.scm_cstmr_suplr_sites WHERE cust_supplier_id = $scmSalesInvcCstmrID LIMIT 1";
                    
                    $rtnCustSupSiteID = -1;
                    $result2 = executeSQLNoParams($strSql2);
                    while ($row2 = loc_db_fetch_array($result2)) {
                        $rtnCustSupSiteID = (int) $row2[0];
                    }
                    
                    $scmSalesInvcCstmr = getGnrlRecNm("scm.scm_cstmr_suplr", "cust_sup_id", "cust_sup_name", $scmSalesInvcCstmrID);
                    $scmSalesInvcCstmrSiteID = $rtnCustSupSiteID;
                    $scmSalesInvcCstmrSite = getGnrlRecNm("scm.scm_cstmr_suplr_sites", "cust_sup_site_id", "site_name", $rtnCustSupSiteID);
                    $scmSalesInvcTtlAmnt = calcAppntmntDataItmsTtlAmt($appntmntID, $sbmtdScmSalesInvcID);
                    $scmSalesInvcOustndngAmnt = $scmSalesInvcTtlAmnt;
                    
                    $strSqlX = "SELECT distinct src_store_id, 'Standard' invoice_clsfctn, appntmnt_no 
                        FROM hosp.appntmnt WHERE appntmnt_id = $appntmntID LIMIT 1";
                    $resultX = executeSQLNoParams($strSqlX);
                    while ($rowX = loc_db_fetch_array($resultX)) {
                        $scmSalesInvcBrnchID = (int) $rowX[0];
                        $scmSalesInvcBrnchNm = getGnrlRecNm("scm.scm_cstmr_suplr_sites", "cust_sup_site_id", "site_name", $scmSalesInvcBrnchID);
                        $scmSalesInvcClssfctn = $rowX[1];
                        $scmSalesInvcDesc = 'SOURCE: '.$rowX[2];
                    }  
                }
                
                /**CLINIC/HOSPITAL**/
                $cnt10 = 0;
                $jsFxn = "closeScmSalesInvForm('myFormsModalLg', 'myFormsModalTitleLg', 'myFormsModalBodyLg');";
                if($crdtAnalysisID > 0){
                    $cnt10 = isSalesInvLnkdToCrdtAnlsys($sbmtdScmSalesInvcID);
                } else if($appntmntID > 0){
                    $cnt10 = isSalesInvLnkdToAppntmnt($sbmtdScmSalesInvcID);
                    $jsFxn = "closeScmSalesInvForm('myFormsModalLgYH', 'myFormsModalLgYHTitle', 'myFormsModalLgYHBody');";
                }    
                /**CLINIC/HOSPITAL**/
				
                $scmSalesInvcOustndngAmnt = $scmSalesInvcTtlAmnt - $scmSalesInvcPaidAmnt;
                $scmSalesInvcOustndngStyle = "color:red;";
                $scmSalesInvcPaidStyle = "color:black;";
                if ($scmSalesInvcOustndngAmnt <= 0) {
                    $scmSalesInvcOustndngStyle = "color:green;";
                }
                if ($scmSalesInvcPaidAmnt > 0 && $scmSalesInvcOustndngAmnt <= 0) {
                    $scmSalesInvcPaidStyle = "color:green;";
                } else if ($scmSalesInvcPaidAmnt > 0) {
                    $scmSalesInvcPaidStyle = "color:brown;";
                }

                $reportName = getEnbldPssblValDesc("Sales Invoice", getLovID("Document Custom Print Process Names"));
                $reportTitle = str_replace("Pro-Forma Invoice", "Payment Voucher", $scmSalesInvcVchType);
                if ($scmSalesInvcVchType == "Sales Invoice" || $scmSalesInvcVchType == "Sales Return" || $scmSalesInvcVchType == "Sales Order" || $scmSalesInvcVchType == "Pro-Forma Invoice") {
                    if ($scmSalesInvcAllwDues == "1") {
                        $reportName = getEnbldPssblValDesc("Sales Invoice - Dues",
                                getLovID("Document Custom Print Process Names"));
                        $reportTitle = "Dues/Bills Payment Voucher";
                    }
                } else if ($scmSalesInvcVchType == "Item Issue-Unbilled") {
                    $reportName = getEnbldPssblValDesc("Item Issues", getLovID("Document Custom Print Process Names"));
                    $reportTitle = $scmSalesInvcVchType;
                } else {
                    $reportName = getEnbldPssblValDesc("Internal Item Request", getLovID("Document Custom Print Process Names"));
                    $reportTitle = $scmSalesInvcVchType;
                }
                $rptID = getRptID($reportName);
                $prmID1 = getParamIDUseSQLRep("{:invoice_id}", $rptID);
                $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                $trnsID = $sbmtdScmSalesInvcID;
                $paramRepsNVals = $prmID1 . "~" . $trnsID . "|" . $prmID2 . "~" . $reportTitle . "|-130~" . $reportTitle . "|-190~PDF";
                $paramStr = urlencode($paramRepsNVals);


                $reportName1 = "Sales Invoice-POS";
                $reportTitle1 = "Payment Receipt";
                $rptID1 = getRptID($reportName1);
                $prmID11 = getParamIDUseSQLRep("{:invoice_id}", $rptID1);
                $prmID12 = getParamIDUseSQLRep("{:documentTitle}", $rptID1);
                $paramRepsNVals1 = $prmID11 . "~" . $trnsID . "|" . $prmID12 . "~" . $reportTitle1 . "|-130~" . $reportTitle1 . "|-190~PDF";
                $paramStr1 = urlencode($paramRepsNVals1);

                $reportTitle2 = "Load Sales Dues Attached Values";
                $reportName2 = "Load Sales Dues Attached Values";
                $rptID2 = getRptID($reportName2);
                $prmID212 = getParamIDUseSQLRep("{:invoice_id}", $rptID2);
                $prmID213 = getParamIDUseSQLRep("{:p_storeid}", $rptID2);
                $paramRepsNVals2 = $prmID212 . "~" . $sbmtdScmSalesInvcID . "|" . $prmID213 . "~" . $selectedStoreID . "|-130~" . $reportTitle2 . "|-190~HTML";
                $paramStr2 = urlencode($paramRepsNVals2);
                ?>
                <form class="form-horizontal" id="oneScmSalesInvcEDTForm">
                    <fieldset class="basic_person_fs2" style="min-height:50px !important;">
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Document No./Name:</label>
                                    </div>
                                    <div class="col-md-3" style="padding:0px 1px 0px 15px;">
                                        <input type="text" class="form-control" aria-label="..." id="sbmtdScmSalesInvcID" name="sbmtdScmSalesInvcID" value="<?php echo $sbmtdScmSalesInvcID; ?>" readonly="true">
                                    </div>
                                    <div class="col-md-5" style="padding:0px 15px 0px 1px;">
                                        <input type="text" class="form-control" aria-label="..." id="scmSalesInvcDocNum" name="scmSalesInvcDocNum" value="<?php echo $gnrtdTrnsNo; ?>" readonly="true">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Document Date:</label>
                                    </div>
                                    <div class="col-md-8 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 15px 0px 15px;">
                                        <input class="form-control" size="16" type="text" id="scmSalesInvcDfltTrnsDte" name="scmSalesInvcDfltTrnsDte" value="<?php echo $scmSalesInvcDfltTrnsDte; ?>" placeholder="Transactions Date" readonly="true">
                                        <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Document Classification:</label>
                                    </div>
                                    <div class="col-md-3" style="padding:0px 1px 0px 15px;">
                                        <input type="text" class="form-control" aria-label="..." id="scmSalesInvcVchType" name="scmSalesInvcVchType" value="<?php echo $scmSalesInvcVchType; ?>" readonly="true">
                                    </div>
                                    <div  class="col-md-5" style="padding:0px 15px 0px 1px;">
                                        <div class="input-group">
                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="scmSalesInvcClssfctn" name="scmSalesInvcClssfctn" value="<?php echo $scmSalesInvcClssfctn; ?>" readonly="true">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sales Invoice Classifications', '', '', '', 'radio', true, '', 'scmSalesInvcClssfctn', '', 'clear', 1, '', function () {
                                                        populateSalesDesc();
                                                    });" data-toggle="tooltip" title="Invoice Classification">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="scmSalesInvcPayMthd" class="control-label col-md-4">Payment Method:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="scmSalesInvcPayMthd" name="scmSalesInvcPayMthd" value="<?php echo $scmSalesInvcPayMthd; ?>" readonly="true">
                                            <input type="hidden" id="scmSalesInvcPayMthdID" value="<?php echo $scmSalesInvcPayMthdID; ?>">
                                            <input type="hidden" id="scmSalesInvcMthdType" value="Customer Payments">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Payment Methods', 'allOtherInputOrgID', 'scmSalesInvcMthdType', '', 'radio', true, '', 'scmSalesInvcPayMthdID', 'scmSalesInvcPayMthd', 'clear', 1, '');" data-toggle="tooltip" title="Existing Payment Method">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label for="srcSalesInvcDocNum" class="control-label col-md-4">Source Doc. No.:</label>
                                    <div  class="col-md-8">
                                        <input type="hidden" class="form-control" aria-label="..." id="srcSalesInvcDocTyp" name="srcSalesInvcDocTyp" value="<?php echo $srcSalesInvcDocTyp; ?>">
                                        <input type="hidden" id="srcSalesInvcDocID" value="<?php echo $srcSalesInvcDocID; ?>"><?php
                                        $lovNm = "";
                                        if ($scmSalesInvcVchType == "Sales Order") {
                                            $lovNm = "Approved Pro-Forma Invoices";
                                        } else if ($scmSalesInvcVchType == "Sales Invoice") {
                                            $lovNm = "Approved Sales Orders";
                                        } else if ($scmSalesInvcVchType == "Item Issue-Unbilled") {
                                            $lovNm = "Approved Internal Item Requests";
                                        } else if ($scmSalesInvcVchType == "Sales Return") {
                                            $lovNm = "Approved Sales Invoices/Item Issues";
                                        }
                                        ?>
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="srcSalesInvcDocNum" name="srcSalesInvcDocNum" value="<?php echo $srcSalesInvcDocNum; ?>" readonly="true" style="width:100%;">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $lovNm; ?>', 'allOtherInputOrgID', 'scmSalesInvcCstmrID', 'scmSalesInvcInvcCur', 'radio', true, '', 'srcSalesInvcDocID', 'srcSalesInvcDocNum', 'clear', 1, '', function () {});" data-toggle="tooltip" title="Existing Document Number">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="scmSalesInvcCstmr" class="control-label col-md-4">Customer:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="scmSalesInvcCstmr" name="scmSalesInvcCstmr" value="<?php echo $scmSalesInvcCstmr; ?>" readonly="true">
                                            <input type="hidden" id="scmSalesInvcCstmrID" value="<?php echo $scmSalesInvcCstmrID; ?>">
                                            <input type="hidden" id="scmSalesInvcCstmrClsfctn" value="<?php echo $scmSalesInvcCstmrClsfctn; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getCstmrSpplrForm(-1, 'Create/Edit Supplier', 'ShowDialog', function () {}, 'scmSalesInvcCstmrID');" data-toggle="tooltip" title="Create/Edit Supplier">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </label>
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'allOtherInputOrgID', '', 'scmSalesInvcCstmrClsfctn', 'radio', true, '', 'scmSalesInvcCstmrID', 'scmSalesInvcCstmr', 'clear', 1, '', function () {
                                                        getInvRcvblsAcntInfo();
                                                    });" data-toggle="tooltip" title="Existing Client/Vendor">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>  
                                <div class="form-group">
                                    <label for="scmSalesInvcCstmrSite" class="control-label col-md-4">Site:</label>
                                    <div  class="col-md-5" style="padding:0px 1px 0px 15px;">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="scmSalesInvcCstmrSite" name="scmSalesInvcCstmrSite" value="<?php echo $scmSalesInvcCstmrSite; ?>" readonly="true">
                                            <input class="form-control" type="hidden" id="scmSalesInvcCstmrSiteID" value="<?php echo $scmSalesInvcCstmrSiteID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Customer/Supplier Sites', 'scmSalesInvcCstmrID', '', '', 'radio', true, '', 'scmSalesInvcCstmrSiteID', 'scmSalesInvcCstmrSite', 'clear', 1, '');" data-toggle="tooltip" title="">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div  class="col-md-3" style="padding:5px 15px 0px 1px;display:inline-table !important;vertical-align: middle !important;float: left !important;">
                                        <div class="form-check" style="font-size: 12px !important;float: left !important;">
                                            <label class="form-check-label" title="Check to Enable Dues/Bills Payment Template">
                                                <?php
                                                $scmSalesInvcAllwDuesChkd = "";
                                                if ($scmSalesInvcAllwDues == "1") {
                                                    $scmSalesInvcAllwDuesChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <input type="checkbox" class="form-check-input" id="scmSalesInvcAllwDues" name="scmSalesInvcAllwDues"  <?php echo $scmSalesInvcAllwDuesChkd; ?> onclick="shwHideDuesPayDivs1('CHECK');">
                                            </label>
                                        </div>
                                        <div class="" style="margin-top:2px !important; float: left !important;font-weight:bold;cursor: pointer;" onclick="shwHideDuesPayDivs1();">
                                            <span>&nbsp;Dues / Bills</span>
                                        </div>
                                    </div>
                                </div>                                                               
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Remark / Narration:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group"  style="width:100%;">
                                            <textarea class="form-control" rows="3" cols="20" id="scmSalesInvcDesc" name="scmSalesInvcDesc" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $scmSalesInvcDesc; ?></textarea>
                                            <input class="form-control" type="hidden" id="scmSalesInvcDesc1" value="<?php echo $scmSalesInvcDesc; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('scmSalesInvcDesc');" style="max-width:30px;width:30px;">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Ext. App Doc.:</label>
                                    </div>
                                    <div class="col-md-5" style="padding:0px 1px 0px 15px;">
                                        <input type="text" class="form-control" aria-label="..." id="otherModuleDocTyp" name="otherModuleDocTyp" value="<?php echo $otherModuleDocTyp; ?>" readonly="true" style="width:100%;">
                                    </div>
                                    <div  class="col-md-3" style="padding:0px 15px 0px 1px;">
                                        <input type="hidden" id="otherModuleDocId" value="<?php echo $otherModuleDocId; ?>">
                                        <input type="text" class="form-control" aria-label="..." id="otherModuleDocNum" name="otherModuleDocNum" value="<?php echo $otherModuleDocNum; ?>" readonly="true" style="width:100%;">
                                    </div>
                                </div>  
                            </div>
                            <div class = "col-md-4">   
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Invoice Total:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <label class="btn btn-primary btn-file input-group-addon active" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $scmSalesInvcInvcCur; ?>', 'scmSalesInvcInvcCur', '', 'clear', 0, '', function () {
                                                        $('#scmSalesInvcInvcCur1').html($('#scmSalesInvcInvcCur').val());
                                                        $('#scmSalesInvcInvcCur2').html($('#scmSalesInvcInvcCur').val());
                                                        $('#scmSalesInvcInvcCur3').html($('#scmSalesInvcInvcCur').val());
                                                        $('#scmSalesInvcInvcCur4').html($('#scmSalesInvcInvcCur').val());
                                                        $('#scmSalesInvcInvcCur5').html($('#scmSalesInvcInvcCur').val());
                                                        $('#scmSalesInvcInvcCur10').html($('#scmSalesInvcInvcCur').val());
                                                    });">
                                                <span class="" style="font-size: 20px !important;" id="scmSalesInvcInvcCur1"><?php echo $scmSalesInvcInvcCur; ?></span>
                                            </label>
                                            <input type="hidden" id="scmSalesInvcInvcCur" value="<?php echo $scmSalesInvcInvcCur; ?>"> 
                                            <input type="hidden" id="scmSalesInvcInvcCurID" value="<?php echo $scmSalesInvcInvcCurID; ?>"> 
                                            <input class="form-control" type="text" id="scmSalesInvcTtlAmnt" value="<?php
                                            echo number_format($scmSalesInvcTtlAmnt, 2);
                                            ?>" style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('scmSalesInvcTtlAmnt');" readonly="true"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Exch. Rate (Multiplier):</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                                <span class="" style="font-size: 20px !important;<?php echo $scmSalesInvcPaidStyle; ?>" id="scmSalesInvcInvcCur4"><?php echo $scmSalesInvcInvcCur; ?></span>
                                                <span class="" style="font-size: 20px !important;<?php echo $scmSalesInvcPaidStyle; ?>" id="scmSalesInvcFuncCur"><?php echo "&nbsp;to " . $fnccurnm; ?></span>
                                            </label>
                                            <input type="text" class="form-control" aria-label="..." id="scmSalesInvcExRate" name="scmSalesInvcExRate" value="<?php
                                            echo number_format(1 / $scmSalesInvcExRate, 4);
                                            ?>" style="font-size: 18px !important;font-weight:bold;width:100%;" <?php echo $mkReadOnly; ?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Total Amount Received:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                                <span class="" style="font-size: 20px !important;<?php echo $scmSalesInvcPaidStyle; ?>" id="scmSalesInvcInvcCur2"><?php echo $scmSalesInvcInvcCur; ?></span>
                                            </label>
                                            <input class="form-control" type="text" id="scmSalesInvcPaidAmnt" value="<?php
                                            echo number_format($scmSalesInvcPaidAmnt, 2);
                                            ?>" style="font-weight:bold;width:100%;font-size:18px !important;<?php echo $scmSalesInvcPaidStyle; ?>" onchange="fmtAsNumber('scmSalesInvcPaidAmnt');" readonly="true"/>
                                            <label data-toggle="tooltip" title="History of Payments" class="btn btn-primary btn-file input-group-addon" onclick="getOneAccbPymntsHstryForm(<?php echo $sbmtdScmRcvblsInvcID; ?>, 103, 'ReloadDialog',<?php echo $sbmtdScmSalesInvcID; ?>, 'Sales Invoice', 'Customer Payments');">
                                                <img src="cmn_images/openfileicon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Outstanding Balance:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                                <span class="" style="font-size: 20px !important;<?php echo $scmSalesInvcOustndngStyle; ?>" id="scmSalesInvcInvcCur3"><?php echo $scmSalesInvcInvcCur; ?></span>
                                            </label>
                                            <input class="form-control" type="text" id="scmSalesInvcOustndngAmnt" value="<?php
                                            echo number_format($scmSalesInvcOustndngAmnt, 2);
                                            ?>" style="font-weight:bold;width:100%;font-size:18px !important;<?php echo $scmSalesInvcOustndngStyle; ?>" onchange="fmtAsNumber('scmSalesInvcOustndngAmnt');"  readonly="true"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Status:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="hidden" id="scmSalesInvcApprvlStatus" value="<?php echo $rqStatus; ?>">                              
                                        <button type="button" class="btn btn-default" style="height:30px;width:100% !important;" id="myScmSalesInvcStatusBtn">
                                            <span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;height:30px;">
                                                <?php
                                                echo $rqStatus . ($scmSalesInvcIsPstd == "1" ? " [Posted]" : " [Not Posted]");
                                                ?>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="basic_person_fs <?php echo $duesPayCls; ?>" style="padding-top:3px !important;" id="scmSalesInvcAllwDuesDiv">
                        <div class="row" style="margin-top:5px;padding: 0px 10px 0px 10px !important;">
                            <div  class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="scmSalesInvcPyItmSetNm" class="control-label col-md-3">Item Set:</label>
                                    <div  class="col-md-5" style="padding: 0px 1px 0px 13px !important;">
                                        <div class="input-group">
                                            <input class="form-control" id="scmSalesInvcPyItmSetNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Item Sets for Payments" type = "text" value="<?php echo $scmSalesInvcPyItmSetNm; ?>" readonly="true"/>
                                            <input type="hidden" id="scmSalesInvcPyItmSetID" value="<?php echo $scmSalesInvcPyItmSetID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Item Sets for Payments(Enabled)', 'allOtherInputOrgID', '', '', 'radio', true, '', 'scmSalesInvcPyItmSetID', 'scmSalesInvcPyItmSetNm', 'clear', 1, '', function () {});">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div  class="col-md-4" style="padding:5px 17px 0px 5px;display:inline-table !important;vertical-align: middle !important;float: left !important;" title="Use existing Advance Payment Balances to settle  for Persons that have Advance Balances!">
                                        <div class="form-check" style="font-size: 12px !important;float: left !important;">
                                            <label class="form-check-label">
                                                <?php
                                                $scmSalesInvcAplyAdvncChkd = "";
                                                if ($scmSalesInvcAplyAdvnc == "1") {
                                                    $scmSalesInvcAplyAdvncChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <input type="checkbox" class="form-check-input" id="scmSalesInvcAplyAdvnc" name="scmSalesInvcAplyAdvnc"  <?php echo $scmSalesInvcAplyAdvncChkd; ?> onclick="">
                                            </label>
                                        </div>
                                        <div class="" style="margin-top:2px !important; float: left !important;font-weight:bold;cursor: pointer;color:red;" onclick="tickUntickChckBx('scmSalesInvcAplyAdvnc');">
                                            <span>&nbsp;Apply Advance</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div  class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <div class="col-md-4" style="padding: 5px 15px 0px 13px !important;">
                                        <label style="margin-bottom:0px !important;">Amount To Pay:</label>
                                    </div>
                                    <div class="col-md-5" style="padding: 0px 1px 0px 11px !important;">
                                        <div class="input-group">
                                            <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                                <span class="" style="font-size: 20px !important;" id="scmSalesInvcInvcCur10"><?php echo $scmSalesInvcInvcCur; ?></span>
                                            </label>
                                            <input class="form-control" type="text" id="scmSalesInvcPyAmntGvn" value="<?php
                                            echo number_format($scmSalesInvcPyAmntGvn, 2);
                                            ?>"  style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('scmSalesInvcPyAmntGvn');"/>
                                        </div>
                                    </div>
                                    <div  class="col-md-3" style="padding:5px 20px 0px 5px;display:inline-table !important;vertical-align: middle !important;float: left !important;" title="Keep and Apply Excess amounts as Advancve Payments">
                                        <div class="form-check" style="font-size: 12px !important;float: left !important;">
                                            <label class="form-check-label">
                                                <?php
                                                $scmSalesInvcKeepExcssChkd = "";
                                                if ($scmSalesInvcKeepExcss == "1") {
                                                    $scmSalesInvcKeepExcssChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <input type="checkbox" class="form-check-input" id="scmSalesInvcKeepExcss" name="scmSalesInvcKeepExcss"  <?php echo $scmSalesInvcKeepExcssChkd; ?> onclick="">
                                            </label>
                                        </div>
                                        <div class="" style="margin-top:2px !important; float: left !important;font-weight:bold;cursor: pointer;color:red;" onclick="tickUntickChckBx('scmSalesInvcKeepExcss');">
                                            <span>&nbsp;Keep All</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div  class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <div class="col-md-4" style="padding: 5px 15px 0px 8px !important;">
                                        <label style="margin-bottom:0px !important;">Cheque Number:</label>
                                    </div>
                                    <div class="col-md-5" style="padding: 0px 2px 0px 8px !important;">
                                        <input type="text" class="form-control" aria-label="..." data-toggle="tooltip" title="Cheque/Card Number" id="scmSalesInvcPyChqNumber" name="scmSalesInvcPyChqNumber" value="<?php echo $scmSalesInvcPyChqNumber; ?>" <?php echo $mkReadOnly; ?>>
                                    </div>
                                    <div class="col-md-3" style="padding: 0px 22px 0px 2px !important;">
                                        <input class="form-control" type="text" data-toggle="tooltip" title="Sign Code (CCV)" id="scmSalesInvcPySignCode" value="<?php echo $scmSalesInvcPySignCode; ?>" style="width:100%;" <?php echo $mkReadOnly; ?>/>
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </fieldset>
                    <fieldset class="">
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs" style="margin-top:1px !important;">
                                    <li class="active"><a data-toggle="tabajxsalesinvc" data-rhodata="" href="#salesInvcDetLines" id="salesInvcDetLinestab">Invoice Lines</a></li>
                                    <li class=""><a data-toggle="tabajxsalesinvc" data-rhodata="" href="#salesInvcExtraInfo" id="salesInvcExtraInfotab">Extra Information</a></li>
                                </ul>  
                                <div class="custDiv" style="padding:0px !important;min-height: 30px !important;"> 
                                    <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php
                                                $edtPriceRdOnly = "readonly=\"true\"";
                                                if ($canEdtPrice === true) {
                                                    $edtPriceRdOnly = "";
                                                }
                                                $nwRowHtml33 = "<tr id=\"oneScmSalesInvcSmryRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#oneScmSalesInvcSmryLinesTable tr').index(this));\">"
                                                        . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>                          
                                                           <td class=\"lovtd\"  style=\"\">  
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_ItmID\" value=\"-1\" style=\"width:100% !important;\">  
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_StoreID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_CnsgnIDs\" value=\"\" style=\"width:100% !important;\"> 
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_ItmAccnts\" value=\"\" style=\"width:100% !important;\"> 
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_LnkdPrsnID\" value=\"-1\" style=\"width:100% !important;\">  
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_SrcDocLnID\" value=\"-1\" style=\"width:100% !important;\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_RentedQty\" value=\"1\" style=\"width:100% !important;\">     
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_OthrMdlID\" value=\"-1\" style=\"width:100% !important;\">     
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_OthrMdlTyp\" value=\"-1\" style=\"width:100% !important;\">
                                                                        <div class=\"input-group\" style=\"width:100% !important;\">
                                                                         <input type=\"text\" class=\"form-control rqrdFld jbDetAccRate jbDetDesc\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_LineDesc\" name=\"oneScmSalesInvcSmryRow_WWW123WWW_LineDesc\" value=\"\" style=\"width:100% !important;\" onkeypress=\"gnrlFldKeyPress(event, 'oneScmSalesInvcSmryRow_WWW123WWW_LineDesc', 'oneScmSalesInvcSmryLinesTable', 'jbDetAccRate');\" onblur=\"afterSalesInvcItmSlctn('oneScmSalesInvcSmryRow__WWW123WWW');\" onchange=\"autoCreateSalesLns=99;\">
                                                                         <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getScmSalesInvcItems('oneScmSalesInvcSmryRow__WWW123WWW', 'ShowDialog', '" . $scmSalesInvcVchType . "', 'false', function () {var a=1;});\">
                                                                             <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                         </label>
                                                                        </div>
                                                                    </td> 
                                                                    <td class=\"lovtd\" style=\"text-align: right;\">
                                                                        <input type=\"text\" class=\"form-control rqrdFld jbDetAccRate\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_QTY\" name=\"oneScmSalesInvcSmryRow_WWW123WWW_QTY\" value=\"0\" onkeypress=\"gnrlFldKeyPress(event, 'oneScmSalesInvcSmryRow_WWW123WWW_QTY', 'oneScmSalesInvcSmryLinesTable', 'jbDetAccRate');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllScmSalesInvcSmryTtl();\">                                                    
                                                                    </td>                                               
                                                                    <td class=\"lovtd\" style=\"max-width:35px;width:35px;text-align: center;\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_UomID\" value=\"-1\" style=\"width:100% !important;\">  
                                                                        <div class=\"\" style=\"width:100% !important;\">
                                                                            <label class=\"btn btn-primary btn-file\" onclick=\"getOneScmUOMBrkdwnForm(-1, 2, 'oneScmSalesInvcSmryRow__WWW123WWW');\">
                                                                                <span class=\"\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_UomNm1\">Each</span>
                                                                            </label>
                                                                        </div>                                              
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                        <input type=\"text\" class=\"form-control jbDetDbt\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_UnitPrice\" name=\"oneScmSalesInvcSmryRow_WWW123WWW_UnitPrice\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'oneScmSalesInvcSmryRow_WWW123WWW_UnitPrice', 'oneScmSalesInvcSmryLinesTable', 'jbDetDbt');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllScmSalesInvcSmryTtl();\" " . $edtPriceRdOnly . ">                                                    
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                        <input type=\"text\" class=\"form-control jbDetCrdt\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_LineAmt\" name=\"oneScmSalesInvcSmryRow_WWW123WWW_LineAmt\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'oneScmSalesInvcSmryRow_WWW123WWW_LineAmt', 'oneScmSalesInvcSmryLinesTable', 'jbDetCrdt');\" style=\"width:100% !important;text-align: right;\" readonly=\"true\" onchange=\"calcAllScmSalesInvcSmryTtl();\">                                                    
                                                                    </td>  
                                                                    <td class=\"lovtd\" style=\"text-align: center;\">
                                                                        <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View Item Consignments\" 
                                                                                onclick=\"getScmSalesInvcItems('oneScmSalesInvcSmryRow__WWW123WWW', 'ShowDialog', '" . $scmSalesInvcVchType . "', 'true', function () {var a=1;});\" style=\"padding:2px !important;\"> 
                                                                            <img src=\"cmn_images/chcklst3.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                                        </button>
                                                                    </td>    
                                                                    <td class=\"lovtd\" style=\"text-align: center;\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_TaxID\" value=\"-1\" style=\"width:100% !important;\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_TaxNm\" value=\"View Tax Codes\" style=\"width:100% !important;\">  
                                                                        <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View Tax Codes\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_TaxBtn\"
                                                                                onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Tax Codes', 'allOtherInputOrgID', '', '', 'radio', true, '-1', 'oneScmSalesInvcSmryRow_WWW123WWW_TaxID', 'oneScmSalesInvcSmryRow_WWW123WWW_TaxNm', 'clear', 0, '', function () {
                                                                                                                    changeBtnTitleFunc('oneScmSalesInvcSmryRow_WWW123WWW_TaxNm', 'oneScmSalesInvcSmryRow_WWW123WWW_TaxBtn');
                                                                                                                });\" style=\"padding:2px !important;\"> 
                                                                            <img src=\"cmn_images/tax-icon420x500.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                                        </button>
                                                                    </td>   
                                                                    <td class=\"lovtd\" style=\"text-align: center;\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_DscntID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_DscntNm\" value=\"View Discounts\" style=\"width:100% !important;\"> 
                                                                        <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View Discounts\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_DscntBtn\"  
                                                                                onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Discount Codes', 'allOtherInputOrgID', '', '', 'radio', true, '-1', 'oneScmSalesInvcSmryRow_WWW123WWW_DscntID', 'oneScmSalesInvcSmryRow_WWW123WWW_DscntNm', 'clear', 0, '', function () {
                                                                                                                    changeBtnTitleFunc('oneScmSalesInvcSmryRow_WWW123WWW_DscntNm', 'oneScmSalesInvcSmryRow_WWW123WWW_DscntBtn');
                                                                                                                });\" style=\"padding:2px !important;\"> 
                                                                            <img src=\"cmn_images/dscnt_456356.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                                        </button>
                                                                    </td>  
                                                                    <td class=\"lovtd\" style=\"text-align: center;\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_ChrgID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_ChrgNm\" value=\"View Extra Charges\" style=\"width:100% !important;\"> 
                                                                        <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View Extra Charges\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_ChrgBtn\" 
                                                                                onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Extra Charges', 'allOtherInputOrgID', '', '', 'radio', true, '-1', 'oneScmSalesInvcSmryRow_WWW123WWW_ChrgID', 'oneScmSalesInvcSmryRow_WWW123WWW_ChrgNm', 'clear', 0, '', function () {
                                                                                                                    changeBtnTitleFunc('oneScmSalesInvcSmryRow_WWW123WWW_ChrgNm', 'oneScmSalesInvcSmryRow_WWW123WWW_ChrgBtn');
                                                                                                                });\" style=\"padding:2px !important;\"> 
                                                                            <img src=\"cmn_images/truck571d7f45.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                                        </button>
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_ExtraDesc\" name=\"oneScmSalesInvcSmryRow_WWW123WWW_ExtraDesc\" value=\"\" style=\"width:100% !important;text-align: left;\" readonly=\"true\">                                                    
                                                                    </td>";
                                                if ($scmSalesInvcAllwDues === '1') {
                                                    $nwRowHtml33 .= "<td class=\"lovtd\" style=\"text-align: center;\">
                                                                                        <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Linked Person\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_PrsnBtn\" 
                                                                                                onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', 'allOtherInputOrgID', '', '', 'radio', true, '-1', 'oneScmSalesInvcSmryRow_WWW123WWW_LnkdPrsnID', 'oneScmSalesInvcSmryRow_WWW123WWW_ExtraDesc', 'clear', 0, '', function () {});\" style=\"padding:2px !important;\"> 
                                                                                            <img src=\"cmn_images/person.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">                                                            
                                                                                        </button>
                                                                                    </td>";
                                                }
                                                $nwRowHtml33 .= "<td class=\"lovtd\" style=\"text-align: center;\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delScmSalesInvcDetLn('oneScmSalesInvcSmryRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Trns. Line\">
                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                            </button>
                                                        </td>
                                                    </tr>";
                                                $nwRowHtml33 = urlencode($nwRowHtml33);
                                                ?> 
                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                    <div class="col-md-4" style="padding:0px 0px 0px 0px !important;float:left;">
                                                        <?php if ($canEdt) { ?>
                                                            <input type="hidden" id="nwSalesDocLineHtm" value="<?php echo $nwRowHtml33; ?>">
                                                            <button id="addNwScmSalesInvcSmryBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="insertNewScmSalesInvcRows('oneScmSalesInvcSmryLinesTable', 0, '<?php echo $nwRowHtml33; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Transaction Line">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>                                 
                                                        <?php } ?>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneScmSalesInvcDocsForm(<?php echo $sbmtdScmSalesInvcID; ?>, 20);" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                                            <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button> 
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneScmSalesInvcForm(<?php echo $sbmtdScmSalesInvcID; ?>, 3, 'ReloadDialog', 'Sales Invoice', '<?php echo $musAllwDues; ?>', '<?php echo $srcCaller; ?>');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;"  onclick="printEmailFullInvc(<?php echo $sbmtdScmSalesInvcID; ?>);" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title = "Print Invoice">
                                                            <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Print
                                                        </button>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;"  onclick="printPOSRcpt(<?php echo $sbmtdScmSalesInvcID; ?>);" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title = "POS Receipt">
                                                            <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            POS
                                                        </button>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;"  onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title = "Print Invoice from Reports">
                                                            <img src="cmn_images/print.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;"  onclick="getSilentRptsRnSts(<?php echo $rptID1; ?>, -1, '<?php echo $paramStr1; ?>');" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title = "POS Receipt from Reports">
                                                            <img src="cmn_images/printer-icon.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                        <button type="button" class="btn btn-default" style="height:30px;margin-bottom: 1px;">
                                                            <span style="font-weight:bold;color:black;">Total: </span>
                                                            <span style="color:red;font-weight: bold;" id="myCptrdSalesInvcValsTtlBtn"><?php echo $scmSalesInvcInvcCur; ?> 
                                                                <?php
                                                                echo number_format($scmSalesInvcTtlAmnt, 2);
                                                                ?>
                                                            </span>
                                                            <input type="hidden" id="myCptrdSalesInvcValsTtlVal" value="<?php echo $scmSalesInvcTtlAmnt; ?>">
                                                        </button>
                                                    </div>
                                                    <div class="col-md-4" style="padding:0px 10px 0px 10px !important;"> 
                                                        <div class="form-group">
                                                            <label for="scmSalesInvcBrnchNm" class="control-label col-md-4" style="padding:5px 10px 0px 13px !important;">Branch:</label>
                                                            <div  class="col-md-8" style="padding:0px 23px 0px 11px !important;">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" aria-label="..." id="scmSalesInvcBrnchNm" name="scmSalesInvcBrnchNm" value="<?php echo $scmSalesInvcBrnchNm; ?>" readonly="true">
                                                                    <input class="form-control" type="hidden" id="scmSalesInvcBrnchID" value="<?php echo $scmSalesInvcBrnchID; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sites/Locations', 'allOtherInputOrgID', '', '', 'radio', true, '', 'scmSalesInvcBrnchID', 'scmSalesInvcBrnchNm', 'clear', 0, '');" data-toggle="tooltip" title="">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                    <div class="col-md-4" style="padding:0px 0px 0px 0px !important;">
                                                        <div class="" style="padding:0px 0px 0px 0px;float:right !important;"> 
                                                            <?php
                                                            if ($rqStatus == "Not Validated") {
                                                                ?>
                                                                <?php if ($canEdt) { ?>
                                                                    <?php if ($cnt10 > 0) { ?>
                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="closeScmSalesInvForm();"><img src="cmn_images/back_2.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Return&nbsp;</button>
                                                                    <?php } ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveScmSalesInvcForm('<?php echo $fnccurnm; ?>', 0, 'NORMAL', '<?php echo $musAllwDues; ?>', '<?php echo $srcCaller; ?>');" data-toggle="tooltip" data-placement="bottom" title="Save Transaction">
                                                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; height:17px; width:auto; position: relative; vertical-align: middle;">&nbsp;Save
                                                                    </button>    
                                                                <?php }if ($scmSalesInvcAllwDues == "1") { ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="runMassPyValuesForm(<?php echo $rptID2; ?>, -1, '<?php echo $paramStr2; ?>', 'NORMAL', '<?php echo $srcCaller; ?>');" data-toggle="tooltip" data-placement="bottom" title="Load Attached Values">
                                                                        <img src="cmn_images/98.png" style="left: 0.5%; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                                    </button>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveScmSalesInvcForm('<?php echo $fnccurnm; ?>', 7, 'NORMAL', '<?php echo $musAllwDues; ?>', '<?php echo $srcCaller; ?>');" data-toggle="tooltip" data-placement="bottom" title="Save and Automatically Load Attached Values">
                                                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save/Load</button>    
                                                                <?php } if ($canRvwApprvDocs) { ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveScmSalesInvcForm('<?php echo $fnccurnm; ?>', 2, 'NORMAL', '<?php echo $musAllwDues; ?>', '<?php echo $srcCaller; ?>');" data-toggle="tooltip" data-placement="bottom" title="Finalize Document">
                                                                        <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Finalize
                                                                    </button>
                                                                    <?php
                                                                }
                                                                if ($canPayDocs === true && $scmSalesInvcCstmrID > 0 && ($scmSalesInvcVchType == "Sales Invoice" || $scmSalesInvcVchType == "Sales Order" || $scmSalesInvcVchType == "Pro-Forma Invoice")) {
                                                                    ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAccbPayInvcForm(<?php echo $sbmtdScmRcvblsInvcID; ?>, 'Customer Payments', 'ShowDialog', -1, <?php echo $sbmtdScmSalesInvcID; ?>, '<?php echo $scmSalesInvcVchType; ?>', 'XX_UNDEFINED_XX', 'NORMAL', 'scmSalesInvcCstmrID', 'scmSalesInvcInvcCur', '<?php echo $musAllwDues; ?>', '<?php echo $srcCaller; ?>');" data-toggle="tooltip" data-placement="bottom" title="Take Deposit">
                                                                        <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Take Deposit
                                                                    </button>
                                                                    <?php
                                                                }
                                                            } else if ($rqStatus == "Approved") {
                                                                if ($canPayDocs === true && $scmSalesInvcOustndngAmnt > 0 && $scmSalesInvcVchType == "Sales Invoice") {
                                                                    ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAccbPayInvcForm(<?php echo $sbmtdScmRcvblsInvcID; ?>, 'Customer Payments', 'ShowDialog', -1, <?php echo $sbmtdScmSalesInvcID; ?>, '<?php echo $scmSalesInvcVchType; ?>', 'XX_UNDEFINED_XX', 'NORMAL', 'XX1_RHO_UNDEFINED_1', 'XX1_RHO_UNDEFINED_1', '<?php echo $musAllwDues; ?>', '<?php echo $srcCaller; ?>');" data-toggle="tooltip" data-placement="bottom" title="Pay Document">
                                                                        <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Pay Invoice
                                                                    </button>
                                                                <?php } ?>
                                                                <?php
                                                                if ($cancelDocs === true) {
                                                                    ?>
                                                                    <button id="fnlzeRvrslScmSalesInvcBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveScmSalesInvcRvrslForm('<?php echo $fnccurnm; ?>', 1, 'NORMAL', '<?php echo $musAllwDues; ?>', '<?php echo $srcCaller; ?>');"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Cancel Document&nbsp;</button>  
                                                                    <!--<button id="fnlzeBadDebtScmSalesInvcBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveScmSalesInvcRvrslForm('<?php echo $fnccurnm; ?>', 2);"  data-toggle="tooltip" data-placement="bottom" title="Declare as Bad Debt">
                                                                    <img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Bad Debt&nbsp;</button>-->                                                                   
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>                    
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneScmSalesInvcLnsTblSctn"> 
                                    <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                        <div id="salesInvcDetLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                            <div class="row" style="padding:0px 13px 0px 13px !important;">
                                                <div class="col-md-10" style="padding:0px 2px 0px 2px !important;">
                                                    <table class="table table-striped table-bordered table-responsive" id="oneScmSalesInvcSmryLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th style="max-width:30px;width:30px;">No.</th>
                                                                <th style="min-width:250px;">Item Code/Description</th>
                                                                <th style="max-width:50px;width:50px;text-align: right;">QTY</th>
                                                                <th style="max-width:55px;text-align: center;">UOM.</th>
                                                                <th style="max-width:170px;width:140px;text-align: right;">Unit Selling Price</th>
                                                                <th style="max-width:170px;width:120px;text-align: right;">Total Amount</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">CS</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">TX</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">DC</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">EX</th>
                                                                <th style="min-width:150px;">Extra Description</th>
                                                                <?php if ($scmSalesInvcAllwDues === '1') { ?>
                                                                    <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                                <?php } ?>
                                                                <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>   
                                                            <?php
                                                            $cntr = 0;
                                                            $resultRw = get_SalesInvcDocDet($sbmtdScmSalesInvcID);
                                                            $ttlTrsctnEntrdAmnt = 0;
                                                            $trnsBrkDwnVType = "VIEW";
                                                            if ($mkReadOnly == "") {
                                                                $trnsBrkDwnVType = "EDIT";
                                                            }
                                                            $ttlRows = loc_db_num_rows($resultRw);
                                                            if ($ttlRows > 0) {
                                                                while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                    $trsctnLnID = (float) $rowRw[0];
                                                                    $trsctnLnItmID = (float) $rowRw[1];
                                                                    $trsctnLnQty = (float) $rowRw[2];
                                                                    $trsctnLnUnitPrice = (float) $rowRw[3];
                                                                    $trsctnLnAmnt = (float) $rowRw[4];
                                                                    $ttlTrsctnEntrdAmnt = $ttlTrsctnEntrdAmnt + $trsctnLnAmnt;
                                                                    $trsctnLnStoreID = (float) $rowRw[5];
                                                                    $trsctnLnCurID = $rowRw[6];
                                                                    $trsctnLnAvlblQty = (float) $rowRw[7];
                                                                    $trsctnLnSrcLnID = (float) $rowRw[8];
                                                                    $trsctnLnTxID = (float) $rowRw[9];
                                                                    $trsctnLnDscntID = (float) $rowRw[10];
                                                                    $trsctnLnChrgID = (float) $rowRw[11];
                                                                    $trsctnLnRetrnRsn = $rowRw[12];
                                                                    $trsctnLnCnsgnIDs = $rowRw[13];
                                                                    $trsctnLnOrgnlPrice = (float) $rowRw[14];
                                                                    $trsctnLnUomID = (float) $rowRw[15];
                                                                    $trsctnLnUomNm = $rowRw[18];
                                                                    $trsctnLnIsDlvrd = $rowRw[19];
                                                                    $trsctnLnExtrDesc = $rowRw[20];
                                                                    $trsctnLnOthMdlDocID = (float) $rowRw[21];
                                                                    $trsctnLnOthMdlDocType = $rowRw[22];
                                                                    $trsctnLnLnkdPrsnID = (float) $rowRw[23];
                                                                    $trsctnLnLnkdPrsnNm = $rowRw[24];
                                                                    $trsctnLnDesc = $rowRw[25];
                                                                    $trsctnLnItmAccnts = $rowRw[27];
                                                                    $ln_RentedQty = (float) $rowRw[29];
                                                                    $ln_OthrMdlID = (float) $rowRw[30];
                                                                    $ln_OthrMdlTyp = $rowRw[31];
                                                                    $trsctnLnTxNm = ($trsctnLnTxID > 0) ? $rowRw[32] : "View Tax Codes";
                                                                    $trsctnLnDscntNm = ($trsctnLnDscntID > 0) ? $rowRw[33] : "View Discounts";
                                                                    $trsctnLnChrgNm = ($trsctnLnChrgID > 0) ? $rowRw[34] : "View Extra Charges";
                                                                    if ($ln_RentedQty == 0) {
                                                                        $ln_RentedQty = 1;
                                                                    }
                                                                    if ($trsctnLnLnkdPrsnID > 0) {
                                                                        $trsctnLnExtrDesc = $trsctnLnLnkdPrsnNm;
                                                                    }
                                                                    $cntr += 1;
                                                                    ?>
                                                                    <tr id="oneScmSalesInvcSmryRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneScmSalesInvcSmryLinesTable tr').index(this));">                                    
                                                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td>                                              
                                                                        <td class="lovtd"  style="">  
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLnID; ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ItmID" value="<?php echo $trsctnLnItmID; ?>" style="width:100% !important;">  
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_StoreID" value="<?php echo $trsctnLnStoreID; ?>" style="width:100% !important;"> 
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_CnsgnIDs" value="<?php echo $trsctnLnCnsgnIDs; ?>" style="width:100% !important;"> 
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ItmAccnts" value="<?php echo $trsctnLnItmAccnts; ?>" style="width:100% !important;"> 
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LnkdPrsnID" value="<?php echo $trsctnLnLnkdPrsnID; ?>" style="width:100% !important;">   
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_SrcDocLnID" value="<?php echo $trsctnLnSrcLnID; ?>" style="width:100% !important;"> 
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_RentedQty" value="<?php echo $ln_RentedQty; ?>" style="width:100% !important;">     
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_OthrMdlID" value="<?php echo $ln_OthrMdlID; ?>" style="width:100% !important;">     
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_OthrMdlTyp" value="<?php echo $ln_OthrMdlTyp; ?>" style="width:100% !important;"> 
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                                ?>
                                                                                <div class="input-group" style="width:100% !important;">
                                                                                    <input type="text" class="form-control rqrdFld jbDetAccRate jbDetDesc" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LineDesc" name="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLnDesc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LineDesc', 'oneScmSalesInvcSmryLinesTable', 'jbDetAccRate');" onblur="afterSalesInvcItmSlctn('oneScmSalesInvcSmryRow_<?php echo $cntr; ?>');" onchange="autoCreateSalesLns = 99;">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getScmSalesInvcItems('oneScmSalesInvcSmryRow_<?php echo $cntr; ?>', 'ShowDialog', '<?php echo $scmSalesInvcVchType; ?>', 'false', function () {
                                                                                                var a = 1;
                                                                                            });">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            <?php } else {
                                                                                ?>
                                                                                <span><?php echo $trsctnLnDesc; ?></span>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </td> 
                                                                        <td class="lovtd" style="text-align: right;">
                                                                            <input type="text" class="form-control rqrdFld jbDetAccRate" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_QTY" name="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_QTY" value="<?php
                                                                            echo $trsctnLnQty;
                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_QTY', 'oneScmSalesInvcSmryLinesTable', 'jbDetAccRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllScmSalesInvcSmryTtl();">                                                    
                                                                        </td>                                               
                                                                        <td class="lovtd" style="max-width:35px;width:35px;text-align: center;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_UomID" value="<?php echo $trsctnLnUomID; ?>" style="width:100% !important;">  
                                                                            <div class="" style="width:100% !important;">
                                                                                <label class="btn btn-primary btn-file" onclick="getOneScmUOMBrkdwnForm(<?php echo $sbmtdScmSalesInvcID; ?>, 2, 'oneScmSalesInvcSmryRow_<?php echo $cntr; ?>');">
                                                                                    <span class="" id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_UomNm1"><?php echo $trsctnLnUomNm; ?></span>
                                                                                </label>
                                                                            </div>                                              
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control jbDetDbt" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_UnitPrice" name="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_UnitPrice" value="<?php
                                                                            echo number_format($trsctnLnUnitPrice, 5);
                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_UnitPrice', 'oneScmSalesInvcSmryLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" <?php echo $edtPriceRdOnly; ?> onchange="calcAllScmSalesInvcSmryTtl();">                                                    
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control jbDetCrdt" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LineAmt" name="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LineAmt" value="<?php
                                                                            echo number_format($trsctnLnAmnt, 2);
                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LineAmt', 'oneScmSalesInvcSmryLinesTable', 'jbDetCrdt');" style="width:100% !important;text-align: right;" readonly="true" onchange="calcAllScmSalesInvcSmryTtl();">                                                    
                                                                        </td>  
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Item Consignments" 
                                                                                    onclick="getScmSalesInvcItems('oneScmSalesInvcSmryRow_<?php echo $cntr; ?>', 'ShowDialog', '<?php echo $scmSalesInvcVchType; ?>', 'true', function () {
                                                                                                var a = 1;
                                                                                            });" style="padding:2px !important;"> 
                                                                                <img src="cmn_images/chcklst3.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                            </button>
                                                                        </td>   
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxID" value="<?php echo $trsctnLnTxID; ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxNm" value="<?php echo $trsctnLnTxNm; ?>" style="width:100% !important;">  
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?php echo $trsctnLnTxNm; ?>" id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxBtn"
                                                                                    onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Tax Codes', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnTxID; ?>', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxID', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxNm', 'clear', 0, '', function () {
                                                                                                changeBtnTitleFunc('oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxNm', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxBtn');
                                                                                            });" style="padding:2px !important;"> 
                                                                                <img src="cmn_images/tax-icon420x500.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                            </button>
                                                                        </td>   
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntID" value="<?php echo $trsctnLnDscntID; ?>" style="width:100% !important;"> 
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntNm" value="<?php echo $trsctnLnDscntNm; ?>" style="width:100% !important;"> 
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?php echo $trsctnLnDscntNm; ?>" id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntBtn"  
                                                                                    onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Discount Codes', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnDscntID; ?>', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntID', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntNm', 'clear', 0, '', function () {
                                                                                                changeBtnTitleFunc('oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntNm', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntBtn');
                                                                                            });" style="padding:2px !important;"> 
                                                                                <img src="cmn_images/dscnt_456356.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                            </button>
                                                                        </td>  
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgID" value="<?php echo $trsctnLnChrgID; ?>" style="width:100% !important;"> 
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgNm" value="<?php echo $trsctnLnChrgNm; ?>" style="width:100% !important;"> 
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?php echo $trsctnLnChrgNm; ?>" id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgBtn" 
                                                                                    onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Extra Charges', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnChrgID; ?>', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgID', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgNm', 'clear', 0, '', function () {
                                                                                                changeBtnTitleFunc('oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgNm', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgBtn');
                                                                                            });" style="padding:2px !important;"> 
                                                                                <img src="cmn_images/truck571d7f45.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                            </button>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ExtraDesc" name="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ExtraDesc" value="<?php echo $trsctnLnExtrDesc; ?>" style="width:100% !important;text-align: left;" readonly="true">                                                    
                                                                        </td>
                                                                        <?php if ($scmSalesInvcAllwDues === '1') { ?>
                                                                            <td class="lovtd" style="text-align: center;">
                                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Linked Person" id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_PrsnBtn" 
                                                                                        onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnLnkdPrsnID; ?>', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LnkdPrsnID', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ExtraDesc', 'clear', 0, '', function () {});" style="padding:2px !important;"> 
                                                                                    <img src="cmn_images/person.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                                </button>
                                                                            </td>
                                                                        <?php } ?>
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delScmSalesInvcDetLn('oneScmSalesInvcSmryRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document Line">
                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            } else {
                                                                $trsctnLnID = -1;
                                                                $trsctnLnItmID = -1;
                                                                $trsctnLnQty = 0;
                                                                $trsctnLnUnitPrice = 0;
                                                                $trsctnLnAmnt = 0;
                                                                $ttlTrsctnEntrdAmnt = $ttlTrsctnEntrdAmnt + $trsctnLnAmnt;
                                                                $trsctnLnStoreID = -1;
                                                                $trsctnLnCurID = $fnccurid;
                                                                $trsctnLnAvlblQty = 0;
                                                                $trsctnLnSrcLnID = -1;
                                                                $trsctnLnTxID = -1;
                                                                $trsctnLnDscntID = -1;
                                                                $trsctnLnChrgID = -1;
                                                                $trsctnLnRetrnRsn = "";
                                                                $trsctnLnCnsgnIDs = ",";
                                                                $trsctnLnOrgnlPrice = 0;
                                                                $trsctnLnUomID = -1;
                                                                $trsctnLnUomNm = "each";
                                                                $trsctnLnIsDlvrd = "0";
                                                                $trsctnLnExtrDesc = "";
                                                                $trsctnLnOthMdlDocID = -1;
                                                                $trsctnLnOthMdlDocType = "";
                                                                $trsctnLnLnkdPrsnID = -1;
                                                                $trsctnLnLnkdPrsnNm = "";
                                                                $trsctnLnDesc = "";
                                                                $trsctnLnItmAccnts = "-1,-1,-1,-1,-1,-1";
                                                                if ($trsctnLnLnkdPrsnID > 0) {
                                                                    $trsctnLnExtrDesc = $trsctnLnLnkdPrsnNm;
                                                                }
                                                                $ln_RentedQty = 1;
                                                                $ln_OthrMdlID = -1;
                                                                $ln_OthrMdlTyp = "";
                                                                $trsctnLnTxNm = "View Tax Codes";
                                                                $trsctnLnDscntNm = "View Discounts";
                                                                $trsctnLnChrgNm = "View Extra Charges";
                                                                $cntr += 1;
                                                                ?>
                                                                <tr id="oneScmSalesInvcSmryRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneScmSalesInvcSmryLinesTable tr').index(this));">                                    
                                                                    <td class="lovtd"><span><?php echo ($cntr); ?></span></td>                                              
                                                                    <td class="lovtd"  style="">  
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLnID; ?>" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ItmID" value="<?php echo $trsctnLnItmID; ?>" style="width:100% !important;">  
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_StoreID" value="<?php echo $trsctnLnStoreID; ?>" style="width:100% !important;"> 
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_CnsgnIDs" value="<?php echo $trsctnLnCnsgnIDs; ?>" style="width:100% !important;"> 
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ItmAccnts" value="<?php echo $trsctnLnItmAccnts; ?>" style="width:100% !important;"> 
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LnkdPrsnID" value="<?php echo $trsctnLnLnkdPrsnID; ?>" style="width:100% !important;">   
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_SrcDocLnID" value="<?php echo $trsctnLnSrcLnID; ?>" style="width:100% !important;">  
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_RentedQty" value="<?php echo $ln_RentedQty; ?>" style="width:100% !important;">     
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_OthrMdlID" value="<?php echo $ln_OthrMdlID; ?>" style="width:100% !important;">     
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_OthrMdlTyp" value="<?php echo $ln_OthrMdlTyp; ?>" style="width:100% !important;"> 
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                            //onfocusout=\"afterSalesInvcItmSlctn('oneScmSalesInvcSmryRow__WWW123WWW');
                                                                            //onfocusout="afterSalesInvcItmSlctn('oneScmSalesInvcSmryRow_');"
                                                                            ?>
                                                                            <div class="input-group" style="width:100% !important;">
                                                                                <input type="text" class="form-control rqrdFld jbDetAccRate jbDetDesc" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LineDesc" name="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLnDesc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LineDesc', 'oneScmSalesInvcSmryLinesTable', 'jbDetAccRate');" onblur="afterSalesInvcItmSlctn('oneScmSalesInvcSmryRow_<?php echo $cntr; ?>');" onchange="autoCreateSalesLns = 99;">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getScmSalesInvcItems('oneScmSalesInvcSmryRow_<?php echo $cntr; ?>', 'ShowDialog', '<?php echo $scmSalesInvcVchType; ?>', 'false', function () {
                                                                                            var a = 1;
                                                                                        });">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        <?php } else {
                                                                            ?>
                                                                            <span><?php echo $trsctnLnDesc; ?></span>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </td> 
                                                                    <td class="lovtd" style="text-align: right;">
                                                                        <input type="text" class="form-control rqrdFld jbDetAccRate" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_QTY" name="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_QTY" value="<?php
                                                                        echo $trsctnLnQty;
                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_QTY', 'oneScmSalesInvcSmryLinesTable', 'jbDetAccRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllScmSalesInvcSmryTtl();">                                                    
                                                                    </td>                                               
                                                                    <td class="lovtd" style="max-width:35px;width:35px;text-align: center;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_UomID" value="<?php echo $trsctnLnUomID; ?>" style="width:100% !important;">  
                                                                        <div class="" style="width:100% !important;">
                                                                            <label class="btn btn-primary btn-file" onclick="getOneScmUOMBrkdwnForm(<?php echo $sbmtdScmSalesInvcID; ?>, 2, 'oneScmSalesInvcSmryRow_<?php echo $cntr; ?>');">
                                                                                <span class="" id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_UomNm1"><?php echo $trsctnLnUomNm; ?></span>
                                                                            </label>
                                                                        </div>                                              
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <input type="text" class="form-control jbDetDbt" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_UnitPrice" name="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_UnitPrice" value="<?php
                                                                        echo number_format($trsctnLnUnitPrice, 5);
                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_UnitPrice', 'oneScmSalesInvcSmryLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" <?php echo $edtPriceRdOnly; ?> onchange="calcAllScmSalesInvcSmryTtl();">                                                    
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <input type="text" class="form-control jbDetCrdt" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LineAmt" name="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LineAmt" value="<?php
                                                                        echo number_format($trsctnLnAmnt, 2);
                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LineAmt', 'oneScmSalesInvcSmryLinesTable', 'jbDetCrdt');" style="width:100% !important;text-align: right;" readonly="true" onchange="calcAllScmSalesInvcSmryTtl();">                                                    
                                                                    </td>  
                                                                    <td class="lovtd" style="text-align: center;">
                                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Item Consignments" 
                                                                                onclick="getScmSalesInvcItems('oneScmSalesInvcSmryRow_<?php echo $cntr; ?>', 'ShowDialog', '<?php echo $scmSalesInvcVchType; ?>', 'true', function () {
                                                                                            var a = 1;
                                                                                        });" style="padding:2px !important;"> 
                                                                            <img src="cmn_images/chcklst3.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                        </button>
                                                                    </td>   
                                                                    <td class="lovtd" style="text-align: center;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxID" value="<?php echo $trsctnLnTxID; ?>" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxNm" value="<?php echo $trsctnLnTxNm; ?>" style="width:100% !important;">  
                                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?php echo $trsctnLnTxNm; ?>" id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxBtn"
                                                                                onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Tax Codes', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnTxID; ?>', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxID', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxNm', 'clear', 0, '', function () {
                                                                                            changeBtnTitleFunc('oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxNm', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_TaxBtn');
                                                                                        });" style="padding:2px !important;"> 
                                                                            <img src="cmn_images/tax-icon420x500.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                        </button>
                                                                    </td>   
                                                                    <td class="lovtd" style="text-align: center;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntID" value="<?php echo $trsctnLnDscntID; ?>" style="width:100% !important;"> 
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntNm" value="<?php echo $trsctnLnDscntNm; ?>" style="width:100% !important;"> 
                                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?php echo $trsctnLnDscntNm; ?>" id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntBtn"  
                                                                                onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Discount Codes', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnDscntID; ?>', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntID', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntNm', 'clear', 0, '', function () {
                                                                                            changeBtnTitleFunc('oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntNm', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_DscntBtn');
                                                                                        });" style="padding:2px !important;"> 
                                                                            <img src="cmn_images/dscnt_456356.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                        </button>
                                                                    </td>  
                                                                    <td class="lovtd" style="text-align: center;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgID" value="<?php echo $trsctnLnChrgID; ?>" style="width:100% !important;"> 
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgNm" value="<?php echo $trsctnLnChrgNm; ?>" style="width:100% !important;"> 
                                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?php echo $trsctnLnChrgNm; ?>" id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgBtn" 
                                                                                onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Extra Charges', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnChrgID; ?>', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgID', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgNm', 'clear', 0, '', function () {
                                                                                            changeBtnTitleFunc('oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgNm', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ChrgBtn');
                                                                                        });" style="padding:2px !important;"> 
                                                                            <img src="cmn_images/truck571d7f45.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                        </button>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <input type="text" class="form-control" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ExtraDesc" name="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ExtraDesc" value="<?php echo $trsctnLnExtrDesc; ?>" style="width:100% !important;text-align: left;" readonly="true">                                                    
                                                                    </td>
                                                                    <?php if ($scmSalesInvcAllwDues === '1') { ?>
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Linked Person" id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_PrsnBtn" 
                                                                                    onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', 'allOtherInputOrgID', '', '', 'radio', true, '<?php echo $trsctnLnLnkdPrsnID; ?>', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_LnkdPrsnID', 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_ExtraDesc', 'clear', 0, '', function () {});" style="padding:2px !important;"> 
                                                                                <img src="cmn_images/person.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                            </button>
                                                                        </td>
                                                                    <?php } ?>
                                                                    <td class="lovtd" style="text-align: center;">
                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delScmSalesInvcDetLn('oneScmSalesInvcSmryRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document Line">
                                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                        <tfoot>                                                            
                                                            <tr>
                                                                <th>&nbsp;</th>
                                                                <th>&nbsp;</th>
                                                                <th>&nbsp;</th>
                                                                <th>TOTALS:</th>
                                                                <th>&nbsp;</th>
                                                                <th style="text-align: right;">
                                                                    <?php
                                                                    echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdRIJbSmryAmtTtlBtn\">" . number_format($ttlTrsctnEntrdAmnt,
                                                                            2, '.', ',') . "</span>";
                                                                    ?>
                                                                    <input type="hidden" id="myCptrdRIJbSmryAmtTtlVal" value="<?php echo $ttlTrsctnEntrdAmnt; ?>">
                                                                </th>
                                                                <th style="">&nbsp;</th>                                           
                                                                <th style="">&nbsp;</th>                                           
                                                                <th style="">&nbsp;</th>
                                                                <th style="">&nbsp;</th>
                                                                <th style="">&nbsp;</th>
                                                                <th style="">&nbsp;</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                                <div class="col-md-2" style="padding:0px 2px 0px 2px !important;">
                                                    <table class="table table-striped table-bordered table-responsive" id="oneScmSalesInvcSmry1Table" cellspacing="0" width="100%" style="width:100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>Summary Item</th>
                                                                <th style="text-align:right;">Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>   
                                                            <?php
                                                            $cntr = 0;
                                                            $resultRw = get_DocSmryLns($sbmtdScmSalesInvcID, $scmSalesInvcVchType);
                                                            $ttlTrsctnEntrdAmnt = 0;
                                                            $trnsBrkDwnVType = "VIEW";
                                                            if ($mkReadOnly == "") {
                                                                $trnsBrkDwnVType = "EDIT";
                                                            }
                                                            $scmRealInvcGrndTtl = 0;
                                                            while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                $trsctnLineID = (float) $rowRw[0];
                                                                $trsctnLineDesc = $rowRw[1];
                                                                $entrdAmnt = (float) $rowRw[2];
                                                                $trsctnCodeBhndID = (int) $rowRw[3];
                                                                $ln_smmry_type = $rowRw[4];
                                                                $shdAutoCalc = $rowRw[5];
                                                                $cntr += 1;
                                                                $style4 = "";
                                                                $style5 = "";
                                                                if ($ln_smmry_type == "5Grand Total") {
                                                                    $style4 = "font-weight:bold;";
                                                                    $style5 = "font-weight:bold;color:black";
                                                                    $scmRealInvcGrndTtl = $entrdAmnt;
                                                                } else if ($ln_smmry_type == "70Change/Balance") {
                                                                    $style4 = "font-weight:bold;";
                                                                    $style5 = "font-weight:bold;color:red";
                                                                } else if ($ln_smmry_type == "6Total Payments Received" || $ln_smmry_type == "8Deposits") {
                                                                    $style4 = "font-weight:bold;color:green";
                                                                    $style5 = "font-weight:bold;color:green";
                                                                } else if ($ln_smmry_type == "9Actual_Change/Balance" || $ln_smmry_type == "7Change/Balance") {
                                                                    if ($entrdAmnt >= 0) {
                                                                        $style4 = "font-weight:bold;color:red";
                                                                        $style5 = "font-weight:bold;color:red";
                                                                    } else {
                                                                        $style4 = "font-weight:bold;";
                                                                        $style5 = "font-weight:bold;color:green";
                                                                    }
                                                                }
                                                                ?>
                                                                <tr id="oneScmSalesInvcSmry1Row_<?php echo $cntr; ?>">                                                 
                                                                    <td class="lovtd"  style="">  
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmry1Row<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneScmSalesInvcSmry1Row<?php echo $cntr; ?>_CodeBhndID" value="<?php echo $trsctnCodeBhndID; ?>" style="width:100% !important;">  
                                                                        <span style="<?php echo $style4; ?>"><?php echo $trsctnLineDesc; ?></span>
                                                                    </td> 
                                                                    <td class="lovtd" style="text-align:right;">
                                                                        <span style="<?php echo $style5; ?>"><?php
                                                                            echo number_format($entrdAmnt, 2);
                                                                            ?></span>
                                                                    </td>  
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                        <tfoot>                                                            
                                                            <tr>
                                                                <th>&nbsp;</th>
                                                                <th>&nbsp;</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                    <input id="scmRealInvcGrndTtl" type="hidden" value="<?php echo $scmRealInvcGrndTtl; ?>"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="salesInvcExtraInfo" class="tab-pane fadein" style="border:none !important;padding:0px !important;">
                                            <div class="row"  style="padding:0px 15px 0px 15px;">
                                                <div class="col-md-4" style="border:1px solid #ddd; border-radius: 5px;padding: 5px 10px 5px 10px;margin-right: 0px !important;">
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label style="margin-bottom:0px !important;">Payment Terms:</label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="input-group"  style="width:100%;">
                                                                <textarea class="form-control" rows="3" cols="20" id="scmSalesInvcPayTerms" name="scmSalesInvcPayTerms" <?php echo $mkReadOnly; ?> style="text-align:left !important;"><?php echo $scmSalesInvcPayTerms; ?></textarea>
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('scmSalesInvcPayTerms');" style="max-width:30px;width:30px;">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label style="margin-bottom:0px !important;">Linked Document:</label>
                                                        </div>
                                                        <div class="col-md-8" style="padding:0px 0px 0px 0px;">
                                                            <div class="col-md-5" style="padding:0px 1px 0px 15px;">
                                                                <select data-placeholder="Select..." class="form-control chosen-select" id="scmSalesInvcEvntDocTyp" style="width:100% !important;" onchange="lnkdEvntScmSalesInvcChng();">
                                                                    <?php
                                                                    $valslctdArry = array("", "", "", "", "");
                                                                    $srchInsArrys = array("None", "Attendance Register", "Production Process Run",
                                                                        "Customer File Number", "Project Management");
                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                        if ($scmSalesInvcEvntDocTyp == $srchInsArrys[$z]) {
                                                                            $valslctdArry[$z] = "selected";
                                                                        }
                                                                        ?>
                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-7" style="padding:0px 15px 0px 1px;">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" aria-label="..." id="scmSalesInvcEvntCtgry" name="scmSalesInvcEvntCtgry" value="<?php echo $scmSalesInvcEvntCtgry; ?>" readonly="true">
                                                                    <label id="scmSalesInvcEvntCtgryLbl" class="btn btn-primary btn-file input-group-addon" onclick="getlnkdEvtAccbRILovCtgry('myLovModal', 'myLovModalTitle', 'myLovModalBody', '', '', '', '', 'radio', true, '', 'scmSalesInvcEvntCtgry', 'scmSalesInvcEvntCtgry', 'clear', 1, '', function () {});">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12" style="padding:2px 15px 0px 15px;">
                                                                <div class="input-group">
                                                                    <input class="form-control" id="scmSalesInvcEvntRgstr" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Linked Document Number" type = "text" min="0" placeholder="" value="<?php echo $scmSalesInvcEvntRgstr; ?>" readonly="true"/>
                                                                    <input type="hidden" id="scmSalesInvcEvntRgstrID" value="<?php echo $scmSalesInvcEvntRgstrID; ?>">
                                                                    <label id="scmSalesInvcEvntRgstrLbl" class="btn btn-primary btn-file input-group-addon" onclick="getlnkdEvtAccbRILovEvnt('myLovModal', 'myLovModalTitle', 'myLovModalBody', '', 'allOtherInputOrgID', '', '', 'radio', true, '', 'scmSalesInvcEvntRgstrID', 'scmSalesInvcEvntRgstr', 'clear', 1, '', function () {});">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4" style="padding: 0px 1px 0px 1px;">
                                                    <fieldset class="basic_person_fs2" style="min-height:50px !important;padding: 5px 15px 5px 15px !important;margin-left:3px !important;border-radius: 5px;">
                                                        <div class="form-group">
                                                            <label for="scmSalesInvcGLBatch" class="control-label col-md-4" style="padding:0px 10px 0px 10px !important;">GL Batch Name:</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group">
                                                                    <input class="form-control" id="scmSalesInvcGLBatch" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type = "text" placeholder="" value="<?php echo $scmSalesInvcGLBatch; ?>" readonly="true"/>
                                                                    <input type="hidden" id="scmSalesInvcGLBatchID" value="<?php echo $scmSalesInvcGLBatchID; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getOneJrnlBatchForm(<?php echo $scmSalesInvcGLBatchID; ?>, 1, 'ReloadDialog',<?php echo $sbmtdScmSalesInvcID; ?>, 'Sales Invoice');">
                                                                        <img src="cmn_images/openfileicon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Open
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="scmSalesInvcRcvblDoc" class="control-label col-md-4" style="padding:0px 10px 0px 10px !important;">Receivable: Doc.</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group">
                                                                    <input class="form-control" id="scmSalesInvcRcvblDoc" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type = "text" placeholder="" value="<?php echo $scmSalesInvcRcvblDoc; ?>" readonly="true"/>
                                                                    <input type="hidden" id="scmSalesInvcRcvblDocID" value="<?php echo $scmSalesInvcRcvblDocID; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getOneAccbRcvblsInvcForm(<?php echo $scmSalesInvcRcvblDocID; ?>, 1, 'ReloadDialog', '<?php echo $scmSalesInvcRcvblDocType; ?>',<?php echo $sbmtdScmSalesInvcID; ?>, 'Sales Invoice');">
                                                                        <img src="cmn_images/openfileicon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Open
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for=accbRcvblDebtGlBatchNm" class="control-label col-md-4" style="padding:0px 10px 0px 10px !important;">Bad Debt GL Batch:</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group">
                                                                    <input class="form-control" id="accbRcvblDebtGlBatchNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type = "text" placeholder="" value="<?php echo $accbRcvblDebtGlBatchNm; ?>" readonly="true"/>
                                                                    <input type="hidden" id="accbRcvblDebtGlBatchID" value="<?php echo $accbRcvblDebtGlBatchID; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getOneJrnlBatchForm(<?php echo $accbRcvblDebtGlBatchID; ?>, 1, 'ReloadDialog',<?php echo $sbmtdScmSalesInvcID; ?>, 'Sales Invoice');">
                                                                        <img src="cmn_images/openfileicon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Open
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>                            
                                                        <div class="form-group">
                                                            <label for="scmSalesInvcDfltBalsAcnt" class="control-label col-md-4" style="padding:0px 10px 0px 10px !important;">Receivable Account:</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group">
                                                                    <input class="form-control" id="scmSalesInvcDfltBalsAcnt" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Enter GL Account Number" type = "text" min="0" placeholder="" value="<?php echo $scmSalesInvcDfltBalsAcnt; ?>" readonly="true"/>
                                                                    <input type="hidden" id="scmSalesInvcDfltBalsAcntID" value="<?php echo $scmSalesInvcDfltBalsAcntID; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Liability Accounts', '', '', '', 'radio', true, '', 'scmSalesInvcDfltBalsAcntID', 'scmSalesInvcDfltBalsAcnt', 'clear', 1, '', function () {});">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
                <?php
            } else if ($vwtyp == 4) {
                //Get Selected Customer ID Account Information
                header("content-type:application/json");
                $scmSalesInvcCstmrID = isset($_POST['scmSalesInvcCstmrID']) ? (float) cleanInputData($_POST['scmSalesInvcCstmrID']) : -1;
                $scmSalesInvcCstmrSiteID = isset($_POST['scmSalesInvcCstmrSiteID']) ? (float) cleanInputData($_POST['scmSalesInvcCstmrSiteID']) : -1;
                $accnts = array("Increase", /* Balancing Account */ "-1", "Increase", /* Charge Account */ "-1");
                $cstmrAccntID = getGnrlRecNm("scm.scm_cstmr_suplr", "cust_sup_id", "dflt_rcvbl_accnt_id", $scmSalesInvcCstmrID);
                $accntID = $cstmrAccntID;
                if ($accntID <= 0) {
                    $dflACntID = get_DfltRcvblAcnt($orgID);
                    $accntID = $dflACntID;
                }
                $accnts[1] = $accntID;
                if ($scmSalesInvcCstmrSiteID > 0) {
                    $scmSalesInvcCstmrSiteID = get_CstmrSpplrSiteLnkID($scmSalesInvcCstmrID, $scmSalesInvcCstmrSiteID);
                    if ($scmSalesInvcCstmrSiteID <= 0) {
                        $scmSalesInvcCstmrSiteID = get_DfltCstmrSpplrSiteID($scmSalesInvcCstmrID);
                    }
                } else {
                    $scmSalesInvcCstmrSiteID = get_DfltCstmrSpplrSiteID($scmSalesInvcCstmrID);
                }
                $arr_content['BalsAcntID'] = $accnts[1];
                $arr_content['BalsAcntNm'] = getAccntNum($accnts[1]) . "." . getAccntName($accnts[1]);
                $arr_content['CostAcntIncsDcrs'] = $accnts[2];
                $arr_content['CostAcntID'] = $accnts[3];
                $arr_content['CostAcntNm'] = getAccntNum($accnts[3]) . "." . getAccntName($accnts[3]);
                $arr_content['scmSalesInvcCstmrSiteID'] = $scmSalesInvcCstmrSiteID;
                $arr_content['scmSalesInvcCstmrSiteNm'] = getCstmrSiteNm($scmSalesInvcCstmrSiteID, $scmSalesInvcCstmrID);
                echo json_encode($arr_content);
                exit();
            } else if ($vwtyp == 5) {
                //Get Selected Supplier ID Account Information
                header("content-type:application/json");
                $scmCnsgnRcptSpplrID = isset($_POST['scmCnsgnRcptSpplrID']) ? (float) cleanInputData($_POST['scmCnsgnRcptSpplrID']) : -1;
                $scmCnsgnRcptSpplrSiteID = isset($_POST['scmCnsgnRcptSpplrSiteID']) ? (float) cleanInputData($_POST['scmCnsgnRcptSpplrSiteID']) : -1;

                $accnts = array("Increase", /* Balancing Account */ "-1", "Increase", /* Charge Account */ "-1");
                $cstmrAccntID = getGnrlRecNm("scm.scm_cstmr_suplr", "cust_sup_id", "dflt_pybl_accnt_id", $scmCnsgnRcptSpplrID);
                $accntID = $cstmrAccntID;
                if ($accntID <= 0) {
                    $dflACntID = get_DfltPyblAcnt($orgID);
                    $accntID = $dflACntID;
                }
                $accnts[1] = $accntID;
                if ($scmCnsgnRcptSpplrSiteID > 0) {
                    $scmCnsgnRcptSpplrSiteID = get_CstmrSpplrSiteLnkID($scmCnsgnRcptSpplrID, $scmCnsgnRcptSpplrSiteID);
                    if ($scmCnsgnRcptSpplrSiteID <= 0) {
                        $scmCnsgnRcptSpplrSiteID = get_DfltCstmrSpplrSiteID($scmCnsgnRcptSpplrID);
                    }
                } else {
                    $scmCnsgnRcptSpplrSiteID = get_DfltCstmrSpplrSiteID($scmCnsgnRcptSpplrID);
                }
                $arr_content['BalsAcntID'] = $accnts[1];
                $arr_content['BalsAcntNm'] = getAccntNum($accnts[1]) . "." . getAccntName($accnts[1]);
                $arr_content['CostAcntIncsDcrs'] = $accnts[2];
                $arr_content['CostAcntID'] = $accnts[3];
                $arr_content['CostAcntNm'] = getAccntNum($accnts[3]) . "." . getAccntName($accnts[3]);
                $arr_content['scmCnsgnRcptSpplrSiteID'] = $scmCnsgnRcptSpplrSiteID;
                $arr_content['scmCnsgnRcptSpplrSiteNm'] = getCstmrSiteNm($scmCnsgnRcptSpplrSiteID, $scmCnsgnRcptSpplrID);
                echo json_encode($arr_content);
                exit();
            } else if ($vwtyp == 20) {
                /* All Attached Documents */
                $sbmtdScmSalesInvcID = isset($_POST['sbmtdScmSalesInvcID']) ? cleanInputData($_POST['sbmtdScmSalesInvcID']) : -1;
                if (!$canAdd || ($sbmtdScmSalesInvcID > 0 && !$canEdt)) {
                    restricted();
                    exit();
                }
                $pkID = $sbmtdScmSalesInvcID;
                $total = get_Total_SalesInvc_Attachments($srchFor, $pkID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $attchSQL = "";
                $result2 = get_SalesInvc_Attachments($srchFor, $curIdx, $lmtSze, $pkID, $attchSQL);
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-4";
                ?>       
                <fieldset class="" style="padding:10px 0px 5px 0px !important;">
                    <form class="" id="attchdSalesInvcDocsTblForm">
                        <div class="row">
                            <?php
                            $nwRowHtml = urlencode("<tr id=\"attchdSalesInvcDocsRow__WWW123WWW\">"
                                    . "<td class=\"lovtd\"><span>New</span></td>"
                                    . "<td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                              <div class=\"input-group\" style=\"width:100% !important;\">
                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"attchdSalesInvcDocsRow_WWW123WWW_DocCtgryNm\" value=\"\">
                                                <input class=\"form-control\" aria-label=\"...\" id=\"attchdSalesInvcDocsRow_WWW123WWW_DocFile\" type=\"file\" style=\"visibility:hidden;height:5px !important;display:none;\" />     
                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Attachment Document Categories', '', '', '', 'radio', true, '', 'attchdSalesInvcDocsRow_WWW123WWW_DocCtgryNm', 'attchdSalesInvcDocsRow_WWW123WWW_DocCtgryNm', 'clear', 0, '');\">
                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                </label>
                                              </div>
                                              </div>
                                              <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attchdSalesInvcDocsRow_WWW123WWW_AttchdDocsID\" value=\"-1\" style=\"\">                                               
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"uploadFileToSalesInvcDocs('attchdSalesInvcDocsRow_WWW123WWW_DocFile','attchdSalesInvcDocsRow_WWW123WWW_AttchdDocsID','attchdSalesInvcDocsRow_WWW123WWW_DocCtgryNm'," . $pkID . ",'attchdSalesInvcDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Download Document\">
                                                    <img src=\"cmn_images/openfileicon.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\"> Upload
                                                </button>
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAttchdSalesInvcDoc('attchdSalesInvcDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Document\">
                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                </button>
                                          </td>
                                        </tr>");
                            ?> 
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 1px !important;"> 
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('attchdSalesInvcDocsTable', 0, '<?php echo $nwRowHtml; ?>');" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Document
                                    </button>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="attchdSalesInvcDocsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAttchdSalesInvcDocs(event, '', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdScmSalesInvcID=<?php echo $sbmtdScmSalesInvcID; ?>', 'ReloadDialog');">
                                    <input id="attchdSalesInvcDocsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdSalesInvcDocs('clear', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdScmSalesInvcID=<?php echo $sbmtdScmSalesInvcID; ?>', 'ReloadDialog');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdSalesInvcDocs('', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdScmSalesInvcID=<?php echo $sbmtdScmSalesInvcID; ?>', 'ReloadDialog');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="attchdSalesInvcDocsDsplySze" style="min-width:70px !important;">                            
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
                                            <a class="rhopagination" href="javascript:getAttchdSalesInvcDocs('previous', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdScmSalesInvcID=<?php echo $sbmtdScmSalesInvcID; ?>','ReloadDialog');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAttchdSalesInvcDocs('next', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdScmSalesInvcID=<?php echo $sbmtdScmSalesInvcID; ?>','ReloadDialog');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="attchdSalesInvcDocsTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
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
                                            $doc_src = $ftp_base_db_fldr . "/Sales/" . $row2[3];
                                            $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                                            if (file_exists($doc_src)) {
                                                //file exists!
                                            } else {
                                                //file does not exist.
                                                $doc_src_encrpt = "None";
                                            }
                                            ?>
                                            <tr id="attchdSalesInvcDocsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><span><?php
                                                        echo ($curIdx * $lmtSze) + ($cntr);
                                                        ?></span></td>
                                                <td class="lovtd">                                                                   
                                                    <span><?php echo $row2[2]; ?></span>
                                                    <input type="hidden" class="form-control" aria-label="..." id="attchdSalesInvcDocsRow<?php echo $cntr; ?>_AttchdDocsID" value="<?php echo $row2[0]; ?>" style="width:100% !important;">                                              
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
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttchdSalesInvcDoc('attchdSalesInvcDocsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document">
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
            } else if ($vwtyp == 701) {
                //Print invoice rpt   
                $vPsblValID1 = getEnbldPssblValID("Html Invoice Print File Name", getLovID("All Other Sales/Inventory Setups"));
                $vPsblVal1 = getPssblValDesc($vPsblValID1);
                if ($vPsblVal1 == "") {
                    $vPsblVal1 = 'htm_rpts/invoice_rpt.php';
                }
                require $vPsblVal1;
            } else if ($vwtyp == 702) {
                //Print POS Rcpt        
                $vPsblValID1 = getEnbldPssblValID("Html POS Receipt File Name", getLovID("All Other Sales/Inventory Setups"));
                $vPsblVal1 = getPssblValDesc($vPsblValID1);
                if ($vPsblVal1 == "") {
                    $vPsblVal1 = 'htm_rpts/pos_rpt.php';
                }
                require $vPsblVal1;
            }
        }
    }
}
?>