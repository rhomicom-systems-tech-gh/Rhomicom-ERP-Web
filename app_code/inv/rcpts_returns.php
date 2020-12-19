<?php
$canAdd = test_prmssns($dfltPrvldgs[24], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[88], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[25], $mdlNm);
$canRvwApprvDocs = $canEdt;
$cancelDocs = $canEdt;

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Doc Header */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteCnsgnRtrnHdrNDet($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Doc Header Line */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteCnsgnRtrnLine($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
                /* Delete Doc Attachment Line */
                $pKeyID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
                $pKeyNm = isset($_POST['docTrnsNum']) ? cleanInputData($_POST['docTrnsNum']) : "";
                if ($canDel) {
                    echo deleteCnsgnRcptDoc($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Consignment Receipt Transaction
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $sbmtdScmCnsgnRtrnID = isset($_POST['sbmtdScmCnsgnRtrnID']) ? (float) cleanInputData($_POST['sbmtdScmCnsgnRtrnID']) : -1;
                $scmCnsgnRtrnDocNum = isset($_POST['scmCnsgnRtrnDocNum']) ? cleanInputData($_POST['scmCnsgnRtrnDocNum']) : "";
                $scmCnsgnRtrnDfltTrnsDte = isset($_POST['scmCnsgnRtrnDfltTrnsDte']) ? cleanInputData($_POST['scmCnsgnRtrnDfltTrnsDte']) : '';
                $scmCnsgnRtrnType = isset($_POST['scmCnsgnRtrnType']) ? cleanInputData($_POST['scmCnsgnRtrnType']) : '';
                $scmCnsgnRtrnInvcCur = isset($_POST['scmCnsgnRtrnInvcCur']) ? cleanInputData($_POST['scmCnsgnRtrnInvcCur']) : $fnccurnm;
                $curLovID = getLovID("Currencies");
                $scmCnsgnRtrnInvcCurID = getPssblValID($scmCnsgnRtrnInvcCur, $curLovID);
                $scmCnsgnRtrnTtlAmnt = isset($_POST['scmCnsgnRtrnTtlAmnt']) ? (float) cleanInputData($_POST['scmCnsgnRtrnTtlAmnt']) : 0;
                $scmCnsgnRtrnExRate = isset($_POST['scmCnsgnRtrnExRate']) ? (float) cleanInputData($_POST['scmCnsgnRtrnExRate']) : 1;
                $funcExchRate = round(get_LtstExchRate($scmCnsgnRtrnInvcCurID, $fnccurid, $scmCnsgnRtrnDfltTrnsDte), 4);
                if ($scmCnsgnRtrnExRate == 0 || $scmCnsgnRtrnExRate == 1) {
                    $scmCnsgnRtrnExRate = $funcExchRate;
                }
                $scmCnsgnRtrnSpplrID = isset($_POST['scmCnsgnRtrnSpplrID']) ? (float) cleanInputData($_POST['scmCnsgnRtrnSpplrID']) : -1;
                $scmCnsgnRtrnSpplrSiteID = isset($_POST['scmCnsgnRtrnSpplrSiteID']) ? (float) cleanInputData($_POST['scmCnsgnRtrnSpplrSiteID']) : -1;
                $scmCnsgnRtrnDfltBalsAcntID = isset($_POST['scmCnsgnRtrnDfltBalsAcntID']) ? (float) cleanInputData($_POST['scmCnsgnRtrnDfltBalsAcntID']) : -1;
                $scmCnsgnRtrnDesc = isset($_POST['scmCnsgnRtrnDesc']) ? cleanInputData($_POST['scmCnsgnRtrnDesc']) : '';
                $srcCnsgnRtrnDocNum = isset($_POST['srcCnsgnRtrnDocNum']) ? cleanInputData($_POST['srcCnsgnRtrnDocNum']) : '';
                $srcCnsgnRtrnDocID = isset($_POST['srcCnsgnRtrnDocID']) ? (float) cleanInputData($_POST['srcCnsgnRtrnDocID']) : -1;

                $slctdDetTransLines = isset($_POST['slctdDetTransLines']) ? cleanInputData($_POST['slctdDetTransLines']) : '';
                $shdSbmt = isset($_POST['shdSbmt']) ? (int) cleanInputData($_POST['shdSbmt']) : 0;
                if (strlen($scmCnsgnRtrnDesc) > 499) {
                    $scmCnsgnRtrnDesc = substr($scmCnsgnRtrnDesc, 0, 499);
                }
                $exitErrMsg = "";
                if ($scmCnsgnRtrnDocNum == "") {
                    $exitErrMsg .= "Please enter Document Number!<br/>";
                }
                if ($scmCnsgnRtrnDfltTrnsDte == "") {
                    $exitErrMsg .= "Document Date cannot be empty!<br/>";
                }
                if ($scmCnsgnRtrnDesc == "") {
                    $exitErrMsg .= "Please enter Description!<br/>";
                }
                /*
                  if ($scmCnsgnRtrnType == "") {
                  $exitErrMsg .= "Document Type cannot be empty!<br/>";
                  }
                  if ($scmCnsgnRtrnCstmrID <= 0) {
                  $exitErrMsg .= "Customer Name cannot be empty!<br/>";
                  }
                  if ($scmCnsgnRtrnCstmrSiteID <= 0) {
                  $exitErrMsg .= "Customer Site cannot be empty!<br/>";
                  } */
                if ($scmCnsgnRtrnDfltBalsAcntID <= 0) {
                    $exitErrMsg .= "Please enter a Supplier Payables Account!<br/>";
                }
                $oldPtyCashID = getGnrlRecID("inv.inv_consgmt_rcpt_rtns_hdr", "rcpt_number", "rcpt_rtns_id", $scmCnsgnRtrnDocNum, $orgID);
                if ($oldPtyCashID > 0 && $oldPtyCashID != $sbmtdScmCnsgnRtrnID) {
                    $exitErrMsg .= "New Document Number/Name is already in use in this Organization!<br/>";
                }
                $apprvlStatus = "Incomplete";
                $nxtApprvlActn = "Return";
                $srcDocHdrID = $srcCnsgnRtrnDocID;
                $srcDocType = "Receipt";
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['sbmtdScmCnsgnRtrnID'] = $sbmtdScmCnsgnRtrnID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                if ($sbmtdScmCnsgnRtrnID <= 0) {
                    createCnsgnRtrnHdr($orgID, $scmCnsgnRtrnDocNum, $scmCnsgnRtrnDesc, $scmCnsgnRtrnDfltTrnsDte, $scmCnsgnRtrnSpplrID, $scmCnsgnRtrnSpplrSiteID,
                            $apprvlStatus, $nxtApprvlActn, $srcCnsgnRtrnDocID, $scmCnsgnRtrnDfltBalsAcntID, $scmCnsgnRtrnInvcCurID, $scmCnsgnRtrnExRate);
                    $sbmtdScmCnsgnRtrnID = getGnrlRecID("inv.inv_consgmt_rcpt_rtns_hdr", "rcpt_number", "rcpt_rtns_id", $scmCnsgnRtrnDocNum, $orgID);
                } else if ($sbmtdScmCnsgnRtrnID > 0) {
                    updtCnsgnRtrnHdr($sbmtdScmCnsgnRtrnID, $scmCnsgnRtrnDocNum, $scmCnsgnRtrnDesc, $scmCnsgnRtrnDfltTrnsDte, $scmCnsgnRtrnSpplrID,
                            $scmCnsgnRtrnSpplrSiteID, $apprvlStatus, $nxtApprvlActn, $srcCnsgnRtrnDocID, $scmCnsgnRtrnDfltBalsAcntID, $scmCnsgnRtrnInvcCurID, $scmCnsgnRtrnExRate);
                }
                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                if (trim($slctdDetTransLines, "|~") != "" && $sbmtdScmCnsgnRtrnID > 0) {
                    //Save Petty Cash Double Entry Lines
                    $variousRows = explode("|", trim($slctdDetTransLines, "|"));
                    //echo count($variousRows);
                    for ($y = 0; $y < count($variousRows); $y++) {
                        //var_dump($crntRow);
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 10) {
                            $ln_TrnsLnID = (float) (cleanInputData1($crntRow[0]));
                            $ln_ItmID = (float) cleanInputData1($crntRow[1]);
                            $ln_StoreID = (int) cleanInputData1($crntRow[2]);
                            $ln_LineDesc = cleanInputData1($crntRow[3]);
                            $ln_QTY = (float) (cleanInputData1($crntRow[4]));
                            $ln_UnitPrice = (float) cleanInputData1($crntRow[5]);
                            $ln_RcptDocLnID = (int) (cleanInputData1($crntRow[6]));
                            $ln_CnsgnCdtn = cleanInputData1($crntRow[7]);
                            $ln_ExtraDesc = cleanInputData1($crntRow[8]);
                            $ln_CnsgnID = (float) cleanInputData1($crntRow[9]);
                            preg_match_all("/\[[^\]]*\]/", $ln_ExtraDesc, $matches);
                            //var_dump($matches);
                            if (trim($ln_ExtraDesc) != "" && strpos($matches[0][0], "[CS No.:") !== FALSE) {
                                $ln_ExtraDesc = str_replace($matches[0][0], "", $ln_ExtraDesc);
                            }
                            //var_dump($ln_ExtraDesc);
                            //exit();
                            $errMsg = "";
                            if ($ln_LineDesc === "" || $ln_ItmID <= 0 || $ln_QTY <= 0) {
                                $errMsg = "Row " . ($y + 1) . ":- Item Description and Quantity are all required Fields!<br/>";
                            }
                            if ($errMsg === "") {
                                //Create Sales Doc Lines
                                if ($ln_LineDesc != "" && $ln_ItmID > 0 && $ln_QTY > 0) {
                                    if ($ln_TrnsLnID <= 0) {
                                        $afftctd += createCnsgnRtrnLine($ln_QTY, $ln_CnsgnID, $ln_RcptDocLnID, $ln_CnsgnCdtn, $ln_ExtraDesc, $ln_ItmID,
                                                $ln_StoreID, $sbmtdScmCnsgnRtrnID);
                                    } else {
                                        $afftctd += updtCnsgnRtrnLine($ln_TrnsLnID, $ln_QTY, $ln_CnsgnID, $ln_RcptDocLnID, $ln_CnsgnCdtn, $ln_ExtraDesc,
                                                $ln_ItmID, $ln_StoreID, $sbmtdScmCnsgnRtrnID);
                                    }
                                }
                            } else {
                                $exitErrMsg .= $errMsg;
                            }
                        }
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
                        $exitErrMsg = approve_cnsgn_rcpt($sbmtdScmCnsgnRtrnID, "Return");
                        if (strpos($exitErrMsg, "SUCCESS") !== FALSE) {
                            $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                        } else {
                            $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                        }
                    } else {
                        $exitErrMsg .= "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Document Successfully Saved!"
                                . "<br/>" . $afftctd . " Document Transaction(s) Saved Successfully!";
                    }
                }
                $arr_content['percent'] = 100;
                $arr_content['sbmtdScmCnsgnRtrnID'] = $sbmtdScmCnsgnRtrnID;
                $arr_content['message'] = $exitErrMsg;
                echo json_encode($arr_content);
                exit();
            } else if ($actyp == 20) {
                //Upload Attachment
                header("content-type:application/json");
                $attchmentID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
                $sbmtdScmCnsgnRtrnID = isset($_POST['sbmtdScmCnsgnRtrnID']) ? cleanInputData($_POST['sbmtdScmCnsgnRtrnID']) : -1;
                if (!($canEdt || $canAdd)) {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $docCtrgrName = isset($_POST['docCtrgrName']) ? cleanInputData($_POST['docCtrgrName']) : "";
                $nwImgLoc = "";
                $errMsg = "";
                $pkID = $sbmtdScmCnsgnRtrnID;
                if ($attchmentID > 0) {
                    uploadDaCnsgnRtrnDoc($attchmentID, $nwImgLoc, $errMsg);
                } else {
                    $attchmentID = getNewCnsgnRcptDocID();
                    createCnsgnRcptDoc($attchmentID, $pkID, "RETURN", $docCtrgrName, "");
                    uploadDaCnsgnRtrnDoc($attchmentID, $nwImgLoc, $errMsg);
                }
                $arr_content['attchID'] = $attchmentID;
                if (strpos($errMsg, "Document Stored Successfully!<br/>") === FALSE) {
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $errMsg;
                } else {
                    $doc_src = $ftp_base_db_fldr . "/Rcpts/" . $nwImgLoc;
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
                //Reverse Receipt Return
                $errMsg = "";
                $scmCnsgnRtrnDesc = isset($_POST['scmCnsgnRtrnDesc']) ? cleanInputData($_POST['scmCnsgnRtrnDesc']) : '';
                $shdSbmt = isset($_POST['shdSbmt']) ? cleanInputData($_POST['shdSbmt']) : 0;
                $sbmtdScmCnsgnRtrnID = isset($_POST['sbmtdScmCnsgnRtrnID']) ? cleanInputData($_POST['sbmtdScmCnsgnRtrnID']) : -1;
                if (!$cancelDocs) {
                    echo "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    exit();
                }
                $rqStatus = "Not Validated"; //approval_status
                $rqStatusNext = "Approve";
                $p_dochdrtype = "";
                $scmInvcDfltTrnsDte = "";
                $scmInvcDocNum = "";
                if ($sbmtdScmCnsgnRtrnID > 0) {
                    $result = get_One_CnsgnRtrnDocHdr($sbmtdScmCnsgnRtrnID);
                    if ($row = loc_db_fetch_array($result)) {
                        $scmInvcDfltTrnsDte = $row[1] . " 12:00:00";
                        $scmInvcDocNum = $row[4];
                        $p_dochdrtype = $row[5];
                        $rqStatus = $row[12];
                        $rqStatusNext = $row[13];
                    }
                }
                if ($rqStatus == "Incomplete" && $sbmtdScmCnsgnRtrnID > 0) {
                    echo deleteCnsgnRtrnHdrNDet($sbmtdScmCnsgnRtrnID, $scmInvcDocNum);
                    exit();
                } else {
                    $exitErrMsg = cancel_cnsgn_rcpt($sbmtdScmCnsgnRtrnID, "Return", $orgID, $usrID);
                    $arr_content['sbmtdScmCnsgnRtrnID'] = $sbmtdScmCnsgnRtrnID;
                    $arr_content['percent'] = 100;
                    if (strpos($exitErrMsg, "SUCCESS") !== FALSE) {
                        execUpdtInsSQL("UPDATE inv.inv_consgmt_rcpt_rtns_hdr SET description='" . loc_db_escape_string($scmCnsgnRtrnDesc) .
                                "' WHERE (rcpt_rtns_id = " . $sbmtdScmCnsgnRtrnID . ")");
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
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Receipt Returns</span>
				</li>
                               </ul>
                              </div>";
                $error = "";
                $searchAll = true;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Document Number';
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
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                if ($vwtyp == 0) {
                    $total = get_Total_CnsgnRtrn($srchFor, $srchIn, $orgID, $qShwUnpstdOnly, $qShwUnpaidOnly);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_Basic_CnsgnRtrn($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qShwUnpstdOnly, $qShwUnpaidOnly);
                    $cntr = 0;
                    $colClassType1 = "col-md-2";
                    $colClassType2 = "col-md-5";
                    $colClassType3 = "col-md-5";
                    ?> 
                    <form id='scmCnsgnRtrnForm' action='' method='post' accept-charset='UTF-8'>
                        <!--ROW ID-->
                        <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                        <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">CONSIGNMENT RECEIPT RETURNS</legend>
                            <div class="row" style="margin-bottom:0px;">
                                <?php
                                $colClassType1 = "col-md-2";
                                $colClassType2 = "col-md-5";
                                $colClassType3 = "col-md-10";
                                ?>
                                <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                                    <div class="input-group">
                                        <input class="form-control" id="scmCnsgnRtrnSrchFor" type = "text" placeholder="Search For" value="<?php
                                        echo trim(str_replace("%", " ", $srchFor));
                                        ?>" onkeyup="enterKeyFuncScmCnsgnRtrn(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0')">
                                        <input id="scmCnsgnRtrnPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                        <input id="sbmtdScmCnsgnRcptID" type = "hidden" value="-1">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getScmCnsgnRtrn('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getScmCnsgnRtrn('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="scmCnsgnRtrnSrchIn">
                                            <?php
                                            $valslctdArry = array("", "", "", "", "");
                                            $srchInsArrys = array("Document Number", "Document Description",
                                                "Supplier Name", "Approval Status", "Created By");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                if ($srchIn == $srchInsArrys[$z]) {
                                                    $valslctdArry[$z] = "selected";
                                                }
                                                ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="scmCnsgnRtrnDsplySze" style="min-width:70px !important;">                            
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
                                                <a href="javascript:getScmCnsgnRtrn('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:getScmCnsgnRtrn('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Next">
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
                                        <div class="col-md-6" style="padding:0px 0px 0px 0px !important;">                      
                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Approved Item Receipts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'sbmtdScmCnsgnRcptID', '', 'clear', 1, '', function () {
                                                                                getOneScmCnsgnRtrnForm(-1, 1, 'ShowDialog');
                                                                            });" data-toggle="tooltip" data-placement="bottom" title="Add New Receipt Return">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                NEW RECEIPT RETURN
                                            </button>   
                                        </div>  
                                    <?php }
                                    ?>
                                    <div class="col-md-3" style="padding:5px 1px 0px 1px !important;display:none;">
                                        <div class = "form-check" style = "font-size: 12px !important;">
                                            <label class = "form-check-label">
                                                <?php
                                                $shwUnpaidOnlyChkd = "";
                                                if ($qShwUnpaidOnly == true) {
                                                    $shwUnpaidOnlyChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <input type="checkbox" class="form-check-input" onclick="getScmCnsgnRtrn('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="scmCnsgnRtrnShwUnpaidOnly" name="scmCnsgnRtrnShwUnpaidOnly"  <?php echo $shwUnpaidOnlyChkd; ?>>
                                                Show Approved but Unpaid
                                            </label>
                                        </div> 
                                    </div>
                                    <div class = "col-md-3" style = "padding:5px 1px 0px 1px !important;display:none;">
                                        <div class = "form-check" style = "font-size: 12px !important;">
                                            <label class = "form-check-label">
                                                <?php
                                                $shwUnpstdOnlyChkd = "";
                                                if ($qShwUnpstdOnly == true) {
                                                    $shwUnpstdOnlyChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <input type="checkbox" class="form-check-input" onclick="getScmCnsgnRtrn('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="scmCnsgnRtrnShwUnpstdOnly" name="scmCnsgnRtrnShwUnpstdOnly"  <?php echo $shwUnpstdOnlyChkd; ?>>
                                                Show Only Unposted
                                            </label>
                                        </div>                            
                                    </div>
                                </div>
                            </div>
                            <div class="row"> 
                                <div  class="col-md-12">
                                    <table class="table table-striped table-bordered table-responsive" id="scmCnsgnRtrnHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="max-width:35px;width:35px;">No.</th>
                                                <th style="max-width:30px;width:30px;">...</th>
                                                <th>Document Number/Type - Transaction Description</th>
                                                <th style="max-width:115px;width:115px;">Date Returned</th>
                                                <th style="text-align:center;max-width:40px;width:40px;">CUR.</th>	
                                                <th style="text-align:right;min-width:120px;width:120px;">Total Amount</th>
                                                <th style="max-width:115px;width:115px;">Document Status</th>
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
                                                <tr id="scmCnsgnRtrnHdrsRow_<?php echo $cntr; ?>">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>    
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View/Edit Invoice" 
                                                                onclick="getOneScmCnsgnRtrnForm(<?php echo $row[0]; ?>, 1, 'ShowDialog');" style="padding:2px !important;" style="padding:2px !important;">                                                                
                                                                    <?php if ($canAdd === true) { ?>                                
                                                                <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } else { ?>
                                                                <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } ?>
                                                        </button>
                                                    </td>
                                                    <td class="lovtd"><?php echo $row[1] . " " . $row[7] . " " . $row[3]; ?></td>
                                                    <td class="lovtd"><?php echo $row[2]; ?></td>
                                                    <td class="lovtd" style="text-align:center;font-weight: bold;color:black;"><?php echo $row[4]; ?></td>
                                                    <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                        echo number_format((float) $row[5], 2);
                                                        ?>
                                                    </td>
                                                    <?php
                                                    $style1 = "color:red;";
                                                    if ($row[6] == "Returned") {
                                                        $style1 = "color:green;";
                                                    } else if ($row[6] == "Cancelled") {
                                                        $style1 = "color:#0d0d0d;";
                                                    }
                                                    ?>
                                                    <td class="lovtd" style="font-weight:bold;<?php echo $style1; ?>"><?php echo $row[6]; ?></td>  
                                                    <?php if ($canDel === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction" onclick="delScmCnsgnRtrn('scmCnsgnRtrnHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                            <input type="hidden" id="scmCnsgnRtrnHdrsRow<?php echo $cntr; ?>_HdrID" name="scmCnsgnRtrnHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                        </td>
                                                    <?php } ?>
                                                    <?php
                                                    if ($canVwRcHstry === true) {
                                                        ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                            echo urlencode(encrypt1(($row[0] . "|inv.inv_consgmt_rcpt_rtns_hdr|rcpt_rtns_id"), $smplTokenWord1));
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
                //New Receipt Form
                //var_dump($_POST);
                $sbmtdScmCnsgnRcptID = isset($_POST['sbmtdScmCnsgnRcptID']) ? cleanInputData($_POST['sbmtdScmCnsgnRcptID']) : -1;
                $sbmtdScmCnsgnRtrnID = isset($_POST['sbmtdScmCnsgnRtrnID']) ? cleanInputData($_POST['sbmtdScmCnsgnRtrnID']) : -1;
                if (!$canAdd || ($sbmtdScmCnsgnRtrnID > 0 && !$canEdt)) {
                    restricted();
                    exit();
                }
                $orgnlScmCnsgnRtrnID = $sbmtdScmCnsgnRtrnID;
                $scmCnsgnRtrnDfltTrnsDte = $gnrlTrnsDteDMY;//HMS
                $scmCnsgnRtrnCreator = $uName;
                $scmCnsgnRtrnCreatorID = $usrID;
                $gnrtdTrnsNo = "";
                $scmCnsgnRtrnType = "Receipt Returns";
                $scmCnsgnRtrnDesc = $scmCnsgnRtrnType;

                $srcCnsgnRtrnDocID = $sbmtdScmCnsgnRcptID;
                $srcCnsgnRtrnDocTyp = "Receipt";
                $srcCnsgnRtrnDocNum = "";

                $scmCnsgnRtrnSpplr = "";
                $scmCnsgnRtrnSpplrID = -1;
                $scmCnsgnRtrnSpplrSite = "";
                $scmCnsgnRtrnSpplrSiteID = -1;
                $scmCnsgnRtrnSpplrClsfctn = "%Supplier%";
                $rqStatus = "Incomplete";
                $rqStatusNext = "Return";
                $rqstatusColor = "red";

                $scmCnsgnRtrnTtlAmnt = 0;
                $scmCnsgnRtrnGLBatch = "";
                $scmCnsgnRtrnGLBatchID = -1;
                $scmCnsgnRtrnDfltBalsAcnt = "";
                $scmCnsgnRtrnInvcCurID = $fnccurid;
                $scmCnsgnRtrnInvcCur = $fnccurnm;
                $scmCnsgnRtrnExRate = 1;
                $scmCnsgnRtrnIsPstd = "0";
                $sbmtdScmPyblsInvcID = -1;
                $scmCnsgnRtrnPyblDocID = -1;
                $scmCnsgnRtrnPyblDoc = "";
                $scmCnsgnRtrnPyblDocType = "";
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                $scmCnsgnRtrnDfltBalsAcntID = get_DfltSplrPyblsCashAcnt($scmCnsgnRtrnSpplrID, $orgID);
                //echo "ACCID::".$scmCnsgnRtrnDfltBalsAcntID;
                if ($sbmtdScmCnsgnRtrnID > 0) {
                    $result = get_One_CnsgnRtrnDocHdr($sbmtdScmCnsgnRtrnID);
                    if ($row = loc_db_fetch_array($result)) {
                        $scmCnsgnRtrnDfltTrnsDte = $row[1];
                        $scmCnsgnRtrnCreator = $row[3];
                        $scmCnsgnRtrnCreatorID = $row[2];
                        $gnrtdTrnsNo = $row[4];
                        $scmCnsgnRtrnType = $row[5];
                        $scmCnsgnRtrnDesc = $row[6];
                        $srcCnsgnRtrnDocID = $row[7];
                        $srcCnsgnRtrnDocNum = $srcCnsgnRtrnDocID;
                        $scmCnsgnRtrnSpplr = $row[9];
                        $scmCnsgnRtrnSpplrID = $row[8];
                        $scmCnsgnRtrnSpplrSite = $row[11];
                        $scmCnsgnRtrnSpplrSiteID = $row[10];
                        $rqStatus = $row[12];
                        $rqStatusNext = $row[13];
                        $rqstatusColor = "red";
                        $srcCnsgnRtrnDocTyp = $row[16];

                        $scmCnsgnRtrnTtlAmnt = (float) $row[14];
                        $scmCnsgnRtrnGLBatch = $row[21];
                        $scmCnsgnRtrnGLBatchID = $row[20];
                        $scmCnsgnRtrnInvcCur = $row[25];
                        $scmCnsgnRtrnInvcCurID = (float) $row[24];
                        if ($scmCnsgnRtrnInvcCurID <= 0) {
                            $scmCnsgnRtrnInvcCurID = $fnccurid;
                            $scmCnsgnRtrnInvcCur = $fnccurnm;
                        }
                        $scmCnsgnRtrnExRate = (float) $row[35];
                        $scmCnsgnRtrnDfltBalsAcntID = $row[29];
                        $scmCnsgnRtrnDfltBalsAcnt = $row[30];
                        $sbmtdScmPyblsInvcID = (float) $row[32];
                        $scmCnsgnRtrnPyblDocID = $sbmtdScmPyblsInvcID;
                        $scmCnsgnRtrnPyblDoc = $row[33];
                        $scmCnsgnRtrnPyblDocType = $row[34];
                        if ($rqStatus == "Returned") {
                            $rqstatusColor = "green";
                        } else {
                            $rqstatusColor = "red";
                        }
                        if ($rqStatus == "Incomplete") {
                            $mkReadOnly = "";
                            $mkRmrkReadOnly = "";
                        } else {
                            $canEdt = FALSE;
                            $mkReadOnly = "readonly=\"true\"";
                            if ($rqStatus != "Returned") {
                                $mkRmrkReadOnly = "readonly=\"true\"";
                            }
                        }
                    }
                } else if ($scmCnsgnRtrnDfltBalsAcntID > 0 && $sbmtdScmCnsgnRcptID > 0) {
                    $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
                    if ($usrTrnsCode == "") {
                        $usrTrnsCode = "XX";
                    }
                    $dte = date('ymd');
                    $docTypPrfx = "RTRN";
                    $gnrtdTrnsNo1 = $docTypPrfx . "-" . $usrTrnsCode . "-" . $dte . "-";
                    $gnrtdTrnsNo = $gnrtdTrnsNo1 . str_pad(((getRecCount_LstNum("inv.inv_consgmt_rcpt_rtns_hdr", "rcpt_number", "rcpt_rtns_id",
                                            $gnrtdTrnsNo1 . "%") + 1) . ""), 3, '0', STR_PAD_LEFT);
                    $scmCnsgnRtrnDfltBalsAcnt = getAccntNum($scmCnsgnRtrnDfltBalsAcntID) . "." . getAccntName($scmCnsgnRtrnDfltBalsAcntID);
                    $scmCnsgnRtrnInvcCurID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $scmCnsgnRtrnDfltBalsAcntID);
                    if ($scmCnsgnRtrnInvcCurID > 0) {
                        $scmCnsgnRtrnInvcCur = getPssblValNm($scmCnsgnRtrnInvcCurID);
                    }
                    if ($sbmtdScmCnsgnRcptID > 0) {
                        $result = get_One_CnsgnRcptDocHdr($sbmtdScmCnsgnRcptID);
                        if ($row = loc_db_fetch_array($result)) {
                            $scmCnsgnRtrnDesc = "[Receipt Returns] " . $row[6];
                            $srcCnsgnRtrnDocID = $sbmtdScmCnsgnRcptID;
                            $srcCnsgnRtrnDocNum = $row[4];
                            $scmCnsgnRtrnSpplr = $row[9];
                            $scmCnsgnRtrnSpplrID = $row[8];
                            $scmCnsgnRtrnSpplrSite = $row[11];
                            $scmCnsgnRtrnSpplrSiteID = $row[10];
                            $srcCnsgnRtrnDocTyp = $row[5];
                            $scmCnsgnRtrnTtlAmnt = (float) $row[14];
                            $scmCnsgnRtrnInvcCur = $row[25];
                            //echo $scmCnsgnRtrnInvcCur;
                            $scmCnsgnRtrnInvcCurID = (float) $row[24];
                            if ($scmCnsgnRtrnInvcCurID <= 0) {
                                $scmCnsgnRtrnInvcCurID = $fnccurid;
                                $scmCnsgnRtrnInvcCur = $fnccurnm;
                            }
                            $scmCnsgnRtrnExRate = (float) $row[36];
                            $scmCnsgnRtrnDfltBalsAcntID = $row[29];
                            $scmCnsgnRtrnDfltBalsAcnt = $row[30];
                        }
                    }
                    $sbmtdScmCnsgnRtrnID = createCnsgnRtrnHdr($orgID, $gnrtdTrnsNo, $scmCnsgnRtrnDesc, $scmCnsgnRtrnDfltTrnsDte, $scmCnsgnRtrnSpplrID,
                            $scmCnsgnRtrnSpplrSiteID, $rqStatus, $rqStatusNext, $srcCnsgnRtrnDocID, $scmCnsgnRtrnDfltBalsAcntID, $scmCnsgnRtrnInvcCurID, $scmCnsgnRtrnExRate);
                    /* $sbmtdScmCnsgnRtrnID = getGnrlRecID("inv.inv_consgmt_rcpt_rtns_hdr", "rcpt_number", "rcpt_rtns_id", $gnrtdTrnsNo, $orgID); */
                } else {
                    exit();
                }
                $reportName = getEnbldPssblValDesc("Sales Invoice", getLovID("Document Custom Print Process Names"));
                $reportTitle = str_replace("Pro-Forma Invoice", "Payment Voucher", $scmCnsgnRtrnType);
                $rptID = getRptID($reportName);
                $prmID1 = getParamIDUseSQLRep("{:invoice_id}", $rptID);
                $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                $trnsID = $sbmtdScmCnsgnRtrnID;
                $paramRepsNVals = $prmID1 . "~" . $trnsID . "|" . $prmID2 . "~" . $reportTitle . "|-130~" . $reportTitle . "|-190~PDF";
                $paramStr = urlencode($paramRepsNVals);
                ?>
                <form class="form-horizontal" id="oneScmCnsgnRtrnEDTForm">
                    <fieldset class="basic_person_fs2" style="min-height:50px !important;">
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Doc. No./Name:</label>
                                    </div>
                                    <div class="col-md-3" style="padding:0px 1px 0px 15px;">
                                        <input type="text" class="form-control" aria-label="..." id="sbmtdScmCnsgnRtrnID" name="sbmtdScmCnsgnRtrnID" value="<?php echo $sbmtdScmCnsgnRtrnID; ?>" readonly="true">
                                    </div>
                                    <div class="col-md-5" style="padding:0px 15px 0px 1px;">
                                        <input type="text" class="form-control" aria-label="..." id="scmCnsgnRtrnDocNum" name="scmCnsgnRtrnDocNum" value="<?php echo $gnrtdTrnsNo; ?>" readonly="true">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Document Date:</label>
                                    </div>
                                    <div class="col-md-8 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px 15px 0px 15px !important;">
                                        <input class="form-control" size="16" type="text" id="scmCnsgnRtrnDfltTrnsDte" name="scmCnsgnRtrnDfltTrnsDte" value="<?php echo $scmCnsgnRtrnDfltTrnsDte; ?>" placeholder="Transactions Date" readonly="true">
                                        <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Document Type:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" aria-label="..." id="scmCnsgnRtrnType" name="scmCnsgnRtrnType" value="<?php echo $scmCnsgnRtrnType; ?>" readonly="true">
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label for="srcCnsgnRtrnDocNum" class="control-label col-md-4">Source Doc. No.:</label>
                                    <div  class="col-md-8">
                                        <input type="hidden" class="form-control" aria-label="..." id="srcCnsgnRtrnDocTyp" name="srcCnsgnRtrnDocTyp" value="<?php echo $srcCnsgnRtrnDocTyp; ?>">
                                        <input type="hidden" id="srcCnsgnRtrnDocID" value="<?php echo $srcCnsgnRtrnDocID; ?>">
                                        <input type="text" class="form-control" aria-label="..." id="srcCnsgnRtrnDocNum" name="srcCnsgnRtrnDocNum" value="<?php echo $srcCnsgnRtrnDocNum; ?>" readonly="true" style="width:100%;">
                                    </div>
                                </div>  
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="scmCnsgnRtrnSpplr" class="control-label col-md-4">Supplier:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="scmCnsgnRtrnSpplr" name="scmCnsgnRtrnSpplr" value="<?php echo $scmCnsgnRtrnSpplr; ?>" readonly="true">
                                            <input type="hidden" id="scmCnsgnRtrnSpplrID" value="<?php echo $scmCnsgnRtrnSpplrID; ?>">
                                            <input type="hidden" id="scmCnsgnRtrnSpplrClsfctn" value="<?php echo $scmCnsgnRtrnSpplrClsfctn; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getCstmrSpplrForm(-1, 'Create/Edit Supplier', 'ShowDialog', function () {}, 'scmCnsgnRtrnSpplrID');" data-toggle="tooltip" title="Create/Edit Supplier">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </label>
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Business/Trade Partners', 'allOtherInputOrgID', '', 'scmCnsgnRtrnSpplrClsfctn', 'radio', true, '', 'scmCnsgnRtrnSpplrID', 'scmCnsgnRtrnSpplr', 'clear', 1, '', function () {
                                                                        getInvPyblsAcntInfo();
                                                                    });" data-toggle="tooltip" title="Existing Client/Vendor">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>  
                                <div class="form-group">
                                    <label for="scmCnsgnRtrnSpplrSite" class="control-label col-md-4">Site:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="scmCnsgnRtrnSpplrSite" name="scmCnsgnRtrnSpplrSite" value="<?php echo $scmCnsgnRtrnSpplrSite; ?>" readonly="true">
                                            <input class="form-control" type="hidden" id="scmCnsgnRtrnSpplrSiteID" value="<?php echo $scmCnsgnRtrnSpplrSiteID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Customer/Supplier Sites', 'scmCnsgnRtrnSpplrID', '', '', 'radio', true, '', 'scmCnsgnRtrnSpplrSiteID', 'scmCnsgnRtrnSpplrSite', 'clear', 1, '');" data-toggle="tooltip" title="">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>                                                               
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Remark / Narration:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group"  style="width:100%;">
                                            <textarea class="form-control" rows="1" cols="20" id="scmCnsgnRtrnDesc" name="scmCnsgnRtrnDesc" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $scmCnsgnRtrnDesc; ?></textarea>
                                            <input class="form-control" type="hidden" id="scmCnsgnRtrnDesc1" value="<?php echo $scmCnsgnRtrnDesc; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('scmCnsgnRtrnDesc');" style="max-width:30px;width:30px;">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label style="margin-bottom:0px !important;">Status:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="hidden" id="scmCnsgnRtrnApprvlStatus" value="<?php echo $rqStatus; ?>">                              
                                        <button type="button" class="btn btn-default" style="height:34px;width:100% !important;" id="myScmCnsgnRtrnStatusBtn">
                                            <span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;height:34px;">
                                                <?php
                                                echo $rqStatus; //. ($scmCnsgnRtrnIsPstd == "1" ? " [Posted]" : " [Not Posted]")
                                                ?>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class = "col-md-4">   
                                <div class="form-group">
                                    <div class="col-md-4" style="padding:0px 10px 0px 10px !important;">
                                        <label style="margin-bottom:0px !important;">Return Total:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <label class="btn btn-primary btn-file input-group-addon active" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $scmCnsgnRtrnInvcCur; ?>', 'scmCnsgnRtrnInvcCur', '', 'clear', 0, '', function () {
                                                                        $('#scmCnsgnRtrnInvcCur1').html($('#scmCnsgnRtrnInvcCur').val());
                                                                        $('#scmCnsgnRtrnInvcCur2').html($('#scmCnsgnRtrnInvcCur').val());
                                                                        $('#scmCnsgnRtrnInvcCur3').html($('#scmCnsgnRtrnInvcCur').val());
                                                                        $('#scmCnsgnRtrnInvcCur4').html($('#scmCnsgnRtrnInvcCur').val());
                                                                        $('#scmCnsgnRtrnInvcCur5').html($('#scmCnsgnRtrnInvcCur').val());
                                                                    });">
                                                <span class="" style="font-size: 20px !important;" id="scmCnsgnRtrnInvcCur1"><?php echo $scmCnsgnRtrnInvcCur; ?></span>
                                            </label>
                                            <input type="hidden" id="scmCnsgnRtrnInvcCur" value="<?php echo $scmCnsgnRtrnInvcCur; ?>"> 
                                            <input type="hidden" id="scmCnsgnRtrnInvcCurID" value="<?php echo $scmCnsgnRtrnInvcCurID; ?>"> 
                                            <input class="form-control" type="text" id="scmCnsgnRtrnTtlAmnt" value="<?php
                                            echo number_format($scmCnsgnRtrnTtlAmnt, 2);
                                            ?>" style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('scmCnsgnRtrnTtlAmnt');" readonly="true"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4" style="padding:0px 10px 0px 10px !important;">
                                        <label style="margin-bottom:0px !important;">Rate (Multiplier):</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                                <span class="" style="font-size: 20px !important;" id="scmCnsgnRtrnInvcCur4"><?php echo $scmCnsgnRtrnInvcCur; ?></span>
                                                <span class="" style="font-size: 20px !important;" id="scmCnsgnRtrnInvcCur6"><?php echo "&nbsp;to " . $fnccurnm; ?></span>
                                            </label>
                                            <input type="text" class="form-control" aria-label="..." id="scmCnsgnRtrnExRate" name="scmCnsgnRtrnExRate" value="<?php
                                            echo number_format($scmCnsgnRtrnExRate, 4);
                                            ?>" style="font-size: 18px !important;font-weight:bold;width:100%;" <?php echo $mkReadOnly; ?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="display:none;">
                                    <label for="scmCnsgnRtrnGLBatch" class="control-label col-md-4" style="padding:0px 10px 0px 10px !important;">GL Batch Name:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input class="form-control" id="scmCnsgnRtrnGLBatch" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type = "text" placeholder="" value="<?php echo $scmCnsgnRtrnGLBatch; ?>" readonly="true"/>
                                            <input type="hidden" id="scmCnsgnRtrnGLBatchID" value="<?php echo $scmCnsgnRtrnGLBatchID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getOneJrnlBatchForm(<?php echo $scmCnsgnRtrnGLBatchID; ?>, 1, 'ReloadDialog',<?php echo $sbmtdScmCnsgnRtrnID; ?>, 'Sales Invoice');">
                                                <img src="cmn_images/openfileicon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Open
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="scmCnsgnRtrnPyblDoc" class="control-label col-md-4" style="padding:0px 10px 0px 10px !important;">Payable Doc.</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input class="form-control" id="scmCnsgnRtrnPyblDoc" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type = "text" placeholder="" value="<?php echo $scmCnsgnRtrnPyblDoc; ?>" readonly="true"/>
                                            <input type="hidden" id="scmCnsgnRtrnPyblDocID" value="<?php echo $scmCnsgnRtrnPyblDocID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getOneAccbPyblsInvcForm(<?php echo $scmCnsgnRtrnPyblDocID; ?>, 1, 'ReloadDialog', '<?php echo $scmCnsgnRtrnPyblDocType; ?>',<?php echo $sbmtdScmCnsgnRtrnID; ?>, 'Receipt Returns');">
                                                <img src="cmn_images/openfileicon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Open
                                            </label>
                                        </div>
                                    </div>
                                </div>                            
                                <div class="form-group">
                                    <label for="scmCnsgnRtrnDfltBalsAcnt" class="control-label col-md-4" style="padding:0px 10px 0px 10px !important;">Payable Account:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input class="form-control" id="scmCnsgnRtrnDfltBalsAcnt" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Enter GL Account Number" type = "text" min="0" placeholder="" value="<?php echo $scmCnsgnRtrnDfltBalsAcnt; ?>" readonly="true"/>
                                            <input type="hidden" id="scmCnsgnRtrnDfltBalsAcntID" value="<?php echo $scmCnsgnRtrnDfltBalsAcntID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Liability Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'scmCnsgnRtrnDfltBalsAcntID', 'scmCnsgnRtrnDfltBalsAcnt', 'clear', 1, '', function () {});">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="custDiv" style="padding:0px !important;min-height: 30px !important;"> 
                                    <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                    <div class="col-md-6" style="padding:0px 0px 0px 0px !important;float:left;">
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneScmCnsgnRtrnDocsForm(<?php echo $sbmtdScmCnsgnRtrnID; ?>, 20);" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                                            <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button> 
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneScmCnsgnRtrnForm(<?php echo $sbmtdScmCnsgnRtrnID; ?>, 1, 'ReloadDialog');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;"  onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="width:100% !important;">
                                                            <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Print
                                                        </button>
                                                        <button type="button" class="btn btn-default" style="height:30px;margin-bottom: 1px;">
                                                            <span style="font-weight:bold;color:black;">Total: </span>
                                                            <span style="color:red;font-weight: bold;" id="myCptrdCnsgnRtrnValsTtlBtn"><?php echo $scmCnsgnRtrnInvcCur; ?> 
                                                                <?php
                                                                echo number_format($scmCnsgnRtrnTtlAmnt, 2);
                                                                ?>
                                                            </span>
                                                            <input type="hidden" id="myCptrdCnsgnRtrnValsTtlVal" value="<?php echo $scmCnsgnRtrnTtlAmnt; ?>">
                                                        </button>
                                                    </div> 
                                                    <div class="col-md-6" style="padding:0px 0px 0px 0px !important;">
                                                        <div class="" style="padding:0px 0px 0px 0px;float:right !important;"> 
                                                            <?php
                                                            if ($rqStatus == "Incomplete") {
                                                                if ($canEdt) {
                                                                    ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveScmCnsgnRtrnForm('<?php echo $fnccurnm; ?>', 0);"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>    
                                                                    <?php
                                                                }
                                                                if ($canRvwApprvDocs) {
                                                                    ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveScmCnsgnRtrnForm('<?php echo $fnccurnm; ?>', 2);" data-toggle="tooltip" data-placement="bottom" title="Finalize Document">
                                                                        <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Finalize
                                                                    </button>
                                                                    <?php
                                                                }
                                                            } else if ($rqStatus == "Returned") {
                                                                if ($cancelDocs) {
                                                                    ?>
                                                                    <button id="fnlzeRvrslScmCnsgnRtrnBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveScmCnsgnRtrnRvrslForm('<?php echo $fnccurnm; ?>', 1);"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Cancel Return&nbsp;</button>  
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
                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneScmCnsgnRtrnLnsTblSctn"> 
                                    <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                        <div id="cnsgnRtrnDetLines" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                            <div class="row" style="padding:0px 13px 0px 13px !important;">
                                                <div class="col-md-12" style="padding:0px 2px 0px 2px !important;">
                                                    <table class="table table-striped table-bordered table-responsive" id="oneScmCnsgnRtrnSmryLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th style="max-width:30px;width:30px;">No.</th>
                                                                <th style="min-width:180px;">Item Code/Description</th>
                                                                <?php if ($rqStatus == "Incomplete") { ?>
                                                                    <th style="max-width:70px;width:70px;text-align: right;">Source QTY</th>
                                                                <?php } ?>
                                                                <th style="max-width:50px;width:50px;text-align: right;">Return QTY</th>
                                                                <th style="max-width:55px;text-align: center;">UOM.</th>
                                                                <th style="max-width:170px;width:140px;text-align: right;">Unit Cost Price</th>
                                                                <th style="max-width:170px;width:120px;text-align: right;">Total Amount</th>
                                                                <th style="min-width:130px;">Source Store</th>
                                                                <th style="max-width:100px;width:100px;">Consignment Condition</th>
                                                                <th style="min-width:120px;">Remarks</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">CS</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">SP</th>
                                                                <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>   
                                                            <?php
                                                            $cntr = 0;
                                                            $resultRw = get_CnsgnRtrnDocDet($sbmtdScmCnsgnRtrnID);
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
                                                                    $trsctnLnItmNm = $rowRw[2];
                                                                    $trsctnLnQty = (float) $rowRw[3];
                                                                    $trsctnLnUnitPrice = (float) $rowRw[4];
                                                                    $trsctnLnAmnt = $trsctnLnQty * $trsctnLnUnitPrice;
                                                                    $ttlTrsctnEntrdAmnt = $ttlTrsctnEntrdAmnt + $trsctnLnAmnt;
                                                                    $trsctnLnPoLnID = (float) $rowRw[5];
                                                                    $trsctnLnStoreID = (float) $rowRw[6];
                                                                    $trsctnLnStoreNm = $rowRw[7];
                                                                    $trsctnLnStckID = (float) $rowRw[8];
                                                                    $trsctnLnCnsgnCndtn = $rowRw[9];
                                                                    $trsctnLnRmrks = $rowRw[10];
                                                                    $trsctnLnCnsgnID = (float) $rowRw[11];
                                                                    $trsctnLnUomID = (float) $rowRw[12];
                                                                    $trsctnLnUomNm = $rowRw[13];
                                                                    $trsctnLnSrcStckBals=(float) $rowRw[14];
                                                                    $cntr += 1;
                                                                    ?>
                                                                    <tr id="oneScmCnsgnRtrnSmryRow_<?php echo $cntr; ?>" onclick="$('#allOtherInputData99').val($('#oneScmCnsgnRtrnSmryLinesTable tr').index(this));">                                    
                                                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td>                                              
                                                                        <td class="lovtd"  style="">  
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLnID; ?>" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_ItmID" value="<?php echo $trsctnLnItmID; ?>" style="width:100% !important;">  
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_StoreID" value="<?php echo $trsctnLnStoreID; ?>" style="width:100% !important;"> 
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_CnsgnID" value="<?php echo $trsctnLnCnsgnID; ?>" style="width:100% !important;">    
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_PODocLnID" value="<?php echo $trsctnLnPoLnID; ?>" style="width:100% !important;">  
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                                ?>
                                                                                <div class="input-group" style="width:100% !important;">
                                                                                    <input type="text" class="form-control jbDetAccRate jbDetDesc" aria-label="..." id="oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_LineDesc" name="oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $trsctnLnItmNm; ?>" style="width:100% !important;" readonly="true" onkeypress="gnrlFldKeyPress(event, 'oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_LineDesc', 'oneScmCnsgnRtrnSmryLinesTable', 'jbDetAccRate');" onblur="afterSalesInvcItmSlctn('oneScmCnsgnRtrnSmryRow_<?php echo $cntr; ?>');" onchange="autoCreateSalesLns = 99;">
                                                                                   <!-- <label class="btn btn-primary btn-file input-group-addon" onclick="getScmSalesInvcItems('oneScmCnsgnRtrnSmryRow_<?php echo $cntr; ?>', 'ShowDialog', '<?php echo $scmCnsgnRtrnType; ?>', 'false', function () {});">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>-->
                                                                                </div>
                                                                            <?php } else {
                                                                                ?>
                                                                                <span><?php echo $trsctnLnItmNm; ?></span>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <?php if ($rqStatus == "Incomplete") { ?>
                                                                            <td class="lovtd" style="text-align: right;">
                                                                                <input type="text" class="form-control jbDetAccRate1" aria-label="..." id="oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_SrcQTY" name="oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_SrcQTY" value="<?php
                                                                                echo $trsctnLnSrcStckBals;
                                                                                ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_SrcQTY', 'oneScmStockTrnsfrSmryLinesTable', 'jbDetAccRate1');" style="width:100% !important;text-align: right;" readonly="true">                                                    
                                                                            </td>
                                                                        <?php } ?> 
                                                                        <td class="lovtd" style="text-align: right;">
                                                                            <input type="text" class="form-control rqrdFld jbDetAccRate" aria-label="..." id="oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_QTY" name="oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_QTY" value="<?php
                                                                            echo $trsctnLnQty;
                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_QTY', 'oneScmCnsgnRtrnSmryLinesTable', 'jbDetAccRate');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllScmCnsgnRcptSmryTtl('oneScmCnsgnRtrnSmryLinesTable');">                                                    
                                                                        </td>                                               
                                                                        <td class="lovtd" style="max-width:35px;width:35px;text-align: center;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_UomID" value="<?php echo $trsctnLnUomID; ?>" style="width:100% !important;">  
                                                                            <div class="" style="width:100% !important;">
                                                                                <label class="btn btn-primary btn-file" onclick="getOneScmUOMBrkdwnForm(<?php echo $sbmtdScmCnsgnRtrnID; ?>, 2, 'oneScmCnsgnRtrnSmryRow_<?php echo $cntr; ?>');">
                                                                                    <span class="" id="oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_UomNm1"><?php echo $trsctnLnUomNm; ?></span>
                                                                                </label>
                                                                            </div>                                              
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control jbDetDbt" aria-label="..." id="oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_UnitPrice" name="oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_UnitPrice" value="<?php
                                                                            echo number_format($trsctnLnUnitPrice, 5);
                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_UnitPrice', 'oneScmCnsgnRtrnSmryLinesTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" readonly="true" onchange="calcAllScmCnsgnRcptSmryTtl('oneScmCnsgnRtrnSmryLinesTable');">                                                    
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control jbDetCrdt" aria-label="..." id="oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_LineAmt" name="oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_LineAmt" value="<?php
                                                                            echo number_format($trsctnLnAmnt, 2);
                                                                            ?>" onkeypress="gnrlFldKeyPress(event, 'oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_LineAmt', 'oneScmCnsgnRtrnSmryLinesTable', 'jbDetCrdt');" style="width:100% !important;text-align: right;" readonly="true" onchange="calcAllScmCnsgnRcptSmryTtl('oneScmCnsgnRtrnSmryLinesTable');">                                                    
                                                                        </td>                                            
                                                                        <td class="lovtd"  style="">
                                                                            <span><?php echo $trsctnLnStoreNm; ?></span>
                                                                        </td>                                          
                                                                        <td class="lovtd"  style="">  
                                                                            <?php
                                                                            if ($canEdt === true) {
                                                                                ?>
                                                                                <div class="input-group" style="width:100% !important;">
                                                                                    <input type="text" class="form-control rqrdFld jbDetAccRate2" aria-label="..." id="oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_CnsgnCdtn" name="oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_CnsgnCdtn" value="<?php echo $trsctnLnCnsgnCndtn; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_CnsgnCdtn', 'oneScmCnsgnRtrnSmryLinesTable', 'jbDetAccRate2');">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Receipt Return Reasons', '', '', '', 'radio', true, '', 'oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_CnsgnCdtn', '', 'clear', 1, '');" data-toggle="tooltip" title="Existing Consignment Condition">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            <?php } else {
                                                                                ?>
                                                                                <span><?php echo $trsctnLnCnsgnCndtn; ?></span>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </td>   
                                                                        <td class="lovtd">
                                                                            <input type="text" class="form-control" aria-label="..." id="oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_ExtraDesc" name="oneScmCnsgnRtrnSmryRow<?php echo $cntr; ?>_ExtraDesc" value="<?php echo "[CS No.:" . $trsctnLnCnsgnID . "]" . $trsctnLnRmrks; ?>" style="width:100% !important;text-align: left;" <?php echo $mkReadOnly; ?>>                                                    
                                                                        </td>  
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Item Consignments" 
                                                                                    onclick="getScmSalesInvcItems('oneScmCnsgnRtrnSmryRow_<?php echo $cntr; ?>', 'ShowDialog', '<?php echo $scmCnsgnRtrnType; ?>', 'true', function () {
                                                                                                                        var a = 1;
                                                                                                                    });" style="padding:2px !important;"> 
                                                                                <img src="cmn_images/chcklst3.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                            </button>
                                                                        </td>  
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View/Edit Item's New Selling Price" 
                                                                                    onclick="getOneINVItmPricesForm('oneScmCnsgnRtrnSmryRow_<?php echo $cntr; ?>', 'ShowDialog');" style="padding:2px !important;"> 
                                                                                <img src="cmn_images/payment_256.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">                                                            
                                                                            </button>
                                                                        </td>
                                                                        <td class="lovtd" style="text-align: center;">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delScmCnsgnRtrnDetLn('oneScmCnsgnRtrnSmryRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document Line">
                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                        <tfoot>                                                            
                                                            <tr>
                                                                <th>&nbsp;</th>
                                                                <th>&nbsp;</th>
                                                                <?php if ($rqStatus == "Incomplete") { ?>
                                                                    <th style="">&nbsp;</th>
                                                                <?php } ?>        
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
                <?php
            } else if ($vwtyp == 20) {
                /* All Attached Documents */
                //sbmtdScmCnsgnRtrnID
                $sbmtdScmCnsgnRtrnID = isset($_POST['sbmtdScmCnsgnRtrnID']) ? cleanInputData($_POST['sbmtdScmCnsgnRtrnID']) : -1;
                if (!$canAdd || ($sbmtdScmCnsgnRtrnID > 0 && !$canEdt)) {
                    restricted();
                    exit();
                }
                $pkID = $sbmtdScmCnsgnRtrnID;
                $total = get_Total_CnsgnRcpt_Attachments($srchFor, $pkID, "RETURN");
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $attchSQL = "";
                $result2 = get_CnsgnRcpt_Attachments($srchFor, $curIdx, $lmtSze, $pkID, "RETURN", $attchSQL);
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-4";
            ?>
                <fieldset class="" style="padding:10px 0px 5px 0px !important;">
                    <form class="" id="attchdCnsgnRtrnDocsTblForm">
                        <div class="row">
                            <?php
                            $nwRowHtml = urlencode("<tr id=\"attchdCnsgnRtrnDocsRow__WWW123WWW\">"
                                . "<td class=\"lovtd\"><span>New</span></td>"
                                . "<td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                              <div class=\"input-group\" style=\"width:100% !important;\">
                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"attchdCnsgnRtrnDocsRow_WWW123WWW_DocCtgryNm\" value=\"\">
                                                <input class=\"form-control\" aria-label=\"...\" id=\"attchdCnsgnRtrnDocsRow_WWW123WWW_DocFile\" type=\"file\" style=\"visibility:hidden;height:5px !important;display:none;\" />     
                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Attachment Document Categories', '', '', '', 'radio', true, '', 'attchdCnsgnRtrnDocsRow_WWW123WWW_DocCtgryNm', 'attchdCnsgnRtrnDocsRow_WWW123WWW_DocCtgryNm', 'clear', 0, '');\">
                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                </label>
                                              </div>
                                              </div>
                                              <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attchdCnsgnRtrnDocsRow_WWW123WWW_AttchdDocsID\" value=\"-1\" style=\"\">                                               
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"uploadFileToCnsgnRtrnDocs('attchdCnsgnRtrnDocsRow_WWW123WWW_DocFile','attchdCnsgnRtrnDocsRow_WWW123WWW_AttchdDocsID','attchdCnsgnRtrnDocsRow_WWW123WWW_DocCtgryNm'," . $pkID . ",'attchdCnsgnRtrnDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Download Document\">
                                                    <img src=\"cmn_images/openfileicon.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\"> Upload
                                                </button>
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAttchdCnsgnRtrnDoc('attchdCnsgnRtrnDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Document\">
                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                </button>
                                          </td>
                                        </tr>");
                            ?>
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 1px !important;">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('attchdCnsgnRtrnDocsTable', 0, '<?php echo $nwRowHtml; ?>');" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Document
                                    </button>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="attchdCnsgnRtrnDocsSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                            echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                            ?>" onkeyup="enterKeyFuncAttchdCnsgnRtrnDocs(event, '', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdScmCnsgnRtrnID=<?php echo $sbmtdScmCnsgnRtrnID; ?>', 'ReloadDialog');">
                                    <input id="attchdCnsgnRtrnDocsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdCnsgnRtrnDocs('clear', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdScmCnsgnRtrnID=<?php echo $sbmtdScmCnsgnRtrnID; ?>', 'ReloadDialog');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdCnsgnRtrnDocs('', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdScmCnsgnRtrnID=<?php echo $sbmtdScmCnsgnRtrnID; ?>', 'ReloadDialog');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="attchdCnsgnRtrnDocsDsplySze" style="min-width:70px !important;">
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
                                            <a class="rhopagination" href="javascript:getAttchdCnsgnRtrnDocs('previous', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdScmCnsgnRtrnID=<?php echo $sbmtdScmCnsgnRtrnID; ?>','ReloadDialog');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAttchdCnsgnRtrnDocs('next', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdScmCnsgnRtrnID=<?php echo $sbmtdScmCnsgnRtrnID; ?>','ReloadDialog');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="attchdCnsgnRtrnDocsTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
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
                                            $doc_src = $ftp_base_db_fldr . "/Rcpts/" . $row2[3];
                                            $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                                            if (file_exists($doc_src)) {
                                                //file exists!
                                            } else {
                                                //file does not exist.
                                                $doc_src_encrpt = "None";
                                            }
                                        ?>
                                            <tr id="attchdCnsgnRtrnDocsRow_<?php echo $cntr; ?>">
                                                <td class="lovtd"><span><?php
                                                                        echo ($curIdx * $lmtSze) + ($cntr);
                                                                        ?></span></td>
                                                <td class="lovtd">
                                                    <span><?php echo $row2[2]; ?></span>
                                                    <input type="hidden" class="form-control" aria-label="..." id="attchdCnsgnRtrnDocsRow<?php echo $cntr; ?>_AttchdDocsID" value="<?php echo $row2[0]; ?>" style="width:100% !important;">
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
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttchdCnsgnRtrnDoc('attchdCnsgnRtrnDocsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document">
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
?>