<?php

$menuItems = array("Workflow Apps", "Workflow Hierarchies", "Approver Groups", "Workflow Notifications");
$menuImages = array("modules.png", "bb_flow.gif", "chng_prvdr.ico", "openfileicon.png");

$mdlNm = "Workflow Manager";
$ModuleName = $mdlNm;

$dfltPrvldgs = array("View Workflow Manager", "View Workflow Apps",
    /* 2 */ "View Workflow Hierarchies", "View Approver Groups",
    /* 4 */ "View Workflow Notifications", "View Record History", "View SQL",
    /* 7 */ "Add Workflow Apps", "Edit Workflow Apps", "Delete Workflow Apps",
    /* 10 */ "Add Workflow Hierarchies", "Edit Workflow Hierarchies", "Delete Workflow Hierarchies",
    /* 13 */ "Add Approver Groups", "Edit Approver Groups", "Delete Approver Groups",
    /* 16 */ "Administer Notifications", "Administer Workflow Setups");

$canview = test_prmssns($dfltPrvldgs[0], $mdlNm);

$wkfAppID = -1;
$wkfAppActionID = -1;
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
					</li>";

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($pgNo == 0) {

            $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type');\">
						<span style=\"text-decoration:none;\">Workflow Manager Menu</span>
					</li>
                                       </ul>
                                     </div>" . "<div style=\"font-family: Tahoma, Arial, sans-serif;font-size: 1.3em;
                    padding:10px 15px 15px 20px;border:1px solid #ccc;\">                    
      <div style=\"padding:5px 30px 5px 10px;margin-bottom:2px;\">
                    <span style=\"font-family: georgia, times;font-size: 12px;font-style:italic;
                    font-weight:normal;\">This is where the workflow behind the application is configured. The module has the ff areas:</span>
                    </div>
      <p>";
            $grpcntr = 0;
            for ($i = 0; $i < count($menuItems); $i++) {
                $No = $i + 1;
                if ($i == 0 && test_prmssns($dfltPrvldgs[1], $mdlNm) == FALSE) {
                    continue;
                } else if ($i == 1 && test_prmssns($dfltPrvldgs[2], $mdlNm) == FALSE) {
                    continue;
                } else if ($i == 2 && test_prmssns($dfltPrvldgs[3], $mdlNm) == FALSE) {
                    continue;
                } else if ($i == 3 && test_prmssns($dfltPrvldgs[4], $mdlNm) == FALSE) {
                    continue;
                }
                if ($grpcntr == 0) {
                    $cntent .= "<div class=\"row\">";
                }
                if ($i == 3) {
                    $cntent .= "<div class=\"col-md-3 colmd3special2\">
        <button type=\"button\" class=\"btn btn-default btn-lg btn-block modulesButton\" onclick=\"openATab('#allmodules', 'grp=40&typ=2&pg=$No&vtyp=2&qMaster=1');\">
            <img src=\"cmn_images/$menuImages[$i]\" style=\"margin:5px; padding-right: 1em; height:58px; width:auto; position: relative; vertical-align: middle;float:left;\">
            <span class=\"wordwrap2\">" . ($menuItems[$i]) . "</span>
        </button>
            </div>";
                } else {
                    $cntent .= "<div class=\"col-md-3 colmd3special2\">
        <button type=\"button\" class=\"btn btn-default btn-lg btn-block modulesButton\" onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$No&vtyp=0');\">
            <img src=\"cmn_images/$menuImages[$i]\" style=\"margin:5px; padding-right: 1em; height:58px; width:auto; position: relative; vertical-align: middle;float:left;\">
            <span class=\"wordwrap2\">" . ($menuItems[$i]) . "</span>
        </button>
            </div>";
                }


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
            require "wkf_apps.php";
        } else if ($pgNo == 2) {
            require "wkf_hrchy.php";
        } else if ($pgNo == 3) {
            require "wkf_aprvr_grps.php";
        } else if ($pgNo == 4) {
            require $fldrPrfx . "app_code/cmncde/myinbx.php";
        } else {
            restricted();
        }
    } else {
        restricted();
    }
}

