<?php
if (array_key_exists('lgn_num', get_defined_vars())) {
    $usrID = $_SESSION['USRID'];
    $prsnid = $_SESSION['PRSN_ID'];
    $orgID = $_SESSION['ORG_ID'];
    $error = "";
    $searchAll = false;
    if (isset($_POST['dataAdminShwAllOrgs'])) {
        $searchAll = cleanInputData($_POST['dataAdminShwAllOrgs']) === "true" ? true : false;
    }
    $canVwAllOrgs = true;
    $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
    $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
    $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
    $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
    $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";
    $fltrTyp = isset($_POST['dataAdminFilterBy']) ? cleanInputData($_POST['dataAdminFilterBy']) : "Relation Type";
    $fltrTypValue = isset($_POST['dataAdminFilterByVal']) ? cleanInputData($_POST['dataAdminFilterByVal']) : "All";

    $canVwRcHstry = test_prmssns("View Record History", $mdlNm);
    if (strpos($srchFor, "%") === FALSE) {
        $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
        $srchFor = str_replace("%%", "%", $srchFor);
    }

    $lnkdFirmID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "lnkd_firm_org_id", $prsnid);
    $pkID = $prsnid;

    $canAddPrsn = test_prmssns($dfltPrvldgs[7], $mdlNm);
    $canEdtPrsn = test_prmssns($dfltPrvldgs[8], $mdlNm);
    $canDelPrsn = test_prmssns($dfltPrvldgs[9], $mdlNm);
    $canMngMyFirm = test_prmssns($dfltPrvldgs[21], $mdlNm);
    $canview = test_prmssns($dfltPrvldgs[0], $mdlNm);
    $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? cleanInputData($_POST['sbmtdPersonID']) : -1;
    $addOrEdit = isset($_POST['addOrEdit']) ? cleanInputData($_POST['addOrEdit']) : 'VIEW';
    $formTitle = isset($_POST['formTitle']) ? cleanInputData($_POST['formTitle']) : 'View/Edit Person Basic Profile';
    $sbmtdPrsnFirmID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "lnkd_firm_org_id", $sbmtdPersonID);
    $lnkdFirmSiteID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "lnkd_firm_site_id", $prsnid);
    if ($qstr == "DELETE") {
    } else if ($qstr == "UPDATE") {
        if ($actyp == 102) {
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
                            if (
                                strtoupper($daPrsnLocalID) == strtoupper("ID NO.**") && strtoupper($daFirstName) == strtoupper("FIRST NAME**")
                                && strtoupper($daRelType) == strtoupper("PERSON TYPE**")
                                && strtoupper($daPrsnSkillsHstry) == strtoupper("SKILLS/NATURE(Languages~Hobbies~Interests~Conduct~Attitude~From Date~To Date|)")
                            ) {
                                continue;
                            } else {
                                $arr_content['percent'] = 100;
                                $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> Selected File is Invalid!";
                                //.strtoupper($number) ."|". strtoupper($processName) ."|". strtoupper($isEnbld1 == "IS ENABLED?");
                                $arr_content['msgcount'] = $total;
                                file_put_contents(
                                    $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prspersonsimprt_progress.rho",
                                    json_encode($arr_content)
                                );
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
                        /*
                                $daTitle != "" &&*/
                        if (
                            $daPrsnLocalID != "" &&
                            $daFirstName != "" &&
                            $daSurName != "" &&
                            $daGender != "" &&
                            $daMaritalStatus != "" &&
                            $daDOB != "" &&
                            $daNationality != "" &&
                            $daRelType != "" &&
                            $daRelCause != ""
                        ) {
                            if ($inptDaPersonID <= 0) {
                                createPrsnBasic(
                                    $daFirstName,
                                    $daSurName,
                                    $daOtherNames,
                                    $daTitle,
                                    $daPrsnLocalID,
                                    $orgID,
                                    $daGender,
                                    $daMaritalStatus,
                                    $daDOB,
                                    $daPOB,
                                    $daReligion,
                                    $daResAddress,
                                    $daPostalAddress,
                                    $daEmail,
                                    "",
                                    $daMobileNos,
                                    $daFaxNo,
                                    $daHomeTown,
                                    $daNationality,
                                    "",
                                    $daCompanyID,
                                    $daCompanyLocID,
                                    $daCompany,
                                    $daCompanyLoc
                                );
                                $inptDaPersonID = getPersonID($daPrsnLocalID);
                                createPrsnsType($inptDaPersonID, $daRelCause, $daRelStartDate, $daRelEndDate, $daRelDetails, $daRelType);
								
								/*CLINIC/HOSPITAL*/
                                //$daRelType
                                if($daRelType == 'Patient'){
                                    //CHECK EXISTENCE OF LINKED PERSON ID
                                    $lnkdPrsnExt = checkLinkedPrsnExtnce($inptDaPersonID, $orgID);
                                    if(!($lnkdPrsnExt)){
                                        //CREATE CUSTOMER
                                        $cstmrSpplrNm = trim($daTitle." ".$daSurName.", ".$daFirstName." ".$daOtherNames)." (".$daPrsnLocalID.")";
                                        $cstmrSpplrDesc = $cstmrSpplrNm;
                                        $cstmrSpplrType = "Customer";
                                        $cstmrSpplrClsfctn = "Individual";
                                        $cstmrLbltyAcntID = get_DfltPyblAcnt($orgID);
                                        $cstmrRcvblsAcntID = get_DfltRcvblAcnt($orgID);
                                        $cstmrSpplrLnkdPrsnID = $inptDaPersonID;
                                        $cstmrSpplrGender = $daGender;
                                        $cstmrSpplrDOB = $daDOB;
                                        $isCstmrEnbldVal = "1";
                                        $accbPrmSnsRstl = getAccbPgPrmssns($orgID);
                                        $fnccurid = $accbPrmSnsRstl[0];
                                        createCstmr($cstmrSpplrNm, $cstmrSpplrDesc, $cstmrSpplrClsfctn, $cstmrSpplrType, $orgID, $cstmrLbltyAcntID, $cstmrRcvblsAcntID, $cstmrSpplrLnkdPrsnID,
                                                $cstmrSpplrGender, $cstmrSpplrDOB, $isCstmrEnbldVal, "", "", "", "", "",
                                                "", "", "", 0, "", "");
                                        $sbmtdCstmrSpplrID = getCstmrID($cstmrSpplrNm, $orgID);
                                        $cstmrSiteCnt = (int) getGnrlRecNm("scm.scm_cstmr_suplr_sites", "cust_supplier_id", "count(cust_sup_site_id)", $sbmtdCstmrSpplrID);
                                        if ($cstmrSiteCnt <= 0) {
                                            createCstmrSite($sbmtdCstmrSpplrID, "To be Specified", "", "", "HEAD OFFICE-" . $cstmrSpplrNm, "HEAD OFFICE-" . $cstmrSpplrNm, "", "", "", -1, -1, "",
                                                    "", "", "", "", "", "", "", "", "1", "", $fnccurid);
                                        }
                                        
                                        execUpdtInsSQL("UPDATE prs.prsn_extra_data SET data_col6 = '$cstmrSpplrNm' WHERE person_id = $inptDaPersonID");
                                    }
                                }
                                /*CLINIC/HOSPITAL*/
                            } else {
                                updatePrsnBasic(
                                    $inptDaPersonID,
                                    $daFirstName,
                                    $daSurName,
                                    $daOtherNames,
                                    $daTitle,
                                    $daPrsnLocalID,
                                    $orgID,
                                    $daGender,
                                    $daMaritalStatus,
                                    $daDOB,
                                    $daPOB,
                                    $daReligion,
                                    $daResAddress,
                                    $daPostalAddress,
                                    $daEmail,
                                    "",
                                    $daMobileNos,
                                    $daFaxNo,
                                    $daHomeTown,
                                    $daNationality,
                                    $daCompanyID,
                                    $daCompanyLocID,
                                    $daCompany,
                                    $daCompanyLoc
                                );
                                $prsntypRowID = -1;
                                if (checkPrsnType($inptDaPersonID, $daRelType, $daRelStartDate, $prsntypRowID) == false) {
                                    //echo $daRelStartDate;
                                    endOldPrsnTypes($inptDaPersonID, $daRelStartDate);
                                    createPrsnsType($inptDaPersonID, $daRelCause, $daRelStartDate, $daRelEndDate, $daRelDetails, $daRelType);
                                } else if ($prsntypRowID > 0) {
                                    updtPrsnsType($prsntypRowID, $inptDaPersonID, $daRelCause, $daRelStartDate, $daRelEndDate, $daRelDetails, $daRelType);
                                }
								
								/*CLINIC/HOSPITAL*/
                                if($daRelType == 'Patient'){
                                    $lnkdPrsnExt = checkLinkedPrsnExtnce($inptDaPersonID, $orgID);
                                    $cstmrSpplrNm = trim($daTitle." ".$daSurName.", ".$daFirstName." ".$daOtherNames)." (".$daPrsnLocalID.")";
                                        $cstmrSpplrDesc = $cstmrSpplrNm;
                                        $cstmrSpplrType = "Customer";
                                        $cstmrSpplrClsfctn = "Individual";
                                        $cstmrLbltyAcntID = get_DfltPyblAcnt($orgID);
                                        $cstmrRcvblsAcntID = get_DfltRcvblAcnt($orgID);
                                        $cstmrSpplrLnkdPrsnID = $inptDaPersonID;
                                        $cstmrSpplrGender = $daGender;
                                        $cstmrSpplrDOB = $daDOB;
                                        $isCstmrEnbldVal = "1";
                                        $accbPrmSnsRstl = getAccbPgPrmssns($orgID);
                                        $fnccurid = $accbPrmSnsRstl[0];
                                    if(!($lnkdPrsnExt)){
                                        createCstmr($cstmrSpplrNm, $cstmrSpplrDesc, $cstmrSpplrClsfctn, $cstmrSpplrType, $orgID, $cstmrLbltyAcntID, $cstmrRcvblsAcntID, $cstmrSpplrLnkdPrsnID,
                                                $cstmrSpplrGender, $cstmrSpplrDOB, $isCstmrEnbldVal, "", "", "", "", "", "", "", "", 0, "", "");
                                        $sbmtdCstmrSpplrID = getCstmrID($cstmrSpplrNm, $orgID);
                                        $cstmrSiteCnt = (int) getGnrlRecNm("scm.scm_cstmr_suplr_sites", "cust_supplier_id", "count(cust_sup_site_id)", $sbmtdCstmrSpplrID);
                                        if ($cstmrSiteCnt <= 0) {
                                            createCstmrSite($sbmtdCstmrSpplrID, "To be Specified", "", "", "HEAD OFFICE-" . $cstmrSpplrNm, "HEAD OFFICE-" . $cstmrSpplrNm, "", "", "", -1, -1, "",
                                                    "", "", "", "", "", "", "", "", "1", "", $fnccurid);
                                        }
                                        
                                        execUpdtInsSQL("UPDATE prs.prsn_extra_data SET data_col6 = '$cstmrSpplrNm' WHERE person_id = $inptDaPersonID");
                                    } else {
                                        $sbmtdCstmrSpplrID = getLnkdPrsnCstmrID($inptDaPersonID, $orgid);
                                        if($sbmtdCstmrSpplrID > 0) {
                                            updateCstmr($sbmtdCstmrSpplrID, $cstmrSpplrNm, $cstmrSpplrDesc, $cstmrSpplrClsfctn, $cstmrSpplrType, $orgID, $cstmrLbltyAcntID, $cstmrRcvblsAcntID, $cstmrSpplrLnkdPrsnID,
                                                    $cstmrSpplrGender, $cstmrSpplrDOB, $isCstmrEnbldVal, "", "", "", "", "", "", "", "", 0, "", "");
                                            $cstmrSiteCnt = (int) getGnrlRecNm("scm.scm_cstmr_suplr_sites", "cust_supplier_id", "count(cust_sup_site_id)", $sbmtdCstmrSpplrID);
                                            if ($cstmrSiteCnt <= 0) {
                                                createCstmrSite($sbmtdCstmrSpplrID, "To be Specified", "", "", "HEAD OFFICE-" . $cstmrSpplrNm, "HEAD OFFICE-" . $cstmrSpplrNm, "", "", "", -1, -1, "",
                                                        "", "", "", "", "", "", "", "", "1", "", $fnccurid);
                                            }
                                            
                                            execUpdtInsSQL("UPDATE prs.prsn_extra_data SET data_col6 = '$cstmrSpplrNm' WHERE person_id = $inptDaPersonID");
                                        }
                                    }
                                }
                                /*CLINIC/HOSPITAL*/
                            }
                            $nwImgLoc = "";
                            if ($inptDaPersonID > 0) {
                                if ($daPrsnPicture != "") {
                                    uploadDaImageExcel($inptDaPersonID, $daPrsnPicture, $nwImgLoc);
                                }
                                $adDataExsts = 0;
                                $data_cols = array(
                                    "", $addtnlPrsnDataCol1, $addtnlPrsnDataCol2, $addtnlPrsnDataCol3, $addtnlPrsnDataCol4, $addtnlPrsnDataCol5,
                                    $addtnlPrsnDataCol6, $addtnlPrsnDataCol7, $addtnlPrsnDataCol8, $addtnlPrsnDataCol9, $addtnlPrsnDataCol10,
                                    $addtnlPrsnDataCol11, $addtnlPrsnDataCol12, $addtnlPrsnDataCol13, $addtnlPrsnDataCol14, $addtnlPrsnDataCol15,
                                    $addtnlPrsnDataCol16, $addtnlPrsnDataCol17, $addtnlPrsnDataCol18, $addtnlPrsnDataCol19, $addtnlPrsnDataCol20,
                                    $addtnlPrsnDataCol21, $addtnlPrsnDataCol22, $addtnlPrsnDataCol23, $addtnlPrsnDataCol24, $addtnlPrsnDataCol25,
                                    $addtnlPrsnDataCol26, $addtnlPrsnDataCol27, $addtnlPrsnDataCol28, $addtnlPrsnDataCol29, $addtnlPrsnDataCol30,
                                    $addtnlPrsnDataCol31, $addtnlPrsnDataCol32, $addtnlPrsnDataCol33, $addtnlPrsnDataCol34, $addtnlPrsnDataCol35,
                                    $addtnlPrsnDataCol36, $addtnlPrsnDataCol37, $addtnlPrsnDataCol38, $addtnlPrsnDataCol39, $addtnlPrsnDataCol40,
                                    $addtnlPrsnDataCol41, $addtnlPrsnDataCol42, $addtnlPrsnDataCol43, $addtnlPrsnDataCol44, $addtnlPrsnDataCol45,
                                    $addtnlPrsnDataCol46, $addtnlPrsnDataCol47, $addtnlPrsnDataCol48, $addtnlPrsnDataCol49, $addtnlPrsnDataCol50
                                );
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
                                            $afftctd8 += updateEduc(
                                                $lnRowID,
                                                $lnCourseNm,
                                                $lnSchool,
                                                $lnLocation,
                                                $lnCertObtained,
                                                $lnStrtDte,
                                                $lnEndDte,
                                                $lnDateAwrd,
                                                $lnCertType
                                            );
                                        } else {
                                            $afftctd8 += createEduc(
                                                $inptDaPersonID,
                                                $lnCourseNm,
                                                $lnSchool,
                                                $lnLocation,
                                                $lnCertObtained,
                                                $lnStrtDte,
                                                $lnEndDte,
                                                $lnDateAwrd,
                                                $lnCertType
                                            );
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
                                            $afftctd9 += createWork(
                                                $inptDaPersonID,
                                                $lnJobNm,
                                                $lnInstitution,
                                                $lnLocation,
                                                $lnStrtDte,
                                                $lnEndDte,
                                                $lnJobDesc,
                                                $lnAchvmnts
                                            );
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
                    file_put_contents(
                        $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prspersonsimprt_progress.rho",
                        json_encode($arr_content)
                    );
                }
            } else {
                $percent = 100;
                $arr_content['percent'] = $percent;
                $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                $arr_content['msgcount'] = "";
                file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_prspersonsimprt_progress.rho", json_encode($arr_content));
            }
        } else if ($actyp == 103) {
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
        } else if ($actyp == 3) {
            //Export Persons
            $inptNum = isset($_POST['inptNum']) ? (int) cleanInputData($_POST['inptNum']) : 0;
            session_write_close();
            $affctd = 0;
            $errMsg = "Invalid Option!";
            if ($inptNum >= 0) {
                $hdngs = array(
                    "ID NO.**", "TITLE**", "FIRST NAME**", "SURNAME**", "OTHER NAMES",
                    "GENDER**", "MARITAL STATUS**", "DATE OF BIRTH**", "PLACE OF BIRTH", "HOME TOWN", "RELIGION", "RESIDENTIAL ADDRESS", "POSTAL ADDRESS",
                    "EMAIL", "TEL.", "MOBILE", "FAX", "NATIONALITY**", "IMAGE FILE NAME", "PERSON TYPE**", "PERSON TYPE REASON**", "PERSON TYPE FUTHER DETAILS",
                    "FROM", "TO", "LINKED FIRM /WORKPLACE", "SITE/BRANCH", "PERSON TYPE HISTORY(Relation/Person Type~Cause of Relation~Start Date~End Date|)",
                    "DIVISIONS/GROUPS(Group Code~Start Date~End Date|)", "IMMEDIATE SUPERVISORS(Supervisor ID No.~Start Date~End Date|)",
                    "SITES/LOCATIONS(Site/Location Code~Start Date~End Date|)", "JOBS(Job Code~Start Date~End Date|)",
                    "GRADES(Grade Code~Start Date~End Date|)", "POSITIONS(Position Code~Division/Group~Start Date~End Date|)",
                    "EDUCATIONAL BACKGROUND(Course Name~School/Institution~School Location~Certificate Obtained~Certificate Type~Date Awarded~Start Date~End Date|)",
                    "WORKING EXPERIENCE(Job Name/Title~Institution Name~Job Location~Job Description~Feats/Achievements~Start Date~End Date|)",
                    "SKILLS/NATURE(Languages~Hobbies~Interests~Conduct~Attitude~From Date~To Date|)",
                    "ID Cards(Country~ID Type~ID No.~Date Issued~Expiry Date~Other Information|)",
                    "Additional Data 1", "Additional Data 2", "Additional Data 3", "Additional Data 4", "Additional Data 5", "Additional Data 6", "Additional Data 7", "Additional Data 8", "Additional Data 9", "Additional Data 10",
                    "Additional Data 11", "Additional Data 12", "Additional Data 13", "Additional Data 14", "Additional Data 15", "Additional Data 16", "Additional Data 17", "Additional Data 18", "Additional Data 19", "Additional Data 20",
                    "Additional Data 21", "Additional Data 22", "Additional Data 23", "Additional Data 24", "Additional Data 25", "Additional Data 26", "Additional Data 27", "Additional Data 28", "Additional Data 29", "Additional Data 30",
                    "Additional Data 31", "Additional Data 32", "Additional Data 33", "Additional Data 34", "Additional Data 35", "Additional Data 36", "Additional Data 37", "Additional Data 38", "Additional Data 39", "Additional Data 40",
                    "Additional Data 41", "Additional Data 42", "Additional Data 43", "Additional Data 44", "Additional Data 45", "Additional Data 46", "Additional Data 47", "Additional Data 48", "Additional Data 49", "Additional Data 50"
                );
                $hdngRslt = get_AllPrsExtrDataCols($orgID);
                while ($hdngRw = loc_db_fetch_array($hdngRslt)) {
                    $u = 36 + ((int) $hdngRw[1]);
                    $hdngs[$u] = $hdngRw[2];
                }
                $limit_size = 0;
                if ($inptNum > 2) {
                    $limit_size = $inptNum;
                } else if ($inptNum == 2) {
                    $limit_size = 1000000;
                }
                $rndm = getRandomNum(10001, 9999999);
                $dteNm = date('dMY_His');
                $nwFileNm = $fldrPrfx . "dwnlds/tmp/PrsPersonsExprt_" . $dteNm . "_" . $rndm . ".csv";
                $dwnldUrl = $app_url . "dwnlds/tmp/PrsPersonsExprt_" . $dteNm . "_" . $rndm . ".csv";
                $opndfile = fopen($nwFileNm, "w");
                fputcsv($opndfile, $hdngs);
                if ($limit_size <= 0) {
                    $arr_content['percent'] = 100;
                    $arr_content['dwnld_url'] = $dwnldUrl;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!... Inventory Items Template Exported.</span>";
                    $arr_content['msgcount'] = 0;
                    file_put_contents(
                        $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_PrsPersons_exprt_progress.rho",
                        json_encode($arr_content)
                    );
                    fclose($opndfile);
                    exit();
                }
                $z = 0;
                $crntRw = "";
                $result = get_BscPrsnExprt($srchFor, $srchIn, 0, $limit_size, $orgID, $searchAll, $sortBy, $fltrTypValue, $fltrTyp);
                $total = loc_db_num_rows($result);
                $fieldCntr = loc_db_num_fields($result);
                while ($row = loc_db_fetch_array($result)) {
                    //"" . ($z + 1), 
                    $crntRw = array(
                        $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6],
                        $row[7], $row[8], $row[9], $row[10], $row[11], $row[12], $row[13], $row[14], $row[15],
                        $row[16], $row[17], $row[18], $row[19], $row[20], $row[21], $row[22], $row[23], $row[24],
                        $row[25], $row[26], $row[27], $row[28], $row[29], $row[30], $row[31], $row[32], $row[33],
                        $row[34], $row[35], $row[36], $row[37], $row[38], $row[39], $row[40], $row[41], $row[42], $row[43],
                        $row[44], $row[45], $row[46], $row[47], $row[48], $row[49], $row[50], $row[51], $row[52], $row[53],
                        $row[54], $row[55], $row[56], $row[57], $row[58], $row[59], $row[60], $row[61], $row[62], $row[63],
                        $row[64], $row[65], $row[66], $row[67], $row[68], $row[69], $row[70], $row[71], $row[72], $row[73],
                        $row[74], $row[75], $row[76], $row[77], $row[78], $row[79], $row[80], $row[81], $row[82], $row[83],
                        $row[84], $row[85], $row[86]
                    );
                    fputcsv($opndfile, $crntRw);
                    //file_put_contents($nwFileNm, $crntRw, FILE_APPEND | LOCK_EX);
                    $percent = round((($z + 1) / $total) * 100, 2);
                    $arr_content['percent'] = $percent;
                    $arr_content['dwnld_url'] = $dwnldUrl;
                    if ($percent >= 100) {
                        $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!..." . ($z +
                            1) . " out of " . $total . " Person(s) exported.</span>";
                        $arr_content['msgcount'] = $total;
                    } else {
                        $arr_content['message'] = "<span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"><br/>Exporting Inventory Items...Please Wait..." . ($z +
                            1) . " out of " . $total . " Person(s) exported.</span>";
                    }
                    file_put_contents(
                        $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_PrsPersons_exprt_progress.rho",
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
                    $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_PrsPersons_exprt_progress.rho",
                    json_encode($arr_content)
                );
            }
        } else if ($actyp == 4) {
            //Checked Exporting Process Status                
            header('Content-Type: application/json');
            $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_PrsPersons_exprt_progress.rho";
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
            $canAddPrsn = test_prmssns($dfltPrvldgs[7], $mdlNm);
            echo $cntent . "<li>
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\" onclick=\"openATab('#allmodules', 'grp=8&typ=1&pg=5&vtyp=0');\">Data Administration</span>
					</li>
                                       </ul>
                                     </div>";
            $total = get_BscPrsnTtl($srchFor, $srchIn, $orgID, $searchAll, $fltrTypValue, $fltrTyp);
            if ($pageNo > ceil($total / $lmtSze)) {
                $pageNo = 1;
            } else if ($pageNo < 1) {
                $pageNo = ceil($total / $lmtSze);
            }

            $curIdx = $pageNo - 1;
            $result = get_BscPrsn($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy, $fltrTypValue, $fltrTyp);
            $cntr = 0;
            $colClassType1 = "col-lg-2";
            $colClassType2 = "col-lg-6";
            $colClassType3 = "col-lg-4";
?>
            <form id='dataAdminForm' action='' method='post' accept-charset='UTF-8'>
                <div class="row rhoRowMargin">
                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                        <div class="input-group">
                            <input class="form-control" id="dataAdminSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                            echo trim(str_replace("%", " ", $srchFor));
                                                                                                                            ?>" onkeyup="enterKeyFuncDtAdmn(event, '', '#allmodules', 'grp=8&typ=1&pg=5&vtyp=0')">
                            <input id="dataAdminPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                            <input id="allAdminsSrcForm" type="hidden" value="5">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getDataAdmin('clear', '#allmodules', 'grp=8&typ=1&pg=5&vtyp=0');">
                                <span class="glyphicon glyphicon-remove"></span>
                            </label>
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getDataAdmin('', '#allmodules', 'grp=8&typ=1&pg=5&vtyp=0');">
                                <span class="glyphicon glyphicon-search"></span>
                            </label>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                            <select data-placeholder="Select..." class="form-control chosen-select" id="dataAdminSrchIn">
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
                            <select data-placeholder="Select..." class="form-control chosen-select" id="dataAdminDsplySze" style="min-width:70px !important;">
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
                            <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                            <select data-placeholder="Select..." class="form-control chosen-select" id="dataAdminSortBy">
                                <?php
                                $valslctdArry = array("", "", "", "", "");
                                $srchInsArrys = array("ID ASC", "ID DESC", "Date Added DESC", "Date of Birth", "Full Name");
                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    if ($sortBy == $srchInsArrys[$z]) {
                                        $valslctdArry[$z] = "selected";
                                    }
                                ?>
                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="<?php echo $colClassType3; ?>">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                            <select data-placeholder="Select..." class="form-control chosen-select" id="dataAdminFilterBy" onchange="onPrsnFilterByChange();">
                                <?php
                                $valslctdArry = array("", "", "", "", "", "");
                                $srchInsArrys = array("Relation Type", "Division/Group", "Grade", "Job", "Position", "Site/Location");
                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    if ($fltrTyp == $srchInsArrys[$z]) {
                                        $valslctdArry[$z] = "selected";
                                    }
                                ?>
                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                <?php } ?>
                            </select>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                            <select data-placeholder="Select..." class="form-control chosen-select" id="dataAdminFilterByVal" onchange="onPrsnFltrValChange();">
                                <?php
                                $srchInsArrys = loadDataOptions2($fltrTyp);
                                $valslctdArry = array_fill(0, count($srchInsArrys), "");
                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    if ($fltrTypValue == $srchInsArrys[$z]) {
                                        $valslctdArry[$z] = "selected";
                                    }
                                ?>
                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="<?php echo $colClassType1; ?>">
                        <nav aria-label="Page navigation">
                            <ul class="pagination" style="margin: 0px !important;">
                                <li>
                                    <a class="rhopagination" href="javascript:getDataAdmin('previous', '#allmodules', 'grp=8&typ=1&pg=5&vtyp=0');" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="rhopagination" href="javascript:getDataAdmin('next', '#allmodules', 'grp=8&typ=1&pg=5&vtyp=0');" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important;">
                    <div class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                        <div class="col-sm-2" style="padding:5px 1px 0px 1px !important;">
                            <?php if ($canVwAllOrgs == true) { ?>
                                <div class="form-check" style="font-size: 12px !important;">
                                    <label class="form-check-label" title="Search all Organisations">
                                        <?php
                                        $shwVwAllOrgsChkd = "";
                                        if ($searchAll == true) {
                                            $shwVwAllOrgsChkd = "checked=\"true\"";
                                        }
                                        ?>
                                        <input type="checkbox" class="form-check-input" onclick="getDataAdmin('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="dataAdminShwAllOrgs" name="dataAdminShwAllOrgs" <?php echo $shwVwAllOrgsChkd; ?>>
                                        Search all Organisations
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-4" style="padding:0px 1px 0px 1px !important;">
                            <?php if ($canAddPrsn === true) { ?>
                                <button type="button" class="btn btn-default btn-sm" onclick="getBscProfileForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'dtAdmnBscPrsnPrflForm', 'Add Person Basic Profile', -1, 23, 2, 'ADD');">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Person
                                </button>
                            <?php } ?>
                            <button type="button" class="btn btn-default btn-sm" onclick="exprtPersons();">
                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                Export Persons
                            </button>
                            <?php if ($canAddPrsn) { ?>
                                <button type="button" class="btn btn-default btn-sm" onclick="importPersons();">
                                    <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Import Persons
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-responsive" id="dataAdminTable" cellspacing="0" width="100%" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <?php if ($canAddPrsn === true) { ?>
                                        <th>...</th>
                                    <?php } ?>
                                    <th>...</th>
                                    <th>ID No.</th>
                                    <th>Full Name</th>
                                    <th>Linked Firm</th>
                                    <th>Email</th>
                                    <th>Contact Nos.</th>
                                    <th>Address</th>
                                    <th>...</th>
                                    <?php if ($canVwRcHstry === true) { ?>
                                        <th>...</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = loc_db_fetch_array($result)) {
                                    /**/
                                    $cntr += 1;
                                ?>
                                    <tr id="dtAdmnBscPrsnPrflRow<?php echo $cntr; ?>">
                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                        <?php if ($canAddPrsn === true) { ?>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Basic Profile" onclick="getBscProfileForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'dtAdmnBscPrsnPrflForm', 'View/Edit Person Basic Profile', <?php echo $row[0]; ?>, 0, 2, 'EDIT');" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                        <?php } ?>
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Print Profile" onclick="getPrsnProfilePDF(<?php echo $row[0]; ?>);" style="padding:2px !important;">
                                                <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                <img src="cmn_images/pdf.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                        </td>
                                        <td class="lovtd"><?php echo $row[1]; ?></td>
                                        <td class="lovtd"><?php echo $row[2]; ?></td>
                                        <td class="lovtd"><?php echo str_replace("()", "", $row[22] . " (" . $row[24] . ")"); ?></td>
                                        <td class="lovtd"><?php echo $row[14]; ?></td>
                                        <td class="lovtd"><?php echo trim($row[15] . ", " . $row[16], ", "); ?></td>
                                        <td class="lovtd"><?php echo trim($row[13] . " " . $row[12], " "); ?></td>
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Basic Profile" onclick="getBscProfileForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'dtAdmnBscPrsnPrflForm', 'View Person Basic Profile (Direct)', <?php echo $row[0]; ?>, 0, 1, 'VIEW');" style="padding:2px !important;">
                                                <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                        </td>
                                        <?php if ($canVwRcHstry === true) { ?>
                                            <td class="lovtd">
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
            </form>
        <?php
        } else if ($vwtyp == 1) {
            /* Get Institutions Under me */
            $canAddPrsn = test_prmssns($dfltPrvldgs[7], $mdlNm);
            echo $cntent . "<li>
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span><span style=\"text-decoration:none;\">Self-Service Managers</span>
					</li>
                                       </ul>
                                     </div>";
            $extrWhere = " and a.lnkd_firm_org_id=" . $lnkdFirmID . " and a.lnkd_firm_org_id>0 ";
            $total = get_BscPrsnTtl($srchFor, $srchIn, $orgID, $searchAll, $fltrTypValue, $fltrTyp, $extrWhere);
            if ($pageNo > ceil($total / $lmtSze)) {
                $pageNo = 1;
            } else if ($pageNo < 1) {
                $pageNo = ceil($total / $lmtSze);
            }

            $curIdx = $pageNo - 1;
            $result = get_BscPrsn(
                $srchFor,
                $srchIn,
                $curIdx,
                $lmtSze,
                $orgID,
                $searchAll,
                $sortBy,
                $fltrTypValue,
                $fltrTyp,
                $extrWhere
            );
            $cntr = 0;
        ?>
            <form id='dataAdminForm' action='' method='post' accept-charset='UTF-8'>
                <div class="row rhoRowMargin">
                    <div class="col-lg-2" style="padding:0px 1px 0px 15px !important;">
                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getBscProfile1Form('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'dtAdmnBscPrsnPrflForm', 'Add Person Basic Profile', -1, 24, 2, 'ADD')">
                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                            New Person
                        </button>
                    </div>
                    <div class="col-lg-3" style="padding:0px 15px 0px 15px !important;">
                        <div class="input-group">
                            <input class="form-control" id="dataAdminSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                            echo trim(str_replace("%", " ", $srchFor));
                                                                                                                            ?>" onkeyup="enterKeyFuncDtAdmn(event, '', '#allmodules', 'grp=8&typ=1&pg=5&vtyp=1')">
                            <input id="dataAdminPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                            <input id="allAdminsSrcForm" type="hidden" value="5">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getDataAdmin('clear', '#allmodules', 'grp=8&typ=1&pg=5&vtyp=1')">
                                <span class="glyphicon glyphicon-remove"></span>
                            </label>
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getDataAdmin('', '#allmodules', 'grp=8&typ=1&pg=5&vtyp=1')">
                                <span class="glyphicon glyphicon-search"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                            <select data-placeholder="Select..." class="form-control chosen-select" id="dataAdminSrchIn">
                                <?php
                                $valslctdArry = array("", "", "", "", "", "", "", "", "", "");
                                $srchInsArrys = array(
                                    "ID/Full Name", "Full Name", "Residential Address", "Contact Information", "Linked Firm/Workplace", "Person Type", "Date of Birth",
                                    "Home Town", "Gender", "Marital Status"
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
                            <select data-placeholder="Select..." class="form-control chosen-select" id="dataAdminDsplySze" style="min-width:70px !important;">
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
                    <div class="col-lg-2">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                            <select data-placeholder="Select..." class="form-control chosen-select" id="dataAdminSortBy">
                                <?php
                                $valslctdArry = array("", "", "", "", "");
                                $srchInsArrys = array("ID ASC", "ID DESC", "Date Added DESC", "Date of Birth", "Full Name");
                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    if ($srchIn == $srchInsArrys[$z]) {
                                        $valslctdArry[$z] = "selected";
                                    }
                                ?>
                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <nav aria-label="Page navigation">
                            <ul class="pagination" style="margin: 0px !important;">
                                <li>
                                    <a class="rhopagination" href="javascript:getDataAdmin('previous', '#allmodules', 'grp=8&typ=1&pg=5&vtyp=1');" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="rhopagination" href="javascript:getDataAdmin('next', '#allmodules', 'grp=8&typ=1&pg=5&vtyp=1');" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-responsive" id="dataAdminTable" cellspacing="0" width="100%" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <?php if ($canAddPrsn === true) { ?>
                                        <th>...</th>
                                    <?php } ?>
                                    <th>...</th>
                                    <th>ID No.</th>
                                    <th>Full Name</th>
                                    <th>Linked Firm</th>
                                    <th>Email</th>
                                    <th>Contact Nos.</th>
                                    <th>Address</th>
                                    <th>...</th>
                                    <?php if ($canVwRcHstry === true) { ?>
                                        <th>...</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = loc_db_fetch_array($result)) {
                                    /**/
                                    $cntr += 1;

                                    /* $childArray = array(
                                      'checked' => var_export($chckd, TRUE),
                                      'PersonID' => $row[0],
                                      'RowNum' => ($curIdx * $lmtSze) + ($cntr + 1),
                                      'LocIDNo' => $row[1],
                                      'FullName' => $row[2],
                                      'DateOfBirth' => $row[9],
                                      'WorkPlace' => str_replace("()", "", $row[22] . " (" . $row[24] . ")"),
                                      'Email' => $row[14],
                                      'TelNos' => trim($row[15] . "," . $row[16], ","),
                                      'PostalResAddress' => trim($row[13] . " " . $row[12], " "),
                                      'Title' => $row[25],
                                      'FirstName' => $row[4],
                                      'Surname' => $row[5],
                                      'OtherNames' => $row[6],
                                      'ImageLoc' => $nwFileName,
                                      'Gender' => $row[7],
                                      'MaritalStatus' => $row[8],
                                      'PlaceOfBirth' => $row[10],
                                      'Religion' => $row[11],
                                      'ResidentialAddress' => $row[12],
                                      'PostalAddress' => $row[13],
                                      'TelNo' => $row[15],
                                      'MobileNo' => $row[16],
                                      'FaxNo' => $row[17],
                                      'HomeTown' => $row[19],
                                      'Nationality' => $row[20],
                                      'LinkedFirmOrgID' => $row[21],
                                      'LinkedFirmSiteID' => $row[23],
                                      'LinkedFirmName' => $row[22],
                                      'LinkedSiteName' => $row[24],
                                      'PrsnType' => $row[26],
                                      'PrnTypRsn' => $row[27],
                                      'FurtherDetails' => $row[28],
                                      'StartDate' => $row[29],
                                      'EndDate' => $row[30]); */
                                ?>
                                    <tr id="dtAdmnBscPrsnPrflRow<?php echo $cntr; ?>">
                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                        <?php if ($canAddPrsn === true) { ?>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Basic Profile" onclick="getBscProfile1Form('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'dtAdmnBscPrsnPrflForm', 'View/Edit Person Basic Profile', <?php echo $row[0]; ?>, 0, 2, 'EDIT')" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                        <?php } ?>
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Print Profile" onclick="getPrsnProfilePDF(<?php echo $row[0]; ?>);" style="padding:2px !important;">
                                                <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                <img src="cmn_images/pdf.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                        </td>
                                        <td class="lovtd"><?php echo $row[1]; ?></td>
                                        <td class="lovtd"><?php echo $row[2]; ?></td>
                                        <td class="lovtd"><?php echo str_replace("()", "", $row[22] . " (" . $row[24] . ")"); ?></td>
                                        <td class="lovtd"><?php echo $row[14]; ?></td>
                                        <td class="lovtd"><?php echo trim($row[15] . ", " . $row[16], ", "); ?></td>
                                        <td class="lovtd"><?php echo trim($row[13] . " " . $row[12], " "); ?></td>
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Basic Profile" onclick="getBscProfile1Form('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'dtAdmnBscPrsnPrflForm', 'View Person Basic Profile', <?php echo $row[0]; ?>, 0, 1, 'VIEW')" style="padding:2px !important;">
                                                <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                        </td>
                                        <?php if ($canVwRcHstry === true) { ?>
                                            <td class="lovtd">
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
            </form>
<?php
        } else if ($vwtyp == 101) {
            //Print invoice rpt   
            $vPsblValID1 = getEnbldPssblValID("Html Person Profile Print File Name", getLovID("All Other Basic Person Setups"));
            $vPsblVal1 = getPssblValDesc($vPsblValID1);
            if ($vPsblVal1 == "") {
                $vPsblVal1 = 'htm_rpts/prs_prfl_rpt.php';
            }
            require $vPsblVal1;
        } else if ($vwtyp == 4) {
            //Get Selected Filter Details
            header("content-type:application/json");
            $dataAdminFilterBy = isset($_POST['dataAdminFilterBy']) ? cleanInputData($_POST['dataAdminFilterBy']) : "";
            $dataAdminFilterByVal = isset($_POST['dataAdminFilterByVal']) ? cleanInputData($_POST['dataAdminFilterByVal']) : "";
            $arr_content['FilterOptions'] = loadDataOptions($dataAdminFilterBy, $dataAdminFilterByVal);
            $errMsg = "Success";
            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $errMsg;
            echo json_encode($arr_content);
            exit();
        }
    }
}
?>