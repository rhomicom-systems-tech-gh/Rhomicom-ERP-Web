<?php
$rhoFutureDateDMYHMS = "31-DEC-4000 23:59:59";

function getStrPlural($inputStr)
{
    $rturnStr = $inputStr . "s";
    if (strtolower(substr($inputStr, -1)) == "s" || strtolower(substr($inputStr, -2)) == "sh" || strtolower(substr($inputStr, -2)) == "ch" || strtolower(substr($inputStr, -1)) == "x" || strtolower(substr($inputStr, -1)) == "z") {
        $rturnStr = $inputStr . "es";
    }
    return $rturnStr;
}

function differenceInHours($startdate, $enddate)
{
    $starttimestamp = strtotime($startdate);
    $endtimestamp = strtotime($enddate);
    $difference = abs($endtimestamp - $starttimestamp) / 3600;
    return $difference;
}

function get_Last_Date_Mnth($inpt_date_DMY)
{
    $strSql = "SELECT public.last_date_of_month('" . $inpt_date_DMY . "');";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return "";
}

function rhoReplaceBtn($str, $needle_start, $needle_end, $replacement, $rplcNeedlesToo = false)
{
    $pos = strpos($str, $needle_start);
    $start = $pos === false ? 0 : $pos + strlen($needle_start);
    $pos1 = strpos($str, $needle_end, $start);
    $end = $pos1 === false ? strlen($str) : $pos1;
    if ($pos === false) {
        return $str;
    } else {
        $nwstr = substr_replace($str, $replacement, $start, $end - $start);
        if ($rplcNeedlesToo) {
            $nwstr = str_replace($needle_end, "", str_replace($needle_start, "", $nwstr));
        }
        return $nwstr;
    }
}

function rhoHex2Rgba($color, $opacity = false)
{
    $default = 'rgb(0,0,0)';
    //Return default if no color provided
    if (empty($color)) {
        return $default;
    }
    //Sanitize $color if "#" is provided 
    if ($color[0] == '#') {
        $color = substr($color, 1);
    }

    //Check if color has 6 or 3 characters and get values
    if (strlen($color) == 6) {
        $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
    } elseif (strlen($color) == 3) {
        $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
    } else {
        return $default;
    }

    //Convert hexadec to rgb
    $rgb = array_map('hexdec', $hex);

    //Check if opacity is set(rgba or rgb)
    if ($opacity) {
        if (abs($opacity) > 1) {
            $opacity = 1.0;
        }
        $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
    } else {
        $output = 'rgb(' . implode(",", $rgb) . ')';
    }
    //Return rgb(a) color string
    return $output;
}
/**
 * Undocumented function
 *
 * @param [type] $url
 * @param [type] $data
 * @param string $cntentTyp
 * @param string $method
 * @return void
 * Example Usage
    $rslt = rhofetchFromAPI(
        'https://jsonplaceholder.typicode.com/todos/1',
        array('key1' => 'value1')
    );
    print_r($rslt);
    exit();
 */
