<?php
$canAdd = test_prmssns($dfltPrvldgs[31], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[32], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[33], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
$assessTypeFltr = "Assessment Sheet Per Group";
$exprtFileNmPrt = "AssessSheets";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Assessment Sheet */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteAssessShtHdr($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save CONTINUOUS ASSESSMENT SHEETS
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $assessShtHdrID = isset($_POST['assessShtHdrID']) ? (float) cleanInputData($_POST['assessShtHdrID']) : -1;
                $assessSheetStatus = getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assess_sheet_status", $assessShtHdrID);
                if ($assessSheetStatus === "Closed") {
                    $canEdt = false;
                }
                $assessShtHdrName = isset($_POST['assessShtHdrName']) ? cleanInputData($_POST['assessShtHdrName']) : "";
                $assessShtHdrDesc = isset($_POST['assessShtHdrDesc']) ? cleanInputData($_POST['assessShtHdrDesc']) : "";
                $assessShtHdrTypeID = isset($_POST['assessShtHdrTypeID']) ? (int) cleanInputData($_POST['assessShtHdrTypeID']) : -1;
                $assessShtHdrTypeNm = isset($_POST['assessShtHdrTypeNm']) ? cleanInputData($_POST['assessShtHdrTypeNm']) : "";

                $assessShtHdrPeriodID = isset($_POST['assessShtHdrPeriodID']) ? (int) cleanInputData($_POST['assessShtHdrPeriodID']) : -1;
                $assessShtHdrPeriodNm = isset($_POST['assessShtHdrPeriodNm']) ? cleanInputData($_POST['assessShtHdrPeriodNm']) : "";

                $assessShtHdrPrsnID = isset($_POST['assessShtHdrPrsnID']) ? (int) cleanInputData($_POST['assessShtHdrPrsnID']) : -1;
                $assessShtHdrPrsnNm = isset($_POST['assessShtHdrPrsnNm']) ? cleanInputData($_POST['assessShtHdrPrsnNm']) : "";

                $assessShtHdrClassID = isset($_POST['assessShtHdrClassID']) ? (int) cleanInputData($_POST['assessShtHdrClassID']) : -1;
                $assessShtHdrClassNm = isset($_POST['assessShtHdrClassNm']) ? cleanInputData($_POST['assessShtHdrClassNm']) : "";

                $assessShtHdrCrseID = isset($_POST['assessShtHdrCrseID']) ? (int) cleanInputData($_POST['assessShtHdrCrseID']) : -1;
                $assessShtHdrCrseNm = isset($_POST['assessShtHdrCrseNm']) ? cleanInputData($_POST['assessShtHdrCrseNm']) : "";
                $assessShtHdrSbjctID = isset($_POST['assessShtHdrSbjctID']) ? (int) cleanInputData($_POST['assessShtHdrSbjctID']) : -1;
                $assessShtHdrSbjctNm = isset($_POST['assessShtHdrSbjctNm']) ? cleanInputData($_POST['assessShtHdrSbjctNm']) : "";
                $assessShtHdrStatus = isset($_POST['assessShtHdrStatus']) ? cleanInputData($_POST['assessShtHdrStatus']) : "Open for Editing";
                $assessShtHdrAsdPrsID = isset($_POST['assessShtHdrAsdPrsID']) ? (float) cleanInputData($_POST['assessShtHdrAsdPrsID']) : -1;
                $assessShtHdrAsdPrsNm = isset($_POST['assessShtHdrAsdPrsNm']) ? cleanInputData($_POST['assessShtHdrAsdPrsNm']) : "";

                $slctdHdrFtrValues = isset($_POST['slctdHdrFtrValues']) ? cleanInputData($_POST['slctdHdrFtrValues']) : "";
                $slctdDetLineValues = isset($_POST['slctdDetLineValues']) ? cleanInputData($_POST['slctdDetLineValues']) : "";
                $shdSbmt = isset($_POST['shdSbmt']) ? (int) cleanInputData($_POST['shdSbmt']) : 0;
                $exitErrMsg = "";
                if ($canEdt === true) {
                    if ($assessShtHdrName == "" || $assessShtHdrDesc == "") {
                        $exitErrMsg .= "Please enter Assessment Sheet Name and Description!<br/>";
                    }
                    if ($assessShtHdrTypeID <= 0 || $assessShtHdrPeriodID <= 0) {
                        $exitErrMsg .= "Please enter Assessment Type and Period!<br/>";
                    }
                    if ($assessShtHdrClassID <= 0 || $assessShtHdrCrseID <= 0 || $assessShtHdrSbjctID <= 0) {
                        $exitErrMsg .= "Please enter $groupLabel/$courseLabel and $sbjctLabel !<br/>";
                    }
                    if (trim($exitErrMsg) !== "") {
                        $arr_content['percent'] = 100;
                        $arr_content['assessShtHdrID'] = $assessShtHdrID;
                        $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                        echo json_encode($arr_content);
                        exit();
                    }
                }
                $oldID = (float) getGnrlRecID("aca.aca_assess_sheet_hdr", "assess_sheet_name", "assess_sheet_hdr_id", $assessShtHdrName, $orgID);
                $afftctd = 0;
                if (($oldID <= 0 || $oldID == $assessShtHdrID)) {
                    if ($canEdt === true) {
                        if ($assessShtHdrID <= 0) {
                            $assessShtHdrID = getNew_AssessShtHdrID();
                            $afftctd += createAssessShtHdr(
                                $assessShtHdrID,
                                $assessShtHdrName,
                                $assessShtHdrDesc,
                                $assessShtHdrClassID,
                                $assessShtHdrTypeID,
                                $assessShtHdrCrseID,
                                $assessShtHdrSbjctID,
                                $assessShtHdrPrsnID,
                                $assessShtHdrPeriodID,
                                $orgID,
                                $assessShtHdrStatus,
                                $assessShtHdrAsdPrsID
                            );
                        } else {
                            $afftctd += updtAssessShtHdr(
                                $assessShtHdrID,
                                $assessShtHdrName,
                                $assessShtHdrDesc,
                                $assessShtHdrClassID,
                                $assessShtHdrTypeID,
                                $assessShtHdrCrseID,
                                $assessShtHdrSbjctID,
                                $assessShtHdrPrsnID,
                                $assessShtHdrPeriodID,
                                $orgID,
                                $assessShtHdrStatus,
                                $assessShtHdrAsdPrsID
                            );
                        }
                        $afftctd1 = 0;
                        $afftctd2 = 0;
                        $errMsg = "";

                        $adDataExsts = 0;
                        $data_cols = array(
                            "", "", "", "", "", "", "", "", "", "",
                            "", "", "", "", "", "", "", "", "", "",
                            "", "", "", "", "", "", "", "", "", "",
                            "", "", "", "", "", "", "", "", "", "",
                            "", "", "", "", "", "", "", "", "", "", ""
                        );
                        if (trim($slctdHdrFtrValues, "|~") != "" && $assessShtHdrID > 0) {
                            $variousRows = explode("|", trim($slctdHdrFtrValues, "|"));
                            for ($y = 0; $y < count($variousRows); $y++) {
                                $crntRow = explode("~", $variousRows[$y]);
                                if (count($crntRow) == 2) {
                                    $ln_Key = (int) (cleanInputData1($crntRow[0]));
                                    $ln_Value = cleanInputData1($crntRow[1]);
                                    if ($ln_Value != "") {
                                        if ($ln_Key > 0) {
                                            $data_cols[$ln_Key] = $ln_Value;
                                            $adDataExsts++;
                                        }
                                    }
                                }
                            }
                        }
                        if ($adDataExsts > 0) {
                            //Header and Footer
                            $afftctd1 += updateAsessColsData1($assessShtHdrID, -1, $data_cols);
                        }
                        if (trim($slctdDetLineValues, "|~") != "" && $assessShtHdrID > 0) {
                            $variousRows = explode("|", trim($slctdDetLineValues, "|"));
                            for ($y = 0; $y < count($variousRows); $y++) {
                                $data_cols = array(
                                    "", "", "", "", "", "", "", "", "", "",
                                    "", "", "", "", "", "", "", "", "", "",
                                    "", "", "", "", "", "", "", "", "", "",
                                    "", "", "", "", "", "", "", "", "", "",
                                    "", "", "", "", "", "", "", "", "", "", ""
                                );
                                $crntRow = explode("~", $variousRows[$y]);
                                if (count($crntRow) > 0) {
                                    $ln_LineID = (float) (cleanInputData1($crntRow[0]));
                                    $ln_PrsnID = (float) cleanInputData1($crntRow[1]);
                                    $ln_TtlCols = (float) cleanInputData1($crntRow[2]);
                                    $ln_PrsnNm = cleanInputData1($crntRow[$ln_TtlCols + 3]);
                                    for ($x = 0; $x < $ln_TtlCols; $x++) {
                                        $ln_Arry = explode("#", trim(cleanInputData1($crntRow[$x + 3]), "#"));
                                        $ln_Key = (int) $ln_Arry[0];
                                        $ln_Value = $ln_Arry[1];
                                        $data_cols[$ln_Key] = cleanInputData2($ln_Value);
                                    }
                                    if ($ln_LineID > 0) {
                                        $afftctd2 += updateAsessColsData($ln_LineID, $data_cols);
                                    }
                                }
                            }
                        }
                    }
                    if ($exitErrMsg != "") {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Assessment Sheet Successfully Saved!"
                            . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    } else {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Assessment Sheet Successfully Saved!";
                    }
                    if ($shdSbmt == 2) {
                        $exitErrMsg = compute_one_assess_sht($assessShtHdrID, $usrID);
                        if (strpos($exitErrMsg, "SUCCESS") !== FALSE) {
                            $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                        } else {
                            $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                        }
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['assessShtHdrID'] = $assessShtHdrID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Either the New Assessment Sheet Name is in Use <br/>or Data Supplied is Incomplete!</span>";
                    $arr_content['percent'] = 100;
                    $arr_content['assessShtHdrID'] = $assessShtHdrID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 1001) {
                //Export
                $assessSbmtdSheetID = isset($_POST['assessSbmtdSheetID']) ? $_POST['assessSbmtdSheetID'] : -1;
                $assessSbmtdSheetNm = isset($_POST['assessSbmtdSheetNm']) ? $_POST['assessSbmtdSheetNm'] : "";
                $assessTypeID = (int) getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assessment_type_id", $assessSbmtdSheetID);
                $assessSheetType = getGnrlRecNm("aca.aca_assessment_types", "assmnt_typ_id", "assmnt_typ_nm", $assessTypeID);
                $assessSheetStatus = getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assess_sheet_status", $assessSbmtdSheetID);
                $assessSheetPrsnNm = getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "prs.get_prsn_name(tutor_person_id) || ' (' || prs.get_prsn_loc_id(tutor_person_id)||')'", $assessSbmtdSheetID);
                $inptNum = isset($_POST['inptNum']) ? (int) cleanInputData($_POST['inptNum']) : 0;
                session_write_close();
                $affctd = 0;
                $errMsg = "Invalid Option!";
                if ($inptNum >= 0) {
                    $rndm = getRandomNum(10001, 9999999);
                    $dteNm = date('dMY_His');
                    $nwFileNm = $fldrPrfx . "dwnlds/tmp/" . $exprtFileNmPrt . "Exprt_" . $dteNm . "_" . $rndm . ".csv";
                    $dwnldUrl = $app_url . "dwnlds/tmp/" . $exprtFileNmPrt . "Exprt_" . $dteNm . "_" . $rndm . ".csv";
                    $opndfile = fopen($nwFileNm, "w");

                    $hdngs1 = array_fill(0, 2, "");
                    $hdngs1[0] = "Assessment Sheet Name:";
                    $hdngs1[1] = $assessSbmtdSheetNm;
                    fputcsv($opndfile, $hdngs1);

                    $hdngs2 = array_fill(0, 2, "");
                    $hdngs2[0] = "Assessment Sheet Type:";
                    $hdngs2[1] = $assessSheetType;
                    fputcsv($opndfile, $hdngs2);

                    $hdngs21 = array_fill(0, 2, "");
                    $hdngs21[0] = "Assessment Administrator:";
                    $hdngs21[1] = $assessSheetPrsnNm;
                    fputcsv($opndfile, $hdngs21);

                    $hdngs3 = array_fill(0, 2, "");
                    $hdngs3[0] = "Assessment Sheet Status:";
                    $hdngs3[1] = $assessSheetStatus;
                    fputcsv($opndfile, $hdngs3);
                    $result1 = get_AssessShtGrpCols("01-Header", $assessTypeID);
                    $cntr1 = 0;
                    $gcntr1 = 0;
                    $cntr1Ttl = loc_db_num_rows($result1);
                    $academicSttngID = -1;
                    while ($row1 = loc_db_fetch_array($result1)) {
                        $hdngs4 = array_fill(0, 2, "");
                        $hdngs4[0] = $row1[2];

                        $columnID = (int) $row1[0];
                        $columnNo = (int) $row1[15];
                        $prsnDValPulld = get_AssessShtColVal($assessSbmtdSheetID, $academicSttngID, $columnNo);

                        $hdngs4[1] = $prsnDValPulld;
                        fputcsv($opndfile, $hdngs4);
                        $cntr1 += 1;
                    }
                    $hdngs5 = array_fill(0, 2, "");
                    fputcsv($opndfile, $hdngs5);
                    $resultHdr = get_AssessShtGrpCols("02-Detail", $assessTypeID);
                    $colscntr1 = 0;
                    $ttlColS = loc_db_num_rows($resultHdr);
                    $colsIDs = array_fill(0, $ttlColS, -1);
                    $colNos = array_fill(0, $ttlColS, 1);
                    $colsNames = array_fill(0, $ttlColS, "");
                    $colsTypes = array_fill(0, $ttlColS, "");
                    $colsIsFrmlr = array_fill(0, $ttlColS, "1");
                    $colMinVals = array_fill(0, $ttlColS, 0);
                    $colMaxVals = array_fill(0, $ttlColS, 0);
                    $colsIsDsplyd = array_fill(0, $ttlColS, "1");
                    $colsHtmlCss = array_fill(0, $ttlColS, "");
                    while ($rwHdr = loc_db_fetch_array($resultHdr)) {
                        $colsIDs[$colscntr1] = (int) $rwHdr[0];
                        $colsNames[$colscntr1] = $rwHdr[2];
                        $colsTypes[$colscntr1] = $rwHdr[4];
                        $colsIsFrmlr[$colscntr1] = $rwHdr[13];
                        $colNos[$colscntr1] = (int) $rwHdr[15];
                        $colMinVals[$colscntr1] = (int) $rwHdr[16];
                        $colMaxVals[$colscntr1] = (int) $rwHdr[17];
                        $colsIsDsplyd[$colscntr1] = $rwHdr[18];
                        $colsHtmlCss[$colscntr1] = $rwHdr[19];
                        $colscntr1++;
                    }
                    $colscntr = 0;
                    $hdngs = array_fill(0, ($ttlColS + 2), "");
                    $hdngs[0] = "Rec. No.**";
                    $hdngs[1] = "Person Full Name (ID No.**)";
                    while ($colscntr < count($colsNames)) {
                        $tdStyle = "";
                        if ($colsIsDsplyd[$colscntr] == "0") {
                            $tdStyle = "display:none;";
                        }
                        $hdngs[$colscntr + 2] = $colsNames[$colscntr];
                        $colscntr++;
                    }
                    $limit_size = 0;
                    if ($inptNum > 2) {
                        $limit_size = $inptNum;
                    } else if ($inptNum == 2) {
                        $limit_size = 1000000;
                    }
                    fputcsv($opndfile, $hdngs);
                    /* if ($limit_size <= 0) {
                      $arr_content['percent'] = 100;
                      $arr_content['dwnld_url'] = $dwnldUrl;
                      $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!... Template Exported.</span>";
                      $arr_content['msgcount'] = 0;
                      file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_" . $exprtFileNmPrt . "_exprt_progress.rho",
                      json_encode($arr_content));
                      fclose($opndfile);
                      exit();
                      } */
                    $z = 0;
                    $crntRw = "";
                    $result = get_AssessShtPrsns("%", "ID/Full Name", 0, $limit_size, $assessSbmtdSheetID);
                    $total = loc_db_num_rows($result);
                    $fieldCntr = loc_db_num_fields($result);
                    while ($row = loc_db_fetch_array($result)) {
                        $crntRw = array_fill(0, ($ttlColS + 2), "");
                        $crntRw[0] = $row[0];
                        $crntRw[1] = str_replace(",", "", $row[6] . " (" . $row[5] . ")");
                        $colscntr = 0;
                        while ($colscntr < count($colsIDs)) {
                            $columnID = (int) $colsIDs[$colscntr];
                            $columnNo = (int) $colNos[$colscntr];
                            $prsnDValPulld = $row[7 + $columnNo];
                            $minValRhoData = $colMinVals[$colscntr];
                            $maxValRhoData = $colMaxVals[$colscntr];
                            $crntRw[$colscntr + 2] = $prsnDValPulld;
                            $colscntr++;
                        }
                        fputcsv($opndfile, $crntRw);
                        //file_put_contents($nwFileNm, $crntRw, FILE_APPEND | LOCK_EX);
                        $percent = round((($z + 1) / $total) * 99, 2);
                        $arr_content['percent'] = $percent;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        if ($percent >= 100) {
                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!..." . ($z +
                                1) . " out of " . $total . " Record(s) exported.</span>";
                            $arr_content['msgcount'] = $total;
                        } else {
                            $arr_content['message'] = "<span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"><br/>Exporting Records...Please Wait..." . ($z +
                                1) . " out of " . $total . " Record(s) exported.</span>";
                        }
                        file_put_contents(
                            $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_" . $exprtFileNmPrt . "_exprt_progress.rho",
                            json_encode($arr_content)
                        );
                        $z++;
                    }

                    $hdngs6 = array_fill(0, 2, "");
                    fputcsv($opndfile, $hdngs6);

                    $result2 = get_AssessShtGrpCols("03-Footer", $assessTypeID);
                    $cntr2 = 0;
                    $cntr1Ttl2 = loc_db_num_rows($result2);
                    while ($row2 = loc_db_fetch_array($result2)) {
                        $hdngs4 = array_fill(0, 2, "");
                        $hdngs4[0] = $row2[2];

                        $columnID = (int) $row2[0];
                        $columnNo = (int) $row2[15];
                        $prsnDValPulld = get_AssessShtColVal($assessSbmtdSheetID, $academicSttngID, $columnNo);

                        $hdngs4[1] = $prsnDValPulld;
                        fputcsv($opndfile, $hdngs4);
                        $cntr2 += 1;
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!..." . ($total) . " out of " . $total . " Record(s) exported.</span>";
                    $arr_content['msgcount'] = $total;
                    file_put_contents(
                        $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_" . $exprtFileNmPrt . "_exprt_progress.rho",
                        json_encode($arr_content)
                    );
                    fclose($opndfile);
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['msgcount'] = "";
                    $arr_content['dwnld_url'] = "";
                    file_put_contents(
                        $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_" . $exprtFileNmPrt . "_exprt_progress.rho",
                        json_encode($arr_content)
                    );
                }
            } else if ($actyp == 1002) {
                //Checked Exporting Process Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_" . $exprtFileNmPrt . "_exprt_progress.rho";
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
            } else if ($actyp == 2) {
            } else if ($actyp == 3) {
            }
        } else {
            if ($vwtyp == 0) {
                //Assessment Sheets
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0&mdl=$mdlACAorPMS');\">
                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                <span style=\"text-decoration:none;\">Assessment Sheets</span>
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
                $qShwSelfOnly = $vwOnlySelfShts;
                if ($vwOnlySelfShts === false) {
                    if (isset($_POST['qShwSelfOnly'])) {
                        $qShwSelfOnly = cleanInputData($_POST['qShwSelfOnly']) === "true" ? true : false;
                    }
                }
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                } //$shwSelfOnly
                $total = get_Total_AssessSheets($srchFor, $srchIn, $orgID, $qShwSelfOnly, $assessTypeFltr);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_AssessSheets($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qShwSelfOnly, $assessTypeFltr);
                $cntr = 0;
                $colClassType1 = "col-md-2";
                $colClassType2 = "col-md-4";
                $colClassType3 = "col-md-6";
                $reportTitle1 = "Auto-Generate and Compute Assessment Sheets";
                $reportName1 = "Auto-Generate and Compute Assessment Sheets";
                $rptID1 = getRptID($reportName1);
?>
                <fieldset class="">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs" style="margin-top:1px !important;">
                                <li class="active"><a data-toggle="tabajxassesssheet" data-rhodata="" href="#assessSheetMainList" id="assessSheetMainListtab">Summary List</a></li>
                                <li class=""><a data-toggle="tabajxassesssheet" data-rhodata="" href="#assessSheetDetList" id="assessSheetDetListtab">Detailed List</a></li>
                            </ul>
                            <div class="custDiv" style="padding:0px !important;min-height: 30px !important;">
                                <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                    <div id="assessSheetMainList" class="tab-pane fadein active" style="border:none !important;padding:0px 0px 0px 0px !important;">
                                        <form id='assessSheetsForm' action='' method='post' accept-charset='UTF-8'>
                                            <!--ROW ID-->
                                            <input class="form-control" id="tblRowID" type="hidden" placeholder="ROW ID" />
                                            <fieldset class="">
                                                <legend class="basic_person_lg1" style="color: #003245">CONTINUOUS ASSESSMENT SHEETS</legend>
                                                <div class="row" style="margin-bottom:0px;">
                                                    <?php
                                                    if ($canAdd === true) {
                                                    ?>
                                                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 15px !important;">
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getAssessSheetDets('clear', '#assessSheetDetList', 'grp=15&typ=1&pg=2&vtyp=1&assessSbmtdSheetID=-1&assessSbmtdSheetNm=&mdl=<?php echo $mdlACAorPMS;?>', 1);">
                                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                New Sheet
                                                            </button>
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getMyMdlRptRuns('', 'ShowDialog', 'grp=9&typ=1&pg=1&vtyp=50&sbmtdRptID=<?php echo $rptID1; ?>&mdl=<?php echo $mdlACAorPMS;?>');" data-toggle="tooltip" data-placement="bottom" title="<?php echo $reportTitle1; ?>">
                                                                <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Auto-Compute All&nbsp;
                                                            </button>
                                                        </div>
                                                    <?php
                                                    } else {
                                                        $colClassType1 = "col-md-2";
                                                        $colClassType2 = "col-md-4";
                                                        $colClassType3 = "col-md-8";
                                                    }
                                                    ?>
                                                    <div class="<?php echo $colClassType3; ?>">
                                                        <div class="input-group">
                                                            <input class="form-control" id="assessSheetsSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                                                echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                                                ?>" onkeyup="enterKeyFuncAssessSheets(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0&mdl=<?php echo $mdlACAorPMS;?>')">
                                                            <input id="assessSheetsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAssessSheets('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0&mdl=<?php echo $mdlACAorPMS;?>');">
                                                                <span class="glyphicon glyphicon-remove"></span>
                                                            </label>
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAssessSheets('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0&mdl=<?php echo $mdlACAorPMS;?>');">
                                                                <span class="glyphicon glyphicon-search"></span>
                                                            </label>
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="assessSheetsSrchIn">
                                                                <?php
                                                                $valslctdArry = array("", "", "", "", "", "", "", "");
                                                                $srchInsArrys = array(
                                                                    "All", "Assessment Sheet Name", "Assessment Group", "Assessment Type",
                                                                    "Programme/Objective", "Subject/Task", "Assessment Period", "Administrator"
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
                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="assessSheetsDsplySze" style="min-width:70px !important;">
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
                                                    <div class="col-md-1" style="padding:0px 0px 0px 15px !important;">
                                                        <nav aria-label="Page navigation">
                                                            <ul class="pagination" style="margin: 0px !important;">
                                                                <li>
                                                                    <a href="javascript:getAssessSheets('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0&mdl=<?php echo $mdlACAorPMS;?>');" aria-label="Previous">
                                                                        <span aria-hidden="true">&laquo;</span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:getAssessSheets('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0&mdl=<?php echo $mdlACAorPMS;?>');" aria-label="Next">
                                                                        <span aria-hidden="true">&raquo;</span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </nav>
                                                    </div>
                                                    <?php if ($vwOnlySelfShts == false) { ?>
                                                        <div class="col-md-1" style="padding:5px 2px 0px 0px !important;">
                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                <label class="form-check-label" title="Only Self-Created">
                                                                    <?php
                                                                    $shwSelfOnlyChkd = "";
                                                                    if ($qShwSelfOnly == true) {
                                                                        $shwSelfOnlyChkd = "checked=\"true\"";
                                                                    }
                                                                    ?>
                                                                    <input type="checkbox" class="form-check-input" onclick="getAssessSheets('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');" id="assessSheetsShwUsrOnly" name="assessSheetsShwUsrOnly" <?php echo $shwSelfOnlyChkd; ?>>
                                                                    Self-Created
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-striped table-bordered table-responsive" id="assessSheetsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th style="max-width:25px;width:25px;">No.</th>
                                                                    <th style="max-width:25px;width:25px;">...</th>
                                                                    <th style="">Assessment Sheet Name</th>
                                                                    <th style="">Sheet Type</th>
                                                                    <th style="">Group</th>
                                                                    <th style="">Programme/Objective</th>
                                                                    <th style="">Subject/Task</th>
                                                                    <th style="">Period</th>
                                                                    <th style="">Sheet Administrator</th>
                                                                    <th style="text-align:right;max-width:55px;width:55px;">Person(s) Involved</th>
                                                                    <th style="max-width:50px;width:50px;">Status</th>
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
                                                                $assessSheetID = -1;
                                                                $assessSheetNm = "";
                                                                while ($row = loc_db_fetch_array($result)) {
                                                                    $cntr += 1;
                                                                    if ($cntr == 1) {
                                                                        $assessSheetID = (float) $row[0];
                                                                        $assessSheetNm = $row[1];
                                                                    }
                                                                    $assessSheetID1 = (float) $row[0];
                                                                    $assessSheetNm1 = $row[1];
                                                                    $assessSheetClassID = (float) $row[2];
                                                                    $assessSheetClassNm = $row[3];
                                                                    $assessSheetTypeID = (float) $row[4];
                                                                    $assessSheetTypeNm = $row[5];
                                                                    $assessSheetCrseID = (float) $row[6];
                                                                    $assessSheetCrseNm = $row[7];
                                                                    $assessSheetSbjctID = (float) $row[8];
                                                                    $assessSheetSbjctNm = $row[9];
                                                                    $assessSheetPrdID = (float) $row[10];
                                                                    $assessSheetPrdNm = $row[11];
                                                                    $assessSheetAdminID = (float) $row[12];
                                                                    $assessSheetAdminNm = $row[13];
                                                                    $assessSheetPrsnCount = (float) $row[15];
                                                                    $assessSheetStatus = $row[16];
                                                                    $assessShtHdrAsdPrsID = (float) $row[17];
                                                                    $assessShtHdrAsdPrsNm = $row[18];
                                                                    $statusStyle = "color:green;font-weight:bold;";
                                                                    if ($assessSheetStatus === "Closed") {
                                                                        $statusStyle = "color:red;font-weight:bold;";
                                                                    }
                                                                ?>
                                                                    <tr id="assessSheetsHdrsRow_<?php echo $cntr; ?>">
                                                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Assessment Sheet" onclick="getAssessSheetDets('clear', '#assessSheetDetList', 'grp=15&typ=1&pg=2&vtyp=1&assessSbmtdSheetID=<?php echo $assessSheetID1; ?>&assessSbmtdSheetNm=<?php echo urlencode($assessSheetNm1); ?>&mdl=<?php echo $mdlACAorPMS;?>', <?php echo $assessSheetID1; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                                                <?php
                                                                                if ($canAdd === true) {
                                                                                ?>
                                                                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                <?php } else { ?>
                                                                                    <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                <?php } ?>
                                                                            </button>
                                                                        </td>
                                                                        <td class="lovtd"><?php echo $assessSheetNm1; ?></td>
                                                                        <td class="lovtd"><?php echo $assessSheetTypeNm; ?></td>
                                                                        <td class="lovtd"><?php echo $assessSheetClassNm; ?></td>
                                                                        <td class="lovtd"><?php echo $assessSheetCrseNm; ?></td>
                                                                        <td class="lovtd"><?php echo $assessSheetSbjctNm; ?></td>
                                                                        <td class="lovtd"><?php echo $assessSheetPrdNm; ?></td>
                                                                        <td class="lovtd"><?php echo $assessSheetAdminNm; ?></td>
                                                                        <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                                                                                                                    echo number_format($assessSheetPrsnCount, 0);
                                                                                                                                                    ?>
                                                                        </td>
                                                                        <td class="lovtd" style="<?php echo $statusStyle; ?>"><?php echo $assessSheetStatus; ?></td>
                                                                        <?php
                                                                        if ($canDel === true) {
                                                                        ?>
                                                                            <td class="lovtd">
                                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Assessment Sheet" onclick="delAssessShtHdr('assessSheetsHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                                    <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                </button>
                                                                                <input type="hidden" id="assessSheetsHdrsRow<?php echo $cntr; ?>_HdrID" name="assessSheetsHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $assessSheetID1; ?>">
                                                                            </td>
                                                                        <?php } ?>
                                                                        <?php
                                                                        if ($canVwRcHstry === true) {
                                                                        ?>
                                                                            <td class="lovtd">
                                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                                        echo urlencode(encrypt1(($assessSheetID1 . "|aca.aca_assess_sheet_hdr|assess_sheet_hdr_id"),
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
                                                        <input type="hidden" id="assessSheetID" name="assessSheetID" value="<?php echo $assessSheetID; ?>">
                                                        <input type="hidden" id="assessSheetNm" name="assessSheetNm" value="<?php echo $assessSheetNm; ?>">
                                                        <input type="hidden" id="assessSheetHdnTabNm" name="assessSheetHdnTabNm" value="asShtDetlsInfo">
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                    <div id="assessSheetDetList" class="tab-pane fadein" style="border:none !important;padding:0px 0px 0px 0px !important;">
                                    </div>
                                </div>
                            </div>
                        </div>
                </fieldset>
            <?php
            } else if ($vwtyp == 1) {
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                $assessSbmtdSheetID = isset($_POST['assessSbmtdSheetID']) ? $_POST['assessSbmtdSheetID'] : -1;
                $assessSbmtdSheetNm = isset($_POST['assessSbmtdSheetNm']) ? $_POST['assessSbmtdSheetNm'] : "";
                $pkID = $assessSbmtdSheetID;
                $assessTypeID = (int) getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assessment_type_id", $assessSbmtdSheetID);
                $assessSheetStatus = getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assess_sheet_status", $assessSbmtdSheetID);
                if ($assessSheetStatus === "Closed") {
                    $canEdt = false;
                }
                $academicSttngID = -1;
                $cntr = 0;
            ?>
                <form id='assessShtHdrForm' action='' method='post' accept-charset='UTF-8'>
                    <fieldset class="">
                        <!--<legend class="basic_person_lg1" style="color: #003245">CONTINUOUS ASSESSMENT SHEETS</legend>-->
                        <div class="row" style="margin-bottom:0px;">
                            <?php if ($canAdd === true) {
                            ?>
                                <div class="col-md-6" style="padding:0px 15px 0px 15px !important;">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getAssessSheetDets('clear', '#assessSheetDetList', 'grp=15&typ=1&pg=2&vtyp=1&assessSbmtdSheetID=-1&assessSbmtdSheetNm=&mdl=<?php echo $mdlACAorPMS;?>', 1);" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title=" New Assessment Sheet">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Sheet
                                    </button>
                                    <?php if ($assessSbmtdSheetID <= 0 || $canEdt === true) { ?>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="saveAssessShtHdrForm();" style="width:100% !important;">
                                            <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            Save
                                        </button>
                                    <?php } ?>
                                    <button type="button" class="btn btn-default" style="" onclick="getAssessSheetDets('clear', '#assessSheetDetList', 'grp=15&typ=1&pg=2&vtyp=1&mdl=<?php echo $mdlACAorPMS;?>');" style="width:100% !important;">
                                        <img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                    <button type="button" class="btn btn-default" style="" onclick="saveAssessShtHdrForm(2);" style="width:100% !important;">
                                        <img src="cmn_images/calculator.gif" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                        Compute Sheet Values
                                    </button>
                                </div>
                            <?php
                            }
                            ?>
                            <div class="col-md-6" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group" style="width:100% !important;">
                                    <label class="btn btn-default btn-file input-group-addon">
                                        <span style="font-weight:bold;">Selected Sheet:</span>
                                    </label>
                                    <input type="text" class="form-control" aria-label="..." id="assessSbmtdSheetNm" name="assessSbmtdSheetNm" value="<?php echo $assessSbmtdSheetNm; ?>" readonly="true" style="width:100% !important;">
                                    <input type="hidden" class="form-control" aria-label="..." id="assessSbmtdSheetID" value="<?php echo $assessSbmtdSheetID; ?>" style="width:100% !important;">
                                    <input type="hidden" class="form-control" aria-label="..." id="assessSbmtdSheetType" value="<?php echo $assessTypeFltr; ?>" style="width:100% !important;">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Assessment Sheets', 'allOtherInputOrgID', 'assessSbmtdSheetType', '', 'radio', true, '', 'assessSbmtdSheetID', 'assessSbmtdSheetNm', 'clear', 1, '', function () {
                                                getAssessSheetDets('clear', '#assessSheetDetList', 'grp=15&typ=1&pg=2&vtyp=1&mdl=<?php echo $mdlACAorPMS;?>');
                                            });">
                                        <span class="glyphicon glyphicon-th-list"></span>
                                    </label>
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
                                            <ul class="nav nav-tabs" style="margin-top:1px !important;display:none;">
                                                <li class="active"><a data-toggle="tabajxasShtdetls" data-rhodata="" href="#asShtDetlsInfo" id="asShtDetlsInfotab">HEADER INFORMATION</a></li>
                                                <li class=""><a data-toggle="tabajxasShtdetls" data-rhodata="" href="#asShtDetlsTrans" id="asShtDetlsTranstab">DETAILS</a></li>
                                                <li class=""><a data-toggle="tabajxasShtdetls" data-rhodata="" href="#asShtDetlsPMRecs" id="asShtDetlsPMRecstab">FOOTER INFORMATION</a></li>
                                            </ul>
                                            <div class="custDiv" style="padding:0px !important;min-height: 30px !important;">
                                                <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                                    <div id="asShtDetlsInfo" class="" style="border:none !important;padding:0px 0px 0px 0px !important;">
                                                        <div class="container-fluid" id="assessSheetHdrDetailInfo">
                                                            <?php
                                                            $assessShtHdrID = -1;
                                                            $assessShtHdrName = "";
                                                            $assessShtHdrClassID = -1;
                                                            $assessShtHdrClassNm = "";
                                                            $assessShtHdrTypeID = -1;
                                                            $assessShtHdrTypeNm = "";
                                                            $assessShtHdrType = $assessTypeFltr;
                                                            $assessShtHdrCrseID = -1;
                                                            $assessShtHdrCrseNm = "";
                                                            $assessShtHdrSbjctID = -1;
                                                            $assessShtHdrSbjctNm = "";
                                                            $assessShtHdrPeriodID = -1;
                                                            $assessShtHdrPeriodNm = "";
                                                            $assessShtHdrPrsnID = $prsnid;
                                                            $assessShtHdrPrsnNm = getPrsnFullNm($prsnid);
                                                            $assessShtHdrDesc = "";
                                                            $assessShtHdrStatus = "";
                                                            $assessShtHdrAsdPrsID = -1;
                                                            $assessShtHdrAsdPrsNm = "";
                                                            if ($pkID > 0) {
                                                                $result1 = get_One_AssessSheetHdr($pkID);
                                                                while ($row1 = loc_db_fetch_array($result1)) {
                                                                    $assessShtHdrID = (float) $row1[0];
                                                                    $assessShtHdrName = $row1[1];
                                                                    $assessShtHdrClassID = (int) $row1[2];
                                                                    $assessShtHdrClassNm = $row1[3];
                                                                    $assessShtHdrTypeID = (int) $row1[4];
                                                                    $assessShtHdrTypeNm = $row1[5];
                                                                    $assessShtHdrCrseID = (int) $row1[6];
                                                                    $assessShtHdrCrseNm = $row1[7];
                                                                    $assessShtHdrSbjctID = (int) $row1[8];
                                                                    $assessShtHdrSbjctNm = $row1[9];
                                                                    $assessShtHdrPeriodID = (float) $row1[10];
                                                                    $assessShtHdrPeriodNm = $row1[11];
                                                                    $assessShtHdrPrsnID = (float) $row1[12];
                                                                    $assessShtHdrPrsnNm = $row1[13];
                                                                    $assessShtHdrDesc = $row1[16];
                                                                    $assessShtHdrType = $row1[14];
                                                                    $assessShtHdrStatus = $row1[17];
                                                                    $assessShtHdrAsdPrsID = (float) $row1[18];
                                                                    $assessShtHdrAsdPrsNm = $row1[19];
                                                                    $statusStyle = "padding:5px;color:green;font-weight:bold;";
                                                                    if ($assessShtHdrStatus === "Closed") {
                                                                        $statusStyle = "padding:5px;color:red;font-weight:bold;";
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                            <div class="row">
                                                                <div class="col-md-6" style="padding:0px 1px 0px 0px !important;">
                                                                    <fieldset class="basic_person_fs123" style="padding-top:10px !important;">
                                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                            <label for="assessShtHdrName" class="control-label col-lg-4">Sheet Name:</label>
                                                                            <div class="col-lg-8">
                                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="assessShtHdrName" name="assessShtHdrName" value="<?php echo $assessShtHdrName; ?>" style="width:100% !important;">
                                                                                <input type="hidden" class="form-control" aria-label="..." id="assessShtHdrID" name="assessShtHdrID" value="<?php echo $assessShtHdrID; ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                            <label for="assessShtHdrDesc" class="control-label col-lg-4">Description:</label>
                                                                            <div class="col-lg-8">
                                                                                <textarea class="form-control rqrdFld" rows="3" cols="20" id="assessShtHdrDesc" name="assessShtHdrDesc" style="text-align:left !important;"><?php echo $assessShtHdrDesc; ?></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                            <label for="assessShtHdrTypeNm" class="control-label col-md-4">Assessment Type:</label>
                                                                            <div class="col-md-8">
                                                                                <div class="input-group">
                                                                                    <input class="form-control" id="assessShtHdrTypeNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Assessment Type" type="text" min="0" placeholder="" value="<?php echo $assessShtHdrTypeNm; ?>" readonly="true" />
                                                                                    <input type="hidden" id="assessShtHdrTypeID" value="<?php echo $assessShtHdrTypeID; ?>">
                                                                                    <input type="hidden" id="assessShtHdrType" value="<?php echo $assessShtHdrType; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Assessment Types', 'allOtherInputOrgID', 'assessShtHdrType', '', 'radio', true, '', 'assessShtHdrTypeID', 'assessShtHdrTypeNm', 'clear', 1, '', function () {});">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                            <label for="assessShtHdrStatus" class="control-label col-lg-4">Assessment Status:</label>
                                                                            <div class="col-lg-8">
                                                                                <?php
                                                                                if ($canEdt === true) {
                                                                                ?>
                                                                                    <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="assessShtHdrStatus" style="min-width:70px !important;">
                                                                                        <?php
                                                                                        $valslctdArry = array("", "");
                                                                                        $dsplySzeArry = array("Open for Editing", "Closed");
                                                                                        for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                                                                            if ($assessShtHdrStatus == $dsplySzeArry[$y]) {
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
                                                                                <?php } else { ?>
                                                                                    <span style="<?php echo $statusStyle; ?>"><?php echo $assessShtHdrStatus; ?></span>
                                                                                <?php
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                        </div>
                                                                    </fieldset>
                                                                </div>
                                                                <div class="col-md-6" style="padding:0px 0px 0px 1px !important;">
                                                                    <fieldset class="basic_person_fs123" style="padding-top:10px !important;">
                                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                            <label for="assessShtHdrPeriodNm" class="control-label col-md-4">Assessment Period:</label>
                                                                            <div class="col-md-8">
                                                                                <div class="input-group">
                                                                                    <input class="form-control" id="assessShtHdrPeriodNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" min="0" placeholder="" value="<?php echo $assessShtHdrPeriodNm; ?>" readonly="true" />
                                                                                    <input type="hidden" id="assessShtHdrPeriodID" value="<?php echo $assessShtHdrPeriodID; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Assessment Periods', 'allOtherInputOrgID', '', '', 'radio', true, '', 'assessShtHdrPeriodID', 'assessShtHdrPeriodNm', 'clear', 1, '', function () {});">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                            <label for="assessShtHdrPrsnNm" class="control-label col-md-4">Main Assessor:</label>
                                                                            <div class="col-md-8">
                                                                                <div class="input-group">
                                                                                    <input class="form-control" id="assessShtHdrPrsnNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" min="0" placeholder="" value="<?php echo $assessShtHdrPrsnNm; ?>" readonly="true" />
                                                                                    <input type="hidden" id="assessShtHdrPrsnID" value="<?php echo $assessShtHdrPrsnID; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', 'allOtherInputOrgID', '', '', 'radio', true, '', 'assessShtHdrPrsnID', 'assessShtHdrPrsnNm', 'clear', 1, '', function () {});">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;display:none;">
                                                                            <label for="assessShtHdrAsdPrsNm" class="control-label col-md-4">Person Assessed:</label>
                                                                            <div class="col-md-8">
                                                                                <div class="input-group">
                                                                                    <input class="form-control" id="assessShtHdrAsdPrsNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" min="0" placeholder="" value="<?php echo $assessShtHdrAsdPrsNm; ?>" readonly="true" />
                                                                                    <input type="hidden" id="assessShtHdrAsdPrsID" value="<?php echo $assessShtHdrAsdPrsID; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', 'allOtherInputOrgID', '', '', 'radio', true, '', 'assessShtHdrAsdPrsID', 'assessShtHdrAsdPrsNm', 'clear', 1, '', function () {});">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                            <label for="assessShtHdrClassNm" class="control-label col-md-4">Assessment Group:</label>
                                                                            <div class="col-md-8">
                                                                                <div class="input-group">
                                                                                    <input class="form-control" id="assessShtHdrClassNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Assessment Group" type="text" min="0" placeholder="" value="<?php echo $assessShtHdrClassNm; ?>" readonly="true" />
                                                                                    <input type="hidden" id="assessShtHdrClassID" value="<?php echo $assessShtHdrClassID; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Assessment Groups', 'allOtherInputOrgID', '', '', 'radio', true, '', 'assessShtHdrClassID', 'assessShtHdrClassNm', 'clear', 1, '', function () {});">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                            <label for="assessShtHdrCrseNm" class="control-label col-md-4">Programme/Objective:</label>
                                                                            <div class="col-md-8">
                                                                                <div class="input-group">
                                                                                    <input class="form-control" id="assessShtHdrCrseNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" min="0" placeholder="" value="<?php echo $assessShtHdrCrseNm; ?>" readonly="true" />
                                                                                    <input type="hidden" id="assessShtHdrCrseID" value="<?php echo $assessShtHdrCrseID; ?>">
                                                                                    <input type="hidden" id="assessShtHdrCrseRecType" value="<?php echo $moduleType; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Assessment Objectives/Courses', 'assessShtHdrClassID', 'assessShtHdrCrseRecType', '', 'radio', true, '', 'assessShtHdrCrseID', 'assessShtHdrCrseNm', 'clear', 1, '', function () {});">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                            <label for="assessShtHdrSbjctNm" class="control-label col-md-4">Task/Subject:</label>
                                                                            <div class="col-md-8">
                                                                                <div class="input-group">
                                                                                    <input class="form-control" id="assessShtHdrSbjctNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" min="0" placeholder="" value="<?php echo $assessShtHdrSbjctNm; ?>" readonly="true" />
                                                                                    <input type="hidden" id="assessShtHdrSbjctID" value="<?php echo $assessShtHdrSbjctID; ?>">
                                                                                    <input type="hidden" id="assessShtHdrSbjctRecType" value="<?php echo $moduleType2; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Assessment Tasks/Subjects', 'assessShtHdrClassID', 'assessShtHdrCrseID', '', 'radio', true, '', 'assessShtHdrSbjctID', 'assessShtHdrSbjctNm', 'clear', 1, '', function () {});">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </fieldset>
                                                                </div>
                                                                <?php
                                                                $result1 = get_AssessShtGrpCols("01-Header", $assessTypeID);
                                                                $cntr1 = 0;
                                                                $gcntr1 = 0;
                                                                $cntr1Ttl = loc_db_num_rows($result1);
                                                                if ($cntr1Ttl > 0) {
                                                                ?>
                                                                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                        <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                                                            <?php
                                                                            while ($row1 = loc_db_fetch_array($result1)) {
                                                                                if ($gcntr1 == 0) {
                                                                                    $gcntr1 += 1;
                                                                                }
                                                                                if (($cntr1 % 2) == 0) {
                                                                            ?>
                                                                                    <div class="row">
                                                                                    <?php
                                                                                }
                                                                                    ?>
                                                                                    <div class="col-md-6">
                                                                                        <div class="form-group form-group-sm">
                                                                                            <label class="control-label col-md-4"><?php echo $row1[2]; ?>:</label>
                                                                                            <div class="col-md-8">
                                                                                                <?php
                                                                                                $columnID = (int) $row1[0];
                                                                                                $columnNo = (int) $row1[15];
                                                                                                $prsnDValPulld = get_AssessShtColVal($assessSbmtdSheetID, $academicSttngID, $columnNo);
                                                                                                $isRqrdFld = ($row1[12] === "1") ? "rqrdFld" : "";
                                                                                                if ($row1[13] == "1" || $canEdt === false) {
                                                                                                    echo str_replace("{:p_col_value}", $prsnDValPulld, $row1[19]);
                                                                                                } else {
                                                                                                    if ($row1[4] == "Date") {
                                                                                                ?>
                                                                                                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                                                            <input class="form-control assessShtHdrFtrVal <?php echo $isRqrdFld; ?>" size="16" type="text" id="assessShtHdrFtrFld_<?php echo $columnNo; ?>" value="<?php echo $prsnDValPulld; ?>">
                                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                                        </div>
                                                                                                    <?php
                                                                                                    } else if ($row1[4] == "Number") {
                                                                                                    ?>
                                                                                                        <input class="form-control assessShtHdrFtrVal <?php echo $isRqrdFld; ?>" id="assessShtHdrFtrFld_<?php echo $columnNo; ?>" type="text" placeholder="" value="<?php echo $prsnDValPulld; ?>" />
                                                                                                    <?php
                                                                                                    } else {
                                                                                                    ?>
                                                                                                        <input class="form-control assessShtHdrFtrVal <?php echo $isRqrdFld; ?>" id="assessShtHdrFtrFld_<?php echo $columnNo; ?>" type="text" placeholder="" value="<?php echo $prsnDValPulld; ?>" />
                                                                                                <?php
                                                                                                    }
                                                                                                }
                                                                                                ?>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <?php
                                                                                    $cntr1 += 1;
                                                                                    if (($cntr1 % 2) == 0 || $cntr1 == ($cntr1Ttl)) {
                                                                                        $cntr1 = 0;
                                                                                    ?>
                                                                                    </div>
                                                                            <?php
                                                                                    }
                                                                                }
                                                                                if ($gcntr1 == 1) {
                                                                                    $gcntr1 = 0;
                                                                                }
                                                                            ?>
                                                                        </fieldset>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                            <?php ?>
                                                        </div>
                                                    </div>
                                                    <div id="asShtDetlsTrans" class="" style="border:none !important;padding:0px 0px 0px 0px !important;">
                                                        <?php
                                                        $academicSttngID = -1;
                                                        $mkReadOnly = "";
                                                        $mkRmrkReadOnly = "";
                                                        $assessSbmtdSheetID = isset($_POST['assessSbmtdSheetID']) ? $_POST['assessSbmtdSheetID'] : -1;
                                                        $assessSbmtdSheetNm = isset($_POST['assessSbmtdSheetNm']) ? $_POST['assessSbmtdSheetNm'] : "";
                                                        $pkID = $assessSbmtdSheetID;
                                                        $assessTypeID = (int) getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assessment_type_id", $assessSbmtdSheetID);
                                                        $assessSheetStatus = getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assess_sheet_status", $assessSbmtdSheetID);
                                                        if ($assessSheetStatus === "Closed") {
                                                            $canEdt = false;
                                                        }
                                                        $cntr = 0;
                                                        $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                                                        $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                                                        $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                                                        $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 15;
                                                        $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
                                                        $searchAll = true;
                                                        $fltrTyp = isset($_POST['acaRgstratnFilterBy']) ? cleanInputData($_POST['acaRgstratnFilterBy']) : "Relation Type";
                                                        $fltrTypValue = isset($_POST['acaRgstratnFilterByVal']) ? cleanInputData($_POST['acaRgstratnFilterByVal']) : "All";

                                                        $qShwCrntOnly = true;
                                                        if (isset($_POST['qShwCrntOnly'])) {
                                                            $qShwCrntOnly = cleanInputData($_POST['qShwCrntOnly']) === "true" ? true : false;
                                                        }

                                                        if (strpos($srchFor, "%") === FALSE) {
                                                            $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                                                            $srchFor = str_replace("%%", "%", $srchFor);
                                                        }
                                                        $total = get_AssessShtPrsnsTtl($srchFor, $srchIn, $assessSbmtdSheetID);
                                                        //var_dump($_POST);
                                                        //echo "TT:" . $total;
                                                        if ($pageNo > ceil($total / $lmtSze)) {
                                                            $pageNo = 1;
                                                        } else if ($pageNo < 1) {
                                                            $pageNo = ceil($total / $lmtSze);
                                                        }
                                                        $extra4Where = " and a.person_id IN (select y1. from aca.aca_assmnt_col_vals y1 where )";
                                                        $curIdx = $pageNo - 1;
                                                        $result = get_AssessShtPrsns($srchFor, $srchIn, $curIdx, $lmtSze, $assessSbmtdSheetID);
                                                        $cntr = 0;
                                                        $vwtyp = 2;
                                                        ?>
                                                        <div class="basic_person_fs123" style="padding-top:2px !important;">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="col-md-3" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;display:none;" onclick="getAssessShtHdr('', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="exprtAsessShts();" data-toggle="tooltip" title="Export Assessment Lines">
                                                                            <img src="cmn_images/document_export.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            Export
                                                                        </button>
                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="" data-toggle="tooltip" title="Import Assessment Lines">
                                                                            <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            Import
                                                                        </button>
                                                                    </div>
                                                                    <div class="col-md-7">
                                                                        <div class="input-group">
                                                                            <input class="form-control" id="assessShtHdrSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                                                                echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                                                                ?>" onkeyup="enterKeyFuncAssessShtHdr(event, '', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>')">
                                                                            <input id="assessShtHdrPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAssessShtHdr('clear', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>')">
                                                                                <span class="glyphicon glyphicon-remove"></span>
                                                                            </label>
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAssessShtHdr('', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');">
                                                                                <span class="glyphicon glyphicon-search"></span>
                                                                            </label>
                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="assessShtHdrSrchIn">
                                                                                <?php
                                                                                $valslctdArry = array("", "", "", "", "", "", "", "", "", "");
                                                                                $srchInsArrys = array(
                                                                                    "ID/Full Name", "Full Name", "Residential Address", "Contact Information", "Linked Firm/Workplace", "Person Type",
                                                                                    "Date of Birth", "Home Town", "Gender", "Marital Status"
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
                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="assessShtHdrDsplySze" style="min-width:70px !important;">
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
                                                                                    <a class="rhopagination" href="javascript:getAssessShtHdr('previous', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');" aria-label="Previous">
                                                                                        <span aria-hidden="true">&laquo;</span>
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a class="rhopagination" href="javascript:getAssessShtHdr('next', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');" aria-label="Next">
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
                                                                    <table class="table table-striped table-bordered table-responsive" id="oneAssessSheetTransLinesTable" cellspacing="0" width="100%" style="width:100% !important;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="min-width:30px;">No.-[Rec. ID]</th>
                                                                                <th style="min-width:175px !important;">Full Name (ID No.)</th>
                                                                                <?php
                                                                                $colWidth = "max-width:70px !important;width:70px !important;";
                                                                                $resultHdr = get_AssessShtGrpCols("02-Detail", $assessTypeID);
                                                                                $colscntr1 = 0;
                                                                                $ttlColS = loc_db_num_rows($resultHdr);
                                                                                $colsIDs = array_fill(0, $ttlColS, -1);
                                                                                $colNos = array_fill(0, $ttlColS, 1);
                                                                                $colsNames = array_fill(0, $ttlColS, "");
                                                                                $colsTypes = array_fill(0, $ttlColS, "");
                                                                                $colsIsFrmlr = array_fill(0, $ttlColS, "1");
                                                                                $colMinVals = array_fill(0, $ttlColS, 0);
                                                                                $colMaxVals = array_fill(0, $ttlColS, 0);
                                                                                $colsIsDsplyd = array_fill(0, $ttlColS, "1");
                                                                                $colsHtmlCss = array_fill(0, $ttlColS, "");
                                                                                while ($rwHdr = loc_db_fetch_array($resultHdr)) {
                                                                                    $colsIDs[$colscntr1] = (int) $rwHdr[0];
                                                                                    $colsNames[$colscntr1] = $rwHdr[2];
                                                                                    $colsTypes[$colscntr1] = $rwHdr[4];
                                                                                    $colsIsFrmlr[$colscntr1] = $rwHdr[13];
                                                                                    $colNos[$colscntr1] = (int) $rwHdr[15];
                                                                                    $colMinVals[$colscntr1] = (int) $rwHdr[16];
                                                                                    $colMaxVals[$colscntr1] = (int) $rwHdr[17];
                                                                                    $colsIsDsplyd[$colscntr1] = $rwHdr[18];
                                                                                    $colsHtmlCss[$colscntr1] = $rwHdr[19];
                                                                                    $colscntr1++;
                                                                                }
                                                                                $colscntr = 0;
                                                                                while ($colscntr < count($colsNames)) {
                                                                                    $tdStyle = "";
                                                                                    if ($colsIsDsplyd[$colscntr] == "0") {
                                                                                        $tdStyle = "display:none;";
                                                                                    }
                                                                                ?>
                                                                                    <th style="text-align: right;<?php echo $tdStyle . $colWidth; ?>"><?php echo $colsNames[$colscntr]; ?></th>
                                                                                <?php
                                                                                    $colscntr++;
                                                                                }
                                                                                ?>
                                                                                <th style="max-width:30px;width:30px;display:none;">...</th>
                                                                                <?php if ($canVwRcHstry) { ?>
                                                                                    <th style="max-width:30px;width:30px;display:none;">...</th>
                                                                                <?php } ?>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            while ($row = loc_db_fetch_array($result)) {
                                                                                if ($pkID <= 0 && $cntr <= 0) {
                                                                                    $pkID = (float) $row[0];
                                                                                }
                                                                                $academicSttngID = (float) $row[1];
                                                                                //border-top:1px solid #ddd;
                                                                                $cntr += 1;
                                                                            ?>
                                                                                <tr id="assessShtColRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr) . "-[" . $row[0] . "]"; ?></td>
                                                                                    <td class="lovtd"><?php echo $row[6] . " (" . $row[5] . ")"; ?>
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="assessShtColRow<?php echo $cntr; ?>_LineID" value="<?php echo $row[0]; ?>">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="assessShtColRow<?php echo $cntr; ?>_PrsnID" value="<?php echo $row[4]; ?>">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="assessShtColRow<?php echo $cntr; ?>_PrsnNm" value="<?php echo $row[6]; ?>">
                                                                                    </td>
                                                                                    <?php
                                                                                    $colscntr = 0;
                                                                                    while ($colscntr < count($colsIDs)) {
                                                                                        $columnID = (int) $colsIDs[$colscntr];
                                                                                        $columnNo = (int) $colNos[$colscntr];
                                                                                        $prsnDValPulld = $row[7 + $columnNo];
                                                                                        $isRqrdFld = "rqrdFld";
                                                                                        $tdClass = "lovtd555";
                                                                                        $tdStyle = "padding: 0px !important;";
                                                                                        if ($colsIsFrmlr[$colscntr] == "1" || $canEdt === false) {
                                                                                            $tdClass = "lovtd";
                                                                                            $tdStyle = "";
                                                                                        }
                                                                                        if ($colsIsDsplyd[$colscntr] == "0") {
                                                                                            $tdStyle = "display:none;";
                                                                                        }
                                                                                        $minValRhoData = $colMinVals[$colscntr];
                                                                                        $maxValRhoData = $colMaxVals[$colscntr];
                                                                                    ?>
                                                                                        <td class="<?php echo $tdClass; ?>" style="text-align: right;<?php echo $tdStyle . $colWidth; ?>">
                                                                                            <?php
                                                                                            if ($colsIsFrmlr[$colscntr] == "1" || $canEdt === false) {
                                                                                                echo str_replace("{:p_col_value}", $prsnDValPulld, $colsHtmlCss[$colscntr]);
                                                                                            } else {
                                                                                                if ($colsTypes[$colscntr] == "Date") {
                                                                                            ?>
                                                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                                                        <input class="form-control assessShtColRow<?php echo $cntr; ?> assesScoreM assesScore<?php echo $columnNo; ?> <?php echo $isRqrdFld; ?>" size="16" data-rhodata="" type="text" id="assessShtColRow<?php echo $cntr . "_AsessScore-" . $columnNo; ?>" value="<?php echo $prsnDValPulld; ?>" style="width:100% !important;">
                                                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                                    </div>
                                                                                                <?php
                                                                                                } else if ($colsTypes[$colscntr] == "Number") {
                                                                                                ?>
                                                                                                    <input class="form-control assessShtColRow<?php echo $cntr; ?> assesScoreM assesScoreNum assesScore<?php echo $columnNo; ?> <?php echo $isRqrdFld; ?>" id="assessShtColRow<?php echo $cntr . "_AsessScore-" . $columnNo; ?>" min-rhodata="<?php echo $minValRhoData; ?>" max-rhodata="<?php echo $maxValRhoData; ?>" type="text" placeholder="" value="<?php echo number_format(((float) $prsnDValPulld), 2); ?>" style="width:100% !important;text-align: right;margin:0px !important;" onblur="vldtAssessColNumFld('assessShtColRow<?php echo $cntr . "_AsessScore-" . $columnNo; ?>');" onkeypress="gnrlFldKeyPress(event, 'assessShtColRow<?php echo $cntr . "_AsessScore-" . $columnNo; ?>', 'oneAssessSheetTransLinesTable', 'assesScore<?php echo $columnNo; ?>');" />
                                                                                                <?php
                                                                                                } else {
                                                                                                ?>
                                                                                                    <input class="form-control assessShtColRow<?php echo $cntr; ?> assesScoreM assesScore<?php echo $columnNo; ?> <?php echo $isRqrdFld; ?>" id="assessShtColRow<?php echo $cntr . "_AsessScore-" . $columnNo; ?>" data-rhodata="" type="text" placeholder="" value="<?php echo $prsnDValPulld; ?>" style="width:100% !important;text-align: right;margin:0px !important;" />
                                                                                            <?php
                                                                                                }
                                                                                            }
                                                                                            ?>
                                                                                        </td>
                                                                                    <?php
                                                                                        $colscntr++;
                                                                                    }
                                                                                    ?>
                                                                                    <td class="lovtd" style="display:none;">
                                                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Basic Profile" onclick="getBscProfileForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'dtAdmnBscPrsnPrflForm', 'View Person Basic Profile', <?php echo $row[0]; ?>, 0, 1, 'VIEW');" style="padding:2px !important;">
                                                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                                            <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                        </button>
                                                                                    </td>
                                                                                    <?php if ($canVwRcHstry === true) { ?>
                                                                                        <td class="lovtd" style="display:none;">
                                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                                                    echo urlencode(encrypt1(($row[0] . "|prs.prsn_names_nos|person_id"), $smplTokenWord1));
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
                                                        <?php
                                                        ?>
                                                    </div>
                                                    <div id="asShtDetlsPMRecs" class="" style="border:none !important;padding:0px 0px 0px 0px !important;">
                                                        <?php
                                                        //Footer
                                                        $mkReadOnly = "";
                                                        $mkRmrkReadOnly = "";
                                                        $assessSbmtdSheetID = isset($_POST['assessSbmtdSheetID']) ? $_POST['assessSbmtdSheetID'] : -1;
                                                        $assessSbmtdSheetNm = isset($_POST['assessSbmtdSheetNm']) ? $_POST['assessSbmtdSheetNm'] : "";
                                                        $assessTypeID = (int) getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assessment_type_id", $assessSbmtdSheetID);
                                                        $assessSheetStatus = getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assess_sheet_status", $assessSbmtdSheetID);
                                                        if ($assessSheetStatus === "Closed") {
                                                            $canEdt = false;
                                                        }
                                                        $academicSttngID = -1;
                                                        $pkID = $assessSbmtdSheetID;
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
                                                        $total = 0;
                                                        if ($pageNo > ceil($total / $lmtSze)) {
                                                            $pageNo = 1;
                                                        } else if ($pageNo < 1) {
                                                            $pageNo = ceil($total / $lmtSze);
                                                        }
                                                        $curIdx = $pageNo - 1;
                                                        $result = null;
                                                        $cntr = 0;
                                                        $vwtyp = 3;
                                                        $result1 = get_AssessShtGrpCols("03-Footer", $assessTypeID);
                                                        $cntr1 = 0;
                                                        $gcntr1 = 0;
                                                        $cntr1Ttl = loc_db_num_rows($result1);
                                                        if ($cntr1Ttl > 0) {
                                                        ?>
                                                            <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                    <?php
                                                                    while ($row1 = loc_db_fetch_array($result1)) {
                                                                        if ($gcntr1 == 0) {
                                                                            $gcntr1 += 1;
                                                                        }
                                                                        if (($cntr1 % 2) == 0) {
                                                                    ?>
                                                                            <div class="row">
                                                                            <?php
                                                                        }
                                                                            ?>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group form-group-sm">
                                                                                    <label class="control-label col-md-4"><?php echo $row1[2]; ?>:</label>
                                                                                    <div class="col-md-8">
                                                                                        <?php
                                                                                        $columnID = (int) $row1[0];
                                                                                        $columnNo = (int) $row1[15];
                                                                                        $prsnDValPulld = get_AssessShtColVal($assessSbmtdSheetID, $academicSttngID, $columnNo);
                                                                                        //get_PrsExtrData($pkID, $row1[1]);
                                                                                        $isRqrdFld = ($row1[12] === "1") ? "rqrdFld" : "";
                                                                                        if ($row1[13] == "1" || $canEdt === false) {
                                                                                            echo str_replace("{:p_col_value}", $prsnDValPulld, $row1[19]);
                                                                                        } else {
                                                                                            if ($row1[4] == "Date") {
                                                                                        ?>
                                                                                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                                                    <input class="form-control assessShtHdrFtrVal <?php echo $isRqrdFld; ?>" size="16" type="text" id="assessShtHdrFtrFld_<?php echo $columnNo; ?>" value="<?php echo $prsnDValPulld; ?>">
                                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                                </div>
                                                                                            <?php
                                                                                            } else if ($row1[4] == "Number") {
                                                                                            ?>
                                                                                                <input class="form-control assessShtHdrFtrVal <?php echo $isRqrdFld; ?>" id="assessShtHdrFtrFld_<?php echo $columnNo; ?>" type="text" placeholder="" value="<?php echo $prsnDValPulld; ?>" />
                                                                                            <?php
                                                                                            } else {
                                                                                            ?>
                                                                                                <input class="form-control assessShtHdrFtrVal <?php echo $isRqrdFld; ?>" id="assessShtHdrFtrFld_<?php echo $columnNo; ?>" type="text" placeholder="" value="<?php echo $prsnDValPulld; ?>" />
                                                                                        <?php
                                                                                            }
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <?php
                                                                            $cntr1 += 1;
                                                                            if (($cntr1 % 2) == 0 || $cntr1 == ($cntr1Ttl)) {
                                                                                $cntr1 = 0;
                                                                            ?>
                                                                            </div>
                                                                    <?php
                                                                            }
                                                                        }
                                                                        if ($gcntr1 == 1) {
                                                                            $gcntr1 = 0;
                                                                        }
                                                                    ?>
                                                                </div>
                                                            </fieldset>
                                                        <?php
                                                        }
                                                        ?>
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
                $academicSttngID = -1;
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                $assessSbmtdSheetID = isset($_POST['assessSbmtdSheetID']) ? $_POST['assessSbmtdSheetID'] : -1;
                $assessSbmtdSheetNm = isset($_POST['assessSbmtdSheetNm']) ? $_POST['assessSbmtdSheetNm'] : "";
                $pkID = $assessSbmtdSheetID;
                $assessTypeID = (int) getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assessment_type_id", $assessSbmtdSheetID);
                $assessSheetStatus = getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assess_sheet_status", $assessSbmtdSheetID);
                if ($assessSheetStatus === "Closed") {
                    $canEdt = false;
                }
                $cntr = 0;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 15;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
                $searchAll = true;
                $fltrTyp = isset($_POST['acaRgstratnFilterBy']) ? cleanInputData($_POST['acaRgstratnFilterBy']) : "Relation Type";
                $fltrTypValue = isset($_POST['acaRgstratnFilterByVal']) ? cleanInputData($_POST['acaRgstratnFilterByVal']) : "All";

                $qShwCrntOnly = true;
                if (isset($_POST['qShwCrntOnly'])) {
                    $qShwCrntOnly = cleanInputData($_POST['qShwCrntOnly']) === "true" ? true : false;
                }

                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                $total = get_AssessShtPrsnsTtl($srchFor, $srchIn, $assessSbmtdSheetID);
                //var_dump($_POST);
                //echo "TT:" . $total;
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $extra4Where = " and a.person_id IN (select y1. from aca.aca_assmnt_col_vals y1 where )";
                $curIdx = $pageNo - 1;
                $result = get_AssessShtPrsns($srchFor, $srchIn, $curIdx, $lmtSze, $assessSbmtdSheetID);
                $cntr = 0;
                $vwtyp = 2;
            ?>
                <div class="basic_person_fs123" style="padding-top:2px !important;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-3" style="padding:0px 0px 0px 0px !important;float:left;">
                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;display:none;" onclick="getAssessShtHdr('', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="exprtAsessShts();" data-toggle="tooltip" title="Export Assessment Lines">
                                    <img src="cmn_images/document_export.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Export
                                </button>
                                <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="" data-toggle="tooltip" title="Import Assessment Lines">
                                    <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Import
                                </button>
                            </div>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <input class="form-control" id="assessShtHdrSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                        echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                        ?>" onkeyup="enterKeyFuncAssessShtHdr(event, '', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>')">
                                    <input id="assessShtHdrPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAssessShtHdr('clear', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAssessShtHdr('', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="assessShtHdrSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "", "", "", "", "");
                                        $srchInsArrys = array(
                                            "ID/Full Name", "Full Name", "Residential Address", "Contact Information", "Linked Firm/Workplace", "Person Type",
                                            "Date of Birth", "Home Town", "Gender", "Marital Status"
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
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="assessShtHdrDsplySze" style="min-width:70px !important;">
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
                                            <a class="rhopagination" href="javascript:getAssessShtHdr('previous', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAssessShtHdr('next', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');" aria-label="Next">
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
                            <table class="table table-striped table-bordered table-responsive" id="oneAssessSheetTransLinesTable" cellspacing="0" width="100%" style="width:100% !important;">
                                <thead>
                                    <tr>
                                        <th style="min-width:30px;">No.-[Rec. ID]</th>
                                        <th style="min-width:175px !important;">Full Name (ID No.)</th>
                                        <?php
                                        $colWidth = "max-width:70px !important;width:70px !important;";
                                        $resultHdr = get_AssessShtGrpCols("02-Detail", $assessTypeID);
                                        $colscntr1 = 0;
                                        $ttlColS = loc_db_num_rows($resultHdr);
                                        $colsIDs = array_fill(0, $ttlColS, -1);
                                        $colNos = array_fill(0, $ttlColS, 1);
                                        $colsNames = array_fill(0, $ttlColS, "");
                                        $colsTypes = array_fill(0, $ttlColS, "");
                                        $colsIsFrmlr = array_fill(0, $ttlColS, "1");
                                        $colMinVals = array_fill(0, $ttlColS, 0);
                                        $colMaxVals = array_fill(0, $ttlColS, 0);
                                        $colsIsDsplyd = array_fill(0, $ttlColS, "1");
                                        $colsHtmlCss = array_fill(0, $ttlColS, "");
                                        while ($rwHdr = loc_db_fetch_array($resultHdr)) {
                                            $colsIDs[$colscntr1] = (int) $rwHdr[0];
                                            $colsNames[$colscntr1] = $rwHdr[2];
                                            $colsTypes[$colscntr1] = $rwHdr[4];
                                            $colsIsFrmlr[$colscntr1] = $rwHdr[13];
                                            $colNos[$colscntr1] = (int) $rwHdr[15];
                                            $colMinVals[$colscntr1] = (int) $rwHdr[16];
                                            $colMaxVals[$colscntr1] = (int) $rwHdr[17];
                                            $colsIsDsplyd[$colscntr1] = $rwHdr[18];
                                            $colsHtmlCss[$colscntr1] = $rwHdr[19];
                                            $colscntr1++;
                                        }
                                        $colscntr = 0;
                                        while ($colscntr < count($colsNames)) {
                                            $tdStyle = "";
                                            if ($colsIsDsplyd[$colscntr] == "0") {
                                                $tdStyle = "display:none;";
                                            }
                                        ?>
                                            <th style="text-align: right;<?php echo $tdStyle . $colWidth; ?>"><?php echo $colsNames[$colscntr]; ?></th>
                                        <?php
                                            $colscntr++;
                                        }
                                        ?>
                                        <th style="max-width:30px;width:30px;display:none;">...</th>
                                        <?php if ($canVwRcHstry) { ?>
                                            <th style="max-width:30px;width:30px;display:none;">...</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        if ($pkID <= 0 && $cntr <= 0) {
                                            $pkID = (float) $row[0];
                                        }
                                        $academicSttngID = (float) $row[1];
                                        //border-top:1px solid #ddd;
                                        $cntr += 1;
                                    ?>
                                        <tr id="assessShtColRow_<?php echo $cntr; ?>" class="hand_cursor">
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr) . "-[" . $row[0] . "]"; ?></td>
                                            <td class="lovtd"><?php echo $row[6] . " (" . $row[5] . ")"; ?>
                                                <input type="hidden" class="form-control" aria-label="..." id="assessShtColRow<?php echo $cntr; ?>_LineID" value="<?php echo $row[0]; ?>">
                                                <input type="hidden" class="form-control" aria-label="..." id="assessShtColRow<?php echo $cntr; ?>_PrsnID" value="<?php echo $row[4]; ?>">
                                                <input type="hidden" class="form-control" aria-label="..." id="assessShtColRow<?php echo $cntr; ?>_PrsnNm" value="<?php echo $row[6]; ?>">
                                            </td>
                                            <?php
                                            $colscntr = 0;
                                            while ($colscntr < count($colsIDs)) {
                                                $columnID = (int) $colsIDs[$colscntr];
                                                $columnNo = (int) $colNos[$colscntr];
                                                $prsnDValPulld = $row[7 + $columnNo];
                                                $isRqrdFld = "rqrdFld";
                                                $tdClass = "lovtd555";
                                                $tdStyle = "padding: 0px !important;";
                                                if ($colsIsFrmlr[$colscntr] == "1" || $canEdt === false) {
                                                    $tdClass = "lovtd";
                                                    $tdStyle = "";
                                                }
                                                if ($colsIsDsplyd[$colscntr] == "0") {
                                                    $tdStyle = "display:none;";
                                                }
                                                $minValRhoData = $colMinVals[$colscntr];
                                                $maxValRhoData = $colMaxVals[$colscntr];
                                            ?>
                                                <td class="<?php echo $tdClass; ?>" style="text-align: right;<?php echo $tdStyle . $colWidth; ?>">
                                                    <?php
                                                    if ($colsIsFrmlr[$colscntr] == "1" || $canEdt === false) {
                                                        echo str_replace("{:p_col_value}", $prsnDValPulld, $colsHtmlCss[$colscntr]);
                                                    } else {
                                                        if ($colsTypes[$colscntr] == "Date") {
                                                    ?>
                                                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                <input class="form-control assessShtColRow<?php echo $cntr; ?> assesScoreM assesScore<?php echo $columnNo; ?> <?php echo $isRqrdFld; ?>" size="16" data-rhodata="" type="text" id="assessShtColRow<?php echo $cntr . "_AsessScore-" . $columnNo; ?>" value="<?php echo $prsnDValPulld; ?>" style="width:100% !important;">
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                            </div>
                                                        <?php
                                                        } else if ($colsTypes[$colscntr] == "Number") {
                                                        ?>
                                                            <input class="form-control assessShtColRow<?php echo $cntr; ?> assesScoreM assesScoreNum assesScore<?php echo $columnNo; ?> <?php echo $isRqrdFld; ?>" id="assessShtColRow<?php echo $cntr . "_AsessScore-" . $columnNo; ?>" min-rhodata="<?php echo $minValRhoData; ?>" max-rhodata="<?php echo $maxValRhoData; ?>" type="text" placeholder="" value="<?php echo number_format(((float) $prsnDValPulld), 2); ?>" style="width:100% !important;text-align: right;margin:0px !important;" onblur="vldtAssessColNumFld('assessShtColRow<?php echo $cntr . "_AsessScore-" . $columnNo; ?>');" onkeypress="gnrlFldKeyPress(event, 'assessShtColRow<?php echo $cntr . "_AsessScore-" . $columnNo; ?>', 'oneAssessSheetTransLinesTable', 'assesScore<?php echo $columnNo; ?>');" />
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <input class="form-control assessShtColRow<?php echo $cntr; ?> assesScoreM assesScore<?php echo $columnNo; ?> <?php echo $isRqrdFld; ?>" id="assessShtColRow<?php echo $cntr . "_AsessScore-" . $columnNo; ?>" data-rhodata="" type="text" placeholder="" value="<?php echo $prsnDValPulld; ?>" style="width:100% !important;text-align: right;margin:0px !important;" />
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            <?php
                                                $colscntr++;
                                            }
                                            ?>
                                            <td class="lovtd" style="display:none;">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Basic Profile" onclick="getBscProfileForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'dtAdmnBscPrsnPrflForm', 'View Person Basic Profile', <?php echo $row[0]; ?>, 0, 1, 'VIEW');" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <td class="lovtd" style="display:none;">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                            echo urlencode(encrypt1(($row[0] . "|prs.prsn_names_nos|person_id"), $smplTokenWord1));
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
                <?php
            } else if ($vwtyp == 201) {
            } else if ($vwtyp == 3) {
                //Footer
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                $assessSbmtdSheetID = isset($_POST['assessSbmtdSheetID']) ? $_POST['assessSbmtdSheetID'] : -1;
                $assessSbmtdSheetNm = isset($_POST['assessSbmtdSheetNm']) ? $_POST['assessSbmtdSheetNm'] : "";
                $assessTypeID = (int) getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assessment_type_id", $assessSbmtdSheetID);
                $assessSheetStatus = getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assess_sheet_status", $assessSbmtdSheetID);
                if ($assessSheetStatus === "Closed") {
                    $canEdt = false;
                }
                $academicSttngID = -1;
                $pkID = $assessSbmtdSheetID;
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
                $total = 0;
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = null;
                $cntr = 0;
                $vwtyp = 3;
                $result1 = get_AssessShtGrpCols("03-Footer", $assessTypeID);
                $cntr1 = 0;
                $gcntr1 = 0;
                $cntr1Ttl = loc_db_num_rows($result1);
                if ($cntr1Ttl > 0) {
                ?>
                    <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                            <?php
                            while ($row1 = loc_db_fetch_array($result1)) {
                                if ($gcntr1 == 0) {
                                    $gcntr1 += 1;
                                }
                                if (($cntr1 % 2) == 0) {
                            ?>
                                    <div class="row">
                                    <?php
                                }
                                    ?>
                                    <div class="col-md-6">
                                        <div class="form-group form-group-sm">
                                            <label class="control-label col-md-4"><?php echo $row1[2]; ?>:</label>
                                            <div class="col-md-8">
                                                <?php
                                                $columnID = (int) $row1[0];
                                                $columnNo = (int) $row1[15];
                                                $prsnDValPulld = get_AssessShtColVal($assessSbmtdSheetID, $academicSttngID, $columnNo);
                                                //get_PrsExtrData($pkID, $row1[1]);
                                                $isRqrdFld = ($row1[12] === "1") ? "rqrdFld" : "";
                                                if ($row1[13] == "1" || $canEdt === false) {
                                                    echo str_replace("{:p_col_value}", $prsnDValPulld, $row1[19]);
                                                } else {
                                                    if ($row1[4] == "Date") {
                                                ?>
                                                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                            <input class="form-control assessShtHdrFtrVal <?php echo $isRqrdFld; ?>" size="16" type="text" id="assessShtHdrFtrFld_<?php echo $columnNo; ?>" value="<?php echo $prsnDValPulld; ?>">
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>
                                                    <?php
                                                    } else if ($row1[4] == "Number") {
                                                    ?>
                                                        <input class="form-control assessShtHdrFtrVal <?php echo $isRqrdFld; ?>" id="assessShtHdrFtrFld_<?php echo $columnNo; ?>" type="text" placeholder="" value="<?php echo $prsnDValPulld; ?>" />
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <input class="form-control assessShtHdrFtrVal <?php echo $isRqrdFld; ?>" id="assessShtHdrFtrFld_<?php echo $columnNo; ?>" type="text" placeholder="" value="<?php echo $prsnDValPulld; ?>" />
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $cntr1 += 1;
                                    if (($cntr1 % 2) == 0 || $cntr1 == ($cntr1Ttl)) {
                                        $cntr1 = 0;
                                    ?>
                                    </div>
                            <?php
                                    }
                                }
                                if ($gcntr1 == 1) {
                                    $gcntr1 = 0;
                                }
                            ?>
                        </div>
                    </fieldset>
<?php
                }
            } else if ($vwtyp == 301) {
            }
        }
    }
}
?>