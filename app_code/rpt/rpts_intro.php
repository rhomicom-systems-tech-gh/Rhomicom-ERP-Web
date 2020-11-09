<?php

$menuItems = array("Run Reports / Processes", "Run Alerts", "Scheduled Report Runs", "Process Runners", "All Run Processes");
$menuImages = array("start.png", "alert.png", "Calander.png", "settings.png", "check_all.png");

$mdlNm = "Reports And Processes";
$ModuleName = $mdlNm;

$dfltPrvldgs = array("View Reports And Processes",
    /* 1 */ "View Report Definitions", "View Report Runs", "View SQL", "View Record History",
    /* 5 */ "Add Report/Process", "Edit Report/Process", "Delete Report/Process",
    /* 8 */ "Run Reports/Process", "Delete Report/Process Runs", "View Runs from Others",
    /* 11 */ "Delete Run Output File", "Add Alert", "Edit Alert", "Delete Alert");
$canview = test_prmssns($dfltPrvldgs[0], $ModuleName) || test_prmssns("View Self-Service", "Self Service");
$caneditRpts = test_prmssns($dfltPrvldgs[6], $ModuleName);
$canaddRpts = test_prmssns($dfltPrvldgs[5], $ModuleName);
$candelRpts = test_prmssns($dfltPrvldgs[7], $ModuleName);
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
					<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type');\">
						<span style=\"text-decoration:none;\">Reports Menu</span>
					</li>";
