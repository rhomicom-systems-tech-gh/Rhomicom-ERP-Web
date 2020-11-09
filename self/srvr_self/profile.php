<?php
require 'srvr_self/intro_files/prfl_intro.php';
$menuItems = array("My Personal Profile", "My Leave of Absence", "Grade Progression Requests");
$menuImages = array("person.png", "absence_1.png", "bb_flow.gif");
$menuLinks = array("grp=50&typ=1&vtyp=0", "grp=50&typ=2&vtyp=0", "grp=50&typ=3&vtyp=0");
$vwtyp1 = 0;
//echo $vwtyp1;
$mdlNm = "Basic Person Data";
$ModuleName = $mdlNm;
$pageHtmlID = "prsnDataPage";

$dfltPrvldgs = array("View Person", "View Basic Person Data",
    /* 2 */ "View Curriculum Vitae", "View Basic Person Assignments",
    /* 4 */ "View Person Pay Item Assignments", "View SQL", "View Record History",
    /* 7 */ "Add Person Info", "Edit Person Info", "Delete Person Info",
    /* 10 */ "Add Basic Assignments", "Edit Basic Assignments", "Delete Basic Assignments",
    /* 13 */ "Add Pay Item Assignments", "Edit Pay Item Assignments", "Delete Pay Item Assignments", "View Banks",
    /* 17 */ "Define Assignment Templates", "Edit Assignment Templates", "Delete Assignment Templates",
    /* 20 */ "View Assignment Templates", "Manage My Firm",
    /* 22 */ "View Leave Management", "Add Leave Management", "Edit Leave Management", "Delete Leave Management",
    /* 26 */ "View Other Person's Absences");

$canview = test_prmssns($dfltPrvldgs[0], $mdlNm) || test_prmssns("View Self-Service", "Self Service");

$prsnid = $_SESSION['PRSN_ID'];
$orgID = $_SESSION['ORG_ID'];
$crntOrgName = getOrgName($orgID);
$usrID = $_SESSION['USRID'];
$uName = $_SESSION['UNAME'];

