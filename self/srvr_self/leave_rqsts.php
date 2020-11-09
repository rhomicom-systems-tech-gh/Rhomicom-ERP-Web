
<div class="content-header" style="padding: 12px 0.5rem !important;border-bottom: 1px solid #ddd;">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">My Leave of Absences</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="javascript:openATab('#allmodules', 'grp=42&typ=1');">All Apps</a></li>
                    <li class="breadcrumb-item"><a href="javascript:openATab('#allmodules', 'grp=50&typ=1&vtyp=999');">Personal Stuff</a></li>
                    <li class="breadcrumb-item active">Absences</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <?php echo "Leave of Absences"; ?>
    </div>
</section>
<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//ABSENSE/LEAVE MANAGEMENT
function get_PlnExctns($searchWord, $searchIn, $offset, $limit_size, $orgID, $dte1, $dte2) {
    global $prsnid;
    global $dfltPrvldgs;
    global $mdlNm;
    $canVwOthrsLeave = test_prmssns($dfltPrvldgs[26], $mdlNm);
    $strSql = "";
    $whereCls = "";
    if ($canVwOthrsLeave === false) {
        $whereCls .= "(a.person_id=" . $prsnid . ") and ";
    }
    if ($dte1 != "") {
        $dte1 = cnvrtDMYTmToYMDTm($dte1);
    }
    if ($dte2 != "") {
        $dte2 = cnvrtDMYTmToYMDTm($dte2);
    }
    if ($searchIn == "Person Name/Number") {
        $whereCls .= "((prs.get_prsn_name(a.person_id) || ' (' || prs.get_prsn_loc_id(a.person_id) || ')') ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    } else if ($searchIn == "Status") {
        $whereCls .= "(a.rqst_status ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    } else if ($searchIn == "Period Date") {
        $whereCls .= "(to_char(to_timestamp(a.execution_strt_dte,'YYYY-MM-DD'),'DD-Mon-YYYY') ilike '" . loc_db_escape_string($searchWord) .
                "' or to_char(to_timestamp(a.execution_end_dte,'YYYY-MM-DD'),'DD-Mon-YYYY') ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    } else if ($searchIn == "Request Comment") {
        $whereCls .= "(a.cmmnt_remark ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    }
    $strSql = "SELECT a.plan_execution_id, a.person_id,
        (prs.get_prsn_name(a.person_id) || ' (' || prs.get_prsn_loc_id(a.person_id) || ')') prsnnm, 
        a.accrual_plan_id, prs.get_accrual_plan_name(a.accrual_plan_id) plnnm,
        to_char(to_timestamp(a.execution_strt_dte,'YYYY-MM-DD'),'DD-Mon-YYYY') execution_strt_dte, 
        to_char(to_timestamp(a.execution_end_dte,'YYYY-MM-DD'),'DD-Mon-YYYY') execution_end_dte, 
        a.days_entitled, a.cmmnt_remark, a.rqst_status,
        prs.get_accrual_pln_bals_info(a.plan_execution_id,'Taken') tkn,
        prs.get_accrual_pln_bals_info(a.plan_execution_id,'Scheduled') schdld,
        prs.get_accrual_pln_bals_info(a.plan_execution_id,'Requested') rqstd,
        prs.get_accrual_pln_bals_info(a.plan_execution_id,'UnRequested') unrqstd
  FROM prs.hr_accrual_plan_exctns  a  " .
            "WHERE ((a.org_id = " . $orgID . ") and " . $whereCls . " (to_timestamp(a.creation_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS'))) " .
            "ORDER BY a.plan_execution_id DESC LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PlnExctnsTtl($searchWord, $searchIn, $orgID, $dte1, $dte2) {
    global $prsnid;
    global $dfltPrvldgs;
    global $mdlNm;
    $canVwOthrsLeave = test_prmssns($dfltPrvldgs[26], $mdlNm);
    $strSql = "";
    $whereCls = "";
    if ($canVwOthrsLeave === false) {
        $whereCls .= "(a.person_id=" . $prsnid . ") and ";
    }
    if ($dte1 != "") {
        $dte1 = cnvrtDMYTmToYMDTm($dte1);
    }
    if ($dte2 != "") {
        $dte2 = cnvrtDMYTmToYMDTm($dte2);
    }
    if ($searchIn == "Person Name/Number") {
        $whereCls .= "((prs.get_prsn_name(a.person_id) || ' (' || prs.get_prsn_loc_id(a.person_id) || ')') ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    } else if ($searchIn == "Status") {
        $whereCls .= "(a.rqst_status ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    } else if ($searchIn == "Period Date") {
        $whereCls .= "(to_char(to_timestamp(a.execution_strt_dte,'YYYY-MM-DD'),'DD-Mon-YYYY') ilike '" . loc_db_escape_string($searchWord) .
                "' or to_char(to_timestamp(a.execution_end_dte,'YYYY-MM-DD'),'DD-Mon-YYYY') ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    } else if ($searchIn == "Request Comment") {
        $whereCls .= "(a.cmmnt_remark ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    }
    $strSql = "SELECT count(plan_execution_id) 
   FROM prs.hr_accrual_plan_exctns a  " .
            "WHERE ((a.org_id = " . $orgID . ") and " . $whereCls . " (to_timestamp(a.creation_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS'))) ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_OnePlnExctnDet($plnExctnID) {
    $strSql = "SELECT a.plan_execution_id, a.person_id,
        (prs.get_prsn_name(a.person_id) || ' (' || prs.get_prsn_loc_id(a.person_id) || ')') prsnnm, 
        a.accrual_plan_id, prs.get_accrual_plan_name(a.accrual_plan_id) plnnm,
        to_char(to_timestamp(a.execution_strt_dte,'YYYY-MM-DD'),'DD-Mon-YYYY') execution_strt_dte, 
        to_char(to_timestamp(a.execution_end_dte,'YYYY-MM-DD'),'DD-Mon-YYYY') execution_end_dte, 
        a.days_entitled, a.cmmnt_remark, a.rqst_status,
        prs.get_accrual_pln_bals_info(a.plan_execution_id,'Taken') tkn,
        prs.get_accrual_pln_bals_info(a.plan_execution_id,'Scheduled') schdld,
        prs.get_accrual_pln_bals_info(a.plan_execution_id,'Requested') rqstd,
        prs.get_accrual_pln_bals_info(a.plan_execution_id,'UnRequested') unrqstd
  FROM prs.hr_accrual_plan_exctns a
         WHERE (a.plan_execution_id=" . $plnExctnID . ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_OnePlnExctnAbsLns($plnExctnID) {
    $strSql = "SELECT a.absence_id, a.plan_execution_id, 
        a.person_id, (prs.get_prsn_name(a.person_id) || ' (' || prs.get_prsn_loc_id(a.person_id) || ')') prsnnm, 
        to_char(to_timestamp(a.absence_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') absence_start_date, 
        to_char(to_timestamp(a.absence_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY') absence_end_date, 
       a.no_of_days, a.absence_reason, a.absence_status
  FROM prs.hr_person_absences a
         WHERE (a.plan_execution_id=" . $plnExctnID . ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AbsenseLns($searchWord, $searchIn, $offset, $limit_size, $orgID, $dte1, $dte2) {
    global $prsnid;
    global $dfltPrvldgs;
    global $mdlNm;
    $canVwOthrsLeave = test_prmssns($dfltPrvldgs[26], $mdlNm);
    $strSql = "";
    $whereCls = "";
    if ($canVwOthrsLeave === false) {
        $whereCls .= "(a.person_id=" . $prsnid . ") and ";
    }
    if ($dte1 != "") {
        $dte1 = cnvrtDMYTmToYMDTm($dte1);
    }
    if ($dte2 != "") {
        $dte2 = cnvrtDMYTmToYMDTm($dte2);
    }
    if ($searchIn == "Person Name/Number") {
        $whereCls .= "((prs.get_prsn_name(a.person_id) || ' (' || prs.get_prsn_loc_id(a.person_id) || ')') ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    } else if ($searchIn == "Status") {
        $whereCls .= "(a.rqst_status ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    } else if ($searchIn == "Period Date") {
        $whereCls .= "(to_char(to_timestamp(a.absence_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') ilike '" . loc_db_escape_string($searchWord) .
                "' or to_char(to_timestamp(a.absence_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY') ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    } else if ($searchIn == "Request Comment") {
        $whereCls .= "(a.cmmnt_remark ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    }
    $strSql = "SELECT a.absence_id, a.plan_execution_id, 
        a.person_id, (prs.get_prsn_name(a.person_id) || ' (' || prs.get_prsn_loc_id(a.person_id) || ')') prsnnm, 
        to_char(to_timestamp(a.absence_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') absence_start_date, 
        to_char(to_timestamp(a.absence_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY') absence_end_date, 
       a.no_of_days, a.absence_reason, a.absence_status, prs.get_accrual_plan_name(b.accrual_plan_id) plnnm
        FROM prs.hr_person_absences a, prs.hr_accrual_plan_exctns b " .
            "WHERE ((a.plan_execution_id = b.plan_execution_id) and " . $whereCls . "(b.org_id = " . $orgID .
            ") and ((to_timestamp(a.absence_start_date || ' 00:00:00','YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS')) OR (to_timestamp(a.absence_end_date || ' 23:59:59','YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS')))) " .
            "ORDER BY a.absence_start_date DESC LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AbsenseLnsTtl($searchWord, $searchIn, $orgID, $dte1, $dte2) {
    global $prsnid;
    global $dfltPrvldgs;
    global $mdlNm;
    $canVwOthrsLeave = test_prmssns($dfltPrvldgs[26], $mdlNm);
    $strSql = "";
    $whereCls = "";
    if ($canVwOthrsLeave === false) {
        $whereCls .= "(a.person_id=" . $prsnid . ") and ";
    }
    if ($dte1 != "") {
        $dte1 = cnvrtDMYTmToYMDTm($dte1);
    }
    if ($dte2 != "") {
        $dte2 = cnvrtDMYTmToYMDTm($dte2);
    }
    if ($searchIn == "Person Name/Number") {
        $whereCls .= "((prs.get_prsn_name(a.person_id) || ' (' || prs.get_prsn_loc_id(a.person_id) || ')') ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    } else if ($searchIn == "Status") {
        $whereCls .= "(a.rqst_status ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    } else if ($searchIn == "Period Date") {
        $whereCls .= "(to_char(to_timestamp(a.absence_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') ilike '" . loc_db_escape_string($searchWord) .
                "' or to_char(to_timestamp(a.absence_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY') ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    } else if ($searchIn == "Request Comment") {
        $whereCls .= "(a.cmmnt_remark ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    }
    $strSql = "SELECT count(a.absence_id) 
        FROM prs.hr_person_absences a, prs.hr_accrual_plan_exctns b " .
            "WHERE ((a.plan_execution_id = b.plan_execution_id) and " . $whereCls . "(b.org_id = " . $orgID .
            ") and ((to_timestamp(a.absence_start_date || ' 00:00:00','YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS')) OR (to_timestamp(a.absence_end_date || ' 23:59:59','YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS')))) ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_AccrualPlns($searchWord, $searchIn, $offset, $limit_size, $orgID) {
    $strSql = "";
    $whereCls = "";
    if ($searchIn == "Plan Name") {
        $whereCls = "(a.accrual_plan_name ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    } else if ($searchIn == "Plan Description") {
        $whereCls = "(a.accrual_plan_desc ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    }
    $strSql = "SELECT accrual_plan_id, accrual_plan_name, accrual_plan_desc, plan_execution_intrvls, 
        to_char(to_timestamp(a.accrual_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') accrual_start_date, 
        to_char(to_timestamp(a.accrual_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY') accrual_end_date, 
       lnkd_balance_item_id, org.get_payitm_nm(lnkd_balance_item_id) bals_itm_nm, 
       lnkd_balnc_add_item_id, org.get_payitm_nm(lnkd_balnc_add_item_id) add_itm_nm,  
       lnkd_balnc_sbtrct_item_id, org.get_payitm_nm(lnkd_balnc_sbtrct_item_id) sbtrct_itm_nm, 
       org_id, created_by, creation_date, last_update_by, last_update_date, can_excd_entltlmnt
  FROM prs.hr_accrual_plans a " .
            "WHERE ((a.org_id = " . $orgID . ") and " . $whereCls . " 1=1) ORDER BY a.accrual_plan_id DESC LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AccrualPlnsTtl($searchWord, $searchIn, $orgID) {
    $strSql = "";
    $whereCls = "";
    if ($searchIn == "Plan Name") {
        $whereCls = "(a.accrual_plan_name ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    } else if ($searchIn == "Plan Description") {
        $whereCls = "(a.accrual_plan_desc ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    }
    $strSql = "SELECT count(1) 
  FROM prs.hr_accrual_plans a " .
            "WHERE ((a.org_id = " . $orgID . ") and " . $whereCls . " 1=1)";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_OneAccrualPlnDet($plnID) {
    $strSql = "SELECT accrual_plan_id, accrual_plan_name, accrual_plan_desc, plan_execution_intrvls, 
        to_char(to_timestamp(a.accrual_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') accrual_start_date, 
        to_char(to_timestamp(a.accrual_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY') accrual_end_date, 
       lnkd_balance_item_id, org.get_payitm_nm(lnkd_balance_item_id) bals_itm_nm, 
       lnkd_balnc_add_item_id, org.get_payitm_nm(lnkd_balnc_add_item_id) add_itm_nm,  
       lnkd_balnc_sbtrct_item_id, org.get_payitm_nm(lnkd_balnc_sbtrct_item_id) sbtrct_itm_nm, 
       org_id, created_by, creation_date, last_update_by, last_update_date, can_excd_entltlmnt
  FROM prs.hr_accrual_plans a 
         WHERE (a.accrual_plan_id=" . $plnID . ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getNewPlnExctnID() {
    $sqlStr = "select nextval('prs.hr_accrual_plan_exctns_plan_execution_id_seq'::regclass);";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getPlnExctnLineID($startDate, $plnExctnID) {
    if ($startDate != "") {
        $startDate = cnvrtDMYToYMD($startDate);
    }
    $sqlStr = "select absence_id from prs.hr_person_absences where absence_start_date = '" .
            loc_db_escape_string($startDate) . "' and plan_execution_id = " .
            loc_db_escape_string($plnExctnID) . " ";
    // and absence_status = 'Requested'
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getLeaveEndDate($startDate, $noDays) {
    if ($startDate != "") {
        $startDate = cnvrtDMYToYMD($startDate);
    }
    $sqlStr = "select prs.xx_get_next_date_aftr('" .
            loc_db_escape_string($startDate) . "', " .
            loc_db_escape_string($noDays) . ") ";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getLeaveDaysEntld($endDate, $itmID, $prsnID, $orgid) {
    //$endDate MUST BE YYYY-MM-DD
    $sqlStr = "Select pay.get_payitm_expctd_amnt(" .
            loc_db_escape_string($itmID) . ", " .
            loc_db_escape_string($prsnID) . ", " .
            loc_db_escape_string($orgid) . ", '" .
            loc_db_escape_string($endDate) . "') ";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function isPlanExctnVld($sbmtdExctnID) {
    $sqlStr = "Select prs.get_accrual_pln_bals_info(" . loc_db_escape_string($sbmtdExctnID) . ", 'UnRequested') ";
    $result = executeSQLNoParams($sqlStr);
    $unRqstd = 0;
    while ($row = loc_db_fetch_array($result)) {
        $unRqstd = (float) $row[0];
    }
    if ($unRqstd != 0) {
        return FALSE;
    } else {
        return TRUE;
    }
}

function createPlnExctns($plnExctnID, $prsnID, $plnID, $startDate
        , $endDate, $noDays, $rmrkCmmnt, $orgid, $rqstStatus) {
    global $usrID;
    if ($startDate != "") {
        $startDate = cnvrtDMYToYMD($startDate);
    }
    if ($endDate != "") {
        $endDate = cnvrtDMYToYMD($endDate);
    }
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO prs.hr_accrual_plan_exctns(
            plan_execution_id, person_id, accrual_plan_id, execution_strt_dte, 
            execution_end_dte, days_entitled, cmmnt_remark, rqst_status,
            org_id, created_by, creation_date, last_update_by, last_update_date) " .
            "VALUES (" . $plnExctnID .
            ", " . $prsnID .
            "," . $plnID .
            ",'" . loc_db_escape_string($startDate) .
            "', '" . loc_db_escape_string($endDate) .
            "'," . $noDays .
            ", '" . loc_db_escape_string($rmrkCmmnt) .
            "', '" . loc_db_escape_string($rqstStatus) .
            "', " . $orgid .
            ", " . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr .
            "')";
    return execUpdtInsSQL($insSQL);
}

function updatePlnExctns($plnExctnID, $prsnID, $plnID, $startDate
        , $endDate, $noDays, $rmrkCmmnt, $orgid, $rqstStatus) {
    global $usrID;
    if ($startDate != "") {
        $startDate = cnvrtDMYToYMD($startDate);
    }
    if ($endDate != "") {
        $endDate = cnvrtDMYToYMD($endDate);
    }
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE prs.hr_accrual_plan_exctns
            SET person_id=" . $prsnID .
            ", accrual_plan_id=" . $plnID .
            ", execution_strt_dte='" . loc_db_escape_string($startDate) .
            "', execution_end_dte='" . loc_db_escape_string($endDate) .
            "', days_entitled=" . $noDays .
            ", cmmnt_remark='" . loc_db_escape_string($rmrkCmmnt) .
            "', rqst_status='" . loc_db_escape_string($rqstStatus) .
            "', org_id=" . $orgid .
            ", last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr . "'
       WHERE plan_execution_id = " . $plnExctnID;
    return execUpdtInsSQL($updtSQL);
}

function updatePlnExctnsInfo($plnExctnID, $noDays, $rmrkCmmnt, $rqstStatus) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE prs.hr_accrual_plan_exctns
            SET days_entitled=" . $noDays .
            ", cmmnt_remark='" . loc_db_escape_string($rmrkCmmnt) .
            "', rqst_status='" . loc_db_escape_string($rqstStatus) .
            "', last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr . "'
       WHERE plan_execution_id = " . $plnExctnID;
    return execUpdtInsSQL($updtSQL);
}

function deletePlnExctns($pkeyID, $extrInfo = "") {
    $selSQL = "Select count(1) from prs.hr_person_absences WHERE plan_execution_id = " . $pkeyID . " and absence_status IN ('Taken','Scheduled')";
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = (float) $row[0];
    }
    if ($trnsCnt > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Plans with some Days Finalized!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    if ($trnsCnt <= 0) {
        $insSQL = "DELETE FROM prs.hr_person_absences WHERE plan_execution_id = " . $pkeyID;
        $affctd = execUpdtInsSQL($insSQL, "Ext. Info:" . $extrInfo);
        $insSQL = "DELETE FROM prs.hr_accrual_plan_exctns WHERE plan_execution_id = " . $pkeyID;
        $affctd1 = execUpdtInsSQL($insSQL, "Ext. Info:" . $extrInfo);

        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Leave Plan Execution(s)!";
        $dsply .= "<br/>$affctd Absence(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function createAbsenseLns($plnExctnID, $prsnID, $startDate, $noDays
        , $endDate, $absncRsn, $absncStatus) {
    global $usrID;
    if ($startDate != "") {
        $startDate = cnvrtDMYToYMD($startDate);
    }
    if ($endDate != "") {
        $endDate = cnvrtDMYToYMD($endDate);
    }
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO prs.hr_person_absences(
            plan_execution_id, person_id, absence_start_date, 
            no_of_days, absence_end_date, absence_reason, absence_status, 
            created_by, creation_date, last_update_by, last_update_date) " .
            "VALUES (" . $plnExctnID .
            ", " . $prsnID .
            ", '" . loc_db_escape_string($startDate) .
            "', " . $noDays .
            ", '" . loc_db_escape_string($endDate) .
            "', '" . loc_db_escape_string($absncRsn) .
            "', '" . loc_db_escape_string($absncStatus) .
            "', " . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr .
            "')";
    return execUpdtInsSQL($insSQL);
}

function updateAbsenseLns($absncID, $plnExctnID, $prsnID, $startDate, $noDays
        , $endDate, $absncRsn, $absncStatus) {
    global $usrID;
    if ($startDate != "") {
        $startDate = cnvrtDMYToYMD($startDate);
    }
    if ($endDate != "") {
        $endDate = cnvrtDMYToYMD($endDate);
    }
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE prs.hr_person_absences
      SET plan_execution_id=" . $plnExctnID .
            ", person_id=" . $prsnID .
            ", absence_start_date='" . loc_db_escape_string($startDate) .
            "', no_of_days=" . $noDays .
            ", absence_end_date='" . loc_db_escape_string($endDate) .
            "', absence_reason='" . loc_db_escape_string($absncRsn) .
            "', absence_status='" . loc_db_escape_string($absncStatus) .
            "', last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr . "'
       WHERE absence_id = " . $absncID;
    return execUpdtInsSQL($updtSQL);
}

function deleteAbsenseLns($pkeyID, $extrInfo = "") {
    $selSQL = "Select count(1) from prs.hr_person_absences WHERE absence_id = " . $pkeyID . " and absence_status IN ('Taken','Scheduled')";
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = (float) $row[0];
    }
    if ($trnsCnt > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Absences with Days Finalized!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    if ($trnsCnt <= 0) {
        $insSQL = "DELETE FROM prs.hr_person_absences WHERE absence_id = " . $pkeyID;
        $affctd1 = execUpdtInsSQL($insSQL, "Ext. Info:" . $extrInfo);
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Absence(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function createAccrualPlns($plnName, $plnDesc, $plnExctnIntrvls, $startDate
        , $endDate, $lnkdBalsItmID, $lnkdAddItmID, $orgid, $lnkdSbtrctItmID, $canExcdLmt) {
    global $usrID;
    $dateStr = getDB_Date_time();
    if ($startDate != "") {
        $startDate = cnvrtDMYToYMD($startDate);
    }
    if ($endDate != "") {
        $endDate = cnvrtDMYToYMD($endDate);
    }
    $insSQL = "INSERT INTO prs.hr_accrual_plans(
            accrual_plan_name, accrual_plan_desc, plan_execution_intrvls, 
            accrual_start_date, accrual_end_date, lnkd_balance_item_id, lnkd_balnc_add_item_id, 
            lnkd_balnc_sbtrct_item_id, can_excd_entltlmnt, org_id, created_by, creation_date, 
            last_update_by, last_update_date) " .
            "VALUES ('" . loc_db_escape_string($plnName) .
            "', '" . loc_db_escape_string($plnDesc) .
            "', '" . loc_db_escape_string($plnExctnIntrvls) .
            "', '" . loc_db_escape_string($startDate) .
            "', '" . loc_db_escape_string($endDate) .
            "', " . $lnkdBalsItmID .
            ", " . $lnkdAddItmID .
            ", " . $lnkdSbtrctItmID .
            ", '" . loc_db_escape_string($canExcdLmt) .
            "', " . $orgid .
            ", " . $usrID . ", '" . $dateStr . "', " . $usrID . ",'" . $dateStr .
            "')";
    //echo $insSQL;
    return execUpdtInsSQL($insSQL);
}

function updateAccrualPlns($plnID, $plnName, $plnDesc, $plnExctnIntrvls, $startDate
        , $endDate, $lnkdBalsItmID, $lnkdAddItmID, $orgid, $lnkdSbtrctItmID, $canExcdLmt) {
    global $usrID;
    $dateStr = getDB_Date_time();
    if ($startDate != "") {
        $startDate = cnvrtDMYToYMD($startDate);
    }
    if ($endDate != "") {
        $endDate = cnvrtDMYToYMD($endDate);
    }
    $updtSQL = "UPDATE prs.hr_accrual_plans
   SET accrual_plan_name='" . loc_db_escape_string($plnName) .
            "', accrual_plan_desc='" . loc_db_escape_string($plnDesc) .
            "', plan_execution_intrvls='" . loc_db_escape_string($plnExctnIntrvls) .
            "', accrual_start_date='" . loc_db_escape_string($startDate) .
            "', accrual_end_date='" . loc_db_escape_string($endDate) .
            "', lnkd_balance_item_id=" . $lnkdBalsItmID .
            ", lnkd_balnc_add_item_id=" . $lnkdAddItmID .
            ", lnkd_balnc_sbtrct_item_id=" . $lnkdSbtrctItmID .
            ", can_excd_entltlmnt='" . loc_db_escape_string($canExcdLmt) .
            "', org_id=" . $orgid .
            ", last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr . "'
       WHERE accrual_plan_id = " . $plnID;
    return execUpdtInsSQL($updtSQL);
}

function deleteAccrualPlns($pkeyID, $extrInfo = "") {
    $selSQL = "Select count(1) from prs.hr_person_absences WHERE absence_id = " . $pkeyID . " and absence_status IN ('Taken','Scheduled')";
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = (float) $row[0];
    }
    if ($trnsCnt > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Absences with Days Finalized!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    if ($trnsCnt <= 0) {
        $insSQL = "DELETE FROM prs.prsn_extra_data_cols WHERE extra_data_cols_id = " . $pkeyID;
        $affctd1 = execUpdtInsSQL($insSQL, "Additional Data Column:" . $extrInfo);
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Additional Data Column(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function leaveReqMsgActns($routingID = -1, $inptSlctdRtngs = "", $actionToPrfrm = "Initiate", $srcDocID = -1, $srcDocType = "Leave Requests") {
    global $app_url;
    global $admin_name;
    $userID = $_SESSION['USRID'];
    $user_Name = $_SESSION['UNAME'];
    $rtngMsgID = -1;
    $affctd = 0;
    $affctd1 = 0;
    $affctd2 = 0;
    $affctd3 = 0;
    $affctd4 = 0;
    $curPrsnsLevel = -123456789;
    $msgTitle = "";
    $msgBdy = "";
    $nwPrsnLocID = isset($_POST['toPrsLocID']) ? cleanInputData($_POST['toPrsLocID']) : "";
    $apprvrCmmnts = isset($_POST['actReason']) ? cleanInputData($_POST['actReason']) : "";
    $fromPrsnID = getUserPrsnID($user_Name);
    $usrFullNm = getPrsnFullNm($fromPrsnID);
    $msg = "";
    $dsply = "";
    $msg_id = -1;
    $appID = -1;
    $attchmnts = "";
    $reqestDte = getFrmtdDB_Date_time();

    $srcdoctyp = $srcDocType;
    $srcdocid = $srcDocID;

    $reportTitle = "Send Outstanding Bulk Messages";
    $reportName = "Send Outstanding Bulk Messages";
    $rptID = getRptID($reportName);
    $prmID = getParamIDUseSQLRep("{:msg_batch_id}", $rptID);
    $msgBatchID = -1;
    //session_write_close();
    if ($routingID <= 0 && $inptSlctdRtngs == "") {
        if ($actionToPrfrm == "Initiate" && $srcDocID > 0) {
            $msg_id = getWkfMsgID();
            $appID = getAppID('Leave Requests', 'Basic Person Data');
//Requestor
            $prsnid = $fromPrsnID;
            $fullNm = $usrFullNm;
            $prsnLocID = getPersonLocID($prsnid);

//Message Header & Details
            $msghdr = "$fullNm ($prsnLocID) Requests for Leave of Absence";
            $msgbody = "LEAVE RECORDS CHANGE REQUEST ON ($reqestDte):- "
                    . "A request for Leave of Absence has been submitted by $fullNm ($prsnLocID) "
                    . "<br/>Please open the attached Work Document and attend to this Request.
                      <br/>Thank you.";
            $msgtyp = "Work Document";
            $msgsts = "0";
            $hrchyid = (float) getGnrlRecID2("wkf.wkf_hierarchy_hdr", "hierarchy_name", "hierarchy_id", $srcDocType . " Hierarchy"); //Get hierarchy ID

            $attchmnts = ""; //Get Attachments
            $attchmnts_desc = ""; //Get Attachments
            $rslt = getLeaveRqstAttchMnts($srcdocid);
            while ($rw = loc_db_fetch_array($rslt)) {
                $attchmnts = $rw[1];
                $attchmnts_desc = $rw[0];
            }
            createWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
//Get Hierarchy Members
            $result = getNextApprvrsInMnlHrchy($hrchyid, $curPrsnsLevel);
            $prsnsFnd = 0;
            $lastPrsnID = "|";
            $msgBatchID = getMsgBatchID();
            $paramRepsNVals = $prmID . "~" . $msgBatchID . "|-190~HTML";
            while ($row = loc_db_fetch_array($result)) {
                $toPrsnID = (float) $row[0];
                $prsnsFnd++;
                if ($toPrsnID > 0) {
                    //transform:translateY(-50%);
                    routWkfMsg($msg_id, $prsnid, $toPrsnID, $userID, 'Initiated', 'Open;Reject;Request for Information;Approve');
                    $dsply = '<div style="text-align:center;font-weight:bold;font-size:18px;color:blue;position:relative;top:50%;">Your request has been submitted successfully for Approval.</br>
                        A notification will be sent to you on approval of your request. Thank you!</div>';
                    $msg = $dsply;
                    //Begin Email Sending Process                    
                    $result1 = getEmlDetailsB4Actn($srcdoctyp, $srcdocid);
                    while ($row1 = loc_db_fetch_array($result1)) {
                        $frmID = $toPrsnID;
                        if (strpos($lastPrsnID, "|" . $frmID . "|") !== FALSE) {
                            $lastPrsnID .= $frmID . "|";
                            continue;
                        }
                        $lastPrsnID .= $frmID . "|";
                        $subject = $row1[1];
                        $actSoFar = $row1[3];
                        if ($actSoFar == "") {
                            $actSoFar = "&nbsp;&nbsp;NONE";
                        }
                        $msgPart = "<span style=\"font-weight:bold;text-decoration:underline;color:blue;\">ACTIONS TAKEN SO FAR:</span><br/>" . $actSoFar . "<br/> <span style=\"font-weight:bold;text-decoration:underline;color:blue;\">ORIGINAL MESSAGE:</span><br/>&nbsp;&nbsp;" . $row1[2];
                        $docType = $srcDocType;
                        $to = getPrsnEmail($frmID);
                        $nameto = getPrsnFullNm($frmID);
                        if ($docType != "" && $docType != "Login") {
                            $message = "Dear $nameto, <br/><br/>A notification has been sent to your account in the Portal as follows:"
                                    . "<br/><br/>"
                                    . $msgPart .
                                    "<br/><br/>Kindly <a href=\""
                                    . $app_url . "\">Login via this Link</a> to <strong>VIEW and ACT</strong> on it!<br/>Thank you for your cooperation!<br/><br/>Best Regards,<br/>" . $admin_name;
                            $errMsg = "";
                            createMessageQueue($msgBatchID, trim(str_replace(";", ",", $to), ";, "), "", "", $message, $subject, "", "Email");
                        }
                    }
                }
            }
            if ($prsnsFnd <= 0) {
                $dsply .= "<br/>|ERROR|-No Approval Hierarchy Found";
                $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
            } else {
                //Update Request Status to In Process
                updatePrsLeaveRqst($srcdocid, "Initiated");
            }
        } else {
            $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Generated";
            $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
        }
    } else {
        if ($routingID > 0) {
            $oldMsgbodyAddOn = "";
            $reslt1 = getWkfMsgRtngData($routingID);
            while ($row = loc_db_fetch_array($reslt1)) {
                $rtngMsgID = (float) $row[0];
                $msg_id = $rtngMsgID;
                $curPrsnsLevel = (float) $row[18];
                $isActionDone = $row[9];
                $oldMsgbodyAddOn = $row[17];
//$rtngMsgID = (float) getGnrlRecNm("wkf.wkf_actual_msgs_routng", "routing_id", "msg_id", $routingID);
//$curPrsnsLevel = (float) getGnrlRecNm("wkf.wkf_actual_msgs_routng", "routing_id", "to_prsns_hrchy_level", $routingID);
//$isActionDone = getGnrlRecNm("wkf.wkf_actual_msgs_routng", "routing_id", "is_action_done", $routingID);
            }
            $row = NULL;

            $reslt2 = getWkfMsgHdrData($rtngMsgID);
            while ($row = loc_db_fetch_array($reslt2)) {
                $msgTitle = $row[1]; //getGnrlRecNm("wkf.wkf_actual_msgs_hdr", "msg_id", "msg_hdr", $rtngMsgID);
                $msgBdy = $row[2]; //getGnrlRecNm("wkf.wkf_actual_msgs_hdr", "msg_id", "msg_body", $rtngMsgID);
                $srcDocID = (float) $row[10]; //getGnrlRecNm("wkf.wkf_actual_msgs_hdr", "msg_id", "src_doc_id", $rtngMsgID);
                $srcDocType = $row[9]; //getGnrlRecNm("wkf.wkf_actual_msgs_hdr", "msg_id", "src_doc_type", $rtngMsgID);
                $orgnlPrsnUsrID = (float) $row[3]; //getGnrlRecNm("wkf.wkf_actual_msgs_hdr", "msg_id", "created_by", $rtngMsgID);
                $hrchyid = (float) $row[5];
                $appID = (float) $row[7];
                $attchmnts = $row[13];
                $attchmnts_desc = $row[14]; //Get Attachments
            }
            $srcdoctyp = $srcDocType;
            $srcdocid = $srcDocID;
            $orgnlPrsnID = getUserPrsnID1($orgnlPrsnUsrID);
            if ($isActionDone == '0') {
                if ($actionToPrfrm == "Open") {
                    echo LeaveRqstRODsply($srcDocID);
                } else if ($actionToPrfrm == "Reject") {
                    $affctd += updtWkfMsgRtngUsngLvl($rtngMsgID, $curPrsnsLevel, $fromPrsnID, "Rejected", "None", $userID);
//$affctd1+= updateWkfMsgBdy($rtngMsgID, $msgbodyAddOn, $userID);
                    $datestr = getFrmtdDB_Date_time();
                    $msgbodyAddOn = "";
                    $msgbodyAddOn .= "REJECTION ON $datestr:- This document has been Rejected by $usrFullNm with the ff Message:<br/>";
                    $msgbodyAddOn .= $apprvrCmmnts . "<br/><br/>";
                    $affctd1 += updtWkfMsgRtngCmnts($routingID, $msgbodyAddOn, $userID);
                    $msgbodyAddOn .= $oldMsgbodyAddOn;

                    updateWkfMsgStatus($rtngMsgID, "1", $userID);
                    updtWkfMsgAllUnclsdRtng($rtngMsgID, $fromPrsnID, "Closed", "None", $userID);

//Message Header & Details
                    $msghdr = "REJECTED - " . $msgTitle;
                    $msgbody = $msgBdy; //$msgbodyAddOn. "ORIGINAL MESSAGE :<br/><br/>" .
                    $msgtyp = "Informational";
                    $msgsts = "0";
//$msg_id = getWkfMsgID();
                    $affctd2 += updateWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
                    $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $orgnlPrsnID, $userID, "Initiated", "Acknowledge;Open", 1, $msgbodyAddOn);
                    $affctd4 += updatePrsLeaveRqst($srcdocid, "Rejected");

                    //Begin Email Sending Process                    
                    $result = getEmlDetailsAftrActn($srcdoctyp, $srcdocid);
                    $lastPrsnID = "|";
                    $msgBatchID = getMsgBatchID();
                    $paramRepsNVals = $prmID . "~" . $msgBatchID . "|-190~HTML";
                    while ($row = loc_db_fetch_array($result)) {
                        $frmID = $row[0];
                        if (strpos($lastPrsnID, "|" . $frmID . "|") !== FALSE || $frmID == $fromPrsnID) {
                            $lastPrsnID .= $frmID . "|";
                            continue;
                        }
                        $lastPrsnID .= $frmID . "|";
                        $subject = $row[1];
                        $actSoFar = $row[3];
                        if ($actSoFar == "") {
                            $actSoFar = "&nbsp;&nbsp;NONE";
                        }
                        $msgPart = "<span style=\"font-weight:bold;text-decoration:underline;color:blue;\">ACTIONS TAKEN SO FAR:</span><br/>" . $actSoFar . "<br/> <span style=\"font-weight:bold;text-decoration:underline;color:blue;\">ORIGINAL MESSAGE:</span><br/>&nbsp;&nbsp;" . $row[2];
                        $docType = $srcDocType;
                        $to = getPrsnEmail($frmID);
                        $nameto = getPrsnFullNm($frmID);
                        if ($docType != "" && $docType != "Login") {
                            $message = "Dear $nameto, <br/><br/>A notification has been sent to your account in the Portal as follows:"
                                    . "<br/><br/>"
                                    . $msgPart .
                                    "<br/><br/>Kindly <a href=\""
                                    . $app_url . "\">Login via this Link</a> to <strong>VIEW and ACT</strong> on it!<br/>Thank you for your cooperation!<br/><br/>Best Regards,<br/>" . $admin_name;
                            $errMsg = "";
                            createMessageQueue($msgBatchID, trim(str_replace(";", ",", $to), ";, "), "", "", $message, $subject, "", "Email");
                            //sendEMail(trim(str_replace(";", ",", $to), ","), $nameto, $subject, $message, $errMsg, "", "", "", $admin_name);
                        }
                    }
                    if ($affctd > 0) {
                        $dsply .= "<br/>Status of $affctd Workflow Document(s) successfully updated to Rejected!";
                        $dsply .= "<br/>$affctd1 Workflow Document(s) Message Body Successfully Updated!";
                        //$dsply .= "<br/>$affctd2 New Workflow Document(s) Message Body Successfully Created!";
                        $dsply .= "<br/>$affctd3 Workflow Document(s) Successfully Re-Routed to Original Sender!";
                        $dsply .= "<br/>$affctd4 Request Status Successfully Updated!";
                        $msg = "<p style = \"text-align:left; color:#32CD32;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>"; //#32CD32
                    } else {
                        $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Rejected";
                        $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
                    }
                } else if ($actionToPrfrm == "Withdraw") {
                    $affctd += updtWkfMsgRtngUsngLvl($rtngMsgID, $curPrsnsLevel, $fromPrsnID, "Rejected", "None", $userID);
//$affctd1+= updateWkfMsgBdy($rtngMsgID, $msgbodyAddOn, $userID);
                    $datestr = getFrmtdDB_Date_time();
                    $msgbodyAddOn = "";
                    $msgbodyAddOn .= "WITHDRAWAL ON $datestr:- This document has been withdrawn by $usrFullNm with the ff Message:<br/>";
                    $msgbodyAddOn .= $apprvrCmmnts . "<br/><br/>";
                    $affctd1 += updtWkfMsgRtngCmnts($routingID, $msgbodyAddOn, $userID);
                    $msgbodyAddOn .= $oldMsgbodyAddOn;

                    updateWkfMsgStatus($rtngMsgID, "1", $userID);
                    updtWkfMsgAllUnclsdRtng($rtngMsgID, $fromPrsnID, "Closed", "None", $userID);

//Message Header & Details
                    $msghdr = "WITHDRAWN - " . $msgTitle;
                    $msgbody = $msgBdy; //$msgbodyAddOn. "ORIGINAL MESSAGE :<br/><br/>" .
                    $msgtyp = "Informational";
                    $msgsts = "0";
//$msg_id = getWkfMsgID();
                    $affctd2 += updateWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
                    $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $orgnlPrsnID, $userID, "Initiated", "Acknowledge;Open", 1, $msgbodyAddOn);
                    $affctd4 += updatePrsLeaveRqst($srcdocid, "Withdrawn");

                    //Begin Email Sending Process                    
                    $result = getEmlDetailsAftrActn($srcdoctyp, $srcdocid);
                    $lastPrsnID = "|";
                    $msgBatchID = getMsgBatchID();
                    $paramRepsNVals = $prmID . "~" . $msgBatchID . "|-190~HTML";
                    while ($row = loc_db_fetch_array($result)) {
                        $frmID = $row[0];
                        if (strpos($lastPrsnID, "|" . $frmID . "|") !== FALSE || $frmID == $fromPrsnID) {
                            $lastPrsnID .= $frmID . "|";
                            continue;
                        }
                        $lastPrsnID .= $frmID . "|";
                        $subject = $row[1];
                        $actSoFar = $row[3];
                        if ($actSoFar == "") {
                            $actSoFar = "&nbsp;&nbsp;NONE";
                        }
                        $msgPart = "<span style=\"font-weight:bold;text-decoration:underline;color:blue;\">ACTIONS TAKEN SO FAR:</span><br/>" . $actSoFar . "<br/> <span style=\"font-weight:bold;text-decoration:underline;color:blue;\">ORIGINAL MESSAGE:</span><br/>&nbsp;&nbsp;" . $row[2];
                        $docType = $srcDocType;
                        $to = getPrsnEmail($frmID);
                        $nameto = getPrsnFullNm($frmID);
                        if ($docType != "" && $docType != "Login") {
                            $message = "Dear $nameto, <br/><br/>A notification has been sent to your account in the Portal as follows:"
                                    . "<br/><br/>"
                                    . $msgPart .
                                    "<br/><br/>Kindly <a href=\""
                                    . $app_url . "\">Login via this Link</a> to <strong>VIEW and ACT</strong> on it!<br/>Thank you for your cooperation!<br/><br/>Best Regards,<br/>" . $admin_name;
                            $errMsg = "";
                            createMessageQueue($msgBatchID, trim(str_replace(";", ",", $to), ";, "), "", "", $message, $subject, "", "Email");
                            //sendEMail(trim(str_replace(";", ",", $to), ","), $nameto, $subject, $message, $errMsg, "", "", "", $admin_name);
                        }
                    }
                    if ($affctd > 0) {
                        $dsply .= "<br/>Status of $affctd Workflow Document(s) successfully updated to Withdrawn!";
                        $dsply .= "<br/>$affctd1 Workflow Document(s) Message Body Successfully Updated!";
                        //$dsply .= "<br/>$affctd2 New Workflow Document(s) Message Body Successfully Created!";
                        $dsply .= "<br/>$affctd3 Workflow Document(s) Successfully Re-Routed to Original Sender!";
                        $dsply .= "<br/>$affctd4 Request Status Successfully Updated!";
                        $msg = "<p style = \"text-align:left; color:#32CD32;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>"; //#32CD32
                    } else {
                        $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Rejected";
                        $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
                    }
                } else if ($actionToPrfrm == "Request for Information") {
                    $nwPrsnID = getPersonID($nwPrsnLocID);
//$nwPrsnFullNm = getPrsnFullNm($nwPrsnID);
                    $affctd += updtWkfMsgRtngUsngLvl($rtngMsgID, $curPrsnsLevel, $fromPrsnID, "Information Requested", "None", $userID);
//$affctd1+= updateWkfMsgBdy($rtngMsgID, $msgbodyAddOn, $userID);
                    $datestr = getFrmtdDB_Date_time();
                    $msgbodyAddOn = "";
                    $msgbodyAddOn .= "INFORMATION REQUESTED ON $datestr:- A requested for Information has been generated by $usrFullNm with the ff Message:<br/>";
                    $msgbodyAddOn .= $apprvrCmmnts . "<br/><br/>";
                    $affctd1 += updtWkfMsgRtngCmnts($routingID, $msgbodyAddOn, $userID);
                    $msgbodyAddOn .= $oldMsgbodyAddOn;

                    updateWkfMsgStatus($rtngMsgID, "1", $userID);
                    updtWkfMsgAllUnclsdRtng($rtngMsgID, $fromPrsnID, "Closed", "None", $userID);

//Message Header & Details
                    $msghdr = "INFORMATION REQUEST - " . $msgTitle;
                    $msgbody = $msgBdy; //"ORIGINAL MESSAGE :<br/><br/>" . 
                    $msgtyp = "Work Document";
                    $msgsts = "0";
//$msg_id = getWkfMsgID();
                    $affctd2 += updateWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
                    $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $nwPrsnID, $userID, "Initiated", "Respond;Open", $curPrsnsLevel, $msgbodyAddOn);
//$affctd4+=updatePrsLeaveRqst($srcdocid, "Rejected");
                    if ($affctd > 0) {
                        $dsply .= "<br/>Status of $affctd Workflow Document(s) successfully updated to Information Requested!";
                        $dsply .= "<br/>$affctd1 Workflow Document(s) Message Body Successfully Updated!";
//$dsply .= "<br/>$affctd2 New Workflow Document(s) Message Body Successfully Created!";
                        $dsply .= "<br/>$affctd3 Workflow Document(s) Successfully Re-Routed to New Person!";
// $dsply .= "<br/>$affctd4 Appointment Status Successfully Updated!";
                        $msg = "<p style = \"text-align:left; color:#32CD32;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>"; //#32CD32
                    } else {
                        $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Worked On";
                        $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
                    }
                } else if ($actionToPrfrm == "Respond") {
                    $nwPrsnID = getPersonID($nwPrsnLocID);
//$nwPrsnFullNm = getPrsnFullNm($nwPrsnID);
                    $affctd += updtWkfMsgRtngUsngLvl($rtngMsgID, $curPrsnsLevel, $fromPrsnID, "Response Given", "None", $userID);
//$affctd1+= updateWkfMsgBdy($rtngMsgID, $msgbodyAddOn, $userID);
                    $datestr = getFrmtdDB_Date_time();
                    $msgbodyAddOn = "";
                    $msgbodyAddOn .= "RESPONSE TO INFORMATION REQUESTED ON $datestr:- A response to an Information Request has been given by $usrFullNm with the ff Message:<br/>";
                    $msgbodyAddOn .= $apprvrCmmnts . "<br/><br/>";
                    $affctd1 += updtWkfMsgRtngCmnts($routingID, $msgbodyAddOn, $userID);
                    $msgbodyAddOn .= $oldMsgbodyAddOn;

                    updateWkfMsgStatus($rtngMsgID, "1", $userID);
                    updtWkfMsgAllUnclsdRtng($rtngMsgID, $fromPrsnID, "Closed", "None", $userID);

//Message Header & Details
                    $msghdr = "RESPONSE TO INFORMATION REQUEST - " . $msgTitle;
                    $msgbody = $msgBdy; //"ORIGINAL MESSAGE :<br/><br/>" . 
                    $msgtyp = "Work Document";
                    $msgsts = "0";
//$msg_id = getWkfMsgID();
                    $affctd2 += updateWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
                    $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $nwPrsnID, $userID, "Initiated", 'Open;Reject;Request for Information;Approve', $curPrsnsLevel, $msgbodyAddOn);
//$affctd4+=updatePrsLeaveRqst($srcdocid, "Rejected");
                    if ($affctd > 0) {
                        $dsply .= "<br/>Status of $affctd Workflow Document(s) successfully updated to Response Given!";
                        $dsply .= "<br/>$affctd1 Workflow Document(s) Message Body Successfully Updated!";
//$dsply .= "<br/>$affctd2 New Workflow Document(s) Message Body Successfully Created!";
                        $dsply .= "<br/>$affctd3 Workflow Document(s) Successfully Re-Routed to New Person!";
// $dsply .= "<br/>$affctd4 Appointment Status Successfully Updated!";
                        $msg = "<p style = \"text-align:left; color:#32CD32;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>"; //#32CD32
                    } else {
                        $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Worked On";
                        $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
                    }
                } else if ($actionToPrfrm == "Acknowledge") {
                    $nwPrsnID = getPersonID($nwPrsnLocID);
//$nwPrsnFullNm = getPrsnFullNm($nwPrsnID);
                    $affctd += updtWkfMsgRtngUsngLvl($rtngMsgID, $curPrsnsLevel, $fromPrsnID, "Acknowledged", "None", $userID);
//$affctd1+= updateWkfMsgBdy($rtngMsgID, $msgbodyAddOn, $userID);
                    $datestr = getFrmtdDB_Date_time();
                    $msgbodyAddOn = "";
                    $msgbodyAddOn .= "MESSAGE ACKNOWLEDGED ON $datestr:- An acknowledgement of the message has been given by $usrFullNm <br/><br/>";
//$msgbodyAddOn.=$apprvrCmmnts . "<br/><br/>";
                    $affctd1 += updtWkfMsgRtngCmnts($routingID, $msgbodyAddOn, $userID);

                    updateWkfMsgStatus($rtngMsgID, "1", $userID);
                    updtWkfMsgAllUnclsdRtng($rtngMsgID, $fromPrsnID, "Closed", "None", $userID);
                    if ($affctd > 0) {
                        $dsply .= "<br/>Status of $affctd Workflow Document(s) successfully updated to Acknowledged!";
                        $dsply .= "<br/>$affctd1 Workflow Document(s) Message Body Successfully Updated!";
//$dsply .= "<br/>$affctd2 New Workflow Document(s) Message Body Successfully Created!";
//$dsply .= "<br/>$affctd3 Workflow Document(s) Successfully Re-Routed to New Person!";
// $dsply .= "<br/>$affctd4 Appointment Status Successfully Updated!";
                        $msg = "<p style = \"text-align:left; color:#32CD32;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>"; //#32CD32
                    } else {
                        $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Worked On";
                        $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
                    }
                } else if ($actionToPrfrm == "Approve") {
                    $nxtPrsnsRslt = getNextApprvrsInMnlHrchy($hrchyid, $curPrsnsLevel);
                    $prsnsFnd = 0;
                    $lastPrsnID = "|";
                    $msgbodyAddOn = "";
                    while ($row = loc_db_fetch_array($nxtPrsnsRslt)) {
                        $nxtPrsnID = (float) $row[0];
                        $newStatus = "Reviewed";
                        $nxtStatus = "Open;Reject;Request for Information;Approve";
                        $nxtApprvr = "Next Approver";
                        if ($prsnsFnd == 0) {
                            $affctd += updtWkfMsgRtngUsngLvl($rtngMsgID, $curPrsnsLevel, $fromPrsnID, $newStatus, $nxtStatus, $userID);
                            $datestr = getFrmtdDB_Date_time();
                            $msgbodyAddOn .= strtoupper($newStatus) . " ON $datestr:- This document has been $newStatus by $usrFullNm <br/><br/>";
                            $affctd1 += updtWkfMsgRtngCmnts($routingID, $msgbodyAddOn, $userID);
                            $msgbodyAddOn .= $oldMsgbodyAddOn;
                            updtWkfMsgAllUnclsdRtng($rtngMsgID, $fromPrsnID, "Closed", "None", $userID);
                            $msghdr = $msgTitle;
                            $msgbody = $msgBdy;
                            $msgtyp = "Work Document";
                            $msgsts = "0";
                            $curPrsnsLevel += 1;
                            $affctd2 += updateWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
                        }
                        $prsnsFnd++;
                        if ($nxtPrsnID > 0) {
                            $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $nxtPrsnID, $userID, $newStatus, $nxtStatus, $curPrsnsLevel, $msgbodyAddOn);
                        }
                        if ($prsnsFnd == 1) {
                            $affctd4 += updatePrsLeaveRqst($srcdocid, $newStatus);
                        }
                    }
                    if ($prsnsFnd <= 0) {
                        $newStatus = "Approved";
                        $nxtStatus = "None;Acknowledge";
                        $nxtApprvr = "Original Person";
                        $affctd += updtWkfMsgRtngUsngLvl($rtngMsgID, $curPrsnsLevel, $fromPrsnID, $newStatus, $nxtStatus, $userID);
                        $datestr = getFrmtdDB_Date_time();
                        $msgbodyAddOn = "";
                        $msgbodyAddOn .= strtoupper($newStatus) . " ON $datestr:- This document has been $newStatus by $usrFullNm <br/><br/>";
                        $affctd1 += updtWkfMsgRtngCmnts($routingID, $msgbodyAddOn, $userID);
                        $msgbodyAddOn .= $oldMsgbodyAddOn;
                        updtWkfMsgAllUnclsdRtng($rtngMsgID, $fromPrsnID, "Closed", "None", $userID);
                        updateWkfMsgStatus($rtngMsgID, "1", $userID);
                        $msghdr = "APPROVED - " . $msgTitle;
                        $msgbody = $msgBdy;
                        $msgtyp = "Informational";
                        $msgsts = "0";
                        $curPrsnsLevel += 1;
                        $affctd2 += updateWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
                        $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $orgnlPrsnID, $userID, $newStatus, $nxtStatus, $curPrsnsLevel, $msgbodyAddOn);
                        $affctd4 += updatePrsLeaveRqst($srcdocid, $newStatus);
                        //Begin Email Sending Process                    
                        $result = getEmlDetailsAftrActn($srcdoctyp, $srcdocid);
                        $lastPrsnID = "|";
                        $msgBatchID = getMsgBatchID();
                        $paramRepsNVals = $prmID . "~" . $msgBatchID . "|-190~HTML";
                        while ($row = loc_db_fetch_array($result)) {
                            $frmID = $orgnlPrsnID;
                            if (strpos($lastPrsnID, "|" . $frmID . "|") !== FALSE) {
                                $lastPrsnID .= $frmID . "|";
                                continue;
                            }
                            $lastPrsnID .= $frmID . "|";
                            $subject = $row[1];
                            $actSoFar = $row[3];
                            if ($actSoFar == "") {
                                $actSoFar = "&nbsp;&nbsp;NONE";
                            }
                            $msgPart = "<span style=\"font-weight:bold;text-decoration:underline;color:blue;\">ACTIONS TAKEN SO FAR:</span><br/>" . $actSoFar . "<br/> <span style=\"font-weight:bold;text-decoration:underline;color:blue;\">ORIGINAL MESSAGE:</span><br/>&nbsp;&nbsp;" . $row[2];
                            $docType = $srcDocType;
                            $to = getPrsnEmail($frmID);
                            $nameto = getPrsnFullNm($frmID);
                            if ($docType != "" && $docType != "Login") {
                                $message = "Dear $nameto, <br/><br/>A notification has been sent to your account in the Portal as follows:"
                                        . "<br/><br/>"
                                        . $msgPart .
                                        "<br/><br/>Kindly <a href=\""
                                        . $app_url . "\">Login via this Link</a> to <strong>VIEW</strong> it!<br/>Thank you for your cooperation!<br/><br/>Best Regards,<br/>" . $admin_name;
                                $errMsg = "";
                                createMessageQueue($msgBatchID, trim(str_replace(";", ",", $to), ";, "), "", "", $message, $subject, "", "Email");
                                //sendEMail(trim(str_replace(";", ",", $to), ","), $nameto, $subject, $message, $errMsg, "", "", "", $admin_name);
                            }
                            break;
                        }
                    }
                    if ($affctd > 0) {
                        $dsply .= "<br/>Status of $affctd Workflow Document(s) successfully updated to $newStatus!";
                        $dsply .= "<br/>$affctd1 Workflow Document(s) Message Body Successfully Updated!";
//$dsply .= "<br/>$affctd2 New Workflow Document(s) Message Body Successfully Created!";
                        $dsply .= "<br/>$affctd3 Workflow Document(s) Successfully Re-Routed to $nxtApprvr!";
                        $dsply .= "<br/>$affctd4 Request Status Successfully Updated!";
                        $msg = "<p style = \"text-align:left; color:#32CD32;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>"; //#32CD32
                    } else {
                        $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Worked On";
                        $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
                    }
                }
            }
        } else {
            $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Selected";
            $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
        }
    }
    if ($msgBatchID > 0) {
        generateReportRun($rptID, $paramRepsNVals, -1);
    }
    return $msg;
}

function getLeaveRqstAttchMnts($plnExctnid) {
    global $ftp_base_db_fldr;
    $sqlStr = "SELECT string_agg(REPLACE(a.attchmnt_desc,';',','),';') attchmnt_desc, 
string_agg(REPLACE('" . $ftp_base_db_fldr . "/PrsnDocs/Leave/' || a.file_name,';',','),';') file_name 
  FROM prs.leave_doc_attchmnts a 
  WHERE plan_execution_id=" . $plnExctnid;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function updatePrsLeaveRqst($srcDocID, $nwvalue) {
    global $usrID;
    $affctd = 0;
    $datestr = getDB_Date_time();
    $nwvalue1 = "";
    if ($nwvalue == "Withdrawn" || $nwvalue == "Rejected") {
        $nwvalue1 = "Requested";
    } else if ($nwvalue == "Approved") {
        $nwvalue1 = "Scheduled";
    } else {
        $nwvalue1 = "";
    }
    if ($nwvalue1 != "") {
        $updSQL1 = "UPDATE prs.hr_person_absences
            SET absence_status='" . loc_db_escape_string($nwvalue1) . "',
                last_update_by = " . $usrID .
                ", last_update_date = '" . loc_db_escape_string($datestr) .
                "' WHERE plan_execution_id =" . $srcDocID . " and absence_status NOT IN ('Taken')";
        $affctd = execUpdtInsSQL($updSQL1);
    }
    if ($affctd > 0 || $nwvalue1 == "") {
        $updSQL = "UPDATE prs.hr_accrual_plan_exctns
            SET rqst_status='" . loc_db_escape_string($nwvalue) . "',
                last_update_by = " . $usrID .
                ", last_update_date = '" . loc_db_escape_string($datestr) .
                "' WHERE plan_execution_id=" . $srcDocID;
        $affctd = execUpdtInsSQL($updSQL);
    }
    return $affctd;
}

function LeaveRqstRODsply($sbmtdExctnID) {
    //New Leave Form  
    $sbmtdPlanID = -1;
    $plnNm = "";
    $rmrksCmnts = "";
    $lnkdPrsnID = -1;
    $lnkdPrsnNm = "";
    $exctnStrtDte = "";
    $exctnEndDte = "";
    $daysEntitled = 0;
    $rqstStatus = "";
    $rqstStatusColor = "red";
    $mkReadOnly = "";
    $mkRmrkReadOnly = "";
    if ($sbmtdExctnID > 0) {
        $result = get_OnePlnExctnDet($sbmtdExctnID);
        while ($row = loc_db_fetch_array($result)) {
            $sbmtdExctnID = (float) $row[0];
            $lnkdPrsnID = (float) $row[1];
            $lnkdPrsnNm = $row[2];
            $exctnStrtDte = $row[5];
            $exctnEndDte = $row[6];
            $daysEntitled = (float) $row[7];
            $rqstStatus = $row[9];
            $sbmtdPlanID = (float) $row[3];
            $plnNm = $row[4];
            $rmrksCmnts = $row[8];

            if ($rqstStatus == "Not Submitted" || $rqstStatus == "Withdrawn" || $rqstStatus == "Rejected") {
                $rqstStatusColor = "red";
            } else if ($rqstStatus != "Authorized") {
                $mkReadOnly = "readonly=\"true\"";
                $mkRmrkReadOnly = "readonly=\"true\"";
                $rqstStatusColor = "brown";
            } else {
                $rqstStatusColor = "green";
                $mkReadOnly = "readonly=\"true\"";
                $mkRmrkReadOnly = "readonly=\"true\"";
            }
        }
    } else {
        return "ERROR-Nothing to Display!!";
    }
    /* if ($sbmtdExctnID <= 0) {
      $sbmtdExctnID = getNewPlnExctnID();
      } */
    $canEdtLve = FALSE;
    $canAddLve = FALSE;
    $canDelLve = FALSE;

    $routingID = getMxRoutingID($sbmtdExctnID, "Leave Requests");
    $reportTitle = "Leave Profile Report";
    $reportName = "Leave Profile Report";
    $rptID = getRptID($reportName);
    $prmID1 = getParamIDUseSQLRep("{:pln_exctn_id}", $rptID);
    $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
    $trnsID = $sbmtdExctnID;
    $paramRepsNVals = $prmID1 . "~" . $trnsID . "|" . $prmID2 . "~" . $reportTitle . "|-130~" . $reportTitle . "|-190~PDF";
    $paramStr = urlencode($paramRepsNVals);
    ?>
    <form class="form-horizontal" id='leavePlnExctnForm' action='' method='post' accept-charset='UTF-8'>
        <div class="row" style="margin: 0px 0px 10px 0px !important;">
            <div class="col-md-6" style="padding:0px 15px 0px 0px !important;">
                <div class="" style="padding:0px 0px 0px 0px;float:left !important;">                               
                    <button type="button" class="btn btn-default btn-sm" style="" id="myVmsTrnsStatusBtn"><span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstStatusColor; ?>;font-weight: bold;"><?php echo $rqstStatus; ?></span></button>
                    <?php //if ($rqstStatus == "Authorized") {               ?>
                    <button type="button" class="btn btn-default" style=""  onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="width:100% !important;">
                        <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                        Print Leave Profile
                    </button>
                    <?php //}              ?>
                </div>
            </div> 
            <div class="col-md-6" style="padding:0px 0px 0px 0px !important;">
                <div class="" style="padding:0px 0px 0px 0px;float:right !important;"> 
                    <?php
                    if (!($rqstStatus == "Not Submitted" || $rqstStatus == "Withdrawn" || $rqstStatus == "Rejected") && ($rqstStatus != "Authorized")) {
                        ?>                                    
                        <button type="button" class="btn btn-default btn-sm" style="" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Leave Approval Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Progress&nbsp;</button>                                
                        <?php
                    } else if ($rqstStatus == "Authorized") {
                        ?>                                    
                        <button type="button" class="btn btn-default btn-sm" style="" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Leave Approval Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;" data-toggle="tooltip" title="Approval Progress History">Progress&nbsp;</button>
                    <?php }
                    ?>
                </div>
            </div>                    
        </div>
        <div class="row" style="padding: 0px 15px 0px 15px !important;">
            <div class="col-md-6" style="padding: 0px 5px 0px 5px !important;">
                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                            <label for="sbmtdExctnID" class="control-label">Plan Execution No.:</label>
                        </div>
                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                            <?php if ($canEdtLve === true) { ?> 
                                <input type="number" name="sbmtdExctnID" id="sbmtdExctnID" class="form-control" value="<?php echo $sbmtdExctnID; ?>" style="width:100% !important;" readonly="true">
                            <?php } else { ?>
                                <span><?php echo $sbmtdExctnID; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                            <label for="plnNm" class="control-label">Leave Plan Name:</label>
                        </div>
                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                            <?php if ($canEdtLve === true) { ?> 
                                <input type="text" name="plnNm" id="plnNm" class="form-control" value="<?php echo $plnNm; ?>" style="width:100% !important;" readonly="true">
                                <input type="hidden" name="sbmtdPlanID" id="sbmtdPlanID" class="form-control" value="<?php echo $sbmtdPlanID; ?>">
                            <?php } else { ?>
                                <span><?php echo $plnNm; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                            <label for="lnkdPrsnNm" class="control-label">Person:</label>
                        </div>
                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                            <input type="hidden" name="lnkdPrsnID" id="lnkdPrsnID" class="form-control" value="<?php echo $lnkdPrsnID; ?>">
                            <span><?php echo $lnkdPrsnNm; ?></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                            <label for="daysEntitled" class="control-label">Days Entitled:</label>
                        </div>
                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                            <?php if ($canEdtLve === true) { ?> 
                                <input type="number" name="daysEntitled" id="daysEntitled" class="form-control" value="<?php echo $daysEntitled; ?>" style="width:100% !important;" readonly="true">
                            <?php } else { ?>
                                <span><?php echo $daysEntitled; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                            <label for="rqstStatus" class="control-label">Status:</label>
                        </div>
                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                            <?php if ($canEdtLve === true) { ?> 
                                <input type="text" name="rqstStatus" id="rqstStatus" class="form-control" value="<?php echo $rqstStatus; ?>" style="width:100% !important;" readonly="true">
                            <?php } else { ?>
                                <span><?php echo $rqstStatus; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>  
            <div class="col-md-6" style="padding: 1px !important;">
                <div class="form-group">                                    
                    <div class="col-sm-12">
                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                            <label for="exctnStrtDte">Start Date:</label>
                        </div>
                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                            <?php if ($canEdtLve === true) { ?> 
                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                    <input class="form-control" size="16" type="text" id="exctnStrtDte" name="exctnStrtDte" value="<?php echo $exctnStrtDte; ?>" readonly="true">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            <?php } else { ?>
                                <span><?php echo $exctnStrtDte; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">                                    
                    <div class="col-sm-12">
                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                            <label for="exctnEndDte">End Date:</label>
                        </div>
                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                            <?php if ($canEdtLve === true) { ?> 
                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                    <input class="form-control" size="16" type="text" id="exctnEndDte" name="exctnEndDte" value="<?php echo $exctnEndDte; ?>" readonly="true">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            <?php } else { ?>
                                <span><?php echo $exctnEndDte; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                            <label for="rmrksCmnts" class="control-label">Remarks/ Comments:</label>
                        </div>
                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">     
                            <?php if ($canEdtLve === true) { ?>
                                <textarea rows="5" name="rmrksCmnts" id="rmrksCmnts" class="form-control"><?php echo $rmrksCmnts; ?></textarea>
                            <?php } else { ?>
                                <span><?php echo $rmrksCmnts; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
        <div class="row" style="padding:1px 15px 1px 15px !important;"><hr style="margin:3px 0px 3px 0px;"></div>
        <div class="row" style="padding:1px 15px 1px 15px !important;">                                
            <div id="plnExctnAbsncs" class="" style="min-width:100% !important;"> 
                <!--<div class="col-md-12" style="display:none;">
                    <button id="refreshVltBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneLeaveRqstsForm(<?php echo $sbmtdExctnID; ?>, 'ReloadDialog');" data-toggle="tooltip" data-placement="bottom" title = "Reload Leave Plan Execution">
                        <img src="cmn_images/refresh.bmp" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                        Refresh
                    </button>
                </div>--> 
                <table class="table table-striped table-bordered" id="onePlnExctnAbsncsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                    <thead>
                        <tr>
                            <th style="">No.</th>
                            <th style="">Absence Start Date</th>
                            <th style="text-align:right;">No. Days</th>
                            <th style="">Absence End Date</th>
                            <th style="">Remark/Narration</th>
                            <th style="">Status</th>
                            <th style="">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rslt = get_OnePlnExctnAbsLns($sbmtdExctnID);
                        $cntrUsr = 0;
                        while ($rwLn = loc_db_fetch_array($rslt)) {
                            $cntrUsr++;
                            $noOfDays = $rwLn[6];
                            $absncStartDte = $rwLn[4];
                            $absncEndDte = $rwLn[5];
                            $absStatus = $rwLn[8];
                            $absReason = $rwLn[7];
                            $style1 = "text-align:right;font-weight:bold;color:red;";
                            if ($rwLn[8] == "Scheduled") {
                                $style1 = "text-align:right;font-weight:bold;color:blue;";
                            }
                            if ($rwLn[8] == "Taken") {
                                $style1 = "text-align:right;font-weight:bold;color:green;";
                            }
                            ?>
                            <tr id="onePlnExctnAbsncsRow_<?php echo $cntrUsr; ?>">                                    
                                <td class="lovtd"><span><?php echo ($cntrUsr); ?></span></td>
                                <td class="lovtd">                                                                            
                                    <?php if ($canEdtLve === true) { ?>
                                        <div class="form-group form-group-sm col-md-12">
                                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="width:100%;">
                                                <input class="form-control rqrdFld" size="16" type="text" id="onePlnExctnAbsncsRow<?php echo $cntrUsr; ?>_StrtDte" value="<?php echo $absncStartDte; ?>" style="width:100%;">
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>                                                                
                                        </div>
                                    <?php } else { ?>
                                        <span><?php echo $absncStartDte; ?></span>
                                    <?php } ?> 
                                </td>
                                <td class="lovtd" style="text-align:right;">
                                    <?php if ($canEdtLve === true) { ?>
                                        <div class="form-group form-group-sm col-md-12">
                                            <input type="number" class="form-control rqrdFld" aria-label="..." id="onePlnExctnAbsncsRow<?php echo $cntrUsr; ?>_NoOfDays" value="<?php echo $noOfDays; ?>" style="width:100%;">
                                            <input type="hidden" class="form-control" aria-label="..." id="onePlnExctnAbsncsRow<?php echo $cntrUsr; ?>_LineID" value="<?php echo $rwLn[0]; ?>">
                                        </div>
                                    <?php } else { ?>
                                        <span><?php echo $noOfDays; ?></span>
                                    <?php } ?> 
                                </td>
                                <td class="lovtd">
                                    <?php if ($canEdtLve === true) { ?>
                                        <div class="form-group form-group-sm col-md-12" style="width:100%;">
                                            <input class="form-control" size="16" type="text" id="onePlnExctnAbsncsRow<?php echo $cntrUsr; ?>_EndDte" value="<?php echo $absncEndDte; ?>" readonly="true" style="width:100%;">                                                                                                                                           
                                        </div>
                                    <?php } else { ?>
                                        <span><?php echo $absncEndDte; ?></span>
                                    <?php } ?>  
                                </td>
                                <td class="lovtd">
                                    <?php if ($canEdtLve === true) { ?>
                                        <div class="form-group form-group-sm col-md-12">
                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="onePlnExctnAbsncsRow<?php echo $cntrUsr; ?>_AbsRsn" value="<?php echo $absReason; ?>" style="width:100%;">                                                                       
                                        </div>
                                    <?php } else { ?>
                                        <span><?php echo $absReason; ?></span>
                                    <?php } ?> 
                                </td>
                                <td class="lovtd">                                                                
                                    <span style="<?php echo $style1; ?>"><?php echo $absStatus; ?></span>
                                </td>
                                <td class="lovtd">
                                    <?php if ($canEdtLve === true) { ?>
                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delLeaveRqstsLines('onePlnExctnAbsncsRow_<?php echo $cntrUsr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Absence">
                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
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
        <div class="" style="float:right;margin-top: 5px;">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </form>                    
    <?php
}
?>

