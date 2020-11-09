<?php

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