$vwtyp = "0";
$qstr = "";
$dsply = "";
$actyp = "";
$srchFor = "";
$srchIn = "Name";
$PKeyID = -1;
$fltrTypValue = "All";
$fltrTyp = "Relation Type";
$sortBy = "ID ASC";
$gnrlTrnsDteDMYHMS = getFrmtdDB_Date_time();
$gnrlTrnsDteYMDHMS = cnvrtDMYTmToYMDTm($gnrlTrnsDteDMYHMS);
$gnrlTrnsDteYMD = substr($gnrlTrnsDteYMDHMS, 0, 10);
if (isset($formArray)) {
    if (count($formArray) > 0) {
        $vwtyp = isset($formArray['vtyp']) ? cleanInputData($formArray['vtyp']) : "0";
        $qstr = isset($formArray['q']) ? cleanInputData($formArray['q']) : '';
    } else {
        $vwtyp = isset($_POST['vtyp']) ? cleanInputData($_POST['vtyp']) : "0";
    }
} else {
    $vwtyp = isset($_POST['vtyp']) ? cleanInputData($_POST['vtyp']) : "0";
}
if (isset($_POST['PKeyID'])) {
    $PKeyID = cleanInputData($_POST['PKeyID']);
}
if (isset($_POST['searchfor'])) {
    $srchFor = cleanInputData($_POST['searchfor']);
}
if (isset($_POST['searchin'])) {
    $srchIn = cleanInputData($_POST['searchin']);
}
if (isset($_POST['q'])) {
    $qstr = cleanInputData($_POST['q']);
}
if (isset($_POST['vtyp'])) {
    $vwtyp = cleanInputData($_POST['vtyp']);
}
if (isset($_POST['actyp'])) {
    $actyp = cleanInputData($_POST['actyp']);
}
if (isset($_POST['fltrTypValue'])) {
    $fltrTypValue = cleanInputData($_POST['fltrTypValue']);
}
if (isset($_POST['fltrTyp'])) {
    $fltrTyp = cleanInputData($_POST['fltrTyp']);
}
if (isset($_POST['sortBy'])) {
    $sortBy = cleanInputData($_POST['sortBy']);
}
if (strpos($srchFor, "%") === FALSE) {
    $srchFor = " " . $srchFor . " ";
    $srchFor = str_replace(" ", "%", $srchFor);
}
if (array_key_exists('lgn_num', get_defined_vars())) {
    $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
    $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
    $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";
    $curIdx = 0;
    $canAddPrsn = test_prmssns($dfltPrvldgs[7], $mdlNm);
    $canEdtPrsn = test_prmssns($dfltPrvldgs[8], $mdlNm);
    $canDelPrsn = test_prmssns($dfltPrvldgs[9], $mdlNm);
    $canview = test_prmssns($dfltPrvldgs[0], $mdlNm);
    $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? cleanInputData($_POST['sbmtdPersonID']) : -1;
    $addOrEdit = isset($_POST['addOrEdit']) ? cleanInputData($_POST['addOrEdit']) : 'VIEW';
    $prsnid = $_SESSION['PRSN_ID'];
    $orgID = $_SESSION['ORG_ID'];
    if (($canAddPrsn === true && $addOrEdit == "ADD") || ($canEdtPrsn === true && $addOrEdit == "EDIT") || ($canview === true && $addOrEdit == "VIEW")) {
        $dsplyMode = $addOrEdit;
    } else {
        $sbmtdPersonID = -1;
    }


    if ($qstr == "DELETE") {
        if ($actyp == 3) {
            $ntnlIDpKey = isset($_POST['ntnlIDpKey']) ? cleanInputData($_POST['ntnlIDpKey']) : -1;
            $srcForm = -1; //isset($_POST['srcForm']) ? cleanInputData($_POST['srcForm']) : -1;
            if ($srcForm <= 0) {
                echo deleteNtnlIDSelf($ntnlIDpKey);
                $rqstRslt = prsn_ChngRqst_Exist($prsnid);
                $rslt = get_RqstStatus($prsnid);
                if ($rqstRslt < 0) {
                    insert_ChangeRequest($prsnid);
                }
            } else {
                if ($canEdtPrsn || ($canMngMyFirm && $lnkdFirmID == $sbmtdPrsnFirmID && $lnkdFirmID > 0)) {
                    echo deleteNtnlID($ntnlIDpKey);
                } else {
                    restricted();
                }
            }
        } else if ($actyp == 5) {
            /* Divisions/Groups */
            //var_dump($_POST);
            $pDivGrpPkeyID = isset($_POST['pDivGrpPkeyID']) ? cleanInputData($_POST['pDivGrpPkeyID']) : -1;
            $srcForm = -1; //isset($_POST['srcForm']) ? cleanInputData($_POST['srcForm']) : -1;
            if ($canEdtPrsn || ($canMngMyFirm && $lnkdFirmID == $sbmtdPrsnFirmID && $lnkdFirmID > 0)) {
                echo deletePDivGrp($pDivGrpPkeyID);
            } else {
                restricted();
            }
        } else if ($actyp == 6) {
            /* Sites/Locations */
            //var_dump($_POST);
            $pSiteLocPkeyID = isset($_POST['pSiteLocPkeyID']) ? cleanInputData($_POST['pSiteLocPkeyID']) : -1;
            $srcForm = -1; //isset($_POST['srcForm']) ? cleanInputData($_POST['srcForm']) : -1;
            if ($canEdtPrsn || ($canMngMyFirm && $lnkdFirmID == $sbmtdPrsnFirmID && $lnkdFirmID > 0)) {
                echo deletePSiteLoc($pSiteLocPkeyID);
            } else {
                restricted();
            }
        } else if ($actyp == 7) {
            /* Grades */
            //var_dump($_POST);
            $pGradePkeyID = isset($_POST['pGradePkeyID']) ? cleanInputData($_POST['pGradePkeyID']) : -1;
            $srcForm = -1; //isset($_POST['srcForm']) ? cleanInputData($_POST['srcForm']) : -1;
            if ($canEdtPrsn || ($canMngMyFirm && $lnkdFirmID == $sbmtdPrsnFirmID && $lnkdFirmID > 0)) {
                echo deletePGrade($pGradePkeyID);
            } else {
                restricted();
            }
        } else if ($actyp == 8) {
            /* Supervisors */
            //var_dump($_POST);
            $pSuprvsrPkeyID = isset($_POST['pSuprvsrPkeyID']) ? cleanInputData($_POST['pSuprvsrPkeyID']) : -1;
            $srcForm = -1; //isset($_POST['srcForm']) ? cleanInputData($_POST['srcForm']) : -1;
            if ($canEdtPrsn || ($canMngMyFirm && $lnkdFirmID == $sbmtdPrsnFirmID && $lnkdFirmID > 0)) {
                echo deletePSuprvsr($pSuprvsrPkeyID);
            } else {
                restricted();
            }
        } else if ($actyp == 9) {
            /* Jobs */
            //var_dump($_POST);
            $pJobPkeyID = isset($_POST['pJobPkeyID']) ? cleanInputData($_POST['pJobPkeyID']) : -1;
            $srcForm = -1; //isset($_POST['srcForm']) ? cleanInputData($_POST['srcForm']) : -1;
            if ($canEdtPrsn || ($canMngMyFirm && $lnkdFirmID == $sbmtdPrsnFirmID && $lnkdFirmID > 0)) {
                echo deletePJob($pJobPkeyID);
            } else {
                restricted();
            }
        } else if ($actyp == 10) {
            /* Positions */
            //var_dump($_POST);
            $pPositionPkeyID = isset($_POST['pPositionPkeyID']) ? cleanInputData($_POST['pPositionPkeyID']) : -1;
            $srcForm = -1; //isset($_POST['srcForm']) ? cleanInputData($_POST['srcForm']) : -1;
            if ($canEdtPrsn || ($canMngMyFirm && $lnkdFirmID == $sbmtdPrsnFirmID && $lnkdFirmID > 0)) {
                echo deletePPosition($pPositionPkeyID);
            } else {
                restricted();
            }
        } else if ($actyp == 11) {
            $educBkgrdPkeyID = isset($_POST['educBkgrdPkeyID']) ? cleanInputData($_POST['educBkgrdPkeyID']) : -1;
            $srcForm = -1; //isset($_POST['srcForm']) ? cleanInputData($_POST['srcForm']) : -1;
            if ($srcForm <= 0) {
                echo deleteEducSelf($educBkgrdPkeyID);
                $rqstRslt = prsn_ChngRqst_Exist($prsnid);
                $rslt = get_RqstStatus($prsnid);
                if ($rqstRslt < 0) {
                    insert_ChangeRequest($prsnid);
                }
            } else {
                if ($canEdtPrsn || ($canMngMyFirm && $lnkdFirmID == $sbmtdPrsnFirmID && $lnkdFirmID > 0)) {
                    echo deleteEduc($educBkgrdPkeyID);
                } else {
                    restricted();
                }
            }
        } else if ($actyp == 12) {
            $workBkgrdPkeyID = isset($_POST['workBkgrdPkeyID']) ? cleanInputData($_POST['workBkgrdPkeyID']) : -1;
            $srcForm = -1; //isset($_POST['srcForm']) ? cleanInputData($_POST['srcForm']) : -1;
            if ($srcForm <= 0) {
                echo deleteWorkSelf($workBkgrdPkeyID);
                $rqstRslt = prsn_ChngRqst_Exist($prsnid);
                $rslt = get_RqstStatus($prsnid);
                if ($rqstRslt < 0) {
                    insert_ChangeRequest($prsnid);
                }
            } else {
                if ($canEdtPrsn || ($canMngMyFirm && $lnkdFirmID == $sbmtdPrsnFirmID && $lnkdFirmID > 0)) {
                    echo deleteWork($workBkgrdPkeyID);
                } else {
                    restricted();
                }
            }
        } else if ($actyp == 13) {
            $skillsPkeyID = isset($_POST['skillsPkeyID']) ? cleanInputData($_POST['skillsPkeyID']) : -1;
            $srcForm = -1; //isset($_POST['srcForm']) ? cleanInputData($_POST['srcForm']) : -1;
            if ($srcForm <= 0) {
                echo deleteSkillSelf($skillsPkeyID);
                $rqstRslt = prsn_ChngRqst_Exist($prsnid);
                $rslt = get_RqstStatus($prsnid);
                if ($rqstRslt < 0) {
                    insert_ChangeRequest($prsnid);
                }
            } else {
                if ($canEdtPrsn || ($canMngMyFirm && $lnkdFirmID == $sbmtdPrsnFirmID && $lnkdFirmID > 0)) {
                    echo deleteSkill($skillsPkeyID);
                } else {
                    restricted();
                }
            }
        } else if ($actyp == 15) {
            /* Delete Attachment */
            $attchmentID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
            $srcForm = -1; //isset($_POST['srcForm']) ? cleanInputData($_POST['srcForm']) : -1;
            if ($srcForm <= 0) {
                echo deletePrsnDocSelf($attchmentID);
                $rqstRslt = prsn_ChngRqst_Exist($prsnid);
                $rslt = get_RqstStatus($prsnid);
                if ($rqstRslt < 0) {
                    insert_ChangeRequest($prsnid);
                }
            } else {
                if ($canEdtPrsn || ($canMngMyFirm && $lnkdFirmID == $sbmtdPrsnFirmID && $lnkdFirmID > 0)) {
                    echo deletePrsnDoc($attchmentID);
                } else {
                    restricted();
                }
            }
        } else if ($actyp == 40) {
            $daChngRqstID = isset($_POST['daChngRqstID']) ? cleanInputData($_POST['daChngRqstID']) : -1;
            echo withdrawDataChngRqst($daChngRqstID);
        }
    } else if ($qstr == "UPDATE") {
        if ($actyp == 1) {
            //Self-Service
            header("content-type:application/json");
            $nwImgLoc = "";
            $orgid = $_SESSION['ORG_ID'];
            $inptDaPersonID = isset($_POST['daPersonID']) ? cleanInputData($_POST['daPersonID']) : -1;
            if ($inptDaPersonID != $prsnid) {
                $arr_content['percent'] = 100;
                $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is either Invalid or Incomplete!</span>";
                echo json_encode($arr_content);
                exit();
            }
            $daPrsnLocalID = isset($_POST['daPrsnLocalID']) ? cleanInputData($_POST['daPrsnLocalID']) : "";
            $daTitle = isset($_POST['daTitle']) ? cleanInputData($_POST['daTitle']) : "";
            $daFirstName = isset($_POST['daFirstName']) ? cleanInputData($_POST['daFirstName']) : "";
            $daOtherNames = isset($_POST['daOtherNames']) ? cleanInputData($_POST['daOtherNames']) : "";
            $daSurName = isset($_POST['daSurName']) ? cleanInputData($_POST['daSurName']) : "";
            $daGender = isset($_POST['daGender']) ? cleanInputData($_POST['daGender']) : "";
            $daMaritalStatus = isset($_POST['daMaritalStatus']) ? cleanInputData($_POST['daMaritalStatus']) : "";
            $daDOB = isset($_POST['daDOB']) ? cleanInputData($_POST['daDOB']) : "";
            $daNationality = isset($_POST['daNationality']) ? cleanInputData($_POST['daNationality']) : "";
            $daCompany = isset($_POST['daCompany']) ? cleanInputData($_POST['daCompany']) : "";
            $daCompanyID = getGnrlRecID2("scm.scm_cstmr_suplr", "cust_sup_name", "cust_sup_id", $daCompany);
            $daCompanyLoc = isset($_POST['daCompanyLoc']) ? cleanInputData($_POST['daCompanyLoc']) : "";
            $daCompanyLocID = getGnrlRecIDExtr("scm.scm_cstmr_suplr_sites", "site_name", "cust_supplier_id", "cust_sup_site_id", $daCompanyLoc, $daCompanyID);
            $daEmail = isset($_POST['daEmail']) ? cleanInputData($_POST['daEmail']) : "";
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
            $daMobileNos = isset($_POST['daMobileNos']) ? cleanInputData($_POST['daMobileNos']) : "";
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
            $daTelNos = isset($_POST['daTelNos']) ? cleanInputData($_POST['daTelNos']) : "";
            $daFaxNo = isset($_POST['daFaxNo']) ? cleanInputData($_POST['daFaxNo']) : "";

            $daPOB = isset($_POST['daPOB']) ? cleanInputData($_POST['daPOB']) : "";
            $daHomeTown = isset($_POST['daHomeTown']) ? cleanInputData($_POST['daHomeTown']) : "";
            $daReligion = isset($_POST['daReligion']) ? cleanInputData($_POST['daReligion']) : "";
            $daPostalAddress = isset($_POST['daPostalAddress']) ? cleanInputData($_POST['daPostalAddress']) : "";
            $daResAddress = isset($_POST['daResAddress']) ? cleanInputData($_POST['daResAddress']) : "";
            $daRelType = isset($_POST['daRelType']) ? cleanInputData($_POST['daRelType']) : "";
            $daRelCause = isset($_POST['daRelCause']) ? cleanInputData($_POST['daRelCause']) : "";
            $daRelDetails = isset($_POST['daRelDetails']) ? cleanInputData($_POST['daRelDetails']) : "";
            $daRelStartDate = isset($_POST['daRelStartDate']) ? cleanInputData($_POST['daRelStartDate']) : "";
            $daRelEndDate = isset($_POST['daRelEndDate']) ? cleanInputData($_POST['daRelEndDate']) : "";
            $shdSbmt = isset($_POST['shdSbmt']) ? cleanInputData($_POST['shdSbmt']) : 0;

            $addtnlPrsnDataCol1 = isset($_POST['addtnlPrsnDataCol1']) ? cleanInputData($_POST['addtnlPrsnDataCol1']) : "";
            $addtnlPrsnDataCol2 = isset($_POST['addtnlPrsnDataCol2']) ? cleanInputData($_POST['addtnlPrsnDataCol2']) : "";
            $addtnlPrsnDataCol3 = isset($_POST['addtnlPrsnDataCol3']) ? cleanInputData($_POST['addtnlPrsnDataCol3']) : "";
            $addtnlPrsnDataCol4 = isset($_POST['addtnlPrsnDataCol4']) ? cleanInputData($_POST['addtnlPrsnDataCol4']) : "";
            $addtnlPrsnDataCol5 = isset($_POST['addtnlPrsnDataCol5']) ? cleanInputData($_POST['addtnlPrsnDataCol5']) : "";
            $addtnlPrsnDataCol6 = isset($_POST['addtnlPrsnDataCol6']) ? cleanInputData($_POST['addtnlPrsnDataCol6']) : "";
            $addtnlPrsnDataCol7 = isset($_POST['addtnlPrsnDataCol7']) ? cleanInputData($_POST['addtnlPrsnDataCol7']) : "";
            $addtnlPrsnDataCol8 = isset($_POST['addtnlPrsnDataCol8']) ? cleanInputData($_POST['addtnlPrsnDataCol8']) : "";
            $addtnlPrsnDataCol9 = isset($_POST['addtnlPrsnDataCol9']) ? cleanInputData($_POST['addtnlPrsnDataCol9']) : "";
            $addtnlPrsnDataCol10 = isset($_POST['addtnlPrsnDataCol10']) ? cleanInputData($_POST['addtnlPrsnDataCol10']) : "";
            $addtnlPrsnDataCol11 = isset($_POST['addtnlPrsnDataCol11']) ? cleanInputData($_POST['addtnlPrsnDataCol11']) : "";
            $addtnlPrsnDataCol12 = isset($_POST['addtnlPrsnDataCol12']) ? cleanInputData($_POST['addtnlPrsnDataCol12']) : "";
            $addtnlPrsnDataCol13 = isset($_POST['addtnlPrsnDataCol13']) ? cleanInputData($_POST['addtnlPrsnDataCol13']) : "";
            $addtnlPrsnDataCol14 = isset($_POST['addtnlPrsnDataCol14']) ? cleanInputData($_POST['addtnlPrsnDataCol14']) : "";
            $addtnlPrsnDataCol15 = isset($_POST['addtnlPrsnDataCol15']) ? cleanInputData($_POST['addtnlPrsnDataCol15']) : "";
            $addtnlPrsnDataCol16 = isset($_POST['addtnlPrsnDataCol16']) ? cleanInputData($_POST['addtnlPrsnDataCol16']) : "";
            $addtnlPrsnDataCol17 = isset($_POST['addtnlPrsnDataCol17']) ? cleanInputData($_POST['addtnlPrsnDataCol17']) : "";
            $addtnlPrsnDataCol18 = isset($_POST['addtnlPrsnDataCol18']) ? cleanInputData($_POST['addtnlPrsnDataCol18']) : "";
            $addtnlPrsnDataCol19 = isset($_POST['addtnlPrsnDataCol19']) ? cleanInputData($_POST['addtnlPrsnDataCol19']) : "";
            $addtnlPrsnDataCol20 = isset($_POST['addtnlPrsnDataCol20']) ? cleanInputData($_POST['addtnlPrsnDataCol20']) : "";
            $addtnlPrsnDataCol21 = isset($_POST['addtnlPrsnDataCol21']) ? cleanInputData($_POST['addtnlPrsnDataCol21']) : "";
            $addtnlPrsnDataCol22 = isset($_POST['addtnlPrsnDataCol22']) ? cleanInputData($_POST['addtnlPrsnDataCol22']) : "";
            $addtnlPrsnDataCol23 = isset($_POST['addtnlPrsnDataCol23']) ? cleanInputData($_POST['addtnlPrsnDataCol23']) : "";
            $addtnlPrsnDataCol24 = isset($_POST['addtnlPrsnDataCol24']) ? cleanInputData($_POST['addtnlPrsnDataCol24']) : "";
            $addtnlPrsnDataCol25 = isset($_POST['addtnlPrsnDataCol25']) ? cleanInputData($_POST['addtnlPrsnDataCol25']) : "";
            $addtnlPrsnDataCol26 = isset($_POST['addtnlPrsnDataCol26']) ? cleanInputData($_POST['addtnlPrsnDataCol26']) : "";
            $addtnlPrsnDataCol27 = isset($_POST['addtnlPrsnDataCol27']) ? cleanInputData($_POST['addtnlPrsnDataCol27']) : "";
            $addtnlPrsnDataCol28 = isset($_POST['addtnlPrsnDataCol28']) ? cleanInputData($_POST['addtnlPrsnDataCol28']) : "";
            $addtnlPrsnDataCol29 = isset($_POST['addtnlPrsnDataCol29']) ? cleanInputData($_POST['addtnlPrsnDataCol29']) : "";
            $addtnlPrsnDataCol30 = isset($_POST['addtnlPrsnDataCol30']) ? cleanInputData($_POST['addtnlPrsnDataCol30']) : "";
            $addtnlPrsnDataCol31 = isset($_POST['addtnlPrsnDataCol31']) ? cleanInputData($_POST['addtnlPrsnDataCol31']) : "";
            $addtnlPrsnDataCol32 = isset($_POST['addtnlPrsnDataCol32']) ? cleanInputData($_POST['addtnlPrsnDataCol32']) : "";
            $addtnlPrsnDataCol33 = isset($_POST['addtnlPrsnDataCol33']) ? cleanInputData($_POST['addtnlPrsnDataCol33']) : "";
            $addtnlPrsnDataCol34 = isset($_POST['addtnlPrsnDataCol34']) ? cleanInputData($_POST['addtnlPrsnDataCol34']) : "";
            $addtnlPrsnDataCol35 = isset($_POST['addtnlPrsnDataCol35']) ? cleanInputData($_POST['addtnlPrsnDataCol35']) : "";
            $addtnlPrsnDataCol36 = isset($_POST['addtnlPrsnDataCol36']) ? cleanInputData($_POST['addtnlPrsnDataCol36']) : "";
            $addtnlPrsnDataCol37 = isset($_POST['addtnlPrsnDataCol37']) ? cleanInputData($_POST['addtnlPrsnDataCol37']) : "";
            $addtnlPrsnDataCol38 = isset($_POST['addtnlPrsnDataCol38']) ? cleanInputData($_POST['addtnlPrsnDataCol38']) : "";
            $addtnlPrsnDataCol39 = isset($_POST['addtnlPrsnDataCol39']) ? cleanInputData($_POST['addtnlPrsnDataCol39']) : "";
            $addtnlPrsnDataCol40 = isset($_POST['addtnlPrsnDataCol40']) ? cleanInputData($_POST['addtnlPrsnDataCol40']) : "";
            $addtnlPrsnDataCol41 = isset($_POST['addtnlPrsnDataCol41']) ? cleanInputData($_POST['addtnlPrsnDataCol41']) : "";
            $addtnlPrsnDataCol42 = isset($_POST['addtnlPrsnDataCol42']) ? cleanInputData($_POST['addtnlPrsnDataCol42']) : "";
            $addtnlPrsnDataCol43 = isset($_POST['addtnlPrsnDataCol43']) ? cleanInputData($_POST['addtnlPrsnDataCol43']) : "";
            $addtnlPrsnDataCol44 = isset($_POST['addtnlPrsnDataCol44']) ? cleanInputData($_POST['addtnlPrsnDataCol44']) : "";
            $addtnlPrsnDataCol45 = isset($_POST['addtnlPrsnDataCol45']) ? cleanInputData($_POST['addtnlPrsnDataCol45']) : "";
            $addtnlPrsnDataCol46 = isset($_POST['addtnlPrsnDataCol46']) ? cleanInputData($_POST['addtnlPrsnDataCol46']) : "";
            $addtnlPrsnDataCol47 = isset($_POST['addtnlPrsnDataCol47']) ? cleanInputData($_POST['addtnlPrsnDataCol47']) : "";
            $addtnlPrsnDataCol48 = isset($_POST['addtnlPrsnDataCol48']) ? cleanInputData($_POST['addtnlPrsnDataCol48']) : "";
            $addtnlPrsnDataCol49 = isset($_POST['addtnlPrsnDataCol49']) ? cleanInputData($_POST['addtnlPrsnDataCol49']) : "";
            $addtnlPrsnDataCol50 = isset($_POST['addtnlPrsnDataCol50']) ? cleanInputData($_POST['addtnlPrsnDataCol50']) : "";

            $oldDaPersonID = getPersonID($daPrsnLocalID);
            //$daTitle != "" &&
            if ($daPrsnLocalID != "" &&
                    $daFirstName != "" &&
                    $daSurName != "" &&
                    $daGender != "" &&
                    $daMaritalStatus != "" &&
                    $daDOB != "" &&
                    $daNationality != "" &&
                    ($oldDaPersonID > 0 && $oldDaPersonID == $inptDaPersonID)) {
                $newLocUpdateStr = "";

                if (is_numeric($daCompany)) {
                    $newLocUpdateStr = "', lnkd_firm_org_id='" . loc_db_escape_string($daCompany) .
                            "', lnkd_firm_site_id='" . loc_db_escape_string($daCompanyLoc) . "', new_company ='', new_company_loc='";
                } else {
                    $newLocUpdateStr = "', lnkd_firm_org_id=-1, lnkd_firm_site_id=-1, new_company ='" . loc_db_escape_string($daCompany) .
                            "', new_company_loc='" . loc_db_escape_string($daCompanyLoc);
                }
                $dateStr = getDB_Date_time();
                $sqlStr = "UPDATE self.self_prsn_names_nos
                SET title='" . loc_db_escape_string($daTitle) .
                        "',first_name='" . loc_db_escape_string($daFirstName) .
                        "', other_names='" . loc_db_escape_string($daOtherNames) .
                        "',sur_name='" . loc_db_escape_string($daSurName) .
                        "', pstl_addrs='" . loc_db_escape_string($daPostalAddress) .
                        "', email='" . loc_db_escape_string($daEmail) .
                        "', cntct_no_tel='" . loc_db_escape_string($daTelNos) .
                        "', gender='" . loc_db_escape_string($daGender) .
                        "', date_of_birth='" . cnvrtDMYToYMD($daDOB) .
                        "', place_of_birth='" . loc_db_escape_string($daPOB) .
                        "', nationality='" . loc_db_escape_string($daNationality) .
                        "', cntct_no_mobl='" . loc_db_escape_string($daMobileNos) .
                        "', res_address='" . loc_db_escape_string($daResAddress) .
                        "', marital_status='" . loc_db_escape_string($daMaritalStatus) .
                        "', hometown='" . loc_db_escape_string($daHomeTown) . $newLocUpdateStr .
                        "', last_update_by=" . $usrID . ", last_update_date='" . loc_db_escape_string($dateStr) . "' WHERE 1=1 AND person_id = " . $prsnid;

                execUpdtInsSQL($sqlStr);
                uploadDaImageSelf($inptDaPersonID, $nwImgLoc);

                $adDataExsts = 0;
                $data_cols = array("", $addtnlPrsnDataCol1, $addtnlPrsnDataCol2, $addtnlPrsnDataCol3, $addtnlPrsnDataCol4, $addtnlPrsnDataCol5,
                    $addtnlPrsnDataCol6, $addtnlPrsnDataCol7, $addtnlPrsnDataCol8, $addtnlPrsnDataCol9, $addtnlPrsnDataCol10,
                    $addtnlPrsnDataCol11, $addtnlPrsnDataCol12, $addtnlPrsnDataCol13, $addtnlPrsnDataCol14, $addtnlPrsnDataCol15, $addtnlPrsnDataCol16,
                    $addtnlPrsnDataCol17, $addtnlPrsnDataCol18, $addtnlPrsnDataCol19, $addtnlPrsnDataCol20,
                    $addtnlPrsnDataCol21, $addtnlPrsnDataCol22, $addtnlPrsnDataCol23, $addtnlPrsnDataCol24, $addtnlPrsnDataCol25, $addtnlPrsnDataCol26,
                    $addtnlPrsnDataCol27, $addtnlPrsnDataCol28, $addtnlPrsnDataCol29, $addtnlPrsnDataCol30,
                    $addtnlPrsnDataCol31, $addtnlPrsnDataCol32, $addtnlPrsnDataCol33, $addtnlPrsnDataCol34, $addtnlPrsnDataCol35, $addtnlPrsnDataCol36,
                    $addtnlPrsnDataCol37, $addtnlPrsnDataCol38, $addtnlPrsnDataCol39, $addtnlPrsnDataCol40,
                    $addtnlPrsnDataCol41, $addtnlPrsnDataCol42, $addtnlPrsnDataCol43, $addtnlPrsnDataCol44, $addtnlPrsnDataCol45, $addtnlPrsnDataCol46,
                    $addtnlPrsnDataCol47, $addtnlPrsnDataCol48, $addtnlPrsnDataCol49, $addtnlPrsnDataCol50);
                for ($y = 0; $y < count($data_cols); $y++) {
                    if ($data_cols[$y] != "") {
                        $adDataExsts++;
                    }
                }
                if ($adDataExsts > 0) {
                    updatePrsnExtrDataSelf($prsnid, $data_cols);
                }
                $rqstRslt = prsn_ChngRqst_Exist($prsnid);
                $rslt = get_RqstStatus($prsnid);
                if ($rqstRslt < 0) {
                    insert_ChangeRequest($prsnid);
                }
                $errMsg = "";
                if ($shdSbmt > 0) {
                    $prsnid = $_SESSION['PRSN_ID'];
                    $srcDocID = get_RqstID($prsnid);
                    $srcDocType = "Personal Records Change";
                    $routingID = -1;
                    $inptSlctdRtngs = "";
                    $actionToPrfrm = "Initiate";
                    $errMsg = "<br/><br/>" . dataChngReqMsgActns($routingID, $inptSlctdRtngs, $actionToPrfrm, $srcDocID, $srcDocType);
                }
                $arr_content['percent'] = 100;
                $arr_content['sbmt_message'] = $errMsg;
                $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>My Basic Data Successfully Saved!";
                echo json_encode($arr_content);
                exit();
            } else {
                $arr_content['percent'] = 100;
                $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is either Invalid or Incomplete!<br/>" . $nwImgLoc . "!</span>";
                echo json_encode($arr_content);
                exit();
            }
        } else if ($actyp == 2) {
            //Persons
            header("content-type:application/json");
            $orgid = $_SESSION['ORG_ID'];
            $nwImgLoc = "";
            $inptDaPersonID = isset($_POST['daPersonID']) ? cleanInputData($_POST['daPersonID']) : -1;
            if (!($canEdtPrsn || $canAddPrsn) && !($canMngMyFirm && $lnkdFirmID == $sbmtdPrsnFirmID && $lnkdFirmID > 0)) {
                $arr_content['percent'] = 100;
                $arr_content['daPersonID'] = $inptDaPersonID;
                $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                echo json_encode($arr_content);
                exit();
            }
            $daPrsnLocalID = isset($_POST['daPrsnLocalID']) ? cleanInputData($_POST['daPrsnLocalID']) : "";
            $daTitle = isset($_POST['daTitle']) ? cleanInputData($_POST['daTitle']) : "";
            $daFirstName = isset($_POST['daFirstName']) ? cleanInputData($_POST['daFirstName']) : "";
            $daOtherNames = isset($_POST['daOtherNames']) ? cleanInputData($_POST['daOtherNames']) : "";
            $daSurName = isset($_POST['daSurName']) ? cleanInputData($_POST['daSurName']) : "";
            $daGender = isset($_POST['daGender']) ? cleanInputData($_POST['daGender']) : "";
            $daMaritalStatus = isset($_POST['daMaritalStatus']) ? cleanInputData($_POST['daMaritalStatus']) : "Unknown";
            $daDOB = isset($_POST['daDOB']) ? cleanInputData($_POST['daDOB']) : "01-Jan-1970";
            $daNationality = isset($_POST['daNationality']) ? cleanInputData($_POST['daNationality']) : "Ghanaian";
            $daCompany = isset($_POST['daCompany']) ? cleanInputData($_POST['daCompany']) : "";
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
            $daCompanyLoc = isset($_POST['daCompanyLoc']) ? cleanInputData($_POST['daCompanyLoc']) : "";
            $daCompanyLocID = getGnrlRecIDExtr("scm.scm_cstmr_suplr_sites", "site_name", "cust_supplier_id", "cust_sup_site_id", $daCompanyLoc, $daCompanyID);
            if ($daCompanyLocID <= 0) {
                createQckCstmrSpplrSite($daCompanyLoc, $daCompanyID, $daCompany);
                $daCompanyLocID = getGnrlRecIDExtr("scm.scm_cstmr_suplr_sites", "site_name", "cust_supplier_id", "cust_sup_site_id", $daCompanyLoc, $daCompanyID);
            }
            $daEmail = isset($_POST['daEmail']) ? cleanInputData($_POST['daEmail']) : "";
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
            $daMobileNos = isset($_POST['daMobileNos']) ? cleanInputData($_POST['daMobileNos']) : "";
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
            $daTelNos = isset($_POST['daTelNos']) ? cleanInputData($_POST['daTelNos']) : "";
            $daFaxNo = isset($_POST['daFaxNo']) ? cleanInputData($_POST['daFaxNo']) : "";

            $daPOB = isset($_POST['daPOB']) ? cleanInputData($_POST['daPOB']) : "";
            $daHomeTown = isset($_POST['daHomeTown']) ? cleanInputData($_POST['daHomeTown']) : "";
            $daReligion = isset($_POST['daReligion']) ? cleanInputData($_POST['daReligion']) : "";
            $daPostalAddress = isset($_POST['daPostalAddress']) ? cleanInputData($_POST['daPostalAddress']) : "";
            $daResAddress = isset($_POST['daResAddress']) ? cleanInputData($_POST['daResAddress']) : "";
            $daRelType = isset($_POST['daRelType']) ? cleanInputData($_POST['daRelType']) : "Applicant";
            $daRelCause = isset($_POST['daRelCause']) ? cleanInputData($_POST['daRelCause']) : "New Enrolment";
            if ($daRelType == "Applicant") {
                /* if (strpos($daCompany, "Bank") !== FALSE) {
                  $daRelType = "PSB Data Capturer (Bank)";
                  } else {
                  $daRelType = "PSB Data Capturer (Telcos)";
                  } */
            }
            $daRelDetails = isset($_POST['daRelDetails']) ? cleanInputData($_POST['daRelDetails']) : "";
            $daRelStartDate = isset($_POST['daRelStartDate']) ? cleanInputData($_POST['daRelStartDate']) : substr($gnrlTrnsDteDMYHMS, 0, 11);
            $daRelEndDate = isset($_POST['daRelEndDate']) ? cleanInputData($_POST['daRelEndDate']) : "31-Dec-4000";
//echo $daRelStartDate;
            if ($daRelStartDate === "01-Jan-0001" || $daRelStartDate === "01-Jan-1" || trim($daRelStartDate) == "") {
                $daRelStartDate = substr($gnrlTrnsDteDMYHMS, 0, 11);
            }
            if ($daRelEndDate === "01-Jan-0001" || $daRelEndDate === "01-Jan-1" || trim($daRelEndDate) == "") {
                $daRelEndDate = "31-Dec-4000";
            }
            //echo $daRelStartDate . "<br/>";
            if ($daDOB != "") {
                $daDOB = cnvrtDMYToYMD($daDOB);
            }
            if ($daRelStartDate != "") {
                $daRelStartDate = cnvrtDMYToYMD($daRelStartDate);
            }
            if ($daRelEndDate != "") {
                $daRelEndDate = cnvrtDMYToYMD($daRelEndDate);
            }

            $addtnlPrsnDataCol1 = isset($_POST['addtnlPrsnDataCol1']) ? cleanInputData($_POST['addtnlPrsnDataCol1']) : "";
            $addtnlPrsnDataCol2 = isset($_POST['addtnlPrsnDataCol2']) ? cleanInputData($_POST['addtnlPrsnDataCol2']) : "";
            $addtnlPrsnDataCol3 = isset($_POST['addtnlPrsnDataCol3']) ? cleanInputData($_POST['addtnlPrsnDataCol3']) : "";
            $addtnlPrsnDataCol4 = isset($_POST['addtnlPrsnDataCol4']) ? cleanInputData($_POST['addtnlPrsnDataCol4']) : "";
            $addtnlPrsnDataCol5 = isset($_POST['addtnlPrsnDataCol5']) ? cleanInputData($_POST['addtnlPrsnDataCol5']) : "";
            $addtnlPrsnDataCol6 = isset($_POST['addtnlPrsnDataCol6']) ? cleanInputData($_POST['addtnlPrsnDataCol6']) : "";
            $addtnlPrsnDataCol7 = isset($_POST['addtnlPrsnDataCol7']) ? cleanInputData($_POST['addtnlPrsnDataCol7']) : "";
            $addtnlPrsnDataCol8 = isset($_POST['addtnlPrsnDataCol8']) ? cleanInputData($_POST['addtnlPrsnDataCol8']) : "";
            $addtnlPrsnDataCol9 = isset($_POST['addtnlPrsnDataCol9']) ? cleanInputData($_POST['addtnlPrsnDataCol9']) : "";
            $addtnlPrsnDataCol10 = isset($_POST['addtnlPrsnDataCol10']) ? cleanInputData($_POST['addtnlPrsnDataCol10']) : "";
            $addtnlPrsnDataCol11 = isset($_POST['addtnlPrsnDataCol11']) ? cleanInputData($_POST['addtnlPrsnDataCol11']) : "";
            $addtnlPrsnDataCol12 = isset($_POST['addtnlPrsnDataCol12']) ? cleanInputData($_POST['addtnlPrsnDataCol12']) : "";
            $addtnlPrsnDataCol13 = isset($_POST['addtnlPrsnDataCol13']) ? cleanInputData($_POST['addtnlPrsnDataCol13']) : "";
            $addtnlPrsnDataCol14 = isset($_POST['addtnlPrsnDataCol14']) ? cleanInputData($_POST['addtnlPrsnDataCol14']) : "";
            $addtnlPrsnDataCol15 = isset($_POST['addtnlPrsnDataCol15']) ? cleanInputData($_POST['addtnlPrsnDataCol15']) : "";
            $addtnlPrsnDataCol16 = isset($_POST['addtnlPrsnDataCol16']) ? cleanInputData($_POST['addtnlPrsnDataCol16']) : "";
            $addtnlPrsnDataCol17 = isset($_POST['addtnlPrsnDataCol17']) ? cleanInputData($_POST['addtnlPrsnDataCol17']) : "";
            $addtnlPrsnDataCol18 = isset($_POST['addtnlPrsnDataCol18']) ? cleanInputData($_POST['addtnlPrsnDataCol18']) : "";
            $addtnlPrsnDataCol19 = isset($_POST['addtnlPrsnDataCol19']) ? cleanInputData($_POST['addtnlPrsnDataCol19']) : "";
            $addtnlPrsnDataCol20 = isset($_POST['addtnlPrsnDataCol20']) ? cleanInputData($_POST['addtnlPrsnDataCol20']) : "";
            $addtnlPrsnDataCol21 = isset($_POST['addtnlPrsnDataCol21']) ? cleanInputData($_POST['addtnlPrsnDataCol21']) : "";
            $addtnlPrsnDataCol22 = isset($_POST['addtnlPrsnDataCol22']) ? cleanInputData($_POST['addtnlPrsnDataCol22']) : "";
            $addtnlPrsnDataCol23 = isset($_POST['addtnlPrsnDataCol23']) ? cleanInputData($_POST['addtnlPrsnDataCol23']) : "";
            $addtnlPrsnDataCol24 = isset($_POST['addtnlPrsnDataCol24']) ? cleanInputData($_POST['addtnlPrsnDataCol24']) : "";
            $addtnlPrsnDataCol25 = isset($_POST['addtnlPrsnDataCol25']) ? cleanInputData($_POST['addtnlPrsnDataCol25']) : "";
            $addtnlPrsnDataCol26 = isset($_POST['addtnlPrsnDataCol26']) ? cleanInputData($_POST['addtnlPrsnDataCol26']) : "";
            $addtnlPrsnDataCol27 = isset($_POST['addtnlPrsnDataCol27']) ? cleanInputData($_POST['addtnlPrsnDataCol27']) : "";
            $addtnlPrsnDataCol28 = isset($_POST['addtnlPrsnDataCol28']) ? cleanInputData($_POST['addtnlPrsnDataCol28']) : "";
            $addtnlPrsnDataCol29 = isset($_POST['addtnlPrsnDataCol29']) ? cleanInputData($_POST['addtnlPrsnDataCol29']) : "";
            $addtnlPrsnDataCol30 = isset($_POST['addtnlPrsnDataCol30']) ? cleanInputData($_POST['addtnlPrsnDataCol30']) : "";
            $addtnlPrsnDataCol31 = isset($_POST['addtnlPrsnDataCol31']) ? cleanInputData($_POST['addtnlPrsnDataCol31']) : "";
            $addtnlPrsnDataCol32 = isset($_POST['addtnlPrsnDataCol32']) ? cleanInputData($_POST['addtnlPrsnDataCol32']) : "";
            $addtnlPrsnDataCol33 = isset($_POST['addtnlPrsnDataCol33']) ? cleanInputData($_POST['addtnlPrsnDataCol33']) : "";
            $addtnlPrsnDataCol34 = isset($_POST['addtnlPrsnDataCol34']) ? cleanInputData($_POST['addtnlPrsnDataCol34']) : "";
            $addtnlPrsnDataCol35 = isset($_POST['addtnlPrsnDataCol35']) ? cleanInputData($_POST['addtnlPrsnDataCol35']) : "";
            $addtnlPrsnDataCol36 = isset($_POST['addtnlPrsnDataCol36']) ? cleanInputData($_POST['addtnlPrsnDataCol36']) : "";
            $addtnlPrsnDataCol37 = isset($_POST['addtnlPrsnDataCol37']) ? cleanInputData($_POST['addtnlPrsnDataCol37']) : "";
            $addtnlPrsnDataCol38 = isset($_POST['addtnlPrsnDataCol38']) ? cleanInputData($_POST['addtnlPrsnDataCol38']) : "";
            $addtnlPrsnDataCol39 = isset($_POST['addtnlPrsnDataCol39']) ? cleanInputData($_POST['addtnlPrsnDataCol39']) : "";
            $addtnlPrsnDataCol40 = isset($_POST['addtnlPrsnDataCol40']) ? cleanInputData($_POST['addtnlPrsnDataCol40']) : "";
            $addtnlPrsnDataCol41 = isset($_POST['addtnlPrsnDataCol41']) ? cleanInputData($_POST['addtnlPrsnDataCol41']) : "";
            $addtnlPrsnDataCol42 = isset($_POST['addtnlPrsnDataCol42']) ? cleanInputData($_POST['addtnlPrsnDataCol42']) : "";
            $addtnlPrsnDataCol43 = isset($_POST['addtnlPrsnDataCol43']) ? cleanInputData($_POST['addtnlPrsnDataCol43']) : "";
            $addtnlPrsnDataCol44 = isset($_POST['addtnlPrsnDataCol44']) ? cleanInputData($_POST['addtnlPrsnDataCol44']) : "";
            $addtnlPrsnDataCol45 = isset($_POST['addtnlPrsnDataCol45']) ? cleanInputData($_POST['addtnlPrsnDataCol45']) : "";
            $addtnlPrsnDataCol46 = isset($_POST['addtnlPrsnDataCol46']) ? cleanInputData($_POST['addtnlPrsnDataCol46']) : "";
            $addtnlPrsnDataCol47 = isset($_POST['addtnlPrsnDataCol47']) ? cleanInputData($_POST['addtnlPrsnDataCol47']) : "";
            $addtnlPrsnDataCol48 = isset($_POST['addtnlPrsnDataCol48']) ? cleanInputData($_POST['addtnlPrsnDataCol48']) : "";
            $addtnlPrsnDataCol49 = isset($_POST['addtnlPrsnDataCol49']) ? cleanInputData($_POST['addtnlPrsnDataCol49']) : "";
            $addtnlPrsnDataCol50 = isset($_POST['addtnlPrsnDataCol50']) ? cleanInputData($_POST['addtnlPrsnDataCol50']) : "";

            $oldDaPersonID = getPersonID($daPrsnLocalID);
            if ($daPrsnLocalID != "" &&
                    $daTitle != "" &&
                    $daFirstName != "" &&
                    $daSurName != "" &&
                    $daGender != "" &&
                    $daMaritalStatus != "" &&
                    $daDOB != "" &&
                    $daNationality != "" &&
                    $daRelType != "" &&
                    $daRelCause != "" &&
                    ($oldDaPersonID <= 0 || $oldDaPersonID == $inptDaPersonID)) {
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
                if ($inptDaPersonID > 0) {
                    if (isset($_FILES["daPrsnPicture"])) {
                        uploadDaImage($inptDaPersonID, $nwImgLoc);
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
                    for ($y = 0; $y < count($data_cols); $y++) {
                        if ($data_cols[$y] != "") {
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
                $arr_content['percent'] = 100;
                $arr_content['daPersonID'] = $inptDaPersonID;
                $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Person Records Successfully Saved!";
                echo json_encode($arr_content);
                exit();
            } else {
                $arr_content['percent'] = 100;
                $arr_content['daPersonID'] = $inptDaPersonID;
                $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Either the New Person ID No. is in Use <br/>or Data Supplied is Incomplete!<br/>" . $nwImgLoc . "!</span>";
                echo json_encode($arr_content);
                exit();
            }
        } else if ($actyp == 3) {
            $ntnlIDpKey = isset($_POST['ntnlIDpKey']) ? cleanInputData($_POST['ntnlIDpKey']) : -1;
            $srcForm = -1; // isset($_POST['srcForm']) ? cleanInputData($_POST['srcForm']) : -1;
            $ntnlIDPersonID = isset($_POST['ntnlIDPersonID']) ? cleanInputData($_POST['ntnlIDPersonID']) : -1;
            $ntnlIDCardsCountry = isset($_POST['ntnlIDCardsCountry']) ? cleanInputData($_POST['ntnlIDCardsCountry']) : "";
            $ntnlIDCardsIDTyp = isset($_POST['ntnlIDCardsIDTyp']) ? cleanInputData($_POST['ntnlIDCardsIDTyp']) : "";
            $ntnlIDCardsIDNo = isset($_POST['ntnlIDCardsIDNo']) ? cleanInputData($_POST['ntnlIDCardsIDNo']) : "";
            $ntnlIDCardsDateIssd = isset($_POST['ntnlIDCardsDateIssd']) ? cleanInputData($_POST['ntnlIDCardsDateIssd']) : "";
            $ntnlIDCardsExpDate = isset($_POST['ntnlIDCardsExpDate']) ? cleanInputData($_POST['ntnlIDCardsExpDate']) : "";
            $ntnlIDCardsOtherInfo = isset($_POST['ntnlIDCardsOtherInfo']) ? cleanInputData($_POST['ntnlIDCardsOtherInfo']) : "";
            $cntrRndm = getRandomNum(5000, 9999);
            $affctRws = 0;
            if ($srcForm <= 0) {
                if ($sbmtdPersonID != $prsnid) {
                    echo restricted();
                    exit();
                }
            } else {
                if (!($canEdtPrsn || $canAddPrsn) && !($canMngMyFirm && $lnkdFirmID == $sbmtdPrsnFirmID && $lnkdFirmID > 0)) {
                    echo restricted();
                    exit();
                }
            }
            if ($ntnlIDPersonID > 0 && $ntnlIDCardsCountry != "" && $ntnlIDCardsIDTyp != "" && $ntnlIDCardsIDNo != "" && ($srcForm > 0 || $ntnlIDPersonID == $prsnid)) {
                if (($srcForm <= 0 && $ntnlIDPersonID == $prsnid) || ($srcForm > 0)) {
                    if ($srcForm <= 0) {
                        $oldnNtnlIDpKey = getNtnltySelfID($ntnlIDPersonID, $ntnlIDCardsCountry, $ntnlIDCardsIDTyp);
                        if ($ntnlIDpKey > 0 && ($oldnNtnlIDpKey == $ntnlIDpKey || $oldnNtnlIDpKey <= 0)) {
                            $affctRws += updateNtnlIDSelf($ntnlIDpKey, $ntnlIDCardsCountry, $ntnlIDCardsIDTyp, $ntnlIDCardsIDNo, $ntnlIDCardsDateIssd, $ntnlIDCardsExpDate, $ntnlIDCardsOtherInfo);
                        } else {
                            $affctRws += createNtnlIDSelf($ntnlIDPersonID, $ntnlIDCardsCountry, $ntnlIDCardsIDTyp, $ntnlIDCardsIDNo, $ntnlIDCardsDateIssd, $ntnlIDCardsExpDate, $ntnlIDCardsOtherInfo);
                            $ntnlIDpKey = getNtnltySelfID($ntnlIDPersonID, $ntnlIDCardsCountry, $ntnlIDCardsIDTyp);
                        }
                    } else {
                        $oldnNtnlIDpKey = getNtnltyID($ntnlIDPersonID, $ntnlIDCardsCountry, $ntnlIDCardsIDTyp);
                        if ($ntnlIDpKey > 0 && ($oldnNtnlIDpKey == $ntnlIDpKey || $oldnNtnlIDpKey <= 0)) {
                            $affctRws += updateNtnlID($ntnlIDpKey, $ntnlIDCardsCountry, $ntnlIDCardsIDTyp, $ntnlIDCardsIDNo, $ntnlIDCardsDateIssd, $ntnlIDCardsExpDate, $ntnlIDCardsOtherInfo);
                        } else {
                            $affctRws += createNtnlID($ntnlIDPersonID, $ntnlIDCardsCountry, $ntnlIDCardsIDTyp, $ntnlIDCardsIDNo, $ntnlIDCardsDateIssd, $ntnlIDCardsExpDate, $ntnlIDCardsOtherInfo);
                            $ntnlIDpKey = getNtnltyID($ntnlIDPersonID, $ntnlIDCardsCountry, $ntnlIDCardsIDTyp);
                        }
                    }
                    if ($affctRws > 0) {
                        if ($srcForm <= 0) {
                            $rqstRslt = prsn_ChngRqst_Exist($prsnid);
                            $rslt = get_RqstStatus($prsnid);
                            if ($rqstRslt < 0) {
                                insert_ChangeRequest($prsnid);
                            }
                        }
                        ?>
                        <tr id="ntnlIDCardsRow_<?php echo $cntrRndm; ?>">
                            <td class="lovtd">
                                <button type="button" class="btn btn-default btn-sm" onclick="getNtnlIDForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'ntnlIDCardsForm', 'ntnlIDCardsRow_<?php echo $cntrRndm; ?>', 'Add/Edit National ID', 11, 'EDIT', <?php echo $ntnlIDpKey; ?>, <?php echo $ntnlIDPersonID; ?>);" style="padding:2px !important;">
                                    <img src="../cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                                <input type="hidden" value="<?php echo $ntnlIDpKey; ?>" id="ntnlIDCardsRow<?php echo $cntrRndm; ?>_NtnlIDpKey"/>                                
                            </td>
                            <td class="lovtd"><?php echo $ntnlIDCardsCountry; ?>
                            </td>
                            <td class="lovtd">
                                <?php echo $ntnlIDCardsIDTyp; ?>
                            </td>
                            <td class="lovtd"><?php echo $ntnlIDCardsIDNo; ?></td>
                            <td class="lovtd"><?php echo $ntnlIDCardsDateIssd; ?></td>
                            <td class="lovtd"><?php echo $ntnlIDCardsExpDate; ?></td>
                            <td class="lovtd"><?php echo $ntnlIDCardsOtherInfo; ?></td>
                            <td class="lovtd">
                                <button type="button" class="btn btn-default btn-sm" onclick="delNtnlID('ntnlIDCardsRow_<?php echo $cntrRndm; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete ID" style="padding:2px !important;" style="padding:2px !important;">
                                    <img src="../cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                            </td>
                        </tr>
                        <?php
                    } else {
                        echo "Failed to Save any Data!";
                    }
                } else {
                    echo "Invalid or Incomplete Data!";
                }
            } else {
                echo "Invalid or Incomplete Data!";
            }
        } else if ($actyp == 4) {
            $addtnlPrsPkey = isset($_POST['addtnlPrsPkey']) ? (float) cleanInputData($_POST['addtnlPrsPkey']) : -1;
            $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? (float) cleanInputData($_POST['sbmtdPersonID']) : -1;
            $extDtColNum = isset($_POST['extDtColNum']) ? (int) cleanInputData($_POST['extDtColNum']) : -1;
            $pipeSprtdFieldIDs = isset($_POST['allTblValues']) ? cleanInputData($_POST['allTblValues']) : -1;
            //var_dump($_POST);
            echo "<button onclick=\"$('#myFormsModal').modal('hide');\">Close</button>";
        } else if ($actyp == 5) {
            /* Divisions/Groups */
            //var_dump($_POST);
            $pDivGrpPkeyID = isset($_POST['pDivGrpPkeyID']) ? cleanInputData($_POST['pDivGrpPkeyID']) : -1;
            $srcForm = -1; //isset($_POST['srcForm']) ? cleanInputData($_POST['srcForm']) : -1;
            $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? cleanInputData($_POST['sbmtdPersonID']) : -1;
            if ($srcForm <= 0) {
                echo restricted();
                exit();
            } else {
                if (!($canEdtPrsn || $canAddPrsn) && !($canMngMyFirm && $lnkdFirmID == $sbmtdPrsnFirmID && $lnkdFirmID > 0)) {
                    echo restricted();
                    exit();
                }
            }
            $pDivGrpName = isset($_POST['pDivGrpName']) ? cleanInputData($_POST['pDivGrpName']) : "";
            $pDivGrpType = isset($_POST['pDivGrpType']) ? cleanInputData($_POST['pDivGrpType']) : "";
            $pDivGrpDivID = isset($_POST['pDivGrpDivID']) ? (int) cleanInputData($_POST['pDivGrpDivID']) : -1;
            $pDivGrpStartDate = isset($_POST['pDivGrpStartDate']) ? cleanInputData($_POST['pDivGrpStartDate']) : "";
            $pDivGrpEndDate = isset($_POST['pDivGrpEndDate']) ? cleanInputData($_POST['pDivGrpEndDate']) : "31-Dec-4000";
            if ($pDivGrpEndDate == "") {
                $pDivGrpEndDate = "31-Dec-4000";
            }
            if (trim($pDivGrpType) == "") {
                $pDivGrpType = getGnrlRecNm("org.org_divs_groups", "div_id", "gst.get_pssbl_val(div_typ_id)", $pDivGrpDivID);
            }
            $cntrRndm = getRandomNum(5000, 9999);
            $affctRws = 0;
            if ($sbmtdPersonID > 0 && $pDivGrpDivID > 0 && $pDivGrpStartDate != "" && ($srcForm > 0 || $sbmtdPersonID == $prsnid)) {
                if (($srcForm <= 0 && $sbmtdPersonID == $prsnid) || ($srcForm > 0)) {
                    $oldPKey = getPDivGrpID($sbmtdPersonID, $pDivGrpDivID);
                    if ($pDivGrpPkeyID > 0 && ($oldPKey == $pDivGrpPkeyID || $oldPKey <= 0)) {
                        $affctRws += updatePDivGrp($pDivGrpPkeyID, $pDivGrpDivID, $pDivGrpStartDate, $pDivGrpEndDate);
                    } else {
                        $affctRws += createPDivGrp($sbmtdPersonID, $pDivGrpDivID, $pDivGrpStartDate, $pDivGrpEndDate);
                        $pDivGrpPkeyID = getPDivGrpID($sbmtdPersonID, $pDivGrpDivID);
                    }

                    if ($affctRws > 0) {
                        ?>
                        <tr id="pDivGrpRow_<?php echo $cntrRndm; ?>">
                            <td class="lovtd">
                                <button type="button" class="btn btn-default btn-sm" onclick="getPDivGrpForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'pDivGrpForm', 'pDivGrpRow_<?php echo $cntrRndm; ?>', 'Edit Division/Group', 13, 'EDIT', <?php echo $pDivGrpPkeyID; ?>, <?php echo $sbmtdPersonID; ?>);" style="padding:2px !important;">
                                    <img src="../cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                                <input type="hidden" value="<?php echo $pDivGrpPkeyID; ?>" id="pDivGrpRow<?php echo $cntrRndm; ?>_PKeyID"/> 
                                <input type="hidden" value="<?php echo $pDivGrpDivID; ?>" id="pDivGrpRow<?php echo $cntrRndm; ?>_DivID"/>                                
                            </td>
                            <td class="lovtd"><?php echo $pDivGrpName; ?></td>
                            <td class="lovtd"><?php echo $pDivGrpType; ?></td>
                            <td class="lovtd"><?php echo $pDivGrpStartDate; ?></td>
                            <td class="lovtd"><?php echo $pDivGrpEndDate; ?></td>
                            <td class="lovtd">
                                <button type="button" class="btn btn-default btn-sm" onclick="delPDivGrpID('pDivGrpRow_<?php echo $cntrRndm; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Division/Group" style="padding:2px !important;" style="padding:2px !important;">
                                    <img src="../cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                            </td>
                        </tr>
                        <?php
                    } else {
                        echo "Error:Failed to Save any Data!";
                    }
                } else {
                    echo "Error:Invalid or Incomplete Data!";
                }
            } else {
                echo "Error:Invalid or Incomplete Data!";
            }
        } else if ($actyp == 6) {
            /* Sites/Locations */
            //var_dump($_POST);
            $pSiteLocPkeyID = isset($_POST['pSiteLocPkeyID']) ? cleanInputData($_POST['pSiteLocPkeyID']) : -1;
            $srcForm = -1; //isset($_POST['srcForm']) ? cleanInputData($_POST['srcForm']) : -1;
            $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? cleanInputData($_POST['sbmtdPersonID']) : -1;
            if ($srcForm <= 0) {
                echo restricted();
                exit();
            } else {
                if (!($canEdtPrsn || $canAddPrsn) && !($canMngMyFirm && $lnkdFirmID == $sbmtdPrsnFirmID && $lnkdFirmID > 0)) {
                    echo restricted();
                    exit();
                }
            }
            $pSiteLocName = isset($_POST['pSiteLocName']) ? cleanInputData($_POST['pSiteLocName']) : "";
            $pSiteLocType = isset($_POST['pSiteLocType']) ? cleanInputData($_POST['pSiteLocType']) : "";
            $pSiteLocID = isset($_POST['pSiteLocID']) ? (int) cleanInputData($_POST['pSiteLocID']) : -1;
            $pSiteLocStartDate = isset($_POST['pSiteLocStartDate']) ? cleanInputData($_POST['pSiteLocStartDate']) : "";
            $pSiteLocEndDate = isset($_POST['pSiteLocEndDate']) ? cleanInputData($_POST['pSiteLocEndDate']) : "31-Dec-4000";
            //var_dump($_POST);
            if ($pSiteLocEndDate == "") {
                $pSiteLocEndDate = "31-Dec-4000";
            }
            if (trim($pSiteLocType) == "") {
                $pSiteLocType = getGnrlRecNm("org.org_sites_locations", "location_id", "gst.get_pssbl_val(site_type_id)", $pSiteLocID);
            }
            $cntrRndm = getRandomNum(5000, 9999);
            $affctRws = 0;
            if ($sbmtdPersonID > 0 && $pSiteLocID > 0 && $pSiteLocStartDate != "" && ($srcForm > 0 || $sbmtdPersonID == $prsnid)) {
                if (($srcForm <= 0 && $sbmtdPersonID == $prsnid) || ($srcForm > 0)) {
                    $oldPKey = getPSiteLocID($sbmtdPersonID, $pSiteLocID);
                    if ($pSiteLocPkeyID > 0 && ($oldPKey == $pSiteLocPkeyID || $oldPKey <= 0)) {
                        $affctRws += updatePSiteLoc($pSiteLocPkeyID, $pSiteLocID, $pSiteLocStartDate, $pSiteLocEndDate);
                    } else {
                        $affctRws += createPSiteLoc($sbmtdPersonID, $pSiteLocID, $pSiteLocStartDate, $pSiteLocEndDate);
                        $pSiteLocPkeyID = getPSiteLocID($sbmtdPersonID, $pSiteLocID);
                    }

                    if ($affctRws > 0) {
                        ?>
                        <tr id="pSiteLocRow_<?php echo $cntrRndm; ?>">
                            <td class="lovtd">
                                <button type="button" class="btn btn-default btn-sm" onclick="getPSiteLocForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'pSiteLocForm', 'pSiteLocRow_<?php echo $cntrRndm; ?>', 'Edit Site/Location', 14, 'EDIT', <?php echo $pSiteLocPkeyID; ?>, <?php echo $sbmtdPersonID; ?>);" style="padding:2px !important;">
                                    <img src="../cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                                <input type="hidden" value="<?php echo $pSiteLocPkeyID; ?>" id="pSiteLocRow<?php echo $cntrRndm; ?>_PKeyID"/> 
                                <input type="hidden" value="<?php echo $pSiteLocID; ?>" id="pSiteLocRow<?php echo $cntrRndm; ?>_SiteLocID"/>                                
                            </td>
                            <td class="lovtd"><?php echo $pSiteLocName; ?></td>
                            <td class="lovtd"><?php echo $pSiteLocType; ?></td>
                            <td class="lovtd"><?php echo $pSiteLocStartDate; ?></td>
                            <td class="lovtd"><?php echo $pSiteLocEndDate; ?></td>
                            <td class="lovtd">
                                <button type="button" class="btn btn-default btn-sm" onclick="delPSiteLocID('pSiteLocRow_<?php echo $cntrRndm; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Site/Location" style="padding:2px !important;" style="padding:2px !important;">
                                    <img src="../cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                            </td>
                        </tr>
                        <?php
                    } else {
                        echo "Error:Failed to Save any Data!";
                    }
                } else {
                    echo "Error:Invalid or Incomplete Data!";
                }
            } else {
                echo "Error:Invalid or Incomplete Data!";
            }
        } else if ($actyp == 7) {
            /* Grades */
            //var_dump($_POST);
            $pGradePkeyID = isset($_POST['pGradePkeyID']) ? cleanInputData($_POST['pGradePkeyID']) : -1;
            $srcForm = -1; //isset($_POST['srcForm']) ? cleanInputData($_POST['srcForm']) : -1;
            $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? cleanInputData($_POST['sbmtdPersonID']) : -1;
            if ($srcForm <= 0) {
                echo restricted();
                exit();
            } else {
                if (!($canEdtPrsn || $canAddPrsn) && !($canMngMyFirm && $lnkdFirmID == $sbmtdPrsnFirmID && $lnkdFirmID > 0)) {
                    echo restricted();
                    exit();
                }
            }
            $pGradeName = isset($_POST['pGradeName']) ? cleanInputData($_POST['pGradeName']) : "";
            $pGradeType = isset($_POST['pGradeType']) ? cleanInputData($_POST['pGradeType']) : "";
            $pGradeID = isset($_POST['pGradeID']) ? (int) cleanInputData($_POST['pGradeID']) : -1;
            $pGradeStartDate = isset($_POST['pGradeStartDate']) ? cleanInputData($_POST['pGradeStartDate']) : "";
            $pGradeEndDate = isset($_POST['pGradeEndDate']) ? cleanInputData($_POST['pGradeEndDate']) : "31-Dec-4000";
            if ($pGradeEndDate == "") {
                $pGradeEndDate = "31-Dec-4000";
            }
            $cntrRndm = getRandomNum(5000, 9999);
            $affctRws = 0;
            if ($sbmtdPersonID > 0 && $pGradeID > 0 && $pGradeStartDate != "" && ($srcForm > 0 || $sbmtdPersonID == $prsnid)) {
                if (($srcForm <= 0 && $sbmtdPersonID == $prsnid) || ($srcForm > 0)) {
                    $oldPKey = getPGradeID($sbmtdPersonID, $pGradeID);
                    if ($pGradePkeyID > 0 && ($oldPKey == $pGradePkeyID || $oldPKey <= 0)) {
                        $affctRws += updatePGrade($pGradePkeyID, $pGradeID, $pGradeStartDate, $pGradeEndDate);
                    } else {
                        $affctRws += createPGrade($sbmtdPersonID, $pGradeID, $pGradeStartDate, $pGradeEndDate);
                        $pGradePkeyID = getPGradeID($sbmtdPersonID, $pGradeID);
                    }

                    if ($affctRws > 0) {
                        ?>
                        <tr id="pGradeRow_<?php echo $cntrRndm; ?>">
                            <td class="lovtd">
                                <button type="button" class="btn btn-default btn-sm" onclick="getPGradeForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'pGradeForm', 'pGradeRow_<?php echo $cntrRndm; ?>', 'Edit Grade', 15, 'EDIT', <?php echo $pGradePkeyID; ?>, <?php echo $sbmtdPersonID; ?>);" style="padding:2px !important;">
                                    <img src="../cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                                <input type="hidden" value="<?php echo $pGradePkeyID; ?>" id="pGradeRow<?php echo $cntrRndm; ?>_PKeyID"/> 
                                <input type="hidden" value="<?php echo $pGradeID; ?>" id="pGradeRow<?php echo $cntrRndm; ?>_GradeID"/>                                
                            </td>
                            <td class="lovtd"><?php echo $pGradeName; ?></td>
                            <td class="lovtd"><?php echo $pGradeStartDate; ?></td>
                            <td class="lovtd"><?php echo $pGradeEndDate; ?></td>
                            <td class="lovtd">
                                <button type="button" class="btn btn-default btn-sm" onclick="delPGradeID('pGradeRow_<?php echo $cntrRndm; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Grade" style="padding:2px !important;" style="padding:2px !important;">
                                    <img src="../cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                            </td>
                        </tr>
                        <?php
                    } else {
                        echo "Error:Failed to Save any Data!";
                    }
                } else {
                    echo "Error:Invalid or Incomplete Data!";
                }
            } else {
                echo "Error:Invalid or Incomplete Data!";
            }
        } else if ($actyp == 8) {
            /* Supervisors */
            //var_dump($_POST);
            $pSuprvsrPkeyID = isset($_POST['pSuprvsrPkeyID']) ? cleanInputData($_POST['pSuprvsrPkeyID']) : -1;
            $srcForm = -1; //isset($_POST['srcForm']) ? cleanInputData($_POST['srcForm']) : -1;
            $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? cleanInputData($_POST['sbmtdPersonID']) : -1;
            if ($srcForm <= 0) {
                echo restricted();
                exit();
            } else {
                if (!($canEdtPrsn || $canAddPrsn) && !($canMngMyFirm && $lnkdFirmID == $sbmtdPrsnFirmID && $lnkdFirmID > 0)) {
                    echo restricted();
                    exit();
                }
            }
            $pSuprvsrName = isset($_POST['pSuprvsrName']) ? cleanInputData($_POST['pSuprvsrName']) : "";
            $pSuprvsrType = isset($_POST['pSuprvsrType']) ? cleanInputData($_POST['pSuprvsrType']) : "";
            $pSuprvsrID = isset($_POST['pSuprvsrID']) ? (int) cleanInputData($_POST['pSuprvsrID']) : -1;
            $pSuprvsrStartDate = isset($_POST['pSuprvsrStartDate']) ? cleanInputData($_POST['pSuprvsrStartDate']) : "";
            $pSuprvsrEndDate = isset($_POST['pSuprvsrEndDate']) ? cleanInputData($_POST['pSuprvsrEndDate']) : "31-Dec-4000";
            if ($pSuprvsrEndDate == "") {
                $pSuprvsrEndDate = "31-Dec-4000";
            }
            $cntrRndm = getRandomNum(5000, 9999);
            $affctRws = 0;
            if ($sbmtdPersonID > 0 && $pSuprvsrID > 0 && $pSuprvsrStartDate != "" && ($srcForm > 0 || $sbmtdPersonID == $prsnid)) {
                if (($srcForm <= 0 && $sbmtdPersonID == $prsnid) || ($srcForm > 0)) {
                    $oldPKey = getPSuprvsrID($sbmtdPersonID, $pSuprvsrID);
                    if ($pSuprvsrPkeyID > 0 && ($oldPKey == $pSuprvsrPkeyID || $oldPKey <= 0)) {
                        $affctRws += updatePSuprvsr($pSuprvsrPkeyID, $pSuprvsrID, $pSuprvsrStartDate, $pSuprvsrEndDate);
                    } else {
                        $affctRws += createPSuprvsr($sbmtdPersonID, $pSuprvsrID, $pSuprvsrStartDate, $pSuprvsrEndDate);
                        $pSuprvsrPkeyID = getPSuprvsrID($sbmtdPersonID, $pSuprvsrID);
                    }

                    if ($affctRws > 0) {
                        ?>
                        <tr id="pSuprvsrRow_<?php echo $cntrRndm; ?>">
                            <td class="lovtd">
                                <button type="button" class="btn btn-default btn-sm" onclick="getPSuprvsrForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'pSuprvsrForm', 'pSuprvsrRow_<?php echo $cntrRndm; ?>', 'Edit Supervisor', 16, 'EDIT', <?php echo $pSuprvsrPkeyID; ?>, <?php echo $sbmtdPersonID; ?>);" style="padding:2px !important;">
                                    <img src="../cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                                <input type="hidden" value="<?php echo $pSuprvsrPkeyID; ?>" id="pSuprvsrRow<?php echo $cntrRndm; ?>_PKeyID"/> 
                                <input type="hidden" value="<?php echo $pSuprvsrID; ?>" id="pSuprvsrRow<?php echo $cntrRndm; ?>_SuprvsrID"/>                                
                            </td>
                            <td class="lovtd"><?php echo $pSuprvsrName; ?></td>
                            <td class="lovtd"><?php echo $pSuprvsrStartDate; ?></td>
                            <td class="lovtd"><?php echo $pSuprvsrEndDate; ?></td>
                            <td class="lovtd">
                                <button type="button" class="btn btn-default btn-sm" onclick="delPSuprvsrID('pSuprvsrRow_<?php echo $cntrRndm; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Supervisor" style="padding:2px !important;" style="padding:2px !important;">
                                    <img src="../cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                            </td>
                        </tr>
                        <?php
                    } else {
                        echo "Error:Failed to Save any Data!";
                    }
                } else {
                    echo "Error:Invalid or Incomplete Data!";
                }
            } else {
                echo "Error:Invalid or Incomplete Data!";
            }
        } else if ($actyp == 9) {
            /* Jobs */
            //var_dump($_POST);
            $pJobPkeyID = isset($_POST['pJobPkeyID']) ? cleanInputData($_POST['pJobPkeyID']) : -1;
            $srcForm = -1; //isset($_POST['srcForm']) ? cleanInputData($_POST['srcForm']) : -1;
            $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? cleanInputData($_POST['sbmtdPersonID']) : -1;
            if ($srcForm <= 0) {
                echo restricted();
                exit();
            } else {
                if (!($canEdtPrsn || $canAddPrsn) && !($canMngMyFirm && $lnkdFirmID == $sbmtdPrsnFirmID && $lnkdFirmID > 0)) {
                    echo restricted();
                    exit();
                }
            }
            $pJobName = isset($_POST['pJobName']) ? cleanInputData($_POST['pJobName']) : "";
            $pJobType = isset($_POST['pJobType']) ? cleanInputData($_POST['pJobType']) : "";
            $pJobID = isset($_POST['pJobID']) ? (int) cleanInputData($_POST['pJobID']) : -1;
            $pJobStartDate = isset($_POST['pJobStartDate']) ? cleanInputData($_POST['pJobStartDate']) : "";
            $pJobEndDate = isset($_POST['pJobEndDate']) ? cleanInputData($_POST['pJobEndDate']) : "31-Dec-4000";
            if ($pJobEndDate == "") {
                $pJobEndDate = "31-Dec-4000";
            }
            $cntrRndm = getRandomNum(5000, 9999);
            $affctRws = 0;
            if ($sbmtdPersonID > 0 && $pJobID > 0 && $pJobStartDate != "" && ($srcForm > 0 || $sbmtdPersonID == $prsnid)) {
                if (($srcForm <= 0 && $sbmtdPersonID == $prsnid) || ($srcForm > 0)) {
                    $oldPKey = getPJobID($sbmtdPersonID, $pJobID);
                    if ($pJobPkeyID > 0 && ($oldPKey == $pJobPkeyID || $oldPKey <= 0)) {
                        $affctRws += updatePJob($pJobPkeyID, $pJobID, $pJobStartDate, $pJobEndDate);
                    } else {
                        $affctRws += createPJob($sbmtdPersonID, $pJobID, $pJobStartDate, $pJobEndDate);
                        $pJobPkeyID = getPJobID($sbmtdPersonID, $pJobID);
                    }
                    if ($affctRws > 0) {
                        ?>
                        <tr id="pJobRow_<?php echo $cntrRndm; ?>">
                            <td class="lovtd">
                                <button type="button" class="btn btn-default btn-sm" onclick="getPJobForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'pJobForm', 'pJobRow_<?php echo $cntrRndm; ?>', 'Edit Job', 17, 'EDIT', <?php echo $pJobPkeyID; ?>, <?php echo $sbmtdPersonID; ?>);" style="padding:2px !important;">
                                    <img src="../cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                                <input type="hidden" value="<?php echo $pJobPkeyID; ?>" id="pJobRow<?php echo $cntrRndm; ?>_PKeyID"/> 
                                <input type="hidden" value="<?php echo $pJobID; ?>" id="pJobRow<?php echo $cntrRndm; ?>_JobID"/>                                
                            </td>
                            <td class="lovtd"><?php echo $pJobName; ?></td>
                            <td class="lovtd"><?php echo $pJobStartDate; ?></td>
                            <td class="lovtd"><?php echo $pJobEndDate; ?></td>
                            <td class="lovtd">
                                <button type="button" class="btn btn-default btn-sm" onclick="delPJobID('pJobRow_<?php echo $cntrRndm; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Job" style="padding:2px !important;" style="padding:2px !important;">
                                    <img src="../cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                            </td>
                        </tr>
                        <?php
                    } else {
                        echo "Error:Failed to Save any Data!";
                    }
                } else {
                    echo "Error:Invalid or Incomplete Data!";
                }
            } else {
                echo "Error:Invalid or Incomplete Data!";
            }
        } else if ($actyp == 10) {
            /* Positions */
            //var_dump($_POST);
            $pPositionPkeyID = isset($_POST['pPositionPkeyID']) ? cleanInputData($_POST['pPositionPkeyID']) : -1;
            $srcForm = -1; //isset($_POST['srcForm']) ? cleanInputData($_POST['srcForm']) : -1;
            $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? cleanInputData($_POST['sbmtdPersonID']) : -1;
            if ($srcForm <= 0) {
                echo restricted();
                exit();
            } else {
                if (!($canEdtPrsn || $canAddPrsn) && !($canMngMyFirm && $lnkdFirmID == $sbmtdPrsnFirmID && $lnkdFirmID > 0)) {
                    echo restricted();
                    exit();
                }
            }
            $pPositionName = isset($_POST['pPositionName']) ? cleanInputData($_POST['pPositionName']) : "";
            $pPositionDivNm = isset($_POST['pPositionDivNm']) ? cleanInputData($_POST['pPositionDivNm']) : "";
            $pPositionID = isset($_POST['pPositionID']) ? (int) cleanInputData($_POST['pPositionID']) : -1;
            $pPositionDivID = isset($_POST['pPositionDivID']) ? (int) cleanInputData($_POST['pPositionDivID']) : -1;
            $pPositionStartDate = isset($_POST['pPositionStartDate']) ? cleanInputData($_POST['pPositionStartDate']) : "";
            $pPositionEndDate = isset($_POST['pPositionEndDate']) ? cleanInputData($_POST['pPositionEndDate']) : "31-Dec-4000";
            if ($pPositionEndDate == "") {
                $pPositionEndDate = "31-Dec-4000";
            }
            $cntrRndm = getRandomNum(5000, 9999);
            $affctRws = 0;
            if ($sbmtdPersonID > 0 && $pPositionID > 0 && $pPositionStartDate != "" && ($srcForm > 0 || $sbmtdPersonID == $prsnid)) {
                if (($srcForm <= 0 && $sbmtdPersonID == $prsnid) || ($srcForm > 0)) {
                    $oldPKey = getPPositionID($sbmtdPersonID, $pPositionID, $pPositionDivID);
                    if ($pPositionPkeyID > 0 && ($oldPKey == $pPositionPkeyID || $oldPKey <= 0)) {
                        $affctRws += updatePPosition($pPositionPkeyID, $pPositionID, $pPositionStartDate, $pPositionEndDate, $pPositionDivID);
                    } else {
                        $affctRws += createPPosition($sbmtdPersonID, $pPositionID, $pPositionStartDate, $pPositionEndDate, $pPositionDivID);
                        $pPositionPkeyID = getPPositionID($sbmtdPersonID, $pPositionID, $pPositionDivID);
                    }
                    if ($affctRws > 0) {
                        ?>
                        <tr id="pPositionRow_<?php echo $cntrRndm; ?>">
                            <td class="lovtd">
                                <button type="button" class="btn btn-default btn-sm" onclick="getPPositionForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'pPositionForm', 'pPositionRow_<?php echo $cntrRndm; ?>', 'Edit Position', 18, 'EDIT', <?php echo $pPositionPkeyID; ?>, <?php echo $sbmtdPersonID; ?>);" style="padding:2px !important;">
                                    <img src="../cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                                <input type="hidden" value="<?php echo $pPositionPkeyID; ?>" id="pPositionRow<?php echo $cntrRndm; ?>_PKeyID"/> 
                                <input type="hidden" value="<?php echo $pPositionID; ?>" id="pPositionRow<?php echo $cntrRndm; ?>_PositionID"/>
                                <input type="hidden" value="<?php echo $pPositionDivID; ?>" id="pPositionRow<?php echo $cntrRndm; ?>_PositionDivID"/>                                 
                            </td>
                            <td class="lovtd"><?php echo $pPositionName; ?></td>
                            <td class="lovtd"><?php echo $pPositionDivNm; ?></td>
                            <td class="lovtd"><?php echo $pPositionStartDate; ?></td>
                            <td class="lovtd"><?php echo $pPositionEndDate; ?></td>
                            <td class="lovtd">
                                <button type="button" class="btn btn-default btn-sm" onclick="delPPositionID('pPositionRow_<?php echo $cntrRndm; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Position" style="padding:2px !important;" style="padding:2px !important;">
                                    <img src="../cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                            </td>
                        </tr>
                        <?php
                    } else {
                        echo "Error:Failed to Save any Data!";
                    }
                } else {
                    echo "Error:Invalid or Incomplete Data!";
                }
            } else {
                echo "Error:Invalid or Incomplete Data!";
            }
        } else if ($actyp == 11) {
            /* Educational Background */
            $educBkgrdPkeyID = isset($_POST['educBkgrdPkeyID']) ? cleanInputData($_POST['educBkgrdPkeyID']) : -1;
            $srcForm = -1; //isset($_POST['srcForm']) ? cleanInputData($_POST['srcForm']) : -1;
            $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? cleanInputData($_POST['sbmtdPersonID']) : -1;
            if ($srcForm <= 0) {
                if ($sbmtdPersonID != $prsnid) {
                    echo restricted();
                    exit();
                }
            } else {
                if (!($canEdtPrsn || $canAddPrsn) && !($canMngMyFirm && $lnkdFirmID == $sbmtdPrsnFirmID && $lnkdFirmID > 0)) {
                    echo restricted();
                    exit();
                }
            }
            $educBkgrdCourseName = isset($_POST['educBkgrdCourseName']) ? cleanInputData($_POST['educBkgrdCourseName']) : "";
            $educBkgrdSchool = isset($_POST['educBkgrdSchool']) ? cleanInputData($_POST['educBkgrdSchool']) : "";
            $educBkgrdLoc = isset($_POST['educBkgrdLoc']) ? cleanInputData($_POST['educBkgrdLoc']) : "";
            $educBkgrdStartDate = isset($_POST['educBkgrdStartDate']) ? cleanInputData($_POST['educBkgrdStartDate']) : "";
            $educBkgrdEndDate = isset($_POST['educBkgrdEndDate']) ? cleanInputData($_POST['educBkgrdEndDate']) : "31-Dec-4000";
            if ($educBkgrdEndDate == "") {
                $educBkgrdEndDate = "31-Dec-4000";
            }
            $educBkgrdCertObtnd = isset($_POST['educBkgrdCertObtnd']) ? cleanInputData($_POST['educBkgrdCertObtnd']) : "";
            $educBkgrdCertTyp = isset($_POST['educBkgrdCertTyp']) ? cleanInputData($_POST['educBkgrdCertTyp']) : "";
            $educBkgrdDateAwrded = isset($_POST['educBkgrdDateAwrded']) ? cleanInputData($_POST['educBkgrdDateAwrded']) : "";
            $cntrRndm = getRandomNum(5000, 9999);
            $affctRws = 0;
            if ($sbmtdPersonID > 0 && $educBkgrdCourseName != "" && $educBkgrdSchool != "" && $educBkgrdLoc != "" && $educBkgrdStartDate != "" && ($srcForm > 0 || $sbmtdPersonID == $prsnid)) {
                if (($srcForm <= 0 && $sbmtdPersonID == $prsnid) || ($srcForm > 0)) {
                    if ($srcForm <= 0) {
                        $oldPKey = getEducSelfID($sbmtdPersonID, $educBkgrdCourseName, $educBkgrdSchool);
                        if ($educBkgrdPkeyID > 0 && ($oldPKey == $educBkgrdPkeyID || $oldPKey <= 0)) {
                            $affctRws += updateEducSelf($educBkgrdPkeyID, $educBkgrdCourseName, $educBkgrdSchool, $educBkgrdLoc, $educBkgrdCertObtnd, $educBkgrdStartDate, $educBkgrdEndDate,
                                    $educBkgrdDateAwrded, $educBkgrdCertTyp);
                        } else {
                            $affctRws += createEducSelf($sbmtdPersonID, $educBkgrdCourseName, $educBkgrdSchool, $educBkgrdLoc, $educBkgrdCertObtnd, $educBkgrdStartDate, $educBkgrdEndDate,
                                    $educBkgrdDateAwrded, $educBkgrdCertTyp);
                            $educBkgrdPkeyID = getEducSelfID($sbmtdPersonID, $educBkgrdCourseName, $educBkgrdSchool);
                        }
                    } else {
                        $oldPKey = getEducID($sbmtdPersonID, $educBkgrdCourseName, $educBkgrdSchool);
                        if ($educBkgrdPkeyID > 0 && ($oldPKey == $educBkgrdPkeyID || $oldPKey <= 0)) {
                            $affctRws += updateEduc($educBkgrdPkeyID, $educBkgrdCourseName, $educBkgrdSchool, $educBkgrdLoc, $educBkgrdCertObtnd, $educBkgrdStartDate, $educBkgrdEndDate,
                                    $educBkgrdDateAwrded, $educBkgrdCertTyp);
                        } else {
                            $affctRws += createEduc($sbmtdPersonID, $educBkgrdCourseName, $educBkgrdSchool, $educBkgrdLoc, $educBkgrdCertObtnd, $educBkgrdStartDate, $educBkgrdEndDate,
                                    $educBkgrdDateAwrded, $educBkgrdCertTyp);
                            $educBkgrdPkeyID = getEducID($sbmtdPersonID, $educBkgrdCourseName, $educBkgrdSchool);
                        }
                    }
                    if ($affctRws > 0) {
                        if ($srcForm <= 0) {
                            $rqstRslt = prsn_ChngRqst_Exist($prsnid);
                            $rslt = get_RqstStatus($prsnid);
                            if ($rqstRslt < 0) {
                                insert_ChangeRequest($prsnid);
                            }
                        }
                        ?>
                        <tr id="educBkgrdRow_<?php echo $cntrRndm; ?>">
                            <td class="lovtd">
                                <button type="button" class="btn btn-default btn-sm" onclick="getEducBkgrdForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'educBkgrdForm', 'educBkgrdRow_<?php echo $cntrRndm; ?>', 'Add/Edit Educational Background', 20, 'EDIT', <?php echo $educBkgrdPkeyID; ?>, <?php echo $sbmtdPersonID; ?>);" style="padding:2px !important;">
                                    <img src="../cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                                <input type="hidden" value="<?php echo $educBkgrdPkeyID; ?>" id="educBkgrdRow<?php echo $cntrRndm; ?>_PKeyID"/>                                
                            </td>
                            <td class="lovtd"><?php echo $educBkgrdCourseName; ?></td>
                            <td class="lovtd"><?php echo $educBkgrdSchool; ?></td>
                            <td class="lovtd"><?php echo $educBkgrdLoc; ?></td>
                            <td class="lovtd"><?php echo $educBkgrdStartDate; ?></td>
                            <td class="lovtd"><?php echo $educBkgrdEndDate; ?></td>
                            <td class="lovtd"><?php echo $educBkgrdCertObtnd; ?></td>
                            <td class="lovtd"><?php echo $educBkgrdCertTyp; ?></td>
                            <td class="lovtd"><?php echo $educBkgrdDateAwrded; ?></td>
                            <td class="lovtd">
                                <button type="button" class="btn btn-default btn-sm" onclick="delEducID('educBkgrdRow_<?php echo $cntrRndm; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Educ. Background" style="padding:2px !important;" style="padding:2px !important;">
                                    <img src="../cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                            </td>
                        </tr>
                        <?php
                    } else {
                        echo "Error:Failed to Save any Data!";
                    }
                } else {
                    echo "Error:Invalid or Incomplete Data!";
                }
            } else {
                echo "Error:Invalid or Incomplete Data!";
            }
        } else if ($actyp == 12) {
            /* Work Background */
            $workBkgrdPkeyID = isset($_POST['workBkgrdPkeyID']) ? cleanInputData($_POST['workBkgrdPkeyID']) : -1;
            $srcForm = -1; //isset($_POST['srcForm']) ? cleanInputData($_POST['srcForm']) : -1;
            $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? cleanInputData($_POST['sbmtdPersonID']) : -1;
            if ($srcForm <= 0) {
                if ($sbmtdPersonID != $prsnid) {
                    echo restricted();
                    exit();
                }
            } else {
                if (!($canEdtPrsn || $canAddPrsn) && !($canMngMyFirm && $lnkdFirmID == $sbmtdPrsnFirmID && $lnkdFirmID > 0)) {
                    echo restricted();
                    exit();
                }
            }
            $workBkgrdJobName = isset($_POST['workBkgrdJobName']) ? cleanInputData($_POST['workBkgrdJobName']) : "";
            $workBkgrdInstitution = isset($_POST['workBkgrdInstitution']) ? cleanInputData($_POST['workBkgrdInstitution']) : "";
            $workBkgrdLoc = isset($_POST['workBkgrdLoc']) ? cleanInputData($_POST['workBkgrdLoc']) : "";
            $workBkgrdStartDate = isset($_POST['workBkgrdStartDate']) ? cleanInputData($_POST['workBkgrdStartDate']) : "";
            $workBkgrdEndDate = isset($_POST['workBkgrdEndDate']) ? cleanInputData($_POST['workBkgrdEndDate']) : "31-Dec-4000";
            if ($workBkgrdEndDate == "") {
                $workBkgrdEndDate = "31-Dec-4000";
            }
            $workBkgrdJobDesc = isset($_POST['workBkgrdJobDesc']) ? cleanInputData($_POST['workBkgrdJobDesc']) : "";
            $workBkgrdAchvmnts = isset($_POST['workBkgrdAchvmnts']) ? cleanInputData($_POST['workBkgrdAchvmnts']) : "";
            $cntrRndm = getRandomNum(5000, 9999);
            $affctRws = 0;
            if ($sbmtdPersonID > 0 && $workBkgrdJobName != "" && $workBkgrdInstitution != "" && $workBkgrdLoc != "" && $workBkgrdStartDate != "" && ($srcForm > 0 || $sbmtdPersonID == $prsnid)) {
                if (($srcForm <= 0 && $sbmtdPersonID == $prsnid) || ($srcForm > 0)) {
                    if ($srcForm <= 0) {
                        $oldPKey = getWorkSelfID($sbmtdPersonID, $workBkgrdJobName, $workBkgrdInstitution);
                        if ($workBkgrdPkeyID > 0 && ($oldPKey == $workBkgrdPkeyID || $oldPKey <= 0)) {
                            $affctRws += updateWorkSelf($pkeyID, $workBkgrdJobName, $workBkgrdInstitution, $workBkgrdLoc, $workBkgrdStartDate, $workBkgrdEndDate, $workBkgrdJobDesc, $workBkgrdAchvmnts);
                        } else {
                            $affctRws += createWorkSelf($sbmtdPersonID, $workBkgrdJobName, $workBkgrdInstitution, $workBkgrdLoc, $workBkgrdStartDate, $workBkgrdEndDate, $workBkgrdJobDesc,
                                    $workBkgrdAchvmnts);
                            $workBkgrdPkeyID = getWorkSelfID($sbmtdPersonID, $workBkgrdJobName, $workBkgrdInstitution);
                        }
                    } else {
                        $oldPKey = getWorkID($sbmtdPersonID, $workBkgrdJobName, $workBkgrdInstitution);
                        if ($workBkgrdPkeyID > 0 && ($oldPKey == $workBkgrdPkeyID || $oldPKey <= 0)) {
                            $affctRws += updateWork($workBkgrdPkeyID, $workBkgrdJobName, $workBkgrdInstitution, $workBkgrdLoc, $workBkgrdStartDate, $workBkgrdEndDate, $workBkgrdJobDesc,
                                    $workBkgrdAchvmnts);
                        } else {
                            $affctRws += createWork($sbmtdPersonID, $workBkgrdJobName, $workBkgrdInstitution, $workBkgrdLoc, $workBkgrdStartDate, $workBkgrdEndDate, $workBkgrdJobDesc,
                                    $workBkgrdAchvmnts);
                            $workBkgrdPkeyID = getWorkID($sbmtdPersonID, $workBkgrdJobName, $workBkgrdInstitution);
                        }
                    }
                    if ($affctRws > 0) {
                        if ($srcForm <= 0) {
                            $rqstRslt = prsn_ChngRqst_Exist($prsnid);
                            $rslt = get_RqstStatus($prsnid);
                            if ($rqstRslt < 0) {
                                insert_ChangeRequest($prsnid);
                            }
                        }
                        ?>
                        <tr id="workBkgrdRow_<?php echo $cntrRndm; ?>">
                            <td class="lovtd">
                                <button type="button" class="btn btn-default btn-sm" onclick="getWorkBkgrdForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'workBkgrdForm', 'workBkgrdRow_<?php echo $cntrRndm; ?>', 'Add/Edit Work Experience', 21, 'EDIT', <?php echo $workBkgrdPkeyID; ?>, <?php echo $sbmtdPersonID; ?>);" style="padding:2px !important;">
                                    <img src="../cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                                <input type="hidden" value="<?php echo $workBkgrdPkeyID; ?>" id="workBkgrdRow<?php echo $cntrRndm; ?>_PKeyID"/>                                
                            </td>
                            <td class="lovtd"><?php echo $workBkgrdJobName; ?></td>
                            <td class="lovtd"><?php echo $workBkgrdInstitution; ?></td>
                            <td class="lovtd"><?php echo $workBkgrdLoc; ?></td>
                            <td class="lovtd"><?php echo $workBkgrdStartDate; ?></td>
                            <td class="lovtd"><?php echo $workBkgrdEndDate; ?></td>
                            <td class="lovtd"><?php echo $workBkgrdJobDesc; ?></td>
                            <td class="lovtd"><?php echo $workBkgrdAchvmnts; ?></td>
                            <td class="lovtd">
                                <button type="button" class="btn btn-default btn-sm" onclick="delWorkID('workBkgrdRow_<?php echo $cntrRndm; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Work Background" style="padding:2px !important;" style="padding:2px !important;">
                                    <img src="../cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                            </td>
                        </tr>
                        <?php
                    } else {
                        echo "Failed to Save any Data!";
                    }
                } else {
                    echo "Invalid or Incomplete Data!";
                }
            } else {
                echo "Invalid or Incomplete Data!";
            }
        } else if ($actyp == 13) {
            /* Skills/Nature */
            $skillsPkeyID = isset($_POST['skillsPkeyID']) ? cleanInputData($_POST['skillsPkeyID']) : -1;
            $srcForm = -1; //isset($_POST['srcForm']) ? cleanInputData($_POST['srcForm']) : -1;
            $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? cleanInputData($_POST['sbmtdPersonID']) : -1;
            if ($srcForm <= 0) {
                if ($sbmtdPersonID != $prsnid) {
                    echo restricted();
                    exit();
                }
            } else {
                if (!($canEdtPrsn || $canAddPrsn) && !($canMngMyFirm && $lnkdFirmID == $sbmtdPrsnFirmID && $lnkdFirmID > 0)) {
                    echo restricted();
                    exit();
                }
            }
            $skillsLanguages = isset($_POST['skillsLanguages']) ? cleanInputData($_POST['skillsLanguages']) : "";
            $skillsHobbies = isset($_POST['skillsHobbies']) ? cleanInputData($_POST['skillsHobbies']) : "";
            $skillsInterests = isset($_POST['skillsInterests']) ? cleanInputData($_POST['skillsInterests']) : "";
            $skillsConduct = isset($_POST['skillsConduct']) ? cleanInputData($_POST['skillsConduct']) : "";
            $skillsAttitudes = isset($_POST['skillsAttitudes']) ? cleanInputData($_POST['skillsAttitudes']) : "";
            $skillsStartDate = isset($_POST['skillsStartDate']) ? cleanInputData($_POST['skillsStartDate']) : "";
            $skillsEndDate = isset($_POST['skillsEndDate']) ? cleanInputData($_POST['skillsEndDate']) : "31-Dec-4000";
            if ($skillsEndDate == "") {
                $skillsEndDate = "31-Dec-4000";
            }
            $cntrRndm = getRandomNum(5000, 9999);
            $affctRws = 0;
            if ($sbmtdPersonID > 0 && $skillsStartDate != "" && $skillsLanguages != "" && ($srcForm > 0 || $sbmtdPersonID == $prsnid)) {
                if (($srcForm <= 0 && $sbmtdPersonID == $prsnid) || ($srcForm > 0)) {
                    if ($srcForm <= 0) {
                        $oldPKey = getSkillsSelfID($sbmtdPersonID, $skillsStartDate, $skillsEndDate);
                        if ($skillsPkeyID > 0 && ($oldPKey == $skillsPkeyID || $oldPKey <= 0)) {
                            $affctRws += updateSkillsSelf($skillsPkeyID, $skillsLanguages, $skillsHobbies, $skillsInterests, $skillsConduct, $skillsAttitudes, $skillsStartDate, $skillsEndDate);
                        } else {
                            $affctRws += createSkillsSelf($sbmtdPersonID, $skillsLanguages, $skillsHobbies, $skillsInterests, $skillsConduct, $skillsAttitudes, $skillsStartDate, $skillsEndDate);
                            $skillsPkeyID = getSkillsSelfID($sbmtdPersonID, $skillsStartDate, $skillsEndDate);
                        }
                    } else {
                        $oldPKey = getSkillsID($sbmtdPersonID, $skillsStartDate, $skillsEndDate);
                        if ($skillsPkeyID > 0 && ($oldPKey == $skillsPkeyID || $oldPKey <= 0)) {
                            $affctRws += updateSkills($skillsPkeyID, $skillsLanguages, $skillsHobbies, $skillsInterests, $skillsConduct, $skillsAttitudes, $skillsStartDate, $skillsEndDate);
                        } else {
                            $affctRws += createSkills($sbmtdPersonID, $skillsLanguages, $skillsHobbies, $skillsInterests, $skillsConduct, $skillsAttitudes, $skillsStartDate, $skillsEndDate);
                            $skillsPkeyID = getSkillsID($sbmtdPersonID, $skillsStartDate, $skillsEndDate);
                        }
                    }
                    if ($affctRws > 0) {
                        if ($srcForm <= 0) {
                            $rqstRslt = prsn_ChngRqst_Exist($prsnid);
                            $rslt = get_RqstStatus($prsnid);
                            if ($rqstRslt < 0) {
                                insert_ChangeRequest($prsnid);
                            }
                        }
                        ?>
                        <tr id="skillsTblRow_<?php echo $cntrRndm; ?>">
                            <td class="lovtd">
                                <button type="button" class="btn btn-default btn-sm" onclick="getSkillsForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'skillsForm', 'skillsTblRow_<?php echo $cntrRndm; ?>', 'Add/Edit Skills/Nature', 22, 'EDIT', <?php echo $skillsPkeyID; ?>, <?php echo $sbmtdPersonID; ?>);" style="padding:2px !important;">
                                    <img src="../cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                                <input type="hidden" value="<?php echo $skillsPkeyID; ?>" id="workBkgrdRow<?php echo $cntrRndm; ?>_PKeyID"/>                                
                            </td>
                            <td class="lovtd"><?php echo $skillsLanguages; ?></td>
                            <td class="lovtd"><?php echo $skillsHobbies; ?></td>
                            <td class="lovtd"><?php echo $skillsInterests; ?></td>
                            <td class="lovtd"><?php echo $skillsConduct; ?></td>
                            <td class="lovtd"><?php echo $skillsAttitudes; ?></td>
                            <td class="lovtd"><?php echo $skillsStartDate; ?></td>
                            <td class="lovtd"><?php echo $skillsEndDate; ?></td>
                            <td class="lovtd">
                                <button type="button" class="btn btn-default btn-sm" onclick="delSkillID('skillsTblRow_<?php echo $cntrRndm; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Skill" style="padding:2px !important;" style="padding:2px !important;">
                                    <img src="../cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                            </td>
                        </tr>
                        <?php
                    } else {
                        echo "Failed to Save any Data!";
                    }
                } else {
                    echo "Invalid or Incomplete Data!";
                }
            } else {
                echo "Invalid or Incomplete Data!";
            }
        } else if ($actyp == 14) {
            /* Other Info */
            var_dump($_POST);
        } else if ($actyp == 15) {
            /* Doc. Attachments */
            header("content-type:application/json");
            $attchmentID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
            $srcForm = -1; //isset($_POST['srcForm']) ? cleanInputData($_POST['srcForm']) : -1;
            $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? cleanInputData($_POST['sbmtdPersonID']) : -1;
            if ($srcForm <= 0) {
                if ($sbmtdPersonID != $prsnid) {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else {
                if (!($canEdtPrsn || $canAddPrsn) && !($canMngMyFirm && $lnkdFirmID == $sbmtdPrsnFirmID && $lnkdFirmID > 0)) {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            }
            $docCtrgrName = isset($_POST['docCtrgrName']) ? cleanInputData($_POST['docCtrgrName']) : "";
            $pkID = -1;
            $nwImgLoc = "";
            $errMsg = "";
            if ($srcForm <= 0) {
                $pkID = $prsnid;
                if ($attchmentID > 0) {
                    uploadDaDocSelf($attchmentID, $nwImgLoc, $errMsg);
                } else {
                    $attchmentID = getNewPrsnDocIDSelf();
                    createPrsnDocSelf($attchmentID, $pkID, $docCtrgrName, "");
                    uploadDaDocSelf($attchmentID, $nwImgLoc, $errMsg);
                }
            } else {
                $pkID = $sbmtdPersonID;
                if ($attchmentID > 0) {
                    uploadDaDoc($attchmentID, $nwImgLoc, $errMsg);
                } else {
                    $attchmentID = getNewPrsnDocID();
                    createPrsnDoc($attchmentID, $pkID, $docCtrgrName, "");
                    uploadDaDoc($attchmentID, $nwImgLoc, $errMsg);
                }
            }
            $arr_content['attchID'] = $attchmentID;
            if (strpos($errMsg, "Document Stored Successfully!<br/>") === FALSE) {
                $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $errMsg;
            } else {
                $doc_src_encrpt = "";
                if ($srcForm <= 0) {
                    $rqstRslt = prsn_ChngRqst_Exist($prsnid);
                    $rslt = get_RqstStatus($prsnid);
                    if ($rqstRslt < 0) {
                        insert_ChangeRequest($prsnid);
                    }
                    $doc_src = $ftp_base_db_fldr . "/PrsnDocs/Request/" . $nwImgLoc;
                    $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                    if (file_exists($doc_src)) {
                        //file exists!
                    } else {
                        //file does not exist.
                        $doc_src_encrpt = "None";
                    }
                } else {
                    $doc_src = $ftp_base_db_fldr . "/PrsnDocs/" . $nwImgLoc;
                    $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                    if (file_exists($doc_src)) {
                        //file exists!
                    } else {
                        //file does not exist.
                        $doc_src_encrpt = "None";
                    }
                }
                $arr_content['crptpath'] = $doc_src_encrpt;
                $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $errMsg;
            }
            echo json_encode($arr_content);
            exit();
        } else if ($actyp == 40) {
            //Submit Data Change Request to Workflow
            $RoutingID = -1;
            if (isset($_POST['RoutingID'])) {
                $RoutingID = cleanInputData($_POST['RoutingID']);
            }
            if ($RoutingID <= 0) {
                $prsnid = $_SESSION['PRSN_ID'];
                $srcDocID = get_RqstID($prsnid);
                $srcDocType = "Personal Records Change";
                $routingID = -1;
                $inptSlctdRtngs = "";
                $actionToPrfrm = "Initiate";
                echo dataChngReqMsgActns($routingID, $inptSlctdRtngs, $actionToPrfrm, $srcDocID, $srcDocType);
            } else {
                $actiontyp = isset($_POST['actiontyp']) ? $_POST['actiontyp'] : "";
                $usrID = $_SESSION['USRID'];
                $arry1 = explode(";", $actiontyp);
                for ($r = 0; $r < count($arry1); $r++) {
                    if ($arry1[$r] !== "") {
                        $srcDocID = -1;
                        $srcDocType = "Personal Records Change";
                        $inptSlctdRtngs = "";
                        $routingID = $RoutingID;
                        $actionToPrfrm = $arry1[$r];
                        echo dataChngReqMsgActns($routingID, $inptSlctdRtngs, $actionToPrfrm, $srcDocID, $srcDocType);
                    }
                }
            }
        }
    } else if ($vwtyp == "999") {
        ?>
        <div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">My Personal Stuff</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:openATab('#allmodules', 'grp=42&typ=1');">All Apps</a></li>
                            <li class="breadcrumb-item active">Personal Stuff</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content" style="padding: 10px 5px 10px 5px !important;">
            <div class="container-fluid">
                <?php
                $grpcntr = 0;
                $cntent = "";
                for ($i = 0; $i < count($menuItems); $i++) {
                    $No = $i + 1;
                    if (($i == 0 || $i == 1 || $i == 2) && test_prmssns("View Self-Service", "Self Service") == FALSE) {
                        continue;
                    }
                    if ($grpcntr == 0) {
                        $cntent .= "<div class=\"row\">";
                    }

                    $cntent .= "<div class=\"col-md-3 colmd3special2\">
        <button type=\"button\" class=\"btn btn-default btn-lg btn-block modulesButton\" onclick=\"openATab('#allmodules', '" . $menuLinks[$i] . "');\">
            <img src=\"../cmn_images/" . $menuImages[$i] . "\" style=\"margin:5px; padding-right: 1em; height:58px; width:auto; position: relative; vertical-align: middle;float:left;\">
            <span class=\"wordwrap2\">" . ($menuItems[$i]) . "</span>
        </button>
            </div>";

                    if ($grpcntr == 3) {
                        $cntent .= "</div>";
                        $grpcntr = 0;
                    } else {
                        $grpcntr = $grpcntr + 1;
                    }
                }
                echo $cntent;
                ?>
            </div>
        </section>
        <?php
    } else if ($vwtyp == "0") {
        $lnkdFirmID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "lnkd_firm_org_id", $prsnid);
        if ($sbmtdPersonID <= 0) {
            $pkID = $prsnid;
        } else {
            $pkID = $sbmtdPersonID;
        }
        $rcrdExst = prsn_Record_Exist($pkID);
        $chngRqstExst = prsn_ChngRqst_Exist($pkID);
        $rqStatus = get_RqstStatus($pkID);
        if ($rqStatus == "Approved") {
            $sbmtdPersonID = $pkID;
        }
        if ($pkID > 0) {
            if ($sbmtdPersonID <= 0) {
                $result = get_SelfPrsnDet($pkID);
            } else {
                $result = get_PrsnDet($pkID);
            }
            while ($row = loc_db_fetch_array($result)) {
                $nwFileName = "";
                $temp = explode(".", $row[2]);
                $extension = end($temp);
                if (trim($extension) == "") {
                    $extension = "png";
                }
                $ecnptDFlNm = encrypt1($row[2], $smplTokenWord1);
                $nwFileName = $ecnptDFlNm . "." . $extension;
                $ftp_src = "";
                if ($sbmtdPersonID <= 0) {
                    $ftp_src = $ftp_base_db_fldr . "/Person/Request/" . $pkID . "." . $extension;
                    if (!($rcrdExst == true && $chngRqstExst > 0 && file_exists($ftp_src))) {
                        $ftp_src = $ftp_base_db_fldr . "/Person/" . $pkID . "." . $extension;
                    }
                } else {
                    $ftp_src = $ftp_base_db_fldr . "/Person/" . $pkID . "." . $extension;
                }
                $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName;
                if (file_exists($ftp_src)) {
                    copy("$ftp_src", "$fullPemDest");
                } else if (!file_exists($fullPemDest)) {
                    $ftp_src = $fldrPrfx . 'cmn_images/image_up.png';
                    copy("$ftp_src", "$fullPemDest");
                }
                $nwFileName = "" . $tmpDest . $nwFileName;
                // QR CODE data 
                $name = str_replace("  ", " ", $row[3] . " " . $row[4] . " " . $row[6] . " " . $row[5]);
                $sortName = $row[5] . ';' . $row[4];
                $phone = $row[16];
                $phonePrivate = '';
                $phoneCell = $row[17];
                $orgName = $row[21];

                $email = $row[15];
                $addressLabel = 'Address';
                $addressPobox = preg_replace("/[\r\n]+/", " ", $row[14]);
                $addressExt = '';
                $addressStreet = '';
                $addressTown = '';
                $addressRegion = '';
                $addressPostCode = '';
                $addressCountry = 'GH';
                // we building raw data 
                $codeContents = 'BEGIN:VCARD' . "\n";
                $codeContents .= 'VERSION:2.1' . "\n";
                $codeContents .= 'N:' . $sortName . "\n";
                $codeContents .= 'FN:' . $name . "\n";
                $codeContents .= 'ORG:' . $orgName . "\n";
                $codeContents .= 'TEL;WORK;VOICE:' . $phone . "\n";
                $codeContents .= 'TEL;HOME;VOICE:' . $phonePrivate . "\n";
                $codeContents .= 'TEL;TYPE=cell:' . $phoneCell . "\n";
                $codeContents .= 'ADR;TYPE=work;' .
                        'LABEL="' . $addressLabel . '":'
                        . $addressPobox . ';'
                        . $addressExt . ';'
                        . $addressStreet . ';'
                        . $addressTown . ';'
                        . $addressPostCode . ';'
                        . $addressCountry
                        . "\n";
                $codeContents .= 'EMAIL:' . $email . "\n";
                $codeContents .= 'END:VCARD';
                ?>
                <div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark">My Personal Profile</h1>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="javascript:openATab('#allmodules', 'grp=42&typ=1');">All Apps</a></li>
                                    <li class="breadcrumb-item"><a href="javascript:openATab('#allmodules', 'grp=50&typ=1&vtyp=999');">Personal Stuff</a></li>
                                    <li class="breadcrumb-item active">Profile</li>
                                </ol>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->
                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row" style="margin-top: 5px !important;">
                            <div class="col-sm-3">
                                <!-- Profile Image -->
                                <?php
                                $rqstatusColor = "red";
                                if ($rqStatus == "Approved") {
                                    $rqstatusColor = "green";
                                }
                                ?>  
                                <div class="card card-primary card-outline">
                                    <div class="card-body box-profile" style="padding:5px !important;">
                                        <div class="text-center">
                                            <button type="button" class="btn btn-default btn-block" style="margin-bottom: 5px;"><span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $rqStatus; ?></span></button>
                                            <fieldset class="basic_person_fs2"><!--<legend class="basic_person_lg">Picture</legend>-->
                                                <div>                                                  
                                                    <img class="elevation-1" style="width: 190px !important; height: auto !important;max-width:100% !important;border-radius: 10%;" src="<?php echo $app_url . $nwFileName; ?>" alt="User profile picture">
                                                </div>                                       
                                            </fieldset> 
                                        </div>
                                        <h3 class="profile-username text-center"><?php echo $name; ?></h3>
                                        <p class="text-muted text-center">ID No.:<?php echo $row[1]; ?></p>
                                        <div class="text-center">
                                            <fieldset class="basic_person_fs2"><!--<legend class="basic_person_lg">QR Code</legend>-->
                                                <div>
                                                    <img src="../<?php echo getQRCodeUrl($codeContents, $ecnptDFlNm . "_QR"); ?>" alt="..." id="imgQrCode" class="img-thumbnail center-block img-responsive" style="height: 170px !important; width: auto !important;">                                            
                                                </div>                                       
                                            </fieldset>
                                        </div>
                                        <div class="" style="padding:5px 0px 0px 0px;justify-content: center;display:flex;">                          
                                            <button type="button" class="btn btn-default" style="display:inline-block;margin-right: 1px;" onclick="openATab('#allmodules', 'grp=50&typ=1&pg=1&vtyp=0');">
                                                <img src="../cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 0px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                            <button type="button" class="btn btn-default" style="display:inline-block;margin-right: 1px;" onclick="getPrsnProfilePDF(<?php echo $pkID; ?>);">
                                                <img src="../cmn_images/pdf.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"> GET PDF
                                            </button>
                                            <button type="button" class="btn btn-default"  style="display:inline-block;" onclick="openATab('#allmodules', 'grp=50&typ=1&pg=2&vtyp=1');">
                                                <img src="../cmn_images/edit32.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;"> EDIT
                                            </button>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <!-- /.col -->
                            <?php
                            $vPsblValID1 = getEnbldPssblValID("Application Instance SHORT CODE", getLovID("All Other General Setups"));
                            $vPsblVal1 = getPssblValDesc($vPsblValID1);
                            $rhoOptionalFlds = "";
                            if ($vPsblVal1 == "TAKBG_SWLFR_APP_1") {
                                $rhoOptionalFlds = "hideNotice";
                            }
                            ?>
                            <div class="col-sm-9">
                                <div class="card card-outline card-outline-tabs">
                                    <div class="card-header p-0 border-bottom-0">
                                        <ul class="nav nav-tabs" id="prsnPrflTabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="prflHomeROtab" data-toggle="pill" href="#prflHomeRO" role="tab" aria-controls="custom-tabs-two-home" aria-selected="false"><i class="fas fa-user"></i> Personal Data</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="prflAddPrsnDataROtab" data-toggle="pill" href="#prflAddPrsnDataRO" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false"><i class="far fa-user"></i> Additional Data</a>
                                            </li>
                                            <li class="nav-item <?php echo $rhoOptionalFlds; ?>">
                                                <a class="nav-link" id="prflCVROtab" data-toggle="pill" href="#prflCVRO" role="tab" aria-controls="custom-tabs-two-settings" aria-selected="true"><i class="fas fa-award"></i> CV</a>
                                            </li>
                                            <li class="nav-item <?php echo $rhoOptionalFlds; ?>">
                                                <a class="nav-link" id="prflOrgAsgnROtab" data-toggle="pill" href="#prflOrgAsgnRO" role="tab" aria-controls="custom-tabs-two-messages" aria-selected="false"><i class="fas fa-calendar-check"></i> Designations</a>
                                            </li>
                                            <li class="nav-item <?php echo $rhoOptionalFlds; ?>">
                                                <a class="nav-link" id="prflOthrInfoROtab" data-toggle="pill" href="#prflOthrInfoRO" role="tab" aria-controls="custom-tabs-two-settings" aria-selected="true"><i class="fas fa-paperclip"></i> Attached Documents</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="prflAccountROtab" data-toggle="pill" href="#prflAccountRO" role="tab" aria-controls="custom-tabs-two-settings" aria-selected="true"><i class="fas fa-cog"></i> Settings</a>
                                            </li>
                                        </ul>
                                    </div><!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="prflHomeRO">
                                                <form class="form-horizontal">
                                                    <div class="row">                               
                                                        <div class="col-sm-6">
                                                            <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Names</legend>
                                                                <div class="form-group row">
                                                                    <label for="idNo" class="control-label col-sm-4">ID No:</label>
                                                                    <div class="col-sm-8">
                                                                        <span><?php echo $row[1]; ?></span>
                                                                    </div>
                                                                </div> 
                                                                <div class="form-group row">
                                                                    <label for="title" class="control-label col-sm-4">Title:</label>
                                                                    <div  class="col-sm-8">
                                                                        <span><?php echo $row[3]; ?></span>
                                                                    </div>
                                                                </div>  
                                                                <div class="form-group row">
                                                                    <label for="firstName" class="control-label col-sm-4">First Name:</label>
                                                                    <div  class="col-sm-8">
                                                                        <span><?php echo $row[4]; ?></span>
                                                                    </div>
                                                                </div> 
                                                                <div class="form-group row">
                                                                    <label for="surName" class="control-label col-sm-4">Surname:</label>
                                                                    <div  class="col-sm-8">
                                                                        <span><?php echo $row[5]; ?></span>
                                                                    </div>
                                                                </div>     
                                                                <div class="form-group row">
                                                                    <label for="otherNames" class="control-label col-sm-4">Other Names:</label>
                                                                    <div  class="col-sm-8">
                                                                        <span><?php echo $row[6]; ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="gender" class="control-label col-sm-4">Gender:</label>
                                                                    <div  class="col-sm-8">
                                                                        <span><?php echo $row[8]; ?></span>
                                                                    </div>
                                                                </div> 
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-sm-6"> 
                                                            <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Personal Data</legend>
                                                                <div class="form-group row">
                                                                    <label for="maritalStatus" class="control-label col-sm-4">Marital Status:</label>
                                                                    <div  class="col-sm-8">
                                                                        <span><?php echo $row[9]; ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="dob" class="control-label col-sm-4">Date of Birth</label>
                                                                    <div class="col-sm-8">
                                                                        <span><?php echo $row[10]; ?></span>
                                                                    </div>
                                                                </div> 
                                                                <div class="form-group row">
                                                                    <label for="pob" class="control-label col-sm-4">Place of Birth:</label>
                                                                    <div  class="col-sm-8">
                                                                        <span><?php echo $row[11]; ?></span>
                                                                    </div>
                                                                </div> 
                                                                <div class="form-group row">
                                                                    <label for="nationality" class="control-label col-sm-4">Nationality:</label>
                                                                    <div  class="col-sm-8">
                                                                        <span><?php echo $row[20]; ?></span>
                                                                    </div>
                                                                </div>  
                                                                <div class="form-group row">
                                                                    <label for="homeTown" class="control-label col-sm-4">Home Town:</label>
                                                                    <div  class="col-sm-8">
                                                                        <span><?php echo $row[19]; ?></span>
                                                                    </div>
                                                                </div> 
                                                                <div class="form-group row">
                                                                    <label for="religion" class="control-label col-sm-4">Religion:</label>
                                                                    <div  class="col-sm-8">
                                                                        <span><?php echo $row[12]; ?></span>
                                                                    </div>
                                                                </div>                                              
                                                            </fieldset>   
                                                        </div>
                                                    </div>    
                                                    <div class="row"><!-- ROW 1 -->                                
                                                        <div class="col-sm-6">
                                                            <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Contact Information</legend>
                                                                <div class="form-group row">
                                                                    <label for="linkedFirm" class="control-label col-sm-4">Linked Firm/ Workplace</label>
                                                                    <div  class="col-sm-8">
                                                                        <span><?php echo $row[21]; ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="branch" class="control-label col-sm-4">Site/Branch:</label>
                                                                    <div  class="col-sm-8">
                                                                        <span><?php echo $row[22]; ?></span>
                                                                    </div>
                                                                </div>  
                                                                <div class="form-group row">
                                                                    <label for="email" class="control-label col-sm-4">Email:</label>
                                                                    <div  class="col-sm-8">
                                                                        <span><?php echo $row[15]; ?></span>
                                                                    </div>
                                                                </div> 
                                                                <div class="form-group row">
                                                                    <label for="telephone" class="control-label col-sm-4">Contact Nos:</label>
                                                                    <div  class="col-sm-8">
                                                                        <span><?php echo trim($row[16] . ", " . $row[17], ", ") ?></span>
                                                                    </div>                                       
                                                                </div>     
                                                                <div class="form-group row">
                                                                    <label for="fax" class="control-label col-sm-4">Fax:</label>
                                                                    <div  class="col-sm-8">
                                                                        <span><?php echo $row[18]; ?></span>
                                                                    </div>
                                                                </div> 
                                                            </fieldset>                                                
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Relationship with Organization</legend>                                    
                                                                <div class="form-group row">
                                                                    <label for="relation" class="control-label col-sm-4">Relation:</label>
                                                                    <div  class="col-sm-8">
                                                                        <span><?php echo $row[23]; ?></span>
                                                                    </div>
                                                                </div>                                            
                                                                <div class="form-group row">
                                                                    <label for="causeOfRelation" class="control-label col-sm-4">Cause of Relation:</label>
                                                                    <div  class="col-sm-8">
                                                                        <span><?php echo $row[24]; ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="furtherDetails" class="control-label col-sm-4">Further Details:</label>
                                                                    <div  class="col-sm-8">
                                                                        <span><?php echo $row[25]; ?></span>
                                                                    </div>
                                                                </div>  
                                                                <div class="form-group row">
                                                                    <label for="startDate" class="control-label col-sm-4">Start Date:</label>
                                                                    <div class="col-sm-8">
                                                                        <span><?php echo $row[26]; ?></span>
                                                                    </div>
                                                                </div>      
                                                                <div class="form-group row">
                                                                    <label for="endDate" class="control-label col-sm-4">End Date:</label>
                                                                    <div class="col-sm-8">
                                                                        <span><?php echo $row[27]; ?></span>
                                                                    </div>
                                                                </div>  
                                                            </fieldset>                                                
                                                        </div>
                                                    </div> 
                                                    <div class="row"><!-- ROW 3 -->
                                                        <div class="col-sm-6">
                                                            <fieldset class="basic_person_fs3"><legend class="basic_person_lg">Residential Address</legend> 
                                                                <div class="form-group row">
                                                                    <div  class="col-sm-12">
                                                                        <span><?php echo $row[13]; ?></span>
                                                                    </div>
                                                                </div> 
                                                            </fieldset>                                        
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <fieldset class="basic_person_fs3"><legend class="basic_person_lg">Postal Address</legend> 
                                                                <div class="form-group row">
                                                                    <div  class="col-sm-12">
                                                                        <span><?php echo $row[14]; ?></span>
                                                                    </div>
                                                                </div>
                                                            </fieldset>                                        
                                                        </div>
                                                    </div> 
                                                    <div class="row">
                                                        <div  class="col-sm-12">
                                                            <fieldset class="basic_person_fs3"><legend class="basic_person_lg">National ID Cards</legend> 
                                                                <table id="nationalIDTblRO" class="table table-striped table-bordered" cellspacing="0" width="100%" style="width:100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Country</th>
                                                                            <th>ID Type</th>
                                                                            <th>ID No.</th>
                                                                            <th>Date Issued</th>
                                                                            <th>Expiry Date</th>
                                                                            <th>Other Information</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <!--<tfoot>
                                                                        <tr>
                                                                            <th>Country</th>
                                                                            <th>ID Type</th>
                                                                            <th>ID No.</th>
                                                                            <th>Date Issued</th>
                                                                            <th>Expiry Date</th>
                                                                            <th>Other Information</th>
                                                                        </tr>
                                                                    </tfoot>-->
                                                                    <tbody>
                                                                        <?php
                                                                        if ($sbmtdPersonID <= 0) {
                                                                            $result1 = get_AllNtnlty_Self($pkID);
                                                                        } else {
                                                                            $result1 = get_AllNtnlty($pkID);
                                                                        }
                                                                        while ($row1 = loc_db_fetch_array($result1)) {
                                                                            ?>
                                                                            <tr>
                                                                                <td class="lovtd"><?php echo $row1[1]; ?></td>
                                                                                <td class="lovtd"><?php echo $row1[2]; ?></td>
                                                                                <td class="lovtd"><?php echo $row1[3]; ?></td>
                                                                                <td class="lovtd"><?php echo $row1[4]; ?></td>
                                                                                <td class="lovtd"><?php echo $row1[5]; ?></td>
                                                                                <td class="lovtd"><?php echo $row1[6]; ?></td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            </fieldset>
                                                        </div>
                                                    </div>  
                                                </form>  
                                            </div>
                                            <!-- /.tab-pane -->
                                            <div class="tab-pane" id="prflAddPrsnDataRO">
                                                <?php
                                                /* Additional Person Data */
                                                if ($pkID > 0) {
                                                    $result = get_PrsExtrDataGrps($orgID);
                                                    ?>
                                                    <form class="form-horizontal">
                                                        <?php
                                                        while ($row = loc_db_fetch_array($result)) {
                                                            ?>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <fieldset class="basic_person_fs4">
                                                                        <legend class="basic_person_lg"><?php echo $row[0]; ?></legend>
                                                                        <?php
                                                                        $result1 = get_PrsExtrDataGrpCols($row[0], $orgID);
                                                                        $cntr1 = 0;
                                                                        $gcntr1 = 0;
                                                                        $cntr1Ttl = loc_db_num_rows($result1);
                                                                        while ($row1 = loc_db_fetch_array($result1)) {
                                                                            /* POSSIBLE FIELDS
                                                                             * label
                                                                             * textbox (for now only this)
                                                                             * textarea (for now only this)
                                                                             * readonly textbox with button
                                                                             * readonly textbox with date
                                                                             * textbox with number validation
                                                                             */
                                                                            if ($row1[7] == "Tabular") {
                                                                                ?>
                                                                                <div class="row">
                                                                                    <div  class="col-sm-12">
                                                                                        <table id="extDataTblCol<?php echo $row1[1]; ?>" class="table table-striped table-bordered extPrsnDataTblRO" cellspacing="0" width="100%" style="width:100%;">
                                                                                            <thead><th>No.</th>
                                                                                            <?php
                                                                                            $fieldHdngs = $row1[11];
                                                                                            $arry1 = explode(",", $fieldHdngs);
                                                                                            $cntr = count($arry1);
                                                                                            for ($i = 0; $i < $row1[9]; $i++) {
                                                                                                if ($i <= $cntr - 1) {
                                                                                                    ?>
                                                                                                    <th><?php echo $arry1[$i]; ?></th>
                                                                                                    <?php
                                                                                                } else {
                                                                                                    ?>
                                                                                                    <th>&nbsp;</th>
                                                                                                    <?php
                                                                                                }
                                                                                            }
                                                                                            ?>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                <?php
                                                                                                if ($sbmtdPersonID <= 0) {
                                                                                                    $fldVal = get_PrsExtrData_Self($pkID, $row1[1]);
                                                                                                } else {
                                                                                                    $fldVal = get_PrsExtrData($pkID, $row1[1]);
                                                                                                }
                                                                                                $arry3 = explode("|", $fldVal);
                                                                                                $cntr3 = count($arry3);
                                                                                                $maxsze = (int) 320 / $row1[9];
                                                                                                if ($maxsze > 100 || $maxsze < 80) {
                                                                                                    $maxsze = 100;
                                                                                                }
                                                                                                for ($j = 0; $j < $cntr3; $j++) {
                                                                                                    ?>
                                                                                                    <tr><td><?php echo ($j + 1); ?></td>
                                                                                                        <?php
                                                                                                        $arry2 = explode("~", $arry3[$j]);
                                                                                                        $cntr2 = count($arry2);
                                                                                                        for ($i = 0; $i < $row1[9]; $i++) {
                                                                                                            if ($i <= $cntr2 - 1) {
                                                                                                                ?>
                                                                                                                <td><?php echo $arry2[$i]; ?></td>
                                                                                                            <?php } else { ?>
                                                                                                                <td>&nbsp;</td>
                                                                                                                <?php
                                                                                                            }
                                                                                                        }
                                                                                                        ?>
                                                                                                    </tr>
                                                                                                    <?php
                                                                                                }
                                                                                                ?>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                                <?php
                                                                            } else {
                                                                                if ($gcntr1 == 0) {
                                                                                    $gcntr1 += 1;
                                                                                }
                                                                                if (($cntr1 % 2) == 0) {
                                                                                    ?> 
                                                                                    <div class="row"> 
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                    <div class="col-sm-6"> 
                                                                                        <div class="form-group row"> 
                                                                                            <label class="control-label col-sm-4"><?php echo $row1[2]; ?>:</label>
                                                                                            <div  class="col-sm-8">
                                                                                                <span>
                                                                                                    <?php
                                                                                                    if ($sbmtdPersonID <= 0) {
                                                                                                        echo get_PrsExtrData_Self($pkID, $row1[1]);
                                                                                                    } else {
                                                                                                        echo get_PrsExtrData($pkID, $row1[1]);
                                                                                                    }
                                                                                                    ?>
                                                                                                </span>
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
                                                                        }
                                                                        if ($gcntr1 == 1) {
                                                                            $gcntr1 = 0;
                                                                        }
                                                                        ?>
                                                                    </fieldset>
                                                                </div>
                                                                <?php ?>
                                                            </div>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </form>

                                            </div>
                                            <!-- /.tab-pane -->
                                            <div class="tab-pane" id="prflOrgAsgnRO">
                                                <?php
                                                /* Org Assignments */
                                                if ($sbmtdPersonID <= 0) {
                                                    $pkID = $prsnid;
                                                } else {
                                                    $pkID = $sbmtdPersonID;
                                                }
                                                $cntr = 0;
                                                ?> 
                                                <div class="row">
                                                    <div class="col-sm-12"> 
                                                        <fieldset class="basic_person_fs4"><legend class="basic_person_lg">DIVISIONS/GROUPS</legend>
                                                            <div  class="col-sm-12" style="padding:0px !important;">
                                                                <table id="divsGroupsRO" class="table table-striped table-bordered orgAsgnmentsTblsRO" cellspacing="0" width="100%" style="width:100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>No.</th>
                                                                            <th>Group Name</th>
                                                                            <th>Group Type</th>
                                                                            <th>Start Date</th>
                                                                            <th>End Date</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        if ($pkID > 0) {
                                                                            $result1 = get_DivsGrps($pkID);
                                                                            while ($row1 = loc_db_fetch_array($result1)) {
                                                                                $cntr += 1;
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?php echo $cntr; ?></td>
                                                                                    <td><?php echo $row1[2]; ?></td>
                                                                                    <td><?php echo $row1[6]; ?></td>
                                                                                    <td><?php echo $row1[3]; ?></td>
                                                                                    <td><?php echo $row1[4]; ?></td>
                                                                                </tr>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div> 
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-sm-12"> 
                                                        <fieldset class="basic_person_fs4"><legend class="basic_person_lg">IMMEDIATE SUPERVISORS</legend> 
                                                            <div  class="col-sm-12" style="padding:0px !important;">
                                                                <table id="immdteSprvsrsRO" class="table table-striped table-bordered orgAsgnmentsTblsRO" cellspacing="0" width="100%" style="width:100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>No.</th>
                                                                            <th>ID No. of Supervisor</th>
                                                                            <th>Name of Supervisor</th>
                                                                            <th>Start Date</th>
                                                                            <th>End Date</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        if ($pkID > 0) {
                                                                            $cntr = 0;
                                                                            $result1 = get_Spvsrs($pkID);
                                                                            while ($row1 = loc_db_fetch_array($result1)) {
                                                                                $cntr += 1;
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?php echo $cntr; ?></td>
                                                                                    <td><?php echo $row1[2]; ?></td>
                                                                                    <td><?php echo $row1[3]; ?></td>
                                                                                    <td><?php echo $row1[4]; ?></td>
                                                                                    <td><?php echo $row1[5]; ?></td>
                                                                                </tr>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div> 
                                                        </fieldset>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <fieldset class="basic_person_fs4"><legend class="basic_person_lg">SITES/LOCATIONS</legend> 
                                                            <div  class="col-sm-12" style="padding:0px !important;">
                                                                <table id="sitesLocsRO" class="table table-striped table-bordered orgAsgnmentsTblsRO" cellspacing="0" width="100%" style="width:100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>No.</th>
                                                                            <th>Site/Branch Name</th>
                                                                            <th>Start Date</th>
                                                                            <th>End Date</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        if ($pkID > 0) {
                                                                            $cntr = 0;

                                                                            $result1 = get_SitesLocs($pkID);
                                                                            while ($row1 = loc_db_fetch_array($result1)) {
                                                                                $cntr += 1;
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?php echo $cntr; ?></td>
                                                                                    <td><?php echo $row1[2]; ?></td>
                                                                                    <td><?php echo $row1[3]; ?></td>
                                                                                    <td><?php echo $row1[4]; ?></td>
                                                                                </tr>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div> 
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <fieldset class="basic_person_fs4"><legend class="basic_person_lg">JOBS</legend>
                                                            <div  class="col-sm-12" style="padding:0px !important;">
                                                                <table id="jobsRO" class="table table-striped table-bordered orgAsgnmentsTblsRO" cellspacing="0" width="100%" style="width:100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>No.</th>
                                                                            <th>Job Name</th>
                                                                            <th>Start Date</th>
                                                                            <th>End Date</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        if ($pkID > 0) {
                                                                            $cntr = 0;

                                                                            $result1 = get_Jobs($pkID);
                                                                            while ($row1 = loc_db_fetch_array($result1)) {
                                                                                $cntr += 1;
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?php echo $cntr; ?></td>
                                                                                    <td><?php echo $row1[2]; ?></td>
                                                                                    <td><?php echo $row1[3]; ?></td>
                                                                                    <td><?php echo $row1[4]; ?></td>
                                                                                </tr>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div> 
                                                        </fieldset>
                                                    </div>                                        
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <fieldset class="basic_person_fs4"><legend class="basic_person_lg">GRADES</legend> 
                                                            <div  class="col-sm-12" style="padding:0px !important;">
                                                                <table id="gradesRO" class="table table-striped table-bordered orgAsgnmentsTblsRO" cellspacing="0" width="100%" style="width:100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>No.</th>
                                                                            <th>Grade Name</th>
                                                                            <th>Start Date</th>
                                                                            <th>End Date</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        if ($pkID > 0) {
                                                                            $cntr = 0;

                                                                            $result1 = get_Grades($pkID);
                                                                            while ($row1 = loc_db_fetch_array($result1)) {
                                                                                $cntr += 1;
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?php echo $cntr; ?></td>
                                                                                    <td><?php echo $row1[2]; ?></td>
                                                                                    <td><?php echo $row1[3]; ?></td>
                                                                                    <td><?php echo $row1[4]; ?></td>
                                                                                </tr>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div> 
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <fieldset class="basic_person_fs4"><legend class="basic_person_lg">POSITIONS</legend> 
                                                            <div  class="col-sm-12" style="padding:0px !important;">
                                                                <table id="positionsRO" class="table table-striped table-bordered orgAsgnmentsTblsRO" cellspacing="0" width="100%" style="width:100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>No.</th>
                                                                            <th>Position Name</th>
                                                                            <th>Start Date</th>
                                                                            <th>End Date</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        if ($pkID > 0) {
                                                                            $cntr = 0;
                                                                            $result1 = get_Pos($pkID);
                                                                            while ($row1 = loc_db_fetch_array($result1)) {
                                                                                $cntr += 1;
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?php echo $cntr; ?></td>
                                                                                    <td><?php echo $row1[2]; ?></td>
                                                                                    <td><?php echo $row1[3]; ?></td>
                                                                                    <td><?php echo $row1[4]; ?></td>
                                                                                </tr>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div> 
                                                        </fieldset>
                                                    </div>                                        
                                                </div>
                                            </div>
                                            <!-- /.tab-pane -->
                                            <div class="tab-pane" id="prflCVRO">
                                                <?php
                                                /* Curiculumn Vitae */
                                                if ($sbmtdPersonID <= 0) {
                                                    $pkID = $prsnid;
                                                } else {
                                                    $pkID = $sbmtdPersonID;
                                                }
                                                $cntr = 0;
                                                ?> 
                                                <div class="row">
                                                    <div class="col-sm-12"> 
                                                        <fieldset class="basic_person_fs4"><legend class="basic_person_lg">EDUCATIONAL BACKGROUND</legend> 
                                                            <div  class="col-sm-12" style="padding:0px !important;">
                                                                <table id="educBkgrdCVRO" class="table table-striped table-bordered cvTblsRO" cellspacing="0" width="100%" style="width:100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>No.</th>		
                                                                            <th>Course Name</th>
                                                                            <th>School/Institution</th>
                                                                            <th>School Location</th>
                                                                            <th>Start Date</th>
                                                                            <th>End Date</th>
                                                                            <th>Certificate Obtained</th>
                                                                            <th>Certificate Type</th>
                                                                            <th>Date Awarded</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        if ($pkID > 0) {
                                                                            $result1 = null;
                                                                            if ($sbmtdPersonID <= 0) {
                                                                                $result1 = get_EducBkgrdSelf($pkID);
                                                                            } else {
                                                                                $result1 = get_EducBkgrd($pkID);
                                                                            }
                                                                            while ($row1 = loc_db_fetch_array($result1)) {
                                                                                $cntr += 1;
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?php echo $cntr; ?></td>
                                                                                    <td><?php echo $row1[1]; ?></td>
                                                                                    <td><?php echo $row1[2]; ?></td>
                                                                                    <td><?php echo $row1[3]; ?></td>
                                                                                    <td><?php echo $row1[4]; ?></td>
                                                                                    <td><?php echo $row1[5]; ?></td>
                                                                                    <td><?php echo $row1[6]; ?></td>
                                                                                    <td><?php echo $row1[7]; ?></td>
                                                                                    <td><?php echo $row1[8]; ?></td>
                                                                                </tr>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div> 
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-sm-12"> 
                                                        <fieldset class="basic_person_fs4"><legend class="basic_person_lg">WORKING EXPERIENCE</legend> 
                                                            <div  class="col-sm-12" style="padding:0px !important;">
                                                                <table  id="workBkgrdCVRO" class="table table-striped table-bordered cvTblsRO" cellspacing="0" width="100%" style="width:100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>No.</th>		
                                                                            <th>Job Name/Title</th>
                                                                            <th>Institution Name</th>
                                                                            <th>Job Location</th>
                                                                            <th>Start Date</th>
                                                                            <th>End Date</th>
                                                                            <th>Job Description</th>
                                                                            <th>Feats/Achievements</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        if ($pkID > 0) {
                                                                            $result1 = null;
                                                                            if ($sbmtdPersonID <= 0) {
                                                                                $result1 = get_WrkBkgrdSelf($pkID);
                                                                            } else {
                                                                                $result1 = get_WrkBkgrd($pkID);
                                                                            }
                                                                            while ($row1 = loc_db_fetch_array($result1)) {
                                                                                $cntr += 1;
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?php echo $cntr; ?></td>
                                                                                    <td><?php echo $row1[1]; ?></td>
                                                                                    <td><?php echo $row1[2]; ?></td>
                                                                                    <td><?php echo $row1[3]; ?></td>
                                                                                    <td><?php echo $row1[4]; ?></td>
                                                                                    <td><?php echo $row1[5]; ?></td>
                                                                                    <td><?php echo $row1[6]; ?></td>
                                                                                    <td><?php echo $row1[7]; ?></td>
                                                                                </tr>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div> 
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-sm-12"> 
                                                        <fieldset class="basic_person_fs4"><legend class="basic_person_lg">SKILLS/NATURE</legend> 
                                                            <div  class="col-sm-12" style="padding:0px !important;">
                                                                <table  id="skillsBkgrdCVRO" class="table table-striped table-bordered cvTblsRO" cellspacing="0" width="100%" style="width:100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>No.</th>		
                                                                            <th>Languages</th>
                                                                            <th>Hobbies</th>
                                                                            <th>Interests</th>
                                                                            <th>Conduct</th>
                                                                            <th>Attitude</th>
                                                                            <th>From Date</th>
                                                                            <th>To Date</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        if ($pkID > 0) {
                                                                            $result1 = null;
                                                                            if ($sbmtdPersonID <= 0) {
                                                                                $result1 = get_SkillNatureSelf($pkID);
                                                                            } else {
                                                                                $result1 = get_SkillNature($pkID);
                                                                            }
                                                                            while ($row1 = loc_db_fetch_array($result1)) {
                                                                                $cntr += 1;
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?php echo $cntr; ?></td>
                                                                                    <td><?php echo $row1[1]; ?></td>
                                                                                    <td><?php echo $row1[2]; ?></td>
                                                                                    <td><?php echo $row1[3]; ?></td>
                                                                                    <td><?php echo $row1[4]; ?></td>
                                                                                    <td><?php echo $row1[5]; ?></td>
                                                                                    <td><?php echo $row1[6]; ?></td>
                                                                                    <td><?php echo $row1[7]; ?></td>
                                                                                </tr>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div> 
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.tab-pane -->
                                            <div class="tab-pane" id="prflOthrInfoRO">
                                                <?php
                                                /* All Attached Documents */
                                                if ($sbmtdPersonID <= 0) {
                                                    $pkID = $prsnid;
                                                } else {
                                                    $pkID = $sbmtdPersonID;
                                                }
                                                $total = 0;
                                                if ($sbmtdPersonID <= 0) {
                                                    $total = get_Total_AttachmentsSelf($srchFor, $pkID);
                                                } else {
                                                    $total = get_Total_Attachments($srchFor, $pkID);
                                                }
                                                if ($pageNo > ceil($total / $lmtSze)) {
                                                    $pageNo = 1;
                                                } else if ($pageNo < 1) {
                                                    $pageNo = ceil($total / $lmtSze);
                                                }

                                                $curIdx = $pageNo - 1;
                                                $attchSQL = "";
                                                $result2 = null;
                                                if ($sbmtdPersonID <= 0) {
                                                    $result2 = get_AttachmentsSelf($srchFor, $curIdx, $lmtSze, $pkID, $attchSQL);
                                                } else {
                                                    $result2 = get_Attachments($srchFor, $curIdx, $lmtSze, $pkID, $attchSQL);
                                                }
                                                $colClassType1 = "col-sm-2";
                                                $colClassType2 = "col-sm-3";
                                                $colClassType3 = "col-sm-4";
                                                $vwtyp = 5;
                                                ?>                    
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div  id="attchdDocsList">
                                                            <form class="form-horizontal" id="attchdDocsTblForm">
                                                                <div class="row" style="display:none;">
                                                                    <div class="<?php echo $colClassType2; ?>">
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control" placeholder="Search For" id="attchdDocsSrchFor" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncAttchdDocs(event, '', '#attchdDocsList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                                                            <input id="attchdDocsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                                                            <div class="input-group-append handCursor" onclick="getAttchdDocs('clear', '#attchdDocsList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                                                                <span class="input-group-text rhoclickable"><i class="fas fa-times"></i></span>
                                                                            </div>
                                                                            <div class="input-group-append handCursor" onclick="getAttchdDocs('', '#attchdDocsList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                                                                <span class="input-group-text rhoclickable"><i class="fas fa-search"></i></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="<?php echo $colClassType2; ?>">
                                                                        <div class="input-group">                                                                                
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-filter"></i></span>
                                                                            </div>
                                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="attchdDocsDsplySze" style="min-width:70px !important;">                            
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
                                                                                    <a class="rhopagination" href="javascript:getAttchdDocs('previous', '#attchdDocsList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                                                                        <span aria-hidden="true">&laquo;</span>
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a class="rhopagination" href="javascript:getAttchdDocs('next', '#attchdDocsList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                                                                        <span aria-hidden="true">&raquo;</span>
                                                                                    </a>
                                                                                </li>
                                                                            </ul>
                                                                        </nav>
                                                                    </div>
                                                                </div>
                                                                <div class="row"> 
                                                                    <div class="col-sm-12">
                                                                        <table class="table table-striped table-bordered" id="attchdDocsTable" cellspacing="0" width="100%" style="width:100%;">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>No.</th>
                                                                                    <th>Doc. Name/Description</th>
                                                                                    <th>&nbsp;</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php
                                                                                $cntr = 0;
                                                                                while ($row2 = loc_db_fetch_array($result2)) {
                                                                                    $cntr += 1;
                                                                                    $doc_src_encrpt = "";
                                                                                    if ($sbmtdPersonID <= 0) {
                                                                                        $doc_src = $ftp_base_db_fldr . "/PrsnDocs/Request/" . $row2[3];
                                                                                        $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                                                                                        if (file_exists($doc_src)) {
                                                                                            //file exists!
                                                                                        } else {
                                                                                            $doc_src1 = $ftp_base_db_fldr . "/PrsnDocs/" . $row2[3];
                                                                                            if (file_exists($doc_src1)) {
                                                                                                $temp = explode(".", $row2[3]);
                                                                                                $extension = end($temp);
                                                                                                $doc_src = $ftp_base_db_fldr . "/PrsnDocs/Request/" . $row2[0] . "." . $extension;
                                                                                                copy($doc_src1, $doc_src);
                                                                                                updatePrsnDocFlNmSelf($row2[0], $row2[0] . "." . $extension);
                                                                                                $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                                                                                                if (file_exists($doc_src)) {
                                                                                                    //file exists!
                                                                                                } else {
                                                                                                    //file does not exist.
                                                                                                    $doc_src_encrpt = "None";
                                                                                                }
                                                                                            } else {
                                                                                                //file does not exist.
                                                                                                $doc_src_encrpt = "None";
                                                                                            }
                                                                                        }
                                                                                    } else {
                                                                                        $doc_src = $ftp_base_db_fldr . "/PrsnDocs/" . $row2[3];
                                                                                        $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                                                                                        if (file_exists($doc_src)) {
                                                                                            //file exists!
                                                                                        } else {
                                                                                            //file does not exist.
                                                                                            $doc_src_encrpt = "None";
                                                                                        }
                                                                                    }
                                                                                    ?>
                                                                                    <tr id="attchdDocsRow_<?php echo $cntr; ?>">                                    
                                                                                        <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                                        <td class="lovtd">                                                                   
                                                                                            <span><?php echo $row2[2]; ?></span>
                                                                                            <input type="hidden" class="form-control" aria-label="..." id="attchdDocsRow<?php echo $cntr; ?>_AttchdDocsID" value="<?php echo $row2[0]; ?>" style="width:100% !important;">                                              
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
                                                                                                    <img src="../cmn_images/dwldicon.png" style="height:15px; width:auto; position: relative; vertical-align: middle;"> Download
                                                                                                </button>
                                                                                            <?php } ?>
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
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                /* Other Information */
                                                $cntr = 0;
                                                $table_id = getMdlGrpID("Person Data", $mdlNm);
                                                $ext_inf_tbl_name = "prs.prsn_all_other_info_table";
                                                $ext_inf_seq_name = "prs.prsn_all_other_info_table_dflt_row_id_seq";
                                                $row_pk_id = $pkID;
                                                ?>
                                                <hr style="border-top:1px dashed #ddd !important;">
                                                <div class="row" style="margin-top:30px !important;">
                                                    <div  class="col-sm-12">
                                                        <fieldset class="" style="padding:10px 0px 5px cccc//px !important;">
                                                            <legend class="basic_person_lg">Other Information</legend>
                                                            <form class="form-horizontal" id="OtherInfoTblForm">
                                                                <table  id="otherExtrInfoRO" class="table table-striped table-bordered otherInfoTblsEDT" cellspacing="0" width="100%" style="width:100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <!--<th>No.</th>-->
                                                                            <th>Category</th>
                                                                            <th>Label</th>
                                                                            <th>Value</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        if ($pkID > 0) {
                                                                            $brghtsqlStr = "";
                                                                            $result1 = getAllwdExtInfosNVals("%", "Extra Info Label", 0, 1000000000, $brghtsqlStr,
                                                                                    $table_id, $row_pk_id, $ext_inf_tbl_name, $orgID);
                                                                            while ($row1 = loc_db_fetch_array($result1)) {
                                                                                $cntr += 1;
                                                                                ?>
                                                                                <tr>
                                                                                    <!--<td><?php echo $cntr; ?></td>-->
                                                                                    <td><?php echo $row1[0]; ?></td>
                                                                                    <td><?php echo $row1[1]; ?></td>
                                                                                    <td><?php echo $row1[2]; ?></td>
                                                                                </tr>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </form>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.tab-pane -->                                            
                                            <div class="tab-pane" id="prflAccountRO">
                                                <?php
                                                $canEdtUsers = false;
                                                $canVwRcHstry = false;
                                                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Role Name";
                                                if ($sortBy == "") {
                                                    $sortBy = "Role Name";
                                                }
                                                $pkID = isset($_POST['sbmtdUserID']) ? $_POST['sbmtdUserID'] : -1;
                                                if ($pkID <= 0) {
                                                    $pkID = $usrID;
                                                }
                                                $result = get_OneUser($pkID);
                                                ?>
                                                <form id='userRolesForm' class="form-horizontal" action='' method='post' accept-charset='UTF-8'>
                                                    <div class="row">
                                                        <?php
                                                        while ($row = loc_db_fetch_array($result)) {
                                                            ?>    
                                                            <div class="col-sm-12 basic_person_lg">User Summary Information</div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group row">
                                                                    <label for="usrPrflAccountName" class="control-label col-sm-4">User Name:</label>
                                                                    <div class="col-sm-8">
                                                                        <?php
                                                                        if ($canEdtUsers === true) {
                                                                            ?>
                                                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="usrPrflAccountName" value="<?php echo $row[0]; ?>">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="usrPrflAccountID" value="<?php echo $row[10]; ?>">
                                                                        <?php } else { ?>
                                                                            <span><?php echo $row[0]; ?></span>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div> 
                                                                <div class="form-group row">
                                                                    <label for="usrPrflAcntOwner" class="control-label col-sm-4">Owned By:</label>
                                                                    <div  class="col-sm-8">
                                                                        <?php
                                                                        if ($canEdtUsers === true) {
                                                                            ?>
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="usrPrflAcntOwner" value="<?php echo $row[1]; ?>" readonly="true">
                                                                                <input type="hidden" class="form-control" aria-label="..." id="usrPrflAcntOwnerLocID" value="<?php echo $row[11]; ?>">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Persons', '', '', '', 'radio', true, '<?php echo $row[11]; ?>', 'usrPrflAcntOwnerLocID', 'usrPrflAcntOwner', 'clear', 1, '');">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $row[1]; ?></span>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>  
                                                                <div class="form-group row">
                                                                    <label for="usrPrflModulesNeeded" class="control-label col-sm-4">Modules Needed:</label>
                                                                    <div  class="col-sm-8">
                                                                        <?php
                                                                        if ($canEdtUsers === true) {
                                                                            ?>
                                                                            <select class="form-control selectpicker" id="usrPrflModulesNeeded">  
                                                                                <option value="" selected disabled>Please Select...</option>
                                                                                <?php
                                                                                $valslctdArry = array("", "", "", "", "",
                                                                                    "", "", "", "", "",
                                                                                    "", "", "", "", "",
                                                                                    "", "", "", "", "",
                                                                                    "", "", "", "", "", "");
                                                                                $optionsArrys = array(
                                                                                    "Person Records Only",
                                                                                    "Point of Sale Only",
                                                                                    "Accounting Only",
                                                                                    "Person Records with Accounting Only",
                                                                                    "Sales with Accounting Only",
                                                                                    "Accounting with Payroll Only",
                                                                                    "Person Records + Hospitality Only",
                                                                                    "Person Records + Events Only",
                                                                                    "Basic Modules Only",
                                                                                    "Basic Modules + Hospitality Only",
                                                                                    "Basic Modules + Events Only",
                                                                                    "Basic Modules + Projects Only",
                                                                                    "Basic Modules + Appointments Only",
                                                                                    "Basic Modules + PMS Only",
                                                                                    "Basic Modules + Events + Hospitality Only",
                                                                                    "Basic Modules - Payroll - Person Records + Events + Hospitality Only",
                                                                                    "Basic Modules + Payroll - Person Records + Events + Hospitality Only",
                                                                                    "Basic Modules + Events + PMS Only",
                                                                                    "Basic Modules + Projects + PMS Only",
                                                                                    "Basic Modules + Projects + Hospitality Only",
                                                                                    "Basic Modules + Projects + Events Only",
                                                                                    "Basic Modules + Events + Hospitality + PMS Only",
                                                                                    "Basic Modules + Projects + Hospitality + PMS Only",
                                                                                    "Basic Modules + Events + Projects + Hospitality Only",
                                                                                    "Basic Modules + Events + Projects + Hospitality + PMS Only",
                                                                                    "All Modules");

                                                                                for ($z = 0; $z < count($optionsArrys); $z++) {
                                                                                    if ($row[14] == $optionsArrys[$z]) {
                                                                                        $valslctdArry[$z] = "selected";
                                                                                    }
                                                                                    ?>
                                                                                    <option value="<?php echo $optionsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $optionsArrys[$z]; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $row[14]; ?></span>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div> 
                                                                <div class="form-group row">
                                                                    <label for="pswwdAge" class="control-label col-sm-4">Password Age:</label>
                                                                    <div  class="col-sm-8">
                                                                        <span><?php echo $row[9]; ?></span>
                                                                    </div>
                                                                </div> 
                                                                <div class="form-group row">
                                                                    <label for="failedLgns" class="control-label col-sm-6">Failed Login Attempts:</label>
                                                                    <div  class="col-sm-6">
                                                                        <span><?php echo $row[6]; ?></span>
                                                                    </div>
                                                                </div>     
                                                            </div>
                                                            <div class="col-sm-6">    
                                                                <div class="form-group row">
                                                                    <label for="lastLgns" class="control-label col-sm-6">Last Login Attempt:</label>
                                                                    <div  class="col-sm-6">
                                                                        <span><?php echo $row[7]; ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="lastChnge" class="control-label col-sm-6">Last Password Change:</label>
                                                                    <div  class="col-sm-6">
                                                                        <span><?php echo $row[8]; ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="usrPrflLnkdCstmr" class="control-label col-sm-4">Customer / Supplier:</label>
                                                                    <div  class="col-sm-8">                                                                        
                                                                        <?php
                                                                        if ($canEdtUsers === true) {
                                                                            ?>
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control" aria-label="..." id="usrPrflLnkdCstmr" value="<?php echo $row[16]; ?>" readonly="true">
                                                                                <input type="hidden" class="form-control" aria-label="..." id="usrPrflLnkdCstmrID" value="<?php echo $row[15]; ?>">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', '', '', '', 'radio', true, '<?php echo $row[15]; ?>', 'usrPrflLnkdCstmrID', 'usrPrflLnkdCstmr', 'clear', 1, '');">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        <?php } else { ?>                                                                        
                                                                            <span><?php echo $row[16]; ?></span>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div> 
                                                                <div class="form-group row">
                                                                    <label for="usrPrflVldtyStartDate" class="control-label col-sm-4">Start Date:</label>
                                                                    <div  class="col-sm-8">
                                                                        <?php
                                                                        if ($canEdtUsers === true) {
                                                                            ?>
                                                                            <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss">
                                                                                <input class="form-control" size="16" type="text" id="usrPrflVldtyStartDate" value="<?php echo $row[2]; ?>" readonly="">
                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $row[2]; ?></span>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="usrPrflVldtyEndDate" class="control-label col-sm-4">End Date:</label>
                                                                    <div  class="col-sm-8">
                                                                        <?php
                                                                        if ($canEdtUsers === true) {
                                                                            ?>
                                                                            <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss">
                                                                                <input class="form-control" size="16" type="text" id="usrPrflVldtyEndDate" value="<?php echo $row[3]; ?>" readonly="">
                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $row[3]; ?></span>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                        $srchFor = "%";
                                                        $lmtSze = 100;
                                                        $srchIn = "Role Name";
                                                        $total = get_TtlUsersRoles($srchFor, $srchIn, $pkID);
                                                        if ($pageNo > ceil($total / $lmtSze)) {
                                                            $pageNo = 1;
                                                        } else if ($pageNo < 1) {
                                                            $pageNo = ceil($total / $lmtSze);
                                                        }
                                                        $curIdx = $pageNo - 1;
                                                        $result1 = get_UsersRoles($srchFor, $srchIn, $curIdx, $lmtSze, $pkID, $sortBy);
                                                        $colClassType1 = "col-sm-2";
                                                        $colClassType2 = "col-sm-3";
                                                        ?>
                                                    </div>
                                                    <div class="row">
                                                        <div  class="col-sm-12" style="padding:0px !important;">
                                                            <fieldset class=""><legend class="basic_person_lg">Assigned User Roles</legend>
                                                                <table class="table table-striped table-bordered" id="userRolesTable" cellspacing="0" width="100%" style="width:100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>No.</th>
                                                                            <th>Role Name</th>
                                                                            <th>Start Date</th>
                                                                            <th>End Date</th>
                                                                            <?php if ($canVwRcHstry === true) { ?>
                                                                                <th>...</th>
                                                                            <?php } ?>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $cntr = 0;
                                                                        while ($row1 = loc_db_fetch_array($result1)) {
                                                                            $cntr += 1;
                                                                            ?>
                                                                            <tr id="usrPrflEdtRow_<?php echo $cntr; ?>">                                    
                                                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                                                <td class="lovtd">
                                                                                    <?php if ($canEdtUsers === true) { ?>
                                                                                        <div class="form-group row col-sm-12">
                                                                                            <div class="input-group"  style="width:100%;">
                                                                                                <input type="text" class="form-control" aria-label="..." id="usrPrflEdtRow<?php echo $cntr; ?>_RoleNm" value="<?php echo $row1[0]; ?>">
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="usrPrflEdtRow<?php echo $cntr; ?>_RoleID" value="<?php echo $row1[3]; ?>">
                                                                                                <input type="hidden" class="form-control" aria-label="..." id="usrPrflEdtRow<?php echo $cntr; ?>_DfltRowID" value="<?php echo $row1[4]; ?>">
                                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'User Roles', '', '', '', 'radio', true, '<?php echo $row1[3]; ?>', 'usrPrflEdtRow<?php echo $cntr; ?>_RoleID', 'usrPrflEdtRow<?php echo $cntr; ?>_RoleNm', 'clear', 1, '');">
                                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                    <?php } else { ?>
                                                                                        <span><?php echo $row1[0]; ?></span>
                                                                                    <?php } ?>                                                         
                                                                                </td>
                                                                                <td class="lovtd">
                                                                                    <?php if ($canEdtUsers === true) { ?>
                                                                                        <div class="form-group row col-sm-12">
                                                                                            <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                                                <input class="form-control" size="16" type="text" id="usrPrflEdtRow<?php echo $cntr; ?>_StrtDte" value="<?php echo $row1[1]; ?>" readonly="">
                                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                            </div>                                                                
                                                                                        </div>
                                                                                    <?php } else { ?>
                                                                                        <span><?php echo $row1[1]; ?></span>
                                                                                    <?php } ?>                                                         
                                                                                </td>
                                                                                <td class="lovtd">
                                                                                    <?php if ($canEdtUsers === true) { ?>
                                                                                        <div class="form-group row col-sm-12">
                                                                                            <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                                                <input class="form-control" size="16" type="text" id="usrPrflEdtRow<?php echo $cntr; ?>_EndDte" value="<?php echo $row1[2]; ?>" readonly="">
                                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                            </div>                                                                
                                                                                        </div>
                                                                                    <?php } else { ?>
                                                                                        <span><?php echo $row1[2]; ?></span>
                                                                                    <?php } ?>                                                         
                                                                                </td>
                                                                                <?php if ($canVwRcHstry === true) { ?>
                                                                                    <td class="lovtd">
                                                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row1[4] . "|sec.sec_users_n_roles|dflt_row_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
                                                                                            <img src="../cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
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
                                                </form>
                                            </div>
                                            <!-- /.tab-pane -->
                                        </div>
                                        <!-- /.tab-content -->
                                    </div><!-- /.card-body -->
                                </div>
                                <!-- /.nav-tabs-custom -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </section>
                <?php
            }
        }
    } else if ($vwtyp == "1") {
        //Edit Profile
        if ($sbmtdPersonID <= 0) {
            $pkID = $prsnid;
        } else {
            $pkID = $sbmtdPersonID;
        }
        $orgType = getPssblValNm((int) (getGnrlRecNm(
                        "org.org_details", "org_id", "org_typ_id", $orgID)));
        $daReligionLbl = "Religion:";
        if (strtoupper($orgType) == "CHURCH") {
            $daReligionLbl = "Place of Worship / Name of Service:";
        }
        $rcrdExst = prsn_Record_Exist($pkID);
        $chngRqstExst = prsn_ChngRqst_Exist($pkID);
        $rqStatus = get_RqstStatus($pkID);
        if ($rqStatus == "Approved") {
            $sbmtdPersonID = $pkID;
        }
        if ($pkID > 0) {
            $result = null;
            if ($sbmtdPersonID <= 0) {
                $result = get_SelfPrsnDet($pkID);
            } else {
                $result = get_PrsnDet($pkID);
            }

            while ($row = loc_db_fetch_array($result)) {
                $nwFileName = "";
                $temp = explode(".", $row[2]);
                $extension = end($temp);
                if (trim($extension) == "") {
                    $extension = "png";
                }
                $ecnptDFlNm = encrypt1($row[2], $smplTokenWord1);
                $nwFileName = $ecnptDFlNm . "." . $extension;
                $ftp_src = "";
                if ($sbmtdPersonID <= 0) {
                    $ftp_src = $ftp_base_db_fldr . "/Person/Request/" . $pkID . "." . $extension;
                    if (!($rcrdExst == true && $chngRqstExst > 0 && file_exists($ftp_src))) {
                        $ftp_src = $ftp_base_db_fldr . "/Person/" . $pkID . "." . $extension;
                    }
                } else {
                    $ftp_src = $ftp_base_db_fldr . "/Person/" . $pkID . "." . $extension;
                }
                $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName;
                if (file_exists($ftp_src)) {
                    copy("$ftp_src", "$fullPemDest");
                } else if (!file_exists($fullPemDest)) {
                    $ftp_src = $fldrPrfx . 'cmn_images/image_up.png';
                    copy("$ftp_src", "$fullPemDest");
                }
                $nwFileName = $tmpDest . $nwFileName;

                // QR CODE data 
                $name = str_replace("  ", " ", $row[3] . " " . $row[4] . " " . $row[6] . " " . $row[5]);
                $sortName = $row[5] . ';' . $row[4];
                $phone = $row[16];
                $phonePrivate = '';
                $phoneCell = $row[17];
                $orgName = $row[21];

                $email = $row[15];
                $addressLabel = 'Address';
                $addressPobox = preg_replace("/[\r\n]+/", " ", $row[14]);

                $addressExt = '';
                $addressStreet = '';
                $addressTown = '';
                $addressRegion = '';
                $addressPostCode = '';
                $addressCountry = 'GH';
                // we building raw data 
                $codeContents = 'BEGIN:VCARD' . "\n";
                $codeContents .= 'VERSION:2.1' . "\n";
                $codeContents .= 'N:' . $sortName . "\n";
                $codeContents .= 'FN:' . $name . "\n";
                $codeContents .= 'ORG:' . $orgName . "\n";
                $codeContents .= 'TEL;WORK;VOICE:' . $phone . "\n";
                $codeContents .= 'TEL;HOME;VOICE:' . $phonePrivate . "\n";
                $codeContents .= 'TEL;TYPE=cell:' . $phoneCell . "\n";
                $codeContents .= 'ADR;TYPE=work;' .
                        'LABEL="' . $addressLabel . '":'
                        . $addressPobox . ';'
                        . $addressExt . ';'
                        . $addressStreet . ';'
                        . $addressTown . ';'
                        . $addressPostCode . ';'
                        . $addressCountry
                        . "\n";
                $codeContents .= 'EMAIL:' . $email . "\n";
                $codeContents .= 'END:VCARD';

                $rqStatus = get_RqstStatus($prsnid);
                $rqstID = get_RqstID($prsnid);
                $rqstatusColor = "red";
                if ($rqStatus == "Approved") {
                    $rqstatusColor = "green";
                }
                $actType = 1;
                ?>
                <div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark">Edit My Personal Profile</h1>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="javascript:openATab('#allmodules', 'grp=42&typ=1');">All Apps</a></li>
                                    <li class="breadcrumb-item"><a href="javascript:openATab('#allmodules', 'grp=50&typ=1&vtyp=999');">Personal Stuff</a></li>
                                    <li class="breadcrumb-item active"><a href="javascript:openATab('#allmodules', 'grp=50&typ=1&pg=2&vtyp=0');">Profile</a></li>
                                    <li class="breadcrumb-item active">Edit Profile</li>
                                </ol>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->
                <!-- Main content -->
                <?php
                $vPsblValID1 = getEnbldPssblValID("Application Instance SHORT CODE", getLovID("All Other General Setups"));
                $vPsblVal1 = getPssblValDesc($vPsblValID1);
                $rhoOptionalFlds = "";
                $rhoAllwdTabs = "#prflHomeEDT|#prflAddPrsnDataEDT|#prflCVEDT|#prflOthrInfoEDT";
                if ($vPsblVal1 == "TAKBG_SWLFR_APP_1") {
                    $rhoOptionalFlds = "hideNotice";
                    $rhoAllwdTabs = "#prflHomeEDT|#prflAddPrsnDataEDT";
                }
                ?>
                <section class="content"  style="padding: 5px 0px 5px 0px !important;">
                    <div class="container-fluid">       
                        <form method="POST"  id="bscPrsnPrflForm" class="form-horizontal signup-form wizard clearfix" novalidate="novalidate" role="application" style="padding:5px;border:1px solid #ddd;border-radius:10px;">
                            <div class="steps clearfix">
                                <ul id="prsnPrflTabs" role="tablist">
                                    <li role="tab" class="current" aria-disabled="false" aria-selected="true" id="prflHomeEDTLi">
                                        <a data-toggle="tabajxprfledt"  href="#prflHomeEDT" id="prflHomeEDTtab">
                                            <span class="current-info audible"> </span>
                                            <div class="title">
                                                <span class="number">1</span>
                                                <span class="title_text">Personal Information</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li role="tab" class="disabled" aria-disabled="true" id="prflAddPrsnDataEDTLi">
                                        <a data-toggle="tabajxprfledt" href="#prflAddPrsnDataEDT" id="prflAddPrsnDataEDTtab">
                                            <div class="title">
                                                <span class="number">2</span>
                                                <span class="title_text">Additional Information</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li role="tab" class="disabled <?php echo $rhoOptionalFlds; ?>" aria-disabled="true" id="prflCVEDTLi">
                                        <a data-toggle="tabajxprfledt" href="#prflCVEDT" id="prflCVEDTtab">
                                            <div class="title">
                                                <span class="number">3</span>
                                                <span class="title_text">CV</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li role="tab" class="disabled <?php echo $rhoOptionalFlds; ?>" aria-disabled="true" id="prflOthrInfoEDTLi">
                                        <a data-toggle="tabajxprfledt" href="#prflOthrInfoEDT" id="prflOthrInfoEDTtab">
                                            <div class="title">
                                                <span class="number">4</span>
                                                <span class="title_text">Supporting Documents</span>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="content clearfix">
                                <div class="row" style="margin: 5px 0px 5px 0px !important;">
                                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                        <div class="col-md-4" style="padding:0px 0px 0px 0px;float:left !important;">
                                            <div class="form-group form-group-sm">
                                                <label for="crntOrgName" class="control-label col-md-4" style="padding:10px 0px 0px 0px;">Organisation:</label>
                                                <div  class="col-md-8" style="padding:8px 0px 0px 0px;">
                                                    <span><?php echo $crntOrgName; ?></span>
                                                    <!--<div class="input-group">
                                                       <input type="text" class="form-control" aria-label="..." id="crntOrgName" value="<?php echo $crntOrgName; ?>">
                                                       <input type="hidden" id="crntOrgID" value="<?php echo $orgID; ?>">
                                                       <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Organisations', '', '', '', 'radio', true, '<?php echo $orgID; ?>', 'crntOrgID', 'crntOrgName', 'clear', 1, '');">
                                                           <span class="glyphicon glyphicon-th-list"></span>
                                                       </label>
                                                   </div>-->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8" style="padding:0px 0px 0px 0px;float:right !important;">
                                            <div class="" style="padding:0px 0px 0px 0px;float:right !important;">
                                                <?php
                                                $rqStatus = get_RqstStatus($prsnid);
                                                $rqstID = get_RqstID($prsnid);
                                                $rqstatusColor = "red";
                                                if ($rqStatus == "Approved") {
                                                    $rqstatusColor = "green";
                                                }
                                                $actType = 1;
                                                ?>
                                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;" id="mySelfStatusBtn"><span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $rqStatus; ?></span></button>
                                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;" onclick="openATab('#allmodules', 'grp=50&typ=1&pg=1&vtyp=1');">
                                                    <img src="../cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 0px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;" onclick="getPrsnProfilePDF(<?php echo $pkID; ?>);"><img src="../cmn_images/pdf.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;"></button>
                                                <?php
                                                if ($rqStatus == "Requires Approval" || $rqStatus == "Withdrawn" || $rqStatus == "Rejected" || $rqStatus == "Approved") {
                                                    ?>                             
                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;" onclick="saveBasicPrsnData(<?php echo $actType; ?>, 0);">
                                                        <img src="../cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save for Later&nbsp;
                                                    </button>
                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;" onclick="saveBasicPrsnData(<?php echo $actType; ?>, 1);"><img src="../cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Submit for Approval&nbsp;</button>
                                                    <?php
                                                } else if ($rqStatus != "Approved") {
                                                    ?>                                    
                                                    <button type="button" class="btn btn-default" style="margin-bottom: 1px;" onclick="wthdrwRqst();"><img src="../cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Withdraw from Approvers&nbsp;</button>                                
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>                    
                                </div> 
                                <div id="prflHomeEDT" role="tabpanel" class="tab-pane fadein active" style="border:none !important;"> 
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Person's Picture</legend>
                                                <div style="margin-bottom: 10px;width:100% !important;justify-content: center;display:flex;">
                                                    <img src="<?php echo $app_url . $nwFileName; ?>" alt="..." id="img1Test" class="img-rounded center-block img-responsive" style="height: 195px !important; width: auto !important;max-width: 100% !important;">                                            
                                                </div>
                                                <div class="form-group form-group-sm">
                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend button-wrap">
                                                                <label class="input-group-text rhoclickable handCursor" for="daPrsnPicture"><i class="fas fa-paperclip"></i> Browse... </label>
                                                                <input type="file" id="daPrsnPicture" name="daPrsnPicture" onchange="changeImgSrc(this, '#img1Test', '#img1SrcLoc');" class="btn btn-default" style="display: none;">                                                            
                                                            </div>  
                                                            <input type="text" class="form-control" aria-label="..." id="img1SrcLoc" value="" >                                                        
                                                        </div>                                                    
                                                    </div>                                            
                                                </div>                                        
                                            </fieldset>
                                        </div>                                
                                        <div class="col-lg-5">
                                            <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Names</legend>
                                                <div class="form-group form-group-sm">
                                                    <label for="daPrsnLocalID" class="control-label col-md-4">ID No:</label>
                                                    <div class="col-md-8">
                                                        <span><?php echo $row[1]; ?></span>
                                                        <input id="daPrsnLocalID" name="daPrsnLocalID" type = "hidden" value="<?php echo $row[1]; ?>"/> 
                                                        <input type="hidden" id="daPersonID" name="daPersonID" value="<?php echo $row[0]; ?>"/>
                                                        <input type="hidden" id="daChngRqstID" name="daChngRqstID" value="<?php echo $rqstID; ?>"/>
                                                    </div>
                                                </div> 
                                                <div class="form-group form-group-sm">
                                                    <label for="daTitle" class="control-label col-md-4">Title:</label>
                                                    <div  class="col-md-8">
                                                        <input class="form-control" id="daTitle" name="daTitle" type = "text" placeholder="Title" value="<?php echo trim($row[3]); ?>" list="daTitleList">
                                                        <datalist id="daTitleList" name="daTitleList">
                                                            <?php
                                                            $brghtStr = "";
                                                            $isDynmyc = FALSE;
                                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Person Titles"), $isDynmyc, -1, "", "");
                                                            while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                $selectedTxt = "";
                                                                /* if ($titleRow[0] == $row[3]) {
                                                                  $selectedTxt = "selected";
                                                                  } */
                                                                ?>
                                                                <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </datalist>
                                                    </div>
                                                </div>  
                                                <div class="form-group form-group-sm">
                                                    <label for="daFirstName" class="control-label col-md-4">First Name:</label>
                                                    <div  class="col-md-8">
                                                        <input class="form-control rqrdFld" id="daFirstName" name="daFirstName" type = "text" placeholder="First Name" value="<?php echo $row[4]; ?>"/>
                                                    </div>
                                                </div> 
                                                <div class="form-group form-group-sm">
                                                    <label for="daSurName" class="control-label col-md-4">Surname:</label>
                                                    <div  class="col-md-8">
                                                        <input class="form-control rqrdFld" id="daSurName" name="daSurName" type = "text" placeholder="Surname" value="<?php echo $row[5]; ?>"/>
                                                    </div>
                                                </div>     
                                                <div class="form-group form-group-sm">
                                                    <label for="daOtherNames" class="control-label col-md-4">Other Names:</label>
                                                    <div  class="col-md-8">
                                                        <textarea class="form-control" id="daOtherNames" name="daOtherNames" cols="2" placeholder="Other Names" rows="3"><?php echo $row[6]; ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm">
                                                    <label for="daGender" class="control-label col-md-4">Gender:</label>
                                                    <div  class="col-md-8">
                                                        <select class="form-control rqrdFld" id="daGender" name="daGender">
                                                            <?php
                                                            $brghtStr = "";
                                                            $isDynmyc = FALSE;
                                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Gender"), $isDynmyc, -1, "", "");
                                                            while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                $selectedTxt = "";
                                                                if ($titleRow[0] == $row[8]) {
                                                                    $selectedTxt = "selected";
                                                                }
                                                                ?>
                                                                <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div> 
                                            </fieldset>
                                        </div>
                                        <div class="col-lg-5"> 
                                            <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Personal Data</legend>
                                                <div class="form-group form-group-sm">
                                                    <label for="daMaritalStatus" class="control-label col-md-4">Marital Status:</label>
                                                    <div  class="col-md-8">
                                                        <select class="form-control rqrdFld" id="daMaritalStatus" name="daMaritalStatus">
                                                            <?php
                                                            $brghtStr = "";
                                                            $isDynmyc = FALSE;
                                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Marital Status"), $isDynmyc, -1, "", "");
                                                            while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                $selectedTxt = "";
                                                                if ($titleRow[0] == $row[9]) {
                                                                    $selectedTxt = "selected";
                                                                }
                                                                ?>
                                                                <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm">
                                                    <label for="daDOB" class="control-label col-md-4">Date of Birth (DD-MMM-YYYY):</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group date rho-DatePicker"  id="daDOBDP" data-target-input="nearest">
                                                            <input class="form-control rqrdFld datetimepicker-input" size="16" type="text" id="daDOB" name="daDOB" value="<?php echo $row[10]; ?>" data-target="#daDOBDP" placeholder="DD-MMM-YYYY">
                                                            <div class="input-group-append handCursor" onclick="$('#daDOB').val('');">
                                                                <span class="input-group-text rhoclickable"><i class="fa fa-times"></i></span>
                                                            </div>
                                                            <div class="input-group-append handCursor" data-target="#daDOBDP" data-toggle="datetimepicker">
                                                                <span class="input-group-text rhoclickable"><i class="fa fa-calendar"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class="form-group form-group-sm">
                                                    <label for="daPOB" class="control-label col-md-4">Place of Birth:</label>
                                                    <div  class="col-md-8">
                                                        <textarea class="form-control" id="daPOB" name="daPOB" cols="2" placeholder="Place of Birth" rows="2"><?php echo $row[11]; ?></textarea>
                                                    </div>
                                                </div> 
                                                <div class="form-group form-group-sm">
                                                    <label for="daNationality" class="control-label col-md-4">Nationality:</label>
                                                    <div class="col-md-8">
                                                        <select class="form-control rqrdFld" id="daNationality" name="daNationality">
                                                            <?php
                                                            $brghtStr = "";
                                                            $isDynmyc = FALSE;
                                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Nationalities"), $isDynmyc, -1, "", "");
                                                            while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                $selectedTxt = "";
                                                                if ($titleRow[0] == $row[20]) {
                                                                    $selectedTxt = "selected";
                                                                }
                                                                ?>
                                                                <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>  
                                                <div class="form-group form-group-sm">
                                                    <label for="daHomeTown" class="control-label col-md-4">Home Town:</label>
                                                    <div  class="col-md-8">
                                                        <textarea class="form-control" id="daHomeTown" name="daHomeTown" cols="2" placeholder="Home Town" rows="1"><?php echo $row[19]; ?></textarea>
                                                    </div>
                                                </div> 
                                                <div class="form-group form-group-sm">
                                                    <label for="daReligion" class="control-label col-md-4"><?php echo $daReligionLbl; ?></label>
                                                    <div  class="col-md-8">
                                                        <input class="form-control" id="daReligion" name="daReligion" type = "text" placeholder="Religion" value="<?php echo $row[12]; ?>"/>
                                                    </div>
                                                </div>                                              
                                            </fieldset>   
                                        </div>
                                    </div>    
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <fieldset class="basic_person_fs2"><legend class="basic_person_lg">QR Code</legend>
                                                <div>
                                                    <img src="<?php echo "../" . getQRCodeUrl($codeContents, $ecnptDFlNm . "_QR"); ?>" alt="..." id="imgQrCode" class="img-thumbnail center-block img-responsive" style="height: 200px !important; width: auto !important;">                                            
                                                </div>                                       
                                            </fieldset>
                                        </div>                                
                                        <div class="col-lg-5">
                                            <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Contact Information</legend>
                                                <div class="form-group form-group-sm">
                                                    <label for="daCompany" class="control-label col-md-4">Workplace:</label>
                                                    <div  class="col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" aria-label="..." id="daCompany" name="daCompany" value="<?php echo $row[21]; ?>">
                                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                            <input type="hidden" id="daCompanyID" value="<?php echo $row[28]; ?>">
                                                            <div class="input-group-append handCursor"  onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'gnrlOrgID', '', '', 'radio', true, '<?php echo $row[21]; ?>', 'daCompanyID', 'daCompany', 'clear', 1, '');">
                                                                <span class="input-group-text rhoclickable"><i class="fas fa-th-list"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm">
                                                    <label for="daCompanyLoc" class="control-label col-md-4">Site/Branch:</label>
                                                    <div  class="col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" aria-label="..." id="daCompanyLoc" name="daCompanyLoc" value="<?php echo $row[22]; ?>">  
                                                            <div class="input-group-append handCursor"  onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Customer/Supplier Sites', 'daCompanyID', '', '', 'radio', true, '<?php echo $row[21]; ?>', '', 'daCompanyLoc', 'clear', 1, '');">
                                                                <span class="input-group-text rhoclickable"><i class="fas fa-th-list"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>  
                                                <div class="form-group form-group-sm">
                                                    <label for="daEmail" class="control-label col-md-4">Email:</label>
                                                    <div  class="col-md-8">
                                                        <input class="form-control rqrdFld" id="daEmail" name="daEmail" type = "email" placeholder="<?php echo $admin_email; ?>" value="<?php echo $row[15]; ?>"/>
                                                    </div>
                                                </div> 
                                                <div class="form-group form-group-sm">
                                                    <label for="daTelNos" class="control-label col-md-4">Contact Nos:</label>
                                                    <div  class="col-md-8">
                                                        <input class="form-control" id="daTelNos" name="daTelNos" type = "text" placeholder="Telephone" value="<?php echo $row[16]; ?>"/>
                                                        <input class="form-control rqrdFld" id="daMobileNos" name="daMobileNos" type = "text" placeholder="Mobile" value="<?php echo $row[17]; ?>"/>                                       
                                                    </div>
                                                </div>     
                                                <div class="form-group form-group-sm">
                                                    <label for="daFaxNo" class="control-label col-md-4">Fax:</label>
                                                    <div  class="col-md-8">
                                                        <input class="form-control" id="daFaxNo" name="daFaxNo" type = "text" placeholder="Fax" value="<?php echo $row[18]; ?>"/>
                                                    </div>
                                                </div> 
                                            </fieldset>                                                
                                        </div>
                                        <div class="col-lg-5">
                                            <fieldset class="basic_person_fs3"><legend class="basic_person_lg">Address</legend> 
                                                <div class="form-group form-group-sm">
                                                    <label for="daPostalAddress" class="control-label col-md-4">Postal Address:</label>
                                                    <div  class="col-md-8">
                                                        <textarea class="form-control" id="daPostalAddress" name="daPostalAddress" cols="2" placeholder="Postal Address" rows="4"><?php echo $row[14]; ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm">
                                                    <label for="daResAddress" class="control-label col-md-4">Residential Address:</label>
                                                    <div  class="col-md-8">
                                                        <textarea class="form-control" id="daResAddress" name="daResAddress" cols="2" placeholder="Residential Address" rows="4"><?php echo $row[13]; ?></textarea>
                                                    </div>
                                                </div> 
                                            </fieldset>  
                                        </div>
                                    </div> 
                                    <div class="row">                                         
                                        <div class="col-lg-12">
                                            <fieldset class="basic_person_fs3" style="padding: 1px 10px 1px 10px !important;">
                                                <legend class="basic_person_lg">National ID Cards</legend> 
                                                <div  class="col-md-12">
                                                    <?php if ($rqStatus == "Requires Approval" || $rqStatus == "Withdrawn" || $rqStatus == "Rejected" || $rqStatus == "Approved") { ?>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getNtnlIDForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'ntnlIDCardsForm', '', 'Add/Edit National ID', 11, 'ADD', -1, <?php echo $pkID; ?>);">
                                                            <img src="../cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Add National ID Card
                                                        </button>
                                                    <?php } ?>
                                                    <table id="nationalIDTblEDT" class="table table-striped table-bordered" cellspacing="0" width="100%" style="width:100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>&nbsp;</th>
                                                                <th>Country</th>
                                                                <th>ID Type</th>
                                                                <th>ID No.</th>
                                                                <th>Date Issued</th>
                                                                <th>Expiry Date</th>
                                                                <th>Other Information</th>
                                                                <th>&nbsp;</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            if ($sbmtdPersonID <= 0) {
                                                                $result1 = get_AllNtnlty_Self($pkID);
                                                            } else {
                                                                $result1 = get_AllNtnlty($pkID);
                                                            }
                                                            $cntr = 0;
                                                            while ($row1 = loc_db_fetch_array($result1)) {
                                                                $cntr++;
                                                                ?>
                                                                <tr id="ntnlIDCardsRow_<?php echo $cntr; ?>">
                                                                    <td class="lovtd">
                                                                        <button type="button" class="btn btn-default btn-sm" onclick="getNtnlIDForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'ntnlIDCardsForm', 'ntnlIDCardsRow_<?php echo $cntr; ?>', 'Add/Edit National ID', 11, 'EDIT', <?php echo $row1[0]; ?>, <?php echo $pkID; ?>);" style="padding:2px !important;">
                                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                            <img src="../cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                        <input type="hidden" value="<?php echo $row1[0]; ?>" id="ntnlIDCardsRow<?php echo $cntr; ?>_NtnlIDpKey"/>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <?php echo $row1[1]; ?>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <?php echo $row1[2]; ?>
                                                                    </td>
                                                                    <td class="lovtd"><?php echo $row1[3]; ?></td>
                                                                    <td class="lovtd"><?php echo $row1[4]; ?></td>
                                                                    <td class="lovtd"><?php echo $row1[5]; ?></td>
                                                                    <td class="lovtd"><?php echo $row1[6]; ?></td>
                                                                    <td class="lovtd">                                                                                    
                                                                        <button type="button" class="btn btn-default btn-sm" onclick="delNtnlID('ntnlIDCardsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete ID" style="padding:2px !important;" style="padding:2px !important;">
                                                                            <img src="../cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div> 
                                            </fieldset>
                                        </div>
                                    </div> 
                                </div>    
                                <div id="prflAddPrsnDataEDT" class="tab-pane fadein hideNotice" style="border:none !important;">
                                    <?php
                                    $rcrdExst = prsn_Record_Exist($pkID);
                                    $result = get_PrsExtrDataGrps($orgID);
                                    while ($row = loc_db_fetch_array($result)) {
                                        ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <fieldset class="basic_person_fs4">
                                                    <legend class="basic_person_lg"><?php echo $row[0]; ?></legend>
                                                    <?php
                                                    $result1 = get_PrsExtrDataGrpCols($row[0], $orgID);
                                                    $cntr1 = 0;
                                                    $gcntr1 = 0;
                                                    $cntr1Ttl = loc_db_num_rows($result1);
                                                    while ($row1 = loc_db_fetch_array($result1)) {
                                                        /* POSSIBLE FIELDS
                                                         * label
                                                         * textbox (for now only this)
                                                         * textarea (for now only this)
                                                         * readonly textbox with button
                                                         * readonly textbox with date
                                                         * textbox with number validation
                                                         */
                                                        if ($row1[7] == "Tabular") {
                                                            $vrsFieldIDs = "";
                                                            for ($i = 0; $i < $row1[9]; $i++) {
                                                                if ($i == $row1[9] - 1) {
                                                                    $vrsFieldIDs .= "prsExtrTblrDtCol_" . $i;
                                                                } else {
                                                                    $vrsFieldIDs .= "prsExtrTblrDtCol_" . $i . "|";
                                                                }
                                                            }
                                                            $fldVal = "";
                                                            if ($sbmtdPersonID <= 0) {
                                                                $fldVal = get_PrsExtrData_Self($pkID, $row1[1]);
                                                            } else {
                                                                $fldVal = get_PrsExtrData($pkID, $row1[1]);
                                                            }
                                                            ?>
                                                            <div class="row">
                                                                <div  class="col-md-12">                                                                    
                                                                    <?php if ($rqStatus == "Requires Approval" || $rqStatus == "Withdrawn" || $rqStatus == "Rejected" || $rqStatus == "Approved") { ?>
                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getAddtnlDataForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'addtnlPrsnTblrDataForm', '', 'Add/Edit Data', 12, 'ADD', -1, '<?php echo $vrsFieldIDs; ?>', <?php echo $row1[1]; ?>, 'extDataTblCol_<?php echo $row1[1]; ?>');">
                                                                            <img src="../cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            Add Data
                                                                        </button>
                                                                    <?php } ?>
                                                                    <input class="form-control" id="addtnlPrsnDataCol<?php echo $row1[1]; ?>" type = "hidden" placeholder="" value="<?php echo $fldVal; ?>"/>
                                                                    <table id="extDataTblCol_<?php echo $row1[1]; ?>" class="table table-striped table-bordered extPrsnDataTblEDT"  cellspacing="0" width="100%" style="width:100%;"><thead><th>&nbsp;&nbsp;...</th>
                                                                        <?php
                                                                        $fieldHdngs = $row1[11];
                                                                        $arry1 = explode(",", $fieldHdngs);
                                                                        $cntr = count($arry1);
                                                                        for ($i = 0; $i < $row1[9]; $i++) {
                                                                            if ($i <= $cntr - 1) {
                                                                                ?>
                                                                                <th><?php echo $arry1[$i]; ?></th>
                                                                                <?php
                                                                            } else {
                                                                                ?>
                                                                                <th>&nbsp;</th>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            $arry3 = explode("|", $fldVal);
                                                                            $cntr3 = count($arry3);
                                                                            $maxsze = (int) 320 / $row1[9];
                                                                            if ($maxsze > 100 || $maxsze < 80) {
                                                                                $maxsze = 100;
                                                                            }
                                                                            for ($j = 0; $j < $cntr3; $j++) {
                                                                                if (trim(str_replace("~", "", $arry3[$j])) == "") {
                                                                                    continue;
                                                                                }
                                                                                ?>
                                                                                <tr id="prsExtrTblrDtCol_<?php echo $row1[1]; ?>_Row<?php echo $j; ?>">
                                                                                    <td>
                                                                                        <button type="button" class="btn btn-default btn-sm" onclick="getAddtnlDataForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'addtnlPrsnTblrDataForm', 'prsExtrTblrDtCol_<?php echo $row1[1]; ?>_Row<?php echo $j; ?>', 'Add/Edit Data', 12, 'EDIT', <?php echo $pkID; ?>, '<?php echo $vrsFieldIDs; ?>', <?php echo $row1[1]; ?>, 'extDataTblCol_<?php echo $row1[1]; ?>');" style="padding:2px !important;">
                                                                                            <img src="../cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                        </button>
                                                                                    </td>
                                                                                    <?php
                                                                                    $arry2 = explode("~", $arry3[$j]);
                                                                                    $cntr2 = count($arry2);
                                                                                    for ($i = 0; $i < $row1[9]; $i++) {
                                                                                        if ($i <= $cntr2 - 1) {
                                                                                            ?>
                                                                                            <td><?php echo $arry2[$i]; ?></td>
                                                                                        <?php } else { ?>
                                                                                            <td>&nbsp;</td>
                                                                                            <?php
                                                                                        }
                                                                                    }
                                                                                    ?>
                                                                                </tr>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <?php
                                                        } else {
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
                                                                        <div  class="col-md-8">
                                                                            <?php
                                                                            $prsnDValPulld = "";
                                                                            if ($sbmtdPersonID <= 0) {
                                                                                $prsnDValPulld = get_PrsExtrData_Self($pkID, $row1[1]);
                                                                            } else {
                                                                                $prsnDValPulld = get_PrsExtrData($pkID, $row1[1]);
                                                                            }
                                                                            $isRqrdFld = ($row1[12] === "1") ? "rqrdFld" : "";
                                                                            if ($row1[4] == "Date") {
                                                                                ?>      
                                                                                <div class="input-group date rho-DatePicker"  id="addtnlPrsnDataCol<?php echo $row1[1]; ?>DP" data-target-input="nearest">
                                                                                    <input class="form-control <?php echo $isRqrdFld; ?> datetimepicker-input" size="16" type="text" id="addtnlPrsnDataCol<?php echo $row1[1]; ?>" name="addtnlPrsnDataCol<?php echo $row1[1]; ?>" value="<?php echo $prsnDValPulld; ?>" data-target="#addtnlPrsnDataCol<?php echo $row1[1]; ?>DP" placeholder="DD-MMM-YYYY">
                                                                                    <div class="input-group-append handCursor" onclick="$('#addtnlPrsnDataCol<?php echo $row1[1]; ?>').val('');">
                                                                                        <span class="input-group-text rhoclickable"><i class="fa fa-times"></i></span>
                                                                                    </div>
                                                                                    <div class="input-group-append handCursor" data-target="#addtnlPrsnDataCol<?php echo $row1[1]; ?>DP" data-toggle="datetimepicker">
                                                                                        <span class="input-group-text rhoclickable"><i class="fa fa-calendar"></i></span>
                                                                                    </div>
                                                                                </div>               
                                                                                <?php
                                                                            } else if ($row1[4] == "Number") {
                                                                                ?>
                                                                                <input class="form-control <?php echo $isRqrdFld; ?>" id="addtnlPrsnDataCol<?php echo $row1[1]; ?>" type = "text" placeholder="" value="<?php echo $prsnDValPulld; ?>"/>
                                                                                <?php
                                                                            } else {
                                                                                if ($row1[3] == "") {
                                                                                    if ($row1[6] <= 200) {
                                                                                        ?>
                                                                                        <input class="form-control <?php echo $isRqrdFld; ?>" id="addtnlPrsnDataCol<?php echo $row1[1]; ?>" type = "text" placeholder="" value="<?php echo $prsnDValPulld; ?>"/>
                                                                                        <?php
                                                                                    } else {
                                                                                        ?>
                                                                                        <textarea class="form-control <?php echo $isRqrdFld; ?>" id="addtnlPrsnDataCol<?php echo $row1[1]; ?>" cols="2" placeholder="" rows="2"><?php echo $prsnDValPulld; ?></textarea>
                                                                                        <?php
                                                                                    }
                                                                                } else {
                                                                                    $brghtStr = "";
                                                                                    $isDynmyc = FALSE;
                                                                                    $lovID = getLovID($row1[3]);
                                                                                    $titleCnt = getTtlLovValues("%", "Both", $brghtStr, $lovID, $isDynmyc, -1, "", "");
                                                                                    if ($titleCnt <= 100 && $row1[6] <= 200) {
                                                                                        ?>
                                                                                        <select class="form-control <?php echo $isRqrdFld; ?>" id="addtnlPrsnDataCol<?php echo $row1[1]; ?>">
                                                                                            <?php
                                                                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, $lovID, $isDynmyc, -1, "", "");
                                                                                            while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                                                $selectedTxt = "";
                                                                                                if ($titleRow[0] == $prsnDValPulld) {
                                                                                                    $selectedTxt = "selected";
                                                                                                }
                                                                                                ?>
                                                                                                <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                                                <?php
                                                                                            }
                                                                                            ?>
                                                                                        </select>
                                                                                    <?php } else if ($titleCnt > 100 && $row1[6] <= 200) {
                                                                                        ?>
                                                                                        <div class="input-group">
                                                                                            <input type="text" class="form-control <?php echo $isRqrdFld; ?>" aria-label="..." id="addtnlPrsnDataCol<?php echo $row1[1]; ?>" value="<?php echo $prsnDValPulld; ?>"> 
                                                                                            <div class="input-group-append handCursor" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $row1[3]; ?>', '', '', '', 'radio', true, '<?php echo $prsnDValPulld; ?>', 'valueElmntID', 'addtnlPrsnDataCol<?php echo $row1[1]; ?>', 'clear', 1, '');">
                                                                                                <span class="input-group-text rhoclickable"><i class="fas fa-th-list"></i></span>
                                                                                            </div>  
                                                                                        </div>
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <div class="input-group">
                                                                                            <textarea class="form-control <?php echo $isRqrdFld; ?>" id="addtnlPrsnDataCol<?php echo $row1[1]; ?>" cols="2" placeholder="" rows="2"><?php echo $prsnDValPulld; ?></textarea> 
                                                                                            <div class="input-group-append handCursor" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $row1[3]; ?>', '', '', '', 'radio', true, '<?php echo $prsnDValPulld; ?>', 'valueElmntID', 'addtnlPrsnDataCol<?php echo $row1[1]; ?>', 'clear', 1, '');">
                                                                                                <span class="input-group-text rhoclickable"><i class="fas fa-th-list"></i></span>
                                                                                            </div>
                                                                                        </div>                                                                    
                                                                                        <?php
                                                                                    }
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
                                                    }
                                                    if ($gcntr1 == 1) {
                                                        $gcntr1 = 0;
                                                    }
                                                    ?>
                                                </fieldset>
                                            </div>
                                            <?php ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div id="prflOrgAsgnEDT" class="tab-pane fadein hideNotice" style="border:none !important;padding:0px !important;">
                                    Designations
                                </div>    
                                <div id="prflCVEDT" class="tab-pane fadein hideNotice" style="border:none !important;">
                                    <div class="row">
                                        <div class="col-md-12"> 
                                            <fieldset class="basic_person_fs4"><legend class="basic_person_lg">EDUCATIONAL BACKGROUND</legend> 
                                                <div  class="col-md-12">                                                    
                                                    <?php if ($rqStatus == "Requires Approval" || $rqStatus == "Withdrawn" || $rqStatus == "Rejected" || $rqStatus == "Approved") { ?>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getEducBkgrdForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'educBkgrdForm', '', 'Add/Edit Educational Background', 20, 'ADD', -1, <?php echo $pkID; ?>);">
                                                            <img src="../cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Add Educational Background
                                                        </button>
                                                    <?php } ?>
                                                    <table class="table table-striped table-bordered cvTblsEDT" id="educBkgrdTable" cellspacing="0" width="100%" style="width:100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>&nbsp;</th>		
                                                                <th>Course Name</th>
                                                                <th>School/Institution</th>
                                                                <th>School Location</th>
                                                                <th>Start Date</th>
                                                                <th>End Date</th>
                                                                <th>Certificate Obtained</th>
                                                                <th>Certificate Type</th>
                                                                <th>Date Awarded</th>
                                                                <th>&nbsp;</th>	
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            if ($pkID > 0) {
                                                                $result1 = null;
                                                                if ($sbmtdPersonID <= 0) {
                                                                    $result1 = get_EducBkgrdSelf($pkID);
                                                                } else {
                                                                    $result1 = get_EducBkgrd($pkID);
                                                                }
                                                                while ($row1 = loc_db_fetch_array($result1)) {
                                                                    $cntr += 1;
                                                                    ?>
                                                                    <tr id="educBkgrdRow_<?php echo $cntr; ?>">
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" onclick="getEducBkgrdForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'educBkgrdForm', 'educBkgrdRow_<?php echo $cntr; ?>', 'Add/Edit Educational Background', 20, 'EDIT', <?php echo $row1[0]; ?>, <?php echo $pkID; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                                                <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                                <img src="../cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                            <input type="hidden" value="<?php echo $row1[0]; ?>" id="educBkgrdRow<?php echo $cntr; ?>_PKeyID"/> 
                                                                        </td>
                                                                        <td class="lovtd"><?php echo $row1[1]; ?></td>
                                                                        <td class="lovtd"><?php echo $row1[2]; ?></td>
                                                                        <td class="lovtd"><?php echo $row1[3]; ?></td>
                                                                        <td class="lovtd"><?php echo $row1[4]; ?></td>
                                                                        <td class="lovtd"><?php echo $row1[5]; ?></td>
                                                                        <td class="lovtd"><?php echo $row1[6]; ?></td>
                                                                        <td class="lovtd"><?php echo $row1[7]; ?></td>
                                                                        <td class="lovtd"><?php echo $row1[8]; ?></td>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" onclick="delEducID('educBkgrdRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Educ. Background" style="padding:2px !important;" style="padding:2px !important;">
                                                                                <img src="../cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div> 
                                            </fieldset>
                                        </div>
                                        <div class="col-md-12"> 
                                            <fieldset class="basic_person_fs4"><legend class="basic_person_lg">WORKING EXPERIENCE</legend> 
                                                <div  class="col-md-12">                                                     
                                                    <?php if ($rqStatus == "Requires Approval" || $rqStatus == "Withdrawn" || $rqStatus == "Rejected" || $rqStatus == "Approved") { ?>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getWorkBkgrdForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'workBkgrdForm', '', 'Add/Edit Work Experience', 21, 'ADD', -1, <?php echo $pkID; ?>);">
                                                            <img src="../cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Add Work Experience
                                                        </button>
                                                    <?php } ?>
                                                    <table class="table table-striped table-bordered cvTblsEDT"  id="workBkgrdTable" cellspacing="0" width="100%" style="width:100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>&nbsp;</th>		
                                                                <th>Job Name/Title</th>
                                                                <th>Institution Name</th>
                                                                <th>Job Location</th>
                                                                <th>Start Date</th>
                                                                <th>End Date</th>
                                                                <th>Job Description</th>
                                                                <th>Feats/Achievements</th>
                                                                <th>&nbsp;</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            if ($pkID > 0) {
                                                                $result1 = null;
                                                                if ($sbmtdPersonID <= 0) {
                                                                    $result1 = get_WrkBkgrdSelf($pkID);
                                                                } else {
                                                                    $result1 = get_WrkBkgrd($pkID);
                                                                }
                                                                while ($row1 = loc_db_fetch_array($result1)) {
                                                                    $cntr += 1;
                                                                    ?>
                                                                    <tr id="workBkgrdRow_<?php echo $cntr; ?>">
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" onclick="getWorkBkgrdForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'workBkgrdForm', 'workBkgrdRow_<?php echo $cntr; ?>', 'Add/Edit Work Experience', 21, 'EDIT', <?php echo $row1[0]; ?>, <?php echo $pkID; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                                                <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                                <img src="../cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                            <input type="hidden" value="<?php echo $row1[0]; ?>" id="workBkgrdRow<?php echo $cntr; ?>_PKeyID"/> 
                                                                        </td>
                                                                        <td class="lovtd"><?php echo $row1[1]; ?></td>
                                                                        <td class="lovtd"><?php echo $row1[2]; ?></td>
                                                                        <td class="lovtd"><?php echo $row1[3]; ?></td>
                                                                        <td class="lovtd"><?php echo $row1[4]; ?></td>
                                                                        <td class="lovtd"><?php echo $row1[5]; ?></td>
                                                                        <td class="lovtd"><?php echo $row1[6]; ?></td>
                                                                        <td class="lovtd"><?php echo $row1[7]; ?></td>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" onclick="delWorkID('workBkgrdRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Work Background" style="padding:2px !important;" style="padding:2px !important;">
                                                                                <img src="../cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div> 
                                            </fieldset>
                                        </div>
                                        <div class="col-md-12"> 
                                            <fieldset class="basic_person_fs4"><legend class="basic_person_lg">SKILLS/NATURE</legend> 
                                                <div  class="col-md-12">                                                       
                                                    <?php if ($rqStatus == "Requires Approval" || $rqStatus == "Withdrawn" || $rqStatus == "Rejected" || $rqStatus == "Approved") { ?>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getSkillsForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'skillsForm', '', 'Add/Edit Skills/Nature', 22, 'ADD', -1, <?php echo $pkID; ?>);">
                                                            <img src="../cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Add Skills/Nature
                                                        </button>
                                                    <?php } ?>
                                                    <table class="table table-striped table-bordered cvTblsEDT" id="skillsTable" cellspacing="0" width="100%" style="width:100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>&nbsp;</th>		
                                                                <th>Languages</th>
                                                                <th>Hobbies</th>
                                                                <th>Interests</th>
                                                                <th>Conduct</th>
                                                                <th>Attitude</th>
                                                                <th>From Date</th>
                                                                <th>To Date</th>
                                                                <th>&nbsp;</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            if ($pkID > 0) {
                                                                $result1 = null;
                                                                if ($sbmtdPersonID <= 0) {
                                                                    $result1 = get_SkillNatureSelf($pkID);
                                                                } else {
                                                                    $result1 = get_SkillNature($pkID);
                                                                }
                                                                while ($row1 = loc_db_fetch_array($result1)) {
                                                                    $cntr += 1;
                                                                    ?>
                                                                    <tr id="skillsTblRow_<?php echo $cntr; ?>">
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" onclick="getSkillsForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'skillsForm', 'skillsTblRow_<?php echo $cntr; ?>', 'Add/Edit Skills/Nature', 22, 'EDIT', <?php echo $row1[0]; ?>, <?php echo $pkID; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                                                <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                                <img src="../cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                            <input type="hidden" value="<?php echo $row1[0]; ?>" id="skillsTblRow<?php echo $cntr; ?>_PKeyID"/> 
                                                                        </td>
                                                                        <td class="lovtd"><?php echo $row1[1]; ?></td>
                                                                        <td class="lovtd"><?php echo $row1[2]; ?></td>
                                                                        <td class="lovtd"><?php echo $row1[3]; ?></td>
                                                                        <td class="lovtd"><?php echo $row1[4]; ?></td>
                                                                        <td class="lovtd"><?php echo $row1[5]; ?></td>
                                                                        <td class="lovtd"><?php echo $row1[6]; ?></td>
                                                                        <td class="lovtd"><?php echo $row1[7]; ?></td>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" onclick="delSkillID('skillsTblRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Skill" style="padding:2px !important;" style="padding:2px !important;">
                                                                                <img src="../cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div> 
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>    
                                <div id="prflOthrInfoEDT" class="tab-pane fadein hideNotice" style="border:none !important;">
                                    <?php
                                    $total = 0;
                                    if ($sbmtdPersonID <= 0) {
                                        $total = get_Total_AttachmentsSelf($srchFor, $pkID);
                                    } else {
                                        $total = get_Total_Attachments($srchFor, $pkID);
                                    }
                                    if ($pageNo > ceil($total / $lmtSze)) {
                                        $pageNo = 1;
                                    } else if ($pageNo < 1) {
                                        $pageNo = ceil($total / $lmtSze);
                                    }

                                    $curIdx = $pageNo - 1;
                                    $attchSQL = "";
                                    $result2 = null;
                                    if ($sbmtdPersonID <= 0) {
                                        $result2 = get_AttachmentsSelf($srchFor, $curIdx, $lmtSze, $pkID, $attchSQL);
                                    } else {
                                        $result2 = get_Attachments($srchFor, $curIdx, $lmtSze, $pkID, $attchSQL);
                                    }
                                    $colClassType1 = "col-lg-2";
                                    $colClassType2 = "col-lg-3";
                                    $colClassType3 = "col-lg-4";
                                    $vwtyp = 5;
                                    ?>                                         
                                    <hr style="margin:1px 0px 1px 0px !important;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div  id="attchdDocsList">
                                                <fieldset class="" style="padding:10px 0px 5px 0px !important;">
                                                    <div class="row">
                                                        <?php
                                                        $nwRowHtml = urlencode("<tr id=\"attchdDocsRow__WWW123WWW\">"
                                                                . "<td class=\"lovtd\"><span>New</span></td>"
                                                                . "<td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                <div class=\"input-group\" style=\"width:100% !important;\">
                                                  <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"attchdDocsRow_WWW123WWW_DocCtgryNm\" value=\"\" readonly=\"true\">
                                                  <input class=\"form-control\" aria-label=\"...\" id=\"attchdDocsRow_WWW123WWW_DocFile\" type=\"file\" style=\"visibility:hidden;height:5px !important;display:none;\" />                                                
                                                  <div class=\"input-group-append handCursor\"  onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Attachment Document Categories', '', '', '', 'radio', true, '', 'attchdDocsRow_WWW123WWW_DocCtgryNm', 'attchdDocsRow_WWW123WWW_DocCtgryNm', 'clear', 0, '');\">
                                                      <span class=\"input-group-text rhoclickable\"><i class=\"fas fa-th-list\"></i></span>
                                                  </div>
                                                </div>
                                              </div>
                                              <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attchdDocsRow_WWW123WWW_AttchdDocsID\" value=\"-1\" style=\"\">                                               
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"uploadFileToPrsnDocs('attchdDocsRow_WWW123WWW_DocFile','attchdDocsRow_WWW123WWW_AttchdDocsID','attchdDocsRow_WWW123WWW_DocCtgryNm'," . $pkID . ",'attchdDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Download Document\">
                                                    <img src=\"../cmn_images/openfileicon.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\"> Upload
                                                </button>
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAttchdDoc('attchdDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Document\">
                                                    <img src=\"../cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                </button>
                                          </td>
                                        </tr>");
                                                        ?>
                                                        <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 1px !important;"> 
                                                            <div class="col-md-12">
                                                                <?php if ($rqStatus == "Requires Approval" || $rqStatus == "Withdrawn" || $rqStatus == "Rejected" || $rqStatus == "Approved") { ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('attchdDocsTable', 0, '<?php echo $nwRowHtml; ?>');" style="width:100% !important;">
                                                                        <img src="../cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        New Document Line
                                                                    </button>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                        <div class="<?php echo $colClassType2; ?>" style="display:none;">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" placeholder="Search For" id="attchdDocsSrchFor" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncAttchdDocs(event, '', '#attchdDocsList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                                                <input id="attchdDocsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                                                <div class="input-group-append handCursor" onclick="getAttchdDocs('clear', '#attchdDocsList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                                                    <span class="input-group-text rhoclickable"><i class="fas fa-times"></i></span>
                                                                </div>
                                                                <div class="input-group-append handCursor" onclick="getAttchdDocs('', '#attchdDocsList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                                                    <span class="input-group-text rhoclickable"><i class="fas fa-search"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="<?php echo $colClassType2; ?>" style="display:none;">
                                                            <div class="input-group">                                                                                
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-filter"></i></span>
                                                                </div>
                                                                <select data-placeholder="Select..." class="form-control chosen-select" id="attchdDocsDsplySze" style="min-width:70px !important;">                            
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
                                                        <div class="<?php echo $colClassType1; ?>" style="display:none;">
                                                            <nav aria-label="Page navigation">
                                                                <ul class="pagination" style="margin: 0px !important;">
                                                                    <li>
                                                                        <a class="rhopagination" href="javascript:getAttchdDocs('previous', '#attchdDocsList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                                                            <span aria-hidden="true">&laquo;</span>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="rhopagination" href="javascript:getAttchdDocs('next', '#attchdDocsList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                                                            <span aria-hidden="true">&raquo;</span>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </nav>
                                                        </div>
                                                    </div>
                                                    <div class="row"> 
                                                        <div class="col-md-12">
                                                            <table class="table table-striped table-bordered" id="attchdDocsTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
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
                                                                        $doc_src_encrpt = "";
                                                                        if ($sbmtdPersonID <= 0) {
                                                                            $doc_src = $ftp_base_db_fldr . "/PrsnDocs/Request/" . $row2[3];
                                                                            $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                                                                            if (file_exists($doc_src)) {
                                                                                //file exists!
                                                                            } else {
                                                                                $doc_src1 = $ftp_base_db_fldr . "/PrsnDocs/" . $row2[3];
                                                                                if (file_exists($doc_src1)) {
                                                                                    $temp = explode(".", $row2[3]);
                                                                                    $extension = end($temp);
                                                                                    $doc_src = $ftp_base_db_fldr . "/PrsnDocs/Request/" . $row2[0] . "." . $extension;
                                                                                    copy($doc_src1, $doc_src);
                                                                                    updatePrsnDocFlNmSelf($row2[0], $row2[0] . "." . $extension);
                                                                                    $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                                                                                    if (file_exists($doc_src)) {
                                                                                        //file exists!
                                                                                    } else {
                                                                                        //file does not exist.
                                                                                        $doc_src_encrpt = "None";
                                                                                    }
                                                                                } else {
                                                                                    //file does not exist.
                                                                                    $doc_src_encrpt = "None";
                                                                                }
                                                                            }
                                                                        } else {
                                                                            $doc_src = $ftp_base_db_fldr . "/PrsnDocs/" . $row2[3];
                                                                            $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                                                                            if (file_exists($doc_src)) {
                                                                                //file exists!
                                                                            } else {
                                                                                //file does not exist.
                                                                                $doc_src_encrpt = "None";
                                                                            }
                                                                        }
                                                                        ?>
                                                                        <tr id="attchdDocsRow_<?php echo $cntr; ?>">                                    
                                                                            <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                            <td class="lovtd">                                                                   
                                                                                <span><?php echo $row2[2]; ?></span>
                                                                                <input type="hidden" class="form-control" aria-label="..." id="attchdDocsRow<?php echo $cntr; ?>_AttchdDocsID" value="<?php echo $row2[0]; ?>" style="width:100% !important;">                                              
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
                                                                                        <img src="../cmn_images/dwldicon.png" style="height:15px; width:auto; position: relative; vertical-align: middle;"> Download
                                                                                    </button>
                                                                                <?php } ?>
                                                                            </td>
                                                                            <td class="lovtd">
                                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttchdDoc('attchdDocsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document">
                                                                                    <img src="../cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
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
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    /* Other Information */
                                    $cntr = 0;
                                    $table_id = getMdlGrpID("Person Data", $mdlNm);
                                    $ext_inf_tbl_name = "prs.prsn_all_other_info_table";
                                    $ext_inf_seq_name = "prs.prsn_all_other_info_table_dflt_row_id_seq";
                                    $row_pk_id = $pkID;
                                    ?>
                                    <hr style="border-top:1px dashed #ddd !important;">
                                    <div class="row" style="margin-top:30px !important;">
                                        <div  class="col-md-12">
                                            <fieldset class="" style="padding:10px 0px 5px cccc//px !important;">
                                                <legend class="basic_person_lg">Other Information</legend>
                                                <table id="otherInfoTblsEDT" class="table table-striped table-bordered otherInfoTblsEDT" cellspacing="0" width="100%" style="width:100%;">
                                                    <thead>
                                                        <tr>
                                                            <!--<th>No.</th>-->
                                                            <th>Category</th>
                                                            <th>Label</th>
                                                            <th>Value</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if ($pkID > 0) {
                                                            $brghtsqlStr = "";
                                                            $result1 = getAllwdExtInfosNVals("%", "Extra Info Label", 0, 1000000000, $brghtsqlStr, $table_id, $row_pk_id, $ext_inf_tbl_name, $orgID);
                                                            while ($row1 = loc_db_fetch_array($result1)) {
                                                                $cntr += 1;
                                                                ?>
                                                                <tr>
                                                                    <!--<td><?php echo $cntr; ?></td>-->
                                                                    <td><?php echo $row1[0]; ?></td>
                                                                    <td><?php echo $row1[1]; ?></td>
                                                                    <td><input class="form-control" id="otherInfoTblRow_<?php echo $cntr; ?>" type = "text" placeholder="" value="<?php echo $row1[2]; ?>"/></td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="actions clearfix row">
                                <div class="col-sm-12 actionsRight">
                                    <input type="hidden" id="rhoPreviousTab" value="none">
                                    <input type="hidden" id="rhoNextTab" value="#prflAddPrsnDataEDT">
                                    <input type="hidden" id="rhoAllwdTabs" value="<?php echo $rhoAllwdTabs; ?>">
                                    <ul role="menu" aria-label="Pagination">
                                        <!--class="disabled" -->
                                        <li class="" id="rhoBackBtn">
                                            <a class="btn rho-primary" href="javascript:getNextPrflEDTTab('previous');" role="menuitem"><i class="fa fa-chevron-left" aria-hidden="true"></i> Back</a>
                                        </li>
                                        <li class="hideNotice" id="rhoPreviousBtn">
                                            <a class="btn rho-primary" href="javascript:getNextPrflEDTTab('previous');" role="menuitem">Previous</a>
                                        </li>
                                        <li class="" id="rhoNextBtn">
                                            <a class="btn rho-primary"href="javascript:getNextPrflEDTTab('next');" role="menuitem">Next</a>
                                        </li>
                                        <?php if ($rqStatus == "Requires Approval" || $rqStatus == "Withdrawn" || $rqStatus == "Rejected" || $rqStatus == "Approved") { ?>
                                            <li  class="hideNotice" id="rhoSubmitBtn">
                                                <a class="btn rho-primary" href="javascript:saveBasicPrsnData(<?php echo $actType; ?>, 1);" role="menuitem">Submit for Approval</a>
                                            </li>
                                        <?php } else { ?>
                                            <li  class="hideNotice" id="rhoSubmitBtn">
                                                <a class="btn rho-primary" href="javascript:wthdrwRqst();" role="menuitem">Withdraw from Reviewers</a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </form>  
                        <?php
                    }
                }
                ?> 
            </div>
        </section>
        <?php
    } else if ($vwtyp == "11") {
        /* Add National ID Form */
        $ntnlIDpKey = isset($_POST['ntnlIDpKey']) ? cleanInputData($_POST['ntnlIDpKey']) : -1;
        $ntnlIDPersonID = isset($_POST['ntnlIDPersonID']) ? cleanInputData($_POST['ntnlIDPersonID']) : -1;
        $tRowElmntNm = isset($_POST['tRowElmntNm']) ? cleanInputData($_POST['tRowElmntNm']) : "";
        ?>
        <form class="form-horizontal" id="ntnlIDCardsForm" style="padding:5px 20px 5px 20px;">
            <div class="row">
                <div class="col-md-12"> 
                    <div class="form-group form-group-sm">
                        <label for="ntnlIDCardsCountry" class="control-label col-md-4">Country:</label>
                        <div class="col-md-8">
                            <input class="form-control" size="16" type="hidden" id="ntnlIDpKey" value="<?php echo $ntnlIDpKey; ?>" readonly="">
                            <select class="form-control rqrdFld" id="ntnlIDCardsCountry">
                                <option value="" selected disabled>Please Select...</option>
                                <?php
                                $brghtStr = "";
                                $isDynmyc = FALSE;
                                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Countries"), $isDynmyc, -1, "", "");
                                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                    $selectedTxt = "";
                                    ?>
                                    <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group form-group-sm">
                        <label for="ntnlIDCardsIDTyp" class="control-label col-md-4">ID Type:</label>
                        <div class="col-md-8">
                            <select class="form-control selectpicker rqrdFld" id="ntnlIDCardsIDTyp">  
                                <option value="" selected disabled>Please Select...</option>
                                <?php
                                $brghtStr = "";
                                $isDynmyc = FALSE;
                                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("National ID Types"), $isDynmyc, -1, "", "");
                                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                    $selectedTxt = "";
                                    ?>
                                    <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="ntnlIDCardsIDNo" class="control-label col-md-4">ID No:</label>
                        <div class="col-md-8">
                            <input class="form-control rqrdFld" id="ntnlIDCardsIDNo" type = "text" placeholder="ID No." value=""/>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="ntnlIDCardsDateIssd" class="control-label col-md-4">Date Issued:</label>
                        <div class="col-md-8">
                            <div class="input-group date rho-DatePicker"  id="ntnlIDCardsDateIssdDP" data-target-input="nearest">
                                <input class="form-control datetimepicker-input" size="16" type="text" id="ntnlIDCardsDateIssd" value="" data-target="#ntnlIDCardsDateIssdDP" placeholder="DD-MMM-YYYY">
                                <div class="input-group-append handCursor" onclick="$('#ntnlIDCardsDateIssd').val('');">
                                    <span class="input-group-text rhoclickable"><i class="fa fa-times"></i></span>
                                </div>
                                <div class="input-group-append handCursor" data-target="#ntnlIDCardsDateIssdDP" data-toggle="datetimepicker">
                                    <span class="input-group-text rhoclickable"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="ntnlIDCardsExpDate" class="control-label col-md-4">Expiry Date:</label>
                        <div class="col-md-8">
                            <div class="input-group date rho-DatePicker"  id="ntnlIDCardsExpDateDP" data-target-input="nearest">
                                <input class="form-control datetimepicker-input" size="16" type="text" id="ntnlIDCardsExpDate" value="" data-target="#ntnlIDCardsExpDateDP" placeholder="DD-MMM-YYYY">
                                <div class="input-group-append handCursor" onclick="$('#ntnlIDCardsExpDate').val('');">
                                    <span class="input-group-text rhoclickable"><i class="fa fa-times"></i></span>
                                </div>
                                <div class="input-group-append handCursor" data-target="#ntnlIDCardsExpDateDP" data-toggle="datetimepicker">
                                    <span class="input-group-text rhoclickable"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="ntnlIDCardsOtherInfo" class="control-label col-md-4">Other Information:</label>
                        <div class="col-md-8">
                            <textarea class="form-control" id="ntnlIDCardsOtherInfo" cols="2" placeholder="Other Information" rows="2"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="float:right;justify-content: flex-end;display:flex;padding:0px 15px;"> 
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-right:1px;">Cancel</button>
                    <button type="button" class="btn rho-primary" onclick="saveNtnlIDForm('myFormsModalx', '<?php echo $tRowElmntNm; ?>', <?php echo $ntnlIDpKey; ?>, <?php echo $ntnlIDPersonID; ?>);">Save Changes</button>
                </div>
            </div>
        </form>
        <?php
    } else if ($vwtyp == "12") {
        /* Add Extra Data Form */
        $addtnlPrsPkey = isset($_POST['addtnlPrsPkey']) ? cleanInputData($_POST['addtnlPrsPkey']) : -1;
        $extDtColNum = isset($_POST['extDtColNum']) ? cleanInputData($_POST['extDtColNum']) : -1;
        $pipeSprtdFieldIDs = isset($_POST['pipeSprtdFieldIDs']) ? cleanInputData($_POST['pipeSprtdFieldIDs']) : "";
        $tableElmntID = isset($_POST['tableElmntID']) ? cleanInputData($_POST['tableElmntID']) : "";
        $tRowElementID = isset($_POST['tRowElementID']) ? cleanInputData($_POST['tRowElementID']) : "";
        $addOrEdit = isset($_POST['addOrEdit']) ? cleanInputData($_POST['addOrEdit']) : "";
        $result1 = get_PrsExtrDataGrpCols1($extDtColNum, $orgID);
        ?>
        <form class="form-horizontal" id="addtnlPrsnTblrDataForm" style="padding:5px 20px 5px 20px;">
            <div class="row">  
                <div class="col-md-12"> 
                    <?php
                    while ($row1 = loc_db_fetch_array($result1)) {
                        $fieldHdngs = $row1[11];
                        $arry1 = explode(",", $fieldHdngs);
                        $cntr = count($arry1);
                        for ($i = 0; $i < $row1[9]; $i++) {
                            if ($i <= $cntr - 1) {
                                ?>
                                <div class="form-group form-group-sm">
                                    <label for="prsExtrTblrDtCol_<?php echo $i; ?>" class="control-label col-md-4"><?php echo $arry1[$i]; ?>:</label>
                                    <div class="col-md-8">
                                        <input class="form-control" id="prsExtrTblrDtCol_<?php echo $i; ?>" type = "text" placeholder="" value=""/>
                                    </div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="form-group form-group-sm">
                                    <label for="prsExtrTblrDtCol_<?php echo $i; ?>" class="control-label col-md-4">&nbsp;:</label>
                                    <div class="col-md-8">
                                        <input class="form-control" id="prsExtrTblrDtCol_<?php echo $i; ?>" type = "text" placeholder="" value=""/>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="float:right;justify-content: flex-end;display:flex;padding:0px 15px;"> 
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-right:1px;">Cancel</button>
                    <button type="button" class="btn rho-primary" onclick="saveAddtnlDataForm('myFormsModalxBody', '<?php echo $addtnlPrsPkey; ?>', '<?php echo $pipeSprtdFieldIDs; ?>',<?php echo $extDtColNum; ?>, '<?php echo $tableElmntID; ?>', '<?php echo $tRowElementID; ?>', '<?php echo $addOrEdit; ?>');">Save Changes</button>
                </div>
            </div>
        </form>
        <?php
    } else if ($vwtyp == "13") {
        /* Add Divisions/Groups Form */
        $pDivGrpPkeyID = isset($_POST['pDivGrpPkeyID']) ? cleanInputData($_POST['pDivGrpPkeyID']) : -1;
        $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? cleanInputData($_POST['sbmtdPersonID']) : -1;
        $tRowElmntNm = isset($_POST['tRowElmntNm']) ? cleanInputData($_POST['tRowElmntNm']) : "";
        ?>
        <form class="form-horizontal" id="pDivGrpForm" style="padding:5px 20px 5px 20px;">
            <div class="row">
                <div class="col-md-12"> 
                    <div class="form-group form-group-sm">
                        <label for="pDivGrpName" class="control-label col-md-4">Division/Group Name:</label>
                        <div  class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control rqrdFld" aria-label="..." id="pDivGrpName" value="">
                                <input type="hidden" id="pDivGrpPKeyID" value="-1">
                                <input type="hidden" id="pDivGrpDivID" value="-1">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Divisions/Groups', 'allOtherInputOrgID', '', '', 'radio', true, '', 'pDivGrpDivID', 'pDivGrpName', 'clear', 1, '');">
                                    <span class="glyphicon glyphicon-th-list"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="pDivGrpType" class="control-label col-md-4">Division/Group Type:</label>
                        <div  class="col-md-8">
                            <input type="text" class="form-control" aria-label="..." id="pDivGrpType" value="" readonly="true">
                        </div>
                    </div>              
                    <div class="form-group form-group-sm">
                        <label for="pDivGrpStartDate" class="control-label col-md-4">Start Date:</label>
                        <div class="col-md-8">
                            <div class="input-group date rho-DatePicker"  id="pDivGrpStartDateDP" data-target-input="nearest">
                                <input class="form-control rqrdFld datetimepicker-input" size="16" type="text" id="pDivGrpStartDate" name="pDivGrpStartDate" value="" data-target="#pDivGrpStartDateDP" placeholder="DD-MMM-YYYY">
                                <div class="input-group-append handCursor" onclick="$('#pDivGrpStartDate').val('');">
                                    <span class="input-group-text rhoclickable"><i class="fa fa-times"></i></span>
                                </div>
                                <div class="input-group-append handCursor" data-target="#pDivGrpStartDateDP" data-toggle="datetimepicker">
                                    <span class="input-group-text rhoclickable"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="pDivGrpEndDate" class="control-label col-md-4">End Date:</label>
                        <div class="col-md-8">
                            <div class="input-group date rho-DatePicker"  id="pDivGrpEndDateDP" data-target-input="nearest">
                                <input class="form-control datetimepicker-input" size="16" type="text" id="pDivGrpEndDate" name="pDivGrpEndDate" value="" data-target="#pDivGrpEndDateDP" placeholder="DD-MMM-YYYY">
                                <div class="input-group-append handCursor" onclick="$('#pDivGrpEndDate').val('');">
                                    <span class="input-group-text rhoclickable"><i class="fa fa-times"></i></span>
                                </div>
                                <div class="input-group-append handCursor" data-target="#pDivGrpEndDateDP" data-toggle="datetimepicker">
                                    <span class="input-group-text rhoclickable"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>  
                </div>  
            </div>
            <div class="row">                
                <div class="col-md-12" style="float:right;justify-content: flex-end;display:flex;padding:0px 15px;"> 
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="savePDivGrpForm('myFormsModalx', '<?php echo $pDivGrpPkeyID; ?>',<?php echo $sbmtdPersonID; ?>, '<?php echo $tRowElmntNm; ?>');">Save Changes</button>
                </div>
            </div>
        </form>
        <?php
    } else if ($vwtyp == "14") {
        /* Add Sites/Locations Form */
        $pSiteLocPkeyID = isset($_POST['pSiteLocPkeyID']) ? cleanInputData($_POST['pSiteLocPkeyID']) : -1;
        $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? cleanInputData($_POST['sbmtdPersonID']) : -1;
        $tRowElmntNm = isset($_POST['tRowElmntNm']) ? cleanInputData($_POST['tRowElmntNm']) : "";
        ?>
        <form class="form-horizontal" id="pSiteLocForm" style="padding:5px 20px 5px 20px;">
            <div class="row">
                <div class="form-group form-group-sm">
                    <label for="pSiteLocName" class="control-label col-md-4">Site/Location Name:</label>
                    <div  class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control rqrdFld" aria-label="..." id="pSiteLocName" value="">
                            <input type="hidden" id="pSiteLocPKeyID" value="-1">
                            <input type="hidden" id="pSiteLocID" value="-1">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sites/Locations', 'allOtherInputOrgID', '', '', 'radio', true, '', 'pSiteLocID', 'pSiteLocName', 'clear', 1, '');">
                                <span class="glyphicon glyphicon-th-list"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="pSiteLocType" class="control-label col-md-4">Site/Location Type:</label>
                    <div  class="col-md-8">
                        <input type="text" class="form-control" aria-label="..." id="pSiteLocType" value="" readonly="true">
                    </div>
                </div>              
                <div class="form-group form-group-sm">
                    <label for="pSiteLocStartDate" class="control-label col-md-4">Start Date:</label>
                    <div class="col-md-8">
                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control rqrdFld" size="16" type="text" id="pSiteLocStartDate" value="" readonly="">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="pSiteLocEndDate" class="control-label col-md-4">End Date:</label>
                    <div class="col-md-8">
                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" id="pSiteLocEndDate" value="" readonly="">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>  
            </div>
            <div class="row" style="float:right;padding-right: 1px;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="savePSiteLocForm('myFormsModalx', '<?php echo $pSiteLocPkeyID; ?>',<?php echo $sbmtdPersonID; ?>, '<?php echo $tRowElmntNm; ?>');">Save Changes</button>
            </div>
        </form>
        <?php
    } else if ($vwtyp == "15") {
        /* Add Grades Form */
        $pGradePkeyID = isset($_POST['pGradePkeyID']) ? cleanInputData($_POST['pGradePkeyID']) : -1;
        $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? cleanInputData($_POST['sbmtdPersonID']) : -1;
        $tRowElmntNm = isset($_POST['tRowElmntNm']) ? cleanInputData($_POST['tRowElmntNm']) : "";
        ?>
        <form class="form-horizontal" id="pGradeForm" style="padding:5px 20px 5px 20px;">
            <div class="row">
                <div class="form-group form-group-sm">
                    <label for="pGradeName" class="control-label col-md-4">Grade Name:</label>
                    <div  class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control rqrdFld" aria-label="..." id="pGradeName" value="">
                            <input type="hidden" id="pGradePKeyID" value="-1">
                            <input type="hidden" id="pGradeID" value="-1">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Grades', 'allOtherInputOrgID', '', '', 'radio', true, '', 'pGradeID', 'pGradeName', 'clear', 1, '');">
                                <span class="glyphicon glyphicon-th-list"></span>
                            </label>
                        </div>
                    </div>
                </div>              
                <div class="form-group form-group-sm">
                    <label for="pGradeStartDate" class="control-label col-md-4">Start Date:</label>
                    <div class="col-md-8">
                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control rqrdFld" size="16" type="text" id="pGradeStartDate" value="" readonly="">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="pGradeEndDate" class="control-label col-md-4">End Date:</label>
                    <div class="col-md-8">
                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" id="pGradeEndDate" value="" readonly="">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>  
            </div>
            <div class="row" style="float:right;padding-right: 1px;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="savePGradeForm('myFormsModalx', '<?php echo $pGradePkeyID; ?>',<?php echo $sbmtdPersonID; ?>, '<?php echo $tRowElmntNm; ?>');">Save Changes</button>
            </div>
        </form>
        <?php
    } else if ($vwtyp == "16") {
        /* Add Supervisors Form */
        $pSuprvsrPkeyID = isset($_POST['pSuprvsrPkeyID']) ? cleanInputData($_POST['pSuprvsrPkeyID']) : -1;
        $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? cleanInputData($_POST['sbmtdPersonID']) : -1;
        $tRowElmntNm = isset($_POST['tRowElmntNm']) ? cleanInputData($_POST['tRowElmntNm']) : "";
        //var_dump($_POST);
        ?>
        <form class="form-horizontal" id="pSuprvsrForm" style="padding:5px 20px 5px 20px;">
            <div class="row">
                <div class="form-group form-group-sm">
                    <label for="pSuprvsrName" class="control-label col-md-4">Supervisor Name:</label>
                    <div  class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control rqrdFld" aria-label="..." id="pSuprvsrName" value="">
                            <input type="hidden" id="pSuprvsrPKeyID" value="-1">
                            <input type="hidden" id="pSuprvsrID" value="-1">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Person IDs', 'allOtherInputOrgID', '', '', 'radio', true, '', 'pSuprvsrID', 'pSuprvsrName', 'clear', 1, '');">
                                <span class="glyphicon glyphicon-th-list"></span>
                            </label>
                        </div>
                    </div>
                </div>             
                <div class="form-group form-group-sm">
                    <label for="pSuprvsrStartDate" class="control-label col-md-4">Start Date:</label>
                    <div class="col-md-8">
                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control rqrdFld" size="16" type="text" id="pSuprvsrStartDate" value="" readonly="">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="pSuprvsrEndDate" class="control-label col-md-4">End Date:</label>
                    <div class="col-md-8">
                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" id="pSuprvsrEndDate" value="" readonly="">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>  
            </div>
            <div class="row" style="float:right;padding-right: 1px;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="savePSuprvsrForm('myFormsModalx', '<?php echo $pSuprvsrPkeyID; ?>',<?php echo $sbmtdPersonID; ?>, '<?php echo $tRowElmntNm; ?>');">Save Changes</button>
            </div>
        </form>
        <?php
    } else if ($vwtyp == "17") {
        /* Add Jobs Form */
        $pJobPkeyID = isset($_POST['pJobPkeyID']) ? cleanInputData($_POST['pJobPkeyID']) : -1;
        $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? cleanInputData($_POST['sbmtdPersonID']) : -1;
        $tRowElmntNm = isset($_POST['tRowElmntNm']) ? cleanInputData($_POST['tRowElmntNm']) : "";
        ?>
        <form class="form-horizontal" id="pJobForm" style="padding:5px 20px 5px 20px;">
            <div class="row">
                <div class="form-group form-group-sm">
                    <label for="pJobName" class="control-label col-md-4">Job Name:</label>
                    <div  class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control rqrdFld" aria-label="..." id="pJobName" value="">
                            <input type="hidden" id="pJobPKeyID" value="-1">
                            <input type="hidden" id="pJobID" value="-1">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Jobs', 'allOtherInputOrgID', '', '', 'radio', true, '', 'pJobID', 'pJobName', 'clear', 1, '');">
                                <span class="glyphicon glyphicon-th-list"></span>
                            </label>
                        </div>
                    </div>
                </div>             
                <div class="form-group form-group-sm">
                    <label for="pJobStartDate" class="control-label col-md-4">Start Date:</label>
                    <div class="col-md-8">
                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control rqrdFld" size="16" type="text" id="pJobStartDate" value="" readonly="">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="pJobEndDate" class="control-label col-md-4">End Date:</label>
                    <div class="col-md-8">
                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" id="pJobEndDate" value="" readonly="">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>  
            </div>
            <div class="row" style="float:right;padding-right: 1px;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="savePJobForm('myFormsModalx', '<?php echo $pJobPkeyID; ?>',<?php echo $sbmtdPersonID; ?>, '<?php echo $tRowElmntNm; ?>');">Save Changes</button>
            </div>
        </form>
        <?php
    } else if ($vwtyp == "18") {
        /* Add Positions Form */
        $pPositionPkeyID = isset($_POST['pPositionPkeyID']) ? cleanInputData($_POST['pPositionPkeyID']) : -1;
        $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? cleanInputData($_POST['sbmtdPersonID']) : -1;
        $tRowElmntNm = isset($_POST['tRowElmntNm']) ? cleanInputData($_POST['tRowElmntNm']) : "";
        ?>
        <form class="form-horizontal" id="pPositionForm" style="padding:5px 20px 5px 20px;">
            <div class="row">
                <div class="form-group form-group-sm">
                    <label for="pPositionName" class="control-label col-md-4">Position Name:</label>
                    <div  class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control rqrdFld" aria-label="..." id="pPositionName" value="">
                            <input type="hidden" id="pPositionPKeyID" value="-1">
                            <input type="hidden" id="pPositionID" value="-1">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Positions', 'allOtherInputOrgID', '', '', 'radio', true, '', 'pPositionID', 'pPositionName', 'clear', 1, '');">
                                <span class="glyphicon glyphicon-th-list"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="pPositionDivNm" class="control-label col-md-4">Division/Group:</label>
                    <div  class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control rqrdFld" aria-label="..." id="pPositionDivNm" value="">
                            <input type="hidden" id="pPositionDivID" value="-1">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Divisions/Groups', 'allOtherInputOrgID', '', '', 'radio', true, '', 'pPositionDivID', 'pPositionDivNm', 'clear', 1, '');">
                                <span class="glyphicon glyphicon-th-list"></span>
                            </label>
                        </div>
                    </div>
                </div>             
                <div class="form-group form-group-sm">
                    <label for="pPositionStartDate" class="control-label col-md-4">Start Date:</label>
                    <div class="col-md-8">
                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control rqrdFld" size="16" type="text" id="pPositionStartDate" value="" readonly="">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="pPositionEndDate" class="control-label col-md-4">End Date:</label>
                    <div class="col-md-8">
                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" id="pPositionEndDate" value="" readonly="">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>  
            </div>
            <div class="row" style="float:right;padding-right: 1px;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="savePPositionForm('myFormsModalx', '<?php echo $pPositionPkeyID; ?>',<?php echo $sbmtdPersonID; ?>, '<?php echo $tRowElmntNm; ?>');">Save Changes</button>
            </div>
        </form>
        <?php
    } else if ($vwtyp == "20") {
        /* Add Educational Background Form */
        $educBkgrdPkeyID = isset($_POST['educBkgrdPkeyID']) ? cleanInputData($_POST['educBkgrdPkeyID']) : -1;
        $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? cleanInputData($_POST['sbmtdPersonID']) : -1;
        $tRowElmntNm = isset($_POST['tRowElmntNm']) ? cleanInputData($_POST['tRowElmntNm']) : "";
        ?>
        <form class="form-horizontal" id="educBkgrdForm" style="padding:5px 20px 5px 20px;">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-sm">
                        <label for="educBkgrdCourseName" class="control-label col-md-4">Course Name:</label>
                        <div  class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control rqrdFld" aria-label="..." id="educBkgrdCourseName" value="">
                                <div class="input-group-append handCursor"  onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'CV Courses', '', '', '', 'radio', true, '', 'educBkgrdCourseName', '', 'clear', 1, '');">
                                    <span class="input-group-text rhoclickable"><i class="fas fa-th-list"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="educBkgrdSchool" class="control-label col-md-4">School / Institution:</label>
                        <div  class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control rqrdFld" aria-label="..." id="educBkgrdSchool" value="">
                                <div class="input-group-append handCursor"  onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Schools/Organisations/Institutions', '', '', '', 'radio', true, '', 'educBkgrdSchool', '', 'clear', 1, '');">
                                    <span class="input-group-text rhoclickable"><i class="fas fa-th-list"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="educBkgrdLoc" class="control-label col-md-4">Location:</label>
                        <div  class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control rqrdFld" aria-label="..." id="educBkgrdLoc" value="">
                                <div class="input-group-append handCursor"  onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Other Locations', '', '', '', 'radio', true, '', 'educBkgrdLoc', '', 'clear', 1, '');">
                                    <span class="input-group-text rhoclickable"><i class="fas fa-th-list"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>                
                    <div class="form-group form-group-sm">
                        <label for="educBkgrdStartDate" class="control-label col-md-4">Start Date:</label>
                        <div class="col-md-8">
                            <?php $rhoDateLabelr = "educBkgrdStartDate"; ?>
                            <div class="input-group date rho-DatePicker"  id="<?php echo $rhoDateLabelr; ?>DP" data-target-input="nearest">
                                <input class="form-control rqrdFld datetimepicker-input" size="16" type="text" id="<?php echo $rhoDateLabelr; ?>" name="<?php echo $rhoDateLabelr; ?>" value="" data-target="#<?php echo $rhoDateLabelr; ?>DP" placeholder="DD-MMM-YYYY">
                                <div class="input-group-append handCursor" onclick="$('#<?php echo $rhoDateLabelr; ?>').val('');">
                                    <span class="input-group-text rhoclickable"><i class="fa fa-times"></i></span>
                                </div>
                                <div class="input-group-append handCursor" data-target="#<?php echo $rhoDateLabelr; ?>DP" data-toggle="datetimepicker">
                                    <span class="input-group-text rhoclickable"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="educBkgrdEndDate" class="control-label col-md-4">End Date:</label>
                        <div class="col-md-8">
                            <?php $rhoDateLabelr = "educBkgrdEndDate"; ?>
                            <div class="input-group date rho-DatePicker"  id="<?php echo $rhoDateLabelr; ?>DP" data-target-input="nearest">
                                <input class="form-control datetimepicker-input" size="16" type="text" id="<?php echo $rhoDateLabelr; ?>" name="<?php echo $rhoDateLabelr; ?>" value="" data-target="#<?php echo $rhoDateLabelr; ?>DP" placeholder="DD-MMM-YYYY">
                                <div class="input-group-append handCursor" onclick="$('#<?php echo $rhoDateLabelr; ?>').val('');">
                                    <span class="input-group-text rhoclickable"><i class="fa fa-times"></i></span>
                                </div>
                                <div class="input-group-append handCursor" data-target="#<?php echo $rhoDateLabelr; ?>DP" data-toggle="datetimepicker">
                                    <span class="input-group-text rhoclickable"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>                
                    <div class="form-group form-group-sm">
                        <label for="educBkgrdCertObtnd" class="control-label col-md-4">Certificate Obtained:</label>
                        <div class="col-md-8">
                            <select class="form-control selectpicker" id="educBkgrdCertObtnd">  
                                <option value="" selected disabled>Please Select...</option>
                                <?php
                                $brghtStr = "";
                                $isDynmyc = FALSE;
                                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Certificate Names"), $isDynmyc, -1, "", "");
                                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                    $selectedTxt = "";
                                    ?>
                                    <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>                
                    <div class="form-group form-group-sm">
                        <label for="educBkgrdCertTyp" class="control-label col-md-4">Certificate Type:</label>
                        <div class="col-md-8">
                            <select class="form-control selectpicker" id="educBkgrdCertTyp">  
                                <option value="" selected disabled>Please Select...</option>
                                <?php
                                $brghtStr = "";
                                $isDynmyc = FALSE;
                                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Qualification Types"), $isDynmyc, -1, "", "");
                                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                    $selectedTxt = "";
                                    ?>
                                    <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="educBkgrdDateAwrded" class="control-label col-md-4">Date Awarded:</label>
                        <div class="col-md-8">
                            <?php $rhoDateLabelr = "educBkgrdDateAwrded"; ?>
                            <div class="input-group date rho-DatePicker"  id="<?php echo $rhoDateLabelr; ?>DP" data-target-input="nearest">
                                <input class="form-control rqrdFld datetimepicker-input" size="16" type="text" id="<?php echo $rhoDateLabelr; ?>" name="<?php echo $rhoDateLabelr; ?>" value="" data-target="#<?php echo $rhoDateLabelr; ?>DP" placeholder="DD-MMM-YYYY">
                                <div class="input-group-append handCursor" onclick="$('#<?php echo $rhoDateLabelr; ?>').val('');">
                                    <span class="input-group-text rhoclickable"><i class="fa fa-times"></i></span>
                                </div>
                                <div class="input-group-append handCursor" data-target="#<?php echo $rhoDateLabelr; ?>DP" data-toggle="datetimepicker">
                                    <span class="input-group-text rhoclickable"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="float:right;justify-content: flex-end;display:flex;padding:0px 15px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-right:1px;">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveEducBkgrdForm('myFormsModalx', '<?php echo $educBkgrdPkeyID; ?>',<?php echo $sbmtdPersonID; ?>, '<?php echo $tRowElmntNm; ?>');">Save Changes</button>
                </div>
            </div>
        </form>
        <?php
    } else if ($vwtyp == "21") {
        /* Add Work Experience Form */
        $workBkgrdPkeyID = isset($_POST['workBkgrdPkeyID']) ? cleanInputData($_POST['workBkgrdPkeyID']) : -1;
        $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? cleanInputData($_POST['sbmtdPersonID']) : -1;
        $tRowElmntNm = isset($_POST['tRowElmntNm']) ? cleanInputData($_POST['tRowElmntNm']) : "";
        ?>
        <form class="form-horizontal" id="workBkgrdForm" style="padding:5px 20px 5px 20px;">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-sm">
                        <label for="workBkgrdJobName" class="control-label col-md-4">Job Name / Title:</label>
                        <div  class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control rqrdFld" aria-label="..." id="workBkgrdJobName" value="">
                                <div class="input-group-append handCursor"  onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Jobs/Professions/Occupations', '', '', '', 'radio', true, '', 'workBkgrdJobName', '', 'clear', 1, '');">
                                    <span class="input-group-text rhoclickable"><i class="fas fa-th-list"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="workBkgrdInstitution" class="control-label col-md-4">Institution:</label>
                        <div  class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control rqrdFld" aria-label="..." id="workBkgrdInstitution" value="">
                                <div class="input-group-append handCursor"  onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Schools/Organisations/Institutions', '', '', '', 'radio', true, '', 'workBkgrdInstitution', '', 'clear', 1, '');">
                                    <span class="input-group-text rhoclickable"><i class="fas fa-th-list"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="workBkgrdLoc" class="control-label col-md-4">Location:</label>
                        <div  class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control rqrdFld" aria-label="..." id="workBkgrdLoc" value="">
                                <div class="input-group-append handCursor"  onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Other Locations', '', '', '', 'radio', true, '', 'workBkgrdLoc', '', 'clear', 1, '');">
                                    <span class="input-group-text rhoclickable"><i class="fas fa-th-list"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>                
                    <div class="form-group form-group-sm">
                        <label for="workBkgrdStartDate" class="control-label col-md-4">Start Date:</label>
                        <div class="col-md-8">

                            <?php $rhoDateLabelr = "workBkgrdStartDate"; ?>
                            <div class="input-group date rho-DatePicker"  id="<?php echo $rhoDateLabelr; ?>DP" data-target-input="nearest">
                                <input class="form-control rqrdFld datetimepicker-input" size="16" type="text" id="<?php echo $rhoDateLabelr; ?>" name="<?php echo $rhoDateLabelr; ?>" value="" data-target="#<?php echo $rhoDateLabelr; ?>DP" placeholder="DD-MMM-YYYY">
                                <div class="input-group-append handCursor" onclick="$('#<?php echo $rhoDateLabelr; ?>').val('');">
                                    <span class="input-group-text rhoclickable"><i class="fa fa-times"></i></span>
                                </div>
                                <div class="input-group-append handCursor" data-target="#<?php echo $rhoDateLabelr; ?>DP" data-toggle="datetimepicker">
                                    <span class="input-group-text rhoclickable"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="workBkgrdEndDate" class="control-label col-md-4">End Date:</label>
                        <div class="col-md-8">
                            <?php $rhoDateLabelr = "workBkgrdEndDate"; ?>
                            <div class="input-group date rho-DatePicker"  id="<?php echo $rhoDateLabelr; ?>DP" data-target-input="nearest">
                                <input class="form-control datetimepicker-input" size="16" type="text" id="<?php echo $rhoDateLabelr; ?>" name="<?php echo $rhoDateLabelr; ?>" value="" data-target="#<?php echo $rhoDateLabelr; ?>DP" placeholder="DD-MMM-YYYY">
                                <div class="input-group-append handCursor" onclick="$('#<?php echo $rhoDateLabelr; ?>').val('');">
                                    <span class="input-group-text rhoclickable"><i class="fa fa-times"></i></span>
                                </div>
                                <div class="input-group-append handCursor" data-target="#<?php echo $rhoDateLabelr; ?>DP" data-toggle="datetimepicker">
                                    <span class="input-group-text rhoclickable"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>                
                    <div class="form-group form-group-sm">
                        <label for="workBkgrdJobDesc" class="control-label col-md-4">Job Description:</label>
                        <div class="col-md-8">
                            <textarea class="form-control" id="workBkgrdJobDesc" cols="2" rows="2" placeholder="Job Description" rows="2"></textarea>
                        </div>
                    </div>                
                    <div class="form-group form-group-sm">
                        <label for="workBkgrdAchvmnts" class="control-label col-md-4">Feats / Achievements:</label>
                        <div class="col-md-8">
                            <textarea class="form-control" id="workBkgrdAchvmnts" cols="2" rows="4" placeholder="Achievements" rows="2"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="float:right;justify-content: flex-end;display:flex;padding:0px 15px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-right:1px;">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveWorkBkgrdForm('myFormsModalx', '<?php echo $workBkgrdPkeyID; ?>',<?php echo $sbmtdPersonID; ?>, '<?php echo $tRowElmntNm; ?>');">Save Changes</button>
                </div>
            </div>
        </form>
        <?php
    } else if ($vwtyp == "22") {
        /* Add Skills/Nature Form */
        $skillsPkeyID = isset($_POST['skillsPkeyID']) ? cleanInputData($_POST['skillsPkeyID']) : -1;
        $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? cleanInputData($_POST['sbmtdPersonID']) : -1;
        $tRowElmntNm = isset($_POST['tRowElmntNm']) ? cleanInputData($_POST['tRowElmntNm']) : "";
        ?>
        <form class="form-horizontal" id="skillsForm" style="padding:5px 20px 5px 20px;">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-sm">
                        <label for="skillsLanguages" class="control-label col-md-4">Languages:</label>
                        <div  class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" aria-label="..." id="skillsLanguages" value="">
                                <div class="input-group-append handCursor"  onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Languages', '', '', '', 'check', true, '', 'skillsLanguages', '', 'clear', 1, '');">
                                    <span class="input-group-text rhoclickable"><i class="fas fa-th-list"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="skillsHobbies" class="control-label col-md-4">Hobbies:</label>
                        <div  class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" aria-label="..." id="skillsHobbies" value="">
                                <div class="input-group-append handCursor"  onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Hobbies', '', '', '', 'check', true, '', 'skillsHobbies', '', 'clear', 1, '');">
                                    <span class="input-group-text rhoclickable"><i class="fas fa-th-list"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="skillsInterests" class="control-label col-md-4">Interests:</label>
                        <div  class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" aria-label="..." id="skillsInterests" value="">
                                <div class="input-group-append handCursor"  onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Interests', '', '', '', 'check', true, '', 'skillsInterests', '', 'clear', 1, '');">
                                    <span class="input-group-text rhoclickable"><i class="fas fa-th-list"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="skillsConduct" class="control-label col-md-4">Conduct:</label>
                        <div  class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" aria-label="..." id="skillsConduct" value="">
                                <div class="input-group-append handCursor"  onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Conduct', '', '', '', 'check', true, '', 'skillsConduct', '', 'clear', 1, '');">
                                    <span class="input-group-text rhoclickable"><i class="fas fa-th-list"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="skillsAttitudes" class="control-label col-md-4">Attitudes:</label>
                        <div  class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" aria-label="..." id="skillsAttitudes" value="">
                                <div class="input-group-append handCursor"  onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Attitudes', '', '', '', 'check', true, '', 'skillsAttitudes', '', 'clear', 1, '');">
                                    <span class="input-group-text rhoclickable"><i class="fas fa-th-list"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>                
                    <div class="form-group form-group-sm">
                        <label for="skillsStartDate" class="control-label col-md-4">Start Date:</label>
                        <div class="col-md-8">
                            <?php $rhoDateLabelr = "skillsStartDate"; ?>
                            <div class="input-group date rho-DatePicker"  id="<?php echo $rhoDateLabelr; ?>DP" data-target-input="nearest">
                                <input class="form-control rqrdFld datetimepicker-input" size="16" type="text" id="<?php echo $rhoDateLabelr; ?>" name="<?php echo $rhoDateLabelr; ?>" value="" data-target="#<?php echo $rhoDateLabelr; ?>DP" placeholder="DD-MMM-YYYY">
                                <div class="input-group-append handCursor" onclick="$('#<?php echo $rhoDateLabelr; ?>').val('');">
                                    <span class="input-group-text rhoclickable"><i class="fa fa-times"></i></span>
                                </div>
                                <div class="input-group-append handCursor" data-target="#<?php echo $rhoDateLabelr; ?>DP" data-toggle="datetimepicker">
                                    <span class="input-group-text rhoclickable"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="skillsEndDate" class="control-label col-md-4">End Date:</label>
                        <div class="col-md-8">
                            <?php $rhoDateLabelr = "skillsEndDate"; ?>
                            <div class="input-group date rho-DatePicker"  id="<?php echo $rhoDateLabelr; ?>DP" data-target-input="nearest">
                                <input class="form-control datetimepicker-input" size="16" type="text" id="<?php echo $rhoDateLabelr; ?>" name="<?php echo $rhoDateLabelr; ?>" value="" data-target="#<?php echo $rhoDateLabelr; ?>DP" placeholder="DD-MMM-YYYY">
                                <div class="input-group-append handCursor" onclick="$('#<?php echo $rhoDateLabelr; ?>').val('');">
                                    <span class="input-group-text rhoclickable"><i class="fa fa-times"></i></span>
                                </div>
                                <div class="input-group-append handCursor" data-target="#<?php echo $rhoDateLabelr; ?>DP" data-toggle="datetimepicker">
                                    <span class="input-group-text rhoclickable"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="float:right;justify-content: flex-end;display:flex;padding:0px 15px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveSkillsForm('myFormsModalx', '<?php echo $skillsPkeyID; ?>',<?php echo $sbmtdPersonID; ?>, '<?php echo $tRowElmntNm; ?>');">Save Changes</button>
                </div>
            </div>
        </form>
        <?php
    }
}
?>