function get_WkfAppsTblr($searchFor, $searchIn, $offset, $limit_size) {

    $wherecls = "";
//"Message Header", "Message Date", "Message Status", "Source App", "Source Module"
    if ($searchIn === "Name") {
        $wherecls = "app_name ilike '" .
                loc_db_escape_string($searchFor) . "'";
    } else if ($searchIn === "Description") {
        $wherecls = "app_desc ilike '" .
                loc_db_escape_string($searchFor) . "'";
    }

    $sqlStr = "SELECT app_id mt, app_name, source_module, app_desc application_description, 
        created_by mt, creation_date mt " .
            "FROM wkf.wkf_apps " .
            "WHERE ($wherecls) ORDER BY app_name 
 LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_WkfAppsDet($pkID) {
    $sqlStr = "SELECT app_id mt, app_name, source_module, app_desc application_description, 
        created_by mt, creation_date mt " .
            "FROM wkf.wkf_apps " .
            "WHERE (app_id= $pkID)";
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_WkfAppsTtl($searchFor, $searchIn) {

    $wherecls = "";
//"Message Header", "Message Date", "Message Status", "Source App", "Source Module"
    if ($searchIn === "Name") {
        $wherecls = "app_name ilike '" .
                loc_db_escape_string($searchFor) . "'";
    } else if ($searchIn === "Description") {
        $wherecls = "app_desc ilike '" .
                loc_db_escape_string($searchFor) . "'";
    }

    $sqlStr = "SELECT count(1) " .
            "FROM wkf.wkf_apps " .
            "WHERE ($wherecls)";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_WkfAppsActns($pkID) {
    $sqlStr = "SELECT action_sql_id mt, action_performed_nm name_of_action_performed,
        action_desc description,
        sql_stmnt sql_statement, created_by mt, creation_date mt, 
       last_update_by mt, last_update_date mt, app_id mt, 
       executable_file_nm executable_file_name_for_desktop_applications, 
       web_url web_url_for_web_applications, 
       CASE WHEN is_web_dsply_diag='1' THEN 'Dialog' ELSE 'Direct' END web_display,
       CASE WHEN is_admin_only='1' THEN 'YES' ELSE 'NO' END \"For Administrators Only?\"
  FROM wkf.wkf_apps_actions a
  WHERE (a.app_id = " . $pkID . ") ORDER BY a.action_performed_nm";
//echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_WkfAppsHrchies($pkID) {
    $sqlStr = "SELECT a.app_hrchy_id, b.hierarchy_name, b.hierchy_type, b.is_enabled is_hierarchy_enabled, a.is_enabled is_linkage_enabled
        FROM  wkf.wkf_apps_n_hrchies a, wkf.wkf_hierarchy_hdr b
        WHERE (a.app_id = " . $pkID . " and a.hierarchy_id = b.hierarchy_id) ORDER BY 1";
//echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function loadWkfRqrmnts() {
    global $dfltPrvldgs;
    global $pageHtmlID;
    $DefaultPrvldgs = $dfltPrvldgs;

    $subGrpNames = ""; //, "Accounting Transactions"
    $mainTableNames = ""; //, "accb.accb_trnsctn_details"
    $keyColumnNames = ""; //, "transctn_id" 
    $myName = "Workflow Manager";
    $myDesc = "This module helps you to configure the application's workflow system!";
    $audit_tbl_name = "wkf.wkf_audit_trail_tbl";
    $smplRoleName = "Workflow Manager Administrator";

    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName, $DefaultPrvldgs, $subGrpNames, $mainTableNames, $keyColumnNames);
    createWkfRqrdLOVs();
    echo "<p style=\"font-size:12px;\">Completed Checking & Requirements load for <b>$myName"
    . "!</b><br/>Click <a href=\"javascript: showPageDetails('$pageHtmlID', 0);\"> here to Go Home</a></p>";
}

function createWkfAppHrchy($appID, $hrchyID, $isEnbld) {
    global $usrID;
    $dateStr = getDB_Date_time();

    $insSQL = "INSERT INTO wkf.wkf_apps_n_hrchies(
            app_id, hierarchy_id, is_enabled, created_by, creation_date, 
            last_update_by, last_update_date) " .
            "VALUES ($appID, $hrchyID, '" . loc_db_escape_string($isEnbld) .
            "', " . $usrID .
            ", '" . $dateStr . "', " . $usrID . ", '" . $dateStr . "')";

    execUpdtInsSQL($insSQL);
}

function updateWkfAppHrchy($apphrchyID, $appID, $hrchyID, $isEnbld) {
//    global $usrID;
//    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE wkf.wkf_apps_n_hrchies SET 
            app_id=$appID, 
            hierarchy_id=$hrchyID, 
            is_enabled='" . loc_db_escape_string($isEnbld) .
            "' WHERE app_hrchy_id = " . $apphrchyID;
    execUpdtInsSQL($insSQL);
}

function deleteWkfAppHrchy($apphrchyID) {
    $insSQL = "DELETE FROM wkf.wkf_apps_n_hrchies WHERE app_hrchy_id = " . $apphrchyID;
    $affctd1 += execUpdtInsSQL($insSQL);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 App Hierarchy(ies)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function createWkfHrchy($hrchyNm, $desc, $hrchyTyp, $sqlStmnt, $isEnbld) {
    global $usrID;
    $dateStr = getDB_Date_time();

    $insSQL = "INSERT INTO wkf.wkf_hierarchy_hdr(
             hierarchy_name, description, is_enabled, created_by, 
            creation_date, last_update_by, last_update_date, hierchy_type, 
            sql_select_stmnt) " .
            "VALUES ('" . loc_db_escape_string($hrchyNm)
            . "', '" . loc_db_escape_string($desc)
            . "', '" . loc_db_escape_string($isEnbld)
            . "'," . $usrID . ", '" . $dateStr
            . "', " . $usrID . ", '" . $dateStr
            . "', '" . loc_db_escape_string($hrchyTyp)
            . "', '" . loc_db_escape_string($sqlStmnt) . "')";

    execUpdtInsSQL($insSQL);
}

function updateWkfHrchy($hrchyID, $hrchyNm, $desc, $hrchyTyp, $sqlStmnt, $isEnbld) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE wkf.wkf_hierarchy_hdr SET 
            hierarchy_name='" . loc_db_escape_string($hrchyNm)
            . "', description='" . loc_db_escape_string($desc)
            . "', last_update_by=$usrID, last_update_date='$dateStr', 
                hierchy_type='" . loc_db_escape_string($hrchyTyp)
            . "',  sql_select_stmnt='" . loc_db_escape_string($sqlStmnt) . "', 
            is_enabled='" . loc_db_escape_string($isEnbld) .
            "' WHERE hierarchy_id = " . $hrchyID;
    execUpdtInsSQL($insSQL);
}

