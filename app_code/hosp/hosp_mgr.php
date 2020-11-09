<?php

require 'app_code/hotl/hotl_funcs.php';
require 'app_code/inv/inv_funcs.php';

$menuItems = array("Summary Dashboard",
    "Visits & Appointments", "Appointment Data",
    "Services Offered", "Service Providers", "List of Diagnosis",
    "Investigation List", "Tech Setup");
$menuImages = array("dashboard220.png", "alarm-clock.png", "appoint.png",
    "chcklst2.png", "practioner.jpg", "chcklst1.png", "checklist1.jpg", "ControlPanel.png");

$brghtMdl = isset($_POST['mdl']) ? cleanInputData($_POST['mdl']) : 'Clinic/Hospital';
$mdlNm = $brghtMdl;
$ModuleName = $mdlNm;
$viewRoleName = "View $brghtMdl";

$dfltPrvldgs = array(/* 0 */ $viewRoleName,
    /* 1 */ "View Visits/Appointments", "View Appointments Data", "View Services Offered", "View Services Providers", 
    /* 5 */ "View Diagnosis List", "View Investigations List", "View Dashboard",
    /* 8 */ "Add Visits/Appointments", "Edit Visits/Appointments", "Delete Visits/Appointments",
    /* 11 */ "Add Appointment Data", "Edit Appointment Data", "Delete Appointment Data",
    /* 14 */ "Add Services Offered", "Edit Services Offered", "Delete Services Offered",
    /* 17 */ "Add Service Providers", "Edit Service Providers", "Delete Service Providers",
    /* 20 */ "Add Diagnosis List", "Edit Diagnosis List", "Delete Diagnosis List",  
    /* 23 */ "Add Investigations List", "Edit Investigations List", "Delete Investigations List",
    /* 26 */ "Close Visit", "View Appointment Data Items",
    /* 28 */ "Add Appointment Data Items", "Edit Appointment Data Items", "Delete Appointment Data Items",
    /* 31 */ "Generate Sales Invoice", "Cancel Appointment", "Setup Extra Service Data", "View Sales Invoice",
    /* 35 */ "Add Consultation Data", "Edit Consultation Data", "Delete Consultation Data",
    /* 38 */ "Add Presciptions", "Edit Presciptions", "Delete Presciptions",
    /* 41 */ "Add Vitals", "Edit Vitals", "Delete Vitals",
    /* 44 */ "Add Recommended Service", "Edit Recommended Service", "Delete Recommended Service");

//$p= '"View SQL", "View Record History", "View only Self-Created Sales", "Cancel Documents", "Take Payments"';

$canview = test_prmssns($dfltPrvldgs[0], $ModuleName);

$canViewAppntmntDataItms = test_prmssns($dfltPrvldgs[27], $ModuleName);
$canAddAppntmntDataItms = test_prmssns($dfltPrvldgs[28], $ModuleName);
$canEditAppntmntDataItms = test_prmssns($dfltPrvldgs[29], $ModuleName);
$canDelAppntmntDataItms = test_prmssns($dfltPrvldgs[30], $ModuleName);
$canGenSalesInvoice = test_prmssns($dfltPrvldgs[31], $ModuleName);
$canViewSalesInvoice = test_prmssns($dfltPrvldgs[34], $ModuleName);

$canAddRecsVNA = test_prmssns($dfltPrvldgs[8], $mdlNm);
$canEdtRecsVNA = test_prmssns($dfltPrvldgs[9], $mdlNm);
$canDelRecsVNA = test_prmssns($dfltPrvldgs[10], $mdlNm);

$prsnid = $_SESSION['PRSN_ID'];
$orgID = $_SESSION['ORG_ID'];
$usrID = $_SESSION['USRID'];
$uName = $_SESSION['UNAME'];
$accbFSRptStoreID = isset($_POST['accbFSRptStoreID']) ? (int) cleanInputData($_POST['accbFSRptStoreID']) : -1;
$selectedStoreID = (int) $_SESSION['SELECTED_SALES_STOREID'];
if ($accbFSRptStoreID > 0 && getUserStorePkeyID($accbFSRptStoreID) > 0) {
    $selectedStoreID = $accbFSRptStoreID;
    $_SESSION['SELECTED_SALES_STOREID'] = $selectedStoreID;
}
$gnrlTrnsDteDMYHMS = getFrmtdDB_Date_time();
$gnrlTrnsDteYMDHMS = cnvrtDMYTmToYMDTm($gnrlTrnsDteDMYHMS);
$gnrlTrnsDteYMD = substr($gnrlTrnsDteYMDHMS, 0, 10);

$invPrmSnsRstl = getInvPgPrmssns($prsnid, $orgID, $usrID);
$fnccurid = $invPrmSnsRstl[0];
$fnccurnm = getPssblValNm($fnccurid);
$brnchLocID = $invPrmSnsRstl[1];
$brnchLoc = getGnrlRecNm("org.org_sites_locations", "location_id", "REPLACE(location_code_name || '.' || site_desc, '.' || location_code_name,'')", $brnchLocID);
$acsCntrlGrpID = $invPrmSnsRstl[2];
if ($selectedStoreID <= 0) {
    $selectedStoreID = $invPrmSnsRstl[3];
    $_SESSION['SELECTED_SALES_STOREID'] = $selectedStoreID;
}
$acsCntrlGrpNm = getStoreNm($selectedStoreID);
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

if (isset($_POST['vtypActn'])) {//ADD, EDIT, VIEW
    $vwtypActn = cleanInputData($_POST['vtypActn']);
}

$cntent = "<div>
				<ul class=\"breadcrumb\" style=\"$breadCrmbBckclr\">
					<li onclick=\"openATab('#home', 'grp=40&typ=1');\">
                                                <i class=\"fa fa-home\" aria-hidden=\"true\"></i>
						<span style=\"text-decoration:none;\">Home</span>
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
					</li>
					<li onclick=\"openATab('#allmodules', 'grp=40&typ=5');\">
						<span style=\"text-decoration:none;\">All Modules&nbsp;</span>
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
					</li>
					<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&mdl=$brghtMdl');\">
						<span style=\"text-decoration:none;\">$brghtMdl Menu</span>
					</li>";
//echo('Hi2');
if ($lgn_num > 0 && $canview === true) {
    //echo('Hi1');
    if ($pgNo == 0) {
        /* $cntent .= "</ul></div>" . "<div style=\"font-family: Tahoma, Arial, sans-serif;font-size: 1.3em;
          padding:10px 15px 15px 20px;border:1px solid #ccc;\">
          <div style=\"padding:5px 30px 5px 10px;margin-bottom:2px;\">
          <span style=\"font-family: georgia, times;font-size: 12px;font-style:italic;
          font-weight:normal;\">This module helps you to manage your organization's Client Visits and Appointments Scheduling!. The module has the ff areas:</span>
          </div>
          <p>"; */
        //CHECK EXISTENCE OF CORE SERVICES AND LOAD IF NOT AVAILABLE
        loadCoreServices();
        
        $lovNm = ("Users\' Sales Stores");
        $cntent .= " </ul></div>" .
                "<div  class=\"col-md-12\" style=\"width:87% !important;max-width:87% !important;font-family: Tahoma, Arial, sans-serif;font-size: 1.3em;padding:2px 5px 2px 5px;border:1px solid #ddd;\">
                        <div class=\"col-md-5\" style=\"padding:0px 1px 0px 1px;margin-top:3px !important;\">
                            <span style=\"font-family: georgia, times;font-size: 14px;font-style:normal;
                            font-weight:bold;color:black;\">Transactions Date:</span>
                                <span style=\"font-family: tahoma;font-size: 14px;font-style:normal;
                            font-weight:bold;color:blue;\">$gnrlTrnsDteDMYHMS</span>
                        </div>
                        <div  class=\"col-md-7\" style=\"padding:0px 1px 0px 1px !important;\">
                            <div class=\"form-group\">
                                <label for=\"accbFSRptStore\" class=\"control-label col-md-4\" style=\"padding:5px 1px 0px 1px !important;font-family: georgia, times;font-size: 14px;font-style:normal;
                            font-weight:bold;color:blac\">Default Store:</label>
                                <div  class=\"col-md-8\" style=\"padding:0px 1px 0px 1px !important;\">
                                    <div class=\"input-group\">
                                        <input type=\"text\" class=\"form-control\" style=\"font-family: tahoma;font-size: 14px;font-style:normal;
                            font-weight:bold;color:blue;width:100%;\" id=\"accbFSRptStore\" name=\"accbFSRptStore\" value=\"" . $acsCntrlGrpNm . "\" readonly=\"true\">
                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"accbFSRptStoreID\" name=\"accbFSRptStoreID\" value=\"" . $selectedStoreID . "\">
                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '" . $lovNm . "', 'allOtherInputOrgID', 'allOtherInputUsrID', '', 'radio', true, '', 'accbFSRptStoreID', 'accbFSRptStore', 'clear', 1, '', function () {setCurStoreHosp();});\">
                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
      <p>";
        $grpcntr = 0;
        for ($i = 0; $i < count($menuItems); $i++) {
            $No = $i + 1;
            /*if ($i >= 5 && $brghtMdl != "Clinic/Hospital") {
                continue;
            }*/
            if($i == 7){//EXCLUDE SCRIPT GENERATOR
                continue;
            }
            
            if ($i == 0 /*&& test_prmssns($dfltPrvldgs[7], $mdlNm) == FALSE*/) {
                continue;
            } else if ($i == 1 && test_prmssns($dfltPrvldgs[1], $mdlNm) == FALSE) {
                continue;
            } else if ($i == 2 && test_prmssns($dfltPrvldgs[2], $mdlNm) == FALSE) {
                continue;
            } else if ($i == 3 && test_prmssns($dfltPrvldgs[3], $mdlNm) == FALSE) {
                continue;
            } else if ($i == 4 && test_prmssns($dfltPrvldgs[4], $mdlNm) == FALSE) {
                continue;
            } else if ($i == 5 && test_prmssns($dfltPrvldgs[5], $mdlNm) == FALSE) {
                continue;
            } else if ($i == 6 && test_prmssns($dfltPrvldgs[6], $mdlNm) == FALSE) {
                continue;
            } 
            
            if ($grpcntr == 0) {
                $cntent .= "<div class=\"row\">";
            }

            $cntent .= "<div class=\"col-md-3 colmd3special2\">
        <button type=\"button\" class=\"btn btn-default btn-lg btn-block modulesButton\" onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$No&vtyp=0&mdl=$brghtMdl');\">
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
    } else if ($pgNo == 1) {
        require "smmry_dshbrd.php";
    } else if ($pgNo == 2) {
        require "vsts_appnts.php";
    } else if ($pgNo == 3) {
        require "appnts_data.php";
    } else if ($pgNo == 4) {
        require "srvcs_offrd.php";
    } else if ($pgNo == 5) {
        require "srvc_prvdrs.php";
    } else if ($pgNo == 6) {
        require "diagnosis.php";
    } else if ($pgNo == 7) {
        require "lab_list.php";
    } else if ($pgNo == 8) {
        require "tech_setup.php";  
    } else if ($pgNo == 101) {
        require "hstrc_mdcl_cnsltn.php";
    } else if ($pgNo == 102) {
        require "srvsoffrd_addtnl_data_stps.php";
    } else if ($pgNo == 103) {
        require "hosp_attachments.php";
    } else {
        restricted();
    }
}

