<?php
$mdlACAorPMS = isset($_POST['mdl']) ? cleanInputData($_POST['mdl']) : "ACA";
$menuItems = array(
    "Summary Reports", "Assessment Sheets", "Task Assignment Setups",
    "Groups / Courses / Subjects", "Position Holders", "Assessment Periods",
    "Assessment Report Types", "Courses/Objectives", "Subjects/Targets", "Grading Systems", "Standard Reports"
);
$menuImages = array(
    "dashboard220.png", "invoice1.png", "settings.png", "resume.png", "supervisor.jpg", "calendar2.png",
    "reports.png", "resume.png", "resume.png", "education.png", "report-icon-png.png"
);

$mdlNm = "Learning/Performance Management";
$ModuleName = $mdlNm;

$dfltPrvldgs = array(
    "View Summary Reports", "View Learning/Performance Management",
    /* 2 */ "View Assessment Sheets", "View Task Assignment Setups", "View Groups/Courses/Subjects",
    /* 5 */ "View Position Holders", "View Assessment Periods", "View Assessment Reports Types",
    /* 8 */ "View Record History", "View SQL",
    /* 10 */ "Add Subjects/Tasks", "Edit Subjects/Tasks", "Delete Subjects/Tasks",
    /* 13 */ "Add Courses/Objectives", "Edit Courses/Objectives", "Delete Courses/Objectives",
    /* 16 */ "Add Assessment Types", "Edit Assessment Types", "Delete Assessment Types",
    /* 19 */ "Add Assessment Periods", "Edit Assessment Periods", "Delete Assessment Periods",
    /* 22 */ "Add Position Holders", "Edit Position Holders", "Delete Position Holders",
    /* 25 */ "Add Groups/Classes", "Edit Groups/Classes", "Delete Groups/Classes",
    /* 28 */ "Add Registrations", "Edit Registrations", "Delete Registrations",
    /* 31 */ "Add Assessment Sheets", "Edit Assessment Sheets", "Delete Assessment Sheets",
    /* 34 */ "Add Summary Reports", "Edit  Summary Reports", "Delete  Summary Reports",
    /* 37 */ "View Only Self-Created Sheets", "View Only Self-Created Summary Reports",
    /* 39 */ "View Appraisal"
);

$prsnid = $_SESSION['PRSN_ID'];
$orgID = $_SESSION['ORG_ID'];
$usrID = $_SESSION['USRID'];
$uName = $_SESSION['UNAME'];
$gnrlTrnsDteDMYHMS = getFrmtdDB_Date_time();
$gnrlTrnsDteYMDHMS = cnvrtDMYTmToYMDTm($gnrlTrnsDteDMYHMS);
$gnrlTrnsDteYMD = substr($gnrlTrnsDteYMDHMS, 0, 10);
$gnrlTrnsDteDMY = substr($gnrlTrnsDteDMYHMS, 0, 11);

$acaPrmSnsRstl = getAcaPgPrmssns($prsnid, $orgID, $usrID);
$fnccurid = $acaPrmSnsRstl[0];
$fnccurnm = getPssblValNm($fnccurid);
$brnchLocID = $acaPrmSnsRstl[1];
$brnchLoc = getGnrlRecNm("org.org_sites_locations", "location_id", "REPLACE(location_code_name || '.' || site_desc, '.' || location_code_name,'')", $brnchLocID);
$acsCntrlGrpID = $acaPrmSnsRstl[2];
/* 
$accbFSRptStoreID = isset($_POST['accbFSRptStoreID']) ? (int) cleanInputData($_POST['accbFSRptStoreID']) : -1;
  $selectedStoreID = (int) $_SESSION['SELECTED_SALES_STOREID'];
  if ($accbFSRptStoreID > 0 && getUserStorePkeyID($accbFSRptStoreID) > 0) {
  $selectedStoreID = $accbFSRptStoreID;
  $_SESSION['SELECTED_SALES_STOREID'] = $selectedStoreID;
  }
  if ($selectedStoreID <= 0) {
  $selectedStoreID = $acaPrmSnsRstl[3];
  $_SESSION['SELECTED_SALES_STOREID'] = $selectedStoreID;
  }
  $acsCntrlGrpNm = getStoreNm($selectedStoreID); 
*/
$canview = ($acaPrmSnsRstl[4] >= 1) ? true : false;
$canViewAcdmcs = ($acaPrmSnsRstl[5] >= 1) ? true : false;
$canViewAssSht = ($acaPrmSnsRstl[6] >= 1) ? true : false;
$canViewAsgnmnt = ($acaPrmSnsRstl[7] >= 1) ? true : false;
$canViewGroups = ($acaPrmSnsRstl[8] >= 1) ? true : false;
$canViewPositns = ($acaPrmSnsRstl[9] >= 1) ? true : false;
$canViewPeriods = ($acaPrmSnsRstl[10] >= 1) ? true : false;
$canViewRptTyps = ($acaPrmSnsRstl[11] >= 1) ? true : false;
$vwOnlySelfShts =  ($acaPrmSnsRstl[12] >= 1) ? true : false;
$vwOnlySelfRpts =  ($acaPrmSnsRstl[13] >= 1) ? true : false;

$canVwRcHstry = ($acaPrmSnsRstl[17] >= 1) ? true : false;
$canViewSQL = ($acaPrmSnsRstl[18] >= 1) ? true : false;
$courseLabelACA = $acaPrmSnsRstl[19];
$sbjctLabelACA = $acaPrmSnsRstl[20];
$groupLabelACA = $acaPrmSnsRstl[21];
$customMdlNm = $acaPrmSnsRstl[22];
$courseLabelPMS = $acaPrmSnsRstl[23];
$sbjctLabelPMS = $acaPrmSnsRstl[24];
$groupLabelPMS = $acaPrmSnsRstl[25];

$courseLabel = ($mdlACAorPMS == "PMS") ? $courseLabelPMS : $courseLabelACA;
$sbjctLabel = ($mdlACAorPMS == "PMS") ? $sbjctLabelPMS : $sbjctLabelACA;
$groupLabel = ($mdlACAorPMS == "PMS") ? $groupLabelPMS : $groupLabelACA;

$courseLOV = "ACA Courses";
$sbjctLOV = "ACA Subjects";
$moduleType = "Course";
$moduleType2 = "Subject";
$moduleType2Wght = "Credit Hours";
$canViewrpts = true;
$courseLabelAddon = "Registration";
$menuItems[0] = "Report Cards";

if ($mdlACAorPMS == "PMS") {
    $courseLOV = "PMS Objectives";
    $sbjctLOV = "PMS Tasks";
    $moduleType = "Objective";
    $moduleType2 = "Task";
    $courseLabelAddon = "Assignment";
    $moduleType2Wght = "Weight";
    $menuItems[0] = "Score Cards";
    if (strpos($customMdlNm, "Academ") !== FALSE) {
        $customMdlNm = "Performance Appraisal System";
    }
    if ($courseLabel == "") {
        $courseLabel = "Objective";
    }

    if ($sbjctLabel == "") {
        $sbjctLabel = "Task";
    }

    if ($groupLabel == "") {
        $groupLabel = "Department";
    }
}

if ($courseLabel == "") {
    $courseLabel = "Programme";
}

if ($sbjctLabel == "") {
    $sbjctLabel = "Subject";
}

if ($groupLabel == "") {
    $groupLabel = "Class";
}

$courseLabels = getStrPlural($courseLabel);
$sbjctLabels = getStrPlural($sbjctLabel);
$groupLabels = getStrPlural($groupLabel);

/*  , 
 * gst.get_pssbl_val_desc(gst.get_pssbl_val(gst.getenbldpssblvalid('Default Course/Objective/Programme Label', gst.get_lov_id('All Other Performance Setups'))),
 * gst.get_pssbl_val_desc(gst.get_pssbl_val(gst.getenbldpssblvalid('Default Subject/Task Label', gst.get_lov_id('All Other Performance Setups')))
  $vPsblValID1 = getEnbldPssblValID("Default Course/Objective/Programme Label", getLovID("All Other Performance Setups"));
  $vPsblVal1 = getPssblValDesc($vPsblValID1);
  $vPsblValID2 = getEnbldPssblValID("Default Subject/Task Label", getLovID("All Other Performance Setups"));
  $vPsblVal2 = getPssblValDesc($vPsblValID2); */

$vwtyp = "0";
$qstr = "";
$dsply = "";
$actyp = "";
$srchFor = "";
$srchIn = "Name";
$PKeyID = -1;
$sortBy = "ID ASC";
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
if (isset($_POST['sortBy'])) {
    $sortBy = cleanInputData($_POST['sortBy']);
}
if (strpos($srchFor, "%") === FALSE) {
    $srchFor = " " . $srchFor . " ";
    $srchFor = str_replace(" ", "%", $srchFor);
}

$cntent = "<div>
                <ul class=\"breadcrumb\" style=\"$breadCrmbBckclr\">
                        <li onclick=\"openATab('#home', 'grp=40&typ=1');\">
                                <i class=\"fa fa-home\" aria-hidden=\"true\"></i>
                                <span style=\"text-decoration:none;\">Home</span>
                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                        </li>
                        <li onclick=\"openATab('#allmodules', 'grp=40&typ=5');\">
                                <span style=\"text-decoration:none;\">All Modules&nbsp;</span><span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                        </li>
                        <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&mdl=$mdlACAorPMS');\">
                                <span style=\"text-decoration:none;\">$customMdlNm Menu</span>
                        </li>";
if ($lgn_num > 0 && $canview === true) {
    if ($pgNo == 0) {
        $cntent .= "</ul>
                       </div>" . "<div style=\"font-family: Tahoma, Arial, sans-serif;font-size: 1.3em;
                    padding:10px 15px 15px 20px;border:1px solid #ccc;\">                    
      <div style=\"padding:5px 30px 5px 10px;margin-bottom:2px;\">
                    <span style=\"font-family: georgia, times;font-size: 12px;font-style:italic;
                    font-weight:normal;\">This module helps you to manage your organization's Learning/Performance Assessment Needs! The module has the ff areas:</span>";
        if ($usrName == "admin") {
            $cntent .= "<button type = \"button\" class=\"btn btn-default btn-sm\" style=\"height:30px;\" onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=0&vtyp=10&mdl=$mdlACAorPMS');\" data-toggle=\"tooltip\" title=\"Reload Module\">
                            <img src=\"cmn_images/refresh.bmp\" style=\"padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;\">
                            Refresh
                        </button>";
        }
        $cntent .= "</div>
      <p>";
        $grpcntr = 0;
        for ($i = 0; $i < count($menuItems); $i++) {
            $customMdlNm = $menuItems[$i];
            $No = $i + 1;
            if ($i == 0 && $canViewAcdmcs === false) {
                continue;
            } else if ($i == 1 && $canViewAssSht === false) {
                continue;
            } else if ($i == 2 && $canViewAsgnmnt === false) {
                continue;
            } else if (($i == 3 || $i == 7 || $i == 8) && $canViewGroups === false) {
                continue;
            } else if ($i == 4 && $canViewPositns === false) {
                continue;
            } else if ($i == 5 && $canViewPeriods === false) {
                continue;
            } else if (($i == 6 || $i == 9) && $canViewRptTyps === false) {
                continue;
            } else if ($i == 10 && $canViewrpts === false) {
                continue;
            }
            /* $menuItems = array("Summary Reports", "Assessment Sheets", "Task Assignment Setups",
              "Groups / Courses / Subjects", "Position Holders", "Assessment Periods",
              "Assessment Report Types", "Courses/Objectives", "Subjects/Targets"); */
            if ($i == 7) {
                $customMdlNm = $courseLabels;
            } else if ($i == 8) {
                $customMdlNm = $sbjctLabels;
            } else if ($i == 2) {
                $customMdlNm = $courseLabel . " " . $courseLabelAddon;
            } else if ($i == 3) {
                $customMdlNm = $groupLabels . " / " . $courseLabels;
            }
            $menuItems[$i] = $customMdlNm;
            if ($grpcntr == 0) {
                $cntent .= "<div class=\"row\">";
            }
            $cntent .= "<div class=\"col-md-3 colmd3special2\">
        <button type=\"button\" class=\"btn btn-default btn-lg btn-block modulesButton\" onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$No&vtyp=0&mdl=$mdlACAorPMS');\">
            <img src=\"cmn_images/$menuImages[$i]\" style=\"margin:5px; padding-right: 1em; height:58px; width:auto; position: relative; vertical-align: middle;float:left;\">
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
        $cntent .= "
      </p>
    </div>";
        echo $cntent;
        if ($vwtyp >= 10) {
            loadAcaMdl();
        }
    } else if ($pgNo == 1) {
        require "smmry_dshbrd.php";
    } else if ($pgNo == 2) {
        require "assmnt_shts.php";
    } else if ($pgNo == 3) {
        require "task_asgnmnts.php";
    } else if ($pgNo == 4) {
        require "groups_courses.php";
    } else if ($pgNo == 5) {
        require "pos_holders.php";
    } else if ($pgNo == 6) {
        require "asmnt_periods.php";
    } else if ($pgNo == 7) {
        require "rpt_types.php";
    } else if ($pgNo == 8) {
        require "courses_objctvs.php";
    } else if ($pgNo == 9) {
        require "sbjcts_trgts.php";
    } else if ($pgNo == 10) {
        require "grade_systems.php";
    } else if ($pgNo == 11) {
        require "aca_rpts.php";
    } else {
        restricted();
    }
}

function getAcaPgPrmssns($prsnID, $orgid, $usrID)
{
    global $ssnRoles;
    $mdlNm = "Learning/Performance Management";
    $rslts = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $sqlStr = "Select (select oprtnl_crncy_id from org.org_details where org_id = $orgid), "
        . "pasn.get_prsn_siteid(" . $prsnID . "), "
        . "pasn.get_prsn_divid_of_spctype(" . $prsnID . ", 'Access Control Group'),"
        . "scm.getUserStoreID(" . $usrID . ", " . $orgid . "), "
        . "sec.test_prmssns('View Summary Reports', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "') vwInvntry, "
        . "sec.test_prmssns('View Learning/Performance Management', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Assessment Sheets', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Task Assignment Setups', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Groups/Courses/Subjects', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Position Holders', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Assessment Periods', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Assessment Reports Types', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Only Self-Created Sheets', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Only Self-Created Summary Reports', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Summary Reports', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Summary Reports', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Summary Reports', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Record History', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View SQL', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), 
               gst.get_pssbl_val_desc(gst.getenbldpssblvalid('Default Course/Objective/Programme Label ACA%', gst.get_lov_id('All Other Performance Setups'))), 
               gst.get_pssbl_val_desc(gst.getenbldpssblvalid('Default Subject/Task Label ACA%', gst.get_lov_id('All Other Performance Setups'))), 
               gst.get_pssbl_val_desc(gst.getenbldpssblvalid('Default Group Label ACA%', gst.get_lov_id('All Other Performance Setups'))),
               gst.get_pssbl_val_desc(gst.getenbldpssblvalid('Learning/Performance Management', gst.get_lov_id('Customized Module Names'))), 
               gst.get_pssbl_val_desc(gst.getenbldpssblvalid('Default Course/Objective/Programme Label PMS%', gst.get_lov_id('All Other Performance Setups'))), 
               gst.get_pssbl_val_desc(gst.getenbldpssblvalid('Default Subject/Task Label PMS%', gst.get_lov_id('All Other Performance Setups'))), 
               gst.get_pssbl_val_desc(gst.getenbldpssblvalid('Default Group Label PMS%', gst.get_lov_id('All Other Performance Setups')))";
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        $rslts[0] = ((int) $row[0]);
        $rslts[1] = ((int) $row[1]);
        $rslts[2] = ((int) $row[2]);
        $rslts[3] = ((int) $row[3]);
        $rslts[4] = ((int) $row[4]);
        $rslts[5] = ((int) $row[5]);
        $rslts[6] = ((int) $row[6]);
        $rslts[7] = ((int) $row[7]);
        $rslts[8] = ((int) $row[8]);
        $rslts[9] = ((int) $row[9]);
        $rslts[10] = ((int) $row[10]);
        $rslts[11] = ((int) $row[11]);
        $rslts[12] = ((int) $row[12]);
        $rslts[13] = ((int) $row[13]);
        $rslts[14] = ((int) $row[14]);
        $rslts[15] = ((int) $row[15]);
        $rslts[16] = ((int) $row[16]);
        $rslts[17] = ((int) $row[17]);
        $rslts[18] = ((int) $row[18]);
        $rslts[19] = ($row[19]);
        $rslts[20] = ($row[20]);
        $rslts[21] = ($row[21]);
        $rslts[22] = ($row[22]);
        $rslts[23] = ($row[23]);
        $rslts[24] = ($row[24]);
        $rslts[25] = ($row[25]);
    }
    return $rslts;
}