function deleteWkfHrchy($hrchyID, $hrchyNm = "") {
    //$affctd1 = 0;
    $affctd2 = 0;
    $affctd3 = 0;

    //$insSQL = "DELETE FROM wkf.wkf_pstn_hierarchy_details WHERE hierarchy_id = " . $hrchyID;
    //$affctd1 += execUpdtInsSQL($insSQL, "Hierarchy Name:" . $hrchyNm);
    $insSQL = "DELETE FROM wkf.wkf_manl_hierarchy_details WHERE hierarchy_id = " . $hrchyID;
    $affctd2 += execUpdtInsSQL($insSQL, "Hierarchy Name:" . $hrchyNm);
    $insSQL = "DELETE FROM wkf.wkf_hierarchy_hdr WHERE hierarchy_id = " . $hrchyID;
    $affctd3 += execUpdtInsSQL($insSQL, "Hierarchy Name:" . $hrchyNm);
    if ($affctd3 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        //$dsply .= "<br/>$affctd1 Position Hierarchies Deleted!";
        $dsply .= "<br/>$affctd2 Manual Hierarchies Deleted!";
        $dsply .= "<br/>$affctd3 Hierarchy Headers Deleted!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function createWkfMnlHrchy($hrchyID, $prsnID, $hrchyLvl, $isEnbld, $position_id, $apprvr_group_id, $aprvrTyp = "Individual") {
    global $usrID;
    $dateStr = getDB_Date_time();

    $insSQL = "INSERT INTO wkf.wkf_manl_hierarchy_details(
            hierarchy_id, person_id, hrchy_level, created_by, 
            creation_date, last_update_by, last_update_date, is_enabled, 
            position_id, apprvr_group_id, apprvr_type) " .
            "VALUES ($hrchyID, $prsnID, $hrchyLvl," . $usrID . ", '" . $dateStr
            . "', " . $usrID . ", '" . $dateStr
            . "', '" . loc_db_escape_string($isEnbld) . "', " . $position_id . ", " . $apprvr_group_id .
            ", '" . loc_db_escape_string($aprvrTyp) . "')";

    return execUpdtInsSQL($insSQL);
}

