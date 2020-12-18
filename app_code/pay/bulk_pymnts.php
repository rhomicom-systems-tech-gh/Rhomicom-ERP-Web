<?php
$canAdd = test_prmssns($dfltPrvldgs[17], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[18], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[19], $mdlNm);
$vwSelf = !test_prmssns($dfltPrvldgs[38], $mdlNm);
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
                /* Delete Mass Pay Run */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteBulkPayRun($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Mass Pay Run Attached Val */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteMsPayAtchdVal($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Mass Pay Run
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $afftctd = 0;
                $payMassPyID = isset($_POST['payMassPyID']) ? (int) cleanInputData($_POST['payMassPyID']) : -1;
                $payactyp = isset($_POST['payactyp']) ? (int) cleanInputData($_POST['payactyp']) : -1;
                $payMassPyName = isset($_POST['payMassPyName']) ? cleanInputData($_POST['payMassPyName']) : "";
                $payMassPyDesc = isset($_POST['payMassPyDesc']) ? cleanInputData($_POST['payMassPyDesc']) : "";
                $payMassPyPrsnStID = isset($_POST['payMassPyPrsnStID']) ? (float) cleanInputData($_POST['payMassPyPrsnStID']) : -1;
                $payMassPyItmSetID = isset($_POST['payMassPyItmSetID']) ? (float) cleanInputData($_POST['payMassPyItmSetID']) : -1;
                $payMassPyDate = isset($_POST['payMassPyDate']) ? cleanInputData($_POST['payMassPyDate']) : "";
                $payMassPyGlDate = isset($_POST['payMassPyGlDate']) ? cleanInputData($_POST['payMassPyGlDate']) : "";

                $payMassPyGrpType = isset($_POST['payMassPyGrpType']) ? cleanInputData($_POST['payMassPyGrpType']) : "Everyone";
                $payMassPyGroupName = isset($_POST['payMassPyGroupName']) ? cleanInputData($_POST['payMassPyGroupName']) : "";
                $payMassPyGroupID = isset($_POST['payMassPyGroupID']) ? cleanInputData($_POST['payMassPyGroupID']) : "-1";
                $payMassPyWorkPlaceName = isset($_POST['payMassPyWorkPlaceName']) ? cleanInputData($_POST['payMassPyWorkPlaceName']) : "";
                $payMassPyWorkPlaceID = isset($_POST['payMassPyWorkPlaceID']) ? (float) cleanInputData($_POST['payMassPyWorkPlaceID']) : -1;
                $payMassPyWorkPlaceSiteID = isset($_POST['payMassPyWorkPlaceSiteID']) ? (int) cleanInputData($_POST['payMassPyWorkPlaceSiteID']) : -1;
                $payMassPyAmntGvn = isset($_POST['payMassPyAmntGvn']) ? (float) cleanInputData($_POST['payMassPyAmntGvn']) : 0.00;
                $payMassPyChqNumber = isset($_POST['payMassPyChqNumber']) ? cleanInputData($_POST['payMassPyChqNumber']) : "";
                $payMassPySignCode = isset($_POST['payMassPySignCode']) ? cleanInputData($_POST['payMassPySignCode']) : "";
                $slctdAttchdVals = isset($_POST['slctdAttchdVals']) ? cleanInputData($_POST['slctdAttchdVals']) : "";
                $payMassPyIsQckPay = isset($_POST['payMassPyIsQckPay']) ? (cleanInputData($_POST['payMassPyIsQckPay']) == "YES" ? TRUE : FALSE) : FALSE;
                $payMassPyAutoAsgng = isset($_POST['payMassPyAutoAsgng']) ? (cleanInputData($_POST['payMassPyAutoAsgng']) == "YES" ? TRUE : FALSE) : FALSE;
                $payMassPyAplyAdvnc = isset($_POST['payMassPyAplyAdvnc']) ? (cleanInputData($_POST['payMassPyAplyAdvnc']) == "YES" ? TRUE : FALSE) : FALSE;
                $payMassPyKeepExcss = isset($_POST['payMassPyKeepExcss']) ? (cleanInputData($_POST['payMassPyKeepExcss']) == "YES" ? TRUE : FALSE) : FALSE;
                $exitErrMsg = "";
                $hsbeenRun = getGnrlRecNm("pay.pay_mass_pay_run_hdr", "mass_pay_id", "run_status", $payMassPyID);
                if ($payMassPyName == "") {
                    $exitErrMsg .= "Please enter Bulk Pay Run Name!<br/>";
                }
                if ($payMassPyDesc == "") {
                    $exitErrMsg .= "Please enter Bulk Pay Run Description!<br/>";
                }
                if ($payMassPyDate == "") {
                    $exitErrMsg .= "Please enter Bulk Pay Run Date!<br/>";
                }
                if ($payMassPyGlDate == "") {
                    $exitErrMsg .= "Please enter Bulk Pay Run GL Date!<br/>";
                }
                if ($payMassPyItmSetID <= 0) {
                    $exitErrMsg .= "Please enter Item Set!<br/>";
                }
                if ($payMassPyIsQckPay == false) {
                    if ($payMassPyPrsnStID <= 0) {
                        $exitErrMsg .= "Person Set CANNOT be EMPTY if Item Type is Tax!";
                    }
                } else {
                    if ($payMassPyGrpType == "") {
                        $exitErrMsg .= "Group Type cannot be empty for a quick Pay!<br/>";
                    }
                    if ($payMassPyGroupID <= 0 || $payMassPyGroupName == "") {
                        $exitErrMsg .= "Group Name CANNOT be EMPTY for a quick Pay!";
                    }
                    /* if ($payMassPyAmntGvn != 0) {
                      $exitErrMsg .= "Amount given CANNOT be EMPTY for a quick Pay!";
                      } */
                }
                if ($canEdt === FALSE || $hsbeenRun == "1") {
                    $exitErrMsg .= "Cannot Edit this Pay Run!<br/>";
                }
                if (trim($exitErrMsg) !== "") {
                    $arr_content['percent'] = 100;
                    $arr_content['payMassPyID'] = $payMassPyID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    echo json_encode($arr_content);
                    exit();
                }
                $oldID = getMsPyID($payMassPyName, $orgID);
                if (($oldID <= 0 || $oldID == $payMassPyID)) {
                    if ($payMassPyID <= 0) {
                        createMsPy(
                            $orgID,
                            $payMassPyName,
                            $payMassPyDesc,
                            $payMassPyDate,
                            $payMassPyPrsnStID,
                            $payMassPyItmSetID,
                            $payMassPyGlDate,
                            $payMassPyGrpType,
                            $payMassPyGroupID,
                            $payMassPyWorkPlaceID,
                            $payMassPyWorkPlaceSiteID,
                            $payMassPyAmntGvn,
                            $fnccurid,
                            $payMassPyChqNumber,
                            $payMassPySignCode,
                            $payMassPyIsQckPay,
                            $payMassPyAutoAsgng,
                            $payMassPyAplyAdvnc,
                            $payMassPyKeepExcss
                        );
                        $payMassPyID = getMsPyID($payMassPyName, $orgID);
                    } else {
                        updateMsPy(
                            $payMassPyID,
                            $payMassPyName,
                            $payMassPyDesc,
                            $payMassPyDate,
                            $payMassPyPrsnStID,
                            $payMassPyItmSetID,
                            $payMassPyGlDate,
                            $payMassPyGrpType,
                            $payMassPyGroupID,
                            $payMassPyWorkPlaceID,
                            $payMassPyWorkPlaceSiteID,
                            $payMassPyAmntGvn,
                            $fnccurid,
                            $payMassPyChqNumber,
                            $payMassPySignCode,
                            $payMassPyIsQckPay,
                            $payMassPyAutoAsgng,
                            $payMassPyAplyAdvnc,
                            $payMassPyKeepExcss
                        );
                    }
                    $afftctd = 0;
                    if (trim($slctdAttchdVals, "|~") != "" && $payMassPyID > 0) {
                        $variousRows = explode("|", trim($slctdAttchdVals, "|"));
                        for ($y = 0; $y < count($variousRows); $y++) {
                            $crntRow = explode("~", $variousRows[$y]);
                            if (count($crntRow) == 6) {
                                $ln_TrnsLnID = (float) (cleanInputData1($crntRow[0]));
                                $ln_PrsnID = (float) cleanInputData1($crntRow[1]);
                                $ln_ItemID = (int) cleanInputData1($crntRow[2]);
                                $ln_ItemValID = (int) cleanInputData1($crntRow[3]);
                                $ln_ValToUse = (float) cleanInputData1($crntRow[4]);
                                $ln_CanEdt = cleanInputData1($crntRow[5]);
                                $ln_DateEarnd = "";
                                $errMsg = "";
                                if ($ln_PrsnID <= 0 || $ln_ItemID <= 0 || $ln_ItemValID <= 0) {
                                    $errMsg = "Row " . ($y + 1) . ":- Person and Pay Item/Value must be valid and existing!<br/>";
                                }
                                if ($errMsg === "") {
                                    if ($ln_TrnsLnID <= 0 && $ln_CanEdt == "1") {
                                        $afftctd += createMsPayAtchdVal(
                                            $payMassPyID,
                                            $ln_PrsnID,
                                            $ln_ItemID,
                                            $ln_ValToUse,
                                            $ln_ItemValID,
                                            $ln_DateEarnd
                                        );
                                    } else if ($ln_TrnsLnID > 0 && $ln_CanEdt == "1") {
                                        $afftctd += updtMsPayAtchdVal($ln_TrnsLnID, $ln_ValToUse);
                                    }
                                } else {
                                    $exitErrMsg .= $errMsg;
                                }
                            }
                        }
                    }
                    if ($exitErrMsg != "") {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Pay Run Successfully Saved!"
                            . "<br/>" . $afftctd . " Value(s) Saved Successfully!"
                            . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    } else {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Pay Run Successfully Saved!" . "<br/>" . $afftctd . " Value(s) Saved Successfully!";
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['payMassPyID'] = $payMassPyID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    session_write_close();
                    if ($payactyp < 5) {
                        //Call Function to autocreate quick pay values using expected results
                        if ($payMassPyIsQckPay == true && $payMassPyID > 0) {
                            $errMsg1 = generateAttachdVals($payMassPyID);
                            if (strpos($errMsg1, "ERROR") !== FALSE) {
                                $exitErrMsg .= "<br/>" . $errMsg1;
                            }
                        } else if ($payMassPyID > 0) {
                            $errMsg1 = generateAttachdVals($payMassPyID);
                            if (strpos($errMsg1, "ERROR") !== FALSE) {
                                $exitErrMsg .= "<br/>" . $errMsg1;
                            }
                        }
                    }
                    exit();
                } else {
                    $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Either the New Bulk Pay Name is in Use <br/>or Data Supplied is Incomplete!</span>";
                    $arr_content['percent'] = 100;
                    $arr_content['payMassPyID'] = $payMassPyID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 2) {
                //Run Mass Pay Run
            } else if ($actyp == 901) {
                //Import Attached Values
                $payMassPyID = isset($_POST['payMassPyID']) ? (int) cleanInputData($_POST['payMassPyID']) : -1;
                $dataToSend = trim(cleanInputData($_POST['dataToSend']), "|~");
                session_write_close();
                $affctd = 0;
                $errMsg = "";
                if ($dataToSend != "") {
                    $variousRows = explode("|", $dataToSend);
                    $total = count($variousRows);
                    for ($z = 0; $z < $total; $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 5) {
                            $ln_PrsnLocID = ltrim(trim(cleanInputData1($crntRow[0])), '\'');
                            $ln_PrsnNm = trim(cleanInputData1($crntRow[1]));
                            $ln_ItemNm = trim(cleanInputData1($crntRow[2]));
                            $ln_ItemValNm = trim(cleanInputData1($crntRow[3]));
                            $ln_Value = trim(cleanInputData1($crntRow[4]));
                            if ($z == 0) {
                                if (
                                    strtoupper($ln_PrsnLocID) == strtoupper("Person's ID No.**")
                                    && strtoupper($ln_ItemValNm) == strtoupper("Item Value Name**")
                                    && strtoupper($ln_Value) == strtoupper("Value/Amount to Use**")
                                ) {
                                    continue;
                                } else {
                                    $arr_content['percent'] = 100;
                                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> Selected File is Invalid!";
                                    //.strtoupper($number) ."|". strtoupper($processName) ."|". strtoupper($isEnbld1 == "IS ENABLED?");
                                    $arr_content['msgcount'] = $total;
                                    file_put_contents(
                                        $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_PyAttchdValuesimport_progress.rho",
                                        json_encode($arr_content)
                                    );
                                    break;
                                }
                            }
                            $ln_PrsnID = getPersonID($ln_PrsnLocID, $orgID);
                            $ln_ItemID = getItmID($ln_ItemNm, $orgID);
                            $ln_ItemValID = getItmValID($ln_ItemValNm, $ln_ItemID);
                            $ln_ValToUse = (float) $ln_Value;
                            $ln_CanEdt = getGnrlRecNm("org.org_pay_items", "item_id", "allow_value_editing", $ln_ItemID);
                            $ln_DateEarnd = "";
                            $exitErrMsg = "";
                            $errMsg = "";
                            if ($ln_PrsnID <= 0 || $ln_ItemID <= 0 || $ln_ItemValID <= 0) {
                                $errMsg = "Row " . ($z + 1) . ":- Person and Pay Item/Value must be valid and existing!<br/>";
                            }
                            $ln_TrnsLnID = getMsPayAtchdValID($payMassPyID, $ln_PrsnID, $ln_ItemID, $ln_ItemValID);
                            if ($errMsg === "") {
                                if ($ln_TrnsLnID <= 0 && $ln_CanEdt == "1") {
                                    $affctd += createMsPayAtchdVal(
                                        $payMassPyID,
                                        $ln_PrsnID,
                                        $ln_ItemID,
                                        $ln_ValToUse,
                                        $ln_ItemValID,
                                        $ln_DateEarnd
                                    );
                                } else if ($ln_TrnsLnID > 0 && $ln_CanEdt == "1") {
                                    $affctd += updtMsPayAtchdVal($ln_TrnsLnID, $ln_ValToUse);
                                }
                            } else {
                                $exitErrMsg .= $errMsg;
                            }
                        }
                        $percent = round((($z + 1) / $total) * 100, 2);
                        $arr_content['percent'] = $percent;
                        if ($percent >= 100) {
                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> 100% Completed!..." . $affctd . " out of " . $total . " Attached Value(s) processed." . $exitErrMsg;
                            $arr_content['msgcount'] = $total;
                        } else {
                            $arr_content['message'] = "<i class=\"fa fa-spin fa-spinner\"></i> Importing Assets...Please Wait..." . ($z + 1) . " out of " . $total . " Attached Value(s) processed.";
                        }
                        file_put_contents(
                            $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_PyAttchdValuesimport_progress.rho",
                            json_encode($arr_content)
                        );
                    }
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['msgcount'] = "";
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_PyAttchdValuesimport_progress.rho", json_encode($arr_content));
                }
            } else if ($actyp == 902) {
                //Checked Importing Process Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_PyAttchdValuesimport_progress.rho";
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
                //Export Attached Values
                $payMassPyID = isset($_POST['payMassPyID']) ? (int) cleanInputData($_POST['payMassPyID']) : -1;
                $inptNum = isset($_POST['inptNum']) ? (int) cleanInputData($_POST['inptNum']) : 0;
                session_write_close();
                $affctd = 0;
                $errMsg = "Invalid Option!";
                if ($inptNum >= 0) {
                    $hdngs = array(
                        "Person's ID No.**", "Full Name", "Item Name**", "Item Value Name**", "Value/Amount to Use**"
                    );
                    $limit_size = 0;
                    if ($inptNum > 2) {
                        $limit_size = $inptNum;
                    } else if ($inptNum == 2) {
                        $limit_size = 1000000;
                    }
                    $rndm = getRandomNum(10001, 9999999);
                    $dteNm = date('dMY_His');
                    $nwFileNm = $fldrPrfx . "dwnlds/tmp/PyAttchdValuesExprt_" . $dteNm . "_" . $rndm . ".csv";
                    $dwnldUrl = $app_url . "dwnlds/tmp/PyAttchdValuesExprt_" . $dteNm . "_" . $rndm . ".csv";
                    $opndfile = fopen($nwFileNm, "w");
                    fputcsv($opndfile, $hdngs);
                    if ($limit_size <= 0) {
                        $arr_content['percent'] = 100;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!... Accounts Chart Template Exported.</span>";
                        $arr_content['msgcount'] = 0;
                        file_put_contents(
                            $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_PyAttchdValuesexprt_progress.rho",
                            json_encode($arr_content)
                        );

                        fclose($opndfile);
                        exit();
                    }
                    $z = 0;
                    $crntRw = "";
                    $result = get_One_MsPayAtchdVals("%", "Person Name/ID", 0, $limit_size, $payMassPyID);
                    $total = loc_db_num_rows($result);
                    $fieldCntr = loc_db_num_fields($result);
                    while ($row = loc_db_fetch_array($result)) {
                        $crntRw = array(
                            "'" . $row[3], $row[4], $row[6], $row[8], $row[9]
                        );
                        fputcsv($opndfile, $crntRw);
                        //file_put_contents($nwFileNm, $crntRw, FILE_APPEND | LOCK_EX);
                        $percent = round((($z + 1) / $total) * 100, 2);
                        $arr_content['percent'] = $percent;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        if ($percent >= 100) {
                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!..." . ($z +
                                1) . " out of " . $total . " Attached Value(s) exported.</span>";
                            $arr_content['msgcount'] = $total;
                        } else {
                            $arr_content['message'] = "<span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"><br/>Exporting Accounts...Please Wait..." . ($z +
                                1) . " out of " . $total . " Attached Value(s) exported.</span>";
                        }
                        file_put_contents(
                            $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_PyAttchdValuesexprt_progress.rho",
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
                        $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_PyAttchdValuesexprt_progress.rho",
                        json_encode($arr_content)
                    );
                }
            } else if ($actyp == 904) {
                //Checked Exporting Process Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_PyAttchdValuesexprt_progress.rho";
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
                $pkID = isset($_POST['sbmtdMassPayRunID']) ? $_POST['sbmtdMassPayRunID'] : -1;
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">All Payment Runs</span>
				</li>
                               </ul>
                              </div>";

                $total = get_Total_MsPy($srchFor, $srchIn, $orgID, $vwSelf);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_Basic_MsPy($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $vwSelf);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-4";
?>
                <form id='payMassPyForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <?php if ($canAdd === true) { ?>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 0px 0px 0px !important;">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOnePayMassPyForm(-1, 1);;" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title=" New Bulk/Mass Pay Run">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Bulk Run
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <?php if ($canEdt) { ?>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="savePayMassPyForm();" style="width:100% !important;">
                                            <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            Save
                                        </button>
                                    <?php } ?>
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
                                <input class="form-control" id="payMassPySrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                ?>" onkeyup="enterKeyFuncPayMassPy(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="payMassPyPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getPayMassPy('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getPayMassPy('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label>
                            </div>
                        </div>
                        <div class="<?php echo $colClassType3; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="payMassPySrchIn">
                                    <?php
                                    $valslctdArry = array("", "");
                                    $srchInsArrys = array("Mass Pay Run Name", "Mass Pay Run Description");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                    ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>>
                                            <?php echo $srchInsArrys[$z]; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="payMassPyDsplySze" style="min-width:70px !important;">
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
                                        <option value="<?php echo $dsplySzeArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>>
                                            <?php echo $dsplySzeArry[$y]; ?>
                                        </option>
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
                                        <a class="rhopagination" href="javascript:getPayMassPy('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getPayMassPy('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
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
                                <table class="table table-striped table-bordered table-responsive" id="payMassPyTable" cellspacing="0" width="100%" style="width:100% !important;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Pay Run Name</th>
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
                                        ?>
                                            <tr id="payMassPyRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?>
                                                </td>
                                                <td class="lovtd"><?php echo $row[1]; ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="payMassPyRow<?php echo $cntr; ?>_CodeID" value="<?php echo $row[0]; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="payMassPyRow<?php echo $cntr; ?>_CodeNm" value="<?php echo $row[1]; ?>">
                                                </td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delPayMassPy('payMassPyRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Item">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                echo urlencode(encrypt1(($row[0] . "|pay.pay_mass_pay_run_hdr|mass_pay_id"),
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
                                <div class="container-fluid" id="payMassPyDetailInfo">
                                    <?php
                                    $payMassPyID = $pkID;
                                    if ($pkID > 0) {
                                        $reportName = "Pay Run Master Sheet-Web";
                                        $reportTitle = "Pay Run Master Sheet";
                                        $rptID = getRptID($reportName);
                                        $prmID01 = getParamIDUseSQLRep("{:ordrBy}", $rptID);
                                        $prmID02 = getParamIDUseSQLRep("{:orgID}", $rptID);
                                        $prmID03 = getParamIDUseSQLRep("{:pay_run_no}", $rptID);
                                        $paramRepsNVals = $prmID01 . "~2|" . $prmID02 . "~" . $orgID . "|" . $prmID03 . "~" . $pkID . "|-130~" . $reportTitle . "|-190~PDF";
                                        $paramStr = urlencode($paramRepsNVals);

                                        $reportName4 = "Pay Run Bank Crediting CSV File-Web";
                                        $reportTitle4 = "Pay Run Bank Crediting CSV File";
                                        $rptID4 = getRptID($reportName4);
                                        $prmID401 = getParamIDUseSQLRep("{:ordrBy}", $rptID4);
                                        $prmID402 = getParamIDUseSQLRep("{:pay_run_name}", $rptID4);
                                        $paramRepsNVals4 = $prmID401 . "~2|" . $prmID402 . "~" . $pkID . "|-130~" . $reportTitle4 . "|-190~CHARACTER SEPARATED FILE (CSV)";
                                        $paramStr4 = urlencode($paramRepsNVals4);

                                        $reportName5 = "Customized Pay Slip (Sample 1)-Per Run";
                                        $reportTitle5 = "Pay Slip";
                                        $rptID5 = getRptID($reportName5);
                                        $prmID501 = getParamIDUseSQLRep("{:IDNos}", $rptID5);
                                        $prmID502 = getParamIDUseSQLRep("{:documentTitle}", $rptID5);
                                        $prmID503 = getParamIDUseSQLRep("{:orgID}", $rptID5);
                                        $prmID504 = getParamIDUseSQLRep("{:pay_run_id}", $rptID5);
                                        $paramRepsNVals5 = $prmID501 . "~c.local_id_no|" . $prmID502 . "~" . $reportTitle5 . "|" . $prmID503 . "~" . $orgID . "|" . $prmID504 . "~" . $pkID . "|-130~" . $reportTitle5 . "|-190~PDF";
                                        $paramStr5 = urlencode($paramRepsNVals5);

                                        $vPsblValID1 = getEnbldPssblValID("Application Instance SHORT CODE", getLovID("All Other General Setups"));
                                        $vPsblVal1 = getPssblValDesc($vPsblValID1);
                                        $rptID6 = -1;
                                        $paramStr6 = "";
                                        $rptID69 = -1;
                                        $paramStr69 = ""; //
                                        $rptID8 = -1;
                                        $paramStr8 = "";
                                        $rptID9 = -1;
                                        $paramStr9 = "";
                                        if ($vPsblVal1 == "TAKBG_SWLFR_APP_1") {
                                            $reportName69 = "General Welfare Credit Letter";
                                            $reportTitle69 = "General Welfare Credit Letter";
                                            $rptID69 = getRptID($reportName69);
                                            $prmID6029 = getParamIDUseSQLRep("{:documentTitle}", $rptID69);
                                            $prmID6039 = getParamIDUseSQLRep("{:orgID}", $rptID69);
                                            $prmID6049 = getParamIDUseSQLRep("{:pay_run_id}", $rptID69);
                                            $paramRepsNVals69 = $prmID6029 . "~" . $reportTitle69 . "|" . $prmID6039 . "~" . $orgID . "|" . $prmID6049 . "~" . $pkID . "|-130~" . $reportTitle69 . "|-190~PDF";
                                            $paramStr69 = urlencode($paramRepsNVals69);

                                            $reportName6 = "Welfare Loan Credit Letter";
                                            $reportTitle6 = "Welfare Loan Letter";
                                            $rptID6 = getRptID($reportName6);
                                            $prmID602 = getParamIDUseSQLRep("{:documentTitle}", $rptID6);
                                            $prmID603 = getParamIDUseSQLRep("{:orgID}", $rptID6);
                                            $prmID604 = getParamIDUseSQLRep("{:pay_run_id}", $rptID6);
                                            $paramRepsNVals6 = $prmID602 . "~" . $reportTitle6 . "|" . $prmID603 . "~" . $orgID . "|" . $prmID604 . "~" . $pkID . "|-130~" . $reportTitle6 . "|-190~PDF";
                                            $paramStr6 = urlencode($paramRepsNVals6);

                                            $reportName8 = "Semi-Month Welfare Contributions Letter";
                                            $reportTitle8 = "Welfare Contributions Letter";
                                            $rptID8 = getRptID($reportName8);
                                            $prmID802 = getParamIDUseSQLRep("{:documentTitle}", $rptID8);
                                            $prmID803 = getParamIDUseSQLRep("{:orgID}", $rptID8);
                                            $prmID804 = getParamIDUseSQLRep("{:pay_run_id}", $rptID8);
                                            $paramRepsNVals8 = $prmID802 . "~" . $reportTitle8 . "|" . $prmID803 . "~" . $orgID . "|" . $prmID804 . "~" . $pkID . "|-130~" . $reportTitle8 . "|-190~PDF";
                                            $paramStr8 = urlencode($paramRepsNVals8);

                                            $reportName9 = "Welfare Loan Repayment Letter";
                                            $reportTitle9 = "Welfare Loan Repayment Letter";
                                            $rptID9 = getRptID($reportName9);
                                            $prmID902 = getParamIDUseSQLRep("{:documentTitle}", $rptID9);
                                            $prmID903 = getParamIDUseSQLRep("{:orgID}", $rptID9);
                                            $prmID904 = getParamIDUseSQLRep("{:pay_run_id}", $rptID9);
                                            $paramRepsNVals9 = $prmID902 . "~" . $reportTitle9 . "|" . $prmID903 . "~" . $orgID . "|" . $prmID904 . "~" . $pkID . "|-130~" . $reportTitle9 . "|-190~PDF";
                                            $paramStr9 = urlencode($paramRepsNVals9);
                                        }

                                        $reportName7 = "Pay Run Results Details";
                                        $reportTitle7 = "Pay Run Results Details";
                                        $rptID7 = getRptID($reportName7);
                                        $prmID700 = getParamIDUseSQLRep("{:id_num}", $rptID7);
                                        $prmID701 = getParamIDUseSQLRep("{:itmNm}", $rptID7);
                                        $prmID702 = getParamIDUseSQLRep("{:documentTitle}", $rptID7);
                                        $prmID703 = getParamIDUseSQLRep("{:orgID}", $rptID7);
                                        $prmID704 = getParamIDUseSQLRep("{:pay_run_id}", $rptID7);
                                        $prmID705 = getParamIDUseSQLRep("{:clsfctn}", $rptID7);
                                        $prmID706 = getParamIDUseSQLRep("{:instu_id}", $rptID7);
                                        $prmID707 = getParamIDUseSQLRep("{:pay_dte}", $rptID7);
                                        $paramRepsNVals7 = $prmID700 . "~c.local_id_no|" . $prmID701 . "~%|" . $prmID702 . "~" . $reportTitle7 . "|" . $prmID705 . "~%|"
                                            . $prmID706 . "~|" . $prmID707 . "~" . $gnrlTrnsDteYMD . "|" . $prmID703 . "~" . $orgID . "|" . $prmID704 . "~" . $pkID .
                                            "|-130~" . $reportTitle7 . "|-190~PDF";
                                        $paramStr7 = urlencode($paramRepsNVals7);

                                        $reportTitle1 = "Bulk Pay Run Process";
                                        $reportName1 = "Bulk Pay Run Process";
                                        $rptID1 = getRptID($reportName1);
                                        $prmID11 = getParamIDUseSQLRep("{:p_mass_py_id}", $rptID1);
                                        $paramRepsNVals1 = $prmID11 . "~" . $pkID . "|-130~" . $reportTitle1 . "|-190~HTML";
                                        $paramStr1 = urlencode($paramRepsNVals1);

                                        $reportTitle2 = "Load Pay Run Attached Values";
                                        $reportName2 = "Load Pay Run Attached Values";
                                        $rptID2 = getRptID($reportName2);
                                        $prmID12 = getParamIDUseSQLRep("{:p_mass_py_id}", $rptID2);
                                        $paramRepsNVals2 = $prmID12 . "~" . $pkID . "|-130~" . $reportTitle2 . "|-190~HTML";
                                        $paramStr2 = urlencode($paramRepsNVals2);

                                        $reportTitle3 = "Rollback Bulk Pay Run Process";
                                        $reportName3 = "Rollback Bulk Pay Run Process";
                                        $rptID3 = getRptID($reportName3);
                                        $prmID13 = getParamIDUseSQLRep("{:p_mass_py_id}", $rptID3);
                                        $paramRepsNVals3 = $prmID13 . "~" . $pkID . "|-130~" . $reportTitle3 . "|-190~HTML";
                                        $paramStr3 = urlencode($paramRepsNVals3);

                                        $result1 = get_OneBasic_MsPy($pkID, $vwSelf);
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $payMassPyID = $row1[0];
                                            $payMassPyName = $row1[1];
                                            $payMassPyDesc = $row1[2];
                                            $payMassPyStatus = $row1[3];
                                            $payMassPyDate = $row1[4];
                                            $payMassPyPrsnStID = $row1[5];
                                            $payMassPyPrsnStNm = $row1[6];
                                            $payMassPyItmSetID = $row1[7];
                                            $payMassPyItmSetNm = $row1[8];

                                            $payMassPySentToGl = $row1[9];
                                            $payMassPyGlDate = $row1[10];

                                            $payMassPyGrpType = $row1[12];
                                            $payMassPyGroupName = $row1[14];
                                            $payMassPyGroupID = $row1[13];
                                            $payMassPyWorkPlaceName = $row1[16];
                                            $payMassPyWorkPlaceID = (float) $row1[15];
                                            $payMassPyWorkPlaceSiteName = $row1[18];
                                            $payMassPyWorkPlaceSiteID = (float) $row1[17];

                                            $payMassPyTtlAmnt = $row1[11];
                                            $payMassPyAmntGvn = (float) $row1[19];
                                            $payMassPyNetAmnt = 0;
                                            if ($payMassPyAmntGvn != 0) {
                                                $payMassPyNetAmnt = $payMassPyTtlAmnt - $payMassPyAmntGvn;
                                            }
                                            $payMassPyCur = $fnccurnm;
                                            $payMassPyChqNumber = $row1[21];
                                            $payMassPySignCode = $row1[22];
                                            $mkReadOnly = "";
                                            $payMassPyIsQckPay = ($row1[23] == "1") ? true : false;
                                            $payMassPyAutoAsgng = ($row1[24] == "1") ? true : false;
                                            $payMassPyAplyAdvnc = ($row1[25] == "1") ? true : false;
                                            $payMassPyKeepExcss = ($row1[26] == "1") ? true : false;
                                            $quickPayCls = "hideNotice";
                                            if (strpos($payMassPyName, "Quick") !== FALSE || $payMassPyIsQckPay === true) {
                                                $payMassPyIsQckPay = true;
                                                $quickPayCls = "";
                                            }

                                            $srchIn = "";
                                            $srchFor = "";
                                            $lmtSze = 100;
                                    ?>
                                            <div class="row">
                                                <div class="col-md-6" style="padding:0px 1px 0px 0px !important;">
                                                    <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="payMassPyName" class="control-label col-lg-4">Pay Run Name:</label>
                                                            <div class="col-lg-8">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="payMassPyName" name="payMassPyName" value="<?php echo $payMassPyName; ?>" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="payMassPyID" name="payMassPyID" value="<?php echo $payMassPyID; ?>">
                                                                <?php } else {
                                                                ?>
                                                                    <span><?php echo $payMassPyName; ?></span>
                                                                <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="payMassPyDesc" class="control-label col-lg-4">Description:</label>
                                                            <div class="col-lg-8">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="payMassPyDesc" name="payMassPyDesc" value="<?php echo $payMassPyDesc; ?>" style="width:100% !important;">
                                                                <?php } else {
                                                                ?>
                                                                    <span><?php echo $payMassPyDesc; ?></span>
                                                                <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="payMassPyPrsnStNm" class="control-label col-md-4">Person Set:</label>
                                                            <div class="col-md-8">
                                                                <div class="input-group">
                                                                    <input class="form-control" id="payMassPyPrsnStNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Person Sets for Payments" type="text" value="<?php echo $payMassPyPrsnStNm; ?>" readonly="true" />
                                                                    <input type="hidden" id="payMassPyPrsnStID" value="<?php echo $payMassPyPrsnStID; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Person Sets for Payments(Enabled)', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payMassPyPrsnStID', 'payMassPyPrsnStNm', 'clear', 1, '', function () {})">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                            <label for="payMassPyItmSetNm" class="control-label col-md-4">Item Set:</label>
                                                            <div class="col-md-8">
                                                                <div class="input-group">
                                                                    <input class="form-control" id="payMassPyItmSetNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Item Sets for Payments" type="text" value="<?php echo $payMassPyItmSetNm; ?>" readonly="true" />
                                                                    <input type="hidden" id="payMassPyItmSetID" value="<?php echo $payMassPyItmSetID; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Item Sets for Payments(Enabled)', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payMassPyItmSetID', 'payMassPyItmSetNm', 'clear', 1, '', function () {
                                                                                                        afterBulkRnItmSet();
                                                                                                    });">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-6" style="padding:0px 0px 0px 1px !important;">
                                                    <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                                        <div class="form-group form-group-sm col-md-12" style="padding:1px 0px 0px 0px !important;">
                                                            <div class="col-md-3">
                                                                <label style="margin-bottom:0px !important;">GL Date:</label>
                                                            </div>
                                                            <div class="col-md-5" style="padding:0px 1px 0px 15px !important;">
                                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="padding:0px 0px 0px 0px !important;">
                                                                    <input class="form-control" size="16" type="text" id="payMassPyGlDate" name="payMassPyGlDate" value="<?php echo $payMassPyGlDate; ?>" placeholder="GL Accounting Date" readonly="true">
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4" style="padding:0px 15px 0px 5px !important;display:inline-table !important;vertical-align: middle !important;float: left !important;">
                                                                <div class="form-check" style="font-size: 12px !important;float: left !important;">
                                                                    <label class="form-check-label" title="Should Skip Automatic Assignment of Pay Elements to Persons">
                                                                        <?php
                                                                        $payMassPyAplyAdvncChkd = "";
                                                                        if ($payMassPyAplyAdvnc == true) {
                                                                            $payMassPyAplyAdvncChkd = "checked=\"true\"";
                                                                        }
                                                                        ?>
                                                                        <input type="checkbox" class="form-check-input" id="payMassPyAplyAdvnc" name="payMassPyAplyAdvnc" <?php echo $payMassPyAplyAdvncChkd; ?>>

                                                                    </label>
                                                                </div>
                                                                <div class="" style="margin-top:2px !important; float: left !important;font-weight:bold;cursor: pointer;color:red;" onclick="tickUntickChckBx('payMassPyAplyAdvnc');" title="Should Skip Automatic Assignment of Pay Elements to Persons">
                                                                    <span>&nbsp;Apply Advance</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-md-3">
                                                                <label style="margin-bottom:0px !important;">Pay Date:</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="padding:0px 0px 0px 0px !important;">
                                                                    <input class="form-control" size="16" type="text" id="payMassPyDate" name="payMassPyDate" value="<?php echo $payMassPyDate; ?>" placeholder="Transactions Date" readonly="true">
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:6px 0px 0px 0px !important;">
                                                            <label for="payMassPyStatus" class="control-label col-md-3">Status:</label>
                                                            <div class="col-md-4">
                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                    <label class="form-check-label">
                                                                        <?php
                                                                        $payMassPyStatusChkd = "";
                                                                        if ($payMassPyStatus == "1") {
                                                                            $payMassPyStatusChkd = "checked=\"true\"";
                                                                        }
                                                                        ?>
                                                                        <input type="checkbox" class="form-check-input" id="payMassPyStatus" name="payMassPyStatus" <?php echo $payMassPyStatusChkd; ?> disabled="true">
                                                                        Run Already
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                    <label class="form-check-label">
                                                                        <?php
                                                                        $payMassPySentToGlChkd = "";
                                                                        if ($payMassPySentToGl == "1") {
                                                                            $payMassPySentToGlChkd = "checked=\"true\"";
                                                                        }
                                                                        ?>
                                                                        <input type="checkbox" class="form-check-input" id="payMassPySentToGl" name="payMassPySentToGl" <?php echo $payMassPySentToGlChkd; ?> disabled="true">
                                                                        Sent to GL
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:2px 0px 0px 0px !important;">
                                                            <label for="payMassPyIsQckPay" class="control-label col-md-3">Run Type:</label>
                                                            <div class="col-md-4">
                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                    <label class="form-check-label" title="Is a Quick Pay Run">
                                                                        <?php
                                                                        $payMassPyIsQckPayChkd = "";
                                                                        if ($payMassPyIsQckPay == true) {
                                                                            $payMassPyIsQckPayChkd = "checked=\"true\"";
                                                                        }
                                                                        ?>
                                                                        <input type="checkbox" class="form-check-input" id="payMassPyIsQckPay" name="payMassPyIsQckPay" <?php echo $payMassPyIsQckPayChkd; ?> onchange="shwHideQuckPayDivs();">
                                                                        Quick Pay
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                    <label class="form-check-label" title="Should Skip Automatic Assignment of Pay Elements to Persons">
                                                                        <?php
                                                                        $payMassPyAutoAsgngChkd = "";
                                                                        if ($payMassPyAutoAsgng == true) {
                                                                            $payMassPyAutoAsgngChkd = "checked=\"true\"";
                                                                        }
                                                                        ?>
                                                                        <input type="checkbox" class="form-check-input" id="payMassPyAutoAsgng" name="payMassPyAutoAsgng" <?php echo $payMassPyAutoAsgngChkd; ?>>
                                                                        Auto-Assign Items
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-12 <?php echo $quickPayCls; ?>" style="padding:0px 0px 0px 0px !important;" id="payMassPyIsQckPayDiv">
                                                    <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                                        <div class="col-md-6" style="padding:0px 0px 0px 0px !important;">
                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                <label for="grpType" class="control-label col-md-4">Group Type:</label>
                                                                <div class="col-md-8">
                                                                    <select class="form-control" id="payMassPyGrpType" onchange="payMassPyGrpTypChange();">
                                                                        <?php
                                                                        $valslctdArry = array("", "", "", "", "", "", "", "");
                                                                        $valuesArrys = array(
                                                                            "Everyone", "Single Person", "Divisions/Groups",
                                                                            "Grade", "Job", "Position", "Site/Location", "Person Type"
                                                                        );

                                                                        for ($z = 0; $z < count($valuesArrys); $z++) {
                                                                            if ($payMassPyGrpType == $valuesArrys[$z]) {
                                                                                $valslctdArry[$z] = "selected";
                                                                            }
                                                                        ?>
                                                                            <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?>
                                                                            </option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                <label for="payMassPyGroupName" class="control-label col-md-4">Group
                                                                    Name:</label>
                                                                <div class="col-md-8">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="payMassPyGroupName" value="<?php echo $payMassPyGroupName; ?>" readonly="">
                                                                        <input type="hidden" id="payMassPyGroupID" value="<?php echo $payMassPyGroupID; ?>">
                                                                        <label id="payMassPyGroupNameLbl" class="btn btn-primary btn-file input-group-addon" onclick="getNoticeLovs('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payMassPyGroupID', 'payMassPyGroupName', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                <label for="payMassPyWorkPlaceName" class="control-label col-md-4">Workplace
                                                                    Name:</label>
                                                                <div class="col-md-8">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="payMassPyWorkPlaceName" value="<?php echo $payMassPyWorkPlaceName; ?>" readonly="true">
                                                                        <input type="hidden" id="payMassPyWorkPlaceID" value="<?php echo $payMassPyWorkPlaceID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payMassPyWorkPlaceID', 'payMassPyWorkPlaceName', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                <label for="payMassPyWorkPlaceSiteName" class="control-label col-md-4">Site:</label>
                                                                <div class="col-md-8">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="payMassPyWorkPlaceSiteName" value="<?php echo $payMassPyWorkPlaceSiteName; ?>" readonly="true">
                                                                        <input type="hidden" id="payMassPyWorkPlaceSiteID" value="<?php echo $payMassPyWorkPlaceSiteID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Customer/Supplier Sites', 'payMassPyWorkPlaceID', '', '', 'radio', true, '', 'payMassPyWorkPlaceSiteID', 'payMassPyWorkPlaceSiteName', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6" style="padding:0px 0px 0px 1px !important;">
                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                <div class="col-md-3">
                                                                    <label style="margin-bottom:0px !important;">Amt. Given:</label>
                                                                </div>
                                                                <div class="col-md-6" style="padding:0px 1px 0px 15px !important;">
                                                                    <div class="input-group">
                                                                        <label class="btn btn-default btn-file input-group-addon active">
                                                                            <span class="" style="font-size: 20px !important;" id="payMassPyCur"><?php echo $payMassPyCur; ?></span>
                                                                        </label>
                                                                        <input class="form-control" type="text" id="payMassPyAmntGvn" value="<?php
                                                                                                                                                echo number_format($payMassPyAmntGvn, 2);
                                                                                                                                                ?>" style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('payMassPyAmntGvn');" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3" style="padding:0px 15px 0px 2px !important;display:inline-table !important;vertical-align: middle !important;float: left !important;">
                                                                    <div class="form-check" style="font-size: 12px !important;float: left !important;">
                                                                        <label class="form-check-label" title="Keep and Apply Excess amounts as Advancve Payments">
                                                                            <?php
                                                                            $payMassPyKeepExcssChkd = "";
                                                                            if ($payMassPyKeepExcss == true) {
                                                                                $payMassPyKeepExcssChkd = "checked=\"true\"";
                                                                            }
                                                                            ?>
                                                                            <input type="checkbox" class="form-check-input" id="payMassPyKeepExcss" name="payMassPyKeepExcss" <?php echo $payMassPyKeepExcssChkd; ?>>

                                                                        </label>
                                                                    </div>
                                                                    <div class="" style="margin-top:2px !important; float: left !important;font-weight:bold;cursor: pointer;color:red;" onclick="tickUntickChckBx('payMassPyKeepExcss');">
                                                                        <span>&nbsp;Keep All</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                <div class="col-md-3">
                                                                    <label style="margin-bottom:0px !important;">Cheque:</label>
                                                                </div>
                                                                <div class="col-md-6" style="padding: 0px 2px 0px 15px !important;">
                                                                    <input type="text" class="form-control" aria-label="..." data-toggle="tooltip" title="Cheque/Card Number" id="payMassPyChqNumber" name="payMassPyChqNumber" value="<?php echo $payMassPyChqNumber; ?>" <?php echo $mkReadOnly; ?>>
                                                                </div>
                                                                <div class="col-md-3" style="padding: 0px 15px 0px 2px !important;">
                                                                    <input class="form-control" type="text" data-toggle="tooltip" title="Sign Code (CCV)" id="payMassPySignCode" value="<?php echo $payMassPySignCode; ?>" style="width:100%;" <?php echo $mkReadOnly; ?> />
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                <div class="col-md-12">
                                                                    <div class="input-group" style="width:100% !important;">
                                                                        <label class="btn btn-primary btn-file input-group-addon" style="<?php echo $breadCrmbBckclr; ?>;min-width:28% !important;width:28% !important;">
                                                                            <span style="font-weight:bold;<?php echo $forecolors; ?>">Total
                                                                                Amount:</span>
                                                                        </label>
                                                                        <input style="font-size:19px !important;font-weight:bold !important;width:100%;color:blue;" class="form-control" id="payMassPyTtlAmnt" type="text" placeholder="0.00" value="<?php
                                                                                                                                                                                                                                                        echo number_format($payMassPyTtlAmnt, 2);
                                                                                                                                                                                                                                                        ?>" readonly="true">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                <div class="col-md-12">
                                                                    <div class="input-group" style="width:100% !important;">
                                                                        <label class="btn btn-primary btn-file input-group-addon" style="<?php echo $breadCrmbBckclr; ?>;min-width:28% !important;width:28% !important;">
                                                                            <span style="font-weight:bold;<?php echo $forecolors; ?>">Difference:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                                        </label>
                                                                        <input style="font-size:16px !important;font-weight:bold !important;width:100% !important;color:<?php
                                                                                                                                                                        echo ($payMassPyNetAmnt <= 0 ? "green" : "red");
                                                                                                                                                                        ?>" class="form-control" id="payMassPyNetAmnt" type="text" placeholder="0.00" value="<?php
                                                                                                                                                                                                                                                                echo number_format($payMassPyNetAmnt, 2);
                                                                                                                                                                                                                                                                ?>" readonly="true">
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
                                                        <li class="active"><a data-toggle="tabajxpaymasspy" data-rhodata="" href="#payMassPyRunDets" id="payMassPyRunDetstab">Run Details</a></li>
                                                        <li class=""><a data-toggle="tabajxpaymasspy" data-rhodata="" href="#payMassPyAttchdVals" id="payMassPyAttchdValstab">Attached Values/Quick
                                                                Pay Values</a></li>
                                                        <!--<li class=""><a data-toggle="tabajxpaymasspy" data-rhodata="" href="#payMassPyQckPayVals" id="payMassPyQckPayValstab">Quick Pay Values</a></li>-->
                                                    </ul>
                                                    <div class="custDiv" style="padding:0px !important;min-height: 30px !important;">
                                                        <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                        <div class="col-md-2" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                            <div class="btn-group">
                                                                                <button type="button" class="btn btn-default btn-sm" style="height: 30px !important;">
                                                                                    <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 5px; height:17px; width:20px; position: relative; vertical-align: middle;">
                                                                                    Run Reports
                                                                                </button>
                                                                                <button class="btn btn-info dropdown-toggle btn-sm" style="height:30px !important;" type="button" data-toggle="dropdown">
                                                                                    <span class="caret"></span>
                                                                                </button>
                                                                                <ul class="dropdown-menu" role="menu" style="margin-left: 15px !important;">
                                                                                    <li>
                                                                                        <a href="javascript:exprtPyRnAttchdVals();">
                                                                                            <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                            Export Attached Values
                                                                                        </a>
                                                                                    </li>
                                                                                    <?php if ($payMassPyStatus == "0" && $canEdt === true) { ?>
                                                                                        <li>
                                                                                            <a href="javascript:importPyRnAttchdVals();">
                                                                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                Import Attached Values
                                                                                            </a>
                                                                                        </li>
                                                                                    <?php } ?>
                                                                                    <li>
                                                                                        <a href="javascript:getSilentRptsRnSts(<?php echo $rptID7; ?>, -1, '<?php echo $paramStr7; ?>');">
                                                                                            <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                            Pay Run Results Details
                                                                                        </a>
                                                                                    </li>
                                                                                    <?php if ($vPsblVal1 == "TAKBG_SWLFR_APP_1") { ?>
                                                                                        <li>
                                                                                            <a href="javascript:getSilentRptsRnSts(<?php echo $rptID6; ?>, -1, '<?php echo $paramStr6; ?>');">
                                                                                                <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                Welfare Loan Credit Letter
                                                                                            </a>
                                                                                        </li>
                                                                                        <li>
                                                                                            <a href="javascript:getSilentRptsRnSts(<?php echo $rptID69; ?>, -1, '<?php echo $paramStr69; ?>');">
                                                                                                <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                General Welfare Credit Letter
                                                                                            </a>
                                                                                        </li>
                                                                                        <li>
                                                                                            <a href="javascript:getSilentRptsRnSts(<?php echo $rptID9; ?>, -1, '<?php echo $paramStr9; ?>');">
                                                                                                <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                Welfare Loan Repayment Letter
                                                                                            </a>
                                                                                        </li>
                                                                                        <li>
                                                                                            <a href="javascript:getSilentRptsRnSts(<?php echo $rptID8; ?>, -1, '<?php echo $paramStr8; ?>');">
                                                                                                <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                Semi-Month Welfare Contributions Letter
                                                                                            </a>
                                                                                        </li>
                                                                                    <?php } else { ?>
                                                                                        <li>
                                                                                            <a href="javascript:getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');">
                                                                                                <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                Pay Run Master Sheet Report
                                                                                            </a>
                                                                                        </li>
                                                                                        <li>
                                                                                            <a href="javascript:getSilentRptsRnSts(<?php echo $rptID4; ?>, -1, '<?php echo $paramStr4; ?>');">
                                                                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                Pay Run Bank Crediting CSV File
                                                                                            </a>
                                                                                        </li>
                                                                                        <li>
                                                                                            <a href="javascript:getSilentRptsRnSts(<?php echo $rptID5; ?>, -1, '<?php echo $paramStr5; ?>');">
                                                                                                <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                Pay Run Results Slips
                                                                                            </a>
                                                                                        </li>
                                                                                    <?php } ?>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-7" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                            <div class="col-md-10" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                                <div class="input-group">
                                                                                    <input class="form-control" id="payMassPyDtSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                                                                    echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                                                                    ?>" onkeyup="enterKeyFuncPayMassPyDt(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>')">
                                                                                    <input id="payMassPyDtPageNo1" type="hidden" value="<?php echo $pageNo1; ?>">
                                                                                    <input id="payMassPyDtPageNo2" type="hidden" value="<?php echo $pageNo2; ?>">
                                                                                    <input id="payMassPyDtPageNo3" type="hidden" value="<?php echo $pageNo3; ?>">
                                                                                    <input id="payMassPyDtTabNo" type="hidden" value="1">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getPayMassPyDt('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>')">
                                                                                        <span class="glyphicon glyphicon-remove"></span>
                                                                                    </label>
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getPayMassPyDt('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>')">
                                                                                        <span class="glyphicon glyphicon-search"></span>
                                                                                    </label>
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="payMassPyDtSrchIn">
                                                                                        <?php
                                                                                        $valslctdArry = array("", "");
                                                                                        $srchInsArrys = array("Person Name/ID No.", "Item Name");

                                                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                            if ($srchIn == $srchInsArrys[$z]) {
                                                                                                $valslctdArry[$z] = "selected";
                                                                                            }
                                                                                        ?>
                                                                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>>
                                                                                                <?php echo $srchInsArrys[$z]; ?>
                                                                                            </option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="payMassPyDtDsplySze" style="min-width:70px !important;">
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
                                                                                            <option value="<?php echo $dsplySzeArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>>
                                                                                                <?php echo $dsplySzeArry[$y]; ?>
                                                                                            </option>
                                                                                        <?php
                                                                                        }
                                                                                        ?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-2" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                                <nav aria-label="Page navigation">
                                                                                    <ul class="pagination" style="margin: 0px !important;">
                                                                                        <li>
                                                                                            <a class="rhopagination" href="javascript:getPayMassPyDt('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>');" aria-label="Previous">
                                                                                                <span aria-hidden="true">&laquo;</span>
                                                                                            </a>
                                                                                        </li>
                                                                                        <li>
                                                                                            <a class="rhopagination" href="javascript:getPayMassPyDt('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>');" aria-label="Next">
                                                                                                <span aria-hidden="true">&raquo;</span>
                                                                                            </a>
                                                                                        </li>
                                                                                    </ul>
                                                                                </nav>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3" style="padding:0px 0px 0px 0px !important;">
                                                                            <div class="" style="padding:0px 0px 0px 0px;float:right !important;">
                                                                                <?php
                                                                                if ($payMassPyStatus == "0") {
                                                                                ?>
                                                                                    <?php if ($canEdt) { ?>
                                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="savePayMassPyForm('<?php echo $fnccurnm; ?>', 0);" data-toggle="tooltip" data-placement="bottom" title="Save and Load Attached Values">
                                                                                            <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                                                            Save
                                                                                        </button>
                                                                                    <?php
                                                                                    }
                                                                                    if ($payMassPyID > 0) {
                                                                                    ?>
                                                                                        <button type="button" class="btn btn-default" style="display:none;margin-bottom: 1px;height:30px;" onclick="savePayMassPyForm('<?php echo $fnccurnm; ?>', 5,<?php echo $rptID2; ?>, -1, '<?php echo $paramStr2; ?>');" data-toggle="tooltip" data-placement="bottom" title="Load Attached Values">
                                                                                            <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Values&nbsp;
                                                                                        </button>
                                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="savePayMassPyForm('<?php echo $fnccurnm; ?>', 5,<?php echo $rptID1; ?>, -1, '<?php echo $paramStr1; ?>');" data-toggle="tooltip" data-placement="bottom" title="Run Bulk Pay">
                                                                                            <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Run
                                                                                            Mass Pay&nbsp;
                                                                                        </button>
                                                                                    <?php
                                                                                    }
                                                                                } else if ($payMassPyStatus == "1") {
                                                                                    $trnsCnt2 = 0;
                                                                                    $trnsCnt3 = 1;
                                                                                    if (strpos($payMassPyName, "Reversal") === FALSE) {
                                                                                        $strSql = "SELECT count(1) FROM pay.pay_itm_trnsctns a WHERE(a.pymnt_vldty_status='VALID' and a.mass_pay_id = " . $payMassPyID . " and a.src_py_trns_id<=0)";
                                                                                        $result1 = executeSQLNoParams($strSql);
                                                                                        while ($row = loc_db_fetch_array($result1)) {
                                                                                            $trnsCnt2 = (float) $row[0];
                                                                                        }
                                                                                    } else {
                                                                                        $strSql2 = "SELECT count(1) FROM pay.pay_itm_trnsctns a WHERE(a.pymnt_vldty_status='VALID' and a.mass_pay_id = " . $payMassPyID . " and a.src_py_trns_id>0)";
                                                                                        $result2 = executeSQLNoParams($strSql2);
                                                                                        while ($row = loc_db_fetch_array($result2)) {
                                                                                            $trnsCnt3 = (float) $row[0];
                                                                                        }
                                                                                    }
                                                                                    if ($trnsCnt2 > 0 || $trnsCnt3 <= 0) {
                                                                                    ?>
                                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="printPayPOSRcpt(<?php echo $payMassPyID; ?>);" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title="POS Receipt">
                                                                                            <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                            Receipt
                                                                                        </button>
                                                                                        <button id="fnlzeRvrslPayMassPyBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="savePayMassPyForm('<?php echo $fnccurnm; ?>', 7,<?php echo $rptID3; ?>, -1, '<?php echo $paramStr3; ?>');" data-toggle="tooltip" data-placement="bottom" title="Rollback Pay Run">
                                                                                            <img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Rollback&nbsp;
                                                                                        </button>
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
                                                    <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="onePayMassPyLnsTblSctn">
                                                        <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                                            <div id="payMassPyRunDets" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <table class="table table-striped table-bordered table-responsive" id="payMassPyRunDetsTable" cellspacing="0" width="100%" style="width:100%;min-width: 500px !important;">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>No.</th>
                                                                                    <th>(ID No.) Person Name</th>
                                                                                    <th>Item Name</th>
                                                                                    <th>Item/Transaction Type</th>
                                                                                    <th style="text-align: right;">Amount Paid</th>
                                                                                    <th style="text-align: center;">UOM</th>
                                                                                    <th>Transaction Description</th>
                                                                                    <th>Validity</th>
                                                                                    <?php if ($canVwRcHstry) { ?>
                                                                                        <th>...</th>
                                                                                    <?php } ?>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php
                                                                                //$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 100;
                                                                                $total = get_One_MsPyDetTtl2($srchFor, $srchIn, $pkID);
                                                                                //echo "::".$total."::".$pageNo1."::".$lmtSze;
                                                                                if ($pageNo1 > ceil($total / $lmtSze)) {
                                                                                    $pageNo1 = 1;
                                                                                } else if ($pageNo1 < 1) {
                                                                                    $pageNo1 = ceil($total / $lmtSze);
                                                                                }

                                                                                $curIdx = $pageNo1 - 1;
                                                                                $resultRw = get_One_MsPyDet2(
                                                                                    $srchFor,
                                                                                    $srchIn,
                                                                                    $curIdx,
                                                                                    $lmtSze,
                                                                                    $pkID
                                                                                );
                                                                                $cntr = 0;

                                                                                $brghtTotal = 0;
                                                                                $fnlColorAmntDffrnc = 0;
                                                                                $prpsdTtlSpnColor = "black";
                                                                                $itmTypCnt = getBatchItmTypCnt(-1, $pkID, -1);
                                                                                while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                                    $trsctnLineID = (float) $rowRw[0];
                                                                                    $trsctnLineIDNo = $rowRw[10];
                                                                                    $trsctnLinePrsnNm = $rowRw[11];
                                                                                    $trsctnLineItmNm = $rowRw[12];
                                                                                    $trsctnLineAmnt = (float) $rowRw[3];
                                                                                    $trsctnLineUOM = $rowRw[14];
                                                                                    $trsctnLineMinType = $rowRw[15];
                                                                                    $trsctnLineType = $rowRw[6];
                                                                                    $trsctnLineVldty = $rowRw[13];
                                                                                    $trsctnLineDesc = $rowRw[7];
                                                                                    $trsctnLineVldtyClr = "green";
                                                                                    if ($trsctnLineVldty == "VOID") {
                                                                                        $trsctnLineVldtyClr = "red";
                                                                                    }
                                                                                    $cntr += 1;
                                                                                ?>
                                                                                    <tr id="payMassPyRunDetsRow_<?php echo $cntr; ?>">
                                                                                        <td class="lovtd">
                                                                                            <span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span>
                                                                                        </td>
                                                                                        <td class="lovtd">
                                                                                            <input type="hidden" class="form-control" aria-label="..." id="payMassPyRunDetsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                                                                            <span><?php echo $trsctnLinePrsnNm . " - [" . $trsctnLineIDNo . "]"; ?></span>
                                                                                        </td>
                                                                                        <td class="lovtd">
                                                                                            <span><?php echo ($trsctnLineItmNm); ?></span>
                                                                                        </td>
                                                                                        <td class="lovtd">
                                                                                            <span><?php echo ($trsctnLineMinType . " - " . $trsctnLineType); ?></span>
                                                                                        </td>
                                                                                        <td class="lovtd" style="text-align: right;font-weight:bold !important;">
                                                                                            <?php
                                                                                            echo getBatchNetAmnt(
                                                                                                $itmTypCnt,
                                                                                                $trsctnLineMinType,
                                                                                                $trsctnLineItmNm,
                                                                                                $rowRw[16],
                                                                                                $trsctnLineAmnt,
                                                                                                $brghtTotal,
                                                                                                $prpsdTtlSpnColor
                                                                                            );
                                                                                            ?>
                                                                                        </td>
                                                                                        <td class="lovtd">
                                                                                            <div class="" style="width:100% !important;">
                                                                                                <label class="btn btn-primary btn-file">
                                                                                                    <span class="" id="payMassPyRunDetsRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $trsctnLineUOM; ?></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td class="lovtd">
                                                                                            <span><?php echo $trsctnLineDesc; ?></span>
                                                                                        </td>
                                                                                        <td class="lovtd" style="font-weight:bold;color:<?php echo $trsctnLineVldtyClr; ?>">
                                                                                            <span><?php echo $trsctnLineVldty; ?></span>
                                                                                        </td>
                                                                                        <?php
                                                                                        if ($canVwRcHstry === true) {
                                                                                        ?>
                                                                                            <td class="lovtd">
                                                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                                                        echo urlencode(encrypt1(($trsctnLineID . "|pay.pay_itm_trnsctns|pay_trns_id"),
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
                                                            </div>
                                                            <div id="payMassPyAttchdVals" class="tab-pane fadein" style="border:none !important;padding:0px !important;">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <table class="table table-striped table-bordered table-responsive" id="payMassPyAttchdValsTable" cellspacing="0" width="100%" style="width:100%;min-width: 500px !important;">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>No.</th>
                                                                                    <th>(ID No.) Person Name</th>
                                                                                    <th>Item Name</th>
                                                                                    <th>Item Type</th>
                                                                                    <th>Item Value Name</th>
                                                                                    <th style="text-align: right;">Value/Amount to Use</th>
                                                                                    <th>...</th>
                                                                                    <?php
                                                                                    if ($canVwRcHstry === true) {
                                                                                    ?>
                                                                                        <th>&nbsp;</th>
                                                                                    <?php } ?>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php
                                                                                //$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 100;
                                                                                $total = get_Total_MsPayAtchdVals($srchFor, $srchIn, $pkID);
                                                                                //echo "::".$total."::".$pageNo1."::".$lmtSze;
                                                                                if ($pageNo2 > ceil($total / $lmtSze)) {
                                                                                    $pageNo2 = 1;
                                                                                } else if ($pageNo2 < 1) {
                                                                                    $pageNo2 = ceil($total / $lmtSze);
                                                                                }

                                                                                $curIdx = $pageNo2 - 1;
                                                                                $resultRw = get_One_MsPayAtchdVals(
                                                                                    $srchFor,
                                                                                    $srchIn,
                                                                                    $curIdx,
                                                                                    $lmtSze,
                                                                                    $pkID
                                                                                );
                                                                                $cntr = 0;
                                                                                $mkReadOnly = "";
                                                                                if ($payMassPyStatus == "1") {
                                                                                    $mkReadOnly = "readonly=\"true\"";
                                                                                }
                                                                                $itmTypCnt = getBatchItmTypCnt1($pkID);
                                                                                $brghtTotal = 0;
                                                                                $prpsdTtlSpnColor = "";
                                                                                while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                                    $trsctnLineID = (float) $rowRw[0];
                                                                                    $trsctnLinePrsnID = (float) $rowRw[2];
                                                                                    $trsctnLineItmID = (float) $rowRw[5];
                                                                                    $trsctnLineItmValID = (float) $rowRw[7];
                                                                                    $trsctnLineIDNo = $rowRw[3];
                                                                                    $trsctnLinePrsnNm = $rowRw[4];
                                                                                    $trsctnLineItmNm = $rowRw[6];
                                                                                    $trsctnLineItmValNm = $rowRw[8];
                                                                                    $trsctnLineMinType = $rowRw[10];
                                                                                    $trsctnLineAmnt = (float) $rowRw[9];
                                                                                    $cntr += 1;
                                                                                    $trsctnAmnt = getBatchNetAmnt(
                                                                                        $itmTypCnt,
                                                                                        $trsctnLineMinType,
                                                                                        $trsctnLineItmNm,
                                                                                        $rowRw[11],
                                                                                        $trsctnLineAmnt,
                                                                                        $brghtTotal,
                                                                                        $prpsdTtlSpnColor
                                                                                    );
                                                                                    $trsctnLineIsValHidden = $mkReadOnly;
                                                                                    if ($rowRw[13] == "0" && $mkReadOnly == "") {
                                                                                        $trsctnLineIsValHidden = "readonly=\"true\"";
                                                                                    }
                                                                                ?>
                                                                                    <tr id="payMassPyAttchdValsRow_<?php echo $cntr; ?>">
                                                                                        <td class="lovtd">
                                                                                            <span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span>
                                                                                        </td>
                                                                                        <td class="lovtd">
                                                                                            <input type="hidden" class="form-control" aria-label="..." id="payMassPyAttchdValsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                                                                            <input type="hidden" class="form-control" aria-label="..." id="payMassPyAttchdValsRow<?php echo $cntr; ?>_PrsnID" value="<?php echo $trsctnLinePrsnID; ?>" style="width:100% !important;">
                                                                                            <input type="hidden" class="form-control" aria-label="..." id="payMassPyAttchdValsRow<?php echo $cntr; ?>_ItemID" value="<?php echo $trsctnLineItmID; ?>" style="width:100% !important;">
                                                                                            <input type="hidden" class="form-control" aria-label="..." id="payMassPyAttchdValsRow<?php echo $cntr; ?>_ItemValID" value="<?php echo $trsctnLineItmValID; ?>" style="width:100% !important;">
                                                                                            <input type="hidden" class="form-control" aria-label="..." id="payMassPyAttchdValsRow<?php echo $cntr; ?>_CanEdt" value="<?php echo $rowRw[13]; ?>" style="width:100% !important;">
                                                                                            <span><?php echo $trsctnLinePrsnNm . " - [" . $trsctnLineIDNo . "]"; ?></span>
                                                                                        </td>
                                                                                        <td class="lovtd">
                                                                                            <span><?php echo ($trsctnLineItmNm); ?></span>
                                                                                        </td>
                                                                                        <td class="lovtd">
                                                                                            <span><?php echo ($trsctnLineMinType); ?></span>
                                                                                        </td>
                                                                                        <td class="lovtd">
                                                                                            <span><?php echo ($trsctnLineItmValNm); ?></span>
                                                                                        </td>
                                                                                        <td class="lovtd" style="text-align: right;">
                                                                                            <input type="text" class="form-control  jbDetDbt" id="payMassPyAttchdValsRow<?php echo $cntr; ?>_ValToUse" onkeypress="gnrlFldKeyPress(event, 'payMassPyAttchdValsRow<?php echo $cntr; ?>_ValToUse', 'payMassPyAttchdValsTable', 'jbDetDbt');" onblur="fmtAsNumber('payMassPyAttchdValsRow<?php echo $cntr; ?>_ValToUse');" style="font-weight:bold;font-size:12px;color:blue;text-align: right;" value="<?php echo number_format($trsctnLineAmnt, 2); ?>" <?php echo $trsctnLineIsValHidden; ?> />
                                                                                        </td>
                                                                                        <td class="lovtd">
                                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delPayMassPyAttchdVal('payMassPyAttchdValsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Attached Value">
                                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                                            </button>
                                                                                        </td>
                                                                                        <?php
                                                                                        if ($canVwRcHstry === true) {
                                                                                        ?>
                                                                                            <td class="lovtd">
                                                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                                                        echo urlencode(encrypt1(($trsctnLineID . "|pay.pay_value_sets_det|value_set_det_id"),
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
                                                                            <tfoot>
                                                                                <tr>
                                                                                    <th>&nbsp;</th>
                                                                                    <th>&nbsp;</th>
                                                                                    <th>&nbsp;</th>
                                                                                    <th>&nbsp;</th>
                                                                                    <th>Net Amount (<?php echo $fnccurnm; ?>):
                                                                                    </th>
                                                                                    <th style="text-align: right;">
                                                                                        <?php
                                                                                        echo "<span style=\"color:$prpsdTtlSpnColor;font-weight:bold;font-size:14px;\" id=\"payMassPyAttchdValsTtlBtn\">" . number_format(
                                                                                            $brghtTotal,
                                                                                            2,
                                                                                            '.',
                                                                                            ','
                                                                                        ) . "</span>";
                                                                                        ?>
                                                                                        <input type="hidden" id="payMassPyAttchdValsTtlVal" value="<?php echo $brghtTotal; ?>">
                                                                                    </th>
                                                                                    <th>&nbsp;</th>
                                                                                    <?php
                                                                                    if ($canVwRcHstry === true) {
                                                                                    ?>
                                                                                        <th>&nbsp;</th>
                                                                                    <?php } ?>
                                                                                </tr>
                                                                            </tfoot>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="payMassPyQckPayVals" class="tab-pane fadein" style="border:none !important;padding:0px !important;">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        Editable Table of Quick Pay Values
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <span>No Results Found</span>

                                    <?php
                                    }
                                    ?>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>
            <?php
            } else if ($vwtyp == 1) {
                $pkID = isset($_POST['sbmtdMassPayRunID']) ? (float) $_POST['sbmtdMassPayRunID'] : -1;
                $isDiagForm = isset($_POST['isDiagForm']) ? $_POST['isDiagForm'] : 'NO';

                $qckPayPrsnSetID = isset($_POST['qckPayPrsnSetID']) ? (float) $_POST['qckPayPrsnSetID'] : -1;
                $qckPayItmSetID = isset($_POST['qckPayItmSetID']) ? (float) $_POST['qckPayItmSetID'] : -1;
                $qckPayPrsns_PrsnID = isset($_POST['qckPayPrsns_PrsnID']) ? (float) $_POST['qckPayPrsns_PrsnID'] : -1;

                $reportName = "Pay Run Master Sheet-Web";
                $reportTitle = "Pay Run Master Sheet";
                $rptID = getRptID($reportName);
                $prmID01 = getParamIDUseSQLRep("{:ordrBy}", $rptID);
                $prmID02 = getParamIDUseSQLRep("{:orgID}", $rptID);
                $prmID03 = getParamIDUseSQLRep("{:pay_run_no}", $rptID);
                $paramRepsNVals = $prmID01 . "~2|" . $prmID02 . "~" . $orgID . "|" . $prmID03 . "~" . $pkID . "|-130~" . $reportTitle . "|-190~PDF";
                $paramStr = urlencode($paramRepsNVals);

                $reportName4 = "Pay Run Bank Crediting CSV File-Web";
                $reportTitle4 = "Pay Run Bank Crediting CSV File";
                $rptID4 = getRptID($reportName4);
                $prmID401 = getParamIDUseSQLRep("{:ordrBy}", $rptID4);
                $prmID402 = getParamIDUseSQLRep("{:pay_run_name}", $rptID4);
                $paramRepsNVals4 = $prmID401 . "~2|" . $prmID402 . "~" . $pkID . "|-130~" . $reportTitle4 . "|-190~CHARACTER SEPARATED FILE (CSV)";
                $paramStr4 = urlencode($paramRepsNVals4);

                $reportName5 = "Customized Pay Slip (Sample 1)-Per Run";
                $reportTitle5 = "Pay Slip";
                $rptID5 = getRptID($reportName5);
                $prmID501 = getParamIDUseSQLRep("{:IDNos}", $rptID5);
                $prmID502 = getParamIDUseSQLRep("{:documentTitle}", $rptID5);
                $prmID503 = getParamIDUseSQLRep("{:orgID}", $rptID5);
                $prmID504 = getParamIDUseSQLRep("{:pay_run_id}", $rptID5);
                $paramRepsNVals5 = $prmID501 . "~c.local_id_no|" . $prmID502 . "~" . $reportTitle5 . "|" . $prmID503 . "~" . $orgID . "|" . $prmID504 . "~" . $pkID . "|-130~" . $reportTitle5 . "|-190~PDF";
                $paramStr5 = urlencode($paramRepsNVals5);

                $vPsblValID1 = getEnbldPssblValID("Application Instance SHORT CODE", getLovID("All Other General Setups"));
                $vPsblVal1 = getPssblValDesc($vPsblValID1);
                $rptID6 = -1;
                $paramStr6 = "";
                $rptID69 = -1;
                $paramStr69 = ""; //
                $rptID8 = -1;
                $paramStr8 = "";
                $rptID9 = -1;
                $paramStr9 = "";
                if ($vPsblVal1 == "TAKBG_SWLFR_APP_1") {
                    $reportName69 = "General Welfare Credit Letter";
                    $reportTitle69 = "General Welfare Credit Letter";
                    $rptID69 = getRptID($reportName69);
                    $prmID6029 = getParamIDUseSQLRep("{:documentTitle}", $rptID69);
                    $prmID6039 = getParamIDUseSQLRep("{:orgID}", $rptID69);
                    $prmID6049 = getParamIDUseSQLRep("{:pay_run_id}", $rptID69);
                    $paramRepsNVals69 = $prmID6029 . "~" . $reportTitle69 . "|" . $prmID6039 . "~" . $orgID . "|" . $prmID6049 . "~" . $pkID . "|-130~" . $reportTitle69 . "|-190~PDF";
                    $paramStr69 = urlencode($paramRepsNVals69);

                    $reportName6 = "Welfare Loan Credit Letter";
                    $reportTitle6 = "Welfare Loan Letter";
                    $rptID6 = getRptID($reportName6);
                    $prmID602 = getParamIDUseSQLRep("{:documentTitle}", $rptID6);
                    $prmID603 = getParamIDUseSQLRep("{:orgID}", $rptID6);
                    $prmID604 = getParamIDUseSQLRep("{:pay_run_id}", $rptID6);
                    $paramRepsNVals6 = $prmID602 . "~" . $reportTitle6 . "|" . $prmID603 . "~" . $orgID . "|" . $prmID604 . "~" . $pkID . "|-130~" . $reportTitle6 . "|-190~PDF";
                    $paramStr6 = urlencode($paramRepsNVals6);

                    $reportName8 = "Semi-Month Welfare Contributions Letter";
                    $reportTitle8 = "Welfare Contributions Letter";
                    $rptID8 = getRptID($reportName8);
                    $prmID802 = getParamIDUseSQLRep("{:documentTitle}", $rptID8);
                    $prmID803 = getParamIDUseSQLRep("{:orgID}", $rptID8);
                    $prmID804 = getParamIDUseSQLRep("{:pay_run_id}", $rptID8);
                    $paramRepsNVals8 = $prmID802 . "~" . $reportTitle8 . "|" . $prmID803 . "~" . $orgID . "|" . $prmID804 . "~" . $pkID . "|-130~" . $reportTitle8 . "|-190~PDF";
                    $paramStr8 = urlencode($paramRepsNVals8);

                    $reportName9 = "Welfare Loan Repayment Letter";
                    $reportTitle9 = "Welfare Loan Repayment Letter";
                    $rptID9 = getRptID($reportName9);
                    $prmID902 = getParamIDUseSQLRep("{:documentTitle}", $rptID9);
                    $prmID903 = getParamIDUseSQLRep("{:orgID}", $rptID9);
                    $prmID904 = getParamIDUseSQLRep("{:pay_run_id}", $rptID9);
                    $paramRepsNVals9 = $prmID902 . "~" . $reportTitle9 . "|" . $prmID903 . "~" . $orgID . "|" . $prmID904 . "~" . $pkID . "|-130~" . $reportTitle9 . "|-190~PDF";
                    $paramStr9 = urlencode($paramRepsNVals9);
                }

                $reportName7 = "Pay Run Results Details";
                $reportTitle7 = "Pay Run Results Details";
                $rptID7 = getRptID($reportName7);
                $prmID700 = getParamIDUseSQLRep("{:id_num}", $rptID7);
                $prmID701 = getParamIDUseSQLRep("{:itmNm}", $rptID7);
                $prmID702 = getParamIDUseSQLRep("{:documentTitle}", $rptID7);
                $prmID703 = getParamIDUseSQLRep("{:orgID}", $rptID7);
                $prmID704 = getParamIDUseSQLRep("{:pay_run_id}", $rptID7);
                $prmID705 = getParamIDUseSQLRep("{:clsfctn}", $rptID7);
                $prmID706 = getParamIDUseSQLRep("{:instu_id}", $rptID7);
                $prmID707 = getParamIDUseSQLRep("{:pay_dte}", $rptID7);
                $paramRepsNVals7 = $prmID700 . "~c.local_id_no|" . $prmID701 . "~%|" . $prmID702 . "~" . $reportTitle7 . "|" . $prmID705 . "~%|"
                    . $prmID706 . "~|" . $prmID707 . "~" . $gnrlTrnsDteYMD . "|" . $prmID703 . "~" . $orgID . "|" . $prmID704 . "~" . $pkID .
                    "|-130~" . $reportTitle7 . "|-190~PDF";
                $paramStr7 = urlencode($paramRepsNVals7);

                $reportTitle1 = "Bulk Pay Run Process";
                $reportName1 = "Bulk Pay Run Process";
                $rptID1 = getRptID($reportName1);
                $prmID11 = getParamIDUseSQLRep("{:p_mass_py_id}", $rptID1);
                $paramRepsNVals1 = $prmID11 . "~" . $pkID . "|-130~" . $reportTitle1 . "|-190~HTML";
                $paramStr1 = urlencode($paramRepsNVals1);

                $reportTitle2 = "Load Pay Run Attached Values";
                $reportName2 = "Load Pay Run Attached Values";
                $rptID2 = getRptID($reportName2);
                $prmID12 = getParamIDUseSQLRep("{:p_mass_py_id}", $rptID2);
                $paramRepsNVals2 = $prmID12 . "~" . $pkID . "|-130~" . $reportTitle2 . "|-190~HTML";
                $paramStr2 = urlencode($paramRepsNVals2);

                $reportTitle3 = "Rollback Bulk Pay Run Process";
                $reportName3 = "Rollback Bulk Pay Run Process";
                $rptID3 = getRptID($reportName3);
                $prmID13 = getParamIDUseSQLRep("{:p_mass_py_id}", $rptID3);
                $paramRepsNVals3 = $prmID13 . "~" . $pkID . "|-130~" . $reportTitle3 . "|-190~HTML";
                $paramStr3 = urlencode($paramRepsNVals3);

                $payMassPyName = "";
                if ($pkID <= 0) {
                    $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
                    if ($usrTrnsCode == "") {
                        $usrTrnsCode = "XX";
                    }
                    $dte = date('ymd');
                    $docTypPrfx = strtoupper($usrName);
                    if ($isDiagForm === 'YES') {
                        $docTypPrfx = strtoupper($usrName) . "-QP";
                    }
                    $gnrtdTrnsNo1 = $docTypPrfx . "-" . $usrTrnsCode . "-" . $dte . "-";
                    $gnrtdTrnsNo = $gnrtdTrnsNo1 . str_pad(((getRecCount_LstNum(
                        "pay.pay_mass_pay_run_hdr",
                        "mass_pay_name",
                        "mass_pay_id",
                        $gnrtdTrnsNo1 . "%"
                    ) + 1) . ""), 3, '0', STR_PAD_LEFT);
                    $payMassPyName = $gnrtdTrnsNo;
                }
                $payMassPyDesc = "";
                $payMassPyStatus = "0";
                $payMassPyDate = $gnrlTrnsDteDMYHMS;
                $payMassPyPrsnStID = $qckPayPrsnSetID;
                $payMassPyPrsnStNm = "";
                if ($payMassPyPrsnStID > 0) {
                    $payMassPyPrsnStNm = getGnrlRecNm("pay.pay_prsn_sets_hdr", "prsn_set_hdr_id", "prsn_set_hdr_name", $payMassPyPrsnStID);
                }
                $payMassPyItmSetID = $qckPayItmSetID;
                $payMassPyItmSetNm = "";
                if ($payMassPyItmSetID > 0) {
                    $payMassPyItmSetNm = getGnrlRecNm("pay.pay_itm_sets_hdr", "hdr_id", "itm_set_name", $payMassPyItmSetID);
                }
                $payMassPySentToGl = "0";
                $payMassPyGlDate = $gnrlTrnsDteDMYHMS;

                $payMassPyGrpType = "Everyone";
                $payMassPyGroupName = "";
                $payMassPyGroupID = "-1";
                if ($qckPayPrsns_PrsnID > 0) {
                    $payMassPyGrpType = "Single Person";
                    $payMassPyGroupName = getPrsnFullNm($qckPayPrsns_PrsnID) . " (" . getPersonLocID($qckPayPrsns_PrsnID) . ")";
                    $payMassPyGroupID = $qckPayPrsns_PrsnID;
                }
                $payMassPyWorkPlaceName = "";
                $payMassPyWorkPlaceID = -1;
                $payMassPyWorkPlaceSiteName = "";
                $payMassPyWorkPlaceSiteID = -1;

                $payMassPyTtlAmnt = 0;
                $payMassPyNetAmnt = 0;
                $payMassPyAmntGvn = 0;
                $payMassPyCur = $fnccurnm;
                $payMassPyChqNumber = "";
                $payMassPySignCode = "";
                $mkReadOnly = "";
                $payMassPyIsQckPay = false;
                $payMassPyAutoAsgng = false;
                $payMassPyAplyAdvnc = false;
                $payMassPyKeepExcss = true;
                $quickPayCls = "hideNotice";
                if ($isDiagForm === 'YES') {
                    $payMassPyIsQckPay = true;
                    $payMassPyAutoAsgng = true;
                    $quickPayCls = "";
                }
                $payMassPyID = $pkID;
                if ($pkID > 0) {
                    $result1 = get_OneBasic_MsPy($pkID, $vwSelf);
                    while ($row1 = loc_db_fetch_array($result1)) {
                        $payMassPyID = $row1[0];
                        $payMassPyName = $row1[1];
                        $payMassPyDesc = $row1[2];
                        $payMassPyStatus = $row1[3];
                        $payMassPyDate = $row1[4];
                        $payMassPyPrsnStID = $row1[5];
                        $payMassPyPrsnStNm = $row1[6];
                        $payMassPyItmSetID = $row1[7];
                        $payMassPyItmSetNm = $row1[8];

                        $payMassPySentToGl = $row1[9];
                        $payMassPyGlDate = $row1[10];

                        $payMassPyGrpType = $row1[12];
                        $payMassPyGroupName = $row1[14];
                        $payMassPyGroupID = $row1[13];
                        $payMassPyWorkPlaceName = $row1[16];
                        $payMassPyWorkPlaceID = (float) $row1[15];
                        $payMassPyWorkPlaceSiteName = $row1[18];
                        $payMassPyWorkPlaceSiteID = (float) $row1[17];

                        $payMassPyTtlAmnt = $row1[11];
                        $payMassPyAmntGvn = (float) $row1[19];
                        $payMassPyNetAmnt = 0;
                        if ($payMassPyAmntGvn != 0) {
                            $payMassPyNetAmnt = $payMassPyTtlAmnt - $payMassPyAmntGvn;
                        }
                        $payMassPyCur = $fnccurnm;
                        $payMassPyChqNumber = $row1[21];
                        $payMassPySignCode = $row1[22];
                        $mkReadOnly = "";
                        $payMassPyIsQckPay = ($row1[23] == "1") ? true : false;
                        $payMassPyAutoAsgng = ($row1[24] == "1") ? true : false;
                        $payMassPyAplyAdvnc = ($row1[25] == "1") ? true : false;
                        $payMassPyKeepExcss = ($row1[26] == "1") ? true : false;
                        $quickPayCls = "hideNotice";
                        if (strpos($payMassPyName, "Quick") !== FALSE || $payMassPyIsQckPay === true) {
                            $payMassPyIsQckPay = true;
                            $quickPayCls = "";
                        }
                    }
                }
                $srchIn = "";
                $srchFor = "";
                $lmtSze = 100;
            ?>
                <div class="row">
                    <div class="col-md-6" style="padding:0px 1px 0px 0px !important;">
                        <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                <label for="payMassPyName" class="control-label col-lg-4">Pay Run Name:</label>
                                <div class="col-lg-8">
                                    <?php
                                    if ($canEdt === true) {
                                    ?>
                                        <input type="text" class="form-control rqrdFld" aria-label="..." id="payMassPyName" name="payMassPyName" value="<?php echo $payMassPyName; ?>" style="width:100% !important;">
                                        <input type="hidden" class="form-control" aria-label="..." id="payMassPyID" name="payMassPyID" value="<?php echo $payMassPyID; ?>">
                                    <?php } else {
                                    ?>
                                        <span><?php echo $payMassPyName; ?></span>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                <label for="payMassPyDesc" class="control-label col-lg-4">Description:</label>
                                <div class="col-lg-8">
                                    <?php
                                    if ($canEdt === true) {
                                    ?>
                                        <input type="text" class="form-control" aria-label="..." id="payMassPyDesc" name="payMassPyDesc" value="<?php echo $payMassPyDesc; ?>" style="width:100% !important;">
                                    <?php } else {
                                    ?>
                                        <span><?php echo $payMassPyDesc; ?></span>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                <label for="payMassPyPrsnStNm" class="control-label col-md-4">Person Set:</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input class="form-control" id="payMassPyPrsnStNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Person Sets for Payments" type="text" value="<?php echo $payMassPyPrsnStNm; ?>" readonly="true" />
                                        <input type="hidden" id="payMassPyPrsnStID" value="<?php echo $payMassPyPrsnStID; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Person Sets for Payments(Enabled)', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payMassPyPrsnStID', 'payMassPyPrsnStNm', 'clear', 1, '', function () {})">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                <label for="payMassPyItmSetNm" class="control-label col-md-4">Item Set:</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input class="form-control rqrdFld" id="payMassPyItmSetNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Item Sets for Payments" type="text" value="<?php echo $payMassPyItmSetNm; ?>" readonly="true" />
                                        <input type="hidden" id="payMassPyItmSetID" value="<?php echo $payMassPyItmSetID; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Item Sets for Payments(Enabled)', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payMassPyItmSetID', 'payMassPyItmSetNm', 'clear', 1, '', function () {
                                                                    afterBulkRnItmSet();
                                                                });">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-md-6" style="padding:0px 0px 0px 1px !important;">
                        <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                            <div class="form-group form-group-sm col-md-12" style="padding:1px 0px 0px 0px !important;">
                                <div class="col-md-3">
                                    <label style="margin-bottom:0px !important;">GL Date:</label>
                                </div>
                                <div class="col-md-5" style="padding:0px 1px 0px 15px !important;">
                                    <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="padding:0px 0px 0px 0px !important;">
                                        <input class="form-control" size="16" type="text" id="payMassPyGlDate" name="payMassPyGlDate" value="<?php echo $payMassPyGlDate; ?>" placeholder="GL Accounting Date" readonly="true">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="col-md-4" style="padding:0px 15px 0px 5px !important;display:inline-table !important;vertical-align: middle !important;float: left !important;">
                                    <div class="form-check" style="font-size: 12px !important;float: left !important;">
                                        <label class="form-check-label" title="Should Skip Automatic Assignment of Pay Elements to Persons">
                                            <?php
                                            $payMassPyAplyAdvncChkd = "";
                                            if ($payMassPyAplyAdvnc == true) {
                                                $payMassPyAplyAdvncChkd = "checked=\"true\"";
                                            }
                                            ?>
                                            <input type="checkbox" class="form-check-input" id="payMassPyAplyAdvnc" name="payMassPyAplyAdvnc" <?php echo $payMassPyAplyAdvncChkd; ?>>
                                        </label>
                                    </div>
                                    <div class="" style="margin-top:2px !important; float: left !important;font-weight:bold;cursor: pointer;color:red;" onclick="tickUntickChckBx('payMassPyAplyAdvnc');" title="Should Skip Automatic Assignment of Pay Elements to Persons">
                                        <span>&nbsp;Apply Advance</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label style="margin-bottom:0px !important;">Pay Date:</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="padding:0px 0px 0px 0px !important;">
                                        <input class="form-control" size="16" type="text" id="payMassPyDate" name="payMassPyDate" value="<?php echo $payMassPyDate; ?>" placeholder="Transactions Date" readonly="true">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:6px 0px 0px 0px !important;">
                                <label for="payMassPyStatus" class="control-label col-md-3">Status:</label>
                                <div class="col-md-4">
                                    <div class="form-check" style="font-size: 12px !important;">
                                        <label class="form-check-label">
                                            <?php
                                            $payMassPyStatusChkd = "";
                                            if ($payMassPyStatus == "1") {
                                                $payMassPyStatusChkd = "checked=\"true\"";
                                            }
                                            ?>
                                            <input type="checkbox" class="form-check-input" id="payMassPyStatus" name="payMassPyStatus" <?php echo $payMassPyStatusChkd; ?> disabled="true">
                                            Run Already
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-check" style="font-size: 12px !important;">
                                        <label class="form-check-label">
                                            <?php
                                            $payMassPySentToGlChkd = "";
                                            if ($payMassPySentToGl == "1") {
                                                $payMassPySentToGlChkd = "checked=\"true\"";
                                            }
                                            ?>
                                            <input type="checkbox" class="form-check-input" id="payMassPySentToGl" name="payMassPySentToGl" <?php echo $payMassPySentToGlChkd; ?> disabled="true">
                                            Sent to GL
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:2px 0px 0px 0px !important;">
                                <label for="payMassPyIsQckPay" class="control-label col-md-3">Run Type:</label>
                                <div class="col-md-4">
                                    <div class="form-check" style="font-size: 12px !important;">
                                        <label class="form-check-label" title="Is a Quick Pay Run">
                                            <?php
                                            $payMassPyIsQckPayChkd = "";
                                            if ($payMassPyIsQckPay == true) {
                                                $payMassPyIsQckPayChkd = "checked=\"true\"";
                                            }
                                            ?>
                                            <input type="checkbox" class="form-check-input" id="payMassPyIsQckPay" name="payMassPyIsQckPay" <?php echo $payMassPyIsQckPayChkd; ?> onchange="shwHideQuckPayDivs();">
                                            Quick Pay
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-check" style="font-size: 12px !important;">
                                        <label class="form-check-label" title="Should Skip Automatic Assignment of Pay Elements to Persons">
                                            <?php
                                            $payMassPyAutoAsgngChkd = "";
                                            if ($payMassPyAutoAsgng == true) {
                                                $payMassPyAutoAsgngChkd = "checked=\"true\"";
                                            }
                                            ?>
                                            <input type="checkbox" class="form-check-input" id="payMassPyAutoAsgng" name="payMassPyAutoAsgng" <?php echo $payMassPyAutoAsgngChkd; ?>>
                                            Auto-Assign Items
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-md-12 <?php echo $quickPayCls; ?>" style="padding:0px 0px 0px 0px !important;" id="payMassPyIsQckPayDiv">
                        <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                            <div class="col-md-6" style="padding:0px 0px 0px 0px !important;">
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                    <label for="grpType" class="control-label col-md-4">Group Type:</label>
                                    <div class="col-md-8">
                                        <select class="form-control" id="payMassPyGrpType" onchange="payMassPyGrpTypChange();">
                                            <?php
                                            $valslctdArry = array("", "", "", "", "", "", "", "");
                                            $valuesArrys = array(
                                                "Everyone", "Single Person", "Divisions/Groups",
                                                "Grade", "Job", "Position", "Site/Location", "Person Type"
                                            );

                                            for ($z = 0; $z < count($valuesArrys); $z++) {
                                                if ($payMassPyGrpType == $valuesArrys[$z]) {
                                                    $valslctdArry[$z] = "selected";
                                                }
                                            ?>
                                                <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>>
                                                    <?php echo $valuesArrys[$z]; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                    <label for="payMassPyGroupName" class="control-label col-md-4">Group Name:</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="payMassPyGroupName" value="<?php echo $payMassPyGroupName; ?>" readonly="">
                                            <input type="hidden" id="payMassPyGroupID" value="<?php echo $payMassPyGroupID; ?>">
                                            <label id="payMassPyGroupNameLbl" class="btn btn-primary btn-file input-group-addon" onclick="getNoticeLovs('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payMassPyGroupID', 'payMassPyGroupName', 'clear', 1, '');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                    <label for="payMassPyWorkPlaceName" class="control-label col-md-4">Workplace Name:</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="payMassPyWorkPlaceName" value="<?php echo $payMassPyWorkPlaceName; ?>" readonly="true">
                                            <input type="hidden" id="payMassPyWorkPlaceID" value="<?php echo $payMassPyWorkPlaceID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'allOtherInputOrgID', '', '', 'radio', true, '', 'payMassPyWorkPlaceID', 'payMassPyWorkPlaceName', 'clear', 1, '');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                    <label for="payMassPyWorkPlaceSiteName" class="control-label col-md-4">Site:</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="payMassPyWorkPlaceSiteName" value="<?php echo $payMassPyWorkPlaceSiteName; ?>" readonly="true">
                                            <input type="hidden" id="payMassPyWorkPlaceSiteID" value="<?php echo $payMassPyWorkPlaceSiteID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Customer/Supplier Sites', 'payMassPyWorkPlaceID', '', '', 'radio', true, '', 'payMassPyWorkPlaceSiteID', 'payMassPyWorkPlaceSiteName', 'clear', 1, '');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" style="padding:0px 0px 0px 1px !important;">
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                    <div class="col-md-3">
                                        <label style="margin-bottom:0px !important;">Amt. Given:</label>
                                    </div>
                                    <div class="col-md-6" style="padding:0px 1px 0px 15px !important;">
                                        <div class="input-group">
                                            <label class="btn btn-default btn-file input-group-addon active">
                                                <span class="" style="font-size: 20px !important;" id="payMassPyCur"><?php echo $payMassPyCur; ?></span>
                                            </label>
                                            <input class="form-control" type="text" id="payMassPyAmntGvn" value="<?php
                                                                                                                    echo number_format($payMassPyAmntGvn, 2);
                                                                                                                    ?>" style="font-weight:bold;width:100%;font-size:18px !important;" onchange="fmtAsNumber('payMassPyAmntGvn');" />
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="padding:0px 15px 0px 2px !important;display:inline-table !important;vertical-align: middle !important;float: left !important;">
                                        <div class="form-check" style="font-size: 12px !important;float: left !important;">
                                            <label class="form-check-label" title="Keep and Apply Excess amounts as Advancve Payments">
                                                <?php
                                                $payMassPyKeepExcssChkd = "";
                                                if ($payMassPyKeepExcss == true) {
                                                    $payMassPyKeepExcssChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <input type="checkbox" class="form-check-input" id="payMassPyKeepExcss" name="payMassPyKeepExcss" <?php echo $payMassPyKeepExcssChkd; ?>>

                                            </label>
                                        </div>
                                        <div class="" style="margin-top:2px !important; float: left !important;font-weight:bold;cursor: pointer;color:red;" onclick="tickUntickChckBx('payMassPyKeepExcss');">
                                            <span>&nbsp;Keep All</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                    <div class="col-md-3">
                                        <label style="margin-bottom:0px !important;">Cheque:</label>
                                    </div>
                                    <div class="col-md-6" style="padding: 0px 2px 0px 15px !important;">
                                        <input type="text" class="form-control" aria-label="..." data-toggle="tooltip" title="Cheque/Card Number" id="payMassPyChqNumber" name="payMassPyChqNumber" value="<?php echo $payMassPyChqNumber; ?>" <?php echo $mkReadOnly; ?>>
                                    </div>
                                    <div class="col-md-3" style="padding: 0px 15px 0px 2px !important;">
                                        <input class="form-control" type="text" data-toggle="tooltip" title="Sign Code (CCV)" id="payMassPySignCode" value="<?php echo $payMassPySignCode; ?>" style="width:100%;" <?php echo $mkReadOnly; ?> />
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                    <div class="col-md-12">
                                        <div class="input-group" style="width:100% !important;">
                                            <label class="btn btn-primary btn-file input-group-addon" style="<?php echo $breadCrmbBckclr; ?>;min-width:28% !important;width:28% !important;">
                                                <span style="font-weight:bold;<?php echo $forecolors; ?>">Total
                                                    Amount:</span>
                                            </label>
                                            <input style="font-size:19px !important;font-weight:bold !important;width:100%;color:blue;" class="form-control" id="payMassPyTtlAmnt" type="text" placeholder="0.00" value="<?php
                                                                                                                                                                                                                            echo number_format($payMassPyTtlAmnt, 2);
                                                                                                                                                                                                                            ?>" readonly="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                    <div class="col-md-12">
                                        <div class="input-group" style="width:100% !important;">
                                            <label class="btn btn-primary btn-file input-group-addon" style="<?php echo $breadCrmbBckclr; ?>;min-width:28% !important;width:28% !important;">
                                                <span style="font-weight:bold;<?php echo $forecolors; ?>">Difference:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                            </label>
                                            <input style="font-size:16px !important;font-weight:bold !important;width:100% !important;color:<?php
                                                                                                                                            echo ($payMassPyNetAmnt <= 0 ? "green" : "red");
                                                                                                                                            ?>" class="form-control" id="payMassPyNetAmnt" type="text" placeholder="0.00" value="<?php
                                                                                                                                                                                                                                    echo number_format($payMassPyNetAmnt, 2);
                                                                                                                                                                                                                                    ?>" readonly="true">
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
                            <li class="active"><a data-toggle="tabajxpaymasspy" data-rhodata="" href="#payMassPyRunDets" id="payMassPyRunDetstab">Run Details</a></li>
                            <li class=""><a data-toggle="tabajxpaymasspy" data-rhodata="" href="#payMassPyAttchdVals" id="payMassPyAttchdValstab">Attached Values/Quick Pay Values</a></li>
                            <!--<li class=""><a data-toggle="tabajxpaymasspy" data-rhodata="" href="#payMassPyQckPayVals" id="payMassPyQckPayValstab">Quick Pay Values</a></li>-->
                        </ul>
                        <div class="custDiv" style="padding:0px !important;min-height: 30px !important;">
                            <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                            <div class="col-md-2" style="padding:0px 0px 0px 0px !important;float:left;">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default btn-sm" style="height: 30px !important;">
                                                        <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 5px; height:17px; width:20px; position: relative; vertical-align: middle;">
                                                        Run Reports
                                                    </button>
                                                    <button class="btn btn-info dropdown-toggle btn-sm" style="height:30px !important;" type="button" data-toggle="dropdown">
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu" style="margin-left: 15px !important;">
                                                        <li>
                                                            <a href="javascript:exprtPyRnAttchdVals();">
                                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Export Attached Values
                                                            </a>
                                                        </li>
                                                        <?php if ($payMassPyStatus == "0" && $canEdt === true) { ?>
                                                            <li>
                                                                <a href="javascript:importPyRnAttchdVals();">
                                                                    <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    Import Attached Values
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                        <li>
                                                            <a href="javascript:getSilentRptsRnSts(<?php echo $rptID7; ?>, -1, '<?php echo $paramStr7; ?>');">
                                                                <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Pay Run Results Details
                                                            </a>
                                                        </li>
                                                        <?php if ($vPsblVal1 == "TAKBG_SWLFR_APP_1") { ?>
                                                            <li>
                                                                <a href="javascript:getSilentRptsRnSts(<?php echo $rptID6; ?>, -1, '<?php echo $paramStr6; ?>');">
                                                                    <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    Welfare Loan Credit Letter
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:getSilentRptsRnSts(<?php echo $rptID69; ?>, -1, '<?php echo $paramStr69; ?>');">
                                                                    <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    General Welfare Credit Letter
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:getSilentRptsRnSts(<?php echo $rptID9; ?>, -1, '<?php echo $paramStr9; ?>');">
                                                                    <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    Welfare Loan Repayment Letter
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:getSilentRptsRnSts(<?php echo $rptID8; ?>, -1, '<?php echo $paramStr8; ?>');">
                                                                    <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    Semi-Month Welfare Contributions Letter
                                                                </a>
                                                            </li>
                                                        <?php } else { ?>
                                                            <li>
                                                                <a href="javascript:getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');">
                                                                    <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    Pay Run Master Sheet Report
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:getSilentRptsRnSts(<?php echo $rptID4; ?>, -1, '<?php echo $paramStr4; ?>');">
                                                                    <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    Pay Run Bank Crediting CSV File
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:getSilentRptsRnSts(<?php echo $rptID5; ?>, -1, '<?php echo $paramStr5; ?>');">
                                                                    <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    Pay Run Results Slips
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-md-7" style="padding:0px 0px 0px 0px !important;float:left;">
                                                <div class="col-md-10" style="padding:0px 0px 0px 0px !important;float:left;">
                                                    <div class="input-group">
                                                        <input class="form-control" id="payMassPyDtSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                                        echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                                        ?>" onkeyup="enterKeyFuncPayMassPyDt(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>')">
                                                        <input id="payMassPyDtPageNo1" type="hidden" value="<?php echo $pageNo1; ?>">
                                                        <input id="payMassPyDtPageNo2" type="hidden" value="<?php echo $pageNo2; ?>">
                                                        <input id="payMassPyDtPageNo3" type="hidden" value="<?php echo $pageNo3; ?>">
                                                        <input id="payMassPyDtTabNo" type="hidden" value="1">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getPayMassPyDt('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>')">
                                                            <span class="glyphicon glyphicon-remove"></span>
                                                        </label>
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getPayMassPyDt('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>')">
                                                            <span class="glyphicon glyphicon-search"></span>
                                                        </label>
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="payMassPyDtSrchIn">
                                                            <?php
                                                            $valslctdArry = array("", "");
                                                            $srchInsArrys = array("Person Name/ID No.", "Item Name");

                                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                if ($srchIn == $srchInsArrys[$z]) {
                                                                    $valslctdArry[$z] = "selected";
                                                                }
                                                            ?>
                                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="payMassPyDtDsplySze" style="min-width:70px !important;">
                                                            <?php
                                                            $valslctdArry = array("", "", "", "", "", "", "", "", "", "", "", "");
                                                            $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000, 5000, 10000, 10000000);
                                                            for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                                                if (100 == $dsplySzeArry[$y]) {
                                                                    $valslctdArry[$y] = "selected";
                                                                } else {
                                                                    $valslctdArry[$y] = "";
                                                                }
                                                            ?>
                                                                <option value="<?php echo $dsplySzeArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $dsplySzeArry[$y]; ?>
                                                                </option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2" style="padding:0px 0px 0px 0px !important;float:left;">
                                                    <nav aria-label="Page navigation">
                                                        <ul class="pagination" style="margin: 0px !important;">
                                                            <li>
                                                                <a class="rhopagination" href="javascript:getPayMassPyDt('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>');" aria-label="Previous">
                                                                    <span aria-hidden="true">&laquo;</span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="rhopagination" href="javascript:getPayMassPyDt('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>');" aria-label="Next">
                                                                    <span aria-hidden="true">&raquo;</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </nav>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="padding:0px 0px 0px 0px !important;">
                                                <div class="" style="padding:0px 0px 0px 0px;float:right !important;">
                                                    <?php
                                                    if ($payMassPyStatus == "0") {
                                                    ?>
                                                        <?php if ($canEdt) { ?>
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="savePayMassPyForm('<?php echo $fnccurnm; ?>', 0, -1, -1, '', '<?php echo $isDiagForm; ?>');" data-toggle="tooltip" data-placement="bottom" title="Save and Load Attached Values">
                                                                <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                                Save
                                                            </button>
                                                        <?php }
                                                        if ($payMassPyID > 0) { ?>
                                                            <button type="button" class="btn btn-default" style="display:none;margin-bottom: 1px;height:30px;" onclick="savePayMassPyForm('<?php echo $fnccurnm; ?>', 5,<?php echo $rptID2; ?>, -1, '<?php echo $paramStr2; ?>', '<?php echo $isDiagForm; ?>');" data-toggle="tooltip" data-placement="bottom" title="Load Attached Values">
                                                                <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Values&nbsp;
                                                            </button>
                                                            <?php if ($payMassPyIsQckPay == true) { ?>
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="savePayMassPyForm('<?php echo $fnccurnm; ?>', 5,<?php echo $rptID1; ?>, -1, '<?php echo $paramStr1; ?>', '<?php echo $isDiagForm; ?>');" data-toggle="tooltip" data-placement="bottom" title="Run Bulk Pay">
                                                                    <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Finalize&nbspQuick
                                                                    Pay
                                                                </button>
                                                            <?php } else { ?>
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="savePayMassPyForm('<?php echo $fnccurnm; ?>', 5,<?php echo $rptID1; ?>, -1, '<?php echo $paramStr1; ?>');" data-toggle="tooltip" data-placement="bottom" title="Run Bulk Pay">
                                                                    <img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Run
                                                                    Mass Pay&nbsp;
                                                                </button>
                                                            <?php             }
                                                        }
                                                    } else if ($payMassPyStatus == "1") {
                                                        $trnsCnt2 = 0;
                                                        $trnsCnt3 = 1;
                                                        if (strpos($payMassPyName, "Reversal") === FALSE) {
                                                            $strSql = "SELECT count(1) FROM pay.pay_itm_trnsctns a WHERE(a.pymnt_vldty_status='VALID' and a.mass_pay_id = " . $payMassPyID . " and a.src_py_trns_id<=0)";
                                                            $result1 = executeSQLNoParams($strSql);
                                                            while ($row = loc_db_fetch_array($result1)) {
                                                                $trnsCnt2 = (float) $row[0];
                                                            }
                                                        } else {
                                                            $strSql2 = "SELECT count(1) FROM pay.pay_itm_trnsctns a WHERE(a.pymnt_vldty_status='VALID' and a.mass_pay_id = " . $payMassPyID . " and a.src_py_trns_id>0)";
                                                            $result2 = executeSQLNoParams($strSql2);
                                                            while ($row = loc_db_fetch_array($result2)) {
                                                                $trnsCnt3 = (float) $row[0];
                                                            }
                                                        }
                                                        if ($trnsCnt2 > 0 || $trnsCnt3 <= 0) {
                                                            ?>
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="printPayPOSRcpt(<?php echo $payMassPyID; ?>);" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title="POS Receipt">
                                                                <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Receipt
                                                            </button>
                                                            <button id="fnlzeRvrslPayMassPyBtn" type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="savePayMassPyForm('<?php echo $fnccurnm; ?>', 7,<?php echo $rptID3; ?>, -1, '<?php echo $paramStr3; ?>', '<?php echo $isDiagForm; ?>');" data-toggle="tooltip" data-placement="bottom" title="Rollback Pay Run">
                                                                <img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Rollback&nbsp;
                                                            </button>
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
                        <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="onePayMassPyLnsTblSctn">
                            <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                <div id="payMassPyRunDets" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-striped table-bordered table-responsive" id="payMassPyRunDetsTable" cellspacing="0" width="100%" style="width:100%;min-width: 500px !important;">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>(ID No.) Person Name</th>
                                                        <th>Item Name</th>
                                                        <th>Item/Transaction Type</th>
                                                        <th style="text-align: right;">Amount Paid</th>
                                                        <th style="text-align: center;">UOM</th>
                                                        <th>Transaction Description</th>
                                                        <th>Validity</th>
                                                        <?php if ($canVwRcHstry) { ?>
                                                            <th>...</th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    //$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 100;
                                                    $total = get_One_MsPyDetTtl2($srchFor, $srchIn, $pkID);
                                                    //echo "::".$total."::".$pageNo1."::".$lmtSze;
                                                    if ($pageNo1 > ceil($total / $lmtSze)) {
                                                        $pageNo1 = 1;
                                                    } else if ($pageNo1 < 1) {
                                                        $pageNo1 = ceil($total / $lmtSze);
                                                    }

                                                    $curIdx = $pageNo1 - 1;
                                                    $cntr = 0;
                                                    $resultRw = get_One_MsPyDet2($srchFor, $srchIn, $curIdx, $lmtSze, $pkID);
                                                    $brghtTotal = 0;
                                                    $fnlColorAmntDffrnc = 0;
                                                    $prpsdTtlSpnColor = "black";
                                                    $itmTypCnt = getBatchItmTypCnt(-1, $pkID, -1);
                                                    while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                        $trsctnLineID = (float) $rowRw[0];
                                                        $trsctnLineIDNo = $rowRw[10];
                                                        $trsctnLinePrsnNm = $rowRw[11];
                                                        $trsctnLineItmNm = $rowRw[12];
                                                        $trsctnLineAmnt = (float) $rowRw[3];
                                                        $trsctnLineUOM = $rowRw[14];
                                                        $trsctnLineMinType = $rowRw[15];
                                                        $trsctnLineType = $rowRw[6];
                                                        $trsctnLineVldty = $rowRw[13];
                                                        $trsctnLineDesc = $rowRw[7];
                                                        $trsctnLineVldtyClr = "green";
                                                        if ($trsctnLineVldty == "VOID") {
                                                            $trsctnLineVldtyClr = "red";
                                                        }
                                                        $cntr += 1;
                                                    ?>
                                                        <tr id="payMassPyRunDetsRow_<?php echo $cntr; ?>">
                                                            <td class="lovtd">
                                                                <span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span>
                                                            </td>
                                                            <td class="lovtd">
                                                                <input type="hidden" class="form-control" aria-label="..." id="payMassPyRunDetsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                                                <span><?php echo $trsctnLinePrsnNm . " - [" . $trsctnLineIDNo . "]"; ?></span>
                                                            </td>
                                                            <td class="lovtd">
                                                                <span><?php echo ($trsctnLineItmNm); ?></span>
                                                            </td>
                                                            <td class="lovtd">
                                                                <span><?php echo ($trsctnLineMinType . " - " . $trsctnLineType); ?></span>
                                                            </td>
                                                            <td class="lovtd" style="text-align: right;font-weight:bold !important;">
                                                                <?php
                                                                echo getBatchNetAmnt(
                                                                    $itmTypCnt,
                                                                    $trsctnLineMinType,
                                                                    $trsctnLineItmNm,
                                                                    $rowRw[16],
                                                                    $trsctnLineAmnt,
                                                                    $brghtTotal,
                                                                    $prpsdTtlSpnColor
                                                                );
                                                                ?>
                                                            </td>
                                                            <td class="lovtd">
                                                                <div class="" style="width:100% !important;">
                                                                    <label class="btn btn-primary btn-file">
                                                                        <span class="" id="payMassPyRunDetsRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $trsctnLineUOM; ?></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="lovtd">
                                                                <span><?php echo $trsctnLineDesc; ?></span>
                                                            </td>
                                                            <td class="lovtd" style="font-weight:bold;color:<?php echo $trsctnLineVldtyClr; ?>">
                                                                <span><?php echo $trsctnLineVldty; ?></span>
                                                            </td>
                                                            <?php
                                                            if ($canVwRcHstry === true) {
                                                            ?>
                                                                <td class="lovtd">
                                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                            echo urlencode(encrypt1(($trsctnLineID . "|pay.pay_itm_trnsctns|pay_trns_id"),
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
                                </div>
                                <div id="payMassPyAttchdVals" class="tab-pane fadein" style="border:none !important;padding:0px !important;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="table table-striped table-bordered table-responsive" id="payMassPyAttchdValsTable" cellspacing="0" width="100%" style="width:100%;min-width: 500px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th>No.</th>
                                                                <th>(ID No.) Person Name</th>
                                                                <th>Item Name</th>
                                                                <th>Item Type</th>
                                                                <th>Item Value Name</th>
                                                                <th style="text-align: right;">Value/Amount to Use</th>
                                                                <th>...</th>
                                                                <?php
                                                                if ($canVwRcHstry === true) {
                                                                ?>
                                                                    <th>&nbsp;</th>
                                                                <?php } ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            //$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 100;
                                                            $total = get_Total_MsPayAtchdVals($srchFor, $srchIn, $pkID);
                                                            //echo "::".$total."::".$pageNo1."::".$lmtSze;
                                                            if ($pageNo2 > ceil($total / $lmtSze)) {
                                                                $pageNo2 = 1;
                                                            } else if ($pageNo2 < 1) {
                                                                $pageNo2 = ceil($total / $lmtSze);
                                                            }

                                                            $curIdx = $pageNo2 - 1;
                                                            $resultRw = get_One_MsPayAtchdVals($srchFor, $srchIn, $curIdx, $lmtSze, $pkID);
                                                            $cntr = 0;
                                                            $mkReadOnly = "";
                                                            if ($payMassPyStatus == "1") {
                                                                $mkReadOnly = "readonly=\"true\"";
                                                            }

                                                            $itmTypCnt = getBatchItmTypCnt1($pkID);
                                                            $brghtTotal = 0;
                                                            $prpsdTtlSpnColor = "";
                                                            while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                                $trsctnLineID = (float) $rowRw[0];
                                                                $trsctnLinePrsnID = (float) $rowRw[2];
                                                                $trsctnLineItmID = (float) $rowRw[5];
                                                                $trsctnLineItmValID = (float) $rowRw[7];
                                                                $trsctnLineIDNo = $rowRw[3];
                                                                $trsctnLinePrsnNm = $rowRw[4];
                                                                $trsctnLineItmNm = $rowRw[6];
                                                                $trsctnLineItmValNm = $rowRw[8];
                                                                $trsctnLineMinType = $rowRw[10];
                                                                $trsctnLineAmnt = (float) $rowRw[9];
                                                                $cntr += 1;
                                                                $trsctnAmnt = getBatchNetAmnt(
                                                                    $itmTypCnt,
                                                                    $trsctnLineMinType,
                                                                    $trsctnLineItmNm,
                                                                    $rowRw[11],
                                                                    $trsctnLineAmnt,
                                                                    $brghtTotal,
                                                                    $prpsdTtlSpnColor
                                                                );
                                                                $trsctnLineIsValHidden = $mkReadOnly;
                                                                if ($rowRw[13] == "0" && $mkReadOnly == "") {
                                                                    $trsctnLineIsValHidden = "readonly=\"true\"";
                                                                }
                                                            ?>
                                                                <tr id="payMassPyAttchdValsRow_<?php echo $cntr; ?>">
                                                                    <td class="lovtd">
                                                                        <span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="payMassPyAttchdValsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="payMassPyAttchdValsRow<?php echo $cntr; ?>_PrsnID" value="<?php echo $trsctnLinePrsnID; ?>" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="payMassPyAttchdValsRow<?php echo $cntr; ?>_ItemID" value="<?php echo $trsctnLineItmID; ?>" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="payMassPyAttchdValsRow<?php echo $cntr; ?>_ItemValID" value="<?php echo $trsctnLineItmValID; ?>" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="payMassPyAttchdValsRow<?php echo $cntr; ?>_CanEdt" value="<?php echo $rowRw[13]; ?>" style="width:100% !important;">
                                                                        <span><?php echo $trsctnLinePrsnNm . " - [" . $trsctnLineIDNo . "]"; ?></span>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <span><?php echo ($trsctnLineItmNm); ?></span>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <span><?php echo ($trsctnLineMinType); ?></span>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <span><?php echo ($trsctnLineItmValNm); ?></span>
                                                                    </td>
                                                                    <td class="lovtd" style="text-align: right;">
                                                                        <input type="text" class="form-control  jbDetDbt" id="payMassPyAttchdValsRow<?php echo $cntr; ?>_ValToUse" onkeypress="gnrlFldKeyPress(event, 'payMassPyAttchdValsRow<?php echo $cntr; ?>_ValToUse', 'payMassPyAttchdValsTable', 'jbDetDbt');" onblur="fmtAsNumber('payMassPyAttchdValsRow<?php echo $cntr; ?>_ValToUse');" style="font-weight:bold;font-size:12px;color:blue;text-align: right;" value="<?php echo number_format($trsctnLineAmnt, 2); ?>" <?php echo $trsctnLineIsValHidden; ?> />
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delPayMassPyAttchdVal('payMassPyAttchdValsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Attached Value">
                                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                    </td>
                                                                    <?php
                                                                    if ($canVwRcHstry === true) {
                                                                    ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                                    echo urlencode(encrypt1(($trsctnLineID . "|pay.pay_value_sets_det|value_set_det_id"),
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
                                                        <tfoot>
                                                            <tr>
                                                                <th>&nbsp;</th>
                                                                <th>&nbsp;</th>
                                                                <th>&nbsp;</th>
                                                                <th>&nbsp;</th>
                                                                <th>Net Amount (<?php echo $fnccurnm; ?>):
                                                                </th>
                                                                <th style="text-align: right;">
                                                                    <?php
                                                                    echo "<span style=\"color:$prpsdTtlSpnColor;font-weight:bold;font-size:14px;\" id=\"payMassPyAttchdValsTtlBtn\">" . number_format(
                                                                        $brghtTotal,
                                                                        2,
                                                                        '.',
                                                                        ','
                                                                    ) . "</span>";
                                                                    ?>
                                                                    <input type="hidden" id="payMassPyAttchdValsTtlVal" value="<?php echo $brghtTotal; ?>">
                                                                </th>
                                                                <th>&nbsp;</th>
                                                                <?php
                                                                if ($canVwRcHstry === true) {
                                                                ?>
                                                                    <th>&nbsp;</th>
                                                                <?php } ?>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="payMassPyQckPayVals" class="tab-pane fadein" style="border:none !important;padding:0px !important;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            Editable Table of Quick Pay Values
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            } else if ($vwtyp == 101) {
                $pkID = isset($_POST['sbmtdMassPayRunID']) ? $_POST['sbmtdMassPayRunID'] : -1;
                //var_dump($_POST);
            ?>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-responsive" id="payMassPyRunDetsTable" cellspacing="0" width="100%" style="width:100%;min-width: 500px !important;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>(ID No.) Person Name</th>
                                    <th>Item Name</th>
                                    <th>Item/Transaction Type</th>
                                    <th style="text-align: right;">Amount Paid</th>
                                    <th style="text-align: center;">UOM</th>
                                    <th>Transaction Description</th>
                                    <th>Validity</th>
                                    <?php if ($canVwRcHstry) { ?>
                                        <th>...</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 100;
                                $total = get_One_MsPyDetTtl2($srchFor, $srchIn, $pkID);
                                //echo "::".$total."::".$pageNo1."::".$lmtSze;
                                if ($pageNo1 > ceil($total / $lmtSze)) {
                                    $pageNo1 = 1;
                                } else if ($pageNo1 < 1) {
                                    $pageNo1 = ceil($total / $lmtSze);
                                }

                                $curIdx = $pageNo1 - 1;
                                $resultRw = get_One_MsPyDet2($srchFor, $srchIn, $curIdx, $lmtSze, $pkID);
                                $cntr = 0;
                                $brghtTotal = 0;
                                $fnlColorAmntDffrnc = 0;
                                $prpsdTtlSpnColor = "black";
                                $itmTypCnt = getBatchItmTypCnt(-1, $pkID, -1);
                                while ($rowRw = loc_db_fetch_array($resultRw)) {
                                    $trsctnLineID = (float) $rowRw[0];
                                    $trsctnLineIDNo = $rowRw[10];
                                    $trsctnLinePrsnNm = $rowRw[11];
                                    $trsctnLineItmNm = $rowRw[12];
                                    $trsctnLineAmnt = (float) $rowRw[3];
                                    $trsctnLineUOM = $rowRw[14];
                                    $trsctnLineMinType = $rowRw[15];
                                    $trsctnLineType = $rowRw[6];
                                    $trsctnLineVldty = $rowRw[13];
                                    $trsctnLineDesc = $rowRw[7];
                                    $trsctnLineVldtyClr = "green";
                                    if ($trsctnLineVldty == "VOID") {
                                        $trsctnLineVldtyClr = "red";
                                    }
                                    $cntr += 1;
                                ?>
                                    <tr id="payMassPyRunDetsRow_<?php echo $cntr; ?>">
                                        <td class="lovtd">
                                            <span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span>
                                        </td>
                                        <td class="lovtd">
                                            <input type="hidden" class="form-control" aria-label="..." id="payMassPyRunDetsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                            <span><?php echo $trsctnLinePrsnNm . " - [" . $trsctnLineIDNo . "]"; ?></span>
                                        </td>
                                        <td class="lovtd">
                                            <span><?php echo ($trsctnLineItmNm); ?></span>
                                        </td>
                                        <td class="lovtd">
                                            <span><?php echo ($trsctnLineMinType . " - " . $trsctnLineType); ?></span>
                                        </td>
                                        <td class="lovtd" style="text-align: right;font-weight:bold !important;">
                                            <?php
                                            echo getBatchNetAmnt(
                                                $itmTypCnt,
                                                $trsctnLineMinType,
                                                $trsctnLineItmNm,
                                                $rowRw[16],
                                                $trsctnLineAmnt,
                                                $brghtTotal,
                                                $prpsdTtlSpnColor
                                            );
                                            ?>
                                        </td>
                                        <td class="lovtd">
                                            <div class="" style="width:100% !important;">
                                                <label class="btn btn-primary btn-file">
                                                    <span class="" id="payMassPyRunDetsRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $trsctnLineUOM; ?></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="lovtd">
                                            <span><?php echo $trsctnLineDesc; ?></span>
                                        </td>
                                        <td class="lovtd" style="font-weight:bold;color:<?php echo $trsctnLineVldtyClr; ?>">
                                            <span><?php echo $trsctnLineVldty; ?></span>
                                        </td>
                                        <?php if ($canVwRcHstry === true) { ?>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                        echo urlencode(encrypt1(($trsctnLineID . "|pay.pay_itm_trnsctns|pay_trns_id"),
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
                <script type="text/javascript">
                    $("#payMassPyDtPageNo1").val(<?php echo $pageNo1; ?>);
                </script>
            <?php
            } else if ($vwtyp == 102) {
                $pkID = isset($_POST['sbmtdMassPayRunID']) ? $_POST['sbmtdMassPayRunID'] : -1;
                $payMassPyStatus = getGnrlRecNm("pay.pay_mass_pay_run_hdr", "mass_pay_id", "run_status", $pkID);
                //var_dump($_POST);
            ?>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-responsive" id="payMassPyAttchdValsTable" cellspacing="0" width="100%" style="width:100%;min-width: 500px !important;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>(ID No.) Person Name</th>
                                    <th>Item Name</th>
                                    <th>Item Type</th>
                                    <th>Item Value Name</th>
                                    <th style="text-align: right;">Value/Amount to Use</th>
                                    <th>...</th>
                                    <?php if ($canVwRcHstry === true) { ?>
                                        <th>&nbsp;</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 100;
                                $total = get_Total_MsPayAtchdVals($srchFor, $srchIn, $pkID);
                                //echo "::".$total."::".$pageNo1."::".$lmtSze;
                                if ($pageNo2 > ceil($total / $lmtSze)) {
                                    $pageNo2 = 1;
                                } else if ($pageNo2 < 1) {
                                    $pageNo2 = ceil($total / $lmtSze);
                                }

                                $curIdx = $pageNo2 - 1;
                                $resultRw = get_One_MsPayAtchdVals($srchFor, $srchIn, $curIdx, $lmtSze, $pkID);
                                $cntr = 0;
                                $mkReadOnly = "";
                                if ($payMassPyStatus == "1") {
                                    $mkReadOnly = "readonly=\"true\"";
                                }

                                $itmTypCnt = getBatchItmTypCnt1($pkID);
                                $brghtTotal = 0;
                                $prpsdTtlSpnColor = "";
                                while ($rowRw = loc_db_fetch_array($resultRw)) {
                                    $trsctnLineID = (float) $rowRw[0];
                                    $trsctnLinePrsnID = (float) $rowRw[2];
                                    $trsctnLineItmID = (float) $rowRw[5];
                                    $trsctnLineItmValID = (float) $rowRw[7];
                                    $trsctnLineIDNo = $rowRw[3];
                                    $trsctnLinePrsnNm = $rowRw[4];
                                    $trsctnLineItmNm = $rowRw[6];
                                    $trsctnLineItmValNm = $rowRw[8];
                                    $trsctnLineMinType = $rowRw[10];
                                    $trsctnLineAmnt = (float) $rowRw[9];
                                    $cntr += 1;
                                    $trsctnAmnt = getBatchNetAmnt(
                                        $itmTypCnt,
                                        $trsctnLineMinType,
                                        $trsctnLineItmNm,
                                        $rowRw[11],
                                        $trsctnLineAmnt,
                                        $brghtTotal,
                                        $prpsdTtlSpnColor
                                    );
                                    $trsctnLineIsValHidden = $mkReadOnly;
                                    if ($rowRw[13] == "0" && $mkReadOnly == "") {
                                        $trsctnLineIsValHidden = "readonly=\"true\"";
                                    }
                                ?>
                                    <tr id="payMassPyAttchdValsRow_<?php echo $cntr; ?>">
                                        <td class="lovtd">
                                            <span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span>
                                        </td>
                                        <td class="lovtd">
                                            <input type="hidden" class="form-control" aria-label="..." id="payMassPyAttchdValsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLineID; ?>" style="width:100% !important;">
                                            <input type="hidden" class="form-control" aria-label="..." id="payMassPyAttchdValsRow<?php echo $cntr; ?>_PrsnID" value="<?php echo $trsctnLinePrsnID; ?>" style="width:100% !important;">
                                            <input type="hidden" class="form-control" aria-label="..." id="payMassPyAttchdValsRow<?php echo $cntr; ?>_ItemID" value="<?php echo $trsctnLineItmID; ?>" style="width:100% !important;">
                                            <input type="hidden" class="form-control" aria-label="..." id="payMassPyAttchdValsRow<?php echo $cntr; ?>_ItemValID" value="<?php echo $trsctnLineItmValID; ?>" style="width:100% !important;">
                                            <input type="hidden" class="form-control" aria-label="..." id="payMassPyAttchdValsRow<?php echo $cntr; ?>_CanEdt" value="<?php echo $rowRw[13]; ?>" style="width:100% !important;">
                                            <span><?php echo $trsctnLinePrsnNm . " - [" . $trsctnLineIDNo . "]"; ?></span>
                                        </td>
                                        <td class="lovtd">
                                            <span><?php echo ($trsctnLineItmNm); ?></span>
                                        </td>
                                        <td class="lovtd">
                                            <span><?php echo ($trsctnLineMinType); ?></span>
                                        </td>
                                        <td class="lovtd">
                                            <span><?php echo ($trsctnLineItmValNm); ?></span>
                                        </td>
                                        <td class="lovtd" style="text-align: right;">
                                            <input type="text" class="form-control  jbDetDbt" id="payMassPyAttchdValsRow<?php echo $cntr; ?>_ValToUse" onkeypress="gnrlFldKeyPress(event, 'payMassPyAttchdValsRow<?php echo $cntr; ?>_ValToUse', 'payMassPyAttchdValsTable', 'jbDetDbt');" onblur="fmtAsNumber('payMassPyAttchdValsRow<?php echo $cntr; ?>_ValToUse');" style="font-weight:bold;font-size:12px;color:blue;text-align: right;" value="<?php echo number_format($trsctnLineAmnt, 2); ?>" <?php echo $trsctnLineIsValHidden; ?> />
                                        </td>
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delPayMassPyAttchdVal('payMassPyAttchdValsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Attached Value">
                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                        </td>
                                        <?php if ($canVwRcHstry === true) { ?>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                        echo urlencode(encrypt1(($trsctnLineID . "|pay.pay_value_sets_det|value_set_det_id"),
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
                            <tfoot>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>Net Amount (<?php echo $fnccurnm; ?>):</th>
                                    <th style="text-align: right;">
                                        <?php
                                        echo "<span style=\"color:$prpsdTtlSpnColor;font-weight:bold;font-size:14px;\" id=\"payMassPyAttchdValsTtlBtn\">" . number_format(
                                            $brghtTotal,
                                            2,
                                            '.',
                                            ','
                                        ) . "</span>";
                                        ?>
                                        <input type="hidden" id="payMassPyAttchdValsTtlVal" value="<?php echo $brghtTotal; ?>">
                                    </th>
                                    <th>&nbsp;</th>
                                    <?php
                                    if ($canVwRcHstry === true) {
                                    ?>
                                        <th>&nbsp;</th>
                                    <?php } ?>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <script type="text/javascript">
                    $("#payMassPyDtPageNo2").val(<?php echo $pageNo2; ?>);
                </script>
<?php
            } else if ($vwtyp == 3) {
            } else if ($vwtyp == 4) {
            } else if ($vwtyp == 702) {
                //Print POS Rcpt        
                $vPsblValID1 = getEnbldPssblValID("Html POS Receipt File Name", getLovID("All Other Internal Payment Setups"));
                $vPsblVal1 = getPssblValDesc($vPsblValID1);
                if ($vPsblVal1 == "") {
                    $vPsblVal1 = 'htm_rpts/pay_pos_rpt.php';
                }
                require $vPsblVal1;
            }
        }
    }
}
