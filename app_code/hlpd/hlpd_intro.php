<?php

$menuItems = array("Summary Dashboard", "My Request Tickets", "All Request Tickets", "Tickets to Resolve", "Request Categories");
$menuImages = array("dashboard220.png", "chat.gif", "all_tickets.png", "my_tickets_256.png", "settings.png");

$mdlNm = "Service Desk Manager";
$ModuleName = $mdlNm;
$prsnid = $_SESSION['PRSN_ID'];
$orgID = $_SESSION['ORG_ID'];
$dfltPrvldgs = array("View Help Desk",
    /* 1 */ "View Help Desk Dashboard",
    /* 2 */ "View My Request Tickets", "View All Request Tickets",
    /* 4 */ "View SQL", "View Record History",
    /* 6 */ "Add Request Tickets", "Edit Request Tickets", "Delete Request Tickets",
    /* 9 */ "Add Tickets for Others", "Edit Tickets for Others", "Delete Tickets for Others",
    /* 12 */ "Add Request Categories", "Edit Request Categories", "Delete Request Categories");

$canview = test_prmssns($dfltPrvldgs[0], $mdlNm) || test_prmssns("View Self-Service", "Self Service");
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
if ($lgn_num > 0 && $canview === true) {
    if ($qstr == "DELETE") {
        
    } else if ($qstr == "UPDATE") {
        if ($actyp == 1) {
            
        }
    } else {
        $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type');\">
						<span style=\"text-decoration:none;\">Service Desk Menu</span>
					</li>";
        if ($pgNo == 0) {
            $cntent .= " </ul></div>
                <div style=\"font-family: Tahoma, Arial, sans-serif;font-size: 1.3em;padding:10px 15px 15px 20px;border:1px solid #ccc;\">                    
                    <div style=\"padding:5px 30px 5px 10px;margin-bottom:2px;\">
                    <span style=\"font-family: georgia, times;font-size: 12px;font-style:italic;
                    font-weight:normal;\">This module helps you to manage your support tickets and I.T Service requests! The module has the ff areas:</span>
                    </div>
        <p>";
            $grpcntr = 0;
            for ($i = 0; $i < count($menuItems); $i++) {
                $No = $i + 1;
                if ($i == 0) {
                    
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
            require "smmry_dshbrd.php";
        } else if ($pgNo == 2) {
            require "my_tickets.php";
        } else if ($pgNo == 3) {
            require "all_tickets.php";
        } else if ($pgNo == 4) {
            require "to_resolve.php";
        } else if ($pgNo == 5) {
            require "rqst_ctgrs.php";
        } else {
            restricted();
        }
    }
} else {
    restricted();
}

function get_RqstCtgrys($searchWord, $searchIn, $offset, $limit_size) {
    $whereCls = "";
    if ($searchIn == "Category Name") {
        $whereCls = " and (a.rqst_ctgry_name ilike '" . loc_db_escape_string($searchWord) . "' "
                . "or a.rqst_sys_code ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Description") {
        $whereCls = " and (a.rqst_ctgry_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT a.rqst_category_id, a.rqst_ctgry_name, a.rqst_ctgry_desc, a.rqst_sys_code, 
       a.is_enabled, a.created_by, a.creation_date, a.last_update_by, a.last_update_date, 
       a.program_to_run_id, b.report_name, 
       a.allwd_group_type, 
       a.allwd_group_value,
        org.get_criteria_name(a.allwd_group_value::bigint,a.allwd_group_type) group_name
  FROM hlpd.hlpd_rqst_categories a
  LEFT OUTER JOIN rpt.rpt_reports b ON (a.program_to_run_id = b.report_id)
        WHERE ((1=1)" . $whereCls . ")
    ORDER BY a.rqst_category_id DESC LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_RqstCtgrysTtl($searchWord, $searchIn) {
    $whereCls = "";
    if ($searchIn == "Category Name") {
        $whereCls = " and (a.rqst_ctgry_name ilike '" . loc_db_escape_string($searchWord) . "' "
                . "or a.rqst_sys_code ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Description") {
        $whereCls = " and (a.rqst_ctgry_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT count(a.rqst_category_id)
  FROM hlpd.hlpd_rqst_categories a
  LEFT OUTER JOIN rpt.rpt_reports b ON (a.program_to_run_id = b.report_id)
        WHERE ((1=1)" . $whereCls . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_AllTickets($searchWord, $searchIn, $offset, $limit_size, $orgID, $dte1, $dte2, $qNotClosed, $meRqsted = FALSE,
        $meToResolve = FALSE) {
    global $usrID;
    global $prsnid;
    $whereCls = "";
    if ($dte1 != "") {
        $dte1 = cnvrtDMYTmToYMDTm($dte1);
    }
    if ($dte2 != "") {
        $dte2 = cnvrtDMYTmToYMDTm($dte2);
    }
    if ($searchIn == "Requestor") {
        $whereCls .= "(a.rqstr_prsn_id_num ilike '" . loc_db_escape_string($searchWord) .
                "' or a.rqstr_name ilike '" . loc_db_escape_string($searchWord) .
                "' or a.rqstr_email ilike '" . loc_db_escape_string($searchWord) .
                "' or a.rqstr_contact_nos ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    } else if ($searchIn == "Ticket Number") {
        $whereCls .= "('' || a.rqst_ticket_id ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    } else {
        $whereCls .= "(a.rqst_subject ilike '" . loc_db_escape_string($searchWord) .
                "' or a.rqst_first_msg_body ilike '" . loc_db_escape_string($searchWord) .
                "' or a.rqst_closure_rmrks ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    }
    if ($qNotClosed) {
        $whereCls .= "(a.is_closed = '0') and ";
    }
    if ($meRqsted) {
        $whereCls .= "(a.rqstr_user_id = " . $usrID . " or a.lnkd_person_id = " . $prsnid . ") and ";
    }
    if ($meToResolve) {
        $whereCls .= "(org.does_prsn_hv_crtria_id(" . $prsnid . ", a.asgnd_group_value::bigint,a.asgnd_group_type)>0) and ";
    }
    
    $strSql = "SELECT a.rqst_ticket_id, 
                lpad('' || a.rqst_ticket_id, 5, '0'), 
                a.rqstr_user_id,
                sec.get_usr_name(a.rqstr_user_id), 
                a.rqstr_prsn_id_num, 
                a.rqstr_name, 
                a.rqstr_email, 
                a.rqstr_contact_nos, 
                a.rqst_category_id, 
                b.rqst_ctgry_name,
                a.rqst_subject, 
                a.rqst_first_msg_body, 
                a.rqst_status, 
                a.is_closed, 
                a.rqst_closure_rmrks, 
                a.asgnd_group_type, 
                a.asgnd_group_value,
                org.get_criteria_name(a.asgnd_group_value::bigint,a.asgnd_group_type) group_name, 
                a.created_by,
                sec.get_usr_name(a.created_by), 
                a.creation_date, 
                a.last_update_by,
                sec.get_usr_name(a.last_update_by), 
                a.last_update_date, 
                a.lnkd_cstmr_spplr_id, 
                a.lnkd_person_id, 
                a.rqstrs_rltnshp_type
        FROM hlpd.hlpd_all_rqst_tickets a,
             hlpd.hlpd_rqst_categories b " .
            "WHERE ((a.rqst_category_id=b.rqst_category_id) and " . $whereCls . "(a.org_id = " . $orgID . ")" .
            " and (to_timestamp(a.creation_date, 'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
            "', 'YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS'))) " .
            "ORDER BY a.rqst_ticket_id DESC LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AllTicketsTtl($searchWord, $searchIn, $orgID, $dte1, $dte2, $qNotClosed, $meRqsted = FALSE,
        $meToResolve = FALSE) {
    global $usrID;
    global $prsnid;
    $whereCls = "";
    if ($dte1 != "") {
        $dte1 = cnvrtDMYTmToYMDTm($dte1);
    }
    if ($dte2 != "") {
        $dte2 = cnvrtDMYTmToYMDTm($dte2);
    }
    if ($searchIn == "Requestor") {
        $whereCls = "(a.rqstr_prsn_id_num ilike '" . loc_db_escape_string($searchWord) .
                "' or a.rqstr_name ilike '" . loc_db_escape_string($searchWord) .
                "' or a.rqstr_email ilike '" . loc_db_escape_string($searchWord) .
                "' or a.rqstr_contact_nos ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    } else if ($searchIn == "Ticket Number") {
        $whereCls = "('' || a.rqst_ticket_id ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    } else {
        $whereCls = "(a.rqst_subject ilike '" . loc_db_escape_string($searchWord) .
                "' or a.rqst_first_msg_body ilike '" . loc_db_escape_string($searchWord) .
                "' or a.rqst_closure_rmrks ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    }
    if ($qNotClosed) {
        $whereCls .= "(a.is_closed = '0') and ";
    }
    if ($meRqsted) {
        $whereCls .= "(a.rqstr_user_id = " . $usrID . " or a.lnkd_person_id = " . $prsnid . ") and ";
    }
    if ($meToResolve) {
        $whereCls .= "(org.does_prsn_hv_crtria_id(" . $prsnid . ", a.asgnd_group_value::bigint,a.asgnd_group_type)>0) and ";
    }
    $strSql = "SELECT count(a.rqst_ticket_id) 
        FROM hlpd.hlpd_all_rqst_tickets a " .
            "WHERE ((1=1) and " . $whereCls . "(a.org_id = " . $orgID . ")" .
            " and (to_timestamp(a.creation_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS'))) ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

?>
