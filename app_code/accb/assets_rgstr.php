<?php
$canAdd = test_prmssns($dfltPrvldgs[14], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[15], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[16], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
$defaultBrkdwnLOV = "Transaction Amount Breakdown Parameters";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Asset/Investment */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteAssetHdrNDet($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Asset/Investment Measurement */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteAssetPMStp($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 3) {
                /* Delete Asset/Investment Transaction */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteAssetTrans($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 4) {
                /* Delete Asset/Investment PM Record */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteAssetPMRecs($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Assets/Investment
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $accbAstHdrID = isset($_POST['accbAstHdrID']) ? (float) cleanInputData($_POST['accbAstHdrID']) : -1;
                $accbAstHdrName = isset($_POST['accbAstHdrName']) ? cleanInputData($_POST['accbAstHdrName']) : "";
                $accbAstHdrDesc = isset($_POST['accbAstHdrDesc']) ? cleanInputData($_POST['accbAstHdrDesc']) : "";
                $accbAstHdrInvItemID = isset($_POST['accbAstHdrInvItemID']) ? (float) cleanInputData($_POST['accbAstHdrInvItemID']) : -1;
                $accbAstHdrClsfctn = isset($_POST['accbAstHdrClsfctn']) ? cleanInputData($_POST['accbAstHdrClsfctn']) : "";
                $accbAstHdrCtgry = isset($_POST['accbAstHdrCtgry']) ? cleanInputData($_POST['accbAstHdrCtgry']) : "";
                $accbAstHdrDivGrpID = isset($_POST['accbAstHdrDivGrpID']) ? (int) cleanInputData($_POST['accbAstHdrDivGrpID']) : -1;

                $accbAstHdrSiteID = isset($_POST['accbAstHdrSiteID']) ? (int) cleanInputData($_POST['accbAstHdrSiteID']) : -1;
                $accbAstHdrBuildLoc = isset($_POST['accbAstHdrBuildLoc']) ? cleanInputData($_POST['accbAstHdrBuildLoc']) : "";
                $accbAstHdrRoomNum = isset($_POST['accbAstHdrRoomNum']) ? cleanInputData($_POST['accbAstHdrRoomNum']) : "";
                $accbAstHdrPrsnID = isset($_POST['accbAstHdrPrsnID']) ? (float) cleanInputData($_POST['accbAstHdrPrsnID']) : -1;

                $accbAstHdrTagNum = isset($_POST['accbAstHdrTagNum']) ? cleanInputData($_POST['accbAstHdrTagNum']) : "";
                $accbAstHdrSerialNum = isset($_POST['accbAstHdrSerialNum']) ? cleanInputData($_POST['accbAstHdrSerialNum']) : "";
                $accbAstHdrBarCode = isset($_POST['accbAstHdrBarCode']) ? cleanInputData($_POST['accbAstHdrBarCode']) : "";
                $accbAstHdrStrtDte = isset($_POST['accbAstHdrStrtDte']) ? cleanInputData($_POST['accbAstHdrStrtDte']) : "";
                $accbAstHdrEndDte = isset($_POST['accbAstHdrEndDte']) ? cleanInputData($_POST['accbAstHdrEndDte']) : "";

                $accbAstHdrAstAcntID = isset($_POST['accbAstHdrAstAcntID']) ? (int) cleanInputData($_POST['accbAstHdrAstAcntID']) : -1;
                $accbAstHdrDprcAcntID = isset($_POST['accbAstHdrDprcAcntID']) ? (int) cleanInputData($_POST['accbAstHdrDprcAcntID']) : -1;
                $accbAstHdrExpnsAcntID = isset($_POST['accbAstHdrExpnsAcntID']) ? (int) cleanInputData($_POST['accbAstHdrExpnsAcntID']) : -1;
                $accbAstHdrRvnuAcntID = isset($_POST['accbAstHdrRvnuAcntID']) ? (int) cleanInputData($_POST['accbAstHdrRvnuAcntID']) : -1;
                $accbAstHdrMntncsAcntID = isset($_POST['accbAstHdrMntncsAcntID']) ? (int) cleanInputData($_POST['accbAstHdrMntncsAcntID']) : -1;
                $accbAstHdrSlvgValue = isset($_POST['accbAstHdrSlvgValue']) ? (float) cleanInputData($_POST['accbAstHdrSlvgValue']) : 0.00;

                $accbAstHdrSQLFrmlr = isset($_POST['accbAstHdrSQLFrmlr']) ? cleanInputData($_POST['accbAstHdrSQLFrmlr']) : "";
                $accbAstHdrAutoDprctn = isset($_POST['accbAstHdrAutoDprctn']) ? (cleanInputData($_POST['accbAstHdrAutoDprctn']) == "YES" ? TRUE : FALSE)
                    : FALSE;
                $slctdExtraInfoLines = isset($_POST['slctdExtraInfoLines']) ? cleanInputData($_POST['slctdExtraInfoLines']) : "";
                $slctdMeasurmntTyps = isset($_POST['slctdMeasurmntTyps']) ? cleanInputData($_POST['slctdMeasurmntTyps']) : "";

                $exitErrMsg = "";
                if ($accbAstHdrName == "" || $accbAstHdrDesc == "") {
                    $exitErrMsg .= "Please enter Asset/Investment Name and Description!<br/>";
                }
                if ($accbAstHdrClsfctn == "" || $accbAstHdrCtgry == "") {
                    $exitErrMsg .= "Please enter Asset/Investment Classification and Category!<br/>";
                }
                if ($accbAstHdrDivGrpID <= 0 || $accbAstHdrSiteID <= 0) {
                    $exitErrMsg .= "Please enter Division and Site/Location!<br/>";
                }
                if ($accbAstHdrTagNum == "" && $accbAstHdrSerialNum == "" && $accbAstHdrBarCode == "") {
                    $exitErrMsg .= "Please enter either Tag Number, Serial Number or Barcode!<br/>";
                }
                if ($accbAstHdrStrtDte == "" || $accbAstHdrEndDte == "") {
                    $exitErrMsg .= "Please enter Asset/Investment Start Date and End Date!<br/>";
                }
                if ($accbAstHdrSQLFrmlr == "") {
                    $exitErrMsg .= "SQL Formula cannot be Empty!<br/>";
                }
                if (
                    $accbAstHdrAstAcntID <= 0 || $accbAstHdrDprcAcntID <= 0 || $accbAstHdrExpnsAcntID <= 0
                    || $accbAstHdrRvnuAcntID <= 0 || $accbAstHdrMntncsAcntID <= 0
                ) {
                    $exitErrMsg .= "Asset GL Accounts must all be provided!<br/>";
                }
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['accbAstHdrID'] = $accbAstHdrID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $oldID = (float) getGnrlRecID("accb.accb_fa_assets_rgstr", "asset_code_name", "asset_id", $accbAstHdrName, $orgID);
                if (($oldID <= 0 || $oldID == $accbAstHdrID)) {
                    if ($accbAstHdrID <= 0) {
                        createAssetHdr(
                            $orgID,
                            $accbAstHdrStrtDte,
                            $accbAstHdrEndDte,
                            $accbAstHdrName,
                            $accbAstHdrClsfctn,
                            $accbAstHdrDesc,
                            $accbAstHdrCtgry,
                            $accbAstHdrDivGrpID,
                            $accbAstHdrSiteID,
                            $accbAstHdrBuildLoc,
                            $accbAstHdrRoomNum,
                            $accbAstHdrPrsnID,
                            $accbAstHdrTagNum,
                            $accbAstHdrSerialNum,
                            $accbAstHdrBarCode,
                            $accbAstHdrAstAcntID,
                            $accbAstHdrDprcAcntID,
                            $accbAstHdrExpnsAcntID,
                            $accbAstHdrInvItemID,
                            $accbAstHdrSQLFrmlr,
                            $accbAstHdrSlvgValue,
                            $accbAstHdrAutoDprctn,
                            $accbAstHdrRvnuAcntID,
                            $accbAstHdrMntncsAcntID
                        );
                        $accbAstHdrID = (float) getGnrlRecID("accb.accb_fa_assets_rgstr", "asset_code_name", "asset_id", $accbAstHdrName, $orgID);
                    } else {
                        updtAssetHdr(
                            $accbAstHdrID,
                            $accbAstHdrStrtDte,
                            $accbAstHdrEndDte,
                            $accbAstHdrName,
                            $accbAstHdrClsfctn,
                            $accbAstHdrDesc,
                            $accbAstHdrCtgry,
                            $accbAstHdrDivGrpID,
                            $accbAstHdrSiteID,
                            $accbAstHdrBuildLoc,
                            $accbAstHdrRoomNum,
                            $accbAstHdrPrsnID,
                            $accbAstHdrTagNum,
                            $accbAstHdrSerialNum,
                            $accbAstHdrBarCode,
                            $accbAstHdrAstAcntID,
                            $accbAstHdrDprcAcntID,
                            $accbAstHdrExpnsAcntID,
                            $accbAstHdrInvItemID,
                            $accbAstHdrSQLFrmlr,
                            $accbAstHdrSlvgValue,
                            $accbAstHdrAutoDprctn,
                            $accbAstHdrRvnuAcntID,
                            $accbAstHdrMntncsAcntID
                        );
                    }
                    $afftctd = 0;
                    $afftctd1 = 0;
                    $afftctd2 = 0;
                    $errMsg = "";
                    if (trim($slctdMeasurmntTyps, "|~") != "" && $accbAstHdrID > 0) {
                        $variousRows = explode("|", trim($slctdMeasurmntTyps, "|"));
                        for ($y = 0; $y < count($variousRows); $y++) {
                            $crntRow = explode("~", $variousRows[$y]);
                            if (count($crntRow) == 5) {
                                $ln_LineID = (float) (cleanInputData1($crntRow[0]));
                                $ln_MsrmntNm = cleanInputData1($crntRow[1]);
                                $ln_UOM = cleanInputData1($crntRow[2]);
                                $ln_MxFigure = (float) cleanInputData1($crntRow[3]);
                                $ln_PMFigure = (float) cleanInputData1($crntRow[4]);
                                if ($ln_MsrmntNm != "" && $ln_UOM != "") {
                                    if ($ln_LineID <= 0) {
                                        $ln_LineID = getNewAssetPMStpID();
                                        $afftctd += createPMStp($ln_LineID, $ln_MsrmntNm, $ln_UOM, $ln_MxFigure, $ln_PMFigure, $accbAstHdrID);
                                    } else {
                                        $afftctd += updatePMStp($ln_LineID, $ln_MsrmntNm, $ln_UOM, $ln_MxFigure, $ln_PMFigure, $accbAstHdrID);
                                    }
                                }
                            }
                        }
                    }
                    if (trim($slctdExtraInfoLines, "|~") != "" && $accbAstHdrID > 0) {
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
                                        "accb.accb_all_other_info_table",
                                        $ln_CombntnID,
                                        $accbAstHdrID,
                                        $ln_Value,
                                        $ln_extrInfoLbl,
                                        $ln_extrInfoCtgry,
                                        $ln_DfltRowID
                                    );
                                } else {
                                    if (doesRowHvOthrInfo("accb.accb_all_other_info_table", $ln_CombntnID, $accbAstHdrID) > 0) {
                                        $afftctd1 += updateRowOthrInfVal(
                                            "accb.accb_all_other_info_table",
                                            $ln_CombntnID,
                                            $accbAstHdrID,
                                            $ln_Value,
                                            $ln_extrInfoLbl,
                                            $ln_extrInfoCtgry,
                                            $ln_DfltRowID
                                        );
                                    } else {
                                        $ln_DfltRowID = getNewExtInfoID("accb.accb_all_other_info_table_dflt_row_id_seq");
                                        $afftctd1 += createRowOthrInfVal(
                                            "accb.accb_all_other_info_table",
                                            $ln_CombntnID,
                                            $accbAstHdrID,
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
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Asset/Investment Successfully Saved!"
                            . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    } else {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Asset/Investment Successfully Saved!";
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['accbAstHdrID'] = $accbAstHdrID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Either the New Asset/Investment Name is in Use <br/>or Data Supplied is Incomplete!</span>";
                    $arr_content['percent'] = 100;
                    $arr_content['accbAstHdrID'] = $accbAstHdrID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 2) {
                header("content-type:application/json");
                $sbmtdAssetID = isset($_POST['sbmtdAssetID']) ? (float) cleanInputData($_POST['sbmtdAssetID']) : -1;
                $sbmtdAssetTransID = isset($_POST['sbmtdAssetTransID']) ? (float) cleanInputData($_POST['sbmtdAssetTransID']) : -1;
                $astTrnsType = isset($_POST['astTrnsType']) ? cleanInputData($_POST['astTrnsType']) : "";
                $astTrnsDate = isset($_POST['astTrnsDate']) ? cleanInputData($_POST['astTrnsDate']) : "";
                $astTrnsDesc = isset($_POST['astTrnsDesc']) ? cleanInputData($_POST['astTrnsDesc']) : "";
                $astTrnsCurNm = isset($_POST['astTrnsCurNm']) ? cleanInputData($_POST['astTrnsCurNm']) : "";
                $astTrnsCurID = getPssblValID($astTrnsCurNm, getLovID("Currencies"));
                $astTrnsAmount = isset($_POST['astTrnsAmount']) ? (float) cleanInputData($_POST['astTrnsAmount']) : 0;

                $astTrnsIncrsDcrs1 = isset($_POST['astTrnsIncrsDcrs1']) ? cleanInputData($_POST['astTrnsIncrsDcrs1']) : "";
                $astTrnsAccountID1 = isset($_POST['astTrnsAccountID1']) ? (int) cleanInputData($_POST['astTrnsAccountID1']) : -1;
                $astTrnsIncrsDcrs2 = isset($_POST['astTrnsIncrsDcrs2']) ? cleanInputData($_POST['astTrnsIncrsDcrs2']) : "";
                $astTrnsAccountID2 = isset($_POST['astTrnsAccountID2']) ? cleanInputData($_POST['astTrnsAccountID2']) : -1;
                $astTrnsFuncCurRate = isset($_POST['astTrnsFuncCurRate']) ? (float) cleanInputData($_POST['astTrnsFuncCurRate']) : 1;
                $shdSbmt = isset($_POST['shdSbmt']) ? (int) cleanInputData($_POST['shdSbmt']) : 0;

                $exitErrMsg = "";
                if ($astTrnsDate == "" || $astTrnsDesc == "") {
                    $exitErrMsg .= "Please enter Date and Description!<br/>";
                }
                if ($astTrnsCurNm == "" || $astTrnsCurID <= 0 || $astTrnsAmount == 0) {
                    $exitErrMsg .= "Please enter Currency and Amount!<br/>";
                }
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['sbmtdAssetTransID'] = $sbmtdAssetTransID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                if ($astTrnsFuncCurRate == 1 || $astTrnsFuncCurRate == 0) {
                    $astTrnsFuncCurRate = round(get_LtstExchRate($astTrnsCurID, $fnccurid, $astTrnsDate), 4);
                }
                $funcCurrAmnt = $astTrnsAmount * $astTrnsFuncCurRate;
                if ($sbmtdAssetTransID <= 0) {
                    $sbmtdAssetTransID = getNewAssetLnID();
                    createAssetTrns(
                        $sbmtdAssetTransID,
                        $sbmtdAssetID,
                        $astTrnsType,
                        $astTrnsDesc,
                        $astTrnsAmount,
                        $astTrnsCurID,
                        $astTrnsIncrsDcrs1,
                        $astTrnsAccountID1,
                        $astTrnsIncrsDcrs2,
                        $astTrnsAccountID2,
                        $fnccurid,
                        $astTrnsFuncCurRate,
                        $funcCurrAmnt,
                        $astTrnsDate
                    );
                } else {
                    updtAssetTrns(
                        $sbmtdAssetTransID,
                        $sbmtdAssetID,
                        $astTrnsType,
                        $astTrnsDesc,
                        $astTrnsAmount,
                        $astTrnsCurID,
                        $astTrnsIncrsDcrs1,
                        $astTrnsAccountID1,
                        $astTrnsIncrsDcrs2,
                        $astTrnsAccountID2,
                        $fnccurid,
                        $astTrnsFuncCurRate,
                        $funcCurrAmnt,
                        $astTrnsDate
                    );
                }
                if ($shdSbmt == 5) {
                    $exitErrMsg = createAssetTrnsAcntng($sbmtdAssetTransID, $orgID, $usrID);
                }
                if ($exitErrMsg != "" && $shdSbmt != 5) {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Transaction Successfully Saved!"
                        . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                } else if ($exitErrMsg != "" && $shdSbmt == 5) {
                    if (strpos($exitErrMsg, "SUCCESS") !== FALSE) {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Transaction Successfully Saved!"
                            . "<br/><span style=\"color:green;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    } else {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Transaction Successfully Saved!"
                            . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    }
                } else {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Transaction Successfully Saved!";
                }
                $arr_content['percent'] = 100;
                $arr_content['sbmtdAssetTransID'] = $sbmtdAssetTransID;
                $arr_content['message'] = $exitErrMsg;
                echo json_encode($arr_content);
                exit();
            } else if ($actyp == 3) {
                //Save PM Records
                header("content-type:application/json");
                $sbmtdAssetID = isset($_POST['sbmtdAssetID']) ? (float) cleanInputData($_POST['sbmtdAssetID']) : -1;
                $sbmtdAssetPmRecID = isset($_POST['sbmtdAssetPmRecID']) ? (float) cleanInputData($_POST['sbmtdAssetPmRecID']) : -1;
                $astPmRecType = isset($_POST['astPmRecType']) ? cleanInputData($_POST['astPmRecType']) : "";
                $astPmRecDate = isset($_POST['astPmRecDate']) ? cleanInputData($_POST['astPmRecDate']) : "";
                $astPmRecUOMNm = isset($_POST['astPmRecUOMNm']) ? cleanInputData($_POST['astPmRecUOMNm']) : "";
                //$astTrnsCurID = getPssblValID($astTrnsCurNm, getLovID("Currencies"));
                $astPmRecStrtFig = isset($_POST['astPmRecStrtFig']) ? (float) cleanInputData($_POST['astPmRecStrtFig']) : 0;
                $astPmRecEndFig = isset($_POST['astPmRecEndFig']) ? (float) cleanInputData($_POST['astPmRecEndFig']) : 0;

                $astPmRecPMAction = isset($_POST['astPmRecPMAction']) ? cleanInputData($_POST['astPmRecPMAction']) : "";
                $astPmRecPMDesc = isset($_POST['astPmRecPMDesc']) ? cleanInputData($_POST['astPmRecPMDesc']) : "";
                $astPmRecIsPMDone = isset($_POST['astPmRecIsPMDone']) ? cleanInputData($_POST['astPmRecIsPMDone']) : "NO";
                $astPmRecIsPMDoneBool = ($astPmRecIsPMDone == "YES") ? TRUE : FALSE;
                $slctdExtraInfoLines = isset($_POST['slctdExtraInfoLines']) ? cleanInputData($_POST['slctdExtraInfoLines']) : "";
                $exitErrMsg = "";
                if ($astPmRecDate == "" || $astPmRecType == "") {
                    $exitErrMsg .= "Please enter Date and PM Type!<br/>";
                }
                if ($astPmRecPMDesc == "") {
                    $exitErrMsg .= "Please enter PM Description!<br/>";
                }
                if ($astPmRecUOMNm == "" || $astPmRecStrtFig == $astPmRecEndFig) {
                    $exitErrMsg .= "Please enter UOM and Starting/Ending Figures!<br/>";
                }
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['sbmtdAssetPmRecID'] = $sbmtdAssetPmRecID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                if ($sbmtdAssetPmRecID <= 0) {
                    $sbmtdAssetPmRecID = getNewAssetPMID();
                    createPM(
                        $sbmtdAssetPmRecID,
                        $astPmRecType,
                        $astPmRecUOMNm,
                        $astPmRecDate,
                        $astPmRecStrtFig,
                        $astPmRecEndFig,
                        $astPmRecIsPMDoneBool,
                        $astPmRecPMAction,
                        $astPmRecPMDesc,
                        $sbmtdAssetID
                    );
                } else {
                    updatePM(
                        $sbmtdAssetPmRecID,
                        $astPmRecType,
                        $astPmRecUOMNm,
                        $astPmRecDate,
                        $astPmRecStrtFig,
                        $astPmRecEndFig,
                        $astPmRecIsPMDoneBool,
                        $astPmRecPMAction,
                        $astPmRecPMDesc,
                        $sbmtdAssetID
                    );
                }
                $afftctd1 = 0;
                if (trim($slctdExtraInfoLines, "|~") != "" && $sbmtdAssetPmRecID > 0) {
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
                                    "accb.accb_all_other_info_table",
                                    $ln_CombntnID,
                                    $sbmtdAssetPmRecID,
                                    $ln_Value,
                                    $ln_extrInfoLbl,
                                    $ln_extrInfoCtgry,
                                    $ln_DfltRowID
                                );
                            } else {
                                if (doesRowHvOthrInfo("accb.accb_all_other_info_table", $ln_CombntnID, $sbmtdAssetPmRecID) > 0) {
                                    $afftctd1 += updateRowOthrInfVal(
                                        "accb.accb_all_other_info_table",
                                        $ln_CombntnID,
                                        $sbmtdAssetPmRecID,
                                        $ln_Value,
                                        $ln_extrInfoLbl,
                                        $ln_extrInfoCtgry,
                                        $ln_DfltRowID
                                    );
                                } else {
                                    $ln_DfltRowID = getNewExtInfoID("accb.accb_all_other_info_table_dflt_row_id_seq");
                                    $afftctd1 += createRowOthrInfVal(
                                        "accb.accb_all_other_info_table",
                                        $ln_CombntnID,
                                        $sbmtdAssetPmRecID,
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
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>PM Record Successfully Saved!"
                        . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                } else {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>PM Record Successfully Saved!";
                }
                $arr_content['percent'] = 100;
                $arr_content['sbmtdAssetPmRecID'] = $sbmtdAssetPmRecID;
                $arr_content['message'] = $exitErrMsg;
                echo json_encode($arr_content);
                exit();
            } else if ($actyp == 901) {
                //Import Assets Register
                $dataToSend = trim(cleanInputData($_POST['dataToSend']), "|~");
                session_write_close();
                $affctd = 0;
                if ($dataToSend != "") {
                    $variousRows = explode("|", $dataToSend);
                    $total = count($variousRows);
                    for ($z = 0; $z < $total; $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 33) {
                            $accbAstHdrID = -1;
                            $accbAstHdrName = ltrim(trim(cleanInputData1($crntRow[0])), '\'');
                            $accbAstHdrDesc = trim(cleanInputData1($crntRow[1]));
                            $accbAstHdrClsfctn = trim(cleanInputData1($crntRow[2]));
                            $accbAstHdrCtgry = trim(cleanInputData1($crntRow[3]));
                            $accbAstHdrTagNum = ltrim(trim(cleanInputData1($crntRow[4])), '\'');
                            $accbAstHdrSerialNum = ltrim(trim(cleanInputData1($crntRow[5])), '\'');
                            $accbAstHdrBarCode = ltrim(trim(cleanInputData1($crntRow[6])), '\'');
                            $accbAstHdrDivGrp = trim(cleanInputData1($crntRow[7]));

                            $accbAstHdrSite = trim(cleanInputData1($crntRow[8]));
                            $accbAstHdrBuildLoc = trim(cleanInputData1($crntRow[9]));
                            $accbAstHdrRoomNum = trim(cleanInputData1($crntRow[10]));
                            $accbAstHdrPrsnLocID = trim(cleanInputData1($crntRow[11]));

                            $accbAstHdrInvItem = ltrim(trim(cleanInputData1($crntRow[12])), '\'');

                            $accbAstHdrAstAcnt = ltrim(trim(cleanInputData1($crntRow[13])), '\'');
                            $accbAstHdrDprcAcnt = ltrim(trim(cleanInputData1($crntRow[14])), '\'');
                            $accbAstHdrExpnsAcnt = ltrim(trim(cleanInputData1($crntRow[15])), '\'');
                            $accbAstHdrRvnuAcnt = ltrim(trim(cleanInputData1($crntRow[16])), '\'');
                            $accbAstHdrMntncsAcnt = ltrim(trim(cleanInputData1($crntRow[17])), '\'');

                            $accbAstHdrStrtDte = ltrim(trim(cleanInputData1($crntRow[18])), '\'');
                            $accbAstHdrEndDte = ltrim(trim(cleanInputData1($crntRow[19])), '\'');

                            $accbAstHdrSlvgValue = trim(cleanInputData1($crntRow[20]));
                            $accbAstHdrAutoDprctn = strtoupper(trim(cleanInputData1($crntRow[21]))) === "YES" ? TRUE : FALSE;

                            $accbAstHdrSQLFrmlr = trim(cleanInputData1($crntRow[22]));

                            //$slctdExtraInfoLines = trim(cleanInputData1($crntRow[0]));
                            //$slctdMeasurmntTyps = trim(cleanInputData1($crntRow[0]));
                            $sbmtdAssetTransID = -1;
                            $astTrnsType = trim(cleanInputData1($crntRow[23]));
                            $astTrnsDesc = trim(cleanInputData1($crntRow[24]));
                            $astTrnsAmount = trim(cleanInputData1($crntRow[25]));
                            $astTrnsCurNm = trim(cleanInputData1($crntRow[26]));

                            $astTrnsIncrsDcrs1 = trim(cleanInputData1($crntRow[27]));
                            $astTrnsAccount1 = ltrim(trim(cleanInputData1($crntRow[28])), '\'');
                            $astTrnsIncrsDcrs2 = trim(cleanInputData1($crntRow[29]));
                            $astTrnsAccount2 =  ltrim(trim(cleanInputData1($crntRow[30])), '\'');
                            $astTrnsDate = ltrim(trim(cleanInputData1($crntRow[31])), '\'');
                            $astTrnsFuncCurRate = trim(cleanInputData1($crntRow[32]));

                            $astTrnsAccountID2 = -1;
                            $astTrnsAccountID1 = -1;
                            $astTrnsCurID = -1;
                            $accbAstHdrMntncsAcntID = -1;
                            $accbAstHdrRvnuAcntID = -1;
                            $accbAstHdrExpnsAcntID = -1;
                            $accbAstHdrDprcAcntID = -1;
                            $accbAstHdrAstAcntID = -1;
                            $accbAstHdrInvItemID = -1;
                            $accbAstHdrPrsnID = -1;
                            $accbAstHdrSiteID = -1;
                            $accbAstHdrDivGrpID = -1;

                            if ($z == 0) {
                                if (
                                    strtoupper($accbAstHdrName) == strtoupper("Asset Number/Code**")
                                    && strtoupper($accbAstHdrMntncsAcnt) == strtoupper("Maintenance Account No.**")
                                    && strtoupper($astTrnsFuncCurRate) == strtoupper("Exchange Rate**")
                                ) {
                                    continue;
                                } else {
                                    $arr_content['percent'] = 100;
                                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> Selected File is Invalid!";
                                    //.strtoupper($number) ."|". strtoupper($processName) ."|". strtoupper($isEnbld1 == "IS ENABLED?");
                                    $arr_content['msgcount'] = $total;
                                    file_put_contents(
                                        $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AsstsRgstrimport_progress.rho",
                                        json_encode($arr_content)
                                    );
                                    break;
                                }
                            } else {
                                $astTrnsAccountID2 =  getAccbAccntId($astTrnsAccount2, $orgID);
                                $astTrnsAccountID1 = getAccbAccntId($astTrnsAccount1, $orgID);
                                if ($astTrnsCurNm == "") {
                                    $astTrnsCurNm = $fnccurnm;
                                }
                                $astTrnsCurID = getPssblValID($astTrnsCurNm, getLovID("Currencies"));
                                $accbAstHdrMntncsAcntID = getAccbAccntId($accbAstHdrMntncsAcnt, $orgID);
                                $accbAstHdrRvnuAcntID = getAccbAccntId($accbAstHdrRvnuAcnt, $orgID);
                                $accbAstHdrExpnsAcntID = getAccbAccntId($accbAstHdrExpnsAcnt, $orgID);
                                $accbAstHdrDprcAcntID = getAccbAccntId($accbAstHdrDprcAcnt, $orgID);
                                $accbAstHdrAstAcntID = getAccbAccntId($accbAstHdrAstAcnt, $orgID);
                                $accbAstHdrInvItemID = getAccbINVItmID($accbAstHdrInvItem, $orgID);
                                $accbAstHdrPrsnID = getPersonID($accbAstHdrPrsnLocID, $orgID);
                                $accbAstHdrSiteID = getAccbSiteID($accbAstHdrSite, $orgID);
                                $accbAstHdrDivGrpID = getAccbDivGrpID($accbAstHdrDivGrp, $orgID);

                                $astTrnsAmount = (float) $astTrnsAmount;
                                $accbAstHdrSlvgValue = (float) $accbAstHdrSlvgValue;
                                $astTrnsFuncCurRate = (float) $astTrnsFuncCurRate;
                                if ($astTrnsFuncCurRate == 0) {
                                    $astTrnsFuncCurRate = 1;
                                }
                            }
                            $exitErrMsg = "";
                            if ($accbAstHdrName == "" || $accbAstHdrDesc == "") {
                                $exitErrMsg .= "Please enter Asset/Investment Name and Description!<br/>";
                            }
                            if ($accbAstHdrClsfctn == "" || $accbAstHdrCtgry == "") {
                                $exitErrMsg .= "Please enter Asset/Investment Classification and Category!<br/>";
                            }
                            if ($accbAstHdrStrtDte == "") {
                                $accbAstHdrStrtDte = $gnrlTrnsDteDMYHMS;
                            }
                            if ($accbAstHdrEndDte == "") {
                                $accbAstHdrEndDte = $rhoFutureDateDMYHMS;
                            }
                            if ($accbAstHdrSQLFrmlr == "") {
                                $accbAstHdrSQLFrmlr = "Select 0.00";
                            }
                            if (trim($exitErrMsg) !== "") {
                                $arr_content['percent'] = 100;
                                $arr_content['accbAstHdrID'] = $accbAstHdrID;
                                $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                                file_put_contents(
                                    $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AsstsRgstrimport_progress.rho",
                                    json_encode($arr_content)
                                );
                                break;
                            }
                            $accbAstHdrID = (float) getGnrlRecID("accb.accb_fa_assets_rgstr", "asset_code_name", "asset_id", $accbAstHdrName, $orgID);
                            if ($accbAstHdrID <= 0) {
                                $affctd +=  createAssetHdr(
                                    $orgID,
                                    $accbAstHdrStrtDte,
                                    $accbAstHdrEndDte,
                                    $accbAstHdrName,
                                    $accbAstHdrClsfctn,
                                    $accbAstHdrDesc,
                                    $accbAstHdrCtgry,
                                    $accbAstHdrDivGrpID,
                                    $accbAstHdrSiteID,
                                    $accbAstHdrBuildLoc,
                                    $accbAstHdrRoomNum,
                                    $accbAstHdrPrsnID,
                                    $accbAstHdrTagNum,
                                    $accbAstHdrSerialNum,
                                    $accbAstHdrBarCode,
                                    $accbAstHdrAstAcntID,
                                    $accbAstHdrDprcAcntID,
                                    $accbAstHdrExpnsAcntID,
                                    $accbAstHdrInvItemID,
                                    $accbAstHdrSQLFrmlr,
                                    $accbAstHdrSlvgValue,
                                    $accbAstHdrAutoDprctn,
                                    $accbAstHdrRvnuAcntID,
                                    $accbAstHdrMntncsAcntID
                                );
                                $accbAstHdrID = (float) getGnrlRecID("accb.accb_fa_assets_rgstr", "asset_code_name", "asset_id", $accbAstHdrName, $orgID);
                            } else {
                                $affctd +=  updtAssetHdr(
                                    $accbAstHdrID,
                                    $accbAstHdrStrtDte,
                                    $accbAstHdrEndDte,
                                    $accbAstHdrName,
                                    $accbAstHdrClsfctn,
                                    $accbAstHdrDesc,
                                    $accbAstHdrCtgry,
                                    $accbAstHdrDivGrpID,
                                    $accbAstHdrSiteID,
                                    $accbAstHdrBuildLoc,
                                    $accbAstHdrRoomNum,
                                    $accbAstHdrPrsnID,
                                    $accbAstHdrTagNum,
                                    $accbAstHdrSerialNum,
                                    $accbAstHdrBarCode,
                                    $accbAstHdrAstAcntID,
                                    $accbAstHdrDprcAcntID,
                                    $accbAstHdrExpnsAcntID,
                                    $accbAstHdrInvItemID,
                                    $accbAstHdrSQLFrmlr,
                                    $accbAstHdrSlvgValue,
                                    $accbAstHdrAutoDprctn,
                                    $accbAstHdrRvnuAcntID,
                                    $accbAstHdrMntncsAcntID
                                );
                            }
                            //Import Transactions
                            $exitErrMsg = "";
                            if ($astTrnsDesc != "" && ($astTrnsAmount) != 0) {
                                if ($astTrnsDate == "") {
                                    $astTrnsDate = $gnrlTrnsDteDMYHMS;
                                }
                                if (trim($exitErrMsg) !== "") {
                                    $arr_content['percent'] = 100;
                                    $arr_content['sbmtdAssetTransID'] = $sbmtdAssetTransID;
                                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                                    file_put_contents(
                                        $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AsstsRgstrimport_progress.rho",
                                        json_encode($arr_content)
                                    );
                                    break;
                                }
                                if ($astTrnsFuncCurRate == 1 || $astTrnsFuncCurRate == 0) {
                                    $astTrnsFuncCurRate = round(get_LtstExchRate($astTrnsCurID, $fnccurid, $astTrnsDate), 4);
                                }
                                $funcCurrAmnt = $astTrnsAmount * $astTrnsFuncCurRate;
                                $sbmtdAssetTransID = getAssetTrnsID($astTrnsType, $astTrnsDate, $astTrnsDesc);
                                if ($sbmtdAssetTransID <= 0) {
                                    $sbmtdAssetTransID = getNewAssetLnID();
                                    createAssetTrns(
                                        $sbmtdAssetTransID,
                                        $sbmtdAssetID,
                                        $astTrnsType,
                                        $astTrnsDesc,
                                        $astTrnsAmount,
                                        $astTrnsCurID,
                                        $astTrnsIncrsDcrs1,
                                        $astTrnsAccountID1,
                                        $astTrnsIncrsDcrs2,
                                        $astTrnsAccountID2,
                                        $fnccurid,
                                        $astTrnsFuncCurRate,
                                        $funcCurrAmnt,
                                        $astTrnsDate
                                    );
                                } else {
                                    updtAssetTrns(
                                        $sbmtdAssetTransID,
                                        $sbmtdAssetID,
                                        $astTrnsType,
                                        $astTrnsDesc,
                                        $astTrnsAmount,
                                        $astTrnsCurID,
                                        $astTrnsIncrsDcrs1,
                                        $astTrnsAccountID1,
                                        $astTrnsIncrsDcrs2,
                                        $astTrnsAccountID2,
                                        $fnccurid,
                                        $astTrnsFuncCurRate,
                                        $funcCurrAmnt,
                                        $astTrnsDate
                                    );
                                }
                            }
                        }
                        $percent = round((($z + 1) / $total) * 100, 2);
                        $arr_content['percent'] = $percent;
                        if ($percent >= 100) {
                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> 100% Completed!..." . $affctd . " out of " . $total . " Asset(s) processed.";
                            $arr_content['msgcount'] = $total;
                        } else {
                            $arr_content['message'] = "<i class=\"fa fa-spin fa-spinner\"></i> Importing Assets...Please Wait..." . ($z + 1) . " out of " . $total . " Asset(s) processed.";
                        }
                        file_put_contents(
                            $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AsstsRgstrimport_progress.rho",
                            json_encode($arr_content)
                        );
                    }
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['msgcount'] = "";
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AsstsRgstrimport_progress.rho", json_encode($arr_content));
                }
            } else if ($actyp == 902) {
                //Checked Importing Process Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AsstsRgstrimport_progress.rho";
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
                        "Asset Number/Code**", "Asset Description**", "Asset Classification**", "Asset Category**",
                        "Tag Number*", "Serial Number*", "Barcode*",
                        "Location (Division/Group)", "Location (Site/Branch)", "Location (Building)",
                        "Location (Floor/Room No.)", "Location (Caretaker ID No.)",
                        "Inventory Item Code",
                        "Asset Account No.**", "Appr/Depr Account No.**",
                        "Expense Account No.**", "Revenue Account No.**", "Maintenance Account No.**",
                        "Start Date**", "End Date", "Salvage Value(Sn)",
                        "Enable Auto Depreciation(YES/NO)", "SQL Formula**", "Transaction Type**",
                        "Transaction Description**", "Amount**", "Curr. Code**", "Increase/Decrease**", "Costing Acc. No.**",
                        "Increase/Decrease**", "Balancing Acc. No.**", "Transaction Date**", "Exchange Rate**"
                    );
                    $limit_size = 0;
                    if ($inptNum > 2) {
                        $limit_size = $inptNum;
                    } else if ($inptNum == 2) {
                        $limit_size = 1000000;
                    }
                    $rndm = getRandomNum(10001, 9999999);
                    $dteNm = date('dMY_His');
                    $nwFileNm = $fldrPrfx . "dwnlds/tmp/AccbAstHdrExprt_" . $dteNm . "_" . $rndm . ".csv";
                    $dwnldUrl = $app_url . "dwnlds/tmp/AccbAstHdrExprt_" . $dteNm . "_" . $rndm . ".csv";
                    $opndfile = fopen($nwFileNm, "w");
                    fputcsv($opndfile, $hdngs);
                    if ($limit_size <= 0) {
                        $arr_content['percent'] = 100;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!... Accounts Chart Template Exported.</span>";
                        $arr_content['msgcount'] = 0;
                        file_put_contents(
                            $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AccbAstHdrexprt_progress.rho",
                            json_encode($arr_content)
                        );

                        fclose($opndfile);
                        exit();
                    }
                    $z = 0;
                    $crntRw = "";
                    $result = get_One_AssetHdrNTrns($orgID, $limit_size);
                    $total = loc_db_num_rows($result);
                    $fieldCntr = loc_db_num_fields($result);
                    while ($row = loc_db_fetch_array($result)) {
                        $crntRw = array(
                            "'" . $row[0], $row[1], $row[2], $row[3], "'" . $row[4], "'" . $row[5], "'" . $row[6],
                            $row[7], $row[8], $row[9], $row[10], $row[11],
                            "'" . $row[12], "'" . $row[13], "'" . $row[14], "'" . $row[15], "'" . $row[16], "'" . $row[17], "'" . $row[18],
                            "'" . $row[19], $row[20], $row[21], $row[22], $row[23], $row[24], $row[25], $row[26], $row[27],
                            "'" . $row[28], $row[29], "'" . $row[30], "'" . $row[31], $row[32]
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
                            $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AccbAstHdrexprt_progress.rho",
                            json_encode($arr_content)
                        );
                        $z++;
                    }
                    if ($z == 0) {
                        $arr_content['percent'] = 100;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!... Template Exported.</span>";
                        $arr_content['msgcount'] = 0;
                        file_put_contents(
                            $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AccbAstHdrexprt_progress.rho",
                            json_encode($arr_content)
                        );
                    }
                    fclose($opndfile);
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['msgcount'] = "";
                    $arr_content['dwnld_url'] = "";
                    file_put_contents(
                        $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AccbAstHdrexprt_progress.rho",
                        json_encode($arr_content)
                    );
                }
            } else if ($actyp == 904) {
                //Checked Exporting Process Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_AccbAstHdrexprt_progress.rho";
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
                //Assets & Investments
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                <span style=\"text-decoration:none;\">Assets & Investments</span>
                            </li>
                           </ul>
                          </div>";
                $error = "";
                $searchAll = true;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 15;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
                $qShwNonZeroOnly = false;
                if (isset($_POST['qShwNonZeroOnly'])) {
                    $qqShwNonZeroOnly = cleanInputData($_POST['qShwNonZeroOnly']) === "true" ? true : false;
                }
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                $total = get_Total_AssetsHdr($srchFor, $srchIn, $orgID, $qShwNonZeroOnly);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_AssetsHdr($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qShwNonZeroOnly);
                $cntr = 0;
                $colClassType1 = "col-md-2";
                $colClassType2 = "col-md-5";
                $colClassType3 = "col-md-5";
?>
                <fieldset class="">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs" style="margin-top:1px !important;">
                                <li class="active"><a data-toggle="tabajxassetrgstr" data-rhodata="" href="#assetRgstrMainList" id="assetRgstrMainListtab">Summary List</a></li>
                                <li class=""><a data-toggle="tabajxassetrgstr" data-rhodata="" href="#assetRgstrDetList" id="assetRgstrDetListtab">Detailed List</a></li>
                            </ul>
                            <div class="custDiv" style="padding:0px !important;min-height: 30px !important;">
                                <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                    <div id="assetRgstrMainList" class="tab-pane fadein active" style="border:none !important;padding:0px 0px 0px 0px !important;">
                                        <form id='accbAssetsForm' action='' method='post' accept-charset='UTF-8'>
                                            <!--ROW ID-->
                                            <input class="form-control" id="tblRowID" type="hidden" placeholder="ROW ID" />
                                            <fieldset class="">
                                                <legend class="basic_person_lg1" style="color: #003245">ASSETS/INVESTMENTS</legend>
                                                <div class="row" style="margin-bottom:0px;">
                                                    <?php
                                                    if ($canAdd === true) {
                                                    ?>
                                                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 15px !important;">
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getAccbAssetDets('clear', '#assetRgstrDetList', 'grp=6&typ=1&pg=9&vtyp=1&accbSbmtdAssetID=-1&accbSbmtdAssetNm=', 1);">
                                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                New Asset/Investment
                                                            </button>
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="exprtAssetRgstr();" data-toggle="tooltip" title="Export Asset Lines">
                                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Export
                                                            </button>
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="importAssetRgstr();" data-toggle="tooltip" title="Import Asset Lines">
                                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Import
                                                            </button>
                                                        </div>
                                                    <?php
                                                    } else {
                                                        $colClassType1 = "col-md-2";
                                                        $colClassType2 = "col-md-4";
                                                        $colClassType3 = "col-md-6";
                                                    }
                                                    ?>
                                                    <div class="<?php echo $colClassType3; ?>">
                                                        <div class="input-group">
                                                            <input class="form-control" id="accbAssetsSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                                            echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                                            ?>" onkeyup="enterKeyFuncAccbAssets(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0')">
                                                            <input id="accbAssetsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbAssets('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                                                <span class="glyphicon glyphicon-remove"></span>
                                                            </label>
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbAssets('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                                                <span class="glyphicon glyphicon-search"></span>
                                                            </label>
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="accbAssetsSrchIn">
                                                                <?php
                                                                $valslctdArry = array("", "", "", "", "", "");
                                                                $srchInsArrys = array(
                                                                    "All", "Asset Code/Tag/Serial", "Asset Description", "Classification/Category",
                                                                    "Location", "Caretaker"
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
                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="accbAssetsDsplySze" style="min-width:70px !important;">
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
                                                                    <a href="javascript:getAccbAssets('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Previous">
                                                                        <span aria-hidden="true">&laquo;</span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:getAccbAssets('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Next">
                                                                        <span aria-hidden="true">&raquo;</span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </nav>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-striped table-bordered table-responsive" id="accbAssetsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th style="max-width:25px;width:25px;">No.</th>
                                                                    <th style="max-width:25px;width:25px;">...</th>
                                                                    <th style="min-width:200px;">Asset Number - Description [Classification]</th>
                                                                    <th style="min-width:120px;">Asset Location</th>
                                                                    <th style="max-width:35px;width:35px;text-align:right;">CUR.</th>
                                                                    <th style="text-align:right;min-width:100px;width:100px;">Total Asset Value</th>
                                                                    <th style="text-align:right;min-width:100px;width:100px;">Total Amount Depreciated</th>
                                                                    <th style="text-align:right;min-width:90px;width:90px;">Net Book Value</th>
                                                                    <th style="min-width:110px;width:110px;">Asset Start Date (Life Expectancy)</th>
                                                                    <?php
                                                                    if ($canDel === true) {
                                                                    ?>
                                                                        <th style="max-width:25px;width:25px;">...</th>
                                                                    <?php } ?>
                                                                    <?php if ($canVwRcHstry) { ?>
                                                                        <th style="max-width:25px;width:25px;">...</th>
                                                                    <?php } ?>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $accbAssetID = -1;
                                                                $accbAssetNm = "";
                                                                while ($row = loc_db_fetch_array($result)) {
                                                                    $cntr += 1;
                                                                    if ($cntr == 1) {
                                                                        $accbAssetID = $row[0];
                                                                        $accbAssetNm = $row[1] . " - " . $row[2] . " [" . $row[3] . " - " . $row[4] . "]";
                                                                    }
                                                                    $accbAssetID1 = $row[0];
                                                                    $accbAssetNm1 = $row[1] . " - " . $row[2] . " [" . $row[3] . " - " . $row[4] . "]";
                                                                ?>
                                                                    <tr id="accbAssetsHdrsRow_<?php echo $cntr; ?>">
                                                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Asset" onclick="getAccbAssetDets('clear', '#assetRgstrDetList', 'grp=6&typ=1&pg=9&vtyp=1&accbSbmtdAssetID=<?php echo $accbAssetID1; ?>&accbSbmtdAssetNm=<?php echo urlencode($accbAssetNm1); ?>', <?php echo $accbAssetID1; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                                                <?php
                                                                                if ($canAdd === true) {
                                                                                ?>
                                                                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                <?php } else { ?>
                                                                                    <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                <?php } ?>
                                                                            </button>
                                                                        </td>
                                                                        <td class="lovtd"><?php echo $row[1] . " - " . $row[2] . " [" . $row[3] . " - " . $row[4] . "]"; ?></td>
                                                                        <td class="lovtd"><?php echo "SITE: " . $row[6] . " DIVISION: " . $row[5]; /* . " BUILDING: " . $row[7] . " ROOM/FLOOR: " . $row[8] . " CARETAKER: " . $row[9] */ ?></td>
                                                                        <td class="lovtd" style="text-align:right;font-weight: bold;color:black;"><?php echo $fnccurnm; ?></td>
                                                                        <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                                                                                                                    echo number_format((float) $row[10], 2);
                                                                                                                                                    ?>
                                                                        </td>
                                                                        <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                                                                                                                    echo number_format((float) $row[11], 2);
                                                                                                                                                    ?></td>
                                                                        <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                                                                                                                    echo number_format((float) $row[10] - (float) $row[11], 2);
                                                                                                                                                    ?>
                                                                        </td>
                                                                        <td class="lovtd" style=""><?php echo $row[13] . " (" . $row[15] . ")"; ?></td>
                                                                        <!--<td class="lovtd"><?php echo ($row[16] == "1" ? "Yes" : "No"); ?></td>-->
                                                                        <?php
                                                                        if ($canDel === true) {
                                                                        ?>
                                                                            <td class="lovtd">
                                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Asset/Investment" onclick="delAccbAstHdr('accbAssetsHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                                    <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                </button>
                                                                                <input type="hidden" id="accbAssetsHdrsRow<?php echo $cntr; ?>_HdrID" name="accbAssetsHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                                            </td>
                                                                        <?php } ?>
                                                                        <?php
                                                                        if ($canVwRcHstry === true) {
                                                                        ?>
                                                                            <td class="lovtd">
                                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                                        echo urlencode(encrypt1(($row[0] . "|accb.accb_fa_assets_rgstr|asset_id"),
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
                                                        <input type="hidden" id="accbAssetID" name="accbAssetID" value="<?php echo $accbAssetID; ?>">
                                                        <input type="hidden" id="accbAssetNm" name="accbAssetNm" value="<?php echo $accbAssetNm; ?>">
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                    <div id="assetRgstrDetList" class="tab-pane fadein" style="border:none !important;padding:0px 0px 0px 0px !important;">
                                    </div>
                                </div>
                            </div>
                        </div>
                </fieldset>
            <?php
            } else if ($vwtyp == 1) {
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                $accbSbmtdAssetID = isset($_POST['accbSbmtdAssetID']) ? $_POST['accbSbmtdAssetID'] : -1;
                $accbSbmtdAssetNm = isset($_POST['accbSbmtdAssetNm']) ? $_POST['accbSbmtdAssetNm'] : "";
                $pkID = $accbSbmtdAssetID;
                $cntr = 0;
            ?>
                <form id='accbAstHdrForm' action='' method='post' accept-charset='UTF-8'>
                    <fieldset class="">
                        <legend class="basic_person_lg1" style="color: #003245">ASSETS/INVESTMENTS</legend>
                        <div class="row" style="margin-bottom:0px;">
                            <?php if ($canAdd === true) {
                            ?>
                                <div class="col-md-4" style="padding:0px 15px 0px 15px !important;">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getAccbAssetDets('clear', '#assetRgstrDetList', 'grp=6&typ=1&pg=9&vtyp=1&accbSbmtdAssetID=-1&accbSbmtdAssetNm=', 1);" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title=" New Asset/Investment">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Asset
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="saveAccbAstHdrForm();" style="width:100% !important;">
                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Save
                                    </button>
                                    <button type="button" class="btn btn-default" style="" onclick="getAccbAssetDets('clear', '#assetRgstrDetList', 'grp=6&typ=1&pg=9&vtyp=1');" style="width:100% !important;">
                                        <img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                        Refresh
                                    </button>
                                </div>
                            <?php
                            }
                            ?>
                            <div class="col-md-6" style="padding:0px 1px 0px 1px !important;">
                                <div class="input-group" style="width:100% !important;">
                                    <label class="btn btn-default btn-file input-group-addon">
                                        <span style="font-weight:bold;">Selected Asset:</span>
                                    </label>
                                    <input type="text" class="form-control" aria-label="..." id="accbSbmtdAssetNm" name="accbSbmtdAssetNm" value="<?php echo $accbSbmtdAssetNm; ?>" readonly="true" style="width:100% !important;">
                                    <input type="hidden" class="form-control" aria-label="..." id="accbSbmtdAssetID" value="<?php echo $accbSbmtdAssetID; ?>" style="width:100% !important;">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Asset Register', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbSbmtdAssetID', 'accbSbmtdAssetNm', 'clear', 1, '', function () {
                                                                getAccbAssetDets('clear', '#assetRgstrDetList', 'grp=6&typ=1&pg=9&vtyp=1');
                                                            });">
                                        <span class="glyphicon glyphicon-th-list"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding:1px 15px 1px 15px !important;">
                            <hr style="margin:1px 0px 3px 0px;">
                        </div>
                        <div class="row " style="margin-bottom:2px;">
                            <div class="col-md-12">
                                <div class="col-md-3" style="padding:0px 1px 0px 1px !important;">
                                    <div class="input-group">
                                        <label class="btn btn-default btn-file input-group-addon">
                                            <span style="font-weight:bold;">Total Asset Value:</span>
                                        </label>
                                        <?php
                                        $aedffrc = getAssetTrnsTypeSum($accbSbmtdAssetID, "1Initial Value")
                                            + getAssetTrnsTypeSum($accbSbmtdAssetID, "3Appreciate Asset");
                                        $style1 = "color:green;";
                                        ?>
                                        <input class="form-control" id="accbAssetTtlValAmt" type="text" placeholder="0.00" value="<?php
                                                                                                                                    echo number_format($aedffrc, 2);
                                                                                                                                    ?>" readonly="true" style="font-size:16px;font-weight:bold;<?php echo $style1; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding:0px 1px 0px 1px !important;">
                                    <div class="input-group">
                                        <label class="btn btn-default btn-file input-group-addon">
                                            <span style="font-weight:bold;">Total Depreciation:</span>
                                        </label>
                                        <?php
                                        $lerdffrc = getAssetTrnsTypeSum($accbSbmtdAssetID, "2Depreciate Asset")
                                            + getAssetTrnsTypeSum($accbSbmtdAssetID, "4Retire Asset");
                                        $style1 = "color:green;";
                                        ?>
                                        <input class="form-control" id="accbAssetTtlDprctnAmt" type="text" placeholder="0.00" value="<?php
                                                                                                                                        echo number_format($lerdffrc, 2);
                                                                                                                                        ?>" readonly="true" style="font-size:16px;font-weight:bold;<?php echo $style1; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding:0px 1px 0px 1px !important;">
                                    <div class="input-group">
                                        <label class="btn btn-default btn-file input-group-addon">
                                            <span style="font-weight:bold;">Net Book Value:</span>
                                        </label>
                                        <?php
                                        $dffrc = $aedffrc - $lerdffrc;
                                        $style1 = "color:green;";
                                        if ($dffrc <= 0) {
                                            $style1 = "color:red;";
                                        }
                                        ?>
                                        <input class="form-control" id="accbAssetNetBookValAmt" type="text" placeholder="0.00" value="<?php
                                                                                                                                        echo number_format($dffrc, 2);
                                                                                                                                        ?>" readonly="true" style="font-size:16px;font-weight:bold;<?php echo $style1; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding:0px 1px 0px 1px !important;">
                                    <div class="input-group">
                                        <label class="btn btn-default btn-file input-group-addon">
                                            <span style="font-weight:bold;">Life Left:</span>
                                        </label>
                                        <?php
                                        $rmngLife = "";
                                        ?>
                                        <input class="form-control" id="accbAstHdrRmngLife1" type="text" placeholder="" value="<?php echo $rmngLife; ?>" readonly="true" style="font-size:16px;font-weight:bold;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding:1px 15px 1px 15px !important;">
                            <hr style="margin:1px 0px 3px 0px;">
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset class="">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <ul class="nav nav-tabs" style="margin-top:1px !important;">
                                                <li class="active"><a data-toggle="tabajxassetdetls" data-rhodata="" href="#assetDetlsInfo" id="assetDetlsInfotab">ASSET/INVESTMENT INFORMATION</a></li>
                                                <li class=""><a data-toggle="tabajxassetdetls" data-rhodata="" href="#assetDetlsTrans" id="assetDetlsTranstab">ACCOUNTING TRANSACTIONS</a></li>
                                                <li class=""><a data-toggle="tabajxassetdetls" data-rhodata="" href="#assetDetlsPMRecs" id="assetDetlsPMRecstab">PREVENTIVE MAINTENANCE RECORDS</a></li>
                                            </ul>
                                            <div class="custDiv" style="padding:0px !important;min-height: 30px !important;">
                                                <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                                    <div id="assetDetlsInfo" class="tab-pane fadein active" style="border:none !important;padding:0px 0px 0px 0px !important;">
                                                        <fieldset class="basic_person_fs" style="padding-top:2px !important;">
                                                            <div class="container-fluid" id="accbAssetHdrDetailInfo">
                                                                <?php
                                                                $accbAstHdrID = -1;
                                                                $accbAstHdrName = "";
                                                                $accbAstHdrDesc = "";
                                                                $accbAstHdrClsfctn = "";
                                                                $accbAstHdrCtgry = "";
                                                                $accbAstHdrDivGrpID = -1;
                                                                $accbAstHdrDivGrpNm = "";
                                                                $accbAstHdrSiteID = -1;
                                                                $accbAstHdrSiteNm = "";
                                                                $accbAstHdrBuildLoc = "";
                                                                $accbAstHdrRoomNum = "";
                                                                $accbAstHdrPrsnID = -1;
                                                                $accbAstHdrPrsnNm = "";

                                                                $accbAstHdrTagNum = "";
                                                                $accbAstHdrSerialNum = "";
                                                                $accbAstHdrBarCode = "";
                                                                $accbAstHdrStrtDte = "";
                                                                $accbAstHdrEndDte = "";

                                                                $accbAstHdrAstAcntID = -1;
                                                                $accbAstHdrAstAcntNm = "";
                                                                $accbAstHdrDprcAcntID = -1;
                                                                $accbAstHdrDprcAcntNm = "";
                                                                $accbAstHdrExpnsAcntID = -1;
                                                                $accbAstHdrExpnsAcntNm = "";
                                                                $accbAstHdrRvnuAcntID = -1;
                                                                $accbAstHdrRvnuAcntNm = "";
                                                                $accbAstHdrMntncsAcntID = -1;
                                                                $accbAstHdrMntncsAcntNm = "";
                                                                $accbAstHdrInvItemID = -1;
                                                                $accbAstHdrInvItemNm = "";

                                                                $accbAstHdrSQLFrmlr = "Select 0.00";
                                                                $accbAstHdrSlvgValue = 0.00;
                                                                $accbAstHdrAutoDprctn = "0";
                                                                $accbAstHdrAge = "";
                                                                $accbAstHdrRmngLife = "";
                                                                if ($pkID > 0) {
                                                                    $result1 = get_One_AssetHdr($pkID);
                                                                    $slctdCodeIDs = ",";
                                                                    while ($row1 = loc_db_fetch_array($result1)) {
                                                                        $accbAstHdrID = $row1[0];
                                                                        $accbAstHdrName = $row1[1];
                                                                        $accbAstHdrDesc = $row1[2];
                                                                        $accbAstHdrClsfctn = $row1[3];
                                                                        $accbAstHdrCtgry = $row1[4];
                                                                        $accbAstHdrDivGrpID = $row1[5];
                                                                        $accbAstHdrDivGrpNm = $row1[6];
                                                                        $accbAstHdrSiteID = $row1[7];
                                                                        $accbAstHdrSiteNm = $row1[8];
                                                                        $accbAstHdrBuildLoc = $row1[9];
                                                                        $accbAstHdrRoomNum = $row1[10];
                                                                        $accbAstHdrPrsnID = $row1[11];
                                                                        $accbAstHdrPrsnNm = $row1[12];

                                                                        $accbAstHdrTagNum = $row1[13];
                                                                        $accbAstHdrSerialNum = $row1[14];
                                                                        $accbAstHdrBarCode = $row1[15];
                                                                        $accbAstHdrStrtDte = $row1[16];
                                                                        $accbAstHdrEndDte = $row1[17];

                                                                        $accbAstHdrAstAcntID = $row1[18];
                                                                        $accbAstHdrAstAcntNm = $row1[19];
                                                                        $accbAstHdrDprcAcntID = $row1[20];
                                                                        $accbAstHdrDprcAcntNm = $row1[21];
                                                                        $accbAstHdrExpnsAcntID = $row1[22];
                                                                        $accbAstHdrExpnsAcntNm = $row1[23];

                                                                        $accbAstHdrRvnuAcntID = $row1[29];
                                                                        $accbAstHdrRvnuAcntNm = $row1[30];
                                                                        $accbAstHdrMntncsAcntID = $row1[31];
                                                                        $accbAstHdrMntncsAcntNm = $row1[32];

                                                                        $accbAstHdrInvItemID = $row1[24];
                                                                        $accbAstHdrInvItemNm = $row1[25];

                                                                        $accbAstHdrSQLFrmlr = $row1[26];
                                                                        $accbAstHdrSlvgValue = $row1[27];
                                                                        $accbAstHdrAutoDprctn = $row1[28];
                                                                        $accbAstHdrAge = computeCrrntAge($accbAstHdrStrtDte);
                                                                        $accbAstHdrRmngLife = computeLifeSpan(
                                                                            getFrmtdDB_Date_time(),
                                                                            $accbAstHdrEndDte
                                                                        );
                                                                    }
                                                                }
                                                                ?>
                                                                <div class="row">
                                                                    <div class="col-md-6" style="padding:0px 1px 0px 0px !important;">
                                                                        <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                                <label for="accbAstHdrName" class="control-label col-lg-4">Asset Code:</label>
                                                                                <div class="col-lg-8">
                                                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="accbAstHdrName" name="accbAstHdrName" value="<?php echo $accbAstHdrName; ?>" style="width:100% !important;">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="accbAstHdrID" name="accbAstHdrID" value="<?php echo $accbAstHdrID; ?>">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="accbAstHdrRmngLife" name="accbAstHdrRmngLife" value="<?php echo $accbAstHdrRmngLife; ?>">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                                <label for="accbAstHdrDesc" class="control-label col-lg-4">Description:</label>
                                                                                <div class="col-lg-8">
                                                                                    <?php
                                                                                    if ($canEdt === true) {
                                                                                    ?>
                                                                                        <textarea class="form-control rqrdFld" rows="4" cols="20" id="accbAstHdrDesc" name="accbAstHdrDesc" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $accbAstHdrDesc; ?></textarea>
                                                                                    <?php } else {
                                                                                    ?>
                                                                                        <span><?php echo $accbAstHdrDesc; ?></span>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                                <label for="accbAstHdrInvItemNm" class="control-label col-md-4">Linked Item (Inv):</label>
                                                                                <div class="col-md-8">
                                                                                    <div class="input-group">
                                                                                        <input class="form-control" id="accbAstHdrInvItemNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Inventory Item" type="text" min="0" placeholder="" value="<?php echo $accbAstHdrInvItemNm; ?>" readonly="true" />
                                                                                        <input type="hidden" id="accbAstHdrInvItemID" value="<?php echo $accbAstHdrInvItemID; ?>">
                                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Inventory Items', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbAstHdrInvItemID', 'accbAstHdrInvItemNm', 'clear', 1, '', function () {});">
                                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                                <label for="accbAstHdrClsfctn" class="control-label col-md-4">Asset Classification:</label>
                                                                                <div class="col-md-8">
                                                                                    <div class="input-group">
                                                                                        <input class="form-control rqrdFld" id="accbAstHdrClsfctn" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Classification" type="text" placeholder="" value="<?php echo $accbAstHdrClsfctn; ?>" readonly="true" />
                                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Asset Classifications', '', '', '', 'radio', true, '', 'accbAstHdrClsfctn', '', 'clear', 1, '', function () {});">
                                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                                <label for="accbAstHdrCtgry" class="control-label col-md-4">Asset Category:</label>
                                                                                <div class="col-md-8">
                                                                                    <div class="input-group">
                                                                                        <input class="form-control rqrdFld" id="accbAstHdrCtgry" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Category" type="text" placeholder="" value="<?php echo $accbAstHdrCtgry; ?>" readonly="true" />
                                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Asset Categories', '', '', '', 'radio', true, '', 'accbAstHdrCtgry', '', 'clear', 1, '', function () {});">
                                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                                <label for="accbAstHdrDivGrpNm" class="control-label col-md-4">Division/Group:</label>
                                                                                <div class="col-md-8">
                                                                                    <div class="input-group">
                                                                                        <input class="form-control rqrdFld" id="accbAstHdrDivGrpNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Division/Group" type="text" value="<?php echo $accbAstHdrDivGrpNm; ?>" readonly="true" />
                                                                                        <input type="hidden" id="accbAstHdrDivGrpID" value="<?php echo $accbAstHdrDivGrpID; ?>">
                                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Divisions/Groups', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbAstHdrDivGrpID', 'accbAstHdrDivGrpNm', 'clear', 1, '', function () {});">
                                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                                <label for="accbAstHdrSiteNm" class="control-label col-md-4">Site/Branch:</label>
                                                                                <div class="col-md-8">
                                                                                    <div class="input-group">
                                                                                        <input class="form-control rqrdFld" id="accbAstHdrSiteNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Site/Branch" type="text" value="<?php echo $accbAstHdrSiteNm; ?>" readonly="true" />
                                                                                        <input type="hidden" id="accbAstHdrSiteID" value="<?php echo $accbAstHdrSiteID; ?>">
                                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sites/Locations', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbAstHdrSiteID', 'accbAstHdrSiteNm', 'clear', 1, '', function () {});">
                                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                                <label for="accbAstHdrBuildLoc" class="control-label col-md-4">Location (Building):</label>
                                                                                <div class="col-md-8">
                                                                                    <div class="input-group">
                                                                                        <input class="form-control" id="accbAstHdrBuildLoc" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Building" type="text" value="<?php echo $accbAstHdrBuildLoc; ?>" readonly="true" />
                                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Asset Building Names', '', '', '', 'radio', true, '', 'accbAstHdrBuildLoc', '', 'clear', 1, '', function () {});">
                                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                                <label for="accbAstHdrRoomNum" class="control-label col-md-4">Floor/Room No.:</label>
                                                                                <div class="col-md-8">
                                                                                    <div class="input-group">
                                                                                        <input class="form-control" id="accbAstHdrRoomNum" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Room Number" type="text" value="<?php echo $accbAstHdrRoomNum; ?>" readonly="true" />
                                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Asset Room Names', '', '', '', 'radio', true, '', 'accbAstHdrRoomNum', '', 'clear', 1, '', function () {});">
                                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                                <label for="accbAstHdrPrsnNm" class="control-label col-md-4">Location (Caretaker):</label>
                                                                                <div class="col-md-8">
                                                                                    <div class="input-group">
                                                                                        <input class="form-control" id="accbAstHdrPrsnNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Caretaker" type="text" value="<?php echo $accbAstHdrPrsnNm; ?>" readonly="true" />
                                                                                        <input type="hidden" id="accbAstHdrPrsnID" value="<?php echo $accbAstHdrPrsnID; ?>">
                                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Persons', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbAstHdrPrsnID', 'accbAstHdrPrsnNm', 'clear', 1, '', function () {});">
                                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                                <label for="accbAstHdrAutoDprctn" class="control-label col-lg-6">Enable Auto-Depreciation?:</label>
                                                                                <div class="col-lg-6">
                                                                                    <?php
                                                                                    $chkdYes = "";
                                                                                    $chkdNo = "checked=\"\"";
                                                                                    if ($accbAstHdrAutoDprctn == "1") {
                                                                                        $chkdNo = "";
                                                                                        $chkdYes = "checked=\"\"";
                                                                                    }
                                                                                    ?>
                                                                                    <label class="radio-inline"><input type="radio" name="accbAstHdrAutoDprctn" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                                    <label class="radio-inline"><input type="radio" name="accbAstHdrAutoDprctn" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                                </div>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                    <div class="col-md-6" style="padding:0px 0px 0px 1px !important;">
                                                                        <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                                <label for="accbAstHdrTagNum" class="control-label col-lg-4">Tag Number:</label>
                                                                                <div class="col-lg-8">
                                                                                    <input type="text" class="form-control" aria-label="..." id="accbAstHdrTagNum" name="accbAstHdrTagNum" value="<?php echo $accbAstHdrTagNum; ?>" style="width:100% !important;">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                                <label for="accbAstHdrSerialNum" class="control-label col-lg-4">Serial Number:</label>
                                                                                <div class="col-lg-8">
                                                                                    <input type="text" class="form-control" aria-label="..." id="accbAstHdrSerialNum" name="accbAstHdrSerialNum" value="<?php echo $accbAstHdrSerialNum; ?>" style="width:100% !important;">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                                <label for="accbAstHdrBarCode" class="control-label col-lg-4">Barcode:</label>
                                                                                <div class="col-lg-8">
                                                                                    <input type="text" class="form-control" aria-label="..." id="accbAstHdrBarCode" name="accbAstHdrBarCode" value="<?php echo $accbAstHdrBarCode; ?>" style="width:100% !important;">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                                <label class="control-label col-lg-4" style="margin-bottom:0px !important;">Start Date:</label>
                                                                                <div class="col-lg-8">
                                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                                        <input class="form-control rqrdFld" size="16" type="text" id="accbAstHdrStrtDte" name="accbAstHdrStrtDte" value="<?php
                                                                                                                                                                                                            echo substr($accbAstHdrStrtDte, 0, 11);
                                                                                                                                                                                                            ?>" placeholder="">
                                                                                        <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                                <label class="control-label col-lg-4" style="margin-bottom:0px !important;">End Date:</label>
                                                                                <div class="col-lg-8">
                                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                                        <input class="form-control rqrdFld" size="16" type="text" id="accbAstHdrEndDte" name="accbAstHdrEndDte" value="<?php
                                                                                                                                                                                                        echo substr($accbAstHdrEndDte, 0, 11);
                                                                                                                                                                                                        ?>" placeholder="">
                                                                                        <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:5px 0px 0px 0px !important;">
                                                                                <label for="accbAstHdrAge" class="control-label col-lg-4">Current Age:</label>
                                                                                <div class="col-lg-8">
                                                                                    <span style="border:1px solid #ddd;border-radius: 2px;padding:5px;height:30px;width:100% !important;font-weight:bold;"><?php echo $accbAstHdrAge; ?></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                                <label for="accbAstHdrAstAcntNm" class="control-label col-md-4">Asset Account:</label>
                                                                                <div class="col-md-8">
                                                                                    <div class="input-group">
                                                                                        <input class="form-control rqrdFld" id="accbAstHdrAstAcntNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Asset Account" type="text" value="<?php echo $accbAstHdrAstAcntNm; ?>" readonly="true" />
                                                                                        <input type="hidden" id="accbAstHdrAstAcntID" value="<?php echo $accbAstHdrAstAcntID; ?>">
                                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Asset Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbAstHdrAstAcntID', 'accbAstHdrAstAcntNm', 'clear', 1, '', function () {});">
                                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                                <label for="accbAstHdrDprcAcntNm" class="control-label col-md-4">Appreciation or Depreciation:</label>
                                                                                <div class="col-md-8">
                                                                                    <div class="input-group">
                                                                                        <input class="form-control rqrdFld" id="accbAstHdrDprcAcntNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Appreciation/Depreciation Account" type="text" value="<?php echo $accbAstHdrDprcAcntNm; ?>" readonly="true" />
                                                                                        <input type="hidden" id="accbAstHdrDprcAcntID" value="<?php echo $accbAstHdrDprcAcntID; ?>">
                                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Asset Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbAstHdrDprcAcntID', 'accbAstHdrDprcAcntNm', 'clear', 1, '', function () {});">
                                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                                <label for="accbAstHdrExpnsAcntNm" class="control-label col-md-4">Depreciation Expense:</label>
                                                                                <div class="col-md-8">
                                                                                    <div class="input-group">
                                                                                        <input class="form-control rqrdFld" id="accbAstHdrExpnsAcntNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Depreciation Expense Account" type="text" value="<?php echo $accbAstHdrExpnsAcntNm; ?>" readonly="true" />
                                                                                        <input type="hidden" id="accbAstHdrExpnsAcntID" value="<?php echo $accbAstHdrExpnsAcntID; ?>">
                                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Expense Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbAstHdrExpnsAcntID', 'accbAstHdrExpnsAcntNm', 'clear', 1, '', function () {});">
                                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                                <label for="accbAstHdrAstAcntNm" class="control-label col-md-4">Revenue Account:</label>
                                                                                <div class="col-md-8">
                                                                                    <div class="input-group">
                                                                                        <input class="form-control rqrdFld" id="accbAstHdrRvnuAcntNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Asset Sale/Appreciation Revenue Account" type="text" value="<?php echo $accbAstHdrRvnuAcntNm; ?>" readonly="true" />
                                                                                        <input type="hidden" id="accbAstHdrRvnuAcntID" value="<?php echo $accbAstHdrRvnuAcntID; ?>">
                                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Revenue Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbAstHdrRvnuAcntID', 'accbAstHdrRvnuAcntNm', 'clear', 1, '', function () {});">
                                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                                <label for="accbAstHdrMntncsAcntNm" class="control-label col-md-4">Maintenance Account:</label>
                                                                                <div class="col-md-8">
                                                                                    <div class="input-group">
                                                                                        <input class="form-control rqrdFld" id="accbAstHdrMntncsAcntNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Asset Repairs/Maintenance Account" type="text" value="<?php echo $accbAstHdrMntncsAcntNm; ?>" readonly="true" />
                                                                                        <input type="hidden" id="accbAstHdrMntncsAcntID" value="<?php echo $accbAstHdrMntncsAcntID; ?>">
                                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Expense Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbAstHdrMntncsAcntID', 'accbAstHdrMntncsAcntNm', 'clear', 1, '', function () {});">
                                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                                <label for="accbAstHdrSlvgValue" class="control-label col-lg-4">Salvage Value (Sn):</label>
                                                                                <div class="col-lg-8">
                                                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="accbAstHdrSlvgValue" name="accbAstHdrSlvgValue" value="<?php echo $accbAstHdrSlvgValue; ?>" style="width:100% !important;">
                                                                                </div>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                        <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;margin-bottom: 5px!important;">
                                                                                <label for="accbAstHdrSQLFrmlr" class="control-label col-md-2">SQL Formular (For Auto-Depreciation):</label>
                                                                                <div class="col-md-10">
                                                                                    <div class="input-group" style="width:100%;">
                                                                                        <textarea class="form-control rqrdFld" rows="2" cols="20" id="accbAstHdrSQLFrmlr" name="accbAstHdrSQLFrmlr" style="text-align:left !important;"><?php echo $accbAstHdrSQLFrmlr; ?></textarea>
                                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('accbAstHdrSQLFrmlr');" style="max-width:30px;width:30px;">
                                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                        <?php
                                                                        $nwRowHtml33 = "<tr id=\"accbAssetsPMStpsRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#accbTaxCodeAdtTblsTable tr').index(this));\">"
                                                                            . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>  
                                                                                           <td class=\"lovtd\">
                                                                                                    <input type=\"hidden\" id=\"accbAssetsPMStpsRow_WWW123WWW_LineID\" value=\"-1\"/>
                                                                                                    <div class=\"input-group\" style=\"width:100% !important;\">
                                                                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"accbAssetsPMStpsRow_WWW123WWW_MsrmntNm\" name=\"accbAssetsPMStpsRow_WWW123WWW_MsrmntNm\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                                                            <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'PM Measurement Types', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbAssetsPMStpsRow_WWW123WWW_MsrmntNm', '', 'clear', 1, '', function () {});\">
                                                                                                                <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                                            </label>
                                                                                                    </div>                                             
                                                                                                </td>                                           
                                                                                                <td class=\"lovtd\" style=\"max-width:80px;width:80px;text-align:center;\">
                                                                                                    <div class=\"\" style=\"width:100% !important;\">
                                                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"accbAssetsPMStpsRow_WWW123WWW_UOM\" name=\"accbAssetsPMStpsRow_WWW123WWW_UOM\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                                                        <label style=\"width:100% !important;\" class=\"btn btn-primary btn-file\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'PM Measurement Units', '', '', '', 'radio', true, '', 'accbAssetsPMStpsRow_WWW123WWW_UOM', '', 'clear', 1, '', function () {
                                                                                                                                                $('#accbAssetsPMStpsRow_WWW123WWW_UOM1').html($('#accbAssetsPMStpsRow_WWW123WWW_UOM').val());
                                                                                                                                            });\">
                                                                                                            <span class=\"\" id=\"accbAssetsPMStpsRow_WWW123WWW_UOM1\">UOM</span>
                                                                                                        </label>
                                                                                                    </div>                                              
                                                                                                </td>
                                                                                                <td class=\"lovtd\">
                                                                                                    <input style=\"width:100% !important;\" type=\"number\" id=\"accbAssetsPMStpsRow_WWW123WWW_MxFigure\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'accbAssetsPMStpsRow_WWW123WWW_MxFigure', 'accbAssetsPMStpsTable', 'jbDetDbt');\"/>
                                                                                                </td>
                                                                                                <td class=\"lovtd\">
                                                                                                    <input style=\"width:100% !important;\" type=\"number\" id=\"accbAssetsPMStpsRow_WWW123WWW_PMFigure\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'accbAssetsPMStpsRow_WWW123WWW_PMFigure', 'accbAssetsPMStpsTable', 'jbDetCrdt');\"/>
                                                                                                </td>
                                                                                                <td class=\"lovtd\">
                                                                                                    <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAccbAstPMStp('accbAssetsPMStpsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Measurement\">
                                                                                                        <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                                                    </button>
                                                                                                </td>";
                                                                        $nwRowHtml33 .= "</tr>";
                                                                        $nwRowHtml33 = urlencode($nwRowHtml33);
                                                                        $nwRowHtml1 = $nwRowHtml33;
                                                                        ?>
                                                                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                            <div class="col-md-8" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                                <?php if ($canEdt) { ?>
                                                                                    <button id="addNwAccbMsrdRecStpBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="insertNewAccbAssetPMStpRows('accbAssetsPMStpsTable', 0, '<?php echo $nwRowHtml1; ?>');" data-toggle="tooltip" data-placement="bottom" title="New PM Setup (Measurement)">
                                                                                        <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                        Add New Measured Record Setup
                                                                                    </button>
                                                                                <?php } ?>
                                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="getOneAccbPyblsInvcDocsForm(<?php echo $pkID; ?>, 20);" data-toggle="tooltip" data-placement="bottom" title="Attached Documents">
                                                                                    <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                </button>
                                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="getAccbAssetDets('clear', '#assetRgstrDetList', 'grp=6&typ=1&pg=9&vtyp=1');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                        <table class="table table-striped table-bordered table-responsive" id="accbAssetsPMStpsTable" cellspacing="0" width="100%" style="width:100%;min-width: 700px;">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th style="max-width:30px;width:30px;text-align: center;">No.</th>
                                                                                    <th>Measurement Type</th>
                                                                                    <th style="max-width:80px;width:80px;text-align: center;">UOM</th>
                                                                                    <th>Allowed Maximum Net Figure Per Day</th>
                                                                                    <th>Cumulative Figure Before Preventive Maintenance</th>
                                                                                    <th style="max-width:30px;width:30px;text-align: center;">...</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php
                                                                                $cntr = 0;
                                                                                $curIdx = 0;
                                                                                $resultPMS = get_One_AssetPMStps($pkID);
                                                                                while ($rwPMS = loc_db_fetch_array($resultPMS)) {
                                                                                    $lineDetID = (float) $rwPMS[0];
                                                                                    $lineMsrmntType = $rwPMS[1];
                                                                                    $lineUOM = $rwPMS[2];
                                                                                    $lineMaxFigure = (float) $rwPMS[3];
                                                                                    $linePMFigure = (float) $rwPMS[4];
                                                                                    $cntr += 1;
                                                                                ?>
                                                                                    <tr id="accbAssetsPMStpsRow_<?php echo $cntr; ?>">
                                                                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                                                        <td class="lovtd">
                                                                                            <input type="hidden" id="accbAssetsPMStpsRow<?php echo $cntr; ?>_LineID" value="<?php echo $lineDetID; ?>" />
                                                                                            <?php
                                                                                            if ($canEdt === true) {
                                                                                            ?>
                                                                                                <div class="input-group" style="width:100% !important;">
                                                                                                    <input type="text" class="form-control" aria-label="..." id="accbAssetsPMStpsRow<?php echo $cntr; ?>_MsrmntNm" name="accbAssetsPMStpsRow<?php echo $cntr; ?>_MsrmntNm" value="<?php echo $lineMsrmntType; ?>" readonly="true" style="width:100% !important;">
                                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'PM Measurement Types', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbAssetsPMStpsRow<?php echo $cntr; ?>_MsrmntNm', '', 'clear', 1, '', function () {});">
                                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                                    </label>
                                                                                                </div>
                                                                                            <?php } else { ?>
                                                                                                <span><?php echo $lineMsrmntType; ?></span>
                                                                                            <?php } ?>
                                                                                        </td>
                                                                                        <td class="lovtd" style="max-width:80px;width:80px;text-align: center;">
                                                                                            <div class="" style="width:100% !important;">
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="accbAssetsPMStpsRow<?php echo $cntr; ?>_UOM" name="accbAssetsPMStpsRow<?php echo $cntr; ?>_UOM" value="<?php echo $lineUOM; ?>" readonly="true" style="width:100% !important;">
                                                                                                <label style="width:100% !important;" class="btn btn-primary btn-file" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'PM Measurement Units', '', '', '', 'radio', true, '', 'accbAssetsPMStpsRow<?php echo $cntr; ?>_UOM', '', 'clear', 1, '', function () {
                                                                                                                                $('#accbAssetsPMStpsRow<?php echo $cntr; ?>_UOM1').html($('#accbAssetsPMStpsRow<?php echo $cntr; ?>_UOM').val());
                                                                                                                            });">
                                                                                                    <span class="" id="accbAssetsPMStpsRow<?php echo $cntr; ?>_UOM1"><?php echo $lineUOM; ?></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td class="lovtd">
                                                                                            <input style="width:100% !important;" class="jbDetDbt" type="number" id="accbAssetsPMStpsRow<?php echo $cntr; ?>_MxFigure" value="<?php echo $lineMaxFigure; ?>" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'accbAssetsPMStpsRow<?php echo $cntr; ?>_MxFigure', 'accbAssetsPMStpsTable', 'jbDetDbt');" />
                                                                                        </td>
                                                                                        <td class="lovtd">
                                                                                            <input style="width:100% !important;" class="jbDetCrdt" type="number" id="accbAssetsPMStpsRow<?php echo $cntr; ?>_PMFigure" value="<?php echo $linePMFigure; ?>" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'accbAssetsPMStpsRow<?php echo $cntr; ?>_PMFigure', 'accbAssetsPMStpsTable', 'jbDetCrdt');" />
                                                                                        </td>
                                                                                        <td class="lovtd">
                                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbAstPMStp('accbAssetsPMStpsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Measurement">
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
                                                                <div class="row">
                                                                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                        <fieldset class="basic_person_fs2" style="min-height:50px !important;padding: 5px 2px 5px 2px !important;margin-left:3px !important;">
                                                                            <table class="table table-striped table-bordered table-responsive" id="oneAccbAssetsExtrInfTable" cellspacing="0" width="100%" style="width:100%;min-width: 200px !important;">
                                                                                <caption class="basic_person_lg" style="color:black !important;font-weight:bold;">EXTRA INFORMATION VALUES</caption>
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th style="max-width:30px;width:30px;">No.</th>
                                                                                        <th style="">Extra Info Category</th>
                                                                                        <th>Extra Info Label</th>
                                                                                        <th style="min-width:300px;">Value</th>
                                                                                        <?php
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
                                                                                        getMdlGrpID("Fixed Assets", $mdlNm),
                                                                                        $pkID,
                                                                                        "accb.accb_all_other_info_table"
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
                                                                                        <tr id="oneAccbAssetsExtrInfRow_<?php echo $cntr; ?>">
                                                                                            <td class="lovtd"><span><?php echo ($cntr); ?></span></td>
                                                                                            <td class="lovtd" style="">
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAccbAssetsExtrInfRow<?php echo $cntr; ?>_DfltRowID" value="<?php echo $dfltRowID; ?>" style="width:100% !important;">
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAccbAssetsExtrInfRow<?php echo $cntr; ?>_CombntnID" value="<?php echo $cmbntnID; ?>" style="width:100% !important;">
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAccbAssetsExtrInfRow<?php echo $cntr; ?>_TableID" value="<?php echo $tableID; ?>" style="width:100% !important;">
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAccbAssetsExtrInfRow<?php echo $cntr; ?>_extrInfoCtgry" value="<?php echo $extrInfoCtgry; ?>" style="width:100% !important;">
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAccbAssetsExtrInfRow<?php echo $cntr; ?>_extrInfoLbl" value="<?php echo $extrInfoLbl; ?>" style="width:100% !important;">
                                                                                                <span><?php echo $extrInfoCtgry; ?></span>
                                                                                            </td>
                                                                                            <td class="lovtd" style="">
                                                                                                <span><?php echo $extrInfoCtgry; ?></span>
                                                                                            </td>
                                                                                            <td class="lovtd" style="">
                                                                                                <input type="text" class="form-control jbDetRfDc" aria-label="..." id="oneAccbAssetsExtrInfRow<?php echo $cntr; ?>_Value" name="oneAccbAssetsExtrInfRow<?php echo $cntr; ?>_Value" value="<?php echo $extrInfoVal; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneAccbAssetsExtrInfRow<?php echo $cntr; ?>_Value', 'oneAccbAssetsExtrInfTable', 'jbDetRfDc');">
                                                                                            </td>
                                                                                            <?php
                                                                                            if ($canVwRcHstry === true) {
                                                                                            ?>
                                                                                                <td class="lovtd">
                                                                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                                                            echo urlencode(encrypt1(($dfltRowID . "|accb.accb_all_other_info_table|dflt_row_id"),
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
                                                                <?php ?>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                    <div id="assetDetlsTrans" class="tab-pane fadein" style="border:none !important;padding:0px 0px 0px 0px !important;">
                                                    </div>
                                                    <div id="assetDetlsPMRecs" class="tab-pane fadein" style="border:none !important;padding:0px 0px 0px 0px !important;">
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
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                $accbSbmtdAssetID = isset($_POST['accbSbmtdAssetID']) ? $_POST['accbSbmtdAssetID'] : -1;
                $accbSbmtdAssetNm = isset($_POST['accbSbmtdAssetNm']) ? $_POST['accbSbmtdAssetNm'] : "";
                $pkID = $accbSbmtdAssetID;
                $cntr = 0;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 15;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                $total = get_TtlAssetTrns($srchFor, $srchIn, $pkID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_AssetTrns($srchFor, $srchIn, $curIdx, $lmtSze, $pkID);
                $cntr = 0;
                $vwtyp = 2;
            ?>
                <fieldset class="basic_person_fs" style="padding-top:2px !important;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-5" style="padding:0px 0px 0px 0px !important;float:left;">
                                <button id="addNwAccbAssetTransBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getOneAccbAssetTransForm(-1,<?php echo $accbSbmtdAssetID; ?>, 'ShowDialog');" data-toggle="tooltip" data-placement="bottom" title="New Asset/Investment Transaction Line">
                                    <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Asset Transaction
                                </button>
                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getAccbAstHdr('', '#assetDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                            </div>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <input class="form-control" id="accbAstHdrSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                    echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                    ?>" onkeyup="enterKeyFuncAccbAstHdr(event, '', '#assetDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <input id="accbAstHdrPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbAstHdr('clear', '#assetDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbAstHdr('', '#assetDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="accbAstHdrSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "");
                                        $srchInsArrys = array("Account Number/Description", "Transaction Description", "Transaction Date");

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                        ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="accbAstHdrDsplySze" style="min-width:70px !important;">
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
                            <div class="col-md-2">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a class="rhopagination" href="javascript:getAccbAstHdr('previous', '#assetDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAccbAstHdr('next', '#assetDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding:1px 15px 1px 15px !important;">
                        <hr style="margin:1px 0px 3px 0px;">
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="oneAccbAssetTransLinesTable" cellspacing="0" width="100%" style="width:100%;min-width: 900px !important;">
                                <thead>
                                    <tr>
                                        <th style="max-width:35px;width:35px;">No.</th>
                                        <th style="max-width:25px;width:25px;">...</th>
                                        <th>Transaction Type / Description</th>
                                        <th style="text-align:center;">CUR.</th>
                                        <th style="text-align:right;">Entered Amount</th>
                                        <th style="max-width:60px;width:60px;text-align: center;">Incrs./ Dcrs.</th>
                                        <th style="max-width:110px;">Charge Account</th>
                                        <th style="max-width:60px;width:60px;text-align: center;">Incrs./ Dcrs.</th>
                                        <th style="max-width:110px;">Balancing Account</th>
                                        <th style="max-width:90px;width:90px;">Transaction Date</th>
                                        <th>GL Batch Name</th>
                                        <th style="max-width:25px;width:25px;">...</th>
                                        <?php
                                        if ($canVwRcHstry === true) {
                                        ?>
                                            <th style="max-width:25px;width:25px;">...</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($rowRw = loc_db_fetch_array($result)) {
                                        $trsctnLineID = (float) $rowRw[0];
                                        $trsctnLineType = $rowRw[1];
                                        $trsctnLineDesc = $rowRw[2];
                                        $entrdAmnt = (float) $rowRw[3];
                                        $entrdCurID = (int) $rowRw[4];
                                        $entrdCurNm = $rowRw[5];
                                        $trnsIncrsDcrs1 = $rowRw[6];
                                        $trsctnAcntID1 = $rowRw[7];
                                        $trsctnAcntNm1 = $rowRw[8];

                                        $trnsIncrsDcrs2 = $rowRw[9];
                                        $trsctnAcntID2 = $rowRw[10];
                                        $trsctnAcntNm2 = $rowRw[11];

                                        $trnstnGlBatchID = $rowRw[12];
                                        $trsctnGlBatchNm = $rowRw[13];
                                        $trsctnGlBatchIsPstd = $rowRw[14];

                                        $trsctnDate = $rowRw[15];
                                        $cntr += 1;
                                    ?>
                                        <tr id="oneAccbAssetTransRow_<?php echo $cntr; ?>">
                                            <td class="lovtd"><span><?php echo ($cntr); ?></span></td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View/Edit Asset Transaction" onclick="getOneAccbAssetTransForm(<?php echo $trsctnLineID; ?>,<?php echo $accbSbmtdAssetID; ?>, 'ShowDialog');" style="padding:2px !important;" style="padding:2px !important;">
                                                    <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <td class="lovtd">
                                                <input type="hidden" class="form-control" aria-label="..." id="oneAccbAssetTransRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                                <span style="font-weight:bold;"><?php echo "[" . $trsctnLineType . "]</span><br/><span>" . $trsctnLineDesc; ?></span>
                                            </td>
                                            <td class="lovtd" style="text-align:center;">
                                                <div class="" style="width:100% !important;">
                                                    <label class="btn btn-primary btn-file" onclick="">
                                                        <span class="" id="oneAccbAssetTransRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $entrdCurNm; ?></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="lovtd" style="text-align:right;">
                                                <span style="font-weight:bold;color:blue;"><?php echo number_format($entrdAmnt, 2); ?></span>
                                            </td>
                                            <td class="lovtd">
                                                <span><?php echo $trnsIncrsDcrs1; ?></span>
                                            </td>
                                            <td class="lovtd">
                                                <input type="hidden" class="form-control" aria-label="..." id="oneAccbAssetTransRow<?php echo $cntr; ?>_AccountID1" value="<?php echo $trsctnAcntID1; ?>" style="width:100% !important;">
                                                <span><?php echo $trsctnAcntNm1; ?></span>
                                            </td>
                                            <td class="lovtd">
                                                <span><?php echo $trnsIncrsDcrs2; ?></span>
                                            </td>
                                            <td class="lovtd">
                                                <input type="hidden" class="form-control" aria-label="..." id="oneAccbAssetTransRow<?php echo $cntr; ?>_AccountID2" value="<?php echo $trsctnAcntID2; ?>" style="width:100% !important;">
                                                <span><?php echo $trsctnAcntNm2; ?></span>
                                            </td>
                                            <td class="lovtd" style="">
                                                <span><?php echo $trsctnDate; ?></span>
                                            </td>
                                            <td class="lovtd">
                                                <div class="input-group" style="width:100% !important;">
                                                    <input class="form-control" id="oneAccbAssetTransRow<?php echo $cntr; ?>_GlBatchNm" style="font-size: 13px !important;font-weight: bold !important;width:100% !important;" placeholder="" type="text" value="<?php echo $trsctnGlBatchNm; ?>" readonly="true" />
                                                    <input type="hidden" class="form-control" aria-label="..." id="oneAccbAssetTransRow<?php echo $cntr; ?>_GlBatchID" value="<?php echo $trnstnGlBatchID; ?>" style="width:100% !important;">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getOneJrnlBatchForm(<?php echo $trnstnGlBatchID; ?>, 1, 'ShowDialog',<?php echo $accbSbmtdAssetID; ?>, 'Asset Transaction');">
                                                        <img src="cmn_images/openfileicon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbAssetTrans('oneAccbAssetTransRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction">
                                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <?php if ($canVwRcHstry === true) {
                                            ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                            echo urlencode(encrypt1(($trsctnLineID . "|accb.accb_fa_asset_trns|asset_trns_id"),
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
                </fieldset>
            <?php
            } else if ($vwtyp == 201) {
                //Create/Edit Asset Transaction Form
                $sbmtdAssetID = isset($_POST['sbmtdAssetID']) ? (float) cleanInputData($_POST['sbmtdAssetID']) : -1;
                if ($sbmtdAssetID <= 0 || !($canAdd || $canEdt)) {
                    restricted();
                    exit();
                }
                $sbmtdAssetTransID = isset($_POST['sbmtdAssetTransID']) ? (float) cleanInputData($_POST['sbmtdAssetTransID']) : -1;
                $astTrnsType = "";
                $astTrnsDesc = "";
                $astTrnsAmount = 0;
                $astTrnsCurID = $fnccurid;
                $astTrnsCurNm = $fnccurnm;
                $astTrnsFuncCur = $fnccurnm;
                $astTrnsIncrsDcrs1 = "Increase";
                $astTrnsAccountNm1 = "";
                $astTrnsAccountID1 = -1;
                $astTrnsIncrsDcrs2 = "Decrease";
                $astTrnsAccountNm2 = "";
                $astTrnsAccountID2 = -1;
                $astTrnsGlBatchNm = "";
                $astTrnsGlBatchID = -1;
                $astTrnsDate = $gnrlTrnsDteDMYHMS;

                $astTrnsFuncCurRate = 1.000;
                $astTrnsFuncCurAmnt = 1.000;
                $mkReadOnly = "";
                if ($sbmtdAssetTransID > 0) {
                    $result = get_OneAssetTrns($sbmtdAssetTransID);
                    while ($row = loc_db_fetch_array($result)) {
                        $sbmtdAssetTransID = (float) $row[0];
                        $astTrnsType = $row[1];
                        $astTrnsDesc = $row[2];
                        $astTrnsAmount = $row[3];
                        $astTrnsCurID = (float) $row[4];
                        $astTrnsCurNm = $row[5];

                        $astTrnsIncrsDcrs1 = $row[6];
                        $astTrnsAccountID1 = $row[7];
                        $astTrnsAccountNm1 = $row[8];

                        $astTrnsIncrsDcrs2 = $row[9];
                        $astTrnsAccountID2 = $row[10];
                        $astTrnsAccountNm2 = $row[11];

                        $astTrnsDate = $row[15];

                        $astTrnsGlBatchNm = $row[13];
                        $astTrnsGlBatchID = (float) $row[12];
                        $astTrnsGlBatchIsPstd = $row[14];
                        $astTrnsFuncCur = $row[17];
                        $astTrnsFuncCurRate = (float) $row[20];
                        $astTrnsFuncCurAmnt = (float) $row[22];
                    }
                }
            ?>
                <form class="form-horizontal" id='astTrnsDetForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row">
                        <div class="row" style="padding: 0px 15px 0px 15px !important;">
                            <div class="col-md-6" style="padding: 0px 0px 0px 0px !important;">
                                <fieldset class="basic_person_fs2" style="min-height:201px !important;padding: 5px 2px 5px 5px !important;margin-left:0px !important;">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                <label for="astTrnsType" class="control-label">Transaction Type:</label>
                                            </div>
                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                <input type="hidden" name="sbmtdAssetID" id="sbmtdAssetID" class="form-control" value="<?php echo $sbmtdAssetID; ?>">
                                                <input type="hidden" name="sbmtdAssetTransID" id="sbmtdAssetTransID" class="form-control" value="<?php echo $sbmtdAssetTransID; ?>">
                                                <?php if ($canEdt === true) { ?>
                                                    <select class="form-control" id="astTrnsType" onchange="autoSlctAsstAcnt();">
                                                        <?php
                                                        $valslctdArry = array("", "", "", "", "", "");
                                                        $valuesArrys = array(
                                                            "1Initial Value", "2Depreciate Asset", "3Appreciate Asset",
                                                            "4Retire Asset", "5Sale of Asset", "6Maintenance of Asset"
                                                        );

                                                        for ($z = 0; $z < count($valuesArrys); $z++) {
                                                            if ($astTrnsType == $valuesArrys[$z]) {
                                                                $valslctdArry[$z] = "selected";
                                                            }
                                                        ?>
                                                            <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                <?php } else { ?>
                                                    <span><?php echo $astTrnsType; ?></span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                <label style="margin-bottom:0px !important;">Transaction Date:</label>
                                            </div>
                                            <div class="col-md-8 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding: 0px 0px 0px 0px !important;">
                                                <input class="form-control" size="16" type="text" id="astTrnsDate" name="astTrnsDate" value="<?php echo $astTrnsDate; ?>" placeholder="Transaction Date" readonly="true">
                                                <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                <label for="astTrnsDesc" class="control-label">Description:</label>
                                            </div>
                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                <?php if ($canEdt === true) { ?>
                                                    <textarea rows="5" name="astTrnsDesc" id="astTrnsDesc" class="form-control rqrdFld"><?php echo $astTrnsDesc; ?></textarea>
                                                <?php } else { ?>
                                                    <span><?php echo $astTrnsDesc; ?></span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                <label style="margin-bottom:0px !important;">Amount:</label>
                                            </div>
                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                <div class="input-group">
                                                    <label class="btn btn-primary btn-file input-group-addon active" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $astTrnsCurNm; ?>', 'astTrnsCurNm', '', 'clear', 0, '', function () {
                                                                                $('#astTrnsCurNm1').html($('#astTrnsCurNm').val());
                                                                            });">
                                                        <span class="" style="font-size: 20px !important;" id="astTrnsCurNm1"><?php echo $astTrnsCurNm; ?></span>
                                                    </label>
                                                    <input type="hidden" id="astTrnsCurNm" value="<?php echo $astTrnsCurNm; ?>">
                                                    <input class="form-control rqrdFld" type="text" id="astTrnsAmount" value="<?php
                                                                                                                                echo number_format($astTrnsAmount, 2);
                                                                                                                                ?>" style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('astTrnsAmount');" <?php echo $mkReadOnly; ?> />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-6" style="padding: 0px 0px 0px 0px !important;">
                                <fieldset class="basic_person_fs2" style="min-height:201px !important;padding: 5px 2px 5px 5px !important;margin-left:2px !important;">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label for="astTrnsIncrsDcrs1" class="control-label col-md-4" style="padding: 0px 0px 0px 0px !important;">Incrs./ Dcrs.:</label>
                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                <select data-placeholder="Select..." class="form-control chosen-select" id="astTrnsIncrsDcrs1" style="width:100% !important;">
                                                    <?php
                                                    $valslctdArry = array("", "");
                                                    $srchInsArrys = array("Increase", "Decrease");
                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                        if ($astTrnsIncrsDcrs1 == $srchInsArrys[$z]) {
                                                            $valslctdArry[$z] = "selected";
                                                        }
                                                    ?>
                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                <label for="astTrnsAccountNm1" class="control-label">Costing Account:</label>
                                            </div>
                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                <?php if ($canEdt === true) { ?>
                                                    <div class="input-group">
                                                        <input type="text" name="astTrnsAccountNm1" id="astTrnsAccountNm1" class="form-control" value="<?php echo $astTrnsAccountNm1; ?>" readonly="true">
                                                        <input type="hidden" name="astTrnsAccountID1" id="astTrnsAccountID1" class="form-control" value="<?php echo $astTrnsAccountID1; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'astTrnsAccountID1', 'astTrnsAccountNm1', 'clear', 0, '', function () {
                                                                                        var aa112 = 1;
                                                                                    });">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                <?php } else { ?>
                                                    <span><?php echo $astTrnsAccountNm1; ?></span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label for="astTrnsIncrsDcrs2" class="control-label col-md-4" style="padding: 0px 0px 0px 0px !important;">Incrs./ Dcrs.:</label>
                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                <select data-placeholder="Select..." class="form-control chosen-select" id="astTrnsIncrsDcrs2" style="width:100% !important;">
                                                    <?php
                                                    $valslctdArry = array("", "");
                                                    $srchInsArrys = array("Increase", "Decrease");
                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                        if ($astTrnsIncrsDcrs2 == $srchInsArrys[$z]) {
                                                            $valslctdArry[$z] = "selected";
                                                        }
                                                    ?>
                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                <label for="astTrnsAccountNm2" class="control-label">Balancing Account:</label>
                                            </div>
                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                <?php if ($canEdt === true) { ?>
                                                    <div class="input-group">
                                                        <input type="text" name="astTrnsAccountNm2" id="astTrnsAccountNm2" class="form-control" value="<?php echo $astTrnsAccountNm2; ?>" readonly="true">
                                                        <input type="hidden" name="astTrnsAccountID2" id="astTrnsAccountID2" class="form-control" value="<?php echo $astTrnsAccountID2; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'astTrnsAccountID2', 'astTrnsAccountNm2', 'clear', 0, '', function () {
                                                                                        var aa112 = 1;
                                                                                    });">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                <?php } else { ?>
                                                    <span><?php echo $astTrnsAccountNm2; ?></span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                <label style="margin-bottom:0px !important;">Func. Curr.:</label>
                                            </div>
                                            <div class="col-md-4" style="padding: 0px 2px 0px 0px !important;">
                                                <div class="input-group">
                                                    <label class="btn btn-default btn-file input-group-addon active" onclick="">
                                                        <span class="" style="" id="astTrnsFuncCur"><?php echo $astTrnsFuncCur; ?></span>
                                                    </label>
                                                    <input class="form-control rqrdFld" type="text" id="astTrnsFuncCurRate" data-toggle="tooltip" title="Rate" value="<?php
                                                                                                                                                                        echo number_format($astTrnsFuncCurRate, 4);
                                                                                                                                                                        ?>" style="width:100%;" onchange="fmtAsNumber('astTrnsFuncCurRate');" <?php echo $mkReadOnly; ?> />
                                                </div>
                                            </div>
                                            <div class="col-md-4" style="padding: 0px 0px 0px 2px !important;">
                                                <input class="form-control" type="text" data-toggle="tooltip" title="Amount" id="astTrnsFuncCurAmnt" value="<?php echo $astTrnsFuncCurAmnt; ?>" style="width:100%;" readonly="true" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label for="astTrnsGlBatchNm" class="control-label col-md-4" style="padding: 0px 0px 0px 0px !important;">GL Batch Name:</label>
                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                <div class="input-group">
                                                    <input class="form-control" id="astTrnsGlBatchNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" placeholder="" value="<?php echo $astTrnsGlBatchNm; ?>" readonly="true" />
                                                    <input type="hidden" id="astTrnsGlBatchID" value="<?php echo $astTrnsGlBatchID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getOneJrnlBatchForm(<?php echo $astTrnsGlBatchID; ?>, 1, 'ShowDialog',<?php echo $sbmtdAssetTransID; ?>, 'None');">
                                                        <img src="cmn_images/openfileicon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Open
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row" style="padding:1px 15px 1px 15px !important;">
                            <hr style="margin:1px 0px 1px 0px;">
                        </div>
                        <div class="row" style="float:right;padding-right: 15px;margin-top: 2px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <?php if ($canEdt === true && $astTrnsGlBatchID <= 0) { ?>
                                <button type="button" class="btn btn-primary" onclick="saveAccbAssetTransForm(0);">Save Changes</button>
                            <?php } ?>
                            <?php if ($astTrnsGlBatchID <= 0 && $sbmtdAssetTransID > 0) { ?>
                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="saveAccbAssetTransForm(5);" data-toggle="tooltip" data-placement="bottom" title="Create Accounting">
                                    <img src="cmn_images/mi_scare_report.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Create Accounting
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            <?php
            } else if ($vwtyp == 202) {
                header("content-type:application/json");
                //Get Asset Asset Account and Name
                //Get Asset Depreciation/Appreciation Account and Name
                //Get Asset Expense/Revenue Account and Name
                /*
                1Initial Value = Increase Asset Acnt (Decrease Bank/Cash or Increase Payable)
                2Depreciate Asset = Increase Asset Depreciation Acnt (Increase Depreciation Expense)
                3Appreciate Asset = Increase Asset Appreciation Acnt (Increase Appreciation Revenue)
                4Retire Asset = Decrease Asset Accnt (Increase Expense)
                5Sale of Asset = Decrease Asset Accnt (Increase Revenue)
                6Maintenance of Asset = Increase Asset Acnt (Decrease Bank/Cash or Increase Payable)
                */
                /* Accounts Needed: 
                    1. Maintenance Expense Account 
                    2. Asset Sale Revenue Account
                */
                $astTrnsType = isset($_POST['astTrnsType']) ? cleanInputData($_POST['astTrnsType']) : "";
                $sbmtdAssetID = isset($_POST['sbmtdAssetID']) ? (float) cleanInputData($_POST['sbmtdAssetID']) : -1;
                $result = get_One_AssetAccnts($sbmtdAssetID);

                $arr_content['AssetAcntID'] = -1;
                $arr_content['AssetAcntNm'] = "";
                $arr_content['DprctnAcntID'] = -1;
                $arr_content['DprctnAcntNm'] = "";
                $arr_content['DprctnExpnsAcntID'] = -1;
                $arr_content['DprctnExpnsAcntNm'] = "";
                $arr_content['RvnuAcntID'] = -1;
                $arr_content['RvnuAcntNm'] = "";
                $arr_content['MntncAcntID'] = -1;
                $arr_content['MntncAcntNm'] = "";
                $arr_content['NetBookValue'] = "0.00";
                $arr_content['BlcngAcntID'] = -1;
                $arr_content['BlcngAcntNm'] = "";
                $arr_content['IsDprctnContra'] = "0";

                while ($rowRw = loc_db_fetch_array($result)) {
                    $arr_content['AssetAcntID'] = $rowRw[0];
                    $arr_content['AssetAcntNm'] = $rowRw[1];
                    $arr_content['DprctnAcntID'] = $rowRw[2];
                    $arr_content['DprctnAcntNm'] = $rowRw[3];
                    $arr_content['DprctnExpnsAcntID'] = $rowRw[4];
                    $arr_content['DprctnExpnsAcntNm'] = $rowRw[5];
                    $arr_content['NetBookValue'] = (float) $rowRw[6];
                    $arr_content['RvnuAcntID'] = $rowRw[7];
                    $arr_content['RvnuAcntNm'] = $rowRw[8];
                    $arr_content['MntncAcntID'] = $rowRw[9];
                    $arr_content['MntncAcntNm'] = $rowRw[10];
                    $blcngAcntID = get_DfltCheckAcnt($orgID);
                    $arr_content['BlcngAcntID'] = $blcngAcntID;
                    $arr_content['BlcngAcntNm'] = getAccntNum($blcngAcntID) . "." . getAccntName($blcngAcntID);
                    $arr_content['IsDprctnContra'] = isAccntContra((int)$rowRw[2]);
                }

                $errMsg = "Success";
                $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $errMsg;
                echo json_encode($arr_content);
                exit();
            } else if ($vwtyp == 3) {
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                $accbSbmtdAssetID = isset($_POST['accbSbmtdAssetID']) ? $_POST['accbSbmtdAssetID'] : -1;
                $accbSbmtdAssetNm = isset($_POST['accbSbmtdAssetNm']) ? $_POST['accbSbmtdAssetNm'] : "";
                $pkID = $accbSbmtdAssetID;
                $cntr = 0;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 15;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                $total = get_TtlAssetPMRecs($srchFor, $srchIn, $pkID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_AssetPMRecs($srchFor, $srchIn, $curIdx, $lmtSze, $pkID);
                $cntr = 0;
                $vwtyp = 3;
            ?>
                <fieldset class="basic_person_fs" style="padding-top:2px !important;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-5" style="padding:0px 0px 0px 0px !important;float:left;">
                                <button type="button" class="btn btn-default" style="margin-bottom: 0px;height:30px;" onclick="getAccbAstPmRec('', '#assetDetlsPMRecs', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneAccbAssetPmRecForm(-1,<?php echo $accbSbmtdAssetID; ?>, 'ShowDialog');">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New PM Record
                                </button>
                                <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="" data-toggle="tooltip" title="Export PM Records">
                                    <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Export
                                </button>
                                <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="" data-toggle="tooltip" title="Import PM Records">
                                    <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Import
                                </button>
                            </div>
                            <div class="col-md-5" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="accbAstPmRecSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                        echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                        ?>" onkeyup="enterKeyFuncAccbAstPmRec(event, '', '#assetDetlsPMRecs', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                    <input id="accbAstPmRecPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbAstPmRec('clear', '#assetDetlsPMRecs', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAccbAstPmRec('', '#assetDetlsPMRecs', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="accbAstPmRecSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "", "");
                                        $srchInsArrys = array("Measurement Type/UOM", "PM Action Taken", "Comments/Remarks", "Record Date");

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                        ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="accbAstPmRecDsplySze" style="min-width:70px !important;">
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
                            <div class="col-md-2">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a class="rhopagination" href="javascript:getAccbAstPmRec('previous', '#assetDetlsPMRecs', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAccbAstPmRec('next', '#assetDetlsPMRecs', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding:1px 15px 1px 15px !important;">
                        <hr style="margin:1px 0px 3px 0px;">
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="oneAccbAssetPMRecsTable" cellspacing="0" width="100%" style="width:100%;min-width: 900px !important;">
                                <thead>
                                    <tr>
                                        <th style="max-width:35px;width:35px;">No.</th>
                                        <th style="max-width:35px;width:35px;">...</th>
                                        <th>Date / Measurement Type</th>
                                        <th style="">UOM</th>
                                        <th style="max-width:70px;width:70px;">Starting Figure</th>
                                        <th style="max-width:70px;width:70px;">Ending Figure</th>
                                        <th style="max-width:70px;width:90px;">Net Figure</th>
                                        <th style="max-width:70px;width:90px;">Overtime Figure</th>
                                        <th style="max-width:70px;width:70px;">Figure Left for PM</th>
                                        <th>PM Action Taken / Remarks</th>
                                        <th style="max-width:35px;width:35px;">...</th>
                                        <?php if ($canVwRcHstry === true) { ?>
                                            <th style="max-width:35px;width:35px;">...</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($rowRw = loc_db_fetch_array($result)) {
                                        $trsctnLineID = (float) $rowRw[0];
                                        $trsctnLineType = $rowRw[1];
                                        $trsctnLineUOM = $rowRw[2];
                                        $trsctnLineDte = $rowRw[3];
                                        $trsctnLineStrtFig = (float) $rowRw[4];
                                        $trsctnLineEndFig = (float) $rowRw[5];
                                        $trsctnLineIsPMDone = $rowRw[6];
                                        $trsctnLinePMAction = $rowRw[7];
                                        $trsctnLineRemark = $rowRw[8];
                                        $trsctnLineNetFig = $trsctnLineEndFig - $trsctnLineStrtFig;
                                        $mxDailyFig = getMxAllwdDailyFig($pkID, $trsctnLineType, $trsctnLineUOM);
                                        $cumFigForPM = getCumFigForPM($pkID, $trsctnLineType, $trsctnLineUOM);
                                        $ttlPrevPMNetFigs = getSumPrevPMNetFigs($pkID, $trsctnLineType, $trsctnLineUOM, $trsctnLineDte);
                                        $cntr += 1;
                                    ?>
                                        <tr id="oneAccbAssetPmRecRow_<?php echo $cntr; ?>">
                                            <td class="lovtd"><span><?php echo ($cntr); ?></span></td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View/Edit Asset PM Record" onclick="getOneAccbAssetPmRecForm(<?php echo $trsctnLineID; ?>,<?php echo $accbSbmtdAssetID; ?>, 'ShowDialog');" style="padding:2px !important;" style="padding:2px !important;">
                                                    <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <td class="lovtd">
                                                <input type="hidden" class="form-control" aria-label="..." id="oneAccbAssetPmRecRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                                <span style="font-weight:bold;"><?php echo "[" . $trsctnLineDte . "]</span><br/><span>" . $trsctnLineType; ?></span>
                                            </td>
                                            <td class="lovtd" style="text-align:center;">
                                                <div class="" style="width:100% !important;">
                                                    <label class="btn btn-primary btn-file" onclick="">
                                                        <span class="" id="oneAccbAssetPmRecRow<?php echo $cntr; ?>_UOM"><?php echo $trsctnLineUOM; ?></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="lovtd" style="text-align:right;">
                                                <span style="font-weight:bold;color:blue;"><?php echo $trsctnLineStrtFig; ?></span>
                                            </td>
                                            <td class="lovtd" style="text-align:right;">
                                                <span style="font-weight:bold;color:blue;"><?php echo $trsctnLineEndFig; ?></span>
                                            </td>
                                            <td class="lovtd" style="text-align:right;">
                                                <span style="font-weight:bold;color:blue;"><?php echo $trsctnLineNetFig; ?></span>
                                            </td>
                                            <td class="lovtd" style="text-align:right;">
                                                <span style="font-weight:bold;color:blue;"><?php echo $trsctnLineNetFig - $mxDailyFig; ?></span>
                                            </td>
                                            <td class="lovtd" style="text-align:right;">
                                                <span style="font-weight:bold;color:blue;"><?php echo $cumFigForPM - $ttlPrevPMNetFigs; ?></span>
                                            </td>
                                            <td class="lovtd">
                                                <span style="font-weight:bold;"><?php echo "[" . $trsctnLinePMAction . "]</span><br/><span>" . $trsctnLineRemark; ?></span>
                                            </td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAccbAssetPmRec('oneAccbAssetPmRecRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete PM Record">
                                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <?php
                                            if ($canVwRcHstry === true) {
                                            ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                            echo urlencode(encrypt1(($trsctnLineID . "|accb.accb_fa_assets_pm_recs|asset_pm_rec_id"),
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
                </fieldset>

            <?php
            } else if ($vwtyp == 301) {
                //Create/Edit Asset PM Form
                $sbmtdAssetID = isset($_POST['sbmtdAssetID']) ? (float) cleanInputData($_POST['sbmtdAssetID']) : -1;
                if ($sbmtdAssetID <= 0 || !($canAdd || $canEdt)) {
                    restricted();
                    exit();
                }
                $sbmtdAssetPmRecID = isset($_POST['sbmtdAssetPmRecID']) ? (float) cleanInputData($_POST['sbmtdAssetPmRecID']) : -1;

                $astPmRecType = "";
                $astPmRecUOMNm = "UOM";
                $astPmRecDate = $gnrlTrnsDteDMYHMS;
                $astPmRecStrtFig = 0.00;
                $astPmRecEndFig = 0.00;
                $astPmRecIsPMDone = "0";
                $astPmRecPMAction = "None";
                $astPmRecPMDesc = "";

                $mkReadOnly = "";
                if ($sbmtdAssetPmRecID > 0) {
                    $result = get_OneAssetTrns($sbmtdAssetPmRecID);
                    while ($row = loc_db_fetch_array($result)) {
                        $sbmtdAssetPmRecID = (float) $row[0];
                        $astPmRecType = $row[1];
                        $astPmRecUOMNm = $row[2];
                        $astPmRecDate = $row[3];
                        $astPmRecStrtFig = (float) $row[4];
                        $astPmRecEndFig = (float) $row[5];
                        $astPmRecIsPMDone = $row[6];
                        $astPmRecPMAction = $row[7];
                        $astPmRecPMDesc = $row[8];
                    }
                }
            ?>
                <form class="form-horizontal" id='astTrnsDetForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row">
                        <div class="row" style="padding: 0px 15px 0px 15px !important;">
                            <div class="col-md-6" style="padding: 0px 0px 0px 0px !important;">
                                <fieldset class="basic_person_fs2" style="min-height:160px !important;padding: 5px 2px 5px 5px !important;margin-left:0px !important;">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                <label style="margin-bottom:0px !important;">PM Record Date:</label>
                                            </div>
                                            <div class="col-md-8 input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding: 0px 0px 0px 0px !important;">
                                                <input class="form-control" size="16" type="text" id="astPmRecDate" name="astPmRecDate" value="<?php echo $astPmRecDate; ?>" placeholder="Record Date" readonly="true">
                                                <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                <label for="astPmRecType" class="control-label">Measurement Type:</label>
                                            </div>
                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                <input type="hidden" name="sbmtdAssetID" id="sbmtdAssetID" class="form-control" value="<?php echo $sbmtdAssetID; ?>">
                                                <input type="hidden" name="sbmtdAssetPmRecID" id="sbmtdAssetPmRecID" class="form-control" value="<?php echo $sbmtdAssetPmRecID; ?>">
                                                <?php if ($canEdt === true) { ?>
                                                    <select class="form-control" id="astPmRecType">
                                                        <?php
                                                        $brghtStr = "";
                                                        $isDynmyc = FALSE;
                                                        $titleRslt = getLovValues(
                                                            "%",
                                                            "Both",
                                                            0,
                                                            100,
                                                            $brghtStr,
                                                            getLovID("PM Measurement Types"),
                                                            $isDynmyc,
                                                            -1,
                                                            "",
                                                            ""
                                                        );
                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                            $selectedTxt = "";
                                                            if ($titleRow[0] == $astPmRecType) {
                                                                $selectedTxt = "selected";
                                                            }
                                                        ?>
                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                <?php } else { ?>
                                                    <span><?php echo $astPmRecType; ?></span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                <label style="margin-bottom:0px !important;">Unit of Measure:</label>
                                            </div>
                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                <label style="width:100% !important;" class="btn btn-primary btn-file active" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'PM Measurement Units', '', '', '', 'radio', true, '<?php echo $astPmRecUOMNm; ?>', 'astPmRecUOMNm', 'astPmRecUOMDesc', 'clear', 0, '', function () {
                                                                            var valNm = $('#astPmRecUOMNm').val();
                                                                            var valDesc = $('#astPmRecUOMDesc').val();
                                                                            var nwSTr = (valNm + ' - ' + valDesc).replace(' - ' + valNm, '');
                                                                            $('#astPmRecUOMNm1').html(nwSTr);
                                                                        });">
                                                    <span class="" style="font-size: 15px !important;font-weight: bold;" id="astPmRecUOMNm1"><?php echo $astPmRecUOMNm; ?></span>
                                                </label>
                                                <input type="hidden" id="astPmRecUOMNm" value="<?php echo $astPmRecUOMNm; ?>">
                                                <input type="hidden" id="astPmRecUOMDesc" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                <label style="margin-bottom:0px !important;">Starting Figure:</label>
                                            </div>
                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                <input class="form-control rqrdFld" type="text" id="astPmRecStrtFig" value="<?php
                                                                                                                            echo number_format($astPmRecStrtFig, 2);
                                                                                                                            ?>" style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('astPmRecStrtFig');" <?php echo $mkReadOnly; ?> />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                <label style="margin-bottom:0px !important;">Ending Figure:</label>
                                            </div>
                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                <input class="form-control rqrdFld" type="text" id="astPmRecEndFig" value="<?php
                                                                                                                            echo number_format($astPmRecEndFig, 2);
                                                                                                                            ?>" style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('astPmRecEndFig');" <?php echo $mkReadOnly; ?> />
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-6" style="padding: 0px 0px 0px 0px !important;">
                                <fieldset class="basic_person_fs2" style="min-height:160px !important;padding: 5px 2px 5px 5px !important;margin-left:2px !important;">
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;margin-bottom: 5px !important;">
                                        <label for="astPmRecIsPMDone" class="control-label col-lg-6">Has PM been Carried Out?:</label>
                                        <div class="col-lg-6">
                                            <?php
                                            $chkdYes = "";
                                            $chkdNo = "checked=\"\"";
                                            if ($astPmRecIsPMDone == "1") {
                                                $chkdNo = "";
                                                $chkdYes = "checked=\"\"";
                                            }
                                            ?>
                                            <label class="radio-inline"><input type="radio" name="astPmRecIsPMDone" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                            <label class="radio-inline"><input type="radio" name="astPmRecIsPMDone" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label for="astPmRecPMAction" class="control-label col-md-4" style="padding: 0px 0px 0px 0px !important;">PM Action Taken:</label>
                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                <select data-placeholder="Select..." class="form-control chosen-select" id="astPmRecPMAction" style="width:100% !important;">
                                                    <?php
                                                    $brghtStr = "";
                                                    $isDynmyc = FALSE;
                                                    $titleRslt = getLovValues(
                                                        "%",
                                                        "Both",
                                                        0,
                                                        100,
                                                        $brghtStr,
                                                        getLovID("PM Actions Taken"),
                                                        $isDynmyc,
                                                        -1,
                                                        "",
                                                        ""
                                                    );
                                                    while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                        $selectedTxt = "";
                                                        if ($titleRow[0] == $astPmRecPMAction) {
                                                            $selectedTxt = "selected";
                                                        }
                                                    ?>
                                                        <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                <label for="astPmRecPMDesc" class="control-label">Remarks:</label>
                                            </div>
                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                <?php if ($canEdt === true) { ?>
                                                    <textarea rows="5" name="astPmRecPMDesc" id="astPmRecPMDesc" class="form-control rqrdFld"><?php echo $astPmRecPMDesc; ?></textarea>
                                                <?php } else { ?>
                                                    <span><?php echo $astPmRecPMDesc; ?></span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row" style="padding:1px 15px 1px 15px !important;">
                            <hr style="margin:1px 0px 1px 0px;">
                        </div>
                        <div class="row" style="float:right;padding-right: 15px;margin-top: 2px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <?php if ($canEdt === true) { ?>
                                <button type="button" class="btn btn-primary" onclick="saveAccbAssetPmRecForm();">Save Changes</button>
                            <?php } ?>
                        </div>
                        <div class="row" style="padding:1px 15px 1px 15px !important;">
                            <hr style="margin:1px 0px 1px 0px;">
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="padding:0px 15px 0px 15px !important;">
                                <fieldset class="basic_person_fs2" style="min-height:50px !important;padding: 5px 2px 5px 2px !important;margin-left:3px !important;">
                                    <table class="table table-striped table-bordered table-responsive" id="oneAccbAstPmRecExtrInfTable" cellspacing="0" width="100%" style="width:100%;min-width: 200px !important;">
                                        <caption class="basic_person_lg" style="color:black !important;font-weight:bold;">EXTRA INFORMATION VALUES</caption>
                                        <thead>
                                            <tr>
                                                <th style="max-width:30px;width:30px;">No.</th>
                                                <th style="">Extra Info Category</th>
                                                <th>Extra Info Label</th>
                                                <th style="min-width:300px;">Value</th>
                                                <?php
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
                                                getMdlGrpID("Fixed Assets PM Records", $mdlNm),
                                                $sbmtdAssetPmRecID,
                                                "accb.accb_all_other_info_table"
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
                                                <tr id="oneAccbAstPmRecExtrInfRow_<?php echo $cntr; ?>">
                                                    <td class="lovtd"><span><?php echo ($cntr); ?></span></td>
                                                    <td class="lovtd" style="">
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbAstPmRecExtrInfRow<?php echo $cntr; ?>_DfltRowID" value="<?php echo $dfltRowID; ?>" style="width:100% !important;">
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbAstPmRecExtrInfRow<?php echo $cntr; ?>_CombntnID" value="<?php echo $cmbntnID; ?>" style="width:100% !important;">
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbAstPmRecExtrInfRow<?php echo $cntr; ?>_TableID" value="<?php echo $tableID; ?>" style="width:100% !important;">
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbAstPmRecExtrInfRow<?php echo $cntr; ?>_extrInfoCtgry" value="<?php echo $extrInfoCtgry; ?>" style="width:100% !important;">
                                                        <input type="hidden" class="form-control" aria-label="..." id="oneAccbAstPmRecExtrInfRow<?php echo $cntr; ?>_extrInfoLbl" value="<?php echo $extrInfoLbl; ?>" style="width:100% !important;">
                                                        <span><?php echo $extrInfoCtgry; ?></span>
                                                    </td>
                                                    <td class="lovtd" style="">
                                                        <span><?php echo $extrInfoCtgry; ?></span>
                                                    </td>
                                                    <td class="lovtd" style="">
                                                        <input type="text" class="form-control jbDetRfDc" aria-label="..." id="oneAccbAstPmRecExtrInfRow<?php echo $cntr; ?>_Value" name="oneAccbAstPmRecExtrInfRow<?php echo $cntr; ?>_Value" value="<?php echo $extrInfoVal; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneAccbAstPmRecExtrInfRow<?php echo $cntr; ?>_Value', 'oneAccbAstPmRecExtrInfTable', 'jbDetRfDc');">
                                                    </td>
                                                    <?php
                                                    if ($canVwRcHstry === true) {
                                                    ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                    echo urlencode(encrypt1(($dfltRowID . "|accb.accb_all_other_info_table|dflt_row_id"),
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
                    </div>
                </form>
<?php
            }
        }
    }
}
?>