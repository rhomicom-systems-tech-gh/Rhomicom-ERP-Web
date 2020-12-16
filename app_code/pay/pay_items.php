<?php
$canAdd = test_prmssns($dfltPrvldgs[26], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[27], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[28], $mdlNm);
$canVwRcHstry = test_prmssns($dfltPrvldgs[7], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$pageNo1 = isset($_POST['pageNo1']) ? cleanInputData($_POST['pageNo1']) : 1;
$pageNo2 = isset($_POST['pageNo2']) ? cleanInputData($_POST['pageNo2']) : 1;
$pageNo3 = isset($_POST['pageNo3']) ? cleanInputData($_POST['pageNo3']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Pay Item */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deletePayItem($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Pay Item Feed */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteItemFeed($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 3) {
                /* Delete Pay Item Value */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteItemVal($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Pay Item
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $payPayItmsID = isset($_POST['payPayItmsID']) ? (int) cleanInputData($_POST['payPayItmsID']) : -1;
                $payPayItmsName = isset($_POST['payPayItmsName']) ? cleanInputData($_POST['payPayItmsName']) : "";
                $payPayItmsDesc = isset($_POST['payPayItmsDesc']) ? cleanInputData($_POST['payPayItmsDesc']) : "";
                $payPayItmsLocClsfctn = isset($_POST['payPayItmsLocClsfctn']) ? cleanInputData($_POST['payPayItmsLocClsfctn']) : "";
                $payPayItmsMajTyp = isset($_POST['payPayItmsMajTyp']) ? cleanInputData($_POST['payPayItmsMajTyp']) : "";
                $payPayItmsMinTyp = isset($_POST['payPayItmsMinTyp']) ? cleanInputData($_POST['payPayItmsMinTyp']) : "";
                $payPayItmsUOM = isset($_POST['payPayItmsUOM']) ? cleanInputData($_POST['payPayItmsUOM']) : "";
                $payPayItmsPyaFeq = isset($_POST['payPayItmsPyaFeq']) ? cleanInputData($_POST['payPayItmsPyaFeq']) : "";
                $payPayItmsBalsType = isset($_POST['payPayItmsBalsType']) ? cleanInputData($_POST['payPayItmsBalsType']) : "";
                $payPayItmsEffctOrg = isset($_POST['payPayItmsEffctOrg']) ? cleanInputData($_POST['payPayItmsEffctOrg']) : "";
                $payPayItmsRunPriority = isset($_POST['payPayItmsRunPriority']) ? (float) cleanInputData($_POST['payPayItmsRunPriority']) : 1500;

                $payPayItmsIsEnbld = isset($_POST['payPayItmsIsEnbld']) ? (cleanInputData($_POST['payPayItmsIsEnbld']) == "YES" ? TRUE : FALSE) : FALSE;
                $payPayItmsAllowEdtng = isset($_POST['payPayItmsAllowEdtng']) ? (cleanInputData($_POST['payPayItmsAllowEdtng']) == "YES" ? TRUE : FALSE) : FALSE;
                $payPayItmsCreatesActng = isset($_POST['payPayItmsCreatesActng']) ? (cleanInputData($_POST['payPayItmsCreatesActng']) == "YES" ? TRUE : FALSE) : FALSE;
                $payPayItmsUsesSQL = isset($_POST['payPayItmsUsesSQL']) ? (cleanInputData($_POST['payPayItmsUsesSQL']) == "YES" ? TRUE : FALSE) : FALSE;
                $payPayItmsIsRetro = isset($_POST['payPayItmsIsRetro']) ? (cleanInputData($_POST['payPayItmsIsRetro']) == "YES" ? TRUE : FALSE) : FALSE;

                $payPayItmsRetroItmID = isset($_POST['payPayItmsRetroItmID']) ? (int) cleanInputData($_POST['payPayItmsRetroItmID']) : -1;
                $payPayItmsInvItmID = isset($_POST['payPayItmsInvItmID']) ? (int) cleanInputData($_POST['payPayItmsInvItmID']) : -1;
                $payPayItmsIncrsDcrs1 = isset($_POST['payPayItmsIncrsDcrs1']) ? cleanInputData($_POST['payPayItmsIncrsDcrs1']) : "";
                $payPayItmsCostAcntID = isset($_POST['payPayItmsCostAcntID']) ? (int) cleanInputData($_POST['payPayItmsCostAcntID']) : -1;
                $payPayItmsIncrsDcrs2 = isset($_POST['payPayItmsIncrsDcrs2']) ? cleanInputData($_POST['payPayItmsIncrsDcrs2']) : "";
                $payPayItmsBalsAcntID = isset($_POST['payPayItmsBalsAcntID']) ? (int) cleanInputData($_POST['payPayItmsBalsAcntID']) : -1;

                $slctdItemIDs = isset($_POST['slctdItemIDs']) ? cleanInputData($_POST['slctdItemIDs']) : "";
                $slctdItemValueIDs = isset($_POST['slctdItemValueIDs']) ? cleanInputData($_POST['slctdItemValueIDs']) : "";
                $slctdExtraInfoLines = isset($_POST['slctdExtraInfoLines']) ? cleanInputData($_POST['slctdExtraInfoLines']) : "";

                $exitErrMsg = "";
                if ($payPayItmsName == "") {
                    $exitErrMsg .= "Please enter Pay Item Name!<br/>";
                }
                if ($payPayItmsMajTyp == "" || $payPayItmsMinTyp == "") {
                    $exitErrMsg .= "Please enter Item Major and Minor Type!<br/>";
                }
                if ($payPayItmsUOM == "") {
                    $exitErrMsg .= "Please enter UOM!<br/>";
                }
                if ($payPayItmsEffctOrg == "") {
                    $exitErrMsg .= "Please enter Effect on Person's Debt!<br/>";
                }
                if ($payPayItmsCreatesActng == true) {
                    if ($payPayItmsIncrsDcrs1 == "None" || $payPayItmsCostAcntID <= 0) {
                        $exitErrMsg .= "Costing Account CANNOT be EMPTY!";
                    }
                    if ($payPayItmsIncrsDcrs2 == "None" || $payPayItmsBalsAcntID <= 0) {
                        $exitErrMsg .= "Balancing Account CANNOT be EMPTY!";
                    }
                }
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['payPayItmsID'] = $payPayItmsID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $oldID = getItmID($payPayItmsName, $orgID);
                if (($oldID <= 0 || $oldID == $payPayItmsID)) {
                    if ($payPayItmsID <= 0) {
                        createItm(
                            $orgID,
                            $payPayItmsName,
                            $payPayItmsDesc,
                            $payPayItmsMajTyp,
                            $payPayItmsMinTyp,
                            $payPayItmsUOM,
                            $payPayItmsUsesSQL,
                            $payPayItmsIsEnbld,
                            $payPayItmsCostAcntID,
                            $payPayItmsBalsAcntID,
                            $payPayItmsPyaFeq,
                            $payPayItmsLocClsfctn,
                            $payPayItmsRunPriority,
                            $payPayItmsIncrsDcrs1,
                            $payPayItmsIncrsDcrs2,
                            $payPayItmsBalsType,
                            100,
                            $payPayItmsIsRetro,
                            $payPayItmsRetroItmID,
                            $payPayItmsInvItmID,
                            $payPayItmsAllowEdtng,
                            $payPayItmsCreatesActng,
                            $payPayItmsEffctOrg
                        );
                        $payPayItmsID = getItmID($payPayItmsName, $orgID);
                    } else {
                        updateItm(
                            $orgID,
                            $payPayItmsID,
                            $payPayItmsName,
                            $payPayItmsDesc,
                            $payPayItmsMajTyp,
                            $payPayItmsMinTyp,
                            $payPayItmsUOM,
                            $payPayItmsUsesSQL,
                            $payPayItmsIsEnbld,
                            $payPayItmsCostAcntID,
                            $payPayItmsBalsAcntID,
                            $payPayItmsPyaFeq,
                            $payPayItmsLocClsfctn,
                            $payPayItmsRunPriority,
                            $payPayItmsIncrsDcrs1,
                            $payPayItmsIncrsDcrs2,
                            $payPayItmsBalsType,
                            100,
                            $payPayItmsIsRetro,
                            $payPayItmsRetroItmID,
                            $payPayItmsInvItmID,
                            $payPayItmsAllowEdtng,
                            $payPayItmsCreatesActng,
                            $payPayItmsEffctOrg
                        );
                    }

                    $afftctd = 0;
                    $afftctd1 = 0;
                    $afftctd2 = 0;
                    if (trim($slctdItemIDs, "|~") != "" && $payPayItmsID > 0) {
                        $variousRows = explode("|", trim($slctdItemIDs, "|"));
                        for ($y = 0; $y < count($variousRows); $y++) {
                            $crntRow = explode("~", $variousRows[$y]);
                            if (count($crntRow) == 4) {
                                $ln_TrnsLnID = (float) (cleanInputData1($crntRow[0]));
                                $ln_ItmID = (float) cleanInputData1($crntRow[1]);
                                $ln_Action = cleanInputData1($crntRow[2]);
                                $ln_ScaleFctr = (float) cleanInputData1($crntRow[3]);
                                $errMsg = "";
                                if ($ln_Action === "" || $ln_ItmID <= 0) {
                                    $errMsg = "Row " . ($y + 1) . ":- Item Feed and Action cannot be empty!<br/>";
                                }
                                if ($errMsg === "") {
                                    if ($ln_Action != "" && $ln_ItmID > 0 && $ln_ScaleFctr != 0) {
                                        $itmid = $payPayItmsID;
                                        $balsItmID = $ln_ItmID;
                                        if ($payPayItmsMajTyp == "Balance Item") {
                                            $itmid = $ln_ItmID;
                                            $balsItmID = $payPayItmsID;
                                        }
                                        if ($ln_TrnsLnID <= 0) {
                                            $afftctd += createItmFeed($itmid, $balsItmID, $ln_Action, $ln_ScaleFctr);
                                        } else {
                                            $afftctd += updateItmFeed($ln_TrnsLnID, $itmid, $balsItmID, $ln_Action, $ln_ScaleFctr);
                                        }
                                    }
                                } else {
                                    $exitErrMsg .= $errMsg;
                                }
                            }
                        }
                    }

                    if (trim($slctdItemValueIDs, "|~") != "" && $payPayItmsID > 0) {
                        $variousRows = explode("|", trim($slctdItemValueIDs, "|"));
                        for ($y = 0; $y < count($variousRows); $y++) {
                            $crntRow = explode("~", $variousRows[$y]);
                            if (count($crntRow) == 4) {
                                $ln_ItemValID = (float) (cleanInputData1($crntRow[0]));
                                $ln_ValNm = cleanInputData1($crntRow[1]);
                                $ln_ValSQL = cleanInputData1($crntRow[2]);
                                $ln_ValAmnt = (float) cleanInputData1($crntRow[3]);
                                $errMsg = "";
                                if ($payPayItmsUsesSQL == true && $ln_ValSQL == "") {
                                    $ln_ValSQL = "Select 0";
                                    //$errMsg .= "Row " . ($y + 1) . ":- Item Value SQL cannot be empty!<br/>";
                                }
                                if ($payPayItmsUsesSQL == true && strlen($ln_ValSQL) <= 6) {
                                    $ln_ValSQL = "Select 0";
                                }
                                if ($payPayItmsUsesSQL == true && $ln_ValSQL != "") {
                                    if (!isItmValSQLValid($ln_ValSQL, $prsnid, $orgID, $gnrlTrnsDteDMYHMS, -1, -1, $errMsg)) {
                                        $errMsg .= "SQL is NOT valid!";
                                        //$ln_ValSQL = "Select 0";
                                    }
                                }
                                $exitErrMsg .= $errMsg;
                                if ($ln_ItemValID <= 0) {
                                    $afftctd2 += createItmVal($payPayItmsID, $ln_ValAmnt, $ln_ValSQL, $ln_ValNm);
                                } else {
                                    $afftctd2 += updateItmVal($ln_ItemValID, $payPayItmsID, $ln_ValAmnt, $ln_ValSQL, $ln_ValNm);
                                }
                            }
                        }
                    }
                    if (trim($slctdExtraInfoLines, "|~") != "" && $payPayItmsID > 0) {
                        $variousRows = explode("|", trim($slctdExtraInfoLines, "|"));
                        for ($y = 0; $y < count($variousRows); $y++) {
                            $crntRow = explode("~", $variousRows[$y]);
                            if (count($crntRow) == 6) {
                                $ln_DfltRowID = (float) (cleanInputData1($crntRow[0]));
                                $ln_CombntnID = (float) cleanInputData1($crntRow[1]);
                                $ln_TableID = (float) cleanInputData1($crntRow[2]);
                                $ln_extrInfoCtgry = cleanInputData1($crntRow[3]);
                                $ln_extrInfoLbl = cleanInputData1($crntRow[4]);
                                $ln_Value = cleanInputData1($crntRow[5]);
                                if ($ln_DfltRowID > 0) {
                                    $afftctd1 += updateRowOthrInfVal(
                                        "org.org_all_other_info_table",
                                        $ln_CombntnID,
                                        $sbmtdAccbRcvblsInvcID,
                                        $ln_Value,
                                        $ln_extrInfoLbl,
                                        $ln_extrInfoCtgry,
                                        $ln_DfltRowID
                                    );
                                } else {
                                    if (doesRowHvOthrInfo("org.org_all_other_info_table", $ln_CombntnID, $sbmtdAccbRcvblsInvcID) > 0) {
                                        $afftctd1 += updateRowOthrInfVal(
                                            "org.org_all_other_info_table",
                                            $ln_CombntnID,
                                            $sbmtdAccbRcvblsInvcID,
                                            $ln_Value,
                                            $ln_extrInfoLbl,
                                            $ln_extrInfoCtgry,
                                            $ln_DfltRowID
                                        );
                                    } else {
                                        $ln_DfltRowID = getNewExtInfoID("org.org_all_other_info_table_dflt_row_id_seq");
                                        $afftctd1 += createRowOthrInfVal(
                                            "org.org_all_other_info_table",
                                            $ln_CombntnID,
                                            $sbmtdAccbRcvblsInvcID,
                                            $ln_Value,
                                            $ln_extrInfoLbl,
                                            $ln_extrInfoCtgry,
                                            $ln_DfltRowID
                                        );
                                    }
                                }
                            }
                        }
                    }
                    if ($exitErrMsg != "") {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Pay Item Successfully Saved!"
                            . "<br/>" . $afftctd . " Pay Item Feed(s) Saved Successfully!"
                            . "<br/>" . $afftctd2 . " Pay Item Value(s) Saved Successfully!"
                            . "<br/>" . $afftctd1 . " Pay Item Extra Information Saved Successfully!"
                            . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    } else {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Pay Item Successfully Saved!"
                            . "<br/>" . $afftctd . " Pay Item Feed(s) Saved Successfully!"
                            . "<br/>" . $afftctd2 . " Pay Item Value(s) Saved Successfully!"
                            . "<br/>" . $afftctd1 . " Pay Item Extra Information Saved Successfully!";
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['payPayItmsID'] = $payPayItmsID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Either the New Item Name is in Use <br/>or Data Supplied is Incomplete!</span>";
                    $arr_content['percent'] = 100;
                    $arr_content['payPayItmsID'] = $payPayItmsID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 2) {
                //Run Mass Pay Run
            } else if ($actyp == 901) {
                //Import Pay Items
                $dataToSend = trim(cleanInputData($_POST['dataToSend']), "|~");
                session_write_close();
                $affctd = 0;
                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                if ($dataToSend != "") {
                    $variousRows = explode("|", $dataToSend);
                    $total = count($variousRows);
                    for ($z = 0; $z < $total; $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 27) {
                            $payPayItmsID = -1;
                            $payPayItmsName = trim(cleanInputData1($crntRow[0]));
                            $payPayItmsDesc = trim(cleanInputData1($crntRow[1]));
                            $payPayItmsMajTyp = trim(cleanInputData1($crntRow[2]));
                            $payPayItmsMinTyp = trim(cleanInputData1($crntRow[3]));
                            $payPayItmsUOM = trim(cleanInputData1($crntRow[4]));
                            $payPayItmsPyaFeq = trim(cleanInputData1($crntRow[5]));
                            $payPayItmsRunPriority = trim(cleanInputData1($crntRow[6]));
                            $payPayItmsUsesSQL = trim(cleanInputData1($crntRow[7]));
                            $payPayItmsLocClsfctn = trim(cleanInputData1($crntRow[8]));
                            $payPayItmsBalsType = trim(cleanInputData1($crntRow[9]));
                            $payPayItmsIncrsDcrs1 = trim(cleanInputData1($crntRow[10]));
                            $payPayItmsCostAcntNm = ltrim(trim(cleanInputData1($crntRow[11])), '\'');
                            $payPayItmsIncrsDcrs2 = trim(cleanInputData1($crntRow[12]));
                            $payPayItmsBalsAcntNm = ltrim(trim(cleanInputData1($crntRow[13])), '\'');
                            $payPayItmsFeedInto = trim(cleanInputData1($crntRow[14]));
                            $payPayItmsAddSbtrct = trim(cleanInputData1($crntRow[15]));
                            $payPayItmsAddSbtScale = trim(cleanInputData1($crntRow[16]));
                            $payPayItmsIsRetro = trim(cleanInputData1($crntRow[17]));
                            $payPayItmsRetroItmNm = trim(cleanInputData1($crntRow[18]));
                            $payPayItmsInvItmNm = trim(cleanInputData1($crntRow[19]));
                            $payPayItmsAllowEdtng = trim(cleanInputData1($crntRow[20]));
                            $payPayItmsCreatesActng = trim(cleanInputData1($crntRow[21]));
                            $payPayItmsEffctOrg = trim(cleanInputData1($crntRow[22]));
                            $payPayItmsIsEnbld = trim(cleanInputData1($crntRow[23]));
                            $payPayItmsValueNm = trim(cleanInputData1($crntRow[24]));
                            $payPayItmsValueAmt = trim(cleanInputData1($crntRow[25]));
                            $payPayItmsValueSQL = trim(cleanInputData1($crntRow[26]));

                            $payPayItmsRetroItmID = -1;
                            $payPayItmsInvItmID = -1;
                            $payPayItmsFeedIntoID = -1;
                            $payPayItmsCostAcntID = -1;
                            $payPayItmsBalsAcntID = -1;

                            if ($z == 0) {
                                if (
                                    strtoupper($payPayItmsName) == strtoupper("Item Code/Name**")
                                    && strtoupper($payPayItmsDesc) == strtoupper("Item Description")
                                    && strtoupper($payPayItmsCreatesActng) == strtoupper("Creates Accounting?(YES/NO)")
                                    && strtoupper($payPayItmsValueSQL) == strtoupper("SQL Formula")
                                ) {
                                    continue;
                                } else {
                                    $arr_content['percent'] = 100;
                                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> Selected File is Invalid!";
                                    //.strtoupper($number) ."|". strtoupper($processName) ."|". strtoupper($isEnbld1 == "IS ENABLED?");
                                    $arr_content['msgcount'] = $total;
                                    file_put_contents(
                                        $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_PayItemsHdrimport_progress.rho",
                                        json_encode($arr_content)
                                    );
                                    break;
                                }
                            } else {
                                $payPayItmsUsesSQL = ($payPayItmsUsesSQL == "YES") ? TRUE : FALSE;
                                $payPayItmsIsRetro = ($payPayItmsIsRetro == "YES") ? TRUE : FALSE;
                                $payPayItmsAllowEdtng = ($payPayItmsAllowEdtng == "YES") ? TRUE : FALSE;
                                $payPayItmsCreatesActng = ($payPayItmsCreatesActng == "YES") ? TRUE : FALSE;
                                $payPayItmsIsEnbld = ($payPayItmsIsEnbld == "YES") ? TRUE : FALSE;
                                $payPayItmsValueAmt = (float)$payPayItmsValueAmt;
                                $payPayItmsRunPriority = (float)$payPayItmsRunPriority;
                                $payPayItmsAddSbtScale = (float) $payPayItmsAddSbtScale;

                                $payPayItmsRetroItmID = getItmID($payPayItmsRetroItmNm, $orgID);
                                $payPayItmsInvItmID = getInvItmID($payPayItmsInvItmNm, $orgID);
                                $payPayItmsFeedIntoID = getItmID($payPayItmsFeedInto, $orgID);
                                $payPayItmsCostAcntID = getAccntID($payPayItmsCostAcntNm, $orgID);
                                $payPayItmsBalsAcntID = getAccntID($payPayItmsBalsAcntNm, $orgID);
                                //$msg = $payPayItmsName . ":" . $payPayItmsDesc . ":" . $payPayItmsCreatesActng . ":" . $payPayItmsValueSQL;
                                //logSessionErrs("WWW z=" . $z . " Before Excel Import" . count($crntRow) . $msg . $gnrlTrnsDteDMYHMS);
                            }
                            $exitErrMsg = "";
                            if ($payPayItmsName == "") {
                                $exitErrMsg .= "Row " . ($z + 1) . ":-Please enter Pay Item Name!<br/>";
                            }
                            if ($payPayItmsMajTyp == "" || $payPayItmsMinTyp == "") {
                                $exitErrMsg .= "Row " . ($z + 1) . ":-Please enter Item Major and Minor Type!<br/>";
                            }
                            if ($payPayItmsUOM == "") {
                                $exitErrMsg .= "Row " . ($z + 1) . ":-Please enter UOM!<br/>";
                            }
                            if ($payPayItmsEffctOrg == "") {
                                $payPayItmsEffctOrg ="None";
                                //$exitErrMsg .= "Row " . ($z + 1) . ":-Please enter Effect on Person's Debt!<br/>";
                            }
                            if ($payPayItmsCreatesActng == true) {
                                if ($payPayItmsIncrsDcrs1 == "None" || $payPayItmsCostAcntID <= 0) {
                                    $exitErrMsg .= "Row " . ($z + 1) . ":-Costing Account CANNOT be EMPTY!";
                                }
                                if ($payPayItmsIncrsDcrs2 == "None" || $payPayItmsBalsAcntID <= 0) {
                                    $exitErrMsg .= "Row " . ($z + 1) . ":-Balancing Account CANNOT be EMPTY!";
                                }
                            }
                            if (trim($exitErrMsg) !== "") {
                                $arr_content['percent'] = 100;
                                $arr_content['payPayItmsID'] = $payPayItmsID;
                                $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                                file_put_contents(
                                    $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_PayItemsHdrimport_progress.rho",
                                    json_encode($arr_content)
                                );
                                break;
                            }
                            $payPayItmsID = getItmID($payPayItmsName, $orgID);
                            if ($payPayItmsID <= 0) {
                                $affctd +=createItm(
                                    $orgID,
                                    $payPayItmsName,
                                    $payPayItmsDesc,
                                    $payPayItmsMajTyp,
                                    $payPayItmsMinTyp,
                                    $payPayItmsUOM,
                                    $payPayItmsUsesSQL,
                                    $payPayItmsIsEnbld,
                                    $payPayItmsCostAcntID,
                                    $payPayItmsBalsAcntID,
                                    $payPayItmsPyaFeq,
                                    $payPayItmsLocClsfctn,
                                    $payPayItmsRunPriority,
                                    $payPayItmsIncrsDcrs1,
                                    $payPayItmsIncrsDcrs2,
                                    $payPayItmsBalsType,
                                    100,
                                    $payPayItmsIsRetro,
                                    $payPayItmsRetroItmID,
                                    $payPayItmsInvItmID,
                                    $payPayItmsAllowEdtng,
                                    $payPayItmsCreatesActng,
                                    $payPayItmsEffctOrg
                                );
                                $payPayItmsID = getItmID($payPayItmsName, $orgID);
                            } else {
                                $affctd +=updateItm(
                                    $orgID,
                                    $payPayItmsID,
                                    $payPayItmsName,
                                    $payPayItmsDesc,
                                    $payPayItmsMajTyp,
                                    $payPayItmsMinTyp,
                                    $payPayItmsUOM,
                                    $payPayItmsUsesSQL,
                                    $payPayItmsIsEnbld,
                                    $payPayItmsCostAcntID,
                                    $payPayItmsBalsAcntID,
                                    $payPayItmsPyaFeq,
                                    $payPayItmsLocClsfctn,
                                    $payPayItmsRunPriority,
                                    $payPayItmsIncrsDcrs1,
                                    $payPayItmsIncrsDcrs2,
                                    $payPayItmsBalsType,
                                    100,
                                    $payPayItmsIsRetro,
                                    $payPayItmsRetroItmID,
                                    $payPayItmsInvItmID,
                                    $payPayItmsAllowEdtng,
                                    $payPayItmsCreatesActng,
                                    $payPayItmsEffctOrg
                                );
                            }
                            //Import Item Feeds
                            $exitErrMsg = "";
                            if ($payPayItmsFeedIntoID > 0 && $payPayItmsID > 0) {
                                $ln_ItmID = $payPayItmsFeedIntoID;
                                $feedItmMayTyp = getGnrlRecNm("org.org_pay_items", "item_id", "item_maj_type", $payPayItmsFeedIntoID);
                                $ln_Action = $payPayItmsAddSbtrct;
                                $ln_ScaleFctr = $payPayItmsAddSbtScale;
                                $errMsg = "";
                                if ($ln_Action === "" || $ln_ItmID <= 0) {
                                    $errMsg .= "Row " . ($z + 1) . ":- Item Feed and Action cannot be empty!<br/>";
                                }
                                if (($payPayItmsMajTyp == "Balance Item" && $feedItmMayTyp == "Balance Item")
                                    || ($payPayItmsMajTyp != "Balance Item" && $feedItmMayTyp != "Balance Item")
                                ) {
                                    $errMsg .= "Row " . ($z + 1) . ":- Balance Item must be fed by non-balance item!<br/>";
                                }
                                if ($errMsg === "") {
                                    if ($ln_Action != "" && $ln_ItmID > 0 && $ln_ScaleFctr != 0) {
                                        $itmid = $payPayItmsID;
                                        $balsItmID = $ln_ItmID;
                                        if ($payPayItmsMajTyp == "Balance Item" && $feedItmMayTyp != "Balance Item") {
                                            $itmid = $ln_ItmID;
                                            $balsItmID = $payPayItmsID;
                                        }
                                        $ln_TrnsLnID = getItmFeedID($itmid, $balsItmID);
                                        if ($ln_TrnsLnID <= 0) {
                                            $afftctd1 += createItmFeed($itmid, $balsItmID, $ln_Action, $ln_ScaleFctr);
                                        } else {
                                            $afftctd1 += updateItmFeed($ln_TrnsLnID, $itmid, $balsItmID, $ln_Action, $ln_ScaleFctr);
                                        }
                                    }
                                } else {
                                    $exitErrMsg .= $errMsg;
                                }
                            }
                            //Import Item Values
                            if (trim($payPayItmsValueNm, "|~") != "" && $payPayItmsID > 0) {
                                $ln_ItemValID = getItmValID($payPayItmsValueNm, $payPayItmsID);
                                $ln_ValNm = $payPayItmsValueNm;
                                $ln_ValSQL = $payPayItmsValueSQL;
                                $ln_ValAmnt = $payPayItmsValueAmt;
                                $errMsg = "";
                                if ($payPayItmsUsesSQL == true && $ln_ValSQL == "") {
                                    $ln_ValSQL = "Select 0";
                                }
                                if ($payPayItmsUsesSQL == true && strlen($ln_ValSQL) <= 6) {
                                    $ln_ValSQL = "Select 0";
                                }
                                if ($payPayItmsUsesSQL == true && $ln_ValSQL != "") {
                                    if (!isItmValSQLValid($ln_ValSQL, $prsnid, $orgID, $gnrlTrnsDteDMYHMS, -1, -1, $errMsg)) {
                                        $errMsg .= "Row " . ($z + 1) . ":SQL is NOT valid!";
                                    }
                                }
                                $exitErrMsg .= $errMsg;
                                if ($errMsg === "") {
                                    if ($ln_ItemValID <= 0) {
                                        $afftctd2 += createItmVal($payPayItmsID, $ln_ValAmnt, $ln_ValSQL, $ln_ValNm);
                                    } else {
                                        $afftctd2 += updateItmVal($ln_ItemValID, $payPayItmsID, $ln_ValAmnt, $ln_ValSQL, $ln_ValNm);
                                    }
                                }
                            }
                            if (trim($exitErrMsg) !== "") {
                                $arr_content['percent'] = 100;
                                $arr_content['payPayItmsID'] = $payPayItmsID;
                                $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                                file_put_contents(
                                    $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_PayItemsHdrimport_progress.rho",
                                    json_encode($arr_content)
                                );
                                break;
                            }
                        }
                        $percent = round((($z + 1) / $total) * 100, 2);
                        $arr_content['percent'] = $percent;
                        if ($percent >= 100) {
                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> 100% Completed!..." . $affctd . " out of " . $total . " Pay Item(s) processed.";
                            $arr_content['msgcount'] = $total;
                        } else {
                            $arr_content['message'] = "<i class=\"fa fa-spin fa-spinner\"></i> Importing Pay Items...Please Wait..." . ($z + 1) . " out of " . $total . " Pay Item(s) processed.";
                        }
                        file_put_contents(
                            $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_PayItemsHdrimport_progress.rho",
                            json_encode($arr_content)
                        );
                    }
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['msgcount'] = "";
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_PayItemsHdrimport_progress.rho", json_encode($arr_content));
                }
            } else if ($actyp == 902) {
                //Checked Importing Process Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_PayItemsHdrimport_progress.rho";
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
            } else if ($actyp == 903) {
                //Export Asset Register
                $inptNum = isset($_POST['inptNum']) ? (int) cleanInputData($_POST['inptNum']) : 0;
                session_write_close();
                $affctd = 0;
                $errMsg = "Invalid Option!";
                if ($inptNum >= 0) {
                    $hdngs = array(
                        "Item Code/Name**", "Item Description", "Item Major Type**", "Item Minor Type**",
                        "Item UOM**", "Pay Frequency", "Pay Priority", "Uses SQL Formulas for Values?", "Local Classification",
                        "Balance Type", "Increase/Decrease Cost Account", "Cost Account Number",
                        "Increase/Decrease Balance Account", "Balance Account Number", "Balance Item it Feeds Into",
                        "Adds/Subtracts", "Scale Factor", "Is Retro?(YES/NO)", "Retro Element Name", "Inventory Item Code",
                        "Allow Value Editing?(YES/NO)", "Creates Accounting?(YES/NO)", "Effect on Person's Debt", "Is Enabled?(YES/NO)",
                        "Possible Value Code/Name**", "Value Amount", "SQL Formula"
                    );
                    $limit_size = 0;
                    if ($inptNum > 2) {
                        $limit_size = $inptNum;
                    } else if ($inptNum == 2) {
                        $limit_size = 1000000;
                    }
                    $rndm = getRandomNum(10001, 9999999);
                    $dteNm = date('dMY_His');
                    $nwFileNm = $fldrPrfx . "dwnlds/tmp/PayItemsHdrExprt_" . $dteNm . "_" . $rndm . ".csv";
                    $dwnldUrl = $app_url . "dwnlds/tmp/PayItemsHdrExprt_" . $dteNm . "_" . $rndm . ".csv";
                    $opndfile = fopen($nwFileNm, "w");
                    fputcsv($opndfile, $hdngs);
                    if ($limit_size <= 0) {
                        $arr_content['percent'] = 100;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!... Accounts Chart Template Exported.</span>";
                        $arr_content['msgcount'] = 0;
                        file_put_contents(
                            $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_PayItemsHdrexprt_progress.rho",
                            json_encode($arr_content)
                        );

                        fclose($opndfile);
                        exit();
                    }
                    $z = 0;
                    $crntRw = "";
                    $result = get_All_PayItemsNVals($orgID, $limit_size);
                    $total = loc_db_num_rows($result);
                    $fieldCntr = loc_db_num_fields($result);
                    while ($row = loc_db_fetch_array($result)) {
                        $crntRw = array(
                            $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6],
                            $row[7], $row[8], $row[9], $row[10], "'" . $row[11],
                            $row[12], "'" . $row[13], $row[14], $row[15], $row[16], $row[17], $row[18],
                            "'" . $row[19], $row[20], $row[21], $row[22], $row[23], $row[24], $row[25], $row[26]
                        );
                        fputcsv($opndfile, $crntRw);
                        //file_put_contents($nwFileNm, $crntRw, FILE_APPEND | LOCK_EX);
                        $percent = round((($z + 1) / $total) * 100, 2);
                        $arr_content['percent'] = $percent;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        if ($percent >= 100) {
                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!..." . ($z +
                                1) . " out of " . $total . " Account(s) exported.</span>";
                            $arr_content['msgcount'] = $total;
                        } else {
                            $arr_content['message'] = "<span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"><br/>Exporting Accounts...Please Wait..." . ($z +
                                1) . " out of " . $total . " Account(s) exported.</span>";
                        }
                        file_put_contents(
                            $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_PayItemsHdrexprt_progress.rho",
                            json_encode($arr_content)
                        );
                        $z++;
                    }
                    fclose($opndfile);
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['msgcount'] = "";
                    $arr_content['dwnld_url'] = "";
                    file_put_contents(
                        $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_PayItemsHdrexprt_progress.rho",
                        json_encode($arr_content)
                    );
                }
            } else if ($actyp == 904) {
                //Checked Exporting Process Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_PayItemsHdrexprt_progress.rho";
                if (file_exists($file)) {
                    $text = file_get_contents($file);
                    echo $text;

                    $obj = json_decode($text);
                    if ($obj->percent >= 100) {
                        //$rs = file_exists($file) ? unlink($file) : TRUE;
                    }
                } else {
                    echo json_encode(array("percent" => 0, "message" => '<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Not Started</span>'));
                }
            }
        } else {
            if ($vwtyp == 0) {
                $pkID = isset($_POST['sbmtdPayItmID']) ? $_POST['sbmtdPayItmID'] : -1;
                $subPgNo = isset($_POST['subPgNo']) ? (int) $_POST['subPgNo'] : 0;
                if ($subPgNo == 0) {
                    echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">All Payment Items</span>
				</li>
                               </ul>
                              </div>";
                    $total = get_Total_Itm($srchFor, $srchIn, $orgID);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }

                    $curIdx = $pageNo - 1;
                    $result = get_Basic_Itm($srchFor, $srchIn, $curIdx, $lmtSze, $orgID);
                    $cntr = 0;
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-5";
                    $colClassType3 = "col-lg-2";
?>
                    <form id='payPayItmsForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row rhoRowMargin">
                            <?php if ($canAdd === true) { ?>
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 0px 0px 0px !important;">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOnePayPayItmsForm(-1, 0);" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title=" New Bulk/Mass Pay Run">
                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New Pay Item
                                        </button>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="exprtPayItems();" data-toggle="tooltip" title="Export Pay Items">
                                            <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            Export
                                        </button>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="importPayItems();" data-toggle="tooltip" title="Import Pay Items">
                                            <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            Import
                                        </button>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="savePayPayItmsForm();" style="width:100% !important;">
                                            <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            Save
                                        </button>
                                    </div>
                                </div>
                            <?php
                            } else {
                                $colClassType1 = "col-lg-2";
                                $colClassType2 = "col-lg-5";
                            }
                            ?>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="payPayItmsSrchFor" type="text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncPayPayItms(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <input id="payPayItmsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getPayPayItms('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getPayPayItms('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="payPayItmsSrchIn">
                                        <?php
                                        $valslctdArry = array("", "");
                                        $srchInsArrys = array("Item Name", "Item Description");

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                        ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="payPayItmsDsplySze" style="min-width:70px !important;">
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
                                            <a class="rhopagination" href="javascript:getPayPayItms('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getPayPayItms('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row" style="padding:1px 15px 1px 15px !important;">
                            <hr style="margin:1px 0px 3px 0px;">
                        </div>
                        <div class="row">
                            <div class="col-md-3" style="padding:0px 1px 0px 15px !important;">
                                <fieldset class="basic_person_fs">
                                    <table class="table table-striped table-bordered table-responsive" id="payPayItmsTable" cellspacing="0" width="100%" style="width:100% !important;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Pay Item Name</th>
                                                <th>...</th>
                                                <?php if ($canVwRcHstry) { ?>
                                                    <th>...</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($row = loc_db_fetch_array($result)) {
                                                if ($pkID <= 0 && $cntr <= 0) {
                                                    $pkID = $row[0];
                                                }
                                                $cntr += 1;
                                                $itemNm = $row[1];
                                                if (strtoupper($row[2]) == strtoupper("Balance Item") || strpos($row[3], "BALANCE") !== FALSE) {
                                                    $itemNm = "<span style=\"font-weight:bold;font-size:10px !important;\">" . strtoupper($row[1]) . "</span>";
                                                }
                                            ?>
                                                <tr id="payPayItmsRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd"><?php echo $itemNm; ?>
                                                        <input type="hidden" class="form-control" aria-label="..." id="payPayItmsRow<?php echo $cntr; ?>_ItemID" value="<?php echo $row[0]; ?>">
                                                        <input type="hidden" class="form-control" aria-label="..." id="payPayItmsRow<?php echo $cntr; ?>_ItemNm" value="<?php echo $row[1]; ?>">
                                                    </td>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delPayPayItms('payPayItmsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Item">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                    echo urlencode(encrypt1(($row[0] . "|org.org_pay_items|item_id"),
                                                                                                                                                                                                                        $smplTokenWord1
                                                                                                                                                                                                                    ));
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
                                </fieldset>
                            </div>
                            <div class="col-md-9" style="padding:0px 15px 0px 1px !important">
                                <fieldset class="basic_person_fs" style="padding-top:2px !important;">
                                    <div class="container-fluid" id="payPayItmsDetailInfo">
                                        <?php
                                    }
                                    if ($subPgNo >= 0) {
                                        $payPayItmsID = -1;
                                        $payPayItmsName = "";
                                        $payPayItmsDesc = "";
                                        $payPayItmsMajTyp = "";
                                        $payPayItmsMinTyp = "";
                                        $payPayItmsUOM = "";
                                        $payPayItmsUsesSQL = "0";
                                        $payPayItmsCostAcntID = -1;
                                        $payPayItmsCostAcnt = "";
                                        $payPayItmsBalsAcntID = -1;
                                        $payPayItmsBalsAcnt = "";

                                        $payPayItmsIsEnbld = "1";
                                        $payPayItmsPyaFeq = "";

                                        $payPayItmsLocClsfctn = "";
                                        $payPayItmsRunPriority = 9000;
                                        $payPayItmsIncrsDcrs1 = "Increase";
                                        $payPayItmsIncrsDcrs2 = "Increase";
                                        $payPayItmsBalsType = "";
                                        $payPayItmsIsRetro = "0";
                                        $payPayItmsRetroItmID = -1;
                                        $payPayItmsRetroItm = "";
                                        $payPayItmsInvItmID = -1;
                                        $payPayItmsInvItm = "";
                                        $payPayItmsAllowEdtng = "0";
                                        $payPayItmsCreatesActng = "0";
                                        $payPayItmsEffctOrg = "None";
                                        $mkReadOnly = "";
                                        $result1 = get_One_Itm_Det($pkID);
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $payPayItmsID = $row1[0];
                                            $payPayItmsName = $row1[1];
                                            $payPayItmsDesc = $row1[2];
                                            $payPayItmsMajTyp = $row1[3];
                                            $payPayItmsMinTyp = $row1[4];
                                            $payPayItmsUOM = $row1[5];
                                            $payPayItmsUsesSQL = $row1[6];
                                            $payPayItmsCostAcntID = (int) $row1[7];
                                            $payPayItmsCostAcnt = $row1[23];
                                            $payPayItmsBalsAcntID = (int) $row1[8];
                                            $payPayItmsBalsAcnt = $row1[24];

                                            $payPayItmsIsEnbld = $row1[9];
                                            $payPayItmsPyaFeq = $row1[11];

                                            $payPayItmsLocClsfctn = $row1[12];
                                            $payPayItmsRunPriority = (float) $row1[13];
                                            $payPayItmsIncrsDcrs1 = $row1[14];
                                            $payPayItmsIncrsDcrs2 = $row1[15];
                                            $payPayItmsBalsType = $row1[16];
                                            $payPayItmsIsRetro = $row1[17];
                                            $payPayItmsRetroItmID = (int) $row1[18];
                                            $payPayItmsRetroItm = $row1[25];
                                            $payPayItmsInvItmID = (int) $row1[19];
                                            $payPayItmsInvItm = $row1[26];
                                            $payPayItmsAllowEdtng = $row1[20];
                                            $payPayItmsCreatesActng = $row1[21];
                                            $payPayItmsEffctOrg = $row1[22];
                                        }
                                        if ($subPgNo == 0 || $subPgNo == 1) {
                                        ?>
                                            <div class="row">
                                                <div class="col-md-6" style="padding:0px 1px 0px 0px !important;">
                                                    <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="payPayItmsName" class="control-label col-lg-4">Pay Item Name:</label>
                                                            <div class="col-lg-8">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="payPayItmsName" name="payPayItmsName" value="<?php echo $payPayItmsName; ?>" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="payPayItmsID" name="payPayItmsID" value="<?php echo $payPayItmsID; ?>">
                                                                <?php } else {
                                                                ?>
                                                                    <span><?php echo $payPayItmsName; ?></span>
                                                                <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="payPayItmsDesc" class="control-label col-lg-4">Description:</label>
                                                            <div class="col-lg-8">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="payPayItmsDesc" name="payPayItmsDesc" value="<?php echo $payPayItmsDesc; ?>" style="width:100% !important;">
                                                                <?php } else {
                                                                ?>
                                                                    <span><?php echo $payPayItmsDesc; ?></span>
                                                                <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="payPayItmsLocClsfctn" class="control-label col-md-4">Classification:</label>
                                                            <div class="col-md-8">
                                                                <div class="input-group">
                                                                    <input class="form-control" id="payPayItmsLocClsfctn" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" value="<?php echo $payPayItmsLocClsfctn; ?>" />
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Pay Item Classifications', '', '', '', 'radio', true, '', 'payPayItmsLocClsfctn', '', 'clear', 1, '', function () {});">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="payPayItmsMajTyp" class="control-label col-md-4">Item Major Type:</label>
                                                            <div class="col-md-8">
                                                                <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="payPayItmsMajTyp" style="width:100% !important;">
                                                                    <?php
                                                                    $valslctdArry = array("", "");
                                                                    $srchInsArrys = array("Balance Item", "Pay Value Item");
                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                        if ($payPayItmsMajTyp == $srchInsArrys[$z]) {
                                                                            $valslctdArry[$z] = "selected";
                                                                        }
                                                                    ?>
                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="payPayItmsMinTyp" class="control-label col-md-4">Item Minor Type:</label>
                                                            <div class="col-md-8">
                                                                <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="payPayItmsMinTyp" style="width:100% !important;">
                                                                    <?php
                                                                    $valslctdArry = array("", "", "", "", "");
                                                                    $srchInsArrys = array(
                                                                        "Bills/Charges", "Deductions", "Earnings", "Employer Charges",
                                                                        "Purely Informational"
                                                                    );
                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                        if ($payPayItmsMinTyp == $srchInsArrys[$z]) {
                                                                            $valslctdArry[$z] = "selected";
                                                                        }
                                                                    ?>
                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="payPayItmsUOM" class="control-label col-md-4">UOM:</label>
                                                            <div class="col-md-8">
                                                                <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="payPayItmsUOM" style="width:100% !important;">
                                                                    <?php
                                                                    $valslctdArry = array("", "");
                                                                    $srchInsArrys = array("Money", "Number");
                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                        if ($payPayItmsUOM == $srchInsArrys[$z]) {
                                                                            $valslctdArry[$z] = "selected";
                                                                        }
                                                                    ?>
                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="payPayItmsPyaFeq" class="control-label col-md-4">Pay Frequency:</label>
                                                            <div class="col-md-8">
                                                                <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="payPayItmsPyaFeq" style="width:100% !important;">
                                                                    <?php
                                                                    $valslctdArry = array("", "", "", "", "", "", "", "", "", "", "", "");
                                                                    $srchInsArrys = array(
                                                                        "Daily", "Weekly", "Fortnightly", "Semi-Monthly", "Monthly", "Once a Month", "Twice a Month",
                                                                        "Quarterly", "Half-Yearly", "Annually", "Adhoc", "None"
                                                                    );
                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                        if ($payPayItmsPyaFeq == $srchInsArrys[$z]) {
                                                                            $valslctdArry[$z] = "selected";
                                                                        }
                                                                    ?>
                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="payPayItmsBalsType" class="control-label col-md-4">Balance Type:</label>
                                                            <div class="col-md-8">
                                                                <select data-placeholder="Select..." class="form-control chosen-select" id="payPayItmsBalsType" style="width:100% !important;">
                                                                    <?php
                                                                    $valslctdArry = array("", "", "");
                                                                    $srchInsArrys = array("", "Cumulative", "Non-Cumulative");
                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                        if ($payPayItmsBalsType == $srchInsArrys[$z]) {
                                                                            $valslctdArry[$z] = "selected";
                                                                        }
                                                                    ?>
                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="payPayItmsEffctOrg" class="control-label col-md-4">Effect on Person's Debt:</label>
                                                            <div class="col-md-8">
                                                                <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="payPayItmsEffctOrg" style="width:100% !important;">
                                                                    <?php
                                                                    $valslctdArry = array("", "", "");
                                                                    $srchInsArrys = array("None", "Increase", "Decrease");
                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                        if ($payPayItmsEffctOrg == $srchInsArrys[$z]) {
                                                                            $valslctdArry[$z] = "selected";
                                                                        }
                                                                    ?>
                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-6" style="padding:0px 0px 0px 1px !important;">
                                                    <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <div class="col-md-3">
                                                                <label style="margin-bottom:0px !important;">Priority:</label>
                                                            </div>
                                                            <div class="col-md-9" style="padding: 0px 15px 0px 15px !important;">
                                                                <input type="number" class="form-control" aria-label="..." data-toggle="tooltip" title="Pay Run Priority" id="payPayItmsRunPriority" name="payPayItmsRunPriority" value="<?php echo $payPayItmsRunPriority; ?>" <?php echo $mkReadOnly; ?>>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:6px 0px 0px 0px !important;">
                                                            <div class="col-md-3">
                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                    <label class="form-check-label">
                                                                        <?php
                                                                        $payPayItmsIsEnbldChkd = "";
                                                                        if ($payPayItmsIsEnbld == "1") {
                                                                            $payPayItmsIsEnbldChkd = "checked=\"true\"";
                                                                        }
                                                                        ?>
                                                                        <input type="checkbox" class="form-check-input" onclick="" id="payPayItmsIsEnbld" name="payPayItmsIsEnbld" <?php echo $payPayItmsIsEnbldChkd; ?>>
                                                                        Enabled?
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                    <label class="form-check-label">
                                                                        <?php
                                                                        $payPayItmsAllowEdtngChkd = "";
                                                                        if ($payPayItmsAllowEdtng == "1") {
                                                                            $payPayItmsAllowEdtngChkd = "checked=\"true\"";
                                                                        }
                                                                        ?>
                                                                        <input type="checkbox" class="form-check-input" onclick="" id="payPayItmsAllowEdtng" name="payPayItmsAllowEdtng" <?php echo $payPayItmsAllowEdtngChkd; ?>>
                                                                        Can Edit?
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                    <label class="form-check-label">
                                                                        <?php
                                                                        $payPayItmsCreatesActngChkd = "";
                                                                        if ($payPayItmsCreatesActng == "1") {
                                                                            $payPayItmsCreatesActngChkd = "checked=\"true\"";
                                                                        }
                                                                        ?>
                                                                        <input type="checkbox" class="form-check-input" onclick="" id="payPayItmsCreatesActng" name="payPayItmsCreatesActng" <?php echo $payPayItmsCreatesActngChkd; ?>>
                                                                        Creates Accounting?
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:6px 0px 0px 0px !important;">
                                                            <label for="payPayItmsUsesSQL" class="control-label col-md-3">&nbsp;</label>
                                                            <div class="col-md-4">
                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                    <label class="form-check-label">
                                                                        <?php
                                                                        $payPayItmsUsesSQLChkd = "";
                                                                        if ($payPayItmsUsesSQL == "1") {
                                                                            $payPayItmsUsesSQLChkd = "checked=\"true\"";
                                                                        }
                                                                        ?>
                                                                        <input type="checkbox" class="form-check-input" onclick="" id="payPayItmsUsesSQL" name="payPayItmsUsesSQL" <?php echo $payPayItmsUsesSQLChkd; ?>>
                                                                        Uses SQL?
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                    <label class="form-check-label">
                                                                        <?php
                                                                        $payPayItmsIsRetroChkd = "";
                                                                        if ($payPayItmsIsRetro == "1") {
                                                                            $payPayItmsIsRetroChkd = "checked=\"true\"";
                                                                        }
                                                                        ?>
                                                                        <input type="checkbox" class="form-check-input" onclick="" id="payPayItmsIsRetro" name="payPayItmsIsRetro" <?php echo $payPayItmsIsRetroChkd; ?>>
                                                                        Is Retro Item?
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="payPayItmsRetroItm" class="control-label col-md-3">Retro Item:</label>
                                                            <div class="col-md-9">
                                                                <div class="input-group">
                                                                    <input class="form-control" id="payPayItmsItmSetNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" value="<?php echo $payPayItmsRetroItm; ?>" readonly="true" />
                                                                    <input type="hidden" id="payPayItmsRetroItmID" value="<?php echo $payPayItmsRetroItmID; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Retro Pay Items', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payPayItmsRetroItmID', 'payPayItmsRetroItm', 'clear', 1, '', function () {});">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="payPayItmsInvItm" class="control-label col-md-3">Sales Item:</label>
                                                            <div class="col-md-9">
                                                                <div class="input-group">
                                                                    <input class="form-control" id="payPayItmsInvItm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" value="<?php echo $payPayItmsInvItm; ?>" readonly="true" />
                                                                    <input type="hidden" id="payPayItmsInvItmID" value="<?php echo $payPayItmsInvItmID; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Inventory Items', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payPayItmsInvItmID', 'payPayItmsInvItm', 'clear', 1, '', function () {});">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-md-3">
                                                                <label style="margin-bottom:0px !important;">Costing Account:</label>
                                                            </div>
                                                            <div class="col-md-9" style="padding:0px 0px 0px 0px;">
                                                                <div class="col-md-12" style="padding:0px 15px 0px 15px;">
                                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="payPayItmsIncrsDcrs1" style="width:100% !important;" onchange="lnkdEvntAccbPyblsInvcChng();">
                                                                        <?php
                                                                        $valslctdArry = array("", "", "");
                                                                        $srchInsArrys = array("None", "Increase", "Decrease");
                                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                            if ($payPayItmsIncrsDcrs1 == $srchInsArrys[$z]) {
                                                                                $valslctdArry[$z] = "selected";
                                                                            }
                                                                        ?>
                                                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-12" style="padding:2px 15px 0px 15px;">
                                                                    <div class="input-group">
                                                                        <input class="form-control" id="payPayItmsCostAcnt" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Cost Account" type="text" value="<?php echo $payPayItmsCostAcnt; ?>" readonly="true" />
                                                                        <input type="hidden" id="payPayItmsCostAcntID" value="<?php echo $payPayItmsCostAcntID; ?>">
                                                                        <label id="payPayItmsCostAcntLbl" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payPayItmsCostAcntID', 'payPayItmsCostAcnt', 'clear', 1, '', function () {});">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-md-3">
                                                                <label style="margin-bottom:0px !important;">Balancing Account:</label>
                                                            </div>
                                                            <div class="col-md-9" style="padding:0px 0px 0px 0px;">
                                                                <div class="col-md-12" style="padding:0px 15px 0px 15px;">
                                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="payPayItmsIncrsDcrs2" style="width:100% !important;" onchange="lnkdEvntAccbPyblsInvcChng();">
                                                                        <?php
                                                                        $valslctdArry = array("", "", "");
                                                                        $srchInsArrys = array("None", "Increase", "Decrease");
                                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                            if ($payPayItmsIncrsDcrs2 == $srchInsArrys[$z]) {
                                                                                $valslctdArry[$z] = "selected";
                                                                            }
                                                                        ?>
                                                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-12" style="padding:2px 15px 0px 15px;">
                                                                    <div class="input-group">
                                                                        <input class="form-control" id="payPayItmsBalsAcnt" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Balancing Account" type="text" value="<?php echo $payPayItmsBalsAcnt; ?>" readonly="true" />
                                                                        <input type="hidden" id="payPayItmsBalsAcntID" value="<?php echo $payPayItmsBalsAcntID; ?>">
                                                                        <label id="payPayItmsCostAcntLbl" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payPayItmsBalsAcntID', 'payPayItmsBalsAcnt', 'clear', 1, '', function () {});">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                    <ul class="nav nav-tabs" style="margin-top:1px !important;">
                                                        <li class="active"><a data-toggle="tabajxpaypayitms" data-rhodata="" href="#payPayItmsBalsFeeds" id="payPayItmsBalsFeedstab">Item Feeds</a></li>
                                                        <li class=""><a data-toggle="tabajxpaypayitms" data-rhodata="" href="#payPayItmsPsblVals" id="payPayItmsPsblValstab">Item Values</a></li>
                                                        <li class=""><a data-toggle="tabajxpaypayitms" data-rhodata="" href="#payPayItmsExtraInfo" id="payPayItmsExtraInfotab">Extra Information</a></li>
                                                    </ul>
                                                    <div class="custDiv" style="padding:0px !important;min-height: 30px !important;">
                                                        <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                        <?php
                                                                        $payItmFdItmLOV = "Balance Items";
                                                                        if ($payPayItmsMajTyp == "Balance Item") {
                                                                            $payItmFdItmLOV = "Non-Balance Items";
                                                                        }
                                                                        $nwRowHtml2 = "<tr id=\"payPayItmsBalsFeedsRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#payPayItmsBalsFeedsTable tr').index(this));\">"
                                                                            . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                                                            . "<td class=\"lovtd\">  
                                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"payPayItmsBalsFeedsRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">   
                                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"payPayItmsBalsFeedsRow_WWW123WWW_ItemID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"payPayItmsBalsFeedsRow_WWW123WWW_ItemNm\" name=\"payPayItmsBalsFeedsRow_WWW123WWW_ItemNm\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '" . $payItmFdItmLOV . "', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payPayItmsBalsFeedsRow_WWW123WWW_ItemID', 'payPayItmsBalsFeedsRow_WWW123WWW_ItemNm', 'clear', 1, '', function () {});\">
                                                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                                    </label>
                                                                                                </div>                                           
                                                                                        </td>  
                                                                                        <td class=\"lovtd\">
                                                                                            <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"payPayItmsBalsFeedsRow_WWW123WWW_Action\" style=\"width:100% !important;\">";
                                                                        $valslctdArry = array("", "");
                                                                        $srchInsArrys = array("Adds", "Subtracts");
                                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                            $nwRowHtml2 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                                                        }
                                                                        $nwRowHtml2 .= "</select>
                                                                                        </td>
                                                                                        <td class=\"lovtd\">
                                                                                            <input type=\"text\" class=\"form-control rqrdFld jbDetDbt\" aria-label=\"...\" id=\"payPayItmsBalsFeedsRow_WWW123WWW_ScaleFctr\" name=\"payPayItmsBalsFeedsRow_WWW123WWW_ScaleFctr\" value=\"1.00\" onkeypress=\"gnrlFldKeyPress(event, 'payPayItmsBalsFeedsRow_WWW123WWW_ScaleFctr', 'payPayItmsBalsFeedsTable', 'jbDetDbt');\" style=\"width:100% !important;text-align: right;\">                                                    
                                                                                        </td>";
                                                                        $nwRowHtml2 .= "<td class=\"lovtd\">
                                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delPayPayItmFeeds('payPayItmsBalsFeedsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Item Feed\">
                                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                            </button>
                                                                        </td>";

                                                                        if ($canVwRcHstry === true) {
                                                                            $nwRowHtml2 .= "<td class=\"lovtd\">&nbsp;</td>";
                                                                        }
                                                                        $nwRowHtml2 .= "</tr>";
                                                                        $nwRowHtml2 = urlencode($nwRowHtml2);


                                                                        $nwRowHtml3 = "<tr id=\"payPayItmsPsblValsRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#payPayItmsPsblValsTable tr').index(this));\">"
                                                                            . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                                                            . "<td class=\"lovtd\"  style=\"\">  
                                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"payPayItmsPsblValsRow_WWW123WWW_ItemID\" value=\"-1\" style=\"width:100% !important;\">
                                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"payPayItmsPsblValsRow_WWW123WWW_ItemValID\" value=\"-1\" style=\"width:100% !important;\">
                                                                                            <input type=\"text\" class=\"form-control  jbDetDesc\" id=\"payPayItmsPsblValsRow_WWW123WWW_ValNm\"  
                                                                                                   onkeypress=\"gnrlFldKeyPress(event, 'payPayItmsPsblValsRow_WWW123WWW_ValNm', 'payPayItmsPsblValsTable', 'jbDetDesc');\" 
                                                                                                   value=\"\" style=\"width:100% !important;\"/>                                                
                                                                                        </td>
                                                                                        <td class=\"lovtd\" style=\"text-align: right;\">";
                                                                        if ($payPayItmsUsesSQL == "1") {
                                                                            $nwRowHtml3 .= "<div class=\"input-group\"  style=\"width:100%;\">
                                                                                                    <textarea class=\"form-control rqrdFld\" rows=\"5\" cols=\"20\" id=\"payPayItmsPsblValsRow_WWW123WWW_ValSQL\" name=\"payPayItmsPsblValsRow_WWW123WWW_ValSQL\" style=\"text-align:left !important;\"></textarea>
                                                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"popUpDisplay('payPayItmsPsblValsRow_WWW123WWW_ValSQL');\" style=\"max-width:30px;width:30px;\">
                                                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                                    </label>";
                                                                        } else {
                                                                            $nwRowHtml3 .= "<input type=\"text\" class=\"form-control  jbDetDbt\" id=\"payPayItmsPsblValsRow_WWW123WWW_ValAmnt\"  
                                                                                                           onkeypress=\"gnrlFldKeyPress(event, 'payPayItmsPsblValsRow_WWW123WWW_ValAmnt', 'payPayItmsPsblValsTable', 'jbDetDbt');\" 
                                                                                                           onblur=\"fmtAsNumber('payPayItmsPsblValsRow_WWW123WWW_ValAmnt');\" 
                                                                                                           style=\"font-weight:bold;font-size:12px;color:blue;text-align: right;width:100% !important;\" 
                                                                                                           value=\"0.00\"/>";
                                                                        }
                                                                        $nwRowHtml3 .= "</td>";
                                                                        $nwRowHtml3 .= "<td class=\"lovtd\">
                                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delPayPayItmsAttchdVal('payPayItmsPsblValsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Possible Value\">
                                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                            </button>
                                                                        </td>";

                                                                        if ($canVwRcHstry === true) {
                                                                            $nwRowHtml3 .= "<td class=\"lovtd\">&nbsp;</td>";
                                                                        }
                                                                        $nwRowHtml3 .= "</tr>";
                                                                        $nwRowHtml3 = urlencode($nwRowHtml3);
                                                                        ?>
                                                                        <div class="col-md-6" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                            <div class="col-md-8" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                                <div class="input-group">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getPayPayItmsDt('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>')">
                                                                                        <span class="glyphicon glyphicon-search"></span>
                                                                                    </label>
                                                                                    <input id="payPayItmsDtPageNo1" type="hidden" value="<?php echo $pageNo1; ?>">
                                                                                    <input id="payPayItmsDtPageNo2" type="hidden" value="<?php echo $pageNo2; ?>">
                                                                                    <input id="payPayItmsDtPageNo3" type="hidden" value="<?php echo $pageNo3; ?>">
                                                                                    <input id="payPayItmsDtTabNo" type="hidden" value="1">
                                                                                    <!--<input class="form-control" id="payPayItmsDtSrchFor" type = "text" placeholder="Search For" value="<?php
                                                                                                                                                                                            echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                                                                            ?>" onkeyup="enterKeyFuncPayPayItmsDt(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>')">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getPayPayItmsDt('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>')">
                                                                                        <span class="glyphicon glyphicon-remove"></span>
                                                                                    </label>
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="payPayItmsDtSrchIn">
                                                                                    <?php
                                                                                    $valslctdArry = array("", "");
                                                                                    $srchInsArrys = array("Person Name/ID No.", "Item Name");

                                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                        if ($srchIn == $srchInsArrys[$z]) {
                                                                                            $valslctdArry[$z] = "selected";
                                                                                        }
                                                                                    ?>
                                                                                                                                                                                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                    <?php } ?>
                                                                                    </select>-->
                                                                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="payPayItmsDtDsplySze" style="min-width:70px !important;">
                                                                                        <?php
                                                                                        $valslctdArry = array(
                                                                                            "", "", "", "", "", "", "", "",
                                                                                            "", "", "", ""
                                                                                        );
                                                                                        $dsplySzeArry = array(
                                                                                            1, 5, 10, 15, 30, 50, 100, 500,
                                                                                            1000, 5000, 10000, 10000000
                                                                                        );
                                                                                        for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                                                                            if (100 == $dsplySzeArry[$y]) {
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
                                                                            <div class="col-md-4" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                                <nav aria-label="Page navigation">
                                                                                    <ul class="pagination" style="margin: 0px !important;">
                                                                                        <li>
                                                                                            <a class="rhopagination" href="javascript:getPayPayItmsDt('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>');" aria-label="Previous">
                                                                                                <span aria-hidden="true">&laquo;</span>
                                                                                            </a>
                                                                                        </li>
                                                                                        <li>
                                                                                            <a class="rhopagination" href="javascript:getPayPayItmsDt('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>');" aria-label="Next">
                                                                                                <span aria-hidden="true">&raquo;</span>
                                                                                            </a>
                                                                                        </li>
                                                                                    </ul>
                                                                                </nav>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                            <?php
                                                                            if ($canAdd || $canEdt) {
                                                                            ?>
                                                                                <button type="button" class="btn btn-default btn-sm" style="margin-bottom: 5px;" id="addNewPayItmsBtn" onclick="insertNewPayItmFeedsRows('payPayItmsBalsFeedsTable', 0, '<?php echo $nwRowHtml2; ?>');">
                                                                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                    Add Item Feed
                                                                                </button>
                                                                                <button type="button" class="btn btn-default btn-sm hideNotice" style="margin-bottom: 5px;" id="addNewPayItmsAttchdValBtn" onclick="insertNewPayItmsAttchdValRows('payPayItmsPsblValsTable', 0, '<?php echo $nwRowHtml3; ?>');">
                                                                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                    Add Item Value
                                                                                </button>
                                                                                <button type="button" class="btn btn-default btn-sm" style="margin-bottom: 5px;" onclick="savePayPayItmsForm();">
                                                                                    <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                    SAVE
                                                                                </button>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="onePayPayItmsLnsTblSctn">
                                                        <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                                            <div id="payPayItmsBalsFeeds" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                                            <?php
                                                        }
                                                        if ($subPgNo == 0 || $subPgNo == 1 || $subPgNo == 101) {
                                                            ?>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <table class="table table-striped table-bordered table-responsive" id="payPayItmsBalsFeedsTable" cellspacing="0" width="100%" style="width:100%;min-width: 500px !important;">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>No.</th>
                                                                                    <th>Item Name</th>
                                                                                    <th>Action</th>
                                                                                    <th style="text-align: right;">Scale Factor</th>
                                                                                    <th>...</th>
                                                                                    <?php if ($canVwRcHstry) { ?>
                                                                                        <th>...</th>
                                                                                    <?php } ?>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php
                                                                                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 100;
                                                                                $total = get_Total_Feeds($pkID);
                                                                                //echo "::".$total."::".$pageNo1."::".$lmtSze;
                                                                                if ($pageNo1 > ceil($total / $lmtSze)) {
                                                                                    $pageNo1 = 1;
                                                                                } else if ($pageNo1 < 1) {
                                                                                    $pageNo1 = ceil($total / $lmtSze);
                                                                                }
                                                                                $curIdx = $pageNo1 - 1;
                                                                                $resultRw = getAllItmFeeds($curIdx, $lmtSze, $pkID);
                                                                                $cntr = 0;
                                                                                while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                                    $trsctnLineID = (float) $rowRw[3];
                                                                                    $payItmFdBalsItmID = (float) $rowRw[0];
                                                                                    $payItmFdFedByItmID = (float) $rowRw[1];
                                                                                    $payItmFdAction = $rowRw[2];
                                                                                    $payItmFdScaleFctr = (float) $rowRw[4];
                                                                                    $payItmFdBalsItmNm = $rowRw[5];
                                                                                    $payItmFdFedByItmNm = $rowRw[6];
                                                                                    $cntr += 1;
                                                                                    $payItmFdItmID = $payItmFdBalsItmID;
                                                                                    $payItmFdItmNm = $payItmFdBalsItmNm;
                                                                                    $payItmFdItmLOV = "Balance Items";
                                                                                    if ($payPayItmsMajTyp == "Balance Item") {
                                                                                        $payItmFdItmLOV = "Non-Balance Items";
                                                                                        $payItmFdItmID = $payItmFdFedByItmID;
                                                                                        $payItmFdItmNm = $payItmFdFedByItmNm;
                                                                                    }
                                                                                ?>
                                                                                    <tr id="payPayItmsBalsFeedsRow_<?php echo $cntr; ?>">
                                                                                        <td class="lovtd">
                                                                                            <span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span>
                                                                                        </td>
                                                                                        <td class="lovtd">
                                                                                            <input type="hidden" class="form-control" aria-label="..." id="payPayItmsBalsFeedsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                                                                            <input type="hidden" class="form-control" aria-label="..." id="payPayItmsBalsFeedsRow<?php echo $cntr; ?>_ItemID" value="<?php echo $payItmFdItmID; ?>" style="width:100% !important;">
                                                                                            <?php
                                                                                            if ($canEdt === true) {
                                                                                            ?>
                                                                                                <div class="input-group" style="width:100% !important;">
                                                                                                    <input type="text" class="form-control" aria-label="..." id="payPayItmsBalsFeedsRow<?php echo $cntr; ?>_ItemNm" name="payPayItmsBalsFeedsRow<?php echo $cntr; ?>_ItemNm" value="<?php echo $payItmFdItmNm; ?>" readonly="true" style="width:100% !important;">
                                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $payItmFdItmLOV; ?>', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payPayItmsBalsFeedsRow<?php echo $cntr; ?>_ItemID', 'payPayItmsBalsFeedsRow<?php echo $cntr; ?>_ItemNm', 'clear', 1, '', function () {});">
                                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                                    </label>
                                                                                                </div>
                                                                                            <?php } else { ?>
                                                                                                <span><?php echo $payItmFdItmNm; ?></span>
                                                                                            <?php } ?>
                                                                                        </td>
                                                                                        <td class="lovtd">
                                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="payPayItmsBalsFeedsRow<?php echo $cntr; ?>_Action" style="width:100% !important;">
                                                                                                <?php
                                                                                                $valslctdArry = array("", "");
                                                                                                $srchInsArrys = array("Adds", "Subtracts");
                                                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                                    if ($payItmFdAction == $srchInsArrys[$z]) {
                                                                                                        $valslctdArry[$z] = "selected";
                                                                                                    }
                                                                                                ?>
                                                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                                <?php } ?>
                                                                                            </select>
                                                                                        </td>
                                                                                        <td class="lovtd">
                                                                                            <input type="text" class="form-control rqrdFld jbDetDbt" aria-label="..." id="payPayItmsBalsFeedsRow<?php echo $cntr; ?>_ScaleFctr" name="payPayItmsBalsFeedsRow<?php echo $cntr; ?>_ScaleFctr" value="<?php
                                                                                                                                                                                                                                                                                                    echo number_format($payItmFdScaleFctr, 2);
                                                                                                                                                                                                                                                                                                    ?>" onkeypress="gnrlFldKeyPress(event, 'payPayItmsBalsFeedsRow<?php echo $cntr; ?>_ScaleFctr', 'payPayItmsBalsFeedsTable', 'jbDetDbt');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?>>
                                                                                        </td>
                                                                                        <td class="lovtd">
                                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delPayPayItmFeeds('payPayItmsBalsFeedsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Item Feed">
                                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                                            </button>
                                                                                        </td>
                                                                                        <?php
                                                                                        if ($canVwRcHstry === true) {
                                                                                        ?>
                                                                                            <td class="lovtd">
                                                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                                                        echo urlencode(encrypt1(($trsctnLineID . "|org.org_pay_itm_feeds|feed_id"),
                                                                                                                                                                                                                                                            $smplTokenWord1
                                                                                                                                                                                                                                                        ));
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
                                                            <?php
                                                        }
                                                        if ($subPgNo == 101) {
                                                            ?>
                                                                <script type="text/javascript">
                                                                    $("#payPayItmsDtPageNo1").val(<?php echo $pageNo1; ?>);
                                                                </script>
                                                            <?php
                                                        }
                                                        if ($subPgNo == 0 || $subPgNo == 1) {
                                                            ?>
                                                            </div>
                                                            <div id="payPayItmsPsblVals" class="tab-pane fadein" style="border:none !important;padding:0px !important;">
                                                            <?php
                                                        }
                                                        if ($subPgNo == 0 || $subPgNo == 1 || $subPgNo == 102) {
                                                            ?>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <table class="table table-striped table-bordered table-responsive" id="payPayItmsPsblValsTable" cellspacing="0" width="100%" style="width:100%;min-width: 500px !important;">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>No.</th>
                                                                                    <th>Item Possible Value Name</th>
                                                                                    <th>Item Possible Value Amount / SQL Formula</th>
                                                                                    <th>...</th>
                                                                                    <?php
                                                                                    if ($canVwRcHstry === true) {
                                                                                    ?>
                                                                                        <th style="">&nbsp;</th>
                                                                                    <?php } ?>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php
                                                                                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 100;
                                                                                $total = get_Total_Psbl_Vl($pkID);
                                                                                if ($pageNo2 > ceil($total / $lmtSze)) {
                                                                                    $pageNo2 = 1;
                                                                                } else if ($pageNo2 < 1) {
                                                                                    $pageNo2 = ceil($total / $lmtSze);
                                                                                }
                                                                                $curIdx = $pageNo2 - 1;
                                                                                $resultRw = getAllItmVals($curIdx, $lmtSze, $pkID);
                                                                                $cntr = 0;
                                                                                $mkReadOnly = "";
                                                                                while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                                    $trsctnLineItmID = (float) $rowRw[4];
                                                                                    $trsctnLineItmValID = (float) $rowRw[0];
                                                                                    $trsctnLineItmValNm = $rowRw[1];
                                                                                    $trsctnLineItmValAmnt = $rowRw[2];
                                                                                    $trsctnLineItmSQL = $rowRw[3];
                                                                                    $cntr += 1;
                                                                                ?>
                                                                                    <tr id="payPayItmsPsblValsRow_<?php echo $cntr; ?>">
                                                                                        <td class="lovtd">
                                                                                            <span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span>
                                                                                        </td>
                                                                                        <td class="lovtd" style="">
                                                                                            <input type="hidden" class="form-control" aria-label="..." id="payPayItmsPsblValsRow<?php echo $cntr; ?>_ItemID" value="<?php echo $trsctnLineItmID; ?>" style="width:100% !important;">
                                                                                            <input type="hidden" class="form-control" aria-label="..." id="payPayItmsPsblValsRow<?php echo $cntr; ?>_ItemValID" value="<?php echo $trsctnLineItmValID; ?>" style="width:100% !important;">
                                                                                            <input type="text" class="form-control  jbDetDesc" id="payPayItmsPsblValsRow<?php echo $cntr; ?>_ValNm" onkeypress="gnrlFldKeyPress(event, 'payPayItmsPsblValsRow<?php echo $cntr; ?>_ValNm', 'payPayItmsPsblValsTable', 'jbDetDesc');" value="<?php echo $trsctnLineItmValNm; ?>" <?php echo $mkReadOnly; ?> style="width:100% !important;" />
                                                                                        </td>
                                                                                        <td class="lovtd" style="text-align: right;">
                                                                                            <?php
                                                                                            if ($payPayItmsUsesSQL == "1") {
                                                                                            ?>
                                                                                                <div class="input-group" style="width:100%;">
                                                                                                    <textarea class="form-control rqrdFld" rows="5" cols="20" id="payPayItmsPsblValsRow<?php echo $cntr; ?>_ValSQL" name="payPayItmsPsblValsRow<?php echo $cntr; ?>_ValSQL" <?php echo $mkReadOnly; ?> style="text-align:left !important;"><?php echo $trsctnLineItmSQL; ?></textarea>
                                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('payPayItmsPsblValsRow<?php echo $cntr; ?>_ValSQL');" style="max-width:30px;width:30px;">
                                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                                    </label>
                                                                                                <?php } else { ?>
                                                                                                    <input type="text" class="form-control  jbDetDbt" id="payPayItmsPsblValsRow<?php echo $cntr; ?>_ValAmnt" onkeypress="gnrlFldKeyPress(event, 'payPayItmsPsblValsRow<?php echo $cntr; ?>_ValAmnt', 'payPayItmsPsblValsTable', 'jbDetDbt');" onblur="fmtAsNumber('payPayItmsPsblValsRow<?php echo $cntr; ?>_ValAmnt');" style="font-weight:bold;font-size:12px;color:blue;text-align: right;width:100% !important;" value="<?php echo number_format($trsctnLineItmValAmnt, 2); ?>" <?php echo $mkReadOnly; ?> />
                                                                                                <?php } ?>
                                                                                        </td>
                                                                                        <td class="lovtd">
                                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delPayPayItmsAttchdVal('payPayItmsPsblValsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Attached Value">
                                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                                            </button>
                                                                                        </td>
                                                                                        <?php
                                                                                        if ($canVwRcHstry === true) {
                                                                                        ?>
                                                                                            <td class="lovtd">
                                                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                                                        echo urlencode(encrypt1(($trsctnLineItmValID . "|org.org_pay_items_values|pssbl_value_id"),
                                                                                                                                                                                                                                                            $smplTokenWord1
                                                                                                                                                                                                                                                        ));
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
                                                            <?php
                                                        }
                                                        if ($subPgNo == 102) {
                                                            ?>
                                                                <script type="text/javascript">
                                                                    $("#payPayItmsDtPageNo2").val(<?php echo $pageNo2; ?>);
                                                                </script>
                                                            <?php
                                                        }
                                                        if ($subPgNo == 0 || $subPgNo == 1) {
                                                            ?>
                                                            </div>
                                                            <div id="payPayItmsExtraInfo" class="tab-pane fadein" style="border:none !important;padding:0px !important;">
                                                            <?php
                                                        }
                                                        if ($subPgNo == 0 || $subPgNo == 1 || $subPgNo == 103) {
                                                            ?>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <fieldset class="basic_person_fs2" style="min-height:50px !important;padding: 5px 2px 5px 2px !important;margin-left:3px !important;">
                                                                            <table class="table table-striped table-bordered table-responsive" id="payPayItmsExtrInfTable" cellspacing="0" width="100%" style="width:100%;min-width: 200px !important;">
                                                                                <caption class="basic_person_lg" style="color:black !important;font-weight:bold;">EXTRA INFORMATION VALUES</caption>
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th style="max-width:30px;width:30px;">No.</th>
                                                                                        <th style="">Extra Info Category</th>
                                                                                        <th>Extra Info Label</th>
                                                                                        <th style="min-width:300px;">Value</th><?php
                                                                                                                                if ($canVwRcHstry === true) {
                                                                                                                                ?>
                                                                                            <th style="max-width:30px;width:30px;">...</th>
                                                                                        <?php } ?>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    $cntr = 0;
                                                                                    $vwSQLStmnt = "";
                                                                                    $resultRw = getAllwdExtInfosNVals(
                                                                                        "%",
                                                                                        "Extra Info Label",
                                                                                        0,
                                                                                        10000000,
                                                                                        $vwSQLStmnt,
                                                                                        getMdlGrpID("Pay Items", $mdlNm),
                                                                                        $pkID,
                                                                                        "pay.pay_all_other_info_table"
                                                                                    );
                                                                                    while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                                        $extrInfoCtgry = $rowRw[0];
                                                                                        $extrInfoLbl = $rowRw[1];
                                                                                        $extrInfoVal = $rowRw[2];
                                                                                        $cmbntnID = (float) $rowRw[3];
                                                                                        $tableID = (float) $rowRw[4];
                                                                                        $dfltRowID = (float) $rowRw[5];
                                                                                        $cntr += 1;
                                                                                    ?>
                                                                                        <tr id="payPayItmsExtrInfRow_<?php echo $cntr; ?>">
                                                                                            <td class="lovtd"><span><?php echo ($cntr); ?></span></td>
                                                                                            <td class="lovtd" style="">
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="payPayItmsExtrInfRow<?php echo $cntr; ?>_DfltRowID" value="<?php echo $dfltRowID; ?>" style="width:100% !important;">
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="payPayItmsExtrInfRow<?php echo $cntr; ?>_CombntnID" value="<?php echo $cmbntnID; ?>" style="width:100% !important;">
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="payPayItmsExtrInfRow<?php echo $cntr; ?>_TableID" value="<?php echo $tableID; ?>" style="width:100% !important;">
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="payPayItmsExtrInfRow<?php echo $cntr; ?>_extrInfoCtgry" value="<?php echo $extrInfoCtgry; ?>" style="width:100% !important;">
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="payPayItmsExtrInfRow<?php echo $cntr; ?>_extrInfoLbl" value="<?php echo $extrInfoLbl; ?>" style="width:100% !important;">
                                                                                                <span><?php echo $extrInfoCtgry; ?></span>
                                                                                            </td>
                                                                                            <td class="lovtd" style="">
                                                                                                <span><?php echo $extrInfoCtgry; ?></span>
                                                                                            </td>
                                                                                            <td class="lovtd" style="">
                                                                                                <input type="text" class="form-control jbDetRfDc" aria-label="..." id="payPayItmsExtrInfRow<?php echo $cntr; ?>_Value" name="payPayItmsExtrInfRow<?php echo $cntr; ?>_Value" value="<?php echo $extrInfoVal; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'payPayItmsExtrInfRow<?php echo $cntr; ?>_Value', 'payPayItmsExtrInfTable', 'jbDetRfDc');">
                                                                                            </td>
                                                                                            <?php
                                                                                            if ($canVwRcHstry === true) {
                                                                                            ?>
                                                                                                <td class="lovtd">
                                                                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                                                            echo urlencode(encrypt1(($dfltRowID . "|pay.pay_all_other_info_table|dflt_row_id"),
                                                                                                                                                                                                                                                                $smplTokenWord1
                                                                                                                                                                                                                                                            ));
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
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                            <?php
                                                        }
                                                        if ($subPgNo == 0 || $subPgNo == 1) {
                                                            ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                                        }
                                                    }
                                                    if ($subPgNo == 0) {
                                        ?>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </form>
<?php
                                                    }
                                                } else if ($vwtyp == 3) {
                                                    //Test SQL
                                                    header("content-type:application/json");
                                                    $payPayItmsSQL = isset($_POST['payPayItmsSQL']) ? cleanInputData($_POST['payPayItmsSQL']) : "";
                                                    $payPayItmsUnitPrc = isset($_POST['payPayItmsUnitPrc']) ? (float) cleanInputData($_POST['payPayItmsUnitPrc']) : 0.00;
                                                    $payPayItmsQty = isset($_POST['payPayItmsQty']) ? (float) cleanInputData($_POST['payPayItmsQty']) : 0.00;
                                                    $errMsg = "";
                                                    $CalcItemValue = 0.00;
                                                    $boolRes = isTxCdeSQLValid($payPayItmsSQL, $payPayItmsUnitPrc, $payPayItmsQty, $CalcItemValue);
                                                    $arr_content['CalcItemValue'] = $CalcItemValue;
                                                    if (!$boolRes) {
                                                        $errMsg .= "SQL is NOT valid!";
                                                        $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-close\" aria-hidden=\"true\"></i>ERROR:" . $errMsg . "</span>";
                                                    } else {
                                                        $errMsg = "SUCCESS";
                                                        $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i>" . $errMsg . ": " . number_format(
                                                            $CalcItemValue,
                                                            5
                                                        ) . "</span>";
                                                    }
                                                    echo json_encode($arr_content);
                                                    exit();
                                                } else if ($vwtyp == 4) {
                                                }
                                            }
                                        }
                                    }
