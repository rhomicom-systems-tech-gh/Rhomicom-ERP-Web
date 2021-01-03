<?php
$z = 0;
$canRunRpts = test_prmssns($dfltPrvldgs[8], $mdlNm);
$canDelRptRuns = test_prmssns($dfltPrvldgs[9], $mdlNm);
$canVwOthrsRuns = test_prmssns($dfltPrvldgs[10], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                echo deleteReportRun($pKeyID);
            } else if ($actyp == 2) {
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $rptNm = isset($_POST['rptNm']) ? cleanInputData($_POST['rptNm']) : "";
                echo deleteReport($pKeyID, $rptNm);
            } else if ($actyp == 3) {
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $paramNm = isset($_POST['paramNm']) ? cleanInputData($_POST['paramNm']) : "";
                echo deleteRptParam($pKeyID, $paramNm);
            } else if ($actyp == 4) {
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $roleNm = isset($_POST['roleNm']) ? cleanInputData($_POST['roleNm']) : "";
                echo deleteRptRole($pKeyID, $roleNm);
            } else if ($actyp == 5) {
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $prgmNm = isset($_POST['prgmNm']) ? cleanInputData($_POST['prgmNm']) : "";
                echo deleteRptPrgm($pKeyID, $prgmNm);
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Import Reports
                $dataToSend = trim(cleanInputData($_POST['dataToSend']), "|~");
                session_write_close();
                $affctd = 0;
                if ($dataToSend != "") {
                    $variousRows = explode("|", $dataToSend);
                    $total = count($variousRows);
                    for ($z = 0; $z < $total; $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 21) {
                            $number = cleanInputData1($crntRow[0]);
                            $processName = trim((cleanInputData1($crntRow[1])));
                            $processDesc = trim(cleanInputData1($crntRow[2]));
                            $processQuery = cleanInputData1($crntRow[3]);
                            $ownerModule = cleanInputData1($crntRow[4]);
                            $processType = cleanInputData1($crntRow[5]);
                            $processRnnr = trim(cleanInputData1($crntRow[6]));
                            $jrxmlFile = trim(cleanInputData1($crntRow[7]));
                            $colsToGrp = cleanInputData1($crntRow[8]);
                            $colsToCnt = cleanInputData1($crntRow[9]);
                            $colsToFrmt = cleanInputData1($crntRow[10]);
                            $colsToSum = cleanInputData1($crntRow[11]);
                            $colsToAvrg = cleanInputData1($crntRow[12]);
                            $outptType = cleanInputData1($crntRow[13]);
                            $orntation = cleanInputData1($crntRow[14]);
                            $layout = cleanInputData1($crntRow[15]);
                            $dlmtr = cleanInputData1($crntRow[16]);
                            $dtailImgCols = cleanInputData1($crntRow[17]);
                            $preSQL = cleanInputData1($crntRow[18]);
                            $postSQL = cleanInputData1($crntRow[19]);
                            $isEnbld1 = cleanInputData1($crntRow[20]);
                            $isEnbld = cleanInputData1($crntRow[20]) == "YES" ? TRUE : FALSE;
                            $oldPrcsID = getRptID($processName);
                            if ($z == 0) {
                                if (strtoupper($number) == "NO." && strtoupper($processName) == "PROCESS NAME**" && strtoupper($isEnbld1) == "IS ENABLED?") {
                                    continue;
                                } else {
                                    $arr_content['percent'] = 100;
                                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> Selected File is Invalid!";
                                    //.strtoupper($number) ."|". strtoupper($processName) ."|". strtoupper($isEnbld1 == "IS ENABLED?");
                                    $arr_content['msgcount'] = $total;
                                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_rptsimprt_progress.rho",
                                            json_encode($arr_content));
                                    break;
                                }
                            }
                            if ($processName == "") {
                                continue;
                            }
                            if ($oldPrcsID <= 0) {
                                $affctd += createRpt($processName, $processDesc, $ownerModule, $processType, $processQuery, $isEnbld, $colsToGrp,
                                        $colsToCnt, $colsToSum, $colsToAvrg, $colsToFrmt, $outptType, $orntation, $processRnnr, $layout, $dlmtr,
                                        $dtailImgCols, $jrxmlFile, $preSQL, $postSQL);
                            } else {
                                $affctd += updateRpt($oldPrcsID, $processName, $processDesc, $ownerModule, $processType, $processQuery, $isEnbld,
                                        $colsToGrp, $colsToCnt, $colsToSum, $colsToAvrg, $colsToFrmt, $outptType, $orntation, $processRnnr, $layout,
                                        $dlmtr, $dtailImgCols, $jrxmlFile, $preSQL, $postSQL);
                            }
                        }
                        $percent = round((($z + 1) / $total) * 100, 2);
                        $arr_content['percent'] = $percent;
                        if ($percent >= 100) {
                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> 100% Completed!..." . $affctd . " out of " . $total . " Process(es) imported.";
                            $arr_content['msgcount'] = $total;
                        } else {
                            $arr_content['message'] = "<i class=\"fa fa-spin fa-spinner\"></i> Importing Reports/Processes...Please Wait..." . ($z + 1) . " out of " . $total . " Process(es) imported.";
                        }
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_rptsimprt_progress.rho", json_encode($arr_content));
                    }
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['msgcount'] = "";
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_rptsimprt_progress.rho", json_encode($arr_content));
                }
            } else if ($actyp == 2) {
                //Checked Importing Process Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_rptsimprt_progress.rho";
                if (file_exists($file)) {
                    $text = file_get_contents($file);
                    echo $text;

                    $obj = json_decode($text);
                    if ($obj->percent >= 100) {
                        //session_write_close();
                        //sleep(10);
                        //$rs = file_exists($file) ? unlink($file) : TRUE;
                    }
                } else {
                    echo json_encode(array("percent" => null, "message" => null));
                }
            } else if ($actyp == 3) {
                //Export Reports
                $inptNum = isset($_POST['inptNum']) ? (int) cleanInputData($_POST['inptNum']) : 0;
                session_write_close();
                $affctd = 0;
                $errMsg = "Invalid Option!";
                if ($inptNum >= 0) {
                    $hdngs = array(
                        "No.", "Process Name**", "Process Description", "Process Query**",
                        "Owner Module**", "Process Type**", "Process Runner**", "Jrxml File Loc.",
                        "Cols Nos To Group or Width & Height (Px) for Charts", "Cols Nos To Count or Use in Charts",
                        "Cols Nos To Format Numerically", "Cols Nos To Sum", "Cols Nos To Average",
                        "Output Type**", "Orientation", "Layout", "Delimiter",
                        "Detail Report Images Col Nos", "Pre-Process Query", "Post-Process Query", "Is Enabled?");
                    $limit_size = 0;
                    if ($inptNum > 2) {
                        $limit_size = $inptNum;
                    } else if ($inptNum == 2) {
                        $limit_size = 1000000;
                    }
                    $rndm = getRandomNum(10001, 9999999);
                    $dteNm = date('dMY_His');
                    $nwFileNm = $fldrPrfx . "dwnlds/tmp/RptsExprt_" . $dteNm . "_" . $rndm . ".csv";
                    $dwnldUrl = $app_url . "dwnlds/tmp/RptsExprt_" . $dteNm . "_" . $rndm . ".csv";
                    $opndfile = fopen($nwFileNm, "w");
                    fputcsv($opndfile, $hdngs);
                    if ($limit_size <= 0) {
                        $arr_content['percent'] = 100;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!... Reports/Processes Template Exported.</span>";
                        $arr_content['msgcount'] = 0;
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_rptsexprt_progress.rho", json_encode($arr_content));

                        fclose($opndfile);
                        exit();
                    }
                    $z = 0;
                    $crntRw = "";
                    $result = get_RptsToExprt("%", "Report Name", 0, $limit_size);
                    $total = loc_db_num_rows($result);
                    $fieldCntr = loc_db_num_fields($result);
                    while ($row = loc_db_fetch_array($result)) {
                        $crntRw = array("" . ($z + 1), $row[1], $row[2], $row[3], $row[4], $row[5], $row[14], $row[18], $row[7], $row[8], $row[11], $row[9],
                            $row[10], $row[12], $row[13], $row[15], $row[17], $row[16], $row[19], $row[20], $row[6]);
                        fputcsv($opndfile, $crntRw);
                        //file_put_contents($nwFileNm, $crntRw, FILE_APPEND | LOCK_EX);
                        $percent = round((($z + 1) / $total) * 100, 2);
                        $arr_content['percent'] = $percent;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        if ($percent >= 100) {
                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!..." . ($z +
                                    1) . " out of " . $total . " Reports/Process(es) exported.</span>";
                            $arr_content['msgcount'] = $total;
                        } else {
                            $arr_content['message'] = "<span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"><br/>Exporting Reports/Processes...Please Wait..." . ($z +
                                    1) . " out of " . $total . " Reports/Process(es) exported.</span>";
                        }
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_rptsexprt_progress.rho", json_encode($arr_content));
                        $z++;
                    }
                    if ($z == 0) {
                        $arr_content['percent'] = 100;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!... Template Exported.</span>";
                        $arr_content['msgcount'] = 0;
                        file_put_contents(
                            $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_rptsexprt_progress.rho",
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
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_rptsexprt_progress.rho", json_encode($arr_content));
                }
            } else if ($actyp == 4) {
                //Checked Exporting Process Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_rptsexprt_progress.rho";
                if (file_exists($file)) {
                    $text = file_get_contents($file);
                    echo $text;
                    $obj = json_decode($text);
                    if ($obj->percent >= 100) {
                        //session_write_close();
                        //sleep(10);
                        //$rs = file_exists($file) ? unlink($file) : TRUE;
                    }
                } else {
                    echo json_encode(array("percent" => 0, "message" => '<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Not Started</span>'));
                }
            } else if ($actyp == 5) {
                //Save Reports
                header("content-type:application/json");
                $orgid = $_SESSION['ORG_ID'];
                $rptsRptID = isset($_POST['rptsRptID']) ? cleanInputData($_POST['rptsRptID']) : -1;
                $rptsRptNm = isset($_POST['rptsRptNm']) ? cleanInputData($_POST['rptsRptNm']) : "";
                $rptsRptDesc = isset($_POST['rptsRptDesc']) ? cleanInputData($_POST['rptsRptDesc']) : "";
                $rptsOwnrMdl = isset($_POST['rptsOwnrMdl']) ? cleanInputData($_POST['rptsOwnrMdl']) : "";
                $rptsProcessTyp = isset($_POST['rptsProcessTyp']) ? cleanInputData($_POST['rptsProcessTyp']) : "";
                $rptsPrcsRnnr = isset($_POST['rptsPrcsRnnr']) ? cleanInputData($_POST['rptsPrcsRnnr']) : '';
                $rptsColsToGrp = isset($_POST['rptsColsToGrp']) ? cleanInputData($_POST['rptsColsToGrp']) : '';
                $rptsColsToCnt = isset($_POST['rptsColsToCnt']) ? cleanInputData($_POST['rptsColsToCnt']) : '';
                $rptsColsToFrmt = isset($_POST['rptsColsToFrmt']) ? cleanInputData($_POST['rptsColsToFrmt']) : '';
                $rptsColsToSum = isset($_POST['rptsColsToSum']) ? cleanInputData($_POST['rptsColsToSum']) : '';
                $rptsColsToAvrg = isset($_POST['rptsColsToAvrg']) ? cleanInputData($_POST['rptsColsToAvrg']) : '';
                $rptsOutputType = isset($_POST['rptsOutputType']) ? cleanInputData($_POST['rptsOutputType']) : '';
                $rptsOrntn = isset($_POST['rptsOrntn']) ? cleanInputData($_POST['rptsOrntn']) : '';
                $rptsLayout = isset($_POST['rptsLayout']) ? cleanInputData($_POST['rptsLayout']) : '';
                $rptsDelimiter = isset($_POST['rptsDelimiter']) ? cleanInputData($_POST['rptsDelimiter']) : '';
                $rptsDetImgCols = isset($_POST['rptsDetImgCols']) ? cleanInputData($_POST['rptsDetImgCols']) : '';
                $rptsQuerySQL = isset($_POST['rptsQuerySQL']) ? cleanInputData($_POST['rptsQuerySQL']) : '';
                $rptsPreSQL = isset($_POST['rptsPreSQL']) ? cleanInputData($_POST['rptsPreSQL']) : '';
                $rptsPostSQL = isset($_POST['rptsPostSQL']) ? cleanInputData($_POST['rptsPostSQL']) : '';
                $rptsIsEnabled = isset($_POST['rptsIsEnabled']) ? cleanInputData($_POST['rptsIsEnabled']) : '';
                $slctdRptParams = isset($_POST['slctdRptParams']) ? cleanInputData($_POST['slctdRptParams']) : '';
                $slctdRptSbPrgms = isset($_POST['slctdRptSbPrgms']) ? cleanInputData($_POST['slctdRptSbPrgms']) : '';
                $slctdRptAllwdRoles = isset($_POST['slctdRptAllwdRoles']) ? cleanInputData($_POST['slctdRptAllwdRoles']) : '';
                $isenbld = false;
                if ($rptsIsEnabled == "YES") {
                    $isenbld = true;
                }
                $rptsJrxmlFileSrc = isset($_POST['rptsJrxmlFileSrc']) ? cleanInputData($_POST['rptsJrxmlFileSrc']) : '';
                $oldRptID = getRptID($rptsRptNm);
                if ($rptsRptNm != "" && ($oldRptID <= 0 || $oldRptID == $rptsRptID)) {
                    if ($rptsRptID <= 0) {
                        createRpt($rptsRptNm, $rptsRptDesc, $rptsOwnrMdl, $rptsProcessTyp, $rptsQuerySQL, $isenbld, $rptsColsToGrp, $rptsColsToCnt,
                                $rptsColsToSum, $rptsColsToAvrg, $rptsColsToFrmt, $rptsOutputType, $rptsOrntn, $rptsPrcsRnnr, $rptsLayout,
                                $rptsDelimiter, $rptsDetImgCols, $rptsJrxmlFileSrc, $rptsPreSQL, $rptsPostSQL);
                        $rptsRptID = getRptID($rptsRptNm);
                    } else {
                        updateRpt($rptsRptID, $rptsRptNm, $rptsRptDesc, $rptsOwnrMdl, $rptsProcessTyp, $rptsQuerySQL, $isenbld, $rptsColsToGrp,
                                $rptsColsToCnt, $rptsColsToSum, $rptsColsToAvrg, $rptsColsToFrmt, $rptsOutputType, $rptsOrntn, $rptsPrcsRnnr,
                                $rptsLayout, $rptsDelimiter, $rptsDetImgCols, $rptsJrxmlFileSrc, $rptsPreSQL, $rptsPostSQL);
                    }
                    $affctdParams = 0;
                    $affctdRoles = 0;
                    $affctdPrgms = 0;
                    $nwImgLoc = "";
                    if ($rptsRptID > 0) {
                        if (isset($_FILES["rptsJrxmlFile"])) {
                            uploadDaJrxml($rptsRptID, $nwImgLoc);
                        }
                        if (trim($slctdRptParams, "|~") != "") {
                            //Save Report Parameters
                            $variousRows = explode("|", trim($slctdRptParams, "|"));
                            for ($z = 0; $z < count($variousRows); $z++) {
                                $crntRow = explode("~", $variousRows[$z]);
                                if (count($crntRow) == 10) {
                                    $inptParamID = (int) (cleanInputData1($crntRow[0]));
                                    $paramNm = cleanInputData1($crntRow[1]);
                                    $sqlRep = cleanInputData1($crntRow[2]);
                                    $dfltVal = cleanInputData1($crntRow[3]);
                                    $inlovName = cleanInputData1($crntRow[4]);
                                    $inlovID = (int) (cleanInputData1($crntRow[5]));
                                    $inDataType = cleanInputData1($crntRow[6]);
                                    $inDateFrmt = cleanInputData1($crntRow[7]);
                                    $isPrmRqrd = cleanInputData1($crntRow[8]) == "YES" ? TRUE : FALSE;
                                    $isPrmDsplyd = cleanInputData1($crntRow[9]) == "YES" ? TRUE : FALSE;
                                    $oldParamID = getParamID($paramNm, $rptsRptID);

                                    if ($paramNm != "" && $sqlRep != "" && ($oldParamID <= 0 || $oldParamID == $inptParamID)) {
                                        if ($inptParamID <= 0) {
                                            $affctdParams += createParam($rptsRptID, $paramNm, $sqlRep, $dfltVal, $isPrmRqrd, $inlovID, $inDataType,
                                                    $inDateFrmt, $inlovName, $isPrmDsplyd);
                                        } else {
                                            $affctdParams += updateParam($inptParamID, $paramNm, $sqlRep, $dfltVal, $isPrmRqrd, $inlovID, $inDataType,
                                                    $inDateFrmt, $inlovName, $isPrmDsplyd);
                                        }
                                    }
                                }
                            }
                        }

                        if (trim($slctdRptSbPrgms, "|~") != "") {
                            //Save Report Parameters
                            $variousRows = explode("|", trim($slctdRptSbPrgms, "|"));
                            for ($z = 0; $z < count($variousRows); $z++) {
                                $crntRow = explode("~", $variousRows[$z]);
                                if (count($crntRow) == 2) {
                                    $inptPrgmID = (int) (cleanInputData1($crntRow[0]));
                                    $prgmNm = cleanInputData1($crntRow[1]);
                                    $oldPrgmID = doesRptHvPrgm($rptsRptID, $inptPrgmID);
                                    if ($oldPrgmID <= 0 && $inptPrgmID > 0) {
                                        $affctdPrgms += createRptPrgmUnt($rptsRptID, $inptPrgmID);
                                    }
                                }
                            }
                        }

                        if (trim($slctdRptAllwdRoles, "|~") != "") {
                            //Save Report Parameters
                            $variousRows = explode("|", trim($slctdRptAllwdRoles, "|"));
                            for ($z = 0; $z < count($variousRows); $z++) {
                                $crntRow = explode("~", $variousRows[$z]);
                                if (count($crntRow) == 2) {
                                    $inptRoleID = (int) (cleanInputData1($crntRow[0]));
                                    $roleNm = cleanInputData1($crntRow[1]);
                                    $oldRoleID = doesRptHvRole($rptsRptID, $inptRoleID);
                                    if ($oldRoleID <= 0 && $inptRoleID > 0) {
                                        $affctdRoles += createRptRole($rptsRptID, $inptRoleID);
                                    }
                                }
                            }
                        }
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['rptsRptID'] = $rptsRptID;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Report/Process Successfully Saved!<br/>"
                            . $affctdParams . " Parameters Saved!<br/>"
                            . $affctdRoles . " Roles Added!<br/>"
                            . $affctdPrgms . " Sub-Programs Added!";
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['rptsRptID'] = -1;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Either the New Report/Process Exists <br/>or Data Supplied is Incomplete!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 6) {
                //Import Report Parameters
                $dataToSend = trim(cleanInputData($_POST['dataToSend']), "|~");
                session_write_close();
                $affctd = 0;
                if ($dataToSend != "") {
                    $variousRows = explode("|", $dataToSend);
                    $total = count($variousRows);
                    for ($z = 0; $z < $total; $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 10) {
                            $number = cleanInputData1($crntRow[0]);
                            $processName = trim((cleanInputData1($crntRow[1])));
                            $paramName = trim(cleanInputData1($crntRow[2]));
                            $sqlRep = cleanInputData1($crntRow[3]);
                            $dfltVal = cleanInputData1($crntRow[4]);
                            if ($dfltVal != "") {
                                if (substr($dfltVal, 0, 1) == "'" && substr_count($dfltVal, "'") == 1) {
                                    $dfltVal = substr($dfltVal, 1);
                                }
                            }
                            $prmLovNm = cleanInputData1($crntRow[5]);
                            $prmLovID = getLovID($prmLovNm);
                            $isValRqrd1 = trim(cleanInputData1($crntRow[6]));
                            $isValRqrd = cleanInputData1($crntRow[6]) == "YES" ? TRUE : FALSE;
                            $prmDataTyp = trim(cleanInputData1($crntRow[7]));
                            $prmDateFrmt = cleanInputData1($crntRow[8]);
                            $isValDsplyd1 = trim(cleanInputData1($crntRow[9]));
                            $isValDsplyd = cleanInputData1($crntRow[9]) == "YES" ? TRUE : FALSE;
                            $inRptID = getRptID($processName);
                            $oldParamID = getParamID($paramName, $inRptID);
                            if ($z == 0) {
                                if (strtoupper($number) == "NO." && strtoupper($processName) == "PROCESS NAME**" && strtoupper($paramName) == "PARAMETER NAME/PROMPT**" &&
                                        strtoupper($prmDateFrmt) == "DATE FORMAT" && strtoupper($isValDsplyd1) == "IS DISPLAYED?") {
                                    continue;
                                } else {
                                    $arr_content['percent'] = 100;
                                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> Selected File is Invalid!";
                                    $arr_content['msgcount'] = $total;
                                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prmsimprt_progress.rho",
                                            json_encode($arr_content));
                                    break;
                                }
                            }
                            if ($paramName == "" || $sqlRep == "" || $inRptID <= 0) {
                                continue;
                            }
                            if ($oldParamID <= 0) {
                                $affctd += createParam($inRptID, $paramName, $sqlRep, $dfltVal, $isValRqrd, $prmLovID, $prmDataTyp, $prmDateFrmt,
                                        $prmLovNm, $isValDsplyd);
                            } else {
                                $affctd += updateParam($oldParamID, $paramName, $sqlRep, $dfltVal, $isValRqrd, $prmLovID, $prmDataTyp, $prmDateFrmt,
                                        $prmLovNm, $isValDsplyd);
                            }
                        }
                        $percent = round((($z + 1) / $total) * 100, 2);
                        $arr_content['percent'] = $percent;
                        if ($percent >= 100) {
                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> 100% Completed!..." . $affctd . " out of " . $total . " Parameter(s) imported.";
                            $arr_content['msgcount'] = $total;
                        } else {
                            $arr_content['message'] = "<i class=\"fa fa-spin fa-spinner\"></i> Importing Parameters...Please Wait..." . ($z + 1) . " out of " . $total . " Parameter(s) imported.";
                        }
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prmsimprt_progress.rho", json_encode($arr_content));
                    }
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['msgcount'] = "";
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prmsimprt_progress.rho", json_encode($arr_content));
                }
            } else if ($actyp == 7) {
                //Checked Importing Parameters Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prmsimprt_progress.rho";
                if (file_exists($file)) {
                    $text = file_get_contents($file);
                    echo $text;
                    $obj = json_decode($text);
                    if ($obj->percent >= 100) {
                        //session_write_close();
                        //sleep(10);
                        //$rs = file_exists($file) ? unlink($file) : TRUE;
                    }
                } else {
                    echo json_encode(array("percent" => null, "message" => null));
                }
            } else if ($actyp == 8) {
                //Export Parameters
                $inptNum = isset($_POST['inptNum']) ? (int) cleanInputData($_POST['inptNum']) : 0;
                session_write_close();
                $affctd = 0;
                $errMsg = "Invalid Option!";
                if ($inptNum >= 0) {
                    $hdngs = array("No.", "Process Name**", "Parameter Name/Prompt**", "SQL Representation**",
                        "Default Value", "LOV Name", "Is Required?", "Data Type", "Date Format", "Is Displayed?");
                    $limit_size = 0;
                    if ($inptNum > 2) {
                        $limit_size = $inptNum;
                    } else if ($inptNum == 2) {
                        $limit_size = 1000000;
                    }
                    $rndm = getRandomNum(10001, 9999999);
                    $dteNm = date('dMY_His');
                    $nwFileNm = $fldrPrfx . "dwnlds/tmp/PrmsExprt_" . $dteNm . "_" . $rndm . ".csv";
                    $dwnldUrl = $app_url . "dwnlds/tmp/PrmsExprt_" . $dteNm . "_" . $rndm . ".csv";
                    $opndfile = fopen($nwFileNm, "w");
                    fputcsv($opndfile, $hdngs);
                    if ($limit_size <= 0) {
                        $arr_content['percent'] = 100;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!... Parameters Template Exported.</span>";
                        $arr_content['msgcount'] = 0;
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prmsexprt_progress.rho", json_encode($arr_content));

                        fclose($opndfile);
                        exit();
                    }
                    $z = 0;
                    $crntRw = "";
                    $result = get_AllParamsExprt(0, $limit_size);
                    $total = loc_db_num_rows($result);
                    $fieldCntr = loc_db_num_fields($result);
                    while ($row = loc_db_fetch_array($result)) {
                        $crntRw = array("" . ($z + 1), $row[8], $row[1], $row[2], ((substr_count($row[3], "'") <= 0) ? "'" . $row[3] : $row[3]), $row[9],
                            $row[4], $row[6], $row[7], $row[10]);
                        fputcsv($opndfile, $crntRw);
                        $percent = round((($z + 1) / $total) * 100, 2);
                        $arr_content['percent'] = $percent;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        if ($percent >= 100) {
                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!..." . ($z +
                                    1) . " out of " . $total . " Parameter(s) exported.</span>";
                            $arr_content['msgcount'] = $total;
                        } else {
                            $arr_content['message'] = "<span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"><br/>Exporting Parameter(s)...Please Wait..." . ($z +
                                    1) . " out of " . $total . " Parameter(s) exported.</span>";
                        }
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prmsexprt_progress.rho", json_encode($arr_content));
                        $z++;
                    }
                    if ($z == 0) {
                        $arr_content['percent'] = 100;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!... Template Exported.</span>";
                        $arr_content['msgcount'] = 0;
                        file_put_contents(
                            $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prmsexprt_progress.rho",
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
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prmsexprt_progress.rho", json_encode($arr_content));
                }
            } else if ($actyp == 9) {
                //Checked Exporting Process Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prmsexprt_progress.rho";
                if (file_exists($file)) {
                    $text = file_get_contents($file);
                    echo $text;

                    $obj = json_decode($text);
                    if ($obj->percent >= 100) {
                        //session_write_close();
                        //sleep(10);
                        //$rs = file_exists($file) ? unlink($file) : TRUE;
                    }
                } else {
                    echo json_encode(array("percent" => 0, "message" => '<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Not Started</span>'));
                }
            } else if ($actyp == 51) {
                //Run a Report
                $rptID = isset($_POST['rptID']) ? (int) cleanInputData($_POST['rptID']) : -1;
                $alrtID = isset($_POST['alrtID']) ? (int) cleanInputData($_POST['alrtID']) : -1;
                $paramsStr = isset($_POST['paramsStr']) ? cleanInputData($_POST['paramsStr']) : '';
                if ($paramsStr != "") {
                    $paramsStr = urldecode($paramsStr);
                }
                $nwRndm = isset($_POST['nwRndm']) ? cleanInputData($_POST['nwRndm']) : 0;
                $rptRunID = 0;
                $dwnldUrl = "None";
                $percent = 0;
                if ($canRunRpts === true) {
                    
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...Permission Denied!</span>";
                    $arr_content['rptRunID'] = "-1";
                    $arr_content['dwnld_url'] = $dwnldUrl;
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/" . $lgn_num . "_" . $nwRndm . "_myrptsrun_progress.rho",
                            json_encode($arr_content));
                    exit();
                }
                $affctd = 0;
                $errMsg = "Invalid Option!";
                if ($rptID >= 0 || $alrtID > 0) {
                    $rptRunID = generateReportRun($rptID, $paramsStr, $alrtID);
                    if ($rptRunID > 0) {
                        session_write_close();
                        do {
                            $result1 = get_OneRptRun($rptRunID);
                            while ($row1 = loc_db_fetch_array($result1)) {
                                $runStatus = $row1[4];
                                $runActvTme = $row1[14];
                                $alrtID = $row1[15];
                                $style2 = "";
                                $runStatusMsg = "<div";
                                if ($row1[4] == "Not Started!") {
                                    $style2 = "style=\"background-color: #E8D68C;padding:2px !important;margin-top:5px !important;\"";
                                } else if ($row1[4] == "Preparing to Start...") {
                                    $style2 = "style=\"background-color: yellow;padding:2px !important;margin-top:5px !important;\"";
                                } else if ($row1[4] == "Running SQL...") {
                                    $style2 = "style=\"background-color: lightgreen;padding:2px !important;margin-top:5px !important;\"";
                                } else if ($row1[4] == "Formatting Output...") {
                                    $style2 = "style=\"background-color: lime;padding:2px !important;margin-top:5px !important;\"";
                                } else if ($row1[4] == "Storing Output..." || $row1[4] == "Sending Output...") {
                                    $style2 = "style=\"background-color: cyan;padding:2px !important;margin-top:5px !important;\"";
                                } else if ($row1[4] == "Completed!") {
                                    $style2 = "style=\"background-color: gainsboro;padding:2px !important;margin-top:5px !important;\"";
                                } else if (strpos($row1[4], "Error") !== FALSE) {
                                    $style2 = "style=\"background-color: red;padding:2px !important;margin-top:5px !important;\"";
                                }
                                $runStatusMsg .= " " . $style2;
                                $runStatusMsg .= ">" . $row1[4] . " ";

                                $runStatusMsg .= "";
                                $tst = isDteTmeWthnIntrvl(cnvrtDMYTmToYMDTm($row1[10]), '40 second');
                                $style3 = "";
                                if ($tst == true) {
                                    $style3 = " style=\"background-color: limegreen;color: white; font-size:12px;padding:2px;margin-top:5px !important;\"";
                                }
                                //$runStatusMsg .=" ". $style2;
                                $runStatusMsg .= "" . $row1[10] . "</div>";


                                $outptUsd = $row1[8];
                                $rpt_src_encrpt = "";
                                if ($outptUsd == "HTML" || $outptUsd == "COLUMN CHART" || $outptUsd == "SIMPLE COLUMN CHART" || $outptUsd == "BAR CHART" ||
                                        $outptUsd == "PIE CHART" || $outptUsd == "LINE CHART") {
                                    $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/amcharts_2100/samples/$row1[0].html";
                                    $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                    if (file_exists($rpt_src)) {
                                        //file exists!
                                    } else {
                                        //file does not exist.
                                        $rpt_src_encrpt = "None";
                                    }
                                } else if ($outptUsd == "STANDARD") {
                                    $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row1[0].txt";
                                    $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                    if (file_exists($rpt_src)) {
                                        //file exists!
                                    } else {
                                        //file does not exist.
                                        $rpt_src_encrpt = "None";
                                    }
                                } else if ($outptUsd == "PDF") {
                                    $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row1[0].pdf";
                                    $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                    if (file_exists($rpt_src)) {
                                        //file exists!
                                    } else {
                                        //file does not exist.
                                        $rpt_src_encrpt = "None";
                                    }
                                } else if ($outptUsd == "MICROSOFT WORD") {
                                    $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row1[0].rtf";
                                    $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                    if (file_exists($rpt_src)) {
                                        //file exists!
                                    } else {
                                        //file does not exist.
                                        $rpt_src_encrpt = "None";
                                    }
                                } else if ($outptUsd == "MICROSOFT EXCEL") {
                                    $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row1[0].xls";
                                    $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                    if (file_exists($rpt_src)) {
                                        //file exists!
                                    } else {
                                        //file does not exist.
                                        $rpt_src_encrpt = "None";
                                    }
                                } else if ($outptUsd == "CHARACTER SEPARATED FILE (CSV)") {
                                    $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row1[0].csv";
                                    $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                    if (file_exists($rpt_src)) {
                                        //file exists!
                                    } else {
                                        //file does not exist.
                                        $rpt_src_encrpt = "None";
                                    }
                                } else {
                                    $rpt_src_encrpt = "None";
                                }
                                if ($rpt_src_encrpt == "None") {
                                    $dwnldUrl = $rpt_src_encrpt;
                                    $runStatusMsg .= "<button type=\"button\" class=\"btn btn-default\" style=\"margin-bottom: 0px;margin-top:5px !important;\" onclick=\"getOneRptsRnStsForm(" . $rptID . "," . $rptRunID . ",3,'0'," . $alrtID . ");\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View Status of Run\">
                                                    <img src=\"cmn_images/search.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\"> View Status </button>";
                                    //$runStatusMsg .= "<br/><span style=\"font-weight: bold;color:#FF0000;\">" . $rpt_src_encrpt . "</span>";
                                } else {
                                    $file_extension = strtolower(substr(strrchr($rpt_src, "."), 1));
                                    if ($file_extension != 'html') {
                                        $nwDwnldFileNm = "dwnlds/tmp/" . encrypt1(basename($rpt_src), $smplTokenWord1) . "." . $file_extension;
                                    } else {
                                        $rpt_src1 = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/amcharts_2100/images/";
                                        $rpt_dest1 = $fldrPrfx . "dwnlds/amcharts_2100/images/";
                                        if ($rpt_dest1 != "") {
                                            recurse_copy($rpt_src1, $rpt_dest1);
                                        }
                                        $nwDwnldFileNm = "dwnlds/amcharts_2100/samples/" . encrypt1(basename($rpt_src), $smplTokenWord1) . "." . $file_extension;
                                    }
                                    if (file_exists($rpt_src)) {
                                        copy($rpt_src, $fldrPrfx . $nwDwnldFileNm);
                                        $filename = $fldrPrfx . $nwDwnldFileNm;
                                        $curFiles = $_SESSION['CUR_RPT_FILES'];
                                        $_SESSION['CUR_RPT_FILES'] = $curFiles . "$filename" . "|";
                                    }
                                    //$a=1/0;
                                    $dwnldUrl = $nwDwnldFileNm;
                                    $runStatusMsg .= "<button type=\"button\" class=\"btn btn-default\" style=\"margin-bottom: 0px;margin-top:5px !important;\" onclick=\"doAjax('grp=1&typ=11&q=Download&fnm=" . $rpt_src_encrpt . "', '', 'Redirect', '', '', '');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"OPEN OUTPUT FILE\">
                                                    <img src=\"cmn_images/dwldicon.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\"> View Output </button>";
                                }
                                $percent = (int) $row1[5];
                                $arr_content['percent'] = $percent;
                                $arr_content['dwnld_url'] = $dwnldUrl;
                                if ($percent >= 100) {
                                    $arr_content['message'] = "<div style=\"color:green;margin-top:2px !important;\"><span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!...</span></div>" . $runStatusMsg;
                                } else {
                                    $arr_content['message'] = "<div style=\"color:blue;font-size:12px;text-align: center;margin-top:2px;\"><span style=\"color:blue;font-size:12px;text-align: center;\">Running Report/Process...Please Wait..." . $percent . " % Completed!</span></div>" . $runStatusMsg;
                                }
                                file_put_contents($ftp_base_db_fldr . "/bin/log_files/" . $lgn_num . "_" . $nwRndm . "_myrptsrun_progress.rho",
                                        json_encode($arr_content));
                            }
                        } while ($percent < 100);
                    } else {
                        $percent = 100;
                        $arr_content['percent'] = $percent;
                        $arr_content['message'] = "<div style=\"color:green;margin-top:2px !important;\"><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...Run ID Not Generated!<br/>$errMsg</span></div>";
                        $arr_content['rptRunID'] = "";
                        $arr_content['dwnld_url'] = "";
                        file_put_contents($ftp_base_db_fldr . "/bin/log_files/" . $lgn_num . "_" . $nwRndm . "_myrptsrun_progress.rho",
                                json_encode($arr_content));
                    }
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<div style=\"color:green;margin-top:2px !important;\"><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span></div>";
                    $arr_content['rptRunID'] = "";
                    $arr_content['dwnld_url'] = "";
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/" . $lgn_num . "_" . $nwRndm . "_myrptsrun_progress.rho",
                            json_encode($arr_content));
                }
            } else if ($actyp == 52) {
                //Check Report Run Process Status                
                header('Content-Type: application/json');
                $nwRndm = isset($_POST['nwRndm']) ? cleanInputData($_POST['nwRndm']) : 0;
                $file = $ftp_base_db_fldr . "/bin/log_files/" . $lgn_num . "_" . $nwRndm . "_myrptsrun_progress.rho";
                if (file_exists($file)) {
                    $text = file_get_contents($file);
                    echo $text;

                    $obj = json_decode($text);
                    if ($obj->percent >= 100) {
                        //session_write_close();
                        //sleep(10);
                        //$rs = file_exists($file) ? unlink($file) : TRUE;
                    }
                } else {
                    echo json_encode(array("percent" => 0, "message" => '<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Not Started</span>'));
                }
            }
        } else {
            if ($vwtyp == 0) {
                $pkID = isset($_POST['sbmtdRptID']) ? $_POST['sbmtdRptID'] : -1;
                $sbmtdAlrtID = -1;
                echo $cntent . "<li>
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Run Report/Process</span>
				</li>
                               </ul>
                              </div>";
                $total = get_RptsTtl($srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_RptsTblr($srchFor, $srchIn, $curIdx, $lmtSze);
                $cntr = 0;
                $colClassType3 = "col-lg-4";
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-6";
                ?>
                <form id='allRptsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <?php
                        if ($canaddRpts === true) {
                            ?> 
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 1px !important;"> 
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneReportStpForm(-1, 9, 'ShowDialog');" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="exprtRpts();" style="">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Export
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="importReports();" style="">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Import
                                    </button>
                                </div>
                            </div>
                            <?php
                        } else {
                            $colClassType1 = "col-lg-3";
                            $colClassType2 = "col-lg-5";
                            $colClassType3 = "col-lg-4";
                        }
                        ?>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <input class="form-control" id="allRptsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllRpts(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="allRptsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllRpts('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllRpts('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allRptsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "");
                                    $srchInsArrys = array("Report Name", "Report Description", "Owner Module");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allRptsDsplySze" style="min-width:70px !important;">                            
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
                                        <a class="rhopagination" href="javascript:getAllRpts('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllRpts('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row"  style="padding:1px 15px 1px 15px !important;"><hr style="margin:1px 0px 3px 0px;"></div>
                    <div class="row" style="padding:0px 15px 0px 15px !important"> 
                        <div  class="col-lg-4" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs">                                        
                                <table class="table table-striped table-bordered table-responsive" id="allRptsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Report/Process Name</th>
                                            <th>&nbsp;</th>
                                            <?php if ($caneditRpts === true) { ?>
                                                <th>&nbsp;</th>
                                            <?php } ?>
                                            <?php if ($canaddRpts === true) { ?>
                                                <th>&nbsp;</th>
                                            <?php } ?>
                                            <?php if ($candelRpts === true) { ?>
                                                <th>&nbsp;</th>
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
                                            <tr id="allRptsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="allRptsRow<?php echo $cntr; ?>_RptID" value="<?php echo $row[0]; ?>">
                                                </td>
                                                <td class="lovtd"><?php echo $row[1]; ?></td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="getOneRptsParamsForm(<?php echo $row[0]; ?>, -1, '<?php echo urlencode($row[1]); ?>', 2, -1, 'ShowDialog');" data-toggle="tooltip" data-placement="bottom" title="Run Report/Process">
                                                        <img src="cmn_images/98.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                                <?php if ($caneditRpts === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="getOneReportStpForm(<?php echo $row[0]; ?>, 8, 'ShowDialog', '0');" data-toggle="tooltip" data-placement="bottom" title="Edit Report/Process">
                                                            <img src="cmn_images/edit32.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <?php if ($canaddRpts === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="getOneReportStpForm(<?php echo $row[0]; ?>, 8, 'ShowDialog', '1');" data-toggle="tooltip" data-placement="bottom" title="Duplicate Report/Process">
                                                            <img src="cmn_images/Copy.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <?php if ($candelRpts === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delRpts('allRptsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Report/Process">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
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
                        <div  class="col-lg-8" style="padding:0px 1px 0px 1px !important">
                            <!--<fieldset class="basic_person_fs" style="padding-top:10px !important;">
                            </fieldset>-->
                            <div class="rho-container-fluid" id="rptsDetailInfo">
                                <?php
                                if ($pkID > 0) {
                                    $sbmtdRptID = $pkID;
                                    $result1 = get_RptsDet($pkID);
                                    while ($row1 = loc_db_fetch_array($result1)) {
                                        if ($caneditRpts === true) {
                                            ?>
                                            <!--<div class="row phone-only-btn" style="margin: 0px 0px 10px 0px !important;">
                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                    <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#rptsDetailInfo', 'grp=9&typ=1&pg=1&vtyp=1&sbmtdRptID=<?php echo $sbmtdRptID; ?>');">Report Runs</button>
                                                    <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#rptSetupTbPage', 'grp=9&typ=1&pg=1&vtyp=8&sbmtdRptID=<?php echo $sbmtdRptID; ?>');">Reports Setup</button>
                                                </div>
                                            </div>
                                            <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-5px !important;">
                                                <li class="active"><a data-toggle="tab" data-rhodata="&pg=1&vtyp=1&sbmtdRptID=<?php echo $sbmtdRptID; ?>" href="#rptRunsTbPage" id="rptRunsTbPagetab">Report Runs</a></li>
                                                <li><a data-toggle="tabajxrpt" data-rhodata="&pg=1&vtyp=8&sbmtdRptID=<?php echo $sbmtdRptID; ?>" href="#rptSetupTbPage" id="rptSetupTbPagetab">Reports Setup</a></li>
                                            </ul>-->                                    
                                            <!--<div class="row">                  
                                                <div class="col-md-12">
                                                    <div class="custDiv"> 
                                                        <div class="tab-content"> 
                                                            <div id="rptSetupTbPage" class="tab-pane fade" style="border:none !important;padding:1px !important;"></div>                       
                                                        </div>                         
                                                    </div>                
                                                </div>
                                            </div>-->

                                            <div id="rptRunsTbPage" class="tab-pane fadein active col-md-12" style="border:none !important;"> 
                                            <?php } else { ?>
                                                <div  class="rho-container-fluid1">
                                                <?php } ?>
                                                <div class="row">
                                                    <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                        <fieldset class="basic_person_fs" style="padding-top:10px !important;"> 
                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                <label for="rptsRptNm" class="control-label col-lg-4">Report Name:</label>
                                                                <div  class="col-lg-8">
                                                                    <span><?php echo $row1[1]; ?></span>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                    <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                        <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                <label for="rptsRptDesc" class="control-label col-lg-4">Description:</label>
                                                                <div  class="col-lg-8">
                                                                    <span><?php echo $row1[2]; ?></span>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <?php
                                                    $pageNo = 1;
                                                    $lmtSze = 10;
                                                    $srchFor = "%";
                                                    $srchIn = "Report Run ID";
                                                    $colClassType1 = "col-lg-2";
                                                    $colClassType2 = "col-lg-3";
                                                    $colClassType3 = "col-lg-5";
                                                    ?>
                                                    <div class="<?php echo $colClassType1; ?>" style="padding:0px 3px 0px 3px !important;">     
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneRptsParamsForm(<?php echo $row1[0]; ?>, -1, '<?php echo urlencode($row1[1]); ?>', 2, -1, 'ShowDialog');" data-toggle="tooltip" data-placement="bottom" title="Run Selected Report/Process">
                                                            <img src="cmn_images/98.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getAllSchdls('', '', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=3&vtyp=0', 1);" data-toggle="tooltip" data-placement="bottom" title="Schedule Selected Report/Process">
                                                            <img src="cmn_images/Calander.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </div>
                                                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                                                        <div class="input-group">
                                                            <input class="form-control" id="rptRunsSrchFor" type = "text" placeholder="Search For" value="<?php
                                                            echo trim(str_replace("%", " ", $srchFor));
                                                            ?>" onkeyup="enterKeyFuncRptRuns(event, '', '#rptsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1&sbmtdRptID=<?php echo $sbmtdRptID; ?>');">
                                                            <input id="rptRunsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllRptRuns('clear', '#rptsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1&sbmtdRptID=<?php echo $sbmtdRptID; ?>');">
                                                                <span class="glyphicon glyphicon-remove"></span>
                                                            </label>
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllRptRuns('', '#rptsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1&sbmtdRptID=<?php echo $sbmtdRptID; ?>');">
                                                                <span class="glyphicon glyphicon-search"></span>
                                                            </label> 
                                                        </div>
                                                    </div>
                                                    <div class="<?php echo $colClassType3; ?>" style="padding:0px 3px 0px 3px !important;">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="rptRunsSrchIn">
                                                                <?php
                                                                $valslctdArry = array("", "", "", "");
                                                                $srchInsArrys = array("Report Run ID", "Run By", "Run Date", "Run Status");

                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                    if ($srchIn == $srchInsArrys[$z]) {
                                                                        $valslctdArry[$z] = "selected";
                                                                    }
                                                                    ?>
                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="rptRunsDsplySze" style="min-width:70px !important;">                            
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
                                                    <div class="<?php echo $colClassType1; ?>" style="padding:0px 3px 0px 3px !important;">
                                                        <nav aria-label="Page navigation">
                                                            <ul class="pagination" style="margin: 0px !important;">
                                                                <li>
                                                                    <a class="rhopagination" href="javascript:getAllRptRuns('previous', '#rptsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1&sbmtdRptID=<?php echo $sbmtdRptID; ?>');" aria-label="Previous">
                                                                        <span aria-hidden="true">&laquo;</span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="rhopagination" href="javascript:getAllRptRuns('next', '#rptsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1&sbmtdRptID=<?php echo $sbmtdRptID; ?>');" aria-label="Next">
                                                                        <span aria-hidden="true">&raquo;</span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </nav>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <fieldset class="basic_person_fs">                                       
                                                            <table class="table table-striped table-bordered table-responsive" id="rptRunsTable" cellspacing="0" width="100%" style="width:100%;min-width: 530px;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>No.</th>
                                                                        <th>Report Run ID</th>
                                                                        <th style="min-width:110px !important;">Run Status</th>
                                                                        <th style="min-width:140px !important;">Last Time Active</th>
                                                                        <th>Open Output File</th>
                                                                        <th>Log Files</th>
                                                                        <th>Run By</th>
                                                                        <!--<th>Progress (%)</th>
                                                                        <th>Date Run</th>
                                                                        <th>Output Used</th>-->
                                                                        <!--<th>Run Source</th>-->
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $total = get_RptRunsTtl($pkID, $srchFor, $srchIn);
                                                                    if ($pageNo > ceil($total / $lmtSze)) {
                                                                        $pageNo = 1;
                                                                    } else if ($pageNo < 1) {
                                                                        $pageNo = ceil($total / $lmtSze);
                                                                    }
                                                                    $curIdx = $pageNo - 1;
                                                                    $result2 = get_RptRuns($pkID, $srchFor, $srchIn, $curIdx, $lmtSze);
                                                                    $cntr = 0;
                                                                    while ($row2 = loc_db_fetch_array($result2)) {
                                                                        $cntr += 1;
                                                                        $sbmtdAlrtID = $row2[14];
                                                                        //$chckd = FALSE; 
                                                                        $outptUsd = $row2[8];
                                                                        $rpt_src_encrpt = "";
                                                                        if ($outptUsd == "HTML" || $outptUsd == "COLUMN CHART" || $outptUsd == "SIMPLE COLUMN CHART" ||
                                                                                $outptUsd == "BAR CHART" || $outptUsd == "PIE CHART" || $outptUsd == "LINE CHART") {
                                                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/amcharts_2100/samples/$row2[0].html";
                                                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                                            if (file_exists($rpt_src)) {
                                                                                //file exists!
                                                                            } else {
                                                                                //file does not exist.
                                                                                $rpt_src_encrpt = "None";
                                                                            }
                                                                        } else if ($outptUsd == "STANDARD") {
                                                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].txt";
                                                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                                            if (file_exists($rpt_src)) {
                                                                                //file exists!
                                                                            } else {
                                                                                //file does not exist.
                                                                                $rpt_src_encrpt = "None";
                                                                            }
                                                                        } else if ($outptUsd == "PDF") {
                                                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].pdf";
                                                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                                            if (file_exists($rpt_src)) {
                                                                                //file exists!
                                                                            } else {
                                                                                //file does not exist.
                                                                                $rpt_src_encrpt = "None";
                                                                            }
                                                                        } else if ($outptUsd == "MICROSOFT WORD") {
                                                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].rtf";
                                                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                                            if (file_exists($rpt_src)) {
                                                                                //file exists!
                                                                            } else {
                                                                                //file does not exist.
                                                                                $rpt_src_encrpt = "None";
                                                                            }
                                                                        } else if ($outptUsd == "MICROSOFT EXCEL") {
                                                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].xls";
                                                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                                            if (file_exists($rpt_src)) {
                                                                                //file exists!
                                                                            } else {
                                                                                //file does not exist.
                                                                                $rpt_src_encrpt = "None";
                                                                            }
                                                                        } else if ($outptUsd == "CHARACTER SEPARATED FILE (CSV)") {
                                                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].csv";
                                                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                                            if (file_exists($rpt_src)) {
                                                                                //file exists!
                                                                            } else {
                                                                                //file does not exist.
                                                                                $rpt_src_encrpt = "None";
                                                                            }
                                                                        } else {
                                                                            $rpt_src_encrpt = "None";
                                                                        }
                                                                        ?>
                                                                        <tr id="rptRunsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                                            <td class="lovtd"><a href="javascript:getOneRptsRnStsForm(<?php echo $pkID; ?>, <?php echo $row2[0]; ?>, 3,'0', <?php echo $sbmtdAlrtID; ?>);" style="color:blue;font-weight:bold;"><?php echo $row2[0]; ?></a></td>
                                                                            <td class="lovtd"><span <?php
                                                                                $style2 = "";
                                                                                if ($row2[4] == "Not Started!") {
                                                                                    $style2 = "style=\"background-color: #E8D68C;padding:5px !important;\"";
                                                                                } else if ($row2[4] == "Preparing to Start...") {
                                                                                    $style2 = "style=\"background-color: yellow;padding:5px !important;\"";
                                                                                } else if ($row2[4] == "Running SQL...") {
                                                                                    $style2 = "style=\"background-color: lightgreen;padding:5px !important;\"";
                                                                                } else if ($row2[4] == "Formatting Output...") {
                                                                                    $style2 = "style=\"background-color: lime;padding:5px !important;\"";
                                                                                } else if ($row2[4] == "Storing Output..." || $row2[4] == "Sending Output...") {
                                                                                    $style2 = "style=\"background-color: cyan;padding:5px !important;\"";
                                                                                } else if ($row2[4] == "Completed!") {
                                                                                    $style2 = "style=\"background-color: gainsboro;padding:5px !important;\"";
                                                                                } else if (strpos($row2[4], "Error") !== FALSE) {
                                                                                    $style2 = "style=\"background-color: red;padding:5px !important;\"";
                                                                                }
                                                                                echo $style2;
                                                                                ?>><?php echo $row2[4]; ?></span></td>
                                                                            <td class="lovtd"><span <?php
                                                                                $tst = isDteTmeWthnIntrvl(cnvrtDMYTmToYMDTm($row2[10]), '40 second');
                                                                                $style2 = "";
                                                                                if ($tst == true) {
                                                                                    $style2 = "style=\"background-color: limegreen;color: white; font-size:12px;padding:2px;\"";
                                                                                }
                                                                                echo $style2;
                                                                                ?>><?php echo $row2[10]; ?></span></td>
                                                                            <td class="lovtd">
                                                                                <?php
                                                                                if ($rpt_src_encrpt == "None") {
                                                                                    ?>
                                                                                    <span style="font-weight: bold;color:#FF0000;">
                                                                                        <?php
                                                                                        echo $rpt_src_encrpt;
                                                                                        ?>
                                                                                    </span>
                                                                                    <?php
                                                                                } else {
                                                                                    ?>
                                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="doAjax('grp=1&typ=11&q=Download&fnm=<?php echo $rpt_src_encrpt; ?>', '', 'Redirect', '', '', '');" data-toggle="tooltip" data-placement="bottom" title="OPEN OUTPUT FILE">
                                                                                        <img src="cmn_images/dwldicon.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> View Output
                                                                                    </button>
                                                                                <?php } ?>
                                                                            </td>
                                                                            <td class="lovtd">
                                                                                <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneRptsLogForm(<?php echo $row2[0]; ?>);" data-toggle="tooltip" data-placement="bottom" title="VIEW PROCESS LOG FILE INFO">
                                                                                    <img src="cmn_images/vwlog.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> View Log
                                                                                </button>
                                                                            </td>
                                                                            <td class="lovtd"><?php echo $row2[2]; ?></td>
                                                                            <!--<td><?php echo $row2[5]; ?></td>
                                                                            <td><span><?php echo $row2[3]; ?></span></td>
                                                                            <td><span><?php echo $row2[8]; ?></span></td>-->
                                                                            <!--<td class="lovtd"><span><?php echo $row2[11]; ?></span></td>-->
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
                                                if ($caneditRpts === true) {
                                                    ?>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <?php
                                        }
                                    }
                                } else {
                                    ?>
                                    <span>No Results Found</span>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </form>
                <?php
            } else if ($vwtyp == 1) {
                $pkID = isset($_POST['sbmtdRptID']) ? $_POST['sbmtdRptID'] : -1;
                $sbmtdAlrtID = -1;
                if ($pkID > 0) {
                    $sbmtdRptID = $pkID;
                    $result1 = get_RptsDet($pkID);
                    while ($row1 = loc_db_fetch_array($result1)) {
                        if ($caneditRpts === true) {
                            ?>
                            <!--<div class="row phone-only-btn" style="margin: 0px 0px 10px 0px !important;">
                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                    <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#rptsDetailInfo', 'grp=9&typ=1&pg=1&vtyp=1&sbmtdRptID=<?php echo $sbmtdRptID; ?>');">Report Runs</button>
                                    <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#rptSetupTbPage', 'grp=9&typ=1&pg=1&vtyp=8&sbmtdRptID=<?php echo $sbmtdRptID; ?>');">Reports Setup</button>
                                </div>
                            </div>
                            <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-5px !important;">
                                <li class="active"><a data-toggle="tab" data-rhodata="&pg=1&vtyp=1&sbmtdRptID=<?php echo $sbmtdRptID; ?>" href="#rptRunsTbPage" id="rptRunsTbPagetab">Report Runs</a></li>
                                <li><a data-toggle="tabajxrpt" data-rhodata="&pg=1&vtyp=8&sbmtdRptID=<?php echo $sbmtdRptID; ?>" href="#rptSetupTbPage" id="rptSetupTbPagetab">Reports Setup</a></li>
                            </ul>-->                                    
                            <!--<div class="row">                  
                                <div class="col-md-12">
                                    <div class="custDiv"> 
                                        <div class="tab-content"> 
                                            <div id="rptSetupTbPage" class="tab-pane fade" style="border:none !important;padding:1px !important;"></div>                       
                                        </div>                         
                                    </div>                
                                </div> 
                            </div>-->

                            <div id="rptRunsTbPage" class="tab-pane fadein active col-md-12" style="border:none !important;"> 
                            <?php } else { ?>
                                <div  class="rho-container-fluid1">
                                <?php } ?>
                                <div class="row">
                                    <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                        <fieldset class="basic_person_fs" style="padding-top:10px !important;"> 
                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                <label for="rptsRptNm" class="control-label col-lg-4">Report Name:</label>
                                                <div  class="col-lg-8">
                                                    <span><?php echo $row1[1]; ?></span>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                        <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                <label for="rptsRptDesc" class="control-label col-lg-4">Description:</label>
                                                <div  class="col-lg-8">
                                                    <span><?php echo $row1[2]; ?></span>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php
                                    $colClassType1 = "col-lg-2";
                                    $colClassType2 = "col-lg-3";
                                    $colClassType3 = "col-lg-5";
                                    ?>
                                    <div class="<?php echo $colClassType1; ?>" style="padding:0px 3px 0px 3px !important;">     
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneRptsParamsForm(<?php echo $row1[0]; ?>, -1, '<?php echo urlencode($row1[1]); ?>', 2, -1, 'ShowDialog');" data-toggle="tooltip" data-placement="bottom" title="Run Selected Report/Process">
                                            <img src="cmn_images/98.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                        </button>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getAllSchdls('', '', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=3&vtyp=0', 1);" data-toggle="tooltip" data-placement="bottom" title="Schedule Selected Report/Process">
                                            <img src="cmn_images/Calander.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                        </button>
                                    </div>
                                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                                        <div class="input-group">
                                            <input class="form-control" id="rptRunsSrchFor" type = "text" placeholder="Search For" value="<?php
                                            echo trim(str_replace("%", " ", $srchFor));
                                            ?>" onkeyup="enterKeyFuncRptRuns(event, '', '#rptsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1&sbmtdRptID=<?php echo $sbmtdRptID; ?>');">
                                            <input id="rptRunsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllRptRuns('clear', '#rptsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1&sbmtdRptID=<?php echo $sbmtdRptID; ?>');">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </label>
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllRptRuns('', '#rptsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1&sbmtdRptID=<?php echo $sbmtdRptID; ?>');">
                                                <span class="glyphicon glyphicon-search"></span>
                                            </label> 
                                        </div>
                                    </div>
                                    <div class="<?php echo $colClassType3; ?>" style="padding:0px 3px 0px 3px !important;">
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                            <select data-placeholder="Select..." class="form-control chosen-select" id="rptRunsSrchIn">
                                                <?php
                                                $valslctdArry = array("", "", "", "");
                                                $srchInsArrys = array("Report Run ID", "Run By", "Run Date", "Run Status");

                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    if ($srchIn == $srchInsArrys[$z]) {
                                                        $valslctdArry[$z] = "selected";
                                                    }
                                                    ?>
                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                            <select data-placeholder="Select..." class="form-control chosen-select" id="rptRunsDsplySze" style="min-width:70px !important;">                            
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
                                    <div class="<?php echo $colClassType1; ?>" style="padding:0px 3px 0px 3px !important;">
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination" style="margin: 0px !important;">
                                                <li>
                                                    <a class="rhopagination" href="javascript:getAllRptRuns('previous', '#rptsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1&sbmtdRptID=<?php echo $sbmtdRptID; ?>');" aria-label="Previous">
                                                        <span aria-hidden="true">&laquo;</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="rhopagination" href="javascript:getAllRptRuns('next', '#rptsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1&sbmtdRptID=<?php echo $sbmtdRptID; ?>');" aria-label="Next">
                                                        <span aria-hidden="true">&raquo;</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <fieldset class="basic_person_fs">                                       
                                            <table class="table table-striped table-bordered table-responsive" id="rptRunsTable" cellspacing="0" width="100%" style="width:100%;min-width: 530px;">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Report Run ID</th>
                                                        <th style="min-width:110px !important;">Run Status</th>
                                                        <th style="min-width:140px !important;">Last Time Active</th>
                                                        <th>Open Output File</th>
                                                        <th>Log Files</th>
                                                        <th>Run By</th>
                                                        <!--<th>Progress (%)</th>
                                                        <th>Date Run</th>
                                                        <th>Output Used</th>-->
                                                        <!--<th>Run Source</th>-->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $total = get_RptRunsTtl($pkID, $srchFor, $srchIn);
                                                    if ($pageNo > ceil($total / $lmtSze)) {
                                                        $pageNo = 1;
                                                    } else if ($pageNo < 1) {
                                                        $pageNo = ceil($total / $lmtSze);
                                                    }

                                                    $curIdx = $pageNo - 1;
                                                    $result2 = get_RptRuns($pkID, $srchFor, $srchIn, $curIdx, $lmtSze);
                                                    $cntr = 0;
                                                    while ($row2 = loc_db_fetch_array($result2)) {
                                                        $cntr += 1;
                                                        $sbmtdAlrtID = $row2[14];
                                                        //$chckd = FALSE; 
                                                        $outptUsd = $row2[8];
                                                        $rpt_src_encrpt = "";
                                                        if ($outptUsd == "HTML" || $outptUsd == "COLUMN CHART" || $outptUsd == "SIMPLE COLUMN CHART" ||
                                                                $outptUsd == "BAR CHART" || $outptUsd == "PIE CHART" || $outptUsd == "LINE CHART") {
                                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/amcharts_2100/samples/$row2[0].html";
                                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                            if (file_exists($rpt_src)) {
                                                                //file exists!
                                                            } else {
                                                                //file does not exist.
                                                                $rpt_src_encrpt = "None";
                                                            }
                                                        } else if ($outptUsd == "STANDARD") {
                                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].txt";
                                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                            if (file_exists($rpt_src)) {
                                                                //file exists!
                                                            } else {
                                                                //file does not exist.
                                                                $rpt_src_encrpt = "None";
                                                            }
                                                        } else if ($outptUsd == "PDF") {
                                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].pdf";
                                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                            if (file_exists($rpt_src)) {
                                                                //file exists!
                                                            } else {
                                                                //file does not exist.
                                                                $rpt_src_encrpt = "None";
                                                            }
                                                        } else if ($outptUsd == "MICROSOFT WORD") {
                                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].rtf";
                                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                            if (file_exists($rpt_src)) {
                                                                //file exists!
                                                            } else {
                                                                //file does not exist.
                                                                $rpt_src_encrpt = "None";
                                                            }
                                                        } else if ($outptUsd == "MICROSOFT EXCEL") {
                                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].xls";
                                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                            if (file_exists($rpt_src)) {
                                                                //file exists!
                                                            } else {
                                                                //file does not exist.
                                                                $rpt_src_encrpt = "None";
                                                            }
                                                        } else if ($outptUsd == "CHARACTER SEPARATED FILE (CSV)") {
                                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].csv";
                                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                            if (file_exists($rpt_src)) {
                                                                //file exists!
                                                            } else {
                                                                //file does not exist.
                                                                $rpt_src_encrpt = "None";
                                                            }
                                                        } else {
                                                            $rpt_src_encrpt = "None";
                                                        }
                                                        ?>
                                                        <tr id="rptRunsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                            <td class="lovtd"><a href="javascript:getOneRptsRnStsForm(<?php echo $pkID; ?>, <?php echo $row2[0]; ?>, 3,'0', <?php echo $sbmtdAlrtID; ?>);" style="color:blue;font-weight:bold;"><?php echo $row2[0]; ?></a></td>
                                                            <td class="lovtd"><span <?php
                                                                $style2 = "";
                                                                if ($row2[4] == "Not Started!") {
                                                                    $style2 = "style=\"background-color: #E8D68C;padding:5px !important;\"";
                                                                } else if ($row2[4] == "Preparing to Start...") {
                                                                    $style2 = "style=\"background-color: yellow;padding:5px !important;\"";
                                                                } else if ($row2[4] == "Running SQL...") {
                                                                    $style2 = "style=\"background-color: lightgreen;padding:5px !important;\"";
                                                                } else if ($row2[4] == "Formatting Output...") {
                                                                    $style2 = "style=\"background-color: lime;padding:5px !important;\"";
                                                                } else if ($row2[4] == "Storing Output..." || $row2[4] == "Sending Output...") {
                                                                    $style2 = "style=\"background-color: cyan;padding:5px !important;\"";
                                                                } else if ($row2[4] == "Completed!") {
                                                                    $style2 = "style=\"background-color: gainsboro;padding:5px !important;\"";
                                                                } else if (strpos($row2[4], "Error") !== FALSE) {
                                                                    $style2 = "style=\"background-color: red;padding:5px !important;\"";
                                                                }
                                                                echo $style2;
                                                                ?>><?php echo $row2[4]; ?></span></td>
                                                            <td class="lovtd"><span <?php
                                                                $tst = isDteTmeWthnIntrvl(cnvrtDMYTmToYMDTm($row2[10]), '40 second');
                                                                $style2 = "";
                                                                if ($tst == true) {
                                                                    $style2 = "style=\"background-color: limegreen;color: white; font-size:12px;padding:2px;\"";
                                                                }
                                                                echo $style2;
                                                                ?>><?php echo $row2[10]; ?></span></td>
                                                            <td class="lovtd">
                                                                <?php
                                                                if ($rpt_src_encrpt == "None") {
                                                                    ?>
                                                                    <span style="font-weight: bold;color:#FF0000;">
                                                                        <?php
                                                                        echo $rpt_src_encrpt;
                                                                        ?>
                                                                    </span>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="doAjax('grp=1&typ=11&q=Download&fnm=<?php echo $rpt_src_encrpt; ?>', '', 'Redirect', '', '', '');" data-toggle="tooltip" data-placement="bottom" title="OPEN OUTPUT FILE">
                                                                        <img src="cmn_images/dwldicon.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> View Output
                                                                    </button>
                                                                <?php }
                                                                ?>
                                                            </td>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneRptsLogForm(<?php echo $row2[0]; ?>);" data-toggle="tooltip" data-placement="bottom" title="VIEW PROCESS LOG FILE INFO">
                                                                    <img src="cmn_images/vwlog.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> View Log
                                                                </button>
                                                            </td>
                                                            <td class="lovtd"><?php echo $row2[2]; ?></td>
                                                            <!--<td><?php echo $row2[5]; ?></td>
                                                            <td><span><?php echo $row2[3]; ?></span></td>
                                                            <td><span><?php echo $row2[8]; ?></span></td>-->
                                                            <!--<td class="lovtd"><span><?php echo $row2[11]; ?></span></td>-->
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
                                if ($caneditRpts === true) {
                                    ?>
                                </div>
                            </div>
                        <?php } else { ?>
                            <?php
                        }
                    }
                } else {
                    ?>
                    <span>No Results Found</span>
                    <?php
                }
            } else if ($vwtyp == 2) {
                //Report Run Parameter Form
                $rptID = isset($_POST['sbmtdRptID']) ? $_POST['sbmtdRptID'] : -1;
                $prvRunID = isset($_POST['sbmtdPrvRunID']) ? $_POST['sbmtdPrvRunID'] : -1;
                $sbmtdAlrtID = isset($_POST['sbmtdAlrtID']) ? $_POST['sbmtdAlrtID'] : -1;
                $pkID = $rptID;
                if ($canRunRpts === true) {
                    
                } else {
                    exit();
                }
                $reportName = getGnrlRecNm("rpt.rpt_reports", "report_id", "report_name", $pkID);
                $rptOutPut = getGnrlRecNm("rpt.rpt_reports", "report_id", "output_type", $pkID);
                $rptOrntn = getGnrlRecNm("rpt.rpt_reports", "report_id", "portrait_lndscp", $pkID);
                $result = get_AllParams($pkID);
                $sysParaIDs = array("-130", "-140", "-150", "-160", "-170", "-180", "-190", "-200");
                $sysParaNames = array("Report Title:", "Cols Nos To Group or Width & Height (Px) for Charts:",
                    "Cols Nos To Count or Use in Charts:", "Columns To Sum:", "Columns To Average:",
                    "Columns To Format Numerically:", "Report Output Formats", "Report Orientations");

                $paramIDs = getGnrlRecNm("rpt.rpt_report_runs", "rpt_run_id", "rpt_rn_param_ids", $prvRunID);
                $paramVals = getGnrlRecNm("rpt.rpt_report_runs", "rpt_run_id", "rpt_rn_param_vals", $prvRunID);
                $prvRunNm = "";
                if ($prvRunID > 0) {
                    $prvRunNm = getGnrlRecNm("rpt.rpt_report_runs", "rpt_run_id", "(run_status_txt || '-' || run_by || '-' || run_date)", $prvRunID);
                }
                $arry1 = explode("|", $paramIDs);
                $arry2 = explode("|", $paramVals);
                $cntr = 0;
                $curIdx = 0;
                ?>
                <form id='rptParamsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row">
                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                            <label for="rptParamPrvRun" class="control-label col-md-3">Copy Previous Values:</label>
                            <div  class="col-md-5">
                                <div class="input-group">
                                    <input type="text" class="form-control" aria-label="..." id="rptParamPrvRun" name="rptParamPrvRun" value="<?php echo $prvRunNm ?>" readonly="true">
                                    <input type="hidden" class="form-control" aria-label="..." id="rptParamPrvRunID" name="rptParamPrvRunID" value="<?php echo $prvRunID; ?>">
                                    <input type="hidden" class="form-control" aria-label="..." id="rptParamRunByID" name="rptParamRunByID" value="<?php echo $usrID; ?>">
                                    <input type="hidden" class="form-control" aria-label="..." id="rptParamRptID" name="rptParamRptID" value="<?php echo $pkID; ?>">
                                    <input type="hidden" class="form-control" aria-label="..." id="rptParamAlrtID" name="rptParamAlrtID" value="<?php echo $sbmtdAlrtID; ?>">
                                    <input type="hidden" class="form-control" aria-label="..." id="rptParamRptName" name="rptParamRptName" value="<?php echo $reportName; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Report/Process Runs', 'rptParamRptID', 'rptParamRunByID', '', 'radio', true, '<?php echo $prvRunID; ?>', 'rptParamPrvRunID', 'rptParamPrvRun', 'clear', 0, '', function () {
                                                reloadParams();
                                            });">
                                        <span class="glyphicon glyphicon-th-list"></span>
                                    </label>
                                </div>
                            </div>
                            <div  class="col-md-4">
                                <div class="row" style="float:right;">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneRptsRnStsForm(<?php echo $pkID; ?>, -1, 3, '0', <?php echo $sbmtdAlrtID; ?>);" data-toggle="tooltip" data-placement="bottom" title="Submit Report/Process">
                                        <img src="cmn_images/98.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Submit Report/Process
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">                                                             
                        <table class="table table-striped table-bordered table-responsive" id="rptParamsTable" cellspacing="0" width="100%" style="width:100%;min-width: 330px;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Parameter Name</th>
                                    <th style="min-width:180px !important;">Parameter Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = loc_db_fetch_array($result)) {
                                    $hideRowCls = "";
                                    if ($row[9] != "1") {
                                        $hideRowCls = "hideNotice";
                                    }
                                    $cntr += 1;
                                    $isrqrd = "";
                                    if ($row[4] == "1") {
                                        $isrqrd = "rqrdFld";
                                    }
                                    $nwval1 = $row[3];
                                    if ($prvRunID > 0) {
                                        $h1 = findArryIdx($arry1, $row[0]);
                                        if ($h1 >= 0) {
                                            $nwval1 = $arry2[$h1];
                                        }
                                    }
                                    $lovnm = $row[6];
                                    $dataTyp = $row[7];
                                    $dtFrmt = $row[8];
                                    ?>
                                    <tr id="rptParamsRow_<?php echo $cntr; ?>" class="hand_cursor <?php echo $hideRowCls; ?>">                                    
                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                        <td class="lovtd"><?php echo $row[1]; ?><input type="hidden" class="form-control" aria-label="..." id="rptParamsRow<?php echo $cntr; ?>_ParamID" value="<?php echo $row[0]; ?>"></td>
                                        <td class="lovtd">
                                            <?php if ($lovnm !== "") { ?>
                                                <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                    <div class="input-group"  style="width:100%;">
                                                        <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="rptParamsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $nwval1; ?>" readonly="true">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $lovnm; ?>', '', '', '', 'radio', true, '<?php echo $nwval1; ?>', 'rptParamsRow<?php echo $cntr; ?>_ParamVal', 'rptParamsRow<?php echo $cntr; ?>_ParamVal', 'clear', 0, '');">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <?php
                                            } else if ($dataTyp == "DATE") {
                                                $dtFrmtCls = "form_date_tme";
                                                if ($dtFrmt == "yyyy-MM-dd") {
                                                    $dtFrmt = "yyyy-mm-dd";
                                                    $dtFrmt1 = "yyyy-mm-dd";
                                                    $dtFrmtCls = "form_date1";
                                                } else if ($dtFrmt == "yyyy-MM-dd HH:mm:ss") {
                                                    $dtFrmt = "yyyy-mm-dd hh:ii:ss";
                                                    $dtFrmt1 = "yyyy-mm-dd hh:ii:ss";
                                                    $dtFrmtCls = "form_date_tme1";
                                                } else if ($dtFrmt == "dd-MMM-yyyy HH:mm:ss") {
                                                    $dtFrmt = "dd-M-yyyy hh:ii:ss";
                                                    $dtFrmt1 = "yyyy-mm-dd hh:ii:ss";
                                                    $dtFrmtCls = "form_date_tme";
                                                } else if ($dtFrmt == "dd-MMM-yyyy") {
                                                    $dtFrmt = "dd-M-yyyy";
                                                    $dtFrmt1 = "yyyy-mm-dd";
                                                    $dtFrmtCls = "form_date";
                                                } else {
                                                    $dtFrmt1 = "yyyy-mm-dd hh:ii:ss";
                                                    $dtFrmt = "dd-M-yyyy hh:ii:ss";
                                                }
                                                ?>                                            
                                                <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                    <div class="input-group date <?php echo $dtFrmtCls; ?>" data-date="" data-date-format="<?php echo $dtFrmt; ?>" data-link-field="dtp_input2" data-link-format="<?php echo $dtFrmt1; ?>" style="width:100%;">
                                                        <input class="form-control <?php echo $isrqrd ?>" size="16" type="text" id="rptParamsRow<?php echo $cntr; ?>_ParamVal" name="rptParamsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $nwval1; ?>" readonly="true">
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                    </div>                                                                
                                                </div>
                                                <?php
                                            } else if ($dataTyp == "NUMBER") {
                                                ?>                                            
                                                <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                    <input type="number" min="-999999999999" max="999999999999" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="rptParamsRow<?php echo $cntr; ?>_ParamVal" name="rptParamsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $nwval1; ?>" style="width:100%;">
                                                </div>
                                                <?php
                                            } else {
                                                ?>                                            
                                                <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                    <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="rptParamsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $nwval1; ?>" style="width:100% !important;">
                                                </div>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                $result1 = get_Rpt_ColsToAct($pkID);
                                $colNoVals = array("", "", "", "", "", "", "", "");
                                $colsCnt = loc_db_num_fields($result1);
                                while ($row1 = loc_db_fetch_array($result1)) {
                                    for ($d = 0; $d < $colsCnt; $d++) {
                                        if ($prvRunID > 0) {
                                            $h1 = findArryIdx($arry1, $sysParaIDs[$d]);
                                            if ($h1 >= 0) {
                                                $colNoVals[$d] = $arry2[$h1];
                                            } else {
                                                $colNoVals[$d] = $row1[$d];
                                            }
                                        } else {
                                            $colNoVals[$d] = $row1[$d];
                                        }
                                    }
                                }
                                for ($d = 0; $d < count($colNoVals); $d++) {
                                    if ($sysParaIDs[$d] !== "-190" && $sysParaIDs[$d] !== "-130") {
                                        continue;
                                    }
                                    $cntr++;
                                    $isrqrd = "";
                                    ?>
                                    <tr id="rptParamsRow_<?php echo $cntr; ?>" class="hand_cursor">
                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                        <td class="lovtd"><?php echo $sysParaNames[$d]; ?><input type="hidden" class="form-control" aria-label="..." id="rptParamsRow<?php echo $cntr; ?>_ParamID" value="<?php echo $sysParaIDs[$d]; ?>"></td>
                                        <td class="lovtd">  
                                            <?php
                                            if ($sysParaIDs[$d] == "-190") {
                                                $lovnm = "Report Output Formats";
                                                $nwval1 = $rptOutPut;
                                                ?>
                                                <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                    <div class="input-group"  style="width:100%;">
                                                        <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="rptParamsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $colNoVals[$d]; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $lovnm; ?>', '', '', '', 'radio', true, '<?php echo $nwval1; ?>', 'rptParamsRow<?php echo $cntr; ?>_ParamVal', 'rptParamsRow<?php echo $cntr; ?>_ParamVal', 'clear', 0, '');">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <?php
                                            } else if ($sysParaIDs[$d] == "-200") {
                                                $lovnm = "Report Orientations";
                                                $nwval1 = $rptOrntn;
                                                ?>
                                                <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                    <div class="input-group"  style="width:100%;">
                                                        <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="rptParamsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $colNoVals[$d]; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $lovnm; ?>', '', '', '', 'radio', true, '<?php echo $nwval1; ?>', 'rptParamsRow<?php echo $cntr; ?>_ParamVal', 'rptParamsRow<?php echo $cntr; ?>_ParamVal', 'clear', 0, '');">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <?php
                                            } else {
                                                ?>                                            
                                                <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                    <input type="text" class="form-control <?php echo $isrqrd ?>" aria-label="..." id="rptParamsRow<?php echo $cntr; ?>_ParamVal" value="<?php echo $colNoVals[$d]; ?>" style="width:100% !important;">
                                                </div>
                                            <?php }
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row" style="float:right;">
                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneRptsRnStsForm(<?php echo $pkID; ?>, -1, 3, '0', <?php echo $sbmtdAlrtID; ?>);" data-toggle="tooltip" data-placement="bottom" title="Submit Report/Process">
                            <img src="cmn_images/98.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Submit Report/Process
                        </button>
                    </div>
                </form>
                <?php
            } else if ($vwtyp == 3) {
                //Report Run Progress Status Form                
                if ($canRunRpts === true) {
                    
                } else {
                    exit();
                }
                $rptID = isset($_POST['sbmtdRptID']) ? $_POST['sbmtdRptID'] : -1;
                $prvRunID = isset($_POST['sbmtdRunID']) ? $_POST['sbmtdRunID'] : -1;
                $sbmtdAlrtID = isset($_POST['sbmtdAlrtID']) ? $_POST['sbmtdAlrtID'] : -1;
                $slctdParams = isset($_POST['slctdParams']) ? $_POST['slctdParams'] : "";
                $autoRfrsh = isset($_POST['autoRfrsh']) ? $_POST['autoRfrsh'] : "0";
                $mustReRun = isset($_POST['mustReRun']) ? $_POST['mustReRun'] : "0";
                $rptRunID = $prvRunID;
                if ($prvRunID <= 0) {
                    //Create Report Run and Launch
                    $slctdParams = urldecode($slctdParams);
                    $prvRunID = generateReportRun($rptID, $slctdParams, $sbmtdAlrtID);
                    $rptRunID = $prvRunID;
                } else if ($mustReRun == "1") {
                    $runStatus = getGnrlRecNm("rpt.rpt_report_runs", "rpt_run_id", "run_status_txt", $prvRunID);
                    $runActvTme = getGnrlRecNm("rpt.rpt_report_runs", "rpt_run_id", "last_actv_date_tme", $prvRunID);
                    if ($runStatus != 'Completed!' && $runStatus != 'Cancelled!' && $runStatus != 'Error!' && doesDteTmeExceedIntrvl($runActvTme,
                                    '30 second') == TRUE && $mustReRun == "1") {
                        reRunReport($rptID, $rptRunID);
                    }
                    //Launch Report Run is idle
                }
                $pkID = $rptID;
                $reportName = getGnrlRecNm("rpt.rpt_reports", "report_id", "report_name", $pkID);
                $rptOutPut = getGnrlRecNm("rpt.rpt_reports", "report_id", "output_type", $pkID);
                $rptOrntn = getGnrlRecNm("rpt.rpt_reports", "report_id", "portrait_lndscp", $pkID);
                $result = get_AllParams($pkID);
                $sysParaIDs = array("-130", "-140", "-150", "-160", "-170", "-180", "-190", "-200");
                $sysParaNames = array("Report Title:", "Cols Nos To Group or Width & Height (Px) for Charts:",
                    "Cols Nos To Count or Use in Charts:", "Columns To Sum:", "Columns To Average:",
                    "Columns To Format Numerically:", "Report Output Formats", "Report Orientations");

                $paramIDs = getGnrlRecNm("rpt.rpt_report_runs", "rpt_run_id", "rpt_rn_param_ids", $prvRunID);
                $paramVals = getGnrlRecNm("rpt.rpt_report_runs", "rpt_run_id", "rpt_rn_param_vals", $prvRunID);
                $prvRunNm = "";
                if ($prvRunID > 0) {
                    $prvRunNm = getGnrlRecNm("rpt.rpt_report_runs", "rpt_run_id", "(run_status_txt || '-' || run_by || '-' || run_date)", $prvRunID);
                }
                $arry1 = explode("|", $paramIDs);
                $arry2 = explode("|", $paramVals);
                $cntr = 0;
                $curIdx = 0;
                $result1 = get_OneRptRun($prvRunID);
                while ($row1 = loc_db_fetch_array($result1)) {
                    $runStatus = $row1[4];
                    $runActvTme = $row1[14];
                    $alrtID = $row1[15];
                    if ((integer) $row1[5] >= 100) {
                        $autoRfrsh = "0";
                    }
                    ?>
                    <form id='rptParamsStsForm' action='' method='post' accept-charset='UTF-8'>                    
                        <div class="row rhoRowMargin">
                            <?php if ($autoRfrsh == "1") { ?>
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;margin-right:5px;" onclick="autoRfrshRptsRnSts(<?php echo $pkID; ?>, <?php echo $prvRunID ?>, 1);" data-toggle="tooltip" data-placement="bottom" title="Stop Auto-Refresh Report/Process Status">
                                    <img src="cmn_images/90.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Stop Auto-Refresh
                                </button>
                            <?php } else { ?>
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;margin-right:5px;" onclick="autoRfrshRptsRnSts(<?php echo $pkID; ?>, <?php echo $prvRunID ?>, 0);" data-toggle="tooltip" data-placement="bottom" title="Auto-Refresh Report/Process Status">
                                    <img src="cmn_images/clock.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Auto-Refresh
                                </button>
                            <?php } ?>
                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;margin-right:5px;" onclick="getOneRptsRnStsForm(<?php echo $pkID; ?>, <?php echo $prvRunID ?>, 3, '0', <?php echo $sbmtdAlrtID; ?>);" data-toggle="tooltip" data-placement="bottom" title="Refresh Report/Process Status">
                                <img src="cmn_images/refresh.bmp" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Refresh
                            </button>
                            <?php
                            if ($runStatus != 'Completed!' && $runStatus != 'Cancelled!' && $runStatus != 'Error!' && doesDteTmeExceedIntrvl($runActvTme,
                                            '30 second') == TRUE) {
                                ?>                            
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;margin-right:5px;" onclick="getOneRptsRnStsForm(<?php echo $pkID; ?>, <?php echo $prvRunID ?>, 3, '1', <?php echo $sbmtdAlrtID; ?>);" data-toggle="tooltip" data-placement="bottom" title="Re-run Report/Process">
                                    <img src="cmn_images/reload.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> RE-RUN
                                </button>
                                <?php
                            } else if ($runStatus != 'Completed!' && $runStatus != 'Cancelled!' && $runStatus != 'Error!' && isDteTmeWthnIntrvl($runActvTme,
                                            '30 second') == TRUE) {
                                ?>                            
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;margin-right:5px;" onclick="cancelRptRun(<?php echo $pkID; ?>, <?php echo $prvRunID ?>,<?php echo $alrtID; ?>);" data-toggle="tooltip" data-placement="bottom" title="Cancel Report/Process Run">
                                    <img src="cmn_images/90.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> CANCEL RUN
                                </button>
                                <?php
                            }
                            ?>
                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;margin-right:5px;" onclick="closeOneRptsRnStsForm();" data-toggle="tooltip" data-placement="bottom" title="Close Report/Process Status">
                                <img src="cmn_images/close.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> CLOSE
                            </button>
                        </div>
                        <div class="row">
                            <fieldset class="basic_person_fs" style="padding-top:10px !important;"> 
                                <div  class="col-md-6" style="padding:0px !important;">
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;margin-bottom:1px !important;">
                                        <label for="rptParamsStsRunID" class="control-label col-lg-5">Run ID:</label>
                                        <div  class="col-lg-7">
                                            <span><?php echo $row1[0]; ?><input type="hidden" id="rptParamsStsRunID" name="rptParamsStsRunID" value="<?php echo $row1[0]; ?>"></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;margin-bottom:1px !important;">
                                        <label for="rptParamsStsDtRn" class="control-label col-lg-5">Date Run:</label>
                                        <div  class="col-lg-7">
                                            <span><?php echo $row1[3]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;margin-bottom:1px !important;">
                                        <label for="rptParamsStsPrgrs" class="control-label col-lg-5">Progress (%):</label>
                                        <div  class="col-lg-7">
                                            <span><?php echo $row1[5]; ?><input type="hidden" id="rptParamsStsPrgrs" name="rptParamsStsPrgrs" value="<?php echo $row1[5]; ?>"></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;margin-bottom:1px !important;">
                                        <label for="rptParamsStsOrntn" class="control-label col-lg-5">Orientation Used:</label>
                                        <div  class="col-lg-7">
                                            <span><?php echo $row1[9]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;margin-bottom:1px !important;">
                                        <label for="rptParamsStsRnSrc" class="control-label col-lg-5">Run Source:</label>
                                        <div  class="col-lg-7">
                                            <span><?php echo $row1[11] . " <strong>(" . $row1[16] . ")</strong>"; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div  class="col-md-6" style="padding:0px !important;">
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;margin-bottom:1px !important;">
                                        <label for="rptParamsStsRnBy" class="control-label col-lg-5">Run By:</label>
                                        <div  class="col-lg-7">
                                            <span><?php echo $row1[2]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;margin-bottom:1px !important;">
                                        <label for="rptParamsStsRun" class="control-label col-lg-5">Run Status Text:</label>
                                        <div  class="col-lg-7">
                                            <span <?php
                                            $style2 = "";
                                            if ($row1[4] == "Not Started!") {
                                                $style2 = "style=\"background-color: #E8D68C;padding:5px !important;\"";
                                            } else if ($row1[4] == "Preparing to Start...") {
                                                $style2 = "style=\"background-color: yellow;padding:5px !important;\"";
                                            } else if ($row1[4] == "Running SQL...") {
                                                $style2 = "style=\"background-color: lightgreen;padding:5px !important;\"";
                                            } else if ($row1[4] == "Formatting Output...") {
                                                $style2 = "style=\"background-color: lime;padding:5px !important;\"";
                                            } else if ($row1[4] == "Storing Output..." || $row1[4] == "Sending Output...") {
                                                $style2 = "style=\"background-color: cyan;padding:5px !important;\"";
                                            } else if ($row1[4] == "Completed!") {
                                                $style2 = "style=\"background-color: gainsboro;padding:5px !important;\"";
                                            } else if (strpos($row1[4], "Error") !== FALSE) {
                                                $style2 = "style=\"background-color: red;padding:5px !important;\"";
                                            }
                                            echo $style2;
                                            ?>><?php echo $row1[4]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;margin-bottom:1px !important;">
                                        <label for="rptParamsStsOutpt" class="control-label col-lg-5">Output Used:</label>
                                        <div  class="col-lg-7">
                                            <span><?php echo $row1[8]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;margin-bottom:1px !important;">
                                        <label for="rptParamsStsLstActv" class="control-label col-lg-5">Last Time Active:</label>
                                        <div  class="col-lg-7">
                                            <span <?php
                                            $tst = isDteTmeWthnIntrvl(cnvrtDMYTmToYMDTm($row1[10]), '40 second');
                                            $style2 = "";
                                            if ($tst == true) {
                                                $style2 = "style=\"background-color: limegreen;color: white; font-size:12px;padding:2px;\"";
                                            }
                                            echo $style2;
                                            ?>><?php echo $row1[10]; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;margin-bottom:1px !important;">
                                        <?php
                                        $outptUsd = $row1[8];
                                        $rpt_src_encrpt = "";
                                        if ($outptUsd == "HTML" || $outptUsd == "COLUMN CHART" || $outptUsd == "SIMPLE COLUMN CHART" || $outptUsd == "BAR CHART" ||
                                                $outptUsd == "PIE CHART" || $outptUsd == "LINE CHART") {
                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/amcharts_2100/samples/$row1[0].html";
                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                            if (file_exists($rpt_src)) {
                                                //file exists!
                                            } else {
                                                //file does not exist.
                                                $rpt_src_encrpt = "None";
                                            }
                                        } else if ($outptUsd == "STANDARD") {
                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row1[0].txt";
                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                            if (file_exists($rpt_src)) {
                                                //file exists!
                                            } else {
                                                //file does not exist.
                                                $rpt_src_encrpt = "None";
                                            }
                                        } else if ($outptUsd == "PDF") {
                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row1[0].pdf";
                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                            if (file_exists($rpt_src)) {
                                                //file exists!
                                            } else {
                                                //file does not exist.
                                                $rpt_src_encrpt = "None";
                                            }
                                        } else if ($outptUsd == "MICROSOFT WORD") {
                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row1[0].rtf";
                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                            if (file_exists($rpt_src)) {
                                                //file exists!
                                            } else {
                                                //file does not exist.
                                                $rpt_src_encrpt = "None";
                                            }
                                        } else if ($outptUsd == "MICROSOFT EXCEL") {
                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row1[0].xls";
                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                            if (file_exists($rpt_src)) {
                                                //file exists!
                                            } else {
                                                //file does not exist.
                                                $rpt_src_encrpt = "None";
                                            }
                                        } else if ($outptUsd == "CHARACTER SEPARATED FILE (CSV)") {
                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row1[0].csv";
                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                            if (file_exists($rpt_src)) {
                                                //file exists!
                                            } else {
                                                //file does not exist.
                                                $rpt_src_encrpt = "None";
                                            }
                                        } else {
                                            $rpt_src_encrpt = "None";
                                        }
                                        ?>
                                        <label for="rptParamsStsRslts" class="control-label col-lg-4">Results:</label>
                                        <div  class="col-lg-4">
                                            <?php
                                            if ($rpt_src_encrpt == "None") {
                                                ?>
                                                <div class="row" style="padding:1px 10px 15px 47px;font-weight: bold;color:#FF0000;">
                                                    <?php
                                                    echo $rpt_src_encrpt;
                                                    ?>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="doAjax('grp=1&typ=11&q=Download&fnm=<?php echo $rpt_src_encrpt; ?>', '', 'Redirect', '', '', '');" data-toggle="tooltip" data-placement="bottom" title="OPEN OUTPUT FILE">
                                                    <img src="cmn_images/dwldicon.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> View Output
                                                </button>
                                            <?php }
                                            ?>
                                        </div>
                                        <div  class="col-lg-4">
                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneRptsLogForm(<?php echo $row1[0]; ?>);" data-toggle="tooltip" data-placement="bottom" title="VIEW PROCESS LOG FILE INFO">
                                                <img src="cmn_images/vwlog.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> View Log
                                            </button>
                                        </div>
                                    </div>
                                </div>                                
                            </fieldset>
                        </div>
                        <div class="row">                                                             
                            <table class="table table-striped table-bordered table-responsive" id="rptParamsStsTable" cellspacing="0" width="100%" style="width:100%;min-width: 330px;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Parameter Name</th>
                                        <th style="min-width:180px !important;">Parameter Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        $cntr += 1;
                                        $isrqrd = "";
                                        if ($row[4] == "1") {
                                            $isrqrd = "rqrdFld";
                                        }
                                        $nwval1 = $row[3];
                                        if ($prvRunID > 0) {
                                            $h1 = findArryIdx($arry1, $row[0]);
                                            if ($h1 >= 0) {
                                                $nwval1 = $arry2[$h1];
                                            }
                                        }
                                        $lovnm = $row[6];
                                        $dataTyp = $row[7];
                                        $dtFrmt = $row[8];
                                        ?>
                                        <tr id="rptParamsStsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd"><?php echo $row[1]; ?></td>
                                            <td class="lovtd"><?php echo $nwval1; ?></td>
                                        </tr>
                                        <?php
                                    }
                                    $result2 = get_Rpt_ColsToAct($pkID);
                                    $colNoVals = array("", "", "", "", "", "", "", "");
                                    $colsCnt = loc_db_num_fields($result2);
                                    while ($row2 = loc_db_fetch_array($result2)) {
                                        for ($d = 0; $d < $colsCnt; $d++) {
                                            if ($prvRunID > 0) {
                                                $h1 = findArryIdx($arry1, $sysParaIDs[$d]);
                                                if ($h1 >= 0) {
                                                    $colNoVals[$d] = $arry2[$h1];
                                                } else {
                                                    $colNoVals[$d] = $row2[$d];
                                                }
                                            } else {
                                                $colNoVals[$d] = $row2[$d];
                                            }
                                        }
                                    }
                                    for ($d = 0; $d < count($colNoVals); $d++) {
                                        if ($sysParaIDs[$d] !== "-190" && $sysParaIDs[$d] !== "-130") {
                                            continue;
                                        }
                                        $cntr++;
                                        $isrqrd = "";
                                        ?>
                                        <tr id="rptParamsStsRow_<?php echo $cntr; ?>" class="hand_cursor">
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd"><?php echo $sysParaNames[$d]; ?></td>
                                            <td class="lovtd"><?php echo $colNoVals[$d]; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                    <?php
                }
            } else if ($vwtyp == 4) {
                //Cancel Report Run Progress  
                $rptID = isset($_POST['sbmtdRptID']) ? $_POST['sbmtdRptID'] : -1;
                $rptRunID = isset($_POST['sbmtdRunID']) ? $_POST['sbmtdRunID'] : -1;
                if ($canRunRpts === true && $rptRunID > 0 && $rptID > 0) {
                    
                } else {
                    exit();
                }
                $rptRnnrNm = getGnrlRecNm("rpt.rpt_reports", "report_id", "process_runner", $rptID);
                $msg_id = getLogMsgID("rpt.rpt_run_msgs", "Process Run", $rptRunID);
                $alrtID = getGnrlRecNm("rpt.rpt_report_runs", "rpt_run_id", "alert_id", $rptRunID);
                $datestr = getDB_Date_time();
                updateLogMsg($msg_id, "\r\n\r\nProgram Cancelled by User " . $usrName, "rpt.rpt_run_msgs", $datestr);
                updateRptRnStopCmd($rptRunID, "1");
                ?>
                <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                    <div class="row" style="float:none;width:100%;text-align: center;">
                        <span style="color:green;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Cancellation Request Submitted Successfully!</span>
                    </div>
                    <div class="row" style="float:none;width:100%;text-align: center;">
                        <span style="color:green;font-weight:bold;font-size:12px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Keep refreshing till status changes!</span>
                    </div>
                    <div class="row" style="float:none;width:100%;text-align: center;">
                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;width:100%;text-align: center;" onclick="rfrshCncldRptRun(<?php echo $rptID; ?>,<?php echo $alrtID; ?>);" data-toggle="tooltip" data-placement="bottom" title="Refresh Report/Process Status">
                            <img src="cmn_images/refresh.bmp" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Refresh Report Runs Table
                        </button>
                    </div>
                </div>
                <?php
            } else if ($vwtyp == 8) {
                //echo "Create Reports"
                $pkID = isset($_POST['sbmtdRptID']) ? $_POST['sbmtdRptID'] : -1;
                $shdDplct = isset($_POST['shdDplct']) ? $_POST['shdDplct'] : '0';
                $srcCaller = isset($_POST['srcCaller']) ? $_POST['srcCaller'] : 'NORMAL';
                if (trim($srcCaller) === "") {
                    $srcCaller = 'NORMAL';
                }
                $sbmtdRptID = -1;
                $sbmtdRptIDDsply = -1;
                $rptsRptNm = "";
                if ($pkID > 0) {
                    $sbmtdRptID = $pkID;
                    $result1 = get_RptsDet($pkID);
                    while ($row1 = loc_db_fetch_array($result1)) {
                        if ($shdDplct == '0') {
                            $sbmtdRptIDDsply = $pkID;
                            $rptsRptNm = $row1[1];
                        } else {
                            $sbmtdRptIDDsply = -1;
                            $rptsRptNm = "(Duplicate) " . $row1[1];
                        }
                        ?>
                        <form id="rptsStpForm">
                            <div class="row">                                 
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveReportsForm('<?php echo $srcCaller; ?>');" style="width:100% !important;">
                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Save Report
                                    </button>
                                </div>
                                <div class="col-md-6" style="padding:0px 20px 0px 20px !important;">
                                    <div class="" style="float:right;">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;"> 
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="rptsRptNm" class="control-label col-lg-4">Process Name:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($caneditRpts === true) { ?>
                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="rptsRptNm" name="rptsRptNm" value="<?php echo $rptsRptNm; ?>" style="width:100%;">
                                                <input type="hidden" class="form-control" aria-label="..." id="rptsRptID" name="rptsRptID" value="<?php echo $sbmtdRptIDDsply; ?>" style="width:100%;">
                                            <?php } else { ?>
                                                <span><?php echo $row1[1]; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="rptsRptDesc" class="control-label col-lg-4">Description:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($caneditRpts === true) { ?>
                                                <textarea class="form-control" aria-label="..." id="rptsRptDesc" name="rptsRptDesc" style="width:100%;" cols="4" rows="5"><?php echo $row1[2]; ?></textarea>
                                            <?php } else { ?>
                                                <span><?php echo $row1[2]; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div> 
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="rptsOwnrMdl" class="control-label col-lg-4">Owner Module:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($caneditRpts === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="rptsOwnrMdl" value="<?php echo $row1[4]; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'System Modules', '', '', '', 'radio', true, '<?php echo $row1[4]; ?>', '', 'rptsOwnrMdl', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else {
                                                ?>
                                                <span><?php echo $row1[4]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="rptsProcessTyp" class="control-label col-lg-4">Process Type:</label>
                                        <div class="col-lg-8">
                                            <?php if ($caneditRpts === true) { ?>
                                                <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="rptsProcessTyp" name="rptsProcessTyp">
                                                    <?php
                                                    $valslctdArry = array("", "", "", "", "", "", "", "");
                                                    $srchInsArrys = array("SQL Report", "Database Function", "Command Line Script-Windows", "Command Line Script-Linux",
                                                        "Posting of GL Trns. Batches", "Journal Import", "Import/Overwrite Data from Excel", "System Process");
                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                        if ($row1[5] == $srchInsArrys[$z]) {
                                                            $valslctdArry[$z] = "selected";
                                                        }
                                                        ?>
                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } else {
                                                ?>
                                                <span><?php echo $row1[5]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="rptsPrcsRnnr" class="control-label col-lg-4">Process Runner:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($caneditRpts === true) { ?>
                                                <div class="input-group">
                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="rptsPrcsRnnr" value="<?php echo $row1[17]; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Background Process Runners', '', '', '', 'radio', true, '<?php echo $row1[17]; ?>', '', 'rptsPrcsRnnr', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else {
                                                ?>
                                                <span><?php echo $row1[17]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="rptsColsToGrp" class="control-label col-lg-6">Cols Nos To Group or Width & Height (px) for Charts:</label>
                                        <div  class="col-lg-6">
                                            <?php if ($caneditRpts === true) { ?>
                                                <input type="text" class="form-control" aria-label="..." id="rptsColsToGrp" name="rptsColsToGrp" value="<?php echo $row1[7]; ?>" style="width:100%;">
                                            <?php } else { ?>
                                                <span><?php echo $row1[7]; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="rptsColsToCnt" class="control-label col-lg-6">Cols Nos To Count or Use in Charts:</label>
                                        <div  class="col-lg-6">
                                            <?php if ($caneditRpts === true) { ?>
                                                <input type="text" class="form-control" aria-label="..." id="rptsColsToCnt" name="rptsColsToCnt" value="<?php echo $row1[8]; ?>" style="width:100%;">
                                            <?php } else { ?>
                                                <span><?php echo $row1[8]; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="rptsColsToFrmt" class="control-label col-lg-6">Cols Nos To Format Numerically:</label>
                                        <div  class="col-lg-6">
                                            <?php if ($caneditRpts === true) { ?>
                                                <input type="text" class="form-control" aria-label="..." id="rptsColsToFrmt" name="rptsColsToFrmt" value="<?php echo $row1[11]; ?>" style="width:100%;">
                                            <?php } else { ?>
                                                <span><?php echo $row1[11]; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="rptsColsToSum" class="control-label col-lg-6">Cols Nos To Sum:</label>
                                        <div  class="col-lg-6">
                                            <?php if ($caneditRpts === true) { ?>
                                                <input type="text" class="form-control" aria-label="..." id="rptsColsToSum" name="rptsColsToSum" value="<?php echo $row1[9]; ?>" style="width:100%;">
                                            <?php } else { ?>
                                                <span><?php echo $row1[9]; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="rptsColsToAvrg" class="control-label col-lg-6">Cols Nos To Average:</label>
                                        <div  class="col-lg-6">
                                            <?php if ($caneditRpts === true) { ?>
                                                <input type="text" class="form-control" aria-label="..." id="rptsColsToAvrg" name="rptsColsToAvrg" value="<?php echo $row1[10]; ?>" style="width:100%;">
                                            <?php } else { ?>
                                                <span><?php echo $row1[10]; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="rptsOutputType" class="control-label col-lg-6">Output Type:</label>
                                        <div class="col-lg-6">
                                            <?php if ($caneditRpts === true) { ?>
                                                <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="rptsOutputType" name="rptsOutputType">
                                                    <?php
                                                    $valslctdArry = array("", "", "", "", "", "", "", "", "", "");
                                                    $srchInsArrys = array("None", "HTML", "STANDARD", "MICROSOFT EXCEL", "PDF",
                                                        "MICROSOFT WORD", "CHARACTER SEPARATED FILE (CSV)", "COLUMN CHART", "PIE CHART", "LINE CHART");
                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                        if ($row1[12] == $srchInsArrys[$z]) {
                                                            $valslctdArry[$z] = "selected";
                                                        }
                                                        ?>
                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } else { ?>
                                                <span><?php echo $row1[12]; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="rptsOrntn" class="control-label col-lg-6">Orientation:</label>
                                        <div class="col-lg-6">
                                            <?php if ($caneditRpts === true) { ?>
                                                <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="rptsOrntn" name="rptsOrntn">
                                                    <?php
                                                    $valslctdArry = array("", "", "");
                                                    $srchInsArrys = array("None", "Portrait", "Landscape");
                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                        if ($row1[13] == $srchInsArrys[$z]) {
                                                            $valslctdArry[$z] = "selected";
                                                        }
                                                        ?>
                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } else { ?>
                                                <span><?php echo $row1[13]; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="rptsLayout" class="control-label col-lg-6">Layout:</label>
                                        <div class="col-lg-6">
                                            <?php if ($caneditRpts === true) { ?>
                                                <select data-placeholder="Select..." class="form-control chosen-select" id="rptsLayout" name="rptsLayout">
                                                    <?php
                                                    $valslctdArry = array("", "", "");
                                                    $srchInsArrys = array("None", "TABULAR", "DETAIL");
                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                        if ($row1[14] == $srchInsArrys[$z]) {
                                                            $valslctdArry[$z] = "selected";
                                                        }
                                                        ?>
                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } else { ?>
                                                <span><?php echo $row1[14]; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div> 
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="rptsDelimiter" class="control-label col-lg-6">Delimiter:</label>
                                        <div class="col-lg-6">
                                            <?php if ($caneditRpts === true) { ?>
                                                <select data-placeholder="Select..." class="form-control chosen-select" id="rptsDelimiter" name="rptsDelimiter">
                                                    <?php
                                                    $valslctdArry = array("", "", "", "", "", "");
                                                    $srchInsArrys = array("None", "Comma (,)", "Semi-Colon(;)", "Pipe(|)", "Tab", "Tilde(~)");
                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                        if ($row1[16] == $srchInsArrys[$z]) {
                                                            $valslctdArry[$z] = "selected";
                                                        }
                                                        ?>
                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } else { ?>
                                                <span><?php echo $row1[16]; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>                                
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="rptsDetImgCols" class="control-label col-lg-6">Detailed Report Images Cols:</label>
                                        <div  class="col-lg-6">
                                            <?php if ($caneditRpts === true) { ?>
                                                <input type="text" class="form-control" aria-label="..." id="rptsDetImgCols" name="rptsDetImgCols" value="<?php echo $row1[15]; ?>" style="width:100%;">
                                            <?php } else { ?>
                                                <span><?php echo $row1[15]; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>   
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="rptsJrxmlFile" class="control-label col-lg-2">Jrxml File:</label>
                                        <div  class="col-lg-10">
                                            <?php if ($caneditRpts === true) { ?>
                                                <div class="input-group">
                                                    <label class="btn btn-primary btn-file input-group-addon">
                                                        Browse... <input type="file" id="rptsJrxmlFile" name="rptsJrxmlFile" onchange="setFileLoc(this, '#rptsJrxmlFileSrc');" class="btn btn-default"  style="display: none;" accept=".jrxml">
                                                    </label>
                                                    <input type="text" class="form-control" aria-label="..." id="rptsJrxmlFileSrc" value="<?php echo $row1[19]; ?>">                                                        
                                                </div> 
                                            <?php } else { ?>
                                                <span><?php echo $row1[19]; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="rptsIsEnabled" class="control-label col-lg-7">Enabled?:</label>
                                        <div  class="col-lg-5">
                                            <?php
                                            $chkdYes = "";
                                            $chkdNo = "checked=\"\"";
                                            if ($row1[6] == "1") {
                                                $chkdNo = "";
                                                $chkdYes = "checked=\"\"";
                                            }
                                            ?>
                                            <?php if ($caneditRpts === true) { ?>
                                                <label class="radio-inline"><input type="radio" name="rptsIsEnabled" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input type="radio" name="rptsIsEnabled" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            <?php } else {
                                                ?>
                                                <span><?php echo ($row1[6] == "1" ? "YES" : "NO"); ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>   
                                </div>
                            </div>
                            <div class="row"  style="padding:1px 15px 5px 15px !important;"><hr style="margin:1px 0px 3px 0px;"></div> 
                            <div class="row"  style="padding:1px 15px 1px 15px !important;">
                                <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-5px !important;">
                                    <li class="active"><a data-toggle="tabajxrptdet" data-rhodata="" href="#rptMainSQLTbPage" id="rptMainSQLTbPagetab">Main Query</a></li>
                                    <li><a data-toggle="tabajxrptdet" data-rhodata="" href="#rptPreSQLTbPage" id="rptPreSQLTbPagetab">Pre-Report Query</a></li>
                                    <li><a data-toggle="tabajxrptdet" data-rhodata="" href="#rptPostSQLTbPage" id="rptPostSQLTbPagetab">Post-Report Query</a></li>
                                    <li><a data-toggle="tabajxrptdet" data-rhodata="" href="#rptParamsTbPage" id="rptParamsTbPagetab">Parameters</a></li>
                                    <li><a data-toggle="tabajxrptdet" data-rhodata="" href="#rptSubPrgmsTbPage" id="rptSubPrgmsTbPagetab">Sub-Program Units</a></li>
                                    <li><a data-toggle="tabajxrptdet" data-rhodata="" href="#rptAlwdRolesTbPage" id="rptAlwdRolesTbPagetab">Allowed Roles</a></li>
                                </ul>                                    
                                <div class="row">                  
                                    <div class="col-md-12">
                                        <div class="custDiv"> 
                                            <div class="tab-content">
                                                <div id="rptMainSQLTbPage" class="tab-pane fadein active" style="border:none !important;">
                                                    <div class="form-group form-group-sm" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="rptsQuerySQL" class="control-label">SQL Query for Report / Process:</label>
                                                        <div  class="">
                                                            <?php if ($caneditRpts === true) { ?>
                                                                <textarea class="form-control rqrdFld" aria-label="..." id="rptsQuerySQL" name="rptsQuerySQL" style="width:100%;" cols="9" rows="30"><?php echo $row1[3]; ?></textarea>
                                                            <?php } else {
                                                                ?>
                                                                <span><?php echo $row1[3]; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="rptPreSQLTbPage" class="tab-pane fadein hideNotice" style="border:none !important;">
                                                    <div class="form-group form-group-sm" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="rptsPreSQL" class="control-label">Pre-Report / Process SQL Query:</label>
                                                        <div  class="">
                                                            <?php if ($caneditRpts === true) { ?>
                                                                <textarea class="form-control" aria-label="..." id="rptsPreSQL" name="rptsPreSQL" style="width:100%;" cols="9" rows="15"><?php echo $row1[20]; ?></textarea>
                                                            <?php } else {
                                                                ?>
                                                                <span><?php echo $row1[20]; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="rptPostSQLTbPage" class="tab-pane fadein hideNotice" style="border:none !important;">
                                                    <div class="form-group form-group-sm" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="rptsPostSQL" class="control-label">Post Report / Process SQL Query :</label>
                                                        <div  class="">
                                                            <?php if ($caneditRpts === true) { ?>
                                                                <textarea class="form-control" aria-label="..." id="rptsPostSQL" name="rptsPostSQL" style="width:100%;" cols="9" rows="15"><?php echo $row1[21]; ?></textarea>
                                                            <?php } else {
                                                                ?>
                                                                <span><?php echo $row1[21]; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="rptParamsTbPage" class="tab-pane fadein hideNotice" style="border:none !important;">
                                                    <?php
                                                    if ($caneditRpts === true) {
                                                        $nwRowHtml = "<tr id=\"rptsStpPrmsRow__WWW123WWW\">
                                    <td class=\"lovtd\"><span class=\"\">New</span></td>
                                                    <td class=\"lovtd\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;\">
                                                                <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"rptsStpPrmsRow_WWW123WWW_ParamNm\" value=\"\" style=\"width:100% !important;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"rptsStpPrmsRow_WWW123WWW_ParamID\" value=\"-1\">
                                                            </div>
						    </td>                                                
                                                    <td class=\"lovtd\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;\">
                                                                <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"rptsStpPrmsRow_WWW123WWW_SQLRep\" value=\"\" style=\"width:100% !important;\">
                                                            </div>
													</td>                                               
                                                    <td class=\"lovtd\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"rptsStpPrmsRow_WWW123WWW_DfltVal\" value=\"\" style=\"width:100% !important;\">
                                                            </div>                                                        
                                                    </td>                                               
                                                    <td class=\"lovtd\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                <div class=\"input-group\"  style=\"width:100%;\">
                                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"rptsStpPrmsRow_WWW123WWW_LovNm\" value=\"\" readonly=\"true\">
                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"rptsStpPrmsRow_WWW123WWW_LovID\" value=\"-1\">
                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'LOV Names', '', '', '', 'radio', true, '', '', 'rptsStpPrmsRow_WWW123WWW_LovNm', 'clear', 1, '');\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                    </label>
                                                                </div>
                                                            </div>                                                       
                                                    </td>                                                                                              
                                                    <td class=\"lovtd\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;\">
                                                                <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"rptsStpPrmsRow_WWW123WWW_DataTyp\">";
                                                        $valslctdArry = array("", "", "");
                                                        $srchInsArrys = array("TEXT", "NUMBER", "DATE");
                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                            $nwRowHtml .= "<option value=\"$srchInsArrys[$z]\" $valslctdArry[$z]>$srchInsArrys[$z]</option>";
                                                        }
                                                        $nwRowHtml .= "</select>
                                                            </div>                                                      
                                                    </td>                                                                                              
                                                    <td class=\"lovtd\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;\">
                                                                <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"rptsStpPrmsRow_WWW123WWW_DateFrmt\">";

                                                        $valslctdArry = array("", "", "", "", "");
                                                        $srchInsArrys = array("None", "yyyy-MM-dd", "yyyy-MM-dd HH:mm:ss", "dd-MMM-yyyy", "dd-MM-yyyy HH:mm:ss");

                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                            $nwRowHtml .= "<option value=\"$srchInsArrys[$z]\" $valslctdArry[$z]>$srchInsArrys[$z]</option>";
                                                        }
                                                        $nwRowHtml .= "</select>
                                                            </div>                                                        
                                                    </td>
                                                    <td class=\"lovtd\">
                                                        <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;\">
                                                                <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                    <label class=\"form-check-label\">
                                                                        <input type=\"checkbox\" class=\"form-check-input\" id=\"rptsStpPrmsRow_WWW123WWW_IsRqrd\" name=\"rptsStpPrmsRow_WWW123WWW_IsRqrd\">
                                                                    </label>
                                                                </div>
                                                            </div>                                                       
                                                    </td>
                                                    <td class=\"lovtd\">
                                                        <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;\">
                                                                <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                    <label class=\"form-check-label\">
                                                                        <input type=\"checkbox\" class=\"form-check-input\" id=\"rptsStpPrmsRow_WWW123WWW_IsDsplyd\" name=\"rptsStpPrmsRow_WWW123WWW_IsDsplyd\">
                                                                    </label>
                                                                </div>
                                                            </div>                                                       
                                                    </td>
                                                        <td class=\"lovtd\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delRptParam('rptsStpPrmsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Parameter\">
                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                            </button>
                                                        </td>
                                        </tr>";
                                                        $nwRowHtml = urlencode($nwRowHtml);
                                                        ?>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('rptsStpPrmsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Parameter">
                                                                    <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">New Parameter
                                                                </button> 
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="" style="float:right;">
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="exprtRptParams();" style="">
                                                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        Export Parameters
                                                                    </button>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="importRptParams();" style="">
                                                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        Import Parameters
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?> 
                                                    <div class="row"> 
                                                        <div  class="col-md-12">
                                                            <table class="table table-striped table-bordered table-responsive" id="rptsStpPrmsTable" cellspacing="0" width="100%" style="width:100%;min-width: 700px !important;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>No.</th>
                                                                        <th style="min-width:90px;">Prompt</th>
                                                                        <th>SQL Representation</th>
                                                                        <th style="min-width:90px;">Default Value</th>
                                                                        <th style="min-width:150px;">LOV Name</th>
                                                                        <th style="min-width:90px;">Data Type</th>
                                                                        <th>Date Format</th>
                                                                        <th>Required?</th>
                                                                        <th>Displayed?</th>
                                                                        <?php
                                                                        if ($caneditRpts === true) {
                                                                            ?>
                                                                            <th>&nbsp;</th>
                                                                        <?php } ?>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $cntr = 0;
                                                                    $curIdx = 0;
                                                                    $lmtSze = 0;
                                                                    $result2 = get_AllParams($sbmtdRptID);
                                                                    $prmID = -1;
                                                                    while ($row2 = loc_db_fetch_array($result2)) {
                                                                        $cntr += 1;
                                                                        if ($shdDplct == '0') {
                                                                            $prmID = $row2[0];
                                                                        } else {
                                                                            $prmID = -1;
                                                                        }
                                                                        ?>
                                                                        <tr id="rptsStpPrmsRow_<?php echo $cntr; ?>">                                    
                                                                            <td class="lovtd"><span class=""><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                            <td class="lovtd">
                                                                                <?php if ($caneditRpts === true) {
                                                                                    ?>
                                                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                                                        <input type="text" class="form-control rqrdFld" aria-label="..." id="rptsStpPrmsRow<?php echo $cntr; ?>_ParamNm" value="<?php echo $row2[1]; ?>" style="width:100% !important;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="rptsStpPrmsRow<?php echo $cntr; ?>_ParamID" value="<?php echo $prmID; ?>">
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    <span><?php echo $row2[1]; ?></span>
                                                                                <?php } ?>                                                         
                                                                            </td>                                                
                                                                            <td class="lovtd">
                                                                                <?php
                                                                                if ($caneditRpts === true) {
                                                                                    ?>
                                                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                                                        <input type="text" class="form-control rqrdFld" aria-label="..." id="rptsStpPrmsRow<?php echo $cntr; ?>_SQLRep" value="<?php echo $row2[2]; ?>" style="width:100% !important;">
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    <span><?php echo $row2[2]; ?></span>
                                                                                <?php } ?>                                                         
                                                                            </td>                                               
                                                                            <td class="lovtd">
                                                                                <?php
                                                                                if ($caneditRpts === true) {
                                                                                    ?>
                                                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                                                        <input type="text" class="form-control" aria-label="..." id="rptsStpPrmsRow<?php echo $cntr; ?>_DfltVal" value="<?php echo $row2[3]; ?>" style="width:100% !important;">
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    <span><?php echo $row2[3]; ?></span>
                                                                                <?php } ?>                                                         
                                                                            </td>                                               
                                                                            <td class="lovtd">
                                                                                <?php if ($caneditRpts === true) {
                                                                                    ?>
                                                                                    <div class="form-group form-group-sm" style="width:100% !important;">
                                                                                        <div class="input-group"  style="width:100%;">
                                                                                            <input type="text" class="form-control" aria-label="..." id="rptsStpPrmsRow<?php echo $cntr; ?>_LovNm" value="<?php echo $row2[6]; ?>" readonly="true">
                                                                                            <input type="hidden" class="form-control" aria-label="..." id="rptsStpPrmsRow<?php echo $cntr; ?>_LovID" value="<?php echo $row2[5]; ?>">
                                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'LOV Names', '', '', '', 'radio', true, '<?php echo $row2[6]; ?>', '', 'rptsStpPrmsRow<?php echo $cntr; ?>_LovNm', 'clear', 1, '');">
                                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    <span><?php echo $row2[3]; ?></span>
                                                                                <?php } ?>                                                         
                                                                            </td>                                                                                              
                                                                            <td class="lovtd">
                                                                                <?php if ($caneditRpts === true) {
                                                                                    ?>
                                                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="rptsStpPrmsRow<?php echo $cntr; ?>_DataTyp">
                                                                                            <?php
                                                                                            $valslctdArry = array("", "", "");
                                                                                            $srchInsArrys = array("TEXT", "NUMBER", "DATE");

                                                                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                                if ($row2[7] == $srchInsArrys[$z]) {
                                                                                                    $valslctdArry[$z] = "selected";
                                                                                                }
                                                                                                ?>
                                                                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    <span><?php echo $row2[7]; ?></span>
                                                                                <?php } ?>                                                         
                                                                            </td>                                                                                              
                                                                            <td class="lovtd">
                                                                                <?php if ($caneditRpts === true) {
                                                                                    ?>
                                                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="rptsStpPrmsRow<?php echo $cntr; ?>_DateFrmt">
                                                                                            <?php
                                                                                            $valslctdArry = array("", "", "", "", "");
                                                                                            $srchInsArrys = array("None", "yyyy-MM-dd", "yyyy-MM-dd HH:mm:ss",
                                                                                                "dd-MMM-yyyy", "dd-MM-yyyy HH:mm:ss");

                                                                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                                if ($row2[8] == $srchInsArrys[$z]) {
                                                                                                    $valslctdArry[$z] = "selected";
                                                                                                }
                                                                                                ?>
                                                                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    <span><?php echo $row2[8]; ?></span>
                                                                                <?php } ?>                                                         
                                                                            </td>
                                                                            <td class="lovtd" style="text-align:center;">
                                                                                <?php
                                                                                $isChkd = "";
                                                                                if ($row2[4] == "1") {
                                                                                    $isChkd = "checked=\"true\"";
                                                                                }
                                                                                if ($caneditRpts === true) {
                                                                                    ?>
                                                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                                                        <div class="form-check" style="font-size: 12px !important;">
                                                                                            <label class="form-check-label">
                                                                                                <input type="checkbox" class="form-check-input" id="rptsStpPrmsRow<?php echo $cntr; ?>_IsRqrd" name="rptsStpPrmsRow<?php echo $cntr; ?>_IsRqrd" <?php echo $isChkd ?>>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    <span><?php echo ($row2[4] == "1" ? "Yes" : "No"); ?></span>
                                                                                <?php } ?>                                                         
                                                                            </td>
                                                                            <td class="lovtd" style="text-align:center;">
                                                                                <?php
                                                                                $isChkd = "";
                                                                                if ($row2[9] == "1") {
                                                                                    $isChkd = "checked=\"true\"";
                                                                                }
                                                                                if ($caneditRpts === true) {
                                                                                    ?>
                                                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                                                        <div class="form-check" style="font-size: 12px !important;">
                                                                                            <label class="form-check-label">
                                                                                                <input type="checkbox" class="form-check-input" id="rptsStpPrmsRow<?php echo $cntr; ?>_IsDsplyd" name="rptsStpPrmsRow<?php echo $cntr; ?>_IsDsplyd" <?php echo $isChkd ?>>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    <span><?php echo ($row2[9] == "1" ? "Yes" : "No"); ?></span>
                                                                                <?php } ?>                                                         
                                                                            </td>
                                                                            <?php
                                                                            if ($caneditRpts === true) {
                                                                                ?>
                                                                                <td class="lovtd">
                                                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delRptParam('rptsStpPrmsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Parameter">
                                                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
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
                                                <div id="rptSubPrgmsTbPage" class="tab-pane fadein hideNotice" style="border:none !important;">  
                                                    <?php
                                                    if ($caneditRpts === true) {
                                                        $nwRowHtml = "<tr id=\"rptsStpPrgmsRow__WWW123WWW\">                                    
                                                                            <td class=\"lovtd\"><span>New</span></td>
                                                                            <td class=\"lovtd\">
                                                                                <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                                    <div class=\"input-group\"  style=\"width:100%;\">
                                                                                        <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"rptsStpPrgmsRow_WWW123WWW_PrgmNm\" value=\"\" readonly=\"true\">
                                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"rptsStpPrgmsRow_WWW123WWW_PrgmID\" value=\"-1\">
                                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"rptsStpPrgmsRow_WWW123WWW_PkeyID\" value=\"-1\">
                                                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Reports and Processes', '', '', '', 'radio', true, '', 'rptsStpPrgmsRow_WWW123WWW_PrgmID', 'rptsStpPrgmsRow_WWW123WWW_PrgmNm', 'clear', 0, '');\">
                                                                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>                                                    
                                                                            </td> 
                                                                            <td class=\"lovtd\">
                                                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delRptPrgm('rptsStpPrgmsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Program\">
                                                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                                </button>
                                                                            </td>
                                                                        </tr>";
                                                        $nwRowHtml = urlencode($nwRowHtml);
                                                        ?>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;margin-top:5px;" onclick="insertNewRowBe4('rptsStpPrgmsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Report/Program">
                                                                    <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">New Report/Program
                                                                </button> 
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?> 
                                                    <div class="row"> 
                                                        <div  class="col-md-12">
                                                            <table class="table table-striped table-bordered table-responsive" id="rptsStpPrgmsTable" cellspacing="0" width="100%" style="width:100%;min-width: 700px !important;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>No.</th>
                                                                        <th style="min-width:200px;">Process/Report Name</th>
                                                                        <?php
                                                                        if ($caneditRpts === true) {
                                                                            ?>
                                                                            <th>&nbsp;</th>
                                                                        <?php } ?>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $cntr = 0;
                                                                    $result3 = get_AllRptPrgms($sbmtdRptID);
                                                                    $setPrgmID = -1;
                                                                    while ($row3 = loc_db_fetch_array($result3)) {
                                                                        $cntr += 1;
                                                                        if ($shdDplct == '0') {
                                                                            $setPrgmID = $row3[0];
                                                                        } else {
                                                                            $setPrgmID = -1;
                                                                        }
                                                                        ?>
                                                                        <tr id="rptsStpPrgmsRow_<?php echo $cntr; ?>">                                    
                                                                            <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                            <td class="lovtd">
                                                                                <span><?php echo $row3[2]; ?></span>
                                                                                <input type="hidden" class="form-control rqrdFld" aria-label="..." id="rptsStpPrgmsRow<?php echo $cntr; ?>_PrgmNm" value="<?php echo $row3[2]; ?>" readonly="true">
                                                                                <input type="hidden" class="form-control" aria-label="..." id="rptsStpPrgmsRow<?php echo $cntr; ?>_PrgmID" value="<?php echo $row3[1]; ?>">
                                                                                <input type="hidden" class="form-control" aria-label="..." id="rptsStpPrgmsRow<?php echo $cntr; ?>_PkeyID" value="<?php echo $setPrgmID; ?>">
                                                                            </td>   
                                                                            <?php
                                                                            if ($caneditRpts === true) {
                                                                                ?>
                                                                                <td class="lovtd">
                                                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delRptPrgm('rptsStpPrgmsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Program">
                                                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
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
                                                <div id="rptAlwdRolesTbPage" class="tab-pane fadein hideNotice" style="border:none !important;">  
                                                    <?php
                                                    if ($caneditRpts === true) {
                                                        $nwRowHtml = "<tr id=\"rptsStpRolesRow__WWW123WWW\">
                                    <td class=\"lovtd\"><span class=\"\">New</span></td>                                                                                                  
                                                    <td class=\"lovtd\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                <div class=\"input-group\"  style=\"width:100%;\">
                                                                    <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"rptsStpRolesRow_WWW123WWW_RoleNm\" value=\"\" readonly=\"true\">
                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"rptsStpRolesRow_WWW123WWW_RoleID\" value=\"-1\">
                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"rptsStpRolesRow_WWW123WWW_RptRoleID\" value=\"-1\">
                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'User Roles', '', '', '', 'radio', true, '', 'rptsStpRolesRow_WWW123WWW_RoleID', 'rptsStpRolesRow_WWW123WWW_RoleNm', 'clear', 0, '');\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                    </label>
                                                                </div>
                                                            </div>                                                       
                                                    </td> 
                                                        <td class=\"lovtd\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delRptRole('rptsStpRolesRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Role\">
                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                            </button>
                                                        </td>
                                        </tr>";
                                                        $nwRowHtml = urlencode($nwRowHtml);
                                                        ?>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;margin-top:5px;" onclick="insertNewRowBe4('rptsStpRolesTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Role">
                                                                    <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">New Role
                                                                </button> 
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?> 
                                                    <div class="row"> 
                                                        <div  class="col-md-12">
                                                            <table class="table table-striped table-bordered table-responsive" id="rptsStpRolesTable" cellspacing="0" width="100%" style="width:100%;min-width: 700px !important;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>No.</th>
                                                                        <th style="min-width:200px;">Role Set Name</th>
                                                                        <?php
                                                                        if ($caneditRpts === true) {
                                                                            ?>
                                                                            <th>&nbsp;</th>
                                                                        <?php } ?>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $cntr = 0;
                                                                    $result3 = get_AllRptRoles($sbmtdRptID);
                                                                    $rptRlID = -1;
                                                                    while ($row3 = loc_db_fetch_array($result3)) {
                                                                        $cntr += 1;
                                                                        if ($shdDplct == '0') {
                                                                            $rptRlID = $row3[2];
                                                                        } else {
                                                                            $rptRlID = -1;
                                                                        }
                                                                        ?>
                                                                        <tr id="rptsStpRolesRow_<?php echo $cntr; ?>">                                    
                                                                            <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                            <td class="lovtd">
                                                                                <span><?php echo $row3[1]; ?></span>
                                                                                <input type="hidden" class="form-control rqrdFld" aria-label="..." id="rptsStpRolesRow<?php echo $cntr; ?>_RoleNm" value="<?php echo $row3[1]; ?>" readonly="true">
                                                                                <input type="hidden" class="form-control" aria-label="..." id="rptsStpRolesRow<?php echo $cntr; ?>_RoleID" value="<?php echo $row3[0]; ?>">  
                                                                                <input type="hidden" class="form-control" aria-label="..." id="rptsStpRolesRow<?php echo $cntr; ?>_RptRoleID" value="<?php echo $rptRlID; ?>">                                                   
                                                                            </td>   
                                                                            <?php
                                                                            if ($caneditRpts === true) {
                                                                                ?>
                                                                                <td class="lovtd">
                                                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delRptRole('rptsStpRolesRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Role">
                                                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <?php
                    }
                }
            } else if ($vwtyp == 9) {
                //echo "Add New Reports"
                $sbmtdRptID = -1;
                if ($caneditRpts === true) {
                    ?>
                    <form id="rptsStpForm">
                        <div class="row">                                 
                            <div class="col-md-12">
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveReportsForm();" style="width:100% !important;">
                                    <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Save Report
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;"> 
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="rptsRptNm" class="control-label col-lg-4">Process Name:</label>
                                    <div  class="col-lg-8">
                                        <input type="text" class="form-control rqrdFld" aria-label="..." id="rptsRptNm" name="rptsRptNm" value="" style="width:100%;">
                                        <input type="hidden" class="form-control" aria-label="..." id="rptsRptID" name="rptsRptID" value="-1" style="width:100%;">
                                        <input type="hidden" class="form-control" aria-label="..." id="isNew123" name="isNew123" value="5">                                    
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="rptsRptDesc" class="control-label col-lg-4">Description:</label>
                                    <div  class="col-lg-8">
                                        <textarea class="form-control" aria-label="..." id="rptsRptDesc" name="rptsRptDesc" style="width:100%;" cols="4" rows="5"></textarea>
                                    </div>
                                </div> 
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="rptsOwnrMdl" class="control-label col-lg-4">Owner Module:</label>
                                    <div  class="col-lg-8">                                    
                                        <div class="input-group">
                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="rptsOwnrMdl" value="">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'System Modules', '', '', '', 'radio', true, '', '', 'rptsOwnrMdl', 'clear', 1, '');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="rptsProcessTyp" class="control-label col-lg-4">Process Type:</label>
                                    <div class="col-lg-8">
                                        <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="rptsProcessTyp" name="rptsProcessTyp">
                                            <?php
                                            $valslctdArry = array("", "", "", "", "", "", "");
                                            $srchInsArrys = array("SQL Report", "Database Function", "Command Line Script-Windows", "Command Line Script-Linux",
                                                "Posting of GL Trns. Batches",
                                                "Journal Import", "Import/Overwrite Data from Excel");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="rptsPrcsRnnr" class="control-label col-lg-4">Process Runner:</label>
                                    <div  class="col-lg-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="rptsPrcsRnnr" value="">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Background Process Runners', '', '', '', 'radio', true, '', '', 'rptsPrcsRnnr', 'clear', 1, '');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="rptsColsToGrp" class="control-label col-lg-6">Cols Nos To Group or Width & Height (px) for Charts:</label>
                                    <div  class="col-lg-6">
                                        <input type="text" class="form-control" aria-label="..." id="rptsColsToGrp" name="rptsColsToGrp" value="" style="width:100%;">                                        
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="rptsColsToCnt" class="control-label col-lg-6">Cols Nos To Count or Use in Charts:</label>
                                    <div  class="col-lg-6">
                                        <input type="text" class="form-control" aria-label="..." id="rptsColsToCnt" name="rptsColsToCnt" value="" style="width:100%;">                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="rptsColsToFrmt" class="control-label col-lg-6">Cols Nos To Format Numerically:</label>
                                    <div  class="col-lg-6">
                                        <input type="text" class="form-control" aria-label="..." id="rptsColsToFrmt" name="rptsColsToFrmt" value="" style="width:100%;">
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="rptsColsToSum" class="control-label col-lg-6">Cols Nos To Sum:</label>
                                    <div  class="col-lg-6">
                                        <input type="text" class="form-control" aria-label="..." id="rptsColsToSum" name="rptsColsToSum" value="" style="width:100%;">
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="rptsColsToAvrg" class="control-label col-lg-6">Cols Nos To Average:</label>
                                    <div  class="col-lg-6">
                                        <input type="text" class="form-control" aria-label="..." id="rptsColsToAvrg" name="rptsColsToAvrg" value="" style="width:100%;">
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="rptsOutputType" class="control-label col-lg-6">Output Type:</label>
                                    <div class="col-lg-6">
                                        <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="rptsOutputType" name="rptsOutputType">
                                            <?php
                                            $valslctdArry = array("", "", "", "", "", "", "", "", "", "");
                                            $srchInsArrys = array("None", "HTML", "STANDARD", "MICROSOFT EXCEL", "PDF",
                                                "MICROSOFT WORD", "CHARACTER SEPARATED FILE (CSV)", "COLUMN CHART", "PIE CHART", "LINE CHART");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="rptsOrntn" class="control-label col-lg-6">Orientation:</label>
                                    <div class="col-lg-6">
                                        <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="rptsOrntn" name="rptsOrntn">
                                            <?php
                                            $valslctdArry = array("", "", "");
                                            $srchInsArrys = array("None", "Portrait", "Landscape");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="rptsLayout" class="control-label col-lg-6">Layout:</label>
                                    <div class="col-lg-6">
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="rptsLayout" name="rptsLayout">
                                            <?php
                                            $valslctdArry = array("", "", "");
                                            $srchInsArrys = array("None", "TABULAR", "DETAIL");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div> 
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="rptsDelimiter" class="control-label col-lg-6">Delimiter:</label>
                                    <div class="col-lg-6">
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="rptsDelimiter" name="rptsDelimiter">
                                            <?php
                                            $valslctdArry = array("", "", "", "", "", "");
                                            $srchInsArrys = array("None", "Comma (,)", "Semi-Colon(;)", "Pipe(|)", "Tab", "Tilde(~)");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>                                
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="rptsDetImgCols" class="control-label col-lg-6">Detailed Report Images Cols:</label>
                                    <div  class="col-lg-6">
                                        <input type="text" class="form-control" aria-label="..." id="rptsDetImgCols" name="rptsDetImgCols" value="" style="width:100%;">
                                    </div>
                                </div>   
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="rptsJrxmlFile" class="control-label col-lg-2">Jrxml File:</label>
                                    <div  class="col-lg-10">
                                        <div class="input-group">
                                            <label class="btn btn-primary btn-file input-group-addon">
                                                Browse... <input type="file" id="rptsJrxmlFile" name="rptsJrxmlFile" onchange="setFileLoc(this, '#rptsJrxmlFileSrc');" class="btn btn-default"  style="display: none;" accept=".jrxml">
                                            </label>
                                            <input type="text" class="form-control" aria-label="..." id="rptsJrxmlFileSrc" value="">                                                        
                                        </div> 
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="rptsIsEnabled" class="control-label col-lg-7">Enabled?:</label>
                                    <div  class="col-lg-5">
                                        <?php
                                        $chkdYes = "";
                                        $chkdNo = "checked=\"\"";
                                        ?>
                                        <label class="radio-inline"><input type="radio" name="rptsIsEnabled" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                        <label class="radio-inline"><input type="radio" name="rptsIsEnabled" value="NO" <?php echo $chkdNo; ?>>NO</label>                                       
                                    </div>
                                </div>   
                            </div>
                        </div>
                        <div class="row"  style="padding:1px 15px 1px 15px !important;"><hr style="margin:1px 0px 10px 0px;"></div>                    
                        <div class="row"  style="padding:1px 15px 1px 15px !important;">
                            <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-5px !important;">
                                <li class="active"><a data-toggle="tabajxrptdet" data-rhodata="" href="#rptMainSQLTbPage" id="rptMainSQLTbPagetab">Main Query</a></li>
                                <li><a data-toggle="tabajxrptdet" data-rhodata="" href="#rptPreSQLTbPage" id="rptPreSQLTbPagetab">Pre-Report Query</a></li>
                                <li><a data-toggle="tabajxrptdet" data-rhodata="" href="#rptPostSQLTbPage" id="rptPostSQLTbPagetab">Post-Report Query</a></li>
                                <li><a data-toggle="tabajxrptdet" data-rhodata="" href="#rptParamsTbPage" id="rptParamsTbPagetab">Parameters</a></li>
                                <li><a data-toggle="tabajxrptdet" data-rhodata="" href="#rptSubPrgmsTbPage" id="rptSubPrgmsTbPagetab">Sub-Program Units</a></li>
                                <li><a data-toggle="tabajxrptdet" data-rhodata="" href="#rptAlwdRolesTbPage" id="rptAlwdRolesTbPagetab">Allowed Roles</a></li>
                            </ul>                                    
                            <div class="row">                  
                                <div class="col-md-12">
                                    <div class="custDiv"> 
                                        <div class="tab-content">
                                            <div id="rptMainSQLTbPage" class="tab-pane fadein active" style="border:none !important;">
                                                <div class="form-group form-group-sm" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="rptsQuerySQL" class="control-label">SQL Query for Report / Process:</label>
                                                    <div  class="">
                                                        <textarea class="form-control rqrdFld" aria-label="..." id="rptsQuerySQL" name="rptsQuerySQL" style="width:100%;" cols="9" rows="30"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="rptPreSQLTbPage" class="tab-pane fadein hideNotice" style="border:none !important;">
                                                <div class="form-group form-group-sm" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="rptsPreSQL" class="control-label">Pre-Report / Process SQL Query:</label>
                                                    <div  class="">
                                                        <textarea class="form-control" aria-label="..." id="rptsPreSQL" name="rptsPreSQL" style="width:100%;" cols="9" rows="15"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="rptPostSQLTbPage" class="tab-pane fadein hideNotice" style="border:none !important;">
                                                <div class="form-group form-group-sm" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="rptsPostSQL" class="control-label">Post Report / Process SQL Query :</label>
                                                    <div  class="">
                                                        <textarea class="form-control" aria-label="..." id="rptsPostSQL" name="rptsPostSQL" style="width:100%;" cols="9" rows="15"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="rptParamsTbPage" class="tab-pane fadein hideNotice" style="border:none !important;">
                                                <?php
                                                $nwRowHtml = "<tr id=\"rptsStpPrmsRow__WWW123WWW\">
                                    <td class=\"lovtd\"><span class=\"\">New</span></td>
                                                    <td class=\"lovtd\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;\">
                                                                <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"rptsStpPrmsRow_WWW123WWW_ParamNm\" value=\"\" style=\"width:100% !important;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"rptsStpPrmsRow_WWW123WWW_ParamID\" value=\"-1\">
                                                            </div>
						    </td>                                                
                                                    <td class=\"lovtd\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;\">
                                                                <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"rptsStpPrmsRow_WWW123WWW_SQLRep\" value=\"\" style=\"width:100% !important;\">
                                                            </div>
													</td>                                               
                                                    <td class=\"lovtd\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"rptsStpPrmsRow_WWW123WWW_DfltVal\" value=\"\" style=\"width:100% !important;\">
                                                            </div>                                                        
                                                    </td>                                               
                                                    <td class=\"lovtd\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                <div class=\"input-group\"  style=\"width:100%;\">
                                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"rptsStpPrmsRow_WWW123WWW_LovNm\" value=\"\" readonly=\"true\">
                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"rptsStpPrmsRow_WWW123WWW_LovID\" value=\"-1\">
                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'LOV Names', '', '', '', 'radio', true, '', '', 'rptsStpPrmsRow_WWW123WWW_LovNm', 'clear', 1, '');\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                    </label>
                                                                </div>
                                                            </div>                                                       
                                                    </td>                                                                                              
                                                    <td class=\"lovtd\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;\">
                                                                <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"rptsStpPrmsRow_WWW123WWW_DataTyp\">";
                                                $valslctdArry = array("", "", "");
                                                $srchInsArrys = array("TEXT", "NUMBER", "DATE");
                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    $nwRowHtml .= "<option value=\"$srchInsArrys[$z]\" $valslctdArry[$z]>$srchInsArrys[$z]</option>";
                                                }
                                                $nwRowHtml .= "</select>
                                                            </div>                                                      
                                                    </td>                                                                                              
                                                    <td class=\"lovtd\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;\">
                                                                <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"rptsStpPrmsRow_WWW123WWW_DateFrmt\">";

                                                $valslctdArry = array("", "", "", "", "");
                                                $srchInsArrys = array("None", "yyyy-MM-dd", "yyyy-MM-dd HH:mm:ss", "dd-MMM-yyyy", "dd-MM-yyyy HH:mm:ss");

                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    $nwRowHtml .= "<option value=\"$srchInsArrys[$z]\" $valslctdArry[$z]>$srchInsArrys[$z]</option>";
                                                }
                                                $nwRowHtml .= "</select>
                                                            </div>                                                        
                                                    </td>
                                                    <td class=\"lovtd\">
                                                        <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;\">
                                                                <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                    <label class=\"form-check-label\">
                                                                        <input type=\"checkbox\" class=\"form-check-input\" id=\"rptsStpPrmsRow_WWW123WWW_IsRqrd\" name=\"rptsStpPrmsRow_WWW123WWW_IsRqrd\">
                                                                    </label>
                                                                </div>
                                                            </div>                                                       
                                                    </td>
                                                    <td class=\"lovtd\">
                                                        <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;\">
                                                                <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                    <label class=\"form-check-label\">
                                                                        <input type=\"checkbox\" class=\"form-check-input\" id=\"rptsStpPrmsRow_WWW123WWW_IsDsplyd\" name=\"rptsStpPrmsRow_WWW123WWW_IsDsplyd\">
                                                                    </label>
                                                                </div>
                                                            </div>                                                       
                                                    </td>
                                                        <td class=\"lovtd\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delRptParam('rptsStpPrmsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Parameter\">
                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                            </button>
                                                        </td>
                                        </tr>";
                                                $nwRowHtml = urlencode($nwRowHtml);
                                                ?>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('rptsStpPrmsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Parameter">
                                                            <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">New Parameter
                                                        </button> 
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="" style="float:right;">
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="exprtRptParams();" style="">
                                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Export Parameters
                                                            </button>
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="importRptParams();" style="">
                                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Import Parameters
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row"> 
                                                    <div  class="col-md-12">
                                                        <table class="table table-striped table-bordered table-responsive" id="rptsStpPrmsTable" cellspacing="0" width="100%" style="width:100%;min-width: 700px !important;">
                                                            <thead>
                                                                <tr>
                                                                    <th>No.</th>
                                                                    <th style="min-width:90px;">Prompt</th>
                                                                    <th>SQL Representation</th>
                                                                    <th style="min-width:90px;">Default Value</th>
                                                                    <th style="min-width:150px;">LOV Name</th>
                                                                    <th style="min-width:90px;">Data Type</th>
                                                                    <th>Date Format</th>
                                                                    <th>Required?</th>
                                                                    <th>Displayed?</th>
                                                                    <th>&nbsp;</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $cntr = 0;
                                                                $curIdx = 0;
                                                                $lmtSze = 0;
                                                                while ($cntr < 1) {
                                                                    $cntr += 1;
                                                                    ?>
                                                                    <tr id="rptsStpPrmsRow_<?php echo $cntr; ?>">                                    
                                                                        <td class="lovtd"><span class=""><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                        <td class="lovtd">
                                                                            <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="rptsStpPrmsRow<?php echo $cntr; ?>_ParamNm" value="" style="width:100% !important;">
                                                                                <input type="hidden" class="form-control" aria-label="..." id="rptsStpPrmsRow<?php echo $cntr; ?>_ParamID" value="-1">
                                                                            </div>                                                       
                                                                        </td>                                                
                                                                        <td class="lovtd">
                                                                            <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="rptsStpPrmsRow<?php echo $cntr; ?>_SQLRep" value="-1" style="width:100% !important;">
                                                                            </div>                                                       
                                                                        </td>                                               
                                                                        <td class="lovtd">
                                                                            <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                                                <input type="text" class="form-control" aria-label="..." id="rptsStpPrmsRow<?php echo $cntr; ?>_DfltVal" value="" style="width:100% !important;">
                                                                            </div>                                                       
                                                                        </td>                                               
                                                                        <td class="lovtd">
                                                                            <div class="form-group form-group-sm" style="width:100% !important;">
                                                                                <div class="input-group"  style="width:100%;">
                                                                                    <input type="text" class="form-control" aria-label="..." id="rptsStpPrmsRow<?php echo $cntr; ?>_LovNm" value="" readonly="true">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="rptsStpPrmsRow<?php echo $cntr; ?>_LovID" value="-1">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'LOV Names', '', '', '', 'radio', true, '', '', 'rptsStpPrmsRow<?php echo $cntr; ?>_LovNm', 'clear', 1, '');">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>                                                       
                                                                        </td>                                                                                              
                                                                        <td class="lovtd">
                                                                            <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                                                <select data-placeholder="Select..." class="form-control chosen-select" id="rptsStpPrmsRow<?php echo $cntr; ?>_DataTyp">
                                                                                    <?php
                                                                                    $valslctdArry = array("", "", "");
                                                                                    $srchInsArrys = array("TEXT", "NUMBER", "DATE");

                                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                        ?>
                                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>                                                        
                                                                        </td>                                                                                              
                                                                        <td class="lovtd">
                                                                            <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                                                <select data-placeholder="Select..." class="form-control chosen-select" id="rptsStpPrmsRow<?php echo $cntr; ?>_DateFrmt">
                                                                                    <?php
                                                                                    $valslctdArry = array("", "", "", "", "");
                                                                                    $srchInsArrys = array("None", "yyyy-MM-dd", "yyyy-MM-dd HH:mm:ss",
                                                                                        "dd-MMM-yyyy", "dd-MM-yyyy HH:mm:ss");

                                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                        ?>
                                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>                                                        
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <?php
                                                                            $isChkd = "";
                                                                            ?>
                                                                            <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                                    <label class="form-check-label">
                                                                                        <input type="checkbox" class="form-check-input" id="rptsStpPrmsRow<?php echo $cntr; ?>_IsRqrd" name="rptsStpPrmsRow<?php echo $cntr; ?>_IsRqrd" <?php echo $isChkd ?>>
                                                                                    </label>
                                                                                </div>
                                                                            </div>                                                       
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <?php
                                                                            $isChkd = "checked=\"true\"";
                                                                            ?>
                                                                            <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                                    <label class="form-check-label">
                                                                                        <input type="checkbox" class="form-check-input" id="rptsStpPrmsRow<?php echo $cntr; ?>_IsDsplyd" name="rptsStpPrmsRow<?php echo $cntr; ?>_IsDsplyd" <?php echo $isChkd ?>>
                                                                                    </label>
                                                                                </div>
                                                                            </div>                                                       
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delRptParam('rptsStpPrmsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Parameter">
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
                                            </div>
                                            <div id="rptSubPrgmsTbPage" class="tab-pane fadein hideNotice" style="border:none !important;">  
                                                <?php
                                                $nwRowHtml = "<tr id=\"rptsStpPrgmsRow__WWW123WWW\">                                    
                                                                            <td class=\"lovtd\"><span>New</span></td>
                                                                            <td class=\"lovtd\">
                                                                                <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                                    <div class=\"input-group\"  style=\"width:100%;\">
                                                                                        <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"rptsStpPrgmsRow_WWW123WWW_PrgmNm\" value=\"\" readonly=\"true\">
                                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"rptsStpPrgmsRow_WWW123WWW_PrgmID\" value=\"-1\">
                                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"rptsStpPrgmsRow_WWW123WWW_PkeyID\" value=\"-1\">
                                                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Reports and Processes', '', '', '', 'radio', true, '', 'rptsStpPrgmsRow_WWW123WWW_PrgmID', 'rptsStpPrgmsRow_WWW123WWW_PrgmNm', 'clear', 0, '');\">
                                                                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>                                                    
                                                                            </td> 
                                                                            <td class=\"lovtd\">
                                                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delRptPrgm('rptsStpPrgmsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Program\">
                                                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                                </button>
                                                                            </td>
                                                                        </tr>";
                                                $nwRowHtml = urlencode($nwRowHtml);
                                                ?>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;margin-top:5px;" onclick="insertNewRowBe4('rptsStpPrgmsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Report/Program">
                                                            <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">New Report/Program
                                                        </button> 
                                                    </div>
                                                </div>
                                                <div class="row"> 
                                                    <div  class="col-md-12">
                                                        <table class="table table-striped table-bordered table-responsive" id="rptsStpPrgmsTable" cellspacing="0" width="100%" style="width:100%;min-width: 700px !important;">
                                                            <thead>
                                                                <tr>
                                                                    <th>No.</th>
                                                                    <th style="min-width:200px;">Process/Report Name</th>
                                                                    <th>&nbsp;</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $cntr = 0;
                                                                while ($cntr < 1) {
                                                                    $cntr += 1;
                                                                    ?>
                                                                    <tr id="rptsStpPrgmsRow_<?php echo $cntr; ?>">                                    
                                                                        <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                        <td class="lovtd">
                                                                            <div class="form-group form-group-sm" style="width:100% !important;">
                                                                                <div class="input-group"  style="width:100%;">
                                                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="rptsStpPrgmsRow<?php echo $cntr; ?>_PrgmNm" value="" readonly="true">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="rptsStpPrgmsRow<?php echo $cntr; ?>_PrgmID" value="-1">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="rptsStpPrgmsRow<?php echo $cntr; ?>_PkeyID" value="-1">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'User Roles', '', '', '', 'radio', true, '', 'rptsStpPrgmsRow<?php echo $cntr; ?>_PrgmID', 'rptsStpPrgmsRow<?php echo $cntr; ?>_PrgmNm', 'clear', 0, '');">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delRptPrgm('rptsStpPrgmsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Program">
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
                                            </div>
                                            <div id="rptAlwdRolesTbPage" class="tab-pane fadein hideNotice" style="border:none !important;">  
                                                <?php
                                                $nwRowHtml = "<tr id=\"rptsStpRolesRow__WWW123WWW\">
                                    <td class=\"lovtd\"><span class=\"\">New</span></td>                                                                                                  
                                                    <td class=\"lovtd\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                <div class=\"input-group\"  style=\"width:100%;\">
                                                                    <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"rptsStpRolesRow_WWW123WWW_RoleNm\" value=\"\" readonly=\"true\">
                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"rptsStpRolesRow_WWW123WWW_RoleID\" value=\"-1\">
                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"rptsStpRolesRow_WWW123WWW_RptRoleID\" value=\"-1\">
                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'User Roles', '', '', '', 'radio', true, '', 'rptsStpRolesRow_WWW123WWW_RoleID', 'rptsStpRolesRow_WWW123WWW_RoleNm', 'clear', 0, '');\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                    </label>
                                                                </div>
                                                            </div>                                                       
                                                    </td> 
                                                        <td class=\"lovtd\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delRptRole('rptsStpRolesRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Role\">
                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                            </button>
                                                        </td>
                                        </tr>";
                                                $nwRowHtml = urlencode($nwRowHtml);
                                                ?>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;margin-top:5px;" onclick="insertNewRowBe4('rptsStpRolesTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Role">
                                                            <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">New Role
                                                        </button> 
                                                    </div>
                                                </div>
                                                <div class="row"> 
                                                    <div  class="col-md-12">
                                                        <table class="table table-striped table-bordered table-responsive" id="rptsStpRolesTable" cellspacing="0" width="100%" style="width:100%;min-width: 700px !important;">
                                                            <thead>
                                                                <tr>
                                                                    <th>No.</th>
                                                                    <th style="min-width:200px;">Role Set Name</th>
                                                                    <th>&nbsp;</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $cntr = 0;
                                                                while ($cntr < 1) {
                                                                    $cntr += 1;
                                                                    ?>
                                                                    <tr id="rptsStpRolesRow_<?php echo $cntr; ?>">                                    
                                                                        <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                        <td class="lovtd">
                                                                            <div class="form-group form-group-sm" style="width:100% !important;">
                                                                                <div class="input-group"  style="width:100%;">
                                                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="rptsStpRolesRow<?php echo $cntr; ?>_RoleNm" value="" readonly="true">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="rptsStpRolesRow<?php echo $cntr; ?>_RoleID" value="-1">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="rptsStpRolesRow<?php echo $cntr; ?>_RptRoleID" value="-1">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'User Roles', '', '', '', 'radio', true, '', 'rptsStpRolesRow<?php echo $cntr; ?>_RoleID', 'rptsStpRolesRow<?php echo $cntr; ?>_RoleNm', 'clear', 0, '');">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>  
                                                                        </td> 
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delRptRole('rptsStpRolesRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Role">
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php
                } else {
                    echo restricted();
                }
            } else if ($vwtyp == 50) {
                $pkID = isset($_POST['sbmtdRptID']) ? $_POST['sbmtdRptID'] : -1;
                $sbmtdAlrtID = -1;
                if ($pkID > 0) {
                    $sbmtdRptID = $pkID;
                    $result1 = get_RptsDet($pkID);
                    while ($row1 = loc_db_fetch_array($result1)) {
                        ?>
                        <form id="mdlRptRnsForm">
                            <div  class="rho-container-fluid1">
                                <div class="row">
                                    <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                        <fieldset class="basic_person_fs" style="padding-top:10px !important;"> 
                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                <label for="rptsRptNm" class="control-label col-lg-4">Report Name:</label>
                                                <div  class="col-lg-8">
                                                    <span><?php echo $row1[1]; ?></span>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                        <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                <label for="rptsRptDesc" class="control-label col-lg-4">Description:</label>
                                                <div  class="col-lg-8">
                                                    <span><?php echo $row1[2]; ?></span>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php
                                    $colClassType1 = "col-lg-2";
                                    $colClassType2 = "col-lg-3";
                                    $colClassType3 = "col-lg-5";
                                    ?>
                                    <div class="<?php echo $colClassType1; ?>" style="padding:0px 3px 0px 3px !important;">     
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneRptsParamsForm(<?php echo $row1[0]; ?>, -1, '<?php echo urlencode($row1[1]); ?>', 2, -1, 'ShowDialog');" data-toggle="tooltip" data-placement="bottom" title="Run Selected Report/Process">
                                            <img src="cmn_images/98.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                        </button>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getAllSchdls('', '', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=3&vtyp=0', 1);" data-toggle="tooltip" data-placement="bottom" title="Schedule Selected Report/Process">
                                            <img src="cmn_images/Calander.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                        </button>
                                    </div>
                                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                                        <div class="input-group">
                                            <input class="form-control" id="mdlRptRnsSrchFor" type = "text" placeholder="Search For" value="<?php
                                            echo trim(str_replace("%", " ", $srchFor));
                                            ?>" onkeyup="enterKeyFuncMdlRptRns(event, '', 'ReloadDialog', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=50&sbmtdRptID=<?php echo $sbmtdRptID; ?>');">
                                            <input id="mdlRptRnsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getMyMdlRptRuns('clear', 'ReloadDialog', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=50&sbmtdRptID=<?php echo $sbmtdRptID; ?>');">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </label>
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getMyMdlRptRuns('', 'ReloadDialog', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=50&sbmtdRptID=<?php echo $sbmtdRptID; ?>');">
                                                <span class="glyphicon glyphicon-search"></span>
                                            </label> 
                                        </div>
                                    </div>
                                    <div class="<?php echo $colClassType3; ?>" style="padding:0px 3px 0px 3px !important;">
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                            <select data-placeholder="Select..." class="form-control chosen-select" id="mdlRptRnsSrchIn">
                                                <?php
                                                $valslctdArry = array("", "", "", "");
                                                $srchInsArrys = array("Report Run ID", "Run By", "Run Date", "Run Status");

                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                    if ($srchIn == $srchInsArrys[$z]) {
                                                        $valslctdArry[$z] = "selected";
                                                    }
                                                    ?>
                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                            <select data-placeholder="Select..." class="form-control chosen-select" id="mdlRptRnsDsplySze" style="min-width:70px !important;">                            
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
                                    <div class="<?php echo $colClassType1; ?>" style="padding:0px 3px 0px 3px !important;">
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination" style="margin: 0px !important;">
                                                <li>
                                                    <a class="rhopagination" href="javascript:getMyMdlRptRuns('previous', 'ReloadDialog', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=50&sbmtdRptID=<?php echo $sbmtdRptID; ?>');" aria-label="Previous">
                                                        <span aria-hidden="true">&laquo;</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="rhopagination" href="javascript:getMyMdlRptRuns('next', 'ReloadDialog', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=50&sbmtdRptID=<?php echo $sbmtdRptID; ?>');" aria-label="Next">
                                                        <span aria-hidden="true">&raquo;</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <fieldset class="basic_person_fs">                                       
                                            <table class="table table-striped table-bordered table-responsive" id="mdlRptRnsTable" cellspacing="0" width="100%" style="width:100%;min-width: 730px;">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Report Run ID</th>
                                                        <th style="min-width:110px !important;">Run Status</th>
                                                        <th style="min-width:140px !important;">Last Time Active</th>
                                                        <th>Open Output File</th>
                                                        <th>Log Files</th>
                                                        <th>Run By</th>
                                                        <!--<th>Progress (%)</th>
                                                        <th>Date Run</th>
                                                        <th>Output Used</th>-->
                                                        <th>Run Source</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $total = get_RptRunsTtl($pkID, $srchFor, $srchIn);
                                                    if ($pageNo > ceil($total / $lmtSze)) {
                                                        $pageNo = 1;
                                                    } else if ($pageNo < 1) {
                                                        $pageNo = ceil($total / $lmtSze);
                                                    }

                                                    $curIdx = $pageNo - 1;
                                                    $result2 = get_RptRuns($pkID, $srchFor, $srchIn, $curIdx, $lmtSze);
                                                    $cntr = 0;
                                                    while ($row2 = loc_db_fetch_array($result2)) {
                                                        $cntr += 1;
                                                        $sbmtdAlrtID = $row2[14];
                                                        //$chckd = FALSE; 
                                                        $outptUsd = $row2[8];
                                                        $rpt_src_encrpt = "";
                                                        if ($outptUsd == "HTML" || $outptUsd == "COLUMN CHART" || $outptUsd == "SIMPLE COLUMN CHART" ||
                                                                $outptUsd == "BAR CHART" || $outptUsd == "PIE CHART" || $outptUsd == "LINE CHART") {
                                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/amcharts_2100/samples/$row2[0].html";
                                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                            if (file_exists($rpt_src)) {
                                                                //file exists!
                                                            } else {
                                                                //file does not exist.
                                                                $rpt_src_encrpt = "None";
                                                            }
                                                        } else if ($outptUsd == "STANDARD") {
                                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].txt";
                                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                            if (file_exists($rpt_src)) {
                                                                //file exists!
                                                            } else {
                                                                //file does not exist.
                                                                $rpt_src_encrpt = "None";
                                                            }
                                                        } else if ($outptUsd == "PDF") {
                                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].pdf";
                                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                            if (file_exists($rpt_src)) {
                                                                //file exists!
                                                            } else {
                                                                //file does not exist.
                                                                $rpt_src_encrpt = "None";
                                                            }
                                                        } else if ($outptUsd == "MICROSOFT WORD") {
                                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].rtf";
                                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                            if (file_exists($rpt_src)) {
                                                                //file exists!
                                                            } else {
                                                                //file does not exist.
                                                                $rpt_src_encrpt = "None";
                                                            }
                                                        } else if ($outptUsd == "MICROSOFT EXCEL") {
                                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].xls";
                                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                            if (file_exists($rpt_src)) {
                                                                //file exists!
                                                            } else {
                                                                //file does not exist.
                                                                $rpt_src_encrpt = "None";
                                                            }
                                                        } else if ($outptUsd == "CHARACTER SEPARATED FILE (CSV)") {
                                                            $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].csv";
                                                            $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                            if (file_exists($rpt_src)) {
                                                                //file exists!
                                                            } else {
                                                                //file does not exist.
                                                                $rpt_src_encrpt = "None";
                                                            }
                                                        } else {
                                                            $rpt_src_encrpt = "None";
                                                        }
                                                        ?>
                                                        <tr id="mdlRptRnsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                            <td class="lovtd"><a href="javascript:getOneRptsRnStsForm(<?php echo $pkID; ?>, <?php echo $row2[0]; ?>, 3,'0', <?php echo $sbmtdAlrtID; ?>);" style="color:blue;font-weight:bold;"><?php echo $row2[0]; ?></a></td>
                                                            <td class="lovtd"><span <?php
                                                                $style2 = "";
                                                                if ($row2[4] == "Not Started!") {
                                                                    $style2 = "style=\"background-color: #E8D68C;padding:5px !important;\"";
                                                                } else if ($row2[4] == "Preparing to Start...") {
                                                                    $style2 = "style=\"background-color: yellow;padding:5px !important;\"";
                                                                } else if ($row2[4] == "Running SQL...") {
                                                                    $style2 = "style=\"background-color: lightgreen;padding:5px !important;\"";
                                                                } else if ($row2[4] == "Formatting Output...") {
                                                                    $style2 = "style=\"background-color: lime;padding:5px !important;\"";
                                                                } else if ($row2[4] == "Storing Output..." || $row2[4] == "Sending Output...") {
                                                                    $style2 = "style=\"background-color: cyan;padding:5px !important;\"";
                                                                } else if ($row2[4] == "Completed!") {
                                                                    $style2 = "style=\"background-color: gainsboro;padding:5px !important;\"";
                                                                } else if (strpos($row2[4], "Error") !== FALSE) {
                                                                    $style2 = "style=\"background-color: red;padding:5px !important;\"";
                                                                }
                                                                echo $style2;
                                                                ?>><?php echo $row2[4]; ?></span></td>
                                                            <td class="lovtd"><span <?php
                                                                $tst = isDteTmeWthnIntrvl(cnvrtDMYTmToYMDTm($row2[10]), '40 second');
                                                                $style2 = "";
                                                                if ($tst == true) {
                                                                    $style2 = "style=\"background-color: limegreen;color: white; font-size:12px;padding:2px;\"";
                                                                }
                                                                echo $style2;
                                                                ?>><?php echo $row2[10]; ?></span></td>
                                                            <td class="lovtd">
                                                                <?php
                                                                if ($rpt_src_encrpt == "None") {
                                                                    ?>
                                                                    <span style="font-weight: bold;color:#FF0000;">
                                                                        <?php
                                                                        echo $rpt_src_encrpt;
                                                                        ?>
                                                                    </span>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="doAjax('grp=1&typ=11&q=Download&fnm=<?php echo $rpt_src_encrpt; ?>', '', 'Redirect', '', '', '');" data-toggle="tooltip" data-placement="bottom" title="OPEN OUTPUT FILE">
                                                                        <img src="cmn_images/dwldicon.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> View Output
                                                                    </button>
                                                                <?php }
                                                                ?>
                                                            </td>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneRptsLogForm(<?php echo $row2[0]; ?>);" data-toggle="tooltip" data-placement="bottom" title="VIEW PROCESS LOG FILE INFO">
                                                                    <img src="cmn_images/vwlog.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> View Log
                                                                </button>
                                                            </td>
                                                            <td class="lovtd"><?php echo $row2[2]; ?></td>
                                                            <!--<td><?php echo $row2[5]; ?></td>
                                                            <td><span><?php echo $row2[3]; ?></span></td>
                                                            <td><span><?php echo $row2[8]; ?></span></td>-->
                                                            <td class="lovtd"><span><?php echo $row2[11]; ?></span></td>
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
                } else {
                    ?>
                    <span>No Results Found</span>
                    <?php
                }
            }
        }
    }
}
?>