function get_AcaSbjcts($searchWord, $searchIn, $offset, $limit_size)
{
    global $orgID;
    global $mdlACAorPMS;
    $strSql = "";
    $whrcls = "";
    $extrWhere = "";
    if ($mdlACAorPMS == "ACA") {
        $extrWhere = " and (a.record_type='Subject')";
    } else if ($mdlACAorPMS == "PMS") {
        $extrWhere = " and (a.record_type='Task')";
    }
    if ($searchIn == "Description") {
        $whrcls = " AND (a.subject_name ilike '" . loc_db_escape_string($searchWord) .
            "' or a.subject_code ilike '" . loc_db_escape_string($searchWord) .
            "' or a.subject_desc ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT a.subject_id, 
a.subject_code, a.subject_name,a.subject_desc, a.is_enabled, a.record_type
  FROM aca.aca_subjects a " .
        "WHERE(a.org_id = " . $orgID . $whrcls . $extrWhere .
        ") ORDER BY a.subject_id DESC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AcaSbjctsExprt($offset, $limit_size, $searchWord, $searchIn)
{
    global $orgID;
    $strSql = "";
    $whrcls = "";
    $extrWhere = "";
    if ($searchIn == "Name") {
        $whrcls = " AND (a.subject_name ilike '" . loc_db_escape_string($searchWord) .
            "' or a.subject_code ilike '" . loc_db_escape_string($searchWord) .
            "' or a.subject_desc ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT a.record_type, a.subject_code, a.subject_name,a.subject_desc, (CASE WHEN a.is_enabled='1' THEN 'YES' ELSE 'NO' END)  
  FROM aca.aca_subjects a " .
        "WHERE(a.org_id = " . $orgID . $whrcls . $extrWhere .
        ") ORDER BY a.subject_id DESC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_AcaSbjcts($searchWord, $searchIn)
{
    global $orgID;
    global $mdlACAorPMS;
    $strSql = "";
    $whrcls = "";
    $extrWhere = "";
    if ($mdlACAorPMS == "ACA") {
        $extrWhere = " and (a.record_type='Subject')";
    } else if ($mdlACAorPMS == "PMS") {
        $extrWhere = " and (a.record_type='Task')";
    }
    if ($searchIn == "Description") {
        $whrcls = " AND (a.subject_name ilike '" . loc_db_escape_string($searchWord) .
            "' or a.subject_code ilike '" . loc_db_escape_string($searchWord) .
            "' or a.subject_desc ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT count(1) 
  FROM aca.aca_subjects a " .
        "WHERE(a.org_id = " . $orgID . $whrcls . $extrWhere .
        ")";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return 0.00;
}

function getNew_AcaSbjctsID()
{
    $strSql = "select nextval('aca.aca_subjects_subject_id_seq')";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function create_AcaSbjcts($subjct_id, $recType, $sbjctCode, $sbjctNm, $sbjctDesc, $isEnbld)
{
    global $orgID;
    global $usrID;
    $insSQL = "INSERT INTO aca.aca_subjects(
            subject_id, record_type, subject_code, subject_name, subject_desc, is_enabled, created_by, 
            creation_date, last_update_by, last_update_date, org_id) " .
        "VALUES (" . $subjct_id . ", '" . loc_db_escape_string($recType) .
        "', '" . loc_db_escape_string($sbjctCode) .
        "', '" . loc_db_escape_string($sbjctNm) .
        "', '" . loc_db_escape_string($sbjctDesc) .
        "', '" . cnvrtBoolToBitStr($isEnbld) .
        "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $orgID . ")";
    return execUpdtInsSQL($insSQL);
}

function update_AcaSbjcts($subjct_id, $recType, $sbjctCode, $sbjctNm, $sbjctDesc, $isEnbld)
{
    global $usrID;
    $updtSQL = "UPDATE aca.aca_subjects SET " .
        "record_type='" . loc_db_escape_string($recType) .
        "', subject_code='" . loc_db_escape_string($sbjctCode) .
        "', subject_name='" . loc_db_escape_string($sbjctNm) .
        "', subject_desc='" . loc_db_escape_string($sbjctDesc) .
        "', is_enabled='" . cnvrtBoolToBitStr($isEnbld) .
        "',last_update_by = " . $usrID . ", " .
        "last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (subject_id =" . $subjct_id . ")";
    return execUpdtInsSQL($updtSQL);
}

function update_AcaSbjcts1($subjct_id, $recType, $sbjctCode, $sbjctNm)
{
    global $usrID;
    $updtSQL = "UPDATE aca.aca_subjects SET " .
        "record_type='" . loc_db_escape_string($recType) .
        "', subject_code='" . loc_db_escape_string($sbjctCode) .
        "', subject_name='" . loc_db_escape_string($sbjctNm) .
        "',last_update_by = " . $usrID . ", " .
        "last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (subject_id =" . $subjct_id . ")";
    return execUpdtInsSQL($updtSQL);
}

function delete_AcaSbjcts($pKeyID, $pkeyDesc)
{
    $delSQL = "DELETE FROM aca.aca_subjects WHERE subject_id = " . $pKeyID . "";
    $affctd1 = execUpdtInsSQL($delSQL, $pkeyDesc);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Subject/Task(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_AcaCourses($searchWord, $searchIn, $offset, $limit_size)
{
    global $orgID;
    global $mdlACAorPMS;
    $strSql = "";
    $whrcls = "";
    $extrWhere = "";
    if ($mdlACAorPMS == "ACA") {
        $extrWhere = " and (a.record_type='Course')";
    } else if ($mdlACAorPMS == "PMS") {
        $extrWhere = " and (a.record_type='Objective')";
    }
    if ($searchIn == "Description") {
        $whrcls = " AND (a.course_code ilike '" . loc_db_escape_string($searchWord) .
            "' or a.course_name ilike '" . loc_db_escape_string($searchWord) .
            "' or a.course_desc ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT a.course_id, 
a.course_code, a.course_name, a.course_desc, a.is_enabled, a.record_type
  FROM aca.aca_courses a " .
        "WHERE(a.org_id = " . $orgID . $whrcls . $extrWhere .
        ") ORDER BY a.course_id DESC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AcaCoursesExprt($offset, $limit_size, $searchWord, $searchIn)
{
    global $orgID;
    $strSql = "";
    $whrcls = "";
    $extrWhere = "";
    if ($searchIn == "Name") {
        $whrcls = " AND (a.course_code ilike '" . loc_db_escape_string($searchWord) .
            "' or a.course_name ilike '" . loc_db_escape_string($searchWord) .
            "' or a.course_desc ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT a.record_type, a.course_code, a.course_name, a.course_desc, (CASE WHEN a.is_enabled='1' THEN 'YES' ELSE 'NO' END)
  FROM aca.aca_courses a " .
        "WHERE(a.org_id = " . $orgID . $whrcls . $extrWhere .
        ") ORDER BY a.course_id DESC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_AcaCourses($searchWord, $searchIn)
{
    global $orgID;
    global $mdlACAorPMS;
    $strSql = "";
    $whrcls = "";
    $extrWhere = "";
    if ($mdlACAorPMS == "ACA") {
        $extrWhere = " and (a.record_type='Course')";
    } else if ($mdlACAorPMS == "PMS") {
        $extrWhere = " and (a.record_type='Objective')";
    }
    if ($searchIn == "Description") {
        $whrcls = " AND (a.course_code ilike '" . loc_db_escape_string($searchWord) .
            "' or a.course_name ilike '" . loc_db_escape_string($searchWord) .
            "' or a.course_desc ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT count(1) 
  FROM aca.aca_courses a " .
        "WHERE(a.org_id = " . $orgID . $whrcls . $extrWhere .
        ")";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return 0.00;
}

function getNew_AcaCoursesID()
{
    $strSql = "select nextval('aca.aca_courses_course_id_seq')";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function create_AcaCourses($course_id, $recType, $courseCode, $courseNm, $courseDesc, $isEnbld)
{
    global $orgID;
    global $usrID;
    $insSQL = "INSERT INTO aca.aca_courses(
            course_id, record_type, course_code, course_name, course_desc, is_enabled, created_by, 
            creation_date, last_update_by, last_update_date, org_id) " .
        "VALUES (" . $course_id . ", '" . loc_db_escape_string($recType) .
        "', '" . loc_db_escape_string($courseCode) .
        "', '" . loc_db_escape_string($courseNm) .
        "', '" . loc_db_escape_string($courseDesc) .
        "', '" . cnvrtBoolToBitStr($isEnbld) .
        "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $orgID . ")";
    return execUpdtInsSQL($insSQL);
}

function update_AcaCourses($course_id, $recType, $courseCode, $courseNm, $courseDesc, $isEnbld)
{
    global $usrID;
    $updtSQL = "UPDATE aca.aca_courses SET " .
        "record_type='" . loc_db_escape_string($recType) .
        "', course_code='" . loc_db_escape_string($courseCode) .
        "', course_name='" . loc_db_escape_string($courseNm) .
        "', course_desc='" . loc_db_escape_string($courseDesc) .
        "', is_enabled='" . cnvrtBoolToBitStr($isEnbld) .
        "',last_update_by = " . $usrID . ", " .
        "last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (course_id =" . $course_id . ")";
    return execUpdtInsSQL($updtSQL);
}

function update_AcaCourses1($course_id, $recType, $courseCode, $courseNm)
{
    global $usrID;
    $updtSQL = "UPDATE aca.aca_courses SET " .
        "record_type='" . loc_db_escape_string($recType) .
        "', course_code='" . loc_db_escape_string($courseCode) .
        "', course_name='" . loc_db_escape_string($courseNm) .
        "', last_update_by = " . $usrID . ", " .
        "last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (course_id =" . $course_id . ")";
    return execUpdtInsSQL($updtSQL);
}

function delete_AcaCourses($pKeyID, $pkeyDesc)
{
    $delSQL = "DELETE FROM aca.aca_courses WHERE course_id = " . $pKeyID . "";
    $affctd1 = execUpdtInsSQL($delSQL, $pkeyDesc);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Course/Objective(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_AcaAssessTypes($searchWord, $searchIn, $offset, $limit_size)
{
    global $orgID;
    $strSql = "";
    $whrcls = "";
    $extrWhere = "";
    if ($searchIn == "Description") {
        $whrcls = " AND (a.assmnt_typ_nm ilike '" . loc_db_escape_string($searchWord) .
            "' or a.assmnt_typ_desc ilike '" . loc_db_escape_string($searchWord) .
            "' or a.assmnt_type ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT a.assmnt_typ_id, 
a.assmnt_typ_nm, a.assmnt_typ_desc, a.assmnt_type, a.is_enabled, a.assmnt_level, 
a.lnkd_assmnt_typ_id, aca.get_assesstypnm(a.lnkd_assmnt_typ_id), 
        a.dflt_grade_scale_id, aca.get_grade_scalenm(a.dflt_grade_scale_id) 
  FROM aca.aca_assessment_types a " .
        "WHERE(a.org_id = " . $orgID . $whrcls . $extrWhere .
        ") ORDER BY a.assmnt_typ_id DESC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AcaAssessTypesExprt($offset, $limit_size, $searchWord, $searchIn)
{
    global $orgID;
    $strSql = "";
    $whrcls = "";
    $extrWhere = "";
    if ($searchIn == "Description") {
        $whrcls = " AND (a.assmnt_typ_nm ilike '" . loc_db_escape_string($searchWord) .
            "' or a.assmnt_typ_desc ilike '" . loc_db_escape_string($searchWord) .
            "' or a.assmnt_type ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT a.assmnt_typ_nm, a.assmnt_typ_desc, a.assmnt_type, a.assmnt_level, 
  aca.get_assesstypnm(a.lnkd_assmnt_typ_id), aca.get_grade_scalenm(a.dflt_grade_scale_id), 
  (CASE WHEN a.is_enabled='1' THEN 'YES' ELSE 'NO' END), b.column_no, b.column_name,
  b.column_desc, b.column_header_text, b.section_located, b.data_type, b.col_min_val, b.col_max_val,
  b.column_formular, b.html_css_style, (CASE WHEN b.is_dsplyd='1' THEN 'YES' ELSE 'NO' END)
  FROM aca.aca_assessment_types a, aca.aca_assessment_columns b " .
        "WHERE(a.assmnt_typ_id=b.assmnt_typ_id and a.org_id = " . $orgID . $whrcls . $extrWhere .
        ") ORDER BY a.assmnt_typ_id DESC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_AcaAssessTypes($searchWord, $searchIn)
{
    global $orgID;
    $strSql = "";
    $whrcls = "";
    $extrWhere = "";
    if ($searchIn == "Description") {
        $whrcls = " AND (a.assmnt_typ_nm ilike '" . loc_db_escape_string($searchWord) .
            "' or a.assmnt_typ_desc ilike '" . loc_db_escape_string($searchWord) .
            "' or a.assmnt_type ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT count(1) 
        FROM aca.aca_assessment_types a " .
        "WHERE(a.org_id = " . $orgID . $whrcls . $extrWhere .
        ")";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return 0.00;
}

function get_AcaAssessTypeDet($assessTypeID)
{
    $strSql = "";
    $strSql = "SELECT a.assmnt_typ_id, a.assmnt_typ_nm, a.assmnt_typ_desc, a.assmnt_type, a.is_enabled, 
        a.assmnt_level, a.lnkd_assmnt_typ_id, aca.get_assesstypnm(a.lnkd_assmnt_typ_id), 
        a.dflt_grade_scale_id, aca.get_grade_scalenm(a.dflt_grade_scale_id)
  FROM aca.aca_assessment_types a " .
        "WHERE(a.assmnt_typ_id = " . $assessTypeID . ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getNew_AcaAssessTypesID()
{
    $strSql = "select nextval('aca.assessment_types_assmnt_typ_id_seq')";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function get_AssessTypesID($assesstypname, $orgid)
{
    $strSql = "select assmnt_typ_id from aca.aca_assessment_types where lower(assmnt_typ_nm) = lower('"
        . loc_db_escape_string($assesstypname) . "') and org_id = " . $orgid;
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function create_AcaAssessTypes(
    $assesstyp_id,
    $assessTypeNm,
    $assessTypeDesc,
    $assessType,
    $assessLevel,
    $isEnbld,
    $lnkdAssessID,
    $lnkdGradeScaleID
) {
    global $orgID;
    global $usrID;
    $insSQL = "INSERT INTO aca.aca_assessment_types(assmnt_typ_id, assmnt_typ_nm, assmnt_typ_desc, created_by, creation_date, last_update_by,
                            last_update_date, is_enabled, assmnt_type, assmnt_level, lnkd_assmnt_typ_id, org_id, dflt_grade_scale_id) " .
        "VALUES (" . $assesstyp_id . ", '" . loc_db_escape_string($assessTypeNm) .
        "', '" . loc_db_escape_string($assessTypeDesc) .
        "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . cnvrtBoolToBitStr($isEnbld) .
        "', '" . loc_db_escape_string($assessType) .
        "', '" . loc_db_escape_string($assessLevel) .
        "', " . $lnkdAssessID . ", " . $orgID . ", " . $lnkdGradeScaleID . ")";
    return execUpdtInsSQL($insSQL);
}

function update_AcaAssessTypes(
    $assesstyp_id,
    $assessTypeNm,
    $assessTypeDesc,
    $assessType,
    $assessLevel,
    $isEnbld,
    $lnkdAssessID,
    $lnkdGradeScaleID
) {
    global $usrID;
    $updtSQL = "UPDATE aca.aca_assessment_types SET " .
        "assmnt_typ_nm='" . loc_db_escape_string($assessTypeNm) .
        "', assmnt_typ_desc='" . loc_db_escape_string($assessTypeDesc) .
        "', assmnt_type='" . loc_db_escape_string($assessType) .
        "', assmnt_level='" . loc_db_escape_string($assessLevel) .
        "', is_enabled='" . cnvrtBoolToBitStr($isEnbld) .
        "', lnkd_assmnt_typ_id = " . $lnkdAssessID .
        ", dflt_grade_scale_id = " . $lnkdGradeScaleID .
        ", last_update_by = " . $usrID .
        ", last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (assmnt_typ_id =" . $assesstyp_id . ")";
    return execUpdtInsSQL($updtSQL);
}

function delete_AcaAssessTypes($pKeyID, $pkeyDesc)
{
    $delSQL = "DELETE FROM aca.aca_assessment_types WHERE assmnt_typ_id = " . $pKeyID . "";
    $affctd1 = execUpdtInsSQL($delSQL, $pkeyDesc);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Assessment Type(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

//Grade Scales

function get_AcaGradeScales($searchWord, $searchIn, $offset, $limit_size)
{
    global $orgID;
    $strSql = "";
    $whrcls = "";
    $extrWhere = "";
    if ($searchIn == "Description") {
        $whrcls = " AND (a.scale_name ilike '" . loc_db_escape_string($searchWord) .
            "' or a.scale_desc ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT DISTINCT a.scale_id, 
a.scale_name, a.scale_desc, a.is_enabled 
  FROM aca.aca_grade_scales a " .
        "WHERE(a.org_id = " . $orgID . $whrcls . $extrWhere .
        ") ORDER BY a.scale_id DESC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_AcaGradeScales($searchWord, $searchIn)
{
    global $orgID;
    $strSql = "";
    $whrcls = "";
    $extrWhere = "";
    if ($searchIn == "Description") {
        $whrcls = " AND (a.scale_name ilike '" . loc_db_escape_string($searchWord) .
            "' or a.scale_desc ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT count(DISTINCT a.scale_id)
  FROM aca.aca_grade_scales a " .
        "WHERE(a.org_id = " . $orgID . $whrcls . $extrWhere .
        ")";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return 0.00;
}

function get_AcaGradeScaleLines($acaGradeScalesID, $offset, $limit_size, $searchWord, $searchIn)
{
    $strSql = "";
    $whrcls = "";
    $extrWhere = "";
    if ($searchIn == "Name") {
        $whrcls = " AND (a.grade_code ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT a.scale_line_id,
a.grade_code, a.grade_gpa_value, a.band_min_value, a.band_max_value, a.grade_description 
  FROM aca.aca_grade_scales a " .
        "WHERE(a.scale_id = " . $acaGradeScalesID . $whrcls . $extrWhere .
        ") ORDER BY a.grade_code LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AcaGradeScaleLinesExprt($offset, $limit_size, $searchWord, $searchIn)
{
    $strSql = "";
    $whrcls = "";
    $extrWhere = "";
    if ($searchIn == "Name") {
        $whrcls = " AND (a.grade_code ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT a.scale_name,a.scale_desc, (CASE WHEN a.is_enabled='1' THEN 'YES' ELSE 'NO' END),
a.grade_code, a.grade_description, a.grade_gpa_value, a.band_min_value, a.band_max_value 
  FROM aca.aca_grade_scales a " .
        "WHERE(1=1 " . $whrcls . $extrWhere .
        ") ORDER BY a.scale_name, a.grade_code LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_AcaGradeScaleLines($acaGradeScalesID, $searchWord, $searchIn)
{
    $strSql = "";
    $whrcls = "";
    $extrWhere = "";
    if ($searchIn == "Name") {
        $whrcls = " AND (a.grade_code ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT count(a.scale_line_id) 
  FROM aca.aca_grade_scales a " .
        "WHERE(a.scale_id = " . $acaGradeScalesID . $whrcls . $extrWhere .
        ")";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return 0.00;
}

function get_AcaGradeScaleDet($scaleID)
{
    $strSql = "";
    $strSql = "SELECT DISTINCT a.scale_id, 
a.scale_name, a.scale_desc, a.is_enabled 
  FROM aca.aca_grade_scales a " .
        "WHERE(a.scale_id = " . $scaleID . ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getNew_AcaGradeScalesID()
{
    $strSql = "select nextval('aca.aca_grade_scale_id_seq')";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function get_GradeScalesID($scalename, $orgid)
{
    $strSql = "select distinct scale_id from aca.aca_grade_scales where lower(scale_name) = lower('"
        . loc_db_escape_string($scalename) . "') and org_id = " . $orgid;
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function create_AcaGradeScales($scale_id, $scaleName, $scaleDesc, $isEnbld)
{
    global $orgID;
    global $usrID;
    $insSQL = "INSERT INTO aca.aca_grade_scales(
	scale_id, scale_name, scale_desc, is_enabled, org_id, grade_code, grade_gpa_value, band_min_value, band_max_value, created_by, creation_date, last_update_by, last_update_date, grade_description)
	  VALUES (" . $scale_id . ", '" . loc_db_escape_string($scaleName) .
        "', '" . loc_db_escape_string($scaleDesc) .
        "', '" . cnvrtBoolToBitStr($isEnbld) .
        "', " . $orgID . ",'',0,0,0, " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '')";
    return execUpdtInsSQL($insSQL);
}

function update_AcaGradeScales($scale_id, $scaleName, $scaleDesc, $isEnbld)
{
    global $usrID;
    $updtSQL = "UPDATE aca.aca_grade_scales SET " .
        "scale_name='" . loc_db_escape_string($scaleName) .
        "', scale_desc='" . loc_db_escape_string($scaleDesc) .
        "', is_enabled='" . cnvrtBoolToBitStr($isEnbld) .
        "', last_update_by = " . $usrID .
        ", last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (scale_id =" . $scale_id . ")";
    return execUpdtInsSQL($updtSQL);
}

function delete_AcaGradeScales($pKeyID, $pkeyDesc)
{
    $delSQL = "DELETE FROM aca.aca_grade_scales WHERE scale_id = " . $pKeyID . "";
    $affctd1 = execUpdtInsSQL($delSQL, $pkeyDesc);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Grade Scale(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getNew_AcaGradeScalesLnID()
{
    $strSql = "select nextval('aca.aca_grade_scales_scale_line_id_seq')";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function get_GradeScalesLnID($gradeNm, $scaleid)
{
    $strSql = "select scale_line_id from aca.aca_grade_scales where lower(grade_code) = lower('"
        . loc_db_escape_string($gradeNm) . "') and scale_id = " . $scaleid;
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function create_AcaGradeScalesLn($scale_id, $scaleName, $scaleDesc, $isEnbld, $scaleLineID, $gradeNm, $gradeDesc, $gradeGPA, $gradeMin, $gradeMax)
{
    global $orgID;
    global $usrID;
    $insSQL = "INSERT INTO aca.aca_grade_scales(
	scale_line_id, scale_id, scale_name, scale_desc, is_enabled, org_id, grade_code, 
        grade_gpa_value, band_min_value, band_max_value, created_by, creation_date, 
        last_update_by, last_update_date, grade_description)
	  VALUES (" . $scaleLineID . ", " . $scale_id . ", '" . loc_db_escape_string($scaleName) .
        "', '" . loc_db_escape_string($scaleDesc) .
        "', '" . cnvrtBoolToBitStr($isEnbld) .
        "', " . $orgID . ",'" . loc_db_escape_string($gradeNm) .
        "'," . $gradeGPA . "," . $gradeMin . "," . $gradeMax . ", " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($gradeDesc) .
        "')";
    $ttl = execUpdtInsSQL($insSQL);
    $delSQL = "DELETE FROM aca.aca_grade_scales WHERE grade_code='' and scale_id = " . $scale_id . "";
    execUpdtInsSQL($delSQL);
    return $ttl;
}

function update_AcaGradeScalesLn($scale_id, $scaleName, $scaleDesc, $isEnbld, $scaleLineID, $gradeNm, $gradeDesc, $gradeGPA, $gradeMin, $gradeMax)
{
    global $usrID;
    $updtSQL = "UPDATE aca.aca_grade_scales SET " .
        "scale_name='" . loc_db_escape_string($scaleName) .
        "', scale_desc='" . loc_db_escape_string($scaleDesc) .
        "', is_enabled='" . cnvrtBoolToBitStr($isEnbld) .
        "', grade_code='" . loc_db_escape_string($gradeNm) .
        "', grade_description='" . loc_db_escape_string($gradeDesc) .
        "', grade_gpa_value = " . $gradeGPA .
        ", band_min_value = " . $gradeMin .
        ", band_max_value = " . $gradeMax .
        ", last_update_by = " . $usrID .
        ", last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (scale_line_id =" . $scaleLineID . ")";
    $ttl = execUpdtInsSQL($updtSQL);
    $delSQL = "DELETE FROM aca.aca_grade_scales WHERE grade_code='' and scale_id = " . $scale_id . "";
    execUpdtInsSQL($delSQL);
    return $ttl;
}

function delete_AcaGradeScalesLn($pKeyID, $pkeyDesc)
{
    $delSQL = "DELETE FROM aca.aca_grade_scales WHERE scale_line_id = " . $pKeyID . "";
    $affctd1 = execUpdtInsSQL($delSQL, $pkeyDesc);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Grade Scale(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

//End Grade Scales
function get_AcaAssessCols($asessTypID, $offset, $limit_size, $searchIn, $searchWord)
{
    $whereClause = " and (a.column_name ilike '" . loc_db_escape_string($searchWord) .
        "' or a.column_desc ilike '" . loc_db_escape_string($searchWord) .
        "' or a.column_header_text ilike '" . loc_db_escape_string($searchWord) . "')";
    $strSql = "SELECT column_id, column_name, column_desc, column_header_text, section_located, data_type, "
        . " data_length, is_formula_column, column_formular, is_enabled, column_no, col_min_val, col_max_val, is_dsplyd, html_css_style, lov_name " .
        "FROM aca.aca_assessment_columns a " .
        "WHERE a.assmnt_typ_id = " . $asessTypID . $whereClause .
        " ORDER BY a.section_located, a.column_name LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Ttl_AcaAssessCols($asessTypID, $searchIn, $searchWord)
{
    $whereClause = " and (a.column_name ilike '" . loc_db_escape_string($searchWord) .
        "' or a.column_desc ilike '" . loc_db_escape_string($searchWord) .
        "' or a.column_header_text ilike '" . loc_db_escape_string($searchWord) . "')";
    $strSql = "SELECT count(1) " .
        "FROM aca.aca_assessment_columns a " .
        "WHERE assmnt_typ_id = " . $asessTypID . $whereClause . "";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return 0;
}

function getNew_AcaAssessColsID()
{
    $strSql = "select nextval('aca.aca_assessment_columns_column_id_seq')";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function get_AssessTypesColID($colname, $assesstypid)
{
    $strSql = "select column_id from aca.aca_assessment_columns where lower(column_name) = lower('"
        . loc_db_escape_string($colname) . "') and assmnt_typ_id = " . $assesstypid;
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function create_AcaAssessCols(
    $colID,
    $assesstyp_id,
    $colNm,
    $colDesc,
    $colHdrText,
    $isFormula,
    $sqlFormula,
    $sectnLocated,
    $dataType,
    $dataLength,
    $isEnbld,
    $colNum,
    $ln_MinValue,
    $ln_MaxValue,
    $ln_IsDsplyd,
    $ln_CSStyle,
    $ln_LovName
) {
    global $usrID;
    $insSQL = "INSERT INTO aca.aca_assessment_columns(
	column_id, assmnt_typ_id, column_name, column_desc, column_header_text, is_formula_column, column_formular, 
	created_by, creation_date, last_update_by, last_update_date, section_located, data_type, 
	data_length, is_enabled, column_no, col_min_val, col_max_val,is_dsplyd,html_css_style, lov_name) " .
        "VALUES (" . $colID .
        ", " . $assesstyp_id .
        ", '" . loc_db_escape_string($colNm) .
        "', '" . loc_db_escape_string($colDesc) .
        "', '" . loc_db_escape_string($colHdrText) .
        "', '" . cnvrtBoolToBitStr($isFormula) .
        "', '" . loc_db_escape_string($sqlFormula) .
        "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($sectnLocated) .
        "', '" . loc_db_escape_string($dataType) .
        "', " . $dataLength .
        ", '" . cnvrtBoolToBitStr($isEnbld) .
        "', " . $colNum .
        ", " . $ln_MinValue .
        ", " . $ln_MaxValue .
        ", '" . cnvrtBoolToBitStr($ln_IsDsplyd) .
        "', '" . loc_db_escape_string($ln_CSStyle) .
        "', '" . loc_db_escape_string($ln_LovName) .
        "')";
    return execUpdtInsSQL($insSQL);
}

function update_AcaAssessCols(
    $colID,
    $assesstyp_id,
    $colNm,
    $colDesc,
    $colHdrText,
    $isFormula,
    $sqlFormula,
    $sectnLocated,
    $dataType,
    $dataLength,
    $isEnbld,
    $colNum,
    $ln_MinValue,
    $ln_MaxValue,
    $ln_IsDsplyd,
    $ln_CSStyle,
    $ln_LovName
) {
    global $usrID;
    $updtSQL = "UPDATE aca.aca_assessment_columns SET " .
        "column_name='" . loc_db_escape_string($colNm) .
        "', column_desc='" . loc_db_escape_string($colDesc) .
        "', column_header_text='" . loc_db_escape_string($colHdrText) .
        "', column_formular='" . loc_db_escape_string($sqlFormula) .
        "', is_formula_column='" . cnvrtBoolToBitStr($isFormula) .
        "', is_enabled='" . cnvrtBoolToBitStr($isEnbld) .
        "', section_located='" . loc_db_escape_string($sectnLocated) .
        "', data_type='" . loc_db_escape_string($dataType) .
        "', data_length = " . $dataLength .
        ", column_no = " . $colNum .
        ", col_min_val = " . $ln_MinValue .
        ", col_max_val = " . $ln_MaxValue .
        ", is_dsplyd='" . cnvrtBoolToBitStr($ln_IsDsplyd) .
        "', html_css_style='" . loc_db_escape_string($ln_CSStyle) .
        "', lov_name='" . loc_db_escape_string($ln_LovName) .
        "', last_update_by = " . $usrID .
        ", last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (column_id =" . $colID . ")";
    return execUpdtInsSQL($updtSQL);
}

function delete_AcaAssessCols($pKeyID, $pkeyDesc)
{
    $delSQL = "DELETE FROM aca.aca_assessment_columns WHERE column_id = " . $pKeyID . "";
    $affctd1 = execUpdtInsSQL($delSQL, $pkeyDesc);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Assessment Column(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function compute_one_assess_sht($p_assess_hdrid, $p_who_rn)
{
    $strSql = "select aca.compute_one_assess_sht(" . $p_assess_hdrid .
        ", " . $p_who_rn . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "ERROR:No Result";
}

function get_AcaPeriods($searchWord, $searchIn, $offset, $limit_size)
{
    global $orgID;
    $strSql = "";
    $whrcls = "";
    $extrWhere = "";
    if ($searchIn == "Description") {
        $whrcls = " AND (a.assmnt_period_name ilike '" . loc_db_escape_string($searchWord) .
            "' or a.assmnt_period_desc ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT a.assmnt_period_id, 
                a.assmnt_period_name, a.assmnt_period_desc, 
                to_char(to_timestamp(a.period_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), 
                to_char(to_timestamp(a.period_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), 
                a.period_type, a.period_status, a.period_number 
            FROM aca.aca_assessment_periods a " .
        "WHERE(a.org_id = " . $orgID . $whrcls . $extrWhere .
        ") ORDER BY a.period_start_date DESC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_AcaPeriods($searchWord, $searchIn)
{
    global $orgID;
    $strSql = "";
    $whrcls = "";
    $extrWhere = "";
    if ($searchIn == "Description") {
        $whrcls = " AND (a.assmnt_period_name ilike '" . loc_db_escape_string($searchWord) .
            "' or a.assmnt_period_desc ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT count(1) 
  FROM aca.aca_assessment_periods a " .
        "WHERE(a.org_id = " . $orgID . $whrcls . $extrWhere .
        ")";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return 0.00;
}

function getNew_AcaPeriodsID()
{
    $strSql = "select nextval('aca.aca_assessment_periods_assmnt_period_id_seq')";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function create_AcaPeriods($period_id, $periodNm, $periodDesc, $strtDte, $endDte, $periodStatus, $periodType, $ln_PeriodNumber)
{
    global $orgID;
    global $usrID;
    if ($strtDte != "") {
        $strtDte = cnvrtDMYToYMD($strtDte);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYToYMD($endDte);
    }
    $insSQL = "INSERT INTO aca.aca_assessment_periods(
	assmnt_period_id, assmnt_period_name, assmnt_period_desc, period_start_date, period_end_date, 
        created_by, creation_date, last_update_by, last_update_date, period_status, org_id, period_type, period_number)
	   VALUES (" . $period_id . ", '" . loc_db_escape_string($periodNm) .
        "', '" . loc_db_escape_string($periodDesc) .
        "', '" . loc_db_escape_string($strtDte) .
        "', '" . loc_db_escape_string($endDte) .
        "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($periodStatus) .
        "', " . $orgID . ", '" . loc_db_escape_string($periodType) .
        "', " . $ln_PeriodNumber . ")";
    return execUpdtInsSQL($insSQL);
}

function update_AcaPeriods($period_id, $periodNm, $periodDesc, $strtDte, $endDte, $periodStatus, $periodType, $ln_PeriodNumber)
{
    global $usrID;
    if ($strtDte != "") {
        $strtDte = cnvrtDMYToYMD($strtDte);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYToYMD($endDte);
    }
    $updtSQL = "UPDATE aca.aca_assessment_periods SET " .
        "period_type='" . loc_db_escape_string($periodType) .
        "', assmnt_period_name='" . loc_db_escape_string($periodNm) .
        "', assmnt_period_desc='" . loc_db_escape_string($periodDesc) .
        "', period_start_date='" . loc_db_escape_string($strtDte) .
        "', period_end_date='" . loc_db_escape_string($endDte) .
        "', period_status='" . loc_db_escape_string($periodStatus) .
        "',period_number = " . $ln_PeriodNumber . ", last_update_by = " . $usrID . ", " .
        "last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (assmnt_period_id =" . $period_id . ")";
    return execUpdtInsSQL($updtSQL);
}

function delete_AcaPeriods($pKeyID, $pkeyDesc)
{
    $delSQL = "DELETE FROM aca.aca_assessment_periods WHERE assmnt_period_id = " . $pKeyID . "";
    $affctd1 = execUpdtInsSQL($delSQL, $pkeyDesc);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Assessment Period(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_AcaPosHldrs($searchWord, $searchIn, $offset, $limit_size)
{
    global $orgID;
    $strSql = "";
    $whrcls = "";
    $extrWhere = "";
    if ($searchIn == "All") {
        $whrcls = " AND (prs.get_prsn_name(a.person_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or prs.get_prsn_loc_id(a.person_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or org.get_pos_name(a.position_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or org.get_div_name(a.div_id) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT a.row_id, a.person_id, prs.get_prsn_name(a.person_id), prs.get_prsn_loc_id(a.person_id), 
            a.position_id, org.get_pos_name(a.position_id),
            to_char(to_timestamp(a.valid_start_date, 'YYYY-MM-DD'), 'DD-Mon-YYYY'),
            to_char(to_timestamp(a.valid_end_date, 'YYYY-MM-DD'), 'DD-Mon-YYYY'), 
            a.div_id, org.get_div_type(a.div_id), org.get_div_name(a.div_id), 
            a.div_sub_cat_id1, aca.get_coursenm(a.div_sub_cat_id1), 
            a.div_sub_cat_id2, aca.get_subjectnm(a.div_sub_cat_id2)
        FROM pasn.prsn_positions a WHERE(a.position_id >= 1 and a.person_id>0" . $whrcls . $extrWhere . ") ORDER BY a.row_id DESC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_AcaPosHldrs($searchWord, $searchIn)
{
    global $orgID;
    $strSql = "";
    $whrcls = "";
    $extrWhere = "";
    if ($searchIn == "All") {
        $whrcls = " AND (prs.get_prsn_name(a.person_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or prs.get_prsn_loc_id(a.person_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or org.get_pos_name(a.position_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or org.get_div_name(a.div_id) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT count(1) 
        FROM pasn.prsn_positions a WHERE(a.position_id >= 1 and a.person_id>0" . $whrcls . $extrWhere . ")";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return 0;
}

function getNew_AcaPosHldrsID()
{
    $strSql = "select nextval('pasn.prsn_positions_row_id_seq')";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function create_AcaPosHldrs($rowID, $prsn_id, $postn_id, $strtDte, $endDte, $divID, $divID1, $divID2)
{
    global $usrID;
    if ($strtDte != "") {
        $strtDte = cnvrtDMYToYMD($strtDte);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYToYMD($endDte);
    }
    $insSQL = "INSERT INTO pasn.prsn_positions(
	row_id, person_id, position_id, valid_start_date, valid_end_date, 
        created_by, creation_date, last_update_by, last_update_date, 
	div_id, div_sub_cat_id1, div_sub_cat_id2) " .
        " VALUES (" . $rowID . ", " . $prsn_id . ", " . $postn_id . ", '" . loc_db_escape_string($strtDte) .
        "', '" . loc_db_escape_string($endDte) .
        "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $divID . ", " . $divID1 . ", " . $divID2 . ")";
    return execUpdtInsSQL($insSQL);
}

function update_AcaPosHldrs($rowID, $prsn_id, $postn_id, $strtDte, $endDte, $divID, $divID1, $divID2)
{
    global $usrID;
    if ($strtDte != "") {
        $strtDte = cnvrtDMYToYMD($strtDte);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYToYMD($endDte);
    }
    $updtSQL = "UPDATE pasn.prsn_positions SET " .
        "  person_id= " . $prsn_id .
        ", position_id=" . $postn_id .
        ", valid_start_date='" . loc_db_escape_string($strtDte) .
        "', valid_end_date='" . loc_db_escape_string($endDte) .
        "', div_id = " . $divID .
        ", div_sub_cat_id1 = " . $divID1 .
        ", div_sub_cat_id2 = " . $divID2 .
        ", last_update_by = " . $usrID . ", " .
        "last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (row_id =" . $rowID . ")";
    return execUpdtInsSQL($updtSQL);
}

function delete_AcaPosHldrs($pKeyID, $pkeyDesc)
{
    $delSQL = "DELETE FROM pasn.prsn_positions WHERE row_id = " . $pKeyID . "";
    $affctd1 = execUpdtInsSQL($delSQL, $pkeyDesc);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Position(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_AcaClasses($searchWord, $searchIn, $offset, $limit_size)
{
    global $orgID;
    $strSql = "";
    $whrcls = "";
    $extrWhere = "";
    if ($searchIn == "Description") {
        $whrcls = " AND (a.class_name ilike '" . loc_db_escape_string($searchWord) .
            "' or a.class_desc ilike '" . loc_db_escape_string($searchWord) .
            "' or a.group_type ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT a.class_id, a.class_name, a.class_desc, a.group_type, a.is_enabled, a.lnkd_div_id
                   FROM aca.aca_classes a " .
        "WHERE(a.org_id = " . $orgID . $whrcls . $extrWhere .
        ") ORDER BY a.class_id DESC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_AcaClasses($searchWord, $searchIn)
{
    global $orgID;
    execUpdtInsSQL("DELETE
            FROM aca.aca_crsrs_n_thr_sbjcts y
            WHERE y.crse_sbjct_id IN (select tbl1.max_pkey_id
                          from (
                                   select a.class_id,
                                          a.course_id,
                                          a.subject_id,
                                          count(a.crse_sbjct_id),
                                          min(a.crse_sbjct_id) min_pkey_id,
                                          max(a.crse_sbjct_id) max_pkey_id
                                   from aca.aca_crsrs_n_thr_sbjcts a
                                   where 1 = 1
                                   group by a.class_id,
                                            a.course_id,
                                            a.subject_id
                                   having count(a.crse_sbjct_id) > 1) tbl1)");
    $strSql = "";
    $whrcls = "";
    $extrWhere = "";
    if ($searchIn == "Description") {
        $whrcls = " AND (a.class_name ilike '" . loc_db_escape_string($searchWord) .
            "' or a.class_desc ilike '" . loc_db_escape_string($searchWord) .
            "' or a.group_type ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT count(1) 
  FROM aca.aca_classes a " .
        "WHERE(a.org_id = " . $orgID . $whrcls . $extrWhere .
        ")";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return 0.00;
}

function get_AcaClassesExprt($offset, $limit_size, $searchWord, $searchIn)
{
    global $orgID;
    $strSql = "";
    $whrcls = "";
    $extrWhere = "";
    if ($searchIn == "Name") {
        $whrcls = " AND (a.class_name ilike '" . loc_db_escape_string($searchWord) .
            "' or a.class_desc ilike '" . loc_db_escape_string($searchWord) .
            "' or a.group_type ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT a.class_name, a.class_desc, a.group_type, aca.get_class_nm(a.next_class_id),
        a.group_fcltr_pos_name, a.group_rep_pos_name, a.sbjct_fcltr_pos_name, (CASE WHEN a.is_enabled='1' THEN 'YES' ELSE 'NO' END), 
        aca.get_coursecode(b.course_id), aca.get_coursenm(b.course_id), b.min_weight_crdt_hrs, b.max_weight_crdt_hrs,
        aca.get_subjectcode(c.subject_id), aca.get_subjectnm(c.subject_id), c.core_or_elective, c.weight_or_credit_hrs,
        c.period_type, c.period_number 
  FROM aca.aca_classes a, aca.aca_classes_n_thr_crses b, aca.aca_crsrs_n_thr_sbjcts c " .
        "WHERE(a.class_id=b.class_id and a.class_id=c.class_id and b.course_id=c.course_id and a.org_id = " . $orgID . $whrcls . $extrWhere .
        ") ORDER BY a.class_name DESC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_OneAcaClasseDet($classID)
{
    $strSql = "";
    $strSql = "SELECT a.class_id, a.class_name, a.class_desc, a.group_type, a.is_enabled, a.lnkd_div_id, 
        group_fcltr_pos_name, group_rep_pos_name, sbjct_fcltr_pos_name, next_class_id, aca.get_class_nm(next_class_id)  
  FROM aca.aca_classes a " .
        "WHERE(a.class_id = " . $classID . ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getNew_AcaClassesID()
{
    $strSql = "select nextval('aca.aca_classes_class_id_seq')";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function get_AcaClassesID($classname, $orgid)
{
    $strSql = "select class_id from aca.aca_classes where lower(class_name) = lower('"
        . loc_db_escape_string($classname) . "') and org_id = " . $orgid;
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function create_AcaClasses(
    $class_id,
    $classname,
    $classDesc,
    $classType,
    $lnkdDivId,
    $isEnbld,
    $lnkdAssessID,
    $acaClassesGrpFcltrPosNm,
    $acaClassesGrpRepPosNm,
    $acaClassesSbjctFcltrPosNm,
    $acaClassesNxtClassID
) {
    global $orgID;
    global $usrID;
    $insSQL = "INSERT INTO aca.aca_classes(
	class_id, class_name, class_desc, is_enabled, created_by, creation_date, last_update_by, 
        last_update_date, assessment_typ_id, lnkd_div_id, group_type, org_id, 
        group_fcltr_pos_name, group_rep_pos_name, sbjct_fcltr_pos_name, next_class_id) " .
        " VALUES (" . $class_id . ", '" . loc_db_escape_string($classname) .
        "', '" . loc_db_escape_string($classDesc) . "', '" . cnvrtBoolToBitStr($isEnbld) .
        "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $lnkdAssessID .
        ", " . $lnkdDivId . ", '" . loc_db_escape_string($classType) .
        "', " . $orgID . ", '" . loc_db_escape_string($acaClassesGrpFcltrPosNm) .
        "', '" . loc_db_escape_string($acaClassesGrpRepPosNm) .
        "', '" . loc_db_escape_string($acaClassesSbjctFcltrPosNm) .
        "', " . $acaClassesNxtClassID .
        ")";
    $rsTtl = execUpdtInsSQL($insSQL);
    if ($lnkdDivId <= 0) {
        executeSQLNoParams("select aca.chckNcreateOrgDivGrp(" . $orgID . ",
                                                    '" . loc_db_escape_string($classname) .
            "','" . loc_db_escape_string($classDesc) . "','" . loc_db_escape_string($classType) .
            "'," . $class_id . "," . $acaClassesNxtClassID .
            "," . $usrID . ")");
    }
    if (trim($acaClassesGrpFcltrPosNm) !== "") {
        executeSQLNoParams("select aca.chckNcreateOrgPosition(" . $orgID . ",
                                                    '" . loc_db_escape_string($acaClassesGrpFcltrPosNm) .
            "','" . loc_db_escape_string($acaClassesGrpFcltrPosNm) . "',-1," . $usrID . ")");
    }
    if (trim($acaClassesGrpRepPosNm) !== "") {
        executeSQLNoParams("select aca.chckNcreateOrgPosition(" . $orgID . ",
                                                    '" . loc_db_escape_string($acaClassesGrpRepPosNm) .
            "','" . loc_db_escape_string($acaClassesGrpRepPosNm) . "',-1," . $usrID . ")");
    }
    if (trim($acaClassesSbjctFcltrPosNm) !== "") {
        executeSQLNoParams("select aca.chckNcreateOrgPosition(" . $orgID . ",
                                                    '" . loc_db_escape_string($acaClassesSbjctFcltrPosNm) .
            "','" . loc_db_escape_string($acaClassesSbjctFcltrPosNm) . "',-1," . $usrID . ")");
    }
    return $rsTtl;
}

function update_AcaClasses(
    $class_id,
    $classname,
    $classDesc,
    $classType,
    $lnkdDivId,
    $isEnbld,
    $lnkdAssessID,
    $acaClassesGrpFcltrPosNm,
    $acaClassesGrpRepPosNm,
    $acaClassesSbjctFcltrPosNm,
    $acaClassesNxtClassID
) {
    global $orgID;
    global $usrID;
    $updtSQL = "UPDATE aca.aca_classes SET " .
        "class_name='" . loc_db_escape_string($classname) .
        "', class_desc='" . loc_db_escape_string($classDesc) .
        "', group_type='" . loc_db_escape_string($classType) .
        "', group_fcltr_pos_name='" . loc_db_escape_string($acaClassesGrpFcltrPosNm) .
        "', group_rep_pos_name='" . loc_db_escape_string($acaClassesGrpRepPosNm) .
        "', sbjct_fcltr_pos_name='" . loc_db_escape_string($acaClassesSbjctFcltrPosNm) .
        "', lnkd_div_id=" . loc_db_escape_string($lnkdDivId) .
        ", next_class_id=" . loc_db_escape_string($acaClassesNxtClassID) .
        ", is_enabled='" . cnvrtBoolToBitStr($isEnbld) .
        "', assessment_typ_id = " . $lnkdAssessID .
        ", last_update_by = " . $usrID .
        ", last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (class_id =" . $class_id . ")";
    $rsTtl = execUpdtInsSQL($updtSQL);

    if ($lnkdDivId <= 0) {
        executeSQLNoParams("select aca.chckNcreateOrgDivGrp(" . $orgID . ",
                                                    '" . loc_db_escape_string($classname) .
            "','" . loc_db_escape_string($classDesc) . "','" . loc_db_escape_string($classType) .
            "'," . $class_id . "," . $acaClassesNxtClassID .
            "," . $usrID . ")");
    }
    if (trim($acaClassesGrpFcltrPosNm) != "") {
        executeSQLNoParams("select aca.chckNcreateOrgPosition(" . $orgID . ",
                                                    '" . loc_db_escape_string($acaClassesGrpFcltrPosNm) .
            "','" . loc_db_escape_string($acaClassesGrpFcltrPosNm) . "',-1," . $usrID . ")");
    }
    if (trim($acaClassesGrpRepPosNm) != "") {
        executeSQLNoParams("select aca.chckNcreateOrgPosition(" . $orgID . ",
                                                    '" . loc_db_escape_string($acaClassesGrpRepPosNm) .
            "','" . loc_db_escape_string($acaClassesGrpRepPosNm) . "',-1," . $usrID . ")");
    }
    if (trim($acaClassesSbjctFcltrPosNm) != "") {
        executeSQLNoParams("select aca.chckNcreateOrgPosition(" . $orgID . ",
                                                    '" . loc_db_escape_string($acaClassesSbjctFcltrPosNm) .
            "','" . loc_db_escape_string($acaClassesSbjctFcltrPosNm) . "',-1," . $usrID . ")");
    }
    return $rsTtl;
}

function delete_AcaClasses($pKeyID, $pkeyDesc)
{
    $delSQL3 = "DELETE FROM aca.aca_crsrs_n_thr_sbjcts WHERE class_id = " . $pKeyID . "";
    $affctd3 = execUpdtInsSQL($delSQL3, $pkeyDesc);
    $delSQL2 = "DELETE FROM aca.aca_classes_n_thr_crses WHERE class_id = " . $pKeyID . "";
    $affctd2 = execUpdtInsSQL($delSQL2, $pkeyDesc);
    $delSQL = "DELETE FROM aca.aca_classes WHERE class_id = " . $pKeyID . "";
    $affctd1 = execUpdtInsSQL($delSQL, $pkeyDesc);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Assessment Group(s)!";
        $dsply .= "<br/>Deleted $affctd2 Programme/Objective(s)!";
        $dsply .= "<br/>Deleted $affctd3 Subject/Target(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_AcaClasseCrses($classID, $offset, $limit_size, $searchIn, $searchWord)
{
    $whereClause = " and (b.course_name ilike '" . loc_db_escape_string($searchWord) .
        "' or b.course_code ilike '" . loc_db_escape_string($searchWord) . "')";
    $strSql = "SELECT a.clss_crse_id, a.course_id, b.course_code, b.course_name, a.is_enabled, "
        . "REPLACE(b.course_code || '.' || b.course_name, '.' || b.course_code, '') course_name, "
        . "a.min_weight_crdt_hrs, a.max_weight_crdt_hrs, 
                aca.get_pos_hldr_prs_nm(-1, c.lnkd_div_id, -1, -1, c.group_fcltr_pos_name), 
                aca.get_pos_hldr_prs_nm(-1, c.lnkd_div_id, -1, -1, c.group_rep_pos_name) " .
        "FROM aca.aca_classes_n_thr_crses a, aca.aca_courses b, aca.aca_classes c " .
        "WHERE a.course_id=b.course_id and a.class_id=c.class_id and a.class_id = " . $classID . $whereClause .
        " ORDER BY b.course_name LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Ttl_AcaClasseCrses($classID, $searchIn, $searchWord)
{
    $whereClause = " and (b.course_name ilike '" . loc_db_escape_string($searchWord) .
        "' or b.course_code ilike '" . loc_db_escape_string($searchWord) . "')";
    $strSql = "SELECT count(1) " .
        "FROM aca.aca_classes_n_thr_crses a, aca.aca_courses b " .
        "WHERE a.course_id=b.course_id and a.clss_crse_id = " . $classID . $whereClause . "";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return 0;
}

function get_AcaClasseSbjcts($classID, $courseID, $offset, $limit_size, $searchIn, $searchWord)
{
    $whereClause = " and (b.subject_name ilike '" . loc_db_escape_string($searchWord) .
        "' or b.subject_code ilike '" . loc_db_escape_string($searchWord) . "')";
    $strSql = "SELECT a.crse_sbjct_id, a.course_id, a.subject_id, a.class_id, a.core_or_elective, a.weight_or_credit_hrs, "
        . "b.subject_code, b.subject_name, a.is_enabled, "
        . "REPLACE(b.subject_code || ' (' || b.subject_name || ')', ' (' || b.subject_code || ')', '') subject_name, "
        . "a.period_type, a.period_number, 
                aca.get_pos_hldr_prs_nm(-1, c.lnkd_div_id, -1, a.subject_id, c.sbjct_fcltr_pos_name) " .
        "FROM aca.aca_crsrs_n_thr_sbjcts a, aca.aca_subjects b, aca.aca_classes c " .
        "WHERE a.subject_id = b.subject_id and a.class_id=c.class_id and a.course_id = " . $courseID .
        " and a.class_id = " . $classID . $whereClause .
        " ORDER BY a.core_or_elective, b.subject_name LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    //echo $strSql
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Ttl_AcaClasseSbjcts($classID, $courseID, $searchIn, $searchWord)
{
    $whereClause = " and (b.subject_name ilike '" . loc_db_escape_string($searchWord) .
        "' or b.subject_code ilike '" . loc_db_escape_string($searchWord) . "')";
    $strSql = "SELECT count(1) " .
        "FROM aca.aca_crsrs_n_thr_sbjcts a, aca.aca_subjects b  " .
        "WHERE a.subject_id = b.subject_id and a.course_id = " . $courseID .
        " and a.class_id = " . $classID . $whereClause . "";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return 0;
}

function getNew_AcaClasseCrsesID()
{
    $strSql = "select nextval('aca.aca_classes_n_thr_crses_clss_crse_id_seq')";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function get_AcaClasseCrseID($courseID, $classid)
{
    $strSql = "select clss_crse_id from aca.aca_classes_n_thr_crses a "
        . "where a.course_id = " . $courseID . " and class_id = " . $classid;
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function create_AcaClasseCrses($clscrsID, $class_id, $courseID, $isEnbld, $minWeight, $maxWeight)
{
    global $usrID;
    $insSQL = "INSERT INTO aca.aca_classes_n_thr_crses(
	clss_crse_id, class_id, course_id, is_enabled, created_by, creation_date, 
        last_update_by, last_update_date, min_weight_crdt_hrs, max_weight_crdt_hrs) " .
        "VALUES (" . $clscrsID .
        ", " . $class_id .
        ", " . $courseID .
        ", '" . cnvrtBoolToBitStr($isEnbld) .
        "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $minWeight . ", " . $maxWeight . ")";
    return execUpdtInsSQL($insSQL);
}

function update_AcaClasseCrses($clscrsID, $class_id, $courseID, $isEnbld, $minWeight, $maxWeight)
{
    global $usrID;
    $updtSQL = "UPDATE aca.aca_classes_n_thr_crses SET " .
        "class_id=" . $class_id .
        ", course_id=" . $courseID .
        ", is_enabled='" . cnvrtBoolToBitStr($isEnbld) .
        "', min_weight_crdt_hrs = " . $minWeight .
        ", max_weight_crdt_hrs = " . $maxWeight .
        ", last_update_by = " . $usrID .
        ", last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (clss_crse_id = " . $clscrsID . ")";
    return execUpdtInsSQL($updtSQL);
}

function delete_AcaClasseCrses($pKeyID, $pkeyDesc)
{
    $delSQL3 = "DELETE FROM aca.aca_crsrs_n_thr_sbjcts WHERE class_id||'.'||course_id IN "
        . "(select y.class_id||'.'||y.course_id from aca.aca_classes_n_thr_crses y where y.clss_crse_id = " . $pKeyID . ")";
    $affctd3 = execUpdtInsSQL($delSQL3, $pkeyDesc);
    $delSQL = "DELETE FROM aca.aca_classes_n_thr_crses WHERE clss_crse_id = " . $pKeyID . "";
    $affctd1 = execUpdtInsSQL($delSQL, $pkeyDesc);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Class Programme/Objective(s)!";
        $dsply .= "<br/>Deleted $affctd3 Subject/Target(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getNew_AcaClasseSbjctID()
{
    $strSql = "select nextval('aca.crsrs_n_thr_sbjts_crse_sbjct_id_seq')";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function get_AcaClasseSbjctID($sbjctID, $courseID, $classid)
{
    $strSql = "select crse_sbjct_id from aca.aca_crsrs_n_thr_sbjcts a "
        . "where a.subject_id = " . $sbjctID . " and a.course_id = " . $courseID . " and a.class_id = " . $classid;
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function create_AcaClasseSbjcts($clssbjctID, $course_id, $sbjctID, $class_id, $coreElective, $weightCredit, $isEnbld, $crseSbjct_PeriodType, $crseSbjct_PeriodNum)
{
    global $usrID;
    $insSQL = "INSERT INTO aca.aca_crsrs_n_thr_sbjcts(
	crse_sbjct_id, course_id, subject_id, class_id, core_or_elective, 
        is_enabled, weight_or_credit_hrs, created_by, creation_date, last_update_by, last_update_date, period_type, period_number) " .
        "VALUES (" . $clssbjctID .
        ", " . $course_id .
        ", " . $sbjctID .
        ", " . $class_id .
        ", '" . loc_db_escape_string($coreElective) .
        "', '" . cnvrtBoolToBitStr($isEnbld) .
        "', " . $weightCredit .
        ", " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($crseSbjct_PeriodType) .
        "', " . $crseSbjct_PeriodNum .
        ")";
    return execUpdtInsSQL($insSQL);
}

function update_AcaClasseSbjcts($clssbjctID, $course_id, $sbjctID, $class_id, $coreElective, $weightCredit, $isEnbld, $crseSbjct_PeriodType, $crseSbjct_PeriodNum)
{
    global $usrID;
    $updtSQL = "UPDATE aca.aca_crsrs_n_thr_sbjcts SET " .
        "class_id=" . $class_id .
        ", course_id=" . $course_id .
        ", subject_id=" . $sbjctID .
        ", is_enabled='" . cnvrtBoolToBitStr($isEnbld) .
        "', core_or_elective='" . loc_db_escape_string($coreElective) .
        "', weight_or_credit_hrs=" . $weightCredit .
        ", period_type='" . loc_db_escape_string($crseSbjct_PeriodType) .
        "', period_number=" . $crseSbjct_PeriodNum .
        ", last_update_by = " . $usrID .
        ", last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (crse_sbjct_id =" . $clssbjctID . ")";
    return execUpdtInsSQL($updtSQL);
}

function delete_AcaClasseSbjcts($pKeyID, $pkeyDesc)
{
    $delSQL = "DELETE FROM aca.aca_crsrs_n_thr_sbjcts WHERE crse_sbjct_id = " . $pKeyID . "";
    $affctd1 = execUpdtInsSQL($delSQL, $pkeyDesc);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Subject(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

//TASK ASSIGNMENTS
function get_AcaSttngsClasses($acaPrsnID, $offset, $limit_size, $searchIn, $searchWord, $qShwCrntOnly)
{
    $whereClause = " and (b.class_name ilike '" . loc_db_escape_string($searchWord) .
        "' or aca.get_coursenm(a.course_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    if ($qShwCrntOnly === true) {
        $whereClause .= " and a.acdmc_period_id IN (Select y.acdmc_period_id from aca.aca_prsns_acdmc_sttngs y,
        aca.aca_assessment_periods z where z.assmnt_period_id = y.acdmc_period_id 
        and y.person_id = " . $acaPrsnID . " ORDER BY z.period_start_date DESC LIMIT 1 OFFSET 0)";
        /* $whereClause .= " and now() between to_timestamp(c.period_start_date,'YYYY-MM-DD 00:00:00') "
          . "and to_timestamp(c.period_end_date,'YYYY-MM-DD 23:59:59')"; */
    }
    $strSql = "SELECT a.acdmc_sttngs_id, a.class_id, a.course_id, aca.get_coursenm(a.course_id), "
        . "b.class_name, a.acdmc_period_id, c.assmnt_period_name, c.period_start_date, c.period_end_date " .
        "FROM aca.aca_prsns_acdmc_sttngs a, aca.aca_classes b, aca.aca_assessment_periods c " .
        "WHERE a.class_id = b.class_id and c.assmnt_period_id = a.acdmc_period_id 
            and a.person_id = " . $acaPrsnID . $whereClause .
        " ORDER BY c.period_start_date DESC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AcaSttngsClasses2($acaSttngsID, $offset, $limit_size, $searchIn, $searchWord)
{
    global $prsnid;
    global $canViewAsgnmnt;
    $whereClause = " and (b.class_name ilike '" . loc_db_escape_string($searchWord) .
        "' or aca.get_coursenm(a.course_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    if ($canViewAsgnmnt === false) {
        $whereClause .= " and a.person_id = " . $prsnid;
    }
    $strSql = "SELECT a.acdmc_sttngs_id, a.class_id, a.course_id, aca.get_coursenm(a.course_id), "
        . "b.class_name, a.acdmc_period_id, c.assmnt_period_name, c.period_start_date, c.period_end_date " .
        "FROM aca.aca_prsns_acdmc_sttngs a, aca.aca_classes b, aca.aca_assessment_periods c " .
        "WHERE a.class_id = b.class_id and c.assmnt_period_id = a.acdmc_period_id 
         and a.acdmc_sttngs_id = " . $acaSttngsID . $whereClause .
        " ORDER BY c.period_start_date DESC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AcaSttngsExprt($offset, $limit_size, $searchIn, $searchWord, $qShwCrntOnly)
{
    $hdngs = array(
        "ID No.**", "Person Full Name", "Group Name**", "Programme/Objective Code**", "Programme/Objective Name",
        "Assessment Period Name**", "Subject/Target Code", "Subject/Target Name", "Core/Elective", "Weight/Credit Hours"
    );
    $strSql = "";
    $extrWhere = "";
    $whrcls = " and (aca.get_class_nm(a.class_id) ilike '" . loc_db_escape_string($searchWord) .
        "' or aca.get_coursenm(a.course_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    if ($qShwCrntOnly === true) {
        $whrcls .= " and a.acdmc_period_id IN (Select y.acdmc_period_id from aca.aca_prsns_acdmc_sttngs y,
            aca.aca_assessment_periods z where z.assmnt_period_id = y.acdmc_period_id 
            and y.person_id = a.person_id ORDER BY z.period_start_date DESC LIMIT 1 OFFSET 0)";
        /* $whereClause .= " and now() between to_timestamp(c.period_start_date,'YYYY-MM-DD 00:00:00') "
              . "and to_timestamp(d.period_end_date,'YYYY-MM-DD 23:59:59')"; */
    }
    $strSql = "SELECT '''' || prs.get_prsn_loc_id(a.person_id), prs.get_prsn_name(a.person_id), aca.get_class_nm(a.class_id),
        aca.get_coursecode(a.course_id), aca.get_coursenm(a.course_id), d.assmnt_period_name,
        aca.get_subjectcode(b.subject_id), aca.get_subjectnm(b.subject_id), c.core_or_elective, c.weight_or_credit_hrs 
  FROM aca.aca_prsns_acdmc_sttngs a, aca.aca_prsns_ac_sttngs_sbjcts b, 
  aca.aca_crsrs_n_thr_sbjcts c, aca.aca_assessment_periods d " .
        "WHERE(a.acdmc_sttngs_id = b.acdmc_sttngs_id and a.class_id=c.class_id "
        . "and b.subject_id=c.subject_id and a.course_id=c.course_id and d.assmnt_period_id = a.acdmc_period_id " . $whrcls . $extrWhere .
        ") ORDER BY prs.get_prsn_loc_id(a.person_id) LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AcaSttngsSbjcts($sttngID, $offset, $limit_size, $searchIn, $searchWord)
{
    $whereClause = " and (aca.get_subjectnm(a.subject_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    $strSql = "SELECT a.ac_sttngs_sbjcts_id, a.subject_id, c.core_or_elective, c.weight_or_credit_hrs, "
        . "aca.get_subjectnm(a.subject_id) subject_name " .
        "FROM aca.aca_prsns_ac_sttngs_sbjcts a, aca.aca_prsns_acdmc_sttngs b, aca.aca_crsrs_n_thr_sbjcts c, aca.aca_assessment_periods d " .
        "WHERE a.acdmc_sttngs_id = b.acdmc_sttngs_id and b.class_id = c.class_id "
        . "and b.course_id = c.course_id and a.subject_id=c.subject_id "
        . "and b.acdmc_period_id=d.assmnt_period_id "
        //. "and (d.period_type = c.period_type or coalesce(c.period_type,'') = '') "
        //. "and (d.period_number = c.period_number or coalesce(c.period_number,1) = 1) "
        . "and a.acdmc_sttngs_id = " . $sttngID .
        " " . $whereClause . " ORDER BY aca.get_subjectnm(a.subject_id) LIMIT " . $limit_size . " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_TtlAcaSttngsSbjcts($sttngID, $searchIn, $searchWord)
{
    $whereClause = " and (aca.get_subjectnm(a.subject_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    $strSql = "SELECT count(1) " .
        "FROM aca.aca_prsns_ac_sttngs_sbjcts a, 
            aca.aca_prsns_acdmc_sttngs b, 
            aca.aca_crsrs_n_thr_sbjcts c, 
            aca.aca_assessment_periods d " .
        "WHERE a.acdmc_sttngs_id = b.acdmc_sttngs_id 
               and b.class_id = c.class_id "
        . "and b.course_id = c.course_id and a.subject_id=c.subject_id "
        . "and b.acdmc_period_id=d.assmnt_period_id "
        . "and (d.period_type = c.period_type or coalesce(c.period_type,'') = '') "
        . "and (d.period_number = c.period_number or coalesce(c.period_number,1) = 1) "
        . "and a.acdmc_sttngs_id = " . $sttngID . " " . $whereClause;
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return 0;
}

function getNew_AcaRgstratnID()
{
    $strSql = "select nextval('aca.aca_prsns_acdmc_sttngs_acdmc_sttngs_id_seq')";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function get_AcaRgstratnID($in_prsnID, $classid, $courseID, $periodID)
{
    $strSql = "select acdmc_sttngs_id from aca.aca_prsns_acdmc_sttngs a "
        . "where a.course_id = " . $courseID .
        " and class_id = " . $classid .
        " and person_id = " . $in_prsnID .
        " and acdmc_period_id = " . $periodID;
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function create_AcaRgstratn($sttngsID, $in_prsnID, $classid, $courseID, $periodID)
{
    global $usrID;
    $insSQL = "INSERT INTO aca.aca_prsns_acdmc_sttngs(
	acdmc_sttngs_id, person_id, class_id, acdmc_period_id, course_id, created_by, creation_date, 
        last_update_by, last_update_date) " .
        "VALUES (" . $sttngsID .
        ", " . $in_prsnID .
        ", " . $classid .
        ", " . $periodID .
        ", " . $courseID .
        ", " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'))";
    return execUpdtInsSQL($insSQL);
}

function update_AcaRgstratn($sttngsID, $in_prsnID, $classid, $courseID, $periodID)
{
    global $usrID;
    $updtSQL = "UPDATE aca.aca_prsns_acdmc_sttngs SET " .
        "class_id=" . $classid .
        ", course_id=" . $courseID .
        ", person_id = " . $in_prsnID .
        ", acdmc_period_id = " . $periodID .
        ", last_update_by = " . $usrID .
        ", last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (acdmc_sttngs_id = " . $sttngsID . ")";
    return execUpdtInsSQL($updtSQL);
}

function delete_AcaRgstratn($pKeyID, $pkeyDesc)
{
    $selSQL1 = "Select count(1) from aca.aca_assmnt_col_vals 
    where acdmc_sttngs_id = " . $pKeyID . "";
    $result1 = executeSQLNoParams($selSQL1);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if (($trnsCnt1) > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete Registrations used in Score Cards!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $acaRgstrClassID = (int)getGnrlRecNm("aca.aca_prsns_acdmc_sttngs", "acdmc_sttngs_id", "class_id", $pKeyID);
    $sbmtdRgstrPersonID = (int)getGnrlRecNm("aca.aca_prsns_acdmc_sttngs", "acdmc_sttngs_id", "person_id", $pKeyID);
    $pDivGrpDivID = (int)getGnrlRecNm("aca.aca_classes", "class_id", "lnkd_div_id", $acaRgstrClassID);
    $oldDivGrpPKey = getPDivGrpID($sbmtdRgstrPersonID, $pDivGrpDivID);
    $delSQL = "DELETE FROM aca.aca_prsns_ac_sttngs_sbjcts WHERE acdmc_sttngs_id = " . $pKeyID . "";
    $affctd0 = execUpdtInsSQL($delSQL, $pkeyDesc);
    $delSQL = "DELETE FROM aca.aca_prsns_acdmc_sttngs WHERE acdmc_sttngs_id = " . $pKeyID . "";
    $affctd1 = execUpdtInsSQL($delSQL, $pkeyDesc);
    if ($oldDivGrpPKey > 0) {
        deletePDivGrp($oldDivGrpPKey);
    }
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Assignment(s)!";
        $dsply .= "<br/>Deleted $affctd0 Detail(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getNew_AcaRgstratnbjctID()
{
    $strSql = "select nextval('aca.aca_prsns_ac_sttngs_sbjcts_ac_sttngs_sbjcts_id_seq')";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function get_AcaRgstratnbjctID($sbjctID, $sttngsID)
{
    $strSql = "select ac_sttngs_sbjcts_id from aca.aca_prsns_ac_sttngs_sbjcts a "
        . "where a.subject_id = " . $sbjctID . " and a.acdmc_sttngs_id = " . $sttngsID;
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function create_AcaRgstratnbjcts($sttngsSbjctID, $sttngsID, $sbjctID)
{
    global $usrID;
    $insSQL = "INSERT INTO aca.aca_prsns_ac_sttngs_sbjcts(
	ac_sttngs_sbjcts_id, acdmc_sttngs_id, subject_id, created_by, creation_date) " .
        "VALUES (" . $sttngsSbjctID .
        ", " . $sttngsID .
        ", " . $sbjctID .
        ", " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'))";
    //echo $insSQL;
    return execUpdtInsSQL($insSQL);
}

function update_AcaRgstratnbjcts($sttngsSbjctID, $sttngsID, $sbjctID)
{
    //global $usrID;
    $updtSQL = "UPDATE aca.aca_prsns_ac_sttngs_sbjcts SET " .
        "acdmc_sttngs_id=" . $sttngsID .
        ", subject_id=" . $sbjctID .
        " WHERE (ac_sttngs_sbjcts_id =" . $sttngsSbjctID . ")";
    //echo $updtSQL;
    return execUpdtInsSQL($updtSQL);
}

function delete_AcaRgstratnbjcts($pKeyID, $pkeyDesc)
{
    $selSQL1 = "Select count(1) from aca.aca_assmnt_col_vals 
    where acdmc_sttngs_id IN (Select z.acdmc_sttngs_id from aca.aca_prsns_ac_sttngs_sbjcts z 
    where z.ac_sttngs_sbjcts_id = " . $pKeyID . ")";
    $result1 = executeSQLNoParams($selSQL1);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if (($trnsCnt1) > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete Registrations used in Score Cards!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM aca.aca_prsns_ac_sttngs_sbjcts WHERE ac_sttngs_sbjcts_id = " . $pKeyID . "";
    $affctd1 = execUpdtInsSQL($delSQL, $pkeyDesc);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Subject/Task(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

//ASSESSMENT SHEETS
function get_AssessSheets($searchWord, $searchIn, $offset, $limit_size, $orgID, $shwSelfOnly, $assessTypeFltr = "Assessment Sheet Per Group")
{
    global $usrID;
    global $prsnid;
    $strSql = "";
    $whrcls = "";
    $selfOnlyCls = "";
    if ($shwSelfOnly) {
        $selfOnlyCls = " and (a.created_by = " . $usrID . " or a.tutor_person_id = " . $prsnid . ")";
    }
    if ($searchIn == "Assessment Sheet Name") {
        $whrcls = " and (a.assess_sheet_name ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Assessment Group") {
        $whrcls = " and (aca.get_class_nm(a.class_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Assessment Type") {
        $whrcls = " and (aca.get_assesstypnm(a.assessment_type_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or aca.get_assesstyp(a.assessment_type_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Programme/Objective") {
        $whrcls = " and (aca.get_coursenm(a.course_id) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Subject/Task") {
        $whrcls = " and (aca.get_subjectnm(a.subject_id) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Assessment Period") {
        $whrcls = " and (aca.get_period_nm(a.academic_period_id) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Administrator") {
        $whrcls = " and (prs.get_prsn_name(a.tutor_person_id) || ' (' || prs.get_prsn_loc_id(a.tutor_person_id)||')' ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Person Assessed") {
        $whrcls = " and (prs.get_prsn_name(a.assessed_person_id) || ' (' || prs.get_prsn_loc_id(a.assessed_person_id)||')' ilike '" . loc_db_escape_string($searchWord) . "')";
    } else {
        $whrcls = " and (prs.get_prsn_name(a.tutor_person_id) || ' (' || prs.get_prsn_loc_id(a.tutor_person_id)||')' ilike '" . loc_db_escape_string($searchWord) .
            "' or prs.get_prsn_name(a.assessed_person_id) || ' (' || prs.get_prsn_loc_id(a.assessed_person_id)||')' ilike '" . loc_db_escape_string($searchWord) .
            "' or aca.get_coursenm(a.course_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or aca.get_subjectnm(a.subject_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or aca.get_assesstypnm(a.assessment_type_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or aca.get_assesstyp(a.assessment_type_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or aca.get_class_nm(a.class_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or a.assess_sheet_name ilike '" . loc_db_escape_string($searchWord) .
            "' or aca.get_period_nm(a.academic_period_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $strSql = "SELECT a.assess_sheet_hdr_id, a.assess_sheet_name, 
                a.class_id, aca.get_class_nm(a.class_id) class_nm,
                a.assessment_type_id, aca.get_assesstypnm(a.assessment_type_id), 
                a.course_id, aca.get_coursenm(a.course_id), 
                a.subject_id, aca.get_subjectnm(a.subject_id),
                a.academic_period_id, aca.get_period_nm(a.academic_period_id),
                a.tutor_person_id, prs.get_prsn_name(a.tutor_person_id) || ' (' || prs.get_prsn_loc_id(a.tutor_person_id)||')' full_nm, 
                aca.get_assesstyp(a.assessment_type_id), aca.get_assess_prsn_cnt(a.assess_sheet_hdr_id), a.assess_sheet_status,
                a.assessed_person_id, prs.get_prsn_name(a.assessed_person_id) || ' (' || prs.get_prsn_loc_id(a.assessed_person_id)||')' stu_full_nm,
                aca.get_assess_sbjct_cnt(a.assess_sheet_hdr_id)
            FROM aca.aca_assess_sheet_hdr a 
            WHERE((a.org_id = " . $orgID . " and aca.get_assesstyp(a.assessment_type_id)='" . $assessTypeFltr . "')" . $whrcls . $selfOnlyCls .
        ") ORDER BY a.assess_sheet_hdr_id DESC LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_AssessSheets($searchWord, $searchIn, $orgID, $shwSelfOnly, $assessTypeFltr = "Assessment Sheet Per Group")
{
    global $usrID;
    global $prsnid;
    $strSql = "";
    $whrcls = "";
    $selfOnlyCls = "";
    if ($shwSelfOnly) {
        $selfOnlyCls = " and (a.created_by = " . $usrID . " or a.tutor_person_id = " . $prsnid . ")";
    }
    if ($searchIn == "Assessment Sheet Name") {
        $whrcls = " and (a.assess_sheet_name ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Assessment Group") {
        $whrcls = " and (aca.get_class_nm(a.class_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Assessment Type") {
        $whrcls = " and (aca.get_assesstypnm(a.assessment_type_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or aca.get_assesstyp(a.assessment_type_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Programme/Objective") {
        $whrcls = " and (aca.get_coursenm(a.course_id) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Subject/Task") {
        $whrcls = " and (aca.get_subjectnm(a.subject_id) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Assessment Period") {
        $whrcls = " and (aca.get_period_nm(a.academic_period_id) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Administrator") {
        $whrcls = " and (prs.get_prsn_name(a.tutor_person_id) || ' (' || prs.get_prsn_loc_id(a.tutor_person_id)||')' ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Person Assessed") {
        $whrcls = " and (prs.get_prsn_name(a.assessed_person_id) || ' (' || prs.get_prsn_loc_id(a.assessed_person_id)||')' ilike '" . loc_db_escape_string($searchWord) . "')";
    } else {
        $whrcls = " and (prs.get_prsn_name(a.tutor_person_id) || ' (' || prs.get_prsn_loc_id(a.tutor_person_id)||')' ilike '" . loc_db_escape_string($searchWord) .
            "' or prs.get_prsn_name(a.assessed_person_id) || ' (' || prs.get_prsn_loc_id(a.assessed_person_id)||')' ilike '" . loc_db_escape_string($searchWord) .
            "' or aca.get_coursenm(a.course_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or aca.get_subjectnm(a.subject_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or aca.get_assesstypnm(a.assessment_type_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or aca.get_assesstyp(a.assessment_type_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or aca.get_class_nm(a.class_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or a.assess_sheet_name ilike '" . loc_db_escape_string($searchWord) .
            "' or aca.get_period_nm(a.academic_period_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $strSql = "SELECT count(1)
            FROM aca.aca_assess_sheet_hdr a 
            WHERE((a.org_id = " . $orgID . " and aca.get_assesstyp(a.assessment_type_id)='" . $assessTypeFltr . "')" . $whrcls . $selfOnlyCls .
        ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_One_AssessSheetHdr($hdrID)
{
    $strSql = "SELECT a.assess_sheet_hdr_id, a.assess_sheet_name, 
                a.class_id, aca.get_class_nm(a.class_id) class_nm,
                a.assessment_type_id, aca.get_assesstypnm(a.assessment_type_id), 
                a.course_id, aca.get_coursenm(a.course_id), 
                a.subject_id, aca.get_subjectnm(a.subject_id),
                a.academic_period_id, aca.get_period_nm(a.academic_period_id),
                a.tutor_person_id, prs.get_prsn_name(a.tutor_person_id) || ' (' || prs.get_prsn_loc_id(a.tutor_person_id)||')' full_nm, 
                aca.get_assesstyp(a.assessment_type_id), aca.get_assess_prsn_cnt(a.assess_sheet_hdr_id), 
                a.assess_sheet_desc, a.assess_sheet_status,
                a.assessed_person_id, prs.get_prsn_name(a.assessed_person_id) || ' (' || prs.get_prsn_loc_id(a.assessed_person_id)||')' stu_full_nm 
            FROM aca.aca_assess_sheet_hdr a  " .
        "WHERE((a.assess_sheet_hdr_id = " . $hdrID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_AssessSheetHdr1($hdrID)
{
    $strSql = "SELECT a.assess_sheet_hdr_id, a.assess_sheet_name, 
                a.class_id, aca.get_class_nm(a.class_id) class_nm,
                a.assessment_type_id, aca.get_assesstypnm(a.assessment_type_id), 
                (CASE WHEN a.course_id<=0 THEN aca.get_max_courseid(a.assess_sheet_hdr_id) ELSE a.course_id END), 
                aca.get_coursenm((CASE WHEN a.course_id<=0 THEN aca.get_max_courseid(a.assess_sheet_hdr_id) ELSE a.course_id END)), 
                a.subject_id, aca.get_subjectnm(a.subject_id),
                a.academic_period_id, aca.get_period_nm(a.academic_period_id),
                a.tutor_person_id, prs.get_prsn_name(a.tutor_person_id) || ' (' || prs.get_prsn_loc_id(a.tutor_person_id)||')' full_nm, 
                aca.get_assesstyp(a.assessment_type_id), aca.get_assess_prsn_cnt(a.assess_sheet_hdr_id), 
                a.assess_sheet_desc, a.assess_sheet_status,
                a.assessed_person_id, prs.get_prsn_name(a.assessed_person_id) stu_full_nm ,
                prs.get_prsn_loc_id(a.assessed_person_id) loc_id, prs.get_prsn_gender(a.assessed_person_id) gender, 
                to_char(to_timestamp(aca.get_period_end(a.academic_period_id), 'YYYY-MM-DD'), 'DD-Mon-YYYY') prd_end, 
                to_char(to_timestamp(aca.get_next_prd_start(a.academic_period_id), 'YYYY-MM-DD'), 'DD-Mon-YYYY') prd_start
            FROM aca.aca_assess_sheet_hdr a  " .
        "WHERE((a.assess_sheet_hdr_id = " . $hdrID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AssessShtPrsnsTtl($searchFor, $searchIn, $assessShtHdrID)
{
    $strSql = "";
    $whrcls = "";
    if ($searchIn == "ID/Full Name") {
        $whrcls = " AND (y3.local_id_no ilike '" . loc_db_escape_string($searchFor) . "' or trim(y3.title || ' ' || y3.sur_name || " .
            "', ' || y3.first_name || ' ' || y3.other_names) ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Full Name") {
        $whrcls = " AND (trim(y3.title || ' ' || y3.sur_name || " .
            "', ' || y3.first_name || ' ' || y3.other_names) ilike '" . loc_db_escape_string($searchFor) . "')";
    }
    $strSql = "SELECT count(y1.ass_col_val_id) " .
        "FROM aca.aca_assmnt_col_vals y1,
     aca.aca_prsns_acdmc_sttngs y2,
     prs.prsn_names_nos y3,
     aca.aca_assess_sheet_hdr y4
WHERE y1.acdmc_sttngs_id = y2.acdmc_sttngs_id
  and y2.person_id = y3.person_id
  and y1.assess_sheet_hdr_id = y4.assess_sheet_hdr_id and y1.assess_sheet_hdr_id=" . $assessShtHdrID . $whrcls . "";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_AssessShtPrsns($searchFor, $searchIn, $offset, $limit_size, $assessShtHdrID)
{
    $strSql = "";
    $whrcls = "";
    if ($searchIn == "ID/Full Name") {
        $whrcls = " AND (y3.local_id_no ilike '" . loc_db_escape_string($searchFor) . "' or trim(y3.title || ' ' || y3.sur_name || " .
            "', ' || y3.first_name || ' ' || y3.other_names) ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Full Name") {
        $whrcls = " AND (trim(y3.title || ' ' || y3.sur_name || " .
            "', ' || y3.first_name || ' ' || y3.other_names) ilike '" . loc_db_escape_string($searchFor) . "')";
    }
    $strSql = "SELECT y1.ass_col_val_id,
       y1.acdmc_sttngs_id,
       y1.assess_sheet_hdr_id,
       '1',
       y3.person_id,
       y3.local_id_no,
       trim(y3.title || ' ' || y3.sur_name || ', ' || y3.first_name || ' ' || y3.other_names) fullname,
       y4.assessment_type_id,
       y1.data_col1,
       y1.data_col2,
       y1.data_col3,
       y1.data_col4,
       y1.data_col5,
       y1.data_col6,
       y1.data_col7,
       y1.data_col8,
       y1.data_col9,
       y1.data_col10,
       y1.data_col11,
       y1.data_col12,
       y1.data_col13,
       y1.data_col14,
       y1.data_col15,
       y1.data_col16,
       y1.data_col17,
       y1.data_col18,
       y1.data_col19,
       y1.data_col20,
       y1.data_col21,
       y1.data_col22,
       y1.data_col23,
       y1.data_col24,
       y1.data_col25,
       y1.data_col26,
       y1.data_col27,
       y1.data_col28,
       y1.data_col29,
       y1.data_col30,
       y1.data_col31,
       y1.data_col32,
       y1.data_col33,
       y1.data_col34,
       y1.data_col35,
       y1.data_col36,
       y1.data_col37,
       y1.data_col38,
       y1.data_col39,
       y1.data_col40,
       y1.data_col41,
       y1.data_col42,
       y1.data_col43,
       y1.data_col44,
       y1.data_col45,
       y1.data_col46,
       y1.data_col47,
       y1.data_col48,
       y1.data_col49,
       y1.data_col50
FROM aca.aca_assmnt_col_vals y1,
     aca.aca_prsns_acdmc_sttngs y2,
     prs.prsn_names_nos y3,
     aca.aca_assess_sheet_hdr y4
WHERE y1.acdmc_sttngs_id = y2.acdmc_sttngs_id
  and y2.person_id = y3.person_id
  and y1.assess_sheet_hdr_id = y4.assess_sheet_hdr_id and y1.assess_sheet_hdr_id=" . $assessShtHdrID . $whrcls . " ORDER BY y3.local_id_no LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_ReportCardLnsTtl($searchFor, $searchIn, $assessShtHdrID)
{
    $strSql = "";
    $whrcls = "";
    global $courseLabel;
    global $sbjctLabel;
    if ($searchIn == $courseLabel . " Name") {
        $whrcls = " AND (aca.get_coursenm(y1.course_id) ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == $sbjctLabel . " Name") {
        $whrcls = " AND (aca.get_subjectnm(y1.subject_id) ilike '" . loc_db_escape_string($searchFor) . "')";
    }
    $strSql = "SELECT count(y1.ass_col_val_id) " .
        "FROM aca.aca_assmnt_col_vals y1,
     aca.aca_prsns_acdmc_sttngs y2,
     aca.aca_assess_sheet_hdr y4
WHERE y1.acdmc_sttngs_id = y2.acdmc_sttngs_id
  and y1.assess_sheet_hdr_id = y4.assess_sheet_hdr_id 
  and y1.assess_sheet_hdr_id=" . $assessShtHdrID . $whrcls . "";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_ReportCardLns($searchFor, $searchIn, $offset, $limit_size, $assessShtHdrID)
{
    $strSql = "";
    $whrcls = "";
    global $courseLabel;
    global $sbjctLabel;
    if ($searchIn == $courseLabel . " Name") {
        $whrcls = " AND (aca.get_coursenm(y1.course_id) ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == $sbjctLabel . " Name") {
        $whrcls = " AND (aca.get_subjectnm(y1.subject_id) ilike '" . loc_db_escape_string($searchFor) . "')";
    }
    $strSql = "SELECT y1.ass_col_val_id,
       y1.acdmc_sttngs_id,
       y1.assess_sheet_hdr_id,
       '1',
       y2.person_id,
      (CASE WHEN y1.subject_id<=0 THEN aca.get_coursenm(y1.course_id) ELSE '' END),
      (CASE WHEN y1.subject_id>0 THEN aca.get_subjectnm(y1.subject_id) ELSE '' END),
       y4.assessment_type_id,
       y1.data_col1,
       y1.data_col2,
       y1.data_col3,
       y1.data_col4,
       y1.data_col5,
       y1.data_col6,
       y1.data_col7,
       y1.data_col8,
       y1.data_col9,
       y1.data_col10,
       y1.data_col11,
       y1.data_col12,
       y1.data_col13,
       y1.data_col14,
       y1.data_col15,
       y1.data_col16,
       y1.data_col17,
       y1.data_col18,
       y1.data_col19,
       y1.data_col20,
       y1.data_col21,
       y1.data_col22,
       y1.data_col23,
       y1.data_col24,
       y1.data_col25,
       y1.data_col26,
       y1.data_col27,
       y1.data_col28,
       y1.data_col29,
       y1.data_col30,
       y1.data_col31,
       y1.data_col32,
       y1.data_col33,
       y1.data_col34,
       y1.data_col35,
       y1.data_col36,
       y1.data_col37,
       y1.data_col38,
       y1.data_col39,
       y1.data_col40,
       y1.data_col41,
       y1.data_col42,
       y1.data_col43,
       y1.data_col44,
       y1.data_col45,
       y1.data_col46,
       y1.data_col47,
       y1.data_col48,
       y1.data_col49,
       y1.data_col50
FROM aca.aca_assmnt_col_vals y1,
     aca.aca_prsns_acdmc_sttngs y2,
     aca.aca_assess_sheet_hdr y4
WHERE y1.acdmc_sttngs_id = y2.acdmc_sttngs_id
  and y1.assess_sheet_hdr_id = y4.assess_sheet_hdr_id 
  and y1.assess_sheet_hdr_id=" . $assessShtHdrID . $whrcls .
        " ORDER BY aca.get_coursenm(y1.course_id), aca.get_subjectnm(y1.subject_id)  LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_ReportCardLns1($searchFor, $searchIn, $offset, $limit_size, $assessShtHdrID)
{
    $strSql = "";
    $whrcls = "";
    global $courseLabel;
    global $sbjctLabel;
    if ($searchIn == $courseLabel . " Name") {
        $whrcls = " AND (aca.get_coursenm(y1.course_id) ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == $sbjctLabel . " Name") {
        $whrcls = " AND (aca.get_subjectnm(y1.subject_id) ilike '" . loc_db_escape_string($searchFor) . "')";
    }
    $strSql = "SELECT y1.ass_col_val_id,
       y1.acdmc_sttngs_id,
       y1.assess_sheet_hdr_id,
       '1',
       y2.person_id,
      (CASE WHEN y1.subject_id<=0 THEN aca.get_coursenm(y1.course_id) ELSE '' END),
      (CASE WHEN y1.subject_id>0 THEN aca.get_subjectnm(y1.subject_id) ELSE '' END),
       y4.assessment_type_id,
       y1.data_col1,
       y1.data_col2,
       y1.data_col3,
       y1.data_col4,
       y1.data_col5,
       y1.data_col6,
       y1.data_col7,
       y1.data_col8,
       y1.data_col9,
       y1.data_col10,
       y1.data_col11,
       y1.data_col12,
       y1.data_col13,
       y1.data_col14,
       y1.data_col15,
       y1.data_col16,
       y1.data_col17,
       y1.data_col18,
       y1.data_col19,
       y1.data_col20,
       y1.data_col21,
       y1.data_col22,
       y1.data_col23,
       y1.data_col24,
       y1.data_col25,
       y1.data_col26,
       y1.data_col27,
       y1.data_col28,
       y1.data_col29,
       y1.data_col30,
       y1.data_col31,
       y1.data_col32,
       y1.data_col33,
       y1.data_col34,
       y1.data_col35,
       y1.data_col36,
       y1.data_col37,
       y1.data_col38,
       y1.data_col39,
       y1.data_col40,
       y1.data_col41,
       y1.data_col42,
       y1.data_col43,
       y1.data_col44,
       y1.data_col45,
       y1.data_col46,
       y1.data_col47,
       y1.data_col48,
       y1.data_col49,
       y1.data_col50
FROM aca.aca_assmnt_col_vals y1,
     aca.aca_prsns_acdmc_sttngs y2,
     aca.aca_assess_sheet_hdr y4
WHERE y1.acdmc_sttngs_id = y2.acdmc_sttngs_id
  and y1.assess_sheet_hdr_id = y4.assess_sheet_hdr_id 
  and y1.subject_id>0
  and y1.assess_sheet_hdr_id=" . $assessShtHdrID . $whrcls .
        " ORDER BY aca.get_coursenm(y1.course_id), aca.get_subjectnm(y1.subject_id)  LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AssessShtGrpCols($grpnm, $assesstyp_ID)
{
    $strSql = "SELECT column_id, column_name, column_header_text, '', 
                data_type, section_located, data_length, '', 
                assmnt_typ_id, 0, column_name, '','0' is_required, is_formula_column, column_formular, column_no,
                col_min_val, col_max_val, is_dsplyd, html_css_style, lov_name
            FROM aca.aca_assessment_columns 
            WHERE section_located = '" . loc_db_escape_string($grpnm) .
        "' and assmnt_typ_id = " . $assesstyp_ID .
        " ORDER BY column_name, column_id";
    //logSessionErrs($strSql);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AssessShtColVal($pkID, $sttngID, $colNum)
{
    $colNms = array(
        "data_col1", "data_col2", "data_col3", "data_col4",
        "data_col5", "data_col6", "data_col7", "data_col8", "data_col9", "data_col10",
        "data_col11", "data_col12", "data_col13", "data_col14", "data_col15", "data_col16",
        "data_col17", "data_col18", "data_col19", "data_col20", "data_col21", "data_col22",
        "data_col23", "data_col24", "data_col25", "data_col26", "data_col27", "data_col28",
        "data_col29", "data_col30", "data_col31", "data_col32", "data_col33", "data_col34",
        "data_col35", "data_col36", "data_col37", "data_col38", "data_col39", "data_col40",
        "data_col41", "data_col42", "data_col43", "data_col44", "data_col45", "data_col46",
        "data_col47", "data_col48", "data_col49", "data_col50"
    );

    $strSql = "SELECT " . $colNms[$colNum - 1] . " 
        FROM aca.aca_assmnt_col_vals a WHERE ((assess_sheet_hdr_id = " . $pkID . " and acdmc_sttngs_id = " . $sttngID . "))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
    //aca.get_col_data_type(" . $pkID . ", " . $colNum . ")='Number
}

function get_AssessShtColValOLD($pkID, $sttngID, $columnID)
{
    $strSql = "SELECT (CASE WHEN is_val_a_number='1' THEN ''|| public.chartonumeric(col_value_2) ELSE col_value_2 END)
        FROM aca.aca_prsn_assmnt_col_vals a WHERE ((assess_sheet_hdr_id = " . $pkID .
        " and acdmc_sttngs_id = " . $sttngID .
        " and column_id = " . $columnID . "))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getNew_AssessShtHdrID()
{
    $strSql = "select nextval('aca.aca_assess_sheet_hdr_assess_sheet_hdr_id_seq')";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function get_AssessShtHdrID($assessShtName, $orgid)
{
    $strSql = "select assess_sheet_hdr_id from aca.aca_assess_sheet_hdr where lower(assess_sheet_name) = lower('"
        . loc_db_escape_string($assessShtName) . "') and org_id = " . $orgid;
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function createAssessShtHdr(
    $assessShtHdrID,
    $assessShtHdrNm,
    $assessShtHdrDesc,
    $assessShtClassID,
    $assessShtTypID,
    $assessShtCrseID,
    $assessShtSbjctID,
    $assessShtTutorID,
    $assessShtPrdID,
    $orgid,
    $assessShtHdrStatus,
    $assessedPrsnID
) {
    global $usrID;
    $insSQL = "INSERT INTO aca.aca_assess_sheet_hdr(
	assess_sheet_hdr_id, assess_sheet_name, class_id, assessment_type_id, course_id, subject_id, tutor_person_id, 
        created_by, creation_date, last_update_by, last_update_date, academic_period_id, org_id, 
        assess_sheet_desc, assess_sheet_status, assessed_person_id)
	  VALUES (" . $assessShtHdrID .
        ", '" . loc_db_escape_string($assessShtHdrNm) .
        "', " . $assessShtClassID .
        ", " . $assessShtTypID .
        ", " . $assessShtCrseID .
        ", " . $assessShtSbjctID .
        ", " . $assessShtTutorID .
        ", " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $assessShtPrdID .
        ", " . $orgid . ", '" . loc_db_escape_string($assessShtHdrDesc) .
        "', '" . loc_db_escape_string($assessShtHdrStatus) .
        "', " . $assessedPrsnID . ")";
    $afctd = execUpdtInsSQL($insSQL);
    //$assessTypeID = (int) getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assessment_type_id", $assessShtHdrID);
    $assessSheetType = getGnrlRecNm("aca.aca_assessment_types", "assmnt_typ_id", "assmnt_type", $assessShtTypID);
    if ($assessSheetType == "Assessment Sheet Per Group") {
        autoCreateSheetRecords($assessShtHdrID);
        autoCreateSheetHdrFtrRecs($assessShtHdrID);
        autoCleanUpSheetRecords($assessShtHdrID);
    } else if ($assessSheetType == "Summary Report Per Person") {

        autoCreateRptCrdRecords($assessShtHdrID, $assessShtCrseID, $assessShtSbjctID);
        autoCreateRptCrdHdrFtrRecs($assessShtHdrID);
        autoCleanUpRptCrdRecords($assessShtHdrID, $assessShtCrseID, $assessShtSbjctID);
    }
    return $afctd;
}

function updtAssessShtHdr(
    $assessShtHdrID,
    $assessShtHdrNm,
    $assessShtHdrDesc,
    $assessShtClassID,
    $assessShtTypID,
    $assessShtCrseID,
    $assessShtSbjctID,
    $assessShtTutorID,
    $assessShtPrdID,
    $orgid,
    $assessShtHdrStatus,
    $assessedPrsnID
) {
    global $usrID;
    $insSQL = "UPDATE aca.aca_assess_sheet_hdr
            SET assess_sheet_name = '" . loc_db_escape_string($assessShtHdrNm) .
        "', assess_sheet_desc='" . loc_db_escape_string($assessShtHdrDesc) .
        "', class_id=" . $assessShtClassID .
        ", assessment_type_id=" . $assessShtTypID .
        ", course_id=" . $assessShtCrseID .
        ", subject_id=" . $assessShtSbjctID .
        ", tutor_person_id=" . $assessShtTutorID .
        ", academic_period_id=" . $assessShtPrdID .
        ", org_id=" . $orgid .
        ", last_update_by = " . $usrID .
        ", last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS'), "
        . "assess_sheet_status='" . loc_db_escape_string($assessShtHdrStatus) .
        "', assessed_person_id=" . $assessedPrsnID .
        " WHERE assess_sheet_hdr_id = " . $assessShtHdrID;
    $afctd = execUpdtInsSQL($insSQL);
    $assessTypeID = (int) getGnrlRecNm("aca.aca_assess_sheet_hdr", "assess_sheet_hdr_id", "assessment_type_id", $assessShtHdrID);
    $assessSheetType = getGnrlRecNm("aca.aca_assessment_types", "assmnt_typ_id", "assmnt_type", $assessTypeID);
    if ($assessSheetType == "Assessment Sheet Per Group") {
        autoCreateSheetRecords($assessShtHdrID);
        autoCreateSheetHdrFtrRecs($assessShtHdrID);
        autoCleanUpSheetRecords($assessShtHdrID);
    } else if ($assessSheetType == "Summary Report Per Person") {

        autoCreateRptCrdRecords($assessShtHdrID, $assessShtCrseID, $assessShtSbjctID);
        autoCreateRptCrdHdrFtrRecs($assessShtHdrID);
        autoCleanUpRptCrdRecords($assessShtHdrID, $assessShtCrseID, $assessShtSbjctID);
    }
    return $afctd;
}

function deleteAssessShtHdr($valLnid, $docNum)
{
    $trnsCnt1 = 0;
    if (($trnsCnt1) > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete an Assessment Sheet in use!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }

    $delSQL = "DELETE FROM aca.aca_assmnt_col_vals WHERE assess_sheet_hdr_id = " . $valLnid;
    $affctd1 = execUpdtInsSQL($delSQL, $docNum);
    $delSQL = "DELETE FROM aca.aca_assess_sheet_hdr WHERE assess_sheet_hdr_id = " . $valLnid;
    $affctd4 = execUpdtInsSQL($delSQL, $docNum);

    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd4 Assessment Sheet(s)!";
        $dsply .= "<br/>Deleted $affctd1 Sheet Record(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function autoCreateSheetRecords($assessShtHdrID)
{
    global $usrID;
    //Insert into aca.aca_assmnt_col_vals all the persons that meet the header criteria 
    $insSQL = "INSERT INTO aca.aca_assmnt_col_vals(acdmc_sttngs_id, assess_sheet_hdr_id, data_col1,
                                    data_col2, data_col3, data_col4, data_col5, data_col6, data_col7, data_col8,
                                    data_col9, data_col10, data_col11, data_col12, data_col13, data_col14, data_col15,
                                    data_col16, data_col17, data_col18, data_col19, data_col20, data_col21, data_col22,
                                    data_col23, data_col24, data_col25, data_col26, data_col27, data_col28, data_col29,
                                    data_col30, data_col31, data_col32, data_col33, data_col34, data_col35, data_col36,
                                    data_col37, data_col38, data_col39, data_col40, data_col41, data_col42, data_col43,
                                    data_col44, data_col45, data_col46, data_col47, data_col48, data_col49, data_col50,
                                    created_by, creation_date, last_update_by, last_update_date)
                    select tbl1.acdmc_sttngs_id
                         , tbl1.assess_sheet_hdr_id
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , '', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS') 
                    from (SELECT a.assess_sheet_hdr_id,
                                    a.assess_sheet_name,
                                    a.assess_sheet_desc,
                                    b.acdmc_sttngs_id,
                                    b.person_id,
                                    a.class_id,
                                    a.assessment_type_id,
                                    a.course_id,
                                    a.subject_id,
                                    a.tutor_person_id,
                                    a.academic_period_id,
                                    a.org_id
                             FROM aca.aca_assess_sheet_hdr a,
                                  aca.aca_prsns_acdmc_sttngs b,
                                  aca.aca_prsns_ac_sttngs_sbjcts c
                             where a.class_id = b.class_id
                               and a.academic_period_id = b.acdmc_period_id
                               and a.course_id = b.course_id
                               and c.acdmc_sttngs_id = b.acdmc_sttngs_id
                               and c.subject_id = a.subject_id
                               and a.assess_sheet_hdr_id= " . $assessShtHdrID . ") tbl1
                               where tbl1.acdmc_sttngs_id NOT IN 
                               (Select z.acdmc_sttngs_id from aca.aca_assmnt_col_vals z where z.assess_sheet_hdr_id=tbl1.assess_sheet_hdr_id)";
    return execUpdtInsSQL($insSQL);
}

function autoCreateSheetHdrFtrRecs($assessShtHdrID)
{
    global $usrID;
    //Insert into aca.aca_assmnt_col_vals all the persons that meet the header criteria 
    $insSQL = "INSERT INTO aca.aca_assmnt_col_vals(acdmc_sttngs_id, assess_sheet_hdr_id, data_col1,
                                    data_col2, data_col3, data_col4, data_col5, data_col6, data_col7, data_col8,
                                    data_col9, data_col10, data_col11, data_col12, data_col13, data_col14, data_col15,
                                    data_col16, data_col17, data_col18, data_col19, data_col20, data_col21, data_col22,
                                    data_col23, data_col24, data_col25, data_col26, data_col27, data_col28, data_col29,
                                    data_col30, data_col31, data_col32, data_col33, data_col34, data_col35, data_col36,
                                    data_col37, data_col38, data_col39, data_col40, data_col41, data_col42, data_col43,
                                    data_col44, data_col45, data_col46, data_col47, data_col48, data_col49, data_col50,
                                    created_by, creation_date, last_update_by, last_update_date)
                    select -1
                         , tbl1.assess_sheet_hdr_id
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , '', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS') 
                    from (SELECT a.assess_sheet_hdr_id
                             FROM aca.aca_assess_sheet_hdr a
                             where a.assess_sheet_hdr_id= " . $assessShtHdrID . ") tbl1
                               where -1 NOT IN 
                               (Select z.acdmc_sttngs_id from aca.aca_assmnt_col_vals z where z.assess_sheet_hdr_id=tbl1.assess_sheet_hdr_id)";
    return execUpdtInsSQL($insSQL);
}

function autoCleanUpSheetRecords($assessShtHdrID)
{
    $insSQL = "DELETE FROM aca.aca_assmnt_col_vals
        WHERE acdmc_sttngs_id>0 
        and assess_sheet_hdr_id= " . $assessShtHdrID .
        " and acdmc_sttngs_id NOT IN (
                    select tbl1.acdmc_sttngs_id
                    from (SELECT a.assess_sheet_hdr_id,
                                    a.assess_sheet_name,
                                    a.assess_sheet_desc,
                                    b.acdmc_sttngs_id,
                                    b.person_id,
                                    a.class_id,
                                    a.assessment_type_id,
                                    a.course_id,
                                    a.subject_id,
                                    a.tutor_person_id,
                                    a.academic_period_id,
                                    a.org_id
                             FROM aca.aca_assess_sheet_hdr a,
                                  aca.aca_prsns_acdmc_sttngs b,
                                  aca.aca_prsns_ac_sttngs_sbjcts c
                             where a.class_id = b.class_id
                               and a.academic_period_id = b.acdmc_period_id
                               and a.course_id = b.course_id
                               and c.acdmc_sttngs_id = b.acdmc_sttngs_id
                               and c.subject_id = a.subject_id
                               and a.assess_sheet_hdr_id= " . $assessShtHdrID . ") tbl1)";
    return execUpdtInsSQL($insSQL);
}

function autoCreateRptCrdRecords($assessShtHdrID, $assessShtCrseID, $assessShtSbjctID)
{
    global $usrID;
    //Insert into aca.aca_assmnt_col_vals all the persons that meet the header criteria 
    $insSQL1 = "INSERT INTO aca.aca_assmnt_col_vals(acdmc_sttngs_id, assess_sheet_hdr_id, data_col1,
                                    data_col2, data_col3, data_col4, data_col5, data_col6, data_col7, data_col8,
                                    data_col9, data_col10, data_col11, data_col12, data_col13, data_col14, data_col15,
                                    data_col16, data_col17, data_col18, data_col19, data_col20, data_col21, data_col22,
                                    data_col23, data_col24, data_col25, data_col26, data_col27, data_col28, data_col29,
                                    data_col30, data_col31, data_col32, data_col33, data_col34, data_col35, data_col36,
                                    data_col37, data_col38, data_col39, data_col40, data_col41, data_col42, data_col43,
                                    data_col44, data_col45, data_col46, data_col47, data_col48, data_col49, data_col50,
                                    created_by, creation_date, last_update_by, last_update_date, course_id, subject_id)
                    select distinct tbl1.acdmc_sttngs_id
                         , tbl1.assess_sheet_hdr_id
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , '', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), tbl1.course_id, -1 
                    from (SELECT a.assess_sheet_hdr_id,
                                    a.assess_sheet_name,
                                    a.assess_sheet_desc,
                                    b.acdmc_sttngs_id,
                                    b.person_id,
                                    a.class_id,
                                    a.assessment_type_id,
                                    b.course_id,
                                    c.subject_id,
                                    a.tutor_person_id,
                                    a.academic_period_id,
                                    a.org_id
                             FROM aca.aca_assess_sheet_hdr a,
                                  aca.aca_prsns_acdmc_sttngs b,
                                  aca.aca_prsns_ac_sttngs_sbjcts c
                             where a.class_id = b.class_id
                               and a.academic_period_id = b.acdmc_period_id
                               and (a.course_id = b.course_id or a.course_id<=0)
                               and (b.course_id = " . $assessShtCrseID . " or " . $assessShtCrseID . " <=0)
                               and c.acdmc_sttngs_id = b.acdmc_sttngs_id
                               and (c.subject_id = a.subject_id or a.subject_id<=0)
                               and (c.subject_id = " . $assessShtSbjctID . " or " . $assessShtSbjctID . " <=0)
                               and a.assessed_person_id = b.person_id
                               and a.assess_sheet_hdr_id= " . $assessShtHdrID . ") tbl1
                               where tbl1.acdmc_sttngs_id||'_'||tbl1.course_id||'_-1' NOT IN 
                               (Select z.acdmc_sttngs_id||'_'||z.course_id||'_'||z.subject_id from aca.aca_assmnt_col_vals z where z.assess_sheet_hdr_id=tbl1.assess_sheet_hdr_id)";
    execUpdtInsSQL($insSQL1);

    $insSQL = "INSERT INTO aca.aca_assmnt_col_vals(acdmc_sttngs_id, assess_sheet_hdr_id, data_col1,
                                    data_col2, data_col3, data_col4, data_col5, data_col6, data_col7, data_col8,
                                    data_col9, data_col10, data_col11, data_col12, data_col13, data_col14, data_col15,
                                    data_col16, data_col17, data_col18, data_col19, data_col20, data_col21, data_col22,
                                    data_col23, data_col24, data_col25, data_col26, data_col27, data_col28, data_col29,
                                    data_col30, data_col31, data_col32, data_col33, data_col34, data_col35, data_col36,
                                    data_col37, data_col38, data_col39, data_col40, data_col41, data_col42, data_col43,
                                    data_col44, data_col45, data_col46, data_col47, data_col48, data_col49, data_col50,
                                    created_by, creation_date, last_update_by, last_update_date, course_id, subject_id)
                    select distinct tbl1.acdmc_sttngs_id
                         , tbl1.assess_sheet_hdr_id
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , '', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), tbl1.course_id, tbl1.subject_id 
                    from (SELECT a.assess_sheet_hdr_id,
                                    a.assess_sheet_name,
                                    a.assess_sheet_desc,
                                    b.acdmc_sttngs_id,
                                    b.person_id,
                                    a.class_id,
                                    a.assessment_type_id,
                                    b.course_id,
                                    c.subject_id,
                                    a.tutor_person_id,
                                    a.academic_period_id,
                                    a.org_id
                             FROM aca.aca_assess_sheet_hdr a,
                                  aca.aca_prsns_acdmc_sttngs b,
                                  aca.aca_prsns_ac_sttngs_sbjcts c
                             where a.class_id = b.class_id
                               and a.academic_period_id = b.acdmc_period_id
                               and (a.course_id = b.course_id or a.course_id<=0)
                               and (b.course_id = " . $assessShtCrseID . " or " . $assessShtCrseID . " <=0)
                               and c.acdmc_sttngs_id = b.acdmc_sttngs_id
                               and (c.subject_id = a.subject_id or a.subject_id<=0)
                               and (c.subject_id = " . $assessShtSbjctID . " or " . $assessShtSbjctID . " <=0)
                               and a.assessed_person_id = b.person_id
                               and a.assess_sheet_hdr_id= " . $assessShtHdrID . ") tbl1
                               where tbl1.acdmc_sttngs_id||'_'||tbl1.course_id || '_' || tbl1.subject_id NOT IN 
                               (Select z.acdmc_sttngs_id||'_'||z.course_id||'_'||z.subject_id from aca.aca_assmnt_col_vals z where z.assess_sheet_hdr_id=tbl1.assess_sheet_hdr_id)";
    return execUpdtInsSQL($insSQL);
}

function autoCreateRptCrdHdrFtrRecs($assessShtHdrID)
{
    global $usrID;
    //Insert into aca.aca_assmnt_col_vals all the persons that meet the header criteria 
    $insSQL = "INSERT INTO aca.aca_assmnt_col_vals(acdmc_sttngs_id, assess_sheet_hdr_id, data_col1,
                                    data_col2, data_col3, data_col4, data_col5, data_col6, data_col7, data_col8,
                                    data_col9, data_col10, data_col11, data_col12, data_col13, data_col14, data_col15,
                                    data_col16, data_col17, data_col18, data_col19, data_col20, data_col21, data_col22,
                                    data_col23, data_col24, data_col25, data_col26, data_col27, data_col28, data_col29,
                                    data_col30, data_col31, data_col32, data_col33, data_col34, data_col35, data_col36,
                                    data_col37, data_col38, data_col39, data_col40, data_col41, data_col42, data_col43,
                                    data_col44, data_col45, data_col46, data_col47, data_col48, data_col49, data_col50,
                                    created_by, creation_date, last_update_by, last_update_date)
                    select -1
                         , tbl1.assess_sheet_hdr_id
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , ''
                         , '', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS') 
                    from (SELECT a.assess_sheet_hdr_id
                             FROM aca.aca_assess_sheet_hdr a
                             where a.assess_sheet_hdr_id= " . $assessShtHdrID . ") tbl1
                               where -1 NOT IN 
                               (Select z.acdmc_sttngs_id from aca.aca_assmnt_col_vals z where z.assess_sheet_hdr_id=tbl1.assess_sheet_hdr_id)";
    return execUpdtInsSQL($insSQL);
}

function autoCleanUpRptCrdRecords($assessShtHdrID, $assessShtCrseID, $assessShtSbjctID)
{
    $insSQL = "DELETE FROM aca.aca_assmnt_col_vals
        WHERE acdmc_sttngs_id>0 
        and subject_id >0
        and assess_sheet_hdr_id= " . $assessShtHdrID .
        " and acdmc_sttngs_id||'_'||course_id || '_' || subject_id NOT IN (
                    select tbl1.acdmc_sttngs_id||'_'||tbl1.course_id || '_' || tbl1.subject_id 
                    from (SELECT a.assess_sheet_hdr_id,
                                    a.assess_sheet_name,
                                    a.assess_sheet_desc,
                                    b.acdmc_sttngs_id,
                                    b.person_id,
                                    a.class_id,
                                    a.assessment_type_id,
                                    b.course_id,
                                    c.subject_id,
                                    a.tutor_person_id,
                                    a.academic_period_id,
                                    a.org_id
                             FROM aca.aca_assess_sheet_hdr a,
                                  aca.aca_prsns_acdmc_sttngs b,
                                  aca.aca_prsns_ac_sttngs_sbjcts c
                             where a.class_id = b.class_id
                               and a.academic_period_id = b.acdmc_period_id
                               and (a.course_id = b.course_id or a.course_id<=0)
                               and (b.course_id = " . $assessShtCrseID . " or " . $assessShtCrseID . " <=0)
                               and c.acdmc_sttngs_id = b.acdmc_sttngs_id
                               and (c.subject_id = a.subject_id or a.subject_id<=0)
                               and (c.subject_id = " . $assessShtSbjctID . " or " . $assessShtSbjctID . " <=0)
                               and a.assessed_person_id = b.person_id
                               and a.assess_sheet_hdr_id= " . $assessShtHdrID . ") tbl1)";
    execUpdtInsSQL($insSQL);

    $insSQL1 = "DELETE FROM aca.aca_assmnt_col_vals
        WHERE acdmc_sttngs_id>0 
        and subject_id <= 0
        and assess_sheet_hdr_id= " . $assessShtHdrID .
        " and acdmc_sttngs_id||'_'||course_id || '_-1' NOT IN (
                    select tbl1.acdmc_sttngs_id||'_'||tbl1.course_id || '_-1' 
                    from (SELECT a.assess_sheet_hdr_id,
                                    a.assess_sheet_name,
                                    a.assess_sheet_desc,
                                    b.acdmc_sttngs_id,
                                    b.person_id,
                                    a.class_id,
                                    a.assessment_type_id,
                                    b.course_id,
                                    c.subject_id,
                                    a.tutor_person_id,
                                    a.academic_period_id,
                                    a.org_id
                             FROM aca.aca_assess_sheet_hdr a,
                                  aca.aca_prsns_acdmc_sttngs b,
                                  aca.aca_prsns_ac_sttngs_sbjcts c
                             where a.class_id = b.class_id
                               and a.academic_period_id = b.acdmc_period_id
                               and (a.course_id = b.course_id or a.course_id<=0)
                               and (b.course_id = " . $assessShtCrseID . " or " . $assessShtCrseID . " <=0)
                               and c.acdmc_sttngs_id = b.acdmc_sttngs_id
                               and (c.subject_id = a.subject_id or a.subject_id<=0)
                               and (c.subject_id = " . $assessShtSbjctID . " or " . $assessShtSbjctID . " <=0)
                               and a.assessed_person_id = b.person_id
                               and a.assess_sheet_hdr_id= " . $assessShtHdrID . ") tbl1)";
    return execUpdtInsSQL($insSQL1);
}

function createAsessColsData($assessShtHdrID, $sttngID, $data_col)
{
    global $usrID;
    $insSQL = "INSERT INTO aca.aca_assmnt_col_vals(
            acdmc_sttngs_id, assess_sheet_hdr_id, data_col1, data_col2, data_col3, data_col4, 
            data_col5, data_col6, data_col7, data_col8, data_col9, data_col10, 
            data_col11, data_col12, data_col13, data_col14, data_col15, data_col16, 
            data_col17, data_col18, data_col19, data_col20, data_col21, data_col22, 
            data_col23, data_col24, data_col25, data_col26, data_col27, data_col28, 
            data_col29, data_col30, data_col31, data_col32, data_col33, data_col34, 
            data_col35, data_col36, data_col37, data_col38, data_col39, data_col40, 
            data_col41, data_col42, data_col43, data_col44, data_col45, data_col46, 
            data_col47, data_col48, data_col49, data_col50, created_by, creation_date, 
            last_update_by, last_update_date)  
            VALUES(" . $sttngID . ", " . $assessShtHdrID . ", '" . loc_db_escape_string($data_col[1]) .
        "', '" . loc_db_escape_string($data_col[2]) . "', '" . loc_db_escape_string($data_col[3]) .
        "', '" . loc_db_escape_string($data_col[4]) . "', '" . loc_db_escape_string($data_col[5]) .
        "', '" . loc_db_escape_string($data_col[6]) . "', '" . loc_db_escape_string($data_col[7]) .
        "', '" . loc_db_escape_string($data_col[8]) . "', '" . loc_db_escape_string($data_col[9]) .
        "', '" . loc_db_escape_string($data_col[10]) . "', '" . loc_db_escape_string($data_col[11]) .
        "', '" . loc_db_escape_string($data_col[12]) . "', '" . loc_db_escape_string($data_col[13]) .
        "', '" . loc_db_escape_string($data_col[14]) . "', '" . loc_db_escape_string($data_col[15]) .
        "', '" . loc_db_escape_string($data_col[16]) . "', '" . loc_db_escape_string($data_col[17]) .
        "', '" . loc_db_escape_string($data_col[18]) . "', '" . loc_db_escape_string($data_col[19]) .
        "', '" . loc_db_escape_string($data_col[20]) . "', '" . loc_db_escape_string($data_col[21]) .
        "', '" . loc_db_escape_string($data_col[22]) . "', '" . loc_db_escape_string($data_col[23]) .
        "', '" . loc_db_escape_string($data_col[24]) . "', '" . loc_db_escape_string($data_col[25]) .
        "', '" . loc_db_escape_string($data_col[26]) . "', '" . loc_db_escape_string($data_col[27]) .
        "', '" . loc_db_escape_string($data_col[28]) . "', '" . loc_db_escape_string($data_col[29]) .
        "', '" . loc_db_escape_string($data_col[30]) . "', '" . loc_db_escape_string($data_col[31]) .
        "', '" . loc_db_escape_string($data_col[32]) . "', '" . loc_db_escape_string($data_col[33]) .
        "', '" . loc_db_escape_string($data_col[34]) . "', '" . loc_db_escape_string($data_col[35]) .
        "', '" . loc_db_escape_string($data_col[36]) . "', '" . loc_db_escape_string($data_col[37]) .
        "', '" . loc_db_escape_string($data_col[38]) . "', '" . loc_db_escape_string($data_col[39]) .
        "', '" . loc_db_escape_string($data_col[40]) . "', '" . loc_db_escape_string($data_col[41]) .
        "', '" . loc_db_escape_string($data_col[42]) . "', '" . loc_db_escape_string($data_col[43]) .
        "', '" . loc_db_escape_string($data_col[44]) . "', '" . loc_db_escape_string($data_col[45]) .
        "', '" . loc_db_escape_string($data_col[46]) . "', '" . loc_db_escape_string($data_col[47]) .
        "', '" . loc_db_escape_string($data_col[48]) . "', '" . loc_db_escape_string($data_col[49]) .
        "', '" . loc_db_escape_string($data_col[50]) . "', $usrID, to_char(now(),'YYYY-MM-DD HH24:MI:SS'), $usrID, to_char(now(),'YYYY-MM-DD HH24:MI:SS'))";
    return execUpdtInsSQL($insSQL);
}

function updateAsessColsData1($assessShtHdrID, $sttngID, $data_col)
{
    global $usrID;
    $updtSQL = "UPDATE aca.aca_assmnt_col_vals 
        SET data_col1='" . loc_db_escape_string($data_col[1]) .
        "', data_col2='" . loc_db_escape_string($data_col[2]) . "', data_col3='" . loc_db_escape_string($data_col[3]) .
        "', data_col4='" . loc_db_escape_string($data_col[4]) . "', data_col5='" . loc_db_escape_string($data_col[5]) .
        "', data_col6='" . loc_db_escape_string($data_col[6]) . "', data_col7='" . loc_db_escape_string($data_col[7]) .
        "', data_col8='" . loc_db_escape_string($data_col[8]) . "', data_col9='" . loc_db_escape_string($data_col[9]) .
        "', data_col10='" . loc_db_escape_string($data_col[10]) . "', data_col11='" . loc_db_escape_string($data_col[11]) .
        "', data_col12='" . loc_db_escape_string($data_col[12]) . "', data_col13='" . loc_db_escape_string($data_col[13]) .
        "', data_col14='" . loc_db_escape_string($data_col[14]) . "', data_col15='" . loc_db_escape_string($data_col[15]) .
        "', data_col16='" . loc_db_escape_string($data_col[16]) . "', data_col17='" . loc_db_escape_string($data_col[17]) .
        "', data_col18='" . loc_db_escape_string($data_col[18]) . "', data_col19='" . loc_db_escape_string($data_col[19]) .
        "', data_col20='" . loc_db_escape_string($data_col[20]) . "', data_col21='" . loc_db_escape_string($data_col[21]) .
        "', data_col22='" . loc_db_escape_string($data_col[22]) . "', data_col23='" . loc_db_escape_string($data_col[23]) .
        "', data_col24='" . loc_db_escape_string($data_col[24]) . "', data_col25='" . loc_db_escape_string($data_col[25]) .
        "', data_col26='" . loc_db_escape_string($data_col[26]) . "', data_col27='" . loc_db_escape_string($data_col[27]) .
        "', data_col28='" . loc_db_escape_string($data_col[28]) . "', data_col29='" . loc_db_escape_string($data_col[29]) .
        "', data_col30='" . loc_db_escape_string($data_col[30]) . "', data_col31='" . loc_db_escape_string($data_col[31]) .
        "', data_col32='" . loc_db_escape_string($data_col[32]) . "', data_col33='" . loc_db_escape_string($data_col[33]) .
        "', data_col34='" . loc_db_escape_string($data_col[34]) . "', data_col35='" . loc_db_escape_string($data_col[35]) .
        "', data_col36='" . loc_db_escape_string($data_col[36]) . "', data_col37='" . loc_db_escape_string($data_col[37]) .
        "', data_col38='" . loc_db_escape_string($data_col[38]) . "', data_col39='" . loc_db_escape_string($data_col[39]) .
        "', data_col40='" . loc_db_escape_string($data_col[40]) . "', data_col41='" . loc_db_escape_string($data_col[41]) .
        "', data_col42='" . loc_db_escape_string($data_col[42]) . "', data_col43='" . loc_db_escape_string($data_col[43]) .
        "', data_col44='" . loc_db_escape_string($data_col[44]) . "', data_col45='" . loc_db_escape_string($data_col[45]) .
        "', data_col46='" . loc_db_escape_string($data_col[46]) . "', data_col47='" . loc_db_escape_string($data_col[47]) .
        "', data_col48='" . loc_db_escape_string($data_col[48]) . "', data_col49='" . loc_db_escape_string($data_col[49]) .
        "', data_col50='" . loc_db_escape_string($data_col[50]) . "', last_update_by=$usrID,  
        last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS') 
        WHERE assess_sheet_hdr_id=" . $assessShtHdrID . " and acdmc_sttngs_id=" . $sttngID;
    //echo $updtSQL;
    return execUpdtInsSQL($updtSQL, "Asessment Column Data Update");
}

function updateAsessColsData($assColValId, $data_col)
{
    global $usrID;
    $updtSQL = "UPDATE aca.aca_assmnt_col_vals 
        SET data_col1='" . loc_db_escape_string($data_col[1]) .
        "', data_col2='" . loc_db_escape_string($data_col[2]) . "', data_col3='" . loc_db_escape_string($data_col[3]) .
        "', data_col4='" . loc_db_escape_string($data_col[4]) . "', data_col5='" . loc_db_escape_string($data_col[5]) .
        "', data_col6='" . loc_db_escape_string($data_col[6]) . "', data_col7='" . loc_db_escape_string($data_col[7]) .
        "', data_col8='" . loc_db_escape_string($data_col[8]) . "', data_col9='" . loc_db_escape_string($data_col[9]) .
        "', data_col10='" . loc_db_escape_string($data_col[10]) . "', data_col11='" . loc_db_escape_string($data_col[11]) .
        "', data_col12='" . loc_db_escape_string($data_col[12]) . "', data_col13='" . loc_db_escape_string($data_col[13]) .
        "', data_col14='" . loc_db_escape_string($data_col[14]) . "', data_col15='" . loc_db_escape_string($data_col[15]) .
        "', data_col16='" . loc_db_escape_string($data_col[16]) . "', data_col17='" . loc_db_escape_string($data_col[17]) .
        "', data_col18='" . loc_db_escape_string($data_col[18]) . "', data_col19='" . loc_db_escape_string($data_col[19]) .
        "', data_col20='" . loc_db_escape_string($data_col[20]) . "', data_col21='" . loc_db_escape_string($data_col[21]) .
        "', data_col22='" . loc_db_escape_string($data_col[22]) . "', data_col23='" . loc_db_escape_string($data_col[23]) .
        "', data_col24='" . loc_db_escape_string($data_col[24]) . "', data_col25='" . loc_db_escape_string($data_col[25]) .
        "', data_col26='" . loc_db_escape_string($data_col[26]) . "', data_col27='" . loc_db_escape_string($data_col[27]) .
        "', data_col28='" . loc_db_escape_string($data_col[28]) . "', data_col29='" . loc_db_escape_string($data_col[29]) .
        "', data_col30='" . loc_db_escape_string($data_col[30]) . "', data_col31='" . loc_db_escape_string($data_col[31]) .
        "', data_col32='" . loc_db_escape_string($data_col[32]) . "', data_col33='" . loc_db_escape_string($data_col[33]) .
        "', data_col34='" . loc_db_escape_string($data_col[34]) . "', data_col35='" . loc_db_escape_string($data_col[35]) .
        "', data_col36='" . loc_db_escape_string($data_col[36]) . "', data_col37='" . loc_db_escape_string($data_col[37]) .
        "', data_col38='" . loc_db_escape_string($data_col[38]) . "', data_col39='" . loc_db_escape_string($data_col[39]) .
        "', data_col40='" . loc_db_escape_string($data_col[40]) . "', data_col41='" . loc_db_escape_string($data_col[41]) .
        "', data_col42='" . loc_db_escape_string($data_col[42]) . "', data_col43='" . loc_db_escape_string($data_col[43]) .
        "', data_col44='" . loc_db_escape_string($data_col[44]) . "', data_col45='" . loc_db_escape_string($data_col[45]) .
        "', data_col46='" . loc_db_escape_string($data_col[46]) . "', data_col47='" . loc_db_escape_string($data_col[47]) .
        "', data_col48='" . loc_db_escape_string($data_col[48]) . "', data_col49='" . loc_db_escape_string($data_col[49]) .
        "', data_col50='" . loc_db_escape_string($data_col[50]) . "', last_update_by=$usrID,  
        last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE ass_col_val_id=" . $assColValId;
    //echo $updtSQL;
    return execUpdtInsSQL($updtSQL, "Asessment Column Data Update");
}

//END ASSESSMENT SHEETS
//FROM BASIC PERSON
function get_BscPrsnTtl($searchFor, $searchIn, $orgID, $searchAll, $fltrTypValue, $fltrTyp, $extra4 = "")
{
    $extra1 = "";
    $extra2 = "";
    $extra3 = "";
    $aldPrsTyp = getAllwdPrsnTyps();
    $aldPrsTyp = "'" . trim($aldPrsTyp, "'") . "'";
    if ($aldPrsTyp != "'All'") {
        $extra3 = " and ((SELECT z.prsn_type FROM pasn.prsn_prsntyps z WHERE (z.person_id = a.person_id) 
ORDER BY z.valid_end_date DESC, z.valid_start_date DESC LIMIT 1 OFFSET 0) IN (" . $aldPrsTyp . "))";
    }

    if ($searchAll == true) {
        $extra1 = "or 1 = 1";
    }
    if ($fltrTypValue == "All") {
        $extra2 = " and 1 = 1";
    } else {
        if ($fltrTyp == "Relation Type") {
            $extra2 = " and ((SELECT z.prsn_type FROM pasn.prsn_prsntyps z WHERE (z.person_id = a.person_id) 
ORDER BY z.valid_end_date DESC, z.valid_start_date DESC LIMIT 1 OFFSET 0)='" . $fltrTypValue . "')";
        } else if ($fltrTyp == "Division/Group") {
            $extra2 = " and (EXISTS(SELECT w.div_code_name FROM pasn.prsn_divs_groups z, org.org_divs_groups w 
WHERE (z.person_id = a.person_id and w.div_id = z.div_id and w.div_code_name='" . $fltrTypValue . "'  
and now() between to_timestamp(z.valid_start_date,'YYYY-MM-DD HH24:MI:SS') and 
to_timestamp(z.valid_end_date,'YYYY-MM-DD HH24:MI:SS'))))";
        } else if ($fltrTyp == "Job") {
            $extra2 = " and (EXISTS(SELECT w.job_code_name FROM pasn.prsn_jobs z, org.org_jobs w 
WHERE (z.person_id = a.person_id and w.job_id = z.job_id and w.job_code_name='" . $fltrTypValue . "'  
and now() between to_timestamp(z.valid_start_date,'YYYY-MM-DD HH24:MI:SS') and 
to_timestamp(z.valid_end_date,'YYYY-MM-DD HH24:MI:SS'))))";
        } else if ($fltrTyp == "Grade") {
            $extra2 = " and (EXISTS(SELECT w.grade_code_name FROM pasn.prsn_grades z, org.org_grades w 
WHERE (z.person_id = a.person_id and w.grade_id = z.grade_id and w.grade_code_name='" . $fltrTypValue . "'  
and now() between to_timestamp(z.valid_start_date,'YYYY-MM-DD HH24:MI:SS') and 
to_timestamp(z.valid_end_date,'YYYY-MM-DD HH24:MI:SS'))))";
        } else if ($fltrTyp == "Position") {
            $extra2 = " and (EXISTS(SELECT w.position_code_name FROM pasn.prsn_positions z, org.org_positions w 
WHERE (z.person_id = a.person_id and w.position_id = z.position_id and w.position_code_name='" . $fltrTypValue . "'  
and now() between to_timestamp(z.valid_start_date,'YYYY-MM-DD HH24:MI:SS') and 
to_timestamp(z.valid_end_date,'YYYY-MM-DD HH24:MI:SS'))))";
        }
    }
    $strSql = "";
    $whrcls = "";
    if ($searchIn == "ID/Full Name") {
        $whrcls = " AND (a.local_id_no ilike '" . loc_db_escape_string($searchFor) . "' or trim(a.title || ' ' || a.sur_name || " .
            "', ' || a.first_name || ' ' || a.other_names) ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Full Name") {
        $whrcls = " AND (trim(a.title || ' ' || a.sur_name || " .
            "', ' || a.first_name || ' ' || a.other_names) ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Residential Address") {
        $whrcls = " AND (a.res_address ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Contact Information") {
        $whrcls = " AND (a.pstl_addrs ilike '" . loc_db_escape_string($searchFor) .
            "' or a.email ilike '" . loc_db_escape_string($searchFor) .
            "' or a.cntct_no_tel ilike '" . loc_db_escape_string($searchFor) .
            "' or a.cntct_no_mobl ilike '" . loc_db_escape_string($searchFor) .
            "' or a.cntct_no_fax ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Linked Firm/Workplace") {
        $whrcls = " AND (scm.get_cstmr_splr_name(a.lnkd_firm_org_id) ilike '" . loc_db_escape_string($searchFor) .
            "' or scm.get_cstmr_splr_site_name(a.lnkd_firm_site_id) ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Person Type") {
        $whrcls = " AND ((Select g.prsn_type || ' ' || g.prn_typ_asgnmnt_rsn "
            . "|| ' ' || g.further_details from pasn.prsn_prsntyps g "
            . "where g.person_id=a.person_id ORDER BY g.valid_start_date DESC "
            . "LIMIT 1 OFFSET 0) ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Date of Birth") {
        $whrcls = " AND (to_char(to_timestamp(a.date_of_birth,"
            . "'YYYY-MM-DD'),'DD-Mon-YYYY') ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Home Town") {
        $whrcls = " AND (a.hometown ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Gender") {
        $whrcls = " AND (a.gender ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Marital Status") {
        $whrcls = " AND (a.marital_status ilike '" . loc_db_escape_string($searchFor) .
            "')";
    }

    $strSql = "SELECT count(1) " .
        "FROM prs.prsn_names_nos a "
        . "LEFT OUTER JOIN pasn.prsn_prsntyps b " .
        "ON (a.person_id = b.person_id and "
        . "b.prsntype_id = (SELECT MAX(c.prsntype_id) from pasn.prsn_prsntyps c where c.person_id = a.person_id)) " .
        "WHERE ((a.org_id = " . $orgID . " " . $extra1 . ")" . $whrcls . $extra2 . $extra3 . $extra4 .
        ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_BscPrsn(
    $searchFor,
    $searchIn,
    $offset,
    $limit_size,
    $orgID,
    $searchAll,
    $sortBy,
    $fltrTypValue,
    $fltrTyp,
    $extra4 = ""
) {
    $extra1 = "";
    $extra2 = "";
    $extra3 = "";
    $aldPrsTyp = getAllwdPrsnTyps();
    $aldPrsTyp = "'" . trim($aldPrsTyp, "'") . "'";
    if ($aldPrsTyp != "'All'") {
        $extra3 = " and ((SELECT z.prsn_type FROM pasn.prsn_prsntyps z WHERE (z.person_id = a.person_id) 
ORDER BY z.valid_end_date DESC, z.valid_start_date DESC LIMIT 1 OFFSET 0) IN (" . $aldPrsTyp . "))";
    }

    if ($searchAll == true) {
        $extra1 = "or 1 = 1";
    }
    if ($fltrTypValue == "All") {
        $extra2 = " and 1 = 1";
    } else {
        if ($fltrTyp == "Relation Type") {
            $extra2 = " and ((SELECT z.prsn_type FROM pasn.prsn_prsntyps z WHERE (z.person_id = a.person_id) 
ORDER BY z.valid_end_date DESC, z.valid_start_date DESC LIMIT 1 OFFSET 0)='" . $fltrTypValue . "')";
        } else if ($fltrTyp == "Division/Group") {
            $extra2 = " and (EXISTS(SELECT w.div_code_name FROM pasn.prsn_divs_groups z, org.org_divs_groups w 
WHERE (z.person_id = a.person_id and w.div_id = z.div_id and w.div_code_name='" . $fltrTypValue . "'  
and now() between to_timestamp(z.valid_start_date,'YYYY-MM-DD HH24:MI:SS') and 
to_timestamp(z.valid_end_date,'YYYY-MM-DD HH24:MI:SS'))))";
        } else if ($fltrTyp == "Job") {
            $extra2 = " and (EXISTS(SELECT w.job_code_name FROM pasn.prsn_jobs z, org.org_jobs w 
WHERE (z.person_id = a.person_id and w.job_id = z.job_id and w.job_code_name='" . $fltrTypValue . "'  
and now() between to_timestamp(z.valid_start_date,'YYYY-MM-DD HH24:MI:SS') and 
to_timestamp(z.valid_end_date,'YYYY-MM-DD HH24:MI:SS'))))";
        } else if ($fltrTyp == "Grade") {
            $extra2 = " and (EXISTS(SELECT w.grade_code_name FROM pasn.prsn_grades z, org.org_grades w 
WHERE (z.person_id = a.person_id and w.grade_id = z.grade_id and w.grade_code_name='" . $fltrTypValue . "'  
and now() between to_timestamp(z.valid_start_date,'YYYY-MM-DD HH24:MI:SS') and 
to_timestamp(z.valid_end_date,'YYYY-MM-DD HH24:MI:SS'))))";
        } else if ($fltrTyp == "Position") {
            $extra2 = " and (EXISTS(SELECT w.position_code_name FROM pasn.prsn_positions z, org.org_positions w 
WHERE (z.person_id = a.person_id and w.position_id = z.position_id and w.position_code_name='" . $fltrTypValue . "'  
and now() between to_timestamp(z.valid_start_date,'YYYY-MM-DD HH24:MI:SS') and 
to_timestamp(z.valid_end_date,'YYYY-MM-DD HH24:MI:SS'))))";
        }
    }
    $strSql = "";
    $whrcls = "";
    $ordrBy = "";
    if ($searchIn == "ID/Full Name") {
        $whrcls = " AND (a.local_id_no ilike '" . loc_db_escape_string($searchFor) . "' or trim(a.title || ' ' || a.sur_name || " .
            "', ' || a.first_name || ' ' || a.other_names) ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Full Name") {
        $whrcls = " AND (trim(a.title || ' ' || a.sur_name || " .
            "', ' || a.first_name || ' ' || a.other_names) ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Residential Address") {
        $whrcls = " AND (a.res_address ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Contact Information") {
        $whrcls = " AND (a.pstl_addrs ilike '" . loc_db_escape_string($searchFor) .
            "' or a.email ilike '" . loc_db_escape_string($searchFor) .
            "' or a.cntct_no_tel ilike '" . loc_db_escape_string($searchFor) .
            "' or a.cntct_no_mobl ilike '" . loc_db_escape_string($searchFor) .
            "' or a.cntct_no_fax ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Linked Firm/Workplace") {
        $whrcls = " AND (scm.get_cstmr_splr_name(a.lnkd_firm_org_id) ilike '" . loc_db_escape_string($searchFor) .
            "' or scm.get_cstmr_splr_site_name(a.lnkd_firm_site_id) ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Person Type") {
        $whrcls = " AND ((Select g.prsn_type || ' ' || g.prn_typ_asgnmnt_rsn "
            . "|| ' ' || g.further_details from pasn.prsn_prsntyps g "
            . "where g.person_id=a.person_id ORDER BY g.valid_start_date DESC "
            . "LIMIT 1 OFFSET 0) ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Date of Birth") {
        $whrcls = " AND (to_char(to_timestamp(a.date_of_birth,"
            . "'YYYY-MM-DD'),'DD-Mon-YYYY') ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Home Town") {
        $whrcls = " AND (a.hometown ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Gender") {
        $whrcls = " AND (a.gender ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Marital Status") {
        $whrcls = " AND (a.marital_status ilike '" . loc_db_escape_string($searchFor) .
            "')";
    }

    if ($sortBy == "Date Added DESC") {
        $ordrBy = "a.creation_date DESC";
    } else if ($sortBy == "Date of Birth") {
        $ordrBy = "a.date_of_birth ASC";
    } else if ($sortBy == "Full Name") {
        $ordrBy = "trim(a.sur_name || " .
            "', ' || a.first_name || ' ' || a.other_names) ASC";
    } else if ($sortBy == "ID ASC") {
        $ordrBy = "a.local_id_no ASC";
    } else if ($sortBy == "ID DESC") {
        $ordrBy = "a.local_id_no DESC";
    } else {
        $ordrBy = "a.local_id_no ASC";
    }

    $strSql = "SELECT a.person_id, a.local_id_no, trim(a.title || ' ' || a.sur_name || " .
        "', ' || a.first_name || ' ' || a.other_names) fullname, "
        . "COALESCE(a.img_location,''), a.first_name, a.sur_name, a.other_names,
                gender, marital_status,date_of_birth,
          place_of_birth, religion, res_address, pstl_addrs, email, cntct_no_tel, 
          cntct_no_mobl, cntct_no_fax, COALESCE(img_location,''), hometown, nationality, 
          lnkd_firm_org_id, 
          CASE WHEN lnkd_firm_org_id <=0 THEN new_company ELSE scm.get_cstmr_splr_name(lnkd_firm_org_id) END, 
          lnkd_firm_site_id, 
          CASE WHEN lnkd_firm_site_id <=0 THEN new_company_loc ELSE scm.get_cstmr_splr_site_name(lnkd_firm_site_id) END, 
          a.title, 
          b.prsn_type, b.prn_typ_asgnmnt_rsn, " .
        "b.further_details, b.valid_start_date, b.valid_end_date  " .
        "FROM prs.prsn_names_nos a "
        . "LEFT OUTER JOIN pasn.prsn_prsntyps b " .
        "ON (a.person_id = b.person_id and "
        . "b.prsntype_id = (SELECT MAX(c.prsntype_id) from pasn.prsn_prsntyps c where c.person_id = a.person_id)) " .
        "WHERE ((a.org_id = " . $orgID . " " . $extra1 . ")" . $whrcls . $extra2 . $extra3 . $extra4 .
        ") ORDER BY " . $ordrBy . " LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getAcaPrsnInfo($inPrsnID)
{
    execUpdtInsSQL("DELETE FROM aca.aca_prsns_ac_sttngs_sbjcts y
                     WHERE y.ac_sttngs_sbjcts_id IN (select tbl1.max_pkey_id
                                from (
                                         select b.person_id,
                                                a.subject_id,
                                                count(a.ac_sttngs_sbjcts_id),
                                                min(a.ac_sttngs_sbjcts_id) min_pkey_id,
                                                max(a.ac_sttngs_sbjcts_id) max_pkey_id
                                         from aca.aca_prsns_ac_sttngs_sbjcts a,
                                              aca.aca_prsns_acdmc_sttngs b
                                         where a.acdmc_sttngs_id = b.acdmc_sttngs_id
                                         group by b.person_id,
                                                  a.subject_id
                                         having count(a.ac_sttngs_sbjcts_id) > 1) tbl1)");
    $strSql = "SELECT a.person_id, a.local_id_no, trim(a.title || ' ' || a.sur_name || " .
        "', ' || a.first_name || ' ' || a.other_names) fullname,
                gender, date_of_birth, email, cntct_no_tel, 
          cntct_no_mobl, cntct_no_fax, nationality, 
          b.prsn_type, b.prn_typ_asgnmnt_rsn, " .
        "b.further_details, b.valid_start_date, b.valid_end_date  " .
        "FROM prs.prsn_names_nos a "
        . "LEFT OUTER JOIN pasn.prsn_prsntyps b " .
        "ON (a.person_id = b.person_id and "
        . "b.prsntype_id = (SELECT MAX(c.prsntype_id) from pasn.prsn_prsntyps c where c.person_id = a.person_id)) " .
        "WHERE ((a.person_id = " . $inPrsnID . "))";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getAllwdPrsnTyps()
{
    $strSql = "select a.pssbl_value_desc from gst.gen_stp_lov_values a, gst.gen_stp_lov_names b, sec.sec_roles c
WHERE a.value_list_id = b.value_list_id and a.pssbl_value = c.role_name 
and b.value_list_name = 'Allowed Person Types for Roles' and a.is_enabled='1' 
and c.role_id IN (" . concatCurRoleIDs() . ") ORDER BY a.pssbl_value_id LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function get_FilterValues($fltrTyp, $orgID)
{
    $result = null;
    if ($fltrTyp == "Position") {
        //Positions
        $result = executeSQLNoParams("Select position_code_name from org.org_positions where org_id=" . $orgID);
    } else if ($fltrTyp == "Division/Group") {
        //Div Groups
        $result = executeSQLNoParams("Select div_code_name from org.org_divs_groups where org_id=" . $orgID);
    } else if ($fltrTyp == "Grade") {
        //Grade
        $result = executeSQLNoParams("Select grade_code_name from org.org_grades where org_id=" . $orgID);
    } else if ($fltrTyp == "Job") {
        //Job
        $result = executeSQLNoParams("Select job_code_name from org.org_jobs where org_id=" . $orgID);
    } else {
        //Person Types
        $aldPrsTyp = getAllwdPrsnTyps();
        $extra3 = "";
        $aldPrsTyp = "'" . trim($aldPrsTyp, "'") . "'";
        if ($aldPrsTyp != "'All'") {
            $extra3 = " and pssbl_value IN (" . $aldPrsTyp . ")";
        }
        $result = getAllEnbldPssblVals("Person Types", $extra3);
    }
    return $result;
}

function getAllEnbldPssblVals($lovNm, $extrWhr)
{
    global $orgID;
    $sqlStr = "select pssbl_value from gst.gen_stp_lov_values " .
        "WHERE is_enabled='1' and value_list_id = " . getLovID($lovNm) .
        " and allowed_org_ids ilike '%," . $orgID .
        ",%'" . $extrWhr . " ORDER BY 1";
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function loadDataOptions($relationType)
{
    global $orgID;
    $pssblItems = [];
    if ($relationType === "Relation Type") {
        $i = 0;
        $brghtStr = "";
        $isDynmyc = FALSE;
        $titleRslt = getLovValues("%", "Both", 0, 500, $brghtStr, getLovID("Person Types"), $isDynmyc, -1, "", "");
        while ($titleRow = loc_db_fetch_array($titleRslt)) {
            $pssblItems[$i] = $titleRow[0];
            $i++;
        }
    } else if ($relationType === "Division/Group") {
        $i = 0;
        $brghtStr = "";
        $isDynmyc = TRUE;
        $titleRslt = getLovValues("%", "Both", 0, 500, $brghtStr, getLovID("Divisions/Groups"), $isDynmyc, $orgID, "", "");
        while ($titleRow = loc_db_fetch_array($titleRslt)) {
            $pssblItems[$i] = $titleRow[1];
            $i++;
        }
    } else if ($relationType === "Grade") {
        $i = 0;
        $brghtStr = "";
        $isDynmyc = TRUE;
        $titleRslt = getLovValues("%", "Both", 0, 500, $brghtStr, getLovID("Grades"), $isDynmyc, $orgID, "", "");
        while ($titleRow = loc_db_fetch_array($titleRslt)) {
            $pssblItems[$i] = $titleRow[1];
            $i++;
        }
    } else if ($relationType === "Job") {
        $i = 0;
        $brghtStr = "";
        $isDynmyc = TRUE;
        $titleRslt = getLovValues("%", "Both", 0, 500, $brghtStr, getLovID("Jobs"), $isDynmyc, $orgID, "", "");
        while ($titleRow = loc_db_fetch_array($titleRslt)) {
            $pssblItems[$i] = $titleRow[1];
            $i++;
        }
    } else if ($relationType === "Position") {
        $i = 0;
        $brghtStr = "";
        $isDynmyc = TRUE;
        $titleRslt = getLovValues("%", "Both", 0, 500, $brghtStr, getLovID("Positions"), $isDynmyc, $orgID, "", "");
        while ($titleRow = loc_db_fetch_array($titleRslt)) {
            $pssblItems[$i] = $titleRow[1];
            $i++;
        }
    } else if ($relationType === "Site/Location") {
        $i = 0;
        $brghtStr = "";
        $isDynmyc = TRUE;
        $titleRslt = getLovValues("%", "Both", 0, 500, $brghtStr, getLovID("Sites/Locations"), $isDynmyc, $orgID, "", "");
        while ($titleRow = loc_db_fetch_array($titleRslt)) {
            $pssblItems[$i] = $titleRow[1];
            //getGnrlRecNm("org.org_sites_locations", "location_id", "location_code_name", ((int) $titleRow[0]));
            $i++;
        }
    }
    return "All;" . join(";", $pssblItems);
}

function loadDataOptions2($relationType)
{
    global $orgID;
    $pssblItems = [];
    if ($relationType === "Relation Type") {
        $i = 0;
        $pssblItems[$i] = "All";
        $i++;
        $brghtStr = "";
        $isDynmyc = FALSE;
        $titleRslt = getLovValues("%", "Both", 0, 500, $brghtStr, getLovID("Person Types"), $isDynmyc, -1, "", "");
        while ($titleRow = loc_db_fetch_array($titleRslt)) {
            $pssblItems[$i] = $titleRow[0];
            $i++;
        }
    } else if ($relationType === "Division/Group") {
        $i = 0;
        $pssblItems[$i] = "All";
        $i++;
        $brghtStr = "";
        $isDynmyc = TRUE;
        $titleRslt = getLovValues("%", "Both", 0, 500, $brghtStr, getLovID("Divisions/Groups"), $isDynmyc, $orgID, "", "");
        while ($titleRow = loc_db_fetch_array($titleRslt)) {
            $pssblItems[$i] = $titleRow[1];
            $i++;
        }
    } else if ($relationType === "Grade") {
        $i = 0;
        $pssblItems[$i] = "All";
        $i++;
        $brghtStr = "";
        $isDynmyc = TRUE;
        $titleRslt = getLovValues("%", "Both", 0, 500, $brghtStr, getLovID("Grades"), $isDynmyc, $orgID, "", "");
        while ($titleRow = loc_db_fetch_array($titleRslt)) {
            $pssblItems[$i] = $titleRow[1];
            $i++;
        }
    } else if ($relationType === "Job") {
        $i = 0;
        $pssblItems[$i] = "All";
        $i++;
        $brghtStr = "";
        $isDynmyc = TRUE;
        $titleRslt = getLovValues("%", "Both", 0, 500, $brghtStr, getLovID("Jobs"), $isDynmyc, $orgID, "", "");
        while ($titleRow = loc_db_fetch_array($titleRslt)) {
            $pssblItems[$i] = $titleRow[1];
            $i++;
        }
    } else if ($relationType === "Position") {
        $i = 0;
        $pssblItems[$i] = "All";
        $i++;
        $brghtStr = "";
        $isDynmyc = TRUE;
        $titleRslt = getLovValues("%", "Both", 0, 500, $brghtStr, getLovID("Positions"), $isDynmyc, $orgID, "", "");
        while ($titleRow = loc_db_fetch_array($titleRslt)) {
            $pssblItems[$i] = $titleRow[1];
            $i++;
        }
    } else if ($relationType === "Site/Location") {
        $i = 0;
        $pssblItems[$i] = "All";
        $i++;
        $brghtStr = "";
        $isDynmyc = TRUE;
        $titleRslt = getLovValues("%", "Both", 0, 500, $brghtStr, getLovID("Sites/Locations"), $isDynmyc, $orgID, "", "");
        while ($titleRow = loc_db_fetch_array($titleRslt)) {
            $pssblItems[$i] = $titleRow[1];
            //getGnrlRecNm("org.org_sites_locations", "location_id", "location_code_name", ((int) $titleRow[0]));
            $i++;
        }
    }
    return $pssblItems;
}

function getPDivGrpID($prsnID, $divGrpID)
{
    $sqlStr = "SELECT prsn_div_id from pasn.prsn_divs_groups where (div_id = " . $divGrpID . " AND person_id = $prsnID)";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createPDivGrp($prsnID, $divGrpID, $pDivGrpStartDate, $pDivGrpEndDate)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO pasn.prsn_divs_groups(person_id, div_id, valid_start_date, valid_end_date, created_by, creation_date,
                                  last_update_by, last_update_date)
                                            VALUES (" . $prsnID . ","
        . $divGrpID . ",'"
        . cnvrtDMYToYMD($pDivGrpStartDate) . "','"
        . cnvrtDMYToYMD($pDivGrpEndDate) . "',"
        . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";
    return execUpdtInsSQL($insSQL);
}

function updatePDivGrp($pkeyID, $divGrpID, $pDivGrpStartDate, $pDivGrpEndDate)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE pasn.prsn_divs_groups
        SET div_id=" . $divGrpID .
        ", valid_start_date='" . cnvrtDMYToYMD($pDivGrpStartDate) .
        "', valid_end_date='" . cnvrtDMYToYMD($pDivGrpEndDate) .
        "', last_update_by=" . $usrID . ", last_update_date='" . $dateStr .
        "' WHERE prsn_div_id = " . $pkeyID;
    return execUpdtInsSQL($updtSQL, "Update of Division/Group");
}

function deletePDivGrp($pkeyID)
{
    $insSQL = "DELETE FROM pasn.prsn_divs_groups WHERE prsn_div_id = " . $pkeyID;
    $affctd1 = execUpdtInsSQL($insSQL, "Remove Person's Division/Group");
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Division/Group(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}