function rhofetchFromAPI($url, $data, $cntentTyp = "application/x-www-form-urlencoded", $method = 'GET')
{
    //$url = 'https://jsonplaceholder.typicode.com/todos/1';
    //$data = array('key1' => 'value1', 'key2' => 'value2');

    //application/json  application/x-www-form-urlencoded\r\n
    $options = array(
        'http' => array(
            'header'  => "Content-type: " . $cntentTyp,
            'method'  => $method,
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    return $result;
}

function rhoPOSTToAPI($url, $data)
{
    // Create a new cURL resource
    $ch = curl_init($url);
    $payload = json_encode($data);

    // Attach encoded JSON string to the POST fields
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // Set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Return response instead of outputting
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

    // Execute the POST request
    $result = curl_exec($ch);
    //print_r(curl_getinfo($ch));
    //$waitCntr = 1 / 0;
    // Close cURL resource
    curl_close($ch);
    unset($ch);
    return  $result;
}

function executeSQLNoParams($selSQL, $extrMsg = '')
{
    $conn = getConn();
    $result = loc_db_query($conn, $selSQL);
    if (!$result) {
        $txt = "@" . date("d-M-Y H:i:s") . PHP_EOL . "An error occurred. <br/> " . loc_db_result_error($result) . PHP_EOL . $selSQL;
        //echo $txt;
        logSessionErrs($txt);
    } else if ($extrMsg != '') {
        $txt = $extrMsg . "@" . date("d-M-Y H:i:s") . PHP_EOL . $selSQL;
        logSessionErrs($txt);
    }
    loc_db_close($conn);
    return $result;
}

function execUpdtInsSQL($inSQL, $extrMsg = '', $src = 0)
{
    $conn = getConn();
    $result = loc_db_query($conn, $inSQL);
    if (!$result) {
        $txt = "@" . date("d-M-Y H:i:s") . PHP_EOL . "An error occurred. <br/> " . loc_db_result_error($result) . PHP_EOL . $inSQL;
        //echo $txt;
        logSessionErrs($txt);
    } else if ($src <= 0) {
        /* $extrMsg != '' */
        $txt = $extrMsg . "@" . date("d-M-Y H:i:s") . PHP_EOL . $inSQL;
        logSessionErrs($txt);
    }
    loc_db_close($conn);
    if ($src == 0) {
        if (trim(strtoupper(substr($inSQL, 0, 11))) == 'DELETE FROM') {
            storeAdtTrailInfo($inSQL, 1, $extrMsg);
        } else if (trim(strtoupper(substr($inSQL, 0, 6))) == 'UPDATE') {
            storeAdtTrailInfo($inSQL, 0, $extrMsg);
        }
    }
    return loc_db_affected_rows($result);
}

function storeAdtTrailInfo($infoStmnt, $actntype, $extrMsg)
{
    global $ModuleName;
    global $ftp_base_db_fldr;
    global $usrID;
    global $lgn_num;
    global $logNxtLine;

    $ModuleAdtTbl = getGnrlRecNm('sec.sec_modules', 'module_id', 'audit_trail_tbl_name', getModuleID($ModuleName));

    $action_types = array("UPDATE STATEMENTS", "DELETE STATEMENTS");
    if ($ModuleAdtTbl == null || $ModuleAdtTbl == "") {
        return;
    }
    if (doesCrPlcTrckThisActn($action_types[$actntype]) == false) {
        return;
    }
    $dateStr = getDB_Date_time();
    $seqName = $ModuleAdtTbl . "_dflt_row_id_seq";
    $seqID = getNewAdtTrailID($seqName);
    if ($seqID > 0) {
        $sqlStr = "INSERT INTO " . $ModuleAdtTbl . " (" .
            "user_id, action_type, action_details, action_time, login_number, dflt_row_id) " .
            "VALUES (" . $usrID . ", '" . $action_types[$actntype] .
            "', '" . loc_db_escape_string($extrMsg) . "', '" . $dateStr . "', " . $lgn_num . ", " . $seqID . ")";
        $txt = loc_db_escape_string($extrMsg) . "@" . date("d-M-Y H:i:s") . PHP_EOL .
            loc_db_escape_string($infoStmnt);
        file_put_contents(
            $ftp_base_db_fldr . "/bin/log_files/adt_trail/$seqID" . "_" . $lgn_num . ".rho",
            $txt . $logNxtLine,
            FILE_APPEND | LOCK_EX
        );
        execUpdtInsSQL($sqlStr, '', 1);
    }
}

function getNewAdtTrailID($seqName)
{
    $strSql = "select nextval('$seqName')";
    $result = executeSQLNoParams($strSql);

    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function getOrgID($orgNm)
{
    $sqlStr = "select MIN(org_id) from org.org_details where lower(org_name)=lower('" . loc_db_escape_string($orgNm) . "')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getOrgLogo($pkID)
{
    $sqlStr = "select COALESCE(org_logo,'') from org.org_details WHERE (org_id=$pkID)";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return '';
}

function getGrpOrgID()
{
    $sqlStr = "select MIN(org_id) from org.org_details where parent_org_id<=0";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getOrgFuncCurID($orgid)
{

    $strSql = "select oprtnl_crncy_id from org.org_details where org_id = $orgid ";
    $result = executeSQLNoParams($strSql);

    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function dbtOrCrdtAccnt($accntid, $incrsDcrse)
{
    $accntType = getAccntType($accntid);
    $isContra = isAccntContra($accntid);
    if ($isContra == "0") {
        if (($accntType == "A" || $accntType == "EX") && $incrsDcrse == "I") {
            return "Debit";
        } else if (($accntType == "A" || $accntType == "EX") && $incrsDcrse == "D") {
            return "Credit";
        } else if (($accntType == "EQ" || $accntType == "R" || $accntType == "L") && $incrsDcrse == "I") {
            return "Credit";
        } else if (($accntType == "EQ" || $accntType == "R" || $accntType == "L") && $incrsDcrse == "D") {
            return "Debit";
        }
    } else {
        if (($accntType == "A" || $accntType == "EX") && $incrsDcrse == "I") {
            return "Credit";
        } else if (($accntType == "A" || $accntType == "EX") && $incrsDcrse == "D") {
            return "Debit";
        } else if (($accntType == "EQ" || $accntType == "R" || $accntType == "L") && $incrsDcrse == "I") {
            return "Debit";
        } else if (($accntType == "EQ" || $accntType == "R" || $accntType == "L") && $incrsDcrse == "D") {
            return "Credit";
        }
    }
    return "";
}

function dbtOrCrdtAccntMultiplier($accntid, $incrsDcrse)
{
    $accntType = getAccntType($accntid);
    $isContra = isAccntContra($accntid);
    if ($isContra == "0") {
        if (($accntType == "A" || $accntType == "EX") && $incrsDcrse == "I") {
            return 1;
        } else if (($accntType == "A" || $accntType == "EX") && $incrsDcrse == "D") {
            return -1;
        } else if (($accntType == "EQ" || $accntType == "R" || $accntType == "L") && $incrsDcrse == "I") {
            return 1;
        } else if (($accntType == "EQ" || $accntType == "R" || $accntType == "L") && $incrsDcrse == "D") {
            return -1;
        }
    } else {
        if (($accntType == "A" || $accntType == "EX") && $incrsDcrse == "I") {
            return -1;
        } else if (($accntType == "A" || $accntType == "EX") && $incrsDcrse == "D") {
            return 1;
        } else if (($accntType == "EQ" || $accntType == "R" || $accntType == "L") && $incrsDcrse == "I") {
            return -1;
        } else if (($accntType == "EQ" || $accntType == "R" || $accntType == "L") && $incrsDcrse == "D") {
            return 1;
        }
    }
    return 1;
}

function drCrAccMltplr($accntid, $drCrdt)
{
    $accntType = getAccntType($accntid);
    $isContra = isAccntContra($accntid);
    if ($isContra == "0") {
        if (($accntType == "A" || $accntType == "EX") && $drCrdt == "Dr") {
            return 1;
        } else if (($accntType == "A" || $accntType == "EX") && $drCrdt == "Cr") {
            return -1;
        } else if (($accntType == "EQ" || $accntType == "R" || $accntType == "L") && $drCrdt == "Cr") {
            return 1;
        } else if (($accntType == "EQ" || $accntType == "R" || $accntType == "L") && $drCrdt == "Dr") {
            return -1;
        }
    } else {
        if (($accntType == "A" || $accntType == "EX") && $drCrdt == "Cr") {
            return -1;
        } else if (($accntType == "A" || $accntType == "EX") && $drCrdt == "Dr") {
            return 1;
        } else if (($accntType == "EQ" || $accntType == "R" || $accntType == "L") && $drCrdt == "Dr") {
            return -1;
        } else if (($accntType == "EQ" || $accntType == "R" || $accntType == "L") && $drCrdt == "Cr") {
            return 1;
        }
    }
    return 1;
}

function get_DfltCashAcnt($orgID)
{
    global $prsnid;
    $strSql = "SELECT org.get_dflt_accnt_id($prsnid, sales_cash_acnt_id) " .
        "FROM scm.scm_dflt_accnts a " .
        "WHERE(a.org_id = " . $orgID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function get_DfltSalesLbltyAcnt($orgID)
{
    global $prsnid;
    $strSql = "SELECT org.get_dflt_accnt_id($prsnid, sales_lblty_acnt_id) " .
        "FROM scm.scm_dflt_accnts a " .
        "WHERE(a.org_id = " . $orgID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function get_DfltRcptLbltyAcnt($orgID)
{
    global $prsnid;
    $strSql = "SELECT org.get_dflt_accnt_id($prsnid, rcpt_lblty_acnt_id) " .
        "FROM scm.scm_dflt_accnts a " .
        "WHERE(a.org_id = " . $orgID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function get_One_DfltAcnt($orgID)
{
    global $prsnid;
    $strSql = "SELECT row_id, "
        . "org.get_dflt_accnt_id($prsnid, itm_inv_asst_acnt_id), "
        . "org.get_dflt_accnt_id($prsnid, cost_of_goods_acnt_id), "
        . "org.get_dflt_accnt_id($prsnid, expense_acnt_id), " .
        "org.get_dflt_accnt_id($prsnid, prchs_rtrns_acnt_id), "
        . "org.get_dflt_accnt_id($prsnid, rvnu_acnt_id), "
        . "org.get_dflt_accnt_id($prsnid, sales_rtrns_acnt_id), "
        . "org.get_dflt_accnt_id($prsnid, sales_cash_acnt_id), " .
        "org.get_dflt_accnt_id($prsnid, sales_check_acnt_id), "
        . "org.get_dflt_accnt_id($prsnid, sales_rcvbl_acnt_id), "
        . "org.get_dflt_accnt_id($prsnid, rcpt_cash_acnt_id), " .
        "org.get_dflt_accnt_id($prsnid, rcpt_lblty_acnt_id), "
        . "org.get_dflt_accnt_id($prsnid, inv_adjstmnts_lblty_acnt_id), "
        . "org.get_dflt_accnt_id($prsnid, ttl_caa), "
        . "org.get_dflt_accnt_id($prsnid, ttl_cla), " .
        "org.get_dflt_accnt_id($prsnid, ttl_aa), "
        . "org.get_dflt_accnt_id($prsnid, ttl_la), 
               org.get_dflt_accnt_id($prsnid, ttl_oea), 
               org.get_dflt_accnt_id($prsnid, ttl_ra), 
               org.get_dflt_accnt_id($prsnid, ttl_cgsa), 
               org.get_dflt_accnt_id($prsnid, ttl_ia), 
               org.get_dflt_accnt_id($prsnid, ttl_pea),
               org.get_dflt_accnt_id($prsnid, sales_dscnt_accnt), "
        . "org.get_dflt_accnt_id($prsnid, prchs_dscnt_accnt), "
        . "org.get_dflt_accnt_id($prsnid, sales_lblty_acnt_id), "
        . "org.get_dflt_accnt_id($prsnid, bad_debt_acnt_id), "
        . "org.get_dflt_accnt_id($prsnid, rcpt_rcvbl_acnt_id), "
        . "org.get_dflt_accnt_id($prsnid, petty_cash_acnt_id) " .
        "FROM scm.scm_dflt_accnts a " .
        "WHERE(a.org_id = " . $orgID . ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_DfltPyblAcnt($orgID)
{
    global $prsnid;
    $strSql = "SELECT org.get_dflt_accnt_id($prsnid, rcpt_lblty_acnt_id) " .
        "FROM scm.scm_dflt_accnts a " .
        "WHERE(a.org_id = $orgID)";

    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function get_DfltRcvblAcnt($orgID)
{
    global $prsnid;
    $strSql = "SELECT org.get_dflt_accnt_id($prsnid, sales_rcvbl_acnt_id) " .
        "FROM scm.scm_dflt_accnts a " .
        "WHERE(a.org_id = $orgID)";

    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function get_DfltInvAcnt($orgID)
{
    global $prsnid;
    $strSql = "SELECT org.get_dflt_accnt_id($prsnid, itm_inv_asst_acnt_id) " .
        "FROM scm.scm_dflt_accnts a " .
        "WHERE(a.org_id = $orgID)";

    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function get_DfltCSGAcnt($orgID)
{
    global $prsnid;
    $strSql = "SELECT org.get_dflt_accnt_id($prsnid, cost_of_goods_acnt_id) " .
        "FROM scm.scm_dflt_accnts a " .
        "WHERE(a.org_id = $orgID)";

    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function get_DfltExpnsAcnt($orgID)
{
    global $prsnid;
    $strSql = "SELECT org.get_dflt_accnt_id($prsnid, expense_acnt_id) " .
        "FROM scm.scm_dflt_accnts a " .
        "WHERE(a.org_id = $orgID)";

    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function get_DfltRvnuAcnt($orgID)
{
    global $prsnid;
    $strSql = "SELECT org.get_dflt_accnt_id($prsnid, rvnu_acnt_id) " .
        "FROM scm.scm_dflt_accnts a " .
        "WHERE(a.org_id = $orgID)";

    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function get_DfltSRAcnt($orgID)
{
    global $prsnid;
    $strSql = "SELECT org.get_dflt_accnt_id($prsnid, sales_rtrns_acnt_id) " .
        "FROM scm.scm_dflt_accnts a " .
        "WHERE(a.org_id = $orgID)";

    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function get_DfltCheckAcnt($orgID)
{
    global $prsnid;
    $strSql = "SELECT org.get_dflt_accnt_id($prsnid, sales_check_acnt_id) " .
        "FROM scm.scm_dflt_accnts a " .
        "WHERE(a.org_id = $orgID)";

    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function get_LtstExchRate($fromCurrID, $toCurrID, $asAtDte)
{
    global $orgID;
    $fnccurid = getOrgFuncCurID($orgID);
    if ($fromCurrID == $toCurrID) {
        return 1;
    }
    $strSql = "SELECT CASE WHEN a.currency_from_id=" . $fromCurrID .
        " THEN a.multiply_from_by ELSE (1/a.multiply_from_by) END
      FROM accb.accb_exchange_rates a WHERE ((a.currency_from_id=" . $fromCurrID .
        " and a.currency_to_id=" . $toCurrID .
        ") or (a.currency_to_id=" . $fromCurrID .
        " and a.currency_from_id=" . $toCurrID .
        ")) and to_timestamp(a.conversion_date,'YYYY-MM-DD') <= to_timestamp('" . $asAtDte .
        "','DD-Mon-YYYY HH24:MI:SS') ORDER BY to_timestamp(a.conversion_date,'YYYY-MM-DD') DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    if ($fromCurrID != $fnccurid && $toCurrID != $fnccurid) {
        $a = get_LtstExchRate($fromCurrID, $fnccurid, $asAtDte);
        $b = get_LtstExchRate($toCurrID, $fnccurid, $asAtDte);
        if ($a != 0 && $b != 0) {
            return $a / $b;
        } else {
            return 1;
        }
    } else {
        return 1;
    }
}

function get_LtstBNKExchRate($fromCurrID, $toCurrID, $asAtDte)
{
    global $orgID;
    $fnccurid = getOrgFuncCurID($orgID);
    if ($fromCurrID == $toCurrID) {
        return 1;
    }
    $strSql = "SELECT CASE WHEN a.currency_from_id=" . $fromCurrID .
        " THEN a.multiply_from_by ELSE (1/a.multiply_from_by) END
      FROM mcf.mcf_exchange_rates a WHERE ((a.currency_from_id=" . $fromCurrID .
        " and a.currency_to_id=" . $toCurrID .
        ") or (a.currency_to_id=" . $fromCurrID .
        " and a.currency_from_id=" . $toCurrID .
        ")) and to_timestamp(a.conversion_date,'YYYY-MM-DD') <= to_timestamp('" . $asAtDte .
        "','DD-Mon-YYYY HH24:MI:SS') ORDER BY to_timestamp(a.conversion_date,'YYYY-MM-DD') DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    if ($fromCurrID != $fnccurid && $toCurrID != $fnccurid) {
        $a = get_LtstBNKExchRate($fromCurrID, $fnccurid, $asAtDte);
        $b = get_LtstBNKExchRate($toCurrID, $fnccurid, $asAtDte);
        if ($a != 0 && $b != 0) {
            return $a / $b;
        } else {
            return 1;
        }
    } else {
        return 1;
    }
}

function get_LtstBNKExchRateBuy($fromCurrID, $toCurrID, $asAtDte)
{
    global $orgID;
    $fnccurid = getOrgFuncCurID($orgID);
    if ($fromCurrID == $toCurrID) {
        return 1;
    }
    $strSql = "SELECT CASE WHEN a.currency_from_id=" . $fromCurrID .
        " THEN a.mltply_frm_by_whn_buyng ELSE (1/a.mltply_frm_by_whn_buyng) END
      FROM mcf.mcf_exchange_rates a WHERE ((a.currency_from_id=" . $fromCurrID .
        " and a.currency_to_id=" . $toCurrID .
        ") or (a.currency_to_id=" . $fromCurrID .
        " and a.currency_from_id=" . $toCurrID .
        ")) and to_timestamp(a.conversion_date,'YYYY-MM-DD') <= to_timestamp('" . $asAtDte .
        "','DD-Mon-YYYY HH24:MI:SS') ORDER BY to_timestamp(a.conversion_date,'YYYY-MM-DD') DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    if ($fromCurrID != $fnccurid && $toCurrID != $fnccurid) {
        $a = get_LtstBNKExchRateBuy($fromCurrID, $fnccurid, $asAtDte);
        $b = get_LtstBNKExchRateBuy($toCurrID, $fnccurid, $asAtDte);
        if ($a != 0 && $b != 0) {
            return $a / $b;
        } else {
            return 1;
        }
    } else {
        return 1;
    }
}

function getAccntLstDailyNetBals($accntID, $balsDate)
{
    if ($balsDate != "") {
        $balsDate = substr(cnvrtDMYTmToYMDTm($balsDate), 0, 10);
    }
    $strSql = "SELECT a.net_balance " .
        "FROM accb.accb_accnt_daily_bals a " .
        "WHERE(to_timestamp(a.as_at_date,'YYYY-MM-DD') <=  to_timestamp('" . $balsDate .
        "','YYYY-MM-DD') and a.accnt_id = " . $accntID .
        ") ORDER BY to_timestamp(a.as_at_date,'YYYY-MM-DD') DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function getAccntCrncyLstDlyNetBals($accntID, $balsDate)
{
    if ($balsDate != "") {
        $balsDate = substr(cnvrtDMYTmToYMDTm($balsDate), 0, 10);
    }
    $strSql = "SELECT a.net_balance " .
        "FROM accb.accb_accnt_crncy_daily_bals a " .
        "WHERE(to_timestamp(a.as_at_date,'YYYY-MM-DD') <=  to_timestamp('" . $balsDate .
        "','YYYY-MM-DD') and a.accnt_id = " . $accntID .
        ") ORDER BY to_timestamp(a.as_at_date,'YYYY-MM-DD') DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function isPayTrnsValid($accntID, $incrsDcrs, $amnt, $date1)
{
    $netamnt = dbtOrCrdtAccntMultiplier($accntID, $incrsDcrs) * $amnt;
    $errMsg = "";
    if (!isTransPrmttd($accntID, $date1, $netamnt, $errMsg)) {
        return false;
    }
    return true;
}

function isTransPrmttd($accntID, $trnsdate, $amnt, &$errMsg)
{
    global $orgID;
    try {
        if ($accntID <= 0 || $trnsdate == "") {
            return false;
        }
        $trnsDte = DateTime::createFromFormat('d-M-Y H:i:s', $trnsdate);
        $dte1 = DateTime::createFromFormat('d-M-Y H:i:s', getLtstPrdStrtDate());
        $dte1Or = DateTime::createFromFormat('d-M-Y H:i:s', getLastPrdClseDate());
        $dte2 = DateTime::createFromFormat('d-M-Y H:i:s', getLtstPrdEndDate());

        if ($trnsDte <= $dte1Or) {
            $errMsg = "Transaction Date cannot be On or Before " . $dte1Or->format('d-M-Y H:i:s');
            return false;
        }

        if ($trnsDte < $dte1) {
            $errMsg = "Transaction Date cannot be before " . $dte1Or->format('d-M-Y H:i:s');
            return false;
        }

        if ($trnsDte > $dte2) {
            $errMsg = "Transaction Date cannot be after " . $dte2->format('d-M-Y H:i:s');
            return false;
        }
        //Check if trnsDate exists in an Open Period
        $prdHdrID = getPrdHdrID($orgID);
        if ($prdHdrID > 0) {
            if (getTrnsDteOpenPrdLnID($prdHdrID, $trnsDte->format('Y-m-d H:i:s')) < 0) {
                $errMsg = "Cannot use a Transaction Date (" . $trnsDte->format('d-M-Y H:i:s') . ") which does not exist in any OPEN period!";
                return false;
            }
            //Check if Date is not in Disallowed Dates
            $noTrnsDatesLov = getGnrlRecNm("accb.accb_periods_hdr", "periods_hdr_id", "no_trns_dates_lov_nm", $prdHdrID);
            $noTrnsDayLov = getGnrlRecNm("accb.accb_periods_hdr", "periods_hdr_id", "no_trns_wk_days_lov_nm", $prdHdrID);

            if ($noTrnsDatesLov != "") {
                if (getEnbldPssblValID(strtoupper($trnsDte->format('d-M-Y')), getLovID($noTrnsDatesLov)) > 0) {
                    $errMsg = "Transactions on this Date (" . $trnsDte->format('d-M-Y') . ") have been banned on this system!";
                    return false;
                }
            }
            //Check if Day of Week is not in Disaalowed days
            if ($noTrnsDatesLov != "") {
                if (getEnbldPssblValID(strtoupper($trnsDte->format('dddd')), getLovID($noTrnsDayLov)) > 0) {
                    $errMsg = "Transactions on this Day of Week (" . $trnsDte->format('dddd') . ") have been banned on this system!";
                    return false;
                }
            }
        }

        //Amount must not disobey budget settings on that account
        $actvBdgtID = getActiveBdgtID($orgID);
        $amntLmt = getAcntsBdgtdAmnt($actvBdgtID, $accntID, $trnsDte->format('d-M-Y H:i:s'));
        $bdte1 = DateTime::createFromFormat('d-M-Y H:i:s', getAcntsBdgtStrtDte($actvBdgtID, $accntID, $trnsDte->format('d-M-Y H:i:s')));
        $bdte2 = DateTime::createFromFormat('d-M-Y H:i:s', getAcntsBdgtEndDte($actvBdgtID, $accntID, $trnsDte->format('d-M-Y H:i:s')));
        $crntBals = getTrnsSum($accntID, $bdte1->format('d-M-Y H:i:s'), $bdte2->format('d-M-Y H:i:s'), "1");
        $actn = getAcntsBdgtLmtActn($actvBdgtID, $accntID, $trnsdate);

        if (($amnt + $crntBals) > $amntLmt) {
            if ($actn == "Disallow") {
                $errMsg = "This transaction will cause budget on \r\nthe chosen account to be exceeded! ";
                return false;
            } else if ($actn == "Warn") {
                $errMsg = "This is just to WARN you that the budget on \r\nthe chosen account will be exceeded!";
                return true;
            } else if ($actn == "Congratulate") {
                $errMsg = "This is just to CONGRATULATE you for exceeding the targetted Amount! ";
                return true;
            } else {
                return true;
            }
        }
        return true;
    } catch (Exception $ex) {
        $errMsg = $ex->getMessage();
        return false;
    }
}

function getTrnsSum($accntid, $strDte, $endDte, $ispsted)
{
    $strDte = cnvrtDMYTmToYMDTm($strDte);
    $endDte = cnvrtDMYTmToYMDTm($endDte);
    $strSql = "SELECT SUM(a.net_amount) " .
        "FROM accb.accb_trnsctn_details a " .
        "WHERE(a.trns_status='" . $ispsted .
        "' and a.accnt_id = " . $accntid .
        " and (to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS') " .
        "between to_timestamp('" . $strDte . "','YYYY-MM-DD HH24:MI:SS')" .
        " AND to_timestamp('" . $endDte . "','YYYY-MM-DD HH24:MI:SS')))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function getTrnsDteOpenPrdLnID($prdHdrID, $trnsDte)
{
    $strSql = "SELECT a.period_det_id " .
        "FROM accb.accb_periods_det a " .
        "WHERE((a.period_hdr_id = " . $prdHdrID .
        ") and (a.period_status='Open') and (to_timestamp('" . $trnsDte . "','YYYY-MM-DD HH24:MI:SS') " .
        "between to_timestamp(a.period_start_date,'YYYY-MM-DD HH24:MI:SS')
       and to_timestamp(a.period_end_date,'YYYY-MM-DD HH24:MI:SS')))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function getPrdHdrID($orgId)
{
    $strSql = "SELECT a.periods_hdr_id " .
        "FROM accb.accb_periods_hdr a " .
        "WHERE(a.use_periods_for_org = '1' and a.org_id = " . $orgId . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function getActiveBdgtID($orgId)
{
    $strSql = "SELECT a.budget_id " .
        "FROM accb.accb_budget_header a " .
        "WHERE(a.is_the_active_one = '1' and a.org_id = " . $orgId . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function getAcntsBdgtdAmnt4Prd($bdgtID, $accntID, $strtdate, $enddate)
{
    $strtdate = cnvrtDMYTmToYMDTm($strtdate);
    $enddate = cnvrtDMYTmToYMDTm($enddate);
    $strSql = "SELECT a.limit_amount " .
        "FROM accb.accb_budget_details a " .
        "WHERE((a.budget_id = " . $bdgtID .
        ") and (a.accnt_id = " . $accntID .
        ") and (a.start_date = '" . $strtdate . "')" .
        " and (a.end_date = '" . $enddate . "'))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0.00;
}

function getAcntsBdgtdAmnt($bdgtID, $accntID, $trnsdate)
{
    $trnsdate = cnvrtDMYTmToYMDTm($trnsdate);
    $strSql = "SELECT a.limit_amount " .
        "FROM accb.accb_budget_details a " .
        "WHERE((a.budget_id = " . $bdgtID .
        ") and (a.accnt_id = " . $accntID . ") and (to_timestamp('" . $trnsdate .
        "','YYYY-MM-DD HH24:MI:SS') between to_timestamp(a.start_date,'YYYY-MM-DD HH24:MI:SS')" .
        " AND to_timestamp(a.end_date,'YYYY-MM-DD HH24:MI:SS')))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0.00;
}

function getAcntsBdgtLmtActn($bdgtID, $accntID, $trnsdate)
{
    $trnsdate = cnvrtDMYTmToYMDTm($trnsdate);
    $strSql = "SELECT a.action_if_limit_excded " .
        "FROM accb.accb_budget_details a " .
        "WHERE((a.budget_id = " . $bdgtID .
        ") and (a.accnt_id = " . $accntID .
        ") and (to_timestamp('" . $trnsdate .
        "','YYYY-MM-DD HH24:MI:SS') between to_timestamp(a.start_date,'YYYY-MM-DD HH24:MI:SS')" .
        " AND to_timestamp(a.end_date,'YYYY-MM-DD HH24:MI:SS')))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "None";
}

function getAcntsBdgtStrtDte($bdgtID, $accntID, $trnsdate)
{
    $trnsdate = cnvrtDMYTmToYMDTm($trnsdate);
    $strSql = "SELECT to_char(to_timestamp(a.start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') " .
        "FROM accb.accb_budget_details a " .
        "WHERE((a.budget_id = " . $bdgtID .
        ") and (a.accnt_id = " . $accntID .
        ") and (to_timestamp('" . $trnsdate .
        "','YYYY-MM-DD HH24:MI:SS') between to_timestamp(a.start_date,'YYYY-MM-DD HH24:MI:SS')" .
        " AND to_timestamp(a.end_date,'YYYY-MM-DD HH24:MI:SS')))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return DateTime::createFromFormat('d-M-Y H:i:s', getFrmtdDB_Date_time())->format('d-M-Y') . " 00:00:00";
}

function getAcntsBdgtEndDte($bdgtID, $accntID, $trnsdate)
{
    if ($trnsdate != "") {
        $trnsdate = cnvrtDMYTmToYMDTm($trnsdate);
    }
    $strSql = "SELECT to_char(to_timestamp(a.end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') " .
        "FROM accb.accb_budget_details a " .
        "WHERE((a.budget_id = " . $bdgtID .
        ") and (a.accnt_id = " . $accntID .
        ") and (to_timestamp('" . $trnsdate . "','YYYY-MM-DD HH24:MI:SS') between to_timestamp(a.start_date,'YYYY-MM-DD HH24:MI:SS')" .
        " AND to_timestamp(a.end_date,'YYYY-MM-DD HH24:MI:SS')))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return DateTime::createFromFormat('d-M-Y H:i:s', getFrmtdDB_Date_time())->format('d-M-Y') . " 23:59:59";
}

function getLastPrdClseDate()
{
    global $orgID;
    $strSql = "SELECT to_char(to_timestamp(period_close_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') " .
        "FROM accb.accb_period_close_dates " .
        "WHERE org_id = " . $orgID .
        " ORDER BY period_close_id DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "01-Jan-1900 00:00:00";
}

function getLtstPrdStrtDate()
{
    $strSql = "SELECT b.pssbl_value " .
        "FROM gst.gen_stp_lov_names a, gst.gen_stp_lov_values b " .
        "WHERE(a.value_list_id = b.value_list_id and b.is_enabled = '1'" .
        " and  a.value_list_name= 'Transactions Date Limit 1') " .
        "ORDER BY b.pssbl_value_id DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        $rs = $row[0];
        if (strlen($rs) <= 11) {
            $rs = $rs . " 00:00:00";
        }
        return $rs;
    }
    return DateTime::createFromFormat('d-M-Y H:i:s', getFrmtdDB_Date_time())->format('d-M-Y') . " 00:00:00";
}

function getLtstOpenPrdAfterDate($trnsDate)
{
    $strSql = "SELECT a.period_start_date " .
        "FROM accb.accb_periods_det a " .
        "WHERE(a.period_start_date >='" . $trnsDate . "' and a.period_status ='Open') ORDER BY a.period_start_date ASC LIMIT 1 OFFSET 0 ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        $rs = $row[0];
        return $rs;
    }
    return DateTime::createFromFormat('d-M-Y H:i:s', getFrmtdDB_Date_time())->format('d-M-Y') . " 00:00:00";
}

function getLtstPrdEndDate()
{
    $strSql = "SELECT b.pssbl_value " .
        "FROM gst.gen_stp_lov_names a, gst.gen_stp_lov_values b " .
        "WHERE(a.value_list_id = b.value_list_id and b.is_enabled = '1'" .
        " and  a.value_list_name= 'Transactions Date Limit 2') " .
        "ORDER BY b.pssbl_value_id DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        $rs = $row[0];
        if (strlen($rs) <= 11) {
            $rs = $rs . " 23:59:59";
        }
        return $rs;
    }
    return DateTime::createFromFormat('d-M-Y H:i:s', getFrmtdDB_Date_time())->format('d-M-Y') . " 23:59:59";
}

function getBrowser()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version = "";
    $ub = "";
    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    } elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
    // Next get the name of the useragent yes seperately and for good reason
    if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    } elseif (preg_match('/Firefox/i', $u_agent)) {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    } elseif (preg_match('/Chrome/i', $u_agent)) {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    } elseif (preg_match('/Safari/i', $u_agent)) {
        $bname = 'Apple Safari';
        $ub = "Safari";
    } elseif (preg_match('/Opera/i', $u_agent)) {
        $bname = 'Opera';
        $ub = "Opera";
    } elseif (preg_match('/Netscape/i', $u_agent)) {
        $bname = 'Netscape';
        $ub = "Netscape";
    }

    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
            $version = $matches['version'][0];
        } else {
            $version = $matches['version'][1];
        }
    } else {
        $version = $matches['version'][0];
    }

    // check if we have a number
    if ($version == null || $version == "") {
        $version = "?";
    }

    return array(
        'userAgent' => $u_agent,
        'name' => $bname,
        'version' => $version,
        'platform' => $platform,
        'pattern' => $pattern
    );
}

function concatCurRoleIDs()
{
    $nwStr = str_replace(";", ",", $_SESSION['ROLE_SET_IDS']);
    return $nwStr;
}

function doesCrPlcTrckThisActn($actionTyp)
{
    global $ModuleName;
    // Checks whether the current policy tracks a particular action in the current module
    $sqlStr = "SELECT policy_id FROM " .
        "sec.sec_audit_trail_tbls_to_enbl WHERE ((policy_id = " . getCurPlcyID() .
        ") AND (module_id = " . getModuleID($ModuleName) .
        ") AND (action_typs_to_track ilike '%" .
        $actionTyp . "%') AND (enable_tracking = TRUE))";
    $result = executeSQLNoParams($sqlStr);
    while (loc_db_num_rows($result) > 0) {
        return true;
    }
    return false;
}

function deleteGnrlRecs($rowID, $tblnm, $pk_nm, $extrInf = '')
{
    $delSQL = "DELETE FROM " . $tblnm . " WHERE " . $pk_nm . " = " . $rowID;
    return execUpdtInsSQL($delSQL, $extrInf);
}

function getLogMsgID($logTblNm, $procstyp, $procsID)
{
    $sqlStr = "select msg_id from $logTblNm where process_typ = '" . loc_db_escape_string($procstyp) .
        "' and process_id = $procsID";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function isDteTmeWthnIntrvl($in_date, $intrval)
{
    $sqlStr = "SELECT age(now(), to_timestamp('$in_date', 'YYYY-MM-DD HH24:MI:SS')) " .
        "<= interval '$intrval'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        if ("$row[0]" == 't') {
            return true;
        } else {
            return false;
        }
    }
    return false;
}

function doesDteTmeExceedIntrvl($in_date, $intrval)
{
    //
    $sqlStr = "SELECT age(now(), to_timestamp('$in_date', 'YYYY-MM-DD HH24:MI:SS')) " .
        "> interval '$intrval'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        if ("$row[0]" == 't') {
            return true;
        } else {
            return false;
        }
    }
    return false;
}

function createRptRn($runBy, $runDate, $rptID, $paramIDs, $paramVals, $outptUsd, $orntUsd, $alrtID)
{
    //$datestr = getDB_Date_time();
    $insSQL = "INSERT INTO rpt.rpt_report_runs(
            run_by, run_date, rpt_run_output, run_status_txt, 
            run_status_prct, report_id, rpt_rn_param_ids, rpt_rn_param_vals, 
            output_used, orntn_used, last_actv_date_tme, is_this_from_schdler, alert_id) 
            VALUES ($runBy, '$runDate', '', 'Not Started!', 0, $rptID, 
            '" . loc_db_escape_string($paramIDs) . "', 
            '" . loc_db_escape_string($paramVals) . "', 
            '" . loc_db_escape_string($outptUsd) . "', 
            '" . loc_db_escape_string($orntUsd) . "', '$runDate', '0', " . loc_db_escape_string($alrtID) . ")";
    execUpdtInsSQL($insSQL);
}

function getRptRnID($rptID, $runBy, $runDate)
{
    $sqlStr = "select rpt_run_id from rpt.rpt_report_runs where run_by = 
        $runBy and report_id = $rptID and run_date = '$runDate' order by rpt_run_id DESC";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createLogMsg($logmsg, $logTblNm, $procstyp, $procsID, $dateStr)
{
    global $usrID;
    $insSQL = "INSERT INTO " . $logTblNm . "(" .
        "log_messages, process_typ, process_id, created_by, creation_date, " .
        "last_update_by, last_update_date) " .
        "VALUES ('" . loc_db_escape_string($logmsg) .
        "','" . loc_db_escape_string($procstyp) . "'," . $procsID .
        ", " . $usrID . ", '" . $dateStr .
        "', " . $usrID . ", '" . $dateStr .
        "')";
    execUpdtInsSQL($insSQL);
}

function updateLogMsg($msgid, $logmsg, $logTblNm, $dateStr)
{
    global $usrID;
    $updtSQL = "UPDATE " . $logTblNm . " " .
        "SET log_messages=log_messages || '" . loc_db_escape_string($logmsg) .
        "', last_update_by=" . $usrID .
        ", last_update_date='" . $dateStr .
        "' WHERE msg_id = " . $msgid;
    execUpdtInsSQL($updtSQL);
}

function updateRptRnParams($rptrnid, $paramIDs, $paramVals, $outputUsd, $orntn)
{
    $updtSQL = "UPDATE rpt.rpt_report_runs SET " .
        "rpt_rn_param_ids = '" . loc_db_escape_string($paramIDs) .
        "', rpt_rn_param_vals = '" . loc_db_escape_string($paramVals) .
        "', output_used = '" . loc_db_escape_string($outputUsd) .
        "', orntn_used= '" . loc_db_escape_string($orntn) .
        "' WHERE (rpt_run_id = " . $rptrnid . ")";
    execUpdtInsSQL($updtSQL);
}

function updatePrcsRnnrCmd($rnnrNm, $cmdStr)
{
    global $usrID;
    $updtCls = "";
    $updtSQL = "UPDATE rpt.rpt_prcss_rnnrs SET 
            shld_rnnr_stop='" . loc_db_escape_string($cmdStr) . "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS')" . $updtCls .
        " WHERE rnnr_name = '" . loc_db_escape_string($rnnrNm) . "'";
    //echo $updtSQL;
    return execUpdtInsSQL($updtSQL);
}

function updateRptRnActvTme($rptrnid, $actvTme)
{
    $updtSQL = "UPDATE rpt.rpt_report_runs SET " .
        "last_actv_date_tme = '" . loc_db_escape_string($actvTme) .
        "' WHERE (rpt_run_id = " . $rptrnid . ")";
    return execUpdtInsSQL($updtSQL);
}

function updateRptRnStopCmd($rptrnid, $cmdStr)
{
    $updtCls = "";
    if ($cmdStr === "1") {
        $updtCls = ", run_status_prct=100, run_status_txt='Cancelled'";
    }
    $updtSQL = "UPDATE rpt.rpt_report_runs SET " .
        "shld_run_stop = '" . loc_db_escape_string($cmdStr) .
        "'" . $updtCls . " WHERE (rpt_run_id = " . $rptrnid . ")";
    return execUpdtInsSQL($updtSQL);
}

function createPrcsRnnr($rnnrNm, $rnnrDesc, $lstActvTm, $stats, $rnnPryty, $execFile)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO rpt.rpt_prcss_rnnrs(
            rnnr_name, rnnr_desc, rnnr_lst_actv_dtetme, created_by, 
            creation_date, last_update_by, last_update_date, rnnr_status, 
            crnt_rnng_priority, executbl_file_nm) " .
        "VALUES ('" . loc_db_escape_string($rnnrNm) . "', '" . loc_db_escape_string($rnnrDesc) .
        "', '" . loc_db_escape_string($lstActvTm) . "', " . $usrID . ", '" . loc_db_escape_string($dateStr) .
        "', " . $usrID . ", '" . loc_db_escape_string($dateStr) .
        "', '" . loc_db_escape_string($stats) . "', '" . loc_db_escape_string($rnnPryty) .
        "', '" . loc_db_escape_string($execFile) . "')";
    return execUpdtInsSQL($insSQL);
}

function updatePrcsRnnr($rnnrID, $rnnrNm, $rnnrDesc, $lstActvTm, $stats, $rnnPryty, $execFile)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE rpt.rpt_prcss_rnnrs SET 
            rnnr_name='" . loc_db_escape_string($rnnrNm) .
        "', rnnr_desc='" . loc_db_escape_string($rnnrDesc) .
        "', rnnr_lst_actv_dtetme='" . loc_db_escape_string($lstActvTm) .
        "', last_update_by=" . $usrID .
        ", last_update_date='" . loc_db_escape_string($dateStr) .
        "', rnnr_status='" . loc_db_escape_string($stats) .
        "', crnt_rnng_priority='" . loc_db_escape_string($rnnPryty) .
        "', executbl_file_nm='" . loc_db_escape_string($execFile) .
        "' WHERE prcss_rnnr_id = " . $rnnrID;
    return execUpdtInsSQL($insSQL);
}

function updatePrcsRnnrNm($rnnrID, $rnnrNm, $rnnrDesc, $execFile, $rnnrPrty = "5-Lowest")
{
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE rpt.rpt_prcss_rnnrs SET 
            rnnr_name='" . loc_db_escape_string($rnnrNm) .
        "', rnnr_desc='" . loc_db_escape_string($rnnrDesc) .
        "', last_update_by=-1, last_update_date='" . loc_db_escape_string($dateStr) .
        "', executbl_file_nm='" . loc_db_escape_string($execFile) .
        "', crnt_rnng_priority='" . loc_db_escape_string($rnnrPrty) .
        "' WHERE prcss_rnnr_id = " . $rnnrID;
    return execUpdtInsSQL($insSQL);
}

function generateReportRun($rptID, $slctdParams, $alrtID)
{
    global $usrID;
    global $ftp_base_db_fldr;
    global $host;
    global $port;
    global $postgre_db_pwd;
    global $database;
    global $app_url;
    global $lgn_num;
    global $rhoAPIUrl;
    $db_usr = "postgres";
    $db_pwd = $postgre_db_pwd;
    $rptRunID = -1;
    $rptRnnrNm = "";
    $rnnrPrcsFile = "";
    $slctdParamsRows = explode("|", $slctdParams);
    $paramIDs = "";
    $paramVals = "";
    $outputUsd = "HTML";
    $orntn = "";
    logSessionErrs("Inside generateReportRun, RPTID=" . $rptID . "==" . $slctdParams);
    for ($i = 0; $i < count($slctdParamsRows); $i++) {
        $slctdParamsCols = explode("~", $slctdParamsRows[$i]);
        if ($slctdParamsCols[0] != "") {
            $paramIDs = $paramIDs . cleanInputData($slctdParamsCols[0]) . "|";
            $paramVals = $paramVals . cleanInputData($slctdParamsCols[1]) . "|";
            if (cleanInputData($slctdParamsCols[0]) == "-190") {
                $outputUsd = cleanInputData($slctdParamsCols[1]);
            } else if (cleanInputData($slctdParamsCols[0]) == "-200") {
                $orntn = cleanInputData($slctdParamsCols[1]);
            }
        }
    }

    $paramIDs = substr($paramIDs, 0, -1); //trim($paramIDs, "| ");
    $paramVals = substr($paramVals, 0, -1); //trim($paramVals, "| ");
    if ($paramIDs != "" && $paramVals != "") {
        $datestr = getDB_Date_time();
        createRptRn($usrID, $datestr, $rptID, "", "", "", "", $alrtID);
        $rptRunID = getRptRnID($rptID, $usrID, $datestr);
        $msg_id = getLogMsgID("rpt.rpt_run_msgs", "Process Run", $rptRunID);
        if ($msg_id <= 0) {
            createLogMsg($datestr .
                " .... Report/Process Run is about to Start...(Being run by " .
                getUserName($usrID) . ")", "rpt.rpt_run_msgs", "Process Run", $rptRunID, $datestr);
            $msg_id = getLogMsgID("rpt.rpt_run_msgs", "Process Run", $rptRunID);
        }
        updateLogMsg(
            $msg_id,
            "\r\n\r\n" . $paramIDs . "\r\n" . $paramVals .
                "\r\n\r\nOUTPUT FORMAT: " . $outputUsd . "\r\nORIENTATION: " . $orntn,
            "rpt.rpt_run_msgs",
            $datestr
        );
        updateRptRnParams($rptRunID, $paramIDs, $paramVals, $outputUsd, $orntn);

        //Launch appropriate process runner
        $rptRnnrNm = getGnrlRecNm("rpt.rpt_reports", "report_id", "process_runner", $rptID);
        $rnnrPrcsFile = $ftp_base_db_fldr . "/bin/REMSProcessRunner.jar";
        updatePrcsRnnrCmd($rptRnnrNm, "0");
        updateRptRnStopCmd($rptRunID, "0");
        //PHP Command to start jar file
        $strArgs = "\"" . $host . "\" " .
            "\"" . $port . "\" " .
            "\"" . $db_usr . "\" " .
            "\"" . $db_pwd . "\" " .
            "\"" . $database . "\" " .
            "\"" . $rptRnnrNm . "\" " .
            "\"" . $rptRunID . "\" " .
            "\"" . $ftp_base_db_fldr . "/bin" . "\" " .
            "WEB" . " " .
            "\"" . $ftp_base_db_fldr . "\" " .
            "\"" . $app_url . "\"";
        $cmd = "java -jar " . $rnnrPrcsFile . " " . $strArgs;
        //-Djava.security.egd=file:/dev/./urandom 
        /* if (substr(php_uname(), 0, 7) == "Windows") {
          $cmd = "java -jar " . $rnnrPrcsFile . " " . $strArgs;
          } */
        //"java -Xms64m -Xmx4096m -jar "
        logSessionErrs(str_replace($db_pwd, "***************", $cmd));
        //return -1;
        $logfilenm = $ftp_base_db_fldr . "/Logs/cmnd_line_logs_" . $rptRunID . "_" . getDB_Date_timeYYMDHMS() . ".txt";

        $rhoAPIhost = parse_url($rhoAPIUrl, PHP_URL_HOST);
        $rhoAPIport = parse_url($rhoAPIUrl, PHP_URL_PORT);

        $errno = 0;
        $errstr = "";
        set_error_handler("rhoErrorHandler3");
        $rc = @fsockopen($rhoAPIhost, $rhoAPIport, $errno, $errstr, 1);
        if (is_resource($rc)) {
            $rslt = rhoPOSTToAPI(
                $rhoAPIUrl . '/startJavaRunner',
                array(
                    'rnnrPrcsFile' => $rnnrPrcsFile,
                    'strArgs' => $strArgs
                )
            );
        } else {
            execInBackground($cmd, $logfilenm);
        }
    } else {
        echo "Invalid Parameters";
    }
    return $rptRunID;
}

function reRunReport($rptID, $rptRunID)
{
    global $usrID;
    global $ftp_base_db_fldr;
    global $host;
    global $port;
    global $postgre_db_pwd;
    global $database;
    global $app_url;
    global $rhoAPIUrl;
    $db_usr = "postgres";
    $db_pwd = $postgre_db_pwd;
    $outputUsd = "HTML";
    $orntn = "";
    $rptRnnrNm = getGnrlRecNm("rpt.rpt_reports", "report_id", "process_runner", $rptID);
    $rnnrPrcsFile = $ftp_base_db_fldr . "/bin/REMSProcessRunner.jar";
    $msg_id = getLogMsgID("rpt.rpt_run_msgs", "Process Run", $rptRunID);
    $datestr = getDB_Date_time();
    if ($msg_id <= 0) {
        createLogMsg($datestr .
            " .... Report/Process Run is about to Start...(Being run by " .
            getUserName($usrID) . ")", "rpt.rpt_run_msgs", "Process Run", $rptRunID, $datestr);
        $msg_id = getLogMsgID("rpt.rpt_run_msgs", "Process Run", $rptRunID);
    }
    updateLogMsg($msg_id, "\r\n\r\nRe-run of program about to start...", "rpt.rpt_run_msgs", $datestr);
    updatePrcsRnnrCmd($rptRnnrNm, "0");
    updateRptRnStopCmd($rptRunID, "0");
    updateRptRnActvTme($rptRunID, $datestr);
    //PHP Command to start jar file
    $strArgs = "\"" . $host . "\" " .
        "\"" . $port . "\" " .
        "\"" . $db_usr . "\" " .
        "\"" . $db_pwd . "\" " .
        "\"" . $database . "\" " .
        "\"" . $rptRnnrNm . "\" " .
        "\"" . $rptRunID . "\" " .
        "\"" . $ftp_base_db_fldr . "/bin" . "\" " .
        "WEB" . " " .
        "\"" . $ftp_base_db_fldr . "\" " .
        "\"" . $app_url . "\"";
    $cmd = "java -jar " . $rnnrPrcsFile . " " . $strArgs;
    logSessionErrs(str_replace($db_pwd, "***************", $cmd));
    $logfilenm = $ftp_base_db_fldr . "/Logs/cmnd_line_logs_" . $rptRunID . "_" . getDB_Date_timeYYMDHMS() . ".txt";

    $rhoAPIhost = parse_url($rhoAPIUrl, PHP_URL_HOST);
    $rhoAPIport = parse_url($rhoAPIUrl, PHP_URL_PORT);

    $errno = 0;
    $errstr = "";
    set_error_handler("rhoErrorHandler3");
    $rc = @fsockopen($rhoAPIhost, $rhoAPIport, $errno, $errstr, 1);
    if (is_resource($rc)) {
        $rslt = rhoPOSTToAPI(
            $rhoAPIUrl . '/startJavaRunner',
            array(
                'rnnrPrcsFile' => $rnnrPrcsFile,
                'strArgs' => $strArgs
            )
        );
    } else {
        execInBackground($cmd, $logfilenm);
    }
    return $rptRunID;
}

function getCurPlcyID()
{
    $sqlStr = "select policy_id from sec.sec_security_policies where is_default = 't'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getAcntGroup($prsnID, $divType = "Access Control Group")
{
    $sqlStr = "select pasn.get_prsn_divid_of_spctype(" . loc_db_escape_string($prsnID) . ",'" . loc_db_escape_string($divType) . "')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getOrgName($orgID)
{
    $strSQL = "SELECT org_name FROM org.org_details WHERE org_id = $orgID";
    $result = executeSQLNoParams($strSQL);
    while ($row = loc_db_fetch_array($result)) {
        return "$row[0]";
    }
    return "";
    //$conn
}

function getOrgSlogan($orgID)
{
    $strSQL = "SELECT org_slogan FROM org.org_details WHERE org_id = $orgID";
    $result = executeSQLNoParams($strSQL);
    while ($row = loc_db_fetch_array($result)) {
        return "$row[0]";
    }
    return "";
    //$conn
}

function getOrgPstlAddrs($orgid)
{
    $sqlStr = "select pstl_addrs from org.org_details where org_id = "
        . $orgid . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return "$row[0]";
    }
    return "";
}

function getOrgEmailAddrs($orgid)
{
    $sqlStr = "select email_addrsses from org.org_details where org_id = "
        . $orgid . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return "$row[0]";
    }
    return "";
}

function getOrgContactNos($orgid)
{
    $sqlStr = "select cntct_nos from org.org_details where org_id = "
        . $orgid . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return "$row[0]";
    }
    return "";
}

function getOrgWebsite($orgid)
{
    $sqlStr = "select websites from org.org_details where org_id = "
        . $orgid . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return "$row[0]";
    }
    return "";
}

function getOrgRptDetails($orgID)
{
    $strSQL = "SELECT org_name,pstl_addrs,cntct_nos,email_addrsses,websites,org_slogan,org_typ_id, gst.get_pssbl_val(org_typ_id) org_type, COALESCE(org_logo,'') FROM org.org_details WHERE org_id = $orgID";
    $result = executeSQLNoParams($strSQL);
    return $result;
    //$conn
}

function getDfltOrgID()
{
    $strSQL = "SELECT org_id FROM org.org_details 
        ORDER BY org_id LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSQL);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getAppID($appnm, $srcmodule)
{
    $sqlStr = "select app_id from wkf.wkf_apps where 
        lower(app_name)=lower('" . loc_db_escape_string($appnm) . "') and 
        lower(source_module)=lower('" . loc_db_escape_string($srcmodule) . "')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function get_MyActiveInbxTtls()
{
    //global $usrID;
    global $user_Name;

    $user_Name = $_SESSION['UNAME'];

    $prsnID = getUserPrsnID($user_Name);
    $wherecls = "";

    $wherecls .= " AND (a.is_action_done = '0')";
    $extrWhr = " AND (a.to_prsn_id = " . $prsnID . ")";
    $sqlStr = "SELECT count(1) 
  FROM wkf.wkf_actual_msgs_routng a, wkf.wkf_actual_msgs_hdr b, wkf.wkf_apps c
WHERE ((c.app_id=b.app_id) AND (a.msg_id=b.msg_id)" . $extrWhr . "$wherecls)";
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function createWkfApp($applNm, $srcMdl, $appdesc)
{
    global $usrID;

    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO wkf.wkf_apps(
            app_name, source_module, app_desc, created_by, creation_date) " .
        "VALUES ('" . loc_db_escape_string($applNm) . "', '" . loc_db_escape_string($srcMdl) .
        "', '" . loc_db_escape_string($appdesc) . "', " . $usrID . ", '" . $dateStr . "')";
    execUpdtInsSQL($insSQL);
}

function updateWkfApp($appID, $applNm, $srcMdl, $appdesc)
{
    //    global $usrID;
    //    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE wkf.wkf_apps SET 
            app_name='" . loc_db_escape_string($applNm) . "', source_module='" . loc_db_escape_string($srcMdl) .
        "', app_desc='" . loc_db_escape_string($appdesc) .
        "' WHERE app_id = " . $appID;
    execUpdtInsSQL($insSQL);
}

function deleteWkfApp($appID, $appNm = "")
{
    $affctd1 = 0;
    $affctd2 = 0;
    $affctd3 = 0;

    $insSQL = "DELETE FROM wkf.wkf_apps_n_hrchies WHERE app_id = " . $appID;
    $affctd1 += execUpdtInsSQL($insSQL, "App Name:" . $appNm);
    $insSQL = "DELETE FROM wkf.wkf_apps_actions WHERE app_id = " . $appID;
    $affctd2 += execUpdtInsSQL($insSQL, "App Name:" . $appNm);
    $insSQL = "DELETE FROM wkf.wkf_apps WHERE app_id = " . $appID;
    $affctd3 += execUpdtInsSQL($insSQL, "App Name:" . $appNm);

    if ($affctd3 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 App Hierarchy(ies)!";
        $dsply .= "<br/>$affctd2 App Action(s)!";
        $dsply .= "<br/>$affctd3 App Header(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function createWkfAppAction($actionNm, $sqlStmnt, $appID, $exctbl, $webURL, $isdiag, $desc, $isadmnonly = "0")
{
    global $usrID;

    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO wkf.wkf_apps_actions(
            action_performed_nm, sql_stmnt, created_by, creation_date, 
            last_update_by, last_update_date, app_id, executable_file_nm, 
            web_url, is_web_dsply_diag, action_desc, is_admin_only) " .
        "VALUES ('" . loc_db_escape_string($actionNm) . "', '" . loc_db_escape_string($sqlStmnt) .
        "', " . $usrID . ", '" . $dateStr . "', " . $usrID . ", '" . $dateStr . "', $appID, 
            '" . loc_db_escape_string($exctbl) . "', '" . loc_db_escape_string($webURL) .
        "', '$isdiag','" . loc_db_escape_string($desc) . "','" . loc_db_escape_string($isadmnonly) . "')";
    return execUpdtInsSQL($insSQL);
}

function updateWkfAppAction($actionID, $actionNm, $sqlStmnt, $appID, $exctbl, $webURL, $isdiag, $desc, $isadmnonly = "0")
{
    global $usrID; //action_sql_id
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE wkf.wkf_apps_actions SET 
            action_performed_nm='" . loc_db_escape_string($actionNm) . "', 
            sql_stmnt='" . loc_db_escape_string($sqlStmnt) . "',
            last_update_by=" . $usrID . ",
            last_update_date='" . loc_db_escape_string($dateStr) . "',
            app_id=" . $appID . ",
            executable_file_nm='" . loc_db_escape_string($exctbl) . "',
            is_web_dsply_diag='" . loc_db_escape_string($isdiag) . "',
            action_desc='" . loc_db_escape_string($desc) . "',
            is_admin_only='" . loc_db_escape_string($isadmnonly) . "',
            web_url='" . loc_db_escape_string($webURL) .
        "' WHERE action_sql_id = " . $actionID;
    return execUpdtInsSQL($insSQL);
}

function deleteWkfAppAction($actionID, $actnNm = "")
{
    $insSQL = "DELETE FROM wkf.wkf_apps_actions WHERE action_sql_id = " . $actionID;
    $affctd1 = execUpdtInsSQL($insSQL, "Action Name:" . $actnNm);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 App Action(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getWkfMsgID()
{
    //echo "uname_".$username;
    $sqlStr = "select nextval('wkf.ntf_actual_msgs_hdr_msg_id_seq'::regclass);";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getWkfMsgHdrData($wfkMsgID)
{
    $sqlStr = "select msg_id, msg_hdr, msg_body, created_by, creation_date, herchy_id, 
       msg_typ, app_id, msg_status, src_doc_type, src_doc_id, last_update_by, 
       last_update_date, attchments, attchments_desc  
       from wkf.wkf_actual_msgs_hdr where msg_id = " . $wfkMsgID;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function getWkfMsgRtngData($wfkRtngID)
{
    $sqlStr = "select msg_id, from_prsn_id, to_prsn_id, date_sent, created_by, creation_date, 
       routing_id, msg_status, action_to_perform, is_action_done, who_prfmd_action, 
       date_action_ws_prfmd, status_aftr_action, nxt_action_to_prfm, 
       last_update_by, last_update_date, who_prfms_next_action, action_comments, 
       to_prsns_hrchy_level, prs.get_prsn_name(a.to_prsn_id)||' ('||prs.get_prsn_loc_id(a.to_prsn_id)||')' 
       from wkf.wkf_actual_msgs_routng a where routing_id = " . $wfkRtngID;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function getNextApprvrInMnlHrchy($hrchyID, $curHrchyLvl)
{
    $sqlStr = "SELECT person_id from wkf.wkf_manl_hierarchy_details " .
        "where ((is_enabled = '1' and hrchy_level > " . $curHrchyLvl . ") AND (hierarchy_id = " . $hrchyID . ")) "
        . "ORDER BY hrchy_level ASC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getNextMnlHrchyLvlID($hrchyID, $curHrchyLvl)
{
    $sqlStr = "SELECT mnl_hrchy_det_id from wkf.wkf_manl_hierarchy_details " .
        "where ((is_enabled = '1' and hrchy_level > " . $curHrchyLvl . ") AND (hierarchy_id = " . $hrchyID . ")) "
        . "ORDER BY hrchy_level ASC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getNextApprvrsInMnlHrchy($hrchyID, $curHrchyLvl)
{
    $sqlStr = "SELECT CASE WHEN b.person_id IS NULL THEN c.person_id ELSE b.person_id END "
        . " from wkf.wkf_manl_hierarchy_details c "
        . "LEFT OUTER JOIN wkf.wkf_apprvr_group_members b ON (b.apprvr_group_id=c.apprvr_group_id) " .
        " WHERE c.hrchy_level = COALESCE((select a.hrchy_level from wkf.wkf_manl_hierarchy_details a "
        . "where ((a.is_enabled = '1' and a.hrchy_level > " . $curHrchyLvl .
        ") AND (a.hierarchy_id = " . $hrchyID . ")) "
        . "ORDER BY a.hrchy_level ASC LIMIT 1 OFFSET 0),-999999999) "
        . "AND (c.hierarchy_id = " . $hrchyID . " and c.is_enabled = '1')";
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function getMxRoutingID($srcDocID, $srcDocType = "Personal Records Change")
{
    $selSQL = "SELECT MAX(b.routing_id)
  FROM wkf.wkf_actual_msgs_hdr a, wkf.wkf_actual_msgs_routng b
  WHERE a.msg_id=b.msg_id and a.src_doc_type='" . $srcDocType . "' 
  and a.src_doc_id=" . $srcDocID;
    $result1 = executeSQLNoParams($selSQL);
    while ($row = loc_db_fetch_array($result1)) {
        $routingID = (float) $row[0];
        return $routingID;
    }
    return -1;
}

function getTrnsBatchID($batchname, $orgid)
{
    $sqlStr = "select batch_id from accb.accb_trnsctn_batches where lower(batch_name) = '" .
        loc_db_escape_string(strtolower($batchname)) . "' and org_id = " . $orgid;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getTrnsBatchName($batchid)
{
    $sqlStr = "select batch_name from accb.accb_trnsctn_batches where batch_id = " .
        $batchid . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getAccntID($accntname, $orgid)
{
    $sqlStr = "select accnt_id from accb.accb_chart_of_accnts where ((lower(accnt_name) = '" .
        loc_db_escape_string(strtolower($accntname)) . "' or lower(accnt_num) = '" .
        loc_db_escape_string(strtolower($accntname)) . "') and org_id = " . $orgid . ")";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getAccntIDUseNum($accntnum, $orgid)
{
    $sqlStr = "select accnt_id from accb.accb_chart_of_accnts where (lower(accnt_num) = '" .
        loc_db_escape_string(strtolower($accntnum)) . "' and org_id = " . $orgid . ")";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getAccntName($accntid)
{
    $sqlStr = "select accnt_name from accb.accb_chart_of_accnts where accnt_id = " .
        $accntid . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getAccntNum($accntid)
{
    $sqlStr = "select accnt_num from accb.accb_chart_of_accnts where accnt_id = " .
        $accntid . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getAccntType($accntid)
{
    $sqlStr = "select accnt_type from accb.accb_chart_of_accnts where accnt_id = " .
        $accntid . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function isAccntContra($accntid)
{
    $sqlStr = "select is_contra from accb.accb_chart_of_accnts where accnt_id = " .
        $accntid . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getNewFSRptRunID()
{
    $strSql = "select nextval('rpt.rpt_accb_data_storage_accb_rpt_runid_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getMinFSRptRunID()
{
    $strSql = "select MIN(accb_rpt_runid) FROM rpt.rpt_accb_data_storage tbl1
  WHERE tbl1.gnrl_data1='No.' and tbl1.gnrl_data11 ilike '%Opening%' 
  and age(now(), to_timestamp(rpt_run_date, 'YYYY-MM-DD HH24:MI:SS')) <= INTERVAL '1 days'";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function incrsOrDcrsAccnt($accntid, $dbtOrCrdt)
{
    $accntType = getAccntType($accntid);
    $isContra = isAccntContra($accntid);
    if ($isContra == "0") {
        if (($accntType == "A" || $accntType == "EX") && $dbtOrCrdt == "Debit") {
            return "INCREASE";
        } else if (($accntType == "A" || $accntType == "EX") && $dbtOrCrdt == "Credit") {
            return "DECREASE";
        } else if (($accntType == "EQ" || $accntType == "R" || $accntType == "L") && $dbtOrCrdt == "Credit") {
            return "INCREASE";
        } else if (($accntType == "EQ" || $accntType == "R" || $accntType == "L") && $dbtOrCrdt == "Debit") {
            return "DECREASE";
        }
    } else {
        if (($accntType == "A" || $accntType == "EX") && $dbtOrCrdt == "Debit") {
            return "DECREASE";
        } else if (($accntType == "A" || $accntType == "EX") && $dbtOrCrdt == "Credit") {
            return "INCREASE";
        } else if (($accntType == "EQ" || $accntType == "R" || $accntType == "L") && $dbtOrCrdt == "Credit") {
            return "DECREASE";
        } else if (($accntType == "EQ" || $accntType == "R" || $accntType == "L") && $dbtOrCrdt == "Debit") {
            return "INCREASE";
        }
    }
    return "";
}

function rho_add_date($givendate, $day = 0, $mth = 0, $yr = 0)
{
    $cd = strtotime($givendate);
    $newdate = date(
        'Y-m-d h:i:s',
        mktime(date('h', $cd), date('i', $cd), date('s', $cd), date('m', $cd) + $mth, date('d', $cd) + $day, date('Y', $cd) + $yr)
    );
    return $newdate;
}

function rho_getDateInDMY($givendate)
{
    $newdate = rho_add_date($givendate);
    $date = new DateTime($newdate);
    return $date->format('d-M-Y');
}

function createWelcomeMsg($username)
{
    global $app_name;
    $msg_id = getWkfMsgID();
    $appID = getAppID('Login', 'System Administration');

    $prsnid = getUserPrsnID($username);
    $fullNm = getPrsnFullNm($prsnid);
    $userID = getUserID($username);
    $sccflLg = getLastSccflLgnMsg($userID);
    $fldLg = getLastFailedLgn($userID);
    $msghdr = "Welcome $fullNm";
    $msgbody = "Welcome $fullNm to $app_name. 
        <br/><br/>Your last successful login was $sccflLg .
        <br/><br/>Your last failed login was $fldLg";
    $msgtyp = "Informational";
    $msgsts = "0";
    $srcdoctyp = "Login";
    $srcdocid = -1;
    $hrchyid = -1;
    logSessionErrs($msgbody);
    createWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid);
    routWkfMsg($msg_id, -1, $prsnid, $userID, 'Initiated', 'Acknowledge');
}

function createSysInboxMsg($prsnid, $msghdr, $msgbody, $attchmnts = "", $attchmnts_desc = "", $fromPrsnID = -1)
{
    global $app_url;
    global $fldrPrfx;
    global $pemDest;
    global $usrID;
    if ($attchmnts != "") {
        $attchmnts = str_replace($app_url, $fldrPrfx, $attchmnts);
    }
    if ($attchmnts_desc != "") {
        $atcArry = explode(";", $attchmnts_desc);
        $attchmnts_desc = "";
        for ($i = 0; $i < count($atcArry); $i++) {
            $tmpStr = str_replace($app_url . $pemDest, "", $atcArry[$i]);
            /* if (strlen($tmpStr) > 25) {
              $tmpStr = substr($tmpStr, 0, 25);
              } */
            $attchmnts_desc .= chunk_split($tmpStr, 25, "<br/>") . ";";
        }
    }
    $msg_id = getWkfMsgID();
    $appID = getAppID('System Inbox', 'System Administration');
    $msgtyp = "Informational";
    $msgsts = "0";
    $srcdoctyp = "System Inbox";
    $srcdocid = -1;
    $hrchyid = -1;
    /* $msgs = "";
      $numargs = func_num_args();
      $msgs .= "Number of arguments: $numargs \n";
      $arg_list = func_get_args();
      for ($i = 0; $i < $numargs; $i++) {
      $msgs .= "Argument $i is: " . $arg_list[$i] . "\n";
      }
      logSessionErrs($msgs); */
    createWkfMsg($msg_id, $msghdr, $msgbody, $usrID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
    routWkfMsg($msg_id, $fromPrsnID, $prsnid, $usrID, 'Initiated', 'Acknowledge');
}

function createWkfMsg(
    $msg_id,
    $msghdr,
    $msgbody,
    $userID,
    $appID,
    $msgtyp,
    $msgsts,
    $srcdoctyp,
    $srcdocid,
    $hrchyid,
    $attchmnts = "",
    $attchmnts_desc = ""
) {
    $dateStr = getDB_Date_time();
    $sqlStr = "INSERT INTO wkf.wkf_actual_msgs_hdr(
            msg_id, msg_hdr, msg_body, created_by, creation_date, herchy_id, 
            msg_typ, app_id, msg_status, src_doc_type, src_doc_id, last_update_by, 
            last_update_date, attchments, attchments_desc) " .
        "VALUES ($msg_id, '" . loc_db_escape_string($msghdr) . "', '" . loc_db_escape_string($msgbody) . "', 
            $userID, '" . $dateStr . "', $hrchyid, '" . loc_db_escape_string($msgtyp) . "', 
            $appID, '$msgsts','" . loc_db_escape_string($srcdoctyp) . "',$srcdocid, 
            $userID, '" . $dateStr . "', '" . loc_db_escape_string($attchmnts) .
        "', '" . loc_db_escape_string($attchmnts_desc) . "')";
    return execUpdtInsSQL($sqlStr);
}

function updateWkfMsg(
    $msg_id,
    $msghdr,
    $msgbody,
    $userID,
    $appID,
    $msgtyp,
    $msgsts,
    $srcdoctyp,
    $srcdocid,
    $hrchyid,
    $attchmnts = "",
    $attchmnts_desc = ""
) {
    $dateStr = getDB_Date_time();
    $sqlStr = "UPDATE wkf.wkf_actual_msgs_hdr SET
            msg_hdr='" . loc_db_escape_string($msghdr) . "', msg_body='" . loc_db_escape_string($msgbody) .
        "', herchy_id=$hrchyid, msg_typ='" . loc_db_escape_string($msgtyp) .
        "', app_id=$appID, msg_status='$msgsts', src_doc_type='" . loc_db_escape_string($srcdoctyp) .
        "', src_doc_id=$srcdocid, last_update_by = " . $userID .
        ", last_update_date = '" . $dateStr . "', "
        . "attchments = '" . loc_db_escape_string($attchmnts) . "', "
        . "attchments_desc = '" . loc_db_escape_string($attchmnts_desc) . "' WHERE msg_id = $msg_id";
    return execUpdtInsSQL($sqlStr);
}

function updateWkfMsgStatus($msg_id, $msgsts, $userID)
{
    $dateStr = getDB_Date_time();
    $sqlStr = "UPDATE wkf.wkf_actual_msgs_hdr SET
        msg_status='$msgsts', 
            last_update_by = " . $userID .
        ", last_update_date = '" . $dateStr . "' WHERE msg_id = $msg_id";
    return execUpdtInsSQL($sqlStr);
}

function updateWkfMsgBdy($msg_id, $msgbodyAddOn, $userID)
{
    $dateStr = getDB_Date_time();
    $sqlStr = "UPDATE wkf.wkf_actual_msgs_hdr SET
            msg_body='" . loc_db_escape_string($msgbodyAddOn) .
        "' || msg_body, last_update_by = " . $userID .
        ", last_update_date = '" . $dateStr . "' WHERE msg_id = $msg_id";
    return execUpdtInsSQL($sqlStr);
}

function routWkfMsg($msg_id, $frmID, $toID, $userID, $curStatus, $actnToPrfm, $hrchylvl = 1, $actCmmnts = "")
{
    $dateStr = getDB_Date_time();
    $sqlStr = "INSERT INTO wkf.wkf_actual_msgs_routng(
            msg_id, from_prsn_id, to_prsn_id, date_sent, 
            created_by, creation_date, msg_status, action_to_perform, last_update_by, 
            last_update_date, to_prsns_hrchy_level, action_comments) 
            VALUES ($msg_id, $frmID, $toID, '$dateStr', $userID, 
            '$dateStr', '" . loc_db_escape_string($curStatus) . "', '" .
        loc_db_escape_string($actnToPrfm) . "', $userID, '$dateStr', $hrchylvl, 
                '" . loc_db_escape_string($actCmmnts) . "')";
    return execUpdtInsSQL($sqlStr);
}

function getEmlDetailsAftrActn($srcdoctyp, $srcdocid)
{
    $selSQL = "SELECT b.to_prsn_id, a.msg_hdr, a.msg_body, COALESCE((select z.action_comments
        from wkf.wkf_actual_msgs_routng z WHERE z.msg_id=b.msg_id ORDER BY z.routing_id DESC LIMIT 1 OFFSET 0),'NONE'), b.msg_id  
  FROM wkf.wkf_actual_msgs_hdr a, wkf.wkf_actual_msgs_routng b
  WHERE a.msg_id=b.msg_id and a.src_doc_type='" . $srcdoctyp . "' 
  and a.src_doc_id=" . $srcdocid . "   
  and b.is_action_done='1' and b.action_comments!='' 
  GROUP BY 1,2,3,4,5 
  HAVING b.msg_id=(Select MAX(z.msg_id) from wkf.wkf_actual_msgs_hdr z WHERE z.src_doc_id=" . $srcdocid . " and z.src_doc_type='" . $srcdoctyp . "')
     ORDER BY 1 DESC";
    return executeSQLNoParams($selSQL);
}

function getEmlDetailsB4Actn($srcdoctyp, $srcdocid)
{
    $selSQL = "SELECT b.to_prsn_id, a.msg_hdr, a.msg_body, COALESCE((select z.action_comments
        from wkf.wkf_actual_msgs_routng z WHERE z.msg_id=b.msg_id ORDER BY z.routing_id DESC LIMIT 1 OFFSET 0),'NONE'), b.msg_id  
  FROM wkf.wkf_actual_msgs_hdr a, wkf.wkf_actual_msgs_routng b
  WHERE a.msg_id=b.msg_id and a.src_doc_type='" . $srcdoctyp . "' 
  and a.src_doc_id=" . $srcdocid . " 
  GROUP BY 1,2,3,4,5 
  HAVING b.msg_id=(Select MAX(z.msg_id) from wkf.wkf_actual_msgs_hdr z WHERE z.src_doc_id=" . $srcdocid . " and z.src_doc_type='" . $srcdoctyp . "')
     ORDER BY 1 DESC";
    return executeSQLNoParams($selSQL);
}

function updtWkfMsgRtng($rtngID, $usrPrsnID, $nwstatus, $nwAction, $userID)
{
    $dateStr = getDB_Date_time();
    $sqlStr = "UPDATE wkf.wkf_actual_msgs_routng SET is_action_done = '1', who_prfmd_action = $usrPrsnID, 
        date_action_ws_prfmd = '$dateStr', 
    status_aftr_action = '" . loc_db_escape_string($nwstatus) . "', 
        nxt_action_to_prfm = '" . loc_db_escape_string($nwAction) . "', 
            last_update_by = $userID, 
    last_update_date = '$dateStr' WHERE routing_id = " . $rtngID;
    return execUpdtInsSQL($sqlStr);
}

function updtWkfMsgRtngUsngLvl($msgID, $hrchylvl, $usrPrsnID, $nwstatus, $nwAction, $userID)
{
    $dateStr = getDB_Date_time();
    $sqlStr = "UPDATE wkf.wkf_actual_msgs_routng SET is_action_done = '1', who_prfmd_action = $usrPrsnID, 
        date_action_ws_prfmd = '$dateStr', 
    status_aftr_action = '" . loc_db_escape_string($nwstatus) . "', 
        nxt_action_to_prfm = '" . loc_db_escape_string($nwAction) . "', 
            last_update_by = $userID, 
    last_update_date = '$dateStr' WHERE msg_id = " . $msgID . " and to_prsns_hrchy_level = $hrchylvl";
    return execUpdtInsSQL($sqlStr);
}

function updtWkfMsgReasgnRtng($rtngID, $fromID, $toID, $userID)
{
    $dateStr = getDB_Date_time();
    $sqlStr = "UPDATE wkf.wkf_actual_msgs_routng 
            SET from_prsn_id=$fromID, to_prsn_id=$toID, last_update_date = '$dateStr', last_update_by = $userID
            , date_sent='" . $dateStr . "'
            WHERE routing_id=" . $rtngID;
    return execUpdtInsSQL($sqlStr);
}

function updtWkfMsgRtngCmnts($rtngID, $msgCmmnts, $userID)
{
    $dateStr = getDB_Date_time();
    $sqlStr = "UPDATE wkf.wkf_actual_msgs_routng 
            SET action_comments = '" . loc_db_escape_string($msgCmmnts) . "' || action_comments, last_update_date = '$dateStr', last_update_by = $userID
            WHERE routing_id=" . $rtngID;
    return execUpdtInsSQL($sqlStr);
}

function updtWkfMsgRtngStatus($rtngID, $msgStst, $userID)
{
    $dateStr = getDB_Date_time();
    $sqlStr = "UPDATE wkf.wkf_actual_msgs_routng 
            SET is_action_done = '" . loc_db_escape_string($msgStst) . "', last_update_date = '$dateStr', last_update_by = $userID
            WHERE routing_id=" . $rtngID;
    return execUpdtInsSQL($sqlStr);
}

function updtWkfMsgAllUnclsdRtng($msgID, $usrPrsnID, $nwstatus, $nwAction, $userID)
{
    $dateStr = getDB_Date_time();
    $sqlStr = "UPDATE wkf.wkf_actual_msgs_routng SET is_action_done = '1', who_prfmd_action = $usrPrsnID, 
        date_action_ws_prfmd = '$dateStr', 
    status_aftr_action = '" . loc_db_escape_string($nwstatus) . "', 
        nxt_action_to_prfm = '" . loc_db_escape_string($nwAction) . "', 
            last_update_by = $userID, 
    last_update_date = '$dateStr' WHERE msg_id = " . $msgID . " and is_action_done = '0'";
    return execUpdtInsSQL($sqlStr);
}

function getUserID($username)
{
    //echo "uname_".$username;
    $sqlStr = "select user_id from sec.sec_users where 
        lower(user_name)=lower('" . loc_db_escape_string($username) .
        "') or lower(coalesce(prs.get_prsn_email(person_id),''))=lower('" . loc_db_escape_string($username) . "')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getUserName($userid)
{
    //echo "uname_".$username;
    $sqlStr = "select user_name from sec.sec_users where 
        user_id= $userid";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getUserPrsnID($username)
{
    $sqlStr = "select person_id from sec.sec_users where 
        lower(user_name)=lower('" . loc_db_escape_string($username) . "')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getUserStoreIDOLD($userid, $orgID)
{
    $sqlStr = "select y.subinv_id 
              from inv.inv_itm_subinventories y, inv.inv_user_subinventories z
              where y.subinv_id=z.subinv_id and 
              y.allow_sales = '1' and z.user_id = " . $userid .
        "and y.org_id= " . $orgID . " order by 1 LIMIT 1 OFFSET 0 ";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getUserStoreID()
{
    global $usrID;
    global $orgID;
    $strSql = "SELECT scm.getUserStoreID(" . $usrID . ", " . $orgID . ") ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return ((int) $row[0]);
    }
    return -1;
}

function getUserStorePkeyID($p_SToreID)
{
    global $usrID;
    $strSql = "SELECT scm.getuserstorepkeyid(" . $usrID . ", " . $p_SToreID . ") ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return ((int) $row[0]);
    }
    return -1;
}

function getActiveInbxMsgs($prsnID)
{
    $sqlStr = "select wkf.get_active_inbx_msgs(" . loc_db_escape_string($prsnID) . ")";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function getUnreadChatMsgs($prsnID)
{
    $sqlStr = "select self.get_unread_chat_msgs(" . loc_db_escape_string($prsnID) . ")";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function getAsgndMdls($prsnID)
{
    $sqlStr = "select sec.get_asgnd_mdls(" . loc_db_escape_string($prsnID) . ")";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function getUnreadArticles($prsnID)
{
    $sqlStr = "select self.get_unread_articles(" . loc_db_escape_string($prsnID) . ")";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function getHmPgIndicators($prsnID)
{
    $rslt = array(0, 0, 0, 0);
    $sqlStr = "select wkf.get_active_inbx_msgs(" . loc_db_escape_string($prsnID) . ") inbx, "
        . "self.get_unread_chat_msgs(" . loc_db_escape_string($prsnID) . ") chat,"
        . "self.get_unread_articles(" . loc_db_escape_string($prsnID) . ") artcl,"
        . "sec.get_asgnd_mdls(" . loc_db_escape_string($prsnID) . ") mdls";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        $rslt[0] = (float) $row[0];
        $rslt[1] = (float) $row[1];
        $rslt[2] = (float) $row[2];
        $rslt[3] = (float) $row[3];
    }
    return $rslt;
}

function getUserPrsnID1($userID)
{
    $sqlStr = "select person_id from sec.sec_users where 
        user_id=$userID";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getNewUserPrsnID($username)
{
    $sqlStr = "select person_id from prs.prsn_names_nos a where (lower(a.local_id_no) = lower('"
        . loc_db_escape_string($username) . "') or lower(a.email) = lower('"
        . loc_db_escape_string($username) . "')) and lower(a.local_id_no) NOT IN "
        . "(Select lower(b.user_name) from sec.sec_users b) ";
    //. "and person_id NOT IN (Select c.person_id from sec.sec_users c)";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getNewUserPrsnIDUseMail($username)
{
    $sqlStr = "select person_id from prs.prsn_names_nos a where lower(a.email) = lower('"
        . loc_db_escape_string($username) . "') and lower(a.email) NOT IN "
        . "(Select lower(b.user_name) from sec.sec_users b) ";
    //. "and person_id NOT IN (Select c.person_id from sec.sec_users c)";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getLtstRecPkIDGnrl($tblNm, $pkeyCol)
{
    $sqlStr = "select " . $pkeyCol . " from " . $tblNm . " ORDER BY 1 DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return ((float) $row[0]) + 1;
    }
    return 1000;
}

function getLtstPrsnIDNoGnrl()
{
    global $orgID;
    $sqlStr = "select count(person_id) from prs.prsn_names_nos WHERE org_id=" . $orgID . "";

    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return str_pad((((float) $row[0]) + 1) . "", 4, '0', STR_PAD_LEFT);
    }
    return "0001";
}

function getLtstPrsnIDNoInPrfxGnrl($prfxTxt)
{
    global $orgID;
    $sqlStr = "select count(person_id) from prs.prsn_names_nos WHERE org_id=" . $orgID .
        " and local_id_no ilike '" . loc_db_escape_string($prfxTxt) . "%'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return str_pad((((float) $row[0]) + 1) . "", 4, '0', STR_PAD_LEFT);
    }
    return "0001";
}

function getLastPrsnIDNoGnrl()
{
    global $orgID;
    $sqlStr = "select (chartonumeric(local_id_no) + 1) from prs.prsn_names_nos 
        WHERE org_id=" . $orgID . "
      ORDER BY chartonumeric(local_id_no) DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return str_pad((((float) $row[0]) + 1) . "", 5, '0', STR_PAD_LEFT);
    }
    return "00001";
}

function getNewLocIDNumberGnrl($locIDTextBox, $iDPrfxComboBox)
{
    $tst = "0001";
    if (getEnbldPssblValID("Yes", getLovID("Person ID No. Prefix Determines ID Serial No.")) > 0) {
        if ($iDPrfxComboBox === "") {
            $tst = getLastPrsnIDNoGnrl();
        } else {
            $tst = getLtstPrsnIDNoInPrfxGnrl($iDPrfxComboBox);
        }
    } else {
        if ($iDPrfxComboBox === "") {
            $tst = getLastPrsnIDNoGnrl();
        } else {
            $tst = getLtstPrsnIDNoGnrl();
        }
    }
    if (strlen($tst) < 4) {
        $tst = str_pad($tst, 4, '0', STR_PAD_LEFT);
    }
    return $iDPrfxComboBox . $tst;
}

function createPrsnsTypeGnrl($prsnid, $rsn, $date1, $date2, $futhDet, $prsntyp)
{
    global $usrID;
    $insSQL = "INSERT INTO pasn.prsn_prsntyps(" .
        "person_id, prn_typ_asgnmnt_rsn, valid_start_date, valid_end_date, " .
        "created_by, creation_date, last_update_by, last_update_date, " .
        "further_details, prsn_type)" .
        "VALUES (" . $prsnid . ", '" . loc_db_escape_string($rsn) .
        "', '" . loc_db_escape_string($date1) . "', '" . loc_db_escape_string($date2) . "', " .
        "" . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " .
        "'" . loc_db_escape_string($futhDet) . "', '" . loc_db_escape_string($prsntyp) . "')";
    execUpdtInsSQL($insSQL);
}

function createPrsnBasicGnrl(
    $frstnm,
    $surname,
    $othnm,
    $title,
    $loc_id,
    $orgid,
    $gender,
    $marsts,
    $dob,
    $pob,
    $rlgn,
    $resaddrs,
    $pstladrs,
    $email,
    $tel,
    $mobl,
    $fax,
    $hometwn,
    $ntnlty,
    $imgLoc,
    $lnkdFrmID,
    $lnkdFrmSiteID,
    $lnkdFirmNm,
    $lnkdFirmSiteName
) {
    global $usrID;
    $insSQL = "INSERT INTO prs.prsn_names_nos(" .
        "created_by, creation_date, last_update_by, last_update_date, " .
        "first_name, sur_name, other_names, title, local_id_no, org_id, " .
        "gender, marital_status, date_of_birth, place_of_birth, religion, " .
        "res_address, pstl_addrs, email, cntct_no_tel, cntct_no_mobl, " .
        "cntct_no_fax, hometown, nationality, img_location, lnkd_firm_org_id, 
            lnkd_firm_site_id, new_company, new_company_loc)" .
        "VALUES (" . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " .
        $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($frstnm) . "', " .
        "'" . loc_db_escape_string($surname) . "', '" . loc_db_escape_string($othnm) .
        "', '" . loc_db_escape_string($title) . "', '" . loc_db_escape_string($loc_id) .
        "', " . $orgid . ", '" . loc_db_escape_string($gender) . "', " .
        "'" . loc_db_escape_string($marsts) . "', '" . $dob .
        "', '" . loc_db_escape_string($pob) . "', '" . loc_db_escape_string($rlgn) .
        "', '" . loc_db_escape_string($resaddrs) . "', " .
        "'" . loc_db_escape_string($pstladrs) . "', '" . loc_db_escape_string($email) .
        "', '" . loc_db_escape_string($tel) . "', '" . loc_db_escape_string($mobl) .
        "', '" . loc_db_escape_string($fax) . "', '" . loc_db_escape_string($hometwn) .
        "', '" . loc_db_escape_string($ntnlty) . "', '" . loc_db_escape_string($imgLoc) .
        "', " . $lnkdFrmID . ", " . $lnkdFrmSiteID .
        ", '" . loc_db_escape_string($lnkdFirmNm) . "', '" . loc_db_escape_string($lnkdFirmSiteName) . "')";
    execUpdtInsSQL($insSQL);
}

function getLastSccflLgnMsg($userID)
{
    $sqlStr = "select trim(to_char(to_timestamp(login_time,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') || '-Machine Details(' || host_mach_details || ')')  from sec.sec_track_user_logins where 
        user_id=" . $userID . " and was_lgn_atmpt_succsful='t' ORDER BY login_number DESC LIMIT 1 OFFSET 1";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getLastFailedLgn($userID)
{
    $sqlStr = "select trim(to_char(to_timestamp(login_time,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') || '-Machine Details(' || host_mach_details || ')')  from sec.sec_track_user_logins where 
        user_id=" . $userID . " and was_lgn_atmpt_succsful='f' ORDER BY login_number DESC LIMIT 1 OFFSET 1";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getPrsnFullNm($prsnID)
{
    $sqlStr = "select trim(title || ' ' || first_name || ' ' || other_names || ' ' || sur_name) from prs.prsn_names_nos where 
        person_id=" . $prsnID . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getPrsnOrgID($prsnID)
{
    //echo "uname_".$username;
    $sqlStr = "select org_id from prs.prsn_names_nos where 
        person_id=" . $prsnID . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getMinOrgID()
{
    //echo "uname_".$username;
    $sqlStr = "select min(org_id) from org.org_details";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getUserOrgID($username)
{
    return getPrsnOrgID(getUserPrsnID($username));
}

function getPersonID($locidno, $orgID = -1)
{
    $extrWhr = "";
    if ($orgID > 0) {
        $extrWhr = " and org_id=" . $orgID;
    }
    $sqlStr = "select person_id from prs.prsn_names_nos where (local_id_no = '" .
        loc_db_escape_string($locidno) . "')" . $extrWhr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getPersonIDUseMail($email, $orgID = -1)
{
    $extrWhr = "";
    if ($orgID > 0) {
        $extrWhr = " and org_id=" . $orgID;
    }
    $sqlStr = "select person_id from prs.prsn_names_nos where (email = '" .
        loc_db_escape_string($email) . "')" . $extrWhr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getPersonLocID($prsnID)
{
    $sqlStr = "select local_id_no from prs.prsn_names_nos where (person_id = " .
        $prsnID . ")";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getPersonImg($pkID)
{
    $sqlStr = "select COALESCE(img_location,'') from prs.prsn_names_nos WHERE (person_id=$pkID)";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return '';
}

function getPersonSlfSrvcsImg($pkID)
{
    $sqlStr = "select img_location from self.self_prsn_names_nos WHERE (person_id=$pkID)";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return '';
}

function getRoleID($locidno)
{
    $sqlStr = "select role_id from sec.sec_roles where (lower(role_name) = lower('" .
        loc_db_escape_string($locidno) . "'))";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getFrmtdDB_Date_time()
{
    $sqlStr = "select to_char(now(), 'DD-Mon-YYYY HH24:MI:SS')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return "$row[0]";
    }
    return "";
}

function getDB_Date_time()
{
    $sqlStr = "select to_char(now(), 'YYYY-MM-DD HH24:MI:SS')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return "$row[0]";
    }
    return "";
}

function getDB_time_Millisecond()
{
    $sqlStr = "select to_char(now(), 'MS')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return "$row[0]";
    }
    return "";
}

function getDB_Date_timeYMDHMS()
{
    $sqlStr = "select to_char(now(), 'YYMMDDHH24MISS')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return "$row[0]";
    }
    return "";
}

function getDB_Date_TimeIntvl($intrvl)
{
    $sqlStr = "select to_char(now()- interval '$intrvl', 'YYYY-MM-DD HH24:MI:SS')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return "$row[0]";
    }
    return "";
}

function getDB_Date_TmIntvlAdd($intrvl)
{
    $sqlStr = "select to_char(now()+ interval '$intrvl', 'YYYY-MM-DD HH24:MI:SS')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return "$row[0]";
    }
    return "";
}

function getDB_Date_TmIntvlAddSub($ymdtme, $intrvl, $addOrSbtrct)
{
    $sqlStr = "select to_char(to_timestamp('" . $ymdtme . "', 'DD-Mon-YYYY HH24:MI:SS') + interval '$intrvl', 'DD-Mon-YYYY HH24:MI:SS')";
    if ($addOrSbtrct == "Subtract") {
        $sqlStr = "select to_char(to_timestamp('" . $ymdtme . "', 'DD-Mon-YYYY HH24:MI:SS') - interval '$intrvl', 'DD-Mon-YYYY HH24:MI:SS')";
    }
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function cnvrtAllToDMYTm($inptDte)
{
    $sqlStr = "select gst.cnvrtAllToDMYTm('$inptDte')";
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return "$row[0]";
    }
    return "";
}

function cnvrtYMDTmToDMYTm($inptDte)
{
    $sqlStr = "select to_char(to_timestamp('$inptDte',
        'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return "$row[0]";
    }
    return "";
}

function cnvrtDMYTmToYMDTm($inptDte)
{
    $sqlStr = "select to_char(to_timestamp('$inptDte',
        'DD-Mon-YYYY HH24:MI:SS'),'YYYY-MM-DD HH24:MI:SS')";
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return "$row[0]";
    }
    return "";
}

function cnvrtDMYToYMD($inptDte)
{
    /* $original_date = "06 Apr 25 13:36";
      $pieces = explode(" ", $original_date);
      $new_date = date("Y-m-d",strtotime($pieces[2]." ".$pieces[1]." ".$pieces[0]));
     */
    $sqlStr = "select to_char(to_timestamp('$inptDte',
        'DD-Mon-YYYY'),'YYYY-MM-DD')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return "$row[0]";
    }
    return "";
}

function cnvrtYMDToDMY($inptDte)
{
    $sqlStr = "select to_char(to_timestamp('$inptDte',
        'YYYY-MM-DD'),'DD-Mon-YYYY')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return "$row[0]";
    }
    return "";
}

function cnvrtBoolToBitStr($testval)
{
    if ($testval) {
        return "1";
    } else {
        return "0";
    }
}

function cnvrtBitStrToBool($testval)
{
    if ($testval == "0") {
        return false;
    } else {
        return true;
    }
}

function restricted()
{
    $usrID = $_SESSION['USRID'];
    if ($usrID > 0) {
        echo "
<div id='rho_form'><H1 style=\"text-align:center; color:red;\">RESTRICTED AREA!!!</H1>
<p style=\"text-align:center; color:red;\"><b><i>Sorry, 
you do not have permission to access this page. 
Click to go <a class=\"rho-button\" href=\"javascript: showRoles();\">back</a> to your Priviledges</i></b></p></div>";
    } else {
        echo "
<div id='rho_form'><H1 style=\"text-align:center; color:red;\">RESTRICTED AREA!!!</H1>
<p style=\"text-align:center; color:red;\"><b><i>Sorry, 
you do not have permission to access this page. 
Click to go <a class=\"rho-button\" href=\"javascript: window.location='index.php';\">[Back]</a></i></b></p></div>";
    }
}

function getLovValuesItm(
    $searchWord,
    $searchIn,
    $offset,
    $limit_size,
    &$brghtsqlStr,
    $lovID,
    &$is_dynamic,
    $criteriaID,
    $criteriaID2,
    $criteriaID3
) {
    global $orgID;
    $lovNm = getLovNm($lovID);
    $strSql = "";
    $is_dynamic = false;
    $extrWhere = "";
    $ordrBy = "ORDER BY 1";
    if ($searchIn == "Item Code") {
        $extrWhere = "and item_code ilike '" . loc_db_escape_string($searchWord) . "'";
    } else {
        $extrWhere = "and item_desc ilike '" . loc_db_escape_string($searchWord) . "'";
    }
    $strSql = "SELECT item_code \"item code\", item_desc \"description\", 
        (SELECT uom_name FROM inv.unit_of_measure WHERE uom_id = base_uom_id) \"base uom\",
        selling_price \"selling price\", (SELECT code_name FROM scm.scm_tax_codes WHERE code_id = tax_code_id) \"tax code\",
        (SELECT code_name FROM scm.scm_tax_codes WHERE code_id = dscnt_code_id) \"discount code\",
        (SELECT code_name FROM scm.scm_tax_codes WHERE code_id = extr_chrg_id) \"extra charge\",
        total_qty \"total qty\", reservations, available_balance, item_id mt
  FROM inv.inv_itm_list WHERE 1=1 AND org_id = $orgID " . $extrWhere . "" . $ordrBy . " LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);

    $brghtsqlStr = $strSql;
    //echo $strSql;
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getUomCnvsnFactor($itmUomId, $valToCnvt)
{
    //echo "uname_".$username;

    $sqlStr = "SELECT COALESCE(cnvsn_factor,0)*$valToCnvt
  FROM inv.itm_uoms WHERE itm_uom_id = $itmUomId";
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return (int) 1 * $valToCnvt;
}

function getItmUomLovs($uomItemCode)
{
    $strSql = "SELECT a.itm_uom_id mt, (SELECT b.uom_name FROM inv.unit_of_measure b WHERE b.uom_id = a.uom_id)\"uom\",
            a.uom_id mt
            FROM inv.itm_uoms a WHERE a.item_id = (SELECT item_id FROM inv.inv_itm_list WHERE item_code = 
            '" . loc_db_escape_string($uomItemCode) . "') 
            union
            SELECT -1 mt, (SELECT b.uom_name FROM inv.unit_of_measure b WHERE b.uom_id = a.base_uom_id) uom, base_uom_id mt
            FROM inv.inv_itm_list a WHERE a.item_code = '" . loc_db_escape_string($uomItemCode) . "'";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function insertSpaces($str, $insChar, $maxChrWidth)
{
    $arrystr = str_split($str);
    $res = "";
    for ($i = 0; $i < count($arrystr); $i++) {
        $res .= $arrystr[$i];
        if ($i > 0 && !($i % $maxChrWidth)) {
            $res .= $insChar;
            //echo $res . $i % $maxChrWidth . $insChar . 'test';
        }
    }

    return $res;
}

function getModuleID($mdl_name)
{
    //Example module name 'Security'
    $sqlStr = "select module_id from sec.sec_modules where module_name = '" .
        loc_db_escape_string($mdl_name) . "'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getPrvldgID($prvldg_name, $ModuleName)
{
    //Example priviledge 'View Security Module'
    $sqlStr = "SELECT prvldg_id from sec.sec_prvldgs where (prvldg_name = '" .
        loc_db_escape_string($prvldg_name) . "' AND module_id = " .
        getModuleID($ModuleName) . ")";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function doSlctdRolesHvThisPrvldg($inp_prvldg_id)
{
    //Checks whether a given role 'system administrator' has a given priviledge
    global $ssnRoles;
    $slctdRl = ";" . $ssnRoles . ";";
    $sqlStr = "SELECT role_id FROM sec.sec_roles_n_prvldgs WHERE ((prvldg_id = " .
        $inp_prvldg_id . ") AND (trim('$slctdRl') ilike trim('%;' || role_id || ';%')) 
                AND (now() between to_timestamp(valid_start_date|| ' 00:00:00','YYYY-MM-DD HH24:MI:SS') " .
        "AND to_timestamp(valid_end_date || ' 23:59:59','YYYY-MM-DD HH24:MI:SS')))";
    $result = executeSQLNoParams($sqlStr);
    //echo $sqlStr;
    if (loc_db_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function doesRoleHvThisPrvldg($inp_role_id, $inp_prvldg_id)
{
    //Checks whether a given role 'system administrator' has a given priviledge
    $sqlStr = "SELECT role_id FROM sec.sec_roles_n_prvldgs WHERE ((prvldg_id = " .
        $inp_prvldg_id . ") AND (role_id = " . $inp_role_id .
        ") AND (now() between to_timestamp(valid_start_date|| ' 00:00:00','YYYY-MM-DD HH24:MI:SS') " .
        "AND to_timestamp(valid_end_date || ' 23:59:59','YYYY-MM-DD HH24:MI:SS')))";
    $result = executeSQLNoParams($sqlStr);
    if (loc_db_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function doCurRolesHvThsPrvldgs($prvldgnames, $mdlNm)
{
    global $Role_Set_IDs;
    $chkRslts = array_fill(0, count($prvldgnames), false);

    for ($i = 0; $i < count($Role_Set_IDs); $i++) {
        for ($j = 0; $j < count($prvldgnames); $j++) {
            if (doesRoleHvThisPrvldg($Role_Set_IDs[$i], getPrvldgID($prvldgnames[$j], $mdlNm)) == true) {
                $chkRslts[$j] = true;
            }
        }
    }
    for ($n = 0; $n < count($chkRslts); $n++) {
        if ($chkRslts[$n] == false) {
            return false;
        }
    }
    return true;
}

function test_prmssns($testdata, $mdlNm)
{
    global $ssnRoles;
    $sqlStr = "Select sec.test_prmssns('" .
        loc_db_escape_string($testdata) . "', '" .
        loc_db_escape_string($mdlNm) . "','" .
        loc_db_escape_string($ssnRoles) . "')";
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (((int) $row[0]) == 1 ? true : false);
    }
    return false;
    /* $dlmtrs = '~';
      $prldgs_to_test = explode($dlmtrs, $testdata);
      $chkRslts = array_fill(0, count($prldgs_to_test), false);
      for ($j = 0; $j < count($prldgs_to_test); $j++) {
      if (doSlctdRolesHvThisPrvldg(getPrvldgID($prldgs_to_test[$j], $mdlNm)) == true) {
      $chkRslts[$j] = true;
      }
      }
      for ($n = 0; $n < count($chkRslts); $n++) {
      if ($chkRslts[$n] == false) {
      return false;
      }
      }
      return true; */
}

function getSelfPgPrmssns($prsnid)
{
    global $ssnRoles;
    $rslts = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $sqlStr = "Select (Select MAX(lnkd_firm_org_id) from prs.prsn_names_nos where person_id=$prsnid) lnkd_firm_org_id, "
        . "sec.test_prmssns('View Self-Service', 'Self Service','" . loc_db_escape_string($ssnRoles) . "') canViewSelfsrvc, "
        . "sec.test_prmssns('View Elections', 'Self Service','" . loc_db_escape_string($ssnRoles) . "') canViewEvote, "
        . "sec.test_prmssns('View E-Library', 'Self Service','" . loc_db_escape_string($ssnRoles) . "') canViewElearn, "
        . "sec.test_prmssns('View Accounting', 'Accounting','" . loc_db_escape_string($ssnRoles) . "') canViewAcntng, "
        . "sec.test_prmssns('View Person', 'Basic Person Data','" . loc_db_escape_string($ssnRoles) . "') canViewPrsn, "
        . "sec.test_prmssns('View Internal Payments', 'Self Service','" . loc_db_escape_string($ssnRoles) . "') canViewIntrnlPay, "
        . "sec.test_prmssns('View Inventory Manager', 'Stores And Inventory Manager','" . loc_db_escape_string($ssnRoles) . "') canViewSales, "
        . "sec.test_prmssns('View My Appointments', 'Self Service','" . loc_db_escape_string($ssnRoles) . "') canViewVsts, "
        . "sec.test_prmssns('View Events And Attendance', 'Events And Attendance','" . loc_db_escape_string($ssnRoles) . "') canViewEvnts, "
        . "sec.test_prmssns('View My Bookings', 'Self Service','" . loc_db_escape_string($ssnRoles) . "') canViewHotel, "
        . "sec.test_prmssns('View My Appointments', 'Self Service','" . loc_db_escape_string($ssnRoles) . "') canViewClnc, "
        . "sec.test_prmssns('View Banking', 'Banking','" . loc_db_escape_string($ssnRoles) . "') canViewBnkng, "
        . "sec.test_prmssns('View My Performance', 'Self Service','" . loc_db_escape_string($ssnRoles) . "') canViewPrfmnc, "
        . "sec.test_prmssns('View Projects Management', 'Projects Management','" . loc_db_escape_string($ssnRoles) . "') canViewProjs, "
        . "sec.test_prmssns('View Vault Management', 'Vault Management','" . loc_db_escape_string($ssnRoles) . "') canViewVMS, "
        . "sec.test_prmssns('View Agent Registry', 'Agent Registry','" . loc_db_escape_string($ssnRoles) . "') canViewAgnt, "
        . "sec.test_prmssns('View Asset Tracking', 'Asset Tracking','" . loc_db_escape_string($ssnRoles) . "') canViewATrckr, "
        . "sec.test_prmssns('View System Administration', 'System Administration','" . loc_db_escape_string($ssnRoles) . "') canViewSysAdmin, "
        . "sec.test_prmssns('View Organization Setup', 'Organization Setup','" . loc_db_escape_string($ssnRoles) . "') canViewOrgStp, "
        . "sec.test_prmssns('View General Setup', 'General Setup','" . loc_db_escape_string($ssnRoles) . "') canViewLov, "
        . "sec.test_prmssns('View Workflow Manager', 'Workflow Manager','" . loc_db_escape_string($ssnRoles) . "') canViewWkf, "
        . "sec.test_prmssns('View Notices Admin', 'System Administration','" . loc_db_escape_string($ssnRoles) . "') canViewArtclAdmn, "
        . "sec.test_prmssns('View Reports And Processes', 'Reports And Processes','" . loc_db_escape_string($ssnRoles) . "') canViewRpts, 
               gst.get_pssbl_val_desc(gst.getenbldpssblvalid('Default Course/Objective/Programme Label%', gst.get_lov_id('All Other Performance Setups'))) prfmc_type, 
               gst.get_pssbl_val_desc(gst.getenbldpssblvalid('Create Links to Main App', gst.get_lov_id('All Other Self-Service Setups'))) self_link, 
               gst.get_pssbl_val_desc(gst.getenbldpssblvalid('Allow User Account Self-Registration', gst.get_lov_id('All Other General Setups'))) self_rgstr, "
        . "sec.test_prmssns('View Help Desk', 'Self Service','" . loc_db_escape_string($ssnRoles) . "') canViewHlpDsk, "
        . "sec.test_prmssns('View My Appraisal', 'Self Service','" . loc_db_escape_string($ssnRoles) . "') canViewApprsl";
    //echo $sqlStr;getEnbldPssblValID("Allow User Account Self-Registration", getLovID("All Other General Setups"))
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
        $rslts[19] = ((int) $row[19]);
        $rslts[20] = ((int) $row[20]);
        $rslts[21] = ((int) $row[21]);
        $rslts[22] = ((int) $row[22]);
        $rslts[23] = ((int) $row[23]);
        $rslts[24] = ($row[24]);
        $rslts[25] = ($row[25]);
        $rslts[26] = ($row[26]);
        $rslts[27] = ((int) $row[27]);
        $rslts[28] = ((int) $row[28]);
    }
    return $rslts;
}

function getHomePgPrmssns($prsnid)
{
    global $ssnRoles;
    $rslts = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $sqlStr = "Select (Select MAX(lnkd_firm_org_id) from prs.prsn_names_nos where person_id=$prsnid) lnkd_firm_org_id, "
        . "sec.test_prmssns('View Self-Service', 'Self Service','" . loc_db_escape_string($ssnRoles) . "') canViewSelfsrvc, "
        . "sec.test_prmssns('View e-Voting', 'e-Voting','" . loc_db_escape_string($ssnRoles) . "')+sec.test_prmssns('View Elections', 'Self Service','" . loc_db_escape_string($ssnRoles) . "') canViewEvote, "
        . "sec.test_prmssns('View e-Learning', 'e-Learning','" . loc_db_escape_string($ssnRoles) . "')+sec.test_prmssns('View e-Learning', 'Self Service','" . loc_db_escape_string($ssnRoles) . "') canViewElearn, "
        . "sec.test_prmssns('View Accounting', 'Accounting','" . loc_db_escape_string($ssnRoles) . "') canViewAcntng, "
        . "sec.test_prmssns('View Person', 'Basic Person Data','" . loc_db_escape_string($ssnRoles) . "') canViewPrsn, "
        . "sec.test_prmssns('View Internal Payments', 'Internal Payments','" . loc_db_escape_string($ssnRoles) . "') canViewIntrnlPay, "
        . "sec.test_prmssns('View Inventory Manager', 'Stores And Inventory Manager','" . loc_db_escape_string($ssnRoles) . "') canViewSales, "
        . "sec.test_prmssns('View Visits and Appointments', 'Visits and Appointments','" . loc_db_escape_string($ssnRoles) . "') canViewVsts, "
        . "sec.test_prmssns('View Events And Attendance', 'Events And Attendance','" . loc_db_escape_string($ssnRoles) . "') canViewEvnts, "
        . "sec.test_prmssns('View Hospitality Manager', 'Hospitality Management','" . loc_db_escape_string($ssnRoles) . "') canViewHotel, "
        . "sec.test_prmssns('View Clinic/Hospital', 'Clinic/Hospital','" . loc_db_escape_string($ssnRoles) . "') canViewClnc, "
        . "sec.test_prmssns('View Banking', 'Banking','" . loc_db_escape_string($ssnRoles) . "') canViewBnkng, "
        . "sec.test_prmssns('View Learning/Performance Management', 'Learning/Performance Management','" . loc_db_escape_string($ssnRoles) . "') canViewPrfmnc, "
        . "sec.test_prmssns('View Projects Management', 'Projects Management','" . loc_db_escape_string($ssnRoles) . "') canViewProjs, "
        . "sec.test_prmssns('View Vault Management', 'Vault Management','" . loc_db_escape_string($ssnRoles) . "') canViewVMS, "
        . "sec.test_prmssns('View Agent Registry', 'Agent Registry','" . loc_db_escape_string($ssnRoles) . "') canViewAgnt, "
        . "sec.test_prmssns('View Asset Tracking', 'Asset Tracking','" . loc_db_escape_string($ssnRoles) . "') canViewATrckr, "
        . "sec.test_prmssns('View System Administration', 'System Administration','" . loc_db_escape_string($ssnRoles) . "') canViewSysAdmin, "
        . "sec.test_prmssns('View Organization Setup', 'Organization Setup','" . loc_db_escape_string($ssnRoles) . "') canViewOrgStp, "
        . "sec.test_prmssns('View General Setup', 'General Setup','" . loc_db_escape_string($ssnRoles) . "') canViewLov, "
        . "sec.test_prmssns('View Workflow Manager', 'Workflow Manager','" . loc_db_escape_string($ssnRoles) . "') canViewWkf, "
        . "sec.test_prmssns('View Notices Admin', 'System Administration','" . loc_db_escape_string($ssnRoles) . "') canViewArtclAdmn, "
        . "sec.test_prmssns('View Reports And Processes', 'Reports And Processes','" . loc_db_escape_string($ssnRoles) . "') canViewRpts, 
               gst.get_pssbl_val_desc(gst.getenbldpssblvalid('Default Course/Objective/Programme Label%', gst.get_lov_id('All Other Performance Setups'))) prfmc_type, 
               gst.get_pssbl_val_desc(gst.getenbldpssblvalid('Create Links to Main App', gst.get_lov_id('All Other Self-Service Setups'))) self_link, 
               gst.get_pssbl_val_desc(gst.getenbldpssblvalid('Allow User Account Self-Registration', gst.get_lov_id('All Other General Setups'))) self_rgstr, "
        . "sec.test_prmssns('View Help Desk', 'Self Service','" . loc_db_escape_string($ssnRoles) . "') canViewHlpDsk, "
        . "sec.test_prmssns('View Appraisal', 'Learning/Performance Management','" . loc_db_escape_string($ssnRoles) . "') canViewAppraisal";
    //echo $sqlStr;getEnbldPssblValID("Allow User Account Self-Registration", getLovID("All Other General Setups"))
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
        $rslts[19] = ((int) $row[19]);
        $rslts[20] = ((int) $row[20]);
        $rslts[21] = ((int) $row[21]);
        $rslts[22] = ((int) $row[22]);
        $rslts[23] = ((int) $row[23]);
        $rslts[24] = ($row[24]);
        $rslts[25] = ($row[25]);
        $rslts[26] = ($row[26]);
        $rslts[27] = ((int) $row[27]);
        $rslts[28] = ((int) $row[28]);
    }
    return $rslts;
}

function getPrsnCmmnAsgnmnts($prsnID, $orgid, $usrID)
{
    $rslts = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $sqlStr = "Select (select oprtnl_crncy_id from org.org_details where org_id = $orgid), "
        . "pasn.get_prsn_siteid(" . $prsnID . "), "
        . "pasn.get_prsn_divid_of_spctype(" . $prsnID . ",'Access Control Group'),"
        . "scm.getUserStoreID(" . $usrID . ", " . $orgid . ")";
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        $rslts[0] = ((int) $row[0]);
        $rslts[1] = ((int) $row[1]);
        $rslts[2] = ((int) $row[2]);
        $rslts[3] = ((int) $row[3]);
    }
    return $rslts;
}

function getMcfPgPrmssns($prsnID, $orgid)
{
    global $ssnRoles;
    $mdlNm = "Banking";
    $rslts = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $sqlStr = "Select (select oprtnl_crncy_id from org.org_details where org_id = $orgid), "
        . "pasn.get_prsn_siteid(" . $prsnID . "), "
        . "pasn.get_prsn_divid_of_spctype(" . $prsnID . ",'Access Control Group'), "
        . "sec.test_prmssns('View Banking', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Customer Management', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Customer Accounts', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Core Banking', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Credit Management', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Investment Management', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Vault Management', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Product Setup', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Utilities & Configuration', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Treasury Management', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Standard Banking Reports', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Loan Applications', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Disbursements', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Loan Repayments', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Loan Calculator', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Cash Loan Payments', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Loans Summary Dashboard', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Risk Factors', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Risk Profiles', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Assessment Sets', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Add Withdrawal Transaction', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Edit Withdrawal Transactions', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Delete/Void Withdrawal Transactions', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Authorize Withdrawal Transactions', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Search All Transactions', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Credit Risk Assessment', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Property Collaterals', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Loan Sector Classifications', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "')";
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
        $rslts[19] = ((int) $row[19]);
        $rslts[20] = ((int) $row[20]);
        $rslts[21] = ((int) $row[21]);
        $rslts[22] = ((int) $row[22]);
        $rslts[23] = ((int) $row[23]);
        $rslts[24] = ((int) $row[24]);
        $rslts[25] = ((int) $row[25]);
        $rslts[26] = ((int) $row[26]);
        $rslts[27] = ((int) $row[27]);
        $rslts[28] = ((int) $row[28]);
        $rslts[29] = ((int) $row[29]);
        $rslts[30] = ((int) $row[30]);
    }
    return $rslts;
}

function getLovID($lovName)
{
    $sqlStr = "SELECT value_list_id from gst.gen_stp_lov_names where (value_list_name = '" .
        loc_db_escape_string($lovName) . "')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getLovNm($lovID)
{
    $sqlStr = "SELECT value_list_name from gst.gen_stp_lov_names " .
        "where (value_list_id = " . $lovID . ")";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getLovOrdrBy($lovID)
{
    $sqlStr = "SELECT dflt_order_by from gst.gen_stp_lov_names " .
        "where (value_list_id = " . $lovID . ")";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "ORDER BY 1";
}

function getCriteRiaID($pssblVal, $lovID)
{
    $sqlStr = "SELECT pssbl_value_id from gst.gen_stp_lov_values " .
        "where ((pssbl_value = '" .
        loc_db_escape_string($pssblVal) . "') AND (value_list_id = " . $lovID . "))";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getPssblValID2($pssblVal, $lovID, $pssblValDesc)
{
    $sqlStr = "SELECT pssbl_value_id from gst.gen_stp_lov_values " .
        "where ((pssbl_value = '" .
        loc_db_escape_string($pssblVal) . "') AND (pssbl_value_desc = '" .
        loc_db_escape_string($pssblValDesc) . "') AND (value_list_id = " . $lovID . "))";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getPssblValNm($pssblVlID)
{
    $sqlStr = "SELECT pssbl_value from gst.gen_stp_lov_values " .
        "where ((pssbl_value_id = " . $pssblVlID . "))";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getPssblValDesc($pssblVlID)
{
    $sqlStr = "SELECT pssbl_value_desc from gst.gen_stp_lov_values " .
        "where ((pssbl_value_id = " . $pssblVlID . "))";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getEnbldPssblValDesc($pssblVal, $lovID)
{
    $sqlStr = "SELECT pssbl_value_desc from gst.gen_stp_lov_values " .
        "where ((upper(pssbl_value) = upper('" .
        loc_db_escape_string($pssblVal) . "')) AND (value_list_id = " . $lovID .
        ") AND (is_enabled='1')) ORDER BY pssbl_value_id LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function isVlLstDynamic($lovID)
{
    $strSql = "select is_list_dynamic from gst.gen_stp_lov_names " .
        "where is_list_dynamic='1' and value_list_id = " . $lovID;
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function getSQLForDynamicVlLst($lovID)
{
    $strSql = "select sqlquery_if_dyn from gst.gen_stp_lov_names " .
        "where value_list_id = " . $lovID;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getLovValues(
    $searchWord,
    $searchIn,
    $offset,
    $limit_size,
    &$brghtsqlStr,
    $lovID,
    &$is_dynamic,
    $criteriaID,
    $criteriaID2,
    $criteriaID3,
    $addtnlWhere = ""
) {
    global $usrID;
    $lovNm = getLovNm($lovID);
    $strSql = "";
    $is_dynamic = false;
    $extrWhere = "";
    $ordrBy = getLovOrdrBy($lovID);
    if (trim($ordrBy) == "") {
        $ordrBy = "ORDER BY 1";
    }
    $selLst = "tbl1.a,tbl1.b,tbl1.c";
    if ($lovNm == "Report/Process Runs") {
        $ordrBy = "ORDER BY 5 DESC";
        $selLst = "tbl1.a,tbl1.b,tbl1.c,tbl1.d,tbl1.e";
    }
    if ($searchIn == "Value") {
        $extrWhere = "and tbl1.a ilike '" . loc_db_escape_string($searchWord) . "'";
    } else if ($searchIn == "Description") {
        $extrWhere = "and tbl1.b ilike '" . loc_db_escape_string($searchWord) . "'";
    } else {
        $extrWhere = "and (tbl1.a ilike '" . loc_db_escape_string($searchWord) .
            "%' or tbl1.b ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    if (isVlLstDynamic($lovID) == true) {
        if ($criteriaID <= 0 && $criteriaID2 == "" && $criteriaID3 == "") {
            $strSql = "select * from (" . str_replace("{:prsn_id}", getUserPrsnID1($usrID), getSQLForDynamicVlLst($lovID)) .
                ") tbl1 WHERE 1=1 " . $extrWhere . $addtnlWhere . " " . $ordrBy . " LIMIT " . $limit_size .
                " OFFSET " . abs($offset * $limit_size);
        } else if ($criteriaID >= 0 && $criteriaID2 == "" && $criteriaID3 == "") {
            $strSql = "select " . $selLst . " from (" . str_replace("{:prsn_id}", getUserPrsnID1($usrID), getSQLForDynamicVlLst($lovID)) .
                ") tbl1 WHERE tbl1.d = " . $criteriaID . " " . $extrWhere . $addtnlWhere . " " . $ordrBy . " LIMIT " . $limit_size .
                " OFFSET " . abs($offset * $limit_size);
        } else if ($criteriaID >= 0 && $criteriaID2 != "" && $criteriaID3 == "") {
            $strSql = "select " . $selLst . " from (" . str_replace("{:prsn_id}", getUserPrsnID1($usrID), getSQLForDynamicVlLst($lovID)) .
                ") tbl1 WHERE (tbl1.d = " . $criteriaID . " and tbl1.e = '" .
                loc_db_escape_string($criteriaID2) . "' " . $extrWhere . $addtnlWhere . ") " . $ordrBy . " LIMIT " . $limit_size .
                " OFFSET " . abs($offset * $limit_size);
        } else if ($criteriaID >= 0 && $criteriaID2 != "" && $criteriaID3 != "") {
            $strSql = "select " . $selLst . " from (" . str_replace("{:prsn_id}", getUserPrsnID1($usrID), getSQLForDynamicVlLst($lovID)) .
                ") tbl1 WHERE (tbl1.d = " . $criteriaID . " and tbl1.e = '" .
                loc_db_escape_string($criteriaID2) . "' and tbl1.f like '" . loc_db_escape_string($criteriaID3) .
                "' " . $extrWhere . $addtnlWhere . ") " . $ordrBy . " LIMIT " . $limit_size .
                " OFFSET " . abs($offset * $limit_size);
        } else if ($criteriaID < 0 && $criteriaID2 != "" && $criteriaID3 == "") {
            $strSql = "select " . $selLst . " from (" . str_replace("{:prsn_id}", getUserPrsnID1($usrID), getSQLForDynamicVlLst($lovID)) .
                ") tbl1 WHERE (tbl1.e = '" .
                loc_db_escape_string($criteriaID2) . "' " . $extrWhere . $addtnlWhere . ") " . $ordrBy . " LIMIT " . $limit_size .
                " OFFSET " . abs($offset * $limit_size);
        } else if ($criteriaID >= 0 && $criteriaID2 == "" && $criteriaID3 != "") {
            $strSql = "select " . $selLst . " from (" . str_replace("{:prsn_id}", getUserPrsnID1($usrID), getSQLForDynamicVlLst($lovID)) .
                ") tbl1 WHERE (tbl1.d = " . $criteriaID . " and tbl1.f like '" . loc_db_escape_string($criteriaID3) .
                "' " . $extrWhere . $addtnlWhere . ") " . $ordrBy . " LIMIT " . $limit_size .
                " OFFSET " . abs($offset * $limit_size);
        } else {
            $strSql = "select " . $selLst . " from (" . str_replace("{:prsn_id}", getUserPrsnID1($usrID), getSQLForDynamicVlLst($lovID)) .
                ") tbl1 WHERE (1=1 " . $extrWhere . $addtnlWhere . ") " . $ordrBy . " LIMIT " . $limit_size .
                " OFFSET " . abs($offset * $limit_size);
        }
        $is_dynamic = true;
    } else {
        if ($searchIn == "Value") {
            $strSql = "SELECT pssbl_value, pssbl_value_desc, pssbl_value_id " .
                "FROM gst.gen_stp_lov_values WHERE ((is_enabled != '0') AND (pssbl_value ilike '" .
                loc_db_escape_string($searchWord) . "') AND (value_list_id = " . $lovID .
                ")" . $addtnlWhere . ") $ordrBy LIMIT " . $limit_size .
                " OFFSET " . abs($offset * $limit_size);
        } else if ($searchIn == "Description") {
            $strSql = "SELECT pssbl_value, pssbl_value_desc, pssbl_value_id " .
                "FROM gst.gen_stp_lov_values WHERE ((is_enabled != '0') AND (pssbl_value_desc ilike '" .
                loc_db_escape_string($searchWord) . "') AND (value_list_id = " . $lovID .
                ")" . $addtnlWhere . ") $ordrBy LIMIT " . $limit_size .
                " OFFSET " . abs($offset * $limit_size);
        } else {
            $strSql = "SELECT pssbl_value, pssbl_value_desc, pssbl_value_id " .
                "FROM gst.gen_stp_lov_values WHERE ((is_enabled != '0') AND (pssbl_value ilike '" .
                loc_db_escape_string($searchWord) . "' or pssbl_value_desc ilike '" .
                loc_db_escape_string($searchWord) . "') AND (value_list_id = " . $lovID .
                ")" . $addtnlWhere . ") $ordrBy LIMIT " . $limit_size .
                " OFFSET " . abs($offset * $limit_size);
        }
    }
    $brghtsqlStr = $strSql;
    //echo getUserPrsnID1($usrID) .":usrID:".$usrID."<br/><br/>";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getTtlLovValues(
    $searchWord,
    $searchIn,
    &$brghtsqlStr,
    $lovID,
    &$is_dynamic,
    $criteriaID,
    $criteriaID2,
    $criteriaID3,
    $addtnlWhere = ""
) {
    global $usrID;
    $lovNm = getLovNm($lovID);
    $strSql = "";
    $is_dynamic = false;
    $extrWhere = "";
    //$ordrBy = "";
    //$selLst = "count(1)";
    if ($lovNm == "Report/Process Runs") {
        $ordrBy = "";
        $selLst = "tbl1.a,tbl1.b,tbl1.c,tbl1.d,tbl1.e";
    }
    if ($searchIn == "Value") {
        $extrWhere = "and tbl1.a ilike '" . loc_db_escape_string($searchWord) . "'";
    } else if ($searchIn == "Description") {
        $extrWhere = "and tbl1.b ilike '" . loc_db_escape_string($searchWord) . "'";
    } else {
        $extrWhere = "and (tbl1.a ilike '" . loc_db_escape_string($searchWord) .
            "%' or tbl1.b ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    if (isVlLstDynamic($lovID) == true) {
        if ($criteriaID <= 0 && $criteriaID2 == "" && $criteriaID3 == "") {
            $strSql = "select count(1) from (" . str_replace("{:prsn_id}", getUserPrsnID1($usrID), getSQLForDynamicVlLst($lovID)) .
                ") tbl1 WHERE 1=1 " . $extrWhere . $addtnlWhere . " ";
        } else if ($criteriaID >= 0 && $criteriaID2 == "" && $criteriaID3 == "") {
            $strSql = "select count(1) from (" . str_replace("{:prsn_id}", getUserPrsnID1($usrID), getSQLForDynamicVlLst($lovID)) .
                ") tbl1 WHERE tbl1.d = " . $criteriaID . " " . $extrWhere . $addtnlWhere . " ";
        } else if ($criteriaID >= 0 && $criteriaID2 != "" && $criteriaID3 == "") {
            $strSql = "select count(1) from (" . str_replace("{:prsn_id}", getUserPrsnID1($usrID), getSQLForDynamicVlLst($lovID)) .
                ") tbl1 WHERE (tbl1.d = " . $criteriaID . " and tbl1.e = '" .
                loc_db_escape_string($criteriaID2) . "' " . $extrWhere . $addtnlWhere . ") ";
        } else if ($criteriaID >= 0 && $criteriaID2 != "" && $criteriaID3 != "") {
            $strSql = "select count(1) from (" . str_replace("{:prsn_id}", getUserPrsnID1($usrID), getSQLForDynamicVlLst($lovID)) .
                ") tbl1 WHERE (tbl1.d = " . $criteriaID . " and tbl1.e = '" .
                loc_db_escape_string($criteriaID2) . "' and tbl1.f like '" . loc_db_escape_string($criteriaID3) .
                "' " . $extrWhere . $addtnlWhere . ") ";
        } else if ($criteriaID < 0 && $criteriaID2 != "" && $criteriaID3 == "") {
            $strSql = "select count(1) from (" . str_replace("{:prsn_id}", getUserPrsnID1($usrID), getSQLForDynamicVlLst($lovID)) .
                ") tbl1 WHERE (tbl1.e = '" .
                loc_db_escape_string($criteriaID2) . "' " . $extrWhere . $addtnlWhere . ") ";
        } else if ($criteriaID >= 0 && $criteriaID2 == "" && $criteriaID3 != "") {
            $strSql = "select count(1) from (" . str_replace("{:prsn_id}", getUserPrsnID1($usrID), getSQLForDynamicVlLst($lovID)) .
                ") tbl1 WHERE (tbl1.d = " . $criteriaID . " and tbl1.f like '" . loc_db_escape_string($criteriaID3) .
                "' " . $extrWhere . $addtnlWhere . ") ";
        } else {
            $strSql = "select count(1) from (" . str_replace("{:prsn_id}", getUserPrsnID1($usrID), getSQLForDynamicVlLst($lovID)) .
                ") tbl1 WHERE (1=1 " . $extrWhere . $addtnlWhere . ") ";
        }
        $is_dynamic = true;
    } else {
        if ($searchIn == "Value") {
            $strSql = "SELECT count(1) " .
                "FROM gst.gen_stp_lov_values WHERE ((is_enabled != '0') AND (pssbl_value ilike '" .
                loc_db_escape_string($searchWord) . "') AND (value_list_id = " . $lovID .
                ")" . $addtnlWhere . ") ";
        } else if ($searchIn == "Description") {
            $strSql = "SELECT count(1) " .
                "FROM gst.gen_stp_lov_values WHERE ((is_enabled != '0') AND (pssbl_value_desc ilike '" .
                loc_db_escape_string($searchWord) . "') AND (value_list_id = " . $lovID .
                ")" . $addtnlWhere . ") ";
        } else {
            $strSql = "SELECT count(1) " .
                "FROM gst.gen_stp_lov_values WHERE ((is_enabled != '0') AND (pssbl_value ilike '" .
                loc_db_escape_string($searchWord) . "' or pssbl_value_desc ilike '" .
                loc_db_escape_string($searchWord) . "') AND (value_list_id = " . $lovID .
                ")" . $addtnlWhere . ") ";
        }
    }

    $brghtsqlStr = $strSql;
    //echo $strSql;
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function getPrsnEmail($prsnid)
{
    $sqlStr = "select a.email from prs.prsn_names_nos a where a.person_id = " .
        $prsnid . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getPrsnMobile($prsnid)
{
    $sqlStr = "select a.cntct_no_mobl || ';' || a.cntct_no_tel from prs.prsn_names_nos a where a.person_id = " .
        $prsnid . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function sendSMS($msgBody, $rcpntNo, &$errMsg)
{
    $msgBody = str_replace("|", "/", str_replace("\n", " ", str_replace("\r", " ", str_replace("\r\n", " ", $msgBody))));
    $dtstResult = executeSQLNoParams("select sms_param1, sms_param2, sms_param3, 
                                                sms_param4, sms_param5, sms_param6, 
                                                sms_param7, sms_param8, sms_param9, sms_param10 
                                                from sec.sec_email_servers where is_default='t'");

    $rvsdMsgBdy = "";
    for ($z = 0; $z < strlen($msgBody); $z++) {
        if ($z > 0 && ($z % 160) == 0) {
            $rvsdMsgBdy .= substr($msgBody, $z, 1) . "|";
        } else {
            $rvsdMsgBdy .= substr($msgBody, $z, 1);
        }
    }
    $nwMsgBdy = explode("|", $rvsdMsgBdy);
    $url = '';
    $fields = array();
    $sendRes = "1";
    for ($z = 0; $z < count($nwMsgBdy); $z++) {

        $paramNms = array();
        $paramVals = array();
        $tmpStr = "";
        $tmpArry = array();
        $i = 0;
        while ($row = loc_db_fetch_array($dtstResult)) {
            $tmpStr = trim($row[0], " | ");
            $tmpArry = explode("|", $tmpStr);

            if ($tmpStr == "" || count($tmpArry) != 2) {
                $paramNms[$i] = "";
                $paramVals[$i] = "";
            } else {
                $paramNms[$i] = $tmpArry[0];
                $paramVals[$i] = $tmpArry[1];
            }

            if ($paramNms[$i] == "url") {
                $url = $paramVals[$i];
            } else if ($paramNms[$i] == "success txt") {
                $succsTxt = $paramVals[$i];
            } else if ($paramNms[$i] != "" && $paramVals[$i] != "") {
                $fields[$paramNms[$i]] = str_replace("{:to}", $rcpntNo, str_replace("{:msg}", $nwMsgBdy[$z], $paramVals[$i]));
            }
        }
        $fields_string = "";
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        rtrim($fields_string, '&');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($result);
        $sendRes = $data->error;
        if ($data->error == "0") {
            $errMsg = "Message was successfully sent";
        } else {
            $errMsg = "Message failed to send. Error: " . $data->error;
        }
    }

    if ($sendRes == "0") {
        return true;
    } else {
        return false;
    }
}

/*
function sendEMail(
    $to,
    $nameto,
    $subject,
    $message,
    &$errMsg,
    $ccMail = "",
    $bccMail = "",
    $attchmnts = "",
    $namefrom = "Portal Administrator"
) {
    global $admin_email;
    global $app_url;
    global $smplTokenWord;
    global $admin_name;
    if ($namefrom === "Portal Administrator" && $admin_name != "") {
        $namefrom = $admin_name;
    }
    try {
        $selSql = "SELECT smtp_client, mail_user_name, mail_password, smtp_port, inhouse_smtp_ip FROM sec.sec_email_servers WHERE (is_default = 't')";
        $selDtSt = executeSQLNoParams($selSql);
        $m = loc_db_num_rows($selDtSt);
        $smtpClnt = "";
        $fromEmlNm = "";
        $fromPswd = "";
        $errMsg = "";
        $portNo = 0;
        $inhouse_smtp_ip = "";
        while ($row = loc_db_fetch_array($selDtSt)) {
            $smtpClnt = $row[0]; //gethostbyname();
            $fromEmlNm = $row[1];
            $fromPswd = decrypt($row[2], $smplTokenWord);
            $portNo = $row[3];
            $inhouse_smtp_ip = $row[4];
        }
        //echo $smtpClnt."<br/>";
        //echo $fromEmlNm."<br/>";
        //echo $fromPswd."<br/>";
        //echo $portNo."<br/>";
        //error_reporting(E_USER_ERROR);"ssl://" . 
        $smtpServer = $smtpClnt;   //smtp.gmail.com ip address of the mail server.  This can also be the local domain name
        $port = $portNo;      // should be 25 by default, but needs to be whichever port the mail server will be using for smtp 
        $timeout = "145";     // typical timeout. try 45 for slow servers
        $username = $fromEmlNm; // the login for your smtp
        $password = $fromPswd;   // the password for your smtp
        $localhost = "127.0.0.1";    // Defined for the web server.  Since this is where we are gathering the details for the email
        $newLine = "<br/>";    // aka, carrage return line feed. var just for newlines in MS
        $secure = 1;   // change to 1 if your server is running under SSL
        $from = $fromEmlNm;
        // Swift Mailer Library
        require_once 'swiftmailer/lib/swift_required.php';

        // Mail Transport
        $transport = null;
        //echo "Before transport";
        if ($port == 25 && $port != "") {
            $smtpServer = $inhouse_smtp_ip;
            $transport = Swift_SmtpTransport::newInstance($smtpServer, $port)
                ->setUsername($username) // Your Gmail Username
                ->setPassword($password);
        } else {
            $transport = Swift_SmtpTransport::newInstance($smtpServer, $port)
                ->setUsername($username) // Your Gmail Username
                ->setPassword($password) // Your Gmail Password                
                ->setEncryption('tls');
        }
        //var_dump($transport);
        //echo "After transport" . $smtpServer . "::" . $port . "::" . $inhouse_smtp_ip;
        //
        // Mailer
        $mailer = Swift_Mailer::newInstance($transport);
        // Create a message setBcc()
        $swmessage = Swift_Message::newInstance("$subject");

        $doc = new DOMDocument();
        @$doc->loadHTML($message);

        $tags = $doc->getElementsByTagName('img');

        foreach ($tags as $tag) {
            $src1 = $tag->getAttribute('src');
            //echo $src1;
            str_replace(
                "src=\"" . $src1 . "\"",
                "src=\"" .
                    $swmessage->embed(Swift_Image::fromPath(str_replace($app_url . "/", "", $src1))) . "\"",
                $message
            );
        }
        $lovID = getLovID("Email Addresses to Ignore");
        $toEmails = explode(",", $to);
        for ($i = 0; $i < count($toEmails); $i++) {
            if (isEmailValid($toEmails[$i], $lovID)) {
                if (getEnbldPssblValID($toEmails[$i], $lovID) <= 0) {
                    //DO Nothing
                } else {
                    $to = str_replace($toEmails[$i], "", $to);
                    $errMsg .= "Address:" . $toEmails[$i] . " blacklisted by Admin!<br/>";
                }
            } else {
                $errMsg .= "Address:" . $toEmails[$i] . " is Invalid!<br/>";
            }
        }
        $to = preg_replace('/,+/', ',', $to);
        $ccEmails = explode(";", $ccMail);
        for ($i = 0; $i < count($ccEmails); $i++) {
            if (isEmailValid($ccEmails[$i], $lovID)) {
                if (getEnbldPssblValID($ccEmails[$i], $lovID) <= 0) {
                    //DO Nothing
                } else {
                    $ccMail = str_replace($ccEmails[$i], "", $ccMail);
                    $errMsg .= "Address:" . $ccEmails[$i] . " blacklisted by Admin!<br/>";
                }
            } else {
                $errMsg .= "Address:" . $ccEmails[$i] . " is Invalid!<br/>";
            }
        }
        $bccMail = preg_replace('/;+/', ';', $bccMail);
        $bccEmails = explode(";", $bccMail);
        for ($i = 0; $i < count($bccEmails); $i++) {
            if (isEmailValid($bccEmails[$i], $lovID)) {
                if (getEnbldPssblValID($bccEmails[$i], $lovID) <= 0) {
                    //DO Nothing
                } else {
                    $bccMail = str_replace($bccEmails[$i], "", $bccMail);
                    $errMsg .= "Address:" . $bccEmails[$i] . " blacklisted by Admin!<br/>";
                }
            } else {
                $errMsg .= "Address:" . $bccEmails[$i] . " is Invalid!<br/>";
            }
        }
        $bccMail = preg_replace('/;+/', ';', $bccMail);
        if ($ccMail != "" && $bccMail != "") {
            $swmessage->setFrom(array("$from" => "$namefrom")) // can be $_POST['email'] etc...
                ->setTo(explode(",", $to)) // your email / multiple supported.
                ->setCc(explode(";", $ccMail))
                ->setBcc(explode(";", $bccMail))
                ->setBody("$message", 'text/html')
                ->addPart(cleanOutputData($message), 'text/plain');
        } else if ($ccMail != "" && $bccMail == "") {
            $swmessage->setFrom(array("$from" => "$namefrom")) // can be $_POST['email'] etc...
                ->setTo(explode(",", $to)) // your email / multiple supported.
                ->setCc(explode(";", $ccMail))
                ->setBody("$message", 'text/html')
                ->addPart(cleanOutputData($message), 'text/plain');
        } else if ($ccMail == "" && $bccMail != "") {
            $swmessage->setFrom(array("$from" => "$namefrom")) // can be $_POST['email'] etc...
                ->setTo(explode(",", $to)) // your email / multiple supported.
                ->setBcc(explode(";", $bccMail))
                ->setBody("$message", 'text/html')
                ->addPart(cleanOutputData($message), 'text/plain');
        } else {
            $swmessage->setFrom(array("$from" => "$namefrom")) // can be $_POST['email'] etc...
                ->setTo(explode(",", $to)) // your email / multiple supported.
                ->setBody("$message", 'text/html')
                ->addPart(cleanOutputData($message), 'text/plain');
        }

        $attchFiles = explode(";", $attchmnts);
        //var_dump($attchFiles);
        for ($i = 0; $i < count($attchFiles); $i++) {
            if ($attchFiles[$i] != "") {
                //echo str_replace($app_url . "/", "", $attchFiles[$i]);
                $swmessage->attach(Swift_Attachment::fromPath(str_replace($app_url . "/", "", $attchFiles[$i])));
            }
        }
        // Send the message
        $swmessage->setMaxLineLength(1000);
        if (checkForInternetConnection()) {
            if ($mailer->send($swmessage)) {
                $errMsg = 'Mail sent successfully!';
                return TRUE;
            } else {
                $errMsg .= "Failed to Send Mail!";
                return FALSE;
            }
        }
        $errMsg .= "No Internet Connection";
        return FALSE;
    } catch (Exception $e) {
        $eMsg = $e->getMessage() . "<br/>"; //substr($e->getTraceAsString() ."<br/>".$e->getMessage(), 0, 40);
        if (strpos($eMsg, 'Connection could not be established') !== FALSE) {
            $eMsg .= "Connection could not be established!<br/>";
        } else if (strpos($eMsg, 'Address in mailbox given') !== FALSE) {
            $eMsg = "Your Registered Email Address is Invalid!<br/>"
                . "Send a Complaint to <a href=\"mailto:" . $admin_email . "\">$admin_email</a> for Assistance!<br/>";
        }
        $errMsg .= "<span style=\"color:red !important;\">Failed to Send Mail! " . $eMsg . "</span>";
        return FALSE;
    }
}*/

function sendEMail(
    $to,
    $nameto,
    $subject,
    $message,
    &$errMsg,
    $ccMail = "",
    $bccMail = "",
    $attchmnts = "",
    $namefrom = "Portal Administrator"
) {
    global $admin_email;
    global $app_url;
    global $smplTokenWord;
    global $admin_name;
    global $fldrPrfx;
    global $pemDest;
    if ($namefrom === "Portal Administrator" && $admin_name != "") {
        $namefrom = $admin_name;
    }
    try {
        $selSql = "SELECT smtp_client, mail_user_name, mail_password, smtp_port, inhouse_smtp_ip FROM sec.sec_email_servers WHERE (is_default = 't')";
        $selDtSt = executeSQLNoParams($selSql);
        $m = loc_db_num_rows($selDtSt);
        $smtpClnt = "";
        $fromEmlNm = "";
        $fromPswd = "";
        $fromPswdOrig = "";
        $errMsg = "";
        $portNo = 0;
        $inhouse_smtp_ip = "";
        while ($row = loc_db_fetch_array($selDtSt)) {
            $smtpClnt = $row[0]; //gethostbyname();
            $fromEmlNm = $row[1];
            $fromPswdOrig = $row[2];
            $fromPswd = decrypt($row[2], $smplTokenWord);
            $portNo = $row[3];
            $inhouse_smtp_ip = $row[4];
        }
        /*$errMsg .= $smtpClnt . "!!<br/>";
        $errMsg .= $fromEmlNm . "!!<br/>";
        $errMsg .= strlen($fromPswd) . "!!--$fromPswdOrig--||--$smplTokenWord--||<br/>";
        $errMsg .= $portNo . "!!<br/>";
        echo $errMsg;
        $port = 1/0;*/
        //error_reporting(E_USER_ERROR);"ssl://" . 
        $smtpServer = $smtpClnt;   //smtp.gmail.com ip address of the mail server.  This can also be the local domain name
        $port = $portNo;      // should be 25 by default, but needs to be whichever port the mail server will be using for smtp 
        $timeout = "145";     // typical timeout. try 45 for slow servers
        $username = $fromEmlNm; // the login for your smtp
        $password = $fromPswd;   // the password for your smtp
        $localhost = "127.0.0.1";    // Defined for the web server.  Since this is where we are gathering the details for the email
        $newLine = "<br/>";    // aka, carrage return line feed. var just for newlines in MS
        $secure = 1;   // change to 1 if your server is running under SSL
        $from = $fromEmlNm;
        // Swift Mailer Library
        require_once 'swiftmailer/lib/swift_required.php';

        // Mail Transport
        $transport = null;
        //echo "Before transport";
        if ($port == 25 && $port != "") {
            $smtpServer = $inhouse_smtp_ip;
            $transport = Swift_SmtpTransport::newInstance($smtpServer, $port)
                ->setUsername($username) // Your Gmail Username
                ->setPassword($password);
        } else {
            $transport = Swift_SmtpTransport::newInstance($smtpServer, $port)
                ->setUsername($username) // Your Gmail Username
                ->setPassword($password) // Your Gmail Password                
                ->setEncryption('tls');
        }
        //var_dump($transport);
        //echo "After transport" . $smtpServer . "::" . $port . "::" . $inhouse_smtp_ip;
        //
        // Mailer
        $mailer = Swift_Mailer::newInstance($transport);
        // Create a message setBcc()
        $swmessage = Swift_Message::newInstance("$subject");

        $doc = new DOMDocument();
        @$doc->loadHTML($message);

        $tags = $doc->getElementsByTagName('img');

        foreach ($tags as $tag) {
            $src1 = $tag->getAttribute('src');
            $fileNm = str_replace($app_url, $fldrPrfx,  $src1);
            str_replace(
                "src=\"" . $src1 . "\"",
                "src=\"" .
                    $swmessage->embed(Swift_Image::fromPath($fileNm)) . "\"",
                $message
            );
        }

        $lovID = getLovID("Email Addresses to Ignore");
        $toEmails = explode(",", $to);
        for ($i = 0; $i < count($toEmails); $i++) {
            if (isEmailValid($toEmails[$i], $lovID)) {
                if (getEnbldPssblValID($toEmails[$i], $lovID) <= 0) {
                    //DO Nothing
                } else {
                    $to = str_replace($toEmails[$i], "", $to);
                    $errMsg .= "Address:" . $toEmails[$i] . " blacklisted by Admin!<br/>";
                }
            } else {
                $errMsg .= "Address:" . $toEmails[$i] . " is Invalid!<br/>";
            }
        }
        $to = preg_replace('/,+/', ',', $to);
        $ccEmails = explode(";", $ccMail);
        for ($i = 0; $i < count($ccEmails); $i++) {
            if (isEmailValid($ccEmails[$i], $lovID)) {
                if (getEnbldPssblValID($ccEmails[$i], $lovID) <= 0) {
                    //DO Nothing
                } else {
                    $ccMail = str_replace($ccEmails[$i], "", $ccMail);
                    $errMsg .= "Address:" . $ccEmails[$i] . " blacklisted by Admin!<br/>";
                }
            } else {
                $errMsg .= "Address:" . $ccEmails[$i] . " is Invalid!<br/>";
            }
        }
        $bccMail = preg_replace('/;+/', ';', $bccMail);
        $bccEmails = explode(";", $bccMail);
        for ($i = 0; $i < count($bccEmails); $i++) {
            if (isEmailValid($bccEmails[$i], $lovID)) {
                if (getEnbldPssblValID($bccEmails[$i], $lovID) <= 0) {
                    //DO Nothing
                } else {
                    $bccMail = str_replace($bccEmails[$i], "", $bccMail);
                    $errMsg .= "Address:" . $bccEmails[$i] . " blacklisted by Admin!<br/>";
                }
            } else {
                $errMsg .= "Address:" . $bccEmails[$i] . " is Invalid!<br/>";
            }
        }
        $bccMail = preg_replace('/;+/', ';', $bccMail);
        if ($ccMail != "" && $bccMail != "") {
            $swmessage->setFrom(array("$from" => "$namefrom")) // can be $_POST['email'] etc...
                ->setTo(explode(",", $to)) // your email / multiple supported.
                ->setCc(explode(";", $ccMail))
                ->setBcc(explode(";", $bccMail))
                ->setBody("$message", 'text/html')
                ->addPart(cleanOutputData($message), 'text/plain');
        } else if ($ccMail != "" && $bccMail == "") {
            $swmessage->setFrom(array("$from" => "$namefrom")) // can be $_POST['email'] etc...
                ->setTo(explode(",", $to)) // your email / multiple supported.
                ->setCc(explode(";", $ccMail))
                ->setBody("$message", 'text/html')
                ->addPart(cleanOutputData($message), 'text/plain');
        } else if ($ccMail == "" && $bccMail != "") {
            $swmessage->setFrom(array("$from" => "$namefrom")) // can be $_POST['email'] etc...
                ->setTo(explode(",", $to)) // your email / multiple supported.
                ->setBcc(explode(";", $bccMail))
                ->setBody("$message", 'text/html')
                ->addPart(cleanOutputData($message), 'text/plain');
        } else {
            $swmessage->setFrom(array("$from" => "$namefrom")) // can be $_POST['email'] etc...
                ->setTo(explode(",", $to)) // your email / multiple supported.
                ->setBody("$message", 'text/html')
                ->addPart(cleanOutputData($message), 'text/plain');
        }

        $attchFiles = explode(";", $attchmnts);
        //var_dump($attchFiles);
        for ($i = 0; $i < count($attchFiles); $i++) {
            if ($attchFiles[$i] != "") {
                $fileNm = str_replace($app_url, $fldrPrfx, $attchFiles[$i]);
                $fileNmDsply = str_replace($app_url . $pemDest, "", $attchFiles[$i]);
                $swmessage->attach(Swift_Attachment::fromPath($fileNm)->setFilename($fileNmDsply));
                //str_replace($app_url . "/", "", $attchFiles[$i])));
            }
        }
        // Send the message
        $swmessage->setMaxLineLength(1000);
        if (checkForInternetConnection()) {
            if ($mailer->send($swmessage)) {
                $errMsg = 'Mail sent successfully!';
                return TRUE;
            } else {
                $errMsg .= "Failed to Send Mail!";
                return FALSE;
            }
        }
        $errMsg .= "No Internet Connection";
        return FALSE;
    } catch (Exception $e) {
        $eMsg = $e->getMessage() . "<br/>"; //substr($e->getTraceAsString() ."<br/>".$e->getMessage(), 0, 40);
        if (strpos($eMsg, 'Connection could not be established') !== FALSE) {
            $eMsg .= "Connection could not be established!<br/>";
        } else if (strpos($eMsg, 'Address in mailbox given') !== FALSE) {
            $eMsg = "Your Registered Email Address is Invalid!<br/>"
                . "Send a Complaint to <a href=\"mailto:" . $admin_email . "\">$admin_email</a> for Assistance!<br/>";
        }
        $errMsg .= "<span style=\"color:red !important;\">Failed to Send Mail! " . $eMsg . "</span>";
        return FALSE;
    }
}


function checkForInternetConnection()
{
    //$num = 0;
    //$error = "";
    //$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    //$connection =  @socket_connect($socket, 'XX.XX.XX.XX', 80);
    try {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $connection = socket_connect($socket, 'mail.rhomicom.com', 587);
        //($sock = @fsockopen('mail.rhomicom.com', 587, $num, $error, 5))
        if (!$connection) {
            return false;
        } else {
            return true;
        }
    } catch (Exception $e) {
        $eMsg = $e->getMessage() . "<br/>";
        echo $eMsg;
        return false;
    }
}

function checkInternetConnxtn($smtpClnt, $portNo)
{
    //$num = 0;
    //$error = "";
    //$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    //$connection =  @socket_connect($socket, 'XX.XX.XX.XX', 80);
    try {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $connection = socket_connect($socket, $smtpClnt, $portNo);
        //($sock = @fsockopen('mail.rhomicom.com', 587, $num, $error, 5))
        if (!$connection) {
            return false;
        } else {
            return true;
        }
    } catch (Exception $e) {
        $eMsg = $e->getMessage() . "<br/>";
        echo $eMsg;
        return false;
    }
}

function isEmailValid($emailString, $lovID)
{
    $isEmailValid = filter_var($emailString, FILTER_VALIDATE_EMAIL);
    if ($isEmailValid === false) {
        createSysLovsPssblVals1($emailString, $lovID);
    }
    return $isEmailValid;
}

function isMobileNumValid($mobileNum)
{
    return preg_match("/^\+?[1-9]\d{4,14}$/", $mobileNum);
}

function createSysLovsPssblVals1($pssblVals, $lovID)
{
    if (getPssblValID($pssblVals, $lovID) <= 0) {
        createPssblValsForLov1($lovID, $pssblVals, $pssblVals, "1", "");
    }
}

function createPssblValsForLov1($lovID, $pssblVal, $pssblValDesc, $isEnbld, $allwd)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO gst.gen_stp_lov_values(" .
        "value_list_id, pssbl_value, pssbl_value_desc, " .
        "created_by, creation_date, last_update_by, " .
        "last_update_date, is_enabled, allowed_org_ids) " .
        "VALUES (" . $lovID . ", '" . loc_db_escape_string($pssblVal) . "', '" .
        loc_db_escape_string($pssblValDesc) .
        "', " . $usrID . ", '" . $dateStr . "', " . $usrID .
        ", '" . $dateStr . "', '" . loc_db_escape_string($isEnbld) .
        "', '" . loc_db_escape_string($allwd) . "')";
    execUpdtInsSQL($insSQL);
}


function getEnbldPssblValID($pssblVal, $lovID)
{
    $sqlStr = "SELECT pssbl_value_id from gst.gen_stp_lov_values " .
        "where ((pssbl_value = '" .
        loc_db_escape_string($pssblVal) . "') AND (value_list_id = " . $lovID . ") and is_enabled = '1')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getEnbldLkPssblValID($pssblVal, $lovID)
{
    $sqlStr = "SELECT pssbl_value_id from gst.gen_stp_lov_values " .
        "where ((pssbl_value ilike '" .
        loc_db_escape_string($pssblVal) . "') AND (value_list_id = " . $lovID . ") and is_enabled = '1')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getPssblValID($pssblVal, $lovID)
{
    $sqlStr = "SELECT pssbl_value_id from gst.gen_stp_lov_values " .
        "where ((pssbl_value = '" .
        loc_db_escape_string($pssblVal) . "') AND (value_list_id = " . $lovID . "))";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getQRCodeUrl($codeContents, $nwFileName)
{
    global $tmpDest;
    global $fldrPrfx;
    $tempDir = $fldrPrfx . $tmpDest;
    require_once 'phpqrcodes/phpqrcode/qrlib.php';
    require_once 'phpqrcodes/phpqrcode/qrconfig.php';
    // generating 
    QRcode::png($codeContents, $tempDir . $nwFileName . '.png', QR_ECLEVEL_L, 3);

    // displaying 
    return $tmpDest . $nwFileName . ".png";
}

/* function cnvrtHtmlToPDFURL($nwHtmlFileName, $nwPDFFileName) {
  // generating
  // You can pass a filename, a HTML string, an URL or an options array to the constructor
  $pdf = new Pdf(array(
  // Explicitly tell wkhtmltopdf that we're using an X environment
  'use-xserver',
  // Enable built in Xvfb support in the command
  'commandOptions' => array(
  'enableXvfb' => true,
  // Optional: Set your path to xvfb-run. Default is just 'xvfb-run'.
  'xvfbRunBinary' => '/usr/bin/xvfb-run',
  // Optional: Set options for xfvb-run. The following defaults are used.
  'xvfbRunOptions' =>  '--server-args="-screen 0, 1024x768x24"',
  ),
  ));
  $pdf->addPage($nwHtmlFileName);
  // On some systems you may have to set the path to the wkhtmltopdf executable
  // $pdf->binary = 'C:\...';
  $error = "";
  if (!$pdf->saveAs($nwPDFFileName)) {
  $error = $pdf->getError();
  // ... handle error here
  }

  // displaying
  if($error===""){
  $error="Success";
  }
  return $error;
  } */

function getMsgBatchID()
{
    $strSql = "select nextval('alrt.bulk_msgs_batch_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function createMessageQueue($batchID, $mailTo, $mailCc, $mailBcc, $msgBody, $msgSbjct, $attachmnts, $msgType)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO alrt.bulk_msgs_sent(
            batch_id, to_list, cc_list, msg_body, date_sent, 
            msg_sbjct, bcc_list, created_by, creation_date, sending_status, 
            err_msg, attch_urls, msg_type) VALUES (" . $batchID .
        ",'" . loc_db_escape_string($mailTo) .
        "','" . loc_db_escape_string($mailCc) .
        "','" . loc_db_escape_string($msgBody) .
        "','" . loc_db_escape_string($dateStr) .
        "','" . loc_db_escape_string($msgSbjct) .
        "','" . loc_db_escape_string($mailBcc) .
        "', " . $usrID .
        ", '" . loc_db_escape_string($dateStr) .
        "','0','','" . loc_db_escape_string($attachmnts) .
        "','" . loc_db_escape_string($msgType) . "')";
    execUpdtInsSQL($insSQL);
}

function getGnrlRecID2($tblNm, $srchcol, $rtrnCol, $recname)
{
    $sqlStr = "select " . $rtrnCol . " from " . $tblNm . " where lower(" . $srchcol . ") = lower('" .
        loc_db_escape_string($recname) . "')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getGnrlRecID($tblNm, $srchcol, $rtrnCol, $recname, $orgid)
{
    $sqlStr = "select " . $rtrnCol . " from " . $tblNm . " where lower(" . $srchcol . ") = lower('" .
        loc_db_escape_string($recname) . "') and org_id = " . $orgid;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getGnrlRecNm($tblNm, $srchcol, $rtrnCol, $recid)
{
    $sqlStr = "select " . $rtrnCol . " from " . $tblNm . " where " . $srchcol . " = " . $recid;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getGnrlRecNm2($tblNm, $srchcol, $rtrnCol, $recnm)
{
    $sqlStr = "select " . $rtrnCol . " from " . $tblNm . " where " . $srchcol . " = '" . loc_db_escape_string($recnm) . "'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getGnrlRecIDExtr($tblNm, $srchcolForNM, $srchcolForID, $rtrnCol, $recname, $recID)
{
    $sqlStr = "select " . $rtrnCol . " from " . $tblNm . " where lower(" . $srchcolForNM . ") = lower('" .
        loc_db_escape_string($recname) . "') and " . $srchcolForID . " = " . $recID;
    $result = executeSQLNoParams($sqlStr);
    //echo $sqlStr;
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getGnrlRecIDExtr1($tblNm, $srchcolForNM, $srchcolForNM1, $rtrnCol, $recname, $recname1)
{
    $sqlStr = "select " . $rtrnCol . " from " . $tblNm . " where lower(" . $srchcolForNM . ") = lower('" .
        loc_db_escape_string($recname) . "') and lower(" . $srchcolForNM1 . ") = '" .
        loc_db_escape_string($recname1) . "'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getRecCount($tblNm, $srchcol, $colToCnt, $srchWrds)
{
    $sqlStr = "select count(" . $colToCnt . ") from " . $tblNm . " where lower(" . $srchcol . ") like lower('" .
        loc_db_escape_string($srchWrds) . "')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function getRecCount_LstNum($tblNm, $srchcol, $colToCnt, $srchWrds)
{
    $sqlStr = "select gst.getreccount_lstnum('" . $tblNm . "','" . $srchcol . "','" . $colToCnt . "','" .
        loc_db_escape_string($srchWrds) . "')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function getActionSQL($routingID, $actyp)
{
    $sqlStr = "select a.sql_stmnt 
from wkf.wkf_apps_actions a, wkf.wkf_actual_msgs_routng b, wkf.wkf_actual_msgs_hdr c
where a.action_performed_nm='" . loc_db_escape_string($actyp) . "' 
and a.app_id=c.app_id
and b.msg_id = c.msg_id
and b.routing_id=$routingID";
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getActionUrl($routingID, $actyp)
{
    $sqlStr = "select a.web_url 
from wkf.wkf_apps_actions a, wkf.wkf_actual_msgs_routng b, wkf.wkf_actual_msgs_hdr c
where a.action_performed_nm='" . loc_db_escape_string($actyp) . "' 
and a.app_id=c.app_id
and b.msg_id = c.msg_id
and b.routing_id=$routingID";
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getActionUrlDsplyTyp($routingID, $actyp)
{
    $sqlStr = "select a.is_web_dsply_diag 
from wkf.wkf_apps_actions a, wkf.wkf_actual_msgs_routng b, wkf.wkf_actual_msgs_hdr c
where a.action_performed_nm='" . loc_db_escape_string($actyp) . "' 
and a.app_id=c.app_id
and b.msg_id = c.msg_id
and b.routing_id=$routingID";
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getActionAdminOnly($routingID, $actyp)
{
    $sqlStr = "select a.is_admin_only 
from wkf.wkf_apps_actions a, wkf.wkf_actual_msgs_routng b, wkf.wkf_actual_msgs_hdr c
where a.action_performed_nm='" . loc_db_escape_string($actyp) . "' 
and a.app_id=c.app_id
and b.msg_id = c.msg_id
and b.routing_id=$routingID";
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}


function getActionDescription($routingID, $actyp)
{
    $sqlStr = "select a.action_desc 
from wkf.wkf_apps_actions a, wkf.wkf_actual_msgs_routng b, wkf.wkf_actual_msgs_hdr c
where a.action_performed_nm='" . loc_db_escape_string($actyp) . "' 
and a.app_id=c.app_id
and b.msg_id = c.msg_id
and b.routing_id=$routingID";
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function executeActionOnMsg($sqlStr)
{
    if ($sqlStr != "") {
        $result = executeSQLNoParams($sqlStr);
        while ($row = loc_db_fetch_array($result)) {
            return $row[0];
        }
    } else {
        return "|ERROR|No SQL";
    }
}

function getSlogan()
{
    global $org_name;
    global $app_slogan;
    global $orgID;
    if ($orgID !== -1) {
        echo getOrgSlogan($orgID) . "               " . getFrmtdDB_Date_time();
    } else {
        echo "$app_slogan" . getFrmtdDB_Date_time();
    }
}

/* function getShapes() {
  global $org_name;
  global $app_image;
  global $orgID;
  global $pemDest;
  global $orgID;
  GLOBAL $ftp_base_db_fldr;

  if ($orgID > 0) {
  $img_src = $pemDest . "$orgID.png";
  $ftp_src = $ftp_base_db_fldr . "/Org/$orgID.png";
  if (file_exists($ftp_src) && !is_dir($ftp_src)) {
  copy("$ftp_src", "$img_src");
  }
  echo "<img src=\"$img_src\" style=\"left: 0.5%; margin:2px; padding-right: 1em; height:65px; width:auto; position: relative; vertical-align: middle;\"/>";
  } else {
  echo "<img src=\"cmn_images/$app_image\" style=\"left: 0.5%; margin:2px; padding-right: 1em; height:65px; width:auto; position: relative; vertical-align: middle;\"/>";
  }
  } */

function getHeadline()
{
    global $org_name;
    global $orgID;
    global $app_name;
    if ($orgID !== -1) {
        echo $org_name;
    } else {
        echo "$app_name";
    }
}

function getRptDrctry()
{
    $sqlStr = "select pssbl_value from gst.gen_stp_lov_values where ((value_list_id = " .
        getLovID("Reports Directory") . ") AND (is_enabled='1')) ORDER BY pssbl_value_id DESC LIMIT 1";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getRptID($rptname)
{
    $sqlStr = "select report_id from rpt.rpt_reports where report_name = '" .
        loc_db_escape_string($rptname) . "'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getAlertID($alrtname)
{
    $sqlStr = "select alert_id from alrt.alrt_alerts where alert_name = '" .
        loc_db_escape_string($alrtname) . "'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getParamIDUseSQLRep($paramSQLRep, $rptID)
{
    $sqlStr = "SELECT parameter_id
  FROM rpt.rpt_report_parameters WHERE paramtr_rprstn_nm_in_query = '" .
        loc_db_escape_string($paramSQLRep) . "' and report_id=" . $rptID;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getParamID($paramNm, $rptID)
{
    $sqlStr = "SELECT parameter_id, report_id, parameter_name, paramtr_rprstn_nm_in_query, 
       created_by, creation_date, last_update_by, last_update_date, 
       default_value, is_required, lov_name_id, param_data_type, date_format, 
       lov_name
  FROM rpt.rpt_report_parameters WHERE parameter_name = '" .
        loc_db_escape_string($paramNm) . "' and report_id=" . $rptID;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getAlrtParamID($alert_id, $paramID)
{
    $sqlStr = "SELECT schdl_param_id 
  FROM rpt.rpt_run_schdule_params WHERE alert_id = " . $alert_id . " and parameter_id=" . $paramID;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function recurse_copy($src, $dst)
{
    $dir = opendir($src);
    if (!file_exists($dst)) {
        @mkdir($dst);
    }
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                recurse_copy($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

function get_string_between($string, $start, $end)
{
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) {
        return '';
    }
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

function validateDateTme($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function storeLogoutTime($lgn_num)
{
    $dateStr = getDB_Date_time();
    $sqlStr = "UPDATE sec.sec_track_user_logins SET logout_time = '" . $dateStr .
        "' WHERE (login_number = " . $lgn_num . ")";
    $result = executeSQLNoParams($sqlStr);
}

function logoutActions()
{
    global $usrID;
    global $orgID;
    global $lgn_num;

    $usrID = $_SESSION['USRID'];
    $orgID = $_SESSION['ORG_ID'];
    $lgn_num = $_SESSION['LGN_NUM'];
    storeLogoutTime($lgn_num);
    destroySession();
}

function get_Module_Rpts(
    $searchWord,
    $searchIn,
    $offset,
    $limit_size,
    $orderLvl,
    $mdlnm = "Generic Module",
    $rptID = -1,
    $includeGnrc = "1",
    $subClsfctn = "",
    $mdlnm2 = "Unknown Module"
) {
    $strSql = "";
    $extrWhr1 = "";
    $extrWhr3 = "";
    $extrWhr2 = " and (a.owner_module ilike '" . loc_db_escape_string($mdlnm) .
        "' or a.owner_module ilike '" . loc_db_escape_string($mdlnm2) .
        "') and (a.report_desc ilike '" . loc_db_escape_string($subClsfctn) . "%')";
    if ($includeGnrc == "1") {
        $extrWhr2 = " and (a.owner_module ilike '" . loc_db_escape_string($mdlnm) . "' or a.owner_module ilike '" . loc_db_escape_string($mdlnm2) .
            "' or a.owner_module ilike 'Generic Module')";
    }
    if ($mdlnm == "Self Service") {
        $extrWhr3 = " or (a.owner_module ilike '" . loc_db_escape_string($mdlnm) . "')";
    }
    if ($rptID > 0) {
        $extrWhr1 = " and a.report_id=" . $rptID;
    }
    $orderBy = "ORDER BY a.report_id DESC";
    if ($orderLvl == "ID DESC") {
        $orderBy = "ORDER BY a.report_id DESC";
    } else if ($orderLvl == "NAME ASC") {
        $orderBy = "ORDER BY a.report_name";
    } else if ($orderLvl == "OWNER MODULE, NAME ASC") {
        $orderBy = "ORDER BY a.owner_module, a.report_name";
    }
    $whereClause = "";
    if ($searchIn == "Report Name") {
        $whereClause = " and (a.report_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Report Description") {
        $whereClause = " and (a.report_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Owner Module") {
        $whereClause = " and (a.owner_module ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT distinct a.report_id, a.report_name, a.report_desc, a.rpt_sql_query, " .
        "a.owner_module, a.rpt_or_sys_prcs, a.is_enabled, a.cols_to_group, a.cols_to_count, " .
        "a.cols_to_sum, a.cols_to_average, a.cols_to_no_frmt, a.output_type, a.portrait_lndscp, 
             a.process_runner , a.rpt_layout, a.imgs_col_nos, a.csv_delimiter
      FROM rpt.rpt_reports a LEFT OUTER JOIN rpt.rpt_reports_allwd_roles b ON (a.report_id = b.report_id) " .
        "WHERE ((b.user_role_id IN (" . concatCurRoleIDs() . ")" . $extrWhr3 . ")"
        . $extrWhr1 . $extrWhr2 . $whereClause . ") " . $orderBy .
        " LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    //echo $strSql;
    return $result;
}

function get_Module_RptsTtl(
    $searchWord,
    $searchIn,
    $mdlnm = "Generic Module",
    $rptID = -1,
    $includeGnrc = "1",
    $subClsfctn = "",
    $mdlnm2 = "Unknown Module"
) {
    $strSql = "";
    $extrWhr1 = "";
    $extrWhr3 = "";
    $extrWhr2 = " and (a.owner_module ilike '" . loc_db_escape_string($mdlnm) .
        "' or a.owner_module ilike '" . loc_db_escape_string($mdlnm2) .
        "') and (a.report_desc ilike '" . loc_db_escape_string($subClsfctn) . "%')";
    if ($includeGnrc == "1") {
        $extrWhr2 = " and (a.owner_module ilike '" . loc_db_escape_string($mdlnm) . "' or a.owner_module ilike '" . loc_db_escape_string($mdlnm2) .
            "' or a.owner_module ilike 'Generic Module')";
    }
    if ($mdlnm == "Self Service") {
        $extrWhr3 = " or (a.owner_module ilike '" . loc_db_escape_string($mdlnm) . "')";
    }
    if ($rptID > 0) {
        $extrWhr1 = " and a.report_id=" . $rptID;
    }
    $whereClause = "";
    if ($searchIn == "Report Name") {
        $whereClause = " and (a.report_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Report Description") {
        $whereClause = " and (a.report_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Owner Module") {
        $whereClause = " and (a.owner_module ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT count(distinct a.report_id)
      FROM rpt.rpt_reports a LEFT OUTER JOIN rpt.rpt_reports_allwd_roles b ON (a.report_id = b.report_id) " .
        "WHERE ((b.user_role_id IN (" . concatCurRoleIDs() . ")" . $extrWhr3 . ")"
        . $extrWhr1 . $extrWhr2 . $whereClause . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

/**
 * PROSPECTIVE MEMBER GLOBAL FUNCTIONS
 */
function getUserIDPM($username)
{
    //echo "uname_".$username;
    $sqlStr = "select user_id from prspt.prspt_user where 
        lower(user_name)=lower('" . loc_db_escape_string($username) . "')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getUserNamePM($userid)
{
    //echo "uname_".$username;
    $sqlStr = "select user_name from prspt.prspt_user where 
        user_id= $userid";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getUserPrsnIDPM($username)
{
    $sqlStr = "select person_id from prspt.prspt_user where 
        lower(user_name)=lower('" . loc_db_escape_string($username) . "')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getUserPrsnID1PM($userID)
{
    $sqlStr = "select person_id from prspt.prspt_user where 
        user_id=$userID";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getPrsnFullNmPM($prsnID)
{
    $sqlStr = "select trim(title || ' ' || first_name || ' ' || middle_name || ' ' || last_name) from prspt.prspt_person_info where 
        person_id=" . $prsnID . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getPssblValDescPM($pssblVlNm, $VlstNm)
{
    $sqlStr = "select pssbl_value_desc
from gst.gen_stp_lov_names a, gst.gen_stp_lov_values b
where ((a.value_list_id = b.value_list_id
and value_list_name = '" . loc_db_escape_string($VlstNm) . "'
and pssbl_value = '" . loc_db_escape_string($pssblVlNm) . "'))";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getStartOfDayYMD()
{
    global $orgID;

    $sqlStr = "SELECT mcf.xx_get_start_of_day_date($orgID)||' '||mcf.get_crnt_time()";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    //return date('Y-m-d');
}

function getStartOfDayDMYHMS()
{
    global $orgID;

    $sqlStr = "SELECT to_char(to_timestamp(mcf.xx_get_start_of_day_date($orgID),'YYYY-MM-DD'),'DD-Mon-YYYY')||' '||mcf.get_crnt_time()";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
}

function getStartOfDayYMDHMS()
{
    global $orgID;

    $sqlStr = "SELECT mcf.xx_get_start_of_day_date($orgID)||' '||mcf.get_crnt_time()";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    //return date('Y-m-d H:i:s');
}

function getStartOfDayDmY()
{
    global $orgID;
    $sqlStr = "SELECT distinct max(to_char(to_timestamp(start_of_day_date,'YYYY-MM-DD'),'DD-Mon-YYYY')) FROM mcf.mcf_cob_trns_records WHERE org_id = $orgID";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return date('d-M-Y');
}

function getTrnsNotAllwdDates()
{
    $dts = array();
    $i = 0;

    $sqlStr = "SELECT distinct pssbl_value
    from gst.gen_stp_lov_names a, gst.gen_stp_lov_values b
    where ((a.value_list_id = b.value_list_id
    AND b.is_enabled = '1'
    AND value_list_name = 'Transactions not Allowed Dates'))";

    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        $dts[$i] = $row[0];
        $i = $i + 1;
    }

    return $dts;
}

function getTrnsNotAllwdDays()
{
    $dys = array();
    $i = 0;

    $sqlStr = "SELECT distinct pssbl_value
    from gst.gen_stp_lov_names a, gst.gen_stp_lov_values b
    where ((a.value_list_id = b.value_list_id
    AND b.is_enabled = '1'
    AND value_list_name = 'Transactions not Allowed Days'))";

    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        $dys[$i] = $row[0];
        $i = $i + 1;
    }

    return $dys;
}

function getFullAcntType($shrtcde)
{
    $fullTypes = array("A -ASSET", "L -LIABILITY", "EQ-EQUITY", "R -REVENUE", "EX-EXPENSE");
    for ($i = 0; $i < count($fullTypes); $i++) {
        if (trim(substr($fullTypes[$i], 0, 2)) == $shrtcde) {
            return $fullTypes[$i];
        }
    }
    return "";
}

function cnvrtBitStrToYN($bitstr)
{
    if ($bitstr == "1") {
        return "YES";
    }
    return "NO";
}

function cnvrtYNToBool($yesno)
{
    if (strtoupper($yesno) == "YES") {
        return true;
    } else {
        return false;
    }
}

function getTotalAllwdExtInf($searchWord, $searchIn, $tblID, $row_id_val, $valTbl)
{
    global $orgID;
    $strSql = "";
    $whrCls = "";

    if ($searchIn == "Value") {
        $whrCls = " AND (tbl1.othr_inf ilike '" . loc_db_escape_string($searchWord) . "' or tbl1.othr_inf IS NULL) ";
    } else if ($searchIn == "Extra Info Label") {
        $whrCls = " AND (tbl1.other_info_label ilike '" .
            loc_db_escape_string($searchWord) . "' or tbl1.other_info_category ilike '" .
            loc_db_escape_string($searchWord) . "') ";
    }
    $strSql = "SELECT count(1) FROM (SELECT b.pssbl_value other_info_category, 
          COALESCE((select c.other_info_label from " . $valTbl . " c " .
        "where ((c.tbl_othr_inf_combntn_id = a.comb_info_id) AND (c.row_pk_id_val = " . $row_id_val . "))), b.pssbl_value) other_info_label, 
         COALESCE((select c.other_info_value from " . $valTbl . " c " .
        "where ((c.tbl_othr_inf_combntn_id = a.comb_info_id) AND (c.row_pk_id_val = " . $row_id_val . "))),'') othr_inf, " .
        "a.comb_info_id, a.table_id, COALESCE((select c.dflt_row_id from " . $valTbl . " c " .
        "where ((c.tbl_othr_inf_combntn_id = a.comb_info_id) AND (c.row_pk_id_val = " . $row_id_val . "))),-1) othr_inf_row_id " .
        "FROM sec.sec_allwd_other_infos a " .
        "LEFT OUTER JOIN gst.gen_stp_lov_values b ON (a.other_info_id = b.pssbl_value_id) " .
        "WHERE((a.is_enabled = '1')  AND (a.table_id = " . $tblID . ") AND (b.allowed_org_ids like '%," . $orgID . ",%') AND (((select c.other_info_value from " . $valTbl . " c " .
        "where ((c.tbl_othr_inf_combntn_id = a.comb_info_id) AND (c.row_pk_id_val = " . $row_id_val . "))) ilike '" .
        loc_db_escape_string($searchWord) . "') OR ((select c.other_info_value from " . $valTbl . " c " .
        "where ((c.tbl_othr_inf_combntn_id = a.comb_info_id) AND (c.row_pk_id_val = " . $row_id_val . "))) is null))) " .
        " UNION 
                  SELECT c.other_info_category, c.other_info_label, c.other_info_value othr_inf, 99999999 comb_info_id, -1 table_id, c.dflt_row_id from " . $valTbl .
        " c  WHERE c.tbl_othr_inf_combntn_id<=0 and c.row_pk_id_val = " . $row_id_val . ") tbl1 WHERE 1=1" . $whrCls;

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function getAllwdExtInfosNVals($searchWord, $searchIn, $offset, $limit_size, &$brghtsqlStr, $tblID, $row_id_val, $valTbl, $Org_id = -1)
{
    global $orgID;
    $strSql = "";
    $whrCls = "";

    if ($searchIn == "Value") {
        $whrCls = " AND (tbl1.othr_inf ilike '" .
            loc_db_escape_string($searchWord) . "' or tbl1.othr_inf IS NULL) ";
    } else if ($searchIn == "Extra Info Label") {
        $whrCls = " AND (tbl1.other_info_label ilike '" .
            loc_db_escape_string($searchWord) .
            "' or tbl1.other_info_category ilike '" . loc_db_escape_string($searchWord) . "') ";
    }
    $strSql = "SELECT tbl1.* FROM (SELECT b.pssbl_value other_info_category, 
          COALESCE((select c.other_info_label from " . $valTbl . " c " .
        "where ((c.tbl_othr_inf_combntn_id = a.comb_info_id) AND (c.row_pk_id_val = " . $row_id_val . "))), b.pssbl_value) other_info_label, 
         COALESCE((select c.other_info_value from " . $valTbl . " c " .
        "where ((c.tbl_othr_inf_combntn_id = a.comb_info_id) AND (c.row_pk_id_val = " . $row_id_val . "))),'') othr_inf, " .
        "a.comb_info_id, a.table_id, COALESCE((select c.dflt_row_id from " . $valTbl . " c " .
        "where ((c.tbl_othr_inf_combntn_id = a.comb_info_id) AND (c.row_pk_id_val = " . $row_id_val . "))),-1) othr_inf_row_id " .
        "FROM sec.sec_allwd_other_infos a " .
        "LEFT OUTER JOIN gst.gen_stp_lov_values b ON (a.other_info_id = b.pssbl_value_id) " .
        "WHERE((a.is_enabled = '1')  AND (a.table_id = " . $tblID . ") AND (b.allowed_org_ids like '%," . $orgID .
        ",%') AND (((select c.other_info_value from " . $valTbl . " c " .
        "where ((c.tbl_othr_inf_combntn_id = a.comb_info_id) AND (c.row_pk_id_val = " . $row_id_val . "))) ilike '" .
        loc_db_escape_string($searchWord) . "') OR ((select c.other_info_value from " . $valTbl . " c " .
        "where ((c.tbl_othr_inf_combntn_id = a.comb_info_id) AND (c.row_pk_id_val = " . $row_id_val . "))) is null))) " .
        " UNION 
                  SELECT c.other_info_category, c.other_info_label, c.other_info_value othr_inf, 99999999 comb_info_id, -1 table_id, c.dflt_row_id from " . $valTbl .
        " c  WHERE c.tbl_othr_inf_combntn_id<=0 and c.row_pk_id_val = " . $row_id_val . ") tbl1 WHERE 1=1" . $whrCls .
        " ORDER BY tbl1.comb_info_id LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);

    $result = executeSQLNoParams($strSql);
    $brghtsqlStr = $strSql;
    //echo $strSql;
    return $result;
}

function getOneExtInfosNVals($tblID, $row_id_val, $valTbl, $psblVal)
{
    global $orgID;
    $strSql = "SELECT tbl1.* FROM (SELECT b.pssbl_value other_info_category, 
          COALESCE((select c.other_info_label from " . $valTbl . " c " .
        "where ((c.tbl_othr_inf_combntn_id = a.comb_info_id) AND (c.row_pk_id_val = " . $row_id_val . "))), b.pssbl_value) other_info_label, 
(select c.other_info_value from " . $valTbl . " c " .
        "where ((c.tbl_othr_inf_combntn_id = a.comb_info_id) AND (c.row_pk_id_val = " . $row_id_val . "))) " .
        "othr_inf, a.comb_info_id, a.table_id, (select c.dflt_row_id from " . $valTbl . " c " .
        "where ((c.tbl_othr_inf_combntn_id = a.comb_info_id) AND (c.row_pk_id_val = " . $row_id_val . "))) " .
        "othr_inf_row_id " .
        "FROM sec.sec_allwd_other_infos a " .
        "LEFT OUTER JOIN gst.gen_stp_lov_values b ON (a.other_info_id = b.pssbl_value_id) " .
        "WHERE((a.is_enabled = '1')  AND (a.table_id = " . $tblID . ") AND (b.allowed_org_ids like '%," . $orgID . ",%')) " .
        " UNION 
                  SELECT c.other_info_category, c.other_info_label, c.other_info_value othr_inf, 99999999 comb_info_id, -1 table_id, c.dflt_row_id from " . $valTbl .
        " c  WHERE c.tbl_othr_inf_combntn_id<=0 and c.row_pk_id_val = " . $row_id_val . ") tbl1 WHERE tbl1.other_info_label='" . loc_db_escape_string($psblVal) . "'" .
        " ORDER BY tbl1.comb_info_id ";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[1];
    }
    return "";
}

function getMainTableNm($tblID)
{
    $strSql = "SELECT main_table_name " .
        "FROM sec.sec_module_sub_groups WHERE (table_id = " . $tblID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getMainTableColNm($tblID)
{
    $strSql = "SELECT row_pk_col_name " .
        "FROM sec.sec_module_sub_groups WHERE (table_id = " . $tblID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function doesRowHvOthrInfo($valTbl, $cmbntnID, $rowValID)
{
    $strSql = "SELECT a.dflt_row_id FROM " . $valTbl . " " .
        "a WHERE((a.tbl_othr_inf_combntn_id = " . $cmbntnID . " and a.tbl_othr_inf_combntn_id>0) AND (a.row_pk_id_val = " . $rowValID . "))";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function getRowOthrInfoVal($dfltRowID, $valTbl)
{
    $strSql = "SELECT a.other_info_value FROM " . $valTbl . " " .
        "a WHERE((a.dflt_row_id = " . $dfltRowID . "))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function updateRowOthrInfVal($valTbl, $cmbntnID, $rowValID, $othrInfVal, $othInfLbl, $othrInfCtgry, $rowID)
{
    global $usrID;
    $updtStr = "UPDATE " . $valTbl . " SET " .
        "other_info_value = '" . loc_db_escape_string($othrInfVal) . "', " .
        "other_info_label = '" . loc_db_escape_string($othInfLbl) . "', " .
        "other_info_category = '" . loc_db_escape_string($othrInfCtgry) . "', " .
        "last_update_by = " . $usrID . ", last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS') " .
        "WHERE (((tbl_othr_inf_combntn_id = " . $cmbntnID .
        " and tbl_othr_inf_combntn_id>0) or (dflt_row_id = " . $rowID .
        ")) AND (row_pk_id_val = " . $rowValID . "))";
    return execUpdtInsSQL($updtStr);
}

function deleteRowOthrInfVal($extInfoID, $valTbl)
{
    $delSQL = "DELETE FROM " . loc_db_escape_string($valTbl) . " WHERE dflt_row_id = " . $extInfoID;
    return execUpdtInsSQL($delSQL);
}

function getNewExtInfoID($extInfSeq)
{
    $strSql = "select nextval('" . loc_db_escape_string($extInfSeq) . "')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createRowOthrInfVal($valTbl, $cmbntnID, $rowValID, $othrInfVal, $othInfLbl, $othrInfCtgry, $rowID)
{
    global $usrID;
    $sqlStr = "INSERT INTO " . loc_db_escape_string($valTbl) . " (" .
        "dflt_row_id, tbl_othr_inf_combntn_id, row_pk_id_val, other_info_value, " .
        "created_by, creation_date, last_update_by, last_update_date, " .
        "other_info_label, other_info_category) " .
        "VALUES (" . $rowID . ", " . $cmbntnID . ", " . $rowValID . ", '" . loc_db_escape_string($othrInfVal) .
        "', " . $usrID . ", " .
        "to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($othInfLbl) .
        "', '" . loc_db_escape_string($othrInfCtgry) . "')";
    return execUpdtInsSQL($sqlStr);
}

/**
 * PROSPECTIVE MEMBER VARIABLES
 */
$usrIDPM = $_SESSION['USRIDPM'];
$user_NamePM = $_SESSION['UNAMEPM'];
$prsnidPM = $_SESSION['PRSN_ID_PM'];

/**
 * END PROSPECTIVE MEMBER VARIABLES
 */
$usrID = $_SESSION['USRID'];
$user_Name = $_SESSION['UNAME'];
$orgID = $_SESSION['ORG_ID'];
$lgn_num = $_SESSION['LGN_NUM'];
$ifrmeSrc = $_SESSION['CUR_IFRM_SRC'];
$prsnid = $_SESSION['PRSN_ID'];
$fullNm = $_SESSION['PRSN_FNAME'];

/*CLINIC/HOSPITAL*/
function getAccbPgPrmssns($orgid)
{
    global $ssnRoles;
    $mdlNm = "Accounting";
    $rslts = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $sqlStr = "Select (select oprtnl_crncy_id from org.org_details where org_id = $orgid), "
        . "sec.test_prmssns('View Accounting', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "')+sec.test_prmssns('View Inventory Manager', 'Stores And Inventory Manager','" . loc_db_escape_string($ssnRoles) . "') vwAccntng, "
        . "sec.test_prmssns('View Chart of Accounts', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Account Transactions', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Petty Cash Vouchers', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Transactions Search', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Financial Statements', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Budgets', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Transaction Templates', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Accounting Periods', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Fixed Assets', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Payables', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Receivables', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Payments', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "')+sec.test_prmssns('View Inventory Manager', 'Stores And Inventory Manager','" . loc_db_escape_string($ssnRoles) . "') vwPymnts, "
        . "sec.test_prmssns('View Customers/Suppliers', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Tax Codes', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Default Accounts', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Account Reconciliation', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Record History', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View SQL', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('View Only Self-Created Transaction Batches', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Setup Exchange Rates', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Setup Document Templates', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Edit Journal Entries(Debit/Credit)', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Edit Journal Entries(Increase/Decrease)', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Edit Simplified Double Entries', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Post Transactions', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "')";
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
        $rslts[19] = ((int) $row[19]);
        $rslts[20] = ((int) $row[20]);
        $rslts[21] = ((int) $row[21]);
        $rslts[22] = ((int) $row[22]);
        $rslts[23] = ((int) $row[23]);
        $rslts[24] = ((int) $row[24]);
        $rslts[25] = ((int) $row[25]);
        $rslts[26] = ((int) $row[26]);
    }
    return $rslts;
}

function createCstmr(
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

function updateCstmr(
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

function getCstmrID($cstmrNm, $orgid)
{
    $sqlStr = "select cust_sup_id from scm.scm_cstmr_suplr where lower(cust_sup_name) = '" .
        loc_db_escape_string(strtolower($cstmrNm)) . "' and org_id=" . $orgid;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createCstmrSite(
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

function updateCstmrSite(
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

function getLnkdPrsnCstmrID($lnkd_prsn_id, $orgid)
{
    $sqlStr = "select cust_sup_id from scm.scm_cstmr_suplr where lnkd_prsn_id = $lnkd_prsn_id and org_id = $orgid LIMIT 1";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}
/*CLINIC/HOSPITAL*/
