<?php
$canAdd = test_prmssns($dfltPrvldgs[34], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[35], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[36], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
$assessTypeFltr = "Summary Report Per Person";
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
                $reportCrdHdrID = isset($_POST['reportCrdHdrID']) ? (float) cleanInputData($_POST['reportCrdHdrID']) : -1;
                $reportCardstatus = getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assess_sheet_status", $reportCrdHdrID);
                if ($reportCardstatus === "Closed") {
                    $canEdt = false;
                }
                $reportCrdHdrName = isset($_POST['reportCrdHdrName']) ? cleanInputData($_POST['reportCrdHdrName']) : "";
                $reportCrdHdrDesc = isset($_POST['reportCrdHdrDesc']) ? cleanInputData($_POST['reportCrdHdrDesc']) : "";
                $reportCrdHdrTypeID = isset($_POST['reportCrdHdrTypeID']) ? (int) cleanInputData($_POST['reportCrdHdrTypeID']) : -1;
                $reportCrdHdrTypeNm = isset($_POST['reportCrdHdrTypeNm']) ? cleanInputData($_POST['reportCrdHdrTypeNm']) : "";

                $reportCrdHdrPeriodID = isset($_POST['reportCrdHdrPeriodID']) ? (int) cleanInputData($_POST['reportCrdHdrPeriodID']) : -1;
                $reportCrdHdrPeriodNm = isset($_POST['reportCrdHdrPeriodNm']) ? cleanInputData($_POST['reportCrdHdrPeriodNm']) : "";

                $reportCrdHdrPrsnID = isset($_POST['reportCrdHdrPrsnID']) ? (int) cleanInputData($_POST['reportCrdHdrPrsnID']) : -1;
                $reportCrdHdrPrsnNm = isset($_POST['reportCrdHdrPrsnNm']) ? cleanInputData($_POST['reportCrdHdrPrsnNm']) : "";

                $reportCrdHdrClassID = isset($_POST['reportCrdHdrClassID']) ? (int) cleanInputData($_POST['reportCrdHdrClassID']) : -1;
                $reportCrdHdrClassNm = isset($_POST['reportCrdHdrClassNm']) ? cleanInputData($_POST['reportCrdHdrClassNm']) : "";

                $reportCrdHdrCrseID = isset($_POST['reportCrdHdrCrseID']) ? (int) cleanInputData($_POST['reportCrdHdrCrseID']) : -1;
                $reportCrdHdrCrseNm = isset($_POST['reportCrdHdrCrseNm']) ? cleanInputData($_POST['reportCrdHdrCrseNm']) : "";
                $reportCrdHdrSbjctID = isset($_POST['reportCrdHdrSbjctID']) ? (int) cleanInputData($_POST['reportCrdHdrSbjctID']) : -1;
                $reportCrdHdrSbjctNm = isset($_POST['reportCrdHdrSbjctNm']) ? cleanInputData($_POST['reportCrdHdrSbjctNm']) : "";
                $reportCrdHdrStatus = isset($_POST['reportCrdHdrStatus']) ? cleanInputData($_POST['reportCrdHdrStatus']) : "Open for Editing";
                $reportCrdHdrAsdPrsID = isset($_POST['reportCrdHdrAsdPrsID']) ? (float) cleanInputData($_POST['reportCrdHdrAsdPrsID']) : -1;
                $reportCrdHdrAsdPrsNm = isset($_POST['reportCrdHdrAsdPrsNm']) ? cleanInputData($_POST['reportCrdHdrAsdPrsNm']) : "";

                $slctdHdrFtrValues = isset($_POST['slctdHdrFtrValues']) ? cleanInputData($_POST['slctdHdrFtrValues']) : "";
                $slctdDetLineValues = isset($_POST['slctdDetLineValues']) ? cleanInputData($_POST['slctdDetLineValues']) : "";
                $shdSbmt = isset($_POST['shdSbmt']) ? (int) cleanInputData($_POST['shdSbmt']) : 0;
                $exitErrMsg = "";
                if ($canEdt === true) {
                    if ($reportCrdHdrName == "" || $reportCrdHdrDesc == "") {
                        $exitErrMsg .= "Please enter Report Card Name and Description!<br/>";
                    }
                    if ($reportCrdHdrTypeID <= 0 || $reportCrdHdrPeriodID <= 0) {
                        $exitErrMsg .= "Please enter Assessment Type and Period!<br/>";
                    }
                    if ($reportCrdHdrClassID <= 0) {
                        $exitErrMsg .= "Please enter $groupLabel!<br/>";
                    }
                    if (trim($exitErrMsg) !== "") {
                        $arr_content['percent'] = 100;
                        $arr_content['reportCrdHdrID'] = $reportCrdHdrID;
                        $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                        echo json_encode($arr_content);
                        exit();
                    }
                }
                $oldID = (float) getGnrlRecID("aca.aca_assess_sheet_hdr", "assess_sheet_name", "assess_sheet_hdr_id", $reportCrdHdrName, $orgID);
                $afftctd = 0;
                if (($oldID <= 0 || $oldID == $reportCrdHdrID)) {
                    if ($canEdt === true) {
                        if ($reportCrdHdrID <= 0) {
                            $reportCrdHdrID = getNew_AssessShtHdrID();
                            $afftctd += createAssessShtHdr(
                                $reportCrdHdrID,
                                $reportCrdHdrName,
                                $reportCrdHdrDesc,
                                $reportCrdHdrClassID,
                                $reportCrdHdrTypeID,
                                $reportCrdHdrCrseID,
                                $reportCrdHdrSbjctID,
                                $reportCrdHdrPrsnID,
                                $reportCrdHdrPeriodID,
                                $orgID,
                                $reportCrdHdrStatus,
                                $reportCrdHdrAsdPrsID
                            );
                        } else {
                            $afftctd += updtAssessShtHdr(
                                $reportCrdHdrID,
                                $reportCrdHdrName,
                                $reportCrdHdrDesc,
                                $reportCrdHdrClassID,
                                $reportCrdHdrTypeID,
                                $reportCrdHdrCrseID,
                                $reportCrdHdrSbjctID,
                                $reportCrdHdrPrsnID,
                                $reportCrdHdrPeriodID,
                                $orgID,
                                $reportCrdHdrStatus,
                                $reportCrdHdrAsdPrsID
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
                        if (trim($slctdHdrFtrValues, "|~") != "" && $reportCrdHdrID > 0) {
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
                            $afftctd1 += updateAsessColsData1($reportCrdHdrID, -1, $data_cols);
                        }
                        if (trim($slctdDetLineValues, "|~") != "" && $reportCrdHdrID > 0) {
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
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Report Card Successfully Saved!"
                            . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                    } else {
                        $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Report Card Successfully Saved!";
                    }
                    if ($shdSbmt == 2) {
                        $exitErrMsg = compute_one_assess_sht($reportCrdHdrID, $usrID);
                        if (strpos($exitErrMsg, "SUCCESS") !== FALSE) {
                            $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                        } else {
                            $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                        }
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['reportCrdHdrID'] = $reportCrdHdrID;
                    $arr_content['message'] = $exitErrMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Either the New Report Card Name is in Use <br/>or Data Supplied is Incomplete!</span>";
                    $arr_content['percent'] = 100;
                    $arr_content['reportCrdHdrID'] = $reportCrdHdrID;
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
                $assessSheetPrsnNm = getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "prs.get_prsn_name(assessed_person_id) || ' (' || prs.get_prsn_loc_id(assessed_person_id)||')'", $assessSbmtdSheetID);
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
                    $hdngs1[0] = "Report Card Name:";
                    $hdngs1[1] = $assessSbmtdSheetNm;
                    fputcsv($opndfile, $hdngs1);

                    $hdngs2 = array_fill(0, 2, "");
                    $hdngs2[0] = "Assessment Type:";
                    $hdngs2[1] = $assessSheetType;
                    fputcsv($opndfile, $hdngs2);

                    $hdngs21 = array_fill(0, 2, "");
                    $hdngs21[0] = "Person Assessed:";
                    $hdngs21[1] = $assessSheetPrsnNm;
                    fputcsv($opndfile, $hdngs21);

                    $hdngs3 = array_fill(0, 2, "");
                    $hdngs3[0] = "Report Card Status:";
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
                    $hdngs = array_fill(0, ($ttlColS + 3), "");
                    $hdngs[0] = "Rec. No.**";
                    $hdngs[1] = $courseLabel;
                    $hdngs[2] = $sbjctLabel;
                    while ($colscntr < count($colsNames)) {
                        $tdStyle = "";
                        if ($colsIsDsplyd[$colscntr] == "0") {
                            $tdStyle = "display:none;";
                        }
                        $hdngs[$colscntr + 3] = $colsNames[$colscntr];
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
                    $result = get_ReportCardLns("%", $courseLabel . " Name", 0, $limit_size, $assessSbmtdSheetID);
                    $total = loc_db_num_rows($result);
                    $fieldCntr = loc_db_num_fields($result);
                    while ($row = loc_db_fetch_array($result)) {
                        $crntRw = array_fill(0, ($ttlColS + 3), "");
                        $crntRw[0] = $row[0];
                        $crntRw[1] = str_replace(",", "", $row[5]);
                        $crntRw[2] = str_replace(",", "", $row[6]);
                        $colscntr = 0;
                        while ($colscntr < count($colsIDs)) {
                            $columnID = (int) $colsIDs[$colscntr];
                            $columnNo = (int) $colNos[$colscntr];
                            $prsnDValPulld = $row[7 + $columnNo];
                            $minValRhoData = $colMinVals[$colscntr];
                            $maxValRhoData = $colMaxVals[$colscntr];
                            $crntRw[$colscntr + 3] = $prsnDValPulld;
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
                //Report Cards
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0&mdl=$mdlACAorPMS');\">
                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                <span style=\"text-decoration:none;\">$menuItems[0]</span>
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
                $qShwSelfOnly = $vwOnlySelfRpts;
                if ($vwOnlySelfRpts === false) {
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

                $reportTitle1 = "Auto-Generate and Compute Report/Score Cards";
                $reportName1 = "Auto-Generate and Compute Report/Score Cards";
                $rptID1 = getRptID($reportName1);
?>
                <fieldset class="">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs" style="margin-top:1px !important;">
                                <li class="active"><a data-toggle="tabajxreportcard" data-rhodata="" href="#reportCardMainList" id="reportCardMainListtab">Summary List</a></li>
                                <li class=""><a data-toggle="tabajxreportcard" data-rhodata="" href="#reportCardDetList" id="reportCardDetListtab">Detailed List</a></li>
                            </ul>
                            <div class="custDiv" style="padding:0px !important;min-height: 30px !important;">
                                <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                    <div id="reportCardMainList" class="tab-pane fadein active" style="border:none !important;padding:0px 0px 0px 0px !important;">
                                        <form id='reportCardsForm' action='' method='post' accept-charset='UTF-8'>
                                            <!--ROW ID-->
                                            <input class="form-control" id="tblRowID" type="hidden" placeholder="ROW ID" />
                                            <fieldset class="">
                                                <legend class="basic_person_lg1" style="color: #003245">REPORT CARDS</legend>
                                                <div class="row" style="margin-bottom:0px;">
                                                    <?php
                                                    if ($canAdd === true) {
                                                    ?>
                                                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 15px !important;">
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getReportCardDets('clear', '#reportCardDetList', 'grp=15&typ=1&pg=1&vtyp=1&assessSbmtdSheetID=-1&assessSbmtdSheetNm=&mdl=<?php echo $mdlACAorPMS;?>', 1);">
                                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                New Report Card
                                                            </button>
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getMyMdlRptRuns('', 'ShowDialog', 'grp=9&typ=1&pg=1&vtyp=50&sbmtdRptID=<?php echo $rptID1; ?>&mdl=<?php echo $mdlACAorPMS;?>');" data-toggle="tooltip" data-placement="bottom" title="<?php echo $reportTitle1; ?>">
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
                                                            <input class="form-control" id="reportCardsSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                                            echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                                            ?>" onkeyup="enterKeyFuncReportCards(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0&mdl=<?php echo $mdlACAorPMS;?>')">
                                                            <input id="reportCardsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getReportCards('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0&mdl=<?php echo $mdlACAorPMS;?>');">
                                                                <span class="glyphicon glyphicon-remove"></span>
                                                            </label>
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getReportCards('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0&mdl=<?php echo $mdlACAorPMS;?>');">
                                                                <span class="glyphicon glyphicon-search"></span>
                                                            </label>
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="reportCardsSrchIn">
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
                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="reportCardsDsplySze" style="min-width:70px !important;">
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
                                                    <div class="col-md-1" style="display:inline-block;padding:0px 0px 0px 15px !important;">
                                                        <nav aria-label="Page navigation">
                                                            <ul class="pagination" style="margin: 0px !important;">
                                                                <li>
                                                                    <a href="javascript:getReportCards('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0&mdl=<?php echo $mdlACAorPMS;?>');" aria-label="Previous">
                                                                        <span aria-hidden="true">&laquo;</span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:getReportCards('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0&mdl=<?php echo $mdlACAorPMS;?>');" aria-label="Next">
                                                                        <span aria-hidden="true">&raquo;</span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </nav>
                                                    </div>
                                                    <?php if ($vwOnlySelfRpts == false) { ?>
                                                        <div class="col-md-1" style="display:inline-block;padding:5px 2px 0px 0px !important;">
                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                <label class="form-check-label" title="Only Self-Created">
                                                                    <?php
                                                                    $shwSelfOnlyChkd = "";
                                                                    if ($qShwSelfOnly == true) {
                                                                        $shwSelfOnlyChkd = "checked=\"true\"";
                                                                    }
                                                                    ?>
                                                                    <input type="checkbox" class="form-check-input" onclick="getReportCards('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');" id="reportCardsShwUsrOnly" name="reportCardsShwUsrOnly" <?php echo $shwSelfOnlyChkd; ?>>
                                                                    Self-Created
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-striped table-bordered table-responsive" id="reportCardsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th style="max-width:25px;width:25px;">No.</th>
                                                                    <th style="max-width:25px;width:25px;">...</th>
                                                                    <th style="max-width:25px;width:25px;">...</th>
                                                                    <th style="">Report Card Name</th>
                                                                    <th style="">Sheet Type</th>
                                                                    <th style="">Group</th>
                                                                    <th style="display:none;">Programme/Objective</th>
                                                                    <th style="display:none;">Subject/Task</th>
                                                                    <th style="">Period</th>
                                                                    <th style="">Person Being Assessed</th>
                                                                    <th style="text-align:right;max-width:60px;width:60px;">Subject Count</th>
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
                                                                $reportCardID = -1;
                                                                $reportCardNm = "";
                                                                while ($row = loc_db_fetch_array($result)) {
                                                                    $cntr += 1;
                                                                    if ($cntr == 1) {
                                                                        $reportCardID = (float) $row[0];
                                                                        $reportCardNm = $row[1];
                                                                    }
                                                                    $reportCardID1 = (float) $row[0];
                                                                    $reportCardNm1 = $row[1];
                                                                    $reportCardClassID = (float) $row[2];
                                                                    $reportCardClassNm = $row[3];
                                                                    $reportCardTypeID = (float) $row[4];
                                                                    $reportCardTypeNm = $row[5];
                                                                    $reportCardCrseID = (float) $row[6];
                                                                    $reportCardCrseNm = $row[7];
                                                                    $reportCardsbjctID = (float) $row[8];
                                                                    $reportCardsbjctNm = $row[9];
                                                                    $reportCardPrdID = (float) $row[10];
                                                                    $reportCardPrdNm = $row[11];
                                                                    $reportCardAdminID = (float) $row[12];
                                                                    $reportCardAdminNm = $row[13];
                                                                    $reportCardPrsnCount = (float) $row[19];
                                                                    $reportCardstatus = $row[16];
                                                                    $reportCrdHdrAsdPrsID = (float) $row[17];
                                                                    $reportCrdHdrAsdPrsNm = $row[18];
                                                                    $statusStyle = "color:green;font-weight:bold;";
                                                                    if ($reportCardstatus === "Closed") {
                                                                        $statusStyle = "color:red;font-weight:bold;";
                                                                    }
                                                                ?>
                                                                    <tr id="reportCardsHdrsRow_<?php echo $cntr; ?>">
                                                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Report Card" onclick="getReportCardDets('clear', '#reportCardDetList', 'grp=15&typ=1&pg=1&vtyp=1&assessSbmtdSheetID=<?php echo $reportCardID1; ?>&assessSbmtdSheetNm=<?php echo urlencode($reportCardNm1); ?>&mdl=<?php echo $mdlACAorPMS;?>', <?php echo $reportCardID1; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                                                <?php
                                                                                if ($canAdd === true) {
                                                                                ?>
                                                                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                <?php } else { ?>
                                                                                    <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                <?php } ?>
                                                                            </button>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="printEmailFullTermRpt(<?php echo $reportCardID1; ?>);" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title="Print Report">
                                                                                <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                        <td class="lovtd"><?php echo $reportCardNm1; ?></td>
                                                                        <td class="lovtd"><?php echo $reportCardTypeNm; ?></td>
                                                                        <td class="lovtd"><?php echo $reportCardClassNm; ?></td>
                                                                        <td class="lovtd" style="display:none;"><?php echo $reportCardCrseNm; ?></td>
                                                                        <td class="lovtd" style="display:none;"><?php echo $reportCardsbjctNm; ?></td>
                                                                        <td class="lovtd"><?php echo $reportCardPrdNm; ?></td>
                                                                        <td class="lovtd"><?php echo $reportCrdHdrAsdPrsNm; ?></td>
                                                                        <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                                                                                                                    echo number_format($reportCardPrsnCount, 0);
                                                                                                                                                    ?>
                                                                        </td>
                                                                        <td class="lovtd" style="<?php echo $statusStyle; ?>"><?php echo $reportCardstatus; ?></td>
                                                                        <?php
                                                                        if ($canDel === true) {
                                                                        ?>
                                                                            <td class="lovtd">
                                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Report Card" onclick="delReportCrdHdr('reportCardsHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                                    <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                </button>
                                                                                <input type="hidden" id="reportCardsHdrsRow<?php echo $cntr; ?>_HdrID" name="reportCardsHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $reportCardID1; ?>">
                                                                            </td>
                                                                        <?php } ?>
                                                                        <?php
                                                                        if ($canVwRcHstry === true) {
                                                                        ?>
                                                                            <td class="lovtd">
                                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                                        echo urlencode(encrypt1(($reportCardID1 . "|aca.aca_assess_sheet_hdr|assess_sheet_hdr_id"),
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
                                                        <input type="hidden" id="reportCardID" name="reportCardID" value="<?php echo $reportCardID; ?>">
                                                        <input type="hidden" id="reportCardNm" name="reportCardNm" value="<?php echo $reportCardNm; ?>">
                                                        <input type="hidden" id="reportCardHdnTabNm" name="reportCardHdnTabNm" value="asShtDetlsInfo">
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                    <div id="reportCardDetList" class="tab-pane fadein" style="border:none !important;padding:0px 0px 0px 0px !important;">
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
                $reportCardstatus = getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assess_sheet_status", $assessSbmtdSheetID);
                if ($reportCardstatus === "Closed") {
                    $canEdt = false;
                }
                $academicSttngID = -1;
                $cntr = 0;
            ?>
                <form id='reportCrdHdrForm' action='' method='post' accept-charset='UTF-8'>
                    <fieldset class="">
                        <div class="row" style="margin-bottom:0px;">
                            <?php if ($canAdd === true) {
                            ?>
                                <div class="col-md-6" style="padding:0px 15px 0px 15px !important;">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getReportCardDets('clear', '#reportCardDetList', 'grp=15&typ=1&pg=1&vtyp=1&assessSbmtdSheetID=-1&assessSbmtdSheetNm=&mdl=<?php echo $mdlACAorPMS;?>', 1);" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title=" New Report Card">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Report
                                    </button>
                                    <?php if ($assessSbmtdSheetID <= 0 || $canEdt === true) { ?>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="saveReportCrdHdrForm();" style="width:100% !important;">
                                            <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        </button>
                                    <?php } ?>
                                    <button type="button" class="btn btn-default" style="" onclick="getReportCardDets('clear', '#reportCardDetList', 'grp=15&typ=1&pg=1&vtyp=1&mdl=<?php echo $mdlACAorPMS;?>');" style="width:100% !important;">
                                        <img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                    <button type="button" class="btn btn-default" style="" onclick="saveReportCrdHdrForm(2);" style="width:100% !important;">
                                        <img src="cmn_images/calculator.gif" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                        Compute Values
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="exprtScoreCards();" data-toggle="tooltip" title="Export Score Card Lines">
                                        <img src="cmn_images/document_export.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="" data-toggle="tooltip" title="Import Score Card Lines">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Import
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="printEmailFullTermRpt(<?php echo $assessSbmtdSheetID; ?>);" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title="Print Report">
                                        <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 1px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Print
                                    </button>
                                </div>
                            <?php
                            }
                            ?>
                            <div class="col-md-6" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group" style="width:100% !important;">
                                    <label class="btn btn-default btn-file input-group-addon">
                                        <span style="font-weight:bold;">Selected Report Card:</span>
                                    </label>
                                    <input type="text" class="form-control" aria-label="..." id="assessSbmtdSheetNm" name="assessSbmtdSheetNm" value="<?php echo $assessSbmtdSheetNm; ?>" readonly="true" style="width:100% !important;">
                                    <input type="hidden" class="form-control" aria-label="..." id="assessSbmtdSheetID" value="<?php echo $assessSbmtdSheetID; ?>" style="width:100% !important;">
                                    <input type="hidden" class="form-control" aria-label="..." id="assessSbmtdSheetType" value="<?php echo $assessTypeFltr; ?>" style="width:100% !important;">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Assessment Sheets', 'allOtherInputOrgID', 'assessSbmtdSheetType', '', 'radio', true, '', 'assessSbmtdSheetID', 'assessSbmtdSheetNm', 'clear', 1, '', function () {
                                                                getReportCardDets('clear', '#reportCardDetList', 'grp=15&typ=1&pg=1&vtyp=1&mdl=<?php echo $mdlACAorPMS;?>');
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
                                            <ul class="nav nav-tabs" style="margin-top:1px !important;display:none">
                                                <li class="active"><a data-toggle="tabajxasShtdetls" data-rhodata="" href="#asShtDetlsInfo" id="asShtDetlsInfotab">HEADER INFORMATION</a></li>
                                                <li class=""><a data-toggle="tabajxasShtdetls" data-rhodata="" href="#asShtDetlsTrans" id="asShtDetlsTranstab">DETAILS</a></li>
                                                <li class=""><a data-toggle="tabajxasShtdetls" data-rhodata="" href="#asShtDetlsPMRecs" id="asShtDetlsPMRecstab">FOOTER INFORMATION</a></li>
                                            </ul>
                                            <div class="custDiv" style="padding:0px !important;min-height: 30px !important;">
                                                <div class="tab-content" style="padding:3px 5px 2px 5px!important;">
                                                    <div id="asShtDetlsInfo" class="" style="border:none !important;padding:0px 0px 0px 0px !important;">
                                                        <div class="container-fluid" id="reportCardHdrDetailInfo">
                                                            <?php
                                                            $reportCrdHdrID = -1;
                                                            $reportCrdHdrName = "";
                                                            $reportCrdHdrClassID = -1;
                                                            $reportCrdHdrClassNm = "";
                                                            $reportCrdHdrTypeID = -1;
                                                            $reportCrdHdrTypeNm = "";
                                                            $reportCrdHdrType = $assessTypeFltr;
                                                            $reportCrdHdrCrseID = -1;
                                                            $reportCrdHdrCrseNm = "";
                                                            $reportCrdHdrSbjctID = -1;
                                                            $reportCrdHdrSbjctNm = "";
                                                            $reportCrdHdrPeriodID = -1;
                                                            $reportCrdHdrPeriodNm = "";
                                                            $reportCrdHdrPrsnID = $prsnid;
                                                            $reportCrdHdrPrsnNm = getPrsnFullNm($prsnid);
                                                            $reportCrdHdrDesc = "";
                                                            $reportCrdHdrStatus = "";
                                                            $reportCrdHdrAsdPrsID = -1;
                                                            $reportCrdHdrAsdPrsNm = "";
                                                            if ($pkID > 0) {
                                                                $result1 = get_One_AssessSheetHdr($pkID);
                                                                while ($row1 = loc_db_fetch_array($result1)) {
                                                                    $reportCrdHdrID = (float) $row1[0];
                                                                    $reportCrdHdrName = $row1[1];
                                                                    $reportCrdHdrClassID = (int) $row1[2];
                                                                    $reportCrdHdrClassNm = $row1[3];
                                                                    $reportCrdHdrTypeID = (int) $row1[4];
                                                                    $reportCrdHdrTypeNm = $row1[5];
                                                                    $reportCrdHdrCrseID = (int) $row1[6];
                                                                    $reportCrdHdrCrseNm = $row1[7];
                                                                    $reportCrdHdrSbjctID = (int) $row1[8];
                                                                    $reportCrdHdrSbjctNm = $row1[9];
                                                                    $reportCrdHdrPeriodID = (float) $row1[10];
                                                                    $reportCrdHdrPeriodNm = $row1[11];
                                                                    $reportCrdHdrPrsnID = (float) $row1[12];
                                                                    $reportCrdHdrPrsnNm = $row1[13];
                                                                    $reportCrdHdrDesc = $row1[16];
                                                                    $reportCrdHdrType = $row1[14];
                                                                    $reportCrdHdrStatus = $row1[17];
                                                                    $reportCrdHdrAsdPrsID = (float) $row1[18];
                                                                    $reportCrdHdrAsdPrsNm = $row1[19];
                                                                    $statusStyle = "padding:5px;color:green;font-weight:bold;";
                                                                    if ($reportCrdHdrStatus === "Closed") {
                                                                        $statusStyle = "padding:5px;color:red;font-weight:bold;";
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                            <div class="row">
                                                                <div class="col-md-6" style="padding:0px 1px 0px 0px !important;">
                                                                    <fieldset class="basic_person_fs123" style="padding-top:10px !important;">
                                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                            <label for="reportCrdHdrName" class="control-label col-lg-4">Sheet Name:</label>
                                                                            <div class="col-lg-8">
                                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="reportCrdHdrName" name="reportCrdHdrName" value="<?php echo $reportCrdHdrName; ?>" style="width:100% !important;">
                                                                                <input type="hidden" class="form-control" aria-label="..." id="reportCrdHdrID" name="reportCrdHdrID" value="<?php echo $reportCrdHdrID; ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                            <label for="reportCrdHdrDesc" class="control-label col-lg-4">Description:</label>
                                                                            <div class="col-lg-8">
                                                                                <textarea class="form-control rqrdFld" rows="3" cols="20" id="reportCrdHdrDesc" name="reportCrdHdrDesc" style="text-align:left !important;"><?php echo $reportCrdHdrDesc; ?></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                            <label for="reportCrdHdrTypeNm" class="control-label col-md-4">Assessment Type:</label>
                                                                            <div class="col-md-8">
                                                                                <div class="input-group">
                                                                                    <input class="form-control" id="reportCrdHdrTypeNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Assessment Type" type="text" min="0" placeholder="" value="<?php echo $reportCrdHdrTypeNm; ?>" readonly="true" />
                                                                                    <input type="hidden" id="reportCrdHdrTypeID" value="<?php echo $reportCrdHdrTypeID; ?>">
                                                                                    <input type="hidden" id="reportCrdHdrType" value="<?php echo $reportCrdHdrType; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Assessment Types', 'allOtherInputOrgID', 'reportCrdHdrType', '', 'radio', true, '', 'reportCrdHdrTypeID', 'reportCrdHdrTypeNm', 'clear', 1, '', function () {});">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                            <label for="reportCrdHdrStatus" class="control-label col-lg-4">Assessment Status:</label>
                                                                            <div class="col-lg-8">
                                                                                <?php
                                                                                if ($canEdt === true) {
                                                                                ?>
                                                                                    <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="reportCrdHdrStatus" style="min-width:70px !important;">
                                                                                        <?php
                                                                                        $valslctdArry = array("", "");
                                                                                        $dsplySzeArry = array("Open for Editing", "Closed");
                                                                                        for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                                                                            if ($reportCrdHdrStatus == $dsplySzeArry[$y]) {
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
                                                                                    <span style="<?php echo $statusStyle; ?>"><?php echo $reportCrdHdrStatus; ?></span>
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
                                                                            <label for="reportCrdHdrPeriodNm" class="control-label col-md-4">Assessment Period:</label>
                                                                            <div class="col-md-8">
                                                                                <div class="input-group">
                                                                                    <input class="form-control" id="reportCrdHdrPeriodNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" min="0" placeholder="" value="<?php echo $reportCrdHdrPeriodNm; ?>" readonly="true" />
                                                                                    <input type="hidden" id="reportCrdHdrPeriodID" value="<?php echo $reportCrdHdrPeriodID; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Assessment Periods', 'allOtherInputOrgID', '', '', 'radio', true, '', 'reportCrdHdrPeriodID', 'reportCrdHdrPeriodNm', 'clear', 1, '', function () {});">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                            <label for="reportCrdHdrPrsnNm" class="control-label col-md-4">Main Assessor:</label>
                                                                            <div class="col-md-8">
                                                                                <div class="input-group">
                                                                                    <input class="form-control" id="reportCrdHdrPrsnNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" min="0" placeholder="" value="<?php echo $reportCrdHdrPrsnNm; ?>" readonly="true" />
                                                                                    <input type="hidden" id="reportCrdHdrPrsnID" value="<?php echo $reportCrdHdrPrsnID; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', 'allOtherInputOrgID', '', '', 'radio', true, '', 'reportCrdHdrPrsnID', 'reportCrdHdrPrsnNm', 'clear', 1, '', function () {});">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                            <label for="reportCrdHdrAsdPrsNm" class="control-label col-md-4">Person Assessed:</label>
                                                                            <div class="col-md-8">
                                                                                <div class="input-group">
                                                                                    <input class="form-control" id="reportCrdHdrAsdPrsNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" min="0" placeholder="" value="<?php echo $reportCrdHdrAsdPrsNm; ?>" readonly="true" />
                                                                                    <input type="hidden" id="reportCrdHdrAsdPrsID" value="<?php echo $reportCrdHdrAsdPrsID; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', 'allOtherInputOrgID', '', '', 'radio', true, '', 'reportCrdHdrAsdPrsID', 'reportCrdHdrAsdPrsNm', 'clear', 1, '', function () {});">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                            <label for="reportCrdHdrClassNm" class="control-label col-md-4">Assessment Group:</label>
                                                                            <div class="col-md-8">
                                                                                <div class="input-group">
                                                                                    <input class="form-control" id="reportCrdHdrClassNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Select Assessment Group" type="text" min="0" placeholder="" value="<?php echo $reportCrdHdrClassNm; ?>" readonly="true" />
                                                                                    <input type="hidden" id="reportCrdHdrClassID" value="<?php echo $reportCrdHdrClassID; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Assessment Groups', 'allOtherInputOrgID', '', '', 'radio', true, '', 'reportCrdHdrClassID', 'reportCrdHdrClassNm', 'clear', 1, '', function () {});">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                                            <label for="reportCrdHdrCrseNm" class="control-label col-md-4">Programme/Objective:</label>
                                                                            <div class="col-md-8">
                                                                                <div class="input-group">
                                                                                    <input class="form-control" id="reportCrdHdrCrseNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" min="0" placeholder="" value="<?php echo $reportCrdHdrCrseNm; ?>" readonly="true" />
                                                                                    <input type="hidden" id="reportCrdHdrCrseID" value="<?php echo $reportCrdHdrCrseID; ?>">
                                                                                    <input type="hidden" id="reportCrdHdrCrseRecType" value="<?php echo $moduleType; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Assessment Objectives/Courses', 'reportCrdHdrClassID', 'reportCrdHdrCrseRecType', '', 'radio', true, '', 'reportCrdHdrCrseID', 'reportCrdHdrCrseNm', 'clear', 1, '', function () {});">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 0px 0px 0px !important;display:none;">
                                                                            <label for="reportCrdHdrSbjctNm" class="control-label col-md-4">Task/Subject:</label>
                                                                            <div class="col-md-8">
                                                                                <div class="input-group">
                                                                                    <input class="form-control" id="reportCrdHdrSbjctNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type="text" min="0" placeholder="" value="<?php echo $reportCrdHdrSbjctNm; ?>" readonly="true" />
                                                                                    <input type="hidden" id="reportCrdHdrSbjctID" value="<?php echo $reportCrdHdrSbjctID; ?>">
                                                                                    <input type="hidden" id="reportCrdHdrSbjctRecType" value="<?php echo $moduleType2; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Assessment Tasks/Subjects', 'reportCrdHdrClassID', 'reportCrdHdrCrseID', '', 'radio', true, '', 'reportCrdHdrSbjctID', 'reportCrdHdrSbjctNm', 'clear', 1, '', function () {});">
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
                                                                                                            <input class="form-control reportCrdHdrFtrVal <?php echo $isRqrdFld; ?>" size="16" type="text" id="reportCrdHdrFtrFld_<?php echo $columnNo; ?>" value="<?php echo $prsnDValPulld; ?>">
                                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                                        </div>
                                                                                                    <?php
                                                                                                    } else if ($row1[4] == "Number") {
                                                                                                    ?>
                                                                                                        <input class="form-control reportCrdHdrFtrVal <?php echo $isRqrdFld; ?>" id="reportCrdHdrFtrFld_<?php echo $columnNo; ?>" type="text" placeholder="" value="<?php echo $prsnDValPulld; ?>" />
                                                                                                    <?php
                                                                                                    } else {
                                                                                                    ?>
                                                                                                        <input class="form-control reportCrdHdrFtrVal <?php echo $isRqrdFld; ?>" id="reportCrdHdrFtrFld_<?php echo $columnNo; ?>" type="text" placeholder="" value="<?php echo $prsnDValPulld; ?>" />
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
                                                        $reportCardstatus = getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assess_sheet_status", $assessSbmtdSheetID);
                                                        if ($reportCardstatus === "Closed") {
                                                            $canEdt = false;
                                                        }
                                                        $cntr = 0;
                                                        $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                                                        $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                                                        $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                                                        $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 1000000;
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
                                                        $total = get_ReportCardLnsTtl($srchFor, $srchIn, $assessSbmtdSheetID);
                                                        //var_dump($_POST);
                                                        //echo "TT:" . $total;
                                                        if ($pageNo > ceil($total / $lmtSze)) {
                                                            $pageNo = 1;
                                                        } else if ($pageNo < 1) {
                                                            $pageNo = ceil($total / $lmtSze);
                                                        }
                                                        $extra4Where = " and a.person_id IN (select y1. from aca.aca_assmnt_col_vals y1 where )";
                                                        $curIdx = $pageNo - 1;
                                                        $result = get_ReportCardLns($srchFor, $srchIn, $curIdx, $lmtSze, $assessSbmtdSheetID);
                                                        $cntr = 0;
                                                        $vwtyp = 2;
                                                        ?>
                                                        <div class="basic_person_fs123" style="padding-top:2px !important;">
                                                            <div class="row">
                                                                <div class="col-md-12" style="display:none;">
                                                                    <div class="col-md-3" style="padding:0px 0px 0px 0px !important;float:left;">
                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getReportCrdHdr('', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                                                    </div>
                                                                    <div class="col-md-7" style="display:none;">
                                                                        <div class="input-group">
                                                                            <input class="form-control" id="reportCrdHdrSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                                                                echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                                                                ?>" onkeyup="enterKeyFuncReportCrdHdr(event, '', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>')">
                                                                            <input id="reportCrdHdrPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getReportCrdHdr('clear', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>')">
                                                                                <span class="glyphicon glyphicon-remove"></span>
                                                                            </label>
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getReportCrdHdr('', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');">
                                                                                <span class="glyphicon glyphicon-search"></span>
                                                                            </label>
                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="reportCrdHdrSrchIn">
                                                                                <?php
                                                                                $valslctdArry = array("", "");
                                                                                $srchInsArrys = array($courseLabel . " Name", $sbjctLabel . " Name");
                                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                    if ($srchIn == $srchInsArrys[$z]) {
                                                                                        $valslctdArry[$z] = "selected";
                                                                                    }
                                                                                ?>
                                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                            <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="reportCrdHdrDsplySze" style="min-width:70px !important;">
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
                                                                    <div class="col-md-2" style="display:none;">
                                                                        <nav aria-label="Page navigation">
                                                                            <ul class="pagination" style="margin: 0px !important;">
                                                                                <li>
                                                                                    <a class="rhopagination" href="javascript:getReportCrdHdr('previous', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');" aria-label="Previous">
                                                                                        <span aria-hidden="true">&laquo;</span>
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a class="rhopagination" href="javascript:getReportCrdHdr('next', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');" aria-label="Next">
                                                                                        <span aria-hidden="true">&raquo;</span>
                                                                                    </a>
                                                                                </li>
                                                                            </ul>
                                                                        </nav>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row" style="padding:1px 15px 1px 15px !important;display:none;">
                                                                <hr style="margin:1px 0px 3px 0px;">
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <table class="table table-striped table-bordered table-responsive" id="oneReportCardTransLinesTable" cellspacing="0" width="100%" style="width:100% !important;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="min-width:30px;">No.-[Rec. ID]</th>
                                                                                <th style="min-width:175px !important;"><?php echo $courseLabel; ?></th>
                                                                                <th style="min-width:175px !important;"><?php echo $sbjctLabel; ?></th>
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
                                                                                $colsValSums = array_fill(0, $ttlColS, "");
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
                                                                                    if ($colsTypes[$colscntr] == "Number") {
                                                                                        $tdStyle .= "text-align: right;";
                                                                                    } else {
                                                                                        $tdStyle .= "text-align: center;";
                                                                                    }
                                                                                ?>
                                                                                    <th style="<?php echo $tdStyle . $colWidth; ?>"><?php echo $colsNames[$colscntr]; ?></th>
                                                                                <?php
                                                                                    $colscntr++;
                                                                                }
                                                                                ?>
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
                                                                                <tr id="reportCrdColRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr) . "-[" . $row[0] . "]"; ?></td>
                                                                                    <td class="lovtd"><?php echo $row[5]; ?>
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="reportCrdColRow<?php echo $cntr; ?>_LineID" value="<?php echo $row[0]; ?>">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="reportCrdColRow<?php echo $cntr; ?>_PrsnID" value="<?php echo $row[4]; ?>">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="reportCrdColRow<?php echo $cntr; ?>_PrsnNm" value="<?php echo $row[6]; ?>">
                                                                                    </td>
                                                                                    <td class="lovtd"><?php echo $row[6]; ?></td>
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
                                                                                        if ($colsTypes[$colscntr] == "Number") {
                                                                                            $tdStyle .= "text-align: right;";
                                                                                            $colsValSums[$colscntr] = ((float) $colsValSums[$colscntr]) + ((float) $prsnDValPulld);
                                                                                        } else {
                                                                                            $tdStyle .= "text-align: center;";
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
                                                                                                        <input class="form-control reportCrdColRow<?php echo $cntr; ?> assesScoreM assesScore<?php echo $columnNo; ?> <?php echo $isRqrdFld; ?>" size="16" data-rhodata="" type="text" id="reportCrdColRow<?php echo $cntr . "_AsessScore-" . $columnNo; ?>" value="<?php echo $prsnDValPulld; ?>" style="width:100% !important;">
                                                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                                    </div>
                                                                                                <?php
                                                                                                } else if ($colsTypes[$colscntr] == "Number") {
                                                                                                ?>
                                                                                                    <input class="form-control reportCrdColRow<?php echo $cntr; ?> assesScoreM assesScoreNum assesScore<?php echo $columnNo; ?> <?php echo $isRqrdFld; ?>" id="reportCrdColRow<?php echo $cntr . "_AsessScore-" . $columnNo; ?>" min-rhodata="<?php echo $minValRhoData; ?>" max-rhodata="<?php echo $maxValRhoData; ?>" type="text" placeholder="" value="<?php echo number_format(((float) $prsnDValPulld), 2); ?>" style="width:100% !important;text-align: right;margin:0px !important;" onblur="vldtAssessColNumFld('reportCrdColRow<?php echo $cntr . "_AsessScore-" . $columnNo; ?>');" onkeypress="gnrlFldKeyPress(event, 'reportCrdColRow<?php echo $cntr . "_AsessScore-" . $columnNo; ?>', 'oneReportCardTransLinesTable', 'assesScore<?php echo $columnNo; ?>');" />
                                                                                                <?php
                                                                                                } else {
                                                                                                ?>
                                                                                                    <input class="form-control reportCrdColRow<?php echo $cntr; ?> assesScoreM assesScore<?php echo $columnNo; ?> <?php echo $isRqrdFld; ?>" id="reportCrdColRow<?php echo $cntr . "_AsessScore-" . $columnNo; ?>" data-rhodata="" type="text" placeholder="" value="<?php echo $prsnDValPulld; ?>" style="width:100% !important;text-align: right;margin:0px !important;" />
                                                                                            <?php
                                                                                                }
                                                                                            }
                                                                                            ?>
                                                                                        </td>
                                                                                    <?php
                                                                                        $colscntr++;
                                                                                    }
                                                                                    ?>
                                                                                </tr>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </tbody>
                                                                        <tfoot>
                                                                            <tr>
                                                                                <?php
                                                                                $ttlFooters = ($colscntr + 3);
                                                                                for ($z = 0; $z < $ttlFooters; $z++) {
                                                                                    $tdStyle = "";
                                                                                    $colscntr = $z - 3;
                                                                                    if ($colscntr >= 0) {
                                                                                        if ($colsIsDsplyd[$colscntr] == "0") {
                                                                                            $tdStyle = "display:none;";
                                                                                        }
                                                                                        if ($colsTypes[$colscntr] == "Number") {
                                                                                            $tdStyle .= "text-align: right;";
                                                                                            $colsValSums[$colscntr] = number_format(((float) $colsValSums[$colscntr]), 2);
                                                                                        } else {
                                                                                            $tdStyle .= "text-align: center;";
                                                                                            $colsValSums[$colscntr] = "&nbsp;";
                                                                                        }
                                                                                ?>
                                                                                        <th style="<?php echo $tdStyle; ?>"><?php echo $colsValSums[$colscntr]; ?></th>
                                                                                    <?php } else {
                                                                                    ?>
                                                                                        <th style="<?php echo $tdStyle; ?>">&nbsp;</th>
                                                                                <?php
                                                                                    }
                                                                                }
                                                                                ?>
                                                                            </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php ?>
                                                    </div>
                                                    <div id="asShtDetlsPMRecs" class="" style="border:none !important;padding:0px 0px 0px 0px !important;">
                                                        <?php
                                                        //Footer
                                                        $mkReadOnly = "";
                                                        $mkRmrkReadOnly = "";
                                                        $assessSbmtdSheetID = isset($_POST['assessSbmtdSheetID']) ? $_POST['assessSbmtdSheetID'] : -1;
                                                        $assessSbmtdSheetNm = isset($_POST['assessSbmtdSheetNm']) ? $_POST['assessSbmtdSheetNm'] : "";
                                                        $assessTypeID = (int) getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assessment_type_id", $assessSbmtdSheetID);
                                                        $reportCardstatus = getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assess_sheet_status", $assessSbmtdSheetID);
                                                        if ($reportCardstatus === "Closed") {
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
                                                                        $cntr1 += 1;
                                                                        $columnID = (int) $row1[0];
                                                                        $columnNo = (int) $row1[15];
                                                                        $prsnDValPulld = get_AssessShtColVal($assessSbmtdSheetID, $academicSttngID, $columnNo);
                                                                        //get_PrsExtrData($pkID, $row1[1]);
                                                                        $isRqrdFld = ($row1[12] === "1") ? "rqrdFld" : "";
                                                                        $tdStyle1 = "";
                                                                        $tdStyle2 = "";
                                                                        if ($row1[18] == "0") {
                                                                            $tdStyle1 = "display:none;";
                                                                        }
                                                                        if ($row1[4] == "Number") {
                                                                            $tdStyle2 .= "text-align: right;";
                                                                            $prsnDValPulld = number_format(((float) $prsnDValPulld), 2);
                                                                        } else {
                                                                            $tdStyle2 .= "text-align: left;";
                                                                        }
                                                                    ?>
                                                                        <div class="row" style="<?php echo $tdStyle1; ?>">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group form-group-sm">
                                                                                    <label class="control-label col-md-4"><?php echo $row1[2]; ?>:</label>
                                                                                    <div class="col-md-8">
                                                                                        <?php
                                                                                        if ($row1[13] == "1" || $canEdt === false) {
                                                                                            echo str_replace("{:p_col_value}", $prsnDValPulld, $row1[19]);
                                                                                        } else {
                                                                                            if ($row1[4] == "Date") {
                                                                                        ?>
                                                                                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                                                    <input class="form-control reportCrdHdrFtrVal <?php echo $isRqrdFld; ?>" size="16" type="text" id="reportCrdHdrFtrFld_<?php echo $columnNo; ?>" value="<?php echo $prsnDValPulld; ?>">
                                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                                </div>
                                                                                            <?php
                                                                                            } else if ($row1[4] == "Number") {
                                                                                            ?>
                                                                                                <input class="form-control reportCrdHdrFtrVal <?php echo $isRqrdFld; ?>" id="reportCrdHdrFtrFld_<?php echo $columnNo; ?>" type="text" placeholder="" value="<?php echo $prsnDValPulld; ?>" />
                                                                                            <?php
                                                                                            } else {
                                                                                            ?>
                                                                                                <input class="form-control reportCrdHdrFtrVal <?php echo $isRqrdFld; ?>" id="reportCrdHdrFtrFld_<?php echo $columnNo; ?>" type="text" placeholder="" value="<?php echo $prsnDValPulld; ?>" />
                                                                                        <?php
                                                                                            }
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php
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
                $reportCardstatus = getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assess_sheet_status", $assessSbmtdSheetID);
                if ($reportCardstatus === "Closed") {
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
                $total = get_ReportCardLnsTtl($srchFor, $srchIn, $assessSbmtdSheetID);
                //var_dump($_POST);
                //echo "TT:" . $total;
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $extra4Where = " and a.person_id IN (select y1. from aca.aca_assmnt_col_vals y1 where )";
                $curIdx = $pageNo - 1;
                $result = get_ReportCardLns($srchFor, $srchIn, $curIdx, $lmtSze, $assessSbmtdSheetID);
                $cntr = 0;
                $vwtyp = 2;
            ?>
                <div class="basic_person_fs123" style="padding-top:2px !important;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-3" style="padding:0px 0px 0px 0px !important;float:left;">
                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="getReportCrdHdr('', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"></button>
                                <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="exprtScoreCards();" data-toggle="tooltip" title="Export Assessment Lines">
                                    <img src="cmn_images/document_export.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                                <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="" data-toggle="tooltip" title="Import Assessment Lines">
                                    <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Import
                                </button>
                            </div>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <input class="form-control" id="reportCrdHdrSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                        echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                        ?>" onkeyup="enterKeyFuncReportCrdHdr(event, '', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>')">
                                    <input id="reportCrdHdrPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getReportCrdHdr('clear', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getReportCrdHdr('', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="reportCrdHdrSrchIn">
                                        <?php
                                        $valslctdArry = array("", "");
                                        $srchInsArrys = array($courseLabel . " Name", $sbjctLabel . " Name");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                        ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="reportCrdHdrDsplySze" style="min-width:70px !important;">
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
                                            <a class="rhopagination" href="javascript:getReportCrdHdr('previous', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getReportCrdHdr('next', '#asShtDetlsTrans', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=<?php echo $mdlACAorPMS;?>');" aria-label="Next">
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
                            <table class="table table-striped table-bordered table-responsive" id="oneReportCardTransLinesTable" cellspacing="0" width="100%" style="width:100% !important;">
                                <thead>
                                    <tr>
                                        <th style="min-width:30px;">No.-[Rec. ID]</th>
                                        <th style="min-width:175px !important;"><?php echo $courseLabel; ?></th>
                                        <th style="min-width:175px !important;"><?php echo $sbjctLabel; ?></th>
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
                                        <tr id="reportCrdColRow_<?php echo $cntr; ?>" class="hand_cursor">
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr) . "-[" . $row[0] . "]"; ?></td>
                                            <td class="lovtd"><?php echo $row[5]; ?>
                                                <input type="hidden" class="form-control" aria-label="..." id="reportCrdColRow<?php echo $cntr; ?>_LineID" value="<?php echo $row[0]; ?>">
                                                <input type="hidden" class="form-control" aria-label="..." id="reportCrdColRow<?php echo $cntr; ?>_PrsnID" value="<?php echo $row[4]; ?>">
                                                <input type="hidden" class="form-control" aria-label="..." id="reportCrdColRow<?php echo $cntr; ?>_PrsnNm" value="<?php echo $row[6]; ?>">
                                            </td>
                                            <td class="lovtd"><?php echo $row[6]; ?></td>
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
                                                                <input class="form-control reportCrdColRow<?php echo $cntr; ?> assesScoreM assesScore<?php echo $columnNo; ?> <?php echo $isRqrdFld; ?>" size="16" data-rhodata="" type="text" id="reportCrdColRow<?php echo $cntr . "_AsessScore-" . $columnNo; ?>" value="<?php echo $prsnDValPulld; ?>" style="width:100% !important;">
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                            </div>
                                                        <?php
                                                        } else if ($colsTypes[$colscntr] == "Number") {
                                                        ?>
                                                            <input class="form-control reportCrdColRow<?php echo $cntr; ?> assesScoreM assesScoreNum assesScore<?php echo $columnNo; ?> <?php echo $isRqrdFld; ?>" id="reportCrdColRow<?php echo $cntr . "_AsessScore-" . $columnNo; ?>" min-rhodata="<?php echo $minValRhoData; ?>" max-rhodata="<?php echo $maxValRhoData; ?>" type="text" placeholder="" value="<?php echo number_format(((float) $prsnDValPulld), 2); ?>" style="width:100% !important;text-align: right;margin:0px !important;" onblur="vldtAssessColNumFld('reportCrdColRow<?php echo $cntr . "_AsessScore-" . $columnNo; ?>');" onkeypress="gnrlFldKeyPress(event, 'reportCrdColRow<?php echo $cntr . "_AsessScore-" . $columnNo; ?>', 'oneReportCardTransLinesTable', 'assesScore<?php echo $columnNo; ?>');" />
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <input class="form-control reportCrdColRow<?php echo $cntr; ?> assesScoreM assesScore<?php echo $columnNo; ?> <?php echo $isRqrdFld; ?>" id="reportCrdColRow<?php echo $cntr . "_AsessScore-" . $columnNo; ?>" data-rhodata="" type="text" placeholder="" value="<?php echo $prsnDValPulld; ?>" style="width:100% !important;text-align: right;margin:0px !important;" />
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
                $reportCardstatus = getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assess_sheet_status", $assessSbmtdSheetID);
                if ($reportCardstatus === "Closed") {
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
                                                            <input class="form-control reportCrdHdrFtrVal <?php echo $isRqrdFld; ?>" size="16" type="text" id="reportCrdHdrFtrFld_<?php echo $columnNo; ?>" value="<?php echo $prsnDValPulld; ?>">
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>
                                                    <?php
                                                    } else if ($row1[4] == "Number") {
                                                    ?>
                                                        <input class="form-control reportCrdHdrFtrVal <?php echo $isRqrdFld; ?>" id="reportCrdHdrFtrFld_<?php echo $columnNo; ?>" type="text" placeholder="" value="<?php echo $prsnDValPulld; ?>" />
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <input class="form-control reportCrdHdrFtrVal <?php echo $isRqrdFld; ?>" id="reportCrdHdrFtrFld_<?php echo $columnNo; ?>" type="text" placeholder="" value="<?php echo $prsnDValPulld; ?>" />
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
            } else if ($vwtyp == 701) {
                //Print Terminal Report   
                $vPsblValID1 = getEnbldPssblValID("Html Report Card Print File Name", getLovID("All Other Performance Setups"));
                $vPsblVal1 = getPssblValDesc($vPsblValID1);
                if ($vPsblVal1 == "") {
                    $vPsblVal1 = 'htm_rpts/terminal_rpt.php';
                }
                require $vPsblVal1;
            }
        }
    }
}
?>