//VISITS AND APPOINTMENTS
function get_AllVisitsTtl($qStrtDte, $qEndDte, $crdtTypeSrchIn, $statusSrchIn, $branchSrchIn, $searchFor, $searchIn, $orgID, $searchAll) {
    $extra1 = "";

    if ($searchAll == true) {
        $extra1 = "or 1 = 1";
    }

    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Visit Number") {
        $whrcls = " AND (b.trnsctn_no ilike '" . loc_db_escape_string($searchFor) . "')";
    } if ($searchIn == "ID/Full Name") {
        $whrcls = " AND (a.local_id_no ilike '" . loc_db_escape_string($searchFor) . "' or trim(a.title || ' ' || a.sur_name || " .
                "', ' || a.first_name || ' ' || a.other_names) ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Full Name") {
        $whrcls = " AND (trim(a.title || ' ' || a.sur_name || " .
                "', ' || a.first_name || ' ' || a.other_names) ilike '" . loc_db_escape_string($searchFor) . "')";
    }

    if ($branchSrchIn == "All Branches") {
        $whrcls .= " and 1 = 1";
    } else {
        $whrcls .= " and b.branch_id = $branchSrchIn";
    }

    if ($statusSrchIn == "All Statuses") {
        $whrcls .= " and 1 = 1";
    } else {
        $whrcls .= "  and b.vst_status = '$statusSrchIn'";
    }

    if ($crdtTypeSrchIn == "All Visit Types") {
        $whrcls .= " and 1 = 1";
    } else {
        $whrcls .= " and b.vstr_type = '" . loc_db_escape_string($crdtTypeSrchIn) . "'";
    }

    if ($qStrtDte != "") {
        $whrcls .= " AND (vst_date >= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qStrtDte)) . "')";
    }
    if ($qEndDte != "") {
        $whrcls .= " AND (vst_date <= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qEndDte)) . "')";
    }

    $strSql = "SELECT count(1) " .
            "FROM prs.prsn_names_nos a, hosp.visit b 
              WHERE a.person_id = b.prsn_id AND ((a.org_id = " . $orgID . " " . $extra1 . ")" . $whrcls .
            ")";
    $result = executeSQLNoParams($strSql);
    //echo $strSql;
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_AllVisits($qStrtDte, $qEndDte, $crdtTypeSrchIn, $statusSrchIn, $branchSrchIn, $searchFor, $searchIn, $offset, $limit_size, $orgID, $searchAll, $sortBy) {
    $extra1 = "";

    if ($searchAll == true) {
        $extra1 = "or 1 = 1";
    }

    $whrcls = "";
    $ordrBy = "";
    if ($searchIn == "Visit Number") {
        $whrcls = " AND (b.trnsctn_no ilike '" . loc_db_escape_string($searchFor) . "')";
    } if ($searchIn == "ID/Full Name") {
        $whrcls = " AND (a.local_id_no ilike '" . loc_db_escape_string($searchFor) . "' or trim(a.title || ' ' || a.sur_name || " .
                "', ' || a.first_name || ' ' || a.other_names) ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Full Name") {
        $whrcls = " AND (trim(a.title || ' ' || a.sur_name || " .
                "', ' || a.first_name || ' ' || a.other_names) ilike '" . loc_db_escape_string($searchFor) . "')";
    }

    if ($branchSrchIn == "All Branches") {
        $whrcls .= " and 1 = 1";
    } else {
        $whrcls .= " and b.branch_id = $branchSrchIn";
    }

    if ($statusSrchIn == "All Statuses") {
        $whrcls .= " and 1 = 1";
    } else {
        $whrcls .= "  and b.vst_status = '$statusSrchIn'";
    }

    if ($crdtTypeSrchIn == "All Visit Types") {
        $whrcls .= " and 1 = 1";
    } else {
        $whrcls .= " and b.vstr_type = '" . loc_db_escape_string($crdtTypeSrchIn) . "'";
    }

    if ($qStrtDte != "") {
        $whrcls .= " AND (vst_date >= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qStrtDte)) . "')";
    }
    if ($qEndDte != "") {
        $whrcls .= " AND (vst_date <= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qEndDte)) . "')";
    }

    if ($sortBy == "Visit Number ASC") {
        $ordrBy = "b.trnsctn_no ASC";
    } else if ($sortBy == "Date Added DESC") {
        $ordrBy = "b.creation_date DESC";
    } else if ($sortBy == "Visit Status ASC") {
        $ordrBy = "b.vst_status ASC";
    }

    $strSql = "SELECT vst_id, b.trnsctn_no, trim(a.title || ' ' || a.sur_name ||', ' || a.first_name || ' ' || a.other_names)||' ('||a.local_id_no||')' prsn_name,   
            to_char(to_timestamp(vst_date,'yyyy-mm-dd hh24:mi:ss'),'dd-Mon-yyyy hh24:mi:ss') vst_date, CASE WHEN bill_this_visit = 'Y' THEN 'Yes' ELSE 'No' END bill_visit, 
            CASE WHEN total_bill = 0 THEN ''||total_bill ELSE to_char(total_bill,'FM9,999,999,999,999D90') END total_bill, 
            CASE WHEN total_pymnt = 0 THEN ''||total_pymnt ELSE to_char(total_pymnt,'FM9,999,999,999,999D90') END total_pymnt, vst_status, case when vst_end_date = '' THEN '' ELSE to_char(to_timestamp(vst_end_date,'yyyy-mm-dd hh24:mi:ss'),'dd-Mon-yyyy hh24:mi:ss') END vst_end_date,
            cmnts, vstr_type, b.org_id, branch_id, b.prsn_id 
        FROM prs.prsn_names_nos a, hosp.visit b 
        WHERE  a.person_id = b.prsn_id AND (( a.org_id = " . $orgID . " " . $extra1 . ")" . $whrcls .
            ") ORDER BY " . $ordrBy . " LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    //var_dump($strSql);
    return $result;
}

function get_HospPerson_BranchID($prsnId) {
    $strSql = "SELECT a.location_id FROM pasn.prsn_locations a
        WHERE ((a.person_id = $prsnId) and
        (now() between to_timestamp(a.valid_start_date,
        'YYYY-MM-DD 00:00:00') AND
        to_timestamp(a.valid_end_date,'YYYY-MM-DD 23:59:59'))) ORDER BY a.prsn_loc_id DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function get_HospBranchIds($siteName) {
    $site_ids = ',';

    $strSql = "select location_id from org.org_sites_locations "
            . "where site_desc ilike '" . loc_db_escape_string($siteName) . "'";

    $result = executeSQLNoParams($strSql);
    $rowNo = loc_db_num_rows($result);
    if ($rowNo <= 0) {
        return -1;
    }
    while ($row = loc_db_fetch_array($result)) {
        $site_ids = $site_ids . $row[0] . ",";
    }

    $site_ids = rtrim(ltrim($site_ids, ","), ",");
    return $site_ids;
}

function get_VisitHdrData($pkID) {
    $strSql = "SELECT vst_id, b.trnsctn_no, trim(a.title || ' ' || a.sur_name ||', ' || a.first_name || ' ' || a.other_names)||' ('||a.local_id_no||')' prsn_name, /**2**/   
            to_char(to_timestamp(vst_date,'yyyy-mm-dd hh24:mi:ss'),'dd-Mon-yyyy hh24:mi:ss') vst_date, CASE WHEN bill_this_visit = 'Y' THEN 'Yes' ELSE 'No' END bill_visit,  /**4**/   
            CASE WHEN total_bill = 0 THEN '0.00' ELSE to_char(total_bill,'FM9,999,999,999,999D90') END total_bill,  /**5**/   
            CASE WHEN total_pymnt = 0 THEN '0.00' ELSE to_char(total_pymnt,'FM9,999,999,999,999D90') END total_pymnt, vst_status,  /**7**/   
            case when vst_end_date = '' THEN '' ELSE to_char(to_timestamp(vst_end_date,'yyyy-mm-dd hh24:mi:ss'),'dd-Mon-yyyy hh24:mi:ss') END vst_end_date,  /**8**/   
            cmnts, vstr_type, b.org_id, branch_id, b.prsn_id, (SELECT COUNT(*) FROM hosp.appntmnt WHERE vst_id = b.vst_id) appntmnt_cnt,  /**14**/   
            vst_option /**15**/
        FROM prs.prsn_names_nos a, hosp.visit b 
        WHERE  a.person_id = b.prsn_id AND b.vst_id = $pkID";

    $result = executeSQLNoParams($strSql);
    //var_dump($strSql);
    return $result;
}

function get_VisitAppointments($pkID) {
    $strSql = "SELECT appntmnt_id, to_char(to_timestamp(appntmnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') appntmnt_date, /**1**/
	type_name||'('||sys_code||')' srvs_type, CASE WHEN UPPER(prvdr_type) = 'G' THEN 'Group' ELSE 'Individual' END prvdr_type,  /**3**/
        CASE WHEN UPPER(prvdr_type) = 'G' THEN prvdr_grp_name||CASE WHEN '('||COALESCE(prs.get_prsn_name(srvs_prvdr_prsn_id),'')||')' = '()' THEN '' ELSE ' ('||COALESCE(prs.get_prsn_name(srvs_prvdr_prsn_id),'')||')' END
        ELSE prs.get_prsn_name(srvs_prvdr_prsn_id) END srvs_provider, /**4**/
	appntmnt_status, cmnts, CASE WHEN appntmnt_end_date = '' THEN '' ELSE to_char(to_timestamp(appntmnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') END appntmnt_end_date, /**7**/
	appntmnt_no, vst_id, a.srvs_type_id, srvs_prvdr_prsn_id, a.prvdr_grp_id, sys_code /**13**/
    FROM hosp.appntmnt a LEFT OUTER JOIN hosp.srvs_types b ON a.srvs_type_id = b.type_id
    LEFT OUTER JOIN  hosp.prvdr_grps c ON a.prvdr_grp_id = c.prvdr_grp_id
    LEFT OUTER JOIN prs.prsn_names_nos d ON a.srvs_prvdr_prsn_id = d.person_id 
    WHERE vst_id = $pkID";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_VisitAppointmentDets($appntmnt_id) {
    $strSql = "SELECT appntmnt_id, to_char(to_timestamp(appntmnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') appntmnt_date, /**1**/
	a.srvs_type_id, type_name||'('||sys_code||')' srvs_type, prvdr_type,  /**4**/
        srvs_prvdr_prsn_id, trim(d.title || ' ' || d.sur_name ||', ' || d.first_name || ' ' || d.other_names)||' ('||d.local_id_no||')' srvs_provider_nm,  /**6**/
	a.prvdr_grp_id, prvdr_grp_name, appntmnt_status, cmnts, appntmnt_end_date, vst_id, appntmnt_no  /**13**/
    FROM hosp.appntmnt a LEFT OUTER JOIN hosp.srvs_types b ON a.srvs_type_id = b.type_id
    LEFT OUTER JOIN  hosp.prvdr_grps c ON a.prvdr_grp_id = c.prvdr_grp_id
    LEFT OUTER JOIN prs.prsn_names_nos d ON a.srvs_prvdr_prsn_id = d.person_id 
    WHERE appntmnt_id = $appntmnt_id";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

//APPOINTMENT DATA ITEMS
function insertAppntmntDataItems($appdt_itm_id, $appntmnt_id, $item_id, $qty, $cmnts, $usrID, $dateStr, $uomID, $src_srvstype_row_id) {
    global $orgID;
    $insSQL = "INSERT INTO hosp.appntmnt_data_items(
            appdt_itm_id, appntmnt_id, qty, cmnts, created_by, creation_date, 
            last_update_by, last_update_date, item_id, uom_id, src_srvstype_row_id)
            VALUES ($appdt_itm_id, $appntmnt_id, $qty,  '" . loc_db_escape_string($cmnts) . "',
            $usrID,'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "', $item_id, $uomID, $src_srvstype_row_id)";

    return execUpdtInsSQL($insSQL);
}

function updateAppntmntDataItems($appdt_itm_id, $appntmnt_id, $item_id, $qty, $cmnts, $usrID, $dateStr, $uomID, $src_srvstype_row_id) {
    $updtSQL = "UPDATE hosp.appntmnt_data_items
   SET item_id = $item_id, qty = $qty,  cmnts='" . loc_db_escape_string($cmnts) . "', last_update_by=$usrID, 
       last_update_date='" . $dateStr . "',
       uom_id = $uomID,
       src_srvstype_row_id = $src_srvstype_row_id
    WHERE appdt_itm_id = $appdt_itm_id";
    //var_dump($updtSQL);
    return execUpdtInsSQL($updtSQL);
}

function deleteAppntmntDataItems($appdt_itm_id) {
    $delSQL1 = "DELETE FROM hosp.appntmnt_data_items WHERE appdt_itm_id = $appdt_itm_id";

    return execUpdtInsSQL($delSQL1);
}

function deleteAppntmntDataItem($item_id, $appntmnt_id, $src_srvstype_row_id) {
    $delSQL1 = "DELETE FROM hosp.appntmnt_data_items WHERE appntmnt_id = $appntmnt_id AND item_id = $item_id "
            . " AND invc_hdr_id = -1 AND invc_det_ln_id = -1 AND is_billed = 'N' AND src_srvstype_row_id = $src_srvstype_row_id";

    return execUpdtInsSQL($delSQL1);
}

function getAppntmntDataItemsID() {
    $sqlStr = "SELECT nextval('hosp.appntmnt_data_items_appdt_itm_id_seq'::regclass);";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getAppntmntDataItemsDets($appntmntID) {
    $strSql = "SELECT appdt_itm_id, x.item_id, item_desc||' ('||item_code||')' itm_desc, qty, /**3**/
        cmnts, CASE WHEN is_billed = 'Y' THEN 'Yes' ELSE 'No' END is_billed, CASE WHEN is_paid = 'Y' THEN 'Yes' ELSE 'No' END is_paid, /**6**/
        x.invc_hdr_id, invc_number, x.invc_det_ln_id, appntmnt_id, /**10**/
        y.orgnl_selling_price /**11**/
  FROM hosp.appntmnt_data_items x INNER JOIN inv.inv_itm_list y ON x.item_id = y.item_id
  LEFT OUTER JOIN scm.scm_sales_invc_hdr z ON x.invc_hdr_id = z.invc_hdr_id
  LEFT OUTER JOIN scm.scm_sales_invc_det w ON x.invc_det_ln_id = w.invc_det_ln_id AND w.itm_id = x.item_id
  WHERE appntmnt_id = $appntmntID";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function getAppntmntInvcIDs($appntmntID) {
    $strSql = "SELECT distinct x.invc_hdr_id
  FROM hosp.appntmnt_data_items x INNER JOIN inv.inv_itm_list y ON x.item_id = y.item_id
  LEFT OUTER JOIN scm.scm_sales_invc_hdr z ON x.invc_hdr_id = z.invc_hdr_id
  LEFT OUTER JOIN scm.scm_sales_invc_det w ON x.invc_det_ln_id = w.invc_det_ln_id AND w.itm_id = x.item_id
  WHERE appntmnt_id = $appntmntID";

    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getVisitAppntmntInvcIDs($vstID) {
    $strSql = "SELECT distinct x.invc_hdr_id FROM hosp.visit u 
        INNER JOIN hosp.appntmnt v ON u.vst_id = v.vst_id 
        INNER JOIN hosp.appntmnt_data_items x on v.appntmnt_id = x.appntmnt_id 
        INNER JOIN inv.inv_itm_list y ON x.item_id = y.item_id 
        LEFT OUTER JOIN scm.scm_sales_invc_hdr z ON x.invc_hdr_id = z.invc_hdr_id 
        LEFT OUTER JOIN scm.scm_sales_invc_det w ON x.invc_det_ln_id = w.invc_det_ln_id 
        AND w.itm_id = x.item_id WHERE z.invc_hdr_id > 0
        AND v.vst_id = $vstID";

    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getAppntmntDataItemsTbl($appntmntID, $searchFor, $searchIn, $offset, $limit_size, $searchAll, $sortBy) {
    $extra1 = "";

    if ($searchAll == true) {
        $extra1 = "or 1 = 1";
    }

    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Item Code") {
        $whrcls .= " AND (y.item_code ilike '" . loc_db_escape_string($searchFor) .
                "') ";
    }

    if ($searchIn == "Item Description") {
        $whrcls .= " AND (y.item_desc ilike '" . loc_db_escape_string($searchFor) .
                "') ";
    }
    $strSql = "SELECT appdt_itm_id, x.item_id, item_desc||' ('||item_code||')' itm_desc, qty, cmnts, /**4**/
                                CASE WHEN is_billed = 'Y' THEN 'Yes' ELSE 'No' END is_billed, CASE WHEN is_paid = 'Y' THEN 'Yes' ELSE 'No' END is_paid, /**6**/
                                x.invc_hdr_id, invc_number, x.invc_det_ln_id, appntmnt_id, uom_id, /**11*/
                                CASE WHEN w.invc_det_ln_id > 0 THEN w.unit_selling_price ELSE coalesce(y.orgnl_selling_price,0) END unit_price /**12**/
				  FROM hosp.appntmnt_data_items x INNER JOIN inv.inv_itm_list y ON x.item_id = y.item_id
				  LEFT OUTER JOIN scm.scm_sales_invc_hdr z ON x.invc_hdr_id = z.invc_hdr_id
				  LEFT OUTER JOIN scm.scm_sales_invc_det w ON x.invc_det_ln_id = w.invc_det_ln_id AND w.itm_id = x.item_id
				  WHERE appntmnt_id =  $appntmntID AND (1 = 1 " . $extra1 . " " . $whrcls .
            ") ORDER BY 3 ASC LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    //var_dump($strSql);
    return $result;
}

function getAppntmntDataItemsTblTtl($appntmntID, $searchFor, $searchIn, $searchAll) {
    $extra1 = "";
    $extra2 = "";

    if ($searchAll == true) {
        $extra1 = "or 1 = 1";
    }

    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Item Code") {
        $whrcls .= " AND (y.item_code ilike '" . loc_db_escape_string($searchFor) .
                "') ";
    }

    if ($searchIn == "Item Description") {
        $whrcls .= " AND (y.item_desc ilike '" . loc_db_escape_string($searchFor) .
                "') ";
    }

    $strSql = "SELECT count(1) 
				  FROM hosp.appntmnt_data_items x INNER JOIN inv.inv_itm_list y ON x.item_id = y.item_id
				  LEFT OUTER JOIN scm.scm_sales_invc_hdr z ON x.invc_hdr_id = z.invc_hdr_id
				  LEFT OUTER JOIN scm.scm_sales_invc_det w ON x.invc_det_ln_id = w.invc_det_ln_id AND w.itm_id = x.item_id
				  WHERE appntmnt_id = $appntmntID AND (1 = 1 " . $extra1 . "" . $whrcls . $extra2 . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

//APPOINTMENT DATA
function get_AllAppointmentsTtl($qStrtDte, $qEndDte, $srvsTypeSrchIn, $statusSrchIn, /* $branchSrchIn, */ $searchFor, $searchIn, $orgID, $searchAll) {
    $extra1 = "";
    global $prsnid;

    if ($searchAll == true) {
        $extra1 = "or 1 = 1";
    }

    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Appointment Number") {
        $whrcls = " AND (appntmnt_no ilike '" . loc_db_escape_string($searchFor) . "')";
    } if ($searchIn == "ID/Full Name") {
        $whrcls = " AND (y.local_id_no ilike '" . loc_db_escape_string($searchFor) . "' or trim(y.title || ' ' || y.sur_name || " .
                "', ' || y.first_name || ' ' || y.other_names) ilike '" . loc_db_escape_string($searchFor) . "')";
    }

    if ($statusSrchIn == "All Statuses") {
        $whrcls .= " and 1 = 1";
    } else {
        $whrcls .= "  and appntmnt_status = '$statusSrchIn'";
    }

    /* if ($srvsTypeSrchIn == "All Service Types") {
      $whrcls .= " and 1 = 1";
      } else {
      $whrcls .= " and type_name||'('||sys_code||')' = '" . loc_db_escape_string($srvsTypeSrchIn) . "'";
      } */

    if ($qStrtDte != "") {
        $whrcls .= " AND (appntmnt_date >= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qStrtDte)) . "')";
    }
    if ($qEndDte != "") {
        $whrcls .= " AND (appntmnt_date <= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qEndDte)) . "')";
    }

    $strSql = "SELECT count(p.*) FROM " .
            "(SELECT DISTINCT appntmnt_id, appntmnt_no, trim(y.title || ' ' || y.sur_name ||', ' || y.first_name || ' ' || y.other_names)||' ('||y.local_id_no||')' customer, to_char(to_timestamp(appntmnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') appntmnt_date, 
	type_name||'('||sys_code||')' srvs_type, CASE WHEN UPPER(prvdr_type) = 'G' THEN 'Group' ELSE 'Individual' END prvdr_type, 
	CASE WHEN UPPER(prvdr_type) = 'G' THEN prvdr_grp_name ELSE trim(d.title || ' ' || d.sur_name ||', ' || d.first_name || ' ' || d.other_names)||' ('||d.local_id_no||')' END srvs_provider, 
	appntmnt_status, a.cmnts, CASE WHEN appntmnt_end_date = '' THEN '' ELSE to_char(to_timestamp(appntmnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') END appntmnt_end_date,
	 a.vst_id, a.srvs_type_id, srvs_prvdr_prsn_id, a.prvdr_grp_id
         FROM hosp.visit x INNER JOIN prs.prsn_names_nos y ON y.person_id = x.prsn_id
                INNER JOIN hosp.appntmnt a ON x.vst_id = a.vst_id
		INNER JOIN hosp.srvs_prvdrs e ON e.srvs_type_id = a.srvs_type_id AND (e.prvdr_grp_id = a.prvdr_grp_id OR e.prsn_id = a.srvs_prvdr_prsn_id)
		/*AND now() >= to_timestamp(appntmnt_date,'YYYY-MM-DD HH24:MI:SS')*/ AND to_timestamp(appntmnt_date,'YYYY-MM-DD HH24:MI:SS') 
		BETWEEN to_timestamp(start_date,'YYYY-MM-DD HH24:MI:SS') AND to_timestamp(end_date,'YYYY-MM-DD HH24:MI:SS')
		LEFT OUTER JOIN hosp.srvs_types b ON a.srvs_type_id = b.type_id
		LEFT OUTER JOIN  hosp.prvdr_grps c ON a.prvdr_grp_id = c.prvdr_grp_id
		LEFT OUTER JOIN prs.prsn_names_nos d ON a.srvs_prvdr_prsn_id = d.person_id 
		WHERE (e.prsn_id = $prsnid AND pasn.get_prsn_siteid($prsnid) = x.branch_id) AND (( 1 = 1" . $extra1 . ")" . $whrcls . "))p";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_AllAppointments($qStrtDte, $qEndDte, $srvsTypeSrchIn, $statusSrchIn, /* $branchSrchIn, */ $searchFor, $searchIn, $offset, $limit_size, $orgID, $searchAll, $sortBy) {
    $extra1 = "";
    global $prsnid;

    if ($searchAll == true) {
        $extra1 = "or 1 = 1";
    }

    $whrcls = "";
    $ordrBy = "";
    if ($searchIn == "Appointment Number") {
        $whrcls = " AND (appntmnt_no ilike '" . loc_db_escape_string($searchFor) . "')";
    } if ($searchIn == "ID/Full Name") {
        $whrcls = " AND (y.local_id_no ilike '" . loc_db_escape_string($searchFor) . "' or trim(y.title || ' ' || y.sur_name || " .
                "', ' || y.first_name || ' ' || y.other_names) ilike '" . loc_db_escape_string($searchFor) . "')";
    }

    if ($statusSrchIn == "All Statuses") {
        $whrcls .= " and 1 = 1";
    } else {
        $whrcls .= "  and appntmnt_status = '$statusSrchIn'";
    }

    /* if ($srvsTypeSrchIn == "All Service Types") {
      $whrcls .= " and 1 = 1";
      } else {
      $whrcls .= " and type_name||'('||sys_code||')' = '" . loc_db_escape_string($srvsTypeSrchIn) . "'";
      } */

    if ($qStrtDte != "") {
        $whrcls .= " AND (appntmnt_date >= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qStrtDte)) . "')";
    }
    if ($qEndDte != "") {
        $whrcls .= " AND (appntmnt_date <= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qEndDte)) . "')";
    }

    if ($sortBy == "Appointment Number ASC") {
        $ordrBy = "appntmnt_no ASC";
    } else if ($sortBy == "Date Added DESC") {
        $ordrBy = "a.creation_date DESC";
    } else if ($sortBy == "Appointment Status ASC") {
        $ordrBy = "appntmnt_status ASC";
    }

    $strSql = "SELECT DISTINCT appntmnt_id, appntmnt_no, trim(y.title || ' ' || y.sur_name ||', ' || y.first_name || ' ' || y.other_names)||' ('||y.local_id_no||')' customer, /**2**/
        to_char(to_timestamp(appntmnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') appntmnt_date, /**3**/ 
	type_name||'('||sys_code||')' srvs_type, CASE WHEN UPPER(prvdr_type) = 'G' THEN 'Group' ELSE 'Individual' END prvdr_type, /**5**/
	/*CASE WHEN UPPER(prvdr_type) = 'G' THEN prvdr_grp_name ELSE trim(d.title || ' ' || d.sur_name ||', ' || d.first_name || ' ' || d.other_names)||' ('||d.local_id_no||')' END srvs_provider, */
        CASE WHEN UPPER(prvdr_type) = 'G' THEN prvdr_grp_name||CASE WHEN '('||COALESCE(prs.get_prsn_name(srvs_prvdr_prsn_id),'')||')' = '()' THEN '' ELSE ' ('||COALESCE(prs.get_prsn_name(srvs_prvdr_prsn_id),'')||')' END
        ELSE prs.get_prsn_name(srvs_prvdr_prsn_id) END srvs_provider, /**6**/
	appntmnt_status, a.cmnts, CASE WHEN appntmnt_end_date = '' THEN '' ELSE to_char(to_timestamp(appntmnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') END appntmnt_end_date,  /**9**/
	 a.vst_id, a.srvs_type_id, srvs_prvdr_prsn_id, a.prvdr_grp_id, sys_code,  /**14**/
         CASE WHEN check_in_date = '' THEN '' ELSE to_char(to_timestamp(check_in_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') END check_in_date,  /**15**/
         a.creation_date
		FROM hosp.visit x INNER JOIN prs.prsn_names_nos y ON y.person_id = x.prsn_id
                INNER JOIN hosp.appntmnt a ON x.vst_id = a.vst_id
		INNER JOIN hosp.srvs_prvdrs e ON e.srvs_type_id = a.srvs_type_id AND (e.prvdr_grp_id = a.prvdr_grp_id OR e.prsn_id = a.srvs_prvdr_prsn_id)
		/*AND now() >= to_timestamp(appntmnt_date,'YYYY-MM-DD HH24:MI:SS')*/ AND to_timestamp(appntmnt_date,'YYYY-MM-DD HH24:MI:SS') 
		BETWEEN to_timestamp(start_date,'YYYY-MM-DD HH24:MI:SS') AND to_timestamp(end_date,'YYYY-MM-DD HH24:MI:SS')
		LEFT OUTER JOIN hosp.srvs_types b ON a.srvs_type_id = b.type_id
		LEFT OUTER JOIN  hosp.prvdr_grps c ON a.prvdr_grp_id = c.prvdr_grp_id
		LEFT OUTER JOIN prs.prsn_names_nos d ON a.srvs_prvdr_prsn_id = d.person_id 
		WHERE (e.prsn_id = $prsnid AND pasn.get_prsn_siteid($prsnid) = x.branch_id) AND (( 1 = 1 " . $extra1 . ")" . $whrcls .
            ") ORDER BY " . $ordrBy . " LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    //var_dump($strSql);
    return $result;
}

function getSrvsTypeCode($appntmntID) {
    $sqlStr = "SELECT sys_code
        FROM hosp.visit x INNER JOIN hosp.appntmnt a ON x.vst_id = a.vst_id 
        INNER JOIN hosp.srvs_types b ON a.srvs_type_id = b.type_id
        WHERE appntmnt_id = $appntmntID";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return '';
}

function getSrvsTypeNm($appntmntID) {
    $sqlStr = "SELECT type_name
        FROM hosp.visit x INNER JOIN hosp.appntmnt a ON x.vst_id = a.vst_id 
        INNER JOIN hosp.srvs_types b ON a.srvs_type_id = b.type_id
        WHERE appntmnt_id = $appntmntID";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return '';
}

function getSrvsTypeCodeFromID($srvs_type_id) {
    $sqlStr = "SELECT sys_code FROM hosp.srvs_types WHERE type_id = $srvs_type_id";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return '';
}

function get_RptsDet($pkeyID) {
    $strSql = "SELECT a.report_id, a.report_name, a.report_desc, a.rpt_sql_query, a.owner_module, 
       a.rpt_or_sys_prcs, a.is_enabled, a.cols_to_group, a.cols_to_count, a.cols_to_sum, 
       a.cols_to_average, a.cols_to_no_frmt, a.output_type, a.portrait_lndscp, 
       a.rpt_layout, a.imgs_col_nos, a.csv_delimiter, a.process_runner, a.is_seeded_rpt, 
       a.jrxml_file_name, a.pre_rpt_sql_query, a.pst_rpt_sql_query
    FROM rpt.rpt_reports a
    WHERE (a.report_id = $pkeyID)";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function prsn_Record_Exist($pkID) {
    $sqlStr = "select person_id FROM self.self_prsn_names_nos WHERE (person_id=$pkID)";
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while (loc_db_num_rows($result) > 0) {
        return true;
    }
    return false;
}

function get_AllMedications($cnsltnId, $appntmntID) {
    $andCls = " AND cnsltn_id = $cnsltnId";

    if ($cnsltnId <= 0) {
        $andCls = "AND x.dest_appntmnt_id = $appntmntID";
    }
    $strSql = "SELECT prscptn_id, x.itm_id, y.item_code||' ('||item_desc||')', /**2**/
        dose_qty, /*inv.get_uom_name(dose_uom_id)*/ dose_uom, /**4**/
	dsge_freqncy_no, dsge_freqncy_uom, /**6**/
	tot_duratn, tot_duratn_uom, dose_form,  sub_allowed, /**10**/
        doc_cmnts, dspnsd_status, pharm_cmnts, admin_times, /**14**/
	cnsltn_id, dest_appntmnt_id, rqstr_aprvl_status, start_date, strength, /**19**/
	end_date, refill_qty, ext_prscrbr_name, ext_prscrbr_contact_addrss, /**24**/
	int_prscrbr_prsn_id
FROM hosp.prscptn x, inv.inv_itm_list y
WHERE x.itm_id = y.item_id $andCls ORDER BY 1";
    $result = executeSQLNoParams($strSql);
    //echo $strSql;
    return $result;
}

function get_AllInvestigations($cnsltnId, $appntmntID) {
    $andCls = " AND cnsltn_id = $cnsltnId";

    if ($cnsltnId <= 0) {
        $andCls = "AND x.dest_appntmnt_id = $appntmntID";
    }
    $strSql = "SELECT invstgtn_id, x.invstgtn_list_id, invstgtn_name, doc_cmnts, /**3**/
        lab_results, lab_cmnts, lab_loc, invstgtn_desc, /**7**/
       dest_appntmnt_id, rcmdd_itm_desc, cnsltn_id, actl_itm_id, /**11**/
       do_inhouse /**12**/
  FROM hosp.invstgtn x, hosp.invstgtn_list y
  where x.invstgtn_list_id = y.invstgtn_list_id $andCls";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AllDiagnosis($cnsltnId) {
    $strSql = "SELECT diag_id, x.disease_id, disease_name,  cmnts
  FROM hosp.cnsltn_diagn_types x, hosp.diseases y
  WHERE x.disease_id = y.disease_id
  AND cnsltn_id = $cnsltnId";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AppointmentDataDet($appntmntid) {
    $strSql = "SELECT appntmnt_id, appntmnt_no, trim(y.title || ' ' || y.sur_name ||', ' || y.first_name || ' ' || y.other_names)||' ('||y.local_id_no||')' customer, /** 2 **/
        to_char(to_timestamp(appntmnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') appntmnt_date, /**3**/
        type_name||'('||sys_code||')' srvs_type, CASE WHEN UPPER(prvdr_type) = 'G' THEN 'Group' ELSE 'Individual' END prvdr_type, /**5**/
	CASE WHEN UPPER(prvdr_type) = 'G' THEN prvdr_grp_name||' ('||COALESCE(prs.get_prsn_name(srvs_prvdr_prsn_id),'')||')' ELSE prs.get_prsn_name(srvs_prvdr_prsn_id) END srvs_provider, /**6**/
	appntmnt_status, a.cmnts, CASE WHEN appntmnt_end_date = '' THEN '' ELSE to_char(to_timestamp(appntmnt_end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') END appntmnt_end_date, /**9**/
	 a.vst_id, a.srvs_type_id, srvs_prvdr_prsn_id, a.prvdr_grp_id, /**13**/
         y.local_id_no, y.gender, y.date_of_birth, x.vstr_type, x.trnsctn_no, /**18**/
         y.person_id, y.img_location /**20**/,
         CASE WHEN check_in_date = '' THEN '' ELSE to_char(to_timestamp(check_in_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') END check_in_date,  /**21**/
         sys_code, b.type_name /**23**/
		FROM hosp.visit x INNER JOIN prs.prsn_names_nos y ON y.person_id = x.prsn_id
                INNER JOIN hosp.appntmnt a ON x.vst_id = a.vst_id
		INNER JOIN hosp.srvs_prvdrs e ON e.srvs_type_id = a.srvs_type_id AND (e.prvdr_grp_id = a.prvdr_grp_id OR e.prsn_id = a.srvs_prvdr_prsn_id)
		/*AND now() >= to_timestamp(appntmnt_date,'YYYY-MM-DD HH24:MI:SS')*/ AND to_timestamp(appntmnt_date,'YYYY-MM-DD HH24:MI:SS') 
		BETWEEN to_timestamp(start_date,'YYYY-MM-DD HH24:MI:SS') AND to_timestamp(end_date,'YYYY-MM-DD HH24:MI:SS')
		INNER JOIN hosp.srvs_types b ON a.srvs_type_id = b.type_id
		INNER JOIN  hosp.prvdr_grps c ON a.prvdr_grp_id = c.prvdr_grp_id 
                AND appntmnt_id = $appntmntid";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AppointmentConsultationData($appntmntid) {
    $strSql = "SELECT cnsltn_id, spch_dictn, physical_examination, cmnts
        FROM hosp.medcl_cnsltn WHERE appntmnt_id = $appntmntid";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_VisitVitalsData($vstID) {
    $sqlStr1 = "SELECT a.appntmnt_id
        FROM hosp.visit x INNER JOIN hosp.appntmnt a ON x.vst_id = a.vst_id 
        INNER JOIN hosp.srvs_types b ON a.srvs_type_id = b.type_id
        WHERE sys_code = 'VS-0001' AND x.vst_id = $vstID limit 1";
    $vtlsAppntmntID = -1;
    $result1 = executeSQLNoParams($sqlStr1);
    while ($row1 = loc_db_fetch_array($result1)) {
        $vtlsAppntmntID = $row1[0];
    }



    $strSql = "SELECT vtl_id, weight, height, bmi, bmi_status, bp_systlc, bp_diastlc, bp_status,
        pulse,  body_tmp, tmp_loc, resptn, oxgn_satn, head_circum, waist_circum,   
        bowel_actn, cmnts, x.appntmnt_id
    FROM hosp.vitals x WHERE appntmnt_id = $vtlsAppntmntID";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AppntmntVitalsData($appntmntID) {

    $strSql = "SELECT vtl_id, weight, height, bmi, bmi_status, bp_systlc, bp_diastlc, bp_status,
        pulse,  body_tmp, tmp_loc, resptn, oxgn_satn, head_circum, waist_circum,   
        bowel_actn, cmnts, x.appntmnt_id
    FROM hosp.vitals x WHERE appntmnt_id = $appntmntID";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getCustAge($dob) {
    $strSql = "select EXTRACT(YEAR FROM age(cast('$dob' as date)))";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getMonthAge($dob) {
    $strSql = "select EXTRACT(MONTH FROM age(cast('$dob' as date)))";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getAppointmentCnsltn($appntmntID) {
    $strSql = "SELECT cnsltn_id, patient_complaints, physical_examination, cmnts, appntmnt_id, /**4**/
        admission_cmnts, /**5**/
        CASE WHEN admission_checkin_date = '' THEN admission_checkin_date ELSE to_char(to_timestamp(admission_checkin_date,'YYYY-MM-DD'),'DD-Mon-YYYY') END admission_checkin_date, /**6**/
        admission_no_of_days /**7**/
        FROM hosp.medcl_cnsltn WHERE appntmnt_id = $appntmntID";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

//MANAGE SERVICE TYPES
function getCreditSrvcOffrdsTbl($isEnabled, $searchFor, $searchIn, $offset, $limit_size, $searchAll, $sortBy) {
    $extra1 = "";

    if ($searchAll == true) {
        $extra1 = "1 = 1";
    }

    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Service Type Description") {
        $whrcls = " AND (a.type_desc ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Service Type Name") {
        $whrcls = " AND (type_name ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Inventory Item/Service") {
        $whrcls = " AND (itm_id IN (SELECT distinct item_id FROM inv.inv_itm_list WHERE item_desc ilike '" . loc_db_escape_string($searchFor) . "'
		 OR item_code ilike '" . loc_db_escape_string($searchFor) . "'))";
    } else if ($searchIn == "System Code") {
        $whrcls = " AND (sys_code ilike '" . loc_db_escape_string($searchFor) . "')";
    }

    if ($isEnabled == "true") {
        $whrcls .= " and upper(a.is_enabled) = '1'";
    }

    $ordrBy = "a.type_name ASC";

    $strSql = "SELECT type_id, type_name, type_desc, itm_id,  sys_code, is_enabled, inv.get_invitm_name(itm_id) itm, telemedicine_enabled
  FROM hosp.srvs_types a  WHERE (1 = 1 AND (" . $extra1 . ")" . $whrcls .
            ") ORDER BY " . $ordrBy . " LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    //var_dump($strSql);
    return $result;
}

function getCreditSrvcOffrdsTblTtl($isEnabled, $searchFor, $searchIn, $searchAll) {
    $extra1 = "";
    $extra2 = "";

    if ($searchAll == true) {
        $extra1 = "1 = 1";
    }

    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Service Type Description") {
        $whrcls = " AND (a.type_desc ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Service Type Name") {
        $whrcls = " AND (type_name ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Inventory Item/Service") {
        $whrcls = " AND (itm_id IN (SELECT distinct item_id FROM inv.inv_itm_list WHERE item_desc ilike '" . loc_db_escape_string($searchFor) . "'
		 OR item_code ilike '" . loc_db_escape_string($searchFor) . "'))";
    } else if ($searchIn == "System Code") {
        $whrcls = " AND (sys_code ilike '" . loc_db_escape_string($searchFor) . "')";
    }

    if ($isEnabled == "true") {
        $whrcls .= " and upper(a.is_enabled) = '1'";
    }

    $strSql = "SELECT count(1) " .
            "FROM hosp.srvs_types a WHERE (1 = 1 AND (" . $extra1 . ")" . $whrcls . $extra2 .
            ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function createCreditSrvcOffrds($typeId, $typeName, $typeDesc, $itmId, $sysCode, $isEnabled, $dateStr, $telemedicine_enabled) {
    global $usrID;
    global $orgID;

    $insSQL = "INSERT INTO hosp.srvs_types(
            type_id, type_name, type_desc, itm_id, 
            sys_code, is_enabled, created_by, creation_date, last_update_by, 
            last_update_date, telemedicine_enabled)
    VALUES ($typeId, '" . loc_db_escape_string($typeName) . "', '" . loc_db_escape_string($typeDesc) . "', " .
            $itmId . ", '" . loc_db_escape_string($sysCode) . "', '" . loc_db_escape_string($isEnabled) . "', $usrID, '" .
            $dateStr . "', $usrID, '" . $dateStr . "','" . cnvrtBoolToBitStr($telemedicine_enabled) . "')";

    return execUpdtInsSQL($insSQL);
}

function updateCreditSrvcOffrds($typeId, $typeName, $typeDesc, $itmId, $sysCode, $isEnabled, $dateStr, $telemedicine_enabled) {
    global $usrID;
    global $orgID;

    $insSQL = "UPDATE hosp.srvs_types SET
            type_name ='" . loc_db_escape_string($typeName) . "', type_desc ='" . loc_db_escape_string($typeDesc) . "',
			itm_id =" . $itmId . ", sys_code ='" . loc_db_escape_string($sysCode) . "', is_enabled ='" . loc_db_escape_string($isEnabled) . "',
           last_update_by =" . $usrID . ", last_update_date = '" . $dateStr . "', telemedicine_enabled = '" . cnvrtBoolToBitStr($telemedicine_enabled) . "'  WHERE type_id = $typeId";

    return execUpdtInsSQL($insSQL);
}

function deleteCreditSrvcOffrds($typeId) {
    $delSQL1 = "DELETE FROM hosp.srvs_types WHERE type_id = $typeId";
    return execUpdtInsSQL($delSQL1);
}

function isSrvcOffrdInActiveUse($typeId) {
    $cnt = 0;
    $strSql1 = "SELECT count(*) FROM hosp.visit WHERE srvs_type_id = $typeId";

    $strSql2 = "SELECT count(*) FROM hosp.srvs_prvdrs WHERE srvs_type_id = $typeId";

    $strSql3 = "SELECT count(*) FROM hosp.prvdr_grp_srvs WHERE type_id = $typeId";

    $result1 = executeSQLNoParams($strSql1);
    $result2 = executeSQLNoParams($strSql2);
    $result3 = executeSQLNoParams($strSql3);

    while ($row1 = loc_db_fetch_array($result1)) {
        if ((int) $row1[0] > 0) {
            $cnt += 1;
        }
    }

    while ($row2 = loc_db_fetch_array($result2)) {
        if ((int) $row2[0] > 0) {
            $cnt += 1;
        }
    }

    while ($row3 = loc_db_fetch_array($result3)) {
        if ((int) $row3[0] > 0) {
            $cnt += 1;
        }
    }

    if ((int) $cnt > 0) {
        return true;
    } else {
        return false;
    }
    return false;
}

function getSrvcOffrdID() {

    $sqlStr = "SELECT nextval('hosp.srvs_types_type_id_seq');";

    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }

    return -1;
}

function getPrsnJobNm($prsnID) {
//echo "uname_".$username;
    $sqlStr = "select org.get_job_name(pasn.get_prsn_jobid($prsnID))";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

//DIAGNOSIS LIST
//MANAGE SERVICE TYPES
function getCreditDiagnsListsTbl($isEnabled, $searchFor, $searchIn, $offset, $limit_size, $searchAll, $sortBy) {
    $extra1 = "";

    if ($searchAll == true) {
        $extra1 = "1 = 1";
    }

    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Diagnosis Description") {
        $whrcls = " AND (a.disease_desc ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Diagnosis Name") {
        $whrcls = " AND (disease_name ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Symptoms") {
        $whrcls = " AND (symtms ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Treatment Medications") {
        $whrcls = " AND (pssbl_trtmnt_mdctn ilike '" . loc_db_escape_string($searchFor) . "')";
    }

    if ($isEnabled == "true") {
        $whrcls .= " and upper(a.is_enabled) = '1'";
    }

    $ordrBy = "a.disease_name ASC";

    $strSql = "SELECT disease_id, disease_name, disease_desc, symtms,  pssbl_trtmnt_mdctn, is_enabled
  FROM hosp.diseases a  WHERE (1 = 1 AND (" . $extra1 . ")" . $whrcls .
            ") ORDER BY " . $ordrBy . " LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    //var_dump($strSql);
    return $result;
}

function getCreditDiagnsListsTblTtl($isEnabled, $searchFor, $searchIn, $searchAll) {
    $extra1 = "";
    $extra2 = "";

    if ($searchAll == true) {
        $extra1 = "1 = 1";
    }

    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Diagnosis Description") {
        $whrcls = " AND (a.disease_desc ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Diagnosis Name") {
        $whrcls = " AND (disease_name ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Symptoms") {
        $whrcls = " AND (symtms ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Treatment Medications") {
        $whrcls = " AND (pssbl_trtmnt_mdctn ilike '" . loc_db_escape_string($searchFor) . "')";
    }

    if ($isEnabled == "true") {
        $whrcls .= " and upper(a.is_enabled) = '1'";
    }

    $strSql = "SELECT count(1) " .
            "FROM hosp.diseases a WHERE (1 = 1 AND (" . $extra1 . ")" . $whrcls . $extra2 .
            ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function createCreditDiagnsLists($diseaseId, $diseaseName, $diseaseDesc, $symtms, $psblTrtmnt, $isEnabled, $dateStr) {
    global $usrID;
    global $orgID;

    $insSQL = "INSERT INTO hosp.diseases(
            disease_id, disease_name, disease_desc, symtms, 
            pssbl_trtmnt_mdctn, is_enabled, created_by, creation_date, last_update_by, 
            last_update_date)
    VALUES ($diseaseId, '" . loc_db_escape_string($diseaseName) . "', '" . loc_db_escape_string($diseaseDesc) . "', '" .
            loc_db_escape_string($symtms) . "', '" . loc_db_escape_string($psblTrtmnt) . "', '" . loc_db_escape_string($isEnabled) . "', $usrID, '" .
            $dateStr . "', $usrID, '" . $dateStr . "')";

    return execUpdtInsSQL($insSQL);
}

function updateCreditDiagnsLists($diseaseId, $diseaseName, $diseaseDesc, $symtms, $psblTrtmnt, $isEnabled, $dateStr) {
    global $usrID;
    global $orgID;

    $insSQL = "UPDATE hosp.diseases SET
            disease_name ='" . loc_db_escape_string($diseaseName) . "', disease_desc ='" . loc_db_escape_string($diseaseDesc) . "',
			symtms ='" . loc_db_escape_string($symtms) . "', pssbl_trtmnt_mdctn ='" . loc_db_escape_string($psblTrtmnt) . "', is_enabled ='" . loc_db_escape_string($isEnabled) . "',
           last_update_by =" . $usrID . ", last_update_date = '" . $dateStr . "' WHERE disease_id = $diseaseId";

    return execUpdtInsSQL($insSQL);
}

function deleteCreditDiagnsLists($diseaseId) {
    $delSQL1 = "DELETE FROM hosp.diseases WHERE disease_id = $diseaseId";
    return execUpdtInsSQL($delSQL1);
}

function isDiagnsListInActiveUse($diseaseId) {
    $cnt = 0;
    $strSql1 = "SELECT count(*) FROM hosp.cnsltn_diagn_types WHERE disease_id = $diseaseId";

    $result1 = executeSQLNoParams($strSql1);

    while ($row1 = loc_db_fetch_array($result1)) {
        if ((int) $row1[0] > 0) {
            $cnt += 1;
        }
    }


    if ((int) $cnt > 0) {
        return true;
    } else {
        return false;
    }
    return false;
}

function getDiagnsListID() {

    $sqlStr = "SELECT nextval('hosp.diseases_disease_id_seq');";

    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }

    return -1;
}

//INVESTIGATION LIST
function getCreditInvstgtnListsTbl($isEnabled, $searchFor, $searchIn, $offset, $limit_size, $searchAll, $sortBy) {
    $extra1 = "";

    if ($searchAll == true) {
        $extra1 = "1 = 1";
    }

    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Description") {
        $whrcls = " AND (a.invstgtn_desc ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Investigation Name") {
        $whrcls = " AND (invstgtn_name ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Investigation Type") {
        $whrcls = " AND (invstgtn_type ilike '" . loc_db_escape_string($searchFor) . "')";
    }

    if ($isEnabled == "true") {
        $whrcls .= " and upper(a.is_enabled) = '1'";
    }

    $ordrBy = "a.invstgtn_name ASC";

    $strSql = "SELECT invstgtn_list_id, invstgtn_name, invstgtn_desc, is_enabled, srvs_itm_id, invstgtn_type
  FROM hosp.invstgtn_list a  WHERE (1 = 1 AND (" . $extra1 . ")" . $whrcls .
            ") ORDER BY " . $ordrBy . " LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    //var_dump($strSql);
    return $result;
}

function getCreditInvstgtnListsTblTtl($isEnabled, $searchFor, $searchIn, $searchAll) {
    $extra1 = "";
    $extra2 = "";

    if ($searchAll == true) {
        $extra1 = "1 = 1";
    }

    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Description") {
        $whrcls = " AND (a.invstgtn_desc ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Investigation Name") {
        $whrcls = " AND (invstgtn_name ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Investigation Type") {
        $whrcls = " AND (invstgtn_type ilike '" . loc_db_escape_string($searchFor) . "')";
    }

    if ($isEnabled == "true") {
        $whrcls .= " and upper(a.is_enabled) = '1'";
    }

    $strSql = "SELECT count(1) " .
            "FROM hosp.invstgtn_list a WHERE (1 = 1 AND (" . $extra1 . ")" . $whrcls . $extra2 .
            ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function createCreditInvstgtnLists($invstgtnListId, $invstgtnName, $invstgtnDesc, $isEnabled, $dateStr, $srvs_itm_id, $invstgtn_type) {
    global $usrID;
    global $orgID;

    $insSQL = "INSERT INTO hosp.invstgtn_list(
            invstgtn_list_id, invstgtn_name, invstgtn_desc, is_enabled, created_by, creation_date, last_update_by, 
            last_update_date, srvs_itm_id, invstgtn_type)
    VALUES ($invstgtnListId, '" . loc_db_escape_string($invstgtnName) . "', '" . loc_db_escape_string($invstgtnDesc) . "',
	'" . loc_db_escape_string($isEnabled) . "', $usrID, '" .  $dateStr . "', $usrID, '" . $dateStr . "', $srvs_itm_id, '$invstgtn_type')";

    return execUpdtInsSQL($insSQL);
}

function updateCreditInvstgtnLists($invstgtnListId, $invstgtnName, $invstgtnDesc, $isEnabled, $dateStr, $srvs_itm_id, $invstgtn_type) {
    global $usrID;
    global $orgID;

    $insSQL = "UPDATE hosp.invstgtn_list SET
            invstgtn_name ='" . loc_db_escape_string($invstgtnName) . "', invstgtn_desc ='" . loc_db_escape_string($invstgtnDesc) . "',
			is_enabled ='" . loc_db_escape_string($isEnabled) . "',
           last_update_by =" . $usrID . ", last_update_date = '" . $dateStr . "', srvs_itm_id = $srvs_itm_id, invstgtn_type = '$invstgtn_type' WHERE invstgtn_list_id = $invstgtnListId";

    return execUpdtInsSQL($insSQL);
}

function deleteCreditInvstgtnLists($invstgtnListId) {
    $delSQL1 = "DELETE FROM hosp.invstgtn_list WHERE invstgtn_list_id = $invstgtnListId";
    return execUpdtInsSQL($delSQL1);
}

function isInvstgtnListInActiveUse($invstgtnListId) {
    $cnt = 0;
    $strSql1 = "SELECT count(*) FROM hosp.invstgtn WHERE invstgtn_list_id = $invstgtnListId";

    $result1 = executeSQLNoParams($strSql1);

    while ($row1 = loc_db_fetch_array($result1)) {
        if ((int) $row1[0] > 0) {
            $cnt += 1;
        }
    }


    if ((int) $cnt > 0) {
        return true;
    } else {
        return false;
    }
    return false;
}

function getInvstgtnListID() {

    $sqlStr = "SELECT nextval('hosp.invstgtn_list_invstgtn_list_id_seq');";

    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }

    return -1;
}

//SERVICE PROVIDIERS
//TABLE hosp.prvdr_grps
function createCreditPrvdrGroups($prvdrGrpId, $prvdrGrpName, $prvdrGrpDesc, $main_srvc_type_id, $max_daily_appntmnts, $cost_itm_id, $isEnabled, $dateStr) {
    global $usrID;
    //global $dateStr;
    global $orgID;

    $insSQL = "INSERT INTO hosp.prvdr_grps(prvdr_grp_id,
            prvdr_grp_name, prvdr_grp_desc, enabled_flag, main_srvc_type_id, max_daily_appntmnts,
			created_by, creation_date, last_update_by, last_update_date, cost_itm_id)
    VALUES ($prvdrGrpId,'" . loc_db_escape_string($prvdrGrpName) . "','" . loc_db_escape_string($prvdrGrpDesc) .
            "','" . $isEnabled . "',$main_srvc_type_id, $max_daily_appntmnts," . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "', $cost_itm_id)";

    return execUpdtInsSQL($insSQL);
}

function updateCreditPrvdrGroups($prvdrGrpId, $prvdrGrpName, $prvdrGrpDesc, $main_srvc_type_id, $max_daily_appntmnts, $cost_itm_id, $isEnabled, $dateStr) {
    global $usrID;
    //global $dateStr;
    global $orgID;

    $insSQL = "UPDATE hosp.prvdr_grps SET
            prvdr_grp_name ='" . loc_db_escape_string($prvdrGrpName) . "', prvdr_grp_desc ='" . loc_db_escape_string($prvdrGrpDesc) . "',
			    main_srvc_type_id = $main_srvc_type_id, max_daily_appntmnts = $max_daily_appntmnts,
                enabled_flag ='" . $isEnabled . "',  last_update_by =" . $usrID . ",
             last_update_date = '" . $dateStr . "', cost_itm_id = $cost_itm_id WHERE prvdr_grp_id = $prvdrGrpId";

    return execUpdtInsSQL($insSQL);
}

function deleteCreditPrvdrGroups($prvdrGrpId) {
    //CASCADE DELETION
    $delSQL1 = "DELETE FROM hosp.prvdr_grps WHERE prvdr_grp_id = $prvdrGrpId";
    $delSQL2 = "DELETE FROM hosp.srvs_prvdrs WHERE prvdr_grp_id = $prvdrGrpId";
    $delSQL3 = "DELETE FROM hosp.prvdr_grp_srvs WHERE prvdr_grp_id = $prvdrGrpId";
    $delSQL4 = "DELETE FROM hosp.hosp.appntmnt WHERE prvdr_grp_id = $prvdrGrpId AND prvdr_grp_id > 0";


    execUpdtInsSQL($delSQL2);
    execUpdtInsSQL($delSQL3);
    execUpdtInsSQL($delSQL4);
    return execUpdtInsSQL($delSQL1);
}

function getCreditPrvdrGroupID() {

    $sqlStr = "SELECT nextval('hosp.prvdr_grps_prvdr_grp_id_seq');";

    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }

    return -1;
}

function getCreditPrvdrGroupsDets($prvdrGrpId) {
    $strSql = "SELECT prvdr_grp_id, prvdr_grp_name, prvdr_grp_desc, enabled_flag, main_srvc_type_id, max_daily_appntmnts,
	cur_days_appntmnts, cost_itm_id, inv.get_invitm_name(cost_itm_id), created_by, creation_date, last_update_by, last_update_date
  FROM hosp.prvdr_grps a  WHERE prvdr_grp_id = $prvdrGrpId";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function getCreditPrvdrGroupsTbl($isEnabled, $searchFor, $searchIn, $offset, $limit_size, $searchAll, $sortBy) {
    $extra1 = "";

    if ($searchAll == true) {
        $extra1 = "1 = 1";
    }

    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Group Name") {
        $whrcls = " AND (a.prvdr_grp_name ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Description") {
        $whrcls = " AND (prvdr_grp_desc ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Main Service Offered") {
        $whrcls = " AND main_srvc_type_id IN (SELECT distinct type_id FROM hosp.srvs_types x
		WHERE type_name ilike '" . loc_db_escape_string($searchFor) . "' OR type_desc ilike '" . loc_db_escape_string($searchFor) . "')";
    }

    if ($isEnabled == "true") {
        $whrcls .= " and upper(a.enabled_flag) = '1'";
    }

    $ordrBy = "a.last_update_date DESC";

    $strSql = "SELECT prvdr_grp_id, prvdr_grp_desc, prvdr_grp_name, enabled_flag, main_srvc_type_id, max_daily_appntmnts,
	cur_days_appntmnts, cost_itm_id
  FROM hosp.prvdr_grps a   WHERE (1 = 1 AND (" . $extra1 . ")" . $whrcls .
            ") ORDER BY " . $ordrBy . " LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    //var_dump($strSql);
    return $result;
}

function getCreditPrvdrGroupsTblTtl($isEnabled, $searchFor, $searchIn, $searchAll) {
    $extra1 = "";
    $extra2 = "";

    if ($searchAll == true) {
        $extra1 = "1 = 1";
    }

    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Group Name") {
        $whrcls = " AND (a.prvdr_grp_name ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Description") {
        $whrcls = " AND (prvdr_grp_desc ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Main Service Offered") {
        $whrcls = " AND main_srvc_type_id IN (SELECT distinct type_id FROM hosp.srvs_types x
		WHERE type_name ilike '" . loc_db_escape_string($searchFor) . "' OR type_desc ilike '" . loc_db_escape_string($searchFor) . "')";
    }

    if ($isEnabled == "true") {
        $whrcls .= " and upper(a.enabled_flag) = '1'";
    }

    $strSql = "SELECT count(1) " .
            "FROM hosp.prvdr_grps a WHERE (1 = 1 AND (" . $extra1 . ")" . $whrcls . $extra2 .
            ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function createCreditPrvdrGroupPersons($prvdr_id, $srvs_type_id, $prvdrGrpId, $providerType, $prsn_cstmr_id, $max_daily_appntmnts, $start_date, $end_date, $dateStr) {
    global $usrID;
    //global $dateStr;
    global $orgID;
    $cstmr_id = -1;
    $prsn_id = -1;

    if ($providerType == 'Locum') {
        $cstmr_id = $prsn_cstmr_id;
    } else {
        $prsn_id = $prsn_cstmr_id;
    }

    $insSQL = "INSERT INTO hosp.srvs_prvdrs(prvdr_id,
            prvdr_grp_id, srvs_type_id, provider_type, prsn_id, cstmr_id, max_daily_appntmnts, start_date, end_date,
            created_by, creation_date, last_update_by, last_update_date)
    VALUES ($prvdr_id, $prvdrGrpId, $srvs_type_id, '" . loc_db_escape_string($providerType) . "', 
		    $prsn_id, $cstmr_id, $max_daily_appntmnts, '" . loc_db_escape_string($start_date) . "',
			'" . loc_db_escape_string($end_date) . "', $usrID,'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";

    return execUpdtInsSQL($insSQL);
}

function updateCreditPrvdrGroupPersons($prvdr_id, $srvs_type_id, $prvdrGrpId, $providerType, $prsn_cstmr_id, $max_daily_appntmnts, $start_date, $end_date, $dateStr) {
    global $usrID;
    //global $dateStr;
    global $orgID;

    $cstmr_id = -1;
    $prsn_id = -1;

    if ($providerType == 'Locum') {
        $cstmr_id = $prsn_cstmr_id;
    } else {
        $prsn_id = $prsn_cstmr_id;
    }
    
    $insSQL = "UPDATE hosp.srvs_prvdrs SET
            srvs_type_id = $srvs_type_id,
            provider_type ='" . loc_db_escape_string($providerType) . "', 
            prsn_id = $prsn_id, cstmr_id = $cstmr_id, max_daily_appntmnts = $max_daily_appntmnts,
			start_date = '" . loc_db_escape_string($start_date) . "',
                        end_date ='" . loc_db_escape_string($end_date) . "',
           last_update_by =" . $usrID . ", last_update_date = '" . $dateStr . "' WHERE prvdr_id = $prvdr_id";

    return execUpdtInsSQL($insSQL);
}

function deleteCreditPrvdrGroupPersons($prvdr_id) {
    $delSQL1 = "DELETE FROM hosp.srvs_prvdrs WHERE prvdr_id = $prvdr_id";
    
    return execUpdtInsSQL($delSQL1);
}

function getCreditPrvdrGroupPersonID() {

    $sqlStr = "SELECT nextval('hosp.srvs_prvdrs_prvdr_id_seq');";

    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }

    return -1;
}

function isPrvdrGroupInActiveUse($prvdrGrpId) {
    $result1 = executeSQLNoParams("SELECT count(*) FROM hosp.srvs_prvdrs WHERE prvdr_grp_id = $prvdrGrpId");

    while ($row1 = loc_db_fetch_array($result1)) {
        if ((int) $row1[0] > 0) {
            return true;
        }
    }

    $result2 = executeSQLNoParams("SELECT count(*) FROM hosp.appntmnt WHERE prvdr_grp_id = $prvdrGrpId");

    while ($row2 = loc_db_fetch_array($result2)) {
        if ((int) $row2[0] > 0) {
            return true;
        }
    }

    $result3 = executeSQLNoParams("SELECT count(*) FROM hosp.prvdr_grp_srvs WHERE prvdr_grp_id = $prvdrGrpId");

    while ($row3 = loc_db_fetch_array($result3)) {
        if ((int) $row3[0] > 0) {
            return true;
        }
    }

    return false;
}

function doesPrvdrGroupNameExistsSave($prvdrGrpName) {
    $result1 = executeSQLNoParams("SELECT prvdr_grp_id FROM hosp.prvdr_grps "
            . "WHERE upper(prvdr_grp_name) = '" . strtoupper(loc_db_escape_string($prvdrGrpName)) . "'");
    while ($row1 = loc_db_fetch_array($result1)) {
        return true;
    }
    return false;
}

function doesPrvdrGroupMainSrvcTypeIdExistsSave($prvdrGroupMainSrvcTypeId) {
    $result1 = executeSQLNoParams("SELECT prvdr_grp_id FROM hosp.prvdr_grps "
            . "WHERE main_srvc_type_id = $prvdrGroupMainSrvcTypeId");
    while ($row1 = loc_db_fetch_array($result1)) {
        return true;
    }
    return false;
}

function doesPrvdrGroupNameExistsUpdate($prvdrGrpId, $prvdrGrpName) {
    $result1 = executeSQLNoParams("SELECT prvdr_grp_id FROM hosp.prvdr_grps "
            . "WHERE upper(prvdr_grp_name) = '" . strtoupper(loc_db_escape_string($prvdrGrpName)) . "' AND prvdr_grp_id != $prvdrGrpId");
    while ($row1 = loc_db_fetch_array($result1)) {
        return (int) $row1[0];
    }
    return false;
}

function doesPrvdrGroupMainSrvcTypeIdExistsUpdate($prvdrGrpId, $prvdrGroupMainSrvcTypeId) {
    $result1 = executeSQLNoParams("SELECT prvdr_grp_id FROM hosp.prvdr_grps "
            . "WHERE main_srvc_type_id = $prvdrGroupMainSrvcTypeId  AND prvdr_grp_id != $prvdrGrpId");
    while ($row1 = loc_db_fetch_array($result1)) {
        return (int) $row1[0];
    }
    return false;
}

function get_AllPrvdrGroupPersons($searchFor, $searchIn, $offset, $limit_size, $prvdrGrpId) {
    $wherecls = "(provider_type ilike '" .
            loc_db_escape_string($searchFor) . "' or  (CASE WHEN prsn_id>0 THEN prs.get_prsn_name(prsn_id)  
			ELSE scm.get_cstmr_splr_name(cstmr_id) END) ilike '" .
            loc_db_escape_string($searchFor) . "') and ";

    $strSql = "SELECT prvdr_id, prvdr_grp_id, provider_type, CASE WHEN provider_type = 'Locum' THEN cstmr_id ELSE prsn_id END prsn_cstmr_id,
        (CASE WHEN prsn_id>0 THEN prs.get_prsn_name(prsn_id) ELSE scm.get_cstmr_splr_name(cstmr_id) END) prsn_name, max_daily_appntmnts, 
        CASE WHEN start_date = '' THEN '' ELSE to_char(to_timestamp(start_date, 'YYYY-MM-DD'), 'DD-Mon-YYYY') END start_date,
        CASE WHEN end_date = '' THEN '' ELSE to_char(to_timestamp(end_date, 'YYYY-MM-DD'), 'DD-Mon-YYYY') END end_date
  FROM hosp.srvs_prvdrs a WHERE 1 = 1 AND " . $wherecls .
            "(a.prvdr_grp_id = " . $prvdrGrpId . ") "
            . "ORDER BY provider_type ASC LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);

    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AllPrvdrGroupPersonsTtl($searchFor, $searchIn, $prvdrGrpId) {
    $wherecls = "(provider_type ilike '" .
            loc_db_escape_string($searchFor) . "' or  (CASE WHEN prsn_id>0 THEN prs.get_prsn_name(prsn_id)  
			ELSE scm.get_cstmr_splr_name(cstmr_id) END) ilike '" .
            loc_db_escape_string($searchFor) . "') and ";

    $strSql = "SELECT count(1) FROM hosp.srvs_prvdrs a WHERE 1 = 1  AND " . $wherecls .
            "(a.prvdr_grp_id = " . $prvdrGrpId . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function isPrvdrGroupPersonInActiveUse($prsn_id) {
    $cnt = 0;
    $strSql1 = "SELECT count(*) FROM hosp.appntmnt WHERE srvs_prvdr_prsn_id = $prsn_id";

    $result1 = executeSQLNoParams($strSql1);

    while ($row1 = loc_db_fetch_array($result1)) {
        if ((int) $row1[0] > 0) {
            $cnt += 1;
        }
    }

    if ((int) $cnt > 0) {
        return true;
    } else {
        return false;
    }
    return false;
}

function getPrvdrGroupIDfromPrvdrGroupPerson($prsn_cstmr_id) {
    $result1 = executeSQLNoParams("SELECT distinct prvdr_grp_id FROM hosp.srvs_prvdrs WHERE prsn_id = $prsn_cstmr_id or cstmr_id = $prsn_cstmr_id");
    while ($row1 = loc_db_fetch_array($result1)) {
        return $row1[0];
    }
    return -1;
}

function get_PrsExtrData($pkID, $colNum = "1") {
    $colNms = array("data_col1", "data_col2", "data_col3", "data_col4",
        "data_col5", "data_col6", "data_col7", "data_col8", "data_col9", "data_col10",
        "data_col11", "data_col12", "data_col13", "data_col14", "data_col15", "data_col16",
        "data_col17", "data_col18", "data_col19", "data_col20", "data_col21", "data_col22",
        "data_col23", "data_col24", "data_col25", "data_col26", "data_col27", "data_col28",
        "data_col29", "data_col30", "data_col31", "data_col32", "data_col33", "data_col34",
        "data_col35", "data_col36", "data_col37", "data_col38", "data_col39", "data_col40",
        "data_col41", "data_col42", "data_col43", "data_col44", "data_col45", "data_col46",
        "data_col47", "data_col48", "data_col49", "data_col50");
    $strSql = "SELECT " . $colNms[$colNum - 1] . ", extra_data_id 
  FROM prs.prsn_extra_data a WHERE ((person_id = $pkID))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function get_AllInhouseAdmissions(/*$cnsltnId, $vstID*/$cnsltnId, $appntmntID = -1) {
    $andCls = " AND x.dest_appntmnt_id = $appntmntID";

    if ($cnsltnId > 0) {
        $andCls = " AND cnsltn_id = $cnsltnId";        
    } else if($appntmntID > 0){
        $andCls = "AND x.dest_appntmnt_id = $appntmntID";
    }
    
    
    $strSql = "SELECT admsn_id, to_char(to_timestamp(x.checkin_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), /**1**/
        to_char(to_timestamp(x.checkout_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), d.service_type_name,/**3**/
        b.room_name, coalesce(y.doc_num,''), facility_type_id, x.room_id, x.ref_check_in_id, x.dest_appntmnt_id,  /**9**/
        coalesce(v.invc_hdr_id,-1) /**10**/
FROM hosp.inpatient_admissions x INNER JOIN hosp.appntmnt z ON x.dest_appntmnt_id = z.appntmnt_id
LEFT OUTER JOIN hotl.checkins_hdr y ON x.ref_check_in_id = y.check_in_id
LEFT OUTER JOIN scm.scm_sales_invc_hdr v ON (y.check_in_id = v.other_mdls_doc_id AND other_mdls_doc_type = 'Check-In')
LEFT OUTER JOIN hotl.service_types d ON (x.facility_type_id=d.service_type_id )
LEFT OUTER JOIN hotl.rooms b ON (x.room_id = b.room_id) WHERE 1 = 1 
            $andCls ORDER BY 1";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function checkInAppointment($appntmntID) {
    global $usrID;
    global $selectedStoreID;

    $updtSQL = "UPDATE hosp.appntmnt SET check_in_date = '" . date("Y-m-d H:i:s") .
            "', last_update_by = $usrID, last_update_date = '" . date("Y-m-d H:i:s") . "', srvs_prvdr_prsn_id = " . getUserPrsnID1($usrID)
            . ", src_store_id = $selectedStoreID, appntmnt_status = 'In Progress' WHERE appntmnt_id = $appntmntID";

    return execUpdtInsSQL($updtSQL);
}

function checkOutAppointment($appntmntID) {
    global $usrID;

    $updtSQL = "UPDATE hosp.appntmnt SET appntmnt_end_date = '" . date("Y-m-d H:i:s") .
            "', last_update_by = $usrID, last_update_date = '" . date("Y-m-d H:i:s") . "', appntmnt_status = 'Completed' WHERE appntmnt_id = $appntmntID";
    //echo $updtSQL;
    return execUpdtInsSQL($updtSQL);
}

function cancelAppointment($appntmntID) {
    global $usrID;

    $updtSQL = "UPDATE hosp.appntmnt SET last_update_by = $usrID, last_update_date = '" . date("Y-m-d H:i:s") . "', appntmnt_status = 'Cancelled' WHERE appntmnt_id = $appntmntID";

    return execUpdtInsSQL($updtSQL);
}

function reopenAppointment($appntmntID) {
    global $usrID;

    $updtSQL = "UPDATE hosp.appntmnt SET appntmnt_end_date = '', last_update_by = $usrID, last_update_date = '" . date("Y-m-d H:i:s") . "', appntmnt_status = 'In Progress' WHERE appntmnt_id = $appntmntID";

    return execUpdtInsSQL($updtSQL);
}

//IN-HOUSE ADMISSIONS
function insertInptntAdmsn($admsn_id, $cnsltn_id, $facility_type_id, $room_id, $dest_appntmnt_id, $checkin_date, $checkout_date, $ref_check_in_id, $created_by, $creation_date, $last_update_by, $last_update_date) {
    $insSQL = "INSERT INTO hosp.inpatient_admissions(
        admsn_id,cnsltn_id,facility_type_id,room_id,dest_appntmnt_id,checkin_date,checkout_date,ref_check_in_id,created_by,creation_date,last_update_by,last_update_date)
        VALUES(
        $admsn_id,$cnsltn_id,$facility_type_id,$room_id,$dest_appntmnt_id,'" . loc_db_escape_string($checkin_date) . "','" . loc_db_escape_string($checkout_date) .
            "',$ref_check_in_id,$created_by,'" . loc_db_escape_string($creation_date) . "',$last_update_by,'" . loc_db_escape_string($last_update_date) . "')";

    return execUpdtInsSQL($insSQL);
}

function updateInptntAdmsn($admsn_id, $cnsltn_id, $facility_type_id, $room_id, $dest_appntmnt_id, $checkin_date, $checkout_date, $ref_check_in_id, $created_by, $creation_date, $last_update_by, $last_update_date) {
    $updtSQL = "UPDATE hosp.inpatient_admissions SET
            admsn_id = $admsn_id, facility_type_id = $facility_type_id,room_id = $room_id,"
            . "dest_appntmnt_id = $dest_appntmnt_id,checkin_date = '" . loc_db_escape_string($checkin_date) . "',checkout_date = '" . loc_db_escape_string($checkout_date) .
            "',ref_check_in_id = $ref_check_in_id, last_update_by = $last_update_by,last_update_date = '" . loc_db_escape_string($last_update_date) . "'
 WHERE admsn_id = $admsn_id";
    //echo $updtSQL;
    return execUpdtInsSQL($updtSQL);
}

function deleteInptntAdmsn($admsn_id) {
    $delSQL = "DELETE FROM hosp.inpatient_admissions WHERE admsn_id = $admsn_id";

    return execUpdtInsSQL($delSQL);
}

function getAdmsn_id() {
    $sqlStr = "SELECT nextval('hosp.inpatient_admissions_admsn_id_seq');";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function isAdmsn_id_InActiveUse($admsn_id) {
    $cnt = 0;
    return false;
}

function getInptntAdmsnData($admsn_id) {
    $sqlStr = "SELECT admsn_id,cnsltn_id,facility_type_id,room_id,dest_appntmnt_id,checkin_date,checkout_date,ref_check_in_id,"
            . "created_by,creation_date,last_update_by,last_update_date FROM hosp.inpatient_admissions WHERE admsn_id = $admsn_id";
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

//PRESCRIPTION MEDICATIONS
function insertMedication($prscptn_id, $cnsltn_id, $created_by, $creation_date, $last_update_by, $last_update_date, $doc_cmnts, $pharm_cmnts, $dspnsd_status, $dest_appntmnt_id, $itm_id, $dose_qty, $dose_uom, $dsge_freqncy_no, $dsge_freqncy_uom, $tot_duratn, $tot_duratn_uom, $dose_form, $admin_times, $sub_allowed, $int_prscrbr_prsn_id) {
    $insSQL = "INSERT INTO hosp.prscptn(
    prscptn_id,cnsltn_id,created_by,creation_date,last_update_by,last_update_date,doc_cmnts,pharm_cmnts,dspnsd_status,dest_appntmnt_id,itm_id,
    dose_qty,dose_uom,dsge_freqncy_no,dsge_freqncy_uom,tot_duratn,tot_duratn_uom,dose_form,admin_times,sub_allowed,int_prscrbr_prsn_id)
    VALUES(
    $prscptn_id,$cnsltn_id,$created_by,'" . loc_db_escape_string($creation_date) . "',$last_update_by,'" . loc_db_escape_string($last_update_date) . "','"
            . loc_db_escape_string($doc_cmnts) . "','" . loc_db_escape_string($pharm_cmnts) . "','" . cnvrtBoolToBitStr($dspnsd_status) . "',$dest_appntmnt_id,$itm_id,$dose_qty,'"
            . loc_db_escape_string($dose_uom) . "',$dsge_freqncy_no,'" . loc_db_escape_string($dsge_freqncy_uom) . "',$tot_duratn,'" . loc_db_escape_string($tot_duratn_uom) . "','"
            . loc_db_escape_string($dose_form) . "','" . loc_db_escape_string($admin_times) . "','" . cnvrtBoolToBitStr($sub_allowed) . "',$int_prscrbr_prsn_id)";

    return execUpdtInsSQL($insSQL);
}

function updateMedication($prscptn_id, $cnsltn_id, $created_by, $creation_date, $last_update_by, $last_update_date, $doc_cmnts, $pharm_cmnts, $dspnsd_status, $dest_appntmnt_id, $itm_id, $dose_qty, $dose_uom, $dsge_freqncy_no, $dsge_freqncy_uom, $tot_duratn, $tot_duratn_uom, $dose_form, $admin_times, $sub_allowed, $int_prscrbr_prsn_id) {
    $updtSQL = "UPDATE hosp.prscptn SET
    prscptn_id = $prscptn_id, last_update_by = $last_update_by,last_update_date = '" . loc_db_escape_string($last_update_date)
            . "',doc_cmnts = '" . loc_db_escape_string($doc_cmnts) . "',pharm_cmnts = '" . loc_db_escape_string($pharm_cmnts)
            . "',dspnsd_status = '" . cnvrtBoolToBitStr($dspnsd_status) . "',itm_id = $itm_id,dose_qty = $dose_qty,dose_uom = '" . loc_db_escape_string($dose_uom)
            . "',dsge_freqncy_no = $dsge_freqncy_no,dsge_freqncy_uom = '" . loc_db_escape_string($dsge_freqncy_uom)
            . "',tot_duratn = $tot_duratn,tot_duratn_uom = '" . loc_db_escape_string($tot_duratn_uom) . "',dose_form = '" . loc_db_escape_string($dose_form)
            . "',admin_times = '" . loc_db_escape_string($admin_times) . "',sub_allowed = '" . cnvrtBoolToBitStr($sub_allowed) . "'
    WHERE prscptn_id = $prscptn_id";

    return execUpdtInsSQL($updtSQL);
}

function deleteMedication($prscptn_id) {
    $delSQL = "DELETE FROM hosp.prscptn WHERE prscptn_id = $prscptn_id";

    return execUpdtInsSQL($delSQL);
}

function getPrscptn_id() {
    $sqlStr = "SELECT nextval('hosp.prscptn_prscptn_id_seq');";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function isPrscptn_id_InActiveUse($prscptn_id) {
    $cnt = 0;
    $sqlStr = "SELECT count(*) FROM scm.scm_sales_invc_det WHERE invc_det_ln_id IN (SELECT distinct ref_invc_det_ln_id FROM hosp.prscptn WHERE prscptn_id = $prscptn_id)";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        if ((int) $row[0] > 0) {
            $cnt += 1;
        }
        if ((int) $cnt > 0) {
            return true;
        } else {
            return false;
        }
        return false;
    }
}

function checkUnlinkedAppntmntDataItmExistince($itmID, $appntmntID, $src_srvstype_row_id) {
    $sqlStr = "SELECT count(*) FROM hosp.appntmnt_data_items WHERE appntmnt_id = $appntmntID
        /*AND invc_hdr_id = -1 AND invc_det_ln_id = -1 AND is_billed = 'N'*/
        AND item_id = $itmID AND src_srvstype_row_id = $src_srvstype_row_id";

    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return 0;
}

//ITEM/SERVICES REQUIRED
function insertItmServsRqrd($itmservs_rqrd_id, $invstgtn_list_id, $quantity, $cmnts, $created_by, $creation_date, $last_update_by, $last_update_date, $itm_id, $servs_type_id, $itm_type) {
    $insSQL = "INSERT INTO hosp.itms_servs_rqrd(
    itmservs_rqrd_id,invstgtn_list_id,quantity,cmnts,created_by,creation_date,last_update_by,last_update_date,itm_id,servs_type_id, itm_type)
    VALUES(
    $itmservs_rqrd_id,$invstgtn_list_id,$quantity,'" . loc_db_escape_string($cmnts) . "',$created_by,'" . loc_db_escape_string($creation_date)
            . "',$last_update_by,'" . loc_db_escape_string($last_update_date) . "',$itm_id,$servs_type_id, '$itm_type')";

    return execUpdtInsSQL($insSQL);
}

function updateItmServsRqrd($itmservs_rqrd_id, $invstgtn_list_id, $quantity, $cmnts, $created_by, $creation_date, $last_update_by, $last_update_date, $itm_id, $servs_type_id, $itm_type) {
    $updtSQL = "UPDATE hosp.itms_servs_rqrd SET
    itmservs_rqrd_id = $itmservs_rqrd_id,quantity = $quantity,cmnts = '" . loc_db_escape_string($cmnts) .
            "',last_update_by = $last_update_by,last_update_date = '" . loc_db_escape_string($last_update_date) . "',itm_id = $itm_id, itm_type = '$itm_type'
    WHERE itmservs_rqrd_id = $itmservs_rqrd_id";

    return execUpdtInsSQL($updtSQL);
}

function deleteItmServsRqrd($itmservs_rqrd_id) {
    $delSQL = "DELETE FROM hosp.itms_servs_rqrd WHERE itmservs_rqrd_id = $itmservs_rqrd_id";

    return execUpdtInsSQL($delSQL);
}

function getItmservs_rqrd_id() {
    $sqlStr = "SELECT nextval('hosp.invstgtn_itms_servs_invstgtn_itmservs_id_seq');";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function isItmservs_rqrd_id_InActiveUse($itmservs_rqrd_id) {
    $cnt = 0;
    return false;
}

function getItmServsRqrdRptTbl($sbmtdSrcRecID, $srcType, $searchFor, $searchIn, $offset, $limit_size, $searchAll, $sortBy) {
    $extra1 = "";
    if ($searchAll == true) {
        $extra1 = "1 = 1";
    }
    $strSql = "";
    $whrcls = "";
    
    $srcTypeId = "servs_type_id";
    if($srcType == "LAB"){
        $srcTypeId = "invstgtn_list_id";
    }

    $strSql = "SELECT itmservs_rqrd_id, invstgtn_list_id, quantity, cmnts, itm_id, servs_type_id, itm_type
   FROM hosp.itms_servs_rqrd WHERE (1 = 1 AND (" . $extra1 . ")" . $whrcls .
            " AND $srcTypeId = $sbmtdSrcRecID) ORDER BY 1 DESC LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getItmServsRqrdRptTblTtl($sbmtdSrcRecID, $srcType, $searchFor, $searchIn, $searchAll) {
    $extra1 = "";
    if ($searchAll == true) {
        $extra1 = "1 = 1";
    }
    $strSql = "";
    $whrcls = "";
    
     $srcTypeId = "servs_type_id";
    if($srcType == "LAB"){
        $srcTypeId = "invstgtn_list_id";
    }

    $strSql = "SELECT count(1)
   FROM hosp.itms_servs_rqrd WHERE (1 = 1 AND (" . $extra1 . ")" . $whrcls . " AND $srcTypeId = $sbmtdSrcRecID)";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

//LAB INVESTIGATIONS
function insertInvstgtn($invstgtn_id, $cnsltn_id, $created_by, $creation_date, $last_update_by, $last_update_date, $doc_cmnts, $lab_cmnts, $lab_loc, $lab_results, $dest_appntmnt_id, $invstgtn_list_id, $do_inhouse) {
    $insSQL = "INSERT INTO hosp.invstgtn(
invstgtn_id,cnsltn_id,created_by,creation_date,last_update_by,last_update_date,doc_cmnts,lab_cmnts,lab_loc,lab_results,dest_appntmnt_id,invstgtn_list_id,do_inhouse)
VALUES(
$invstgtn_id,$cnsltn_id,$created_by,'" . loc_db_escape_string($creation_date) . "',$last_update_by,'" . loc_db_escape_string($last_update_date) . "','" 
            . loc_db_escape_string($doc_cmnts) . "','" . loc_db_escape_string($lab_cmnts) . "','" . loc_db_escape_string($lab_loc) . "','" 
            . loc_db_escape_string($lab_results) . "',$dest_appntmnt_id,$invstgtn_list_id,'" . cnvrtBoolToBitStr($do_inhouse) . "')";
    

    return execUpdtInsSQL($insSQL);
}

function updateInvstgtn($invstgtn_id, $cnsltn_id, $created_by, $creation_date, $last_update_by, $last_update_date, $doc_cmnts, $lab_cmnts, $lab_loc, $lab_results, $dest_appntmnt_id, $invstgtn_list_id, $do_inhouse) {
    $updtSQL = "UPDATE hosp.invstgtn SET
invstgtn_id = $invstgtn_id,last_update_by = $last_update_by,last_update_date = '" . loc_db_escape_string($last_update_date) . "',doc_cmnts = '" 
            . loc_db_escape_string($doc_cmnts) . "',lab_cmnts = '" . loc_db_escape_string($lab_cmnts) . "',lab_loc = '" . loc_db_escape_string($lab_loc) 
            . "',lab_results = '" . loc_db_escape_string($lab_results) . "',invstgtn_list_id = $invstgtn_list_id,do_inhouse = '" . cnvrtBoolToBitStr($do_inhouse) . "'
WHERE invstgtn_id = $invstgtn_id";

    return execUpdtInsSQL($updtSQL);
}

function deleteInvstgtn($invstgtn_id) {
    $delSQL = "DELETE FROM hosp.invstgtn WHERE invstgtn_id = $invstgtn_id";
    
    $delSQL1 = "DELETE FROM hosp.appntmnt_data_items WHERE src_srvstype_row_id = $invstgtn_id";

    execUpdtInsSQL($delSQL1);

    return execUpdtInsSQL($delSQL);
}

function getInvstgtn_id() {
    $sqlStr = "SELECT nextval('hosp.invstgtn_hdr_invstgtn_hdr_id_seq');";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function isInvstgtn_id_InActiveUse($invstgtn_id) {
    $cnt = 0;
    return false;
}


function getInvstgtnSrvsItmId($invstgtn_id) {
    $sqlStr = "SELECT distinct srvs_itm_id
        FROM hosp.invstgtn x, hosp.invstgtn_list y
        WHERE x.invstgtn_list_id = y.invstgtn_list_id
        AND invstgtn_id = $invstgtn_id";

    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}


//VISITS
function getTrnsNoNxtVal($lnType) {
    $dte = date("Y-m-d");
    $sqlStr = "SELECT (cur_no + 1) FROM hosp.id_cnta WHERE ln_type = '$lnType' AND dte = '$dte'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        if(strlen($row[0]) == 1){
            return "000".$row[0];
        } else if(strlen($row[0]) == 2){
            "00".$row[0];
        } else if(strlen($row[0]) == 3){
            "0".$row[0];
        } else {
            return $row[0];
        } 
    }
    return "0001";
}

function insertVisit($vst_id, $prsn_id, $vst_date, $cmnts, $created_by, $creation_date, $last_update_by, $last_update_date, $vst_option, $bill_this_visit, $branch_id) {
    $nxtVal = getTrnsNoNxtVal("V");
    global $orgID;
    $dte = date("Y-m-d");
    //$rstl = 0;
    $trnsctn_no = "VST-".date("Ymd")."-".$nxtVal;
    $insSQL = "INSERT INTO hosp.visit(
        vst_id,prsn_id,vst_date,cmnts,created_by,creation_date,last_update_by,last_update_date,vst_option,bill_this_visit,branch_id,trnsctn_no, vst_status, org_id)
        VALUES(
        $vst_id,$prsn_id,'" . loc_db_escape_string($vst_date) . "','" . loc_db_escape_string($cmnts) . "',$created_by,'" . loc_db_escape_string($creation_date) 
            . "',$last_update_by,'" . loc_db_escape_string($last_update_date) . "','" . loc_db_escape_string($vst_option) . "','" . loc_db_escape_string($bill_this_visit) 
            . "',$branch_id,'" . loc_db_escape_string($trnsctn_no) . "', 'Open', $orgID)";

    $rslt = (int)execUpdtInsSQL($insSQL);
    
    if($rslt > 0){
        if($nxtVal == "0001"){
            execUpdtInsSQL("UPDATE hosp.id_cnta SET cur_no = ".(int)$nxtVal.", dte = '$dte'  WHERE ln_type = 'V'");
        } else {
            execUpdtInsSQL("UPDATE hosp.id_cnta SET cur_no = ".(int)$nxtVal." WHERE ln_type = 'V' AND dte = '$dte'");
        }
    }
    
    return $rslt;
}

function updateVisit($vst_id, $prsn_id, $vst_date, $cmnts, $created_by, $creation_date, $last_update_by, $last_update_date, $vst_option, $bill_this_visit, $branch_id) {
    $updtSQL = "UPDATE hosp.visit SET
        vst_id = $vst_id,prsn_id = $prsn_id,vst_date = '" . loc_db_escape_string($vst_date) . "',cmnts = '" . loc_db_escape_string($cmnts) 
            . "',last_update_by = $last_update_by,last_update_date = '" . loc_db_escape_string($last_update_date) . "',vst_option = '" . loc_db_escape_string($vst_option) . "',bill_this_visit = '" . loc_db_escape_string($bill_this_visit) . "'
        WHERE vst_id = $vst_id";

    return execUpdtInsSQL($updtSQL);
}

function deleteVisit($vst_id) {
    $delSQL = "DELETE FROM hosp.visit WHERE vst_id = $vst_id";

    return execUpdtInsSQL($delSQL);
}

function getVst_id() {
    $sqlStr = "SELECT nextval('hosp.visit_vst_id_seq');";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function isVst_id_InActiveUse($vst_id) {
    $cnt = 0;
    $sqlStr = "SELECT count(*) FROM hosp.appntmnt WHERE vst_id = $vst_id";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        if ((int) $row[0] > 0) {
            $cnt += 1;
        }
        if ((int) $cnt > 0) {
            return true;
        } else {
            return false;
        }
        return false;
    }
}

function insertAppntmnt($appntmnt_id, $vst_id, $appntmnt_date, $srvs_type_id, $prvdr_type, $srvs_prvdr_prsn_id, $prvdr_grp_id, $cmnts, $created_by, $creation_date, 
        $last_update_by, $last_update_date) {
    $nxtVal = getTrnsNoNxtVal("A");
    $dte = date("Y-m-d");
    $trnsctn_no = "APT-".date("Ymd")."-".$nxtVal;
    
    $insSQL = "INSERT INTO hosp.appntmnt(
        appntmnt_id,vst_id,appntmnt_date,srvs_type_id,prvdr_type,srvs_prvdr_prsn_id,prvdr_grp_id,cmnts,created_by,creation_date,last_update_by,last_update_date,appntmnt_no)
        VALUES(
        $appntmnt_id,$vst_id,'" . loc_db_escape_string($appntmnt_date) . "',$srvs_type_id,'" . loc_db_escape_string($prvdr_type) . "',$srvs_prvdr_prsn_id,$prvdr_grp_id,'" 
            . loc_db_escape_string($cmnts) . "',$created_by,'" . loc_db_escape_string($creation_date) . "',$last_update_by,'" . loc_db_escape_string($last_update_date) 
            . "','" . $trnsctn_no . "')";

    //echo $insSQL;
    
    $rslt = (int)execUpdtInsSQL($insSQL);
    
    if($rslt > 0){
        if($nxtVal == "0001"){
            execUpdtInsSQL("UPDATE hosp.id_cnta SET cur_no = ".(int)$nxtVal.", dte = '$dte'  WHERE ln_type = 'A'");
        } else {
            execUpdtInsSQL("UPDATE hosp.id_cnta SET cur_no = ".(int)$nxtVal." WHERE ln_type = 'A' AND dte = '$dte'");
        }
    }
    
    return $rslt;
}

function updateAppntmnt($appntmnt_id, $vst_id, $appntmnt_date, $srvs_type_id, $prvdr_type, $srvs_prvdr_prsn_id, $prvdr_grp_id, $cmnts, $created_by, $creation_date, 
        $last_update_by, $last_update_date) {
    $updtSQL = "UPDATE hosp.appntmnt SET
        appntmnt_id = $appntmnt_id,appntmnt_date = '" . loc_db_escape_string($appntmnt_date) . "',srvs_type_id = $srvs_type_id,prvdr_type = '" . loc_db_escape_string($prvdr_type) 
            . "',srvs_prvdr_prsn_id = $srvs_prvdr_prsn_id,prvdr_grp_id = $prvdr_grp_id,cmnts = '" . loc_db_escape_string($cmnts) 
            . "',last_update_by = $last_update_by,last_update_date = '" . loc_db_escape_string($last_update_date) . "'
WHERE appntmnt_id = $appntmnt_id";

    return execUpdtInsSQL($updtSQL);
}

function deleteAppntmnt($appntmnt_id) {
    $delSQL = "DELETE FROM hosp.appntmnt WHERE appntmnt_id = $appntmnt_id";
    
    $delSQL1 = "DELETE FROM hosp.appntmnt_data_items WHERE appntmnt_id = $appntmnt_id";

    execUpdtInsSQL($delSQL1);

    return execUpdtInsSQL($delSQL);
}

function getAppntmnt_id() {
    $sqlStr = "SELECT nextval('hosp.appntmnt_appntmnt_id_seq');";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function isAppntmnt_id_InActiveUse($appntmnt_id) {
    $cnt = 0;
    /*$sqlStr = "SELECT count(*) FROM hosp.appntmnt_data_items WHERE appntmnt_id = $appntmnt_id";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        if ((int) $row[0] > 0) {
            $cnt += 1;
        }
        if ((int) $cnt > 0) {
            return true;
        } else {
            return false;
        }
        return false;
    }*/
    return false;
}


//VITALS
function insertVitals($vtl_id, $appntmnt_id, $weight, $height, $bp_systlc, $bp_diastlc, $pulse, $resptn, $body_tmp, $oxgn_satn, $head_circum, 
        $waist_circum, $bmi, $bmi_status, $bowel_actn, $cmnts, $created_by, $creation_date, $last_update_by, $last_update_date, $tmp_loc, $bp_status) {
    $insSQL = "INSERT INTO hosp.vitals(
    vtl_id,appntmnt_id,weight,height,bp_systlc,bp_diastlc,pulse,resptn,body_tmp,oxgn_satn,head_circum,waist_circum,bmi,bmi_status,bowel_actn,cmnts,
    created_by,creation_date,last_update_by,last_update_date,tmp_loc,bp_status)
    VALUES(
    $vtl_id,$appntmnt_id,$weight,$height,$bp_systlc,$bp_diastlc,$pulse,$resptn,$body_tmp,$oxgn_satn,$head_circum,$waist_circum,$bmi,'" . loc_db_escape_string($bmi_status) 
            . "','" . loc_db_escape_string($bowel_actn) . "','" . loc_db_escape_string($cmnts) . "',$created_by,'" . loc_db_escape_string($creation_date) 
            . "',$last_update_by,'" . loc_db_escape_string($last_update_date) . "','" . loc_db_escape_string($tmp_loc) . "','" . loc_db_escape_string($bp_status) . "')";

    return execUpdtInsSQL($insSQL);
}

function updateVitals($vtl_id, $appntmnt_id, $weight, $height, $bp_systlc, $bp_diastlc, $pulse, $resptn, $body_tmp, $oxgn_satn, $head_circum, 
        $waist_circum, $bmi, $bmi_status, $bowel_actn, $cmnts, $created_by, $creation_date, $last_update_by, $last_update_date, $tmp_loc, $bp_status) {
    $updtSQL = "UPDATE hosp.vitals SET
    vtl_id = $vtl_id,weight = $weight,height = $height,bp_systlc = $bp_systlc,bp_diastlc = $bp_diastlc,pulse = $pulse,resptn = $resptn,body_tmp = $body_tmp,"
            . "oxgn_satn = $oxgn_satn,head_circum = $head_circum,waist_circum = $waist_circum,bmi = $bmi,bmi_status = '" . loc_db_escape_string($bmi_status) 
            . "',bowel_actn = '" . loc_db_escape_string($bowel_actn) . "',cmnts = '" . loc_db_escape_string($cmnts) . "',last_update_by = $last_update_by,"
            . "last_update_date = '" . loc_db_escape_string($last_update_date) . "',tmp_loc = '" . loc_db_escape_string($tmp_loc) 
            . "',bp_status = '" . loc_db_escape_string($bp_status) . "'
     WHERE vtl_id = $vtl_id";

    return execUpdtInsSQL($updtSQL);
}

function deleteVitals($vtl_id) {
    $delSQL = "DELETE FROM hosp.vitals WHERE vtl_id = $vtl_id";

    return execUpdtInsSQL($delSQL);
}

function getVtl_id() {
    $sqlStr = "SELECT nextval('hosp.vitals_vtl_id_seq');";
    $result = executeSQLNoParams($sqlStr);
    while($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
        return -1;
}

function isVtl_id_InActiveUse($vtl_id) {
    $cnt = 0;
        return false;
}

//DIAGNOSIS
function insertDiagnosis($diag_id, $disease_id, $created_by, $creation_date, $last_update_by, $last_update_date, $cnsltn_id, $cmnts) {
    $insSQL = "INSERT INTO hosp.cnsltn_diagn_types(
    diag_id,disease_id,created_by,creation_date,last_update_by,last_update_date,cnsltn_id,cmnts)
    VALUES(
    '" . loc_db_escape_string($diag_id) . "',$disease_id,$created_by,'" . loc_db_escape_string($creation_date) . "',$last_update_by,'" . loc_db_escape_string($last_update_date) . "',$cnsltn_id,'" . loc_db_escape_string($cmnts) . "')";

    return execUpdtInsSQL($insSQL);
}

function updateDiagnosis($diag_id, $disease_id, $created_by, $creation_date, $last_update_by, $last_update_date, $cnsltn_id, $cmnts) {
    $updtSQL = "UPDATE hosp.cnsltn_diagn_types SET
    diag_id = '" . loc_db_escape_string($diag_id) . "',disease_id = $disease_id,last_update_by = $last_update_by,last_update_date = '" . loc_db_escape_string($last_update_date) . "',cmnts = '" . loc_db_escape_string($cmnts) . "'
     WHERE diag_id = $diag_id";

    return execUpdtInsSQL($updtSQL);
}

function deleteDiagnosis($diag_id) {
    $delSQL = "DELETE FROM hosp.cnsltn_diagn_types WHERE diag_id = $diag_id";

    return execUpdtInsSQL($delSQL);
}

function getDiag_id() {
    $sqlStr = "SELECT nextval('hosp.cnsltn_diagn_types_diag_id_seq');";
    $result = executeSQLNoParams($sqlStr);
    while($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
        return -1;
}

function isDiag_id_InActiveUse($diag_id) {
    $cnt = 0;
        return false;
}


//CONSULTATION
function insertConsultation($cnsltn_id, $appntmnt_id, $patient_complaints, $physical_examination, $cmnts, $created_by, $creation_date, $last_update_by, 
        $last_update_date, $admission_cmnts, $admission_checkin_date, $admission_no_of_days) {
    $insSQL = "INSERT INTO hosp.medcl_cnsltn(
    cnsltn_id,appntmnt_id,patient_complaints,physical_examination,cmnts,created_by,creation_date,last_update_by,last_update_date, admission_cmnts, admission_checkin_date, admission_no_of_days)
    VALUES(
    $cnsltn_id,$appntmnt_id,'" . loc_db_escape_string($patient_complaints) . "','" . loc_db_escape_string($physical_examination) 
            . "','" . loc_db_escape_string($cmnts) . "',$created_by,'" . loc_db_escape_string($creation_date) . "',$last_update_by,'" . loc_db_escape_string($last_update_date) . "','"
            .  loc_db_escape_string($admission_cmnts) . "','$admission_checkin_date', $admission_no_of_days)";
    //echo $insSQL;
    return execUpdtInsSQL($insSQL);
}

function updateConsultation($cnsltn_id, $appntmnt_id, $patient_complaints, $physical_examination, $cmnts, $created_by, $creation_date, $last_update_by, 
        $last_update_date, $admission_cmnts, $admission_checkin_date, $admission_no_of_days) {
    $updtSQL = "UPDATE hosp.medcl_cnsltn SET
    cnsltn_id = $cnsltn_id,patient_complaints = '" . loc_db_escape_string($patient_complaints) . "',physical_examination = '" . loc_db_escape_string($physical_examination) 
            . "',cmnts = '" . loc_db_escape_string($cmnts) . "',last_update_by = $last_update_by, last_update_date = '" . loc_db_escape_string($last_update_date) . "',
                admission_cmnts = '" . loc_db_escape_string($admission_cmnts) . "', admission_checkin_date = '$admission_checkin_date', admission_no_of_days = $admission_no_of_days
     WHERE cnsltn_id = $cnsltn_id";

    return execUpdtInsSQL($updtSQL);
}

function deleteConsultation($cnsltn_id) {
    $delSQL = "DELETE FROM hosp.medcl_cnsltn WHERE cnsltn_id = $cnsltn_id";

    return execUpdtInsSQL($delSQL);
}

function getCnsltn_id() {
    $sqlStr = "SELECT nextval('hosp.medcl_cnsltn_cnsltn_id_seq');";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function isCnsltn_id_InActiveUse($cnsltn_id) {
    $cnt = 0;
    return false;
}

function getAppntmntCnsltnID($appntmntID) {
    $sqlStr = "SELECT cnsltn_id FROM hosp.medcl_cnsltn WHERE appntmnt_id  = $appntmntID";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int)$row[0];
    }
    return -1;
}

function getSrvsTypeMainPrvdrGrp($main_srvc_type_sys_code) {
    $sqlStr = "SELECT distinct prvdr_grp_id "
            . " FROM hosp.prvdr_grps x, hosp.srvs_types y"
            . " WHERE x.main_srvc_type_id = y.type_id"
            . " AND y.sys_code = '$main_srvc_type_sys_code' LIMIT 1";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int)$row[0];
    }
    return -1;
}

function getSrvsTypeIDFromSysCode($main_srvc_type_sys_code) {
    $sqlStr = "SELECT y.type_id FROM hosp.srvs_types y WHERE y.sys_code = '$main_srvc_type_sys_code' LIMIT 1";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int)$row[0];
    }
    return -1;
}

function getCnsltnDetails($cnsltnID) {
    $strSql = "SELECT cnsltn_id, patient_complaints, physical_examination, cmnts, appntmnt_id, /**4**/
        admission_cmnts, /**5**/
        CASE WHEN admission_checkin_date = '' THEN admission_checkin_date ELSE to_char(to_timestamp(admission_checkin_date,'YYYY-MM-DD'),'DD-Mon-YYYY') END admission_checkin_date, /**6**/
        admission_no_of_days /**7**/
        FROM hosp.medcl_cnsltn WHERE cnsltn_id = $cnsltnID";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getInhouseAdmsnCnsltnID($appntmnt_id) {
    $sqlStr = "SELECT y.cnsltn_id FROM hosp.inpatient_admissions y WHERE y.dest_appntmnt_id = $appntmnt_id AND y.cnsltn_id > 0  LIMIT 1";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int)$row[0];
    }
    return -1;
}

//HISTORIC APPOINTMENTS
function get_PatientHstrcAppointmentsTtl($patientPrsnID, $qStrtDte, $qEndDte, $statusSrchIn, /* $branchSrchIn, */ $searchFor, $searchIn, $orgID, $searchAll) {
    $extra1 = "";
    global $prsnid;

    if ($searchAll == true) {
        $extra1 = "or 1 = 1";
    }

    $strSql = "";
    $whrcls = "";
        if ($qStrtDte != "") {
        $whrcls .= " AND (appntmnt_date >= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qStrtDte)) . "')";
    }
    if ($qEndDte != "") {
        $whrcls .= " AND (appntmnt_date <= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qEndDte)) . "')";
    }

    $strSql = "SELECT count(p.*) FROM " .
            "(SELECT DISTINCT appntmnt_id, to_char(to_timestamp(appntmnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') appntmnt_date, appntmnt_no,
	    a.vst_id, a.srvs_type_id, srvs_prvdr_prsn_id, a.prvdr_grp_id, sys_code
         FROM hosp.visit x INNER JOIN prs.prsn_names_nos y ON y.person_id = x.prsn_id
                INNER JOIN hosp.appntmnt a ON x.vst_id = a.vst_id
		INNER JOIN hosp.srvs_prvdrs e ON e.srvs_type_id = a.srvs_type_id AND (e.prvdr_grp_id = a.prvdr_grp_id OR e.prsn_id = a.srvs_prvdr_prsn_id)
		/*AND now() >= to_timestamp(appntmnt_date,'YYYY-MM-DD HH24:MI:SS')*/ AND to_timestamp(appntmnt_date,'YYYY-MM-DD HH24:MI:SS') 
		BETWEEN to_timestamp(start_date,'YYYY-MM-DD HH24:MI:SS') AND to_timestamp(end_date,'YYYY-MM-DD HH24:MI:SS')
		LEFT OUTER JOIN hosp.srvs_types b ON a.srvs_type_id = b.type_id
		LEFT OUTER JOIN  hosp.prvdr_grps c ON a.prvdr_grp_id = c.prvdr_grp_id
		LEFT OUTER JOIN prs.prsn_names_nos d ON a.srvs_prvdr_prsn_id = d.person_id 
		WHERE (e.prsn_id = $prsnid AND pasn.get_prsn_siteid($prsnid) = x.branch_id) AND sys_code = 'MC-0001' AND y.person_id = $patientPrsnID 
                AND substr(appntmnt_date,1,10) < to_char(now(),'YYYY-MM-DD')
                AND (( 1 = 1" . $extra1 . ")" . $whrcls . "))p";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_PatientHstrcAppointments($patientPrsnID, $qStrtDte, $qEndDte, $statusSrchIn, /* $branchSrchIn, */ $searchFor, $searchIn, $offset, $limit_size, $orgID, $searchAll) {
    $extra1 = "";
    global $prsnid;

    if ($searchAll == true) {
        $extra1 = "or 1 = 1";
    }

    $whrcls = "";
    $ordrBy = "";
    /* if ($srvsTypeSrchIn == "All Service Types") {
      $whrcls .= " and 1 = 1";
      } else {
      $whrcls .= " and type_name||'('||sys_code||')' = '" . loc_db_escape_string($srvsTypeSrchIn) . "'";
      } */

    if ($qStrtDte != "") {
        $whrcls .= " AND (appntmnt_date >= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qStrtDte)) . "')";
    }
    if ($qEndDte != "") {
        $whrcls .= " AND (appntmnt_date <= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qEndDte)) . "')";
    }

    $ordrBy = "appntmnt_date ASC";

    $strSql = "SELECT DISTINCT appntmnt_id, to_char(to_timestamp(appntmnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') appntmnt_date, appntmnt_no,
	    a.vst_id, a.srvs_type_id, srvs_prvdr_prsn_id, a.prvdr_grp_id, sys_code
		FROM hosp.visit x INNER JOIN prs.prsn_names_nos y ON y.person_id = x.prsn_id
                INNER JOIN hosp.appntmnt a ON x.vst_id = a.vst_id
		INNER JOIN hosp.srvs_prvdrs e ON e.srvs_type_id = a.srvs_type_id AND (e.prvdr_grp_id = a.prvdr_grp_id OR e.prsn_id = a.srvs_prvdr_prsn_id)
		/*AND now() >= to_timestamp(appntmnt_date,'YYYY-MM-DD HH24:MI:SS')*/ AND to_timestamp(appntmnt_date,'YYYY-MM-DD HH24:MI:SS') 
		BETWEEN to_timestamp(start_date,'YYYY-MM-DD HH24:MI:SS') AND to_timestamp(end_date,'YYYY-MM-DD HH24:MI:SS')
		LEFT OUTER JOIN hosp.srvs_types b ON a.srvs_type_id = b.type_id
		LEFT OUTER JOIN  hosp.prvdr_grps c ON a.prvdr_grp_id = c.prvdr_grp_id
		LEFT OUTER JOIN prs.prsn_names_nos d ON a.srvs_prvdr_prsn_id = d.person_id 
		WHERE (e.prsn_id = $prsnid AND pasn.get_prsn_siteid($prsnid) = x.branch_id) AND sys_code = 'MC-0001' AND y.person_id = $patientPrsnID
                AND substr(appntmnt_date,1,10) < to_char(now(),'YYYY-MM-DD')
		AND (( 1 = 1 " . $extra1 . ")" . $whrcls .
            ") ORDER BY " . $ordrBy . " LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    //var_dump($strSql);
    return $result;
}

function checkExstncOfVitslsForVisit($vst_id) {
    $cnt = 0;
    $strSql1 = "SELECT count(*) FROM hosp.appntmnt x, hosp.visit y, hosp.srvs_types z  "
            . "WHERE x.vst_id = y.vst_id AND x.srvs_type_id = z.type_id "
            . " AND z.sys_code = 'VS-0001' AND x.vst_id = $vst_id";

    $result1 = executeSQLNoParams($strSql1);

    while ($row1 = loc_db_fetch_array($result1)) {
        if ((int) $row1[0] > 0) {
            $cnt += 1;
        }
    }


    if ((int) $cnt > 0) {
        return true;
    } else {
        return false;
    }
    return false;
}

function getVitalsSrvsTypeID() {
    $strSql1 = "SELECT type_id FROM hosp.srvs_types  "
            . "WHERE sys_code = 'VS-0001' LIMIT 1";

    $result1 = executeSQLNoParams($strSql1);

    while ($row1 = loc_db_fetch_array($result1)) {
        return (int) $row1[0];
    }

    return -1;
}

/* ADDITIONAL SERVICES DATA */
function createSrvsExtrData($srcPkID, $data_col, $srcType) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO hosp.hosp_extra_data(
            src_pk_id, data_col1, data_col2, data_col3, data_col4, 
            data_col5, data_col6, data_col7, data_col8, data_col9, data_col10, 
            data_col11, data_col12, data_col13, data_col14, data_col15, data_col16, 
            data_col17, data_col18, data_col19, data_col20, data_col21, data_col22, 
            data_col23, data_col24, data_col25, data_col26, data_col27, data_col28, 
            data_col29, data_col30, data_col31, data_col32, data_col33, data_col34, 
            data_col35, data_col36, data_col37, data_col38, data_col39, data_col40, 
            data_col41, data_col42, data_col43, data_col44, data_col45, data_col46, 
            data_col47, data_col48, data_col49, data_col50, src_type, created_by, creation_date, 
            last_update_by, last_update_date)  
            VALUES($srcPkID, '" . loc_db_escape_string($data_col[1]) .
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
            "', '" . loc_db_escape_string($data_col[50]) . "', '" . loc_db_escape_string($srcType) .
            "', $usrID, '$dateStr', $usrID, '$dateStr')";
    return execUpdtInsSQL($insSQL);
}

function updateSrvsExtrData($srcPkID, $data_col, $srcType) {
    global $usrID;
    $dateStr = getDB_Date_time();

    $tblNm = "hosp.hosp_extra_data";

    $updtSQL = "UPDATE $tblNm 
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
            "', data_col50='" . loc_db_escape_string($data_col[50]) . "', src_type='" . loc_db_escape_string($srcType) .
            "', last_update_by=$usrID,  
        last_update_date='$dateStr' WHERE src_pk_id =$srcPkID AND src_type = '$srcType'";
    //echo $updtSQL;
    return execUpdtInsSQL($updtSQL, "Extra Data Update");
}

function createHospExtrDataCol($colno, $collabel, $lovnm, $datatyp
, $catgry, $lngth, $dsplytyp, $orgid, $tblrnumcols, $ordr, $csvTblColNms, $isrqrd, $srcType) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO hosp.hosp_extra_data_cols(
            column_no, column_label, attchd_lov_name, 
            column_data_type, column_data_category, data_length, data_dsply_type, 
            org_id, no_cols_tblr_dsply, col_order, csv_tblr_col_nms, created_by, creation_date, 
            last_update_by, last_update_date,is_required, src_type)" .
            "VALUES (" . $colno .
            ", '" . loc_db_escape_string($collabel) .
            "', '" . loc_db_escape_string($lovnm) .
            "', '" . loc_db_escape_string($datatyp) .
            "', '" . loc_db_escape_string($catgry) .
            "', " . loc_db_escape_string($lngth) .
            ", '" . loc_db_escape_string($dsplytyp) .
            "', " . $orgid .
            ", " . $tblrnumcols . ", " . $ordr .
            ", '" . loc_db_escape_string($csvTblColNms) .
            "', " . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr .
            "', '" . cnvrtBoolToBitStr($isrqrd) . "','" . loc_db_escape_string($srcType) . "')";
    return execUpdtInsSQL($insSQL);
}

function updateHospExtrDataCol($colno, $collabel, $lovnm, $datatyp
, $catgry, $lngth, $dsplytyp, $orgid, $tblrnumcols, $rowid, $ordr, $csvTblColNms, $isrqrd, $srcType) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE hosp.hosp_extra_data_cols SET 
            column_no=" . $colno .
            ", column_label='" . loc_db_escape_string($collabel) .
            "', attchd_lov_name='" . loc_db_escape_string($lovnm) .
            "', column_data_type='" . loc_db_escape_string($datatyp) .
            "', column_data_category='" . loc_db_escape_string($catgry) .
            "', data_length=" . loc_db_escape_string($lngth) .
            ", data_dsply_type='" . loc_db_escape_string($dsplytyp) .
            "', org_id=" . $orgid .
            ", no_cols_tblr_dsply=" . $tblrnumcols .
            ", col_order=" . $ordr .
            ", csv_tblr_col_nms='" . loc_db_escape_string($csvTblColNms) .
            "', last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr .
            "', is_required='" . cnvrtBoolToBitStr($isrqrd) .
            "', src_type = '" . loc_db_escape_string($srcType)
            . "' WHERE extra_data_cols_id = " . $rowid;
    //echo $updtSQL;
    return execUpdtInsSQL($updtSQL);
}

function deleteHospExtrDataCol($pkeyID, $extrInfo = "") {
    $delSQL = "DELETE FROM hosp.hosp_extra_data_cols WHERE extra_data_cols_id = " . $pkeyID;
    
    //GET COLUMN NUMBER FOR $pkeyID
    $colNo = (int)getGnrlRecNm("hosp.hosp_extra_data_cols", "extra_data_cols_id", "column_no", $pkeyID);
    $pkIdOrgID = (int)getGnrlRecNm("hosp.hosp_extra_data_cols", "extra_data_cols_id", "org_id", $pkeyID);
    
    $updtSql = "UPDATE hosp.hosp_extra_data SET data_col$colNo = '' WHERE src_pk_id  IN (SELECT distinct appntmnt_id FROM hosp.appntmnt x, hosp.visit y"
            . " WHERE x.vst_id = y.vst_id AND y.org_id = $pkIdOrgID)";
    execUpdtInsSQL($updtSql, "Additional Data Column:" . $extrInfo);
    
    $affctd1 = execUpdtInsSQL($delSQL, "Additional Data Column:" . $extrInfo);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Additional Data Column(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_SrvsExtrDataGrpCols($grpnm, $org_ID, $srcType) {
    $strSql = "SELECT extra_data_cols_id, column_no, column_label, attchd_lov_name, 
       column_data_type, column_data_category, data_length, 
       CASE WHEN data_dsply_type='T' THEN 'Tabular' ELSE 'Detail' END, 
       org_id, no_cols_tblr_dsply, col_order, csv_tblr_col_nms 
        FROM hosp.hosp_extra_data_cols
        WHERE column_data_category= '" . loc_db_escape_string($grpnm) .
            "' and org_id = " . $org_ID . " and column_label !='' AND src_type = '$srcType' ORDER BY col_order, column_no, extra_data_cols_id";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AllSrvsExtrDataCols($org_ID, $srcType) {
    $strSql = "SELECT extra_data_cols_id, column_no, column_label, attchd_lov_name, 
       column_data_type, column_data_category, data_length, 
       CASE WHEN data_dsply_type='T' THEN 'Tabular' ELSE 'Detail' END, 
       org_id, no_cols_tblr_dsply, col_order, csv_tblr_col_nms, is_required, src_type 
        FROM hosp.hosp_extra_data_cols 
        WHERE org_id = " . $org_ID . " AND src_type = '" . loc_db_escape_string($srcType) . "' ORDER BY column_no";
    //echo($strSql);
    $result = executeSQLNoParams($strSql);

    return $result;
}

function get_SrvsExtrDataGrpCols1($colNum, $org_ID, $srcType) {
    $strSql = "SELECT extra_data_cols_id, column_no, column_label, attchd_lov_name, 
       column_data_type, column_data_category, data_length, 
       CASE WHEN data_dsply_type='T' THEN 'Tabular' ELSE 'Detail' END, 
       org_id, no_cols_tblr_dsply, col_order, csv_tblr_col_nms 
        FROM hosp.hosp_extra_data_cols
        WHERE column_no= '" . $colNum .
            "' and org_id = " . $org_ID . " AND src_type = '$srcType' ORDER BY col_order, column_no, extra_data_cols_id";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_SrvsExtrDataGrps($org_ID, $srcType) {
    $strSql = "SELECT column_data_category, MIN(extra_data_cols_id) , MIN(col_order)  
        FROM hosp.hosp_extra_data_cols
        WHERE org_id =$org_ID and column_label !='' AND src_type = '$srcType' 
            GROUP BY column_data_category ORDER BY 3, 2, 1";
    $result = executeSQLNoParams($strSql);
    //echo $strSql;
    return $result;
}

function get_SrvsExtrData($pkID, $srcType, $colNum = "1", $rvsnTtlAPD = "0") {

    $tblNm = "hosp.hosp_extra_data";

    $colNms = array("data_col1", "data_col2", "data_col3", "data_col4",
        "data_col5", "data_col6", "data_col7", "data_col8", "data_col9", "data_col10",
        "data_col11", "data_col12", "data_col13", "data_col14", "data_col15", "data_col16",
        "data_col17", "data_col18", "data_col19", "data_col20", "data_col21", "data_col22",
        "data_col23", "data_col24", "data_col25", "data_col26", "data_col27", "data_col28",
        "data_col29", "data_col30", "data_col31", "data_col32", "data_col33", "data_col34",
        "data_col35", "data_col36", "data_col37", "data_col38", "data_col39", "data_col40",
        "data_col41", "data_col42", "data_col43", "data_col44", "data_col45", "data_col46",
        "data_col47", "data_col48", "data_col49", "data_col50");
    $strSql = "SELECT " . $colNms[$colNum - 1] . ", extra_data_id 
  FROM $tblNm a WHERE ((src_pk_id = $pkID) AND src_type = '$srcType')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getAppntmntStatus($appntmnt_id) {
    $result1 = executeSQLNoParams("SELECT appntmnt_status FROM hosp.appntmnt WHERE appntmnt_id = $appntmnt_id");
    while ($row1 = loc_db_fetch_array($result1)) {
        return $row1[0];
    }
    return "INVALID";
}

//RECOMMENDED SERVICE
function insertRcmdSrvs($rcmd_srv_id,$cnsltn_id,$srv_type_id,$created_by,$creation_date,$last_update_by,$last_update_date,$doc_cmnts,$srvs_prvdr_cmnts,$dest_appntmnt_id) {
	$insSQL = "INSERT INTO hosp.rcmd_srvs(
	rcmd_srv_id,cnsltn_id,srv_type_id,created_by,creation_date,last_update_by,last_update_date,doc_cmnts,srvs_prvdr_cmnts,dest_appntmnt_id)
	VALUES(
	$rcmd_srv_id,$cnsltn_id,$srv_type_id,$created_by,'" . loc_db_escape_string($creation_date) . "',$last_update_by,'" . loc_db_escape_string($last_update_date) . "','" . loc_db_escape_string($doc_cmnts) . "','" . loc_db_escape_string($srvs_prvdr_cmnts) . "',$dest_appntmnt_id)";

	return execUpdtInsSQL($insSQL);
}

function updateRcmdSrvs($rcmd_srv_id,$cnsltn_id,$srv_type_id,$created_by,$creation_date,$last_update_by,$last_update_date,$doc_cmnts,$srvs_prvdr_cmnts,$dest_appntmnt_id) {
	$updtSQL = "UPDATE hosp.rcmd_srvs SET
	rcmd_srv_id = $rcmd_srv_id,last_update_by = $last_update_by,last_update_date = '" . loc_db_escape_string($last_update_date) . "',srvs_prvdr_cmnts = '" . loc_db_escape_string($srvs_prvdr_cmnts) . "'
	 WHERE rcmd_srv_id = $rcmd_srv_id";

	return execUpdtInsSQL($updtSQL);
}

function deleteRcmdSrvs($rcmd_srv_id) {
	$delSQL = "DELETE FROM hosp.rcmd_srvs WHERE rcmd_srv_id = $rcmd_srv_id";
        $dstAppntmntID = getGnrlRecNm("hosp.rcmd_srvs", "rcmd_srv_id", "dest_appntmnt_id", $rcmd_srv_id);
        if($dstAppntmntID != ""){
            $dstAppntmntID = (int)$dstAppntmntID;
            execUpdtInsSQL("DELETE FROM hosp.appntmnt WHERE appntmnt_id = $dstAppntmntID");
        }

	return execUpdtInsSQL($delSQL);
}

function getRcmd_srv_id() {
    $sqlStr = "SELECT nextval('hosp.rcmdd_srvs_hdr_rcmd_srv_hdr_id_seq');";
    $result = executeSQLNoParams($sqlStr);
    while($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function isRcmd_srv_id_InActiveUse($rcmd_srv_id) {
    $cnt = 0;
    $dstAppntmntID = getGnrlRecNm("hosp.rcmd_srvs", "rcmd_srv_id", "dest_appntmnt_id", $rcmd_srv_id);
    $srvsTypeID = (int)getGnrlRecNm("hosp.appntmnt", "appntmnt_id", "srvs_type_id", $dstAppntmntID);
    $srvsTypeSysCode = getGnrlRecNm("hosp.srvs_types", "type_id", "sys_code", $srvsTypeID);

    $sqlStr = "SELECT count(*) FROM hosp.hosp_extra_data WHERE src_type = '$srvsTypeSysCode' AND src_pk_id = $dstAppntmntID";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        if ((int) $row[0] > 0) {
            $cnt += 1;
        }
        if ((int) $cnt > 0) {
            return true;
        } else {
            return false;
        }
        return false;
    }
    return false;
}

function saveAddtnlSrvsData($appntmntID, $srcType, $addtnlSrvsDataCol1, $addtnlSrvsDataCol2, $addtnlSrvsDataCol3, $addtnlSrvsDataCol4, $addtnlSrvsDataCol5, $addtnlSrvsDataCol6, $addtnlSrvsDataCol7, $addtnlSrvsDataCol8, $addtnlSrvsDataCol9, $addtnlSrvsDataCol10, $addtnlSrvsDataCol11, $addtnlSrvsDataCol12, $addtnlSrvsDataCol13, $addtnlSrvsDataCol14, $addtnlSrvsDataCol15, $addtnlSrvsDataCol16, $addtnlSrvsDataCol17, $addtnlSrvsDataCol18, $addtnlSrvsDataCol19, $addtnlSrvsDataCol20, $addtnlSrvsDataCol21, $addtnlSrvsDataCol22, $addtnlSrvsDataCol23, $addtnlSrvsDataCol24, $addtnlSrvsDataCol25, $addtnlSrvsDataCol26, $addtnlSrvsDataCol27, $addtnlSrvsDataCol28, $addtnlSrvsDataCol29, $addtnlSrvsDataCol30, $addtnlSrvsDataCol31, $addtnlSrvsDataCol32, $addtnlSrvsDataCol33, $addtnlSrvsDataCol34, $addtnlSrvsDataCol35, $addtnlSrvsDataCol36, $addtnlSrvsDataCol37, $addtnlSrvsDataCol38, $addtnlSrvsDataCol39, $addtnlSrvsDataCol40, $addtnlSrvsDataCol41, $addtnlSrvsDataCol42, $addtnlSrvsDataCol43, $addtnlSrvsDataCol44, $addtnlSrvsDataCol45, $addtnlSrvsDataCol46, $addtnlSrvsDataCol47, $addtnlSrvsDataCol48, $addtnlSrvsDataCol49, $addtnlSrvsDataCol50) {
    $adDataExsts = 0;
    $data_cols = array("", $addtnlSrvsDataCol1, $addtnlSrvsDataCol2, $addtnlSrvsDataCol3, $addtnlSrvsDataCol4, $addtnlSrvsDataCol5, $addtnlSrvsDataCol6, $addtnlSrvsDataCol7, $addtnlSrvsDataCol8, $addtnlSrvsDataCol9, $addtnlSrvsDataCol10,
        $addtnlSrvsDataCol11, $addtnlSrvsDataCol12, $addtnlSrvsDataCol13, $addtnlSrvsDataCol14, $addtnlSrvsDataCol15, $addtnlSrvsDataCol16, $addtnlSrvsDataCol17, $addtnlSrvsDataCol18, $addtnlSrvsDataCol19, $addtnlSrvsDataCol20,
        $addtnlSrvsDataCol21, $addtnlSrvsDataCol22, $addtnlSrvsDataCol23, $addtnlSrvsDataCol24, $addtnlSrvsDataCol25, $addtnlSrvsDataCol26, $addtnlSrvsDataCol27, $addtnlSrvsDataCol28, $addtnlSrvsDataCol29, $addtnlSrvsDataCol30,
        $addtnlSrvsDataCol31, $addtnlSrvsDataCol32, $addtnlSrvsDataCol33, $addtnlSrvsDataCol34, $addtnlSrvsDataCol35, $addtnlSrvsDataCol36, $addtnlSrvsDataCol37, $addtnlSrvsDataCol38, $addtnlSrvsDataCol39, $addtnlSrvsDataCol40,
        $addtnlSrvsDataCol41, $addtnlSrvsDataCol42, $addtnlSrvsDataCol43, $addtnlSrvsDataCol44, $addtnlSrvsDataCol45, $addtnlSrvsDataCol46, $addtnlSrvsDataCol47, $addtnlSrvsDataCol48, $addtnlSrvsDataCol49, $addtnlSrvsDataCol50);
    for ($y = 0; $y < count($data_cols); $y++) {
        if ($data_cols[$y] != "") {
            $adDataExsts++;
        }
    }
    $extrDataID = -1;
    $extrDataIDStr = "";
    //$extrDataIDStr = getGnrlRecNm("prs.prsn_extra_data", "person_id", "extra_data_id", $inptDaPersonID);

    $result = executeSQLNoParams("SELECT extra_data_id FROM hosp.hosp_extra_data WHERE src_pk_id = $appntmntID AND src_type = '$srcType'");
    while ($row = loc_db_fetch_array($result)) {
        $extrDataIDStr = $row[0];
        break;
    }

    if ($extrDataIDStr != "") {
        $extrDataID = (float) $extrDataIDStr;
    }

    //INSERT FIRST RECORD
    /*if ($extrDataID < 0) {
        createSrvsExtrData($appntmntID, $data_cols, $srcType);
    }*/

    if ($adDataExsts > 0) {
        if ($extrDataID > 0) {
            updateSrvsExtrData($appntmntID, $data_cols, $srcType);
        } else {
            createSrvsExtrData($appntmntID, $data_cols, $srcType);
        }
    }

    return true;
}

function getCreditRcmddSrvsMainsTbl($cnsltnID, $searchFor, $searchIn, $offset, $limit_size) {
    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Service Name") {
        $whrcls = " AND (b.type_name ilike '" . loc_db_escape_string($searchFor) . "')";
    } 

    $ordrBy = "a.last_update_date DESC";

    $strSql = "SELECT rcmd_srv_id, type_name, doc_cmnts, sys_code, srv_type_id, dest_appntmnt_id
        FROM hosp.rcmd_srvs a, hosp.srvs_types b WHERE b.type_id = a.srv_type_id AND cnsltn_id = $cnsltnID $whrcls" .
            " ORDER BY " . $ordrBy . " LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    //var_dump($strSql);
    return $result;
}

function getCreditRcmddSrvsMainsTblTtl($cnsltnID, $searchFor, $searchIn) {
    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Service Name") {
        $whrcls = " AND (b.type_name ilike '" . loc_db_escape_string($searchFor) . "')";
    }

    $strSql = "SELECT count(tt.*) " .
            "FROM (SELECT rcmd_srv_id, type_name, doc_cmnts, sys_code, srv_type_id, dest_appntmnt_id
                FROM hosp.rcmd_srvs a, hosp.srvs_types b WHERE b.type_id = a.srv_type_id AND cnsltn_id = $cnsltnID  $whrcls) tt";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function addtnlServiceRqrd($sysCode) {
    $strSql = "SELECT count(*) " .
            "FROM hosp.hosp_extra_data_cols WHERE src_type = '$sysCode'";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        if((int)$row[0] > 0){
            return true;
        }
    }
    return false;
}

function getCnsltnLabOrRadialogyCnt($cnsltnID, $invstgnType) {
    $strSql = "select count(x.*) FROM hosp.invstgtn x, hosp.invstgtn_list y WHERE x.invstgtn_list_id = y.invstgtn_list_id"
            . " AND cnsltn_id = $cnsltnID AND invstgtn_type = '$invstgnType'";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return 0;
}

//DOCUMENT ATTACHMENTS
function get_FSCAttachments($searchWord, $offset, $limit_size, $hdrID, $hdrType, &$attchSQL) {
    $strSql = "SELECT a.attchmnt_id, a.src_id, a.attchmnt_desc, a.file_name, a.attchmnt_src, a.file_type " .
            "FROM hosp.hosp_doc_attchmnts a " .
            "WHERE(a.attchmnt_desc ilike '" . loc_db_escape_string($searchWord) .
            "' and a.src_id = " . $hdrID .
            " and a.attchmnt_src='" . loc_db_escape_string($hdrType) .
            "') ORDER BY a.attchmnt_id LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    $attchSQL = $strSql;
    return $result;
}

function get_Total_FSCAttachments($searchWord, $hdrID, $hdrType) {
    $strSql = "SELECT count(1) " .
            "FROM hosp.hosp_doc_attchmnts a " .
            "WHERE(a.attchmnt_desc ilike '" . loc_db_escape_string($searchWord) .
            "' and a.src_id = " . $hdrID .
            " and a.attchmnt_src='" . loc_db_escape_string($hdrType) .
            "')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function updateFSCDocFlNm($attchmnt_id, $file_name) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE hosp.hosp_doc_attchmnts SET file_name='"
            . loc_db_escape_string($file_name) .
            "', last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr . "'
                WHERE attchmnt_id=" . $attchmnt_id;
    return execUpdtInsSQL($insSQL);
}

function getNewFSCDocID() {
    $strSql = "select nextval('hosp.hosp_doc_attchmnts_attchmnt_id_seq')";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function createFSCDoc($attchmnt_id, $hdrid, $attchmnt_desc, $file_name, $fileType, $hdrType) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO hosp.hosp_doc_attchmnts(
            attchmnt_id, src_id, attchmnt_desc, file_name, created_by, 
            creation_date, last_update_by, last_update_date, file_type, attchmnt_src)
             VALUES (" . $attchmnt_id . ", " . $hdrid . ",'"
            . loc_db_escape_string($attchmnt_desc) . "','"
            . loc_db_escape_string($file_name) . "',"
            . $usrID . ",'" . $dateStr . "'," . $usrID .
            ",'" . $dateStr . "', '" . loc_db_escape_string($fileType) . "', '" . loc_db_escape_string($hdrType) . "')";
    return execUpdtInsSQL($insSQL);
}

function deleteFSCDoc($pkeyID, $docTrnsNum = "") {
    $insSQL = "DELETE FROM hosp.hosp_doc_attchmnts WHERE attchmnt_id = " . $pkeyID;
    $affctd1 = execUpdtInsSQL($insSQL, "BNK Trns. No:" . $docTrnsNum);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Attached Document(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function uploadDaFSCTrnsDoc($attchmntID, $docTrsType, $docFileType, &$nwImgLoc, &$errMsg) {
    global $tmpDest;
    global $ftp_base_db_fldr;
    global $usrID;
    global $fldrPrfx;
    global $smplTokenWord1;

    $msg = "";
    $allowedExts = array('png', 'jpg', 'gif', 'jpeg', 'bmp', 'pdf', 'xls', 'xlsx',
        'doc', 'docx', 'ppt', 'pptx', 'txt', 'csv');

    if (isset($_FILES["daFSCAttchmnt"])) {
        $flnm = $_FILES["daFSCAttchmnt"]["name"];
        $temp = explode(".", $flnm);
        $extension = end($temp);
        if ($_FILES["daFSCAttchmnt"]["error"] > 0) {
            $msg .= "Return Code: " . $_FILES["daFSCAttchmnt"]["error"] . "<br>";
        } else {
            $msg .= "Uploaded File: " . $_FILES["daFSCAttchmnt"]["name"] . "<br>";
            $msg .= "Type: " . $_FILES["daFSCAttchmnt"]["type"] . "<br>";
            $msg .= "Size: " . round(($_FILES["daFSCAttchmnt"]["size"]) / (1024 * 1024), 2) . " MB<br>";
//$msg .= "Temp file: " . $_FILES["daMCFAttchmnt"]["tmp_name"] . "<br>";
            if ((($_FILES["daFSCAttchmnt"]["type"] == "image/gif") ||
                    ($_FILES["daFSCAttchmnt"]["type"] == "image/jpeg") ||
                    ($_FILES["daFSCAttchmnt"]["type"] == "image/jpg") ||
                    ($_FILES["daFSCAttchmnt"]["type"] == "image/pjpeg") ||
                    ($_FILES["daFSCAttchmnt"]["type"] == "image/x-png") ||
                    ($_FILES["daFSCAttchmnt"]["type"] == "image/png") ||
                    in_array($extension, $allowedExts)) &&
                    ($_FILES["daFSCAttchmnt"]["size"] < 10000000)) {
                $nwFileName = encrypt1($attchmntID . "." . $extension, $smplTokenWord1) . "." . $extension;
                $img_src = $fldrPrfx . $tmpDest . "$nwFileName";
                move_uploaded_file($_FILES["daFSCAttchmnt"]["tmp_name"], $img_src);
                $ftp_src = "";
                $ftp_src = $ftp_base_db_fldr . "/Mcf/Transactions/$attchmntID" . "." . $extension;
                if (file_exists($img_src)) {
                    copy("$img_src", "$ftp_src");
                    $dateStr = getDB_Date_time();
                    $updtSQL = "UPDATE hosp.hosp_doc_attchmnts
                            SET file_name='" . $attchmntID . "." . $extension .
                            "', last_update_by=" . $usrID .
                            ", last_update_date='" . $dateStr .
                            "' WHERE attchmnt_id=" . $attchmntID;
                    execUpdtInsSQL($updtSQL);
                }
                $msg .= "Document Stored Successfully!<br/>";
                $nwImgLoc = "$attchmntID" . "." . $extension;
                $errMsg = $msg;
                return TRUE;
            } else {
                $msg .= "Invalid file!<br/>File Size must be below 10MB and<br/>File Type must be in the ff:<br/>" . implode(", ", $allowedExts);
                $nwImgLoc = $msg;
                $errMsg = $msg;
            }
        }
    }
    $msg .= "<br/>Invalid file";
    $nwImgLoc = $msg;
    $errMsg = $msg;
    return FALSE;
}

function getFscRqstAttchMnts($hdrId) {
    global $ftp_base_db_fldr;
    $sqlStr = "SELECT string_agg(REPLACE(a.attchmnt_desc,';',','),';') attchmnt_desc, 
string_agg(REPLACE('" . $ftp_base_db_fldr . "/Mcf/Transactions/' || a.file_name,';',','),';') file_name 
  FROM hosp.hosp_doc_attchmnts a 
  WHERE src_id=" . $hdrId;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function cnsltnAdmissionExist($cnsltnID, $docAdmsnCheckInDate, $checkout_date){
    $cnta = 0;
    
    $sqlStr = "SELECT count(*) FROM hosp.inpatient_admissions WHERE cnsltn_id = $cnsltnID AND checkin_date = '$docAdmsnCheckInDate' AND checkout_date = '$checkout_date'";
    $result = executeSQLNoParams($sqlStr);
    
    while($row = loc_db_fetch_array($result)){
        $cnta = (int)$row[0];
    }
    
    if($cnta > 0){
        return true;
    } else {
        return false;
    }   
}   


function createAppntmntAdmsnRequest($admsn_rqst_id, $appntmnt_id, $admission_cmnts, $admission_checkin_date, 
            $admission_no_of_days, $created_by, $creation_date, $last_update_by,  $last_update_date){
    $sqlStr = "INSERT INTO hosp.appntmnt_admission_requests(
            admsn_rqst_id, appntmnt_id, admission_cmnts, admission_checkin_date, 
            admission_no_of_days, created_by, creation_date, last_update_by, 
            last_update_date)
    VALUES ($admsn_rqst_id, $appntmnt_id,'". loc_db_escape_string($admission_cmnts)."', '$admission_checkin_date', 
            $admission_no_of_days, $created_by, '$creation_date', $last_update_by,  '$last_update_date')";
    
    return execUpdtInsSQL($sqlStr);
}

function getAdmsn_rqst_id() {
    $sqlStr = "SELECT nextval('hosp.appntmnt_admission_requests_admsn_rqst_id_seq');";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function cnsltnPrescriptionExist($cnsltnID, $cnsltnAppntmnt_id){
    $cnta = 0;
    
    $sqlStr = "SELECT count(*) FROM hosp.prscptn WHERE cnsltn_id = $cnsltnID AND dest_appntmnt_id = $cnsltnAppntmnt_id";
    $result = executeSQLNoParams($sqlStr);
    
    while($row = loc_db_fetch_array($result)){
        $cnta = (int)$row[0];
    }
    
    if($cnta > 0){
        return true;
    } else {
        return false;
    }   
}

function cnsltnLabInvstgnExist($cnsltnID, $cnsltnAppntmnt_id){
    $cnta = 0;
    
    $sqlStr = "SELECT count(*) FROM hosp.invstgtn x WHERE cnsltn_id = $cnsltnID AND dest_appntmnt_id = $cnsltnAppntmnt_id AND invstgtn_list_id IN "
                . "( SELECT invstgtn_list_id FROM hosp.invstgtn_list WHERE x.invstgtn_list_id = invstgtn_list_id AND invstgtn_type = 'Lab')";
    $result = executeSQLNoParams($sqlStr);
    
    while($row = loc_db_fetch_array($result)){
        $cnta = (int)$row[0];
    }
    
    if($cnta > 0){
        return true;
    } else {
        return false;
    }   
}

function cnsltnRadiologyInvstgnExist($cnsltnID, $cnsltnAppntmnt_id){
    $cnta = 0;
    
    $sqlStr = "SELECT count(*) FROM hosp.invstgtn x WHERE cnsltn_id = $cnsltnID AND dest_appntmnt_id = $cnsltnAppntmnt_id AND invstgtn_list_id IN "
                . "( SELECT invstgtn_list_id FROM hosp.invstgtn_list WHERE x.invstgtn_list_id = invstgtn_list_id AND invstgtn_type = 'Radiology')";
    $result = executeSQLNoParams($sqlStr);
    
    while($row = loc_db_fetch_array($result)){
        $cnta = (int)$row[0];
    }
    
    if($cnta > 0){
        return true;
    } else {
        return false;
    }   
}

function getAppntmntAdmsnDetails($appntmntID) {
    $strSql = "SELECT admsn_rqst_id, admission_cmnts, /**1**/
        CASE WHEN admission_checkin_date = '' THEN admission_checkin_date ELSE to_char(to_timestamp(admission_checkin_date,'YYYY-MM-DD'),'DD-Mon-YYYY') END admission_checkin_date, /**2**/
        admission_no_of_days /**3**/
  FROM hosp.appntmnt_admission_requests WHERE appntmnt_id = $appntmntID";

    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function closeHospVisit($vstID) {
    global $usrID;

    $updtSQL = "UPDATE hosp.visit SET vst_end_date = '" . date("Y-m-d H:i:s") .
            "', last_update_by = $usrID, last_update_date = '" . date("Y-m-d H:i:s") . "', vst_status = 'Closed' WHERE vst_id = $vstID";
    
    //GET ALL UNCLOSED APPOINTMENTS
    $sqlStr = "SELECT distinct appntmnt_id FROM hosp.appntmnt WHERE vst_id = $vstID AND appntmnt_status != 'Completed'";
    $result = executeSQLNoParams($sqlStr);
    while($row = loc_db_fetch_array($result)){
        execUpdtInsSQL("UPDATE hosp.appntmnt SET appntmnt_end_date = '" . date("Y-m-d H:i:s") .
            "', last_update_by = $usrID, last_update_date = '" . date("Y-m-d H:i:s") . "', appntmnt_status = 'Completed' WHERE appntmnt_id = $row[0]");
    }

    return execUpdtInsSQL($updtSQL);
}


function checkExistenceOfServiceType($typeId, $srvcOffrdSys) {
    $cnt = 0;
    $sqlStr = "SELECT count(*) FROM hosp.srvs_types WHERE UPPER(trim(sys_code)) = UPPER(trim('".loc_db_escape_string($srvcOffrdSys)."'))";
    
    if($typeId > 0){
       $sqlStr = "SELECT count(*) FROM hosp.srvs_types WHERE UPPER(trim(sys_code)) =UPPER(trim('".loc_db_escape_string($srvcOffrdSys)."')) AND type_id != $typeId";
    }
    
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        if ((int) $row[0] > 0) {
            $cnt += 1;
        }
        if ((int) $cnt > 0) {
            return true;
        } else {
            return false;
        }
        return false;
    }
}

function checkExistenceOfServiceNm($typeId, $srvcOffrdNm) {
    $cnt = 0;
    $sqlStr = "SELECT count(*) FROM hosp.srvs_types WHERE UPPER(trim(type_name)) = UPPER(trim('".loc_db_escape_string($srvcOffrdNm)."'))";
    
    if($typeId > 0){
       $sqlStr = "SELECT count(*) FROM hosp.srvs_types WHERE UPPER(trim(type_name)) = UPPER(trim('".loc_db_escape_string($srvcOffrdNm)."')) AND type_id != $typeId";
    }
    
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        if ((int) $row[0] > 0) {
            $cnt += 1;
        }
        if ((int) $cnt > 0) {
            return true;
        } else {
            return false;
        }
        return false;
    }
}

function getAppntmntBillVisit($vst_id){
    $sqlStr = "SELECT bill_this_visit FROM hosp.visit x  WHERE vst_id = $vst_id";
    
    $result = executeSQLNoParams($sqlStr);
    while($row = loc_db_fetch_array($result)){
        return $row[0];
    }
    return '0';
}

function getAppntmntBillAppntmntVisit($appntmntID){
    $sqlStr = "SELECT bill_this_visit FROM hosp.visit x, hosp.appntmnt y WHERE 1 = 1 AND x.vst_id = y.vst_id AND appntmnt_id = $appntmntID";
    
    $result = executeSQLNoParams($sqlStr);
    while($row = loc_db_fetch_array($result)){
        return $row[0];
    }
    return '0';
}

function getPrvdrGrpCostItem($prvdr_grp_id){
    //GET PROVIDER GROUP
    $sqlStr = "SELECT cost_itm_id FROM hosp.prvdr_grps x  WHERE prvdr_grp_id = $prvdr_grp_id";
    
    $result = executeSQLNoParams($sqlStr);
    while($row = loc_db_fetch_array($result)){
        return (int) $row[0];
    }
    return -1;
}

function loadCoreServices() {
    global $orgID;
    global $usrID;
    $dte = getDB_Date_time();
    
    $sqlStrVS = "SELECT count(*) FROM hosp.srvs_types WHERE sys_code = 'VS-0001'";
    $sqlStrMC = "SELECT count(*) FROM hosp.srvs_types WHERE sys_code = 'MC-0001'";
    $sqlStrLI = "SELECT count(*) FROM hosp.srvs_types WHERE sys_code = 'LI-0001'";
    $sqlStrPH = "SELECT count(*) FROM hosp.srvs_types WHERE sys_code = 'PH-0001'";
    $sqlStrIA = "SELECT count(*) FROM hosp.srvs_types WHERE sys_code = 'IA-0001'";
    $sqlStrRD = "SELECT count(*) FROM hosp.srvs_types WHERE sys_code = 'RD-0001'";
    

    $resultVS = executeSQLNoParams($sqlStrVS);
    $vsRwCntVS = loc_db_num_rows($resultVS);
    
    if($vsRwCntVS <= 0){
        
        $resultMC = executeSQLNoParams($sqlStrMC);
        $resultLI = executeSQLNoParams($sqlStrLI);
        $resultPH = executeSQLNoParams($sqlStrPH);
        $resultIA = executeSQLNoParams($sqlStrIA);
        $resultRD = executeSQLNoParams($sqlStrRD);
    
        while ($rowVS = loc_db_fetch_array($resultVS)) {
            if ((int) $rowVS[0] <= 0) {
                execUpdtInsSQL("INSERT INTO hosp.srvs_types(type_name, type_desc, sys_code, org_id, created_by, creation_date, last_update_by, last_update_date)"
                        . "VALUES('Vitals','Vitals','VS-0001',$orgID,$usrID,'$dte',$usrID,'$dte')");
            }
        }

        while ($rowMC = loc_db_fetch_array($resultMC)) {
            if ((int) $rowMC[0] <= 0) {
                execUpdtInsSQL("INSERT INTO hosp.srvs_types(type_name, type_desc, sys_code, org_id, created_by, creation_date, last_update_by, last_update_date)"
                        . "VALUES('Consultation','Consultation','MC-0001',$orgID,$usrID,'$dte',$usrID,'$dte')");
            }
        }

        while ($rowLI = loc_db_fetch_array($resultLI)) {
            if ((int) $rowLI[0] <= 0) {
                execUpdtInsSQL("INSERT INTO hosp.srvs_types(type_name, type_desc, sys_code, org_id, created_by, creation_date, last_update_by, last_update_date)"
                        . "VALUES('Lab-Investigation','Lab-Investigation','LI-0001',$orgID,$usrID,'$dte',$usrID,'$dte')");
            }
        }

        while ($rowPH = loc_db_fetch_array($resultPH)) {
            if ((int) $rowPH[0] <= 0) {
                execUpdtInsSQL("INSERT INTO hosp.srvs_types(type_name, type_desc, sys_code, org_id, created_by, creation_date, last_update_by, last_update_date)"
                        . "VALUES('Pharmacy','Pharmacy','PH-0001',$orgID,$usrID,'$dte',$usrID,'$dte')");
            }
        }

        while ($rowIA = loc_db_fetch_array($resultIA)) {
            if ((int) $rowIA[0] <= 0) {
                execUpdtInsSQL("INSERT INTO hosp.srvs_types(type_name, type_desc, sys_code, org_id, created_by, creation_date, last_update_by, last_update_date)"
                        . "VALUES('In-Patient Admissions','In-Patient Admissions','IA-0001',$orgID,$usrID,'$dte',$usrID,'$dte')");
            }
        }
        
        while ($rowRD = loc_db_fetch_array($resultRD)) {
            if ((int) $rowRD[0] <= 0) {
                execUpdtInsSQL("INSERT INTO hosp.srvs_types(type_name, type_desc, sys_code, org_id, created_by, creation_date, last_update_by, last_update_date)"
                        . "VALUES('Radiology','Radiology','RD-0001',$orgID,$usrID,'$dte',$usrID,'$dte')");
            }
        }
    }
    
}
?>
