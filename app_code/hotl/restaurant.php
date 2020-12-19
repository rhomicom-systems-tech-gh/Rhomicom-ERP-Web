<?php
$canAdd = test_prmssns($dfltPrvldgs[24], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[25], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[26], $mdlNm);

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
                    echo deleteCheckIn($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
                
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Sales Invoice Transaction
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
                if ($usrTrnsCode == "") {
                    $usrTrnsCode = "XX";
                }
                $dte = date('ymd');
                $sbmtdHotlChckinDocID = isset($_POST['sbmtdHotlChckinDocID']) ? (float) cleanInputData($_POST['sbmtdHotlChckinDocID']) : -1;
                $hotlChckinDocVchType = isset($_POST['hotlChckinDocVchType']) ? cleanInputData($_POST['hotlChckinDocVchType']) : "";
                $hotlChckinDocNum = isset($_POST['hotlChckinDocNum']) ? cleanInputData($_POST['hotlChckinDocNum']) : "";
                $hotlChckinFcltyType = isset($_POST['hotlChckinFcltyType']) ? cleanInputData($_POST['hotlChckinFcltyType']) : "";
                $hotlChckinDocStrtDte = isset($_POST['hotlChckinDocStrtDte']) ? cleanInputData($_POST['hotlChckinDocStrtDte']) : "";
                $hotlChckinDocEndDte = isset($_POST['hotlChckinDocEndDte']) ? cleanInputData($_POST['hotlChckinDocEndDte']) : "";
                $hotlChckinDocSrvcTypID = isset($_POST['hotlChckinDocSrvcTypID']) ? (float) cleanInputData($_POST['hotlChckinDocSrvcTypID'])
                            : -1;
                $hotlChckinDocSrvcTyp = isset($_POST['hotlChckinDocSrvcTyp']) ? cleanInputData($_POST['hotlChckinDocSrvcTyp']) : "";
                $hotlChckinDocRmID = isset($_POST['hotlChckinDocRmID']) ? (float) cleanInputData($_POST['hotlChckinDocRmID']) : -1;
                $hotlChckinDocRmNum = isset($_POST['hotlChckinDocRmNum']) ? (float) cleanInputData($_POST['hotlChckinDocRmNum']) : "";
                $hotlChckinDocNoAdlts = isset($_POST['hotlChckinDocNoAdlts']) ? (float) cleanInputData($_POST['hotlChckinDocNoAdlts']) : 0;
                $hotlChckinDocNoChldrn = isset($_POST['hotlChckinDocNoChldrn']) ? (float) cleanInputData($_POST['hotlChckinDocNoChldrn']) : 0;
                $hotlChckinDocSpnsrID = isset($_POST['hotlChckinDocSpnsrID']) ? (float) cleanInputData($_POST['hotlChckinDocSpnsrID']) : -1;
                $hotlChckinDocSpnsr = isset($_POST['hotlChckinDocSpnsr']) ? cleanInputData($_POST['hotlChckinDocSpnsr']) : "";
                $hotlChckinDocSpnsee = isset($_POST['hotlChckinDocSpnsee']) ? cleanInputData($_POST['hotlChckinDocSpnsee']) : "";
                $hotlChckinDocSpnsrSiteID = isset($_POST['hotlChckinDocSpnsrSiteID']) ? (float) cleanInputData($_POST['hotlChckinDocSpnsrSiteID'])
                            : -1;
                $hotlChckinDocSpnseeID = isset($_POST['hotlChckinDocSpnseeID']) ? (float) cleanInputData($_POST['hotlChckinDocSpnseeID']) : -1;
                $hotlChckinDocSpnseeSiteID = isset($_POST['hotlChckinDocSpnseeSiteID']) ? (float) cleanInputData($_POST['hotlChckinDocSpnseeSiteID'])
                            : -1;
                $hotlChckinDocOthrInfo = isset($_POST['hotlChckinDocOthrInfo']) ? cleanInputData($_POST['hotlChckinDocOthrInfo']) : "";
                if ($hotlChckinDocOthrInfo == "") {
                    $hotlChckinDocOthrInfo = $hotlChckinDocVchType . ": Facility No. " . $hotlChckinDocRmNum . " for " . $hotlChckinDocSpnsr . " from " .
                            $hotlChckinDocStrtDte . " to " . $hotlChckinDocEndDte . " (" . $hotlChckinDocNum . ")";
                }

                $sbmtdScmSalesInvcID = isset($_POST['sbmtdScmSalesInvcID']) ? (float) cleanInputData($_POST['sbmtdScmSalesInvcID']) : -1;
                $scmSalesInvcDocNum = isset($_POST['scmSalesInvcDocNum']) ? cleanInputData($_POST['scmSalesInvcDocNum']) : "";
                $scmSalesInvcDfltTrnsDte = isset($_POST['scmSalesInvcDfltTrnsDte']) ? cleanInputData($_POST['scmSalesInvcDfltTrnsDte']) : '';
                $scmSalesInvcVchType = 'Sales Invoice';
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
                $scmSalesInvcEvntRgstrID = isset($_POST['scmSalesInvcEvntRgstrID']) ? (float) cleanInputData($_POST['scmSalesInvcEvntRgstrID'])
                            : -1;
                $scmSalesInvcCstmrID = $hotlChckinDocSpnsrID;
                $scmSalesInvcCstmrSiteID = $hotlChckinDocSpnsrSiteID;
                $scmSalesInvcBrnchID = isset($_POST['scmSalesInvcBrnchID']) ? (float) cleanInputData($_POST['scmSalesInvcBrnchID']) : $brnchLocID;
                $scmSalesInvcDfltBalsAcntID = isset($_POST['scmSalesInvcDfltBalsAcntID']) ? (float) cleanInputData($_POST['scmSalesInvcDfltBalsAcntID'])
                            : -1;
                $scmSalesInvcDesc = $hotlChckinDocOthrInfo;
                $scmSalesInvcPayTerms = isset($_POST['scmSalesInvcPayTerms']) ? cleanInputData($_POST['scmSalesInvcPayTerms']) : '';

                $scmSalesInvcPayMthdID = isset($_POST['scmSalesInvcPayMthdID']) ? (int) cleanInputData($_POST['scmSalesInvcPayMthdID']) : -10;
                $scmSalesInvcCstmrInvcNum = isset($_POST['scmSalesInvcCstmrInvcNum']) ? cleanInputData($_POST['scmSalesInvcCstmrInvcNum']) : '';
                $srcSalesInvcDocID = isset($_POST['srcSalesInvcDocID']) ? (float) cleanInputData($_POST['srcSalesInvcDocID']) : -1;
                $slctdDetTransLines = isset($_POST['slctdDetTransLines']) ? cleanInputData($_POST['slctdDetTransLines']) : '';
                $slctdFcltiesInfoLines = isset($_POST['slctdFcltiesInfoLines']) ? cleanInputData($_POST['slctdFcltiesInfoLines']) : '';
                $shdSbmt = isset($_POST['shdSbmt']) ? (int) cleanInputData($_POST['shdSbmt']) : 0;
                $accbRcvblDebtGlBatchID = -1;
                $otherModuleDocId = -1;
                $otherModuleDocTyp = "";
                if (strlen($scmSalesInvcDesc) > 499) {
                    $scmSalesInvcDesc = substr($scmSalesInvcDesc, 0, 499);
                }
                $hotlChckinDocArvlFrm = isset($_POST['hotlChckinDocArvlFrm']) ? cleanInputData($_POST['hotlChckinDocArvlFrm']) : "";
                $hotlChckinDocPrcdTo = isset($_POST['hotlChckinDocPrcdTo']) ? cleanInputData($_POST['hotlChckinDocPrcdTo']) : "";
                $hotlChckinDocPayTrms = $scmSalesInvcPayTerms;
                $hotlChckinDocStatus = "";
                if ($hotlChckinDocVchType == "Rent Out") {
                    $hotlChckinDocStatus = "Rented Out";
                } else if ($hotlChckinDocVchType == "Check-In") {
                    $hotlChckinDocStatus = "Checked-In";
                } else if ($hotlChckinDocVchType == "Reservation") {
                    $hotlChckinDocStatus = "Reserved";
                } else if ($hotlChckinDocVchType == "Restaurant Order") {
                    $hotlChckinDocStatus = "Ordered";
                }
                $hotlChckinDocCalcMthd = isset($_POST['hotlChckinDocCalcMthd']) ? cleanInputData($_POST['hotlChckinDocCalcMthd']) : "0";
                $hotlChckinDocSpnsrSite = isset($_POST['hotlChckinDocSpnsrSite']) ? cleanInputData($_POST['hotlChckinDocSpnsrSite']) : "";
                $hotlChckinDocSpnseeSite = isset($_POST['hotlChckinDocSpnseeSite']) ? cleanInputData($_POST['hotlChckinDocSpnseeSite']) : "";
                $hotlChckinDocPrntChcknID = isset($_POST['hotlChckinDocPrntChcknID']) ? (float) cleanInputData($_POST['hotlChckinDocPrntChcknID'])
                            : -1;
                $hotlChckinDocPrntChcknTyp = isset($_POST['hotlChckinDocPrntChcknTyp']) ? cleanInputData($_POST['hotlChckinDocPrntChcknTyp'])
                            : "";
                $scmSalesInvcRcvblDocID = isset($_POST['scmSalesInvcRcvblDocID']) ? (float) cleanInputData($_POST['scmSalesInvcRcvblDocID'])
                            : -1;
                $scmSalesInvcRcvblDoc = isset($_POST['scmSalesInvcRcvblDoc']) ? cleanInputData($_POST['scmSalesInvcRcvblDoc']) : "";
                $tst = deleteRcvblsDocHdrNDet($scmSalesInvcRcvblDocID, $scmSalesInvcRcvblDoc);
                $exitErrMsg = "";
                if ($hotlChckinDocNum == "") {
                    $exitErrMsg .= "Please enter Document Number!<br/>";
                }
                if ($hotlChckinDocVchType == "") {
                    $exitErrMsg .= "Document Type cannot be empty!<br/>";
                }
                if ($scmSalesInvcDfltTrnsDte == "") {
                    $exitErrMsg .= "Document Date cannot be empty!<br/>";
                }
                if ($hotlChckinDocSrvcTypID <= 0) {
                    $exitErrMsg .= "Service Type cannot be empty!<br/>";
                }
                if ($hotlChckinDocRmID <= 0) {
                    $exitErrMsg .= "Facility Number cannot be empty!<br/>";
                }
                if ($hotlChckinDocVchType == "Check-In" || $hotlChckinDocVchType == "Reservation") {
                    if ($hotlChckinDocSpnseeID <= 0 || $hotlChckinDocSpnsrID <= 0) {
                        $exitErrMsg .= "Sponsor and Occupant cannot be empty!<br/>";
                    }
                }
                $date1 = DateTime::createFromFormat('d-M-Y H:i:s', $hotlChckinDocStrtDte);
                $date2 = DateTime::createFromFormat('d-M-Y H:i:s', $hotlChckinDocEndDte);

                if ($date2 < $date1) {
                    if ($hotlChckinDocVchType == "Check-In" || $hotlChckinDocVchType == "Reservation") {
                        $exitErrMsg .= "End Date cannot be Before " . $date1->format('d-M-Y H:i:s') . "<br/>";
                    } else {
                        $hotlChckinDocEndDte = $hotlChckinDocStrtDte;
                        $date2 = $date1;
                    }
                }
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
                $allwDues = false;
                $cstmrInvcNum = $scmSalesInvcCstmrInvcNum;
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['sbmtdScmSalesInvcID'] = $sbmtdScmSalesInvcID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                if ($sbmtdHotlChckinDocID <= 0) {
                    $sbmtdHotlChckinDocID = createCheckIn($hotlChckinDocNum, $hotlChckinDocVchType, $hotlChckinDocStrtDte,
                            $hotlChckinDocEndDte, $hotlChckinDocSrvcTypID, $hotlChckinDocRmID, $hotlChckinDocNoAdlts,
                            $hotlChckinDocNoChldrn, $hotlChckinDocSpnsrID, $hotlChckinDocSpnsrSiteID, $hotlChckinDocSpnseeID,
                            $hotlChckinDocSpnseeSiteID, $hotlChckinDocArvlFrm, $hotlChckinDocPrcdTo, $hotlChckinDocOthrInfo,
                            $hotlChckinFcltyType, $hotlChckinDocStatus, $hotlChckinDocPrntChcknID, $hotlChckinDocPrntChcknTyp,
                            $hotlChckinDocCalcMthd);
                } else if ($sbmtdHotlChckinDocID > 0) {
                    updateCheckIn($sbmtdHotlChckinDocID, $hotlChckinDocNum, $hotlChckinDocVchType, $hotlChckinDocStrtDte,
                            $hotlChckinDocEndDte, $hotlChckinDocSrvcTypID, $hotlChckinDocRmID, $hotlChckinDocNoAdlts,
                            $hotlChckinDocNoChldrn, $hotlChckinDocSpnsrID, $hotlChckinDocSpnsrSiteID, $hotlChckinDocSpnseeID,
                            $hotlChckinDocSpnseeSiteID, $hotlChckinDocArvlFrm, $hotlChckinDocPrcdTo, $hotlChckinDocOthrInfo,
                            $hotlChckinFcltyType, $hotlChckinDocStatus, $hotlChckinDocPrntChcknID, $hotlChckinDocPrntChcknTyp,
                            $hotlChckinDocCalcMthd);
                }
                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                $afftctd3 = 0;
                $afftctd4 = 0;

                if (trim($scmSalesInvcDocNum) == "") {
                    $docTypes = array("Pro-Forma Invoice", "Sales Order", "Sales Invoice",
                        "Internal Item Request", "Item Issue-Unbilled", "Sales Return");
                    $docTypPrfxs = array("PFI", "SO", "SI", "IIR", "IIU", "SR");
                    $docTypPrfx = $docTypPrfxs[findArryIdx($docTypes, $scmSalesInvcVchType)];
                    $gnrtdTrnsNo1 = $docTypPrfx . "-" . $usrTrnsCode . "-" . $dte . "-";
                    $gnrtdTrnsNo = $gnrtdTrnsNo1 . str_pad(((getRecCount_LstNum("scm.scm_sales_invc_hdr", "invc_number", "invc_hdr_id",
                                            $gnrtdTrnsNo1 . "%") + 1) . ""), 3, '0', STR_PAD_LEFT);
                    $scmSalesInvcDocNum = $gnrtdTrnsNo;
                }
                if ($sbmtdScmSalesInvcID <= 0) {
                    createSalesDocHdr($orgID, $scmSalesInvcDocNum, $scmSalesInvcDesc, $scmSalesInvcVchType, $scmSalesInvcDfltTrnsDte,
                            $scmSalesInvcPayTerms, $scmSalesInvcCstmrID, $scmSalesInvcCstmrSiteID, $apprvlStatus, $nxtApprvlActn,
                            $srcSalesInvcDocID, $scmSalesInvcDfltBalsAcntID, $scmSalesInvcPayMthdID, $scmSalesInvcInvcCurID,
                            $scmSalesInvcExRate, $sbmtdHotlChckinDocID, $hotlChckinDocVchType, $enblAutoChrg, $scmSalesInvcEvntRgstrID,
                            $scmSalesInvcEvntCtgry, $allwDues, $scmSalesInvcEvntDocTyp, $srcSalesInvcDocTyp, $scmSalesInvcBrnchID);
                    $sbmtdScmSalesInvcID = getGnrlRecID("scm.scm_sales_invc_hdr", "invc_number", "invc_hdr_id", $scmSalesInvcDocNum, $orgID);
                } else if ($sbmtdScmSalesInvcID > 0) {
                    updtSalesDocHdr($sbmtdScmSalesInvcID, $scmSalesInvcDocNum, $scmSalesInvcDesc, $scmSalesInvcVchType,
                            $scmSalesInvcDfltTrnsDte, $scmSalesInvcPayTerms, $scmSalesInvcCstmrID, $scmSalesInvcCstmrSiteID, $apprvlStatus,
                            $nxtApprvlActn, $srcSalesInvcDocID, $scmSalesInvcDfltBalsAcntID, $scmSalesInvcPayMthdID, $scmSalesInvcInvcCurID,
                            $scmSalesInvcExRate, $sbmtdHotlChckinDocID, $hotlChckinDocVchType, $enblAutoChrg, $scmSalesInvcEvntRgstrID,
                            $scmSalesInvcEvntCtgry, $allwDues, $scmSalesInvcEvntDocTyp, $srcSalesInvcDocTyp, $scmSalesInvcBrnchID);
                }
                if (trim($slctdDetTransLines, "|~") != "") {
                    $variousRows = explode("|", trim($slctdDetTransLines, "|"));
                    for ($y = 0; $y < count($variousRows); $y++) {
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 18) {
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
                            $ln_IsDlvrd = (cleanInputData1($crntRow[17])) === "YES" ? true : false;
                            if ($scmSalesInvcVchType == "Sales Return") {
                                $ln_ReturnRsn = $ln_ExtraDesc;
                            }
                            $errMsg = "";
                            if ($ln_LineDesc === "" || $ln_ItmID <= 0 || $ln_QTY <= 0) {
                                $errMsg = "Row " . ($y + 1) . ":- Item Description and Quantity are all required Fields!<br/>";
                            }
                            $extrDescs = "";
                            if ($errMsg === "") {
                                //Create Sales Doc Lines
                                if ($ln_LineDesc != "" && $ln_ItmID > 0 && $ln_QTY > 0) {
                                    if ($ln_TrnsLnID <= 0) {
                                        $ln_TrnsLnID = getNewSalesInvcLnID();
                                        $afftctd += createSalesDocLn1($ln_TrnsLnID, $sbmtdScmSalesInvcID, $ln_ItmID, $ln_QTY, $ln_UnitPrice,
                                                $ln_StoreID, $scmSalesInvcInvcCurID, $ln_SrcDocLnID, $ln_TaxID, $ln_DscntID, $ln_ChrgID,
                                                $ln_ReturnRsn, $ln_CnsgnIDs, $ln_OrgnlPrice, $ln_IsDlvrd, $ln_LnkdPrsnID, $ln_LineDesc,
                                                $ln_AstAcntID, $ln_CogsID, $ln_SalesRevID, $ln_SalesRetID, $ln_PurcRetID, $ln_ExpnsID,
                                                $rented_itm_qty, $sbmtdHotlChckinDocID, $hotlChckinDocVchType, $extrDescs);
                                        if ($ln_ItmID > 0 && $ln_StoreID > 0 && $ln_IsDlvrd === true) {
                                            $dfltRcvblAcntID = -1;
                                            $dfltCashAcntID = -1;
                                            $dfltCheckAcntID = -1;
                                            $dfltLbltyAccnt = -1;
                                            $stckID = (float) getGnrlRecNm("inv.inv_itm_list", "item_id",
                                                            "inv.getitemstockid(item_id, " . $ln_StoreID . ")", $ln_ItmID);
                                            $errMsg = udateItemBalances($ln_ItmID, $ln_QTY, $ln_CnsgnIDs, $ln_TaxID, $ln_DscntID,
                                                    $ln_ChrgID, $scmSalesInvcVchType, $sbmtdScmSalesInvcID, $srcSalesInvcDocID,
                                                    $dfltRcvblAcntID, $ln_AstAcntID, $ln_CogsID, $ln_ExpnsID, $ln_SalesRevID, $stckID,
                                                    $ln_UnitPrice, $ln_UnitPrice, $ln_TrnsLnID, $ln_SalesRetID, $dfltCashAcntID,
                                                    $dfltCheckAcntID, $ln_SrcDocLnID, $scmSalesInvcDfltTrnsDte, $scmSalesInvcDocNum,
                                                    $scmSalesInvcInvcCurID, $scmSalesInvcExRate, $dfltLbltyAccnt, $srcSalesInvcDocTyp);
                                            if (strpos($exitErrMsg, "SUCCESS") !== FALSE) {
                                                $exitErrMsg .= "<span style=\"color:green;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $errMsg . "</span>";
                                            } else {
                                                $exitErrMsg .= "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $errMsg . "</span>";
                                            }
                                        } else if ($ln_ItmID > 0 && $ln_StoreID <= 0 && $ln_IsDlvrd == true) {
                                            updateSalesLnDlvrd($ln_TrnsLnID, true);
                                        }
                                    } else {
                                        $isPrevdlvrd = cnvrtBitStrToBool(getGnrlRecNm("scm.scm_sales_invc_det", "invc_det_ln_id",
                                                        "is_itm_delivered", $ln_TrnsLnID));
                                        $stckID = (float) getGnrlRecNm("scm.scm_sales_invc_det", "invc_det_ln_id",
                                                        "inv.getitemstockid(itm_id, store_id)", $ln_TrnsLnID);
                                        $cnsgmntIDs = getGnrlRecNm("scm.scm_sales_invc_det", "invc_det_ln_id", "consgmnt_ids", $ln_TrnsLnID);
                                        if ($isPrevdlvrd === true && $stckID > 0) {
                                            if ($ln_SrcDocLnID > 0) {
                                                $doc_qty = (float) getGnrlRecNm("scm.scm_sales_invc_det", "invc_det_ln_id", "doc_qty",
                                                                $ln_TrnsLnID);
                                                updtSrcDocTrnsctdQty($ln_SrcDocLnID, -1 * $doc_qty);
                                            }
                                            $exitErrMsg .= rvrsQtyPostngs($ln_TrnsLnID, $cnsgmntIDs, $scmSalesInvcDfltTrnsDte, $stckID,
                                                    $srcSalesInvcDocTyp);
                                        }
                                        if ($ln_OthrMdlID == $sbmtdHotlChckinDocID && $ln_OthrMdlTyp == $hotlChckinDocVchType) {
                                            $afftctd += updateSalesDocLn1($ln_TrnsLnID, $ln_ItmID, $ln_QTY, $ln_UnitPrice, $ln_StoreID,
                                                    $scmSalesInvcInvcCurID, $ln_SrcDocLnID, $ln_TaxID, $ln_DscntID, $ln_ChrgID,
                                                    $ln_ReturnRsn, $ln_CnsgnIDs, $ln_OrgnlPrice, $ln_IsDlvrd, $ln_LnkdPrsnID, $ln_LineDesc,
                                                    $ln_AstAcntID, $ln_CogsID, $ln_SalesRevID, $ln_SalesRetID, $ln_PurcRetID, $ln_ExpnsID,
                                                    $rented_itm_qty, $sbmtdHotlChckinDocID, $hotlChckinDocVchType, $extrDescs);
                                        } else {
                                            $afftctd += updateSalesDocLn3($ln_TrnsLnID, $ln_ItmID, $ln_QTY, $ln_StoreID,
                                                    $scmSalesInvcInvcCurID, $ln_SrcDocLnID, $ln_TaxID, $ln_DscntID, $ln_ChrgID,
                                                    $ln_ReturnRsn, $ln_CnsgnIDs, $ln_IsDlvrd, $ln_LnkdPrsnID, $ln_LineDesc, $ln_AstAcntID,
                                                    $ln_CogsID, $ln_SalesRevID, $ln_SalesRetID, $ln_PurcRetID, $ln_ExpnsID, $rented_itm_qty,
                                                    $ln_OthrMdlID, $ln_OthrMdlTyp, $extrDescs);
                                        }
                                        if ($ln_ItmID > 0 && $ln_StoreID > 0 && $ln_IsDlvrd === true) {
                                            $dfltRcvblAcntID = -1;
                                            $dfltCashAcntID = -1;
                                            $dfltCheckAcntID = -1;
                                            $dfltLbltyAccnt = -1;
                                            $stckID = (float) getGnrlRecNm("inv.inv_itm_list", "item_id",
                                                            "inv.getitemstockid(item_id, " . $ln_StoreID . ")", $ln_ItmID);
                                            $errMsg = udateItemBalances($ln_ItmID, $ln_QTY, $ln_CnsgnIDs, $ln_TaxID, $ln_DscntID,
                                                    $ln_ChrgID, $scmSalesInvcVchType, $sbmtdScmSalesInvcID, $srcSalesInvcDocID,
                                                    $dfltRcvblAcntID, $ln_AstAcntID, $ln_CogsID, $ln_ExpnsID, $ln_SalesRevID, $stckID,
                                                    $ln_UnitPrice, $ln_UnitPrice, $ln_TrnsLnID, $ln_SalesRetID, $dfltCashAcntID,
                                                    $dfltCheckAcntID, $ln_SrcDocLnID, $scmSalesInvcDfltTrnsDte, $scmSalesInvcDocNum,
                                                    $scmSalesInvcInvcCurID, $scmSalesInvcExRate, $dfltLbltyAccnt, $srcSalesInvcDocTyp);
                                            if (strpos($exitErrMsg, "SUCCESS") !== FALSE) {
                                                $exitErrMsg .= "<span style=\"color:green;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $errMsg . "</span>";
                                            } else {
                                                $exitErrMsg .= "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $errMsg . "</span>";
                                            }
                                        } else if ($ln_ItmID > 0 && $ln_StoreID <= 0 && $ln_IsDlvrd == true) {
                                            updateSalesLnDlvrd($ln_TrnsLnID, true);
                                        }
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
                        if ($hotlChckinDocVchType == "Rent Out") {
                            updtCheckInStatus($sbmtdHotlChckinDocID, "Facility Returned");
                            updtRoomDirtyStatus($hotlChckinDocRmID, false);
                        } else if ($hotlChckinDocVchType == "Check-In") {
                            updtCheckInStatus($sbmtdHotlChckinDocID, "Checked-Out");
                            updtRoomDirtyStatus($hotlChckinDocRmID, false);
                        } else {
                            updtCheckInStatus($sbmtdHotlChckinDocID, "Cancelled");
                        }
                    } else {
                        $exitErrMsg .= "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Document Successfully Saved!"
                                . "<br/>" . $afftctd . " Document Transaction(s) Saved Successfully!";
                    }
                }
                $arr_content['percent'] = 100;
                $arr_content['sbmtdHotlChckinDocID'] = $sbmtdHotlChckinDocID;
                $arr_content['message'] = $exitErrMsg;
                echo json_encode($arr_content);
                exit();
            }
        } else if ($qstr == "VOID") {
            if ($actyp == 1) {
                //Reverse Sales Invoice Voucher
                $errMsg = "";
                $hotlChckinDocDesc = isset($_POST['hotlChckinDocDesc']) ? cleanInputData($_POST['hotlChckinDocDesc']) : '';
                $hotlChckinDocNum = isset($_POST['hotlChckinDocNum']) ? cleanInputData($_POST['hotlChckinDocNum']) : "";
                $shdSbmt = isset($_POST['shdSbmt']) ? cleanInputData($_POST['shdSbmt']) : 0;
                $sbmtdHotlChckinDocID = isset($_POST['sbmtdHotlChckinDocID']) ? (float) cleanInputData($_POST['sbmtdHotlChckinDocID']) : -1;
                if (!$cancelDocs) {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    exit();
                }
                $rqStatus = "Not Validated"; //approval_status
                $rqStatusNext = "Approve";
                $p_dochdrtype = "";
                $scmInvcDfltTrnsDte = "";
                $scmInvcDocNum = "";
                $sbmtdScmSalesInvcID = get_One_ChcknSalesID($sbmtdHotlChckinDocID);
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
                if ($rqStatus == "Not Validated" && $sbmtdHotlChckinDocID > 0) {
                    echo deleteCheckIn($sbmtdHotlChckinDocID, $hotlChckinDocNum);
                    echo deleteSalesDocHdrNDet($sbmtdScmSalesInvcID, $scmInvcDocNum);
                    exit();
                } else {
                    $exitErrMsg = cancelSalesPrchsDoc($sbmtdScmSalesInvcID, "Sales", $orgID, $usrID);
                    $arr_content['sbmtdHotlChckinDocID'] = $sbmtdHotlChckinDocID;
                    $arr_content['percent'] = 100;
                    if (strpos($exitErrMsg, "SUCCESS") !== FALSE) {
                        updtCheckInStatus($sbmtdHotlChckinDocID, "Cancelled");
                        execUpdtInsSQL("UPDATE scm.scm_sales_invc_hdr SET comments_desc='" . loc_db_escape_string($hotlChckinDocDesc) . "' WHERE (invc_hdr_id = " . $sbmtdScmSalesInvcID . ")");
                        execUpdtInsSQL("UPDATE hotl.checkins_hdr SET other_info='" . loc_db_escape_string($hotlChckinDocDesc) . "' WHERE (check_in_id = " . $sbmtdHotlChckinDocID . ")");
                        $arr_content['sbmtMsg'] = "<span style=\"color:green;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    } else {
                        $arr_content['sbmtMsg'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    }
                    echo json_encode($arr_content);
                    exit();
                }
            }
        } else {
            if ($vwtyp == 0) {
                $srcModule = isset($_POST['srcModule']) ? cleanInputData($_POST['srcModule']) : 'allmodules';
                if ($srcModule === "allmodules") {
                    echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Restaurant</span>
				</li>
                               </ul>
                              </div>";
                }
                $error = "";
                $searchAll = true;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Customer';
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
                $qShwSelfOnly = $vwOnlySelf;
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
                $extrWhere = " and a.doc_type IN ('Restaurant Order') and a.fclty_type IN ('Restaurant Table')";
                /* and a.prnt_chck_in_id<=0 and ((select count(1) from hotl.checkins_hdr xyz where xyz.prnt_chck_in_id = a.check_in_id)>0 or a.service_det_id <= 0) */
                //1000000;// 
                $total = get_Ttl_Checkins($srchFor, $srchIn, $orgID, $qShwUnpstdOnly, $qShwUnpaidOnly, $extrWhere, $qShwSelfOnly,
                        $qShwMyBranch);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_Checkins($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qShwUnpstdOnly, $qShwUnpaidOnly, $extrWhere,
                        $qShwSelfOnly, $qShwMyBranch);
                $cntr = 0;
                $colClassType1 = "col-md-2";
                $colClassType2 = "col-md-5";
                $colClassType3 = "col-md-5";
                ?> 
                <form id='hotlChckinDocForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">ALL RESTAURANT ORDERS</legend>
                        <div class="row" style="margin-bottom:0px;">
                            <?php
                            $colClassType1 = "col-md-2";
                            $colClassType2 = "col-md-5";
                            $colClassType3 = "col-md-10";
                            ?>
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="hotlChckinDocSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncHotlChckinDoc(event, '', '#<?php echo $srcModule; ?>', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcModule=<?php echo $srcModule; ?>')">
                                    <input id="hotlChckinDocPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <input id="sbmtdScmRtrnSrcDocID" type = "hidden" value="-1">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getHotlChckinDoc('clear', '#<?php echo $srcModule; ?>', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcModule=<?php echo $srcModule; ?>');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getHotlChckinDoc('', '#<?php echo $srcModule; ?>', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcModule=<?php echo $srcModule; ?>');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="hotlChckinDocSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "", "", "");
                                        $srchInsArrys = array(
                                            "Customer", "Table/Room Number", "Document Number",
                                            "Doc. Status", "Start Date",
                                            "Created By", "Branch", "Purpose of Visit");
                                        /* , "Facility Number",
                                          "Table/Room Number" */
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="hotlChckinDocDsplySze" style="min-width:70px !important;">                            
                                        <?php
                                        $valslctdArry = array("", "", "", "",
                                            "", "", "", "");
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
                            <div class="<?php echo $colClassType1; ?>">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a href="javascript:getHotlChckinDoc('previous', '#<?php echo $srcModule; ?>', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcModule=<?php echo $srcModule; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getHotlChckinDoc('next', '#<?php echo $srcModule; ?>', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcModule=<?php echo $srcModule; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>   
                        <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important">   
                            <div class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                                <?php if ($canAdd === true && $srcModule === "allmodules") { ?>   
                                    <div class="col-md-5" style="padding:0px 0px 0px 0px !important;">                      
                                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneHotlChckinDocForm(-1, 3, 'ShowDialog', 'Restaurant Order', 'RESTAURANT', '<?php echo $srcModule; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add New Restaurant Order">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            NEW ORDER
                                        </button>
                                    </div>  
                                <?php }
                                ?>
                                <div class="col-md-2" style="padding:5px 1px 0px 1px !important;">
                                    <div class = "form-check" style = "font-size: 12px !important;">
                                        <label class = "form-check-label">
                                            <?php
                                            $shwUnpaidOnlyChkd = "";
                                            if ($qShwUnpaidOnly == true) {
                                                $shwUnpaidOnlyChkd = "checked=\"true\"";
                                            }
                                            ?>
                                            <input type="checkbox" class="form-check-input" onclick="getHotlChckinDoc('', '#<?php echo $srcModule; ?>', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcModule=<?php echo $srcModule; ?>');" id="hotlChckinDocShwUnpaidOnly" name="hotlChckinDocShwUnpaidOnly"  <?php echo $shwUnpaidOnlyChkd; ?>>
                                            Finalized but Unpaid
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
                                            <input type="checkbox" class="form-check-input" onclick="getHotlChckinDoc('', '#<?php echo $srcModule; ?>', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcModule=<?php echo $srcModule; ?>');" id="hotlChckinDocShwUnpstdOnly" name="hotlChckinDocShwUnpstdOnly"  <?php echo $shwUnpstdOnlyChkd; ?>>
                                            On-going Transactions
                                        </label>
                                    </div>                            
                                </div>
                                <?php if ($vwOnlySelf == false) { ?>
                                    <div class = "col-md-2" style = "padding:5px 1px 0px 1px !important;">
                                        <div class = "form-check" style = "font-size: 12px !important;">
                                            <label class = "form-check-label">
                                                <?php
                                                $shwSelfOnlyChkd = "";
                                                if ($qShwSelfOnly == true) {
                                                    $shwSelfOnlyChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <input type="checkbox" class="form-check-input" onclick="getHotlChckinDoc('', '#<?php echo $srcModule; ?>', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcModule=<?php echo $srcModule; ?>');" id="hotlChckinDocShwSelfOnly" name="hotlChckinDocShwSelfOnly"  <?php echo $shwSelfOnlyChkd; ?>>
                                                Only Self-Created
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
                                                <input type="checkbox" class="form-check-input" onclick="getHotlChckinDoc('', '#<?php echo $srcModule; ?>', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcModule=<?php echo $srcModule; ?>');" id="hotlChckinDocShwMyBranchOnly" name="hotlChckinDocShwMyBranchOnly"  <?php echo $shwMyBranchOnlyChkd; ?>>
                                                My Branch
                                            </label>
                                        </div>                            
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="hotlChckinDocHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th style="max-width:35px;width:35px;">No.</th>
                                            <th style="max-width:30px;width:30px;">...</th>
                                            <th>Document Number/Type - Transaction Description</th>
                                            <th>Branch</th>
                                            <th style="text-align:center;max-width:30px;width:30px;">CUR.</th>	
                                            <th style="text-align:right;min-width:100px;width:100px;">Total Invoice Amount</th>
                                            <th style="text-align:right;min-width:100px;width:100px;">Total Amount Paid</th>
                                            <th style="text-align:right;min-width:100px;width:100px;">Amount Outstanding</th>
                                            <th style="max-width:105px;width:105px;">Invoice Status</th>
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
                                            <tr id="hotlChckinDocHdrsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>    
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View/Edit Document" 
                                                            onclick="getOneHotlChckinDocForm(<?php echo $row[0]; ?>, 3, 'ShowDialog', '<?php echo $row[14]; ?>', 'RESTAURANT', '<?php echo $srcModule; ?>');" style="padding:2px !important;" style="padding:2px !important;">                                                                
                                                                <?php
                                                                if ($canAdd === true) {
                                                                    ?>                                
                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        <?php } else { ?>
                                                            <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        <?php } ?>
                                                    </button>
                                                </td>
                                                <td class="lovtd"><?php echo $row[1] . " (" . $row[2] . ") " . $row[4] . " " . $row[5]; ?></td>
                                                <td class="lovtd"><?php echo $row[13]; ?></td>
                                                <td class="lovtd" style="text-align:center;font-weight: bold;color:black;"><?php echo $row[6]; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                    echo number_format((float) $row[7], 2);
                                                    ?>
                                                </td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                    echo number_format((float) $row[8], 2);
                                                    ?>
                                                </td>
                                                <?php
                                                $style1 = "color:red;";
                                                if (((float) $row[7]) <= 0) {
                                                    $style1 = "color:green;";
                                                }
                                                ?>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;<?php echo $style1; ?>"><?php
                                                    echo number_format((float) $row[9], 2);
                                                    ?>
                                                </td>
                                                <?php
                                                $style1 = "color:red;";
                                                $style2 = "color:black;"; //14,20
                                                if ($row[10] == "Approved") {
                                                    $style1 = "color:green;";
                                                } else if ($row[10] == "Cancelled") {
                                                    $style1 = "color:#0d0d0d;";
                                                }
                                                if ($row[15] == "Reserved" || $row[15] == "Checked-In" || $row[15] == "Ordered" || $row[15] == "Rented Out") {
                                                    $style2 = "color:green;";
                                                } else if ($row[15] == "Cancelled") {
                                                    $style2 = "color:#0d0d0d;";
                                                }
                                                ?>
                                                <td class="lovtd" style="font-weight:bold;"><span style="<?php echo $style1; ?>"><?php echo $row[10]; ?></span>
                                                    <span style="<?php echo $style2; ?>"> [<?php echo $row[15]; ?>]</span>
                                                </td> 
                                                <?php
                                                if ($canDel === true) {
                                                    ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction" onclick="delHotlChckinDoc('hotlChckinDocHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                            <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                        <input type="hidden" id="hotlChckinDocHdrsRow<?php echo $cntr; ?>_HdrID" name="hotlChckinDocHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                    </td>
                                                <?php } ?>
                                                <?php
                                                if ($canVwRcHstry === true) {
                                                    ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                        echo urlencode(encrypt1(($row[0] . "|hotl.checkins_hdr|check_in_id"),
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
                //New Rentals Form
                //var_dump($_POST);
                $sbmtdln_FcltyType = isset($_POST['ln_FcltyType']) ? cleanInputData($_POST['ln_FcltyType']) : '';
                $sbmtdln_SrvsTypID = isset($_POST['ln_SrvsTypID']) ? (float) cleanInputData($_POST['ln_SrvsTypID']) : -1;
                $sbmtdln_RoomID = isset($_POST['ln_RoomID']) ? (float) cleanInputData($_POST['ln_RoomID']) : -1;
                $srcModule = isset($_POST['srcModule']) ? cleanInputData($_POST['srcModule']) : 'allmodules';

                $sbmtdHotlChckinDocID = isset($_POST['sbmtdHotlChckinDocID']) ? (float) cleanInputData($_POST['sbmtdHotlChckinDocID']) : -1;
                $hotlChckinDocVchType = isset($_POST['hotlChckinDocVchType']) ? cleanInputData($_POST['hotlChckinDocVchType']) : "Restaurant Order";
                $hotlChckinFcltyType = "Restaurant Table";
                if (trim($sbmtdln_FcltyType) !== "") {
                    $hotlChckinFcltyType = trim($sbmtdln_FcltyType);
                }

                $sbmtdScmRtrnSrcDocID = isset($_POST['sbmtdScmRtrnSrcDocID']) ? cleanInputData($_POST['sbmtdScmRtrnSrcDocID']) : -1;
                $sbmtdScmSalesInvcID = isset($_POST['sbmtdScmSalesInvcID']) ? cleanInputData($_POST['sbmtdScmSalesInvcID']) : -1;
                $scmSalesInvcVchType = isset($_POST['scmSalesInvcVchType']) ? cleanInputData($_POST['scmSalesInvcVchType']) : "Sales Invoice";

                if (!$canAdd || ($sbmtdScmSalesInvcID > 0 && !$canEdt)) {
                    restricted();
                    exit();
                }
                $hotlChckinDocNum = "";
                $hotlChckinDocStrtDte = $gnrlTrnsDteDMYHMS;
                $hotlChckinDocEndDte = $gnrlTrnsDteDMYHMS;

                $hotlChckinDocSrvcTypID = -1;
                $hotlChckinDocSrvcTyp = "";
                $hotlChckinDocRmID = -1;
                $hotlChckinDocRmNum = "";

                if ($sbmtdln_SrvsTypID > 0 && $sbmtdln_RoomID > 0) {
                    $hotlChckinDocSrvcTypID = $sbmtdln_SrvsTypID;
                    $hotlChckinDocSrvcTyp = getSrvsTypName($hotlChckinDocSrvcTypID);
                    $hotlChckinDocRmID = $sbmtdln_RoomID;
                    $hotlChckinDocRmNum = getRoomName($hotlChckinDocRmID);
                }
                $hotlChckinDocNoAdlts = 0;
                $hotlChckinDocNoChldrn = 0;
                $hotlChckinDocSpnsrID = -1;
                $hotlChckinDocSpnsr = "";
                $hotlChckinDocSpnseeID = -1;
                $hotlChckinDocSpnsee = "";
                $hotlChckinDocArvlFrm = "";
                $hotlChckinDocPrcdTo = "";
                $hotlChckinDocOthrInfo = "";
                $hotlChckinDocPayTrms = "";
                $hotlChckinDocStatus = "";
                $hotlChckinDocCalcMthd = "0";
                if ($hotlChckinDocVchType == "Rent Out") {
                    $hotlChckinDocCalcMthd = "1";
                    $hotlChckinFcltyType = "Rental Item";
                    $hotlChckinDocStatus = "Rented Out";
                } else if ($hotlChckinDocVchType == "Check-In") {
                    $hotlChckinDocCalcMthd = "0";
                    $hotlChckinFcltyType = "Room/Hall";
                    $hotlChckinDocStatus = "Checked-In";
                } else if ($hotlChckinDocVchType == "Reservation") {
                    $hotlChckinDocCalcMthd = "0";
                    $hotlChckinFcltyType = "Room/Hall";
                    $hotlChckinDocStatus = "Reserved";
                } else if ($hotlChckinDocVchType == "Restaurant Order") {
                    $hotlChckinDocCalcMthd = "0";
                    $hotlChckinFcltyType = "Restaurant Table";
                    $hotlChckinDocStatus = "Ordered";
                }
                $hotlChckinDocSpnsrSiteID = -1;
                $hotlChckinDocSpnsrSite = "";
                $hotlChckinDocSpnseeSiteID = -1;
                $hotlChckinDocSpnseeSite = "";
                $hotlChckinDocPrntChcknID = -1;
                $hotlChckinDocPrntChcknTyp = "";
                $hotlChckinDocPrntChcknNo = "";

                $scmSalesInvcDfltTrnsDte = $gnrlTrnsDteDMYHMS;
                $scmSalesInvcCreator = $uName;
                $scmSalesInvcCreatorID = $usrID;
                $scmSalesInvcBrnchID = $brnchLocID;
                $scmSalesInvcBrnchNm = $brnchLoc;
                $gnrtdTrnsNo = "";
                $scmSalesInvcDesc = "";

                $srcSalesInvcDocID = $sbmtdScmRtrnSrcDocID;
                $srcSalesInvcDocTyp = "";
                $scmSalesInvcDocTmpltID = -1;
                $srcSalesInvcDocNum = "";

                $scmSalesInvcCstmr = "";
                $scmSalesInvcCstmrID = -1;
                $scmSalesInvcCstmrSite = "";
                $scmSalesInvcCstmrSiteID = -1;
                $scmSalesInvcCstmrClsfctn = "%Customer%";
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
                $scmSalesInvcDfltBalsAcntID = get_DfltCstmrRcvblsCashAcnt($scmSalesInvcCstmrID, $orgID);

                if ($sbmtdHotlChckinDocID > 0) {
                    $result = get_One_CheckinDt($sbmtdHotlChckinDocID);
                    if ($row = loc_db_fetch_array($result)) {
                        $hotlChckinDocNum = $row[1];
                        $hotlChckinDocVchType = $row[2];
                        $hotlChckinFcltyType = $row[3];
                        $hotlChckinDocStrtDte = $row[4];
                        $hotlChckinDocEndDte = $row[5];
                        $hotlChckinDocSrvcTypID = (float) $row[6];
                        $hotlChckinDocSrvcTyp = $row[7];
                        $hotlChckinDocRmID = (float) $row[8];
                        $hotlChckinDocRmNum = $row[9];
                        $hotlChckinDocNoAdlts = (float) $row[10];
                        $hotlChckinDocNoChldrn = (float) $row[11];
                        $scmSalesInvcCreatorID = (float) $row[19];
                        $hotlChckinDocPrntChcknID = (float) $row[30];
                        $hotlChckinDocPrntChcknTyp = $row[31];
                        $hotlChckinDocPrntChcknNo = $row[40];
                        $scmSalesInvcCreator = $row[35];
                        $hotlChckinDocSpnsrID = (float) $row[12];
                        $hotlChckinDocSpnsr = $row[36];
                        $hotlChckinDocSpnsrSiteID = (float) $row[13];
                        $hotlChckinDocSpnsrSite = $row[37];
                        $hotlChckinDocSpnseeID = (float) $row[14];
                        $hotlChckinDocSpnsee = $row[38];
                        $hotlChckinDocSpnseeSiteID = (float) $row[15];
                        $hotlChckinDocSpnseeSite = $row[39];
                        $scmSalesInvcPayMthd = $row[24];
                        $scmSalesInvcPayMthdID = (float) $row[23];
                        $scmSalesInvcInvcCur = $row[26];
                        $scmSalesInvcInvcCurID = $row[25];
                        $scmSalesInvcExRate = (float) $row[27];
                        if ($scmSalesInvcExRate == 0) {
                            $scmSalesInvcExRate = 1;
                        }
                        $sbmtdScmSalesInvcID = (float) $row[21];
                        $gnrtdTrnsNo = $row[22];
                        $scmSalesInvcVchType = $row[29];
                        $rqStatus = $row[28];
                        $hotlChckinDocArvlFrm = $row[16];
                        $hotlChckinDocPrcdTo = $row[17];
                        $hotlChckinDocOthrInfo = $row[18];
                        $hotlChckinDocPayTrms = $row[34];
                        $scmSalesInvcPayTerms = $row[34];
                        $hotlChckinDocStatus = $row[20];
                        $hotlChckinDocUseNights = $row[33] == "1" ? true : false;
                        $hotlChckinDocCalcMthd = $row[33];
                        if ($hotlChckinDocOthrInfo == "") {
                            $hotlChckinDocOthrInfo = $hotlChckinDocVchType . ": Facility No. " . $hotlChckinDocRmNum . " for " . $hotlChckinDocSpnsee . " from " .
                                    $hotlChckinDocStrtDte . " to " . $hotlChckinDocEndDte . " (" . $hotlChckinDocNum . ")";
                        }
                    }
                } else if ($scmSalesInvcDfltBalsAcntID > 0) {
                    $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
                    if ($usrTrnsCode == "") {
                        $usrTrnsCode = "XX";
                    }
                    $dte = date('ymd');
                    $docTypes = array("Rent Out", "Check-In", "Reservation", "Restaurant Order");
                    $docTypPrfxs = array("RT", "CI", "RS", "RO");

                    $docTypPrfx = $docTypPrfxs[findArryIdx($docTypes, $hotlChckinDocVchType)];
                    $gnrtdTrnsNo1 = $docTypPrfx . "-" . $usrTrnsCode . "-" . $dte . "-";
                    $gnrtdTrnsNo = $gnrtdTrnsNo1 . str_pad(((getRecCount_LstNum("hotl.checkins_hdr", "doc_num", "check_in_id",
                                            $gnrtdTrnsNo1 . "%") + 1) . ""), 3, '0', STR_PAD_LEFT);
                    $hotlChckinDocNum = $gnrtdTrnsNo;
                    $scmSalesInvcDfltBalsAcnt = getAccntNum($scmSalesInvcDfltBalsAcntID) . "." . getAccntName($scmSalesInvcDfltBalsAcntID);
                    $scmSalesInvcInvcCurID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id",
                                    $scmSalesInvcDfltBalsAcntID);
                    if ($scmSalesInvcInvcCurID > 0) {
                        $scmSalesInvcInvcCur = getPssblValNm($scmSalesInvcInvcCurID);
                    }
                    $sbmtdHotlChckinDocID = createCheckIn($gnrtdTrnsNo, $hotlChckinDocVchType, $hotlChckinDocStrtDte, $hotlChckinDocEndDte,
                            $hotlChckinDocSrvcTypID, $hotlChckinDocRmID, $hotlChckinDocNoAdlts, $hotlChckinDocNoChldrn,
                            $hotlChckinDocSpnsrID, $hotlChckinDocSpnsrSiteID, $hotlChckinDocSpnseeID, $hotlChckinDocSpnseeSiteID,
                            $hotlChckinDocArvlFrm, $hotlChckinDocPrcdTo, $hotlChckinDocOthrInfo, $hotlChckinFcltyType, $hotlChckinDocStatus,
                            $hotlChckinDocPrntChcknID, $hotlChckinDocPrntChcknTyp, $hotlChckinDocCalcMthd);
                }
                if ($sbmtdScmSalesInvcID > 0) {
                    $result = get_One_SalesInvcDocHdr($sbmtdScmSalesInvcID);
                    if ($row = loc_db_fetch_array($result)) {
                        $scmSalesInvcDfltTrnsDte = $row[1];
                        $scmSalesInvcDesc = $row[6];
                        $srcSalesInvcDocID = $row[7];
                        $srcSalesInvcDocTyp = $row[16];
                        $srcSalesInvcDocNum = $row[37];
                        $scmSalesInvcCstmr = $row[9];
                        $scmSalesInvcCstmrID = $row[8];
                        $scmSalesInvcCstmrSite = $row[11];
                        $scmSalesInvcCstmrSiteID = $row[10];
                        $rqStatusNext = $row[13];
                        $rqstatusColor = "red";
                        $scmSalesInvcPayTerms = $row[15];
                        $scmSalesInvcTtlAmnt = (float) $row[14];
                        $scmSalesInvcAppldAmnt = (float) $row[34];
                        $scmSalesInvcPaidAmnt = $row[19];
                        $scmSalesInvcGLBatch = $row[21];
                        $scmSalesInvcGLBatchID = $row[20];
                        $scmSalesInvcCstmrInvcNum = $row[22];
                        $scmSalesInvcDocTmplt = $row[23];
                        $scmSalesInvcEvntRgstr = "";
                        $scmSalesInvcEvntRgstrID = $row[26];
                        $scmSalesInvcEvntCtgry = $row[27];
                        $scmSalesInvcEvntDocTyp = $row[28];
                        $scmSalesInvcDfltBalsAcntID = $row[29];
                        $scmSalesInvcDfltBalsAcnt = $row[30];
                        $scmSalesInvcIsPstd = $row[31];
                        $scmSalesInvcAllwDues = $row[38];
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
                } else if ($scmSalesInvcDfltBalsAcntID > 0) {
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
                    }
                    $sbmtdScmSalesInvcID = createSalesDocHdr($orgID, $gnrtdTrnsNo, $hotlChckinDocOthrInfo, $scmSalesInvcVchType,
                            substr($hotlChckinDocStrtDte, 0, 11), $hotlChckinDocPayTrms, $hotlChckinDocSpnsrID, $hotlChckinDocSpnsrSiteID,
                            $rqStatus, $rqStatusNext, $srcSalesInvcDocID, $scmSalesInvcDfltBalsAcntID, $scmSalesInvcPayMthdID,
                            $scmSalesInvcInvcCurID, $scmSalesInvcExRate, $sbmtdHotlChckinDocID, $hotlChckinDocVchType,
                            $scmSalesInvcAutoBals, $scmSalesInvcEvntRgstrID, $scmSalesInvcEvntCtgry, $scmSalesInvcAllwDues,
                            $scmSalesInvcEvntDocTyp, $srcSalesInvcDocTyp, $scmSalesInvcBrnchID);
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

                if ($scmSalesInvcAllwDues == "1") {
                    $reportName = getEnbldPssblValDesc("Sales Invoice - Dues", getLovID("Document Custom Print Process Names"));
                }

                $reportTitle = str_replace("Pro-Forma Invoice", "Payment Voucher", $scmSalesInvcVchType);
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
                ?>
                <form class="form-horizontal" id="oneScmSalesInvcEDTForm">
                    <fieldset class="basic_person_fs2" style="min-height:50px !important;">
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Doc. No./Name:</label>
                                    </div>
                                    <div class="col-md-4" style="padding:0px 1px 0px 15px;">
                                        <input type="text" class="form-control" aria-label="..." id="sbmtdHotlChckinDocID" name="sbmtdHotlChckinDocID" value="<?php echo $sbmtdHotlChckinDocID; ?>" readonly="true">
                                        <input type="hidden" id="hotlChckinDocSrvcTypID" value="<?php echo $hotlChckinDocSrvcTypID; ?>">
                                        <!--<input type="hidden" id="hotlChckinDocSrvcTyp" value="<?php echo $hotlChckinDocSrvcTyp; ?>">-->
                                        <input type="hidden" id="hotlChckinDocRmID" value="<?php echo $hotlChckinDocRmID; ?>">
                                        <!--<input type="hidden" id="hotlChckinDocRmNum" value="<?php echo $hotlChckinDocRmNum; ?>">
                                        <input type="hidden" id="hotlChckinDocNoAdlts" value="<?php echo $hotlChckinDocNoAdlts; ?>">
                                        <input type="hidden" id="hotlChckinDocNoChldrn" value="<?php echo $hotlChckinDocNoChldrn; ?>">-->
                                        <input type="hidden" id="hotlChckinDocSpnseeID" value="<?php echo $hotlChckinDocSpnseeID; ?>">
                                        <!--<input type="hidden" id="hotlChckinDocSpnsee" value="<?php echo $hotlChckinDocSpnsee; ?>">-->
                                        <input type="hidden" id="hotlChckinDocSpnseeSiteID" value="<?php echo $hotlChckinDocSpnseeSiteID; ?>">
                                        <input type="hidden" id="hotlChckinDocSpnseeSite" value="<?php echo $hotlChckinDocSpnseeSite; ?>">
                                        <!--<input type="hidden" id="hotlChckinDocArvlFrm" value="<?php echo $hotlChckinDocArvlFrm; ?>">
                                        <input type="hidden" id="hotlChckinDocPrcdTo" value="<?php echo $hotlChckinDocPrcdTo; ?>">-->
                                        <input type="hidden" id="hotlChckinDocPrntChcknID" value="<?php echo $hotlChckinDocPrntChcknID; ?>">
                                        <input type="hidden" id="hotlChckinDocPrntChcknTyp" value="<?php echo $hotlChckinDocPrntChcknTyp; ?>">
                                    </div>
                                    <div class="col-md-4" style="padding:0px 15px 0px 1px;">
                                        <input type="text" class="form-control" aria-label="..." id="hotlChckinDocNum" name="hotlChckinDocNum" value="<?php echo $hotlChckinDocNum; ?>" readonly="true">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Transaction Date:</label>
                                    </div>
                                    <div class="col-md-8 input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="padding:0px 15px 0px 15px !important;">
                                        <input class="form-control" size="16" type="text" id="hotlChckinDocStrtDte" name="hotlChckinDocStrtDte" value="<?php echo $hotlChckinDocStrtDte; ?>" placeholder="Start Date" <?php echo $mkReadOnly; ?>>
                                        <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="form-group" style="display:none;">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">End Date:</label>
                                    </div>
                                    <div class="col-md-8 input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="padding:0px 15px 0px 15px !important;">
                                        <input class="form-control" size="16" type="text" id="hotlChckinDocEndDte" name="hotlChckinDocEndDte" value="<?php echo $hotlChckinDocEndDte; ?>" placeholder="End Date" <?php echo $mkReadOnly; ?>>
                                        <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Doc./Facility Type:</label>
                                    </div>
                                    <div class="col-md-4" style="padding:0px 1px 0px 15px;">
                                        <input type="text" class="form-control" aria-label="..." id="hotlChckinDocVchType" name="hotlChckinDocVchType" value="<?php echo $hotlChckinDocVchType; ?>" readonly="true">
                                    </div>
                                    <div class="col-md-4" style="padding:0px 15px 0px 1px;">
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="hotlChckinFcltyType">
                                            <?php
                                            $valslctdArry = array("");
                                            $srchInsArrys = array("Restaurant Table");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                if ($hotlChckinFcltyType == $srchInsArrys[$z]) {
                                                    $valslctdArry[$z] = "selected";
                                                }
                                                ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="hotlChckinDocSrvcTyp" class="control-label col-md-4">Table Type:</label>
                                    <div  class="col-md-4" style="padding:0px 1px 0px 15px;">
                                        <div class="input-group" style="width:100% !important;">
                                            <input type="text" class="form-control" aria-label="..." id="hotlChckinDocSrvcTyp" name="hotlChckinDocSrvcTyp" value="<?php echo $hotlChckinDocSrvcTyp; ?>" readonly="true" style="width:100% !important;">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Hospitality Service Types', 'allOtherInputOrgID', 'hotlChckinFcltyType', '', 'radio', true, '', 'hotlChckinDocSrvcTypID', 'hotlChckinDocSrvcTyp', 'clear', 1, '', function () {});">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div> 
                                    </div> 
                                    <div  class="col-md-4" style="padding:0px 15px 0px 1px;">
                                        <div class="input-group" style="width:100% !important;">
                                            <input type="text" class="form-control" aria-label="..." id="hotlChckinDocRmNum" name="hotlChckinDocRmNum" value="<?php echo $hotlChckinDocRmNum; ?>" readonly="true" style="width:100% !important;">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getRoomNumLovsPage('hotlChckinDocStrtDte', 'hotlChckinDocEndDte', 'myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Hospitality Facility Numbers', 'hotlChckinDocSrvcTypID', '', '', 'radio', true, '', 'hotlChckinDocRmID', 'hotlChckinDocRmNum', 'clear', 1, '', function () {});">
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
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="hotlChckinDocSpnsr" class="control-label col-md-4">Customer:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="hotlChckinDocSpnsr" name="hotlChckinDocSpnsr" value="<?php echo $hotlChckinDocSpnsr; ?>" readonly="true">
                                            <input type="hidden" id="hotlChckinDocSpnsrID" value="<?php echo $hotlChckinDocSpnsrID; ?>">
                                            <input type="hidden" id="scmSalesInvcCstmrClsfctn" value="<?php echo $scmSalesInvcCstmrClsfctn; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getCstmrSpplrForm(-1, 'Create/Edit Supplier', 'ShowDialog', function () {}, 'hotlChckinDocSpnsrID');" data-toggle="tooltip" title="Create/Edit Customer">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </label>
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Business/Trade Partners', 'allOtherInputOrgID', '', 'scmSalesInvcCstmrClsfctn', 'radio', true, '', 'hotlChckinDocSpnsrID', 'hotlChckinDocSpnsr', 'clear', 1, '', function () {
                                                                        getHotlRcvblsAcntInfo();
                                                                    });" data-toggle="tooltip" title="Existing Client/Vendor">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>  
                                <div class="form-group" style="display:none;">
                                    <label for="scmSalesInvcCstmrSite" class="control-label col-md-4">Site:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="hotlChckinDocSpnsrSite" name="hotlChckinDocSpnsrSite" value="<?php echo $hotlChckinDocSpnsrSite; ?>" readonly="true">
                                            <input class="form-control" type="hidden" id="hotlChckinDocSpnsrSiteID" value="<?php echo $hotlChckinDocSpnsrSiteID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Customer/Supplier Sites', 'hotlChckinDocSpnsrSiteID', '', '', 'radio', true, '', 'hotlChckinDocSpnsrSiteID', 'hotlChckinDocSpnsrSite', 'clear', 0, '');" data-toggle="tooltip" title="">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>  
                                <div class="form-group" style="display:none;">
                                    <label for="hotlChckinDocSpnsee" class="control-label col-md-4">Occupant:</label>
                                    <div  class="col-md-8"> 
                                        <div class="input-group" style="width:100% !important;">
                                            <input type="text" class="form-control" aria-label="..." id="hotlChckinDocSpnsee" name="hotlChckinDocSpnsee" value="<?php echo $hotlChckinDocSpnsee; ?>" readonly="true" style="width:100% !important;">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Business/Trade Partners', 'allOtherInputOrgID', '', 'scmSalesInvcCstmrClsfctn', 'radio', true, '', 'hotlChckinDocSpnseeID', 'hotlChckinDocSpnsee', 'clear', 1, '', function () {
                                                                        getHotlOccpntRcvblsAcntInfo();
                                                                    });">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>  
                                <div class="form-group" style="display:none;">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Place From/To:</label>
                                    </div>
                                    <div class="col-md-4" style="padding:0px 1px 0px 15px;">
                                        <input type="text" class="form-control" aria-label="..." id="hotlChckinDocArvlFrm" name="hotlChckinDocArvlFrm" value="<?php echo $hotlChckinDocArvlFrm; ?>" <?php echo $mkRmrkReadOnly; ?>>
                                    </div>
                                    <div class="col-md-4" style="padding:0px 15px 0px 1px;">
                                        <input type="text" class="form-control" aria-label="..." id="hotlChckinDocPrcdTo" name="hotlChckinDocPrcdTo" value="<?php echo $hotlChckinDocPrcdTo; ?>" <?php echo $mkRmrkReadOnly; ?>>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Remark / Narration:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group"  style="width:100%;">
                                            <textarea class="form-control" rows="3" cols="20" id="hotlChckinDocOthrInfo" name="hotlChckinDocOthrInfo" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $hotlChckinDocOthrInfo; ?></textarea>
                                            <input class="form-control" type="hidden" id="hotlChckinDocOthrInfo1" value="<?php echo $hotlChckinDocOthrInfo; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('hotlChckinDocOthrInfo');" style="max-width:30px;width:30px;">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Persons Seated:</label>
                                    </div>
                                    <div class="col-md-8" style="padding:0px 15px 0px 15px;">
                                        <input type="number" class="form-control" aria-label="..." id="hotlChckinDocNoAdlts" name="hotlChckinDocNoAdlts" value="<?php echo $hotlChckinDocNoAdlts; ?>" <?php echo $mkRmrkReadOnly; ?> title="Number of Persons Seated">
                                    </div>
                                    <div class="col-md-4" style="padding:0px 15px 0px 1px;display:none;">
                                        <input type="number" class="form-control" aria-label="..." id="hotlChckinDocNoChldrn" name="hotlChckinDocNoChldrn" value="<?php echo $hotlChckinDocNoChldrn; ?>" <?php echo $mkRmrkReadOnly; ?> title="Number of Children">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="hotlChckinDocPrntChcknNo" class="control-label col-md-4">Check-In No.:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="hotlChckinDocPrntChcknNo" name="hotlChckinDocPrntChcknNo" value="<?php echo $hotlChckinDocPrntChcknNo; ?>" readonly="true">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Hospitality Active Check-Ins', 'allOtherInputOrgID', '', '', 'radio', true, '', 'hotlChckinDocPrntChcknID', 'hotlChckinDocPrntChcknNo', 'clear', 1, '');" data-toggle="tooltip" title="Linked Parent Check-In Document">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class = "col-md-4">
                                <div class="form-group" style="display:none;">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Calc. Method:</label>
                                    </div>
                                    <div class="col-md-3" style="padding:0px 1px 0px 15px;">
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="hotlChckinDocCalcMthd">
                                            <?php
                                            $valslctdArry = array("", "", "", "", "", "");
                                            $srchInsArrys = array("Nights", "Days", "Hours", "Months", "Years", "Term");
                                            $srchInsArrys1 = array("0", "1", "2", "3", "4", "5");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                if ($hotlChckinDocCalcMthd == $srchInsArrys1[$z]) {
                                                    $valslctdArry[$z] = "selected";
                                                }
                                                ?>
                                                <option value="<?php echo $srchInsArrys1[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                            <?php } ?>
                                        </select> 
                                    </div>
                                    <div class="col-md-2" style="padding:0px 1px 0px 1px;">
                                        <input type="text" class="form-control" aria-label="..." id="sbmtdScmSalesInvcID" name="sbmtdScmSalesInvcID" value="<?php echo $sbmtdScmSalesInvcID; ?>" readonly="true">
                                    </div>
                                    <div class="col-md-3" style="padding:0px 15px 0px 1px;">
                                        <input type="text" class="form-control" aria-label="..." id="scmSalesInvcDocNum" name="scmSalesInvcDocNum" value="<?php echo $gnrtdTrnsNo; ?>" readonly="true">
                                    </div>
                                </div>   
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
                                        <label style="margin-bottom:0px !important;">Exch. Rate:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                                <span class="" style="font-size: 20px !important;<?php echo $scmSalesInvcPaidStyle; ?>" id="scmSalesInvcInvcCur4"><?php echo $scmSalesInvcInvcCur; ?></span>
                                                <span class="" style="font-size: 20px !important;<?php echo $scmSalesInvcPaidStyle; ?>" id="scmSalesInvcFuncCur"><?php echo "&nbsp;to " . $fnccurnm; ?></span>
                                            </label>
                                            <input type="text" class="form-control" aria-label="..." id="scmSalesInvcExRate" name="scmSalesInvcExRate" value="<?php
                                            echo number_format(1 / $scmSalesInvcExRate, 4);
                                            ?>" style="font-size: 18px !important;font-weight:bold;width:100%;" <?php echo $mkReadOnly; ?> title="Exchange Rate (Multiplier)">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Amount Received:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                                <span class="" style="font-size: 20px !important;<?php echo $scmSalesInvcPaidStyle; ?>" id="scmSalesInvcInvcCur2"><?php echo $scmSalesInvcInvcCur; ?></span>
                                            </label>
                                            <input class="form-control" type="text" id="scmSalesInvcPaidAmnt" value="<?php
                                            echo number_format($scmSalesInvcPaidAmnt, 2);
                                            ?>" style="font-weight:bold;width:100%;font-size:18px !important;<?php echo $scmSalesInvcPaidStyle; ?>" onchange="fmtAsNumber('scmSalesInvcPaidAmnt');" readonly="true"/>
                                            <label data-toggle="tooltip" title="History of Payments" class="btn btn-primary btn-file input-group-addon" onclick="getOneAccbPymntsHstryForm(<?php echo $sbmtdScmRcvblsInvcID; ?>, 103, 'ReloadDialog', <?php echo $sbmtdHotlChckinDocID; ?>, 'Sales Invoice-Hospitality', 'Customer Payments');">
                                                <img src="cmn_images/openfileicon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Outstanding Bals:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                                <span class="" style="font-size: 20px !important;<?php echo $scmSalesInvcOustndngStyle; ?>" id="scmSalesInvcInvcCur3"><?php echo $scmSalesInvcInvcCur; ?></span>
                                            </label>
                                            <input class="form-control" type="text" id="scmSalesInvcOustndngAmnt" value="<?php
                                            echo number_format($scmSalesInvcOustndngAmnt, 2);
                                            ?>" 
                                                   style="font-weight:bold;width:100%;font-size:18px !important;<?php echo $scmSalesInvcOustndngStyle; ?>" onchange="fmtAsNumber('scmSalesInvcOustndngAmnt');"  readonly="true"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Status:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="hidden" id="scmSalesInvcApprvlStatus" value="<?php echo $rqStatus; ?>">  
                                        <span  class="btn btn-default" style="white-space: normal !important;word-wrap:break-word !important; color:<?php echo $rqstatusColor; ?>;font-weight: bold;height:34px;font-size:11px;width:100% !important;" id="myScmSalesInvcStatusBtn">
                                            <?php
                                            echo str_replace(" [" . $rqStatus . "]", "",
                                                    $rqStatus . ($scmSalesInvcIsPstd == "1" ? " [Posted]" : " [Not Posted]") . " [" . $hotlChckinDocStatus . "]");
                                            ?>
                                        </span>
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
                                                            </td>
                                                            <td class=\"lovtd\" style=\"text-align: right;display:none;\">
                                                                <input type=\"text\" class=\"form-control rqrdFld jbDetAccRate2\" aria-label=\"...\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_RentedQty\" name=\"oneScmSalesInvcSmryRow_WWW123WWW_RentedQty\" value=\"1\" onkeypress=\"gnrlFldKeyPress(event, 'oneScmSalesInvcSmryRow_WWW123WWW_RentedQty', 'oneScmSalesInvcSmryLinesTable', 'jbDetAccRate2');\" style=\"width:100% !important;text-align: right;\" onchange=\"calcAllScmSalesInvcSmryTtl();\">                                                    
                                                            </td>                                
                                                            <td class=\"lovtd\" style=\"text-align:center;\">
                                                                <div class=\"form-group form-group-sm\">
                                                                    <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                        <label class=\"form-check-label\">
                                                                            <input type=\"checkbox\" class=\"form-check-input\" id=\"oneScmSalesInvcSmryRow_WWW123WWW_IsDlvrd\" name=\"oneScmSalesInvcSmryRow_WWW123WWW_IsDlvrd\">
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </td> 
                                                            <td class=\"lovtd\" style=\"text-align: center;\">
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
                                                            <button id="addNwScmSalesInvcSmryBtn" type="button" class="btn btn-default hideNotice" style="margin-bottom: 1px;height:30px;" onclick="insertNewScmSalesInvcRows('oneScmSalesInvcSmryLinesTable', 0, '<?php echo $nwRowHtml33; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Sales Line">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>                                 
                                                        <?php } ?>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneScmSalesInvcDocsForm(<?php echo $sbmtdScmSalesInvcID; ?>, 20);" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                                            <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button> 
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneHotlChckinDocForm(<?php echo $sbmtdHotlChckinDocID; ?>, 3, 'ReloadDialog', '<?php echo $hotlChckinDocVchType; ?>', 'RESTAURANT', '<?php echo $srcModule; ?>');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;"  onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="width:100% !important;">
                                                            <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Print
                                                        </button>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;"  onclick="getSilentRptsRnSts(<?php echo $rptID1; ?>, -1, '<?php echo $paramStr1; ?>');" style="width:100% !important;">
                                                            <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            POS
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
                                                                    <input class="form-control" type="hidden" id="scmSalesLnkdPrsnID" value="-1">
                                                                    <input type="hidden" id="scmSalesInvcCstmrClsfctn" value="%Customer%">
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
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveHotlChckinDocForm('<?php echo $fnccurnm; ?>', 0, 'NORMAL', 'RESTAURANT', '<?php echo $srcModule; ?>');"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>    
                                                                <?php } ?>
                                                                <?php if ($canRvwApprvDocs) { ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveHotlChckinDocForm('<?php echo $fnccurnm; ?>', 2, 'NORMAL', 'RESTAURANT', '<?php echo $srcModule; ?>');" data-toggle="tooltip" data-placement="bottom" title="Finalize Document">
                                                                        <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Finalize
                                                                    </button>
                                                                    <?php
                                                                }
                                                                if ($canPayDocs === true && $scmSalesInvcCstmrID > 0 && ($scmSalesInvcVchType
                                                                        == "Sales Invoice" || $scmSalesInvcVchType == "Sales Order" || $scmSalesInvcVchType
                                                                        == "Pro-Forma Invoice")) {
                                                                    ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAccbPayInvcForm(<?php echo $sbmtdScmRcvblsInvcID; ?>, 'Customer Payments', 'ShowDialog', -1, <?php echo $sbmtdHotlChckinDocID; ?>, 'Sales Invoice-Hospitality', 'XX_UNDEFINED_XX', '', 'hotlChckinDocSpnsrID', 'scmSalesInvcInvcCur');" data-toggle="tooltip" data-placement="bottom" title="Take Deposit">
                                                                        <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Take Deposit
                                                                    </button>
                                                                    <?php
                                                                }
                                                            } else if ($rqStatus == "Approved") {
                                                                if ($canPayDocs === true && $scmSalesInvcOustndngAmnt > 0 && $scmSalesInvcVchType
                                                                        == "Sales Invoice") {
                                                                    ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAccbPayInvcForm(<?php echo $sbmtdScmRcvblsInvcID; ?>, 'Customer Payments', 'ShowDialog', -1, <?php echo $sbmtdHotlChckinDocID; ?>, 'Sales Invoice-Hospitality');" data-toggle="tooltip" data-placement="bottom" title="Pay Document">
                                                                        <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Pay Invoice
                                                                    </button>
                                                                <?php } ?>
                                                                <?php
                                                                if ($cancelDocs === true) {
                                                                    ?>
                                                                    <button id="fnlzeRvrslScmSalesInvcBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveHotlChckinDocRvrslForm('<?php echo $fnccurnm; ?>', 1, 'NORMAL', 'RESTAURANT', '<?php echo $srcModule; ?>');"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Cancel Document&nbsp;</button>  
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
                                                                <th style="max-width:70px;width:70px;text-align: right;">QTY</th>
                                                                <th style="max-width:55px;text-align: center;">UOM.</th>
                                                                <th style="max-width:150px;width:140px;text-align: right;">Unit Selling Price</th>
                                                                <th style="max-width:140px;width:120px;text-align: right;">Total Amount</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">CS</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">TX</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">DC</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">EX</th>
                                                                <th style="min-width:120px;">Extra Description</th>
                                                                <th style="max-width:70px;width:70px;text-align: right;display:none;">Rented Item QTY</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">Given Out</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>   
                                                            <?php
                                                            $cntr = 0;
                                                            $resultRw = get_SalesInvcDocDet2($sbmtdScmSalesInvcID);
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
                                                                        <td class="lovtd" style="text-align: right;display:none;">
                                                                            <input type="text" class="form-control rqrdFld jbDetAccRate2" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_RentedQty" name="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_RentedQty" value="<?php
                                                                            echo $ln_RentedQty;
                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_RentedQty', 'oneScmSalesInvcSmryLinesTable', 'jbDetAccRate2');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllScmSalesInvcSmryTtl();">                                                    
                                                                        </td>                                  
                                                                        <td class="lovtd" style="text-align:center;">
                                                                            <?php
                                                                            $isChkd = "";
                                                                            if ($trsctnLnIsDlvrd == "1") {
                                                                                $isChkd = "checked=\"true\"";
                                                                            }
                                                                            ?>
                                                                            <div class="form-group form-group-sm">
                                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                                    <label class="form-check-label">
                                                                                        <input type="checkbox" class="form-check-input" id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_IsDlvrd" name="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_IsDlvrd" <?php echo $isChkd ?>>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </td>  
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
                                                                $ln_OthrMdlID = $sbmtdHotlChckinDocID;
                                                                $ln_OthrMdlTyp = $hotlChckinDocVchType;
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
                                                                    <td class="lovtd" style="text-align: right;display:none;">
                                                                        <input type="text" class="form-control rqrdFld jbDetAccRate2" aria-label="..." id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_RentedQty" name="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_RentedQty" value="<?php
                                                                        echo $ln_RentedQty;
                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmSalesInvcSmryRow<?php echo $cntr; ?>_RentedQty', 'oneScmSalesInvcSmryLinesTable', 'jbDetAccRate2');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllScmSalesInvcSmryTtl();">                                                    
                                                                    </td>                                  
                                                                    <td class="lovtd" style="text-align:center;">
                                                                        <?php
                                                                        $isChkd = "";
                                                                        if ($trsctnLnIsDlvrd == "1") {
                                                                            $isChkd = "checked=\"true\"";
                                                                        }
                                                                        ?>
                                                                        <div class="form-group form-group-sm">
                                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                                <label class="form-check-label">
                                                                                    <input type="checkbox" class="form-check-input" id="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_IsDlvrd" name="oneScmSalesInvcSmryRow<?php echo $cntr; ?>_IsDlvrd" <?php echo $isChkd ?>>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </td>  
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
                                                                <th style="display:none;">&nbsp;</th>
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
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Liability Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'scmSalesInvcDfltBalsAcntID', 'scmSalesInvcDfltBalsAcnt', 'clear', 1, '', function () {});">
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
            }
        }
    }
}
?>