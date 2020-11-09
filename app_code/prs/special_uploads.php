<?php

if (array_key_exists('lgn_num', get_defined_vars())) {
    $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
    $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
    $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";
    $curIdx = 0;
    $canAddPrsn = test_prmssns($dfltPrvldgs[7], $mdlNm);
    $canEdtPrsn = test_prmssns($dfltPrvldgs[8], $mdlNm);
    $canDelPrsn = test_prmssns($dfltPrvldgs[9], $mdlNm);
    $canMngMyFirm = test_prmssns($dfltPrvldgs[21], $mdlNm);
    $canview = test_prmssns($dfltPrvldgs[0], $mdlNm);
    $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? cleanInputData($_POST['sbmtdPersonID']) : -1;
    $addOrEdit = isset($_POST['addOrEdit']) ? cleanInputData($_POST['addOrEdit']) : 'VIEW';
    $formTitle = isset($_POST['formTitle']) ? cleanInputData($_POST['formTitle']) : 'View/Edit Person Basic Profile';
    $prsnid = $_SESSION['PRSN_ID'];
    $orgID = $_SESSION['ORG_ID'];
    $lnkdFirmID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "lnkd_firm_org_id", $prsnid);
    $sbmtdPrsnFirmID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "lnkd_firm_org_id", $sbmtdPersonID);
    $lnkdFirmSiteID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "lnkd_firm_site_id", $prsnid);
    if (($canAddPrsn === true && $addOrEdit == "ADD") || ($canEdtPrsn === true && $addOrEdit == "EDIT") || ($canview === true && $addOrEdit == "VIEW")) {
        $dsplyMode = $addOrEdit;
    } else {
        $dsplyMode = "VIEW";
        $sbmtdPersonID = -1;
    }
    if ($qstr == "DELETE") {
        
    } else if ($qstr == "UPDATE") {
        if ($actyp == 1001) {
            //Export
            $exprtFileNmPrt = "CourseObjctvs";
            $inptNum = isset($_POST['inptNum']) ? (int) cleanInputData($_POST['inptNum']) : 0;
            session_write_close();
            $affctd = 0;
            $errMsg = "Invalid Option!";
            if ($inptNum >= 0) {
                $hdngs = array("Type**", "Programme/Objective Code**", "Programme/Objective Name**", "Programme/Objective Description", "ENABLED?");
                $limit_size = 0;
                if ($inptNum > 2) {
                    $limit_size = $inptNum;
                } else if ($inptNum == 2) {
                    $limit_size = 1000000;
                }
                $rndm = getRandomNum(10001, 9999999);
                $dteNm = date('dMY_His');
                $nwFileNm = $fldrPrfx . "dwnlds/tmp/" . $exprtFileNmPrt . "Exprt_" . $dteNm . "_" . $rndm . ".csv";
                $dwnldUrl = $app_url . "dwnlds/tmp/" . $exprtFileNmPrt . "Exprt_" . $dteNm . "_" . $rndm . ".csv";
                $opndfile = fopen($nwFileNm, "w");
                fputcsv($opndfile, $hdngs);
                if ($limit_size <= 0) {
                    $arr_content['percent'] = 100;
                    $arr_content['dwnld_url'] = $dwnldUrl;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!... Template Exported.</span>";
                    $arr_content['msgcount'] = 0;
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_" . $exprtFileNmPrt . "_exprt_progress.rho",
                            json_encode($arr_content));
                    fclose($opndfile);
                    exit();
                }
                $z = 0;
                $crntRw = "";
                $result = get_AcaCoursesExprt(0, $limit_size, "%", "Name");
                $total = loc_db_num_rows($result);
                $fieldCntr = loc_db_num_fields($result);
                while ($row = loc_db_fetch_array($result)) {
                    //"" . ($z + 1), 
                    $crntRw = array($row[0], $row[1], $row[2], $row[3], $row[4]);
                    fputcsv($opndfile, $crntRw);
                    //file_put_contents($nwFileNm, $crntRw, FILE_APPEND | LOCK_EX);
                    $percent = round((($z + 1) / $total) * 100, 2);
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
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_" . $exprtFileNmPrt . "_exprt_progress.rho",
                            json_encode($arr_content));
                    $z++;
                }
                fclose($opndfile);
            } else {
                $percent = 100;
                $arr_content['percent'] = $percent;
                $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                $arr_content['msgcount'] = "";
                $arr_content['dwnld_url'] = "";
                file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_" . $exprtFileNmPrt . "_exprt_progress.rho",
                        json_encode($arr_content));
            }
        } else if ($actyp == 1002) {
            //Checked Exporting Process Status 
            $exprtFileNmPrt = "CourseObjctvs";
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
        } else if ($actyp == 1003) {
            if (!($canEdtPrsn || $canAddPrsn) && !($canMngMyFirm && $lnkdFirmID == $sbmtdPrsnFirmID && $lnkdFirmID > 0)) {
                $arr_content['percent'] = 100;
                $arr_content['daPersonID'] = $inptDaPersonID;
                $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                echo json_encode($arr_content);
                exit();
            }
            //Import Persons
            $dataToSend = trim(cleanInputData($_POST['dataToSend']), "|~");
            session_write_close();
            //New Person Form
            $affctd = 0;
            if ($dataToSend != "") {
                $variousRowsBIG = explode("|", $dataToSend);
                $totalBIG = count($variousRowsBIG);
                for ($y = 0; $y < $totalBIG; $y++) {
                    $crntRow = explode("~", $variousRowsBIG[$y]);
                    if (count($crntRow) == 87) {
                        $daPrsnLocalID = trim(cleanInputData1($crntRow[0]));
                        if (strpos($daPrsnLocalID, "'") === 0) {
                            $daPrsnLocalID = substr($daPrsnLocalID, 1);
                        }
                        $daTitle = trim((cleanInputData1($crntRow[1])));
                        $daFirstName = trim(cleanInputData1($crntRow[2]));
                        $daSurName = trim(cleanInputData1($crntRow[3]));
                        $daOtherNames = trim(cleanInputData1($crntRow[4]));

                        $daGender = trim(cleanInputData1($crntRow[5]));
                        $daMaritalStatus = trim(cleanInputData1($crntRow[6]));
                        $daDOB = trim(cleanInputData1($crntRow[7]));
                        if (strpos($daDOB, "'") === 0) {
                            $daDOB = substr($daDOB, 1);
                        }
                        $daPOB = trim(cleanInputData1($crntRow[8]));
                        $daHomeTown = trim(cleanInputData1($crntRow[9]));
                        $daReligion = trim(cleanInputData1($crntRow[10]));
                        $daResAddress = trim(cleanInputData1($crntRow[11]));
                        $daPostalAddress = trim(cleanInputData1($crntRow[12]));
                        $daEmail = trim(cleanInputData1($crntRow[13]));
                        $daTelNos = trim(cleanInputData1($crntRow[14]));
                        if (strpos($daTelNos, "'") === 0) {
                            $daTelNos = substr($daTelNos, 1);
                        }
                        $daMobileNos = trim(cleanInputData1($crntRow[15]));
                        if (strpos($daMobileNos, "'") === 0) {
                            $daMobileNos = substr($daMobileNos, 1);
                        }

                        $daFaxNo = trim(cleanInputData1($crntRow[16]));
                        if (strpos($daFaxNo, "'") === 0) {
                            $daFaxNo = substr($daFaxNo, 1);
                        }
                        $daNationality = trim(cleanInputData1($crntRow[17]));
                        $daPrsnPicture = trim(cleanInputData1($crntRow[18]));
                        $daRelType = trim(cleanInputData1($crntRow[19]));
                        $daRelCause = trim(cleanInputData1($crntRow[20]));
                        $daRelDetails = trim(cleanInputData1($crntRow[21]));
                        $daRelStartDate = trim(cleanInputData1($crntRow[22]));
                        if (strpos($daRelStartDate, "'") === 0) {
                            $daRelStartDate = substr($daRelStartDate, 1);
                        }
                        $daRelEndDate = trim(cleanInputData1($crntRow[23]));
                        if (strpos($daRelEndDate, "'") === 0) {
                            $daRelEndDate = substr($daRelEndDate, 1);
                        }
                        $daCompany = trim(cleanInputData1($crntRow[24]));
                        $daCompanyLoc = trim(cleanInputData1($crntRow[25]));
                        $daPrsnTypeHstry = trim(cleanInputData1($crntRow[26]));
                        $daPrsnDivGrpHstry = trim(cleanInputData1($crntRow[27]));
                        $daPrsnSpvsrHstry = trim(cleanInputData1($crntRow[28]));
                        $daPrsnSiteLocsHstry = trim(cleanInputData1($crntRow[29]));
                        $daPrsnJobsHstry = trim(cleanInputData1($crntRow[30]));
                        $daPrsnGradesHstry = trim(cleanInputData1($crntRow[31]));
                        $daPrsnPostnHstry = trim(cleanInputData1($crntRow[32]));
                        $daPrsnEducHstry = trim(cleanInputData1($crntRow[33]));
                        $daPrsnWorkHstry = trim(cleanInputData1($crntRow[34]));
                        $daPrsnSkillsHstry = trim(cleanInputData1($crntRow[35]));
                        $daPrsnNtnlIDCrdsHstry = trim(cleanInputData1($crntRow[36]));

                        $addtnlPrsnDataCol1 = trim(cleanInputData1($crntRow[37]));
                        $addtnlPrsnDataCol2 = trim(cleanInputData1($crntRow[38]));
                        $addtnlPrsnDataCol3 = trim(cleanInputData1($crntRow[39]));
                        $addtnlPrsnDataCol4 = trim(cleanInputData1($crntRow[40]));
                        $addtnlPrsnDataCol5 = trim(cleanInputData1($crntRow[41]));
                        $addtnlPrsnDataCol6 = trim(cleanInputData1($crntRow[42]));
                        $addtnlPrsnDataCol7 = trim(cleanInputData1($crntRow[43]));
                        $addtnlPrsnDataCol8 = trim(cleanInputData1($crntRow[44]));
                        $addtnlPrsnDataCol9 = trim(cleanInputData1($crntRow[45]));
                        $addtnlPrsnDataCol10 = trim(cleanInputData1($crntRow[46]));
                        $addtnlPrsnDataCol11 = trim(cleanInputData1($crntRow[47]));
                        $addtnlPrsnDataCol12 = trim(cleanInputData1($crntRow[48]));
                        $addtnlPrsnDataCol13 = trim(cleanInputData1($crntRow[49]));
                        $addtnlPrsnDataCol14 = trim(cleanInputData1($crntRow[50]));
                        $addtnlPrsnDataCol15 = trim(cleanInputData1($crntRow[51]));
                        $addtnlPrsnDataCol16 = trim(cleanInputData1($crntRow[52]));
                        $addtnlPrsnDataCol17 = trim(cleanInputData1($crntRow[53]));
                        $addtnlPrsnDataCol18 = trim(cleanInputData1($crntRow[54]));
                        $addtnlPrsnDataCol19 = trim(cleanInputData1($crntRow[55]));
                        $addtnlPrsnDataCol20 = trim(cleanInputData1($crntRow[56]));
                        $addtnlPrsnDataCol21 = trim(cleanInputData1($crntRow[57]));
                        $addtnlPrsnDataCol22 = trim(cleanInputData1($crntRow[58]));
                        $addtnlPrsnDataCol23 = trim(cleanInputData1($crntRow[59]));
                        $addtnlPrsnDataCol24 = trim(cleanInputData1($crntRow[60]));
                        $addtnlPrsnDataCol25 = trim(cleanInputData1($crntRow[61]));
                        $addtnlPrsnDataCol26 = trim(cleanInputData1($crntRow[62]));
                        $addtnlPrsnDataCol27 = trim(cleanInputData1($crntRow[63]));
                        $addtnlPrsnDataCol28 = trim(cleanInputData1($crntRow[64]));
                        $addtnlPrsnDataCol29 = trim(cleanInputData1($crntRow[65]));
                        $addtnlPrsnDataCol30 = trim(cleanInputData1($crntRow[66]));
                        $addtnlPrsnDataCol31 = trim(cleanInputData1($crntRow[67]));
                        $addtnlPrsnDataCol32 = trim(cleanInputData1($crntRow[68]));
                        $addtnlPrsnDataCol33 = trim(cleanInputData1($crntRow[69]));
                        $addtnlPrsnDataCol34 = trim(cleanInputData1($crntRow[70]));
                        $addtnlPrsnDataCol35 = trim(cleanInputData1($crntRow[71]));
                        $addtnlPrsnDataCol36 = trim(cleanInputData1($crntRow[72]));
                        $addtnlPrsnDataCol37 = trim(cleanInputData1($crntRow[73]));
                        $addtnlPrsnDataCol38 = trim(cleanInputData1($crntRow[74]));
                        $addtnlPrsnDataCol39 = trim(cleanInputData1($crntRow[75]));
                        $addtnlPrsnDataCol40 = trim(cleanInputData1($crntRow[76]));
                        $addtnlPrsnDataCol41 = trim(cleanInputData1($crntRow[77]));
                        $addtnlPrsnDataCol42 = trim(cleanInputData1($crntRow[78]));
                        $addtnlPrsnDataCol43 = trim(cleanInputData1($crntRow[79]));
                        $addtnlPrsnDataCol44 = trim(cleanInputData1($crntRow[80]));
                        $addtnlPrsnDataCol45 = trim(cleanInputData1($crntRow[81]));
                        $addtnlPrsnDataCol46 = trim(cleanInputData1($crntRow[82]));
                        $addtnlPrsnDataCol47 = trim(cleanInputData1($crntRow[83]));
                        $addtnlPrsnDataCol48 = trim(cleanInputData1($crntRow[84]));
                        $addtnlPrsnDataCol49 = trim(cleanInputData1($crntRow[85]));
                        $addtnlPrsnDataCol50 = trim(cleanInputData1($crntRow[86]));

                        $daCompanyID = -1;
                        $daCompanyLocID = -1;
                        if ($y === 0) {
                            if (strtoupper($daPrsnLocalID) == strtoupper("ID NO.**") && strtoupper($daFirstName) == strtoupper("FIRST NAME**") && strtoupper($daRelType) == strtoupper("PERSON TYPE**") && strtoupper($daPrsnSkillsHstry) == strtoupper("SKILLS/NATURE(Languages~Hobbies~Interests~Conduct~Attitude~From Date~To Date|)")) {
                                continue;
                            } else {
                                $arr_content['percent'] = 100;
                                $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> Selected File is Invalid!";
                                //.strtoupper($number) ."|". strtoupper($processName) ."|". strtoupper($isEnbld1 == "IS ENABLED?");
                                $arr_content['msgcount'] = $total;
                                file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prspersonsimprt_progress.rho",
                                        json_encode($arr_content));
                                break;
                            }
                        } else {
                            $daCompanyID = getGnrlRecID2("scm.scm_cstmr_suplr", "cust_sup_name", "cust_sup_id", $daCompany);
                            if ($daCompanyID <= 0) {
                                createQckCstmrSpplr($daCompany);
                                $daCompanyID = getGnrlRecID2("scm.scm_cstmr_suplr", "cust_sup_name", "cust_sup_id", $daCompany);
                            }
                            if ($daPrsnLocalID == "") {
                                $v = "";
                                if (preg_match_all('/\b(\w)/', strtoupper($daCompany), $m)) {
                                    $v = implode('', $m[1]);
                                }
                                $idcntr = getGnrlRecNm("prs.prsn_names_nos", "lnkd_firm_org_id", "count(1)", $daCompanyID);
                                $digit = $idcntr + 1;
                                $daPrsnLocalID = $v . sprintf('%03d', $digit);
                                if (getPersonID($daPrsnLocalID) > 0) {
                                    $daPrsnLocalID .= "-" . strtoupper(getRandomTxt(4));
                                }
                            }
                            $daCompanyLocID = getGnrlRecIDExtr("scm.scm_cstmr_suplr_sites", "site_name", "cust_supplier_id", "cust_sup_site_id", $daCompanyLoc, $daCompanyID);
                            if ($daCompanyLocID <= 0) {
                                createQckCstmrSpplrSite($daCompanyLoc, $daCompanyID, $daCompany);
                                $daCompanyLocID = getGnrlRecIDExtr("scm.scm_cstmr_suplr_sites", "site_name", "cust_supplier_id", "cust_sup_site_id", $daCompanyLoc, $daCompanyID);
                            }
                            if ($daEmail != "") {
                                $invldMailLovID = getLovID("Email Addresses to Ignore");
                                $daEmail = str_replace(" ", ",", str_replace(", ", ",", str_replace(":", ",", str_replace(";", ",", $daEmail))));
                                $tmpMails = explode(",", $daEmail);
                                $anodaEmail = "";
                                foreach ($tmpMails as $tmpMail) {
                                    if (isEmailValid($tmpMail, $invldMailLovID)) {
                                        $anodaEmail .= $tmpMail . ", ";
                                    }
                                }
                                if ($anodaEmail != "") {
                                    $daEmail = trim($anodaEmail, ", ");
                                }
                            }
                            if ($daMobileNos != "") {
                                $daMobileNos = str_replace(" ", ",", str_replace(", ", ",", str_replace(":", ",", str_replace(";", ",", $daMobileNos))));
                                $tmpMobileNos = explode(",", $daMobileNos);
                                $anodaMobileNos = "";
                                foreach ($tmpMobileNos as $tmpMobileNo) {
                                    if (substr($tmpMobileNo, 0, 1) === '0') {
                                        $tmpMobileNo = "+233" . substr($tmpMobileNo, 1);
                                    }
                                    if (isMobileNumValid($tmpMobileNo)) {
                                        $anodaMobileNos .= $tmpMobileNo . ", ";
                                    }
                                }
                                if ($anodaMobileNos != "") {
                                    $daMobileNos = trim($anodaMobileNos, ", ");
                                }
                            }
                            if ($daRelStartDate === "01-Jan-0001" || $daRelStartDate === "01-Jan-1" || trim($daRelStartDate) == "") {
                                $daRelStartDate = substr($gnrlTrnsDteDMYHMS, 0, 11);
                            }
                            if ($daRelEndDate === "01-Jan-0001" || $daRelEndDate === "01-Jan-1" || trim($daRelEndDate) == "") {
                                $daRelEndDate = "31-Dec-4000";
                            }
                            if ($daDOB != "") {
                                $daDOB = cnvrtDMYToYMD($daDOB);
                            }
                            if ($daRelStartDate != "") {
                                $daRelStartDate = cnvrtDMYToYMD($daRelStartDate);
                            }
                            if ($daRelEndDate != "") {
                                $daRelEndDate = cnvrtDMYToYMD($daRelEndDate);
                            }
                        }
                        $exitErrMsg = "";
                        $inptDaPersonID = getPersonID($daPrsnLocalID);
                        if ($daPrsnLocalID != "" &&
                                $daTitle != "" &&
                                $daFirstName != "" &&
                                $daSurName != "" &&
                                $daGender != "" &&
                                $daMaritalStatus != "" &&
                                $daDOB != "" &&
                                $daNationality != "" &&
                                $daRelType != "" &&
                                $daRelCause != "") {
                            if ($inptDaPersonID <= 0) {
                                createPrsnBasic($daFirstName, $daSurName, $daOtherNames, $daTitle, $daPrsnLocalID, $orgID, $daGender, $daMaritalStatus, $daDOB, $daPOB, $daReligion, $daResAddress,
                                        $daPostalAddress, $daEmail, "", $daMobileNos, $daFaxNo, $daHomeTown, $daNationality, ""
                                        , $daCompanyID, $daCompanyLocID, $daCompany, $daCompanyLoc);
                                $inptDaPersonID = getPersonID($daPrsnLocalID);
                                createPrsnsType($inptDaPersonID, $daRelCause, $daRelStartDate, $daRelEndDate, $daRelDetails, $daRelType);
                            } else {
                                updatePrsnBasic($inptDaPersonID, $daFirstName, $daSurName, $daOtherNames, $daTitle, $daPrsnLocalID, $orgID, $daGender, $daMaritalStatus, $daDOB, $daPOB, $daReligion, $daResAddress,
                                        $daPostalAddress, $daEmail, "", $daMobileNos, $daFaxNo, $daHomeTown, $daNationality, $daCompanyID, $daCompanyLocID, $daCompany, $daCompanyLoc);
                                $prsntypRowID = -1;
                                if (checkPrsnType($inptDaPersonID, $daRelType, $daRelStartDate, $prsntypRowID) == false) {
                                    //echo $daRelStartDate;
                                    endOldPrsnTypes($inptDaPersonID, $daRelStartDate);
                                    createPrsnsType($inptDaPersonID, $daRelCause, $daRelStartDate, $daRelEndDate, $daRelDetails, $daRelType);
                                } else if ($prsntypRowID > 0) {
                                    updtPrsnsType($prsntypRowID, $inptDaPersonID, $daRelCause, $daRelStartDate, $daRelEndDate, $daRelDetails, $daRelType);
                                }
                            }
                            $nwImgLoc = "";
                            if ($inptDaPersonID > 0) {
                                if ($daPrsnPicture != "") {
                                    uploadDaImageExcel($inptDaPersonID, $daPrsnPicture, $nwImgLoc);
                                }
                                $adDataExsts = 0;
                                $data_cols = array("", $addtnlPrsnDataCol1, $addtnlPrsnDataCol2, $addtnlPrsnDataCol3, $addtnlPrsnDataCol4, $addtnlPrsnDataCol5,
                                    $addtnlPrsnDataCol6, $addtnlPrsnDataCol7, $addtnlPrsnDataCol8, $addtnlPrsnDataCol9, $addtnlPrsnDataCol10,
                                    $addtnlPrsnDataCol11, $addtnlPrsnDataCol12, $addtnlPrsnDataCol13, $addtnlPrsnDataCol14, $addtnlPrsnDataCol15,
                                    $addtnlPrsnDataCol16, $addtnlPrsnDataCol17, $addtnlPrsnDataCol18, $addtnlPrsnDataCol19, $addtnlPrsnDataCol20,
                                    $addtnlPrsnDataCol21, $addtnlPrsnDataCol22, $addtnlPrsnDataCol23, $addtnlPrsnDataCol24, $addtnlPrsnDataCol25,
                                    $addtnlPrsnDataCol26, $addtnlPrsnDataCol27, $addtnlPrsnDataCol28, $addtnlPrsnDataCol29, $addtnlPrsnDataCol30,
                                    $addtnlPrsnDataCol31, $addtnlPrsnDataCol32, $addtnlPrsnDataCol33, $addtnlPrsnDataCol34, $addtnlPrsnDataCol35,
                                    $addtnlPrsnDataCol36, $addtnlPrsnDataCol37, $addtnlPrsnDataCol38, $addtnlPrsnDataCol39, $addtnlPrsnDataCol40,
                                    $addtnlPrsnDataCol41, $addtnlPrsnDataCol42, $addtnlPrsnDataCol43, $addtnlPrsnDataCol44, $addtnlPrsnDataCol45,
                                    $addtnlPrsnDataCol46, $addtnlPrsnDataCol47, $addtnlPrsnDataCol48, $addtnlPrsnDataCol49, $addtnlPrsnDataCol50);
                                for ($y1 = 0; $y1 < count($data_cols); $y1++) {
                                    if ($data_cols[$y1] != "") {
                                        $adDataExsts++;
                                    }
                                }
                                $extrDataID = -1;
                                $extrDataIDStr = getGnrlRecNm("prs.prsn_extra_data", "person_id", "extra_data_id", $inptDaPersonID);

                                if ($extrDataIDStr != "") {
                                    $extrDataID = (float) $extrDataIDStr;
                                }
                                if ($adDataExsts > 0) {
                                    if ($extrDataID > 0) {
                                        updatePrsnExtrData($inptDaPersonID, $data_cols);
                                    } else {
                                        createPrsnExtrData($inptDaPersonID, $data_cols);
                                    }
                                }
                            }
                            //Save Person Type History
                            $afftctd = 0;
                            if (trim($daPrsnTypeHstry, "|~") != "") {
                                $variousRows = explode("|", trim($daPrsnTypeHstry, "|"));
                                for ($z = 0; $z < count($variousRows); $z++) {
                                    $crntRow = explode("~", $variousRows[$z]);
                                    if (count($crntRow) == 4) {
                                        $lnPrsntypRowID = -1;
                                        $lnRelType = cleanInputData1($crntRow[0]);
                                        $lnRelCause = cleanInputData1($crntRow[1]);
                                        $lnStrtDte = cleanInputData1($crntRow[2]);
                                        $lnEndDte = cleanInputData1($crntRow[3]);
                                        $lnRelDetails = "";
                                        if ($lnStrtDte != "") {
                                            $lnStrtDte = cnvrtDMYToYMD($lnStrtDte);
                                        } else {
                                            $lnStrtDte = $gnrlTrnsDteYMD;
                                        }
                                        if ($lnEndDte != "") {
                                            $lnEndDte = cnvrtDMYToYMD($lnEndDte);
                                        } else {
                                            $lnEndDte = "4000-12-31";
                                        }
                                        if (checkPrsnType($inptDaPersonID, $lnRelType, $lnStrtDte, $lnPrsntypRowID) == false) {
                                            $afftctd += createPrsnsType($inptDaPersonID, $lnRelCause, $lnStrtDte, $lnEndDte, $lnRelDetails, $lnRelType);
                                        }
                                    }
                                }
                            }
                            $afftctd0 = 0;
                            if (trim($daPrsnNtnlIDCrdsHstry, "|~") != "") {
                                $variousRows = explode("|", trim($daPrsnNtnlIDCrdsHstry, "|"));
                                for ($z = 0; $z < count($variousRows); $z++) {
                                    $crntRow = explode("~", $variousRows[$z]);
                                    if (count($crntRow) == 6) {
                                        $lnIDCardsCountry = cleanInputData1($crntRow[0]);
                                        $lnIDCardsIDTyp = cleanInputData1($crntRow[1]);
                                        $lnIDCardsIDNo = cleanInputData1($crntRow[2]);
                                        $lnStrtDte = cleanInputData1($crntRow[3]);
                                        $lnEndDte = cleanInputData1($crntRow[4]);
                                        $lnIDCardsOtherInfo = cleanInputData1($crntRow[5]);
                                        if ($lnStrtDte === "") {
                                            $lnStrtDte = substr($gnrlTrnsDteDMYHMS, 0, 11);
                                        }
                                        if ($lnEndDte != "") {
                                            $lnEndDte = "31-Dec-4000";
                                        }
                                        $lnRowID = getNtnltyID($inptDaPersonID, $lnIDCardsCountry, $lnIDCardsIDTyp);
                                        if ($lnRowID > 0) {
                                            $afftctd0 += updateNtnlID($lnRowID, $lnIDCardsCountry, $lnIDCardsIDTyp, $lnIDCardsIDNo, $lnStrtDte, $lnEndDte, $lnIDCardsOtherInfo);
                                        } else {
                                            $afftctd0 += createNtnlID($inptDaPersonID, $lnIDCardsCountry, $lnIDCardsIDTyp, $lnIDCardsIDNo, $lnStrtDte, $lnEndDte, $lnIDCardsOtherInfo);
                                        }
                                    }
                                }
                            }
                            //Save Divs/Groups
                            $afftctd1 = 0;
                            if (trim($daPrsnDivGrpHstry, "|~") != "") {
                                $variousRows = explode("|", trim($daPrsnDivGrpHstry, "|"));
                                for ($z = 0; $z < count($variousRows); $z++) {
                                    $crntRow = explode("~", $variousRows[$z]);
                                    if (count($crntRow) == 3) {
                                        $lnDivGrpName = cleanInputData1($crntRow[0]);
                                        $lnDivGrpID = getGnrlRecID("org.org_divs_groups", "div_code_name", "div_id", $lnDivGrpName, $orgID);
                                        $lnStrtDte = cleanInputData1($crntRow[1]);
                                        $lnEndDte = cleanInputData1($crntRow[2]);
                                        if ($lnStrtDte === "") {
                                            $lnStrtDte = substr($gnrlTrnsDteDMYHMS, 0, 11);
                                        }
                                        if ($lnEndDte != "") {
                                            $lnEndDte = "31-Dec-4000";
                                        }
                                        $lnRowID = getPDivGrpID($inptDaPersonID, $lnDivGrpID);
                                        if ($lnRowID > 0) {
                                            $afftctd1 += updatePDivGrp($lnRowID, $lnDivGrpID, $lnStrtDte, $lnEndDte);
                                        } else {
                                            $afftctd1 += createPDivGrp($inptDaPersonID, $lnDivGrpID, $lnStrtDte, $lnEndDte);
                                        }
                                    }
                                }
                            }
                            //Save SpvsrHistory
                            $afftctd2 = 0;
                            if (trim($daPrsnSpvsrHstry, "|~") != "") {
                                $variousRows = explode("|", trim($daPrsnSpvsrHstry, "|"));
                                for ($z = 0; $z < count($variousRows); $z++) {
                                    $crntRow = explode("~", $variousRows[$z]);
                                    if (count($crntRow) == 3) {
                                        $lnSuprvsrIDNo = cleanInputData1($crntRow[0]);
                                        $lnSuprvsrID = getGnrlRecID("prs.prsn_names_nos", "local_id_no", "person_id", $lnSuprvsrIDNo, $orgID);
                                        $lnStrtDte = cleanInputData1($crntRow[1]);
                                        $lnEndDte = cleanInputData1($crntRow[2]);
                                        if ($lnStrtDte === "") {
                                            $lnStrtDte = substr($gnrlTrnsDteDMYHMS, 0, 11);
                                        }
                                        if ($lnEndDte != "") {
                                            $lnEndDte = "31-Dec-4000";
                                        }
                                        $lnRowID = getPSuprvsrID($inptDaPersonID, $lnSuprvsrID);
                                        if ($lnRowID > 0) {
                                            $afftctd2 += updatePSuprvsr($lnRowID, $lnSuprvsrID, $lnStrtDte, $lnEndDte);
                                        } else {
                                            $afftctd2 += createPSuprvsr($inptDaPersonID, $lnSuprvsrID, $lnStrtDte, $lnEndDte);
                                        }
                                    }
                                }
                            }
                            //Save SiteHistory
                            $afftctd4 = 0;
                            if (trim($daPrsnSiteLocsHstry, "|~") != "") {
                                $variousRows = explode("|", trim($daPrsnSiteLocsHstry, "|"));
                                for ($z = 0; $z < count($variousRows); $z++) {
                                    $crntRow = explode("~", $variousRows[$z]);
                                    if (count($crntRow) == 3) {
                                        $lnSiteLocCode = cleanInputData1($crntRow[0]);
                                        $lnSiteLocID = getGnrlRecID("org.org_sites_locations", "location_code_name", "location_id", $lnSiteLocCode, $orgID);
                                        $lnStrtDte = cleanInputData1($crntRow[1]);
                                        $lnEndDte = cleanInputData1($crntRow[2]);
                                        if ($lnStrtDte === "") {
                                            $lnStrtDte = substr($gnrlTrnsDteDMYHMS, 0, 11);
                                        }
                                        if ($lnEndDte != "") {
                                            $lnEndDte = "31-Dec-4000";
                                        }
                                        $lnRowID = getPSiteLocID($inptDaPersonID, $lnSiteLocID);
                                        if ($lnRowID > 0) {
                                            $afftctd4 += updatePSiteLoc($lnRowID, $lnSiteLocID, $lnStrtDte, $lnEndDte);
                                        } else {
                                            $afftctd4 += createPSiteLoc($inptDaPersonID, $lnSiteLocID, $lnStrtDte, $lnEndDte);
                                        }
                                    }
                                }
                            }
                            //Save JobHistory
                            $afftctd5 = 0;
                            if (trim($daPrsnJobsHstry, "|~") != "") {
                                $variousRows = explode("|", trim($daPrsnJobsHstry, "|"));
                                for ($z = 0; $z < count($variousRows); $z++) {
                                    $crntRow = explode("~", $variousRows[$z]);
                                    if (count($crntRow) == 3) {
                                        $lnJobCode = cleanInputData1($crntRow[0]);
                                        $lnJobID = getGnrlRecID("org.org_jobs", "job_code_name", "job_id", $lnJobCode, $orgID);
                                        $lnStrtDte = cleanInputData1($crntRow[1]);
                                        $lnEndDte = cleanInputData1($crntRow[2]);
                                        if ($lnStrtDte === "") {
                                            $lnStrtDte = substr($gnrlTrnsDteDMYHMS, 0, 11);
                                        }
                                        if ($lnEndDte != "") {
                                            $lnEndDte = "31-Dec-4000";
                                        }
                                        $lnRowID = getPJobID($inptDaPersonID, $lnJobID);
                                        if ($lnRowID > 0) {
                                            $afftctd5 += updatePJob($lnRowID, $lnJobID, $lnStrtDte, $lnEndDte);
                                        } else {
                                            $afftctd5 += createPJob($inptDaPersonID, $lnJobID, $lnStrtDte, $lnEndDte);
                                        }
                                    }
                                }
                            }
                            //Save GradesHistory
                            $afftctd6 = 0;
                            if (trim($daPrsnGradesHstry, "|~") != "") {
                                $variousRows = explode("|", trim($daPrsnGradesHstry, "|"));
                                for ($z = 0; $z < count($variousRows); $z++) {
                                    $crntRow = explode("~", $variousRows[$z]);
                                    if (count($crntRow) == 3) {
                                        $lnGradeCode = cleanInputData1($crntRow[0]);
                                        $lnGradeID = getGnrlRecID("org.org_grades", "grade_code_name", "grade_id", $lnGradeCode, $orgID);
                                        $lnStrtDte = cleanInputData1($crntRow[1]);
                                        $lnEndDte = cleanInputData1($crntRow[2]);
                                        if ($lnStrtDte === "") {
                                            $lnStrtDte = substr($gnrlTrnsDteDMYHMS, 0, 11);
                                        }
                                        if ($lnEndDte != "") {
                                            $lnEndDte = "31-Dec-4000";
                                        }
                                        $lnRowID = getPGradeID($inptDaPersonID, $lnGradeID);
                                        if ($lnRowID > 0) {
                                            $afftctd6 += updatePGrade($lnRowID, $lnGradeID, $lnStrtDte, $lnEndDte);
                                        } else {
                                            $afftctd6 += createPGrade($inptDaPersonID, $lnGradeID, $lnStrtDte, $lnEndDte);
                                        }
                                    }
                                }
                            }
                            //Save PositionHistory
                            $afftctd7 = 0;
                            if (trim($daPrsnPostnHstry, "|~") != "") {
                                $variousRows = explode("|", trim($daPrsnPostnHstry, "|"));
                                for ($z = 0; $z < count($variousRows); $z++) {
                                    $crntRow = explode("~", $variousRows[$z]);
                                    if (count($crntRow) == 4) {
                                        $lnPostnCode = cleanInputData1($crntRow[0]);
                                        $lnPostnID = getGnrlRecID("org.org_positions", "position_code_name", "position_id", $lnPostnCode, $orgID);
                                        $lnPostnDivCode = cleanInputData1($crntRow[1]);
                                        $lnPostnDivID = getGnrlRecID("org.org_divs_groups", "div_code_name", "div_id", $lnPostnDivCode, $orgID);
                                        $lnStrtDte = cleanInputData1($crntRow[2]);
                                        $lnEndDte = cleanInputData1($crntRow[3]);
                                        if ($lnStrtDte === "") {
                                            $lnStrtDte = substr($gnrlTrnsDteDMYHMS, 0, 11);
                                        }
                                        if ($lnEndDte != "") {
                                            $lnEndDte = "31-Dec-4000";
                                        }
                                        $lnRowID = getPPositionID($inptDaPersonID, $lnPostnID, $lnPostnDivID);
                                        if ($lnRowID > 0) {
                                            $afftctd7 += updatePPosition($lnRowID, $lnPostnID, $lnStrtDte, $lnEndDte, $lnPostnDivID);
                                        } else {
                                            $afftctd7 += createPPosition($inptDaPersonID, $lnPostnID, $lnStrtDte, $lnEndDte, $lnPostnDivID);
                                        }
                                    }
                                }
                            }
                            //logSessionErrs("Save EducHistory");
                            //Save EducHistory
                            $afftctd8 = 0;
                            if (trim($daPrsnEducHstry, "|~") != "") {
                                $variousRows = explode("|", trim($daPrsnEducHstry, "|"));
                                for ($z = 0; $z < count($variousRows); $z++) {
                                    $crntRow = explode("~", $variousRows[$z]);
                                    if (count($crntRow) == 8) {
                                        $lnCourseNm = cleanInputData1($crntRow[0]);
                                        $lnSchool = cleanInputData1($crntRow[1]);
                                        $lnLocation = cleanInputData1($crntRow[2]);
                                        $lnCertObtained = cleanInputData1($crntRow[3]);
                                        $lnCertType = cleanInputData1($crntRow[4]);
                                        $lnDateAwrd = cleanInputData1($crntRow[5]);
                                        $lnStrtDte = cleanInputData1($crntRow[6]);
                                        $lnEndDte = cleanInputData1($crntRow[7]);
                                        if ($lnStrtDte === "") {
                                            $lnStrtDte = substr($gnrlTrnsDteDMYHMS, 0, 11);
                                        }
                                        if ($lnEndDte != "") {
                                            $lnEndDte = "31-Dec-4000";
                                        }
                                        $lnRowID = getEducID($inptDaPersonID, $lnCourseNm, $lnSchool);
                                        if ($lnRowID > 0) {
                                            $afftctd8 += updateEduc($lnRowID, $lnCourseNm, $lnSchool, $lnLocation, $lnCertObtained, $lnStrtDte, $lnEndDte,
                                                    $lnDateAwrd, $lnCertType);
                                        } else {
                                            $afftctd8 += createEduc($inptDaPersonID, $lnCourseNm, $lnSchool, $lnLocation, $lnCertObtained, $lnStrtDte, $lnEndDte,
                                                    $lnDateAwrd, $lnCertType);
                                        }
                                    }
                                }
                            }
                            //Save WorkHistory
                            $afftctd9 = 0;
                            if (trim($daPrsnWorkHstry, "|~") != "") {
                                $variousRows = explode("|", trim($daPrsnWorkHstry, "|"));
                                for ($z = 0; $z < count($variousRows); $z++) {
                                    $crntRow = explode("~", $variousRows[$z]);
                                    if (count($crntRow) == 7) {
                                        $lnJobNm = cleanInputData1($crntRow[0]);
                                        $lnInstitution = cleanInputData1($crntRow[1]);
                                        $lnLocation = cleanInputData1($crntRow[2]);
                                        $lnJobDesc = cleanInputData1($crntRow[3]);
                                        $lnAchvmnts = cleanInputData1($crntRow[4]);
                                        $lnStrtDte = cleanInputData1($crntRow[5]);
                                        $lnEndDte = cleanInputData1($crntRow[6]);
                                        if ($lnStrtDte === "") {
                                            $lnStrtDte = substr($gnrlTrnsDteDMYHMS, 0, 11);
                                        }
                                        if ($lnEndDte != "") {
                                            $lnEndDte = "31-Dec-4000";
                                        }
                                        $lnRowID = getWorkID($inptDaPersonID, $lnJobNm, $lnInstitution);
                                        if ($lnRowID > 0) {
                                            $afftctd9 += updateWork($lnRowID, $lnJobNm, $lnInstitution, $lnLocation, $lnStrtDte, $lnEndDte, $lnJobDesc, $lnAchvmnts);
                                        } else {
                                            $afftctd9 += createWork($inptDaPersonID, $lnJobNm, $lnInstitution, $lnLocation, $lnStrtDte, $lnEndDte, $lnJobDesc,
                                                    $lnAchvmnts);
                                        }
                                    }
                                }
                            }
                            //Save SkillsHistory
                            $afftctd10 = 0;
                            if (trim($daPrsnSkillsHstry, "|~") != "") {
                                $variousRows = explode("|", trim($daPrsnSkillsHstry, "|"));
                                for ($z = 0; $z < count($variousRows); $z++) {
                                    $crntRow = explode("~", $variousRows[$z]);
                                    if (count($crntRow) == 7) {
                                        $lnLanguages = cleanInputData1($crntRow[0]);
                                        $lnHobbies = cleanInputData1($crntRow[1]);
                                        $lnInterests = cleanInputData1($crntRow[2]);
                                        $lnConduct = cleanInputData1($crntRow[3]);
                                        $lnAttitude = cleanInputData1($crntRow[4]);
                                        $lnStrtDte = cleanInputData1($crntRow[5]);
                                        $lnEndDte = cleanInputData1($crntRow[6]);
                                        if ($lnStrtDte === "") {
                                            $lnStrtDte = substr($gnrlTrnsDteDMYHMS, 0, 11);
                                        }
                                        if ($lnEndDte != "") {
                                            $lnEndDte = "31-Dec-4000";
                                        }
                                        $lnRowID = getSkillsID($inptDaPersonID, $lnStrtDte, $lnEndDte);
                                        if ($lnRowID > 0) {
                                            $afftctd10 += updateSkills($lnRowID, $lnLanguages, $lnHobbies, $lnInterests, $lnConduct, $lnAttitude, $lnStrtDte, $lnEndDte);
                                        } else {
                                            $afftctd10 += createSkills($inptDaPersonID, $lnLanguages, $lnHobbies, $lnInterests, $lnConduct, $lnAttitude, $lnStrtDte, $lnEndDte);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $percent = round((($y + 1) / $totalBIG) * 100, 2);
                    $arr_content['percent'] = $percent;
                    if ($percent >= 100) {
                        $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> 100% Completed!..." . $affctd . " out of " . $totalBIG . " Inventory Item(s) imported.";
                        $arr_content['msgcount'] = $totalBIG;
                    } else {
                        $arr_content['message'] = "<i class=\"fa fa-spin fa-spinner\"></i> Importing Accounts...Please Wait..." . ($y + 1) . " out of " . $totalBIG . " Inventory Item(s) imported.";
                    }
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prspersonsimprt_progress.rho",
                            json_encode($arr_content));
                }
            } else {
                $percent = 100;
                $arr_content['percent'] = $percent;
                $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                $arr_content['msgcount'] = "";
                file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prspersonsimprt_progress.rho", json_encode($arr_content));
            }
        } else if ($actyp == 1004) {
            //Checked Importing Process Status                
            header('Content-Type: application/json');
            $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prspersonsimprt_progress.rho";
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
        }
    } else {
        
    }
}