function updateWkfMnlHrchy($hrchyDetID, $prsnID, $hrchyLvl, $isEnbld, $position_id, $apprvr_group_id, $aprvrTyp = "Individual") {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE wkf.wkf_manl_hierarchy_details SET " .
            "person_id=" . $prsnID .
            ", hrchy_level=" . $hrchyLvl .
            ", last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr .
            "', is_enabled='" . loc_db_escape_string($isEnbld) .
            "', position_id = " . $position_id .
            ", apprvr_group_id = " . $apprvr_group_id .
            ", apprvr_type='" . loc_db_escape_string($aprvrTyp) .
            "' WHERE mnl_hrchy_det_id = " . $hrchyDetID;
    return execUpdtInsSQL($insSQL);
}

function deleteWkfMnlHrchy($hrchyDetID, $apprvNm = "") {
    $insSQL = "DELETE FROM wkf.wkf_manl_hierarchy_details WHERE mnl_hrchy_det_id = " . $hrchyDetID;
    $affctd1 = execUpdtInsSQL($insSQL, "Approver Name:" . $apprvNm);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Manual Hierarchy(ies)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function createWkfPosHrchy($hrchyID, $postnID, $hrchyLvl, $isEnbld) {
    global $usrID;
    $dateStr = getDB_Date_time();

    $insSQL = "INSERT INTO wkf.wkf_pstn_hierarchy_details(
            hierarchy_id, position_id, hrchy_level, created_by, 
            creation_date, last_update_by, last_update_date, is_enabled) " .
            "VALUES ($hrchyID, $postnID, $hrchyLvl," . $usrID . ", '" . $dateStr
            . "', " . $usrID . ", '" . $dateStr
            . "', '" . loc_db_escape_string($isEnbld) . "')";

    execUpdtInsSQL($insSQL);
}

function updateWkfPosHrchy($hrchyDetID, $postnID, $hrchyLvl, $isEnbld) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE wkf.wkf_pstn_hierarchy_details SET 
            position_id=$postnID, hrchy_level=$hrchyLvl, "
            . "last_update_by=$usrID, last_update_date='$dateStr', 
            is_enabled='" . loc_db_escape_string($isEnbld) .
            "' WHERE hierarchy_det_id = " . $hrchyDetID;
    execUpdtInsSQL($insSQL);
}

