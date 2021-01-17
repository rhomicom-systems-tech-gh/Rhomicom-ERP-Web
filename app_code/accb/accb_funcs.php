<?php

function get_ChartsToExprt($orgID, $limit_size)
{
    $strSql = "SELECT a.accnt_num, a.accnt_name, 
                  a.accnt_desc, a.accnt_type, accb.get_accnt_name(a.prnt_accnt_id), 
                  a.is_prnt_accnt, a.is_retained_earnings, a.is_net_income, a.is_contra, 
                  a.report_line_no, a.has_sub_ledgers, accb.get_accnt_name(a.control_account_id), 
                  gst.get_pssbl_val(a.crncy_id), a.is_suspens_accnt, a.account_clsfctn, org.get_sgmnt_val(a.accnt_seg1_val_id), 
       org.get_sgmnt_val(a.accnt_seg2_val_id), org.get_sgmnt_val(a.accnt_seg3_val_id), org.get_sgmnt_val(a.accnt_seg4_val_id), org.get_sgmnt_val(a.accnt_seg5_val_id),
       org.get_sgmnt_val(a.accnt_seg6_val_id), org.get_sgmnt_val(a.accnt_seg7_val_id), org.get_sgmnt_val(a.accnt_seg8_val_id), org.get_sgmnt_val(a.accnt_seg9_val_id),
       org.get_sgmnt_val(a.accnt_seg10_val_id), accb.get_accnt_num(mapped_grp_accnt_id) 
                  FROM accb.accb_chart_of_accnts a WHERE a.org_id = " . $orgID .
        " ORDER BY a.accnt_typ_id, a.report_line_no, a.accnt_num LIMIT " . $limit_size . " OFFSET 0";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Basic_ChrtDet($searchWord, $searchIn, $offset, $limit_size, $orgID)
{
    $strSql = "";
    $whereCls = " and (accnt_num ilike '" . loc_db_escape_string($searchWord) .
        "' or accnt_name ilike '" . loc_db_escape_string($searchWord) .
        "')";
    $subSql = "SELECT accnt_id,accnt_num,accnt_name,space||accnt_num||'.'||accnt_name account_number_name, is_prnt_accnt, 
        CASE WHEN accnt_type='A' THEN 'A -ASSET' WHEN accnt_type='EQ' THEN 'EQ-EQUITY' WHEN accnt_type='L' THEN 'L -LIABILITY' WHEN accnt_type='R' THEN 'R -REVENUE' ELSE 'EX-EXPENSE' END accnt_type,
        accnt_typ_id, prnt_accnt_id, control_account_id, depth, path, cycle,
        CASE WHEN balance_date ='' THEN '' ELSE to_char(to_timestamp(balance_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') END bsldte, debit_balance, credit_balance, 
       is_enabled, net_balance, gst.get_pssbl_val(accb.get_accnt_crncy_id(accnt_id)) crncy
      FROM suborg WHERE 1=1 " . $whereCls . " ORDER BY accnt_typ_id, path";
    if ($searchIn != "Parent Account Details" || strlen($searchWord) <= 3) {
        $strSql = "WITH RECURSIVE suborg(accnt_id, accnt_num, accnt_name, is_prnt_accnt, accnt_type, accnt_typ_id, prnt_accnt_id, control_account_id, depth, path, cycle, space, balance_date, debit_balance, credit_balance, 
       is_enabled, net_balance) AS 
      (SELECT a.accnt_id, a.accnt_num, a.accnt_name, a.is_prnt_accnt, a.accnt_type,a.accnt_typ_id, a.prnt_accnt_id, a.control_account_id, 1, ARRAY[a.accnt_num||'']::character varying[], false, '' opad, a.balance_date, a.debit_balance, a.credit_balance, 
       a.is_enabled, a.net_balance 
      FROM accb.accb_chart_of_accnts a 
        WHERE ((CASE WHEN a.prnt_accnt_id<=0 THEN a.control_account_id ELSE a.prnt_accnt_id END)=-1 AND (a.org_id = " . $orgID . ")) 
      UNION ALL        
      SELECT a.accnt_id, a.accnt_num, a.accnt_name, a.is_prnt_accnt, a.accnt_type,a.accnt_typ_id, a.prnt_accnt_id, a.control_account_id, sd.depth + 1, 
      path || a.accnt_num, 
      a.accnt_num = ANY(path), space || '      ', a.balance_date, a.debit_balance, a.credit_balance, 
       a.is_enabled, a.net_balance
      FROM 
      accb.accb_chart_of_accnts a, suborg AS sd 
      WHERE (((CASE WHEN a.prnt_accnt_id<=0 THEN a.control_account_id ELSE a.prnt_accnt_id END)=sd.accnt_id AND NOT cycle) 
       AND (a.org_id = " . $orgID . "))) 
       " . $subSql . " LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    } else {
        $subSql = "SELECT accnt_id,accnt_num,accnt_name,space||accnt_num||'.'||accnt_name account_number_name, is_prnt_accnt, 
        CASE WHEN accnt_type='A' THEN 'A -ASSET' WHEN accnt_type='EQ' THEN 'EQ-EQUITY' WHEN accnt_type='L' THEN 'L -LIABILITY' WHEN accnt_type='R' THEN 'R -REVENUE' ELSE 'EX-EXPENSE' END accnt_type,
        accnt_typ_id, prnt_accnt_id, control_account_id, depth, path, cycle,
        CASE WHEN balance_date ='' THEN '' ELSE to_char(to_timestamp(balance_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') END bsldte, debit_balance, credit_balance, 
       is_enabled, net_balance, gst.get_pssbl_val(accb.get_accnt_crncy_id(accnt_id)) crncy 
      FROM suborg WHERE 1=1 ORDER BY accnt_typ_id, path";

        $strSql = "WITH RECURSIVE suborg(accnt_id, accnt_num, accnt_name, is_prnt_accnt, accnt_type, accnt_typ_id, prnt_accnt_id, control_account_id, depth, path, cycle, space, balance_date, debit_balance, credit_balance, 
       is_enabled, net_balance) AS 
      ( 
      SELECT a.accnt_id, a.accnt_num, a.accnt_name, a.is_prnt_accnt, a.accnt_type,a.accnt_typ_id, a.prnt_accnt_id, a.control_account_id, 1, ARRAY[a.accnt_num||'']::character varying[], false, '' opad, a.balance_date, a.debit_balance, a.credit_balance, 
       a.is_enabled, a.net_balance 
      FROM accb.accb_chart_of_accnts a 
        WHERE ((a.accnt_name ilike '" . loc_db_escape_string($searchWord) .
            "' or a.accnt_num ilike '" . loc_db_escape_string($searchWord) .
            "') AND (a.org_id = " . $orgID . ")) 
      UNION ALL        
      SELECT a.accnt_id, a.accnt_num, a.accnt_name, a.is_prnt_accnt, a.accnt_type,a.accnt_typ_id, a.prnt_accnt_id, a.control_account_id, sd.depth + 1, 
      path || a.accnt_num, 
      a.accnt_num = ANY(path), space || '      ', a.balance_date, a.debit_balance, a.credit_balance, 
       a.is_enabled, a.net_balance
      FROM 
      accb.accb_chart_of_accnts a, suborg AS sd 
      WHERE (((CASE WHEN a.prnt_accnt_id<=0 THEN a.control_account_id ELSE a.prnt_accnt_id END)=sd.accnt_id AND NOT cycle) 
       AND (a.org_id = " . $orgID . "))) 
       " . $subSql . " LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    }
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Basic_ChrtTtls($searchWord, $searchIn, $orgID)
{
    $strSql = "";
    $whereCls = " and (accnt_num ilike '" . loc_db_escape_string($searchWord) .
        "' or accnt_name ilike '" . loc_db_escape_string($searchWord) .
        "')";
    $subSql = "SELECT count(1) 
      FROM suborg WHERE 1=1 " . $whereCls . "";

    if ($searchIn != "Parent Account Details" || strlen($searchWord) <= 3) {
        $strSql = "WITH RECURSIVE suborg(accnt_id, accnt_num, accnt_name, is_prnt_accnt, accnt_type, accnt_typ_id, prnt_accnt_id, control_account_id, depth, path, cycle, space) AS 
      ( 
      SELECT a.accnt_id, a.accnt_num, a.accnt_name, a.is_prnt_accnt, a.accnt_type,a.accnt_typ_id, a.prnt_accnt_id, a.control_account_id, 1, ARRAY[a.accnt_num||'']::character varying[], false, '' opad 
      FROM accb.accb_chart_of_accnts a 
        WHERE ((CASE WHEN a.prnt_accnt_id<=0 THEN a.control_account_id ELSE a.prnt_accnt_id END)=-1 AND (a.org_id = " . $orgID . ")) 
      UNION ALL        
      SELECT a.accnt_id, a.accnt_num, a.accnt_name, a.is_prnt_accnt, a.accnt_type,a.accnt_typ_id, a.prnt_accnt_id, a.control_account_id, sd.depth + 1, 
      path || a.accnt_num, 
      a.accnt_num = ANY(path), space || '      '
      FROM 
      accb.accb_chart_of_accnts a, suborg AS sd 
      WHERE (((CASE WHEN a.prnt_accnt_id<=0 THEN a.control_account_id ELSE a.prnt_accnt_id END)=sd.accnt_id AND NOT cycle) 
       AND (a.org_id = " . $orgID . "))) 
       " . $subSql;
    } else {
        $subSql = "SELECT accnt_id,accnt_num,accnt_name,space||accnt_num||'.'||accnt_name account_number_name, is_prnt_accnt, accnt_type,accnt_typ_id, prnt_accnt_id, control_account_id, depth, path, cycle 
      FROM suborg WHERE 1=1 ORDER BY accnt_typ_id, path";

        $strSql = "WITH RECURSIVE suborg(accnt_id, accnt_num, accnt_name, is_prnt_accnt, accnt_type, accnt_typ_id, prnt_accnt_id, control_account_id, depth, path, cycle, space) AS 
      ( 
      SELECT a.accnt_id, a.accnt_num, a.accnt_name, a.is_prnt_accnt, a.accnt_type,a.accnt_typ_id, a.prnt_accnt_id, a.control_account_id, 1, ARRAY[a.accnt_num||'']::character varying[], false, '' opad 
      FROM accb.accb_chart_of_accnts a 
        WHERE ((a.accnt_name ilike '" . loc_db_escape_string($searchWord) .
            "' or a.accnt_num ilike '" . loc_db_escape_string($searchWord) .
            "') AND (a.org_id = " . $orgID . ")) 
      UNION ALL        
      SELECT a.accnt_id, a.accnt_num, a.accnt_name, a.is_prnt_accnt, a.accnt_type,a.accnt_typ_id, a.prnt_accnt_id, a.control_account_id, sd.depth + 1, 
      path || a.accnt_num, 
      a.accnt_num = ANY(path), space || '      '
      FROM 
      accb.accb_chart_of_accnts a, suborg AS sd 
      WHERE (((CASE WHEN a.prnt_accnt_id<=0 THEN a.control_account_id ELSE a.prnt_accnt_id END)=sd.accnt_id AND NOT cycle) 
       AND (a.org_id = " . $orgID . "))) 
       " . $subSql;
    }
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_Bals_Prnt_Accnts($prntAccntID)
{
    $strSql = "WITH RECURSIVE subaccnt(accnt_id, prnt_accnt_id, accnt_num, accnt_name, debit_balance, credit_balance, net_balance, depth, path, cycle, space) AS " .
        "( " .
        "   SELECT e.accnt_id, e.prnt_accnt_id, e.accnt_num, e.accnt_name, e.debit_balance, e.credit_balance, e.net_balance, 1, ARRAY[e.accnt_id], false, '' FROM accb.accb_chart_of_accnts e WHERE e.prnt_accnt_id = " . $prntAccntID .
        "   UNION ALL " .
        "  SELECT d.accnt_id, d.prnt_accnt_id, d.accnt_num, d.accnt_name, d.debit_balance, d.credit_balance, d.net_balance, sd.depth + 1, " .
        "        path || d.accnt_id, " .
        "        d.accnt_id = ANY(path), space || '.' " .
        " FROM " .
        "    accb.accb_chart_of_accnts AS d, " .
        "   subaccnt AS sd " .
        "  WHERE d.prnt_accnt_id = sd.accnt_id AND NOT cycle " .
        ") " .
        "SELECT SUM(debit_balance), SUM(credit_balance), SUM(net_balance) " .
        "FROM subaccnt " .
        "WHERE accnt_num ilike '%'";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function deleteAccbAccnt($accntid, $accntDesc)
{
    $strSql = "SELECT count(1) FROM accb.accb_trnsctn_details a WHERE(a.accnt_id = " . $accntid . ")";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete accounts with Transactions in their Name!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $strSql = "SELECT count(1) FROM accb.accb_chart_of_accnts a WHERE(a.prnt_accnt_id = " . $accntid . ")";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete Parent Accounts with Child Accounts!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $strSql = "SELECT count(1) FROM org.org_segment_values a WHERE(a.mapped_grp_accnt_id = " . $accntid . ")";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete Accounts with Subsidiary Account Mappings!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $strSql = "SELECT count(1) FROM pay.pay_gl_interface a WHERE(a.accnt_id = " . $accntid . ")";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete accounts with Personnel Payments in their Name!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $strSql = "SELECT count(1) FROM org.org_pay_items a WHERE(a.cost_accnt_id = " . $accntid . " or a.bals_accnt_id = " . $accntid . ")";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete accounts with Pay Items in their Name!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM accb.accb_account_clsfctns WHERE account_id = " . $accntid . "";
    $affctd2 = execUpdtInsSQL($delSQL, "Account Desc:" . $accntDesc);
    $delSQL = "DELETE FROM accb.accb_chart_of_accnts WHERE (accnt_id = " . $accntid . ")";
    $affctd1 = execUpdtInsSQL($delSQL, "Account Desc:" . $accntDesc);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Account(s)!";
        $dsply .= "<br/>$affctd2 Report Classification(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_One_Chrt_Det($chrtID)
{
    $sqlStr = "SELECT accnt_id, accnt_num, accnt_name, accnt_desc, is_contra, prnt_accnt_id, accb.get_accnt_name(prnt_accnt_id) prnt_accnt_nm,
       CASE WHEN balance_date ='' THEN '' ELSE to_char(to_timestamp(balance_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') END bsldte, 
        created_by, creation_date, last_update_by, last_update_date, 
       org_id, accnt_type, is_prnt_accnt, debit_balance, credit_balance, 
       is_enabled, net_balance, is_retained_earnings, is_net_income, 
       accnt_typ_id, report_line_no, has_sub_ledgers, 
       control_account_id, accb.get_accnt_name(control_account_id) cntrl_accnt_nm, 
       crncy_id, gst.get_pssbl_val(crncy_id) crncy_nm, is_suspens_accnt, account_clsfctn, 
       accnt_seg1_val_id, accnt_seg2_val_id, accnt_seg3_val_id, accnt_seg4_val_id, 
       accnt_seg5_val_id, accnt_seg6_val_id, accnt_seg7_val_id, accnt_seg8_val_id, 
       accnt_seg9_val_id, accnt_seg10_val_id, mapped_grp_accnt_id, 
       accb.get_accnt_num(mapped_grp_accnt_id)||'.'||accb.get_accnt_name(mapped_grp_accnt_id) mppd_accnt_nm " .
        "FROM accb.accb_chart_of_accnts a " .
        "WHERE (a.accnt_id = " . $chrtID . ") ORDER BY a.accnt_typ_id, a.report_line_no, a.accnt_num";
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function getAcctTypID($accntTyp)
{
    if ($accntTyp == "A") {
        return 1;
    } else if ($accntTyp == "L") {
        return 2;
    } else if ($accntTyp == "EQ") {
        return 3;
    } else if ($accntTyp == "R") {
        return 4;
    } else if ($accntTyp == "EX") {
        return 5;
    }
    return -1;
}

function getFullAcctType($accntTyp)
{
    if ($accntTyp == "A") {
        return "A -ASSET";
    } else if ($accntTyp == "L") {
        return "L -LIABILITY";
    } else if ($accntTyp == "EQ") {
        return "EQ-EQUITY";
    } else if ($accntTyp == "R") {
        return "R -REVENUE";
    } else if ($accntTyp == "EX") {
        return "EX-EXPENSE";
    }
    return "";
}

function get_SegmnetsTtl($orgid)
{
    $strSql = "SELECT no_of_accnt_sgmnts FROM org.org_details a  " .
        " WHERE((a.org_id = " . $orgid . "))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function get_One_SegmentDet($segNum, $orgid)
{
    $strSql = "SELECT a.segment_id, a.segment_name_prompt, a.system_clsfctn, org.get_sgmnt_id(a.prnt_sgmnt_number), a.prnt_sgmnt_number  
        FROM org.org_acnt_sgmnts a WHERE((a.org_id = " . $orgid . " and a.segment_number = " . $segNum . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getSegmentVal($segmentValID)
{
    $sqlStr = "select segment_value from org.org_segment_values where segment_value_id = " .
        $segmentValID . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getSegmentValID($segmentValue, $orgid)
{
    $sqlStr = "select segment_value_id from org.org_segment_values where lower(segment_value) = lower('" .
        loc_db_escape_string($segmentValue) . "') and org_id=" . $orgid;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getSegmentValDesc($segmentValID)
{
    $sqlStr = "select segment_description from org.org_segment_values where segment_value_id = " .
        $segmentValID . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getSegmentDpndntValID($segmentValID)
{
    $sqlStr = "select dpndnt_sgmnt_val_id from org.org_segment_values where segment_value_id = " .
        $segmentValID . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function getSegmentValChildSegNum($segmentValID)
{
    $sqlStr = "select MAX(b.segment_number) from org.org_segment_values a, org.org_acnt_sgmnts b "
        . "where a.segment_id=b.segment_id and a.dpndnt_sgmnt_val_id = " . $segmentValID . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function getSegmentChildSegNum($segmentID)
{
    $sqlStr = "select MAX(b.segment_number) from org.org_acnt_sgmnts b "
        . "where org.get_sgmnt_id(b.prnt_sgmnt_number) = " . $segmentID . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function get_One_AccntRptClsfctns($accntid)
{
    $strSql = "SELECT account_clsfctn_id, maj_rpt_ctgry, min_rpt_ctgry, 
       created_by, creation_date, last_update_by, last_update_date
  FROM accb.accb_account_clsfctns a WHERE(a.account_id = " . $accntid . ") ORDER BY 1";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AccntRptClsfctnID($majCtgrName, $minCtgrName, $accntID)
{
    $strSql = "SELECT account_clsfctn_id from accb.accb_account_clsfctns where account_id=" . $accntID .
        " and lower(maj_rpt_ctgry)=lower('" . loc_db_escape_string($majCtgrName) .
        "') and lower(min_rpt_ctgry)=lower('" . loc_db_escape_string($minCtgrName) . "')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getNewAccntRptClsfLnID()
{
    $strSql = "select nextval('accb.accb_account_clsfctns_account_clsfctn_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function createAccntRptClsfctn($clsfctnID, $majCtgrName, $minCtgrName, $accntID)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO accb.accb_account_clsfctns(
            account_clsfctn_id, account_id, maj_rpt_ctgry, min_rpt_ctgry, 
            created_by, creation_date, last_update_by, last_update_date) " .
        "VALUES (" . $clsfctnID . ", " . $accntID .
        ", '" . loc_db_escape_string($majCtgrName) .
        "', '" . loc_db_escape_string($minCtgrName) .
        "', " . $usrID . ", '" . $dateStr .
        "', " . $usrID . ", '" . $dateStr .
        "')";
    return execUpdtInsSQL($insSQL);
}

function updateAccntRptClsfctn($clsfctnID, $majCtgrName, $minCtgrName, $accntID)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE accb.accb_account_clsfctns SET " .
        "maj_rpt_ctgry='" . loc_db_escape_string($majCtgrName) .
        "', min_rpt_ctgry='" . loc_db_escape_string($minCtgrName) .
        "',account_id=" . $accntID .
        ", last_update_by = " . $usrID . ", " .
        "last_update_date = '" . $dateStr .
        "' WHERE (account_clsfctn_id =" . $clsfctnID . ")";
    return execUpdtInsSQL($updtSQL);
}

function deleteAccntRptClsfctn($lnID)
{
    $delSQL = "DELETE FROM accb.accb_account_clsfctns WHERE account_clsfctn_id = " .
        $lnID . "";
    $affctd1 = execUpdtInsSQL($delSQL);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Report Calssification(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getAccountCmbntnID(
    $orgID,
    $accntSegmnt1,
    $accntSegmnt2,
    $accntSegmnt3,
    $accntSegmnt4,
    $accntSegmnt5,
    $accntSegmnt6,
    $accntSegmnt7,
    $accntSegmnt8,
    $accntSegmnt9,
    $accntSegmnt10
) {
    $sqlStr = "select x.accnt_id from accb.accb_chart_of_accnts x 
                                where x.org_id = " . $orgID .
        " and x.accnt_seg1_val_id = " . $accntSegmnt1 .
        " and x.accnt_seg2_val_id= " . $accntSegmnt2 .
        " and x.accnt_seg3_val_id= " . $accntSegmnt3 .
        " and x.accnt_seg4_val_id= " . $accntSegmnt4 .
        " and x.accnt_seg5_val_id= " . $accntSegmnt5 .
        " and x.accnt_seg6_val_id= " . $accntSegmnt6 .
        " and x.accnt_seg7_val_id= " . $accntSegmnt7 .
        " and x.accnt_seg8_val_id= " . $accntSegmnt8 .
        " and x.accnt_seg9_val_id= " . $accntSegmnt9 .
        " and x.accnt_seg10_val_id= " . $accntSegmnt10 .
        " and (''||x.accnt_seg1_val_id
                    ||x.accnt_seg2_val_id
                    ||x.accnt_seg3_val_id
                    ||x.accnt_seg4_val_id
                    ||x.accnt_seg5_val_id
                    ||x.accnt_seg6_val_id
                    ||x.accnt_seg7_val_id
                    ||x.accnt_seg8_val_id
                    ||x.accnt_seg9_val_id
                    ||x.accnt_seg10_val_id) != ('-1-1-1-1-1-1-1-1-1-1')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function clearChrtRetEarns($org_id)
{
    $updtSQL = "UPDATE accb.accb_chart_of_accnts " .
        "SET is_retained_earnings='0' WHERE org_id = " . $org_id;
    return execUpdtInsSQL($updtSQL);
}

function clearChrtNetIncome($org_id)
{
    $updtSQL = "UPDATE accb.accb_chart_of_accnts " .
        "SET is_net_income='0' WHERE org_id = " . $org_id;
    return execUpdtInsSQL($updtSQL);
}

function clearChrtSuspns($org_id)
{
    $updtSQL = "UPDATE accb.accb_chart_of_accnts " .
        "SET is_suspens_accnt='0' WHERE org_id = " . $org_id;
    return execUpdtInsSQL($updtSQL);
}

function createChrt(
    $orgid,
    $accntnum,
    $accntname,
    $accntdesc,
    $isContra,
    $prntAccntID,
    $accntTyp,
    $isparent,
    $isenbld,
    $isretearngs,
    $isnetincome,
    $rpt_ln,
    $hasSbLdgrs,
    $cntrlAccntID,
    $currID,
    $isSuspns,
    $accClsftn,
    $accntSegmnt1,
    $accntSegmnt2,
    $accntSegmnt3,
    $accntSegmnt4,
    $accntSegmnt5,
    $accntSegmnt6,
    $accntSegmnt7,
    $accntSegmnt8,
    $accntSegmnt9,
    $accntSegmnt10,
    $mappedAcntID
) {
    global $usrID;
    $dateStr = getDB_Date_time();
    if ($isretearngs == true) {
        clearChrtRetEarns($orgid);
    }
    if ($isnetincome == true) {
        clearChrtNetIncome($orgid);
    }
    if ($isSuspns == true) {
        clearChrtSuspns($orgid);
    }
    $insSQL = "INSERT INTO accb.accb_chart_of_accnts(" .
        "accnt_num, accnt_name, accnt_desc, is_contra, " .
        "prnt_accnt_id, balance_date, created_by, creation_date, last_update_by, " .
        "last_update_date, org_id, accnt_type, is_prnt_accnt, debit_balance, " .
        "credit_balance, is_enabled, net_balance, is_retained_earnings, " .
        "is_net_income, accnt_typ_id, report_line_no, has_sub_ledgers, " .
        "control_account_id, crncy_id, is_suspens_accnt,account_clsfctn, accnt_seg1_val_id, 
       accnt_seg2_val_id, accnt_seg3_val_id, accnt_seg4_val_id, accnt_seg5_val_id, 
       accnt_seg6_val_id, accnt_seg7_val_id, accnt_seg8_val_id, accnt_seg9_val_id, 
       accnt_seg10_val_id, mapped_grp_accnt_id)" .
        "VALUES ('" . loc_db_escape_string($accntnum) . "', '" . loc_db_escape_string($accntname) .
        "', '" . loc_db_escape_string($accntdesc) . "', '" . cnvrtBoolToBitStr($isContra) .
        "', " . $prntAccntID . ", '" . $dateStr . "', " . $usrID . ", '" . $dateStr .
        "', " . $usrID . ", '" . $dateStr . "', " .
        $orgid . ", '" . loc_db_escape_string($accntTyp) .
        "', '" . cnvrtBoolToBitStr($isparent) . "', 0, 0, '" .
        cnvrtBoolToBitStr($isenbld) . "', 0, '" .
        cnvrtBoolToBitStr($isretearngs) . "', '" .
        cnvrtBoolToBitStr($isnetincome) . "', " .
        getAcctTypID($accntTyp) .
        ", " . $rpt_ln . ", '" . cnvrtBoolToBitStr($hasSbLdgrs) .
        "', " . $cntrlAccntID . ", " . $currID . ", '" . cnvrtBoolToBitStr($isSuspns) .
        "','" . loc_db_escape_string($accClsftn) . "', " . $accntSegmnt1 . ", " . $accntSegmnt2 . ", " . $accntSegmnt3 .
        ", " . $accntSegmnt4 . ", " . $accntSegmnt5 . ", " . $accntSegmnt6 . ", " . $accntSegmnt7 . ", " . $accntSegmnt8 .
        ", " . $accntSegmnt9 . ", " . $accntSegmnt10 . ", " . $mappedAcntID . ")";
    return execUpdtInsSQL($insSQL);
}

function updateChrtDet(
    $orgid,
    $accntid,
    $accntnum,
    $accntname,
    $accntdesc,
    $isContra,
    $prntAccntID,
    $accntTyp,
    $isparent,
    $isenbld,
    $isretearngs,
    $isnetincome,
    $rpt_ln,
    $hasSbLdgrs,
    $cntrlAccntID,
    $currID,
    $isSuspns,
    $accClsftn,
    $accntSegmnt1,
    $accntSegmnt2,
    $accntSegmnt3,
    $accntSegmnt4,
    $accntSegmnt5,
    $accntSegmnt6,
    $accntSegmnt7,
    $accntSegmnt8,
    $accntSegmnt9,
    $accntSegmnt10,
    $mappedAcntID
) {
    global $usrID;
    $dateStr = getDB_Date_time();
    if ($isretearngs == true) {
        clearChrtRetEarns($orgid);
    }
    if ($isnetincome == true) {
        clearChrtNetIncome($orgid);
    }
    if ($isSuspns == true) {
        clearChrtSuspns($orgid);
    }

    $updtSQL = "UPDATE accb.accb_chart_of_accnts " .
        "SET accnt_num='" . loc_db_escape_string($accntnum) . "', accnt_name='" . loc_db_escape_string($accntname) .
        "', accnt_desc='" . loc_db_escape_string($accntdesc) . "', is_contra='" . cnvrtBoolToBitStr($isContra) . "', " .
        "prnt_accnt_id=" . $prntAccntID . ", " .
        "last_update_by=" . $usrID . ", last_update_date='" . $dateStr .
        "', accnt_type='" . loc_db_escape_string($accntTyp) . "', " .
        "is_prnt_accnt='" . cnvrtBoolToBitStr($isparent) .
        "', is_enabled='" . cnvrtBoolToBitStr($isenbld) . "', " .
        "is_retained_earnings='" . cnvrtBoolToBitStr($isretearngs) .
        "', is_net_income='" . cnvrtBoolToBitStr($isnetincome) .
        "', accnt_typ_id = " . getAcctTypID($accntTyp) .
        ", report_line_no = " . $rpt_ln .
        ", has_sub_ledgers = '" . cnvrtBoolToBitStr($hasSbLdgrs) .
        "', control_account_id = " . $cntrlAccntID .
        ", crncy_id = " . $currID .
        ", is_suspens_accnt = '" . cnvrtBoolToBitStr($isSuspns) .
        "', account_clsfctn = '" . loc_db_escape_string($accClsftn) .
        "', accnt_seg1_val_id=" . $accntSegmnt1 .
        ", accnt_seg2_val_id=" . $accntSegmnt2 .
        ", accnt_seg3_val_id =" . $accntSegmnt3 .
        ", accnt_seg4_val_id =" . $accntSegmnt4 .
        ", accnt_seg5_val_id =" . $accntSegmnt5 .
        ", accnt_seg6_val_id =" . $accntSegmnt6 .
        ", accnt_seg7_val_id =" . $accntSegmnt7 .
        ", accnt_seg8_val_id =" . $accntSegmnt8 .
        ",  accnt_seg9_val_id =" . $accntSegmnt9 .
        ", accnt_seg10_val_id =" . $accntSegmnt10 .
        ", mapped_grp_accnt_id =" . $mappedAcntID .
        " WHERE accnt_id = " . $accntid;
    return execUpdtInsSQL($updtSQL);
}

function get_Basic_BatchDet($searchWord, $searchIn, $offset, $limit_size, $orgID, $shwUsrOnly, $shwUnpstdOnly)
{
    global $canVwOnlySelf;
    global $usrID;
    $whercls = "";

    if ($canVwOnlySelf == true || $shwUsrOnly == true) {
        $whercls = " AND (a.created_by=" . $usrID . ")";
    }
    $unpstdCls = "";
    if ($shwUnpstdOnly) {
        $unpstdCls = " AND (a.batch_status!='1')";
    }
    $strSql = "";
    $whercls1 = "";

    if ($searchIn == "Batch Name") {
        $whercls1 = " AND (a.batch_name ilike '" . loc_db_escape_string($searchWord) .
            "' or trim(to_char(a.batch_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Batch Description") {
        $whercls1 = " AND (a.batch_description ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Batch Status") {
        $whercls1 = " AND ((CASE WHEN a.batch_status='1' THEN 'Posted' ELSE 'Not Posted' END) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Batch Number") {
        $whercls1 = " AND (trim(to_char(a.batch_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Batch Date") {
        $whercls1 = " AND (to_char(to_timestamp(a.creation_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else {
        $whercls1 = " AND (a.batch_name ilike '" . loc_db_escape_string($searchWord) .
            "' or trim(to_char(a.batch_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "' or a.batch_description ilike '" . loc_db_escape_string($searchWord) .
            "' or (CASE WHEN a.batch_status='1' THEN 'Posted' ELSE 'Not Posted' END) ilike '" . loc_db_escape_string($searchWord) .
            "' or trim(to_char(a.batch_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "' or to_char(to_timestamp(a.creation_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT a.batch_id, a.batch_name, CASE WHEN a.src_batch_id<=0 THEN a.batch_description ELSE a.reversal_reason END, " .
        "a.batch_status, to_char(to_timestamp(a.creation_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), 
                    a.batch_source, a.batch_vldty_status, CASE WHEN a.avlbl_for_postng='1' THEN 'Pending Auto-Post' ELSE 'Not Monitored' END,                     
                    accb.get_batch_trnssum(a.batch_id,'dbt_amount') dbt_amount, 
                    accb.get_batch_trnssum(a.batch_id,'crdt_amount') crdt_amount, 
                    accb.get_batch_trnssum(a.batch_id,'net_amount') net_amount " .
        "FROM accb.accb_trnsctn_batches a " .
        "WHERE ((a.org_id = " . $orgID . ")" . $whercls1 . "" . $whercls . $unpstdCls . ") ORDER BY a.batch_id DESC LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_Batches($searchWord, $searchIn, $orgID, $shwUsrOnly, $shwUnpstdOnly)
{
    global $canVwOnlySelf;
    global $usrID;

    $whercls = "";
    $unpstdCls = "";
    if ($canVwOnlySelf == true || $shwUsrOnly == true) {
        $whercls = " AND (a.created_by=" . $usrID . ")";
    }
    if ($shwUnpstdOnly) {
        $unpstdCls = " AND (a.batch_status!='1')";
    }
    $strSql = "";
    $whercls1 = "";

    if ($searchIn == "Batch Name") {
        $whercls1 = " AND (a.batch_name ilike '" . loc_db_escape_string($searchWord) .
            "' or trim(to_char(a.batch_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Batch Description") {
        $whercls1 = " AND (a.batch_description ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Batch Status") {
        $whercls1 = " AND ((CASE WHEN a.batch_status='1' THEN 'Posted' ELSE 'Not Posted' END) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Batch Number") {
        $whercls1 = " AND (trim(to_char(a.batch_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Batch Date") {
        $whercls1 = " AND (to_char(to_timestamp(a.creation_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else {
        $whercls1 = " AND (a.batch_name ilike '" . loc_db_escape_string($searchWord) .
            "' or trim(to_char(a.batch_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "' or a.batch_description ilike '" . loc_db_escape_string($searchWord) .
            "' or (CASE WHEN a.batch_status='1' THEN 'Posted' ELSE 'Not Posted' END) ilike '" . loc_db_escape_string($searchWord) .
            "' or trim(to_char(a.batch_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "' or to_char(to_timestamp(a.creation_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT count(1) FROM accb.accb_trnsctn_batches a " .
        "WHERE ((a.org_id = " . $orgID . ")" . $whercls1 . "" . $whercls . $unpstdCls . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_One_BatchDet($batchID)
{
    global $canVwOnlySelf;
    global $usrID;
    $whercls = "";

    if ($canVwOnlySelf == true) {
        $whercls = " AND (a.created_by=" . $usrID . ")";
    }
    $strSql = "SELECT a.batch_id, a.batch_name, a.batch_description, " .
        "a.batch_status, to_char(to_timestamp(a.creation_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), 
                    a.batch_source, a.batch_vldty_status, CASE WHEN a.avlbl_for_postng='1' THEN 'Pending Auto-Post' ELSE 'Not Monitored' END,                     
                    accb.get_batch_trnssum(a.batch_id,'dbt_amount') dbt_amount, 
                    accb.get_batch_trnssum(a.batch_id,'crdt_amount') crdt_amount, 
                    accb.get_batch_trnssum(a.batch_id,'net_amount') net_amount, 
                    a.batch_vldty_status, a.src_batch_id, 
                    a.dflt_blcng_acnt_id, accb.get_accnt_num(a.dflt_blcng_acnt_id) || '.' || accb.get_accnt_name(a.dflt_blcng_acnt_id) dflt_blcng_acnt,
                    a.reversal_reason, a.dflt_cur_id, gst.get_pssbl_val(a.dflt_cur_id), 
                  CASE WHEN a.dflt_trans_date!='' THEN
                  to_char(to_timestamp(a.dflt_trans_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') 
                  ELSE to_char(to_timestamp(a.creation_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS')  END " .
        "FROM accb.accb_trnsctn_batches a " .
        "WHERE ((a.batch_id = " . $batchID . ")" . $whercls . ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_Batch_Trns($batchID, $lmtSze = 1000000000)
{
    $strSql = "SELECT a.transctn_id, b.accnt_num, b.accnt_name, " .
        "a.transaction_desc, a.dbt_amount, a.crdt_amount, " .
        "to_char(to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), a.func_cur_id, " .
        "a.batch_id, a.accnt_id, a.net_amount, a.trns_status, a.entered_amnt, gst.get_pssbl_val(a.entered_amt_crncy_id), a.entered_amt_crncy_id, " .
        "a.accnt_crncy_amnt, gst.get_pssbl_val(a.accnt_crncy_id), a.accnt_crncy_id, a.func_cur_exchng_rate, "
        . "a.accnt_cur_exchng_rate, a.ref_doc_number, gst.get_pssbl_val(a.func_cur_id), a.source_trns_ids, a.dbt_or_crdt, a.trnsctn_smmry_id " .
        "FROM accb.accb_trnsctn_details a LEFT OUTER JOIN " .
        "accb.accb_chart_of_accnts b on a.accnt_id = b.accnt_id " .
        "WHERE(a.batch_id = " . $batchID . ") ORDER BY a.trnsctn_date ASC, a.transctn_id ASC LIMIT " . $lmtSze . " OFFSET 0";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_DetTransToExprt($batchID, $limit_size = 1000000000)
{
    //global $orgID;
    //b.org_id = "  . $orgID . " and 
    $strSql = "SELECT b.accnt_num, b.accnt_name, a.transaction_desc, a.dbt_amount, a.crdt_amount, " .
        "to_char(to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') " .
        "FROM accb.accb_trnsctn_details a LEFT OUTER JOIN " .
        "accb.accb_chart_of_accnts b on a.accnt_id = b.accnt_id Where a.batch_id = " . $batchID .
        " ORDER BY a.transctn_id DESC LIMIT " . $limit_size . " OFFSET 0";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_EditTransToExprt($batchID, $limit_size = 1000000000)
{
    //global $orgID;
    //b.org_id = "  . $orgID . " and 
    $strSql = "SELECT a.transaction_desc, a.ref_doc_number, a.dbt_or_crdt, a.accnt_id, b.accnt_num, b.accnt_name, a.entered_amnt, gst.get_pssbl_val(a.entered_amt_crncy_id),  " .
        "to_char(to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') " .
        "FROM accb.accb_trnsctn_details a LEFT OUTER JOIN " .
        "accb.accb_chart_of_accnts b on a.accnt_id = b.accnt_id Where a.batch_id = " . $batchID .
        " ORDER BY a.transctn_id DESC LIMIT " . $limit_size . " OFFSET 0";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_SmmryTransToExprt($batchID, $limit_size = 1000000000)
{
    //global $orgID;
    //b.org_id = "  . $orgID . " and 
    $strSql = "SELECT a.trnsctn_smmry_desc, a.ref_doc_number, "
        . "a.incrs_dcrs1, accb.get_accnt_num(a.acnt_id1), accb.get_accnt_name(a.acnt_id1), "
        . "a.incrs_dcrs2, accb.get_accnt_num(a.acnt_id2), accb.get_accnt_name(a.acnt_id2), "
        . "a.trnsctn_smmry_amnt, gst.get_pssbl_val(a.entrd_curr_id),  " .
        "to_char(to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') " .
        "FROM accb.accb_trnsctn_smmrys a Where a.batch_id = " . $batchID .
        " ORDER BY a.trnsctn_date ASC, a.trnsctn_smmry_id ASC LIMIT " . $limit_size . " OFFSET 0";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_JrnlSmmry_Trns($batchID, $lmtSze = 1000000000)
{
    $strSql = "SELECT a.trnsctn_smmry_id, a.ref_doc_number, a.trnsctn_smmry_desc, a.trnsctn_smmry_amnt, 
        to_char(to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') trnsctn_date, 
        a.incrs_dcrs1, a.acnt_id1, accb.get_accnt_num(a.acnt_id1) || '.' || accb.get_accnt_name(a.acnt_id1), 
        a.incrs_dcrs2, a.acnt_id2, accb.get_accnt_num(a.acnt_id2) || '.' || accb.get_accnt_name(a.acnt_id2), 
        a.entrd_curr_id, gst.get_pssbl_val(a.entrd_curr_id), 
        a.func_cur_exchng_rate " .
        "FROM accb.accb_trnsctn_smmrys a " .
        "WHERE(a.batch_id = " . $batchID . ") ORDER BY a.trnsctn_date ASC, a.trnsctn_smmry_id ASC LIMIT " . $lmtSze . " OFFSET 0";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Batch_dateSums($batchID)
{
    $strSql = "SELECT substring(a.trnsctn_date from 1 for 10), round(SUM(a.dbt_amount),4), round(SUM(a.crdt_amount),4) 
    FROM accb.accb_trnsctn_details a
    WHERE(a.batch_id = " . $batchID . ") 
    GROUP BY substring(a.trnsctn_date from 1 for 10) 
    HAVING round(SUM(a.dbt_amount),2) != round(SUM(a.crdt_amount),2)
    ORDER BY 1";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Batch_DbtSum($batchID)
{
    $strSql = "SELECT SUM(a.dbt_amount)" .
        "FROM accb.accb_trnsctn_details a " .
        "WHERE(a.batch_id = " . $batchID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return round((float) $row[0], 2);
    }
    return 0;
}

function get_Batch_CrdtSum($batchID)
{
    $strSql = "SELECT SUM(a.crdt_amount)" .
        "FROM accb.accb_trnsctn_details a " .
        "WHERE(a.batch_id = " . $batchID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return round((float) $row[0], 2);
    }
    return 0;
}

function getBatchID($batchname, $orgid)
{
    $strSql = "SELECT a.batch_id " .
        "FROM accb.accb_trnsctn_batches a " .
        "WHERE ((a.batch_name ilike '" . loc_db_escape_string($batchname) .
        "') AND (a.org_id = " . $orgid . "))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return round((float) $row[0]);
    }
    return -1;
}

function getBatchNm($batchid)
{
    $strSql = "SELECT a.batch_name " .
        "FROM accb.accb_trnsctn_batches a " .
        "WHERE ((a.batch_id = " . $batchid . "))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getNewJrnlBatchID()
{
    $strSql = "select nextval('accb.accb_trnsctn_batches_batch_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return round((float) $row[0]);
    }
    return -1;
}

function createBatch(
    $orgid,
    $batchname,
    $batchdesc,
    $btchsrc,
    $batchvldty,
    $srcbatchid,
    $avlblforPpstng,
    $dfltBlcngAcntID,
    $rvrsalReason,
    $dfltCurID,
    $dfltTrnsDte
) {
    global $usrID;
    if ($dfltTrnsDte != "") {
        $dfltTrnsDte = cnvrtDMYTmToYMDTm($dfltTrnsDte);
    }
    $insSQL = "INSERT INTO accb.accb_trnsctn_batches(" .
        "batch_name, batch_description, created_by, creation_date, " .
        "org_id, batch_status, last_update_by, last_update_date, " .
        "batch_source, batch_vldty_status, src_batch_id, avlbl_for_postng, "
        . "dflt_blcng_acnt_id, reversal_reason, dflt_cur_id, dflt_trans_date) " .
        "VALUES ('" . loc_db_escape_string($batchname) . "', '" . loc_db_escape_string($batchdesc) .
        "', " . $usrID . ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $orgid .
        ", '0', " . $usrID . ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($btchsrc) .
        "', '" . loc_db_escape_string($batchvldty) .
        "', " . $srcbatchid .
        ",'" . $avlblforPpstng . "'," . $dfltBlcngAcntID . ",'" . loc_db_escape_string($rvrsalReason) .
        "'," . $dfltCurID . ", '" . loc_db_escape_string($dfltTrnsDte) .
        "')";
    return execUpdtInsSQL($insSQL);
}

function voidJrnlBatch($oldbatchid, $rvrsalReason, $dfltTrnsDte)
{
    global $usrID;
    if ($dfltTrnsDte != "") {
        $dfltTrnsDte = cnvrtDMYTmToYMDTm($dfltTrnsDte);
    }
    $insSQL = "INSERT INTO accb.accb_trnsctn_batches(" .
        "batch_name, batch_description, created_by, creation_date, " .
        "org_id, batch_status, last_update_by, last_update_date, " .
        "batch_source, batch_vldty_status, src_batch_id, avlbl_for_postng, "
        . "dflt_blcng_acnt_id, reversal_reason, dflt_cur_id, dflt_trans_date) " .
        "SELECT 'RVRSL-' || batch_name, '(Reversal) '||batch_description, " . $usrID . ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " .
        "org_id, '0', " . $usrID . ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " .
        "'Manual Batch Reversal', 'VALID', " . $oldbatchid . ", '0', "
        . "dflt_blcng_acnt_id, '" . loc_db_escape_string($rvrsalReason) .
        "', dflt_cur_id, '" . $dfltTrnsDte . "' FROM accb.accb_trnsctn_batches WHERE batch_id=" . $oldbatchid;
    return execUpdtInsSQL($insSQL);
}

function updateBatch($batchid, $batchname, $batchdesc, $dfltBlcngAcntID, $rvrsalReason, $dfltCurID, $dfltTrnsDte)
{
    global $usrID;
    if ($dfltTrnsDte != "") {
        $dfltTrnsDte = cnvrtDMYTmToYMDTm($dfltTrnsDte);
    }
    $updtSQL = "UPDATE accb.accb_trnsctn_batches " .
        "SET batch_name='" . loc_db_escape_string($batchname) .
        "', batch_description='" . loc_db_escape_string($batchdesc) .
        "', dflt_blcng_acnt_id=" . $dfltBlcngAcntID .
        ", reversal_reason='" . loc_db_escape_string($rvrsalReason) .
        "', dflt_cur_id=" . $dfltCurID .
        ", dflt_trans_date='" . loc_db_escape_string($dfltTrnsDte) .
        "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS') WHERE batch_id = " . $batchid;
    return execUpdtInsSQL($updtSQL);
}

function updateBatchVoid($batchid, $rvrsalReason)
{
    global $usrID;
    $updtSQL = "UPDATE accb.accb_trnsctn_batches " .
        "SET reversal_reason='" . loc_db_escape_string($rvrsalReason) .
        "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS') WHERE batch_id = " . $batchid;
    return execUpdtInsSQL($updtSQL);
}

function updateBatchAvlblty($batchid, $nwStatus)
{
    global $usrID;
    $updtSQL = "UPDATE accb.accb_trnsctn_batches " .
        "SET avlbl_for_postng='" . loc_db_escape_string($nwStatus) .
        "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS') WHERE batch_id = " . $batchid;
    return execUpdtInsSQL($updtSQL);
}

function updateBatchVldtyStatus($batchid, $nwVldtyStatus)
{
    global $usrID;
    $updtSQL = "UPDATE accb.accb_trnsctn_batches " .
        "SET batch_vldty_status='" . loc_db_escape_string($nwVldtyStatus) .
        "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS') WHERE batch_id = " . $batchid;
    return execUpdtInsSQL($updtSQL);
}

function deleteBatch($batchID, $batchNm)
{
    $strSql = "SELECT count(1) FROM accb.accb_trnsctn_details a WHERE(a.batch_id = " . $batchID . " and a.trns_status='1')";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete a Batch with Posted Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $lnkdVoidedBatchId = (float) getGnrlRecNm("accb.accb_trnsctn_batches", "batch_id", "src_batch_id", $batchID);
    $delSQL = "DELETE FROM accb.accb_trnsctn_details WHERE (batch_id = " . $batchID . ")";
    $affctd2 = execUpdtInsSQL($delSQL, "Desc:" . $batchNm);
    $delSQL = "DELETE FROM accb.accb_trnsctn_batches WHERE (batch_id = " . $batchID . ")";
    $affctd1 = execUpdtInsSQL($delSQL, "Desc:" . $batchNm);
    if ($affctd1 > 0) {
        $updtSQL = "UPDATE accb.accb_trnsctn_batches SET batch_vldty_status='VALID' WHERE (batch_id = " . $lnkdVoidedBatchId . " and batch_id>0)";
        $affctd = execUpdtInsSQL($updtSQL);
        $updtSQL = "UPDATE accb.accb_pybls_invc_hdr SET gl_batch_id=-1, approval_status='Not Validated', next_aproval_action='Approve' WHERE (gl_batch_id = " . $batchID . ")";
        $affctd += execUpdtInsSQL($updtSQL);
        $updtSQL = "UPDATE accb.accb_rcvbls_invc_hdr SET gl_batch_id=-1, approval_status='Not Validated', next_aproval_action='Approve' WHERE (gl_batch_id = " . $batchID . ")";
        $affctd += execUpdtInsSQL($updtSQL);
        $updtSQL = "UPDATE accb.accb_ptycsh_vchr_hdr SET gl_batch_id=-1, approval_status='Not Validated', next_aproval_action='Approve' WHERE (gl_batch_id = " . $batchID . ")";
        $affctd += execUpdtInsSQL($updtSQL);
        $updtSQL = "UPDATE accb.accb_payments SET gl_batch_id=-1 WHERE (gl_batch_id = " . $batchID . ")";
        $affctd += execUpdtInsSQL($updtSQL);
        $updtSQL = "UPDATE accb.accb_fa_asset_trns SET gl_batch_id=-1 WHERE (gl_batch_id = " . $batchID . ")";
        $affctd += execUpdtInsSQL($updtSQL);
        $updtSQL = "UPDATE scm.scm_gl_interface SET gl_batch_id=-1 WHERE (gl_batch_id = " . $batchID . ")";
        $affctd += execUpdtInsSQL($updtSQL);
        $updtSQL = "UPDATE mcf.mcf_gl_interface SET gl_batch_id=-1 WHERE (gl_batch_id = " . $batchID . ")";
        $affctd += execUpdtInsSQL($updtSQL);
        $updtSQL = "UPDATE vms.vms_gl_interface SET gl_batch_id=-1 WHERE (gl_batch_id = " . $batchID . ")";
        $affctd += execUpdtInsSQL($updtSQL);
        $updtSQL = "UPDATE pay.pay_gl_interface SET gl_batch_id=-1 WHERE (gl_batch_id = " . $batchID . ")";
        $affctd += execUpdtInsSQL($updtSQL);

        $dsply = "Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Journal Batch(es)!";
        $dsply .= "<br/>Deleted $affctd2 Journal Transaction(s)!";
        $dsply .= "<br/>Updated $affctd Other Source Module's Transaction(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteJrnlDetln($trnsID, $batchNm)
{
    $strSql = "SELECT count(1) FROM accb.accb_trnsctn_details a WHERE(a.transctn_id = " . $trnsID . " and a.trns_status='1')";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete a Posted Transaction!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM accb.accb_trnsctn_amnt_breakdown WHERE (transaction_id = " . $trnsID . " and transaction_id>0)";
    $affctd3 = execUpdtInsSQL($delSQL, "Desc:" . $batchNm);
    $delSQL = "DELETE FROM accb.accb_trnsctn_smmrys WHERE (trnsctn_smmry_id "
        . "IN (Select b.trnsctn_smmry_id FROM accb.accb_trnsctn_details b WHERE b.transctn_id=" . $trnsID . "))";
    $affctd2 = execUpdtInsSQL($delSQL, "Desc:" . $batchNm);
    $delSQL = "DELETE FROM accb.accb_trnsctn_details WHERE (transctn_id = " . $trnsID . ")";
    $affctd1 = execUpdtInsSQL($delSQL, "Desc:" . $batchNm);
    if ($affctd1 > 0) {
        $dsply = "Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Journal Transaction(s)!";
        $dsply .= "<br/>Deleted $affctd2 Journal Summary Transaction(s)!";
        $dsply .= "<br/>Deleted $affctd3 Journal Transaction Breakdown(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteJrnlSmmryln($trnsSmryID, $batchNm)
{
    $strSql = "SELECT count(1) FROM accb.accb_trnsctn_details a WHERE(a.trnsctn_smmry_id = " . $trnsSmryID . " and a.trns_status='1')";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete Posted Transaction(s)!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM accb.accb_trnsctn_amnt_breakdown WHERE (trnsctn_smmry_id=" . $trnsSmryID . " and trnsctn_smmry_id>0)";
    $affctd3 = execUpdtInsSQL($delSQL, "Desc:" . $batchNm);
    $delSQL = "DELETE FROM accb.accb_trnsctn_details WHERE (trnsctn_smmry_id = " . $trnsSmryID . ")";
    $affctd2 = execUpdtInsSQL($delSQL, "Desc:" . $batchNm);
    $delSQL = "DELETE FROM accb.accb_trnsctn_smmrys WHERE (trnsctn_smmry_id=" . $trnsSmryID . ")";
    $affctd1 = execUpdtInsSQL($delSQL, "Desc:" . $batchNm);
    if ($affctd1 > 0) {
        $dsply = "Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Journal Summary Transaction(s)!";
        $dsply .= "<br/>Deleted $affctd2 Journal Transaction(s)!";
        $dsply .= "<br/>Deleted $affctd3 Journal Transaction Breakdown(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_IntfcTrns($searchWord, $searchIn, $offset, $limit_size, $orgID, $trnsID, $lowVal, $highVal, $orderBy)
{
    /* Date (ASC), Date (DESC) */
    $ordrCls = "ORDER BY to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS') DESC";
    if ($orderBy == "Date (ASC)") {
        $ordrCls = "ORDER BY to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS')";
    }
    $strSql = "";
    $intfcTblNm = "scm.scm_gl_interface";
    $extrDesc = "scm.get_src_doc_num(a.src_doc_id,a.src_doc_typ)";
    $batchID = (float) getGnrlRecNm("accb.accb_trnsctn_details", "transctn_id", "batch_id", $trnsID);

    $batchSrc = getGnrlRecNm("accb.accb_trnsctn_batches", "batch_id", "batch_source", $batchID);
    if (strpos($batchSrc, "Internal Payments") !== FALSE) {
        $intfcTblNm = "pay.pay_gl_interface";
        $extrDesc = "a.source_trns_id";
    } else if (strpos($batchSrc, "Banking") !== FALSE) {
        $intfcTblNm = "mcf.mcf_gl_interface";
        $extrDesc = "a.src_doc_typ";
    } else if (strpos($batchSrc, "Vault") !== FALSE) {
        $intfcTblNm = "vms.vms_gl_interface";
        $extrDesc = "a.src_doc_typ";
    }

    $amntCls = "";
    if ($lowVal != 0 || $highVal != 0) {
        $amntCls = " and ((a.dbt_amount !=0 and a.dbt_amount between " . $lowVal . " and " . $highVal .
            ") or (a.crdt_amount !=0 and a.crdt_amount between " . $lowVal . " and " . $highVal . "))";
    }
    $strSql = "SELECT c.transctn_id, b.accnt_num, b.accnt_name, 
a.transaction_desc || ' Source Doc: ' ||" . $extrDesc . " description, 
a.dbt_amount, a.crdt_amount,
to_char(to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), c.func_cur_id,
c.batch_id,c.accnt_id, a.net_amount, c.entered_amnt, gst.get_pssbl_val(c.entered_amt_crncy_id), c.entered_amt_crncy_id, 
c.accnt_crncy_amnt, gst.get_pssbl_val(c.accnt_crncy_id), c.accnt_crncy_id, c.func_cur_exchng_rate, c.accnt_cur_exchng_rate, 
(select d.batch_name from accb.accb_trnsctn_batches d where d.batch_id = c.batch_id) btch_nm, '' refdoc
FROM " . $intfcTblNm . " a, accb.accb_chart_of_accnts b, accb.accb_trnsctn_details c 
WHERE ((a.accnt_id = b.accnt_id and c.batch_id=a.gl_batch_id) and (b.accnt_num ilike '%')" . $amntCls . " and (b.org_id = " . $orgID . ") 
and c.transctn_id=" . $trnsID . " and c.source_trns_ids like '%,' || a.interface_id || ',%') " .
        $ordrCls . " LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_IntfcTrns($searchWord, $searchIn, $orgID, $trnsID, $lowVal, $highVal, $orderBy)
{
    /* Date (ASC)
      Date (DESC) */
    $ordrCls = "ORDER BY to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS') DESC";
    if ($orderBy == "Date (ASC)") {
        $ordrCls = "ORDER BY to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS')";
    }
    $strSql = "";
    $intfcTblNm = "scm.scm_gl_interface";
    $extrDesc = "scm.get_src_doc_num(a.src_doc_id,a.src_doc_typ)";
    $batchID = (float) getGnrlRecNm(
        "accb.accb_trnsctn_details",
        "transctn_id",
        "batch_id",
        $trnsID
    );

    $batchSrc = getGnrlRecNm(
        "accb.accb_trnsctn_batches",
        "batch_id",
        "batch_source",
        $batchID
    );
    if (strpos($batchSrc, "Internal Payments") !== FALSE) {
        $intfcTblNm = "pay.pay_gl_interface";
        $extrDesc = "a.source_trns_id";
    } else if (strpos($batchSrc, "Banking") !== FALSE) {
        $intfcTblNm = "mcf.mcf_gl_interface";
        $extrDesc = "a.src_doc_typ";
    } else if (strpos($batchSrc, "Vault") !== FALSE) {
        $intfcTblNm = "vms.vms_gl_interface";
        $extrDesc = "a.src_doc_typ";
    }

    $amntCls = "";
    if ($lowVal != 0 || $highVal != 0) {
        $amntCls = " and ((a.dbt_amount !=0 and a.dbt_amount between " . $lowVal . " and " . $highVal .
            ") or (a.crdt_amount !=0 and a.crdt_amount between " . $lowVal . " and " . $highVal . "))";
    }
    $strSql = "SELECT count(1) " .
        "FROM " . $intfcTblNm . " a, accb.accb_chart_of_accnts b, accb.accb_trnsctn_details c 
WHERE ((a.accnt_id = b.accnt_id and c.batch_id=a.gl_batch_id) and (b.accnt_num ilike '%')" . $amntCls . " and (b.org_id = " . $orgID . ") 
and c.transctn_id=" . $trnsID . " and c.source_trns_ids like '%,' || a.interface_id || ',%') ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return round((float) $row[0]);
    }
    return 0;
}

function get_AccntStmntTransactions($accntID, $dte1, $dte2, $isposted, $lowVal, $highVal, $showMnl, $showUnbalcd, $shwIntrfc)
{
    if ($dte1 != "") {
        $dte1 = cnvrtDMYTmToYMDTm($dte1);
    }
    if ($dte2 != "") {
        $dte2 = cnvrtDMYTmToYMDTm($dte2);
    }
    $strSql = "";
    $whereCls = "";
    if ($showUnbalcd == true) {
        $whereCls = " AND (select ','||string_agg(tbl1.trnsids,',')||','
    FROM(SELECT abs(a.net_amount), count(a.transctn_id), string_agg('' || a.transctn_id, ',') trnsids, sum(a.net_amount)
    FROM(accb.accb_trnsctn_details a LEFT OUTER JOIN accb.accb_trnsctn_batches c ON a.batch_id = c.batch_id)
    LEFT OUTER JOIN accb.accb_chart_of_accnts b on a.accnt_id = b.accnt_id
    WHERE((b.accnt_id = " . $accntID . " or b.prnt_accnt_id = " . $accntID . " or b.control_account_id = " . $accntID .
            ") and(trns_status = '" . cnvrtBoolToBitStr($isposted) .
            "') and(to_timestamp(a.trnsctn_date, 'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS')))
    GROUP BY 1
    HAVING sum(a.net_amount)!= 0
    ORDER BY abs(a.net_amount) DESC) tbl1) like '%,' || a.transctn_id || ',%'";
    }
    /* mod(count(a.transctn_id), 2) != 0 */
    if ($showMnl == true) {
        $whereCls = " AND COALESCE((select min(z.transctn_id) from accb.accb_trnsctn_details z 
where a.net_amount=-1*z.net_amount and a.trnsctn_date<=z.trnsctn_date and a.transctn_id<z.transctn_id 
 and a.accnt_id=z.accnt_id and z.trns_status = '" . cnvrtBoolToBitStr($isposted) .
            "' and a.net_amount>=0 and (to_timestamp(z.trnsctn_date,'YYYY-MM-DD HH24:MI:SS') between 
to_timestamp('" . $dte1 . "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS'))),-1)<=0
  AND COALESCE((select min(z.transctn_id) from accb.accb_trnsctn_details z 
where a.net_amount=-1*z.net_amount and a.trnsctn_date>=z.trnsctn_date and a.transctn_id>z.transctn_id 
 and a.accnt_id=z.accnt_id and z.trns_status = '" . cnvrtBoolToBitStr($isposted) .
            "' and a.net_amount<0 and (to_timestamp(z.trnsctn_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS'))),-1)<=0";
    }
    $amntCls = "";
    if ($lowVal != 0 || $highVal != 0) {
        $amntCls = " and ((dbt_amount !=0 and dbt_amount between " . $lowVal . " and " . $highVal .
            ") or (crdt_amount !=0 and crdt_amount between " . $lowVal . " and " . $highVal . "))";
    }
    if ($shwIntrfc) {
        $strSql = "SELECT a.transctn_id, b.accnt_num, b.accnt_name, 
COALESCE(y.transaction_desc, COALESCE(y1.transaction_desc, COALESCE(y2.transaction_desc, COALESCE(y3.transaction_desc, a.transaction_desc)))), 
COALESCE(y.dbt_amount, COALESCE(y1.dbt_amount, COALESCE(y2.dbt_amount, COALESCE(y3.dbt_amount, a.dbt_amount)))), 
COALESCE(y.crdt_amount, COALESCE(y1.crdt_amount, COALESCE(y2.crdt_amount, COALESCE(y3.crdt_amount, a.crdt_amount)))), 
to_char(to_timestamp(COALESCE(y.trnsctn_date, COALESCE(y1.trnsctn_date, COALESCE(y2.trnsctn_date, COALESCE(y3.trnsctn_date, a.trnsctn_date)))),'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), 
a.func_cur_id, a.batch_id, a.accnt_id,
COALESCE(y.net_amount, COALESCE(y1.net_amount, COALESCE(y2.net_amount, COALESCE(y3.net_amount, a.net_amount)))), 
c.batch_name, a.trns_status, c.batch_source, " .
            " a.entered_amnt, gst.get_pssbl_val(a.entered_amt_crncy_id), a.entered_amt_crncy_id, " .
            "a.accnt_crncy_amnt, gst.get_pssbl_val(a.accnt_crncy_id), a.accnt_crncy_id, a.func_cur_exchng_rate, a.accnt_cur_exchng_rate, 
c.batch_name btch_nm, a.ref_doc_number, a.is_reconciled, a.dbt_or_crdt, " .
            "c.batch_vldty_status, c.src_batch_id " .
            "FROM (accb.accb_trnsctn_details a " .
            "LEFT OUTER JOIN accb.accb_trnsctn_batches c ON a.batch_id = c.batch_id) " .
            "LEFT OUTER JOIN accb.accb_chart_of_accnts b on a.accnt_id = b.accnt_id " .
            "LEFT OUTER JOIN mcf.mcf_gl_interface y on (a.accnt_id = y.accnt_id and y.gl_batch_id=a.batch_id and (c.batch_source ilike '%Banking%') and a.source_trns_ids like '%,' || y.interface_id || ',%') " .
            "LEFT OUTER JOIN vms.vms_gl_interface y1 on (a.accnt_id = y1.accnt_id and y1.gl_batch_id=a.batch_id and c.batch_source ilike '%Vault Management%' and a.source_trns_ids like '%,' || y1.interface_id || ',%') " .
            "LEFT OUTER JOIN pay.pay_gl_interface y2 on (a.accnt_id = y2.accnt_id and y2.gl_batch_id=a.batch_id and c.batch_source ilike '%Internal Payments%' and a.source_trns_ids like '%,' || y2.interface_id || ',%') " .
            "LEFT OUTER JOIN scm.scm_gl_interface y3 on (a.accnt_id = y3.accnt_id and y3.gl_batch_id=a.batch_id and c.batch_source ilike '%Inventory%' and a.source_trns_ids like '%,' || y3.interface_id || ',%') " .
            "WHERE((b.accnt_id = " . $accntID . " or b.prnt_accnt_id=" . $accntID . " or b.control_account_id=" . $accntID .
            ") and (trns_status = '" . cnvrtBoolToBitStr($isposted) .
            "') and (to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS'))" . $whereCls . $amntCls . ") " .
            "ORDER BY to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS') ASC, a.transctn_id ";
    } else {
        $strSql = "SELECT a.transctn_id, b.accnt_num, b.accnt_name, a.transaction_desc, a.dbt_amount, 
a.crdt_amount, to_char(to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), 
a.func_cur_id, a.batch_id, a.accnt_id, a.net_amount, c.batch_name, a.trns_status, c.batch_source, " .
            " a.entered_amnt, gst.get_pssbl_val(a.entered_amt_crncy_id), a.entered_amt_crncy_id, " .
            "a.accnt_crncy_amnt, gst.get_pssbl_val(a.accnt_crncy_id), a.accnt_crncy_id, a.func_cur_exchng_rate, a.accnt_cur_exchng_rate, 
c.batch_name btch_nm, a.ref_doc_number, a.is_reconciled, a.dbt_or_crdt, " .
            "c.batch_vldty_status, c.src_batch_id " .
            "FROM (accb.accb_trnsctn_details a LEFT OUTER JOIN accb.accb_trnsctn_batches c ON a.batch_id = c.batch_id) " .
            "LEFT OUTER JOIN accb.accb_chart_of_accnts b on a.accnt_id = b.accnt_id " .
            "WHERE((b.accnt_id = " . $accntID . " or b.prnt_accnt_id=" . $accntID . " or b.control_account_id=" . $accntID .
            ") and (trns_status = '" . cnvrtBoolToBitStr($isposted) .
            "') and (to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS'))" . $whereCls . $amntCls . ") " .
            "ORDER BY to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS') ASC, a.transctn_id ";
    }
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AccntClassStmntTrns($accntClass, $dte1, $dte2, $isposted, $lowVal, $highVal)
{
    if ($dte1 != "") {
        $dte1 = cnvrtDMYTmToYMDTm($dte1);
    }
    if ($dte2 != "") {
        $dte2 = cnvrtDMYTmToYMDTm($dte2);
    }
    $strSql = "";
    $whereCls = "";

    $amntCls = "";
    if ($lowVal != 0 || $highVal != 0) {
        $amntCls = " and ((dbt_amount !=0 and dbt_amount between " . $lowVal . " and " . $highVal .
            ") or (crdt_amount !=0 and crdt_amount between " . $lowVal . " and " . $highVal . "))";
    }

    $strSql = "SELECT distinct a.transctn_id, b.accnt_num, b.accnt_name, a.transaction_desc, a.dbt_amount, 
        a.crdt_amount, to_char(to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), 
        a.func_cur_id, a.batch_id, a.accnt_id, a.net_amount, c.batch_name, a.trns_status, c.batch_source, " .
        " a.entered_amnt, gst.get_pssbl_val(a.entered_amt_crncy_id), a.entered_amt_crncy_id, " .
        "a.accnt_crncy_amnt, gst.get_pssbl_val(a.accnt_crncy_id), a.accnt_crncy_id, a.func_cur_exchng_rate, a.accnt_cur_exchng_rate, 
        c.batch_name btch_nm, a.ref_doc_number, a.is_reconciled, a.dbt_or_crdt, " .
        "c.batch_vldty_status, c.src_batch_id, a.trnsctn_date " .
        "FROM (accb.accb_trnsctn_details a LEFT OUTER JOIN accb.accb_trnsctn_batches c ON a.batch_id = c.batch_id) " .
        "LEFT OUTER JOIN accb.accb_chart_of_accnts b on a.accnt_id = b.accnt_id " .
        "WHERE((b.account_clsfctn = '" . loc_db_escape_string($accntClass) . "' and b.is_prnt_accnt='0' and b.has_sub_ledgers='0') " .
        "and (trns_status = '" . cnvrtBoolToBitStr($isposted) .
        "') and (to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
        "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS'))" . $whereCls . $amntCls . ") " .
        "ORDER BY a.trnsctn_date ASC, a.transctn_id ";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AccntClassAccounts($accntClass)
{
    $strSql = "";
    $whereCls = "";

    $amntCls = "";
    $strSql = "SELECT b.accnt_id, b.accnt_num, b.accnt_name " .
        "FROM accb.accb_chart_of_accnts b " .
        "WHERE((b.account_clsfctn = '" . loc_db_escape_string($accntClass) . "' and b.is_prnt_accnt='0' and b.has_sub_ledgers='0') " .
        "" . $whereCls . $amntCls . ") ORDER BY b.accnt_num ";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_JrnBatchAttachments($searchWord, $offset, $limit_size, $hdrID, &$attchSQL)
{
    $strSql = "SELECT a.attchmnt_id, a.batch_id, a.attchmnt_desc, a.file_name " .
        "FROM accb.accb_batch_trns_attchmnts a " .
        "WHERE(a.attchmnt_desc ilike '" . loc_db_escape_string($searchWord) .
        "' and a.batch_id = " . $hdrID . ") ORDER BY a.attchmnt_id LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    $attchSQL = $strSql;
    return $result;
}

function get_Total_JrnBatchAttachments($searchWord, $hdrID)
{
    $strSql = "SELECT count(1) " .
        "FROM accb.accb_batch_trns_attchmnts a " .
        "WHERE(a.attchmnt_desc ilike '" . loc_db_escape_string($searchWord) .
        "' and a.batch_id = " . $hdrID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function getJrnBatchAttchmtDocs($batchid)
{
    $sqlStr = "SELECT attchmnt_id, file_name, attchmnt_desc
  FROM accb.accb_batch_trns_attchmnts WHERE 1=1 AND file_name != '' AND batch_id = " . $batchid;

    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function updateJrnlBatchDocFlNm($attchmnt_id, $file_name)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE accb.accb_batch_trns_attchmnts SET file_name='"
        . loc_db_escape_string($file_name) .
        "', last_update_by=" . $usrID .
        ", last_update_date='" . $dateStr . "'
                WHERE attchmnt_id=" . $attchmnt_id;
    return execUpdtInsSQL($insSQL);
}

function getNewJrnlBatchDocID()
{
    $strSql = "select nextval('accb.accb_batch_attchmnt_id_seq')";
    $result = executeSQLNoParams($strSql);

    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function createJrnlBatchDoc($attchmnt_id, $hdrid, $attchmnt_desc, $file_name)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO accb.accb_batch_trns_attchmnts(
            attchmnt_id, batch_id, attchmnt_desc, file_name, created_by, 
            creation_date, last_update_by, last_update_date)
             VALUES (" . $attchmnt_id . ", " . $hdrid . ",'"
        . loc_db_escape_string($attchmnt_desc) . "','"
        . loc_db_escape_string($file_name) . "',"
        . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";
    return execUpdtInsSQL($insSQL);
}

function deleteJrnlBatchDoc($pkeyID, $docTrnsNum = "")
{
    $insSQL = "DELETE FROM accb.accb_batch_trns_attchmnts WHERE attchmnt_id = " . $pkeyID;
    $affctd1 = execUpdtInsSQL($insSQL, "Trns. No:" . $docTrnsNum);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Attached Document(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function uploadDaJrnlBatchDoc($attchmntID, &$nwImgLoc, &$errMsg)
{
    global $tmpDest;
    global $ftp_base_db_fldr;
    global $usrID;
    global $fldrPrfx;
    global $smplTokenWord1;

    $msg = "";
    $allowedExts = array(
        'png', 'jpg', 'gif', 'jpeg', 'bmp', 'pdf', 'xls', 'xlsx',
        'doc', 'docx', 'ppt', 'pptx', 'txt', 'csv'
    );

    if (isset($_FILES["daJrnlBatchAttchmnt"])) {
        $flnm = $_FILES["daJrnlBatchAttchmnt"]["name"];
        $temp = explode(".", $flnm);
        $extension = end($temp);
        if ($_FILES["daJrnlBatchAttchmnt"]["error"] > 0) {
            $msg .= "Return Code: " . $_FILES["daJrnlBatchAttchmnt"]["error"] . "<br>";
        } else {
            $msg .= "Uploaded File: " . $_FILES["daJrnlBatchAttchmnt"]["name"] . "<br>";
            $msg .= "Type: " . $_FILES["daJrnlBatchAttchmnt"]["type"] . "<br>";
            $msg .= "Size: " . round(($_FILES["daJrnlBatchAttchmnt"]["size"]) / (1024 * 1024), 2) . " MB<br>";
            //$msg .= "Temp file: " . $_FILES["daJrnlBatchAttchmnt"]["tmp_name"] . "<br>";
            if ((($_FILES["daJrnlBatchAttchmnt"]["type"] == "image/gif") || ($_FILES["daJrnlBatchAttchmnt"]["type"] == "image/jpeg") || ($_FILES["daJrnlBatchAttchmnt"]["type"] == "image/jpg") || ($_FILES["daJrnlBatchAttchmnt"]["type"] == "image/pjpeg") || ($_FILES["daJrnlBatchAttchmnt"]["type"] == "image/x-png") ||
                ($_FILES["daJrnlBatchAttchmnt"]["type"] == "image/png") || in_array($extension, $allowedExts)) && ($_FILES["daJrnlBatchAttchmnt"]["size"] <
                10000000)) {
                $nwFileName = encrypt1($attchmntID . "." . $extension, $smplTokenWord1) . "." . $extension;
                $img_src = $fldrPrfx . $tmpDest . "$nwFileName";
                move_uploaded_file($_FILES["daJrnlBatchAttchmnt"]["tmp_name"], $img_src);
                $ftp_src = $ftp_base_db_fldr . "/Accntn/$attchmntID" . "." . $extension;
                if (file_exists($img_src)) {
                    copy("$img_src", "$ftp_src");
                    $dateStr = getDB_Date_time();
                    $updtSQL = "UPDATE accb.accb_batch_trns_attchmnts
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
                $msg .= "Invalid file!<br/>File Size must be below 10MB and<br/>File Type must be in the ff:<br/>" . implode(
                    ", ",
                    $allowedExts
                );
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

function getNewAmntBrkDwnID()
{
    $strSql = "select nextval('accb.accb_trnsctn_amnt_breakdown_trns_amnt_det_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return round((float) $row[0]);
    }
    return -1;
}

function getTemplateNm($templtID)
{
    $strSql = "SELECT a.template_name " .
        "FROM accb.accb_trnsctn_templates_hdr a " .
        "WHERE ((a.template_id = " . $templtID . "))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getAttchmntID($attchname, $batchID, $tblNm, $pkName)
{
    $strSql = "SELECT a.attchmnt_id " .
        "FROM " . loc_db_escape_string($tblNm) . " a " .
        "WHERE ((a.attchmnt_desc = '" . loc_db_escape_string($attchname) .
        "') AND (a." . $pkName . " = " . $batchID . "))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return round((float) $row[0]);
    }
    return -1;
}

function getSimlrPstdBatchID($srcbatchid, $orgnlbatchname, $orgid)
{
    $strSql = "SELECT a.batch_id " .
        "FROM accb.accb_trnsctn_batches a " .
        "WHERE (((a.src_batch_id = " . $srcbatchid .
        ") or (a.batch_name ilike '" . loc_db_escape_string($orgnlbatchname) .
        "' AND a.batch_vldty_status = 'VOID')) AND (a.org_id = " . $orgid . "))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return round((float) $row[0]);
    }
    return -1;
}

function getSimlrPstdBatchID2($orgnlbatchname, $orgid)
{
    $srcbatchid = getBatchID($orgnlbatchname, $orgid);
    $strSql = "SELECT a.batch_id " .
        "FROM accb.accb_trnsctn_batches a " .
        "WHERE (((a.src_batch_id = " . $srcbatchid .
        ") or (a.batch_name ilike '" . loc_db_escape_string($orgnlbatchname) .
        "' AND a.batch_vldty_status = 'VOID')) AND (a.org_id = " . $orgid . "))";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return round((float) $row[0]);
    }
    return -1;
}

function get_DfltPttyCashAcnt($orgID)
{
    global $prsnid;
    $strSql = "SELECT org.get_dflt_accnt_id(" . $prsnid . ", petty_cash_acnt_id) " .
        "FROM scm.scm_dflt_accnts a " .
        "WHERE(a.org_id = " . $orgID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return ((int) $row[0]);
    }
    return -1;
}

function get_DfltCstmrCashPayMthd($orgID)
{
    global $prsnid;
    $strSql = "SELECT paymnt_mthd_id " .
        "FROM accb.accb_paymnt_mthds a " .
        "WHERE(a.org_id = " . $orgID . " and upper(a.pymnt_mthd_name) like upper('%Customer%')  "
        . "and upper(a.pymnt_mthd_name) like upper('%Cash%')) ORDER BY 1 DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return ((int) $row[0]);
    }
    return -1;
}

function get_DfltPayMthdID($orgID, $cstmrOrSpplr, $mthdNamePortion)
{
    $strSql = "SELECT paymnt_mthd_id " .
        "FROM accb.accb_paymnt_mthds a " .
        "WHERE(a.is_enabled='1' and a.org_id = " . $orgID . " and upper(a.supported_doc_type) like upper('%" . loc_db_escape_string($cstmrOrSpplr) . "%')  "
        . "and upper(a.pymnt_mthd_name) like upper('%" . loc_db_escape_string($mthdNamePortion) . "%')) ORDER BY 1 DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return ((int) $row[0]);
    }
    return -1;
}

function get_DfltPayMthdNm($orgID, $cstmrOrSpplr, $mthdNamePortion)
{
    $strSql = "SELECT pymnt_mthd_name " .
        "FROM accb.accb_paymnt_mthds a " .
        "WHERE(a.is_enabled='1' and a.org_id = " . $orgID . " and upper(a.supported_doc_type) like upper('%" . loc_db_escape_string($cstmrOrSpplr) . "%')  "
        . "and upper(a.pymnt_mthd_name) like upper('%" . loc_db_escape_string($mthdNamePortion) . "%')) ORDER BY 1 DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return ($row[0]);
    }
    return -1;
}

function get_DfltPyblsCashAcnt($orgID)
{
    global $prsnid;
    $strSql = "SELECT org.get_dflt_accnt_id(" . $prsnid . ", rcpt_lblty_acnt_id) " .
        "FROM scm.scm_dflt_accnts a " .
        "WHERE(a.org_id = " . $orgID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return ((int) $row[0]);
    }
    return -1;
}

function get_DfltSplrPyblsCashAcnt($spplrID, $orgID)
{
    global $prsnid;
    $strSql = "SELECT (CASE WHEN " . $spplrID . ">0 THEN (SELECT dflt_pybl_accnt_id FROM scm.scm_cstmr_suplr WHERE cust_sup_id=" . $spplrID . ") "
        . "ELSE (SELECT org.get_dflt_accnt_id(" . $prsnid . ", rcpt_lblty_acnt_id)) END) " .
        "FROM scm.scm_dflt_accnts a " .
        "WHERE(a.org_id = " . $orgID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return ((int) $row[0]);
    }
    return -1;
}

function get_DfltCstmrRcvblsCashAcnt($spplrID, $orgID)
{
    global $prsnid;
    $strSql = "SELECT (CASE WHEN " . $spplrID . ">0 THEN (SELECT dflt_rcvbl_accnt_id FROM scm.scm_cstmr_suplr WHERE cust_sup_id=" . $spplrID . ") "
        . "ELSE (SELECT org.get_dflt_accnt_id(" . $prsnid . ", sales_rcvbl_acnt_id)) END) " .
        "FROM scm.scm_dflt_accnts a " .
        "WHERE(a.org_id = " . $orgID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return ((int) $row[0]);
    }
    return -1;
}

function get_PttyCashDocHdr($searchWord, $searchIn, $offset, $limit_size, $orgID, $shwUnpstdOnly)
{
    $strSql = "";
    $whrcls = "";
    $unpstdCls = "";
    if ($shwUnpstdOnly) {
        $unpstdCls = " AND (round(a.invoice_amount-a.amnt_paid,2)>0 or a.approval_status IN ('Not Validated','Validated','Reviewed') " .
            "or accb.is_gl_batch_pstd(a.gl_batch_id)='0' )";
    }
    if ($searchIn == "Document Number") {
        $whrcls = " and (a.ptycsh_vchr_number ilike '" . loc_db_escape_string($searchWord) .
            "' or trim(to_char(a.ptycsh_vchr_hdr_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Document Description") {
        $whrcls = " and (a.comments_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Document Classification") {
        $whrcls = " and (a.doc_tmplt_clsfctn ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Supplier Name") {
        $whrcls = " and (a.supplier_id IN (select c.cust_sup_id from 
scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
            "'))";
    } else if ($searchIn == "Supplier's Invoice Number") {
        $whrcls = " and (a.spplrs_invc_num ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Source Doc Number") {
        $whrcls = " and (trim(to_char(a.src_doc_hdr_id, '9999999999999999999999999')) 
IN (select trim(to_char(d.rcpt_id, '9999999999999999999999999')) from inv.inv_consgmt_rcpt_hdr d 
where trim(to_char(d.rcpt_id, '9999999999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "') or trim(to_char(a.src_doc_hdr_id, '9999999999999999999999999')) 
IN (select trim(to_char(e.rcpt_rtns_id, '9999999999999999999999999')) from inv.inv_consgmt_rcpt_rtns_hdr e 
where trim(to_char(e.rcpt_rtns_id, '9999999999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "') or a.src_doc_hdr_id IN (select f.ptycsh_vchr_hdr_id from accb.accb_ptycsh_vchr_hdr f
where f.ptycsh_vchr_number ilike '" . loc_db_escape_string($searchWord) . "'))";
    } else if ($searchIn == "Approval Status") {
        $whrcls = " and (a.approval_status ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Created By") {
        $whrcls = " and (sec.get_usr_name(a.created_by) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Currency") {
        $whrcls = " and (gst.get_pssbl_val(a.invc_curr_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else {
        $whrcls = " and (a.ptycsh_vchr_number ilike '" . loc_db_escape_string($searchWord) .
            "' or trim(to_char(a.ptycsh_vchr_hdr_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "' or a.comments_desc ilike '" . loc_db_escape_string($searchWord) .
            "' or a.doc_tmplt_clsfctn ilike '" . loc_db_escape_string($searchWord) .
            "' or a.supplier_id IN (select c.cust_sup_id from 
scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
            "') or a.spplrs_invc_num ilike '" . loc_db_escape_string($searchWord) .
            "' or trim(to_char(a.src_doc_hdr_id, '9999999999999999999999999')) 
IN (select trim(to_char(d.rcpt_id, '9999999999999999999999999')) from inv.inv_consgmt_rcpt_hdr d 
where trim(to_char(d.rcpt_id, '9999999999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "') or trim(to_char(a.src_doc_hdr_id, '9999999999999999999999999')) 
IN (select trim(to_char(e.rcpt_rtns_id, '9999999999999999999999999')) from inv.inv_consgmt_rcpt_rtns_hdr e 
where trim(to_char(e.rcpt_rtns_id, '9999999999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "') or a.src_doc_hdr_id IN (select f.ptycsh_vchr_hdr_id from accb.accb_ptycsh_vchr_hdr f
where f.ptycsh_vchr_number ilike '" . loc_db_escape_string($searchWord) .
            "') or a.approval_status ilike '" . loc_db_escape_string($searchWord) .
            "' or sec.get_usr_name(a.created_by) ilike '" . loc_db_escape_string($searchWord) .
            "' or gst.get_pssbl_val(a.invc_curr_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT ptycsh_vchr_hdr_id, ptycsh_vchr_number, ptycsh_vchr_type
, round(a.invoice_amount-a.amnt_paid,2), a.approval_status, a.gl_batch_id,
to_char(to_timestamp(a.ptycsh_vchr_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ptycsh_vchr_date,
comments_desc, a.supplier_id, scm.get_cstmr_splr_name(a.supplier_id) payee, a.invc_curr_id,
gst.get_pssbl_val(a.invc_curr_id) crncy, round(a.invoice_amount,2) invc_amnt,
a.balancing_accnt_id, accb.get_accnt_num(a.balancing_accnt_id) || '.' || accb.get_accnt_name(a.balancing_accnt_id) ptty_cash_acnt,
accb.is_gl_batch_pstd(a.gl_batch_id) is_pstd
        FROM accb.accb_ptycsh_vchr_hdr a 
        WHERE((a.org_id = " . $orgID . ")" . $whrcls . $unpstdCls .
        ") ORDER BY ptycsh_vchr_hdr_id DESC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_PttyCashDoc($searchWord, $searchIn, $orgID, $shwUnpstdOnly)
{
    $strSql = "";
    $whrcls = "";
    $unpstdCls = "";
    if ($shwUnpstdOnly) {
        $unpstdCls = " AND (round(a.invoice_amount-a.amnt_paid,2)>0 or a.approval_status IN ('Not Validated','Validated','Reviewed') " .
            "or accb.is_gl_batch_pstd(a.gl_batch_id)='0' )";
    }
    if ($searchIn == "Document Number") {
        $whrcls = " and (a.ptycsh_vchr_number ilike '" . loc_db_escape_string($searchWord) . "' or trim(to_char(a.ptycsh_vchr_hdr_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Document Description") {
        $whrcls = " and (a.comments_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Document Classification") {
        $whrcls = " and (a.doc_tmplt_clsfctn ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Supplier Name") {
        $whrcls = " and (a.supplier_id IN (select c.cust_sup_id from 
scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
            "'))";
    } else if ($searchIn == "Supplier's Invoice Number") {
        $whrcls = " and (a.spplrs_invc_num ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Source Doc Number") {
        $whrcls = " and (trim(to_char(a.src_doc_hdr_id, '9999999999999999999999999')) 
IN (select trim(to_char(d.rcpt_id, '9999999999999999999999999')) from inv.inv_consgmt_rcpt_hdr d 
where trim(to_char(d.rcpt_id, '9999999999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "') or trim(to_char(a.src_doc_hdr_id, '9999999999999999999999999')) 
IN (select trim(to_char(e.rcpt_rtns_id, '9999999999999999999999999')) from inv.inv_consgmt_rcpt_rtns_hdr e 
where trim(to_char(e.rcpt_rtns_id, '9999999999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "') or a.src_doc_hdr_id IN (select f.ptycsh_vchr_hdr_id from accb.accb_ptycsh_vchr_hdr f
where f.ptycsh_vchr_number ilike '" . loc_db_escape_string($searchWord) .
            "'))";
    } else if ($searchIn == "Approval Status") {
        $whrcls = " and (a.approval_status ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Created By") {
        $whrcls = " and (sec.get_usr_name(a.created_by) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Currency") {
        $whrcls = " and (gst.get_pssbl_val(a.invc_curr_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else {
        $whrcls = " and (a.ptycsh_vchr_number ilike '" . loc_db_escape_string($searchWord) .
            "' or trim(to_char(a.ptycsh_vchr_hdr_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "' or a.comments_desc ilike '" . loc_db_escape_string($searchWord) .
            "' or a.doc_tmplt_clsfctn ilike '" . loc_db_escape_string($searchWord) .
            "' or a.supplier_id IN (select c.cust_sup_id from 
scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
            "') or a.spplrs_invc_num ilike '" . loc_db_escape_string($searchWord) .
            "' or trim(to_char(a.src_doc_hdr_id, '9999999999999999999999999')) 
IN (select trim(to_char(d.rcpt_id, '9999999999999999999999999')) from inv.inv_consgmt_rcpt_hdr d 
where trim(to_char(d.rcpt_id, '9999999999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "') or trim(to_char(a.src_doc_hdr_id, '9999999999999999999999999')) 
IN (select trim(to_char(e.rcpt_rtns_id, '9999999999999999999999999')) from inv.inv_consgmt_rcpt_rtns_hdr e 
where trim(to_char(e.rcpt_rtns_id, '9999999999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "') or a.src_doc_hdr_id IN (select f.ptycsh_vchr_hdr_id from accb.accb_ptycsh_vchr_hdr f
where f.ptycsh_vchr_number ilike '" . loc_db_escape_string($searchWord) .
            "') or a.approval_status ilike '" . loc_db_escape_string($searchWord) .
            "' or sec.get_usr_name(a.created_by) ilike '" . loc_db_escape_string($searchWord) .
            "' or gst.get_pssbl_val(a.invc_curr_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $strSql = "SELECT count(1) FROM accb.accb_ptycsh_vchr_hdr a  
        WHERE((a.org_id = " . $orgID . ")" . $whrcls . $unpstdCls . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_One_PttyCashDocHdr($hdrID)
{
    $strSql = "SELECT ptycsh_vchr_hdr_id, to_char(to_timestamp(ptycsh_vchr_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), 
       created_by, sec.get_usr_name(a.created_by), ptycsh_vchr_number, ptycsh_vchr_type, 
       comments_desc, src_doc_hdr_id, supplier_id, scm.get_cstmr_splr_name(a.supplier_id),
       supplier_site_id, scm.get_cstmr_splr_site_name(a.supplier_site_id), 
       approval_status, next_aproval_action, invoice_amount, 
       payment_terms, src_doc_type, pymny_method_id, accb.get_pymnt_mthd_name(a.pymny_method_id), 
       amnt_paid, gl_batch_id, accb.get_gl_batch_name(a.gl_batch_id),
       spplrs_invc_num, doc_tmplt_clsfctn, invc_curr_id, gst.get_pssbl_val(a.invc_curr_id),
        event_rgstr_id, evnt_cost_category, event_doc_type, balancing_accnt_id,
        accb.get_accnt_num(a.balancing_accnt_id) || '.' || accb.get_accnt_name(a.balancing_accnt_id) balancing_accnt,
        accb.is_gl_batch_pstd(a.gl_batch_id) is_pstd
  FROM accb.accb_ptycsh_vchr_hdr a " .
        "WHERE((a.ptycsh_vchr_hdr_id = " . $hdrID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PttyCashDocDet($docHdrID)
{
    $strSql = "";
    $whrcls = " and (a.ptycsh_smmry_type !='6Grand Total' and 
a.ptycsh_smmry_type !='7Total Payments Made' and a.ptycsh_smmry_type !='8Outstanding Balance')";
    $strSql = "SELECT ptycsh_smmry_id, ptycsh_smmry_type, ptycsh_smmry_desc, ptycsh_smmry_amnt, 
       code_id_behind, auto_calc, incrs_dcrs1, 
       asset_expns_acnt_id, incrs_dcrs2, liability_acnt_id, appld_prepymnt_doc_id, 
       entrd_curr_id, gst.get_pssbl_val(a.entrd_curr_id), 
       func_curr_id, gst.get_pssbl_val(a.func_curr_id), 
      accnt_curr_id, gst.get_pssbl_val(a.accnt_curr_id), 
      func_curr_rate, accnt_curr_rate, 
       func_curr_amount, accnt_curr_amnt, initial_amnt_line_id, ref_doc_number,
        accb.get_accnt_num(a.asset_expns_acnt_id) || '.' || accb.get_accnt_name(a.asset_expns_acnt_id) charge_accnt,
        accb.get_accnt_num(a.liability_acnt_id) || '.' || accb.get_accnt_name(a.liability_acnt_id) balancing_accnt, a.slctd_amnt_brkdwns 
  FROM accb.accb_ptycsh_amnt_smmrys a " .
        "WHERE((a.src_ptycsh_hdr_id = " . $docHdrID . ")" . $whrcls . ") ORDER BY ptycsh_smmry_type ASC ";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getNewPttyCashLnID()
{
    $strSql = "select nextval('accb.accb_ptycsh_amnt_smmrys_ptycsh_smmry_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function getLtstPttyCashIDNoInPrfx($prfxTxt)
{
    global $orgID;
    $sqlStr = "select count(ptycsh_vchr_hdr_id) from accb.accb_ptycsh_vchr_hdr WHERE org_id=" .
        $orgID . " and ptycsh_vchr_number ilike '" . loc_db_escape_string($prfxTxt) . "%'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return str_pad(((((float) $row[0]) + 1) . ""), 4, '0', STR_PAD_LEFT);
    }
    return "0001";
}

function getNewAccbPttyCashID()
{
    $strSql = "select nextval('accb.accb_ptycsh_vchr_hdr_ptycsh_vchr_hdr_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function createPttyCashDocHdr(
    $orgid,
    $docDte,
    $docNum,
    $docType,
    $docDesc,
    $srcDocHdrID,
    $spplrID,
    $spplrSiteID,
    $apprvlStatus,
    $nxtApprvlActn,
    $invcAmnt,
    $pymntTrms,
    $srcDocType,
    $pymntMthdID,
    $amntPaid,
    $glBtchID,
    $spplrInvcNum,
    $docTmpltClsftn,
    $currID,
    $amntAppld,
    $rgstrID,
    $costCtgry,
    $evntType,
    $blcngAccntID
) {
    global $usrID;
    if ($docDte != "") {
        $docDte = cnvrtDMYToYMD($docDte);
    }
    $insSQL = "INSERT INTO accb.accb_ptycsh_vchr_hdr(
            ptycsh_vchr_date, created_by, creation_date, 
            last_update_by, last_update_date, ptycsh_vchr_number, ptycsh_vchr_type, 
            comments_desc, src_doc_hdr_id, supplier_id, supplier_site_id, 
            approval_status, next_aproval_action, org_id, invoice_amount, 
            payment_terms, src_doc_type, pymny_method_id, amnt_paid, gl_batch_id, 
            spplrs_invc_num, doc_tmplt_clsfctn, invc_curr_id, invc_amnt_appld_elswhr,
            event_rgstr_id, evnt_cost_category, event_doc_type, balancing_accnt_id) " .
        "VALUES ('" . loc_db_escape_string($docDte) .
        "', " . $usrID . ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($docNum) .
        "', '" . loc_db_escape_string($docType) .
        "', '" . loc_db_escape_string($docDesc) .
        "', " . $srcDocHdrID .
        ", " . $spplrID .
        ", " . $spplrSiteID .
        ", '" . loc_db_escape_string($apprvlStatus) .
        "', '" . loc_db_escape_string($nxtApprvlActn) .
        "', " . $orgid .
        ", " . $invcAmnt .
        ", '" . loc_db_escape_string($pymntTrms) .
        "', '" . loc_db_escape_string($srcDocType) .
        "', " . $pymntMthdID .
        ", " . $amntPaid .
        ", " . $glBtchID .
        ", '" . loc_db_escape_string($spplrInvcNum) .
        "', '" . loc_db_escape_string($docTmpltClsftn) .
        "', " . $currID . ", " . $amntAppld . ", " . $rgstrID .
        ", '" . loc_db_escape_string($costCtgry) .
        "', '" . loc_db_escape_string($evntType) . "', " . $blcngAccntID . ")";
    return execUpdtInsSQL($insSQL);
}

function updtPttyCashDocHdr(
    $hdrID,
    $docDte,
    $docNum,
    $docType,
    $docDesc,
    $srcDocHdrID,
    $spplrID,
    $spplrSiteID,
    $apprvlStatus,
    $nxtApprvlActn,
    $invcAmnt,
    $pymntTrms,
    $srcDocType,
    $pymntMthdID,
    $amntPaid,
    $glBtchID,
    $spplrInvcNum,
    $docTmpltClsftn,
    $currID,
    $amntAppld,
    $rgstrID,
    $costCtgry,
    $evntType,
    $blcngAccntID
) {
    global $usrID;
    if ($docDte != "") {
        $docDte = cnvrtDMYToYMD($docDte);
    }
    $insSQL = "UPDATE accb.accb_ptycsh_vchr_hdr
       SET ptycsh_vchr_date='" . $docDte .
        "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), ptycsh_vchr_number='" . loc_db_escape_string($docNum) .
        "', ptycsh_vchr_type='" . loc_db_escape_string($docType) .
        "', comments_desc='" . loc_db_escape_string($docDesc) .
        "', src_doc_hdr_id=" . $srcDocHdrID .
        ", supplier_id=" . $spplrID .
        ", supplier_site_id=" . $spplrSiteID .
        ", approval_status='" . loc_db_escape_string($apprvlStatus) .
        "', next_aproval_action='" . loc_db_escape_string($nxtApprvlActn) .
        "', invoice_amount=" . $invcAmnt .
        ", payment_terms='" . loc_db_escape_string($pymntTrms) .
        "', src_doc_type='" . loc_db_escape_string($srcDocType) .
        "', pymny_method_id=" . $pymntMthdID .
        ", amnt_paid=" . $amntPaid .
        ", gl_batch_id=" . $glBtchID .
        ", spplrs_invc_num='" . loc_db_escape_string($spplrInvcNum) .
        "', doc_tmplt_clsfctn='" . loc_db_escape_string($docTmpltClsftn) .
        "', invc_curr_id=" . $currID .
        ", invc_amnt_appld_elswhr=" . $amntAppld .
        ", event_rgstr_id=" . $rgstrID .
        ", evnt_cost_category='" . loc_db_escape_string($costCtgry) .
        "', event_doc_type='" . loc_db_escape_string($evntType) .
        "', balancing_accnt_id=" . $blcngAccntID .
        " WHERE ptycsh_vchr_hdr_id = " . $hdrID;
    return execUpdtInsSQL($insSQL);
}

function updatePttyCashDocVoid($batchid, $rvrsalReason, $nwStatus, $nextAction, $glBatchID)
{
    global $usrID;
    $updtSQL = "UPDATE accb.accb_ptycsh_vchr_hdr " .
        "SET comments_desc='" . loc_db_escape_string($rvrsalReason) .
        "', approval_status='" . loc_db_escape_string($nwStatus) .
        "', next_aproval_action='" . loc_db_escape_string($nextAction) .
        "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS')" .
        ", gl_batch_id=" . $glBatchID .
        " WHERE ptycsh_vchr_hdr_id = " . $batchid;
    return execUpdtInsSQL($updtSQL);
}

function createPttyCashDocDet(
    $smmryID,
    $hdrID,
    $lineType,
    $lineDesc,
    $entrdAmnt,
    $entrdCurrID,
    $codeBhnd,
    $docType,
    $autoCalc,
    $incrDcrs1,
    $costngID,
    $incrDcrs2,
    $blncgAccntID,
    $prepayDocHdrID,
    $vldyStatus,
    $orgnlLnID,
    $funcCurrID,
    $accntCurrID,
    $funcCurrRate,
    $accntCurrRate,
    $funcCurrAmnt,
    $accntCurrAmnt,
    $initAmntID,
    $lnkdVmsHdrID,
    $refDocNum,
    $slctdBrkdwns
) {
    global $usrID;
    $insSQL = "INSERT INTO accb.accb_ptycsh_amnt_smmrys(
            ptycsh_smmry_id, ptycsh_smmry_type, ptycsh_smmry_desc, ptycsh_smmry_amnt, 
            code_id_behind, src_ptycsh_type, src_ptycsh_hdr_id, created_by, 
            creation_date, last_update_by, last_update_date, auto_calc, incrs_dcrs1, 
            asset_expns_acnt_id, incrs_dcrs2, liability_acnt_id, appld_prepymnt_doc_id, 
            validty_status, orgnl_line_id, entrd_curr_id, 
            func_curr_id, accnt_curr_id, func_curr_rate, accnt_curr_rate, 
            func_curr_amount, accnt_curr_amnt, initial_amnt_line_id,
            lnkd_vms_trns_hdr_id, ref_doc_number, slctd_amnt_brkdwns) " .
        "VALUES (" . $smmryID . ", '" . loc_db_escape_string($lineType) .
        "', '" . loc_db_escape_string($lineDesc) .
        "', " . $entrdAmnt .
        ", " . $codeBhnd .
        ", '" . loc_db_escape_string($docType) .
        "', " . $hdrID .
        ", " . $usrID . ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), '" . cnvrtBoolToBitStr($autoCalc) .
        "', '" . loc_db_escape_string($incrDcrs1) .
        "', " . $costngID .
        ", '" . loc_db_escape_string($incrDcrs2) .
        "', " . $blncgAccntID .
        ", " . $prepayDocHdrID .
        ", '" . loc_db_escape_string($vldyStatus) .
        "', " . $orgnlLnID .
        ", " . $entrdCurrID .
        ", " . $funcCurrID .
        ", " . $accntCurrID .
        ", " . $funcCurrRate .
        ", " . $accntCurrRate .
        ", " . $funcCurrAmnt .
        ", " . $accntCurrAmnt .
        ", " . $initAmntID .
        ", " . $lnkdVmsHdrID .
        ", '" . loc_db_escape_string($refDocNum) .
        "', '" . loc_db_escape_string($slctdBrkdwns) .
        "')";
    return execUpdtInsSQL($insSQL);
}

function voidPttyCashDocDet($oldhdrID, $newhdrID)
{
    global $usrID;
    $insSQL = "INSERT INTO accb.accb_ptycsh_amnt_smmrys(
            ptycsh_smmry_type, ptycsh_smmry_desc, ptycsh_smmry_amnt, 
            code_id_behind, src_ptycsh_type, src_ptycsh_hdr_id, created_by, 
            creation_date, last_update_by, last_update_date, auto_calc, incrs_dcrs1, 
            asset_expns_acnt_id, incrs_dcrs2, liability_acnt_id, appld_prepymnt_doc_id, 
            validty_status, orgnl_line_id, entrd_curr_id, 
            func_curr_id, accnt_curr_id, func_curr_rate, accnt_curr_rate, 
            func_curr_amount, accnt_curr_amnt, initial_amnt_line_id,
            lnkd_vms_trns_hdr_id, ref_doc_number, slctd_amnt_brkdwns) " .
        "Select ptycsh_smmry_type, ptycsh_smmry_desc, -1*ptycsh_smmry_amnt, 
            code_id_behind, src_ptycsh_type, " . $newhdrID . ", " . $usrID . ", 
            to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $usrID . ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), auto_calc, incrs_dcrs1, 
            asset_expns_acnt_id, incrs_dcrs2, liability_acnt_id, appld_prepymnt_doc_id, 
            validty_status, orgnl_line_id, entrd_curr_id, 
            func_curr_id, accnt_curr_id, func_curr_rate, accnt_curr_rate, 
            func_curr_amount, accnt_curr_amnt, initial_amnt_line_id,
            lnkd_vms_trns_hdr_id, ref_doc_number, slctd_amnt_brkdwns 
            FROM accb.accb_ptycsh_amnt_smmrys WHERE src_ptycsh_hdr_id=" . $oldhdrID;
    return execUpdtInsSQL($insSQL);
}

function updtPttyCashDocDet(
    $docDetID,
    $hdrID,
    $lineType,
    $lineDesc,
    $entrdAmnt,
    $entrdCurrID,
    $codeBhnd,
    $docType,
    $autoCalc,
    $incrDcrs1,
    $costngID,
    $incrDcrs2,
    $blncgAccntID,
    $prepayDocHdrID,
    $vldyStatus,
    $orgnlLnID,
    $funcCurrID,
    $accntCurrID,
    $funcCurrRate,
    $accntCurrRate,
    $funcCurrAmnt,
    $accntCurrAmnt,
    $initAmntID,
    $lnkdVmsHdrID,
    $refDocNum,
    $slctdBrkdwns
) {
    global $usrID;
    $insSQL = "UPDATE accb.accb_ptycsh_amnt_smmrys
   SET ptycsh_smmry_type='" . loc_db_escape_string($lineType) .
        "', ptycsh_smmry_desc='" . loc_db_escape_string($lineDesc) .
        "', ptycsh_smmry_amnt=" . $entrdAmnt .
        ", code_id_behind=" . $codeBhnd .
        ", src_ptycsh_type='" . loc_db_escape_string($docType) .
        "', src_ptycsh_hdr_id=" . $hdrID .
        ", last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), auto_calc='" . cnvrtBoolToBitStr($autoCalc) .
        "', incrs_dcrs1='" . loc_db_escape_string($incrDcrs1) .
        "', asset_expns_acnt_id=" . $costngID .
        ", incrs_dcrs2='" . loc_db_escape_string($incrDcrs2) .
        "', liability_acnt_id=" . $blncgAccntID .
        ", appld_prepymnt_doc_id=" . $prepayDocHdrID .
        ", validty_status='" . loc_db_escape_string($vldyStatus) .
        "', orgnl_line_id=" . $orgnlLnID .
        ", entrd_curr_id=" . $entrdCurrID .
        ", func_curr_id=" . $funcCurrID .
        ", accnt_curr_id=" . $accntCurrID .
        ", func_curr_rate=" . $funcCurrRate .
        ", accnt_curr_rate=" . $accntCurrRate .
        ", func_curr_amount=" . $funcCurrAmnt .
        ", accnt_curr_amnt=" . $accntCurrAmnt .
        ", initial_amnt_line_id=" . $initAmntID .
        ", lnkd_vms_trns_hdr_id=" . $lnkdVmsHdrID .
        ", ref_doc_number='" . loc_db_escape_string($refDocNum) .
        "', slctd_amnt_brkdwns='" . loc_db_escape_string($slctdBrkdwns) .
        "' WHERE ptycsh_smmry_id = " . $docDetID;
    return execUpdtInsSQL($insSQL);
}

function deletePttyCashDocHdrNDet($valLnid, $docNum)
{
    $strSql = "SELECT count(1) FROM accb.accb_ptycsh_vchr_hdr a WHERE(a.ptycsh_vchr_hdr_id = " . $valLnid .
        " and a.approval_status IN ('Validated', 'Approved', 'Cancelled','Initiated','Reviewed'))";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete a Finalized Document!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM accb.accb_ptycsh_amnt_smmrys WHERE src_ptycsh_hdr_id = " . $valLnid;
    $affctd2 = execUpdtInsSQL($delSQL, "Desc:" . $docNum);
    $delSQL = "DELETE FROM accb.accb_ptycsh_vchr_hdr WHERE ptycsh_vchr_hdr_id = " . $valLnid;
    $affctd1 = execUpdtInsSQL($delSQL, "Desc:" . $docNum);
    if ($affctd1 > 0) {
        $dsply = "";
        $batchID = (float) getGnrlRecNm("accb.accb_ptycsh_vchr_hdr", "ptycsh_vchr_hdr_id", "gl_batch_id", $valLnid);
        if ($batchID > 0) {
            $dsply .= deleteBatch($batchID, $docNum);
        }
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Petty Cash Voucher(s)!";
        $dsply .= "<br/>Deleted $affctd2 Petty Cash Transaction(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deletePttyCashDocDet($valLnid, $docNum)
{
    $strSql = "SELECT COUNT(1) FROM accb.accb_ptycsh_amnt_smmrys b, accb.accb_ptycsh_vchr_hdr a "
        . "WHERE b.ptycsh_smmry_id = " . $valLnid .
        " and b.src_ptycsh_hdr_id = a.ptycsh_vchr_hdr_id  "
        . "WHERE(a.approval_status IN ('Validated', 'Approved', 'Cancelled','Initiated','Reviewed')";
    $result1 = executeSQLNoParams($strSql, "Desc:" . $docNum);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete a Finalized Document!";
        return "<p style = \"text-align:left;color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM accb.accb_trnsctn_details WHERE source_mdl_det_line_id = " . $valLnid .
        " and source_mdl_name='Petty Cash' and trns_status='0'";
    $affctd1 = execUpdtInsSQL($delSQL);
    $delSQL = "DELETE FROM accb.accb_ptycsh_amnt_smmrys WHERE ptycsh_smmry_id = " . $valLnid;
    $affctd1 = execUpdtInsSQL($delSQL);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Petty Cash Line(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_PttyCashDocSmryLns($dochdrID, $docTyp)
{
    $strSql = "SELECT a.ptycsh_smmry_id, a.ptycsh_smmry_desc, " .
        "CASE WHEN substr(a.ptycsh_smmry_type,1,1) IN ('3','5') THEN -1 * a.ptycsh_smmry_amnt ELSE a.ptycsh_smmry_amnt END, a.code_id_behind, a.ptycsh_smmry_type, a.auto_calc " .
        "FROM accb.accb_ptycsh_amnt_smmrys a " .
        "WHERE((a.src_ptycsh_hdr_id = " . $dochdrID .
        ") and (a.src_ptycsh_type='" . $docTyp .
        "') and (substr(a.ptycsh_smmry_type,1,1) NOT IN ('6','7','8'))) ORDER BY a.ptycsh_smmry_type";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PttyCashDocEndLns($dochdrID, $docTyp)
{
    $strSql = "SELECT a.ptycsh_smmry_id, a.ptycsh_smmry_desc, " .
        "a.ptycsh_smmry_amnt, a.code_id_behind, substr(a.ptycsh_smmry_type,2), a.auto_calc " .
        "FROM accb.accb_ptycsh_amnt_smmrys a " .
        "WHERE((a.src_ptycsh_hdr_id = " . $dochdrID .
        ") and (a.src_ptycsh_type='" . $docTyp . "') and (substr(a.ptycsh_smmry_type,1,1) IN ('6','7'))) ORDER BY a.ptycsh_smmry_type";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getPttyCashDocGrndAmnt($dochdrID)
{
    $strSql = "select SUM(CASE WHEN y.ptycsh_smmry_type='3Discount' 
or scm.istaxwthhldng(y.code_id_behind)='1' or y.ptycsh_smmry_type='5Applied Prepayment'
      THEN -1*y.ptycsh_smmry_amnt ELSE y.ptycsh_smmry_amnt END) amnt " .
        "from accb.accb_ptycsh_amnt_smmrys y " .
        "where y.src_ptycsh_hdr_id=" . $dochdrID .
        " and y.ptycsh_smmry_type IN ('1Initial Amount','2Tax','3Discount','4Extra Charge','5Applied Prepayment')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function getPttyCashDocFuncAmnt($dochdrID)
{
    $strSql = "select SUM(CASE WHEN y.ptycsh_smmry_type='3Discount' 
or scm.istaxwthhldng(y.code_id_behind)='1' or y.ptycsh_smmry_type='5Applied Prepayment'
      THEN -1*y.func_curr_amount ELSE y.func_curr_amount END) amnt " .
        "from accb.accb_ptycsh_amnt_smmrys y " .
        "where y.src_ptycsh_hdr_id=" . $dochdrID .
        " and y.ptycsh_smmry_type IN ('1Initial Amount','2Tax','3Discount','4Extra Charge','5Applied Prepayment')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function getPttyCashDocAccntAmnt($dochdrID)
{
    $strSql = "select SUM(CASE WHEN y.ptycsh_smmry_type='3Discount' 
or scm.istaxwthhldng(y.code_id_behind)='1' or y.ptycsh_smmry_type='5Applied Prepayment'
      THEN -1*y.accnt_curr_amnt ELSE y.accnt_curr_amnt END) amnt " .
        "from accb.accb_ptycsh_amnt_smmrys y " .
        "where y.src_ptycsh_hdr_id=" . $dochdrID .
        " and y.ptycsh_smmry_type IN ('1Initial Amount','2Tax','3Discount','4Extra Charge','5Applied Prepayment')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function getPttyCashTrnsSumUsngStatus($accntID, $trnsStatus)
{
    $strSql = "select COALESCE(SUM(a.dbt_amount-a.crdt_amount),0) from accb.accb_trnsctn_details a, 
accb.accb_chart_of_accnts b where a.accnt_id = b.accnt_id and a.trns_status = '" . loc_db_escape_string($trnsStatus) .
        "' and a.accnt_id = " . $accntID;

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function getPttyCashSmmryItmID($smmryType, $codeBhnd, $srcDocID, $srcDocTyp, $smmryNm)
{
    $strSql = "select y.ptycsh_smmry_id " .
        "from accb.accb_ptycsh_amnt_smmrys y " .
        "where y.ptycsh_smmry_type= '" . loc_db_escape_string($smmryType) .
        "' and y.ptycsh_smmry_desc = '" . loc_db_escape_string($smmryNm) .
        "' and y.code_id_behind= " . $codeBhnd .
        " and y.src_ptycsh_type='" . loc_db_escape_string($srcDocTyp) .
        "' and y.src_ptycsh_hdr_id=" . $srcDocID . " ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function updtPttyCashDocApprvl($docid, $apprvlSts, $nxtApprvl)
{
    global $usrID;
    $extrCls = "";
    if ($apprvlSts == "Cancelled") {
        $extrCls = ", invoice_amount=0, invc_amnt_appld_elswhr=0";
    }
    $updtSQL = "UPDATE accb.accb_ptycsh_vchr_hdr SET " .
        "approval_status='" . loc_db_escape_string($apprvlSts) .
        "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS')"
        . ", next_aproval_action='" . loc_db_escape_string($nxtApprvl) .
        "'" . $extrCls . " WHERE (ptycsh_vchr_hdr_id = " . $docid . ")";
    return execUpdtInsSQL($updtSQL);
}

function updtPttyCashDocAmnt($docid, $invAmnt)
{
    global $usrID;
    $extrCls = ", invoice_amount=" . $invAmnt . "";
    $updtSQL = "UPDATE accb.accb_ptycsh_vchr_hdr SET " .
        "last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS')" . $extrCls . " WHERE (ptycsh_vchr_hdr_id = " .
        $docid . ")";
    return execUpdtInsSQL($updtSQL);
}

function updtPttyCashDocGLBatch($docid, $glBatchID)
{
    global $usrID;
    $updtSQL = "UPDATE accb.accb_ptycsh_vchr_hdr SET " .
        "gl_batch_id=" . $glBatchID .
        ", last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS') WHERE (ptycsh_vchr_hdr_id = " .
        $docid . ")";
    return execUpdtInsSQL($updtSQL);
}

function updtPttyCashDocAmntPaid($docid, $amntPaid)
{
    global $usrID;
    $updtSQL = "UPDATE accb.accb_ptycsh_vchr_hdr SET " .
        "amnt_paid=amnt_paid + " . $amntPaid .
        ", last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS') WHERE (ptycsh_vchr_hdr_id = " . $docid . ")";
    return execUpdtInsSQL($updtSQL);
}

function updtPttyCashDocAmntAppld($docid, $amntAppld)
{
    global $usrID;
    $updtSQL = "UPDATE accb.accb_ptycsh_vchr_hdr SET " .
        "invc_amnt_appld_elswhr=invc_amnt_appld_elswhr + " . $amntAppld .
        ", last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS') WHERE (ptycsh_vchr_hdr_id = " . $docid . ")";
    return execUpdtInsSQL($updtSQL);
}

function getPttyCashDocTtlPymnts($dochdrID, $docType)
{
    $strSql = "select SUM(y.amount_paid) amnt " .
        "from accb.accb_payments y " .
        "where y.src_doc_id = " . $dochdrID . " and y.src_doc_typ = '" . loc_db_escape_string($docType) . "'";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function getPttyCashDocBlncngAccnt($srcDocID, $docType)
{
    $whrcls = " and (a.ptycsh_smmry_type !='6Grand Total' and 
a.ptycsh_smmry_type !='7Total Payments Made' and a.ptycsh_smmry_type !='8Outstanding Balance')";

    $strSql = "select 
        distinct liability_acnt_id, ptycsh_smmry_id 
        from accb.accb_ptycsh_amnt_smmrys a 
        where src_ptycsh_hdr_id = " . $srcDocID .
        " and src_ptycsh_type = '" . loc_db_escape_string($docType) .
        "'" . $whrcls . " order by ptycsh_smmry_id LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function getPttyCashPrepayDocCnt($dochdrID)
{
    $strSql = "select count(appld_prepymnt_doc_id) " .
        "from accb.accb_ptycsh_amnt_smmrys y " .
        "where y.src_ptycsh_hdr_id = " . $dochdrID . " and y.appld_prepymnt_doc_id >0 " .
        "Group by y.appld_prepymnt_doc_id having count(y.appld_prepymnt_doc_id)>1";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_PttyCash_Attachments($searchWord, $offset, $limit_size, $batchID, &$attchSQL)
{
    $strSql = "SELECT a.attchmnt_id, a.doc_hdr_id, a.attchmnt_desc, a.file_name " .
        "FROM accb.accb_ptycsh_doc_attchmnts a " .
        "WHERE(a.attchmnt_desc ilike '" . loc_db_escape_string($searchWord) .
        "' and a.doc_hdr_id = " . $batchID . ") ORDER BY a.attchmnt_id LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_PttyCash_Attachments($searchWord, $batchID)
{
    $strSql = "SELECT count(1) " .
        "FROM accb.accb_ptycsh_doc_attchmnts a " .
        "WHERE(a.attchmnt_desc ilike '" . loc_db_escape_string($searchWord) .
        "' and a.doc_hdr_id = " . $batchID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function getPttyCashAttchmtDocs($batchid)
{
    $sqlStr = "SELECT attchmnt_id, file_name, attchmnt_desc
  FROM accb.accb_ptycsh_doc_attchmnts WHERE 1=1 AND file_name != '' AND doc_hdr_id = " . $batchid;

    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function updatePttyCashDocFlNm($attchmnt_id, $file_name)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE accb.accb_ptycsh_doc_attchmnts SET file_name='"
        . loc_db_escape_string($file_name) .
        "', last_update_by=" . $usrID .
        ", last_update_date='" . $dateStr . "'
                WHERE attchmnt_id=" . $attchmnt_id;
    return execUpdtInsSQL($insSQL);
}

function getNewPttyCashDocID()
{
    $strSql = "select nextval('accb.accb_ptycsh_doc_attchmnts_attchmnt_id_seq')";
    $result = executeSQLNoParams($strSql);

    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function createPttyCashDoc($attchmnt_id, $hdrid, $attchmnt_desc, $file_name)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO accb.accb_ptycsh_doc_attchmnts(
            attchmnt_id, doc_hdr_id, attchmnt_desc, file_name, created_by, 
            creation_date, last_update_by, last_update_date)
             VALUES (" . $attchmnt_id . ", " . $hdrid . ",'"
        . loc_db_escape_string($attchmnt_desc) . "','"
        . loc_db_escape_string($file_name) . "',"
        . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";
    return execUpdtInsSQL($insSQL);
}

function deletePttyCashDoc($pkeyID, $docTrnsNum = "")
{
    $insSQL = "DELETE FROM accb.accb_ptycsh_doc_attchmnts WHERE attchmnt_id = " . $pkeyID;
    $affctd1 = execUpdtInsSQL($insSQL, "Trns. No:" . $docTrnsNum);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Attached Document(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function uploadDaPttyCashDoc($attchmntID, &$nwImgLoc, &$errMsg)
{
    global $tmpDest;
    global $ftp_base_db_fldr;
    global $usrID;
    global $fldrPrfx;
    global $smplTokenWord1;

    $msg = "";
    $allowedExts = array(
        'png', 'jpg', 'gif', 'jpeg', 'bmp', 'pdf', 'xls', 'xlsx',
        'doc', 'docx', 'ppt', 'pptx', 'txt', 'csv'
    );

    if (isset($_FILES["daPttyCashAttchmnt"])) {
        $flnm = $_FILES["daPttyCashAttchmnt"]["name"];
        $temp = explode(".", $flnm);
        $extension = end($temp);
        if ($_FILES["daPttyCashAttchmnt"]["error"] > 0) {
            $msg .= "Return Code: " . $_FILES["daPttyCashAttchmnt"]["error"] . "<br>";
        } else {
            $msg .= "Uploaded File: " . $_FILES["daPttyCashAttchmnt"]["name"] . "<br>";
            $msg .= "Type: " . $_FILES["daPttyCashAttchmnt"]["type"] . "<br>";
            $msg .= "Size: " . round(($_FILES["daPttyCashAttchmnt"]["size"]) / (1024 * 1024), 2) . " MB<br>";
            //$msg .= "Temp file: " . $_FILES["daPttyCashAttchmnt"]["tmp_name"] . "<br>";
            if ((($_FILES["daPttyCashAttchmnt"]["type"] == "image/gif") || ($_FILES["daPttyCashAttchmnt"]["type"] == "image/jpeg") || ($_FILES["daPttyCashAttchmnt"]["type"] == "image/jpg") || ($_FILES["daPttyCashAttchmnt"]["type"] == "image/pjpeg") || ($_FILES["daPttyCashAttchmnt"]["type"] == "image/x-png") ||
                ($_FILES["daPttyCashAttchmnt"]["type"] == "image/png") || in_array($extension, $allowedExts)) && ($_FILES["daPttyCashAttchmnt"]["size"] <
                10000000)) {
                $nwFileName = encrypt1($attchmntID . "." . $extension, $smplTokenWord1) . "." . $extension;
                $img_src = $fldrPrfx . $tmpDest . "$nwFileName";
                move_uploaded_file($_FILES["daPttyCashAttchmnt"]["tmp_name"], $img_src);
                $ftp_src = $ftp_base_db_fldr . "/PtyCshDocs/$attchmntID" . "." . $extension;
                if (file_exists($img_src)) {
                    copy("$img_src", "$ftp_src");
                    $dateStr = getDB_Date_time();
                    $updtSQL = "UPDATE accb.accb_ptycsh_doc_attchmnts
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
                $msg .= "Invalid file!<br/>File Size must be below 10MB and<br/>File Type must be in the ff:<br/>" . implode(
                    ", ",
                    $allowedExts
                );
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

function get_COA_CRLSum($orgID)
{
    $strSql = "SELECT SUM(a.net_balance) " .
        "FROM accb.accb_chart_of_accnts a " .
        "WHERE ((a.org_id = " . $orgID . ") and " .
        "(a.is_net_income = '0') and (a.control_account_id <= 0) " .
        "and (a.accnt_type IN ('EQ','R', 'L')))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_COA_AESum($orgID)
{
    $strSql = "SELECT SUM(a.net_balance) " .
        "FROM accb.accb_chart_of_accnts a " .
        "WHERE ((a.org_id = " . $orgID . ") and " .
        "(a.is_net_income = '0') and (a.has_sub_ledgers !='1') " .
        "and (a.accnt_type IN ('A','EX')))";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_Transactions(
    $searchWord,
    $searchIn,
    $offset,
    $limit_size,
    $orgID,
    $dte1,
    $dte2,
    $isposted,
    $shwIntrfc,
    $lowVal,
    $highVal,
    $orderBy
) {
    /* Date (ASC)
      Date (DESC) */
    $ordrCls = "ORDER BY to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS') DESC";
    if ($orderBy == "Date (ASC)") {
        $ordrCls = "ORDER BY to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS')";
    }

    if ($dte1 != "") {
        $dte1 = cnvrtDMYTmToYMDTm($dte1);
    }
    if ($dte2 != "") {
        $dte2 = cnvrtDMYTmToYMDTm($dte2);
    }
    $strSql = "";
    $whereCls = "";
    $isposted1 = "";
    if ($isposted == true) {
        $isposted1 = " and (trns_status = '1')";
    }
    if ($shwIntrfc) {
        if ($searchIn == "Account Number") {
            $whereCls = " AND (b.accnt_num ilike '" . loc_db_escape_string($searchWord) .
                "' or accb.get_accnt_num(b.control_account_id) ilike '" . loc_db_escape_string($searchWord) .
                "' or b.accnt_name ilike '" . loc_db_escape_string($searchWord) .
                "' or accb.get_accnt_name(b.control_account_id) ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Account Name") {
            $whereCls = " AND (b.accnt_num ilike '" . loc_db_escape_string($searchWord) .
                "' or accb.get_accnt_num(b.control_account_id) ilike '" . loc_db_escape_string($searchWord) .
                "' or b.accnt_name ilike '" . loc_db_escape_string($searchWord) .
                "' or accb.get_accnt_name(b.control_account_id) ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Transaction Description") {
            $whereCls = " AND ((COALESCE(y.transaction_desc, COALESCE(y1.transaction_desc, COALESCE(y2.transaction_desc, COALESCE(y3.transaction_desc, a.transaction_desc))))) ilike '" . loc_db_escape_string($searchWord) .
                "' or a.ref_doc_number ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Transaction Date") {
            $whereCls = " AND (to_char(to_timestamp(COALESCE(y.trnsctn_date, COALESCE(y1.trnsctn_date, COALESCE(y2.trnsctn_date, COALESCE(y3.trnsctn_date, a.trnsctn_date)))),'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Batch Name") {
            $whereCls = " AND (c.batch_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
        } else if ($searchIn == "Transaction ID") {
            $whereCls = " AND (''||a.transctn_id = '" . loc_db_escape_string(str_replace("%", "", $searchWord)) .
                "')";
        } else {
            $whereCls = " AND (b.accnt_num ilike '" . loc_db_escape_string($searchWord) .
                "' or accb.get_accnt_num(b.control_account_id) ilike '" . loc_db_escape_string($searchWord) .
                "' or b.accnt_name ilike '" . loc_db_escape_string($searchWord) .
                "' or accb.get_accnt_name(b.control_account_id) ilike '" . loc_db_escape_string($searchWord) .
                "' or (COALESCE(y.transaction_desc, COALESCE(y1.transaction_desc, COALESCE(y2.transaction_desc, COALESCE(y3.transaction_desc, a.transaction_desc))))) ilike '" . loc_db_escape_string($searchWord) .
                "' or a.ref_doc_number ilike '" . loc_db_escape_string($searchWord) .
                "' or to_char(to_timestamp(COALESCE(y.trnsctn_date, COALESCE(y1.trnsctn_date, COALESCE(y2.trnsctn_date, COALESCE(y3.trnsctn_date, a.trnsctn_date)))),'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "' or c.batch_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
        }

        $amntCls = "";
        if ($lowVal != 0 || $highVal != 0) {
            $amntCls = " and ((COALESCE(y.dbt_amount, COALESCE(y1.dbt_amount, COALESCE(y2.dbt_amount, COALESCE(y3.dbt_amount, a.dbt_amount)))) !=0 "
                . "and COALESCE(y.dbt_amount, COALESCE(y1.dbt_amount, COALESCE(y2.dbt_amount, COALESCE(y3.dbt_amount, a.dbt_amount)))) between " . $lowVal . " and " . $highVal .
                ") or (COALESCE(y.crdt_amount, COALESCE(y1.crdt_amount, COALESCE(y2.crdt_amount, COALESCE(y3.crdt_amount, a.crdt_amount)))) !=0 "
                . "and COALESCE(y.crdt_amount, COALESCE(y1.crdt_amount, COALESCE(y2.crdt_amount, COALESCE(y3.crdt_amount, a.crdt_amount)))) between " . $lowVal . " and " . $highVal . "))";
        }
        $strSql = "SELECT a.transctn_id, b.accnt_num, b.accnt_name, 
            COALESCE(y.transaction_desc, COALESCE(y1.transaction_desc, COALESCE(y2.transaction_desc, COALESCE(y3.transaction_desc, a.transaction_desc)))),
            COALESCE(y.dbt_amount, COALESCE(y1.dbt_amount, COALESCE(y2.dbt_amount, COALESCE(y3.dbt_amount, a.dbt_amount)))), 
        COALESCE(y.crdt_amount, COALESCE(y1.crdt_amount, COALESCE(y2.crdt_amount, COALESCE(y3.crdt_amount, a.crdt_amount)))), 
        to_char(to_timestamp(COALESCE(y.trnsctn_date, COALESCE(y1.trnsctn_date, COALESCE(y2.trnsctn_date, COALESCE(y3.trnsctn_date, a.trnsctn_date)))),'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), 
        a.func_cur_id, a.batch_id, a.accnt_id, 
        COALESCE(y.net_amount, COALESCE(y1.net_amount, COALESCE(y2.net_amount, COALESCE(y3.net_amount, a.net_amount)))), 
        c.batch_name, a.trns_status, c.batch_source, " .
            " a.entered_amnt, gst.get_pssbl_val(a.entered_amt_crncy_id), a.entered_amt_crncy_id, " .
            "a.accnt_crncy_amnt, gst.get_pssbl_val(a.accnt_crncy_id), a.accnt_crncy_id, a.func_cur_exchng_rate, a.accnt_cur_exchng_rate, 
        (select d.batch_name from accb.accb_trnsctn_batches d where d.batch_id = a.batch_id) btch_nm, a.ref_doc_number,
        COALESCE(y.interface_id, COALESCE(y1.interface_id, COALESCE(y2.interface_id, COALESCE(y3.interface_id, -199)))) interface_id, 
        a.source_trns_ids  " .
            "FROM (accb.accb_trnsctn_details a "
            . "LEFT OUTER JOIN accb.accb_trnsctn_batches c ON a.batch_id = c.batch_id) " .
            "LEFT OUTER JOIN accb.accb_chart_of_accnts b on a.accnt_id = b.accnt_id  " .
            "LEFT OUTER JOIN mcf.mcf_gl_interface y on (a.accnt_id = y.accnt_id and y.gl_batch_id=a.batch_id and (c.batch_source ilike '%Banking%') and a.source_trns_ids like '%,' || y.interface_id || ',%') " .
            "LEFT OUTER JOIN vms.vms_gl_interface y1 on (a.accnt_id = y1.accnt_id and y1.gl_batch_id=a.batch_id and c.batch_source ilike '%Vault Management%' and a.source_trns_ids like '%,' || y1.interface_id || ',%') " .
            "LEFT OUTER JOIN pay.pay_gl_interface y2 on (a.accnt_id = y2.accnt_id and y2.gl_batch_id=a.batch_id and c.batch_source ilike '%Internal Payments%' and a.source_trns_ids like '%,' || y2.interface_id || ',%') " .
            "LEFT OUTER JOIN scm.scm_gl_interface y3 on (a.accnt_id = y3.accnt_id and y3.gl_batch_id=a.batch_id and c.batch_source ilike '%Inventory%' and a.source_trns_ids like '%,' || y3.interface_id || ',%') " .
            "WHERE((b.org_id = " . $orgID . ")" . $isposted1 .
            " and (to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . loc_db_escape_string($dte1) .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . loc_db_escape_string($dte2) . "','YYYY-MM-DD HH24:MI:SS'))" . $whereCls . $amntCls . ") " .
            $ordrCls . " LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    } else {
        if ($searchIn == "Account Number") {
            $whereCls = " AND (b.accnt_num ilike '" . loc_db_escape_string($searchWord) .
                "' or accb.get_accnt_num(b.control_account_id) ilike '" . loc_db_escape_string($searchWord) .
                "' or b.accnt_name ilike '" . loc_db_escape_string($searchWord) .
                "' or accb.get_accnt_name(b.control_account_id) ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Account Name") {
            $whereCls = " AND (b.accnt_num ilike '" . loc_db_escape_string($searchWord) .
                "' or accb.get_accnt_num(b.control_account_id) ilike '" . loc_db_escape_string($searchWord) .
                "' or b.accnt_name ilike '" . loc_db_escape_string($searchWord) .
                "' or accb.get_accnt_name(b.control_account_id) ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Transaction Description") {
            $whereCls = " AND (a.transaction_desc ilike '" . loc_db_escape_string($searchWord) .
                "' or a.ref_doc_number ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Transaction Date") {
            $whereCls = " AND (to_char(to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS'), " .
                "'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Batch Name") {
            $whereCls = " AND (c.batch_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
        } else if ($searchIn == "Transaction ID") {
            $whereCls = " AND (''||a.transctn_id = '" . loc_db_escape_string(str_replace("%", "", $searchWord)) .
                "')";
        } else {
            $whereCls = " AND (b.accnt_num ilike '" . loc_db_escape_string($searchWord) .
                "' or accb.get_accnt_num(b.control_account_id) ilike '" . loc_db_escape_string($searchWord) .
                "' or b.accnt_name ilike '" . loc_db_escape_string($searchWord) .
                "' or accb.get_accnt_name(b.control_account_id) ilike '" . loc_db_escape_string($searchWord) .
                "' or a.transaction_desc ilike '" . loc_db_escape_string($searchWord) .
                "' or a.ref_doc_number ilike '" . loc_db_escape_string($searchWord) .
                "' or to_char(to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS'), " .
                "'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "' or c.batch_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
        }

        $amntCls = "";
        if ($lowVal != 0 || $highVal != 0) {
            $amntCls = " and ((dbt_amount !=0 and dbt_amount between " . $lowVal . " and " . $highVal .
                ") or (crdt_amount !=0 and crdt_amount between " . $lowVal . " and " . $highVal . "))";
        }
        $strSql = "SELECT a.transctn_id, b.accnt_num, b.accnt_name, a.transaction_desc, a.dbt_amount, 
        a.crdt_amount, to_char(to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), 
        a.func_cur_id, a.batch_id, a.accnt_id, a.net_amount, c.batch_name, a.trns_status, c.batch_source, " .
            " a.entered_amnt, gst.get_pssbl_val(a.entered_amt_crncy_id), a.entered_amt_crncy_id, " .
            "a.accnt_crncy_amnt, gst.get_pssbl_val(a.accnt_crncy_id), a.accnt_crncy_id, a.func_cur_exchng_rate, a.accnt_cur_exchng_rate, 
        (select d.batch_name from accb.accb_trnsctn_batches d where d.batch_id = a.batch_id) btch_nm, a.ref_doc_number, 
        -199 interface_id, a.source_trns_ids " .
            "FROM (accb.accb_trnsctn_details a "
            . "LEFT OUTER JOIN accb.accb_trnsctn_batches c ON a.batch_id = c.batch_id) " .
            "LEFT OUTER JOIN accb.accb_chart_of_accnts b on a.accnt_id = b.accnt_id " .
            "WHERE((b.org_id = " . $orgID . ")" . $isposted1 .
            " and (to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . loc_db_escape_string($dte1) .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . loc_db_escape_string($dte2) . "','YYYY-MM-DD HH24:MI:SS'))" . $whereCls . $amntCls . ") " .
            $ordrCls . " LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    }
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_Transactions($searchWord, $searchIn, $orgID, $dte1, $dte2, $isposted, $shwIntrfc, $lowVal, $highVal)
{
    if ($dte1 != "") {
        $dte1 = cnvrtDMYTmToYMDTm($dte1);
    }
    if ($dte2 != "") {
        $dte2 = cnvrtDMYTmToYMDTm($dte2);
    }
    $strSql = "";
    $whereCls = "";
    $isposted1 = "";
    if ($isposted == true) {
        $isposted1 = " and (trns_status = '1')";
    }
    if ($shwIntrfc) {
        if ($searchIn == "Account Number") {
            $whereCls = " AND (b.accnt_num ilike '" . loc_db_escape_string($searchWord) .
                "' or accb.get_accnt_num(b.control_account_id) ilike '" . loc_db_escape_string($searchWord) .
                "' or b.accnt_name ilike '" . loc_db_escape_string($searchWord) .
                "' or accb.get_accnt_name(b.control_account_id) ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Account Name") {
            $whereCls = " AND (b.accnt_num ilike '" . loc_db_escape_string($searchWord) .
                "' or accb.get_accnt_num(b.control_account_id) ilike '" . loc_db_escape_string($searchWord) .
                "' or b.accnt_name ilike '" . loc_db_escape_string($searchWord) .
                "' or accb.get_accnt_name(b.control_account_id) ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Transaction Description") {
            $whereCls = " AND ((COALESCE(y.transaction_desc, COALESCE(y1.transaction_desc, COALESCE(y2.transaction_desc, COALESCE(y3.transaction_desc, a.transaction_desc))))) ilike '" . loc_db_escape_string($searchWord) .
                "' or a.ref_doc_number ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Transaction Date") {
            $whereCls = " AND (to_char(to_timestamp(COALESCE(y.trnsctn_date, COALESCE(y1.trnsctn_date, COALESCE(y2.trnsctn_date, COALESCE(y3.trnsctn_date, a.trnsctn_date)))),'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Batch Name") {
            $whereCls = " AND (c.batch_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
        } else if ($searchIn == "Transaction ID") {
            $whereCls = " AND (''||a.transctn_id = '" . loc_db_escape_string(str_replace("%", "", $searchWord)) .
                "')";
        } else {
            $whereCls = " AND (b.accnt_num ilike '" . loc_db_escape_string($searchWord) .
                "' or accb.get_accnt_num(b.control_account_id) ilike '" . loc_db_escape_string($searchWord) .
                "' or b.accnt_name ilike '" . loc_db_escape_string($searchWord) .
                "' or accb.get_accnt_name(b.control_account_id) ilike '" . loc_db_escape_string($searchWord) .
                "' or (COALESCE(y.transaction_desc, COALESCE(y1.transaction_desc, COALESCE(y2.transaction_desc, COALESCE(y3.transaction_desc, a.transaction_desc))))) ilike '" . loc_db_escape_string($searchWord) .
                "' or a.ref_doc_number ilike '" . loc_db_escape_string($searchWord) .
                "' or to_char(to_timestamp(COALESCE(y.trnsctn_date, COALESCE(y1.trnsctn_date, COALESCE(y2.trnsctn_date, COALESCE(y3.trnsctn_date, a.trnsctn_date)))),'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "' or c.batch_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
        }

        $amntCls = "";
        if ($lowVal != 0 || $highVal != 0) {
            $amntCls = " and ((COALESCE(y.dbt_amount, COALESCE(y1.dbt_amount, COALESCE(y2.dbt_amount, COALESCE(y3.dbt_amount, a.dbt_amount)))) !=0 "
                . "and COALESCE(y.dbt_amount, COALESCE(y1.dbt_amount, COALESCE(y2.dbt_amount, COALESCE(y3.dbt_amount, a.dbt_amount)))) between " . $lowVal . " and " . $highVal .
                ") or (COALESCE(y.crdt_amount, COALESCE(y1.crdt_amount, COALESCE(y2.crdt_amount, COALESCE(y3.crdt_amount, a.crdt_amount)))) !=0 "
                . "and COALESCE(y.crdt_amount, COALESCE(y1.crdt_amount, COALESCE(y2.crdt_amount, COALESCE(y3.crdt_amount, a.crdt_amount)))) between " . $lowVal . " and " . $highVal . "))";
        }
        $strSql = "SELECT count(1) " .
            "FROM (accb.accb_trnsctn_details a "
            . "LEFT OUTER JOIN accb.accb_trnsctn_batches c ON a.batch_id = c.batch_id) " .
            "LEFT OUTER JOIN accb.accb_chart_of_accnts b on a.accnt_id = b.accnt_id  " .
            "LEFT OUTER JOIN mcf.mcf_gl_interface y on (a.accnt_id = y.accnt_id and y.gl_batch_id=a.batch_id and (c.batch_source ilike '%Banking%') and a.source_trns_ids like '%,' || y.interface_id || ',%') " .
            "LEFT OUTER JOIN vms.vms_gl_interface y1 on (a.accnt_id = y1.accnt_id and y1.gl_batch_id=a.batch_id and c.batch_source ilike '%Vault Management%' and a.source_trns_ids like '%,' || y1.interface_id || ',%') " .
            "LEFT OUTER JOIN pay.pay_gl_interface y2 on (a.accnt_id = y2.accnt_id and y2.gl_batch_id=a.batch_id and c.batch_source ilike '%Internal Payments%' and a.source_trns_ids like '%,' || y2.interface_id || ',%') " .
            "LEFT OUTER JOIN scm.scm_gl_interface y3 on (a.accnt_id = y3.accnt_id and y3.gl_batch_id=a.batch_id and c.batch_source ilike '%Inventory%' and a.source_trns_ids like '%,' || y3.interface_id || ',%') " .
            "WHERE((b.org_id = " . $orgID . ")" . $isposted1 .
            " and (to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . loc_db_escape_string($dte1) .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . loc_db_escape_string($dte2) . "','YYYY-MM-DD HH24:MI:SS'))" . $whereCls . $amntCls . ") ";
    } else {
        if ($searchIn == "Account Number") {
            $whereCls = " AND (b.accnt_num ilike '" . loc_db_escape_string($searchWord) .
                "' or accb.get_accnt_num(b.control_account_id) ilike '" . loc_db_escape_string($searchWord) .
                "' or b.accnt_name ilike '" . loc_db_escape_string($searchWord) .
                "' or accb.get_accnt_name(b.control_account_id) ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Account Name") {
            $whereCls = " AND (b.accnt_num ilike '" . loc_db_escape_string($searchWord) .
                "' or accb.get_accnt_num(b.control_account_id) ilike '" . loc_db_escape_string($searchWord) .
                "' or b.accnt_name ilike '" . loc_db_escape_string($searchWord) .
                "' or accb.get_accnt_name(b.control_account_id) ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Transaction Description") {
            $whereCls = " AND (a.transaction_desc ilike '" . loc_db_escape_string($searchWord) .
                "' or a.ref_doc_number ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Transaction Date") {
            $whereCls = " AND (to_char(to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS'), " .
                "'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Batch Name") {
            $whereCls = " AND (c.batch_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
        } else if ($searchIn == "Transaction ID") {
            $whereCls = " AND (''||a.transctn_id = '" . loc_db_escape_string(str_replace("%", "", $searchWord)) .
                "')";
        } else {
            $whereCls = " AND (b.accnt_num ilike '" . loc_db_escape_string($searchWord) .
                "' or accb.get_accnt_num(b.control_account_id) ilike '" . loc_db_escape_string($searchWord) .
                "' or b.accnt_name ilike '" . loc_db_escape_string($searchWord) .
                "' or accb.get_accnt_name(b.control_account_id) ilike '" . loc_db_escape_string($searchWord) .
                "' or a.transaction_desc ilike '" . loc_db_escape_string($searchWord) .
                "' or a.ref_doc_number ilike '" . loc_db_escape_string($searchWord) .
                "' or to_char(to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS'), " .
                "'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "' or c.batch_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
        }

        $amntCls = "";
        if ($lowVal != 0 || $highVal != 0) {
            $amntCls = " and ((dbt_amount !=0 and dbt_amount between " . $lowVal . " and " . $highVal .
                ") or (crdt_amount !=0 and crdt_amount between " . $lowVal . " and " . $highVal . "))";
        }
        $strSql = "SELECT count(1) " .
            "FROM (accb.accb_trnsctn_details a LEFT OUTER JOIN accb.accb_trnsctn_batches c ON a.batch_id = c.batch_id) " .
            "LEFT OUTER JOIN accb.accb_chart_of_accnts b on a.accnt_id = b.accnt_id " .
            "WHERE((b.org_id = " . $orgID . ")" . $isposted1 .
            " and (to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . loc_db_escape_string($dte1) .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . loc_db_escape_string($dte2) . "','YYYY-MM-DD HH24:MI:SS'))" . $whereCls . $amntCls . ")";
    }
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_TbalsRpt($trnsID, $asAtDate)
{
    $strSql = "SELECT
tbl1.gnrl_data1::INTEGER rownumbr,
(CASE WHEN 'Yes' ilike 'Yes' THEN tbl1.gnrl_data14||tbl1.gnrl_data2
ELSE tbl1.gnrl_data2 END) account_number,
tbl1.gnrl_data3 accnt_name,
tbl1.gnrl_data4::NUMERIC debit_balance,
tbl1.gnrl_data5::NUMERIC credit_balance,
tbl1.gnrl_data6::NUMERIC net_balance,
tbl1.gnrl_data7 as_at_date,
tbl1.gnrl_data8::INTEGER accnt_id,
tbl1.gnrl_data9 is_prnt_accnt,
to_char(to_timestamp('" . $asAtDate . "','YYYY-MM-DD'),'DD-Mon-YYYY') p_as_at_date,
tbl1.gnrl_data10::NUMERIC debit_balsm,
tbl1.gnrl_data11::NUMERIC credit_balsm,
tbl1.gnrl_data12 depth,
tbl1.gnrl_data13 path,
tbl1.gnrl_data14 lftpaddng
FROM rpt.rpt_accb_data_storage tbl1
WHERE tbl1.accb_rpt_runid=" . $trnsID . " ORDER BY tbl1.gnrl_data1::INTEGER";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PnLRpt($trnsID, $asAtDate1, $asAtDate2)
{
    $strSql = "SELECT
tbl1.gnrl_data1::INTEGER rownumbr,
(CASE WHEN 'Yes' ilike 'Yes' THEN tbl1.gnrl_data12||tbl1.gnrl_data2
ELSE tbl1.gnrl_data2 END) account_number,
tbl1.gnrl_data3 accnt_name,
tbl1.gnrl_data4::NUMERIC trns_amnt,
tbl1.gnrl_data5::INTEGER accnt_id,
tbl1.gnrl_data6 is_prnt_accnt,
tbl1.gnrl_data7 has_sub_ledgers,
tbl1.gnrl_data8 accnt_type,
tbl1.gnrl_data9::NUMERIC trns_amntsm,
tbl1.gnrl_data10 depth,
tbl1.gnrl_data11 path,
tbl1.gnrl_data12 lftpaddng,
to_char(to_timestamp('" . $asAtDate1 . "','YYYY-MM-DD'),'DD-Mon-YYYY') P_FROM_DATE,
to_char(to_timestamp('" . $asAtDate2 . "','YYYY-MM-DD'),'DD-Mon-YYYY') P_TO_DATE,
(Select SUM(b.gnrl_data9::NUMERIC) FROM rpt.rpt_accb_data_storage b WHERE b.accb_rpt_runid=" . $trnsID . " and b.gnrl_data8='R') rvnu_sum,
(Select SUM(b.gnrl_data9::NUMERIC) FROM rpt.rpt_accb_data_storage b WHERE b.accb_rpt_runid=" . $trnsID . " and b.gnrl_data8='EX') expns_sum,
tbl1.gnrl_data13::NUMERIC dbt_amnt
FROM rpt.rpt_accb_data_storage tbl1
WHERE tbl1.accb_rpt_runid=" . $trnsID . " ORDER BY tbl1.gnrl_data1::INTEGER";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PeriodicRpt($trnsID, $asAtDate1, $asAtDate2)
{
    $strSql = "SELECT
tbl1.gnrl_data1::INTEGER rownumbr,
tbl1.gnrl_data10||tbl1.gnrl_data2 account_number,
tbl1.gnrl_data3 accnt_name,
tbl1.gnrl_data4::INTEGER accnt_id,
tbl1.gnrl_data5 is_prnt_accnt,
tbl1.gnrl_data6 has_sub_ledgers,
tbl1.gnrl_data7 accnt_type,
tbl1.gnrl_data8 depth,
tbl1.gnrl_data9 path,
tbl1.gnrl_data10 lftpaddng,
tbl1.gnrl_data11 openbals,
tbl1.gnrl_data12 period1,
tbl1.gnrl_data13 period2,
tbl1.gnrl_data14 period3,
tbl1.gnrl_data15 period4,
tbl1.gnrl_data16 period5,
tbl1.gnrl_data17 period6,
tbl1.gnrl_data18 period7,
tbl1.gnrl_data19 period8,
tbl1.gnrl_data20 period9,
tbl1.gnrl_data21 period10,
tbl1.gnrl_data22 period11,
tbl1.gnrl_data23 period12,
tbl1.gnrl_data24 period13,
tbl1.gnrl_data25 period14,
tbl1.gnrl_data26 period15,
tbl1.gnrl_data27 period16,
tbl1.gnrl_data28 period17,
tbl1.gnrl_data29 period18,
tbl1.gnrl_data30 period19,
tbl1.gnrl_data31 period20,
tbl1.gnrl_data32 period21,
tbl1.gnrl_data33 period22,
tbl1.gnrl_data34 period23,
tbl1.gnrl_data35 period24,
tbl1.gnrl_data36 period25,
tbl1.gnrl_data37 period26,
tbl1.gnrl_data38 period27,
tbl1.gnrl_data39 period28,
tbl1.gnrl_data40 period29,
tbl1.gnrl_data41 period30,
tbl1.gnrl_data42 period31,
tbl1.gnrl_data43 period32,
tbl1.gnrl_data44 period33,
tbl1.gnrl_data45 period34,
tbl1.gnrl_data46 period35,
tbl1.gnrl_data47 period36,
tbl1.gnrl_data48 period37,
tbl1.gnrl_data49 period38,
tbl1.gnrl_data50 period39,
to_char(to_timestamp('" . $asAtDate1 . "','YYYY-MM-DD'),'DD-Mon-YYYY') P_FROM_DATE,
to_char(to_timestamp('" . $asAtDate2 . "','YYYY-MM-DD'),'DD-Mon-YYYY') P_TO_DATE
FROM rpt.rpt_accb_data_storage tbl1
WHERE tbl1.gnrl_data1!='No.' and tbl1.accb_rpt_runid=" . $trnsID . " ORDER BY tbl1.gnrl_data1::INTEGER";
    //echo $strSql;
    //return;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PeriodicRptHdr($trnsID, $asAtDate1, $asAtDate2)
{
    $strSql = "SELECT
tbl1.gnrl_data1 rwnumber,
tbl1.gnrl_data2 account_number,
tbl1.gnrl_data3 accnt_name,
tbl1.gnrl_data4 accnt_id,
tbl1.gnrl_data5 is_prnt_accnt,
tbl1.gnrl_data6 has_sub_ledgers,
tbl1.gnrl_data7 accnt_type,
tbl1.gnrl_data8 depth,
tbl1.gnrl_data9 path,
tbl1.gnrl_data10 lftpaddng,
tbl1.gnrl_data11 openbals,
tbl1.gnrl_data12 period1,
tbl1.gnrl_data13 period2,
tbl1.gnrl_data14 period3,
tbl1.gnrl_data15 period4,
tbl1.gnrl_data16 period5,
tbl1.gnrl_data17 period6,
tbl1.gnrl_data18 period7,
tbl1.gnrl_data19 period8,
tbl1.gnrl_data20 period9,
tbl1.gnrl_data21 period10,
tbl1.gnrl_data22 period11,
tbl1.gnrl_data23 period12,
tbl1.gnrl_data24 period13,
tbl1.gnrl_data25 period14,
tbl1.gnrl_data26 period15,
tbl1.gnrl_data27 period16,
tbl1.gnrl_data28 period17,
tbl1.gnrl_data29 period18,
tbl1.gnrl_data30 period19,
tbl1.gnrl_data31 period20,
tbl1.gnrl_data32 period21,
tbl1.gnrl_data33 period22,
tbl1.gnrl_data34 period23,
tbl1.gnrl_data35 period24,
tbl1.gnrl_data36 period25,
tbl1.gnrl_data37 period26,
tbl1.gnrl_data38 period27,
tbl1.gnrl_data39 period28,
tbl1.gnrl_data40 period29,
tbl1.gnrl_data41 period30,
tbl1.gnrl_data42 period31,
tbl1.gnrl_data43 period32,
tbl1.gnrl_data44 period33,
tbl1.gnrl_data45 period34,
tbl1.gnrl_data46 period35,
tbl1.gnrl_data47 period36,
tbl1.gnrl_data48 period37,
tbl1.gnrl_data49 period38,
tbl1.gnrl_data50 period39,
to_char(to_timestamp('" . $asAtDate1 . "','YYYY-MM-DD'),'DD-Mon-YYYY') P_FROM_DATE,
to_char(to_timestamp('" . $asAtDate2 . "','YYYY-MM-DD'),'DD-Mon-YYYY') P_TO_DATE
FROM rpt.rpt_accb_data_storage tbl1
WHERE tbl1.gnrl_data1='No.' and tbl1.accb_rpt_runid=" . $trnsID . "";
    //echo $strSql;
    //return;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_CshFlwRpt($trnsID, $asAtDate1, $asAtDate2)
{
    $strSql = "SELECT
tbl1.gnrl_data1::INTEGER rownumbr,
tbl1.gnrl_data10||tbl1.gnrl_data2 account_number,
tbl1.gnrl_data3 accnt_name,
tbl1.gnrl_data4::INTEGER accnt_id,
tbl1.gnrl_data5 is_prnt_accnt,
tbl1.gnrl_data6 has_sub_ledgers,
tbl1.gnrl_data7 accnt_type,
tbl1.gnrl_data8 depth,
tbl1.gnrl_data9 path,
tbl1.gnrl_data10 lftpaddng,
tbl1.gnrl_data11 period1,
tbl1.gnrl_data12 period2,
tbl1.gnrl_data13 period3,
tbl1.gnrl_data14 period4,
tbl1.gnrl_data15 period5,
tbl1.gnrl_data16 period6,
tbl1.gnrl_data17 period7,
tbl1.gnrl_data18 period8,
tbl1.gnrl_data19 period9,
tbl1.gnrl_data20 period10,
tbl1.gnrl_data21 period11,
tbl1.gnrl_data22 period12,
tbl1.gnrl_data23 period13,
tbl1.gnrl_data24 period14,
tbl1.gnrl_data25 period15,
tbl1.gnrl_data26 period16,
tbl1.gnrl_data27 period17,
tbl1.gnrl_data28 period18,
tbl1.gnrl_data29 period19,
tbl1.gnrl_data30 period20,
tbl1.gnrl_data31 period21,
tbl1.gnrl_data32 period22,
tbl1.gnrl_data33 period23,
tbl1.gnrl_data34 period24,
tbl1.gnrl_data35 period25,
tbl1.gnrl_data36 period26,
tbl1.gnrl_data37 period27,
tbl1.gnrl_data38 period28,
tbl1.gnrl_data39 period29,
tbl1.gnrl_data40 period30,
tbl1.gnrl_data41 period31,
tbl1.gnrl_data42 period32,
tbl1.gnrl_data43 period33,
tbl1.gnrl_data44 period34,
tbl1.gnrl_data45 period35,
tbl1.gnrl_data46 period36,
tbl1.gnrl_data47 period37,
tbl1.gnrl_data48 period38,
tbl1.gnrl_data49 period39,
tbl1.gnrl_data50 period40,
to_char(to_timestamp('" . $asAtDate1 . "','YYYY-MM-DD'),'DD-Mon-YYYY') P_FROM_DATE,
to_char(to_timestamp('" . $asAtDate2 . "','YYYY-MM-DD'),'DD-Mon-YYYY') P_TO_DATE
FROM rpt.rpt_accb_data_storage tbl1
WHERE tbl1.gnrl_data1!='No.' and tbl1.accb_rpt_runid=" . $trnsID . " ORDER BY tbl1.gnrl_data1::INTEGER";
    //echo $strSql;
    //return;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_CshFlwRptHdr($trnsID, $asAtDate1, $asAtDate2)
{
    $strSql = "SELECT
tbl1.gnrl_data1 rwnumber,
tbl1.gnrl_data2 account_number,
tbl1.gnrl_data3 accnt_name,
tbl1.gnrl_data4 accnt_id,
tbl1.gnrl_data5 is_prnt_accnt,
tbl1.gnrl_data6 has_sub_ledgers,
tbl1.gnrl_data7 accnt_type,
tbl1.gnrl_data8 depth,
tbl1.gnrl_data9 path,
tbl1.gnrl_data10 lftpaddng,
tbl1.gnrl_data11 period1,
tbl1.gnrl_data12 period2,
tbl1.gnrl_data13 period3,
tbl1.gnrl_data14 period4,
tbl1.gnrl_data15 period5,
tbl1.gnrl_data16 period6,
tbl1.gnrl_data17 period7,
tbl1.gnrl_data18 period8,
tbl1.gnrl_data19 period9,
tbl1.gnrl_data20 period10,
tbl1.gnrl_data21 period11,
tbl1.gnrl_data22 period12,
tbl1.gnrl_data23 period13,
tbl1.gnrl_data24 period14,
tbl1.gnrl_data25 period15,
tbl1.gnrl_data26 period16,
tbl1.gnrl_data27 period17,
tbl1.gnrl_data28 period18,
tbl1.gnrl_data29 period19,
tbl1.gnrl_data30 period20,
tbl1.gnrl_data31 period21,
tbl1.gnrl_data32 period22,
tbl1.gnrl_data33 period23,
tbl1.gnrl_data34 period24,
tbl1.gnrl_data35 period25,
tbl1.gnrl_data36 period26,
tbl1.gnrl_data37 period27,
tbl1.gnrl_data38 period28,
tbl1.gnrl_data39 period29,
tbl1.gnrl_data40 period30,
tbl1.gnrl_data41 period31,
tbl1.gnrl_data42 period32,
tbl1.gnrl_data43 period33,
tbl1.gnrl_data44 period34,
tbl1.gnrl_data45 period35,
tbl1.gnrl_data46 period36,
tbl1.gnrl_data47 period37,
tbl1.gnrl_data48 period38,
tbl1.gnrl_data49 period39,
tbl1.gnrl_data50 period40,
to_char(to_timestamp('" . $asAtDate1 . "','YYYY-MM-DD'),'DD-Mon-YYYY') P_FROM_DATE,
to_char(to_timestamp('" . $asAtDate2 . "','YYYY-MM-DD'),'DD-Mon-YYYY') P_TO_DATE
FROM rpt.rpt_accb_data_storage tbl1
WHERE tbl1.gnrl_data1='No.' and tbl1.accb_rpt_runid=" . $trnsID . "";
    //echo $strSql;
    //return;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_BlShtRpt($trnsID, $asAtDate)
{
    $strSql = "SELECT
tbl1.gnrl_data1::INTEGER rownumbr,
(CASE WHEN 'Yes' ilike 'Yes' THEN tbl1.gnrl_data12||tbl1.gnrl_data2
ELSE tbl1.gnrl_data2 END) account_number,
tbl1.gnrl_data3 accnt_name,
tbl1.gnrl_data4::NUMERIC trns_amnt,
tbl1.gnrl_data5::INTEGER accnt_id,
tbl1.gnrl_data6 is_prnt_accnt,
tbl1.gnrl_data7 has_sub_ledgers,
tbl1.gnrl_data8 accnt_type,
tbl1.gnrl_data9::NUMERIC trns_amntsm,
tbl1.gnrl_data10 depth,
tbl1.gnrl_data11 path,
tbl1.gnrl_data12 lftpaddng,
to_char(to_timestamp('" . $asAtDate . "','YYYY-MM-DD'),'DD-Mon-YYYY') p_as_at_date,
(Select SUM(b.gnrl_data9::NUMERIC) FROM rpt.rpt_accb_data_storage b WHERE b.accb_rpt_runid=" . $trnsID . " and b.gnrl_data8='A') asset_sum,
(Select SUM(b.gnrl_data9::NUMERIC) FROM rpt.rpt_accb_data_storage b WHERE b.accb_rpt_runid=" . $trnsID . " and b.gnrl_data8='EQ') equity_sum,
(Select SUM(b.gnrl_data9::NUMERIC) FROM rpt.rpt_accb_data_storage b WHERE b.accb_rpt_runid=" . $trnsID . " and b.gnrl_data8='L') lblty_sum,
tbl1.gnrl_data13::NUMERIC crdt_amnt
FROM rpt.rpt_accb_data_storage tbl1 
WHERE tbl1.accb_rpt_runid=" . $trnsID . " ORDER BY tbl1.gnrl_data1::INTEGER";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_GLStmntRpt($trnsID, $asAtDate1, $asAtDate2)
{
    /**(CASE WHEN tbl1.gnrl_data5 !='' THEN 'Ref. No.:'||tbl1.gnrl_data5 ELSE '' END) */
    $strSql = "SELECT
tbl1.gnrl_data1::INTEGER rownumbr,
tbl1.gnrl_data2 account_number,
tbl1.gnrl_data3 accnt_name,
tbl1.gnrl_data4 transaction_desc,
tbl1.gnrl_data5 ref_doc_number,
tbl1.gnrl_data6::NUMERIC dbt_amount,
tbl1.gnrl_data7::NUMERIC crdt_amount,
tbl1.gnrl_data8::NUMERIC net_amount,
tbl1.gnrl_data13::NUMERIC opng_dbt_amount,
tbl1.gnrl_data14::NUMERIC opng_crdt_amount,
tbl1.gnrl_data15::NUMERIC opng_net_amount,
tbl1.gnrl_data16::NUMERIC clsng_dbt_amount,
tbl1.gnrl_data17::NUMERIC clsng_crdt_amount,
tbl1.gnrl_data18::NUMERIC clsng_net_amount,
tbl1.gnrl_data12 trnsctn_date,
to_char(to_timestamp('" . $asAtDate1 . "','YYYY-MM-DD'),'DD-Mon-YYYY') P_FROM_DATE,
to_char(to_timestamp('" . $asAtDate2 . "','YYYY-MM-DD'),'DD-Mon-YYYY') P_TO_DATE, 
(SELECT SUM(b.gnrl_data8::NUMERIC) FROM rpt.rpt_accb_data_storage b
WHERE b.gnrl_data1::INTEGER <= tbl1.gnrl_data1::INTEGER
AND b.accb_rpt_runid=tbl1.accb_rpt_runid) rnng_bals,
tbl1.gnrl_data11::NUMERIC trnsctn_line,
tbl1.gnrl_data10::INTEGER accnt_id,
tbl1.gnrl_data19 is_reconciled,
tbl1.gnrl_data20 batch_vldty_status,
tbl1.gnrl_data21::INTEGER src_batch_id
FROM rpt.rpt_accb_data_storage tbl1 
WHERE tbl1.accb_rpt_runid=" . $trnsID . " ORDER BY tbl1.gnrl_data1::INTEGER";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_CshBkStmntRpt($trnsID, $asAtDate1, $asAtDate2)
{
    $strSql = "SELECT
tbl1.gnrl_data1::INTEGER rownumbr,
tbl1.gnrl_data2 account_number,
tbl1.gnrl_data3 accnt_name,
tbl1.gnrl_data4 || (CASE WHEN tbl1.gnrl_data5 !='' THEN 'Ref. No.:'||tbl1.gnrl_data5 ELSE '' END) transaction_desc,
tbl1.gnrl_data5 ref_doc_number,
tbl1.gnrl_data6::NUMERIC dbt_amount,
tbl1.gnrl_data7::NUMERIC crdt_amount,
tbl1.gnrl_data8::NUMERIC net_amount,
tbl1.gnrl_data13::NUMERIC opng_dbt_amount,
tbl1.gnrl_data14::NUMERIC opng_crdt_amount,
tbl1.gnrl_data15::NUMERIC opng_net_amount,
tbl1.gnrl_data16::NUMERIC clsng_dbt_amount,
tbl1.gnrl_data17::NUMERIC clsng_crdt_amount,
tbl1.gnrl_data18::NUMERIC clsng_net_amount,
tbl1.gnrl_data12 trnsctn_date,
to_char(to_timestamp('" . $asAtDate1 . "','YYYY-MM-DD'),'DD-Mon-YYYY') P_FROM_DATE,
to_char(to_timestamp('" . $asAtDate2 . "','YYYY-MM-DD'),'DD-Mon-YYYY') P_TO_DATE, 
(SELECT SUM(b.gnrl_data8::NUMERIC) FROM rpt.rpt_accb_data_storage b
WHERE b.gnrl_data1::INTEGER <= tbl1.gnrl_data1::INTEGER
AND b.accb_rpt_runid=tbl1.accb_rpt_runid AND (b.gnrl_data10::INTEGER IN (-1) OR b.gnrl_data10::INTEGER IN (SELECT z.account_id
                       FROM accb.accb_account_clsfctns z
                       WHERE z.maj_rpt_ctgry in ('Cash Balance')))) rnng_bals,
tbl1.gnrl_data11::NUMERIC trnsctn_line, 
(SELECT SUM((CASE WHEN b.gnrl_data10::INTEGER=-1 THEN b.gnrl_data19::NUMERIC ELSE b.gnrl_data8::NUMERIC END)) FROM rpt.rpt_accb_data_storage b
WHERE b.gnrl_data1::INTEGER <= tbl1.gnrl_data1::INTEGER
AND b.accb_rpt_runid=tbl1.accb_rpt_runid AND (b.gnrl_data10::INTEGER IN (-1) OR b.gnrl_data10::INTEGER IN (SELECT z.account_id
                       FROM accb.accb_account_clsfctns z
                       WHERE z.maj_rpt_ctgry in ('Bank Balance')))) rnng_bals2,
tbl1.gnrl_data19::NUMERIC opng_net_bank,
tbl1.gnrl_data20::NUMERIC clsng_net_bank
FROM rpt.rpt_accb_data_storage tbl1 
WHERE tbl1.accb_rpt_runid=" . $trnsID . " ORDER BY tbl1.gnrl_data1::INTEGER";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_ImprtdBnkStmntRpt($accntID, $asAtDate1, $asAtDate2)
{
    /**row_number() over(ORDER BY a.cust_sup_site_id) as row */
    $strSql = "SELECT
                imprtd_rec_pos_cntr rownumbr,
                accb.get_accnt_num(a.account_id) account_number,
                accb.get_accnt_name(a.account_id) accnt_name,
                a.reconcile_desc transaction_desc,
                a.ref_doc_number ref_doc_number,
                a.debit_amount dbt_amount,
                a.credit_amount crdt_amount,
                a.net_amount net_amount,
                a.opng_dbt_amount,
                a.opng_crdt_amount,
                a.opng_net_amount,
                a.clsng_dbt_amount,
                a.clsng_crdt_amount,
                a.clsng_net_amount,
                to_char(to_timestamp(a.bnk_trans_date,'YYYY-MM-DD'),'DD-Mon-YYYY') trnsctn_date,
                to_char(to_timestamp('" . $asAtDate1 . "','YYYY-MM-DD'),'DD-Mon-YYYY') P_FROM_DATE,
                to_char(to_timestamp('" . $asAtDate2 . "','YYYY-MM-DD'),'DD-Mon-YYYY') P_TO_DATE, 
                ((SELECT SUM(b.net_amount) FROM accb.accb_trans_to_reconcile b
                WHERE b.imprtd_rec_pos_cntr <= a.imprtd_rec_pos_cntr
                AND b.import_hdr_runid=a.import_hdr_runid)+a.opng_net_amount) rnng_bals,
                a.reconcile_line_id trnsctn_line,
                a.account_id accnt_id,
                a.is_reconciled is_reconciled,
                'VALID' batch_vldty_status,
                -1 src_batch_id,
                to_char(to_timestamp(a.value_date,'YYYY-MM-DD'),'DD-Mon-YYYY') value_date,
                a.import_hdr_runid
                FROM accb.accb_trans_to_reconcile a 
                WHERE a.import_hdr_runid = (Select MAX(c.import_hdr_runid) 
                                    FROM accb.accb_trans_to_reconcile c 
                                    WHERE c.account_id=" . $accntID .
        " and c.reconcile_strt_date>='" . $asAtDate1 .
        "' and c.reconcile_end_date<='" . $asAtDate2 . "') 
                ORDER BY a.imprtd_rec_pos_cntr";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_MxImprtdBnkStmntID($accntID, $asAtDate1, $asAtDate2)
{
    global $orgID;
    if ($asAtDate1 != "") {
        $asAtDate1 = cnvrtDMYToYMD($asAtDate1);
    }
    if ($asAtDate2 != "") {
        $asAtDate2 = cnvrtDMYToYMD($asAtDate2);
    }
    $strSql = "Select MAX(c.import_hdr_runid) 
                FROM accb.accb_trans_to_reconcile c 
                WHERE c.account_id=" . $accntID ." and c.org_id=".$orgID.
        " and (('" . $asAtDate1 .
        "' >=c.reconcile_strt_date and '" . $asAtDate1 .
        "' <= c.reconcile_end_date) or ('" . $asAtDate2 .
        "' >= c.reconcile_strt_date and '" . $asAtDate2 . "' <= c.reconcile_end_date))";
        logSessionErrs($strSql);
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function get_ImprtdBnkTransID($rcnclHdrID, $imprtTrnsDate, $imprtReference, $imprtRemarks)
{
    if ($imprtTrnsDate != "") {
        $imprtTrnsDate = cnvrtDMYToYMD($imprtTrnsDate);
    }
    $strSql = "Select MAX(c.reconcile_line_id) 
                FROM accb.accb_trans_to_reconcile c 
                WHERE c.import_hdr_runid=" . $rcnclHdrID .
        " and c.bnk_trans_date>='" . loc_db_escape_string($imprtTrnsDate) .
        "' and c.ref_doc_number = '" . loc_db_escape_string($imprtReference) . "'
                and c.reconcile_desc = '" . loc_db_escape_string($imprtRemarks) . "'";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function get_MaxBnkStmntHdrID()
{
    global $orgID;
    $strSql = "Select MAX(c.import_hdr_runid) 
                FROM accb.accb_trans_to_reconcile c WHERE org_id=" . $orgID;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function createBnkStmntTrans(
    $accntid,
    $strtDte,
    $enddte,
    $rcnclHdrID,
    $imprtTrnsDate,
    $imprtValueDate,
    $imprtReference,
    $imprtDebits,
    $imprtCredits,
    $imprtRunBals,
    $imprtRemarks,
    $imprtRecCntr,
    $opngDebits,
    $opngCredits,
    $clsngDebits,
    $clsngCredits
) {
    global $usrID;
    global $orgID;
    if ($strtDte != "") {
        $strtDte = cnvrtDMYToYMD($strtDte);
    }
    if ($enddte == "") {
        $enddte = "31-Dec-4000 23:59:59";
    }
    if ($enddte != "") {
        $enddte = cnvrtDMYToYMD($enddte);
    }
    if ($imprtTrnsDate != "") {
        $imprtTrnsDate = cnvrtDMYToYMD($imprtTrnsDate);
    }
    if ($imprtValueDate != "") {
        $imprtValueDate = cnvrtDMYToYMD($imprtValueDate);
    }

    $insSQL = "INSERT INTO accb.accb_trans_to_reconcile(
        bnk_trans_date, value_date, reconcile_desc, ref_doc_number, account_id, 
        imprtd_rec_pos_cntr, import_hdr_runid, reconcile_strt_date, reconcile_end_date, debit_amount, 
        credit_amount, net_amount, bals_afta_trans, opng_dbt_amount, opng_crdt_amount, opng_net_amount, 
        clsng_dbt_amount, clsng_crdt_amount, clsng_net_amount, 
        is_reconciled, lnkd_sys_trans_id, org_id, created_by, creation_date, last_update_by, last_update_date) " .
        "VALUES ('" . loc_db_escape_string($imprtTrnsDate) .
        "', '" . loc_db_escape_string($imprtValueDate) .
        "', '" . loc_db_escape_string($imprtRemarks) .
        "', '" . loc_db_escape_string($imprtReference) .
        "', " . $accntid .
        ", " . $imprtRecCntr .
        ", " . $rcnclHdrID .
        ", '" . loc_db_escape_string($strtDte) .
        "', '" . loc_db_escape_string($enddte) .
        "', " . $imprtDebits .
        ", " . $imprtCredits .
        ", " . ($imprtCredits - $imprtDebits) .
        ", " . $imprtRunBals .
        ", " . $opngDebits .
        ", " . $opngCredits .
        ", " . ($opngCredits - $opngDebits) .
        ", " . $clsngDebits .
        ", " . $clsngCredits .
        ", " . ($clsngCredits + $clsngDebits) .
        ", '0', -1, " . $orgID . ", " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'))";
    return execUpdtInsSQL($insSQL);
}

function changeImprtdReconciledStatus($trnsID, $nwStatus)
{
    if ($trnsID <= 0) {
        return;
    }
    $updtSQL = "UPDATE accb.accb_trans_to_reconcile SET is_reconciled = '" .
        loc_db_escape_string($nwStatus) . "' WHERE reconcile_line_id=" . $trnsID;
    return execUpdtInsSQL($updtSQL);
}

function delBnkStmntTrans(
    $rcnclHdrID, $docNum
) {
    global $orgID;
    $delSQL = "DELETE FROM accb.accb_trans_to_reconcile WHERE (import_hdr_runid = " . $rcnclHdrID . " or import_hdr_runid<=0) and org_id=" . $orgID;
    $affctd1 = execUpdtInsSQL($delSQL, "Desc:" . $docNum);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Imported Statement(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_Trns_AmntBrkdwn($trnsID, $lovID, $mode)
{
    $whrcls = "";
    if ($mode == "VIEW") {
        $whrcls = " and tbl1.trns_amnt_det_id>0";
    }
    $strSql = "  SELECT   *
    FROM   (SELECT   a.trns_amnt_det_id,
                     a.description,
                     a.quantity,
                     a.unit_amnt,
                     a.ttl_amnt,
                     a.lnkd_pssbl_val_id
              FROM   accb.accb_trnsctn_amnt_breakdown a
             WHERE   ((a.transaction_id = " . $trnsID . " and a.trnsctn_smmry_id<=0 and a.transaction_id>0)"
        . " or (a.trnsctn_smmry_id=" . $trnsID . " and a.transaction_id<=0 and a.trnsctn_smmry_id>0))
            UNION
            SELECT   -1 trns_amnt_det_id,
                     b.pssbl_value,
                     0,
                     public.chartodouble(b.pssbl_value_desc) pssbl_value_desc,
                     0,
                     b.pssbl_value_id
              FROM   gst.gen_stp_lov_values b
             WHERE   b.value_list_id = " . $lovID . "
                     AND b.is_enabled='1'
                     AND b.pssbl_value_id NOT IN
                              (SELECT   c.lnkd_pssbl_val_id
                                 FROM   accb.accb_trnsctn_amnt_breakdown c
                                WHERE   ((c.transaction_id = " . $trnsID . " and c.trnsctn_smmry_id<=0) or (c.trnsctn_smmry_id=" . $trnsID . " and c.transaction_id<=0)))) tbl1
        WHERE 1=1" . $whrcls . " ORDER BY   6,1";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function deleteTrns_AmntBrkdwn($brkdwnDetid, $brkdwnDesc)
{
    $strSql = "SELECT count(1) FROM accb.accb_trnsctn_details a WHERE(a.transctn_id "
        . "IN (Select b.transaction_id from accb.accb_trnsctn_amnt_breakdown b where b.trns_amnt_det_id = " . $brkdwnDetid . ") and a.trns_status='1')";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete Posted Amount Breakdowns!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM accb.accb_trnsctn_amnt_breakdown WHERE (trns_amnt_det_id = " . $brkdwnDetid . ")";
    $affctd1 = execUpdtInsSQL($delSQL, "Desc:" . $brkdwnDesc);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Transaction Amount Breakdown(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getTrnsID($trsDesc, $accntID, $entrdAmnt, $entrdCurID, $trnsDate)
{
    $strSql = "Select transctn_id from accb.accb_trnsctn_details
   where accnt_id=" . $accntID . " and transaction_desc='" . loc_db_escape_string($trsDesc) .
        "' and entered_amnt =" . $entrdAmnt . " and " .
        "entered_amt_crncy_id=" . $entrdCurID . " and trnsctn_date = '" . $trnsDate . "'";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function getTrnsIDInBatch($srcMdlNm, $srcMdlLnID, $batchID, $accntID, $dbtcrdt)
{
    $strSql = "Select transctn_id from accb.accb_trnsctn_details
   where source_mdl_det_line_id=" . $srcMdlLnID . " and source_mdl_name='" . loc_db_escape_string($srcMdlNm) .
        "' and batch_id=" . $batchID . " and accnt_id=" . $accntID . " and dbt_or_crdt='" . loc_db_escape_string($dbtcrdt) . "'";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function getTrnsIDUsgSmry($accntID, $smmryID)
{
    $strSql = "Select transctn_id from accb.accb_trnsctn_details
   where accnt_id=" . $accntID . " and trnsctn_smmry_id = " . $smmryID . " and trnsctn_smmry_id>0";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function getTrnsIDUsgSmryDC($accntID, $dbtOrCrdt1, $smmryID)
{
    $strSql = "Select transctn_id from accb.accb_trnsctn_details
   where accnt_id=" . $accntID . " and trnsctn_smmry_id = " . $smmryID . " and trnsctn_smmry_id>0 and dbt_or_crdt='" . loc_db_escape_string($dbtOrCrdt1) . "'";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function deleteTrnsIDUsgSmry($smmryID)
{
    $delSql = "DELETE
                from accb.accb_trnsctn_details z
                where z.transctn_id IN (Select a.transctn_id
                                        from accb.accb_trnsctn_details a
                                        where a.trnsctn_smmry_id > 0
                                          and a.trnsctn_smmry_id = " . $smmryID . "
                                          and a.accnt_id || a.dbt_or_crdt not in
                                              (select y.acnt_id1 ||
                                                      substring(accb.dbt_or_crdt_accnt(y.acnt_id1, substring(y.incrs_dcrs1, 1, 1)), 1,
                                                                1)
                                               from accb.accb_trnsctn_smmrys y
                                               where y.trnsctn_smmry_id = a.trnsctn_smmry_id)
                                          and a.accnt_id || a.dbt_or_crdt not in
                                              (select y.acnt_id2 ||
                                                      substring(accb.dbt_or_crdt_accnt(y.acnt_id2, substring(y.incrs_dcrs2, 1, 1)), 1,
                                                                1)
                                               from accb.accb_trnsctn_smmrys y
                                               where y.trnsctn_smmry_id = a.trnsctn_smmry_id))";

    return execUpdtInsSQL($delSql);
}

function getTrnsIDSmmryID($trsDesc, $accntID, $entrdAmnt, $entrdCurID, $trnsDate)
{
    $strSql = "Select trnsctn_smmry_id from accb.accb_trnsctn_details
   where accnt_id=" . $accntID . " and transaction_desc='" . loc_db_escape_string($trsDesc) .
        "' and entered_amnt =" . $entrdAmnt . " and " .
        "entered_amt_crncy_id=" . $entrdCurID . " and trnsctn_date = '" . $trnsDate . "'";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function getTrnsSmmryID($trsDesc, $accntID1, $accntID2, $entrdAmnt, $entrdCurID, $trnsDate)
{
    $strSql = "Select trnsctn_smmry_id from accb.accb_trnsctn_smmrys
   where acnt_id1=" . $accntID1 . " and acnt_id2=" . $accntID2 .
        " and trnsctn_smmry_desc='" . loc_db_escape_string($trsDesc) .
        "' and trnsctn_smmry_amnt =" . $entrdAmnt . " and " .
        "entrd_curr_id=" . $entrdCurID . " and trnsctn_date = '" . $trnsDate . "'";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function getNewTrnsSmryLnID()
{
    $strSql = "select nextval('accb.accb_trnsctn_smmrys_trnsctn_smmry_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function createTrnsSmryLn(
    $trnsID,
    $batchID,
    $refDocNum,
    $trnsDesc,
    $incrsDcrs1,
    $acctID1,
    $incrsDcrs2,
    $acctID2,
    $entrdAmnt,
    $trnsDate,
    $entrdCrID,
    $funcCurRate
) {
    global $usrID;
    if (strlen($trnsDesc) > 499) {
        $trnsDesc = substr($trnsDesc, 0, 499);
    }
    if ($trnsDate != "") {
        $trnsDate = cnvrtDMYTmToYMDTm($trnsDate);
    }
    $insSQL = "INSERT INTO accb.accb_trnsctn_smmrys(
	trnsctn_smmry_id, batch_id, ref_doc_number, trnsctn_smmry_desc, trnsctn_smmry_amnt, 
	trnsctn_date, incrs_dcrs1, acnt_id1, incrs_dcrs2, acnt_id2, entrd_curr_id, func_cur_exchng_rate, 
	created_by, creation_date, last_update_by, last_update_date) " .
        "VALUES (" . $trnsID . ", " . $batchID .
        ", '" . loc_db_escape_string($refDocNum) .
        "', '" . loc_db_escape_string($trnsDesc) .
        "', " . $entrdAmnt .
        ", '" . loc_db_escape_string($trnsDate) .
        "', '" . loc_db_escape_string($incrsDcrs1) .
        "', " . $acctID1 .
        ", '" . loc_db_escape_string($incrsDcrs2) .
        "', " . $acctID2 . ", " . $entrdCrID . ", " . $funcCurRate . ", " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'))";
    return execUpdtInsSQL($insSQL);
}

function updateTrnsSmryLn(
    $trnsID,
    $batchID,
    $refDocNum,
    $trnsDesc,
    $incrsDcrs1,
    $acctID1,
    $incrsDcrs2,
    $acctID2,
    $entrdAmnt,
    $trnsDate,
    $entrdCrID,
    $funcCurRate
) {
    global $usrID;
    if (strlen($trnsDesc) > 499) {
        $trnsDesc = substr($trnsDesc, 0, 499);
    }
    if ($trnsDate != "") {
        $trnsDate = cnvrtDMYTmToYMDTm($trnsDate);
    }
    $insSQL = "UPDATE accb.accb_trnsctn_smmrys SET 
        batch_id=" . $batchID . ", 
        ref_doc_number='" . loc_db_escape_string($refDocNum) . "', 
        trnsctn_smmry_desc='" . loc_db_escape_string($trnsDesc) . "', 
        trnsctn_smmry_amnt=" . $entrdAmnt . ", 
        trnsctn_date='" . loc_db_escape_string($trnsDate) . "', 
        incrs_dcrs1='" . loc_db_escape_string($incrsDcrs1) . "', 
        acnt_id1=" . $acctID1 . ", 
        incrs_dcrs2='" . loc_db_escape_string($incrsDcrs2) . "', 
        acnt_id2=" . $acctID2 . ", 
        entrd_curr_id=" . $entrdCrID . ", 
        func_cur_exchng_rate=" . $funcCurRate . ", 
        last_update_by=" . $usrID . ", 
        last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS') 
	WHERE (trnsctn_smmry_id= " . $trnsID . ")";
    return execUpdtInsSQL($insSQL);
}

function getNewTrnsID()
{
    $strSql = "select nextval('accb.accb_trnsctn_details_transctn_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return round((float) $row[0]);
    }
    return -1;
}

function createTransaction(
    $trnsID,
    $accntid,
    $trnsDesc,
    $dbtAmnt,
    $trnsDate,
    $crncyid,
    $batchid,
    $crdtamnt,
    $netAmnt,
    $entrdAmt,
    $entrdCurrID,
    $acntAmnt,
    $acntCurrID,
    $funcExchRate,
    $acntExchRate,
    $dbtOrCrdt,
    $refDocNum,
    $srcTrnsID1,
    $srcTrnsID2,
    $trnsSmmryID,
    $source_mdl_name,
    $source_mdl_ln_id
) {
    global $usrID;
    if ($trnsDate != "") {
        $trnsDate = cnvrtDMYTmToYMDTm($trnsDate);
    }
    if (strlen($trnsDesc) > 500) {
        $trnsDesc = substr($trnsDesc, 0, 500);
    }
    /* if (getTrnsID($trnsDesc, $accntid, $entrdAmt, $entrdCurrID, $trnsDate) > 0) {
      $errMsg = "Same Transaction has been created Already!<br/>Consider changing the Date or Time and Try Again!";
      return 0;
      } */
    if (is_numeric($refDocNum)) {
        $refDocNum = "Ref.:" . $refDocNum;
    }
    $insSQL = "INSERT INTO accb.accb_trnsctn_details(" .
        "transctn_id, accnt_id, transaction_desc, dbt_amount, trnsctn_date, " .
        "func_cur_id, created_by, creation_date, batch_id, crdt_amount, " .
        "last_update_by, last_update_date, net_amount, 
            entered_amnt, entered_amt_crncy_id, accnt_crncy_amnt, accnt_crncy_id, 
            func_cur_exchng_rate, accnt_cur_exchng_rate, dbt_or_crdt, ref_doc_number, 
            src_trns_id_reconciled, src_trns_id_being_blncd, trnsctn_smmry_id,
            source_mdl_name, source_mdl_det_line_id) " .
        "VALUES (" . $trnsID . "," . $accntid . ", '" . loc_db_escape_string($trnsDesc) . "', " . $dbtAmnt .
        ", '" . $trnsDate . "', " . $crncyid . ", " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $batchid . ", " . $crdtamnt . ", " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS')," . $netAmnt . ", " . $entrdAmt .
        ", " . $entrdCurrID . ", " . $acntAmnt .
        ", " . $acntCurrID . ", " . $funcExchRate .
        ", " . $acntExchRate . ", '" . $dbtOrCrdt . "', '" .
        loc_db_escape_string($refDocNum) . "', " . $srcTrnsID1 . ", " . $srcTrnsID2 . ", " . $trnsSmmryID .
        ", '" . loc_db_escape_string($source_mdl_name) . "', " . $source_mdl_ln_id . ")";
    $resCnt = execUpdtInsSQL($insSQL);
    if ($srcTrnsID1 > 0) {
        changeReconciledStatus($srcTrnsID1, "1");
    }
    return $resCnt;
}

function voidJrnlBatchTrans($oldBatchId, $nwBatchId, $trnsDate)
{
    global $usrID;
    if ($trnsDate != "") {
        $trnsDate = cnvrtDMYTmToYMDTm($trnsDate);
    }
    $insSQL = "INSERT INTO accb.accb_trnsctn_details(" .
        "accnt_id, transaction_desc, dbt_amount, trnsctn_date, " .
        "func_cur_id, created_by, creation_date, batch_id, crdt_amount, " .
        "last_update_by, last_update_date, net_amount, 
            entered_amnt, entered_amt_crncy_id, accnt_crncy_amnt, accnt_crncy_id, 
            func_cur_exchng_rate, accnt_cur_exchng_rate, dbt_or_crdt, ref_doc_number, 
            src_trns_id_reconciled, src_trns_id_being_blncd, trnsctn_smmry_id,
            source_mdl_name, source_mdl_det_line_id) " .
        " SELECT accnt_id, '(Reversal) '||transaction_desc, -1*dbt_amount, '" . $trnsDate . "', " .
        "func_cur_id, " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $nwBatchId . ", -1*crdt_amount, " .
        $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), -1*net_amount, 
            -1*entered_amnt, entered_amt_crncy_id, -1*accnt_crncy_amnt, accnt_crncy_id, 
            func_cur_exchng_rate, accnt_cur_exchng_rate, dbt_or_crdt, ref_doc_number, 
            src_trns_id_reconciled, src_trns_id_being_blncd, trnsctn_smmry_id,
            source_mdl_name, source_mdl_det_line_id "
        . "FROM accb.accb_trnsctn_details WHERE batch_id = " . $oldBatchId . " and batch_id>0 ORDER BY transctn_id";
    //var_dump($insSQL);
    return execUpdtInsSQL($insSQL);
}

function updateTransaction(
    $accntid,
    $trnsDesc,
    $dbtAmnt,
    $trnsDate,
    $crncyid,
    $batchid,
    $crdtamnt,
    $netAmnt,
    $trnsid,
    $entrdAmt,
    $entrdCurrID,
    $acntAmnt,
    $acntCurrID,
    $funcExchRate,
    $acntExchRate,
    $dbtOrCrdt,
    $refDocNum,
    $srcTrnsID1,
    $srcTrnsID2,
    $trnsSmmryID,
    $source_mdl_name,
    $source_mdl_ln_id
) {
    global $usrID;
    if (is_numeric($refDocNum)) {
        $refDocNum = "Ref.:" . $refDocNum;
    }
    if ($trnsDate != "") {
        $trnsDate = cnvrtDMYTmToYMDTm($trnsDate);
    }
    if (strlen($trnsDesc) > 500) {
        $trnsDesc = substr($trnsDesc, 0, 500);
    }
    $updtSQL = "UPDATE accb.accb_trnsctn_details " .
        "SET accnt_id=" . $accntid . ", transaction_desc='" . loc_db_escape_string($trnsDesc) .
        "', dbt_amount=" . $dbtAmnt . ", trnsctn_date='" . $trnsDate . "', func_cur_id=" . $crncyid .
        ", batch_id=" . $batchid . ", crdt_amount=" . $crdtamnt . ", last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), net_amount=" . $netAmnt .
        ", entered_amnt=" . $entrdAmt . ", entered_amt_crncy_id=" . $entrdCurrID .
        ", accnt_crncy_amnt=" . $acntAmnt . ", accnt_crncy_id=" . $acntCurrID .
        ", func_cur_exchng_rate=" . $funcExchRate . ", accnt_cur_exchng_rate=" . $acntExchRate .
        ", dbt_or_crdt='" . $dbtOrCrdt .
        "', ref_doc_number='" . loc_db_escape_string($refDocNum) .
        "', src_trns_id_reconciled = " . $srcTrnsID1 .
        ", src_trns_id_being_blncd = " . $srcTrnsID2 .
        ", trnsctn_smmry_id = " . $trnsSmmryID .
        ", source_mdl_name = '" . loc_db_escape_string($source_mdl_name) .
        "', source_mdl_det_line_id = " . $source_mdl_ln_id .
        " WHERE transctn_id=" . $trnsid;
    $resCnt = execUpdtInsSQL($updtSQL);
    if ($srcTrnsID1 > 0) {
        changeReconciledStatus($srcTrnsID1, "1");
    }
    return $resCnt;
}

function changeReconciledStatus($trnsID, $nwStatus)
{
    if ($trnsID <= 0) {
        return;
    }
    $updtSQL = "UPDATE accb.accb_trnsctn_details SET is_reconciled = '" .
        loc_db_escape_string($nwStatus) . "' WHERE transctn_id=" . $trnsID . " or src_trns_id_reconciled = " . $trnsID;
    return execUpdtInsSQL($updtSQL);
}

function get_BrkDwnLnID($bdgtDetID, $itmID, $accntID)
{
    $strSql = "SELECT bdgt_amnt_brkdwn_id from accb.accb_bdgt_amnt_brkdwn where account_id=" . $accntID .
        " and bdgt_item_id=" . $itmID .
        " and budget_det_id=" . $bdgtDetID . "";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function get_AccntBrkDwnLnID($transaction_id, $lnkd_pssbl_val_id)
{
    $strSql = "SELECT trns_amnt_det_id from accb.accb_trnsctn_amnt_breakdown where transaction_id=" . $transaction_id .
        " and lnkd_pssbl_val_id=" . $lnkd_pssbl_val_id . " and transaction_id>0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function get_AccntBrkDwnSmryLnID($smryln_id, $lnkd_pssbl_val_id)
{
    $strSql = "SELECT trns_amnt_det_id from accb.accb_trnsctn_amnt_breakdown where trnsctn_smmry_id=" . $smryln_id .
        " and lnkd_pssbl_val_id=" . $lnkd_pssbl_val_id . " and trnsctn_smmry_id>0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getNewBrkDwnLnID()
{
    $strSql = "select nextval('accb.accb_bdgt_amnt_brkdwn_bdgt_amnt_brkdwn_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function createAmntBrkDwn($trnsdetid, $trnsID, $pssblvalid, $trnsDesc, $qty, $unitAmnt, $ttlAmnt, $lnSmryID)
{
    global $usrID;
    if (strlen($trnsDesc) > 290) {
        $trnsDesc = substr($trnsDesc, 0, 290);
    }
    $insSQL = "INSERT INTO accb.accb_trnsctn_amnt_breakdown(
            transaction_id, description, quantity, unit_amnt, ttl_amnt, created_by, 
            creation_date, last_update_by, last_update_date, trns_amnt_det_id, 
            lnkd_pssbl_val_id, trnsctn_smmry_id) " .
        "VALUES (" . $trnsID . ", '" . loc_db_escape_string($trnsDesc) . "', " . $qty . ", " .
        $unitAmnt . ", " . $ttlAmnt . ", " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $trnsdetid . ", " . $pssblvalid . ", " . $lnSmryID . ")";
    return execUpdtInsSQL($insSQL);
}

function updateAmntBrkDwn($trnsdetid, $trnsID, $pssblvalid, $trnsDesc, $qty, $unitAmnt, $ttlAmnt, $lnSmryID)
{
    global $usrID;
    $insSQL = "UPDATE accb.accb_trnsctn_amnt_breakdown SET 
            transaction_id=" . $trnsID .
        ", description='" . loc_db_escape_string($trnsDesc) .
        "', quantity=" . $qty .
        ", unit_amnt=" . $unitAmnt .
        ", ttl_amnt=" . $ttlAmnt .
        ", last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS')" .
        ", lnkd_pssbl_val_id=" . $pssblvalid .
        ", trnsctn_smmry_id=" . $lnSmryID .
        " WHERE (trns_amnt_det_id= " . $trnsdetid . ")";
    return execUpdtInsSQL($insSQL);
}

function updateAmntBrkDwnID($oldtrnsID, $nwtrnsID)
{
    global $usrID;
    $insSQL = "UPDATE accb.accb_trnsctn_amnt_breakdown SET 
            transaction_id=" . $nwtrnsID . ", last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS') " .
        "WHERE (transaction_id= " . $oldtrnsID . ")";
    return execUpdtInsSQL($insSQL);
}

function get_Basic_Bdgt($searchWord, $searchIn, $offset, $limit_size, $orgID)
{
    $strSql = "";
    if ($searchIn == "Budget Name") {
        $strSql = "SELECT a.budget_id, a.budget_name, a.budget_desc, " .
            "a.is_the_active_one, " .
            "CASE WHEN a.start_date='' THEN '' ELSE to_char(to_timestamp(a.start_date, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') END, " .
            "CASE WHEN a.end_date='' THEN '' ELSE to_char(to_timestamp(a.end_date, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') END, "
            . "a.period_type, 0.00 ttl_incm, 0.00 ttl_expns, 0.00 net_incm, allwd_accnt_types " .
            "FROM accb.accb_budget_header a " .
            "WHERE ((a.budget_name ilike '" . loc_db_escape_string($searchWord) .
            "') AND (a.org_id = " . $orgID . ")) ORDER BY a.budget_id DESC LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    } else {
        $strSql = "SELECT a.budget_id, a.budget_name, a.budget_desc, " .
            "a.is_the_active_one, " .
            "CASE WHEN a.start_date='' THEN '' ELSE to_char(to_timestamp(a.start_date, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') END, " .
            "CASE WHEN a.end_date='' THEN '' ELSE to_char(to_timestamp(a.end_date, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') END, "
            . "a.period_type, 0.00 ttl_incm, 0.00 ttl_expns, 0.00 net_incm, allwd_accnt_types " .
            "FROM accb.accb_budget_header a " .
            "WHERE ((a.budget_desc ilike '" . loc_db_escape_string($searchWord) .
            "') AND (a.org_id = " . $orgID . ")) ORDER BY a.budget_id DESC LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    }
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_Bdgt($searchWord, $searchIn, $orgID)
{
    $strSql = "";
    if ($searchIn == "Budget Name") {
        $strSql = "SELECT count(1) " .
            "FROM accb.accb_budget_header a " .
            "WHERE ((a.budget_name ilike '" . loc_db_escape_string($searchWord) .
            "') AND (a.org_id = " . $orgID . "))";
    } else {
        $strSql = "SELECT count(1) " .
            "FROM accb.accb_budget_header a " .
            "WHERE ((a.budget_desc ilike '" . loc_db_escape_string($searchWord) .
            "') AND (a.org_id = " . $orgID . "))";
    }
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_One_BdgtDtExprt($searchWord, $searchIn, $offset, $limit_size, $bdgtID, $bdgtYear, $bdgtPeriodType)
{
    /* Account Number
      Account Name
      Period Start Date
      Period End Date */
    $whrcls = "";
    $strSql = "";
    if ($searchIn == "Account Number") {
        $whrcls = " and (a.accnt_num ilike '" . loc_db_escape_string($searchWord) . "' or a.accnt_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Account Name") {
        $whrcls = " and (a.accnt_num ilike '" . loc_db_escape_string($searchWord) . "' or a.accnt_name ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $strSql = "Select DISTINCT a.accnt_num, a.accnt_name, 
            gst.get_pssbl_val(b.entrd_curr_id) entrd_curr_nm, b.action_if_limit_excded, 
            accb.get_bdgt_amount(b.budget_id,b.accnt_id, '" . $bdgtYear . "-01-01 00:00:00', '" . $bdgtYear . "-01-31 23:59:59'), 
            accb.get_bdgt_amount(b.budget_id,b.accnt_id, '" . $bdgtYear . "-02-01 00:00:00', '" . $bdgtYear . "-02-31 23:59:59'),
            accb.get_bdgt_amount(b.budget_id,b.accnt_id, '" . $bdgtYear . "-03-01 00:00:00', '" . $bdgtYear . "-03-31 23:59:59'), 
            accb.get_bdgt_amount(b.budget_id,b.accnt_id, '" . $bdgtYear . "-04-01 00:00:00', '" . $bdgtYear . "-04-31 23:59:59'),
            accb.get_bdgt_amount(b.budget_id,b.accnt_id, '" . $bdgtYear . "-05-01 00:00:00', '" . $bdgtYear . "-05-31 23:59:59'), 
            accb.get_bdgt_amount(b.budget_id,b.accnt_id, '" . $bdgtYear . "-06-01 00:00:00', '" . $bdgtYear . "-06-31 23:59:59'),
            accb.get_bdgt_amount(b.budget_id,b.accnt_id, '" . $bdgtYear . "-07-01 00:00:00', '" . $bdgtYear . "-07-31 23:59:59'), 
            accb.get_bdgt_amount(b.budget_id,b.accnt_id, '" . $bdgtYear . "-08-01 00:00:00', '" . $bdgtYear . "-08-31 23:59:59'),
            accb.get_bdgt_amount(b.budget_id,b.accnt_id, '" . $bdgtYear . "-09-01 00:00:00', '" . $bdgtYear . "-09-31 23:59:59'), 
            accb.get_bdgt_amount(b.budget_id,b.accnt_id, '" . $bdgtYear . "-10-01 00:00:00', '" . $bdgtYear . "-10-31 23:59:59'),
            accb.get_bdgt_amount(b.budget_id,b.accnt_id, '" . $bdgtYear . "-11-01 00:00:00', '" . $bdgtYear . "-11-31 23:59:59'), 
            accb.get_bdgt_amount(b.budget_id,b.accnt_id, '" . $bdgtYear . "-12-01 00:00:00', '" . $bdgtYear . "-12-31 23:59:59'), 
            b.budget_id, a.accnt_typ_id, b.accnt_id, b.entrd_curr_id
        FROM accb.accb_chart_of_accnts a,
        accb.accb_budget_details b
   WHERE (a.accnt_id = b.accnt_id AND b.budget_id = " . $bdgtID . $whrcls . ") 
   ORDER BY a.accnt_typ_id, a.accnt_num LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    //b.func_curr_rate, 

    if ($bdgtPeriodType == "Yearly") {
        $strSql = "Select DISTINCT a.accnt_num, a.accnt_name, 
            gst.get_pssbl_val(b.entrd_curr_id) entrd_curr_nm, b.action_if_limit_excded, 
            accb.get_bdgt_amount(b.budget_id,b.accnt_id, '" . $bdgtYear . "-01-01 00:00:00', '" . $bdgtYear . "-12-31 23:59:59'), 
            b.budget_id, a.accnt_typ_id, b.accnt_id, b.entrd_curr_id
        FROM accb.accb_chart_of_accnts a,
        accb.accb_budget_details b
   WHERE (a.accnt_id = b.accnt_id AND b.budget_id = " . $bdgtID . $whrcls . ") 
   ORDER BY a.accnt_typ_id, a.accnt_num LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    } else if ($bdgtPeriodType == "Half Yearly") {
        $strSql = "Select DISTINCT a.accnt_num, a.accnt_name, 
            gst.get_pssbl_val(b.entrd_curr_id) entrd_curr_nm, b.action_if_limit_excded, 
            accb.get_bdgt_amount(b.budget_id,b.accnt_id, '" . $bdgtYear . "-01-01 00:00:00', '" . $bdgtYear . "-06-30 23:59:59'), 
            accb.get_bdgt_amount(b.budget_id,b.accnt_id, '" . $bdgtYear . "-07-01 00:00:00', '" . $bdgtYear . "-12-31 23:59:59'), 
            b.budget_id, a.accnt_typ_id, b.accnt_id, b.entrd_curr_id
        FROM accb.accb_chart_of_accnts a,
        accb.accb_budget_details b
   WHERE (a.accnt_id = b.accnt_id AND b.budget_id = " . $bdgtID . $whrcls . ") 
   ORDER BY a.accnt_typ_id, a.accnt_num LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    } else if ($bdgtPeriodType == "Quarterly") {
        $strSql = "Select DISTINCT a.accnt_num, a.accnt_name, 
            gst.get_pssbl_val(b.entrd_curr_id) entrd_curr_nm, b.action_if_limit_excded, 
            accb.get_bdgt_amount(b.budget_id,b.accnt_id, '" . $bdgtYear . "-01-01 00:00:00', '" . $bdgtYear . "-03-31 23:59:59'), 
            accb.get_bdgt_amount(b.budget_id,b.accnt_id, '" . $bdgtYear . "-04-01 00:00:00', '" . $bdgtYear . "-06-31 23:59:59'),
            accb.get_bdgt_amount(b.budget_id,b.accnt_id, '" . $bdgtYear . "-07-01 00:00:00', '" . $bdgtYear . "-09-31 23:59:59'), 
            accb.get_bdgt_amount(b.budget_id,b.accnt_id, '" . $bdgtYear . "-10-01 00:00:00', '" . $bdgtYear . "-12-31 23:59:59'), 
            b.budget_id, a.accnt_typ_id, b.accnt_id, b.entrd_curr_id
        FROM accb.accb_chart_of_accnts a,
        accb.accb_budget_details b
   WHERE (a.accnt_id = b.accnt_id AND b.budget_id = " . $bdgtID . $whrcls . ") 
   ORDER BY a.accnt_typ_id, a.accnt_num LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    }
    //logSessionErrs($strSql);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_BdgtDt($searchWord, $searchIn, $offset, $limit_size, $bdgtID, $qShwNonZeroOnly, $shdRefreshMatView = false)
{
    /* Account Number
      Account Name
      Period Start Date
      Period End Date */
    $whrcls = "";
    $strSql = "";
    if ($shdRefreshMatView === false) {
        if ($searchIn == "Account Number") {
            $whrcls = " and (a.accnt_num ilike '" . loc_db_escape_string($searchWord) . "' or a.accnt_name ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Account Name") {
            $whrcls = " and (a.accnt_num ilike '" . loc_db_escape_string($searchWord) . "' or a.accnt_name ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Period Start Date") {
            $whrcls = " and a.start_date ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Period End Date") {
            $whrcls = " and a.end_date ilike '" . loc_db_escape_string($searchWord) . "')";
        }
        if ($qShwNonZeroOnly) {
            $whrcls .= " and COALESCE(a.limit_amount,0) !=0";
        }
        $strSql = "select budget_det_id, accnt_id, accnt_num, accnt_name,limit_amount, usd_amnt, start_date, end_date, 
        action_if_limit_excded, entrd_curr_id, entrd_curr_nm, entrd_amount, func_curr_rate, budget_id, 
        accnt_typ_id FROM accb.accb_budget_detail_mv a " .
            "WHERE(a.budget_id = " . $bdgtID . $whrcls . ") ORDER BY a.accnt_typ_id, a.accnt_num, " .
            "a.start_date LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    } else {
        if ($searchIn == "Account Number") {
            $whrcls = " and (a.accnt_num ilike '" . loc_db_escape_string($searchWord) . "' or a.accnt_name ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Account Name") {
            $whrcls = " and (a.accnt_num ilike '" . loc_db_escape_string($searchWord) . "' or a.accnt_name ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Period Start Date") {
            $whrcls = " and to_char(to_timestamp(b.start_date, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Period End Date") {
            $whrcls = " and to_char(to_timestamp(b.end_date, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) . "')";
        }
        if ($qShwNonZeroOnly) {
            $whrcls .= " and COALESCE(b.limit_amount,0) !=0";
        }
        $strSql = "select b.budget_det_id, b.accnt_id, a.accnt_num, a.accnt_name, b.limit_amount, 
        COALESCE(accb.get_prd_usr_trns_sum(a.accnt_id, b.start_date, b.end_date), 0) usd_amnt,
        to_char(to_timestamp(b.start_date, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') start_date,
        to_char(to_timestamp(b.end_date, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') end_date, 
        b.action_if_limit_excded, b.entrd_curr_id, gst.get_pssbl_val(b.entrd_curr_id) entrd_curr_nm, 
        b.entrd_amount, b.func_curr_rate, b.budget_id, a.accnt_typ_id 
        FROM accb.accb_chart_of_accnts a,
        accb.accb_budget_details b
   WHERE (a.accnt_id = b.accnt_id AND b.budget_id = " . $bdgtID . $whrcls . ") 
   ORDER BY a.accnt_typ_id, a.accnt_num, b.start_date LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    }
    /* (select z.limit_amount FROM accb.accb_budget_details z where z.budget_det_id=a.budget_det_id)
     * 
     *  $strSql = "SELECT a.budget_det_id, a.accnt_id, b.accnt_num, b.accnt_name, " .
      "COALESCE(a.limit_amount,0), COALESCE(accb.get_prd_usr_trns_sum(a.accnt_id, a.start_date, a.end_date),0) usd_amnt, " .
      "to_char(to_timestamp(a.start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'),
      to_char(to_timestamp(a.end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), " .
      "a.action_if_limit_excded, a.entrd_curr_id,gst.get_pssbl_val(a.entrd_curr_id), "
      . "a.entrd_amount, a.func_curr_rate  " .
      "FROM accb.accb_budget_details a LEFT OUTER JOIN " .
      "accb.accb_chart_of_accnts b on a.accnt_id = b.accnt_id " .
      "WHERE(a.budget_id = " . $bdgtID . $whrcls . ") ORDER BY b.accnt_typ_id, b.accnt_num, " .
      "to_timestamp(a.start_date,'YYYY-MM-DD HH24:MI:SS') LIMIT " . $limit_size .
      " OFFSET " . (abs($offset * $limit_size)); */

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_BdgtDt($searchWord, $searchIn, $bdgtID, $qShwNonZeroOnly, $shdRefreshMatView = false)
{
    global $fnccurid;
    execUpdtInsSQL("Update accb.accb_budget_details SET entrd_curr_id=" . $fnccurid .
        ", entrd_amount=COALESCE(limit_amount,0)" .
        ", func_curr_rate=1.0000 WHERE entrd_curr_id<=0");
    $whrcls = "";
    $strSql = "";
    if ($shdRefreshMatView === false) {
        if ($searchIn == "Account Number") {
            $whrcls = " and (a.accnt_num ilike '" . loc_db_escape_string($searchWord) . "' or a.accnt_name ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Account Name") {
            $whrcls = " and (a.accnt_num ilike '" . loc_db_escape_string($searchWord) . "' or a.accnt_name ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Period Start Date") {
            $whrcls = " and a.start_date ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Period End Date") {
            $whrcls = " and a.end_date ilike '" . loc_db_escape_string($searchWord) . "')";
        }
        if ($qShwNonZeroOnly) {
            $whrcls .= " and COALESCE(a.limit_amount,0) !=0";
        }
        $strSql = "SELECT count(1) " .
            "FROM accb.accb_budget_detail_mv a " .
            "WHERE(a.budget_id = " . $bdgtID . $whrcls . ")";
    } else {
        if ($searchIn == "Account Number") {
            $whrcls = " and (a.accnt_num ilike '" . loc_db_escape_string($searchWord) . "' or a.accnt_name ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Account Name") {
            $whrcls = " and (a.accnt_num ilike '" . loc_db_escape_string($searchWord) . "' or a.accnt_name ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Period Start Date") {
            $whrcls = " and to_char(to_timestamp(b.start_date, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) . "')";
        } else if ($searchIn == "Period End Date") {
            $whrcls = " and to_char(to_timestamp(b.end_date, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) . "')";
        }
        if ($qShwNonZeroOnly) {
            $whrcls .= " and COALESCE(b.limit_amount,0) !=0";
        }
        $strSql = "SELECT count(1) " .
            "FROM accb.accb_chart_of_accnts a,
            accb.accb_budget_details b
       WHERE (a.accnt_id = b.accnt_id AND b.budget_id = " . $bdgtID . $whrcls . ")";
    }
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_Bdgt_IncmSum($bdgtID)
{
    $strSql = "SELECT accb.get_bdgt_IncmSum(" . $bdgtID . ")";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_Bdgt_ExpnsSum($bdgtID)
{
    $strSql = "SELECT accb.get_bdgt_ExpnsSum(" . $bdgtID . ")";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function isBdgtMVPopulated()
{
    $strSql = "SELECT (CASE WHEN relispopulated THEN 'true' ELSE 'false' END) mv_loaded FROM pg_class WHERE relname = 'accb_budget_detail_mv'";
    $result = executeSQLNoParams($strSql);

    while ($row = loc_db_fetch_array($result)) {
        return  $row[0] == "true";
    }
    return false;
}

function get_Bdgt_MinCrncyID($bdgtID)
{
    $strSql = "SELECT MIN(b.entrd_curr_id)
    FROM accb.accb_budget_detail_mv b
    WHERE ((b.budget_id = " . $bdgtID . "))";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function get_Bdgt_AmntBrkdwn($acntID, $bdgtDtID, &$chrt_SQL)
{
    $strSql = "";
    $extrWhere = "";
    if ($bdgtDtID > 0) {
        $extrWhere = " and a.budget_det_id=" . $bdgtDtID;
    }
    $strSql = "SELECT a.bdgt_amnt_brkdwn_id, a.budget_det_id, 
        a.account_id, accb.get_accnt_num(b.accnt_id) || '.' || accb.get_accnt_name(b.accnt_id) accnt_name2, 
        a.bdgt_item_id, inv.get_invitm_name(a.bdgt_item_id), a.bdgt_detail_type, 
       a.item_quantity1, a.item_quantity2, a.unit_price_or_rate,
        (a.item_quantity1*a.item_quantity2*a.unit_price_or_rate), a.remarks_desc, 
        to_char(to_timestamp(b.start_date,'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS')  start_date, 
        to_char(to_timestamp(b.end_date,'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') end_date
        FROM accb.accb_bdgt_amnt_brkdwn a, accb.accb_budget_details b
             WHERE   (a.account_id = " . $acntID . " and a.budget_det_id=b.budget_det_id" . $extrWhere . ")
        ORDER BY   4, 1";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    $chrt_SQL = $strSql;
    return $result;
}

function get_Bdgt_DetBrkdwns($searchWord, $searchIn, $offset, $limit_size, $bdgtID)
{
    /* "Account Number",
      "Account Name",
      "Budget Item",
      "Remark" */
    $whrcls = "";
    if ($searchIn == "Account Number") {
        $whrcls = " and (accb.get_accnt_num(b.accnt_id) || '.' || accb.get_accnt_name(b.accnt_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Account Name") {
        $whrcls = " and (accb.get_accnt_num(b.accnt_id) || '.' || accb.get_accnt_name(b.accnt_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Budget Item") {
        $whrcls = " and (inv.get_invitm_name(a.bdgt_item_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Remark") {
        $whrcls = " and (a.remarks_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = " SELECT a.bdgt_amnt_brkdwn_id, a.budget_det_id, 
        a.account_id, accb.get_accnt_num(b.accnt_id) || '.' || accb.get_accnt_name(b.accnt_id) accnt_name, 
        a.bdgt_item_id, inv.get_invitm_name(a.bdgt_item_id), a.bdgt_detail_type, 
       a.item_quantity1, a.item_quantity2, a.unit_price_or_rate,
        (a.item_quantity1*a.item_quantity2*a.unit_price_or_rate), a.remarks_desc, 
        to_char(to_timestamp(b.start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS')  start_date, 
        to_char(to_timestamp(b.end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') end_date, 
        accb.get_accnt_num(a.account_id), accb.get_accnt_name(a.account_id), b.start_date,
        b.entrd_curr_id, gst.get_pssbl_val(b.entrd_curr_id) 
        FROM accb.accb_bdgt_amnt_brkdwn a, accb.accb_budget_details b 
             WHERE (a.budget_det_id IN (select z.budget_det_id from accb.accb_budget_details z where z.budget_id=" . $bdgtID .
        ") and a.budget_det_id=b.budget_det_id)" . $whrcls .
        " ORDER BY   14, 16, 5 LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    //chrt_SQL = strSql;
    return $result;
}

function get_Ttl_Bdgt_DetBrkdwns($searchWord, $searchIn, $bdgtID)
{
    $whrcls = "";
    if ($searchIn == "Account Number") {
        $whrcls = " and (accb.get_accnt_num(b.accnt_id) || '.' || accb.get_accnt_name(b.accnt_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Account Name") {
        $whrcls = " and (accb.get_accnt_num(b.accnt_id) || '.' || accb.get_accnt_name(b.accnt_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Budget Item") {
        $whrcls = " and (inv.get_invitm_name(a.bdgt_item_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Remark") {
        $whrcls = " and (a.remarks_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = " SELECT count(1) 
        FROM accb.accb_bdgt_amnt_brkdwn a, accb.accb_budget_details b 
             WHERE (a.budget_det_id IN (select z.budget_det_id from accb.accb_budget_details z where z.budget_id=" . $bdgtID .
        ") and a.budget_det_id=b.budget_det_id)" . $whrcls;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0.00;
}

function deleteBudgetLine($valLnid, $LineDesc)
{
    $delSql = "DELETE FROM accb.accb_bdgt_amnt_brkdwn WHERE budget_det_id =" . $valLnid;
    execUpdtInsSQL($delSql, "Budget Name = " . $LineDesc);
    $delSQL = "DELETE FROM accb.accb_budget_details WHERE budget_det_id = " . $valLnid;
    return execUpdtInsSQL($delSQL, "Budget Name = " . $LineDesc);
}

function deleteBdgt($bdgtid, $bdgtNm)
{
    $delSql = "DELETE FROM accb.accb_bdgt_amnt_brkdwn WHERE(budget_det_id IN (Select budget_det_id from accb.accb_budget_details WHERE budget_id = " . $bdgtid . "))";
    $affctd = execUpdtInsSQL($delSql, "Budget Name = " . $bdgtNm);
    $delSQL = "DELETE FROM accb.accb_budget_details WHERE budget_id = " . $bdgtid;
    $affctd0 = execUpdtInsSQL($delSQL, "Budget Name = " . $bdgtNm);
    $delSql = "DELETE FROM accb.accb_budget_header WHERE(budget_id = " . $bdgtid . ")";
    $affctd1 = execUpdtInsSQL($delSql, "Budget Name = " . $bdgtNm);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Budget Header(s)!";
        $dsply .= "<br/>$affctd0 Budget Line(s)!";
        $dsply .= "<br/>$affctd Budget Amount Breakdown(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteBdgtBrkDwn($trnsdetid)
{
    $delSql = "DELETE FROM accb.accb_bdgt_amnt_brkdwn WHERE(bdgt_amnt_brkdwn_id = " . $trnsdetid . ")";
    $affctd1 = execUpdtInsSQL($delSql);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Budget Amount Breakdown(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_BdgtDetID($strtDte, $endDte, $bdgtID, $accntID)
{
    if ($strtDte != "") {
        $strtDte = cnvrtDMYTmToYMDTm($strtDte);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYTmToYMDTm($endDte);
    }
    $strSql = "SELECT budget_det_id from accb.accb_budget_details where accnt_id=" . $accntID .
        " and budget_id=" . $bdgtID .
        " and start_date='" . $strtDte . "'" .
        " and end_date='" . $endDte . "'";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createBdgtBrkDwn($brkDwnID, $accntID, $itemID, $bdgtDetType, $lineDesc, $qty, $mltplr2, $unitAmnt, $bdgtDetID, $strtDte, $endDte)
{
    global $usrID;
    if (strlen($lineDesc) > 299) {
        $lineDesc = substr($lineDesc, 0, 299);
    }
    if (strlen($bdgtDetType) > 299) {
        $bdgtDetType = substr($bdgtDetType, 0, 299);
    }
    if ($strtDte != "") {
        $strtDte = cnvrtDMYTmToYMDTm($strtDte);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYTmToYMDTm($endDte);
    }
    $insSQL = "INSERT INTO accb.accb_bdgt_amnt_brkdwn(
            bdgt_amnt_brkdwn_id, account_id, bdgt_item_id, bdgt_detail_type, 
            item_quantity1, item_quantity2, unit_price_or_rate, remarks_desc, 
            created_by, creation_date, last_update_by, last_update_date, budget_det_id, start_date, end_date) VALUES (" . $brkDwnID .
        ", " . $accntID .
        ", " . $itemID .
        ", '" . loc_db_escape_string($bdgtDetType) .
        "', " . $qty .
        ", " . $mltplr2 .
        ", " . $unitAmnt .
        ",'" . loc_db_escape_string($lineDesc) .
        "', " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $bdgtDetID .
        ", '" . $strtDte . "', '" . $endDte . "')";
    return execUpdtInsSQL($insSQL);
}

function updateBdgtBrkDwn($brkDwnID, $accntID, $itemID, $bdgtDetType, $lineDesc, $qty, $mltplr2, $unitAmnt, $bdgtDetID, $strtDte, $endDte)
{
    global $usrID;
    if ($strtDte != "") {
        $strtDte = cnvrtDMYTmToYMDTm($strtDte);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYTmToYMDTm($endDte);
    }
    $insSQL = "UPDATE accb.accb_bdgt_amnt_brkdwn SET 
                                account_id=" . $accntID .
        ", bdgt_item_id = " . $itemID .
        ", bdgt_detail_type ='" . loc_db_escape_string($bdgtDetType) .
        "', item_quantity1=" . $qty .
        ", unit_price_or_rate=" . $unitAmnt .
        ", item_quantity2=" . $mltplr2 .
        ", remarks_desc='" . loc_db_escape_string($lineDesc) .
        "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), budget_det_id = " . $bdgtDetID .
        ", start_date='" . $strtDte .
        "', end_date='" . $endDte .
        "' WHERE (bdgt_amnt_brkdwn_id= " . $brkDwnID . ")";
    return execUpdtInsSQL($insSQL);
}

function updateBdgtDetAmnt($bdgtDetID, $accntID, $ttlAmount)
{
    global $usrID;
    $insSQL = "UPDATE accb.accb_budget_details SET 
                               limit_amount=" . $ttlAmount .
        ", last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (budget_det_id= " . $bdgtDetID . " and accnt_id = " . $accntID . ")";
    return execUpdtInsSQL($insSQL);
}

function updateBdgtDetAmnt1($bdgtDetID, $accntID)
{
    global $usrID;
    $insSQL = "UPDATE accb.accb_budget_details SET 
                               limit_amount=(SELECT SUM(z.item_quantity1*z.item_quantity2*z.unit_price_or_rate) " .
        "FROM accb.accb_bdgt_amnt_brkdwn z WHERE z.budget_det_id=" . $bdgtDetID .
        " and z.account_id = " . $accntID . "), last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (budget_det_id= " . $bdgtDetID .
        " and accnt_id = " . $accntID . ")";
    return execUpdtInsSQL($insSQL);
}

function updateBdgtBrkDwnDates($bdgtDetID, $strtDte, $endDte)
{
    global $usrID;
    if ($strtDte != "") {
        $strtDte = cnvrtDMYTmToYMDTm($strtDte);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYTmToYMDTm($endDte);
    }
    $insSQL = "UPDATE accb.accb_bdgt_amnt_brkdwn SET 
                                 last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), start_date='" . $strtDte .
        "', end_date='" . $endDte .
        "' WHERE (budget_det_id = " . $bdgtDetID . ")";
    return execUpdtInsSQL($insSQL);
}

function createBdgtLn($bdgtID, $accntid, $amntLmt, $strtDate, $endDate, $action, $entrd_amount, $func_curr_rate, $entrd_curr_id)
{
    global $usrID;
    if ($strtDate != "") {
        $strtDate = cnvrtDMYTmToYMDTm($strtDate);
    }
    if ($endDate != "") {
        $endDate = cnvrtDMYTmToYMDTm($endDate);
    }
    $insSQL = "INSERT INTO accb.accb_budget_details(
	budget_id, accnt_id, limit_amount, start_date, end_date, 
        created_by, creation_date, last_update_by, last_update_date, 
        action_if_limit_excded, entrd_amount, func_curr_rate, entrd_curr_id) VALUES (" . $bdgtID . "," . $accntid . ", " . $amntLmt .
        ", '" . $strtDate . "', '" . $endDate . "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($action) .
        "'," . $entrd_amount . "," . $func_curr_rate . "," . $entrd_curr_id . ")";
    return execUpdtInsSQL($insSQL);
}

function createBudget($orgid, $bdgtname, $bdgtdesc, $isactive, $strtDate, $endDate, $prdType, $allwd_accnt_types)
{
    global $usrID;
    global $orgID;
    if ($strtDate != "") {
        $strtDate = cnvrtDMYTmToYMDTm($strtDate);
    }
    if ($endDate != "") {
        $endDate = cnvrtDMYTmToYMDTm($endDate);
    }
    if ($isactive) {
        setAllBdgtInActive();
    }
    $insSQL = "INSERT INTO accb.accb_budget_header(" .
        "budget_name, budget_desc, is_the_active_one, created_by, " .
        "creation_date, last_update_by, last_update_date, org_id, start_date, end_date, period_type,allwd_accnt_types) " .
        "VALUES ('" . loc_db_escape_string($bdgtname) . "', '" . loc_db_escape_string($bdgtdesc) .
        "', '" . cnvrtBoolToBitStr($isactive) . "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $orgID . ", '" . loc_db_escape_string($strtDate) .
        "', '" . loc_db_escape_string($endDate) .
        "', '" . loc_db_escape_string($prdType) .
        "', '" . loc_db_escape_string($allwd_accnt_types) .
        "')";
    return execUpdtInsSQL($insSQL);
}

function updateBdgtLn($bdgtDtID, $accntid, $amntLmt, $strtDate, $endDate, $action, $entrd_amount, $func_curr_rate, $entrd_curr_id)
{
    global $usrID;
    if ($strtDate != "") {
        $strtDate = cnvrtDMYTmToYMDTm($strtDate);
    }
    if ($endDate != "") {
        $endDate = cnvrtDMYTmToYMDTm($endDate);
    }
    $updtSQL = "UPDATE accb.accb_budget_details SET " .
        "accnt_id = " . $accntid . ", limit_amount = " . $amntLmt .
        ", start_date = '" . $strtDate . "', " .
        "end_date = '" . $endDate . "', last_update_by = " .
        $usrID . ", last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " .
        "action_if_limit_excded = '" . loc_db_escape_string($action) . "', 
            entrd_amount=" . $entrd_amount . ", 
            func_curr_rate=" . $func_curr_rate . ", 
            entrd_curr_id=" . $entrd_curr_id . " " .
        "WHERE budget_det_id = " . $bdgtDtID;
    //echo $updtSQL;
    return execUpdtInsSQL($updtSQL);
}

function setAllBdgtInActive()
{
    global $usrID;
    $updtSQL = "UPDATE accb.accb_budget_header SET " .
        "is_the_active_one = '0', last_update_by = " . $usrID .
        ", last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS') " .
        "WHERE is_the_active_one = '1'";
    return execUpdtInsSQL($updtSQL);
}

function updateBudget($budgtID, $bdgtname, $bdgtdesc, $isactive, $strtDate, $endDate, $prdType, $allwd_accnt_types)
{
    global $usrID;
    if ($strtDate != "") {
        $strtDate = cnvrtDMYTmToYMDTm($strtDate);
    }
    if ($endDate != "") {
        $endDate = cnvrtDMYTmToYMDTm($endDate);
    }
    if ($isactive) {
        setAllBdgtInActive();
    }
    $updtSQL = "UPDATE accb.accb_budget_header SET " .
        "budget_name = '" . loc_db_escape_string($bdgtname) .
        "', budget_desc = '" . loc_db_escape_string($bdgtdesc) .
        "', is_the_active_one = '" . cnvrtBoolToBitStr($isactive) .
        "', last_update_by = " . $usrID .
        ", last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS') " .
        ", start_date = '" . loc_db_escape_string($strtDate) . "' " .
        ", end_date = '" . loc_db_escape_string($endDate) . "' " .
        ", period_type = '" . loc_db_escape_string($prdType) . "'" .
        ", allwd_accnt_types = '" . loc_db_escape_string($allwd_accnt_types) . "' " .
        " WHERE budget_id = " . $budgtID;
    return execUpdtInsSQL($updtSQL);
}

function get_InvItemPrice($itmID)
{
    $strSql = "SELECT selling_price " .
        "FROM inv.inv_itm_list a " .
        "WHERE item_id =" . $itmID . "";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0.00;
}

function get_Basic_Tmplt($searchWord, $searchIn, $offset, $limit_size, $orgID)
{
    $strSql = "";
    if ($searchIn == "Template Name") {
        $strSql = "SELECT a.template_id, a.template_name, a.template_description,0,0, a.doc_type,a.is_enabled " .
            "FROM accb.accb_trnsctn_templates_hdr a " .
            "WHERE ((a.template_name ilike '" . loc_db_escape_string($searchWord) .
            "') AND (a.org_id = " . $orgID . ")) ORDER BY a.template_id DESC LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    } else if ($searchIn == "Template Description") {
        $strSql = "SELECT a.template_id, a.template_name, a.template_description,0,0, a.doc_type,a.is_enabled " .
            "FROM accb.accb_trnsctn_templates_hdr a " .
            "WHERE ((a.template_description ilike '" . loc_db_escape_string($searchWord) .
            "') AND (a.org_id = " . $orgID . ")) ORDER BY a.template_id DESC LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    } else {
        $strSql = "SELECT a.template_id, a.template_name, a.template_description,0,0, a.doc_type,a.is_enabled " .
            "FROM accb.accb_trnsctn_templates_hdr a " .
            "WHERE ((a.template_description ilike '" . loc_db_escape_string($searchWord) .
            "' or a.template_name ilike '" . loc_db_escape_string($searchWord) .
            "') AND (a.org_id = " . $orgID . ")) ORDER BY a.template_id DESC LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    }

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_Tmplts($searchWord, $searchIn, $orgID)
{
    $strSql = "";
    if ($searchIn == "Template Name") {
        $strSql = "SELECT count(1) " .
            "FROM accb.accb_trnsctn_templates_hdr a " .
            "WHERE ((a.template_name ilike '" . loc_db_escape_string($searchWord) .
            "') AND (a.org_id = " . $orgID . "))";
    } else if ($searchIn == "Template Description") {
        $strSql = "SELECT count(1) " .
            "FROM accb.accb_trnsctn_templates_hdr a " .
            "WHERE ((a.template_description ilike '" . loc_db_escape_string($searchWord) .
            "') AND (a.org_id = " . $orgID . "))";
    } else {
        $strSql = "SELECT count(1) " .
            "FROM accb.accb_trnsctn_templates_hdr a " .
            "WHERE ((a.template_description ilike '" . loc_db_escape_string($searchWord) .
            "' or a.template_name ilike '" . loc_db_escape_string($searchWord) .
            "') AND (a.org_id = " . $orgID . "))";
    }
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_One_Tmplt($tmpltID)
{
    $strSql = "SELECT a.template_id, a.template_name, a.template_description,0,0, a.doc_type,a.is_enabled " .
        "FROM accb.accb_trnsctn_templates_hdr a " .
        "WHERE ((a.template_id = " . $tmpltID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_Tmplt_Trns1($tmpltID, $curID)
{
    $strSql = "SELECT a.detail_id, a.increase_decrease, b.accnt_num, b.accnt_name, a.trnstn_desc, " .
        "a.accnt_id " .
        "FROM accb.accb_trnsctn_templates_det a LEFT OUTER JOIN " .
        "accb.accb_chart_of_accnts b on a.accnt_id = b.accnt_id " .
        "WHERE(a.template_id = " . $tmpltID . " and b.crncy_id = " . $curID . ") ORDER BY a.detail_id";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_Tmplt_Trns($tmpltID)
{
    $strSql = "SELECT a.detail_id, a.increase_decrease, b.accnt_num, b.accnt_name, a.trnstn_desc, " .
        "a.accnt_id " .
        "FROM accb.accb_trnsctn_templates_det a LEFT OUTER JOIN " .
        "accb.accb_chart_of_accnts b on a.accnt_id = b.accnt_id " .
        "WHERE(a.template_id = " . $tmpltID . ") ORDER BY a.detail_id";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Multi_Tmplt_Trns($tmpltIDs)
{
    $strSql = "SELECT a.detail_id, a.increase_decrease, b.accnt_num, b.accnt_name, a.trnstn_desc, " .
        "a.accnt_id,a.template_id " .
        "FROM accb.accb_trnsctn_templates_det a LEFT OUTER JOIN " .
        "accb.accb_chart_of_accnts b on a.accnt_id = b.accnt_id " .
        "WHERE('," . $tmpltIDs . ",' like '%,'||a.template_id||',%') ORDER BY a.template_id,a.detail_id";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Multi_Tmplt_Trns1($tmpltIDs)
{
    $strSql = "SELECT tbl1.* FROM (SELECT a.increase_decrease, accb.get_accnt_num(a.accnt_id) || '.' || accb.get_accnt_name(a.accnt_id) accnt_name, "
        . "a.trnstn_desc, a.accnt_id, "
        . "b.increase_decrease, accb.get_accnt_num(b.accnt_id) || '.' || accb.get_accnt_name(b.accnt_id) accnt_name2, "
        . "b.trnstn_desc, b.accnt_id, a.template_id " .
        "FROM accb.accb_trnsctn_templates_det a, accb.accb_trnsctn_templates_det b "
        . "WHERE a.template_id = b.template_id "
        . "and a.detail_id=(SELECT MIN(z.detail_id) FROM accb.accb_trnsctn_templates_det z WHERE z.template_id=a.template_id) "
        . "and b.detail_id=(SELECT MAX(y.detail_id) FROM accb.accb_trnsctn_templates_det y WHERE y.template_id=b.template_id) "
        . "and ('," . $tmpltIDs . ",' like '%,'||a.template_id||',%')) tbl1 "
        . " ORDER BY tbl1.template_id";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_Tmplt_Usrs($tmpltID)
{
    $strSql = "SELECT b.user_name, trim(c.title || ' ' || c.sur_name || ', ' || c.first_name " .
        "|| ' ' || c.other_names) fullname, a.user_id, b.person_id, a.row_id, to_char(to_timestamp(a.valid_start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), " .
        "to_char(to_timestamp(a.valid_end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') " .
        "FROM (accb.accb_trnsctn_templates_usrs a LEFT OUTER JOIN " .
        "sec.sec_users b ON a.user_id = b.user_id) LEFT OUTER JOIN prs.prsn_names_nos c " .
        "ON b.person_id = c.person_id " .
        "WHERE(a.template_id = " . $tmpltID . ") ORDER BY a.row_id";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Tmplt_Usr($tmpltID, $usrid)
{
    $strSql = "SELECT a.row_id " .
        "FROM accb.accb_trnsctn_templates_usrs a " .
        "WHERE((a.template_id = " . $tmpltID . ") and (a.user_id = " . $usrid . "))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function get_Usrs_Tmplt($searchWord, $searchIn, $offset, $limit_size, $orgID)
{
    global $usrID;
    $curid = getOrgFuncCurID($orgID);
    $strSql = "";
    if ($searchIn == "Template Name") {
        $strSql = "SELECT a.template_id, a.template_name, a.template_description " .
            "FROM accb.accb_trnsctn_templates_hdr a " .
            "WHERE (((Select count(y.detail_id) from accb.accb_trnsctn_templates_det y, " .
            "accb.accb_chart_of_accnts c where y.accnt_id = c.accnt_id and " .
            "y.template_id=a.template_id and c.crncy_id = " . $curid . ")=2) and (a.template_name ilike '" . loc_db_escape_string($searchWord) .
            "') AND (a.org_id = " . $orgID .
            ") and (a.template_id IN (select b.template_id from accb.accb_trnsctn_templates_usrs b " .
            "where ((b.user_id = " . $usrID .
            ") and (now() between to_timestamp(valid_start_date,'YYYY-MM-DD HH24:MI:SS') " .
            "AND to_timestamp(valid_end_date,'YYYY-MM-DD HH24:MI:SS')))))) " .
            "ORDER BY a.template_name LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    } else if ($searchIn == "Template Description") {
        $strSql = "SELECT a.template_id, a.template_name, a.template_description " .
            "FROM accb.accb_trnsctn_templates_hdr a " .
            "WHERE (((Select count(y.detail_id) from accb.accb_trnsctn_templates_det y, " .
            "accb.accb_chart_of_accnts c where y.accnt_id = c.accnt_id and " .
            "y.template_id=a.template_id and c.crncy_id = " . $curid . ")=2) and (a.template_description ilike '" . loc_db_escape_string($searchWord) .
            "') AND (a.org_id = " . $orgID .
            ") and (a.template_id IN (select b.template_id from accb.accb_trnsctn_templates_usrs b where ((b.user_id = " . $usrID .
            ") and (now() between to_timestamp(valid_start_date,'YYYY-MM-DD HH24:MI:SS') " .
            "AND to_timestamp(valid_end_date,'YYYY-MM-DD HH24:MI:SS')))))) " .
            "ORDER BY a.template_name LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    }
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_Usr_Tmplts($searchWord, $searchIn, $orgID)
{
    global $usrID;
    $curid = getOrgFuncCurID($orgID);
    $strSql = "";
    if ($searchIn == "Template Name") {
        $strSql = "SELECT count(1) " .
            "FROM accb.accb_trnsctn_templates_hdr a " .
            "WHERE (((Select count(y.detail_id) from accb.accb_trnsctn_templates_det y, " .
            "accb.accb_chart_of_accnts c where y.accnt_id = c.accnt_id and " .
            "y.template_id=a.template_id and c.crncy_id = " . $curid . ")=2) and (a.template_name ilike '" . loc_db_escape_string($searchWord) .
            "') AND (a.org_id = " . $orgID .
            ") and (a.template_id IN (select b.template_id from accb.accb_trnsctn_templates_usrs b " .
            "where ((b.user_id = " . $usrID .
            ") and (now() between to_timestamp(valid_start_date,'YYYY-MM-DD HH24:MI:SS') " .
            "AND to_timestamp(valid_end_date,'YYYY-MM-DD HH24:MI:SS')))))) ";
    } else if ($searchIn == "Template Description") {
        $strSql = "SELECT count(1) " .
            "FROM accb.accb_trnsctn_templates_hdr a " .
            "WHERE (((Select count(y.detail_id) from accb.accb_trnsctn_templates_det y, " .
            "accb.accb_chart_of_accnts c where y.accnt_id = c.accnt_id and " .
            "y.template_id=a.template_id and c.crncy_id = " . $curid . ")=2) and (a.template_description ilike '" . loc_db_escape_string($searchWord) .
            "') AND (a.org_id = " . $orgID .
            ") and (a.template_id IN (select b.template_id from accb.accb_trnsctn_templates_usrs b " .
            "where ((b.user_id = " . $usrID .
            ") and (now() between to_timestamp(valid_start_date,'YYYY-MM-DD HH24:MI:SS') " .
            "AND to_timestamp(valid_end_date,'YYYY-MM-DD HH24:MI:SS')))))) ";
    }
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function deleteTmpltTrnsDetLn($valLnid, $docNum)
{
    $delSQL = "DELETE FROM accb.accb_trnsctn_templates_det WHERE detail_id = " . $valLnid;
    $affctd1 = execUpdtInsSQL($delSQL, "Desc:" . $docNum);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Template Transaction Line(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteTmpltTrns($valLnid, $docNum)
{
    $delSQL = "DELETE FROM accb.accb_trnsctn_templates_det WHERE template_id = " . $valLnid;
    $affctd1 = execUpdtInsSQL($delSQL, "Desc:" . $docNum);
    $delSQL = "DELETE FROM accb.accb_trnsctn_templates_hdr WHERE template_id = " . $valLnid;
    $affctd2 = execUpdtInsSQL($delSQL, "Desc:" . $docNum);
    if ($affctd2 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Template Transaction Line(s)!";
        $dsply .= "<br/>Deleted $affctd2 Transaction Template(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getTrnsTmpltID($tmpltname, $orgid)
{
    $sqlStr = "select template_id from accb.accb_trnsctn_templates_hdr where lower(template_name) = '" .
        loc_db_escape_string($tmpltname) . "' and org_id = " . $orgid;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function getTrnsTmpltName($tmpltid)
{
    $sqlStr = "select template_name from accb.accb_trnsctn_templates_hdr where template_id = " .
        $tmpltid . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function createTmplt($orgid, $tmpltname, $tmpltdesc, $accbTrnsTmpltDocTyp, $enbld)
{
    global $usrID;
    $insSQL = "INSERT INTO accb.accb_trnsctn_templates_hdr(" .
        "template_name, template_description, created_by, " .
        "creation_date, last_update_by, last_update_date, org_id, doc_type,is_enabled) " .
        "VALUES ('" . loc_db_escape_string($tmpltname) . "', '" . loc_db_escape_string($tmpltdesc) .
        "', " . $usrID . ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " .
        $usrID . ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $orgid . ", '" . loc_db_escape_string($accbTrnsTmpltDocTyp) .
        "','" . cnvrtBoolToBitStr($enbld) .
        "')";
    return execUpdtInsSQL($insSQL);
}

function createTmpltTrns($accntid, $trnsDesc, $tmpltid, $incrsDcrs)
{
    global $usrID;
    $insSQL = "INSERT INTO accb.accb_trnsctn_templates_det(" .
        "template_id, accnt_id, increase_decrease, trnstn_desc, " .
        "created_by, creation_date, last_update_by, last_update_date) " .
        "VALUES (" . $tmpltid . ", " . $accntid . ", '" . loc_db_escape_string($incrsDcrs) . "', '" .
        loc_db_escape_string($trnsDesc) . "', " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'))";
    return execUpdtInsSQL($insSQL);
}

function createTmpltUsr($userid, $tmpltid, $strtDte, $endDte)
{
    global $usrID;
    if ($strtDte != "") {
        $strtDte = cnvrtDMYTmToYMDTm($strtDte);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYTmToYMDTm($endDte);
    }
    $insSQL = "INSERT INTO accb.accb_trnsctn_templates_usrs(" .
        "template_id, user_id, valid_start_date, valid_end_date, " .
        "created_by, creation_date, last_update_by, last_update_date)" .
        "VALUES (" . $tmpltid . ", " . $userid . ", '" . loc_db_escape_string($strtDte) .
        "', '" . loc_db_escape_string($endDte) . "', " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'))";
    return execUpdtInsSQL($insSQL);
}

function updateTmplt($tmpltid, $tmpltname, $tmpltdesc, $accbTrnsTmpltDocTyp, $enbld)
{
    global $usrID;
    $updtSQL = "UPDATE accb.accb_trnsctn_templates_hdr " .
        "SET template_name='" . loc_db_escape_string($tmpltname) .
        "', template_description='" . loc_db_escape_string($tmpltdesc) .
        "', doc_type='" . loc_db_escape_string($accbTrnsTmpltDocTyp) .
        "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS'),is_enabled='" . cnvrtBoolToBitStr($enbld) .
        "' WHERE template_id = " . $tmpltid;
    return execUpdtInsSQL($updtSQL);
}

function updateTmpltTrns($accntid, $trnsDesc, $tmpltid, $incrsDcrs, $detid)
{
    global $usrID;
    $updtSQL = "UPDATE accb.accb_trnsctn_templates_det " .
        "SET accnt_id=" . $accntid . ", increase_decrease='" . $incrsDcrs .
        "', trnstn_desc='" . loc_db_escape_string($trnsDesc) .
        "', template_id=" . loc_db_escape_string($tmpltid) . ", last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS') WHERE detail_id=" . $detid;
    return execUpdtInsSQL($updtSQL);
}

function changeTmpltUsrVldStrDate($rowid, $inpt_date)
{
    global $usrID;
    if ($inpt_date != "") {
        $inpt_date = cnvrtDMYTmToYMDTm($inpt_date);
    }
    $updtSQL = "UPDATE accb.accb_trnsctn_templates_usrs SET valid_start_date = '" . $inpt_date .
        "', last_update_by = " . $usrID . ", last_update_date = to_char(now(), 'YYYY-MM-DD HH24:MI:SS') WHERE (row_id = " .
        $rowid . ")";
    return execUpdtInsSQL($updtSQL);
}

function changeTmpltUsrVldEndDate($rowid, $inpt_date)
{
    global $usrID;
    if ($inpt_date != "") {
        $inpt_date = cnvrtDMYTmToYMDTm($inpt_date);
    }
    $updtSQL = "UPDATE accb.accb_trnsctn_templates_usrs SET valid_end_date = '" .
        $inpt_date . "', last_update_by = " . $usrID .
        ", last_update_date = to_char(now(), 'YYYY-MM-DD HH24:MI:SS') WHERE (row_id = " .
        $rowid . ")";
    return execUpdtInsSQL($updtSQL);
}

function get_One_CaldrDet($OrgID)
{
    $strSql = "SELECT periods_hdr_id, period_hdr_name, period_hdr_desc, period_type, 
       use_periods_for_org, no_trns_wk_days_lov_nm, no_trns_dates_lov_nm, 
       org_id
  FROM accb.accb_periods_hdr a " .
        "WHERE(a.org_id = " . $OrgID . ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_Period_DetLns($searchWord, $searchIn, $offset, $limit_size, $hdrID)
{
    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Period Name") {
        $whrcls = " AND (period_det_name ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Start Date") {
        $whrcls = " AND (to_char(to_timestamp(a.period_start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "End Date") {
        $whrcls = " AND (to_char(to_timestamp(a.period_end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Status") {
        $whrcls = " AND (a.period_status ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else {
        $whrcls = " AND (a.period_status ilike '" . loc_db_escape_string($searchWord) .
            "' or period_det_name ilike '" . loc_db_escape_string($searchWord) .
            "' or to_char(to_timestamp(a.period_start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
            "' or to_char(to_timestamp(a.period_end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
            "' or a.period_status ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT period_det_id, period_hdr_id, period_det_name, 
to_char(to_timestamp(a.period_start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), 
to_char(to_timestamp(a.period_end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), 
        period_status
        FROM accb.accb_periods_det a " .
        "WHERE((a.period_hdr_id = " . $hdrID . ")" . $whrcls .
        ") ORDER BY a.period_start_date DESC LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_Period_DetLns($searchWord, $searchIn, $hdrID)
{
    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Period Name") {
        $whrcls = " AND (period_det_name ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Start Date") {
        $whrcls = " AND (to_char(to_timestamp(a.period_start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "End Date") {
        $whrcls = " AND (to_char(to_timestamp(a.period_end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Status") {
        $whrcls = " AND (a.period_status ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else {
        $whrcls = " AND (a.period_status ilike '" . loc_db_escape_string($searchWord) .
            "' or period_det_name ilike '" . loc_db_escape_string($searchWord) .
            "' or to_char(to_timestamp(a.period_start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
            "' or to_char(to_timestamp(a.period_end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
            "' or a.period_status ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }

    $strSql = "SELECT count(1) 
        FROM accb.accb_periods_det a " .
        "WHERE((a.period_hdr_id = " . $hdrID . ")" . $whrcls . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function createPeriodsHdr(
    $orgid,
    $hdrname,
    $hdrdesc,
    $prdtyp,
    $usePerds,
    $noTrnsDysLOV,
    $noTrnsDatesLOV
) {
    global $usrID;
    $insSQL = "INSERT INTO accb.accb_periods_hdr(
            period_hdr_name, period_hdr_desc, period_type, 
            created_by, creation_date, last_update_by, last_update_date, 
            use_periods_for_org, no_trns_wk_days_lov_nm, no_trns_dates_lov_nm, 
            org_id) VALUES ('" . loc_db_escape_string($hdrname) .
        "', '" . loc_db_escape_string($hdrdesc) .
        "', '" . loc_db_escape_string($prdtyp) .
        "', " . $usrID . ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), '" . cnvrtBoolToBitStr($usePerds) .
        "', '" . loc_db_escape_string($noTrnsDysLOV) .
        "', '" . loc_db_escape_string($noTrnsDatesLOV) .
        "', " . $orgid . ")";
    return execUpdtInsSQL($insSQL);
}

function updatePeriodsHdr(
    $hdrid,
    $hdrname,
    $hdrdesc,
    $prdtyp,
    $usePerds,
    $noTrnsDysLOV,
    $noTrnsDatesLOV
) {
    global $usrID;
    $updtSQL = "UPDATE accb.accb_periods_hdr SET " .
        "period_hdr_name='" . loc_db_escape_string($hdrname) .
        "', period_hdr_desc='" . loc_db_escape_string($hdrdesc) .
        "', period_type='" . loc_db_escape_string($prdtyp) .
        "', use_periods_for_org='" . cnvrtBoolToBitStr($usePerds) .
        "', last_update_by=" . $usrID . ", " .
        "last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), "
        . "no_trns_wk_days_lov_nm='" . loc_db_escape_string($noTrnsDysLOV) .
        "', no_trns_dates_lov_nm='" . loc_db_escape_string($noTrnsDatesLOV) . "' " .
        "WHERE (periods_hdr_id =" . $hdrid . ")";
    return execUpdtInsSQL($updtSQL);
}

function get_PrdDetID($hdrID, $prdNm)
{
    global $orgID;
    $strSql = "SELECT period_det_id FROM accb.accb_periods_det a, accb.accb_periods_hdr b " .
        "WHERE(a.period_hdr_id=b.periods_hdr_id and b.org_id=" . $orgID . " and a.period_hdr_id = " . $hdrID .
        " and a.period_det_name = '" . loc_db_escape_string($prdNm) . "')";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function isNwPrdDatesInUse($strDte, $endDte)
{
    global $orgID;
    $strSql = "SELECT period_det_id FROM accb.accb_periods_det a, accb.accb_periods_hdr b " .
        "WHERE((a.period_hdr_id=b.periods_hdr_id) and (b.org_id=" . $orgID . ") and (to_timestamp('" . loc_db_escape_string($strDte) .
        "','DD-Mon-YYYY HH24:MI:SS') between to_timestamp(a.period_start_date,'YYYY-MM-DD HH24:MI:SS')
       and to_timestamp(a.period_end_date,'YYYY-MM-DD HH24:MI:SS')
       or to_timestamp('" . loc_db_escape_string($endDte) .
        "','DD-Mon-YYYY HH24:MI:SS') between to_timestamp(a.period_start_date,'YYYY-MM-DD HH24:MI:SS')
       and to_timestamp(a.period_end_date,'YYYY-MM-DD HH24:MI:SS')))";

    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    return false;
}

function isNwPrdDatesInUse1($strDte, $endDte, $prdLnID)
{
    global $orgID;
    $strSql = "SELECT period_det_id FROM accb.accb_periods_det a, accb.accb_periods_hdr b  " .
        "WHERE((a.period_hdr_id=b.periods_hdr_id) and (b.org_id=" . $orgID . ") and (to_timestamp('" . loc_db_escape_string($strDte) .
        "','DD-Mon-YYYY HH24:MI:SS') between to_timestamp(a.period_start_date,'YYYY-MM-DD HH24:MI:SS')
       and to_timestamp(a.period_end_date,'YYYY-MM-DD HH24:MI:SS')
       or to_timestamp('" . loc_db_escape_string($endDte) .
        "','DD-Mon-YYYY HH24:MI:SS') between to_timestamp(a.period_start_date,'YYYY-MM-DD HH24:MI:SS')
       and to_timestamp(a.period_end_date,'YYYY-MM-DD HH24:MI:SS')) and (a.period_det_id != " . $prdLnID . "))";

    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    return false;
}

function doesNwPrdDatesMeetPrdTyp($strDte, $endDte, $prdIntrvlTyp)
{
    $strSql = "SELECT age(to_timestamp('" . loc_db_escape_string($endDte) .
        "','DD-Mon-YYYY HH24:MI:SS') + interval '10 second', to_timestamp('" . loc_db_escape_string($strDte) .
        "','DD-Mon-YYYY HH24:MI:SS')) = interval '" . $prdIntrvlTyp . "'";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0] == "t";
    }
    return false;
}

function doesNwPrdDatesMeetPrdTyp2($strDte, $endDte, $prdIntrvlTyp)
{
    $strSql = "SELECT age(to_timestamp('" . loc_db_escape_string($endDte) .
        "','DD-Mon-YYYY HH24:MI:SS') + interval '10 second', to_timestamp('" . loc_db_escape_string($strDte) .
        "','DD-Mon-YYYY HH24:MI:SS')) <= interval '" . $prdIntrvlTyp . "'";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0] == "t";
    }
    return false;
}

function createPeriodsDetLn($hdrid, $start_date, $end_date, $prdStatus, $prdNm)
{
    global $usrID;
    if ($start_date != "") {
        $start_date = cnvrtDMYTmToYMDTm($start_date);
    }
    if ($end_date != "") {
        $end_date = cnvrtDMYTmToYMDTm($end_date);
    }
    $insSQL = "INSERT INTO accb.accb_periods_det(
            period_hdr_id, period_start_date, period_end_date, 
            created_by, creation_date, last_update_by, last_update_date, 
            period_det_name, period_status) " .
        "VALUES (" . $hdrid . ", '" . $start_date . "', '" . $end_date .
        "', " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($prdNm) .
        "', '" . loc_db_escape_string($prdStatus) .
        "')";
    return execUpdtInsSQL($insSQL);
}

function updtPeriodsDetLn($prdDetLnid, $start_date, $end_date, $prdStatus, $prdNm)
{
    global $usrID;
    if ($start_date != "") {
        $start_date = cnvrtDMYTmToYMDTm($start_date);
    }
    if ($end_date != "") {
        $end_date = cnvrtDMYTmToYMDTm($end_date);
    }
    $insSQL = "UPDATE accb.accb_periods_det SET 
             period_start_date='" . $start_date .
        "', period_end_date='" . $end_date .
        "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS')"
        . ", period_det_name='" . loc_db_escape_string($prdNm) .
        "', period_status='" . loc_db_escape_string($prdStatus) .
        "' " .
        "WHERE period_det_id=" . $prdDetLnid . " ";
    return execUpdtInsSQL($insSQL);
}

function deletePeriodsDetLn($valLnid, $docNum)
{
    $strSql = "SELECT count(1) FROM accb.accb_periods_det a WHERE(a.period_det_id = " . $valLnid .
        " and a.period_status NOT IN ('Never Opened'))";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete a Period whose status is not NEVER OPENED!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM accb.accb_periods_det WHERE period_det_id = " . $valLnid;
    $affctd1 = execUpdtInsSQL($delSQL, "Desc:" . $docNum);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Accounting Period(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function updtPeriodsDetLnStatus($prdDetLnid, $prdStatus)
{
    global $usrID;
    $insSQL = "UPDATE accb.accb_periods_det SET 
             last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS')"
        . ", period_status='" . loc_db_escape_string($prdStatus) .
        "' " .
        "WHERE period_det_id=" . $prdDetLnid . " ";
    return execUpdtInsSQL($insSQL);
}

function areTherePrvsUnclsdPrds($hdrID, $curprdStrtDte)
{
    $strSql = "SELECT a.period_det_id 
       FROM accb.accb_periods_det a 
       WHERE((a.period_hdr_id = " . $hdrID .
        ") and (to_timestamp('" . $curprdStrtDte .
        "','YYYY-MM-DD HH24:MI:SS') 
        > to_timestamp(a.period_end_date,'YYYY-MM-DD HH24:MI:SS')) and (a.period_status !='Closed'))";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    return false;
}

function get_TrnsCntBtwnDtes($orgID, $dte1, $dte2)
{
    $strSql = "SELECT count(a.transctn_id)
      FROM (accb.accb_trnsctn_details a LEFT OUTER JOIN accb.accb_trnsctn_batches c ON a.batch_id = c.batch_id) " .
        "LEFT OUTER JOIN accb.accb_chart_of_accnts b on a.accnt_id = b.accnt_id " .
        "WHERE((b.org_id = " . $orgID . ") and (to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS') <= to_timestamp('" . $dte2 .
        "','YYYY-MM-DD HH24:MI:SS')) and (to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS') >= to_timestamp('" . $dte1 .
        "','YYYY-MM-DD HH24:MI:SS')))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_TrnsCntB4Dte($orgID, $dte1, $isposted)
{
    $strSql = "SELECT count(a.transctn_id)
      FROM (accb.accb_trnsctn_details a LEFT OUTER JOIN accb.accb_trnsctn_batches c ON a.batch_id = c.batch_id) " .
        "LEFT OUTER JOIN accb.accb_chart_of_accnts b on a.accnt_id = b.accnt_id " .
        "WHERE((b.org_id = " . $orgID . ") and (a.trns_status = '" . cnvrtBoolToBitStr($isposted) .
        "') and (to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS') <= to_timestamp('" . $dte1 .
        "','YYYY-MM-DD HH24:MI:SS'))) ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_UnImprtdPayTrns($orgID, $dte1)
{
    $strSql = "select count(a.interface_id) from pay.pay_gl_interface a, accb.accb_chart_of_accnts b 
      where ((a.accnt_id = b.accnt_id) and (a.gl_batch_id <=0) and (b.org_id = " . $orgID .
        ") and (to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS') <= to_timestamp('" . $dte1 . "','YYYY-MM-DD HH24:MI:SS')))";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function actOnPeriodStatus($prddetID)
{
    ini_set('max_execution_time', 1200);
    global $usrID;
    global $orgID;
    $msg = "";
    $curStatus = getGnrlRecNm("accb.accb_periods_det", "period_det_id", "period_status", $prddetID);
    $lstPrdCloseDte = getLastPrdClseDate();
    $lstPrdCloseDteCnvtd = cnvrtDMYTmToYMDTm($lstPrdCloseDte);
    $prdStrDte = getGnrlRecNm("accb.accb_periods_det", "period_det_id", "period_start_date", $prddetID);
    $prdEndDte = getGnrlRecNm("accb.accb_periods_det", "period_det_id", "period_end_date", $prddetID);
    $isAdjstmntPrd = doesNwPrdDatesMeetPrdTyp2(cnvrtYMDTmToDMYTm($prdStrDte), cnvrtYMDTmToDMYTm($prdEndDte), "18 second");
    $periodHdrID = (int) getGnrlRecNm("accb.accb_periods_det", "period_det_id", "period_hdr_id", $prddetID);
    /*if($isAdjstmntPrd===true){
        $lstPrdCloseDte = $prdStrDte;
    }*/
    if ($curStatus == "Never Opened") {
        //Update period status to Open
        $prdStrDte = getGnrlRecNm("accb.accb_periods_det", "period_det_id", "period_start_date", $prddetID);
        $prdEndDte = getGnrlRecNm("accb.accb_periods_det", "period_det_id", "period_end_date", $prddetID);
        if ($prdStrDte <= $lstPrdCloseDteCnvtd && $isAdjstmntPrd === false) {
            return "The start date is in a period that has been <br/>closed already by a background program!";
        }
        updtPeriodsDetLnStatus($prddetID, "Open");
        return "Period Successfully Opened!";
    } else if ($curStatus == "Open") {
        $prdStrDte = getGnrlRecNm("accb.accb_periods_det", "period_det_id", "period_start_date", $prddetID);
        $prdEndDte = getGnrlRecNm("accb.accb_periods_det", "period_det_id", "period_end_date", $prddetID);
        if (areTherePrvsUnclsdPrds($periodHdrID, $prdStrDte) === true) {
            return "There are unclosed periods (or Adjustment Periods) before this period!<br/> Please close all such periods first!";
        } else {
            $trnsCntBtwn = get_TrnsCntBtwnDtes($orgID, $prdStrDte, $prdEndDte);
            if ($trnsCntBtwn <= 0) {
                //Update period status to Never Opened
                if ($prdStrDte <= $lstPrdCloseDteCnvtd && $isAdjstmntPrd === false) {
                    return "The start date is in a period that has been <br/>closed already by a background program!";
                }
                updtPeriodsDetLnStatus($prddetID, "Never Opened");
                return "Period Successfully Deactivated!";
            }
        }
        if ($prdEndDte <= $lstPrdCloseDteCnvtd && $isAdjstmntPrd === false) {
            return "The period ending on this date has been <br/>closed already by a background program!" . $prdEndDte;
        }
        $unimprtd = get_UnImprtdPayTrns($orgID, $prdEndDte);
        if ($unimprtd > 0) {
            return "There are " . $unimprtd . " yet to be imported transactions before this <br/>period's end date in the Internal Payments Module!<br/> Please Send all such transactions to GL first!";
        }

        $unpstedCnt = get_TrnsCntB4Dte($orgID, $prdEndDte, false);
        if ($unpstedCnt > 0) {
            return "There are " . $unpstedCnt . " unposted transactions before this period's end date!<br/> Please DELETE or POST all such transactions first!";
        }
        $strSql = "select accb.close_period('" . $prdEndDte . "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $orgID . ", -1)";
        $result = executeSQLNoParams($strSql);
        while ($row = loc_db_fetch_array($result)) {
            $msg = $row[0];
            return $msg;
        }
        return "ERROR:";
    } else if ($curStatus == "Closed") {
        $prdStrDte = getGnrlRecNm("accb.accb_periods_det", "period_det_id", "period_start_date", $prddetID);
        $prdEndDte = getGnrlRecNm("accb.accb_periods_det", "period_det_id", "period_end_date", $prddetID);
        $isprdEndDtePstd = getGnrlRecNm2(
            "accb.accb_period_close_dates",
            "period_close_date",
            "accb.is_gl_batch_pstd(gl_batch_id)",
            $prdEndDte
        );
        if ($prdEndDte <= $lstPrdCloseDteCnvtd || $isAdjstmntPrd === true) {
            if ($isprdEndDtePstd == "0") {
                $strSql = "select accb.rvrs_period_close('" . $prdEndDte . "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $orgID . ", -1)";
                $result = executeSQLNoParams($strSql);
                while ($row = loc_db_fetch_array($result)) {
                    $msg = $row[0];
                    return $msg;
                }
                return "ERROR:";
            } else {
                $strSql = "select accb.rvrs_pstd_period_close('" . $prdEndDte . "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $orgID . ", -1)";
                $result = executeSQLNoParams($strSql);
                while ($row = loc_db_fetch_array($result)) {
                    $msg = $row[0];
                    return $msg;
                }
                return "ERROR:";
            }
        } else {
            return "Only the Last Closed Period can be Reversed!";
        }
    } else {
        return "Invalid Current Status!";
    }
}

function get_AssetsHdr($searchWord, $searchIn, $offset, $limit_size, $orgID, $shwNonZeroOnly)
{
    $strSql = "";
    $whrcls = "";
    $nonZeroCls = "";
    if ($shwNonZeroOnly) {
        $nonZeroCls = " AND (round(accb.get_asset_trns_typ_amnt(a.asset_id,'1Initial Value')
+ accb.get_asset_trns_typ_amnt(a.asset_id,'3Appreciate Asset')
- accb.get_asset_trns_typ_amnt(a.asset_id,'2Depreciate Asset')
- accb.get_asset_trns_typ_amnt(a.asset_id,'4Retire Asset'),2)>0)";
    }
    if ($searchIn == "Asset Code/Tag/Serial") {
        $whrcls = " and (a.asset_code_name ilike '" . loc_db_escape_string($searchWord) .
            "' or a.tag_number ilike '" . loc_db_escape_string($searchWord) .
            "' or a.serial_number ilike '" . loc_db_escape_string($searchWord) .
            "' or a.barcode ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Asset Description") {
        $whrcls = " and (a.asset_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Classification/Category") {
        $whrcls = " and (a.asset_classification ilike '" . loc_db_escape_string($searchWord) .
            "' or a.asset_category ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Location") {
        $whrcls = " and (org.get_div_name(a.asset_div_grp_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or org.get_site_name(a.asset_site_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or a.asset_building_loc ilike '" . loc_db_escape_string($searchWord) .
            "' or a.asset_room_no ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Caretaker") {
        $whrcls = " and (prs.get_prsn_name(a.asset_caretaker) || ' ' || prs.get_prsn_loc_id(a.asset_caretaker) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else {
        $whrcls = " and (prs.get_prsn_name(a.asset_caretaker) || ' ' || prs.get_prsn_loc_id(a.asset_caretaker) ilike '" . loc_db_escape_string($searchWord) .
            "' or org.get_div_name(a.asset_div_grp_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or org.get_site_name(a.asset_site_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or a.asset_building_loc ilike '" . loc_db_escape_string($searchWord) .
            "' or a.asset_room_no ilike '" . loc_db_escape_string($searchWord) .
            "' or a.asset_classification ilike '" . loc_db_escape_string($searchWord) .
            "' or a.asset_category ilike '" . loc_db_escape_string($searchWord) .
            "' or a.asset_desc ilike '" . loc_db_escape_string($searchWord) .
            "' or a.asset_code_name ilike '" . loc_db_escape_string($searchWord) .
            "' or a.tag_number ilike '" . loc_db_escape_string($searchWord) .
            "' or a.serial_number ilike '" . loc_db_escape_string($searchWord) .
            "' or a.barcode ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $strSql = "SELECT a.asset_id, a.asset_code_name, 
        a.asset_desc, a.asset_classification, a.asset_category, 
        org.get_div_name(a.asset_div_grp_id) ast_div_grp,
  org.get_site_code_desc(a.asset_site_id) ast_site,
  a.asset_building_loc, a.asset_room_no, prs.get_prsn_name(a.asset_caretaker) full_nm,
  round(accb.get_asset_trns_typ_amnt(a.asset_id,'1Initial Value'),2) ttl_ast_val,
  round(accb.get_asset_trns_typ_amnt(a.asset_id,'2Depreciate Asset'),2) ttl_ast_drpct,
  round(accb.get_asset_trns_typ_amnt(a.asset_id,'1Initial Value')
+ accb.get_asset_trns_typ_amnt(a.asset_id,'3Appreciate Asset')
- accb.get_asset_trns_typ_amnt(a.asset_id,'2Depreciate Asset')
- accb.get_asset_trns_typ_amnt(a.asset_id,'4Retire Asset'),2) net_book_val,
  to_char(to_timestamp(a.asset_life_start_date, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') asset_life_start_date, 
  to_char(to_timestamp(a.asset_life_end_date, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') asset_life_end_date, 
  (extract('years' from age(now(), to_timestamp(a.asset_life_start_date, 'YYYY-MM-DD HH24:MI:SS'))) || ' yr(s) ' 
              || extract('months' from age(now(), to_timestamp(a.asset_life_start_date, 'YYYY-MM-DD HH24:MI:SS'))) || ' mon(s) ' 
              || extract('days' from age(now(), to_timestamp(a.asset_life_start_date, 'YYYY-MM-DD HH24:MI:SS'))) || ' day(s) ') asset_age, 
              a.enbl_auto_dprctn 
        FROM accb.accb_fa_assets_rgstr a 
        WHERE((a.org_id = " . $orgID . ")" . $whrcls . $nonZeroCls .
        ") ORDER BY a.asset_id DESC LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_AssetsHdr($searchWord, $searchIn, $orgID, $shwNonZeroOnly)
{
    $strSql = "";
    $whrcls = "";
    $nonZeroCls = "";
    if ($shwNonZeroOnly) {
        $nonZeroCls = " AND (round(accb.get_asset_trns_typ_amnt(a.asset_id,'1Initial Value')
+ accb.get_asset_trns_typ_amnt(a.asset_id,'3Appreciate Asset')
- accb.get_asset_trns_typ_amnt(a.asset_id,'2Depreciate Asset')
- accb.get_asset_trns_typ_amnt(a.asset_id,'4Retire Asset'),2)>0)";
    }
    if ($searchIn == "Asset Code/Tag/Serial") {
        $whrcls = " and (a.asset_code_name ilike '" . loc_db_escape_string($searchWord) .
            "' or a.tag_number ilike '" . loc_db_escape_string($searchWord) .
            "' or a.serial_number ilike '" . loc_db_escape_string($searchWord) .
            "' or a.barcode ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Asset Description") {
        $whrcls = " and (a.asset_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Classification/Category") {
        $whrcls = " and (a.asset_classification ilike '" . loc_db_escape_string($searchWord) .
            "' or a.asset_category ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Location") {
        $whrcls = " and (org.get_div_name(a.asset_div_grp_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or org.get_site_name(a.asset_site_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or a.asset_building_loc ilike '" . loc_db_escape_string($searchWord) .
            "' or a.asset_room_no ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Caretaker") {
        $whrcls = " and (prs.get_prsn_name(a.asset_caretaker) || ' ' || prs.get_prsn_loc_id(a.asset_caretaker) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else {
        $whrcls = " and (prs.get_prsn_name(a.asset_caretaker) || ' ' || prs.get_prsn_loc_id(a.asset_caretaker) ilike '" . loc_db_escape_string($searchWord) .
            "' or org.get_div_name(a.asset_div_grp_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or org.get_site_name(a.asset_site_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or a.asset_building_loc ilike '" . loc_db_escape_string($searchWord) .
            "' or a.asset_room_no ilike '" . loc_db_escape_string($searchWord) .
            "' or a.asset_classification ilike '" . loc_db_escape_string($searchWord) .
            "' or a.asset_category ilike '" . loc_db_escape_string($searchWord) .
            "' or a.asset_desc ilike '" . loc_db_escape_string($searchWord) .
            "' or a.asset_code_name ilike '" . loc_db_escape_string($searchWord) .
            "' or a.tag_number ilike '" . loc_db_escape_string($searchWord) .
            "' or a.serial_number ilike '" . loc_db_escape_string($searchWord) .
            "' or a.barcode ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT count(1) 
        FROM accb.accb_fa_assets_rgstr a 
        WHERE((a.org_id = " . $orgID . ")" . $whrcls . $nonZeroCls . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function getAssetTrnsTypeSum($assetID, $trnsType)
{
    $strSql = "SELECT accb.get_asset_trns_typ_amnt(" . $assetID . ",'" . loc_db_escape_string($trnsType) . "')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function updtAssetTrnsGLBatch($asstTrnsID, $glBatchID)
{
    global $usrID;
    $updtSQL = "UPDATE accb.accb_fa_asset_trns SET " .
        "gl_batch_id=" . $glBatchID .
        ", last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (asset_trns_id = " .
        $asstTrnsID . ")";
    return execUpdtInsSQL($updtSQL);
}

function computeCrrntAge($dateOB)
{
    if ($dateOB == "") {
        return "";
    }

    $strSql = "SELECT extract('years' from age(now(), to_timestamp('" . loc_db_escape_string($dateOB) . "', 'DD-Mon-YYYY'))) || ' yr(s) ' " .
        "|| extract('months' from age(now(), to_timestamp('" . loc_db_escape_string($dateOB) . "', 'DD-Mon-YYYY'))) || ' mon(s) ' " .
        "|| extract('days' from age(now(), to_timestamp('" . loc_db_escape_string($dateOB) . "', 'DD-Mon-YYYY'))) || ' day(s) ' ";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function computeLifeSpan($strtDte, $endDte)
{
    if ($endDte == "") {
        $endDte = "31-Dec-4000 23:59:59";
    }
    if ($strtDte == "" || $endDte == "") {
        return "";
    }
    $strSql = "SELECT extract('years' from age(to_timestamp('" . loc_db_escape_string($endDte) . "', 'DD-Mon-YYYY HH24:MI:SS'), to_timestamp('" . loc_db_escape_string($strtDte) . "', 'DD-Mon-YYYY HH24:MI:SS'))) || ' yr(s) ' " .
        "|| extract('months' from age(to_timestamp('" . loc_db_escape_string($endDte) . "', 'DD-Mon-YYYY HH24:MI:SS'), to_timestamp('" . loc_db_escape_string($strtDte) . "', 'DD-Mon-YYYY HH24:MI:SS'))) || ' mon(s) ' " .
        "|| extract('days' from age(to_timestamp('" . loc_db_escape_string($endDte) . "', 'DD-Mon-YYYY HH24:MI:SS'), to_timestamp('" . loc_db_escape_string($strtDte) . "', 'DD-Mon-YYYY HH24:MI:SS'))) || ' day(s) ' ";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function get_One_AssetPMStps($hdrID)
{
    $strSql = "SELECT asset_pm_stp_id, measurement_type, uom, max_daily_net_fig_allwd, 
       cmltv_fig_for_srvcng 
  FROM accb.accb_fa_assets_pm_stps a " .
        "WHERE((a.asset_id = " . $hdrID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_AssetHdr($hdrID)
{
    $strSql = "SELECT asset_id, asset_code_name, asset_desc, asset_classification, 
       asset_category, asset_div_grp_id, org.get_div_name(asset_div_grp_id), 
       asset_site_id, org.get_site_name(asset_site_id), asset_building_loc, 
       asset_room_no, asset_caretaker, 
       prs.get_prsn_name(asset_caretaker) || ' (' || prs.get_prsn_loc_id(asset_caretaker) || ')' fullnm, 
       tag_number, serial_number, barcode, 
       to_char(to_timestamp(asset_life_start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') startdte, 
       to_char(to_timestamp(asset_life_end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') enddte, 
       asset_accnt_id, accb.get_accnt_num(asset_accnt_id) || '.' || accb.get_accnt_name(asset_accnt_id) assetacc,
       dpr_aprc_accnt_id, accb.get_accnt_num(dpr_aprc_accnt_id) || '.' || accb.get_accnt_name(dpr_aprc_accnt_id) depreacc,
       expns_rvnu_accnt_id, accb.get_accnt_num(expns_rvnu_accnt_id) || '.' || accb.get_accnt_name(expns_rvnu_accnt_id) expnsacc,
       inv_item_id, inv.get_invitm_name(inv_item_id), 
       sql_formula, asset_salvage_value, enbl_auto_dprctn,
       rvnu_accnt_id, accb.get_accnt_num(rvnu_accnt_id) || '.' || accb.get_accnt_name(rvnu_accnt_id) revnuacc,
       mntnc_exp_accnt_id, accb.get_accnt_num(mntnc_exp_accnt_id) || '.' || accb.get_accnt_name(mntnc_exp_accnt_id) mntncacc
  FROM accb.accb_fa_assets_rgstr a " .
        "WHERE((a.asset_id = " . $hdrID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}


function get_One_AssetAccnts($hdrID)
{
    $strSql = "SELECT asset_accnt_id, accb.get_accnt_num(asset_accnt_id) || '.' || accb.get_accnt_name(asset_accnt_id) assetacc,
       dpr_aprc_accnt_id, accb.get_accnt_num(dpr_aprc_accnt_id) || '.' || accb.get_accnt_name(dpr_aprc_accnt_id) depreacc,
       expns_rvnu_accnt_id, accb.get_accnt_num(expns_rvnu_accnt_id) || '.' || accb.get_accnt_name(expns_rvnu_accnt_id) expnsacc,
        round(accb.get_asset_trns_typ_amnt(a.asset_id,'1Initial Value')
        + accb.get_asset_trns_typ_amnt(a.asset_id,'3Appreciate Asset')
        - accb.get_asset_trns_typ_amnt(a.asset_id,'2Depreciate Asset')
        - accb.get_asset_trns_typ_amnt(a.asset_id,'4Retire Asset'),2) net_book_val,
       rvnu_accnt_id, accb.get_accnt_num(rvnu_accnt_id) || '.' || accb.get_accnt_name(rvnu_accnt_id) revnuacc,
       mntnc_exp_accnt_id, accb.get_accnt_num(mntnc_exp_accnt_id) || '.' || accb.get_accnt_name(mntnc_exp_accnt_id) mntncacc,
            inv_item_id, inv.get_invitm_name(inv_item_id), sql_formula, asset_salvage_value, enbl_auto_dprctn
  FROM accb.accb_fa_assets_rgstr a " .
        "WHERE((a.asset_id = " . $hdrID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_AssetHdrNTrns($org_ID, $lmit)
{
    $extrWhr = "";
    if ($lmit >= 0) {
        $extrWhr = " LIMIT " . $lmit . " OFFSET 0";
    } else if ($lmit < 0) {
        $extrWhr = "";
    }

    $strSql = "SELECT a.asset_code_name, a.asset_desc, a.asset_classification, a.asset_category, 
       a.tag_number, a.serial_number, a.barcode, org.get_div_name(a.asset_div_grp_id), 
       org.get_site_name(a.asset_site_id), a.asset_building_loc, a.asset_room_no, prs.get_prsn_loc_id(a.asset_caretaker), 
       inv.get_invitm_name(a.inv_item_id), accb.get_accnt_num(a.asset_accnt_id) assetacc,
       accb.get_accnt_num(a.dpr_aprc_accnt_id) depreacc,
       accb.get_accnt_num(a.expns_rvnu_accnt_id) expnsacc,
       accb.get_accnt_num(a.rvnu_accnt_id) rvnuacc,
       accb.get_accnt_num(a.mntnc_exp_accnt_id) mntncsacc,
       to_char(to_timestamp(a.asset_life_start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') startdte, 
       to_char(to_timestamp(a.asset_life_end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') enddte, 
       a.asset_salvage_value, (CASE WHEN a.enbl_auto_dprctn = '1' THEN 'YES' ELSE 'NO' END) auto_dep, 
       a.sql_formula, b.trns_type, b.line_desc,b.trns_amount, gst.get_pssbl_val(b.entrd_curr_id),
       b.incrs_dcrs1, accb.get_accnt_num(b.cost_accnt_id), b.incrs_dcrs2, accb.get_accnt_num(b.bals_leg_accnt_id),
       to_char(to_timestamp(b.trns_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), b.func_curr_rate,
       a.asset_accnt_id,a.dpr_aprc_accnt_id,a.expns_rvnu_accnt_id, a.inv_item_id, a.asset_id, a.asset_div_grp_id, 
       a.asset_caretaker, a.asset_site_id 
       FROM accb.accb_fa_assets_rgstr a
       LEFT OUTER JOIN accb.accb_fa_asset_trns b ON (a.asset_id = b.asset_id) 
       WHERE a.org_id = $org_ID ORDER BY a.asset_code_name, b.trns_type" . $extrWhr;

    $result = executeSQLNoParams($strSql);
    return $result;
}

function getAccbDivGrpID($divName, $inOrgID)
{
    $strSQL = "SELECT div_id FROM org.org_divs_groups WHERE lower(div_code_name) = lower('"
        . loc_db_escape_string($divName) . "') and org_id = " . $inOrgID;
    $result = executeSQLNoParams($strSQL);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
    //$conn
}

function getAccbSiteID($sitename, $orgid)
{
    $sqlStr = "select location_id from org.org_sites_locations where lower(location_code_name) = '" .
        loc_db_escape_string($sitename) . "' and org_id = " . $orgid;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getAccbINVItmID($itemNm, $orgid)
{
    $sqlStr = "select item_id from inv.inv_itm_list where lower(trim(item_code)) = '" .
        loc_db_escape_string(strtolower($itemNm)) . "' and org_id = " . $orgid;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getAccbAccntId($accnt_num, $orgid)
{
    $sqlStr = "select accnt_id from accb.accb_chart_of_accnts where lower(trim(accnt_num)) = '" .
        loc_db_escape_string(strtolower($accnt_num)) . "' and org_id = " . $orgid;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getNewAssetLnID()
{
    $strSql = "select nextval('accb.accb_fa_asset_trns_asset_trns_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getNewAssetPMStpID()
{
    $strSql = "select nextval('accb.accb_fa_assets_pm_stps_asset_pm_stp_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getNewAssetPMID()
{
    $strSql = "select nextval('accb.accb_fa_assets_pm_recs_asset_pm_rec_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getAssetTrnsID($trnsType, $trnsDte, $trnsDesc)
{
    if ($trnsDte != "") {
        $trnsDte = substr(cnvrtDMYTmToYMDTm($trnsDte), 0, 10);
    }
    $strSql = "select asset_trns_id from accb.accb_fa_asset_trns where trns_type='"
        . loc_db_escape_string($trnsType) . "' and trns_date like '"
        . loc_db_escape_string($trnsDte) . "%' and line_desc ilike '" . loc_db_escape_string($trnsDesc) . "'";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createPM($pmID, $measmtTyp, $uom, $recDate, $strtFig, $endFig, $isPmDone, $pmActnTkn, $cmmnts, $assetID)
{
    global $usrID;
    if ($recDate != "") {
        $recDate = cnvrtDMYTmToYMDTm($recDate);
    }
    $insSQL = "INSERT INTO accb.accb_fa_assets_pm_recs(
            asset_pm_rec_id, measurement_type, uom, record_date, starting_fig, 
            ending_fig, is_pm_done, exact_pm_action_done, comments_remarks, 
            created_by, creation_date, last_update_by, last_update_date, 
            asset_id) VALUES (" . $pmID .
        ", '" . loc_db_escape_string($measmtTyp) .
        "', '" . loc_db_escape_string($uom) .
        "', '" . loc_db_escape_string($recDate) .
        "', " . $strtFig .
        ", " . $endFig .
        ", '" . cnvrtBoolToBitStr($isPmDone) .
        "', '" . loc_db_escape_string($pmActnTkn) .
        "', '" . loc_db_escape_string($cmmnts) .
        "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $assetID . ")";
    return execUpdtInsSQL($insSQL);
}

function updatePM($pmID, $measmtTyp, $uom, $recDate, $strtFig, $endFig, $isPmDone, $pmActnTkn, $cmmnts, $assetID)
{
    global $usrID;
    if ($recDate != "") {
        $recDate = cnvrtDMYTmToYMDTm($recDate);
    }
    $updtSQL = "UPDATE accb.accb_fa_assets_pm_recs
   SET measurement_type='" . loc_db_escape_string($measmtTyp) .
        "', uom='" . loc_db_escape_string($uom) .
        "', record_date='" . loc_db_escape_string($recDate) .
        "', starting_fig=" . $strtFig .
        ", ending_fig=" . $endFig .
        ", is_pm_done='" . cnvrtBoolToBitStr($isPmDone) .
        "', exact_pm_action_done='" . loc_db_escape_string($pmActnTkn) .
        "', comments_remarks='" . loc_db_escape_string($cmmnts) .
        "', created_by=" . $usrID .
        ", creation_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), asset_id=" . $assetID .
        " WHERE asset_pm_rec_id = " . $pmID;
    return execUpdtInsSQL($updtSQL);
}

function createPMStp($pmStpID, $measmtTyp, $uom, $mxFigAllwd, $cumFigForPM, $assetID)
{
    global $usrID;
    $insSQL = "INSERT INTO accb.accb_fa_assets_pm_stps(
            asset_pm_stp_id, measurement_type, uom, max_daily_net_fig_allwd, 
            cmltv_fig_for_srvcng, created_by, creation_date, last_update_by, 
            last_update_date, asset_id) " .
        "VALUES (" . $pmStpID . ", '" . loc_db_escape_string($measmtTyp) .
        "', '" . loc_db_escape_string($uom) .
        "', " . $mxFigAllwd .
        ", " . $cumFigForPM .
        ", " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $assetID . ")";
    return execUpdtInsSQL($insSQL);
}

function updatePMStp($pmStpID, $measmtTyp, $uom, $mxFigAllwd, $cumFigForPM, $assetID)
{
    global $usrID;
    $updtSQL = "UPDATE accb.accb_fa_assets_pm_stps
            SET measurement_type='" . loc_db_escape_string($measmtTyp) .
        "', uom='" . loc_db_escape_string($uom) .
        "', max_daily_net_fig_allwd=" . $mxFigAllwd .
        ", cmltv_fig_for_srvcng=" . $cumFigForPM .
        ", last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE asset_pm_stp_id = " . $pmStpID;
    return execUpdtInsSQL($updtSQL);
}

function createAssetHdr(
    $orgid,
    $strtDte,
    $enddte,
    $assetNum,
    $assetClsf,
    $assetDesc,
    $assetCtgry,
    $divGrpID,
    $siteID,
    $bldngLoc,
    $roomNum,
    $assetPrsn,
    $tagNum,
    $serialNum,
    $barCode,
    $assetAccnt,
    $deprAccnt,
    $expnsAccnt,
    $invItmID,
    $sqlFormula,
    $salvageVal,
    $autoDprct,
    $rvnuAcntID,
    $mntncsAcntID
) {
    global $usrID;
    if ($strtDte != "") {
        $strtDte = cnvrtDMYTmToYMDTm($strtDte);
    }
    if ($enddte == "") {
        $enddte = "31-Dec-4000 23:59:59";
    }
    if ($enddte != "") {
        $enddte = cnvrtDMYTmToYMDTm($enddte);
    }

    $insSQL = "INSERT INTO accb.accb_fa_assets_rgstr(
            asset_code_name, asset_desc, asset_classification, 
            asset_category, asset_div_grp_id, asset_site_id, asset_building_loc, 
            asset_room_no, asset_caretaker, tag_number, serial_number, barcode, 
            asset_life_start_date, asset_life_end_date, asset_accnt_id, dpr_aprc_accnt_id, 
            expns_rvnu_accnt_id, created_by, creation_date, last_update_by, 
            last_update_date, inv_item_id, sql_formula, asset_salvage_value, 
            org_id, enbl_auto_dprctn,rvnu_accnt_id,mntnc_exp_accnt_id) " .
        "VALUES ('" . loc_db_escape_string($assetNum) .
        "', '" . loc_db_escape_string($assetDesc) .
        "', '" . loc_db_escape_string($assetClsf) .
        "', '" . loc_db_escape_string($assetCtgry) .
        "', " . $divGrpID .
        ", " . $siteID .
        ", '" . loc_db_escape_string($bldngLoc) .
        "', '" . loc_db_escape_string($roomNum) .
        "', " . $assetPrsn .
        ", '" . loc_db_escape_string($tagNum) .
        "', '" . loc_db_escape_string($serialNum) .
        "', '" . loc_db_escape_string($barCode) .
        "', '" . loc_db_escape_string($strtDte) .
        "', '" . loc_db_escape_string($enddte) .
        "', " . $assetAccnt .
        ", " . $deprAccnt .
        ", " . $expnsAccnt .
        ", " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $invItmID .
        ", '" . loc_db_escape_string($sqlFormula) .
        "', " . $salvageVal .
        ", " . $orgid . ", '" . cnvrtBoolToBitStr($autoDprct) . "', " . $rvnuAcntID .
        ", " . $mntncsAcntID .
        ")";
    return execUpdtInsSQL($insSQL);
}

function updtAssetHdr(
    $hdrID,
    $strtDte,
    $enddte,
    $assetNum,
    $assetClsf,
    $assetDesc,
    $assetCtgry,
    $divGrpID,
    $siteID,
    $bldngLoc,
    $roomNum,
    $assetPrsn,
    $tagNum,
    $serialNum,
    $barCode,
    $assetAccnt,
    $deprAccnt,
    $expnsAccnt,
    $invItmID,
    $sqlFormula,
    $salvageVal,
    $autoDprct,
    $rvnuAcntID,
    $mntncsAcntID
) {
    global $usrID;
    if ($strtDte != "") {
        $strtDte = cnvrtDMYTmToYMDTm($strtDte);
    }
    if ($enddte == "") {
        $enddte = "31-Dec-4000 23:59:59";
    }
    if ($enddte != "") {
        $enddte = cnvrtDMYTmToYMDTm($enddte);
    }
    $insSQL = "UPDATE accb.accb_fa_assets_rgstr 
            SET asset_code_name = '" . loc_db_escape_string($assetNum) .
        "', asset_desc='" . loc_db_escape_string($assetDesc) .
        "', asset_classification='" . loc_db_escape_string($assetClsf) .
        "', asset_category='" . loc_db_escape_string($assetCtgry) .
        "', asset_div_grp_id=" . $divGrpID .
        ", asset_site_id=" . $siteID .
        ", asset_building_loc='" . loc_db_escape_string($bldngLoc) .
        "', asset_room_no='" . loc_db_escape_string($roomNum) .
        "', asset_caretaker=" . $assetPrsn .
        ", tag_number='" . loc_db_escape_string($tagNum) .
        "', serial_number='" . loc_db_escape_string($serialNum) .
        "', barcode='" . loc_db_escape_string($barCode) .
        "', asset_life_start_date='" . loc_db_escape_string($strtDte) .
        "', asset_life_end_date='" . loc_db_escape_string($enddte) .
        "', asset_accnt_id=" . $assetAccnt .
        ", dpr_aprc_accnt_id=" . $deprAccnt .
        ", expns_rvnu_accnt_id=" . $expnsAccnt .
        ", last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), inv_item_id=" . $invItmID .
        ", sql_formula='" . loc_db_escape_string($sqlFormula) .
        "', asset_salvage_value = " . $salvageVal .
        ", enbl_auto_dprctn = '" . cnvrtBoolToBitStr($autoDprct) .
        "',rvnu_accnt_id=" . $rvnuAcntID .
        ",mntnc_exp_accnt_id=" . $mntncsAcntID .
        " WHERE asset_id = " . $hdrID;
    return execUpdtInsSQL($insSQL);
}

function createAssetTrns(
    $assetTrnsID,
    $hdrID,
    $lineType,
    $lineDesc,
    $entrdAmnt,
    $entrdCurrID,
    $incrDcrs1,
    $costngID,
    $incrDcrs2,
    $blncgAccntID,
    $funcCurrID,
    $funcCurrRate,
    $funcCurrAmnt,
    $trnsDte
) {
    global $usrID;
    if ($trnsDte != "") {
        $trnsDte = cnvrtDMYTmToYMDTm($trnsDte);
    }

    $insSQL = "INSERT INTO accb.accb_fa_asset_trns(
            asset_trns_id, trns_type, incrs_dcrs1, cost_accnt_id, incrs_dcrs2, 
            bals_leg_accnt_id, created_by, creation_date, last_update_by, 
            last_update_date, asset_id, gl_batch_id, trns_date, trns_amount, 
            entrd_curr_id, func_curr_id, accnt_curr_id, func_curr_rate, accnt_curr_rate, 
            func_curr_amount, accnt_curr_amnt, line_desc) " .
        "VALUES (" . $assetTrnsID . ", '" . loc_db_escape_string($lineType) .
        "', '" . loc_db_escape_string($incrDcrs1) .
        "', " . $costngID .
        ", '" . loc_db_escape_string($incrDcrs2) .
        "', " . $blncgAccntID .
        ", " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $hdrID .
        ", -1" .
        ", '" . loc_db_escape_string($trnsDte) .
        "', " . $entrdAmnt .
        ", " . $entrdCurrID .
        ", " . $funcCurrID .
        ", " . $entrdCurrID .
        ", " . $funcCurrRate .
        ", 1" .
        ", " . $funcCurrAmnt .
        ", " . $entrdAmnt .
        ", '" . loc_db_escape_string($lineDesc) .
        "')";
    return execUpdtInsSQL($insSQL);
}

function updtAssetTrns(
    $assetTrnsID,
    $hdrID,
    $lineType,
    $lineDesc,
    $entrdAmnt,
    $entrdCurrID,
    $incrDcrs1,
    $costngID,
    $incrDcrs2,
    $blncgAccntID,
    $funcCurrID,
    $funcCurrRate,
    $funcCurrAmnt,
    $trnsDte
) {
    global $usrID;
    if ($trnsDte != "") {
        $trnsDte = cnvrtDMYTmToYMDTm($trnsDte);
    }
    $insSQL = "UPDATE accb.accb_fa_asset_trns SET 
              trns_type='" . loc_db_escape_string($lineType) .
        "', incrs_dcrs1='" . loc_db_escape_string($incrDcrs1) .
        "', cost_accnt_id=" . $costngID .
        ", incrs_dcrs2='" . loc_db_escape_string($incrDcrs2) .
        "', bals_leg_accnt_id=" . $blncgAccntID .
        ", last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), trns_date='" . loc_db_escape_string($trnsDte) .
        "', trns_amount=" . $entrdAmnt .
        ", entrd_curr_id=" . $entrdCurrID .
        ", func_curr_id=" . $funcCurrID .
        ", accnt_curr_id=" . $entrdCurrID .
        ", func_curr_rate=" . $funcCurrRate .
        ", func_curr_amount=" . $funcCurrAmnt .
        ", accnt_curr_amnt=" . $entrdAmnt .
        ", line_desc='" . loc_db_escape_string($lineDesc) .
        "' WHERE asset_trns_id = " . $assetTrnsID . " and gl_batch_id <= 0";

    return execUpdtInsSQL($insSQL);
}

function createAssetTrnsAcntng($p_AstTrnsID, $p_org_id, $p_who_rn)
{
    $strSql = "select accb.createAssetAccounting(" . $p_AstTrnsID . "," . $p_org_id . "," . $p_who_rn . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "ERROR:No Result";
}

function deleteAssetHdrNDet($valLnid, $docNum)
{
    $trnsCnt1 = 0;
    $strSql = "SELECT count(1) FROM accb.accb_fa_asset_trns a WHERE(a.asset_id = " . $valLnid . " and a.gl_batch_id>0)";
    $result1 = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if (($trnsCnt1) > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete an Asset/Investment with Accounting Created!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }

    $delSQL = "DELETE FROM accb.accb_fa_asset_trns WHERE asset_id = " . $valLnid;
    $affctd1 = execUpdtInsSQL($delSQL, $docNum);
    $delSQL = "DELETE FROM accb.accb_fa_assets_pm_stps WHERE asset_id = " . $valLnid;
    $affctd2 = execUpdtInsSQL($delSQL, $docNum);
    $delSQL = "DELETE FROM accb.accb_fa_assets_pm_recs WHERE asset_id = " . $valLnid;
    $affctd3 = execUpdtInsSQL($delSQL, $docNum);
    $delSQL = "DELETE FROM accb.accb_fa_assets_rgstr WHERE asset_id = " . $valLnid;
    $affctd4 = execUpdtInsSQL($delSQL, $docNum);

    if ($affctd4 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd4 Asset/Investment(s)!";
        $dsply .= "<br/>Deleted $affctd3 PM Record(s)!";
        $dsply .= "<br/>Deleted $affctd2 Measurement Setup(s)!";
        $dsply .= "<br/>Deleted $affctd1 Asset Transaction(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteAssetTrans($valLnid, $assetNum)
{
    $trnsCnt1 = 0;
    $strSql = "SELECT count(1) FROM accb.accb_fa_asset_trns a WHERE(a.asset_trns_id=" . $valLnid . " and a.gl_batch_id>0)";
    $result1 = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if (($trnsCnt1) > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete an Asset/Investment Transaction with Accounting Created!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM accb.accb_fa_asset_trns WHERE asset_trns_id=" . $valLnid;
    $affctd = execUpdtInsSQL($delSQL, $assetNum);
    if ($affctd > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd Asset Transaction(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteAssetPMStp($pmstpid, $assetNum)
{
    $delSQL = "DELETE FROM accb.accb_fa_assets_pm_stps WHERE asset_pm_stp_id = " . $pmstpid;
    $affctd = execUpdtInsSQL($delSQL, $assetNum);
    if ($affctd > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd Asset Measurement(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteAssetPMRecs($pmid, $assetNum)
{
    $delSQL = "DELETE FROM accb.accb_fa_assets_pm_recs WHERE asset_pm_rec_id = " . $pmid;
    $affctd = execUpdtInsSQL($delSQL, $assetNum);
    if ($affctd > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd Asset PM Record(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_AssetPMRecs($searchWord, $searchIn, $offset, $limit_size, $hdrID)
{
    /*
     * Record Date
      Measurement Type/UOM
      PM Action Taken
     * Comments/Remarks
     */
    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Measurement Type/UOM") {
        $whrcls = " and (a.measurement_type ilike '" . loc_db_escape_string($searchWord) .
            "' or a.uom ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "PM Action Taken") {
        $whrcls = " and (a.exact_pm_action_done ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Comments/Remarks") {
        $whrcls = " and (a.comments_remarks ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Record Date") {
        $whrcls = " and (to_char(to_timestamp(a.record_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $strSql = "SELECT asset_pm_rec_id, measurement_type, uom, 
        to_char(to_timestamp(record_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), 
        starting_fig, ending_fig, is_pm_done, exact_pm_action_done, comments_remarks, asset_id 
  FROM accb.accb_fa_assets_pm_recs a " .
        "WHERE((a.asset_id = " . $hdrID . ")" . $whrcls .
        ") ORDER BY record_date DESC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_TtlAssetPMRecs($searchWord, $searchIn, $hdrID)
{
    /*
     * Record Date
      Measurement Type/UOM
      PM Action Taken
     * Comments/Remarks
     */
    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Measurement Type/UOM") {
        $whrcls = " and (a.measurement_type ilike '" . loc_db_escape_string($searchWord) .
            "' or a.uom ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "PM Action Taken") {
        $whrcls = " and (a.exact_pm_action_done ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Comments/Remarks") {
        $whrcls = " and (a.comments_remarks ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Record Date") {
        $whrcls = " and (to_char(to_timestamp(a.record_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $strSql = "SELECT count(1) 
  FROM accb.accb_fa_assets_pm_recs a " .
        "WHERE((a.asset_id = " . $hdrID . ")" . $whrcls .
        ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_OneAssetPMRecs($hdrID)
{
    $strSql = "SELECT asset_pm_rec_id, measurement_type, uom, 
to_char(to_timestamp(record_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), 
starting_fig, ending_fig, is_pm_done, 
exact_pm_action_done, comments_remarks, asset_id 
  FROM accb.accb_fa_assets_pm_recs a " .
        "WHERE((a.asset_pm_rec_id = " . $hdrID . "))";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function getMxAllwdDailyFig($asstID, $measTyp, $uom)
{
    $strSql = "SELECT max_daily_net_fig_allwd 
  FROM accb.accb_fa_assets_pm_stps a " .
        "WHERE((a.asset_id = " . $asstID .
        ") and a.measurement_type = '" . loc_db_escape_string($measTyp) .
        "' and a.uom = '" . loc_db_escape_string($uom) .
        "') ORDER BY asset_pm_stp_id DESC LIMIT 1 OFFSET 0";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function getCumFigForPM($asstID, $measTyp, $uom)
{
    $strSql = "SELECT cmltv_fig_for_srvcng 
  FROM accb.accb_fa_assets_pm_stps a " .
        "WHERE((a.asset_id = " . $asstID .
        ") and a.measurement_type = '" . loc_db_escape_string($measTyp) .
        "' and a.uom = '" . loc_db_escape_string($uom) .
        "') ORDER BY asset_pm_stp_id DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function getSumPrevPMNetFigs($asstID, $measTyp, $uom, $recDate)
{
    if ($recDate != "") {
        $recDate = cnvrtDMYTmToYMDTm($recDate);
    }
    $strSql = "SELECT COALESCE(SUM(ending_fig - starting_fig),0) 
  FROM accb.accb_fa_assets_pm_recs a " .
        "WHERE((a.asset_id = " . $asstID .
        ") and a.measurement_type = '" . loc_db_escape_string($measTyp) .
        "' and a.uom = '" . loc_db_escape_string($uom) .
        "' and a.record_date>COALESCE((SELECT MAX(b.record_date) from accb.accb_fa_assets_pm_recs b where b.is_pm_done='1'),'0001-01-01 00:00:00')
            and a.record_date<='" . $recDate . "')";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_AssetTrns($searchWord, $searchIn, $offset, $limit_size, $hdrID)
{
    /*
     * Account Number/Description
      Transaction Description
      Transaction Date
     */
    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Account Number/Description") {
        $whrcls = " and (accb.get_accnt_num(a.cost_accnt_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or accb.get_accnt_num(a.bals_leg_accnt_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or accb.get_accnt_name(a.cost_accnt_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or accb.get_accnt_name(a.bals_leg_accnt_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Transaction Description") {
        $whrcls = " and (a.line_desc ilike '" . loc_db_escape_string($searchWord) .
            "' or a.trns_type ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Transaction Date") {
        $whrcls = " and (to_char(to_timestamp(trns_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $strSql = "SELECT asset_trns_id, trns_type, line_desc, trns_amount,
       entrd_curr_id, gst.get_pssbl_val(a.entrd_curr_id), 
       incrs_dcrs1, cost_accnt_id, accb.get_accnt_num(a.cost_accnt_id) || '.' || accb.get_accnt_name(a.cost_accnt_id) cost_accnt, 
       incrs_dcrs2, bals_leg_accnt_id, accb.get_accnt_num(a.bals_leg_accnt_id) || '.' || accb.get_accnt_name(a.bals_leg_accnt_id) bals_leg_accnt, 
       gl_batch_id, accb.get_gl_batch_name(a.gl_batch_id),accb.is_gl_batch_pstd(a.gl_batch_id) is_pstd,
       to_char(to_timestamp(trns_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'),        
       func_curr_id, gst.get_pssbl_val(a.func_curr_id), 
       accnt_curr_id, gst.get_pssbl_val(a.accnt_curr_id), 
       func_curr_rate, accnt_curr_rate, 
       func_curr_amount, accnt_curr_amnt
  FROM accb.accb_fa_asset_trns a " .
        "WHERE((a.asset_id = " . $hdrID . ")" . $whrcls .
        ") ORDER BY trns_type ASC, trns_date ASC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_TtlAssetTrns($searchWord, $searchIn, $hdrID)
{
    /*
     * Account Number/Description
      Transaction Description
      Transaction Date
     */
    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Account Number/Description") {
        $whrcls = " and (accb.get_accnt_num(a.cost_accnt_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or accb.get_accnt_num(a.bals_leg_accnt_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or accb.get_accnt_name(a.cost_accnt_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or accb.get_accnt_name(a.bals_leg_accnt_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Transaction Description") {
        $whrcls = " and (a.line_desc ilike '" . loc_db_escape_string($searchWord) .
            "' or a.trns_type ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Transaction Date") {
        $whrcls = " and (to_char(to_timestamp(trns_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $strSql = "SELECT count(1) 
  FROM accb.accb_fa_asset_trns a " .
        "WHERE((a.asset_id = " . $hdrID . ")" . $whrcls .
        ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_OneAssetTrns($hdrID)
{
    $strSql = "SELECT asset_trns_id, trns_type, line_desc, trns_amount,
       entrd_curr_id, gst.get_pssbl_val(a.entrd_curr_id), 
       incrs_dcrs1, cost_accnt_id, accb.get_accnt_num(a.cost_accnt_id) || '.' || accb.get_accnt_name(a.cost_accnt_id) cost_accnt, 
       incrs_dcrs2, bals_leg_accnt_id, accb.get_accnt_num(a.bals_leg_accnt_id) || '.' || accb.get_accnt_name(a.bals_leg_accnt_id) bals_leg_accnt, 
       gl_batch_id, accb.get_gl_batch_name(a.gl_batch_id),accb.is_gl_batch_pstd(a.gl_batch_id) is_pstd,
       to_char(to_timestamp(trns_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'),        
       func_curr_id, gst.get_pssbl_val(a.func_curr_id), 
       accnt_curr_id, gst.get_pssbl_val(a.accnt_curr_id), 
       func_curr_rate, accnt_curr_rate, 
       func_curr_amount, accnt_curr_amnt
  FROM accb.accb_fa_asset_trns a " .
        "WHERE((a.asset_trns_id = " . $hdrID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PyblsDocHdr($searchWord, $searchIn, $offset, $limit_size, $orgID, $shwUnpstdOnly, $shwUnpaidOnly)
{
    $strSql = "";
    $whrcls = "";
    $unpstdCls = "";
    if ($shwUnpstdOnly) {
        $unpstdCls .= " AND (accb.is_gl_batch_pstd(a.gl_batch_id)!='1' and a.approval_status IN ('Approved','Cancelled'))";
    }
    if ($shwUnpaidOnly) {
        $unpstdCls .= " AND (round(a.invoice_amount-a.amnt_paid,2)>0 and a.approval_status IN ('Approved'))";
    }
    if ($searchIn == "Document Number") {
        $whrcls = " and (a.pybls_invc_number ilike '" . loc_db_escape_string($searchWord) .
            "' or trim(to_char(a.pybls_invc_hdr_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Document Description") {
        $whrcls = " and (a.comments_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Document Classification") {
        $whrcls = " and (a.doc_tmplt_clsfctn ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Supplier Name") {
        $whrcls = " and (a.supplier_id IN (select c.cust_sup_id from 
scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
            "'))";
    } else if ($searchIn == "Supplier's Invoice Number") {
        $whrcls = " and (a.spplrs_invc_num ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Source Doc Number") {
        $whrcls = " and (trim(to_char(a.src_doc_hdr_id, '9999999999999999999999999')) 
IN (select trim(to_char(d.rcpt_id, '9999999999999999999999999')) from inv.inv_consgmt_rcpt_hdr d 
where trim(to_char(d.rcpt_id, '9999999999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "') or trim(to_char(a.src_doc_hdr_id, '9999999999999999999999999')) 
IN (select trim(to_char(e.rcpt_rtns_id, '9999999999999999999999999')) from inv.inv_consgmt_rcpt_rtns_hdr e 
where trim(to_char(e.rcpt_rtns_id, '9999999999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "') or a.src_doc_hdr_id IN (select f.pybls_invc_hdr_id from accb.accb_pybls_invc_hdr f
where f.pybls_invc_number ilike '" . loc_db_escape_string($searchWord) .
            "'))";
    } else if ($searchIn == "Approval Status") {
        $whrcls = " and (a.approval_status ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Created By") {
        $whrcls = " and (sec.get_usr_name(a.created_by) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Currency") {
        $whrcls = " and (gst.get_pssbl_val(a.invc_curr_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else {
        $whrcls = " and (a.pybls_invc_number ilike '" . loc_db_escape_string($searchWord) .
            "' or trim(to_char(a.pybls_invc_hdr_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "' or a.spplrs_invc_num ilike '" . loc_db_escape_string($searchWord) .
            "' or a.supplier_id IN (select c.cust_sup_id from 
scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
            "') or a.comments_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT pybls_invc_hdr_id, pybls_invc_number, pybls_invc_type, 
        comments_desc,gst.get_pssbl_val(a.invc_curr_id), round(a.invoice_amount,2), 
        round(a.amnt_paid,2), round(a.invoice_amount-a.amnt_paid,2), a.approval_status, scm.get_cstmr_splr_name(a.supplier_id) 
        FROM accb.accb_pybls_invc_hdr a 
        WHERE((a.org_id = " . $orgID . ")" . $whrcls . $unpstdCls .
        ") ORDER BY pybls_invc_hdr_id DESC LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_PyblsDoc($searchWord, $searchIn, $orgID, $shwUnpstdOnly, $shwUnpaidOnly)
{
    $strSql = "";
    $whrcls = "";
    $unpstdCls = "";
    if ($shwUnpstdOnly) {
        $unpstdCls .= " AND (accb.is_gl_batch_pstd(a.gl_batch_id)!='1' and a.approval_status IN ('Approved','Cancelled'))";
    }
    if ($shwUnpaidOnly) {
        $unpstdCls .= " AND (round(a.invoice_amount-a.amnt_paid,2)>0 and a.approval_status IN ('Approved'))";
    }
    if ($searchIn == "Document Number") {
        $whrcls = " and (a.pybls_invc_number ilike '" . loc_db_escape_string($searchWord) .
            "' or trim(to_char(a.pybls_invc_hdr_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Document Description") {
        $whrcls = " and (a.comments_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Document Classification") {
        $whrcls = " and (a.doc_tmplt_clsfctn ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Supplier Name") {
        $whrcls = " and (a.supplier_id IN (select c.cust_sup_id from 
scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
            "'))";
    } else if ($searchIn == "Supplier's Invoice Number") {
        $whrcls = " and (a.spplrs_invc_num ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Source Doc Number") {
        $whrcls = " and (trim(to_char(a.src_doc_hdr_id, '9999999999999999999999999')) 
IN (select trim(to_char(d.rcpt_id, '9999999999999999999999999')) from inv.inv_consgmt_rcpt_hdr d 
where trim(to_char(d.rcpt_id, '9999999999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "') or trim(to_char(a.src_doc_hdr_id, '9999999999999999999999999')) 
IN (select trim(to_char(e.rcpt_rtns_id, '9999999999999999999999999')) from inv.inv_consgmt_rcpt_rtns_hdr e 
where trim(to_char(e.rcpt_rtns_id, '9999999999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "') or a.src_doc_hdr_id IN (select f.pybls_invc_hdr_id from accb.accb_pybls_invc_hdr f
where f.pybls_invc_number ilike '" . loc_db_escape_string($searchWord) .
            "'))";
    } else if ($searchIn == "Approval Status") {
        $whrcls = " and (a.approval_status ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Created By") {
        $whrcls = " and (sec.get_usr_name(a.created_by) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Currency") {
        $whrcls = " and (gst.get_pssbl_val(a.invc_curr_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else {
        $whrcls = " and (a.pybls_invc_number ilike '" . loc_db_escape_string($searchWord) .
            "' or trim(to_char(a.pybls_invc_hdr_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "' or a.spplrs_invc_num ilike '" . loc_db_escape_string($searchWord) .
            "' or a.supplier_id IN (select c.cust_sup_id from 
scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
            "') or a.comments_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT count(1) FROM accb.accb_pybls_invc_hdr a  
        WHERE((a.org_id = " . $orgID . ")" . $whrcls . $unpstdCls .
        ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_One_PyblsInvcDocHdr($hdrID)
{
    $strSql = "SELECT pybls_invc_hdr_id, to_char(to_timestamp(pybls_invc_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), 
       created_by, sec.get_usr_name(a.created_by), pybls_invc_number, pybls_invc_type, 
       comments_desc, src_doc_hdr_id, supplier_id, scm.get_cstmr_splr_name(a.supplier_id),
       supplier_site_id, scm.get_cstmr_splr_site_name(a.supplier_site_id), 
       approval_status, next_aproval_action, invoice_amount, 
       payment_terms, src_doc_type, pymny_method_id, accb.get_pymnt_mthd_name(a.pymny_method_id), 
       amnt_paid, gl_batch_id, accb.get_gl_batch_name(a.gl_batch_id),
       spplrs_invc_num, doc_tmplt_clsfctn, invc_curr_id, gst.get_pssbl_val(a.invc_curr_id),
        event_rgstr_id, evnt_cost_category, event_doc_type, balancing_accnt_id,
        accb.get_accnt_num(a.balancing_accnt_id) || '.' || accb.get_accnt_name(a.balancing_accnt_id) balancing_accnt,
        accb.is_gl_batch_pstd(a.gl_batch_id) is_pstd, advc_pay_ifo_doc_id, advc_pay_ifo_doc_typ, next_part_payment, 
        firts_cheque_num, invc_amnt_appld_elswhr, func_curr_rate, scm.get_src_doc_num(src_doc_hdr_id,src_doc_type)  
  FROM accb.accb_pybls_invc_hdr a 
  WHERE((a.pybls_invc_hdr_id = " . $hdrID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PyblsInvcDocDet($docHdrID)
{
    $whrcls = " and (a.pybls_smmry_type !='6Grand Total' and 
a.pybls_smmry_type !='7Total Payments Made' and a.pybls_smmry_type !='8Outstanding Balance')";

    $strSql = "SELECT pybls_smmry_id, pybls_smmry_type, pybls_smmry_desc, (CASE WHEN a.pybls_smmry_type='3Discount' 
or scm.istaxwthhldng(a.code_id_behind)='1' or a.pybls_smmry_type='5Applied Prepayment'
      THEN -1 ELSE 1 END ) * pybls_smmry_amnt, 
       code_id_behind, auto_calc, incrs_dcrs1, 
       asset_expns_acnt_id, incrs_dcrs2, liability_acnt_id, appld_prepymnt_doc_id, 
       entrd_curr_id, gst.get_pssbl_val(a.entrd_curr_id), 
       func_curr_id, gst.get_pssbl_val(a.func_curr_id), 
      accnt_curr_id, gst.get_pssbl_val(a.accnt_curr_id), 
      func_curr_rate, accnt_curr_rate, 
       func_curr_amount, accnt_curr_amnt, initial_amnt_line_id, 
       ref_doc_number,
        accb.get_accnt_num(a.asset_expns_acnt_id) || '.' || accb.get_accnt_name(a.asset_expns_acnt_id) charge_accnt,
        accb.get_accnt_num(a.liability_acnt_id) || '.' || accb.get_accnt_name(a.liability_acnt_id) balancing_accnt, a.slctd_amnt_brkdwns,
        accb.get_src_doc_num(appld_prepymnt_doc_id,'Supplier Advance Payments') src_doc_num,
        scm.istaxwthhldng(code_id_behind), tax_code_id, whtax_code_id, dscnt_code_id, 
        scm.get_tax_code(tax_code_id), scm.get_tax_code(whtax_code_id), scm.get_tax_code(dscnt_code_id)
  FROM accb.accb_pybls_amnt_smmrys a " .
        "WHERE((a.src_pybls_hdr_id = " . $docHdrID . ")" . $whrcls . ") ORDER BY pybls_smmry_type ASC, pybls_smmry_id ";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function getNewPyblsLnID()
{
    $strSql = "select nextval('accb.accb_pybls_amnt_smmrys_pybls_smmry_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function createPyblsDocHdr(
    $orgid,
    $docDte,
    $docNum,
    $docType,
    $docDesc,
    $srcDocHdrID,
    $spplrID,
    $spplrSiteID,
    $apprvlStatus,
    $nxtApprvlActn,
    $invcAmnt,
    $pymntTrms,
    $srcDocType,
    $pymntMthdID,
    $amntPaid,
    $glBtchID,
    $spplrInvcNum,
    $docTmpltClsftn,
    $currID,
    $amntAppld,
    $rgstrID,
    $costCtgry,
    $evntType,
    $dfltBalsAcntID,
    $advcPayDocID,
    $advcPayDocTyp,
    $nextPartPay,
    $firstCheqNum,
    $accbPyblsInvcFuncCrncyRate
) {
    global $usrID;
    if ($docDte != "") {
        $docDte = cnvrtDMYToYMD($docDte);
    }
    $insSQL = "INSERT INTO accb.accb_pybls_invc_hdr(
            pybls_invc_date, created_by, creation_date, 
            last_update_by, last_update_date, pybls_invc_number, pybls_invc_type, 
            comments_desc, src_doc_hdr_id, supplier_id, supplier_site_id, 
            approval_status, next_aproval_action, org_id, invoice_amount, 
            payment_terms, src_doc_type, pymny_method_id, amnt_paid, gl_batch_id, 
            spplrs_invc_num, doc_tmplt_clsfctn, invc_curr_id, invc_amnt_appld_elswhr,
            event_rgstr_id, evnt_cost_category, event_doc_type, 
	balancing_accnt_id, advc_pay_ifo_doc_id, advc_pay_ifo_doc_typ, 
	next_part_payment, firts_cheque_num, func_curr_rate) " .
        "VALUES ('" . loc_db_escape_string($docDte) .
        "', " . $usrID . ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($docNum) .
        "', '" . loc_db_escape_string($docType) .
        "', '" . loc_db_escape_string($docDesc) .
        "', " . $srcDocHdrID .
        ", " . $spplrID .
        ", " . $spplrSiteID .
        ", '" . loc_db_escape_string($apprvlStatus) .
        "', '" . loc_db_escape_string($nxtApprvlActn) .
        "', " . $orgid .
        ", " . $invcAmnt .
        ", '" . loc_db_escape_string($pymntTrms) .
        "', '" . loc_db_escape_string($srcDocType) .
        "', " . $pymntMthdID .
        ", " . $amntPaid .
        ", " . $glBtchID .
        ", '" . loc_db_escape_string($spplrInvcNum) .
        "', '" . loc_db_escape_string($docTmpltClsftn) .
        "', " . $currID . ", " . $amntAppld . ", " . $rgstrID .
        ", '" . loc_db_escape_string($costCtgry) . "', '" . loc_db_escape_string($evntType) . "', " . $dfltBalsAcntID .
        ", " . $advcPayDocID .
        ", '" . loc_db_escape_string($advcPayDocTyp) . "', " . $nextPartPay .
        ", '" . loc_db_escape_string($firstCheqNum) . "'," . $accbPyblsInvcFuncCrncyRate . ")";
    return execUpdtInsSQL($insSQL);
}

function updtPyblsDocHdr(
    $hdrID,
    $docDte,
    $docNum,
    $docType,
    $docDesc,
    $srcDocHdrID,
    $spplrID,
    $spplrSiteID,
    $apprvlStatus,
    $nxtApprvlActn,
    $invcAmnt,
    $pymntTrms,
    $srcDocType,
    $pymntMthdID,
    $amntPaid,
    $glBtchID,
    $spplrInvcNum,
    $docTmpltClsftn,
    $currID,
    $amntAppld,
    $rgstrID,
    $costCtgry,
    $evntType,
    $dfltBalsAcntID,
    $advcPayDocID,
    $advcPayDocTyp,
    $nextPartPay,
    $firstCheqNum,
    $accbPyblsInvcFuncCrncyRate
) {
    global $usrID;
    if ($docDte != "") {
        $docDte = cnvrtDMYToYMD($docDte);
    }
    $insSQL = "UPDATE accb.accb_pybls_invc_hdr
       SET pybls_invc_date='" . loc_db_escape_string($docDte) .
        "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), pybls_invc_number='" . loc_db_escape_string($docNum) .
        "', pybls_invc_type='" . loc_db_escape_string($docType) .
        "', comments_desc='" . loc_db_escape_string($docDesc) .
        "', src_doc_hdr_id=" . $srcDocHdrID .
        ", supplier_id=" . $spplrID .
        ", supplier_site_id=" . $spplrSiteID .
        ", approval_status='" . loc_db_escape_string($apprvlStatus) .
        "', next_aproval_action='" . loc_db_escape_string($nxtApprvlActn) .
        "', invoice_amount=" . $invcAmnt .
        ", payment_terms='" . loc_db_escape_string($pymntTrms) .
        "', src_doc_type='" . loc_db_escape_string($srcDocType) .
        "', pymny_method_id=" . $pymntMthdID .
        ", amnt_paid=" . $amntPaid .
        ", gl_batch_id=" . $glBtchID .
        ", spplrs_invc_num='" . loc_db_escape_string($spplrInvcNum) .
        "', doc_tmplt_clsfctn='" . loc_db_escape_string($docTmpltClsftn) .
        "', invc_curr_id=" . $currID .
        ", invc_amnt_appld_elswhr=" . $amntAppld .
        ", event_rgstr_id=" . $rgstrID .
        ", evnt_cost_category='" . loc_db_escape_string($costCtgry) .
        "', event_doc_type='" . loc_db_escape_string($evntType) .
        "', balancing_accnt_id=" . $dfltBalsAcntID .
        ", advc_pay_ifo_doc_id=" . $advcPayDocID .
        ", advc_pay_ifo_doc_typ='" . loc_db_escape_string($advcPayDocTyp) .
        "', next_part_payment=" . $nextPartPay .
        ", firts_cheque_num='" . loc_db_escape_string($firstCheqNum) .
        "', func_curr_rate=" . $accbPyblsInvcFuncCrncyRate .
        " WHERE pybls_invc_hdr_id = " . $hdrID;
    return execUpdtInsSQL($insSQL);
}

function createPyblsDocDet(
    $smmryID,
    $hdrID,
    $lineType,
    $lineDesc,
    $entrdAmnt,
    $entrdCurrID,
    $codeBhnd,
    $docType,
    $autoCalc,
    $incrDcrs1,
    $costngID,
    $incrDcrs2,
    $blncgAccntID,
    $prepayDocHdrID,
    $vldyStatus,
    $orgnlLnID,
    $funcCurrID,
    $accntCurrID,
    $funcCurrRate,
    $accntCurrRate,
    $funcCurrAmnt,
    $accntCurrAmnt,
    $initAmntID,
    $ref_doc_number,
    $slctd_amnt_brkdwns,
    $ln_TaxID,
    $ln_WHTaxID,
    $ln_DscntID
) {
    $res = createPyblsDocDet1(
        $smmryID,
        $hdrID,
        $lineType,
        $lineDesc,
        $entrdAmnt,
        $entrdCurrID,
        $codeBhnd,
        $docType,
        $autoCalc,
        $incrDcrs1,
        $costngID,
        $incrDcrs2,
        $blncgAccntID,
        $prepayDocHdrID,
        $vldyStatus,
        $orgnlLnID,
        $funcCurrID,
        $accntCurrID,
        $funcCurrRate,
        $accntCurrRate,
        $funcCurrAmnt,
        $accntCurrAmnt,
        $initAmntID,
        $ref_doc_number,
        $slctd_amnt_brkdwns,
        $ln_TaxID,
        $ln_WHTaxID,
        $ln_DscntID
    );
    if ($ln_TaxID > 0 && isTaxAParent($ln_TaxID) === true) {
        $codeIDs = explode(",", trim(getTaxChildIDs($ln_TaxID), ", "));
        for ($y = 0; $y < count($codeIDs); $y++) {
            $ln_TaxID = (int) $codeIDs[$y];
            $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_TaxID);
            $dcntAMnt = getCodeAmnt($ln_DscntID, $entrdAmnt);
            $codeAmnt = getCodeAmnt($ln_TaxID, $entrdAmnt - $dcntAMnt);
            $lnSmmryLnID = getPyblsLnDetID("2Tax", $ln_TaxID, $smmryID);
            $accnts = getPyblBalncnAccnt("2Tax", $ln_TaxID, -1, -1, $docType);
            $funcCurrAmnt = $codeAmnt * $funcCurrRate;
            $accntCurrAmnt = $codeAmnt * $accntCurrRate;
            $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $entrdAmnt . ")";
            if ($lnSmmryLnID <= 0) {
                $lnSmmryLnID = getNewPyblsLnID();
                $res += createPyblsDocDet1(
                    $lnSmmryLnID,
                    $hdrID,
                    "2Tax",
                    $txlineDesc,
                    $codeAmnt,
                    $entrdCurrID,
                    $ln_TaxID,
                    $docType,
                    true,
                    $accnts[2],
                    $accnts[3],
                    $accnts[0],
                    $blncgAccntID,
                    -1,
                    $vldyStatus,
                    -1,
                    $funcCurrID,
                    $accntCurrID,
                    $funcCurrRate,
                    $accntCurrRate,
                    $funcCurrAmnt,
                    $accntCurrAmnt,
                    $smmryID,
                    $ref_doc_number,
                    "",
                    -1,
                    -1,
                    -1
                );
            } else {
                $res += updtPyblsDocDet1(
                    $lnSmmryLnID,
                    $hdrID,
                    "2Tax",
                    $txlineDesc,
                    $codeAmnt,
                    $entrdCurrID,
                    $ln_TaxID,
                    $docType,
                    true,
                    $accnts[2],
                    $accnts[3],
                    $accnts[0],
                    $blncgAccntID,
                    -1,
                    $vldyStatus,
                    -1,
                    $funcCurrID,
                    $accntCurrID,
                    $funcCurrRate,
                    $accntCurrRate,
                    $funcCurrAmnt,
                    $accntCurrAmnt,
                    $smmryID,
                    $ref_doc_number,
                    "",
                    -1,
                    -1,
                    -1
                );
            }
        }
    } else if ($ln_TaxID > 0) {
        $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_TaxID);
        $dcntAMnt = getCodeAmnt($ln_DscntID, $entrdAmnt);
        $codeAmnt = getCodeAmnt($ln_TaxID, $entrdAmnt - $dcntAMnt);
        $lnSmmryLnID = getPyblsLnDetID("2Tax", $ln_TaxID, $smmryID);
        $accnts = getPyblBalncnAccnt("2Tax", $ln_TaxID, -1, -1, $docType);
        $funcCurrAmnt = $codeAmnt * $funcCurrRate;
        $accntCurrAmnt = $codeAmnt * $accntCurrRate;
        $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $entrdAmnt . ")";
        if ($lnSmmryLnID <= 0) {
            $lnSmmryLnID = getNewPyblsLnID();
            $res += createPyblsDocDet1(
                $lnSmmryLnID,
                $hdrID,
                "2Tax",
                $txlineDesc,
                $codeAmnt,
                $entrdCurrID,
                $ln_TaxID,
                $docType,
                true,
                $accnts[2],
                $accnts[3],
                $accnts[0],
                $blncgAccntID,
                -1,
                $vldyStatus,
                -1,
                $funcCurrID,
                $accntCurrID,
                $funcCurrRate,
                $accntCurrRate,
                $funcCurrAmnt,
                $accntCurrAmnt,
                $smmryID,
                $ref_doc_number,
                "",
                -1,
                -1,
                -1
            );
        } else {
            $res += updtPyblsDocDet1(
                $lnSmmryLnID,
                $hdrID,
                "2Tax",
                $txlineDesc,
                $codeAmnt,
                $entrdCurrID,
                $ln_TaxID,
                $docType,
                true,
                $accnts[2],
                $accnts[3],
                $accnts[0],
                $blncgAccntID,
                -1,
                $vldyStatus,
                -1,
                $funcCurrID,
                $accntCurrID,
                $funcCurrRate,
                $accntCurrRate,
                $funcCurrAmnt,
                $accntCurrAmnt,
                $smmryID,
                $ref_doc_number,
                "",
                -1,
                -1,
                -1
            );
        }
    }

    if ($ln_WHTaxID > 0 && isTaxAParent($ln_WHTaxID) === true) {
        $codeIDs = explode(",", trim(getTaxChildIDs($ln_WHTaxID), ", "));
        for ($y = 0; $y < count($codeIDs); $y++) {
            $ln_WHTaxID = (int) $codeIDs[$y];
            $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_WHTaxID);
            $dcntAMnt = getCodeAmnt($ln_DscntID, $entrdAmnt);
            $codeAmnt = getCodeAmnt($ln_WHTaxID, $entrdAmnt - $dcntAMnt);
            $lnSmmryLnID = getPyblsLnDetID("2Tax", $ln_WHTaxID, $smmryID);
            $accnts = getPyblBalncnAccnt("2Tax", $ln_WHTaxID, -1, -1, $docType);
            $funcCurrAmnt = $codeAmnt * $funcCurrRate;
            $accntCurrAmnt = $codeAmnt * $accntCurrRate;
            $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $entrdAmnt . ")";
            if ($lnSmmryLnID <= 0) {
                $lnSmmryLnID = getNewPyblsLnID();
                $res += createPyblsDocDet1(
                    $lnSmmryLnID,
                    $hdrID,
                    "2Tax",
                    $txlineDesc,
                    $codeAmnt,
                    $entrdCurrID,
                    $ln_WHTaxID,
                    $docType,
                    true,
                    $accnts[2],
                    $accnts[3],
                    $accnts[0],
                    $blncgAccntID,
                    -1,
                    $vldyStatus,
                    -1,
                    $funcCurrID,
                    $accntCurrID,
                    $funcCurrRate,
                    $accntCurrRate,
                    $funcCurrAmnt,
                    $accntCurrAmnt,
                    $smmryID,
                    $ref_doc_number,
                    "",
                    -1,
                    -1,
                    -1
                );
            } else {
                $res += updtPyblsDocDet1(
                    $lnSmmryLnID,
                    $hdrID,
                    "2Tax",
                    $txlineDesc,
                    $codeAmnt,
                    $entrdCurrID,
                    $ln_WHTaxID,
                    $docType,
                    true,
                    $accnts[2],
                    $accnts[3],
                    $accnts[0],
                    $blncgAccntID,
                    -1,
                    $vldyStatus,
                    -1,
                    $funcCurrID,
                    $accntCurrID,
                    $funcCurrRate,
                    $accntCurrRate,
                    $funcCurrAmnt,
                    $accntCurrAmnt,
                    $smmryID,
                    $ref_doc_number,
                    "",
                    -1,
                    -1,
                    -1
                );
            }
        }
    } else if ($ln_WHTaxID > 0) {
        $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_WHTaxID);
        $dcntAMnt = getCodeAmnt($ln_DscntID, $entrdAmnt);
        $codeAmnt = getCodeAmnt($ln_WHTaxID, $entrdAmnt - $dcntAMnt);
        $lnSmmryLnID = getPyblsLnDetID("2Tax", $ln_WHTaxID, $smmryID);
        $accnts = getPyblBalncnAccnt("2Tax", $ln_WHTaxID, -1, -1, $docType);
        $funcCurrAmnt = $codeAmnt * $funcCurrRate;
        $accntCurrAmnt = $codeAmnt * $accntCurrRate;
        $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $entrdAmnt . ")";
        if ($lnSmmryLnID <= 0) {
            $lnSmmryLnID = getNewPyblsLnID();
            $res += createPyblsDocDet1(
                $lnSmmryLnID,
                $hdrID,
                "2Tax",
                $txlineDesc,
                $codeAmnt,
                $entrdCurrID,
                $ln_WHTaxID,
                $docType,
                true,
                $accnts[2],
                $accnts[3],
                $accnts[0],
                $blncgAccntID,
                -1,
                $vldyStatus,
                -1,
                $funcCurrID,
                $accntCurrID,
                $funcCurrRate,
                $accntCurrRate,
                $funcCurrAmnt,
                $accntCurrAmnt,
                $smmryID,
                $ref_doc_number,
                "",
                -1,
                -1,
                -1
            );
        } else {
            $res += updtPyblsDocDet1(
                $lnSmmryLnID,
                $hdrID,
                "2Tax",
                $txlineDesc,
                $codeAmnt,
                $entrdCurrID,
                $ln_WHTaxID,
                $docType,
                true,
                $accnts[2],
                $accnts[3],
                $accnts[0],
                $blncgAccntID,
                -1,
                $vldyStatus,
                -1,
                $funcCurrID,
                $accntCurrID,
                $funcCurrRate,
                $accntCurrRate,
                $funcCurrAmnt,
                $accntCurrAmnt,
                $smmryID,
                $ref_doc_number,
                "",
                -1,
                -1,
                -1
            );
        }
    }

    if ($ln_DscntID > 0 && isTaxAParent($ln_DscntID) === true) {
        $codeIDs = explode(",", trim(getTaxChildIDs($ln_DscntID), ", "));
        for ($y = 0; $y < count($codeIDs); $y++) {
            $ln_DscntID = (int) $codeIDs[$y];
            $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_DscntID);
            $codeAmnt = getCodeAmnt($ln_DscntID, $entrdAmnt);
            $lnSmmryLnID = getPyblsLnDetID("3Discount", $ln_DscntID, $smmryID);
            $accnts = getPyblBalncnAccnt("3Discount", $ln_DscntID, -1, -1, $docType);
            $funcCurrAmnt = $codeAmnt * $funcCurrRate;
            $accntCurrAmnt = $codeAmnt * $accntCurrRate;
            $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $entrdAmnt . ")";
            if ($lnSmmryLnID <= 0) {
                $lnSmmryLnID = getNewPyblsLnID();
                $res += createPyblsDocDet1(
                    $lnSmmryLnID,
                    $hdrID,
                    "3Discount",
                    $txlineDesc,
                    $codeAmnt,
                    $entrdCurrID,
                    $ln_DscntID,
                    $docType,
                    true,
                    $accnts[2],
                    $accnts[3],
                    $accnts[0],
                    $blncgAccntID,
                    -1,
                    $vldyStatus,
                    -1,
                    $funcCurrID,
                    $accntCurrID,
                    $funcCurrRate,
                    $accntCurrRate,
                    $funcCurrAmnt,
                    $accntCurrAmnt,
                    $smmryID,
                    $ref_doc_number,
                    "",
                    -1,
                    -1,
                    -1
                );
            } else {
                $res += updtPyblsDocDet1(
                    $lnSmmryLnID,
                    $hdrID,
                    "3Discount",
                    $txlineDesc,
                    $codeAmnt,
                    $entrdCurrID,
                    $ln_DscntID,
                    $docType,
                    true,
                    $accnts[2],
                    $accnts[3],
                    $accnts[0],
                    $blncgAccntID,
                    -1,
                    $vldyStatus,
                    -1,
                    $funcCurrID,
                    $accntCurrID,
                    $funcCurrRate,
                    $accntCurrRate,
                    $funcCurrAmnt,
                    $accntCurrAmnt,
                    $smmryID,
                    $ref_doc_number,
                    "",
                    -1,
                    -1,
                    -1
                );
            }
        }
    } else if ($ln_DscntID > 0) {
        $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_DscntID);
        $codeAmnt = getCodeAmnt($ln_DscntID, $entrdAmnt);
        $lnSmmryLnID = getPyblsLnDetID("3Discount", $ln_DscntID, $smmryID);
        $accnts = getPyblBalncnAccnt("3Discount", $ln_DscntID, -1, -1, $docType);
        $funcCurrAmnt = $codeAmnt * $funcCurrRate;
        $accntCurrAmnt = $codeAmnt * $accntCurrRate;
        $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $entrdAmnt . ")";
        if ($lnSmmryLnID <= 0) {
            $lnSmmryLnID = getNewPyblsLnID();
            $res += createPyblsDocDet1(
                $lnSmmryLnID,
                $hdrID,
                "3Discount",
                $txlineDesc,
                $codeAmnt,
                $entrdCurrID,
                $ln_DscntID,
                $docType,
                true,
                $accnts[2],
                $accnts[3],
                $accnts[0],
                $blncgAccntID,
                -1,
                $vldyStatus,
                -1,
                $funcCurrID,
                $accntCurrID,
                $funcCurrRate,
                $accntCurrRate,
                $funcCurrAmnt,
                $accntCurrAmnt,
                $smmryID,
                $ref_doc_number,
                "",
                -1,
                -1,
                -1
            );
        } else {
            $res += updtPyblsDocDet1(
                $lnSmmryLnID,
                $hdrID,
                "3Discount",
                $txlineDesc,
                $codeAmnt,
                $entrdCurrID,
                $ln_DscntID,
                $docType,
                true,
                $accnts[2],
                $accnts[3],
                $accnts[0],
                $blncgAccntID,
                -1,
                $vldyStatus,
                -1,
                $funcCurrID,
                $accntCurrID,
                $funcCurrRate,
                $accntCurrRate,
                $funcCurrAmnt,
                $accntCurrAmnt,
                $smmryID,
                $ref_doc_number,
                "",
                -1,
                -1,
                -1
            );
        }
    }
    return $res;
}

function createPyblsDocDet1(
    $smmryID,
    $hdrID,
    $lineType,
    $lineDesc,
    $entrdAmnt,
    $entrdCurrID,
    $codeBhnd,
    $docType,
    $autoCalc,
    $incrDcrs1,
    $costngID,
    $incrDcrs2,
    $blncgAccntID,
    $prepayDocHdrID,
    $vldyStatus,
    $orgnlLnID,
    $funcCurrID,
    $accntCurrID,
    $funcCurrRate,
    $accntCurrRate,
    $funcCurrAmnt,
    $accntCurrAmnt,
    $initAmntID,
    $ref_doc_number,
    $slctd_amnt_brkdwns,
    $ln_TaxID,
    $ln_WHTaxID,
    $ln_DscntID
) {
    global $usrID;
    $insSQL = "INSERT INTO accb.accb_pybls_amnt_smmrys(
	pybls_smmry_id, pybls_smmry_type, pybls_smmry_desc, pybls_smmry_amnt, code_id_behind, src_pybls_type, 
	src_pybls_hdr_id, created_by, creation_date, last_update_by, last_update_date, auto_calc, incrs_dcrs1, 
	asset_expns_acnt_id, incrs_dcrs2, liability_acnt_id, appld_prepymnt_doc_id, validty_status, orgnl_line_id, 
	entrd_curr_id, func_curr_id, accnt_curr_id, func_curr_rate, accnt_curr_rate, func_curr_amount, 
	accnt_curr_amnt, initial_amnt_line_id, ref_doc_number, slctd_amnt_brkdwns, tax_code_id, whtax_code_id, dscnt_code_id) " .
        "VALUES (" . $smmryID . ", '" . loc_db_escape_string($lineType) .
        "', '" . loc_db_escape_string($lineDesc) .
        "', " . $entrdAmnt .
        ", " . $codeBhnd .
        ", '" . loc_db_escape_string($docType) .
        "', " . $hdrID .
        ", " . $usrID . ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), '" . cnvrtBoolToBitStr($autoCalc) .
        "', '" . loc_db_escape_string($incrDcrs1) .
        "', " . $costngID .
        ", '" . loc_db_escape_string($incrDcrs2) .
        "', " . $blncgAccntID .
        ", " . $prepayDocHdrID .
        ", '" . loc_db_escape_string($vldyStatus) .
        "', " . $orgnlLnID .
        ", " . $entrdCurrID .
        ", " . $funcCurrID .
        ", " . $accntCurrID .
        ", " . $funcCurrRate .
        ", " . $accntCurrRate .
        ", " . $funcCurrAmnt .
        ", " . $accntCurrAmnt .
        ", " . $initAmntID .
        ", '" . loc_db_escape_string($ref_doc_number) .
        "', '" . loc_db_escape_string($slctd_amnt_brkdwns) .
        "', " . $ln_TaxID .
        ", " . $ln_WHTaxID .
        ", " . $ln_DscntID .
        ")";
    return execUpdtInsSQL($insSQL);
}

function getPyblBalncnAccnt($lineType, $codebhndID, $spplrID, $prepayDocID, $docType)
{
    global $orgID;
    $res = array("Increase", /* Balancing Account */ "-1", "Increase", /* Charge Account */ "-1");
    $spplrAccntID = "-1";

    if ($docType == "Supplier Standard Payment" || $docType == "Supplier Advance Payment" || $docType == "Direct Topup for Supplier" || $docType == "Supplier Debit Memo (InDirect Topup)") {
        $spplrAccntID = getGnrlRecNm("scm.scm_cstmr_suplr", "cust_sup_id", "dflt_pybl_accnt_id", $spplrID);
    } else { //if (docType == "Direct Refund from Supplier")
        $spplrAccntID = getGnrlRecNm("scm.scm_cstmr_suplr", "cust_sup_id", "dflt_rcvbl_accnt_id", $spplrID);
    }

    $accntID = (int) $spplrAccntID;
    if ($accntID <= 0) {
        if ($docType == "Supplier Standard Payment" || $docType == "Supplier Advance Payment" || $docType == "Direct Topup for Supplier" || $docType == "Supplier Debit Memo (InDirect Topup)") {
            $dflACntID = get_DfltPyblAcnt($orgID);
            $accntID = $dflACntID;
        } else {
            $dflACntID = get_DfltRcvblAcnt($orgID);
            $accntID = $dflACntID;
        }
    }
    $res[1] = $accntID;
    if ($docType == "Supplier Standard Payment" || $docType == "Supplier Advance Payment" || $docType == "Direct Topup for Supplier" || $docType == "Supplier Debit Memo (InDirect Topup)") {
        if ($lineType == "1Initial Amount") {
            $res[0] = "Increase";
            $res[2] = "Increase";
            $res[3] = "-1";
            //res[3] = Global.get_DfltExpnsAcnt($orgID).ToString();
            return $res;
        }
        if ($lineType == "2Tax") {
            $taxAccntID = getGnrlRecNm("scm.scm_tax_codes", "code_id", "taxes_payables_accnt_id", $codebhndID);
            $isRcvrbl = getGnrlRecNm("scm.scm_tax_codes", "code_id", "is_recovrbl_tax", $codebhndID);
            $isWthHldng = getGnrlRecNm("scm.scm_tax_codes", "code_id", "is_withldng_tax", $codebhndID);
            $res[0] = "Increase";
            if ($isRcvrbl == "1") {
                $res[2] = "Decrease";
                $res[3] = $taxAccntID;
            } else if ($isWthHldng == "1") {
                $res[0] = "Decrease";
                $res[2] = "Increase";
                $res[3] = $taxAccntID;
            } else {
                $taxExpnsAccntID = getGnrlRecNm("scm.scm_tax_codes", "code_id", "tax_expense_accnt_id", $codebhndID);
                $res[2] = "Increase";
                $res[3] = $taxExpnsAccntID;
            }
            return $res;
        }
        if ($lineType == "3Discount") {
            //string taxAccntID = Global.mnFrm.cmCde.getGnrlRecNm(
            // "scm.scm_tax_codes", "code_id", "dscount_expns_accnt_id",
            // codebhndID);
            $res[0] = "Decrease";
            $res[2] = "Increase";
            $prchsDscntAccntID = getGnrlRecNm("scm.scm_tax_codes", "code_id", "prchs_dscnt_accnt_id", $codebhndID);
            $res[2] = "Increase";
            $res[3] = $prchsDscntAccntID;

            return $res;
        }
        if ($lineType == "4Extra Charge") {
            $res[0] = "Increase";
            $chrgeExpnsAccntID = getGnrlRecNm("scm.scm_tax_codes", "code_id", "chrge_expns_accnt_id", $codebhndID);
            $res[2] = "Increase";
            $res[3] = $chrgeExpnsAccntID;
        }
        if ($docType == "Supplier Standard Payment" || $docType == "Direct Topup for Supplier") {
            if ($lineType == "5Applied Prepayment") {
                $prepayAccntID = -1;
                $prepayDocType = getGnrlRecNm("accb.accb_pybls_invc_hdr", "pybls_invc_hdr_id", "pybls_invc_type", $prepayDocID);
                $res[0] = "Decrease";
                $res[2] = "Decrease";
                if ($prepayDocType == "Supplier Credit Memo (InDirect Refund)") {
                    $prepayAccntID = get_PyblPrepayDocLbltyAcntID($prepayDocID);
                } else {
                    $prepayAccntID = get_PyblPrepayDocAcntID($prepayDocID);
                }
                $res[3] = $prepayAccntID;
            }
        }
    } else {
        if ($lineType == "1Initial Amount") {
            $res[0] = "Increase";
            $res[2] = "Decrease";
            $res[3] = "-1";
            //res[3] = Global.get_DfltExpnsAcnt($orgID).ToString();
            return $res;
        }
        if ($lineType == "2Tax") {
            $taxAccntID = getGnrlRecNm("scm.scm_tax_codes", "code_id", "taxes_payables_accnt_id", $codebhndID);
            $isRcvrbl = getGnrlRecNm("scm.scm_tax_codes", "code_id", "is_recovrbl_tax", $codebhndID);
            $isWthHldng = getGnrlRecNm("scm.scm_tax_codes", "code_id", "is_withldng_tax", $codebhndID);
            $res[0] = "Increase";
            if ($isRcvrbl == "1") {
                $res[2] = "Increase";
                $res[3] = $taxAccntID;
            } else if ($isWthHldng == "1") {
                $res[0] = "Decrease";
                $res[2] = "Decrease";
                $res[3] = $taxAccntID;
            } else {
                $taxExpnsAccntID = getGnrlRecNm("scm.scm_tax_codes", "code_id", "tax_expense_accnt_id", $codebhndID);
                $res[2] = "Decrease";
                $res[3] = $taxExpnsAccntID;
            }
            return $res;
        }
        if ($lineType == "3Discount") {
            //string taxAccntID = Global.mnFrm.cmCde.getGnrlRecNm(
            // "scm.scm_tax_codes", "code_id", "dscount_expns_accnt_id",
            // codebhndID);
            $res[0] = "Decrease";
            $res[2] = "Decrease";
            $prchsDscntAccntID = getGnrlRecNm("scm.scm_tax_codes", "code_id", "prchs_dscnt_accnt_id", $codebhndID);
            $res[2] = "Decrease";
            $res[3] = $prchsDscntAccntID;

            return $res;
        }
        if ($lineType == "4Extra Charge") {
            $res[0] = "Increase";
            $chrgeExpnsAccntID = getGnrlRecNm("scm.scm_tax_codes", "code_id", "chrge_expns_accnt_id", $codebhndID);
            $res[2] = "Decrease";
            $res[3] = $chrgeExpnsAccntID;
        }
        if ($docType == "Direct Refund from Supplier") {
            if ($lineType == "5Applied Prepayment") {
                $prepayAccntID = get_PyblPrepayDocLbltyAcntID($prepayDocID);
                $res[0] = "Decrease";
                $res[2] = "Decrease";
                $res[3] = $prepayAccntID;
            }
        }
    }
    return $res;
}

function getPyblsLnDetID($trnsType, $ln_CodeID, $initAmntLnID)
{
    $strSql = "select pybls_smmry_id from accb.accb_pybls_amnt_smmrys where pybls_smmry_type='"
        . loc_db_escape_string($trnsType) . "' and initial_amnt_line_id = " . $initAmntLnID . " and code_id_behind=" . $ln_CodeID;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getCodeAmnt($codeID, $grndAmnt)
{
    $codeSQL = getGnrlRecNm("scm.scm_tax_codes", "code_id", "sql_formular", $codeID);
    $CalcItemValue = 0;
    $boolRes = isTxCdeSQLValid($codeSQL, $grndAmnt, 1, $CalcItemValue);
    if ($boolRes === true) {
        return $CalcItemValue;
    } else {
        return 0.00;
    }
}

function updtPyblsDocDet(
    $docDetID,
    $hdrID,
    $lineType,
    $lineDesc,
    $entrdAmnt,
    $entrdCurrID,
    $codeBhnd,
    $docType,
    $autoCalc,
    $incrDcrs1,
    $costngID,
    $incrDcrs2,
    $blncgAccntID,
    $prepayDocHdrID,
    $vldyStatus,
    $orgnlLnID,
    $funcCurrID,
    $accntCurrID,
    $funcCurrRate,
    $accntCurrRate,
    $funcCurrAmnt,
    $accntCurrAmnt,
    $initAmntID,
    $ref_doc_number,
    $slctd_amnt_brkdwns,
    $ln_TaxID,
    $ln_WHTaxID,
    $ln_DscntID
) {
    $res = updtPyblsDocDet1(
        $docDetID,
        $hdrID,
        $lineType,
        $lineDesc,
        $entrdAmnt,
        $entrdCurrID,
        $codeBhnd,
        $docType,
        $autoCalc,
        $incrDcrs1,
        $costngID,
        $incrDcrs2,
        $blncgAccntID,
        $prepayDocHdrID,
        $vldyStatus,
        $orgnlLnID,
        $funcCurrID,
        $accntCurrID,
        $funcCurrRate,
        $accntCurrRate,
        $funcCurrAmnt,
        $accntCurrAmnt,
        $initAmntID,
        $ref_doc_number,
        $slctd_amnt_brkdwns,
        $ln_TaxID,
        $ln_WHTaxID,
        $ln_DscntID
    );
    $smmryID = $docDetID;
    if ($ln_TaxID > 0 && isTaxAParent($ln_TaxID) === true) {
        $codeIDs = explode(",", trim(getTaxChildIDs($ln_TaxID), ", "));
        for ($y = 0; $y < count($codeIDs); $y++) {
            $ln_TaxID = (int) $codeIDs[$y];
            $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_TaxID);
            $dcntAMnt = getCodeAmnt($ln_DscntID, $entrdAmnt);
            $codeAmnt = getCodeAmnt($ln_TaxID, $entrdAmnt - $dcntAMnt);
            $lnSmmryLnID = getPyblsLnDetID("2Tax", $ln_TaxID, $smmryID);
            $accnts = getPyblBalncnAccnt("2Tax", $ln_TaxID, -1, -1, $docType);
            $funcCurrAmnt = $codeAmnt * $funcCurrRate;
            $accntCurrAmnt = $codeAmnt * $accntCurrRate;
            $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $entrdAmnt . ")";
            if ($lnSmmryLnID <= 0) {
                $lnSmmryLnID = getNewPyblsLnID();
                $res += createPyblsDocDet1(
                    $lnSmmryLnID,
                    $hdrID,
                    "2Tax",
                    $txlineDesc,
                    $codeAmnt,
                    $entrdCurrID,
                    $ln_TaxID,
                    $docType,
                    true,
                    $accnts[2],
                    $accnts[3],
                    $accnts[0],
                    $blncgAccntID,
                    -1,
                    $vldyStatus,
                    -1,
                    $funcCurrID,
                    $accntCurrID,
                    $funcCurrRate,
                    $accntCurrRate,
                    $funcCurrAmnt,
                    $accntCurrAmnt,
                    $smmryID,
                    $ref_doc_number,
                    "",
                    -1,
                    -1,
                    -1
                );
            } else {
                $res += updtPyblsDocDet1(
                    $lnSmmryLnID,
                    $hdrID,
                    "2Tax",
                    $txlineDesc,
                    $codeAmnt,
                    $entrdCurrID,
                    $ln_TaxID,
                    $docType,
                    true,
                    $accnts[2],
                    $accnts[3],
                    $accnts[0],
                    $blncgAccntID,
                    -1,
                    $vldyStatus,
                    -1,
                    $funcCurrID,
                    $accntCurrID,
                    $funcCurrRate,
                    $accntCurrRate,
                    $funcCurrAmnt,
                    $accntCurrAmnt,
                    $smmryID,
                    $ref_doc_number,
                    "",
                    -1,
                    -1,
                    -1
                );
            }
        }
    } else if ($ln_TaxID > 0) {
        $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_TaxID);
        $dcntAMnt = getCodeAmnt($ln_DscntID, $entrdAmnt);
        $codeAmnt = getCodeAmnt($ln_TaxID, $entrdAmnt - $dcntAMnt);
        $lnSmmryLnID = getPyblsLnDetID("2Tax", $ln_TaxID, $smmryID);
        $accnts = getPyblBalncnAccnt("2Tax", $ln_TaxID, -1, -1, $docType);
        $funcCurrAmnt = $codeAmnt * $funcCurrRate;
        $accntCurrAmnt = $codeAmnt * $accntCurrRate;
        $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $entrdAmnt . ")";
        if ($lnSmmryLnID <= 0) {
            $lnSmmryLnID = getNewPyblsLnID();
            $res += createPyblsDocDet1(
                $lnSmmryLnID,
                $hdrID,
                "2Tax",
                $txlineDesc,
                $codeAmnt,
                $entrdCurrID,
                $ln_TaxID,
                $docType,
                true,
                $accnts[2],
                $accnts[3],
                $accnts[0],
                $blncgAccntID,
                -1,
                $vldyStatus,
                -1,
                $funcCurrID,
                $accntCurrID,
                $funcCurrRate,
                $accntCurrRate,
                $funcCurrAmnt,
                $accntCurrAmnt,
                $smmryID,
                $ref_doc_number,
                "",
                -1,
                -1,
                -1
            );
        } else {
            $res += updtPyblsDocDet1(
                $lnSmmryLnID,
                $hdrID,
                "2Tax",
                $txlineDesc,
                $codeAmnt,
                $entrdCurrID,
                $ln_TaxID,
                $docType,
                true,
                $accnts[2],
                $accnts[3],
                $accnts[0],
                $blncgAccntID,
                -1,
                $vldyStatus,
                -1,
                $funcCurrID,
                $accntCurrID,
                $funcCurrRate,
                $accntCurrRate,
                $funcCurrAmnt,
                $accntCurrAmnt,
                $smmryID,
                $ref_doc_number,
                "",
                -1,
                -1,
                -1
            );
        }
    }

    if ($ln_WHTaxID > 0 && isTaxAParent($ln_WHTaxID) === true) {
        $codeIDs = explode(",", trim(getTaxChildIDs($ln_WHTaxID), ", "));
        for ($y = 0; $y < count($codeIDs); $y++) {
            $ln_WHTaxID = (int) $codeIDs[$y];
            $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_WHTaxID);
            $dcntAMnt = getCodeAmnt($ln_DscntID, $entrdAmnt);
            $codeAmnt = getCodeAmnt($ln_WHTaxID, $entrdAmnt - $dcntAMnt);
            $lnSmmryLnID = getPyblsLnDetID("2Tax", $ln_WHTaxID, $smmryID);
            $accnts = getPyblBalncnAccnt("2Tax", $ln_WHTaxID, -1, -1, $docType);
            $funcCurrAmnt = $codeAmnt * $funcCurrRate;
            $accntCurrAmnt = $codeAmnt * $accntCurrRate;
            $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $entrdAmnt . ")";
            if ($lnSmmryLnID <= 0) {
                $lnSmmryLnID = getNewPyblsLnID();
                $res += createPyblsDocDet1(
                    $lnSmmryLnID,
                    $hdrID,
                    "2Tax",
                    $txlineDesc,
                    $codeAmnt,
                    $entrdCurrID,
                    $ln_WHTaxID,
                    $docType,
                    true,
                    $accnts[2],
                    $accnts[3],
                    $accnts[0],
                    $blncgAccntID,
                    -1,
                    $vldyStatus,
                    -1,
                    $funcCurrID,
                    $accntCurrID,
                    $funcCurrRate,
                    $accntCurrRate,
                    $funcCurrAmnt,
                    $accntCurrAmnt,
                    $smmryID,
                    $ref_doc_number,
                    "",
                    -1,
                    -1,
                    -1
                );
            } else {
                $res += updtPyblsDocDet1(
                    $lnSmmryLnID,
                    $hdrID,
                    "2Tax",
                    $txlineDesc,
                    $codeAmnt,
                    $entrdCurrID,
                    $ln_WHTaxID,
                    $docType,
                    true,
                    $accnts[2],
                    $accnts[3],
                    $accnts[0],
                    $blncgAccntID,
                    -1,
                    $vldyStatus,
                    -1,
                    $funcCurrID,
                    $accntCurrID,
                    $funcCurrRate,
                    $accntCurrRate,
                    $funcCurrAmnt,
                    $accntCurrAmnt,
                    $smmryID,
                    $ref_doc_number,
                    "",
                    -1,
                    -1,
                    -1
                );
            }
        }
    } else if ($ln_WHTaxID > 0) {
        $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_WHTaxID);
        $dcntAMnt = getCodeAmnt($ln_DscntID, $entrdAmnt);
        $codeAmnt = getCodeAmnt($ln_WHTaxID, $entrdAmnt - $dcntAMnt);
        $lnSmmryLnID = getPyblsLnDetID("2Tax", $ln_WHTaxID, $smmryID);
        $accnts = getPyblBalncnAccnt("2Tax", $ln_WHTaxID, -1, -1, $docType);
        $funcCurrAmnt = $codeAmnt * $funcCurrRate;
        $accntCurrAmnt = $codeAmnt * $accntCurrRate;
        $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $entrdAmnt . ")";
        if ($lnSmmryLnID <= 0) {
            $lnSmmryLnID = getNewPyblsLnID();
            $res += createPyblsDocDet1(
                $lnSmmryLnID,
                $hdrID,
                "2Tax",
                $txlineDesc,
                $codeAmnt,
                $entrdCurrID,
                $ln_WHTaxID,
                $docType,
                true,
                $accnts[2],
                $accnts[3],
                $accnts[0],
                $blncgAccntID,
                -1,
                $vldyStatus,
                -1,
                $funcCurrID,
                $accntCurrID,
                $funcCurrRate,
                $accntCurrRate,
                $funcCurrAmnt,
                $accntCurrAmnt,
                $smmryID,
                $ref_doc_number,
                "",
                -1,
                -1,
                -1
            );
        } else {
            $res += updtPyblsDocDet1(
                $lnSmmryLnID,
                $hdrID,
                "2Tax",
                $txlineDesc,
                $codeAmnt,
                $entrdCurrID,
                $ln_WHTaxID,
                $docType,
                true,
                $accnts[2],
                $accnts[3],
                $accnts[0],
                $blncgAccntID,
                -1,
                $vldyStatus,
                -1,
                $funcCurrID,
                $accntCurrID,
                $funcCurrRate,
                $accntCurrRate,
                $funcCurrAmnt,
                $accntCurrAmnt,
                $smmryID,
                $ref_doc_number,
                "",
                -1,
                -1,
                -1
            );
        }
    }

    if ($ln_DscntID > 0 && isTaxAParent($ln_DscntID) === true) {
        $codeIDs = explode(",", trim(getTaxChildIDs($ln_DscntID), ", "));
        for ($y = 0; $y < count($codeIDs); $y++) {
            $ln_DscntID = (int) $codeIDs[$y];
            $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_DscntID);
            $codeAmnt = getCodeAmnt($ln_DscntID, $entrdAmnt);
            $lnSmmryLnID = getPyblsLnDetID("3Discount", $ln_DscntID, $smmryID);
            $accnts = getPyblBalncnAccnt("3Discount", $ln_DscntID, -1, -1, $docType);
            $funcCurrAmnt = $codeAmnt * $funcCurrRate;
            $accntCurrAmnt = $codeAmnt * $accntCurrRate;
            $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $entrdAmnt . ")";
            if ($lnSmmryLnID <= 0) {
                $lnSmmryLnID = getNewPyblsLnID();
                $res += createPyblsDocDet1(
                    $lnSmmryLnID,
                    $hdrID,
                    "3Discount",
                    $txlineDesc,
                    $codeAmnt,
                    $entrdCurrID,
                    $ln_DscntID,
                    $docType,
                    true,
                    $accnts[2],
                    $accnts[3],
                    $accnts[0],
                    $blncgAccntID,
                    -1,
                    $vldyStatus,
                    -1,
                    $funcCurrID,
                    $accntCurrID,
                    $funcCurrRate,
                    $accntCurrRate,
                    $funcCurrAmnt,
                    $accntCurrAmnt,
                    $smmryID,
                    $ref_doc_number,
                    "",
                    -1,
                    -1,
                    -1
                );
            } else {
                $res += updtPyblsDocDet1(
                    $lnSmmryLnID,
                    $hdrID,
                    "3Discount",
                    $txlineDesc,
                    $codeAmnt,
                    $entrdCurrID,
                    $ln_DscntID,
                    $docType,
                    true,
                    $accnts[2],
                    $accnts[3],
                    $accnts[0],
                    $blncgAccntID,
                    -1,
                    $vldyStatus,
                    -1,
                    $funcCurrID,
                    $accntCurrID,
                    $funcCurrRate,
                    $accntCurrRate,
                    $funcCurrAmnt,
                    $accntCurrAmnt,
                    $smmryID,
                    $ref_doc_number,
                    "",
                    -1,
                    -1,
                    -1
                );
            }
        }
    } else if ($ln_DscntID > 0) {
        $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_DscntID);
        $codeAmnt = getCodeAmnt($ln_DscntID, $entrdAmnt);
        $lnSmmryLnID = getPyblsLnDetID("3Discount", $ln_DscntID, $smmryID);
        $accnts = getPyblBalncnAccnt("3Discount", $ln_DscntID, -1, -1, $docType);
        $funcCurrAmnt = $codeAmnt * $funcCurrRate;
        $accntCurrAmnt = $codeAmnt * $accntCurrRate;
        $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $entrdAmnt . ")";
        if ($lnSmmryLnID <= 0) {
            $lnSmmryLnID = getNewPyblsLnID();
            $res += createPyblsDocDet1(
                $lnSmmryLnID,
                $hdrID,
                "3Discount",
                $txlineDesc,
                $codeAmnt,
                $entrdCurrID,
                $ln_DscntID,
                $docType,
                true,
                $accnts[2],
                $accnts[3],
                $accnts[0],
                $blncgAccntID,
                -1,
                $vldyStatus,
                -1,
                $funcCurrID,
                $accntCurrID,
                $funcCurrRate,
                $accntCurrRate,
                $funcCurrAmnt,
                $accntCurrAmnt,
                $smmryID,
                $ref_doc_number,
                "",
                -1,
                -1,
                -1
            );
        } else {
            $res += updtPyblsDocDet1(
                $lnSmmryLnID,
                $hdrID,
                "3Discount",
                $txlineDesc,
                $codeAmnt,
                $entrdCurrID,
                $ln_DscntID,
                $docType,
                true,
                $accnts[2],
                $accnts[3],
                $accnts[0],
                $blncgAccntID,
                -1,
                $vldyStatus,
                -1,
                $funcCurrID,
                $accntCurrID,
                $funcCurrRate,
                $accntCurrRate,
                $funcCurrAmnt,
                $accntCurrAmnt,
                $smmryID,
                $ref_doc_number,
                "",
                -1,
                -1,
                -1
            );
        }
    }
    return $res;
}

function updtPyblsDocDet1(
    $docDetID,
    $hdrID,
    $lineType,
    $lineDesc,
    $entrdAmnt,
    $entrdCurrID,
    $codeBhnd,
    $docType,
    $autoCalc,
    $incrDcrs1,
    $costngID,
    $incrDcrs2,
    $blncgAccntID,
    $prepayDocHdrID,
    $vldyStatus,
    $orgnlLnID,
    $funcCurrID,
    $accntCurrID,
    $funcCurrRate,
    $accntCurrRate,
    $funcCurrAmnt,
    $accntCurrAmnt,
    $initAmntID,
    $ref_doc_number,
    $slctd_amnt_brkdwns,
    $ln_TaxID,
    $ln_WHTaxID,
    $ln_DscntID
) {
    global $usrID;
    $insSQL = "UPDATE accb.accb_pybls_amnt_smmrys
   SET pybls_smmry_type='" . loc_db_escape_string($lineType) .
        "', pybls_smmry_desc='" . loc_db_escape_string($lineDesc) .
        "', pybls_smmry_amnt=" . $entrdAmnt .
        ", code_id_behind=" . $codeBhnd .
        ", src_pybls_type='" . loc_db_escape_string($docType) .
        "', src_pybls_hdr_id=" . $hdrID .
        ", last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), auto_calc='" . cnvrtBoolToBitStr($autoCalc) .
        "', incrs_dcrs1='" . loc_db_escape_string($incrDcrs1) .
        "', asset_expns_acnt_id=" . $costngID .
        ", incrs_dcrs2='" . loc_db_escape_string($incrDcrs2) .
        "', liability_acnt_id=" . $blncgAccntID .
        ", appld_prepymnt_doc_id=" . $prepayDocHdrID .
        ", validty_status='" . loc_db_escape_string($vldyStatus) .
        "', orgnl_line_id=" . $orgnlLnID .
        ", entrd_curr_id=" . $entrdCurrID .
        ", func_curr_id=" . $funcCurrID .
        ", accnt_curr_id=" . $accntCurrID .
        ", func_curr_rate=" . $funcCurrRate .
        ", accnt_curr_rate=" . $accntCurrRate .
        ", func_curr_amount=" . $funcCurrAmnt .
        ", accnt_curr_amnt=" . $accntCurrAmnt .
        ", initial_amnt_line_id=" . $initAmntID .
        ", ref_doc_number='" . loc_db_escape_string($ref_doc_number) .
        "', slctd_amnt_brkdwns='" . loc_db_escape_string($slctd_amnt_brkdwns) .
        "', tax_code_id=" . $ln_TaxID .
        ", whtax_code_id=" . $ln_WHTaxID .
        ", dscnt_code_id=" . $ln_DscntID .
        " WHERE pybls_smmry_id = " . $docDetID;
    return execUpdtInsSQL($insSQL);
}

function deletePyblsDocHdrNDet($valLnid, $docNum)
{
    $trnsCnt1 = 0;
    $docStatus = getGnrlRecNm("accb.accb_pybls_invc_hdr", "pybls_invc_hdr_id", "approval_status", $valLnid);
    if ($docStatus == "Approved" || $docStatus == "Initiated" || $docStatus == "Validated" || $docStatus == "Cancelled" || strpos(
        $docStatus,
        "Reviewed"
    ) !== FALSE) {
        $trnsCnt1 = 1;
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot DELETE Approved, Initiated, Reviewed, Validated or Cancelled Documents!!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM accb.accb_pybls_amnt_smmrys WHERE src_pybls_hdr_id = " . $valLnid;
    $affctd1 = execUpdtInsSQL($delSQL, "Doc. No.:" . $docNum);
    $delSQL = "DELETE FROM accb.accb_pybls_invc_hdr WHERE pybls_invc_hdr_id = " . $valLnid;
    $affctd2 = execUpdtInsSQL($delSQL, "Doc. No.:" . $docNum);
    if ($affctd2 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Invoice Line(s)!";
        $dsply .= "<br/>Deleted $affctd2 Payable Document(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deletePyblsDocDet($valLnid, $docNum)
{
    $trnsCnt1 = 0;
    $docHdrID = (float) getGnrlRecNm("accb.accb_pybls_amnt_smmrys", "pybls_smmry_id", "src_pybls_hdr_id", $valLnid);
    $docStatus = getGnrlRecNm("accb.accb_pybls_invc_hdr", "pybls_invc_hdr_id", "approval_status", $docHdrID);
    if ($docStatus == "Approved" || $docStatus == "Initiated" || $docStatus == "Validated" || $docStatus == "Cancelled" || strpos(
        $docStatus,
        "Reviewed"
    ) !== FALSE) {
        $trnsCnt1 = 1;
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot DELETE Approved, Initiated, Reviewed, Validated or Cancelled Documents!!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }

    $delSQL = "DELETE FROM accb.accb_pybls_amnt_smmrys WHERE pybls_smmry_id = " . $valLnid;
    $affctd1 = execUpdtInsSQL($delSQL, "Doc. No.:" . $docNum);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Invoice Line(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getPyblsDocGrndAmnt($dochdrID)
{
    $strSql = "select SUM(CASE WHEN y.pybls_smmry_type='3Discount' 
or scm.istaxwthhldng(y.code_id_behind)='1' or y.pybls_smmry_type='5Applied Prepayment'
      THEN -1*y.pybls_smmry_amnt ELSE y.pybls_smmry_amnt END) amnt " .
        "from accb.accb_pybls_amnt_smmrys y " .
        "where y.src_pybls_hdr_id=" . $dochdrID .
        " and y.pybls_smmry_type IN ('1Initial Amount','2Tax','3Discount','4Extra Charge','5Applied Prepayment')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function getPyblsDocFuncAmnt($dochdrID)
{
    $strSql = "select SUM(CASE WHEN y.pybls_smmry_type='3Discount' 
or scm.istaxwthhldng(y.code_id_behind)='1' or y.pybls_smmry_type='5Applied Prepayment'
      THEN -1*y.func_curr_amount ELSE y.func_curr_amount END) amnt " .
        "from accb.accb_pybls_amnt_smmrys y " .
        "where y.src_pybls_hdr_id=" . $dochdrID .
        " and y.pybls_smmry_type IN ('1Initial Amount','2Tax','3Discount','4Extra Charge','5Applied Prepayment')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function getPyblsDocAccntAmnt($dochdrID)
{
    $strSql = "select SUM(CASE WHEN y.pybls_smmry_type='3Discount' 
or scm.istaxwthhldng(y.code_id_behind)='1' or y.pybls_smmry_type='5Applied Prepayment'
      THEN -1*y.accnt_curr_amnt ELSE y.accnt_curr_amnt END) amnt " .
        "from accb.accb_pybls_amnt_smmrys y " .
        "where y.src_pybls_hdr_id=" . $dochdrID .
        " and y.pybls_smmry_type IN ('1Initial Amount','2Tax','3Discount','4Extra Charge','5Applied Prepayment')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function getPyblsSmmryItmID($smmryType, $codeBhnd, $srcDocID, $srcDocTyp, $smmryNm)
{
    $strSql = "select y.pybls_smmry_id " .
        "from accb.accb_pybls_amnt_smmrys y " .
        "where y.pybls_smmry_type= '" . loc_db_escape_string($smmryType) .
        "' and y.pybls_smmry_desc = '" . loc_db_escape_string($smmryNm) .
        "' and y.code_id_behind= " . $codeBhnd .
        " and y.src_pybls_type='" . loc_db_escape_string($srcDocTyp) .
        "' and y.src_pybls_hdr_id=" . $srcDocID . " ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function updtPyblsDocApprvl($docid, $apprvlSts, $nxtApprvl)
{
    global $usrID;
    $extrCls = "";
    if ($apprvlSts == "Cancelled") {
        $extrCls = ", invoice_amount=0, invc_amnt_appld_elswhr=0";
    }
    $updtSQL = "UPDATE accb.accb_pybls_invc_hdr SET " .
        "approval_status='" . loc_db_escape_string($apprvlSts) .
        "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS')"
        . ", next_aproval_action='" . loc_db_escape_string($nxtApprvl) .
        "'" . $extrCls . " WHERE (pybls_invc_hdr_id = " . $docid . ")";
    return execUpdtInsSQL($updtSQL);
}

function updtPyblsDocAmnt($docid, $invAmnt)
{
    global $usrID;
    $extrCls = ", invoice_amount=" . $invAmnt . "";
    $updtSQL = "UPDATE accb.accb_pybls_invc_hdr SET " .
        "last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS')" . $extrCls . " WHERE (pybls_invc_hdr_id = " .
        $docid . ")";
    return execUpdtInsSQL($updtSQL);
}

function updtPyblsDocGLBatch($docid, $glBatchID)
{
    global $usrID;
    $updtSQL = "UPDATE accb.accb_pybls_invc_hdr SET " .
        "gl_batch_id=" . $glBatchID .
        ", last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS') WHERE (pybls_invc_hdr_id = " .
        $docid . ")";
    return execUpdtInsSQL($updtSQL);
}

function updtPyblsDocAmntPaid($docid, $amntPaid)
{
    global $usrID;
    $updtSQL = "UPDATE accb.accb_pybls_invc_hdr SET " .
        "amnt_paid=amnt_paid + " . $amntPaid .
        ", last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS') WHERE (pybls_invc_hdr_id = " .
        $docid . ")";
    return execUpdtInsSQL($updtSQL);
}

function updtPyblsDocAmntAppld($docid, $amntAppld)
{
    global $usrID;
    $updtSQL = "UPDATE accb.accb_pybls_invc_hdr SET " .
        "invc_amnt_appld_elswhr=invc_amnt_appld_elswhr + " . $amntAppld .
        ", last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS') WHERE (pybls_invc_hdr_id = " .
        $docid . ")";
    return execUpdtInsSQL($updtSQL);
}

function getPyblsDocTtlPymnts($dochdrID, $docType)
{
    $strSql = "select SUM(y.amount_paid) amnt " .
        "from accb.accb_payments y " .
        "where y.src_doc_id = " . $dochdrID . " and y.src_doc_typ = '" . loc_db_escape_string($docType) . "'";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function getPyblsDocBlncngAccnt($srcDocID, $docType)
{
    $whrcls = " and (a.pybls_smmry_type !='6Grand Total' and 
a.pybls_smmry_type !='7Total Payments Made' and a.pybls_smmry_type !='8Outstanding Balance')";

    $strSql = "select 
        distinct liability_acnt_id, pybls_smmry_id 
        from accb.accb_pybls_amnt_smmrys a 
        where src_pybls_hdr_id = " . $srcDocID .
        " and src_pybls_type = '" . loc_db_escape_string($docType) .
        "'" . $whrcls . " order by pybls_smmry_id LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getPyblsPrepayDocCnt($dochdrID)
{
    $strSql = "select count(appld_prepymnt_doc_id) " .
        "from accb.accb_pybls_amnt_smmrys y " .
        "where y.src_pybls_hdr_id = " . $dochdrID . " and y.appld_prepymnt_doc_id >0 " .
        "Group by y.appld_prepymnt_doc_id having count(y.appld_prepymnt_doc_id)>1";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_PyblPrepayDocAcntID($prepayDocID)
{
    $strSql = "SELECT asset_expns_acnt_id, pybls_smmry_id " .
        "FROM accb.accb_pybls_amnt_smmrys a " .
        "WHERE(a.src_pybls_hdr_id = " . $prepayDocID .
        " and pybls_smmry_type = '1Initial Amount') ORDER BY pybls_smmry_id ASC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function get_PyblPrepayDocLbltyAcntID($prepayDocID)
{
    $strSql = "SELECT liability_acnt_id, pybls_smmry_id " .
        "FROM accb.accb_pybls_amnt_smmrys a " .
        "WHERE(a.src_pybls_hdr_id = " . $prepayDocID .
        " and pybls_smmry_type = '1Initial Amount') ORDER BY pybls_smmry_id ASC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function get_PyblPrepayDocAvlblAmnt($prepayDocID)
{
    $strSql = "SELECT invoice_amount-invc_amnt_appld_elswhr " .
        "FROM accb.accb_pybls_invc_hdr a " .
        "WHERE(a.pybls_invc_hdr_id = " . $prepayDocID .
        " and (invoice_amount-invc_amnt_appld_elswhr)>0)";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_RcvblPrepayDocRvnuAcntID($prepayDocID)
{
    $strSql = "SELECT rvnu_acnt_id, rcvbl_smmry_id " .
        "FROM accb.accb_rcvbl_amnt_smmrys a " .
        "WHERE(a.src_rcvbl_hdr_id = " . $prepayDocID .
        " and rcvbl_smmry_type = '1Initial Amount') ORDER BY rcvbl_smmry_id ASC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function get_RcvblPrepayDocRcvblAcntID($prepayDocID)
{
    $strSql = "SELECT rcvbl_acnt_id, rcvbl_smmry_id " .
        "FROM accb.accb_rcvbl_amnt_smmrys a " .
        "WHERE(a.src_rcvbl_hdr_id = " . $prepayDocID .
        " and rcvbl_smmry_type = '1Initial Amount') ORDER BY rcvbl_smmry_id ASC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function get_RcvblPrepayDocAvlblAmnt($prepayDocID)
{
    $strSql = "SELECT invoice_amount-invc_amnt_appld_elswhr " .
        "FROM accb.accb_rcvbls_invc_hdr a " .
        "WHERE(a.rcvbls_invc_hdr_id = " . $prepayDocID .
        " and (invoice_amount-invc_amnt_appld_elswhr)>0)";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function get_RcvblPrepayDocAppldAmnt($prepayDocID)
{
    $strSql = "SELECT invc_amnt_appld_elswhr " .
        "FROM accb.accb_rcvbls_invc_hdr a " .
        "WHERE(a.rcvbls_invc_hdr_id = " . $prepayDocID .
        " and (invc_amnt_appld_elswhr)>0)";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_RcvblPrepayDocUsages($prepayDocID, $rcvblDoctype)
{
    $strSql = "SELECT count(1) FROM (SELECT y.rcvbls_invc_number a, z.rcvbl_smmry_amnt || ' (' || y.approval_status || ')' b, '' c, 1 d, 
                z.appld_prepymnt_doc_id||'' e, accb.get_src_doc_type(z.appld_prepymnt_doc_id,'Customer') f 
            FROM accb.accb_rcvbls_invc_hdr y,accb.accb_rcvbl_amnt_smmrys z 
                WHERE y.rcvbls_invc_hdr_id =z.src_rcvbl_hdr_id and z.appld_prepymnt_doc_id > 0 
                    UNION 
            Select accb.get_src_doc_num(w.src_doc_id, w.src_doc_typ) a, 
            CASE WHEN (w.amount_paid>0 and w.change_or_balance <=0) or (w.amount_paid < 0 and w.change_or_balance >= 0) THEN 
                Round(((w.amount_paid/abs(w.amount_paid))*w.amount_paid)-w.change_or_balance,2)|| ' (' || w.pymnt_vldty_status || ')' 
                ELSE w.amount_paid || ' (' || w.pymnt_vldty_status || ')' END b, '' c, 1 d, 
            w.prepay_doc_id||'' e, prepay_doc_type f FROM accb.accb_payments w WHERE w.prepay_doc_id>0 and prepay_doc_type ilike '%Customer%' and pymnt_vldty_status='VALID') tbl1 " .
        "WHERE(tbl1.e = '' || " . $prepayDocID . " and tbl1.f = '" . loc_db_escape_string($rcvblDoctype) . "')";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_PyblPrepayDocUsages($prepayDocID, $rcvblDoctype)
{
    $strSql = "SELECT count(1) FROM (SELECT y.pybls_invc_number a, z.pybls_smmry_amnt || ' (' || y.approval_status || ')' b, '' c, 1 d, 
                z.appld_prepymnt_doc_id||'' e, accb.get_src_doc_type(z.appld_prepymnt_doc_id,'Supplier') f 
            FROM accb.accb_pybls_invc_hdr y,accb.accb_pybls_amnt_smmrys z 
                WHERE y.pybls_invc_hdr_id =z.src_pybls_hdr_id and z.appld_prepymnt_doc_id > 0 
                    UNION 
            Select accb.get_src_doc_num(w.src_doc_id, w.src_doc_typ) a, 
            CASE WHEN (w.amount_paid>0 and w.change_or_balance <=0) or (w.amount_paid < 0 and w.change_or_balance >= 0) THEN 
                Round(((w.amount_paid/abs(w.amount_paid))*w.amount_paid)-w.change_or_balance,2) || ' (' || w.pymnt_vldty_status || ')' 
                ELSE w.amount_paid || ' (' || w.pymnt_vldty_status || ')' END b, '' c, 1 d, 
            w.prepay_doc_id || '' e, prepay_doc_type f FROM accb.accb_payments w WHERE w.prepay_doc_id>0 and prepay_doc_type ilike '%Supplier%' and pymnt_vldty_status='VALID') tbl1 " .
        "WHERE(tbl1.e = '' || " . $prepayDocID . " and tbl1.f = '" . loc_db_escape_string($rcvblDoctype) . "')";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_PyblPrepayDocAppldAmnt($prepayDocID)
{
    $strSql = "SELECT invc_amnt_appld_elswhr " .
        "FROM accb.accb_pybls_invc_hdr a " .
        "WHERE(a.pybls_invc_hdr_id = " . $prepayDocID .
        " and (invc_amnt_appld_elswhr)>0)";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function reCalcPyblInvcSmmrys($srcDocID, $srcDocType, $invcCurrID)
{
    global $usrID;
    $strSql = "select accb.recalcpyblssmmrys(" . $srcDocID .
        ",'" . loc_db_escape_string($srcDocType) .
        "'," . $usrID . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "ERROR:No Result";
}

function validatePyblInvcLns($docHdrID, $docType, $invcAmnt, &$errMsg)
{
    $errMsg = "";
    $sameprepayCnt = getPyblsPrepayDocCnt($docHdrID);
    if ($sameprepayCnt > 1) {
        $errMsg .= "Same Prepayment Cannot be Applied More than Once!";
        return false;
    }
    return true;
}

function get_PyblsInvc_Attachments($searchWord, $offset, $limit_size, $batchID, &$attchSQL)
{
    $strSql = "SELECT a.attchmnt_id, a.doc_hdr_id, a.attchmnt_desc, a.file_name " .
        "FROM accb.accb_pybl_doc_attchmnts a " .
        "WHERE(a.attchmnt_desc ilike '" . loc_db_escape_string($searchWord) .
        "' and a.doc_hdr_id = " . $batchID . ") ORDER BY a.attchmnt_id LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_PyblsInvc_Attachments($searchWord, $batchID)
{
    $strSql = "SELECT count(1) " .
        "FROM accb.accb_pybl_doc_attchmnts a " .
        "WHERE(a.attchmnt_desc ilike '" . loc_db_escape_string($searchWord) .
        "' and a.doc_hdr_id = " . $batchID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function getPyblsInvcAttchmtDocs($batchid)
{
    $sqlStr = "SELECT attchmnt_id, file_name, attchmnt_desc
  FROM accb.accb_pybl_doc_attchmnts WHERE 1=1 AND file_name != '' AND doc_hdr_id = " . $batchid;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function updatePyblsInvcDocFlNm($attchmnt_id, $file_name)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE accb.accb_pybl_doc_attchmnts SET file_name='"
        . loc_db_escape_string($file_name) .
        "', last_update_by=" . $usrID .
        ", last_update_date='" . $dateStr . "'
                WHERE attchmnt_id=" . $attchmnt_id;
    return execUpdtInsSQL($insSQL);
}

function getNewPyblsInvcDocID()
{
    $strSql = "select nextval('accb.accb_pybl_doc_attchmnts_attchmnt_id_seq')";
    $result = executeSQLNoParams($strSql);

    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function createPyblsInvcDoc($attchmnt_id, $hdrid, $attchmnt_desc, $file_name)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO accb.accb_pybl_doc_attchmnts(
            attchmnt_id, doc_hdr_id, attchmnt_desc, file_name, created_by, 
            creation_date, last_update_by, last_update_date)
             VALUES (" . $attchmnt_id . ", " . $hdrid . ",'"
        . loc_db_escape_string($attchmnt_desc) . "','"
        . loc_db_escape_string($file_name) . "',"
        . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";
    return execUpdtInsSQL($insSQL);
}

function deletePyblsInvcDoc($pkeyID, $docTrnsNum = "")
{
    $insSQL = "DELETE FROM accb.accb_pybl_doc_attchmnts WHERE attchmnt_id = " . $pkeyID;
    $affctd1 = execUpdtInsSQL($insSQL, "Trns. No:" . $docTrnsNum);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Attached Document(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function uploadDaPyblsInvcDoc($attchmntID, &$nwImgLoc, &$errMsg)
{
    global $tmpDest;
    global $ftp_base_db_fldr;
    global $usrID;
    global $fldrPrfx;
    global $smplTokenWord1;

    $msg = "";
    $allowedExts = array(
        'png', 'jpg', 'gif', 'jpeg', 'bmp', 'pdf', 'xls', 'xlsx',
        'doc', 'docx', 'ppt', 'pptx', 'txt', 'csv'
    );

    if (isset($_FILES["daPyblsInvcAttchmnt"])) {
        $flnm = $_FILES["daPyblsInvcAttchmnt"]["name"];
        $temp = explode(".", $flnm);
        $extension = end($temp);
        if ($_FILES["daPyblsInvcAttchmnt"]["error"] > 0) {
            $msg .= "Return Code: " . $_FILES["daPyblsInvcAttchmnt"]["error"] . "<br>";
        } else {
            $msg .= "Uploaded File: " . $_FILES["daPyblsInvcAttchmnt"]["name"] . "<br>";
            $msg .= "Type: " . $_FILES["daPyblsInvcAttchmnt"]["type"] . "<br>";
            $msg .= "Size: " . round(($_FILES["daPyblsInvcAttchmnt"]["size"]) / (1024 * 1024), 2) . " MB<br>";
            //$msg .= "Temp file: " . $_FILES["daPyblsInvcAttchmnt"]["tmp_name"] . "<br>";
            if ((($_FILES["daPyblsInvcAttchmnt"]["type"] == "image/gif") || ($_FILES["daPyblsInvcAttchmnt"]["type"] == "image/jpeg") || ($_FILES["daPyblsInvcAttchmnt"]["type"] == "image/jpg") || ($_FILES["daPyblsInvcAttchmnt"]["type"] == "image/pjpeg") || ($_FILES["daPyblsInvcAttchmnt"]["type"] == "image/x-png") ||
                ($_FILES["daPyblsInvcAttchmnt"]["type"] == "image/png") || in_array($extension, $allowedExts)) && ($_FILES["daPyblsInvcAttchmnt"]["size"] <
                10000000)) {
                $nwFileName = encrypt1($attchmntID . "." . $extension, $smplTokenWord1) . "." . $extension;
                $img_src = $fldrPrfx . $tmpDest . "$nwFileName";
                move_uploaded_file($_FILES["daPyblsInvcAttchmnt"]["tmp_name"], $img_src);
                $ftp_src = $ftp_base_db_fldr . "/PyblDocs/$attchmntID" . "." . $extension;
                if (file_exists($img_src)) {
                    copy("$img_src", "$ftp_src");
                    $dateStr = getDB_Date_time();
                    $updtSQL = "UPDATE accb.accb_pybl_doc_attchmnts
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
                $msg .= "Invalid file!<br/>File Size must be below 10MB and<br/>File Type must be in the ff:<br/>" . implode(
                    ", ",
                    $allowedExts
                );
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

function get_RcvblsInvc_Attachments($searchWord, $offset, $limit_size, $batchID, &$attchSQL)
{
    $strSql = "SELECT a.attchmnt_id, a.doc_hdr_id, a.attchmnt_desc, a.file_name " .
        "FROM accb.accb_rcvbl_doc_attchmnts a " .
        "WHERE(a.attchmnt_desc ilike '" . loc_db_escape_string($searchWord) .
        "' and a.doc_hdr_id = " . $batchID . ") ORDER BY a.attchmnt_id LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_RcvblsInvc_Attachments($searchWord, $batchID)
{
    $strSql = "SELECT count(1) " .
        "FROM accb.accb_rcvbl_doc_attchmnts a " .
        "WHERE(a.attchmnt_desc ilike '" . loc_db_escape_string($searchWord) .
        "' and a.doc_hdr_id = " . $batchID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function getRcvblsInvcAttchmtDocs($batchid)
{
    $sqlStr = "SELECT attchmnt_id, file_name, attchmnt_desc
  FROM accb.accb_rcvbl_doc_attchmnts WHERE 1=1 AND file_name != '' AND doc_hdr_id = " . $batchid;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function updateRcvblsInvcDocFlNm($attchmnt_id, $file_name)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE accb.accb_rcvbl_doc_attchmnts SET file_name='"
        . loc_db_escape_string($file_name) .
        "', last_update_by=" . $usrID .
        ", last_update_date='" . $dateStr . "'
                WHERE attchmnt_id=" . $attchmnt_id;
    return execUpdtInsSQL($insSQL);
}

function getNewRcvblsInvcDocID()
{
    $strSql = "select nextval('accb.accb_rcvbl_doc_attchmnts_attchmnt_id_seq')";
    $result = executeSQLNoParams($strSql);

    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function createRcvblsInvcDoc($attchmnt_id, $hdrid, $attchmnt_desc, $file_name)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO accb.accb_rcvbl_doc_attchmnts(
            attchmnt_id, doc_hdr_id, attchmnt_desc, file_name, created_by, 
            creation_date, last_update_by, last_update_date)
             VALUES (" . $attchmnt_id . ", " . $hdrid . ",'"
        . loc_db_escape_string($attchmnt_desc) . "','"
        . loc_db_escape_string($file_name) . "',"
        . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";
    return execUpdtInsSQL($insSQL);
}

function deleteRcvblsInvcDoc($pkeyID, $docTrnsNum = "")
{
    $insSQL = "DELETE FROM accb.accb_rcvbl_doc_attchmnts WHERE attchmnt_id = " . $pkeyID;
    $affctd1 = execUpdtInsSQL($insSQL, "Trns. No:" . $docTrnsNum);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Attached Document(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function uploadDaRcvblsInvcDoc($attchmntID, &$nwImgLoc, &$errMsg)
{
    global $tmpDest;
    global $ftp_base_db_fldr;
    global $usrID;
    global $fldrPrfx;
    global $smplTokenWord1;

    $msg = "";
    $allowedExts = array(
        'png', 'jpg', 'gif', 'jpeg', 'bmp', 'pdf', 'xls', 'xlsx',
        'doc', 'docx', 'ppt', 'pptx', 'txt', 'csv'
    );

    if (isset($_FILES["daRcvblsInvcAttchmnt"])) {
        $flnm = $_FILES["daRcvblsInvcAttchmnt"]["name"];
        $temp = explode(".", $flnm);
        $extension = end($temp);
        if ($_FILES["daRcvblsInvcAttchmnt"]["error"] > 0) {
            $msg .= "Return Code: " . $_FILES["daRcvblsInvcAttchmnt"]["error"] . "<br>";
        } else {
            $msg .= "Uploaded File: " . $_FILES["daRcvblsInvcAttchmnt"]["name"] . "<br>";
            $msg .= "Type: " . $_FILES["daRcvblsInvcAttchmnt"]["type"] . "<br>";
            $msg .= "Size: " . round(($_FILES["daRcvblsInvcAttchmnt"]["size"]) / (1024 * 1024), 2) . " MB<br>";
            //$msg .= "Temp file: " . $_FILES["daRcvblsInvcAttchmnt"]["tmp_name"] . "<br>";
            if ((($_FILES["daRcvblsInvcAttchmnt"]["type"] == "image/gif") || ($_FILES["daRcvblsInvcAttchmnt"]["type"] == "image/jpeg") || ($_FILES["daRcvblsInvcAttchmnt"]["type"] == "image/jpg") || ($_FILES["daRcvblsInvcAttchmnt"]["type"] == "image/pjpeg") || ($_FILES["daRcvblsInvcAttchmnt"]["type"] == "image/x-png") ||
                ($_FILES["daRcvblsInvcAttchmnt"]["type"] == "image/png") || in_array($extension, $allowedExts)) && ($_FILES["daRcvblsInvcAttchmnt"]["size"] <
                10000000)) {
                $nwFileName = encrypt1($attchmntID . "." . $extension, $smplTokenWord1) . "." . $extension;
                $img_src = $fldrPrfx . $tmpDest . "$nwFileName";
                move_uploaded_file($_FILES["daRcvblsInvcAttchmnt"]["tmp_name"], $img_src);
                $ftp_src = $ftp_base_db_fldr . "/RcvblDocs/$attchmntID" . "." . $extension;
                if (file_exists($img_src)) {
                    copy("$img_src", "$ftp_src");
                    $dateStr = getDB_Date_time();
                    $updtSQL = "UPDATE accb.accb_rcvbl_doc_attchmnts
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
                $msg .= "Invalid file!<br/>File Size must be below 10MB and<br/>File Type must be in the ff:<br/>" . implode(
                    ", ",
                    $allowedExts
                );
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

function get_RcvblsDocHdr($searchWord, $searchIn, $offset, $limit_size, $orgID, $shwUnpstdOnly, $shwUnpaidOnly)
{
    $strSql = "";
    $whrcls = "";
    $unpstdCls = "";
    if ($shwUnpstdOnly) {
        $unpstdCls .= " AND (accb.is_gl_batch_pstd(a.gl_batch_id)!='1' and a.approval_status IN ('Approved','Cancelled'))";
    }
    if ($shwUnpaidOnly) {
        $unpstdCls .= " AND (round(a.invoice_amount-a.amnt_paid,2)>0 and a.approval_status IN ('Approved'))";
    }
    if ($searchIn == "Document Number") {
        $whrcls = " and (a.rcvbls_invc_number ilike '" . loc_db_escape_string($searchWord) . "' or trim(to_char(a.rcvbls_invc_hdr_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Document Description") {
        $whrcls = " and (a.comments_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Document Classification") {
        $whrcls = " and (a.doc_tmplt_clsfctn ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Customer Name") {
        $whrcls = " and (a.customer_id IN (select c.cust_sup_id from 
scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
            "'))";
    } else if ($searchIn == "Customer's Doc. Number") {
        $whrcls = " and (a.cstmrs_doc_num ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Source Doc Number") {
        $whrcls = " and (a.src_doc_hdr_id IN (select d.invc_hdr_id from scm.scm_sales_invc_hdr d 
where d.invc_number ilike '" . loc_db_escape_string($searchWord) .
            "') or a.src_doc_hdr_id IN (select f.rcvbls_invc_hdr_id from accb.accb_rcvbls_invc_hdr f
where f.rcvbls_invc_number ilike '" . loc_db_escape_string($searchWord) .
            "'))";
    } else if ($searchIn == "Approval Status") {
        $whrcls = " and (a.approval_status ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Created By") {
        $whrcls = " and (sec.get_usr_name(a.created_by) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Currency") {
        $whrcls = " and (gst.get_pssbl_val(a.invc_curr_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT rcvbls_invc_hdr_id, rcvbls_invc_number, 
rcvbls_invc_type,comments_desc,gst.get_pssbl_val(a.invc_curr_id), round(a.invoice_amount,2), 
        round(a.amnt_paid,2), round(a.invoice_amount-a.amnt_paid,2), a.approval_status, scm.get_cstmr_splr_name(a.customer_id)
        FROM accb.accb_rcvbls_invc_hdr a 
        WHERE((a.org_id = " . $orgID . ")" . $whrcls . $unpstdCls .
        ") ORDER BY rcvbls_invc_hdr_id DESC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_RcvblsDoc($searchWord, $searchIn, $orgID, $shwUnpstdOnly, $shwUnpaidOnly)
{
    $strSql = "";
    $whrcls = "";
    $unpstdCls = "";
    if ($shwUnpstdOnly) {
        $unpstdCls .= " AND (accb.is_gl_batch_pstd(a.gl_batch_id)!='1' and a.approval_status IN ('Approved','Cancelled'))";
    }
    if ($shwUnpaidOnly) {
        $unpstdCls .= " AND (round(a.invoice_amount-a.amnt_paid,2)>0 and a.approval_status IN ('Approved'))";
    }
    if ($searchIn == "Document Number") {
        $whrcls = " and (a.rcvbls_invc_number ilike '" . loc_db_escape_string($searchWord) . "' or trim(to_char(a.rcvbls_invc_hdr_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Document Description") {
        $whrcls = " and (a.comments_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Document Classification") {
        $whrcls = " and (a.doc_tmplt_clsfctn ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Customer Name") {
        $whrcls = " and (a.customer_id IN (select c.cust_sup_id from 
scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
            "'))";
    } else if ($searchIn == "Customer's Doc. Number") {
        $whrcls = " and (a.cstmrs_doc_num ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Source Doc Number") {
        $whrcls = " and (a.src_doc_hdr_id IN (select d.invc_hdr_id from scm.scm_sales_invc_hdr d 
where d.invc_number ilike '" . loc_db_escape_string($searchWord) .
            "') or a.src_doc_hdr_id IN (select f.rcvbls_invc_hdr_id from accb.accb_rcvbls_invc_hdr f
where f.rcvbls_invc_number ilike '" . loc_db_escape_string($searchWord) .
            "'))";
    } else if ($searchIn == "Approval Status") {
        $whrcls = " and (a.approval_status ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Created By") {
        $whrcls = " and (sec.get_usr_name(a.created_by) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Currency") {
        $whrcls = " and (gst.get_pssbl_val(a.invc_curr_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT count(1) 
        FROM accb.accb_rcvbls_invc_hdr a 
        WHERE((a.org_id = " . $orgID . ")" . $whrcls . $unpstdCls . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_One_RcvblsInvcDocHdr($hdrID)
{
    $strSql = "SELECT rcvbls_invc_hdr_id, to_char(to_timestamp(rcvbls_invc_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), 
       created_by, sec.get_usr_name(a.created_by), rcvbls_invc_number, rcvbls_invc_type, 
       comments_desc, src_doc_hdr_id, customer_id, scm.get_cstmr_splr_name(a.customer_id),
       customer_site_id, scm.get_cstmr_splr_site_name(a.customer_site_id), 
       approval_status, next_aproval_action, invoice_amount, 
       payment_terms, src_doc_type, pymny_method_id, accb.get_pymnt_mthd_name(a.pymny_method_id), 
       amnt_paid, gl_batch_id, accb.get_gl_batch_name(a.gl_batch_id),
       cstmrs_doc_num, doc_tmplt_clsfctn, invc_curr_id, gst.get_pssbl_val(a.invc_curr_id),
       event_rgstr_id, evnt_cost_category, event_doc_type, balancing_accnt_id,
       accb.get_accnt_num(a.balancing_accnt_id) || '.' || accb.get_accnt_name(a.balancing_accnt_id) balancing_accnt,
       accb.is_gl_batch_pstd(a.gl_batch_id) is_pstd, advc_pay_ifo_doc_id, advc_pay_ifo_doc_typ, 
       invc_amnt_appld_elswhr, debt_gl_batch_id, accb.get_gl_batch_name(a.debt_gl_batch_id), 
       scm.get_src_doc_num(a.src_doc_hdr_id, a.src_doc_type), func_curr_rate   
  FROM accb.accb_rcvbls_invc_hdr a
  WHERE ((a.rcvbls_invc_hdr_id = " . $hdrID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function reCalcRcvblInvcSmmrys($srcDocID, $srcDocType, $invcCurrID)
{
    global $usrID;
    $strSql = "select accb.recalcrcvblssmmrys(" . $srcDocID .
        ",'" . loc_db_escape_string($srcDocType) .
        "'," . $usrID . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "ERROR:No Result";
    /* $grndAmnt = getRcvblsDocGrndAmnt($srcDocID);
      //Grand Total
      $smmryNm = "Grand Total";
      $smmryID = getRcvblsSmmryItmID("6Grand Total", -1, $srcDocID, $srcDocType, $smmryNm);
      if ($smmryID <= 0) {
      $curlnID = getNewRcvblsLnID();
      createRcvblsDocDet($curlnID, $srcDocID, "6Grand Total", $smmryNm, $grndAmnt, $invcCurrID, -1, $srcDocType, true, "Increase", -1, "Increase", -1, -1, "VALID", -1, -1, -1, 0, 0, 0, 0, -1, 1, 0, "", ",", -1, -1, -1);
      } else {
      updtRcvblsDocDet($smmryID, $srcDocID, "6Grand Total", $smmryNm, $grndAmnt, $invcCurrID, -1, $srcDocType, true, "Increase", -1, "Increase", -1, -1, "VALID", -1, -1, -1, 0, 0, 0, 0, -1, 1, 0, "", ",", -1, -1, -1);
      }

      //7Total Payments Received
      $smmryNm = "Total Payments Made";
      $smmryID = getRcvblsSmmryItmID("7Total Payments Made", -1, $srcDocID, $srcDocType, $smmryNm);
      $pymntsAmnt = getRcvblsDocTtlPymnts($srcDocID, $srcDocType);

      if ($smmryID <= 0) {
      $curlnID = getNewRcvblsLnID();
      createRcvblsDocDet($curlnID, $srcDocID, "7Total Payments Made", $smmryNm, $pymntsAmnt, $invcCurrID, -1, $srcDocType, true, "Increase", -1, "Increase", -1, -1, "VALID", -1, -1, -1, 0, 0, 0, 0, -1, 1, 0, "", ",", -1, -1, -1);
      } else {
      updtRcvblsDocDet($smmryID, $srcDocID, "7Total Payments Made", $smmryNm, $pymntsAmnt, $invcCurrID, -1, $srcDocType, true, "Increase", -1, "Increase", -1, -1, "VALID", -1, -1, -1, 0, 0, 0, 0, -1, 1, 0, "", ",", -1, -1, -1);
      }
      updtRcvblsHdrAmntPaid($srcDocID, $pymntsAmnt);
      //7Total Payments Received
      $smmryNm = "Outstanding Balance";
      $smmryID = getRcvblsSmmryItmID("8Outstanding Balance", -1, $srcDocID, $srcDocType, $smmryNm);
      $outstndngAmnt = $grndAmnt - $pymntsAmnt;
      if ($smmryID <= 0) {
      $curlnID = getNewRcvblsLnID();
      createRcvblsDocDet($curlnID, $srcDocID, "8Outstanding Balance", $smmryNm, $outstndngAmnt, $invcCurrID, -1, $srcDocType, true, "Increase", -1, "Increase", -1, -1, "VALID", -1, -1, -1, 0, 0, 0, 0, -1, 1, 0, "", ",", -1, -1, -1);
      } else {
      updtRcvblsDocDet($smmryID, $srcDocID, "8Outstanding Balance", $smmryNm, $outstndngAmnt, $invcCurrID, -1, $srcDocType, true, "Increase", -1, "Increase", -1, -1, "VALID", -1, -1, -1, 0, 0, 0, 0, -1, 1, 0, "", ",", -1, -1, -1);
      } */
}

function validateRcvblInvcLns($docHdrID, $docType, $accbRcvblsInvcTtlAmnt, &$errMsg)
{
    $sameprepayCnt = getRcvblsPrepayDocCnt($docHdrID);
    /* $grndAmnt = getRcvblsDocGrndAmnt($docHdrID);
      if (round($accbRcvblsInvcTtlAmnt, 2) != round($grndAmnt, 2)) {
      $errMsg .= "Total Invoice Amount must be the Same as the Invoice Grand Total!";
      return false;
      } */
    if ($sameprepayCnt > 1) {
        $errMsg .= "Same Prepayment Cannot be Applied More than Once!";
        return false;
    }
    /* $blcngAccntID = -1;
      $costAccntID = -1;
      for (int i = 0; i < this.smmryDataGridView.Rows.Count; i++)
      {
      this.dfltFill(i);
      string lineTypeNm = this.smmryDataGridView.Rows[i].Cells[0].Value.ToString();

      int codeBhndID = -1;
      int.TryParse(this.smmryDataGridView.Rows[i].Cells[10].Value.ToString(), out codeBhndID);

      long prepayDocID = -1;
      long.TryParse(this.smmryDataGridView.Rows[i].Cells[17].Value.ToString(), out prepayDocID);

      double prepayLnAmnt = -1;
      double.TryParse(this.smmryDataGridView.Rows[i].Cells[2].Value.ToString(), out prepayLnAmnt);
      if (prepayDocID > 0)
      {
      if (!Global.isRcvblPrepayDocValid(prepayDocID, invcCurrID, cstmrID))
      {
      Global.mnFrm.cmCde.showMsg("An Invalid Prepayment has been Applied!", 0);
      return false;
      }
      }
      if (lineTypeNm == "5Applied Prepayment")
      {
      if ((docType == "Customer Advance Payment"
      || docType == "Customer Credit Memo (InDirect Topup)"
      || docType == "Customer Debit Memo (InDirect Refund)"))
      {
      Global.mnFrm.cmCde.showMsg("Cannot Apply Prepayments to this Document Type!", 0);
      return false;
      }
      else
      {
      double prepayAvlblAmnt = Global.get_RcvblPrepayDocAvlblAmnt(prepayDocID);
      if (prepayLnAmnt > prepayAvlblAmnt)
      {
      Global.mnFrm.cmCde.showMsg("Applied Prepayment Amount Exceeds the \r\nAvailable Amount on the Source Document!", 0);
      return false;
      }
      }
      }

      string incrDcrs1 = this.smmryDataGridView.Rows[i].Cells[8].Value.ToString();
      int accntID1 = -1;
      int.TryParse(this.smmryDataGridView.Rows[i].Cells[10].Value.ToString(), out accntID1);
      string isdbtCrdt1 = Global.mnFrm.cmCde.dbtOrCrdtAccnt(accntID1, incrDcrs1.Substring(0, 1));

      string incrDcrs2 = this.smmryDataGridView.Rows[i].Cells[12].Value.ToString();
      int accntID2 = -1;
      int.TryParse(this.smmryDataGridView.Rows[i].Cells[14].Value.ToString(), out accntID2);

      double lnAmnt = 0;
      double.TryParse(this.smmryDataGridView.Rows[i].Cells[21].Value.ToString(), out lnAmnt);
      if (lnAmnt == 0)
      {
      Global.mnFrm.cmCde.showMsg("Please Enter an Amount Other than Zero for all Lines!", 0);
      return false;
      }
      if (accntID1 <= 0 || accntID2 <= 0)
      {
      Global.mnFrm.cmCde.showMsg("Please provide the Costing and Balancing Account for all Lines!", 0);
      return false;
      }

      string isdbtCrdt2 = Global.mnFrm.cmCde.dbtOrCrdtAccnt(accntID2, incrDcrs2.Substring(0, 1));
      if (i == 0)
      {
      blcngAccntID = accntID2;
      costAccntID = accntID1;
      }

      if (blcngAccntID != accntID2)
      {
      Global.mnFrm.cmCde.showMsg("Balancing Account must be the Same for all Lines!", 0);
      return false;
      }

      if (docType == "Customer Advance Payment"
      && costAccntID != accntID1)
      {
      Global.mnFrm.cmCde.showMsg("Costing Account must be the Same for all " +
      "\r\nLines in a Customer Advance Payment Document!", 0);
      return false;
      }

      string acntType = Global.mnFrm.cmCde.getAccntType(accntID1);

      if (docType == "Customer Advance Payment"
      && acntType != "L")
      {
      Global.mnFrm.cmCde.showMsg("Must Increase an Account Payable(Customer Advance Payments Account) for all " +
      "\r\nLines in a Customer Advance Payment Document!", 0);
      return false;
      }

      if (isdbtCrdt1.ToUpper() == isdbtCrdt2.ToUpper())
      {
      if (docType == "Customer Standard Payment"
      || docType == "Customer Advance Payment"
      || docType == "Direct Topup from Customer"
      || docType == "Customer Debit Memo (InDirect Refund)")
      {
      if (lineTypeNm == "1Initial Amount")
      {
      Global.mnFrm.cmCde.showMsg("Row " + (i + 1).ToString() +
      ":- Must Increase Revenue or Customer Advance Payment Account!", 0);
      return false;
      }
      if (lineTypeNm == "2Tax")
      {
      Global.mnFrm.cmCde.showMsg("Row " + (i + 1).ToString() +
      ":- Must Increase Sales Tax Expense or Increase/Decrease Taxes Payable Account!", 0);
      return false;
      }
      if (lineTypeNm == "3Discount")
      {
      Global.mnFrm.cmCde.showMsg("Row " + (i + 1).ToString() +
      ":- Must Increase Sales Discounts (Contra Revenue) Account!", 0);
      return false;
      }
      if (lineTypeNm == "4Extra Charge")
      {
      Global.mnFrm.cmCde.showMsg("Row " + (i + 1).ToString() +
      ":- Must Increase Revenue Account!", 0);
      return false;
      }
      if (docType == "Customer Standard Payment"
      || docType == "Direct Topup from Customer")
      {
      if (lineTypeNm == "5Applied Prepayment")
      {
      Global.mnFrm.cmCde.showMsg("Row " + (i + 1).ToString() +
      ":- Must Decrease Customer Advance Payment Account or Liability Account!", 0);
      return false;
      }
      }
      }
      else
      {
      if (lineTypeNm == "1Initial Amount")
      {
      Global.mnFrm.cmCde.showMsg("Row " + (i + 1).ToString() +
      ":- Must Decrease a Revenue or Customer Advance Payment Account!", 0);
      return false;
      }
      if (lineTypeNm == "2Tax")
      {
      Global.mnFrm.cmCde.showMsg("Row " + (i + 1).ToString() +
      ":- Must Decrease a Sales Tax Expense or Increase/Decrease a Taxes Payable Account!", 0);
      return false;
      }
      if (lineTypeNm == "3Discount")
      {
      Global.mnFrm.cmCde.showMsg("Row " + (i + 1).ToString() +
      ":- Must Increase Sales Discounts (Contra Revenue) Account!", 0);
      return false;
      }
      if (lineTypeNm == "4Extra Charge")
      {
      Global.mnFrm.cmCde.showMsg("Row " + (i + 1).ToString() +
      ":- Must Decrease Revenue Account!", 0);
      return false;
      }
      if (docType == "Direct Refund to Customer")
      {
      if (lineTypeNm == "5Applied Prepayment")
      {
      Global.mnFrm.cmCde.showMsg("Row " + (i + 1).ToString() +
      ":- Must Decrease a Liability Account!", 0);
      return false;
      }
      }
      }
      }
      } */
    return true;
}

function getNewRcvblsLnID()
{
    $strSql = "select nextval('accb.accb_rcvbl_amnt_smmrys_rcvbl_smmry_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function getLtstRcvblsIDNoInPrfx($prfxTxt)
{
    global $orgID;
    $strSql = "select count(rcvbls_invc_hdr_id) from accb.accb_rcvbls_invc_hdr WHERE org_id=" . $orgID .
        " and rcvbls_invc_number ilike '" . loc_db_escape_string($prfxTxt) . "%'";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return str_pad((((float) $row[0]) + 1) . "", 4, '0', STR_PAD_LEFT);
    }
    return "0001";
}

function createRcvblsDocHdr(
    $orgid,
    $docDte,
    $docNum,
    $docType,
    $docDesc,
    $srcDocHdrID,
    $cstmrID,
    $cstmrSiteID,
    $apprvlStatus,
    $nxtApprvlActn,
    $invcAmnt,
    $pymntTrms,
    $srcDocType,
    $pymntMthdID,
    $amntPaid,
    $glBtchID,
    $cstmrDocNum,
    $docTmpltClsftn,
    $currID,
    $amntAppld,
    $rgstrID,
    $costCtgry,
    $evntType,
    $accbRcvblsInvcDfltBalsAcntID,
    $accbRcvblDebtGlBatchID,
    $advcPayDocId,
    $advcPayDocTyp,
    $accbRcvblsInvcFuncCrncyRate
) {
    global $usrID;
    if ($docDte != "") {
        $docDte = cnvrtDMYToYMD($docDte);
    }
    $insSQL = "INSERT INTO accb.accb_rcvbls_invc_hdr(
            rcvbls_invc_date, created_by, creation_date, 
            last_update_by, last_update_date, rcvbls_invc_number, rcvbls_invc_type, 
            comments_desc, src_doc_hdr_id, customer_id, customer_site_id, 
            approval_status, next_aproval_action, org_id, invoice_amount, 
            payment_terms, src_doc_type, pymny_method_id, amnt_paid, gl_batch_id, 
            cstmrs_doc_num, doc_tmplt_clsfctn, invc_curr_id, invc_amnt_appld_elswhr,
            event_rgstr_id, evnt_cost_category, event_doc_type, 
            balancing_accnt_id, debt_gl_batch_id, advc_pay_ifo_doc_id, advc_pay_ifo_doc_typ, func_curr_rate) " .
        "VALUES ('" . loc_db_escape_string($docDte) .
        "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($docNum) .
        "', '" . loc_db_escape_string($docType) .
        "', '" . loc_db_escape_string($docDesc) .
        "', " . $srcDocHdrID .
        ", " . $cstmrID .
        ", " . $cstmrSiteID .
        ", '" . loc_db_escape_string($apprvlStatus) .
        "', '" . loc_db_escape_string($nxtApprvlActn) .
        "', " . $orgid .
        ", " . $invcAmnt .
        ", '" . loc_db_escape_string($pymntTrms) .
        "', '" . loc_db_escape_string($srcDocType) .
        "', " . $pymntMthdID .
        ", " . $amntPaid .
        ", " . $glBtchID .
        ", '" . loc_db_escape_string($cstmrDocNum) .
        "', '" . loc_db_escape_string($docTmpltClsftn) .
        "', " . $currID . ", " . $amntAppld . ", " . $rgstrID .
        ", '" . loc_db_escape_string($costCtgry) .
        "', '" . loc_db_escape_string($evntType) .
        "', " . $accbRcvblsInvcDfltBalsAcntID .
        ", " . $accbRcvblDebtGlBatchID .
        ", " . $advcPayDocId .
        ", '" . loc_db_escape_string($advcPayDocTyp) . "', " . $accbRcvblsInvcFuncCrncyRate .
        ")";
    return execUpdtInsSQL($insSQL);
}

function updtRcvblsDocHdr(
    $hdrID,
    $docDte,
    $docNum,
    $docType,
    $docDesc,
    $srcDocHdrID,
    $spplrID,
    $spplrSiteID,
    $apprvlStatus,
    $nxtApprvlActn,
    $invcAmnt,
    $pymntTrms,
    $srcDocType,
    $pymntMthdID,
    $amntPaid,
    $glBtchID,
    $spplrInvcNum,
    $docTmpltClsftn,
    $currID,
    $amntAppld,
    $rgstrID,
    $costCtgry,
    $evntType,
    $accbRcvblsInvcDfltBalsAcntID,
    $accbRcvblsInvcFuncCrncyRate
) {
    global $usrID;
    if ($docDte != "") {
        $docDte = cnvrtDMYToYMD($docDte);
    }
    $insSQL = "UPDATE accb.accb_rcvbls_invc_hdr
       SET rcvbls_invc_date='" . loc_db_escape_string($docDte) .
        "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS')" .
        ", rcvbls_invc_number='" . loc_db_escape_string($docNum) .
        "', rcvbls_invc_type='" . loc_db_escape_string($docType) .
        "', comments_desc='" . loc_db_escape_string($docDesc) .
        "', src_doc_hdr_id=" . $srcDocHdrID .
        ", customer_id=" . $spplrID .
        ", customer_site_id=" . $spplrSiteID .
        ", approval_status='" . loc_db_escape_string($apprvlStatus) .
        "', next_aproval_action='" . loc_db_escape_string($nxtApprvlActn) .
        "', invoice_amount=" . $invcAmnt .
        ", payment_terms='" . loc_db_escape_string($pymntTrms) .
        "', src_doc_type='" . loc_db_escape_string($srcDocType) .
        "', pymny_method_id=" . $pymntMthdID .
        ", amnt_paid=" . $amntPaid .
        ", gl_batch_id=" . $glBtchID .
        ", cstmrs_doc_num='" . loc_db_escape_string($spplrInvcNum) .
        "', doc_tmplt_clsfctn='" . loc_db_escape_string($docTmpltClsftn) .
        "', invc_curr_id=" . $currID .
        ", event_rgstr_id=" . $rgstrID .
        ", evnt_cost_category='" . loc_db_escape_string($costCtgry) .
        "', event_doc_type='" . loc_db_escape_string($evntType) .
        "', balancing_accnt_id=" . $accbRcvblsInvcDfltBalsAcntID .
        ", func_curr_rate=" . $accbRcvblsInvcFuncCrncyRate .
        " WHERE rcvbls_invc_hdr_id = " . $hdrID;
    return execUpdtInsSQL($insSQL);
}

function createRcvblsDocDet(
    $smmryID,
    $hdrID,
    $lineType,
    $lineDesc,
    $entrdAmnt,
    $entrdCurrID,
    $codeBhnd,
    $docType,
    $autoCalc,
    $incrDcrs1,
    $costngID,
    $incrDcrs2,
    $blncgAccntID,
    $prepayDocHdrID,
    $vldyStatus,
    $orgnlLnID,
    $funcCurrID,
    $accntCurrID,
    $funcCurrRate,
    $accntCurrRate,
    $funcCurrAmnt,
    $accntCurrAmnt,
    $initAmntLineID,
    $lineQty,
    $unitPrice,
    $refDocNo,
    $slctdBrkdwns,
    $ln_TaxID,
    $ln_WHTaxID,
    $ln_DscntID
) {
    $res = createRcvblsDocDet1(
        $smmryID,
        $hdrID,
        $lineType,
        $lineDesc,
        $entrdAmnt,
        $entrdCurrID,
        $codeBhnd,
        $docType,
        $autoCalc,
        $incrDcrs1,
        $costngID,
        $incrDcrs2,
        $blncgAccntID,
        $prepayDocHdrID,
        $vldyStatus,
        $orgnlLnID,
        $funcCurrID,
        $accntCurrID,
        $funcCurrRate,
        $accntCurrRate,
        $funcCurrAmnt,
        $accntCurrAmnt,
        $initAmntLineID,
        $lineQty,
        $unitPrice,
        $refDocNo,
        $slctdBrkdwns,
        $ln_TaxID,
        $ln_WHTaxID,
        $ln_DscntID
    );
    if ($ln_TaxID > 0 && isTaxAParent($ln_TaxID) === true) {
        $codeIDs = explode(",", trim(getTaxChildIDs($ln_TaxID), ", "));
        for ($y = 0; $y < count($codeIDs); $y++) {
            $ln_TaxID = (int) $codeIDs[$y];
            $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_TaxID);
            $dcntAMnt = getCodeAmnt($ln_DscntID, $entrdAmnt);
            $codeAmnt = getCodeAmnt($ln_TaxID, $entrdAmnt - $dcntAMnt);
            $lnSmmryLnID = getRcvblsLnDetID("2Tax", $ln_TaxID, $smmryID);
            $accnts = getRcvblBalncnAccnt("2Tax", $ln_TaxID, -1, -1, $docType);
            $funcCurrAmnt = $codeAmnt * $funcCurrRate;
            $accntCurrAmnt = $codeAmnt * $accntCurrRate;
            $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $entrdAmnt . ")";
            if ($lnSmmryLnID <= 0) {
                $lnSmmryLnID = getNewRcvblsLnID();
                $res += createRcvblsDocDet1(
                    $lnSmmryLnID,
                    $hdrID,
                    "2Tax",
                    $txlineDesc,
                    $codeAmnt,
                    $entrdCurrID,
                    $ln_TaxID,
                    $docType,
                    true,
                    $accnts[2],
                    $accnts[3],
                    $accnts[0],
                    $blncgAccntID,
                    -1,
                    $vldyStatus,
                    -1,
                    $funcCurrID,
                    $accntCurrID,
                    $funcCurrRate,
                    $accntCurrRate,
                    $funcCurrAmnt,
                    $accntCurrAmnt,
                    $smmryID,
                    1,
                    $codeAmnt,
                    $refDocNo,
                    ",",
                    -1,
                    -1,
                    -1
                );
            } else {
                $res += updtRcvblsDocDet1(
                    $lnSmmryLnID,
                    $hdrID,
                    "2Tax",
                    $txlineDesc,
                    $codeAmnt,
                    $entrdCurrID,
                    $ln_TaxID,
                    $docType,
                    true,
                    $accnts[2],
                    $accnts[3],
                    $accnts[0],
                    $blncgAccntID,
                    -1,
                    $vldyStatus,
                    -1,
                    $funcCurrID,
                    $accntCurrID,
                    $funcCurrRate,
                    $accntCurrRate,
                    $funcCurrAmnt,
                    $accntCurrAmnt,
                    $smmryID,
                    1,
                    $codeAmnt,
                    $refDocNo,
                    ",",
                    -1,
                    -1,
                    -1
                );
            }
        }
    } else if ($ln_TaxID > 0) {
        $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_TaxID);
        $dcntAMnt = getCodeAmnt($ln_DscntID, $entrdAmnt);
        $codeAmnt = getCodeAmnt($ln_TaxID, $entrdAmnt - $dcntAMnt);
        $lnSmmryLnID = getRcvblsLnDetID("2Tax", $ln_TaxID, $smmryID);
        $accnts = getRcvblBalncnAccnt("2Tax", $ln_TaxID, -1, -1, $docType);
        $funcCurrAmnt = $codeAmnt * $funcCurrRate;
        $accntCurrAmnt = $codeAmnt * $accntCurrRate;
        $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $entrdAmnt . ")";
        if ($lnSmmryLnID <= 0) {
            $lnSmmryLnID = getNewRcvblsLnID();
            $res += createRcvblsDocDet1(
                $lnSmmryLnID,
                $hdrID,
                "2Tax",
                $txlineDesc,
                $codeAmnt,
                $entrdCurrID,
                $ln_TaxID,
                $docType,
                true,
                $accnts[2],
                $accnts[3],
                $accnts[0],
                $blncgAccntID,
                -1,
                $vldyStatus,
                -1,
                $funcCurrID,
                $accntCurrID,
                $funcCurrRate,
                $accntCurrRate,
                $funcCurrAmnt,
                $accntCurrAmnt,
                $smmryID,
                1,
                $codeAmnt,
                $refDocNo,
                ",",
                -1,
                -1,
                -1
            );
        } else {
            $res += updtRcvblsDocDet1(
                $lnSmmryLnID,
                $hdrID,
                "2Tax",
                $txlineDesc,
                $codeAmnt,
                $entrdCurrID,
                $ln_TaxID,
                $docType,
                true,
                $accnts[2],
                $accnts[3],
                $accnts[0],
                $blncgAccntID,
                -1,
                $vldyStatus,
                -1,
                $funcCurrID,
                $accntCurrID,
                $funcCurrRate,
                $accntCurrRate,
                $funcCurrAmnt,
                $accntCurrAmnt,
                $smmryID,
                1,
                $codeAmnt,
                $refDocNo,
                ",",
                -1,
                -1,
                -1
            );
        }
    }

    if ($ln_WHTaxID > 0 && isTaxAParent($ln_WHTaxID) === true) {
        $codeIDs = explode(",", trim(getTaxChildIDs($ln_WHTaxID), ", "));
        for ($y = 0; $y < count($codeIDs); $y++) {
            $ln_WHTaxID = (int) $codeIDs[$y];
            $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_WHTaxID);
            $dcntAMnt = getCodeAmnt($ln_DscntID, $entrdAmnt);
            $codeAmnt = getCodeAmnt($ln_WHTaxID, $entrdAmnt - $dcntAMnt);
            $lnSmmryLnID = getRcvblsLnDetID("2Tax", $ln_WHTaxID, $smmryID);
            $accnts = getRcvblBalncnAccnt("2Tax", $ln_WHTaxID, -1, -1, $docType);
            $funcCurrAmnt = $codeAmnt * $funcCurrRate;
            $accntCurrAmnt = $codeAmnt * $accntCurrRate;
            $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $entrdAmnt . ")";
            if ($lnSmmryLnID <= 0) {
                $lnSmmryLnID = getNewRcvblsLnID();
                $res += createRcvblsDocDet1(
                    $lnSmmryLnID,
                    $hdrID,
                    "2Tax",
                    $txlineDesc,
                    $codeAmnt,
                    $entrdCurrID,
                    $ln_WHTaxID,
                    $docType,
                    true,
                    $accnts[2],
                    $accnts[3],
                    $accnts[0],
                    $blncgAccntID,
                    -1,
                    $vldyStatus,
                    -1,
                    $funcCurrID,
                    $accntCurrID,
                    $funcCurrRate,
                    $accntCurrRate,
                    $funcCurrAmnt,
                    $accntCurrAmnt,
                    $smmryID,
                    1,
                    $codeAmnt,
                    $refDocNo,
                    ",",
                    -1,
                    -1,
                    -1
                );
            } else {
                $res += updtRcvblsDocDet1(
                    $lnSmmryLnID,
                    $hdrID,
                    "2Tax",
                    $txlineDesc,
                    $codeAmnt,
                    $entrdCurrID,
                    $ln_WHTaxID,
                    $docType,
                    true,
                    $accnts[2],
                    $accnts[3],
                    $accnts[0],
                    $blncgAccntID,
                    -1,
                    $vldyStatus,
                    -1,
                    $funcCurrID,
                    $accntCurrID,
                    $funcCurrRate,
                    $accntCurrRate,
                    $funcCurrAmnt,
                    $accntCurrAmnt,
                    $smmryID,
                    1,
                    $codeAmnt,
                    $refDocNo,
                    ",",
                    -1,
                    -1,
                    -1
                );
            }
        }
    } else if ($ln_WHTaxID > 0) {
        $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_WHTaxID);
        $dcntAMnt = getCodeAmnt($ln_DscntID, $entrdAmnt);
        $codeAmnt = getCodeAmnt($ln_WHTaxID, $entrdAmnt - $dcntAMnt);
        $lnSmmryLnID = getRcvblsLnDetID("2Tax", $ln_WHTaxID, $smmryID);
        $accnts = getRcvblBalncnAccnt("2Tax", $ln_WHTaxID, -1, -1, $docType);
        $funcCurrAmnt = $codeAmnt * $funcCurrRate;
        $accntCurrAmnt = $codeAmnt * $accntCurrRate;
        $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $entrdAmnt . ")";
        if ($lnSmmryLnID <= 0) {
            $lnSmmryLnID = getNewRcvblsLnID();
            $res += createRcvblsDocDet1(
                $lnSmmryLnID,
                $hdrID,
                "2Tax",
                $txlineDesc,
                $codeAmnt,
                $entrdCurrID,
                $ln_WHTaxID,
                $docType,
                true,
                $accnts[2],
                $accnts[3],
                $accnts[0],
                $blncgAccntID,
                -1,
                $vldyStatus,
                -1,
                $funcCurrID,
                $accntCurrID,
                $funcCurrRate,
                $accntCurrRate,
                $funcCurrAmnt,
                $accntCurrAmnt,
                $smmryID,
                1,
                $codeAmnt,
                $refDocNo,
                ",",
                -1,
                -1,
                -1
            );
        } else {
            $res += updtRcvblsDocDet1(
                $lnSmmryLnID,
                $hdrID,
                "2Tax",
                $txlineDesc,
                $codeAmnt,
                $entrdCurrID,
                $ln_WHTaxID,
                $docType,
                true,
                $accnts[2],
                $accnts[3],
                $accnts[0],
                $blncgAccntID,
                -1,
                $vldyStatus,
                -1,
                $funcCurrID,
                $accntCurrID,
                $funcCurrRate,
                $accntCurrRate,
                $funcCurrAmnt,
                $accntCurrAmnt,
                $smmryID,
                1,
                $codeAmnt,
                $refDocNo,
                ",",
                -1,
                -1,
                -1
            );
        }
    }

    if ($ln_DscntID > 0 && isTaxAParent($ln_DscntID) === true) {
        $codeIDs = explode(",", trim(getTaxChildIDs($ln_DscntID), ", "));
        for ($y = 0; $y < count($codeIDs); $y++) {
            $ln_DscntID = (int) $codeIDs[$y];
            $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_DscntID);
            $codeAmnt = getCodeAmnt($ln_DscntID, $entrdAmnt);
            $lnSmmryLnID = getRcvblsLnDetID("3Discount", $ln_DscntID, $smmryID);
            $accnts = getRcvblBalncnAccnt("3Discount", $ln_DscntID, -1, -1, $docType);
            $funcCurrAmnt = $codeAmnt * $funcCurrRate;
            $accntCurrAmnt = $codeAmnt * $accntCurrRate;
            $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $entrdAmnt . ")";
            if ($lnSmmryLnID <= 0) {
                $lnSmmryLnID = getNewRcvblsLnID();
                $res += createRcvblsDocDet1(
                    $lnSmmryLnID,
                    $hdrID,
                    "3Discount",
                    $txlineDesc,
                    $codeAmnt,
                    $entrdCurrID,
                    $ln_DscntID,
                    $docType,
                    true,
                    $accnts[2],
                    $accnts[3],
                    $accnts[0],
                    $blncgAccntID,
                    -1,
                    $vldyStatus,
                    -1,
                    $funcCurrID,
                    $accntCurrID,
                    $funcCurrRate,
                    $accntCurrRate,
                    $funcCurrAmnt,
                    $accntCurrAmnt,
                    $smmryID,
                    1,
                    $codeAmnt,
                    $refDocNo,
                    ",",
                    -1,
                    -1,
                    -1
                );
            } else {
                $res += updtRcvblsDocDet1(
                    $lnSmmryLnID,
                    $hdrID,
                    "3Discount",
                    $txlineDesc,
                    $codeAmnt,
                    $entrdCurrID,
                    $ln_DscntID,
                    $docType,
                    true,
                    $accnts[2],
                    $accnts[3],
                    $accnts[0],
                    $blncgAccntID,
                    -1,
                    $vldyStatus,
                    -1,
                    $funcCurrID,
                    $accntCurrID,
                    $funcCurrRate,
                    $accntCurrRate,
                    $funcCurrAmnt,
                    $accntCurrAmnt,
                    $smmryID,
                    1,
                    $codeAmnt,
                    $refDocNo,
                    ",",
                    -1,
                    -1,
                    -1
                );
            }
        }
    } else if ($ln_DscntID > 0) {
        $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_DscntID);
        $codeAmnt = getCodeAmnt($ln_DscntID, $entrdAmnt);
        $lnSmmryLnID = getRcvblsLnDetID("3Discount", $ln_DscntID, $smmryID);
        $accnts = getRcvblBalncnAccnt("3Discount", $ln_DscntID, -1, -1, $docType);
        $funcCurrAmnt = $codeAmnt * $funcCurrRate;
        $accntCurrAmnt = $codeAmnt * $accntCurrRate;
        $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $entrdAmnt . ")";
        if ($lnSmmryLnID <= 0) {
            $lnSmmryLnID = getNewRcvblsLnID();
            $res += createRcvblsDocDet1(
                $lnSmmryLnID,
                $hdrID,
                "3Discount",
                $txlineDesc,
                $codeAmnt,
                $entrdCurrID,
                $ln_DscntID,
                $docType,
                true,
                $accnts[2],
                $accnts[3],
                $accnts[0],
                $blncgAccntID,
                -1,
                $vldyStatus,
                -1,
                $funcCurrID,
                $accntCurrID,
                $funcCurrRate,
                $accntCurrRate,
                $funcCurrAmnt,
                $accntCurrAmnt,
                $smmryID,
                1,
                $codeAmnt,
                $refDocNo,
                ",",
                -1,
                -1,
                -1
            );
        } else {
            $res += updtRcvblsDocDet1(
                $lnSmmryLnID,
                $hdrID,
                "3Discount",
                $txlineDesc,
                $codeAmnt,
                $entrdCurrID,
                $ln_DscntID,
                $docType,
                true,
                $accnts[2],
                $accnts[3],
                $accnts[0],
                $blncgAccntID,
                -1,
                $vldyStatus,
                -1,
                $funcCurrID,
                $accntCurrID,
                $funcCurrRate,
                $accntCurrRate,
                $funcCurrAmnt,
                $accntCurrAmnt,
                $smmryID,
                1,
                $codeAmnt,
                $refDocNo,
                ",",
                -1,
                -1,
                -1
            );
        }
    }
    return $res;
}

function createRcvblsDocDet1(
    $smmryID,
    $hdrID,
    $lineType,
    $lineDesc,
    $entrdAmnt,
    $entrdCurrID,
    $codeBhnd,
    $docType,
    $autoCalc,
    $incrDcrs1,
    $costngID,
    $incrDcrs2,
    $blncgAccntID,
    $prepayDocHdrID,
    $vldyStatus,
    $orgnlLnID,
    $funcCurrID,
    $accntCurrID,
    $funcCurrRate,
    $accntCurrRate,
    $funcCurrAmnt,
    $accntCurrAmnt,
    $initAmntLineID,
    $lineQty,
    $unitPrice,
    $refDocNo,
    $slctdBrkdwns,
    $ln_TaxID,
    $ln_WHTaxID,
    $ln_DscntID
) {
    global $usrID;
    $insSQL = "INSERT INTO accb.accb_rcvbl_amnt_smmrys(
            rcvbl_smmry_id, rcvbl_smmry_type, rcvbl_smmry_desc, rcvbl_smmry_amnt, 
            code_id_behind, src_rcvbl_type, src_rcvbl_hdr_id, created_by, 
            creation_date, last_update_by, last_update_date, auto_calc, incrs_dcrs1, 
            rvnu_acnt_id, incrs_dcrs2, rcvbl_acnt_id, appld_prepymnt_doc_id, 
            orgnl_line_id, validty_status, entrd_curr_id, func_curr_id, accnt_curr_id, 
            func_curr_rate, accnt_curr_rate, func_curr_amount, accnt_curr_amnt, 
            initial_amnt_line_id, line_qty, unit_price, ref_doc_number, slctd_amnt_brkdwns, tax_code_id, whtax_code_id, dscnt_code_id) 
            VALUES (" . $smmryID . ", '" . loc_db_escape_string($lineType) .
        "', '" . loc_db_escape_string($lineDesc) .
        "', " . $entrdAmnt .
        ", " . $codeBhnd .
        ", '" . loc_db_escape_string($docType) .
        "', " . $hdrID .
        ", " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . cnvrtBoolToBitStr($autoCalc) .
        "', '" . loc_db_escape_string($incrDcrs1) .
        "', " . $costngID .
        ", '" . loc_db_escape_string($incrDcrs2) .
        "', " . $blncgAccntID .
        ", " . $prepayDocHdrID .
        ", " . $orgnlLnID .
        ", '" . loc_db_escape_string($vldyStatus) .
        "', " . $entrdCurrID .
        ", " . $funcCurrID .
        ", " . $accntCurrID .
        ", " . $funcCurrRate .
        ", " . $accntCurrRate .
        ", " . $funcCurrAmnt .
        ", " . $accntCurrAmnt .
        ", " . $initAmntLineID .
        ", " . $lineQty .
        ", " . $unitPrice . ", '" . loc_db_escape_string($refDocNo) .
        "', '" . loc_db_escape_string($slctdBrkdwns) .
        "', " . $ln_TaxID .
        ", " . $ln_WHTaxID .
        ", " . $ln_DscntID .
        ")";
    return execUpdtInsSQL($insSQL);
}

function getRcvblBalncnAccnt($lineType, $codebhndID, $cstmrID, $prepayDocID, $docType)
{
    global $orgID;
    $res = array("Increase", /* Balancing Account */ "-1", "Increase", /* Charge Account */ "-1");
    $cstmrAccntID = "-1";

    if ($docType == "Customer Standard Payment" || $docType == "Customer Advance Payment" || $docType == "Direct Topup from Customer" || $docType == "Customer Credit Memo (InDirect Topup)") {
        $cstmrAccntID = getGnrlRecNm("scm.scm_cstmr_suplr", "cust_sup_id", "dflt_rcvbl_accnt_id", $cstmrID);
    } else { //if (docType == "Direct Refund to Customer")
        $cstmrAccntID = getGnrlRecNm("scm.scm_cstmr_suplr", "cust_sup_id", "dflt_pybl_accnt_id", $cstmrID);
    }

    $accntID = $cstmrAccntID;
    if ($accntID <= 0) {
        if ($docType == "Customer Standard Payment" || $docType == "Customer Advance Payment" || $docType == "Direct Topup from Customer" || $docType == "Customer Credit Memo (InDirect Topup)") {
            $dflACntID = get_DfltRcvblAcnt($orgID);
            $accntID = $dflACntID;
        } else {
            $dflACntID = get_DfltPyblAcnt($orgID);
            $accntID = $dflACntID;
        }
    }
    $res[1] = $accntID;
    if ($docType == "Customer Standard Payment" || $docType == "Customer Advance Payment" || $docType == "Direct Topup from Customer" || $docType == "Customer Credit Memo (InDirect Topup)") {
        if ($lineType == "1Initial Amount") {
            $res[0] = "Increase";
            $res[2] = "Increase";
            $res[3] = "-1";
            //res[3] = Global.get_DfltExpnsAcnt($orgID).ToString();
            return $res;
        }
        if ($lineType == "2Tax") {
            $taxAccntID = getGnrlRecNm("scm.scm_tax_codes", "code_id", "taxes_payables_accnt_id", $codebhndID);
            $taxExpAccntID = getGnrlRecNm("scm.scm_tax_codes", "code_id", "tax_expense_accnt_id", $codebhndID);
            $isRcvrbl = getGnrlRecNm("scm.scm_tax_codes", "code_id", "is_recovrbl_tax", $codebhndID);
            $isWthHldng = getGnrlRecNm("scm.scm_tax_codes", "code_id", "is_withldng_tax", $codebhndID);
            $res[0] = "Increase";
            if ($isRcvrbl == "1") {
                $res[2] = "Increase";
                $res[3] = $taxAccntID;
            } else if ($isWthHldng == "1") {
                $res[0] = "Decrease";
                $res[2] = "Increase";
                $res[3] = $taxExpAccntID;
            } else {
                //    string taxExpnsAccntID = Global.mnFrm.cmCde.getGnrlRecNm(
                //"scm.scm_tax_codes", "code_id", "tax_expense_accnt_id",
                //codebhndID);
                $res[2] = "Increase";
                $res[3] = $taxAccntID;
            }
            return $res;
        }
        if ($lineType == "3Discount") {
            //string taxAccntID = Global.mnFrm.cmCde.getGnrlRecNm(
            // "scm.scm_tax_codes", "code_id", "dscount_expns_accnt_id",
            // codebhndID);
            $res[0] = "Decrease";
            $res[2] = "Increase";
            $salesDscntAccntID = getGnrlRecNm("scm.scm_tax_codes", "code_id", "dscount_expns_accnt_id", $codebhndID);
            $res[2] = "Increase";
            $res[3] = $salesDscntAccntID;
            return $res;
        }
        if ($lineType == "4Extra Charge") {
            $res[0] = "Increase";
            $chrgeRvnuAccntID = getGnrlRecNm("scm.scm_tax_codes", "code_id", "chrge_revnu_accnt_id", $codebhndID);
            $res[2] = "Increase";
            $res[3] = $chrgeRvnuAccntID;
        }
        if ($docType == "Customer Standard Payment" || $docType == "Direct Topup from Customer") {
            if ($lineType == "5Applied Prepayment") {
                $prepayAccntID = -1;
                $prepayDocType = getGnrlRecNm("accb.accb_rcvbls_invc_hdr", "rcvbls_invc_hdr_id", "rcvbls_invc_type", $prepayDocID);
                $res[0] = "Decrease";
                $res[2] = "Decrease";
                if ($prepayDocType == "Customer Credit Memo (InDirect Topup)") {
                    $prepayAccntID = get_RcvblPrepayDocRcvblAcntID($prepayDocID);
                } else {
                    $prepayAccntID = get_RcvblPrepayDocRvnuAcntID($prepayDocID);
                }
                $res[3] = $prepayAccntID;
            }
        }
    } else {
        if ($lineType == "1Initial Amount") {
            $res[0] = "Increase";
            $res[2] = "Decrease";
            $res[3] = "-1";
            //res[3] = Global.get_DfltExpnsAcnt($orgID).ToString();
            return $res;
        }
        if ($lineType == "2Tax") {
            $taxAccntID = getGnrlRecNm("scm.scm_tax_codes", "code_id", "taxes_payables_accnt_id", $codebhndID);
            $isRcvrbl = getGnrlRecNm("scm.scm_tax_codes", "code_id", "is_recovrbl_tax", $codebhndID);
            $isWthHldng = getGnrlRecNm("scm.scm_tax_codes", "code_id", "is_withldng_tax", $codebhndID);
            $res[0] = "Increase";
            if ($isRcvrbl == "1") {
                $res[2] = "Decrease";
                $res[3] = $taxAccntID;
            } else if ($isWthHldng == "1") {
                $res[0] = "Decrease";
                $res[2] = "Increase";
                $res[3] = $taxAccntID;
            } else {
                //    string taxExpnsAccntID = Global.mnFrm.cmCde.getGnrlRecNm(
                //"scm.scm_tax_codes", "code_id", "tax_expense_accnt_id",
                //codebhndID);
                $res[2] = "Decrease";
                $res[3] = $taxAccntID;
            }
            return $res;
        }
        if ($lineType == "3Discount") {
            //string taxAccntID = Global.mnFrm.cmCde.getGnrlRecNm(
            // "scm.scm_tax_codes", "code_id", "dscount_expns_accnt_id",
            // codebhndID);
            $res[0] = "Decrease";
            $res[2] = "Decrease";
            $prchsDscntAccntID = getGnrlRecNm("scm.scm_tax_codes", "code_id", "prchs_dscnt_accnt_id", $codebhndID);
            $res[2] = "Decrease";
            $res[3] = $prchsDscntAccntID;
            return $res;
        }
        if ($lineType == "4Extra Charge") {
            $res[0] = "Increase";
            $chrgeRvnuAccntID = getGnrlRecNm("scm.scm_tax_codes", "code_id", "chrge_revnu_accnt_id", $codebhndID);
            $res[2] = "Decrease";
            $res[3] = $chrgeRvnuAccntID;
        }
        if ($docType == "Direct Refund to Customer") {
            if ($lineType == "5Applied Prepayment") {
                $prepayAccntID = get_RcvblPrepayDocRcvblAcntID($prepayDocID);
                $res[0] = "Decrease";
                $res[2] = "Decrease";
                $res[3] = $prepayAccntID;
            }
        }
    }
    return $res;
}

function getRcvblsLnDetID($trnsType, $ln_CodeID, $initAmntLnID)
{
    $strSql = "select rcvbl_smmry_id from accb.accb_rcvbl_amnt_smmrys where rcvbl_smmry_type='"
        . loc_db_escape_string($trnsType) . "' and initial_amnt_line_id = " . $initAmntLnID . " and code_id_behind=" . $ln_CodeID;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function updtRcvblsDocDet(
    $docDetID,
    $hdrID,
    $lineType,
    $lineDesc,
    $entrdAmnt,
    $entrdCurrID,
    $codeBhnd,
    $docType,
    $autoCalc,
    $incrDcrs1,
    $costngID,
    $incrDcrs2,
    $blncgAccntID,
    $prepayDocHdrID,
    $vldyStatus,
    $orgnlLnID,
    $funcCurrID,
    $accntCurrID,
    $funcCurrRate,
    $accntCurrRate,
    $funcCurrAmnt,
    $accntCurrAmnt,
    $initAmntLineID,
    $lineQty,
    $unitPrice,
    $refDocNo,
    $slctdBrkdwns,
    $ln_TaxID,
    $ln_WHTaxID,
    $ln_DscntID
) {
    $res = updtRcvblsDocDet1(
        $docDetID,
        $hdrID,
        $lineType,
        $lineDesc,
        $entrdAmnt,
        $entrdCurrID,
        $codeBhnd,
        $docType,
        $autoCalc,
        $incrDcrs1,
        $costngID,
        $incrDcrs2,
        $blncgAccntID,
        $prepayDocHdrID,
        $vldyStatus,
        $orgnlLnID,
        $funcCurrID,
        $accntCurrID,
        $funcCurrRate,
        $accntCurrRate,
        $funcCurrAmnt,
        $accntCurrAmnt,
        $initAmntLineID,
        $lineQty,
        $unitPrice,
        $refDocNo,
        $slctdBrkdwns,
        $ln_TaxID,
        $ln_WHTaxID,
        $ln_DscntID
    );
    $smmryID = $docDetID;
    if ($ln_TaxID > 0 && isTaxAParent($ln_TaxID) === true) {
        $codeIDs = explode(",", trim(getTaxChildIDs($ln_TaxID), ", "));
        for ($y = 0; $y < count($codeIDs); $y++) {
            $ln_TaxID = (int) $codeIDs[$y];
            $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_TaxID);
            $dcntAMnt = getCodeAmnt($ln_DscntID, $entrdAmnt);
            $codeAmnt = getCodeAmnt($ln_TaxID, $entrdAmnt - $dcntAMnt);
            $lnSmmryLnID = getRcvblsLnDetID("2Tax", $ln_TaxID, $smmryID);
            $accnts = getRcvblBalncnAccnt("2Tax", $ln_TaxID, -1, -1, $docType);
            $funcCurrAmnt = $codeAmnt * $funcCurrRate;
            $accntCurrAmnt = $codeAmnt * $accntCurrRate;
            $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $entrdAmnt . ")";
            if ($lnSmmryLnID <= 0) {
                $lnSmmryLnID = getNewRcvblsLnID();
                $res += createRcvblsDocDet1(
                    $lnSmmryLnID,
                    $hdrID,
                    "2Tax",
                    $txlineDesc,
                    $codeAmnt,
                    $entrdCurrID,
                    $ln_TaxID,
                    $docType,
                    true,
                    $accnts[2],
                    $accnts[3],
                    $accnts[0],
                    $blncgAccntID,
                    -1,
                    $vldyStatus,
                    -1,
                    $funcCurrID,
                    $accntCurrID,
                    $funcCurrRate,
                    $accntCurrRate,
                    $funcCurrAmnt,
                    $accntCurrAmnt,
                    $smmryID,
                    1,
                    $codeAmnt,
                    $refDocNo,
                    ",",
                    -1,
                    -1,
                    -1
                );
            } else {
                $res += updtRcvblsDocDet1(
                    $lnSmmryLnID,
                    $hdrID,
                    "2Tax",
                    $txlineDesc,
                    $codeAmnt,
                    $entrdCurrID,
                    $ln_TaxID,
                    $docType,
                    true,
                    $accnts[2],
                    $accnts[3],
                    $accnts[0],
                    $blncgAccntID,
                    -1,
                    $vldyStatus,
                    -1,
                    $funcCurrID,
                    $accntCurrID,
                    $funcCurrRate,
                    $accntCurrRate,
                    $funcCurrAmnt,
                    $accntCurrAmnt,
                    $smmryID,
                    1,
                    $codeAmnt,
                    $refDocNo,
                    ",",
                    -1,
                    -1,
                    -1
                );
            }
        }
    } else if ($ln_TaxID > 0) {
        $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_TaxID);
        $dcntAMnt = getCodeAmnt($ln_DscntID, $entrdAmnt);
        $codeAmnt = getCodeAmnt($ln_TaxID, $entrdAmnt - $dcntAMnt);
        $lnSmmryLnID = getRcvblsLnDetID("2Tax", $ln_TaxID, $smmryID);
        $accnts = getRcvblBalncnAccnt("2Tax", $ln_TaxID, -1, -1, $docType);
        $funcCurrAmnt = $codeAmnt * $funcCurrRate;
        $accntCurrAmnt = $codeAmnt * $accntCurrRate;
        $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $entrdAmnt . ")";
        if ($lnSmmryLnID <= 0) {
            $lnSmmryLnID = getNewRcvblsLnID();
            $res += createRcvblsDocDet1(
                $lnSmmryLnID,
                $hdrID,
                "2Tax",
                $txlineDesc,
                $codeAmnt,
                $entrdCurrID,
                $ln_TaxID,
                $docType,
                true,
                $accnts[2],
                $accnts[3],
                $accnts[0],
                $blncgAccntID,
                -1,
                $vldyStatus,
                -1,
                $funcCurrID,
                $accntCurrID,
                $funcCurrRate,
                $accntCurrRate,
                $funcCurrAmnt,
                $accntCurrAmnt,
                $smmryID,
                1,
                $codeAmnt,
                $refDocNo,
                ",",
                -1,
                -1,
                -1
            );
        } else {
            $res += updtRcvblsDocDet1(
                $lnSmmryLnID,
                $hdrID,
                "2Tax",
                $txlineDesc,
                $codeAmnt,
                $entrdCurrID,
                $ln_TaxID,
                $docType,
                true,
                $accnts[2],
                $accnts[3],
                $accnts[0],
                $blncgAccntID,
                -1,
                $vldyStatus,
                -1,
                $funcCurrID,
                $accntCurrID,
                $funcCurrRate,
                $accntCurrRate,
                $funcCurrAmnt,
                $accntCurrAmnt,
                $smmryID,
                1,
                $codeAmnt,
                $refDocNo,
                ",",
                -1,
                -1,
                -1
            );
        }
    }

    if ($ln_WHTaxID > 0 && isTaxAParent($ln_WHTaxID) === true) {
        $codeIDs = explode(",", trim(getTaxChildIDs($ln_WHTaxID), ", "));
        for ($y = 0; $y < count($codeIDs); $y++) {
            $ln_WHTaxID = (int) $codeIDs[$y];
            $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_WHTaxID);
            $dcntAMnt = getCodeAmnt($ln_DscntID, $entrdAmnt);
            $codeAmnt = getCodeAmnt($ln_WHTaxID, $entrdAmnt - $dcntAMnt);
            $lnSmmryLnID = getRcvblsLnDetID("2Tax", $ln_WHTaxID, $smmryID);
            $accnts = getRcvblBalncnAccnt("2Tax", $ln_WHTaxID, -1, -1, $docType);
            $funcCurrAmnt = $codeAmnt * $funcCurrRate;
            $accntCurrAmnt = $codeAmnt * $accntCurrRate;
            $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $entrdAmnt . ")";
            if ($lnSmmryLnID <= 0) {
                $lnSmmryLnID = getNewRcvblsLnID();
                $res += createRcvblsDocDet1(
                    $lnSmmryLnID,
                    $hdrID,
                    "2Tax",
                    $txlineDesc,
                    $codeAmnt,
                    $entrdCurrID,
                    $ln_WHTaxID,
                    $docType,
                    true,
                    $accnts[2],
                    $accnts[3],
                    $accnts[0],
                    $blncgAccntID,
                    -1,
                    $vldyStatus,
                    -1,
                    $funcCurrID,
                    $accntCurrID,
                    $funcCurrRate,
                    $accntCurrRate,
                    $funcCurrAmnt,
                    $accntCurrAmnt,
                    $smmryID,
                    1,
                    $codeAmnt,
                    $refDocNo,
                    ",",
                    -1,
                    -1,
                    -1
                );
            } else {
                $res += updtRcvblsDocDet1(
                    $lnSmmryLnID,
                    $hdrID,
                    "2Tax",
                    $txlineDesc,
                    $codeAmnt,
                    $entrdCurrID,
                    $ln_WHTaxID,
                    $docType,
                    true,
                    $accnts[2],
                    $accnts[3],
                    $accnts[0],
                    $blncgAccntID,
                    -1,
                    $vldyStatus,
                    -1,
                    $funcCurrID,
                    $accntCurrID,
                    $funcCurrRate,
                    $accntCurrRate,
                    $funcCurrAmnt,
                    $accntCurrAmnt,
                    $smmryID,
                    1,
                    $codeAmnt,
                    $refDocNo,
                    ",",
                    -1,
                    -1,
                    -1
                );
            }
        }
    } else if ($ln_WHTaxID > 0) {
        $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_WHTaxID);
        $dcntAMnt = getCodeAmnt($ln_DscntID, $entrdAmnt);
        $codeAmnt = getCodeAmnt($ln_WHTaxID, $entrdAmnt - $dcntAMnt);
        $lnSmmryLnID = getRcvblsLnDetID("2Tax", $ln_WHTaxID, $smmryID);
        $accnts = getRcvblBalncnAccnt("2Tax", $ln_WHTaxID, -1, -1, $docType);
        $funcCurrAmnt = $codeAmnt * $funcCurrRate;
        $accntCurrAmnt = $codeAmnt * $accntCurrRate;
        $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $entrdAmnt . ")";
        if ($lnSmmryLnID <= 0) {
            $lnSmmryLnID = getNewRcvblsLnID();
            $res += createRcvblsDocDet1(
                $lnSmmryLnID,
                $hdrID,
                "2Tax",
                $txlineDesc,
                $codeAmnt,
                $entrdCurrID,
                $ln_WHTaxID,
                $docType,
                true,
                $accnts[2],
                $accnts[3],
                $accnts[0],
                $blncgAccntID,
                -1,
                $vldyStatus,
                -1,
                $funcCurrID,
                $accntCurrID,
                $funcCurrRate,
                $accntCurrRate,
                $funcCurrAmnt,
                $accntCurrAmnt,
                $smmryID,
                1,
                $codeAmnt,
                $refDocNo,
                ",",
                -1,
                -1,
                -1
            );
        } else {
            $res += updtRcvblsDocDet1(
                $lnSmmryLnID,
                $hdrID,
                "2Tax",
                $txlineDesc,
                $codeAmnt,
                $entrdCurrID,
                $ln_WHTaxID,
                $docType,
                true,
                $accnts[2],
                $accnts[3],
                $accnts[0],
                $blncgAccntID,
                -1,
                $vldyStatus,
                -1,
                $funcCurrID,
                $accntCurrID,
                $funcCurrRate,
                $accntCurrRate,
                $funcCurrAmnt,
                $accntCurrAmnt,
                $smmryID,
                1,
                $codeAmnt,
                $refDocNo,
                ",",
                -1,
                -1,
                -1
            );
        }
    }

    if ($ln_DscntID > 0 && isTaxAParent($ln_DscntID) === true) {
        $codeIDs = explode(",", trim(getTaxChildIDs($ln_DscntID), ", "));
        for ($y = 0; $y < count($codeIDs); $y++) {
            $ln_DscntID = (int) $codeIDs[$y];
            $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_DscntID);
            $codeAmnt = getCodeAmnt($ln_DscntID, $entrdAmnt);
            $lnSmmryLnID = getRcvblsLnDetID("3Discount", $ln_DscntID, $smmryID);
            $accnts = getRcvblBalncnAccnt("3Discount", $ln_DscntID, -1, -1, $docType);
            $funcCurrAmnt = $codeAmnt * $funcCurrRate;
            $accntCurrAmnt = $codeAmnt * $accntCurrRate;
            $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $entrdAmnt . ")";
            if ($lnSmmryLnID <= 0) {
                $lnSmmryLnID = getNewRcvblsLnID();
                $res += createRcvblsDocDet1(
                    $lnSmmryLnID,
                    $hdrID,
                    "3Discount",
                    $txlineDesc,
                    $codeAmnt,
                    $entrdCurrID,
                    $ln_DscntID,
                    $docType,
                    true,
                    $accnts[2],
                    $accnts[3],
                    $accnts[0],
                    $blncgAccntID,
                    -1,
                    $vldyStatus,
                    -1,
                    $funcCurrID,
                    $accntCurrID,
                    $funcCurrRate,
                    $accntCurrRate,
                    $funcCurrAmnt,
                    $accntCurrAmnt,
                    $smmryID,
                    1,
                    $codeAmnt,
                    $refDocNo,
                    ",",
                    -1,
                    -1,
                    -1
                );
            } else {
                $res += updtRcvblsDocDet1(
                    $lnSmmryLnID,
                    $hdrID,
                    "3Discount",
                    $txlineDesc,
                    $codeAmnt,
                    $entrdCurrID,
                    $ln_DscntID,
                    $docType,
                    true,
                    $accnts[2],
                    $accnts[3],
                    $accnts[0],
                    $blncgAccntID,
                    -1,
                    $vldyStatus,
                    -1,
                    $funcCurrID,
                    $accntCurrID,
                    $funcCurrRate,
                    $accntCurrRate,
                    $funcCurrAmnt,
                    $accntCurrAmnt,
                    $smmryID,
                    1,
                    $codeAmnt,
                    $refDocNo,
                    ",",
                    -1,
                    -1,
                    -1
                );
            }
        }
    } else if ($ln_DscntID > 0) {
        $txsmmryNm = getGnrlRecNm("scm.scm_tax_codes", "code_id", "code_name", $ln_DscntID);
        $codeAmnt = getCodeAmnt($ln_DscntID, $entrdAmnt);
        $lnSmmryLnID = getRcvblsLnDetID("3Discount", $ln_DscntID, $smmryID);
        $accnts = getRcvblBalncnAccnt("3Discount", $ln_DscntID, -1, -1, $docType);
        $funcCurrAmnt = $codeAmnt * $funcCurrRate;
        $accntCurrAmnt = $codeAmnt * $accntCurrRate;
        $txlineDesc = $txsmmryNm . " on " . $lineDesc . " (" . $entrdAmnt . ")";
        if ($lnSmmryLnID <= 0) {
            $lnSmmryLnID = getNewRcvblsLnID();
            $res += createRcvblsDocDet1(
                $lnSmmryLnID,
                $hdrID,
                "3Discount",
                $txlineDesc,
                $codeAmnt,
                $entrdCurrID,
                $ln_DscntID,
                $docType,
                true,
                $accnts[2],
                $accnts[3],
                $accnts[0],
                $blncgAccntID,
                -1,
                $vldyStatus,
                -1,
                $funcCurrID,
                $accntCurrID,
                $funcCurrRate,
                $accntCurrRate,
                $funcCurrAmnt,
                $accntCurrAmnt,
                $smmryID,
                1,
                $codeAmnt,
                $refDocNo,
                ",",
                -1,
                -1,
                -1
            );
        } else {
            $res += updtRcvblsDocDet1(
                $lnSmmryLnID,
                $hdrID,
                "3Discount",
                $txlineDesc,
                $codeAmnt,
                $entrdCurrID,
                $ln_DscntID,
                $docType,
                true,
                $accnts[2],
                $accnts[3],
                $accnts[0],
                $blncgAccntID,
                -1,
                $vldyStatus,
                -1,
                $funcCurrID,
                $accntCurrID,
                $funcCurrRate,
                $accntCurrRate,
                $funcCurrAmnt,
                $accntCurrAmnt,
                $smmryID,
                1,
                $codeAmnt,
                $refDocNo,
                ",",
                -1,
                -1,
                -1
            );
        }
    }
    return $res;
}

function updtRcvblsDocDet1(
    $docDetID,
    $hdrID,
    $lineType,
    $lineDesc,
    $entrdAmnt,
    $entrdCurrID,
    $codeBhnd,
    $docType,
    $autoCalc,
    $incrDcrs1,
    $costngID,
    $incrDcrs2,
    $blncgAccntID,
    $prepayDocHdrID,
    $vldyStatus,
    $orgnlLnID,
    $funcCurrID,
    $accntCurrID,
    $funcCurrRate,
    $accntCurrRate,
    $funcCurrAmnt,
    $accntCurrAmnt,
    $initAmntLineID,
    $lineQty,
    $unitPrice,
    $refDocNo,
    $slctdBrkdwns,
    $ln_TaxID,
    $ln_WHTaxID,
    $ln_DscntID
) {
    global $usrID;
    $insSQL = "UPDATE accb.accb_rcvbl_amnt_smmrys
   SET rcvbl_smmry_type='" . loc_db_escape_string($lineType) .
        "', rcvbl_smmry_desc='" . loc_db_escape_string($lineDesc) .
        "', rcvbl_smmry_amnt=" . $entrdAmnt .
        ", code_id_behind=" . $codeBhnd .
        ", src_rcvbl_type='" . loc_db_escape_string($docType) .
        "', src_rcvbl_hdr_id=" . $hdrID .
        ", last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), auto_calc='" . cnvrtBoolToBitStr($autoCalc) .
        "', incrs_dcrs1='" . loc_db_escape_string($incrDcrs1) .
        "', rvnu_acnt_id=" . $costngID .
        ", incrs_dcrs2='" . loc_db_escape_string($incrDcrs2) .
        "', rcvbl_acnt_id=" . $blncgAccntID .
        ", appld_prepymnt_doc_id=" . $prepayDocHdrID .
        ", validty_status='" . loc_db_escape_string($vldyStatus) .
        "', orgnl_line_id=" . $orgnlLnID .
        ", entrd_curr_id=" . $entrdCurrID .
        ", func_curr_id=" . $funcCurrID .
        ", accnt_curr_id=" . $accntCurrID .
        ", func_curr_rate=" . $funcCurrRate .
        ", accnt_curr_rate=" . $accntCurrRate .
        ", func_curr_amount=" . $funcCurrAmnt .
        ", accnt_curr_amnt=" . $accntCurrAmnt .
        ", initial_amnt_line_id=" . $initAmntLineID .
        ", line_qty=" . $lineQty .
        ", unit_price=" . $unitPrice .
        ", ref_doc_number='" . loc_db_escape_string($refDocNo) .
        "', slctd_amnt_brkdwns='" . loc_db_escape_string($slctdBrkdwns) .
        "', tax_code_id=" . $ln_TaxID .
        ", whtax_code_id=" . $ln_WHTaxID .
        ", dscnt_code_id=" . $ln_DscntID .
        " WHERE rcvbl_smmry_id = " . $docDetID;
    return execUpdtInsSQL($insSQL);
}

function deleteRcvblsDocHdrNDet($valLnid, $docNum)
{
    $trnsCnt1 = 0;
    $docStatus = getGnrlRecNm("accb.accb_rcvbls_invc_hdr", "rcvbls_invc_hdr_id", "approval_status", $valLnid);
    if ($docStatus == "Approved" || $docStatus == "Initiated" || $docStatus == "Validated" || $docStatus == "Cancelled" || strpos(
        $docStatus,
        "Reviewed"
    ) !== FALSE) {
        $trnsCnt1 = 1;
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot DELETE Approved, Initiated, Reviewed, Validated or Cancelled Documents!!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM accb.accb_rcvbl_amnt_smmrys WHERE src_rcvbl_hdr_id = " . $valLnid;
    $affctd1 = execUpdtInsSQL($delSQL, "Document Number = " . $docNum);
    $delSQL = "DELETE FROM accb.accb_rcvbls_invc_hdr WHERE rcvbls_invc_hdr_id = " . $valLnid;
    $affctd2 = execUpdtInsSQL($delSQL, "Document Number = " . $docNum);
    if ($affctd2 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Invoice Line(s)!";
        $dsply .= "<br/>Deleted $affctd2 Receivable Document(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteRcvblsDocDet($valLnid, $docNum)
{
    $trnsCnt1 = 0;
    $docHdrID = (float) getGnrlRecNm("accb.accb_rcvbl_amnt_smmrys", "rcvbl_smmry_id", "src_rcvbl_hdr_id", $valLnid);
    $docStatus = getGnrlRecNm("accb.accb_rcvbls_invc_hdr", "rcvbls_invc_hdr_id", "approval_status", $docHdrID);
    if ($docStatus == "Approved" || $docStatus == "Initiated" || $docStatus == "Validated" || $docStatus == "Cancelled" || strpos(
        $docStatus,
        "Reviewed"
    ) !== FALSE) {
        $trnsCnt1 = 1;
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot DELETE Approved, Initiated, Reviewed, Validated or Cancelled Documents!!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }

    $delSQL = "DELETE FROM accb.accb_rcvbl_amnt_smmrys WHERE rcvbl_smmry_id = " . $valLnid;
    $affctd1 = execUpdtInsSQL($delSQL, "Doc. No.:" . $docNum);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Invoice Line(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_RcvblsInvcDocDet($docHdrID)
{
    $whrcls = " and (a.rcvbl_smmry_type !='6Grand Total' and 
a.rcvbl_smmry_type !='7Total Payments Made' and a.rcvbl_smmry_type !='8Outstanding Balance')";
    $strSql = "SELECT rcvbl_smmry_id, rcvbl_smmry_type, rcvbl_smmry_desc, (CASE WHEN a.rcvbl_smmry_type='3Discount' 
or scm.istaxwthhldng(a.code_id_behind)='1' or a.rcvbl_smmry_type='5Applied Prepayment'
      THEN -1 ELSE 1 END ) * rcvbl_smmry_amnt, 
       code_id_behind, auto_calc, incrs_dcrs1, 
       rvnu_acnt_id, incrs_dcrs2, rcvbl_acnt_id, appld_prepymnt_doc_id, 
       entrd_curr_id, gst.get_pssbl_val(a.entrd_curr_id), 
       func_curr_id, gst.get_pssbl_val(a.func_curr_id), 
      accnt_curr_id, gst.get_pssbl_val(a.accnt_curr_id), 
      func_curr_rate, accnt_curr_rate, 
       func_curr_amount, accnt_curr_amnt, initial_amnt_line_id, 
       ref_doc_number,
        accb.get_accnt_num(a.rvnu_acnt_id) || '.' || accb.get_accnt_name(a.rvnu_acnt_id) charge_accnt,
        accb.get_accnt_num(a.rcvbl_acnt_id) || '.' || accb.get_accnt_name(a.rcvbl_acnt_id) balancing_accnt, 
        a.slctd_amnt_brkdwns,
        accb.get_src_doc_num(appld_prepymnt_doc_id,'Customer Advance Payments') src_doc_num, 
        REPLACE(REPLACE(a.rcvbl_smmry_type,'2Tax','3Tax'),'3Discount','2Discount') smtyp,
        line_qty, unit_price,
        scm.istaxwthhldng(code_id_behind), tax_code_id, whtax_code_id, dscnt_code_id, 
        scm.get_tax_code(tax_code_id), scm.get_tax_code(whtax_code_id), scm.get_tax_code(dscnt_code_id)  
  FROM accb.accb_rcvbl_amnt_smmrys a " .
        "WHERE((a.src_rcvbl_hdr_id = " . $docHdrID . ")" . $whrcls . ") ORDER BY a.rcvbl_smmry_type ASC, a.rcvbl_smmry_id ";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function isTaxWthHldng($codeID)
{
    $strSql = "Select scm.istaxwthhldng(" . $codeID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        if ($row[0] == "1") {
            return true;
        }
    }
    return false;
}

function isTaxAParent($codeID)
{
    $strSql = "Select scm.istaxaparent(" . $codeID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        if ($row[0] == "1") {
            return true;
        }
    }
    return false;
}

function getTaxChildIDs($codeID)
{
    $strSql = "Select scm.gettaxchldids(" . $codeID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return ",";
}

function getRcvblsDocGrndAmnt($dochdrID)
{
    $strSql = "select SUM(CASE WHEN y.rcvbl_smmry_type = '3Discount' 
or scm.istaxwthhldng(y.code_id_behind)='1' or y.rcvbl_smmry_type='5Applied Prepayment'
      THEN -1*y.rcvbl_smmry_amnt ELSE y.rcvbl_smmry_amnt END) amnt " .
        "from accb.accb_rcvbl_amnt_smmrys y " .
        "where y.src_rcvbl_hdr_id = " . $dochdrID .
        " and y.rcvbl_smmry_type IN ('1Initial Amount','2Tax','3Discount','4Extra Charge','5Applied Prepayment')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function getRcvblsPrepayDocCnt($dochdrID)
{
    $strSql = "select count(appld_prepymnt_doc_id) " .
        "from accb.accb_rcvbl_amnt_smmrys y " .
        "where y.src_rcvbl_hdr_id = " . $dochdrID . " and y.appld_prepymnt_doc_id >0 " .
        "Group by y.appld_prepymnt_doc_id having count(y.appld_prepymnt_doc_id)>1";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function getPyblsPrepayAvlblAmt($dochdrID)
{
    $strSql = "SELECT amnt_paid-invc_amnt_appld_elswhr 
  FROM accb.accb_pybls_invc_hdr a
  WHERE (a.pybls_invc_hdr_id = " . $dochdrID . " AND (amnt_paid-invc_amnt_appld_elswhr) > 0)";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function getRcvblsPrepayAvlblAmt($dochdrID)
{
    $strSql = "SELECT amnt_paid-invc_amnt_appld_elswhr 
  FROM accb.accb_rcvbls_invc_hdr a
  WHERE (a.rcvbls_invc_hdr_id = " . $dochdrID . " AND (amnt_paid-invc_amnt_appld_elswhr) > 0)";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function processInvcQuickPay(
    $p_orgnlPymntID,
    $p_NewPymntBatchID,
    $p_invoice_id,
    $p_msPyID,
    $p_createPrepay,
    $p_doc_types,
    $p_pay_mthd_id,
    $p_pay_remarks,
    $p_pay_date,
    $p_pay_amt_rcvd,
    $p_appld_prpay_docid,
    $p_cheque_card_name,
    $p_cheque_card_num,
    $p_cheque_card_code,
    $p_cheque_card_expdate,
    $p_who_rn,
    $p_run_date,
    $orgidno,
    $p_msgid,
    $p_inCstmrSpplrID,
    $p_invcCurD
) {
    $strSql = "select accb.invoice_payment(" . $p_orgnlPymntID . "," . $p_NewPymntBatchID .
        "," . $p_invoice_id . "," . $p_msPyID . "," . $p_createPrepay . ",'" . loc_db_escape_string($p_doc_types) .
        "',$p_pay_mthd_id,'" . loc_db_escape_string($p_pay_remarks) . "','" . loc_db_escape_string($p_pay_date) .
        "'," . $p_pay_amt_rcvd . "," . $p_appld_prpay_docid . ",'" . loc_db_escape_string($p_cheque_card_name) .
        "','" . loc_db_escape_string($p_cheque_card_num) . "','" . loc_db_escape_string($p_cheque_card_code) .
        "','" . loc_db_escape_string($p_cheque_card_expdate) . "'," . $p_who_rn . ",'" . $p_run_date . "'," . $orgidno . "," . $p_msgid . "," . $p_inCstmrSpplrID . "," . $p_invcCurD . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "ERROR:No Result";
}

function processInvcBatchPay($p_is_a_rvrsal, $p_PymntBatchID, $p_org_id, $p_who_rn, $p_msgid)
{
    $strSql = "select accb.batch_invoice_payments(" . $p_is_a_rvrsal . "," . $p_PymntBatchID . "," . $p_who_rn . ",to_char(now(),'YYYY-MM-DD HH24:MI:SS')," .
        $p_org_id . "," . $p_msgid . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "ERROR:No Result";
}

function cancelPyblsRcvblDoc($p_dochdrid, $p_dochdrtype, $p_dockind, $p_org_id, $p_who_rn)
{
    $strSql = "select accb.doccanclltnprocess(" . $p_dochdrid .
        ",'" . loc_db_escape_string($p_dochdrtype) .
        "','" . loc_db_escape_string($p_dockind) .
        "'," . $p_org_id . "," . $p_who_rn . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "ERROR:No Result";
}

function apprvPyblsRcvblDoc($p_dochdrid, $p_docnum, $p_dockind, $p_org_id, $p_who_rn)
{
    $strSql = "select accb.approve_pyblrcvbldoc(" . $p_dochdrid .
        ",'" . loc_db_escape_string($p_docnum) .
        "','" . loc_db_escape_string($p_dockind) .
        "'," . $p_org_id . "," . $p_who_rn . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "ERROR:No Result";
}

function isRcvblPrepayDocValid($dochdrID, $crncyID, $cstmrID)
{
    $strSql = "select rcvbls_invc_hdr_id " .
        "from accb.accb_rcvbls_invc_hdr y " .
        "where y.rcvbls_invc_hdr_id = " . $dochdrID .
        " and y.customer_id =" . $cstmrID .
        " and y.invc_curr_id = " . $crncyID;
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    return false;
}

function getRcvblsDocFuncAmnt($dochdrID)
{
    $strSql = "select SUM(CASE WHEN y.rcvbl_smmry_type='3Discount' 
or scm.istaxwthhldng(y.code_id_behind)='1' or y.rcvbl_smmry_type='5Applied Prepayment'
      THEN -1*y.func_curr_amount ELSE y.func_curr_amount END) amnt " .
        "from accb.accb_rcvbl_amnt_smmrys y " .
        "where y.src_rcvbl_hdr_id=" . $dochdrID .
        " and y.rcvbl_smmry_type IN ('1Initial Amount','2Tax','3Discount','4Extra Charge','5Applied Prepayment')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function getRcvblsDocAccntAmnt($dochdrID)
{
    $strSql = "select SUM(CASE WHEN y.rcvbl_smmry_type='3Discount' 
or scm.istaxwthhldng(y.code_id_behind)='1' or y.rcvbl_smmry_type='5Applied Prepayment'
      THEN -1*y.accnt_curr_amnt ELSE y.accnt_curr_amnt END) amnt " .
        "from accb.accb_rcvbl_amnt_smmrys y " .
        "where y.src_rcvbl_hdr_id=" . $dochdrID .
        " and y.rcvbl_smmry_type IN ('1Initial Amount','2Tax','3Discount','4Extra Charge','5Applied Prepayment')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function getRcvblsSmmryItmID($smmryType, $codeBhnd, $srcDocID, $srcDocTyp, $smmryNm)
{
    $strSql = "select y.rcvbl_smmry_id " .
        "from accb.accb_rcvbl_amnt_smmrys y " .
        "where y.rcvbl_smmry_type= '" . $smmryType . "' and y.rcvbl_smmry_desc = '" . $smmryNm .
        "' and y.code_id_behind= " . $codeBhnd .
        " and y.src_rcvbl_type='" . loc_db_escape_string($srcDocTyp) .
        "' and y.src_rcvbl_hdr_id=" . $srcDocID . " ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function updtRcvblsDocApprvl($docid, $apprvlSts, $nxtApprvl)
{
    global $usrID;
    $extrCls = "";

    if ($apprvlSts == "Cancelled") {
        $extrCls = ", invoice_amount=0, invc_amnt_appld_elswhr=0";
    }
    $updtSQL = "UPDATE accb.accb_rcvbls_invc_hdr SET " .
        "approval_status='" . loc_db_escape_string($apprvlSts) .
        "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS')" .
        ", next_aproval_action='" . loc_db_escape_string($nxtApprvl) .
        "'" . $extrCls . " WHERE (rcvbls_invc_hdr_id = " . $docid . ")";
    return execUpdtInsSQL($updtSQL);
}

function updtRcvblsDocGLBatch($docid, $glBatchID)
{
    global $usrID;
    $updtSQL = "UPDATE accb.accb_rcvbls_invc_hdr SET " .
        "gl_batch_id=" . $glBatchID .
        ", last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (rcvbls_invc_hdr_id = " . $docid . ")";
    return execUpdtInsSQL($updtSQL);
}

function updtRcvblsDocAmntPaid($docid, $amntPaid)
{
    global $usrID;
    $updtSQL = "UPDATE accb.accb_rcvbls_invc_hdr SET " .
        "amnt_paid=amnt_paid + " . $amntPaid .
        ", last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (rcvbls_invc_hdr_id = " .
        $docid . ")";
    return execUpdtInsSQL($updtSQL);
}

function updtRcvblsHdrAmntPaid($docid, $amntPaid)
{
    global $usrID;
    $updtSQL = "UPDATE accb.accb_rcvbls_invc_hdr SET " .
        "amnt_paid= + " . $amntPaid .
        ", last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (rcvbls_invc_hdr_id = " .
        $docid . ")";
    return execUpdtInsSQL($updtSQL);
}

function updtRcvblsDocAmnt($docid, $invAmnt)
{
    global $usrID;
    $extrCls = ", invoice_amount=" . $invAmnt . "";
    $updtSQL = "UPDATE accb.accb_rcvbls_invc_hdr SET " .
        "last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS')" . $extrCls . " WHERE (rcvbls_invc_hdr_id = " .
        $docid . ")";
    return execUpdtInsSQL($updtSQL);
}

function updtRcvblsDocAmntAppld($docid, $amntAppld)
{
    global $usrID;
    $updtSQL = "UPDATE accb.accb_rcvbls_invc_hdr SET " .
        "invc_amnt_appld_elswhr=invc_amnt_appld_elswhr + " . $amntAppld .
        ", last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (rcvbls_invc_hdr_id = " . $docid . ")";
    return execUpdtInsSQL($updtSQL);
}

function getRcvblsDocTtlPymnts($dochdrID, $docType)
{
    $strSql = "select SUM(y.amount_paid) amnt " .
        "from accb.accb_payments y " .
        "where y.src_doc_id = " . $dochdrID . " and y.src_doc_typ = '" . loc_db_escape_string($docType) . "'";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_PymntBatch($searchWord, $searchIn, $offset, $limit_size, $orgID, $startDte, $endDte, $qUnposted)
{
    if ($startDte != "") {
        $startDte = cnvrtDMYTmToYMDTm($startDte);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYTmToYMDTm($endDte);
    }
    $strSql = "";
    $whrcls = "";
    $dteCls = " and (to_timestamp(a.pymnt_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $startDte . "','YYYY-MM-DD HH24:MI:SS') 
and to_timestamp('" . $endDte . "','YYYY-MM-DD HH24:MI:SS'))";
    if ($qUnposted) {
        $dteCls .= " and COALESCE(accb.is_gl_batch_pstd(a.gl_batch_id), '0')='0'";
    }
    if ($searchIn == "Batch Name") {
        $whrcls = " and (a.pymnt_batch_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Batch Description") {
        $whrcls = " and (a.pymnt_batch_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Document Classification") {
        $whrcls = " and (a.doc_clsfctn ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Customer/Supplier Name") {
        $whrcls = " and (a.cust_spplr_id IN (select c.cust_sup_id from 
scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
            "'))";
    } else if ($searchIn == "Payment Method") {
        $whrcls = " and (accb.get_pymnt_mthd_name(a.pymnt_mthd_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Source Doc Number") {
        $whrcls = " and (a.pymnt_batch_id IN (select y.pymnt_batch_id from accb.accb_payments y where accb.get_src_doc_num(y.src_doc_id,y.src_doc_typ) ilike '" . loc_db_escape_string($searchWord) .
            "'))";
    } else if ($searchIn == "Document Type") {
        $whrcls = " and (a.doc_type ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Batch Source") {
        $whrcls = " and a.batch_source ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Batch Status") {
        $whrcls = " and a.batch_status ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT pymnt_batch_id, pymnt_batch_name, batch_source, pymnt_batch_desc, accb.get_pymnt_mthd_name(a.pymnt_mthd_id), 
        to_char(to_timestamp(pymnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), gst.get_pssbl_val(a.entrd_curr_id), 
        amount_being_paid, batch_status
        FROM accb.accb_payments_batches a 
        WHERE((a.org_id = " . $orgID . ")" . $whrcls . $dteCls .
        ") ORDER BY pymnt_batch_id DESC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_PymntBatch($searchWord, $searchIn, $orgID, $startDte, $endDte, $qUnposted)
{
    if ($startDte != "") {
        $startDte = cnvrtDMYTmToYMDTm($startDte);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYTmToYMDTm($endDte);
    }
    $strSql = "";
    $whrcls = "";
    $dteCls = " and (a.pymnt_batch_id IN (select f.pymnt_batch_id from accb.accb_payments f where 
to_timestamp(f.pymnt_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $startDte . "','YYYY-MM-DD HH24:MI:SS') 
and to_timestamp('" . $endDte . "','YYYY-MM-DD HH24:MI:SS')))";
    if ($qUnposted) {
        $dteCls .= " and COALESCE(accb.is_gl_batch_pstd(a.gl_batch_id), '0')='0'";
    }
    if ($searchIn == "Batch Name") {
        $whrcls = " and (a.pymnt_batch_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Batch Description") {
        $whrcls = " and (a.pymnt_batch_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Document Classification") {
        $whrcls = " and (a.doc_clsfctn ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Customer/Supplier Name") {
        $whrcls = " and (a.cust_spplr_id IN (select c.cust_sup_id from 
scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
            "'))";
    } else if ($searchIn == "Payment Method") {
        $whrcls = " and (accb.get_pymnt_mthd_name(a.pymnt_mthd_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Source Doc Number") {
        $whrcls = " and (a.pymnt_batch_id IN (select y.pymnt_batch_id from accb.accb_payments y where accb.get_src_doc_num(y.src_doc_id,y.src_doc_typ) ilike '" . loc_db_escape_string($searchWord) .
            "'))";
    } else if ($searchIn == "Document Type") {
        $whrcls = " and (a.doc_type ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Batch Source") {
        $whrcls = " and a.batch_source ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Batch Status") {
        $whrcls = " and a.batch_status ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT count(1) 
        FROM accb.accb_payments_batches a 
        WHERE((a.org_id = " . $orgID . ")" . $whrcls . $dteCls .
        ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_One_PymntBatchHdr($hdrID)
{
    $strSql = "SELECT pymnt_batch_id, pymnt_batch_name, pymnt_batch_desc, 
      pymnt_mthd_id, accb.get_pymnt_mthd_name(a.pymnt_mthd_id), 
       doc_type, doc_clsfctn, to_char(to_timestamp(docs_start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), 
        to_char(to_timestamp(docs_end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), batch_status, 
       batch_source, gl_batch_id, accb.get_gl_batch_name(a.gl_batch_id), cust_spplr_id, scm.get_cstmr_splr_name(cust_spplr_id),
       batch_vldty_status, orgnl_batch_id, to_char(to_timestamp(pymnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), 
    incrs_dcrs1, rcvbl_lblty_accnt_id, accb.get_accnt_num(rcvbl_lblty_accnt_id) || '.' || accb.get_accnt_name(rcvbl_lblty_accnt_id) lblty_acc, 
    incrs_dcrs2, cash_or_suspns_acnt_id, accb.get_accnt_num(cash_or_suspns_acnt_id) || '.' || accb.get_accnt_name(cash_or_suspns_acnt_id) cash_acc, 
    amount_given, amount_being_paid, change_or_balance, entrd_curr_id, gst.get_pssbl_val(a.entrd_curr_id), 
    func_curr_id, gst.get_pssbl_val(a.func_curr_id), func_curr_rate, func_curr_amount, 
    accnt_curr_id, gst.get_pssbl_val(a.accnt_curr_id), 
    accnt_curr_rate, accnt_curr_amnt, cheque_card_name, cheque_card_num, sign_code, accb.is_gl_batch_pstd(a.gl_batch_id)
      FROM accb.accb_payments_batches a WHERE((a.pymnt_batch_id = " . $hdrID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PymntBatchLns($offset, $limit_size, $docHdrID, $isRmvd)
{
    $strSql = "SELECT pymnt_id, pymnt_mthd_id, amount_paid, change_or_balance, pymnt_remark, 
       src_doc_typ, src_doc_id, accb.get_src_doc_num(a.src_doc_id, a.src_doc_typ), 
       to_char(to_timestamp(pymnt_date, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS'), 
       incrs_dcrs1, rcvbl_lblty_accnt_id, 
       incrs_dcrs2, cash_or_suspns_acnt_id, 
       gl_batch_id, accb.get_gl_batch_name(gl_batch_id), 
       orgnl_pymnt_id, pymnt_vldty_status, 
       entrd_curr_id, gst.get_pssbl_val(a.entrd_curr_id), 
       func_curr_id, gst.get_pssbl_val(a.func_curr_id), 
       accnt_curr_id, gst.get_pssbl_val(a.accnt_curr_id), 
       func_curr_rate, accnt_curr_rate, func_curr_amount, accnt_curr_amnt, 
       pymnt_batch_id, a.is_removed, a.amount_given, accb.get_src_doc_num(a.prepay_doc_id, a.prepay_doc_type)
       FROM accb.accb_payments a " .
        "WHERE((a.pymnt_batch_id = " . $docHdrID . ") and a.is_removed='" . loc_db_escape_string($isRmvd) . "') ORDER BY pymnt_id ASC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_PymntLnDets($payLineID)
{
    $strSql = "SELECT a.pymnt_id, a.pymnt_mthd_id, accb.get_pymnt_mthd_name(a.pymnt_mthd_id), 
        a.amount_paid, a.change_or_balance, a.pymnt_remark, 
       a.src_doc_typ, a.src_doc_id, accb.get_src_doc_num(a.src_doc_id, a.src_doc_typ), 
       to_char(to_timestamp(a.pymnt_date, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS'), 
       a.incrs_dcrs1, a.rcvbl_lblty_accnt_id,
        accb.get_accnt_num(a.rcvbl_lblty_accnt_id) || '.' || accb.get_accnt_name(a.rcvbl_lblty_accnt_id) rcvbl_lblty_accnt, 
       a.incrs_dcrs2, a.cash_or_suspns_acnt_id,
        accb.get_accnt_num(a.cash_or_suspns_acnt_id) || '.' || accb.get_accnt_name(a.cash_or_suspns_acnt_id) cash_or_suspns_acnt, 
       a.gl_batch_id, accb.get_gl_batch_name(a.gl_batch_id), 
       a.orgnl_pymnt_id, a.pymnt_vldty_status, 
       a.entrd_curr_id, gst.get_pssbl_val(a.entrd_curr_id), 
       a.func_curr_id, gst.get_pssbl_val(a.func_curr_id), 
       a.accnt_curr_id, gst.get_pssbl_val(a.accnt_curr_id), 
       a.func_curr_rate, a.accnt_curr_rate, a.func_curr_amount, a.accnt_curr_amnt, 
       a.pymnt_batch_id, a.is_removed, a.amount_given, a.prepay_doc_id, 
       accb.get_src_doc_num(a.prepay_doc_id, a.prepay_doc_type),
    a.pay_means_other_info, a.cheque_card_name, a.expiry_date, a.cheque_card_num, 
    a.sign_code, a.bkgrd_actvty_status, a.bkgrd_actvty_gen_doc_name, b.cust_spplr_id
       FROM accb.accb_payments a, accb.accb_payments_batches b " .
        "WHERE((a.pymnt_batch_id=b.pymnt_batch_id) and (a.pymnt_id = " . $payLineID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PayHstry_Trns($docHdrID, $docTypes)
{
    $strSql = "";
    $whereCls = "";
    if ($docTypes == "Customer Payments") {
        $whereCls = " and (a.src_doc_typ ilike '%Customer%' and a.src_doc_id=" . $docHdrID . ")";
    } else {
        $whereCls = " and (a.src_doc_typ ilike '%Supplier%' and a.src_doc_id=" . $docHdrID . ")";
    }
    $strSql = "SELECT a.pymnt_id, a.pymnt_mthd_id, accb.get_pymnt_mthd_name(a.pymnt_mthd_id), 
      a.amount_paid, a.change_or_balance, a.pymnt_remark, 
      a.src_doc_typ, a.src_doc_id, accb.get_src_doc_num(a.src_doc_id, a.src_doc_typ), 
      a.created_by, to_char(to_timestamp(a.pymnt_date, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS'), 
      sec.get_usr_name(a.created_by), a.gl_batch_id, accb.get_gl_batch_name(a.gl_batch_id), 
    b.pymnt_batch_name, a.pymnt_batch_id, a.prepay_doc_id, 
    accb.get_src_doc_num(a.prepay_doc_id, a.prepay_doc_type),
    a.pay_means_other_info, a.cheque_card_name, a.expiry_date, a.cheque_card_num, 
    a.sign_code, a.bkgrd_actvty_status, a.bkgrd_actvty_gen_doc_name, a.amount_given,
    a.entrd_curr_id, gst.get_pssbl_val(a.entrd_curr_id), b.batch_source " .
        "FROM accb.accb_payments a, accb.accb_payments_batches b " .
        "WHERE((a.pymnt_batch_id = b.pymnt_batch_id)" . $whereCls . ") ORDER BY a.pymnt_id DESC";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getNewPymntBatchID()
{
    $strSql = "select  last_value from accb.accb_payments_batches_pymnt_batch_id_seq";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (((float) $row[0]) + 1);
    }
    return -1;
}

function getNewPymntLnID()
{
    $strSql = "select nextval('accb.accb_payments_pymnt_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function createPymntsBatch(
    $orgid,
    $strtDte,
    $endDte,
    $docType,
    $batchName,
    $batchDesc,
    $spplrID,
    $pymntMthdID,
    $batchSource,
    $orgnlBtchID,
    $vldtyStatus,
    $docTmpltClsftn,
    $batchStatus,
    $pymntDate,
    $incrdcrs1,
    $rcvblPyblAcntID,
    $incrsDcrs2,
    $cashSuspnsAcntID,
    $amntGvn,
    $amntPaid,
    $chngBals,
    $entrdCurID,
    $funcCurID,
    $funcCurRate,
    $funcCurAmnt,
    $acntCurID,
    $acntCurRate,
    $acntCurAmnt,
    $chequeCardName,
    $chequeCardNum,
    $sign_code
) {
    global $usrID;
    global $smplTokenWord1;
    if ($pymntDate != "") {
        $pymntDate = cnvrtDMYTmToYMDTm($pymntDate);
    }
    if ($strtDte != "") {
        $strtDte = cnvrtDMYTmToYMDTm($strtDte);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYTmToYMDTm($endDte);
    }
    $insSQL = "INSERT INTO accb.accb_payments_batches(
            pymnt_batch_name, pymnt_batch_desc, pymnt_mthd_id, 
            doc_type, doc_clsfctn, docs_start_date, docs_end_date, batch_status, 
            batch_source, created_by, creation_date, last_update_by, last_update_date, 
            batch_vldty_status, orgnl_batch_id, org_id, cust_spplr_id, pymnt_date, incrs_dcrs1, 
            rcvbl_lblty_accnt_id, incrs_dcrs2, cash_or_suspns_acnt_id, amount_given, amount_being_paid, 
            change_or_balance, entrd_curr_id, func_curr_id, func_curr_rate, func_curr_amount, accnt_curr_id, 
            accnt_curr_rate, accnt_curr_amnt, cheque_card_name, cheque_card_num, sign_code, gl_batch_id) " .
        "VALUES ('" . loc_db_escape_string($batchName) .
        "', '" . loc_db_escape_string($batchDesc) .
        "', " . $pymntMthdID .
        ", '" . loc_db_escape_string($docType) .
        "', '" . loc_db_escape_string($docTmpltClsftn) .
        "', '" . loc_db_escape_string($strtDte) .
        "', '" . loc_db_escape_string($endDte) .
        "', '" . loc_db_escape_string($batchStatus) .
        "', '" . loc_db_escape_string($batchSource) .
        "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($vldtyStatus) .
        "', " . $orgnlBtchID .
        ", " . $orgid . ", " . $spplrID .
        ", '" . loc_db_escape_string($pymntDate) .
        "', '" . loc_db_escape_string($incrdcrs1) .
        "', '" . $rcvblPyblAcntID .
        "', '" . loc_db_escape_string($incrsDcrs2) .
        "', '" . $cashSuspnsAcntID .
        "', '" . $amntGvn .
        "', '" . $amntPaid .
        "', '" . $chngBals .
        "', '" . $entrdCurID .
        "', '" . $funcCurID .
        "', '" . $funcCurRate .
        "', '" . $funcCurAmnt .
        "', '" . $acntCurID .
        "', '" . $acntCurRate .
        "', '" . $acntCurAmnt .
        "', '" . loc_db_escape_string($chequeCardName) .
        "', '" . loc_db_escape_string($chequeCardNum) .
        "', '" . loc_db_escape_string(encrypt1($sign_code, $smplTokenWord1)) .
        "',-1)";
    return execUpdtInsSQL($insSQL);
}

function updtPymntsBatchVldty($batchID, $vldtyStatus)
{
    global $usrID;
    $updtSQL = "UPDATE accb.accb_payments_batches SET 
            last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), batch_vldty_status='" . loc_db_escape_string($vldtyStatus) .
        "' WHERE pymnt_batch_id = " . $batchID;
    return execUpdtInsSQL($updtSQL);
}

function updtPymntsLnVldty($pymtLnID, $vldtyStatus)
{
    global $usrID;
    $updtSQL = "UPDATE accb.accb_payments SET 
            last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), pymnt_vldty_status='" . loc_db_escape_string($vldtyStatus) .
        "' WHERE pymnt_id = " . $pymtLnID;
    return execUpdtInsSQL($updtSQL);
}

function updtPymntsBatch(
    $batchID,
    $strtDte,
    $endDte,
    $docType,
    $batchName,
    $batchDesc,
    $spplrID,
    $pymntMthdID,
    $batchSource,
    $orgnlBtchID,
    $vldtyStatus,
    $docTmpltClsftn,
    $batchStatus,
    $pymntDate,
    $incrdcrs1,
    $rcvblPyblAcntID,
    $incrsDcrs2,
    $cashSuspnsAcntID,
    $amntGvn,
    $amntPaid,
    $chngBals,
    $entrdCurID,
    $funcCurID,
    $funcCurRate,
    $funcCurAmnt,
    $acntCurID,
    $acntCurRate,
    $acntCurAmnt,
    $chequeCardName,
    $chequeCardNum,
    $sign_code
) {
    global $usrID;
    global $smplTokenWord1;
    if ($pymntDate != "") {
        $pymntDate = cnvrtDMYTmToYMDTm($pymntDate);
    }
    if ($strtDte != "") {
        $strtDte = cnvrtDMYTmToYMDTm($strtDte);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYTmToYMDTm($endDte);
    }
    $insSQL = "UPDATE accb.accb_payments_batches SET 
            pymnt_batch_name='" . loc_db_escape_string($batchName) .
        "', pymnt_batch_desc='" . loc_db_escape_string($batchDesc) .
        "', pymnt_mthd_id=" . $pymntMthdID .
        ", doc_type='" . loc_db_escape_string($docType) .
        "', doc_clsfctn='" . loc_db_escape_string($docTmpltClsftn) .
        "', docs_start_date='" . loc_db_escape_string($strtDte) .
        "', docs_end_date='" . loc_db_escape_string($endDte) .
        "', batch_status='" . loc_db_escape_string($batchStatus) .
        "', batch_source='" . loc_db_escape_string($batchSource) .
        "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS')" .
        ", batch_vldty_status='" . loc_db_escape_string($vldtyStatus) .
        "', orgnl_batch_id=" . $orgnlBtchID .
        ", cust_spplr_id=" . $spplrID .
        ", pymnt_date='" . loc_db_escape_string($pymntDate) .
        "', incrs_dcrs1='" . loc_db_escape_string($incrdcrs1) .
        "', rcvbl_lblty_accnt_id=" . $rcvblPyblAcntID .
        ", incrs_dcrs2='" . loc_db_escape_string($incrsDcrs2) .
        "', cash_or_suspns_acnt_id=" . $cashSuspnsAcntID .
        ", amount_given=" . $amntGvn .
        ", amount_being_paid=" . $amntPaid .
        ", change_or_balance=" . $chngBals .
        ", entrd_curr_id=" . $entrdCurID .
        ", func_curr_id=" . $funcCurID .
        ", func_curr_rate=" . $funcCurRate .
        ", func_curr_amount=" . $funcCurAmnt .
        ", accnt_curr_id=" . $acntCurID .
        ", accnt_curr_rate=" . $acntCurRate .
        ", accnt_curr_amnt=" . $acntCurAmnt .
        ", cheque_card_name='" . loc_db_escape_string($chequeCardName) .
        "', cheque_card_num='" . loc_db_escape_string($chequeCardNum) .
        "', sign_code='" . loc_db_escape_string(encrypt1($sign_code, $smplTokenWord1)) .
        "' WHERE pymnt_batch_id = " . $batchID;
    return execUpdtInsSQL($insSQL);
}

function createPymntDet(
    $pymntID,
    $pymntBatchID,
    $pymntMthdID,
    $amntPaid,
    $entrdCurrID,
    $chnge_bals,
    $pymntRemark,
    $srcDocType,
    $srcDocID,
    $pymntDte,
    $incrDcrs1,
    $blncgAccntID,
    $incrDcrs2,
    $chrgAccntID,
    $glBatchID,
    $vldyStatus,
    $orgnlLnID,
    $funcCurrID,
    $accntCurrID,
    $funcCurrRate,
    $accntCurrRate,
    $funcCurrAmnt,
    $accntCurrAmnt,
    $prepayDocID,
    $prepayDocType,
    $otherinfo,
    $cardNm,
    $expryDte,
    $cardNum,
    $sgnCode,
    $actvtyStatus,
    $actvtyDocName,
    $amntGvn
) {
    global $usrID;
    global $smplTokenWord1;
    if ($pymntDte != "") {
        $pymntDte = cnvrtDMYTmToYMDTm($pymntDte);
    }
    $insSQL = "INSERT INTO accb.accb_payments(
            pymnt_id, pymnt_mthd_id, amount_paid, change_or_balance, pymnt_remark, 
            src_doc_typ, src_doc_id, created_by, creation_date, last_update_by, 
            last_update_date, pymnt_date, incrs_dcrs1, rcvbl_lblty_accnt_id, 
            incrs_dcrs2, cash_or_suspns_acnt_id, gl_batch_id, orgnl_pymnt_id, 
            pymnt_vldty_status, entrd_curr_id, func_curr_id, accnt_curr_id, 
            func_curr_rate, accnt_curr_rate, func_curr_amount, accnt_curr_amnt, 
            pymnt_batch_id, prepay_doc_id, prepay_doc_type, pay_means_other_info, cheque_card_name, 
            expiry_date, cheque_card_num, sign_code, bkgrd_actvty_status, 
            bkgrd_actvty_gen_doc_name, intnl_pay_trns_id, is_cheque_printed, is_removed, amount_given) " .
        "VALUES (" . $pymntID . ", " . $pymntMthdID . "," . $amntPaid . "," . $chnge_bals .
        ",'" . loc_db_escape_string($pymntRemark) .
        "', '" . loc_db_escape_string($srcDocType) .
        "', " . $srcDocID .
        ", " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($pymntDte) .
        "', '" . loc_db_escape_string(substr($incrDcrs1, 0, 1)) .
        "', " . $blncgAccntID .
        ", '" . loc_db_escape_string(substr($incrDcrs2, 0, 1)) .
        "', " . $chrgAccntID .
        ", " . $glBatchID .
        ", " . $orgnlLnID .
        ", '" . loc_db_escape_string($vldyStatus) .
        "', " . $entrdCurrID .
        ", " . $funcCurrID .
        ", " . $accntCurrID .
        ", " . $funcCurrRate .
        ", " . $accntCurrRate .
        ", " . $funcCurrAmnt .
        ", " . $accntCurrAmnt .
        ", " . $pymntBatchID .
        ", " . $prepayDocID .
        ", '" . loc_db_escape_string($prepayDocType) .
        "', '" . loc_db_escape_string($otherinfo) .
        "', '" . loc_db_escape_string($cardNm) .
        "', '" . loc_db_escape_string($expryDte) .
        "', '" . loc_db_escape_string($cardNum) .
        "','" . loc_db_escape_string(encrypt1($sgnCode, $smplTokenWord1)) .
        "', '" . loc_db_escape_string($actvtyStatus) .
        "', '" . loc_db_escape_string($actvtyDocName) .
        "',-1,'0','0'," . $amntGvn . ")";
    return execUpdtInsSQL($insSQL);
}

function updtPymntDet(
    $pymntID,
    $pymntBatchID,
    $pymntMthdID,
    $amntPaid,
    $entrdCurrID,
    $chnge_bals,
    $pymntRemark,
    $srcDocType,
    $srcDocID,
    $pymntDte,
    $incrDcrs1,
    $blncgAccntID,
    $incrDcrs2,
    $chrgAccntID,
    $glBatchID,
    $vldyStatus,
    $orgnlLnID,
    $funcCurrID,
    $accntCurrID,
    $funcCurrRate,
    $accntCurrRate,
    $funcCurrAmnt,
    $accntCurrAmnt,
    $prepayDocID,
    $prepayDocType,
    $otherinfo,
    $cardNm,
    $expryDte,
    $cardNum,
    $sgnCode,
    $actvtyStatus,
    $actvtyDocName,
    $intrnlPayTrnsID,
    $isChqPrntd,
    $amntGvn
) {
    global $usrID;
    global $smplTokenWord1;
    if ($pymntDte != "") {
        $pymntDte = cnvrtDMYTmToYMDTm($pymntDte);
    }
    $insSQL = "UPDATE accb.accb_payments SET 
                 pymnt_mthd_id=" . $pymntMthdID .
        ", amount_paid=" . $amntPaid .
        ", change_or_balance=" . $chnge_bals .
        ", pymnt_remark='" . loc_db_escape_string($pymntRemark) .
        "', src_doc_typ='" . loc_db_escape_string($srcDocType) .
        "', src_doc_id=" . $srcDocID .
        ", last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), pymnt_date='" . loc_db_escape_string($pymntDte) .
        "', incrs_dcrs1='" . loc_db_escape_string(substr($incrDcrs1, 0, 1)) .
        "', rcvbl_lblty_accnt_id=" . $blncgAccntID .
        ", incrs_dcrs2='" . loc_db_escape_string(substr($incrDcrs2, 0, 1)) .
        "', cash_or_suspns_acnt_id=" . $chrgAccntID .
        ", gl_batch_id=" . $glBatchID .
        ", orgnl_pymnt_id=" . $orgnlLnID .
        ", pymnt_vldty_status='" . loc_db_escape_string($vldyStatus) .
        "', entrd_curr_id=" . $entrdCurrID .
        ", func_curr_id=" . $funcCurrID .
        ", accnt_curr_id=" . $accntCurrID .
        ", func_curr_rate=" . $funcCurrRate .
        ", accnt_curr_rate=" . $accntCurrRate .
        ", func_curr_amount=" . $funcCurrAmnt .
        ", accnt_curr_amnt=" . $accntCurrAmnt .
        ", pymnt_batch_id=" . $pymntBatchID .
        ", prepay_doc_id=" . $prepayDocID .
        ", prepay_doc_type=='" . loc_db_escape_string($prepayDocType) .
        "', pay_means_other_info='" . loc_db_escape_string($otherinfo) .
        "', cheque_card_name='" . loc_db_escape_string($cardNm) .
        "', expiry_date='" . loc_db_escape_string($expryDte) .
        "', cheque_card_num='" . loc_db_escape_string($cardNum) .
        "', sign_code='" . loc_db_escape_string(encrypt1($sgnCode, $smplTokenWord1)) .
        "', bkgrd_actvty_status='" . loc_db_escape_string($actvtyStatus) .
        "', bkgrd_actvty_gen_doc_name='" . loc_db_escape_string($actvtyDocName) .
        "', intnl_pay_trns_id=" . $intrnlPayTrnsID .
        ", is_cheque_printed='" . loc_db_escape_string($isChqPrntd) .
        "', amount_given=" . $amntGvn .
        " WHERE pymnt_id = " . $pymntID;
    return execUpdtInsSQL($insSQL);
}

function updtPymntDet1(
    $pymntID,
    $pymntBatchID,
    $pymntMthdID,
    $amntPaid,
    $entrdCurrID,
    $chnge_bals,
    $pymntRemark,
    $pymntDte,
    $incrDcrs1,
    $blncgAccntID,
    $incrDcrs2,
    $chrgAccntID,
    $glBatchID,
    $vldyStatus,
    $orgnlLnID,
    $funcCurrID,
    $accntCurrID,
    $funcCurrRate,
    $accntCurrRate,
    $funcCurrAmnt,
    $accntCurrAmnt,
    $prepayDocID,
    $prepayDocType,
    $otherinfo,
    $cardNm,
    $expryDte,
    $cardNum,
    $sgnCode,
    $actvtyStatus,
    $actvtyDocName,
    $intrnlPayTrnsID,
    $isRemoved,
    $amntGvn
) {
    global $usrID;
    global $smplTokenWord1;
    if ($pymntDte != "") {
        $pymntDte = cnvrtDMYTmToYMDTm($pymntDte);
    }
    $insSQL = "UPDATE accb.accb_payments SET 
                 pymnt_mthd_id=" . $pymntMthdID .
        ", amount_paid=" . $amntPaid .
        ", change_or_balance=" . $chnge_bals .
        ", pymnt_remark='" . loc_db_escape_string($pymntRemark) .
        "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), pymnt_date='" . loc_db_escape_string($pymntDte) .
        "', incrs_dcrs1='" . loc_db_escape_string(substr($incrDcrs1, 0, 1)) .
        "', rcvbl_lblty_accnt_id=" . $blncgAccntID .
        ", incrs_dcrs2='" . loc_db_escape_string(substr($incrDcrs2, 0, 1)) .
        "', cash_or_suspns_acnt_id=" . $chrgAccntID .
        ", gl_batch_id=" . $glBatchID .
        ", orgnl_pymnt_id=" . $orgnlLnID .
        ", pymnt_vldty_status='" . loc_db_escape_string($vldyStatus) .
        "', entrd_curr_id=" . $entrdCurrID .
        ", func_curr_id=" . $funcCurrID .
        ", accnt_curr_id=" . $accntCurrID .
        ", func_curr_rate=" . $funcCurrRate .
        ", accnt_curr_rate=" . $accntCurrRate .
        ", func_curr_amount=" . $funcCurrAmnt .
        ", accnt_curr_amnt=" . $accntCurrAmnt .
        ", pymnt_batch_id=" . $pymntBatchID .
        ", prepay_doc_id=" . $prepayDocID .
        ", prepay_doc_type='" . loc_db_escape_string($prepayDocType) .
        "', pay_means_other_info='" . loc_db_escape_string($otherinfo) .
        "', cheque_card_name='" . loc_db_escape_string($cardNm) .
        "', expiry_date='" . loc_db_escape_string($expryDte) .
        "', cheque_card_num='" . loc_db_escape_string($cardNum) .
        "', sign_code='" . loc_db_escape_string(encrypt1($sgnCode, $smplTokenWord1)) .
        "', bkgrd_actvty_status='" . loc_db_escape_string($actvtyStatus) .
        "', bkgrd_actvty_gen_doc_name='" . loc_db_escape_string($actvtyDocName) .
        "', intnl_pay_trns_id=" . $intrnlPayTrnsID .
        ", is_removed='" . loc_db_escape_string($isRemoved) .
        "', amount_given=" . $amntGvn .
        " WHERE pymnt_id = " . $pymntID;
    return execUpdtInsSQL($insSQL);
}

function deletePymntsBatchNDet($valLnid, $batchName)
{
    $strSql = "SELECT count(1) FROM accb.accb_payments a WHERE(a.pymnt_batch_id = " . $valLnid . " and a.gl_batch_id>0)";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete a Batch with Processed Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }

    $delSQL = "DELETE FROM accb.accb_payments WHERE pymnt_batch_id = " . $valLnid;
    $affctd2 = execUpdtInsSQL($delSQL, "Batch:" . $batchName);
    $delSQL = "DELETE FROM accb.accb_payments_batches WHERE pymnt_batch_id = " . $valLnid;
    $affctd1 = execUpdtInsSQL($delSQL, "Batch:" . $batchName);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Payment Batch!";
        $dsply .= "<br/>Deleted $affctd2 Payment Document(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deletePymntsDet($valLnid, $batchName)
{
    $strSql = "SELECT count(1) FROM accb.accb_payments a WHERE(a.pymnt_id = " . $valLnid . " and a.gl_batch_id>0)";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete a Processed Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }

    $delSQL = "DELETE FROM accb.accb_payments WHERE pymnt_id = " . $valLnid;
    $affctd2 = execUpdtInsSQL($delSQL, "Batch:" . $batchName);
    if ($affctd2 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd2 Payment Document(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function rmvRestorePymntsDet($valLnid, $rmvRstrStatus)
{
    $strSql = "SELECT count(1) FROM accb.accb_payments a WHERE(a.pymnt_id = " . $valLnid . " and a.gl_batch_id>0)";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Updated<br/>Cannot modify a Processed Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }

    $updtSQL = "UPDATE accb.accb_payments SET is_removed='" . loc_db_escape_string($rmvRstrStatus) . "' WHERE pymnt_id = " . $valLnid;
    $affctd2 = execUpdtInsSQL($updtSQL);
    if ($affctd2 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Updated $affctd2 Payment Document(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Updated";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_PaymentDocsPymtID($docHdrID, $docTypes, $payBatchID)
{
    $strSql = "";
    $whereCls = "";
    if ($docTypes == "Customer Payments") {
        $whereCls = " and (a.src_doc_typ ilike '%Customer%' and a.src_doc_id=" . $docHdrID . ") and a.pymnt_batch_id=" . $payBatchID;
    } else {
        $whereCls = " and (a.src_doc_typ ilike '%Supplier%' and a.src_doc_id=" . $docHdrID . ") and a.pymnt_batch_id=" . $payBatchID;
    }
    $strSql = "SELECT a.pymnt_id " .
        "FROM accb.accb_payments a, accb.accb_payments_batches b " .
        "WHERE((a.pymnt_batch_id = b.pymnt_batch_id)" . $whereCls . ") ORDER BY a.pymnt_id DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function get_QlfyngPaymentDocs($docTypes, $whereClause)
{
    $strSql = "";
    if ($docTypes == "Customer Payments") {
        $strSql = "SELECT rcvbls_invc_hdr_id, 
       rcvbls_invc_number, 
       rcvbls_invc_type, 
       comments_desc,
       invoice_amount, 
       amnt_paid
  FROM accb.accb_rcvbls_invc_hdr a WHERE approval_status='Approved' and (invoice_amount-amnt_paid)>0" . $whereClause;
    } else {
        $strSql = "SELECT pybls_invc_hdr_id, 
       pybls_invc_number, 
       pybls_invc_type, 
       comments_desc,
       invoice_amount, 
       amnt_paid
  FROM accb.accb_pybls_invc_hdr a WHERE approval_status='Approved' and (invoice_amount-amnt_paid)>0" . $whereClause;
    }
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function updtPymntBatchStatus($docid, $batchStatus)
{
    global $usrID;
    $updtSQL = "UPDATE accb.accb_payments_batches SET " .
        "batch_status='" . loc_db_escape_string($batchStatus) .
        "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (pymnt_batch_id = " .
        $docid . ")";
    return execUpdtInsSQL($updtSQL);
}

function updtPymntLnGLBatch($docid, $glBatchID)
{
    global $usrID;
    $updtSQL = "UPDATE accb.accb_payments SET " .
        "gl_batch_id=" . $glBatchID .
        ", last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (pymnt_id = " . $docid . ")";
    return execUpdtInsSQL($updtSQL);
}

function get_Basic_Cstmr($searchFor, $searchIn, $offset, $limit_size)
{
    global $orgID;
    $whereClause = "";
    $strSql = "";
    if ($searchIn == "Customer/Supplier Name") {
        $whereClause = " and (a.cust_sup_name ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Customer/Supplier Description") {
        $whereClause = " and (a.cust_sup_desc ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Customer/Supplier Type") {
        $whereClause = " and (a.cust_or_sup ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Linked Person") {
        $whereClause = " and ((prs.get_prsn_name(a.lnkd_prsn_id) || ' (' || prs.get_prsn_loc_id(a.lnkd_prsn_id) || ')') ilike '" . loc_db_escape_string($searchFor) .
            "')";
    }
    $strSql = "SELECT cust_sup_id, cust_sup_name, 
                cust_sup_desc, cust_sup_clssfctn, cust_or_sup, org_id, 
                dflt_pybl_accnt_id, dflt_rcvbl_accnt_id, lnkd_prsn_id, person_gender, 
                to_char(to_timestamp(dob_estblshmnt,'YYYY-MM-DD'),'DD-Mon-YYYY'), 
                is_enabled, firm_brand_name, type_of_organisation, 
                company_reg_num, date_of_incorptn, type_of_incorporation, vat_number, 
                tin_number, ssnit_reg_number, no_of_emplyees, description_of_services, 
                list_of_services, (select count(1) from scm.scm_cstmr_suplr_sites z where z.cust_supplier_id=a.cust_sup_id) no_of_sites,
                (prs.get_prsn_name(a.lnkd_prsn_id) || ' (' || prs.get_prsn_loc_id(a.lnkd_prsn_id) || ')') prsn_name,
                accb.get_accnt_num(dflt_pybl_accnt_id) || '.' || accb.get_accnt_name(dflt_pybl_accnt_id) lblty_acc, 
                accb.get_accnt_num(dflt_rcvbl_accnt_id) || '.' || accb.get_accnt_name(dflt_rcvbl_accnt_id) rcvbl_acc " .
        "FROM scm.scm_cstmr_suplr a " .
        "WHERE ((a.org_id = " . $orgID . ")$whereClause) ORDER BY a.cust_sup_id DESC LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Basic_CstmrTtl($searchFor, $searchIn)
{
    global $orgID;
    $whereClause = "";
    $strSql = "";
    if ($searchIn == "Customer/Supplier Name") {
        $whereClause = " and (a.cust_sup_name ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Customer/Supplier Description") {
        $whereClause = " and (a.cust_sup_desc ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Customer/Supplier Type") {
        $whereClause = " and (a.cust_or_sup ilike '" . loc_db_escape_string($searchFor) .
            "')";
    } else if ($searchIn == "Linked Person") {
        $whereClause = " and ((prs.get_prsn_name(a.lnkd_prsn_id) || ' (' || prs.get_prsn_loc_id(a.lnkd_prsn_id) || ')') ilike '" . loc_db_escape_string($searchFor) .
            "')";
    }
    $strSql = "SELECT count(1) " .
        "FROM scm.scm_cstmr_suplr a " .
        "WHERE ((a.org_id = " . $orgID . ")$whereClause)";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_OneCstmr($cstmr_id)
{
    $strSql = "SELECT cust_sup_id, cust_sup_name, 
                cust_sup_desc, cust_sup_clssfctn, cust_or_sup, 
                dflt_pybl_accnt_id, dflt_rcvbl_accnt_id, lnkd_prsn_id, person_gender, 
                to_char(to_timestamp(dob_estblshmnt,'YYYY-MM-DD'),'DD-Mon-YYYY'), 
                is_enabled, firm_brand_name, type_of_organisation, 
                company_reg_num, date_of_incorptn, type_of_incorporation, vat_number, 
                tin_number, ssnit_reg_number, no_of_emplyees, description_of_services, 
                list_of_services,
                (prs.get_prsn_name(a.lnkd_prsn_id) || ' (' || prs.get_prsn_loc_id(a.lnkd_prsn_id) || ')') prsn_name,
                accb.get_accnt_num(dflt_pybl_accnt_id) || '.' || accb.get_accnt_name(dflt_pybl_accnt_id) lblty_acc, 
                accb.get_accnt_num(dflt_rcvbl_accnt_id) || '.' || accb.get_accnt_name(dflt_rcvbl_accnt_id) rcvbl_acc,
                cstmr_image " .
        "FROM scm.scm_cstmr_suplr a WHERE a.cust_sup_id=" . $cstmr_id;
    return executeSQLNoParams($strSql);
}

function get_OneCstmrSites($cstmr_id)
{
    $strSql = "SELECT row_number() over(ORDER BY a.cust_sup_site_id) as row, "
        . "a.cust_sup_site_id, a.site_name, a.site_desc, " .
        "a.bank_name, a.bank_branch, a.bank_accnt_number, a.wth_tax_code_id, " .
        "a.discount_code_id, a.billing_address, a.ship_to_address, " .
        "a.contact_person_name, a.contact_nos, a.email, a.swift_code, 
       a.nationality, a.national_id_typ, a.id_number, a.date_issued, a.expiry_date, 
       a.other_info, is_enabled, a.iban_number, a.accnt_cur_id, gst.get_pssbl_val(a.accnt_cur_id) " .
        "FROM scm.scm_cstmr_suplr_sites a " .
        "WHERE(a.cust_supplier_id = " . $cstmr_id .
        ") ORDER BY a.cust_sup_site_id";
    return executeSQLNoParams($strSql);
}

function get_OneCstmrSitesDt($cstmrSiteID)
{
    $strSql = "SELECT a.cust_sup_site_id, a.site_name, a.site_desc, " .
        "a.bank_name, a.bank_branch, a.bank_accnt_number, a.wth_tax_code_id, " .
        "a.discount_code_id, a.billing_address, a.ship_to_address, " .
        "a.contact_person_name, a.contact_nos, a.email, a.swift_code, 
       a.nationality, a.national_id_typ, a.id_number, a.date_issued, a.expiry_date, 
       a.other_info, is_enabled, a.iban_number, a.accnt_cur_id, gst.get_pssbl_val(a.accnt_cur_id),
       scm.get_tax_code(a.wth_tax_code_id), scm.get_tax_code(a.discount_code_id)" .
        "FROM scm.scm_cstmr_suplr_sites a " .
        "WHERE(a.cust_sup_site_id = " . $cstmrSiteID . ") ORDER BY a.site_name";
    return executeSQLNoParams($strSql);
}

function getCstmrID_GLOBALS($cstmrNm, $orgid)
{
    $sqlStr = "select cust_sup_id from scm.scm_cstmr_suplr where lower(cust_sup_name) = '" .
        loc_db_escape_string(strtolower($cstmrNm)) . "' and org_id=" . $orgid;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getCstmrSpplrName($cstmrid)
{
    $sqlStr = "select cust_sup_name from scm.scm_cstmr_suplr where cust_sup_id=" . $cstmrid;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function createCstmr_GLOBALS(
    $cstmrNm,
    $cstmrDesc,
    $clsfctn,
    $cstrmOrSpplr,
    $orgid,
    $dfltPyblAcnt,
    $dfltRcvblAcnt,
    $lnkdPrsn,
    $gender,
    $dob,
    $isenbled,
    $frmBrndNm,
    $orgType,
    $cmpnyRegNum,
    $dateIncorp,
    $typeOfIncorp,
    $vatNum,
    $tinNum,
    $ssnitNum,
    $numEmplys,
    $descSrvcs,
    $listSrvcs
) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO scm.scm_cstmr_suplr(
            cust_sup_name, created_by, creation_date, last_update_by, last_update_date, 
            cust_sup_desc, cust_sup_clssfctn, cust_or_sup, org_id, 
            dflt_pybl_accnt_id, dflt_rcvbl_accnt_id, lnkd_prsn_id, person_gender, 
            dob_estblshmnt, is_enabled, firm_brand_name, type_of_organisation, 
            company_reg_num, date_of_incorptn, type_of_incorporation, vat_number, 
            tin_number, ssnit_reg_number, no_of_emplyees, description_of_services, 
            list_of_services) " .
        "VALUES ('" . loc_db_escape_string($cstmrNm) .
        "', " . loc_db_escape_string($usrID) .
        ", '" . loc_db_escape_string($dateStr) .
        "', " . loc_db_escape_string($usrID) .
        ", '" . loc_db_escape_string($dateStr) .
        "', '" . loc_db_escape_string($cstmrDesc) .
        "', '" . loc_db_escape_string($clsfctn) .
        "', '" . loc_db_escape_string($cstrmOrSpplr) .
        "'," . loc_db_escape_string($orgid) .
        "," . loc_db_escape_string($dfltPyblAcnt) .
        "," . loc_db_escape_string($dfltRcvblAcnt) .
        "," . loc_db_escape_string($lnkdPrsn) .
        ", '" . loc_db_escape_string($gender) .
        "', '" . loc_db_escape_string($dob) .
        "', '" . loc_db_escape_string($isenbled) .
        "', '" . loc_db_escape_string($frmBrndNm) .
        "', '" . loc_db_escape_string($orgType) .
        "', '" . loc_db_escape_string($cmpnyRegNum) .
        "', '" . loc_db_escape_string($dateIncorp) .
        "', '" . loc_db_escape_string($typeOfIncorp) .
        "', '" . loc_db_escape_string($vatNum) .
        "', '" . loc_db_escape_string($tinNum) .
        "', '" . loc_db_escape_string($ssnitNum) .
        "', " . loc_db_escape_string($numEmplys) .
        ", '" . loc_db_escape_string($descSrvcs) .
        "', '" . loc_db_escape_string($listSrvcs) .
        "')";
    return execUpdtInsSQL($insSQL);
}

function updateCstmr_GLOBALS(
    $cstmrid,
    $cstmrNm,
    $cstmrDesc,
    $clsfctn,
    $cstrmOrSpplr,
    $orgid,
    $dfltPyblAcnt,
    $dfltRcvblAcnt,
    $lnkdPrsn,
    $gender,
    $dob,
    $isenbled,
    $frmBrndNm,
    $orgType,
    $cmpnyRegNum,
    $dateIncorp,
    $typeOfIncorp,
    $vatNum,
    $tinNum,
    $ssnitNum,
    $numEmplys,
    $descSrvcs,
    $listSrvcs
) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE scm.scm_cstmr_suplr
   SET cust_sup_name='" . loc_db_escape_string($cstmrNm) .
        "', last_update_by=" . loc_db_escape_string($usrID) .
        ", last_update_date='" . loc_db_escape_string($dateStr) .
        "', cust_sup_desc='" . loc_db_escape_string($cstmrDesc) .
        "', cust_sup_clssfctn='" . loc_db_escape_string($clsfctn) .
        "', cust_or_sup='" . loc_db_escape_string($cstrmOrSpplr) .
        "', org_id=" . loc_db_escape_string($orgid) .
        ", dflt_pybl_accnt_id=" . loc_db_escape_string($dfltPyblAcnt) .
        ", dflt_rcvbl_accnt_id=" . loc_db_escape_string($dfltRcvblAcnt) .
        ", lnkd_prsn_id=" . loc_db_escape_string($lnkdPrsn) .
        ", person_gender='" . loc_db_escape_string($gender) .
        "', dob_estblshmnt='" . loc_db_escape_string($dob) .
        "', is_enabled='" . loc_db_escape_string($isenbled) .
        "', firm_brand_name='" . loc_db_escape_string($frmBrndNm) .
        "', type_of_organisation='" . loc_db_escape_string($orgType) .
        "', company_reg_num='" . loc_db_escape_string($cmpnyRegNum) .
        "', date_of_incorptn='" . loc_db_escape_string($dateIncorp) .
        "', type_of_incorporation='" . loc_db_escape_string($typeOfIncorp) .
        "', vat_number='" . loc_db_escape_string($vatNum) .
        "', tin_number='" . loc_db_escape_string($tinNum) .
        "', ssnit_reg_number='" . loc_db_escape_string($ssnitNum) .
        "', no_of_emplyees=" . loc_db_escape_string($numEmplys) .
        ", description_of_services='" . loc_db_escape_string($descSrvcs) .
        "', list_of_services='" . loc_db_escape_string($listSrvcs) .
        "' WHERE cust_sup_id = " . $cstmrid;
    return execUpdtInsSQL($insSQL);
}

function getCstmrSiteID($siteNm, $cstmrID)
{
    $sqlStr = "select cust_sup_site_id from scm.scm_cstmr_suplr_sites where cust_supplier_id = " . loc_db_escape_string($cstmrID) .
        " and lower(site_name) = '" . loc_db_escape_string(strtolower($siteNm)) . "'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createCstmrSite_GLOBALS(
    $cstmrID,
    $cntctPrsn,
    $cntctNos,
    $email,
    $siteNm,
    $siteDesc,
    $bankNm,
    $bankBrnch,
    $bnkNum,
    $wthTaxID,
    $dscntCodeID,
    $bllngAddrs,
    $shpToAddrs,
    $swftCode,
    $natnlty,
    $ntnltyIDType,
    $idNum,
    $dateIssued,
    $expryDate,
    $otherInfo,
    $isenabled,
    $ibanNum,
    $accntCurID
) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO scm.scm_cstmr_suplr_sites(
            cust_supplier_id, contact_person_name, contact_nos, email, created_by, 
            creation_date, last_update_by, last_update_date, site_name, site_desc, 
            bank_name, bank_branch, bank_accnt_number, wth_tax_code_id, discount_code_id, 
            billing_address, ship_to_address, swift_code, 
            nationality, national_id_typ, id_number, date_issued, expiry_date, 
            other_info, is_enabled, iban_number, accnt_cur_id) " .
        "VALUES (" . loc_db_escape_string($cstmrID) .
        ", '" . loc_db_escape_string($cntctPrsn) .
        "', '" . loc_db_escape_string($cntctNos) .
        "', '" . loc_db_escape_string($email) .
        "', " . loc_db_escape_string($usrID) .
        ", '" . loc_db_escape_string($dateStr) .
        "', " . loc_db_escape_string($usrID) .
        ", '" . loc_db_escape_string($dateStr) .
        "', '" . loc_db_escape_string($siteNm) .
        "', '" . loc_db_escape_string($siteDesc) .
        "', '" . loc_db_escape_string($bankNm) .
        "', '" . loc_db_escape_string($bankBrnch) .
        "', '" . loc_db_escape_string($bnkNum) .
        "', " . loc_db_escape_string($wthTaxID) .
        ", " . loc_db_escape_string($dscntCodeID) .
        ", '" . loc_db_escape_string($bllngAddrs) .
        "', '" . loc_db_escape_string($shpToAddrs) .
        "', '" . loc_db_escape_string($swftCode) .
        "', '" . loc_db_escape_string($natnlty) .
        "', '" . loc_db_escape_string($ntnltyIDType) .
        "', '" . loc_db_escape_string($idNum) .
        "', '" . loc_db_escape_string($dateIssued) .
        "', '" . loc_db_escape_string($expryDate) .
        "', '" . loc_db_escape_string($otherInfo) .
        "', '" . loc_db_escape_string($isenabled) .
        "', '" . loc_db_escape_string($ibanNum) .
        "', " . loc_db_escape_string($accntCurID) .
        ")";
    return execUpdtInsSQL($insSQL);
}

function updateCstmrSite_GLOBALS(
    $siteID,
    $cstmrID,
    $cntctPrsn,
    $cntctNos,
    $email,
    $siteNm,
    $siteDesc,
    $bankNm,
    $bankBrnch,
    $bnkNum,
    $wthTaxID,
    $dscntCodeID,
    $bllngAddrs,
    $shpToAddrs,
    $swftCode,
    $natnlty,
    $ntnltyIDType,
    $idNum,
    $dateIssued,
    $expryDate,
    $otherInfo,
    $isenabled,
    $ibanNum,
    $accntCurID
) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE scm.scm_cstmr_suplr_sites
   SET cust_supplier_id=" . loc_db_escape_string($cstmrID) .
        ", contact_person_name='" . loc_db_escape_string($cntctPrsn) .
        "', contact_nos='" . loc_db_escape_string($cntctNos) .
        "', email='" . loc_db_escape_string($email) .
        "', last_update_by=" . loc_db_escape_string($usrID) .
        ", last_update_date='" . loc_db_escape_string($dateStr) .
        "', site_name='" . loc_db_escape_string($siteNm) .
        "', site_desc='" . loc_db_escape_string($siteDesc) .
        "', bank_name='" . loc_db_escape_string($bankNm) .
        "', bank_branch='" . loc_db_escape_string($bankBrnch) .
        "', bank_accnt_number='" . loc_db_escape_string($bnkNum) .
        "', wth_tax_code_id=" . loc_db_escape_string($wthTaxID) .
        ", discount_code_id=" . loc_db_escape_string($dscntCodeID) .
        ", billing_address='" . loc_db_escape_string($bllngAddrs) .
        "', ship_to_address='" . loc_db_escape_string($shpToAddrs) .
        "', swift_code='" . loc_db_escape_string($swftCode) .
        "', nationality='" . loc_db_escape_string($natnlty) .
        "', national_id_typ='" . loc_db_escape_string($ntnltyIDType) .
        "', id_number='" . loc_db_escape_string($idNum) .
        "', date_issued='" . loc_db_escape_string($dateIssued) .
        "', expiry_date='" . loc_db_escape_string($expryDate) .
        "', other_info='" . loc_db_escape_string($otherInfo) .
        "', is_enabled='" . loc_db_escape_string($isenabled) .
        "', iban_number='" . loc_db_escape_string($ibanNum) .
        "', accnt_cur_id=" . loc_db_escape_string($accntCurID) .
        " WHERE cust_sup_site_id = " . $siteID;
    return execUpdtInsSQL($insSQL);
}

function deleteCstmr($pkeyID, $extrInfo = "")
{
    $selSQL = "Select count(1) from scm.scm_prchs_docs_hdr where supplier_id = " . $pkeyID;
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = (float) $row[0];
    }
    if ($trnsCnt > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Customers/Suppliers used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL1 = "Select count(1) from scm.scm_sales_invc_hdr where customer_id = " . $pkeyID;
    $result1 = executeSQLNoParams($selSQL1);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Customers/Suppliers used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL11 = "Select count(1) from accb.accb_rcvbls_invc_hdr where customer_id = " . $pkeyID;
    $result11 = executeSQLNoParams($selSQL11);
    $trnsCnt11 = 0;
    while ($row = loc_db_fetch_array($result11)) {
        $trnsCnt11 = (float) $row[0];
    }
    if ($trnsCnt11 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Customers/Suppliers used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL12 = "Select count(1) from accb.accb_pybls_invc_hdr where supplier_id = " . $pkeyID;
    $result12 = executeSQLNoParams($selSQL12);
    $trnsCnt12 = 0;
    while ($row = loc_db_fetch_array($result12)) {
        $trnsCnt12 = (float) $row[0];
    }
    if ($trnsCnt12 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Customers/Suppliers used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL13 = "Select count(1) from accb.accb_ptycsh_vchr_hdr where supplier_id = " . $pkeyID;
    $result13 = executeSQLNoParams($selSQL13);
    $trnsCnt13 = 0;
    while ($row = loc_db_fetch_array($result13)) {
        $trnsCnt13 = (float) $row[0];
    }
    if ($trnsCnt13 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Customers/Suppliers used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL3 = "Select count(1) from vms.vms_transactions_hdr "
        . "WHERE cstmr_spplr_id= " . $pkeyID . "";
    $result3 = executeSQLNoParams($selSQL3);
    $trnsCnt3 = 0;
    while ($row = loc_db_fetch_array($result3)) {
        $trnsCnt3 = (float) $row[0];
    }
    if ($trnsCnt3 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Customers/Suppliers used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $affctd1 = 0;
    $affctd2 = 0;
    $affctd3 = 0;
    $affctd4 = 0;
    if (($trnsCnt + $trnsCnt1 + $trnsCnt11 + $trnsCnt3 + $trnsCnt12 + $trnsCnt13) <= 0) {
        $insSQL = "DELETE FROM scm.scm_cstmr_suplr_sites WHERE cust_supplier_id = " . $pkeyID;
        $affctd1 = execUpdtInsSQL($insSQL, "Customer/Supplier Name:" . $extrInfo);
        $insSQL = "DELETE FROM scm.scm_cstmr_suplr WHERE cust_sup_id = " . $pkeyID;
        $affctd4 = execUpdtInsSQL($insSQL, "Customer/Supplier Name:" . $extrInfo);
    }
    if ($affctd4 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd4 Customer/Supplier(s)!";
        $dsply .= "<br/>$affctd1 Customer/Supplier Site(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteCstmrSite($pkeyID, $extrInfo = "")
{
    $selSQL11 = "Select count(1) from scm.scm_prchs_docs_hdr where supplier_site_id = " . $pkeyID;
    $result11 = executeSQLNoParams($selSQL11);
    $trnsCnt11 = 0;
    while ($row = loc_db_fetch_array($result11)) {
        $trnsCnt11 = (float) $row[0];
    }
    if ($trnsCnt11 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Sites used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL12 = "Select count(1) from scm.scm_sales_invc_hdr where customer_site_id = " . $pkeyID;
    $result12 = executeSQLNoParams($selSQL12);
    $trnsCnt12 = 0;
    while ($row = loc_db_fetch_array($result12)) {
        $trnsCnt12 = (float) $row[0];
    }
    if ($trnsCnt12 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Sites used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL13 = "Select count(1) from accb.accb_rcvbls_invc_hdr where customer_site_id = " . $pkeyID;
    $result13 = executeSQLNoParams($selSQL13);
    $trnsCnt13 = 0;
    while ($row = loc_db_fetch_array($result13)) {
        $trnsCnt13 = (float) $row[0];
    }
    if ($trnsCnt13 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Sites used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL14 = "Select count(1) from accb.accb_pybls_invc_hdr where supplier_site_id = " . $pkeyID;
    $result14 = executeSQLNoParams($selSQL14);
    $trnsCnt14 = 0;
    while ($row = loc_db_fetch_array($result14)) {
        $trnsCnt14 = (float) $row[0];
    }
    if ($trnsCnt14 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Sites used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL15 = "Select count(1) from accb.accb_ptycsh_vchr_hdr where supplier_site_id = " . $pkeyID;
    $result15 = executeSQLNoParams($selSQL15);
    $trnsCnt15 = 0;

    while ($row = loc_db_fetch_array($result15)) {
        $trnsCnt15 = (float) $row[0];
    }
    if ($trnsCnt15 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Sites used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $affctd1 = 0;
    if (($trnsCnt11 + $trnsCnt12 + $trnsCnt13 + $trnsCnt14 + $trnsCnt15) <= 0) {
        $insSQL = "DELETE FROM scm.scm_cstmr_suplr_sites WHERE cust_sup_site_id = " . $pkeyID;
        $affctd1 = execUpdtInsSQL($insSQL, "Site Name:" . $extrInfo);
    }
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Site(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function uploadDaImageCstmr($cstmrid, &$nwImgLoc)
{
    global $tmpDest;
    global $ftp_base_db_fldr;
    global $usrID;
    global $fldrPrfx;
    global $smplTokenWord1;

    $msg = "";
    $allowedExts = array("gif", "jpeg", "jpg", "png");
    if (isset($_FILES["daCstmrPicture"])) {
        //$files = multiple($_FILES);
        $flnm = $_FILES["daCstmrPicture"]["name"];
        $msg .= $flnm;
        $temp = explode(".", $flnm);
        $extension = end($temp);
        if ($_FILES["daCstmrPicture"]["error"] > 0) {
            $msg .= "Return Code: " . $_FILES["daCstmrPicture"]["error"] . "<br>";
        } else {
            $msg .= "Upload: " . $_FILES["daCstmrPicture"]["name"] . "<br>";
            $msg .= "Type: " . $_FILES["daCstmrPicture"]["type"] . "<br>";
            $msg .= "Size: " . ($_FILES["daCstmrPicture"]["size"]) . " bytes<br>";
            $msg .= "Temp file: " . $_FILES["daCstmrPicture"]["tmp_name"] . "<br>";
            if ((($_FILES["daCstmrPicture"]["type"] == "image/gif") || ($_FILES["daCstmrPicture"]["type"] == "image/jpeg") || ($_FILES["daCstmrPicture"]["type"] == "image/jpg") || ($_FILES["daCstmrPicture"]["type"] == "image/pjpeg") || ($_FILES["daCstmrPicture"]["type"] == "image/x-png") || ($_FILES["daCstmrPicture"]["type"] == "image/png") || in_array($extension, $allowedExts)) && ($_FILES["daCstmrPicture"]["size"] < 2000000)) {
                $nwFileName = encrypt1($cstmrid . "." . $extension, $smplTokenWord1) . "." . $extension;
                $img_src = $fldrPrfx . $tmpDest . "$nwFileName";
                move_uploaded_file($_FILES["daCstmrPicture"]["tmp_name"], $img_src);
                $ftp_src = $ftp_base_db_fldr . "/Cstmr/$cstmrid" . "." . $extension;
                if (file_exists($img_src)) {
                    copy("$img_src", "$ftp_src");

                    $dateStr = getDB_Date_time();
                    $updtSQL = "UPDATE scm.scm_cstmr_suplr " .
                        "SET last_update_by=" . $usrID . ", " .
                        "last_update_date='" . $dateStr .
                        "', cstmr_image = '" . $cstmrid . "." . $extension . "' WHERE cust_sup_id=" . $cstmrid;
                    execUpdtInsSQL($updtSQL);
                }
                $msg .= "Image Stored";
                $nwImgLoc = "$cstmrid" . "." . $extension;
                return TRUE;
            } else {
                $msg .= "<br/>Invalid file";
                $nwImgLoc = $msg;
            }
        }
    }
    $msg .= "<br/>Invalid file!<br/>File Size must be below 2MB and<br/>File Type must be in the ff:<br/>" . implode(", ", $allowedExts);
    $nwImgLoc = $msg;
    return FALSE;
}

function get_CstmrAttachments($searchWord, $offset, $limit_size, $hdrID, &$attchSQL)
{
    $strSql = "SELECT a.attchmnt_id, a.firms_id, a.attchmnt_desc, a.file_name " .
        "FROM accb.accb_firms_doc_attchmnts a " .
        "WHERE(a.attchmnt_desc ilike '" . loc_db_escape_string($searchWord) .
        "' and a.firms_id = " . $hdrID . ") ORDER BY a.attchmnt_id LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    $attchSQL = $strSql;
    return $result;
}

function get_Total_CstmrAttachments($searchWord, $hdrID)
{
    $strSql = "SELECT count(1) " .
        "FROM accb.accb_firms_doc_attchmnts a " .
        "WHERE(a.attchmnt_desc ilike '" . loc_db_escape_string($searchWord) .
        "' and a.firms_id = " . $hdrID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function getCstmrTrnsAttchmtDocs($hdrID)
{
    $sqlStr = "SELECT attchmnt_id, file_name, attchmnt_desc
  FROM accb.accb_firms_doc_attchmnts WHERE 1=1 AND file_name != '' AND firms_id = " . $hdrID;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function updateCstmrDocFlNm($attchmnt_id, $file_name)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE accb.accb_firms_doc_attchmnts SET file_name='"
        . loc_db_escape_string($file_name) .
        "', last_update_by=" . $usrID .
        ", last_update_date='" . $dateStr . "'
                WHERE attchmnt_id=" . $attchmnt_id;
    return execUpdtInsSQL($insSQL);
}

function getNewCstmrDocID()
{
    $strSql = "select nextval('accb.accb_firms_doc_attchmnts_attchmnt_id_seq')";
    $result = executeSQLNoParams($strSql);

    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function createCstmrDoc($attchmnt_id, $hdrid, $attchmnt_desc, $file_name)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO accb.accb_firms_doc_attchmnts(
            attchmnt_id, firms_id, attchmnt_desc, file_name, created_by, 
            creation_date, last_update_by, last_update_date)
             VALUES (" . $attchmnt_id . ", " . $hdrid . ",'"
        . loc_db_escape_string($attchmnt_desc) . "','"
        . loc_db_escape_string($file_name) . "',"
        . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";
    return execUpdtInsSQL($insSQL);
}

function deleteCstmrDoc($pkeyID, $docTrnsNum = "")
{
    $insSQL = "DELETE FROM accb.accb_firms_doc_attchmnts WHERE attchmnt_id = " . $pkeyID;
    $affctd1 = execUpdtInsSQL($insSQL, "Firm Name:" . $docTrnsNum);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Attached Document(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function uploadDaCstmrDoc($attchmntID, &$nwImgLoc, &$errMsg)
{
    global $tmpDest;
    global $ftp_base_db_fldr;
    global $usrID;
    global $fldrPrfx;
    global $smplTokenWord1;

    $msg = "";
    $allowedExts = array(
        'png', 'jpg', 'gif', 'jpeg', 'bmp', 'pdf', 'xls', 'xlsx',
        'doc', 'docx', 'ppt', 'pptx', 'txt', 'csv'
    );

    if (isset($_FILES["daCstmrAttchmnt"])) {
        $flnm = $_FILES["daCstmrAttchmnt"]["name"];
        $temp = explode(".", $flnm);
        $extension = end($temp);
        if ($_FILES["daCstmrAttchmnt"]["error"] > 0) {
            $msg .= "Return Code: " . $_FILES["daCstmrAttchmnt"]["error"] . "<br>";
        } else {
            $msg .= "Uploaded File: " . $_FILES["daCstmrAttchmnt"]["name"] . "<br>";
            $msg .= "Type: " . $_FILES["daCstmrAttchmnt"]["type"] . "<br>";
            $msg .= "Size: " . round(($_FILES["daCstmrAttchmnt"]["size"]) / (1024 * 1024), 2) . " MB<br>";
            //echo $_FILES["daCstmrAttchmnt"]["type"];
            if ((($_FILES["daCstmrAttchmnt"]["type"] == "image/gif") || ($_FILES["daCstmrAttchmnt"]["type"] == "image/jpeg") || ($_FILES["daCstmrAttchmnt"]["type"] == "image/jpg") || ($_FILES["daCstmrAttchmnt"]["type"] == "image/pjpeg") || ($_FILES["daCstmrAttchmnt"]["type"] == "image/x-png") || ($_FILES["daCstmrAttchmnt"]["type"] == "image/png") || in_array($extension, $allowedExts)) && ($_FILES["daCstmrAttchmnt"]["size"] < 10000000)) {
                $nwFileName = encrypt1($attchmntID . "." . $extension, $smplTokenWord1) . "." . $extension;
                $img_src = $fldrPrfx . $tmpDest . "$nwFileName";
                move_uploaded_file($_FILES["daCstmrAttchmnt"]["tmp_name"], $img_src);
                $ftp_src = $ftp_base_db_fldr . "/FirmsDocs/$attchmntID" . "." . $extension;
                if (file_exists($img_src)) {
                    copy("$img_src", "$ftp_src");
                    $dateStr = getDB_Date_time();
                    $updtSQL = "UPDATE accb.accb_firms_doc_attchmnts
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
                $msg .= "Invalid file!<br/>File Size must be below 10MB and<br/>File Type must be in the ff:<br/>" . implode(
                    ", ",
                    $allowedExts
                );
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

function createRate($rate_dte, $curFrom, $curFrmID, $curTo, $curToID, $scalefactor)
{
    global $usrID;
    $rate_dte = cnvrtDMYToYMD($rate_dte);
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO accb.accb_exchange_rates(
            conversion_date, currency_from, currency_from_id, currency_to, 
            currency_to_id, multiply_from_by, created_by, creation_date, 
            last_update_by, last_update_date) " .
        "VALUES ('" . loc_db_escape_string($rate_dte) .
        "', '" . loc_db_escape_string($curFrom) .
        "', " . $curFrmID .
        ", '" . $curTo .
        "', " . $curToID .
        ", " . $scalefactor .
        ", " . $usrID . ", '" . $dateStr .
        "', " . $usrID . ", '" . $dateStr .
        "')";
    return execUpdtInsSQL($insSQL);
}

function updtRate($rateID, $rate_dte, $curFrom, $curFrmID, $curTo, $curToID, $scalefactor)
{
    global $usrID;
    $rate_dte = cnvrtDMYToYMD($rate_dte);
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE accb.accb_exchange_rates SET 
            conversion_date='" . loc_db_escape_string($rate_dte) .
        "', currency_from='" . loc_db_escape_string($curFrom) .
        "', currency_from_id=" . $curFrmID .
        ", last_update_by=" . $usrID . ", last_update_date='" . $dateStr .
        "', currency_to='" . loc_db_escape_string($curTo) .
        "', currency_to_id=" . $curToID .
        ", multiply_from_by = " . $scalefactor .
        " WHERE rate_id = " . $rateID;
    return execUpdtInsSQL($insSQL);
}

function updtRateValue($rateID, $scalefactor)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE accb.accb_exchange_rates SET 
            last_update_by=" . $usrID .
        ", last_update_date='" . $dateStr .
        "', multiply_from_by=" . $scalefactor .
        " WHERE rate_id = " . $rateID;
    return execUpdtInsSQL($insSQL);
}

function deleteRate($valLnid, $rateDesc)
{
    $delSQL = "DELETE FROM accb.accb_exchange_rates WHERE rate_id = " . $valLnid;
    return execUpdtInsSQL($delSQL, $rateDesc);
}

function doesRateExst($rateDte, $fromCur, $toCur)
{
    $rateDte = cnvrtDMYToYMD($rateDte);
    $strSql = "SELECT rate_id 
            FROM accb.accb_exchange_rates WHERE currency_from='" . loc_db_escape_string($fromCur) .
        "' and currency_to='" . loc_db_escape_string($toCur) .
        "' and conversion_date='" . loc_db_escape_string($rateDte) .
        "'";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    return false;
}

function doesRateExst1($rateDte, $fromCur, $toCur)
{
    $rateDte = cnvrtDMYToYMD($rateDte);
    $strSql = "SELECT rate_id 
                        FROM accb.accb_exchange_rates WHERE currency_from='" . loc_db_escape_string($fromCur) .
        "' and currency_to='" . loc_db_escape_string($toCur) .
        "' and conversion_date='" . loc_db_escape_string($rateDte) .
        "'";
    $result = executeSQLNoParams($strSql);
    if ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function get_ExchgCurrencies($funcCurCode)
{
    $strSql = "SELECT pssbl_value_id, pssbl_value, pssbl_value_desc,
       is_enabled, allowed_org_ids
  FROM gst.gen_stp_lov_values WHERE pssbl_value != '" . loc_db_escape_string($funcCurCode) .
        "' and is_enabled='1' and value_list_id=" . getLovID("Currencies");
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Rates($searchWord, $searchIn, $dte1, $dte2, $offset, $limit_size)
{
    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Currency From") {
        $whrcls = " AND (gst.get_pssbl_val_desc(a.currency_from_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or a.currency_from ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Currency To") {
        $whrcls = " AND (gst.get_pssbl_val_desc(a.currency_to_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or a.currency_to ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Multiply By") {
        $whrcls = " AND (trim(to_char(a.multiply_from_by, '9999999999999999999999999D9999S')) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }

    $strSql = "SELECT rate_id, to_char(to_timestamp(conversion_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), 
        currency_from, currency_from_id, gst.get_pssbl_val_desc(a.currency_from_id), 
        currency_to, currency_to_id, gst.get_pssbl_val_desc(a.currency_to_id), 
        multiply_from_by, conversion_date 
        FROM accb.accb_exchange_rates a " .
        "WHERE((to_timestamp(conversion_date,'YYYY-MM-DD') >= to_timestamp('"
        . loc_db_escape_string($dte1) . "' ,'DD-Mon-YYYY HH24:MI:SS') AND to_timestamp(conversion_date,'YYYY-MM-DD') <=to_timestamp('" .
        loc_db_escape_string($dte2) . "' ,'DD-Mon-YYYY HH24:MI:SS'))" . $whrcls .
        ") ORDER BY conversion_date DESC, currency_from ASC LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_Rates($searchWord, $searchIn, $dte1, $dte2)
{
    $strSql = "";
    $whrcls = "";

    if ($searchIn == "Currency From") {
        $whrcls = " AND (gst.get_pssbl_val_desc(a.currency_from_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or a.currency_from ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Currency To") {
        $whrcls = " AND (gst.get_pssbl_val_desc(a.currency_to_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or a.currency_to ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Multiply By") {
        $whrcls = " AND (trim(to_char(a.multiply_from_by, '9999999999999999999999999D9999S')) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }

    $strSql = "SELECT count(1) FROM accb.accb_exchange_rates a " .
        "WHERE((to_timestamp(conversion_date,'YYYY-MM-DD HH24:MI:SS') >= to_timestamp('" .
        $dte1 . "' ,'DD-Mon-YYYY HH24:MI:SS') AND to_timestamp(conversion_date,'YYYY-MM-DD HH24:MI:SS') <=to_timestamp('" .
        $dte2 . "' ,'DD-Mon-YYYY HH24:MI:SS'))" . $whrcls . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function createPymntMthd($orgid, $mthdNm, $mthdDesc, $accntID, $docType, $bckgrndPrcss, $isenbld)
{
    global $usrID;
    $insSQL = "INSERT INTO accb.accb_paymnt_mthds(
            pymnt_mthd_name, pymnt_mthd_desc, current_asst_acnt_id, 
            created_by, creation_date, last_update_by, last_update_date, 
            supported_doc_type, bckgrnd_process_name, org_id, is_enabled) " .
        "VALUES ('" . loc_db_escape_string($mthdNm) .
        "', '" . loc_db_escape_string($mthdDesc) .
        "', " . $accntID .
        ", " . $usrID . ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($docType) .
        "', '" . loc_db_escape_string($bckgrndPrcss) . "', " . $orgid .
        ",'" . cnvrtBoolToBitStr($isenbld) .
        "')";
    return execUpdtInsSQL($insSQL);
}

function updtPymntMthd($mthdID, $mthdNm, $mthdDesc, $accntID, $docType, $bckgrndPrcss, $isenbld)
{
    global $usrID;
    $insSQL = "UPDATE accb.accb_paymnt_mthds SET 
            pymnt_mthd_name='" . loc_db_escape_string($mthdNm) .
        "', pymnt_mthd_desc='" . loc_db_escape_string($mthdDesc) .
        "', current_asst_acnt_id=" . $accntID .
        ", last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS')" .
        ", supported_doc_type='" . loc_db_escape_string($docType) .
        "', bckgrnd_process_name='" . loc_db_escape_string($bckgrndPrcss) .
        "', is_enabled = '" . cnvrtBoolToBitStr($isenbld) .
        "' WHERE paymnt_mthd_id = " . $mthdID;
    return execUpdtInsSQL($insSQL);
}

function deletePymntMthd($valLnid, $mthdNm)
{
    $delSQL = "DELETE FROM accb.accb_paymnt_mthds WHERE paymnt_mthd_id = " . $valLnid;
    return execUpdtInsSQL($delSQL, "Pay Method Name:" . $mthdNm);
}

function get_PymntMthds($offset, $limit_size, $orgID)
{
    $strSql = "SELECT paymnt_mthd_id, pymnt_mthd_name, pymnt_mthd_desc, current_asst_acnt_id,         
       supported_doc_type, bckgrnd_process_name, is_enabled, 
       accb.get_accnt_num(current_asst_acnt_id)||'.'||accb.get_accnt_name(current_asst_acnt_id) chrg_accnt_nm
       FROM accb.accb_paymnt_mthds a " .
        "WHERE((a.org_id = " . $orgID . ")) ORDER BY 2, 5 LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_PymntMthds($orgID)
{
    $strSql = "SELECT count(1) FROM accb.accb_paymnt_mthds a " .
        "WHERE((a.org_id = " . $orgID . "))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_Org_DfltAcnts($orgID)
{
    global $prsnid;
    $strSql = "SELECT row_id, "
        . "itm_inv_asst_acnt_id, (accb.get_accnt_num(a.itm_inv_asst_acnt_id)||'.'||accb.get_accnt_name(a.itm_inv_asst_acnt_id)) itm_inv_accnt, "
        . "cost_of_goods_acnt_id, (accb.get_accnt_num(a.cost_of_goods_acnt_id)||'.'||accb.get_accnt_name(a.cost_of_goods_acnt_id)) cost_goods_sold_accnt, "
        . "expense_acnt_id, (accb.get_accnt_num(a.expense_acnt_id)||'.'||accb.get_accnt_name(a.expense_acnt_id)) expense_accnt, " .
        "prchs_rtrns_acnt_id, (accb.get_accnt_num(a.prchs_rtrns_acnt_id)||'.'||accb.get_accnt_name(a.prchs_rtrns_acnt_id)) prchs_rtrns_accnt, "
        . "rvnu_acnt_id, (accb.get_accnt_num(a.rvnu_acnt_id)||'.'||accb.get_accnt_name(a.rvnu_acnt_id)) rvnu_acnt, "
        . "sales_rtrns_acnt_id, (accb.get_accnt_num(a.sales_rtrns_acnt_id)||'.'||accb.get_accnt_name(a.sales_rtrns_acnt_id)) sales_rtrns_acnt, "
        . "sales_cash_acnt_id, (accb.get_accnt_num(a.sales_cash_acnt_id)||'.'||accb.get_accnt_name(a.sales_cash_acnt_id)) sales_rtrns_acnt, " .
        "sales_check_acnt_id, (accb.get_accnt_num(a.sales_check_acnt_id)||'.'||accb.get_accnt_name(a.sales_check_acnt_id)) sales_check_acnt, "
        . "sales_rcvbl_acnt_id, (accb.get_accnt_num(a.sales_rcvbl_acnt_id)||'.'||accb.get_accnt_name(a.sales_rcvbl_acnt_id)) sales_rcvbl_acnt, "
        . "rcpt_cash_acnt_id, (accb.get_accnt_num(a.rcpt_cash_acnt_id)||'.'||accb.get_accnt_name(a.rcpt_cash_acnt_id)) rcpt_cash_acnt, " .
        "rcpt_lblty_acnt_id, (accb.get_accnt_num(a.rcpt_lblty_acnt_id)||'.'||accb.get_accnt_name(a.rcpt_lblty_acnt_id)) rcpt_lblty_acnt, "
        . "inv_adjstmnts_lblty_acnt_id, (accb.get_accnt_num(a.inv_adjstmnts_lblty_acnt_id)||'.'||accb.get_accnt_name(a.inv_adjstmnts_lblty_acnt_id)) rcpt_cash_acnt, "
        . "ttl_caa, (accb.get_accnt_num(a.ttl_caa)||'.'||accb.get_accnt_name(a.ttl_caa)) ttl_caa_acnt, "
        . "ttl_cla, (accb.get_accnt_num(a.ttl_cla)||'.'||accb.get_accnt_name(a.ttl_cla)) ttl_cla_acnt, " .
        "ttl_aa, (accb.get_accnt_num(a.ttl_aa)||'.'||accb.get_accnt_name(a.ttl_aa)) ttl_aa_acnt, "
        . "ttl_la, (accb.get_accnt_num(a.ttl_la)||'.'||accb.get_accnt_name(a.ttl_la)) ttl_la_acnt, 
               ttl_oea, (accb.get_accnt_num(a.ttl_oea)||'.'||accb.get_accnt_name(a.ttl_oea)) ttl_oea_acnt, 
               ttl_ra, (accb.get_accnt_num(a.ttl_ra)||'.'||accb.get_accnt_name(a.ttl_ra)) ttl_ra_acnt, 
               ttl_cgsa, (accb.get_accnt_num(a.ttl_cgsa)||'.'||accb.get_accnt_name(a.ttl_cgsa)) ttl_cgsa_acnt, 
               ttl_ia, (accb.get_accnt_num(a.ttl_ia)||'.'||accb.get_accnt_name(a.ttl_ia)) ttl_ia_acnt, 
               ttl_pea, (accb.get_accnt_num(a.ttl_pea)||'.'||accb.get_accnt_name(a.ttl_pea)) ttl_pea_acnt,
               sales_dscnt_accnt, (accb.get_accnt_num(a.sales_dscnt_accnt)||'.'||accb.get_accnt_name(a.sales_dscnt_accnt)) sales_dscnt, "
        . "prchs_dscnt_accnt, (accb.get_accnt_num(a.prchs_dscnt_accnt)||'.'||accb.get_accnt_name(a.prchs_dscnt_accnt)) prchs_dscnt, "
        . "sales_lblty_acnt_id, (accb.get_accnt_num(a.sales_lblty_acnt_id)||'.'||accb.get_accnt_name(a.sales_lblty_acnt_id)) sales_lblty_acnt, "
        . "bad_debt_acnt_id, (accb.get_accnt_num(a.bad_debt_acnt_id)||'.'||accb.get_accnt_name(a.bad_debt_acnt_id)) bad_debt_acnt, "
        . "rcpt_rcvbl_acnt_id, (accb.get_accnt_num(a.rcpt_rcvbl_acnt_id)||'.'||accb.get_accnt_name(a.rcpt_rcvbl_acnt_id)) rcpt_rcvbl_acnt, "
        . "petty_cash_acnt_id, (accb.get_accnt_num(a.petty_cash_acnt_id)||'.'||accb.get_accnt_name(a.petty_cash_acnt_id)) petty_cash_acnt"
        . " FROM scm.scm_dflt_accnts a " .
        "WHERE(a.org_id = " . $orgID . ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}

/* function createDfltAcnts($orgid) {
  global $usrID;
  $insSQL = "INSERT INTO scm.scm_dflt_accnts(" .
  "itm_inv_asst_acnt_id, cost_of_goods_acnt_id, expense_acnt_id, " .
  "prchs_rtrns_acnt_id, rvnu_acnt_id, sales_rtrns_acnt_id, sales_cash_acnt_id, " .
  "sales_check_acnt_id, sales_rcvbl_acnt_id, rcpt_cash_acnt_id, " .
  "rcpt_lblty_acnt_id, rho_name, org_id, created_by, creation_date, " .
  "last_update_by, last_update_date) " .
  "VALUES (-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,'Default Accounts', " .
  $orgid . ", " . $usrID . ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " .
  $usrID . ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'))";
  return execUpdtInsSQL($insSQL);
  } */

function updateDfltAcnt($rowid, $colNm, $colVal)
{
    global $usrID;
    $updtSQL = "UPDATE scm.scm_dflt_accnts SET " .
        $colNm . " = " . $colVal .
        ", last_update_by=" . $usrID . ", " .
        "last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS') " .
        "WHERE (row_id =" . $rowid . ")";
    return execUpdtInsSQL($updtSQL);
}

function createDocTmpltHdr($orgid, $tmpltNm, $tmpltDesc, $docType, $isenbld)
{
    global $usrID;
    $insSQL = "INSERT INTO accb.accb_doc_tmplts_hdr(
            doc_tmplt_name, doc_tmplt_desc, created_by, 
            creation_date, last_update_by, last_update_date, 
            is_enabled, org_id, doc_type) " .
        "VALUES ('" . loc_db_escape_string($tmpltNm) .
        "', '" . loc_db_escape_string($tmpltDesc) .
        "', " . $usrID . ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'),'" . cnvrtBoolToBitStr($isenbld) .
        "', " . $orgid .
        ", '" . loc_db_escape_string($docType) .
        "')";
    return execUpdtInsSQL($insSQL);
}

function createDocTmpltDet($hdrID, $lineType, $lineDesc, $autoCalc, $incrDcrs, $accntID, $codeBhnd)
{
    global $usrID;
    $insSQL = "INSERT INTO accb.accb_doc_tmplts_det(
            doc_tmplts_hdr_id, line_item_type, line_description, 
            auto_calc, incrs_dcrs, costing_accnt_id, created_by, creation_date, 
            last_update_by, last_update_date, code_behind_id) " .
        "VALUES (" . $hdrID .
        ", '" . loc_db_escape_string($lineType) .
        "', '" . loc_db_escape_string($lineDesc) .
        "', '" . cnvrtBoolToBitStr($autoCalc) .
        "', '" . loc_db_escape_string($incrDcrs) .
        "', " . $accntID .
        ", " . $usrID . ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $codeBhnd .
        ")";
    return execUpdtInsSQL($insSQL);
}

function updtDocTmpltDet($tmpltDetID, $lineType, $lineDesc, $autoCalc, $incrDcrs, $accntID, $codeBhnd)
{
    global $usrID;
    $insSQL = "UPDATE accb.accb_doc_tmplts_det SET 
            line_item_type='" . loc_db_escape_string($lineType) .
        "', line_description='" . loc_db_escape_string($lineDesc) .
        "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), incrs_dcrs='" . loc_db_escape_string($incrDcrs) .
        "', auto_calc = '" . cnvrtBoolToBitStr($autoCalc) .
        "', costing_accnt_id = " . $accntID .
        ", code_behind_id = " . $codeBhnd .
        " WHERE doc_tmplt_det_id = " . $tmpltDetID;
    return execUpdtInsSQL($insSQL);
}

function updtDocTmpltHdr($tmpltHdrID, $tmpltNm, $tmpltDesc, $docType, $isenbld)
{
    global $usrID;
    $insSQL = "UPDATE accb.accb_doc_tmplts_hdr SET 
            doc_tmplt_name='" . loc_db_escape_string($tmpltNm) .
        "', doc_tmplt_desc='" . loc_db_escape_string($tmpltDesc) .
        "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), doc_type='" . loc_db_escape_string($docType) .
        "', is_enabled = '" . cnvrtBoolToBitStr($isenbld) .
        "' WHERE doc_tmplts_hdr_id = " . $tmpltHdrID;
    return execUpdtInsSQL($insSQL);
}

function deleteTmpltHdrNDet($valLnid, $tmpltNm)
{
    $delSQL = "DELETE FROM accb.accb_doc_tmplts_det WHERE doc_tmplts_hdr_id = " . $valLnid;
    $affctd1 = execUpdtInsSQL($delSQL, "Document Template Name:" . $tmpltNm);
    $delSQL = "DELETE FROM accb.accb_doc_tmplts_hdr WHERE doc_tmplts_hdr_id = " . $valLnid;
    $affctd2 = execUpdtInsSQL($delSQL, "Document Template Name:" . $tmpltNm);
    if ($affctd2 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Template Transaction Line(s)!";
        $dsply .= "<br/>Deleted $affctd2 Document Template(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteTmpltDet($valLnid, $tmpltNm)
{
    $delSQL = "DELETE FROM accb.accb_doc_tmplts_det WHERE doc_tmplt_det_id = " . $valLnid;
    $affctd1 = execUpdtInsSQL($delSQL, "Document Template Name/Line Type = " . $tmpltNm);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Template Transaction Line(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_DocTmpltsHdr($searchWord, $searchIn, $offset, $limit_size, $orgID)
{
    $strSql = "";
    $whrcls = "";

    if ($searchIn == "Template Name") {
        $whrcls = " and (a.doc_tmplt_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Template Description") {
        $whrcls = " and (a.doc_tmplt_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Document Type") {
        $whrcls = " and (a.doc_type ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT doc_tmplts_hdr_id, doc_tmplt_name, doc_tmplt_desc, doc_type, is_enabled
        FROM accb.accb_doc_tmplts_hdr a " .
        "WHERE((a.org_id = " . $orgID . ")" . $whrcls .
        ") ORDER BY doc_tmplt_name LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_DocTmpltsHdr($searchWord, $searchIn, $orgID)
{
    $strSql = "";
    $whrcls = "";

    if ($searchIn == "Template Name") {
        $whrcls = " and (a.doc_tmplt_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Template Description") {
        $whrcls = " and (a.doc_tmplt_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Document Type") {
        $whrcls = " and (a.doc_type ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT count(1) FROM accb.accb_doc_tmplts_hdr a " .
        "WHERE((a.org_id = " . $orgID . ")" . $whrcls .
        ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_OneDocTmpltsHdr($tmpltHdrID)
{
    $strSql = "SELECT doc_tmplts_hdr_id, doc_tmplt_name, doc_tmplt_desc, doc_type, is_enabled
        FROM accb.accb_doc_tmplts_hdr a " .
        "WHERE(a.doc_tmplts_hdr_id = " . $tmpltHdrID . ")";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_DocTmpltsDet($tmpltHdrID)
{
    $strSql = "SELECT doc_tmplt_det_id, line_item_type, line_description, 
incrs_dcrs, costing_accnt_id, 
       accb.get_accnt_num(costing_accnt_id)||'.'||accb.get_accnt_name(costing_accnt_id) costing_accnt, auto_calc, code_behind_id,
       scm.istaxwthhldng(code_behind_id)
  FROM accb.accb_doc_tmplts_det a " .
        "WHERE((a.doc_tmplts_hdr_id = " . $tmpltHdrID . ")) ORDER BY line_item_type ASC ";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_DocTmpltsDet($tmpltHdrID)
{
    $strSql = "SELECT count(1)
  FROM accb.accb_doc_tmplts_det a " .
        "WHERE((a.doc_tmplts_hdr_id = " . $tmpltHdrID . "))";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function isTaxItmInUse($itmID)
{
    if ($itmID <= 0) {
        return false;
    }
    $strSql = "SELECT a.tax_code_id " .
        "FROM scm.scm_sales_invc_det a " .
        "WHERE(a.tax_code_id = " . $itmID . ") LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    $strSql = "SELECT a.code_id_behind " .
        "FROM scm.scm_doc_amnt_smmrys a " .
        "WHERE(a.code_id_behind = " . $itmID . " and a.code_id_behind>0) LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    $strSql = "SELECT a.code_id_behind " .
        "FROM accb.accb_pybls_amnt_smmrys a " .
        "WHERE(a.code_id_behind = " . $itmID . " and a.code_id_behind>0) LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    $strSql = "SELECT a.code_id_behind " .
        "FROM accb.accb_rcvbl_amnt_smmrys a " .
        "WHERE(a.code_id_behind = " . $itmID . " and a.code_id_behind>0) LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    $strSql = "SELECT a.wth_tax_code_id " .
        "FROM scm.scm_cstmr_suplr_sites a " .
        "WHERE(a.wth_tax_code_id = " . $itmID . " or a.discount_code_id = " . $itmID . ") LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    $strSql = "SELECT a.item_id " .
        "FROM inv.inv_itm_list a " .
        "WHERE(a.tax_code_id = " . $itmID . " or a.dscnt_code_id = " .
        $itmID . " or a.extr_chrg_id = " . $itmID . ") LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    return false;
}

function deleteTaxItm($itmid, $itmNm)
{
    $trnsCnt1 = 0;
    $trnsCnt2 = 0;
    $trnsCnt3 = 0;
    $trnsCnt4 = 0;
    $trnsCnt5 = 0;
    $trnsCnt6 = 0;
    $strSql = "SELECT count(1) FROM accb.accb_pybls_amnt_smmrys a WHERE(a.code_id_behind = " . $itmid . ")";
    $result1 = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    $strSql = "SELECT count(1) FROM accb.accb_rcvbl_amnt_smmrys a WHERE(a.code_id_behind = " . $itmid . ")";
    $result2 = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result2)) {
        $trnsCnt2 = (float) $row[0];
    }
    $strSql = "SELECT count(1) FROM scm.scm_doc_amnt_smmrys a WHERE(a.code_id_behind = " . $itmid . ")";
    $result3 = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result3)) {
        $trnsCnt3 = (float) $row[0];
    }
    $strSql = "SELECT count(1) FROM scm.scm_sales_invc_det a WHERE(a.tax_code_id = " . $itmid . ")";
    $result4 = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result4)) {
        $trnsCnt4 = (float) $row[0];
    }
    $strSql = "SELECT count(1) FROM scm.scm_cstmr_suplr_sites a WHERE(a.wth_tax_code_id = " . $itmid . " or a.discount_code_id = " . $itmid . ")";
    $result5 = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result5)) {
        $trnsCnt5 = (float) $row[0];
    }
    $strSql = "SELECT count(1) FROM inv.inv_itm_list a WHERE(a.tax_code_id = " . $itmid . " or a.dscnt_code_id = " . $itmid . " or a.extr_chrg_id = " . $itmid . ")";
    $result6 = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result6)) {
        $trnsCnt6 = (float) $row[0];
    }
    if (($trnsCnt1 + $trnsCnt2 + $trnsCnt3 + $trnsCnt4 + $trnsCnt5 + $trnsCnt6) > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete a Tax/Discount/Charge Code used in Transaction!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM scm.scm_tax_codes WHERE code_id = " . $itmid;
    $affctd1 = execUpdtInsSQL($delSQL, "Item Name = " . $itmNm);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Tax/Discount/Charge Item(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function isTxCdeSQLValid($selSQL, $untiPrice, $qty, &$CalcItemValue)
{
    set_error_handler("rhoErrorHandler3");
    try {
        if (strpos(strtoupper($selSQL), "DELETE ") !== FALSE || strpos(strtoupper($selSQL), "UPDATE ") !== FALSE || strpos(
            strtoupper($selSQL),
            "INSERT "
        ) !== FALSE || strpos(strtoupper($selSQL), "TRUNCATE ") !== FALSE) {
            return false;
        }
        $conn = getConn();
        $result = loc_db_query($conn, str_replace("{:qty}", $qty, str_replace("{:unit_price}", $untiPrice, $selSQL)));
        if (!$result) {
            return false;
        } else {
            $boolRes = (loc_db_num_rows($result) > 0);
            while ($rw = loc_db_fetch_array($result)) {
                $CalcItemValue = (float) $rw[0];
            }
            return $boolRes;
        }
        loc_db_close($conn);
        return false;
    } catch (\Exception $ex) {
        return false;
    }
}

function getChargeItmID($itmname, $orgid)
{
    $sqlStr = "select code_id from scm.scm_tax_codes where lower(code_name) = '" .
        loc_db_escape_string($itmname) . "' and org_id = " . $orgid;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function get_One_TaxDet($codeID)
{
    $strSql = "SELECT a.code_id, a.code_name, a.code_desc, " .
        "a.itm_type, a.is_enabled, a.taxes_payables_accnt_id, " .
        "a.dscount_expns_accnt_id, " .
        "a.chrge_revnu_accnt_id, a.sql_formular, a.is_recovrbl_tax, a.is_withldng_tax, 
        a.tax_expense_accnt_id, a.prchs_dscnt_accnt_id, a.chrge_expns_accnt_id, a.is_parent, a.child_code_ids " .
        "FROM scm.scm_tax_codes a " .
        "WHERE(a.code_id = " . $codeID . ") ORDER BY a.itm_type, a.code_name";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getTaxNm($codeID)
{
    $strSql = "SELECT a.code_name FROM scm.scm_tax_codes a " .
        "WHERE(a.code_id = " . $codeID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getTaxID($codeNm, $orgID)
{
    $strSql = "SELECT a.code_id FROM scm.scm_tax_codes a " .
        "WHERE(a.code_name = '" . loc_db_escape_string($codeNm) . "' and a.org_id = " . $orgID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function get_Basic_Tax($searchWord, $searchIn, $offset, $limit_size, $orgID)
{
    $strSql = "";
    if ($searchIn == "Item Name") {
        $strSql = "SELECT a.code_id, a.code_name, a.itm_type " .
            "FROM scm.scm_tax_codes a " .
            "WHERE ((a.code_name ilike '" . loc_db_escape_string($searchWord) .
            "') AND (a.org_id = " . $orgID . ")) ORDER BY a.itm_type, a.code_name LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    } else if ($searchIn == "Item Description") {
        $strSql = "SELECT a.code_id, a.code_name, a.itm_type " .
            "FROM scm.scm_tax_codes a " .
            "WHERE ((a.code_desc ilike '" . loc_db_escape_string($searchWord) .
            "') AND (a.org_id = " . $orgID . ")) ORDER BY a.itm_type, a.code_name LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    } else {
        $strSql = "SELECT a.code_id, a.code_name, a.itm_type " .
            "FROM scm.scm_tax_codes a " .
            "WHERE ((a.itm_type ilike '" . loc_db_escape_string($searchWord) .
            "') AND (a.org_id = " . $orgID . ")) ORDER BY a.itm_type, a.code_name LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    }
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_Tax($searchWord, $searchIn, $orgID)
{
    $strSql = "";
    if ($searchIn == "Item Name") {
        $strSql = "SELECT count(1) " .
            "FROM scm.scm_tax_codes a " .
            "WHERE ((a.code_name ilike '" . loc_db_escape_string($searchWord) .
            "') AND (a.org_id = " . $orgID . "))";
    } else if ($searchIn == "Item Description") {
        $strSql = "SELECT count(1)  " .
            "FROM scm.scm_tax_codes a " .
            "WHERE ((a.code_desc ilike '" . loc_db_escape_string($searchWord) .
            "') AND (a.org_id = " . $orgID . "))";
    } else {
        $strSql = "SELECT count(1)  " .
            "FROM scm.scm_tax_codes a " .
            "WHERE ((a.itm_type ilike '" . loc_db_escape_string($searchWord) .
            "') AND (a.org_id = " . $orgID . "))";
    }
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_OneTaxCodesHdr($code_id)
{
    $strSql = "SELECT a.code_id, a.code_name, a.code_desc, a.itm_type, a.is_enabled, 
        taxes_payables_accnt_id, accb.get_accnt_num(taxes_payables_accnt_id)||'.'||accb.get_accnt_name(taxes_payables_accnt_id) taxes_payables_accnt,
        dscount_expns_accnt_id, accb.get_accnt_num(dscount_expns_accnt_id)||'.'||accb.get_accnt_name(dscount_expns_accnt_id) dscount_expns_accnt,
        chrge_revnu_accnt_id, accb.get_accnt_num(chrge_revnu_accnt_id)||'.'||accb.get_accnt_name(chrge_revnu_accnt_id) chrge_revnu_accnt,
        sql_formular, is_recovrbl_tax, 
        tax_expense_accnt_id, accb.get_accnt_num(tax_expense_accnt_id)||'.'||accb.get_accnt_name(tax_expense_accnt_id) tax_expense_accnt,
        prchs_dscnt_accnt_id, accb.get_accnt_num(prchs_dscnt_accnt_id)||'.'||accb.get_accnt_name(prchs_dscnt_accnt_id) prchs_dscnt_accnt,
        chrge_expns_accnt_id, accb.get_accnt_num(chrge_expns_accnt_id)||'.'||accb.get_accnt_name(chrge_expns_accnt_id) chrge_expns_accnt,
        is_withldng_tax, is_parent, child_code_ids " .
        "FROM scm.scm_tax_codes a " .
        "WHERE (a.code_id = " . $code_id . ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function createTaxRec(
    $orgid,
    $codename,
    $codedesc,
    $itmTyp,
    $isEnbld,
    $taxAcntID,
    $expnsAcntID,
    $rvnuAcntID,
    $sqlFormular,
    $isTxRcvrbl,
    $txExpAccID,
    $prchDscAccID,
    $chrgExpAccID,
    $isWthHldng,
    $isParnt,
    $codeIDs
) {
    global $usrID;
    $insSQL = "INSERT INTO scm.scm_tax_codes(" .
        "code_name, code_desc, created_by, creation_date, last_update_by, " .
        "last_update_date, itm_type, is_enabled, taxes_payables_accnt_id, " .
        "dscount_expns_accnt_id, " .
        "chrge_revnu_accnt_id, " .
        "org_id, sql_formular, 
            is_recovrbl_tax, tax_expense_accnt_id, prchs_dscnt_accnt_id, 
            chrge_expns_accnt_id, is_withldng_tax, is_parent, child_code_ids) " .
        "VALUES ('" . loc_db_escape_string($codename) .
        "', '" . loc_db_escape_string($codedesc) .
        "', " . $usrID . ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($itmTyp) . "', '" .
        cnvrtBoolToBitStr($isEnbld) . "', " . $taxAcntID . ", " .
        $expnsAcntID . ", " . $rvnuAcntID .
        ", " . $orgid . ", '" . loc_db_escape_string($sqlFormular) .
        "', '" . cnvrtBoolToBitStr($isTxRcvrbl) . "', " . $txExpAccID . ", " . $prchDscAccID . ", " . $chrgExpAccID .
        ", '" . cnvrtBoolToBitStr($isWthHldng) . "', '" . cnvrtBoolToBitStr($isParnt) . "', '" .
        $codeIDs . "')";
    return execUpdtInsSQL($insSQL);
}

function updateTaxRec(
    $codeid,
    $codename,
    $codedesc,
    $itmTyp,
    $isEnbld,
    $taxAcntID,
    $expnsAcntID,
    $rvnuAcntID,
    $sqlFormular,
    $isTxRcvrbl,
    $txExpAccID,
    $prchDscAccID,
    $chrgExpAccID,
    $isWthHldng,
    $is_parent,
    $chldCodeIDs
) {
    global $usrID;
    $updtSQL = "UPDATE scm.scm_tax_codes SET " .
        "code_name='" . loc_db_escape_string($codename) .
        "', code_desc='" . loc_db_escape_string($codedesc) .
        "', last_update_by=" . $usrID . ", " .
        "last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), itm_type='" . loc_db_escape_string($itmTyp) .
        "', is_enabled='" . cnvrtBoolToBitStr($isEnbld) .
        "', taxes_payables_accnt_id=" . $taxAcntID . ", " .
        "dscount_expns_accnt_id=" . $expnsAcntID . ", " .
        "chrge_revnu_accnt_id=" . $rvnuAcntID .
        ", sql_formular='" . loc_db_escape_string($sqlFormular) .
        "', is_recovrbl_tax='" . cnvrtBoolToBitStr($isTxRcvrbl) .
        "', tax_expense_accnt_id=" . $txExpAccID .
        ", prchs_dscnt_accnt_id=" . $prchDscAccID .
        ", chrge_expns_accnt_id=" . $chrgExpAccID .
        ", is_withldng_tax='" . cnvrtBoolToBitStr($isWthHldng) .
        "', is_parent='" . cnvrtBoolToBitStr($is_parent) .
        "', child_code_ids='" . $chldCodeIDs .
        "' WHERE (code_id =" . $codeid . ")";
    return execUpdtInsSQL($updtSQL);
}

function get_Forums($searchFor, $searchIn, $offset, $limit_size, $ordrBy)
{
    global $qStrtDte;
    global $qEndDte;
    global $artCategory;
    global $prsnid;

    $extrWhr = "";
    $ordrByCls = "ORDER BY tbl1.article_id DESC";
    if ($artCategory != "" && $artCategory != "All") {
        $extrWhr = " AND (tbl1.article_category ilike '" . loc_db_escape_string($artCategory) . "')";
    }
    $extrWhr .= " AND (tbl1.is_published = '1') 
            and (tbl1.article_category IN ('Forum Topic','Chat Room')) 
            and (org.does_prsn_hv_crtria_id($prsnid,tbl1.allowed_group_id, tbl1.allowed_group_type)>0)";
    $wherecls = " AND (tbl1.article_header ilike '" . loc_db_escape_string($searchFor) . "' "
        . "or tbl1.header_url ilike '" . loc_db_escape_string($searchFor) .
        "' OR tbl1.article_category ilike '" . loc_db_escape_string($searchFor) .
        "' OR tbl1.article_body ilike '" . loc_db_escape_string($searchFor) . "')";

    if ($qStrtDte != "") {
        $wherecls .= " AND (tbl1.publishing_date >= '" . loc_db_escape_string($qStrtDte) . "')";
    }
    if ($qEndDte != "") {
        $wherecls .= " AND (tbl1.publishing_date <= '" . loc_db_escape_string($qEndDte) . "')";
    }
    if ($ordrBy == "Date Published") {
        $ordrByCls = "ORDER BY tbl1.publishing_date DESC";
    } else if ($ordrBy == "No. of Hits") {
        $ordrByCls = "ORDER BY tbl1.hits DESC";
    } else if ($ordrBy == "Category") {
        $ordrByCls = "ORDER BY tbl1.article_category ASC";
    } else if ($ordrBy == "Title") {
        $ordrByCls = "ORDER BY tbl1.article_header ASC";
    } else {
        $ordrByCls = "ORDER BY tbl1.publishing_date DESC";
    }
    $sqlStr = "SELECT tbl1.* FROM (SELECT a.article_id, a.article_category, a.article_header, a.header_url, a.article_body,  
       a.is_published, to_char(to_timestamp(a.publishing_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') publishing_date, 
       a.author_name, a.author_email, prs.get_prsn_loc_id(a.author_prsn_id), 
       (select count(b.posted_cmnt_id) from self.self_article_cmmnts b where a.article_id = b.article_id) hits,
       a.allowed_group_type, a.allowed_group_id, a.article_intro_msg, a.local_classification   
  FROM self.self_articles a) tbl1  
WHERE (1=1" . $extrWhr . "$wherecls) $ordrByCls LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_ForumTtls($searchFor, $searchIn)
{
    //global $usrID;
    global $qStrtDte;
    global $qEndDte;
    global $artCategory;
    global $isMaster;
    global $prsnid;
    $extrWhr = "";

    if ($artCategory != "" && $artCategory != "All") {
        $extrWhr = " AND (a.article_category ilike '" . loc_db_escape_string($artCategory) . "')";
    }
    $extrWhr .= " AND (a.is_published = '1') 
            and (a.article_category NOT IN ('Forum Topic','Chat Room')) 
            and (org.does_prsn_hv_crtria_id($prsnid,a.allowed_group_id, a.allowed_group_type)>0)";
    $wherecls = " AND (a.article_header ilike '" . loc_db_escape_string($searchFor) . "' "
        . "or a.header_url ilike '" . loc_db_escape_string($searchFor) .
        "' OR a.article_category ilike '" . loc_db_escape_string($searchFor) .
        "' OR a.article_body ilike '" . loc_db_escape_string($searchFor) . "')";

    if ($qStrtDte != "") {
        $qStrtDte = cnvrtDMYTmToYMDTm($qStrtDte);
        $wherecls .= " AND (a.publishing_date >= '" . loc_db_escape_string($qStrtDte) . "')";
    }
    if ($qEndDte != "") {
        $qEndDte = cnvrtDMYTmToYMDTm($qEndDte);
        $wherecls .= " AND (a.publishing_date <= '" . loc_db_escape_string($qEndDte) . "')";
    }

    $sqlStr = "SELECT count(1) 
  FROM self.self_articles a 
  WHERE (1=1" . $extrWhr . "$wherecls)";
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_DfltCstmrSpplrSiteID($spplrID)
{
    $strSql = "SELECT cust_sup_site_id
	FROM scm.scm_cstmr_suplr_sites
	WHERE cust_supplier_id=" . $spplrID . "
	and is_enabled='1' ORDER BY cust_sup_site_id DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return ((int) $row[0]);
    }
    return -1;
}

function get_CstmrSpplrSiteLnkID($spplrID, $siteID)
{
    $strSql = "SELECT cust_sup_site_id
	FROM scm.scm_cstmr_suplr_sites
	WHERE cust_supplier_id=" . $spplrID . "
	and is_enabled='1' and cust_sup_site_id=" . $siteID .
        " ORDER BY cust_sup_site_id DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return ((int) $row[0]);
    }
    return -1;
}

function getCstmrSiteNm($siteID, $cstmrID)
{
    $sqlStr = "select site_name from scm.scm_cstmr_suplr_sites where cust_supplier_id = " . loc_db_escape_string($cstmrID) .
        " and cust_sup_site_id = " . loc_db_escape_string(strtolower($siteID)) . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

//FUND MANAGEMENT
function get_InvstTransDocHdr($searchWord, $searchIn, $offset, $limit_size, $orgID, $shwUnpstdOnly)
{
    $strSql = "";
    $whrcls = "";
    $unpstdCls = "";
    if ($shwUnpstdOnly) {
        $unpstdCls = " AND (a.REQUEST_STATUS IN ('Not Finalized') " .
            "or accb.is_gl_batch_pstd(a.gl_batch_id)='0' )";
    }
    if ($searchIn == "Ref. Number") {
        $whrcls = " and (a.investment_ref_num ilike '" . loc_db_escape_string($searchWord) .
            "' or a.investor_client_id ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Description") {
        $whrcls = " and (a.investment_narration ilike '" . loc_db_escape_string($searchWord) . "'"
            . "or a.transaction_type ilike '" . loc_db_escape_string($searchWord) . "')";
    } else {
        $whrcls = " and (a.investment_ref_num ilike '" . loc_db_escape_string($searchWord) .
            "' or a.investor_client_id ilike '" . loc_db_escape_string($searchWord) .
            "' or a.investment_narration ilike '" . loc_db_escape_string($searchWord) . "'"
            . "or a.transaction_type ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT a.investment_id,a.item_type_id,a.investor_client_id, a.investment_ref_num
,b.item_type_name, a.investment_narration,a.REQUEST_STATUS, a.transaction_type,
a.roll_over_type,a.prchs_amnt,a.maturity_amnt, 
round(a.maturity_amnt-a.prchs_amnt,2) expctd_intrst, a.intrst_rate,b.investment_period,b.period_type,
to_char(to_timestamp(a.purchase_date,'YYYY-MM-DD'),'DD-Mon-YYYY') purchase_date,
(CASE WHEN a.maturity_date !='' THEN to_char(to_timestamp(a.maturity_date,'YYYY-MM-DD'),'DD-Mon-YYYY') ELSE a.maturity_date END) maturity_date, 
a.gl_batch_id, accb.get_gl_batch_name(a.gl_batch_id), accb.is_gl_batch_pstd(a.gl_batch_id),
a.cur_exchng_rate, a.entrd_crcny_id, gst.get_pssbl_val(a.entrd_crcny_id) 
        FROM pay.pay_fund_management a, pay.loan_pymnt_invstmnt_typs b 
        WHERE((a.item_type_id=b.item_type_id and a.org_id = " . $orgID . ")" . $whrcls . $unpstdCls .
        ") ORDER BY investment_id DESC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_InvstTransDoc($searchWord, $searchIn, $orgID, $shwUnpstdOnly)
{
    $strSql = "";
    $whrcls = "";
    $unpstdCls = "";
    if ($shwUnpstdOnly) {
        $unpstdCls = " AND (a.REQUEST_STATUS IN ('Not Finalized') " .
            "or accb.is_gl_batch_pstd(a.gl_batch_id)='0' )";
    }
    if ($searchIn == "Ref. Number") {
        $whrcls = " and (a.investment_ref_num ilike '" . loc_db_escape_string($searchWord) .
            "' or a.investor_client_id ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Description") {
        $whrcls = " and (a.investment_narration ilike '" . loc_db_escape_string($searchWord) . "'"
            . "or a.transaction_type ilike '" . loc_db_escape_string($searchWord) . "')";
    } else {
        $whrcls = " and (a.investment_ref_num ilike '" . loc_db_escape_string($searchWord) .
            "' or a.investor_client_id ilike '" . loc_db_escape_string($searchWord) .
            "' or a.investment_narration ilike '" . loc_db_escape_string($searchWord) . "'"
            . "or a.transaction_type ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT count(1) FROM pay.pay_fund_management a, pay.loan_pymnt_invstmnt_typs b 
        WHERE((a.item_type_id=b.item_type_id and a.org_id = " . $orgID . ")" . $whrcls . $unpstdCls . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_One_InvstTransDocHdr($hdrID)
{
    $strSql = "SELECT a.investment_id,a.item_type_id,a.investor_client_id, a.investment_ref_num
,b.item_type_name, a.investment_narration,a.REQUEST_STATUS, a.transaction_type,
a.roll_over_type,a.prchs_amnt,a.maturity_amnt, 
round(a.maturity_amnt-a.prchs_amnt,2) expctd_intrst, a.intrst_rate,b.investment_period,b.period_type,
to_char(to_timestamp(a.purchase_date,'YYYY-MM-DD'),'DD-Mon-YYYY') purchase_date,
(CASE WHEN a.maturity_date !='' THEN to_char(to_timestamp(a.maturity_date,'YYYY-MM-DD'),'DD-Mon-YYYY') ELSE a.maturity_date END) maturity_date, 
a.gl_batch_id, accb.get_gl_batch_name(a.gl_batch_id), accb.is_gl_batch_pstd(a.gl_batch_id),
a.cur_exchng_rate, a.entrd_crcny_id, gst.get_pssbl_val(a.entrd_crcny_id)
        FROM pay.pay_fund_management a, pay.loan_pymnt_invstmnt_typs b 
        WHERE((a.item_type_id=b.item_type_id and a.investment_id = " . $hdrID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getNewPayInvstTransID()
{
    $strSql = "select nextval('pay.pay_fund_management_investment_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function createInvstTransDocHdr(
    $hdrID,
    $orgid,
    $itmTypID,
    $invstrClientID,
    $refNum,
    $narration,
    $trnsTyp,
    $rollOvrTyp,
    $entrdCrncyID,
    $prchsAmnt,
    $mtrtyAmnt,
    $exchngRate,
    $intrstRate,
    $prchsDate,
    $matrtyDate
) {
    global $usrID;
    if ($prchsDate != "") {
        $prchsDate = cnvrtDMYToYMD($prchsDate);
    }
    if ($matrtyDate != "") {
        $matrtyDate = cnvrtDMYToYMD($matrtyDate);
    }
    $insSQL = "INSERT INTO pay.pay_fund_management(investment_id, item_type_id, investor_client_id, investment_ref_num,
                                    investment_narration, transaction_type, roll_over_type, entrd_crcny_id, prchs_amnt,
                                    maturity_amnt, cur_exchng_rate, intrst_rate, gl_batch_id, request_status,
                                    purchase_date, maturity_date, org_id, created_by, creation_date, last_update_by,
                                    last_update_date) " .
        "VALUES (" . $hdrID . ", " . $itmTypID . ", '" . loc_db_escape_string($invstrClientID) . "', '" . loc_db_escape_string($refNum) .
        "','" . loc_db_escape_string($narration) .
        "','" . loc_db_escape_string($trnsTyp) .
        "','" . loc_db_escape_string($rollOvrTyp) .
        "'," . $entrdCrncyID . "," . $prchsAmnt . "," . $mtrtyAmnt . "," . $exchngRate . "," . $intrstRate .
        ",-1, 'Not Finalized','" . loc_db_escape_string($prchsDate) .
        "','" . loc_db_escape_string($matrtyDate) .
        "'," . $orgid . ", " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'))";
    return execUpdtInsSQL($insSQL);
}

function updtInvstTransDocHdr(
    $hdrID,
    $itmTypID,
    $invstrClientID,
    $refNum,
    $narration,
    $trnsTyp,
    $rollOvrTyp,
    $entrdCrncyID,
    $prchsAmnt,
    $mtrtyAmnt,
    $exchngRate,
    $intrstRate,
    $prchsDate,
    $matrtyDate
) {
    global $usrID;
    if ($prchsDate != "") {
        $prchsDate = cnvrtDMYToYMD($prchsDate);
    }
    if ($matrtyDate != "") {
        $matrtyDate = cnvrtDMYToYMD($matrtyDate);
    }
    $insSQL = "
UPDATE pay.pay_fund_management
SET item_type_id=" . $itmTypID . ",
    investor_client_id='" . loc_db_escape_string($invstrClientID) . "',
    investment_ref_num='" . loc_db_escape_string($refNum) . "',
    investment_narration='" . loc_db_escape_string($narration) . "',
    transaction_type='" . loc_db_escape_string($trnsTyp) . "',
    roll_over_type='" . loc_db_escape_string($rollOvrTyp) . "',
    entrd_crcny_id=" . $entrdCrncyID . ",
    prchs_amnt=" . $prchsAmnt . ",
    maturity_amnt=" . $mtrtyAmnt . ",
    cur_exchng_rate=" . $exchngRate . ",
    intrst_rate=" . $intrstRate . ",
    gl_batch_id=-1,
    request_status='Not Finalized',
    purchase_date='" . loc_db_escape_string($prchsDate) . "',
    maturity_date='" . loc_db_escape_string($matrtyDate) . "',
    last_update_by=" . $usrID . ",
    last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS')
WHERE investment_id = " . $hdrID;
    return execUpdtInsSQL($insSQL);
}

function updtInvstTransDocHdr22($hdrID, $narration, $trnsTyp, $rollOvrTyp, $mtrtyAmnt, $oldmtrtyAmnt, $matrtyDate)
{
    global $usrID;
    if ($matrtyDate != "") {
        $matrtyDate = cnvrtDMYToYMD($matrtyDate);
    }
    $insSQL = "
UPDATE pay.pay_fund_management
SET investment_narration='" . loc_db_escape_string($narration) . "',
    transaction_type='" . loc_db_escape_string($trnsTyp) . "',
    roll_over_type='" . loc_db_escape_string($rollOvrTyp) . "',
    maturity_amnt=" . $mtrtyAmnt . ",
    old_maturity_amnt=" . $oldmtrtyAmnt . ",
    gl_batch_id=-1,
    request_status='Not Finalized',
    maturity_date='" . loc_db_escape_string($matrtyDate) . "',
    last_update_by=" . $usrID . ",
    last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS')
WHERE investment_id = " . $hdrID;
    return execUpdtInsSQL($insSQL);
}

function updtInvstTransDocApprvl($docid, $apprvlSts, $nxtApprvl)
{
    global $usrID;
    $extrCls = "";
    if ($apprvlSts == "Cancelled") {
        $extrCls = ", prchs_amnt=0, maturity_amnt=0";
    }
    $updtSQL = "UPDATE pay.pay_fund_management SET " .
        "request_status='" . loc_db_escape_string($apprvlSts) .
        "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS')"
        . "" . $extrCls . " WHERE (investment_id = " . $docid . ")";
    return execUpdtInsSQL($updtSQL);
}

function updtInvstTransDocGLBatch($docid, $glBatchID)
{
    global $usrID;
    $updtSQL = "UPDATE pay.pay_fund_management SET " .
        "gl_batch_id=" . $glBatchID .
        ", last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS') WHERE (investment_id = " .
        $docid . ")";
    return execUpdtInsSQL($updtSQL);
}

function updateInvstTransDocVoid($batchid, $rvrsalReason, $nwStatus, $nextAction, $glBatchID)
{
    global $usrID;
    $updtSQL = "UPDATE pay.pay_fund_management " .
        "SET investment_narration='" . loc_db_escape_string($rvrsalReason) .
        "', REQUEST_STATUS='" . loc_db_escape_string($nwStatus) .
        "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS')" .
        ", gl_batch_id=" . $glBatchID .
        ", prchs_amnt=0, maturity_amnt=0 WHERE investment_id = " . $batchid;
    return execUpdtInsSQL($updtSQL);
}

function deleteInvstTrans($valLnid, $docNum)
{
    $strSql = "SELECT count(1) FROM pay.pay_fund_management a WHERE(a.investment_id = " . $valLnid .
        " and a.request_status IN ('Validated', 'Approved', 'Cancelled','Initiated','Reviewed','Finalized'))";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if (($trnsCnt1) > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete a Finalized Transaction!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL2 = "DELETE FROM pay.pay_trans_attchmnts WHERE src_pkey_id = " . $valLnid . " and src_trans_type='INVESTMENT'";
    $affctd2 = execUpdtInsSQL($delSQL2, "Desc:" . $docNum);
    $delSQL = "DELETE FROM pay.pay_fund_management WHERE investment_id = " . $valLnid;
    $affctd1 = execUpdtInsSQL($delSQL, "Desc:" . $docNum);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Transaction(s)!";
        $dsply .= "<br/>Deleted $affctd2 Transaction Attachment(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function createInvstTrnsAccntng(
    $sbmtdPayInvstTransID,
    $payInvstTrnsType,
    $payInvstItemTypID,
    $payInvstTransDesc,
    $payInvstRefNum,
    $payInvstTransInvcCur,
    $payInvstPrchsDte,
    $payInvstExchngRate,
    $payInvstPrchsAmnt,
    $payInvstMatureAmnt,
    $payOLDMatureAmnt,
    &$afftctd1,
    &$errMsg
) {
    global $fnccurid;
    global $orgID;
    global $usrID;
    //Save Fund Management Double Entry Lines
    //For Investment Purchases
    //1. Debit Investment Asset Accnt and Credit Cash/Bank (Purchase Amount)
    //2. Debit Interest Receivable and Credit Defferred Interest Liability (Maturity Amount - Purchase Amount)
    //For Investment Redisounts or Maturity
    //1. Debit Deffered Interest Lblty and Credit Interest Revenue Accnt(Maturity Amount - Purchase Amount)
    //2. Debit Cash/Bank and Credit Interest Receivable (Maturity Amount - Purchase Amount)
    //3. Debit Cash/Bank and Credit Investment Asset Accnt (Purchase Amount)
    try {
        $curLovID = getLovID("Currencies");
        $lnSmmryLnID = $sbmtdPayInvstTransID;
        $ln_FuncExchgRate = $payInvstExchngRate;
        $lnRefDoc = $payInvstRefNum;
        $lineDesc = $payInvstTransDesc;
        $lineCurNm = $payInvstTransInvcCur;
        $lineCurID = getPssblValID($lineCurNm, $curLovID);
        $lineTransDate = $payInvstPrchsDte;
        $accnRslt = get_One_PayTransTyps($payInvstItemTypID);
        $payTransTypsPrd = 0;
        $payTransPeriodTyp = "Days";
        $payTransCshAcntID = -1;
        $payTransAssetAcntID = -1;
        $payTransRcvblAcntID = -1;
        $payTransLbltyAcntID = -1;
        $payTransRvnuAcntID = -1;
        while ($row = loc_db_fetch_array($accnRslt)) {
            $payTransTypsPrd = (float) $row[8];
            $payTransPeriodTyp = $row[9];
            $payTransCshAcntID = (int) $row[10];
            $payTransAssetAcntID = (int) $row[12];
            $payTransRcvblAcntID = (int) $row[14];
            $payTransLbltyAcntID = (int) $row[16];
            $payTransRvnuAcntID = (int) $row[18];
        }
        $payInvstTransDfltBalsAcntID = $payTransCshAcntID;
        $dte = date('ymd');
        $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
        if ($usrTrnsCode == "") {
            $usrTrnsCode = "XX";
        }
        //$userAccntName = getGnrlRecNm("sec.sec_users", "user_id", "user_name", $usrID);
        $gnrtdTrnsNo1 = "INVSTMT" . "-" . $usrTrnsCode . "-" . $dte . "-";
        $gnrtdTrnsNo = $gnrtdTrnsNo1 . str_pad((getRecCount_LstNum("accb.accb_trnsctn_batches", "batch_name", "batch_id", $gnrtdTrnsNo1 . "%") + 1), 3, '0', STR_PAD_LEFT);
        createBatch(
            $orgID,
            $gnrtdTrnsNo,
            $payInvstTransDesc,
            "Internal Pay Fund Management",
            "VALID",
            -1,
            "0",
            $payInvstTransDfltBalsAcntID,
            "",
            $lineCurID,
            $payInvstPrchsDte
        );
        $payInvstTransGLBatchID = getBatchID($gnrtdTrnsNo, $orgID);

        $ln_Arry_IncrsDcrs1 = array("Increase", "Increase");
        $ln_Arry_IncrsDcrs2 = array("Decrease", "Increase");
        $ln_Arry_AccountID1 = array($payTransAssetAcntID, $payTransRcvblAcntID);
        $ln_Arry_AccountID2 = array($payTransCshAcntID, $payTransLbltyAcntID);
        $ln_Arry_entrdAmt = array($payInvstPrchsAmnt, ($payInvstMatureAmnt - $payInvstPrchsAmnt));

        if ($payInvstTrnsType === "REDEEM") {
            $ln_Arry_IncrsDcrs1 = array("Decrease", "Increase", "Increase", "Decrease");
            $ln_Arry_IncrsDcrs2 = array("Increase", "Decrease", "Decrease", "Decrease");
            $ln_Arry_AccountID1 = array($payTransLbltyAcntID, $payTransCshAcntID, $payTransCshAcntID, $payTransLbltyAcntID);
            $ln_Arry_AccountID2 = array($payTransRvnuAcntID, $payTransRcvblAcntID, $payTransAssetAcntID, $payTransRcvblAcntID);
            $ln_Arry_entrdAmt = array(($payInvstMatureAmnt - $payInvstPrchsAmnt), ($payInvstMatureAmnt - $payInvstPrchsAmnt), $payInvstMatureAmnt, ($payOLDMatureAmnt - $payInvstMatureAmnt));
            if (($payOLDMatureAmnt - $payInvstPrchsAmnt) <= 0 && ($payInvstMatureAmnt - $payInvstPrchsAmnt) > 0) {
                $ln_Arry_IncrsDcrs1 = array("Increase", "Increase");
                $ln_Arry_IncrsDcrs2 = array("Increase", "Decrease");
                $ln_Arry_AccountID1 = array($payTransCshAcntID, $payTransCshAcntID);
                $ln_Arry_AccountID2 = array($payTransRvnuAcntID, $payTransAssetAcntID);
                $ln_Arry_entrdAmt = array(($payInvstMatureAmnt - $payInvstPrchsAmnt), $payInvstMatureAmnt);
            } else if (($payOLDMatureAmnt - $payInvstPrchsAmnt) <= 0 && ($payInvstMatureAmnt - $payInvstPrchsAmnt) <= 0) {
                $ln_Arry_IncrsDcrs1 = array("Increase");
                $ln_Arry_IncrsDcrs2 = array("Decrease");
                $ln_Arry_AccountID1 = array($payTransCshAcntID);
                $ln_Arry_AccountID2 = array($payTransAssetAcntID);
                $ln_Arry_entrdAmt = array($payInvstMatureAmnt);
            }
        }
        for ($t = 0; $t < count($ln_Arry_IncrsDcrs1); $t++) {
            $ln_IncrsDcrs1 = $ln_Arry_IncrsDcrs1[$t];
            $ln_IncrsDcrs2 = $ln_Arry_IncrsDcrs2[$t];
            $ln_AccountID1 = $ln_Arry_AccountID1[$t];
            $ln_AccountID2 = $ln_Arry_AccountID2[$t];
            $entrdAmt = $ln_Arry_entrdAmt[$t];
            if (!($lnSmmryLnID > 0 && $ln_AccountID1 > 0 && $payInvstTransGLBatchID > 0 && $ln_AccountID2 > 0 && $entrdAmt > 0)) {
                continue;
            }
            $drCrdt1 = dbtOrCrdtAccnt($ln_AccountID1, substr($ln_IncrsDcrs1, 0, 1)) == "Debit" ? "D" : "C";
            if ($drCrdt1 === "D") {
                $ln_IncrsDcrs2 = str_replace("d", "D", str_replace("i", "I", strtolower(incrsOrDcrsAccnt($ln_AccountID2, "Credit"))));
            } else {
                $ln_IncrsDcrs2 = str_replace("d", "D", str_replace("i", "I", strtolower(incrsOrDcrsAccnt($ln_AccountID2, "Debit"))));
            }
            $drCrdt2 = dbtOrCrdtAccnt($ln_AccountID2, substr($ln_IncrsDcrs2, 0, 1)) == "Debit" ? "D" : "C";
            $lineDbtAmt1 = ($drCrdt1 == "D") ? $entrdAmt : 0;
            $lineCrdtAmt1 = ($drCrdt1 == "C") ? $entrdAmt : 0;
            $lineDbtAmt2 = ($drCrdt2 == "D") ? $entrdAmt : 0;
            $lineCrdtAmt2 = ($drCrdt2 == "C") ? $entrdAmt : 0;
            $funcExchRate = $ln_FuncExchgRate;
            if (($funcExchRate == 0 || $funcExchRate == 1) && $lineCurID != $fnccurid) {
                $funcExchRate = round(get_LtstExchRate($lineCurID, $fnccurid, $lineTransDate), 4);
            }
            //$funcCurrAmnt = $funcExchRate * $entrdAmt;
            $netAmnt1 = drCrAccMltplr($ln_AccountID1, $drCrdt1) * $entrdAmt;
            $dbtOrCrdt1 = substr(strtoupper($drCrdt1), 0, 1);
            $accntCurrID1 = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $ln_AccountID1);
            $acntExchRate1 = round(get_LtstExchRate($lineCurID, $accntCurrID1, $lineTransDate), 4);
            $acntAmnt1 = $acntExchRate1 * $entrdAmt;

            $netAmnt2 = drCrAccMltplr($ln_AccountID2, $drCrdt2) * $entrdAmt;
            $dbtOrCrdt2 = substr(strtoupper($drCrdt2), 0, 1);
            $accntCurrID2 = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $ln_AccountID2);
            $acntExchRate2 = round(get_LtstExchRate($lineCurID, $accntCurrID2, $lineTransDate), 4);
            $acntAmnt2 = $acntExchRate1 * $entrdAmt;
            $srcTrnsID1 = -1;
            $srcTrnsID2 = -1;
            //Create First Leg of Summary Transaction as Journal Entry
            //Insert
            $lnTrnsLnID1 = getNewTrnsID();
            $afftctd1 += createTransaction($lnTrnsLnID1, $ln_AccountID1, $lineDesc, $lineDbtAmt1, $lineTransDate, $lineCurID, $payInvstTransGLBatchID, $lineCrdtAmt1, $netAmnt1, $entrdAmt, $lineCurID, $acntAmnt1, $accntCurrID1, $funcExchRate, $acntExchRate1, $dbtOrCrdt1, $lnRefDoc, $srcTrnsID1, $srcTrnsID2, -1, "Internal Pay Fund Management", $lnSmmryLnID);
            //Create Second Leg of Summary Transaction as Journal Entry                        
            //Insert
            $lnTrnsLnID2 = getNewTrnsID();
            $afftctd1 += createTransaction($lnTrnsLnID2, $ln_AccountID2, $lineDesc, $lineDbtAmt2, $lineTransDate, $lineCurID, $payInvstTransGLBatchID, $lineCrdtAmt2, $netAmnt2, $entrdAmt, $lineCurID, $acntAmnt2, $accntCurrID2, $funcExchRate, $acntExchRate2, $dbtOrCrdt2, $lnRefDoc, $srcTrnsID1, $srcTrnsID2, -1, "Internal Pay Fund Management", $lnSmmryLnID);
        }
        //Final Approval
        updtInvstTransDocGLBatch($sbmtdPayInvstTransID, $payInvstTransGLBatchID);
        updtInvstTransDocApprvl($sbmtdPayInvstTransID, "Finalized", "Cancel");
        return true;
    } catch (Exception $e) {
        $errMsg .= 'Caught exception: VALIDATE ACCNTS ' . $e->getMessage();
        return false;
    }
}

function get_InvstTrans_Attachments($searchWord, $offset, $limit_size, $batchID, $batchTyp, &$attchSQL)
{
    $strSql = "SELECT a.attchmnt_id, a.src_pkey_id, a.attchmnt_desc, a.file_name, a.src_trans_type  " .
        "FROM pay.pay_trans_attchmnts a " .
        "WHERE(a.attchmnt_desc ilike '" . loc_db_escape_string($searchWord) .
        "' and a.src_pkey_id = " . $batchID . " and a.src_trans_type='" . loc_db_escape_string($batchTyp) .
        "') ORDER BY a.attchmnt_id LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_InvstTrans_Attachments($searchWord, $batchID, $batchTyp)
{
    $strSql = "SELECT count(1) " .
        "FROM pay.pay_trans_attchmnts a " .
        "WHERE(a.attchmnt_desc ilike '" . loc_db_escape_string($searchWord) .
        "' and a.src_pkey_id = " . $batchID . " and a.src_trans_type='" . loc_db_escape_string($batchTyp) .
        "')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function getInvstTransAttchmtDocs($batchid)
{
    $sqlStr = "SELECT attchmnt_id, file_name, attchmnt_desc, src_pkey_id, src_trans_type 
  FROM pay.pay_trans_attchmnts WHERE 1=1 AND file_name != '' AND doc_hdr_id = " . $batchid;

    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function updateInvstTransDocFlNm($attchmnt_id, $file_name)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE pay.pay_trans_attchmnts SET file_name='"
        . loc_db_escape_string($file_name) .
        "', last_update_by=" . $usrID .
        ", last_update_date='" . $dateStr . "'
                WHERE attchmnt_id=" . $attchmnt_id;
    return execUpdtInsSQL($insSQL);
}

function getNewInvstTransDocID()
{
    $strSql = "select nextval('pay.pay_trans_attchmnts_attchmnt_id_seq')";
    $result = executeSQLNoParams($strSql);

    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function createInvstTransDoc($attchmnt_id, $hdrid, $hdrType, $attchmnt_desc, $file_name)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO pay.pay_trans_attchmnts(
            attchmnt_id, src_pkey_id, src_trans_type, attchmnt_desc, file_name, created_by, 
            creation_date, last_update_by, last_update_date)
             VALUES (" . $attchmnt_id . ", " . $hdrid . ",'"
        . loc_db_escape_string($hdrType) . "','"
        . loc_db_escape_string($attchmnt_desc) . "','"
        . loc_db_escape_string($file_name) . "',"
        . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";
    return execUpdtInsSQL($insSQL);
}

function deleteInvstTransDoc($pkeyID, $docTrnsNum = "")
{
    $insSQL = "DELETE FROM pay.pay_trans_attchmnts WHERE attchmnt_id = " . $pkeyID;
    $affctd1 = execUpdtInsSQL($insSQL, "Trns. No:" . $docTrnsNum);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Attached Document(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function uploadDaInvstTransDoc($attchmntID, &$nwImgLoc, &$errMsg)
{
    global $tmpDest;
    global $ftp_base_db_fldr;
    global $usrID;
    global $fldrPrfx;
    global $smplTokenWord1;

    $msg = "";
    $allowedExts = array(
        'png', 'jpg', 'gif', 'jpeg', 'bmp', 'pdf', 'xls', 'xlsx',
        'doc', 'docx', 'ppt', 'pptx', 'txt', 'csv'
    );

    if (isset($_FILES["daInvstTransAttchmnt"])) {
        $flnm = $_FILES["daInvstTransAttchmnt"]["name"];
        $temp = explode(".", $flnm);
        $extension = end($temp);
        if ($_FILES["daInvstTransAttchmnt"]["error"] > 0) {
            $msg .= "Return Code: " . $_FILES["daInvstTransAttchmnt"]["error"] . "<br>";
        } else {
            $msg .= "Uploaded File: " . $_FILES["daInvstTransAttchmnt"]["name"] . "<br>";
            $msg .= "Type: " . $_FILES["daInvstTransAttchmnt"]["type"] . "<br>";
            $msg .= "Size: " . round(($_FILES["daInvstTransAttchmnt"]["size"]) / (1024 * 1024), 2) . " MB<br>";
            //$msg .= "Temp file: " . $_FILES["daInvstTransAttchmnt"]["tmp_name"] . "<br>";
            if ((($_FILES["daInvstTransAttchmnt"]["type"] == "image/gif") || ($_FILES["daInvstTransAttchmnt"]["type"] == "image/jpeg") || ($_FILES["daInvstTransAttchmnt"]["type"] == "image/jpg") || ($_FILES["daInvstTransAttchmnt"]["type"] == "image/pjpeg") || ($_FILES["daInvstTransAttchmnt"]["type"] == "image/x-png") ||
                ($_FILES["daInvstTransAttchmnt"]["type"] == "image/png") || in_array($extension, $allowedExts)) && ($_FILES["daInvstTransAttchmnt"]["size"] <
                10000000)) {
                $nwFileName = encrypt1($attchmntID . "." . $extension, $smplTokenWord1) . "." . $extension;
                $img_src = $fldrPrfx . $tmpDest . "$nwFileName";
                move_uploaded_file($_FILES["daInvstTransAttchmnt"]["tmp_name"], $img_src);
                $ftp_src = $ftp_base_db_fldr . "/PayDocs/$attchmntID" . "." . $extension;
                if (file_exists($img_src)) {
                    copy("$img_src", "$ftp_src");
                    $dateStr = getDB_Date_time();
                    $updtSQL = "UPDATE pay.pay_trans_attchmnts
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
                $msg .= "Invalid file!<br/>File Size must be below 10MB and<br/>File Type must be in the ff:<br/>" . implode(
                    ", ",
                    $allowedExts
                );
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
//LOAN, PAYMENT, INVESTMENT TYPES
function get_PayTransTyps($searchWord, $searchIn, $offset, $limit_size, $orgID)
{
    $strSql = "";
    $whrcls = "";
    $unpstdCls = "";
    if ($searchIn == "Name") {
        $whrcls = " and (a.item_type_name ilike '" . loc_db_escape_string($searchWord) .
            "' or a.item_type_desc ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT item_type_id, item_type_name, item_type_desc, item_type, 
        a.pay_itm_set_id, pay.get_itm_st_name(a.pay_itm_set_id),
        a.main_amnt_itm_id, org.get_payitm_nm(a.main_amnt_itm_id),
        a.investment_period, a.period_type, 
        a.cash_accnt_id, accb.get_accnt_num(a.cash_accnt_id) || '.' || accb.get_accnt_name(a.cash_accnt_id),
        a.asset_accnt_id, accb.get_accnt_num(a.asset_accnt_id) || '.' || accb.get_accnt_name(a.asset_accnt_id),
        a.rcvbl_accnt_id, accb.get_accnt_num(a.rcvbl_accnt_id) || '.' || accb.get_accnt_name(a.rcvbl_accnt_id),
        a.lblty_accnt_id, accb.get_accnt_num(a.lblty_accnt_id) || '.' || accb.get_accnt_name(a.lblty_accnt_id),
        a.rvnu_accnt_id, accb.get_accnt_num(a.rvnu_accnt_id) || '.' || accb.get_accnt_name(a.rvnu_accnt_id)
        FROM pay.loan_pymnt_invstmnt_typs a 
        WHERE((a.org_id = " . $orgID . ")" . $whrcls . $unpstdCls .
        ") ORDER BY item_type_id DESC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_PayTransTyps($searchWord, $searchIn, $orgID)
{
    $strSql = "";
    $whrcls = "";
    $unpstdCls = "";
    if ($searchIn == "Name") {
        $whrcls = " and (a.item_type_name ilike '" . loc_db_escape_string($searchWord) .
            "' or a.item_type_desc ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT count(1) FROM pay.loan_pymnt_invstmnt_typs a 
        WHERE((a.org_id = " . $orgID . ")" . $whrcls . $unpstdCls .
        ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_One_PayTransTyps($hdrID)
{
    $strSql = "SELECT item_type_id, item_type_name, item_type_desc, item_type, 
        a.pay_itm_set_id, pay.get_itm_st_name(a.pay_itm_set_id),
        a.main_amnt_itm_id, org.get_payitm_nm(a.main_amnt_itm_id),
        a.investment_period, a.period_type, 
        a.cash_accnt_id, accb.get_accnt_num(a.cash_accnt_id) || '.' || accb.get_accnt_name(a.cash_accnt_id),
        a.asset_accnt_id, accb.get_accnt_num(a.asset_accnt_id) || '.' || accb.get_accnt_name(a.asset_accnt_id),
        a.rcvbl_accnt_id, accb.get_accnt_num(a.rcvbl_accnt_id) || '.' || accb.get_accnt_name(a.rcvbl_accnt_id),
        a.lblty_accnt_id, accb.get_accnt_num(a.lblty_accnt_id) || '.' || accb.get_accnt_name(a.lblty_accnt_id),
        a.rvnu_accnt_id, accb.get_accnt_num(a.rvnu_accnt_id) || '.' || accb.get_accnt_name(a.rvnu_accnt_id),
        is_enabled, perdic_deduc_frmlr, INTRST_RATE,intrst_period_type, REPAY_PERIOD, repay_period_type, 
        net_loan_amount_sql, max_loan_amount_sql, enforce_max_amnt, min_loan_amount_sql, lnkd_loan_type_id,
        lnkd_loan_mn_itm_id, org.get_payitm_nm(a.lnkd_loan_mn_itm_id) 
        FROM pay.loan_pymnt_invstmnt_typs a " .
        "WHERE((a.item_type_id = " . $hdrID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getNewAccbTransTypsID()
{
    $strSql = "select nextval('pay.loan_pymnt_invstmnt_typs_item_type_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function createTransTyps(
    $orgid,
    $itmTypName,
    $itmTypDesc,
    $itmTyp,
    $itmSetID,
    $mainItmID,
    $cashAcntID,
    $assetAccntID,
    $rcvblAccntID,
    $lbltyAccntID,
    $rvnuAccntID,
    $investPeriod,
    $periodType,
    $enbld,
    $deducFrmlr,
    $intrstRate,
    $intrstPrdTyp,
    $repayPrd,
    $repayPrdTyp,
    $netAmntFrmlr,
    $maxAmntFrmlr,
    $enfrcMaxAmt,
    $lnkdPayTransTypsID,
    $minAmntFrmlr,
    $lnkdMnBalsItmID
) {
    global $usrID;
    $insSQL = "INSERT INTO pay.loan_pymnt_invstmnt_typs(item_type_name, item_type_desc, item_type, pay_itm_set_id,
                                         main_amnt_itm_id, cash_accnt_id, asset_accnt_id, rcvbl_accnt_id,
                                         lblty_accnt_id, rvnu_accnt_id, investment_period, period_type, org_id,
                                         created_by, creation_date, last_update_by, last_update_date, is_enabled, 
                                         perdic_deduc_frmlr, INTRST_RATE,intrst_period_type, REPAY_PERIOD, repay_period_type,
                                         net_loan_amount_sql, max_loan_amount_sql, enforce_max_amnt, 
                                         lnkd_loan_type_id, min_loan_amount_sql, lnkd_loan_mn_itm_id) " .
        "VALUES ('" . loc_db_escape_string($itmTypName) .
        "','" . loc_db_escape_string($itmTypDesc) .
        "','" . loc_db_escape_string($itmTyp) .
        "', " . $itmSetID . ", " . $mainItmID . ", " . $cashAcntID . ", " . $assetAccntID .
        ", " . $rcvblAccntID . ", " . $lbltyAccntID . ", " . $rvnuAccntID .
        ", " . $investPeriod . ",'" . loc_db_escape_string($periodType) .
        "', " . $orgid . ", " . $usrID . ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'),'" . cnvrtBoolToBitStr($enbld) .
        "','" . loc_db_escape_string($deducFrmlr) .
        "', " . $intrstRate . ",'" . loc_db_escape_string($intrstPrdTyp) .
        "', " . $repayPrd . ",'" . loc_db_escape_string($repayPrdTyp) .
        "','" . loc_db_escape_string($netAmntFrmlr) .
        "','" . loc_db_escape_string($maxAmntFrmlr) .
        "','" . cnvrtBoolToBitStr($enfrcMaxAmt) .
        "', " . $lnkdPayTransTypsID . ",'" . loc_db_escape_string($minAmntFrmlr) .
        "', " . $lnkdMnBalsItmID . ")";
    return execUpdtInsSQL($insSQL);
}

function updtTransTyps(
    $hdrID,
    $itmTypName,
    $itmTypDesc,
    $itmTyp,
    $itmSetID,
    $mainItmID,
    $cashAcntID,
    $assetAccntID,
    $rcvblAccntID,
    $lbltyAccntID,
    $rvnuAccntID,
    $investPeriod,
    $periodType,
    $enbld,
    $deducFrmlr,
    $intrstRate,
    $intrstPrdTyp,
    $repayPrd,
    $repayPrdTyp,
    $netAmntFrmlr,
    $maxAmntFrmlr,
    $enfrcMaxAmt,
    $lnkdPayTransTypsID,
    $minAmntFrmlr,
    $lnkdMnBalsItmID
) {
    global $usrID;
    $insSQL = "UPDATE pay.loan_pymnt_invstmnt_typs 
       SET item_type_name='" . $itmTypName .
        "', item_type_desc='" . loc_db_escape_string($itmTypDesc) .
        "', item_type='" . loc_db_escape_string($itmTyp) .
        "', investment_period=" . $investPeriod .
        ", period_type='" . loc_db_escape_string($periodType) .
        "', pay_itm_set_id=" . $itmSetID .
        ",is_enabled='" . cnvrtBoolToBitStr($enbld) .
        "', main_amnt_itm_id=" . $mainItmID .
        ", cash_accnt_id=" . $cashAcntID .
        ", asset_accnt_id=" . $assetAccntID .
        ", rcvbl_accnt_id=" . $rcvblAccntID .
        ", lblty_accnt_id=" . $lbltyAccntID .
        ", rvnu_accnt_id=" . $rvnuAccntID .
        ", perdic_deduc_frmlr='" . loc_db_escape_string($deducFrmlr) .
        "', INTRST_RATE=" . $intrstRate .
        ", intrst_period_type='" . loc_db_escape_string($intrstPrdTyp) .
        "', REPAY_PERIOD=" . $repayPrd .
        ", repay_period_type='" . loc_db_escape_string($repayPrdTyp) .
        "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS')" .
        ", net_loan_amount_sql='" . loc_db_escape_string($netAmntFrmlr) .
        "', max_loan_amount_sql='" . loc_db_escape_string($maxAmntFrmlr) .
        "', enforce_max_amnt='" . loc_db_escape_string($enfrcMaxAmt) .
        "', min_loan_amount_sql='" . loc_db_escape_string($minAmntFrmlr) .
        "', lnkd_loan_type_id=" . $lnkdPayTransTypsID .
        ", lnkd_loan_mn_itm_id=" . $lnkdMnBalsItmID .
        " WHERE item_type_id = " . $hdrID;
    return execUpdtInsSQL($insSQL);
}

function deleteTransTyps($valLnid, $docNum)
{
    $strSql = "SELECT count(1) FROM pay.pay_loan_pymnt_rqsts a WHERE(a.item_type_id = " . $valLnid .
        ")";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    $strSql2 = "SELECT count(1) FROM pay.pay_fund_management a WHERE(a.item_type_id = " . $valLnid .
        ")";
    $result2 = executeSQLNoParams($strSql2);
    $trnsCnt2 = 0;
    while ($row = loc_db_fetch_array($result2)) {
        $trnsCnt2 = (float) $row[0];
    }
    if (($trnsCnt1 + $trnsCnt2) > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete a type used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM pay.loan_pymnt_invstmnt_typs WHERE item_type_id = " . $valLnid;
    $affctd1 = execUpdtInsSQL($delSQL, "Desc:" . $docNum);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Transaction Setup(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_One_PayTypClsfctns($typid)
{
    $strSql = "SELECT typ_clsfctn_id, clsfctn_name, clsfctn_desc, order_number,is_enabled,
       created_by, creation_date, last_update_by, last_update_date
  FROM pay.loan_pymnt_typ_clsfctn a WHERE(a.item_type_id = " . $typid . ") ORDER BY 4";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getNewPayTypClsfctnID()
{
    $strSql = "select nextval('pay.loan_pymnt_typ_clsfctn_typ_clsfctn_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function createPayTypClsfctn($typid, $clsfctnNm, $clsfctnDesc, $ordrNum, $isEnbld)
{
    global $usrID;
    $insSQL = "INSERT INTO pay.loan_pymnt_typ_clsfctn(item_type_id, clsfctn_name, clsfctn_desc, order_number,
                                       is_enabled, created_by, creation_date, last_update_by, last_update_date) " .
        "VALUES (" . $typid . ", '" . loc_db_escape_string($clsfctnNm) .
        "', '" . loc_db_escape_string($clsfctnDesc) .
        "', " . $ordrNum .
        ",'" . cnvrtBoolToBitStr($isEnbld) .
        "', " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'))";
    return execUpdtInsSQL($insSQL);
}

function updtPayTypClsfctn($hdrID, $typid, $clsfctnNm, $clsfctnDesc, $ordrNum, $isEnbld)
{
    global $usrID;
    $insSQL = "UPDATE pay.loan_pymnt_typ_clsfctn
                SET item_type_id=" . $typid . ",
                    clsfctn_name='" . loc_db_escape_string($clsfctnNm) . "',
                    clsfctn_desc='" . loc_db_escape_string($clsfctnDesc) . "',
                    is_enabled='" . cnvrtBoolToBitStr($isEnbld) . "',
                    order_number=" . $ordrNum . ",
                    last_update_by=" . $usrID . ",
                    last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS')
                WHERE typ_clsfctn_id = " . $hdrID;
    return execUpdtInsSQL($insSQL);
}

function deletePayTypClsfctn($valLnid, $docNum)
{
    $delSQL = "DELETE FROM pay.loan_pymnt_typ_clsfctn WHERE typ_clsfctn_id = " . $valLnid;
    $affctd1 = execUpdtInsSQL($delSQL, "Desc:" . $docNum);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Classification(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function loadTypClsfctnOptions($payTrnsRqstsItmTypID, $payTrnsRqstsType)
{
    $pssblItems = [];
    $i = 0;
    $lqlovNm = "Internal Pay Loan Classifications";
    if ($payTrnsRqstsType == "PAYMENT") {
        $lqlovNm = "Internal Pay Payment Classifications";
    } else if ($payTrnsRqstsType == "SETTLEMENT") {
        $lqlovNm = "Internal Pay Settlement Classifications";
    }
    //Semi-Month
    $brghtStr = "";
    $isDynmyc = true;
    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID($lqlovNm), $isDynmyc, $payTrnsRqstsItmTypID, "", "");
    while ($titleRow = loc_db_fetch_array($titleRslt)) {
        $pssblItems[$i] = $titleRow[0];
        $i++;
    }
    return join(";", $pssblItems);
}

function loadTypRqstsOptions($payTrnsRqstsItmTypID, $payTrnsRqstsPrsnID, &$firstPayTrnsRqstsID)
{
    global $orgID;
    $payTrnsRqstsDpndtItmTypID = (float) getGnrlRecNm("pay.loan_pymnt_invstmnt_typs", "item_type_id", "lnkd_loan_type_id", $payTrnsRqstsItmTypID);
    $payTrnsRqstsDpndtBalsItmID = (float) getGnrlRecNm("pay.loan_pymnt_invstmnt_typs", "item_type_id", "lnkd_loan_mn_itm_id", $payTrnsRqstsItmTypID);
    $titleRslt = get_UnsttldLoanRqsts("%", "Requestor", 0, 500, $orgID, $payTrnsRqstsPrsnID, $payTrnsRqstsDpndtItmTypID, $payTrnsRqstsDpndtBalsItmID, "LOAN");
    $pssblItems = [];
    $i = 0;
    while ($titleRow = loc_db_fetch_array($titleRslt)) {
        if ($i == 0) {
            $firstPayTrnsRqstsID = (float) $titleRow[0];
        }
        $pssblItems[$i] = $titleRow[0] . "|" . $titleRow[5] . "(ID:" . $titleRow[0] . " AMT:" . number_format((float)$titleRow[9], 2) . ")";
        $i++;
    }
    return join(";", $pssblItems);
}

//INCOME/EXPENSE VOUCHERS
function get_SmplVchrHdr($searchWord, $searchIn, $offset, $limit_size, $orgID, $shwUnpstdOnly, $smplVchrTrnsType)
{
    $strSql = "";
    $whrcls = "";
    $unpstdCls = "";
    if ($shwUnpstdOnly) {
        $unpstdCls = " AND (a.approval_status IN ('Not Finalized') " .
            "or accb.is_gl_batch_pstd(a.gl_batch_id)='0' )";
    }
    if ($searchIn == "Ref. Number") {
        $whrcls = " and (a.ref_doc_number ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Description") {
        $whrcls = " and (a.comments_desc ilike '" . loc_db_escape_string($searchWord) . "'"
            . "or a.smpl_vchr_type ilike '" . loc_db_escape_string($searchWord) . "')";
    } else {
        $whrcls = " and (a.ref_doc_number ilike '" . loc_db_escape_string($searchWord) .
            "' or a.comments_desc ilike '" . loc_db_escape_string($searchWord) .
            "' or a.smpl_vchr_type ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $whrcls .= " and (a.smpl_vchr_type ilike '" . loc_db_escape_string($smplVchrTrnsType) . "')";
    $strSql = "SELECT a.smpl_vchr_hdr_id,a.smpl_vchr_number,a.smpl_vchr_type, a.comments_desc
,a.ref_doc_number, a.supplier_id, scm.get_cstmr_splr_name(a.supplier_id) payee, a.invc_curr_id,
gst.get_pssbl_val(a.invc_curr_id) crncy, round(a.invoice_amount,2) invc_amnt, a.func_curr_rate,
to_char(to_timestamp(a.smpl_vchr_date,'YYYY-MM-DD'),'DD-Mon-YYYY') trans_date,
a.approval_status, a.gl_batch_id, accb.get_gl_batch_name(a.gl_batch_id), accb.is_gl_batch_pstd(a.gl_batch_id),
a.supplier_site_id, scm.get_cstmr_splr_site_name(a.supplier_site_id)
        FROM accb.accb_smpl_vchr_hdr a 
    WHERE((a.org_id = " . $orgID . ")" . $whrcls . $unpstdCls .
        ") ORDER BY smpl_vchr_hdr_id DESC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_SmplVchr($searchWord, $searchIn, $orgID, $shwUnpstdOnly, $smplVchrTrnsType)
{
    $strSql = "";
    $whrcls = "";
    $unpstdCls = "";
    if ($shwUnpstdOnly) {
        $unpstdCls = " AND (a.approval_status IN ('Not Finalized') " .
            "or accb.is_gl_batch_pstd(a.gl_batch_id)='0' )";
    }
    if ($searchIn == "Ref. Number") {
        $whrcls = " and (a.ref_doc_number ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Description") {
        $whrcls = " and (a.comments_desc ilike '" . loc_db_escape_string($searchWord) . "'"
            . "or a.smpl_vchr_type ilike '" . loc_db_escape_string($searchWord) . "')";
    } else {
        $whrcls = " and (a.ref_doc_number ilike '" . loc_db_escape_string($searchWord) .
            "' or a.comments_desc ilike '" . loc_db_escape_string($searchWord) .
            "' or a.smpl_vchr_type ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $whrcls .= " and (a.smpl_vchr_type ilike '" . loc_db_escape_string($smplVchrTrnsType) . "')";
    $strSql = "SELECT count(1)
        FROM accb.accb_smpl_vchr_hdr a
        WHERE((a.org_id = " . $orgID . ")" . $whrcls . $unpstdCls .
        ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_One_SmplVchrHdr($hdrID)
{
    $strSql = "SELECT a.smpl_vchr_hdr_id, a.smpl_vchr_number, a.smpl_vchr_type, a.comments_desc
    ,a.ref_doc_number, a.supplier_id, scm.get_cstmr_splr_name(a.supplier_id) payee, a.invc_curr_id,
    gst.get_pssbl_val(a.invc_curr_id) crncy, round(a.invoice_amount,2) invc_amnt, a.func_curr_rate,
    to_char(to_timestamp(a.smpl_vchr_date,'YYYY-MM-DD'),'DD-Mon-YYYY') trans_date,
    a.approval_status, a.gl_batch_id, accb.get_gl_batch_name(a.gl_batch_id), 
    accb.is_gl_batch_pstd(a.gl_batch_id), a.supplier_site_id, 
    scm.get_cstmr_splr_site_name(a.supplier_site_id), a.mltpl_lines_allwd, a.smpl_vchr_det_tmplt_id
            FROM accb.accb_smpl_vchr_hdr a  
        WHERE((a.smpl_vchr_hdr_id = " . $hdrID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_SmplVchrLines($hdrID)
{
    $strSql = "SELECT a.smpl_vchr_det_id,a.smpl_vchr_det_tmplt_id,a.smpl_vchr_det_desc, 
    round(a.smpl_vchr_det_amnt,2) line_amnt,
    to_char(to_timestamp(a.smpl_vchr_line_date,'YYYY-MM-DD'),'DD-Mon-YYYY') trans_date,
    accb.get_template_name(a.smpl_vchr_det_tmplt_id)
      FROM accb.accb_smpl_vchr_det a, accb.accb_trnsctn_templates_hdr b  
        WHERE((a.smpl_vchr_det_tmplt_id=b.template_id and a.smpl_vchr_hdr_id = " . $hdrID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getNewSmplVchrHdrID()
{
    $strSql = "select nextval('accb.accb_smpl_vchr_hdr_smpl_vchr_hdr_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function createSmplVchrHdr(
    $hdrID,
    $orgid,
    $payeeID,
    $payeeSiteID,
    $transNo,
    $refNum,
    $narration,
    $trnsTyp,
    $entrdCrncyID,
    $invcAmnt,
    $exchngRate,
    $transDate,
    $mainTmpltID,
    $allwLines
) {
    global $usrID;
    if ($transDate != "") {
        $transDate = cnvrtDMYToYMD($transDate);
    }
    $insSQL = "
    INSERT INTO accb.accb_smpl_vchr_hdr (
            smpl_vchr_hdr_id, smpl_vchr_date, smpl_vchr_number, smpl_vchr_type,
            comments_desc, ref_doc_number, supplier_id, supplier_site_id,
            invc_curr_id, func_curr_rate, invoice_amount, approval_status,
            org_id, gl_batch_id, created_by, creation_date, last_update_by, last_update_date,
            smpl_vchr_det_tmplt_id, mltpl_lines_allwd
        )
    VALUES (" . $hdrID . ", '" . loc_db_escape_string($transDate) .
        "','" . loc_db_escape_string($transNo) .
        "','" . loc_db_escape_string($trnsTyp) .
        "','" . loc_db_escape_string($narration) .
        "', '" . loc_db_escape_string($refNum) .
        "'," . $payeeID . "," . $payeeSiteID . "," . $entrdCrncyID .
        "," . $exchngRate . "," . $invcAmnt . ",'Not Finalized'," . $orgid . ",-1, " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $mainTmpltID .
        ", '" . cnvrtBoolToBitStr($allwLines) .
        "')";
    return execUpdtInsSQL($insSQL);
}

function updtSmplVchrHdr(
    $hdrID,
    $orgid,
    $payeeID,
    $payeeSiteID,
    $transNo,
    $refNum,
    $narration,
    $trnsTyp,
    $entrdCrncyID,
    $invcAmnt,
    $exchngRate,
    $transDate,
    $mainTmpltID,
    $allwLines
) {
    global $usrID;
    if ($transDate != "") {
        $transDate = cnvrtDMYToYMD($transDate);
    }
    $insSQL = "
    UPDATE accb.accb_smpl_vchr_hdr
      SET smpl_vchr_date = '" . loc_db_escape_string($transDate) . "',
        smpl_vchr_number = '" . loc_db_escape_string($transNo) . "',
        smpl_vchr_type = '" . loc_db_escape_string($trnsTyp) . "',
        comments_desc = '" . loc_db_escape_string($narration) . "',
        ref_doc_number = '" . loc_db_escape_string($refNum) . "',
        supplier_id = " . $payeeID . ",
        supplier_site_id = " . $payeeSiteID . ",
        invc_curr_id = " . $entrdCrncyID . ",
        func_curr_rate = " . $exchngRate . ",
        invoice_amount = " . $invcAmnt . ",
        approval_status = 'Not Finalized',
        last_update_by = " . $usrID . ",
        last_update_date = to_char(now(), 'YYYY-MM-DD HH24:MI:SS'),
        smpl_vchr_det_tmplt_id = " . $mainTmpltID . ",
        mltpl_lines_allwd = '" . cnvrtBoolToBitStr($allwLines) . "'
      WHERE smpl_vchr_hdr_id = " . $hdrID;
    return execUpdtInsSQL($insSQL);
}

function updtSmplVchrApprvl($docid, $apprvlSts, $nxtApprvl)
{
    global $usrID;
    $extrCls = "";
    if ($apprvlSts == "Cancelled") {
        $extrCls = ", invoice_amount=0";
    }
    $updtSQL = "UPDATE accb.accb_smpl_vchr_hdr SET " .
        "approval_status='" . loc_db_escape_string($apprvlSts) .
        "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS')"
        . "" . $extrCls . " WHERE (smpl_vchr_hdr_id = " . $docid . ")";
    return execUpdtInsSQL($updtSQL);
}

function updtSmplVchrTtlAmnt($docid)
{
    global $usrID;
    $updtSQL = "UPDATE accb.accb_smpl_vchr_hdr SET " .
        "invoice_amount=(select sum(smpl_vchr_det_amnt) from accb.accb_smpl_vchr_det where smpl_vchr_hdr_id = " . $docid .
        "), last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS') WHERE (smpl_vchr_hdr_id = " .
        $docid . ")";
    return execUpdtInsSQL($updtSQL);
}

function updtSmplVchrGLBatch($docid, $glBatchID)
{
    global $usrID;
    $updtSQL = "UPDATE accb.accb_smpl_vchr_hdr SET " .
        "gl_batch_id=" . $glBatchID .
        ", last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS') WHERE (smpl_vchr_hdr_id = " .
        $docid . ")";
    return execUpdtInsSQL($updtSQL);
}

function updateSmplVchrVoid($batchid, $rvrsalReason, $nwStatus, $nextAction, $glBatchID)
{
    global $usrID;
    $updtSQL = "UPDATE accb.accb_smpl_vchr_hdr " .
        "SET comments_desc='" . loc_db_escape_string($rvrsalReason) .
        "', approval_status='" . loc_db_escape_string($nwStatus) .
        "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS')" .
        ", gl_batch_id=" . $glBatchID .
        ", invoice_amount=0 WHERE smpl_vchr_hdr_id = " . $batchid;
    return execUpdtInsSQL($updtSQL);
}

function deleteSmplVchr($valLnid, $docNum)
{
    $strSql = "SELECT count(1) FROM accb.accb_smpl_vchr_hdr a WHERE(a.smpl_vchr_hdr_id = " . $valLnid .
        " and a.approval_status IN ('Validated', 'Approved', 'Cancelled','Initiated','Reviewed','Finalized'))";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if (($trnsCnt1) > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete a Finalized Transaction!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL2 = "DELETE FROM pay.pay_trans_attchmnts WHERE src_pkey_id = " . $valLnid . " and src_trans_type ilike '%VOUCHER%'";
    $affctd2 = execUpdtInsSQL($delSQL2, "Desc:" . $docNum);
    $delSQL21 = "DELETE FROM accb.accb_smpl_vchr_det WHERE smpl_vchr_hdr_id = " . $valLnid;
    $affctd21 = execUpdtInsSQL($delSQL2, "Desc:" . $docNum);
    $delSQL = "DELETE FROM accb.accb_smpl_vchr_hdr WHERE smpl_vchr_hdr_id = " . $valLnid;
    $affctd1 = execUpdtInsSQL($delSQL, "Desc:" . $docNum);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Transaction(s)!";
        $dsply .= "<br/>Deleted $affctd2 Transaction Attachment(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getNewSmplVchrLineID()
{
    $strSql = "select nextval('accb.accb_smpl_vchr_det_smpl_vchr_det_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function createSmplVchrLine($lineID, $hdrID, $tmpltID, $narration, $invcAmnt, $transDate)
{
    global $usrID;
    if ($transDate != "") {
        $transDate = cnvrtDMYToYMD($transDate);
    }
    $insSQL = "
    INSERT INTO accb.accb_smpl_vchr_det (
        smpl_vchr_det_id, smpl_vchr_hdr_id, smpl_vchr_line_date,
        smpl_vchr_det_desc, smpl_vchr_det_tmplt_id, smpl_vchr_det_amnt, 
        created_by, creation_date, last_update_by, last_update_date
        )
    VALUES (" . $lineID . ", " . $hdrID . ", '" . loc_db_escape_string($transDate) .
        "','" . loc_db_escape_string($narration) .
        "'," . $tmpltID . "," . $invcAmnt . ", " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'))";
    return execUpdtInsSQL($insSQL);
}

function updtSmplVchrLine($lineID, $tmpltID, $narration, $invcAmnt, $transDate)
{
    global $usrID;
    if ($transDate != "") {
        $transDate = cnvrtDMYToYMD($transDate);
    }
    $insSQL = "
    UPDATE
        accb.accb_smpl_vchr_det
    SET smpl_vchr_line_date = '" . loc_db_escape_string($transDate) . "',
        smpl_vchr_det_desc = '" . loc_db_escape_string($narration) . "',
        smpl_vchr_det_tmplt_id = " . $tmpltID . ",
        smpl_vchr_det_amnt = " . $invcAmnt . ",
        last_update_by = " . $usrID . ",
        last_update_date = to_char(now(), 'YYYY-MM-DD HH24:MI:SS')
    WHERE smpl_vchr_det_id = " . $lineID;
    return execUpdtInsSQL($insSQL);
}

function deleteSmplVchrLine($valLnid, $docNum)
{
    $strSql = "SELECT count(1) FROM accb.accb_smpl_vchr_hdr a 
    WHERE(a.smpl_vchr_hdr_id =(select b.smpl_vchr_hdr_id FROM accb.accb_smpl_vchr_det b WHERE b.smpl_vchr_det_id= " . $valLnid .
        ") and a.approval_status IN ('Validated', 'Approved', 'Cancelled','Initiated','Reviewed','Finalized'))";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if (($trnsCnt1) > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete a Finalized Transaction!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM accb.accb_smpl_vchr_det WHERE smpl_vchr_det_id = " . $valLnid;
    $affctd1 = execUpdtInsSQL($delSQL, "Desc:" . $docNum);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Transaction Line(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function createSmplVchrAccntng(
    $sbmtdSmplVchrID,
    $smplVchrTrnsType,
    $smplVchrDesc,
    $smplVchrRefNum,
    $smplVchrInvcCur,
    $smplVchrTransDte,
    $smplVchrExchngRate,
    &$afftctd1,
    &$errMsg
) {
    global $fnccurid;
    global $orgID;
    global $usrID;
    //Save Transaction Voucher Double Entry Lines
    //Loop Transaction Lines
    //1. Debit Investment Asset Accnt and Credit Cash/Bank (Purchase Amount)
    //2. Debit Interest Receivable and Credit Defferred Interest Liability (Maturity Amount - Purchase Amount)
    //For Investment Redisounts or Maturity
    //1. Debit Deffered Interest Lblty and Credit Interest Revenue Accnt(Maturity Amount - Purchase Amount)
    //2. Debit Cash/Bank and Credit Interest Receivable (Maturity Amount - Purchase Amount)
    //3. Debit Cash/Bank and Credit Investment Asset Accnt (Purchase Amount)
    try {
        $curLovID = getLovID("Currencies");
        $lnSmmryLnID = $sbmtdSmplVchrID;
        $ln_FuncExchgRate = $smplVchrExchngRate;
        $lnRefDoc = $smplVchrRefNum;
        $lineDesc = $smplVchrDesc;
        $lineCurNm = $smplVchrInvcCur;
        $lineCurID = getPssblValID($lineCurNm, $curLovID);
        $lineTransDate = $smplVchrTransDte;
        $dte = date('ymd');
        $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
        if ($usrTrnsCode == "") {
            $usrTrnsCode = "XX";
        }
        //$userAccntName = getGnrlRecNm("sec.sec_users", "user_id", "user_name", $usrID);
        $docTypPrfx = ($smplVchrTrnsType === "Expense Voucher") ? "EXP" : "INC";
        $gnrtdTrnsNo1 = $docTypPrfx . "-" . $usrTrnsCode . "-" . $dte . "-";
        $gnrtdTrnsNo = $gnrtdTrnsNo1 . str_pad((getRecCount_LstNum("accb.accb_trnsctn_batches", "batch_name", "batch_id", $gnrtdTrnsNo1 . "%") + 1), 3, '0', STR_PAD_LEFT);
        createBatch(
            $orgID,
            $gnrtdTrnsNo,
            $smplVchrDesc,
            $smplVchrTrnsType,
            "VALID",
            -1,
            "0",
            -1,
            "",
            $lineCurID,
            $smplVchrTransDte
        );
        $payInvstTransGLBatchID = getBatchID($gnrtdTrnsNo, $orgID);

        $vchrRslt = get_One_SmplVchrLines($sbmtdSmplVchrID);
        while ($row = loc_db_fetch_array($vchrRslt)) {
            $ln_TmpltID = (float) $row[1];
            $ln_Amount = (float) $row[3];
            $ln_Desc = (float) $row[2];
            $ln_Date = (float) $row[4] . " 12:00:00";
            $accntmpltRslt = get_One_Tmplt_Trns($ln_TmpltID);
            while ($row1 = loc_db_fetch_array($accntmpltRslt)) {
                $ln_IncrsDcrs1 = $row1[1];
                $ln_AccountID1 = (float) $row1[5];
                $entrdAmt = $ln_Amount;
                if (!($lnSmmryLnID > 0 && $ln_AccountID1 > 0 && $payInvstTransGLBatchID > 0 && $entrdAmt > 0)) {
                    continue;
                }
                $drCrdt1 = dbtOrCrdtAccnt($ln_AccountID1, substr($ln_IncrsDcrs1, 0, 1)) == "Debit" ? "D" : "C";
                $lineDbtAmt1 = ($drCrdt1 == "D") ? $entrdAmt : 0;
                $lineCrdtAmt1 = ($drCrdt1 == "C") ? $entrdAmt : 0;
                $funcExchRate = $ln_FuncExchgRate;
                if (($funcExchRate == 0 || $funcExchRate == 1) && $lineCurID != $fnccurid) {
                    $funcExchRate = round(get_LtstExchRate($lineCurID, $fnccurid, $lineTransDate), 4);
                }
                //$funcCurrAmnt = $funcExchRate * $entrdAmt;
                $netAmnt1 = drCrAccMltplr($ln_AccountID1, $drCrdt1) * $entrdAmt;
                $dbtOrCrdt1 = substr(strtoupper($drCrdt1), 0, 1);
                $accntCurrID1 = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $ln_AccountID1);
                $acntExchRate1 = round(get_LtstExchRate($lineCurID, $accntCurrID1, $lineTransDate), 4);
                $acntAmnt1 = $acntExchRate1 * $entrdAmt;
                $srcTrnsID1 = -1;
                $srcTrnsID2 = -1;
                //Create First Leg of Summary Transaction as Journal Entry
                //Insert
                $lnTrnsLnID1 = getNewTrnsID();
                $afftctd1 += createTransaction($lnTrnsLnID1, $ln_AccountID1, $lineDesc, $lineDbtAmt1, $lineTransDate, $lineCurID, $payInvstTransGLBatchID, $lineCrdtAmt1, $netAmnt1, $entrdAmt, $lineCurID, $acntAmnt1, $accntCurrID1, $funcExchRate, $acntExchRate1, $dbtOrCrdt1, $lnRefDoc, $srcTrnsID1, $srcTrnsID2, -1, "Internal Pay Fund Management", $lnSmmryLnID);
            }
        }
        //Final Approval
        updtSmplVchrGLBatch($sbmtdSmplVchrID, $payInvstTransGLBatchID);
        updtSmplVchrApprvl($sbmtdSmplVchrID, "Finalized", "Cancel");
        return true;
    } catch (Exception $e) {
        $errMsg .= 'Caught exception: VALIDATE ACCNTS ' . $e->getMessage();
        return false;
    }
}