if ($lgn_num > 0 && $canview === true) {
    if ($pgNo == 0) {
        $cntent .= "
                                       </ul>
                                     </div>" . "<div style=\"font-family: Tahoma, Arial, sans-serif;font-size: 1.3em;
                    padding:10px 15px 15px 20px;border:1px solid #ccc;\">                    
      <div style=\"padding:5px 30px 5px 10px;margin-bottom:2px;\">
                    <span style=\"font-family: georgia, times;font-size: 12px;font-style:italic;
                    font-weight:normal;\">This is where all reports and background processes are created and run . The module has the ff areas:</span>
                    </div>
      <p>";
        $grpcntr = 0;
        for ($i = 0; $i < count($menuItems); $i++) {
            $No = $i + 1;
            if (($i == 0 || $i == 1 || $i == 2 || $i == 4) && test_prmssns($dfltPrvldgs[2], $mdlNm) == FALSE && test_prmssns("View Self-Service",
                            "Self Service") == FALSE) {
                continue;
            } else if ($i == 3 && test_prmssns($dfltPrvldgs[5], $mdlNm) == FALSE && test_prmssns($dfltPrvldgs[6], $mdlNm) == FALSE) {
                continue;
            }
            if ($grpcntr == 0) {
                $cntent .= "<div class=\"row\">";
            }

            $cntent .= "<div class=\"col-md-3 colmd3special2\">
        <button type=\"button\" class=\"btn btn-default btn-lg btn-block modulesButton\" onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$No&vtyp=0');\">
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
        //Report Runner   
        require 'rpts_rnnr.php';
    } else if ($pgNo == 2) {
        require "rpts_alrts.php";
    } else if ($pgNo == 3) {
        require "schld_runs.php";
    } else if ($pgNo == 4) {
        require "prcs_rnnrs.php";
    } else if ($pgNo == 5) {
        require "all_rpts_rns.php";
    } else {
        restricted();
    }
} else {
    restricted();
}

function deleteReportRun($hdrid) {
    $insSQL = "DELETE FROM rpt.rpt_report_runs WHERE rpt_run_id = " . $hdrid;
    $affctd = execUpdtInsSQL($insSQL);
    $insSQL1 = "DELETE FROM rpt.rpt_run_msgs WHERE process_id = " . $hdrid . " and process_typ='Process Run'";
    $affctd1 = execUpdtInsSQL($insSQL1);

    if ($affctd > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd Report Run(s)!";
        $dsply .= "<br/>$affctd1 Report Run Log(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">

    $dsply</p>";
    }
}

function deleteAlert($hdrid, $alrtNm = "") {
    if (isAlertInUse($hdrid)) {
        $dsply = "No Record Deleted!<br/>Alert has been run!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $insSQL = "DELETE FROM alrt.alrt_alerts WHERE alert_id = " . $hdrid;
    $affctd = execUpdtInsSQL($insSQL, "Report Name:" . $alrtNm);
    $insSQL1 = "DELETE FROM rpt.rpt_run_schdule_params WHERE alert_id = " . $hdrid . "";
    $affctd1 = execUpdtInsSQL($insSQL1, "Report Name:" . $alrtNm);
    if ($affctd > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd Alert(s)!";
        $dsply .= "<br/>$affctd1 Alert Scheduled Parameter(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteReport($hdrid, $rptNm = "") {
    if (isRptInUse($hdrid)) {
        $dsply = "No Record Deleted!<br/>Report/Process has been run!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $insSQL = "DELETE FROM rpt.rpt_reports WHERE report_id = " . $hdrid;
    $affctd = execUpdtInsSQL($insSQL, "Report Name:" . $rptNm);
    $insSQL1 = "DELETE FROM rpt.rpt_report_parameters WHERE report_id = " . $hdrid . "";
    $affctd1 = execUpdtInsSQL($insSQL1, "Report Name:" . $rptNm);
    $insSQL2 = "DELETE FROM rpt.rpt_reports_allwd_roles WHERE report_id = " . $hdrid . "";
    $affctd2 = execUpdtInsSQL($insSQL2, "Report Name:" . $rptNm);
    $insSQL3 = "DELETE FROM rpt.rpt_run_schdule_params WHERE schedule_id IN (select schedule_id from rpt.rpt_run_schdules where report_id = " . $hdrid . ")";
    $affctd3 = execUpdtInsSQL($insSQL3, "Report Name:" . $rptNm);
    $insSQL4 = "DELETE FROM rpt.rpt_run_schdules where report_id = " . $hdrid . "";
    $affctd4 = execUpdtInsSQL($insSQL4, "Report Name:" . $rptNm);

    if ($affctd > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd Report(s)!";
        $dsply .= "<br/>$affctd1 Report Parameter(s)!";
        $dsply .= "<br/>$affctd2 Report Role(s)!";
        $dsply .= "<br/>$affctd3 Report Run Schedule Parameter(s)!";
        $dsply .= "<br/>$affctd4 Report Run Schedule(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteRptParam($hdrid, $paramNm = "") {
    $insSQL = "DELETE FROM rpt.rpt_report_parameters WHERE parameter_id = " . $hdrid . "";
    $affctd = execUpdtInsSQL($insSQL, "Parameter Name:" . $paramNm);

    if ($affctd > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd Report Parameter(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteRptRole($hdrid, $roleNm = "") {
    $insSQL = "DELETE FROM rpt.rpt_reports_allwd_roles WHERE rpt_roles_id = " . $hdrid . "";
    $affctd = execUpdtInsSQL($insSQL, "Role Name:" . $roleNm);

    if ($affctd > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd Report Role(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteRptPrgm($hdrid, $prgmNm = "") {
    $insSQL = "DELETE FROM rpt.rpt_set_prgrm_units WHERE set_unit_id = " . $hdrid . "";
    $affctd = execUpdtInsSQL($insSQL, "Program Name:" . $prgmNm);

    if ($affctd > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd Report Sub-Program(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
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

function get_RptsTblr($searchWord, $searchIn, $offset, $limit_size) {
    global $caneditRpts;

    $whereCls = "";
    if ($searchIn == "Report Name") {
        $whereCls = "(a.report_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Report Description") {
        $whereCls = "(a.report_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Owner Module") {
        $whereCls = "(a.owner_module ilike '" . loc_db_escape_string($searchWord) . "')";
    } else {
        $whereCls = "(a.report_name ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $strSql = "";
    if ($caneditRpts == false) {
        $strSql = "SELECT distinct a.report_id mt, a.report_name, a.report_desc mt
    FROM rpt.rpt_reports a,
    rpt.rpt_reports_allwd_roles b
    WHERE ((a.report_id = b.report_id) and (b.user_role_id IN (" . concatCurRoleIDs() . ")) and $whereCls)
    ORDER BY a.report_id DESC LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    } else {
        $strSql = "SELECT distinct a.report_id mt, a.report_name, a.report_desc mt
    FROM rpt.rpt_reports a
    WHERE ($whereCls)
    ORDER BY a.report_id DESC LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    }
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_RptsToExprt($searchWord, $searchIn, $offset, $limit_size) {
    global $caneditRpts;

    $whereCls = "";
    if ($searchIn == "Report Name") {
        $whereCls = "(a.report_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Report Description") {
        $whereCls = "(a.report_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Owner Module") {
        $whereCls = "(a.owner_module ilike '" . loc_db_escape_string($searchWord) . "')";
    } else {
        $whereCls = "(a.report_name ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $strSql = "";
    if ($caneditRpts == false) {
        $strSql = "SELECT distinct report_id, report_name, report_desc, rpt_sql_query, " .
                "owner_module, rpt_or_sys_prcs, CASE WHEN is_enabled='1' THEN 'YES' ELSE 'NO' END, cols_to_group, cols_to_count, " .
                "a.cols_to_sum, a.cols_to_average, a.cols_to_no_frmt, a.output_type, a.portrait_lndscp
      ,a.process_runner , a.rpt_layout, a.imgs_col_nos, a.csv_delimiter, a.jrxml_file_name, a.pre_rpt_sql_query, a.pst_rpt_sql_query
    FROM rpt.rpt_reports a,
    rpt.rpt_reports_allwd_roles b
    WHERE ((a.report_id = b.report_id) and (b.user_role_id IN (" . concatCurRoleIDs() . ")) and $whereCls)
    ORDER BY a.report_id DESC LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    } else {
        $strSql = "SELECT distinct report_id, report_name, report_desc, rpt_sql_query, " .
                "owner_module, rpt_or_sys_prcs, "
                . "CASE WHEN is_enabled='1' THEN 'YES' ELSE 'NO' END, cols_to_group, cols_to_count, " .
                "a.cols_to_sum, a.cols_to_average, a.cols_to_no_frmt, a.output_type, a.portrait_lndscp
      ,a.process_runner , a.rpt_layout, a.imgs_col_nos, a.csv_delimiter, a.jrxml_file_name, a.pre_rpt_sql_query, a.pst_rpt_sql_query 
    FROM rpt.rpt_reports a
    WHERE ($whereCls)
    ORDER BY a.report_id DESC LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    }
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_RptsTtl($searchWord, $searchIn) {
    global $caneditRpts;

    $whereCls = "";
    if ($searchIn == "Report Name") {
        $whereCls = "(a.report_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Report Description") {
        $whereCls = "(a.report_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Owner Module") {
        $whereCls = "(a.owner_module ilike '" . loc_db_escape_string($searchWord) . "')";
    } else {
        $whereCls = "(a.report_name ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $strSql = "";
    if ($caneditRpts == false) {
        $strSql = "SELECT count(1) FROM 
            (SELECT distinct a.report_id mt, a.report_name, a.report_desc mt
    FROM rpt.rpt_reports a,
    rpt.rpt_reports_allwd_roles b
    WHERE ((a.report_id = b.report_id) and (b.user_role_id IN (" . concatCurRoleIDs() . ")) and $whereCls)) tbl1";
    } else {
        $strSql = "SELECT count(1) FROM 
            (SELECT distinct a.report_id mt, a.report_name, a.report_desc mt
    FROM rpt.rpt_reports a
    WHERE ($whereCls)) tbl1";
    }
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_ASchdlRuns($searchWord, $searchIn, $offset, $limit_size) {
    global $dfltPrvldgs;
    global $usrID;
    global $mdlNm;

    $whereCls = "";
    $extrWhrcls = "";
    if (test_prmssns($dfltPrvldgs[10], $mdlNm) == false) {
        $extrWhrcls = " and (a.created_by = $usrID)";
    }
    if ($searchIn == "Report Name") {
        $whereCls = " and (b.report_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Start Date") {
        $whereCls = " and (to_char(to_timestamp(a.start_dte_tme, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Repeat Interval") {
        $whereCls = " and ('' || a.repeat_every ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Created By") {
        $whereCls = " and (sec.get_usr_name(a.created_by) ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $strSql = "SELECT a.schedule_id, a.report_id mt, b.report_name,
    to_char(to_timestamp(a.start_dte_tme, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') start_date,
    a.repeat_every, a.repeat_uom, sec.get_usr_name(a.created_by) created_by
    FROM rpt.rpt_run_schdules a, rpt.rpt_reports b
    WHERE a.report_id = b.report_id" . $whereCls . $extrWhrcls .
            " ORDER BY a.schedule_id DESC LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    //echo $strSql;
    return $result;
}

function get_ASchdlRunsTtl($searchWord, $searchIn) {
    global $dfltPrvldgs;
    global $usrID;
    global $mdlNm;

    $whereCls = "";
    $extrWhrcls = "";
    if (test_prmssns($dfltPrvldgs[10], $mdlNm) == false) {
        $extrWhrcls = " and (a.created_by = $usrID)";
    }
    if ($searchIn == "Report Name") {
        $whereCls = " and (b.report_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Start Date") {
        $whereCls = " and (to_char(to_timestamp(a.start_dte_tme, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Repeat Interval") {
        $whereCls = " and ('' || a.repeat_every ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Created By") {
        $whereCls = " and (sec.get_usr_name(a.created_by) ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $strSql = "SELECT count(1) 
    FROM rpt.rpt_run_schdules a, rpt.rpt_reports b
    WHERE a.report_id = b.report_id" . $whereCls . $extrWhrcls;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_RptRuns($pkID, $searchWord, $searchIn, $offset, $limit_size, $sortBy = "Report Run ID") {
    global $dfltPrvldgs;
    global $usrID;
    global $mdlNm;
    /* "Report Run ID", "Last Active Time" */
    $whereCls = "";
    $ordrByCls = "a.rpt_run_id DESC";
    if ($sortBy == "Last Active Time") {
        $ordrByCls = "a.last_actv_date_tme DESC";
    }
    if ($searchIn == "Report Run ID") {
        $whereCls = " and (trim(to_char(a.rpt_run_id, '99999999999999999999999999999999999999999999')) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Run Date") {
        $whereCls = " and (to_char(to_timestamp(a.date_sent, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Run By") {
        $whereCls = " and ((select b.user_name from
    sec.sec_users b where b.user_id = a.run_by) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Run Status") {
        $whereCls = " and (a.run_status_txt ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Report Name") {
        $whereCls = " and (rpt.get_rpt_name(a.report_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $extrWhrcls = "";
    if (test_prmssns($dfltPrvldgs[10], $mdlNm) == false) {
        $extrWhrcls = " and (a.run_by = $usrID)";
    }
    $strSql = "SELECT a.rpt_run_id \"Run ID\", 
        a.run_by mt, 
        (select b.user_name from sec.sec_users b where b.user_id = a.run_by) \"Run By\", 
        to_char(to_timestamp(a.run_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') date_run, 
          a.run_status_txt run_status_text, 
          a.run_status_prct \"Progress (%)\", 
          a.rpt_rn_param_ids mt, 
          a.rpt_rn_param_vals mt, 
          a.output_used, 
          a.orntn_used mt, 
    CASE WHEN a.last_actv_date_tme='' or a.last_actv_date_tme IS NULL THEN '' 
    ELSE to_char(to_timestamp(a.last_actv_date_tme,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') END last_time_active, 
    CASE WHEN is_this_from_schdler='1' THEN 'SCHEDULER' ELSE 'USER' END run_source ,
    a.rpt_run_id \"Open Output File\", 
    b.report_name mt, a.alert_id mt,
    age(to_timestamp(a.last_actv_date_tme,'YYYY-MM-DD HH24:MI:SS'),to_timestamp(a.run_date,'YYYY-MM-DD HH24:MI:SS')) duration,
    a.report_id
      FROM rpt.rpt_report_runs a, rpt.rpt_reports b 
        WHERE (a.report_id = b.report_id and ((a.report_id = $pkID and a.alert_id<=0) or ($pkID <= 0))$whereCls" . "$extrWhrcls) 
        ORDER BY " . $ordrByCls . " LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_RptRunsTtl($pkID, $searchWord, $searchIn) {
    global $dfltPrvldgs;
    global $usrID;
    global $mdlNm;

    $whereCls = "";
    if ($searchIn == "Report Run ID") {
        $whereCls = " and (trim(to_char(a.rpt_run_id, '99999999999999999999999999999999999999999999')) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Run Date") {
        $whereCls = " and (to_char(to_timestamp(a.date_sent, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Run By") {
        $whereCls = " and ((select b.user_name from
    sec.sec_users b where b.user_id = a.run_by) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Run Status") {
        $whereCls = " and (a.run_status_txt ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Report Name") {
        $whereCls = " and (rpt.get_rpt_name(a.report_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $extrWhrcls = "";
    if (test_prmssns($dfltPrvldgs[10], $mdlNm) == false) {
        $extrWhrcls = " and (a.run_by = $usrID)";
    }
    $strSql = "SELECT count(1) 
      FROM rpt.rpt_report_runs a 
        WHERE (((a.report_id = $pkID and a.alert_id<=0)or($pkID <= 0))$whereCls" . "$extrWhrcls)";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_AlrtRuns($pkID, $searchWord, $searchIn, $offset, $limit_size) {
    global $dfltPrvldgs;
    global $usrID;
    global $mdlNm;

    $whereCls = "";
    if ($searchIn == "Report Run ID") {
        $whereCls = " and (trim(to_char(a.rpt_run_id, '99999999999999999999999999999999999999999999')) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Run Date") {
        $whereCls = " and (to_char(to_timestamp(a.date_sent, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Run By") {
        $whereCls = " and ((select b.user_name from
    sec.sec_users b where b.user_id = a.run_by) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Run Status") {
        $whereCls = " and (a.run_status_txt ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $extrWhrcls = "";
    if (test_prmssns($dfltPrvldgs[10], $mdlNm) == false) {
        $extrWhrcls = " and (a.run_by = $usrID)";
    }
    $strSql = "SELECT a.rpt_run_id \"Run ID\", 
        a.run_by mt, 
        (select b.user_name from sec.sec_users b where b.user_id = a.run_by) \"Run By\", 
        to_char(to_timestamp(a.run_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') date_run, 
          a.run_status_txt run_status_text, 
          a.run_status_prct \"Progress (%)\", 
          a.rpt_rn_param_ids mt, 
          a.rpt_rn_param_vals mt, 
          a.output_used, 
          a.orntn_used mt, 
          CASE WHEN a.last_actv_date_tme='' or a.last_actv_date_tme IS NULL THEN '' ELSE to_char(to_timestamp(a.last_actv_date_tme,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') END last_time_active, 
          CASE WHEN is_this_from_schdler='1' THEN 'SCHEDULER' ELSE 'USER' END run_source,
          a.rpt_run_id \"Open Output File\", 
          b.report_name mt, 
          a.alert_id, 
          a.msg_sent_id,
          a.report_id
      FROM rpt.rpt_report_runs a, rpt.rpt_reports b 
        WHERE (a.report_id = b.report_id and (a.alert_id = $pkID)$whereCls" . "$extrWhrcls) 
        ORDER BY a.rpt_run_id DESC LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AlrtRunsTtl($pkID, $searchWord, $searchIn) {
    global $dfltPrvldgs;
    global $usrID;
    global $mdlNm;

    $whereCls = "";
    if ($searchIn == "Report Run ID") {
        $whereCls = " and (trim(to_char(a.rpt_run_id, '99999999999999999999999999999999999999999999')) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Run Date") {
        $whereCls = " and (to_char(to_timestamp(a.date_sent, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Run By") {
        $whereCls = " and ((select b.user_name from
    sec.sec_users b where b.user_id = a.run_by) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Run Status") {
        $whereCls = " and (a.run_status_txt ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $extrWhrcls = "";
    if (test_prmssns($dfltPrvldgs[10], $mdlNm) == false) {
        $extrWhrcls = " and (a.run_by = $usrID)";
    }
    $strSql = "SELECT count(1) 
      FROM rpt.rpt_report_runs a 
        WHERE ((a.alert_id = $pkID)$whereCls" . "$extrWhrcls)";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_RptAlerts($searchWord, $searchIn, $offset, $limit_size) {
    global $caneditRpts;
    $whereCls = "";

    if ($searchIn == "Alert Name") {
        $whereCls = " and (a.alert_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Report Name") {
        $whereCls = " and (b.report_name ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "";
    if ($caneditRpts == false) {
        $strSql = "SELECT distinct a.alert_id, a.report_id, b.report_name, a.alert_name 
   FROM alrt.alrt_alerts a , rpt.rpt_reports b,
    rpt.rpt_reports_allwd_roles c
    WHERE a.report_id = b.report_id and b.report_id = c.report_id and (c.user_role_id IN (" . concatCurRoleIDs() . "))" . "$whereCls" .
                " ORDER BY 1 DESC LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    } else {
        $strSql = "SELECT distinct a.alert_id, a.report_id, b.report_name, a.alert_name 
   FROM alrt.alrt_alerts a , rpt.rpt_reports b
    WHERE a.report_id = b.report_id" . "$whereCls" .
                " ORDER BY 1 DESC LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    }

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_RptAlertsDet($pkID) {
    $strSql = "SELECT a.alert_id, a.alert_name, a.report_id, b.report_name, 
       a.alert_desc, a.to_mail_num_list_mnl, a.cc_mail_num_list_mnl, 
       a.alert_msg_body_mnl, a.alert_type, a.is_enabled, a.msg_sbjct_mnl, a.bcc_mail_num_list_mnl, 
       a.paramtr_sets_gnrtn_sql, a.shd_rpt_be_run, 
       to_char(to_timestamp(a.start_dte_tme,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') start_dte_tme, 
       a.repeat_uom, a.repeat_every, a.run_at_spcfd_hour, a.attchment_urls, a.end_hour 
      FROM alrt.alrt_alerts a, rpt.rpt_reports b 
      WHERE a.report_id = b.report_id and a.alert_id = $pkID";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_OneAlertsMsgDet($pkID) {
    $strSql = "SELECT a.msg_sent_id mt, a.to_list \"To\", a.cc_list \"Cc\", a.bcc_list \"Bcc\", 
        a.msg_type \"Message Type\", a.msg_sbjct \"Subject\", a.msg_body \"Message\", 
        a.date_sent, a.sending_status \"Status\", a.err_msg \"Error Message\", 
       a.attch_urls \"Atachments\", a.report_id mt, a.person_id mt, a.cstmr_spplr_id mt, 
       a.alert_id mt
  FROM alrt.alrt_msgs_sent a
      WHERE a.msg_sent_id = $pkID";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_RptAlertsTtl($searchWord, $searchIn) {
    global $caneditRpts;
    $whereCls = "";

    if ($searchIn == "Alert Name") {
        $whereCls = " and (a.alert_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Report Name") {
        $whereCls = " and (b.report_name ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "";
    if ($caneditRpts == false) {
        $strSql = "SELECT count(distinct a.alert_id)   
   FROM alrt.alrt_alerts a , rpt.rpt_reports b,
    rpt.rpt_reports_allwd_roles c
    WHERE a.report_id = b.report_id and b.report_id = c.report_id and (c.user_role_id IN (" . concatCurRoleIDs() . "))" . "$whereCls";
    } else {
        $strSql = "SELECT count(distinct a.alert_id)   
   FROM alrt.alrt_alerts a , rpt.rpt_reports b
    WHERE a.report_id = b.report_id" . "$whereCls";
    }
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_OneSchdlRun($pkID) {
    $strSql = "SELECT a.schedule_id, a.report_id mt, b.report_name,
    to_char(to_timestamp(a.start_dte_tme, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') start_date,
    a.repeat_every, a.repeat_uom, a.run_at_spcfd_hour 
    FROM rpt.rpt_run_schdules a, rpt.rpt_reports b
    WHERE a.report_id = b.report_id and a.schedule_id = $pkID ORDER BY a.schedule_id DESC";
    $result = executeSQLNoParams($strSql);
    //echo $strSql;
    return $result;
}

function get_OneRptRun($pkID) {

    $strSql = "SELECT a.rpt_run_id \"Run ID\", 
        a.run_by mt, 
        (select b.user_name from sec.sec_users b where b.user_id = a.run_by) \"Run By\", 
        to_char(to_timestamp(a.run_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') date_run, 
          a.run_status_txt run_status_text, 
          a.run_status_prct \"Progress (%)\", 
          a.rpt_rn_param_ids mt, 
          a.rpt_rn_param_vals mt, 
          a.output_used , 
          a.orntn_used orientation_used, 
    CASE WHEN a.last_actv_date_tme='' or a.last_actv_date_tme IS NULL THEN '' ELSE to_char(to_timestamp(a.last_actv_date_tme,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') END last_time_active, 
    CASE WHEN is_this_from_schdler='1' THEN 'SCHEDULER' ELSE 'USER' END run_source ,
    a.rpt_run_id \"Open Output File\", 
    a.report_id mt, 
    a.last_actv_date_tme, 
    a.alert_id,
    age(to_timestamp(a.last_actv_date_tme,'YYYY-MM-DD HH24:MI:SS'),to_timestamp(a.run_date,'YYYY-MM-DD HH24:MI:SS')) duration
      FROM rpt.rpt_report_runs a 
        WHERE ((a.rpt_run_id = $pkID ))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AllParams($rptID) {
    $strSql = "SELECT parameter_id , parameter_name, paramtr_rprstn_nm_in_query mt, default_value, 
 is_required mt, lov_name_id, lov_name, param_data_type, date_format, shd_be_dsplyd
 FROM rpt.rpt_report_parameters WHERE report_id = $rptID ORDER BY parameter_name";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AllRptRoles($rptID) {
    $strSql = "SELECT a.user_role_id, b.role_name, a.rpt_roles_id "
            . "FROM rpt.rpt_reports_allwd_roles a, sec.sec_roles b "
            . "WHERE a.report_id = $rptID and a.user_role_id = b.role_id
               ORDER BY a.rpt_roles_id";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AllSchdldParams($schdlID) {
    $strSql = "SELECT a.schdl_param_id mt, a.parameter_id mt, b.parameter_name, a.parameter_value, lov_name_id
      FROM rpt.rpt_run_schdule_params a, rpt.rpt_report_parameters b  
      WHERE a.parameter_id = b.parameter_id and a.schedule_id=$schdlID ORDER BY a.parameter_id";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AllRptPrgms($rptID) {
    $strSql = "SELECT a.set_unit_id mt, a.program_unit_id mt, b.report_name
      FROM rpt.rpt_set_prgrm_units a, rpt.rpt_reports b  
      WHERE a.program_unit_id = b.report_id and a.report_set_id=$rptID ORDER BY b.report_name";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_SchdldParamVal($schdlID, $paramID) {
    $strSql = "SELECT a.parameter_value
      FROM rpt.rpt_run_schdule_params a, rpt.rpt_report_parameters b  
      WHERE a.parameter_id = $paramID and a.schedule_id=$schdlID";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function get_SchdldAlrtParamVal($alrtID, $paramID) {
    $strSql = "SELECT a.parameter_value
      FROM rpt.rpt_run_schdule_params a, rpt.rpt_report_parameters b  
      WHERE a.parameter_id = $paramID and a.alert_id=$alrtID";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function get_Rpt_ColsToAct($rptID) {
    $strSql = "SELECT report_name, cols_to_group, cols_to_count, cols_to_sum, cols_to_average, cols_to_no_frmt,
        output_type, portrait_lndscp
      FROM rpt.rpt_reports WHERE report_id = $rptID";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function createRpt($rptNm, $rptDesc, $ownrMdl, $rptPrcs, $rptSQL, $isenbld, $colsGrp, $colsCnt, $colsSum, $colsAvrg
, $colsNoFrmt, $outptTyp, $orntn, $prcRnnr, $rptLyout, $dlmtr, $img_cols, $jxrmlFileNm, $preRptSQL = "", $pstRptSQL = "") {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO rpt.rpt_reports(" .
            "report_name, report_desc, rpt_sql_query, owner_module, " .
            "created_by, creation_date, last_update_by, last_update_date, " .
            "rpt_or_sys_prcs, is_enabled, cols_to_group, cols_to_count, " .
            "cols_to_sum, cols_to_average, cols_to_no_frmt, output_type, portrait_lndscp, 
            rpt_layout, imgs_col_nos, csv_delimiter, process_runner, jrxml_file_name, pre_rpt_sql_query, pst_rpt_sql_query) " .
            "VALUES ('" . loc_db_escape_string($rptNm) .
            "', '" . loc_db_escape_string($rptDesc) .
            "', '" . loc_db_escape_string($rptSQL) .
            "', '" . loc_db_escape_string($ownrMdl) .
            "', " . $usrID .
            ", '" . $dateStr .
            "', " . $usrID .
            ", '" . $dateStr .
            "', '" . loc_db_escape_string($rptPrcs) .
            "', '" . cnvrtBoolToBitStr($isenbld) .
            "', '" . loc_db_escape_string($colsGrp) .
            "', '" . loc_db_escape_string($colsCnt) .
            "', '" . loc_db_escape_string($colsSum) .
            "', '" . loc_db_escape_string($colsAvrg) .
            "', '" . loc_db_escape_string($colsNoFrmt) .
            "', '" . loc_db_escape_string($outptTyp) .
            "', '" . loc_db_escape_string($orntn) .
            "', '" . loc_db_escape_string($rptLyout) .
            "', '" . loc_db_escape_string($img_cols) .
            "', '" . loc_db_escape_string($dlmtr) .
            "', '" . loc_db_escape_string($prcRnnr) .
            "', '" . loc_db_escape_string($jxrmlFileNm) .
            "', '" . loc_db_escape_string($preRptSQL) .
            "', '" . loc_db_escape_string($pstRptSQL) .
            "')";
    return execUpdtInsSQL($insSQL);
}

function createAlert($alertNm, $alertDesc, $toMail, $ccMail, $msgBody, $isenbld, $alrtTyp, $sbjct, $bccMail, $paramsSQL
, $rptID, $runRpt, $strtDate, $rptUOM, $rptEvery, $runOnHour, $attchUrls, $endHour) {
    global $usrID;
    $strtDate = cnvrtDMYTmToYMDTm($strtDate);
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO alrt.alrt_alerts(
            alert_name, alert_desc, to_mail_num_list_mnl, cc_mail_num_list_mnl, 
            alert_msg_body_mnl, alert_type, created_by, creation_date, last_update_by, 
            last_update_date, is_enabled, msg_sbjct_mnl, bcc_mail_num_list_mnl, 
            paramtr_sets_gnrtn_sql, report_id, shd_rpt_be_run, start_dte_tme, 
            repeat_uom, repeat_every, run_at_spcfd_hour, attchment_urls, end_hour) " .
            "VALUES ('" . loc_db_escape_string($alertNm) .
            "', '" . loc_db_escape_string($alertDesc) .
            "', '" . loc_db_escape_string($toMail) .
            "', '" . loc_db_escape_string($ccMail) .
            "', '" . loc_db_escape_string($msgBody) .
            "', '" . loc_db_escape_string($alrtTyp) . "', " .
            $usrID .
            ", '" . $dateStr .
            "', " . $usrID .
            ", '" . $dateStr .
            "', '" . cnvrtBoolToBitStr($isenbld) .
            "', '" . loc_db_escape_string($sbjct) .
            "', '" . loc_db_escape_string($bccMail) .
            "', '" . loc_db_escape_string($paramsSQL) .
            "', " . $rptID .
            ", '" . cnvrtBoolToBitStr($runRpt) .
            "', '" . loc_db_escape_string($strtDate) .
            "', '" . loc_db_escape_string($rptUOM) .
            "', " . $rptEvery .
            ", '" . cnvrtBoolToBitStr($runOnHour) .
            "', '" . loc_db_escape_string($attchUrls) .
            "', " . loc_db_escape_string($endHour) . ")";
    return execUpdtInsSQL($insSQL);
}

function updateAlert($alertID, $alertNm, $alertDesc, $toMail, $ccMail, $msgBody, $isenbld, $alrtTyp, $sbjct, $bccMail, $paramsSQL
, $rptID, $runRpt, $strtDate, $rptUOM, $rptEvery, $runOnHour, $attchUrls, $endHour) {
    global $usrID;
    $strtDate = cnvrtDMYTmToYMDTm($strtDate);
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE alrt.alrt_alerts SET 
            alert_name='" . loc_db_escape_string($alertNm) .
            "', alert_desc='" . loc_db_escape_string($alertDesc) .
            "', to_mail_num_list_mnl='" . loc_db_escape_string($toMail) .
            "', cc_mail_num_list_mnl='" . loc_db_escape_string($ccMail) .
            "', alert_msg_body_mnl='" . loc_db_escape_string($msgBody) .
            "', alert_type='" . loc_db_escape_string($alrtTyp) .
            "', last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr .
            "', is_enabled='" . cnvrtBoolToBitStr($isenbld) .
            "', msg_sbjct_mnl='" . loc_db_escape_string($sbjct) .
            "', bcc_mail_num_list_mnl='" . loc_db_escape_string($bccMail) .
            "', paramtr_sets_gnrtn_sql='" . loc_db_escape_string($paramsSQL) .
            "', report_id=" . $rptID .
            ", shd_rpt_be_run='" . cnvrtBoolToBitStr($runRpt) .
            "', start_dte_tme='" . loc_db_escape_string($strtDate) .
            "', repeat_uom='" . loc_db_escape_string($rptUOM) .
            "', repeat_every=" . $rptEvery .
            ", run_at_spcfd_hour='" . cnvrtBoolToBitStr($runOnHour) .
            "', attchment_urls='" . loc_db_escape_string($attchUrls) .
            "', end_hour=" . $endHour .
            " WHERE alert_id = " . $alertID;
    return execUpdtInsSQL($updtSQL);
}

function createParam($rptID, $paramNm, $qryRep, $dfltVal, $isrqrd, $lov_name
, $dataType, $datefrmt, $lovNm, $isdsplyd = TRUE) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO rpt.rpt_report_parameters(" .
            "report_id, parameter_name, paramtr_rprstn_nm_in_query, " .
            "created_by, creation_date, last_update_by, last_update_date, " .
            "default_value, is_required, lov_name_id, param_data_type, date_format, lov_name, shd_be_dsplyd) " .
            "VALUES (" . $rptID .
            ", '" . loc_db_escape_string($paramNm) .
            "', '" . loc_db_escape_string($qryRep) .
            "', " . $usrID .
            ", '" . $dateStr .
            "', " . $usrID .
            ", '" . $dateStr .
            "', '" . loc_db_escape_string($dfltVal) .
            "', '" . cnvrtBoolToBitStr($isrqrd) .
            "', '" . loc_db_escape_string($lov_name) .
            "', '" . loc_db_escape_string($dataType) .
            "', '" . loc_db_escape_string($datefrmt) .
            "', '" . loc_db_escape_string($lovNm) . "', '" . cnvrtBoolToBitStr($isdsplyd) .
            "')";
    return execUpdtInsSQL($insSQL);
}

function createRptRole($rptID, $roleID) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO rpt.rpt_reports_allwd_roles(" .
            "report_id, user_role_id, created_by, creation_date) " .
            "VALUES (" . $rptID .
            ", " . $roleID .
            ", " . $usrID .
            ", '" . $dateStr .
            "')";
    return execUpdtInsSQL($insSQL);
}

function createRptPrgmUnt($rptID, $prmID) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO rpt.rpt_set_prgrm_units(
	report_set_id, program_unit_id, created_by, creation_date) " .
            "VALUES (" . $rptID .
            ", " . $prmID .
            ", " . $usrID .
            ", '" . $dateStr .
            "')";
    return execUpdtInsSQL($insSQL);
}

function updateRptJrxml($rptid, $jxrmlFileNm) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE rpt.rpt_reports SET " .
            "last_update_by = " . $usrID . ", " .
            "last_update_date = '" . $dateStr .
            "', jrxml_file_name='" . loc_db_escape_string($jxrmlFileNm) .
            "' " .
            "WHERE (report_id = " . $rptid . ")";
    return execUpdtInsSQL($updtSQL);
}

function updateRpt($rptid, $rptNm, $rptDesc, $ownrMdl, $rptPrcs, $rptSQL, $isenbld, $colsGrp, $colsCnt, $colsSum, $colsAvrg, $colsNoFrmt,
        $outptTyp, $orntn, $prcRnnr, $rptLyout, $dlmtr, $img_cols, $jxrmlFileNm, $preRptSQL = "", $pstRptSQL = "") {
    global $usrID;
    $dateStr = getDB_Date_time();

    $updtSQL = "UPDATE rpt.rpt_reports SET " .
            "report_name = '" . loc_db_escape_string($rptNm) .
            "', report_desc = '" . loc_db_escape_string($rptDesc) .
            "', rpt_sql_query = '" . loc_db_escape_string($rptSQL) . "', " .
            "owner_module = '" . loc_db_escape_string($ownrMdl) . "', " .
            "is_enabled = '" . cnvrtBoolToBitStr($isenbld) .
            "', last_update_by = " . $usrID . ", " .
            "last_update_date = '" . $dateStr .
            "', rpt_or_sys_prcs = '" . loc_db_escape_string($rptPrcs) .
            "', cols_to_group = '" . loc_db_escape_string($colsGrp) .
            "', cols_to_count = '" . loc_db_escape_string($colsCnt) .
            "', cols_to_sum = '" . loc_db_escape_string($colsSum) .
            "', cols_to_average = '" . loc_db_escape_string($colsAvrg) .
            "', cols_to_no_frmt = '" . loc_db_escape_string($colsNoFrmt) . "'" .
            ", output_type = '" . loc_db_escape_string($outptTyp) . "'" .
            ", portrait_lndscp = '" . loc_db_escape_string($orntn) .
            "', rpt_layout='" . loc_db_escape_string($rptLyout) .
            "', imgs_col_nos='" . loc_db_escape_string($img_cols) .
            "', csv_delimiter='" . loc_db_escape_string($dlmtr) .
            "', process_runner='" . loc_db_escape_string($prcRnnr) .
            "', jrxml_file_name='" . loc_db_escape_string($jxrmlFileNm) .
            "', pre_rpt_sql_query='" . loc_db_escape_string($preRptSQL) .
            "', pst_rpt_sql_query='" . loc_db_escape_string($pstRptSQL) .
            "' WHERE (report_id = " . $rptid . ")";
    return execUpdtInsSQL($updtSQL);
}

function updateParam($paramid, $paramNm, $qryRep, $dfltVal, $isrqrd, $lov_name, $dataType, $datefrmt, $lovNm, $isdsplyd = TRUE) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE rpt.rpt_report_parameters SET " .
            "parameter_name = '" . loc_db_escape_string($paramNm) .
            "', paramtr_rprstn_nm_in_query = '" . loc_db_escape_string($qryRep) .
            "', default_value = '" . loc_db_escape_string($dfltVal) .
            "', is_required = '" . cnvrtBoolToBitStr($isrqrd) .
            "', last_update_by = " . $usrID . ", " .
            "last_update_date = '" . $dateStr .
            "', lov_name_id = '" . loc_db_escape_string($lov_name) .
            "', param_data_type = '" . loc_db_escape_string($dataType) .
            "', date_format = '" . loc_db_escape_string($datefrmt) .
            "', lov_name = '" . loc_db_escape_string($lovNm) .
            "', shd_be_dsplyd = '" . cnvrtBoolToBitStr($isdsplyd) .
            "' WHERE (parameter_id = " . $paramid . ")";
    return execUpdtInsSQL($updtSQL);
}

function updateParamLOV($lovID, $paramNm, $qryRep) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE rpt.rpt_report_parameters SET " .
            "lov_name_id = '" . $lovID . "', last_update_by = " . $usrID . ", " .
            "last_update_date = '" . $dateStr .
            "' WHERE (paramtr_rprstn_nm_in_query = '" . loc_db_escape_string($qryRep) .
            "' and report_id >=1 and report_id <=500)";
    return execUpdtInsSQL($updtSQL);
}

function isRptInUse($rptID) {
    $strSql = "SELECT a.rpt_run_id " .
            "FROM rpt.rpt_report_runs a " .
            "WHERE(a.report_id  = " . $rptID . ") LIMIT 1";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return true;
    }
    return false;
}

function isAlertInUse($alertID) {
    $strSql = "SELECT a.rpt_run_id " .
            "FROM rpt.rpt_report_runs a " .
            "WHERE(a.alert_id  = " . $alertID . ") LIMIT 1";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return true;
    }
    return false;
}

function doesRptHvRole($rptID, $role_id) {
    $strSql = "SELECT rpt_roles_id FROM rpt.rpt_reports_allwd_roles " .
            "WHERE report_id = " . $rptID . " and user_role_id = " . $role_id;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function doesRptHvPrgm($rptID, $prgm_id) {
    $strSql = "SELECT set_unit_id FROM rpt.rpt_set_prgrm_units " .
            "WHERE report_set_id = " . $rptID . " and program_unit_id = " . $prgm_id;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function uploadDaJrxml($rptID, &$nwImgLoc) {
    global $tmpDest;
    global $ftp_base_db_fldr;
    global $usrID;
    global $fldrPrfx;
    global $smplTokenWord1;

    $msg = "";
    if (isset($_FILES["rptsJrxmlFile"])) {
        $allowedExts = array('jrxml');
        $flnm = $_FILES["rptsJrxmlFile"]["name"];
        $temp = explode(".", $flnm);
        $extension = end($temp);
        if ($_FILES["rptsJrxmlFile"]["error"] > 0) {
            $msg .= "Return Code: " . $_FILES["rptsJrxmlFile"]["error"] . "<br>";
        } else {
            $msg .= "Uploaded File: " . $_FILES["rptsJrxmlFile"]["name"] . "<br>";
            $msg .= "Type: " . $_FILES["rptsJrxmlFile"]["type"] . "<br>";
            $msg .= "Size: " . round(($_FILES["rptsJrxmlFile"]["size"]) / (1024 * 1024), 2) . " MB<br>";
            //$msg .= "Temp file: " . $_FILES["daPrsnAttchmnt"]["tmp_name"] . "<br>";
            if ((in_array($extension, $allowedExts))) {
                $nwFileName = encrypt1($rptID . "." . $extension, $smplTokenWord1) . "." . $extension;
                $img_src = $fldrPrfx . $tmpDest . "$nwFileName";
                move_uploaded_file($_FILES["rptsJrxmlFile"]["tmp_name"], $img_src);
                $ftp_src = $ftp_base_db_fldr . "/Rpts/jrxmls/$rptID" . "." . $extension;
                if (file_exists($img_src)) {
                    copy("$img_src", "$ftp_src");

                    $dateStr = getDB_Date_time();
                    $updtSQL = "UPDATE rpt.rpt_reports
                            SET jrxml_file_name='" . $rptID . "." . $extension .
                            "', last_update_by=" . $usrID .
                            ", last_update_date='" . $dateStr .
                            "' WHERE report_id=" . $rptID;
                    execUpdtInsSQL($updtSQL);
                }
                $msg .= "Report Stored Successfully!<br/>";
                $nwImgLoc = $msg . "$rptID" . "." . $extension;
                return TRUE;
            } else {
                $msg .= "Invalid file!<br/>File Type must be in the ff:<br/>" . implode(", ", $allowedExts);
                $nwImgLoc = $msg;
            }
        }
    }
    $msg .= "<br/>Invalid file";
    $nwImgLoc = $msg;
    return FALSE;
}

function get_AllParamsExprt($offset, $limit_size) {
    $strSql = "SELECT parameter_id, parameter_name, paramtr_rprstn_nm_in_query, default_value, " .
            "CASE WHEN is_required='1' THEN 'YES' ELSE 'NO' END, lov_name_id, param_data_type, "
            . "date_format, rpt.get_rpt_name(report_id), lov_name, CASE WHEN shd_be_dsplyd='1' THEN 'YES' ELSE 'NO' END shd_be_dsplyd "
            . "FROM rpt.rpt_report_parameters ORDER BY report_id, parameter_name  LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_SchduleID($USER_ID, $rptID, $strtdte) {
    $selSQL = "SELECT a.schedule_id 
       FROM rpt.rpt_run_schdules a, rpt.rpt_reports b 
        WHERE a.report_id=b.report_id and a.created_by=" . $USER_ID .
            " and a.report_id=" . $rptID . " and a.start_dte_tme='" . $strtdte .
            "'";
    $result = executeSQLNoParams($selSQL);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function get_SchduleParamID($schdlID, $paramID) {
    $selSQL = "SELECT a.schdl_param_id 
       FROM rpt.rpt_run_schdule_params a 
        WHERE a.schedule_id=" . $schdlID .
            " and a.parameter_id=" . $paramID . " ";
    $result = executeSQLNoParams($selSQL);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function get_AlertParamID($alertID, $paramID) {
    $selSQL = "SELECT a.schdl_param_id 
       FROM rpt.rpt_run_schdule_params a 
        WHERE a.alert_id=" . $alertID .
            " and a.parameter_id=" . $paramID . " ";
    $result = executeSQLNoParams($selSQL);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createPrcsSchdl($rptID, $strDteTm, $rptuom, $rptEvery, $rnAtHr) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO rpt.rpt_run_schdules(
            report_id, created_by, creation_date, last_update_by, 
            last_update_date, start_dte_tme, repeat_uom, repeat_every,
run_at_spcfd_hour) " .
            "VALUES (" . $rptID . ", " . $usrID . ", '" . $dateStr .
            "', " . $usrID . ", '" . $dateStr .
            "', '" . loc_db_escape_string($strDteTm) . "', '" . loc_db_escape_string($rptuom) .
            "', " . $rptEvery . ", '" . cnvrtBoolToBitStr($rnAtHr) . "')";
    return execUpdtInsSQL($insSQL);
}

function updatePrcsSchdl($schdlID, $rptID, $strDteTm, $rptuom, $rptEvery, $rnAtHr) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE rpt.rpt_run_schdules SET 
            report_id=" . $rptID . ", start_dte_tme='" . loc_db_escape_string($strDteTm) .
            "', repeat_uom='" . loc_db_escape_string($rptuom) .
            "', last_update_by=" . $usrID . ", last_update_date='" . $dateStr .
            "', repeat_every=" . $rptEvery .
            ", run_at_spcfd_hour = '" . cnvrtBoolToBitStr($rnAtHr) . "' WHERE schedule_id = " . $schdlID;
    return execUpdtInsSQL($insSQL);
}

function createPrcsSchdlParms($alertID, $schdlID, $paramID, $paramVal) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO rpt.rpt_run_schdule_params(
            schedule_id, parameter_id, parameter_value, created_by, 
            creation_date, last_update_by, last_update_date, alert_id) " .
            "VALUES (" . $schdlID .
            ", " . $paramID .
            ", '" . loc_db_escape_string($paramVal) .
            "', " . $usrID .
            ", '" . $dateStr .
            "', " . $usrID .
            ", '" . $dateStr .
            "', " . $alertID . ")";
    return execUpdtInsSQL($insSQL);
}

function updatePrcsSchdlParms($schdlParamID, $paramID, $paramVal) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE rpt.rpt_run_schdule_params SET 
            parameter_id=" . $paramID .
            ", parameter_value='" . loc_db_escape_string($paramVal) .
            "', last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr .
            "' WHERE schdl_param_id = " . $schdlParamID;
    return execUpdtInsSQL($insSQL);
}

function get_PrcsRnnrs() {
    $selSQL = "SELECT prcss_rnnr_id, rnnr_name, rnnr_desc, rnnr_lst_actv_dtetme, rnnr_status, 
       executbl_file_nm, crnt_rnng_priority 
       FROM rpt.rpt_prcss_rnnrs 
       ORDER BY rnnr_name";
    $result = executeSQLNoParams($selSQL);
    return $result;
}

function isRunnrRnng($rnnrNm) {
    $selSQL = "SELECT age(now(), 
to_timestamp(CASE WHEN rnnr_lst_actv_dtetme='' THEN '2013-01-01 00:00:00' ELSE rnnr_lst_actv_dtetme END, 'YYYY-MM-DD HH24:MI:SS')) " .
            "<= interval '50 second' 
       FROM rpt.rpt_prcss_rnnrs WHERE rnnr_name='" . loc_db_escape_string($rnnrNm) . "'";
    $result = executeSQLNoParams($selSQL);
    while ($row = loc_db_fetch_array($result)) {
        //logSessionErrs(print_r($row[0], true));
        $res = $row[0] == "f" ? false : true;
        //var_dump($res);
        return $res;
    }
    return false;
}

?>