function deleteWkfPosHrchy($hrchyDetID) {
    $insSQL = "DELETE FROM wkf.wkf_pstn_hierarchy_details WHERE hierarchy_det_id = " . $hrchyDetID;
    $affctd1 += execUpdtInsSQL($insSQL);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Position Hierarchy(ies)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_WkfAppHrchyID($pkID, $hrchyID) {
    $sqlStr = "SELECT app_hrchy_id 
  FROM wkf.wkf_apps_n_hrchies a
  WHERE (a.app_id = " . $pkID . " and hierarchy_id=" . $hrchyID .
            ")";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function get_MnlHrchyDetID($pkID, $apprvrID, $hrchyLvl) {
    $sqlStr = "SELECT mnl_hrchy_det_id 
  FROM wkf.wkf_manl_hierarchy_details a
  WHERE (a.hierarchy_id = " . $pkID .
            " and (CASE WHEN a.apprvr_type='Position Holder' THEN position_id " .
            " WHEN a.apprvr_type='Group' THEN apprvr_group_id " .
            " ELSE person_id " .
            " END)=" . $apprvrID .
            " and hrchy_level = " . $hrchyLvl . ") ORDER BY a.hrchy_level";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function get_PosHrchyDetID($pkID, $postnID, $hrchyLvl) {
    $sqlStr = "SELECT hierarchy_det_id 
  FROM wkf.wkf_pstn_hierarchy_details a
  WHERE (a.hierarchy_id = " . $pkID . " and position_id=" . $postnID .
            " and hrchy_level = " . $hrchyLvl . ") ORDER BY a.hrchy_level";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function get_HrchyCntntMnl($pkID) {
    $sqlStr = "SELECT mnl_hrchy_det_id mt,
         a.apprvr_type apprv_typ,
         CASE WHEN a.apprvr_type = 'Group' THEN 
         (Select z.group_name from wkf.wkf_apprvr_groups z where z.apprvr_group_id = a.apprvr_group_id)
              WHEN a.apprvr_type = 'Position Holder' THEN 
                org.get_pos_name(a.position_id) 
              ELSE prs.get_prsn_name(a.person_id) || ' ('||prs.get_prsn_loc_id(a.person_id)||')' 
          END apprvr_name,
          CASE WHEN a.apprvr_group_id > 0 THEN a.apprvr_group_id::bigint 
               WHEN a.position_id>0 THEN a.position_id::bigint 
               ELSE a.person_id 
        END apprv_id,
            hrchy_level,
            is_enabled
  FROM wkf.wkf_manl_hierarchy_details a
  WHERE (a.hierarchy_id = " . $pkID . ") ORDER BY a.hrchy_level";
//echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_HrchyCntntPos($pkID) {
    $sqlStr = "SELECT hierarchy_det_id mt, 
        position_id , 
        org.get_pos_name(position_id) pos_name, 
        hrchy_level, is_enabled
  FROM wkf.wkf_pstn_hierarchy_details a
  WHERE (a.hierarchy_id = " . $pkID . ") ORDER BY a.hrchy_level";

    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_WkfHrchyDet($pkID) {
    $sqlStr = "SELECT hierarchy_id mt, hierarchy_name, description, 
        CASE WHEN is_enabled='1' THEN 'Yes' ELSE 'No' END \"is_enabled\", 
        hierchy_type, sql_select_stmnt
  FROM wkf.wkf_hierarchy_hdr 
        WHERE (hierarchy_id = $pkID)";
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_WkfHrchyTblr($searchFor, $searchIn, $offset, $limit_size) {

    $wherecls = "1=1";
//"Message Header", "Message Date", "Message Status", "Source App", "Source Module"
    if ($searchIn === "Name") {
        $wherecls = "hierarchy_name ilike '" .
                loc_db_escape_string($searchFor) . "'";
    } else if ($searchIn === "Description") {
        $wherecls = "description ilike '" .
                loc_db_escape_string($searchFor) . "'";
    }

    $sqlStr = "SELECT hierarchy_id , hierarchy_name, description, hierchy_type, sql_select_stmnt, is_enabled " .
            "FROM wkf.wkf_hierarchy_hdr " .
            "WHERE ($wherecls) ORDER BY hierarchy_name 
 LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_WkfHrchyTtl($searchFor, $searchIn) {
    $wherecls = "1=1";
//"Message Header", "Message Date", "Message Status", "Source App", "Source Module"
    if ($searchIn === "Name") {
        $wherecls = "hierarchy_name ilike '" .
                loc_db_escape_string($searchFor) . "'";
    } else if ($searchIn === "Description") {
        $wherecls = "description ilike '" .
                loc_db_escape_string($searchFor) . "'";
    }

    $sqlStr = "SELECT count(1) " .
            "FROM wkf.wkf_hierarchy_hdr " .
            "WHERE ($wherecls)";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_WkfGrpMembers($pkID) {
    $sqlStr = "SELECT member_id mt, 
        prs.get_prsn_loc_id(person_id) \"ID No.\", 
        prs.get_prsn_name(person_id) full_name, 
        is_enabled
  FROM wkf.wkf_apprvr_group_members a
  WHERE (a.apprvr_group_id = " . $pkID . ") ORDER BY a.person_id";
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_WkfApprvrGrps($searchFor, $searchIn, $offset, $limit_size) {
    $wherecls = "";
//"Message Header", "Message Date", "Message Status", "Source App", "Source Module"
    if ($searchIn === "Name") {
        $wherecls = "group_name ilike '" .
                loc_db_escape_string($searchFor) . "'";
    } else if ($searchIn === "Description") {
        $wherecls = "group_description ilike '" .
                loc_db_escape_string($searchFor) . "'";
    }

    $sqlStr = "SELECT apprvr_group_id , group_name, group_description, "
            . "linked_firm_id, scm.get_cstmr_splr_name(linked_firm_id), is_enabled " .
            "FROM wkf.wkf_apprvr_groups " .
            "WHERE ($wherecls) ORDER BY group_name 
 LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_WkfApprvrGrpDet($pkID) {
    $sqlStr = "SELECT apprvr_group_id , group_name, group_description, "
            . "linked_firm_id, scm.get_cstmr_splr_name(linked_firm_id), is_enabled " .
            "FROM wkf.wkf_apprvr_groups " .
            "WHERE (apprvr_group_id=$pkID)";
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_WkfApprvrGrpsTtl($searchFor, $searchIn) {
    $wherecls = "";
//"Message Header", "Message Date", "Message Status", "Source App", "Source Module"
    if ($searchIn === "Name") {
        $wherecls = "group_name ilike '" .
                loc_db_escape_string($searchFor) . "'";
    } else if ($searchIn === "Description") {
        $wherecls = "group_description ilike '" .
                loc_db_escape_string($searchFor) . "'";
    }

    $sqlStr = "SELECT count(1) " .
            "FROM wkf.wkf_apprvr_groups " .
            "WHERE ($wherecls)";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function createWkfApprvrGrps($grpNm, $grpDesc, $lnkdFrmID, $isEnbld) {
    global $usrID;
    global $orgID;
    $dateStr = getDB_Date_time();

    $insSQL = "INSERT INTO wkf.wkf_apprvr_groups(
            group_name, group_description, linked_firm_id, 
            org_id, is_enabled, created_by, creation_date, last_update_by, 
            last_update_date) " .
            "VALUES ('" . loc_db_escape_string($grpNm)
            . "', '" . loc_db_escape_string($grpDesc)
            . "'," . $lnkdFrmID . "," . $orgID
            . ", '" . loc_db_escape_string($isEnbld)
            . "'," . $usrID . ", '" . $dateStr
            . "', " . $usrID . ", '" . $dateStr
            . "')";

    execUpdtInsSQL($insSQL);
}

function updateWkfApprvrGrps($grpID, $grpNm, $grpDesc, $lnkdFrmID, $isEnbld) {
    global $usrID;
    global $orgID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE wkf.wkf_apprvr_groups SET 
            group_name='" . loc_db_escape_string($grpNm)
            . "', group_description='" . loc_db_escape_string($grpDesc)
            . "', last_update_by=$usrID, last_update_date='$dateStr', 
                linked_firm_id=" . $lnkdFrmID
            . ", org_id=" . $orgID . ", is_enabled='" . loc_db_escape_string($isEnbld) .
            "' WHERE apprvr_group_id = " . $grpID;
    execUpdtInsSQL($insSQL);
}

function deleteWkfApprvrGrps($grpID, $grpNm = "") {
    $affctd1 = 0;
    $affctd2 = 0;
    $affctd3 = 0;

    $insSQL = "DELETE FROM wkf.wkf_apprvr_group_members WHERE apprvr_group_id = " . $grpID;
    $affctd1 += execUpdtInsSQL($insSQL, "Group Name:" . $grpNm);
    $insSQL = "DELETE FROM wkf.wkf_apprvr_groups WHERE apprvr_group_id = " . $grpID;
    $affctd3 += execUpdtInsSQL($insSQL, "Group Name:" . $grpNm);
    if ($affctd3 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd2 Group Member(s) Deleted!";
        $dsply .= "<br/>$affctd3 Approver Group(s) Deleted!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function createWkfGrpMembers($grpID, $prsnID, $isEnbld) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO wkf.wkf_apprvr_group_members(
            apprvr_group_id, person_id, created_by, 
            creation_date, last_update_by, last_update_date, is_enabled) " .
            "VALUES ($grpID, $prsnID, " . $usrID . ", '" . $dateStr
            . "', " . $usrID . ", '" . $dateStr
            . "', '" . loc_db_escape_string($isEnbld) . "')";
    return execUpdtInsSQL($insSQL);
}

function updateWkfGrpMembers($memberID, $prsnID, $isEnbld) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE wkf.wkf_apprvr_group_members SET 
            person_id=$prsnID, "
            . "last_update_by=$usrID, last_update_date='$dateStr', 
            is_enabled='" . loc_db_escape_string($isEnbld) .
            "' WHERE member_id = " . $memberID;
    return execUpdtInsSQL($insSQL);
}

function deleteWkfGrpMembers($memberID, $memberNm = "") {
    $insSQL = "DELETE FROM wkf.wkf_apprvr_group_members WHERE member_id = " . $memberID;
    $affctd1 = execUpdtInsSQL($insSQL, "Member Name:" . $memberNm);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Group Member(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getLnkdFrmApprvrGrpID($lnkdFrmID) {
    $selSQL = "select apprvr_group_id 
from wkf.wkf_apprvr_groups where (linked_firm_id=" . $lnkdFrmID . ")";
    $result1 = executeSQLNoParams($selSQL);
    while ($row = loc_db_fetch_array($result1)) {
        return (float) $row[0];
    }
    return -1;
}

?>
